<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1879);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboEstados.php'; 
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
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

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
											<div class="col-md-2">
												<label>Data</label>
												<input class="form-control obrigatorio" name="vSAGEDATAINICIO" title="Data" id="vSAGEDATAINICIO" type="date" maxlength="10" value="<?= (isset($vIOid) ? $vROBJETO['AGEDATAINICIO'] : date('Y-m-d'));?>">
											</div>
											<div class="col-md-2">
												<label>Hora Início
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" title="Hora Início" name="vSAGEHORAINICIO" id="vSAGEHORAINICIO" value="<?= $vROBJETO['AGEHORAINICIO']; ?>" type="time">
											</div>
											<div class="col-md-2">
												<label>Hora Final
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" title="Hora Final" name="vSAGEHORAFINAL" id="vSAGEHORAFINAL" value="<?= $vROBJETO['AGEHORAFINAL']; ?>" type="time">
											</div>
											<div class="col-md-6">
												<label>Responsável
												<small class="text-danger font-13">*</small>
												</label>
												<select name="vIAGERESPONSAVEL" id="vIAGERESPONSAVEL" class="custom-select divObrigatorio" title="Responsável">
													<option value="">  -------------  </option>
													<?php foreach (comboUsuarios() as $usuarios): ?>
														<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($_SESSION['SI_USUCODIGO'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-3">
												<label>Estado</label>
												<select title="Estado" id="vHESTCODIGO" class="custom-select" name="vHESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade', 'vHCIDCODIGO');">
													<?php foreach (comboEstados() as $tabelas) : ?>
														<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ((isset($vRENDERECO['ESTCODIGO']) ? $vRENDERECO['ESTCODIGO'] : '') == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-md-3">
												<div id="div_cidade"></div>
											</div>
											<div class="col-md-3">
												<label>Concluído</label>
												<select class="custom-select" name="vSAGECONCLUIDO" id="vSAGECONCLUIDO" title="Concluído">
													<option value="N" <?php if ($vROBJETO['AGECONCLUIDO'] == "N") echo "selected='selected'"; ?>>Não</option>
													<option value="S" <?php if ($vROBJETO['AGECONCLUIDO'] == "S") echo "selected='selected'"; ?>>Sim</option>
												</select>
											</div>
											<div class="col-sm-3">
												<label>Enviar E-mail</label>
												<select class="form-control" name="vSAGEENVIAREMAIL" id="vSAGEENVIAREMAIL">
													<option value="S" <?php if ($vROBJETO['AGEENVIAREMAIL'] == "S") echo "selected='selected'"; ?>>Sim</option>
													<option value="N" <?php if ($vROBJETO['AGEENVIAREMAIL'] == "N") echo "selected='selected'"; ?>>Não</option>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-12"> 
												<label>Descrição/Especificação
													<small class="text-danger font-13">*</small>
												</label>
												<textarea class="form-control obrigatorio" rows="4" id="vSAGEDESCRICAO" name="vSAGEDESCRICAO" title="Descrição/Especificação"><?= $vROBJETO['AGEDESCRICAO']; ?></textarea>
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

		<!--Wysiwig js-->
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="../assets/pages/jquery.form-editor.init.js"></script>
		<script src="js/cadAgenda.js"></script>
    </body>
</html>