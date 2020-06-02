<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';

// funcoes da Teraware para manipulação da NFe
include_once('include/funcoesNFSe.php');

if ($_GET['method'] == 'enviarNFSeLote') {
	if (isset($_GET['pLOids'])) {		
		gerarNFSeXMLEnvioLote($_GET['pLOids']);
		
		// gravar xml
		//echo $vSErro = gerarNFSeXMLEnvio($_POST['pINSECODIGO']);
		//enviar para outro servidor
		//ftpArquivo($vSRetornoNomeArquivo);
		// verificar resultado
		//verificarArquivoRetorno($vSRetornoNomeArquivo);
	}
}

if($_POST['method'] == "enviarEmailNFSe"){
	if(isset($_POST['pLNSECODIGO'])){				
		echo $vSErro = enviarEmailNFSe($_POST['pLNSECODIGO'], 'E');
		return;			
	}
}

if($_POST['method'] == "cancelarNFSe"){
	if(isset($_POST['pINFSCODIGO'])){				
		echo $vSErro = cancelarNFSe($_POST['pINFSCODIGO'], $_POST['pSNFSNRORECEITA'], $_POST['pIEMPCODIGO'], $_POST['pINSENUMERORPS']);  
		return;			
	}
}

if($_GET['method'] == "downloadArquivosNFe"){
	if(isset($_GET['pLNFSCODIGO'])){
		$vSNomeArquivoZip = downloadArquivosNFSe($_GET['pLNFSCODIGO']);
		include_once('../include/exportarZIP.php');
		return;
	}
}

return;

return;
if ($_POST['method'] == 'enviarNFSe') {
	if (isset($_POST['pINSECODIGO'])) {
		// verificar se já foi enviado
		
		// gravar xml
		echo $vSErro = gerarNFSeXMLEnvio($_POST['pINSECODIGO']);
		//enviar para outro servidor
		//ftpArquivo($vSRetornoNomeArquivo);
		// verificar resultado
		//verificarArquivoRetorno($vSRetornoNomeArquivo);
	}
}
return;

?>