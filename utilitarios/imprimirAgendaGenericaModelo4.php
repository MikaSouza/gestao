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
		<td nowrap="nowrap" class="bg-cinza" colspan="3" align="center">RELATÓRIO DE ATIVIDADES / ASSESSORIA CONTÁBIL</td>
	</tr>';
	
	
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">MUNICÍPIO:</td>
		<td class="base-color" colspan="2" align="center">RIOZINHO/RS</td>
		</tr>';
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">CONTRATO Nº:</td> 
		<td class="base-color">19/2021</td> 
	</tr>';
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">MÊS:</td> 
		<td class="base-color">JUNHO/2021</td> 
	</tr>';
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">DOCUMENTO FISCAL Nº:</td> 
		<td class="base-color">xxxxxx</td> 
	</tr>';	
$vSCOPDESCRICAO .= '<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa">DESCRIÇÃO DAS ATIVIDADES</td> 
	</tr>';
$vSCOPDESCRICAO .= '<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa" colspan="2">1 – Assessoramento para conclusão do processo de levantamento, análises e compilação
			de informações e estruturação da proposta do Plano Plurianual do Município para o
			quadriênio 2022-2025, elaboração de tabelas de projeções de receita e estimativas de
			despesa e elaboração do projeto de lei;
			2 – Orientações gerais ao Setor Contábil para abertura de crédito adicional especial no
			orçamento, para viabilização de repasse de recursos ao Corpo de Bombeiros;
			3 - Assessoramento para elaboração do SIOPE relativo ao 1º e 2º bimestres de 2021;
			4 – Auxílio para realização de Audiência Pública para apresentação e discussão do PPA na
			Câmara de Vereadores.
			5 – Orientações gerais para realização de alterações orçamentárias, revisões de
			procedimentos e conciliações.

			• Atendimento presencial na sede da Prefeitura: 29/06/2021
			• Atendimento remoto permanente por meio de suporte telefônico, atendimento eletrônico e
			demais meios de comunicação (e-mail, chat, aplicativos de celular e outros).</td> 
	</tr>
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