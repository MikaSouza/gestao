<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'GED') {
    listGED($_POST['pIOID'], $_POST['pIOIDMENU']);
}

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_GED') {
    fill_GED($_GET['CHGCODIGO'], $_GET['formatoRetorno']);
}

if (isset($_POST['method']) && $_POST['method'] == 'incluirGED') {
    print_r($_FILES);
    print_r($_POST);
    if ($_FILES['fileUpload']['error'] == 0) {
        $nomeArquivo = removerAcentoEspacoCaracter($_FILES['fileUpload']['name']);
        uploadArquivo($_FILES['fileUpload'], '../../ged/'.$_POST['vSGEDDIRETORIO'], $nomeArquivo);
        $_POSTCHG['vIGEDVINCULO'] = $_POST['vIGEDVINCULO'];
        $_POSTCHG['vIGEDTIPO'] = $_POST['vIGEDTIPO'];
        $_POSTCHG['vSGEDNOMEARQUIVO'] = $nomeArquivo;
        $_POSTCHG['vSGEDDIRETORIO'] = $_POST['vSGEDDIRETORIO'];
        $_POSTCHG['vIEMPCODIGO'] = 1;
        $_POSTCHG['vIMENCODIGO'] = $_POST['vIMENCODIGO'];
        echo insertUpdateGED($_POSTCHG, 'N');
    }
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
    $config_excluir = array(
        "tabela" => Encriptar("GED", 'crud'),
        "prefixo" => "GED",
        "status" => "N",
        "ids" => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa" => "N",
    );
    echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateGED($_POSTDADOS, $pSMsg = 'N')
{
    $dadosBanco = array(
        'tabela'  => 'GED',
        'prefixo' => 'GED',
        'fields'  => $_POSTDADOS,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N'
        );
    $id = insertUpdate($dadosBanco);
    return $id;
}

function fill_GED($pOid, $formatoRetorno = 'array')
{
    $SqlMain = 	"SELECT
	                *
	            FROM
                    GED
				WHERE
					GEDSTATUS = 'S'
				AND
                    GEDCODIGO = {$pOid}";

    $vConexao     = sql_conectar_banco(vGBancoSite);
    $resultSet    = sql_executa(vGBancoSite, $vConexao, $SqlMain);
    if (!$registro = sql_retorno_lista($resultSet)) {
        return false;
    }

    if ($formatoRetorno == 'array') {
        return $registro !== null ? $registro : "N";
    }
    if ($formatoRetorno == 'json') {
        echo json_encode($registro);
    }
}

function listGED($vIOIDPAI, $pIOIDMENU)
{
    $sql = "SELECT
                FS.*, T.TABDESCRICAO AS TIPO, U.USUNOME,
				CONCAT('<a target=_blank href=/ged/', FS.GEDDIRETORIO, '/', FS.GEDNOMEARQUIVO, '>ABRIR</a>') AS LINK
            FROM
                GED FS
			LEFT JOIN TABELAS T ON T.TABCODIGO = FS.GEDTIPO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = FS.GEDUSU_INC
			WHERE
				GEDSTATUS = 'S'
            AND FS.GEDVINCULO = ".$vIOIDPAI."
			AND FS.MENCODIGO = ".$pIOIDMENU;

    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array()
                );
    $result = consultaComposta($arrayQuery);
    $vAConfig['TRANSACTION'] = "../../utilitarios/transaction/transactionGED.php";
    $vAConfig['DIV_RETORNO'] = "div_ged";
    $vAConfig['FUNCAO_RETORNO'] = "GED";
    $vAConfig['ID_PAI'] = $vIOIDPAI; 
    $vAConfig['BTN_NOVO_REGISTRO'] = 'N';
	$vAConfig['BTN_EDITAR'] = 'N';
    $vAConfig['vATitulos'] 	= array('', 'Data', 'Usuário', 'Tipo', 'Arquivo', 'Link');
    $vAConfig['vACampos'] 	= array('GEDCODIGO', 'GEDDATA_INC', 'USUNOME', 'TIPO', 'GEDNOMEARQUIVO', 'LINK');
    $vAConfig['vATipos'] 	= array('chave', 'datetime', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
    include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

    return ;
}

function listGED2($_POSTDADOS)
{
    $where = '';
    if (verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])) {
        if ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S') {
            $where .= "AND FS.GEDSTATUS = 'S' ";
        } elseif ($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N') {
            $where .= "AND FS.GEDSTATUS = 'N' ";
        }
    } else {
        $where .= "AND FS.GEDSTATUS = 'S' ";
    }

    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])) {
        $where .= 'AND FS.GEDDATA_INC >= ? ';
    }
    if (verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])) {
        $where .= 'AND FS.GEDDATA_INC <= ? ';
    }
    $sql = "SELECT
                FS.*, T.TABDESCRICAO AS TIPO, U.USUNOME, M.MENTITULO,
				CONCAT('<a target=_blank href=/ged/', FS.GEDDIRETORIO, '/', FS.GEDNOMEARQUIVO, '>ABRIR</a>') AS LINK
            FROM
                GED FS
			LEFT JOIN TABELAS T ON T.TABCODIGO = FS.GEDTIPO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = FS.GEDUSU_INC
			LEFT JOIN MENUS M ON M.MENCODIGO = FS.MENCODIGO
			WHERE
				1 = 1
			".	$where	."
			LIMIT 250	";

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
    $result = consultaComposta($arrayQuery);

    return $result;
}