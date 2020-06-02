<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateGuias($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Guias($_GET['oid'], $vAConfiguracaoTela);
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

function listGuias($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND g.GUISTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND g.GUISTATUS = 'N' ";
	}else
		$where .= "AND g.GUISTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND g.GUIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND g.GUIDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND c.CLISEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND c.CLINOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND c.CLICNPJ = ? ';

	/*
	
	,
				(
					SELECT
						GROUP_CONCAT(CONCAT('<a target=\"_BLANK\" href=\"', GEDDIRETORIO, '\" title=\"', GEDNOMEARQUIVO,'\">', GEDNOMEARQUIVO, '</a>') SEPARATOR '<br>')
					FROM
						GED
					WHERE
						GEDVINCULO = g.GUICODIGO AND
						MENCODIGO = 1890 AND
						GEDSTATUS = 'S'
				) AS GED
	*/
	
	$sql = "SELECT
				g.*,
				(CASE
				    WHEN GUIPAGADOR = 'C' THEN 'CLIENTE'
				    WHEN g.GUIPAGADOR = 'M' THEN 'MARPA'
				    ELSE ''
				END) AS PAGADOR,
				CONCAT(c.IDSIGLA, ' - ', c.CLINOME) AS CLINOME,
				t.TABDESCRICAO
			FROM
				GUIAS g
				LEFT JOIN CLIENTES c ON c.CLICODIGO = g.CLICODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = g.TABPROCEDIMENTO
			WHERE 1 = 1
			".	$where	."
			ORDER BY 1";

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
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
	}
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateGuias($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'GUIAS',
		'prefixo' => 'GUI',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadGuias.php',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Guias($pOid){
	$SqlMain = "SELECT
                     CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE, g.*
                FROM GUIAS g
				LEFT JOIN CLIENTES c ON c.CLICODIGO = g.CLICODIGO
                WHERE GUICODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}