<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1966);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../financeiro/combos/comboBancos.php';
include_once __DIR__.'/../cadastro/combos/comboPaises.php';
include_once __DIR__.'/../cadastro/combos/comboEstados.php';
include_once '../sistema/combos/comboEmpresaUsuaria.php';
include_once __DIR__.'/combos/comboValesTransporte.php';
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
										<?php if($vIOid > 0){ ?>										
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#bancario-1" role="tab" onclick="gerarGridJSON('transactionUsuariosxContasBancarias.php', 'div_DadosBancarios', 'UsuariosxContasBancarias', '<?= $vIOid;?>');">Dados Bancários</a>
                                        </li>    
										<?php } ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#desligamento" role="tab">Desligamento</a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#profile-1" role="tab">Documentos</a>
                                        </li>
										<?php if($vIOid > 0){ ?>
										<!--
										<li class="nav-item waves-effect waves-light">
											<a class="nav-link" data-toggle="tab" href="#documentospendentes" role="tab" onclick="gerarGridJSON('transactionUsuariosxDocumentoPendente.php', 'div_UsuariosxDocumentoPendente', 'UsuariosxDocumentoPendente', '<?= $vIOid;?>');">Documentos Pendentes</a>                                           
                                        </li> -->	
										<?php } ?>            
                                        <?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#escolaridade" role="tab" onclick="gerarGridJSON('transactionUsuariosxEscolaridade.php', 'div_UsuariosxEscolaridade', 'UsuariosxEscolaridade', '<?= $vIOid;?>');">Escolaridade</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#feedBack" role="tab" onclick="gerarGridJSON('transactionUsuariosxFeedback.php', 'div_UsuariosxFeedback', 'UsuariosxFeedback', '<?= $vIOid;?>');">FeedBack</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ferias" role="tab" onclick="gerarGridJSON('transactionUsuariosxFerias.php', 'div_UsuariosxFerias', 'UsuariosxFerias', '<?= $vIOid;?>');">Férias</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">												
											<a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '1966');">GED</a>
										</li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#remuneracao" role="tab" onclick="gerarGridJSON('transactionUsuariosxRemuneracao.php', 'div_UsuariosxRemuneracao', 'UsuariosxRemuneracao', '<?= $vIOid;?>');">Remuneração</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#beneficios-1" role="tab" onclick="gerarGridJSON('transactionUsuariosxValesTransporte.php', 'div_ValesTransporte', 'UsuariosxValesTransporte', '<?= $vIOid;?>');">Vale Transporte</a>
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
													<label>Filial
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vIEMPCODIGO" id="vIEMPCODIGO" class="custom-select obrigatorio" title="Filial">
														<option value></option>
                                                       	<?php foreach (comboEmpresaUsuaria('') as $tabelas): ?>
															<option value="<?php echo $tabelas['EMPCODIGO']; ?>" <?php if ($vROBJETO['EMPCODIGO'] == $tabelas['EMPCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['EMPNOMEFANTASIA']; ?></option>
														<?php endforeach; ?>
													</select>
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
																	<label>País</label>
																	<select title="País" id="vHPAICODIGO" class="custom-select" name="vHPAICODIGO">
																		<?php 
																		if (($vRENDERECO['PAICODIGO'] == '') || (isset($vRENDERECO['PAICODIGO']))) 
																			$vIPAICODIGO = 30;
																		else 	
																			$vIPAICODIGO = $vRENDERECO['PAICODIGO'];
																		foreach (comboPaises() as $tabelas): ?>
																			<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vIPAICODIGO == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
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
										
										<!--Aba Dados Bancarios -->			
                                        <div class="tab-pane p-3" id="bancario-1" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_DadosBancarios" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Bancarios end -->
										
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

										<!--Aba Dados Documentos Pendentes 	
                                        <div class="tab-pane p-3" id="documentospendentes" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_UsuariosxDocumentoPendente" class="table-responsive"></div>                                                
                                            </div>
                                        </div>-->	
                                        <!-- Aba Dados Documentos Pendentes end -->
										                                  			
										<?php if($vIOid > 0){ ?>
										<!--Aba Dados Escolaridade -->			
                                        <div class="tab-pane p-3" id="escolaridade" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_UsuariosxEscolaridade" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Escolaridade end -->
										<!--Aba Dados FeedBack -->			
                                        <div class="tab-pane p-3" id="feedBack" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_UsuariosxFeedback" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados FeedBack end -->
										<!--Aba Dados Ferias -->			
                                        <div class="tab-pane p-3" id="ferias" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_UsuariosxFerias" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Ferias end -->	

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
                                       
										<!--Aba Dados Remuneração -->
										<div class="tab-pane p-3" id="remuneracao" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_UsuariosxRemuneracao" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Remuneração end -->	
										<!--Aba Dados Beneficios -->
                                        <div class="tab-pane p-3" id="beneficios-1" role="tabpanel">
                                            <div class="form-group row">
                                                <div id="div_ValesTransporte" class="table-responsive"></div>                                                
                                            </div>
                                        </div>
                                        <!-- Aba Dados Beneficios end --> 										
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
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxContasBancarias">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Dados Bancários</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxContasBancarias">
							 <input type="hidden" id="hdn_pai_UsuariosxContasBancarias" name="hdn_pai_UsuariosxContasBancarias" value="<?= $vIOid;?>">
							 <input type="hidden" id="hdn_filho_UsuariosxContasBancarias" name="hdn_filho_UsuariosxContasBancarias" value="">
							<div class="col-md-12">
								<label>Banco</label>
								<select class=" custom-select divObrigatorio" id="vIBACCODIGO" name="vIBACCODIGO" title="Banco">
									<option value="">Selecione</option>
									<?php foreach (comboBancos() as $bancos): ?>
									<option value="<?php echo $bancos['BACCODIGO']?>"> <?= $bancos['BACCODIGOBACEN']."-".$bancos['BACBANCO'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Tipo de Conta</label>
								<select class="custom-select divObrigatorio" id="vIUXBTIPO" name="vIUXBTIPO" title="Tipo de Conta">
									<?php foreach (comboTabelas('CONTAS BANCARIAS - TIPOS') as $tabelas): ?>
									<option value="<?= $tabelas['TABCODIGO']?>"> <?= $tabelas['TABDESCRICAO']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Agência</label>
								<input class="form-control divObrigatorio" name="vSUXBAGENCIA" title="Agência" id="vSUXBAGENCIA" type="text">
							</div>
							<div class="col-md-6">
								<label>Conta</label>
								<input class="form-control divObrigatorio" name="vSUXBCONTA" title="Conta" id="vSUXBCONTA" type="text">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxContasBancarias('modal_div_UsuariosxContasBancarias','transactionUsuariosxContasBancarias.php', 'div_DadosBancarios', 'UsuariosxContasBancarias', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxValesTransporte">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Vale Transporte</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxValesTransporte">
							<input type="hidden" id="hdn_pai_UsuariosxValesTransporte" name="hdn_pai_UsuariosxValesTransporte" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_UsuariosxValesTransporte" name="hdn_filho_UsuariosxValesTransporte" value="">
							<div class="col-md-12">
								<label>Vale - Itinerário</label>
								<select class="custom-select divObrigatorio" id="vIVXTCODIGO" name="vIVXTCODIGO" title="Vale - Itinerário" onchange="fillVTValorLinha(this.value);">
									<?php foreach (comboValesTransporte() as $tabelas): ?>
									<option value="<?php echo $tabelas['VXTCODIGO']?>"> <?= $tabelas['VXTNOME'].' - '.$tabelas['VXTITINERARIO']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Valor Unitário</label>
								<input class="form-control divObrigatorio classmonetario" name="vMUXVVALOR" title="Valor Unitário" id="vMUXVVALOR"  type="text">
							</div>
							<div class="col-md-6">
								<label>Quantidade por dia</label>
								<input class="form-control divObrigatorio" name="vIUXVQTDE" title="Quantidade por dia" id="vIUXVQTDE" onKeyPress="return digitos(event, this)"  type="text">
							</div>							
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxValesTransporte('modal_div_UsuariosxValesTransporte','transactionUsuariosxValesTransporte.php', 'div_ValesTransporte', 'UsuariosxValesTransporte', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxEscolaridade">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Escolaridade</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxEscolaridade">
							<input type="hidden" id="hdn_pai_UsuariosxEscolaridade" name="hdn_pai_UsuariosxEscolaridade" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_UsuariosxEscolaridade" name="hdn_filho_UsuariosxEscolaridade" value="">
							<div class="col-md-6">
								<label>Grau de Escolaridade</label>
								<select class=" custom-select divObrigatorio" id="vITABCODIGOESCOLARIDADE"  name="vITABCODIGOESCOLARIDADE"  title="Grau de Escolaridade">
									<?php foreach (comboTabelas('USUARIOS - ESCOLARIDADE') as $tabelas): ?>
									<option value="<?= $tabelas['TABCODIGO']?>"> <?= $tabelas['TABDESCRICAO']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Ano/Semestre</label>
								<input class="form-control" name="vSUXESEMESTRE" title="Ano/Semestre" id="vSUXESEMESTRE" onKeyPress="return digitos(event, this)"  type="text">
							</div>
							<div class="col-md-12">
								<label>Instituição de Ensino</label>
								<input class="form-control" name="vSUXEINSTITUICAO" title="Instituição de Ensino" id="vSUXEINSTITUICAO"  type="text">
							</div>
							<div class="col-md-12">
								<label>Curso</label>
								<input class="form-control" name="vSUXECURSO" title="Curso" id="vSUXECURSO"  type="text">
							</div>
							<div class="col-md-6">
								<label>Data Início
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Início" name="vDUXEDATAINICIO" id="vDUXEDATAINICIO" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data Fim/Previsão
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Fim/Previsão" name="vDUXEDATAFIM" id="vDUXEDATAFIM" value="" type="date" >
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxEscolaridade('modal_div_UsuariosxEscolaridade','transactionUsuariosxEscolaridade.php', 'div_UsuariosxEscolaridade', 'UsuariosxEscolaridade', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxFeedback">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">FeedBack</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxFeedback">
							<input type="hidden" id="hdn_pai_UsuariosxFeedback" name="hdn_pai_UsuariosxFeedback" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_UsuariosxFeedback" name="hdn_filho_UsuariosxFeedback" value="">
							<div class="col-md-6">
								<label>Tipo/Perfil</label>
								<select class=" custom-select divObrigatorio" id="vITABCODIGOPERFIL"  name="vITABCODIGOPERFIL" title="Tipo/Perfil">
									<?php foreach (comboTabelas('USUARIOS - TIPO PERFIL') as $tabelas): ?>
									<option value="<?= $tabelas['TABCODIGO']?>"> <?= $tabelas['TABDESCRICAO']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Feedback</label>
								<select class=" custom-select divObrigatorio" id="vITABCODIGOFEEDBACK"  name="vITABCODIGOFEEDBACK"  title="Feedback">
									<?php foreach (comboTabelas('USUARIOS - FEEDBACK') as $tabelas): ?>
									<option value="<?= $tabelas['TABCODIGO']?>"> <?= $tabelas['TABDESCRICAO']?></option>
									<?php endforeach; ?>
								</select>
							</div>							
							<div class="col-md-12">
								<label>Observação</label>
								<textarea title="Observação" class="form-control" id="vSUXFOBSERVACAO" name="vSUXFOBSERVACAO" rows="3"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxFeedback('modal_div_UsuariosxFeedback','transactionUsuariosxFeedback.php', 'div_UsuariosxFeedback', 'UsuariosxFeedback', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxFerias">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Férias</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxFerias">
							<input type="hidden" id="hdn_pai_UsuariosxFerias" name="hdn_pai_UsuariosxFerias" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_UsuariosxFerias" name="hdn_filho_UsuariosxFerias" value="">
							<div class="col-md-6">
								<label>Data Admissão (Base)</label>
								<input class="form-control" readonly title="Data Admissão (Base)" name="vHUXFDATAADMISSAO" id="vHUXFDATAADMISSAO" value="<?= $vROBJETO['USUDATAADMISSAO']?>" type="date" >
							</div>
							<div class="col-md-6">
								<label>Tempo de Empresa Ano(s)</label>
								<input class="form-control" readonly title="Tempo de Empresa" name="vHUXFTEMPOEMPRESA" id="vHUXFTEMPOEMPRESA" value="<?= calcular_idade($vROBJETO['USUDATAADMISSAO'], '')?>" type="text" >
							</div>
							<div class="col-md-6">
								<label>Período Aquisitivo - Data Início
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Início" name="vDUXFDATAAQUISITIVO1" id="vDUXFDATAAQUISITIVO1" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data Fim/Previsão
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Fim/Previsão" name="vDUXFDATAAQUISITIVO2" id="vDUXFDATAAQUISITIVO2" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data limite Gozo
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data limite Gozo" name="vDUXFDATALIMITEGOZO" id="vDUXFDATALIMITEGOZO" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data Gozo Início</label>
								<input class="form-control" title="Data Gozo Início" name="vDUXFDATAGOZOINICIAL" id="vDUXFDATAGOZOINICIAL" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Data Gozo Final</label>
								<input class="form-control" title="Data Gozo Final" name="vDUXFDATAGOZOFINAL" id="vDUXFDATAGOZOFINAL" value="" type="date" >
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxFerias('modal_div_UsuariosxFerias','transactionUsuariosxFerias.php', 'div_UsuariosxFerias', 'UsuariosxFerias', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalUsuariosxRemuneracao">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mt-0" id="exampleModalLabel">Remuneração</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="row" id="modal_div_UsuariosxRemuneracao">
							<input type="hidden" id="hdn_pai_UsuariosxRemuneracao" name="hdn_pai_UsuariosxRemuneracao" value="<?= $vIOid;?>">
							<input type="hidden" id="hdn_filho_UsuariosxRemuneracao" name="hdn_filho_UsuariosxRemuneracao" value="">
							<div class="col-md-6">
								<label>Data Alteração
									<small class="text-danger font-13">*</small>
								</label>
								<input class="form-control divObrigatorio" title="Data Alteração" name="vDUXRDATAALTERACAOSALARIAL" id="vDUXRDATAALTERACAOSALARIAL" value="" type="date" >
							</div>
							<div class="col-md-6">
								<label>Novo Salário</label>
								<input class="form-control divObrigatorio classmonetario" name="vMUXRSALARIOATUAL" title="Novo Salário" id="vMUXRSALARIOATUAL"  type="text">
							</div>
							<div class="col-md-12">
								<label>Motivo Alteração</label>
								<textarea title="Motivo Alteração" class="form-control" id="vSUXRMOTIVOALTERACAOSALARIAL" name="vSUXRMOTIVOALTERACAOSALARIAL" rows="3"></textarea>							
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mt-3">
								<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalUsuariosxRemuneracao('modal_div_UsuariosxRemuneracao','transactionUsuariosxRemuneracao.php', 'div_UsuariosxRemuneracao', 'UsuariosxRemuneracao', '<?= $vIOid;?>')">Salvar</button>
							</div>
						</div>
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
			//Vínculo Empregatício
			var vAParameters =
			{
				 'vSTitulo': 'Vínculo Empregatício',
				 'vSTabTipo': 'USUARIOS - VINCULO',
				 'vSCampo': 'vIUSUVINCULO',
				 'vIValor': '<?php echo $vROBJETO['USUVINCULO']; ?>',
				 'vSDesabilitar' : '<?= $vSDisabled; ?>',
				 'vSDiv': 'divVinculoEmpregaticio',
				 'vSObrigatorio': 'S',
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