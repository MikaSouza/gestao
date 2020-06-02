<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateValesTransporte($_POST, 'S'); 	
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_ValesTransporte($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ValesTransporte')
	fill_ValesTransporte($_GET['vIVXTCODIGO'], $_GET['formatoRetorno']);

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

function insertUpdateValesTransporte($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'VALESTRANSPORTE',
		'prefixo' => 'VXT',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ValesTransporte($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                r.*,
	                f.FAVNOMEFANTASIA
	            FROM
                    VALESTRANSPORTE r
				LEFT JOIN FAVORECIDOS f ON f.FAVCODIGO = r.FAVCODIGO	
				WHERE
                    VXTCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' ) {
		$registro['VXTVALORUNITARIO'] = formatar_moeda($registro['VXTVALORUNITARIO'],'');
		echo json_encode($registro);
	}	
}

function listValesTransporte(){

	$sql = "SELECT
	                r.*,
	                f.FAVNOMEFANTASIA
	            FROM
	                VALESTRANSPORTE r
				LEFT JOIN FAVORECIDOS f ON f.FAVCODIGO = r.FAVCODIGO
				WHERE
					r.VXTSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}
