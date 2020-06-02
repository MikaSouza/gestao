<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1987);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/combos/comboPaises.php';
include_once __DIR__.'/combos/comboEstados.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?>

        <link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
        <link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
		<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />				
    </head>
	<?php if ($vIOid > 0){ ?>
    <body onload="mostrarIE('<?= $vROBJETO['FAVISENTAIE']; ?>'); mostrarJxF('<?= $vROBJETO['FAVTIPOCLIENTE']; ?>'); exibirCidades('<?= $vROBJETO['ESTCODIGO']; ?>', '<?= $vROBJETO['CIDCODIGO']; ?>', 'div_cidade_inpi', 'vICIDCODIGO');">
	<?php } else { ?>
	<body onload="mostrarJxF('J');">
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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">
                                        <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<input type="hidden" name="vIFAVIMPCLICODIGO" id="vIFAVIMPCLICODIGO" value="<?= $vROBJETO['FAVIMPCLICODIGO']; ?>"/>
										<input type="hidden" name="vIFAVIMPUSUCODIGO" id="vIFAVIMPUSUCODIGO" value="<?= $vROBJETO['FAVIMPUSUCODIGO']; ?>"/>
                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#contatos" role="tab">Contato</a>
                                        </li>                                                                               
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#enderecos-1" role="tab">Endereço</a>
                                        </li>
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel"> 
											<?php if($vIOid == '') { ?>
											<div class="form-group row">
												<div class="col-md-2 fa-pull-right ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="exibirFormModal('','Colaboradores','Colaboradores')">Importar Colaborador</button><br>
												</div>
												<div class="col-md-2 fa-pull-left ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="exibirFormModal('','Clientes','Clientes')">Importar Cliente</button><br>
												</div>
											</div>	
											<?php } ?>
											<div class="form-group row">
												<div class="col-md-4">
													<label>Tipo Pessoa
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFAVTIPOCLIENTE" class="custom-select obrigatorio" name="vSFAVTIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['FAVTIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['FAVTIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>
												<div class="col-md-4 divJuridica">                                                        
													<label>CNPJ
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFAVCNPJ" id="vSFAVCNPJ" type="text" title="CNPJ" value="<?= ($vIOid > 0 ? $vROBJETO['FAVCNPJ'] : '');?>" >                                                       
												</div>
												<div class="col-md-4 divJuridica">
													<br/>				  
													<button type="button" class="btn btn-secondary waves-effect" onclick="buscarDadosReceita();">Buscar Dados Receita Federal</button><br>
												</div>	
												<div class="col-md-2 divFisica" >                                                        
													<label>CPF
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFAVCPF" id="vSFAVCPF" type="text" title="CPF" value="<?= ($vIOid > 0 ? $vROBJETO['FAVCPF'] : '');?>" >                                                       
												</div>
												<div class="col-md-2 divFisica">
                                                    <label>Data Nascimento</label>
                                                    <input class="form-control" title="Data Nascimento" name="vDFAVDATA_NASCIMENTO" id="vDFAVDATA_NASCIMENTO" value="<?= $vROBJETO['FAVDATA_NASCIMENTO'];  ?>" type="date" >
                                                </div>	
											</div>											
											<div class="form-group row divJuridica">
                                                <div class="col-md-6">                                                      
													<label>Razão Social
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFAVRAZAOSOCIAL" id="vSFAVRAZAOSOCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FAVRAZAOSOCIAL'] : ''); ?>" title="Razão Social" >
												</div>
												<div class="col-md-6">                                                      
													<label>Nome Fantasia
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFAVNOMEFANTASIA" id="vSFAVNOMEFANTASIA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FAVNOMEFANTASIA'] : ''); ?>" title="Nome Fantasia" >
												</div>	
                                            </div>   
											<div class="form-group row divFisica">
                                                <div class="col-md-6">                                                      
													<label>Nome
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vHFAVNOME" id="vHFAVNOME" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FAVRAZAOSOCIAL'] : ''); ?>" title="Nome" >
												</div>												
                                            </div>	
											<div class="form-group row divJuridica">                                                												
                                                <div class="col-md-6">
                                                    <label>Início das Atividades</label>
                                                    <input class="form-control" name="vDFAVDATA_INICIO_ATIVIDADES" id="vDFAVDATA_INICIO_ATIVIDADES" value="<?= $vROBJETO['FAVDATA_INICIO_ATIVIDADES'];  ?>" type="date" >                                                    
												</div>
												<div class="col-md-6">   
                                                    <label>Situação Receita Federal
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vIFAVSITUACAORECEITA" id="vIFAVSITUACAORECEITA" class="custom-select obrigatorio" title="Situação Receita Federal">
                                                        <option value="">  -------------  </option>
														<?php 
														if ($vROBJETO['FAVSITUACAORECEITA'] == '') $vROBJETO['FAVSITUACAORECEITA'] = 20;
														foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['FAVSITUACAORECEITA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
											</div>											
                                            <div class="form-group row divJuridica">
                                                <div class="col-md-3">   
                                                    <label>Inscrição Municipal</label>
                                                    <input class="form-control" name="vSFAVIM" id="vSFAVIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FAVIM'] : '');?>" >
                                                </div>
                                                
                                                <div class="col-md-3">   
                                                    <label>Inscrição Estadual</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFAVISENTAIE" class="custom-select" name="vSFAVISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['FAVISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['FAVISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>
                                                <!-- estadual -->
                                                <div class="col-sm-3 divIE" id="divIE">
                                                    <label>Nº Insc. Estadual</label>
                                                    <input class="form-control" title="Nº Insc. Estadual" name="vSFAVIE" id="vSFAVIE" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FAVIE'] : '');?>" >
                                                </div>
                                            </div>
                                    </div>                                    
									<!-- Aba Contatos -->
									<div class="tab-pane p-3" id="contatos" role="tabpanel">
										<input type="hidden" name="vHCONCODIGO" id="vHCONCODIGO" value="<?= $vROBJETO['CONCODIGO']; ?>"/>
										<div class="form-group row">
											<div class="col-sm-6">
												<label>Contato Principal</label>
												<input class="form-control" title="Contato" name="vSFAVCONTATO" id="vSFAVCONTATO" type="text" value="<?= $vROBJETO['FAVCONTATO'];?>" >
											</div>
											<div class="col-sm-3">
												<label>Telefone Principal</label>													
												<input type="text" id="vSFAVFONE" name="vSFAVFONE" class="form-control" maxlength="14" title="Telefone Principal" value="<?= $vROBJETO['FAVFONE']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
											</div>
											<div class="col-sm-3">
												<label>Telefone Celular</label>													
												<input type="text" id="vSFAVCELULAR" name="vSFAVCELULAR" class="form-control" maxlength="15" title="Telefone Celular" value="<?= $vROBJETO['FAVCELULAR']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
											</div>												
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label>E-mail Principal</label>
												<input class="form-control" title="E-mail" name="vSFAVEMAIL" id="vSFAVEMAIL" type="email" value="<?= $vROBJETO['FAVEMAIL'];?>" >
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-6">
												<label>Contato Nota Fiscal</label>
												<input class="form-control" title="Contato" name="vSFAVCONTATOENVIONFE" id="vSFAVCONTATOENVIONFE" type="text" value="<?= $vROBJETO['FAVCONTATOENVIONFE'];?>" >
											</div>
											<div class="col-sm-6">
												<label>E-mail Nota Fiscal</label>
												<input class="form-control" title="E-mail" name="vSFAVEMAILENVIONFE" id="vSFAVEMAILENVIONFE" type="email" value="<?= $vROBJETO['FAVEMAILENVIONFE'];?>" >
											</div>
										</div>	
										<div class="accordion" id="accordionExample-faq">												
											<div class="card shadow-none border mb-1">
												<div class="card-header" id="headingTwo">
												<h5 class="my-0">
													<button class="btn btn-link collapsed ml-4 align-self-center" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
														Instruções especiais do contato
													</button>
												</h5>
												</div>
												<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample-faq">
												<div class="card-body">
													<textarea class="form-control" id="vSFAVOBSCONTATO" name="vSFAVOBSCONTATO" rows="3"><?= $vROBJETO['FAVOBSCONTATO']; ?></textarea>
												</div>
												</div>
											</div>
										</div><!--end accordion-->									
									</div>
									<!-- Aba Contatos end -->
										
									<!-- Aba Dados Documento -->
                                    <div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
										<div class="form-group row">
											<div class="col-sm-3">
												<label>País</label>
												<select title="País" id="vIPAICODIGO" class="custom-select" name="vIPAICODIGO">
													<?php 
													if ($vROBJETO['PAICODIGO'] == '') $vROBJETO['PAICODIGO'] = 30;
													foreach (comboPaises() as $tabelas): ?>
														<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vROBJETO['PAICODIGO'] == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-sm-3">
												<label>CEP</label>
												<input class="form-control" title="CEP" name="vSFAVCEP" id="vSFAVCEP" type="text" value="<?= $vROBJETO['FAVCEP'];  ?>" onblur="buscarCEP(this.value);">
											</div>
											<div class="col-sm-3">
												<label>Bairro</label>
												<input class="form-control" title="Bairro" name="vSFAVBAIRRO" id="vSFAVBAIRRO" type="text" value="<?= $vROBJETO['FAVBAIRRO'];  ?>" >
											</div>											
										</div>											
										<div class="form-group row">
											<div class="col-sm-4">
												<label>Endereço</label>
												<input class="form-control" title="Endereço" name="vSFAVLOGRADOURO" id="vSFAVLOGRADOURO" type="text" value="<?= $vROBJETO['FAVLOGRADOURO'];?>">
											</div>
											<div class="col-sm-2">
												<label>Nº</label>
												<input class="form-control" name="vSFAVNROLOGRADOURO" id="vSFAVNROLOGRADOURO" type="text" value="<?= $vROBJETO['FAVNROLOGRADOURO'];?>" >
											</div>
											<div class="col-sm-2">
												<label>Complemento</label>
												<input class="form-control" name="vSFAVCOMPLEMENTO" id="vSFAVCOMPLEMENTO" type="text" value="<?= $vROBJETO['FAVCOMPLEMENTO'];?>" >
											</div>
											<div class="col-sm-2">
												<label>Estado</label>
												<select title="Estado" id="vIESTCODIGO" class="custom-select" name="vIESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade_inpi', 'vICIDCODIGO');">
													<?php foreach (comboEstados() as $tabelas): ?>
														<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vROBJETO['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-sm-2">
												<div id="div_cidade_inpi"></div>
											</div>
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
        <!-- end page-wrapper -->
		<div class="modal fade bs-example-modal-center"  role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalClientes">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Importar Cliente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row" id="modal_div_Clientes">
							<input type="hidden" id="hdn_pai_ClientesxRelacionados" name="hdn_pai_ClientesxRelacionados" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_ClientesxRelacionados" name="hdn_filho_ClientesxRelacionados" value="">
							<div class="col-md-10">                                                      
								<label>Sigla - Cliente
									<small class="text-danger font-13">*</small>
								</label>			
								<select name="vICLICODIGO" id="vICLICODIGO" title="Sigla - Cliente" class="form-control  divObrigatorio" style="width: 100%;font-size: 13px;" onblur="validarCliente();"/>								
								<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalClientes('modal_div_Clientes','transactionClientes.php', 'div_relacionados', 'Clientes', '<?= $vIOid;?>');">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
		<!-- end page-wrapper -->
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalColaboradores">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Importar Colaboradores</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row" id="modal_div_Colaboradores">
							<input type="hidden" id="hdn_pai_ClientesxRelacionados" name="hdn_pai_ClientesxRelacionados" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_ClientesxRelacionados" name="hdn_filho_ClientesxRelacionados" value="">
							<div class="col-md-12">
								<label>Colaborador
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vIUSUCODIGO" id="vIUSUCODIGO" class="custom-select divObrigatorio" title="Colaborador">
									<option value="">  -------------  </option>
									<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
										<option value="<?php echo $usuarios['USUCODIGO']; ?>"><?php echo $usuarios['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select> 
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalColaboradores('modal_div_Colaboradores','transactionUsuarios.php', 'div_relacionados', 'Colaboradores', '<?= $vIOid;?>');">Salvar</button>
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
		 <script src="../assets/pages/jquery.form-upload.init.js"></script>
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="../assets/plugins/select2/select2.min.js"></script>
		<!--<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>-->
		<script src="js/cadFavorecidos.js"></script>
    </body>
</html>