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
	<?php include_once '../includes/scripts_header.php' ?>

	<!-- DataTables -->
	<link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<!-- Responsive datatable examples -->
	<link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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

				<div class="row">
					<div class="col-lg-12 mx-auto">
						<div class="card">
							<div class="card-body invoice-head">
								<div class="row">
									<div class="col-lg-4">
										<div class="card client-card">
											<div class="card-body text-center">
												<h5 class=" client-name"><?= $vROBJETOHOME['CLINOMEFANTASIA']; ?></h5> <br />
												<span class="text-muted mr-3"><i class="fas fa-crosshairs mr-2 text-info"></i><?= $vRENDERECOHOME['CIDDESCRICAO']; ?> <?= $vRENDERECOHOME['ESTSIGLA']; ?></span><br />
												<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i>
													<?php if (isset($vRCONTATOHOME['CONFONE'])) echo $vRCONTATOHOME['CONFONE'];
													else echo '';
													?>
													<?php if (isset($vRCONTATOHOME['CONCELULAR'])) echo $vRCONTATOHOME['CONCELULAR'];
													else echo '';
													?>
												</span>
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
													<span class="text-muted mr-3"><i class="dripicons-mail mr-2 text-info"></i><?= $contatos_cliente['CONEMAIL']; ?></span><br>
													<!--<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i><? //= $contatos_cliente['CONCELULAR'];
																																?></span>-->
													<span class="text-muted"><i class="	fas fa-phone mr-2 text-info"></i><?= $contatos_cliente['CONFONE']; ?></span>
													&nbsp; &nbsp;<span class="text-muted mr-3"><i class="far fa-address-book mr-2 text-info"></i>Cargo: &nbsp;<?= $vRCONTATOHOME['CONCARGO']; ?></span><br>

												</div>
											</div>
										</div></br>
									<?php endforeach ?>

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
								<h4 class="header-title mt-0 mb-3" style="text-align: center !important;"><b>Orientações Técnicas</b></h4>
								<div class="table-responsive dash-social" style="overflow:hidden">
									<form id="frmGrid" name="frmGrid">
										<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;display: block; overflow-x: auto;">
											<thead>
												<tr>
													<th>Número</th>
													<th>Ano</th>
													<th>Data Publicação</th>
													<th>Anexo(s)</th>
													<th>Título</th>
													<!-- <th>Orientação Técnica</th> -->
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
															<td align="center"><?= $result1['OXTNUMERO']; ?></td>
															<td align="left"><?= $result1['OXTANO']; ?></td>
															<td align="center"><?= formatar_data($result1['OXTDATA_INC']); ?></td>
															<td align="center">
																<?php // verificar se tem mais orientações
																$anexos = listOrientacaoTecnicaPainelAnexos($result1['OXTCODIGO']);
																if (count($anexos) > 0) :
																	foreach ($anexos['dados'] as $resultAnexos) :
																		$linkArquivo = $resultAnexos['LINK'];
																		$nomeArquivo = $resultAnexos['GEDNOMEARQUIVO'];
																?>
																		<a href="<?= $linkArquivo; ?>" title="<?= $nomeArquivo ?>" alt="<?= $nomeArquivo ?>" target="_blank"><i class="fas fa-file" style="color: #50649c;"></i></a>
																<?php endforeach;
																endif; ?>
															</td>
															<td align="justify"><a href="<?= $link ?>" target="_blank" title="Baixar Arquivo"> <i class="fas fa-download" style="color: #50649c;"> &nbsp;&nbsp; &nbsp;&nbsp;</i> <?= $result1['OXTTITULO']; ?></a></td>
															<!-- <td align="center"><a href="<? //= $link;
																								?>" title="Baixar Arquivo" target="_blank"><button type="button" class="btn btn-outline-warning"><i class="fas fa-download"></i></button></a>
													</td> -->
														</tr>
														<!--end tr-->
													<?php endforeach ?>
												<?php
												else :
													echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há Orientações Técnicas!</span>';
												endif;
												?>
											</tbody>
										</table>
										<!--end table-->
									</form>
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

	<!-- Required datatable js -->
	<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
	<!-- Botões de Exemplos -->
	<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
	<script src="../assets/plugins/datatables/jszip.min.js"></script>
	<script src="../assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="../assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.print.min.js"></script>
	<script src="../assets/plugins/datatables/buttons.colVis.min.js"></script>
	<!-- Responsive examples -->
	<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
	<script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
	<script src="../assets/pages/jquery.datatable.init.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>
	<!-- <script src="js/listClientes.js"></script> -->

</body>

</html>