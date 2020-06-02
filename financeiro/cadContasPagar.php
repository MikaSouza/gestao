<?php 
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1983);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../financeiro/transaction/transactionBancos.php';
include_once __DIR__.'/combos/comboCentroCusto.php';
include_once __DIR__.'/../sistema/combos/comboPeriodicidades.php';
include_once '../sistema/combos/comboEmpresaUsuaria.php';
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
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		
    </head>
    <?php if ($vIOid > 0){ ?>
    <body onload="exibirContasBancarias('<?= $vROBJETO['EMPCODIGO']; ?>', '<?= $vROBJETO['CBACODIGO']; ?>', '');">
	<?php } else { ?>
	<body>
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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" onSubmit="return validarForm();">
										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>										
								   								   
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#liquidar" role="tab">Liquidar</a>
                                        </li>     
										<?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../cadastro/transaction/transactionClientesxGED.php', 'div_ged', 'ClientesxGED', '<?= $vIOid;?>', '1983');">Digitalizações/Arquivos</a>
                                        </li>	
										<?php } ?>
                                    </ul>
                                    
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>Filial
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select obrigatorio" title="Filial">
														<option value></option>
                                                       	<?php foreach (comboEmpresaUsuaria('N') as $tabelas): ?>
															<option value="<?php echo $tabelas['EMPCODIGO']; ?>" <?php if ($vROBJETO['EMPCODIGO'] == $tabelas['EMPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-6">
													<label>Conta Bancária Débito</label>
													<select title="Conta Bancária Débito" id="vICBACODIGO" class="custom-select obrigatorio" name="vICBACODIGO" >
														<option value></option>
														<?php $result = comboContasBancarias();
														foreach ($result['dados'] as $result) : ?>
																<option value="<?php echo $result['CBACODIGO']; ?>" <?php if ($vROBJETO['CBACODIGO'] == $result['CBACODIGO']) echo "selected='selected'"; ?>><?php echo $result['CONTA']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>	
											<div class="form-group row">
												<div class="col-md-5">                                                      
													<label>Favorecido
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Favorecido" type="text" name="vHFAVORECIDO" id="vHFAVORECIDO" class="form-control obrigatorio autocomplete" data-hidden="#vIFAVCODIGO" value="<?php echo $vROBJETO['FAVNOMEFANTASIA']; ?>" onblur="validarCliente();"/>
													<span id="aviso-cliente" style="color: red;font-size: 11px; display: none;">O Favorecido não foi selecionado corretamente!</span>
													<input type="hidden" name="vIFAVCODIGO" id="vIFAVCODIGO" value="<?php if(isset($vIOid)) echo $vROBJETO['FAVCODIGO']; ?>"/>
												</div>
												<div class="col-md-1 btnLimparCliente">
													<br/>				  
													<button type="button" class="btn btn-danger waves-effect" onclick="removerCliente();">Limpar</button><br>
												</div>	
												<div class="col-md-3"> 
													<label>Tipo de Conta</label>
													<select class="form-control obrigatorio" title="Tipo de Conta" name="vSCTPTIPOCONTA" id="vSCTPTIPOCONTA">
														<option value="F" <?php if ($vROBJETO['CTPTIPOCONTA'] == "F") echo "selected='selected'"; ?>>Fixa</option>
														<option value="V" <?php if ($vROBJETO['CTPTIPOCONTA'] == "V") echo "selected='selected'"; ?>>Variável</option>
													</select>	
												</div>	
											</div>
                                            <div class="form-group row">      
												<div class="col-md-6">  
													<div id="divCentroCusto"></div>  
												</div>	
												<div class="col-md-6">
													<div id="divPlanoContas"></div>												    
												</div>
                                            </div>
                                            <div class="form-group row">
												<div class="col-md-3">
													<label>Data de Vencimento
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Data de Vencimento" name="vDCTPDATAVENCIMENTO" id="vDCTPDATAVENCIMENTO" value="<?= $vROBJETO['CTPDATAVENCIMENTO'];  ?>" type="date" >													
												</div>	
												<div class="col-md-3">	
													<label>Valor a Pagar
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control classmonetario obrigatorio" title="Valor a Pagar" name="vMCTPVALORAPAGAR" id="vMCTPVALORAPAGAR" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTPVALORAPAGAR'], false); }?>" type="text" >
												</div>
												<div class="col-md-3">
													<div id="divFormaCobranca"></div>													
												</div>	
                                            </div>    
                                            <div class="form-group row">
												<div class="col-md-3">
													<label>Número</label>
													<input type="text" class="form-control" name="vSCTPNRODOCUMENTO" id="vSCTPNRODOCUMENTO" title="Número" value="<?= $vROBJETO['CTPNRODOCUMENTO']; ?>"/>													
												</div>	
												<div class="col-md-3">
													<label>Data de Emissão</label>
													<input class="form-control" title="Data de Vencimento" name="vDCTPDATAEMISSAODOCUMENTO" id="vDCTPDATAEMISSAODOCUMENTO" value="<?= $vROBJETO['CTPDATAEMISSAODOCUMENTO'];  ?>" type="date" >
												</div>	
                                            </div>
                                            <div class="form-group row">
												<div class="col-md-12">
													<label>Descrição</label>
													<textarea class="form-control obrigatorio" title="Descrição" id="vSCTPDESCRICAO" name="vSCTPDESCRICAO" rows="3"><?= $vROBJETO['CTPDESCRICAO']; ?></textarea>													
												</div>	
                                            </div>
											<?php if ($vIOid == ''){ ?>
                                            <div class="form-group row">
												<div class="col-md-4">
													<label>Periocidade</label>                                                
													<select name="vHPERCODIGO" id="vHPERCODIGO" class="custom-select" title="Periocidade">
														<?php foreach (comboPeriodicidades() as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['PERCODIGO']; ?>"><?php echo $tabelas['PERNOME']. ' ( '.$tabelas['PERPERIODOADICIONAL'].' - '.$tabelas['PERPERIODOBASE'].' )'; ?></option>
														<?php endforeach; ?>
													</select>
												</div>	
												<div class="col-md-4">	                                                
													<label>Repetições</label>
													<input class="form-control" type="text" value="1" id="repetir" name="repetir" >
												</div>	
                                            </div>
											<?php } ?>
											<div class="form-group row">
												<div class="col-md-2">
													<label>Cadastro (Status)</label>
													<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
													</select>
												</div>
											</div>
                                            
                                        </div>                                        

                                        <!-- Tab Liquidar -->
                                        <div class="tab-pane p-3" id="liquidar" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-2">
													<label>Desconto</label>
													<input class="form-control classmonetario" title="Desconto" name="vMCTPDESCONTO" id="vMCTPDESCONTO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTPDESCONTO'], false); }?>" type="text" >
												</div>
												<div class="col-md-2">
													<label>Valor Pago</label>
													<input class="form-control classmonetario" title="Valor Pago" name="vMCTPVALORPAGO" id="vMCTPVALORPAGO" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTPVALORPAGO'], false); }?>" type="text" >
												</div>
												<div class="col-md-2">
													<label>Data Pagamento</label>
													<input class="form-control" title="Data Pagamento" name="vDCTPDATAPAGAMENTO" id="vDCTPDATAPAGAMENTO" value="<?= $vROBJETO['CTPDATAPAGAMENTO'];  ?>" type="date" >
												</div>
												<div class="col-md-4">
													<div id="divFormaPagamento"></div>													
												</div>
												<div class="col-md-2">
													<label>Cheque</label>
													<input type="text" class="form-control" name="vSCTPNROCHEQUE" id="vSCTPNROCHEQUE" title="Cheque" value="<?= $vROBJETO['CTPNROCHEQUE']; ?>"/>																										
												</div>
											</div>												
                                        </div>		
											
										<!-- Aba Dados GED -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div id="modal_div_ClientesxGED">
												<div class="form-group row">                                                												
													<div class="col-md-4">   
														<div id="divTipoArquivo"></div>	
													</div>
													<div class="file-field col-md-4">
														<label>Escolher Arquivo
															<small class="text-danger font-13">*</small>
														</label>
														<input type="file" id="fileUpload" name="fileUpload">
												   </div>
												   <div class="col-md-4">
														<br/>				  
														<button type="button" id="btnEnviar" class="btn btn-secondary waves-effect">Salvar Documentos</button><br>
													</div>
												</div>	
											</div>	
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>	
										
                                    </div>
                                    <div class="form-group">
										<label class="form-check-label" for="invalidCheck3" style="color: red">
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
            <?php include_once '../includes/footer.php' ?>
        </div>
		<!-- Modal Tabela Padrão -->
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalTabelaPadrao">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Controles Tabelas</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_tabela_padrao"> 
							<input type="hidden" id="vHTABPARTITULO" name="vHTABPARTITULO" value="">
							<input type="hidden" id="vHTABPARNAME" name="vHTABPARNAME" value="">
							<input type="hidden" id="vHTABPARDIV" name="vHTABPARDIV" value="">
							<input type="hidden" id="vHTABCODIGODIALOG" name="vHTABCODIGODIALOG" value="">
							<input type="hidden" id="vHTABTIPODIALOG" name="vHTABTIPODIALOG" value="">
							<div class="col-md-12">
								<label>Descrição</label>
								<input class="form-control divObrigatorio" name="vHTABDESCRICAODIALOG" title="Descrição" id="vHTABDESCRICAODIALOG" type="text">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary btn-xs waves-effect waves-light fa-pull-right" onclick="salvarModalTabelaPadrao('')">Salvar</button>						
							<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
		<script src="js/cadContasPagar.js"></script>		
		<script>
			//Centro de Custo
			var vAParameters =
			{
				 'vSTitulo': 'Centro de Custo',
				 'vSTabTipo': 'CONTAS A PAGAR - CENTRO DE CUSTO',
				 'vSCampo': 'vITABCENTROCUSTO',
				 'vIValor': '<?php echo $vROBJETO['TABCENTROCUSTO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divCentroCusto',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Plano de Contas
			var vAParameters =
			{
				 'vSTitulo': 'Plano de Contas',
				 'vSTabTipo': 'CONTAS A PAGAR - PLANO DE CONTAS',
				 'vSCampo': 'vITABPLANOCONTAS',
				 'vIValor': '<?php echo $vROBJETO['TABPLANOCONTAS']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divPlanoContas',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);		
			//Forma de Cobrança
			var vAParameters =
			{
				 'vSTitulo': 'Forma de Cobrança',
				 'vSTabTipo': 'CONTAS A PAGAR - FORMA DE COBRANÇA',
				 'vSCampo': 'vITABFORMACOBRANCA',
				 'vIValor': '<?php echo $vROBJETO['TABFORMACOBRANCA']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divFormaCobranca',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);	
			//Forma de Pagamento
			var vAParameters =
			{
				 'vSTitulo': 'Forma de Pagamento',
				 'vSTabTipo': 'CONTAS A PAGAR - FORMA DE PAGAMENTO',
				 'vSCampo': 'vITABFORMAPAGAMENTO',
				 'vIValor': '<?php echo $vROBJETO['TABFORMAPAGAMENTO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divFormaPagamento',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Tipo Arquivo
			var vAParameters =
			{
				 'vSTitulo': 'Tipo Arquivo',
				 'vSTabTipo': 'GED - TIPO',
				 'vSCampo': 'vHGEDTIPO',
				 'vIValor': '',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divTipoArquivo',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
		</script>
    </body>
</html>