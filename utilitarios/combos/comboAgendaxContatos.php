<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillClientexContatos') {
	$vICLICODIGO = filter_input(INPUT_GET, "vICLICODIGO", FILTER_SANITIZE_NUMBER_INT);
    $vICONCODIGO = filter_input(INPUT_GET, "vICONCODIGO", FILTER_SANITIZE_NUMBER_INT);
    $vSMETHOD    = filter_input(INPUT_GET, "vSMETHOD", FILTER_SANITIZE_STRING);    
	
	$result = comboClientexContatos($vICLICODIGO);
	?>
	<div class="col-md-6">
		<label>Contato
			<small class="text-danger font-13">*</small>
		</label>
		<div class="input-group-prepend">
		<select id="vHCONCODIGO" title="Contato" class="custom-select obrigatorio" name="vHCONCODIGO" onchange="incluirNovoContato()">
			<option value="">  -------------  </option>
			<?php foreach ($result['dados'] as $result): ?>
				<option value="<?php echo $result['CONCODIGO']; ?>" <?php if ($vICONCODIGO == $result['CONCODIGO']) echo "selected='selected'"; ?>><?php echo $result['CONNOME'].' - '.$result['CONEMAIL'].' - '.$result['CONFONE']; ?></option>
			<?php endforeach; ?>
		</select>
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
	</div>	
	<?php

}

function comboClientexContatos($vICLICODIGO)
{
	$sql = "SELECT
					CONCODIGO,
                    CONNOME,
                    CONEMAIL,
					CONFONE
				FROM 
					CONTATOS 
				WHERE 
					CONSTATUS = 'S' AND					
					CLICODIGO = ?
		ORDER BY
			CONNOME ";	
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(array($vICLICODIGO, PDO::PARAM_INT))
				);
	$result = consultaComposta($arrayQuery);
	return $result;
}