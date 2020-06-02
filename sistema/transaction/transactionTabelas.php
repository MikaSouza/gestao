<?php

if(isset($_POST["method"]) && $_POST["method"] == 'excluirTAB') {
    $config_excluir = array(
        "tabela" => Encriptar("TABELAS", 'crud'),
        "prefixo" => "TAB",
        "status" => "N",
        "ids" => $_POST['pSCodigos'],
        "mensagem" => "S"
    );
    echo excluirAtivarRegistros($config_excluir);
}

function listTabelas(){

	$sql = "SELECT
				*
			FROM
				TABELAS
			WHERE
				TABSTATUS = 'S'
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function comboTabelas($tabtipo='', $showEmptyOption = true){
	$baseSQL = "SELECT
					TABCODIGO,
					TABDESCRICAO
				FROM
					TABELAS
				WHERE
					TABSTATUS = 'S'	";

	if (!is_null($tabtipo)) :
		$baseSQL .= "AND
						TABTIPO = ?";
	endif;

	$baseSQL .= " ORDER BY
					TABDESCRICAO ASC";

	$query = array();
	$query['query']      = $baseSQL;
	$query['parametros'] = array();

	if (!is_null($tabtipo)) :
		$query['parametros'] = array(
			array($tabtipo, PDO::PARAM_STR),
		);
	endif;

	$data     = consultaComposta($query);
	$response = $data['dados'];

	if ($showEmptyOption) {
		array_unshift($response, array('TABCODIGO' => '', 'TABDESCRICAO' => '----------'));
	}

	return $response;
}

function getTabela($pSTABDESCRICAO, $pSTABTIPO)
{
	$pSTABDESCRICAO = trim($pSTABDESCRICAO);

	$vITABCODIGO = 0;
    $SqlMain = "SELECT
    				TABCODIGO
	        	FROM
	        		TABELAS
	        	WHERE
	        		TABSTATUS = 'S' AND
	        		TABTIPO = '{$pSTABTIPO}' AND
	        		TABDESCRICAO = '{$pSTABDESCRICAO}'";
    $vConexao = sql_conectar_banco();
    $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $SqlMain);

    while ($reg_RS = sql_retorno_lista($RS_MAIN)){
        $vITABCODIGO = $reg_RS['TABCODIGO'];
    }

    if ($vITABCODIGO == 0) {  // incluir
    	$_POSTTAB['vITABCODIGO'] = '';
    	$_POSTTAB['vSTABTIPO'] = $pSTABTIPO;
    	$_POSTTAB['vSTABDESCRICAO'] = $pSTABDESCRICAO;
    	$vITABCODIGO = insertUpdateTabela($_POSTTAB);
    }

    $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $SqlMain);
    return $vITABCODIGO;
}

function getTabtipo()
{
	$data = consultaComposta(array(
		'query' => "SELECT
						DISTINCT(TABTIPO) AS TABTIPO
					FROM
						TABELAS
					WHERE
						TABSTATUS = 'S'
					ORDER BY
						TABTIPO ASC",
	));

	return array_column($data['dados'], 'TABTIPO');
}