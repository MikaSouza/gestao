<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if( $_GET['hdn_metodo_fill'] == 'fill_Processo' )
	fill_Processo($_GET['vIPRCCODIGO'], $_GET['formatoRetorno']);

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateProcesso($_POST, 'N'); 
	sweetAlert('', '', 'S', 'cadProcesso.php?method=update&oid='.$vIOid, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Processo($_GET['oid'], 'array');
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

function listProcesso($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND p.PRCSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND p.PRCSTATUS = 'N' ";
	}else
		$where .= "AND p.PRCSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND p.PRCDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND p.PRCDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDistribuicaoInicio']))
		$where .= 'AND p.PRCDATAADMISSAO >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDistribuicaoFim']))
		$where .= 'AND p.PRCDATAADMISSAO <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND p.PRCSEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND p.PRCNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND p.PRCCNPJ = ? ';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRCSituacao']) && ($_POSTDADOS['FILTROS']['vSPRCSituacao'] != "T")) {
		if($_POSTDADOS['FILTROS']['vSPRCSituacao'] == "A")
			$where .=" and p.PRCDATAADMISSAO is not null and PRCDATADEMISSAO is null ";
		else if($_POSTDADOS['FILTROS']['vSPRCSituacao'] == "D")
			$where .=" and p.PRCDATADEMISSAO is not null ";
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$Sql .=" and p.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];

	$sql = "SELECT
				p.PRCCODIGO,
				p.PRCSTATUS,
				p.PRCNROPROCESSO,
				p.PRCAUTOR,
				p.PRCEMPRESA,
				p.PRCREU,
				p.PRCVALORDACAUSA,
				p.PRCDATADISTRIBUICAO,
				p.PRCSINTESE,
				p.PRCOBSERVACAO,
				p.PRCDATA_INC,
				c1.CLINOME as AUTOR,
				c2.CLINOME as EMPRESA,
				c3.CLINOME as REU,
				(SELECT t1.TABDESCRICAO FROM TABELAS t1 WHERE t1.TABCODIGO = p.PRCJUSTICA) as JUSTICA,
				(SELECT t2.TABDESCRICAO FROM TABELAS t2 WHERE t2.TABCODIGO = p.PRCTIPODEACAO) as TIPODEACAO,
				(SELECT t3.TABDESCRICAO FROM TABELAS t3 WHERE t3.TABCODIGO = p.PRCVARA) as VARA,
				(SELECT t4.TABDESCRICAO FROM TABELAS t4 WHERE t4.TABCODIGO = p.PRCFORO) as FORO,
				(SELECT t5.TABDESCRICAO FROM TABELAS t5 WHERE t5.TABCODIGO = p.PRCCOMARCA) as COMARCA,
				(SELECT t6.TABDESCRICAO FROM TABELAS t6 WHERE t6.TABCODIGO = p.PRCFASE) as FASE,
				(SELECT u.USUNOME FROM USUARIOS u WHERE u.USUCODIGO = p.PRCRESPONSAVEL) as RESPONSAVEL,
				(SELECT COUNT(f.PXFCODIGO) FROM PROCESSOXFASE f WHERE PXFSTATUS = 'S' AND p.PRCCODIGO = f.PRCCODIGO AND f.PXFPENDENTE = 'S') AS PENDENTE
			FROM 
				PROCESSOS p	
			LEFT JOIN CLIENTES c1 ON c1.CLICODIGO = p.PRCAUTOR
			LEFT JOIN CLIENTES c2 ON c2.CLICODIGO = p.PRCEMPRESA
			LEFT JOIN CLIENTES c3 ON c3.CLICODIGO = p.PRCREU
			WHERE 1 = 1
			".	$where	."
			ORDER BY 1 
		LIMIT 100";

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
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDistribuicaoInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataDistribuicaoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDistribuicaoFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataDistribuicaoFim']." 23:59:59";
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'], PDO::PARAM_INT);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateProcesso($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'PROCESSOS',
		'prefixo' => 'PRC',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);			
	return $id;
}

function fill_Processo($pOid, $formatoRetorno = 'array'){
	$SqlMain = "SELECT
                    *
                FROM PROCESSOS
                    WHERE PRCCODIGO  =".$pOid;	
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}