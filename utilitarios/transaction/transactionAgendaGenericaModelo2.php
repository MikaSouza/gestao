<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateAgendaGenericaModelo2($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_AgendaGenericaModelo2($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];	
}

function insertUpdateAgendaGenericaModelo2($dados, $pSMsg = 'N'){
	
	
	foreach($_POST as $campo =>$name ){
		$valor = $_POST[$campo];
		$vSTemp = substr($campo, 0, 3);
		if ($vSTemp == 'vSM') {
			list($vSLixo, $vIAGERESPONSAVEL, $vSAGEDATA) = explode('_', $campo);
			$vSAGEMUNICIPIO = $valor;
		}	
		if ($vSTemp == 'vSA') {
			$vSAGEATIVIDADE = $valor;
			//verifica se ja existe			
			$vIAGECODIGO = verificarAgendaxResponsavel($vSAGEDATA, $vIAGERESPONSAVEL);
			$dados2 = array(
				'vIAGECODIGO'  => $vIAGECODIGO,
				'vIAGERESPONSAVEL'  => $vIAGERESPONSAVEL,
				'vSAGEDATA' 		=> $vSAGEDATA,
				'vSAGEMUNICIPIO'  	=> $vSAGEMUNICIPIO,
				'vSAGEATIVIDADE'    =>$vSAGEATIVIDADE
			);
			//pre($dados2); 
			$dadosBanco = array(
				'tabela'  => 'AGENDAGENERICAMODELO2',
				'prefixo' => 'AGE',
				'fields'  => $dados2,
				'msg'     => 'N',
				'url'     => '',
				'debug'   => 'N' 
				);
			$id = insertUpdate($dadosBanco);
		}	
		//$valor = $_POST[$campo];

	 }
	
    return $id;
}

function fill_AgendaGenericaModelo2($pOid){
	$SqlMain = "SELECT
	                a.*,
					u.USUNOME
	            FROM
                    AGENDAGENERICAMODELO2 a
				LEFT JOIN USUARIOS u ON u.USUCODIGO = a.AGERESPONSAVEL	
				WHERE
					a.AGESTATUS = 'S'
				AND
                    a.AGECODIGO = ".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}


function fill_AgendaGenericaModelo2MesAno($vIANO, $vIMES, $vICLIRESPONSAVEL){
	$where = '';
	if (verificarVazio($vICLIRESPONSAVEL)) {
		$vICLIRESPONSAVEL = implode(',',$vICLIRESPONSAVEL); 
		$where .= " AND e.AGERESPONSAVEL in (".$vICLIRESPONSAVEL.") ";  
	}
	$sql = "SELECT
				e.*
				FROM
					AGENDAGENERICAMODELO2 e
				WHERE e.AGESTATUS = 'S'
				AND YEAR(e.AGEDATA) = ?
				AND MONTH(e.AGEDATA) = ?
				" .    $where    . "
			ORDER BY e.AGEDATA ";
	//echo $sql.$vIANO.' '.$vIMES;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$arrayQuery['parametros'][] = array($vIANO, PDO::PARAM_INT);
	$arrayQuery['parametros'][] = array($vIMES, PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);
	//pre($result);
	return $result;  
}	

function verificarAgendaxResponsavel($vSAGEDATA, $vIAGERESPONSAVEL)
{
	$sql = "SELECT
			 AGECODIGO
			FROM
			 	AGENDAGENERICAMODELO2
			WHERE
				AGEDATA = ?
			AND
				AGERESPONSAVEL = ?
			AND
				AGESTATUS = 'S' ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vSAGEDATA, PDO::PARAM_STR),
			array($vIAGERESPONSAVEL, PDO::PARAM_INT)
		)
	);
	$list = consultaComposta($arrayQuery);
	if ($list['quantidadeRegistros'] > 0)
		$vIAGECODIGO = $list['dados'][0]['AGECODIGO'];
	else
		$vIAGECODIGO = '';
	return $vIAGECODIGO;
}