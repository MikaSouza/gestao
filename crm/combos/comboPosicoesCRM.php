<?php
function comboPosicoesCRM()
{
	$sql = "SELECT
                    PXCCODIGO, PXCNOME
                FROM
                    POSICOESCRM
                ORDER BY
                    PXCNOME ";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];	
}	