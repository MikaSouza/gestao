<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1949);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
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

								<?php
                                    $vAConfiguracaoTela['FILTROS'] = $_POST;
                                    $vAConfiguracaoTela['BTN_FILTROS'] = 'S';
                                    $vAConfig['vATitulos'] = array("Número Contrato", "Cliente", "Produto/Serviço", "Consultor", 'Data de Início', 'Data de Término', "Situação", "Data Cadastro");
                                    $vAConfig['vACampos'] = array("CTRNROCONTRATO", "CLIENTE", "TIPOCONTRATO", "CONSULTOR", 'CTRDATAAINICIO', 'CTRDATATERMINO', "POSICAO", "CTRDATA_INC");
                                    $vAConfig['vATipos'] = array("sequencial", "varchar", "varchar", "varchar", 'date', 'date', "varchar", "datetime");
                                    include_once __DIR__.'/../twcore/teraware/componentes/gridPadrao.php'; ?>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<?php include_once '../includes/footer.php' ?>
	</div>
	<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
		aria-hidden="true">
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
								<label>Nome Cliente</label>
								<input class="form-control" name="vSCLINOME" id="vSCLINOME" type="text" value=""
									title="NOME CLIENTE">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>CNPJ/CPF</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ"
									value="">
							</div>
							<div class="col-md-6">
								<label>Representante</label>
								<select name="vICLIRESPONSAVEL" id="vICLIRESPONSAVEL" class="custom-select"
									title="Representante">
									<option value=""> ------------- </option>
									<?php foreach (comboUsuarios() as $usuarios): ?>
									<option value="<?php echo $usuarios['USUCODIGO']; ?>">
										<?php echo $usuarios['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Contato</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ"
									value="">
							</div>
							<div class="col-md-6">
								<label>E-mail</label>
								<input class="form-control" name="vSCLIEMAIL" id="vSCLIEMAIL" type="text" value=""
									title="NOME CLIENTE">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Cadastro Entre: INÍCIO</label>
								<input class="form-control" name="vDDataInicio" title="Data Início" id="vDDataInicio"
									type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim" type="date"
									maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Posição</label>
								<select name="vSStatusFiltro" id="vSStatusFiltro" class="custom-select" title="Posição">
									<option value=""> ------------- </option>
									<?php foreach (comboTabelas('CONTRATOS - POSICAO') as $tabelas): ?>
									<option value="<?php echo $tabelas['TABCODIGO']; ?>">
										<?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="submit" title="Pesquisar"
									class="btn btn-primary waves-effect waves-light fa-pull-right">Pesquisar</button>
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

</body>

</html>