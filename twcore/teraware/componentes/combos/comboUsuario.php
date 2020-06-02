<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *  
 *  @param [in] $pAConfig['label'] Label do campo
 *  @param [in] $pAConfig['id_name'] Nome e ID do campo
 *  @param [in] $pAConfig['id_registro'] Valor selecionado do campo
 *  @param [in] $pAConfig['obrigatorio'] Campo Obrigatorio, passar S
 *  @param [in] $pAConfig['desabilitar'] Campo desabilitado, passar S
 *  @param [in] $pAConfig['width'] Tamanho da largura do campo em px
 *  @param [in] $pAConfig['tipo_arq'] Tipo de arquivo, informar: cad ou list
 *  @return Codigo HTML
 *  
 *  @details Details
 */
function gerarUsuario($pAConfig){
	if(!isset($pAConfig['id_name']))
		$pAConfig['id_name'] = "vIUSUCODIGO";
		
	if(!isset($pAConfig['label']))
		$pAConfig['label'] = "Usuário";
		
	if(strtoupper($pAConfig['obrigatorio']) == "S")
		$pAConfig['obrigatorio'] = "obrigatorio";
	else
		$pAConfig['obrigatorio'] = "";

	if(strtoupper($pAConfig['desabilitar']) == "S")
		$pAConfig['desabilitar'] = "disabled";
	else
		$pAConfig['desabilitar'] = "";
?>
	<span><?php echo $pAConfig['label']; ?></span><br />
	<select name="<?php echo $pAConfig['id_name']; ?>" id="<?php echo $pAConfig['id_name']; ?>"  style="width:<?php echo $pAConfig['width']; ?>" class="<?php echo $pAConfig['obrigatorio']; ?>" <?php echo $pAConfig['desabilitar']; ?> >
		<? if(strtolower($pAConfig['tipo_arq']) == "list") { ?>
			<option value="T">Todos</option>
		<? } else { ?>
			<option value=""> ---------- </option>
		<? } ?>
		<?php $vConexao = sql_conectar_banco(vGBancoSite);
		$SqlVendedor = "Select USUCODIGO, USUNOME
						From USUARIOS
						where USUSTATUS = 'S'
						and EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA']."
						Order by USUNOME";
				$RS_CC = sql_executa(vGBancoSite,$vConexao,$SqlVendedor);
			while($reg_CC = sql_retorno_lista($RS_CC)){ ?> 
		<option value="<?php echo $reg_CC['USUCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $reg_CC['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_CC['USUNOME']; ?></option>
		<?php } ?>
	</select><br />
<?php } ?>