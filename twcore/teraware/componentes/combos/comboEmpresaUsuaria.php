<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *
 *  @param [in] $pAConfig['titulo'] Título que aparecerá ao Usuário
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
//function gerarEmpresaUsuaria($pSNome, $pSObrigatorio, $pSDisabled, $pISeletor){
function gerarEmpresaUsuaria($pAConfig){
	if(!isset($pAConfig['id_name']))
		$pAConfig['id_name'] = "vIEMPCODIGO";

	if(isset($pAConfig['obrigatorio']))
		if(strtoupper($pAConfig['obrigatorio']) == "S")
			$pAConfig['obrigatorio'] = "obrigatorio";
		else
			$pAConfig['obrigatorio'] = "";

	if(isset($pAConfig['desabilitar']))
		if(strtoupper($pAConfig['desabilitar']) == "S")
			$pAConfig['desabilitar'] = "disabled";
		else
			$pAConfig['desabilitar'] = "";

	$pAConfig['titulo'] = (isset($pAConfig['titulo'])) ? $pAConfig['titulo'] : "Empresa Usuária";

?>
	<span><?= $pAConfig['titulo']; ?></span><br />
	<select title='<?= $pAConfig['titulo']; ?>' name="<?php echo $pAConfig['id_name']; ?>" id="<?php echo $pAConfig['id_name']; ?>"  style="width:<?php echo $pAConfig['width']; ?>" class="<?php echo $pAConfig['obrigatorio']; ?>" <?php echo $pAConfig['desabilitar']; ?> <?php echo $pAConfig['evento']; ?>>
		<? if(strtolower($pAConfig['tipo_arq']) == "list") { ?>
			<option value="T">Todas</option>
		<? } ?>
		<?php $vConexao = sql_conectar_banco(vGBancoSite);
		$SqlEU = "SELECT
						EMPCODIGO,
						EMPNOMEFANTASIA
					FROM
						EMPRESAUSUARIA
					WHERE
						EMPSTATUS = 'S' AND
						EMPCODIGO in (". $_SESSION['SA_EMPRESAS'] .")
					ORDER BY
						EMPNOMEFANTASIA";
		$RS_EU = sql_executa(vGBancoSite,$vConexao,$SqlEU);
		while($reg_EU = sql_retorno_lista($RS_EU)){ ?>
		<option value="<?php echo $reg_EU['EMPCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $reg_EU['EMPCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_EU['EMPNOMEFANTASIA']; ?></option>
		<?php } ?>
	</select><br />
<?php } ?>