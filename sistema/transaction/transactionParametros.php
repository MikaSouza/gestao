<?php

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPAR') {
	$config_excluir = array(
		"tabela" => Encriptar("PARAMETROS", 'crud'),
		"prefixo" => "PAR",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listParametros(){

	$sql = "SELECT
				*
			FROM 
				PARAMETROS
			WHERE				
				PARSTATUS = 'S'
			ORDER BY 1 ";
	
	$arrayQuery = array(					
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}