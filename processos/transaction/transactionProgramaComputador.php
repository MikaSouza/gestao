<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateProgramaComputador($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_ProgramaComputador($_GET['oid'], $vAConfiguracaoTela);
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

function listProgramaComputador($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND g.PGCSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND g.PGCSTATUS = 'N' ";
	}else
		$where .= "AND g.PGCSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND g.PGCDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND g.PGCDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND g.PGCSEQUENCIAL = ? ';

	$sql = "SELECT
				g.*,
				CONCAT(c.IDSIGLA, ' - ', c.CLINOME) AS CLINOME
			FROM
				PROGRAMACOMPUTADOR g
				LEFT JOIN CLIENTES c ON c.CLICODIGO = g.CLICODIGO
			WHERE 1 = 1
			".	$where	."
			ORDER BY 1 
			LIMIT 10";

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

function insertUpdateProgramaComputador($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'PROGRAMACOMPUTADOR',
		'prefixo' => 'PGC',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadProgramaComputador.php',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ProgramaComputador($pOid){
	$SqlMain = "SELECT
                    CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE, g.*
                FROM PROGRAMACOMPUTADOR g
				LEFT JOIN CLIENTES c ON c.CLICODIGO = g.CLICODIGO
                WHERE g.PGCCODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}