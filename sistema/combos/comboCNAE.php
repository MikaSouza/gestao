<?php
function comboCNAE()
{
   $sql = "SELECT
                CNACODIGO, CNACNAE, CNADESCRICAO
            FROM
                CNAE
            WHERE
                CNASTATUS = 'S'           
            ORDER BY
                CNACNAE ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}