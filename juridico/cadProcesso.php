<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2010);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
    <body onload="gerarGridJSON('transactionUsuariosxAcessos.php', 'div_acessos', 'UsuariosxAcessos', '<?= $vIOid;?>');
				 exibirCidades('<?= $vRENDERECO['ESTCODIGO']; ?>', '<?= $vRENDERECO['CIDCODIGO']; ?>', 'div_cidade', 'vHCIDCODIGO');">
	<?php } else { ?>
	<body>
	<?php } ?>

        <?php include_once '../includes/menu.php' ?>

        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <!-- Page-Title -->
                    <?php include_once '../includes/breadcrumb.php' ?>

					<div class="row">
							<div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                   <!-- <h4 class="mt-0 header-title">Cadastro de <?= $vAConfiguracaoTela['MENTITULO'];?></h4>-->

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">

                                    <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
                                    <input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
                                    <input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>

                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
                                        <?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">												
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '2010');">GED</a>
										</li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#fases" role="tab" onclick="gerarGridJSON('transactionProcessoxFases.php', 'div_ProcessoxFases', 'ProcessoxFases', '<?= $vIOid;?>');">Fases</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>Número Processo
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Número Processo" name="vSPRCNROPROCESSO" id="vSPRCNROPROCESSO" type="text" value="<?= $vROBJETO['PRCNROPROCESSO'];?>">
												</div>	
											</div>											
											<div class="form-group row">
												<div class="col-md-6">
													<div id="divJustica"></div>
												</div>
												<div class="col-md-6">
													<div id="divTipoAcao"></div>                                                    
												</div>
											</div>											
                                            <div class="form-group row">
                                                <div class="col-md-6">
													<label>Autor
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Autor" name="vSPRCAUTOR" id="vSPRCAUTOR" type="text" value="<?= $vROBJETO['PRCAUTOR'];?>">
												</div>
												<div class="col-sm-4">
                                                    <label>Empresa
														<small class="text-danger font-13">*</small>
													</label>
                                                    <select title="Empresa" id="vIPRCEMPRESA" class="custom-select obrigatorio" name="vIPRCEMPRESA">
														<option value="">  -------------  </option>
                                                        <?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
                                                            <option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['PRCEMPRESA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
												</div>
                                                <div class="col-sm-4">
                                                    <label>Réu
														<small class="text-danger font-13">*</small>
													</label>
                                                    <select title="Réu" id="vIPRCREU" class="custom-select obrigatorio" name="vIPRCREU">
														<option value="">  -------------  </option>
                                                        <?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
                                                            <option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['PRCREU'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-4">
													<div id="divVaras"></div>                                                     
												</div>
												<div class="col-md-4">
													<div id="divForo"></div>                                                    
												</div>
												<div class="col-md-4">
													<div id="divComarcas"></div>  
												</div>	
											</div>
											<div class="form-group row">
												<div class="col-md-4">
                                                    <label>Responsável
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vIPRCRESPONSAVEL" id="vIPRCRESPONSAVEL" class="custom-select obrigatorio" title="Responsável">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['PRCRESPONSAVEL'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-4">
													<div id="divFase"></div>                                                     
												</div>
												<div class="col-md-4">
													<div id="divGrupo"></div>                                                     
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-4">
                                                    <label>Valor da Causa
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control classmonetario obrigatorio" title="Valor da Causa" name="vMPRCVALORDACAUSA" id="vMPRCVALORDACAUSA" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['PRCVALORDACAUSA'], false); }?>" type="text" >
												</div>
												<div class="col-md-4">
													<label>Data da Distribuição
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Data da Distribuição" name="vDPRCDATADISTRIBUICAO" id="vDPRCDATADISTRIBUICAO" value="<?= $vROBJETO['PRCDATADISTRIBUICAO'];  ?>" type="date" >													
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Síntese do Processo</label>
													<textarea class="form-control" id="vSPRCSINTESE" name="vSPRCSINTESE" rows="3"><?= $vROBJETO['PRCSINTESE']; ?></textarea>
												</div>	
												<div class="col-md-6">
													<label>Observação</label>
													<textarea class="form-control" id="vSPRCOBSERVACAO" name="vSPRCOBSERVACAO" rows="3"><?= $vROBJETO['PRCOBSERVACAO']; ?></textarea>
												</div>	
											</div>
											<div class="form-group row">
												<div class="col-md-3">
													<label>Cadastro (Status)</label>
													<select class="custom-select" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO']?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
													</select>
												</div>
											</div>
                                        </div>
	                                  			
										<?php if($vIOid > 0){ ?>

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
                                       
										<!--Aba Dados Remuneração -->
										<div class="tab-pane p-3" id="fases" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_ProcessoxFases" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Remuneração end -->										
										<?php } ?>
										<div class="form-group row">
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
		
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalProcessoxFases">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Fases</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_ProcessoxFases">
							<input type="hidden" id="hdn_pai_ProcessoxFases" name="hdn_pai_ProcessoxFases" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_ProcessoxFases" name="hdn_filho_ProcessoxFases" value="">
							<div class="form-group row">
								<div class="col-md-6">
									<label>Data
										<small class="text-danger font-13">*</small>
									</label>
									<input class="form-control divObrigatorio" title="Data" name="vDPXFDATA" id="vDPXFDATA" value="" type="date" >
								</div>
								<div class="col-md-6">                                                      
									<label>Responsável</label>
									<select name="vIAGERESPONSAVEL" id="vIAGERESPONSAVEL" class="custom-select divObrigatorio" title="Responsável">
										<option value="">  -------------  </option>
										<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
											<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($_SESSION['SI_USUCODIGO'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6">
									<label>Tipo de Fase</label>
									<input class="form-control" name="vIPXFTIPOFASE" title="Tipo de Fase" id="vIPXFTIPOFASE"  type="text">
								</div>
							</div>	
							<div class="form-group row">
								<div class="col-md-2">        
									<label>Prazo</label>
									<select class="custom-select" name="vSPXFPRAZO" id="vSPXFPRAZO" title="Prazo">									
										<option value="N" selected>Não</option> 
										<option value="S">Sim</option>									
									</select>
								</div>
								<div class="col-md-2">        
									<label>Alarme</label>
									<select class="custom-select" name="vSPXFALARME" id="vSPXFALARME">
										<option value="S">Sim</option>
										<option value="N">Não</option>
									</select>
								</div>
								<div class="col-md-2">        
									<label>Visível</label>
									<select class="custom-select" name="vSPXFVISIVEL" id="vSPXFVISIVEL">
										<option value="N">Não</option>
										<option value="S">Sim</option>													
									</select>
								</div>
								<div class="col-md-2">        
									<label>Pendente</label>
									<select class="custom-select" name="vSPXFPENDENTE" id="vSPXFPENDENTE">
										<option value="N">Não</option>
										<option value="S">Sim</option>													
									</select>
								</div>
							</div>
							<div class="form-group row">	
								<div class="col-md-12">
									<label>Descrição</label>
									<input class="form-control divObrigatorio" name="vSPXFDESCRICAO" title="Descrição" id="vSPXFDESCRICAO" type="text">
								</div>
							</div>	
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalProcessoxFases('modal_div_ProcessoxFases','transactionProcessoxFases.php', 'div_ProcessoxFases', 'ProcessoxFases', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
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

        <!-- Sweet-Alert  -->
        <script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="../assets/pages/jquery.sweet-alert.init.js"></script>

        <script src="../assets/plugins/dropify/js/dropify.min.js"></script>
        <script src="../assets/pages/jquery.profile.init.js"></script>

        <script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
        <script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
        <script src="../assets/pages/jquery.gallery.inity.js"></script>
        <script src="../assets/plugins/select2/select2.min.js"></script>
        
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Usuarios js -->
		<script src="js/cadProcesso.js"></script>				
		<script>
			//Justiça
			var vAParameters =
			{
				 'vSTitulo': 'Justiça',
				 'vSTabTipo': 'PROCESSOS - JUSTICA',
				 'vSCampo': 'vIPRCJUSTICA',
				 'vIValor': '<?php echo $vROBJETO['PRCJUSTICA']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divJustica',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Tipo de Ação
			var vAParameters =
			{
				 'vSTitulo': 'Tipo de Ação',
				 'vSTabTipo': 'PROCESSOS - TIPODEACAO',
				 'vSCampo': 'vIPRCTIPODEACAO',
				 'vIValor': '<?php echo $vROBJETO['PRCTIPODEACAO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divTipoAcao',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);		
			//Varas
			var vAParameters =
			{
				 'vSTitulo': 'Varas',
				 'vSTabTipo': 'PROCESSOS - VARAS',
				 'vSCampo': 'vIPRCVARA',
				 'vIValor': '<?php echo $vROBJETO['PRCVARA']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divVaras',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);	
			//Foro
			var vAParameters =
			{
				 'vSTitulo': 'Foro',
				 'vSTabTipo': 'PROCESSOS - FORO',
				 'vSCampo': 'vIPRCFORO',
				 'vIValor': '<?php echo $vROBJETO['PRCFORO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divForo',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);			
			//Comarcas
			var vAParameters =
			{
				 'vSTitulo': 'Comarcas',
				 'vSTabTipo': 'PROCESSOS - COMARCAS',
				 'vSCampo': 'vIPRCCOMARCA', 
				 'vIValor': '<?php echo $vROBJETO['PRCCOMARCA']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divComarcas',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Fase
			var vAParameters =
			{
				 'vSTitulo': 'Fase',
				 'vSTabTipo': 'PROCESSOS - FASE',
				 'vSCampo': 'vIPRCFASE', 
				 'vIValor': '<?php echo $vROBJETO['PRCFASE']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divFase',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Grupo
			var vAParameters =
			{
				 'vSTitulo': 'Grupo',
				 'vSTabTipo': 'PROCESSOS - GRUPOS',
				 'vSCampo': 'vIPRCGRUPO', 
				 'vIValor': '<?php echo $vROBJETO['PRCGRUPO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divGrupo',
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