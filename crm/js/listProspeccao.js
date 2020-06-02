function buscarProspeccao() {	
	$.ajax({
		type: "POST",
		url: "transaction/transactionProspeccao.php",
		data: $("#formProspecacao").serialize(),
		async: true,
		success: function(vSReturn){
			$("#div_prospecacao").html(vSReturn);
		},
		complete: function() {

		}

	});
	
}

function abrirModalDocumentos(vICXPCODIGO){	
	$('#vICXPCODIGO').val(vICXPCODIGO);
	$( "#modalDocumentos").modal("show");	
}

function salvarModalDocumentos(){	
	var vICXPCODIGO = $('#vICXPCODIGO').val();
	var vIDOPCODIGO = $('#vIDOPCODIGO').val();
	window.open('imprimirProspeccaoxDocumentosPadroes.php?vIDOPCODIGO='+vIDOPCODIGO+'&vICXPCODIGO='+vICXPCODIGO);
	$( "#modalDocumentos").modal("hide");
}
