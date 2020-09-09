<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateInformacoesPreliminares($_POST, 'N');
	sweetAlert('', '', 'S', 'cadInformacoesPreliminares.php?method=update&oid='.$vIOid, 'S');
    return;    
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_InformacoesPreliminares($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if( $_GET['hdn_metodo_fill'] == 'fill_InformacoesPreliminares' )
	fill_InformacoesPreliminares($_GET['vICLICODIGO'], $_GET['formatoRetorno']);

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

function listInformacoesPreliminares($_POSTDADOS){
	$where = '';	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND (C.CLIRAZAOSOCIAL LIKE ? OR C.CLINOMEFANTASIA LIKE ?) ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND C.CLICNPJ = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.CLIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.CLIDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICONTATO']))
		$where .= 'AND C.CLICONTATO LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLIEMAIL']))
		$where .= 'AND C.CLIEMAIL LIKE ? ';		
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.CLISTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.CLISTATUS = 'N' ";
	}else
		$where .= "AND C.CLISTATUS = 'S' ";
	$sql = "SELECT
				C.CLICODIGO, C.CLISEQUENCIAL, C.CLINOMEFANTASIA, C.CLIRAZAOSOCIAL,
				CONCAT(
					(CASE WHEN C.CLITIPOCLIENTE = 'J' THEN
					C.CLICNPJ
					ELSE
					C.CLICPF
					END)
					) as CNPJCPF,
				C.CLIDATA_INC, C.CLIDATA_ALT, C.CLISTATUS
			FROM
				CLIENTES C						
			WHERE
				1 = 1
			".	$where	."
			LIMIT 250	";	
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICONTATO'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLICONTATO'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLIEMAIL'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLIEMAIL'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
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

function insertUpdateInformacoesPreliminares($_POSTCLI, $pSMsg = 'N'){
	if ($_POSTCLI['vSCLITIPOCLIENTE'] == 'F'){
		$_POSTCLI['vSCLIRAZAOSOCIAL'] = $_POSTCLI['vHCLINOME'];
		$_POSTCLI['vSCLINOMEFANTASIA'] = $_POSTCLI['vHCLINOME'];	
	}
	$dadosBanco = array(
		'tabela'  => 'CLIENTES',
		'prefixo' => 'CLI',
		'fields'  => $_POSTCLI,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);	
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_InformacoesPreliminares($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 'SELECT c.*
				 From CLIENTES c
				 Where c.CLICODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}