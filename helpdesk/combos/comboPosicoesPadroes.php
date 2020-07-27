<?php
function comboPosicoesPadroes()
{
	$sql = "SELECT
				pp.POPCODIGO,
				pp.POPNOME
			FROM
				POSICOESPADROES pp
			WHERE				
				pp.POPSTATUS = 'S' 
			ORDER BY
				pp.POPNOME ";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];	
}	