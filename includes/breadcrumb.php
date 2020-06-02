<div class="row">
	<div class="col-sm-12">
		<div class="page-title-box">
			<div class="float-right">
				<ol class="breadcrumb">
					<?php if ($_SESSION['SS_USUMASTER'] == 'S') { ?>
					<li class="breadcrumb-item"><a href="../cadastro/">Home</a></li>
					<?php } else { ?>
					<li class="breadcrumb-item">Home</li>
					<?php } ?>
					<li class="breadcrumb-item"><a href="<?= $vAConfiguracaoTela['MENARQUIVOLIST'];?>"><?= $vAConfiguracaoTela['MENGRUPO'];?></a></li>
					<li class="breadcrumb-item active"><?= $vAConfiguracaoTela['MENTITULO'];?></li>
				</ol>
			</div>
			<h4 class="page-title">Cadastro de <?= $vAConfiguracaoTela['MENTITULO'];?></h4>
		</div>
	</div>
</div>