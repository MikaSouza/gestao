<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../cadastro/combos/comboAvisos.php';
$vAAvisos = comboAvisos();
?>
<li class="dropdown notification-list">
	<a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown" href="#" role="button"
		aria-haspopup="false" aria-expanded="false">
		<i class="dripicons-bell noti-icon"></i>
		<span class="badge badge-danger badge-pill noti-icon-badge"><?= $vAAvisos['quantidadeRegistros']; ?></span>
	</a>
	<div class="dropdown-menu dropdown-menu-right dropdown-lg">
		<!-- item-->		
		<h6 class="dropdown-item-text">
			Feed Avisos (<?= $vAAvisos['quantidadeRegistros']; ?>)
		</h6>
		<div class="slimscroll notification-list">
			<?php foreach ($vAAvisos['dados'] as $tabelas): ?>        
				<!-- item-->
				<a href="http://app.twflex.com.br/cadastro/cadAvisos.php?method=update&oid=<?= $tabelas['AVICODIGO'];?>" class="dropdown-item notify-item active">
					<div class="notify-icon bg-warning"><i class="mdi mdi-checkbox-marked-outline"></i></div>
					<p class="notify-details"><?= $tabelas['AVITITULO']; ?><small class="text-muted"><?= formatar_data($tabelas['AVIDATAINICIAL']); ?></small></p>
				</a>			
			<?php endforeach; ?>			
		</div>
		<!-- All-->
		<a href="../cadastro/listAvisos.php" class="dropdown-item text-center text-primary">
			Visualizar todas <i class="fi-arrow-right"></i>
		</a>
	</div>
</li><!--end notification-list-->