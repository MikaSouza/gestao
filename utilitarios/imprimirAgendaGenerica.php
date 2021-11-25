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


$vSCOPDESCRICAO .= '<table width="100%" border="0.5px" align="center" style="background-color:#D3D3D3">
	<tr class="bg-cinza text-center"> 
		<td nowrap="nowrap" class="bg-cinza" colspan="14" align="center">PROGRAMA DE TRABALHO '. strtoupper(descricaoMes($vIMES)) .' DE '. $vIANO .'</td>
	</tr>';
	
	
$vSCOPDESCRICAO .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">&nbsp;</td>';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSCOPDESCRICAO .= '<td class="base-color" colspan="2" align="center">'. otimizarNome($usuarios['USUNOME']) .'</td>';
		endforeach; 
		$vSCOPDESCRICAO .= '<td class="base-color">&nbsp;</td> 
	</tr>
	<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa">&nbsp;</td> ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSCOPDESCRICAO .= '<td class="bg-turquesa" align="center">Município</td> 
		<td class="bg-turquesa" align="center">Atividade</td>';
		endforeach; 
		$vSCOPDESCRICAO .= '<td class="bg-turquesa">&nbsp;</td> 
	</tr>';
	
	$vResult = fill_AgendaGenericaMesAno($vIANO, $vIMES, $vICLIRESPONSAVEL);
	$atividades = array(); 
	  
	foreach ($vResult['dados'] as $arrayDados) {
		$atividades[$arrayDados['AGERESPONSAVEL']][$arrayDados['AGEDATA']]['MUNICIPIO'] = $arrayDados['AGEMUNICIPIO'];	
		$atividades[$arrayDados['AGERESPONSAVEL']][$arrayDados['AGEDATA']]['ATIVIDADE'] = $arrayDados['AGEATIVIDADE'];
	}	
	$vDDataInicio = $vIANO.'-'.$vIMES.'-01';
	$vSUltimoDiaMes = ultimoDiaMes($vIANO, $vIMES);
	$vDDataFinal = $vIANO.'-'.$vIMES.'-'.$vSUltimoDiaMes;
	$vtimestamp = strtotime($vDDataInicio);
	$hojemaisum = date('Y-m-d', strtotime('+1 days', strtotime($vDDataFinal)));
	$xd = 0; 
	do {
		$datax = date('w', $vtimestamp + 60*60*24*$xd); 
		if (strlen($vIDia) == 1) $vIDia = '0'.$vIDia;
		if (strlen($vIMES) == 1) $vIMES = '0'.$vIMES;
		$vIDia = date('d', $vtimestamp + 60*60*24*$xd); 	
	$vSCOPDESCRICAO .= '<tr style="background-color: '. (($datax == 0) || ($datax == 1) ? '#d3d3d3' : '#FFFFFF') .'" class="linha">	
		<td>'.  substr(diaSemana2($datax, 'S'), 0, 1)."/".date('d', $vtimestamp + 60*60*24*$xd).'</td>  ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) : 
		$vSCOPDESCRICAO .= '<td>'. (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO']).'</td> 
		<td>'. (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE']).'</td>';
		endforeach;
		$vSCOPDESCRICAO .= '<td>'.  substr(diaSemana2($datax, 'S'), 0, 1)."/".date('d', $vtimestamp + 60*60*24*$xd) .'</td> 
	</tr>';
		$xd = $xd + 1;
		$datay = date('Y-m-d', $vtimestamp + 60*60*24*$xd);
	} while ($datay < $hojemaisum); 

$vSCOPDESCRICAO .= '<tr style="background-color:#FFFFFF">
		<td class="base-color">&nbsp;</td>';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSCOPDESCRICAO .= '<td class="base-color" colspan="2" align="center">'. otimizarNome($usuarios['USUNOME']) .'</td>';
		endforeach; 
		$vSCOPDESCRICAO .= '<td class="base-color">&nbsp;</td> 
	</tr>
	<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa">&nbsp;</td> ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSCOPDESCRICAO .= '<td class="bg-turquesa" align="center">Município</td> 
		<td class="bg-turquesa" align="center">Atividade</td>';
		endforeach; 
		$vSCOPDESCRICAO .= '<td class="bg-turquesa">&nbsp;</td>  
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