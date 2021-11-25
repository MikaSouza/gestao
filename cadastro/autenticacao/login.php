<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
include_once __DIR__ . '/transaction/transactionLogin.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8" />
	<title><?= vSTituloSite; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Teraware Soluções em Software e Internet Ltda" />
	<meta name="description" content=".: Gestão SRV - Sistema de Gestão Empresarial :." />

	<!-- App favicon -->
	<link rel="shortcut icon" href="../assets/images/icon.png">

	<link href="../assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

	<!-- App css -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

</head>

<body class="account-body accountbg">

	<!-- Log In page -->
	<div class="row vh-100 ">
		<div class="col-12 align-self-center">
			<div class="auth-page">
				<div class="card auth-card shadow-lg">
					<div class="card-body">
						<div class="px-3">
							<div class="auth-logo-box">
								<a href="https://gestao-srv.teraware.net.br/autenticacao/login.php" class="logo logo-admin"><img src="../assets/images/logo_menu.png" alt="logo" class="auth-logo" style="border-radius: 0 !important"></a>
							</div>
							<!--end auth-logo-box-->

							<div class="text-center auth-logo-text">
								<h4 class="mt-0 mb-3 mt-5">InfoGestão</h4>
							</div>
							<!--end auth-logo-text-->

							<form class="form-horizontal auth-form my-4" name="frmLogin" id="frmLogin" action="#" method="post" onsubmit="return validarForm();">

								<div class="form-group">
									<label for="vSUsuario">Login/E-mail</label>
									<div class="input-group mb-3">
										<span class="auth-form-icon">
											<i class="dripicons-mail"></i>
										</span>
										<input type="text" class="form-control obrigatorio" title="Login/E-mail" id="vSUsuario" name="vSUsuario" maxlength="150" placeholder="Digite seu login/e-mail">
									</div>
								</div>
								<!--end form-group-->

								<div class="form-group">
									<label for="vSSenha">Senha</label>
									<div class="input-group mb-3">
										<span class="auth-form-icon">
											<i class="dripicons-lock"></i>
										</span>
										<input type="password" class="form-control obrigatorio" title="Senha" id="vSSenha" name="vSSenha" autocomplete="false" maxlength="100" placeholder="Digite sua senha">
									</div>
								</div>
								<!--end form-group-->

								<div class="form-group row mt-4">
									<div class="col-sm-6">
										<div class="custom-control custom-switch switch-success">

										</div>
									</div>
									<!--end col-->
									<div class="col-sm-6 text-right">
										<a href="recuperar-login.php" class="text-muted font-13"><i class="dripicons-lock"></i> Lembrar senha?</a>
									</div>
									<!--end col-->
								</div>
								<!--end form-group-->

								<div class="form-group mb-0 row">
									<div class="col-12 mt-2">
										<button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">Entrar <i class="fas fa-sign-in-alt ml-1"></i></button>
									</div>
									<!--end col-->
								</div>
								<!--end form-group-->
							</form>
							<!--end form-->
						</div>
						<!--end /div-->

					</div>
					<!--end card-body-->
				</div>
				<!--end card-->
				<div class="row mt-2 px-5">
					<div class="col-1"></div>

					<div class="col-10 text-center">
						<a href="http://teraware.com.br" class="" target="_blank" title="Teraware - ERP | Sistemas Web | ERP Customizados | Projetos Especiais" alt="Teraware - ERP | Sistemas Web | ERP Customizados | Projetos Especiais">
							<img src="../assets/images/tw-icone.png" height="35" alt="logo" class="auth-logo">
						</a>
					</div>
					<div class="col-1"></div>
				</div>
				<!--end account-social-->
			</div>
			<!--end auth-page-->
		</div>
		<!--end col-->
	</div>
	<!--end row-->
	<!-- End Log In page -->

	<!-- jQuery  -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/metisMenu.min.js"></script>
	<script src="../assets/js/waves.min.js"></script>
	<script src="../assets/js/jquery.slimscroll.min.js"></script>

	<!-- Sweet-Alert  -->
	<script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
	<script src="../assets/pages/jquery.sweet-alert.init.js"></script>

	<!-- App js -->
	<script src="../assets/js/app.js"></script>

	<!-- Funcoes js -->
	<script src="js/funcoes.js"></script>
	<?php if ($_GET['vMGS'] == 'E') { ?>
		<script type="text/javascript" DEFER="DEFER">
			Swal.fire('Opss..', 'Login ou Senha inválidos!', 'warning');
		</script>
	<?php } ?>
</body>

</html>