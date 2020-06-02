<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateAvisos($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Avisos($_GET['oid'], $vAConfiguracaoTela);
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

function listAvisos($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.AVISTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.AVISTATUS = 'N' ";
	}else
		$where .= "AND C.AVISTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.AVIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.AVIDATA_INC <= ? ';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vIAVISEQUENCIAL']))
		$where .= 'AND C.AVISEQUENCIAL = ? ';
	
	$sql = "SELECT
				*
			FROM
				AVISOS C
			WHERE
				1 = 1
			".	$where	."	
			LIMIT 50	";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vIAVISEQUENCIAL'])){		
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIAVISEQUENCIAL'], PDO::PARAM_INT);
	}	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateAvisos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'AVISOS',
		'prefixo' => 'AVI',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadAvisos.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);	
	return $id; 
}

function fill_Avisos($pOid){
	$SqlMain = 'SELECT c.*
				 From AVISOS c
				 Where c.AVICODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

