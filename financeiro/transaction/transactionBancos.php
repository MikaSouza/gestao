<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST["method"]) && $_POST["method"] == 'excluirBAC') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("BANCOS", 'crud'),
        "prefixo"  => "BAC",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listBancos(){

	$sql = "SELECT
				*
			FROM
				BANCOS
			WHERE
				BACSTATUS = 'S'
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}
function comboBancos(){
	$baseSQL = "SELECT
					*
				FROM
					BANCOS
				WHERE
					BACSTATUS = 'S'	";

	$baseSQL .= " ORDER BY
					BACBANCO ASC";

	$query = array();
	$query['query']     = $baseSQL;


	$data     = consultaComposta($query);
	$response = $data['dados'];
	return $response;
}