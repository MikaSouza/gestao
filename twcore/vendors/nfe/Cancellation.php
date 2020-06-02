<?php
require_once __DIR__.'/NFSe.php';

class Cancellation extends NFSe
{
    public function __construct($ctrcodigo)
    {
        parent::__construct($ctrcodigo);
    }

    public function build()
    {
        //Cria o DOM para o XML
        $dom = new DOMDocument('1.0');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $dom->encoding = 'UTF-8';

        $CancelarNfseEnvio = $dom->createElement('CancelarNfseEnvio');
        $CancelarNfseEnvio->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
        $dom->appendChild($CancelarNfseEnvio);

        $Pedido = $dom->createElement('Pedido');
        $Pedido->setAttribute('xmlns', 'http://www.abrasf.org.br/nfse.xsd');
        $CancelarNfseEnvio->appendChild($Pedido);

        $InfPedidoCancelamento = $dom->createElement('InfPedidoCancelamento');
        $InfPedidoCancelamento->setAttribute('Id', 'pedidoCancelamento_'.$this->empresa->cnpj.$this->inscricao_municipal.$this->numero);
        $Pedido->appendChild($InfPedidoCancelamento);


        $IdentificacaoNfse = $dom->createElement('IdentificacaoNfse');
        $InfPedidoCancelamento->appendChild($IdentificacaoNfse);

        $CodigoCancelamento = $dom->createElement('CodigoCancelamento', 2);
        $InfPedidoCancelamento->appendChild($CodigoCancelamento);

        $Numero             = $dom->createElement('Numero', $this->numero);
        $Cnpj               = $dom->createElement('Cnpj', $this->empresa->cnpj);
        $InscricaoMunicipal = $dom->createElement('InscricaoMunicipal', $this->empresa->inscricao_municipal);
        $CodigoMunicipio    = $dom->createElement('CodigoMunicipio', $this->empresa->codigo_municipio);

        $IdentificacaoNfse->appendChild($Numero);
        $IdentificacaoNfse->appendChild($Cnpj);
        $IdentificacaoNfse->appendChild($InscricaoMunicipal);
        $IdentificacaoNfse->appendChild($CodigoMunicipio);

        if (!$dom->save(__DIR__.'/xml/cancelamento/'.$this->id.'.xml')) {
            throw new Exception("Não foi possível criar o xml");
        }
    }

    //Método responsável por assinar o certificado
    public function sign()
    {
        try {
            //Verifica se o arquivo XML existe
            if (!is_file(__DIR__.'/xml/cancelamento/'.$this->id.'.xml')) {
                throw new Exception("Não foi possível encontrar o arquivo xml");
            }

            //Instancia as ferramentas
            require_once __DIR__.'/nfephp-master/libs/NFe/ToolsNFePHP.class.php';
            $tools = new ToolsNFePHP;

            //Seta as chaves
            $tools->setPublicKey(__DIR__.'/certificados/'.$this->empresa->id.'/public.pem');
            $tools->setPrivateKey(__DIR__.'/certificados/'.$this->empresa->id.'/private.pem');

            //Recupera o xml gerado pelo metodo build
            $nfefile = utf8_encode(file_get_contents(__DIR__.'/xml/cancelamento/'.$this->id.'.xml'));
            //Assina o xml
            $xmlAssinado = $tools->signXML($nfefile, 'InfPedidoCancelamento', 'Pedido');

            //Salva o xml assinado
            $xml = fopen("xml/cancelamento/".$this->id.".xml", "wb");
            fwrite($xml, $xmlAssinado);
            fclose($xml);
        } catch (Exception $e) {
            pre($e->getMessage());
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
                $xmlAssinado = utf8_encode(file_get_contents('xml/cancelamento/'.$this->id.'.xml'));

                //SoapClient é instanciado
                $client = $this->getClient();

                //Define os dados que serão enviados no método RecepcionarLoteRps do webservice
                $dados = array(
                    'nfseCabecMsg' => $cabecalho,
                    'nfseDadosMsg' => $xmlAssinado
                );
                $retorno = $client->CancelarNfse($dados);
                $retorno = $retorno->outputXML;
                $retornoXML = new SimpleXMLElement($retorno);
                pre($retornoXML);
                // $protocolo = (string) $retornoXML->Protocolo;

                // return insertUpdate(array(
                //     'tabela'  => 'NOTASFISCAIS',
                //     'prefixo' => 'NFS',
                //     'msg'     => 'N',
                //     'url'     => '',
                //     'debug'   => 'N',
                //     'fields'  => array(
                //         'vINFSCODIGO'    => $this->id,
                //         'vSNFSPROTOCOLO' => $protocolo,
                //     ),
                // ));
            } catch (Exception $e) {
                pre($e->getMessage());
            } catch (SoapFault $se) {
                pre($se);
            }
            return false;
        }
}