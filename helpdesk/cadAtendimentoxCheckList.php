<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2023);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
											<div class="col-md-4">
												<label>Título
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control  obrigatorio" name="vSATINOME" id="vSATINOME" type="text" value="<?= $vROBJETO['ATINOME']?>" title="Título" >
											</div>
										</div>	
										
										<div class="form-group row">
											<div class="col-12">
												<div class="form-group row">
													<div class="col-md-8"> 
														<label>Pergunta
															<small class="text-danger font-13">*</small>
														</label>
														<textarea title="Descrição" class="form-control obrigatorio" id="vSATIDESCRICAO" name="vSATIDESCRICAO" rows="3"><?= $vROBJETO['ATIDESCRICAO']; ?></textarea>
													</div> 	
												</div>	
												<div class="card">
													<div class="card-body">
														
														<form method="POST" class="form-horizontal well">
															<fieldset>
																<div class="repeater-default">
																	<div data-repeater-list="car">
																		<div data-repeater-item="">
																			<div class="form-group row d-flex align-items-end">																		
																				
																				<div class="col-sm-4">
																					<label class="control-label">Resposta</label>
																					<input type="text" name="car[0][model]" value="Beetle" class="form-control">
																				</div><!--end col-->																
																	
																				<div class="col-sm-2">
																					<span data-repeater-delete="" class="btn btn-danger btn-sm">
																						<span class="far fa-trash-alt mr-1"></span> Deletar Item
																					</span>
																				</div><!--end col-->
																			</div><!--end row-->
																		</div><!--end /div-->
																
																	</div><!--end repet-list-->
																	<div class="form-group mb-0 row">
																		<div class="col-sm-12">
																			<span data-repeater-create="" class="btn btn-secondary">
																				<span class="fas fa-plus"></span> Adicionar Item
																			</span>
																		</div><!--end col-->
																	</div><!--end row-->                                         
																</div> <!--end repeter-->                                           
															</fieldset><!--end fieldset-->
														</form><!--end form-->
													</div><!--end card-body-->
												</div><!--end card-->
												<div class="form-group mb-0 row">
													<div class="col-sm-12">
														<span data-repeater-create="" class="btn btn-warning">
															<span class="fas fa-plus"></span> Adicionar Nova Pergunta
														</span>
													</div><!--end col-->
												</div><!--end row-->   
											</div> <!-- end col -->
										</div> <!-- end col -->
																																																
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

        <!-- Parsley js -->
        <script src="../assets/plugins/parsleyjs/parsley.min.js"></script>
        <script src="../assets/pages/jquery.validation.init.js"></script>

        <script src="../assets/js/jquery.core.js"></script>
		
		<script src="../assets/plugins/repeater/jquery.repeater.min.js"></script>
        <script src="../assets/pages/jquery.form-repeater.js"></script>

		
		<?php include_once '../includes/scripts_footer.php' ?>

    </body>
</html>