<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1956);
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
		<link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
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
                                    <form class="form-parsley" action="#" method="post" name="formEtiquetas" id="formEtiquetas" >
			
										<input type="hidden" name="hdn_metodo_search" id="hdn_metodo_search" value="searchEtiquetas"/>

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
											<div class="col-md-2">
												<label>Contato</label>
												<input type="text" class="form-control" name="vSCLICONTATO" id="vSCLICONTATO" maxlength="100" title="Contato" value=""/>	
											</div>
											<div class="col-md-2">
												<label>E-mail</label>      
												<input class="form-control" name="vSCLIEMAIL" id="vSCLIEMAIL" type="email" value="" title="E-mail">
											</div>
											<div class="col-md-1">
												<label>Telefone</label> 
												<input class="form-control" name="vSCLIFONE" id="vSCLIFONE" type="text" value="" maxlength="13" onKeyPress="return digitos(event, this);" onKeyUp="mascara('TEL',this,event);" >
											</div>
											<div class="col-md-1">
												<label>.</label><br/>
												<button type="button" class="btn btn-primary waves-effect waves-light" class="nav-link" data-toggle="tab" href="#politica" role="tab"
												onclick="buscarEtiquetas();">FILTRAR</button>
											</div>	
                                        </div>										
										
										<div id="div_etiquetas" class="table-responsive"></div>

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
		<script src="../assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="../assets/pages/jquery.profile.init.js"></script>
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/listEtiquetas.js"></script>

    </body>
</html>