$(document).ready(function() {
    $('#formLiquidar').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formLiquidar').serialize();
		
		let contas = [];
		$("input[type=checkbox][name='vEnviarLiquidar[]']:checked").each(function(index, el) {
			contas.push($(this).val());
		});
		console.log(contas);
		console.log($('#vMCTPVALORPAGO').val());
		
		$.ajax({
			url: 'transaction/transactionContasPagar.php?hdn_metodo_search=liquidarContasPagar',
			type: 'POST',
			async: true,
			dataType: 'json',
			data: {
				vACTPCODIGO: contas,
				vMCTPVALORPAGO: $('#vMCTPVALORPAGO').val(),
				vITABFORMAPAGAMENTO: $('#vITABFORMAPAGAMENTO').val(),
				vDCTPDATAPAGAMENTO: $('#vDCTPDATAPAGAMENTOLIQ').val(),
				vICBACODIGO: $('#vICBACODIGO').val()
			},
			success: function(response) {
				swal(':)', 'Dados atulizados com sucesso!', 'success');
				
				$( "#modalLiquidar").modal("hide");	
				gerarGridJSONContasPagar();
			},
			error: function() {
				swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			}
		});
		
	});
})

function abrirModalLiquidar(){	
	$( "#modalLiquidar").modal("show");	
}	

function faturarNFSe(){
	alert('foi');	
	let contas = [];
	$("input[type=checkbox][name='vEnviarNFSe[]']:checked").each(function(index, el) {
		contas.push($(this).val());
	});
	console.log(contas);
	
	
	if(!contas){
		swal(':(', 'Por favor selecione um registro!', 'info');		
		return;
	}	
	
	swal({
		title: 'Como você deseja emitir NFSe para o fatura selecionada?',
		text: 'Gostaria de gerar uma única nota fiscal para o mesmo cliente quando houver mais de uma cobrança para o mesmo?',
		type: 'info',
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		var vSUnificar = 'S';
	});
	
	
	var conf = confirm("Gostaria de gerar uma única nota fiscal para o mesmo cliente quando houver mais de uma cobrança para o mesmo?");
    if (conf == true)
		var vSUnificar = 'S';
	else
		var vSUnificar = 'N';	
	var data = {
		method: 'faturarContasReceber',		
		pLOids: contas,
		pSUnificar: vSUnificar
	};

	jQuery.ajax({
		async: false,
		type: "POST",
		data: data,
		url: "transaction/transactionContasReceber.php",
		success: function(msg){
			swal(':)', 'Dados atualizados com sucesso!', 'success');
			return true;
		},
		error: function(msg) {
			swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			return false;
		}
	})
}


function abrirModalDocumentos(){	
	$( "#modalDocumentos").modal("show");	
}

function salvarModalDocumentos(){	
	var vICXPCODIGO = $('#vICXPCODIGO').val();
	var vIDOPCODIGO = $('#vIDOPCODIGO').val();	
	if (vIDOPCODIGO == 'R')
		window.open('impReciboCreditoImpresso.php?vIDOPCODIGO='+vIDOPCODIGO+'&vICXPCODIGO='+vICXPCODIGO);
	else if (vIDOPCODIGO == 'O')
		window.open('impReciboCreditoVirtual.php?vIDOPCODIGO='+vIDOPCODIGO+'&vICXPCODIGO='+vICXPCODIGO);
	else if (vIDOPCODIGO == 'N')
		window.open('../twcore/vendors/impReciboCreditoVirtual.php?vIDOPCODIGO='+vIDOPCODIGO+'&vICXPCODIGO='+vICXPCODIGO);
	$( "#modalDocumentos").modal("hide");
}

