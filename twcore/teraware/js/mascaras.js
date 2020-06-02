/**
 *  @file mascaras.js
 *  @brief Brief
 */ 

$(function(){

	$('.type_telefone').mask('(99) 9999 9999 Z', {translation:  {'Z': {pattern: /[9-9]/, optional: true}}});

	$(".type_cpf").mask("999.999.999-99", {reverse: true});

	$(".type_cnpj").mask("99.999.999.9999-99", {reverse: true});

	$(".type_data").mask("99/99/9999", {reverse: true});
	$(".type_data").attr('maxlength', '19');
    $(".type_data").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        showOn: "button",
        buttonImage: "../imagens/calendar.gif",
        buttonImageOnly: true
    });
	
	$(".type_data_simples").mask("99/99/9999", {reverse: true});

	$(".type_cep").mask("99999-999", {reverse: true});

	$(".type_hora_min").mask("99:99", {reverse: true});

	$(".type_hora_min_sec").mask("99:99:99", {reverse: true});

	$(".type_data_hora").mask("99/99/9999 99:99", {reverse: true});

});