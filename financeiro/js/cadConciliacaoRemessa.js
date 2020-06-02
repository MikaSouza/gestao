function buscarConciliacaoRemessa() {
	var vSAlerta = "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
    var vSErro = 'N';
    if ($("#vICBACODIGO").val() == '') {
		var vSErro = 'S';
        vSAlerta += "<br/>* Prencha um campo Conta Bancária para gerar o arquivo!";    
    } 
    if (vSErro == 'S'){
		Swal.fire('Opss..', vSAlerta, 'warning');
    } else{

		$.ajax({
			type: "POST",
			url: "transaction/transactionConciliacaoRemessa.php",
			data: $("#formConciliacaoRemessa").serialize(),
			async: true,
			success: function(vSReturn){
				$("#div_remessa").html(vSReturn);
			},
			complete: function() {

			}

		});
	}
}