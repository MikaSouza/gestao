<?php
function comboValesTransporte()
{
	$sql = "SELECT
                VXTCODIGO, VXTNOME, VXTITINERARIO
            FROM
                VALESTRANSPORTE
            WHERE
				VXTSTATUS = 'S'
            ORDER BY
                VXTNOME ASC";
	$arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
	);
	$list = consultaComposta($arrayQuery);
    return $list['dados'];
}