<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateTabelasxClassesxMarcas($_POST, 'S'); 	
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_TabelasxClassesxMarcas($_GET['oid'], 'array');
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

function insertUpdateTabelasxClassesxMarcas($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CLASSES',
		'prefixo' => 'CLA',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_TabelasxClassesxMarcas($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                r.*
	            FROM
                    CLASSES r
				WHERE
                    CLACODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listTabelasxClassesxMarcas($_POSTDADOS){
	$where = '';		
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLADESCRICAO']))
		$where .= 'AND CLADESCRICAO LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLANOTAEXPLICATIVA']))
		$where .= 'AND CLANOTAEXPLICATIVA LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLACODCLASSE']))
		$where .= 'AND CLACODCLASSE = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLAGRUPO']))
		$where .= 'AND CLAGRUPO = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND CLASTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND CLASTATUS = 'N' ";
	}else
		$where .= "AND CLASTATUS = 'S' ";
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND CLADATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND CLADATA_INC <= ? ';
	
	$sql = "SELECT
	                r.*
	            FROM
	                CLASSES r
				WHERE
					1 = 1
			".	$where	."
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);				
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLADESCRICAO'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLADESCRICAO'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}				
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLANOTAEXPLICATIVA'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLANOTAEXPLICATIVA'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLACODCLASSE']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLACODCLASSE'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLAGRUPO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLAGRUPO'], PDO::PARAM_INT);	
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
