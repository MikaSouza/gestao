<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1944);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
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
										<?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#prazos" role="tab" onclick="gerarGridJSON('transactionUsuariosxEnderecos.php', 'div_Enderecos', 'Enderecos', '<?= $vIOid;?>');">Prazos</a>
                                        </li>	
                                        <?php } ?>                                        
                                    </ul>
                                    <!-- Nav tabs end -->
										
									<!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
										
											<div class="form-group row">
												<div class="col-sm-3">
													<label>Pasta</label>
													<input class="form-control is-invalid obrigatorio classnumerico" title="Pasta" name="vICODPASTA" id="vICODPASTA" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CODPASTA']; }?>" required>
												</div>                                        
												<div class="col-sm-3">
													<label>Processo</label>
													<input class="form-control is-invalid obrigatorio classnumericobig" title="Processo" name="vICODPROCESSO" id="vICODPROCESSO" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CODPROCESSO']; }?>" required>
												</div>        
												<div class="col-sm-3">	
													<label>Data Lançamento
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Data Lançamento" name="vDCODDATAPROCESSO" id="vDCODDATAPROCESSO" value="<?= $vROBJETO['CODDATAPROCESSO'];  ?>" type="date" >
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
													<label>Representante
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vICODCONSULTOR" id="vICODCONSULTOR" class="custom-select obrigatorio" title="Representante">
														<option value="">  -------------  </option>
														<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['CODCONSULTOR'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<div class="form-group row">																						
												<div class="col-md-6">
													<label>Tipo de proteção
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vICODORGAO" id="vICODORGAO" class="custom-select obrigatorio" title="Tipo de proteção">
														<option value="">  -------------  </option>
														<?php foreach (comboTabelas('PROCESSOS - ORGAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CODORGAO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-6">
													<label>Motivo Cancelamento</label>
													<select name="vICODMOTIVOCANCELAMENTO" id="vICODMOTIVOCANCELAMENTO" class="custom-select" title="Motivo Cancelamento">
														<option value="">  -------------  </option>
														<?php foreach (comboTabelas('PROCESSOS - CANCELAMENTO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CODMOTIVOCANCELAMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select> 		
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">                                                      
													<label>Título
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Título" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio" value="<?php echo $vROBJETO['CLINOME']; ?>" />												
												</div> 	
												<div class="col-sm-3">
													<label>Livro</label>
													<input class="form-control is-invalid classnumerico" title="Livro" name="vICODPASTA" id="vICODPASTA" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CODPASTA']; }?>">
												</div>                                        
												<div class="col-sm-3">
													<label>Folha do Livro</label>
													<input class="form-control is-invalid" title="Folha do Livro" name="vICODPROCESSO" id="vICODPROCESSO" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CODPROCESSO']; }?>">
												</div>
											</div>
											<div class="form-group row">																						
												<div class="col-md-6">
													<label>Tipo de Direito Autoral
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vICODORGAO" id="vICODORGAO" class="custom-select obrigatorio" title="Tipo de Direito Autoral">
														<option value="">  -------------  </option>
														<?php foreach (comboTabelas('PROCESSOS - ORGAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CODORGAO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-6">
													<label>Depositante</label>
													<input title="Depositante" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio" value="<?php echo $vROBJETO['CLINOME']; ?>" /> 		
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-12">
													<label>Observações</label>
													<textarea class="form-control" id="vSCODOBSERVACAO" name="vSCODOBSERVACAO" rows="3"><?= $vROBJETO['CODOBSERVACAO']; ?></textarea>
												</div>	
											</div>
											<div class="form-group">
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
										<!-- Aba Contatos -->
										<div class="tab-pane p-2" id="prazos" role="tabpanel">
											<div class="form-group row">
												<div id="div_auditoria" class="table-responsive"></div>
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
		<script src="js/cadCodigoBarras.js"></script>

    </body>
</html>