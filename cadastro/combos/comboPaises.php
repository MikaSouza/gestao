<?php
function comboPaises()
{
   $sql = "SELECT
                PAICODIGO, PAIDESCRICAO
            FROM
                PAISES
            WHERE
                PAISTATUS = 'S'               
            ORDER BY
                PAIDESCRICAO";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}