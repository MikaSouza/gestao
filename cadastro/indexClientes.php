<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
include_once '../utilitarios/transaction/transactionAgenda.php';
//$dados['start'] = date('Y-m-d', strtotime('-7 days', date('Y-m-d')));
//$dados['end'] = date('Y-m-d', strtotime('+1 days', date('Y-m-d')));
include_once __DIR__ . '/transaction/transactionClientes.php';
include_once __DIR__ . '/transaction/transactionEnderecos.php';
include_once __DIR__ . '/transaction/transactionContatos.php';

//buscar o contrato
include_once '../comercial/transaction/transactionContratos.php';
$contrato_ativo =  fill_Contratos($_SESSION['SI_CTRCODIGO']);



if (($_GET['id'] == 0) || ($_GET['id'] == 0)) {
	echo "<script language='javaScript'>window.location.href='index.php'</script>";
	return;
}

$vROBJETOHOME = fill_ClientesHome($_GET['id']);
$vRENDERECOHOME = fill_EnderecosHome($_GET['id'], 'S');
$vRCONTATOHOME = fill_ContatosHome($_GET['id'], 'N');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8" />
	<?php include_once '../includes/scripts_header.php' ?>

	<!-- DataTables -->
	<link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<!-- Responsive datatable examples -->
	<link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

	<link href="../assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

	<!-- App css -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>

	<?php include_once '../includes/menuClientes.php' ?>

	<div class="page-wrapper">


		<!-- Page Content-->
		<div class="page-content">

			<div class="container-fluid">
				<!-- Page-Title -->
				<div class="row">
					<div class="col-sm-12">
						<div class="page-title-box">
							<div class="float-right">
								<!-- <ol class="breadcrumb">
									<li class="breadcrumb-item active">Capa</li>
								</ol> -->
								<!--end breadcrumb-->
							</div>
							<!--end /div-->
							<!-- <h4 class="page-title">Capa</h4> -->
						</div>
						<!--end page-title-box-->
					</div>
					<!--end col-->
				</div>
				<!--end row-->
				<!-- end page title end breadcrumb -->

				<div class="row">
					<div class="col-lg-12 mx-auto">
						<div class="card">
							<div class="card-body invoice-head">
								<div class="row">
									<div class="col-lg-4">
										<div class="card client-card">
											<div class="card-body text-center">
												<h5 class=" client-name"><?= $vROBJETOHOME['CLINOMEFANTASIA']; ?></h5> <br />
												<span class="text-muted mr-3"><i class="dripicons-location mr-2 text-info"></i><?= $vRENDERECOHOME['CIDDESCRICAO']; ?><?= $vRENDERECOHOME['ESTSIGLA']; ?></span><br />
												<span class="text-muted"><i class="dripicons-phone mr-2 text-info"></i>
													<?php if (isset($vRCONTATOHOME['CONFONE'])) echo $vRCONTATOHOME['CONFONE'];
													else echo '';
													?>
													<?php if (isset($vRCONTATOHOME['CONCELULAR'])) echo $vRCONTATOHOME['CONCELULAR'];
													else echo '';
													?></span>
											</div>
										</div>
									</div>
									<?php $contatos_cliente = listContatosHome($_GET['id']);
									foreach ($contatos_cliente['dados'] as $contatos_cliente) : ?>
										<div class="col-lg-4">
											<div class="card client-card">
												<div class="card-body text-center">
													<h5 class=" client-name">
														<?= $contatos_cliente['CONNOME']; ?><br /><?= $contatos_cliente['CONCARGO']; ?>
													</h5>
													<span class="text-muted mr-3"><i class="dripicons-mail mr-2 text-info"></i><?= $contatos_cliente['CONEMAIL']; ?></span>
													<span class="text-muted"><i class="dripicons-phone mr-2 text-info"></i><?= $contatos_cliente['CONFONE']; ?>
														<?= $contatos_cliente['CONCELULAR']; ?></span>
												</div>
											</div>
										</div>
									<?php endforeach ?>
									<div class="col-lg-4">
										<div class="card client-card">
											<div class="card-body text-center"><br /><br />
												<span class="text-muted mr-3"><i class="dripicons-web mr-2 text-info"></i>Cargo:<?= $vRCONTATOHOME['CONCARGO'];
																																?></span><br>
												<span class="text-muted"><i class="dripicons-music mr-2 text-info"></i>Setor:
													<?= $vRCONTATOHOME['CONSETOR'];
													?></span><br />
												<!-- <span class="text-muted"><i class="dripicons-pulse mr-2 text-info"></i>95% Online</span> -->
											</div>
										</div>
									</div>
								</div>
								<!--end row-->
							</div>
							<!--end card-body-->
						</div>
						<!--end row-->
					</div>
					<!--end row-->
				</div>
				<!--end row-->

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h4 class="header-title mt-0 mb-3">Orientações Técnicas</h4>
								<div class="table-responsive">
									<table class="table table-hover mb-0">
										<thead class="thead-light">
											<tr>
												<th class="border-top-0">Número</th>
												<th class="border-top-0">Ano</th>
												<th class="border-top-0">Título</th>
												<th class="border-top-0">Visualizar<br /> Orientação</th>
												<th class="border-top-0">Visualizar<br /> Anexos</th>
											</tr>
										</thead>
										<tbody>
											<?php
											include_once '../helpdesk/transaction/transactionOrientacaoTecnica.php';
											$_POST['vDDataInicio'] = $contrato_ativo['CTRDATAAINICIO'];
											$atividades_pendentes = listOrientacaoTecnicaPainel($_POST);
											if (count($atividades_pendentes) > 0) :
												foreach ($atividades_pendentes['dados'] as $result1) :
													if ($result1['OXTNUMERO'] != '')
														$link = 'https://gestao-srv.twflex.com.br/ged/orientacao_tecnica/' . $result1['OXTNUMERO'] . '_' . $result1['OXTANO'] . '.pdf';
											?>
													<tr>
														<td align="right"><?= $result1['OXTNUMERO']; ?></td>
														<td align="right"><?= $result1['OXTANO']; ?></td>
														<td align="left"><?= $result1['OXTTITULO']; ?></td>
														<td align="center"><a href="<?php echo $link; ?>" title="Baixar Arquivo" target="_blank"><button type="button" class="btn btn-outline-warning"><i class="fas fa-file-audio"></i></button></a>
														</td>
														<td align="center">
															<?php // verificar se tem mais orientações
															$anexos = listOrientacaoTecnicaPainelAnexos($result1['OXTCODIGO']);
															if (count($anexos) > 0) :
																foreach ($anexos['dados'] as $resultAnexos) :
																	$linkArquivo = $resultAnexos['LINK'];
															?>
																	<a href="<?= $linkArquivo; ?>" title="Baixar Arquivo" target="_blank"><button type="button" class="btn btn-outline-warning"><i class="fas fa-file-audio"></i></button></a>
															<?php endforeach;
															endif; ?>
														</td>
													</tr>
													<!--end tr-->
												<?php endforeach ?>
											<?php
											else :
												echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há orientações técnicas!</span>';
											endif;
											?>
										</tbody>
									</table>
									<!--end table-->
								</div>
								<!--end /div-->
							</div>
							<!--end card-body-->
						</div>
						<!--end card-->
					</div>
					<!--end col-->
				</div>

			</div><!-- container -->
		</div>
		<?php include_once '../includes/footer.php' ?>
	</div>
	<!-- end page-wrapper -->

	<!-- jQuery  -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/metisMenu.min.js"></script>
	<script src="../assets/js/waves.min.js"></script>
	<script src="../assets/js/jquery.slimscroll.min.js"></script>

	<script src="../assets/plugins/moment/moment.js"></script>
	<script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="../assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
	<script src="../assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="../assets/pages/jquery.crm_dashboard.init.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>

</body>

</html>