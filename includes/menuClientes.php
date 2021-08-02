<?php
//valida se foi efetuado login no sistema
if (!isset($_SESSION['SI_USUCODIGO'])) {
	if ($_SESSION['SS_SECURITY'] != '1ODLkhuDE2OE') {
		echo "<script language='javaScript'>window.location.href='../autenticacao/login.php'</script>";
		return;
	}
}
?>
<!-- Top Bar Start -->
<div class="topbar">

	<!-- Navbar -->
	<nav class="topbar-main top-barra sm-top-barra" style="margin: 0 auto">
		<!-- LOGO -->
		<div class="topbar-left">
			<a href="../cadastro/indexClientes.php?id=<?= $_GET['id'] ?>" class="logo">
				<span>
					<img src="../assets/images/novo_logo.png" alt="logo-small" class="logo-int-vipal sm-logo-int-vipal">
				</span>
			</a>
		</div>
		<!--topbar-left-->
		<!--end logo-->

		<ul class="list-unstyled topbar-nav float-right mb-0">

			<li class="dropdown">
				<a class="nav-link dropdown-toggle waves-effect waves-light nav-user pr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
					<img src="<?= ($_SESSION['SS_USUFOTO'] == '' ? '../assets/images/users/user-1.jpg' : '../ged/contatos_fotos/' . $_SESSION['SS_USUFOTO']); ?>" alt="profile-user" class="rounded-circle">
					<span class="ml-1 nav-user-name hidden-sm">
						<?= utf8_encode($_SESSION['SS_USUNOME']) . ' - ' . utf8_encode($_SESSION['SS_USUSETOR']); ?>
						<i class="mdi mdi-chevron-down"></i> </span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="<?= URL_BASE; ?>cadastro/contaCliente.php?id=<?= $_GET['id'] ?>"><i class="dripicons-user text-muted mr-2"></i> Conta</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="../includes/logout.php"><i class="dripicons-exit text-muted mr-2"></i> Sair</a>
				</div>
			</li>
			<!--end dropdown-->
			<?php //include_once('notificacoes.php');
			?>
			<li class="menu-item">
				<!-- Mobile menu toggle-->
				<a class="navbar-toggle nav-link" id="mobileToggle">
					<div class="lines">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</a>
				<!-- End mobile menu toggle-->
			</li>
			<!--end menu item-->
		</ul>
		<!--end topbar-nav-->

	</nav>
	<!-- end navbar-->
	<!-- MENU Start -->
	<div class="navbar-custom-menu">
		<div class="container-fluid">
			<div id="navigation">
				   <!-- Navigation Menu-->
				<ul class="navigation-menu">
					<li class="has-submenu">
						<a href="../indexClientes.php">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path class="svg-primary" d="M405.333 80h-87.35C310.879 52.396 285.821 32 256 32s-54.879 20.396-61.983 48h-87.35C83.198 80 64 99.198 64 122.667v314.665C64 460.801 83.198 480 106.667 480h298.666C428.802 480 448 460.801 448 437.332V122.667C448 99.198 428.802 80 405.333 80zM256 80c11.729 0 21.333 9.599 21.333 21.333s-9.604 21.334-21.333 21.334-21.333-9.6-21.333-21.334S244.271 80 256 80zm152 360H104V120h40v72h224v-72h40v320z"></path>
                            	</svg>
							<span>Agenda</span>
						</a>
					</li><!--end has-submenu-->
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path d="M208 400h96v-47.994h-96V400zM32 112v47.994h448V112H32zm80 168.783h288v-49.555H112v49.555z"></path>
								</svg>
							<span>Orientação Técnica</span>
						</a>
					</li><!--end has-submenu-->
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path d="M337.454 232c33.599 0 61.092-27.002 61.092-60 0-32.997-27.493-60-61.092-60s-61.09 27.003-61.09 60c0 32.998 27.491 60 61.09 60zm-162.908 0c33.599 0 61.09-27.002 61.09-60 0-32.997-27.491-60-61.09-60s-61.092 27.003-61.092 60c0 32.998 27.493 60 61.092 60zm0 44C126.688 276 32 298.998 32 346v54h288v-54c0-47.002-97.599-70-145.454-70zm162.908 11.003c-6.105 0-10.325 0-17.454.997 23.426 17.002 32 28 32 58v54h128v-54c0-47.002-94.688-58.997-142.546-58.997z"></path>
								</svg>
							<span>Solicitar Atendimento</span>
						</a>
					</li><!--end has-submenu-->
				</ul>
			</div> <!-- end navigation -->
		</div> <!-- end container-fluid -->
	</div> <!-- end navbar-custom -->
</div>
<!-- Top Bar End -->