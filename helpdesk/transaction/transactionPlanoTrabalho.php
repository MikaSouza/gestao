<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdatePlanoTrabalho($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_PlanoTrabalho($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$atividades = fill_ProcessosxAtividades($vIOid);
} else {
	$fill['dados'][] = [
		'ATICODIGO'    => 0,
		'ATISTATUS'    => 'S',
		'TABDEPARTAMENTO'    => 0,
		'ATIPRAZO'     => '',
		'ATIATIVIDADE' => '',
	];
	$atividades = $fill['dados'];
}

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

function listPlanoTrabalho(){

	$sql = "SELECT
			C.*
		FROM PROCESSOSINTERNO C			
			WHERE C.PRISTATUS = 'S'
			ORDER BY 1";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdatePlanoTrabalho($_POSTDADOS, $pSMsg = 'N'){
	print_r($_POSTDADOS);
	$dadosBanco = array(
		'tabela'  => 'PROCESSOSINTERNO',
		'prefixo' => 'PRI',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	if (isset($_POSTDADOS['car'])) {
		foreach ($_POSTDADOS['car'] as $i => $atividade) {
			insertUpdate([
				'tabela'  => 'PROCESSOSXATIVIDADES',
				'prefixo' => 'PXA',
				'debug'   => 'S',
				'msg'     => 'N',
				'fields'  => [
					'vIPXACODIGO'    	=> '',
					'vIPRICODIGO'    	=> $id,
					'vITABDEPARTAMENTO' => $atividade['model'],
					'vIPXAPRAZO'     	=> $atividade['dias'],
					'vIATICODIGO' 	 	=> $atividade['make'],
					'vSPXASTATUS' 	 	=> 'S',
					'vIPXAPOSICAO'		=> 1 //$_POSTDADOS['vHPXAPOSICAO'][$i]
				],
			]);
		}
	}

	return $id;
}

function fill_PlanoTrabalho($pOid){
	$SqlMain = "SELECT
                    *
                FROM PROCESSOSINTERNO
                    WHERE PRICODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function fill_ProcessosxAtividades($pOid)
{
	$fill = consultaComposta([
		'query' => "SELECT
						a.PXACODIGO,
						a.PXASTATUS,
						a.TABDEPARTAMENTO,
						a.PXAPRAZO,
						a.ATICODIGO,
						a.PXAPOSICAO
					FROM
						PROCESSOSXATIVIDADES a
					WHERE
						a.PRICODIGO = ?
					AND
						a.PXASTATUS = 'S'
					ORDER BY
						a.PXAPOSICAO ASC",
		'parametros' => [
			[$pOid, PDO::PARAM_INT],
		],
	]);

	$fill['dados'][] = [
		'ATICODIGO'    => 0,
		'ATISTATUS'    => 'S',
		'TABDEPARTAMENTO'    => 0,
		'ATIPRAZO'     => '',
		'ATIATIVIDADE' => '',
	];

	return $fill['dados'];
}