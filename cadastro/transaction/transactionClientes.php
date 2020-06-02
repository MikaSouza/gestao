<?php
include_once '../../twcore/teraware/php/constantes.php';
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateClientes($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Clientes($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];

	//incluir contatos
	include_once 'transactionContatos.php';
	$vRCONTATOINPI = fill_Contatos($vIOid, 26933);
	$vRCONTATOCOR = fill_Contatos($vIOid, 26936);
	$vRCONTATOCOB = fill_Contatos($vIOid, 26934);

	//incluir endereços
	include_once 'transactionEnderecos.php';
	$vRENDERECOINPI = fill_Enderecos($vIOid, 426);
	$vRENDERECOCOR = fill_Enderecos($vIOid, 475);
	$vRENDERECOCOB = fill_Enderecos($vIOid, 427);
}

if( $_GET['hdn_metodo_fill'] == 'fill_Clientes' )
	fill_Clientes($_GET['vICLICODIGO'], $_GET['formatoRetorno']);

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

function listClientes($_POSTDADOS){
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND C.CLINOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND C.CLICNPJ = ? ';
	
	$sql = "SELECT
				C.CLICODIGO, C.CLISEQUENCIAL, C.CLINOME, C.CLICNPJ, C.CLIDATA_INC, C.CLIDATA_ALT, C.CLISTATUS,
				U.USUNOME AS REPRESENTANTE, T.TABDESCRICAO AS POSICAO
			FROM
				CLIENTES C
			LEFT JOIN USUARIOS U ON U.USUCODIGO = C.CLIRESPONSAVEL
			LEFT JOIN TABELAS T ON T.TABCODIGO = C.CLIPOSICAO
			WHERE
				1 = 1
			".	$where	."
			LIMIT 250	";
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
	}
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateClientes($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CLIENTES',
		'prefixo' => 'CLI',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);

	//incluir endereços
	include_once 'transactionEnderecos.php';
	$_POSTDADOS['vICLICODIGO'] = $id;
	//INPI
	$_POSTDADOS['vHTABCODIGO'] = 426;
	insertUpdateEnderecos($_POSTDADOS, 'N');
	//Correspondencia
	$_POSTDADOS['vHTABCODIGO'] = 475;
	insertUpdateEnderecos($_POSTDADOS, 'N');
	//Cobrança
	$_POSTDADOS['vHTABCODIGO'] = 427;
	insertUpdateEnderecos($_POSTDADOS, 'N');

	//incluir contatos
	include_once 'transactionContatos.php';
	//INPI
	$_POSTDADOS['vHTABCODIGO'] = 26933;
	insertUpdateContatos($_POSTDADOS, 'N');
	//Correspondencia
	$_POSTDADOS['vHTABCODIGO'] = 26936;
	insertUpdateContatos($_POSTDADOS, 'N');
	//Cobrança
	$_POSTDADOS['vHTABCODIGO'] = 26934;
	insertUpdateContatos($_POSTDADOS, 'N');
	return $id;
}

function fill_Clientes($pOid, $formatoRetorno = 'array' ){
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

