<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdatePadrao($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
	if(isset($_GET['oid']) and $_GET['oid']  > 0){
		$vSIdUsu = $_GET['oid'];
	}else if(isset($_SESSION['SITW_USUCODIGO']) and $_SESSION['SITW_USUCODIGO'] > 0){
		$vSIdUsu = $_SESSION['SITW_USUCODIGO'];
	}
	$vROBJETO = fill_Padrao($vSIdUsu , $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

function insertUpdatePadrao($_POSTDADOS, $pSMsg = 'N') {
	$dadosBanco = array(
		'tabela'  => $_POSTDADOS['vHTABELA'],
		'prefixo' => $_POSTDADOS['vHPREFIXO'],
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => $_POSTDADOS['vHURL'],
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Padrao($pOid, $pAConfiguracaoTela) {
    $SqlMain = "SELECT
		*
    FROM
		".$pAConfiguracaoTela['MENTABELABANCO']."
    WHERE
		".$pAConfiguracaoTela['MENPREFIXO']."CODIGO = ".$pOid;
    $vConexao = sql_conectar_banco(vGBancoSite);
    $resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
    $registro = sql_retorno_lista($resultSet);
    return $registro !== null ? $registro : "N";
}