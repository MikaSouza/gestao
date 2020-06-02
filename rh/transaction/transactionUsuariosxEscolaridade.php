<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxEscolaridade')
	listUsuariosxEscolaridadeFilhos($_POST['pIOID'], 'UsuariosxEscolaridade');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxEscolaridade')
	fill_UsuariosxEscolaridade($_GET['vIUXECODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("USUARIOSXESCOLARIDADE", 'crud'),
		"prefixo" => "UXE",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxEscolaridade'){
	$escolaridade['vIUXECODIGO'] 			 = $_POST['hdn_filho_codgo'];
	$escolaridade['vIUSUCODIGO'] 			 = $_POST['hdn_pai_codgo'];
	$escolaridade['vITABCODIGOESCOLARIDADE'] = $_POST['vITABCODIGOESCOLARIDADE'];
	$escolaridade['vSUXESEMESTRE']           = $_POST['vSUXESEMESTRE'];
	$escolaridade['vSUXEINSTITUICAO']        = $_POST['vSUXEINSTITUICAO'];
	$escolaridade['vSUXECURSO']              = $_POST['vSUXECURSO'];
	$escolaridade['vDUXEDATAINICIO']         = $_POST['vDUXEDATAINICIO'];
	$escolaridade['vDUXEDATAFIM']            = $_POST['vDUXEDATAFIM'];
	$escolaridade['vIEMPCODIGO']             = 1;

	$vIOID = insertUpdateUsuariosxEscolaridade($escolaridade, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxEscolaridade($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXESCOLARIDADE',
		'prefixo' => 'UXE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxEscolaridade($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					USUARIOSXESCOLARIDADE
				WHERE
					UXECODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listUsuariosxEscolaridade(){

	$sql = "SELECT
				e.*,
				t.TABDESCRICAO AS ESCOLARIDADE,
				u.USUNOME
			FROM
				USUARIOSXESCOLARIDADE e
			LEFT JOIN
                TABELAS t
            ON
                e.TABCODIGOESCOLARIDADE = t.TABCODIGO
            LEFT JOIN
                USUARIOS u
            ON
                e.USUCODIGO = u.USUCODIGO
			WHERE e.UXESTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxEscolaridadeFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				e.*,
				t.TABDESCRICAO AS ESCOLARIDADE,
				u.USUNOME
			FROM
				USUARIOSXESCOLARIDADE e
			LEFT JOIN
                TABELAS t
            ON
                e.TABCODIGOESCOLARIDADE = t.TABCODIGO
            LEFT JOIN
                USUARIOS u
            ON
                e.USUCODIGO = u.USUCODIGO
			WHERE e.UXESTATUS = 'S' AND
			e.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxEscolaridade.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxEscolaridade";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxEscolaridade";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']  = array('', 'Grau de Escolaridade', 'Ano / Semestre', 'Instituição de Ensino', 'Curso', 'Data Início', 'Data Fim / Previsão', 'Ativo');
	$vAConfig['vACampos']   = array('UXECODIGO', 'ESCOLARIDADE', 'UXESEMESTRE', 'UXEINSTITUICAO', 'UXECURSO', 'UXEDATAINICIO', 'UXEDATAFIM', 'UXESTATUS');
	$vAConfig['vATipos']    = array('chave', 'varchar', 'varchar', 'varchar','varchar', 'date', 'date', 'simNao');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}

?>