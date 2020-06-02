<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
if(isset($_POST["method"]) && $_POST["method"] == 'excluirUSU') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOS", 'crud'),
		"prefixo" => "USU",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listUsuario(){

	$sql = "SELECT
				*
			FROM
				USUARIOS
			WHERE
				USUSTATUS = 'S'
			ORDER BY 1 ";	
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateUsuario($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOS',
		'prefixo' => 'USU',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadUsuario.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Usuario($pOid){
	$SqlMain = 'Select *
				 From USUARIOS
				 Where USUCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}