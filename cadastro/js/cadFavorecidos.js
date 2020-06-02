$(document).ready(function () {
    $('#vICLICODIGO').select2({
      minimumInputLength: 2,
      multiple: false,
      ajax: {
        url: '../cadastro/combos/comboClientes2.php',
		dataType: 'json',
		delay: 250,
        data: function (params) {
          var query = {
            term: params['term'],
            action: 'your_action'
          }
          return query;
        },
        processResults: function (response) {
		 return {
			results: response
		 };
	   }
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
			vSObrigatorio: 'N',
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
  		document.getElementById("vSFAVCNPJ").classList.add("obrigatorio");
		document.getElementById("vSFAVCPF").classList.remove("obrigatorio");
		document.getElementById("vHFAVNOME").classList.remove("obrigatorio");
		document.getElementById("vSFAVRAZAOSOCIAL").classList.add("obrigatorio");
		document.getElementById("vSFAVNOMEFANTASIA").classList.add("obrigatorio");
	}else{
		$(".divFisica").show();	
		$(".divJuridica").hide();	
		document.getElementById("vSFAVCPF").classList.add("obrigatorio");
  		document.getElementById("vSFAVCNPJ").classList.remove("obrigatorio");
		document.getElementById("vHFAVNOME").classList.add("obrigatorio");
		document.getElementById("vSFAVRAZAOSOCIAL").classList.remove("obrigatorio");
		document.getElementById("vSFAVNOMEFANTASIA").classList.remove("obrigatorio");
	}	
}

function mostrarIE(pSValue){
	var element = document.getElementById("vSFAVIE");
	if (pSValue == 'N') {
		$(".divIE").show();		
	}else{
		$(".divIE").hide();	
	}	
}

$(function(){

	$("#vSFAVISENTAIE").on('change', function(){
		mostrarIE($(this).val());
	});

    $("#vSFAVCNPJ").mask("99.999.999/9999-99");
	$("#vSFAVCEP").mask("99999-999");
	$("#vSFAVFONE").mask("(99) 9999-9999");
	$("#vSFAVCELULAR").mask("(99) 9999-9999");		

});

function buscarCEP(vSCEP){
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
					
					$("#vSFAVLOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vSFAVBAIRRO").val(result.bairro).addClass('isActive');
					$("#vIESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
					exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_inpi', 'vICIDCODIGO');						
					$("#vSFAVNROLOGRADOURO").focus();						
									
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	}	
}

function buscarDadosReceita(){
	if ($("#vSFAVCNPJ").val() == ''){
		sweetAlert("Oops...", "Informe o CNPJ para buscar os dados na Receita Federal!", "error");		
	} else {
		$.getJSON("../includes/buscarCNPJFavorecido.php?vSCNPJ="+$('#vSFAVCNPJ').val(), function(json) {
			console.log(json);
			for (var i in json) {	
				vSMSG = json.vSMSG;
				vSBloqueia = json.vSBloqueia;							
				$("#vIFAVCODIGO").val(json.vIFAVCODIGO);				
				$("#vSFAVRAZAOSOCIAL").val(json.vSFAVRAZAOSOCIAL);
				$("#vSFAVNOMEFANTASIA").val(json.vSFAVNOMEFANTASIA);
				$("#vIFAVSITUACAORECEITA").val(json.vIFAVSITUACAORECEITA);							
				$("#vDFAVDATA_INICIO_ATIVIDADES").val(json.vDFAVDATA_INICIO_ATIVIDADES);							
										
				
				$("#vSFAVFONE").val(json.vSFAVFONE);
				$("#vSFAVEMAIL").val(json.vSFAVEMAIL);
				//endereco				
				$("#vSFAVLOGRADOURO").val(json.vSENDLOGRADOURO);
				$("#vSFAVNROLOGRADOURO").val(json.vSENDNROLOGRADOURO);
				$("#vSFAVCOMPLEMENTO").val(json.vSENDCOMPLEMENTO);
				$("#vSFAVBAIRRO").val(json.vSENDBAIRRO);
				$("#vSFAVCEP").val(json.vSENDCEP);
				$("#vIESTCODIGO").val(json.vIESTCODIGO);
				exibirCidades(json.vIESTCODIGO, json.vICIDCODIGO, 'div_cidade_inpi', 'vICIDCODIGO')
				swal({title : ":)", text : vSMSG, type : "info"});
				
			}
		});		
	}	
}	

function salvarModalClientes(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){	
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){		
		// cliente
		var vSUrl = 'transaction/transactionClientes.php?hdn_metodo_fill=fill_Clientes&vICLICODIGO='+$("#vICLICODIGO").val()+'&formatoRetorno=json';
		$.getJSON(vSUrl, function(json) {
			for (var i in json) {
				$("#vIFAVIMPCLICODIGO").val(json.CLICODIGO);
				$("#vSFAVRAZAOSOCIAL").val(json.CLINOME);
				$("#vSFAVNOMEFANTASIA").val(json.CLINOME);
				$("#vSFAVCNPJ").val(json.CLICNPJ);
				$("#vSFAVTIPOCLIENTE").val(json.CLITIPOCLIENTE);
				$("#vSFAVCPF").val(json.CLICPF);
				$("#vDFAVDATA_NASCIMENTO").val(json.CLIDATA_NASCIMENTO);
				$("#vDFAVDATA_INICIO_ATIVIDADES").val(json.CLIDATA_INICIO_ATIVIDADES);
				$("#vIFAVSITUACAORECEITA").val(json.CLISITUACAORECEITA);
				$("#vSFAVIM").val(json.CLIIM);
				$("#vSFAVISENTAIE").val(json.CLIISENTAIE);
				$("#vSFAVIE").val(json.CLIIE);
			}
		});
		// endereco
		var vSUrl = 'transaction/transactionEnderecos.php?hdn_metodo_fill=fill_Enderecos&vICLICODIGO='+$("#vICLICODIGO").val()+'&vITABCODIGO=427&formatoRetorno=json';
		$.getJSON(vSUrl, function(json) {
			for (var i in json) {
				$("#vIPAICODIGO").val(json.PAICODIGO);
				$("#vSFAVCEP").val(json.ENDCEP);
				$("#vSFAVBAIRRO").val(json.ENDBAIRRO);
				$("#vSFAVLOGRADOURO").val(json.ENDLOGRADOURO);
				$("#vSFAVNROLOGRADOURO").val(json.ENDNROLOGRADOURO);
				$("#vSFAVCOMPLEMENTO").val(json.ENDCOMPLEMENTO);
				$("#vIESTCODIGO").val(json.ESTCODIGO);
				exibirCidades(json.ESTCODIGO, json.CIDCODIGO, 'div_cidade_inpi', 'vICIDCODIGO');					
			}
		});
		// contatos
		var vSUrl = 'transaction/transactionContatos.php?hdn_metodo_fill=fill_Contatos&vICLICODIGO='+$("#vICLICODIGO").val()+'&vICONTIPO=26933&formatoRetorno=json';
		$.getJSON(vSUrl, function(json) {
			for (var i in json) {
				$("#vSFAVCONTATO").val(json.CONNOME);
				$("#vSFAVFONE").val(json.CONFONE);
				$("#vSFAVCELULAR").val(json.CONCELULAR);
				$("#vSFAVEMAIL").val(json.CONEMAIL);							
			}
		});
		$( "#modal"+pMetodo ).modal("hide");
	} else {
		swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
	}
}

function salvarModalColaboradores(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		
		var vSUrl = '../rh/transaction/transactionUsuario.php?hdn_metodo_fill=fill_Usuario&vIUSUCODIGO='+$("#vIUSUCODIGO").val()+'&formatoRetorno=json';
		$.getJSON(vSUrl, function(json) {
			console.log(json);
			for (var i in json) {
				$("#vHFAVNOME").val(json.USUNOME);
				$("#vSFAVCNPJ").val(json.USUCNPJ);
				if (json.USUCPF != '')	{	
					mostrarJxF('F');
					$("#vSFAVTIPOCLIENTE").val('F');					
				}else {
					mostrarJxF('J');
					$("#vSFAVTIPOCLIENTE").val('J');
				}	
				$("#vSFAVCPF").val(json.USUCPF);
				$("#vDFAVDATA_NASCIMENTO").val(json.USUDATA_NASCIMENTO);
				$("#vDFAVDATA_INICIO_ATIVIDADES").val(json.CLIDATA_INICIO_ATIVIDADES);
				$("#vIFAVSITUACAORECEITA").val(20);				
				$("#vSFAVCONTATO").val(json.USUNOME);
				$("#vSFAVFONE").val(json.USUFONE);
				$("#vSFAVCELULAR").val(json.USUCELULAR);
				$("#vSFAVEMAIL").val(json.USUEMAIL);								
			}
		});

		$( "#modal"+pMetodo ).modal("hide");
	} else {
		swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
	}
}