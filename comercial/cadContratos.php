<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1949);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboProdutosxServicos.php';
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
		<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
		 
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
										
                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
										<?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>');">Digitalizações/Arquivos</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#faturamento" role="tab" onclick="gerarGridJSON('transactionClientesxFaturamento.php', 'div_faturamento', 'ClientesxFaturamento', '<?= $vIOid;?>');">Faturamento</a>
                                        </li>
                                        <?php } ?>                                        
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">	
											<div class="form-group row">
												<div class="col-md-5">                                                      
													<label>Cliente
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Cliente" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio autocomplete" data-hidden="#vICLICODIGO" value="<?php echo $vROBJETO['CLIENTE']; ?>" onblur="validarCliente();"/>
													<span id="aviso-cliente" style="color: red;font-size: 11px; display: none;">O Cliente não foi selecionado corretamente!</span>
													<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value="<?php if(isset($vIOid)) echo $vROBJETO['CLICODIGO']; ?>"/>
												</div>
												<div class="col-md-1 btnLimparCliente">
													<br/>				  
													<button type="button" class="btn btn-danger waves-effect" onclick="removerCliente();">Limpar</button><br>
												</div>		
												<div class="col-md-6">
													<div id="divConsultor"></div>
												</div> 	
											</div>	
											<div class="form-group row">
												<div class="col-md-2">
                                                    <label>Data de Início</label>
                                                    <input class="form-control obrigatorio" title="Data de Início" name="vDCLIDATA_NASCIMENTO" id="vDCLIDATA_NASCIMENTO" value="<?= $vROBJETO['CLIDATA_NASCIMENTO'];  ?>" type="date" >
                                                </div>
												<div class="col-md-2">
                                                    <label>Término da Vigência</label>
                                                    <input class="form-control obrigatorio" title="Término da Vigência" name="vDCLIDATA_NASCIMENTO" id="vDCLIDATA_NASCIMENTO" value="<?= $vROBJETO['CLIDATA_NASCIMENTO'];  ?>" type="date" >
                                                </div>
												<div class="col-md-3">	
													<label>Mensalidade Inicial
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control classmonetario obrigatorio" title="Mensalidade Inicial" name="vMCTPVALORAPAGAR" id="vMCTPVALORAPAGAR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTPVALORAPAGAR'], false); }?>" type="text" >
												</div>
												<div class="col-md-3">	
													<label>Mensalidade Atual
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control classmonetario obrigatorio" title="Mensalidade Atual" name="vMCTPVALORAPAGAR" id="vMCTPVALORAPAGAR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTPVALORAPAGAR'], false); }?>" type="text" >
												</div>
											</div>
											<div class="form-group row">	
												<div class="col-md-4">   
													<label>Produto/Serviço
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vHGED" id="vHGED" class="custom-select divObrigatorio" title="Produto/Serviço">
														<option value="">  -------------  </option>
														<?php foreach (comboProdutosxServicos() as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['PXSCODIGO']; ?>" ><?php echo $tabelas['PXSNOME']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-3">
													<label>Situação
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Situação" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Ativo</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Inativo</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Encerrado</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Observações</option>	
													</select>
												</div>												
											</div>	
											<div class="form-group row">
												<div class="col-md-12">                                                      
													<label>Observações
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSPXADESCRICAO" name="vSPXADESCRICAO" title="Descrição"><?= nl2br($vROBJETO['PXADESCRICAO']); ?></textarea>
												</div>
											</div>
																						                                            
										</div>
                                    													
										<!-- Aba Dados GED -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div id="modal_div_ClientesxGED">
												<div class="form-group row">                                                												
													<div class="col-md-4">   
														<label>Tipo Arquivo
															<small class="text-danger font-13">*</small>
														</label>
														<select name="vHGED" id="vHGED" class="custom-select divObrigatorio" title="Tipo Arquivo">
															<option value="">  -------------  </option>
															<?php foreach (comboTabelas('GED - TIPO') as $tabelas): ?>                                                            
																<option value="<?php echo $tabelas['TABCODIGO']; ?>" ><?php echo $tabelas['TABDESCRICAO']; ?></option>
															<?php endforeach; ?>
														</select>                                                    
													</div>
													<div class="file-field col-md-4">
														<label>Escolher Arquivo
															<small class="text-danger font-13">*</small>
														</label>
														<input type="file" id="fileUpload" name="fileUpload">
												   </div>
												   <div class="col-md-4">
														<br/>				  
														<button type="button" id="btnEnviar" class="btn btn-secondary waves-effect">Salvar Documentos</button><br>
													</div>
												</div>	
											</div>	
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>																									
										<div class="tab-pane p-3" id="faturamento" role="tabpanel">
											<div class="form-group row">
												<div id="div_faturamento" class="table-responsive"></div>
											</div>
										</div>
										
										<div class="tab-pane p-3" id="contratos" role="tabpanel">
											<div class="form-group row">
												<div id="div_contratos" class="table-responsive"></div>
											</div>
										</div>
										
										<div class="tab-pane p-3" id="oportunidades" role="tabpanel">
											<div class="form-group row">
												<div id="div_oportunidades" class="table-responsive"></div>
											</div>
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

        <!-- Sweet-Alert  -->
        <script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="../assets/pages/jquery.sweet-alert.init.js"></script>

        <script src="../assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="../assets/pages/jquery.profile.init.js"></script>

        <script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
        <script src="../assets/pages/jquery.gallery.inity.js"></script>
		<script src="../assets/pages/jquery.form-upload.init.js"></script>
		<script src="../assets/plugins/select2/select2.min.js"></script>
		
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="js/cadContratos.js"></script>
    </body>
</html>
