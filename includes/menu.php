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
			<a href="../cadastro/" class="logo">
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
					<a class="dropdown-item" href="<?= URL_BASE;?>rh/contaUsuario.php"><i class="dripicons-user text-muted mr-2"></i> Conta</a>
					<a class="dropdown-item" href="http://helpdesk.teraware.com.br/login.php" target="_blank"><i class="fa fa-comments"></i> Abrir Atendimento(s)</a>
					<!--<a class="dropdown-item" target="_blank" href="../manual/manualUtilitarios.pdf"><i class="dripicons-wallet text-muted mr-2"></i> Manual</a>-->
					<?php if ($_SESSION['SS_USUMASTER'] == 'S') { ?>
					<!--<a class="dropdown-item" href="#"><i class="dripicons-gear text-muted mr-2"></i> Configurações</a>-->				
					<?php } ?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="../includes/logout.php"><i class="dripicons-exit text-muted mr-2"></i> Sair</a>
				</div>
			</li><!--end dropdown-->
			<?php include_once('notificacoes.php');?>
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
				<!-- Navigation Menu-->
				<ul class="navigation-menu">					
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path class="svg-primary" d="M405.333 80h-87.35C310.879 52.396 285.821 32 256 32s-54.879 20.396-61.983 48h-87.35C83.198 80 64 99.198 64 122.667v314.665C64 460.801 83.198 480 106.667 480h298.666C428.802 480 448 460.801 448 437.332V122.667C448 99.198 428.802 80 405.333 80zM256 80c11.729 0 21.333 9.599 21.333 21.333s-9.604 21.334-21.333 21.334-21.333-9.6-21.333-21.334S244.271 80 256 80zm152 360H104V120h40v72h224v-72h40v320z"></path>
                            	</svg>
							<span>Cadastros</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][109]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listAvisos.php"><i class="fas fa-thumbtack"></i>Avisos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][6]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listClientes.php"><i class="far fa-handshake"></i>Clientes</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2030]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listInformacoesPreliminares.php"><i class="fas fa-edit"></i>Info Preliminares</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1989]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listDocumentosPadroes.php"><i class="far fa-address-card"></i>Documentos Padrões</a></li>	
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1943]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listCidades.php"><i class="fas fa-hotel"></i>Cidades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listProdutosxServicos.php"><i class="fas fa-project-diagram"></i>Produtos/Serviços</a></li>	
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][18]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listTabelas.php"><i class="fas fa-table"></i>Tabelas</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listDashBoard.php"><i class="fas fa-chart-pie"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>
					</li><!--end has-submenu-->																						
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path d="M362.1 205.2L272.9 72.5C269 66.8 262.5 64 256 64c-6.5 0-13 2.8-16.9 8.7l-89.2 132.5H52.4c-11.2 0-20.4 9.1-20.4 20.2 0 1.8.2 3.6.8 5.5l51.7 187.5c4.7 17 20.4 29.5 39.1 29.5h264.7c18.7 0 34.4-12.5 39.3-29.5l51.7-187.5.6-5.5c0-11.1-9.2-20.2-20.4-20.2h-97.4zm-167.2 0l61.1-89 61.1 89H194.9zM256 367.1c-22.4 0-40.7-18.2-40.7-40.5s18.3-40.5 40.7-40.5 40.7 18.2 40.7 40.5-18.3 40.5-40.7 40.5z"></path>
								</svg>
							<span>Comercial</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1991]['CONSULTA'] == "S") { ?>
						<ul class="submenu">							
							
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1949]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listContratos.php"><i class="mdi mdi-file-document-box-multiple"></i>Contratos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listDashBoard.php"><i class="fas fa-chart-pie"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>
					</li><!--end has-submenu-->																															
												
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path d="M467.3 168.1c-1.8 0-3.5.3-5.1 1l-177.6 92.1h-.1c-7.6 4.7-12.5 12.5-12.5 21.4v185.9c0 6.4 5.6 11.5 12.7 11.5 2.2 0 4.3-.5 6.1-1.4.2-.1.4-.2.5-.3L466 385.6l.3-.1c8.2-4.5 13.7-12.7 13.7-22.1V179.6c0-6.4-5.7-11.5-12.7-11.5zM454.3 118.5L272.6 36.8S261.9 32 256 32c-5.9 0-16.5 4.8-16.5 4.8L57.6 118.5s-8 3.3-8 9.5c0 6.6 8.3 11.5 8.3 11.5l185.5 97.8c3.8 1.7 8.1 2.6 12.6 2.6 4.6 0 8.9-1 12.7-2.7l185.4-97.9s7.5-4 7.5-11.5c.1-6.3-7.3-9.3-7.3-9.3zM227.5 261.2L49.8 169c-1.5-.6-3.3-1-5.1-1-7 0-12.7 5.1-12.7 11.5v183.8c0 9.4 5.5 17.6 13.7 22.1l.2.1 174.7 92.7c1.9 1.1 4.2 1.7 6.6 1.7 7 0 12.7-5.2 12.7-11.5V282.6c.1-8.9-4.9-16.8-12.4-21.4z"></path>
								</svg>
							<span>Consultoria</span>
						</a>
						<ul class="submenu">							
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2025]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listAtendimentos.php"><i class="far fa-comments"></i>Atendimentos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2022]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listAtividades.php"><i class="fas fa-tasks"></i>Atividades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2023]['CONSULTA'] == "S") { ?> 
							<li><a href="../helpdesk/listCheckList.php"><i class="fas fa-check-double"></i>CheckList</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2024]['CONSULTA'] == "S") { ?> 
							<li><a href="../helpdesk/listPlanoTrabalho.php"><i class="fas fa-stream"></i>Plano de Trabalho</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2028]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listPosicoesPadroes.php"><i class="fas fa-database"></i>Posições Padrões</a></li> 
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2027]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listPrioridades.php"><i class="fa fa-list-ol"></i>Prioridades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2026]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listOrientacaoTecnica.php"><i class="mdi mdi-lead-pencil"></i>Orientação Técnica</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][109]['CONSULTA'] == "S") { ?>
							<li><a href="../helpdesk/listDashBoard.php"><i class="fas fa-chart-pie"></i>Dashboard</a></li>
							<?php } ?>							
						</ul><!--end submenu-->
					</li><!--end has-submenu-->
									
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							 	<path d="M337.454 232c33.599 0 61.092-27.002 61.092-60 0-32.997-27.493-60-61.092-60s-61.09 27.003-61.09 60c0 32.998 27.491 60 61.09 60zm-162.908 0c33.599 0 61.09-27.002 61.09-60 0-32.997-27.491-60-61.09-60s-61.092 27.003-61.092 60c0 32.998 27.493 60 61.092 60zm0 44C126.688 276 32 298.998 32 346v54h288v-54c0-47.002-97.599-70-145.454-70zm162.908 11.003c-6.105 0-10.325 0-17.454.997 23.426 17.002 32 28 32 58v54h128v-54c0-47.002-94.688-58.997-142.546-58.997z"></path>
							</svg>
							<span>RH</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1966]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listUsuario.php"><i class="dripicons-user"></i>Colaboradores/Usuários</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][645]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listGrupoAcessos.php"><i class="fa fa-users"></i>Grupos Acessos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][80]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listLogsAcessos.php"><i class="far fa-eye"></i>Logs Acessos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listDashBoard.php"><i class="fas fa-chart-pie"></i>Dashboard</a></li>
							<?php } ?>
						</ul>
						<?php } ?>
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path d="M208 400h96v-47.994h-96V400zM32 112v47.994h448V112H32zm80 168.783h288v-49.555H112v49.555z"></path>
							</svg>
							<span>Utilitários</span>
						</a>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listAgenda.php"><i class="fas fa-calendar"></i>Agenda</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listAgendaCalendario.php"><i class="far fa-calendar-alt"></i>Agenda Calendário</a></li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listGED.php"><i class="fas fa-copy"></i>Digitalizações/GED</a></li>
							<?php } ?>					
						</ul>
					</li><!--end has-submenu-->	
					<?php if ($_SESSION['SS_USUMASTER'] == 'S') { ?>	
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" version="1.1" id="Layer_5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
								<g>
									<path class="svg-primary" d="M376,192h-24v-46.7c0-52.7-42-96.5-94.7-97.3c-53.4-0.7-97.3,42.8-97.3,96v48h-24c-22,0-40,18-40,40v192c0,22,18,40,40,40
										h240c22,0,40-18,40-40V232C416,210,398,192,376,192z M270,316.8v68.8c0,7.5-5.8,14-13.3,14.4c-8,0.4-14.7-6-14.7-14v-69.2
										c-11.5-5.6-19.1-17.8-17.9-31.7c1.4-15.5,14.1-27.9,29.6-29c18.7-1.3,34.3,13.5,34.3,31.9C288,300.7,280.7,311.6,270,316.8z
											M324,192H188v-48c0-18.1,7.1-35.1,20-48s29.9-20,48-20s35.1,7.1,48,20s20,29.9,20,48V192z"/>
								</g>
							</svg>
							<span>Sistema</span>
						</a>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listMenus.php"><i class="fas fa-sitemap"></i>Menus</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listEmpresaUsuaria.php"><i class="mdi mdi-factory"></i>Empresa Usuária</a></li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listParametros.php"><i class="fas fa-tasks"></i>Parâmetros</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listPeriodicidades.php"><i class="far fa-calendar-alt"></i>Peridiocidades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="https://www.gestao.srv.br/tw/interface/login.php"target="_blank"> <i class="fas fa-desktop"></i>Admin Site</a></li>
							<?php } ?>
						</ul>
					</li><!--end has-submenu-->		
					<?php } ?>	
				</ul><!-- End navigation menu -->
			</div> <!-- end navigation -->
		</div> <!-- end container-fluid -->
	</div> <!-- end navbar-custom -->
</div>
<!-- Top Bar End -->