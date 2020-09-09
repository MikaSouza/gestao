$(function () {
	$('.select2-multiple').select2();
    var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vICLICODIGO").val());
		form.append('vSGEDDIRETORIO', 'clientes');
		form.append('vIMENCODIGO', 6);
		form.append('method', 'incluirGED');
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');
		form.append('vIGEDTIPO', $("#vHGED").val());
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vICLICODIGO").val();
			$.ajax({
				url: "../utilitarios/transaction/transactionGED.php",
				data: form,
				processData: false, 
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});				
					gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', hdn_pai_codigo, '6');
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}	
    });

});