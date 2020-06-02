<div class="col-md-6 fa-pull-left">
	<?php if ($_GET['method'] != 'consultar') {
	if($vAConfiguracaoTela['BTN_GRAVAR'] == ""){ ?>
		<button type="button" onClick="validarForm();" title="Salvar Dados" class="btn btn-primary waves-effect waves-light">Salvar Dados</button>
	<?php } ?>
	<a href="<?= $vAConfiguracaoTela['MENARQUIVOLIST'];?>">
	<button type="button" title="Cancelar" class="btn btn-warning waves-effect m-l-5">Voltar</button>
	</a>	
	<a href="<?= $vAConfiguracaoTela['MENARQUIVOCAD'];?>?method=insert">
	<button type="button" title="Incluir Novo Registro" class="btn btn-success waves-effect waves-light">Incluir Novo Registro</button>
	</a>
	<?php } else { ?>
	<a href="<?= $vAConfiguracaoTela['MENARQUIVOLIST'];?>">
	<button type="button" title="Retornar" class="btn btn-warning waves-effect m-l-5">Voltar</button>
	</a>
	<?php } ?>
</div>