<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxDesligamentos')
	listUsuariosxDesligamentosFilhos($_POST['pIOID'], 'UsuariosxDesligamentos');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxDesligamentos')
	fill_UsuariosxDesligamentos($_GET['vIUXECODIGO'], $_GET['formatoRetorno']);

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

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxDesligamentos'){
	$escolaridade['vIUXECODIGO'] 			 = $_POST['hdn_filho_codgo'];
	$escolaridade['vIUSUCODIGO'] 			 = $_POST['hdn_pai_codgo'];
	$escolaridade['vITABCODIGOESCOLARIDADE'] = $_POST['vITABCODIGOESCOLARIDADE'];
	$escolaridade['vSUXESEMESTRE']           = $_POST['vSUXESEMESTRE'];
	$escolaridade['vSUXEINSTITUICAO']        = $_POST['vSUXEINSTITUICAO'];
	$escolaridade['vSUXECURSO']              = $_POST['vSUXECURSO'];
	$escolaridade['vDUXEDATAINICIO']         = $_POST['vDUXEDATAINICIO'];
	$escolaridade['vDUXEDATAFIM']            = $_POST['vDUXEDATAFIM'];
	$escolaridade['vIEMPCODIGO']             = 1;

	$vIOID = insertUpdateUsuariosxDesligamentos($escolaridade, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxDesligamentos($_POSTDADOS, $pSMsg = 'N'){
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

function fill_UsuariosxDesligamentos($pOid, $formatoRetorno = 'array' ){
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

function listUsuariosxDesligamentos(){

	$sql = "SELECT
				u.USUNOME,
				u.USUDATADEMISSAO,
				t.TABDESCRICAO AS MOTIVODESLIGAMENTO,
				u.USUOBSERVACAODESLIGAMENTO
			FROM
				USUARIOS u			        
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.USUMOTIVODESLIGAMENTO		
			WHERE u.USUSTATUS = 'S' 
			AND u.USUDATADEMISSAO IS NOT NULL
			ORDER BY u.USUDATADEMISSAO DESC ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxDesligamentosFilhos($vIOIDPAI, $tituloModal){
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
	$vAConfig['TRANSACTION'] = "transactionUsuariosxDesligamentos.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxDesligamentos";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxDesligamentos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']  = array('', 'Grau de Escolaridade', 'Ano / Semestre', 'Instituição de Ensino', 'Curso', 'Data Início', 'Data Fim / Previsão', 'Ativo');
	$vAConfig['vACampos']   = array('UXECODIGO', 'ESCOLARIDADE', 'UXESEMESTRE', 'UXEINSTITUICAO', 'UXECURSO', 'UXEDATAINICIO', 'UXEDATAFIM', 'UXESTATUS');
	$vAConfig['vATipos']    = array('chave', 'varchar', 'varchar', 'varchar','varchar', 'date', 'date', 'simNao');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}

?>