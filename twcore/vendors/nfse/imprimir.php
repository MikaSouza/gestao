<?php
/*Gunther - 06.10.2016
troquei os links que estavam publicados igam http://www.igam.com.br/aplicativo/nfe
para a pasta igam que criei dentro do nfe/.
com isso não precisamos mais acessar o site deles - esteve fora do ar e desconfigurou a NOTA FISCAL
*/
define("GLOBAL_PATH",$_SERVER['DOCUMENT_ROOT'].'/marpa_intranet/');
include_once(GLOBAL_PATH."cfg/cfg.main.php");
require_once(CLASSE_PATH.'formatacao.class.php');
require_once(CLASSE_PATH.'error.class.php');
require_once(CLASSE_PATH.'core.class.php');
require_once(CLASSE_PATH.'dbpg.class.php');
require_once(CLASSE_PATH.'geral.class.php');
require_once(CLASSE_PATH.'sendmail.php');
require_once(GLOBAL_PATH.'smtp/smtp.php');
$db = new edz_db(DB_HOST,DB_USER,DB_PASS,DB_BASE);
//error_reporting(E_ALL);
if($_GET['tipolan']>0 && $_GET['numlan']>0) :
$codempfil = $_GET['codempfil'];
if($codempfil<=0) $codempfil = 1;
$tipolan = $_GET['tipolan'];
$numlan = $_GET['numlan'];
//$sql = "SELECT codempfil, tipolan, numlan FROM marpafin WHERE tipolan = $tipolan AND numlan = $numlan";
//$res = $db->db_query($sql);
$sql = "SELECT valdeducoes, nrnotafin, numrecfin, TO_CHAR(datalan, 'dd/mm/YYYY') as dt, cgc, cgccobranca, valor, email, empresaendcobr as empresa,
					bairrocob, cepcob, telefcob, cidadecob as cidade, endercob, estado, texto, codempfil, sigla, lote_envio, datalan, fj, simples
				FROM marpafin mf LEFT JOIN marpacliente mc USING(sigla)
				WHERE codempfil = $codempfil AND tipolan = $tipolan AND numlan = $numlan";
$res = $db->db_query($sql);
$r = $res[0];
$lote = $r['lote_envio'];
$datalan = $r['datalan'];
$nota = ($r['nrnotafin']>0?trim($r['nrnotafin']):trim($r['numrecfin']));
//if($lote<=0 && $nota!='201500000000327' && $nnota!='201500000000325' && $nota['nrnotafin']!='201500000000326')
	//exit('Nota inv&aacute;lida');
$sql = "SELECT verificacao FROM nota_fiscal_lote WHERE id = $lote";
$res = $db->db_query($sql);
$verificacao = $res[0]['verificacao'];
//if($verificacao=='') exit('C&oacute;digo de verifica&ccedil;&atilde;o n&atilde;o gerado, aguarde....');
$numero = ($r['nrnotafin']>0?$r['nrnotafin']:$r['numrecfin']);
$n1 = substr($numero,0,4);
$n2 = substr($numero,4,11);
$n2 = ltrim($n2, '0');
//Gunther 03.05.2016 - colocava fixo o cgc em vez cgccobranca
$cnpj = $r['cgc'];
if (strlen($r['cgccobranca']) > 10)
	{
	$cnpj = $r['cgccobranca'];
	$cnpj = ereg_replace("[^0-9]", "", $cnpj);
	$cnpj = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
	}
//$cnpj = ereg_replace("[^0-9]", "", $cnpj);
//$cnpj = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
$nome = utf8_encode($r['empresa']);
$endereco = utf8_encode($r['endercob']).' - '.utf8_encode($r['bairrocob']);
$cidade = utf8_encode($r['cidade']);
$estado = utf8_encode($r['estado']);
$telefone = $r['telefcob'];
$email = $r['email'];
$texto.= utf8_encode($r['texto']);
$valor = $r['valor'];
$simples = $r['simples'];
$fj = $r['fj'];
$nota = $r;

$valor_retencoes = $r['valdeducoes'];
if (!in_array($nota['codempfil'], [3, 6])){
    if ($nota['valor'] >= 680) {
        $nota['ir'] = $nota['valor'] * 1.5 / 100;
    } else {
        $data = date('Y-m-d');
        $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data' AND datalan <= '$datalan' AND nrnotafin != 1 AND nrnotafin != $r[nrnotafin] AND nrnotafin > 0 AND valdeducoes <= 0 AND codstatus = 1";
        $res = $db->db_query($sql);
        $total = $res[0]['total'];

        if ($total >= 680) {
            $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data' AND datalan <= '$datalan' AND nrnotafin > 0 AND valor < 680 AND valdeducoes <= 0 AND codstatus = 1";
            $res = $db->db_query($sql);
            $total = $res[0]['total'];
            $nota['ir'] = $total * 1.5 / 100;
        } else {
            if ($total+$nota['valor'] >= 680) {
                $nota['ir'] = ($nota['valor'] + $total) * 1.5 / 100;
            }
        }
    }

    if ($nota['valor'] >= 215.06) {
        $nota['pis']    = $nota['valor'] * 0.65 / 100;
        $nota['cofins'] = $nota['valor'] * 3.00 / 100;
        $nota['csll']   = $nota['valor'] * 1.00 / 100;
    } else {
        $data = date('Y-m-d',mktime(0, 0, 0, date("m"), date(1), date("Y")));
        $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data' AND datalan <= '$datalan' AND nrnotafin != 1  AND nrnotafin != $r[nrnotafin] AND nrnotafin > 0 AND valdeducoes <= 0 AND codstatus IN (1,3)";
        $res = $db->db_query($sql);
        $total = $res[0]['total'];

        if ($total >= 215.06) {
            $nota['pis']    = ($total) * 0.65 / 100;
            $nota['cofins'] = ($total) * 3.00 / 100;
            $nota['csll']   = ($total) * 1.00 / 100;
        } else {
            if($total + $nota['valor']>=215.06) {
                $nota['pis']    = ($nota['valor'] + $total) * 0.65 / 100;
                $nota['cofins'] = ($nota['valor'] + $total) * 3.00 / 100;
                $nota['csll']   = ($nota['valor'] + $total) * 1.00 / 100;
            }
        }
    }
}

$ir     = round($nota['ir'],2);
$pis    = round($nota['pis'],2);
$cofins = round($nota['cofins'],2);
$csll   = round($nota['csll'],2);

if ($simples == 1) {
    $pis    = 0;
    $cofins = 0;
    $csll   = 0;
}
if ($fj == "F") {
    $ir     = 0;
    $pis    = 0;
    $cofins = 0;
    $csll   = 0;
}

$valoriss  = (!in_array($_GET['codempfil'], [3, 6])) ? $valor * 0.05 : 0;
$valoriss  = number_format($valoriss,2,',','.');
$total     = round($valor - $ir - $pis - $cofins - $csll,2);
$retencoes = $ir + $pis + $cofins + $csll;
// $retencoes = (!in_array($_GET['codempfil'], [3, 6])) ? $valor_retencoes : 0; //GUNTHER
$total     = round($valor - $retencoes,2); // GUNTHER

$valor     = number_format($valor,2,',','.');
$ir        = number_format($ir,2,',','.');
$pis       = number_format($pis,2,',','.');
$cofins    = number_format($cofins,2,',','.');
$csll      = number_format($csll,2,',','.');
$total     = number_format($total,2,',','.');
$retencoes = number_format($retencoes,2,',','.');

if ($_GET['codempfil'] == 3) { //Virtual
	$nome_empresa = 'MARPA VIRTUAL SOLUÇÕES DIGITAIS EIRELI';
	$cnpj_empresa = '08.942.390/0001-20';
	$inscricaoMunicipal_empresa = '51230720';
} else if ($_GET['codempfil'] == 6) { //Tributário
  $nome_empresa = 'MARPA VIRTUAL SOLUÇÕES DIGITAIS EIRELI';
  $cnpj_empresa = '08.942.390/0002-01';
  $inscricaoMunicipal_empresa = '29929024';
} else if ($_GET['codempfil'] == 4) { //Tributário
	$nome_empresa = 'MARPA GESTÃO TRIBUTÁRIA LTDA';
	$cnpj_empresa = '20.102.230/0002-50';
	$inscricaoMunicipal_empresa = '29929024';
} else if ($_GET['codempfil'] == 5) { //Filial
	$nome_empresa = 'MARPA CONSULTORIA E ASSESSORIA EMPRESARIAL LTDA';
	$cnpj_empresa = '91.933.119/0009-20';
	$inscricaoMunicipal_empresa = '29342821';
} else { //Matriz
	$nome_empresa = 'MARPA CONSULTORIA E ASSESSORIA EMPRESARIAL LTDA';
	$cnpj_empresa = '91.933.119/0001-72';
	$inscricaoMunicipal_empresa = '08036721';
}
?>
<!DOCTYPE composition PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="cache-control" content="PRIVATE" />
  <title>:: NFS-e - Nota Fiscal de Servi&ccedil;os eletr&ocirc;nica ::</title>
  <link rel="stylesheet" type="text/css" href="igam/nota_fiscal.css" />
</head>
<body>
<div id="moldura">
	<form id="form" name="form" method="post" action="/nfse/pages/exibicaoNFS-e.jsf" enctype="application/x-www-form-urlencoded">
		<input type="hidden" name="form" value="form" />
		<table id="container" border="0" cellpadding="0" cellspacing="0" width="646">
    	<tbody>
        <tr>
        	<td>
            <div class="hh1">
              NFS-e - NOTA FISCAL DE SERVI&Ccedil;OS ELETR&Ocirc;NICA
            </div>
          </td>
        </tr>
        <tr>
          <td class="bordaInferior">
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr class="teste">
                <td width="25%" class="bordaLateral">
                  <span class="numeroDestaque">N&ordm;:<?=$n1?>/<?=$n2?> </span>
                </td>
                <td width="31%" class="bordaLateral">
                  <span class="subTitulo">Emitida em:<br /> </span><span class="dataEmissao"><?=$r['dt']?> </span>
                </td>
                <!--<td width="17%" class="bordaLateral">
                  <span class="subTitulo">Compet&ecirc;ncia:</span>
                  <br /><span class="dataEmissao">01/04/2015</span>
                </td>-->
                <td width="27%">
                  <span class="subTitulo">C&oacute;digo de Verifica&ccedil;&atilde;o:<br />
                  </span><span class="dataEmissao"><?=$verificacao;?></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tbody>
                <tr>
                  <td valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tbody>
                        <tr>
                          <td width="160"><img id="form:imgLogo" src="http://www.marpa.com.br/assets/frontend/img/logo.png" height="60" align="middle" />
                        </td>
                        <td width="491">
                          <div class="hh3"><?php echo $nome_empresa ?></div>
                          <table width="100%" cellspacing="1" class="teste">
                            <tbody>
                              <tr>
                                <td width="52%">
                                  <span class="cnpjPrincipal" id="j_id">CPF/CNPJ: <?php echo $cnpj_empresa ?> </span>
                                </td>
                                <td width="48%">
                                  <span class="cnpjPrincipal" id="j_id2">Inscri&ccedil;&atilde;o Municipal: <?php echo $inscricaoMunicipal_empresa ?> </span>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2">RUA MANOELITO DE ORNELLAS, 55, PRAIA DE BELAS - Cep: 90110-230</td>
                              </tr>
                              <tr>
                                <td>
                                  <span id="j_id3">Porto Alegre</span>
                                </td>
                                <td>
                                  <span id="j_id5">RS </span>
                                </td>
                              </tr>
                              <tr>
                                <td>Telefone: (51) 3022-5555</td>
                                <td>Email: faturamento@marpa.com.br</td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
											<tr>
                        <td colspan="2"><hr class="linhaDivisao" /></td>
                    	</tr>
                    	<tr>
                        <td colspan="2">
                          <div class="box02">
                            <table>
                              <tbody>
                                <tr>
                                  <td colspan="2">
                                    <div class="hh2">Tomador do(s) Servi&ccedil;o(s)</div>
                                  </td>
                                </tr>
                                <tr class="teste">
                                  <td width="52%">
                                    <span class="cnpjPrincipal" id="j_id27">
                                    	<span id="form:j_id52">CPF/CNPJ: <?=$cnpj?></span>
                                    </span>
                                  </td>
                                  <td width="48%">
                                    <span class="cnpjPrincipal" id="j_id33">
                                    	<span id="form:j_id58">Inscri&ccedil;&atilde;o Municipal: N&atilde;o Informado</span>
                                    </span>
                                  </td>
                                </tr>
                                  <tr class="teste">
                                    <td colspan="2">
                                      <span class="cnpjPrincipal"><?=$nome?></span>
                                    </td>
                                  </tr>
                                  <tr class="teste">
                                    <td colspan="2"><?=$endereco?></td>
                                  </tr>
                                  <tr class="teste">
                                    <td>
                                      <span id="j_id41"><span id="form:j_id66"><?=$cidade?></span> </span>
                                    </td>
                                    <td>
                                      <span id="j_id44"><span id="form:j_id69"><?=$estado?></span> </span>
                                    </td>
                                  </tr>
                                  <tr class="teste">
                                    <td>Telefone: <?=$telefone?></td>
                                    <td>Email: <?=$email?></td>
                                  </tr>
                                </tbody>
                              </table>
                              <img src="igam/bottom_box02.gif" alt="." class="noprint" />
                            </div>
                          	<hr class="linhaDivisao" />
                        	</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td></td>
        </tr>
        <tr class="teste"></tr>
        <tr>
          <td>
            <div class="box02">
              <table border="0" cellpadding="4" cellspacing="0" width="100%">
                <tbody>
                  <tr>
                    <td width="100%">
                    	<div class="hh2">Discrimina&ccedil;&atilde;o do(s) Servi&ccedil;o(s)</div>
                    </td>
                  </tr>
                  <tr>
                    <td class="servicos"><?=$texto?></td>
                  </tr>
                </tbody>
              </table>
            	<img src="igam/bottom_box02.gif" alt="." class="noprint" />
              <hr class="linhaDivisao" />
            </div>
            <div class="box04">
              <table border="0" cellpadding="2" cellspacing="0" width="100%">
              	<tbody>
                  <tr>
                    <td>
                      <span class="subTitulo">C&oacute;digo de Tributa&ccedil;&atilde;o Municipal:</span>
                      <p class="teste">
                        <?php
                          if (in_array($_GET['codempfil'], [3, 6])) {
                            echo '170200300 / Organização, arquivamento, conservação e gerenciamento de documentos de terceiros em quaisquer meios';
                          } else if (in_array($_GET['codempfil'], [4])) {
                            echo '172000100 / Consultoria e assessoria econômica ou financeira';
                          } else {
                            echo '170100100 / Assessoria ou consultoria de qualquer natureza, exceto econômica, financeira, de imprensa, em informática ou relacionada a operações de fatorização (factoring)';
                          }
                        ?>
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
              <hr class="linhaDivisao" />
            </div>
            <div style="margin: 0px 5px;">
              <p class="teste">
              	<span class="subTitulo">Subitem Lista de Servi&ccedil;os LC 116/03 / Descri&ccedil;&atilde;o:</span>
              </p>
              <p class="teste">
                <?php
                  if (in_array($_GET['codempfil'], [3])) {
                    echo '17.02 / Datilografia, digitação, estenografia, expediente, secretaria em geral, resposta audível, redação, edição, interpretação, revisão, tradução, apoio e infra-estrutura administrativa e congêneres.';
                  } else if (in_array($_GET['codempfil'], [4])) {
                    echo '17.20 / Consultoria e assessoria econômica ou financeira.';
                  } else {
                    echo '17.01 / Assessoria ou consultoria de qualquer natureza, não contida em outros itens desta lista; análise, exame, pesquisa, coleta, compilação e fornecimento de dados e informações de qualquer natureza, inclusive cadastro e similares.';
                  }
                ?>
              </p>
              <hr class="linhaDivisao" />
            </div>
            <div class="box04">
              <span id="j_id106"> </span>
              <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tbody>
                  <tr>
                    <td valign="top" width="50%">
                      <p class="teste">
                        <span class="subTitulo">Cod/Munic&iacute;pio da incid&ecirc;ncia do ISSQN:</span>
                      </p>
                      <p class="teste">4314902 / Porto Alegre</p>
                    </td>
                    <td valign="top" width="50%">
                      <span class="subTitulo">Natureza da Opera&ccedil;&atilde;o:</span>
                      <p class="teste">Tributa&ccedil;&atilde;o no munic&iacute;pio</p>
                    </td>
                  </tr>
              	</tbody>
              </table>
            </div>
            <span id="form:j_id116">
              <div style="margin: 0px 5px;">
                <span id="j_id106"> </span>
                  <table border="0" cellpadding="4" cellspacing="0" width="100%">
                    <tbody>
                      <tr>
                      	<span id="form:j_id118">
                          <td width="33%" height="25" align="center" valign="middle" class="bordaLateral">
                            <p class="teste">
                              <?php
                                if (in_array($_GET['codempfil'], [3, 6])) {
                                  echo '<span class="subTitulo"> Regime Especial de Tributa&ccedil;&atilde;o: </span>Sociedade de Profissionais ME ou EPP do Simples Nacional';
                                }
                              ?>
                            </p>
                          </td>
                        </span>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </span>
            	<hr class="linhaDivisao" />
    				</td>
  				</tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top" class="bordaInferior">
                    <div class="box05">
                      <table border="0" cellpadding="4" cellspacing="0" width="100%">
                        <tbody>
                          <tr>
                            <td class="hh2" width="44%">Valor dos servi&ccedil;os:</td>
                            <td class="hh2" width="56%" align="right">R$ <?=$valor?></td>
                          </tr>
                          <tr>
                          	<td colspan="2">
                              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tableValores">
                                <tr>
                                  <td width="131" class="bordaInferior">(-) Descontos:</td>
                                  <td width="165" align="right" class="bordaInferior">R$ 0,00</td>
                                </tr>
                                <tr>
                                  <td class="bordaInferior">(-) Reten&ccedil;&otilde;es Federais:</td>
                                  <td align="right" class="bordaInferior">R$ <?=$retencoes?></td>
                                </tr>
                                <tr>
                                  <td class="bordaInferior">(-) ISS Retido na Fonte:</td>
                                  <td align="right" class="bordaInferior">R$ 0,00</td>
                                </tr>
                                <tr>
                                  <td class="valorLiquido"><b>Valor L&iacute;quido:</b></td>
                                  <td align="right" class="valorLiquido"><b>R$ <?=$total?></b></td>
                                </tr>
                            	</table>
                      			</td>
                    			</tr>
                				</tbody>
            					</table>
            					<img src="igam/bottom_box05.gif" alt="." class="noprint" />
        						</div>
    							</td>
                  <td class="bordaInferior">
                    <div class="box05">
                    	<table border="0" cellpadding="4" cellspacing="0" width="100%">
                        <tbody>
                          <tr>
                            <td width="42%" class="hh2">Valor dos servi&ccedil;os:</td>
                            <td width="58%" align="right" class="hh2">R$ <?=$valor?></td>
                          </tr>
                          <tr>
	                          <td colspan="2">
	                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="teste">
	                              <tr>
                                	<td width="152" class="bordaInferior">(-) Dedu&ccedil;&otilde;es:</td>
                                  <td width="144" align="right" class="bordaInferior">R$ 0,00</td>
                                </tr>
                                <tr>
                                  <td class="bordaInferior">(-) Desconto Incondicionado:</td>
                                	<td align="right" class="bordaInferior">R$ 0,00</td>
                                </tr>
                                <tr>
                                  <td class="bordaInferior" style="font-size: 12px;">
                                    <strong>(=) Base de C&aacute;lculo:</strong>
                                  </td>
                                  <td align="right" class="bordaInferior" style="font-size: 12px;">
                                    <strong>R$ <?=$valor?> </strong>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="bordaInferior">(x) Al&iacute;quota:</td>
                                  <td align="right" class="bordaInferior">-</td>
                                </tr>
                                <tr>
                                  <td class="valorLiquido">
                                    <b>(=)Valor do ISS:</b>
                                  </td>
                                  <td align="right" class="valorLiquido">
                                    <strong>R$ <?=$valoriss?> </strong>
								  </td>
                                </tr>
                              </table>
	                          </td>
                          </tr>
                        </tbody>
                      </table>
                      <img src="igam/bottom_box05.gif" alt="." class="noprint" />
                    </div>
                  </td>
              	</tr>
          		</table>
      			</td>
          </tr>
          <span id="form:j_id148">
            <tr>
              <td class="bordaInferior">
                <span style="margin: 0px 5px;" class="subTitulo">Reten&ccedil;&otilde;es Federais:</span><br />
                <span id="form:j_id150">
                  <span style="margin: 0px 5px;" class="subTitulo">PIS:</span>
                  <span class="teste">R$ <?=$pis?></span>
                </span>
                <span id="form:j_id153">
                	<span style="margin: 0px 5px;" class="subTitulo">COFINS:</span>
                	<span class="teste">R$ <?=$cofins?></span>
                </span>
                <span id="form:j_id156">
                	<span style="margin: 0px 5px;" class="subTitulo">IR:</span>
                	<span class="teste">R$ <?=$ir?></span>
                </span>
                <span id="form:j_id159">
                	<span style="margin: 0px 5px;" class="subTitulo">CSLL:</span>
                	<span class="teste">R$ <?=$csll?></span>
                </span>
                <span id="form:j_id162">
                  <span style="margin: 0px 5px;" class="subTitulo">INSS:</span>
                  <span class="teste">R$ 0,00</span>
                </span>
              </td>
            </tr>
          </span>
          <tr>
          	<td>
              <table class="tableNoBorder" border="0" cellpadding="4" cellspacing="0" width="100%">
              	<tbody>
                	<tr>
                    <td width="9%">
                      <img src="igam/img_brasao_PortoAlegre.png" alt="Bras&atilde;o Prefeitura" />
                    </td>
                  	<td width="91%">
                      <span class="subTitulo">Prefeitura de Porto Alegre - Secretaria da Fazenda</span>
                      <p class="teste">
                        Rua Siqueira Campos, 1300 - 4&ordm; andar - Bairro Centro Hist&oacute;rico - CEP: 90.010-907 - Porto Alegre RS.<br />
                       	Tel: 156 (op&ccedil;&atilde;o 4) ou (51) 3289-0140 (chamadas de outras cidades)<br />
                        Email: nfse@smf.prefpoa.com.br<br />
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
      	</tbody>
			</table>
		</div>
	</body>
</html>
<?php endif; ?>