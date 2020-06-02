<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1960);

include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboProdutosxServicos.php';
include_once __DIR__.'/../cadastro/combos/comboPaises.php';
include_once __DIR__.'/../cadastro/combos/comboEstados.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';

// fill empresa
if ($vIOid == ''){
	$vROBJETO = fillProspeccaoxClientes($_GET['oidcliente']);
	$vIRESPONSAVEL = $_SESSION['SI_USUCODIGO'];
	
	//incluir contatos
	include_once __DIR__.'/../cadastro/transaction/transactionContatos.php';
	$vRCONTATOINPI = fill_Contatos($vIOid, 26933);

	//incluir endereços
	include_once __DIR__.'/../cadastro/transaction/transactionEnderecos.php';	
	$vRENDERECOINPI = fill_Enderecos($vIOid, 426);
	
}	

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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">
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
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#historico" role="tab" onclick="gerarGridJSON('transactionProspeccaoxAgenda.php', 'div_historico', 'ProspeccaoxAgenda', '1');">Contatos/Atividades</a>
                                        </li>	
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#historicogeral" role="tab" onclick="gerarGridJSON('transactionClientesxHistorico.php', 'div_historico_geral', 'ClientesxHistorico', '<?= $vROBJETO['CLICODIGO'];?>');">Histórico Geral do Cliente</a>
                                        </li>
                                    </ul>
                                    <!-- Nav tabs end --> 

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row ">
                                                <div class="col-md-6">                                                      
													<label>Sigla/Cliente (+ Detalhes do Cliente clique <a href="../cadastro/cadClientes.php?method=insert" target="_blank">AQUI</a>)
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vHCLINOME" id="vHCLINOME" type="text" value="<?= $vROBJETO['CLIENTE']; ?>" title="Nome Cliente" >
													<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value="<?= $vROBJETO['CLICODIGO']; ?>"/>
												</div>   
												<div class="col-md-6">
                                                    <label>Representante
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vICXPREPRESENTANTE" id="vICXPREPRESENTANTE" class="custom-select obrigatorio" title="Natureza Jurídica">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboUsuarios('') as $usuarios): ?>                                                            
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vIRESPONSAVEL == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
											</div>	
											<div class="form-group row"> 	
												<div class="col-md-3">
                                                    <label>Produto/Serviço
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vIPXSCODIGO" id="vIPXSCODIGO" class="custom-select obrigatorio" title="Produto/Serviço">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboProdutosxServicos() as $produtos): ?>                                                            
															<option value="<?php echo $produtos['PXSCODIGO']; ?>" <?php if ($vROBJETO['PXSCODIGO'] == $produtos['PXSCODIGO']) echo "selected='selected'"; ?>><?php echo $produtos['PXSNOME']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
												<div class="col-md-3">
                                                    <label>Fonte Oportunidade
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vITABFONTEOPORTUNIDADE" id="vITABFONTEOPORTUNIDADE" class="custom-select obrigatorio" title="Fonte Oportunidade">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('CRM - FONTE OPORTUNIDADE') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABFONTEOPORTUNIDADE'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
												<div class="col-md-3">
                                                    <label>Meio de Contato
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vICXPMEIOCONTATO" id="vICXPMEIOCONTATO" class="custom-select obrigatorio" title="Meio de Contato">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('CRM - MEIO CONTATO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXPMEIOCONTATO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<input type="hidden" name="vIPXCCODIGO" id="vIPXCCODIGO" value="1">
												<input type="hidden" name="vSCXPINSERIDOPOR" id="vSCXPINSERIDOPOR" value="M">
												<input type="hidden" name="vSCXPTIPO" id="vSCXPTIPO" value="S">
																								
                                            </div>    
											<div class="accordion" id="reformaSim">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Contato Principal
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<p class="mb-0 text-muted">								
															<input type="hidden" name="vHINPICONCODIGO" id="vHINPICONCODIGO" value="<?= $vRCONTATOINPI['CONCODIGO']; ?>"/>															
															<div class="form-group row">
																<div class="col-sm-6">
																	<label>Contato</label>
																	<input class="form-control obrigatorio" title="Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vRCONTATOINPI['CONNOME'];?>" >
																</div>
																<div class="col-sm-3">
																	<label>Telefone Principal</label>
																	<input class="form-control obrigatorio" title="Telefone Principal" name="vSCXPFONE" id="vSCXPFONE" type="text" value="<?= $vRCONTATOINPI['CONFONE']; ?>" maxlength="15" >
																</div>
																<div class="col-sm-3">
																	<label>Telefone Celular</label>
																	<input class="form-control" name="vHINPICONCELULAR" id="vHINPICONCELULAR" type="text" value="<?= $vRCONTATOINPI['CONCELULAR']; ?>" maxlength="15" >
																</div>												
															</div>
															<div class="form-group row">
																<div class="col-sm-6">
																	<label>E-mail</label>
																	<input class="form-control obrigatorio" title="E-mail" name="vSCXPEMAIL" id="vSCXPEMAIL" type="email" value="<?= $vRCONTATOINPI['CONEMAIL'];?>" >
																</div>
																<div class="col-sm-6">
																	<label>Site</label>
																	<input class="form-control" name="vSCXPSITE" id="vSCXPSITE" type="text" value="<?= $vRCONTATOINPI['CXPSITE'];?>" >
																</div>
															</div>	
															</p>
														</div>
													</div>
												</div>
											</div>
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Endereço Principal
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<p class="mb-0 text-muted">											
															<div class="form-group row">
																<div class="col-sm-3">
																	<label>País</label>
																	<select title="País" id="vHINPIPAICODIGO" class="custom-select" name="vHINPIPAICODIGO">
																		<?php 
																		if ($vRENDERECOINPI['PAICODIGO'] == '') $vRENDERECOINPI['PAICODIGO'] = 30;
																		foreach (comboPaises() as $tabelas): ?>
																			<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vRENDERECOINPI['PAICODIGO'] == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="col-sm-3">
																	<label>CEP</label>
																	<input class="form-control" title="CEP" name="vHINPIENDCEP" id="vHINPIENDCEP" type="text" value="<?= $vRENDERECOINPI['ENDCEP'];  ?>" onblur="buscarCEP(this.value, 'INPI');">
																</div>
																<div class="col-sm-3">
																	<label>Bairro</label>
																	<input class="form-control" title="Bairro" name="vHINPIENDBAIRRO" id="vHINPIENDBAIRRO" type="text" value="<?= $vRENDERECOINPI['ENDBAIRRO'];  ?>" >
																</div>											
															</div>											
															<div class="form-group row">
																<div class="col-sm-4">
																	<label>Endereço</label>
																	<input class="form-control" title="Endereço" name="vHINPIENDLOGRADOURO" id="vHINPIENDLOGRADOURO" type="text" value="<?= $vRENDERECOINPI['ENDLOGRADOURO'];?>">
																</div>
																<div class="col-sm-2">
																	<label>Nº</label>
																	<input class="form-control" name="vHINPIENDNROLOGRADOURO" id="vHINPIENDNROLOGRADOURO" type="text" value="<?= $vRENDERECOINPI['ENDNROLOGRADOURO'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Complemento</label>
																	<input class="form-control" name="vHINPIENDCOMPLEMENTO" id="vHINPIENDCOMPLEMENTO" type="text" value="<?= $vRENDERECOINPI['ENDCOMPLEMENTO'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Estado</label>
																	<select title="Estado" id="vHINPIESTCODIGO" class="custom-select" name="vHINPIESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade_inpi', 'vHINPICIDCODIGO');">
																		<option value="">  ---------  </option>
																		<?php foreach (comboEstados() as $tabelas): ?>																
																			<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vRENDERECOINPI['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="col-sm-2">
																	<div id="div_cidade_inpi"></div>
																</div>
															</div>	
															</p>
														</div>
													</div>
												</div>		
											</div>	
                                            <div class="form-group row">
												<div class="col-md-12">
													<label>Descrição
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<textarea title="Descrição" class="form-control obrigatorio" id="vSCXPOBSERVACAO" name="vSCXPOBSERVACAO" rows="5"><?= $vROBJETO['CXPOBSERVACAO']; ?></textarea>											
												</div> 	
											</div> 
											<div class="accordion" id="reformaSim4">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Informações Gerais
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<div class="form-group row">
															
																<div class="col-sm-2">
																	<label>Atividade Atual</label>
																	<input class="form-control" disabled title="Atividade Atual" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Último Contato</label>
																	<input class="form-control" disabled title="Último Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Próximo Contato</label>
																	<input class="form-control" disabled title="Próximo Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Data Cadastro</label>
																	<input class="form-control" disabled title="Próximo Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Data Conclusão</label>
																	<input class="form-control" disabled title="Próximo Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Tempo Fechamento</label>
																	<input class="form-control" disabled title="Próximo Contato" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vROBJETO['CONNOME'];?>" >
																</div>
															</div>
														</div>
													</div>
												</div>		
											</div>
											<div class="form-group">
												<label class="form-check-label" for="invalidCheck3" style="color: red">
													Campos em vermelho são de preenchimento obrigatório!<br/>												
												</label>
											</div>											
										</div>	
                                        <div class="tab-pane p-3" id="historico" role="tabpanel">
											<div class="form-group row">
												<div id="div_historico" class="table-responsive"></div>
											</div>
										</div>   
										<div class="tab-pane p-3" id="historicogeral" role="tabpanel">
											<div class="form-group row">
												<div id="div_historico_geral" class="table-responsive"></div>
											</div>
										</div>	
                                    </div>

									<?php include('../includes/botoes_cad_novo.php'); ?>
                                
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalClientesxHistorico">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Histórico Geral</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body" id="modal_div_ClientesxHistorico">
							<div class="row">
								<input type="hidden" id="hdn_pai_ClientesxHistorico" name="hdn_pai_ClientesxHistorico" value="<?= $vIOid;?>">
								<input type="hidden" id="hdn_filho_ClientesxHistorico" name="hdn_filho_ClientesxHistorico" value="">								
								<div class="col-md-6">
									<label>Data Contato
										<small class="text-danger font-13">*</small>
									</label>
									<input class="form-control divObrigatorio" title="Data Contato" name="vDCHGDATA" id="vDCHGDATA" value="" type="date" >
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">   
									<label>Tipo Contato
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGTIPO" id="vICHGTIPO" class="custom-select divObrigatorio" title="Tipo Contato">
										<option value="">  -------------  </option>
										<?php 									
										foreach (comboTabelas('HISTORICO GERAL - TIPO') as $tabelas): ?>                                                            
											<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>                                                    
								</div>
								<div class="col-md-6">   
									<label>Status
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio" title="Status">
										<option value="">  -------------  </option>
										<?php 									
										foreach (comboTabelas('HISTORICO GERAL - STATUS') as $tabelas): ?>                                                            
											<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>                                                    
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Descrição
										<small class="text-danger font-13">*</small>
									</label>
									<textarea class="form-control divObrigatorio" id="vSCHGHISTORICO" name="vSCHGHISTORICO" rows="3"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mt-3">
									<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalClientesxHistorico('modal_div_ClientesxHistorico','transactionClientesxHistorico.php', 'div_ClientesxHistorico', 'ClientesxHistorico', '<?= $vIOid;?>');">Salvar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
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

        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="js/cadSolicitacaoCRM.js"></script>
    </body>
</html>