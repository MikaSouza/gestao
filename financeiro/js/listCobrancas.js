$(document).ready(function() {
    $('#formDiario').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formDiario').serialize();
		
		let contas = [];
		$("input[type=checkbox][name='vEnviarDiario[]']:checked").each(function(index, el) {
			contas.push($(this).val());
		});
		
		$.ajax({
			url: 'transaction/transactionContasPagar.php?hdn_metodo_search=liquidarContasPagar',
			type: 'POST',
			async: true,
			dataType: 'json',
			data: {
				vACTRCODIGO: contas,
				vSCTRHISTORICO: $('#vSCTRHISTORICO').val(),				
				vDCTRDATACONTATO: $('#vDCTRDATACONTATO').val()
			},
			success: function(response) {
				swal(':)', 'Dados atulizados com sucesso!', 'success');
				
				$( "#modalDiario").modal("hide");	
				gerarGridJSONContasPagar();
			},
			error: function() {
				swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			}
		});
		
	});
})

function abrirModalLiquidar(){	
	$( "#modalDiario").modal("show");	
}	

function salvarLiquidacao(){
	var vSForm = document.querySelector('formDiario');
	var data = new FormData(vSForm);
	console.log(data);		
}	

