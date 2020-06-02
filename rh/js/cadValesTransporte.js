$(function(){
	
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