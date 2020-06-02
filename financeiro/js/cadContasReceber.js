$(function(){
	$(".btnLimparCliente").hide();
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);
			 exibirClientexConsultor('vICTRCONSULTOR', ui.item.id, '', '');
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });	
		
	$(".divCA").hide();	
	$(".divContrato").hide();	
});

function validarCliente(){
    if (($('#vICLICODIGO').val() == '') && ($('#vHCLIENTE').val() != '')) {
        $('#vHCLIENTE').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function removerCliente()	{
	$('#vHCLIENTE').prop('disabled', false);
	$('#vHCLIENTE').val('');
	$('#vICLICODIGO').val('');    
	$(".btnLimparCliente").hide();	
}

function exibirClientexConsultor(pSNAME, pICLICODIGO, pIUSUCODIGO, pSMETHOD) {
    if (pICLICODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "../twcore/teraware/componentes/combos/comboClientexConsultor.php",
            data: {
            	vSNAME: pSNAME,
                vIUSUCODIGO: pIUSUCODIGO,
                vICLICODIGO: pICLICODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillClientexConsultor'
            },
            success: function(resposta){
                document.getElementById('divConsultor').innerHTML = resposta;
                return true;
            },
            error: function(msg) {
                sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
                return false;
            }
        });
    }
}

function exibirContasBancarias(vIEMPCODIGO, vICBACODIGO, pSMETHOD) {
    if (vIEMPCODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "combos/comboContasBancarias.php",
            data: {            	
                vICBACODIGO: vICBACODIGO,
                vIEMPCODIGO: vIEMPCODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillContasBancarias'
            },
            success: function(resposta){
                document.getElementById('divContasBancarias').innerHTML = resposta;
                return true;
            },
            error: function(msg) {
                sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
                return false;
            }
        });
    }
}

function gerarCA(pSValue){
	if (pSValue == 'S') {
		$(".divCA").show();	
  		//document.getElementById("vSCLICNPJ").classList.add("obrigatorio");
	}else{		
		$(".divCA").hide();			
  		//document.getElementById("vSCLICNPJ").classList.remove("obrigatorio");
	}	
}

function gerarContrato(pSValue){
	if (pSValue == 'S') {
		$(".divContrato").show();	
  		//document.getElementById("vSCLICNPJ").classList.add("obrigatorio");
	}else{		
		$(".divContrato").hide();			
  		//document.getElementById("vSCLICNPJ").classList.remove("obrigatorio");
	}	
}

function fillContasReceberxParcelasVinculo(pICXPCODIGO, titulo){
	var vSUrl = 'transaction/transactionContasReceberxParcelas.php?hdn_metodo_fill=fill_ContasReceberxParcelasVinculo&CXPCODIGO='+pICXPCODIGO+'&formatoRetorno=json';
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

function salvarModalContasReceberxParcelasVinculo(div_nome,pSTransaction, pSDivReturn, pMetodo, pIOID){
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