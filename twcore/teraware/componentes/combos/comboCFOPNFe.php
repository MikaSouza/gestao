<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *  
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
function gerarCFOPNFe($pAConfig){
	if(!isset($pAConfig['id_name']))
		$pAConfig['id_name'] = "vINFSCFOP";

	if(strtoupper($pAConfig['obrigatorio']) == "S")
		$pAConfig['obrigatorio'] = "obrigatorio";
	else
		$pAConfig['obrigatorio'] = "";

	if(strtoupper($pAConfig['desabilitar']) == "S")
		$pAConfig['desabilitar'] = "disabled";
	else
		$pAConfig['desabilitar'] = "";
?>
	<span>CFOP</span><br />
	<select name="<?php echo $pAConfig['id_name']; ?>" id="<?php echo $pAConfig['id_name']; ?>"  style="width:<?php echo $pAConfig['width']; ?>" class="<?php echo $pAConfig['obrigatorio']; ?>" <?php echo $pAConfig['desabilitar']; ?> >
		<? if(strtolower($pAConfig['tipo_arq']) == "list") { ?>
			<option value="T">Todas</option>
		<? } ?>
		<?php $vConexao = sql_conectar_banco(vGBancoSite);
		$SqlAux = "SELECT CFOCODIGO, CFODESCRICAO, CFOCODIGOFISCAL, TABCODIGO
				   FROM CFOPS
				   WHERE CFOSTATUS = 'S'
				   ORDER BY CFOCODIGOFISCAL";
		$RS_Aux = sql_executa(vGBancoSite,$vConexao, $SqlAux);
		while($reg_Aux = sql_retorno_lista($RS_Aux)){ ?> 
		<option value="<?php echo $reg_Aux['CFOCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $reg_Aux['CFOCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_Aux['CFOCODIGOFISCAL'] . " - " . $reg_Aux['CFODESCRICAO']; ?></option>
		<?php } ?>
	</select><br />
<?php } ?>