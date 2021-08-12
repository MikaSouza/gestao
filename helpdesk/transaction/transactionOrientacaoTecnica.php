<?php

if (($_POST["methodPOST"] == "insert") || ($_POST["methodPOST"] == "update")) {
	$vIOid = insertUpdateOrientacaoTecnica($_POST, 'S');
	return;
} elseif (($_GET["method"] == "consultar") || ($_GET["method"] == "update")) {
	$vROBJETO = fill_OrientacaoTecnica($_GET['oid'], $vAConfiguracaoTela);
	$vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'CODIGO'];
	$vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'STATUS'];
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
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

function listOrientacaoTecnica($_POSTDADOS)
{
	$where = '';
	if (verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])) {
		if ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S') {
			$where .= "AND C.OXTSTATUS = 'S' ";
		} elseif ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N') {
			$where .= "AND C.OXTSTATUS = 'N' ";
		}
	} else {
		$where .= "AND C.OXTSTATUS = 'S' ";
	}

	if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
		$where .= 'AND C.OXTDATA_INC >= ? ';
	}
	if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
		$where .= 'AND C.OXTDATA_INC <= ? ';
	}

	if (verificarVazio($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL'])) {
		$where .= 'AND C.OXTSEQUENCIAL = ? ';
	}

	$sql = "SELECT
				*
			FROM
				ORIENTACAOTECNICA C
			WHERE
				1 = 1
			" .	$where	. "
			LIMIT 100	";

	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array()
	);
	if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio'] . " 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim'] . " 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if (verificarVazio($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL'])) {
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIOXTSEQUENCIAL'], PDO::PARAM_INT);
	}
	$result = consultaComposta($arrayQuery);

	return $result;
}

function insertUpdateOrientacaoTecnica($parametros, $pSMsg = 'N')
{
	if ($_FILES['vHARQUIVO']['error'] == 0) {
		$nomeArquivo = $parametros['vIOXTNUMERO'] . '_' . $parametros['vIOXTANO'] . '.pdf';
		uploadArquivo($_FILES['vHARQUIVO'], '../ged/orientacao_tecnica', $nomeArquivo);
	}
	$dadosBanco = array(
		'tabela'  => 'ORIENTACAOTECNICA',
		'prefixo' => 'OXT',
		'fields'  => $parametros,
		'msg'     => 'N',
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);

	if (empty($parametros['vIOXTCODIGO'])) {
		$orientacao['codigo'] = $id;
		$orientacao['titulo'] = $parametros['vSOXTTITULO'];
		$orientacao['arquivo'] = $nomeArquivo;

		enviarOrientacaoTecnica($orientacao);
	}

	sweetAlert('', '', 'S', 'cadOrientacaoTecnica.php?method=update&oid=' . $id, 'S');

	return $id;
}

function fill_OrientacaoTecnica($pOid)
{
	$SqlMain = 'SELECT c.*
				 From ORIENTACAOTECNICA c
				 Where c.OXTCODIGO = ' . $pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao, $SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function getEmailContatos()
{
	$result = consultaComposta([
		'parametros' => "SELECT DISTINCT
							c.CONEMAIL
						FROM
							CONTATOS c
						LEFT JOIN
							CLIENTES l ON c.CLICODIGO = l.CLICODIGO
						WHERE
							c.CONSTATUS = 'S'
						AND
							l.CLISTATUS = 'S'",
		'parametros' => [],
	]);
	return $result['dados'];
}

function enviarOrientacaoTecnica($orientacao)
{
	require_once __DIR__ . '/../../twcore/vendors/phpmailer/email.php';
	// $emails = getEmailContatos();
	$emails = ['atendimento@teraware.com.br', 'gestao@gestao.srv.br', 'nathan@gestao.srv.br'];
	$enviados = [];

	foreach ($emails as $email) {
		$dadosEmail = array(
			'titulo'        => $orientacao['titulo'],
			'descricao'     => 'Olá!<br />
								Acaba de ser disponibilizada uma nova Orientação Técnica no sistema INFOGESTÃO. <br/>
								Para acessar o sistema, clique no link a seguir:',
			'destinatarios' => array(
				$email,
			),
			'fields' => array(
				'Orientação Técnica'    => 'https://gestao-srv.teraware.net.br/autenticacao/login.php',
			)
		);
		$enviados[] = emailField($dadosEmail);
	}

	$fails = array_filter($enviados, function ($enviado) {
		return $enviado != 1;
	});

	if (count($fails) > 0) {
		$response = [
			'success' => false,
			'msg'     => 'Não foi possível enviar E-mail.'
		];

		if (count($fails) != count($enviados)) {
			$response['msg'] .= '. Os demais foram enviados com sucesso!';
		}

		echo json_encode($response);
		die();
	} else {
		echo json_encode([
			'success' => true,
			'msg' => 'Todos os E-mails foram enviados com sucesso!',
		]);
	}
}

function listOrientacaoTecnicaPainel($_POSTDADOS)
{
	$where = '';
	if (verificarVazio($_POSTDADOS['vDDataInicio']))
		$where .= 'AND A.OXTDATA_INC >= ? ';
	if (verificarVazio($_POSTDADOS['vDDataFim']))
		$where .= 'AND A.OXTDATA_INC <= ? ';
	$sql = "SELECT
				A.OXTNUMERO, A.*
			FROM
				ORIENTACAOTECNICA A
			WHERE
				A.OXTSTATUS = 'S'
			" .	$where	. "
			ORDER BY A.OXTNUMERO desc";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array()
	);
	if (verificarVazio($_POSTDADOS['vDDataInicio'])) {
		$varIni = $_POSTDADOS['vDDataInicio'] . " 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if (verificarVazio($_POSTDADOS['vDDataFim'])) {
		$varFim = $_POSTDADOS['vDDataFim'] . " 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	$result = consultaComposta($arrayQuery);

	return $result;
}

function listOrientacaoTecnicaPainelAnexos($vIOXTCODIGO)
{
	$sql = "SELECT
                G.GEDCODIGO,
                DATE_FORMAT(G.GEDDATA_INC, '%d/%m/%Y %H:%i') AS DATA_INCLUSAO,
                G.GEDTIPO,
                U.USUNOME,
                G.GEDNOMEARQUIVO,
				CONCAT('https://gestao-srv.twflex.com.br/', G.GEDDIRETORIO, '/', G.GEDNOMEARQUIVO) AS LINK
            FROM
                GED G
			LEFT JOIN TABELAS T ON T.TABCODIGO = G.GEDTIPO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = G.GEDUSU_INC
			WHERE
				GEDSTATUS = 'S'
            AND G.GEDVINCULO = ?
			AND G.MENCODIGO = 2026 ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vIOXTCODIGO, PDO::PARAM_INT)
		)
	);
	$result = consultaComposta($arrayQuery);

	return $result;
}
