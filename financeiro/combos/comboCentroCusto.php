<?php
function comboCentroCusto()
{
   $sql = "SELECT
                CDCCODIGO, CDCTITULO
            FROM
                CENTRODECUSTO
            WHERE
                CDCSTATUS = 'S'               
            ORDER BY
                CDCTITULO ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}