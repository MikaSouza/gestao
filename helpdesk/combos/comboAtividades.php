<?php
function comboAtividades()
{
   $sql = "SELECT
                ATICODIGO, ATINOME
            FROM
                ATIVIDADES
            WHERE
                ATISTATUS = 'S'
            ORDER BY
                ATINOME ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}