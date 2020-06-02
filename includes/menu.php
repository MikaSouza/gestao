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
			<?php if (($_SESSION['SS_USUMASTER'] == 'S') || ($_SESSION['SI_USUCODIGO'] == 49)) { ?>
			<a href="../cadastro/" class="logo">
			<?php } else { ?>
				<a href="../../marpa_intranet/" class="logo">
			<?php } ?>
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
					<a class="dropdown-item" href="http://sistema.marpa.com.br:5588/marpa_consultoria/rh/contaUsuario.php"><i class="dripicons-user text-muted mr-2"></i> Conta</a>
					<a class="dropdown-item" href="http://helpdesk.teraware.com.br/login.php" target="_blank"><i class="dripicons-help text-muted mr-2"></i> Atendimento</a>
					<a class="dropdown-item" target="_blank" href="../manual/manualUtilitarios.pdf"><i class="dripicons-wallet text-muted mr-2"></i> Manual</a>
					<?php if ($_SESSION['SS_USUMASTER'] == 'S') { ?>
					<a class="dropdown-item" href="#"><i class="dripicons-gear text-muted mr-2"></i> Configurações</a>					
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
							<li><a href="../cadastro/listAvisos.php"><i class="mdi mdi-progress-alert"></i>Avisos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][6]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listClientes.php"><i class="mdi mdi-account-group"></i>Clientes</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1989]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listDocumentosPadroes.php"><i class="mdi mdi-account-group"></i>Documentos Padrões</a></li>	
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1956]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listEtiquetas.php"><i class="mdi mdi-sticker"></i>Etiquetas</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1943]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listCidades.php"><i class="mdi mdi-office-building"></i>Cidades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1987]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listFavorecidos.php"><i class="mdi mdi-account-group"></i>Favorecidos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listProdutosxServicos.php"><i class="mdi mdi-file-search-outline"></i>Produtos/Serviços</a></li>	
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][18]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listTabelas.php"><i class="mdi mdi-table-edit"></i>Tabelas</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../cadastro/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								    <g>
										<g>
											<path d="M276,68.1v219c0,3.7-2.5,6.8-6,7.7L81.1,343.4c-2.3,0.6-3.6,3.1-2.7,5.4C109.1,426,184.9,480.6,273.2,480
												C387.8,479.3,480,386.5,480,272c0-112.1-88.6-203.5-199.8-207.8C277.9,64.1,276,65.9,276,68.1z"/>
										</g>
											<path class="svg-primary" d="M32,239.3c0,0,0.2,48.8,15.2,81.1c0.8,1.8,2.8,2.7,4.6,2.2l193.8-49.7c3.5-0.9,6.4-4.6,6.4-8.2V36c0-2.2-1.8-4-4-4
											C91,33.9,32,149,32,239.3z"/>
                                    </g>
								</svg>
							<span>CRM</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1973]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1956]['CONSULTA'] == "S") { ?>
							<li><a href="../crm/listTriagem.php"><i class="mdi mdi-magnify"></i>Triagem</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1982]['CONSULTA'] == "S") { ?>
							<li><a href="../crm/listPosicoesCRM.php"><i class="mdi mdi-checkbox-multiple-marked-outline"></i>Posições</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1962]['CONSULTA'] == "S") { ?>
							<li><a href="../crm/listProspeccao.php"><i class="mdi mdi-lightbulb-on-outline"></i>Oportunidades</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../crm/listDashBoard.php"><i class="mdi mdi-calendar-check-outline"></i>Dashboard</a></li>
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
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1887]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listABs.php"><i class="mdi mdi-file-document"></i>ABs</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1886]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listCAs.php"><i class="mdi mdi-file-document-outline"></i>CAs</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1901]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listFolhas.php"><i class="mdi mdi-file-document-box-multiple-outline"></i>Folhas</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1949]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listContratos.php"><i class="mdi mdi-file-document-box-multiple"></i>Contratos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listContratos.php"><i class="mdi mdi-map-search-outline"></i>Mapa de Comissões</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1947]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listNotasFiscais.php"><i class="mdi mdi-receipt"></i>Notas Fiscais</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../crm/listProspeccao.php"><i class="mdi mdi-map-marker-radius"></i>Prospecção</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../comercial/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
								<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
									<path d="M467.3 168.1c-1.8 0-3.5.3-5.1 1l-177.6 92.1h-.1c-7.6 4.7-12.5 12.5-12.5 21.4v185.9c0 6.4 5.6 11.5 12.7 11.5 2.2 0 4.3-.5 6.1-1.4.2-.1.4-.2.5-.3L466 385.6l.3-.1c8.2-4.5 13.7-12.7 13.7-22.1V179.6c0-6.4-5.7-11.5-12.7-11.5zM454.3 118.5L272.6 36.8S261.9 32 256 32c-5.9 0-16.5 4.8-16.5 4.8L57.6 118.5s-8 3.3-8 9.5c0 6.6 8.3 11.5 8.3 11.5l185.5 97.8c3.8 1.7 8.1 2.6 12.6 2.6 4.6 0 8.9-1 12.7-2.7l185.4-97.9s7.5-4 7.5-11.5c.1-6.3-7.3-9.3-7.3-9.3zM227.5 261.2L49.8 169c-1.5-.6-3.3-1-5.1-1-7 0-12.7 5.1-12.7 11.5v183.8c0 9.4 5.5 17.6 13.7 22.1l.2.1 174.7 92.7c1.9 1.1 4.2 1.7 6.6 1.7 7 0 12.7-5.2 12.7-11.5V282.6c.1-8.9-4.9-16.8-12.4-21.4z"></path>
								</svg>
							<span>Estoque/Suprimentos</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1990]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1974]['CONSULTA'] == "S") { ?>
							<li><a href="../estoque/listHistoricoxEstoque.php"><i class="mdi mdi-calendar-clock"></i>Histórico de Estoque</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1952]['CONSULTA'] == "S") { ?>
							<li><a href="../estoque/listProdutos.php"><i class="mdi mdi-file-search-outline"></i>Produtos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1986]['CONSULTA'] == "S") { ?>
							<li><a href="../estoque/listSolicitacaoCompra.php"><i class="mdi mdi-arrow-down-bold-box-outline"></i>Solitação de Compras</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1986]['CONSULTA'] == "S") { ?>
							<li><a href="../estoque/listSolicitacaoSuprimentos.php"><i class="mdi mdi-arrow-down-bold-box-outline"></i>Solitação de Suprimentos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../estoque/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>	
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path d="M32 96v256h448V96H32zm160.5 224h-80.4c0-26.6-21.5-48.1-48.1-48.1V192c35.3 0 64-28.7 64-64h64.5c-19.9 23.5-32.5 57.8-32.5 96s12.6 72.5 32.5 96zM448 271.9c-26 0-48 21.5-48 48.1h-80.5c19.9-23.5 32.5-57.8 32.5-96s-12.6-72.5-32.5-96H384c0 35.3 28.7 64 64 64v79.9zM32 384h448v32H32z"></path>
							</svg>
							<span>Financeiro</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1967]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][4]['CONSULTA'] == "S") { ?>
							<li><a href="../financeiro/listBancos.php"><i class="mdi mdi-bank"></i>Bancos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1980]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-cash"></i>Conciliação Bancária</a>
								<ul class="submenu">
									<li><a href="../financeiro/cadConciliacaoRemessa.php">Gerar Arquivo Remessa</a></li>
									<li><a href="../financeiro/cadConciliacaoRetorno.php">Importar Arquivo Retorno</a></li>
								</ul>
							</li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1983]['CONSULTA'] == "S") { ?>
							<li><a href="../financeiro/listContasPagar.php"><i class="mdi mdi-cash-refund"></i>Contas a Pagar</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1971]['CONSULTA'] == "S") { ?>
							<li><a href="../financeiro/listContasReceber.php"><i class="mdi mdi-cash-multiple"></i>Contas a Receber</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1984]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-cash"></i>Cobranças</a>
								<ul class="submenu">
									<li><a href="../financeiro/cadConciliacaoRetorno.php">Cálculo de Juros</a></li>
									<li><a href="../financeiro/listCobrancas.php">Inadimplentes</a></li>									
								</ul>
							</li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1970]['CONSULTA'] == "S") { ?>
							<li><a href="../financeiro/listContasBancarias.php"><i class="mdi mdi-bank-transfer"></i>Contas Bancárias</a></li>
							<?php } ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Relatórios</a>
								<ul class="submenu">									
									<li><a href="../juridico/listProcessoxFases.php">Fases</a></li>
								</ul>
                            </li>	
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../financeiro/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>	
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path d="M352 144v-39.6C352 82 334 64 311.6 64H200.4C178 64 160 82 160 104.4V144H48v263.6C48 430 66 448 88.4 448h335.2c22.4 0 40.4-18 40.4-40.4V144H352zm-40 0H200v-40h112v40z"></path>
							</svg>
							<span>Jurídico</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1977]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1977]['CONSULTA'] == "S") { ?>
							<li><a href="../juridico/listProcesso.php"><i class="mdi mdi-progress-check"></i>Processos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1977]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Relatórios</a>
								<ul class="submenu">									
									<li><a href="../juridico/listProcessoxFases.php">Fases</a></li>
								</ul>
                            </li>																				
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1977]['CONSULTA'] == "S") { ?>
							<li><a href="../juridico/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul><!--end submenu-->
						<?php } ?>	
					</li><!--end has-submenu-->										
					<li class="has-submenu">
						<a href="#">
							<svg class="nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path d="M405.333 64H106.667C83.198 64 64 83.198 64 106.667v298.666C64 428.802 83.198 448 106.667 448h298.666C428.802 448 448 428.802 448 405.333V106.667C448 83.198 428.802 64 405.333 64zm-192 298.667L106.667 256l29.864-29.864 76.802 76.802 162.136-162.136 29.864 29.865-192 192z"></path>
							</svg>
							<span>Processos</span>
						</a>
						<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1977]['CONSULTA'] == "S") { ?>
						<ul class="submenu">
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1945]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listGuias.php"><i class="mdi mdi-file-search"></i>Guias</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1981]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listAverbacaoContratosINPI.php"><i class="mdi mdi-file-search-outline"></i>Averbação INPI</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1948]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listCodigoBarras.php"><i class="mdi mdi-barcode"></i>Código de Barras</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1944]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listDireitosAutorais.php"><i class="mdi mdi-copyright"></i>Direito Autorais</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1953]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-bookmark-check"></i>Marcas</a>
								<ul class="submenu">
									<li><a href="../processos/listMarcasBrasil.php">Brasil</a></li>
									<li><a href="../processos/listMarcasExterior.php">Exterior</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1963]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-book"></i>Patentes</a>
								<ul class="submenu">
									<li><a href="../processos/listPatentesBrasil.php">Brasil</a></li>
									<li><a href="../processos/listPatentesExterior.php">Exterior</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1939]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listProdutos.php"><i class="mdi mdi-file-search-outline"></i>Produtos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1951]['CONSULTA'] == "S") { ?>
							<li><a href="../processos/listProgramaComputador.php"><i class="mdi mdi-laptop-windows"></i>Programas de Computador</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-compare"></i>Similares</a>
								<ul class="submenu">
									<li><a href="../processos/listPatentesBrasil.php">Marcas</a></li>
									<li><a href="../processos/listPatentesExterior.php">Patentes</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-book-open-page-variant"></i>Importação Revista</a>
								<ul class="submenu">
									<li><a href="../processos/listPatentesBrasil.php">Marcas</a></li>
									<li><a href="../processos/listPatentesExterior.php">Patentes</a></li>
									<li><a href="../processos/listPatentesExterior.php">Softwares</a></li>
									<li><a href="../processos/listPatentesExterior.php">Averbação</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Geração de Cartas</a>
								<ul class="submenu">
									<li><a href="../processos/listPatentesBrasil.php">Processos</a></li>
									<li><a href="../processos/listPatentesExterior.php">Similares</a></li>
									<li><a href="../processos/listPatentesExterior.php">RPI</a></li>
									<li><a href="../processos/listPatentesExterior.php">Prorrogação</a></li>
									<li><a href="../processos/listPatentesExterior.php">Prorrogação</a></li>
								</ul>
                            </li>
							<?php } ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Tabelas</a>
								<ul class="submenu">									
									<li><a href="../processos/listTabelasxClassesxMarcas.php">Classes (Marcas)</a></li>
									<li><a href="../processos/listTabelasxClassesDePara.php">Classes (De/Para)</a></li>
									<li><a href="../processos/listTabelasxContadoresProcessos.php">Contadores de Processos</a></li>
									<li><a href="../processos/listTabelasxDespachosMarcas.php">Despachos Marcas</a></li>
									<li><a href="../processos/listTabelasxNovosDespachosMarcas.php">Novos Despachos Marcas</a></li>
									<li><a href="../processos/listTabelasxDespachosPatentes.php">Despachos Patentes</a></li>
									<li><a href="../processos/listTabelasxDespachosProgComp.php">Despachos Prog. Comp.</a></li>
									<li><a href="../processos/listTabelasxDespachosAverbContratos.php">Despachos Averb. Contratos</a></li>
								</ul>
                            </li>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1954]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
							<?php } ?>
						</ul>
						<?php } ?>	
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
							<li><a href="../rh/listGrupoAcessos.php"><i class="dripicons-user-group"></i>Grupos Acessos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][80]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listLogsAcessos.php"><i class="mdi mdi-progress-check"></i>Logs Acessos</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][80]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Lançamentos</a>
								<ul class="submenu">
									<li><a href="../rh/listLancamentosUsuariosxAdiantamentos.php">Adiantamentos</a></li>
									<li><a href="../rh/listLancamentosUsuariosxFaltas.php">Faltas</a></li>
									<li><a href="../rh/listLancamentosUsuariosxRecebimentos.php">Folha de Pagamento</a></li>
									<li><a href="../rh/listLancamentosUsuariosxValesTransporte.php">Vales</a></li>									
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][80]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Relatórios</a>
								<ul class="submenu">									
									<li><a href="../rh/listUsuariosxContasBancarias.php">Contas Bancárias</a></li>
									<li><a href="../rh/listUsuariosxDesligamentos.php">Desligamento</a></li>
									<li><a href="../rh/listUsuariosxDocumentoPendente.php">Documentos Pendentes</a></li>
									<li><a href="../rh/listUsuariosxEscolaridade.php">Escolaridade</a></li>
									<li><a href="../rh/listUsuariosxFeedback.php">FeedBack</a></li>
									<li><a href="../rh/listUsuariosxFerias.php">Férias</a></li>
									<li><a href="../rh/listUsuariosxRemuneracao.php">Remuneração</a></li>
									<li><a href="../rh/listUsuariosxValesTransporte.php">Vales Transportes</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][2002]['CONSULTA'] == "S") { ?>
							<li class="has-submenu">
								<a href="#"><i class="mdi mdi-email-mark-as-unread"></i>Vales Transportes</a>
								<ul class="submenu">
									<li><a href="../rh/listValesTransporte.php">Configuração</a></li>
								</ul>
                            </li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1957]['CONSULTA'] == "S") { ?>
							<li><a href="../rh/listDashBoard.php"><i class="mdi mdi-monitor-dashboard"></i>Dashboard</a></li>
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
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1988]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listAjuda.php"><i class="mdi mdi-calendar-multiselect"></i>Ajuda Virtual</a></li>								
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listAgenda.php"><i class="mdi mdi-calendar-multiselect"></i>Agenda</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listAgendaCalendario.php"><i class="mdi mdi-calendar-multiselect"></i>Agenda Calendário</a></li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1879]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listGED.php"><i class="mdi mdi-calendar-multiselect"></i>Digitalizações/GED</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1993]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listProtocolos.php"><i class="mdi mdi-calendar-multiselect"></i>Protocolos</a></li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1950]['CONSULTA'] == "S") { ?>
							<li><a href="../utilitarios/listRamais.php"><i class="mdi mdi-phone-log"></i>Ramais</a></li>
							<!--<li><a href="../utilitarios/listSolicitacaoLigacao.php"><i class="mdi mdi-phone-incoming"></i>Solicitar Ligação</a></li>-->
							<?php } ?>
							<li><a href="../utilitarios/cadSolicitacaoSuprimentos.php?method=insert"><i class="mdi mdi-arrow-down-bold-box-outline"></i>Solicitar Suprimentos</a></li>
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
							<li><a href="../sistema/listMenus.php"><i class="mdi mdi-menu-open"></i>Menus</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listEmpresaUsuaria.php"><i class="mdi mdi-factory"></i>Empresa Usuária</a></li>							
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listParametros.php"><i class="dripicons-gear"></i>Parâmetros</a></li>
							<?php } ?>
							<?php if ($_SESSION['SA_ACESSOS']['TABELA'][1975]['CONSULTA'] == "S") { ?>
							<li><a href="../sistema/listPeriodicidades.php"><i class="mdi mdi-calendar-check-outline"></i>Peridiocidades</a></li>
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