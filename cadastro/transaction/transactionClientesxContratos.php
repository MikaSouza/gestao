<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxContratos') {
    listContratos($_POST['pIOID'], 'ClientesxContratos');
}

function listContratos($vIOIDPAI, $tituloModal)
{
    $sql = "SELECT
				e.*,
				t.CLINOMEFANTASIA AS CLIENTE,
				b.PXSNOME as TIPOCONTRATO,
                b2.TABDESCRICAO AS POSICAO,
                u.USUNOME AS CONSULTOR,
				CONCAT('<a href=\'../comercial/cadContratos.php?oid=', e.CTRCODIGO, '&amp;method=update\' target=\'_blank\'> ', CTRNROCONTRATO, '</a>') AS LINKCONTRATO
			FROM CONTRATOS e
			LEFT JOIN PRODUTOSXSERVICOS b ON b.PXSCODIGO = e.PXSCODIGO
			LEFT JOIN TABELAS b2 ON b2.TABCODIGO = e.CTRPOSICAO
			LEFT JOIN USUARIOS u ON u.USUCODIGO = e.CTRVENDEDOR
			LEFT JOIN CLIENTES t ON t.CLICODIGO = e.CLICODIGO
			WHERE CTRSTATUS = 'S'
			AND e.CLICODIGO = {$vIOIDPAI}
			ORDER BY 1 ";

    $arrayQuery = array(
                    'query' => $sql,
                    'parametros' => array()
                );
    $result = consultaComposta($arrayQuery);
    $vAConfig['TRANSACTION'] = "transactionClientesxContratos.php";
    $vAConfig['DIV_RETORNO'] = "div_contratos";
    $vAConfig['FUNCAO_RETORNO'] = "ClientesxContratos";
    $vAConfig['ID_PAI'] = $vIOIDPAI;
    $vAConfig['BTN_NOVO_REGISTRO'] = 'N';
    $vAConfig['BTN_EDITAR'] = 'N';
    $vAConfig['BTN_EXCLUIR'] = 'N'; 
    $vAConfig['vATitulos'] = array("Número Contrato", "Produto/Serviço", "Consultor", 'Data de Início', 'Data de Término', "Situação", "Data Cadastro");
    $vAConfig['vACampos'] = array("LINKCONTRATO", "TIPOCONTRATO", "CONSULTOR", 'CTRDATAAINICIO', 'CTRDATATERMINO', "POSICAO", "CTRDATA_INC");
    $vAConfig['vATipos'] = array("sequencial", "varchar", "varchar", 'date', 'date', "varchar", "datetime");

    include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

    return ;
}