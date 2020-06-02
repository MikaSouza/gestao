$(function(){
	$(".btnLimparCliente").hide();
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);			 
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });

});

function removerCliente()	{
	$('#vHCLIENTE').prop('disabled', false);
	$('#vHCLIENTE').val('');
	$('#vICLICODIGO').val('');    
	$(".btnLimparCliente").hide();	
}


function validarCliente(){
    if (($('#vICLICODIGO').val() == '') && ($('#vHCLIENTE').val() != '')) {
        $('#vHCLIENTE').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function buscarEtiquetas() {
	var vSAlerta = "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
    var vSErro = 'N';
    if (($("#vSCLICNPJ").val() == '') && ($("#vSCLICPF").val() == '') && ($("#vSCLINOME").val() == '') && ($("#vSCLICONTATO").val() == '') && ($("#vSCLIEMAIL").val() == '') && ($("#vSCLIFONE").val() == '')) {
		var vSErro = 'S';
        vSAlerta += "<br/>* Prencha um campo para realizar a Etiquetas!";    
    } 
    if (vSErro == 'S'){
		Swal.fire('Opss..', vSAlerta, 'warning');
    } else{

		$.ajax({
			type: "POST",
			url: "transaction/transactionEtiquetas.php",
			data: $("#formEtiquetas").serialize(),
			async: true,
			success: function(vSReturn){
				$("#div_etiquetas").html(vSReturn);
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