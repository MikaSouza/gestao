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
                                    <form class="form-parsley" action="#" method="post" name="formTriagem" id="formTriagem" >
			
										<input type="hidden" name="hdn_metodo_search" id="hdn_metodo_search" value="searchTriagem"/>

										<div class="form-group row">
											<div class="col-md-2">
												<label>Tipo Pessoa
													<small class="text-danger font-13">*</small>
												</label>
												<select class="form-control obrigatorio" name="vSCLITIPOCLIENTE" id="vSCLITIPOCLIENTE" title="Tipo Pessoa" onchange="mostrarJxF(this.value);">
													<option value="J">Jurídica</option>
													<option value="F">Física</option>
												</select>
											</div>
											<div class="col-md-2 divJuridica">
												<label>CNPJ</label>      
												<input type="text" class="form-control" name="vSCLICNPJ" id="vSCLICNPJ"  title="CNPJ" value="" maxlength="18" onKeyPress="return digitos(event, this)" onKeyUp="mascara('CNPJ',this,event)" />
											</div>
											<div class="col-md-2 divFisica">
												<label>CPF</label>     
												<input class="form-control" name="vSCLICPF" id="vSCLICPF" type="text" title="CPF" maxlength="14" value="" onKeyPress="return digitos(event, this)" onKeyUp="mascara('CPF',this,event)">
											</div>
											<div class="col-md-2">
												<label>Cliente/Empresa</label> 
												<input type="text" class="form-control" name="vSCLINOME" id="vSCLINOME" maxlength="100" title="Cliente/Empresa" value=""/>	
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
												onclick="buscarTriagem();">FILTRAR</button>
											</div>	
                                        </div>										
										
										<div id="div_triagem" class="table-responsive"></div>

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
        <script src="js/listTriagem.js"></script>

    </body>
</html>