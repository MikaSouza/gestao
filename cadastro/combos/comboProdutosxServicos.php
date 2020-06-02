<?php
function comboProdutosxServicos()
{
	$sql = "SELECT
                    PXSCODIGO, PXSNOME
                FROM
                    PRODUTOSXSERVICOS
                ORDER BY
                    PXSNOME ";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];	
}	