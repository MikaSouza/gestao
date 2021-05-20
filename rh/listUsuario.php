<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once '../sistema/combos/comboEmpresaUsuaria.php';
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
								<a href="cadUsuario.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo
										Registro</button>
								</a>
								<button type="button" class="btn btn-primary waves-effect waves-light float-right"
									data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+
									Filtros</button>
								<div class="table-responsive dash-social" style="overflow:hidden">
									<table id="datatable-buttons"
										class="table table-striped table-bordered dt-responsive nowrap"
										style="height: 430px; overflow: auto; width: 100%">
										<thead>
											<tr>
												<th>Acesso</th>
												<th width="25px">AÇÕES</th>
												<th>Nome</th>
												<th>E-mail</th>
												<th>Cargo</th>
												<th>Departamento</th>
												<th>Data<br /> Nascimento</th>
												<th>Data<br />Admissão</th>
												<th>Ativo</th>
											</tr>
										</thead>
										<?php
										if ($_POST['vSUSUSITUACAO'] == '')
											$_POST['vSUSUSITUACAO'] = 'A';
										$result = listUsuario($_POST);
										$vITotalRegistros =  $result['quantidadeRegistros'];
										foreach ($result['dados'] as $result) :?>
										<tr>
											<td align="center"><input type='checkbox' title='ckPadrao'
													name='vEnviarAcesso[]' value='<?= $result['USUCODIGO'];?>'
													id='vEnviarAcesso[]' /></td>
											<td align="center">
												<a href="cadUsuario.php?oid=<?= $result['USUCODIGO'];?>&method=update"
													class="mr-2" title="Editar Registro" alt="Editar Registro"><i
														class="fas fa-edit text-info font-16"></i></a>
												<a href="#"
													onclick="excluirRegistroGrid('<?= $result['USUCODIGO']?>', 'transactionUsuario.php', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')"
													title="Excluir Registro" alt="Excluir Registro"><i
														class="fas fa-trash-alt text-danger font-16"></i></a>
											</td>
											<td align="left"><?= $result['USUNOME'];?></td>
											<td align="left"><?= $result['USUEMAIL'];?></td>
											<td align="left"><?= $result['CARGO'];?></td>
											<td align="left"><?= $result['DEPARTAMENTO'];?></td>
											<td align="center"><?= $result['DATA_NASCIMENTO'];?></td>
											<td align="center"><?= $result['DATA_ADMISSAO']; ?></td>
											<td align="left"><?= $result['STATUS'];?></td>
										</tr>
										<?php endforeach; ?>
									</table>
								</div>
								<button type="button" title="Enviar Acesso" style="width:150px"
									onclick="enviarAcessos();" class="btn btn-primary waves-effect waves-light">Enviar
									Acesso</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include_once '../includes/footer.php' ?>
	</div>

	<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="modalFiltros"
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
							<div class="col-md-12">
								<label>Nome</label>
								<input class="form-control" name="vSUSUNOME" id="vSUSUNOME" type="text" value=""
									title="Nome">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>CPF</label>
								<input class="form-control" name="vSUSUCPF" id="vSUSUCPF" type="text" title="CPF"
									value="">
							</div>
							<div class="col-md-6">
								<label>E-mail</label>
								<input class="form-control" name="vSUSUEMAIL" id="vSUSUEMAIL" type="text" value=""
									title="E-mail">
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
								<label>Data Admissão Entre: INÍCIO</label>
								<input class="form-control" name="vDDataAdmissaoInicio" title="Data Admissão"
									id="vDDataAdmissaoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataAdmissaoFim" title="Data Fim"
									id="vDDataAdmissaoFim" type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Demissão Entre: INÍCIO</label>
								<input class="form-control" name="vDDataDemissaoInicio" title="Data Demissão"
									id="vDDataDemissaoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataDemissaoFim" title="Data Fim"
									id="vDDataDemissaoFim" type="date" maxlength="10">
							</div>
						</div>
						<div class="form-froup row">
							<div class="col-md-6">
								<label>Departamento/Setor</label>
								<select name="vITABDEPARTAMENTO" id="vITABDEPARTAMENTO" class="custom-select"
									title="Departamento/Setor">
									<option value></option>
									<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
									<option value="<?php echo $tabelas['TABCODIGO']; ?>">
										<?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Cargo</label>
								<select name="vITABCARGO" id="vITABCARGO" class="custom-select" title="Cargo">
									<option value></option>
									<?php foreach (comboTabelas('USUARIOS - CARGOS') as $tabelas): ?>
									<option value="<?php echo $tabelas['TABCODIGO']; ?>">
										<?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div></br>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Situação</label>
								<select name="vSUSUSITUACAO" id="vSUSUSITUACAO" class="custom-select" title="Situação">
									<option value="T">Todos</option>
									<option selected="selected" value="A">Admitidos</option>
									<option value="D">Demitidos</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Filial</label>
								<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select" title="Filial">
									<option value></option>
									<?php foreach (comboEmpresaUsuaria('') as $tabelas): ?>
									<option value="<?php echo $tabelas['EMPCODIGO']; ?>">
										<?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
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

	<script src="js/listUsuarios.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>

</body>

</html>