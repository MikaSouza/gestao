<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxContasBancarias')
	listUsuariosxContasBancariasFilhos($_POST['pIOID'], 'UsuariosxContasBancarias');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxContasBancarias')
	fill_UsuariosxContasBancarias($_GET['vIUXBCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirUsuariosxContasBancarias'){
	$_POSTCHG['vIUXBCODIGO'] = $_POST['hdn_filho_codigo'];
	$_POSTCHG['vIUSUCODIGO'] = $_POST['hdn_pai_codigo'];
	$_POSTCHG['vIBACCODIGO'] = $_POST['vIBACCODIGO'];
	$_POSTCHG['vIUXBTIPO'] = $_POST['vIUXBTIPO'];
	$_POSTCHG['vSUXBAGENCIA'] = $_POST['vSUXBAGENCIA'];
	$_POSTCHG['vSUXBCONTA'] = $_POST['vSUXBCONTA'];
	$_POSTCHG['vIEMPCODIGO']  = 1;//$_SESSION["SI_USU_EMPRESA"];
	echo insertUpdateUsuariosxContasBancarias($_POSTCHG, 'N');
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

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOSXCONTASBANCARIAS", 'crud'),
		"prefixo" => "UXB",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateUsuariosxContasBancarias($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXCONTASBANCARIAS',
		'prefixo' => 'UXB',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function listUsuariosxContasBancarias(){

	$sql = "SELECT
	                u.*, b.BACBANCO as NOMEDOBANCO, r.USUNOME AS NOMEDEUSUARIO
	            FROM
	                USUARIOSXCONTASBANCARIAS u
				LEFT JOIN
	               USUARIOS r
	            ON
	                u.USUCODIGO = r.USUCODIGO	
	            LEFT JOIN
	            	BANCOS b
	            ON
	            	u.BACCODIGO = b.BACCODIGO
				WHERE
					u.UXBSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function fill_UsuariosxContasBancarias($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                u.*, b.BACBANCO as NOMEDOBANCO,b.BACCODIGO
	            FROM
	                USUARIOSXCONTASBANCARIAS u
	            LEFT JOIN
					BANCOS b
	            ON
	            	u.BACCODIGO = b.BACCODIGO
				WHERE
					u.UXBSTATUS = 'S'
				AND
					u.UXBCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listUsuariosxContasBancariasFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				BANCOS.BACBANCO,
				USUARIOSXCONTASBANCARIAS.UXBAGENCIA,
				USUARIOSXCONTASBANCARIAS.UXBCONTA,
				USUARIOSXCONTASBANCARIAS.UXBCODIGO
			FROM
				USUARIOSXCONTASBANCARIAS
			JOIN BANCOS ON BANCOS.BACCODIGO = USUARIOSXCONTASBANCARIAS.BACCODIGO
			WHERE
				UXBSTATUS = 'S' AND
				USUARIOSXCONTASBANCARIAS.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxContasBancarias.php";
	$vAConfig['DIV_RETORNO'] = "div_DadosBancarios";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxContasBancarias";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Banco', 'Agência', 'Conta');
	$vAConfig['vACampos'] 	= array('UXBCODIGO', 'BACBANCO', 'UXBAGENCIA', 'UXBCONTA');
	$vAConfig['vATipos'] 	= array('chave', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}