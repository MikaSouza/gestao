<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateContasReceber($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_ContasReceber($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCTR') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("CONTASARECEBER", 'crud'),
        "prefixo"  => "CTR",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

if(isset($_POST["method"]) && $_POST["method"] == 'faturarContasReceber')
	lancar_faturamento_nfse_contas_receber($_POST['pLOids'], $_POST['pSUnificar']);

function listContasReceber($_POSTDADOS){
	$where = '';
	// Separar por tipo
	if($_POSTDADOS['FILTROS']['vSTipoPesquisa'] == 'S') {//Sintético
		
		if(verificarVazio($_POSTDADOS['vIEMPCODIGO']))
			$where .= 'AND cp.EMPCODIGO = ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
			$where .= 'AND C.CLISEQUENCIAL = ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
			$where .= 'AND c.CLINOME LIKE ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
			if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
				$where .= "AND cp.CTRSTATUS = 'S' ";
			else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
				$where .= "AND cp.CTRSTATUS = 'N' ";
		}else
			$where .= "AND cp.CTRSTATUS = 'S' ";

		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
			$where .= 'AND cp.CTRDATA_INC >= ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
			$where .= 'AND cp.CTRDATA_INC <= ? ';
		
		$sql = "SELECT
					cp.*,
					c.CLINOME,
					e.EMPNOMEFANTASIA,
					u.USUNOME AS CONSULTOR,
					t1.TABDESCRICAO AS CENTROCUSTO,
					t3.TABDESCRICAO AS PLANO
				FROM
					CONTASARECEBER cp 
				LEFT JOIN CLIENTES c ON c.CLICODIGO = cp.CLICODIGO	
				LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = cp.EMPCODIGO	
				LEFT JOIN USUARIOS u ON	cp.CTRCONSULTOR = u.USUCODIGO
				LEFT JOIN TABELAS t1 ON	t1.TABCODIGO = cp.TABCENTROCUSTO
				LEFT JOIN TABELAS t3 ON	t3.TABCODIGO = cp.TABPLANOCONTAS
				WHERE
					cp.CTRSTATUS = 'S'
					".	$where	."
				LIMIT 20 ";
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		if(verificarVazio($_POSTDADOS['vIEMPCODIGO']))	
			$arrayQuery['parametros'][] = array($_POSTDADOS['vIEMPCODIGO'], PDO::PARAM_INT);			
		if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
			$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);				
		if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
			$vSCLINOME = $_POSTDADOS['FILTROS']['vSCLINOME'];
			$arrayQuery['parametros'][] = array("%$vSCLINOME%", PDO::PARAM_STR);
		}			
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
			$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
			$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
		}
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
			$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
			$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
		}
	} else { // Analítico // Parcelas
		if(verificarVazio($_POSTDADOS['vIEMPCODIGO']))
			$where .= 'AND cp.EMPCODIGO = ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
			$where .= 'AND C.CLISEQUENCIAL = ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
			$where .= 'AND c.CLINOME LIKE ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
			if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
				$where .= "AND cp.CTRSTATUS = 'S' ";
			else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
				$where .= "AND cp.CTRSTATUS = 'N' ";
		}else
			$where .= "AND cp.CTRSTATUS = 'S' ";

		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
			$where .= 'AND cp.CTRDATA_INC >= ? ';
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
			$where .= 'AND cp.CTRDATA_INC <= ? ';
		
		$sql = "SELECT
					cp.*,
					c.CLINOME,
					e.EMPNOMEFANTASIA,
					u.USUNOME AS CONSULTOR,
					t1.TABDESCRICAO AS CENTROCUSTO,
					t3.TABDESCRICAO AS PLANO
				FROM 
					CONTASARECEBERXPARCELAS cxp
				LEFT JOIN CONTASARECEBER cp ON cp.CTRCODIGO = cxp.CTRCODIGO	
				LEFT JOIN CLIENTES c ON c.CLICODIGO = cp.CLICODIGO	
				LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = cp.EMPCODIGO	
				LEFT JOIN USUARIOS u ON	cp.CTRCONSULTOR = u.USUCODIGO
				LEFT JOIN TABELAS t1 ON	t1.TABCODIGO = cp.TABCENTROCUSTO
				LEFT JOIN TABELAS t3 ON	t3.TABCODIGO = cp.TABPLANOCONTAS
				WHERE
					cp.CTRSTATUS = 'S' AND cxp.CXPSTATUS = 'S'
					".	$where	."
				LIMIT 20 ";
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		if(verificarVazio($_POSTDADOS['vIEMPCODIGO']))	
			$arrayQuery['parametros'][] = array($_POSTDADOS['vIEMPCODIGO'], PDO::PARAM_INT);			
		if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
			$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);				
		if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
			$vSCLINOME = $_POSTDADOS['FILTROS']['vSCLINOME'];
			$arrayQuery['parametros'][] = array("%$vSCLINOME%", PDO::PARAM_STR);
		}			
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
			$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
			$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
		}
		if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
			$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
			$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
		}

	}	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateContasReceber($_POSTDADOS, $pSMsg = 'N'){
	
	$dadosBanco = array(
		'tabela'  => 'CONTASARECEBER',
		'prefixo' => 'CTR',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);		
	include_once('transactionContasReceberxParcelas.php');
	$dadosCXP = array(
		'vICTRCODIGO'  => $id,
		'vICXPFORMAPAGAMENTO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vSCXPNUMERO'  => $_POSTDADOS['vSCTRNRODOCUMENTO'],
		'vDCXPDATAVENCIMENTO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vMCXPVALOR'  => $_POSTDADOS['vHCXPVALOR'],
		'vSCXPDESCRICAO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vDCXPDATAPAGAMENTO'  => $_POSTDADOS['vHCXPDATAPAGAMENTO'],
		'vICBACODIGO'  => $_POSTDADOS['vICBACODIGO'],
		'vSCXPMODOFATURAMENTO'  => $_POSTDADOS['vICTRMODOFATURAMENTO'],
		'vSCXPPROTESTO'  => $_POSTDADOS['vHCXPPROTESTO'],
		'vSCXPDIRETORIA'  => $_POSTDADOS['vHCXPDIRETORIA'],
		'vSCXPAUTCLIENTE'  => $_POSTDADOS['vHCXPAUTCLIENTE'],
		'vSCXPAUTCOMISSAO'  => $_POSTDADOS['vHCXPAUTCOMISSAO'],
		'vSCXPEXTERIOR'  => $_POSTDADOS['vHCXPEXTERIOR'],
		'vICXPPOSICAO'  => $_POSTDADOS['vHCXPPOSICAO'],
		'vICXPFORMACOBRANCA'  => $_POSTDADOS['vHCXPFORMACOBRANCA'],
		'vICXPCLASSIFICACAO'  => $_POSTDADOS['vHCXPCLASSIFICACAO'],		
	);	
	insertUpdateContasReceberxParcelas($dadosCXP, 'N'); 
	
	if ($_POSTDADOS['vHGERARCA'] == 'S'){// CA
		include_once('../comercial/transactionCAs.php');
		if ($_POSTDADOS['vHNUMEROCA1'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA1'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}	
		if ($_POSTDADOS['vHNUMEROCA2'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA2'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA3'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA3'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA4'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA4'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA5'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA5'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vMCXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
	}
	if ($_POSTDADOS['vHGERARCONTRATO'] == 'S'){// CONTRATO
		include_once('../comercial/transactionContratos.php');
		if ($_POSTDADOS['vHCTRCONTRATO'] != ''){
			$dadosBancoCTR = array(
				'vICTRCODIGO' 	  => '',
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vIEMPCODIGO'  	  => 1,
				'vICTRNROCONTRATO'   => $_POSTDADOS['vHCTRCONTRATO'],	 		
				'vSCTRDESCRICAO'   	=> $_POSTDADOS['vSCTRDESCRICAO'],				
				'vICTRTIPOCONTRATO'  => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vDCTRDATAAINICIO'       => $_POSTDADOS['vHCTRDATAINICIO'],
				'vDCTRDATATERMINO'       => $_POSTDADOS['vHCTRDATAFINAL'],
				'vICTRTIPO'     	=> $_POSTDADOS['vICTRMOEDA'],
				'vMCTRVALORCOBERTURA'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICTRATENDENTE'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateContratos($dadosBancoCTR, 'N'); 
		}
	}
	
	return $id; 
}

function fill_ContasReceber($pOid){
	$SqlMain = "SELECT CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE, p.*
				FROM CONTASARECEBER p
				LEFT JOIN CLIENTES c ON c.CLICODIGO = p.CLICODIGO
				WHERE p.CTRCODIGO = ".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function getDadosRecibo($ctrcodigo, $cxpcodigo = '')
{
	$empcodigo = 2; //$_SESSION["SI_USU_EMPRESA"];

	$qrybase = "SELECT
					p.CLINOME,
					c.CTRDESCRICAO,
					c.CTRVALORARECEBER,
					FORMAT(c.CTRVALORARECEBER, 2, 'pt_BR') AS VALOR_RECEBER,
					(SELECT GROUP_CONCAT(
						DATE_FORMAT(t.CXPDATAVENCIMENTO, '%d/%m/%Y') SEPARATOR ' - ')
				    FROM CONTASARECEBERXPARCELAS t
				    WHERE t.CTRCODIGO = c.CTRCODIGO
				    AND t.CXPSTATUS = 'S'
				    ORDER BY t.CXPDATAVENCIMENTO ASC
					) AS DATAS_VENCIMENTO
				FROM
					CONTASARECEBER c
				LEFT JOIN
					CLIENTES p
				ON
					c.CLICODIGO = p.CLICODIGO
				WHERE
					c.EMPCODIGO = {$empcodigo}
				AND
					c.CTRCODIGO = {$ctrcodigo} ";

	if (!empty($cxpcodigo)) {
		$qrybase .= "AND
						t.CXPCODIGO = ?";
	}
	$query = array();
	$query['query']      = $qrybase;
	$query['parametros'] = array();

	if (!empty($cxpcodigo)) {
		$query['parametros'] = array(
			array($cxpcodigo, PDO::PARAM_INT),
		);
	}

	$data = consultaComposta($query);

	return $data['dados'];
}

function gerar_notas_fiscais_ContasReceber($vACTPCODIGO, $vITABFORMAPAGAMENTO, $vDCTPDATAPAGAMENTO, $vICBACODIGO){
	//print_r($vACTPCODIGO);
	include_once '../../twcore/teraware/php/constantes.php';
	include_once('../comercial/transaction/transactionNotasFiscais.php');
	$vConexao = sql_conectar_banco(vGBancoSite);		
	// buscar contas a receber
	$SqlMain = "SELECT c.CTRCODIGO, c.CLICODIGO, c.ENDCODIGO
				FROM CONTASARECEBER c 
				WHERE CTRCODIGO IN (".implode(",", $vACTPCODIGO).")";							
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);	
	while($reg_RS = sql_retorno_lista($RS_MAIN)){		
		// baixa contas
		$dadosConta = array(
			'vICTRCODIGO'         => $reg_RS["CTRCODIGO"],			
			'vDCTPDATAPAGAMENTO'  => $vDCTPDATAPAGAMENTO, 
			'vITABFORMAPAGAMENTO' => $vITABFORMAPAGAMENTO,
			'vITABFORMAPAGAMENTO' => $reg_RS["CTPVALORAPAGAR"],
			'vICLICODIGO'         => $reg_RS["CLICODIGO"],	
			'vIENDCODIGO'         => $reg_RS["ENDCODIGO"],	
			'repetir'             => 1,
			'url'				  => ''	
		);
		insertUpdateNotasFiscais($dadosConta, 'N');
	}
	return [
        'success' => true,
        'msg' => 'Dados atulizados com sucesso!',
    ];
}

function lancar_faturamento_nfse_contas_receber($pLOids, $pSUnificar){
	include_once '../../twcore/teraware/php/constantes.php';
	include_once '../../comercial/transaction/transactionNotasFiscais.php';
	include_once '../../comercial/transaction/transactionNFSexItens.php';
		//WHERE CTPCODIGO IN (".implode(",", $vACTPCODIGO).")";
	$pOidLista = str_replace("-", ",", $pLOids);	
	$vConexao = sql_conectar_banco();
	// Agrupar quando necessario
	if ($pSUnificar == 'S'){
		$SqlMain = "SELECT SUM(CTRVALORARECEBER) AS TOTAL, CLICODIGO, EMPCODIGO
				FROM
					CONTASARECEBER								
				WHERE CTRCODIGO IN (".$pOidLista.")	
				GROUP BY CLICODIGO, EMPCODIGO";
		$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
		while($reg_RSNFSE = sql_retorno_lista($RS_MAIN)){
			// endereco
			$SqlMain2 = "SELECT ENDCODIGO
					FROM
						ENDERECOS
					WHERE CLICODIGO = ".$reg_RSNFSE['CLICODIGO']."
					AND ENDSTATUS = 'S' 
					AND ENDPADRAO = 'S' 
					LIMIT 1 ";	
			$RS_MAIN2 = sql_executa(vGBancoSite, $vConexao,$SqlMain2);
			while($reg = sql_retorno_lista($RS_MAIN2)){
				$vIENDCODIGO = $reg['ENDCODIGO'];
			}
			$dadosConta = array(
				'vINSECODIGO'         => '',	
				'vINSENUMERORPS'      => 1,	
				'vICTRCODIGO'         => $reg_RSNFSE["CTRCODIGO"],			
				'vIEMPCODIGO'  		  => $reg_RSNFSE["EMPCODIGO"],				
				'vICLICODIGO'         => $reg_RSNFSE["CLICODIGO"],	
				'vIENDCODIGO'         => $vIENDCODIGO,	
				'vISERCODIGO' 		  => 57,
				'vICNACODIGO' 		  => 268,
				'vINOSCODIGO'         => 1,
				'vINSEMUNICIPIOSERVICO'	 => 4314902,
				'vINSEUFSERVICO'	  => 43,
				'vSNSEVALORTOTAL'	  => $reg_RSNFSE['TOTAL'],
				'vSNSEBASECALCULO'	  => $reg_RSNFSE['TOTAL'],
				'vSNSEVALORLIQUIDO'	  => $reg_RSNFSE['TOTAL'],
				'vSNSEISSRETIDO'	  => 2,
				'vSNSEALIQUOTA'	 	  => 0.0821,
				'vSNSEVALORISS'	 	  => (number_format(($reg_RSNFSE['TOTAL'] * 0.0821), 2, ".", "")),
				'vSNSEEMAILENVIO'	  => 'N',
				'vINSECODIGOTRIBUTACAO'	  => '10700100'
				
			);
			$vINSECODIGO = insertUpdateNotasFiscais($dadosConta, 'N');
			$dadosItem = array(
				'vINXSCODIGO'         => '',	
				'vINSECODIGO'         => $vINSECODIGO,			
				'vIEMPCODIGO'  		  => $reg_RSNFSE["EMPCODIGO"],			
				'vSNXSDESCRICAO'      => $reg_RSNFSE['CTRDESCRICAO'],	
				'vSNXSVALOR'          => $reg_RSNFSE['TOTAL']				
			);
			insertUpdateNFSexItens($dadosItem, 'N');

			$SqlUpdate = "Update CONTASARECEBER set NSECODIGO = ".$vINSECODIGO."
						Where CTRCODIGO = ".$reg_RSNFSE['CTRCODIGO'];
			$RS_MAINUpdate = sql_executa(vGBancoSite, $vConexao,$SqlUpdate);
			
		}										
	} else {			
		$SqlMain = "SELECT CLICODIGO, CTRVALORARECEBER, CTRDESCRICAO, CTRCODIGO, EMPCODIGO
				FROM
					CONTASARECEBER							
				WHERE CTRCODIGO IN (".implode(",", $pLOids).")	";
		$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
		while($reg_RSNFSE = sql_retorno_lista($RS_MAIN)){
			// endereco
			$SqlMain2 = "SELECT ENDCODIGO
					FROM
						ENDERECOS
					WHERE CLICODIGO = ".$reg_RSNFSE['CLICODIGO']."
					LIMIT 1";	
			$RS_MAIN2 = sql_executa(vGBancoSite, $vConexao,$SqlMain2);
			$vIENDCODIGO = '';
			while($reg = sql_retorno_lista($RS_MAIN2)){
				$vIENDCODIGO = $reg['ENDCODIGO'];
			}

			$dadosConta = array(
				'vINSECODIGO'         => '',	
				'vINSENUMERORPS'      => 1,	
				'vICTRCODIGO'         => $reg_RSNFSE["CTRCODIGO"],			
				'vIEMPCODIGO'  		  => $reg_RSNFSE["EMPCODIGO"],				
				'vICLICODIGO'         => $reg_RSNFSE["CLICODIGO"],	
				'vIENDCODIGO'         => $vIENDCODIGO,	
				'vISERCODIGO' 		  => 57,
				'vICNACODIGO' 		  => 268,
				'vINOSCODIGO'         => 1,
				'vINSEMUNICIPIOSERVICO'	 => 4314902,
				'vINSEUFSERVICO'	  => 43,
				'vSNSEVALORTOTAL'	  => $reg_RSNFSE['CTRVALORARECEBER'],
				'vSNSEBASECALCULO'	  => $reg_RSNFSE['CTRVALORARECEBER'],
				'vSNSEVALORLIQUIDO'	  => $reg_RSNFSE['CTRVALORARECEBER'],
				'vSNSEISSRETIDO'	  => 2,
				'vSNSEALIQUOTA'	 	  => 0.0821,
				'vSNSEVALORISS'	 	  => (number_format(($reg_RSNFSE['CTRVALORARECEBER'] * 0.0821), 2, ".", "")),
				'vSNSEEMAILENVIO'	  => 'N',
				'vINSECODIGOTRIBUTACAO'	  => '10700100'
				
			);
			$vINSECODIGO = insertUpdateNotasFiscais($dadosConta, 'N');					
			$dadosItem = array(
				'vINXSCODIGO'         => '',	
				'vINSECODIGO'         => $vINSECODIGO,			
				'vIEMPCODIGO'  		  => $reg_RSNFSE["EMPCODIGO"],			
				'vSNXSDESCRICAO'      => $reg_RSNFSE['CTRDESCRICAO'],	
				'vSNXSVALOR'          => $reg_RSNFSE['CTRVALORARECEBER']				
			);
			insertUpdateNFSexItens($dadosItem, 'N');				
			
			$SqlUpdate = "Update CONTASARECEBER set NSECODIGO = ".$vINSECODIGO."
						Where CTRCODIGO = ".$reg_RSNFSE['CTRCODIGO'];
			$RS_MAINUpdate = sql_executa(vGBancoSite, $vConexao,$SqlUpdate);
			
		}	
	}
	echo 'Registro(s) lançado(s) com sucesso!';			
						

	//sql_fechar_conexao_banco($vConexao);
	//echo "<script language='javaScript'>window.location.href='../interface/listFaturamentoTreinamento.php'</script>";
	return;
}
