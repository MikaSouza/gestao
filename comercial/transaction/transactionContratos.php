<?php

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateContratos($_POST, 'N');
    sweetAlert('', '', 'S', 'cadContratos.php?method=update&oid=' . $vIOid, 'S');
	return;
} elseif (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Contratos($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	
	// produtos
	if ($vIOid > 0) {
		foreach (buscaContratoxProdutosxServicos($vIOid) as $tabelas) :
			$arrayPreMold[] = $tabelas['PXSCODIGO'];
		endforeach;
		$contArray = count($arrayPreMold);
	}
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

function listContratos($_POSTDADOS)
{
    $where = '';
    if (verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])) {
        if ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S') {
            $where .= "AND e.CTRSTATUS = 'S' ";
        } elseif ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N') {
            $where .= "AND e.CTRSTATUS = 'N' ";
        }
    } else {
        $where .= "AND e.CTRSTATUS = 'S' ";
    }

    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
        $where .= 'AND e.CTRDATA_INC >= ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
        $where .= 'AND e.CTRDATA_INC <= ? ';
    }

    if (verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])) {
        $where .= 'AND C.CLISEQUENCIAL = ? ';
    }

    $sql = "SELECT
				e.*,
				t.CLINOMEFANTASIA AS CLIENTE,
				(SELECT
					GROUP_CONCAT(i.PXSNOME SEPARATOR '<br />')
					FROM
					CONTRATOSXPRODUTOSXSERVICOS CXT
					LEFT JOIN PRODUTOSXSERVICOS i ON i.PXSCODIGO = CXT.PXSCODIGO
					WHERE
					CXT.CXPSTATUS = 'S' AND
					CXT.CTRCODIGO = e.CTRCODIGO
				) AS TIPOCONTRATO,
                b2.TABDESCRICAO AS POSICAO,
                u.USUNOME AS CONSULTOR
			FROM CONTRATOS e
			LEFT JOIN TABELAS b2 ON b2.TABCODIGO = e.CTRPOSICAO
			LEFT JOIN USUARIOS u ON u.USUCODIGO = e.CTRVENDEDOR
			LEFT JOIN CLIENTES t ON t.CLICODIGO = e.CLICODIGO
			WHERE CTRSTATUS = 'S'
			ORDER BY 1 ";

    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array()
                );
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
        $varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
        $arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
        $varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
        $arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])) {
        $arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
    }

    $result = consultaComposta($arrayQuery);

    return $result;
}

function insertUpdateContratos($_POSTDADOS, $pSMsg = 'N')
{
	if ($_POSTDADOS['vICTRCODIGO'] == '')
		$_POSTDADOS['vSCTRNROCONTRATO'] = proxima_Sequencial('CONTRATOS', 'S');
    $dadosBanco = array(
        'tabela'  => 'CONTRATOS',
        'prefixo' => 'CTR',
        'fields'  => $_POSTDADOS,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N'
        );
    $id = insertUpdate($dadosBanco);
		
	// produtos
	if ($_POSTDADOS['vHPXSCODIGO']) {
		foreach ($_POSTDADOS['vHPXSCODIGO'] as $result) {			
			if (verificarContratoxProduto($result, $id) == 0) {
				$dadosProdutos = array(
					'vICTRCODIGO'   => $id,
					'vIPXSCODIGO'   => $result
				);
				insertUpdateContratoxProdutosxServicos($dadosProdutos, 'N');
			}
		}
		foreach (buscaContratoxProdutosxServicos($id) as $dadosCad) {
			if (in_array($dadosCad['PXSCODIGO'], $_POSTDADOS['vHPXSCODIGO'])) {
			} else {
				$config_excluir = array(
					"tabela" => Encriptar("CONTRATOSXPRODUTOSXSERVICOS", 'crud'), 
					"prefixo" => "CXP",
					"status" => "N",
					"ids" => $dadosCad['CXPCODIGO'],
					"mensagem" => "N"
				);
				excluirAtivarRegistros($config_excluir);
			}
		}
	}
	
    return $id;
}

function fill_Contratos($pOid)
{
    $SqlMain = "Select c.CLINOMEFANTASIA AS CLIENTE, p.*
				 From CONTRATOS p
				 LEFT JOIN CLIENTES c ON c.CLICODIGO = p.CLICODIGO
				 Where p.CTRCODIGO = ".$pOid;
    $vConexao = sql_conectar_banco(vGBancoSite);
    $resultSet = sql_executa(vGBancoSite, $vConexao, $SqlMain);
    $registro = sql_retorno_lista($resultSet);
    return $registro !== null ? $registro : "N";
}

//contratos x produtos/serviços

function verificarContratoxProduto($idEmp, $idPreMold)
{
	$sql = "SELECT
			 *
			FROM
			 	CONTRATOSXPRODUTOSXSERVICOS
			WHERE
				CTRCODIGO = ?
			AND
				PXSCODIGO = ?
			AND
				CXPSTATUS = 'S' ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($idPreMold, PDO::PARAM_INT),
			array($idEmp, PDO::PARAM_INT)
		)
	);
	$list = consultaComposta($arrayQuery);
	return    $list['quantidadeRegistros'];
}

function insertUpdateContratoxProdutosxServicos($_POSTDADOS, $pSMsg = 'N')
{
	$dadosBanco = array(
		'tabela'  => 'CONTRATOSXPRODUTOSXSERVICOS',
		'prefixo' => 'CXP',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function buscaContratoxProdutosxServicos($vICTRCODIGO)
{
	$sql = "SELECT
			 PXSCODIGO, CXPCODIGO
			FROM
			 	CONTRATOSXPRODUTOSXSERVICOS
			WHERE
				CTRCODIGO = ?
			AND
				CXPSTATUS = 'S' ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vICTRCODIGO, PDO::PARAM_INT)
		)
	);
	$list = consultaComposta($arrayQuery);
	return    $list['dados'];
}