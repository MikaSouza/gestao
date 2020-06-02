<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxDocumentoPendente')
	listUsuariosxDocumentoPendenteFilhos($_POST['pIOID'], 'UsuariosxDocumentoPendente');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxDocumentoPendente')
	fill_UsuariosxDocumentoPendente($_GET['UXECODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOSXDOCUMENTOSPENDENTES", 'crud'),
		"prefixo" => "UXD",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if (isset($_POST['method']) && $_POST['method'] ='insertUpdate'){
	$oidUsuario 			   = $_POST['hdn_oid'];
	$dados['vIOidListaAcesso'] = explode(',',$_POST['vIOidListaAcesso']);
	$dados['vIOidLista']  	   = explode(',',$_POST['vIOidLista']);
	$dados['listEntregue']     = explode(',',$_POST['listEntregue']);
	$dados['listPendente']     = explode(',',$_POST['listPendente']);

	$id         = '';
	$contador   = array();
	$documentos = array();

	foreach ($dados['vIOidLista'] as $key => $value) {
		$documentos['vIUXDCODIGO']   = $dados['vIOidListaAcesso'][$key];
		$documentos['vSUXDENTREGUE'] = $dados['listEntregue'][$key];
		$documentos['vSUXDPENDENTE'] = $dados['listPendente'][$key];
		$documentos['vIGVDCODIGO']   = $dados['vIOidLista'][$key];
		$documentos['vIUSUCODIGO']   = $oidUsuario;
		$documentos['vIEMPCODIGO']   = $_SESSION["SI_USU_EMPRESA"];

		if ($documentos['vIUXDCODIGO'] != 'false') {
			$id = insertUpdateUsuariosxDocumentosPendentes($documentos, 'N');
		}

		if ($documentos['vIUXDCODIGO'] == 'false') {
			unset($documentos['vIUXDCODIGO']);
			$id = insertUpdateUsuariosxDocumentosPendentes($documentos, 'N');
		}

		array_push($contador, $id);
	}

	$retorno = array();

	if (sizeof($contador) > 0) {
		$retorno['msg'] 	= "Registro(s) atualizado(s)! ";
		$retorno['status'] 	= "success";
	} else {
		$retorno['msg']		= "Erro ao atualizar registro(s)";
		$retorno['status'] 	= "error";
	}

	echo json_encode($retorno);
	return;
}

function insertUpdateUsuariosxDocumentosPendentes($_POSTDADOS, $pSMsg = 'N')
{
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXDOCUMENTOSPENDENTES',
		'prefixo' => 'UXD',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxDocumentosPendentes($codDocumento, $formatoRetorno = 'array')
{
	$SqlMain = "SELECT *
				FROM
					USUARIOSXDOCUMENTOSPENDENTES
				WHERE
					UXBCODIGO = {$codDocumento}";

	$vConexao  = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);

	if (!$registro = sql_retorno_lista($resultSet)) return false;

	if ( $formatoRetorno == 'array') {
		return $registro !== null ? $registro : "N";
	}
	if ( $formatoRetorno == 'json' ) {
		echo json_encode($registro);
	}

	return $registro !== null ? $registro : "N";
}

function listUsuariosxDocumentoPendente(){

	$sql = "SELECT
				u.USUNOME,
				v.VIETITULO AS VINCULO,
				x.GVDCODIGO,
				x.UXDENTREGUE,
				x.UXDPENDENTE,
				dp.DOPTITULO AS DOCUMENTO
			FROM USUARIOSXDOCUMENTOSPENDENTES x
			LEFT JOIN USUARIOS u ON u.USUCODIGO = x.USUCODIGO
			LEFT JOIN VINCULOEMPREGATICIO v ON v.VIECODIGO = u.USUVINCULO
			LEFT JOIN GRUPOSVINCULO gv ON gv.VIECODIGO = v.VIECODIGO
			LEFT JOIN GRUPOSVINCULOXDOCUMENTOSPENDENTES gd ON gd.GVDCODIGO = x.GVDCODIGO
			LEFT JOIN DOCUMENTOSPENDENTES dp ON dp.DOPCODIGO = gd.DOPCODIGO
			WHERE
				gd.GVDATIVO = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxDocumentoPendenteFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				u.USUNOME,
				v.TABDESCRICAO AS VINCULO,
				x.GVDCODIGO,
				x.UXDENTREGUE,
				x.UXDPENDENTE,
				dp.TABDESCRICAO AS DOCUMENTO
			FROM USUARIOSXDOCUMENTOSPENDENTES x
			LEFT JOIN USUARIOS u ON u.USUCODIGO = x.USUCODIGO
			LEFT JOIN TABELAS v ON v.TABCODIGO = u.USUVINCULO
			LEFT JOIN GRUPOSVINCULO gv ON gv.VIECODIGO = v.TABCODIGO
			LEFT JOIN GRUPOSVINCULOXDOCUMENTOSPENDENTES gd ON gd.GVDCODIGO = x.GVDCODIGO
			LEFT JOIN TABELAS dp ON dp.TABCODIGO = gd.DOPCODIGO
			WHERE
				gd.GVDATIVO = 'S'
			AND x.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxEscolaridade.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxEscolaridade";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxEscolaridade";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']  = array('', 'Colaborador','Grau de Escolaridade', 'Ano / Semestre', 'Instituição de Ensino', 'Curso', 'Data Início', 'Data Fim / Previsão', 'Ativo');
	$vAConfig['vACampos']   = array('UXECODIGO', 'USUNOME','ESCOLARIDADE', 'UXESEMESTRE', 'UXEINSTITUICAO', 'UXECURSO', 'UXEDATAINICIO', 'UXEDATAFIM', 'UXESTATUS');
	$vAConfig['vATipos']    = array('chave', 'varchar', 'varchar', 'varchar', 'varchar','varchar', 'date', 'date', 'simNao');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}