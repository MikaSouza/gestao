$(function(){	

	var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vIPRCCODIGO").val());
		form.append('vSGEDDIRETORIO', 'juridico');
		form.append('vIMENCODIGO', 2010);
		form.append('method', 'incluirGED'); 
		form.append('vIGEDTIPO', $("#vHGEDTIPO").val());
    });	

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');		
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vIPRCCODIGO").val();
			$.ajax({
				url: "../utilitarios/transaction/transactionGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});					
					gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', hdn_pai_codigo, '2010');
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });
	
});

function fillProcessoxFases(vIPXFCODIGO,titulo){
	var vSUrl = 'transaction/transactionProcessoxFases.php?hdn_metodo_fill=fill_ProcessoxFases&vIPXFCODIGO='+vIPXFCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#hdn_pai_"+titulo).val(json.PXFCODIGO);
			$("#vDPXFDATA").val(json.PXFDATA);
			$("#vIAGERESPONSAVEL").val(json.PXFRESPONSAVEL);
			$("#vIPXFTIPOFASE").val(json.PXFTIPOFASE);
			$("#vSPXFPRAZO").val(json.PXFPRAZO);
			$("#vSPXFALARME").val(json.PXFALARME);
			$("#vSPXFVISIVEL").val(json.PXFVISIVEL);
			$("#vSPXFPENDENTE").val(json.PXFPENDENTE);
			$("#vSPXFDESCRICAO").val(json.PXFDESCRICAO);
		}
	}); 
}

function salvarModalProcessoxFases(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
	var erros = validarCamposDiv(div_nome);
	if(erros.length === 0){
		var data = {
			method: "incluirProcessoxFases",
			hdn_filho_codgo: $("#hdn_filho_"+pMetodo).val(),
			hdn_pai_codgo: $("#hdn_pai_"+pMetodo).val(),						
			vSPXFDATA: $("#vSPXFDATA").val(),
			vIPXFRESPONSAVEL: $("#vIPXFRESPONSAVEL").val(),
			vSPXFTIPOFASE: $("#vSPXFTIPOFASE").val(),
			vSPXFPRAZO: $("#vSPXFPRAZO").val(),
			vSPXFALARME: $("#vSPXFALARME").val(),
			vSPXFVISIVEL: $("#vSPXFVISIVEL").val(),
			vSPXFPENDENTE: $("#vSPXFPENDENTE").val(),
			vSPXFDESCRICAO: $("#vSPXFDESCRICAO").val(),
			vSPXFDATAALARME: $("#vSPXFDATAALARME").val(),
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