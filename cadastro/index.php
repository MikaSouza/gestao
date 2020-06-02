<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once '../utilitarios/transaction/transactionAgenda.php';
//$dados['start'] = date('Y-m-d', strtotime('-7 days', date('Y-m-d')));
//$dados['end'] = date('Y-m-d', strtotime('+1 days', date('Y-m-d')));
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <?php include_once '../includes/scripts_header.php' ?>

        <link href="../assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

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
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-right">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Capa</li>
                                    </ol><!--end breadcrumb-->
                                </div><!--end /div-->
                                <h4 class="page-title">Capa</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->                                        
                    
                    <div class="row"> 
                                                        
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mt-0 mb-3">Próximos Compromissos (7 dias)</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Hora</th>
                                                    <th>Responsável</th>                                                    
                                                    <th>Agendou</th>
                                                    <th>Cliente</th>
                                                    <th>Histórico</th>
													<th nowrap>Ações</th>
                                                </tr><!--end tr-->
                                            </thead>
        
                                            <tbody>
											<?php
											$atividades_pendentes = comboAgendaHome('H');
											if (count($atividades_pendentes) > 0):
											
											foreach ($atividades_pendentes as $i => $vAAgendaHome): ?>
                                                <tr>
                                                    <td><?= formatar_data($vAAgendaHome['AGEDATAINICIO']);?></td>
                                                    <td><?= $vAAgendaHome['AGEHORAINICIO'].' - '.$vAAgendaHome['AGEHORAFINAL'];?></td>
                                                    <td><?= $vAAgendaHome['RESPONSAVEL'];?></td>
                                                    <td><?= $vAAgendaHome['AGENDOU'];?></td>
													<?php if($vAAgendaHome['CLINOME'] == '') { ?>
														<td><?= 'Sem Cliente Definido';?></td>
													<?php } else { ?>
													<td>
														<a target="_blank" href="cadClientes.php?oid=<?php echo $vAAgendaHome['CLICODIGO']; ?>&method=consultar" >
														<?= $vAAgendaHome['CLINOME']; ?></a>
													</td>
													<?php } ?>
													<td><?= $vAAgendaHome['AGEDESCRICAO'];?></td>
                                                    <td nowrap>                 
														<a href="../utilitarios/cadAgenda.php?oid=<?php echo $vAAgendaHome['AGECODIGO']; ?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro" target="_blank"><i class="fas fa-edit text-info font-16"></i></a>                                                        
														<a href="#" onclick="excluirRegistroGridCapa('<?= $vAAgendaHome['AGECODIGO'];?>', '../utilitarios/transaction/transactionAgenda.php', 'excluirPadrao', '1879')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
                                                    </td>
                                                </tr><!--end tr-->
											<?php endforeach ?>	
											<?php
												else:
													echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há atividades nos próximos dias!</span>';
												endif;
											?>
                                            </tbody>
                                        </table>                    
                                    </div>  
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                        <div class="col-lg-4">
                            <div class="card">                                       
                                <div class="card-body"> 
                                    <h4 class="header-title mt-0 mb-3">Compromissos Pendentes/Atrasados</h4>
                                    <div class="slimscroll crm-dash-activity">
                                        <div class="activity">
											<?php
											$atividades_pendentes = comboAgendaHome('P');
											if (count($atividades_pendentes) > 0):
											
											foreach ($atividades_pendentes as $i => $vAAgendaHome): ?>
                                            <i class="mdi mdi-alert-octagon icon-alert-octagon text-danger"></i>
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0"><?= formatar_data($vAAgendaHome['AGEDATAINICIO']);?></h6>
                                                        <span class="text-muted"><?= $vAAgendaHome['AGEHORAINICIO'].' - '.$vAAgendaHome['AGEHORAFINAL'];?></span>
                                                    </div>
                                                    <p class="text-muted mt-3"><?= $vAAgendaHome['AGEDESCRICAO'];?>
                                                        <a href="../utilitarios/cadAgenda.php?oid=<?php echo $vAAgendaHome['AGECODIGO']; ?>&method=update" class="text-info" target="_blank">[+ detalhes]</a>
                                                    </p>
                                                    <div>
                                                        <span class="badge badge-soft-secondary">
														<?php if($vAAgendaHome['CLINOME'] == '') {  echo 'Sem Cliente Definido'; } else { ?>
														<a target="_blank" href="cadClientes.php?oid=<?php echo $vAAgendaHome['CLICODIGO']; ?>&method=consultar" >
														<?= $vAAgendaHome['CLINOME']; ?></a>
														<?php } ?>	
														</span>
                                                    </div>
                                                </div>
                                            </div>
											<?php endforeach ?>	 
											<?php
												else:
													echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há atividades pendentes!</span>';
												endif;
											?>	
                                        </div><!--end activity-->
                                    </div><!--end crm-dash-activity-->
                                </div>  <!--end card-body-->                                     
                            </div><!--end card--> 
                        </div><!--end col--> 
                    </div><!--end row-->    
					
					<!-- Juridico 
					<div class="row"> 
                                                        
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mt-0 mb-3">PROCESSOS COM FASES PENDENTES</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Processo</th>
                                                    <th>Fase</th>
                                                    <th>Autor</th>                                                    
                                                    <th>Data</th>
                                                    <th>Data Alarme</th>
                                                    <th>Responsável</th>
													<th>Descrição da Fase</th>
													<th nowrap>Ações</th>
                                                </tr>
                                            </thead>
        
                                            <tbody>
											<?php
											$atividades_pendentes = comboAgendaHome('H');
											if (count($atividades_pendentes) > 0):
											
											foreach ($atividades_pendentes as $i => $vAAgendaHome): ?>
                                                <tr>
                                                    <td><?= formatar_data($vAAgendaHome['AGEDATAINICIO']);?></td>
                                                    <td><?= $vAAgendaHome['AGEHORAINICIO'].' - '.$vAAgendaHome['AGEHORAFINAL'];?></td>
                                                    <td><?= $vAAgendaHome['RESPONSAVEL'];?></td>
                                                    <td><?= $vAAgendaHome['AGENDOU'];?></td>
													<?php if($vAAgendaHome['CLINOME'] == '') { ?>
														<td><?= 'Sem Cliente Definido';?></td>
													<?php } else { ?>
													<td>
														<a target="_blank" href="cadClientes.php?oid=<?php echo $vAAgendaHome['CLICODIGO']; ?>&method=consultar" >
														<?= $vAAgendaHome['CLINOME']; ?></a>
													</td>
													<?php } ?>
													<td><?= $vAAgendaHome['AGEDESCRICAO'];?></td>
                                                    <td nowrap>                 
														<a href="../utilitarios/cadAgenda.php?oid=<?php echo $vAAgendaHome['AGECODIGO']; ?>&method=update" class="mr-2" title="Editar Registro" alt="Editar Registro" target="_blank"><i class="fas fa-edit text-info font-16"></i></a>                                                        
														<a href="#" onclick="excluirRegistroGridCapa('<?= $vAAgendaHome['AGECODIGO'];?>', '../utilitarios/transaction/transactionAgenda.php', 'excluirPadrao', '1879')" title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
                                                    </td>
                                                </tr>
											<?php endforeach ?>	
											<?php
												else:
													echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há atividades nos próximos dias!</span>';
												endif;
											?>
                                            </tbody>
                                        </table>                    
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">                                       
                                <div class="card-body"> 
                                    <h4 class="header-title mt-0 mb-3">Compromissos Pendentes/Atrasados</h4>
                                    <div class="slimscroll crm-dash-activity">
                                        <div class="activity">
											<?php
											$atividades_pendentes = comboAgendaHome('P');
											if (count($atividades_pendentes) > 0):
											
											foreach ($atividades_pendentes as $i => $vAAgendaHome): ?>
                                            <i class="mdi mdi-alert-octagon icon-alert-octagon text-danger"></i>
                                            <div class="time-item">
                                                <div class="item-info">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0"><?= formatar_data($vAAgendaHome['AGEDATAINICIO']);?></h6>
                                                        <span class="text-muted"><?= $vAAgendaHome['AGEHORAINICIO'].' - '.$vAAgendaHome['AGEHORAFINAL'];?></span>
                                                    </div>
                                                    <p class="text-muted mt-3"><?= $vAAgendaHome['AGEDESCRICAO'];?>
                                                        <a href="../utilitarios/cadAgenda.php?oid=<?php echo $vAAgendaHome['AGECODIGO']; ?>&method=update" class="text-info" target="_blank">[+ detalhes]</a>
                                                    </p>
                                                    <div>
                                                        <span class="badge badge-soft-secondary">
														<?php if($vAAgendaHome['CLINOME'] == '') {  echo 'Sem Cliente Definido'; } else { ?>
														<a target="_blank" href="cadClientes.php?oid=<?php echo $vAAgendaHome['CLICODIGO']; ?>&method=consultar" >
														<?= $vAAgendaHome['CLINOME']; ?></a>
														<?php } ?>	
														</span>
                                                    </div>
                                                </div>
                                            </div>
											<?php endforeach ?>	 
											<?php
												else:
													echo '<span style="display: table; margin: 88px auto; font-size: 15px;">Não há atividades pendentes!</span>';
												endif;
											?>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					-->

                </div><!-- container -->
            </div>
            <?php include_once '../includes/footer.php' ?>
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <script src="../assets/plugins/moment/moment.js"></script>
        <script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="../assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="../assets/pages/jquery.crm_dashboard.init.js"></script>

        <?php include_once '../includes/scripts_footer.php' ?>
       
    </body>
</html>