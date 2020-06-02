<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$qrycli =   "SELECT 
				CLACODIGO AS id,
				CONCAT(CLACODCLASSE, ' - ', CLADESCRICAO) AS value
			FROM
				CLASSES
			WHERE
				CLASTATUS = 'S'
			AND
				(CLACODCLASSE LIKE ? OR
				CLADESCRICAO LIKE ?)
			ORDER BY
				CLACODCLASSE";

$resultados = consultaComposta(
	array(
		'query' => $qrycli,
		'parametros' => array(
			array('%'.$termo.'%', PDO::PARAM_STR),
			array('%'.$termo.'%', PDO::PARAM_STR),
		)
	)
);

echo json_encode($resultados['dados']);
	