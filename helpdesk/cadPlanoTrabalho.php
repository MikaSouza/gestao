<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2014);
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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">
                                        <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>
										
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
												<div class="col-md-6">
                                                    <label>Produto/Serviço
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vIPXSCODIGO" id="vIPXSCODIGO" class="custom-select obrigatorio" title="Produto/Serviço">
                                                        <option value="">  -------------  </option>
														<?php 														
														foreach (comboProdutosxServicos() as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['PXSCODIGO']; ?>" <?php if ($vROBJETO['PXSCODIGO'] == $tabelas['PXSCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PXSNOME']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>												
											</div>	
											<div class="form-group row">
												<div class="col-md-12">                                                      
													<label>Descrição
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSPXADESCRICAO" name="vSPXADESCRICAO" title="Descrição"><?= nl2br($vROBJETO['PXADESCRICAO']); ?></textarea>
												</div>
											</div>	
											<div class="form-group row">
												<label class="form-check-label" for="invalidCheck3" style="color: red">
													Atenção! Para alterar a ordem das atividades clique no ícone das setas no lado esquerdo da tabela e arraste até a posição desejada!<br/>												
												</label>
											</div>
																					
											<button class="btnGenericoFilho" id="add_atividade" style="margin-top: 20px;" onclick="event.preventDefault();">Adicionar atividade</button>
											<button class="btnGenericoFilho" id="add_atividade2" style="margin-top: 20px;" onclick="event.preventDefault();">Adicionar atividade</button>
											<?php if($vIOid > 0){ ?>
											
											
											<table class="tabela-default" style="margin-top: 20px;" id="table_atividades">
												<thead>
													<tr>
														<th>Acão</th>
														<th>Atividade</th>
														<th>Departamento</th>
														<th>Prazo (dias)</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($atividades as $atividade): ?>
													<tr data-index="<?= $atividade['PXACODIGO']; ?>" data-position="<?= $atividade['PXAPOSICAO']; ?>">
														<td>
															<a href="#" class="btnReordenarMini" title="Reordenar" alt="Consultar"></a>
															<input type="hidden" name="vHPXAPOSICAO[]" value="<?= $atividade['PXAPOSICAO']; ?>">
														</td>
														<td>
															<select name="vHATCATIVIDADE[]" style="width: 250px;">
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
														</td>
														<td>
															<select name="vHTABDEPARTAMENTO[]">
																<?php
																	foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $usuario):
																		if ($usuario['TABCODIGO'] == $atividade['TABDEPARTAMENTO']): ?>
																			<option selected value="<?php echo $usuario['TABCODIGO'] ?>"><?php echo $usuario['TABDESCRICAO'] ?></option>
																		<?php else: ?>
																			<option value="<?php echo $usuario['TABCODIGO'] ?>"><?php echo $usuario['TABDESCRICAO'] ?></option>
																		<?php
																			endif;
																	endforeach;
																?>
															</select>
														</td>
														<td>
															<input type="number" name="vHPXAPRAZO[]" style="width: 80px;" value="<?php if(isset($vIOid)) echo $atividade['PXAPRAZO']; else echo 1; ?>" min="1">
															<input type="hidden" name="vHPXACODIGO[]" value="<?php echo $atividade['PXACODIGO']; ?>">
														</td>
														<td>
															<select name="vHPXASTATUS[]" style="width: 100px;">
																<option <?php if ($atividade['PXASTATUS'] == 'S') ?> value="S">Ativo</option>
																<option <?php if ($atividade['PXASTATUS'] == 'N') ?> value="N">Inativo</option>
															</select>
														</td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
											
											<div class="accordion" id="reformaSim">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Atividades
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<p class="mb-0 text-muted">
															<?php foreach ($atividades as $atividade): ?>
															<div class="form-group row" id="table_atividades2">
																<div class="col-md-4">
																	<label>Atividade</label>
																	<select name="vHATCATIVIDADE[]" title="Atividade" class="form-control">
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
																</div>
																<div class="col-md-3">
																	<label>Departamento</label>
																	<select name="vHTABDEPARTAMENTO[]" title="Departamento" class="form-control">
																		<?php
																			foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $usuario):
																				if ($usuario['TABCODIGO'] == $atividade['TABDEPARTAMENTO']): ?>
																					<option selected value="<?php echo $usuario['TABCODIGO'] ?>"><?php echo $usuario['TABDESCRICAO'] ?></option>
																				<?php else: ?>
																					<option value="<?php echo $usuario['TABCODIGO'] ?>"><?php echo $usuario['TABDESCRICAO'] ?></option>
																				<?php
																					endif;
																			endforeach;
																		?>
																	</select>
																</div>
																<div class="col-md-2">
																	<label>Prazo (dias)</label>																	
																	<input type="number" class="form-control" title="Prazo (dias)" name="vHPXAPRAZO[]" value="<?php if(isset($vIOid)) echo $atividade['PXAPRAZO']; else echo 1; ?>" min="1">
																	<input type="hidden" name="vHPXACODIGO[]" value="<?php echo $atividade['PXACODIGO']; ?>">
																</div>
																<div class="col-md-3">
																	<label>Status</label>
																	<select name="vHPXASTATUS[]" title="Atividade" class="form-control">
																		<option <?php if ($atividade['PXASTATUS'] == 'S') ?> value="S">Ativo</option>
																		<option <?php if ($atividade['PXASTATUS'] == 'N') ?> value="N">Inativo</option>
																	</select>																
																</div>
															</div>		
															<div class="form-group row" id="table_atividades2">
															<?php endforeach; ?>	
															</p>
														</div>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>                                  		
																																					

										<div class="form-group">
											<label class="form-check-label" for="invalidCheck3" style="color: red">
												Campos em vermelho são de preenchimento obrigatório!<br/>												
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
		<script src="../assets/pages/jquery.form-upload.init.js"></script>
		<!--Wysiwig js-->
        <script src="../assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="../assets/pages/jquery.form-editor.init.js"></script> 
		
        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Cad Empresa js -->
		<script src="js/cadPlanoTrabalho.js"></script>
    </body>
</html>
