<?php 
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(77);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../sistema/transaction/transactionTabelas.php'; 
include_once __DIR__.'/../financeiro/transaction/transactionBancos.php';
include_once __DIR__.'/../sistema/combos/comboEmpresaUsuaria.php';
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
                        <div class="col-lg-10 mx-auto">
                            <div class="card">
                                <div class="card-body">                                    
                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">

										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?= $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<div class="form-group row">
											<div class="col-md-6">
												<label>Empresa</label>
                                                <select class=" custom-select obrigatorio" id="vIEMPCODIGO"  name="vIEMPCODIGO"  title="Empresa">
                                                    <?php foreach (comboEmpresaUsuaria() as $tabelas): ?>
                                                    <option value="<?= $tabelas['EMPCODIGO']?>" <?php if ($vROBJETO['EMPCODIGO'] == $tabelas['EMPCODIGO']) echo "selected='selected'"; ?>> <?= $tabelas['EMPNOMEFANTASIA']?></option>
                                                    <?php endforeach; ?>
												</select>
											</div>	
											<div class="col-md-6">
												<label>Tipo
													<small class="text-danger font-13">*</small>
												</label>
												<select title="Tipo" id="vICBATIPO" class="custom-select obrigatorio" name="vICBATIPO">
													<?php foreach (comboTabelas('CONTAS BANCARIAS - TIPOS') as $tabelas): ?>
														<option value="<?php echo $tabelas['TABCODIGO']?>" <?php if ($vROBJETO['CBATIPO'] == $tabelas['TABCODIGO']) echo "selected"; ?>><?= $tabelas['TABDESCRICAO']?></option>
													<?php endforeach; ?>
												</select>												
											</div>
											<div class="col-md-6">
												<label>Banco</label>
												<select class=" custom-select obrigatorio" id="vIBACCODIGO"  name="vIBACCODIGO"  title="Banco">
													<option value="">Selecione</option>
													<?php foreach (comboBancos() as $bancos): ?>
													<option value="<?php echo $bancos['BACCODIGO']?>"> <?= $bancos['BACCODIGOBACEN']."-".$bancos['BACBANCO'] ?></option>
													<?php endforeach; ?>
												</select>												
											</div>
											<div class="col-md-6">
												<label>Agência com dígito</label>
												<input type="text" class="form-control" name="vSCBAAGENCIA" id="vSCBAAGENCIA" maxlength="20" title="Agência" value="<?= $vROBJETO['CBAAGENCIA']; ?>"/>
											</div>
											<div class="col-md-6">
												<label>Conta Corrente com dígito</label>
												<input type="text" class="form-control" name="vSCBACONTA" id="vSCBACONTA" maxlength="20" title="Conta" value="<?= $vROBJETO['CBACONTA']; ?>"/>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-3">
												<label>Padrão Despesas</label>
												<select class="form-control" name="vSCBAPADRAODESPESAS" id="vSCBAPADRAODESPESAS">
													<option value="S" <?php if ($vROBJETO['CBAPADRAODESPESAS'] == "S") echo "selected='selected'"; ?>>Sim</option>
													<option value="N" <?php if ($vROBJETO['CBAPADRAODESPESAS'] == "N") echo "selected='selected'"; ?>>Não</option>
												</select>
											</div>	
											<div class="col-md-3">
												<label>Padrão Receitas</label>
												<select class="form-control" name="vSCBAPADRAORECEITAS" id="vSCBAPADRAORECEITAS">
													<option value="S" <?php if ($vROBJETO['CBAPADRAORECEITAS'] == "S") echo "selected='selected'"; ?>>Sim</option>
													<option value="N" <?php if ($vROBJETO['CBAPADRAORECEITAS'] == "N") echo "selected='selected'"; ?>>Não</option>
												</select>
											</div>	
											<div class="col-sm-4">
												<label>Saldo Real</label>
												<input class="form-control classmonetario" name="vMUSUSALARIOINICIAL" id="vMUSUSALARIOINICIAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['USUSALARIOINICIAL'], false); }?>" type="text" >
											</div>
											<div class="col-sm-4">
												<label>Valor Limite</label>
												<input class="form-control classmonetario" name="vMUSUSALARIOINICIAL" id="vMUSUSALARIOINICIAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['USUSALARIOINICIAL'], false); }?>" type="text" >
											</div>
											<div class="col-sm-4">
												<label>Saldo Disponível</label>
												<input class="form-control classmonetario" name="vMUSUSALARIOINICIAL" id="vMUSUSALARIOINICIAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['USUSALARIOINICIAL'], false); }?>" type="text" >
											</div>		
										</div>
										<div class="form-group row">
											<div class="col-md-2">
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

		<?php include_once '../includes/scripts_footer.php' ?>

    </body>
</html>