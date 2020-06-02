<?php
function comboAtividades($vSTABTIPO)
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
       'parametros' => array(
           array($vSTABTIPO, PDO::PARAM_STR)
           )
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}