<?php

if(isset($_GET["pSFileName"]))
    $vSFileName = $_GET["pSFileName"];
else
    $vSFileName = "prefixo_nao_definido";

if(isset($_GET["pSDirTemp"]))
    $vSDirTemp = $_GET["pSDirTemp"];
else
    $vSDirTemp = "temp_upload";

if(isset($_GET["pSDirUpload"]))
    $vSDirUpload = $_GET["pSDirUpload"];
else
    $vSDirUpload = "uploads";

// Classe global do ajax_upload
require_once '../ajax_upload/qqFileUploader.php';
require_once '../include/funcoes.php';

$uploader = new qqFileUploader();

// Extensões permitidas, ex.: array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array();

// Tamanho máximo do arquivo de upload (em bytes)
$uploader->sizeLimit = 2 * 1024 * 1024;

// Nome da input do javascript
$uploader->inputName = 'qqfile';

// Se for utulizado o upload em partes (quando envia o arquivo e depois manda salvar), defina o diretório temporário
//$uploader->chunksFolder = 'temp_upload';
$uploader->chunksFolder = $vSDirTemp;

// Diretório do upload
//$result = $uploader->handleUpload('uploads');
//$result = $uploader->handleUpload($vSDirUpload);

// Para salvar o arquivo com nome específico, deve ser definido o segundo parâmetro, conforme abaixo
// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());
$result = $uploader->handleUpload($vSDirUpload, str_replace(" ", "_", trim($vSFileName.'_'.mt_rand().'_'.removerAcentoEspacoCaracter($uploader->getName()))));

// Retorna o nome do arquivo após o upload para a variável $result['uploadName']
$result['uploadName'] = $uploader->getUploadName();
$result['uploadDiretorio'] = $vSDirUpload;

header("Content-Type: text/plain");
echo json_encode($result);