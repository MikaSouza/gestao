<?php
function comboBancos()
{
   $sql = "SELECT
                BACCODIGO, BACBANCO, BACCODIGOBACEN
            FROM
                BANCOS
            WHERE
                BACSTATUS = 'S'               
            ORDER BY
                BACBANCO ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}