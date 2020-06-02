<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1886);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once '../sistema/combos/comboEmpresaUsuaria.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
    <body onload="exibirClientexConsultor('vICXCCONSULTOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', '');">
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
									
									 <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Requerente</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#home-2" role="tab">Serviço</a>
                                        </li>
                                    </ul>
                                    <!-- Nav tabs end -->
										
									<!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-1" id="home-1" role="tabpanel">
										
											<div class="form-group row">
												<div class="col-md-6">
													<label>Filial
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select obrigatorio" title="Filial">
														<option value></option>
                                                       	<?php foreach (comboEmpresaUsuaria() as $tabelas): ?>
															<option value="<?php echo $tabelas['EMPCODIGO']; ?>" <?php if ($vROBJETO['EMPCODIGO'] == $tabelas['EMPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-6">
													<label>Tipo de Lançamento
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vITABCENTROCUSTO" id="vITABCENTROCUSTO" class="custom-select obrigatorio" title="Tipo de Lançamento">
														<option value></option>
                                                       	<?php foreach (comboTabelas('CONTAS A RECEBER - CENTRO DE CUSTO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABCENTROCUSTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>												 
											</div>
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
												<div class="col-sm-2">
													<label>Número CA
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classnumerico" title="Número CA" name="vICXCNUMEROCA" id="vICXCNUMEROCA" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CXCNUMEROCA']; }?>" >
												</div>
												<div class="col-sm-2">
													<label>Número AB</label>
													<input class="form-control classnumerico" title="Número AB" name="vHCODPASTA" id="vHCODPASTA" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CODPASTA']; }?>" >
												</div>
												<div class="col-sm-3">
													<label>Classe</label>
													<input class="form-control" title="Classe" name="vICXCCLASSE" id="vICXCCLASSE" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CXCCLASSE']; }?>" >
												</div>                                        
												<div class="col-sm-3">
													<label>Processo</label>
													<input class="form-control obrigatorio classnumericobig" title="Processo" name="vICXCPROCESSO" id="vICXCPROCESSO" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CXCPROCESSO']; }?>" >
												</div>        
												<div class="col-sm-3">	
													<label>Data Lançamento
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Data Lançamento" name="vDCXCDATA" id="vDCXCDATA" value="<?= $vROBJETO['CXCDATA'];  ?>" type="date" >
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
										<div class="tab-pane p-2" id="home-2" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">                                                      
													<label>Código Serviço
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Código Serviço" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio" value="<?php echo $vROBJETO['CLINOME']; ?>" />												
												</div> 	
												<div class="col-md-2">
													<label>Moeda
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vICXCMOEDA" id="vICXCMOEDA" class="custom-select obrigatorio" title="Moeda">
														<option value></option>
														<?php if ($vROBJETO['CXCMOEDA'] == '') $vROBJETO['CXCMOEDA'] = 26780;
															foreach (comboTabelas('CONTAS A RECEBER - MOEDA') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXCMOEDA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Valor do Serviço
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor do Serviço" name="vMCXCVALOR" id="vMCXCVALOR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCVALOR'], false); }?>" type="text" >
												</div>                                        
												<div class="col-sm-3">
													<label>Outras Taxas</label>
													<input class="form-control classmonetario" title="Outras Taxas" name="vMCXCOUTRASTAXAS" id="vMCXCOUTRASTAXAS" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCOUTRASTAXAS'], false); }?>" type="text" >
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-12">
													<label>Descrição do Serviço
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control obrigatorio" title="Descrição do Serviço" id="vSCXCDESCRICAO" name="vSCXCDESCRICAO" rows="3"><?= $vROBJETO['CXCDESCRICAO']; ?></textarea>
												</div>	
											</div>
											<div class="form-group row">
												<div class="col-sm-3">
													<label>Fiz/Jur</label>
													<input class="form-control classmonetario" title="Fiz/Jur" name="vMCXCFIZJUR" id="vMCXCFIZJUR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCFIZJUR'], false); }?>" type="text" >
												</div>  	
												<div class="col-sm-3">
													<label>Matriz</label>
													<input class="form-control classmonetario" title="Matriz" name="vMCXCMATRIZ" id="vMCXCMATRIZ" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCMATRIZ'], false); }?>" type="text" >
												</div>                                        
												<div class="col-sm-3">
													<label>Jurídico</label>
													<input class="form-control classmonetario" title="Jurídico" name="vMCXCJURIDICO" id="vMCXCJURIDICO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCJURIDICO'], false); }?>" type="text" >
												</div>
												<div class="col-sm-3">
													<label>Técnico</label>
													<input class="form-control classmonetario" title="Técnico" name="vMCXCTECNICO" id="vMCXCTECNICO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCTECNICO'], false); }?>" type="text" >
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-4">
													<label>Comissão</label>
													<input class="form-control classmonetario" title="Comissão" name="vMCXCVALORCOMISSAO" id="vMCXCVALORCOMISSAO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCVALORCOMISSAO'], false); }?>" type="text" >
												</div>  	
												<div class="col-sm-4">
													<label>Total de Comissão</label>
													<input class="form-control classmonetario" readonly title="Total de Comissão" name="vMCXCTOTALCOMISSOES" id="vMCXCTOTALCOMISSOES" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCTOTALCOMISSOES'], false); }?>" type="text" >
												</div>                                        
												<div class="col-sm-4">
													<label>Total de Taxas</label>
													<input class="form-control classmonetario" readonly title="Total de Taxas" name="vMCXCTOTALTAXAS" id="vMCXCTOTALTAXAS" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXCTOTALTAXAS'], false); }?>" type="text" >
												</div>
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
		<script src="js/cadCAs.js"></script>

    </body>
</html>