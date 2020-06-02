<?php
/**

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


function getUploder(){

	include_once('qqFileUploader.php');
	
	
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
	
}
*/


if(isset($_GET["pSDirTemp"]))
    $vSDirTemp = $_GET["pSDirTemp"];
else
    $vSDirTemp = "temp_upload";

if(isset($_GET["pSDirUpload"]))
    $vSDirUpload = $_GET["pSDirUpload"];
else
    $vSDirUpload = "uploads";
	
	
//$vSDirUpload =  DIR_ROOT_ABSL . "anexosAtendimento/";

// Classe global do ajax_upload
require_once 'qqFileUploader.php';
require_once '../../../include/constantes.php';
require_once '../../../include/funcoes.php';
require_once '../../../include/funcoesBanco.php';

$uploader = new qqFileUploader();

// Extensões permitidas, ex.: array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array();

// Tamanho máximo do arquivo de upload (em bytes)
$limit = 2;
														
if($_SESSION['SI_USU_EMPRESA'] == 1 || $_SESSION['SI_USU_EMPRESA'] == 47){
	$limit = 5;
}
$uploader->sizeLimit = $limit * 1024 * 1024;

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
$arquivoNovo = str_replace(" ", "_", trim(mt_rand().'_'.removerAcentoEspacoCaracter($uploader->getName())));
$result = $uploader->handleUpload($vSDirUpload, $arquivoNovo);

// Retorna o nome do arquivo após o upload para a variável $result['uploadName']
$result['uploadName'] = $uploader->getUploadName();
$result['uploadDiretorio'] = $vSDirUpload;

//Novos itens
$result['uploadArquivoFinal'] = $vSDirUpload . $arquivoNovo;

header("Content-Type: text/plain");
echo json_encode($result);
