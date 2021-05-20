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
			<a href="../cadastro/indexClientes.php" class="logo">
				<span>
					<img src="../assets/images/logo_menu.png" alt="logo-small" class="logo-int-vipal sm-logo-int-vipal">
				</span>
			</a>
		</div><!--topbar-left-->
		<!--end logo-->

		<ul class="list-unstyled topbar-nav float-right mb-0">

			<li class="dropdown">
				<a class="nav-link dropdown-toggle waves-effect waves-light nav-user pr-0" data-toggle="dropdown" href="#" role="button"
					aria-haspopup="false" aria-expanded="false">
					<img src="<?= ($_SESSION['SS_USUFOTO'] == '' ? '../assets/images/users/user-1.jpg' : '../ged/usuarios_fotos/'.$_SESSION['SS_USUFOTO']);?>" alt="profile-user" class="rounded-circle">	
					<span class="ml-1 nav-user-name hidden-sm">
					<?= utf8_encode($_SESSION['SS_USUNOME']).' - '.utf8_encode($_SESSION['SS_USUSETOR']);?>
					<i class="mdi mdi-chevron-down"></i> </span>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="<?= URL_BASE;?>cadastro/contaCliente.php"><i class="dripicons-user text-muted mr-2"></i> Conta</a>					
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="../includes/logout.php"><i class="dripicons-exit text-muted mr-2"></i> Sair</a>
				</div>
			</li><!--end dropdown-->
			<?php //include_once('notificacoes.php');?>
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
			</li> <!--end menu item-->
		</ul><!--end topbar-nav-->

	</nav>
	<!-- end navbar-->
	 <!-- MENU Start -->
	<div class="navbar-custom-menu">
		<div class="container-fluid">
			<div id="navigation">

			</div> <!-- end navigation -->
		</div> <!-- end container-fluid -->
	</div> <!-- end navbar-custom -->
</div>
<!-- Top Bar End -->