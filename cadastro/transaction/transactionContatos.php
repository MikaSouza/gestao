<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxContatos')
	listContatos($_POST['pIOID'], 'ClientesxContatos');

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxContatos')
	insertUpdateContatos($_POST);

if (isset($_POST['method'])) {
	switch ($_POST['method']) {
		case 'listar-contatos':
			echo json_encode(getContatosByCodigoCliente($_POST['clicodigo']));
			break;
		case 'enviarAcesso':
			echo json_encode(enviarEmailAcessoSistema($_POST['concodigos']));
			break;
		case 'fill_ClientesxContatos':
			echo json_encode(showContato((int) $_POST['vICONCODIGO']));
			break;
		case 'excluir-contato':
			excluirContato($_POST['concodigo']);
			break;
	}
}

/*
if ($_GET['hdn_metodo_fill'] == 'fill_ClientesxContatos')
	fill_Contatos($_GET['vICONCODIGO'], $_GET['formatoRetorno']);
*/
if ($_GET['hdn_metodo_fill'] == 'fill_ClientesxContatos')
	fill_ContatosPadrao($_GET['vICONCODIGO'], $_GET['formatoRetorno']);

if ($_GET['hdn_metodo_fill'] == 'gerarSenhaContatos') {
	echo gerarSenhaAleatoria(9, true, false);
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	echo excluirAtivarRegistros(array(
		"tabela"   => Encriptar("CONTATOS", 'crud'),
		"prefixo"  => "CON",
		"status"   => "N",
		"ids"      => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa"  => "N",
	));
}

function getContatosByCodigoCliente($codigo)
{
	if (empty($codigo)) {
		return array('success' => false, 'message' => 'Informe um código de cliente válido!');
	}

	$result = consultaComposta([
		'query' => "SELECT
						CONCODIGO,
						CONNOME,
						CONEMAIL,
						CONSENHA,
						CONFONE,
						CONCELULAR,
						CONCARGO,
						CONSETOR,
						DATE_FORMAT(CONDATA_INC, '%d/%m/%Y %H:%i') AS DATA_INCLUSAO
					FROM
						CONTATOS
					WHERE
						CONSTATUS = 'S'
					AND
						CLICODIGO = ?",
		'parametros' => array(
			array($codigo, PDO::PARAM_INT)
		)
	]);

	if ($result['quantidadeRegistros'] > 0) {
		return array('success' => true, 'contatos' => $result['dados']);
	} else {
		return array('success' => false, 'message' => 'Nenhum registro encontrado!');
	}
}

function insertUpdateContatos($_POSTDADOS, $pSMsg = 'N')
{
	if ($_POSTDADOS['vHCONEMAIL'] != '') {

		$_POSTDADOSCON['vICONCODIGO'] = $_POSTDADOS['vHCONCODIGO'];
		$_POSTDADOSCON['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCON['vSCONNOME'] = $_POSTDADOS['vHCONNOME'];
		$_POSTDADOSCON['vSCONEMAIL'] = $_POSTDADOS['vHCONEMAIL'];
		$_POSTDADOSCON['vSCONSITE'] = $_POSTDADOS['vHCONSITE'];
		$_POSTDADOSCON['vSCONCELULAR'] = $_POSTDADOS['vHCONCELULAR'];
		$_POSTDADOSCON['vSCONFONE'] = $_POSTDADOS['vHCONFONE'];
		$_POSTDADOSCON['vSCONCARGO'] = $_POSTDADOS['vHCONCARGO'];
		$_POSTDADOSCON['vSCONSETOR'] = $_POSTDADOS['vHCONSETOR'];
		$_POSTDADOSCON['vSCONOBSERVACOES'] = $_POSTDADOS['vHCONOBSERVACOES'];
		$_POSTDADOSCON['vSCONPRINCIPAL'] = $_POSTDADOS['vHCONPRINCIPAL'];
		$_POSTDADOSCON['vSCONSENHA'] = $_POSTDADOS['vHCONSENHA'];

		$dadosBanco = array(
			'tabela'  => 'CONTATOS',
			'prefixo' => 'CON',
			'fields'  => $_POSTDADOSCON,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
		);
		$id = insertUpdate($dadosBanco);

		return $id;
	}
}

function getContato($concodigo)
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
								DATE_FORMAT(CONDATA_INC, '%d/%m/%Y %H:%i') AS DATA_INCLUSAO
							FROM
								CONTATOS
							WHERE
								CONCODIGO = ?",
			'parametros' => array(
				array($concodigo, PDO::PARAM_INT)
			)
		]);

		return $result['dados'];
	}
}

function getDadosAcesso($concodigos)
{
	$ids = implode(',', $concodigos);

	$result = consultaComposta([
		'query' => "SELECT
						CONCODIGO,
						CONNOME,
						CONEMAIL,
						CONSENHA
					FROM
						CONTATOS
					WHERE
						CONSTATUS = 'S'
					AND
						CONCODIGO IN ({$ids})",
		'parametros' => array()
	]);
	return $result['dados'];
}

function enviarEmailAcessoSistema($concodigos)
{
	require_once __DIR__ . '/../../twcore/vendors/phpmailer/email.php';

	$contatos = getDadosAcesso($concodigos);

	// $emails = ['atendimento@teraware.com.br', 'gestao@gestao.srv.br', 'nathan@gestao.srv.br'];

	$url_acesso = "https://gestao-srv.twflex.com.br/";
	$assunto = utf8_decode("Acesso ao Sistema da Empresa Gestão");

	$enviados = [];

	foreach ($contatos as $contato) {
		$dadosEmail = array(
			'titulo'        => $assunto,
			'descricao'     => "<p>Prezado(a) " . $contato['CONNOME'] . "<br /><br />
									Informamos que você foi cadastrado no Sistema da Empresa Gestão<br />
									Para acessar o sistema siga os seguintes passos:
								</p>
								<ul type='circle'>
									<li>Acesse o endereço:<br/>
									- <a href='" . $url_acesso . "'>" . $url_acesso . "</a><br/>
									</li>
									<li>Informe o seu e-mail: <strong>" . $contato['CONEMAIL'] . "</strong></li>
									<li>Informe a sua senha: <strong>" . $contato['CONSENHA'] . "</strong></li>
									<li>Clique no botão <strong>Entrar</strong> para realizar o logon no sistema.</li>
								</ul>
								<br />
								",
			'destinatarios' => array(
				'atendimento@teraware.com.br'
			),
			'fields' => array()
		);
		$enviados[$contato['CONEMAIL']] = emailField($dadosEmail);
	}

	$fails = array_filter($enviados, function ($enviado) {
		return $enviado != 1;
	});

	if (count($fails) > 0) {
		$response = [
			'success' => false,
			'msg'     => 'Não foi possível enviar E-mail para os contatos: ' . implode(', ', array_values($fails)),
		];

		if (count($fails) != count($enviados)) {
			$response['msg'] .= '. Os demais foram enviados com sucesso!';
		}

		return $response;
	} else {
		return ['success' => true, 'msg' => 'Todos os E-mails foram enviados com sucesso!'];
	}
}

function showContato($concodigo)
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
							CONSENHA
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

/**
 */
function excluirContato($concodigo)
{
	try {
		//Iniciando a conexão
		$db = conectarBanco();
		//Preparando a query
		$stmt = $db->prepare("UPDATE CONTATOS SET CONSTATUS = 'N' WHERE CONCODIGO = :id");
		$stmt->BindValue(':id', $concodigo, PDO::PARAM_INT);
		$stmt->execute();

		echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'success'), JSON_FORCE_OBJECT);
	} catch (PDOException $e) {
		echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'Error: ' . $e->getMessage()), JSON_FORCE_OBJECT);
	}
}

function fill_Contatos($vICLICODIGO, $vSCONTIPO, $formatoRetorno = 'array')
{
	$sql = "SELECT *
			FROM CONTATOS
			WHERE CONSTATUS = 'S'
			AND CLICODIGO = ?
			AND CONPRINCIPAL = ? ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vICLICODIGO, PDO::PARAM_INT),
			array($vSCONTIPO, PDO::PARAM_STR)
		)
	);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];
	if ($formatoRetorno == 'array') {
		return $registro !== null ? $registro : "N";
	} elseif ($formatoRetorno == 'json') {
		echo json_encode($registro);
	}
	return $registro !== null ? $registro : "N";
}

function fill_ContatosPadrao($vICONCODIGO, $formatoRetorno = 'array')
{

	$sql = "SELECT *
			FROM CONTATOS
			WHERE CONSTATUS = 'S'
			AND CONCODIGO = ? ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vICONCODIGO, PDO::PARAM_INT)
		)
	);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];
	if ($formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if ($formatoRetorno == 'json')
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}


function listContatos($vIOIDPAI, $tituloModal)
{

	if ($vIOIDPAI != "") {
		$sql = "SELECT
				c.*
			FROM
				CONTATOS c
			WHERE
				c.CONPRINCIPAL = 'N' AND
				c.CONSTATUS = 'S' AND
				c.CLICODIGO = " . $vIOIDPAI;
		$arrayQuery = array(
			'query' => $sql,
			'parametros' => array()
		);
		$result = consultaComposta($arrayQuery);

    ?>
	<button type="button" class="btn btn-primary px-4 btn-rounded float-right mt-0 mb-3"
	onclick="exibirFormModal('','modal_div_<?=$tituloModal?>','<?=$tituloModal?>')">+ Novo Registro</button>

	<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
	style="border-collapse: collapse; border-spacing: 0; width: 100%;">
	<thead>
		<tr>
			<th>Nome</th><th>E-mail</th><th>Telefone</th><th>Celular</th><th>Cargo</th><th>Setor</th><th>Data Inclusão</th>
			<th>Enviar Acesso</th>
			<th>Ações</th>
		</tr>
	</thead>
	<?php
    $colsp = count($vAConfig['vATitulos']);
    $arrayGrid = $result;

    if ($arrayGrid['quantidadeRegistros'] == 0) {
        echo "<td align='center' colspan='".$colsp."'>Sem dados disponíveis na tabela.</td>";
    } else {
        foreach ($arrayGrid['dados'] as $arrayGrid) : ?>

	<tr>
		<td align="left"><?= $arrayGrid['CONNOME'];?></td>
		<td align="left"><?= $arrayGrid['CONEMAIL'];?></td>
		<td align="left"><?= $arrayGrid['CONFONE'];?></td>
		<td align="left"><?= $arrayGrid['CONCELULAR'];?></td>
		<td align="left"><?= $arrayGrid['CONCARGO'];?></td>
		<td align="left"><?= $arrayGrid['CONSETOR'];?></td>
		<td align="center"><?= formatar_data($arrayGrid['CONDATA_INC']);?></td>
		<td align="center"><input type='checkbox' title='ckPadrao'
													name='vEnviarAcesso[]' value='<?=$arrayGrid['CONCODIGO'];?>'
													id='vEnviarAcesso[]' /></td>
		<td>			
			<a onclick="exibirFormModal(<?=$arrayGrid['CONCODIGO'];?>,'','<?=$tituloModal?>')" class="mr-2 mdi"
				style="cursor: pointer;" title="Editar Registro" alt="Editar Registro"><i
					class="fas fa-edit text-info font-16"></i></a>
			<a href="#"
				onclick="excluirRegistroGridFilha('<?= $arrayGrid['CONCODIGO']; ?>', 'transactionContatos.php', 'excluirFilho', 'div_contatos', '<?= $vIOIDPAI; ?>', 'ClientesxContatos')"
				title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
		</td>
	</tr>
	
	<?php endforeach;
    }
    ?>
	<tr>
		<td align="right" colspan="8"> 
			<button type="button" title="Enviar Acesso" style="width:150px"
									onclick="enviarAcessos();" class="btn btn-primary waves-effect waves-light">Enviar
									Acesso</button>
		</td>
		<td align="left">&nbsp</td> 
	</tr>							
	</table>
	
	<?php

		return;
	}
}
