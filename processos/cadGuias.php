<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1945);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    </head>
	<?php if ($vIOid > 0){ ?>
    <body onload="exibirClientexConsultor('vIGUICONSULTOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', '');">
	<?php } else { ?>
	<body>
	<?php } ?>

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
										 <?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSON('transactionGuiasxGED.php', 'div_ged', 'GuiasxGED', '<?= $vIOid;?>');">Digitalizações/Arquivos</a>
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
													<label>Sigla - Cliente
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Sigla - Cliente" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio autocomplete" data-hidden="#vICLICODIGO" value="<?php echo $vROBJETO['CLIENTE']; ?>" onblur="validarCliente();"/>
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
													<label>CA / FR
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control" name="vSGUICAFR" id="vSGUICAFR" type="text" value="<?= $vROBJETO['GUICAFR']; ?>" title="CA / FR" >
												</div> 
												<div class="col-md-4">                                                      
													<label>Número da Nota Fiscal/Recibo</label>
													<input class="form-control" name="vSGUINRONOTARECIBO" id="vSGUINRONOTARECIBO" type="text" value="<?= $vROBJETO['GUINRONOTARECIBO']; ?>" title="Número da Nota Fiscal/Recibo" >
												</div>
												<div class="col-md-2">                                                      
													<label>Processo</label>
													<input class="form-control" name="vSGUIPROCESSO" id="vSGUIPROCESSO" type="text" value="<?= $vROBJETO['GUIPROCESSO']; ?>" title="Processo" >
												</div>	
												<div class="col-md-2">
                                                    <label>Data de Vencimento
														<small class="text-danger font-13">*</small>
													</label>
                                                    <input class="form-control obrigatorio" title="Data de Vencimento" name="vDGUIDATAVENCIMENTO" id="vDGUIDATAVENCIMENTO" value="<?= $vROBJETO['GUIDATAVENCIMENTO'];  ?>" type="date" >
                                                </div>	
                                                <div class="col-md-2">                                                      
													<label>Pagador
														<small class="text-danger font-13">*</small>
													</label>													
													<select class="custom-select obrigatorio" title="Pagador" name="vSGUIPAGADOR" id="vSGUIPAGADOR">
														<option value="">-------</option>
														<option value="C" <?php if ($vROBJETO['GUIPAGADOR'] == "C") echo "selected='selected'"; ?>>Cliente</option>
														<option value="M" <?php if ($vROBJETO['GUIPAGADOR'] == "M") echo "selected='selected'"; ?>>Marpa</option>
													</select>
												</div>    
														
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Procedimento
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vITABPROCEDIMENTO" id="vITABPROCEDIMENTO" class="custom-select obrigatorio" title="Procedimento">
														<option value></option>
                                                       	<?php foreach (comboTabelas('CONTAS A RECEBER - PLANO DE CONTAS') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABPROCEDIMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-3">
                                                    <label>Data de Pagamento</label>
                                                    <input class="form-control" title="Data de Pagamento" name="vDGUIDATAPAGAMENTO" id="vDGUIDATAPAGAMENTO" value="<?= $vROBJETO['GUIDATAPAGAMENTO'];  ?>" type="date" >
                                                </div>	
												<div class="col-md-3">
													<label>Valor
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<input title="Valor" class="form-control classmonetario obrigatorio" name="vMGUIVALOR" id="vMGUIVALOR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['GUIVALOR'], false); }?>" type="text" >                                                   
                                                </div>
                                            </div>   
											<div class="form-group row">
												<div class="col-md-3">
													<label>Cadastro (Status)</label>
													<select class="custom-select" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO']?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
													</select>
												</div>
											</div>
                                                                                                                               
										</div>

										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div id="modal_div_ClientesxGED">
												<div class="form-group row">                     
													<input type="hidden" name="vHGEDTIPO" id="vHGEDTIPO" value="26993"/>
													<div class="file-field col-md-6">
														<label>Escolher Arquivo
															<small class="text-danger font-13">*</small>
														</label>
														<input type="file" id="fileUpload" name="fileUpload">
												   </div>
												   <div class="col-md-6">
														<br/>				  
														<button type="button" id="btnEnviar" class="btn btn-secondary waves-effect">Salvar Documentos</button><br>
													</div>
												</div>	
											</div>	
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>
                                        <!-- Aba Dados Documentos end -->

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

        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadGuias.js"></script>
    </body>
</html>