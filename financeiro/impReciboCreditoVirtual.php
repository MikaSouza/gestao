<?php 
error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once __DIR__.'/../twcore/teraware/php/constantes.php';

//if (($_SERVER["REQUEST_METHOD"] == "GET") && ($_GET["oid"] != "")) {
	
	$vICTRCODIGO = $_GET["vICXPCODIGO"];
	$vICXPCODIGO = $_GET["oid_parcela"];
    $vConexao = sql_conectar_banco();
	
	$empcodigo = 2; //$_SESSION["SI_USU_EMPRESA"];
	
	if ($vICXPCODIGO > 0){
		$vSSql = "SELECT
					p.CLINOME,
					c.CTRDESCRICAO,
					t.CXPVALOR AS VALORRECEBER,
					t.CXPDATAVENCIMENTO AS VENCIMENTO
				FROM
					CONTASARECEBER c 
				LEFT JOIN
					CLIENTES p
				ON
					c.CLICODIGO = p.CLICODIGO
				LEFT JOIN
					CONTASARECEBERXPARCELAS t
				ON
					t.CTRCODIGO = c.CTRCODIGO	
				WHERE					 
					t.CXPCODIGO = ".$vICXPCODIGO." AND
					c.EMPCODIGO = ".$empcodigo." AND
					c.CTRCODIGO = ".$vICTRCODIGO;
	} else {
	
		$vSSql = "SELECT
						p.CLINOME,
						c.CTRDESCRICAO,
						c.CTRVALORARECEBER AS VALORRECEBER,
						t.CXPDATAVENCIMENTO AS VENCIMENTO
					FROM
						CONTASARECEBER c
					LEFT JOIN
						CLIENTES p
					ON
						c.CLICODIGO = p.CLICODIGO
					LEFT JOIN
						CONTASARECEBERXPARCELAS t
					ON
						t.CTRCODIGO = c.CTRCODIGO	
					WHERE
						t.CXPSTATUS = 'S' AND
						c.EMPCODIGO = ".$empcodigo." AND
						c.CTRCODIGO = ".$vICTRCODIGO;
	}					
	$vSSql = stripcslashes($vSSql);
	$vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
	while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
		$vSCLIENTE = $vSResultLinha['CLINOME'];
		$vSCTRDESCRICAO = $vSResultLinha['CTRDESCRICAO'];
		$vSCTRVALORCOBERTURA = formatar_moeda($vSResultLinha['VALORRECEBER'])." (".valorPorExtenso($vSResultLinha['VALORRECEBER']).")";
		// pegar vencimentos
		$vSSql2 = "SELECT
						t.CXPDATAVENCIMENTO AS VENCIMENTO
					FROM						
						CONTASARECEBERXPARCELAS t					
					WHERE
						t.CXPSTATUS = 'S' AND						
						t.CTRCODIGO = ".$vICTRCODIGO;
		$vAResultDB2 = sql_executa(vGBancoSite, $vConexao, $vSSql2, false);
		$i = 0;
		while($vSResultLinha2 = sql_retorno_lista($vAResultDB2)) {
			if ($i == 0)
				$vDVENCIMENTO = formatar_data($vSResultLinha2['VENCIMENTO']);
			else
				$vDVENCIMENTO .= '- '. formatar_data($vSResultLinha2['VENCIMENTO']);
			$i++;
		}
	}

//}	
?>
<div style="background-image: url('../assets/images/recibo.jpg'); width: 714px; height: 475px;font-size:20px">
	<div style='clear: both;height:30px'></div>
	<div style='float:left; padding-left: 580px'><b>&nbsp;&nbsp;</b></div>
	<div style='clear: both;height:32px'></div>
	<div style='float:left; padding-left: 50px'><b><?= $vSCLIENTE;?></b></div>
	<div style='clear: both;height:55px'></div>
	<div style='float:left; padding-left: 50px; text-align:justify; height: 52px; width:625px; white-space:normal;'><?= $vSCTRVALORCOBERTURA;?></div>
	<div style='clear: both;height:18px'></div>
	<pre><div style='float:left; padding-left:25px; text-align:justify; width:665px; height: 80px; white-space:normal; font-size:12px'><?= $vSCTRDESCRICAO;?><br /><br />VENCIMENTO: <?= $vDVENCIMENTO;?></div></pre>
	<div style='clear: both;height:24px'></div>
	<div style='float:left; padding-left:87px;'><?= date('d').' '.date('m').' 20'.date('y');?></div>
	<div style='clear: both;height:43px'></div>
	<div style='float:left; padding-left:362px; font-size:10px'>Impresso por: <?= $_SESSION['SS_NOME_USUARIO'];?></div>
</div>  