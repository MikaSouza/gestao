$(function(){
	$("#vSCLICNPJ").mask("99.999.999/9999-99");
	
	mostrarJxF('J');
});	

function buscarTriagem() {
	var vSAlerta = "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
    var vSErro = 'N';
    if (($("#vSCLICNPJ").val() == '') && ($("#vSCLICPF").val() == '') && ($("#vSCLINOME").val() == '') && ($("#vSCLICONTATO").val() == '') && ($("#vSCLIEMAIL").val() == '') && ($("#vSCLIFONE").val() == '')) {
		var vSErro = 'S';
        vSAlerta += "<br/>* Prencha um campo para realizar a Triagem!";    
    } 
    if (vSErro == 'S'){
		Swal.fire('Opss..', vSAlerta, 'warning');
    } else{

		$.ajax({
			type: "POST",
			url: "transaction/transactionTriagem.php",
			data: $("#formTriagem").serialize(),
			async: true,
			success: function(vSReturn){
				$("#div_triagem").html(vSReturn);
			},
			complete: function() {

			}

		});
	}
}

function mostrarJxF(pSValue){
	var element = document.getElementById("J");
	if (pSValue == 'J') {
		$(".divJuridica").show();	
		$(".divFisica").hide();	  	
		$("#vSCLICPF").val('');	
	}else{
		$(".divFisica").show();	
		$(".divJuridica").hide();
		$("#vSCLICNPJ").val('');
	}	
}