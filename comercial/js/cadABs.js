$(function(){
	$(".btnLimparCliente").hide();
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);
			 exibirClientexConsultor('vICXBCONSULTOR', ui.item.id, '', '');
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });
	
	$("#vHCXBCLASSE1").autocomplete({
		source: "../cadastro/combos/comboClasses.php",		
		minLength: 2,
		autoFocus: true,
		select: function(event, ui) {
			$('#vICXBCLASSE1').val(ui.item.id);
		}
	});
	
	$("#vHCXBCLASSE2").autocomplete({
		source: "../cadastro/combos/comboClasses.php",		
		minLength: 2,
		autoFocus: true,
		select: function(event, ui) {
			$('#vICXBCLASSE2').val(ui.item.id);
		}
	});

	$("#vHCXBCLASSE3").autocomplete({
		source: "../cadastro/combos/comboClasses.php",		
		minLength: 2,
		autoFocus: true,
		select: function(event, ui) {
			$('#vICXBCLASSE3').val(ui.item.id);
		}
	});

	$("#vHCXBCLASSE4").autocomplete({
		source: "../cadastro/combos/comboClasses.php",		
		minLength: 2,
		autoFocus: true,
		select: function(event, ui) {
			$('#vICXBCLASSE4').val(ui.item.id);
		}
	});
	
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