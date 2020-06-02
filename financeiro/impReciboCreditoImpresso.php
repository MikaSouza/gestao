<?php

error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once 'transaction/transactionContasReceber.php';
include_once '../twcore/vendors/mpdf/mpdf.php';

//if (($_SERVER["REQUEST_METHOD"] == "GET") && !empty($_GET["vICXPCODIGO"]) ) {

	$vICTRCODIGO = filter_input(INPUT_GET, 'vICXPCODIGO', FILTER_SANITIZE_NUMBER_INT);
	$vICXPCODIGO = filter_input(INPUT_GET, 'oid_parcela', FILTER_SANITIZE_NUMBER_INT);$_GET[""];

	$vSHtmlDefault = array();

	$vSHtmlDefault["header"] = '';

	$vSHtmlDefault["body"] = '';

	if ($vICXPCODIGO > 0){
		$registros = getDadosRecibo($vICTRCODIGO, $vICXPCODIGO);
	} else {
		$registros = getDadosRecibo($vICTRCODIGO);
	}

	foreach ($registros as $registro) {
		$vSCLIENTE           = $registro['CLINOME'];
		$vSCTRDESCRICAO      = $registro['CTRDESCRICAO'];
		$vSCTRVALORCOBERTURA = $registro['VALOR_RECEBER']." (".valorPorExtenso($registro['CTRVALORARECEBER']).")";
		$vDVENCIMENTO        = $registro['DATAS_VENCIMENTO'];
	}

	$vSHtmlDefault["body"] .= "<div style='width: 714px; height: 475px;font-size:20px'>
			<div style='clear: both;height:30px'></div>
			<div style='float:left; padding-left: 480px'><b>&nbsp;&nbsp;</b></div>
			<div style='clear: both;height:25px;'></div>
			<div style='float:left; padding-left: 50px'><b>{$vSCLIENTE}</b></div>
			<div style='clear: both;height:75px;'></div>
			<div style='float:left; padding-left: 50px; text-align:justify; height: 52px; width:625px; white-space:normal;'>
			{$vSCTRVALORCOBERTURA}</div>
			<div style='clear: both;height:38px'></div>
			<div style='float:left; padding-left:25px; text-align:justify; width:665px; height: 80px; white-space:normal; font-size:12px'>{$vSCTRDESCRICAO}<br /><br />VENCIMENTO: {$vDVENCIMENTO}</div>
			<div style='clear: both;height:50px'></div>
			<div style='float:left; padding-left:65px;'>".date('d/m/Y')."</div>
			<div style='clear: both;height:43px'></div>
			<div style='float:left; padding-left:362px; font-size:10px'>Impresso por: {$_SESSION[SS_NOME_USUARIO]}</div>
		</div>";

	$mpdf = new mPDF("c", "A4", "", "helvetica", 0, 10, 0, 15, 0, 8);
    //DEFINE O CABEÇALHO
    $mpdf->SetHTMLHeader($vSHtmlDefault["header"]);
	$mpdf->SetHTMLFooter($vSRodape);
    $mpdf->SetDisplayMode("fullpage");

    $mpdf->list_indent_first_level = 0;

    //Carrega css padrão PDF
    $stylesheet = file_get_contents("../assets/css/mpdf.css");
    $mpdf->WriteHTML($stylesheet, 1);

    $mpdf->WriteHTML($vSHtmlDefault["body"], 2);

	$mpdf->Output("ReciboCredito.pdf", "I");
/*
} else {
    header("Location: main.php");
}*/