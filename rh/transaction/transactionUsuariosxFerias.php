<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxFerias')
	listUsuariosxFeriasFilhos($_POST['pIOID'], 'UsuariosxFerias');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxFerias')
	fill_UsuariosxFerias($_GET['vIUXFCODIGO'], $_GET['formatoRetorno']);

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

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOSXFERIAS", 'crud'),
		"prefixo" => "UXF",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxFerias'){
	$ferias['vIUXFCODIGO'] 		 	  = $_POST['hdn_filho_codgo'];
	$ferias['vIUSUCODIGO'] 		 	  = $_POST['hdn_pai_codgo'];	
	$ferias['vDUXFDATAAQUISITIVO1']   = $_POST['vDUXFDATAAQUISITIVO1'];
	$ferias['vDUXFDATAAQUISITIVO2']   = $_POST['vDUXFDATAAQUISITIVO2'];
	$ferias['vDUXFDATALIMITEGOZO'] 	= $_POST['vDUXFDATALIMITEGOZO'];
	$ferias['vDUXFDATAGOZOINICIAL'] 	= $_POST['vDUXFDATAGOZOINICIAL'];
	$ferias['vDUXFDATAGOZOFINAL'] 	= $_POST['vDUXFDATAGOZOFINAL'];
	$ferias['vIEMPCODIGO']            = 1;

	$vIOID = insertUpdateUsuariosxFerias($ferias, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxFerias($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXFERIAS',
		'prefixo' => 'UXF',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxFerias($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT *
				FROM
					USUARIOSXFERIAS
				WHERE
					UXFCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listUsuariosxFerias(){

	$sql = "SELECT
				f.*,
                u.USUNOME
            FROM
                USUARIOSXFERIAS f
            LEFT JOIN
                USUARIOS u
            ON
                f.USUCODIGO = u.USUCODIGO
			WHERE f.UXFSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxFeriasFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				f.*,
                u.USUNOME
            FROM
                USUARIOSXFERIAS f
            LEFT JOIN
                USUARIOS u
            ON
                f.USUCODIGO = u.USUCODIGO
			WHERE f.UXFSTATUS = 'S' AND
			f.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxFerias.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxFerias";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxFerias";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Data Aquisitivo Início', 'Data Aquisitivo Fim', 'Limite Gozo', 'Data Gozo Inicial', 'Data Gozo Final');
	$vAConfig['vACampos']      = array('UXFCODIGO', 'UXFDATAAQUISITIVO1', 'UXFDATAAQUISITIVO2', 'UXFDATALIMITEGOZO', 'UXFDATAGOZOINICIAL', 'UXFDATAGOZOFINAL');
	$vAConfig['vATipos']       = array('chave', 'date','date', 'date','date', 'date');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}
?>