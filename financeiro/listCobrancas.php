<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1984);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once 'combos/comboContasBancarias.php';
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

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <?php include_once '../includes/breadcrumb.php' ?>

                    <div class="row">
                        <div class="col-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
																
									<a href="cadContasPagar.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
									<div class="table-responsive dash-social" style="overflow:hidden">
										<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
											<thead>
												<tr>             
													<th width="50px">Marcar</th>
													<th>Credor</th>
													<th>Centro de Custo</th>
													<th>Plano de Contas</th>                    
													<th>Forma de Cobrança</th>
													<th>Data Vencimento</th>
													<th>Valor a Receber</th>
													<th>Data Pagamento</th>
													<th>Valor Recebido</th>
													<th>Número Documento</th>
													<th>Descrição</th>
													<th>Data Duplicata</th>
													<th>Data Inclusão</th>
													<th>Ações</th>
												</tr>
											</thead> 
											<tbody>
											<?php          
												$result = listContasPagar($_POST);
												$vITotalRegistros =  $result['quantidadeRegistros'];
												foreach ($result['dados'] as $result1) :
													$i++; ?>
													<tr>
														<?php
														 if ($result1['CTRDATAPAGAMENTO'] == '') { ?>
															<td align="center"><input type='checkbox' title='ckPadrao' name='vEnviarDiario[]' value ='<?= $result1['CTRCODIGO'];?>' id='vEnviarDiario[]' /></td>
														<?php }else{ ?>
															<td ></td>
														<?php } ?>                        
														<td ><?= adicionarCaracterLeft($result1['CTRCODIGO'], 6);?></td>
														<td align="left"><?= $result1['CLINOMEFANTASIA'];?></td>
														<td align="left"><?= $result1['CENTROCUSTO'];?></td>                        
														<td align="left"><?= $result1['FORMACOBRANCA'];?></td>
														<td align="center"><?= formatar_data($result1['CTRDATAVENCIMENTO']);?></td>
														<td align="center"><?= formatar_data($result1['CTRVALORARECEBER']);?></td>
														<td align="right"><?= formatar_moeda($result1['CTRVALORARECEBER'], false);?></td>
														<td align="center"><?= formatar_data($result1['CTRDATAPAGAMENTO']);?></td>
														<td align="right"><?= formatar_moeda($result1['CTRVALORRECEBIDO'], false);?></td>
														<td align="left"><?= $result1['CTRNRODOCUMENTO'];?></td>
														<td align="left"><?= $result1['CTRDESCRICAO'];?></td>
														<td align="left"><?= $result1['CTRDATADUPLICATA'];?></td>
														<td align="left"><?= $result1['CTRDATA_INC'];?></td>
														<td align="center">
															<a href="cadContasPagar.php?oid=<?= $result1['CTRCODIGO'];?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
															<a href="#" onclick="excluirRegistroGrid('<?= $result1['CTRCODIGO'];?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN'];?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
														</td>						
													</tr>
												<?php endforeach;  ?>
											</tbody>	
										</table>
									</div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div><!-- container -->
            </div>
            <!-- end page content -->

			<?php include_once '../includes/footer.php' ?>
        </div>
        <!-- end page-wrapper -->
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalDiario">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Diário de Cobrança</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formDiario" id="formDiario">
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Contato</label>
								<input class="form-control divObrigatorio" name="vDCTRDATACONTATO" title="Data Contato" id="vDCTRDATACONTATO" type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12">
								<label>Descrição</label>
								<textarea class="form-control divObrigatorio" id="vSCTRHISTORICO" title="Descrição" name="vSCTRHISTORICO" rows="3"></textarea>
							</div>
						</div>
						<input type="hidden" id="hdn_metodo_search" name="hdn_metodo_search" value="liquidarContasPagar">
						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="submit" title="Salvar Dados" class="btn btn-primary waves-effect waves-light fa-pull-right">Salvar Dados</button>
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
		<script src="js/listCobrancas.js"></script>

    </body>
</html>