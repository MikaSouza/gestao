<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1949);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
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
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    </head>
    <?php if ($vIOid > 0){ ?>
    <body onload="exibirClientexConsultor('vICTRVENDEDOR', '<?= $vROBJETO['CLICODIGO']; ?>', '', '');">
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
											<div class="col-md-4">
												<label>Tipo Lançamento
													<small class="text-danger font-13">*</small>
												</label>
												<select name="vICTRTIPOCONTRATO" id="vICTRTIPOCONTRATO" class="custom-select obrigatorio" title="Tipo Lançamento">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('CONTAS A RECEBER - CENTRO DE CUSTO') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CTRTIPOCONTRATO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-md-2">
												<label>Dia Vencimento</label>
												<input type="text" class="form-control classnumerico" name="vICTRDIAFATURAMENTO" id="vICTRDIAFATURAMENTO" maxlength="10" title="Dia Vencimento" value="<?= $vROBJETO['CTRDIAFATURAMENTO']; ?>"/>
											</div>											
										</div>	
										<div class="form-group row">	
											<div class="col-md-2">
												<label>Data Início
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" title="Data Assinatura" name="vDCTRDATAAINICIO" id="vDCTRDATAAINICIO" value="<?= $vROBJETO['CTRDATAAINICIO'];  ?>" type="date" >
											</div>
											<div class="col-md-2">
												<label>Data Término
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" title="Data Assinatura" name="vDCTRDATATERMINO" id="vDCTRDATATERMINO" value="<?= $vROBJETO['CTRDATATERMINO'];  ?>" type="date" >
											</div>
											<div class="col-md-2">
												<label>Parcelas</label>
												<input type="text" class="form-control classnumerico" name="vICTRPRAZO" id="vICTRPRAZO" maxlength="10" title="Dia Vencimento" value="<?= $vROBJETO['CTRPRAZO']; ?>"/>
											</div>
										</div>
										<div class="form-group row">	
											<div class="col-md-4">
												<label>Valor das Parcelas</label>
												<input type="text" class="form-control classmonetario" name="vMCTRVALORPARCELA" id="vMCTRVALORPARCELA" maxlength="10" title="Valor das Parcelas" value="<?= formatar_moeda($vROBJETO['CTRVALORPARCELA'], false); ?>"/>
											</div>											
										</div>
										<div class="form-group row">
											<div class="col-md-12">
												<label>Observações</label>
												<textarea class="form-control" id="vSCTRDESCRICAO" name="vSCTRDESCRICAO" rows="3"><?= $vROBJETO['CTRDESCRICAO']; ?></textarea>
											</div>	
                                        </div>
										<div class="form-group row">
											<div class="col-md-2">
												<label>Status</label>
												<select class="form-control" name="vSCTRPOSICAO" id="vSCTRPOSICAO">
													<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Em Aberto</option>
													<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Cancelado</option>
												</select>
											</div>
											<div class="col-md-2">
												<label>ID</label>
												<input type="text" class="form-control" name="vH<?= $vAConfiguracaoTela['MENPREFIXO'];?>" id="vH<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" title="ID" value="<?= adicionarCaracterLeft($vROBJETO['CTRCODIGO'], 6); ?>" readonly/>												
											</div>
											<div class="col-md-2">
												<label>Data Lançamento/Inclusão</label>
												<input class="form-control" title="Lançamento/Inclusão" name="vDCTRDATA_INC" id="vDCTRDATA_INC" value="<?= formatar_data_hora($vROBJETO['CTRDATA_INC']); ?>" type="text" readonly>
											</div>
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

        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>		

		<?php include_once '../includes/scripts_footer.php' ?>
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadContratos.js"></script>

    </body>
</html>