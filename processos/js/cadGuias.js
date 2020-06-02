$(function(){
	$(".btnLimparCliente").hide();
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);
			 exibirClientexConsultor('vIGUICONSULTOR', ui.item.id, '', '');
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

function exibirClientexConsultor(pSNAME, pICLICODIGO, pIUSUCODIGO, pSMETHOD) {
    if (pICLICODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "../twcore/teraware/componentes/combos/comboClientexConsultor.php",
            data: {
            	vSNAME: pSNAME,
                vIUSUCODIGO: pIUSUCODIGO,
                vICLICODIGO: pICLICODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillClientexConsultor'
            },
            success: function(resposta){
                document.getElementById('divConsultor').innerHTML = resposta;
                return true;
            },
            error: function(msg) {
                sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
                return false;
            }
        });
    }
}

$(function () {

    var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vIGUICODIGO").val());
		form.append('vSGEDDIRETORIO', 'guias');
		form.append('vIMENCODIGO', 1945);
		form.append('method', 'incluirGuiasxGED');
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');
		form.append('vIGEDTIPO', 26993);
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vIGUICODIGO").val();
			$.ajax({
				url: "transaction/transactionGuiasxGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
					gerarGridJSON('transactionGuiasxGED.php', 'div_ged', 'GuiasxGED', hdn_pai_codigo);
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });
});