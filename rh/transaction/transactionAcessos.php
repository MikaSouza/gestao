<?php

if(isset($_POST["method"]) && $_POST["method"] == 'excluirACE') {
	$config_excluir = array(
		"tabela" => Encriptar("ACESSOS", 'crud'),
		"prefixo" => "ACE",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listAcessos(){

	$sql = "SELECT
				*
			FROM 
				ACESSOS
			WHERE				
				ACESTATUS = 'S'
			ORDER BY 1 ";
	
	$arrayQuery = array(					
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateAcessos($_POSTDADOS, $pSMsg = 'N'){	
	$dadosBanco = array(
		'tabela'  => 'ACESSOS',
		'prefixo' => 'ACE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadAcessos.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id; 
}

function fill_Acessos($pOid){
	$SqlMain = 'Select *
				 From ACESSOS
				 Where ACECODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}