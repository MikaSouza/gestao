<?php
require "NFseSOAP.php";
require_once "nfephp-master/libs/NFe/ToolsNFePHP.class.php";

class NFse {
	function montaXML($nota) {

		$dom = new DOMDocument('1.0');
    $dom->formatOutput = true;
    $dom->preserveWhiteSpace = false;
    $dom->encoding = 'UTF-8';
    $NFe = $dom->createElement("EnviarLoteRpsEnvio");
    $NFe->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");
    $dom->appendChild($NFe);

    //IDENTIFICACAO DA NFS-E
    $LoteRps = $dom->createElement("LoteRps");
    $LoteRps->setAttribute("versao", "1.00");
    $LoteRps->setAttribute("Id", "Lote");
    $LoteRps->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");

    $NFe->appendChild($LoteRps);
    //Numero do lote
    $numero = $nota['numero'];
    $NumeroLote = $dom->createElement("NumeroLote",$numero);
    $LoteRps->appendChild($NumeroLote);
    //CNPJ
    switch ($nota['codempfil']) {
    	//Marpa Matriz
    	default:
    	case '1':
    		$cnpj = '91933119000172';
  		break;
  		//Marpa Filial Nova
    	case '5':
    		$cnpj = '91933119000920';
  		break;
        //Virtual
        case '3':
            $cnpj = '08942390000120';
        break;
        //Tributario
        case '4':
            $cnpj = '20102230000250';
        break;
        //Virtual - filial
        case '6':
            $cnpj = '08942390000201';
        break;
    }
    $Cnpj = $dom->createElement("Cnpj",$cnpj);
    $LoteRps->appendChild($Cnpj);
    //Inscrição Municipal
    switch ($nota['codempfil']) {
    	//Marpa Matriz
    	default:
    	case '1':
    		$inscricao_municipal = '08036721';
  		break;
  		//Marpa Filial Nova
    	case '5':
    		$inscricao_municipal = '29342821';
  		break;
        //Virtual
        case '3':
            $inscricao_municipal = '51230720';
        break;
  		//Tributário
    	case '4':
    		$inscricao_municipal = '29929024';
  		break;
        //Virtual - filial
        case '6':
            $inscricao_municipal = '29842620';
        break;
    }
    $InscricaoMunicipal = $dom->createElement("InscricaoMunicipal",$inscricao_municipal);
    $LoteRps->appendChild($InscricaoMunicipal);
    //Quantidade RPS
    $quantidade_rps = $nota['quantidade_rps'];
    $QuantidadeRps = $dom->createElement("QuantidadeRps",$quantidade_rps);
    $LoteRps->appendChild($QuantidadeRps);

    //IDENTIFICACAO DA LISTA RPS
    $ListaRps = $dom->createElement("ListaRps");
    $LoteRps->appendChild($ListaRps);
    //Valores
    $Rps = $dom->createElement("Rps");
    $Rps->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");
    $ListaRps->appendChild($Rps);
    //InfRps
    $InfRps = $dom->createElement("InfRps");
    $InfRps->setAttribute("Id", "rps:".$nota['numero']."ABCDZ");
    $InfRps->setAttribute("xmlns", "http://www.abrasf.org.br/nfse.xsd");
    $Rps->appendChild($InfRps);

    //IDENTIFICAÇÃO DA RPS INDIVIDUAL
    $IdentificacaoRps = $dom->createElement("IdentificacaoRps");
    $InfRps->appendChild($IdentificacaoRps);
    //Número da RPS
    $numero_rps = $nota['numero'];
    $Numero = $dom->createElement("Numero",$numero_rps);
    $IdentificacaoRps->appendChild($Numero);
    //Série da RPS
    $serie_rps = 'ABCDZ';
    $Serie = $dom->createElement("Serie",$serie_rps);
    $IdentificacaoRps->appendChild($Serie);
    //Tipo da RPS
    $tipo_rps = '1';
    $Tipo = $dom->createElement("Tipo",$tipo_rps);
    $IdentificacaoRps->appendChild($Tipo);

    //Data de Emissao
    $emissao = date('Y-m-d').'T'.date('H:i:s');
    $DataEmissao = $dom->createElement("DataEmissao",$emissao);
    $InfRps->appendChild($DataEmissao);
    //Natureza da Operação
    $natureza = 1;
    $NaturezaOperacao = $dom->createElement("NaturezaOperacao",$natureza);
    $InfRps->appendChild($NaturezaOperacao);

    //Regime Especial Tributação
    switch ($nota['codempfil']) {
    	//Marpa
  		//Marpa Filial Nova
        //Virtual
  		//Tribuário
        default:
            //Não tem regime especial de tributação
        break;
        case '3':
    	case '6':
    		$tributacao = 6;
    		$RegimeEspecialTributacao = $dom->createElement("RegimeEspecialTributacao",$tributacao);
    		$InfRps->appendChild($RegimeEspecialTributacao);
  		break;
    }

    //Optante pelo Simples Nacional
    switch ($nota['codempfil']) {
    	//Marpa
        //Marpa Filial Nova
        //Tributário
        default:
            $simples = 2; //Não
  		break;
  		//Virtual
        case '3':
    	case '6':
    		$simples = 1; //Sim
  		break;
    }
    $OptanteSimplesNacional = $dom->createElement("OptanteSimplesNacional",$simples);
    $InfRps->appendChild($OptanteSimplesNacional);
    //Incentivador Cultural
    $incentivador = 2;
    $IncentivadorCultural = $dom->createElement("IncentivadorCultural",$incentivador);
    $InfRps->appendChild($IncentivadorCultural);
    //Status
    $status = 1;
    $Status = $dom->createElement("Status",$status);
    $InfRps->appendChild($Status);

    //IDENTIFICACAO DO SERVICO
    $Servico = $dom->createElement("Servico");
    $InfRps->appendChild($Servico);
    //Valores
    $Valores = $dom->createElement("Valores");
    $Servico->appendChild($Valores);
    //Valor da Nota
    $valor = $nota['valor'];
    $ValorServicos = $dom->createElement("ValorServicos",$valor);
    $Valores->appendChild($ValorServicos);
    //Valor Deduções
    $valor_deducoes = '0.00';
    $ValorDeducoes = $dom->createElement("ValorDeducoes",$valor_deducoes);
    $Valores->appendChild($ValorDeducoes);
    //Valor PIS
    if (in_array($nota['codempfil'], [3, 6]))
        $valor_pis = '0.00';
    else
        $valor_pis = $nota['pis'];
    $ValorPis = $dom->createElement("ValorPis",$valor_pis);
    $Valores->appendChild($ValorPis);
    //Valor COFINS
    if (in_array($nota['codempfil'], [3, 6]))
        $valor_cofins = '0.00';
    else
        $valor_cofins = $nota['cofins'];
    $ValorCofins = $dom->createElement("ValorCofins",$valor_cofins);
    $Valores->appendChild($ValorCofins);
    //Valor INSS
    $valor_inss = '0.00';
    $ValorCofins = $dom->createElement("ValorInss",$valor_inss);
    $Valores->appendChild($ValorCofins);
    //Valor IR
    if (in_array($nota['codempfil'], [3, 6]))
        $valor_ir = '0.00';
    else
        $valor_ir = $nota['ir'];
    $ValorIr = $dom->createElement("ValorIr",$valor_ir);
    $Valores->appendChild($ValorIr);
    //Valor Csll
    if (in_array($nota['codempfil'], [3, 6]))
        $valor_csll = '0.00';
    else
        $valor_csll = $nota['csll'];
    $ValorCsll = $dom->createElement("ValorCsll",$valor_csll);
    $Valores->appendChild($ValorCsll);
    //ISS Retido
    $iss_retido = '2';
    $IssRetido = $dom->createElement("IssRetido",$iss_retido);
    $Valores->appendChild($IssRetido);

    //Codigo do servico prestado. Item da LC 116/2003
    switch ($nota['codempfil']) {
    	//Marpa
        //Marpa Filial Nova
  		//Tributário
        default:
            $servico = '17.01';
        break;
        //Virtual
        case '3':
        case '6':
            $servico = '17.02';
        break;
        case '4':
    		$servico = '17.20';
  		break;
    }
    $ItemListaServico = $dom->createElement("ItemListaServico",$servico);
    $Servico->appendChild($ItemListaServico);
    //Código Tributação Município
    if (in_array($nota['codempfil'], array(3, 6)))
        $codigo_municipio = '170200300';
    else if (in_array($nota['codempfil'], array(4)))
        $codigo_municipio = '172000100';
    else
        $codigo_municipio = '170100100';
    $CodigoTributacaoMunicipio = $dom->createElement("CodigoTributacaoMunicipio",$codigo_municipio);
    $Servico->appendChild($CodigoTributacaoMunicipio);
    //Discriminacao
    $discriminacao_txt = $nota['discriminacao'];
    $Discriminacao = $dom->createElement("Discriminacao",$discriminacao_txt);
    $Servico->appendChild($Discriminacao);
    //Codigo Municipio (IBGE)
    $municipio = '4314902';
    $CodigoMunicipio = $dom->createElement("CodigoMunicipio",$municipio);
    $Servico->appendChild($CodigoMunicipio);

    //IDENTIFICACAO DO PRESTADOR DO SERVICO
    $PrestadorServico = $dom->createElement("Prestador");
    $InfRps->appendChild($PrestadorServico);
    //CNPJ
    $CNPJPrestador = $dom->createElement("Cnpj",$cnpj);
    $PrestadorServico->appendChild($CNPJPrestador);
    //Inscricao Municipal
    $InscricaoMunicipal = $dom->createElement("InscricaoMunicipal",$inscricao_municipal);
    $PrestadorServico->appendChild($InscricaoMunicipal);


    //IDENTIFICACAO DO TOMADOR DE SERVICO
    $TomadorServico = $dom->createElement("Tomador");
    $InfRps->appendChild($TomadorServico);
    $IdentificacaoTomador = $dom->createElement("IdentificacaoTomador");
    $TomadorServico->appendChild($IdentificacaoTomador);
    //CPF / CNPJ
    $CpfCnpj = $dom->createElement("CpfCnpj");
    $IdentificacaoTomador->appendChild($CpfCnpj);

    if($nota['fj']=='F') {
    	$cpf_tomador = $nota['cgc'];
	    $Cpf = $dom->createElement("Cpf",$cpf_tomador);
	    $CpfCnpj->appendChild($Cpf);
    } else {
	    $cnpj_tomador = $nota['cgc'];
	    $Cnpj = $dom->createElement("Cnpj",$cnpj_tomador);
	    $CpfCnpj->appendChild($Cnpj);
	  }
    //Razão Social
    $razao_social = $nota['razao_social'];
    $RazaoSocial = $dom->createElement("RazaoSocial",$razao_social);
    $TomadorServico->appendChild($RazaoSocial);
    //Endereco
    $Endereco = $dom->createElement("Endereco");
    $TomadorServico->appendChild($Endereco);
    $endereco_tomador = $nota['endereco'];
    $EnderecoTomador = $dom->createElement("Endereco",$endereco_tomador);
    $Endereco->appendChild($EnderecoTomador);
    //Numero
    $numero_tomador = '0';
    $NumeroTomador = $dom->createElement("Numero",$numero_tomador);
    $Endereco->appendChild($NumeroTomador);
    //Numero
    //$complemento_tomador = '';
    //$Complemento = $dom->createElement("Complemento",$complemento_tomador);
    //$Endereco->appendChild($Complemento);
    //Bairro
    $bairro_tomador = $nota['bairro'];
    $BairroTomador = $dom->createElement("Bairro",$bairro_tomador);
    $Endereco->appendChild($BairroTomador);
    //Cidade
    //$cidade_tomador = $nota['cidade'];
    $cidade_tomador = $nota['cidade_tomador'];
    $CidadeTomador = $dom->createElement("CodigoMunicipio",$cidade_tomador);
    $Endereco->appendChild($CidadeTomador);
    //Estado
    $estado_tomador = $nota['uf'];
    $EstadoTomador = $dom->createElement("Uf",$estado_tomador);
    $Endereco->appendChild($EstadoTomador);
    //CEP
    $cep_tomador = $nota['cep'];
    $CepTomador = $dom->createElement("Cep",$cep_tomador);
    $Endereco->appendChild($CepTomador);
    //Contato
    $ContatoTomador = $dom->createElement("Contato");
    $TomadorServico->appendChild($ContatoTomador);
    //Telefone
    $telefone_tomador = $nota['telefone'];
    $Telefone = $dom->createElement("Telefone",$telefone_tomador);
    $ContatoTomador->appendChild($Telefone);
    //Email
    $email_tomador = $nota['email'];
    $Email = $dom->createElement("Email",$email_tomador);
    $ContatoTomador->appendChild($Email);
    $teste = $dom->saveXML();
    $dom->save('xml/envio/'.$nota['numero'].'.xml');

    $tools = new ToolsNFePHP();

    //Certificado para assinatura
    switch ($nota['codempfil']) {
        //Marpa
        //Marpa Filial Nova
        default:
        case '1':
        case '5':
            $tools->setPublicKey('/var/www/html/marpa_intranet/nfe/marpa_public.pem');
            $tools->setPrivateKey('/var/www/html/marpa_intranet/nfe/marpa_private.pem');
        break;
        //Virtual
        case '3':
        case '6':
            $tools->setPublicKey('/var/www/html/marpa_intranet/nfe/virtual_public.pem');
            $tools->setPrivateKey('/var/www/html/marpa_intranet/nfe/virtual_private.pem');
        break;
        //Tributário
        case '4':
            $tools->setPublicKey('/var/www/html/marpa_intranet/nfe/tributario_public.pem');
            $tools->setPrivateKey('/var/www/html/marpa_intranet/nfe/tributario_private.pem');
        break;
    }

    $nfefile = utf8_encode(file_get_contents('xml/envio/'.$nota['numero'].'.xml'));
    $sXmlAssinado = $tools->signXML($nfefile, 'InfRps','Rps',$nota['numero']."ABCDZ");

    $xml = fopen("xml/envio/".$nota['numero'].".xml", "wb");
    fwrite($xml, $sXmlAssinado);
    fclose($xml);

    $nfefile = utf8_encode(file_get_contents('xml/envio/'.$nota['numero'].'.xml'));
    $sXmlAssinado = $tools->signXML($nfefile, 'LoteRps','EnviarLoteRpsEnvio',$nota['numero']."ABCDZ");
    $xml = fopen("xml/envio/".$nota['numero'].".xml", "wb");
    fwrite($xml, $sXmlAssinado);
    fclose($xml);

    //echo '<pre>'.htmlspecialchars($sXmlAssinado);exit('...');

    $cabec = "<cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" encoding=\"UTF-8\" versao=\"1.00\"><versaoDados>1.00</versaoDados></cabecalho>";
    //echo '<pre>'.htmlspecialchars($sXmlAssinado);exit();
    $dados = array();
    $dados[0] = $cabec;
    $dados[1] = $sXmlAssinado;
    //var_dump($sXmlAssinado);exit();

    $NFseSOAP = new NFseSOAP();
    $retorno = $NFseSOAP->envia($dados, $nota['numero'], $nota['codempfil']);
    return $retorno;
	}
}
?>
