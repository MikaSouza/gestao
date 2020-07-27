<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'AtendimentoxHistoricos')
	listAtendimentoxHistoricosFilhos($_POST['pIOID'], 'AtendimentoxHistoricos');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_AtendimentoxHistoricos')
	fill_AtendimentoxHistoricos($_GET['vIAXHCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("ATENDIMENTOXHISTORICOS", 'crud'),
		"prefixo" => "AXH",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirAtendimentoxHistoricos'){
	$remuneracao['vIAXHCODIGO'] 		 	  	 = $_POST['hdn_filho_codgo'];
	$remuneracao['vIUSUCODIGO'] 		 	  	 = $_POST['hdn_pai_codgo'];	
	$remuneracao['vDAXHDATAALTERACAOSALARIAL']   = $_POST['vDAXHDATAALTERACAOSALARIAL'];
	$remuneracao['vMAXHSALARIOATUAL'] 	 		 = $_POST['vMAXHSALARIOATUAL'];
	$remuneracao['vSAXHMOTIVOALTERACAOSALARIAL'] = $_POST['vSAXHMOTIVOALTERACAOSALARIAL'];
	$remuneracao['vIEMPCODIGO']                  = 1;

	$vIOID = insertUpdateAtendimentoxHistoricos($remuneracao, 'N');
	echo $vIOID;
}

function insertUpdateAtendimentoxHistoricos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ATENDIMENTOXHISTORICOS',
		'prefixo' => 'AXH',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_AtendimentoxHistoricos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					ATENDIMENTOXHISTORICOS
				WHERE
					AXHCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	$registro['AXHSALARIOATUAL']            = formatar_moeda($registro['AXHSALARIOATUAL'], false);
	$registro['AXHMOTIVOALTERACAOSALARIAL'] = $registro['AXHMOTIVOALTERACAOSALARIAL'];


	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listAtendimentoxHistoricos(){

	$sql = "SELECT
				r.AXHSALARIOATUAL AS SALARIOATUAL,
				r.AXHDATAALTERACAOSALARIAL,
				r.AXHMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				ATENDIMENTOXHISTORICOS r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.AXHSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listAtendimentoxHistoricosFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				r.*,
				( SELECT u1.USUNOME FROM USUARIOS u1 WHERE u1.USUCODIGO = r.AXHATENDENTENOVO ) AS ATENDENTE_NOVO,
				( SELECT p.POPNOME FROM POSICOESPADROES p WHERE p.POPCODIGO = r.POPCODIGO ) AS POSICAO_NOME
			FROM
				ATENDIMENTOXHISTORICOS r
			WHERE r.AXHSTATUS = 'S' AND
			r.ATECODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionAtendimentoxHistoricos.php";
	$vAConfig['DIV_RETORNO'] = "div_historico";
	$vAConfig['FUNCAO_RETORNO'] = "AtendimentoxHistoricos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Inclusão', 'Atendente', 'Posição', 'Descrição');
	$vAConfig['vACampos']      = array('AXHCODIGO', 'AXHUSU_INC', 'ATENDENTE_NOVO', 'POSICAO_NOME', 'AXHDESCRICAO');
	$vAConfig['vATipos']       = array('chave', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php'; 
	return;
}
?>