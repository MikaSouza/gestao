<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxEnderecos')
	listUsuariosxEnderecos($_POST['pIOID'], 'UsuariosxEnderecos');

if( $_GET['hdn_metodo_fill'] == 'fill_UsuariosxEnderecos' )
	fill_UsuariosxEnderecos($_GET['vIUSUCODIGO'], $_GET['formatoRetorno']); 

if (isset($_POST["method"]) && $_POST["method"] == 'excluirUXE') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("USUARIOSXENDERECOS", 'crud'),
        "prefixo"  => "UXE",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listUsuariosxEnderecos($vIOIDPAI, $tituloModal){

	$sql = "SELECT
	                r.*,
	             	e.ESTSIGLA,
	                c.CIDDESCRICAO,
	                t.TABDESCRICAO
	            FROM
	                USUARIOSXENDERECOS r
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
					r.UXESTATUS = 'S'
				AND
					r.USUCODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionUsuariosxEnderecos.php";
	$vAConfig['DIV_RETORNO'] = "div_UsuariosxEnderecos";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxUsuariosxEnderecos"; 
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos'] 	= array('', 'Tipo', 'Logradouro', 'Nro', 'Complemento', 'Bairro', 'CEP', 'Cidade', 'UF');
	$vAConfig['vACampos'] 	= array('UXECODIGO', 'TABDESCRICAO', 'UXELOGRADOURO', 'UXENROLOGRADOURO', 'UXECOMPLEMENTO', 'UXEBAIRRO', 'UXECEP', 'CIDDESCRICAO', 'ESTSIGLA');
	$vAConfig['vATipos'] 	= array('chave', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;
}

function insertUpdateUsuariosxEnderecos($_POSTDADOS, $pSMsg = 'N'){
	$_POSTDADOSEND['vIUXECODIGO'] = $_POSTDADOS['vHUXECODIGO'];
	$_POSTDADOSEND['vSUXELOGRADOURO'] = $_POSTDADOS['vHUXELOGRADOURO']; 
	$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHESTCODIGO'];
	$_POSTDADOSEND['vIPAICODIGO'] = $_POSTDADOS['vHPAICODIGO'];
	$_POSTDADOSEND['vICIDCODIGO'] = $_POSTDADOS['vHCIDCODIGO'];
	$_POSTDADOSEND['vSUXENROLOGRADOURO'] = $_POSTDADOS['vHUXENROLOGRADOURO'];
	$_POSTDADOSEND['vSUXEBAIRRO'] = $_POSTDADOS['vHUXEBAIRRO'];
	$_POSTDADOSEND['vSUXECEP'] = $_POSTDADOS['vHUXECEP'];
	$_POSTDADOSEND['vSUXECOMPLEMENTO'] = $_POSTDADOS['vHUXECOMPLEMENTO'];			
	$_POSTDADOSEND['vIUSUCODIGO'] = $_POSTDADOS['vIUSUCODIGO'];	
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXENDERECOS',
		'prefixo' => 'UXE',
		'fields'  => $_POSTDADOSEND,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	
	return $id; 
}

function fill_UsuariosxEnderecos($vIUSUCODIGO, $formatoRetorno = 'array'){
	
	$sql = "SELECT *
			FROM USUARIOSXENDERECOS
			WHERE UXESTATUS = 'S'
			AND USUCODIGO = ?";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						 array($vIUSUCODIGO, PDO::PARAM_INT)
					)
				);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];	
	if( $formatoRetorno == 'array')
		return $registro;
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}