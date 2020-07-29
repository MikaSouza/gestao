<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2025);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/combos/comboPosicoesPadroes.php';
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
									
									<a href="cadAtendimentos.php?method=insert" id="btnIncluir">
									<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
									</a>
									<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
									<div class="table-responsive dash-social" style="overflow:auto">
									<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
										<thead>
											<tr>											
												<th>Número</th>
												<th>Posição Atual</th>                    
												<th>Categoria</th>
												<th>Prioridade</th>
												<th>Cliente</th>
												<th>Produto/Serviço</th>
												<th>Assunto</th>
												<th>Atendente</th>
												<th>Abertura</th>
												<th>Tempo Previsto</th>
												<th>Conclusão</th>
												<th>Ações</th>					
											</tr>
										</thead> 
										<tbody>
										<?php          
																					
											$_POST['vSStatusFiltro'] = 'S';
											//$_POST['vSPosicaoFiltro'] = '1';		 		
											$_POST['vSPosicao'] = 'ABERTO';			
											$result = listAtendimentos($_POST);
											$vITotalRegistros =  $result['quantidadeRegistros'];
											foreach ($result['dados'] as $result1) :
												$i++; ?>
												<tr> 													
													<td align="left"><?= adicionarCaracterLeft($result1['ATESEQUENCIAL'], 5);?></td>
													<td align="left">
														<div class="marcadorCelulaGrid" style="border-left:8px solid <?php echo '#'.$result1['POSICAO_COR'];?>;">
															<?php echo $result1["POSICAO_ATUAL"]; ?>
														</div>
													</td>	 
													<td align="left"><?= $result1['CATEGORIA'];?></td>
													<td align="left"><?= $result1['PRIORIDADE_NOME'];?></td>
													<td align="left"><?= $result1['CLINOMEFANTASIA'];?></td>
													<td align="left"><?= $result1['PRODUTO'];?></td>
													<td align="left"><?= $result1['ATEASSUNTO'];?></td>
													<td align="left"><?= $result1['ATENDENTE'];?></td>
													<td align="center"><?= formatar_data_hora($result1['ATEDATA_INC']);?></td>
													<td align="center"><?= formatar_data_hora($result1['ATEDATA_INC']);?></td>
													<td align="center"><?= formatar_data_hora($result1['ATEDATACONCLUSAO']);?></td>
													<td align="center">
														<a href="cadAtendimentos.php?oid=<?= $result1['ATECODIGO'];?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro"><i class="fas fa-edit text-info font-16"></i></a>
														<a href="#" onclick="excluirRegistroGrid('<?= $result1['ATECODIGO'];?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN'];?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO'];?>')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
													</td>						
												</tr>
											<?php endforeach;  ?>
										</tbody>	
									</table>
									</div>
								</div>
								<tr>							  
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
							<div class="col-md-6">                                                      
								<label>Sigla</label>
								<input class="form-control classnumerico" name="vICLISEQUENCIAL" id="vICLISEQUENCIAL" type="text" value="" title="SIGLA" >
							</div> 
							<div class="col-md-6">                                                      
								<label>Nome Cliente</label>
								<input class="form-control" name="vSCLINOME" id="vSCLINOME" type="text" value="" title="NOME CLIENTE" >
							</div>							
						</div>	
						<div class="form-group row">
							<div class="col-md-6">                                                        
								<label>CNPJ/CPF</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ" value="" >
							</div> 
						</div>	
						<div class="form-group row">
							<div class="col-md-6">                                                        
								<label>Contato</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ" value="" >
							</div> 
							<div class="col-md-6">                                                      
								<label>E-mail</label>
								<input class="form-control" name="vSCLIEMAIL" id="vSCLIEMAIL" type="text" value="" title="NOME CLIENTE" >
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
								<label>Posição</label>
								<select name="vSPosicaoFiltro" id="vSPosicaoFiltro" class="custom-select" title="Posição">
									<option value="">  -------------  </option>
									<?php foreach (comboPosicoesPadroes() as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['POPCODIGO']; ?>"><?php echo $tabelas['POPNOME']; ?></option>
									<?php endforeach; ?>
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

    </body>
</html>