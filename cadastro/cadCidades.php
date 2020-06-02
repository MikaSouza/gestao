<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1943);
include_once __DIR__.'/transaction/transactionPadrao.php';
include_once __DIR__.'/transaction/transactionCidades.php';
include_once __DIR__.'/combos/comboEstados.php';
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
                        <div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">

										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<div class="form-group row">                                        
                                            <div class="col-sm-4">
                                                <label>Código IBGE
													<small class="text-danger font-13">*</small>
												</label>
                                                <input class="form-control obrigatorio" title="Código IBGE" name="vICIDCODIGOIBGE" id="vICIDCODIGOIBGE" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CIDCODIGOIBGE']; }?>" required>
                                            </div>
											<div class="col-sm-4">
                                                <label>Estado
													<small class="text-danger font-13">*</small>
												</label>
                                                <select title="Estado" id="vIESTCODIGO" class="form-control obrigatorio" name="vIESTCODIGO">
                                                        <?php foreach (comboEstados() as $tabelas): ?>
                                                            <option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vROBJETO['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
                                                        <?php endforeach; ?>
                                                </select>
                                            </div>
											<div class="col-sm-4">
                                                <label>Cidade
													<small class="text-danger font-13">*</small>
												</label>
                                                <input class="form-control obrigatorio" title="Cidade" name="vSCIDDESCRICAO" id="vSCIDDESCRICAO" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CIDDESCRICAO']; }?>" required>
                                            </div>
                                        </div>

										<div class="form-group">
											<div class="col-sm-3">
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

		<!-- Funcoes js -->
		<script src="../assets/js/funcoes.js"></script>

    </body>
</html>