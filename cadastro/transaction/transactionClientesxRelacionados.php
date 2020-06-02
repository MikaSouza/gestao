<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxRelacionados')
	listClientesxRelacionados($_POST['pIOID'], 'ClientesxRelacionados');

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxRelacionados'){
	$_POSTCHG['vICLICODIGOREL'] = $_POST['vHCLICODIGOREL'];
	$_POSTCHG['vICLICODIGO'] = $_POST['hdn_pai_codgo'];
	$_POSTCHG['vICXRCODIGO'] = $_POST['hdn_filho_codgo'];
	echo insertUpdateClientesxRelacionados($_POSTCHG, 'N');
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("CLIENTESXRELACIONADOS", 'crud'),
		"prefixo" => "CXR",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateClientesxRelacionados($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'CLIENTESXRELACIONADOS',
		'prefixo' => 'CXR',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function listClientesxRelacionados($vIOIDPAI, $tituloModal){
	$sql = "SELECT
                R.CXRCODIGO, R.CXRDATA_INC, C.CLINOME
            FROM
                CLIENTESXRELACIONADOS R
            LEFT JOIN
                CLIENTES C
            ON
                C.CLICODIGO = R.CLICODIGOREL
			WHERE
				R.CXRSTATUS = 'S'
            AND R.CLICODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionClientesxRelacionados.php";
	$vAConfig['DIV_RETORNO'] = "div_relacionados";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxRelacionados";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['BTN_EDITAR'] = 'N';
	$vAConfig['vATitulos'] 	= array('', 'Cliente', 'Data Cadastro');
	$vAConfig['vACampos'] 	= array('CXRCODIGO', 'CLINOME', 'CXRDATA_INC');
	$vAConfig['vATipos'] 	= array('chave', 'varchar', 'datetime');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}