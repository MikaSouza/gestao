<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateContratos($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Contratos($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirCTR') {
	$config_excluir = array(
		"tabela" => Encriptar("CONTRATOS", 'crud'),
		"prefixo" => "CTR",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "S",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listContratos($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.CLISTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.CLISTATUS = 'N' ";
	}else
		$where .= "AND C.CLISTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND C.CLIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND C.CLIDATA_INC <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND C.CLISEQUENCIAL = ? ';

	$sql = "SELECT
				e.*,
				t.CLINOMEFANTASIA AS CLIENTE,
				b.TABDESCRICAO as TIPOCONTRATO,
                b2.TABDESCRICAO AS POSICAO,
                u.USUNOME AS CONSULTOR
			FROM CONTRATOS e
			LEFT JOIN TABELAS b ON b.TABCODIGO = e.CTRTIPOCONTRATO
			LEFT JOIN TABELAS b2 ON b2.TABCODIGO = e.CTRPOSICAO
			LEFT JOIN USUARIOS u ON u.USUCODIGO = e.CTRVENDEDOR
			LEFT JOIN CLIENTES t ON t.CLICODIGO = e.CLICODIGO
			WHERE CTRSTATUS = 'S'
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}

	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateContratos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CONTRATOS',
		'prefixo' => 'CTR',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadContratos.php',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Contratos($pOid){
	$SqlMain = "Select c.CLINOMEFANTASIA AS CLIENTE, p.*
				 From CONTRATOS p
				 LEFT JOIN CLIENTES c ON c.CLICODIGO = p.CLICODIGO
				 Where p.CTRCODIGO = ".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}
