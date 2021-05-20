<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../../cadastro/combos/comboAtividades.php';
include_once __DIR__.'/../../cadastro/combos/comboTabelas.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'AtendimentoxPlanoTrabalho')
	listAtendimentoxPlanoTrabalhoFilhos($_POST['pIOID'], 'AtendimentoxPlanoTrabalho');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_AtendimentoxPlanoTrabalho')
	fill_AtendimentoxPlanoTrabalho($_GET['vIAXPCODIGO'], $_GET['formatoRetorno']);

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

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("ATENDIMENTOXPLANOTRABALHO", 'crud'),
		"prefixo" => "AXP",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirAtendimentoxPlanoTrabalho'){
	$remuneracao['vIAXPCODIGO'] 		 	  	 = $_POST['hdn_filho_codgo'];
	$remuneracao['vIUSUCODIGO'] 		 	  	 = $_POST['hdn_pai_codgo'];
	$remuneracao['vDAXPDATAALTERACAOSALARIAL']   = $_POST['vDAXPDATAALTERACAOSALARIAL'];
	$remuneracao['vMAXPSALARIOATUAL'] 	 		 = $_POST['vMAXPSALARIOATUAL'];
	$remuneracao['vSAXPMOTIVOALTERACAOSALARIAL'] = $_POST['vSAXPMOTIVOALTERACAOSALARIAL'];
	$remuneracao['vIEMPCODIGO']                  = 1;

	$vIOID = insertUpdateAtendimentoxPlanoTrabalho($remuneracao, 'N');
	echo $vIOID;
}

function insertUpdateAtendimentoxPlanoTrabalho($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ATENDIMENTOXPLANOTRABALHO',
		'prefixo' => 'AXP',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_AtendimentoxPlanoTrabalho($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					ATENDIMENTOXPLANOTRABALHO
				WHERE
					AXPCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	$registro['AXPSALARIOATUAL']            = formatar_moeda($registro['AXPSALARIOATUAL'], false);
	$registro['AXPMOTIVOALTERACAOSALARIAL'] = $registro['AXPMOTIVOALTERACAOSALARIAL'];

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listAtendimentoxPlanoTrabalho(){

	$sql = "SELECT
				r.AXPSALARIOATUAL AS SALARIOATUAL,
				r.AXPDATAALTERACAOSALARIAL,
				r.AXPMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				ATENDIMENTOXPLANOTRABALHO r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.AXPSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function buscaPeriodoPlanoTrabalho($vIOIDPAI,$retorno = 'array'){

	$sql = "SELECT axt.*, a.ATINOME, a.ATIDESCRICAO,
			u.USUNOME AS RESPONSAVEL, t.TABDESCRICAO AS DEPARTAMENTO,
			g.AGEDATAINICIO, g.AGEDATAFINAL, g.AGESITUACAO
			FROM ATENDIMENTOXPLANOTRABALHO axt
			LEFT JOIN ATIVIDADES a ON a.ATICODIGO = axt.ATICODIGO
			LEFT JOIN USUARIOS u ON u.USUCODIGO = axt.AXPRESPONSAVEL
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			LEFT JOIN AGENDA g ON g.AGECODIGO = axt.AGECODIGO
			WHERE axt.AXPSTATUS = 'S' AND
				axt.ATECODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	if($retorno == 'chart') {
		foreach ($result['dados'] as $lista) {
			$lista['AGEDATAFINAL'] = verificarVazio($lista['AGEDATAFINAL']) ? $lista['AGEDATAFINAL'] : date('Y-m-d');

			$lista['AGEDATAINICIO'] = ($lista['AGEDATAINICIO'] > date('Y-m-d') || $lista['AGEDATAINICIO'] == '0000-00-00') ?  date('Y-m-d') : $lista['AGEDATAINICIO'];
			$lista['AGEDATAFINAL'] = ($lista['AGEDATAFINAL'] > date('Y-m-d') || $lista['AGEDATAFINAL'] == '0000-00-00') ?  date('Y-m-d') : $lista['AGEDATAFINAL'];

			$arrayRetorno[] = [$lista['ATINOME'], $lista['AGEDATAINICIO'], $lista['AGEDATAFINAL']];
		}
		return $arrayRetorno;
	}else{
		return $result;
	}

}

function listAtendimentoxPlanoTrabalhoFilhos($vIOIDPAI, $tituloModal){


	$result = buscaPeriodoPlanoTrabalho($vIOIDPAI,'array');

	$vAConfig['TRANSACTION'] = "transactionAtendimentoxPlanoTrabalho.php";
	$vAConfig['DIV_RETORNO'] = "div_subAtendimentos";
	$vAConfig['FUNCAO_RETORNO'] = "AtendimentoxPlanoTrabalho";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$tituloModal = 'AtividadePlanoTrabalho';
	$vAConfig['vATitulos']     = array('', 'Data Previsão', 'Atividade', 'Especificação', 'Departamento', 'Responsável', 'Situação', 'Conclusão');
	$vAConfig['vACampos']      = array('AXPCODIGO', 'AGEDATAINICIO', 'ATINOME', 'ATIDESCRICAO', 'DEPARTAMENTO', 'RESPONSAVEL', 'AGESITUACAO', 'AXPDATACONCLUSAO');
	$vAConfig['vATipos']       = array('chave', 'date', 'varchar', 'varchar', 'varchar', 'varchar', 'simNao', 'varchar');
	?>

	<?php include_once '../../twcore/teraware/componentes/gridPadraoFilha.php'; ?>


	<?php


	return;
}


function fill_ProcessosxAtividades($pOid)
{
	// $fill = consultaComposta([
	// 	'query' => "SELECT
	// 					a.PXACODIGO,
	// 					a.PXASTATUS,
	// 					a.TABDEPARTAMENTO,
	// 					a.PXAPRAZO,
	// 					a.ATICODIGO,
	// 					a.PXAPOSICAO
	// 				FROM
	// 					PROCESSOSXATIVIDADES a
	// 				INNER JOIN ATENDIMENTOXPLANOTRABALHO axt ON axt.PRICODIGO = a.PRICODIGO
	// 				WHERE
	// 					axt.ATECODIGO = ?
	// 				AND
	// 					a.PXASTATUS = 'S'
	// 				GROUP BY ATICODIGO,TABDEPARTAMENTO
	// 				ORDER BY
	// 					a.PXAPOSICAO ASC",
	// 	'parametros' => [
	// 		[$pOid, PDO::PARAM_INT],
	// 	],
	// ]);

	$fill['dados'][] = [
		'ATICODIGO'    => 0,
		'ATISTATUS'    => 'S',
		'TABDEPARTAMENTO'    => 0,
		'ATIPRAZO'     => '',
		'ATIATIVIDADE' => '',
	];

	return $fill['dados'];
}

?>