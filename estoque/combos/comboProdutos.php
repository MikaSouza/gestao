<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$produtos = consultaComposta([
	'query' => 'SELECT
                    p.PRONOME AS value,
                    p.PROCODIGO AS id,
	                t.TABDESCRICAO AS unidade
				FROM PRODUTOS p
				LEFT JOIN TABELAS t ON t.TABCODIGO = p.PROUNIDADE
				WHERE 
					p.PROSTATUS = \'S\' AND					
					p.PRONOME LIKE ?
				ORDER BY 
					p.PRONOME',
	'parametros' => [
		["%{$termo}%", PDO::PARAM_STR],
	],
]);

echo json_encode($produtos['dados']);