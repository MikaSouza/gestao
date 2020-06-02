<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];

function getRepresentantes() {
	$sql = "SELECT						
				COUNT(u.CLIRESPONSAVEL) as TOTAL,
				t.USUNOME
			FROM CLIENTES u
			LEFT JOIN USUARIOS t ON t.USUCODIGO = u.CLIRESPONSAVEL
			WHERE
				u.CLISTATUS = 'S'	AND 
				t.USUDATADEMISSAO IS NULL
			GROUP BY u.CLIRESPONSAVEL
			ORDER BY TOTAL DESC	";			
	$arrayQueryFilho = array(
						'query' => $sql,
						'parametros' => array()
					);
	$lista = consultaComposta($arrayQueryFilho);		
	$retorno = array(); $labels = array(); $series = array();
	foreach ($lista['dados'] as $lista) :
		if(trim($lista['USUNOME']) == "")
			$lista['USUNOME'] = "Não definido";
		$labels[] = $lista['USUNOME'];
		$series[] = (int)$lista['TOTAL'];
	endforeach; 
	return array($series, $labels);
}

function getTipoClientes() {
	$sql = "SELECT						
				COUNT(u.CLITIPOCADASTRO) as TOTAL,
				t.TABDESCRICAO
			FROM CLIENTES u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.CLITIPOCADASTRO
			WHERE
				u.CLISTATUS = 'S'
			GROUP BY u.CLITIPOCADASTRO
					ORDER BY TOTAL DESC ";			
	$arrayQueryFilho = array(
						'query' => $sql,
						'parametros' => array()
					);
	$lista = consultaComposta($arrayQueryFilho);		
	$retorno = array(); $labels = array(); $series = array();
	foreach ($lista['dados'] as $lista) :
		if(trim($lista['TABDESCRICAO']) == "")
			$lista['TABDESCRICAO'] = "Não definido";
		$labels[] = $lista['TABDESCRICAO'];
		$series[] = (int)$lista['TOTAL'];
	endforeach; 
	return array($series, $labels);
}

function getStatusClientes() {
	$sql = "SELECT						
				COUNT(u.CLIPOSICAO) as TOTAL,
				t.TABDESCRICAO
			FROM CLIENTES u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.CLIPOSICAO
			WHERE
				u.CLISTATUS = 'S'
			GROUP BY u.CLIPOSICAO
			ORDER BY TOTAL DESC	";			
	$arrayQueryFilho = array(
						'query' => $sql,
						'parametros' => array()
					);
	$lista = consultaComposta($arrayQueryFilho);		
	$retorno = array(); $labels = array(); $series = array();
	foreach ($lista['dados'] as $lista) :
		if(trim($lista['TABDESCRICAO']) == "")
			$lista['TABDESCRICAO'] = "Não definido";
		$labels[] = $lista['TABDESCRICAO'];
		$series[] = (int)$lista['TOTAL'];
	endforeach; 
	return array($series, $labels);
}

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
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Representantes</h4>
									<div class="">
										<div id="div_representante" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->   		
                        <div class="col-6">                           
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Tipo de Cadastro</h4>
									<div class="">
										<div id="div_tipo" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Status</h4>
									<div class="">
										<div id="div_status " class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
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
		
		<script src="../assets/plugins/moment/moment.js"></script>
        <script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/plugins/apexcharts/irregular-data-series.js"></script>
        <script src="../assets/plugins/apexcharts/ohlc.js"></script>
		<script type="text/javascript" src="../assets/js/graficos.js"></script>

		<?php
			$global_representante = getRepresentantes();
			$vASeriesR = json_encode($global_representante[0]);
			$vALabelsR = json_encode($global_representante[1]);
		
			$global_tipo = getTipoClientes();
			$vASeriesTipo = json_encode($global_tipo[0]);
			$vALabelsTipo = json_encode($global_tipo[1]);
			
			$global_status = getStatusClientes();
			$vASeriesS = json_encode($global_status[0]);
			$vALabelsS = json_encode($global_status[1]);	
			
		?>
		<script type="text/javascript" DEFER="DEFER">
		
			var dataObject = {
				title: 'Representantes',
				width: 480,
				height: 480,
				div_retorno: '#div_representante',
				series: <?= $vASeriesR;?>,
				labels: <?= $vALabelsR;?>
			}
			chartPie( dataObject );
		
			var dataObject = {
				title: 'Tipo de Cadastro',
				width: 480,
				height: 480,
				div_retorno: '#div_tipo',
				series: <?= $vASeriesTipo;?>,
				labels: <?= $vALabelsTipo;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Status',
				width: 480,
				height: 480,
				div_retorno: '#div_status',
				series: <?= $vASeriesS;?>,
				labels: <?= $vALabelsS;?>
			}
			chartPie( dataObject );						
			
		</script>
		
    </body>
</html>