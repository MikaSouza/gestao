$(function(){
	$(".btnLimparCliente").hide();
	
    $("#vHFAVORECIDO").autocomplete({
        source: "../cadastro/combos/comboFavorecidos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIFAVCODIGO').val(ui.item.id);
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });	
	
	 var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vICTPCODIGO").val());
		form.append('vSGEDDIRETORIO', 'contaspagar');
		form.append('vIMENCODIGO', 1983);
		form.append('method', 'incluirClientesxGED'); 
		form.append('vIGEDTIPO', $("#vHGEDTIPO").val());
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');		
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vICTPCODIGO").val();
			$.ajax({
				url: "../cadastro/transaction/transactionClientesxGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
					gerarGridJSONGED('../../cadastro/transaction/transactionClientesxGED.php', 'div_ged', 'ClientesxGED', hdn_pai_codigo, '1983');
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });

});

function validarCliente(){
    if (($('#vIFAVCODIGO').val() == '') && ($('#vHFAVORECIDO').val() != '')) {
        $('#vHFAVORECIDO').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function removerCliente()	{
	$('#vHFAVORECIDO').prop('disabled', false);
	$('#vHFAVORECIDO').val('');
	$('#vIFAVCODIGO').val('');    
	$(".btnLimparCliente").hide();	
}
