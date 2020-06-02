<?php

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPER') {
	$config_excluir = array(
		"tabela" => Encriptar("PERIODICIDADES", 'crud'),
		"prefixo" => "PER",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "S",
	);  
	echo excluirAtivarRegistros($config_excluir);
}

function listPeriodicidades(){

	$sql = "SELECT
				*
			FROM
				PERIODICIDADES
			WHERE				
				PERSTATUS = 'S'
			ORDER BY 1 ";
	
	$arrayQuery = array(					
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;
}
