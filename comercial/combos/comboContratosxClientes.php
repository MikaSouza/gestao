<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

$termo = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);

$clientes = consultaComposta([
	'query' => "SELECT
					C.CTRNROCONTRATO,
					C.CTRCODIGO AS id,
                    L.CLINOMEFANTASIA AS value,
                    L.CLICODIGO
				FROM 
					CONTRATOS C
				INNER JOIN CLIENTES L ON L.CLICODIGO = C.CLICODIGO
				WHERE 
					C.CTRSTATUS = 'S' AND	
					L.CLISTATUS = 'S' AND					
					(L.CLINOMEFANTASIA LIKE ? OR
					L.CLIRAZAOSOCIAL LIKE ? OR
					C.CTRNROCONTRATO LIKE ?)
				ORDER BY 
					L.CLINOMEFANTASIA",
	'parametros' => [
		["%{$termo}%", PDO::PARAM_STR],
		["%{$termo}%", PDO::PARAM_STR],
		["%{$termo}%", PDO::PARAM_STR],
	],
]);

$return_arr = array();
foreach ($clientes['dados'] as $clientes) :
	$row_array['id']    = $clientes['id'];
	$row_array['cliente']    = $clientes['CLICODIGO'];
	$row_array['value'] = $clientes['CTRNROCONTRATO'].' - '. $clientes['value'];
	array_push($return_arr, $row_array);
endforeach;
echo json_encode($return_arr);