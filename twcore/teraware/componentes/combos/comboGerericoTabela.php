<?php // quando alterar este arquivo verificar as dependências
include_once($_SERVER['DOCUMENT_ROOT'].'/include/constantes.php');
if (isset($_POST['hdn_metodo_combo']) && $_POST['hdn_metodo_combo'] == 'comboTabela') {
	$combo_centro_custo = array(
		"label" => $_POST['pSTITULO'],   
		"tipo" => $_POST['pSTABTIPO'],  
		"id_name" => $_POST['pSCAMPO'],  
		"id_registro" => $_POST['pIOID'],
		"obrigatorio" => $_POST['pSOBRIGATORIO'],
		"desabilitar" => $_POST['pSDESABILITAR'],
		"div" => $_POST['pSDiv'],
	);
	gerarTabela($combo_centro_custo);
}	
	
/**
 *  @brief Brief
 *  
 *  @param [in] $pAConfig['label'] Label do campo
 *  @param [in] $pAConfig['id_name'] Nome e ID do campo
 *  @param [in] $pAConfig['id_registro'] Valor selecionado do campo
 *  @param [in] $pAConfig['obrigatorio'] Campo Obrigatorio, passar S
 *  @param [in] $pAConfig['desabilitar'] Campo desabilitado, passar S
 *  @param [in] $pAConfig['width'] Tamanho da largura do campo em px
 *  @return Codigo HTML
 *  
 *  @details Details
 */
function gerarTabela($pAConfig){


	if(strtoupper($pAConfig['obrigatorio']) == "S")
		$pAConfig['obrigatorio'] = "obrigatorio";
	else
		$pAConfig['obrigatorio'] = "";

	if(strtoupper($pAConfig['desabilitar']) == "S")
		$pAConfig['desabilitar'] = "disabled";
	else
		$pAConfig['desabilitar'] = "";
?>
	<span><?php echo $pAConfig['label'];?> </span><br />
	<select  name="<?php echo $pAConfig['id_name'];?>" id="<?php echo $pAConfig['id_name'];?>" title="<?php echo $pAConfig['label'];?>" alt="<?php echo $pAConfig['label'];?>" class="<?php echo $pAConfig['obrigatorio'];?>" <?php echo $pAConfig['desabilitar'];?> >
		<option value="">----------</option>
				<?php
					$SqlCC = 'SELECT 
						T.TABCODIGO,
						T.TABDESCRICAO
					FROM
						TABELAS T
					WHERE
						T.TABTIPO = "'. $pAConfig['tipo'] .'" ';
					$SqlCC .=' 
					AND
						T.TABSTATUS = "S"
					ORDER BY
						T.TABDESCRICAO';
					$vConexao = sql_conectar_banco(vGBancoSite);
					$RS_CC = sql_executa(vGBancoSite,$vConexao,$SqlCC);
					while($reg_CC = sql_retorno_lista($RS_CC)){ ?>
			<option value="<?php echo $reg_CC['TABCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $reg_CC['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_CC['TABDESCRICAO']; ?></option>
					<?php }?>
		</select>		
		<input type="hidden" name="hdn_oid_temp_tab_codigo" id="hdn_oid_temp_tab_codigo" />
		<?php if(strtoupper($pAConfig['desabilitar']) != "S") { ?>
			<?php if ($_SESSION['SA_ACESSOS']['TABELA'][523]['CONSULTA'] == "S") { ?>
				<a id="PopUP<?php echo $pAConfig['id_name'];?>" class="controles"><img src="../imagens/controles.png" width="20px" border="0" title="Configurações" /></a>
			<?php } ?>
			<div id="tooltiptextPopUP<?php echo $pAConfig['id_name'];?>" class="tipControle">
				<?php if ($_SESSION['SA_ACESSOS']['TABELA'][523]['INCLUSAO'] == "S") { ?> 
					<a href="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
											 'vSTitulo': '<?php echo $pAConfig['label'];?>',											 
											 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
											 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
											 'vIValor': '<?php echo $pAConfig['id_registro']; ?>',
											 'vSDiv': '<?php echo $pAConfig['div'];?>',
											 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
											 'pSMethod': 'incluir'
											});" class="btnIncluirMini"></a>
				<?php } ?>
				<div id="displayPopUP<?php echo $pAConfig['id_name'];?>" style="float: right; display:block; ">
					<?php if ($_SESSION['SA_ACESSOS']['TABELA'][523]['ALTERACAO'] == "S") { ?>
						<a href="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
											 'vSTitulo': '<?php echo $pAConfig['label'];?>',		 
											 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
											 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
											 'vIValor': '<?php echo $pAConfig['id_registro']; ?>',
											 'vSDiv': '<?php echo $pAConfig['div'];?>',
											 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
											 'pSMethod': 'alterar'
											});" class="btnEditarMini"></a>
					<?php } ?>
					<?php if ($_SESSION['SA_ACESSOS']['TABELA'][523]['EXCLUSAO'] == "S") { ?>
						<a href="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
											 'vSTitulo': '<?php echo $pAConfig['label'];?>',											 
											 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
											 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
											 'vIValor': '<?php echo $pAConfig['id_registro']; ?>',
											 'vSDiv': '<?php echo $pAConfig['div'];?>',
											 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
											 'pSMethod': 'desativar'
											});" class="btnCancelarMini"></a>
					<?php } ?>	
				</div>
			</div>
		<?php } ?>
		<br />
<?php } ?>