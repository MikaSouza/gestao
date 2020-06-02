<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1980);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once 'combos/comboContasBancarias.php';
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
                                    <form class="form-parsley" action="#" method="post" name="formConciliacaoRemessa" id="formConciliacaoRemessa" >
			
										<input type="hidden" name="hdn_metodo_search" id="hdn_metodo_search" value="searchConciliacaoRemessa"/>

										<div class="form-group row">
											<div class="col-md-2">
												<label>Conta Bancária</label>
												<select title="Conta Bancária" id="vICBACODIGO" class="custom-select obrigatorio" name="vICBACODIGO" >
													<option value="">------</option>
													<?php $result = comboContasBancarias('');
													foreach ($result['dados'] as $result) : ?>
															<option value="<?php echo $result['CBACODIGO']; ?>" <?php if ($_GET['vICBACODIGO'] == $result['CBACODIGO']) echo "selected='selected'"; ?> ><?php echo $result['CONTA']; ?></option>
													<?php endforeach; ?>
												</select>												
											</div>
											<div class="col-md-2">
												<label>Arquivo Gerado</label>
												<select class="form-control" title="Arquivo Gerado" name="vSARQUIVOGERADO" id="vSARQUIVOGERADO">
													<option value="">Todos</option>
													<option value="N">Não</option>
													<option value="S">Sim</option>															
												</select>
											</div>
											<div class="col-md-2">					
												<label>Data Vencimento Inicial</label>
												<input class="form-control" title="Data Vencimento Inicial" name="vDDATAVENCIMENTOINICIAL" id="vDDATAVENCIMENTOINICIAL" value="<?= $vROBJETO['CTRDATAEMISSAODOCUMENTO'];  ?>" type="date" >
											</div>	
											<div class="col-md-2">					
												<label>Data Vencimento Final</label>
												<input class="form-control" title="Data Vencimento Final" name="vDDATAVENCIMENTOFINAL" id="vDDATAVENCIMENTOFINAL" value="<?= $vROBJETO['CTRDATAEMISSAODOCUMENTO'];  ?>" type="date" >
											</div>											
											<div class="col-md-2">
												<label>.</label><br/>
												<button type="button" class="btn btn-primary waves-effect waves-light" class="nav-link" data-toggle="tab" href="#politica" role="tab"
												onclick="buscarConciliacaoRemessa();">FILTRAR</button>
											</div>	
                                        </div>										
										
										<div id="div_remessa" class="table-responsive"></div>

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
        <script src="js/cadConciliacaoRemessa.js"></script>

    </body>
</html>