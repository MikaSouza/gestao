<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *
 *  @param [in] $pAConfig['id_name'] Nome e ID do campo
 *  @param [in] $pAConfig['id_registro'] Valor selecionado do campo
 *  @param [in] $pAConfig['obrigatorio'] Campo Obrigatorio, passar S
 *  @param [in] $pAConfig['desabilitar'] Campo desabilitado, passar S
 *  @param [in] $pAConfig['width'] Tamanho da largura do campo em px
 *  @param [in] $pAConfig['tipo'] Tipo Crédito ou Débito
 *  @return Codigo HTML
 *
 *  @details Details
 */
function gerarCartoesCreditoDebito($pAConfig){
	if(!isset($pAConfig['id_name']))
		$pAConfig['id_name'] = "vIVENCENTROCUSTO";

	if(strtoupper($pAConfig['obrigatorio']) == "S")
		$pAConfig['obrigatorio'] = "obrigatorio";
	else
		$pAConfig['obrigatorio'] = "";

	if(strtoupper($pAConfig['desabilitar']) == "S")
		$pAConfig['desabilitar'] = "disabled";
	else
		$pAConfig['desabilitar'] = "";
?>
<span>Cartão de Crédito/Débito</span><br />
<select name="<?php echo $pAConfig['id_name']; ?>" id="<?php echo $pAConfig['id_name']; ?>"  style="width:<?php echo $pAConfig['width']; ?>" class="<?php echo $pAConfig['obrigatorio']; ?>" <?php echo $pAConfig['desabilitar']; ?> >
	<option value=""> ---------- </option>
	<?php $vConexao = sql_conectar_banco(vGBancoSite);
	$SqlCC = 'Select CARNOME, CARCODIGO
			  From CARTOES
			  where CARSTATUS = "S" ';
	if ($pAConfig['tipo'] != "")
		$SqlCC .= ' and CARTIPO = "'. $pAConfig['tipo'] .'" ';
	$SqlCC .= ' Order by CARNOME';
	$RS_CC = sql_executa(vGBancoSite,$vConexao,$SqlCC);
	while($reg_CC = sql_retorno_lista($RS_CC)){ ?>
	<option value="<?php echo $reg_CC['CARCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $reg_CC['CARCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_CC['CARNOME']; ?></option>
	<?php } ?>
</select><br />
<?php } ?>