<?php
include_once '../../twcore/teraware/php/constantes.php';
if( $_GET['hdn_metodo_fill'] == 'fill_Enderecos' )
	fill_Enderecos($_GET['vICLICODIGO'], $_GET['vITABCODIGO'], $_GET['formatoRetorno']); 

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCLI') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("ENDERECOS", 'crud'),
        "prefixo"  => "CLI",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listEnderecos(){

	$sql = "SELECT
				C.CLICODIGO, C.CLISEQUENCIAL, C.CLINOME, C.CLICNPJ, C.CLIDATA_INC, C.CLIDATA_ALT, C.CLISTATUS,
				U.USUNOME AS REPRESENTANTE
			FROM
				ENDERECOS C		
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

function insertUpdateEnderecos($_POSTDADOS, $pSMsg = 'N'){
	if ($_POSTDADOS['vHTABCODIGO'] == 426) { //INPI
		$_POSTDADOSEND['vIENDCODIGO'] = $_POSTDADOS['vHINPIENDCODIGO'];
		$_POSTDADOSEND['vITABCODIGO'] = 426;
		$_POSTDADOSEND['vSENDLOGRADOURO'] = $_POSTDADOS['vHINPIENDLOGRADOURO']; 
		$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHINPIESTCODIGO'];
		$_POSTDADOSEND['vIPAICODIGO'] = $_POSTDADOS['vHINPIPAICODIGO'];
		$_POSTDADOSEND['vICIDCODIGO'] = $_POSTDADOS['vHINPICIDCODIGO'];
		$_POSTDADOSEND['vSENDNROLOGRADOURO'] = $_POSTDADOS['vHINPIENDNROLOGRADOURO'];
		$_POSTDADOSEND['vSENDBAIRRO'] = $_POSTDADOS['vHINPIENDBAIRRO'];
		$_POSTDADOSEND['vSENDCEP'] = $_POSTDADOS['vHINPIENDCEP'];
		$_POSTDADOSEND['vSENDCOMPLEMENTO'] = $_POSTDADOS['vHINPIENDCOMPLEMENTO'];		
	} else if ($_POSTDADOS['vHTABCODIGO'] == 427) { //Cobranca
		$_POSTDADOSEND['vIENDCODIGO'] = $_POSTDADOS['vHCOBENDCODIGO'];;
		$_POSTDADOSEND['vITABCODIGO'] = 427;
		$_POSTDADOSEND['vSENDLOGRADOURO'] = $_POSTDADOS['vHCOBENDLOGRADOURO']; 
		$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHCOBESTCODIGO'];
		$_POSTDADOSEND['vIPAICODIGO'] = $_POSTDADOS['vHCOBPAICODIGO'];
		$_POSTDADOSEND['vICIDCODIGO'] = $_POSTDADOS['vHCOBCIDCODIGO'];
		$_POSTDADOSEND['vSENDNROLOGRADOURO'] = $_POSTDADOS['vHCOBENDNROLOGRADOURO'];
		$_POSTDADOSEND['vSENDBAIRRO'] = $_POSTDADOS['vHCOBENDBAIRRO'];
		$_POSTDADOSEND['vSENDCEP'] = $_POSTDADOS['vHCOBENDCEP'];
		$_POSTDADOSEND['vSENDCOMPLEMENTO'] = $_POSTDADOS['vHCOBENDCOMPLEMENTO'];		
	} else if ($_POSTDADOS['vHTABCODIGO'] == 475) { //Correspondencia
		$_POSTDADOSEND['vIENDCODIGO'] = $_POSTDADOS['vHCORENDCODIGO'];;
		$_POSTDADOSEND['vITABCODIGO'] = 475;
		$_POSTDADOSEND['vSENDLOGRADOURO'] = $_POSTDADOS['vHCORENDLOGRADOURO']; 
		$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHCORESTCODIGO'];
		$_POSTDADOSEND['vIPAICODIGO'] = $_POSTDADOS['vHCORPAICODIGO'];
		$_POSTDADOSEND['vICIDCODIGO'] = $_POSTDADOS['vHCOBCIDCODIGO'];
		$_POSTDADOSEND['vSENDNROLOGRADOURO'] = $_POSTDADOS['vHCORENDNROLOGRADOURO'];
		$_POSTDADOSEND['vSENDBAIRRO'] = $_POSTDADOS['vHCORENDBAIRRO'];
		$_POSTDADOSEND['vSENDCEP'] = $_POSTDADOS['vHCORENDCEP'];
		$_POSTDADOSEND['vSENDCOMPLEMENTO'] = $_POSTDADOS['vHCORENDCOMPLEMENTO'];		
	}
	$_POSTDADOSEND['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
	$dadosBanco = array(
		'tabela'  => 'ENDERECOS',
		'prefixo' => 'END',
		'fields'  => $_POSTDADOSEND,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	
	return $id; 
}

function fill_Enderecos($vICLICODIGO, $vITABCODIGO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM ENDERECOS
			WHERE ENDSTATUS = 'S'
			AND CLICODIGO = ? 
			AND TABCODIGO = ? ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						 array($vICLICODIGO, PDO::PARAM_INT),
						 array($vITABCODIGO, PDO::PARAM_INT)
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