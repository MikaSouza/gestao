<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateAtendimentos($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Atendimentos($_GET['oid'], $vAConfiguracaoTela);
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

function listAtendimentos($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['vSStatusFiltro'])){
		if($_POSTDADOS['vSStatusFiltro'] == 'S')
			$where .= "AND a.ATESTATUS = 'S' ";
		else if($_POSTDADOS['vSStatusFiltro'] == 'N')
			$where .= "AND a.ATESTATUS = 'N' ";
	}else
		$where .= "AND a.ATESTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['vDDataInicio']))
		$where .= 'AND a.ATEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['vDDataFim']))
		$where .= 'AND a.ATEDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['vIATESEQUENCIAL']))
		$where .= 'AND a.ATESEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['vSATENOME']))
		$where .= 'AND a.ATENOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['vSATECNPJ']))
		$where .= 'AND a.ATECNPJ = ? ';
	if(verificarVazio($_POSTDADOS['vSCLINOME']))
		$where .= 'AND c.CLINOMEFANTASIA LIKE ? '; 
	if(verificarVazio($_POSTDADOS['vSPosicaoFiltro']) && ($_POSTDADOS['vSPosicaoFiltro'] != ""))
		$where .= 'AND a.ATEPOSICAOATUAL IN (?) ';
	if(verificarVazio($_POSTDADOS['vSPosicao']) && ($_POSTDADOS['vSPosicao'] != ""))
		$where .= 'AND a.ATEPOSICAO = ? AND a.ATEDATACONCLUSAO IS NULL AND a.ATEDATACANCELAMENTO IS NULL ';
	
	$sql = "SELECT
				a.*,
				c.CLINOMEFANTASIA,
				pp.POPNOME AS POSICAO_ATUAL, pp.POPCORIDENTIFICACAO AS POSICAO_COR, 
				(SELECT t.TABDESCRICAO FROM TABELAS t WHERE t.TABCODIGO = a.TABCODIGO) AS CATEGORIA,
				ap.ATPDESCRICAO AS PRIORIDADE_NOME,				
				(SELECT u.USUNOME FROM USUARIOS u WHERE u.USUCODIGO = a.ATEATENDENTE) as ATENDENTE,
				(SELECT p.PXSNOME FROM PRODUTOSXSERVICOS p WHERE p.PXSCODIGO = a.PROCODIGO) as PRODUTO
			FROM
				ATENDIMENTO a	
			LEFT JOIN POSICOESPADROES pp ON	pp.POPCODIGO = a.ATEPOSICAOATUAL
			LEFT JOIN ATENDIMENTOSPRIORIDADES ap ON a.ATPCODIGO = ap.ATPCODIGO
			LEFT JOIN CLIENTES c ON c.CLICODIGO = a.CLICODIGO
			WHERE 
				1 = 1
			".	$where	."
			ORDER BY a.ATESEQUENCIAL desc
			LIMIT 250	";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);		
	if(verificarVazio($_POSTDADOS['vDDataInicio'])){
		$varIni = $_POSTDADOS['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vDDataFim'])){
		$varFim = $_POSTDADOS['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vIATESEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['vIATESEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['vSATENOME'])){
		$pesquisa = $_POSTDADOS['vSATENOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSATECNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['vSATECNPJ'], PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSCLINOME'])){
		$vSCLINOME = $_POSTDADOS['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$vSCLINOME%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSPosicaoFiltro'])){
		$arrayQuery['parametros'][] = array(implode(",", $_POSTDADOS['vSPosicaoFiltro']), PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSPosicao'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['vSPosicao'], PDO::PARAM_STR);
	}
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateAtendimentos($_POSTDADOS, $pSMsg = 'N'){
	

	if ($_POSTDADOS['vIATECODIGO'] == '')
		$_POSTDADOS['vIATESEQUENCIAL'] = proxima_Sequencial('ATENDIMENTO');

	$dadosBanco = array(
		'tabela'  => 'ATENDIMENTO ',
		'prefixo' => 'ATE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);	
	if ($_POSTDADOS['vIATECODIGO'] == '') {
		include_once 'transactionAtendimentosxContatos.php';
		insert_update_AtendimentosxContatosLote($id, $_POSTDADOS['vICLICODIGO'], $_POSTDADOS["vACONCODIGO"], $_POSTDADOS['vHAXCRESPONSAVEL'], 'S', 'N');
	}	
	return $id;
}

function fill_Atendimentos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 'SELECT c.*, t.CLINOMEFANTASIA, u.USUNOME AS USUARIOINCLUSAO, u2.USUNOME AS USUARIOALTERACAO
				FROM ATENDIMENTO c
				LEFT JOIN CLIENTES t ON t.CLICODIGO = c.CLICODIGO
				LEFT JOIN USUARIOS u ON u.USUCODIGO = c.ATEUSU_INC
				LEFT JOIN USUARIOS u2 ON u2.USUCODIGO = c.ATEUSU_ALT
				WHERE c.ATECODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}

