<?php
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2030);
include_once __DIR__ . '/transaction/' . $vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__ . '/../cadastro/combos/comboTabelas.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php include_once '../includes/scripts_header.php' ?>

	<link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
	<link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />

	<!-- App css -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
	<link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../assets/css/stylesUpload.css" />

</head>

<body>

	<?php include_once '../includes/menu.php' ?>

	<div class="page-wrapper">

		<!-- Page Content-->
		<div class="page-content">

			<div class="container-fluid">

				<?php include_once '../includes/breadcrumb.php' ?>

				<div class="row">
					<div class="col-lg-12 mx-auto">
						<div class="card">
							<div class="card-body">

								<form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>" enctype="multipart/form-data">
									<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO" value="<?php echo $vIOid; ?>" />
									<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if (isset($_GET['method'])) {
																										echo $_GET['method'];
																									} else {
																										echo "insert";
																									} ?>" />
									<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>" />
									<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>" />
									<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>" />
									<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1" />
									<input type="hidden" name="vHMENCODIGO" id="vHMENCODIGO" value="<?= $vAConfiguracaoTela['MENCODIGO']; ?>" />


									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#contatos" role="tab">Respons??vel</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#enderecos-1" role="tab">Membros</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#contratos" role="tab">Atividades</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid; ?>', '6');">Digitaliza????es/Arquivos</a>
										</li>
									</ul>
									<!-- Nav tabs end -->

									<!-- Tab panes -->
									<div class="tab-content">

										<!-- Aba Dados Gerais -->
										<div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>1 - Nome do Ente
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNOMEDOENTE" id="vSFORNOMEDOENTE" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNOMEDOENTE'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>2 - N??mero de habitantes do munic??pio
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNUMHABMUNICIPIO" id="vSFORNUMHABMUNICIPIO" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNUMHABMUNICIPIO'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>3 - Valor do or??amento anual
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vMFORVALORCANUAL" id="vMFORVALORCANUAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORVALORCANUAL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>4 - N??mero de servidores (inclui agentes pol??ticos e ccs)</label>
													<input class="form-control" name="vSFORNUMSERVIDORES" id="vSFORNUMSERVIDORES" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNUMSERVIDORES'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>5 - Descrever a Lei que instituiu o Sistema de Controle Interno e suas altera????es, anexando c??pia</label>
													<input class="form-control" name="vSFORDESCLEI" id="vSFORDESCLEI" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORDESCLEI'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>6 - A Unidade de Controle Interno possui Regimento Interno?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORPOSREGINTERNO" class="custom-select obrigatorio" name="vSFORPOSREGINTERNO">
														<option value="Sim" <?php if ($vROBJETO['FORPOSREGINTERNO'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORPOSREGINTERNO'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Aba Dados Gerais -->

										<!-- Responsavel -->
										<div class="tab-pane p-3" id="contatos" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>7.1 - O respons??vel pelo Sistema de Controle Interno ocupa o cargo de
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORCARGORESPINT" class="custom-select obrigatorio" name="vSFORCARGORESPINT">
														<option value="Presidente" <?php if ($vROBJETO['FORCARGORESPINT'] == 'Presidente') echo "selected='selected'"; ?>>Presidente</option>
														<option value="Coordenador" <?php if ($vROBJETO['FORCARGORESPINT'] == 'Coordenador') echo "selected='selected'"; ?>>Coordenador</option>
														<option value="Diretor" <?php if ($vROBJETO['FORCARGORESPINT'] == 'Diretor') echo "selected='selected'"; ?>>Diretor</option>
														<option value="Chefe" <?php if ($vROBJETO['FORCARGORESPINT'] == 'Chefe') echo "selected='selected'"; ?>>Chefe</option>
														<option value="Outro" <?php if ($vROBJETO['FORCARGORESPINT'] == 'Outro') echo "selected='selected'"; ?>>Outro</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Nome do Respons??vel
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNOMERESPINT" id="vSFORNOMERESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNOMERESPINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>Forma????o
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORFORMRESPINT" class="custom-select obrigatorio" name="vSFORFORMRESPINT">
														<option value="1?? grau incompleto" <?php if ($vROBJETO['FORFORMRESPINT'] == '1?? grau incompleto') echo "selected='selected'"; ?>>1?? grau incompleto</option>
														<option value="1?? grau completo" <?php if ($vROBJETO['FORFORMRESPINT'] == '1?? grau completo') echo "selected='selected'"; ?>>1?? grau completo</option>
														<option value="2?? grau incompleto" <?php if ($vROBJETO['FORFORMRESPINT'] == '2?? grau incompleto') echo "selected='selected'"; ?>>2?? grau incompleto</option>
														<option value="2?? grau completo" <?php if ($vROBJETO['FORFORMRESPINT'] == '2?? grau completo') echo "selected='selected'"; ?>>2?? grau completo</option>
														<option value="N??vel Superior. Descrever:" <?php if ($vROBJETO['FORFORMRESPINT'] == 'N??vel Superior. Descrever:') echo "selected='selected'"; ?>>N??vel Superior. Descrever:</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Insira o Superior</label>
													<input class="form-control" name="vSFORSUPRESPINT" id="vSFORSUPRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORSUPRESPINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Tempo de Experi??ncia no Controle Interno</label>
													<input class="form-control" name="vSFORTEMPEXPRESPINT" id="vSFORTEMPEXPRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTEMPEXPRESPINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Tem dedica????o exclusiva no Controle Interno
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORDEDEXCRESPINT" class="custom-select obrigatorio" name="vSFORDEDEXCRESPINT">
														<option value="Sim" <?php if ($vROBJETO['FORDEDEXCRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORDEDEXCRESPINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Qual cargo que ocupa no Munic??pio?</label>
													<input class="form-control" name="vSFORCARGOMUNRESPINT" id="vSFORCARGOMUNRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORCARGOMUNRESPINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>?? servidor de Carreira?</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORSERCARRRESPINT" class="custom-select" name="vSFORSERCARRRESPINT">
														<option value="Sim" <?php if ($vROBJETO['FORSERCARRRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORSERCARRRESPINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Est?? em est??gio probat??rio?</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORESTPROBRESPINT" class="custom-select" name="vSFORESTPROBRESPINT">
														<option value="Sim" <?php if ($vROBJETO['FORESTPROBRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORESTPROBRESPINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Realizou algum curso de atualiza????o na ??rea de Controle Interno da Administra????o..</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORREACURRESPINT" class="custom-select" name="vSFORREACURRESPINT">
														<option value="Nenhum" <?php if ($vROBJETO['FORREACURRESPINT'] == 'Nenhum') echo "selected='selected'"; ?>>Nenhum</option>
														<option value="Um" <?php if ($vROBJETO['FORREACURRESPINT'] == 'Um') echo "selected='selected'"; ?>>Um</option>
														<option value="Dois ?? quatro" <?php if ($vROBJETO['FORREACURRESPINT'] == 'Dois ?? quatro') echo "selected='selected'"; ?>>Dois ?? quatro</option>
														<option value="Cinco ou mais" <?php if ($vROBJETO['FORREACURRESPINT'] == 'Cinco ou mais') echo "selected='selected'"; ?>>Cinco ou mais</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Responsavel -->

										<!-- Membros -->
										<div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>7.2 ??? Dos Membros do Sistema de Controle Interno</label>
													<label>Nome do Membro
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNOMEMEMBINT" id="vSFORNOMEMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNOMEMEMBINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Forma????o
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORFORMEMBINT" class="custom-select obrigatorio" name="vSFORFORMEMBINT">
														<option value="1?? grau incompleto" <?php if ($vROBJETO['FORFORMEMBINT'] == '1?? grau incompleto') echo "selected='selected'"; ?>>1?? grau incompleto</option>
														<option value="1?? grau completo" <?php if ($vROBJETO['FORFORMEMBINT'] == '1?? grau completo') echo "selected='selected'"; ?>>1?? grau completo</option>
														<option value="2?? grau incompleto" <?php if ($vROBJETO['FORFORMEMBINT'] == '2?? grau incompleto') echo "selected='selected'"; ?>>2?? grau incompleto</option>
														<option value="2?? grau completo" <?php if ($vROBJETO['FORFORMEMBINT'] == '2?? grau completo') echo "selected='selected'"; ?>>2?? grau completo</option>
														<option value="N??vel Superior. Descrever:" <?php if ($vROBJETO['FORFORMEMBINT'] == 'N??vel Superior. Descrever:') echo "selected='selected'"; ?>>N??vel Superior. Descrever:</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Insira o Superior</label>
													<input class="form-control obrigatorio" name="vSFORSUPMEMBINT" id="vSFORSUPMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORSUPMEMBINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Tempo de Experi??ncia no Controle Interno</label>
													<input class="form-control" name="vSFORTEMPEXPMEMBINT" id="vSFORTEMPEXPMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTEMPEXPMEMBINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Tem dedica????o exclusiva no Controle Interno</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORDEDEXCMEMBINT" class="custom-select" name="vSFORDEDEXCMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['FORDEDEXCMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORDEDEXCMEMBINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Qual cargo que ocupa no Munic??pio</label>
													<input class="form-control" name="vSFORCARGOMUNMEMBINT" id="vSFORCARGOMUNMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORCARGOMUNMEMBINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>?? servidor de Carreira</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORSERCARRMEMBINT" class="custom-select" name="vSFORSERCARRMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['FORSERCARRMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORSERCARRMEMBINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Est?? em est??gio probat??rio</label>
													<select title="Tipo Pessoa" id="vSFORESTPROBMEMBINT" class="custom-select obrigatorio" name="vSFORESTPROBMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['FORESTPROBMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORESTPROBMEMBINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Realizou algum curso de atualiza????o na ??rea de Controle Interno da Administra????o..</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORREACURMEMBINT" class="custom-select" name="vSFORREACURMEMBINT">
														<option value="Nenhum" <?php if ($vROBJETO['FORREACURMEMBINT'] == 'Nenhum') echo "selected='selected'"; ?>>Nenhum</option>
														<option value="Um" <?php if ($vROBJETO['FORREACURMEMBINT'] == 'Um') echo "selected='selected'"; ?>>Um</option>
														<option value="Dois ?? quatro" <?php if ($vROBJETO['FORREACURMEMBINT'] == 'Dois ?? quatro') echo "selected='selected'"; ?>>Dois ?? quatro</option>
														<option value="Cinco ou mais" <?php if ($vROBJETO['FORREACURMEMBINT'] == 'Cinco ou mais') echo "selected='selected'"; ?>>Cinco ou mais</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Membros -->

										<!-- Atividades -->
										<div class="tab-pane p-3" id="contratos" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>8 ??? Das atividades da Unidade de Controle Interno</label>
													<label>8.1 - A Unidade de Controle Interno possui um planejamento/programa de trabalho com a descri????o das suas atividades?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORPOSSPLAUNIINT" class="custom-select" name="vSFORPOSSPLAUNIINT">
														<option value="Sim" <?php if ($vROBJETO['FORPOSSPLAUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORPOSSPLAUNIINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>8.2 Procedimentos de Auditoria
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORPROAUDUNIINT" class="custom-select" name="vSFORPROAUDUNIINT">
														<option value="Sim" <?php if ($vROBJETO['FORPROAUDUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORPROAUDUNIINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
														<option value="Raramente" <?php if ($vROBJETO['FORPROAUDUNIINT'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>Descrever o resumo das auditorias realizadas nos ??ltimos 06 meses
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control obrigatorio" name="vSFORDESCRESUNIINT" id="vSFORDESCRESUNIINT" type="text"><?= nl2br($vROBJETO['FORDESCRESUNIINT']); ?></textarea>
												</div>
												<div class="col-md-6">
													<label>8.3 - Verifica????es, Controles e Acompanhamentos</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORVERCONMUNIINT" class="custom-select" name="vSFORVERCONMUNIINT">
														<option value="Sim" <?php if ($vROBJETO['FORVERCONMUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORVERCONMUNIINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
														<option value="Raramente" <?php if ($vROBJETO['FORVERCONMUNIINT'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Descrever as esp??cies de verifica????es, controles e acompanhamentos..</label>
													<textarea class="form-control obrigatorio" name="vSFORDESCVERCONUNIINT" id="vSFORDESCVERCONUNIINT" type="text"><?= nl2br($vROBJETO['FORDESCVERCONUNIINT']); ?></textarea>
												</div>
												<div class="col-md-6">
													<label>8.4 - Emiss??o de Relat??rios</label>
													<label>Auditoria
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORAUDEMIREL" class="custom-select obrigatorio" name="vSFORAUDEMIREL">
														<option value="Sim" <?php if ($vROBJETO['FORAUDEMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORAUDEMIREL'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
														<option value="Raramente" <?php if ($vROBJETO['FORAUDEMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos ??ltimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORAUDQUANTEMIREL" id="vSFORAUDQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORAUDQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Verifica????o
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORVEREMIREL" class="custom-select obrigatorio" name="vSFORVEREMIREL">
														<option value="Sim" <?php if ($vROBJETO['FORVEREMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORVEREMIREL'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
														<option value="Raramente" <?php if ($vROBJETO['FORVEREMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos ??ltimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORVERQUANTEMIREL" id="vSFORVERQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORVERQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Acompanhamento
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORACOMEMIREL" class="custom-select obrigatorio" name="vSFORACOMEMIREL">
														<option value="Sim" <?php if ($vROBJETO['FORACOMEMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORACOMEMIREL'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
														<option value="Raramente" <?php if ($vROBJETO['FORACOMEMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos ??ltimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORACOMQUANTEMIREL" id="vSFORACOMQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORACOMQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>8.5 - O envio dos relat??rios emitidos ?? realizado/direcionado para quem
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORENVIORELQUEM" id="vSFORENVIORELQUEM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORENVIORELQUEM'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.6 - Os respons??veis pelo recebimento dos relat??rios emitidos pela Unidade..</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORRESPONRELAT" class="custom-select" name="vSFORRESPONRELAT">
														<option value="Sim" <?php if ($vROBJETO['FORRESPONRELAT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORRESPONRELAT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>8.7 - Os relat??rios ou outros atos produzidos pela Unidade de Controle..
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORENTRERELAT" class="custom-select obrigatorio" name="vSFORENTRERELAT">
														<option value="Sim" <?php if ($vROBJETO['FORENTRERELAT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORENTRERELAT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.8 - A Unidade de Controle Interno emite Normas Internas Operacionais</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFOREMISSAOINT" class="custom-select" name="vSFOREMISSAOINT">
														<option value="Sim" <?php if ($vROBJETO['FOREMISSAOINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FOREMISSAOINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Se a resposta foi sim, qual ato administrativo foi usado para implanta????o das normas
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORATOADMNORM" id="vSFORATOADMNORM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORATOADMNORM'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.9 - A Unidade de Controle Interno costuma registrar atividades em atas</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORUNIINTREGATA" class="custom-select" name="vSFORUNIINTREGATA">
														<option value="Sim" <?php if ($vROBJETO['FORUNIINTREGATA'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORUNIINTREGATA'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Se a resposta foi sim, qual ato administrativo foi usado para implanta????o das normas
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORTIPOUNIINTREGATA" id="vSFORTIPOUNIINTREGATA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTIPOUNIINTREGATA'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.10 - A Unidade de Controle Interno costuma participar de reuni??es setoriais</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORUNIINTREUNIAO" class="custom-select" name="vSFORUNIINTREUNIAO">
														<option value="Sim" <?php if ($vROBJETO['FORUNIINTREUNIAO'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORUNIINTREUNIAO'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Tente explicar um pouco sobre a frequ??ncia, setores e quais esp??cies de reuni??es
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSFORFREQREUNIAOUNIINT" name="vSFORFREQREUNIAOUNIINT" rows="3"><?= nl2br($vROBJETO['FORFREQREUNIAOUNIINT']); ?></textarea>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.11 - A Unidade de Controle Interno atua como auxiliar do Tribunal de Contas do seu Estado</label>
													<select title="Inscri????o Estadual" title="Inscri????o Estadual" id="vSFORATUAAUXUNIINT" class="custom-select" name="vSFORATUAAUXUNIINT">
														<option value="Sim" <?php if ($vROBJETO['FORATUAAUXUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="N??o" <?php if ($vROBJETO['FORATUAAUXUNIINT'] == 'N??o') echo "selected='selected'"; ?>>N??o</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.12 - Que ferramentas s??o utilizadas para intera????o com o TCE? Sistema informatizado, e-mail, preenchimento de relat??rios? Por favor descreva</label>
													<textarea class="form-control" id="vSFORFERRAMENTASINT" name="vSFORFERRAMENTASINT" rows="3"><?= nl2br($vROBJETO['FORFERRAMENTASINT']); ?></textarea>
												</div>
											</div>
										</div>
										<!-- Atividades -->

										<!-- Arquivos -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div class="form-group">
												<div class="area-upload">
													<label for="upload-file" class="label-upload">
														<i class="fas fa-cloud-upload-alt"></i>
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta ??rea <br />
															Formatos permitidos (PDF, Word/Doc e Excel)
														</div>
													</label>
													<input type="file" accept="*" id="upload-file" multiple />

													<div class="lista-uploads">
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>
										<!-- Arquivos -->

										<div class="form-group">
											<label class="form-check-label" for="invalidCheck3" style="color: red">
												Campos em vermelho s??o de preenchimento obrigat??rio!<br />
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>
									</div>
								</form>
								<!--end form-->
							</div>
							<!--end card-body-->
						</div>
						<!--end card-->
					</div>
					<!--end col-->

				</div>
				<!--end row-->

			</div><!-- container -->
		</div>

		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalClientesxHistorico">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Hist??rico Geral</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">??</span>
						</button>
					</div>
					<div class="modal-body" id="modal_div_ClientesxHistorico">
						<div class="row">
							<input type="hidden" id="hdn_pai_ClientesxHistorico" name="hdn_pai_ClientesxHistorico" value="<?= $vIOid; ?>">
							<input type="hidden" id="hdn_filho_ClientesxHistorico" name="hdn_filho_ClientesxHistorico" value="">
							<div class="col-md-6">
								<label>Data Contato
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Contato" name="vDCHGDATA" id="vDCHGDATA" value="" type="date">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>Tipo Contato
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vICHGTIPO" id="vICHGTIPO" class="custom-select divObrigatorio" title="Tipo Contato">
									<option value=""> ------------- </option>
									<?php
									foreach (comboTabelas('HISTORICO GERAL - TIPO') as $tabelas) : ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Status
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio" title="Status">
									<option value=""> ------------- </option>
									<?php
									foreach (comboTabelas('HISTORICO GERAL - STATUS') as $tabelas) : ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Descri????o
									<small class="text-danger font-13">*</small>
								</label>
								<textarea class="form-control divObrigatorio" id="vSCHGHISTORICO" name="vSCHGHISTORICO" rows="3"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalClientesxHistorico('modal_div_ClientesxHistorico','transactionClientesxHistorico.php', 'div_ClientesxHistorico', 'ClientesxHistorico', '<?= $vIOid; ?>');">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end page content -->
		<?php include_once '../includes/footer.php' ?>
	</div>
	<!-- end page-wrapper -->

	<!-- jQuery  -->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/js/metisMenu.min.js"></script>
	<script src="../assets/js/waves.min.js"></script>
	<script src="../assets/js/jquery.slimscroll.min.js"></script>

	<!-- Sweet-Alert  -->
	<script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
	<script src="../assets/pages/jquery.sweet-alert.init.js"></script>

	<script src="../assets/plugins/dropify/js/dropify.min.js"></script>
	<script src="../assets/pages/jquery.profile.init.js"></script>

	<script src="../assets/plugins/filter/isotope.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/masonry.pkgd.min.js"></script>
	<script src="../assets/plugins/filter/jquery.magnific-popup.min.js"></script>
	<script src="../assets/pages/jquery.gallery.inity.js"></script>
	<script src="../assets/pages/jquery.form-upload.init.js"></script>
	<script src="../assets/plugins/select2/select2.min.js"></script>

	<?php include_once '../includes/scripts_footer.php' ?>
	<!-- Cad Empresa js -->
	<script src="js/cadInformacoesPreliminares.js"></script>
	<script src="js/scriptUpload.js"></script>
</body>

</html>