<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1879);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboAtividades.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?> 

        <!--calendar css-->
        <link href="../assets/plugins/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />
		<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
		
        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

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
							<div id="mycal"></div>
						</div>
					</div>                    

                </div><!-- container -->
            </div>
           <?php include_once '../includes/footer.php' ?>
        </div>
        <!-- end page-wrapper -->
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalAgenda">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Atividades - Agenda</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					<form class="form-parsley" action="#" method="post" name="formAgendaDetalhe" id="formAgendaDetalhe">
						<div class="form-group row">
							<div class="col-lg-12">
								<div class="checkbox checkbox-warning">
									<input id="checkbox2" type="checkbox" onclick="mostrarDivCliente();">
									<label for="checkbox2">
										Sem Cliente
									</label>
								</div>
							</div>	
						</div>
						<div class="form-group row divCliente">
							<div class="col-md-12">                                                      
								<label>Sigla - Cliente
									<small class="text-danger font-13">*</small>
								</label>			
								<select name="vICLICODIGO" id="vICLICODIGO" title="Sigla - Cliente" class="form-control divObrigatorio" style="width: 100%;font-size: 13px;"/><br/>								
								<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value=""/>
							</div>								
						</div>	
						<div class="form-group row">
							<div class="col-md-6">
								<label>Data</label>
								<input class="form-control divObrigatorio" name="vSAGEDATAINICIO" title="Data" id="vSAGEDATAINICIO" type="date" maxlength="10" value="<?= date('Y-m-d');?>">
							</div>
							<div class="col-md-3">
								<label>Hora Início
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Hora Início" name="vSAGEHORAINICIO" id="vSAGEHORAINICIO" value="" type="text" maxlength="5" onKeyPress="return digitos(event, this);" onKeyUp="mascara('HORA', this, event);" >
							</div>
							<div class="col-md-3">
								<label>Hora Final
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Hora Final" name="vSAGEHORAFINAL" id="vSAGEHORAFINAL" value="" type="text" maxlength="5" onKeyPress="return digitos(event, this);" onKeyUp="mascara('HORA', this, event);" >
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-6">   
								<label>Tipo de Contato/Atividade
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vIAGETIPO" id="vIAGETIPO" class="custom-select divObrigatorio" title="Tipo de Contato/Atividade">
									<option value="">  -------------  </option>
									<?php 									
									foreach (comboAtividades() as $tabelas): ?>                                                            
										<option value="<?php echo $tabelas['ATICODIGO']; ?>"><?php echo $tabelas['ATINOME']; ?></option>
									<?php endforeach; ?>
								</select>                                                    
							</div>
							<div class="col-md-6">                                                      
								<label>Responsável</label>
								<select name="vIAGERESPONSAVEL" id="vIAGERESPONSAVEL" class="custom-select divObrigatorio" title="Responsável">
									<option value="">  -------------  </option>
									<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
										<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($_SESSION['SI_USUCODIGO'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>							
						<div class="form-group row">
							<div class="col-md-12">                                                      
								<label>Descrição</label>
								<textarea class="form-control divObrigatorio" id="vSAGEDESCRICAO" name="vSAGEDESCRICAO" rows="3" title="Descrição" ></textarea>
							</div>
						</div>	
						<div class="form-group row">
							<div class="col-md-3">        
								<label>Concluído</label>
								<select class="custom-select" name="vSAGECONCLUIDO" id="vSAGECONCLUIDO" title="Concluído">									
									<option value="N" selected>Não</option> 
									<option value="S">Sim</option>									
								</select>
							</div>
							<div class="col-md-4">        
								<label>Enviar E-mail</label>
								<select class="custom-select" name="vSAGEENVIAREMAIL" id="vSAGEENVIAREMAIL">
									<option value="S">Sim</option>
									<option value="N">Não</option>
								</select>
							</div>
							<div class="col-md-5">        
								<label>Copiar Supervisor?</label>
								<select class="custom-select" name="vSAGECOPIARSUPERVISOR" id="vSAGECOPIARSUPERVISOR">
									<option value="N">Não</option>
									<option value="S">Sim</option>													
								</select>
							</div>
						</div>
						<input type="hidden" id="vIAGECODIGO" name="vIAGECODIGO" value="">
						<input type="hidden" id="hdn_metodo_insert" name="hdn_metodo_insert" value="insertAgenda">
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="eventContent">		
			<b>Título</b>: <span id="pSAGETITULO"></span><br>
			<b>Cliente</b>: <span id="pSCLIENTE"></span><br>
			<b>Contato</b>: <span id="pSCONTATO"></span><br>
			<b>Vínculo</b>: <span id="pSVINCULO"></span><br>
			<b>Responsável</b>: <span id="pSAGERESPONSAVEL"></span><br>
			<b>Tipo de Atividade</b>: <span id="pSAGETIPO"></span><br>
			<b>Início</b>: <span id="startTime"></span><br>
			<b>Término</b>: <span id="endTime"></span><br>
			<b>Concluído</b>: <span id="pSAGECONCLUIDO"></span><br>
			<b>Descrição</b>: <p id="pSAGEDESCRICAO"></p><br>
			<?php if (verificarAcessoCadastroNoRedirect(379, 'update')): ?>
				<button id="eventLink" class="btnGenericoFilho" title="Editar Atividade"><b>Editar Atividade</b></button>&nbsp;&nbsp;&nbsp;&nbsp;     
			<?php endif; ?>
			<?php if (verificarAcessoCadastroNoRedirect(379, 'excluir')): ?>
				<button id="deleteEvent" class="btnGenericoFilho" title="Excluir Atividade"><b>Excluir Atividade</b></button><br>       
			<?php endif; ?><br><br> 
		</div>
		
        <!-- jQuery  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.min.js"></script>
        <script src="../assets/js/jquery.slimscroll.min.js"></script>

        <script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../assets/plugins/moment/moment.js"></script>
        <script src='../assets/plugins/fullcalendar/js/fullcalendar.min.js'></script>
        <script src='../assets/pages/jquery.calendar.js'></script>

        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/select2/select2.min.js"></script>
        <script src="js/agenda.js"></script>
    </body>
</html>