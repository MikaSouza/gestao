<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if (isset($_POST['method'])) {
    switch ($_POST['method']) {
        case 'listar-contatos':
            echo json_encode(getContatosByCodigoCliente($_POST['clicodigo']));
            break;
        case 'enviarAcesso':
            echo json_encode(enviarEmailAcessoSistema($_POST['concodigos']));
            break;
        case 'incluir-contato':
            echo json_encode(insertUpdateContato($_POST, 'N'));
            break;
        case 'show-contato':
            echo json_encode(showContato((int) $_POST['concodigo']));
            break;
        case 'excluir-contato':
            excluirContato($_POST['concodigo']);
            break;
    }
}

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

function insertUpdateContato($parametros, $pSMsg = 'N')
{
    try {
        $dadosBanco = array(
            'tabela'  => 'CONTATOS',
            'prefixo' => 'CON',
            'fields'  => $parametros,
            'msg'     => $pSMsg,
            'url'     => '',
            'debug'   => 'N'
        );
        $id = insertUpdate($dadosBanco);
        $contato = "";

        if (!isset($parametros['vICONCODIGO'])) {
            $contato = getContato($id);
        }
        return array('success' => true, 'message' => 'Registro de inserido com sucesso!', 'registro' => $contato);
    } catch (Exception $e) {
        return array('success' => false, 'message' => 'Error: ' . $e->getMessage());
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
    require_once __DIR__.'/../../twcore/vendors/phpmailer/email.php';

    $contatos = getDadosAcesso($concodigos);

    // $emails = ['atendimento@teraware.com.br', 'gestao@gestao.srv.br', 'nathan@gestao.srv.br'];

    $url_acesso = "https://gestao.srv.br/loginPainel.php";
    $assunto = utf8_decode("Acesso ao Sistema da Empresa Gestão");

    $enviados = [];

    foreach ($contatos as $contato) {
        $dadosEmail = array(
            'titulo'        => $assunto,
            'descricao'     => "<p>Prezado(a) " .$contato['CONNOME'] . "<br /><br />
                                    Informamos que você foi cadastrado no Sistema da Empresa Gestão<br />
                                    Para acessar o sistema siga os seguintes passos:
                                </p>
                                <ul type='circle'>
                                    <li>Acesse o endereço:<br/>
                                    - <a href='".$url_acesso."'>".$url_acesso."</a><br/>
                                    </li>
                                    <li>Informe o seu e-mail: <strong>".$contato['CONEMAIL']."</strong></li>
                                    <li>Informe a sua senha: <strong>".$contato['CONSENHA']."</strong></li>
                                    <li>Clique no botão <strong>Entrar</strong> para realizar o logon no sistema.</li>
                                </ul>
                                <br />
                                ",
            'destinatarios' => array(
                'atendimento@teraware.com.br',
                'francisco.souza@teraware.com.br'
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
            'msg'     => 'Não foi possível enviar E-mail para os contatos: '.implode(', ', array_values($fails)),
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