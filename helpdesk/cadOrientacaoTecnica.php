<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2026);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];

$metodo = (isset($_GET['method'])) ? $_GET['method'] : "insert";
$fileObrigatorio = ($_GET['method'] == 'update') ? '' : "obrigatorio";

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
	<link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
	<?php include_once '../includes/scripts_header.php' ?>
	<!-- App css -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../assets/css/stylesUpload.css" />
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
								<form class="form-parsley" action="#" method="post"
									name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>"
									id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">

									<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO"
										id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO"
										value="<?php echo $vIOid; ?>" />
									<input type="hidden" name="methodPOST" id="methodPOST" value="<?= $metodo ?>" />
									<input type="hidden" name="vHTABELA" id="vHTABELA"
										value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>" />
									<input type="hidden" name="vHPREFIXO" id="vHPREFIXO"
										value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>" />
									<input type="hidden" name="vHURL" id="vHURL"
										value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>" />
									<input type="hidden" name="vHMENCODIGO" id="vHMENCODIGO"
										value="<?= $vAConfiguracaoTela['MENCODIGO']; ?>" />

									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link active" data-toggle="tab" href="#geral" role="tab">Dados
												Gerais</a>
										</li>
										<?php if ($vIOid > 0): ?>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#anexos" role="tab"
												onclick="buscarAnexos(<?= $vIOid; ?>, 2026)">Digitalizações/Arquivos</a>
										</li>
										<?php endif; ?>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active p-3" id="geral" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>Título
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSOXTTITULO"
														id="vSOXTTITULO" type="text"
														value="<?= ($vIOid > 0 ? $vROBJETO['OXTTITULO'] : ''); ?>"
														title="Título">
												</div>
												<div class="col-md-6">
													<?php $vSCaminho = '../ged/orientacao_tecnica/'.$vROBJETO['OXTNUMERO'].'_'.$vROBJETO['OXTANO'].'.pdf';
                                                        if (file_exists($vSCaminho)) {
                                                            ?>
													<br /><a href="<?= $vSCaminho; ?>" target="_blank">
														<button type="button"
															class="btn btn-secondary waves-effect">ABRIR
															ARQUIVO ANEXADO AQUI</button><br></a>
													<?php
                                                        }  ?>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-3">
													<label>Número
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Número"
														name="vIOXTNUMERO" id="vIOXTNUMERO" type="number" maxlength="4"
														value="<?php if (isset($vIOid)) {
                                                            echo $vROBJETO['OXTNUMERO'];
                                                        }?>">
												</div>
												<div class="col-md-3">
													<label>Ano
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" title="Ano" name="vIOXTANO"
														id="vIOXTANO" type="number" maxlength="4" value="<?php if (isset($vIOid)) {
                                                            echo $vROBJETO['OXTANO'];
                                                        } else {
                                                            echo date('Y');
                                                        }?>">
												</div>
												<div class="col-md-6">
													<label>Incluir/Alterar Arquivo
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control <?= $fileObrigatorio ?>" title="Arquivo"
														type="file" name="vHARQUIVO" id="vHARQUIVO">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-12">
													<label>Descrição
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSOXTDESCRICAO"
														name="vSOXTDESCRICAO"
														title="Descrição"><?= $vROBJETO['OXTDESCRICAO']; ?></textarea>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-3">
													<label>Cadastro (Status)</label>
													<select class="form-control"
														name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS"
														id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") {
                                                            echo "selected='selected'";
                                                        } ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") {
                                                            echo "selected='selected'";
                                                        } ?>>Inativo</option>
													</select>
												</div>
											</div>
										</div>
										<div class="tab-pane p-3" id="anexos" role="tabpanel">
											<div class="form-group">
												<div class="area-upload">
													<label for="upload-file" class="label-upload">
														<i class="fas fa-cloud-upload-alt"></i>
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta
															área <br />
															Formatos permitidos (PDF, Word/Doc e Excel)
														</div>
													</label>
													<input type="file" accept="*" id="upload-file" multiple />

													<div class="lista-uploads">
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div id="div_ged" class="table-responsive">
													<table id="datatable-buttons"
														class="table table-striped table-bordered dt-responsive nowrap d-none"
														style="overflow: auto; width: 100%">
														<thead>
															<tr>
																<th>Data Inclusão</th>
																<th>Usuário</th>
																<th>Tipo</th>
																<th>Arquivo</th>
																<th>Link</th>
																<th>Ação</th>
															</tr>
														</thead>
														<tbody id="tbody_ged">
														</tbody>
														<tfoot id="tfoot_ged">
														</tfoot>
													</table>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="form-check-label is-invalid" for="invalidCheck3"
												style="color: red">
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
	<!-- Dropify  -->
	<script src="../assets/plugins/dropify/js/dropify.min.js"></script>
	<script src="../assets/pages/jquery.profile.init.js"></script>
	<script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
	<script src="../assets/pages/jquery.gallery.inity.js"></script>
	<script src="../assets/pages/jquery.form-upload.init.js"></script>
	<!--Wysiwig js-->
	<script src="../assets/plugins/tinymce/tinymce.min.js"></script>
	<script src="../assets/pages/jquery.form-editor.init.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>
	<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="js/cadOrientacaoTecnica.js"></script>
	<script src="js/uploadOrientacaoTecnica.js"></script>
</body>

</html>