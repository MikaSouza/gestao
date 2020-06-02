<?php
function comboUsuarios($vIDepartamento = null)
{
	if(verificarVazio($vIDepartamento))
		$where .= 'AND TABDEPARTAMENTO = ? ';
	
    $sql = "SELECT
                    USUCODIGO, USUNOME
                FROM
                    USUARIOS
                WHERE
                    USUSTATUS = 'S' 
				AND USUDATADEMISSAO IS NULL	
				".	$where	."	
                ORDER BY
                    USUNOME ASC";			
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   if(verificarVazio($vIDepartamento)){		
		$arrayQuery['parametros'][] = array($vIDepartamento, PDO::PARAM_INT);
	}
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}