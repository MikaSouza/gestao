<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1962);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboDocumentosPadroes.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?>

        <!-- DataTables -->
        <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
									<a href="listTriagem.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
									<div class="table-responsive dash-social" style="overflow:hidden">
									<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="height: 430px; overflow: auto; width: 100%">
										<thead>
											<tr>
												<th>Código</th>
												<th>Tipo</th>
												<th>Cliente/Empresa</th>
												<th>Serviço</th>
												<th>Representante</th>				
												<th>Data Inclusão</th>	
												<th>Posição</th>
												<th width="25px">AÇÕES</th>		
												<th></th>	
											</tr>
										</thead>
									<?php $result = listProspeccao($_POST);
									$vITotalRegistros =  $result['quantidadeRegistros'];
									foreach ($result['dados'] as $result) :?>									
										<tr>	
											<td align="right">
												<?php if (($result['CXPTIPO'] == 'S') || ($result['CXPTIPO'] == 'I')){ ?>
													<a href="cadSolicitacaoCRM.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">
												<?php }else{ ?>
													<a href="cadProspeccao.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">
												<?php } ?>
												<?= $result['CXPCODIGO'];?></a></td>
											<td align="left"><?= $result['TIPO'];?></td> 
											<td align="left"><?= $result['CLINOME'];?></td>
											<td align="left"><?= $result['PRODUTO'];?></td>
											<td align="left"><?= $result['ATENDENTE'];?></td>
											<td align="center"><?= formatar_data_hora($result['CXPDATA_INC']);?></td>
											<td align="left"><?= $result['PXCNOME'];?></td> 
											<td align="center">		
											<?php if (($result['CXPTIPO'] == 'S') || ($result['CXPTIPO'] == 'I')){ ?>
												<a href="cadSolicitacaoCRM.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">EDITAR</a>
											<?php }else{ ?>
												<a href="cadProspeccao.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">EDITAR</a>
											<?php } ?>
											<?php if (($result['CXPTIPO'] == 'S') || ($result['CXPTIPO'] == 'I')){ ?>
												<a href="cadSolicitacaoCRM.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">
											<?php }else{ ?>
												<a href="cadProspeccao.php?method=update&oid=<?= $result['CXPCODIGO'];?>" class="mr-2" title="Editar Registro" alt="Editar Registro">
											<?php } ?>
											</a></td>
											<td align="center"><button type="button" class="btn btn-secondary waves-effect" onclick="abrirModalDocumentos('<?= $result['CXPCODIGO'];?>');">Gerar Documentação</button></td>
										</tr>
									<?php endforeach; ?>
									</table>	
									</div>
								
									<form class="form-parsley" action="#" method="post" name="formProspecacao" id="formProspecacao" >
										<input type="hidden" name="hdn_metodo_search" id="hdn_metodo_search" value="searchProspecacao"/>
										<div id="div_prospecacao" class="table-responsive"></div>
									</form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

			<?php include_once '../includes/footer.php' ?>
        </div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Filtros</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formPesquisar" id="formPesquisar">
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Cadastro Entre: INÍCIO</label>
								<input class="form-control" name="vDDataInicio" title="Data Início" id="vDDataInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim"  type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-4">
								<label>Tipo</label>
								<select class="form-control" id="vSTipoFiltro" name="vSTipoFiltro">
									<option value="A">AMBOS</option>
									<option value="I">INDICAÇÃO/SOLICITAÇÃO</option>
									<option value="P">PROSPECÇÃO</option>
								</select>
							</div>
							<div class="col-md-4">
								<label>ATIVO</label>
								<select class="form-control" id="vSStatusFiltro" name="vSStatusFiltro">
									<option value="A">AMBOS</option>
									<option value="N">NÃO</option>
									<option value="S">SIM</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="submit" title="Pesquisar" class="btn btn-primary waves-effect waves-light fa-pull-right">Pesquisar</button>
							</div>
						</div>
					</form>  

					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalDocumentos">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Gerar Documentação</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formModeloDocumento" id="formModeloDocumento">
						<div class="col-md-12">  
							<label>Modelo Documento</label>
							<select name="vIDOPCODIGO" id="vIDOPCODIGO" class="custom-select" title="Modelo Documento">
								<option value></option>
								<?php foreach (comboDocumentosPadroes() as $tabelas): ?>                                                            
									<option value="<?php echo $tabelas['DOPCODIGO']; ?>"><?php echo $tabelas['DOPNOME']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>	
						
						<input type="hidden" id="hdn_metodo_search" name="hdn_metodo_search" value="liquidarContasPagar">
						<input type="hidden" id="vICXPCODIGO" name="vICXPCODIGO" value="">
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="button" title="Salvar Dados" class="btn btn-primary waves-effect waves-light fa-pull-right" onclick="salvarModalDocumentos();">Salvar Dados</button>
							</div>
						</div>
					</form>  

					</div>
				</div>
			</div>
		</div>
        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <!-- Required datatable js -->
        <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Botões de Exemplos -->
        <script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="../assets/plugins/datatables/jszip.min.js"></script>
        <script src="../assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="../assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="../assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="../assets/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="../assets/pages/jquery.datatable.init.js"></script>

        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="js/listProspeccao.js"></script>
    </body>
</html>