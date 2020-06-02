<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateLancamentosUsuariosxAdiantamentos($_POST, 'N'); 
	sweetAlert('', '', 'S', 'cadLancamentosUsuariosxAdiantamentos.php?method=update&oid='.$vIOid, 'S'); 
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_LancamentosUsuariosxAdiantamentos($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$vIMES = $vROBJETO['LUAMES'];
	$vIANO = $vROBJETO['LUAANO'];
} else {
	$vIMES = date('m');
	$vIANO = date('Y');	
}	

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_LancamentosUsuariosxAdiantamentos')
	fill_LancamentosUsuariosxAdiantamentos($_GET['vILUACODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("LANCAMENTOSUSUARIOSXADIANTAMENTOS", 'crud'),
		"prefixo" => "LUA",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function fill_LancamentosUsuariosxAdiantamentos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    LANCAMENTOSUSUARIOSXADIANTAMENTOS
				WHERE
                    LUACODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listLancamentosUsuariosxAdiantamentos($_POSTDADOS){

	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" AND u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUAMES']))
		$where .=" AND r.LUAMES = ".$_POSTDADOS['FILTROS']['vILUAMES'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUAANO']))
		$where .=" AND r.LUAANO = ".$_POSTDADOS['FILTROS']['vILUAANO'];
	$sql = "SELECT
	                r.*,
					CONCAT(r.LUAMES, '/', r.LUAANO) AS PERIODO,
	                t.TABDESCRICAO AS DEPARTAMENTO, 
					u.USUNOME AS NOMEDEUSUARIO
	            FROM
	                LANCAMENTOSUSUARIOSXADIANTAMENTOS r
				LEFT JOIN
	               USUARIOS u
	            ON
	                u.USUCODIGO = r.USUCODIGO	
		       	LEFT JOIN
	                TABELAS t
	            ON
	                t.TABCODIGO = u.TABDEPARTAMENTO
				WHERE
					r.LUASTATUS = 'S' 
				".	$where	."	";

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
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUAMES']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUAMES'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUAANO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUAANO'], PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateLancamentosUsuariosxAdiantamentos($_POSTDADOS, $pSMsg = 'N'){

	// incluir usuario x recebimento
	foreach ($_POSTDADOS['vMLUAVALOR'] as $key => $value) :
		$dadosBeneficios = array(
			'vILUACODIGO'    => $_POSTDADOS['vIIDS'][$key],
			'vIUSUCODIGO'    => $_POSTDADOS['vIIDSUSUCODIGO'][$key],
			'vILUAMES'   	 => $_POSTDADOS['vILUAMES'],
			'vILUAANO'   	 => $_POSTDADOS['vILUAANO'],
			'vMLUAVALOR' => $_POSTDADOS['vMLUAVALOR'][$key],
		);		
		
		$dadosBanco = array(
			'tabela'  => 'LANCAMENTOSUSUARIOSXADIANTAMENTOS',
			'prefixo' => 'LUA',
			'fields'  => $dadosBeneficios,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);

	endforeach;

	//enviar e-mail
	enviarEmailLancamentosUsuariosxAdiantamentos($_POSTDADOS);
	return $id;
}

function listLancamentosUsuariosxAdiantamentosFilhos($_POSTDADOS){
	$sql = 	'SELECT
				u.USUNOME, u.USUCODIGO, t.TABDESCRICAO AS DEPARTAMENTO
			FROM
				USUARIOS u	
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO			
			WHERE
				u.USUSTATUS = "S"
			AND u.USUDATADEMISSAO IS NULL	
			ORDER BY u.USUNOME';
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		$result = consultaComposta($arrayQuery);?>
		<div class="table-responsive">
		<table class="table mb-0">
			<thead class="thead-light">
				<tr>
					<th>Colaborador</th>
					<th>Departamento/Setor</th>
					<th>Valor Adiantamento</th>
				</tr>
			</thead>
			<tbody>			
			<?php 
			foreach ($result['dados'] as $result) :
				$SqlMain = "SELECT
						l.LUACODIGO,
						l.LUAANO,
						l.LUAMES,
						l.LUAVALOR
					FROM
						LANCAMENTOSUSUARIOSXADIANTAMENTOS l
					WHERE
						l.USUCODIGO = ?
					AND l.LUAMES = ?
					AND l.LUAANO = ? ";
				$arrayQueryFilho = array(
						'query' => $SqlMain,
						'parametros' => array()
					);
				$arrayQueryFilho['parametros'][] = array($result['USUCODIGO'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUAMES'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUAANO'], PDO::PARAM_INT);	
				$resultFilho = consultaComposta($arrayQueryFilho);	
				$vILUACODIGO = '';
				$vMLUAVALOR = '';
				foreach ($resultFilho['dados'] as $resultFilho) :
					$vILUACODIGO = $resultFilho['LUACODIGO'];
					$vMLUAVALOR = $resultFilho['LUAVALOR'];
				endforeach;
				
			?>
				<tr>
					<td align="left"><?= $result['USUNOME']; ?></td>
					<td align="left"><?= $result['DEPARTAMENTO']; ?></td>
					<td align="right" style="width: 200px;">						
						<input type="text" class="form-control classmonetario" value="<?= formatar_moeda($vMLUAVALOR, false);?>" name="vMLUAVALOR[]" id="vMLUAVALOR" style="width: 100px; text-align: right">
					</td>
				</tr>
				<input type="hidden" value="<?= $vILUACODIGO;?>" name="vIIDS[]">
				<input type="hidden" value="<?= $result['USUCODIGO'];?>" name="vIIDSUSUCODIGO[]">
			<?php 
			endforeach;
			?>
			</tbody>
		</table>
	</div>
	<?php
	
	return;
}

function enviarEmailLancamentosUsuariosxAdiantamentos($dados)
{
	$SqlMain = "SELECT
					l.LUAANO,
					l.LUAMES,
					l.LUAVALOR,
					u.USUNOME,
					t.TABDESCRICAO AS DEPARTAMENTO
				FROM
					LANCAMENTOSUSUARIOSXADIANTAMENTOS l
				LEFT JOIN USUARIOS u ON	u.USUCODIGO = l.USUCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO		
				WHERE
					l.LUASTATUS = 'S'
				AND l.LUAMES = ?
				AND l.LUAANO = ? 
			ORDER BY u.USUNOME	";
	$arrayQueryFilho = array(
			'query' => $SqlMain,
			'parametros' => array()
		);
	$arrayQueryFilho['parametros'][] = array($dados['vILUAMES'], PDO::PARAM_INT);	
	$arrayQueryFilho['parametros'][] = array($dados['vILUAANO'], PDO::PARAM_INT);	
	$resultFilho = consultaComposta($arrayQueryFilho);

	$recipients = array();
	
	$Assunto    = 'Lançamento Adiantamento Folha de Pagamento: '.descricaoMes($dados['vILUAMES']).'/'.$dados['vILUAANO'];

	$Mensagem   = "<b>".$Assunto."</b><br />";
	$Mensagem  .= "<i>\"Por favor não responda a este e-mail, pois esta mensagem é uma aviso automático do sistema.\"<br /></i><br /><br />";
	
	$Mensagem  .= "<table style='border-collapse:collapse;' border='1' width='100%'>
					<thead>
						<tr style = 'background-color:#E8EAE8'>
							<th>Colaborador</th>
							<th>Departamento/Setor</th>
							<th>Valor Adiantamento</th>
						</tr>
					</thead>
					<tbody>";
	$i=0; $vMTOTALLUAVALOR = 0;
	foreach ($resultFilho['dados'] as $regemails) :
				
		$vILUAANO = $regemails['LUAANO'];
		$vSLUAMES = descricaoMes($regemails['LUAMES']);
				
		$vSUSUNOME = $regemails['USUNOME'];
		$vSDEPARTAMENTO = $regemails['DEPARTAMENTO'];	
		
		$vMLUAVALOR = formatar_moeda($regemails['LUAVALOR']);
		$vMTOTALLUAVALOR += $regemails['LUAVALOR']; 
				
		$recipients[] = 'godinho@teraware.com.br'; //$regemails['USUEMAIL'];

		$style = ($i%2 != 0) ? "style='background-color:#E8EAE8'" : "style=''" ;

		$i++;

		$Mensagem .= "	<tr {$style}>
							<td align='left'>".$vSUSUNOME."</td>
							<td align='left'>".$vSDEPARTAMENTO."</td>
							<td align='right'>".$vMLUAVALOR."</td>
						</tr>";
	endforeach;
	
	$Mensagem .= 	"</tbody>
				<tr style = 'background-color:#E8EAE8'>
							<th colspan='2'>Totais</th>
							<th>".formatar_moeda($vMTOTALLUAVALOR)."</th>
						</tr>
				</table><br /><br />";			    
	$Mensagem .= "Equipe Teraware - Robô Feliz";	
    enviarEmail($recipients, $Assunto, $Mensagem, 'N');
}