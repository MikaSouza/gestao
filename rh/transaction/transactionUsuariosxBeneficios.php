<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxBeneficios')
	listUsuariosxBeneficiosFilhos($_POST['pIOID'], 'UsuariosxBeneficios');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxBeneficios')
	fill_UsuariosxBeneficios($_GET['vIUXBCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("USUARIOSXBENEFICIOS", 'crud'),
		"prefixo" => "UXB",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirUsuariosxBeneficios'){
	$_POSTBEN['vIUXBCODIGO'] = $_POST['hdn_filho_codigo'];
	$_POSTBEN['vIUSUCODIGO'] = $_POST['hdn_pai_codigo'];
	$_POSTBEN['vIEMPCODIGO'] = 1;//$_SESSION["SI_USU_EMPRESA"];
	$_POSTBEN['vITABCODIGO'] = $_POST['vITABCODIGO'];
	$_POSTBEN['vIUXBQTDE'] = $_POST['vIUXBQTDE'];
	$_POSTBEN['vMUXBVALOR'] = $_POST['vMUXBVALOR'];
	$_POSTBEN['vMUXBPORCENTO'] = $_POST['vMUXBPORCENTO'];
	$vIOID = insertUpdateUsuariosxBeneficios($_POSTBEN, 'N');
	echo $vIOID;
}

function insertUpdateUsuariosxBeneficios($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'USUARIOSXBENEFICIOS',
		'prefixo' => 'UXB',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_UsuariosxBeneficios($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    USUARIOSXBENEFICIOS
				WHERE
                    UXBCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' ) {
		$registro['UXBVALOR'] = formatar_moeda($registro['UXBVALOR'],'');
		$registro['UXBPORCENTO'] = formatar_moeda($registro['UXBPORCENTO'],'');
		echo json_encode($registro);
	}	
}

function listUsuariosxBeneficios(){

	$sql = "SELECT
	                r.*,
	                t.TABDESCRICAO AS BENEFICIO,
					u.USUNOME AS NOMEDEUSUARIO
	            FROM
	                USUARIOSXBENEFICIOS r
				LEFT JOIN
	               USUARIOS u
	            ON
	                u.USUCODIGO = r.USUCODIGO	
		       	LEFT JOIN
	                TABELAS t
	            ON
	                r.TABCODIGO = t.TABCODIGO
				WHERE
					r.UXBSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listUsuariosxBeneficiosFilhos($vIOIDPAI, $tituloModal){
	$sql = 	'SELECT
				r.*,
				t.TABDESCRICAO
			FROM
				USUARIOSXBENEFICIOS r
			LEFT JOIN
				TABELAS t
			ON
				r.TABCODIGO = t. TABCODIGO
			WHERE
				r.UXBSTATUS = "S"
			AND
			r.USUCODIGO = '.$vIOIDPAI;
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		$result = consultaComposta($arrayQuery);
		$vAConfig['TRANSACTION'] = "transactionUsuariosxBeneficios.php";
		$vAConfig['DIV_RETORNO'] = "div_Beneficios";
		$vAConfig['FUNCAO_RETORNO'] = "UsuariosxBeneficios";
		$vAConfig['ID_PAI'] = $vIOIDPAI;
		$vAConfig['vATitulos']     = array('','Benefício', 'Quantidade (dia)', 'Valor Unitário', '%');
		$vAConfig['vACampos']      = array('UXBCODIGO','TABDESCRICAO', 'UXBQTDE', 'UXBVALOR', 'UXBPORCENTO');
		$vAConfig['vATipos']       = array('chave','varchar', 'int','float', 'float');
		include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';
	return ;
}
