<?php
session_start();
require_once '../../../include/constantes.php';
require_once '../../../include/funcoes.php';
require_once '../../../include/funcoesBanco.php';

if( isset($_GET) ){

	if( $_GET['method'] == 'criarZIPCadaArquivo' ){	
	
		criarZIPCadaArquivo( $_GET['arquivos_finais'], true, $_GET['bytesUsados'] );	
		
	} else if ( $_GET['method'] == 'criarZIPTodosArquivos' ){
	
		criarZIPTodosArquivos( $_GET['arquivos_finais'], $_GET['nomeZIP'], true, $_GET['bytesUsados'] );	
		
	}  else if ( $_GET['method'] == 'adicionarArquivosZIP' ){
		
		adicionarArquivosZIP( $_GET['arquivos_finais'], $_GET['zip_diretorio'], true, $_GET['bytesUsados'] );
	
	} else {
		print_r($_GET);
	}

}
/**
 *  $arquivos(
 *  	'caminho_arquivo_servidor' => '....',
 *  	'nome_arquivo' => ''
 *  )
 *  
 */

function criarZIPCadaArquivo( $arquivos, $excluirOriginais = true, $bytesUsados){ 

	$arquivos_concluidos = array();
	
	$zip_nome = '';
	$zip_dir = '';
	$temp;

	if( isTamanhoAnexoPorAtendimentoPermitido( $bytesUsados, $arquivos ) == 'N' ){
	
		$limite_MB = 1;
		if($_SESSION["SI_USU_EMPRESA"] == 1){
			$limite_MB = 10;
		}
		
		//if(getParametroValor('CMV') == $_SESSION['SI_USU_EMPRESA']){
		//if(verificarParametro($_SESSION['SI_USU_EMPRESA'], "CMV") > 0) {
		if($_SESSION['SI_USU_EMPRESA'] == 47) {
			$limite_MB = 5;
		}
	
		array_push( $arquivos_concluidos , array(
			'erro' => 'S',
			'erro_msg' => 'Seu atendimento possui mais de '.$limite_MB.' MB em anexos!',
		));
		
	} else {
	
		if( is_array( $arquivos ) ){
			
			foreach( $arquivos as $arquivo ){
			
				$temp = explode(".", $arquivo['caminho_arquivo_servidor']);
				array_pop($temp);			
				$temp = implode(".", $temp);			
				$zip_dir =  $temp. ".zip";
				
				$zip = new ZipArchive();
				
				$opened = $zip->open( $zip_dir , ZipArchive::CREATE );		
				
				if(file_exists( $arquivo['caminho_arquivo_servidor'] )){
					$zip->addFile( $arquivo['caminho_arquivo_servidor'], $arquivo['nome_arquivo'] );
				}		
				$zip->close();

				if( $excluirOriginais && file_exists( $arquivo['caminho_arquivo_servidor'] ) ){				
					unlink ( $arquivo['caminho_arquivo_servidor'] );
				}

				$temp = explode(".", $arquivo['nome_arquivo']);
				array_pop($temp);			
				$temp = implode(".", $temp);			
				$zip_nome =  $temp. ".zip";
				
				array_push( $arquivos_concluidos , array(
					'arquivo_zip_nome' => $zip_nome,
					'arquivo_zip_nome_servidor' => array_pop(explode("/", $zip_dir)),
					'arquivo_zip_caminho' => $zip_dir,				
					'arquivo_zip_tamanho' => filesize( $zip_dir ),
				));
			}
			
		}	
	
	}
		
	echo json_encode( $arquivos_concluidos );
	
}

function criarZIPTodosArquivos( $arquivos, $nomeZIP, $excluirOriginais = true, $bytesUsados  ){ 

	$retorno = array(
		'arquivo_zip_nome' => '',		
		'arquivo_zip_caminho' => '',		
		'arquivo_zip_tamanho' => '',
		'erro' => 'N'
	);
	$erro = false;
	
	if( isTamanhoAnexoPorAtendimentoPermitido( $bytesUsados, $arquivos ) == 'N' ){
	
		$limite_MB = 1;
		if($_SESSION["SI_USU_EMPRESA"] == 1){
			$limite_MB = 10;
		}
		//if(getParametroValor('CMV') == $_SESSION['SI_USU_EMPRESA']){
		//if(verificarParametro($_SESSION['SI_USU_EMPRESA'], "CMV") > 0) {
		if($_SESSION['SI_USU_EMPRESA'] == 47) {
			$limite_MB = 5;
		}
	
		$erro = true;
		$retorno['erro'] = 'S';
		$retorno['erro_msg'] = 'Seu atendimento possui mais de '.$limite_MB.' MB em anexos!';
		
	} else {
	
		$nomeSemAcentuacao = removerAcentoEspacoCaracter($nomeZIP);
		
		$nomeZIPFinal = str_replace(" ", "_", trim(mt_rand().'_'.$nomeSemAcentuacao));
			
		//$dir_base = $arquivos[0]['arquivo_zip_nome'];
		$dir_base = "";
		$temp = explode("/", $arquivos[0]['caminho_arquivo_servidor']);
		array_pop($temp);
		
		$dir_base = implode("/", $temp);	
		$diretorio_destino = $dir_base . '/' .$nomeZIPFinal . ".zip";
		
		$total_arquivos = count($arquivos);
		
		if( isTamanhoFinalValido( $arquivos )){
			
			$zip = new ZipArchive();		
			$opened = $zip->open( $diretorio_destino , ZipArchive::CREATE );
			
			if( $opened === true ){

				for( $i = 0; $i < $total_arquivos; $i++ ) {		
					if( file_exists( $arquivos[$i]['caminho_arquivo_servidor'] ) ){
						$zip->addFile( $arquivos[$i]['caminho_arquivo_servidor'], $arquivos[$i]['nome_arquivo'] );
					}
				}		
				$zip->close();
				$opened = null;
			}	

		} else {
			$erro = true;
			$retorno['erro'] = 'S';	
			$retorno['erro_msg'] = "Tamanho dos arquivos é superior ao tamanho limite!";
		
		}

		for( $i = 0; $i < $total_arquivos; $i++ ) {

			if( $excluirOriginais && file_exists( $arquivos[$i]['caminho_arquivo_servidor'] ) ){
				unlink ( $arquivos[$i]['caminho_arquivo_servidor'] );
			}
		}
	}
	if(!$erro){
	
		$retorno['arquivo_zip_nome'] = $nomeSemAcentuacao . ".zip";
		$retorno['arquivo_zip_caminho'] = $nomeZIPFinal . '.zip';
		$retorno['arquivo_zip_tamanho'] = filesize( $diretorio_destino );
		$retorno['erro'] = 'N';
	}

		
	//print_r($retorno)	;
		
	echo json_encode( $retorno );	
	
}

function adicionarArquivosZIP( $arquivos, $zip_diretorio, $excluirOriginais = true, $bytesUsados ){

	
	$retorno = array(
		'arquivo_zip_tamanho' => '',		
		'erro' => 'N'
	);
	
	
	if( isTamanhoAnexoPorAtendimentoPermitido( $bytesUsados, $arquivos ) == 'N' ){
		$limite_MB = 1;
		if($_SESSION["SI_USU_EMPRESA"] == 1){
			$limite_MB = 10;
		}
		//if(getParametroValor('CMV') == $_SESSION['SI_USU_EMPRESA']){
		//if(verificarParametro($_SESSION['SI_USU_EMPRESA'], "CMV") > 0) {
		if($_SESSION['SI_USU_EMPRESA'] == 47) {
			$limite_MB = 5;
		}
		
		$erro = true;
		$retorno['erro'] = 'S';
		$retorno['erro_msg'] = 'Seu atendimento possui mais de '.$limite_MB.' MB em anexos!';		
		
	} else {	
	
		if( file_exists($zip_diretorio) ){

			$total_arquivos = count($arquivos);
			
			if( isTamanhoFinalValido( $arquivos, 2, $zip_diretorio )){
		
				$zip = new ZipArchive();
			
				$opened = $zip->open( $zip_diretorio );
							
				if( $opened === true ){
				
					for( $i = 0; $i < $total_arquivos; $i++ ) {		
						$zip->addFile( $arquivos[$i]['caminho_arquivo_servidor'], $arquivos[$i]['nome_arquivo'] );
					}		
					$zip->close();
					$opened = null;
				}
		
			} else {
				
				$retorno['erro'] = 'S';
				$retorno['erro_msg'] = 'Tamanho dos arquivos é maior que o limite!';
			
			}
			
			for( $i = 0; $i < $total_arquivos; $i++ ) {

				if( $excluirOriginais && file_exists( $arquivos[$i]['caminho_arquivo_servidor'] ) ){
					unlink ( $arquivos[$i]['caminho_arquivo_servidor'] );
				}
			}	

			if( $retorno['erro'] == 'N'){
				$retorno['arquivo_zip_tamanho'] = filesize( $zip_diretorio );
			}
			
		} else {
		
			$retorno['erro_msg'] = $zip_diretorio . " - não existe!";
			$retorno['erro'] = 'S';
		
		}
	}
	echo json_encode( $retorno );

}

function isTamanhoFinalValido(  $arquivos, $tamanhoLimitMB = 2, $zip_diretorio = null ){

	if($_SESSION["SI_USU_EMPRESA"] == 1){
		$tamanhoLimitMB = 10;
	}
	//if(getParametroValor('CMV') == $_SESSION['SI_USU_EMPRESA']){
	//if(verificarParametro($_SESSION['SI_USU_EMPRESA'], "CMV") > 0) {
	if($_SESSION['SI_USU_EMPRESA'] == 47) {
		$tamanhoLimitMB = 5;
	}

	$tamanho_limite_bytes = $tamanhoLimitMB * 1024 * 1024;
	$tamanho_arquivo_final = 0;
	$total_arquivos = count($arquivos);
	
	if( $zip_diretorio != null ){
		$tamanho_arquivo_final += filesize( $zip_diretorio );
	}
	
	for( $i = 0; $i < $total_arquivos; $i++ ){

		if(file_exists($arquivos[$i]['caminho_arquivo_servidor'])){
			//echo 'arquivo' . $arquivos[i]['caminho_arquivo_servidor'] . ' -- tamanho: ' . filesize( $arquivos[i]['caminho_arquivo_servidor'] ) . '\n';
			$tamanho_arquivo_final += filesize( $arquivos[$i]['caminho_arquivo_servidor'] );
		} 
	}
	
	return ( $tamanho_arquivo_final <= $tamanho_limite_bytes ) ? true : false;

}

function isTamanhoAnexoPorAtendimentoPermitido( $bytesUsados, $arquivos, $limitMBPorAtendimento = 2 ){
 	
	if($_SESSION["SI_USU_EMPRESA"] == 1){
		$limitMBPorAtendimento = 10;
	}
	//if(getParametroValor('CMV') == $_SESSION['SI_USU_EMPRESA']){
	//if(verificarParametro($_SESSION['SI_USU_EMPRESA'], "CMV") > 0) {
	if($_SESSION['SI_USU_EMPRESA'] == 47) {
		$limitMBPorAtendimento = 5;
	}

	$limitePorAtendimento = $limitMBPorAtendimento * 1024 * 1024;
	
	$isValido = 'S';
	
	foreach( $arquivos as $arquivo ){
		if( file_exists( $arquivo['caminho_arquivo_servidor'] ) ){				
			$bytesUsados += filesize( $arquivo['caminho_arquivo_servidor'] );
			
			if( $bytesUsados > $limitePorAtendimento ){
				break;
			}
		}
	}

	if( $bytesUsados > $limitePorAtendimento ){
		
		$isValido = 'N';
		
		foreach( $arquivos as $arquivo ){
			if( file_exists( $arquivo['caminho_arquivo_servidor'] ) ){				
				unlink ( $arquivo['caminho_arquivo_servidor'] );
			}
		}	
	}	
	
	return $isValido;

}

function is_url_exist($url){
	$ch = curl_init($url);    
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($code == 200){
	   $status = true;
	}else{
	  $status = false;
	}
	curl_close($ch);
   return $status;
}

function getFileSizeRemote( $url ){
  //= curl_init('http://www.google.co.in/images/srpr/logo4w.png');

	$url = curl_init($url);
		
	curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($url, CURLOPT_HEADER, TRUE);
	curl_setopt($url, CURLOPT_NOBODY, TRUE);

	$data = curl_exec($url);
	$size = curl_getinfo($url, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

	curl_close($url);

	return $size;

}


