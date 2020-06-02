<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxRemuneracao')
	listUsuariosxRemuneracaoFilhos($_POST['pIOID'], 'UsuariosxRemuneracao');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxRemuneracao')
	fill_UsuariosxRemuneracao($_GET['vIUXRCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("USUARIOSXREMUNERACAO", 'crud'),
		"prefixo" => "UXR",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxRemuneracao'){
	$remuneracao['vIUXRCODIGO'] 		 	  	 = $_POST['hdn_filho_codgo'];
	$remuneracao['vIUSUCODIGO'] 		 	  	 = $_POST['hdn_pai_codgo'];	
	$remuneracao['vDUXRDATAALTERACAOSALARIAL']   = $_POST['vDUXRDATAALTERACAOSALARIAL'];
	$remuneracao['vMUXRSALARIOATUAL'] 	 		 = $_POST['vMUXRSALARIOATUAL'];
	$remuneracao['vSUXRMOTIVOALTERACAOSALARIAL'] = $_POST['vSUXRMOTIVOALTERACAOSALARIAL'];
	$remuneracao['vIEMPCODIGO']                  = 1;

	$vIOID = insertUpdateUsuariosxRemuneracao($remuneracao, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxRemuneracao($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXREMUNERACAO',
		'prefixo' => 'UXR',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxRemuneracao($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					USUARIOSXREMUNERACAO
				WHERE
					UXRCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	$registro['UXRSALARIOATUAL']            = formatar_moeda($registro['UXRSALARIOATUAL'], false);
	$registro['UXRMOTIVOALTERACAOSALARIAL'] = $registro['UXRMOTIVOALTERACAOSALARIAL'];


	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listUsuariosxRemuneracao(){

	$sql = "SELECT
				r.UXRSALARIOATUAL AS SALARIOATUAL,
				r.UXRDATAALTERACAOSALARIAL,
				r.UXRMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				USUARIOSXREMUNERACAO r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.UXRSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxRemuneracaoFilhos($vIOIDPAI, $tituloModal){
	$sql = "SELECT
				r.UXRCODIGO,
				r.UXRSALARIOATUAL AS SALARIOATUAL,
				r.UXRDATAALTERACAOSALARIAL,
				r.UXRMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				USUARIOSXREMUNERACAO r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.UXRSTATUS = 'S' AND
			r.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxRemuneracao.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxRemuneracao";
	$vAConfig['FUNCAO_RETORNO'] = "UsuariosxRemuneracao";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Salário Atual', 'Data da Alteração', 'Motivo da Alteração');
	$vAConfig['vACampos']      = array('UXRCODIGO', 'SALARIOATUAL', 'UXRDATAALTERACAOSALARIAL', 'UXRMOTIVOALTERACAOSALARIAL');
	$vAConfig['vATipos']       = array('chave', 'monetario', 'date','varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php'; 
	return;
}
?>