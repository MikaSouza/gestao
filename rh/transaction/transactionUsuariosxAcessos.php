<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'UsuariosxAcessos')
	listUsuariosxAcessos($_POST['pIOID'], 'UsuariosxAcessos');

if (isset($_GET['hdn_metodo_fill']) && $_GET['hdn_metodo_fill'] == 'fill_UsuariosxAcessos')
	fill_ContasReceberxParcelas($_GET['ACECODIGO'], $_GET['formatoRetorno']);

if (isset($_POST["method"]) && $_POST["method"] == 'excluirACE') {
	echo excluirAtivarRegistros(array(
        "tabela"   => Encriptar("ACESSOS", 'crud'),
        "prefixo"  => "ACE",
        "status"   => "N",
        "ids"      => $_POST['pSCodigos'],
        "mensagem" => "S",
        "empresa"  => "S",
    ));
}

function listUsuariosxAcessos($vIUSUCODIGO, $tituloModal){

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
                                    ACECODIGO,
                                    ACEINCLUSAO,
                                    ACEALTERACAO,
                                    ACEEXCLUSAO,
                                    ACECONSULTA,
									ACEEXPORTAR									
								From
                                    ACESSOS  
								Where
                                    ACETABELA = '".$result['MENCODIGO']."' and
                                    USUCODIGO = '".$vIUSUCODIGO."' ";

				$RS_ACESSO = sql_executa(vGBancoSite, $vConexao,$SqlAcesso,FALSE);
				$vSConsulta = 'N';
				$vSInclusao = 'N';
				$vSAlteracao = 'N';
				$vSExclusao = 'N';
				$vSExportar = 'N';
				$vSStatusInicialConsulta = '';
				$vSStatusInicialInclusao = '';
				$vSStatusInicialExclusao = '';
				$vSStatusInicialAlteracao = '';
				$vSStatusInicialExportar = '';				
				$vIACECODIGO = '';
				while($reg_acesso = sql_retorno_lista($RS_ACESSO)){
					$vSConsulta = $reg_acesso['ACECONSULTA'];
					$vSInclusao = $reg_acesso['ACEINCLUSAO'];
					$vSAlteracao = $reg_acesso['ACEALTERACAO'];
					$vSExclusao = $reg_acesso['ACEEXCLUSAO'];
					$vIACECODIGO = $reg_acesso['ACECODIGO'];
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

function insertUpdateLoteUsuariosxAcessos($_POSTDADOS, $pSMsg = 'N'){
	// incluir acessos novos
	if (!isset($_POSTDADOS['vLOidLista'])) {
		foreach ($_POSTDADOS['vLOidLista'] as $key => $value) 
		{		
			if(in_array($value, $_POSTDADOS['vKConsulta']))	$dadosAcesso['vSACECONSULTA'] = 'S'; else $dadosAcesso['vSACECONSULTA'] = 'N';	
			if(in_array($value, $_POSTDADOS['vKInclusao']))	$dadosAcesso['vSACEINCLUSAO'] = 'S'; else $dadosAcesso['vSACEINCLUSAO'] = 'N';	
			if(in_array($value, $_POSTDADOS['vKAlteracao'])) $dadosAcesso['vSACEALTERACAO'] = 'S'; else $dadosAcesso['vSACEALTERACAO'] = 'N';	
			if(in_array($value, $_POSTDADOS['vKExclusao']))	$dadosAcesso['vSACEEXCLUSAO'] = 'S'; else $dadosAcesso['vSACEEXCLUSAO'] = 'N';	
						
			$dadosAcesso['vIUSUCODIGO'] = $_POSTDADOS['vIUSUCODIGO'];
			$dadosAcesso['vIACETABELA'] = $value;
			$dadosAcesso['vIACECODIGO'] = $_POSTDADOS['vLACECODIGO'][$key];
			insertUpdateUsuariosxAcessos($dadosAcesso, 'N');
		}
	}	
	return;
}

function insertUpdateUsuariosxAcessos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'ACESSOS',
		'prefixo' => 'ACE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);	
	return $id; 
}

if(isset($_POST['method']) && $_POST['method'] ='insert_update_usuario_acesso_lote')
{
	$oidUsuario 				   = $_POST['hdn_oid'];
	$_POST['vIOidListaAcesso']     = explode(',',$_POST['vIOidListaAcesso']);
	$_POST['vIOidLista']  		   = explode(',',$_POST['vIOidLista']);
	$_POST['listStatusConsulta']   = explode(',',$_POST['listStatusConsulta']); 
	$_POST['listStatusInclusao']   = explode(',',$_POST['listStatusInclusao']);
	$_POST['listStatusAlteracao']  = explode(',',$_POST['listStatusAlteracao']); 
	$_POST['listStatusExclusao']   = explode(',',$_POST['listStatusExclusao']); 
	$_POST['listStatusExportar']   = explode(',',$_POST['listStatusExportar']); 

	foreach ($_POST['vIOidLista'] as $key => $value) 
	{
			if(!empty($_POST['listStatusConsulta'][$key]))
			{
				if($_POST['vIOidListaAcesso'][$key] != 'false')
				{
					insert_update_usuario_acesso($_POST['vIOidListaAcesso'][$key], $_POST['vIOidLista'][$key], 
						$_POST['listStatusInclusao'][$key], $_POST['listStatusAlteracao'][$key], $_POST['listStatusExclusao'][$key], $_POST['listStatusConsulta'][$key], $_POST['listStatusExportar'][$key], $oidUsuario, 'N');
				}

				if($_POST['vIOidListaAcesso'][$key] == 'false')
				{
					insert_update_usuario_acesso(false, $_POST['vIOidLista'][$key], 
						$_POST['listStatusInclusao'][$key], $_POST['listStatusAlteracao'][$key], $_POST['listStatusExclusao'][$key], $_POST['listStatusConsulta'][$key], $_POST['listStatusExportar'][$key], $oidUsuario, 'N');
				}
			}
	}

	$retorno = array();

	if($total == $i)
	{
		$retorno['msg'] 	= "Acesso atualizado! ";
		$retorno['status'] 	= "success";
	}
	else
	{
		$retorno['msg']		= "Erro ao atualizar permissão";
		$retorno['status'] 	= "error";
	}

	echo json_encode($retorno);
	return;
}

function fill_UsuariosxAcessos($pOid, $formatoRetorno = 'array' ){
	$SqlMain = 	"SELECT
	                *
	            FROM
                    ACESSOS
				WHERE
					ACESTATUS = 'S'
				AND
                    ACECODIGO = {$pOid}";

	$vConexao     = sql_conectar_banco(vGBancoSite);
	$resultSet    = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	if(!$registro = sql_retorno_lista($resultSet)) return false;

	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	if( $formatoRetorno == 'json' )
		echo json_encode($registro);
}
