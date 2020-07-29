<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboEstados.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include_once '../includes/scripts_header.php' ?>

        <link href="../assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">
        <link href="../assets/plugins/filter/magnific-popup.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

        <!-- App css -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

    </head>

    <?php if ($vIOid > 0){ ?>
    <body onload="gerarGridJSON('transactionUsuariosxAcessos.php', 'div_acessos', 'UsuariosxAcessos', '<?= $vIOid;?>');
				 exibirCidades('<?= $vRENDERECO['ESTCODIGO']; ?>', '<?= $vRENDERECO['CIDCODIGO']; ?>', 'div_cidade', 'vHCIDCODIGO');">
	<?php } else { ?>
	<body>
	<?php } ?>

        <?php include_once '../includes/menu.php' ?>

        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content">

                <div class="container-fluid">
                    <!-- Page-Title -->
                    <?php include_once '../includes/breadcrumb.php' ?>

					<div class="row">
							<div class="col-lg-12 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                   <!-- <h4 class="mt-0 header-title">Cadastro de <?= $vAConfiguracaoTela['MENTITULO'];?></h4>-->

                                    <form class="form-parsley" action="#" method="post" name="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" id="form<?= $vAConfiguracaoTela['MENTITULOFUNC'];?>" enctype="multipart/form-data">

                                    <input type="hidden" name="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" id="vI<?= $vAConfiguracaoTela['MENPREFIXO'];?>CODIGO" value="<?php echo $vIOid; ?>"/>
                                    <input type="hidden" name="methodPOST" id="methodPOST" value="<?php if(isset($_GET['method'])){ echo $_GET['method']; }else{ echo "insert";} ?>"/>                                    

                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#acesses-1" role="tab">Acessos/TI</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#desligamento" role="tab">Desligamento</a>
                                        </li>           
                                        <?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">												
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '1966');">GED</a>
										</li>
                                        <?php } ?>
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
                                                <div class=" col-md-4 col-lg-4">
                                                    <input type="file" name="vHUSUFOTO" id="vHUSUFOTO" class="dropify" data-default-file="<?= ($vROBJETO['USUFOTO'] == '' ? '../assets/images/users/user-1.jpg' : '../ged/usuarios_fotos/'.$vROBJETO['USUFOTO']);?>" style="max-height: 130px">
                                                </div>
												<div class="col-md-8 col-lg-8">
                                                    <div class="col-lg-12">
                                                        <label>Nome Completo
                                                            <small class="text-danger font-13">*</small>
                                                        </label>
                                                        <input class="form-control  obrigatorio" name="vSUSUNOME" id="vSUSUNOME" type="text" value="<?= $vROBJETO['USUNOME']?>" title="Nome Completo" >
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 fa-pull-left">
                                                        <label>Data de Nascimento</label>
                                                        <input class="form-control" name="vDUSUDATA_NASCIMENTO" id="vDUSUDATA_NASCIMENTO" type="text" value="<?= formatar_data($vROBJETO['USUDATA_NASCIMENTO'])?>" maxlength="10" onKeyPress="return digitos(event, this);" onKeyUp="mascara('DATA',this,event);" >
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 fa-pull-right">
                                                        <label>Idade</label>
													    <input class="form-control" value="<?= calcular_idade($vROBJETO['USUDATA_NASCIMENTO'], '')?> Anos" type="text" disabled>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6 fa-pull-left">
                                                        <label>Data de Admissão
                                                            <small class="text-danger font-13">*</small>
                                                        </label>
                                                        <input class="form-control obrigatorio" title="Data de Admissão" name="vDUSUDATAADMISSAO" id="vDUSUDATAADMISSAO" type="text" value="<?= formatar_data($vROBJETO['USUDATAADMISSAO'])?>" maxlength="10" onKeyPress="return digitos(event, this);" onKeyUp="mascara('DATA',this,event);" >
                                                      </div>
                                                    <div class="col-md-6 col-lg-6 fa-pull-right">
                                                        <label>Tempo de Empresa</label>
                                                        <input class="form-control" value="<?= calcular_idade($vROBJETO['USUDATAADMISSAO'], '')?> Anos" type="text" disabled >
                                                   </div>
                                                </div>
											</div>											
											<div class="form-group row">
												<div class="col-md-6">
													<label>Sexo</label>
													<select class="custom-select" name="vSUSUSEXO" id="vSUSUSEXO">
														<option value="F" <?php if ($vROBJETO['USUSEXO'] == 'F') echo "selected"; ?>>Feminino</option>
														<option value="M" <?php if ($vROBJETO['USUSEXO'] == 'M') echo "selected"; ?>>Masculino</option>
													</select>
												</div>
												<div class="col-md-6">
                                                    <div id="divEstadoCivil"></div>        
												</div>
											</div> 
                                            <div class="form-group row">
                                                <div class="col-sm-4">
													<div id="divDepartamento"></div>													
												</div>
												<div class="col-sm-4">
													<div id="divCargo"></div>                                                    
                                                </div>
                                                <div class="col-sm-4">
													<label>Salário Inicial</label>
													<input class="form-control classmonetario" name="vMUSUSALARIOINICIAL" id="vMUSUSALARIOINICIAL" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['USUSALARIOINICIAL'], false); }?>" type="text" >
												</div>
											</div>
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Informações Contato
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">									
															<div class="form-group row">
																<div class="col-sm">
																	<label>Telefone</label>
																	<input class="form-control" name="vSUSUFONE" id="vSUSUFONE" type="text" value="<?= $vROBJETO['USUFONE']?>" maxlength="13" onKeyPress="return digitos(event, this);" onKeyUp="mascara('TEL',this,event);" >
																</div>
																<div class="col-sm">
																	<label>Celular</label>
																	<input class="form-control" name="vSUSUCELULAR" id="vSUSUCELULAR" type="text" value="<?= $vROBJETO['USUCELULAR']?> "maxlength="14" onKeyPress="return digitos(event, this);" onKeyUp="mascara('CEL',this,event);" >
																</div>
															</div>
															<div class="form-group row">
																<div class="form-group col-sm">
																	<label>E-mail Alternativo/Pessoal</label>
																	<input class="form-control" name="vSUSUEMAILALT" id="vSUSUEMAILALT" type="email" value="<?= $vROBJETO['USUEMAILALT']?>" >
																</div>
																<div class="form-group col-sm">
																	<label>Ramal</label>
																	<input class="form-control" name="vSUSURAMAL" id="vSUSURAMAL" type="text" value="<?= $vROBJETO['USURAMAL']?>" >
																</div>
															</div>
														</div>
													</div>
												</div>		
											</div>
											<div class="accordion" id="reformaSim2">
												<div class="card border mb-0 shadow-none">
													<div class="card-header p-0" id="headingOne">
														<h5 class="my-1">
															<button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Informações Endereço
															</button>
														</h5>
													</div>
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
														<div class="card-body">
															<input type="hidden" name="vHUXECODIGO" id="vHUXECODIGO" value="<?= $vRENDERECO['UXECODIGO'];?>"/>
															<div class="form-group row">
																<div class="col-sm-3">
																	<label>CEP</label>
																	<input class="form-control" title="CEP" name="vHUXECEP" id="vHUXECEP" type="text" value="<?= $vRENDERECO['UXECEP'];  ?>" onblur="buscarCEP(this.value);">
																</div>
																<div class="col-sm-3">
																	<label>Bairro</label>
																	<input class="form-control" title="Bairro" name="vHUXEBAIRRO" id="vHUXEBAIRRO" type="text" value="<?= $vRENDERECO['UXEBAIRRO'];  ?>" >
																</div>											
															</div>											
															<div class="form-group row">
																<div class="col-sm-4">
																	<label>Endereço</label>
																	<input class="form-control" title="Endereço" name="vHUXELOGRADOURO" id="vHUXELOGRADOURO" type="text" value="<?= $vRENDERECO['UXELOGRADOURO'];?>">
																</div>
																<div class="col-sm-2">
																	<label>Nº</label>
																	<input class="form-control" name="vHUXENROLOGRADOURO" id="vHUXENROLOGRADOURO" type="text" value="<?= $vRENDERECO['UXENROLOGRADOURO'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Complemento</label>
																	<input class="form-control" name="vHUXECOMPLEMENTO" id="vHUXECOMPLEMENTO" type="text" value="<?= $vRENDERECO['UXECOMPLEMENTO'];?>" >
																</div>
																<div class="col-sm-2">
																	<label>Estado</label>
																	<select title="Estado" id="vHESTCODIGO" class="custom-select" name="vHESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade', 'vHCIDCODIGO');">
																		<?php foreach (comboEstados() as $tabelas): ?>
																			<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vRENDERECO['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
																<div class="col-sm-2">
																	<div id="div_cidade"></div>
																</div>
															</div>          	
														</div>
													</div>
												</div>		
											</div>
											<div class="form-group row">
												<div class="col-md-3">
													<label>Cadastro (Status)</label>
													<select class="custom-select" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO']?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Ativo</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Inativo</option>
													</select>
												</div>
											</div>
                                        </div>
                                        <!-- Aba Dados Gerais end -->

                                        <!-- Aba Acessos/ti -->
                                        <div class="tab-pane p-3" id="acesses-1" role="tabpanel">                                            
                                            <div class="form-group row">
												<div class="col-md-6">
													<label>Senha</label>
													<input class="form-control" name="vSUSUSENHA" id="vSUSUSENHA" value="<?= Desencriptar($vROBJETO['USUSENHA'], cSPalavraChave);?>" type="password" title="Senha">
												</div>
												<div class="col-md-6">
													<label>Confirmar Senha</label>
													<input class="form-control" name="vHUSUSENHA" id="vHUSUSENHA" value="<?= Desencriptar($vROBJETO['USUSENHA'], cSPalavraChave);?>" type="password" title="Confirme a senha">
												</div>
                                            </div>
											<div class="form-group row">
                                                <div class="col-md-6">
                                                    <label>E-mail Profissional/Login</label>
                                                    <input class="form-control" name="vSUSUEMAIL" id="vSUSUEMAIL" type="email" value="<?= $vROBJETO['USUEMAIL']?>" title="E-mail Profissional">
                                                </div>
												<div class="col-md-6">
													<label>Grupo de Acesso/Perfil</label>
													<select title="Grupo de Acesso/Perfil" id="vIUSUPERFIL" class="custom-select" name="vIUSUPERFIL" onchange="exibirAcessosGrupos(this.value);">
														<option value="">  -------------  </option>
														<?php foreach (comboTabelas('USUARIOS - GRUPOS ACESSO') as $tabelas): ?>
															<option value="<?php echo $tabelas['TABCODIGO']?>" <?php if ($vROBJETO['USUPERFIL'] == $tabelas['TABCODIGO']) echo "selected"; ?>><?= $tabelas['TABDESCRICAO']?></option>
														<?php endforeach; ?>
													</select>
												</div>
                                            </div>
											<div class="form-group row">
												<div id="div_acessos" class="table-responsive"></div>
											</div>            
                                        </div>
                                        <!-- Aba Acessos/TI end -->																											
										
										<!-- Aba Desligamento -->
                                        <div class="tab-pane p-3" id="desligamento" role="tabpanel">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label>Data Demissão</label>													
                                                    <input class="form-control" title="Data Demissão" name="vDUSUDATADEMISSAO" id="vDUSUDATADEMISSAO" value="<?= $vROBJETO['USUDATADEMISSAO'];?>" type="date" >
                                                </div>
												<div class="col-md-6">
													<div id="divMotivoDesligamento"></div>													
												</div>	
                                            </div>
                                            <div class="form-group row">
												<div class="col-md-12">
													<label>Observações/Entrevista de Desligamento</label>
													<textarea title="Observações/Entrevista de desligamento" class="form-control" id="vSUSUOBSERVACAODESLIGAMENTO" name="vSUSUOBSERVACAODESLIGAMENTO" rows="5"><?= $vROBJETO['USUOBSERVACAODESLIGAMENTO']; ?></textarea>
												</div> 	
											</div>      
                                        </div>
                                        <!-- Aba Desligamento end -->

                                        <!-- Aba Dados Documento -->
                                        <div class="tab-pane p-3" id="profile-1" role="tabpanel">
                                            <p class="text-muted mb-0">
                                                <div class="form-group">
                                                    <div class="col-md-6">
														<div id="divVinculoEmpregaticio"></div>                                                        
                                                    </div>
                                                </div>
                                                <div class="accordion" id="accordionExample">
                                                    <div class="card border mb-0 shadow-none">
                                                        <div class="card-header p-0" id="cpf">
                                                            <h5 class="my-1" data-toggle="collapse" data-target="#dadosCpf" aria-expanded="false" aria-controls="dadosCpf">
                                                                <button class="btn btn-link text-dark collapsed" type="button" >
                                                                CPF
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="dadosCpf" class="collapse" aria-labelledby="cpf" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <label>Número</label>
                                                                        <input class="form-control" name="vSUSUCPF" id="vSUSUCPF" type="text" value="<?= $vROBJETO['USUCPF']?>"  maxlength="14" onKeyPress="return digitos(event, this);" onKeyUp="mascara('CPF',this,event);" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card border mb-0 shadow-none">
                                                        <div class="card-header p-0" id="cnpj">
                                                            <h5 class="my-1" data-toggle="collapse" data-target="#dadoCnpj" aria-expanded="false" aria-controls="dadoCnpj">
                                                                <button class="btn btn-link text-dark collapsed" type="button" >
                                                                CNPJ
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="dadoCnpj" class="collapse" aria-labelledby="cpf" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 col-lg-6">
                                                                        <label>Número</label>
                                                                        <input class="form-control" name="vSUSUCNPJ" id="vSUSUCNPJ" type="text" value="<?= $vROBJETO['USUCNPJ']?>"  maxlength="18" onKeyPress="return digitos(event, this);" onKeyUp="mascara('CNPJ',this,event);"  onblur="validarCNPJ('USUCNPJ')">
                                                                    </div>
                                                                    <div class="col-md-6 col-lg-6">
                                                                        <label>Razão Social</label>
                                                                        <input class="form-control" name="vSUSURAZAOSOCIAL" id="vSUSURAZAOSOCIAL" type="text" value="<?= $vROBJETO['USURAZAOSOCIAL']?>" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mb-0 border shadow-none">
                                                        <div class="card-header p-0" id="RG">
                                                        <h5 class="my-1" data-toggle="collapse" data-target="#dadosRg" aria-expanded="false" aria-controls="dadosRg">
                                                            <button class="btn btn-link text-dark collapsed" type="button" >
                                                            RG
                                                            </button>
                                                        </h5>
                                                        </div>
                                                        <div id="dadosRg" class="collapse" aria-labelledby="RG" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Número</label>
                                                                        <input class="form-control" name="vSUSURG" id="vSUSURG" type="text" value="<?= $vROBJETO['USURG']?>" >
                                                                   </div>
                                                                    <div class="col-md-4">
                                                                        <label>Órgão Emissor</label>
                                                                        <input class="form-control" name="vSUSURGORGAOEMISSOR" id="vSUSURGORGAOEMISSOR" type="text" value="<?= $vROBJETO['USURGORGAOEMISSOR']?>" >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Data Emissão</label>
                                                                        <input class="form-control" name="vDUSURGDATAEMISSAO" id="vDUSURGDATAEMISSAO" type="text" value="<?= formatar_data($vROBJETO['USURGDATAEMISSAO'])?>"maxlength="10" onKeyPress="return digitos(event, this);" onKeyUp="mascara('DATA',this,event);" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mb-0 border shadow-none">
                                                        <div class="card-header p-0" id="CTPS">
                                                            <h5 class="my-1" data-toggle="collapse" data-target="#dadosCtps" aria-expanded="false" aria-controls="dadosCtps">
                                                                <button class="btn btn-link text-dark collapsed" type="button" >
                                                                CTPS
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="dadosCtps" class="collapse" aria-labelledby="CTPS" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Número</label>
                                                                        <input class="form-control" name="vSUSUCARTEIRAPROFISSIONAL" id="vSUSUCARTEIRAPROFISSIONAL" type="text" value="<?= $vROBJETO['USUCARTEIRAPROFISSIONAL']?>" >
                                                                     </div>
                                                                    <div class="col-md-4">
                                                                        <label>Série</label>
                                                                        <input class="form-control" name="vSUSUCARTEIRAPROFISSIONALSERIE" id="vSUSUCARTEIRAPROFISSIONALSERIE" type="text" value="<?= $vROBJETO['USUCARTEIRAPROFISSIONALSERIE']?>" >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>PIS</label>
                                                                        <input class="form-control" name="vSUSUPIS" id="vSUSUPIS" type="text" value="<?= $vROBJETO['USUPIS']?>" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card mb-0 border shadow-none">
                                                        <div class="card-header p-0" id="titulo">
                                                            <h5 class="my-1" data-toggle="collapse" data-target="#dadosTitulo" aria-expanded="false" aria-controls="dadosTitulo">
                                                                <button class="btn btn-link text-dark collapsed" type="button">
                                                                Título de Eleitor
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="dadosTitulo" class="collapse" aria-labelledby="titulo" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Número</label>
                                                                        <input class="form-control" name="vSUSUTITULO_ELEITOR" id="vSUSUTITULO_ELEITOR" type="text" value="<?= $vROBJETO['USUTITULO_ELEITOR']?>" >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Zona Eleitoral</label>
                                                                        <input class="form-control" name="vSUSUTITULO_ELEITOR_ZONA" id="vSUSUTITULO_ELEITOR_ZONA" type="text" value="<?= $vROBJETO['USUTITULO_ELEITOR_ZONA']?>" >
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Seção</label>
                                                                        <input class="form-control" name="vSUSUTITULO_ELEITOR_SECAO" id="vSUSUTITULO_ELEITOR_SECAO" type="text" value="<?= $vROBJETO['USUTITULO_ELEITOR_SECAO']?>" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
													<div class="card mb-0 border shadow-none">
                                                        <div class="card-header p-0" id="filiacao">
                                                            <h5 class="my-1" data-toggle="collapse" data-target="#dadosFiliacao" aria-expanded="false" aria-controls="dadosFiliacao">
                                                                <button class="btn btn-link text-dark collapsed" type="button">
                                                                Filiação
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div id="dadosFiliacao" class="collapse" aria-labelledby="filiacao" data-parent="#accordionExample" style="">
                                                            <div class="card-body">
																<div class="row">
																	<div class="col-md-6">
																		<label>Nome Pai</label>
																		<input class="form-control" name="vSUSUNOME_PAI" id="vSUSUNOME_PAI" type="text" value="<?= $vROBJETO['USUNOME_PAI']?>" title="Nome Pai" >
																	</div>
																	<div class="col-md-6">
																		<label>Nome Mãe</label>
																		<input class="form-control" name="vSUSUNOME_MAE" id="vSUSUNOME_MAE" type="text" value="<?= $vROBJETO['USUNOME_MAE']?>" title="Nome Mãe" >
																	</div>
																</div>                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </p>
                                        </div>
                                        <!-- Aba Dados Documentos end -->

										<?php if($vIOid > 0){ ?>

										<!-- Aba Dados GED -->
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div id="modal_div_ClientesxGED">
												<div class="form-group row">                                                												
													<div class="col-md-4">   
														<div id="divTipoArquivo"></div>	
													</div>
													<div class="file-field col-md-4">
														<label>Escolher Arquivo
															<small class="text-danger font-13">*</small>
														</label>
														<input type="file" id="fileUpload" name="fileUpload">
												   </div>
												   <div class="col-md-4">
														<br/>				  
														<button type="button" id="btnEnviar" class="btn btn-secondary waves-effect">Salvar Documentos</button><br>
													</div>
												</div>	
											</div>	
											<div class="form-group row">
												<div id="div_ged" class="table-responsive"></div>
											</div>
										</div>	
									
										<?php } ?>
										<div class="form-group row">
											<label class="form-check-label" for="invalidCheck3" style="color: red">
												Campos em vermelho são de preenchimento obrigatório!<br/>
												Para o usuário não ter mais acesso ao sistema deve estar Inativado ou com a Data Demissão preenchida.
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
        <!-- end page-wrapper -->
												
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
        <script src="../assets/plugins/select2/select2.min.js"></script>
        
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Usuarios js -->
		<script src="js/cadUsuarios.js"></script>
		<script>
			//Departamento/Setor
			var vAParameters =
			{
				 'vSTitulo': 'Departamento/Setor',
				 'vSTabTipo': 'USUARIOS - DEPARTAMENTOS',
				 'vSCampo': 'vITABDEPARTAMENTO',
				 'vIValor': '<?php echo $vROBJETO['TABDEPARTAMENTO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divDepartamento',
				 'vSObrigatorio': 'S',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Cargo
			var vAParameters =
			{
				 'vSTitulo': 'Cargo',
				 'vSTabTipo': 'USUARIOS - CARGOS',
				 'vSCampo': 'vITABCARGO',
				 'vIValor': '<?php echo $vROBJETO['TABCARGO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divCargo',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);			
			//Estado Civil
			var vAParameters =
			{
				 'vSTitulo': 'Estado Civil',
				 'vSTabTipo': 'USUARIOS - ESTADO CIVIL',
				 'vSCampo': 'vIUSUESTADOCIVIL',
				 'vIValor': '<?php echo $vROBJETO['USUESTADOCIVIL']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divEstadoCivil',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			//Motivo do Desligamento
			var vAParameters =
			{
				 'vSTitulo': 'Motivo do Desligamento',
				 'vSTabTipo': 'USUARIOS - MOTIVO DESLIGAMENTO',
				 'vSCampo': 'vIUSUMOTIVODESLIGAMENTO',
				 'vIValor': '<?php echo $vROBJETO['USUMOTIVODESLIGAMENTO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divMotivoDesligamento',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
			
			//Tipo Arquivo
			var vAParameters =
			{
				 'vSTitulo': 'Tipo Arquivo',
				 'vSTabTipo': 'GED - TIPO',
				 'vSCampo': 'vHGEDTIPO',
				 'vIValor': '',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divTipoArquivo',
				 'vSObrigatorio': 'N',
				 'vSMethod': '<?= $_GET['method']; ?>'
			}
			combo_padrao_tabelas(vAParameters);
		</script>
		
    </body>
</html>