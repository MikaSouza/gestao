<?php
function comboPeriodicidades()
{
	$sql = "SELECT 
				PERCODIGO,
				PERNOME,
				PERPERIODOBASE,
				PERPERIODOADICIONAL
			FROM
				PERIODICIDADES
			WHERE PERSTATUS = 'S'";
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}