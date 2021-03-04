<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxEnderecos') {
    listEnderecos($_POST['pIOID'], 'ClientesxEnderecos');
}

if ($_GET['hdn_metodo_fill'] == 'fill_Enderecos') {
    fill_Enderecos($_GET['vIENDCODIGO'], $_GET['formatoRetorno']);
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
    echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("ENDERECOS", 'crud'),
        "prefixo"  => "END",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "N",
    ));
}

if (isset($_POST['method']) && $_POST['method'] == 'incluirEndereco') {
    insertUpdateEnderecos($_POST);
}


function listEnderecos($vIOIDPAI, $tituloModal)
{
    $sql = "SELECT
	                r.*,
	             	e.ESTSIGLA,
	                c.CIDDESCRICAO,
	                t.TABDESCRICAO
	            FROM
	                ENDERECOS r
	            LEFT JOIN
	                ESTADOS e
	            ON
	                r.ESTCODIGO = e.ESTCODIGO
	            LEFT JOIN
	                CIDADES c
	            ON
	                r.CIDCODIGO = c.CIDCODIGO
		       	LEFT JOIN
	                TABELAS t
	            ON
	                r.TABCODIGO = t.TABCODIGO
				WHERE
					r.ENDSTATUS = 'S'
                AND
                    r.ENDPADRAO <> 'S'
				AND
					r.CLICODIGO = ".$vIOIDPAI;
    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array()
                );
    $result = consultaComposta($arrayQuery);
    $vAConfig['TRANSACTION'] = "transactionEnderecos.php";
    $vAConfig['DIV_RETORNO'] = "div_enderecos";
    $vAConfig['FUNCAO_RETORNO'] = "ClientesxEnderecos";
    $vAConfig['ID_PAI'] = $vIOIDPAI;
    $vAConfig['vATitulos'] 	= array('', 'Tipo', 'Logradouro', 'Nro', 'Complemento', 'Bairro', 'CEP', 'Cidade', 'UF');
    $vAConfig['vACampos'] 	= array('ENDCODIGO', 'TABDESCRICAO', 'ENDLOGRADOURO', 'ENDNROLOGRADOURO', 'ENDCOMPLEMENTO', 'ENDBAIRRO', 'ENDCEP', 'CIDDESCRICAO', 'ESTSIGLA');
    $vAConfig['vATipos'] 	= array('chave', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
    include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

    return ;
}

function insertUpdateEnderecos($parametros, $pSMsg = 'N')
{
    $dadosBanco = array(
        'tabela'  => 'ENDERECOS',
        'prefixo' => 'END',
        'fields'  => $parametros,
        'msg'     => $pSMsg,
        'url'     => '',
        'debug'   => 'N'
        );
    $id = insertUpdate($dadosBanco);
    return $id;
}

function fill_Enderecos($vIENDCODIGO, $formatoRetorno = 'array')
{
    $sql = "SELECT *
			FROM ENDERECOS
			WHERE ENDSTATUS = 'S'
			AND ENDCODIGO = ?";
    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array(
                         array($vIENDCODIGO, PDO::PARAM_INT)
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

function getEnderecosByCodigoCliente($clicodigo)
{
    $sql = "SELECT
	                r.*,
	             	e.ESTSIGLA,
	                c.CIDDESCRICAO,
	                t.TABDESCRICAO
	            FROM
	                ENDERECOS r
	            LEFT JOIN
	                ESTADOS e
	            ON
	                r.ESTCODIGO = e.ESTCODIGO
	            LEFT JOIN
	                CIDADES c
	            ON
	                r.CIDCODIGO = c.CIDCODIGO
		       	LEFT JOIN
	                TABELAS t
	            ON
	                r.TABCODIGO = t.TABCODIGO
				WHERE
					r.ENDSTATUS = 'S'
                AND
                    r.ENDPADRAO = 'S'
				AND
					r.CLICODIGO = {$clicodigo}";
    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array()
                );
    $result = consultaComposta($arrayQuery);

    return $result['dados'][0];
}