<?php
function comboDocumentosPadroes()
{
	$sql = "SELECT
                    DOPCODIGO, DOPNOME
                FROM
                    DOCUMENTOSPADROES
                ORDER BY
                    DOPNOME ";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];	
}	