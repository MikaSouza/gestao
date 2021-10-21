<?php 
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel; charset=UTF-8");
header ("Content-Disposition: attachment; filename=\"relatorio.xls\"");
header ("Content-Description: PHP Generated Data" );

include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/transaction/transactionAgendaGenerica.php';
include_once __DIR__.'/../rh/combos/comboUsuarios.php';
$arquivo = 'agenda-aberta-'.date('d-m-Y H:m:s').'.xls';

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../assets/images/rw.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Relat&oacute;rio Agenda Aberta</title>
</head>
<body>
<br>

<?php
$vSHTML = '<table width="100%" border="0.5px" align="center" cellpadding="6" cellspacing="1" style="background-color:#D3D3D3">
	<tr class="bg-cinza text-center"> 
		<td nowrap="nowrap" class="bg-cinza" colspan="14" align="center">PROGRAMA DE TRABALHO '. strtoupper(descricaoMes($vIMES)) .' DE '. $vIANO .'</td>
	</tr>';
	
	
$vSHTML .= '<tr style="background-color:#E6E6FA">
		<td class="base-color">&nbsp;</td>';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSHTML .= '<td class="base-color" colspan="2" align="center">'. otimizarNome($usuarios['USUNOME']) .'</td>';
		endforeach; 
		$vSHTML .= '<td class="base-color">&nbsp;</td> 
	</tr>
	<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa">&nbsp;</td> ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSHTML .= '<td class="bg-turquesa" align="center">Município</td> 
		<td class="bg-turquesa" align="center">Atividade</td>';
		endforeach; 
		$vSHTML .= '<td class="bg-turquesa">&nbsp;</td> 
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
	$vSHTML .= '<tr style="background-color: '. (($datax == 0) || ($datax == 1) ? '#d3d3d3' : '#FFFFFF') .'" class="linha">	
		<td>'. substr(diaSemana2($datax, 'S'), 0, 1)."/".date('d', $vtimestamp + 60*60*24*$xd).'</td>  ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) : 
		$vSHTML .= '<td>'. (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['MUNICIPIO']).'</td> 
		<td>'. (($atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE'] == '') ? '' : $atividades[$usuarios['USUCODIGO']][$vIANO.'-'.$vIMES.'-'.$vIDia]['ATIVIDADE']).'</td>';
		endforeach;
		$vSHTML .= '<td>'. substr(diaSemana2($datax, 'S'), 0, 1)."/".date('d', $vtimestamp + 60*60*24*$xd) .'</td> 
	</tr>';
		$xd = $xd + 1;
		$datay = date('Y-m-d', $vtimestamp + 60*60*24*$xd);
	} while ($datay < $hojemaisum); 

$vSHTML .= '<tr style="background-color:#FFFFFF">
		<td class="base-color">&nbsp;</td>';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSHTML .= '<td class="base-color" colspan="2" align="center">'. otimizarNome($usuarios['USUNOME']) .'</td>';
		endforeach; 
		$vSHTML .= '<td class="base-color">&nbsp;</td> 
	</tr>
	<tr style="background-color:#FFFFFF">
		<td class="bg-turquesa">&nbsp;</td> ';
		foreach (comboUsuariosAgenda('', $vICLIRESPONSAVEL) as $usuarios) :  
		$vSHTML .= '<td class="bg-turquesa" align="center">Município</td> 
		<td class="bg-turquesa" align="center">Atividade</td>';
		endforeach; 
		$vSHTML .= '<td class="bg-turquesa">&nbsp;</td>  
	</tr>
	</table>';
	echo $vSHTML;
?>

<br />
</body>
</html>
