<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ContasReceberxParcelasVinculo')
	listContasReceberxParcelasVinculo($_POST['pIOID'], 'ContasReceberxParcelasVinculo');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ContasReceberxParcelasVinculo')
	fill_ContasReceberxParcelas($_GET['CXPCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCXP') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("CONTASARECEBERXPARCELAS", 'crud'),
        "prefixo"  => "CXP",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listContasReceberxParcelasVinculo($vICTRCODIGO, $tituloModal){

	$sql = "SELECT
				*
			FROM
				CONTASARECEBERXPARCELAS
			WHERE
				CXPSTATUS = 'S'
			LIMIT 20 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionContasReceberxParcelas.php";
	$vAConfig['DIV_RETORNO'] = "div_parcelas";
	$vAConfig['FUNCAO_RETORNO'] = "ContasReceberxParcelas";
	$vAConfig['ID_PAI'] = $vICTRCODIGO;
	$vAConfig['vATitulos'] 	= array('', 'Vencimento', 'Seq - Neg', 'Status', 'Pagamento', 'Classificação', 'Valor', 'Comissão', 'Protesto');
	$vAConfig['vACampos'] 	= array('CXPCODIGO', 'CXPDATAVENCIMENTO', 'CXPNUMERO', 'CXPPOSICAO', 'CXPDATAPAGAMENTO', 'CXPCLASSIFICACAO', 'CXPVALOR', 'CXPAUTCOMISSAO', 'CXPPROTESTO');
	$vAConfig['vATipos'] 	= array('chave', 'date', 'varchar', 'varchar', 'date', 'varchar', 'monetario', 'simNao', 'simNao');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return;
}

function insertUpdateContasReceberxParcelas($_POSTDADOS, $pSMsg = 'N'){
	print_r($_POSTDADOS);
	$dadosBanco = array(
		'tabela'  => 'CONTASARECEBERXPARCELAS',
		'prefixo' => 'CXP',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);	
	return $id; 
}

function fill_ContasReceberxParcelas($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    CONTASARECEBERXPARCELAS
				WHERE
					CXPSTATUS = 'S'
				AND
                    CXPCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}
