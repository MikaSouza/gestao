<?php 
// quando alterar este arquivo verificar as dependências
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_combo']) && $_POST['hdn_metodo_combo'] == 'comboTabela') {
	$combo_tabela_padrao = array(
		"label"       => $_POST['pSTITULO'],
		"tipo"        => $_POST['pSTABTIPO'],
		"id_name"     => $_POST['pSCAMPO'],
		"id_registro" => $_POST['pIOID'],
		"obrigatorio" => $_POST['pSOBRIGATORIO'],
		"desabilitar" => $_POST['pSDESABILITAR'],
		"div"         => $_POST['pSDiv'],
		"controles"   => $_POST['pSControles'],
		"classe"      => $_POST['pSClasse'],
		"callback"	  => $_POST['pSCallback'],
		"metodo"	  => $_POST['pSMethod'],
		"style"	  	  => $_POST['pStyle'],
	);
	gerarTabela($combo_tabela_padrao);
}

/**
 *  @brief Brief
 *
 *  @param [in] $pAConfig['label'] Label do campo
 *  @param [in] $pAConfig['id_name'] Nome e ID do campo
 *  @param [in] $pAConfig['id_registro'] Valor selecionado do campo
 *  @param [in] $pAConfig['obrigatorio'] Campo Obrigatorio, passar S
 *  @param [in] $pAConfig['desabilitar'] Campo desabilitado, passar S
 *  @param [in] $pAConfig['style'] O valor a ser inserido no atributo 'style' no select
 *  @param [in] $pAConfig['width'] Tamanho da largura do campo em px
 *  @param [in] $pAConfig['controles'] Exibir ícone com as opções Adicionar, Editar e Excluir
 *  @return Codigo HTML
 *
 *  @details Details
 */
function gerarTabela($pAConfig){

	if (empty($pAConfig['classe'])) {

		if(strtoupper($pAConfig['obrigatorio']) == "S")
			$pAConfig['obrigatorio'] = "obrigatorio";
		else
			$pAConfig['obrigatorio'] = "";
	} else {
		$pAConfig['obrigatorio'] = $pAConfig['classe'];
	}

	if(strtoupper($pAConfig['desabilitar']) == "S")
		$pAConfig['desabilitar'] = "disabled";
	else
		$pAConfig['desabilitar'] = "";

	(isset($pAConfig['style'])) ? $pAConfig['style'] : '' ;

	$exibe_controle = (isset($pAConfig['controles'])) ? strtoupper($pAConfig['controles']) : "S" ;

	$sql = "SELECT
                TABCODIGO, TABDESCRICAO
            FROM
                TABELAS
            WHERE
                TABSTATUS = 'S'
            AND
                TABTIPO = ?    
            ORDER BY
                TABDESCRICAO ASC";
    $arrayQuery = array(
       'query' => $sql,
       'parametros' => array(
           array($pAConfig['tipo'], PDO::PARAM_STR)
           )
    );
    $list = consultaComposta($arrayQuery);
	
?>

<label><?= $pAConfig['label'];
if(strtoupper($pAConfig['obrigatorio']) == "S") { ?>
<small class="text-danger font-13">*</small> 
<?php } ?>
</label>
<div class="input-group-prepend">
	<select title="<?= $pAConfig['label'];?>" id="<?= $pAConfig['id_name'];?>" class="custom-select <?= $pAConfig['obrigatorio'];?>" name="<?= $pAConfig['id_name'];?>">
		<option value="">  -------------  </option>
		<?php foreach ($list['dados'] as $tabelas): ?>
			<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($pAConfig['id_registro'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
		if($exibe_controle === "S") :
			if($pAConfig['desabilitar'] != "disabled") { ?>
	<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-align-justify"></i><i class="mdi mdi-chevron-down ml-1"></i></button>
	<div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -103px, 0px);">
		<a class="dropdown-item" href="#" onclick="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
													 'vSTitulo': '<?php echo $pAConfig['label'];?>',
													 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
													 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
													 'vIValor': '',
													 'vSDiv': '<?php echo $pAConfig['div'];?>',
													 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
													 'pSMethod': 'incluir',
													 'vSCallback': '<?php echo $pAConfig['callback'];?>'
													});">Incluir</a>
		<a class="dropdown-item" href="#" onclick="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
													 'vSTitulo': '<?php echo $pAConfig['label'];?>',
													 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
													 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
													 'vIValor': '<?php echo $pAConfig['id_registro']; ?>',
													 'vSDiv': '<?php echo $pAConfig['div'];?>',
													 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
													 'pSMethod': 'alterar',
													 'vSCallback': '<?php echo $pAConfig['callback'];?>'
													});">Alterar</a>
		<a class="dropdown-item" href="#" onclick="javascript:exibirFormModalIncluirAlterarTipoTabela(vAParameters = {
													 'vSTitulo': '<?php echo $pAConfig['label'];?>',
													 'vSTabTipo': '<?php echo $pAConfig['tipo'];?>',
													 'vSCampo': '<?php echo $pAConfig['id_name'];?>',
													 'vIValor': '<?php echo $pAConfig['id_registro']; ?>',
													 'vSDiv': '<?php echo $pAConfig['div'];?>',
													 'vSObrigatorio': '<?php echo $pAConfig['obrigatorio'];?>',
													 'pSMethod': 'desativar',
													 'vSCallback': '<?php echo $pAConfig['callback'];?>'
													});">Excluir</a>
	</div>
	<?php }
		endif;
	?>
</div>
<?php }	?>