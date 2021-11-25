<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2032);
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
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>										
										
										<!-- Nav tabs -->
										<ul  class="nav nav-tabs" role="tablist">
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
											</li>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link" data-toggle="tab" href="#contatos" role="tab">Ajuda</a>
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
														<input class="form-control obrigatorio" name="vSAVPNOME" id="vSAVPNOME" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['AVPNOME'] : ''); ?>" title="Título" >
													</div>
												</div>
												<div class="form-group row">
													<div class="col-md-12">                                                      
														<label>Descrição
															<small class="text-danger font-13">*</small>
														</label>
														<textarea class="form-control" id="vSAVPDESCRICAO" name="vSAVPDESCRICAO" title="Descrição"><?= $vROBJETO['AVPDESCRICAO']; ?></textarea>
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
																						
											<div class="tab-pane p-3" id="contatos" role="tabpanel">
												<p><b>Os par&acirc;metros aceitos para os Avisos Padr&otilde;es est&atilde;o definidos abaixo, bem como a sua aplica&ccedil;&atilde;o:</b></p>
												<b>[radio]</b> = nome da rádio;<br />
												<b>[radio_cpf_cnpj]</b> = cpf ou cnpj do rádio;<br />
												<b>[radio_endereco]</b> = endere&ccedil;o do rádio;<br />
												<b>[radio_telefone]</b> = telefone do rádio;<br />		
												<b>[radio_email]</b> = email do rádio;<br />		
																						
												<b>[data_hora_atual]</b> = data e hora atual (DD/MM/AAAA HH:MM:SS);<br />
												<b>[data_dia_atual_n]</b> = exibe o dia atual em formato num&eacute;rico;<br />
												<b>[data_mes_atual_n]</b> = exibe o m&ecirc;s atual em formato num&eacute;rico;<br />
												<b>[data_mes_atual_s]</b> = exibe o m&ecirc;s atual escrito por extenso;<br />
												<b>[data_ano_atual_n]</b> = exibe o ano atual em formato num&eacute;rico;<br />
												
												<b>[nome_responsavel]</b> = nome do responsável;<br />	
											</div>
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
		<script src="js/cadAvisosPadroes.js"></script>
    </body>
</html>