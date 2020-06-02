<?php
function comboTabelas($vSTABTIPO)
{
   $sql = "SELECT
                TABCODIGO, TABDESCRICAO
            FROM
                TABELAS
            WHERE
                TABSTATUS = 'S'
            AND
                TABTIPO = ?    
            ORDER BY
                TABDESCRICAO ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array(
           array($vSTABTIPO, PDO::PARAM_STR)
           )
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}