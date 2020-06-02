<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateHistoricoxEstoque($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_HistoricoxEstoque($_GET['oid'], $vAConfiguracaoTela);
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

function listHistoricoxEstoque(){

	$sql = "SELECT
				h.*,
				CASE HXETIPOMOV WHEN 'E' THEN 'Entrada'	WHEN 'S' THEN 'Saída' END AS TIPO ,
				p.PRONOME,
				h.HXEQTDE,
				u.USUNOME,
				u.USUCODIGO
			FROM
				HISTORICOXESTOQUE h, PRODUTOS p, USUARIOS u
			WHERE
				p.PROCODIGO = h.PROCODIGO
			AND
				u.USUCODIGO = h.HXEUSU_INC
			ORDER BY 1";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateHistoricoxEstoque($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'HISTORICOXESTOQUE',
		'prefixo' => 'HXE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadHistoricoxEstoque.php',
		'debug'   => 'S'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_HistoricoxEstoque($pOid){
	$SqlMain = "SELECT
                    *
                FROM HISTORICOXESTOQUE
                    WHERE HXECODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}