<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(18);
include_once __DIR__.'/transaction/transactionPadrao.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
		<?php include_once '../includes/scripts_header.php' ?>
		
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
                        <div class="col-lg-10 mx-auto">
                            <div class="card">
                                <div class="card-body">                                   
                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">
										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>										
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<div class="form-group row">
											<div class="col-md-6">
												<label>Tipo
													<small class="text-danger font-13">*</small>
												</label>
												<input type="text" class="form-control is-invalid obrigatorio" name="vSTABTIPO" id="vSTABTIPO" maxlength="100" required title="Tipo" value="<?= $vROBJETO['TABTIPO']; ?>"/>
											</div>
											<div class="col-md-6">												
												<label>Descri????o
													<small class="text-danger font-13">*</small>
												</label>
												<input type="text" class="form-control is-invalid obrigatorio" name="vSTABDESCRICAO" id="vSTABDESCRICAO" title="Descri????o" value="<?= $vROBJETO['TABDESCRICAO']; ?>"/>
											</div>
                                        </div>																				
										<div class="form-group row">
											<div class="col-md-2">
												<label>Cadastro (Status)</label>
												<select class="custom-select" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
													<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
													<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
												</select>
											</div>	
										</div>
										<div class="form-group">
											<label class="form-check-label is-invalid" for="invalidCheck3" style="color: red">
												Campos em vermelho s??o de preenchimento obrigat??rio!
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <?php include_once '../includes/footer.php' ?>
        </div>

        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <!-- Parsley js -->
        <script src="../assets/plugins/parsleyjs/parsley.min.js"></script>
        <script src="../assets/pages/jquery.validation.init.js"></script>

        <script src="../assets/js/jquery.core.js"></script>
		
		<?php include_once '../includes/scripts_footer.php' ?>

    </body>
</html>