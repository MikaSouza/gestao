<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxAuditoria')
	listClientesxAuditoria($_POST['pIOID'], 'ClientesxAuditoria');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_ClientesxAuditoria')
	fill_ClientesxAuditoria($_GET['AUDCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['method']) && $_POST['method'] == 'incluirClientesxAuditoria'){
	
	$_POSTAUD['vIEMPCODIGO'] = 1;
	$_POSTAUD['vSAUDANTIGO'] =
	$_POSTAUD['vSAUDNOVO'] =
	$_POSTAUD['vIAUDMENU'] =
	$_POSTAUD['AUDIDVINCULO'] =
	$_POSTAUD['vSAUDCAMPO'] =
	$_POSTAUD['vICLICODIGO'] =
	
	
	$_POSTAUD['vIAUDIDSEGMENTO'] = $_POST['vIAUDIDSEGMENTO'];
	$_POSTAUD['vIAUDIDFILIAIS'] = $_POST['hdn_pai_codgo'];
	$_POSTAUD['vIAUDCODIGO'] = $_POST['hdn_filho_codgo'];
	echo insertUpdateClientesxAuditoria($_POSTAUD, 'N');
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("AUDITORIA", 'crud'),
		"prefixo" => "AUD",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function insertUpdateClientesxAuditoria($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'AUDITORIA',
		'prefixo' => 'AUD',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_ClientesxAuditoria($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    AUDITORIA
				WHERE
					AUDSTATUS = 'S'
				AND
                    AUDCODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listClientesxAuditoria($vIOIDPAI, $tituloModal){
	$sql = "SELECT
                A.*, U.USUNOME
            FROM
                AUDITORIA A
			LEFT JOIN USUARIOS U ON U.USUCODIGO = A.AUDUSU_INC				
			WHERE
				A.AUDSTATUS = 'S'
            AND A.CLICODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionClientesxAuditoria.php";
	$vAConfig['DIV_RETORNO'] = "div_historico";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxAuditoria";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['BTN_NOVO_REGISTRO'] = "N";
	$vAConfig['BTN_EDITAR'] = "N";
	$vAConfig['vATitulos'] 	= array('', 'Data', 'Operação', 'Usuário', 'Campo', 'Antigo', 'Novo');
	$vAConfig['vACampos'] 	= array('AUDCODIGO', 'AUDDATA_INC', 'USUNOME', 'AUDUSU_INC', 'AUDCAMPO', 'AUDANTIGO', 'AUDNOVO');
	$vAConfig['vATipos'] 	= array('chave', 'datetime', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}

function auditoria($dados){

	$sql = "SELECT * FROM  ".$dados['tabela']." ";
	$sql .= "WHERE ".$dados['prefixo']."CODIGO = ".$dados['fields']['vI'.$dados['prefixo'].'CODIGO'];
	
	$vIAUDIDVINCULO = $dados['fields']['vI'.$dados['prefixo'].'CODIGO'];

	//Iniciando a conexão
	$db = conectarBanco();

	//Preparando a query
	$query = $db->prepare($sql);

	$fields = array_combine(array_map('removePrefix', array_keys($dados['fields'])), $dados['fields']);
 
	//Executando
	$vSAlteracoes = '';
	if($query->Execute()){
		$result = $query->fetch(PDO::FETCH_ASSOC);
		foreach($result as $i => $campos){
			foreach ($fields as $k => $valores){
				if (($i == $k) && ($campos != $valores)) {
					$_POSTDADOS2['vIAUDCODIGO'] = '';
					$_POSTDADOS2['vIEMPCODIGO'] = 1;
					$_POSTDADOS2['vIAUDMENU'] = 76;
					$_POSTDADOS2['vSAUDANTIGO'] = $campos;
					$_POSTDADOS2['vSAUDNOVO'] = $valores; 
					$_POSTDADOS2['vIAUDIDVINCULO'] = $vIAUDIDVINCULO; 

					$dadosBanco = array(
						'tabela'  => 'AUDITORIA',
						'prefixo' => 'AUD',
						'fields'  => $_POSTDADOS2,
						'msg'     => '',
						'url'     => '',
						'debug'   => 'S'
						);
					$id = insertUpdate($dadosBanco);	

				}
			}
		}
	}
	return  $vSAlteracoes;   
}