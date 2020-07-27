<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
$vAConfiguracaoTela = configuracoes_menu_acesso(1946);

if(isset($_FILES["input-file-now-custom-1"]) && !empty($_FILES["input-file-now-custom-1"]))
{
    $arquivo1 = $_FILES["input-file-now-custom-1"];
    if(file_exists($arquivo1["tmp_name"]))
    {
        $_POST['vSEMPLOGOMARCA'] = $arquivo1["name"];
        $diretorio = "../assets/images/empresas";
        uploadArquivo($arquivo1, $diretorio, $arquivo1["name"]);
    }
}
include_once __DIR__.'/transaction/'.$vAConfiguracaoTela['MENARQUIVOTRAN'];
include_once __DIR__.'/../cadastro/combos/comboEstados.php';
include_once __DIR__.'/../cadastro/combos/comboTabelas.php';
include_once __DIR__.'/../cadastro/combos/comboTipoLogradouro.php';
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
    <body onload="mostrarIE('<?= $vROBJETO['EMPISENTAIE']; ?>'); exibirCidades('<?= $vROBJETO['ESTCODIGO']; ?>', '<?= $vROBJETO['CIDCODIGO']; ?>');">

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
                                            <a class="nav-link" data-toggle="tab" href="#end-1" role="tab">Endereço</a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-toggle="tab" href="#contacts-1" role="tab">Contatos</a>
                                        </li>
                                    </ul>
                                    <!-- Nav tabs end -->

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <!-- Aba Dados Gerais -->
                                        <div class="tab-pane active p-3" id="home-1" role="tabpanel">

											<div class="form-group row">
                                                <div class=" col-md-4 col-lg-4">
                                                    <?php 
                                                    if ($vROBJETO['EMPLOGOMARCA'] != '') 
                                                        $vSLOGO = '../assets/images/empresas/'. $vROBJETO['EMPLOGOMARCA'];
                                                    else 
                                                        $vSLOGO = '../assets/images/users/user-1.jpg'; ?>                                            
                                                        <input type="file" id="input-file-now-custom-1" name="input-file-now-custom-1" class="dropify" data-default-file="<?= $vSLOGO;?>"/><br>
											    </div>
                                                <div class="col-md-8 col-lg-8">
                                                    <div class="col-lg-6 fa-pull-left">                                                        
                                                        <label>CNPJ
                                                            <small class="text-danger font-13">*</small>
                                                        </label>
                                                        <input class="form-control obrigatorio" name="vSEMPCNPJ" id="vSEMPCNPJ" type="text" title="CNPJ" value="<?= $vROBJETO['EMPCNPJ'];?>" >                                                       
                                                    </div>
                                                    <div class="col-lg-6 fa-pull-right" style="padding-top:25px" >  
                                                                              
                                                        <button type="button" class="btn btn-secondary waves-effect">Buscar Dados Receita Federal</button><br>
                                                    </div>
                                                    <div class="col-lg-12 fa-pull-left">                                                       
                                                        <label>Razão Social
                                                            <small class="text-danger font-13">*</small>
                                                        </label>
                                                        <input class="form-control obrigatorio" name="vSEMPRAZAOSOCIAL" id="vSEMPRAZAOSOCIAL" type="text" value="<?= $vROBJETO['EMPRAZAOSOCIAL']; ?>" title="Razão Social" >
                                                    </div>
                                                    <div class="col-lg-12 fa-pull-left">
                                                        <label>Nome Fantasia
                                                            <small class="text-danger font-13">*</small>
                                                        </label>
                                                        <input class="form-control obrigatorio" name="vSEMPNOMEFANTASIA" id="vSEMPNOMEFANTASIA" type="text" value="<?= $vROBJETO['EMPNOMEFANTASIA']; ?>" title="Nome Fantasia" >
                                                    </div>                                                                                                       
                                                </div>    												
                                            </div>   
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>Início das Atividades
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <input class="form-control obrigatorio" name="vDEMPDATA_INICIO_ATIVIDADES" id="vDEMPDATA_INICIO_ATIVIDADES" value="<?= $vROBJETO['EMPDATA_INICIO_ATIVIDADES'];  ?>" type="date" >
                                                </div>
												<div class="col-md-3">
                                                    <label>Situação Receita Federal
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
													<select name="vIEMPSITUACAORECEITA" id="vIEMPSITUACAORECEITA" class="custom-select obrigatorio" title="Situação Receita Federal">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('RECEITA FEDERAL - SITUACAO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['EMPSITUACAORECEITA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Natureza Jurídica
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vIEMPNATUREZAJURIDICA" id="vIEMPNATUREZAJURIDICA" class="custom-select obrigatorio" title="Natureza Jurídica">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('EMPRESAS - NATUREZA JURIDICA') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['EMPNATUREZAJURIDICA'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
												</div>
											</div>
                                            <div class="form-group row">
                                                <div class="col-md-3">   
                                                    <label>Inscrição Municipal
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <input class="form-control obrigatorio" name="vSEMPIM" id="vSEMPIM" type="text" value="<?= $vROBJETO['EMPIM'];?>" >
                                                </div>
                                                <div class="col-md-3">   
                                                    <label>Regime Tributário
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select name="vIEMPREGIMETRIBUTARIO" id="vIEMPREGIMETRIBUTARIO" class="custom-select obrigatorio" title="Regime Tributário">
                                                        <option value="">  -------------  </option>
														<?php foreach (comboTabelas('EMPRESAS - REGIME TRIBUTÁRIO') as $tabelas): ?>                                                            
															<option value="<?php echo $tabelas['TABCODIGO']; ?>" <?php if ($vROBJETO['EMPREGIMETRIBUTARIO'] == $tabelas['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TABDESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>                                                    
                                                </div>
                                                <div class="col-md-3">   
                                                    <label>Inscrição Estadual
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select title="Inscrição Estadual" title="Inscrição Estadual" id="vSEMPISENTAIE" class="custom-select obrigatorio" name="vSEMPISENTAIE">
                                                        <option value="N" <?php if ($vROBJETO['EMPISENTAIE'] == 'N') echo "selected='selected'"; ?>>NÃO ISENTO INSCR. ESTADUAL</option>
                                                        <option value="S" <?php if ($vROBJETO['EMPISENTAIE'] == 'S') echo "selected='selected'"; ?>>ISENTO INSCR. ESTADUAL</option>                                                        
                                                    </select>
                                                </div>
                                                <!-- estadual -->
                                                <div class="col-sm-3 divIE" id="divIE">
                                                    <label>Nº Insc. Estadual</label>
                                                    <input class="form-control obrigatorio" title="Nº Insc. Estadual" name="vSEMPIE" id="vSEMPIE" type="text" value="<?= $vROBJETO['EMPIE'];?>" >
                                                </div>
                                                 
                                            </div>
                                            <div class="form-group row">
                                                
                                                <div class="col-md-3">   
                                                    <label>Optante pelo simples nacional?
                                                        <small class="text-danger font-13">*</small>
                                                    </label>
                                                    <select title="Estado Civil" id="vSEMPOPTANTESIMPLESNACIONAL" class="custom-select obrigatorio" name="vSEMPOPTANTESIMPLESNACIONAL" onchange="mostraCampo('E', this.value);">
                                                        <option value="S" <?php if ($vROBJETO['EMPOPTANTESIMPLESNACIONAL'] == 'S') echo "selected='selected'"; ?>>Sim</option>
                                                        <option value="N" <?php if ($vROBJETO['EMPOPTANTESIMPLESNACIONAL'] == 'N') echo "selected='selected'"; ?>>Não</option>
                                                    </select>
                                                </div>
                                            </div>                                              
                                    </div>

                                    <!-- Aba Dados Documento -->
                                    <div class="tab-pane p-3" id="end-1" role="tabpanel"> 
                                        <p class="text-muted mb-0">
                                            <div class="form-group row">
												<div class="col-sm-3">
													<label>CEP</label>
													<input class="form-control obrigatorio" title="CEP" name="vSEMPCEP" id="vSEMPCEP" type="text" value="<?= $vROBJETO['EMPCEP'];  ?>" >
												</div>
												<div class="col-sm-3">
													<label>Tipo Logradouro</label>
													<select title="Tipo Logradouro" id="vITLOCODIGO" class="form-control obrigatorio" name="vITLOCODIGO">
														<?php foreach (comboTipoLogradouro() as $tabelas): ?>
															<option value="<?php echo $tabelas['TLOCODIGO']; ?>" <?php if ($vROBJETO['TLOCODIGO'] == $tabelas['TLOCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['TLODESCRICAO']; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Logradouro</label>
													<input class="form-control obrigatorio" title="Logradouro" name="vSEMPLOGRADOURO" id="vSEMPLOGRADOURO" type="text" value="<?= $vROBJETO['EMPLOGRADOURO'];?> "maxlength="15" onKeyPress="return digitos(event, this);" onKeyUp="" >
												</div>
												<div class="col-sm-3">
													<label>Nº</label>
													<input class="form-control" name="vSEMPNROLOGRADOURO" id="vSEMPNROLOGRADOURO" type="text" value="<?= $vROBJETO['EMPNROLOGRADOURO'];?>" >
												</div>
											</div>
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>Bairro</label>
                                                        <input class="form-control obrigatorio" title="Bairro" name="vSEMPBAIRRO" id="vSEMPBAIRRO" type="text" value="<?= $vROBJETO['EMPBAIRRO'];  ?>" >
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>Complemento</label>
                                                        <input class="form-control" name="vSEMPCOMPLEMENTO" id="vSEMPCOMPLEMENTO" type="text" value="<?= $vROBJETO['EMPCOMPLEMENTO'];?>" >
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>Estado</label>
                                                        <select title="Estado" id="vIESTCODIGO" class="form-control obrigatorio" name="vIESTCODIGO" >
															<?php foreach (comboEstados() as $tabelas): ?>
																<option value="<?php echo $tabelas['ESTCODIGO']; ?>" <?php if ($vROBJETO['ESTCODIGO'] == $tabelas['ESTCODIGO']) echo "selected='selected'"; ?>><?php echo $tabelas['ESTDESCRICAO']; ?></option>
															<?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div id="div_cidade"></div>
                                                    </div>
                                                </div>

                                            </p>

                                        </div>

                                        <!-- Aba Dados Documentos end -->

                                        <!-- Aba Contatos -->
                                        <div class="tab-pane p-3" id="contacts-1" role="tabpanel">
                                            <p class="text-muted mb-0">
                                                <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label>Telefone Principal</label>
                                                        <input class="form-control obrigatorio" title="Telefone Principal" name="vSEMPFONE" id="vSEMPFONE" type="text" value="<?= $vROBJETO['EMPFONE'];?>" maxlength="15" >
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>Telefone 2</label>
                                                        <input class="form-control" name="vSEMPFONE2" id="vSEMPFONE2" type="text" value="<?= $vROBJETO['EMPFONE2'];?> "maxlength="15" >
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Contato Principal</label>
                                                        <input class="form-control obrigatorio" title="Contato Principal" name="vSEMPCONTATO" id="vSEMPCONTATO" type="text" value="<?= $vROBJETO['EMPCONTATO'];?>" >
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label>E-mail</label>
                                                        <input class="form-control obrigatorio" title="E-mail" name="vSEMPEMAIL" id="vSEMPEMAIL" type="email" value="<?= $vROBJETO['EMPEMAIL'];?>" >
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Site</label>
                                                        <input class="form-control" name="vSEMPSITE" id="vSEMPSITE" type="text" value="<?= $vROBJETO['EMPSITE'];  ?>" >
                                                    </div>
                                                </div>
                                            </p>
                                        </div>
                                        <!-- Aba Contatos end -->

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

        <?php include_once '../includes/scripts_footer.php' ?>
        <!-- Cad Empresa js -->
		<script src="js/cadEmpresaUsuaria.js"></script>
    </body>
</html>