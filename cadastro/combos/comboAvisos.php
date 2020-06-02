<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

function comboAvisos()
{
	$sql = "SELECT
			AVICODIGO,
			AVITITULO,
			AVIDATAINICIAL
		FROM
			AVISOS
		WHERE
			AVISTATUS = 'S'
		ORDER BY
			AVIDATAINICIAL DESC ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	return $result;
}