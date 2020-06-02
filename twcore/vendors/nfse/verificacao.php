<?php
error_reporting(0);
ini_set("default_charset","UTF-8");
date_default_timezone_set("Brazil/East");
define("GLOBAL_PATH",$_SERVER['DOCUMENT_ROOT'].'/marpa_intranet/');
include_once(GLOBAL_PATH . "cfg/cfg.main.php");
require_once(CLASSE_PATH.'formatacao.class.php');
require_once(CLASSE_PATH.'error.class.php');
require_once(CLASSE_PATH.'core.class.php');
require_once(CLASSE_PATH.'dbpg.class.php');
require_once(CLASSE_PATH.'geral.class.php');
require_once(CLASSE_PATH.'sendmail.php');
$db = new edz_db(DB_HOST,DB_USER,DB_PASS,DB_BASE);
$email = new sendmail();
error_reporting(E_ALL);

require_once("NFse.php");
function atualizaNotas() {
	global $db;
	$sql = "SELECT nl.id, nl.protocolo, nl.tentativas, mf.codempfil
					FROM nota_fiscal_lote nl
          LEFT JOIN marpafin mf ON mf.lote_envio = nl.id
					WHERE nl.status = 2 AND nl.verificacao is null
					LIMIT 10";
	$notas = $db->db_query($sql);
	$NFse = new NFse();
	foreach ($notas as $i=>$nota) {
		echo '<br /><br /><br /><br />Lote: '.$nota['id'];
		echo '<br />Protocolo: '.$nota['protocolo'];
		$erro = false;
		$URLWebservice = 'https://nfe.portoalegre.rs.gov.br/bhiss-ws/nfse?wsdl';
    switch ($nota['codempfil']) {
          default:
          case '1':
          case '5':
            $key = getcwd().'/marpa_public_private.pem';
          break;
          case '3':
          case '6':
            $key = getcwd().'/virtual_public_private.pem';
          break;
          case '4':
            $key = getcwd().'/tributario_public_private.pem';
          break;
        }
    $options = array(
      'soap_version'=>SOAP_1_1,
      'exceptions'=>true,
      'trace'=>true,
      'cache_wsdl'=>WSDL_CACHE_MEMORY,
      'local_cert'=>$key,
      'passphrase'   => '15011221',
      'https' => array(
          'curl_verify_ssl_peer'  => true,
          'curl_verify_ssl_host'  => true
      )
    );
    $client = new SoapClient($URLWebservice, $options);
    $dados[0] = "<cabecalho xmlns=\"http://www.abrasf.org.br/nfse.xsd\" versao=\"1.00\"><versaoDados>1.00</versaoDados></cabecalho>";
    switch ($nota['codempfil']) {
      default:
      case '1'://Marpa Matriz
        $dados[1] = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>91933119000172</Cnpj><InscricaoMunicipal>08036721</InscricaoMunicipal></Prestador><Protocolo>'.$nota['protocolo'].'</Protocolo></ConsultarLoteRpsEnvio>';
      break;
      case '5'://Filial
        $dados[1] = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>91933119000920</Cnpj><InscricaoMunicipal>29342821</InscricaoMunicipal></Prestador><Protocolo>'.$nota['protocolo'].'</Protocolo></ConsultarLoteRpsEnvio>';
      break;
      case '3'://Virtual
        $dados[1] = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>08942390000120</Cnpj><InscricaoMunicipal>51230720</InscricaoMunicipal></Prestador><Protocolo>'.$nota['protocolo'].'</Protocolo></ConsultarLoteRpsEnvio>';
      break;
      case '4'://Tributário
        $dados[1] = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>20102230000250</Cnpj><InscricaoMunicipal>29929024</InscricaoMunicipal></Prestador><Protocolo>'.$nota['protocolo'].'</Protocolo></ConsultarLoteRpsEnvio>';
      break;
      case '6'://Virtual - filial
        $dados[1] = '<ConsultarLoteRpsEnvio xmlns="http://www.abrasf.org.br/nfse.xsd"><Prestador><Cnpj>08942390000201</Cnpj><InscricaoMunicipal>29842620</InscricaoMunicipal></Prestador><Protocolo>'.$nota['protocolo'].'</Protocolo></ConsultarLoteRpsEnvio>';
      break;
    }
    $options = array(
      'nfseCabecMsg'=>$dados[0],
      'nfseDadosMsg'=>$dados[1]
    );
    $retorno = $client->ConsultarLoteRps($options);
    $xml = simplexml_load_string($retorno->outputXML);
    $nr_nota = (string)$xml->ListaNfse->CompNfse->Nfse->InfNfse->Numero;
    $verificacao = (string)$xml->ListaNfse->CompNfse->Nfse->InfNfse->CodigoVerificacao;

    echo '<br />Nota: '.$nr_nota;
    echo '<br />Verificacao: '.$verificacao;
    if($nota>0) {
    	$sql = "SELECT codempfil, tipolan, numlan FROM marpafin WHERE lote_envio = $nota[id]";
    	$res = $db->db_query($sql);
    	$codempfil = $res[0]['codempfil'];
    	$tipolan = $res[0]['tipolan'];
    	$numlan = $res[0]['numlan'];
    	if($codempfil>0 && $tipolan>0 && $numlan>0 && $nr_nota>0 && $verificacao!='') {
    		$erro = false;
    		$sql = "UPDATE nota_fiscal_lote SET verificacao = '".$verificacao."' WHERE id = $nota[id]";
	    	$db->db_query($sql);
	    	echo '<br />SQL: '.$sql;

//Gunther 19.04.2016 - inclui linhas abaixo:
//não precisa mais - achei o que era. o num_lote
/*
    		$sql = "UPDATE marpafin SET nrnotafin = $nr_nota WHERE codempfil = $codempfil AND tipolan = $tipolan AND numlan = $numlan";
    		$db->db_query($sql);
	    	echo '<br />SQL: '.$sql;
    		$sql = "UPDATE marpafinpc SET nrnota = $nr_nota WHERE codempfil = $codempfil AND tipolan = $tipolan AND numlan = $numlan";
    		$db->db_query($sql);
	    	echo '<br />SQL: '.$sql;
*/
//Gunther 19.04.2016 - inclui linhas acima:

    	}
    }
	}
  echo '<br />FIM Atualiza&ccedil;&atilde;o';
}
atualizaNotas();
?>