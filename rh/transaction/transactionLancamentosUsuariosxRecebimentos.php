<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateLancamentosUsuariosxRecebimentos($_POST, 'N'); 
	//sweetAlert('', '', 'S', 'cadUsuario.php?method=update&oid='.$vIOid, 'S'); 
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_LancamentosUsuariosxRecebimentos($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$vIMES = $vROBJETO['LURMES'];
	$vIANO = $vROBJETO['LURANO'];
} else {
	$vIMES = date('m');
	$vIANO = date('Y');	
}	

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_LancamentosUsuariosxRecebimentos')
	fill_LancamentosUsuariosxRecebimentos($_GET['vILURCODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("LANCAMENTOSUSUARIOSXRECEBIMENTOS", 'crud'),
		"prefixo" => "LUR",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function fill_LancamentosUsuariosxRecebimentos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    LANCAMENTOSUSUARIOSXRECEBIMENTOS
				WHERE
                    LURCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listLancamentosUsuariosxRecebimentos($_POSTDADOS){

	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" AND u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILURMES']))
		$where .=" AND r.LURMES = ".$_POSTDADOS['FILTROS']['vILURMES'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILURANO']))
		$where .=" AND r.LURANO = ".$_POSTDADOS['FILTROS']['vILURANO'];
	$sql = "SELECT
	                r.*,
					CONCAT(r.LURMES, '/', r.LURANO) AS PERIODO,
	                t.TABDESCRICAO AS DEPARTAMENTO, 
					u.USUNOME AS NOMEDEUSUARIO
	            FROM
	                LANCAMENTOSUSUARIOSXRECEBIMENTOS r
				LEFT JOIN
	               USUARIOS u
	            ON
	                u.USUCODIGO = r.USUCODIGO	
		       	LEFT JOIN
	                TABELAS t
	            ON
	                t.TABCODIGO = u.TABDEPARTAMENTO
				WHERE
					r.LURSTATUS = 'S' 
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vILURMES']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILURMES'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vILURANO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILURANO'], PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateLancamentosUsuariosxRecebimentos($_POSTDADOS, $pSMsg = 'N'){

	// incluir usuario x recebimento
	foreach ($_POSTDADOS['vMLURVALORSALARIO'] as $key => $value) :
		$dadosBeneficios = array(
			'vILURCODIGO'    => $_POSTDADOS['vIIDS'][$key],
			'vIUSUCODIGO'    => $_POSTDADOS['vIIDSUSUCODIGO'][$key],
			'vILURMES'   	 => $_POSTDADOS['vILURMES'],
			'vILURANO'   	 => $_POSTDADOS['vILURANO'],
			'vMLURVALORBONIFICACAO' => $_POSTDADOS['vMLURVALORBONIFICACAO'][$key],
			'vMLURVALORSALARIO'  => $_POSTDADOS['vMLURVALORSALARIO'][$key]
		);		
		
		$dadosBanco = array(
			'tabela'  => 'LANCAMENTOSUSUARIOSXRECEBIMENTOS',
			'prefixo' => 'LUR',
			'fields'  => $dadosBeneficios,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);

	endforeach;

	//enviar e-mail
	enviarEmailLancamentosUsuariosxRecebimentos($_POSTDADOS);
	return $id;
}

function listLancamentosUsuariosxRecebimentosFilhos($_POSTDADOS){
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
					<th>Dias Trabalhados</th>
					<th>Valor Bonificação</th>
					<th>Valor Salário</th>
				</tr>
			</thead>
			<tbody>			
			<?php 
			foreach ($result['dados'] as $result) :
				$SqlMain = "SELECT
						l.LURCODIGO,
						l.LURANO,
						l.LURMES,
						l.LURDIAS,
						l.LURVALORSALARIO,
						l.LURVALORBONIFICACAO
					FROM
						LANCAMENTOSUSUARIOSXRECEBIMENTOS l
					WHERE
						l.USUCODIGO = ?
					AND l.LURMES = ?
					AND l.LURANO = ? ";
				$arrayQueryFilho = array(
						'query' => $SqlMain,
						'parametros' => array()
					);
				$arrayQueryFilho['parametros'][] = array($result['USUCODIGO'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILURMES'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILURANO'], PDO::PARAM_INT);	
				$resultFilho = consultaComposta($arrayQueryFilho);	
				$vILURCODIGO = '';
				$vILURDIAS = '';
				$vMLURVALORSALARIO = '';
				$vMLURVALORBONIFICACAO = '';
				foreach ($resultFilho['dados'] as $resultFilho) :
					$vILURCODIGO = $resultFilho['LURCODIGO'];
					$vILURDIAS = $resultFilho['LURDIAS'];
					$vMLURVALORSALARIO = $resultFilho['LURVALORSALARIO'];
					$vMLURVALORBONIFICACAO = $resultFilho['LURVALORBONIFICACAO'];
				endforeach;
				
			?>
				<tr>
					<td align="left"><?= $result['USUNOME']; ?></td>
					<td align="left"><?= $result['DEPARTAMENTO']; ?></td>
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classnumerico" value="<?= $vILURDIAS;?>" name="vILUVDIAS[]" id="vILUVDIAS" style="width: 100px; text-align: right">
					</td>
					<td align="right" style="width: 200px;">						
						<input type="text" class="form-control classmonetario" value="<?= formatar_moeda($vMLURVALORBONIFICACAO, false);?>" name="vMLURVALORBONIFICACAO[]" id="vMLURVALORBONIFICACAO" style="width: 100px; text-align: right">
					</td>
					<td align="right" style="width: 200px;">						
						<input type="text" class="form-control classmonetario" value="<?= formatar_moeda($vMLURVALORSALARIO, false);?>" name="vMLURVALORSALARIO[]" id="vMLURVALORSALARIO" style="width: 100px; text-align: right">						
					</td>
				</tr>
				<input type="hidden" value="<?= $vILURCODIGO;?>" name="vIIDS[]">
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

function enviarEmailLancamentosUsuariosxRecebimentos($dados)
{
	$SqlMain = "SELECT
					l.LURANO,
					l.LURMES,
					l.LURDIAS,
					l.LURVALORSALARIO,
					l.LURVALORBONIFICACAO,
					u.USUNOME,
					t.TABDESCRICAO AS DEPARTAMENTO
				FROM
					LANCAMENTOSUSUARIOSXRECEBIMENTOS l
				LEFT JOIN USUARIOS u ON	u.USUCODIGO = l.USUCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO		
				WHERE
					l.LURSTATUS = 'S'
				AND l.LURMES = ?
				AND l.LURANO = ? 
			ORDER BY u.USUNOME	";
	$arrayQueryFilho = array(
			'query' => $SqlMain,
			'parametros' => array()
		);
	$arrayQueryFilho['parametros'][] = array($dados['vILURMES'], PDO::PARAM_INT);	
	$arrayQueryFilho['parametros'][] = array($dados['vILURANO'], PDO::PARAM_INT);	
	$resultFilho = consultaComposta($arrayQueryFilho);

	$recipients = array();
	
	$Assunto    = 'Lançamento Folha de Pagamento: '.descricaoMes($dados['vILURMES']).'/'.$dados['vILURANO'];

	$Mensagem   = "<b>".$Assunto."</b><br />";
	$Mensagem  .= "<i>\"Por favor não responda a este e-mail, pois esta mensagem é uma aviso automático do sistema.\"<br /></i><br /><br />";
	
	$Mensagem  .= "<table style='border-collapse:collapse;' border='1' width='100%'>
					<thead>
						<tr style = 'background-color:#E8EAE8'>
							<th>Colaborador</th>
							<th>Departamento/Setor</th>
							<th>Dias Trabalhados</th>
							<th>Valor Bonificação</th>
							<th>Valor Salário</th>
						</tr>
					</thead>
					<tbody>";
	$i=0; $vMTOTALVALORBONIFICACAO = 0; $vMTOTALVALORSALARIO = 0;
	foreach ($resultFilho['dados'] as $regemails) :
				
		$vILURANO = $regemails['LURANO'];
		$vSLURMES = descricaoMes($regemails['LURMES']);
				
		$vSUSUNOME = $regemails['USUNOME'];
		$vSDEPARTAMENTO = $regemails['DEPARTAMENTO'];	
		
		$vILURDIAS = $regemails['LURDIAS'];
		$vMLURVALORBONIFICACAO = formatar_moeda($regemails['LURVALORBONIFICACAO']);
		$vMLURVALORSALARIO = formatar_moeda($regemails['LURVALORSALARIO']);
		$vMTOTALVALORBONIFICACAO += $regemails['LURVALORBONIFICACAO']; 
		$vMTOTALVALORSALARIO += $regemails['LURVALORSALARIO'];
				
		$recipients[] = 'godinho@teraware.com.br'; //$regemails['USUEMAIL'];

		$style = ($i%2 != 0) ? "style='background-color:#E8EAE8'" : "style=''" ;

		$i++;

		$Mensagem .= "	<tr {$style}>
							<td align='left'>".$vSUSUNOME."</td>
							<td align='left'>".$vSDEPARTAMENTO."</td>
							<td align='right'>".$vILURDIAS."</td>
							<td align='right'>".$vMLURVALORBONIFICACAO."</td>
							<td align='right'>".$vMLURVALORSALARIO."</td>
						</tr>";
	endforeach;
	
	$Mensagem .= 	"</tbody>
				<tr style = 'background-color:#E8EAE8'>
							<th colspan='3'>Totais</th>
							<th>".formatar_moeda($vMTOTALVALORBONIFICACAO)."</th>
							<th>".formatar_moeda($vMTOTALVALORSALARIO)."</th>
						</tr>
				</table><br /><br />";			    
	$Mensagem .= "Equipe Teraware - Robô Feliz";	
    enviarEmail($recipients, $Assunto, $Mensagem, 'N');
}