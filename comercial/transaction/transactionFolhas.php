<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateFolhas($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Folhas($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirCXF') {
	$config_excluir = array(
		"tabela" => Encriptar("CLIENTESXFOLHAS", 'crud'),
		"prefixo" => "CXF",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "S",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listFolhas($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.CXFSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.CXFSTATUS = 'N' ";
	}else
		$where .= "AND C.CXFSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.CXFDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.CXFDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND C.CLISEQUENCIAL = ? ';

	$sql = "SELECT
				e.*,
				u.USUNOME AS REPRESENTANTE,
				CONCAT(c.IDSIGLA, ' - ', c.CLINOME) AS CLINOME
			FROM CLIENTESXFOLHAS e
			LEFT JOIN CLIENTES c ON c.CLICODIGO = e.CLICODIGO
			LEFT JOIN USUARIOS u ON	e.CXFCONSULTOR = u.USUCODIGO
				WHERE CXFSTATUS = 'S'
			ORDER BY e.CXFDATA_INC desc
			LIMIT 10 ";

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

function insertUpdateFolhas($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CLIENTESXFOLHAS',
		'prefixo' => 'CXF',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadFolhas.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Folhas($pOid){
	$SqlMain = "SELECT CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE, p.*
				 FROM CLIENTESXFOLHAS p
				 LEFT JOIN CLIENTES c ON c.CLICODIGO = p.CLICODIGO
				 WHERE p.CXFCODIGO = ".$pOid;
	echo $SqlMain;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}
