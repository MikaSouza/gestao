<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'search_AtendimentoxContato')
	search_AtendimentoxContato($_POST['vIATECODIGO'], $_POST['vICLICODIGO'], $_POST['vICONCODIGOS'], $_POST['editavel'], $_POST['vSAXCRESPONSAVEL']);

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_AtendimentosxContatos')
	fill_AtendimentosxContatos($_GET['vIAXCCODIGO'], $_GET['formatoRetorno']);

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
		"tabela" => Encriptar("ATENDIMENTOSXCONTATOS", 'crud'),
		"prefixo" => "AXC",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

if(isset($_POST["method"]) && $_POST["method"] == 'incluirAtendimentosxContatos'){
	$remuneracao['vIATECODIGO'] 		 	  	 = $_POST['hdn_oid'];
	$remuneracao['vICLICODIGO'] 		 	  	 = $_POST['hdnOIDCLICODIGO'];	
	$remuneracao['vICONCODIGO']   				 = $_POST['vICONCODIGO'];
	$remuneracao['vSAXCRESPONSAVEL'] 	 		 = $_POST['pSAXCRESPONSAVEL'];
	$remuneracao['vIEMPCODIGO']                  = 1;

	$vIOID = insertUpdateAtendimentosxContatos($remuneracao, 'N');
	echo $vIOID;
}

function insertUpdateAtendimentosxContatos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ATENDIMENTOSXCONTATOS',
		'prefixo' => 'AXC',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'S'
		);
	$id = insertUpdate($dadosBanco);
	return $id;
}

function fill_AtendimentosxContatos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = "SELECT
					*
				FROM
					ATENDIMENTOSXCONTATOS
				WHERE
					AXCCODIGO = $pOid";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	$registro['AXCSALARIOATUAL']            = formatar_moeda($registro['AXCSALARIOATUAL'], false);
	$registro['AXCMOTIVOALTERACAOSALARIAL'] = $registro['AXCMOTIVOALTERACAOSALARIAL'];


	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);

	return $registro !== null ? $registro : "N";
}

function listAtendimentosxContatos(){

	$sql = "SELECT
				r.AXCSALARIOATUAL AS SALARIOATUAL,
				r.AXCDATAALTERACAOSALARIAL,
				r.AXCMOTIVOALTERACAOSALARIAL,
	            u.USUNOME AS NOMEDEUSUARIO
			FROM
				ATENDIMENTOSXCONTATOS r
            LEFT JOIN
                USUARIOS u
            ON
                r.USUCODIGO = u.USUCODIGO
			WHERE r.AXCSTATUS = 'S' ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function listAtendimentosxContatosFilhos($vIOIDPAI, $tituloModal){ 
	$sql = "SELECT
				r.*,
				( SELECT u1.USUNOME FROM USUARIOS u1 WHERE u1.USUCODIGO = r.AXCATENDENTENOVO ) AS ATENDENTE_NOVO,
				( SELECT p.POPNOME FROM POSICOESPADROES p WHERE p.POPCODIGO = r.POPCODIGO ) AS POSICAO_NOME
			FROM
				ATENDIMENTOSXCONTATOS r
			WHERE r.AXCSTATUS = 'S' AND
			r.ATECODIGO = ".$vIOIDPAI;
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionAtendimentosxContatos.php";
	$vAConfig['DIV_RETORNO'] = "div_historico";
	$vAConfig['FUNCAO_RETORNO'] = "AtendimentosxContatos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['vATitulos']     = array('', 'Inclusão', 'Atendente', 'Posição', 'Descrição');
	$vAConfig['vACampos']      = array('AXCCODIGO', 'AXCUSU_INC', 'ATENDENTE_NOVO', 'POSICAO_NOME', 'AXCDESCRICAO');
	$vAConfig['vATipos']       = array('chave', 'varchar', 'varchar', 'varchar', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php'; 
	return;
}

function insert_update_AtendimentosxContatosLote($_POSTDADOS){

	$responsavel = "";			
	foreach( $pACONCODIGO as $pICONCODIGO ){
		
		$responsavel = $pIAXCRESPONSAVEL == $pICONCODIGO ? 'S' : 'N'; 
				
		$vACONTATO = array(
					'vICONCODIGO' => $pICONCODIGO,
					'vSAXCRESPONSAVEL' => $responsavel,					
					'vIEMPCODIGO' => $_SESSION['SI_USU_EMPRESA'],					
					'vICLICODIGO' => $pICLICODIGO,
					'vIATECODIGO' => $pIATECODIGO,
				); 
		insertUpdateAtendimentosxContatos($vACONTATO);
	}	
	
} 

function search_AtendimentoxContato($vIATECODIGO, $vICLICODIGO, $vICONCODIGOS = null, $editavel = 'S', $vSAXCRESPONSAVEL = null){

	$Sql = "SELECT
				c.CONNOME,
				c.CONEMAIL,
				c.CONFONE,
				c.CONCELULAR,
				c.CONCODIGO,
				c.CONSTATUS,
				c.CONCARGO,";
				
	if( $vIATECODIGO != null && $vIATECODIGO != '')
		$Sql .=	"a.AXCRESPONSAVEL, ";	
				
	$Sql .=		"cli.CLIFONE
			FROM
				CONTATOS c
			LEFT JOIN
				CLIENTES cli
			ON
				cli.CLICODIGO = c.CLICODIGO

				";
	if( $vIATECODIGO != null && $vIATECODIGO != ''){			
		$Sql .=	"
				LEFT JOIN
					ATENDIMENTOSXCONTATOS a
				ON
					a.CONCODIGO = c.CONCODIGO";
	}
	$Sql .=	"	WHERE
					c.CONSTATUS = 'S'";
					
	if( $vIATECODIGO != null && $vIATECODIGO != '')
		$Sql .= "AND a.AXCSTATUS = 'S'";	
					
	$Sql .="	AND
					c.CLICODIGO = ".$vICLICODIGO."
				AND ";
				
	if(($vIATECODIGO != null) && ($vIATECODIGO != "" ))
		$Sql .= "a.ATECODIGO = " . $vIATECODIGO;
	if(($vICONCODIGOS != null) && ($vICONCODIGOS != ""))
		$Sql .= "c.CONCODIGO IN (" . $vICONCODIGOS . ")";
	
	if(($vIATECODIGO == "") && ($vICONCODIGOS == "")){ 
		echo "";
		return false;
	}
	$arrayQuery = array(
					'query' => $Sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);
	//pre($result); 
	?>
	<div class="card">
		<div class="card-body">
			<div class="table-responsive mt-4">
				<table class="table mb-0">
					<thead class="thead-light">
					<tr>
						<th>Excluir</th>
						<th>Nome</th>
						<th>Contato</th>
					</tr>
					</thead>
					<tbody>
					<?php $vSHiddenButtons = ""; 
					foreach ($result['dados'] as $result): ?>
					<?php $vSHiddenButtons .= "<input type='hidden' name='vACONCODIGO[]' id='vACONCODIGO".$result['CONCODIGO']."' value='".$result['CONCODIGO']."' />";?>
					<tr>
						<th scope="row">Dasktops</th>
						<td><?= $result['CONNOME']; ?></td>
						<td><?= $result['CONEMAIL']; ?></td>
					</tr>
					<?php endforeach; ?>					
					</tbody>
					<div id="hiddencontatos"><?php echo $vSHiddenButtons; ?></div>
				</table><!--end /table-->
			</div>
		</div><!--end card-body-->
	</div><!--end card-->
	<?php return $result;	
}
?>