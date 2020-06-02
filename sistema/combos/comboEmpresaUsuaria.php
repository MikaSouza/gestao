<?php
function comboEmpresaUsuaria($vSEMPOMITIR)
{
   $sql = "SELECT
                EMPCODIGO, EMPNOMEFANTASIA
            FROM
                EMPRESAUSUARIA
            WHERE
                EMPSTATUS = 'S' ";
	if ($vSEMPOMITIR != '')			
		$sql .= " AND EMPOMITIR = '".$vSEMPOMITIR."' ";
   $sql .= "  ORDER BY
                EMPNOMEFANTASIA ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}