<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1988);
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
												<label>Departamento/Setor</label>
												<select title="Departamento/Setor" id="vIHELSETOR" class="custom-select" name="vIHELSETOR">
													<option value=""></option>
													<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
														<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['HELSETOR'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
													<?php endforeach; ?>
												</select>	
											</div>	
										</div>
										<div class="form-group row">
											<div class="col-md-6">                                                      
												<label>Título
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" name="vSHELTITULO" id="vSHELTITULO" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['HELTITULO'] : ''); ?>" title="Título" >
											</div> 
										</div>										
										<div class="form-group row">
											<div class="col-md-12">                                                      
												<label>Link
													<small class="text-danger font-13">*</small>
												</label>
												<input class="form-control obrigatorio" name="vSHELLINK" id="vSHELLINK" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['HELLINK'] : ''); ?>" title="Link" >
											</div> 
										</div>
										<?php if ($vROBJETO['HELLINK'] != '') { ?>
										<div class="form-group row">
											<div class="col-md-12">                                                      
												<video src="<?= $vROBJETO['HELLINK'];?>" width="640" height="480" controls>
													Desculpa, o seu navegador não suporta vídeos incorporados,
													mas você pode <a href="../ged/videos/1.mp4">baixá-lo</a>
													e assistir pelo seu reprodutor de mídia favorito!
												</video>
											</div> 
										</div>		
										<?php } ?>	
										<div class="form-group row">
											<div class="col-md-2">
												<label>Cadastro (Status)</label>
												<select class="custom-select" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
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
		
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
		<script src="js/cadSolicitacaoSuprimentos.js"></script>

    </body>
</html>