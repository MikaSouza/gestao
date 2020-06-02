function exibirCidades(codestado, codcidade) {

    jQuery.ajax({
        async: true,
        type: "GET",
        url: "../cadastro/transaction/transactionCidades.php",
        data: {
            codestado: codestado,
			codcidade: codcidade,
            method: 'fillcidade'
        },
        success: function(resposta){
            document.getElementById('div_cidade').innerHTML = resposta;
            return true;
        },
        error: function(msg) {
            alert("Ocorreu um erro na busca do estado!"+msg);
            return false;
        }
    });
}

function mostrarIE(pSValue){
	var element = document.getElementById("vSEMPIE");
	if (pSValue == 'N') {
		$(".divIE").show();		
  		element.classList.add("obrigatorio");
	}else{
		$(".divIE").hide();	
  		element.classList.remove("obrigatorio");
	}	
}

$(function(){

	$("#vSEMPISENTAIE").on('change', function(){
		mostrarIE($(this).val());
	});

    $("#vIESTCODIGO").on('change', function(){
		exibirCidades($(this).val(), '');
	});

	$("#vSEMPCEP").on('focusout', function(){
		$.ajax({
			url: '../includes/buscarCEP.php',
			type: 'GET',
			dataType: 'json',
			data: {
				cep: $(this).val()
			},
			success: function(result){

				if(result.logradouro != ''){
					$("#vSEMPLOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vSEMPBAIRRO").val(result.bairro).addClass('isActive');
					$("#vIESTCODIGO").val(result.estadoCodigo).addClass('isActive');
					exibirCidades(result.estadoCodigo, result.cidadeCodigo);
					$("#vITLOCODIGO").val(result.tipologradouro).addClass('isActive');
					$("#vSEMPNROLOGRADOURO").focus();
					
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
    });
    
    $("#vSEMPCNPJ").mask("99.999.999/9999-99");
	$("#vSEMPCEP").mask("99999-999");
	$("#vSEMPFONE").mask("(99) 9999-9999");
	$("#vSEMPFONE2").mask("(99) 9999-9999");

});