<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2028);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
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
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ajuda" role="tab" >Ajuda</a>
                                        </li>										                                     
                                    </ul>
                                    <!-- Nav tabs end -->
									
									<!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
																				
											<div class="form-group row">
												<div class="col-md-6">                                                      
													<label>Título
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSPOPNOME" id="vSPOPNOME" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['POPNOME'] : ''); ?>" title="Título" >
												</div>											
											</div>										
											<div class="form-group row">
												<div class="col-md-12">                                                      
													<label>Descrição
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSPOPDESCRICAO" name="vSPOPDESCRICAO" title="Descrição"><?= nl2br($vROBJETO['POPDESCRICAO']); ?></textarea>
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
											
										</div>
                                    
										<!-- Aba Ajuda -->
										<div class="tab-pane p-3" id="ajuda" role="tabpanel">
											<p><b>Os par&acirc;metros aceitos para as Posições est&atilde;o definidos abaixo, bem como a sua aplica&ccedil;&atilde;o:</b></p>
											<b>[codigo]</b> = n&uacute;mero do atendimento; <br />
											<b>[cliente]</b> = nome fantasia do cliente;<br />
											<b>[endereco]</b> = endere&ccedil;o para o atendimento;<br />
											<b>[contato]</b> = usu&aacute;rio que solicitou o atendimento;<br />
											<b>[produto]</b> = produto/servi&ccedil;o no qual ser&aacute; realizado o atendimento;<br />
											<b>[tipo_atendimento]</b> = tipo de atendimento (garantia, normal);<br />
											<b>[origem]</b> = origem do atendimento (telefone, e-mail, sistema);<br />
											<b>[categoria]</b> = categoria do atendimento;<br />
											<b>[assunto]</b> = assunto do atendimento;<br />
											<b>[descricao]</b> = descri&ccedil;&atilde;o do atendimento;<br />
											<b>[data_abertura]</b> = data de abertura do atendimento;<br />
											<b>[previsao_conclusao]</b> = previs&atilde;o de conclus&atilde;o do atendimento;<br />
											<b>[status]</b> = status do atendimento (aberto, em andamento ou fechado);<br />
											<b>[atendente]</b> = atendente que realizar&aacute; o atendimento;<br />
											<b>[empresa]</b> = empresa do atendente;<br />
											<b>[now]</b> = data e hora atual (utiliza a data e hora do momento);<br />
										</div>
										<!-- Aba Ajuda end -->
										
										<div class="form-group">
											<label class="form-check-label is-invalid" for="invalidCheck3" style="color: red">
												Campos em vermelho são de preenchimento obrigatório!
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>
									</div>	
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

		<!-- Sweet-Alert  -->
        <script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="../assets/pages/jquery.sweet-alert.init.js"></script>
		
		<!--Wysiwig js-->
        <script src="../assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="../assets/pages/jquery.form-editor.init.js"></script> 

         <?php include_once '../includes/scripts_footer.php' ?>
		<script src="js/cadPosicoesPadroes.js"></script>
    </body>
</html>