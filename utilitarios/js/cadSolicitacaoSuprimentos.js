$(function () {
	$(".btnLimparProduto1").hide();
	$(".btnLimparProduto2").hide();
	$(".btnLimparProduto3").hide();
	$(".btnLimparProduto4").hide();
	$(".btnLimparProduto5").hide();
	
	$("#vHPRONOME1").autocomplete({
        source: "../estoque/combos/comboProdutos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIPROCODIGO1').val(ui.item.id);		           
            $('#vHPROUNIDADE1').val(ui.item.unidade);
            $(this).prop('disabled', true);
			$(".btnLimparProduto1").show();	           
        }
    });
	
	$("#vHPRONOME2").autocomplete({
        source: "../estoque/combos/comboProdutos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIPROCODIGO2').val(ui.item.id);		           
            $('#vHPROUNIDADE2').val(ui.item.unidade);
            $(this).prop('disabled', true);
            $(".btnLimparProduto2").show();	 
        }
    });
	$("#vHPRONOME3").autocomplete({
        source: "../estoque/combos/comboProdutos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIPROCODIGO3').val(ui.item.id);		           
            $('#vHPROUNIDADE3').val(ui.item.unidade);
            $(this).prop('disabled', true);
            $(".btnLimparProduto3").show();	 
        }
    });
	$("#vHPRONOME4").autocomplete({
        source: "../estoque/combos/comboProdutos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIPROCODIGO4').val(ui.item.id);		           
            $('#vHPROUNIDADE4').val(ui.item.unidade);
            $(this).prop('disabled', true);
            $(".btnLimparProduto4").show();	 
        }
    });
	$("#vHPRONOME5").autocomplete({
        source: "../estoque/combos/comboProdutos.php",
        minLength: 2,
        autoFocus: true,
        select: function(event, ui) {
            $('#vIPROCODIGO5').val(ui.item.id);		           
            $('#vHPROUNIDADE5').val(ui.item.unidade);
            $(this).prop('disabled', true);
            $(".btnLimparProduto5").show();	 
        }
    });
	
});

function validaProduto(vIMarcador){
    if (($('#vIPROCODIGO'+vIMarcador).val() == '') && ($('#vHPRONOME'+vIMarcador).val() != '')) {
        $('#vHPRONOME'+vIMarcador).val('');
        document.getElementById("aviso-produto"+vIMarcador).style.display = 'inline-block';
    } else {
        document.getElementById("aviso-produto"+vIMarcador).style.display = 'none';
    }
}

function removerProduto(vIMarcador)	{
	$('#vHPRONOME'+vIMarcador).prop('disabled', false);
	$('#vHPRONOME'+vIMarcador).val('');
	$('#vIPROCODIGO'+vIMarcador).val('');
	$('#vHPROUNIDADE'+vIMarcador).val('');     
	$(".btnLimparProduto"+vIMarcador).hide();	
}	