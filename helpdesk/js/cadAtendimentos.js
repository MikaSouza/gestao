$(document).ready(function () {
  if($("#vSATEMENSAGEM").length > 0){
      tinymce.init({
          selector: "textarea#vSATEMENSAGEM",
          theme: "modern",
          height:300,
          plugins: [
              "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
              "save table contextmenu directionality emoticons template paste textcolor"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
          style_formats: [
              {title: 'Bold text', inline: 'b'},
              {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
              {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
              {title: 'Example 1', inline: 'span', classes: 'example1'},
              {title: 'Example 2', inline: 'span', classes: 'example2'},
              {title: 'Table styles'},
              {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
          ]
      });
  }
});


function exibirClientexContatos(vICLICODIGO, vICONCODIGO, pSMETHOD) {
    if (vICLICODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "combos/comboAtendimentoxContatos.php",
            data: {
                vICONCODIGO: vICONCODIGO,
                vICLICODIGO: vICLICODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillClientexContatos'
            },
            success: function(resposta){
                document.getElementById('divContatos').innerHTML = resposta;
                return true;
            },
            error: function(msg) {
                sweetAlert("Oops...", "Ocorreu um erro inesperado! "+msg, "error");
                return false;
            }
        });
    }
}

function exibirClientexProduto(vICLICODIGO, vICONCODIGO, pSMETHOD) {
    if (vICLICODIGO > 0){
        jQuery.ajax({
            async: true,
            type: "GET",
            url: "../cadastro/combos/comboContatos.php",
            data: {
                vICONCODIGO: vICONCODIGO,
                vICLICODIGO: vICLICODIGO,
                vSMETHOD: pSMETHOD,
				method : 'fillClientexContatos'
            },
            success: function(resposta){
                document.getElementById('divProduto').innerHTML = resposta;
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
	
    $("#vHCLIENTE").autocomplete({
        source: "../cadastro/combos/comboClientes.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vICLICODIGO').val(ui.item.id);
			exibirClientexContatos(ui.item.id, '', '');
            $(this).prop('disabled', true);
            $(".btnLimparCliente").show();	
        }
    });	
	    
	
	$('#formContatos').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formContatos').serialize();				
		var data = {
					method           : "incluirContato",
					vICONCODIGO      : $("#vHCONCODIGO").val(),
					vICLICODIGO      : $("#vICLICODIGO").val(),
					vSCONNOME        : $("#vSCONNOME").val(),
					vICONESTADOCIVIL : $("#vICONESTADOCIVIL").val(),
					vSCONEMAIL       : $("#vSCONEMAIL").val(),
					vSCONCPF         : $("#vSCONCPF").val(),
					vSCONRG          : $("#vSCONRG").val(),
					vSCONFONE        : $("#vSCONFONE").val(),
					vSCONRAMAL       : $("#vSCONRAMAL").val(),
					vSCONCELULAR     : $("#vSCONCELULAR").val(),
					vSCONCARGO       : $("#vSCONCARGO").val(),
					vSCONSETOR       : $("#vSCONSETOR").val(),
					vSCONPRINCIPAL   : $("#vSCONPRINCIPAL").val()
				};
		$.ajax({
			url: 'transaction/transactionContatos.php',
			type: 'POST',
			async: true,
			dataType: 'json',
			data: data,
			success: function(response) {
				swal(':)', 'Dados atualizados com sucesso!', 'success');
				
				$( "#modalContatos").modal("hide");	
				gerarGridJSONContasPagar();
			},
			error: function() {
				swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
			}
		});
		
	});
});

function validarCliente(){
    if (($('#vICLICODIGO').val() == '') && ($('#vHCLIENTE').val() != '')) {
        $('#vHCLIENTE').val('');
        document.getElementById("aviso-cliente").style.display = 'inline-block';
    } else {
        document.getElementById("aviso-cliente").style.display = 'none';
    }
}

function removerCliente()	{
	$('#vHCLIENTE').prop('disabled', false);
	$('#vHCLIENTE').val('');
	$('#vICLICODIGO').val('');    
	$(".btnLimparCliente").hide();	
}

function fillContatos(pICONCODIGO){
	var vSUrl = '../transaction/transactionClientexContato.php?hdn_metodo_fill=fill_Contato&vICONCODIGO='+pICONCODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
			$("#vSCONNOME").val(json.CONNOME);
			$("#vSCONEMAIL").val(json.CONEMAIL);
			$("#vSCONCELULAR").val(json.CONCELULAR);
			$("#vSCONFONE").val(json.CONFONE);
			$("#vSCONCPF").val(json.CONCPF);
			$("#vSCONCARGO").val(json.CONCARGO);
			$("#vSCONRAMAL").val(json.CONRAMAL);
			$("#vICONCODIGO").val(json.CONCODIGO);
			$("#vICLICODIGO").val(json.CLICODIGO);
			$("#vICONESTADOCIVIL").val(json.CONESTADOCIVIL);
			$("#vSCONRG").val(json.CONRG);
			$("#vSCONSETOR").val(json.CONSETOR);
			$("#vSCONPRINCIPAL").val(json.CONPRINCIPAL);
		}
	});
}

var incluirNovoContato = function(){

	var contato_id = $("#vHCONCODIGO").val(),
		input_hidden = "";

	if(contato_id != ""){

		var isResponsavel = confirm("Este é o responsável pelo atendimento?") ? 'S' : 'N';

		var modoExibicao = ( $("#vIATECODIGO").val() == "" ) ? "insert" : "update";

		if( modoExibicao == "insert"){
			if(isResponsavel == 'S'){ 
				$("#AXCRESPONSAVEL").val(contato_id);
			}
			input_hidden = "<div id='hiddencontatos'><input type='hidden' name='vACONCODIGO[]' value='"+contato_id+"' /></div>";
			console.log( input_hidden );
			$("#gridContatos").append(input_hidden);
			$("#vHCONCODIGO").val('');
			gerarGridContato();

		} else if( modoExibicao == "update"){

			var data = {
				method:  'incluirAtendimentosxContatos',
				pIATECODIGO: $("#vIATECODIGO").val(),
				pICLICODIGO: $("#vICLICODIGO").val(),
				pICONCODIGO: $("#vHCONCODIGO").val(),
				pSAXCRESPONSAVEL: isResponsavel
			};

			$.ajax({
				url: "transaction/transactionAtendimentosxContatos.php",
				type: 'POST',
				data: data,
				success: function(){
					gerarGridContato();
					$("#vHCONCODIGO").val('');
				}
			});
		}

	} else { sweetAlert("Oops...", "Por favor selecione um contato!", "warning"); }

}

var removerContato = function( vSCONCODIGO ){

	var modoExibicao = ( $("#vIATECODIGO").val() == "" ) ? "insert" : "update";

	var continuar = confirm("Deseja excluir esse registro?");

	if(continuar){
		if( modoExibicao == "insert"){
			$("input[name='vACONCODIGO[]']").each(function(){
				if($(this).val() == vSCONCODIGO){
					$(this).remove();
					gerarGridContato();
					return false;
				}
			});
		} else if( modoExibicao == "update"){


			var data = {
				method: 'deleta_AtendimentoxContato',
				pSCONCODIGO: vSCONCODIGO,
				pSATECODIGO: $("#vIATECODIGO").val()
			};

			$.ajax({
				url: "transaction/transactionAtendimentosxContatos.php",
				type: 'POST',
				data: data,
				success: function(){
					gerarGridContato();
				}
			});

		}
	}
}

var gerarGridContato = function(){

	var modoExibicao = ( $("#vIATECODIGO").val() == "" ) ? "insert" : "update",
		data = "",
		cliente_id = $("#vICLICODIGO").val();

	if( cliente_id != "" ){

		if( modoExibicao == "insert"){

			var contatos_ids = [],
				vSCONCODIGOS = '';

			$("input[name='vACONCODIGO[]']").each(function(){
				contatos_ids.push($(this).val());
			});

			vSCONCODIGOS = contatos_ids.join(",");

			data = {
				vIATECODIGO : '',
				vICLICODIGO : cliente_id,
				vICONCODIGOS: vSCONCODIGOS,
				vSAXCRESPONSAVEL:$("#AXCRESPONSAVEL").val(),
				hdn_metodo_search: 'search_AtendimentoxContato',
				editavel: $("#formulario_editavel").val()
			};

		}else if( modoExibicao == "update"){

			data = {
				vIATECODIGO : $("#vIATECODIGO").val(),
				vICLICODIGO : cliente_id,
				vICONCODIGOS: '',
				hdn_metodo_search: 'search_AtendimentoxContato',
				editavel: $("#formulario_editavel").val()
			};
		}

		$.ajax({
			url: "transaction/transactionAtendimentosxContatos.php",
			type: 'POST',
			data: data,
			async: false,
			success: function(retorno){
				$("#gridContatos").html(retorno);
				if( $('input[name="vACONCODIGO[]"]').length > 0 ){
					$("#vHCONCODIGO").removeClass("obrigatorio");
				} else if( $('input[name="vACONCODIGO[]"]').length == 0 ){
					if(! $("#vHCONCODIGO").hasClass("obrigatorio"))
						$("#vHCONCODIGO").addClass("obrigatorio");
				}
			}
		});

	} else {
		sweetAlert("Oops...", "O cliente deve ser selecionado antes do contato.", "warning"); 
	}

}