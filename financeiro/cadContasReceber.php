<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1971);
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
		<link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
				
    </head>
    <?php if ($vIOid > 0){ ?>
    <body onload="exibirContasBancarias('<?= $vROBJETO['EMPCODIGO']; ?>', '<?= $vROBJETO['CBACODIGO']; ?>', ''); exibirClientexConsultor('vICTRCONSULTOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', ''); gerarGridJSON('transactionContasReceberxParcelas.php', 'div_parcelas', 'ContasReceberxParcelasVinculo', '<?= $vIOid;?>');">
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

										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?= $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										 <!-- Nav tabs -->
										 <ul  class="nav nav-tabs" role="tablist">
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link active" data-toggle="tab" href="#home" role="tab">Dados Gerais</a>
											</li>										
										</ul>
									<!-- Nav tabs end -->
									<!-- Tab panes -->
                                    <div class="tab-content">
										<!-- Aba Dados Gerais -->
										<div class="tab-pane active p-3" id="home" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>Filial
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select obrigatorio" title="Filial" onchange="exibirContasBancarias(this.value, '', '');">
														<option value></option>
                                                       	<?php foreach (comboEmpresaUsuaria('N') as $tabelas): ?>
															<option value="<?php echo $tabelas['EMPCODIGO']; ?>" <?php if ($vROBJETO['EMPCODIGO'] == $tabelas['EMPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-6">
													<div id="divContasBancarias"></div>
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
												<div class="col-md-6">
													<label>Procedimento
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vITABPLANOCONTAS" id="vITABPLANOCONTAS" class="custom-select obrigatorio" title="Procedimento">
														<option value></option>
                                                       	<?php foreach (comboTabelas('CONTAS A RECEBER - PLANO DE CONTAS') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABPLANOCONTAS'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>	
											<div class="form-group row">
												<div class="col-md-6">
													<label>Modo Faturamento
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vSCTRMODOFATURAMENTO" id="vSCTRMODOFATURAMENTO" class="custom-select obrigatorio" title="Modo Faturamento">
														<option value="Recibo" <?php if ($vROBJETO['CTRMODOFATURAMENTO'] == "Recibo") echo "selected='selected'"; ?>>Recibo</option>
														<option value="Nota" <?php if ($vROBJETO['CTRMODOFATURAMENTO'] == "Nota") echo "selected='selected'"; ?>>Nota</option>														
													</select>
												</div>
												<div class="col-md-3">
													<label>Número
														<small class="text-danger font-13">*</small>
													</label>
													<input type="text" class="form-control obrigatorio" name="vSCTRNRODOCUMENTO" id="vSCTRNRODOCUMENTO" maxlength="20" title="Número" value="<?= $vROBJETO['CTRNRODOCUMENTO']; ?>"/>
												</div>
												<div class="col-md-3">
													<label>Data Emissão
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Data Emissão" name="vDCTRDATAEMISSAODOCUMENTO" id="vDCTRDATAEMISSAODOCUMENTO" value="<?= $vROBJETO['CTRDATAEMISSAODOCUMENTO'];  ?>" type="date" >
												</div>
											</div>	
											<div class="form-group row">
												<div class="col-md-3">
													<label>Moeda
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vICTRMOEDA" id="vICTRMOEDA" class="custom-select obrigatorio" title="Moeda">
														<option value></option>
														<?php if ($vROBJETO['CTRMOEDA'] == '') $vROBJETO['CTRMOEDA'] = 26780;
															foreach (comboTabelas('CONTAS A RECEBER - MOEDA') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CTRMOEDA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Valor Total
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor Total" name="vMCTRVALORARECEBER" id="vMCTRVALORARECEBER" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRVALORARECEBER'], false); }?>" type="text" >
												</div>
											</div>	
											<div class="form-group row">
												<div class="col-md-2">
													<label>Protesto</label>
													<select class="form-control" name="vHCXPPROTESTO" id="vHCXPPROTESTO">
														<option value="N" <?php if ($vROBJETO['CXPPROTESTO'] == "N") echo "selected='selected'"; ?>>Não</option>
														<option value="S" <?php if ($vROBJETO['CXPPROTESTO'] == "S") echo "selected='selected'"; ?>>Sim</option>														
													</select>
												</div>	
												<div class="col-md-2">
													<label>Faturamento Diretoria?</label>
													<select class="form-control" name="vHCXPDIRETORIA" id="vHCXPDIRETORIA">
														<option value="N" <?php if ($vROBJETO['CXPDIRETORIA'] == "N") echo "selected='selected'"; ?>>Não</option>
														<option value="S" <?php if ($vROBJETO['CXPDIRETORIA'] == "S") echo "selected='selected'"; ?>>Sim</option>														
													</select>
												</div>
												<div class="col-md-2">
													<label>Aut. Cliente</label>
													<select class="form-control" name="vHCXPAUTCLIENTE" id="vHCXPAUTCLIENTE">
														<option value="N" <?php if ($vROBJETO['CXPAUTCLIENTE'] == "N") echo "selected='selected'"; ?>>Não</option>
														<option value="S" <?php if ($vROBJETO['CXPAUTCLIENTE'] == "S") echo "selected='selected'"; ?>>Sim</option>														
													</select>
												</div>
												<div class="col-md-2">
													<label>Aut. Comissão</label>
													<select class="form-control" name="vHCXPAUTCOMISSAO" id="vHCXPAUTCOMISSAO">
														<option value="S" <?php if ($vROBJETO['CXPAUTCOMISSAO'] == "S") echo "selected='selected'"; ?>>Sim</option>														
														<option value="N" <?php if ($vROBJETO['CXPAUTCOMISSAO'] == "N") echo "selected='selected'"; ?>>Não</option>													
													</select>
												</div>
												<div class="col-md-2">
													<label>Exterior</label>
													<select class="form-control" name="vHCXPEXTERIOR" id="vHCXPEXTERIOR">
														<option value="N" <?php if ($vROBJETO['CXPEXTERIOR'] == "N") echo "selected='selected'"; ?>>Não</option>
														<option value="S" <?php if ($vROBJETO['CXPEXTERIOR'] == "S") echo "selected='selected'"; ?>>Sim</option>														
													</select>
												</div>
												<div class="col-md-2">
													<label>Tipo Faturamento</label>
													<select class="form-control" name="vSCTRTIPOFATURAMENTO" id="vSCTRTIPOFATURAMENTO">
														<option value="F1" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "N") echo "selected='selected'"; ?>>F1</option>
														<option value="F2" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "S") echo "selected='selected'"; ?>>F2</option>														
													</select>
												</div>												
											</div>	
											
											<div class="form-group row">
												<div class="col-md-12">
													<label>Texto</label>
													<textarea class="form-control" id="vSCTRDESCRICAO" name="vSCTRDESCRICAO" rows="3"><?= $vROBJETO['CTRDESCRICAO']; ?></textarea>
												</div>	
											</div>
											<div class="accordion" id="reformaSim">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Dados da Parcela
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">										
															<?php if ($vIOid > 0){ ?>
																<div class="form-group row">
																	<div id="div_parcelas" class="table-responsive"></div>
																</div>
															<?php } else { ?>
																<div class="form-group row">
																	<div class="col-md-3">
																		<label>Status/Posição
																			<small class="text-danger font-13">*</small>
																		</label>
																		<select name="vHCXPPOSICAO" id="vHCXPPOSICAO" class="custom-select obrigatorio" title="Status/Posição">
																			<option value></option>
																			<?php if ($vROBJETO['CXPPOSICAO'] == '') $vROBJETO['CXPPOSICAO'] = 25481;
																				foreach (comboTabelas('CONTAS A RECEBER - POSICAO') as $tabelas): ?>                                                            
																				<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXPPOSICAO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>
																	<div class="col-md-3">
																		<label>Forma de Cobrança
																			<small class="text-danger font-13">*</small>
																		</label>
																		<select name="vHCXPFORMACOBRANCA" id="vHCXPFORMACOBRANCA" class="custom-select obrigatorio" title="Forma de Cobrança">
																			<option value></option>
																			<?php foreach (comboTabelas('CONTAS A RECEBER - FORMA DE COBRANÇA') as $tabelas): ?>                                                            
																				<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXPFORMACOBRANCA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>
																	<div class="col-md-3">
																		<label>Classificação
																			<small class="text-danger font-13">*</small>
																		</label>
																		<select name="vHCXPCLASSIFICACAO" id="vHCXPCLASSIFICACAO" class="custom-select obrigatorio" title="Classificação">
																			<option value></option>
																			<?php foreach (comboTabelas('CONTAS A RECEBER - FORMA DE COBRANÇA') as $tabelas): ?>                                                            
																				<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CXPCLASSIFICACAO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>
																</div>
																<div class="form-group row">												
																	<div class="col-sm-2">
																		<label>Valor do Título
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input class="form-control obrigatorio classmonetario" title="Valor do Título" name="vHCXPVALOR" id="vHCXPVALOR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CXPVALOR'], false); }?>" type="text" >
																	</div>
																	<div class="col-md-2">
																		<label>Data Vencimento
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input class="form-control obrigatorio" title="Data Vencimento" name="vHCXPDATAVENCIMENTO" id="vHCXPDATAVENCIMENTO" value="<?= $vROBJETO['CXPDATAVENCIMENTO'];  ?>" type="date" >
																	</div>
																	<div class="col-md-2">
																		<label>Data Pagamento</label>
																		<input class="form-control " title="Data Emissão" name="vHCXPDATAPAGAMENTO" id="vHCXPDATAPAGAMENTO" value="<?= $vROBJETO['CXPDATAPAGAMENTO'];  ?>" type="date" >
																	</div>
																	<div class="col-md-6">
																		<label>Gerar parcelas com base na parcela atual? 
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input class="form-control obrigatorio" title="Gerar Parcelas" name="vHCXPDATAPAGAMENTO" id="vHCXPDATAPAGAMENTO" value="1" type="number" >
																	</div>
																</div>
																<div class="form-group row">												
																	<div class="col-sm-2">
																		<label>Gerar CA?</label>
																		<select class="form-control" name="vHGERARCA" id="vHGERARCA" onchange="gerarCA(this.value);">
																			<option value="N" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "N") echo "selected='selected'"; ?>>Não</option>
																			<option value="S" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "S") echo "selected='selected'"; ?>>Sim</option>														
																		</select>
																	</div>
																	<div class="col-md-2">
																		<label>Gerar Contrato?</label>
																		<select class="form-control" name="vHGERARCONTRATO" id="vHGERARCONTRATO" onchange="gerarContrato(this.value);">
																			<option value="N" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "N") echo "selected='selected'"; ?>>Não</option>
																			<option value="S" <?php if ($vROBJETO['CTRTIPOFATURAMENTO'] == "S") echo "selected='selected'"; ?>>Sim</option>														
																		</select>
																	</div>													
																</div>
																<div class="form-group row divCA">												
																	<div class="col-sm-2">
																		<label>Número
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input type="text" class="form-control" name="vHNUMEROCA1" id="vHNUMEROCA1" maxlength="20" title="Número" value=""/>
																	</div>
																	<div class="col-sm-2">
																		<label>Número</label>
																		<input type="text" class="form-control" name="vHNUMEROCA2" id="vHNUMEROCA2" maxlength="20" title="Número" value=""/>											
																	</div>
																	<div class="col-sm-2">
																		<label>Número</label>
																		<input type="text" class="form-control" name="vHNUMEROCA3" id="vHNUMEROCA3" maxlength="20" title="Número" value=""/>											
																	</div>	
																	<div class="col-sm-2">
																		<label>Número</label>
																		<input type="text" class="form-control" name="vHNUMEROCA4" id="vHNUMEROCA4" maxlength="20" title="Número" value=""/>											
																	</div>
																	<div class="col-sm-2">
																		<label>Número</label>
																		<input type="text" class="form-control" name="vHNUMEROCA5" id="vHNUMEROCA6" maxlength="20" title="Número" value=""/>											
																	</div>	
																</div>
																<div class="form-group row divContrato">												
																	<div class="col-sm-2">
																		<label>Número
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input type="text" class="form-control" name="vHCTRCONTRATO" id="vHCTRCONTRATO" maxlength="20" title="Número Contrato" value=""/>											
																	</div>
																	<div class="col-md-2">
																		<label>Data Assinatura
																			<small class="text-danger font-13">*</small>
																		</label>
																		<input class="form-control" title="Data Assinatura" name="vHCTRDATAINICIO" id="vHCTRDATAINICIO" value="" type="date" >
																	</div>
																	<div class="col-md-2">
																		<label>Data Término</label>
																		<input class="form-control " title="Data Término" name="vHCTRDATAFINAL" id="vHCTRDATAFINAL" value="" type="date" >
																	</div>	
																</div>												
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- End Aba Dados Gerais -->								
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalContasReceberxParcelasVinculo">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Parcelas</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body" id="modal_div_ContasReceberxParcelasVinculo">
						<div class="row">
							<input type="hidden" id="hdn_pai_ContasReceberxParcelasVinculo" name="hdn_pai_ContasReceberxParcelasVinculo" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_ContasReceberxParcelasVinculo" name="hdn_filho_ContasReceberxParcelasVinculo" value="">								
							<div class="col-md-6">
								<label>Data Vencimento
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Vencimento" name="vDCHGDATA" id="vDCHGDATA" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data Pagamento</label>
								<input class="form-control" title="Data Pagamento" name="vDCHGDATA" id="vDCHGDATA" value="" type="date" >
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>Valor Parcela</label>
								<input class="form-control divObrigatorio classmonetario" name="vMUXRSALARIOATUAL" title="Valor Parcela" id="vMUXRSALARIOATUAL"  type="text">
							</div>
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
							<div class="col-md-6">   
								<label>Comissão
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio" title="Comissão">
									<option value="">  -------------  </option>
									<?php 									
									foreach (comboTabelas('HISTORICO GERAL - STATUS') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>                                                    
							</div>
							<div class="col-md-6">   
								<label>Protesto
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio" title="Protesto">
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
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalContasReceberxParcelasVinculo('modal_div_ContasReceberxParcelasVinculo','transactionContasReceberxParcelasVinculo.php', 'div_ContasReceberxParcelasVinculo', 'ContasReceberxParcelasVinculo', '<?= $vIOid;?>');">Salvar</button>
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

		<?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/dropify/js/dropify.min.js"></script>        
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadContasReceber.js"></script>

    </body>
</html>