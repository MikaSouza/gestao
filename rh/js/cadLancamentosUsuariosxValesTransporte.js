function gerarGridColaboradores()
{
	$.ajax({
		type: "POST",
		url: "transaction/transactionLancamentosUsuariosxValesTransporte.php",
		data: {
				hdn_metodo_search : 'searchUsuariosxValesTransporteFilhos',
				vILUVMES : $("#vILUVMES").val(),
				vILUVANO : $("#vILUVANO").val(),
				vIEMPCODIGO : $("#vIEMPCODIGO").val(),
				vIVXTCODIGO : $("#vIVXTCODIGO").val()
			},
		async: true,
		success: function(vSReturn){
			// console.log(vSReturn);

			$("#div_colaboradores").html(vSReturn);
		},
		complete: function() {
			//disabled.prop('disabled', true);
		}
	});
	
}	

function diasUteis()
{	
	var vILUVMES = $("#vILUVMES").val();
	var	vILUVANO = $("#vILUVANO").val();
	if ((vILUVMES > 0) && (vILUVANO > 0)) {
		$.ajax({
			type: "POST",
			url: "transaction/transactionLancamentosUsuariosxValesTransporte.php",
			data: {
					hdn_metodo : 'diasUteisUsuariosxValesTransporte',
					vILUVMES : $("#vILUVMES").val(),
					vILUVANO : $("#vILUVANO").val()
				},
			async: true,
			success: function(vSReturn){
				$("#vILUVDIAS").val(vSReturn);
			},
			complete: function() {
				//disabled.prop('disabled', true);
			}
		});
	}	
}	