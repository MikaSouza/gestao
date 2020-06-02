<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateLancamentosUsuariosxBeneficios($_POST, 'N'); 
	//sweetAlert('', '', 'S', 'cadUsuario.php?method=update&oid='.$vIOid, 'S'); 
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Usuario($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'LancamentosUsuariosxBeneficios')
	listLancamentosUsuariosxBeneficiosFilhos($_POST['pIOID'], 'LancamentosUsuariosxBeneficios');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_LancamentosUsuariosxBeneficios')
	fill_LancamentosUsuariosxBeneficios($_GET['vIUXBCODIGO'], $_GET['formatoRetorno']);

if(isset($_POST["method"]) && $_POST["method"] == 'excluirFilho') {
	$config_excluir = array(
		"tabela" => Encriptar("LANCAMENTOSUSUARIOSXBENEFICIOS", 'crud'),
		"prefixo" => "UXB",
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N",
	);
	echo excluirAtivarRegistros($config_excluir);
}

function fill_LancamentosUsuariosxBeneficios($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    LANCAMENTOSUSUARIOSXBENEFICIOS
				WHERE
                    UXBCODIGO = {$pOid}";
	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}

function listLancamentosUsuariosxBeneficios(){

	$sql = "SELECT
	                r.*,
	                t.TABDESCRICAO AS BENEFICIO,
					u.USUNOME AS NOMEDEUSUARIO
	            FROM
	                LANCAMENTOSUSUARIOSXBENEFICIOS r
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

function insertUpdateLancamentosUsuariosxBeneficios($_POSTDADOS, $pSMsg = 'N'){

	$ultimo_dia = date("t", mktime(0,0,0, $_POSTDADOS['vILUBMES'],'01', $_POSTDADOS['vILUBANO']));

	print_r($_POSTDADOS);
	return;

	// incluir uma carcaça e um custo
	foreach ($_POSTDADOS['vMVALOR'] as $key => $value) :
		if ($_POSTDADOS['vIIDSUSUCODIGO'][$key] > 0) { // update carcaca
			$dadosBeneficios = array(
				'vIUSUCODIGO'    => $_POSTDADOS['vIIDSUSUCODIGO'][$key],
				'vMLUBVALOR'   => $_SESSION['SI_EMPCODIGO'],
				'vSLUBDATAVENCIMENTO' => $ultimo_dia,
				'vICARIDMEDIDA'  => $_POSTDADOS['vIIDS'][$key]
			);
		} else {
			$dadosBeneficios = array(
				'vICARCODIGO'    => '',
				'vICAREMPRESA'   => $_SESSION['SI_EMPCODIGO'],
				'vSCARDESCRICAO' => 'IGUAL MEDIDA',
				'vICARIDMEDIDA'  => $_POSTDADOS['vIIDS'][$key]
			);
		}
		
		$dadosBanco = array(
			'tabela'  => 'LANCAMENTOSUSUARIOSXBENEFICIOS',
			'prefixo' => 'UXB',
			'fields'  => $dadosBeneficios,
			'msg'     => $pSMsg,
			'url'     => '',
			'debug'   => 'N'
			);
		$id = insertUpdate($dadosBanco);

	endforeach;

	return;
}

function listLancamentosUsuariosxBeneficiosFilhos($vIOIDPAI, $tituloModal){
	$sql = 	'SELECT
				u.USUNOME, u.USUCODIGO
			FROM
				USUARIOS u	
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
					<th>Valor</th>
				</tr>
			</thead>
			<tbody>			
			<?php 
			$vConexao = sql_conectar_banco(vGBancoSite);
			foreach ($result['dados'] as $result) :	?>
				<tr>
					<td><?= $result['USUNOME']; ?></td>
					<td align="right" style="width: 200px;">
						<input type="text" class="form-control classmonetario2casas" value="" name="vMVALOR[]" id="vMVALOR" style="width: 100px; text-align: right">
						<input type="hidden" value="<?= $arrayGrid['MEDCODIGO'];?>" name="vIIDS[]">
						<input type="hidden" value="<?= $arrayGrid['USUCODIGO'];?>" name="vIIDSUSUCODIGO[]">
					</td>
				</tr>
				<input type="hidden" name="vLOidLista[]" id="vLOidLista" value="<?= $result['MENCODIGO'];?>" />
				<input type="hidden" name="vLACECODIGO[]" id="vLACECODIGO" value="<?= $vIACECODIGO;?>" />
			<?php 
			endforeach;
			?>
			</tbody>
		</table>
	</div>
	<?php
	
	return;
}
