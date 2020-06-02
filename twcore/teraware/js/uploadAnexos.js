/**
 *  @file uploadAnexos.js
 *  @brief Funções padrões para realização dos uploads de arquivos no sistema 
 */


/**
 *  @brief [ Função para realizar upload de arquivos para o servidor]
 *  
 *  @param [objeto] params 
 *  				[params.element] Elemento onde o anexo irá ser setado
 *  				[params.debugFineUploader] Modo debug para teste
 *  				[params.multiple] Permitir multiplos arquivos
 *  				[params.autoUpload] Permitir uploads de arquivos automaticos
 *  				[params.request.endpoint] Url do arquivo PHP para manipular a requisição
 *  				[params.request.endpointParams] Parametros que serão enviados para o arquivo PHP de manipulação
 *  @param [objeto] callback [ objeto contendo os manipuladores de eventos da biblioteca FineUpload ]
 *  @return [void]
 *  
 *  @details [Detalhes_Metodo]
 *  
 *  @author [Marcelo Serpa]
 */
var uploadAnexo = function( params, callback ){

	var mensagem_erro = "";
	

	var element = $("#div-fineUploader-anexo")[0],
		debugFineUploader	= true,
		multiple = true;
		autoUpload = true;
		request_endpoint = '../twcore/vendors/fineUploader/fineUploaderHandler.php';
		request_params = {
			pSDirTemp :   '../../../../erp/arquivos/temp/',
			pSDirUpload : '../../../../erp/anexosAtendimento/'
		},
		callback_default = {
		
			onComplete: function(id, fileName, responseJSON) {
				if( responseJSON.success ){								
					alert('salvo com sucesso.');								
				}
			}
	
		}
		
	if( typeof( callback) != "undefined" ){
		callback_default = callback;
	}
		
	if (typeof( params) != "undefined" && params != '' && params != null ){
	
		if (typeof( params.element ) != "undefined"){
			element = params.element;
		}
		
		if (typeof( params.debugFineUploader ) != "undefined"){
			debugFineUploader = params.debugFineUploader;
		}

		if (typeof( params.multiple ) != "undefined"){
			multiple = params.multiple;
		}
		
		if (typeof( params.autoUpload ) != "undefined"){
			autoUpload = params.autoUpload;
		}

		if (typeof( params.request ) == "undefined"){
		
			mensagem_erro += "request não definido!\n";
			
		} else {
			
			if (typeof( params.request.endpoint ) != "undefined"){
				request_endpoint = params.request.endpoint;
			} else {
				mensagem_erro += "endpoint não definido!\n";
			}
			
			if (typeof( params.request.endpointParams ) != "undefined"){
				request_params = params.request.endpointParams;
				
				if(	typeof( params.request.endpointParams.pSDirTemp ) == "undefined" || typeof( params.request.endpointParams.pSDirUpload ) == "undefined" ){
					mensagem_erro += "diretorio de upload e temporario não informado !\n";
				}
				
			}else{
				mensagem_erro += "endpointParams não definido!\n";
			}					
			
		}	
	} else {
	
		mensagem_erro += "É necessario informa o endpoint e endpointParams\n";
	}
	if( !mensagem_erro ){
		var fineUploaderSistema = new qq.FineUploader({
			element: element,
			debug: debugFineUploader,
			multiple: multiple,
			autoUpload: autoUpload,
			request:{
				endpoint: request_endpoint,
				params: request_params
			},
			callbacks: callback_default

		});
	} else {
		mensagem_erro = "Problema para configurar o uploadAnexos\n\n" + mensagem_erro;
		alert( mensagem_erro );
	}
}
