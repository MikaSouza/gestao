<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(6);
include_once __DIR__ . '/transaction/' . $vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__ . '/../cadastro/combos/comboTabelas.php';
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

	<?php include_once '../includes/menu.php' ?>

	<div class="page-wrapper">

		<div class="page-content">

			<div class="container-fluid">
				<?php include_once '../includes/breadcrumb.php' ?>

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<a href="cadClientes.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
								</a>
								<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
								<div class="table-responsive dash-social" style="overflow:hidden">
									<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="height: 430px; overflow: auto; width: 100%">
										<thead>
											<tr>
												<th>Enviar I.P.</th>
												<th>Info. Pre.</th>
												<th width="25px">AÇÕES</th>
												<th>Razão Social</th>
												<th>Nome Fantasia</th>
												<th>CPF/CNPJ</th>
												<th>Data Inclusão</th>
												<th>Ativo</th>
											</tr>
										</thead>
										<?php
										$result = listClientes($_POST);
										$vITotalRegistros =  $result['quantidadeRegistros'];
										foreach ($result['dados'] as $result) : ?>
											<tr>
												<td align="center"><input type='checkbox' title='ckPadrao' name='vEnviarEmail[]' value='<?= $result['CLICODIGO']; ?>' id='vEnviarEmail[]' /></td>
												<td align="center">
													<a href="cadInformacoesPreliminares.php?oid=<?= $result['CLICODIGO']; ?>&method=update">
														<button type="button" class="btn btn-secondary waves-effect">Info. Pre.</button>
													</a>
												</td>
												<td align="center">
													<a href="cadClientes.php?oid=<?= $result['CLICODIGO']; ?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
													<a href="#" onclick="excluirRegistroGrid('<?= $result['CLICODIGO'] ?>', 'transactionClientes.php', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO']; ?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
												</td>
												<td align="left"><?= $result['CLIRAZAOSOCIAL']; ?></td>
												<td align="left"><?= $result['CLINOMEFANTASIA']; ?></td>
												<td align="left"><?= $result['CNPJCPF']; ?></td>
												<td align="center"><?= formatar_data_hora($result['CLIDATA_INC']); ?></td>
												<td align="left"><?= ativoSimNao($result['CLISTATUS']); ?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								</div>
								<button type="button" title="Enviar E-mail" style="width:150px" onclick="enviarEmailInfoPreliminares();" class="btn btn-primary waves-effect waves-light">Enviar E-mail</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<?php include_once '../includes/footer.php' ?>
	</div>
	<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title mt-0" id="exampleModalLabel">Filtros</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formPesquisar" id="formPesquisar">
						<div class="form-group row">
							<div class="col-md-6">
								<label>Cliente</label>
								<input class="form-control" name="vSCLINOME" id="vSCLINOME" type="text" value="" title="Cliente">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>CNPJ/CPF</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ/CPF" value="">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Contato</label>
								<input class="form-control" name="vSCLICONTATO" id="vSCLICONTATO" type="text" title="Contato" value="">
							</div>
							<div class="col-md-6">
								<label>E-mail</label>
								<input class="form-control" name="vSCLIEMAIL" id="vSCLIEMAIL" type="text" value="" title="E-mail">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Cadastro Entre: INÍCIO</label>
								<input class="form-control" name="vDDataInicio" title="Data Início" id="vDDataInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim" type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-4">
								<label>Ativo</label>
								<select class="form-control" id="vSStatusFiltro" name="vSStatusFiltro">
									<option value="A">Ambos</option>
									<option value="N">Não</option>
									<option value="S" selected>Sim</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="submit" title="Pesquisar" class="btn btn-primary waves-effect waves-light fa-pull-right">Pesquisar</button>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

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
	<script src="js/listClientes.js"></script>
</body>

</html>