
var criarZIPCadaArquivo = function( arquivos, tamanhoByteUsados ){

	var url = '../twcore/teraware/php/compactadorZIP.php';
	
	var objretorno;
	
	$.ajax({
		url: url,
		type: 'GET',
		async: false,
		dataType: 'json',
		data: { 
			arquivos_finais: arquivos,
			method: 'criarZIPCadaArquivo',
			bytesUsados : tamanhoByteUsados
		}, 
		success: function(response){	
			objretorno = response;
		}
	
	});
	return objretorno;
}

var criarZIPTodosArquivo = function( arquivos, nomeZIP, tamanhoByteUsados ){

	var url = '../twcore/teraware/php/compactadorZIP.php', 
		objretorno;
	
	$.ajax({
		url: url,
		type: 'GET',
		dataType: 'json',
		async: false,
		data: { 
			arquivos_finais: arquivos,
			nomeZIP: nomeZIP,
			method: 'criarZIPTodosArquivos',
			bytesUsados : tamanhoByteUsados
		}, 
		success: function(response){
			objretorno =  response;
		}
	
	});
	return objretorno;
}

var adicionarArquivosEmZip = function( arquivos, zip_diretorio, tamanhoByteUsados ){

	var url = '../twcore/teraware/php/compactadorZIP.php', 
		objretorno,
		dataObj = { 
			arquivos_finais: arquivos,
			zip_diretorio: zip_diretorio,
			method: 'adicionarArquivosZIP',
			bytesUsados : tamanhoByteUsados
		};		
	//prompt("99", url + "?" + $.param(dataObj));
	
	$.ajax({
		url: url,
		type: 'GET',
		async: false,
		dataType: 'json',
		data: dataObj, 
		success: function(response){
			objretorno =  response;
		}
	
	});
	return objretorno;
}
