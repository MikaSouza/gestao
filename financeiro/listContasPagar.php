<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1983);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
									
									<a href="cadContasPagar.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
									<div class="table-responsive dash-social" style="overflow:auto">
									<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
											<tr>             
												<th width="50px">Marcar</th>
												<th>Código</th>
												<th>Favorecido</th>
												<th>Centro de Custo</th>                    
												<th>Seq.</th>
												<th>Data Vencimento</th>
												<th>Valor a Pagar</th>
												<th>Data Pagamento</th>
												<th>Valor Pago</th>
												<th>Ações</th>					
											</tr>
										</thead> 
										<tbody>
										<?php          
											$_POST['vSPosicaoFiltro'] = 'A';
											$_POST['vSStatusFiltro'] = 'S';
											$result = listContasPagar($_POST);
											$vITotalRegistros =  $result['quantidadeRegistros'];
											foreach ($result['dados'] as $result1) :
												$i++; ?>
												<tr>
													<?php
													 if ($result1['CTPDATAPAGAMENTO'] == '') { ?>
														<td align="center"><input type='checkbox' title='ckPadrao' name='vEnviarLiquidar[]' value ='<?= $result1['CTPCODIGO'];?>' id='vEnviarLiquidar[]' /></td>
													<?php }else{ ?>
														<td ></td>
													<?php } ?>                        
													<td ><?= adicionarCaracterLeft($result1['CTPCODIGO'], 6);?></td>
													<td align="left"><?= $result1['FAVNOMEFANTASIA'];?></td>
													<td align="left"><?= $result1['CENTROCUSTO'];?></td>                        
													<td align="left"><?= $result1['CTPSEQUENCIAL'];?></td>
													<td align="center"><?= formatar_data($result1['CTPDATAVENCIMENTO']);?></td>
													<td align="right"><?= formatar_moeda($result1['CTPVALORAPAGAR'], false);?></td>
													<td align="center"><?= formatar_data($result1['CTPDATAPAGAMENTO']);?></td>
													<td align="right"><?= formatar_moeda($result1['CTPVALORPAGO'], false);?></td>
													<td align="center">
														<a href="cadContasPagar.php?oid=<?= $result1['CTPCODIGO'];?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
														<a href="#" onclick="excluirRegistroGrid('<?= $result1['CTPCODIGO'];?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN'];?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
													</td>						
												</tr>
											<?php endforeach;  ?>
										</tbody>	
									</table>
									</div>
								</div>
								<button type="button" title="Liquidar Contas" style="width:150px" onclick="abrirModalLiquidar();" class="btn btn-primary waves-effect waves-light">Liquidar</button>
                              
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
								<label>Favorecido</label>
								<input class="form-control" name="vSFAVNOME" id="vSFAVNOME" type="text" value="" title="Favorecido" >
							</div>	
							<div class="col-md-6">                                                      
								<label>Descrição</label>
								<input class="form-control" name="vSCTPDESCRICAO" id="vSCTPDESCRICAO" type="text" value="" title="Descrição" >
							</div>	
						</div>	
						<div class="form-group row">
							<div class="col-md-6">                                                        
								<label>CNPJ/CPF</label>
								<input class="form-control" name="vSFAVCNPJ" id="vSFAVCNPJ" type="text" title="CNPJ" value="" >
							</div> 
							<div class="col-md-6">
								<label>Posição</label>
								<select class="form-control" id="vSPosicaoFiltro" name="vSPosicaoFiltro">
									<option value="">-------</option>
									<option value="A" selected>ABERTAS</option>
									<option value="P">PAGAS</option>
									<option value="T">ATRASADAS</option>
								</select>
							</div>
						</div>
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
							<div class="col-md-6">
								<label>Data Vencimento ENTRE</label>
								<input class="form-control" name="vDDataVencimentoInicio" title="Data Vencimento Início" id="vDDataVencimentoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataVencimentoFim" title="Data Vencimento Fim" id="vDDataVencimentoFim"  type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Pagamento ENTRE</label>
								<input class="form-control" name="vDDataPagamentoInicio" title="Data Pagamento Início" id="vDDataPagamentoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataPagamentoFim" title="Data Pagamento Fim" id="vDDataPagamentoFim"  type="date" maxlength="10">
							</div>
						</div>
                        <div class="form-froup row">
                            <div class="col-md-6">  
								<label>Centro de Custo</label>
								<select name="vITABCENTROCUSTO" id="vITABCENTROCUSTO" class="custom-select" title="Centro de Custo">
									<option value></option>
									<?php foreach (comboTabelas('CONTAS A PAGAR - CENTRO DE CUSTO') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABCENTROCUSTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>	
							<div class="col-md-6">
								<label>Plano de Contas</label>
								<select name="vITABPLANOCONTAS" id="vITABPLANOCONTAS" class="custom-select" title="Plano de Contas">
									<option value></option>
									<?php foreach (comboTabelas('CONTAS A PAGAR - PLANO DE CONTAS') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABPLANOCONTAS'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
                        </div>
						<div class="form-group row">
							<div class="col-md-4">
								<label>Ativo</label>
								<select class="form-control" id="vSStatusFiltro" name="vSStatusFiltro">
									<option value="A">AMBOS</option>
									<option value="N">NÃO</option>
									<option value="S" selected>SIM</option>
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalLiquidar">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Liquidar Contas a Pagar</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formLiquidar" id="formLiquidar">
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Pagamento</label>
								<input class="form-control divObrigatorio" name="vDCTPDATAPAGAMENTOLIQ" title="Data Pagamento" id="vDCTPDATAPAGAMENTOLIQ" type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12">
								<label>Forma de Pagamento</label>
								<select name="vITABFORMAPAGAMENTO" id="vITABFORMAPAGAMENTO" class="custom-select divObrigatorio" title="Forma de Pagamento">
									<option value></option>
									<?php foreach (comboTabelas('CONTAS A PAGAR - FORMA DE PAGAMENTO') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>	
						<div class="form-group row">
							<div class="col-md-12">
								<label>Conta Bancária</label>
								<select title="Conta Bancária" id="vICBACODIGO" class="custom-select divObrigatorio" name="vICBACODIGO" >
									<?php $result = comboContasBancarias();
									foreach ($result['dados'] as $result) : ?>
											<option value="<?php echo $result['CBACODIGO']; ?>" ><?php echo $result['CONTA']; ?></option>
									<?php endforeach; ?>
								</select>
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
		<script src="js/listContasPagar.js"></script>
    </body>
</html>