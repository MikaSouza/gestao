/**
 *  @file crud.js
 *  @brief Funções padrões para realização de CRUD no sistema
 */


/**
 *  @brief Função auxiliar que é utilizada para debugar outras funções
 *
 *  @param [objeto] params
 *  
 *  @return Return_Description
 *
 *  @details [Detalhes_Metodo]
 *
 *  @author Adriano Dias da Silva
 */
var debugParams = function( params ) {
	if(params.debug == 'alert_console') {
		alert(dump(params));
		console.log(dump(params));
	} else if(params.debug == 'alert')
		alert(dump(params));
	else if(params.debug == 'console')
		console.log(dump(params));
}

/**
 *  @brief Função para popular os formulários de uma tela específica
 *  
 *  @param [objeto] params
 *  
 *   	   [objeto.data.transaction] Transaction que deve ser utilizada na requisição
 *         [objeto.data.idTable] ID para pesquisa no banco de dados
 *         [objeto.data.metodoFill] Nome do método a ser utilizado
 *  
 *   	   [objeto.idFill] ID da div que contém os inputs para serem populados dinâmicamente
 *  
 *         [objeto.callback.success] Função de callback
 *  
 *  @param [objeto] callback
 *  
 *  @return [Retorno_Metodo]
 *  
 *  @details [Detalhes_Metodo]
 *  
 *  @author Marcelo Serpa
 */
var fillData = function( params ){

	//console.log(dump(params));
	
	if(params.data.debug)
		debugParams(params);

	var json = "",
		element = "",
		url = '../transaction/'+params.data.transaction+'.php', 
		dataObject = {
			id: params.data.idTable,
			hdn_metodo_fill: params.data.metodoFill
		};
	
	$.ajax({
		url: url,
		async: false,
		type: 'POST',
		data: dataObject,
		success: function( response ){

			var inicioSeletor = "#", seletor;
			
			if( typeof( params.idFill ) != "undefined" ) {
				inicioSeletor += params.idFill + "_";
			}
						
			json = $.parseJSON(response);
			
			$.each(json, function (index, item) {
				
				seletor =  inicioSeletor + index;

				element = $( seletor );
				element.val(item);	
				
				if (!element.length > 0) {
					console.log('Elemento ' + index + " não foi encontrado...");
				}

				if( element.is("select")){
					if( element.attr("onchange") != undefined ){
						element.trigger("change");
					}
				}
				if( typeof(params.callback ) != "undefined" ){
					if( typeof(params.callback.success ) != "undefined" ){
						params.callback.success(json);
					}
				}

			});
		}

	});
	
}



/**
 *  Default
 *  mensagem = N
 *  status = N
 *  
 *  Obrigatorio
 *  prefixo
	tabela
	id_registro
	
	params.data.prefixo
	params.data.tabela
	params.data.id_registro
	params.data.mensagem
	params.data.status
	
	
	params.callback.success
	
 *  
 */	
var deletaAtivaRegistro = function( params ){

	if( confirm("Você realmente deseja excluir o registro selecionado?") ){
		
		//alert(dump(params));
		
		var mensagemErro = "";
		
		if( typeof( params.data.prefixo ) == "undefined" ) {
			mensagemErro += "\nAtributo prefixo obrigatorio";
		}	

		if( typeof( params.data.tabela ) == "undefined" ) {
			mensagemErro += "\nAtributo tabela obrigatorio";
		}	

		if( typeof( params.data.id_registro ) == "undefined" ) {
			mensagemErro += "\nAtributo id_registro obrigatorio";
		}			
		
		if( mensagemErro != "" ){
			
			alert( "Alguns erros ocorreram durante a solicitação\n\n" + mensagemErro );
		
		} else {
		
			if( typeof( params.data.mensagem ) == "undefined" ) {
				params.mensagem = 'N';
			}
			
			if( typeof( params.data.status ) == "undefined" ) {
				params.status = 'N';
			}		

			var url = "../twcore/teraware/php/crud.php";
			
			params.data['method'] = 'excluirAtivarRegistros';			
			
			$.ajax({
				url: url,
				async: true,
				type: 'GET',
				data: params.data,
				success: function( response ){					
				
					if( typeof( params.callback.success ) != "undefined" ){
					
						params.callback.success( response );
					
					} 
					criarToolTip();
				}
			});				
		
		}		
		
		
		/**
		var mensagemErro = "";
		
		if( typeof( params.prefixo ) == "undefined" ) {
			mensagemErro += "\nAtributo prefixo obrigatorio";
		}	

		if( typeof( params.tabela ) == "undefined" ) {
			mensagemErro += "\nAtributo tabela obrigatorio";
		}	

		if( typeof( params.id_registro ) == "undefined" ) {
			mensagemErro += "\nAtributo id_registro obrigatorio";
		}			
		
		if( mensagemErro != "" ){
			
			alert( "Alguns erros ocorreram durante a solicitação\n\n" + mensagemErro );
		
		} else {
		
			if( typeof( params.mensagem ) == "undefined" ) {
				params.mensagem = 'N';
			}
			
			if( typeof( params.status ) == "undefined" ) {
				params.status = 'N';
			}		

			var url = "../twcore/teraware/php/crud.php";
			
			params['method'] = 'deletaAtivaRegistro';			
			
			$.ajax({
				url: url,
				async: true,
				type: 'GET',
				data: params.data,
				success: function( response ){
					
					alert(dump(response));
				
					if( typeof( params.callbackSuccess ) != "undefined" ){
					
						params.callbackSuccess( response );
					
					} 
					criarToolTip();
				}
			});				
		
		}
*/		
	}
}


/**
 *  @brief Função para desabilitar os campos da tela de cadastro quando for uma consulta ou o registro estiver inativo
 *
 *  @param [objeto] params
 *         [objeto.metodo] Método utilizado (consultar, update, insert)
 *         [objeto.status] Status do registro (ativo = S ou inativo = N)
 *  
 *  @return Return_Description
 *
 *  @details [Detalhes_Metodo]
 *
 *  @author Marcelo Serpa
 */
var disableData = function( params ) {
	if(params.debug)
		debugParams(params);

	if((params.metodo != "") && (params.status != "")) {
		//Destrói todos os datepicker da tela
		$(".classdatepicker").datepicker("destroy");

		//Desabilita todos os campos
		$("input[type='text'").prop("disabled", "true");
		$("input[type='checkbox'").prop("disabled", "true");
		$("select").prop("disabled", "true");
		$("textarea").prop("disabled", "true");
		if((params.metodo == "consultar") || (params.status == "N"))
			$("#chkAtivo").attr('disabled', false);
	}
}

/**
 *  @brief Função para a criação dos tooltips das grids
 *  
 *  @return [Retorno_Metodo]
 *  
 *  @details [Detalhes_Metodo]
 *  
 *  @author Adriano Dias da Silva
 */
var criarToolTip = function( ) {
	$('.controles').each(function() {
        $(this).qtip({
            content: {
                text: $('#tooltiptext'+this.id),
                title: {
                    text: 'Controles',
                    button: true
                }
            },
            show:{
                event: 'click'
            },
            hide: {
                fixed: true,
                delay: 500
            },
            style: {
                classes: 'qtip-blue qtip-shadow'
            }
        });
    });
}

/**
 *  @brief Função para a criação excução do AJAX de grids filhas
 *  
 *  @param [objeto] params
 *         [objeto.async] Requisição assíncrona (true) ou síncrona (false)
 *         [objeto.type] Tipo de requisição GET (default) ou POST
 *         [objeto.transaction] Arquivo origem, onde será realizada a requisição
 *         [objeto.data.method] Método da requisição
 *         [objeto.data.idPai] ID do registro pai que será utilizado na consulta
 *         [objeto.divRetorno] Div que será retornado o conteúdo pesquisado
 *         [objeto.callbackSuccess] Função para manipulação do retorno Success
 *  
 *  @return [Retorno_Metodo]
 *  
 *  @details [Detalhes_Metodo]
 *  
 *  @author Adriano Dias da Silva
 */
var gerarGridFilha = function( params ) {
	if(params.debug) {
		debugParams(params);
		var url = "substitua_pela_url_raiz_do_servidor/transaction/"+params.transaction+"?method="+params.data.method+"&idPai="+params.data.idPai;
		prompt(url, url);
	}
	
	if( typeof( params.async ) == "undefined" ) {
		params.async = true;
	}
	
	if( typeof( params.type ) == "undefined" ) {
		params.type = 'GET';
	}
	
	
	var dataObject = {
			method: params.data.method,
			idPai: params.data.idPai
		}
		
	$.ajax({
		url: '../transaction/'+params.transaction,
		async: params.async,
		type: params.type,
		data: dataObject,
		success: function( response ){
			if( typeof( params.callbackSuccess ) != "undefined" ){
				params.callbackSuccess( response );
			} else {
				$( "#"+params.divRetorno ).html( response );
			}
			criarToolTip();
		}
	});
}



/**
 *  @brief Função para validação de elementos de formulario dentro de uma div
 *  
 *  @param [objeto] params
 *         [objeto.idDivFormulario] id da div na qual seus filhos devem ser validados
 *  
 *  @return void
 *  
 *  @details É necessario adicionar a classe "div-elemento-obrigatorio" em todos os elementos
 *  		 obrigatório da div em questão
 *  
 *  @author Marcelo Serpa
 */
function validarFormulario( params ){

	var idDivFormulario = params.idDivFormulario;

	var mensagemErro = "Erros ocorreram durante o envio de seu formulário.\n\nPor favor, faça as seguintes correções:\n",
		isValido = "S";

	idDivFormulario = "#" + idDivFormulario;
	
	$( idDivFormulario ).children('.div-elemento-obrigatorio').each(function(){
				
		if( $(this).is('select') || $(this).is('input') || $(this).is('textarea') ){

			if( $(this).val() == '' ){	

				isValido = "N";				
				if( $(this).prop('title') != '' ){
					mensagemErro += "\n* O campo " + $(this).prop('title') + " deve ser preenchido.";
				}
			} 		
		}
	});
	
	return ( isValido == "N" ) ? mensagemErro : "";

}

/**
 *  @brief Função para remover classes de erro de elementos de formulario dentro de uma div
 *  
 *  @param [objeto] params
 *         [objeto.idDivFormulario] id da div na qual seus filhos devem ser limpos
 *  
 *  @return void
 *  
 *  @details 
 *  
 *  @author Marcelo Serpa
 */
function removerErrosFormulario( params ){

	var classError = 'ui-state-error', 
		idDivFormulario = params.idDivFormulario;

	idDivFormulario = "#" + idDivFormulario;
	
	$( idDivFormulario ).children('.' + classError).each(function(){
		$(this).removeClass(classError);
	});
}

/**
 *  @brief Função para aplicar os valores default de cada elemento dentro de uma div
 *  
 *  @param [objeto] params
 *         [objeto.idDivFormulario] id da div na qual seus filhos devem voltar ao valores padrões
 *  
 *  @return void
 *  
 *  @details 
 *  
 *  @author Marcelo Serpa
 */
function aplicarCamposDefault( params ){

	idDivFormulario = "#" + params.idDivFormulario;
	
	$( idDivFormulario ).find('textarea').each(function(){
		$(this).val('');
	});

	$( idDivFormulario ).find('input[type=text]').each(function(){
		$(this).val('');
	});
	
	$( idDivFormulario ).find('input[type=password]').each(function(){
		$(this).val('');
	});	
	
	$( idDivFormulario ).find('select').each(function(){
		
		var possuiSelected = false;
		
		$(this).children('option').each(function(){
			
			if($(this).attr("selected") == "selected" ){
			
				possuiSelected = true;
				var optionSelected = $(this).val();
				
				$(this).parent().val(optionSelected);
			}
		});
		
		if( !possuiSelected ){
			$(this).val('');
		}

		if( $(this).attr("onchange") ){			
			$(this).change();
		}
		
	});
}


