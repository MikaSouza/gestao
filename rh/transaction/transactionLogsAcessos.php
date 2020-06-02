<?php

function listLogsAcessos(){
	$cod_usuario = filter_var($_SESSION['SI_USUCODIGO'], FILTER_SANITIZE_NUMBER_INT);
	$sql = "SELECT
				l.LUADATA_INC, l.LUAIP, u.USUNOME
			FROM
				LOGUSUARIOACESSO l
			LEFT JOIN USUARIOS u ON u.USUCODIGO = l.LUAUSU_INC	
			WHERE
				l.LUASTATUS = 'S' 
			AND l.LUAUSU_INC = ? 	
			ORDER BY l.LUADATA_INC DESC	";
	$result = consultaComposta([
		'query' => $sql,
		'parametros' => [
			[$cod_usuario, PDO::PARAM_INT],
		],
	]);

	return $result;

}

function insertUpdateLogsAcessos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'LOGUSUARIOACESSO',
		'prefixo' => 'LUA',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id; 
}
