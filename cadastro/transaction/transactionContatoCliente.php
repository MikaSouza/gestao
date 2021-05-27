<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert") || ($_POST["methodPOST"] == "update")) {
	$vIOid = insertUpdateContatoCliente($_POST, 'N');
	sweetAlert('', '', 'S', 'contaCliente.php?id=' . $GET['id'], 'S');
	return;
}
function showContatoCliente($concodigo)
{
	if (isset($concodigo)) {
		$result = consultaComposta([
			'query' => "SELECT
							CONCODIGO,
							CONNOME,
							CONEMAIL,
							CONFONE,
							CONCELULAR,
							CONCARGO,
							CONSETOR,
							CONSENHA,
							CONFOTO
						FROM
						CONTATOS
						WHERE
						CONCODIGO = ?",
			'parametros' => array(
				array($concodigo, PDO::PARAM_INT)
			)
		]);

		return $result['dados'][0];
	}
	return [];
}

function insertUpdateContatoCliente($_POSTCLI, $pSMsg = 'N')
{
	if ($_FILES['vHCONFOTO']['error'] == 0) {
		$nomeArquivo = removerAcentoEspacoCaracter($_FILES['vHCONFOTO']['name']);
		$nomeArquivo = substr(str_replace(',', '', number_format(microtime(true) * 1000000, 0)), 0, 10) . '_' . $nomeArquivo;
		uploadArquivo($_FILES['vHCONFOTO'], '../ged/contatos_fotos', $nomeArquivo);
		$_POSTCLI['vSCONFOTO'] = $nomeArquivo;
		// $_SESSION['SS_USUFOTO'] = $nomeArquivo;
		// $_SESSION['SS_USUNOME'] = $_POSTCLI['vHMCONNOME'];
		// $_SESSION['SS_USUSETOR'] = $_POSTCLI['vHMCONSETOR'];
	}
	$dadosBanco = array(
		'tabela'  => 'CONTATOS',
		'prefixo' => 'CON',
		'fields'  => $_POSTCLI,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
	);
	$id = insertUpdate($dadosBanco);

	return $id;
}
