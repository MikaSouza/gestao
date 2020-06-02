$(function(){

	$("#vSCLIISENTAIE").on('change', function(){
		mostrarIE($(this).val());
	});

    $("#vHCLICNPJ").mask("99.999.999/9999-99");
	$("#vHINPIENDCEP").mask("99999-999");
	$("#vSCXPFONE").mask("(99) 9999-9999");
	$("#vSCLICELULAR").mask("(99) 9999-9999");
	$("#vHCLICPF").mask("999.999.999-99");
	mostrarJxF($("#vHCLITIPOCLIENTE").val());

});


function exibirCidades(codestado, codcidade, div_retorno, vSNome) {

    jQuery.ajax({
        async: true,
        type: "GET",
        url: "../cadastro/combos/comboCidades.php",
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
					$("#vHINPIENDLOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vHINPIENDBAIRRO").val(result.bairro).addClass('isActive');
					$("#vHINPIESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
					exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_inpi', 'vHINPICIDCODIGO');						
					$("#vHINPIENDNROLOGRADOURO").focus();										
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	}	
}

function mostrarJxF(pSValue){
	var element = document.getElementById("J");
	if (pSValue == 'J') {
		$(".divJuridica").show();	
		$(".divFisica").hide();	
	}else{
		$(".divFisica").show();	
		$(".divJuridica").hide();	
	}	
}


function buscarDadosReceita(){
	if ($("#vHCLICNPJ").val() == ''){
		sweetAlert("Oops...", "Informe o CNPJ para buscar os dados na Receita Federal!", "error");		
	} else {
		$.getJSON("../includes/buscarCNPJ.php?vSCNPJ="+$('#vHCLICNPJ').val(), function(json) {
			console.log(json);
			for (var i in json) {	
				vSMSG = json.vSMSG;
				vSBloqueia = json.vSBloqueia;							
				$("#vICLICODIGO").val(json.vICLICODIGO);				
				$("#vSCLINOME").val(json.vHCLINOME);
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

function salvarModalProspeccaoxAgenda(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluir"+pMetodo,
            hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
            hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			vDCHGDATA: $("#vDCHGDATA").val(),
			vSCHGHORA: $("#vSCHGHORA").val(),
			vICHGTIPO: $("#vICHGTIPO").val(),
			vICHGPOSICAO: $("#vICHGPOSICAO").val(),
			vICLICODIGO: $("#vICLICODIGO").val(),
			vICXPREPRESENTANTE: $("#vICXPREPRESENTANTE").val(),
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

function fillProspeccaoxAgenda(pIAGECODIGO,titulo){
	var vSUrl = 'transaction/transactionProspeccaoxAgenda.php?hdn_metodo_fill=fill_ProspeccaoxAgenda&AGECODIGO='+pIAGECODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
            $("#hdn_pai_"+titulo).val(json.AGEVINCULO);
            $("#vDCHGDATA").val(json.AGEDATAINICIO);
			$("#vSCHGHORA").val(json.AGEHORAINICIO);
			$("#vICHGTIPO").val(json.AGETIPO);
			$("#vSCHGHISTORICO").val(json.AGEDESCRICAO);
        }
	});

}