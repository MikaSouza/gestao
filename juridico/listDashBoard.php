<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';

function getTipo(){	
	$sql = "SELECT						
				COUNT(u.PRCTIPODEACAO) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCTIPODEACAO
			WHERE
				u.PRCSTATUS = 'S'	  
			GROUP BY u.PRCTIPODEACAO
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

function getVaras() {
	$sql = "SELECT						
				COUNT(u.PRCVARA) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCVARA
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCVARA IS NOT NULL AND u.PRCVARA <> ''	
			GROUP BY u.PRCVARA
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

function getForo() {
	$sql = "SELECT						
				COUNT(u.PRCFORO) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCFORO
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCFORO IS NOT NULL AND u.PRCFORO <> ''
			GROUP BY u.PRCFORO
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

function getComarcas() {
	$sql = "SELECT						
				COUNT(u.PRCCOMARCA) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCCOMARCA
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCCOMARCA IS NOT NULL AND u.PRCCOMARCA <> ''
			GROUP BY u.PRCCOMARCA
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

function getResponsavel() {
	$sql = "SELECT						
				COUNT(u.PRCRESPONSAVEL) as TOTAL,
				t.USUNOME
			FROM PROCESSOS u
			LEFT JOIN USUARIOS t ON t.USUCODIGO = u.PRCRESPONSAVEL
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCRESPONSAVEL IS NOT NULL AND u.PRCRESPONSAVEL <> ''
			GROUP BY u.PRCRESPONSAVEL
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

function getFase() {
	$sql = "SELECT						
				COUNT(u.PRCFASE) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCFASE
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCFASE IS NOT NULL AND u.PRCFASE <> ''
			GROUP BY u.PRCFASE
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

function getGrupo() {
	$sql = "SELECT						
				COUNT(u.PRCGRUPO) as TOTAL,
				t.TABDESCRICAO
			FROM PROCESSOS u
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.PRCGRUPO
			WHERE
				u.PRCSTATUS = 'S' AND 
				u.PRCGRUPO IS NOT NULL AND u.PRCGRUPO <> ''
			GROUP BY u.PRCGRUPO
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
									<h4 class="header-title mt-0 mb-4">Tipo de Ação</h4>
									<div class="">
										<div id="div_tipo" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Varas</h4>
									<div class="">
										<div id="div_varas" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Foro</h4>
									<div class="">
										<div id="div_foro" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->   
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Comarcas</h4>
									<div class="">
										<div id="div_comarcas" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col-->  
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Responsável</h4>
									<div class="">
										<div id="div_responsavel" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col--> 	
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Fase</h4>
									<div class="">
										<div id="div_fase" class="apex-charts"></div>
									</div>                                        
								</div><!--end card-body-->
							</div><!--end card-->
						</div><!--end col--> 
						<div class="col-6"> 
							<div class="card">
								<div class="card-body">
									<h4 class="header-title mt-0 mb-4">Grupo</h4>
									<div class="">
										<div id="div_grupo" class="apex-charts"></div>
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
			$global_tipo = getTipo();
			$vASeries = json_encode($global_tipo[0]);
			$vALabels = json_encode($global_tipo[1]);
			
			$global_varas = getVaras();
			$vASeriesVE = json_encode($global_varas[0]);
			$vALabelsVE = json_encode($global_varas[1]);
			
			$global_foro = getForo();
			$vASeriesC = json_encode($global_foro[0]);
			$vALabelsC = json_encode($global_foro[1]);
			
			$global_comarcas = getComarcas();
			$vASeriesF = json_encode($global_comarcas[0]);
			$vALabelsF = json_encode($global_comarcas[1]);
			
			$global_responsavel = getResponsavel();
			$vASeriesR = json_encode($global_responsavel[0]);
			$vALabelsR = json_encode($global_responsavel[1]);
			
			$global_fase = getFase();
			$vASeriesA = json_encode($global_fase[0]);
			$vALabelsA = json_encode($global_fase[1]);
			
			$global_grupo = getGrupo();
			$vASeriesG = json_encode($global_grupo[0]);
			$vALabelsG = json_encode($global_grupo[1]);
			
		?>
		<script type="text/javascript" DEFER="DEFER">
		
			var dataObject = {
				title: 'Tipo de Ação',
				width: 480,
				height: 480,
				div_retorno: '#div_tipo',
				series: <?= $vASeries;?>,
				labels: <?= $vALabels;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Varas',
				width: 480,
				height: 480,
				div_retorno: '#div_varas',
				series: <?= $vASeriesVE;?>,
				labels: <?= $vALabelsVE;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Foro',
				width: 480,
				height: 480,
				div_retorno: '#div_foro',
				series: <?= $vASeriesC;?>,
				labels: <?= $vALabelsC;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Comarcas',
				width: 480,
				height: 480,
				div_retorno: '#div_comarcas',
				series: <?= $vASeriesF;?>,
				labels: <?= $vALabelsF;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Responsável',
				width: 480,
				height: 480,
				div_retorno: '#div_responsavel',
				series: <?= $vASeriesR;?>,
				labels: <?= $vALabelsR;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Fase',
				width: 480,
				height: 480,
				div_retorno: '#div_fase',
				series: <?= $vASeriesA;?>,
				labels: <?= $vALabelsA;?>
			}
			chartPie( dataObject );
			
			var dataObject = {
				title: 'Grupo',
				width: 480,
				height: 480,
				div_retorno: '#div_grupo',
				series: <?= $vASeriesG;?>,
				labels: <?= $vALabelsG;?>
			}
			chartPie( dataObject );
		</script>
		
    </body>
</html>