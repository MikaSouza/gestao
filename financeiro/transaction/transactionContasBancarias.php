<?php

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCBA') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("CONTASBANCARIAS", 'crud'),
        "prefixo"  => "CBA",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listContasBancarias(){

	$sql = "SELECT
				c.*,
				e.EMPNOMEFANTASIA,
				l.TABDESCRICAO,
				CONCAT(c.CBAAGENCIA, ' - ', IFNULL(c.CBACONTA, '')) AS CONTA
			FROM
				CONTASBANCARIAS c
			LEFT JOIN TABELAS l on l.TABCODIGO = c.CBATIPO	
			LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = c.EMPCODIGO
			WHERE
				c.CBASTATUS = 'S'
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateContasBancarias($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ContasBancarias',
		'prefixo' => 'CBA',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadContasBancarias.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id; 
}

function fill_ContasBancarias($pOid){
	$SqlMain = 'Select *
				 From CONTASBANCARIAS
				 Where CBACODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

