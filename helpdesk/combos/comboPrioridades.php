<?php
function comboPrioridades()
{
	$sql = "SELECT
				ATPCODIGO,
				ATPNUMERO,
				ATPDESCRICAO
			FROM
				ATENDIMENTOSPRIORIDADES
			WHERE				
				ATPSTATUS = 'S' 
			ORDER BY
				ATPNUMERO ";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];	
}	