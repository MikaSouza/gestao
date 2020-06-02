<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateMenus($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Menus($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
	include_once '../../twcore/teraware/php/constantes.php';
	$pAConfiguracaoTela = configuracoes_menu_acesso($_POST["vIOIDMENU"]);
	$config_excluir = array(
		"tabela" => Encriptar($pAConfiguracaoTela['MENTABELABANCO'], 'crud'),
		"prefixo" => $pAConfiguracaoTela['MENPREFIXO'],
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listMenus(){

	$sql = "SELECT
				m.*,
				(SELECT CONCAT(d.MENGRUPO, ' - ', d.MENTITULO) FROM MENUS d WHERE d.MENCODIGO = m.MENDEPENDENCIA) as DEPENDENCIA
			FROM
				MENUS m
			WHERE
				MENSTATUS = 'S'
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateMenus($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'MENUS',
		'prefixo' => 'MEN',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadMenus.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function comboMenusGrupo() 
{
	$baseSQL = "SELECT
					DISTINCT(MENGRUPO) as GRUPO
					FROM
					MENUS
					WHERE
					MENSTATUS = 'S'
					ORDER BY GRUPO";
	$query = array();
	$query['query']      = $baseSQL;
	$query['parametros'] = array();

	$data = consultaComposta($query);

	$response = $data['dados'];

	return $response;
}

function fill_Menus($pOid){
	$SqlMain = 'Select *
				 From MENUS
				 Where MENCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}