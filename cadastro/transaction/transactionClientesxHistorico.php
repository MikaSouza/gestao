<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxHistorico')
	listClientesxHistorico($_POST['pIOID'], 'ClientesxHistorico');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ClientesxHistorico')
	fill_ClientesxHistorico($_GET['CHGCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxHistorico'){
	$_POSTCHG['vDCHGDATA'] = $_POST['vDCHGDATA'];
	$_POSTCHG['vICHGTIPO'] = $_POST['vICHGTIPO'];
	$_POSTCHG['vICHGPOSICAO'] = $_POST['vICHGPOSICAO'];
	$_POSTCHG['vSCHGHISTORICO'] = $_POST['vSCHGHISTORICO'];
	$_POSTCHG['vICLICODIGO'] = $_POST['hdn_pai_codgo'];
	$_POSTCHG['vICHGCODIGO'] = $_POST['hdn_filho_codgo'];
	$_POSTCHG['vIEMPCODIGO'] = 1;
	echo insertUpdateClientesxHistorico($_POSTCHG, 'N');
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("CLIENTESXHISTORICOGERAL", 'crud'),
		"prefixo" => "CHG",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateClientesxHistorico($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CLIENTESXHISTORICOGERAL',
		'prefixo' => 'CHG',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ClientesxHistorico($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    CLIENTESXHISTORICOGERAL
				WHERE
					CHGSTATUS = 'S'
				AND
                    CHGCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listClientesxHistorico($vIOIDPAI, $tituloModal){
	$sql = "SELECT
                FS.*, T.TABDESCRICAO AS TIPO, T2.TABDESCRICAO AS POSICAO, U.USUNOME
            FROM
                CLIENTESXHISTORICOGERAL FS
			LEFT JOIN TABELAS T ON T.TABCODIGO = FS.CHGTIPO	
			LEFT JOIN TABELAS T2 ON T2.TABCODIGO = FS.CHGPOSICAO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = FS.CHGUSU_INC			
			WHERE
				CHGSTATUS = 'S'
            AND FS.CLICODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionClientesxHistorico.php";
	$vAConfig['DIV_RETORNO'] = "div_historico";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxHistorico";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Data Contato', 'Tipo Contato', 'Status', 'Usuário', 'Descrição');
	$vAConfig['vACampos'] 	= array('CHGCODIGO', 'CHGDATA', 'TIPO', 'POSICAO', 'USUNOME', 'CHGHISTORICO');
	$vAConfig['vATipos'] 	= array('chave', 'datetime', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}