<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ProspeccaoxAgenda')
	listProspeccaoxAgenda($_POST['pIOID'], 'ProspeccaoxAgenda');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ProspeccaoxAgenda')
	fill_ProspeccaoxAgenda($_GET['AGECODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirProspeccaoxAgenda'){
	print_r($_POST);
	$_POSTCHG['vSAGEDATAINICIO'] = $_POST['vDCHGDATA'];
	$_POSTCHG['vSAGEHORAINICIO'] = $_POST['vSCHGHORA'];
	$_POSTCHG['vSAGEDATAFINAL'] = $_POST['vDCHGDATA'];
	$_POSTCHG['vSAGEHORAFINAL'] = $_POST['vSCHGHORA'];
	$_POSTCHG['vIAGERESPONSAVEL'] = $_POST['vICXPREPRESENTANTE'];
	$_POSTCHG['vSAGETITULO'] = 'CRM/Comercial';
	$_POSTCHG['vSAGEDESCRICAO'] = $_POST['vSCHGHISTORICO'];
	$_POSTCHG['vICLICODIGO'] = $_POST['vICLICODIGO'];
	$_POSTCHG['vIAGEVINCULO'] = $_POST['hdn_pai_codgo'];
	$_POSTCHG['vIMENCODIGO'] = 1962;
	$_POSTCHG['vIAGETIPO'] = $_POST['vICHGTIPO'];
	//$_POSTCHG['vICHGPOSICAO'] = $_POST['vICHGPOSICAO'];	
	$_POSTCHG['vIAGECODIGO'] = $_POST['hdn_filho_codgo'];
	echo insertUpdateProspeccaoxAgenda($_POSTCHG, 'N');
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("AGENDA", 'crud'),
		"prefixo" => "AGE",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateProspeccaoxAgenda($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'AGENDA',
		'prefixo' => 'AGE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ProspeccaoxAgenda($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    AGENDA
				WHERE
					AGESTATUS = 'S'
				AND
                    AGECODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listProspeccaoxAgenda($vIOIDPAI, $tituloModal){
	$sql = "SELECT
                FS.*, T.TABDESCRICAO AS TIPO, T2.TABDESCRICAO AS POSICAO, U.USUNOME
            FROM
                AGENDA FS
			LEFT JOIN TABELAS T ON T.TABCODIGO = FS.AGETIPO	
			LEFT JOIN TABELAS T2 ON T2.TABCODIGO = FS.AGESITUACAO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = FS.AGEUSU_INC			
			WHERE
				AGESTATUS = 'S'
            AND FS.CLICODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionProspeccaoxAgenda.php";
	$vAConfig['DIV_RETORNO'] = "div_historico";
	$vAConfig['FUNCAO_RETORNO'] = "ProspeccaoxAgenda";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Data Contato/Atividade', 'Hora',  'Tipo Contato/Atividade', 'Status', 'Usuário', 'Descrição');
	$vAConfig['vACampos'] 	= array('AGECODIGO', 'AGEDATAINICIO', 'AGEHORAINICIO', 'TIPO', 'POSICAO', 'USUNOME', 'AGEDESCRICAO');
	$vAConfig['vATipos'] 	= array('chave', 'date', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}