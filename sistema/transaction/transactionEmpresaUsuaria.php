<?php
include_once $_SERVER['DOCUMENT_ROOT'].'twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
	$vSName = "insertUpdate".$vAConfiguracaoTela['MENTITULOFUNC'];
    $vIOid = $vSName($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
	$vSName = "fill_".$vAConfiguracaoTela['MENTITULOFUNC'];
    $vROBJETO = $vSName($_GET['oid']);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirEMP') {
	$config_excluir = array(
		"tabela" => Encriptar("EMPRESAUSUARIA", 'crud'),
		"prefixo" => "EMP",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S"
	);
	echo excluirAtivarRegistros($config_excluir);

}

function listEmpresaUsuaria(){

	$sql = "SELECT
				e.*
				FROM
					EMPRESAUSUARIA e
				WHERE EMPSTATUS = 'S'
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateEmpresaUsuaria($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'EMPRESAUSUARIA',
		'prefixo' => 'EMP',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadEmpresaUsuaria.php',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_EmpresaUsuaria($pOid){
	$SqlMain = "SELECT
		E.*,
		C.CNADESCRICAO,
		C.CNACNAE,
		cid.CIDCODIGOIBGE
	FROM EMPRESAUSUARIA E
		LEFT JOIN CNAE C ON C.CNACODIGO = E.CNACODIGO
		LEFT JOIN CIDADES cid ON cid.CIDCODIGO = E.CIDCODIGO
	WHERE EMPCODIGO =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}