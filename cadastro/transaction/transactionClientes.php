<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert") || ($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateClientes($_POST, 'N');
    sweetAlert('', '', 'S', 'cadClientes.php?method=update&oid=' . $vIOid, 'S');
    return;
} elseif (($_GET["method"] == "consultar") || ($_GET["method"] == "update")) {
    $vROBJETO = fill_Clientes($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'STATUS'];

    // tipo parceiro
    if ($vIOid > 0) {
        foreach (buscaClientexTipoParceiro($vIOid) as $tabelas) :
            $arrayPreMold[] = $tabelas['TABCODIGO'];
        endforeach;
        $contArray = count($arrayPreMold);
    }

    //incluir contatos
    include_once 'transactionContatos.php';
    $vRCONTATO = fill_Contatos($vIOid, 'S');

    //incluir endereços
    include_once 'transactionEnderecos.php';
    $vRENDERECO = getEnderecosByCodigoCliente($vIOid);
}

if (isset($_GET['method'])) {
    switch ($_GET['method']) {
        case 'enviarEmailInfoSistema':
            echo json_encode(enviarEmailInfoSistema($_POST['vACLICODIGO']));
            break;
    }
}

if ($_GET['hdn_metodo_fill'] == 'fill_Clientes') {
    fill_Clientes($_GET['vICLICODIGO'], $_GET['formatoRetorno']);
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

function listClientes($_POSTDADOS)
{
    $where = '';
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])) {
        $where .= 'AND (C.CLIRAZAOSOCIAL LIKE ? OR C.CLINOMEFANTASIA LIKE ?) ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])) {
        $where .= 'AND C.CLICNPJ = ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
        $where .= 'AND C.CLIDATA_INC >= ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
        $where .= 'AND C.CLIDATA_INC <= ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLICONTATO'])) {
        $where .= 'AND C.CLICONTATO LIKE ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLIEMAIL'])) {
        $where .= 'AND C.CLIEMAIL LIKE ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])) {
        if ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S') {
            $where .= "AND C.CLISTATUS = 'S' ";
        } elseif ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N') {
            $where .= "AND C.CLISTATUS = 'N' ";
        }
    } else {
        $where .= "AND C.CLISTATUS = 'S' ";
    }
    $sql = "SELECT
				C.CLICODIGO, C.CLISEQUENCIAL, C.CLINOMEFANTASIA, C.CLIRAZAOSOCIAL,
				CONCAT(
					(CASE WHEN C.CLITIPOCLIENTE = 'J' THEN
					C.CLICNPJ
					ELSE
					C.CLICPF
					END)
					) as CNPJCPF,
				C.CLIDATA_INC, C.CLIDATA_ALT, C.CLISTATUS
			FROM
				CLIENTES C
			WHERE
				1 = 1
			" .    $where    . "
			LIMIT 250	";
    $arrayQuery = array(
        'query' => $sql,
        'parametros' => array()
    );
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])) {
        $pesquisa = $_POSTDADOS['FILTROS']['vSCLINOME'];
        $arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
        $arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])) {
        $arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLICONTATO'])) {
        $pesquisa = $_POSTDADOS['FILTROS']['vSCLICONTATO'];
        $arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vSCLIEMAIL'])) {
        $pesquisa = $_POSTDADOS['FILTROS']['vSCLIEMAIL'];
        $arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
        $varIni = $_POSTDADOS['FILTROS']['vDDataInicio'] . " 00:00:00";
        $arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
        $varFim = $_POSTDADOS['FILTROS']['vDDataFim'] . " 23:59:59";
        $arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
    }
    $result = consultaComposta($arrayQuery);
    return $result;
}

function insertUpdateClientes($_POSTCLI, $pSMsg = 'N')
{
    if ($_POSTCLI['vSCLITIPOCLIENTE'] == 'F') {
        $_POSTCLI['vSCLIRAZAOSOCIAL'] = $_POSTCLI['vHCLINOME'];
        $_POSTCLI['vSCLINOMEFANTASIA'] = $_POSTCLI['vHCLINOME'];
    }
    $dadosBanco = array(
        'tabela'  => 'CLIENTES',
        'prefixo' => 'CLI',
        'fields'  => $_POSTCLI,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N'
    );
    $id = insertUpdate($dadosBanco);

    //incluir endereços
    include_once 'transactionEnderecos.php';
    $_POSTCLI['vICLICODIGO'] = $id;
    //Principal
    $_POSTCLI['vHTABCODIGO'] = 426;
    insertUpdateEnderecos($_POSTCLI, 'N');

    //incluir contatos
    include_once 'transactionContatos.php';
    //Principal
    $_POSTCLI['vHTABCODIGO'] = 26933;
    insertUpdateContatos($_POSTCLI, 'N');

    // tipo parceiro
    if ($_POSTCLI['vHTIPOPARCEIRO']) {
        foreach ($_POSTCLI['vHTIPOPARCEIRO'] as $result) {
            if (testaNoBanco($result, $id) == 0) {
                $dadosPreMoldados = array(
                    'vICLICODIGO'   => $id,
                    'vITABCODIGO'   => $result
                );
                insertUpdateClientexTipoParceiro($dadosPreMoldados, 'N');
            }
        }
        foreach (buscaClientexTipoParceiro($id) as $dadosCad) {
            if (in_array($dadosCad['TABCODIGO'], $_POSTCLI['vHTIPOPARCEIRO'])) {
            } else {
                $config_excluir = array(
                    "tabela" => Encriptar("CLIENTESXTIPOPARCEIRO", 'crud'),
                    "prefixo" => "CXT",
                    "status" => "N",
                    "ids" => $dadosCad['CXTCODIGO'],
                    "mensagem" => "N"
                );
                excluirAtivarRegistros($config_excluir);
            }
        }
    }

    return $id;
}

function fill_Clientes($pOid, $formatoRetorno = 'array')
{
    $SqlMain = 'SELECT c.*
				 From CLIENTES c
				 Where c.CLICODIGO = ' . $pOid;
    $vConexao = sql_conectar_banco(vGBancoSite);
    $resultSet = sql_executa(vGBancoSite, $vConexao, $SqlMain);
    $registro = sql_retorno_lista($resultSet);
    if ($formatoRetorno == 'array') {
        return $registro !== null ? $registro : "N";
    } elseif ($formatoRetorno == 'json') {
        echo json_encode($registro);
    }
    return $registro !== null ? $registro : "N";
}

function buscaClientexTipoParceiro($vICLICODIGO)
{
    $sql = "SELECT
			 TABCODIGO, CXTCODIGO
			FROM
			 	CLIENTESXTIPOPARCEIRO
			WHERE
				CLICODIGO = ?
			AND
				CXTSTATUS = 'S' ";
    $arrayQuery = array(
        'query' => $sql,
        'parametros' => array(
            array($vICLICODIGO, PDO::PARAM_INT)
        )
    );
    $list = consultaComposta($arrayQuery);
    return    $list['dados'];
}

function testaNoBanco($idEmp, $idPreMold)
{
    $sql = "SELECT
			 *
			FROM
			 	CLIENTESXTIPOPARCEIRO
			WHERE
				CLICODIGO = ?
			AND
				TABCODIGO = ?
			AND
				CXTSTATUS = 'S' ";
    $arrayQuery = array(
        'query' => $sql,
        'parametros' => array(
            array($idEmp, PDO::PARAM_INT),
            array($idPreMold, PDO::PARAM_INT)
        )
    );
    $list = consultaComposta($arrayQuery);
    return    $list['quantidadeRegistros'];
}


function insertUpdateClientexTipoParceiro($_POSTDADOS, $pSMsg = 'N')
{
    $dadosBanco = array(
        'tabela'  => 'CLIENTESXTIPOPARCEIRO',
        'prefixo' => 'CXT',
        'fields'  => $_POSTDADOS,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N'
    );
    $id = insertUpdate($dadosBanco);
    return $id;
}

function enviarEmailInfoSistema($vACLICODIGO)
{
    $usuarios = consultaComposta([
        'query' => 'SELECT
        			   CLICODIGO,
                       CLINOMEFANTASIA
                    FROM
                        CLIENTES
                    WHERE
                        CLICODIGO IN (' . implode(',', $vACLICODIGO) . ')',
    ]);


    $vSURLAcesso = "http://sites-gestao-srv.teraware.net.br/formulario";

    foreach ($usuarios['dados'] as $usuario) {
        $Assunto = "INFORMAÇÕES PRELIMINARES PARA INÍCIO DAS ATIVIDADES";
        $Mensagem = "<p>Prezado " . $usuario['CLINOMEFANTASIA'] . "<br /><br />
						Informamos que para início das atividades solicitamos que preencha o formulário abaixo.<br />
						Para acessar o sistema siga os seguintes passos:
					</p>
					<ul type='circle'>
						<li>Acesse o endereço:<br/>
						- Link: <a href='" . $vSURLAcesso . "' alt='INFORMAÇÕES PRELIMINARES PARA INÍCIO DAS ATIVIDADES'>" . $vSURLAcesso . "</a><br/>
						</li>
					</ul>
					<br />
					<p>Para dúvidas envie e-mail para nosso suporte técnico, para gestao@gestao.srv.br</p>
					<p>Ou pelo telefone: (51) 3541-3355 ou Whats (51) 9 8443-2097</p>
					<p>Gestão Inteligência em Administração Pública</p>
					";

        $pAEmails = ['godinho@teraware.com.br'];

        ob_start();
        $send[$usuario['CLICODIGO']] = enviarEmail($pAEmails, $Assunto, $Mensagem);
        ob_end_clean();
    }


    $fails = array_filter($send, function ($success) {
        return !$success;
    });

    if (count($fails)) {
        $response = [
            'success' => false,
            'msg'     => 'Não foi possível enviar E-mail para os clientes: ' . implode(', ', array_keys($fails)),
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
