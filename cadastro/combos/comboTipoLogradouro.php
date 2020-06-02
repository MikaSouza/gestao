<?php
function comboTipoLogradouro($estcodigo)
{
	$sql = "SELECT
                    TLOCODIGO, TLOSIGLA, TLODESCRICAO
                FROM
                    TIPOLOGRADOURO
                ORDER BY
                    TLODESCRICAO ASC";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}