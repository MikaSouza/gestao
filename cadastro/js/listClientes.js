function enviarEmailInfoPreliminares(){	
	
	let clientes = [];
	$("input[type=checkbox][name='vEnviarEmail[]']:checked").each(function(index, el) {
		clientes.push($(this).val());
	});
	console.log(clientes);
	swal.fire({
		title: "Enviar E-mail",
		text: "Você Deseja Enviar o e-mail com a solicitação das informações preliminares para os clientes selecionados?",
		type: 'info',
		showCancelButton: true,
		confirmButtonText: "Sim, enviar!",
		cancelButtonText: "Não, enviar!",
		reverseButtons: true
	}).then((result) => {	
		if (result.value) {
			$.ajax({
				url: 'transaction/transactionClientes.php?method=enviarEmailInfoSistema',
				type: 'POST',
				async: true,
				dataType: 'json',
				data: {
					vACLICODIGO: clientes
				},
				success: function(response) {
					if (response.success) {
						swal('', response.msg, 'success');
					} else {
						swal('', response.msg, 'error');
					}
					$("input[type=checkbox][name='vEnviarEmail[]']:checked").prop('checked', false);
				},
				error: function() {
					swal('', 'Ocorreu uma falha no envio dos E-mails', 'error');
				}
			});
		} else if (
		  // Read more about handling dismissals
		  result.dismiss === Swal.DismissReason.cancel
		) {
		  swal.fire(
			'Cancelado',
			'O registro não foi enviado :)',
			'error'
		  )
		}
	})		
}	
