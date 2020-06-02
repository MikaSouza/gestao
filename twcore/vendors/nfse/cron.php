<?php
session_start();
ini_set("default_charset","UTF-8");
//date_default_timezone_set("Brazil/East");
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
$mail = new sendmail();
ini_set('Display_errors', 'On');
error_reporting(E_ALL);
error_reporting(E_ALL);
require_once("NFse.php");
function enviaNotas() {
	global $db,$mail;
	$sql = "SELECT DISTINCT mf.codempfil, mf.tipolan, mf.numlan, mf.valor, mc.fj, mc.cgc, mc.emailcob, mc.empresaendcobr as empresa,
						mc.telefcob as telefone, mc.cidadecob as cidade , mc.endercob as endereco, mc.bairrocob as bairro,  mf.texto,
						mc.cepcob as cep, mc.sigla, mc.estadocob as estado, mc.prefixocob as prefixo, cgccobranca, mc.simples
					FROM marpafin mf INNER JOIN marpacliente mc ON(mc.sigla = mf.sigla)
					WHERE status_envio = 0 AND nrnotafin = 1 LIMIT 5
					--WHERE status_envio = 0 AND nrnotafin >= 36000 LIMIT 1";
	$notas = $db->db_query($sql);

	$NFse = new NFse();
	$html = "<table width='100%'><tr><th>Fatura</th><th>Cliente</th><th>Valor</th><th>Protocolo</th><th>Lote</th></tr>";
	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ','Ü','º','°');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o','U','','');
	foreach($notas as $i=>$nota) {
		foreach($nota as $x=>$n) {
			$notas[$i][$x] = utf8_encode($n);
			$notas[$i][$x] = str_replace($a, $b, $notas[$i][$x]);
		}
	}
	/*
	$sql = "UPDATE marpafin SET status_envio = 0, lote_envio = 0 WHERE codempfil = 1 AND tipolan = 8 AND numlan = 3097";
	$db->db_query($sql);
	exit('ok');*/
	foreach ($notas as $i=>$nota) {
		$_SESSION['empresa'] = $nota['codempfil'];
		echo 'Fatura: '.$nota['codempfil'].' - '.$nota['tipolan'].' - '.$nota['numlan'];
		$sql = "SELECT MAX(id) as m FROM nota_fiscal_lote";
		$lotes = $db->db_query($sql);
		$lote = $lotes[0]['m'];
		$lote++;
		if($lote<=455) $lote = 455;
		$sql = "INSERT INTO nota_fiscal_lote (id,status,tentativas) VALUES ($lote, 0, 0)";
		$db->db_query($sql);
		$sql = "UPDATE marpafin SET status_envio = 1, lote_envio = $lote WHERE codempfil = $nota[codempfil] AND tipolan = $nota[tipolan] AND numlan = $nota[numlan]";
    $db->db_query($sql);
		$nota['numero'] = $lote;

		$nota['ir'] = '0.00';
		$nota['pis'] = '0.00';
		$nota['cofins'] = '0.00';
		$nota['csll'] = '0.00';

        if (!in_array($nota['codempfil'], [3, 6])){
            if($nota['valor']>=680) {
                $nota['ir'] = $nota['valor'] * 1.5 / 100;
            } else {
                $data = date('Y-m-d');
                $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data' AND nrnotafin != 1 AND nrnotafin > 0 AND codstatus = 1";
                $res = $db->db_query($sql);
                $total = $res[0]['total'];

                if($total>=680) {
                    $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data'  AND nrnotafin > 0 AND valor < 680 AND codstatus = 1";
                    $res = $db->db_query($sql);
                    $total = $res[0]['total'];
                    $nota['ir'] = $total * 1.5 / 100;
                } else {
                    if($total+$nota['valor']>= 680) {
                        $nota['ir'] = ($nota['valor'] + $total) * 1.5 / 100;
                    }
                }
            }
            if($nota['valor']>=215.06) {
                $nota['pis'] = $nota['valor'] * 0.65 / 100;
                $nota['cofins'] = $nota['valor'] * 3.00 / 100;
                $nota['csll'] = $nota['valor'] * 1.00 / 100;
            } else {
                $data = date('Y-m-d',mktime(0, 0, 0, date("m"), date(1), date("Y")));
                $sql = "SELECT SUM(valor) as total FROM marpafin WHERE sigla = $nota[sigla] AND datalan >= '$data' AND nrnotafin != 1  AND nrnotafin > 0 AND codstatus IN (1,3)";
                $res = $db->db_query($sql);
                $total = $res[0]['total'];
                if($total>=215.06) {
                    $nota['pis'] = ($total) * 0.65 / 100;
                    $nota['cofins'] = ($total) * 3.00 / 100;
                    $nota['csll'] = ($total) * 1.00 / 100;
                } else {
                    if($total + $nota['valor']>=215.06) {
                        $nota['pis'] = ($nota['valor'] + $total) * 0.65 / 100;
                        $nota['cofins'] = ($nota['valor'] + $total) * 3.00 / 100;
                		$nota['csll'] = ($nota['valor'] + $total) * 1.00 / 100;
                    }
                }
            }
        }

        if ($nota['simples'] == 1 || strtoupper($nota['fj']) == "F") {
            $nota['ir']     = ($nota['simples'] == 1) ? $nota['ir'] : '0.00';
			$nota['pis']    = '0.00';
			$nota['cofins'] = '0.00';
			$nota['csll']   = '0.00';
        }

        $arr_estados = [
            'AC' => 12,
            'AL' => 27,
            'AP' => 16,
            'AM' => 13,
            'BA' => 29,
            'CE' => 23,
            'DF' => 53,
            'ES' => 32,
            'GO' => 52,
            'MA' => 21,
            'MT' => 51,
            'MS' => 50,
            'MG' => 31,
            'PA' => 15,
            'PB' => 25,
            'PR' => 41,
            'PE' => 26,
            'PI' => 22,
            'RJ' => 33,
            'RN' => 24,
            'RS' => 43,
            'RO' => 11,
            'RR' => 14,
            'SC' => 42,
            'SP' => 35,
            'SE' => 28,
            'TO' => 17,
        ];

        $estado_id = array_key_exists(strtoupper($nota['estado']), $arr_estados) ? $arr_estados[strtoupper($nota['estado'])] : 43;
        $where = ' AND estado_id = '.$estado_id;


    //$nota['cidade'] = str_replace($a, $b, $nota['cidade']);
    $sql = "SELECT codigo FROM marpamunicipio WHERE cidade ilike '%".$nota['cidade']."%' $where AND codigo > 0";
    $cidade = $db->db_query($sql);
    $nota['cidade_tomador'] = ($cidade[0]['codigo']>0?$cidade[0]['codigo']:'4314902');
    if($nota['cidade_tomador'] == '4314902') $nota['estado'] = 'RS';

    $telefone = explode('/', $nota['telefone']);
    $telefone = $telefone[0];
    $telefone = explode(';', $nota['telefone']);
    $telefone = $telefone[0];
    $telefone = str_replace('-', '', $telefone);
    $telefone = str_replace('.', '', $telefone);
    $telefone = $nota['prefixo'].$telefone;
    $nota['telefone'] = substr($telefone, 0, 11);

    $cgc = ($nota['cgccobranca']!=''?$nota['cgccobranca']:$nota['cgc']);
    if(strlen($nota['cgccobranca'])>11) $nota['fj'] = 'J';
    $cgc = str_replace('-', '', $cgc);
    $cgc = str_replace('.', '', $cgc);
    $nota['cgc'] = str_replace('/', '', $cgc);

    $email = explode('/', $nota['emailcob']);
    $email = $email[0];
    $email = explode(' ', $nota['emailcob']);
    $email = $email[0];
    $email = explode(';', $nota['emailcob']);
    $email = $email[0];
    $nota['emailcob'] = substr($email, 0, 50);

		$nota['email'] = strlen($nota['emailcob'])>2?$nota['emailcob']:'faturamento@marpa.com.br';
		$nota['endereco'] = str_replace($a, $b, $nota['endereco']);
		$nota['endereco'] = strlen($nota['endereco'])>2?$nota['endereco']:'ENDEREÇO NAO CADASTRADO';
		$nota['bairro'] = strlen($nota['bairro'])>2?$nota['bairro']:'BAIRRO NAO CADASTRADO';
		$nota['uf'] = strlen($nota['estado'])==2?$nota['estado']:'RS';
		$nota['cep'] = str_replace('-', '', $nota['cep']);
		$nota['razao_social'] = str_replace('&', 'e', $nota['empresa']);
		$nota['razao_social'] = str_replace($a, $b, $nota['razao_social']);
		$nota['quantidade_rps'] = 1;
		$nota['discriminacao'] = preg_replace('/[^A-Za-z0-9() -]/', '', $nota['texto']);
		echo '<pre>';print_r($nota);echo '</pre>';
		$retorno = $NFse->montaXML($nota);
    $retorno = $retorno->outputXML;
		$retornoXML = new SimpleXMLElement($retorno);
		$protocolo = (string)$retornoXML->Protocolo;
		if($protocolo=='') $protocolo = 'Erro no envio';
		echo '<pre> Protocolo: ';var_dump($protocolo);
    $xml = fopen("xml/retorno/".$lote.".xml", "wb");
    fwrite($xml, $retorno);
    fclose($xml);
    $sql = "UPDATE nota_fiscal_lote SET data_envio = '".date('Y-m-d')."', protocolo = '$protocolo', status = 1 WHERE id = $lote";
    $db->db_query($sql);
    $html.= "<tr><td>".$nota['codempfil']." - ".$nota['tipolan']." - ".$nota['numlan']."</td><td>".utf8_decode($nota['empresa'])."</td><td>R$ $nota[valor]</td><td>$protocolo</td><td>$lote</td></tr>";
	}
	$html.="</table>";
  $subject = 'Envio de '.count($notas).' notas fiscais '.date('d/m/Y H:i');
  $to = 'faturamento@marpa.com.br; sistema.relatorios@marpa.com.br';
  //$to = 'epohren@gmail.com';
  if(count($notas)>0) $mail->sendMailAuth($to,'sistema@marpa.com.br',$html,$subject);
  echo 'FIM Cron<br />';
}
enviaNotas();
require_once('atualizaNotas.php');
?>