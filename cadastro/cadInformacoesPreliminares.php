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
											<a class="nav-link" data-toggle="tab" href="#contatos" role="tab">Responsável</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#enderecos-1" role="tab">Membros</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#contratos" role="tab">Atividades</a>
										</li>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid; ?>', '6');">Digitalizações/Arquivos</a>
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
													<label>2 - Número de habitantes do município
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNUMHABMUNICIPIO" id="vSFORNUMHABMUNICIPIO" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNUMHABMUNICIPIO'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>3 - Valor do orçamento anual
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vMFORVALORCANUAL" id="vMFORVALORCANUAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORVALORCANUAL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>4 - Número de servidores (inclui agentes políticos e ccs)</label>
													<input class="form-control" name="vSFORNUMSERVIDORES" id="vSFORNUMSERVIDORES" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNUMSERVIDORES'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>5 - Descrever a Lei que instituiu o Sistema de Controle Interno e suas alterações, anexando cópia</label>
													<input class="form-control" name="vSFORDESCLEI" id="vSFORDESCLEI" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORDESCLEI'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>6 - A Unidade de Controle Interno possui Regimento Interno?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORPOSREGINTERNO" class="custom-select obrigatorio" name="vSFORPOSREGINTERNO">
														<option value="S" <?php if ($vROBJETO['POSREGINTERNO'] == 'S') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['POSREGINTERNO'] == 'N') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Aba Dados Gerais -->

										<!-- Responsavel -->
										<div class="tab-pane p-3" id="contatos" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>7.1 - O responsável pelo Sistema de Controle Interno ocupa o cargo de
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORCARGORESPINT" class="custom-select obrigatorio" name="vSFORCARGORESPINT">
														<option value="Presidente" <?php if ($vROBJETO['CARGORESPINT'] == 'Presidente') echo "selected='selected'"; ?>>Presidente</option>
														<option value="Coordenador" <?php if ($vROBJETO['CARGORESPINT'] == 'Coordenador') echo "selected='selected'"; ?>>Coordenador</option>
														<option value="Diretor" <?php if ($vROBJETO['CARGORESPINT'] == 'Diretor') echo "selected='selected'"; ?>>Diretor</option>
														<option value="Chefe" <?php if ($vROBJETO['CARGORESPINT'] == 'Chefe') echo "selected='selected'"; ?>>Chefe</option>
														<option value="Outro" <?php if ($vROBJETO['CARGORESPINT'] == 'Outro') echo "selected='selected'"; ?>>Outro</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Nome do Responsável
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNOMERESPINT" id="vSFORNOMERESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNOMERESPINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>Formação
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORFORMRESPINT" class="custom-select obrigatorio" name="vSFORFORMRESPINT">
														<option value="1ª grau incompleto" <?php if ($vROBJETO['FORMRESPINT'] == '1ª grau incompleto') echo "selected='selected'"; ?>>1ª grau incompleto</option>
														<option value="1ª grau completo" <?php if ($vROBJETO['FORMRESPINT'] == '1ª grau completo') echo "selected='selected'"; ?>>1ª grau completo</option>
														<option value="2º grau incompleto" <?php if ($vROBJETO['FORMRESPINT'] == '2º grau incompleto') echo "selected='selected'"; ?>>2º grau incompleto</option>
														<option value="2º grau completo" <?php if ($vROBJETO['FORMRESPINT'] == '2º grau completo') echo "selected='selected'"; ?>>2º grau completo</option>
														<option value="Nível Superior. Descrever:" <?php if ($vROBJETO['FORMRESPINT'] == 'Nível Superior. Descrever:') echo "selected='selected'"; ?>>Nível Superior. Descrever:</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Insira o Superior</label>
													<input class="form-control" name="vSFORSUPRESPINT" id="vSFORSUPRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORSUPRESPINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Tempo de Experiência no Controle Interno</label>
													<input class="form-control" name="vSFORTEMPEXPRESPINT" id="vSFORTEMPEXPRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTEMPEXPRESPINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Tem dedicação exclusiva no Controle Interno
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORDEDEXCRESPINT" class="custom-select obrigatorio" name="vSFORDEDEXCRESPINT">
														<option value="Sim" <?php if ($vROBJETO['DEDEXCRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['DEDEXCRESPINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Qual cargo que ocupa no Município?</label>
													<input class="form-control" name="vSFORCARGOMUNRESPINT" id="vSFORCARGOMUNRESPINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORCARGOMUNRESPINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>É servidor de Carreira?</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORSERCARRRESPINT" class="custom-select" name="vSFORSERCARRRESPINT">
														<option value="Sim" <?php if ($vROBJETO['SERCARRRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['SERCARRRESPINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Está em estágio probatório?</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORESTPROBRESPINT" class="custom-select" name="vSFORESTPROBRESPINT">
														<option value="Sim" <?php if ($vROBJETO['ESTPROBRESPINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['ESTPROBRESPINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Realizou algum curso de atualização na área de Controle Interno da Administração..</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORREACURRESPINT" class="custom-select" name="vSFORREACURRESPINT">
														<option value="Nenhum" <?php if ($vROBJETO['REACURRESPINT'] == 'Nenhum') echo "selected='selected'"; ?>>Nenhum</option>
														<option value="Um" <?php if ($vROBJETO['REACURRESPINT'] == 'Um') echo "selected='selected'"; ?>>Um</option>
														<option value="Dois à quatro" <?php if ($vROBJETO['REACURRESPINT'] == 'Dois à quatro') echo "selected='selected'"; ?>>Dois à quatro</option>
														<option value="Cinco ou mais" <?php if ($vROBJETO['REACURRESPINT'] == 'Cinco ou mais') echo "selected='selected'"; ?>>Cinco ou mais</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Responsavel -->

										<!-- Membros -->
										<div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>7.2 – Dos Membros do Sistema de Controle Interno</label>
													<label>Nome do Membro
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORNOMEMEMBINT" id="vSFORNOMEMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORNOMEMEMBINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Formação
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORFORMEMBINT" class="custom-select obrigatorio" name="vSFORFORMEMBINT">
														<option value="1ª grau incompleto" <?php if ($vROBJETO['FORMEMBINT'] == '1ª grau incompleto') echo "selected='selected'"; ?>>1ª grau incompleto</option>
														<option value="1ª grau completo" <?php if ($vROBJETO['FORMEMBINT'] == '1ª grau completo') echo "selected='selected'"; ?>>1ª grau completo</option>
														<option value="2º grau incompleto" <?php if ($vROBJETO['FORMEMBINT'] == '2º grau incompleto') echo "selected='selected'"; ?>>2º grau incompleto</option>
														<option value="2º grau completo" <?php if ($vROBJETO['FORMEMBINT'] == '2º grau completo') echo "selected='selected'"; ?>>2º grau completo</option>
														<option value="Nível Superior. Descrever:" <?php if ($vROBJETO['FORMEMBINT'] == 'Nível Superior. Descrever:') echo "selected='selected'"; ?>>Nível Superior. Descrever:</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Insira o Superior</label>
													<input class="form-control obrigatorio" name="vSFORSUPMEMBINT" id="vSFORSUPMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORSUPMEMBINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Tempo de Experiência no Controle Interno</label>
													<input class="form-control" name="vSFORTEMPEXPMEMBINT" id="vSFORTEMPEXPMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTEMPEXPMEMBINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Tem dedicação exclusiva no Controle Interno</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORDEDEXCMEMBINT" class="custom-select" name="vSFORDEDEXCMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['DEDEXCMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['DEDEXCMEMBINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Qual cargo que ocupa no Município</label>
													<input class="form-control" name="vSFORCARGOMUNMEMBINT" id="vSFORCARGOMUNMEMBINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORCARGOMUNMEMBINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>É servidor de Carreira</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORSERCARRMEMBINT" class="custom-select" name="vSFORSERCARRMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['SERCARRMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['SERCARRMEMBINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Está em estágio probatório</label>
													<select title="Tipo Pessoa" id="vSFORESTPROBMEMBINT" class="custom-select obrigatorio" name="vSFORESTPROBMEMBINT">
														<option value="Sim" <?php if ($vROBJETO['ESTPROBMEMBINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['ESTPROBMEMBINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Realizou algum curso de atualização na área de Controle Interno da Administração..</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORREACURMEMBINT" class="custom-select" name="vSFORREACURMEMBINT">
														<option value="Nenhum" <?php if ($vROBJETO['REACURMEMBINT'] == 'Nenhum') echo "selected='selected'"; ?>>Nenhum</option>
														<option value="Um" <?php if ($vROBJETO['REACURMEMBINT'] == 'Um') echo "selected='selected'"; ?>>Um</option>
														<option value="Dois à quatro" <?php if ($vROBJETO['REACURMEMBINT'] == 'Dois à quatro') echo "selected='selected'"; ?>>Dois à quatro</option>
														<option value="Cinco ou mais" <?php if ($vROBJETO['REACURMEMBINT'] == 'Cinco ou mais') echo "selected='selected'"; ?>>Cinco ou mais</option>
													</select>
												</div>
											</div>
										</div>
										<!-- Membros -->

										<!-- Atividades -->
										<div class="tab-pane p-3" id="contratos" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-6">
													<label>8 – Das atividades da Unidade de Controle Interno</label>
													<label>8.1 - A Unidade de Controle Interno possui um planejamento/programa de trabalho com a descrição das suas atividades?
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORPOSSPLAUNIINT" id="vSFORPOSSPLAUNIINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORPOSSPLAUNIINT'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>8.2 Procedimentos de Auditoria
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORPROAUDUNIINT" id="vSFORPROAUDUNIINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORPROAUDUNIINT'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>Descrever o resumo das auditorias realizadas nos últimos 06 meses
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control obrigatorio" name="vSFORDESCRESUNIINT" id="vSFORDESCRESUNIINT" type="text"><?= $fill['FORFREQREUNIAOUNIINT'] ?></textarea>
												</div>
												<div class="col-md-6">
													<label>8.3 - Verificações, Controles e Acompanhamentos</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORVERCONMUNIINT" class="custom-select" name="vSFORVERCONMUNIINT">
														<option value="Sim" <?php if ($vROBJETO['VERCONMUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['VERCONMUNIINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
														<option value="Raramente" <?php if ($vROBJETO['VERCONMUNIINT'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Descrever as espécies de verificações, controles e acompanhamentos..</label>
													<textarea class="form-control obrigatorio" name="vSFORDESCVERCONUNIINT" id="vSFORDESCVERCONUNIINT" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORDESCVERCONUNIINT'] : ''); ?>"></textarea>
												</div>
												<div class="col-md-6">
													<label>8.4 - Emissão de Relatórios</label>
													<label>Auditoria
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORAUDEMIREL" class="custom-select obrigatorio" name="vSFORAUDEMIREL">
														<option value="Sim" <?php if ($vROBJETO['AUDEMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['AUDEMIREL'] == 'Não') echo "selected='selected'"; ?>>Não</option>
														<option value="Raramente" <?php if ($vROBJETO['AUDEMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos últimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORAUDQUANTEMIREL" id="vSFORAUDQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORAUDQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Verificação
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORVEREMIREL" class="custom-select obrigatorio" name="vSFORVEREMIREL">
														<option value="Sim" <?php if ($vROBJETO['VEREMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['VEREMIREL'] == 'Não') echo "selected='selected'"; ?>>Não</option>
														<option value="Raramente" <?php if ($vROBJETO['VEREMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos últimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORVERQUANTEMIREL" id="vSFORVERQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORVERQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>Acompanhamento
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORACOMEMIREL" class="custom-select obrigatorio" name="vSFORACOMEMIREL">
														<option value="Sim" <?php if ($vROBJETO['ACOMEMIREL'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['ACOMEMIREL'] == 'Não') echo "selected='selected'"; ?>>Não</option>
														<option value="Raramente" <?php if ($vROBJETO['ACOMEMIREL'] == 'Raramente') echo "selected='selected'"; ?>>Raramente</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Nos últimos 06 meses, quantos foram emitidos</label>
													<input class="form-control obrigatorio" name="vSFORACOMQUANTEMIREL" id="vSFORACOMQUANTEMIREL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORACOMQUANTEMIREL'] : ''); ?>">
												</div>
												<div class="col-md-6">
													<label>8.5 - O envio dos relatórios emitidos é realizado/direcionado para quem
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORENVIORELQUEM" id="vSFORENVIORELQUEM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORENVIORELQUEM'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.6 - Os responsáveis pelo recebimento dos relatórios emitidos pela Unidade..</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORRESPONRELAT" class="custom-select" name="vSFORRESPONRELAT">
														<option value="Sim" <?php if ($vROBJETO['RESPONRELAT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['RESPONRELAT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>8.7 - Os relatórios ou outros atos produzidos pela Unidade de Controle..
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSFORENTRERELAT" class="custom-select obrigatorio" name="vSFORENTRERELAT">
														<option value="Sim" <?php if ($vROBJETO['ENTRERELAT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['ENTRERELAT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.8 - A Unidade de Controle Interno emite Normas Internas Operacionais</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFOREMISSAOINT" class="custom-select" name="vSFOREMISSAOINT">
														<option value="Sim" <?php if ($vROBJETO['EMISSAOINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['EMISSAOINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Se a resposta foi sim, qual ato administrativo foi usado para implantação das normas
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORATOADMNORM" id="vSFORATOADMNORM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORATOADMNORM"'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.9 - A Unidade de Controle Interno costuma registrar atividades em atas</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORUNIINTREGATA" class="custom-select" name="vSFORUNIINTREGATA">
														<option value="Sim" <?php if ($vROBJETO['UNIINTREGATA'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['UNIINTREGATA'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Se a resposta foi sim, qual ato administrativo foi usado para implantação das normas
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSFORTIPOUNIINTREGATA" id="vSFORTIPOUNIINTREGATA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['FORTIPOUNIINTREGATA"'] : ''); ?>">
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.10 - A Unidade de Controle Interno costuma participar de reuniões setoriais</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORUNIINTREUNIAO" class="custom-select" name="vSFORUNIINTREUNIAO">
														<option value="Sim" <?php if ($vROBJETO['UNIINTREUNIAO'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['UNIINTREUNIAO'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Tente explicar um pouco sobre a frequência, setores e quais espécies de reuniões
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSFORFREQREUNIAOUNIINT" name="vSFORFREQREUNIAOUNIINT" rows="3"><?= $fill['FORFREQREUNIAOUNIINT'] ?></textarea>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.11 - A Unidade de Controle Interno atua como auxiliar do Tribunal de Contas do seu Estado</label>
													<select title="Inscrição Estadual" title="Inscrição Estadual" id="vSFORATUAAUXUNIINT" class="custom-select" name="vSFORATUAAUXUNIINT">
														<option value="Sim" <?php if ($vROBJETO['ATUAAUXUNIINT'] == 'Sim') echo "selected='selected'"; ?>>Sim</option>
														<option value="Não" <?php if ($vROBJETO['ATUAAUXUNIINT'] == 'Não') echo "selected='selected'"; ?>>Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>8.12 - Que ferramentas são utilizadas para interação com o TCE? Sistema informatizado, e-mail, preenchimento de relatórios? Por favor descreva</label>
													<textarea class="form-control" id="vSFORFERRAMENTASINT" name="vSFORFERRAMENTASINT" rows="3"><?= $fill['FORFERRAMENTASINT'] ?></textarea>
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
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta área <br />
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
												Campos em vermelho são de preenchimento obrigatório!<br />
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
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Histórico Geral</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
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
								<label>Descrição
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