<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2025);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once __DIR__.'/combos/comboPosicoesPadroes.php';
include_once __DIR__.'/combos/comboPrioridades.php';
include_once __DIR__.'/../cadastro/combos/comboAtividades.php';
include_once __DIR__.'/transaction/transactionAtendimentoxPlanoTrabalho.php';

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
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="../assets/css/stylesUpload.css"/>
    </head>
	<?php if ($vIOid > 0){ ?>
    <body onload="exibirClientexContatos('<?= $vROBJETO['CLICODIGO'];?>', '', '');">
	<?php } else { ?>
	<body>
	<?php } ?>

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

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">
                                        <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
										<input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>
										<input type="hidden" name="vHTABELA" id="vHTABELA" value="<?= $vAConfiguracaoTela['MENTABELABANCO'] ?>"/>
										<input type="hidden" name="vHPREFIXO" id="vHPREFIXO" value="<?= $vAConfiguracaoTela['MENPREFIXO']; ?>"/>
										<input type="hidden" name="vHURL" id="vHURL" value="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>"/>
										<input type="hidden" name="vIEMPCODIGO" id="vIEMPCODIGO" value="1"/>
										<input type="hidden" name="vHMENCODIGO" id="vHMENCODIGO" value="<?= $vAConfiguracaoTela['MENCODIGO']; ?>"/>

                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
										<?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '2025');">Digitalizações/Arquivos</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#posicoes" role="tab" onclick="gerarGridJSON('transactionAtendimentoxHistoricos.php', 'div_historico', 'AtendimentoxHistoricos', '<?= $vIOid;?>');">Posições/Históricos</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#subAtendimentos" role="tab" onclick="gerarGridJSON('transactionAtendimentoxPlanoTrabalho.php', 'div_subAtendimentoxPlanoTrabalho', 'AtendimentoxPlanoTrabalho', '<?= $vIOid;?>');">Plano de Trabalho</a>
                                        </li>
										<!-- outra versão
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSON('transactionClientesxGED.php', 'div_ged', 'ClientesxGED', '<?= $vIOid;?>');">Despesas</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSON('transactionClientesxGED.php', 'div_ged', 'ClientesxGED', '<?= $vIOid;?>');">Faturamento</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#documentacao" role="tab" onclick="gerarGridJSON('transactionClientesxGED.php', 'div_ged', 'ClientesxGED', '<?= $vIOid;?>');">Documentação Apoio</a>
                                        </li> -->
                                        <?php } ?>
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-5">
													<label>Cliente
														<small class="text-danger font-13">*</small>
													</label>
													<input title="Cliente" type="text" name="vHCLIENTE" id="vHCLIENTE" class="form-control obrigatorio autocomplete" data-hidden="#vICLICODIGO" value="<?php echo $vROBJETO['CLINOMEFANTASIA']; ?>" onblur="validarCliente();"/>
													<span id="aviso-cliente" style="color: red;font-size: 11px; display: none;">O Cliente não foi selecionado corretamente!</span>
													<input type="hidden" name="vICLICODIGO" id="vICLICODIGO" value="<?php if(isset($vIOid)) echo $vROBJETO['CLICODIGO']; ?>"/>
												</div>
												<div class="col-md-1 btnLimparCliente">
													<br/>
													<button type="button" class="btn btn-danger waves-effect" onclick="removerCliente();">Limpar</button><br>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<div id="divContatos"></div>
													<div id="gridContatos"></div>
													<input type='hidden' name='vHAXCRESPONSAVEL' id='vHAXCRESPONSAVEL' />
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<div id="divProduto"></div>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-3">
                                                    <div id="divTipo"></div>
                                                </div>
												<div class="col-md-3">
                                                    <div id="divOrigem"></div>
                                                </div>
												<div class="col-md-3">
                                                    <div id="divCategoria"></div>
                                                </div>
												<div class="col-md-3">
													<label>E-mail Informativo?
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vSATEENVIAREMAIL" id="vSATEENVIAREMAIL" class="custom-select obrigatorio" title="E-mail Informativo?">
														<option <?php if ($vSATEENVIAREMAIL == "S") echo "selected=selected"; ?>value="S">Sim</option>
														<option <?php if ($vSATEENVIAREMAIL == "N") echo "selected=selected"; ?>value="N">Não</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<div class="col-md-6">
													<label>Atendente
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIATEATENDENTE" id="vIATEATENDENTE" class="custom-select obrigatorio" title="Atendente">
														<option value="">  -------------  </option>
														<?php foreach (comboUsuarios('') as $tabelas): ?>
															<option value="<?php echo $tabelas['USUCODIGO']; ?>" <?php if ($vROBJETO['ATEATENDENTE'] == $tabelas['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
                                                <div class="col-md-6">
                                                    <label>Posição
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vSATEPOSICAOATUAL" id="vSATEPOSICAOATUAL" class="custom-select obrigatorio" title="Posição">
                                                        <option value="">  -------------  </option>
														<?php
														foreach (comboPosicoesPadroes() as $tabelas): ?>
															<option value="<?php echo $tabelas['POPCODIGO']; ?>" <?php if ($vROBJETO['ATEPOSICAOATUAL'] == $tabelas['POPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['POPNOME']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<div class="form-group row">
                                                <div class="col-md-6">
													<label>Assunto
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSATEASSUNTO" id="vSATEASSUNTO" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['ATEASSUNTO'] : ''); ?>" title="Assunto" >
												</div>
												<div class="col-md-6">
													<label>Prioridade
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIATPCODIGO" id="vIATPCODIGO" class="custom-select obrigatorio" title="Prioridade">
														<option value="">  -------------  </option>
														<?php
														foreach (comboPrioridades() as $tabelas): ?>
															<option value="<?php echo $tabelas['ATPCODIGO']; ?>" <?php if ($vROBJETO['ATPCODIGO'] == $tabelas['ATPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ATPNUMERO'].' - '. $tabelas['ATPDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
                                            </div>
											<div class="form-group row">
												<div class="col-md-12">
													<label>Descrição
														<small class="text-danger font-13">*</small>
													</label>
													<textarea class="form-control" id="vSATEMENSAGEM" name="vSATEMENSAGEM" title="Descrição"><?= nl2br($vROBJETO['ATEMENSAGEM']); ?></textarea>
												</div>
											</div>

											<div class="accordion" id="reformaSim">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Previsão de Conclusão
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<p class="mb-0 text-muted">
															<div class="form-group row">
																<div class="col-md-2">
																	<label>Data Inicial</label>
																	<input class="form-control" title="Data Inicial" name="vDATEPREVISAOCONCLUSAOINICIO" id="vDATEPREVISAOCONCLUSAOINICIO" value="<?= $vROBJETO['ATEPREVISAOCONCLUSAOINICIO'];  ?>" type="date" >
																</div>
																<div class="col-md-2">
																	<label>Hora Inicial</label>
																	<input type="text" class="form-control" name="vSATEPREVISAOCONCLUSAOHORAINI" id="vSATEPREVISAOCONCLUSAOHORAINI" title="Hora Inicial" value="<?= $vROBJETO['ATEPREVISAOCONCLUSAOHORAINI']; ?>"/>
																</div>
																<div class="col-md-2">
																	<label>Data Final</label>
																	<input class="form-control" title="Data Final" name="vDATEPREVISAOCONCLUSAOFIM" id="vDATEPREVISAOCONCLUSAOFIM" value="<?= $vROBJETO['ATEPREVISAOCONCLUSAOFIM'];  ?>" type="date" >
																</div>
																<div class="col-md-2">
																	<label>Hora Final</label>
																	<input type="text" class="form-control" name="vSATEPREVISAOCONCLUSAOHORAFIM" id="vSATEPREVISAOCONCLUSAOHORAFIM" title="Hora Final" value="<?= $vROBJETO['ATEPREVISAOCONCLUSAOHORAFIM']; ?>"/>
																</div>
															</div>
															</p>
														</div>
													</div>
												</div>
											</div>

											<?php if($vIOid > 0){ ?>
											<div class="accordion" id="reformaSim">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Acompanhamento
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<p class="mb-0 text-muted">
															<div class="form-group row">
																<div class="col-md-2">
																	<label>Número Atendimento</label>
																	<input class="form-control" title="Número Atendimento" disabled name="vHATESEQUENCIAL" id="vHATESEQUENCIAL" value="<?= adicionarCaracterLeft($vROBJETO['ATESEQUENCIAL'], 5);  ?>" type="text" >
																</div>
																<div class="col-md-2">
																	<label>Data Abertura</label>
																	<input type="text" class="form-control" disabled name="vHATEDATA_INC" id="vHATEDATA_INC" title="Data Abertura" value="<?= formatar_data_hora($vROBJETO['ATEDATA_INC']); ?>"/>
																</div>
																<div class="col-md-2">
																	<label>Usuário Inclusão</label>
																	<input class="form-control" title="Usuário Inclusão" disabled name="vHATEUSU_INC" id="vHATEUSU_INC" value="<?= $vROBJETO['USUARIOINCLUSAO'];  ?>" type="text" >
																</div>
																<div class="col-md-2">
																	<label>Data Alteração</label>
																	<input type="text" class="form-control" disabled name="vHATEDATA_ALT" id="vHATEDATA_ALT" title="Data Alteração" value="<?= formatar_data_hora($vROBJETO['ATEDATA_ALT']); ?>"/>
																</div>
																<div class="col-md-2">
																	<label>Usuário Alteração</label>
																	<input type="text" class="form-control" disabled name="vHATEUSU_ALT" id="vHATEUSU_ALT" title="Usuário Alteração" value="<?= $vROBJETO['USUARIOALTERACAO']; ?>"/>
																</div>
																<div class="col-md-2">
																	<label>Data Conclusão</label>
																	<input type="text" class="form-control" disabled name="vHATEDATACONCLUSAO" id="vHATEDATACONCLUSAO" title="Data Conclusão" value="<?= formatar_data_hora($vROBJETO['ATEDATACONCLUSAO']); ?>"/>
																</div>
															</div>
															</p>
														</div>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>

										<!-- Aba Dados GED -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div class="form-group">
												<div class="area-upload">
													<label for="upload-file" class="label-upload">
														<i class="fas fa-cloud-upload-alt"></i>
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta área <br/>
														Formatos permitidos (Imagem, PDF, Vídeo e Áudio)
														</div>
													</label>
													<input type="file" accept="*" id="upload-file" multiple/>

													<div class="lista-uploads">
													</div>
												</div>
											</div>
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>

										<!--Aba Posicoes -->
                                        <div class="tab-pane p-3" id="posicoes" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_historico" class="table-responsive"></div>
                                            </div>
                                        </div>
                                        <!-- Aba Posicoes end -->

										<div class="tab-pane p-3" id="subAtendimentos" role="tabpanel">
											<div class="form-group row">
                                                <div id="div_subAtendimentoxPlanoTrabalho" class="table-responsive"></div>
											</div>
											<div class="form-group row">
												<div id="graficoTime"></div>
											</div>
										</div>
										<!--
										<div class="tab-pane p-3" id="documentacao" role="tabpanel">
											<div class="form-group row">
                                                <div id="div_documentacao" class="table-responsive"></div>
                                            </div>
										</div>-->

										<div class="form-group">
											<label class="form-check-label" for="invalidCheck3" style="color: red">
												Campos em vermelho são de preenchimento obrigatório!<br/>
											</label>
										</div>
										<?php include('../includes/botoes_cad_novo.php'); ?>
                                    </div>
                                    </form><!--end form-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->

                    </div><!--end row-->

                </div><!-- container -->
            </div>

            <!-- end page content -->
            <?php include_once '../includes/footer.php' ?>
        </div>
 <!--
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalAtendimentoxHistoricos">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Posições/Históricos</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_AtendimentoxHistoricos">
							<input type="hidden" id="hdn_pai_AtendimentoxHistoricos" name="hdn_pai_AtendimentoxHistoricos" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_AtendimentoxHistoricos" name="hdn_filho_AtendimentoxHistoricos" value="">
							<div class="col-md-6">
								<label>Atendente
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vIATEATENDENTE" id="vIATEATENDENTE" class="custom-select obrigatorio" title="Atendente">
									<option value="">  -------------  </option>
									<?php foreach (comboUsuarios('') as $tabelas): ?>
										<option value="<?php echo $tabelas['USUCODIGO']; ?>" <?php if ($vROBJETO['ATEATENDENTE'] == $tabelas['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['USUNOME']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Posição
									<small class="text-danger font-13">*</small>
								</label>
								<select name="vSATEPOSICAOATUAL" id="vSATEPOSICAOATUAL" class="custom-select obrigatorio" title="Posição">
									<option value="">  -------------  </option>
									<?php
									foreach (comboPosicoesPadroes() as $tabelas): ?>
										<option value="<?php echo $tabelas['POPCODIGO']; ?>" <?php if ($vROBJETO['ATEPOSICAOATUAL'] == $tabelas['POPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['POPNOME']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-12">
								<label>Motivo Alteração</label>
								<textarea title="Motivo Alteração" class="form-control" id="vSUXRMOTIVOALTERACAOSALARIAL" name="vSUXRMOTIVOALTERACAOSALARIAL" rows="3"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalAtendimentoxHistoricos('modal_div_AtendimentoxHistoricos','transactionAtendimentoxHistoricos.php', 'div_AtendimentoxHistoricos', 'AtendimentoxHistoricos', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		-->
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalAtividadePlanoTrabalho">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Incluir/Alterar Atividades</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="form-parsley" action="#" method="post" name="formContatos" id="formContatos">
						<input type="hidden" id="hdn_pai_AtividadePlanoTrabalho" name="hdn_pai_AtividadePlanoTrabalho" value="<?= $vIOid;?>">
						<input type="hidden" id="hdn_filho_AtividadePlanoTrabalho" name="hdn_filho_AtividadePlanoTrabalho" value="">

						<div class="form-group row">

							<div class="col-sm-6">
								<label class="control-label">Atividade</label>
								<select id="vHMATICODIGO" class="form-control">
									<?php
										foreach (comboAtividades('') as $cbAtividade):
											if ($cbAtividade['ATICODIGO'] == $atividade['ATICODIGO']): ?>
												<option selected value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
											<?php else: ?>
												<option value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
											<?php
												endif;
										endforeach;
									?>
								</select>
							</div><!--end col-->

							<div class="col-sm-6">
								<label class="control-label">Departamento</label>
								<select id="vHMDEPARTAMENTO" class="form-control">
									<option value="">  -------------  </option>
									<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABDEPARTAMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div><!--end col-->

						</div>

						<div class="form-group row">
							<div class="col-sm-6">
								<label>Data Previsão</label>
								<input class="form-control" title="E-mail" name="vHMDATAPREVISAO" id="vHMDATAPREVISAO" type="email" value="<?= $vRCONTATO['CONEMAIL'];?>" >
							</div>
							<div class="col-sm-6">
								<label class="control-label">Responsável</label>
								<select id="vHMRESPONSAVEL" class="form-control">
									<option value="">  -------------  </option>
									<?php foreach (comboTabelas('USUARIOS - DEPARTAMENTOS') as $tabelas): ?>
										<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['TABDEPARTAMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div><!--end col-->
						</div>

						<div class="form-group row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalCustosMateriasPrimas('modal_div_ClientesxContatos','transactionContatos.php', 'div_ClientesxContatos', 'ClientesxContatos', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Tabela Padrão -->
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalTabelaPadrao">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Controles Tabelas</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_tabela_padrao">
							<input type="hidden" id="vHTABPARTITULO" name="vHTABPARTITULO" value="">
							<input type="hidden" id="vHTABPARNAME" name="vHTABPARNAME" value="">
							<input type="hidden" id="vHTABPARDIV" name="vHTABPARDIV" value="">
							<input type="hidden" id="vHTABCODIGODIALOG" name="vHTABCODIGODIALOG" value="">
							<input type="hidden" id="vHTABTIPODIALOG" name="vHTABTIPODIALOG" value="">
							<div class="col-md-12">
								<label>Descrição</label>
								<input class="form-control divObrigatorio" name="vHTABDESCRICAODIALOG" title="Descrição" id="vHTABDESCRICAODIALOG" type="text">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary btn-xs waves-effect waves-light fa-pull-right" onclick="salvarModalTabelaPadrao('')">Salvar</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
						</div>
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
		<!--Wysiwig js-->
        <script src="../assets/plugins/tinymce/tinymce.min.js"></script>
        <script src="../assets/pages/jquery.form-editor.init.js"></script>

		<script src="../assets/plugins/repeater/jquery.repeater.min.js"></script>
		<script src="../assets/pages/jquery.form-repeater.js"></script>

        <?php include_once '../includes/scripts_footer.php' ?>
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Cad Empresa js -->
		<script src="js/cadAtendimentos.js"></script>
		<script>
			//Categoria
			var vAParameters =
			{
				 'vSTitulo': 'Categoria',
				 'vSTabTipo': 'HELPDESK - CATEGORIA',
				 'vSCampo': 'vITABCODIGO',
				 'vIValor': '<?php echo $vROBJETO['TABCODIGO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divCategoria',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Tipo
			var vAParameters =
			{
				 'vSTitulo': 'Tipo',
				 'vSTabTipo': 'HELPDESK - TIPO ATENDIMENTO',
				 'vSCampo': 'vIATETIPOATENDIMENTO',
				 'vIValor': '<?php echo $vROBJETO['ATETIPOATENDIMENTO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divTipo',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Origem
			var vAParameters =
			{
				 'vSTitulo': 'Origem',
				 'vSTabTipo': 'HELPDESK - ORIGEM',
				 'vSCampo': 'vIATEORIGEM',
				 'vIValor': '<?php echo $vROBJETO['ATEORIGEM']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divOrigem',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
		</script>

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript" src="js/graficos.js"></script>

		<?php

		if($vIOid > 0){

			$ARRAY = buscaPeriodoPlanoTrabalho($vIOid,'chart');
			$ARR = json_encode($ARRAY);

			//print_r($ARRAY);
			?>

			<script type="text/javascript" DEFER="DEFER">
				var dataObject =
				{
					title: 'Servidor - Tempo de Contribuição',
					width: 850,
					height: 500,
					div_retorno: 'graficoTime',
					data: <?= $ARR; ?>
				}
				chartTimeline( dataObject );
			</script>
			<!-- FIM GRAFICO TIMELINE -->
		<?php } ?>


		<script src="js/scriptUpload.js"></script>
    </body>
</html>
