<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'AtendimentoxSubAtendimentos')
	listAtendimentoxSubAtendimentosFilhos($_POST['pIOID'], 'AtendimentoxSubAtendimentos');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_AtendimentoxSubAtendimentos')
	fill_AtendimentoxSubAtendimentos($_GET['vIAXSCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("ATENDIMENTOSXSUBATENDIMENTOS", 'crud'),
		"prefixo" => "AXS",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirAtendimentoxSubAtendimentos'){
	$remuneracao['vIAXSCODIGO'] 		 	  	 = $_POST['hdn_filho_codgo'];
	$remuneracao['vIUSUCODIGO'] 		 	  	 = $_POST['hdn_pai_codgo'];	
	$remuneracao['vDAXSDATAALTERACAOSALARIAL']   = $_POST['vDAXSDATAALTERACAOSALARIAL'];
	$remuneracao['vMAXSSALARIOATUAL'] 	 		 = $_POST['vMAXSSALARIOATUAL'];
	$remuneracao['vSAXSMOTIVOALTERACAOSALARIAL'] = $_POST['vSAXSMOTIVOALTERACAOSALARIAL'];
	$remuneracao['vIEMPCODIGO']                  = 1;

	$vIOID = insertUpdateAtendimentoxSubAtendimentos($remuneracao, 'N');
	echo $vIOID;
}

function insertUpdateAtendimentoxSubAtendimentos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ATENDIMENTOSXSUBATENDIMENTOS',
		'prefixo' => 'AXS',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_AtendimentoxSubAtendimentos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					ATENDIMENTOSXSUBATENDIMENTOS
				WHERE
					AXSCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	$registro['AXSSALARIOATUAL']            = formatar_moeda($registro['AXSSALARIOATUAL'], false);
	$registro['AXSMOTIVOALTERACAOSALARIAL'] = $registro['AXSMOTIVOALTERACAOSALARIAL'];

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listAtendimentoxSubAtendimentos(){

	$sql = "SELECT
				r.AXSSALARIOATUAL AS SALARIOATUAL,
				r.AXSDATAALTERACAOSALARIAL,
				r.AXSMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				ATENDIMENTOSXSUBATENDIMENTOS r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.AXSSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listAtendimentoxSubAtendimentosFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				r.*
			FROM
				ATENDIMENTOSXSUBATENDIMENTOS r
			WHERE r.AXSSTATUS = 'S' AND
			r.ATECODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionAtendimentoxSubAtendimentos.php";
	$vAConfig['DIV_RETORNO'] = "div_subAtendimentos";
	$vAConfig['FUNCAO_RETORNO'] = "AtendimentoxSubAtendimentos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Prioridade', 'Inclusão', 'Assunto', 'Descrição', 'Atendente', 'Previsão', 'Conclusão');
	$vAConfig['vACampos']      = array('AXSCODIGO', 'AXSPRIORIDADE', 'AXSDATA_INC', 'AXSASSUNTO', 'AXSDESCRICAO', 'AXSATENDENTE', 'AXSPREVISAOCONCLUSAO', 'AXSDATACONCLUSAO');
	$vAConfig['vATipos']       = array('chave', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php'; 
	return;
}
?>