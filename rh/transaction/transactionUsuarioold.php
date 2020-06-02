<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_POST['hdn_metodo_search'])){
	switch ($_POST['hdn_metodo_search']) {
		case 'searchUSU':
			searchUsuario($_POST);
			break;
		case 'searchRAM':
			searchUsuarioRamal($_POST);
			break;
	}
}

if (isset($_GET['method'])) {
	switch ($_GET['method']) {
		case 'getusername':
			echo json_encode(verificarUsername($_GET['username']));
			break;
		case 'enviarEmailSenha':
			echo json_encode(enviarEmailAcessoSistema($_POST['vAUSUCODIGO']));
			break;
	}
}

if ($_POST['method'] === 'setusername')
	echo json_encode(gerarUsername($_POST['username']));

if (isset($_POST["method"]) && $_POST["method"] == 'excluirUSU') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOS", 'crud'),
		"prefixo" => "USU",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S"
	);

	echo excluirAtivarRegistros($config_excluir);
}

if (isset($_POST["method"]) && $_POST["method"] == 'alterarSenha'){
	$sqlPesquisaSenha = "SELECT
							USUSENHA
						FROM
							USUARIOS
						WHERE
							USUCODIGO = ".$_SESSION['SI_USUCODIGO'];

	$vConexao = sql_conectar_banco();
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$sqlPesquisaSenha,FALSE);

	$isUsuarioValido = false;

	while($row = sql_retorno_lista($RS_POST)) {
		if( Desencriptar($row['USUSENHA'], 'TWFlex') == $_POST['pSSenhaAntiga'])
			$isUsuarioValido = true;
	}

	if(!$isUsuarioValido) {
		echo "Senha Inválida";
	} else {
		$vISENHAALT = Encriptar($_POST['pISENHAALT'], 'TWFlex');
		$sqlUpdateSenha = " UPDATE
								USUARIOS
							SET
								USUSENHA = '" . $vISENHAALT . "'
							WHERE
								USUCODIGO = '" . $_SESSION['SI_USUCODIGO'] . "'
							";
		sql_executa(vGBancoSite, $vConexao,$sqlUpdateSenha,FALSE);
		echo "Senha alterada com sucesso!";
	}
}

function searchUsuario($dados)
{
	include_once '../grids/gridPadrao.php';

	$Sql = "SELECT
				u.*,
				t.TABDESCRICAO AS DEPARTAMENTO,
				t2.TABDESCRICAO AS CARGO,
				(SELECT t3.TABDESCRICAO FROM TABELAS t3 WHERE t3.TABCODIGO = u.USUVINCULO) AS VINCULO
			FROM USUARIOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			LEFT JOIN TABELAS t2 ON t2.TABCODIGO = u.TABCARGO
			WHERE 1 = 1";

	if (verificarVazio($dados['vSUsuSituacao']) && ($dados['vSUsuSituacao'] != "T")) {
		if($dados['vSUsuSituacao'] == "A") {
			$Sql .=" AND USUDATAADMISSAO IS NOT NULL AND USUDATADEMISSAO IS NULL ";
		}
		if($dados['vSUsuSituacao'] == "D") {
			$Sql .=" AND USUDATADEMISSAO IS NOT NULL ";
		}
	}

	if(verificarVazio($dados['vSAniversariante']) && ($dados['vSAniversariante'] != "T"))
		$Sql .=" and MONTH(USUDATAADMISSAO) = '".$dados['vSAniversariante']."' ";
	if(verificarVazio($dados['vSAniversarianteIdade']) && ($dados['vSAniversarianteIdade'] != "T"))
		$Sql .=" and MONTH(USUDATA_NASCIMENTO) = '".$dados['vSAniversarianteIdade']."' ";
	if(verificarVazio($dados['vITABDEPARTAMENTO']))
		$Sql .=" and TABDEPARTAMENTO = ".$dados['vITABDEPARTAMENTO'];

	if (verificarVazio($dados['vIEMPCODIGO'])) {
		$Sql .=" AND EMPCODIGO = ".$dados['vIEMPCODIGO'];
	}

	if (verificarVazio($dados['vSUSUNOME'])) {
		$Sql .=" AND USUNOME LIKE '%".$dados['vSUSUNOME']."%'";
	}

	if (verificarVazio($dados['vSUSUCPF'])) {
		$Sql .=" AND USUCPF LIKE '%".$dados['vSUSUCPF']."%'";
	}

	if (verificarVazio($dados['vSUSUEMAIL'])) {
		$Sql .=" AND USUEMAIL LIKE '%".$dados['vSUSUEMAIL']."%'";
	}

	if (verificarVazio($dados['vITABDEPARTAMENTO'])) {
		$Sql .=" AND TABDEPARTAMENTO = ".$dados['vITABDEPARTAMENTO'];
	}

	if (verificarVazio($dados['vSStatus']) && ($dados['vSStatus'] != "T")) {
		$Sql .=" AND USUSTATUS = '".$dados['vSStatus']."'";
	}

	if (verificarVazio($dados['vIUSUVINCULO'])) {
		$Sql .=" AND USUVINCULO = ".$dados['vIUSUVINCULO'];
	}

	$Sql .= sql_add_datas_between('USUDATA_INC', $dados['vSDataCadastroInicial'], $dados['vSDataCadastroFinal']);
	$Sql .= sql_add_datas_between('USUDATA_ALT', $dados['vSDataAlteracaoInicial'], $dados['vSDataAlteracaoFinal']);
	$Sql .= sql_add_datas_between('USUDATAADMISSAO', $dados['vDUSUDATAADMISSAOINICIAL'], $dados['vDUSUDATAADMISSAOFINAL']);
	$Sql .= sql_add_datas_between('USUDATADEMISSAO', $dados['vDUSUDATADEMISSAOINICIAL'], $dados['vDUSUDATADEMISSAOFINAL']);

	if(verificarVazio($dados['hdn_FRAMEWORK_ORDENAR'])) {
		$Sql .= " ORDER BY ".$dados['hdn_FRAMEWORK_ORDENAR']." ".$dados['hdn_ordem_grid'];
	} else {
		$Sql .= " ORDER BY 1";
	}

	$Sql = stripcslashes($Sql);
	$vConexao = sql_conectar_banco();
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);

	$vAConfig['vIIDACESSO'] = 1898;
	$vAConfig['vSTitulo'] = 'Usuario';
	$vAConfig['vSPrefixo'] = 'USU';


	$vAConfig['vAConfFuncoes'] = array(
        array(
            "title"         => "Enviar E-mail Senha",
            "id_element"    => "vEnviarEmail",
            "function"      => "enviarEmailSenha",
            "classHref"     => "btnEmailMini",
            "element"       => "checkbox",
            "position"      => "true",
            "classPosition" => "btnEmailMini",
        ),
    );

	$vAConfig['vATitulos'] = array('Código', 'Nome', 'CPF', 'Vínculo', 'Data Admissão', 'Data Demissão', 'Departamento', 'Cargo', 'E-mail', 'Data Inclusão', 'Data Alteração', 'Ativo');

	$vAConfig['vACampos'] = array('USUSEQUENCIAL', 'USUNOME', 'USUCPF', 'VINCULO', 'USUDATAADMISSAO', 'USUDATADEMISSAO', 'DEPARTAMENTO', 'CARGO', 'USUEMAIL', 'USUDATA_INC', 'USUDATA_ALT', 'USUSTATUS');

	$vAConfig['vATipos'] = array('sequencial', 'varchar', 'varchar', 'varchar', 'date', 'date', 'varchar', 'varchar', 'varchar', 'datetime', 'datetime', 'simNao');

	if ($dados['hdn_metodo_search'] == 'searchUSU') {
		montarGridPadrao($RS_POST, $vAConfig);
	}

	if ($dados['hdn_metodo_search'] == 'exportarGridUSU') {
		exportarTXTGridPadrao($RS_POST, $vAConfig);
	}

	sql_fechar_conexao_banco($vConexao);
}

function insertUpdateUsuario($dados, $pSMsg = 'N')
{
	$cod_usuario = filter_var($dados['vIUSUCODIGO'], FILTER_SANITIZE_NUMBER_INT);

	if (empty($dados['vIUSUCODIGO'])) {
		$dados['vIUSUSEQUENCIAL'] = proxima_Sequencial('USUARIOS');
	}

	if(verificarVazio($dados['vSUSUSENHA'])) {
		$dados['vSUSUSENHA'] = Encriptar($dados['vSUSUSENHA'], 'TWFlex');
	}

	$dadosBanco = array(
		'tabela'  => 'USUARIOS',
		'prefixo' => 'USU',
		'fields'  => $dados,
		'msg'     => 'N',
		'url'     => 'cadUsuario.php',
		'debug'   => 'S'
	);

	$id = insertUpdate($dadosBanco);

	if (empty($dados['vIUSUCODIGO'])) {
		insert_update_UsuariosxEmpresaUsuaria('', $_SESSION['SI_USU_EMPRESA'], 'S', $id, 'N');
	}

	sweetAlert('', '', 'S', $dadosBanco['url'].'?method=update&oid='.$id, $pSMsg);

}

function fill_Usuario($pOid){
	$SqlMain = 'Select *
	From USUARIOS
	Where USUCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function searchUsuarioRamal($_POSTDADOS)	{
	include_once('../grids/gridPadrao.php');

	$Sql = "SELECT u.USUNOME, u.USUEMAIL, u.USURAMAL, t.TABDESCRICAO AS SETOR
			FROM USUARIOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			WHERE USUSTATUS = 'S' ";
	if(verificarVazio($_POSTDADOS['vIEMPCODIGO']) && ($_POSTDADOS['vIEMPCODIGO'] != "T"))
		$Sql .=" AND EMPCODIGO = ".$_POSTDADOS['vIEMPCODIGO'];
	else
		$Sql .=" AND EMPCODIGO in (". $_SESSION['SA_EMPRESAS'] .")";
	if(verificarVazio($_POSTDADOS['vSUSUNOME']))
		$Sql .=" AND USUNOME like '%".$_POSTDADOS['vSUSUNOME']."%'";
	if(verificarVazio($_POSTDADOS['vSUSUEMAIL']))
		$Sql .=" AND USUEMAIL like '%".$_POSTDADOS['vSUSUEMAIL']."%'";
	if(verificarVazio($_POSTDADOS['vSUSURAMAL']))
		$Sql .=" AND USURAMAL = '".$_POSTDADOS['vSUSURAMAL']."'";
	if(verificarVazio($_POSTDADOS['vITABDEPARTAMENTO']) && ($_POSTDADOS['vITABDEPARTAMENTO'] != "T"))
		$Sql .=" and TABDEPARTAMENTO = '".$_POSTDADOS['vITABDEPARTAMENTO']."'";
	if(verificarVazio($_POSTDADOS['hdn_FRAMEWORK_ORDENAR']))
		$Sql .= " order by ".$_POSTDADOS['hdn_FRAMEWORK_ORDENAR']." ".$_POSTDADOS['hdn_ordem_grid'];
	else
		$Sql .= " order by 1";

	$Sql = stripcslashes($Sql);
	$vConexao = sql_conectar_banco();
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);

	$vAConfig['vIIDACESSO'] = 1898;
	$vAConfig['bDesabilitar'] = 'S';
	$vAConfig['vSTitulo'] = 'Usuario';
	$vAConfig['vSPrefixo'] = 'USU';

	$vAConfig['vATitulos'] = array('Nome', 'Departamento/Setor', 'E-mail', 'Ramal');
	$vAConfig['vACampos'] = array('USUNOME', 'SETOR', 'USUEMAIL', 'USURAMAL');
	$vAConfig['vATipos'] = array('varchar', 'varchar', 'varchar', 'varchar');

	if ($_POSTDADOS['hdn_metodo_search'] == 'searchRAM')
		montarGridPadrao($RS_POST, $vAConfig);
	elseif ($_POSTDADOS['hdn_metodo_search'] == 'exportarGridRAM')
		exportarTXTGridPadrao($RS_POST, $vAConfig);
	sql_fechar_conexao_banco($vConexao);
}

function comboUsuarios($departamento = null, $role = null)
{
	$getAll = true;
	if (!is_null($role)) {
		if (!verificarParametro($_SESSION['SI_USUCODIGO'], $role)) {
			$getAll = false;
		}
	}

	$baseSQL = "SELECT
					USUCODIGO,
					USUNOME
				FROM
					USUARIOS
				WHERE
					USUSTATUS = 'S'
				AND
					USUDATADEMISSAO IS NULL
				";
	if (!is_null($departamento)) {
		$vIDEPARTAMENTO = getParametroValor($departamento);
		$baseSQL .= "AND
					TABDEPARTAMENTO = ?";
	}

	$baseSQL .= " ORDER BY
					USUNOME ASC";

	if ($getAll) {

		$query = array();
		$query['query']      = $baseSQL;
		$query['parametros'] = array();

		if (!is_null($departamento)) {
			$query['parametros'] = array(
				array($vIDEPARTAMENTO, PDO::PARAM_INT),
			);
		}

		$data = consultaComposta($query);

		$response = $data['dados'];
	} else {

		$response = array(array(
			'USUCODIGO' => $_SESSION['SI_USUCODIGO'],
			'USUNOME'   => $_SESSION['SS_NOME_USUARIO'],
		));
	}

	if (count($response) > 1) {
		array_unshift($response, array('USUCODIGO' => '', 'USUNOME' => '-----'));
	}

	return $response;
}

function pertenceDepartamento($departamento, $user = false)
{
	if (!$user) {
		$user = $_SESSION['SI_USUCODIGO'];
	}

	$query = consultaComposta([
		'query' => "SELECT (
						SELECT
							TABDEPARTAMENTO
						FROM
							USUARIOS
						WHERE
							USUCODIGO = ?
					) = (
						SELECT
							PARDESCRICAO
						FROM
							PARAMETROS
						WHERE
		                    PARSTATUS = 'S' AND
							PARTIPO = ?) AS BELONGSDEPARTMENT",
		'parametros' => [
			[$user, PDO::PARAM_INT],
			[$departamento, PDO::PARAM_STR],
		]
	]);

	return $query['dados'][0]['BELONGSDEPARTMENT'];
}

function getConsultorByCodcliente($clicodigo)
{
	if (!empty($clicodigo)) {

		$consulta = consultaComposta(array(
			'query' => "SELECT
		                    u.USUNOME,
		                    u.USUCODIGO
						FROM
							USUARIOS u, CLIENTES c
						WHERE
							u.USUCODIGO = c.CLIRESPONSAVEL
						AND
							u.USUSTATUS = 'S'
			            AND
			            	u.USUDATADEMISSAO IS NULL
			            AND
			            	c.CLICODIGO = ?
						ORDER BY
							u.USUNOME",
			'parametros' => array(
					array($clicodigo, PDO::PARAM_INT),
				)
		));

		$response = $consulta['dados'];
	    $response = array_map(function($usuario){
	    	$usuario['USUNOME'] = utf8_encode($usuario['USUNOME']);
	    	return $usuario;
	    }, $response);

	    if ($consulta['quantidadeRegistros'] > 1) {
			array_unshift($response, array('USUCODIGO' => '', 'USUNOME' => '----------'));
		}

	} else {

		$response = array(array(
			'USUCODIGO' => '',
			'USUNOME'   => 'Nenhum consultor cadastrado para este cliente!',
		));

	}

	return $response;
}

function enviarEmailAcessoSistema($vAUSUCODIGO)
{
	$usuarios = consultaComposta([
        'query' => 'SELECT
        			   USUCODIGO,
                       USUSENHA,
                       USUNOME,
                       USULOGIN,
                       USUEMAIL
                    FROM
                        USUARIOS
                    WHERE
                        USUCODIGO IN ('.implode(',', $vAUSUCODIGO).')',
    ]);


	$vSURLAcessoInterno = "http://marpatributario.marpa.local/login.php";
	$vSURLAcessoExterno = "http://marpadatabase.ddns.net:8888/login.php";

	$url_manual_interno = "http://marpatributario.marpa.local/arquivos/manual.pdf";
	$url_manual_externo = "http://marpadatabase.ddns.net:8888/arquivos/manual.pdf";

	foreach ($usuarios['dados'] AS $usuario) {

		$Assunto = utf8_decode("Acesso ao Sistema de Gestão Marpa Tributário - ").$usuario['USUNOME'];
		$vSSenha = Desencriptar($usuario['USUSENHA'], 'TWFlex');
		$Mensagem = "<p>Prezado " .$usuario['USUNOME'] . "<br /><br />
						Informamos que você foi cadastrado no Sistema de Gestão Marpa Tributário<br />
						Para acessar o sistema siga os seguintes passos:
					</p>
					<ul type='circle'>
						<li>Acesse o endereço:<br/>
						- Interno: <a href='".$vSURLAcessoInterno."' alt='Sistema de Gestão Marpa Tributário'>".$vSURLAcessoInterno."</a><br/>
						- Fora da Empresa: <a href='".$vSURLAcessoExterno."' alt='Sistema de Gestão Marpa Tributário'>".$vSURLAcessoExterno."</a>
						</li>
						<li>Informe o seu login: <strong>".$usuario['USULOGIN']."</strong></li>
						<li>Informe a sua senha: <strong>".$vSSenha."</strong></li>
						<li>Clique no botão <strong>Entrar</strong> para realizar o logon no sistema.</li>
					</ul>
					<br />
					<p>Link do manual do sistema:<br>
						<ul type='circle'>
							<li>Interno: <a href='".$url_manual_interno."' alt='Manual do Sistema de Gestão Marpa Tributário'>".$url_manual_interno."</a>
							</li>
							<li>Fora da Empresa: <a href='".$url_manual_externo."' alt='Manual do Sistema de Gestão Marpa Tributário'>".$url_manual_externo."</a>
							</li>
						</ul>
					</p>
					<p>Para dúvidas envie e-mail para nosso suporte técnico, para sistema@marpa.com.br!</p>
					<p>Teraware Soluções em Software agradece.</p>
					";

		$pAEmails = ['sistema@marpa.com.br', $usuario['USUEMAIL']];

		ob_start();
		$send[$usuario['USUCODIGO']] = enviarEmail($pAEmails, $Assunto, $Mensagem);
		ob_end_clean();
	}


	$fails = array_filter($send, function($success) {
        return !$success;
    });

    if (count($fails)) {
        $response = [
            'success' => false,
            'msg'     => 'Não foi possível enviar E-mail para os contratos: '.implode(', ', array_keys($fails)),
        ];

        if (count($fails) != count($send)) {
            $response['msg'] .= '. Os demais foram enviados com sucesso!';
        }

        return $response;
    }

    return [
        'success' => true,
        'msg' => 'Todos os E-mails foram enviados com sucesso!',
    ];
}

function gerarUsername($nome)
{
	$nickname = converterTamanhoLetra(RemoverAcentos($nome));
	$nickname = explode(" ", $nickname);

	if (count($nickname) > 2) {
		return current($nickname).".".end($nickname);
	}

	return $nickname[0].".".$nickname[1];
}

function verificarUsername($username)
{
	$empcodigo = $_SESSION['SI_USU_EMPRESA'];

	$nick = consultaComposta(array(
		'query' => "SELECT USULOGIN FROM USUARIOS WHERE USULOGIN = ? AND EMPCODIGO = ?",
		'parametros' => array(
			array($username, PDO::PARAM_STR),
			array($empcodigo, PDO::PARAM_INT),
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
