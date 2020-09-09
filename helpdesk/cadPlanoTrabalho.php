<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2024);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboProdutosxServicos.php';
include_once __DIR__.'/../cadastro/combos/comboAtividades.php';
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
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    </head>
	<body>

        <?php include_once '../includes/menu.php' ?>

        <div class="page-wrapper">

            <!-- Page Content-->
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
												<input class="form-control  obrigatorio" name="vSPRINOME" id="vSPRINOME" type="text" value="<?= $vROBJETO['PRINOME']?>" title="Título" >
											</div>											
										</div>																	
										<div class="form-group row">
											<div class="col-md-12"> 
												<label>Descrição
													<small class="text-danger font-13">*</small>
												</label>
												<textarea title="Descrição" class="form-control obrigatorio" id="vSPRIDESCRICAO" name="vSPRIDESCRICAO" rows="3"><?= $vROBJETO['PRIDESCRICAO']; ?></textarea>
											</div> 	
										</div>			
										<div class="form-group row">
											<fieldset>
												<div class="repeater-default">
													<div data-repeater-list="car">
														<?php foreach ($atividades as $atividade): ?>
														<div data-repeater-item="">
															<div class="form-group row d-flex align-items-end">
																
																<div class="col-sm-4">
																	<label class="control-label">Atividade</label>
																	<select name="car[0][make]" class="form-control">
																		<?php
																			foreach (comboAtividades('') as $cbAtividade):
																				if ($cbAtividade['ATICODIGO'] == $atividade['ATICODIGO']): ?>
																					<option selected value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
																				<?php else: ?>
																					<option value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
																				<?php
																					endif;
																			endforeach;
																		?>
																	</select>
																</div><!--end col-->
																
																<div class="col-sm-4">
																	<label class="control-label">Departamento</label>
																	 <select name="car[0][model]" class="form-control">
																		<option value="">  -------------  </option>
																		<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>                                                            
																			<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABDEPARTAMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>                                                                
																</div><!--end col-->
													
																<div class="col-sm-3">
																	<label class="control-label">Prazo (dias)</label>
																	<input type="number" name="car[0][dias]" value="1" class="form-control">
																</div><!--end col-->
													
																<div class="col-sm-1">
																	<span data-repeater-delete="" class="btn btn-danger btn-sm">
																		<span class="far fa-trash-alt mr-1"></span> Deletar
																	</span>
																</div><!--end col-->
															</div><!--end row-->
														</div><!--end /div-->
														<?php endforeach; ?>											
													</div><!--end repet-list-->
													<div class="form-group mb-0 row">
														<div class="col-sm-12">
															<span data-repeater-create="" class="btn btn-secondary">
																<span class="fas fa-plus"></span> Adicionar Atividade
															</span>
														</div><!--end col-->
													</div><!--end row-->                                         
												</div> <!--end repeter-->                                           
											</fieldset><!--end fieldset-->
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
												Campos em vermelho são de preenchimento obrigatório!
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
					
                </div><!-- container -->
            </div>									
			
            <!-- end page content -->
            <?php include_once '../includes/footer.php' ?>
        </div> 		
		
        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <script src="../assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="../assets/pages/jquery.profile.init.js"></script>

        <script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
        <script src="../assets/pages/jquery.gallery.inity.js"></script>
		
		<script src="../assets/plugins/repeater/jquery.repeater.min.js"></script>
        <script src="../assets/pages/jquery.form-repeater.js"></script>
		
        <?php include_once '../includes/scripts_footer.php' ?>

    </body>
</html>
