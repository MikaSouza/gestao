<?php
function comboEstados($estcodigo)
{
	$sql = "SELECT
                    ESTCODIGO, ESTSIGLA, ESTDESCRICAO
                FROM
                    ESTADOS
                ORDER BY
                    ESTDESCRICAO ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}