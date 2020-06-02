<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$clientes = consultaComposta([
	'query' => 'SELECT
                    FAVNOMEFANTASIA AS value,
                    FAVCODIGO AS id
				FROM 
					FAVORECIDOS 
				WHERE 
					FAVSTATUS = \'S\' AND					
					(FAVRAZAOSOCIAL LIKE ? OR
					FAVNOMEFANTASIA LIKE ?)
				ORDER BY 
					FAVNOMEFANTASIA',
	'parametros' => [
		["%{$termo}%", PDO::PARAM_STR],
		["%{$termo}%", PDO::PARAM_STR],
	],
]);

echo json_encode($clientes['dados']);