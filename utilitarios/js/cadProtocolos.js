function removerCliente()	{
	$('#vIPTOPASTA').prop('disabled', false);
	$('#vIPTOPASTA').val('');
	$('#vICLICODIGO').val('');  
	$(".btnLimparCliente").hide();	
	$(".divComplemento").hide();	
}

function validarCliente(){
    if (($('#vICLICODIGO').val() == '') && ($('#vIPTOPASTA').val() != '')) {
        $('#vIPTOPASTA').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function fillPrazo(pSNAME, vSPTOTIPO, pIPTOPRAZO, pSMETHOD) {

	if (vSPTOTIPO != ''){
		if ((vSPTOTIPO == 'A') || (vSPTOTIPO == 'D') || (vSPTOTIPO == 'S'))
			var vSTipo = 'PROTOCOLOS - PRAZO OUTROS';
		else if (vSPTOTIPO == 'M')
			var vSTipo = 'PROTOCOLOS - PRAZO MARCAS';
		else if (vSPTOTIPO == 'P')
			var vSTipo = 'PROTOCOLOS - PRAZO PATENTES';	
		jQuery.ajax({
			async: true,
			type: "GET",
			url: "combos/comboTabelasxProtocolos.php",
			data: {
				vSNAME: pSNAME,
                vIPTOPRAZO: pIPTOPRAZO,
                vSTABTIPO: vSTipo,
                vSMETHOD: pSMETHOD,
				method : 'fillProtocoloPrazos'
			},
			success: function(resposta){
                document.getElementById('divPrazo1').innerHTML = resposta;
				return true;
			},
			error: function(msg) {
				sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
				return false;
			}
		});
    }
}	

function fillPasta(vIPTOPASTA){
	jQuery.ajax({
        async: true,
        type: "GET",
        url: "combos/comboProtocolos.php",
        data: {
            vIPTOPASTA: vIPTOPASTA,
            method: 'fillDadosPasta'
        },
        success: function(resposta){
            document.getElementById('divPrazo2').innerHTML = resposta;
			$(".divComplemento").show();	
			$(".btnLimparCliente").show();
			/*
			$('#vICLICODIGO').val(ui.item.id);
            //$(this).prop('disabled', true);
			
            $(".btnLimparCliente").show();	*/
            return true;
        },
        error: function(msg) {
            alert("Ocorreu um erro na busca da cidade!"+msg);
            return false;
        }
    });
}	