<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxValesTransporte')
	listUsuariosxValesTransporteFilhos($_POST['pIOID'], 'UsuariosxValesTransporte');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxValesTransporte')
	fill_UsuariosxValesTransporte($_GET['vIUXVCODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
	include_once '../../twcore/teraware/php/constantes.php';
	$pAConfiguracaoTela = configuracoes_menu_acesso($_POST["vIOIDMENU"]);
	$config_excluir = array(
		"tabela" => Encriptar($pAConfiguracaoTela['MENTABELABANCO'], 'crud'),
		"prefixo" => $pAConfiguracaoTela['MENPREFIXO'],
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("USUARIOSXVALESTRANSPORTE", 'crud'),
		"prefixo" => "UXV",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxValesTransporte'){
	$_POSTBEN['vIUXVCODIGO'] = $_POST['hdn_filho_codigo'];
	$_POSTBEN['vIUSUCODIGO'] = $_POST['hdn_pai_codigo'];
	$_POSTBEN['vIVXTCODIGO'] = $_POST['vIVXTCODIGO'];
	$_POSTBEN['vIUXVQTDE'] = $_POST['vIUXVQTDE'];
	$_POSTBEN['vMUXVVALOR'] = $_POST['vMUXVVALOR'];
	$vIOID = insertUpdateUsuariosxValesTransporte($_POSTBEN, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxValesTransporte($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXVALESTRANSPORTE',
		'prefixo' => 'UXV',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxValesTransporte($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    USUARIOSXVALESTRANSPORTE
				WHERE
                    UXVCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' ) {
		$registro['UXVVALOR'] = formatar_moeda($registro['UXVVALOR'],'');
		echo json_encode($registro);
	}	
}

function listUsuariosxValesTransporte($_POSTDADOS){
	$where = '';

	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" and u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vIVXTCODIGO']))
		$where .=" and v.VXTCODIGO = ".$_POSTDADOS['FILTROS']['vIVXTCODIGO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUSITUACAO']) && ($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] != "T")) {
		if($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] == "A")
			$where .=" and u.USUDATAADMISSAO is not null and USUDATADEMISSAO is null ";
		else if($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] == "D")
			$where .=" and u.USUDATADEMISSAO is not null ";
	}
	$sql = "SELECT
	                r.*,
	                v.VXTNOME,
					v.VXTITINERARIO,
					u.USUNOME AS NOMEDEUSUARIO,
					t.TABDESCRICAO AS DEPARTAMENTO
	            FROM
	                USUARIOSXVALESTRANSPORTE r
				LEFT JOIN
	               USUARIOS u
	            ON
	                u.USUCODIGO = r.USUCODIGO	
		        LEFT JOIN VALESTRANSPORTE v ON v.VXTCODIGO = r.VXTCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO	
				WHERE					
					r.UXVSTATUS = 'S' 
					".	$where	." ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);				

	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSUSUNOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vIVXTCODIGO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIVXTCODIGO'], PDO::PARAM_INT);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxValesTransporteFilhos($vIOIDPAI, $tituloModal){
	$sql = 	'SELECT
				r.*, v.VXTNOME, v.VXTITINERARIO
			FROM
				USUARIOSXVALESTRANSPORTE r		
			LEFT JOIN VALESTRANSPORTE v ON v.VXTCODIGO = r.VXTCODIGO
			WHERE
				r.UXVSTATUS = "S"
			AND
			r.USUCODIGO = '.$vIOIDPAI;
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		$result = consultaComposta($arrayQuery);
		$vAConfig['TRANSACTION'] = "transactionUsuariosxValesTransporte.php";
		$vAConfig['DIV_RETORNO'] = "div_ValesTransporte";
		$vAConfig['FUNCAO_RETORNO'] = "UsuariosxValesTransporte";
		$vAConfig['ID_PAI'] = $vIOIDPAI;
		$vAConfig['vATitulos']     = array('', 'Vale', 'Itinerário', 'Valor Unitário', 'Quantidade dia(s)');
		$vAConfig['vACampos']      = array('UXVCODIGO', 'VXTNOME', 'VXTITINERARIO', 'UXVVALOR', 'UXVQTDE');
		$vAConfig['vATipos']       = array('chave', 'varchar', 'varchar', 'monetario', 'int');
		include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return ;
}
