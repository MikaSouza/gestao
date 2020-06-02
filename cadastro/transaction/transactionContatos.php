<?php
include_once '../../twcore/teraware/php/constantes.php';
if( $_GET['hdn_metodo_fill'] == 'fill_Contatos' )
	fill_Contatos($_GET['vICLICODIGO'], $_GET['vICONTIPO'], $_GET['formatoRetorno']); 

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCLI') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("CONTATOS", 'crud'),
        "prefixo"  => "CON",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listContatos(){

	$sql = "SELECT
				C.CLICODIGO, C.CLISEQUENCIAL, C.CLINOME, C.CLICNPJ, C.CLIDATA_INC, C.CLIDATA_ALT, C.CLISTATUS,
				U.USUNOME AS REPRESENTANTE
			FROM
				CONTATOS C		
			LEFT JOIN USUARIOS U ON U.USUCODIGO = C.CLIRESPONSAVEL
			WHERE
				1 = 1
			LIMIT 50	";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateContatos($_POSTDADOS, $pSMsg = 'N'){
	
	if ($_POSTDADOS['vHTABCODIGO'] == 26933) { //INPI
		$_POSTDADOSCON['vICONCODIGO'] = $_POSTDADOS['vHINPICONCODIGO'];
		$_POSTDADOSCON['vICONTIPO'] = 26933;
		$_POSTDADOSCON['vSCONNOME'] = $_POSTDADOS['vHINPICONNOME']; 
		$_POSTDADOSCON['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCON['vSCONEMAIL'] = $_POSTDADOS['vHINPICONEMAIL'];
		$_POSTDADOSCON['vSCONCELULAR'] = $_POSTDADOS['vHINPICONCELULAR'];
		$_POSTDADOSCON['vSCONFONE'] = $_POSTDADOS['vHINPICONFONE'];
		$_POSTDADOSCON['vSCONOBSERVACOES'] = $_POSTDADOS['vHINPICONOBSERVACOES'];
	} else if ($_POSTDADOS['vHTABCODIGO'] == 26934) { //Cobranca
		$_POSTDADOSCON['vICONCODIGO'] = $_POSTDADOS['vHCOBCONCODIGO'];
		$_POSTDADOSCON['vICONTIPO'] = 26934;
		$_POSTDADOSCON['vSCONNOME'] = $_POSTDADOS['vHCOBCONNOME']; 
		$_POSTDADOSCON['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCON['vSCONEMAIL'] = $_POSTDADOS['vHCOBCONEMAIL'];
		$_POSTDADOSCON['vSCONCELULAR'] = $_POSTDADOS['vHCOBCONCELULAR'];
		$_POSTDADOSCON['vSCONFONE'] = $_POSTDADOS['vHCOBCONFONE'];
		$_POSTDADOSCON['vSCONOBSERVACOES'] = $_POSTDADOS['vHCOBCONOBSERVACOES'];
	} else if ($_POSTDADOS['vHTABCODIGO'] == 26936) { //Correspondencia
		$_POSTDADOSCON['vICONCODIGO'] = $_POSTDADOS['vHCORCONCODIGO'];
		$_POSTDADOSCON['vICONTIPO'] = 26936;
		$_POSTDADOSCON['vSCONNOME'] = $_POSTDADOS['vHCORCONNOME']; 
		$_POSTDADOSCON['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCON['vSCONEMAIL'] = $_POSTDADOS['vHCORCONEMAIL'];
		$_POSTDADOSCON['vSCONCELULAR'] = $_POSTDADOS['vHCOBCONCELULAR'];
		$_POSTDADOSCON['vSCONFONE'] = $_POSTDADOS['vHCORCONFONE'];		
		$_POSTDADOSCON['vSCONOBSERVACOES'] = $_POSTDADOS['vHCORCONOBSERVACOES'];
	}
	
	$_POSTDADOSCON['vIEMPCODIGO'] = 1;
	
	$dadosBanco = array(
		'tabela'  => 'CONTATOS',
		'prefixo' => 'CON',
		'fields'  => $_POSTDADOSCON,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	
	return $id; 
}

function fill_Contatos($vICLICODIGO, $vICONTIPO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM CONTATOS
			WHERE CONSTATUS = 'S'
			AND CLICODIGO = ? 
			AND CONTIPO = ? ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						 array($vICLICODIGO, PDO::PARAM_INT),
						 array($vICONTIPO, PDO::PARAM_INT)
					)
				);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];	
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}

