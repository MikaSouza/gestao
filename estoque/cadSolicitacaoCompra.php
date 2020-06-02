<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2011);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../sistema/combos/comboTabelas.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
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
    <body><?php include_once '../includes/menu.php' ?>

			<div class="page-wrapper">

            <div class="page-content">

                <div class="container-fluid">

                    <?php include_once '../includes/breadcrumb.php' ?>

                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">

										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?= $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>
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
										<div class="form-group">
											<label class="form-check-label is-invalid" for="invalidCheck3" style="color: red">
												Digite o Produto que desejado, caso não encontrar na listagem cadastre em Produtos antes.<br/>
												Você poderá solicitar mais de um produto basta preencher os outros campos de produtos.<br/>
											</label>
										</div>
										<div class="form-group row">	
											<div class="col-md-5">                                                      
												<label>Produto 1
													<small class="text-danger font-13">*</small>
												</label>												
												<input title="Produto 1" type="text" name="vHPRONOME1" id="vHPRONOME1" class="form-control obrigatorio autocomplete" data-hidden="#vIPROCODIGO1" value="<?php echo $vROBJETO['PRODUTO']; ?>" onblur="validaProduto(1);"/>
												<span id="aviso-produto1" style="color: red;font-size: 11px; display: none;">O Produto não foi selecionado corretamente!</span>
												<input type="hidden" name="vIPROCODIGO1" id="vIPROCODIGO1" value="<?php if(isset($vIOid)) echo $vROBJETO['PROCODIGO1']; ?>"/>
											</div>
											<div class="col-md-1 btnLimparProduto1">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerProduto(1);">Limpar</button><br>
											</div>
											<div class="col-sm-2">
                                                <label>Quantidade</label>
                                                <input class="form-control obrigatorio" title="Quantidade" name="vISOCQUANTIDADE1" id="vISOCQUANTIDADE1" type="number" value="<?php if(isset($vIOid)){ echo $vROBJETO['SOCQUANTIDADE1']; }?>">
                                            </div>
											<div class="col-sm-2">
												<label>Valor Unitário</label>
												<input class="form-control classmonetario obrigatorio" title="Valor Unitário" name="vMSOCVALOR1" id="vMSOCVALOR1" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['SOCVALOR1'], false); }?>" type="text" >
											</div>
										</div>																				
										<div class="form-group row">	
											<div class="col-md-5">                                                      
												<label>Produto 2</label>												
												<input title="Produto 2" type="text" name="vHPRONOME2" id="vHPRONOME2" class="form-control autocomplete" data-hidden="#vIPROCODIGO2" value="<?php echo $vROBJETO['PRODUTO2']; ?>" onblur="validaProduto(2);"/>
												<span id="aviso-produto2" style="color: red;font-size: 11px; display: none;">O Produto não foi selecionado corretamente!</span>
												<input type="hidden" name="vIPROCODIGO2" id="vIPROCODIGO2" value="<?php if(isset($vIOid)) echo $vROBJETO['PROCODIGO2']; ?>"/>
											</div>
											<div class="col-md-1 btnLimparProduto2">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerProduto(2);">Limpar</button><br>
											</div>
											<div class="col-sm-2">
                                                <label>Quantidade</label>
                                                <input class="form-control" title="Quantidade" name="vISOCQUANTIDADE2" id="vISOCQUANTIDADE2" type="number" value="<?php if(isset($vIOid)){ echo $vROBJETO['SOCQUANTIDADE2']; }?>">
                                            </div>
											<div class="col-sm-2">
												<label>Valor Unitário</label>
												<input class="form-control classmonetario" title="Valor Unitário" name="vMSOCVALOR2" id="vMSOCVALOR2" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['SOCVALOR2'], false); }?>" type="text" >
											</div>
										</div>
										<div class="form-group row">	
											<div class="col-md-5">                                                      
												<label>Produto 3</label>												
												<input title="Produto 3" type="text" name="vHPRONOME3" id="vHPRONOME3" class="form-control autocomplete" data-hidden="#vIPROCODIGO3" value="<?php echo $vROBJETO['PRODUTO3']; ?>" onblur="validaProduto(3);"/>
												<span id="aviso-produto3" style="color: red;font-size: 11px; display: none;">O Produto não foi selecionado corretamente!</span>
												<input type="hidden" name="vIPROCODIGO3" id="vIPROCODIGO3" value="<?php if(isset($vIOid)) echo $vROBJETO['PROCODIGO3']; ?>"/>
											</div>
											<div class="col-md-1 btnLimparProduto3">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerProduto(3);">Limpar</button><br>
											</div>
											<div class="col-sm-2">
                                                <label>Quantidade</label>
                                                <input class="form-control" title="Quantidade" name="vISOCQUANTIDADE3" id="vISOCQUANTIDADE3" type="number" value="<?php if(isset($vIOid)){ echo $vROBJETO['SOCQUANTIDADE3']; }?>">
                                            </div>
											<div class="col-sm-2">
												<label>Valor Unitário</label>
												<input class="form-control classmonetario" title="Valor Unitário" name="vMSOCVALOR3" id="vMSOCVALOR3" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['SOCVALOR3'], false); }?>" type="text" >
											</div>
										</div>
										<div class="form-group row">	
											<div class="col-md-5">                                                      
												<label>Produto 4</label>												
												<input title="Produto 4" type="text" name="vHPRONOME4" id="vHPRONOME4" class="form-control autocomplete" data-hidden="#vIPROCODIGO4" value="<?php echo $vROBJETO['PRODUTO4']; ?>" onblur="validaProduto(4);"/>
												<span id="aviso-produto4" style="color: red;font-size: 11px; display: none;">O Produto não foi selecionado corretamente!</span>
												<input type="hidden" name="vIPROCODIGO4" id="vIPROCODIGO4" value="<?php if(isset($vIOid)) echo $vROBJETO['PROCODIGO4']; ?>"/>
											</div>
											<div class="col-md-1 btnLimparProduto4">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerProduto(4);">Limpar</button><br>
											</div>
											<div class="col-sm-2">
                                                <label>Quantidade</label>
                                                <input class="form-control" title="Quantidade" name="vISOCQUANTIDADE4" id="vISOCQUANTIDADE4" type="number" value="<?php if(isset($vIOid)){ echo $vROBJETO['SOCQUANTIDADE4']; }?>">
                                            </div>
											<div class="col-sm-2">
												<label>Valor Unitário</label>
												<input class="form-control classmonetario" title="Valor Unitário" name="vMSOCVALOR4" id="vMSOCVALOR4" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['SOCVALOR4'], false); }?>" type="text" >
											</div>
										</div>
										<div class="form-group row">	
											<div class="col-md-5">                                                      
												<label>Produto 5</label>												
												<input title="Produto 5" type="text" name="vHPRONOME5" id="vHPRONOME5" class="form-control autocomplete" data-hidden="#vIPROCODIGO5" value="<?php echo $vROBJETO['PRODUTO5']; ?>" onblur="validaProduto(5);"/>
												<span id="aviso-produto5" style="color: red;font-size: 11px; display: none;">O Produto não foi selecionado corretamente!</span>
												<input type="hidden" name="vIPROCODIGO5" id="vIPROCODIGO5" value="<?php if(isset($vIOid)) echo $vROBJETO['PROCODIGO5']; ?>"/>
											</div>
											<div class="col-md-1 btnLimparProduto5">
												<br/>				  
												<button type="button" class="btn btn-danger waves-effect" onclick="removerProduto(5);">Limpar</button><br>
											</div>
											<div class="col-sm-2">
                                                <label>Quantidade</label>
                                                <input class="form-control" title="Quantidade" name="vISOCQUANTIDADE5" id="vISOCQUANTIDADE5" type="number" value="<?php if(isset($vIOid)){ echo $vROBJETO['SOCQUANTIDADE5']; }?>">
                                            </div>
											<div class="col-sm-2">
												<label>Valor Unitário</label>
												<input class="form-control classmonetario" title="Valor Unitário" name="vMSOCVALOR5" id="vMSOCVALOR5" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['SOCVALOR5'], false); }?>" type="text" >
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

		<?php include_once '../includes/scripts_footer.php' ?>
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadSolicitacaoCompra.js"></script>
		<?php if ($vIOid == ''){ ?>
			<script type="text/javascript">
				$(".btnLimparCliente").hide();
				$(".btnLimparProduto1").hide();
				$(".btnLimparProduto2").hide();
				$(".btnLimparProduto3").hide();
				$(".btnLimparProduto4").hide();
				$(".btnLimparProduto5").hide();
			</script>
		<?php } ?>
    </body>
</html>