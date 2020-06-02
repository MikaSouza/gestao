<?php
function listRamais($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND u.USUSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND u.USUSTATUS = 'N' ";
	}else
		$where .= "AND u.USUSTATUS = 'S' ";

	$sql = "SELECT u.USUNOME, u.USUEMAIL, u.USURAMAL, t.TABDESCRICAO AS SETOR
			FROM USUARIOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			WHERE 
				u.USUDATADEMISSAO IS NULL
			AND (u.USUNOME IS NOT NULL AND u.USUNOME <> '')
			".	$where	."
			ORDER BY 1 ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);			

	return $result;

}