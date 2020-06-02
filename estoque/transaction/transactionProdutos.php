<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateProdutos($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Produtos($_GET['oid'], $vAConfiguracaoTela);
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

function listProdutos($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME']))
		$where .= 'AND P.PRONOME LIKE ? ';	
	if(verificarVazio($_POSTDADOS['FILTROS']['vIPROGRUPO1']))
		$where .= 'AND P.PROGRUPO1 = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND P.PROSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND P.PROSTATUS = 'N' ";
	}else
		$where .= "AND P.PROSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND P.PRODATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND P.PRODATA_INC <= ? ';

	$sql = "SELECT
			P.*, 
			T.TABDESCRICAO AS UNIDADE,
			T2.TABDESCRICAO AS GRUPO
		FROM PRODUTOS P
		LEFT JOIN TABELAS T ON T.TABCODIGO = P.PROUNIDADE
		LEFT JOIN TABELAS T2 ON T2.TABCODIGO = P.PROGRUPO1
			WHERE 1 = 1
			".	$where	."
			ORDER BY P.PRONOME 
			LIMIT 200";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME'])){
		$vSPRONOME = $_POSTDADOS['FILTROS']['vSPRONOME'];
		$arrayQuery['parametros'][] = array("%$vSPRONOME%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vIPROGRUPO1']))		
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIPROGRUPO1'], PDO::PARAM_INT);	
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateProdutos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'PRODUTOS',
		'prefixo' => 'PRO',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadProdutos.php',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Produtos($pOid){
	$SqlMain = "SELECT
                    *
                FROM PRODUTOS
                    WHERE PROCODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}