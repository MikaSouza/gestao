<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_POST['method']) && $_POST['method'] == 'enviarEmail'){
	echo enviarEmailRecuperarSenha($_POST['Login']);
}

function enviarEmailRecuperarSenha($login){
	$sql = "SELECT
				C.CONCODIGO,
				C.CONSENHA,
				C.CONNOME,
				C.CONFOTO,
				R.CLICODIGO,
				R.CLIRAZAOSOCIAL,
				L.CTRCODIGO,
				C.CONEMAIL
			FROM
				CONTATOS C
			INNER JOIN CLIENTES R ON R.CLICODIGO = C.CLICODIGO
			INNER JOIN CONTRATOS L ON L.CLICODIGO = C.CLICODIGO
			WHERE
				C.CONSTATUS = 'S' AND
				R.CLISTATUS = 'S' AND
				L.CTRSTATUS = 'S' AND
				L.CTRPOSICAO IN (27026, 27115) AND
				C.CONEMAIL = ? ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($login, PDO::PARAM_STR)
		)
    );
    $result = consultaComposta($arrayQuery);

    if ($result['quantidadeRegistros'] > 0) {

		$senha = gerarSenhaAleatoria(10);

        $vConexao = sql_conectar_banco(vGBancoSite);
        $update = "		UPDATE CONTATOS SET
                        CONSENHA = '{$senha}'
                        WHERE CONCODIGO = '{$result['dados'][0]['CONCODIGO']}'";
        $resultSet = sql_executa(vGBancoSite, $vConexao,$update);

        $_SESSION['SI_USUCODIGO'] = $result['dados'][0]['CONCODIGO'];

        $retornoEmail['retorno'] = enviarEmailRecuperarSenhaSistema($result['dados'][0]['CONEMAIL'], $senha, $result['dados'][0]['CONNOME']);

        return json_encode($retornoEmail);

    }else{

        $retornoEmail['retorno'] = 0;
        return json_encode($retornoEmail);
    }

}

function enviarEmailRecuperarSenhaSistema($vSCONEMAIL, $vSCONSENHA, $vSCONNOME)
{
	require_once __DIR__ . '/../../twcore/vendors/phpmailer/email.php';

	// $emails = ['atendimento@teraware.com.br', 'gestao@gestao.srv.br', 'nathan@gestao.srv.br'];

	$url_acesso = "https://gestao-srv.twflex.com.br/";
	$assunto = utf8_decode("Acesso ao Sistema da Empresa Gestão");

	$enviados = [];

	$dadosEmail = array(
		'titulo'        => $assunto,
		'descricao'     => "<p>Prezado(a) " . $vSCONNOME . "<br /><br />
								Informamos que você foi cadastrado no Sistema da Empresa Gestão<br />
								Para acessar o sistema siga os seguintes passos:
							</p>
							<ul type='circle'>
								<li>Acesse o endereço:<br/>
								- <a href='" . $url_acesso . "'>" . $url_acesso . "</a><br/>
								</li>
								<li>Informe o seu e-mail: <strong>" . $vSCONEMAIL . "</strong></li>
								<li>Informe a sua senha: <strong>" . $vSCONSENHA . "</strong></li>
								<li>Clique no botão <strong>Entrar</strong> para realizar o login no sistema.</li>
							</ul>
							<br />
							",

		'destinatarios' => array(
			'gestao@gestao.srv.br', $vSCONEMAIL
		),
		'fields' => array()
	);
	$enviados[] = emailField($dadosEmail);


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

?>
