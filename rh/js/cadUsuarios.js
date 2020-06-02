$(function(){	

	var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vIUSUCODIGO").val());
		form.append('vSGEDDIRETORIO', 'usuarios');
		form.append('vIMENCODIGO', 1966);
		form.append('method', 'incluirGED'); 
		form.append('vIGEDTIPO', $("#vHGEDTIPO").val());
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');		
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vIUSUCODIGO").val();
			$.ajax({
				url: "../utilitarios/transaction/transactionGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});					
					gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', hdn_pai_codigo, '1966');
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

$(function(){
	$("#vHUXECEP").mask("99999-999");
	$("#vHUXECEP").on('focusout', function(){
		$.ajax({
			url: '../includes/buscarCEP.php',
			type: 'GET',
			dataType: 'json',
			data: {
				cep: $(this).val()
			},
			success: function(result){

				if(result.logradouro != ''){
					$("#vHUXELOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vHUXEBAIRRO").val(result.bairro).addClass('isActive');
					$("#vHESTCODIGO").val(result.estadoCodigo).addClass('isActive');
					exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade', 'vHCIDCODIGO');
					$("#vHUXENROLOGRADOURO").focus();
				}else{
					sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	});

});

function fillUsuariosxValesTransporte(vIUXVCODIGO, titulo){
	var vSUrl = 'transaction/transactionUsuariosxValesTransporte.php?hdn_metodo_fill=fill_UsuariosxValesTransporte&vIUXVCODIGO='+vIUXVCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vIVXTCODIGO").val(json.VXTCODIGO);
			$("#vMUXVVALOR").val(json.UXVVALOR);
			$("#vIUXVQTDE").val(json.UXVQTDE); 
		}
	});
}

function fillUsuariosxBeneficios(vIUXBCODIGO, titulo){
	var vSUrl = 'transaction/transactionUsuariosxBeneficios.php?hdn_metodo_fill=fill_UsuariosxBeneficios&vIUXBCODIGO='+vIUXBCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vITABCODIGO").val(json.TABCODIGO);
			$("#vIUXBCODIGO").val(json.UXBCODIGO);
			$("#vIUXBQTDE").val(json.UXBQTDE);
			$("#vMUXBVALOR").val(json.UXBVALOR);
			$("#vMUXBPORCENTO").val(json.UXBPORCENTO);
		}
	});
}

function fillUsuariosxContasBancarias(vIUXBCODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxContasBancarias.php?hdn_metodo_fill=fill_UsuariosxContasBancarias&vIUXBCODIGO='+vIUXBCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vSUXBAGENCIA").val(json.UXBAGENCIA);
			$("#vSUXBCONTA").val(json.UXBCONTA);
			$("#vIBACCODIGO").val(json.BACCODIGO);
		}
	});
}

function fillUsuariosxDocumentoPendente(pIUXBCODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxDocumentoPendente.php?hdn_metodo_fill=fill_UsuariosxDocumentoPendente&UXBCODIGO='+pIUXBCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vSUXBAGENCIA").val(json.UXBAGENCIA);
			$("#vSUXBCONTA").val(json.UXBCONTA);
			$("#vIBACCODIGO").val(json.BACCODIGO);
		}
	});
}

function fillUsuariosxEscolaridade(vIUXECODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxEscolaridade.php?hdn_metodo_fill=fill_UsuariosxEscolaridade&vIUXECODIGO='+vIUXECODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vITABCODIGOESCOLARIDADE").val(json.TABCODIGOESCOLARIDADE);
			$("#vSUXESEMESTRE").val(json.UXESEMESTRE);
			$("#vSUXEINSTITUICAO").val(json.UXEINSTITUICAO);
			$("#vSUXECURSO").val(json.UXECURSO);
			$("#vDUXEDATAINICIO").val(json.UXEDATAINICIO);
			$("#vDUXEDATAFIM").val(json.UXEDATAFIM);
		}
	});
}

function fillUsuariosxFeedback(vIUXFCODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxFeedback.php?hdn_metodo_fill=fill_UsuariosxFeedback&vIUXFCODIGO='+vIUXFCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vITABCODIGOFEEDBACK").val(json.TABCODIGOFEEDBACK);
			$("#vITABCODIGOPERFIL").val(json.TABCODIGOPERFIL);
			$("#vSUXFOBSERVACAO").val(json.UXFOBSERVACAO);
		}
	});
}

function fillUsuariosxFerias(vIUXFCODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxFerias.php?hdn_metodo_fill=fill_UsuariosxFerias&vIUXFCODIGO='+vIUXFCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vDUXFDATAAQUISITIVO1").val(json.UXFDATAAQUISITIVO1);
			$("#vDUXFDATAAQUISITIVO2").val(json.UXFDATAAQUISITIVO2);
			$("#vDUXFDATALIMITEGOZO").val(json.UXFDATALIMITEGOZO);  
			$("#vDUXFDATAGOZOINICIAL").val(json.UXFDATAGOZOINICIAL);
			$("#vDUXFDATAGOZOFINAL").val(json.UXFDATAGOZOFINAL);
		}
	});
}

function fillUsuariosxRemuneracao(vIUXRCODIGO,titulo){
	var vSUrl = 'transaction/transactionUsuariosxRemuneracao.php?hdn_metodo_fill=fill_UsuariosxRemuneracao&vIUXRCODIGO='+vIUXRCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.USUCODIGO);
			$("#vDUXRDATAALTERACAOSALARIAL").val(json.UXRDATAALTERACAOSALARIAL);
			$("#vMUXRSALARIOATUAL").val(json.UXRSALARIOATUAL);
			$("#vSUXRMOTIVOALTERACAOSALARIAL").val(json.UXRMOTIVOALTERACAOSALARIAL);
		}
	});
}

function salvarModalUsuariosxContasBancarias(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluir"+pMetodo,
			hdn_pai_codigo: $("#hdn_pai_"+pMetodo).val(),
			hdn_filho_codigo: $("#hdn_filho_"+pMetodo).val(),
			vIBACCODIGO:$("#vIBACCODIGO").val(),
			vIUXBTIPO:$("#vIUXBTIPO").val(),
			vSUXBAGENCIA:$("#vSUXBAGENCIA").val(),
			vSUXBCONTA:$("#vSUXBCONTA").val()
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

function salvarModalUsuariosxValesTransporte(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxValesTransporte",
			hdn_filho_codigo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codigo: $("#hdn_pai_"+pMetodo).val(),
			vIVXTCODIGO: $("#vIVXTCODIGO").val(),
			vMUXVVALOR: $("#vMUXVVALOR").val(),
			vIUXVQTDE: $("#vIUXVQTDE").val()
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

function salvarModalUsuariosxBeneficios(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxBeneficios",
			hdn_filho_codigo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codigo: $("#hdn_pai_"+pMetodo).val(),
			vITABCODIGO: $("#vITABCODIGO").val(),
			vIUXBQTDE: $("#vIUXBQTDE").val(),
			vMUXBVALOR: $("#vMUXBVALOR").val(),
			vMUXBPORCENTO: $("#vMUXBPORCENTO").val()
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

function salvarModalUsuariosxDocumentoPendente(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxDocumentoPendente",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
			vITABCODIGO: $("#vITABCODIGO").val(),
			vIUXBQTDE: $("#vIUXBQTDE").val(),
			vMUXBVALOR: $("#vMUXBVALOR").val(),
			vMUXBPORCENTO: $("#vMUXBPORCENTO").val()
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

function salvarModalUsuariosxEscolaridade(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxEscolaridade",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
			vITABCODIGOESCOLARIDADE: $("#vITABCODIGOESCOLARIDADE").val(),
			vSUXESEMESTRE: $("#vSUXESEMESTRE").val(),
			vSUXEINSTITUICAO: $("#vSUXEINSTITUICAO").val(),
			vSUXECURSO: $("#vSUXECURSO").val(),
			vDUXEDATAINICIO: $("#vDUXEDATAINICIO").val(),
			vDUXEDATAFIM: $("#vDUXEDATAFIM").val()
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

function salvarModalUsuariosxFeedback(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxFeedback",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
			vITABCODIGOFEEDBACK: $("#vITABCODIGOFEEDBACK").val(),
			vITABCODIGOPERFIL: $("#vITABCODIGOPERFIL").val(),
			vSUXFOBSERVACAO: $("#vSUXFOBSERVACAO").val()
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

function salvarModalUsuariosxFerias(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxFerias",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),
			vDUXFDATAAQUISITIVO1: $("#vDUXFDATAAQUISITIVO1").val(),
			vDUXFDATAAQUISITIVO2: $("#vDUXFDATAAQUISITIVO2").val(), 
			vDUXFDATALIMITEGOZO: $("#vDUXFDATALIMITEGOZO").val(),
			vDUXFDATAGOZOINICIAL: $("#vDUXFDATAGOZOINICIAL").val(),
			vDUXFDATAGOZOFINAL: $("#vDUXFDATAGOZOFINAL").val()
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

function salvarModalUsuariosxRemuneracao(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirUsuariosxRemuneracao",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),			
			vDUXRDATAALTERACAOSALARIAL: $("#vDUXRDATAALTERACAOSALARIAL").val(),
			vMUXRSALARIOATUAL: $("#vMUXRSALARIOATUAL").val(),
			vSUXRMOTIVOALTERACAOSALARIAL: $("#vSUXRMOTIVOALTERACAOSALARIAL").val()
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

function exibirAcessosGrupos(vIUSUPERFIL)
{
	
	$('input:checkbox').prop('checked',false);

	//var pSPerfil = document.getElementById("vIUSUPERFIL").value;
	var vSUrl = "transaction/transactionGrupoAcessos.php?method=searchGruposAcessosUsuarios&pSPerfil="+vIUSUPERFIL;

	var checkboxNomes   = ['vKConsulta', 'vKInclusao', 'vKAlteracao', 'vKExclusao'];
	var operacaoesNomes = ['CONSULTA', 'INCLUSAO', 'ALTERACAO', 'EXCLUSAO'];

	$.getJSON(vSUrl, function(json) {
		console.log(json);
		var selector, value;

		$.each(json, function( index, obj){
			for( var i in checkboxNomes ){

				selector = '#' + checkboxNomes[i] + obj.ACESSOCOD;
				value    = obj[operacaoesNomes[i]];

				$(selector).val(obj.ACESSOCOD);

				if( value === 'S'){
					$(selector).prop('checked', true);
				}else if( value === 'N'){
					$(selector).prop('checked', false);
				}
			}
		});
	});	
}	

function fillVTValorLinha(vIVXTCODIGO){
	var vSUrl = 'transaction/transactionValesTransporte.php?hdn_metodo_fill=fill_ValesTransporte&vIVXTCODIGO='+vIVXTCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {				
			$("#vMUXVVALOR").val(json.VXTVALORUNITARIO);			
		}
	});
}
