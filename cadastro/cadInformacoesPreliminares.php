<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(2030);
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
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
		<link rel="stylesheet" href="../assets/css/stylesUpload.css"/> 
		 
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
                                            <a class="nav-link" data-toggle="tab" href="#ged-1" role="tab" onclick="gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', '<?= $vIOid;?>', '6');">Digitalizações/Arquivos</a>
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
													<input class="form-control obrigatorio" name="vSCLIRAZAOSOCIAL" id="vSCLIRAZAOSOCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>" title="Razão Social" >
												</div>  
												<div class="col-md-6">                                                      
													<label>2 - Número de habitantes do município
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLINOMEFANTASIA" id="vSCLINOMEFANTASIA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLINOMEFANTASIA'] : ''); ?>" title="Nome Fantasia" >
												</div>	
                                            </div>
											<div class="form-group row">	
												<div class="col-sm-6">
													<label>3 - Valor do orçamento anual
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor Total" name="vMCTRVALORARECEBER" id="vMCTRVALORARECEBER" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRVALORARECEBER'], false); }?>" type="text" >
												</div>  
												<div class="col-md-6">   
                                                    <label>4 - Número de servidores (inclui agentes políticos e ccs)</label>
                                                    <input class="form-control" name="vSCLIIM" id="vSCLIIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : '');?>" >
                                                </div>
														
											</div>		
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>5 - Descrever a Lei que instituiu o Sistema de Controle Interno e suas alterações, anexando cópia</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>6 - A Unidade de Controle Interno possui Regimento Interno?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
										</div>
                                    
										<!-- Aba Contatos -->
										<div class="tab-pane p-3" id="contatos" role="tabpanel">
											<div class="form-group row">
                                                <div class="col-md-6">                                                      
													<label>7.1 - O responsável pelo Sistema de Controle Interno ocupa o cargo de
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLIRAZAOSOCIAL" id="vSCLIRAZAOSOCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>" title="Razão Social" >
												</div>  
												<div class="col-md-6">                                                      
													<label>Nome do Responsável
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLINOMEFANTASIA" id="vSCLINOMEFANTASIA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLINOMEFANTASIA'] : ''); ?>" title="Nome Fantasia" >
												</div>	
                                            </div>
											<div class="form-group row">	
												<div class="col-sm-6">
													<label>Formação
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor Total" name="vMCTRVALORARECEBER" id="vMCTRVALORARECEBER" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRVALORARECEBER'], false); }?>" type="text" >
												</div>  
												<div class="col-md-6">   
                                                    <label>Tempo de Experiência no Controle Interno</label>
                                                    <input class="form-control" name="vSCLIIM" id="vSCLIIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : '');?>" >
                                                </div>
														
											</div>		
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Tem dedicação exclusiva no Controle Interno</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Qual cargo que ocupa no Município
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>É servidor de Carreira?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Está em estágio probatório?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Realizou algum curso de atualização na área de Controle Interno da Administração Pública nos últimos 12 meses?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
											</div>
										</div>
										<!-- Aba Contatos end -->
											
										<!-- Aba Dados Documento -->
										<div class="tab-pane p-3" id="enderecos-1" role="tabpanel">
											<div class="form-group row">
                                                <div class="col-md-6">                                                      
													<label>Nome do Membro
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLIRAZAOSOCIAL" id="vSCLIRAZAOSOCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>" title="Razão Social" >
												</div>  
												<div class="col-md-6">                                                      
													<label>Formação
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLINOMEFANTASIA" id="vSCLINOMEFANTASIA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLINOMEFANTASIA'] : ''); ?>" title="Nome Fantasia" >
												</div>	
                                            </div>
											<div class="form-group row">	
												<div class="col-sm-6">
													<label>Tempo de Experiência no Controle Interno
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor Total" name="vMCTRVALORARECEBER" id="vMCTRVALORARECEBER" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRVALORARECEBER'], false); }?>" type="text" >
												</div>  
												<div class="col-md-6">   
                                                    <label>Tem dedicação exclusiva no Controle Interno</label>
                                                    <input class="form-control" name="vSCLIIM" id="vSCLIIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : '');?>" >
                                                </div>
														
											</div>		
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Qual cargo que ocupa no Município</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>É servidor de Carreira?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>	
											</div>	
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Está em estágio probatório?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Realizou algum curso de atualização na área de Controle Interno da Administração Pública nos últimos 12 meses?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="J" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Jurídica</option>
														<option value="F" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Física</option>														
													</select>
												</div>	
											</div>			
										</div>									
										<!-- Aba Dados GED -->																															

										<div class="tab-pane p-3" id="contratos" role="tabpanel">
											<div class="form-group row">
                                                <div class="col-md-6">                                                      
													<label>A Unidade de Controle Interno possui um planejamento/programa de trabalho com a descrição das suas atividades?
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLIRAZAOSOCIAL" id="vSCLIRAZAOSOCIAL" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIRAZAOSOCIAL'] : ''); ?>" title="Razão Social" >
												</div>  
												<div class="col-md-6">                                                      
													<label>Procedimentos de Auditoria:
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio" name="vSCLINOMEFANTASIA" id="vSCLINOMEFANTASIA" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLINOMEFANTASIA'] : ''); ?>" title="Nome Fantasia" >
												</div>	
                                            </div>
											<div class="form-group row">	
												<div class="col-sm-6">
													<label>Verificações, Controles e Acompanhamentos:
														<small class="text-danger font-13">*</small>
													</label>
													<input class="form-control obrigatorio classmonetario" title="Valor Total" name="vMCTRVALORARECEBER" id="vMCTRVALORARECEBER" value="<?php if(isset($vIOid)){ echo formatar_moeda($vROBJETO['CTRVALORARECEBER'], false); }?>" type="text" >
												</div>  
												<div class="col-md-6">   
                                                    <label>Descrever as espécies de verificações, controles e acompanhamentos realizados no exercício anterior e no corrente:</label>
                                                    <input class="form-control" name="vSCLIIM" id="vSCLIIM" type="text" value="<?= ($vIOid > 0 ? $vROBJETO['CLIIM'] : '');?>" >
                                                </div>
														
											</div>		
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Auditoria:</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Nos últimos 06 meses, quantos foram emitidos?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Acompanhamento:</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Nos últimos 06 meses, quantos foram emitidos?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>O envio dos relatórios emitidos é realizado/direcionado para quem?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>Os responsáveis pelo recebimento dos relatórios emitidos pela Unidade de Controle Interno costumam responder identificando as medidas que foram ou serão adotadas com relação aos apontamentos de inconformidades e recomendações?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Os relatórios ou outros atos produzidos pela Unidade de Controle Interno são entregues diretamente nos setores ou por meio de protocolo?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>A Unidade de Controle Interno emite Normas Internas Operacionais?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Se a resposta foi sim, qual ato administrativo foi usado para implantação das normas?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>A Unidade de Controle Interno costuma registrar atividades em atas?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
											<div class="form-group row">     
												<div class="col-md-6">   
                                                    <label>Se a resposta é sim, quais tipos de atividades?</label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSCLIISENTAIE" class="custom-select" name="vSCLIISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['CLIISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['CLIISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>  
												<div class="col-md-6">
													<label>A Unidade de Controle Interno costuma participar de reuniões setoriais?
														<small class="text-danger font-13">*</small>
													</label>
													<select title="Tipo Pessoa" id="vSCLITIPOCLIENTE" class="custom-select obrigatorio" name="vSCLITIPOCLIENTE" onchange="mostrarJxF(this.value);">
														<option value="S" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'J') echo "selected='selected'"; ?>>Sim</option>
														<option value="N" <?php if ($vROBJETO['CLITIPOCLIENTE'] == 'F') echo "selected='selected'"; ?>>Não</option>														
													</select>
												</div>	
											</div>
										</div>
										
										<div class="tab-pane p-3" id="ged-1" role="tabpanel">
											<div class="form-group"> 
												<div class="area-upload">
													<label for="upload-file" class="label-upload">
														<i class="fas fa-cloud-upload-alt"></i>
														<div class="texto">Clique ou arraste o(s) arquivo(s) para esta área <br/>
														Formatos permitidos (PDF, Word/Doc e Excel)
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
		<script src="../assets/plugins/select2/select2.min.js"></script>
		
        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="js/cadInformacoesPreliminares.js"></script>
		<script src="js/scriptUpload.js"></script>
    </body>
</html>
