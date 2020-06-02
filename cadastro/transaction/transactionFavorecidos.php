<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateFavorecidos($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Favorecidos($_GET['oid'], $vAConfiguracaoTela);
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

function listFavorecidos($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.FAVSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.FAVSTATUS = 'N' ";
	}else
		$where .= "AND C.FAVSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.FAVDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.FAVDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vIFAVSEQUENCIAL']))
		$where .= 'AND C.FAVCODIGO = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSFAVNOME']))
		$where .= 'AND C.FAVNOMEFANTASIA LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSFAVCNPJ']))
		$where .= 'AND C.FAVCNPJ = ? ';

	$sql = "SELECT
				C.FAVCODIGO, C.FAVSEQUENCIAL, C.FAVNOMEFANTASIA, C.FAVCNPJ, C.FAVDATA_INC, C.FAVDATA_ALT, C.FAVSTATUS,
				CASE WHEN FAVTIPOCLIENTE = 'J' THEN 
					C.FAVCNPJ
				ELSE C.FAVCPF END AS CNPJCPF 	
			FROM
				FAVORECIDOS C
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vIFAVSEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIFAVSEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSFAVNOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSFAVNOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSFAVCNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSFAVCNPJ'], PDO::PARAM_STR);
	}
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateFavorecidos($_POSTDADOS, $pSMsg = 'N'){
	if ($_POSTDADOS['vSFAVTIPOCLIENTE'] == 'F'){
		$_POSTDADOS['vSFAVRAZAOSOCIAL'] = $_POSTDADOS['vHFAVNOME'];
		$_POSTDADOS['vSFAVNOMEFANTASIA'] = $_POSTDADOS['vHFAVNOME'];	
	}	
	$dadosBanco = array(
		'tabela'  => 'FAVORECIDOS',
		'prefixo' => 'FAV',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadFavorecidos.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
}

function fill_Favorecidos($pOid){
	$SqlMain = 'SELECT c.*
				 From FAVORECIDOS c
				 Where c.FAVCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

