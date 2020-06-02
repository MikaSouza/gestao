<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1939);
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

                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label>Pasta</label>
                                                <input class="form-control is-invalid obrigatorio" title="Pasta" name="vICIDCODIGOIBGE" id="vICIDCODIGOIBGE" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CIDCODIGOIBGE']; }?>" required>
                                            </div>                                        
                                            <div class="col-sm-3">
                                                <label>Processo</label>
                                                <input class="form-control is-invalid obrigatorio" title="Processo" name="vICIDCODIGOIBGE" id="vICIDCODIGOIBGE" type="text" value="<?php if(isset($vIOid)){ echo $vROBJETO['CIDCODIGOIBGE']; }?>" required>
                                            </div>        
											<div class="col-sm-3">	
												<label>Data Lançamento
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" title="Data Lançamento" name="vDCLIDATA_NASCIMENTO" id="vDCLIDATA_NASCIMENTO" value="<?= $vROBJETO['CLIDATA_NASCIMENTO'];  ?>" type="date" >
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
											<div class="col-md-3">
												<label>Órgão
													<small class="text-danger font-13">*</small>
												</label>
												<select name="vICLISITUACAORECEITA" id="vICLISITUACAORECEITA" class="custom-select obrigatorio" title="Situação Receita Federal">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLISITUACAORECEITA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>                                                    
											</div>
											<div class="col-md-3">
												<label>Motivo Cancelamento
													<small class="text-danger font-13">*</small>
												</label>
												<select name="vICLISITUACAORECEITA" id="vICLISITUACAORECEITA" class="custom-select obrigatorio" title="Situação Receita Federal">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLISITUACAORECEITA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>                                                    
											</div>
                                        </div>
										<div class="form-group row">
											<div class="col-md-12">
												<label>Observaões</label>
												<textarea class="form-control" id="vSCTRDESCRICAO" name="vSCTRDESCRICAO" rows="3"><?= $vROBJETO['CTRDESCRICAO']; ?></textarea>
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

		<!-- Sweet-Alert  -->
        <script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script src="../assets/pages/jquery.sweet-alert.init.js"></script>

        <?php include_once '../includes/scripts_footer.php' ?>

    </body>
</html>