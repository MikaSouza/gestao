<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateContasPagar($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_ContasPagar($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchContasPagar') {
    listContasPagar($_POST);
}	

if (isset($_GET['hdn_metodo_search']) && $_GET['hdn_metodo_search'] == 'liquidarContasPagar')
    baixar_lote_ContasaPagar($_POST['vACTPCODIGO'], $_POST['vITABFORMAPAGAMENTO'], $_POST['vDCTPDATAPAGAMENTO'], $_POST['vICBACODIGO']);

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

function listContasPagar($_POSTDADOS){
	include_once '../../twcore/teraware/php/constantes.php';
	$where = '';
	$_POSTDADOS['vSStatusFiltro'] = 'S';
	if(verificarVazio($_POSTDADOS['vSStatusFiltro'])){
		if($_POSTDADOS['vSStatusFiltro'] == 'S')
			$where .= "AND c.CTPSTATUS = 'S' ";
		else if($_POSTDADOS['vSStatusFiltro'] == 'N')
			$where .= "AND c.CTPSTATUS = 'N' ";
	}else
		$where .= "AND c.CTPSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['vDDataInicio']))
		$where .= 'AND c.CTPDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['vDDataFim']))
		$where .= 'AND c.CTPDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['vDDataVencimentoInicio']))
		$where .= 'AND c.CTPDATAVENCIMENTO >= ? ';
	if(verificarVazio($_POSTDADOS['vDDataVencimentoFim']))
		$where .= 'AND c.CTPDATAVENCIMENTO <= ? ';
	if(verificarVazio($_POSTDADOS['vDDataPagamentoInicio']))
		$where .= 'AND c.CTPDATAPAGAMENTO >= ? ';
	if(verificarVazio($_POSTDADOS['vDDataPagamentoFim']))
		$where .= 'AND c.CTPDATAPAGAMENTO <= ? ';
	if(verificarVazio($_POSTDADOS['vICLISEQUENCIAL']))
		$where .= 'AND c.CTPSEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['vSFAVNOME']))
		$where .= 'AND f.FAVNOMEFANTASIA LIKE ? ';
	if(verificarVazio($_POSTDADOS['vSCTPDESCRICAO']))
		$where .= 'AND c.CTPDESCRICAO LIKE ? ';
	if(verificarVazio($_POSTDADOS['vSFAVCNPJ']))
		$where .= 'AND f.FAVCNPJ = ? ';	
	if(verificarVazio($_POSTDADOS['vITABCENTROCUSTO']))
		$where .= 'AND c.TABCENTROCUSTO = ? ';
	if(verificarVazio($_POSTDADOS['vITABPLANOCONTAS']))
		$where .= 'AND c.TABPLANOCONTAS = ? ';
	if(verificarVazio($_POSTDADOS['vSPosicaoFiltro']))
		if($_POSTDADOS['vSPosicaoFiltro'] == 'P')
			$where .=' AND c.CTPDATAPAGAMENTO IS NOT NULL';
		elseif($_POSTDADOS['vSPosicaoFiltro'] == 'A')
			$where .=' AND c.CTPDATAPAGAMENTO IS NULL';
		elseif($_POSTDADOS['vSPosicaoFiltro'] == 'D')
			$where .=' AND c.CTPDATAVENCIMENTO < CURDATE() ANS cp.CTPDATAPAGAMENTO IS NULL';
	$sql = "SELECT
				c.*, f.FAVNOMEFANTASIA,
				t1.TABDESCRICAO AS CENTROCUSTO,
				t3.TABDESCRICAO AS PLANO
			FROM
				CONTASAPAGAR c
			LEFT JOIN FAVORECIDOS f ON f.FAVCODIGO = c.FAVCODIGO	
			LEFT JOIN TABELAS t1 ON	t1.TABCODIGO = c.TABCENTROCUSTO
			LEFT JOIN TABELAS t3 ON	t3.TABCODIGO = c.TABPLANOCONTAS
			WHERE
				1 = 1
			".	$where	."
			ORDER BY 1 ";
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
	if(verificarVazio($_POSTDADOS['vDDataVencimentoInicio'])){
		$varIni = $_POSTDADOS['vDDataVencimentoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vDDataVencimentoFim'])){
		$varFim = $_POSTDADOS['vDDataVencimentoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vDDataPagamentoInicio'])){
		$varIni = $_POSTDADOS['vDDataPagamentoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vDDataPagamentoFim'])){
		$varFim = $_POSTDADOS['vDDataPagamentoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['vSFAVNOME'])){
		$pesquisa = $_POSTDADOS['vSFAVNOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSCTPDESCRICAO'])){
		$pesquisa = $_POSTDADOS['vSCTPDESCRICAO'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['vSFAVCNPJ']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['vSFAVCNPJ'], PDO::PARAM_STR);
	if(verificarVazio($_POSTDADOS['vITABCENTROCUSTO']))	
		$arrayQuery['parametros'][] = array($_POSTDADOS['vITABCENTROCUSTO'], PDO::PARAM_INT);	
	if(verificarVazio($_POSTDADOS['vITABPLANOCONTAS']))	
		$arrayQuery['parametros'][] = array($_POSTDADOS['vITABPLANOCONTAS'], PDO::PARAM_INT);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateContasPagar($_POSTDADOS, $pSMsg = 'N'){
	if($_POSTDADOS['methodPOST'] == 'insert')
		$vIRepetir = $_POSTDADOS['repetir'];		
	else
		$vIRepetir = 1;  

	if( $periodo = getPeriodicidadeReal($_POSTDADOS['vHPERCODIGO']) ){ 
		$periodicidade_adicional = $periodo['periodicidade_adicional'];
		$periodicidade_base = $periodo['periodicidade_base'];
	}else{
		$periodicidade_base = 'Mensal';
		$periodicidade_adicional = 1;
	} 
	$vDCTPDATAVENCIMENTO = formatar_data($_POSTDADOS['vDCTPDATAVENCIMENTO']);
	for($i=0; $i<$vIRepetir; $i++){
		$_POSTDADOS['vICTPSEQUENCIAL'] = $i+1;	
		if($_POSTDADOS['methodPOST'] == 'insert')
			$_POSTDADOS['vDCTPDATAVENCIMENTO'] = gerarNovaDataPeriodo( $periodicidade_base, $periodicidade_adicional, $i, $vDCTPDATAVENCIMENTO);
		$dadosBanco = array(
			'tabela'  => 'CONTASAPAGAR',
			'prefixo' => 'CTP',
			'fields'  => $_POSTDADOS,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);
	}	
	sweetAlert('', '', 'S', 'cadContasPagar.php?method=update&oid='.$id, 'S');
	return $id; 
}

function fill_ContasPagar($pOid){
	$SqlMain = 'Select c.*, f.FAVNOMEFANTASIA
				 From CONTASAPAGAR c
				 LEFT JOIN FAVORECIDOS f ON f.FAVCODIGO = c.FAVCODIGO
				 Where CTPCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function baixar_lote_ContasaPagar($vACTPCODIGO, $vITABFORMAPAGAMENTO, $vDCTPDATAPAGAMENTO, $vICBACODIGO){
	//print_r($vACTPCODIGO);
	include_once '../../twcore/teraware/php/constantes.php';
	$vConexao = sql_conectar_banco(vGBancoSite);		
	// lancar filhos
	$SqlMain = "UPDATE CONTASAPAGAR SET CTPVALORPAGO = CTPVALORAPAGAR, 
				TABFORMAPAGAMENTO = ".$vITABFORMAPAGAMENTO.",
				CBACODIGO = ".$vICBACODIGO.",
				CTPDATAPAGAMENTO = '".formatar_data_banco($vDCTPDATAPAGAMENTO)."'
				WHERE CTPCODIGO IN (".implode(",", $vACTPCODIGO).")";							
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	/*
	$SqlMain = "SELECT CTPVALORAPAGAR, CTPCODIGO, EMPCODIGO
				FROM CONTASAPAGAR 
				WHERE CTPCODIGO IN (".implode(",", $vACTPCODIGO).")";	
	
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	while($reg_RS = sql_retorno_lista($RS_MAIN)){		
		// baixa contas
		$dadosConta = array(
			'vICTPCODIGO'         => $reg_RS["CTPCODIGO"],			
			'vDCTPDATAPAGAMENTO'  => $vDCTPDATAPAGAMENTO,
			'vITABFORMAPAGAMENTO' => $vITABFORMAPAGAMENTO,
			'vITABFORMAPAGAMENTO' => $reg_RS["CTPVALORAPAGAR"],
			'vICBACODIGO'         => $vICBACODIGO,
			'repetir'             => 1,
			'url'				  => ''	
		);
		insertUpdateContasPagar($dadosConta, 'N');
	} 	*/
	return [
        'success' => true,
        'msg' => 'Dados atulizados com sucesso!',
    ];
}
