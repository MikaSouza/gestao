<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1887);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once '../sistema/combos/comboEmpresaUsuaria.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
    <?php if ($vIOid > 0){ ?>
    <body onload="exibirClientexConsultor('vICXBCONSULTOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', '');">
	<?php } else { ?>
	<body>
	<?php } ?>

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
                                        <div class="tab-pane active p-1" id="home-1" role="tabpanel">
										
											<div class="form-group row">
												<div class="col-md-5">                                                      
													<label>Sigla - Cliente
														<small class="text-danger font-13">*</small> (Caso não exista o Cliente clique <a href="../cadastro/cadClientes.php?method=insert" target="_blank">AQUI</a>)
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
											<div class="accordion" id="reformaSim1">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Dados AB
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">											
															<div class="form-group row">
																<div class="col-sm-2">
																	<label>Número AB</label>
																	<input class="form-control obrigatorio classnumerico" title="Número AB" name="vICXBNUMEROAB" id="vICXBNUMEROAB" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CXBNUMEROAB']; }?>" >
																</div>
																<div class="col-sm-2">	
																	<label>Data Lançamento
																		<small class="text-danger font-13">*</small>
																	</label>
																	<input class="form-control obrigatorio" title="Data Lançamento" name="vDCXBDATA" id="vDCXBDATA" value="<?= $vROBJETO['CXBDATA'];  ?>" type="date" >
																</div> 
																<div class="col-md-2">
																	<label>Moeda
																		<small class="text-danger font-13">*</small>
																	</label>
																	<select name="vICXBMOEDA" id="vICXBMOEDA" class="custom-select obrigatorio" title="Moeda">
																		<option value></option>
																		<?php if ($vROBJETO['CXBMOEDA'] == '') $vROBJETO['CXBMOEDA'] = 26780;
																			foreach (comboTabelas('CONTAS A RECEBER - MOEDA') as $tabelas): ?>                                                            
																			<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXBMOEDA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="col-sm-2">
																	<label>Valor do Serviço
																		<small class="text-danger font-13">*</small>
																	</label>
																	<input class="form-control obrigatorio classmonetario" title="Valor do Serviço" name="vMCXBVALOR" id="vMCXBVALOR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXBVALOR'], false); }?>" type="text" >
																</div>
																<div class="col-md-2">
																	<label>Tipo de Pagamento
																		<small class="text-danger font-13">*</small>
																	</label>
																	<select name="vICXBFORMACOBRANCA" id="vICXBFORMACOBRANCA" class="custom-select obrigatorio" title="Tipo de Lançamento">
																		<option value></option>
																		<?php foreach (comboTabelas('CONTAS A RECEBER - FORMA DE COBRANÇA') as $tabelas): ?>                                                            
																			<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXBFORMACOBRANCA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="col-sm-2">	
																	<label>Data de Pagamento</label>
																	<input class="form-control" title="Data de Pagamento" name="vDCXBDATAPAGAMENTO" id="vDCXBDATAPAGAMENTO" value="<?= $vROBJETO['CXBDATAPAGAMENTO'];  ?>" type="date" >
																</div>													
															</div> 	
															<div class="form-group row">
																<div class="col-md-6">
																	<label>Marca
																		<small class="text-danger font-13">*</small>
																	</label>
																	<input class="form-control obrigatorio" title="Marca" type="text" id="vSCXBMARCA" name="vSCXBMARCA" maxlength="250" value="<?= $vROBJETO['CXBMARCA']; ?>" />
																</div> 
																<div class="col-md-6">
																	<label>Autorizante</label>
																	<select name="vICXBAUTORIZANTE" id="vICXBAUTORIZANTE" class="custom-select" title="Autorizante">
																		<option value="">  -------------  </option>
																		<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
																			<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['CXBAUTORIZANTE'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
																		<?php endforeach; ?>
																	</select>                                                    
																</div>												
															</div>
														</div>
													</div>
												</div>		
											</div>	
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Classes
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">																							
															<div class="form-group row">
																<div class="col-md-6">                                                      
																	<label>Classe 1</label>												
																	<input title="Classe 1" type="text" name="vHCXBCLASSE1" id="vHCXBCLASSE1" class="form-control autocomplete" data-hidden="#vICXBCLASSE1" value="<?php echo $vROBJETO['CLASSE1']; ?>" />
																	<input type="hidden" name="vICXBCLASSE1" id="vICXBCLASSE1" value="<?php if(isset($vIOid)) echo $vROBJETO['CXBCLASSE1']; ?>"/>
																</div> 	
																<div class="col-md-6">                                                      
																	<label>Classe 2</label>												
																	<input title="Classe 2" type="text" name="vHCXBCLASSE2" id="vHCXBCLASSE2" class="form-control autocomplete" data-hidden="#vICXBCLASSE2" value="<?php echo $vROBJETO['CLASSE2']; ?>" />
																	<input type="hidden" name="vICXBCLASSE2" id="vICXBCLASSE2" value="<?php if(isset($vIOid)) echo $vROBJETO['CXBCLASSE2']; ?>"/>
																</div>
															</div>
															<div class="form-group row">
																<div class="col-md-6">                                                      
																	<label>Classe 3</label>												
																	<input title="Classe 3" type="text" name="vHCXBCLASSE3" id="vHCXBCLASSE3" class="form-control autocomplete" data-hidden="#vICXBCLASSE3" value="<?php echo $vROBJETO['CLASSE3']; ?>" />
																	<input type="hidden" name="vICXBCLASSE3" id="vICXBCLASSE3" value="<?php if(isset($vIOid)) echo $vROBJETO['CXBCLASSE3']; ?>"/>
																</div> 	
																<div class="col-md-6">                                                      
																	<label>Classe 4</label>												
																	<input title="Classe 4" type="text" name="vHCXBCLASSE4" id="vHCXBCLASSE4" class="form-control autocomplete" data-hidden="#vICXBCLASSE4" value="<?php echo $vROBJETO['CLASSE4']; ?>" />
																	<input type="hidden" name="vICXBCLASSE4" id="vICXBCLASSE4" value="<?php if(isset($vIOid)) echo $vROBJETO['CXBCLASSE4']; ?>"/>
																</div>
															</div>
														</div>
													</div>
												</div>		
											</div>			
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Comissão
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">											
															<div class="form-group row">
																<div class="col-sm-2">	
																	<label>Data de Pagamento</label>
																	<input class="form-control" title="Data de Pagamento" name="vDCXBDATACOMISSAO" id="vDCXBDATACOMISSAO" value="<?= $vROBJETO['CXBDATACOMISSAO'];  ?>" type="date" >
																</div>
																<div class="col-sm-2">
																	<label>Valor Pago</label>
																	<input class="form-control classmonetario" title="Valor Pago" name="vMCXBVALORCOMISSAO" id="vMCXBVALORCOMISSAO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXBVALORCOMISSAO'], false); }?>" type="text" >
																</div>
															</div>
														</div>
													</div>
												</div>		
											</div>															
											<div class="form-group row">
												<div class="col-sm-3">
													<label>Cadastro (Status)</label>
													<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
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
										</div>																				
										<?php include('../includes/botoes_cad_novo.php'); ?>
                                    </form>
									</div>
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
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadABs.js"></script>

    </body>
</html>