<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1952);
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
    <body><?php include_once '../includes/menu.php' ?>

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
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>
										<div class="form-group row">
											<div class="col-md-6">
												<label>Código Auxiliar</label>
												<input type="text" class="form-control" name="vSPROCODIGOAUX" id="vSPROCODIGOAUX" maxlength="200" title="Código Auxiliar" value="<?= $vROBJETO['PROCODIGOAUX']; ?>"/>
											</div>
											<div class="col-md-6">
												<label>Nome
													<small class="text-danger font-13">*</small>
												</label>
												<input type="text" class="form-control obrigatorio" name="vSPRONOME" id="vSPRONOME" maxlength="250" title="Nome" value="<?= $vROBJETO['PRONOME']; ?>"/>
											</div>											
										</div>	
										<div class="form-group row">	
											<div class="col-md-6">
												<label>Unidade
													<small class="text-danger font-13">*</small>
												</label>
												<select name="vIPROUNIDADE" id="vIPROUNIDADE" class="custom-select obrigatorio" title="Unidade">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('PRODUTOS - UNIDADE') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['PROUNIDADE'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>                                                    
											</div>
											<div class="col-md-6">
												<label>Grupo 1
													<small class="text-danger font-13">*</small>
												</label>
												<select name="vIPROGRUPO1" id="vIPROGRUPO1" class="custom-select obrigatorio" title="Grupo 1">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('PRODUTOS - GRUPO 1') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['PROGRUPO1'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>                                                                                                    
											</div>
										</div>
										<div class="form-group row">	
											<div class="col-md-6">
												<label>Grupo 2</label>
												<select name="vIPROGRUPO2" id="vIPROGRUPO2" class="custom-select" title="Grupo 2">
													<option value="">  -------------  </option>
													<?php foreach (comboTabelas('PRODUTOS - GRUPO 2') as $tabelas): ?>                                                            
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['PROGRUPO2'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>                                                                                                    
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-12">
												<label>Descrição</label>
												<textarea class="form-control" id="vSPRODESCRICAO" name="vSPRODESCRICAO" rows="3"><?= $vROBJETO['PRODESCRICAO']; ?></textarea>											
											</div>
										</div>
										<div class="accordion" id="reformaSim">
											<div class="card border mb-0 shadow-none">
												<div class="card-header p-0" id="headingOne">
													<h5 class="my-1">
														<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
														Estoque
														</button>
													</h5>
												</div>
												<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
													<div class="card-body">										
														<div class="form-group row">	
															<div class="col-md-4">
																<label>Quantidade Atual</label>
																<input type="number" class="form-control" name="vIPROQTDEATUAL" id="vIPROQTDEATUAL" maxlength="10" title="Quantidade Atual" value="<?= $vROBJETO['PROQTDEATUAL']; ?>"/>
															</div>
															<div class="col-md-4">
																<label>Quantidade Mínima</label>
																<input type="number" class="form-control" name="vIPROQTDEMINIMA" id="vIPROQTDEMINIMA" maxlength="10" title="Quantidade Mínima" value="<?= $vROBJETO['PROQTDEMINIMA']; ?>"/>
															</div>
															<div class="col-md-4">
																<label>Quantidade Máxima</label>
																<input type="number" class="form-control" name="vIPROQTDEMAXIMA" id="vIPROQTDEMAXIMA" maxlength="10" title="Quantidade Máxima" value="<?= $vROBJETO['PROQTDEMAXIMA']; ?>"/>
															</div>
														</div>
														<div class="form-group row">	
															<div class="col-md-4">
																<label>Valor Unitário Custo</label>
																<input type="text" class="form-control classmonetario" name="vMPROVALORUNITARIOCUSTO" id="vMPROVALORUNITARIOCUSTO" maxlength="10" title="% DESVIO DESPESA" value="<?= formatar_moeda($vROBJETO['PROVALORUNITARIOCUSTO'], false); ?>"/>
															</div>
															<div class="col-md-4">
																<label>Valor Unitário Venda</label>
																<input type="text" class="form-control classmonetario" name="vMPROVALORUNITARIO" id="vMPROVALORUNITARIO" maxlength="10" title="% MÉDIA PROJETADA" value="<?= formatar_moeda($vROBJETO['PROVALORUNITARIO'], false); ?>"/>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-2">
												<label>CADASTRO (STATUS)</label>
												<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
													<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
													<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
												</select>
											</div>
											<div class="col-md-2">
												<label>ID</label>
												<input type="text" class="form-control" name="vH<?= $vAConfiguracaoTela['MENPREFIXO'];?>" id="vH<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" title="ID" value="<?= adicionarCaracterLeft($vROBJETO['PROCODIGO'], 6); ?>" readonly/>												
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

    </body>
</html>