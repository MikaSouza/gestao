<?php
ini_set('upload_max_filesize', '10M');
header('Content-Type: application/json');
print_r($_POST);
$file = $_FILES['file'];

$ret = [];

$diretorio = '../ged/contratos/'.$_POST['vICTRCODIGO'];
echo $diretorio;
if (!is_dir($diretorio)) {
	mkdir($diretorio, 0755, true);
} else {
	chmod($diretorio, 0755);
}
include_once __DIR__.'/transaction/transactionContratosxGED.php';
if(move_uploaded_file($file['tmp_name'],$diretorio.'/'.$file['name'])){
    $ret["status"] = "success";
    $ret["path"] = $diretorio.'/'. $file['name'];
    $ret["name"] = $file['name'];
	$nomeArquivo = removerAcentoEspacoCaracter($file['name']);
	// inserir banco de dados	
	$dadosBanco = array(
		'vIGEDCODIGO'   => '',
		'vSGEDNOMEARQUIVO' 	=> $nomeArquivo,
		'vSGEDDIRETORIO'  => $diretorio,
		'vIGEDVINCULO'    => $_POST['vICTRCODIGO'],
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