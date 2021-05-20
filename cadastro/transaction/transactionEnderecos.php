<?php
include_once __DIR__ . '/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxEnderecos') {
	listEnderecos($_POST['pIOID'], 'ClientesxEnderecos');
}

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxEnderecos')
	insertUpdateEnderecos($_POST);

if (isset($_POST['method'])) {
	switch ($_POST['method']) {
		case 'fill_ClientesxEnderecos':
			echo json_encode(showEndereco((int) $_POST['vIENDCODIGO']));
			break;
		case 'excluir-endereco':
			excluirEndereco($_POST['endcodigo']);
			break;
	}
}

// if ($_GET['hdn_metodo_fill'] == 'fill_Enderecos') {
// 	fill_Enderecos($_GET['vIENDCODIGO'], $_GET['formatoRetorno']);
// }

if ($_GET['hdn_metodo_fill'] == 'fill_ClientesxEnderecos')
	fill_EnderecosPadrao($_GET['vIENDCODIGO'], $_GET['formatoRetorno']);


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

function insertUpdateEnderecos($_POSTDADOS, $pSMsg = 'N'){
	if (($_POSTDADOS['vHENDLOGRADOURO'] != '') || ($_POSTDADOS['vHESTCODIGO'] != '') || ($_POSTDADOS['vHCIDCODIGO'] != '') ||
		($_POSTDADOS['vHENDNROLOGRADOURO'] != '') || ($_POSTDADOS['vHENDBAIRRO'] != '') || ($_POSTDADOS['vHENDCEP'] != '') ||
		($_POSTDADOS['vHENDCOMPLEMENTO'] != '')	)
	{
		$_POSTDADOSEND['vIENDCODIGO'] = $_POSTDADOS['vHENDCODIGO'];
		$_POSTDADOSEND['vITABCODIGO'] = 426;
		$_POSTDADOSEND['vSENDLOGRADOURO'] = $_POSTDADOS['vHENDLOGRADOURO']; 
		$_POSTDADOSEND['vIESTCODIGO'] = $_POSTDADOS['vHESTCODIGO'];
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

function fill_EnderecosPadrao($vIENDCODIGO, $formatoRetorno = 'array')
{
	$sql = "SELECT *
			FROM ENDERECOS
			WHERE ENDSTATUS = 'S'
			AND ENDCODIGO = ? ";
	$arrayQuery = array(
		'query' => $sql,
		'parametros' => array(
			array($vIENDCODIGO, PDO::PARAM_INT)
		)
	);
	$result = consultaComposta($arrayQuery);
	$registro = $result['dados'][0];
	if ($formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if ($formatoRetorno == 'json')
		echo json_encode($registro);
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

function showEndereco($endcodigo)
{
	echo '====' . $endcodigo;
	if (isset($endcodigo)) {
		$result = consultaComposta([
			'query' => "SELECT
							E.ENDCODIGO,
							E.ENDCEP,
							E.ENDLOGRADOURO,
							E.ENDNROLOGRADOURO,
							E.ENDCOMPLEMENTO,
							E.ENDBAIRRO,
							U.ESTCODIGO,
							C.CIDCODIGO
						FROM
							ENDERECOS E
						LEFT JOIN
							ESTADOS U ON E.ESTCODIGO = U.ESTCODIGO
						LEFT JOIN
							CIDADES C ON E.CIDCODIGO = C.CIDCODIGO
						WHERE
							ENDCODIGO = ?",
			'parametros' => array(
				array($endcodigo, PDO::PARAM_INT)
			)
		]);

		return $result['dados'][0];
	}
	return [];
}

function excluirEndereco($endcodigo)
{
	try {
		//Iniciando a conexão
		$db = conectarBanco();
		//Preparando a query
		$stmt = $db->prepare("UPDATE ENDERECOS SET ENDSTATUS = 'N' WHERE ENDCODIGO = :id");
		$stmt->BindValue(':id', $endcodigo, PDO::PARAM_INT);
		$stmt->execute();

		echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'success'), JSON_FORCE_OBJECT);
	} catch (PDOException $e) {
		echo json_encode(array('registrosExcluidos' => $stmt->rowCount(), 'message' => 'Error: ' . $e->getMessage()), JSON_FORCE_OBJECT);
	}
}

function listEnderecos($vIOIDPAI, $tituloModal)
{

	if ($vIOIDPAI != "") {

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
		-- AND
		-- 	r.ENDPADRAO <> 'S'
		AND
			r.CLICODIGO = '{$vIOIDPAI}'";

		$arrayQuery = array(
			'query' => $sql,
			'parametros' => array()
		);
		$result = consultaComposta($arrayQuery);
	} else {
		$result['quantidadeRegistros'] = 0;
	}

	$vAConfig['TRANSACTION'] = "transactionEnderecos.php";
	$vAConfig['DIV_RETORNO'] = "div_enderecos";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxEnderecos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Tipo', 'Logradouro', 'Nro', 'Complemento', 'Bairro', 'CEP', 'Cidade', 'UF');
	$vAConfig['vACampos']     = array('ENDCODIGO', 'TABDESCRICAO', 'ENDLOGRADOURO', 'ENDNROLOGRADOURO', 'ENDCOMPLEMENTO', 'ENDBAIRRO', 'ENDCEP', 'CIDDESCRICAO', 'ESTSIGLA');
	$vAConfig['vATipos']     = array('chave', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return;
}