<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'search-anexos') {
    echo json_encode(listOrientacaoTecnicaxGED($_POST['oxtcodigo'], $_POST['mencodigo']));
}

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_OrientacaoTecnicaxGED') {
    fill_OrientacaoTecnicaxGED($_GET['CHGCODIGO'], $_GET['formatoRetorno']);
}

if (isset($_POST['method']) && $_POST['method'] == 'incluirOrientacaoTecnicaxGED') {
    // print_r($_FILES);
    // print_r($_POST);
    if ($_FILES['fileUpload']['error'] == 0) {
        $nomeArquivo = removerAcentoEspacoCaracter($_FILES['fileUpload']['name']);
        uploadArquivo($_FILES['fileUpload'], '../../ged/'.$_POST['vSGEDDIRETORIO'], $nomeArquivo);
        $_POSTCHG['vIGEDVINCULO'] = $_POST['vIGEDVINCULO'];
        $_POSTCHG['vIGEDTIPO'] = $_POST['vIGEDTIPO'];
        $_POSTCHG['vSGEDNOMEARQUIVO'] = $nomeArquivo;
        $_POSTCHG['vSGEDDIRETORIO'] = $_POST['vSGEDDIRETORIO'];
        $_POSTCHG['vIEMPCODIGO'] = 1;
        $_POSTCHG['vIMENCODIGO'] = $_POST['vIMENCODIGO'];
        echo insertUpdateOrientacaoTecnicaxGED($_POSTCHG, 'N');
    }
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluir-anexo') {
    // $config_excluir = array(
    //     "tabela" => Encriptar("GED", 'crud'),
    //     "prefixo" => "GED",
    //     "status" => "N",
    //     "ids" => $_POST['pSCodigos'],
    //     "mensagem" => "S",
    //     "empresa" => "N",
    // );

    try {
        //Iniciando a conexão
        $db = conectarBanco();
        //Preparando a query
        $stmt = $db->prepare('DELETE FROM GED WHERE GEDCODIGO = :id');
        $stmt->BindValue(':id', $_POST['gedcodigo'], PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'success'), JSON_FORCE_OBJECT);
    } catch (PDOException $e) {
        echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'Error: ' . $e->getMessage()), JSON_FORCE_OBJECT);
    }


    // echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateOrientacaoTecnicaxGED($_POSTDADOS, $pSMsg = 'N')
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

function fill_OrientacaoTecnicaxGED($pOid, $formatoRetorno = 'array')
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

function listOrientacaoTecnicaxGED($gedvinculo, $mencodigo)
{
    // echo "O vinculo eh {$gedvinculo} e o menu eh {$mencodigo}";
    $sql = "SELECT
                G.GEDCODIGO,
                DATE_FORMAT(G.GEDDATA_INC, '%d/%m/%Y %H:%i') AS DATA_INCLUSAO,
                G.GEDTIPO,
                U.USUNOME,
                G.GEDNOMEARQUIVO,
				CONCAT('<a target=\"_blanc\" href=https://gestao-srv.twflex.com.br/', G.GEDDIRETORIO, '/', G.GEDNOMEARQUIVO, '>ABRIR</a>') AS LINK
            FROM
                GED G
			LEFT JOIN TABELAS T ON T.TABCODIGO = G.GEDTIPO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = G.GEDUSU_INC
			WHERE
				GEDSTATUS = 'S'
            AND G.GEDVINCULO = ?
			AND G.MENCODIGO = ? ";
    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array(
                        array($gedvinculo, PDO::PARAM_INT),
                        array($mencodigo, PDO::PARAM_INT)
                    )
                );
    $result = consultaComposta($arrayQuery);

    return $result['dados'];
}