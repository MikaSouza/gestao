<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1879);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN']; 
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
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

									<?php 
									$vAConfiguracaoTela['FILTROS'] = $_POST;
									if ($_POST['vIAGERESPONSAVEL'] == '')
										$vAConfiguracaoTela['FILTROS']['vIAGERESPONSAVEL'] = $_SESSION['SI_USUCODIGO'];
									else 
										$vAConfiguracaoTela['FILTROS']['vIAGERESPONSAVEL'] = $_POST['vIAGERESPONSAVEL'];
									$vAConfiguracaoTela['BTN_FILTROS'] = 'S';
									$vAConfig['vATitulos'] = array('Data Atividade', 'Hora', 'Contrato', 'Cliente', 'Contato', 'Tipo de Atividade', 'Respons??vel', 'Agendou', 'Descri????o');
									$vAConfig['vACampos'] = array('AGEDATAINICIO', 'HORAS', 'CTRNROCONTRATO', 'CLINOME', 'CONNOME', 'ATINOME', 'RESPONSAVEL', 'AGENDOU', 'AGEDESCRICAO');
									$vAConfig['vATipos'] = array('datetime', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar', 'varchar');

									include_once __DIR__.'/../twcore/teraware/componentes/gridPadrao.php'; ?>

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
							<span aria-hidden="true">??</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formPesquisar" id="formPesquisar">
						<div class="form-group row">
							<div class="col-md-4">                                                        
								<label>Contrato</label>
								<input class="form-control" name="vSCTRCONTRATO" id="vSCTRCONTRATO" type="text" title="Contrato" value="" >
							</div> 
							<div class="col-md-8">                                                      
								<label>Nome Cliente</label>
								<input class="form-control" name="vSCLINOME" id="vSCLINOME" type="text" value="" title="NOME CLIENTE" >
							</div>							
						</div>	
						<div class="form-group row">
							<div class="col-md-6">                                                        
								<label>CNPJ/CPF</label>
								<input class="form-control" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ" value="" >
							</div> 
							<div class="col-md-6">                                                      
								<label>Respons??vel</label>
								<select name="vIAGERESPONSAVEL" id="vIAGERESPONSAVEL" class="custom-select" title="Respons??vel">
									<option value="">  -------------  </option>
									<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
										<option value="<?php echo $usuarios['USUCODIGO']; ?>"><?php echo $usuarios['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select>
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
								<label>Data Atividade Entre: In??cio</label>
								<input class="form-control" name="vDDataInicio" title="Data In??cio" id="vDDataInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>Final</label>
								<input class="form-control" name="vDDataFim" title="Data Fim" id="vDDataFim"  type="date" maxlength="10">
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
        <!-- Bot??es de Exemplos -->
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
		<script>
			$(document).ready(function() {
				$.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss'); //Formata????o com Hora
				$.fn.dataTable.moment('DD/MM/YYYY'); //Formata????o sem Hora

				var table = $('#datatable-buttons').dataTable({
					"iDisplayLength": 500,
					"lengthChange": false,
					"responsive": false,
					"buttons": ["copy", "excel", "pdf", "colvis"],
					// Campo ordenado por padr??o (ao carregar p??gina).
					// O 1 ?? a coluna a ser ordenada lembrando que come??a com 0
					"order": [
						[1, "desc"]
					]
				});

				table
					.buttons()
					.container()
					.appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");
			});
		</script>
    </body>
</html>