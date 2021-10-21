<?php
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../twcore/vendors/mpdf/mpdf.php';
include_once __DIR__.'/transaction/transactionAgendaGenerica.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';

if ($_GET['vIMES'] == '')
	$vIMES = date('m');
else 
	$vIMES = $_GET['vIMES'];
if ($_GET['vIANO'] == '')
	$vIANO = date('Y');
else 
	$vIANO = $_GET['vIANO'];
if ($_GET['vICLIRESPONSAVEL'] == '')
	$vICLIRESPONSAVEL = '';
else 
	$vICLIRESPONSAVEL = $_GET['vICLIRESPONSAVEL'];

$vIMargemTopo = 30;
$vSHtmlDefault["header"] = "<table border='0' style='border-collapse: collapse;' width='100%'> 
								<tr>
									<th align='left'>
										<img src='../assets/images/novo_logo.png' width='215px;' />
									</th>
								</tr>
							</table><hr>";


$vSCOPDESCRICAO .= '<table width="100%" border="1" align="center" cellpadding="6" cellspacing="1" style="background-color:#D3D3D3">
	<tr class="bg-cinza text-center"> 
		<td nowrap="nowrap" class="bg-cinza" colspan="3" align="center">CONTROLE DOS MUNICÍPIOS ATENDIDOS</td>
	</tr>
	<tr class="bg-cinza text-center"> 
		<td nowrap="nowrap" class="bg-cinza" colspan="3" align="center">JUNHO DE 2021</td>
	</tr>';
	
	
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color" colspan="2">AGUDO</td></tr>
		<tr style="background-color:#E6E6FA">
		<td class="base-color" align="center">01/06/21</td>
		<td class="base-color" align="left">Encaminhamento da Orientação Técnica nº 37/2021, que aborda alguns
comentários e recente decisão do TCU sobre atestados de capacidade técnica e a sua
aceitação em processos licitatórios.</td>
		</tr>';
		$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color" colspan="2">AGUDO</td></tr>
		<tr style="background-color:#E6E6FA">
		<td class="base-color" align="center">01/06/21</td>
		<td class="base-color" align="left">Encaminhamento da Orientação Técnica nº 37/2021, que aborda alguns
comentários e recente decisão do TCU sobre atestados de capacidade técnica e a sua
aceitação em processos licitatórios.</td>
		</tr>';
		$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color" colspan="2">AGUDO</td></tr>
		<tr style="background-color:#E6E6FA">
		<td class="base-color" align="center">01/06/21</td>
		<td class="base-color" align="left">Encaminhamento da Orientação Técnica nº 37/2021, que aborda alguns
comentários e recente decisão do TCU sobre atestados de capacidade técnica e a sua
aceitação em processos licitatórios.</td>
		</tr>';
		$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color" colspan="2">AGUDO</td></tr>
		<tr style="background-color:#E6E6FA">
		<td class="base-color" align="center">01/06/21</td>
		<td class="base-color" align="left">Encaminhamento da Orientação Técnica nº 37/2021, que aborda alguns
comentários e recente decisão do TCU sobre atestados de capacidade técnica e a sua
aceitação em processos licitatórios.</td>
		</tr>';
		$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color" colspan="2">AGUDO</td></tr>
		<tr style="background-color:#E6E6FA">
		<td class="base-color" align="center">01/06/21</td>
		<td class="base-color" align="left">Encaminhamento da Orientação Técnica nº 37/2021, que aborda alguns
comentários e recente decisão do TCU sobre atestados de capacidade técnica e a sua
aceitação em processos licitatórios.</td>
		</tr>';
$vSCOPDESCRICAO .= '
	</table>';
	
	
$htmlImpressao["footer"] = "<hr>
							<table width='100%'>
								<tr>
									<td align='center'>Gestão Assessoria e Consultoria em Administração Pública Ltda</td>
								</tr>
								<tr>
									<td align='center'>Rua João Bayer nº 744 – Bairro Petrópolis – Taquara – RS | CEP 95607-008 | Fone: (51) 3541 3355</td>
								</tr>
								<tr>
									<td align='center'>www.gestao.srv.br | gestao@gestao.srv.br</td>
								</tr>
							</table>";	

$vSHtmlDefault["body"] = $vSCOPDESCRICAO;

// mode, format, default_font_size, default_font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer
$mpdf = new mPDF("c", "A4-L", "", "", 10, 10, $vIMargemTopo, 10, 10, 5);

//DEFINE O CABEÇALHO
$mpdf->SetHTMLHeader($vSHtmlDefault["header"]);

$mpdf->SetDisplayMode("fullpage");

$mpdf->list_indent_first_level = 0;

//Carrega css padrão PDF
//$stylesheet = file_get_contents("../css/mpdf.css");
//$mpdf->WriteHTML($stylesheet, 1);

$mpdf->WriteHTML($vSHtmlDefault["body"], 2);

//DEFINE O RODAPE
$mpdf->SetHTMLFooter($htmlImpressao["footer"]);

//echo _MPDF_TEMP_PATH; 
$mpdf->Output("agenda-".date('d-m-Y H:m:s').".pdf", "I");

?>