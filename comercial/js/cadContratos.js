$(function(){
	$(".select2-multiple").select2();
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

function excluirRegistroGridFilha(
    pOID,
    pSTransaction,
    pSMethod,
    pSDivRetorno,
    pID_PAI,
    pSFuncaoRetorno
) {
    swal.fire({
        title: "Deseja excluir/inativar o(s) registro(s) selecionados?",
        text: "Excluir/Inativar registro(s)!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Não, cancelar!",
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                async: false,
                type: "POST",
                url: "transaction/" + pSTransaction,
                data: {
                    pSCodigos: pOID,
                    method: pSMethod,
                },
                success: function (msg) {
                    swal.fire("Excluído!", msg, "success");  

                    gerarGridJSONGED(
                        pSTransaction,
                        pSDivRetorno,
                        pSFuncaoRetorno,
                        pID_PAI,
						$("#vHMENCODIGO").val()
                    );

                    return true;
                },
                error: function (msg) {
                    swal.fire(
                        "Oops...",
                        "Ocorreu um erro inesperado! " + msg,
                        "error"
                    );
                    return false;
                },
            });
        } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swal.fire("Cancelado", "O registro não foi excluído :)", "error");
        }
    });
}