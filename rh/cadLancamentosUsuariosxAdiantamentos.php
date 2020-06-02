<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2007);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?>

        <link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
        <link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

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
                    <?php include_once '../includes/breadcrumb.php' ?>

					<div class="row">
							<div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                 
                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">

                                    <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
                                    <input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>

                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>                                        
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-2">
													<label>Mês Competência
														<small class="text-danger font-13">*</small>
													</label>										
													<select class="form-control obrigatorio" name="vILUAMES" id="vILUAMES" title="Mês Competência">
														<option value="1" <?= ($vIMES == '1' ? 'selected' : ''); ?>>Janeiro</option>
														<option value="2" <?= ($vIMES == '2' ? 'selected' : ''); ?>>Fevereiro</option>
														<option value="3" <?= ($vIMES == '3' ? 'selected' : ''); ?>>Março</option>
														<option value="4" <?= ($vIMES == '4' ? 'selected' : ''); ?>>Abril</option>
														<option value="5" <?= ($vIMES == '5' ? 'selected' : ''); ?>>Maio</option>
														<option value="6" <?= ($vIMES == '6' ? 'selected' : ''); ?>>Junho</option>
														<option value="7" <?= ($vIMES == '7' ? 'selected' : ''); ?>>Julho</option>
														<option value="8" <?= ($vIMES == '8' ? 'selected' : ''); ?>>Agosto</option>
														<option value="9" <?= ($vIMES == '9' ? 'selected' : ''); ?>>Setembro</option>
														<option value="10" <?= ($vIMES == '10' ? 'selected' : ''); ?>>Outubro</option>
														<option value="11" <?= ($vIMES == '11' ? 'selected' : ''); ?>>Novembro</option>
														<option value="12" <?= ($vIMES == '12' ? 'selected' : ''); ?>>Dezembro</option>
													</select>
												</div>
												<div class="col-md-2"> 
													<label>Ano Competência
														<small class="text-danger font-13">*</small>
													</label>
													<input type="text" class="form-control obrigatorio" name="vILUAANO" id="vILUAANO" title="Ano Competência" value="<?= $vIANO; ?>" />
												</div>														
											</div>
											<div class="form-group row">
												<div id="div_colaboradores" class="table-responsive"></div>
												<?php
												$arrayDados = array(
													'vILUAMES' => $vIMES,
													'vILUAANO' => $vIANO
												);
												listLancamentosUsuariosxAdiantamentosFilhos($arrayDados);
												?>
											</div>   
																						
                                        </div>
                                        <!-- Aba Dados Gerais end -->

										<div class="form-group row">
											<label class="form-check-label" for="invalidCheck3" style="color: red">
												Campos em vermelho são de preenchimento obrigatório!<br/>
												Para o usuário não ter mais acesso ao sistema deve estar Inativado ou com a Data Demissão preenchida.
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>
                                    </div>
                                    </form><!--end form-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->

                    </div><!--end row-->

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
        <script src="../assets/plugins/select2/select2.min.js"></script>
        
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Usuarios js -->
		<script src="js/cadLancamentosUsuariosxBeneficios.js"></script>

    </body>
</html>