<?php
function comboGrupoAcessos()
{
    $sql = "SELECT
                g.GRACODIGO, t.TABDESCRICAO AS GRUPO
			FROM
				GRUPOSACESSO g 
			LEFT JOIN TABELAS t ON t.TABCODIGO = g.GRAPERFIL	
			WHERE
				g.GRASTATUS = 'S' 
			ORDER BY
				GRUPO";			
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
   );
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}