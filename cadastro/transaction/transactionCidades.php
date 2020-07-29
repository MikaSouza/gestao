<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
	include_once '../../twcore/teraware/php/constantes.php';
	$pAConfiguracaoTela = configuracoes_menu_acesso($_POST["vIOIDMENU"]);
	$config_excluir = array(
		"tabela" => Encriptar($pAConfiguracaoTela['MENTABELABANCO'], 'crud'),
		"prefixo" => $pAConfiguracaoTela['MENPREFIXO'],
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listCidades($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND C.CIDSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND C.CIDSTATUS = 'N' ";
	}else
		$where .= "AND C.CIDSTATUS = 'S' ";
	$sql = "SELECT
				C.*, E.ESTDESCRICAO
			FROM
				CIDADES C
			LEFT JOIN ESTADOS E ON E.ESTCODIGO = C.ESTCODIGO
			WHERE
				1 = 1
			".	$where	."
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateCidades($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CIDADES',
		'prefixo' => 'CID',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => 'cadCidades.php',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_Cidades($pOid){
	$SqlMain = 'Select *
				 From CIDADES
				 Where CIDCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function getIdLogradouro($pSTipoLogradouro){
   if($pSTipoLogradouro != ''){
	   $pSTipoLogradouro = strtoupper($pSTipoLogradouro);
	   $sql = "SELECT
					TLOCODIGO
				FROM
					TIPOLOGRADOURO
				 WHERE TLODESCRICAO LIKE ? ";
	   $arrayQuery = array(
		  'query' => $sql,
		  'parametros' => array(
								array("%$pSTipoLogradouro%", PDO::PARAM_STR)
							)
	   );
	   $list = consultaComposta($arrayQuery);
	   if($list['quantidadeRegistros'] > 0)
		   return $list['dados'][0]['TLOCODIGO'];
	   else
		   return 0;
   }
}

function getIdCidade($pSNomeCidade, $pIEstCodigo=null){
	if($pSNomeCidade != ''){
		$pSNomeCidade = strtoupper($pSNomeCidade);
		$sql = "SELECT
					CIDCODIGO
				 FROM
					CIDADES
				 WHERE CIDDESCRICAO LIKE ?
				 AND ESTCODIGO = ?
				 AND CIDSTATUS = 'S' ";
	    $arrayQuery = array(
		  'query' => $sql,
		  'parametros' => array(
								array("%$pSNomeCidade%", PDO::PARAM_STR),
								array("$pIEstCodigo", PDO::PARAM_INT)
							)
	   );
	   $list = consultaComposta($arrayQuery);
	   if($list['quantidadeRegistros'] > 0)
		   return $list['dados'][0]['CIDCODIGO'];
	   else
		   return 0;
	}
}

function getIdUf($pSNomeEstado){
	if($pSNomeEstado != ''){
		$pSNomeEstado = strtoupper($pSNomeEstado);
		$sql = "SELECT
					ESTCODIGO
				FROM
					ESTADOS
				 Where (UPPER(ESTSIGLA) LIKE ? OR UPPER(ESTDESCRICAO) LIKE ?) ";
	   $arrayQuery = array(
		  'query' => $sql,
		  'parametros' => array(
								array("%$pSNomeEstado%", PDO::PARAM_STR),
								array("%$pSNomeEstado%", PDO::PARAM_STR)
							)
	   );
	   $list = consultaComposta($arrayQuery);
	   if($list['quantidadeRegistros'] > 0)
		   return $list['dados'][0]['ESTCODIGO'];
	   else
		   return 0;
	}
}