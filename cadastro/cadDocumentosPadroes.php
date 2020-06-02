<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1989);
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
														<input class="form-control obrigatorio" name="vSDOPNOME" id="vSDOPNOME" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['DOPNOME'] : ''); ?>" title="Título" >
													</div>
												</div>
												<div class="form-group row">
													<div class="col-md-12">                                                      
														<label>Descrição
															<small class="text-danger font-13">*</small>
														</label>
														<textarea class="form-control" id="vSDOPDESCRICAO" name="vSDOPDESCRICAO" title="Descrição"><?= $vROBJETO['DOPDESCRICAO']; ?></textarea>
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
												<p><b>Os par&acirc;metros aceitos para os Contratos Padr&otilde;es est&atilde;o definidos abaixo, bem como a sua aplica&ccedil;&atilde;o:</b></p>
												<b>[cliente]</b> = raz&atilde;o social do cliente;<br />
												<b>[cliente_cpf_cnpj]</b> = cpf ou cnpj do cliente;<br />
												<b>[cliente_endereco]</b> = endere&ccedil;o do cliente;<br />
												<b>[cliente_telefone]</b> = telefone do cliente;<br />
												<b>[cliente_email]</b> = e-mail do cliente;<br />
												<b>[tipo_contrato]</b> = tipo de contrato; <br />
												<b>[numero_contrato]</b> = n&uacute;mero do contrato; <br />
												<b>[vig_data_inicio]</b> = vig&ecirc;ncia do contrato - data inicial;<br />
												<b>[vig_data_fim]</b> = vig&ecirc;ncia do contrato - data final;<br />
												<b>[vig_data_cancelamento]</b> = vig&ecirc;ncia do contrato - data cancelamento;<br />
												<b>[contrato_motivo_cancelamento]</b> = exibe o motivo do cancelamento do contrato;<br />
												<b>[contrato_cancelamento_multa_base]</b> = valor da multa de cancelamento do contrato em porcentagem;<br />

												<b>[indice_reajuste]</b> = &iacute;ndice de reajuste;<br />
												<b>[vendedor]</b> = vendedor do contrato;<br />
												<b>[horas_cobertura]</b> = horas cobertas pelo contrato para atendimento;<br />
												<b>[dia_faturamento]</b> = dia do faturamento mensal;<br />
												<b>[valor_cobertura]</b> = valor de cobertura para faturamento mensal;<br />
												<b>[descricao_contrato]</b> = descri&ccedil;&atilde;o do contrato;<br />
																						
												<b>[faturamento_recebimento]</b> = lista os dados de pagamento em uma tabela;<br />
												<b>[data_hora_atual]</b> = data e hora atual (DD/MM/AAAA HH:MM:SS);<br />
												<b>[data_dia_atual_n]</b> = exibe o dia atual em formato num&eacute;rico;<br />
												<b>[data_mes_atual_n]</b> = exibe o m&ecirc;s atual em formato num&eacute;rico;<br />
												<b>[data_mes_atual_s]</b> = exibe o m&ecirc;s atual escrito por extenso;<br />
												<b>[data_ano_atual_n]</b> = exibe o ano atual em formato num&eacute;rico;<br />
												<b>[contratada_assinatura]</b> = encarregado pela assinatura do contrato (contratada);<br />
												<b>[contratante_assinatura]</b> = encarregado pela assinatura do contrato (contratante);<br />
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
		<script src="js/cadDocumentosPadroes.js"></script>
    </body>
</html>