$(function () {
	$('.select2-multiple').select2();
    var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vICLICODIGO").val());
		form.append('vSGEDDIRETORIO', 'clientes');
		form.append('vIMENCODIGO', 6);
		form.append('method', 'incluirGED');
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');
		form.append('vIGEDTIPO', $("#vHGED").val());
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vICLICODIGO").val();
			$.ajax({
				url: "../utilitarios/transaction/transactionGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});				
					gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', hdn_pai_codigo, '6');
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });
	
	$('#formEnderecos').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formEnderecos').serialize();				
		var data = {
					method: "incluirEndereco",
					hdn_oid_endereco: $("#hdn_endereco").val(),
					vICLICODIGO: $("#vICLICODIGO").val(),
					vITABCODIGO: $("#vITABCODIGO").val(),
					vITLOCODIGO: $("#vITLOCODIGO").val(),
					vSENDLOGRADOURO: $("#vSENDLOGRADOURO").val(),
					vSENDNROLOGRADOURO: $("#vHENDNROLOGRADOURO").val(),
					vSENDCOMPLEMENTO: $("#vHENDCOMPLEMENTO").val(),
					vSENDBAIRRO: $("#vHENDBAIRRO").val(),
					vSENDCEP: $("#vHENDCEP").val(),
					vIPAICODIGO: $("#vHPAICODIGO").val(),
					vIESTCODIGO: $("#vHESTCODIGO").val(),
					vICIDCODIGO: $("#vHCIDCODIGO").val()
				};
		$.ajax({
			url: 'transaction/transactionClientes.php',
			type: 'POST',
			async: true,
			dataType: 'json',
			data: data,
			success: function(response) {
				swal(':)', 'Dados atulizados com sucesso!', 'success');
				
				$( "#modalEnderecos").modal("hide");	
				gerarGridJSONContasPagar();
			},
			error: function() {
				swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			}
		});
		
	});
	
	$('#formContatos').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formContatos').serialize();				
		var data = {
					method           : "incluirContato",
					vICONCODIGO      : $("#vHCONCODIGO").val(),
					vICLICODIGO      : $("#vICLICODIGO").val(),
					vSCONNOME        : $("#vSCONNOME").val(),
					vICONESTADOCIVIL : $("#vICONESTADOCIVIL").val(),
					vSCONEMAIL       : $("#vSCONEMAIL").val(),
					vSCONCPF         : $("#vSCONCPF").val(),
					vSCONRG          : $("#vSCONRG").val(),
					vSCONFONE        : $("#vSCONFONE").val(),
					vSCONRAMAL       : $("#vSCONRAMAL").val(),
					vSCONCELULAR     : $("#vSCONCELULAR").val(),
					vSCONCARGO       : $("#vSCONCARGO").val(),
					vSCONSETOR       : $("#vSCONSETOR").val(),
					vSCONPRINCIPAL   : $("#vSCONPRINCIPAL").val()
				};
		$.ajax({
			url: 'transaction/transactionContatos.php',
			type: 'POST',
			async: true,
			dataType: 'json',
			data: data,
			success: function(response) {
				swal(':)', 'Dados atulizados com sucesso!', 'success');
				
				$( "#modalContatos").modal("hide");	
				gerarGridJSONContasPagar();
			},
			error: function() {
				swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			}
		});
		
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
		document.getElementById("vHCLINOME").classList.remove("obrigatorio");
		document.getElementById("vSCLIRAZAOSOCIAL").classList.add("obrigatorio");
		document.getElementById("vSCLINOMEFANTASIA").classList.add("obrigatorio");		
		document.getElementById("vICLISITUACAORECEITA").classList.add("obrigatorio");				
	}else{
		$(".divFisica").show();	
		$(".divJuridica").hide();	
		document.getElementById("vSCLICPF").classList.add("obrigatorio");
  		document.getElementById("vSCLICNPJ").classList.remove("obrigatorio");
		document.getElementById("vHCLINOME").classList.add("obrigatorio");
		document.getElementById("vSCLIRAZAOSOCIAL").classList.remove("obrigatorio");
		document.getElementById("vSCLINOMEFANTASIA").classList.remove("obrigatorio");	
		document.getElementById("vICLISITUACAORECEITA").classList.remove("obrigatorio");	
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
	$("#vHENDCEP").mask("99999-999");
	$("#vHMENDCEP").mask("99999-999");
	$("#vHCONFONE").mask("(99) 9999-9999");	
	
	mostrarJxF($("#vSCLITIPOCLIENTE").val());

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

					$("#vHENDLOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vHENDBAIRRO").val(result.bairro).addClass('isActive');
					$("#vHESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
					exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade', 'vHCIDCODIGO');						
					$("#vHENDNROLOGRADOURO").focus();															
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
				$("#vSCLIRAZAOSOCIAL").val(json.vSCLIRAZAOSOCIAL);
				$("#vSCLINOMEFANTASIA").val(json.vSCLINOMEFANTASIA);
				$("#vICLISITUACAORECEITA").val(json.vICLISITUACAORECEITA);							
				$("#vDCLIDATA_INICIO_ATIVIDADES").val(json.vDCLIDATA_INICIO_ATIVIDADES);							
							
				$("#vICLIREGIMETRIBUTARIO").val(json.vICLIREGIMETRIBUTARIO);
				$("#vICLINATUREZAJURIDICA").val(json.vICLINATUREZAJURIDICA);			
				$("#vICNACODIGO").val(json.vICNACODIGO);		
				
				$("#vHCONFONE").val(json.vSCLIFONE);
				$("#vHCONEMAIL").val(json.vSCLIEMAIL);
				//endereco				
				$("#vHENDLOGRADOURO").val(json.vSENDLOGRADOURO);
				$("#vHENDNROLOGRADOURO").val(json.vSENDNROLOGRADOURO);
				$("#vHENDCOMPLEMENTO").val(json.vSENDCOMPLEMENTO);
				$("#vHENDBAIRRO").val(json.vSENDBAIRRO);
				$("#vHENDCEP").val(json.vSENDCEP);
				$("#vHESTCODIGO").val(json.vIESTCODIGO);
				exibirCidades(json.vIESTCODIGO, json.vICIDCODIGO, 'div_cidade', 'vHCIDCODIGO')
				swal({title : ":)", text : vSMSG, type : "info"});
				
			}
		});		
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
function salvarModalClientesxContatos(div_nome, pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluir"+pMetodo,
            hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
            hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			vHCONNOME: $("#vHMCONNOME").val(),
			vHCONEMAIL: $("#vHMCONEMAIL").val(),
			vHCONCELULAR: $("#vHMCONFONE").val(),
			vHCONFONE: $("#vHMCONCELULAR").val(),
			vHCONCARGO: $("#vHMCONCARGO").val(),
			vHCONSETOR: $("#vHMCONSETOR").val(),
			vHCONSENHA: $("#vHMCONSENHA").val()
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

function fillContatos(pICONCODIGO){
	var vSUrl = '../transaction/transactionContatos.php?hdn_metodo_fill=fill_Contatos&vICONCODIGO='+pICONCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#vHMCONNOME").val(json.CONNOME);
			$("#vHMCONEMAIL").val(json.CONEMAIL);
			$("#vHMCONCELULAR").val(json.CONCELULAR);
			$("#vHMCONFONE").val(json.CONFONE);
			$("#vSCONCPF").val(json.CONCPF);
			$("#vSCONCARGO").val(json.CONCARGO);
			$("#vSCONRAMAL").val(json.CONRAMAL);
			$("#vICONCODIGO").val(json.CONCODIGO);
			$("#vICLICODIGO").val(json.CLICODIGO);
			$("#vICONESTADOCIVIL").val(json.CONESTADOCIVIL);
			$("#vSCONRG").val(json.CONRG);
			$("#vSCONSETOR").val(json.CONSETOR);
			$("#vSCONPRINCIPAL").val(json.CONPRINCIPAL);
		}
	});
}

function fillEndereco(pIENDCODIGO){
	var vSUrl = '../transaction/transactionEnderecos.php?hdn_metodo_fill=fill_Enderecos&vIENDCODIGO='+pIENDCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#vITABCODIGO").val(json.TABCODIGO);
			$("#vHMENDLOGRADOURO").val(json.ENDLOGRADOURO);
			$("#vHMENDNROLOGRADOURO").val(json.ENDNROLOGRADOURO);
			$("#vHMENDCOMPLEMENTO").val(json.ENDCOMPLEMENTO);
			$("#vHMENDBAIRRO").val(json.ENDBAIRRO);
			$("#vHMENDCEP").val(json.ENDCEP);
			$("#vIPAICODIGO").val(json.PAICODIGO);
			exibirEstados(json.PAICODIGO, json.ESTCODIGO, json.CIDCODIGO);
			$("#vSENDLONGITUDE").val(json.ENDLONGITUDE);
			$("#vSENDLATITUDE").val(json.ENDLATITUDE);
			$("#hdn_endereco").val(json.ENDCODIGO);
		}
	});
}