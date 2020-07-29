<?php
ini_set('upload_max_filesize', '10M');
header('Content-Type: application/json');
print_r($_POST);
$file = $_FILES['file'];

$ret = [];

$diretorio = 'uploads/'.$_POST['vSATITOKEN'];
echo $diretorio;
if (!is_dir($diretorio)) {
	mkdir($diretorio, 0755, true);
} else {
	chmod($diretorio, 0755);
}
include_once __DIR__.'/transaction/transactionAtividadesxAnexos.php';
if(move_uploaded_file($file['tmp_name'],$diretorio.'/'.$file['name'])){
    $ret["status"] = "success";
    $ret["path"] = $diretorio.'/'. $file['name'];
    $ret["name"] = $file['name'];
	
	// inserir banco de dados	
	$dadosBanco = array(
		'vIAXACODIGO'   => '',
		'vSAXANOME' 	=> $file['name'],
		'vSAXACAMINHO'  => $diretorio,
		'vSATITOKEN'    => $_POST['vSATITOKEN'],
		'vSAXATIPO'     => $file['type'],
		'vIAXATAMANHO'  => $file['size'] 
		);	
	insertUpdateAtividadesxAnexos($dadosBanco, 'N');
}else{
    $ret["status"] = "error";
    $ret["name"] = $file['name']; 
}

echo json_encode($ret, JSON_PRETTY_PRINT);
?>