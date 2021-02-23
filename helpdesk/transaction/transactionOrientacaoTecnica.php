<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateOrientacaoTecnica($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_OrientacaoTecnica($_GET['oid'], $vAConfiguracaoTela);
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

function listOrientacaoTecnica($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.OXTSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.OXTSTATUS = 'N' ";
	}else
		$where .= "AND C.OXTSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.OXTDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.OXTDATA_INC <= ? ';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL']))
		$where .= 'AND C.OXTSEQUENCIAL = ? ';
	
	$sql = "SELECT
				*
			FROM
				ORIENTACAOTECNICA C
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL'])){		
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL'], PDO::PARAM_INT);
	}	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateOrientacaoTecnica($_POSTDADOS, $pSMsg = 'N'){
	if($_FILES['vHARQUIVO']['error'] == 0){		
		$nomeArquivo = $_POSTDADOS['vIOXTNUMERO'].'_'.$_POSTDADOS['vIOXTANO'].'.pdf';
		uploadArquivo($_FILES['vHARQUIVO'], '../ged/orientacao_tecnica', $nomeArquivo);
	} 
	$dadosBanco = array(
		'tabela'  => 'ORIENTACAOTECNICA',
		'prefixo' => 'OXT',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadOrientacaoTecnica.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);	
	return $id; 
}

function fill_OrientacaoTecnica($pOid){
	$SqlMain = 'SELECT c.*
				 From ORIENTACAOTECNICA c
				 Where c.OXTCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

