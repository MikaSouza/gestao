<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
if (!isset($_SESSION['SI_USUCODIGO'])) {
	echo "<script language='javaScript'>window.location.href='../autenticacao/login.php'</script>";
	return;
}
include_once __DIR__ . '/transaction/transactionContatoCliente.php';
$vROBJETO = showContatoCliente($_SESSION['SI_USUCODIGO'], 'array');
$vIOid = $vROBJETO['CONCODIGO'];
$vSDefaultStatusCad = $vROBJETO['CONSTATUS'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php include_once '../includes/scripts_header.php' ?>

	<link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
	<link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />

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
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="indexClientes.php?id=<?= $_GET['id'] ?>">Home</a></li>
									<li class="breadcrumb-item active">Conta do Usuário</li>
								</ol>
								<!--end breadcrumb-->
							</div>
							<!--end /div-->
							<h4 class="page-title">Cadastro de Dados do Usuário</h4>
						</div>
						<!--end page-title-box-->
					</div>
					<!--end col-->
				</div>
				<!--end row-->
				<!-- end page title end breadcrumb -->

				<div class="row">
					<div class="col-lg-10 mx-auto">
						<div class="card">
							<div class="card-body">
								<!-- <h4 class="mt-0 header-title">Cadastro de <?= $vAConfiguracaoTela['MENTITULO']; ?></h4>-->

								<form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>" enctype="multipart/form-data">
									<input type="hidden" name="vICONCODIGO" id="vICONCODIGO" value="<?php echo $vIOid; ?>" />
									<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if (isset($_GET['method'])) {
																										echo $_GET['method'];
																									} else {
																										echo "insert";
																									} ?>" />
									<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>" />
									<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>" />
									<input type="hidden" name="vHURL" id="vHURL" value="contaCliente.php" />

									<div class="form-group row">
										<div class="col-md-4 col-lg-4">
											<input type="file" name="vHCONFOTO" id="vHCONFOTO" class="dropify" data-default-file="<?= ($vROBJETO['CONFOTO'] == '' ? '../assets/images/users/user-1.jpg' : '../ged/contatos_fotos/' . $vROBJETO['CONFOTO']); ?>" style="max-height: 130px">
										</div>
										<div class="col-md-8 col-lg-8">
											<div class="col-lg-12">
												<label>Nome Completo
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" name="vSCONNOME" id="vSCONNOME" type="text" value="<?= $vROBJETO['CONNOME'] ?>" title="Nome Completo">
											</div>
											<div class="col-lg-8 fa-pull-left">
												<label>E-mail
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" name="vSCONEMAIL" id="vSCONEMAIL" type="email" value="<?= $vROBJETO['CONEMAIL'] ?>" title="E-mail">
											</div>
											<div class="col-lg-4 fa-pull-right">
												<label>Senha Portal</label>
												<input class="form-control" title="Senha Portal" name="vSCONSENHA" id="vSCONSENHA" type="text" value="<?= $vROBJETO['CONSENHA'] ?>">
											</div>
											<div class="col-lg-4 fa-pull-left">
												<label>Telefone</label>
												<input type="text" id="vSCONCELULAR" name="vSCONCELULAR" class="form-control" maxlength="15" title="Telefone Celular" value="<?= $vROBJETO['CONCELULAR'] ?>" onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
											</div>
											<div class="col-lg-4 fa-pull-right">
												<label>Cargo</label>
												<input class="form-control" name="vSCONCARGO" id="vSCONCARGO" type="text" value="<?= $vROBJETO['CONCARGO'] ?>" maxlength="50">
											</div>
											<div class="col-lg-4 fa-pull-left">
												<label>Setor/Lotação</label>
												<input class="form-control" name="vSCONSETOR" id="vSCONSETOR" type="text" value="<?= $vROBJETO['CONSETOR'] ?>" maxlength="50">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="form-check-label" for="invalidCheck3" style="color: red">
											Campos em vermelho são de preenchimento obrigatório!</label>
									</div>
									<div class="col-md-6 fa-pull-left">
										<button type="button" onClick="validarForm();" title="Salvar Dados" class="btn btn-primary waves-effect waves-light">Salvar Dados</button>
										<a href="indexClientes.php?id=<?= $_GET['id'] ?>">
											<button type="button" title="Retornar" class="btn btn-warning waves-effect m-l-5">Voltar</button>
										</a>
									</div>
								</form>
								<!--end form-->
							</div>
							<!--end card-body-->
						</div>
						<!--end card-->
					</div>
					<!--end col-->

				</div>
				<!--end row-->

			</div><!-- container -->
		</div>
		<!-- end page content -->
		<?php include_once '../includes/footer.php' ?>
	</div>
	<!-- end page-wrapper -->

	<!-- jQuery  -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/metisMenu.min.js"></script>
	<script src="../assets/js/waves.min.js"></script>
	<script src="../assets/js/jquery.slimscroll.min.js"></script>

	<!-- Sweet-Alert  -->
	<script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
	<script src="../assets/pages/jquery.sweet-alert.init.js"></script>

	<script src="../assets/plugins/dropify/js/dropify.min.js"></script>
	<script src="../assets/pages/jquery.profile.init.js"></script>

	<script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
	<script src="../assets/pages/jquery.gallery.inity.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>
	<!-- Cad Usuarios js -->
	<script src="js/cadUsuarios.js"></script>

</body>

</html>