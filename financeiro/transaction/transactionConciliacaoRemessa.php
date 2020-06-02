<?php

include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchConciliacaoRemessa')
    listConciliacaoRemessa($_POST);

function listConciliacaoRemessa($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vICBACODIGO']))
		$where .= 'AND c.CBACODIGO = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSARQUIVOGERADO'])){
		if($_POSTDADOS['FILTROS']['vSARQUIVOGERADO'] == 'S')
			$where .= "AND c.ARQUIVOGERADO = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSARQUIVOGERADO'] == 'N')
			$where .= "AND c.ARQUIVOGERADO = 'N' ";
	}else
		$where .= "AND c.ARQUIVOGERADO = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDATAVENCIMENTOINICIAL']))
		$where .= 'AND C.CLIDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDATAVENCIMENTOFINAL']))
		$where .= 'AND C.CLIDATA_INC <= ? ';

	return;
	$sql = "SELECT
				cp.*,
				c.CLINOME,
				e.EMPNOMEFANTASIA,
				u.USUNOME AS CONSULTOR,
				t1.TABDESCRICAO AS CENTROCUSTO,
				t3.TABDESCRICAO AS PLANO
			FROM
				CONTASARECEBER cp 
			LEFT JOIN CLIENTES c ON c.CLICODIGO = cp.CLICODIGO	
			LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = cp.EMPCODIGO	
			LEFT JOIN USUARIOS u ON	cp.CTRCONSULTOR = u.USUCODIGO
			LEFT JOIN TABELAS t1 ON	t1.TABCODIGO = cp.TABCENTROCUSTO
			LEFT JOIN TABELAS t3 ON	t3.TABCODIGO = cp.TABPLANOCONTAS
			WHERE
				cp.CTRSTATUS = 'S'
				".	$where	."
			LIMIT 20 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vICBACODIGO'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICBACODIGO'], PDO::PARAM_INT);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$vSCLINOME = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$vSCLINOME%", PDO::PARAM_STR);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDATAVENCIMENTOINICIAL'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDATAVENCIMENTOINICIAL']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDATAVENCIMENTOFINAL'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDATAVENCIMENTOFINAL']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
				
	$result = consultaComposta($arrayQuery);
	$vITotalRetorno = $result['quantidadeRegistros'];
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="height: 430px; overflow: auto; width: 100%">
		<thead>
			<tr>
				<th>Sigla</th>
				<th>Cliente/Empresa</th>
				<th>CPF/CNPJ</th>
				<th>Representante</th>
				<th>Data Vencimento</th>
				<th>Data Inclusão</th>	
				<th></th>				
			</tr>
		</thead>
	<?php foreach ($result['dados'] as $result) : ?>

		<tr>	
			<td align="right"><a href="method=update&filial=" class="mr-2" title="Editar Registro" alt="Editar Registro"><?= $result['CLISEQUENCIAL'];?></a></td>
			<td align="left"><?= $result['CLINOME'];?></td> 
			<td align="left"><?= $result['CNPJCPF'];?></td>
			<td align="left"><?= $result['COMERCIAL'];?></td>
			<td align="left"><?= $result['TIPOCADASTRO'];?></td>
			<td align="center"><?= formatar_data_hora($result['CLIDATA_INC']);?></td>			
			<td align="left"><a href="cadSolicitacaoCRM.php?method=insert&oidcliente=<?= $result['CLICODIGO'];?>"><button type="button" class="btn btn-secondary waves-effect">Abrir Solicitação</button></a></td>
		</tr>

	<?php endforeach; ?>
	</table>	
	<a href="cadProspeccao.php?method=insert">
	<button type="button" class="btn btn-secondary waves-effect">Abrir Nova Prospecção</button>
	</a>
<?php	
	return $result;
}

function insertUpdateContasReceber($_POSTDADOS, $pSMsg = 'N'){
	
	$dadosBanco = array(
		'tabela'  => 'CONTASARECEBER',
		'prefixo' => 'CTR',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
		);
	$id = insertUpdate($dadosBanco);		
	include_once('transactionContasReceberxParcelas.php');
	$dadosCXP = array(
		'vICTRCODIGO'  => $id,
		'vICXPFORMAPAGAMENTO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vSCXPNUMERO'  => $_POSTDADOS['vSCTRNRODOCUMENTO'],
		'vDCXPDATAVENCIMENTO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vMCXPVALOR'  => $_POSTDADOS['vHCXPVALOR'],
		'vSCXPDESCRICAO'  => $_POSTDADOS['vHCXPDATAVENCIMENTO'],
		'vDCXPDATAPAGAMENTO'  => $_POSTDADOS['vHCXPDATAPAGAMENTO'],
		'vICBACODIGO'  => $_POSTDADOS['vICBACODIGO'],
		'vSCXPMODOFATURAMENTO'  => $_POSTDADOS['vICTRMODOFATURAMENTO'],
		'vSCXPPROTESTO'  => $_POSTDADOS['vHCXPPROTESTO'],
		'vSCXPDIRETORIA'  => $_POSTDADOS['vHCXPDIRETORIA'],
		'vSCXPAUTCLIENTE'  => $_POSTDADOS['vHCXPAUTCLIENTE'],
		'vSCXPAUTCOMISSAO'  => $_POSTDADOS['vHCXPAUTCOMISSAO'],
		'vSCXPEXTERIOR'  => $_POSTDADOS['vHCXPEXTERIOR'],
		'vICXPPOSICAO'  => $_POSTDADOS['vHCXPPOSICAO'],
		'vICXPFORMACOBRANCA'  => $_POSTDADOS['vHCXPFORMACOBRANCA'],
		'vICXPCLASSIFICACAO'  => $_POSTDADOS['vHCXPCLASSIFICACAO'],		
	);	
	insertUpdateContasReceberxParcelas($dadosCXP, 'N'); 
	
	if ($_POSTDADOS['vHGERARCA'] == 'S'){// CA
		include_once('../comercial/transactionCAs.php');
		if ($_POSTDADOS['vHNUMEROCA1'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA1'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}	
		if ($_POSTDADOS['vHNUMEROCA2'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA2'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA3'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA3'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA4'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA4'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vSXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
		if ($_POSTDADOS['vHNUMEROCA5'] != ''){
			$dadosBancoCA = array(
				'vICXCCODIGO' 	  => '',
				'vICXCNUMEROCA'   => $_POSTDADOS['vHNUMEROCA5'],	 		
				'vIEMPCODIGO'  	  => 1,
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vICTRCODIGO'     => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vSCXCDATA'       => date('Y-m-d'),
				'vSCXCDESCRICAO'   => $_POSTDADOS['vSCTRDESCRICAO'],
				'vICXCMOEDA'     => $_POSTDADOS['vICTRMOEDA'],
				'vMCXCVALOR'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICXCCONSULTOR'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateCAs($dadosBancoCA, 'N'); 
		}
	}
	if ($_POSTDADOS['vHGERARCONTRATO'] == 'S'){// CONTRATO
		include_once('../comercial/transactionContratos.php');
		if ($_POSTDADOS['vHCTRCONTRATO'] != ''){
			$dadosBancoCTR = array(
				'vICTRCODIGO' 	  => '',
				'vICLICODIGO'     => $_POSTDADOS['vICLICODIGO'],
				'vIEMPCODIGO'  	  => 1,
				'vICTRNROCONTRATO'   => $_POSTDADOS['vHCTRCONTRATO'],	 		
				'vSCTRDESCRICAO'   	=> $_POSTDADOS['vSCTRDESCRICAO'],				
				'vICTRTIPOCONTRATO'  => $id,
				'vITABCENTROCUSTO'   => $_POSTDADOS['vITABCENTROCUSTO'],
				'vDCTRDATAAINICIO'       => $_POSTDADOS['vHCTRDATAINICIO'],
				'vDCTRDATATERMINO'       => $_POSTDADOS['vHCTRDATAFINAL'],
				'vICTRTIPO'     	=> $_POSTDADOS['vICTRMOEDA'],
				'vMCTRVALORCOBERTURA'     => $_POSTDADOS['vMCTRVALORARECEBER'],
				'vICTRATENDENTE'     => $_POSTDADOS['vICTRCONSULTOR']
			);
			insertUpdateContratos($dadosBancoCTR, 'N'); 
		}
	}
	
	return $id; 
}

function fill_ContasReceber($pOid){
	$SqlMain = 'SELECT c.*
				 From CONTASARECEBER c
				 Where c.CTRCODIGO = '.$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function geraHeaderRemessaBB() {
	global $db;
	$header = "";
	//ID: 01
	//POSI��O: 001 - 001
	//TIPO: NUM�RICO | LENGHT: 1
	//DESCRI��O: IDENTIFICA��O DO REGISTRO HEADER
	$header = '0';
	//ID: 02
	//POSI��O: 002 - 002
	//TIPO: NUM�RICO | LENGHT: 1
	//DESCRI��O: TIPO DE OPERA��O
	$header.= '1';
	//ID: 03
	//POSI��O: 003 - 009
	//TIPO: ALFANUM�RICO | LENGHT: 7
	//DESCRI��O: IDENTIFICA��O POR EXTENSO DO TIPO DE OPERA��O (REMESSA OU TESTE)
	$header.= 'REMESSA';
	//ID: 04
	//POSI��O: 010 - 011
	//TIPO: NUM�RICO | LENGHT: 2
	//DESCRI��O: IDENTIFICA��O DO TIPO DE SERVI�O
	$header.= '01';
	//ID: 05
	//POSI��O: 012 - 019
	//TIPO: ALFANUM�RICO | LENGHT: 8
	//DESCRI��O: IDENTIFICA��O POR EXTENSO DO TIPO DE SERVI�O
	$header.= 'COBRANCA';
	//ID: 06
	//POSI��O: 020 - 026
	//TIPO: ALFANUM�RICO | LENGHT: 7
	//DESCRI��O: COMPLEMENTO DO REGISTRO
	$header.= '       ';
	//ID: 07
	//POSI��O: 027 - 030
	//TIPO: NUM�RICO | LENGHT: 4
	//DESCRI��O: PREFIXO DA AG�NCIA: N�MERO DA AG�NCIA ONDE EST� CADASTRADO O CONV�NIO L�DER DA EMPRESA
	$header.= '3334';
	//ID: 08
	//POSI��O: 031 - 031
	//TIPO: ALFANUM�RICO | LENGHT: 1
	//DESCRI��O: D�GITO VERIFICADOR - D.V. - DO PREFIXO DA AG�NCIA
	$header.= '0';
	//ID: 09
	//POSI��O: 032 - 039
	//TIPO: NUM�RICO | LENGHT: 8
	//DESCRI��O: N�MERO DA CONTA CORRENTE: N�MERO DA CONTA ONDE EST� CADASTRADO O CONV�NIO L�DER DA EMPRESA
	$header.= '00004804';
	//ID: 10
	//POSI��O: 040 - 040
	//TIPO: ALFANUM�RICO | LENGHT: 1
	//DESCRI��O: D�GITO VERIFICADOR - D.V. - DA CONTA CORRENTE DA EMPRESA
	$header.= '6';
	//ID: 11
	//POSI��O: 041 - 046
	//TIPO: NUM�RICO | LENGHT: 6
	//DESCRI��O: N�MERO DO CONV�NIO L�DER
	$header.= '086858';
	//ID: 12
	//POSI��O: 047 - 076
	//TIPO: ALFANUM�RICO | LENGHT: 30
	//DESCRI��O: NOME DA EMPRESA
	$header.= 'MARPA CONS ASSESS EMPRES LTDA ';
	//ID: 13
	//POSI��O: 077 - 094
	//TIPO: ALFANUM�RICO | LENGHT: 18
	//DESCRI��O: 001BANCODOBRASIL
	$header.= '001BANCO DO BRASIL';
	//ID: 14
	//POSI��O: 095 - 100
	//TIPO: NUM�RICO | LENGHT: 6
	//DESCRI��O: DATA DA GRAVA��O DA REMESSA
	$header.= date('dmy');
	//ID: 15
	//POSI��O: 101 - 107
	//TIPO: NUM�RICO | LENGHT: 7
	//DESCRI��O: SEQUENCIAL DA REMESSA
	$sql = "SELECT remessa_id FROM marparemessa where (banco = 1)";
	$res = $db->db_query($sql);
	$seq = $res[0]['remessa_id'];
	$seq++;
	$sql = "UPDATE marparemessa SET remessa_id = $seq where (banco = 1)";
	$db->db_query($sql);
	while(strlen($seq)<7) {
		$seq='0'.$seq;
	}
	$header.= $seq;
	//ID: 16
	//POSI��O: 108 - 394
	//TIPO: ALFANUM�RICO | LENGHT: 287
	//DESCRI��O: COMPLEMENTO DO REGISTRO: "BRANCOS"
	$brancos = "";
	while(strlen($brancos)<287) {
		$brancos.= " ";
	}
	$header.= $brancos;
	//ID: 17
	//POSI��O: 395 - 400
	//TIPO: NUM�RICO | LENGHT: 6
	//DESCRI��O: SEQU�NCIAL DO REGISTRO
	$header.= '000001';
	return $header;
}