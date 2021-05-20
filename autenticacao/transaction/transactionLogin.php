<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if ($_POST['vSLoginRecuperar'] && $_POST['vSLoginRecuperar'] != '') {

	$result = verificarUsername($_POST['vSLoginRecuperar']);

	if ($result['success'] == 1) {
		header('location:recuperar-login.php?vMGS=E');
	} else {
		// mandar email
	}
}

//if(verificacaoGoogleRecaptcha($_POST['g-recaptcha-response'])){
unset($_POST['g-recaptcha-response']);
$email = filter_var($_POST["vSUsuario"], FILTER_SANITIZE_EMAIL);

if ((verificarVazio($email)) && (verificarVazio($_POST["vSSenha"]))) {
	$senha	    = $_POST['vSSenha'];
	$login = loginApp($email, $senha);
	if ($login['USUCODIGO'] !== false && is_numeric($login['USUCODIGO'])) {
		// Usuario
        $_SESSION['SS_TIPOLOGIN'] = 'U';
		$_SESSION['SI_USUCODIGO'] = $login['USUCODIGO'];
		$_SESSION['SS_USUNOME'] = $login['USUNOME'];
		$_SESSION['SS_USUSETOR'] = $login['SETOR'];
		$_SESSION['SS_SECURITY'] = '1ODLkhuDE2OE';
		if ($login['USUCODIGO'] == 1)
			$_SESSION['SS_USUMASTER'] = 'S';
		else
			$_SESSION['SS_USUMASTER'] = 'N';
		$_SESSION['SS_USUFOTO'] = $login['USUFOTO'];

		include_once __DIR__ . '/../../rh/transaction/transactionLogsAcessos.php';
		$dadosBanco = array(
			'vILUACODIGO'  => '',
			'vSLUAIP' => $_SERVER["REMOTE_ADDR"],
			'vIEMPCODIGO'  => 2
		);
		insertUpdateLogsAcessos($dadosBanco, 'N');

		header('location:' . URL_BASE . 'cadastro/#');
	} elseif ($login['CONCODIGO'] !== false && is_numeric($login['CONCODIGO'])) {
		// Radio
		$_SESSION['SS_TIPOLOGIN'] = 'C';
		$_SESSION['SI_CLICODIGO'] = $login['CLICODIGO'];
		$_SESSION['SI_USUCODIGO'] = $login['CONCODIGO'];
		$_SESSION['SS_USUNOME'] = $login['CONNOME'];
		$_SESSION['SS_SECURITY'] = '1ODLkhuDE2OE';
		$_SESSION['SS_USUMASTER'] = 'N';
		$_SESSION['SS_EMPLOGO'] = $login['EMPLOGOMARCA'];
		$_SESSION['SI_CTRCODIGO'] = $login['CTRCODIGO'];
		$_SESSION['SS_USUFOTO'] = $login['CONFOTO'];
		//radio
		/*
		include_once __DIR__.'/../../rh/transaction/transactionRadiosLogsAcessos.php';
		$dadosBanco = array(
			'vILRACODIGO'  => '',
			'vSLRAIP' => $_SERVER["REMOTE_ADDR"],
			'vICLICODIGO'  => $login['CLICODIGO'],
			'vICONCODIGO'  => $login['CONCODIGO']
			);
		insertUpdateRadiosLogsAcessos($dadosBanco, 'N'); */
		header('location:' . URL_BASE . 'cadastro/indexClientes.php');            
	} else {
		header('location:login.php?vMGS=E');
	}	
} else {
	return false;
}

//}

function loginApp($documento, $senha)
{
	$sql = "SELECT
				u.USUSENHA,
				u.USUNOME,
				u.USUEMAIL,
				u.USUCODIGO,
				u.USULOGIN,
				t.TABDESCRICAO AS SETOR,
				u.USUFOTO
			FROM
				USUARIOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			WHERE
				u.USUSTATUS = 'S' and
				u.USUEMAIL = ? ";
	$dadosBanco = array(
		'query' => $sql,
		'parametros' => array(
			array($documento, PDO::PARAM_STR)
		)
	);
	$list = consultaComposta($dadosBanco);

	if ($list['quantidadeRegistros'] > 0) {
		$vSSenhaAtual = Desencriptar($list['dados'][0]['USUSENHA'], cSPalavraChave);
		if ($vSSenhaAtual == $senha) {
			//permissões
			fillAcessosSistema($list['dados'][0]['USUCODIGO']);
			unset($list['dados'][0]['USUSENHA']);
			return $list['dados'][0];
		} else {
			return false;
		}
	} else {
        //verificar login cliente
        $sql = "SELECT
				C.CONCODIGO,
				C.CONSENHA,
				C.CONNOME,
				C.CONFOTO,
				R.CLICODIGO,
				R.CLIRAZAOSOCIAL,
				L.CTRCODIGO
			FROM
				CONTATOS C
			INNER JOIN CLIENTES R ON R.CLICODIGO = C.CLICODIGO
			INNER JOIN CONTRATOS L ON L.CLICODIGO = C.CLICODIGO
			WHERE
				C.CONSTATUS = 'S' AND
				R.CLISTATUS = 'S' AND
				L.CTRSTATUS = 'S' AND
				L.CTRPOSICAO IN (27026, 27115) AND
				C.CONEMAIL = ? 	";
        $dadosBanco = array(
                        'query' => $sql,
                        'parametros' => array(
                            array($documento, PDO::PARAM_STR)
                        )
                    );
        $list = consultaComposta($dadosBanco);
        if ($list['quantidadeRegistros'] > 0) {
            $vSPassou = 'N';
            foreach ($list['dados'] as $result1) {
                //$vSSenhaAtual = Desencriptar($result1['CONSENHA'], cSPalavraChave);
                $vSSenhaAtual = $result1['CONSENHA'];
                if ($vSSenhaAtual == $senha) {
                    $vSPassou = 'S';
                    unset($result1['CONSENHA']);
                    return $result1;
                }
            }
            if ($vSPassou == 'N') {
                return false;
            }
        } else {
            return false;
        }
    }
}

function verificarUsername($username)
{

	$nick = consultaComposta(array(
		'query' => "SELECT USULOGIN FROM USUARIOS WHERE USULOGIN = ?",
		'parametros' => array(
			array($username, PDO::PARAM_STR)
		)
	));

	if ($nick['quantidadeRegistros'] > 0) {

		return [
			'success' => false,
			'msg'     => 'Este nome de usuário já foi utilizado, favor digitar um novo nome de usuário!'
		];
	}

	return [
		'success' => true,
		'msg' => 'Este nome de usuário está disponível!'
	];
}

function fillAcessosSistema($vIUSUCODIGO)
{
	$sql = "SELECT
					ACETABELA,
					ACECONSULTA,
					ACEINCLUSAO,
					ACEALTERACAO,
					ACEEXCLUSAO,
					ACEEXPORTAR
				FROM
					ACESSOS
				WHERE
					USUCODIGO = ? ";
	$dadosBanco = array(
		'query' => $sql,
		'parametros' => array(
			array($vIUSUCODIGO, PDO::PARAM_INT)
		)
	);
	$list = consultaComposta($dadosBanco);
	foreach ($list['dados'] as $tabelas) :
		$_SESSION['SA_ACESSOS']['TABELA'][$tabelas['ACETABELA']]['CONSULTA'] = $tabelas['ACECONSULTA'];
		$_SESSION['SA_ACESSOS']['TABELA'][$tabelas['ACETABELA']]['INCLUSAO'] = $tabelas['ACEINCLUSAO'];
		$_SESSION['SA_ACESSOS']['TABELA'][$tabelas['ACETABELA']]['ALTERACAO'] = $tabelas['ACEALTERACAO'];
		$_SESSION['SA_ACESSOS']['TABELA'][$tabelas['ACETABELA']]['EXCLUSAO'] = $tabelas['ACEEXCLUSAO'];
		$_SESSION['SA_ACESSOS']['TABELA'][$tabelas['ACETABELA']]['EXPORTAR'] = $tabelas['ACEEXPORTAR'];
	endforeach;
}
