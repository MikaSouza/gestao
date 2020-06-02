<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {	
    $vIOid = insertUpdateGrupoAcessos($_POST, 'N'); 
	sweetAlert('', '', 'S', 'cadGrupoAcessos.php?method=update&oid='.$vIOid, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_GrupoAcessos($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'GrupoAcessosxAcessos')
	listGrupoAcessosxAcessos($_POST['pIOID']);

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

//busca dados de acesso do grupo de acesso, através do javascript cadUsuario.js
if (isset($_GET['method']) && $_GET['method'] == 'searchGruposAcessosUsuarios') {
	$vConexao = sql_conectar_banco(vGBancoSite);
	$SqlAcessos = "SELECT
						g.MENCODIGO as ACESSOCOD,
						g.GXACONSULTA as CONSULTA,
						g.GXAINCLUSAO as INCLUSAO,
						g.GXAALTERACAO as ALTERACAO,
						g.GXAEXCLUSAO as EXCLUSAO
					FROM GRUPOSACESSO a,
						GRUPOSACESSOSXACESSOS g
					WHERE
						a.GRACODIGO = g.GRACODIGO AND 
						g.GXASTATUS = 'S' AND 
						a.GRAPERFIL = ".$_GET['pSPerfil'];
	$vConexao = sql_conectar_banco();
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao, $SqlAcessos);
	$vAAcessos = array();
	$i = 0;

	while($reg_RS = sql_retorno_lista($RS_MAIN)) {
		$vSACESSOCOD = $reg_RS['ACESSOCOD'];
		$vSCONSULTA = $reg_RS['CONSULTA'];
		$vSINCLUSAO = $reg_RS['INCLUSAO'];
		$vSALTERACAO = $reg_RS['ALTERACAO'];
		$vSEXCLUSAO = $reg_RS['EXCLUSAO'];		

		$vAAcessos[$i] = array(
							'ACESSOCOD' => $vSACESSOCOD,
							'CONSULTA' => $vSCONSULTA,
							'INCLUSAO' => $vSINCLUSAO,
							'ALTERACAO' => $vSALTERACAO,
							'EXCLUSAO' => $vSEXCLUSAO
		);
		$i = $i+1;
	}

	echo json_encode($vAAcessos);
}

function listGrupoAcessos(){

	$sql = "SELECT
                g.*, t.TABDESCRICAO AS GRUPO
			FROM
				GRUPOSACESSO g 
			LEFT JOIN TABELAS t ON t.TABCODIGO = g.GRAPERFIL	
			WHERE
				g.GRASTATUS = 'S' 
			ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function fill_GrupoAcessos($pOid, $formatoRetorno = 'array'){
	$SqlMain = "SELECT
                    *
                FROM GRUPOSACESSO
                    WHERE GRACODIGO  =".$pOid;	
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}

function insertUpdateLoteUsuariosxAcessos($_POSTDADOS, $vIGRACODIGO){
	// incluir acessos novos
	foreach ($_POSTDADOS['vLOidLista'] as $key => $value) 
	{		
		if(in_array($value, $_POSTDADOS['vKConsulta']))	$dadosAcesso['vSGXACONSULTA'] = 'S'; else $dadosAcesso['vSGXACONSULTA'] = 'N';	
		if(in_array($value, $_POSTDADOS['vKInclusao']))	$dadosAcesso['vSGXAINCLUSAO'] = 'S'; else $dadosAcesso['vSGXAINCLUSAO'] = 'N';	
		if(in_array($value, $_POSTDADOS['vKAlteracao'])) $dadosAcesso['vSGXAALTERACAO'] = 'S'; else $dadosAcesso['vSGXAALTERACAO'] = 'N';	
		if(in_array($value, $_POSTDADOS['vKExclusao']))	$dadosAcesso['vSGXAEXCLUSAO'] = 'S'; else $dadosAcesso['vSGXAEXCLUSAO'] = 'N';	
					
		$dadosAcesso['vIGRACODIGO'] = $vIGRACODIGO;
		$dadosAcesso['vIMENCODIGO'] = $value;
		$dadosAcesso['vIGXACODIGO'] = $_POSTDADOS['vLGXACODIGO'][$key];
		insertUpdateGrupoAcessosxAcessos($dadosAcesso, 'N');
	}
	return;
}

function insertUpdateGrupoAcessos($_POSTDADOS, $pSMsg = 'N'){

	$dadosBanco = array(
		'tabela'  => 'GRUPOSACESSO',
		'prefixo' => 'GRA',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);	
	
	insertUpdateLoteUsuariosxAcessos($_POSTDADOS, $id);
	
	return $id; 
}

function insertUpdateGrupoAcessosxAcessos($dados, $pSMsg = 'N')
{
	$dadosBanco = array(
		'tabela'  => 'GRUPOSACESSOSXACESSOS',
		'prefixo' => 'GXA',
		'fields'  => $dados,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);

	$id = insertUpdate($dadosBanco);
	return $id;
}

function listGrupoAcessosxAcessos($vIGRACODIGO){

	$sql = "SELECT DISTINCT
					t.MENCODIGO,
					t.MENGRUPO,
					t.MENTITULO,
					(CASE WHEN t.MENGRUPO = t.MENTITULO THEN
						t.MENGRUPO
					ELSE
						CONCAT(t.MENGRUPO, ' - ', t.MENTITULO)
					END) as COL_ORDER
				FROM
					MENUS t
				WHERE
					t.MENSTATUS = 'S' AND
					t.MENVISIVEL = 'S'
			ORDER BY COL_ORDER";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	$result = consultaComposta($arrayQuery);	
	?>
	<div class="table-responsive">
		<table class="table mb-0">
			<thead class="thead-light">
				<tr>
					<th>Acesso</th>
					<th>Consulta</th>
					<th>Inclusão</th>
					<th>Alteração</th>
					<th>Exclusão</th>
				</tr>
			</thead>
			<tbody>			
			<?php 
			$vConexao     = sql_conectar_banco(vGBancoSite);
			foreach ($result['dados'] as $result) :
			
				$SqlAcesso = "Select
                                    GXACODIGO,
                                    GXAINCLUSAO,
                                    GXAALTERACAO,
                                    GXAEXCLUSAO,
                                    GXACONSULTA								
								From
                                    GRUPOSACESSOSXACESSOS  
								Where
									GXASTATUS = 'S' AND
                                    MENCODIGO = '".$result['MENCODIGO']."' and
                                    GRACODIGO = '".$vIGRACODIGO."' ";				
				$RS_ACESSO = sql_executa(vGBancoSite, $vConexao,$SqlAcesso,FALSE);
				$vSConsulta = 'N';
				$vSInclusao = 'N';
				$vSAlteracao = 'N';
				$vSExclusao = 'N';				
				$vSStatusInicialConsulta = '';
				$vSStatusInicialInclusao = '';
				$vSStatusInicialExclusao = '';
				$vSStatusInicialAlteracao = '';
				$vIGXACODIGO = '';
				while($reg_acesso = sql_retorno_lista($RS_ACESSO)){
					$vSConsulta = $reg_acesso['GXACONSULTA'];
					$vSInclusao = $reg_acesso['GXAINCLUSAO'];
					$vSAlteracao = $reg_acesso['GXAALTERACAO'];
					$vSExclusao = $reg_acesso['GXAEXCLUSAO'];
					$vIGXACODIGO = $reg_acesso['GXACODIGO'];
				}						
				if($result['MENGRUPO'] == $result['MENTITULO'])
					$vSNomenclatura = "<b>".$result['MENGRUPO']."</b>";
				else
					$vSNomenclatura = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ".$result['MENTITULO'];
			?>
				<tr>
					<td><?php echo $vSNomenclatura; ?></td>
					<td>
						<div class="checkbox checkbox-success">
							<input type="checkbox" name="vKConsulta[]" id="vKConsulta<?= $result['MENCODIGO']; ?>" <?php if($_GET['metodo'] == 'consultar')  echo "disabled";?> <?php if($vSConsulta == 'S') echo "checked";?> value="<?php echo $result['MENCODIGO'];?>" />							
							<label for="vKConsulta<?= $result['MENCODIGO']; ?>"></label>
						</div>
				   </td>
					<td>
						<div class="checkbox checkbox-primary">
							<input type="checkbox" name="vKInclusao[]" id="vKInclusao<?php echo $result['MENCODIGO']; ?>" <?php if($_GET['metodo'] == 'consultar')  echo "disabled";?>  <?php if($vSInclusao == 'S') echo "checked";?> value="<?php echo $result['MENCODIGO'];?>" />
							<label for="vKInclusao<?php echo $result['MENCODIGO']; ?>"></label>
						</div>
					</td>
					<td>
						<div class="checkbox checkbox-warning">
							<input type="checkbox" name="vKAlteracao[]" id="vKAlteracao<?php echo $result['MENCODIGO']; ?>" <?php if($_GET['metodo'] == 'consultar')  echo "disabled";?> <?php if($vSAlteracao == 'S') echo "checked";?> value="<?php echo $result['MENCODIGO'];?>" />
							<label for="vKAlteracao<?php echo $result['MENCODIGO']; ?>"></label>
						</div>
					</td>
					<td>
						<div class="checkbox checkbox-danger">
							<input type="checkbox" name="vKExclusao[]" id="vKExclusao<?php echo $result['MENCODIGO']; ?>" <?php if($_GET['metodo'] == 'consultar')  echo "disabled";?> <?php if($vSExclusao == 'S') echo "checked";?> value="<?php echo $result['MENCODIGO'];?>" />
							<label for="vKExclusao<?php echo $result['MENCODIGO']; ?>"></label>
						</div>
				   </td>
				</tr>
				<input type="hidden" name="vLOidLista[]" id="vLOidLista" value="<?= $result['MENCODIGO'];?>" />
				<input type="hidden" name="vLGXACODIGO[]" id="vLGXACODIGO" value="<?= $vIGXACODIGO;?>" />
			<?php 
			endforeach;
			?>
			</tbody>
		</table>
	</div>
	<?php
	
	return;
}