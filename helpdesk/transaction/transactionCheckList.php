<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if( $_GET['hdn_metodo_fill'] == 'fill_CheckList' )
	fill_CheckList($_GET['vICHECODIGO'], $_GET['formatoRetorno']);

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateCheckList($_POST, 'N'); 
	sweetAlert('', '', 'S', 'cadCheckList.php?method=update&oid='.$vIOid, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_CheckList($_GET['oid'], 'array');
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

function listCheckList($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND u.USUSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND u.CHESTATUS = 'N' ";
	}else
		$where .= "AND u.CHESTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND u.CHEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND u.CHEDATA_INC <= ? ';

	$sql = "SELECT
				u.*
			FROM CHECKLIST u	
			WHERE 1 = 1
			".	$where	."
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataAdmissaoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataAdmissaoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataDemissaoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataDemissaoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCHENOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCHENOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vIEMPCODIGO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIEMPCODIGO'], PDO::PARAM_INT);
	$result = consultaComposta($arrayQuery);

	return $result;
}

function insertUpdateCheckList($_POSTDADOS, $pSMsg = 'N'){
	
	$dadosBanco = array(
		'tabela'  => 'CHECKLIST',
		'prefixo' => 'CHE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);	
	return $id;
}

function fill_CheckList($pOid, $formatoRetorno = 'array'){
	$SqlMain = "SELECT
                    *
                FROM CHECKLIST
                    WHERE CHECODIGO  =".$pOid;	
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}