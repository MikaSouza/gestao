$(function(){

	$("#vHCONTRATO").autocomplete({
        source: "../comercial/combos/comboContratosxClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICTRCODIGO').val(ui.item.id);
			$('#vICLICODIGO').val(ui.item.cliente);
            $(this).prop('disabled', true);
            $(".btnLimparContrato").show();	
        }
    });	
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);
			exibirClientexContatos(ui.item.id, '', '');
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });	
	
	$("#vHCONTATO").autocomplete({
        source: "../cadastro/combos/comboPessoasxContatos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICONCODIGO').val(ui.item.id);			 
            $(this).prop('disabled', true);
            $(".btnLimparContato").show();	
        }
    });	
	    
});

function removerContrato()	{
	$('#vHCONTRATO').prop('disabled', false);
	$('#vHCONTRATO').val('');
	$('#vICTRCODIGO').val('');    
	$(".btnLimparContrato").hide();	
}

function removerCliente()	{
	$('#vHCLIENTE').prop('disabled', false);
	$('#vHCLIENTE').val('');
	$('#vICLICODIGO').val('');    
	$(".btnLimparCliente").hide();	
}

function removerContato(){
    $('#vHCONTATO').prop('disabled', false);
	$('#vHCONTATO').val('');
	$('#vICONCODIGO').val('');    
	$(".btnLimparContato").hide();
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

function mostrarDivContrato(){
	if ($('#checkbox1').is(':checked')) {
		$(".divContrato").show();	
		document.getElementById("vHCONTRATO").classList.add("obrigatorio");
		$(".divCliente").hide();	
		document.getElementById("vHCLIENTE").classList.remove("obrigatorio");
		$("input[type=checkbox][name='checkbox2']:checked").prop('checked', false);
		$(".btnLimparContrato").hide();
	}else{		
		$(".divContrato").hide();	
		document.getElementById("vHCONTRATO").classList.remove("obrigatorio");		
	}	
}

function mostrarDivCliente(){
	if ($('#checkbox2').is(':checked')) {
		$(".divCliente").show();	
		document.getElementById("vHCLIENTE").classList.add("obrigatorio");
		$(".divContrato").hide();	
		document.getElementById("vHCONTRATO").classList.remove("obrigatorio");
		document.getElementById("vHCONCODIGO").classList.remove("obrigatorio");
		$("input[type=checkbox][name='checkbox1']:checked").prop('checked', false);
		$(".btnLimparCliente").hide(); 
	}else{		
		$(".divCliente").hide();	
		document.getElementById("vHCLIENTE").classList.remove("obrigatorio");		
	}	
}

function mostrarDivContato(){
	if ($('#checkbox3').is(':checked')) {
		$(".divCliente").show();	
		document.getElementById("vHCLIENTE").classList.add("obrigatorio");
		$(".divContato").show();
		if ($('#vICLICODIGO').val() > 0)
			exibirClientexContatos($('#vICLICODIGO').val(), '', '');
	}else{		
		$(".divContato").hide();	
		document.getElementById("vHCONCODIGO").classList.remove("obrigatorio");		
		document.getElementById("vHCLIENTE").classList.remove("obrigatorio");	
	}	
}

function validarContrato(){
    if (($('#vICTRCODIGO').val() == '') && ($('#vHCONTRATO').val() != '')) {
        $('#vHCONTRATO').val('');
        document.getElementById("aviso-contrato").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-contrato").style.display = 'none';
    }
}

function validarCliente(){
    if (($('#vICLICODIGO').val() == '') && ($('#vHCLIENTE').val() != '')) {
        $('#vHCLIENTE').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function validarContato(){
    if (($('#vICONCODIGO').val() == '') && ($('#vHCONTATO').val() != '')) {
        $('#vHCONTATO').val('');
        document.getElementById("aviso-contato").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-contato").style.display = 'none';
    }
}

function exibirClientexContatos(vICLICODIGO, vICONCODIGO, pSMETHOD) {
    if (vICLICODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "combos/comboAgendaxContatos.php",
            data: {
                vICONCODIGO: vICONCODIGO,
                vICLICODIGO: vICLICODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillClientexContatos'
            },
            success: function(resposta){
                document.getElementById('divContatos').innerHTML = resposta;
                return true;
            },
            error: function(msg) {
                sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
                return false;
            }
        });
    }
}

$(function () {
    $('.wrapper1').on('scroll', function (e) {
        $('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
    }); 
    $('.wrapper2').on('scroll', function (e) {
        $('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
    });
});
$(window).on('load', function (e) {
    $('.div1').width($('table').width());
    $('.div2').width($('table').width());
});