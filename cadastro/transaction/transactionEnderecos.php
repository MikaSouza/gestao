<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxEnderecos')
	listEnderecos($_POST['pIOID'], 'ClientesxEnderecos');

if( $_GET['hdn_metodo_fill'] == 'fill_Enderecos' )
	fill_Enderecos($_GET['vICLICODIGO'], $_GET['vITABCODIGO'], $_GET['formatoRetorno']); 

if (isset($_POST["method"]) && $_POST["method"] == 'excluirCLI') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("ENDERECOS", 'crud'),
        "prefixo"  => "CLI",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listEnderecos($vIOIDPAI, $tituloModal){

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
	                r.CIDCODIGO = c. CIDCODIGO
		       	LEFT JOIN
	                TABELAS t
	            ON
	                r.TABCODIGO = t. TABCODIGO
				WHERE
					r.ENDSTATUS = 'S'
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

function insertUpdateEnderecos($_POSTDADOS, $pSMsg = 'N'){
	if ($_POSTDADOS['vHENDLOGRADOURO'] != ''){
		$_POSTDADOSEND['vIENDCODIGO'] = $_POSTDADOS['vHENDCODIGO'];
		$_POSTDADOSEND['vITABCODIGO'] = 426;
		$_POSTDADOSEND['vSENDLOGRADOURO'] = $_POSTDADOS['vHENDLOGRADOURO']; 
		$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHESTCODIGO'];
		$_POSTDADOSEND['vIPAICODIGO'] = $_POSTDADOS['vHPAICODIGO'];
		$_POSTDADOSEND['vICIDCODIGO'] = $_POSTDADOS['vHCIDCODIGO'];
		$_POSTDADOSEND['vSENDNROLOGRADOURO'] = $_POSTDADOS['vHENDNROLOGRADOURO'];
		$_POSTDADOSEND['vSENDBAIRRO'] = $_POSTDADOS['vHENDBAIRRO'];
		$_POSTDADOSEND['vSENDCEP'] = $_POSTDADOS['vHENDCEP'];
		$_POSTDADOSEND['vSENDCOMPLEMENTO'] = $_POSTDADOS['vHENDCOMPLEMENTO'];			
		$_POSTDADOSEND['vICLICODIGO'] = $_POSTDADOS['vICLICODIGO'];
		$_POSTDADOSEND['vSENDPADRAO'] = $_POSTDADOS['vHENDPADRAO'];
		$dadosBanco = array(
			'tabela'  => 'ENDERECOS',
			'prefixo' => 'END',
			'fields'  => $_POSTDADOSEND,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);
		return $id; 
	}		
}

function fill_Enderecos($vICLICODIGO, $vSCONTIPO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM ENDERECOS
			WHERE ENDSTATUS = 'S'
			AND CLICODIGO = ? 
			AND ENDPADRAO = ? ";
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