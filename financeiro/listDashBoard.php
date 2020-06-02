<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];

function getCentroCusto() {
	$sql = "SELECT						
				COUNT(u.TABCENTROCUSTO) as TOTAL, 
				t.TABDESCRICAO
			FROM CONTASAPAGAR u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABCENTROCUSTO
			WHERE
				u.CTPSTATUS = 'S'
			GROUP BY u.TABCENTROCUSTO
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

function getPlanoContas() {
	$sql = "SELECT						
				COUNT(u.TABPLANOCONTAS) as TOTAL,
				t.TABDESCRICAO
			FROM CONTASAPAGAR u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABPLANOCONTAS
			WHERE
				u.CTPSTATUS = 'S'
			GROUP BY u.TABPLANOCONTAS
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

function getContaBancaria() {
	$sql = "SELECT						
				COUNT(u.CBACODIGO) as TOTAL,
				t.CBAAGENCIA
			FROM CONTASAPAGAR u
			LEFT JOIN CONTASBANCARIAS t ON t.CBACODIGO = u.CBACODIGO
			WHERE
				u.CTPSTATUS = 'S' 
			GROUP BY u.CBACODIGO
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

function getFilial() {
	$sql = "SELECT						
				COUNT(u.EMPCODIGO) as TOTAL,
				t.EMPNOMEFANTASIA
			FROM CONTASAPAGAR u
			LEFT JOIN EMPRESAUSUARIA t ON t.EMPCODIGO = u.EMPCODIGO
			WHERE
				u.CTPSTATUS = 'S' 
			GROUP BY u.EMPCODIGO
			ORDER BY TOTAL DESC	";			
	$arrayQueryFilho = array(
						'query' => $sql,
						'parametros' => array()
					);
	$lista = consultaComposta($arrayQueryFilho);		
	$retorno = array(); $labels = array(); $series = array();
	foreach ($lista['dados'] as $lista) :
		if(trim($lista['EMPNOMEFANTASIA']) == "")
			$lista['EMPNOMEFANTASIA'] = "Não definido";
		$labels[] = $lista['EMPNOMEFANTASIA'];
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
									<h4 class="header-title mt-0 mb-4">Centro de Custo</h4>
									<div class="">
										<div id="div_centro_custo" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Plano de Contas</h4>
									<div class="">
										<div id="div_plano_contas" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Conta Bancária</h4>
									<div class="">
										<div id="div_conta_bancaria" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->   
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Filial</h4>
									<div class="">
										<div id="div_filial" class="apex-charts"></div>
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
			$global_centro_custo = getCentroCusto();
			$vASeriesCC = json_encode($global_centro_custo[0]);
			$vALabelsCC = json_encode($global_centro_custo[1]);
			
			$global_plano_contas = getPlanoContas();
			$vASeriesPC = json_encode($global_plano_contas[0]);
			$vALabelsPC = json_encode($global_plano_contas[1]);
			
			$global_conta_bancaria = getContaBancaria();
			$vASeriesCB = json_encode($global_conta_bancaria[0]);
			$vALabelsCB = json_encode($global_conta_bancaria[1]);
			
			$global_filiais = getFilial();
			$vASeriesF = json_encode($global_filiais[0]);
			$vALabelsF = json_encode($global_filiais[1]);
			
		?>
		<script type="text/javascript" DEFER="DEFER">
		
			var dataObject = {
				title: 'Centro de Custo',
				width: 480,
				height: 480,
				div_retorno: '#div_centro_custo',
				series: <?= $vASeriesCC;?>,
				labels: <?= $vALabelsCC;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Plano de Contas',
				width: 480,
				height: 480,
				div_retorno: '#div_plano_contas',
				series: <?= $vASeriesPC;?>,
				labels: <?= $vALabelsPC;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Conta Bancária',
				width: 480,
				height: 480,
				div_retorno: '#div_conta_bancaria',
				series: <?= $vASeriesCB;?>,
				labels: <?= $vALabelsCB;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Filial',
				width: 480,
				height: 480,
				div_retorno: '#div_filial',
				series: <?= $vASeriesF;?>,
				labels: <?= $vALabelsF;?>
			}
			chartPie( dataObject );
		</script>
		
    </body>
</html>