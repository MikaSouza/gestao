<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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

									<?php
									$vAConfiguracaoTela['FILTROS'] = $_POST;
									if ($vAConfiguracaoTela['FILTROS']['vSUSUSITUACAO'] == '')
										$vAConfiguracaoTela['FILTROS']['vSUSUSITUACAO'] = 'A';									
									$vAConfiguracaoTela['BTN_FILTROS'] = 'S';									
									$vAConfig['vATitulos'] = array('Nome', 'Departamento', 'Departamento', 'Data Nascimento', 'Data Admissão', 'Data Demissão', 'Ativo');
									$vAConfig['vACampos'] = array('USUNOME', 'EMPRESA', 'DEPARTAMENTO', 'USUDATA_NASCIMENTO', 'USUDATAADMISSAO', 'USUDATADEMISSAO', 'USUSTATUS');
									$vAConfig['vATipos'] = array('varchar','varchar', 'varchar', 'date', 'date', 'date', 'simNao');
									include_once __DIR__.'/../twcore/teraware/componentes/gridPadrao.php'; ?>

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
							<div class="col-md-12">                                                      
								<label>Nome</label>
								<input class="form-control" name="vSUSUNOME" id="vSUSUNOME" type="text" value="" title="Nome" >
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
								<label>Data Admissão Entre: INÍCIO</label>
								<input class="form-control" name="vDDataAdmissaoInicio" title="Data Admissão" id="vDDataAdmissaoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataAdmissaoFim" title="Data Fim" id="vDDataAdmissaoFim"  type="date" maxlength="10">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data Demissão Entre: INÍCIO</label>
								<input class="form-control" name="vDDataDemissaoInicio" title="Data Demissão" id="vDDataDemissaoInicio" type="date" maxlength="10">
							</div>
							<div class="col-md-6">
								<label>FINAL</label>
								<input class="form-control" name="vDDataDemissaoFim" title="Data Fim" id="vDDataDemissaoFim"  type="date" maxlength="10">
							</div>
						</div>
						<div class="form-froup row">
                            <div class="col-md-6">  
								<label>Departamento/Setor</label>
								<select name="vITABDEPARTAMENTO" id="vITABDEPARTAMENTO" class="custom-select" title="Departamento/Setor">
									<option value></option>
									<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>	
							<div class="col-md-6">
								<label>Cargo</label>
								<select name="vITABPLANOCONTAS" id="vITABPLANOCONTAS" class="custom-select" title="Cargo">
									<option value></option>
									<?php foreach (comboTabelas('USUARIOS - CARGOS') as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
                        </div></br>
						<div class="form-group row">
							<div class="col-md-6">
								<label>Situação</label>
								<select name="vSUSUSITUACAO" id="vSUSUSITUACAO" class="custom-select" title="Situação">
									<option value="T">Todos</option>
									<option selected="selected" value="A">Admitidos</option>
									<option value="D">Demitidos</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Filial</label>
								<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select" title="Filial">
									<option value></option>
									<?php foreach (comboEmpresaUsuaria('') as $tabelas): ?>
										<option value="<?php echo $tabelas['EMPCODIGO']; ?>"><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
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