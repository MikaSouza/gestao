<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$clientes = consultaComposta([
	'query' => "SELECT
                    CLINOMEFANTASIA AS value,
                    CLICODIGO AS id
				FROM 
					CLIENTES 
				WHERE 
					CLISTATUS = 'S' AND					
					(CLINOMEFANTASIA LIKE ? OR
					CLIRAZAOSOCIAL LIKE ?)
				ORDER BY 
					CLINOMEFANTASIA",
	'parametros' => [
		["%{$termo}%", PDO::PARAM_STR],
		["%{$termo}%", PDO::PARAM_STR],
	],
]);

echo json_encode($clientes['dados']);