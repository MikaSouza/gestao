<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
$termo  = ($_GET['term']);
$cod_usuario = filter_var($_SESSION['SI_USUCODIGO'], FILTER_SANITIZE_NUMBER_INT);
//$termo = $_GET['term'];//filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
//echo '--'.strlen($termo).$termo;
if (strlen($termo) > 2){
	$sql = "SELECT
						CONNOME AS text,
						CONCODIGO AS id,
						P.PESNOMEFANTASIA,
						CONEMAIL, CONFONE
					FROM 
						PESSOASXCONTATOS C
					LEFT JOIN PESSOAS P ON P.PESCODIGO = C.PESCODIGO	
					WHERE 
						CONSTATUS = 'S' AND			
						P.PESSTATUS = 'S' AND	
						(CONNOME LIKE ?)
					ORDER BY 
						CONNOME";				
	$arrayQuery = array(
				'query' => $sql,
				'parametros' => array()
			);
	$arrayQuery['parametros'][] = array("%$termo%", PDO::PARAM_STR);

	$clientes = consultaComposta($arrayQuery);					

	$return_arr = array();
	foreach ($clientes['dados'] as $clientes) :
		$row_array['id']    = $clientes['id'];
		$row_array['value'] = $clientes['PESNOMEFANTASIA'].' - '.$clientes['text'].' '. ($clientes['CONEMAIL'] == '' ? '' :  $clientes['CONEMAIL']).' '. ($clientes['CONFONE'] == '' ? '' :  $clientes['CONFONE']);
		array_push($return_arr, $row_array);
	endforeach;
	echo json_encode($return_arr);
	
}	