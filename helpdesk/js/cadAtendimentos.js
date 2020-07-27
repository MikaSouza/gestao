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
            url: "../cadastro/combos/comboContatos.php",
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
				swal(':)', 'Dados atulizados com sucesso!', 'success');
				
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
