<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateAgenda($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_AgendaTela($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];	
}

if ($_GET['method'] == 'list') {
    unset($_GET['list']);
    echo json_encode(listAgendaCalendario($_GET));
}
if ($_GET['method'] == 'delete') {
    echo json_encode(deleteAgenda($_GET['vIAGECODIGO']));
}
if ($_POST['hdn_metodo_insert'] == 'insertAgenda') {
	$_POST['vSAGEDATAFINAL'] = $_POST['vSAGEDATAINICIO'];
    echo json_encode(insertUpdateAgenda($_POST, 'N'));
}

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_AgendaCalendario')
	fill_AgendaCalendario($_GET['AGECODIGO'], $_GET['formatoRetorno']);

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

function comboAgendaHome($pSTIPO)
{
	$cod_usuario = filter_var($_SESSION['SI_USUCODIGO'], FILTER_SANITIZE_NUMBER_INT);
	$sql = 'SELECT
				a.AGECODIGO,
				a.AGEDATAINICIO,
				a.AGEDATAFINAL,
				a.AGEHORAINICIO,
				a.AGEHORAFINAL,
				a.AGEDESCRICAO,
				a.CLICODIGO,
				t.ATINOME,
				cli.CLINOMEFANTASIA AS CLINOME,
				u.USUNOME AS RESPONSAVEL,
				u2.USUNOME AS AGENDOU
			FROM
				AGENDA a
			LEFT JOIN
                ATIVIDADES t
            ON
                t.ATICODIGO = a.AGETIPO
			LEFT JOIN CLIENTES cli ON cli.CLICODIGO = a.CLICODIGO	
			LEFT JOIN USUARIOS u ON u.USUCODIGO = a.AGERESPONSAVEL
			LEFT JOIN USUARIOS u2 ON u2.USUCODIGO = a.AGEUSU_INC
			WHERE
				a.AGERESPONSAVEL = ? AND
				a.AGESTATUS = "S" AND
				a.AGECONCLUIDO = "N" ';
		if ($pSTIPO == 'P') {
			$sql .= '	AND a.AGEDATAINICIO < CURDATE() ';
			$sql .= ' ORDER BY	a.AGEDATAINICIO DESC';	
		}	
		if ($pSTIPO == 'H') {
			$sql .= '	AND (a.AGEDATAINICIO >= CURDATE() AND a.AGEDATAINICIO <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)) ';
			$sql .= ' ORDER BY	a.AGEDATAINICIO ASC';	
		}	
		
	$query = consultaComposta([
		'query' => $sql,
		'parametros' => [
			[$cod_usuario, PDO::PARAM_INT],
		],
	]);

	return $query['dados'];
}

function listAgendaCalendario($dados)
{
    $cod_usuario = filter_var($_SESSION['SI_USUCODIGO'], FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT
                a.*,
                t.TABDESCRICAO,
                t.TABCOR,
                cli.CLINOMEFANTASIA AS CLINOME,
                u.USUNOME,
                m.MENTITULO,
                m.MENARQUIVOCAD
            FROM
                AGENDA a
                LEFT JOIN CLIENTES cli ON cli.CLICODIGO = a.CLICODIGO
                LEFT JOIN TABELAS t ON t.TABCODIGO = a.AGETIPO
                LEFT JOIN USUARIOS u ON u.USUCODIGO = a.AGERESPONSAVEL
                LEFT JOIN MENUS m ON m.MENCODIGO = a.MENCODIGO
            WHERE
                a.AGESTATUS = 'S'";

    if (verificarVazio($dados['start']))
        $sql .= " AND a.AGEDATAINICIO >= '".$dados['start']."'";

    if (verificarVazio($dados['end']))
        $sql .= " AND a.AGEDATAFINAL <= '".$dados['end']."'";
	/*
    if (!(verificarParametro($cod_usuario, 'COORDENADOR') > 0)) {
        $sql .= " AND a.AGERESPONSAVEL = {$cod_usuario}";
    } else {
        if (verificarVazio($dados['consultor'])) {
            $sql .= " AND a.AGERESPONSAVEL = ".$dados['consultor'];
        } else {
            $sql .= " AND a.AGERESPONSAVEL = ".$cod_usuario;
        }
        if (verificarVazio($dados['vinculo'])) {
            $sql .= " AND a.MENCODIGO = ".$dados['vinculo'];
        }
    }*/

    $Sql       = stripcslashes($sql);
    $vConexao  = sql_conectar_banco();
    $resultSet = sql_executa(vGBancoSite, $vConexao,$sql,false);
    $events    = array();

    while($row = sql_retorno_lista($resultSet)) {
        $cor   = ($row['AGECONCLUIDO'] == 'S') ? '#167F39' : '#'.$row['TABCOR'];			
        $event = array(
            'id'            => $row['AGECODIGO'],
            'title'         => $row['AGETITULO'],
            'description'   => $row['AGEDESCRICAO'],
            'start'         => $row['AGEDATAINICIO'].' '.$row['AGEHORAINICIO'],
            'end'           => $row['AGEDATAFINAL'].' '.$row['AGEHORAFINAL'],
            'url'           => '',
            'assignee'      => $row['USUNOME'],
            'assignee_id'   => $row['AGERESPONSAVEL'],
            'clientName'    => $row['CLINOME'],
            'clientId'      => $row['CLICODIGO'],
            'type'          => $row['TABDESCRICAO'],
            'type_id'       => $row['AGETIPO'],
            'menuName'      => $row['MENARQUIVOCAD'],
            'menuTitulo'    => $row['MENTITULO'],
            'link_id'       => $row['AGEVINCULO'],
            'color'         => isColor($cor) ? $cor : '#6B6B6B',
            'concluido'     => $row['AGECONCLUIDO'],
            'enviar_email'  => $row['AGEENVIAREMAIL'],
        );

        array_push($events, $event);
    }

    return array_merge(
        $events
    );
}

function listAgenda($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND a.AGESTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND a.AGESTATUS = 'N' ";
	}else
		$where .= "AND a.AGESTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND a.AGEDATAINICIO >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND a.AGEDATAFINAL <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND cli.IDSIGLA = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND cli.CLINOMEFANTASIA LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND cli.CLICNPJ = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vIAGERESPONSAVEL']))
		$where .= 'AND a.AGERESPONSAVEL = ? ';
	
	$sql = 'SELECT
				a.*,
                t.TABDESCRICAO,
                t.TABCOR,
				CONCAT(a.AGEHORAINICIO, " - ", a.AGEHORAFINAL) AS HORAS,                           
				CONCAT("<a href=\'../cadastro/cadClientes.php?oid=", a.CLICODIGO, "&method=update\' target=\'_blank\'>", cli.CLINOMEFANTASIA, "</a>") AS CLINOME,					
                u.USUNOME AS RESPONSAVEL,
				u2.USUNOME AS AGENDOU,
                m.MENTITULO,
                m.MENARQUIVOCAD,
				t4.ATINOME 
            FROM
                AGENDA a
                LEFT JOIN CLIENTES cli ON cli.CLICODIGO = a.CLICODIGO
                LEFT JOIN TABELAS t ON t.TABCODIGO = a.AGETIPO
                LEFT JOIN USUARIOS u ON u.USUCODIGO = a.AGERESPONSAVEL
				LEFT JOIN USUARIOS u2 ON u2.USUCODIGO = a.AGEUSU_INC
                LEFT JOIN MENUS m ON m.MENCODIGO = a.MENCODIGO
				LEFT JOIN ATIVIDADES t4 ON t4.ATICODIGO = a.AGETIPO	
			WHERE
				1 = 1
				'.	$where;					
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vIAGERESPONSAVEL']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIAGERESPONSAVEL'], PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);

	return $result;
}

function insertUpdateAgenda($dados, $pSMsg = 'N'){
	
    $dadosBanco = array(
        'tabela'  => 'AGENDA',
        'prefixo' => 'AGE',
        'fields'  => $dados,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N' 
        );
    $id = insertUpdate($dadosBanco);
	/*
    if ($id) {
        if ($dados['vSAGEENVIAREMAIL'] != 'N') {
            enviarEmailAgendamento($dados);
        }
    }*/
    return $id;
}

function enviarEmailAgendamento($dados)
{
    $recipients = array();
    $SqlMain = "SELECT
						c.CLINOME,
						c.CLIEMAIL,
						u.USUEMAIL,
						u.USUNOME,	
						a.CLICODIGO						
					FROM
						AGENDA a
					LEFT JOIN USUARIOS u ON	u.USUCODIGO = a.AGERESPONSAVEL
					LEFT JOIN CLIENTES c ON	c.CLICODIGO = a.CLICODIGO	
					WHERE
						a.AGECODIGO = ".$vISOECODIGO;
					
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$recipients = array();
	while ($regemails = sql_retorno_lista($resultSet)){
		$vICLICODIGO = $regemails['CLICODIGO'];
		$vSUSUEMAIL  = $regemails['USUEMAIL'];
		$vSUSUNOME   = $regemails['USUNOME'];
		$vSCLINOME   = $regemails['CLINOME'];
		
		$vISOECODIGO = str_pad($regemails['SOECODIGO'], 5, '0', STR_PAD_LEFT);
		$data_atual  = formatar_data_hora($regemails['SOEDATA_INC']);
		$nomeProduto = $regemails['PRODUTO'];
		$quantidade = $regemails['SOEQUANTIDADE1'];
		$nomeProduto2 = $regemails['PRODUTO2'];
		$quantidade2 = $regemails['SOEQUANTIDADE2'];
		$nomeProduto3 = $regemails['PRODUTO3'];
		$quantidade3 = $regemails['SOEQUANTIDADE3'];
		$nomeProduto4 = $regemails['PRODUTO4'];
		$quantidade4 = $regemails['SOEQUANTIDADE4'];
		$nomeProduto5 = $regemails['PRODUTO5'];
		$quantidade5 = $regemails['SOEQUANTIDADE5'];
		$nomeUsuario = $regemails['SOLICITANTE'];
		$usuarioDepartamento = $regemails['DEPARTAMENTO'];		
		$recipients[] = $regemails['USUEMAIL'];
	}
	
	if ($vICLICODIGO > 0){
		include_once __DIR__.'/../../cadastro/transaction/transactionEnderecos.php';	
		$vRENDERECOINPI = fill_Enderecos($vICLICODIGO, 426);
	}
    $message = 'À '.$vSCLINOME.',
                <br><br>
                Prezado '.$cliente['CLICONTATO'].',
                <br><br>
                Conforme contato telefônico, segue confirmação da visita de nosso consultor (a) '.$vSUSUNOME.', que se encontra em cópia neste e-mail.
                <br><br>
                Cito a data: '.$dados['vDAGEDATAINICIO'].' as '.$dados['vSAGEHORAINICIO'].' hrs
                <br><br>
                Endereço:'.$vRENDERECOINPI['TLODESCRICAO'].' '.$vRENDERECOINPI['ENDLOGRADOURO'].', '.$vRENDERECOINPI['ENDNROLOGRADOURO'].' - '.$vRENDERECOINPI['ENDCOMPLEMENTO'].', '.$vRENDERECOINPI['ENDBAIRRO'].' - '.$vRENDERECOINPI['ENDCEP'].'<br> 
                Cidade: '.$vRENDERECOINPI['CIDDESCRICAO'].' - '.$vRENDERECOINPI['ESTSIGLA'].'
                <br><br>
                Qualquer inconsistência, por favor retorne este e-mail.
                <br><br>
                Agradecemos a sua disponibilidade,
                <br><br>
                Atenciosamente,
                <br>
                '.$_SESSION['SS_NOME_USUARIO'];

    if (in_array($dados['vSAGEENVIAREMAIL'], ['R', 'A'])) { //Responsável ou ambos
        $recipients[] = $vSUSUEMAIL;
    }

    if (in_array($dados['vSAGEENVIAREMAIL'], ['C', 'A'])) { //Cliente ou ambos
        $recipients[] = $cliente['CLIEMAIL'];
    }
	
    enviarEmail($recipients, 'Confirmação de Visita Marpa', $message, 'N');
}

function fill_Agenda($codigo)
{
    $fill = fillUnico(array(
        'tabela'  => 'AGENDA',
        'prefixo' => 'AGE',
        'codigo'  => $codigo
    ));

    $fill['AGEDATAFINAL']  = formatar_data($fill['AGEDATAFINAL']);
    $fill['AGEDATAINICIO'] = formatar_data($fill['AGEDATAINICIO']);

    include '../transaction/transactionCliente.php';
    $cliente = fill_cliente($fill['CLICODIGO']);

    $fill['CLIENTE'] = $cliente['CLINOME'];

    return $fill;
}

function deleteAgenda($vIAGECODIGO)
{
    return insertUpdateAgenda(
        array(
            'vIAGECODIGO' => $vIAGECODIGO,
            'vSAGESTATUS' => 'N'
        ),
        'N'
    );
}

function fill_AgendaCalendario($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                a.*,  CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE
	            FROM
                    AGENDA a
				LEFT JOIN CLIENTES c ON c.CLICODIGO = a.CLICODIGO	
				WHERE
					a.AGESTATUS = 'S'
				AND
                    a.AGECODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function fill_AgendaTela($pOid){
	$SqlMain = "SELECT
	                a.*,  CONCAT(c.CLISEQUENCIAL,' - ', c.CLINOME) AS CLIENTE
	            FROM
                    AGENDA a
				LEFT JOIN CLIENTES c ON c.CLICODIGO = a.CLICODIGO	
				WHERE
					a.AGESTATUS = 'S'
				AND
                    a.AGECODIGO = ".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}