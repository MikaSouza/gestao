<?php
ini_set('upload_max_filesize', '10M');
header('Content-Type: application/json');
// print_r($_POST);
$file = $_FILES['file'];

$ret = [];

$diretorio = '../ged/orientacao_tecnica/'.$_POST['vIOXTCODIGO'];
// echo $diretorio;
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
} else {
    chmod($diretorio, 0755);
}
include_once __DIR__.'/transaction/transactionOrientacaoTecnicaxGED.php';
$nomeArquivo = removerAcentoEspacoCaracter($file['name']);
$file['name'] = $nomeArquivo;
if (move_uploaded_file($file['tmp_name'], $diretorio.'/'.$file['name'])) {
    $ret["status"] = 200;
    $ret["path"] = $diretorio.'/'. $file['name'];
    $ret["name"] = $file['name'];
  //  $nomeArquivo = removerAcentoEspacoCaracter($file['name']);
    // inserir banco de dados 
    $dadosBanco = array(
        'vIGEDCODIGO'   => '',
        'vSGEDNOMEARQUIVO' 	=> $nomeArquivo,
        'vSGEDDIRETORIO'  => $diretorio,
        'vIGEDVINCULO'    => $_POST['vIOXTCODIGO'],
        'vIMENCODIGO'    => $_POST['vHMENCODIGO'],
        'vSGEDTIPO'     => $file['type'],
        'vIGEDTAMANHO'  => $file['size']
        );
    insertUpdateOrientacaoTecnicaxGED($dadosBanco, 'N');
} else {
    $ret["status"] = "error";
    $ret["name"] = $file['name'];
}

echo json_encode($ret, JSON_PRETTY_PRINT);