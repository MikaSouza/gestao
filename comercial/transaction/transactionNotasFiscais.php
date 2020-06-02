<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateNotasFiscais($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_NotasFiscais($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirNSE') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("NOTASFISCAISSERVICO", 'crud'),
        "prefixo"  => "NSE",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listNotasFiscais($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.CLISTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.CLISTATUS = 'N' ";
	}else
		$where .= "AND C.CLISTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.CLIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.CLIDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND C.CLISEQUENCIAL = ? ';

	$sql = "SELECT
				n.*, c.CLINOME
			FROM
				NOTASFISCAISSERVICO n
			LEFT JOIN CLIENTES c ON c.CLICODIGO = n.CLICODIGO		
			WHERE
				NSESTATUS = 'S'
			ORDER BY 1 ";

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
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}

	$result = consultaComposta($arrayQuery);

	return $result;
}

function insertUpdateNotasFiscais($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'NOTASFISCAISSERVICO',
		'prefixo' => 'NSE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_NotasFiscais($pOid){
	$SqlMain = 'Select *
				 From NOTASFISCAISSERVICO
				 Where NSECODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}
