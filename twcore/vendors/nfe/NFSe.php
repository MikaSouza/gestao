<?php

    class NFSe extends TWSet
    {
        //Atributos para a confecção da nota
        public $id;
        public $quantidade_rps;
        public $valor;
        public $pis;
        public $cofins;
        public $ir;
        public $csll;
        public $iss;
        public $discriminacao;
        public $tomador;
        public $empresa;

        //Atributos da nota
        public $protocolo;
        public $verificacao;
        public $numero;
        public $data;

        //O construtor será responsável por registrar a nota no banco de dados e recuperar o sequencial
        public function __construct($ctrcodigo)
        {
            $find = consultaComposta(array(
                'query' => 'SELECT
                                NFSCODIGO,
                                NFSPROTOCOLO,
                                NFSNUMERO,
                                NFSPOSICAO,
                                NFSVERIFICACAO,
                                NFSDATA_INC
                            FROM
                                NOTASFISCAIS
                            WHERE
                                CTRCODIGO = ?',
                'parametros' => array(
                    array($ctrcodigo, PDO::PARAM_INT),
                ),
            ));

            if ($find['quantidadeRegistros'] > 0) {
                $dados = $find['dados'][0];
                $this->id          = $dados['NFSCODIGO'];
                $this->numero      = $dados['NFSNUMERO'];
                $this->verificacao = $dados['NFSVERIFICACAO'];
                $this->data        = $dados['NFSDATA_INC'];
                $this->protocolo   = ($dados['NFSPOSICAO'] == 3) ? $dados['NFSPROTOCOLO'] : '';
            } else {
                $this->id = insertUpdate(array(
                    'tabela'  => 'NOTASFISCAIS',
                    'prefixo' => 'NFS',
                    'msg'     => 'N',
                    'url'     => '',
                    'debug'   => 'N',
                    'fields'  => array(
                        'vICTRCODIGO'  => $ctrcodigo,
                        'vINFSPOSICAO' => 1,
                    ),
                ));
            }
        }


        //Método responsável por realizar o cálculo dos impostos
        public function calculate()
        {
            if ($this->empresa->simples || $this->tomador->tipo == 'F') {
                $this->ir     = '0.00';
                $this->pis    = '0.00';
                $this->cofins = '0.00';
                $this->csll   = '0.00';
                $this->iss    = '0.00';
                return;
            }

            $this->iss = $this->valor*0.05;

            if ($this->valor >= 680) {
                $this->ir = $this->empresa->calculate_ir ? (float) $this->Valor*1.5/100 : '0.00';
            }

            if ($this->valor >= 215.06) {
                $this->pis    = $this->empresa->calculate_pis ? (float) $this->valor*0.65/100 : '0.00';
                $this->cofins = $this->empresa->calculate_cofins ? (float) $this->valor*3.00/100 : '0.00';
                $this->csll   = $this->empresa->calculate_csll ? (float) $this->valor*1.00/100 : '0.00';
            }
        }

        //Método responsável por criar o XML da nota
        public function build()
        {
            try {
                if ($this->protocolo != '') {
                    throw new Exception("A NFSe para esta fatura já foi emitida!");
                }
                //Cria o DOM para o XML
                $dom = new DOMDocument('1.0');
                $dom->formatOutput = true;
                $dom->preserveWhiteSpace = false;
                $dom->encoding = 'UTF-8';

                //Cria um nodo para o lote de rps
                $NFe = $dom->createElement('EnviarLoteRpsEnvio');
                $NFe->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
                $dom->appendChild($NFe);

                //Cria um nodo de lote de rps
                $LoteRps = $dom->createElement('LoteRps');
                $LoteRps->setAttribute('Id', 'lote');
                $LoteRps->setAttribute('versao', '1.00');
                $LoteRps->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
                $NFe->appendChild($LoteRps);

                //Seta o número do lote de rps
                $NumeroLote = $dom->createElement('NumeroLote', $this->id);
                $LoteRps->appendChild($NumeroLote);

                //Seta o cnpj da empresa
                $Cnpj = $dom->createElement('Cnpj', $this->empresa->cnpj);
                $LoteRps->appendChild($Cnpj);

                //Seta a inscrição municipal da empresa
                $InscricaoMunicipal = $dom->createElement("InscricaoMunicipal", $this->empresa->inscricao_municipal);
                $LoteRps->appendChild($InscricaoMunicipal);

                //Seta a quantidade RPS
                $QuantidadeRps = $dom->createElement("QuantidadeRps", $this->quantidade_rps);
                $LoteRps->appendChild($QuantidadeRps);

                //Identificação da lista RPS
                $ListaRps = $dom->createElement("ListaRps");
                $LoteRps->appendChild($ListaRps);

                //Rps
                $Rps = $dom->createElement("Rps");
                $Rps->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");
                $ListaRps->appendChild($Rps);

                //InfRps
                $InfRps = $dom->createElement("InfRps");
                $InfRps->setAttribute("Id", "rps:{$this->id}ABCDZ");
                $InfRps->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");
                $Rps->appendChild($InfRps);

                //Identificação da Rps individual
                $IdentificacaoRps = $dom->createElement("IdentificacaoRps");
                $InfRps->appendChild($IdentificacaoRps);

                //Número da RPS
                $Numero = $dom->createElement("Numero", $this->id);
                $IdentificacaoRps->appendChild($Numero);

                //Série da RPS
                $Serie = $dom->createElement("Serie", 'ABCDZ');
                $IdentificacaoRps->appendChild($Serie);

                //Tipo da RPS
                $Tipo = $dom->createElement("Tipo", 1);
                $IdentificacaoRps->appendChild($Tipo);

                //Data de Emissão
                $emissao = date('Y-m-d').'T'.date('H:i:s');
                $DataEmissao = $dom->createElement("DataEmissao", $emissao);
                $InfRps->appendChild($DataEmissao);

                //Natureza da Operação
                $NaturezaOperacao = $dom->createElement("NaturezaOperacao", 1);
                $InfRps->appendChild($NaturezaOperacao);

                //Regime Especial Tributação
                if (!is_null($this->regime_tributacao)) {
                    $RegimeEspecialTributacao = $dom->createElement("RegimeEspecialTributacao", $this->empresa->regime_tributacao);
                    $InfRps->appendChild($RegimeEspecialTributacao);
                }

                //Optante pelo Simples Nacional
                $simples = $this->empresa->simples ? 1 : 2;
                $OptanteSimplesNacional = $dom->createElement("OptanteSimplesNacional", $simples);
                $InfRps->appendChild($OptanteSimplesNacional);

                //Incentivador Cultural
                $IncentivadorCultural = $dom->createElement("IncentivadorCultural", $this->empresa->incentivadorCultural);
                $InfRps->appendChild($IncentivadorCultural);

                //Status
                $Status = $dom->createElement("Status", 1);
                $InfRps->appendChild($Status);

                //Identificação do serviço
                $Servico = $dom->createElement("Servico");
                $InfRps->appendChild($Servico);

                //Valores
                $Valores = $dom->createElement("Valores");
                $Servico->appendChild($Valores);

                //Valor da Nota
                $ValorServicos = $dom->createElement("ValorServicos", number_format($this->valor, 2, '.', ''));
                $Valores->appendChild($ValorServicos);

                //Valor Deduções
                $ValorDeducoes = $dom->createElement("ValorDeducoes", '0.00');
                $Valores->appendChild($ValorDeducoes);

                //Valor PIS
                $ValorPis = $dom->createElement("ValorPis", $this->pis);
                $Valores->appendChild($ValorPis);

                //Valor COFINS
                $ValorCofins = $dom->createElement("ValorCofins", $this->cofins);
                $Valores->appendChild($ValorCofins);

                //Valor INSS
                $ValorCofins = $dom->createElement("ValorInss", '0.00');
                $Valores->appendChild($ValorCofins);

                //Valor IR
                $ValorIr = $dom->createElement("ValorIr", $this->ir);
                $Valores->appendChild($ValorIr);

                //Valor Csll
                $ValorCsll = $dom->createElement("ValorCsll", $this->csll);
                $Valores->appendChild($ValorCsll);

                //ISS Retido
                $IssRetido = $dom->createElement("IssRetido", 2);
                $Valores->appendChild($IssRetido);

                //Código do servico prestado. Item da LC 116/2003
                $ItemListaServico = $dom->createElement("ItemListaServico", $this->empresa->codigo_servico);
                $Servico->appendChild($ItemListaServico);

                //Código Tributação Município
                $CodigoTributacaoMunicipio = $dom->createElement("CodigoTributacaoMunicipio", $this->empresa->codigo_tributacao_municipio);
                $Servico->appendChild($CodigoTributacaoMunicipio);

                //Discriminação
                $Discriminacao = $dom->createElement("Discriminacao", $this->discriminacao);
                $Servico->appendChild($Discriminacao);

                //Codigo Municipio (IBGE)
                $CodigoMunicipio = $dom->createElement("CodigoMunicipio", $this->empresa->codigo_municipio);
                $Servico->appendChild($CodigoMunicipio);

                //Identificação do prestador do serviço
                $PrestadorServico = $dom->createElement("Prestador");
                $InfRps->appendChild($PrestadorServico);

                //CNPJ
                $CNPJPrestador = $dom->createElement("Cnpj", $this->empresa->cnpj);
                $PrestadorServico->appendChild($CNPJPrestador);

                //Inscrição Municipal
                $InscricaoMunicipal = $dom->createElement("InscricaoMunicipal", $this->empresa->inscricao_municipal);
                $PrestadorServico->appendChild($InscricaoMunicipal);

                //Identificação do tomador do serviço
                $TomadorServico = $dom->createElement("Tomador");
                $InfRps->appendChild($TomadorServico);

                $IdentificacaoTomador = $dom->createElement("IdentificacaoTomador");
                $TomadorServico->appendChild($IdentificacaoTomador);

                //CPF/CNPJ
                $CpfCnpj = $dom->createElement("CpfCnpj");
                $IdentificacaoTomador->appendChild($CpfCnpj);

                if ($this->tomador->tipo == 'F') {
                    $documento = $dom->createElement("Cpf", $this->tomador->cpf_cnpj);
                } else {
                    $documento = $dom->createElement("Cnpj", $this->tomador->cpf_cnpj);
                }
                $CpfCnpj->appendChild($documento);

                //Razão Social
                $RazaoSocial = $dom->createElement("RazaoSocial", $this->tomador->razao_social);
                $TomadorServico->appendChild($RazaoSocial);

                //Endereço
                $Endereco = $dom->createElement("Endereco");
                $TomadorServico->appendChild($Endereco);

                //Logradouro
                $EnderecoTomador = $dom->createElement("Endereco", $this->tomador->endereco->logradouro);
                $Endereco->appendChild($EnderecoTomador);

                //Número
                $NumeroTomador = $dom->createElement("Numero", $this->tomador->endereco->numero);
                $Endereco->appendChild($NumeroTomador);

                //Complemento
                $Complemento = $dom->createElement("Complemento", $this->tomador->endereco->complemento);
                $Endereco->appendChild($Complemento);

                //Bairro
                $BairroTomador = $dom->createElement("Bairro", $this->tomador->endereco->bairro);
                $Endereco->appendChild($BairroTomador);

                //Cidade
                $CidadeTomador = $dom->createElement("CodigoMunicipio", $this->tomador->endereco->cidade);
                $Endereco->appendChild($CidadeTomador);

                //Estado
                $EstadoTomador = $dom->createElement("Uf", $this->tomador->endereco->uf);
                $Endereco->appendChild($EstadoTomador);

                //CEP
                $CepTomador = $dom->createElement("Cep", $this->tomador->endereco->cep);
                $Endereco->appendChild($CepTomador);

                //Contato
                $ContatoTomador = $dom->createElement("Contato");
                $TomadorServico->appendChild($ContatoTomador);

                //Telefone
                $Telefone = $dom->createElement("Telefone", $this->tomador->telefone);
                $ContatoTomador->appendChild($Telefone);

                //Email
                $Email = $dom->createElement("Email", $this->tomador->email);
                $ContatoTomador->appendChild($Email);

                if (!$dom->save(__DIR__.'/xml/envio/'.$this->id.'.xml')) {
                    throw new Exception("Não foi possível criar o xml");
                }
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
                return false;
            }

            return is_file(__DIR__.'/xml/envio/'.$this->id.'.xml');
        }

        //Método responsável por assinar o certificado
        public function sign()
        {
            try {
                //Verifica se o arquivo XML existe
                if (!is_file(__DIR__.'/xml/envio/'.$this->id.'.xml')) {
                    throw new Exception("Não foi possível encontrar o arquivo xml");
                }

                //Instancia as ferramentas
                require_once __DIR__.'/nfephp-master/libs/NFe/ToolsNFePHP.class.php';
                $tools = new ToolsNFePHP;

                //Seta as chaves
                $tools->setPublicKey(__DIR__.'/certificados/'.$this->empresa->id.'/public.pem');
                $tools->setPrivateKey(__DIR__.'/certificados/'.$this->empresa->id.'/private.pem');

                //Recupera o xml gerado pelo metodo build
                $nfefile = utf8_encode(file_get_contents(__DIR__.'/xml/envio/'.$this->id.'.xml'));
                //Assina o xml
                $xmlAssinado = $tools->signXML($nfefile, 'InfRps', 'Rps', $this->id."ABCDZ");
                $xmlAssinado = $tools->signXML($xmlAssinado, 'LoteRps', 'EnviarLoteRpsEnvio', $this->id."ABCDZ");

                //Salva o xml assinado
                $xml = fopen(__DIR__.'/xml/envio/'.$this->id.'.xml', 'wb');
                fwrite($xml, $xmlAssinado);
                fclose($xml);
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
                return false;
            }

            return true;
        }

        //Método responsável por enviar a nota à prefeitura
        public function send()
        {
            try {
                //Define o cabeçalho da requisição
                $cabecalho = "<cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" encoding=\"UTF-8\" versao=\"1.00\"><versaoDados>1.00</versaoDados></cabecalho>";
                //Recupera o xml já assinado
                $xmlAssinado = utf8_encode(file_get_contents(__DIR__.'/xml/envio/'.$this->id.'.xml'));

                //SoapClient é instanciado
                $client = $this->getClient();

                //Define os dados que serão enviados no método RecepcionarLoteRps do webservice
                $dados = array(
                    'nfseCabecMsg' => $cabecalho,
                    'nfseDadosMsg' => $xmlAssinado
                );
                $retorno = $client->RecepcionarLoteRps($dados);
                $retorno = $retorno->outputXML;
                $retornoXML = new SimpleXMLElement($retorno);

                $protocolo = (string) $retornoXML->Protocolo;

                return insertUpdate(array(
                    'tabela'  => 'NOTASFISCAIS',
                    'prefixo' => 'NFS',
                    'msg'     => 'N',
                    'url'     => '',
                    'debug'   => 'N',
                    'fields'  => array(
                        'vINFSCODIGO'    => $this->id,
                        'vSNFSPROTOCOLO' => $protocolo,
                    ),
                ));
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
            } catch (SoapFault $se) {
                alertAndExit($se);
            }
            return false;
        }

        public function check()
        {
            $cabecalho = "<cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" versao=\"1.00\"><versaoDados>1.00</versaoDados></cabecalho>";
            $mensagem = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>'.$this->empresa->cnpj.'</Cnpj><InscricaoMunicipal>'.$this->empresa->inscricao_municipal.'</InscricaoMunicipal></Prestador><Protocolo>'.$this->protocolo.'</Protocolo></ConsultarLoteRpsEnvio>';

            $options = array(
                'nfseCabecMsg' => $cabecalho,
                'nfseDadosMsg' => $mensagem,
            );

            $client = $this->getClient();

            $retorno = $client->ConsultarLoteRps($options);
            $xml = simplexml_load_string($retorno->outputXML);

            $this->update($xml);
        }

        private function update($response)
        {
            $data = [
                $response->ListaMensagemRetornoLote->MensagemRetorno,
                $response->ListaMensagemRetorno->MensagemRetorno
            ];

            $fields = [
                'vINFSCODIGO'  => $this->id,
                'vSNFSMSG'     => '',
                'vINFSERRO'    => false,
                'vINFSPOSICAO' => 1,
            ];

            foreach ($data as $msg) {
                if ($msg->Codigo != '') {
                    $fields['vSNFSMSG']     = $msg->Codigo.' - '.$msg->Mensagem;
                    $fields['vINFSERRO']    = true;
                    $fields['vINFSPOSICAO'] = 2;
                }
            }

            if (!$fields['vINFSERRO']) {
                $fields['vSNFSNUMERO']      = (string) $response->ListaNfse->CompNfse->Nfse->InfNfse->Numero;
                $fields['vSNFSVERIFICACAO'] = (string) $response->ListaNfse->CompNfse->Nfse->InfNfse->CodigoVerificacao;
                    $fields['vINFSPOSICAO'] = 3;
            }

            if ($response->ListaNfse->CompNfse->NfseCancelamento) {
                $fields['vINFSPOSICAO'] = 4;
                $fields['vSNFSMSG']     = 'Nota Cancelada';
            }

            insertUpdate(array(
                'tabela'  => 'NOTASFISCAIS',
                'prefixo' => 'NFS',
                'msg'     => 'N',
                'url'     => '',
                'debug'   => 'N',
                'fields'  => $fields,
            ));
        }

        protected function getClient()
        {
            //Seta a url do webservice da prefeitura
            $URLWebservice = 'https://nfse-hom.procempa.com.br/bhiss-ws/nfse?wsdl';
            // $URLWebservice = 'https://nfe.portoalegre.rs.gov.br/bhiss-ws/nfse?wsdl';

            //São configuradas as opções para o SOAP
            $options = array(
                'soap_version' => SOAP_1_1,
                'exceptions'   => true,
                'trace'        => true,
                'cache_wsdl'   => WSDL_CACHE_MEMORY,
                'local_cert'   => __DIR__.'/certificados/'.$this->empresa->id.'/public_private.pem',
                'passphrase'   => $this->empresa->pass,
                'https' => array(
                    'curl_verify_ssl_peer'  => true,
                    'curl_verify_ssl_host'  => true
                )
            );

            //SoapClient é instanciado
            $client = new SoapClient($URLWebservice, $options);
            return $client;
        }
    }

    class Tomador extends TWSet
    {
        public $cpf_cnpj;
        public $tipo;
        public $razao_social;
        public $telefone;
        public $email;
        public $endereco;

        public function __construct($clicodigo) {
            try {
                require_once '../transaction/transactionCliente.php';
                $cliente = fill_cliente($clicodigo);

                if ($cliente == 'N') {
                    throw new Exception("Não foi possível encontrar o cliente");
                }

                $cliente = array_map(function($field){
                    return $this->sanitize($field);
                }, $cliente);

                $this->cpf_cnpj     = ($cliente['CLITIPOCLIENTE'] == 'J') ? filterNumber($cliente['CLICNPJ']) : filterNumber($cliente['CLICPF']);
                $this->tipo         = $cliente['CLITIPOCLIENTE'];
                $this->razao_social = $cliente['CLINOME'];
                $this->telefone     = filterNumber($cliente['CLIFONE']);
                $this->email        = $cliente['CLIEMAIL'];
                $this->endereco     = new Endereco($cliente['CLICODIGO']);
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
            }
        }
    }

    class Endereco extends TWSet
    {
        public $logradouro;
        public $numero;
        public $complemento;
        public $bairro;
        public $cidade;
        public $cidadeNome;
        public $uf;
        public $cep;

        public function __construct($clicodigo)
        {
            try {
                require_once '../transaction/transactionClientexEndereco.php';
                $endereco = enderecoTipo($clicodigo, 'ENDERECO - COBRANCA');

                if (!$endereco) {
                    throw new Exception("Não foi possível encontrar o endereço de cobrança do cliente!");
                }

                $endereco = array_map(function($field){
                    return $this->sanitize($field);
                }, $endereco);

                $this->logradouro  = $endereco['ENDLOGRADOURO'];
                $this->numero      = $endereco['ENDNROLOGRADOURO'];
                $this->complemento = $endereco['ENDCOMPLEMENTO'];
                $this->bairro      = $endereco['ENDBAIRRO'];
                $this->cidade      = $endereco['CIDCODIGOIBGE'];
                $this->cidadeNome  = $endereco['CIDDESCRICAO'];
                $this->uf          = $endereco['ESTSIGLA'];
                $this->cep         = filterNumber($endereco['ENDCEP']);
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
            }
        }
    }

    class Empresa extends TWSet
    {
        public $id;
        public $cnpj;
        public $razao_social;
        public $inscricao_municipal;
        public $regime_tributacao;
        public $simples;
        public $codigo_servico;
        public $codigo_municipio;
        public $codigo_tributacao_municipio;
        public $incentivadorCultural;
        public $pass;
        public $calculate_ir     = true;
        public $calculate_pis    = true;
        public $calculate_cofins = true;
        public $calculate_csll   = true;

        public function __construct($empcodigo)
        {
            try {
                require_once '../transaction/transactionEmpresaUsuaria.php';
                $empresa = fill_EmpresaUsuaria($empcodigo);

                if ($empresa == 'N') {
                    throw new Exception("Não foi possível encontrar a empresa usuária");
                }

                $empresa = array_map(function($field){
                    return $this->sanitize($field);
                }, $empresa);

                $this->id                          = $empresa['EMPCODIGO'];
                $this->cnpj                        = filterNumber($empresa['EMPCNPJ']);
                $this->razao_social                = $empresa['EMPRAZAOSOCIAL'];
                $this->inscricao_municipal         = filterNumber($empresa['EMPIM']);
                $this->regime_tributacao           = $empresa['EMPNFSEREGIMEESPECIALTRIBUTACAO'];
                $this->simples                     = $empresa['EMPOPTANTESIMPLESNACIONAL'];
                $this->codigo_servico              = $empresa['EMPCODIGOSERVICO'];
                $this->codigo_municipio            = $empresa['CIDCODIGOIBGE'];
                $this->codigo_tributacao_municipio = $empresa['EMPCODIGOTRIBUTACAOMUNICIPIO'];
                $this->incentivadorCultural        = $empresa['EMPINCENTIVADORCULTURAL'];
                $this->pass                        = $empresa['EMPCERTPASS'];
                $this->calculate_ir                = true;
                $this->calculate_pis               = ($empresa['EMPRETEMPIS'] == 'S');
                $this->calculate_cofins            = ($empresa['EMPRETEMCOFINS'] == 'S');
                $this->calculate_csll              = ($empresa['EMPRETEMCSLL'] == 'S');

                if (!$this->issetCertificates()) {
                    throw new Exception("Não foram encontrados os certificados");
                }
            } catch (Exception $e) {
                alertAndExit($e->getMessage());
                die;
            }
        }

        protected function issetCertificates()
        {
            return  is_file(__DIR__.'/certificados/'.$this->id.'/public.pem') &&
                    is_file(__DIR__.'/certificados/'.$this->id.'/private.pem') &&
                    is_file(__DIR__.'/certificados/'.$this->id.'/public_private.pem');
        }
    }

    class TWSet
    {
        //O método mágico __set se encarregará de remover os carácteres especiais dos atributos
        public function __set($name, $value)
        {
            if (is_string($value)) {
                $this->$name = $this->sanitize($value);
            } else {
                $this->$name = $value;
            }
        }

        public function __get($name)
        {
            return $this->$name;
        }

        public function sanitize($value)
        {
           $old = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ','Ü','º','°', '&');
            $new = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','U','','', 'e');
            return str_replace($old, $new, $value);
        }
    }

    function alertAndExit($message)
    {
        echo '<script>alert("'.$message.'")</script>';
        exit;
    }