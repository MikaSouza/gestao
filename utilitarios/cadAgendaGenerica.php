<?php

include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1879);
include_once __DIR__.'/transaction/transactionAgendaGenerica.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';

if ($_GET['vIMES'] == '')
	$vIMES = date('m');
else 
	$vIMES = $_GET['vIMES'];
if ($_GET['vIANO'] == '')
	$vIANO = date('Y');
else 
	$vIANO = $_GET['vIANO'];
if ($_GET['vICLIRESPONSAVEL'] == '')
	$vICLIRESPONSAVEL = '';
else {
	$vICLIRESPONSAVEL = $_GET['vICLIRESPONSAVEL'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?>

        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
	body,
	td,
	th {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}

	a:link {
		text-decoration: none;
	}

	a:visited {
		text-decoration: none;
	}

	a:hover {
		text-decoration: none;
	}

	a:active {
		text-decoration: none;
	}

	body {
		background-color: #FFFFFF;
	}

	.border-row {
		outline: 1px solid black;
		opacity: 0.7;
	}

	.base-color {
		background-color: 	#5D7EAC;
	}

	.bg-yellow {
		background-color: #faff00;
	}
	
	.bg-titulo {
		background-color: #FFE099;
	}	

	.bg-green-yellow {
		background-color: #A5FF4A;
	}

	.bg-orange {
		background-color: #FF9326;
	}

	.bg-lavanda {
		background-color: #f29dd8;
	}

	.bg-aqua {
		background-color: #FFFFFF;
	}

	.bg-turquesa {
		background-color: #A4DCF4;
	}

	.bg-vlight-grey {
		background-color: #ccc;
	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}
	
	.form-control {
		font-size: 11px;
		border: 1px solid rgb(141, 142, 143);
		height: calc(1.8rem + 2px);
		color: #2f5275;
	}

	.headcol {
		position: absolute;
		width: 5em;
		height: 46px;
		line-height: 50px;
		text-align: center;
		left: 0;
		top: auto;
		background-color:#E6E6FA;
	}

	.wrapper1 { width: 100%; overflow-x: scroll; overflow-y: hidden; }
	.wrapper2 { width: 100%; overflow-x: scroll; overflow-y: hidden; }
	.wrapper1 { height: 20px; }
	.div1 { margin-left: 5em; height: 20px; }
	.div2 { overflow: visible; width: 2100px}
	
	table {
	  border-collapse: separate;
	}

	td {
	  border-left-width: 0;
	  height: 16px;
	}

	td:first-child {
	  border-left-width: 1px;
	}
	</style>
	<script>
	/*
	window.onload = function() {
		document.querySelectorAll('.linha').forEach(e => e.addEventListener("click", function(elem) {
			elem.target.closest('tr').classList.toggle("border-row");
		}));
	}*/
	</script>
    </head>
	<body>

		<?php include_once '../includes/menu.php' ?>

        <div class="page-wrapper">

            <div class="page-content-list">

                <div class="container-fluid">

                    <?php $vAConfiguracaoTela['MENTITULO'] = 'Agenda Aberta 1';
					include_once '../includes/breadcrumb.php' ?>
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <form class="form-parsley" action="#" method="GET" name="form_search" id="form_search">
										<input type="hidden" name="hdn_metodo_search" id="hdn_metodo_search" value="searchDashBoard"/>

										<div class="form-group row">
											<div class="col-md-2"> 
												<label>Ano Base</label>
												<input type="text" class="form-control" name="vIANO" id="vIANO" title="Ano Base" value="<?= $vIANO; ?>" />
											</div>
											<div class="col-md-2">
												<label>Mês Base</label>										
												<select class="form-control" name="vIMES" id="vIMES" title="Mês Base">
													<option value="1" <?= ($vIMES == '1' ? 'selected' : ''); ?>>Janeiro</option>
													<option value="2" <?= ($vIMES == '2' ? 'selected' : ''); ?>>Fevereiro</option>
													<option value="3" <?= ($vIMES == '3' ? 'selected' : ''); ?>>Março</option>
													<option value="4" <?= ($vIMES == '4' ? 'selected' : ''); ?>>Abril</option>
													<option value="5" <?= ($vIMES == '5' ? 'selected' : ''); ?>>Maio</option>
													<option value="6" <?= ($vIMES == '6' ? 'selected' : ''); ?>>Junho</option>
													<option value="7" <?= ($vIMES == '7' ? 'selected' : ''); ?>>Julho</option>
													<option value="8" <?= ($vIMES == '8' ? 'selected' : ''); ?>>Agosto</option>
													<option value="9" <?= ($vIMES == '9' ? 'selected' : ''); ?>>Setembro</option>
													<option value="10" <?= ($vIMES == '10' ? 'selected' : ''); ?>>Outubro</option>
													<option value="11" <?= ($vIMES == '11' ? 'selected' : ''); ?>>Novembro</option>
													<option value="12" <?= ($vIMES == '12' ? 'selected' : ''); ?>>Dezembro</option>
												</select>
											</div>
											<div class="col-md-4">
												<label>Representante</label>
												<select name="vICLIRESPONSAVEL[]" id="vICLIRESPONSAVEL" title="Representante" class="form-control" style="width: 100%;font-size: 13px;" multiple>													
													<?php foreach (comboUsuariosAgenda() as $usuarios) :
														if (count($vICLIRESPONSAVEL) && ($vICLIRESPONSAVEL != '') > 0) { ?>
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if (in_array($usuarios['USUCODIGO'], $vICLIRESPONSAVEL)) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php } else { ?>
															<option value="<?php echo $usuarios['USUCODIGO']; ?>"><?php echo $usuarios['USUNOME']; ?></option>
													<?php }
													endforeach; ?>
												</select>
												<!--
												<select name="vICLIRESPONSAVEL" id="vICLIRESPONSAVEL" class="custom-select" title="Representante">
													<option value=""> Todos </option>
													<?php foreach (comboUsuariosAgenda() as $usuarios) : ?>
														<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vICLIRESPONSAVEL == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
													<?php endforeach; ?>
												</select>-->
											</div>
											<div class="col-md-4">
												<label></label><br/>
											
												<button type="submit" id="btnPesquisar" class="btn btn-primary" class="nav-link" >FILTRAR</button>												
												<button type="button" id="btnImprimir" class="btn btn-primary" class="nav-link" onClick="gerarExcelAgenda('<?= $vIMES;?>', '<?= $vIANO;?>', '<?= $vICLIRESPONSAVEL;?>');">GERAR EXCEL</button>
											</div>	
										</div>
									</form>	
									<form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>">
										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>

										<div class="wrapper1">
											<div class="div1"></div>
										</div>
										<div style="height: 20px"></div>
										<div class="wrapper2">
											<div class="div2">
											<table border="0" align="center" cellpadding="0.5" cellspacing="0.5" style="background-color:#9C816A; margin-left: 3.5em; overflow: auto">
											<thead >
											<tr class="bg-titulo text-center"> 
												<td nowrap="nowrap" class="bg-titulo text-center" colspan="14"><b>PROGRAMA DE TRABALHO <?= strtoupper(descricaoMes($vIMES));?> DE <?= $vIANO;?></b></td>
											</tr>	
											<tr style="background-color:#FFFFFF;">
												<td class="base-color">&nbsp;</td> 
												<?php foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  ?>
												<td class="base-color" colspan="2" align="center"><b><?= otimizarNome($usuarios['USUNOME']); ?></b></td>
												<?php endforeach; ?>
												<td class="base-color">&nbsp;</td> 
											</tr>
											<tr style="background-color:#FFFFFF;">
												<td class="bg-turquesa">&nbsp;</td> 
												<?php foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  ?>
												<td class="bg-turquesa" align="center"><b>Município</b></td> 
												<td class="bg-turquesa" align="center"><b>Atividade</b></td> 
												<?php endforeach; ?>
												<td class="bg-turquesa">&nbsp;</td> 
											</tr>
											</thead>
											<?php
												$vResult = fill_AgendaGenericaMesAno($vIANO, $vIMES, $vICLIRESPONSAVEL);
												$atividades = array(); 
												
												foreach ($vResult['dados'] as $arrayDados) {
													$atividades[$arrayDados['AGERESPONSAVEL']][$arrayDados['AGEDATA']]['MUNICIPIO'] = $arrayDados['AGEMUNICIPIO'];	
													$atividades[$arrayDados['AGERESPONSAVEL']][$arrayDados['AGEDATA']]['ATIVIDADE'] = $arrayDados['AGEATIVIDADE'];
												}	
											//	pre($atividades);
												$vDDataInicio = $vIANO.'-'.$vIMES.'-01';
												$vSUltimoDiaMes = ultimoDiaMes($vIANO, $vIMES);
												$vDDataFinal = $vIANO.'-'.$vIMES.'-'.$vSUltimoDiaMes;
												$vtimestamp = strtotime($vDDataInicio);
												$hojemaisum = date('Y-m-d', strtotime('+1 days', strtotime($vDDataFinal)));
												$xd = 0; $i=0;
												do {
													$datax = date('w', $vtimestamp + 60*60*24*$xd); 																								
													?> 
												<tr style="background-color: <?= (($datax == 6) || ($datax == 0) ? '#76767a' : '#E6E6FA');?>" class="linha">	
													<td align="center" class="headcol"> 
														<?php $vIDia = date('d', $vtimestamp + 60*60*24*$xd); 
															if (strlen($vIDia) == 1) $vIDia = '0'.$vIDia;
															if (strlen($vIMES) == 1) $vIMES = '0'.$vIMES;
															echo substr(diaSemana2($datax, 'S'), 0, 4)."/".date('d', $vtimestamp + 60*60*24*$xd); 
														?> 
													</td>   
													<?php foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
														//if($i%2!=0) $Pintar = "#E6E6FA";  else $Pintar = "";  $i++;
														$PintarAtv = "#E6E6FA";
														$PintarMun = "";
														
														if (($datax == 6) || ($datax == 0)) {$PintarAtv = "#76767a"; $PintarMun = "#76767a";}
													?>												
													<td><textarea class="form-control" title="Município" name="vSM_0<?= $usuarios['USUCODIGO']; ?>_<?= $vIANO;?>-<?= $vIMES;?>-<?= $vIDia;?>" id="vSM_0<?= $usuarios['USUCODIGO']; ?>_<?= $vIANO;?>-<?= $vIMES;?>-<?= $vIDia;?>" style="background-color: <?= $PintarMun; ?>; resize: none; width: 150px; color: <?= (($datax == 6) || ($datax == 0) ? white : black);?>" rows="2" cols="33"><?= (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO']);?></textarea></td>
													<td><textarea class="form-control" title="Atividade" name="vSA_0<?= $usuarios['USUCODIGO']; ?>_<?= $vIANO;?>-<?= $vIMES;?>-<?= $vIDia;?>" id="vSA_0<?= $usuarios['USUCODIGO']; ?>_<?= $vIANO;?>-<?= $vIMES;?>-<?= $vIDia;?>" style="background-color: <?= $PintarAtv; ?>; resize: none; width: 150px; color: <?= (($datax == 6) || ($datax == 0) ? white : black);?>" rows="2" cols="33"><?= (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE']);?></textarea></td> 
													<?php endforeach; ?>			
												</tr>	
												<?php 	 
													$xd = $xd + 1;
													$datay = date('Y-m-d', $vtimestamp + 60*60*24*$xd);
												} while ($datay < $hojemaisum); ?>
											<tr style="background-color:#FFFFFF">
												<td class="bg-turquesa">&nbsp;</td> 
												<?php foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  ?>
												<td class="bg-turquesa" align="center">Município</td> 
												<td class="bg-turquesa" align="center">Atividade</td> 
												<?php endforeach; ?>
												<td class="bg-turquesa">&nbsp;</td> 
											</tr>	
											<tr style="background-color:#FFFFFF">
												<td class="base-color">&nbsp;</td> 
												<?php foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  ?>
												<td class="base-color" colspan="2" align="center"><?= otimizarNome($usuarios['USUNOME']); ?></td>
												<?php endforeach; ?>
												<td class="base-color">&nbsp;</td> 
											</tr>
											</table>
											</div>
										</div>
										</br>
										<div class="col-md-6 fa-pull-left">
											<button type="button" onClick="validarForm();" title="Salvar Dados" class="btn btn-primary waves-effect waves-light">Salvar Dados</button>
											<a href="<?= '../cadastro/#' ?>">
												<button type="button" title="Cancelar" class="btn btn-warning waves-effect m-l-5">Voltar</button>
											</a> 
										</div>
                                    </form>
                                </div>
                            </div>
                        </div>
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

		<?php include_once '../includes/scripts_footer.php' ?>

		<!--Wysiwig js-->
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="../assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="../assets/pages/jquery.form-editor.init.js"></script>
		<script src="../assets/plugins/select2/select2.min.js"></script>
		<script src="js/cadAgenda.js"></script>
		
		<script>
			$(function () {
				$(".select2-multiple").select2();
			});	
			function validarForm(){
				var vAErro = validaCamposForm().split("#");
				if (vAErro[0] == 'S'){
					Swal.fire('Opss..', vAErro[1], 'warning')
					return false;
				} else
					document.forms[1].submit();
			}
			
			function imprimirAgenda(vIMES, vIANO, vICLIRESPONSAVEL){
				window.open(
					"imprimirAgendaGenerica.php?" +
						"vIMES=" + vIMES + "&vIANO=" + vIANO + "&vICLIRESPONSAVEL=" + vICLIRESPONSAVEL,
					"popup",
					"toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=0,left=0,width=" +
						$(window).width() +
						",height=" +
						$(window).height()
				);

			}
			
			function gerarExcelAgenda(vIMES, vIANO, vICLIRESPONSAVEL){
				var vICLIRESPONSAVEL = $("#vICLIRESPONSAVEL").val();
				window.open(
					"excelAgendaGenerica.php?" +
						"vIMES=" + vIMES + "&vIANO=" + vIANO + "&vICLIRESPONSAVEL=" + vICLIRESPONSAVEL,
					"popup",
					"toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,top=0,left=0,width=" +
						$(window).width() +
						",height=" +
						$(window).height()
				);

			}
			
			
		</script>
		<script>
			$(document).ready(function() {
				$('#vICLIRESPONSAVEL').select2();
				$("#vICLIRESPONSAVEL").addClass("form-control");
				$("#vICLIRESPONSAVEL").addClass("custom-select");
				$("#vICLIRESPONSAVEL").select2({
					height: '38px !important'
				});
			});
		</script>
    </body>
</html>