<?php
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateTabelas($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Tabelas($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST['method']) && $_POST['method'] == 'insertTabelasPadrao'){
	include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
	$_POSTTAB['vITABCODIGO']    = $_POST['vITABCODIGO'];
	$_POSTTAB['vSTABTIPO']      = $_POST['vSTABTIPO'];
	$_POSTTAB['vSTABDESCRICAO'] = $_POST['vSTABDESCRICAO'];		
	echo $vIOid = insertUpdateTabelas($_POSTTAB, 'N');
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

function listTabelas($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSTABTIPO']))
		$where .= 'AND TABTIPO = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND TABSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND TABSTATUS = 'N' ";
	}else
		$where .= "AND TABSTATUS = 'S' ";
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.CLIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.CLIDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSTABDESCRICAO']))
		$where .= 'AND TABDESCRICAO LIKE ? ';
	$sql = "SELECT
				*
			FROM
				TABELAS
			WHERE
				1 = 1
			".	$where	."
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vSTABTIPO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSTABTIPO'], PDO::PARAM_STR);			
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSTABDESCRICAO'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSTABDESCRICAO'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}		
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateTabelas($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'TABELAS',
		'prefixo' => 'TAB',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadTabelas.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);	
	return $id; 
}

function comboTabelas($tabtipo='', $showEmptyOption = true){
	$baseSQL = "SELECT
					TABCODIGO,
					TABDESCRICAO
				FROM
					TABELAS
				WHERE
					TABSTATUS = 'S'	";

	if (!is_null($tabtipo)) :
		$baseSQL .= "AND
						TABTIPO = ?";
	endif;

	$baseSQL .= " ORDER BY
					TABDESCRICAO ASC";

	$query = array();
	$query['query']      = $baseSQL;
	$query['parametros'] = array();

	if (!is_null($tabtipo)) :
		$query['parametros'] = array(
			array($tabtipo, PDO::PARAM_STR),
		);
	endif;

	$data     = consultaComposta($query);
	$response = $data['dados'];

	if ($showEmptyOption) {
		array_unshift($response, array('TABCODIGO' => '', 'TABDESCRICAO' => '----------'));
	}

	return $response;
}

function getTabela($pSTABDESCRICAO, $pSTABTIPO)
{
	$pSTABDESCRICAO = trim($pSTABDESCRICAO);

	$vITABCODIGO = 0;
    $SqlMain = "SELECT
    				TABCODIGO
	        	FROM
	        		TABELAS
	        	WHERE
	        		TABSTATUS = 'S' AND
	        		TABTIPO = '{$pSTABTIPO}' AND
	        		TABDESCRICAO = '{$pSTABDESCRICAO}'";
    $vConexao = sql_conectar_banco();
    $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $SqlMain);

    while ($reg_RS = sql_retorno_lista($RS_MAIN)){
        $vITABCODIGO = $reg_RS['TABCODIGO'];
    }

    if ($vITABCODIGO == 0) {  // incluir
    	$_POSTTAB['vITABCODIGO'] = '';
    	$_POSTTAB['vSTABTIPO'] = $pSTABTIPO;
    	$_POSTTAB['vSTABDESCRICAO'] = $pSTABDESCRICAO;
    	$vITABCODIGO = insertUpdateTabela($_POSTTAB);
    }

    $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $SqlMain);
    return $vITABCODIGO;
}

function getTabtipo()
{
	$data = consultaComposta(array(
		'query' => "SELECT
						DISTINCT(TABTIPO) AS TABTIPO
					FROM
						TABELAS
					WHERE
						TABSTATUS = 'S'
					ORDER BY
						TABTIPO ASC",
	));

	return array_column($data['dados'], 'TABTIPO');
}

function fill_Tabelas($pOid){
	$SqlMain = 'SELECT *
				 From TABELAS
				 Where TABCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}