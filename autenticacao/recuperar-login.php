<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/transaction/transactionLogin.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <title><?= vSTituloSite; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Teraware Soluções em Software e Internet Ltda"  />
		<meta name="description" content="VIPCustos - Sistema de Gestão de Custos"/>

        <!-- App favicon -->
        <link rel="shortcut icon" href="../assets/images/favicon.ico">

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
                                    <a href="<?= vSEmpresaSite; ?>" class="logo logo-admin"><img src="../assets/images/logotipo.png" height="55" alt="logo" class="auth-logo"></a>
                                </div><!--end auth-logo-box-->

                                <div class="text-center auth-logo-text">
                                    <h4 class="mt-0 mb-3 mt-5">Resetar Senha - VIPCustos</h4>
                                    <p class="text-muted mb-0">Digite seu login e as instruções serão enviadas por email para você!</p>
                                </div> <!--end auth-logo-text-->


                                <form class="form-horizontal auth-form my-4" name="frmLogin" id="frmLogin" action="#" method="post" onsubmit="return validarForm();">


                                    <div class="form-group">
                                        <label for="useremail">Login</label>
                                        <div class="input-group mb-3">
                                            <span class="auth-form-icon">
                                                <i class="dripicons-mail"></i>
                                            </span>
                                            <input type="text" class="form-control obrigatorio" id="vSLoginRecuperar" title="Login" name="vSLoginRecuperar" placeholder="Digite seu login">
                                        </div>
                                    </div><!--end form-group-->


                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit">Enviar <i class="fas fa-sign-in-alt ml-1"></i></button>
                                        </div><!--end col-->
                                    </div> <!--end form-group-->

                                    <div class="m-3 text-center text-muted">
                                        <p class=""><a href="login.php" class="text-primary ml-2">Voltar</a></p>
                                    </div>
                                </form><!--end form-->
                            </div><!--end /div-->

                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end auth-page-->
            </div><!--end col-->
        </div><!--end row-->
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
		<?php if ($_GET['vMGS'] == 'E'){ ?>
			<script type="text/javascript" DEFER="DEFER">
				Swal.fire('Opss..', 'Usuário não existe na base de dados!', 'warning');
			 </script>
		<?php } ?>

    </body>
</html>