<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__ . '/transaction/' . $vAConfiguracaoTela['MENARQUIVOTRAN'];
$vROBJETO = fill_Usuario($_SESSION['SI_USUCODIGO'], 'array');
$vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'CODIGO'];
$vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'] . 'STATUS'];
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

	<?php include_once '../includes/menu.php' ?>

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
									<li class="breadcrumb-item"><a href="index.php">Home</a></li>
									<li class="breadcrumb-item"><a href="<?= $vAConfiguracaoTela['MENARQUIVOLIST']; ?>">Consultar</a></li>
									<li class="breadcrumb-item active"><?= $vAConfiguracaoTela['MENTITULO']; ?></li>
								</ol>
								<!--end breadcrumb-->
							</div>
							<!--end /div-->
							<h4 class="page-title">Cadastro de <?= $vAConfiguracaoTela['MENTITULO']; ?></h4>
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
									<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO" value="<?php echo $vIOid; ?>" />
									<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if (isset($_GET['method'])) {
																										echo $_GET['method'];
																									} else {
																										echo "insert";
																									} ?>" />
									<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>" />
									<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>" />
									<input type="hidden" name="vHURL" id="vHURL" value="contaUsuario.php" />
									<!-- <input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1" /> -->

									<div class="form-group row">
										<div class="col-md-4 col-lg-4">
											<input type="file" name="vHUSUFOTO" id="vHUSUFOTO" class="dropify" data-default-file="<?= ($vROBJETO['USUFOTO'] == '' ? '../assets/images/users/user-1.jpg' : '../ged/usuarios_fotos/' . $vROBJETO['USUFOTO']); ?>" style="max-height: 130px">
										</div>
										<div class="col-md-8 col-lg-8">
											<div class="col-lg-12">
												<label>Nome Completo
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control  obrigatorio" name="vSUSUNOME" id="vSUSUNOME" type="text" value="<?= $vROBJETO['USUNOME'] ?>" title="Nome Completo">
											</div>
											<div class="col-lg-12">
												<label>E-mail Alternativo/Pessoal</label>
												<input class="form-control" name="vSUSUEMAILALT" id="vSUSUEMAILALT" type="email" value="<?= $vROBJETO['USUEMAILALT'] ?>">
											</div>
											<div class="col-lg-4 fa-pull-left">
												<label>Data de Nascimento</label>
												<input class="form-control" name="vDUSUDATA_NASCIMENTO" id="vDUSUDATA_NASCIMENTO" type="text" value="<?= formatar_data($vROBJETO['USUDATA_NASCIMENTO']) ?>" maxlength="10" onKeyPress="return digitos(event, this);" onKeyUp="mascara('DATA',this,event);">
											</div>
											<div class="col-lg-4 fa-pull-right">
												<label>Senha</label>
												<input class="form-control  obrigatorio" name="vSUSUSENHA" id="vSUSUSENHA" value="<?= $vROBJETO['USUSENHA'] ?>" type="password" title="Senha">
											</div>
											<div class="col-lg-4 fa-pull-right">
												<label>Celular</label>
												<input class="form-control" name="vSUSUCELULAR" id="vSUSUCELULAR" type="text" value="<?= $vROBJETO['USUCELULAR'] ?>" maxlength="14" onKeyPress="return digitos(event, this);" onKeyUp="mascara('CEL',this,event);">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="form-check-label" for="invalidCheck3" style="color: red">
											Campos em vermelho são de preenchimento obrigatório!</label>
									</div>
									<?php include('../includes/botoes_cad_novo.php'); ?>
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