<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxFeedback')
	listUsuariosxFeedbackFilhos($_POST['pIOID'], 'UsuariosxFeedback');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxFeedback')
	fill_UsuariosxFeedback($_GET['vIUXFCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("USUARIOSXFEEDBACK", 'crud'),
		"prefixo" => "UXF",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxFeedback'){
	$escolaridade['vIUXFCODIGO'] 		 = $_POST['hdn_filho_codgo'];
	$escolaridade['vIUSUCODIGO'] 		 = $_POST['hdn_pai_codgo'];	
	$escolaridade['vITABCODIGOFEEDBACK'] = $_POST['vITABCODIGOFEEDBACK'];
	$escolaridade['vITABCODIGOPERFIL']   = $_POST['vITABCODIGOPERFIL'];
	$escolaridade['vSUXFOBSERVACAO']     = $_POST['vSUXFOBSERVACAO'];
	$escolaridade['vIEMPCODIGO']         = 1;

	$vIOID = insertUpdateUsuariosxFeedback($escolaridade, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxFeedback($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXFEEDBACK',
		'prefixo' => 'UXF',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxFeedback($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					USUARIOSXFEEDBACK
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

function listUsuariosxFeedback(){

	$sql = "SELECT
				f.*,
                t.TABDESCRICAO AS FEEDBACK,
                t1.TABDESCRICAO AS PERFIL,
                u.USUNOME
            FROM
                USUARIOSXFEEDBACK f
			LEFT JOIN
                TABELAS t
            ON
                t.TABCODIGO = f.TABCODIGOFEEDBACK
           	LEFT JOIN
                TABELAS t1
            ON
                t1.TABCODIGO = f.TABCODIGOPERFIL
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

function listUsuariosxFeedbackFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				f.*,
                t.TABDESCRICAO AS FEEDBACK,
                t1.TABDESCRICAO AS PERFIL,
                u.USUNOME
            FROM
                USUARIOSXFEEDBACK f
			LEFT JOIN
                TABELAS t
            ON
                t.TABCODIGO = f.TABCODIGOFEEDBACK
           	LEFT JOIN
                TABELAS t1
            ON
                t1.TABCODIGO = f.TABCODIGOPERFIL
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
	$vAConfig['TRANSACTION'] = "transactionUsuariosxFeedback.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxFeedback";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxFeedback";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Feedback', 'Perfil', 'Observação', 'Data Cadastro');
	$vAConfig['vACampos']      = array('UXFCODIGO', 'FEEDBACK', 'PERFIL', 'UXFOBSERVACAO', 'UXFDATA_INC');
	$vAConfig['vATipos']       = array('chave', 'varchar', 'varchar', 'varchar', 'date');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}

?>