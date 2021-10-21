<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
$termo  = ($_GET['term']);
$cod_cliente = filter_var($_SESSION['SI_USUCODIGO'], FILTER_SANITIZE_NUMBER_INT);
//$termo = $_GET['term'];//filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
//echo '--'.strlen($termo).$termo;
if (strlen($termo) > 2){
	$sql = "SELECT
						CONNOME AS text,
						CONCODIGO AS id,
						CONEMAIL, CONFONE
					FROM 
						CONTATOS C
					WHERE 
						CONSTATUS = 'S' AND
						CLICODIGO = ? AND
						(CONNOME LIKE ?)
					ORDER BY 
						CONNOME";				
	$arrayQuery = array(
				'query' => $sql,
				'parametros' => array()
			);
	$arrayQuery['parametros'][] = array($cod_cliente, PDO::PARAM_INT);		
	$arrayQuery['parametros'][] = array("%$termo%", PDO::PARAM_STR);

	$clientes = consultaComposta($arrayQuery);					

	$return_arr = array();
	foreach ($clientes['dados'] as $clientes) :
		$row_array['id']    = $clientes['id'];
		$row_array['value'] = $clientes['text'].' '. ($clientes['CONEMAIL'] == '' ? '' :  $clientes['CONEMAIL']).' '. ($clientes['CONFONE'] == '' ? '' :  $clientes['CONFONE']);
		array_push($return_arr, $row_array);
	endforeach;
	echo json_encode($return_arr);
	
}	