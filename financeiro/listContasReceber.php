<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1971);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once '../sistema/combos/comboEmpresaUsuaria.php';
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
									
									<a href="cadContasReceber.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
									<div class="table-responsive dash-social" style="overflow:auto">
										<?php 
										$_POST['vSPosicaoFiltro'] = 'A';
										$_POST['vSStatusFiltro'] = 'S';
										$result = listContasReceber($_POST);
										$vITotalRegistros =  $result['quantidadeRegistros'];
										if ($_POST['vSTipoPesquisa'] == 'S') { ?>
									
										<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
											<thead>
												<tr>
													<th width="50px">Marcar</th>
													<th>Número Lançamento</th>
													<th>Filial</th>
													<th>Data</th>                    
													<th>Recibo/Nota</th>
													<th>Valor a Receber</th>
													<th>Sigla/Cliente</th>
													<th>Consultor</th>
													<th>Procedimento</th>
													<th>Ações</th>					
												</tr>
											</thead> 
											<tbody>
											<?php												
												foreach ($result['dados'] as $result1) :
													$i++; ?>
													<tr>     
														<td align="center"><input type='checkbox' title='ckPadrao' name='vEnviarNFSe[]' value ='<?= $result1['CTRCODIGO'];?>' id='vEnviarNFSe[]' /></td>
														<td ><?= adicionarCaracterLeft($result1['CTRCODIGO'], 6);?></td>
														<td align="left"><?= $result1['EMPNOMEFANTASIA'];?></td>
														<td align="center"><?= formatar_data($result1['CTRDATA_INC']);?></td>                        
														<td align="left"><?= $result1['CTRNRODOCUMENTO'];?></td>
														<td align="right"><?= formatar_moeda($result1['CTRVALORARECEBER'], false);?></td>
														<td align="left"><?= $result1['CLINOME'];?></td>
														<td align="left"><?= $result1['CONSULTOR'];?></td>
														<td align="left"><?= $result1['CENTROCUSTO'];?></td>
														<td align="center">
															<a href="cadContasReceber.php?oid=<?= $result1['CTRCODIGO'];?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
															<a href="#" onclick="excluirRegistroGrid('<?= $result1['CTRCODIGO'];?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN'];?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>														
															<button type="button" class="btn btn-secondary waves-effect" onclick="abrirModalDocumentos('<?= $result1['CTRCODIGO'];?>');">Recibo/Nota</button>
														</td>						
													</tr>
												<?php endforeach;  ?>											
											</tbody>	
										</table>
										<?php } else { ?>
										<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
											<thead>
												<tr>
													<th width="50px">Marcar</th>
													<th>Número Lançamento</th>
													<th>Filial</th>
													<th>Data</th>                    
													<th>Recibo/Nota</th>
													<th>Valor a Receber</th>
													<th>Sigla/Cliente</th>
													<th>Consultor</th>
													<th>Procedimento</th>
													<th>Ações</th>					
												</tr>
											</thead> 
											<tbody>
											<?php												
												foreach ($result['dados'] as $result1) :
													$i++; ?>
													<tr>     
														<td align="center"><input type='checkbox' title='ckPadrao' name='vEnviarNFSe[]' value ='<?= $result1['CTRCODIGO'];?>' id='vEnviarNFSe[]' /></td>
														<td ><?= adicionarCaracterLeft($result1['CTRCODIGO'], 6);?></td>
														<td align="left"><?= $result1['EMPNOMEFANTASIA'];?></td>
														<td align="center"><?= formatar_data($result1['CTRDATA_INC']);?></td>                        
														<td align="left"><?= $result1['CTRNRODOCUMENTO'];?></td>
														<td align="right"><?= formatar_moeda($result1['CTRVALORARECEBER'], false);?></td>
														<td align="left"><?= $result1['CLINOME'];?></td>
														<td align="left"><?= $result1['CONSULTOR'];?></td>
														<td align="left"><?= $result1['CENTROCUSTO'];?></td>
														<td align="center">
															<a href="cadContasReceber.php?oid=<?= $result1['CTRCODIGO'];?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
															<a href="#" onclick="excluirRegistroGrid('<?= $result1['CTRCODIGO'];?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN'];?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>														
															<button type="button" class="btn btn-secondary waves-effect" onclick="abrirModalDocumentos('<?= $result1['CTRCODIGO'];?>');">Recibo/Nota</button>
														</td>						
													</tr>
												<?php endforeach;  ?>											
											</tbody>	
										</table>										
										<?php }  ?>
									</div>
								</div>
								<button type="button" style="width:150px" title="Gerar NFSe" onclick="faturarNFSe();" class="btn btn-primary waves-effect waves-light">Gerar NFSe</button>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

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
							<div class="col-md-4">
								<label>Tipo Pesquisa</label>
								<select class="form-control" id="vSTipoPesquisa" name="vSTipoPesquisa">
									<option value="A">Analítico</option>
									<option value="S">Sintético</option>																		
								</select>
							</div>
							<div class="col-md-4">
								<label>Posição</label>
								<select class="form-control" id="vSStatusFiltro" name="vSStatusFiltro">
									<option value="A">Aguardando Faturamento</option>
									<option value="R">Aguardando Remessa</option>
									<option value="F">Faturados</option>
								</select>
							</div>
							<div class="col-md-12">
								<label>Filial</label>
								<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select" title="Filial">
									<option value></option>
									<?php foreach (comboEmpresaUsuaria('N') as $tabelas): ?>
										<option value="<?php echo $tabelas['EMPCODIGO']; ?>"><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<div id="divContasBancarias"></div>
							</div>
						</div>	
						<div class="form-group row">
                            <div class="col-md-6">                                                        
								<label>Sigla do Cliente</label>
								<input class="form-control" name="vICLISEQUENCIAL" id="vICLISEQUENCIAL" type="text" title="Sigla do Cliente">
                            </div>
                            <div class="col-md-6">                                                      
								<label>Nome do Cliente</label>
								<input class="form-control" name="vSCLINOME" id="vSCLINOME" type="text" title="Nome do Cliente" >
                            </div>
						</div>	
						<div class="form-group row">
                            <div class="col-md-6">                                                        
								<label>CPF/CNPJ</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CPF/CNPJ">
                            </div>
							<div class="col-md-6">                                                      
								<label>Número do Recibo / Nota</label>
								<input class="form-control classnumerico" name="vICTRNRODOCUMENTO" id="vICTRNRODOCUMENTO" type="text" title="Número do Recibo / Nota">
							</div>
						</div>	
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Cadastro Entre: Início</label>
								<input class="form-control" name="vDDataInicio" title="Data Início" id="vDDataInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>Final</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim"  type="date" maxlength="10">
							</div>
                        </div>
                        <div class="form-group row">
							<div class="col-md-6">
								<label>Data Pagamento Entre</label>
								<input class="form-control" name="vDDataInicio" title="Data Início" id="vDDataInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>Data Vencimento Entre</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim"  type="date" maxlength="10">
							</div>
                        </div>
                        <div class="form-froup row">
                            <div class="col-md-6">                                                      
								<label>Número de Lançamento</label>
								<input class="form-control" name="vSCLINOME" id="vICLINOME" type="text" title="Número de Lançamento">
                            </div>
                            <div class="col-md-6">                                                      
								<label>Representante</label>
								<select name="vICLIRESPONSAVEL" id="vICLIRESPONSAVEL" class="custom-select" title="Representante">
									<option value="">  -------------  </option>
									<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
										<option value="<?php echo $usuarios['USUCODIGO']; ?>"><?php echo $usuarios['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>	
						<div class="form-group row">	
                            <div class="col-md-6">                                                      
								<label>Número do Contrato / CA</label>
								<input class="form-control" name="vSCLINOME" id="vICLINOME" type="text" title="Número do Contrato / CA">
                            </div>
                            <div class="col-md-6">                                                      
								<label>Nosso Número</label>
								<input class="form-control" name="vSCTRNOSSONUMERO" id="vICTRNOSSONUMERO" type="text" title="Nosso Número">
							</div>
                        </div>
						<div class="form-group row">
							<div class="col-md-4">
								<label>Ativo</label>
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
								<option value="N">Nota Fiscal</option>								
								<option value="R">Recibo Impresso</option>
								<option value="O">Recibo Online</option>	
							</select>
						</div>	
						
						<input type="hidden" id="hdn_metodo_search" name="hdn_metodo_search" value="liquidarContasPagar">
						<input type="hidden" id="vICXPCODIGO" name="vICXPCODIGO" value="">
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="button" title="Gerar" class="btn btn-primary waves-effect waves-light fa-pull-right" onclick="salvarModalDocumentos();">Gerar</button>
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
		<script src="js/listContasReceber.js"></script>

    </body>
</html>