<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateLancamentosUsuariosxFaltas($_POST, 'N'); 
	sweetAlert('', '', 'S', 'cadLancamentosUsuariosxFaltas.php?method=update&oid='.$vIOid, 'S'); 
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_LancamentosUsuariosxFaltas($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	$vIMES = $vROBJETO['LUFMES'];
	$vIANO = $vROBJETO['LUFANO'];
} else {
	$vIMES = date('m');
	$vIANO = date('Y');	
}	

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_LancamentosUsuariosxFaltas')
	fill_LancamentosUsuariosxFaltas($_GET['vILUFCODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("LANCAMENTOSUSUARIOSXFALTAS", 'crud'),
		"prefixo" => "LUF",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function fill_LancamentosUsuariosxFaltas($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    LANCAMENTOSUSUARIOSXFALTAS
				WHERE
                    LUFCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listLancamentosUsuariosxFaltas($_POSTDADOS){

	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" AND u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUFMES']))
		$where .=" AND r.LUFMES = ".$_POSTDADOS['FILTROS']['vILUFMES'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUFANO']))
		$where .=" AND r.LUFANO = ".$_POSTDADOS['FILTROS']['vILUFANO'];
	$sql = "SELECT
	                r.*,
					CONCAT(r.LUFMES, '/', r.LUFANO) AS PERIODO,
	                t.TABDESCRICAO AS DEPARTAMENTO, 
					u.USUNOME AS NOMEDEUSUARIO
	            FROM
	                LANCAMENTOSUSUARIOSXFALTAS r
				LEFT JOIN
	               USUARIOS u
	            ON
	                u.USUCODIGO = r.USUCODIGO	
		       	LEFT JOIN
	                TABELAS t
	            ON
	                t.TABCODIGO = u.TABDEPARTAMENTO
				WHERE
					r.LUFSTATUS = 'S' 
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
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUFMES']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUFMES'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vILUFANO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vILUFANO'], PDO::PARAM_INT);	
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateLancamentosUsuariosxFaltas($_POSTDADOS, $pSMsg = 'N'){

	// incluir usuario x recebimento
	foreach ($_POSTDADOS['vILUFDIAS'] as $key => $value) :
		$dadosBeneficios = array(
			'vILUFCODIGO'    => $_POSTDADOS['vIIDS'][$key],
			'vIUSUCODIGO'    => $_POSTDADOS['vIIDSUSUCODIGO'][$key],
			'vILUFMES'   	 => $_POSTDADOS['vILUFMES'],
			'vILUFANO'   	 => $_POSTDADOS['vILUFANO'],
			'vILUFDIAS'  	 => $_POSTDADOS['vILUFDIAS'][$key]
		);		
		
		$dadosBanco = array(
			'tabela'  => 'LANCAMENTOSUSUARIOSXFALTAS',
			'prefixo' => 'LUF',
			'fields'  => $dadosBeneficios,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);

	endforeach;

	//enviar e-mail
	enviarEmailLancamentosUsuariosxFaltas($_POSTDADOS);
	return $id;
}

function listLancamentosUsuariosxFaltasFilhos($_POSTDADOS){
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
					<th>Faltas</th>
				</tr>
			</thead>
			<tbody>			
			<?php 
			foreach ($result['dados'] as $result) :
				$SqlMain = "SELECT
						l.LUFCODIGO,
						l.LUFANO,
						l.LUFMES,
						l.LUFDIAS
					FROM
						LANCAMENTOSUSUARIOSXFALTAS l
					WHERE
						l.USUCODIGO = ?
					AND l.LUFMES = ?
					AND l.LUFANO = ? ";
				$arrayQueryFilho = array(
						'query' => $SqlMain,
						'parametros' => array()
					);
				$arrayQueryFilho['parametros'][] = array($result['USUCODIGO'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUFMES'], PDO::PARAM_INT);	
				$arrayQueryFilho['parametros'][] = array($_POSTDADOS['vILUFANO'], PDO::PARAM_INT);	
				$resultFilho = consultaComposta($arrayQueryFilho);	
				$vILUFCODIGO = '';
				$vILUFDIAS = 0;
				$vMLUFVALORSALARIO = '';
				$vMLUFVALORBONIFICACAO = '';
				foreach ($resultFilho['dados'] as $resultFilho) :
					$vILUFCODIGO = $resultFilho['LUFCODIGO'];
					$vILUFDIAS = $resultFilho['LUFDIAS'];
				endforeach;
				
			?>
				<tr>
					<td align="left"><?= $result['USUNOME']; ?></td>
					<td align="left"><?= $result['DEPARTAMENTO']; ?></td>
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classnumerico" value="<?= $vILUFDIAS;?>" name="vILUFDIAS[]" id="vILUFDIAS" style="width: 100px; text-align: right">
					</td>
				</tr>
				<input type="hidden" value="<?= $vILUFCODIGO;?>" name="vIIDS[]">
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

function enviarEmailLancamentosUsuariosxFaltas($dados)
{
	$SqlMain = "SELECT
					l.LUFANO,
					l.LUFMES,
					l.LUFDIAS,
					u.USUNOME,
					t.TABDESCRICAO AS DEPARTAMENTO
				FROM
					LANCAMENTOSUSUARIOSXFALTAS l
				LEFT JOIN USUARIOS u ON	u.USUCODIGO = l.USUCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO		
				WHERE
					l.LUFSTATUS = 'S'
				AND l.LUFMES = ?
				AND l.LUFANO = ? 
			ORDER BY u.USUNOME	";
	$arrayQueryFilho = array(
			'query' => $SqlMain,
			'parametros' => array()
		);
	$arrayQueryFilho['parametros'][] = array($dados['vILUFMES'], PDO::PARAM_INT);	
	$arrayQueryFilho['parametros'][] = array($dados['vILUFANO'], PDO::PARAM_INT);	
	$resultFilho = consultaComposta($arrayQueryFilho);

	$recipients = array();
	
	$Assunto    = 'Lançamento Faltas: '.descricaoMes($dados['vILUFMES']).'/'.$dados['vILUFANO'];

	$Mensagem   = "<b>".$Assunto."</b><br />";
	$Mensagem  .= "<i>\"Por favor não responda a este e-mail, pois esta mensagem é uma aviso automático do sistema.\"<br /></i><br /><br />";
	
	$Mensagem  .= "<table style='border-collapse:collapse;' border='1' width='100%'>
					<thead>
						<tr style = 'background-color:#E8EAE8'>
							<th>Colaborador</th>
							<th>Departamento/Setor</th>
							<th>Quantidade dia(s)</th>
						</tr>
					</thead>
					<tbody>";
	$i=0; $vMTOTALLUFDIAS = 0;
	foreach ($resultFilho['dados'] as $regemails) :
				
		$vILUFANO = $regemails['LUFANO'];
		$vSLUFMES = descricaoMes($regemails['LUFMES']);
				
		$vSUSUNOME = $regemails['USUNOME'];
		$vSDEPARTAMENTO = $regemails['DEPARTAMENTO'];	
		
		$vILUFDIAS = $regemails['LUFDIAS'];
		$vMTOTALLUFDIAS += $regemails['LUFDIAS']; 
				
		$recipients[] = 'godinho@teraware.com.br'; //$regemails['USUEMAIL'];
		if ($vILUFDIAS > 0){
			$style = ($i%2 != 0) ? "style='background-color:#E8EAE8'" : "style=''" ;

			$i++;

			$Mensagem .= "	<tr {$style}>
								<td align='left'>".$vSUSUNOME."</td>
								<td align='left'>".$vSDEPARTAMENTO."</td>
								<td align='right'>".$vILUFDIAS."</td>
							</tr>";
		}					
	endforeach;
	
	$Mensagem .= 	"</tbody>
				<tr style = 'background-color:#E8EAE8'>
							<th colspan='2'>Totais</th>
							<th>".$vMTOTALLUFDIAS."</th>
						</tr>
				</table><br /><br />";			    
	$Mensagem .= "Equipe Teraware - Robô Feliz";	
    enviarEmail($recipients, $Assunto, $Mensagem, 'N');
}