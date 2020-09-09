<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxContatos')
	listContatos($_POST['pIOID'], 'ClientesxContatos');

if( $_GET['hdn_metodo_fill'] == 'fill_Contatos' )
	fill_Contatos($_GET['vICLICODIGO'], $_GET['formatoRetorno']); 

if( $_GET['hdn_metodo_fill'] == 'fill_ContatosPadrao' )
	fill_ContatosPadrao($_GET['vICONCODIGO'], $_GET['formatoRetorno']); 

if(isset($_POST["method"]) && $_POST["method"] == 'incluirClientesxContatos'){
	$_POSTBEN['vHCONCODIGO'] = $_POST['hdn_filho_codigo'];
	$_POSTBEN['vICLICODIGO'] = $_POST['hdn_pai_codigo'];
	$_POSTBEN['vHCONNOME'] = $_POST['vHCONNOME'];
	$_POSTBEN['vHCONEMAIL'] = $_POST['vHCONEMAIL'];
	$_POSTBEN['vHCONCELULAR'] = $_POST['vHCONCELULAR'];
	$_POSTBEN['vHCONFONE'] = $_POST['vHCONFONE'];
	$_POSTBEN['vHCONCARGO'] = $_POST['vHCONCARGO'];
	$_POSTBEN['vHCONSETOR'] = $_POST['vHCONSETOR'];
	$_POSTBEN['vHCONSENHA'] = $_POST['vHCONSENHA'];
	$_POSTBEN['vHCONPRINCIPAL'] = 'N';
	$vIOID = insertUpdateContatos($_POSTBEN, 'N');
	echo $vIOID;
}

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCLI') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("CONTATOS", 'crud'),
        "prefixo"  => "CON",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listContatos($vIOIDPAI, $tituloModal){

	$sql = "SELECT
				c.*				
			FROM
				CONTATOS c
			WHERE
				c.CLICODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionContatos.php";
	$vAConfig['DIV_RETORNO'] = "div_contatos";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxContatos"; 
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Nome', 'E-mail', 'Telefone', 'Celular', 'Cargo', 'Setor/Lotação', 'Data Inclusão');
	$vAConfig['vACampos'] 	= array('CONCODIGO', 'CONNOME', 'CONEMAIL', 'CONFONE', 'CONCELULAR', 'CONCARGO', 'CONSETOR"', 'CONDATA_INC');
	$vAConfig['vATipos'] 	= array('chave', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'datetime');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return ;
}

function insertUpdateContatos($_POSTDADOS, $pSMsg = 'N'){
	if ($_POSTDADOS['vHCONNOME'] != ''){	
		$_POSTDADOSCON['vICONCODIGO'] = $_POSTDADOS['vHCONCODIGO'];
		$_POSTDADOSCON['vSCONNOME'] = $_POSTDADOS['vHCONNOME']; 
		$_POSTDADOSCON['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSCON['vSCONEMAIL'] = $_POSTDADOS['vHCONEMAIL'];
		$_POSTDADOSCON['vSCONCELULAR'] = $_POSTDADOS['vHCONCELULAR'];
		$_POSTDADOSCON['vSCONFONE'] = $_POSTDADOS['vHCONFONE'];
		$_POSTDADOSCON['vSCONCARGO'] = $_POSTDADOS['vHCONCARGO'];	
		$_POSTDADOSCON['vSCONSETOR'] = $_POSTDADOS['vHCONSETOR'];	
		$_POSTDADOSCON['vSCONSENHA'] = $_POSTDADOS['vHCONSENHA'];
		$_POSTDADOSEND['vSCONPRINCIPAL'] = $_POSTDADOS['vHCONPRINCIPAL'];
		
		$dadosBanco = array(
			'tabela'  => 'CONTATOS',
			'prefixo' => 'CON',
			'fields'  => $_POSTDADOSCON,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);		
		return $id; 
	}	
}

function fill_Contatos($vICLICODIGO, $vSCONTIPO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM CONTATOS
			WHERE CONSTATUS = 'S'
			AND CLICODIGO = ? 
			AND CONPRINCIPAL = ? ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						 array($vICLICODIGO, PDO::PARAM_INT),
						 array($vSCONTIPO, PDO::PARAM_STR)
					)
				);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];	
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}

function fill_ContatosPadrao($vICONCODIGO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM CONTATOS
			WHERE CONSTATUS = 'S'
			AND CONCODIGO = ? ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						 array($vICONCODIGO, PDO::PARAM_INT)
					)
				);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];	
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}