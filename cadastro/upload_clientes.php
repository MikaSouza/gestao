<?php
ini_set('upload_max_filesize', '10M');
header('Content-Type: application/json');
print_r($_POST);
$file = $_FILES['file'];

$ret = [];

$diretorio = '../ged/clientes/'.$_POST['vICLICODIGO'];
echo $diretorio;
if (!is_dir($diretorio)) {
	mkdir($diretorio, 0755, true);
} else {
	chmod($diretorio, 0755);
}
include_once __DIR__.'/transaction/transactionClientesxGED.php';
$nomeArquivo = removerAcentoEspacoCaracter($file['name']);
if(move_uploaded_file($file['tmp_name'],$diretorio.'/'.$nomeArquivo)){
    $ret["status"] = "success";
    $ret["path"] = $diretorio.'/'. $file['name'];
    $ret["name"] = $file['name'];
	
	// inserir banco de dados	
	$dadosBanco = array(
		'vIGEDCODIGO'   => '',
		'vSGEDNOMEARQUIVO' 	=> $nomeArquivo,
		'vSGEDDIRETORIO'  => $diretorio,
		'vIGEDVINCULO'    => $_POST['vICLICODIGO'],
		'vIMENCODIGO'    => $_POST['vHMENCODIGO'],
		'vSGEDTIPO'     => $file['type'],		
		'vIGEDTAMANHO'  => $file['size'] 
		);	
	insertUpdateClientesxGED($dadosBanco, 'N');
}else{
    $ret["status"] = "error";
    $ret["name"] = $file['name']; 
}

echo json_encode($ret, JSON_PRETTY_PRINT);
?>