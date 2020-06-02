<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
$termo  = ($_GET['term']);
//$termo = $_GET['term'];//filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
//echo '--'.strlen($termo).$termo;
if (strlen($termo) > 2){
	$clientes = consultaComposta([
		'query' => 'SELECT
						CONCAT(IDSIGLA,\' | \',CLINOME) AS text,
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
	foreach($clientes['dados'] as $clientes):
		$data[] = array("id"=>$clientes['id'], "text"=>$clientes['text']);
	endforeach;
	echo json_encode($data);
}	