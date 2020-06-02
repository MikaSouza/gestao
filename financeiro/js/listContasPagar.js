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

function salvarLiquidacao(){
	var vSForm = document.querySelector('formLiquidar');
	var data = new FormData(vSForm);
	console.log(data);		
}	

