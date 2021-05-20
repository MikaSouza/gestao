$(function(){

	var form;
    $('#fileUpload').change(function (event) {
        form = new FormData();
        form.append('fileUpload', event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
		form.append('vIGEDVINCULO', $("#vIUSUCODIGO").val());
		form.append('vSGEDDIRETORIO', 'usuarios');
		form.append('vIMENCODIGO', 1966);
		form.append('method', 'incluirGED');
		form.append('vIGEDTIPO', $("#vHGEDTIPO").val());
    });

    $('#btnEnviar').click(function () {
		var erros = validarCamposDiv('modal_div_ClientesxGED');
		if(erros.length === 0){
			var hdn_pai_codigo = $("#vIUSUCODIGO").val();
			$.ajax({
				url: "../utilitarios/transaction/transactionGED.php",
				data: form,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function (data) {
					swal({title : "", text :"Cadastro realizado com sucesso", type : "success"});
					gerarGridJSONGED('../../utilitarios/transaction/transactionGED.php', 'div_ged', 'GED', hdn_pai_codigo, '1966');
					return true;
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}
    });

});

function exibirCidades(codestado, codcidade, div_retorno, vSNome) {

    jQuery.ajax({
        async: true,
        type: "GET",
        url: "../cadastro/combos/comboCidades.php",
        data: {
            codestado: codestado,
			codcidade: codcidade,
			vSNome: vSNome,
            method: 'fillcidade'
        },
        success: function(resposta){
            document.getElementById(div_retorno).innerHTML = resposta;
            return true;
        },
        error: function(msg) {
            alert("Ocorreu um erro na busca da cidade!"+msg);
            return false;
        }
    });
}

$(function(){
	$("#vHUXECEP").mask("99999-999");
	$("#vHUXECEP").on('focusout', function(){
		$.ajax({
			url: '../includes/buscarCEP.php',
			type: 'GET',
			dataType: 'json',
			data: {
				cep: $(this).val()
			},
			success: function(result){

				if(result.logradouro != ''){
					$("#vHUXELOGRADOURO").val(result.logradouro).addClass('isActive');
					$("#vHUXEBAIRRO").val(result.bairro).addClass('isActive');
					$("#vHESTCODIGO").val(result.estadoCodigo).addClass('isActive');
					exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade', 'vHCIDCODIGO');
					$("#vHUXENROLOGRADOURO").focus();
				}else{
					sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	});

});

function exibirAcessosGrupos(vIUSUPERFIL)
{

	$('input:checkbox').prop('checked',false);

	//var pSPerfil = document.getElementById("vIUSUPERFIL").value;
	var vSUrl = "transaction/transactionGrupoAcessos.php?method=searchGruposAcessosUsuarios&pSPerfil="+vIUSUPERFIL;

	var checkboxNomes   = ['vKConsulta', 'vKInclusao', 'vKAlteracao', 'vKExclusao'];
	var operacaoesNomes = ['CONSULTA', 'INCLUSAO', 'ALTERACAO', 'EXCLUSAO'];

	$.getJSON(vSUrl, function(json) {
		console.log(json);
		var selector, value;

		$.each(json, function( index, obj){
			for( var i in checkboxNomes ){

				selector = '#' + checkboxNomes[i] + obj.ACESSOCOD;
				value    = obj[operacaoesNomes[i]];

				$(selector).val(obj.ACESSOCOD);

				if( value === 'S'){
					$(selector).prop('checked', true);
				}else if( value === 'N'){
					$(selector).prop('checked', false);
				}
			}
		});
	});
}


function enviarAcessos(){

	var array = document.getElementsByClassName('acessos');
	var arraySelecionados = [];

	for (let i = 0; i < array.length; i++) {
		if(array[i].checked){
			var arr = array[i].id.split("-");
			arraySelecionados.push(arr[1]);
		}

	}
	var data = {
		method: "enviarAcessos",
		arraySelecionados: arraySelecionados
	};

	// console.log(data);

	$.ajax({
		async: true,
		type: "POST",
		url: "transaction/transactionUsuario.php",
		data: data,
		beforeSend: function() {
			Swal.fire({
				position: 'center',
				icon: 'warning',
				title: 'Carregando!',
				text: 'Rotina sendo executada...',
				showConfirmButton: false
			});
		},
		success: function(msg){

			// console.log(msg);
			swal({title : "", text :"Acessos enviados com sucesso!", type : "success"});

			return true;
		},
		error: function(msg) {
			sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			alert(msg);
			$( "#modal"+pMetodo ).modal("hide");
			return false;
		}
	});

}