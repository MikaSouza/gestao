<?php
function comboUsuarios($vIDepartamento = null, $vIUSUCODIGO = null)
{
	if(verificarVazio($vIDepartamento))
		$where .= 'AND TABDEPARTAMENTO = ? ';
	
	if(verificarVazio($vIUSUCODIGO))
		$where .= 'AND USUCODIGO = ? ';
	
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
    if(verificarVazio($vIDepartamento))
		$arrayQuery['parametros'][] = array($vIDepartamento, PDO::PARAM_INT);
	if(verificarVazio($vIUSUCODIGO))
		$arrayQuery['parametros'][] = array($vIUSUCODIGO, PDO::PARAM_INT);
	
   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}

function comboUsuariosAgenda($vIDepartamento = null, $vIUSUCODIGO = null)
{
	if(verificarVazio($vIDepartamento))
		$where .= 'AND TABDEPARTAMENTO = ? ';
	
	if(verificarVazio($vIUSUCODIGO)) {
		$vLUSUCODIGO = implode(",", $vIUSUCODIGO);
		$where .= 'AND USUCODIGO in ('.$vLUSUCODIGO.') ';
	}
    $sql = "SELECT
                    USUCODIGO, USUNOME
                FROM
                    USUARIOS
                WHERE
                    USUSTATUS = 'S'  
				AND USUAGENDA = 'S' 
				AND USUDATADEMISSAO IS NULL	
				".	$where	."	
                ORDER BY
                    USUNOME ASC";					
    $arrayQuery = array(
       'query' => $sql,
       'parametros' => array()
    );
    if(verificarVazio($vIDepartamento))
		$arrayQuery['parametros'][] = array($vIDepartamento, PDO::PARAM_INT);

   $list = consultaComposta($arrayQuery);
   return $list['dados'];
}