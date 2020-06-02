<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/transaction/transactionContasBancarias.php';

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateContasBancarias($_POST, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_ContasBancarias($_GET['oid']);
    $vIOid = $vROBJETO['CBACODIGO'];
    $vSDefaultStatusCad = $vROBJETO['CBASTATUS'];
}

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

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <?php include_once '../includes/breadcrumb.php' ?>

                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="card">
                                <div class="card-body">

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" onSubmit="return validarForm();">
										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>



                                    <!-- Tab Dados Gerais -->
                                    <div class="tab-content">
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
                                            <p class="text-muted mb-0">
                                            <div class="form-group">
                                                <label>Agência/Empresa - (Cadastrado em Parceiros)</label>
                                                <input class="form-control is-invalid" type="text" value="" id="example-text-input" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tipo</label>
                                                <input class="form-control is-invalid" type="text" value="" id="example-text-input" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Dados Bancários:</label>
                                                <input class="form-control" type="text" value="" id="example-text-input" required>
                                                <label>Banco</label>
                                                <input class="form-control" type="text" value="" id="example-text-input" required>
                                                <label>Agência com Dígito</label>
                                                <input class="form-control" type="text" value="" id="example-text-input" required>
                                                <label>Conta Corrente com Dígito</label>
                                                <input class="form-control" type="text" value="" id="example-text-input" required>
                                                <label>Código Cedente</label>
                                                <input class="form-control" type="number" value="" id="example-text-input" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Saldos:</label>
                                                <input class="form-control is-invalid" type="number" value="" id="example-text-input" required>
                                                <label>Saldo Real</label>
                                                <input class="form-control is-invalid" type="number" value="" id="example-text-input" required>
                                                <label>Valor Limite</label>
                                                <input class="form-control is-invalid" type="number" value="" id="example-text-input" required>
                                                <label>Saldo Disponível</label>
                                                <input class="form-control is-invalid" type="number" value="" id="example-text-input" required>
                                                <label>Código Cedente</label>
                                                <input class="form-control is-invalid" type="number" value="" id="example-text-input" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Observações</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                            </div>
                                            <div class="form-group row">
												<div class="col-md-2">
													<label>Cadastro (Status)</label>
													<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="form-check-label is-invalid" for="invalidCheck3" style="color: red">
													Campos em vermelho são de preenchimento obrigatório!
												</label>
											</div>
                                            <?php include('../includes/botoes_cad_novo.php'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Tab Dados Gerais -->

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

		<!-- Sweet-Alert  -->
        <script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="../assets/pages/jquery.sweet-alert.init.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.js"></script>

    </body>
</html>