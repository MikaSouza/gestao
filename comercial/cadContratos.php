<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1949);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboProdutosxServicos.php';
include_once __DIR__ . '/../rh/combos/comboUsuarios.php';

if (!isset($vIOid))
	$vROBJETO['CTRNROCONTRATO'] = proxima_Sequencial('CONTRATOS', 'N');

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
		<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="../assets/css/stylesUpload.css"/> 
		
    </head>
	<?php if ($vIOid > 0){ ?>
    <body onload="exibirClientexConsultor('vICTRVENDEDOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', '');">
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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">
                                        <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<input type="hidden" name="vHMENCODIGO" id="vHMENCODIGO" value="<?= $vAConfiguracaoTela['MENCODIGO']; ?>"/>
										
                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
										<?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '1949');">Digitalizações/Arquivos</a>                                            
                                        </li>
                                        <?php } ?>                                        
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">	
											<div class="form-group row">
												<div class="col-md-5">                                                      
													<label>Cliente
														<small class="text-danger font-13">*</small>
													</label>												
													<input title="Cliente" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio autocomplete" data-hidden="#vICLICODIGO" value="<?php echo $vROBJETO['CLIENTE']; ?>" <?= ativoSimNao($result['CLISTATUS']);?> onblur="validarCliente();"/>
													<span id="aviso-cliente" style="color: red;font-size: 11px; display: none;">O Cliente não foi selecionado corretamente!</span>
													<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value="<?php if(isset($vIOid)) echo $vROBJETO['CLICODIGO']; ?>"/>
												</div>
												<div class="col-md-1 btnLimparCliente">
													<br/>				  
													<button type="button" class="btn btn-danger waves-effect" onclick="removerCliente();">Limpar</button><br>
												</div>		
												<div class="col-md-6">
													<label>Representante</label>
													<select name="vICTRVENDEDOR" id="vICTRVENDEDOR" class="custom-select" title="Representante">
														<option value=""> ------------- </option>
														<?php foreach (comboUsuarios() as $usuarios) : ?>
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['CTRVENDEDOR'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>
												</div> 	
											</div>	
											<div class="form-group row">
												<div class="col-md-2">
                                                    <label>Data de Início</label>
                                                    <input class="form-control obrigatorio" title="Data de Início" name="vDCTRDATAAINICIO" id="vDCTRDATAAINICIO" value="<?= $vROBJETO['CTRDATAAINICIO'];  ?>" type="date" >
                                                </div>
												<div class="col-md-2">
                                                    <label>Término da Vigência</label>
                                                    <input class="form-control obrigatorio" title="Término da Vigência" name="vDCTRDATATERMINO" id="vDCTRDATATERMINO" value="<?= $vROBJETO['CTRDATATERMINO'];  ?>" type="date" >
                                                </div>		
												<div class="col-md-2">	
													<label>Número Contrato</label>
													<input class="form-control" title="Número Contrato" readonly name="vSCTRNROCONTRATO" id="vSCTRNROCONTRATO" value="<?php echo $vROBJETO['CTRNROCONTRATO']; ?>" type="text" >
												</div>		
											</div>											
											<div class="form-group row">	
												<div class="col-md-6"> 
													<label>Produto/Serviço</label>
													<select name="vHPXSCODIGO[]" id="vHPXSCODIGO" title="Produto/Serviço" class="form-control obrigatorio" style="width: 100%;font-size: 13px;" multiple>														
														<?php foreach (comboProdutosxServicos() as $tabelas) :
															if ($contArray > 0) { ?>
																<option value="<?php echo $tabelas['PXSCODIGO']; ?>" <?php if (in_array($tabelas['PXSCODIGO'], $arrayPreMold)) echo "selected='selected'"; ?>><?php echo $tabelas['PXSNOME']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $tabelas['PXSCODIGO']; ?>"><?php echo $tabelas['PXSNOME']; ?></option>
														<?php }
														endforeach; ?>
													</select>
												</div>												
												<div class="col-sm-3">
													<div id="divPosicao"></div>													
												</div>														
											</div>	
											<div class="form-group row">
												<div class="col-md-12">                                                      
													<label>Observações
														<small class="text-danger font-13"></small>
													</label>
													<textarea class="form-control" id="vSCTRDESCRICAO" name="vSCTRDESCRICAO" title="Descrição"><?= nl2br($vROBJETO['CTRDESCRICAO']); ?></textarea>
												</div>
											</div>
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Informações Faturamento
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">									
															<div class="form-group row">
																<div class="col-sm-4">
																	<div id="divFormaCobranca"></div>													
																</div>	
																<div class="col-md-3">	
																	<label>Mensalidade Inicial
																		<small class="text-danger font-13">*</small>
																	</label>
																	<input class="form-control classmonetario obrigatorio" title="Mensalidade Inicial" name="vMCTRMENSALIDADEINICIAL" id="vMCTRMENSALIDADEINICIAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRMENSALIDADEINICIAL'], false); }?>" type="text" >
																</div>
																<div class="col-md-3">	
																	<label>Mensalidade Atual
																		<small class="text-danger font-13">*</small>
																	</label>
																	<input class="form-control classmonetario obrigatorio" title="Mensalidade Atual" name="vMCTRMENSALIDADEATUAL" id="vMCTRMENSALIDADEATUAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRMENSALIDADEATUAL'], false); }?>" type="text" >
																</div>
															</div>
														</div>
													</div>
												</div>		
											</div>											                                            
										</div>
                                    													
										<!-- Aba Dados GED -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div class="form-group">
												<div class="area-upload">
													<label for="upload-file" class="label-upload">
														<i class="fas fa-cloud-upload-alt"></i>
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta área <br/>
														Formatos permitidos (PDF, Word/Doc e Excel)
														<p>Os arquivos devem ser salvos nas seguintes formatações: Sem acentuação e sem espaços.</p>
														<p>No seguinte formato: meu-arquivo-deleitura.pdf ou meu_arquivo_de_leitura.pdf</p>
														</div>
													</label>
													<input type="file" accept="*" id="upload-file" multiple/>

													<div class="lista-uploads">
													</div>
												</div>
											</div>											
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
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
		<script src="../assets/plugins/select2/select2.min.js"></script>
		
        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Cad Empresa js -->
		<script src="js/cadContratos.js"></script>
		<script>
			//Posição
			var vAParameters =
			{
				 'vSTitulo': 'Posição',
				 'vSTabTipo': 'CONTRATOS - POSICAO',
				 'vSCampo': 'vICTRPOSICAO',
				 'vIValor': '<?php echo $vROBJETO['CTRPOSICAO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divPosicao',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Forma de Cobrança
			var vAParameters =
			{
				 'vSTitulo': 'Forma de Cobrança',
				 'vSTabTipo': 'CONTAS A RECEBER - FORMA DE COBRANCA',
				 'vSCampo': 'vICTRFORMACOBRANCA',
				 'vIValor': '<?php echo $vROBJETO['CTRFORMACOBRANCA']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divFormaCobranca',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			
			<?php if($vIOid == 0){ ?>
			$(function(){
				$(".btnLimparCliente").hide();
			});	
			<?php } ?>
		</script>	
		<script src="js/scriptUpload.js"></script>
		<script>
			$(document).ready(function() {
				$('#vHPXSCODIGO').select2();
				$("#vHPXSCODIGO").addClass("obrigatorio");
				$("#vHPXSCODIGO").addClass("form-control");
				$("#vHPXSCODIGO").addClass("custom-select");
				$("#vHPXSCODIGO").select2({
					height: '38px !important'
				});
			});
		</script>
    </body>
</html>
