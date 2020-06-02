<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ProcessoxFases')
	listProcessoxFasesFilhos($_POST['pIOID'], 'ProcessoxFases');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ProcessoxFases')
	fill_ProcessoxFases($_GET['vIPXFCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirProcessoxFases'){
	$_POSTCHG['vIPXFCODIGO'] = $_POST['hdn_filho_codigo'];
	$_POSTCHG['vIPRCCODIGO'] = $_POST['hdn_pai_codigo'];
	$_POSTCHG['vSPXFDATA'] = $_POST['vSPXFDATA'];
	$_POSTCHG['vIPXFRESPONSAVEL'] = $_POST['vIPXFRESPONSAVEL'];
	$_POSTCHG['vSPXFTIPOFASE'] = $_POST['vSPXFTIPOFASE'];
	$_POSTCHG['vSPXFPRAZO'] = $_POST['vSPXFPRAZO'];
	$_POSTCHG['vSPXFALARME'] = $_POST['vSPXFALARME'];
	$_POSTCHG['vSPXFVISIVEL'] = $_POST['vSPXFVISIVEL'];
	$_POSTCHG['vSPXFPENDENTE'] = $_POST['vSPXFPENDENTE'];
	$_POSTCHG['vSPXFDESCRICAO'] = $_POST['vSPXFDESCRICAO'];
	$_POSTCHG['vSPXFDATAALARME'] = $_POST['vSPXFDATAALARME'];
	$_POSTCHG['vIEMPCODIGO']  = 1;//$_SESSION["SI_USU_EMPRESA"];
	echo insertUpdateProcessoxFases($_POSTCHG, 'N');
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("PROCESSOXFASE", 'crud'),
		"prefixo" => "PXF",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateProcessoxFases($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'PROCESSOXFASE',
		'prefixo' => 'PXF',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function listProcessoxFases(){

	$sql = "SELECT
				f.PXFCODIGO, u.USUNOME, f.PXFDATA, f.PXFTIPOFASE, 
				f.PXFPRAZO, f.PXFALARME, f.PXFVISIVEL, f.PXFPENDENTE, 
				f.PXFDESCRICAO, t.TABDESCRICAO, p.PRCNROPROCESSO
			FROM
				PROCESSOXFASE f 
			LEFT JOIN PROCESSOS p ON p.PRCCODIGO = f.PRCCODIGO		
			LEFT JOIN USUARIOS u ON u.USUCODIGO = f.PXFRESPONSAVEL	
			LEFT JOIN TABELAS t ON t.TABCODIGO = f.PXFTIPOFASE
			WHERE
				f.PXFSTATUS = 'S' 
			LIMIT 500	"; 

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function fill_ProcessoxFases($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                u.*
	            FROM
	                PROCESSOXFASE u	           
				WHERE
					u.PXFSTATUS = 'S'
				AND
					u.PXFCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listProcessoxFasesFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				f.PXFCODIGO, u.USUNOME, f.PXFDATA, f.PXFTIPOFASE, 
				f.PXFPRAZO, f.PXFALARME, f.PXFVISIVEL, f.PXFPENDENTE, 
				f.PXFDESCRICAO, t.TABDESCRICAO
			FROM
				PROCESSOXFASE f 
			LEFT JOIN USUARIOS u ON u.USUCODIGO = f.PXFRESPONSAVEL	
			LEFT JOIN TABELAS t ON t.TABCODIGO = f.PXFTIPOFASE
			WHERE
				f.PRCCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);	
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionProcessoxFases.php";
	$vAConfig['DIV_RETORNO'] = "div_ProcessoxFases";
	$vAConfig['FUNCAO_RETORNO'] = "ProcessoxFases";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Responsável', 'Data', 'Tipo de Fase', 'Prazo', 'Alarme', 'Visível', 'Pendente', 'Descrição');
	$vAConfig['vACampos'] 	= array('PXFCODIGO', 'USUNOME', 'PXFDATA', 'TABDESCRICAO', 'PXFPRAZO', 'PXFALARME', 'PXFVISIVEL', 'PXFPENDENTE', 'PXFDESCRICAO');
	$vAConfig['vATipos'] 	= array('chave', 'varchar', 'date', 'varchar', 'simNao', 'simNao', 'simNao', 'simNao', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return;
}