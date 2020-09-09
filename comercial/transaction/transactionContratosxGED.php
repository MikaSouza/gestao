<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxGED')
	listClientesxGED($_POST['pIOID'], $_POST['pIOIDMENU']); 

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ClientesxGED')
	fill_ClientesxGED($_GET['CHGCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxGED'){
	print_r($_FILES);
	print_r($_POST);
	if($_FILES['fileUpload']['error'] == 0){
		$nomeArquivo = removerAcentoEspacoCaracter($_FILES['fileUpload']['name']);
		uploadArquivo($_FILES['fileUpload'], '../../ged/'.$_POST['vSGEDDIRETORIO'], $nomeArquivo);
		$_POSTCHG['vIGEDVINCULO'] = $_POST['vIGEDVINCULO'];
		$_POSTCHG['vIGEDTIPO'] = $_POST['vIGEDTIPO'];	
		$_POSTCHG['vSGEDNOMEARQUIVO'] = $nomeArquivo;
		$_POSTCHG['vSGEDDIRETORIO'] = $_POST['vSGEDDIRETORIO'];
		$_POSTCHG['vIEMPCODIGO'] = 1; 
		$_POSTCHG['vIMENCODIGO'] = $_POST['vIMENCODIGO'];	
		echo insertUpdateClientesxGED($_POSTCHG, 'N');
	}	
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
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

function insertUpdateClientesxGED($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'GED',
		'prefixo' => 'GED',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ClientesxGED($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    GED
				WHERE
					GEDSTATUS = 'S'
				AND
                    GEDCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listClientesxGED($vIOIDPAI, $pIOIDMENU){
	$sql = "SELECT
                FS.*, T.TABDESCRICAO AS TIPO, U.USUNOME,
				CONCAT('<a href=http://sistema.marpa.com.br:5588/marpa_consultoria/', FS.GEDDIRETORIO, '/', FS.GEDNOMEARQUIVO, '>ABRIR</a>') AS LINK
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
	$vAConfig['TRANSACTION'] = "transactionClientesxGED.php";
	$vAConfig['DIV_RETORNO'] = "div_ged";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxGED";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['BTN_NOVO_REGISTRO'] = 'N';
	$vAConfig['vATitulos'] 	= array('', 'Data', 'Usuário', 'Tipo', 'Link');
	$vAConfig['vACampos'] 	= array('GEDCODIGO', 'GEDDATA_INC', 'USUNOME', 'TIPO', 'LINK');
	$vAConfig['vATipos'] 	= array('chave', 'datetime', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}