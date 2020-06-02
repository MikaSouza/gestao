<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchProspecacao')
    listProspeccao($_POST);

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateProspeccao($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Prospeccao($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$vIRESPONSAVEL = $vROBJETO['CXPREPRESENTANTE'];
	
	//fill contato
	include_once '../cadastro/transaction/transactionContatos.php';	
	$vRCONTATOINPI = fill_Contatos($vROBJETO['CLICODIGO'], 26933);

	//fill endereço
	include_once '../cadastro/transaction/transactionEnderecos.php';	
	$vRENDERECOINPI = fill_Enderecos($vROBJETO['CLICODIGO'], 426);
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

function listProspeccao($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['vSStatusFiltro'])){
		if($_POSTDADOS['vSStatusFiltro'] == 'S')
			$where .= "AND C.CXPSTATUS = 'S' ";
		else if($_POSTDADOS['vSStatusFiltro'] == 'N')
			$where .= "AND C.CXPSTATUS = 'N' ";
	}else
		$where .= "AND C.CXPSTATUS = 'S' ";
	if(verificarVazio($_POSTDADOS['vSTipoFiltro'])){
		if($_POSTDADOS['vSTipoFiltro'] == 'I')
			$where .= "AND C.CXPTIPO = 'I' ";
		else if($_POSTDADOS['vSTipoFiltro'] == 'P')
			$where .= "AND C.CXPTIPO = 'P' ";
	}
	if(verificarVazio($_POSTDADOS['vDDataInicio']))
		$where .= 'AND C.CXPDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['vDDataFim']))
		$where .= 'AND C.CXPDATA_INC <= ? ';
	$sql = "SELECT
				C.CXPTIPO,
				CASE C.CXPTIPO
				WHEN 'S' THEN 'SOLICITAÇÃO'
				WHEN 'I' THEN 'INDICAÇÃO'				
				ELSE 'PROSPECÇÃO' END AS TIPO,
				C.*, P.PXCNOME, T.CLINOME, 
				U.USUNOME AS REPRESENTANTE, U2.USUNOME AS ATENDENTE,
				T2.PXSNOME AS PRODUTO
			FROM PROSPECCAO C	
			LEFT JOIN POSICOESCRM P ON P.PXCCODIGO = C.PXCCODIGO	
			LEFT JOIN CLIENTES T ON T.CLICODIGO = C.CLICODIGO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = C.CXPREPRESENTANTE
			LEFT JOIN USUARIOS U2 ON U2.USUCODIGO = C.CXPUSU_INC
			LEFT JOIN PRODUTOSXSERVICOS T2 ON T2.PXSCODIGO = C.PXSCODIGO
			WHERE 1 = 1 
			".	$where	."	
			ORDER BY CXPDATA_INC desc 
			LIMIT 100 ";
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
	$result = consultaComposta($arrayQuery);
	return $result;
}

function insertUpdateProspeccao($_POSTDADOS, $pSMsg = 'N'){
	include_once '../cadastro/transaction/transactionClientes.php';
	
	if ($_POSTDADOS['vSCXPTIPO'] == 'S') { // solicitacao 
	
		$_POSTDADOSCLI['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCLI['vSCLITIPOCLIENTE'] = $_POSTDADOS['vHCLITIPOCLIENTE'];
		$_POSTDADOSEND['vSCLICPF'] = $_POSTDADOS['vHCLICPF']; 
		$_POSTDADOSCLI['vSCLICNPJ'] = $_POSTDADOS['vHCLICNPJ'];
		$_POSTDADOSCLI['vSCLINOME'] = $_POSTDADOS['vHCLINOME'];		
		$_POSTDADOSCLI['vICLIPOSICAO'] = 30;
		$_POSTDADOSCLI['vICLITIPOCADASTRO'] = 28;
		$_POSTDADOSCLI['vICLIRESPONSAVEL'] = $_POSTDADOS['vICXPREPRESENTANTE'];
	
		$_POSTDADOS['vICLICODIGO'] = insertUpdateClientes($_POSTDADOSCLI, 'N');
		
	} else if ($_POSTDADOS['vSCXPTIPO'] == 'P') { // prospecacao 
		// incluir cliente
		$_POSTDADOSCLI['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCLI['vSCLITIPOCLIENTE'] = $_POSTDADOS['vHCLITIPOCLIENTE'];
		$_POSTDADOSEND['vSCLICPF'] = $_POSTDADOS['vHCLICPF']; 
		$_POSTDADOSCLI['vSCLICNPJ'] = $_POSTDADOS['vHCLICNPJ'];
		$_POSTDADOSCLI['vSCLINOME'] = $_POSTDADOS['vHCLINOME'];
		$_POSTDADOSCLI['vIEMPCODIGO'] = 1;
		$_POSTDADOSCLI['vICLIPOSICAO'] = 30;
		$_POSTDADOSCLI['vICLITIPOCADASTRO'] = 29;
		$_POSTDADOSCLI['vICLIRESPONSAVEL'] = $_POSTDADOS['vICXPREPRESENTANTE'];
		
		//incluir endereços
		if ($_POSTDADOS['vHINPIENDLOGRADOURO'] != '') { //INPI
			$_POSTDADOSCLI['vIENDCODIGO'] = $_POSTDADOS['vHINPIENDCODIGO'];
			$_POSTDADOSCLI['vHTABCODIGO'] = 426;
			$_POSTDADOSCLI['vSENDLOGRADOURO'] = $_POSTDADOS['vHINPIENDLOGRADOURO']; 
			$_POSTDADOSCLI['vIESTCODIGO'] = $_POSTDADOS['vHINPIESTCODIGO'];
			$_POSTDADOSCLI['vIPAICODIGO'] = $_POSTDADOS['vHINPIPAICODIGO'];
			$_POSTDADOSCLI['vICIDCODIGO'] = $_POSTDADOS['vHINPICIDCODIGO'];
			$_POSTDADOSCLI['vSENDNROLOGRADOURO'] = $_POSTDADOS['vHINPIENDNROLOGRADOURO'];
			$_POSTDADOSCLI['vSENDBAIRRO'] = $_POSTDADOS['vHINPIENDBAIRRO'];
			$_POSTDADOSCLI['vSENDCEP'] = $_POSTDADOS['vHINPIENDCEP'];
			$_POSTDADOSCLI['vSENDCOMPLEMENTO'] = $_POSTDADOS['vHINPIENDCOMPLEMENTO'];			
		}	
		
		//incluir contatos
		if ($_POSTDADOS['vHINPICONNOME'] != '') { //INPI
			$_POSTDADOSCLI['vICONCODIGO'] = '';
			$_POSTDADOSCLI['vHTABCODIGO'] = 26933;
			$_POSTDADOSCLI['vSCONNOME'] = $_POSTDADOS['vHINPICONNOME']; 
			$_POSTDADOSCLI['vSCONEMAIL'] = $_POSTDADOS['vSCXPEMAIL'];
			$_POSTDADOSCLI['vSCONCELULAR'] = $_POSTDADOS['vHINPICONCELULAR'];
			$_POSTDADOSCLI['vSCONFONE'] = $_POSTDADOS['vSCXPFONE'];	
		}
		
		$_POSTDADOS['vICLICODIGO'] = insertUpdateClientes($_POSTDADOSCLI, 'N');

	}
	$dadosBanco = array(
		'tabela'  => 'PROSPECCAO',
		'prefixo' => 'CXP',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => $_POSTDADOS['vHURL'],
		'debug'   => 'S'
	);
	$id = insertUpdate($dadosBanco);
	
	// enviar e-mail
	
	// enviar sms
	
	return $id;
}

function fill_Prospeccao($pOid){
	$SqlMain = "SELECT
                    c.CLISEQUENCIAL, c.CLINOME AS CLIENTE, p.*,
					c.CLICNPJ, c.CLICPF, c.CLITIPOCLIENTE
                FROM PROSPECCAO p
				LEFT JOIN CLIENTES c ON c.CLICODIGO = p.CLICODIGO
                    WHERE p.CXPCODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function fillProspeccaoxClientes($pOid){
	$SqlMain = "SELECT
                    CONCAT(CLISEQUENCIAL,' - ', CLINOME) AS CLIENTE, CLIRESPONSAVEL, CLICODIGO
                FROM CLIENTES
                    WHERE CLICODIGO  =".$pOid;				
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}