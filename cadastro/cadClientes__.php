<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/
include_once __DIR__ . '/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(6);
include_once __DIR__ . '/transaction/' . $vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__ . '/../cadastro/combos/comboTabelas.php';
include_once __DIR__ . '/../rh/combos/comboUsuarios.php';
include_once __DIR__ . '/combos/comboEstados.php';
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
<?php if ($vIOid > 0) {
    ?>

<body
	onload="mostrarIE('<?= $vROBJETO['CLIISENTAIE']; ?>');
				  exibirCidades('<?= $vRENDERECO['ESTCODIGO']; ?>', '<?= $vRENDERECO['CIDCODIGO']; ?>', 'div_cidade', 'vHCIDCODIGO');">
	<?php
} else {
        ?>

	<body>
		<?php
    } ?>

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

									<form class="form-parsley" action="#" method="post"
										name="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>"
										id="form<?= $vAConfiguracaoTela['MENTITULOFUNC']; ?>"
										enctype="multipart/form-data">
										<input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO"
											id="vI<?= $vAConfiguracaoTela['MENPREFIXO']; ?>CODIGO"
											value="<?php echo $vIOid; ?>" />
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if (isset($_GET['method'])) {
        echo $_GET['method'];
    } else {
        echo "insert";
    } ?>" />
										<input type="hidden" name="vHTABELA" id="vHTABELA"
											value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>" />
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO"
											value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>" />
										<input type="hidden" name="vHURL" id="vHURL"
											value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>" />
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1" />
										<input type="hidden" name="vHMENCODIGO" id="vHMENCODIGO"
											value="<?= $vAConfiguracaoTela['MENCODIGO']; ?>" />


										<!-- Nav tabs -->
										<ul class="nav nav-tabs" role="tablist">
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link active" data-toggle="tab" href="#home-1"
													role="tab">Dados Gerais</a>
											</li>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link" data-toggle="tab" href="#contatos" role="tab"
													onclick="listarContatos(<?php echo $vIOid; ?>);">Contatos</a>
											</li>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link" data-toggle="tab" href="#enderecos-1" role="tab"
													onclick="gerarGridJSON('transactionEnderecos.php', 'div_enderecos', 'ClientesxEnderecos', '<?= $vIOid; ?>');">Endere??os</a>
											</li>
											<?php if ($vIOid > 0) {
        ?>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab"
													onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid; ?>', '6');">Digitaliza????es/Arquivos</a>
											</li>
											<li class="nav-item waves-effect waves-light">
												<a class="nav-link" data-toggle="tab" href="#contratos" role="tab"
													onclick="gerarGridJSON('transactionClientesxContratos.php', 'div_contratos', 'ClientesxContratos', '<?= $vIOid; ?>');">Contratos</a>
											</li>
											<?php
    } ?>
										</ul>
										<!-- Nav tabs end -->

										<!-- Tab panes -->
										<div class="tab-content">
											<!-- Aba Dados Gerais -->
											<div class="tab-pane active p-3" id="home-1" role="tabpanel">
												<div class="form-group row">
													<div class="col-md-3">
														<label>Tipo Parceiro</label>
														<select name="vHTIPOPARCEIRO[]" id="vHTIPOPARCEIRO"
															title="Tipo Parceiro" class="form-control obrigatorio"
															style="width: 100%;font-size: 13px;" multiple>
															<!--<select class="form-control select2 mb-3 select2-multiple obrigatorio" style="width: 100%" multiple="multiple" data-placeholder="Selecione um ou mais tipos">-->
															<?php foreach (comboTabelas('PARCEIROS - TIPO') as $tabelas) :
                                                                if ($contArray > 0) {
                                                                    ?>
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if (in_array($tabelas['TABCODIGO'], $arrayPreMold)) {
                                                                        echo "selected='selected'";
                                                                    } ?>><?php echo $tabelas['TABDESCRICAO']; ?>
															</option>
															<?php
                                                                } else {
                                                                    ?>
															<option value="<?php echo $tabelas['TABCODIGO']; ?>">
																<?php echo $tabelas['TABDESCRICAO']; ?></option>
															<?php
                                                                }
                                                            endforeach; ?>
														</select>
													</div>
													<div class="col-md-3">
														<label>Tipo Pessoa
															<small class="text-danger font-13">*</small>
														</label>
														<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE"
															class="custom-select obrigatorio" name="vSCLITIPOCLIENTE"
															onchange="mostrarJxF(this.value);">
															<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') {
                                                                echo "selected='selected'";
                                                            } ?>>Jur??dica</option>
															<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') {
                                                                echo "selected='selected'";
                                                            } ?>>F??sica</option>
														</select>
													</div>
													<div class="col-md-3 divJuridica">
														<label>CNPJ
															<small class="text-danger font-13">*</small>
														</label>
														<input class="form-control obrigatorio" name="vSCLICNPJ"
															id="vSCLICNPJ" type="text" title="CNPJ"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLICNPJ'] : ''); ?>">
													</div>
													<div class="col-md-3 divJuridica">
														<br />
														<button type="button" class="btn btn-secondary waves-effect"
															onclick="buscarDadosReceita();">Buscar Dados Receita
															Federal</button><br>
													</div>
													<div class="col-md-2 divFisica">
														<label>CPF
															<small class="text-danger font-13">*</small>
														</label>
														<input class="form-control obrigatorio" name="vSCLICPF"
															id="vSCLICPF" type="text" title="CPF"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLICPF'] : ''); ?>"
															maxlength="14" onKeyPress="return digitos(event, this);"
															onKeyUp="mascara('CPF',this,event);">
													</div>
													<div class="col-md-2 divFisica">
														<label>Data Nascimento</label>
														<input class="form-control" title="Data Nascimento"
															name="vDCLIDATA_NASCIMENTO" id="vDCLIDATA_NASCIMENTO"
															value="<?= $vROBJETO['CLIDATA_NASCIMENTO'];  ?>"
															type="date">
													</div>
												</div>
												<div class="form-group row divJuridica">
													<div class="col-md-6">
														<label>Raz??o Social
															<small class="text-danger font-13">*</small>
														</label>
														<input class="form-control obrigatorio" name="vSCLIRAZAOSOCIAL"
															id="vSCLIRAZAOSOCIAL" type="text"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>"
															title="Raz??o Social">
													</div>
													<div class="col-md-6">
														<label>Nome Fantasia
															<small class="text-danger font-13">*</small>
														</label>
														<input class="form-control obrigatorio" name="vSCLINOMEFANTASIA"
															id="vSCLINOMEFANTASIA" type="text"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLINOMEFANTASIA'] : ''); ?>"
															title="Nome Fantasia">
													</div>
												</div>
												<div class="form-group row divFisica">
													<div class="col-md-6">
														<label>Nome
															<small class="text-danger font-13">*</small>
														</label>
														<input class="form-control obrigatorio" name="vHCLINOME"
															id="vHCLINOME" type="text"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>"
															title="Nome">
													</div>
												</div>
												<div class="form-group row divJuridica">
													<div class="col-md-6">
														<label>In??cio das Atividades</label>
														<input class="form-control" name="vDCLIDATA_INICIO_ATIVIDADES"
															id="vDCLIDATA_INICIO_ATIVIDADES"
															value="<?= $vROBJETO['CLIDATA_INICIO_ATIVIDADES'];  ?>"
															type="date">
													</div>
													<div class="col-md-6">
														<label>Situa????o Receita Federal
															<small class="text-danger font-13">*</small>
														</label>
														<select name="vICLISITUACAORECEITA" id="vICLISITUACAORECEITA"
															class="custom-select obrigatorio"
															title="Situa????o Receita Federal">
															<option value=""> ------------- </option>
															<?php
                                                            if ($vROBJETO['CLISITUACAORECEITA'] == '') {
                                                                $vROBJETO['CLISITUACAORECEITA'] = 20;
                                                            }
                                                            foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas) : ?>
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLISITUACAORECEITA'] == $tabelas['TABCODIGO']) {
                                                                echo "selected='selected'";
                                                            } ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="form-group row divJuridica">
													<div class="col-md-3">
														<label>Inscri????o Municipal</label>
														<input class="form-control" name="vSCLIIM" id="vSCLIIM"
															type="text"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : ''); ?>">
													</div>

													<div class="col-md-3">
														<label>Inscri????o Estadual</label>
														<select title="Inscri????o Estadual" title="Inscri????o Estadual"
															id="vSCLIISENTAIE" class="custom-select"
															name="vSCLIISENTAIE">
															<option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') {
                                                                echo "selected='selected'";
                                                            } ?>>N??O ISENTO INSCR. ESTADUAL</option>
															<option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') {
                                                                echo "selected='selected'";
                                                            } ?>>ISENTO INSCR. ESTADUAL</option>
														</select>
													</div>
													<!-- estadual -->
													<div class="col-sm-3 divIE" id="divIE">
														<label>N?? Insc. Estadual</label>
														<input class="form-control" title="N?? Insc. Estadual"
															name="vSCLIIE" id="vSCLIIE" type="text"
															value="<?= ($vIOid > 0 ? $vROBJETO['CLIIE'] : ''); ?>">
													</div>

												</div>
												<div class="form-group row divJuridica">
													<div class="col-md-3">
														<label>Optante pelo simples nacional?</label>
														<select title="Estado Civil" id="vSCLIOPTANTESIMPLESNACIONAL"
															class="custom-select" name="vSCLIOPTANTESIMPLESNACIONAL"
															onchange="mostraCampo('E', this.value);">
															<option value=""> ------------- </option>
															<option value="S" <?php if ($vROBJETO['CLIOPTANTESIMPLESNACIONAL'] == 'S') {
                                                                echo "selected='selected'";
                                                            } ?>>Sim</option>
															<option value="N" <?php if ($vROBJETO['CLIOPTANTESIMPLESNACIONAL'] == 'N') {
                                                                echo "selected='selected'";
                                                            } ?>>N??o</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<div class="col-md-6">
														<label>Representante</label>
														<select name="vICLIRESPONSAVEL" id="vICLIRESPONSAVEL"
															class="custom-select" title="Representante">
															<option value=""> ------------- </option>
															<?php foreach (comboUsuarios() as $usuarios) : ?>
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['CLIRESPONSAVEL'] == $usuarios['USUCODIGO']) {
                                                                echo "selected='selected'";
                                                            } ?>><?php echo $usuarios['USUNOME']; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>

											<!-- Aba Contatos -->
											<div class="tab-pane p-3" id="contatos" role="tabpanel">
												<div class="accordion" id="reformaSim">
													<div class="card border mb-0 shadow-none">
														<div class="card-header p-0" id="headingOne">
															<h5 class="my-1">
																<button class="btn btn-link text-dark" type="button"
																	data-toggle="collapse" data-target="#collapseOne"
																	aria-expanded="true" aria-controls="collapseOne">
																	Principal
																</button>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show"
															aria-labelledby="headingOne"
															data-parent="#accordionExample">
															<div class="card-body">
																<p class="mb-0 text-muted">
																	<input type="hidden" name="vHCONPRINCIPAL"
																		id="vHCONPRINCIPAL" value="S" />
																<div class="form-group row">
																	<div class="col-sm-6">
																		<label>Contato Respons??vel</label>
																		<input class="form-control" title="Contato"
																			name="vSCLICONTATO" id="vSCLICONTATO"
																			type="text"
																			value="<?= (isset($vROBJETO['CLICONTATO']) ? $vROBJETO['CLICONTATO'] : ''); ?>">
																	</div>
																	<div class="col-sm-3">
																		<label>Telefone</label>
																		<input type="text" id="vSCLIFONE"
																			name="vSCLIFONE" class="form-control"
																			maxlength="14" title="Telefone Principal"
																			value="<?= (isset($vROBJETO['CLIFONE']) ? $vROBJETO['CLIFONE'] : ''); ?>"
																			onKeyPress="return digitos(event, this);"
																			onkeyup="mascara('TEL', this, event)" />
																	</div>
																	<div class="col-sm-3">
																		<label>Telefone Celular</label>
																		<input class="form-control" name="vSCLICELULAR"
																			id="vSCLICELULAR" type="text"
																			title="Telefone Celular"
																			value="<?= (isset($vROBJETO['CLICELULAR']) ? $vROBJETO['CLICELULAR'] : ''); ?>"
																			maxlength="15"
																			onKeyPress="return digitos(event, this);"
																			onkeyup="mascara('TEL', this, event)">
																	</div>
																</div>
																<div class="form-group row">
																	<div class="col-sm-6">
																		<label>E-mail</label>
																		<input class="form-control" title="E-mail"
																			name="vSCLIEMAIL" id="vSCLIEMAIL"
																			type="email"
																			value="<?= (isset($vROBJETO['CLIEMAIL']) ? $vROBJETO['CLIEMAIL'] : ''); ?>">
																	</div>
																	<div class="col-sm-6">
																		<label>Site</label>
																		<input class="form-control" name="vSCLISITE"
																			id="vSCLISITE" type="text"
																			value="<?= (isset($vROBJETO['CLISITE']) ? $vROBJETO['CLISITE'] : '');  ?>">
																	</div>
																</div>
																<div class="form-group row">
																	<div class="col-sm-12">
																		<label>Instru????es especiais para efetuar contato
																			com cliente</label>
																		<textarea class="form-control"
																			id="vSCLIOBSESPCONTATO"
																			name="vSCLIOBSESPCONTATO"
																			rows="3"><?= (isset($vROBJETO['CLIOBSESPCONTATO']) ? $vROBJETO['CLIOBSESPCONTATO'] : ''); ?></textarea>
																	</div>
																</div>
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="accordion" id="reformaSim2">
													<div class="card border mb-0 shadow-none">
														<div class="card-header p-0" id="headingOne">
															<h5 class="my-1">
																<button class="btn btn-link text-dark" type="button"
																	data-toggle="collapse" data-target="#collapseOne"
																	aria-expanded="true" aria-controls="collapseOne">
																	Demais Contatos/Consulentes
																</button>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show"
															aria-labelledby="headingOne"
															data-parent="#accordionExample">
															<div class="card-body">
																<p class="mb-0 text-muted"></p>
																<div class="form-group row">
																	<div id="div_contatos"
																		class="table-responsive mt-5">
																		<button type="button"
																			onclick="exibirFormContato();"
																			class="btn btn-primary px-4 btn-rounded float-right mt-0 mb-3">+
																			Novo Registro</button>
																		<table id="datatable-buttons"
																			class="table table-striped table-bordered dt-responsive nowrap"
																			style="overflow: auto; width: 100%">
																			<thead>
																				<tr class="text-center">
																					<th>Acesso</th>
																					<th>Nome</th>
																					<th>E-mail</th>
																					<th>Telefone</th>
																					<th>Celular</th>
																					<th>Cargo</th>
																					<th>Setor/Lota????o</th>
																					<th>Data Inclus??o</th>
																					<th>A????o</th>
																				</tr>
																			</thead>
																			<tbody id="tbody_contatos">
																			</tbody>
																			<tfoot id="tfoot_contatos">
																			</tfoot>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- Aba Contatos end -->

											<!-- Aba Dados Endere??o -->
											<div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
												<div class="accordion" id="reformaSim3">
													<div class="card border mb-0 shadow-none">
														<div class="card-header p-0" id="headingOne">
															<h5 class="my-1">
																<button class="btn btn-link text-dark" type="button"
																	data-toggle="collapse" data-target="#collapseOne"
																	aria-expanded="true" aria-controls="collapseOne">
																	Principal
																</button>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show"
															aria-labelledby="headingOne"
															data-parent="#accordionExample">
															<div class="card-body">
																<p class="mb-0 text-muted">
																	<input type="hidden" name="vHENDCODIGO"
																		id="vHENDCODIGO"
																		value="<?= $vRENDERECO['ENDCODIGO']; ?>" />
																	<input type="hidden" name="vHENDPADRAO"
																		id="vHENDPADRAO" value="S" />
																<div class="form-group row">
																	<div class="col-sm-3">
																		<label>CEP</label>
																		<input class="form-control" title="CEP"
																			name="vHENDCEP" id="vHENDCEP" type="text"
																			value="<?= (isset($vRENDERECO['ENDCEP']) ? $vRENDERECO['ENDCEP'] : '');  ?>"
																			onblur="buscarCEP(this.value);">
																	</div>
																	<div class="col-sm-3">
																		<label>Bairro</label>
																		<input class="form-control" title="Bairro"
																			name="vHENDBAIRRO" id="vHENDBAIRRO"
																			type="text"
																			value="<?= (isset($vRENDERECO['ENDBAIRRO']) ? $vRENDERECO['ENDBAIRRO'] : '');  ?>">
																	</div>
																</div>
																<div class="form-group row">
																	<div class="col-sm-4">
																		<label>Endere??o</label>
																		<input class="form-control" title="Endere??o"
																			name="vHENDLOGRADOURO" id="vHENDLOGRADOURO"
																			type="text"
																			value="<?= (isset($vRENDERECO['ENDLOGRADOURO']) ? $vRENDERECO['ENDLOGRADOURO'] : ''); ?>">
																	</div>
																	<div class="col-sm-2">
																		<label>N??</label>
																		<input class="form-control"
																			name="vHENDNROLOGRADOURO"
																			id="vHENDNROLOGRADOURO" type="text"
																			value="<?= (isset($vRENDERECO['ENDNROLOGRADOURO']) ? $vRENDERECO['ENDNROLOGRADOURO'] : ''); ?>">
																	</div>
																	<div class="col-sm-2">
																		<label>Complemento</label>
																		<input class="form-control"
																			name="vHENDCOMPLEMENTO"
																			id="vHENDCOMPLEMENTO" type="text"
																			value="<?= (isset($vRENDERECO['ENDCOMPLEMENTO']) ? $vRENDERECO['ENDCOMPLEMENTO'] : ''); ?>">
																	</div>
																	<div class="col-sm-2">
																		<label>Estado</label>
																		<select title="Estado" id="vHESTCODIGO"
																			class="custom-select" name="vHESTCODIGO"
																			onchange="exibirCidades(this.value, '', 'div_cidade', 'vHCIDCODIGO');">
																			<?php foreach (comboEstados() as $tabelas) : ?>
																			<option
																				value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ((isset($vRENDERECO['ESTCODIGO']) ? $vRENDERECO['ESTCODIGO'] : '') == $tabelas['ESTCODIGO']) {
                                                                echo "selected='selected'";
                                                            } ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<div id="div_cidade"></div>
																	</div>
																</div>
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="accordion" id="reformaSim4">
													<div class="card border mb-0 shadow-none">
														<div class="card-header p-0" id="headingOne">
															<h5 class="my-1">
																<button class="btn btn-link text-dark" type="button"
																	data-toggle="collapse" data-target="#collapseOne"
																	aria-expanded="true" aria-controls="collapseOne">
																	Demais Endere??os
																</button>
															</h5>
														</div>
														<div id="collapseOne" class="collapse show"
															aria-labelledby="headingOne"
															data-parent="#accordionExample">
															<div class="card-body">
																<p class="mb-0 text-muted">
																<div class="form-group row">
																	<div id="div_enderecos" class="table-responsive">
																	</div>
																</div>
																</p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- Aba Dados GED -->
											<div class="tab-pane p-3" id="ged-1" role="tabpanel">
												<div class="form-group">
													<div class="area-upload">
														<label for="upload-file" class="label-upload">
															<i class="fas fa-cloud-upload-alt"></i>
															<div class="texto">Clique ou arraste o(s) arquivo(s) para
																esta ??rea <br />
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

											<div class="tab-pane p-3" id="contratos" role="tabpanel">
												<div class="form-group row">
													<div id="div_contratos" class="table-responsive"></div>
												</div>
											</div>

											<div class="form-group">
												<label class="form-check-label" for="invalidCheck3" style="color: red">
													Campos em vermelho s??o de preenchimento obrigat??rio!<br />
												</label>
											</div>
											<?php include('../includes/botoes_cad_novo.php'); ?>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div><!-- container -->
			</div>

			<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
				aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalClientesxHistorico">
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
								<input type="hidden" id="hdn_pai_ClientesxHistorico" name="hdn_pai_ClientesxHistorico"
									value="<?= $vIOid; ?>">
								<input type="hidden" id="hdn_filho_ClientesxHistorico"
									name="hdn_filho_ClientesxHistorico" value="">
								<div class="col-md-6">
									<label>Data Contato
										<small class="text-danger font-13">*</small>
									</label>
									<input class="form-control divObrigatorio" title="Data Contato" name="vDCHGDATA"
										id="vDCHGDATA" value="" type="date">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Tipo Contato
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGTIPO" id="vICHGTIPO" class="custom-select divObrigatorio"
										title="Tipo Contato">
										<option value=""> ------------- </option>
										<?php
                                        foreach (comboTabelas('HISTORICO GERAL - TIPO') as $tabelas) : ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>">
											<?php echo $tabelas['TABDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6">
									<label>Status
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio"
										title="Status">
										<option value=""> ------------- </option>
										<?php
                                        foreach (comboTabelas('HISTORICO GERAL - STATUS') as $tabelas) : ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>">
											<?php echo $tabelas['TABDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Descri????o
										<small class="text-danger font-13">*</small>
									</label>
									<textarea class="form-control divObrigatorio" id="vSCHGHISTORICO"
										name="vSCHGHISTORICO" rows="3"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mt-3">
									<button type="button"
										class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right"
										onclick="salvarModalClientesxHistorico('modal_div_ClientesxHistorico','transactionClientesxHistorico.php', 'div_ClientesxHistorico', 'ClientesxHistorico', '<?= $vIOid; ?>');">Salvar</button>
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
			aria-hidden="true" id="modalClientesxEnderecos">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Endere??o</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">??</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="form-parsley" action="#" method="post" name="formEnderecos" id="formEnderecos">
							<div class="form-group row">
								<input type="hidden" id="vIENDCODIGO" name="vIENDCODIGO" value="">
								<div class="col-md-6">
									<label>CEP</label>
									<input name="vSENDCEP" id="vSENDCEP" class="form-control  divObrigatorio"
										maxlength="9" onKeyPress="return digitos(event, this);"
										onKeyUp="mascara('CEP', this, event)" title="CEP" type="text">
								</div>
								<div class="col-md-6">
									<label>Tipo Endereco</label>
									<select id="vITABCODIGO" name="vITABCODIGO" class=" custom-select divObrigatorio"
										title="Tipo Endereco">
										<?php foreach (comboTabelas('ENDERECOS - TIPO') as $tipo) : ?>
										<option value="<?php echo $tipo['TABCODIGO'] ?>">
											<?php echo $tipo['TABDESCRICAO'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-8">
									<label>Logradouro</label>
									<input name="vSENDLOGRADOURO" id="vSENDLOGRADOURO"
										class="form-control  divObrigatorio" maxlength="50" title="Logradouro"
										type="text">
								</div>
								<div class="col-md-4">
									<label>N??mero</label>
									<input name="vSENDNROLOGRADOURO" id="vSENDNROLOGRADOURO"
										class="form-control  divObrigatorio" maxlength="50" title="N??mero" type="text"
										onKeyPress="return digitos(event, this)">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-8">
									<label>Complemento</label>
									<input name="vSENDCOMPLEMENTO" id="vSENDCOMPLEMENTO" class="form-control"
										maxlength="200" type="text">
								</div>
								<div class="col-md-4">
									<label>Bairro</label>
									<input name="vSENDBAIRRO" id="vSENDBAIRRO" class="form-control  divObrigatorio"
										maxlength="100" value="" title="Bairro" type="text">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">
									<label>Estado</label>
									<select name="vIESTCODIGO" id="vIESTCODIGO" class=" custom-select divObrigatorio"
										title="Estado"
										onchange="exibirCidades(this.value, '', 'div_cidade_modal', 'vICIDCODIGO');">
										<?php foreach (comboEstados() as $tabelas) : ?>
										<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vROBJETO['ESTCODIGO'] == $tabelas['ESTCODIGO']) {
                                            echo "selected='selected'";
                                        } ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6">
									<div id="div_cidade_modal"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12 mt-3">
									<button type="button"
										class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right"
										onclick="salvarModalClientesxEnderecos();">Salvar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
			aria-hidden="true" id="modalClientesxContatos">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Contato</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">??</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="form-parsley" action="#" method="post" name="formClientesxContatos"
							id="formClientesxContatos">
							<input type="hidden" id="vHMCONCODIGO" name="vHMCONCODIGO" value="">
							<div class="form-group row">
								<div class="col-sm-12">
									<label>Contato</label>
									<input class="form-control divObrigatorio" title="Contato" name="vHMCONNOME"
										id="vHMCONNOME" type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label>E-mail</label>
									<input class="form-control divObrigatorio" title="E-mail" name="vHMCONEMAIL"
										id="vHMCONEMAIL" type="email" value="">
								</div>
								<div class="col-sm-6">
									<label>Senha Portal</label><a href="javascript:void(0);" id="gerarSenhaBtn"> (Gerar
										senha)</a>
									<input class="form-control" title="Senha Portal" name="vHMCONSENHA" id="vHMCONSENHA"
										type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label>Telefone</label>
									<input type="text" id="vHMCONFONE" name="vHMCONFONE" class="form-control"
										maxlength="14" title="Telefone Principal" value=""
										onKeyPress="return digitos(event, this);"
										onkeyup="mascara('TEL', this, event)" />
								</div>
								<div class="col-sm-6">
									<label>Telefone Celular</label>
									<input type="text" id="vHMCONCELULAR" name="vHMCONCELULAR" class="form-control"
										maxlength="15" title="Telefone Celular" value=""
										onKeyPress="return digitos(event, this);"
										onkeyup="mascara('TEL', this, event)" />
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label>Cargo</label>
									<input type="text" id="vHMCONCARGO" name="vHMCONCARGO" class="form-control"
										maxlength="150" title="Cargo" value="" />
								</div>
								<div class="col-sm-6">
									<label>Setor/Lota????o</label>
									<input type="text" id="vHMCONSETOR" name="vHMCONSETOR" class="form-control"
										maxlength="150" title="Setor/Lota????o" value="" />
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12 mt-3">
									<button type="button"
										class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right"
										onclick="salvarContato()">Salvar</button>
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
		<script src="js/cadClientes.js"></script>
		<script>
		$(document).ready(function() {
			$('#vHTIPOPARCEIRO').select2();
			$("#vHTIPOPARCEIRO").addClass("obrigatorio");
			$("#vHTIPOPARCEIRO").addClass("form-control");
			$("#vHTIPOPARCEIRO").addClass("custom-select");
			$("#vHTIPOPARCEIRO").select2({
				height: '38px !important'
			});
		});
		</script>
		<script src="js/scriptUpload.js"></script>
	</body>

</html>