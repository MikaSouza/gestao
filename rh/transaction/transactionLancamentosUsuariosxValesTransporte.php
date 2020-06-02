<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateLancamentosUsuariosxValesTransporte($_POST, 'N'); 
	//sweetAlert('', '', 'S', 'cadUsuario.php?method=update&oid='.$vIOid, 'S'); 
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_LancamentosUsuariosxValesTransporte($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$vIMES = $vROBJETO['LUVMES'];
	$vIANO = $vROBJETO['LUVANO'];
} else {
	$vIMES = date('m');
	$vIANO = date('Y');	
}	

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchUsuariosxValesTransporteFilhos')
	listLancamentosUsuariosxValesTransporteFilhos($_POST);

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_LancamentosUsuariosxValesTransporte')
	fill_LancamentosUsuariosxValesTransporte($_GET['vILUVCODIGO'], $_GET['formatoRetorno']);

if (isset($_POST['hdn_metodo']) && $_POST['hdn_metodo'] == 'diasUteisUsuariosxValesTransporte')
	echo dias_uteis($_POST['vILUVMES'], $_POST['vILUVANO']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("LANCAMENTOSUSUARIOSXVALES", 'crud'),
		"prefixo" => "LUV",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function fill_LancamentosUsuariosxValesTransporte($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    LANCAMENTOSUSUARIOSXVALES
				WHERE
                    LUVCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listLancamentosUsuariosxValesTransporte($_POSTDADOS){

	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" AND u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUVMES']))
		$where .=" AND r.LUVMES = ".$_POSTDADOS['FILTROS']['vILUVMES'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUVANO']))
		$where .=" AND r.LUVANO = ".$_POSTDADOS['FILTROS']['vILUVANO'];
	$sql = "SELECT
	                r.*,
					CONCAT(r.LUVMES, '/', r.LUVANO) AS PERIODO,
	                t.TABDESCRICAO AS DEPARTAMENTO, 
					u.USUNOME AS NOMEDEUSUARIO,
					x.UXVVALOR, x.UXVQTDE
	            FROM
	                LANCAMENTOSUSUARIOSXVALES r
				LEFT JOIN USUARIOS u ON u.USUCODIGO = r.USUCODIGO	
				LEFT JOIN USUARIOSXVALESTRANSPORTE x ON u.USUCODIGO = x.USUCODIGO
		       	LEFT JOIN
	                TABELAS t
	            ON
	                t.TABCODIGO = u.TABDEPARTAMENTO
				WHERE
					r.LUVSTATUS = 'S' 
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUVMES']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUVMES'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUVANO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUVANO'], PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateLancamentosUsuariosxValesTransporte($_POSTDADOS, $pSMsg = 'N'){

	// incluir usuario x recebimento
	foreach ($_POSTDADOS['vMLUVVALOR'] as $key => $value) :
		$dadosBeneficios = array(
			'vILUVCODIGO'    => $_POSTDADOS['vIIDS'][$key],
			'vIUSUCODIGO'    => $_POSTDADOS['vIIDSUSUCODIGO'][$key],
			'vILUVMES'   	 => $_POSTDADOS['vILUVMES'],
			'vILUVANO'   	 => $_POSTDADOS['vILUVANO'],
			'vMLUVVALOR'  => $_POSTDADOS['vMLUVVALOR'][$key]
		);		
		
		$dadosBanco = array(
			'tabela'  => 'LANCAMENTOSUSUARIOSXVALES',
			'prefixo' => 'LUV',
			'fields'  => $dadosBeneficios,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);

	endforeach;

	//enviar e-mail
	enviarEmailLancamentosUsuariosxValesTransporte($_POSTDADOS);
	return $id;
}

function listLancamentosUsuariosxValesTransporteFilhos($_POSTDADOS){
	
	$sql = 	'SELECT
				u.USUNOME, u.USUCODIGO, t.TABDESCRICAO AS DEPARTAMENTO, x.UXVVALOR, x.UXVQTDE
			FROM
				USUARIOSXVALESTRANSPORTE x
			LEFT JOIN USUARIOS u ON u.USUCODIGO = x.USUCODIGO		
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO			
			WHERE x.UXVSTATUS = "S"
			AND	u.USUSTATUS = "S"
			AND u.EMPCODIGO = ?	
			AND x.VXTCODIGO = ?	
			AND u.USUDATADEMISSAO IS NULL	
			ORDER BY u.USUNOME';
		$arrayQuery = array(
						'query' => $sql,
						'parametros' => array()
					);
		$arrayQuery['parametros'][] = array($_POSTDADOS['vIEMPCODIGO'], PDO::PARAM_INT);	
		$arrayQuery['parametros'][] = array($_POSTDADOS['vIVXTCODIGO'], PDO::PARAM_INT);	
		$result = consultaComposta($arrayQuery);?>
		<div class="table-responsive">
		<table class="table mb-0">
			<thead class="thead-light">
				<tr>
					<th>Colaborador</th>
					<th>Departamento/Setor</th>
					<th>Quantidade por dia</th>
					<th>Valor Unitário</th>
					<th>Faltas</th>
					<th>Dias Trabalhados</th>
					<th>Valor Compra</th>
				</tr>
			</thead>
			<tbody>			
			<?php $vMLUVVALORCOMPRATOTAL = 0;
			foreach ($result['dados'] as $result) :
				//buscar faltas
				$SqlMain = "SELECT
						l.LUFDIAS
					FROM
						LANCAMENTOSUSUARIOSXFALTAS l
					WHERE
						l.USUCODIGO = ?
					AND l.LUFMES = ?
					AND l.LUFANO = ? 
					AND l.LUFSTATUS = 'S' ";			
				$arrayQueryFilho = array(
						'query' => $SqlMain,
						'parametros' => array()
					);
				$arrayQueryFilho['parametros'][] = array($result['USUCODIGO'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUVMES'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUVANO'], PDO::PARAM_INT);	
				$resultFilho = consultaComposta($arrayQueryFilho);	
				$vILUVDIASFALTAS = 0;
				foreach ($resultFilho['dados'] as $resultFilho) :
					$vILUVDIASFALTAS = $resultFilho['LUFDIAS'];
				endforeach; 
			
				$SqlMain = "SELECT
						l.LUVCODIGO,
						l.LUVANO,
						l.LUVMES,
						l.LUVDIAS,
						l.LUVVALOR
					FROM
						LANCAMENTOSUSUARIOSXVALES l
					WHERE
						l.USUCODIGO = ?
					AND l.LUVMES = ?
					AND l.LUVANO = ? ";
				$arrayQueryFilho = array(
						'query' => $SqlMain,
						'parametros' => array()
					);
				$arrayQueryFilho['parametros'][] = array($result['USUCODIGO'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUVMES'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUVANO'], PDO::PARAM_INT);	
				$resultFilho = consultaComposta($arrayQueryFilho);	
				$vILUVCODIGO = '';
				$vILUVDIAS = '';
				$vMLUVVALORSALARIO = '';
				$vMLUVVALORBONIFICACAO = '';
				foreach ($resultFilho['dados'] as $resultFilho) :
					$vILUVCODIGO = $resultFilho['LUVCODIGO'];
					$vILUVDIAS = $resultFilho['LUVDIAS'];
					$vMLUVVALOR = $resultFilho['LUVVALOR'];
				endforeach; 
				
				$vIQtdeDiasUteis = dias_uteis($_POSTDADOS['vILUVMES'], $_POSTDADOS['vILUVANO']);
				$vIQtdeMes = $result['UXVQTDE'] * ($vIQtdeDiasUteis - $vILUVDIASFALTAS);
				$vMLUVVALORCOMPRA = $result['UXVVALOR'] * $vIQtdeMes;
				$vMLUVVALORCOMPRATOTAL += $vMLUVVALORCOMPRA;
			?>
				<tr>
					<td align="left"><?= $result['USUNOME']; ?></td>
					<td align="left"><?= $result['DEPARTAMENTO']; ?></td> 					
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classnumerico" value="<?= $result['UXVQTDE']; ?>" readonly name="vILUVDIAS[]" id="vILUVDIAS" style="width: 100px; text-align: right">
					</td>
					<td align="right" style="width: 200px;">						
						<input type="text" class="form-control classmonetario" value="<?= formatar_moeda($result['UXVVALOR'], false);?>" readonly name="vMLURVALORBONIFICACAO[]" id="vMLURVALORBONIFICACAO" style="width: 100px; text-align: right">
					</td>
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classnumerico" value="<?= $vILUVDIASFALTAS; ?>" name="vILUVDIAS[]" id="vILUVDIAS" readonly style="width: 100px; text-align: right">
					</td>	
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classnumerico" value="<?= ($vIQtdeDiasUteis - $vILUVDIASFALTAS); ?>" name="vILUVDIAS[]" id="vILUVDIAS" style="width: 100px; text-align: right">
					</td>	
					<td align="right" style="width: 200px;">						
						<input type="text" class="form-control classmonetario" value="<?= formatar_moeda($vMLUVVALORCOMPRA, false);?>" name="vMLUVVALOR[]" id="vMLUVVALOR" style="width: 100px; text-align: right">						
					</td>
				</tr>
				<input type="hidden" value="<?= $vILUVCODIGO;?>" name="vIIDS[]">
				<input type="hidden" value="<?= $result['USUCODIGO'];?>" name="vIIDSUSUCODIGO[]">
			<?php 
			endforeach;
			?>
			<tr>	
				<td align="left" colspan="6"><b>Totais</b></td>
				<td align="right"><b><?= formatar_moeda($vMLUVVALORCOMPRATOTAL, true);?></b></td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php
}

function enviarEmailLancamentosUsuariosxValesTransporte($dados)
{
	$SqlMain = "SELECT
					l.LUVANO,
					l.LUVMES,
					l.LUVDIAS,
					l.LUVVALOR,
					u.USUNOME,
					t.TABDESCRICAO AS DEPARTAMENTO,
					x.UXVVALOR, 
					x.UXVQTDE
				FROM
					LANCAMENTOSUSUARIOSXVALES l
				LEFT JOIN USUARIOS u ON	u.USUCODIGO = l.USUCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO		
				LEFT JOIN USUARIOSXVALESTRANSPORTE x ON u.USUCODIGO = x.USUCODIGO
				WHERE
					l.LUVSTATUS = 'S'
				AND l.LUVMES = ?
				AND l.LUVANO = ? 
			ORDER BY u.USUNOME	";
	$arrayQueryFilho = array(
			'query' => $SqlMain,
			'parametros' => array()
		);
	$arrayQueryFilho['parametros'][] = array($dados['vILUVMES'], PDO::PARAM_INT);	
	$arrayQueryFilho['parametros'][] = array($dados['vILUVANO'], PDO::PARAM_INT);	
	$resultFilho = consultaComposta($arrayQueryFilho);

	$recipients = array();
	
	$Assunto    = 'Lançamento Vales Transporte: '.descricaoMes($dados['vILUVMES']).'/'.$dados['vILUVANO'];

	$Mensagem   = "<b>".$Assunto."</b><br />";
	$Mensagem  .= "<i>\"Por favor não responda a este e-mail, pois esta mensagem é uma aviso automático do sistema.\"<br /></i><br /><br />";
	
	$Mensagem  .= "<table style='border-collapse:collapse;' border='1' width='100%'>
					<thead>
						<tr style = 'background-color:#E8EAE8'>
							<th>Colaborador</th>
							<th>Departamento/Setor</th>
							<th>Dias Trabalhados</th>
							<th>Valor Unitário</th>
							<th>Faltas</th>
							<th>Valor Compra</th>
						</tr>
					</thead>
					<tbody>";
	$i=0; $vMTOTALLUVVALOR = 0;
	foreach ($resultFilho['dados'] as $regemails) :
				
		$vILUVANO = $regemails['LUVANO'];
		$vSLUVMES = descricaoMes($regemails['LUVMES']);
				
		$vSUSUNOME = $regemails['USUNOME'];
		$vSDEPARTAMENTO = $regemails['DEPARTAMENTO'];	
		
		$vILUVDIAS = $regemails['LUVDIAS'];
		$vMLUVVALOR = formatar_moeda($regemails['UXVQTDE']);	
		$vMUXVVALOR = formatar_moeda($regemails['UXVVALOR']);		
		$vMTOTALLUVVALOR += $regemails['LUVVALOR']; 
				
		$recipients[] = 'godinho@teraware.com.br'; //$regemails['USUEMAIL'];

		$style = ($i%2 != 0) ? "style='background-color:#E8EAE8'" : "style=''" ;

		$i++;

		$Mensagem .= "	<tr {$style}>
							<td align='left'>".$vSUSUNOME."</td>
							<td align='left'>".$vSDEPARTAMENTO."</td>
							<td align='right'>".$vILUVDIAS."</td>
							<td align='right'>".$vMUXVVALOR."</td>
							<td align='right'>".$vMLUVVALORBONIFICACAO."</td>
							<td align='right'>".$vMLUVVALOR."</td>
						</tr>";
	endforeach;
	
	$Mensagem .= 	"</tbody>
				<tr style = 'background-color:#E8EAE8'>
							<th colspan='4'>Totais</th>
							<th>".formatar_moeda($vMTOTALVALORBONIFICACAO)."</th>
							<th>".formatar_moeda($vMTOTALLUVVALOR)."</th>
						</tr>
				</table><br /><br />";			    
	$Mensagem .= "Equipe Teraware - Robô Feliz";	
    enviarEmail($recipients, $Assunto, $Mensagem, 'N');
}