<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$clientes = consultaComposta([
	'query' => 'SELECT
                    CONCAT(IDSIGLA,\' | \',CLINOME) AS value,
                    CLICODIGO AS id
				FROM 
					CLIENTES 
				WHERE 
					CLISTATUS = \'S\' AND					
					(CLINOME LIKE ? OR
					IDSIGLA LIKE ?)
				ORDER BY 
					CLINOME',
	'parametros' => [
		["%{$termo}%", PDO::PARAM_STR],
		["%{$termo}%", PDO::PARAM_STR],
	],
]);

echo json_encode($clientes['dados']);