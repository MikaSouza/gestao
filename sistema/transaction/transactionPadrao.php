<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {	
    $vIOid = insertUpdatePadrao($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Padrao($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
	$vAConfiguracaoTela = configuracoes_menu_acesso($_POST["vIOIDMENU"]);
	$config_excluir = array(
		"tabela" => $vAConfiguracaoTela['MENTABELABANCO'],
		"prefixo" => $vAConfiguracaoTela['MENPREFIXO'],
		"status" => "N",
		"codigo" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo deletarRegistro($config_excluir);
}

function listPadrao($_POSTDADOS){

	$sql = "SELECT
				*
			FROM 
				".$_POSTDADOS['MENTABELABANCO']."
			WHERE				
				".$_POSTDADOS['MENPREFIXO']."STATUS = 'S'
			ORDER BY 1 ";
	$arrayQuery = array(					
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	return $result;
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