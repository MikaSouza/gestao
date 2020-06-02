<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(6);


if(isset($_FILES["input-file-now-custom-1"]) && !empty($_FILES["input-file-now-custom-1"]))
{
    $arquivo1 = $_FILES["input-file-now-custom-1"];
    if(file_exists($arquivo1["tmp_name"]))
    {
        $_POST['vSCLILOGOMARCA'] = $arquivo1["name"];
        $diretorio = "../assets/images/empresas";
        uploadArquivo($arquivo1, $diretorio, $arquivo1["name"]);
    }
}
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
//include_once 'transaction/transactionCidades.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../sistema/combos/comboCNAE.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
include_once __DIR__.'/combos/comboPaises.php';
include_once __DIR__.'/combos/comboEstados.php';

//print_r($vROBJETO);

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

    </head>
	<?php if ($vIOid > 0){ ?>
    <body onload="mostrarIE('<?= $vROBJETO['CLIISENTAIE']; ?>'); 
				  exibirCidades('<?= $vRENDERECOINPI['ESTCODIGO']; ?>', '<?= $vRENDERECOINPI['CIDCODIGO']; ?>', 'div_cidade_inpi', 'vHINPICIDCODIGO');
				  exibirCidades('<?= $vRENDERECOCOR['ESTCODIGO']; ?>', '<?= $vRENDERECOCOR['CIDCODIGO']; ?>', 'div_cidade_cor', 'vHCORCIDCODIGO');
				  exibirCidades('<?= $vRENDERECOCOB['ESTCODIGO']; ?>', '<?= $vRENDERECOCOB['CIDCODIGO']; ?>', 'div_cidade_cob', 'vHCOBCIDCODIGO'); ">
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
                                    <!-- Nav tabs -->
                                    <ul  class="nav nav-tabs" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-toggle="tab" href="#home-1" role="tab">Dados Gerais</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#contatos" role="tab">Contatos</a>
                                        </li>                                                                               
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#enderecos-1" role="tab">Endereços</a>
                                        </li>
										 <?php if($vIOid > 0){ ?>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSON('transactionClientesxGED.php', 'div_ged', 'ClientesxGED', '<?= $vIOid;?>');">Digitalizações/Arquivos</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#auditoria" role="tab" onclick="gerarGridJSON('transactionClientesxAuditoria.php', 'div_auditoria', 'ClientesxAuditoria', '<?= $vIOid;?>');">Auditoria</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">											
                                            <a class="nav-link" data-toggle="tab" href="#relacionados" role="tab" onclick="gerarGridJSON('transactionClientesxRelacionados.php', 'div_relacionados', 'ClientesxRelacionados', '<?= $vIOid;?>');">Clientes Relacionados</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#faturamento" role="tab" onclick="gerarGridJSON('transactionClientesxFaturamento.php', 'div_ClientesxFaturamento', 'ClientesxFaturamento', '<?= $vIOid;?>');">Faturamento</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#processos" role="tab" onclick="gerarGridJSON('transactionClientesxGeralProcessos.php', 'div_processos', 'ClientesxProcessos', '<?= $vIOid;?>');">Geral Processos</a>
                                        </li>
										<li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#historico" role="tab" onclick="gerarGridJSON('transactionClientesxHistorico.php', 'div_historico', 'ClientesxHistorico', '<?= $vIOid;?>');">Histórico Contatos</a>
                                        </li>
                                        <?php } ?>                                        
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">
											<div class="form-group row">
												<div class="col-md-4">
													<label>Tipo Pessoa
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>
												<div class="col-md-4 divJuridica">                                                        
													<label>CNPJ
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLICNPJ" id="vSCLICNPJ" type="text" title="CNPJ" value="<?= ($vIOid > 0 ? $vROBJETO['CLICNPJ'] : '');?>" >                                                       
												</div>
												<div class="col-md-4 divJuridica">
													<br/>				  
													<button type="button" class="btn btn-secondary waves-effect" onclick="buscarDadosReceita();">Buscar Dados Receita Federal</button><br>
												</div>	
												<div class="col-md-2 divFisica" >                                                        
													<label>CPF
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLICPF" id="vSCLICPF" type="text" title="CNPJ" value="<?= ($vIOid > 0 ? $vROBJETO['CLICNPJ'] : '');?>" >                                                       
												</div>
												<div class="col-md-2 divFisica">
                                                    <label>Data Nascimento</label>
                                                    <input class="form-control" title="Data Nascimento" name="vDCLIDATA_NASCIMENTO" id="vDCLIDATA_NASCIMENTO" value="<?= $vROBJETO['CLIDATA_NASCIMENTO'];  ?>" type="date" >
                                                </div>	
											</div>											
											<div class="form-group row">
												<div class="col-md-2">                                                      
													<label>Sigla
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classnumerico" name="vICLISEQUENCIAL" id="vICLISEQUENCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLISEQUENCIAL'] : ''); ?>" title="Sigla" >
												</div> 
                                                <div class="col-md-4">                                                      
													<label>Nome Cliente
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLINOME" id="vSCLINOME" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLINOME'] : ''); ?>" title="Nome Cliente" >
												</div>   
												<div class="col-md-3">
													<label>Tipo de Cadastro
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vICLITIPOCADASTRO" id="vICLITIPOCADASTRO" class="custom-select obrigatorio" title="Tipo de Cadastro">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('PARCEIROS - TIPO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLITIPOCADASTRO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                     
                                                </div>	
												<div class="col-md-3">
													<label>Status
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vICLIPOSICAO" id="vICLIPOSICAO" class="custom-select obrigatorio" title="Status">
                                                        <option value="">  -------------  </option>
														<?php 
														if ($vROBJETO['CLIPOSICAO'] == '') $vROBJETO['CLIPOSICAO'] = 30;
														foreach (comboTabelas('PARCEIROS - POSICAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLIPOSICAO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
                                                </div>
                                            </div>     
											<div class="form-group row">                                                												
                                                <div class="col-md-6">
                                                    <label>Representante
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vICLIRESPONSAVEL" id="vICLIRESPONSAVEL" class="custom-select obrigatorio" title="Representante">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboUsuarios() as $usuarios): ?>                                                            
															<option value="<?php echo $usuarios['USUCODIGO']; ?>" <?php if ($vROBJETO['CLIRESPONSAVEL'] == $usuarios['USUCODIGO']) echo "selected='selected'"; ?>><?php echo $usuarios['USUNOME']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-6">   
                                                    <label>Comissão</label>													
													<select class="form-control" name="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS" id="vS<?= $vAConfiguracaoTela['MENPREFIXO'];?>STATUS">
														<option value="S" <?php if ($vSDefaultStatusCad == "S") echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vSDefaultStatusCad == "N") echo "selected='selected'"; ?>>Não</option>
													</select>                                                  
                                                </div>
											</div>	
											<div class="form-group row">                                                												
                                                <div class="col-md-6">
                                                    <label>Anuidade Brasil</label>
													<select name="vSCLIANUIDADEBRASIL" id="vSCLIANUIDADEBRASIL" title="Anuidade Brasil" class="custom-select" >
														<option value="" >  ---------------------------  </option>
														<option value="N" <?php if ($vROBJETO['CLIANUIDADEBRASIL'] == 'N') echo 'selected'; ?>>Não paga Anuidade, Gerar só Prazos</option>
														<option value="S" <?php if ($vROBJETO['CLIANUIDADEBRASIL'] == 'S') echo 'selected'; ?>>Sim, paga Anuidade</option>		
														<option value="T" <?php if ($vROBJETO['CLIANUIDADEBRASIL'] == 'T') echo 'selected'; ?>>Taxa de Administração</option>
													</select>                                                   
												</div>
												<div class="col-md-6">   
                                                    <label>Anuidade Exterior</label>
													<select name="vSCLIANUIDADEEXTERIOR" id="vSCLIANUIDADEEXTERIOR" title="Anuidade Exterior" class="custom-select" >
														<option value="" >  ---------------------------  </option>
														<option value="N" <?php if ($vROBJETO['CLIANUIDADEEXTERIOR'] == 'N') echo 'selected'; ?>>Não paga Anuidade, Gerar só Prazos</option>
														<option value="S" <?php if ($vROBJETO['CLIANUIDADEEXTERIOR'] == 'S') echo 'selected'; ?>>Sim, paga Anuidade</option>
													</select>                                                  
                                                </div>
											</div>											
											<div class="form-group row">                                                												
                                                <div class="col-md-6">
                                                    <label>Motivo de Cancelamento</label>
                                                    <select name="vICLIMOTIVOCANCELAMENTO" id="vICLIMOTIVOCANCELAMENTO" class="custom-select" title="Motivo de Cancelamento">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('PROCESSOS - CANCELAMENTO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLIMOTIVOCANCELAMENTO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-6">
                                                    <label>Data Início Distrato</label>
                                                    <input class="form-control" name="vDCLIDATADISTRATO" id="vDCLIDATADISTRATO" value="<?= $vROBJETO['CLIDATADISTRATO'];  ?>" type="date" >
                                                </div>
											</div>
											<div class="form-group row divJuridica">                                                												
                                                <div class="col-md-6">
                                                    <label>Início das Atividades</label>
                                                    <input class="form-control" name="vDCLIDATA_INICIO_ATIVIDADES" id="vDCLIDATA_INICIO_ATIVIDADES" value="<?= $vROBJETO['CLIDATA_INICIO_ATIVIDADES'];  ?>" type="date" >                                                    
												</div>
												<div class="col-md-6">   
                                                    <label>Situação Receita Federal
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vICLISITUACAORECEITA" id="vICLISITUACAORECEITA" class="custom-select obrigatorio" title="Situação Receita Federal">
                                                        <option value="">  -------------  </option>
														<?php 
														if ($vROBJETO['CLISITUACAORECEITA'] == '') $vROBJETO['CLISITUACAORECEITA'] = 20;
														foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLISITUACAORECEITA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
											</div>
											<div class="form-group row divJuridica">                                                												
                                                <div class="col-md-6">
                                                    <label>Natureza Jurídica</label>
                                                    <select name="vICLINATUREZAJURIDICA" id="vICLINATUREZAJURIDICA" class="custom-select" title="Natureza Jurídica">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('EMPRESAS - NATUREZA JURIDICA') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLINATUREZAJURIDICA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
												<div class="col-md-6">   
                                                    <label>Regime Tributário</label>
                                                    <select name="vICLIREGIMETRIBUTARIO" id="vICLIREGIMETRIBUTARIO" class="custom-select" title="Regime Tributário">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('EMPRESAS - REGIME TRIBUTARIO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['CLIREGIMETRIBUTARIO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
											</div>
                                            <div class="form-group row divJuridica">
                                                <div class="col-md-3">   
                                                    <label>Inscrição Municipal</label>
                                                    <input class="form-control" name="vSCLIIM" id="vSCLIIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : '');?>" >
                                                </div>
                                                
                                                <div class="col-md-3">   
                                                    <label>Inscrição Estadual</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>
                                                <!-- estadual -->
                                                <div class="col-sm-3 divIE" id="divIE">
                                                    <label>Nº Insc. Estadual</label>
                                                    <input class="form-control" title="Nº Insc. Estadual" name="vSCLIIE" id="vSCLIIE" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIE'] : '');?>" >
                                                </div>
                                                 
                                            </div>
                                            <div class="form-group row divJuridica">
                                                <div class="col-md-9">   
                                                    <label>CNAE Principal</label>
                                                    <select name="vICNACODIGO" id="vICNACODIGO" class="custom-select" title="Situação Receita Federal">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboCNAE() as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['CNACODIGO']; ?>" <?php if ($vROBJETO['CNACODIGO'] == $tabelas['CNACODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['CNACNAE'].' - '.$tabelas['CNADESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
                                                <div class="col-md-3">   
                                                    <label>Optante pelo simples nacional?</label>
                                                    <select title="Estado Civil" id="vSCLIOPTANTESIMPLESNACIONAL" class="custom-select" name="vSCLIOPTANTESIMPLESNACIONAL" onchange="mostraCampo('E', this.value);">
														<option value="">  -------------  </option>
                                                        <option value="S" <?php if ($vROBJETO['CLIOPTANTESIMPLESNACIONAL'] == 'S') echo "selected='selected'"; ?>>Sim</option>
                                                        <option value="N" <?php if ($vROBJETO['CLIOPTANTESIMPLESNACIONAL'] == 'N') echo "selected='selected'"; ?>>Não</option>
                                                    </select>
                                                </div>
                                            </div>												
                                    </div>
                                    

									<!-- Aba Contatos -->
									<div class="tab-pane p-3" id="contatos" role="tabpanel">
										<fieldset>
											<legend>Ministério da Fazenda (INPI)</legend>
											<input type="hidden" name="vHINPICONCODIGO" id="vHINPICONCODIGO" value="<?= $vRCONTATOINPI['CONCODIGO']; ?>"/>
                                            <div class="form-group row">
												<div class="col-sm-6">
													<label>Contato</label>
													<input class="form-control obrigatorio" title="Contato (INPI)" name="vHINPICONNOME" id="vHINPICONNOME" type="text" value="<?= $vRCONTATOINPI['CONNOME'];?>" >
												</div>
												<div class="col-sm-3">
													<label>Telefone Principal</label>
													<input type="text" id="vHINPICONFONE" name="vHINPICONFONE" class="form-control obrigatorio" maxlength="14" title="Telefone Principal" value="<?= $vRCONTATOINPI['CONFONE']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>
												<div class="col-sm-3">
													<label>Telefone Celular</label>													
													<input type="text" id="vHINPICONCELULAR" name="vHINPICONCELULAR" class="form-control" maxlength="15" title="Telefone Celular" value="<?= $vRCONTATOINPI['CONCELULAR']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>E-mail</label>
													<input class="form-control obrigatorio" title="E-mail" name="vHINPICONEMAIL" id="vHINPICONEMAIL" type="email" value="<?= $vRCONTATOINPI['CONEMAIL'];?>" >
												</div>
												<div class="col-sm-6">
													<label>Site</label>
													<input class="form-control" name="vSCLISITE" id="vSCLISITE" type="text" value="<?= $vROBJETO['CLISITE'];  ?>" >
												</div>
											</div>			
											<div class="accordion" id="accordionExample-faq">												
												<div class="card shadow-none border mb-1">
													<div class="card-header" id="headingThree">
													<h5 class="my-0">
														<button class="btn btn-link collapsed ml-4" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
															Instruções especiais para efetuar contato com cliente
														</button>
													</h5>
													</div>
													<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample-faq">
													<div class="card-body">
														<textarea class="form-control" id="vHINPICONOBSERVACOES" name="vHINPICONOBSERVACOES" rows="3"><?= $vROBJETO['CONOBSERVACOES']; ?></textarea>
													</div>
													</div>
												</div>                                                
											</div><!--end accordion-->			
                                        </fieldset>
										<fieldset>
											<legend>Correspondência</legend>
											<input type="hidden" name="vHCORCONCODIGO" id="vHCORCONCODIGO" value="<?= $vRCONTATOCOR['CONCODIGO']; ?>"/>
											<div class="form-group row">
												<div class="col-md-4 fa-pull-right ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillContatoINPI('INPI', 'COR');">Preencher Dados Ministério da Fazenda (INPI)</button><br>
												</div>
												<div class="col-md-3 fa-pull-left ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillContatoINPI('COB', 'COR');">Preencher Dados Cobrança</button><br>
												</div>
											</div>	
                                            <div class="form-group row">
												<div class="col-sm-6">
													<label>Contato</label>
													<input class="form-control obrigatorio" title="Contato Correspondência" name="vHCORCONNOME" id="vHCORCONNOME" type="text" value="<?= $vRCONTATOCOR['CONNOME'];?>" >
												</div>
												<div class="col-sm-3"> 
													<label>Telefone Principal</label>													
													<input type="text" id="vHCORCONFONE" name="vHCORCONFONE" class="form-control obrigatorio" maxlength="14" title="Telefone Principal" value="<?= $vRCONTATOCOR['CONFONE']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>
												<div class="col-sm-3">
													<label>Telefone Celular</label>													
													<input type="text" id="vHCORCONCELULAR" name="vHCORCONCELULAR" class="form-control" maxlength="15" title="Telefone Celular" value="<?= $vRCONTATOCOR['CONCELULAR']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>E-mail</label>
													<input class="form-control obrigatorio" title="E-mail" name="vHCORCONEMAIL" id="vHCORCONEMAIL" type="email" value="<?= $vRCONTATOCOR['CONEMAIL'];?>" >
												</div>
											</div>	
											<div class="accordion" id="accordionExample-faq">
												<div class="card shadow-none border mb-1">
													<div class="card-header" id="headingOne">
													<h5 class="my-0">
														<button class="btn btn-link ml-4" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
															Instruções especiais para envio de correspondência
														</button>
													</h5>
													</div>
												
													<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample-faq">
													<div class="card-body">
														<textarea class="form-control" id="vHCORCONOBSERVACOES" name="vHCORCONOBSERVACOES" rows="3"><?= $vRCONTATOCOR['CONOBSERVACOES']; ?></textarea>
													</div>
													</div>
												</div>
											</div><!--end accordion-->			
                                        </fieldset>
										<fieldset>
											<legend>Cobrança</legend>
											<input type="hidden" name="vHCOBCONCODIGO" id="vHCOBCONCODIGO" value="<?= $vRCONTATOCOB['CONCODIGO']; ?>"/>
											<div class="form-group row">
												<div class="col-md-4 fa-pull-right ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillContatoINPI('INPI', 'COB');">Preencher Dados Ministério da Fazenda (INPI)</button><br>
												</div>
												<div class="col-md-3 fa-pull-left ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillContatoINPI('COR', 'COB');">Preencher Dados Correspondência</button><br>
												</div>
											</div>
                                            <div class="form-group row">
												<div class="col-sm-6">
													<label>Contato</label>
													<input class="form-control obrigatorio" title="Contato Cobrança" name="vHCOBCONNOME" id="vHCOBCONNOME" type="text" value="<?= $vRCONTATOCOB['CONNOME'];?>" >
												</div>
												<div class="col-sm-3">
													<label>Telefone Principal</label>													
													<input type="text" id="vHCOBCONFONE" name="vHCOBCONFONE" class="form-control obrigatorio" maxlength="14" title="Telefone Principal" value="<?= $vRCONTATOCOB['CONFONE']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>
												<div class="col-sm-3">
													<label>Telefone Celular</label>													
													<input type="text" id="vHCOBCONCELULAR" name="vHCOBCONCELULAR" class="form-control" maxlength="15" title="Telefone Celular" value="<?= $vRCONTATOCOB['CONCELULAR']; ?>"  onKeyPress="return digitos(event, this);" onkeyup="mascara('TEL', this, event)" />
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-sm-6">
													<label>E-mail</label>
													<input class="form-control obrigatorio" title="E-mail" name="vHCOBCONEMAIL" id="vHCOBCONEMAIL" type="email" value="<?= $vRCONTATOCOB['CONEMAIL'];?>" >
												</div>
											</div>	
											<div class="accordion" id="accordionExample-faq">												
												<div class="card shadow-none border mb-1">
													<div class="card-header" id="headingTwo">
													<h5 class="my-0">
														<button class="btn btn-link collapsed ml-4 align-self-center" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
															Instruções especiais para envio de fatura
														</button>
													</h5>
													</div>
													<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample-faq">
													<div class="card-body">
														<textarea class="form-control" id="vHCOBCONOBSERVACOES" name="vHCOBCONOBSERVACOES" rows="3"><?= $vRCONTATOCOB['CONOBSERVACOES']; ?></textarea>
													</div>
													</div>
												</div>
											</div><!--end accordion-->			
                                        </fieldset>										
									</div>
									<!-- Aba Contatos end -->
										
									<!-- Aba Dados Documento -->
                                    <div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
										<fieldset>
											<legend>Ministério da Fazenda (INPI)</legend>
											<input type="hidden" name="vHINPIENDCODIGO" id="vHINPIENDCODIGO" value="<?= $vRENDERECOINPI['ENDCODIGO']; ?>"/>
                                            <div class="form-group row">
												<div class="col-sm-3">
													<label>País</label>
													<select title="País" id="vHINPIPAICODIGO" class="custom-select obrigatorio" name="vHINPIPAICODIGO">
														<?php 
														if ($vRENDERECOINPI['PAICODIGO'] == '') $vRENDERECOINPI['PAICODIGO'] = 30;
														foreach (comboPaises() as $tabelas): ?>
															<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vRENDERECOINPI['PAICODIGO'] == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>CEP</label>
													<input class="form-control obrigatorio" title="CEP" name="vHINPIENDCEP" id="vHINPIENDCEP" type="text" value="<?= $vRENDERECOINPI['ENDCEP'];  ?>" onblur="buscarCEP(this.value, 'INPI');">
												</div>
												<div class="col-sm-3">
													<label>Bairro</label>
													<input class="form-control obrigatorio" title="Bairro" name="vHINPIENDBAIRRO" id="vHINPIENDBAIRRO" type="text" value="<?= $vRENDERECOINPI['ENDBAIRRO'];  ?>" >
												</div>											
											</div>											
											<div class="form-group row">
												<div class="col-sm-4">
													<label>Endereço</label>
													<input class="form-control obrigatorio" title="Endereço" name="vHINPIENDLOGRADOURO" id="vHINPIENDLOGRADOURO" type="text" value="<?= $vRENDERECOINPI['ENDLOGRADOURO'];?>">
												</div>
												<div class="col-sm-2">
													<label>Nº</label>
													<input class="form-control" name="vHINPIENDNROLOGRADOURO" id="vHINPIENDNROLOGRADOURO" type="text" value="<?= $vRENDERECOINPI['ENDNROLOGRADOURO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Complemento</label>
													<input class="form-control" name="vHINPIENDCOMPLEMENTO" id="vHINPIENDCOMPLEMENTO" type="text" value="<?= $vRENDERECOINPI['ENDCOMPLEMENTO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Estado</label>
													<select title="Estado" id="vHINPIESTCODIGO" class="custom-select obrigatorio" name="vHINPIESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade_inpi', 'vHINPICIDCODIGO');">
														<?php foreach (comboEstados() as $tabelas): ?>
															<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vRENDERECOINPI['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-2">
													<div id="div_cidade_inpi"></div>
												</div>
											</div>											
                                        </fieldset>
                                        <fieldset>
											<legend>Correspondência</legend>
											<input type="hidden" name="vHCORENDCODIGO" id="vHCORENDCODIGO" value="<?= $vRENDERECOCOR['ENDCODIGO']; ?>"/>
											<div class="form-group row">
												<div class="col-md-4 fa-pull-right ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillINPI('INPI', 'COR');">Preencher Dados Ministério da Fazenda (INPI)</button><br>
												</div>
												<div class="col-md-3 fa-pull-left ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillINPI('COB', 'COR');">Preencher Dados Cobrança</button><br>
												</div>
											</div>
                                            <div class="form-group row">
												<div class="col-sm-3">
													<label>País</label>
													<select title="País" id="vHCORPAICODIGO" class="custom-select obrigatorio" name="vHCORPAICODIGO">
														<?php foreach (comboPaises() as $tabelas): ?>
															<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vRENDERECOCOR['PAICODIGO'] == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>CEP</label>
													<input class="form-control obrigatorio" title="CEP" name="vHCORENDCEP" id="vHCORENDCEP" type="text" value="<?= $vRENDERECOCOR['ENDCEP'];  ?>" onblur="buscarCEP(this.value, 'COR');">
												</div>
												<div class="col-sm-3">
													<label>Bairro</label>
													<input class="form-control obrigatorio" title="Bairro" name="vHCORENDBAIRRO" id="vHCORENDBAIRRO" type="text" value="<?= $vRENDERECOCOR['ENDBAIRRO'];  ?>" >
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-sm-4">
													<label>Endereço</label>
													<input class="form-control obrigatorio" title="Endereço" name="vHCORENDLOGRADOURO" id="vHCORENDLOGRADOURO" type="text" value="<?= $vRENDERECOCOR['ENDLOGRADOURO'];?>">
												</div>
												<div class="col-sm-2">
													<label>Nº</label>
													<input class="form-control" name="vHCORENDNROLOGRADOURO" id="vHCORENDNROLOGRADOURO" type="text" value="<?= $vRENDERECOCOR['ENDNROLOGRADOURO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Complemento</label>
													<input class="form-control" name="vHCORENDCOMPLEMENTO" id="vHCORENDCOMPLEMENTO" type="text" value="<?= $vRENDERECOCOR['ENDCOMPLEMENTO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Estado</label>
													<select title="Estado" id="vHCORESTCODIGO" class="custom-select obrigatorio" name="vHCORESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade_cor', 'vHCORCIDCODIGO');">
														<?php foreach (comboEstados() as $tabelas): ?>
															<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vRENDERECOCOR['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-2">
													<div id="div_cidade_cor"></div>
												</div>
											</div>												
                                        </fieldset>
										<fieldset>
											<legend>Cobrança</legend>
											<input type="hidden" name="vHCOBENDCODIGO" id="vHCOBENDCODIGO" value="<?= $vRENDERECOCOB['ENDCODIGO']; ?>"/>
											<div class="form-group row">
												<div class="col-md-4 fa-pull-right ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillINPI('INPI', 'COB');">Preencher Dados Ministério da Fazenda (INPI)</button><br>
												</div>
												<div class="col-md-3 fa-pull-left ">
													<button type="button" class="btn btn-secondary waves-effect" onclick="fillINPI('COR', 'COB');">Preencher Dados Correspondência</button><br>
												</div>
											</div>
                                            <div class="form-group row">
												<div class="col-sm-3">
													<label>País</label>
													<select title="País" id="vHCOBPAICODIGO" class="custom-select obrigatorio" name="vHCOBPAICODIGO">
														<?php foreach (comboPaises() as $tabelas): ?>
															<option value="<?php echo $tabelas['PAICODIGO']; ?>" <?php if ($vRENDERECOCOB['PAICODIGO'] == $tabelas['PAICODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['PAIDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>CEP</label>
													<input class="form-control obrigatorio" title="CEP" name="vHCOBENDCEP" id="vHCOBENDCEP" type="text" value="<?= $vRENDERECOCOB['ENDCEP'];  ?>" onblur="buscarCEP(this.value, 'COB');">
												</div>
												<div class="col-sm-3">
													<label>Bairro</label>
													<input class="form-control obrigatorio" title="Bairro" name="vHCOBENDBAIRRO" id="vHCOBENDBAIRRO" type="text" value="<?= $vRENDERECOCOB['ENDBAIRRO'];  ?>" >
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-sm-4">
													<label>Endereço</label>
													<input class="form-control obrigatorio" title="Endereço" name="vHCOBENDLOGRADOURO" id="vHCOBENDLOGRADOURO" type="text" value="<?= $vRENDERECOCOB['ENDLOGRADOURO'];?> " >
												</div>
												<div class="col-sm-2">
													<label>Nº</label>
													<input class="form-control" name="vHCOBENDNROLOGRADOURO" id="vHCOBENDNROLOGRADOURO" type="text" value="<?= $vRENDERECOCOB['ENDNROLOGRADOURO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Complemento</label>
													<input class="form-control" name="vHCOBENDCOMPLEMENTO" id="vHCOBENDCOMPLEMENTO" type="text" value="<?= $vRENDERECOCOB['ENDCOMPLEMENTO'];?>" >
												</div>
												<div class="col-sm-2">
													<label>Estado</label>
													<select title="Estado" id="vHCOBESTCODIGO" class="custom-select obrigatorio" name="vHCOBESTCODIGO" onchange="exibirCidades(this.value, '', 'div_cidade_cob', 'vHCOBCIDCODIGO');">
														<?php foreach (comboEstados() as $tabelas): ?>
															<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vRENDERECOCOB['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-2">
													<div id="div_cidade_cob"></div>
												</div>
											</div>
                                        </fieldset>	
                                    </div>
									
									<!-- Aba Dados GED -->
                                    <div class="tab-pane p-3" id="ged-1" role="tabpanel">
										<div id="modal_div_ClientesxGED">
											<div class="form-group row">                                                												
												<div class="col-md-4">   
													<label>Tipo Arquivo
														<small class="text-danger font-13">*</small>
													</label>
													<select name="vHGED" id="vHGED" class="custom-select divObrigatorio" title="Tipo Arquivo">
														<option value="">  -------------  </option>
														<?php foreach (comboTabelas('GED - TIPO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" ><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
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
									
									<!-- Aba Dados GED -->
                                    <div class="tab-pane p-3" id="auditoria" role="tabpanel">
		
										<div class="form-group row">
											<div id="div_auditoria" class="table-responsive"></div>
										</div>		
										
									</div>
									
									<div class="tab-pane p-3" id="relacionados" role="tabpanel">
										<div class="form-group row">
											<div id="div_relacionados" class="table-responsive"></div>
										</div>
									</div>
																		
									<div class="tab-pane p-3" id="faturamento" role="tabpanel">
										<div class="form-group row">
											<div id="div_ClientesxFaturamento" class="table-responsive"></div>
										</div>
									</div>
									
									<div class="tab-pane p-3" id="processos" role="tabpanel">
										<div class="form-group row">
											<div id="div_processos" class="table-responsive"></div>
										</div>
									</div>
									
									<div class="tab-pane p-3" id="historico" role="tabpanel">
										<div class="form-group row">
											<div id="div_historico" class="table-responsive"></div>
										</div>
									</div>
                                        <!-- Aba Dados Documentos end -->

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
			<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalClientesxRelacionados">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title mt-0" id="exampleModalLabel">Clientes Relacionados</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row" id="modal_div_ClientesxRelacionados">
								<input type="hidden" id="hdn_pai_ClientesxRelacionados" name="hdn_pai_ClientesxRelacionados" value="<?= $vIOid;?>">
								<input type="hidden" id="hdn_filho_ClientesxRelacionados" name="hdn_filho_ClientesxRelacionados" value="">
								<div class="col-md-12">
									<label>Sigla Cliente
										<small class="text-danger font-13">*</small>
									</label>
									<input class="form-control divObrigatorio classnumerico" name="vHCLICODIGOREL" id="vHCLICODIGOREL" type="text" value="" title="Sigla" >			
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mt-3">
									<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalClientesxRelacionados('modal_div_ClientesxRelacionados','transactionClientesxRelacionados.php', 'div_relacionados', 'ClientesxRelacionados', '<?= $vIOid;?>');">Salvar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
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
								<input type="hidden" id="hdn_pai_ClientesxHistorico" name="hdn_pai_ClientesxHistorico" value="<?= $vIOid;?>">
								<input type="hidden" id="hdn_filho_ClientesxHistorico" name="hdn_filho_ClientesxHistorico" value="">								
								<div class="col-md-6">
									<label>Data Contato
										<small class="text-danger font-13">*</small>
									</label>
									<input class="form-control divObrigatorio" title="Data Contato" name="vDCHGDATA" id="vDCHGDATA" value="" type="date" >
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">   
									<label>Tipo Contato
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGTIPO" id="vICHGTIPO" class="custom-select divObrigatorio" title="Tipo Contato">
										<option value="">  -------------  </option>
										<?php 									
										foreach (comboTabelas('HISTORICO GERAL - TIPO') as $tabelas): ?>                                                            
											<option value="<?php echo $tabelas['TABCODIGO']; ?>"><?php echo $tabelas['TABDESCRICAO']; ?></option>
										<?php endforeach; ?>
									</select>                                                    
								</div>
								<div class="col-md-6">   
									<label>Status
										<small class="text-danger font-13">*</small>
									</label>
									<select name="vICHGPOSICAO" id="vICHGPOSICAO" class="custom-select divObrigatorio" title="Status">
										<option value="">  -------------  </option>
										<?php 									
										foreach (comboTabelas('HISTORICO GERAL - STATUS') as $tabelas): ?>                                                            
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
									<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right" onclick="salvarModalClientesxHistorico('modal_div_ClientesxHistorico','transactionClientesxHistorico.php', 'div_ClientesxHistorico', 'ClientesxHistorico', '<?= $vIOid;?>');">Salvar</button>
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
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="js/cadClientes.js"></script>
    </body>
</html>