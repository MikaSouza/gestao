<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../twcore/vendors/mpdf/mpdf.php';

if (($_SERVER["REQUEST_METHOD"] == "GET") && ($_GET["oid"] != "")) {
	$vIDOPCODIGO = $_GET["oid"];
	$vConexao = sql_conectar_banco();

	///////////////////////
	// CONTRATOS PADRÕES //
	///////////////////////

	$vSSql = "SELECT
					DOPDESCRICAO
				FROM
					DOCUMENTOSPADROES
				WHERE
					DOPSTATUS = 'S'
				AND
					DOPCODIGO = ".$vIDOPCODIGO;

	$vSSql = stripcslashes($vSSql);
	$vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);

	while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
		$vSDOPDESCRICAO = $vSResultLinha['DOPDESCRICAO'];
	}
	//if(empty($vSDOPDESCRICAO))
		//header("Location: main.php");

	////////////////////
	// CONFIGURA HTML //
	////////////////////

	$vSHtmlDefault = array();
	$vSHtmlDefault["body"] = $vSDOPDESCRICAO;		
	$vIMargemTopo = 10;
	$vSHtmlDefault["header"] = '';
	
	sql_fechar_conexao_banco($vConexao);

	/////////
	// PDF //
	/////////

	// mode, format, default_font_size, default_font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer
	$mpdf = new mPDF("c", "A4", "", "", 10, 10, $vIMargemTopo, 10, 10, 10);

	//DEFINE O CABEÇALHO
	$mpdf->SetHTMLHeader($vSHtmlDefault["header"]);

	$mpdf->SetDisplayMode("fullpage");

	$mpdf->list_indent_first_level = 0;

	//Carrega css padrão PDF
	//$stylesheet = file_get_contents("../css/mpdf.css");
	$mpdf->WriteHTML($stylesheet, 1);

	$mpdf->WriteHTML($vSHtmlDefault["body"], 2);

	$mpdf->Output("contratoPadrao.pdf", "I");

} else {
	header("Location: main.php");
}
?>