<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2002);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
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
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

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
								
									<form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" onSubmit="return validarForm();">

										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>		
										<div class="form-group row">
											<div class="col-md-5">                                                      
												<label>Fornecedor
													<small class="text-danger font-13">*</small>
												</label>												
												<input title="Fornecedor" type="text" name="vHFAVORECIDO" id="vHFAVORECIDO" class="form-control obrigatorio autocomplete" data-hidden="#vIFAVCODIGO" value="<?php echo $vROBJETO['FAVNOMEFANTASIA']; ?>" onblur="validarCliente();"/>
												<span id="aviso-cliente" style="color: red;font-size: 11px; display: none;">O Fornecedor não foi selecionado corretamente!</span>
												<input type="hidden" name="vIFAVCODIGO" id="vIFAVCODIGO" value="<?php if(isset($vIOid)) echo $vROBJETO['FAVCODIGO']; ?>"/>
											</div>        
											<div class="col-md-1 btnLimparCliente">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerCliente();">Limpar</button><br>
											</div>		
										</div>
										<div class="form-group row">
											<div class="col-md-3">	
												<label>Nome
													<small class="text-danger font-13">*</small>
												</label>
												<input type="text" class="form-control obrigatorio" name="vSVXTNOME" id="vSVXTNOME" title="Nome" value="<?= $vROBJETO['VXTNOME']; ?>"/>												
											</div>	
											<div class="col-md-2">	
												<label>Valor Unitário
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control classmonetario obrigatorio" title="Valor Unitário" name="vMVXTVALORUNITARIO" id="vMVXTVALORUNITARIO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['VXTVALORUNITARIO'], false); }?>" type="text" >
											</div>	
										</div>	
										<div class="form-group row">
											<div class="col-md-6">	
												<label>Itinerário
													<small class="text-danger font-13">*</small>
												</label>
												<input type="text" class="form-control obrigatorio" name="vSVXTITINERARIO" id="vSVXTITINERARIO" title="Itinerário" value="<?= $vROBJETO['VXTITINERARIO']; ?>"/>												
											</div>	
										</div>		
										<div class="form-group row">
											<div class="col-md-4">
												<label>Cadastro (Status)</label>
												<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
													<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
													<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
												</select>
											</div>
										</div>
										<div class="form-group row">
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

        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/dropify/js/dropify.min.js"></script>        
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadValesTransporte.js"></script>				
		<?php if ($vIOid == ''){ ?>
			<script type="text/javascript">
				$(".btnLimparCliente").hide();
			</script>
		<?php } ?>
    </body>
</html>