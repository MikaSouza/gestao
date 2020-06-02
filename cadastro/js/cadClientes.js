$(function () {

    var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vICLICODIGO").val());
		form.append('vSGEDDIRETORIO', '../ged');
		form.append('vIMENCODIGO', 6);
		form.append('method', 'incluirClientesxGED');
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');
		form.append('vIGEDTIPO', $("#vHGED").val());
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vICLICODIGO").val();
			$.ajax({
				url: "transaction/transactionClientesxGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
					gerarGridJSON('transactionClientesxGED.php', 'div_ged', 'ClientesxGED', hdn_pai_codigo);
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });
});

function exibirCidades(codestado, codcidade, div_retorno, vSNome) {

    jQuery.ajax({
        async: true,
        type: "GET",
        url: "combos/comboCidades.php",
        data: {
            codestado: codestado,
			codcidade: codcidade,
			vSNome: vSNome,
            method: 'fillcidade'
        },
        success: function(resposta){
            document.getElementById(div_retorno).innerHTML = resposta;
            return true;
        },
        error: function(msg) {
            alert("Ocorreu um erro na busca da cidade!"+msg);
            return false;
        }
    });
}

function mostrarJxF(pSValue){
	var element = document.getElementById("J");
	if (pSValue == 'J') {
		$(".divJuridica").show();	
		$(".divFisica").hide();	
  		document.getElementById("vSCLICNPJ").classList.add("obrigatorio");
		document.getElementById("vSCLICPF").classList.remove("obrigatorio");
	}else{
		$(".divFisica").show();	
		$(".divJuridica").hide();	
		document.getElementById("vSCLICPF").classList.add("obrigatorio");
  		document.getElementById("vSCLICNPJ").classList.remove("obrigatorio");
	}	
}

function mostrarIE(pSValue){
	var element = document.getElementById("vSCLIIE");
	if (pSValue == 'N') {
		$(".divIE").show();		
  		element.classList.add("obrigatorio");
	}else{
		$(".divIE").hide();	
  		element.classList.remove("obrigatorio");
	}	
}

$(function(){

	$("#vSCLIISENTAIE").on('change', function(){
		mostrarIE($(this).val());
	});

    $("#vSCLICNPJ").mask("99.999.999/9999-99");
	$("#vHINPIENDCEP").mask("99999-999");
	$("#vHCORENDCEP").mask("99999-999");
	$("#vHCOBENDCEP").mask("99999-999");
	$("#vSCLIFONE").mask("(99) 9999-9999");
	$("#vSCLICELULAR").mask("(99) 9999-9999");
	
	mostrarJxF($("#vSCLITIPOCLIENTE").val());

});

function buscarCEP(vSCEP, vSORIGEM){
	if (vSCEP != ''){
		$.ajax({
			url: '../includes/buscarCEP.php',
			type: 'GET',
			dataType: 'json',
			data: {
				cep: vSCEP
			},
			success: function(result){
				console.log(result);
				if(result.logradouro != ''){
					if (vSORIGEM == 'INPI') {	
					
						$("#vHINPIENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHINPIENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHINPIESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_inpi', 'vHINPICIDCODIGO');						
						$("#vHINPIENDNROLOGRADOURO").focus();						
				
					} else if (vSORIGEM == 'COR') {	

						$("#vHCORENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHCORENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHCORESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_cor', 'vHCORCIDCODIGO');					
						$("#vHCORENDNROLOGRADOURO").focus();
						
					} else if (vSORIGEM == 'COB') {	
					
						$("#vHCOBENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHCOBENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHCOBESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_cob', 'vHCOBCIDCODIGO');					
						$("#vHCOBENDNROLOGRADOURO").focus();
						
					}
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	}	
}

function buscarDadosReceita(){
	if ($("#vSCLICNPJ").val() == ''){
		sweetAlert("Oops...", "Informe o CNPJ para buscar os dados na Receita Federal!", "error");		
	} else {
		$.getJSON("../includes/buscarCNPJ.php?vSCNPJ="+$('#vSCLICNPJ').val(), function(json) {
			console.log(json);
			for (var i in json) {	
				vSMSG = json.vSMSG;
				vSBloqueia = json.vSBloqueia;							
				$("#vICLICODIGO").val(json.vICLICODIGO);				
				$("#vSCLINOME").val(json.vSCLINOME);
				$("#vICLISITUACAORECEITA").val(json.vICLISITUACAORECEITA);							
				$("#vDCLIDATA_INICIO_ATIVIDADES").val(json.vDCLIDATA_INICIO_ATIVIDADES);							
							
				$("#vICLIREGIMETRIBUTARIO").val(json.vICLIREGIMETRIBUTARIO);
				$("#vICLINATUREZAJURIDICA").val(json.vICLINATUREZAJURIDICA);			
				$("#vICNACODIGO").val(json.vICNACODIGO);		
				
				$("#vHINPICONFONE").val(json.vSCLIFONE);
				$("#vHINPICONEMAIL").val(json.vSCLIEMAIL);
				//endereco				
				$("#vHINPIENDLOGRADOURO").val(json.vSENDLOGRADOURO);
				$("#vHINPIENDNROLOGRADOURO").val(json.vSENDNROLOGRADOURO);
				$("#vHINPIENDCOMPLEMENTO").val(json.vSENDCOMPLEMENTO);
				$("#vHINPIENDBAIRRO").val(json.vSENDBAIRRO);
				$("#vHINPIENDCEP").val(json.vSENDCEP);
				$("#vHINPIESTCODIGO").val(json.vIESTCODIGO);
				exibirCidades(json.vIESTCODIGO, json.vICIDCODIGO, 'div_cidade_inpi', 'vHINPICIDCODIGO')
				swal({title : ":)", text : vSMSG, type : "info"});
				
			}
		});		
	}	
}	

function fillINPI(vSORIGEM, vSDESTINO)
{
	if ((vSORIGEM == 'INPI') && (vSDESTINO == 'COR')) {
		$("#vHCORPAICODIGO").val($("#vHINPIPAICODIGO").val());
		$("#vHCORENDCEP").val($("#vHINPIENDCEP").val());
		$("#vHCORENDBAIRRO").val($("#vHINPIENDBAIRRO").val());
		$("#vHCORENDLOGRADOURO").val($("#vHINPIENDLOGRADOURO").val());
		$("#vHCORENDNROLOGRADOURO").val($("#vHINPIENDNROLOGRADOURO").val());
		$("#vHCORENDCOMPLEMENTO").val($("#vHINPIENDCOMPLEMENTO").val());
		$("#vHCORESTCODIGO").val($("#vHINPIESTCODIGO").val());
		exibirCidades($("#vHINPIESTCODIGO").val(), $("#vHINPICIDCODIGO").val(), 'div_cidade_cor', 'vHCORCIDCODIGO');		
	} else if ((vSORIGEM == 'COB') && (vSDESTINO == 'COR')) {	
		$("#vHCORPAICODIGO").val($("#vHCOBPAICODIGO").val());
		$("#vHCORENDCEP").val($("#vHCOBENDCEP").val());
		$("#vHCORENDBAIRRO").val($("#vHCOBENDBAIRRO").val());
		$("#vHCORENDLOGRADOURO").val($("#vHCOBENDLOGRADOURO").val());
		$("#vHCORENDNROLOGRADOURO").val($("#vHCOBENDNROLOGRADOURO").val());
		$("#vHCORENDCOMPLEMENTO").val($("#vHCOBENDCOMPLEMENTO").val());
		$("#vHCORESTCODIGO").val($("#vHCOBESTCODIGO").val());		
		exibirCidades($("#vHCOBESTCODIGO").val(), $("#vHCOBCIDCODIGO").val(), 'div_cidade_cor', 'vHCORCIDCODIGO');		
	} else if ((vSORIGEM == 'INPI') && (vSDESTINO == 'COB')) {	
		$("#vHCOBPAICODIGO").val($("#vHINPIPAICODIGO").val());
		$("#vHCOBENDCEP").val($("#vHINPIENDCEP").val());
		$("#vHCOBENDBAIRRO").val($("#vHINPIENDBAIRRO").val());
		$("#vHCOBENDLOGRADOURO").val($("#vHINPIENDLOGRADOURO").val());
		$("#vHCOBENDNROLOGRADOURO").val($("#vHINPIENDNROLOGRADOURO").val());
		$("#vHCOBENDCOMPLEMENTO").val($("#vHINPIENDCOMPLEMENTO").val());
		$("#vHCOBESTCODIGO").val($("#vHINPIESTCODIGO").val());		
		exibirCidades($("#vHINPIESTCODIGO").val(), $("#vHINPICIDCODIGO").val(), 'div_cidade_cob', 'vHCOBCIDCODIGO');		
	} else if ((vSORIGEM == 'COR') && (vSDESTINO == 'COB')) {	
		$("#vHCOBPAICODIGO").val($("#vHCORPAICODIGO").val());
		$("#vHCOBENDCEP").val($("#vHCORENDCEP").val());
		$("#vHCOBENDBAIRRO").val($("#vHCORENDBAIRRO").val());
		$("#vHCOBENDLOGRADOURO").val($("#vHCORENDLOGRADOURO").val());
		$("#vHCOBENDNROLOGRADOURO").val($("#vHCORENDNROLOGRADOURO").val());
		$("#vHCOBENDCOMPLEMENTO").val($("#vHCORENDCOMPLEMENTO").val());
		$("#vHCOBESTCODIGO").val($("#vHCORESTCODIGO").val());		
		exibirCidades($("#vHCORESTCODIGO").val(), $("#vHCORCIDCODIGO").val(), 'div_cidade_cor', 'vHCOBCIDCODIGO');		
	} 
}	

function fillContatoINPI(vSORIGEM, vSDESTINO)
{
	if ((vSORIGEM == 'INPI') && (vSDESTINO == 'COR')) {
		$("#vHCORCONNOME").val($("#vHINPICONNOME").val());
		$("#vHCORCONFONE").val($("#vHINPICONFONE").val());
		$("#vHCORCONCELULAR").val($("#vHINPICONCELULAR").val());
		$("#vHCORCONEMAIL").val($("#vHINPICONEMAIL").val());			
	} else if ((vSORIGEM == 'COB') && (vSDESTINO == 'COR')) {	
		$("#vHCORCONNOME").val($("#vHCOBCONNOME").val());
		$("#vHCORCONFONE").val($("#vHCOBCONFONE").val());
		$("#vHCORCONCELULAR").val($("#vHCOBCONCELULAR").val());
		$("#vHCORCONEMAIL").val($("#vHCOBCONEMAIL").val());		
	} else if ((vSORIGEM == 'INPI') && (vSDESTINO == 'COB')) {	
		$("#vHCOBCONNOME").val($("#vHINPICONNOME").val());
		$("#vHCOBCONFONE").val($("#vHINPICONFONE").val());
		$("#vHCOBCONCELULAR").val($("#vHINPICONCELULAR").val());
		$("#vHCOBCONEMAIL").val($("#vHINPICONEMAIL").val());				
	} else if ((vSORIGEM == 'COR') && (vSDESTINO == 'COB')) {	
		$("#vHCOBCONNOME").val($("#vHCORCONNOME").val());
		$("#vHCOBCONFONE").val($("#vHCORCONFONE").val());
		$("#vHCOBCONCELULAR").val($("#vHCORCONCELULAR").val());
		$("#vHCOBCONEMAIL").val($("#vHCORCONEMAIL").val());			
	} 
}	

function fillClientesxHistorico(pIFXSCODIGO,titulo){
	var vSUrl = 'transaction/transactionClientesxHistorico.php?hdn_metodo_fill=fill_ClientesxHistorico&FXSCODIGO='+pIFXSCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
            $("#hdn_pai_"+titulo).val(json.FXSIDFILIAIS);
            $("#vDCHGDATA").val(json.CHGDATA);
			$("#vICHGTIPO").val(json.CHGTIPO);
			$("#vICHGPOSICAO").val(json.CHGPOSICAO);
			$("#vSCHGHISTORICO").val(json.CHGHISTORICO);
        }
	});

}
function salvarModalClientesxHistorico(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluir"+pMetodo,
            hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
            hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			vDCHGDATA: $("#vDCHGDATA").val(),
			vICHGTIPO: $("#vICHGTIPO").val(),
			vICHGPOSICAO: $("#vICHGPOSICAO").val(),
			vSCHGHISTORICO: $("#vSCHGHISTORICO").val()
		};
		$.ajax({
			async: true,
			type: "POST",
			url: "transaction/"+pSTransaction,
			data: data,
			success: function(msg){
				swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
				gerarGridJSON(pSTransaction, pSDivReturn, pMetodo, pIOID);
				$( "#modal"+pMetodo ).modal("hide");
				return true;
			},
			error: function(msg) {
				limparCamposDialog(div_nome);
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
				alert(msg);
				$( "#modal"+pMetodo ).modal("hide");
				return false;
			}
		});
	} else {
		swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
	}
}

function salvarModalClientesxRelacionados(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluir"+pMetodo,
            hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
            hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			vHCLICODIGOREL: $("#vHCLICODIGOREL").val()
		};
		$.ajax({
			async: true,
			type: "POST",
			url: "transaction/"+pSTransaction,
			data: data,
			success: function(msg){
				swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
				gerarGridJSON(pSTransaction, pSDivReturn, pMetodo, pIOID);
				$( "#modal"+pMetodo ).modal("hide");
				return true;
			},
			error: function(msg) {
				limparCamposDialog(div_nome);
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
				alert(msg);
				$( "#modal"+pMetodo ).modal("hide");
				return false;
			}
		});
	} else {
		swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
	}
}
