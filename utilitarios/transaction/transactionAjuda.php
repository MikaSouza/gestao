<?php
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateAjuda($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Ajuda($_GET['oid'], $vAConfiguracaoTela);
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

function listAjuda($_POSTDADOS){
	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME']))
		$where .= 'AND p.PRONOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vISOESOLICITANTE']))
		$where .= 'AND s.SOESOLICITANTE = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND s.SOEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND s.SOEDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaInicio']))
		$where .= 'AND s.SOEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaFim']))
		$where .= 'AND s.SOEDATA_INC <= ? ';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSSOEPOSICAO'])){
		$where .= 'AND h.HELPOSICAO = ? ';
	}else 
		$where .= "AND h.HELSTATUS = 'S' ";
	$sql = 'SELECT
					h.*,
					t.TABDESCRICAO AS DEPARTAMENTO,
					CONCAT("<a href=\'", HELLINK ,"\' target=\'_blank\'> ABRIR </a>") AS LINKRADIO
				FROM
					HELPVIDEOS h
				LEFT JOIN
					TABELAS t ON h.HELSETOR = t.TABCODIGO
				WHERE
					1 = 1 '. $where.'
				ORDER BY 1 ';				
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME'])){
		$vSPRONOME = $_POSTDADOS['FILTROS']['vSPRONOME'];
		$arrayQuery['parametros'][] = array("%$vSPRONOME%", PDO::PARAM_STR);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vISOESOLICITANTE']))		
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vISOESOLICITANTE'], PDO::PARAM_INT);	
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}	
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataEntregaInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataEntregaFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSSOEPOSICAO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSSOEPOSICAO'], PDO::PARAM_STR);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateAjuda($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'HELPVIDEOS',
		'prefixo' => 'HEL',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadAjuda.php',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Ajuda($pOid){
	$SqlMain = "SELECT
                    *
                FROM HELPVIDEOS
                    WHERE HELCODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}