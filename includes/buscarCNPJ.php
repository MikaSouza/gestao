<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/

include_once '../twcore/teraware/php/constantes.php';
require_once '../sistema/transaction/transactionTabelas.php';
$cnpj = filterNumber($_GET['vSCNPJ']);

//validar CNPJ
if (!valida_CNPJ($cnpj)){
	$vSMSG = 'CNPJ inválido, verifique o número digitado.';
	$vSBloqueia = 'S';	
	$vARetorno = [
		'vSMSG' => $vSMSG,
		'vSBloqueia' => $vSBloqueia				
	];
	echo json_encode($vARetorno);
	//pre($vARetorno);
	return;
} else {

	// pesquisar se cliente existe
	$Sql = "SELECT
				c.CLICODIGO,				
				c.CLICNPJ,
				c.CLINOMEFANTASIA,
				c.CLIDATA_INICIO_ATIVIDADES,
				c.CLIFONE,
				c.CLIEMAIL,
				c.CLIREGIMETRIBUTARIO,
				c.CLISITUACAORECEITA,
				c.CLIRESPONSAVEL
			FROM
				CLIENTES c
			WHERE c.CLISTATUS = 'S' AND
				c.CLICNPJ = '".formatar_cnpj($cnpj)."'";	
	//echo $Sql;				
	$vConexao = sql_conectar_banco(vGBancoSite);
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,false);
	$vICLICODIGO = 0;
	while($reg_post = sql_retorno_lista($RS_POST)){
		$vICLICODIGO  = $reg_post['CLICODIGO'];				
		
		if ($reg_post['CLIRESPONSAVEL'] == $_SESSION['SI_USUCODIGO']) {// liberado
			$vSMSG = 'Cliente pertence a sua carteira liberado para uso.';
			$vSBloqueia = 'N';
			//return $vSMSG;
		} else { // Bloqueado
			$vSMSG = 'Cliente já cadastrado na base!, bloqueado para uso.';
			//return $vSMSG;
			$vSBloqueia = 'S';
		}		
		
		if (($reg_post['CLISITUACAORECEITA'] == 'I') || ($reg_post['CLISITUACAORECEITA'] == 'B')){
			$vSMSG = 'ATENÇÃO - Cliente consta com situação: '.($reg_post['CLISITUACAORECEITA'] == 'I' ? 'INAPTA' : 'BAIXADA');
		}
		$vSSql = "SELECT ENDLOGRADOURO, ENDNROLOGRADOURO, 
				ENDCOMPLEMENTO, ENDBAIRRO, ENDCEP, CIDDESCRICAO, 
				ESTSIGLA, E.ESTCODIGO, E.CIDCODIGO
			FROM ENDERECOS E 
			LEFT JOIN TABELAS T ON T.TABCODIGO = E.TABCODIGO 
			LEFT JOIN ESTADOS U ON U.ESTCODIGO = E.ESTCODIGO
			LEFT JOIN CIDADES C ON C.CIDCODIGOIBGE = E.CIDCODIGO			
			WHERE E.CLICODIGO = ".$vICLICODIGO." AND E.ENDSTATUS = 'S' AND E.TABCODIGO = 426
			LIMIT 1";
								
		$vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
		while($registro = sql_retorno_lista($vAResultDB)) {
			$vSENDLOGRADOURO = $registro['ENDLOGRADOURO'];
			$vSENDNROLOGRADOURO = $registro['ENDNROLOGRADOURO'];
			$vSENDCOMPLEMENTO = $registro['ENDCOMPLEMENTO'];
			$vSENDBAIRRO = $registro['ENDBAIRRO'];
			$vSENDCEP = $registro['ENDCEP'];
			$vSCIDDESCRICAO = $registro['CIDDESCRICAO'];	
			$vSESTSIGLA = $registro['ESTSIGLA'];	
			$vIESTCODIGO = $registro['ESTCODIGO'];	
			$vICIDCODIGO = $registro['CIDCODIGO'];
			
		 }	
		$vARetorno = [
			'vSMSG' => $vSMSG,
			'vSBloqueia' => $vSBloqueia,
			'vICLICODIGO'		 => $reg_post['CLICODIGO'],
			'vSCLISITUACAORECEITA'  	 => $reg_post['CLISITUACAORECEITA'],
			'vDCLIDATA_INICIO_ATIVIDADES'  => formatar_data($reg_post['CLIDATA_INICIO_ATIVIDADES']),	
			'vSCLINOME'	 => $reg_post['CLINOMEFANTASIA'],			
			'vICLIREGIMETRIBUTARIO' => $reg_post['CLIREGIMETRIBUTARIO'],		
						
			'vSCLIFONE'  		 => $reg_post['CLIFONE'],
			'vSCLIEMAIL'  		 => $reg_post['CLIEMAIL'],
			
			"vSENDLOGRADOURO"    => $vSENDLOGRADOURO,
			"vSENDNROLOGRADOURO" => $vSENDNROLOGRADOURO,
			"vSENDCOMPLEMENTO"   => $vSENDCOMPLEMENTO,
			"vSENDBAIRRO"        => $vSENDBAIRRO,
			"vSENDCEP"           => $vSENDCEP,
			"vSCIDDESCRICAO"     => $vSCIDDESCRICAO,
			"vSESTSIGLA"         =>$vSESTSIGLA,
			"vIESTCODIGO"        =>$vIESTCODIGO,
			"vICIDCODIGO"        =>$vICIDCODIGO
			
		];
		echo json_encode($vARetorno);
		//pre($vARetorno);
		return;
	}	
}
if ($vICLICODIGO == 0) { // buscar receita
	$retorno = file_get_contents("http://ws.hubdodesenvolvedor.com.br/v2/cnpj/?cnpj={$cnpj}&token=61855550lsuvqSMoBf111678320");
	$retorno = json_decode($retorno, true);
		
	if ($retorno['return'] == 'OK') { // sucesso
		//print_r($retorno); 
				
		$tipo 			 		= $retorno['result']['tipo'];
		$abertura    	 		= formatar_data_banco($retorno['result']['abertura']);
		$nome     		 		= $retorno['result']['nome'];
		$fantasia        		= $retorno['result']['fantasia'];
		$porte         	 		= $retorno['result']['porte'];
		$cnae_principal_code  	= $retorno['result']['atividade_principal']['code'];
		$cnae_principal_text  	= $retorno['result']['atividade_principal']['text'];
		$cnae_secundaria_code 	= $retorno['result']['atividades_secundarias']['code'];
		$cnae_secundaria_text 	= $retorno['result']['atividades_secundarias']['text'];
		$natureza_juridica  	= $retorno['result']['natureza_juridica'];
		$logradouro         	= $retorno['result']['logradouro'];
		$numero             	= $retorno['result']['numero'];
		$complemento        	= $retorno['result']['complemento'];
		$cep         	    	= $retorno['result']['cep'];
		$bairro             	= $retorno['result']['bairro'];
		$municipio          	= $retorno['result']['municipio'];
		$uf         	    	= $retorno['result']['uf'];
		$email         	    	= $retorno['result']['email'];
		$telefone           	= $retorno['result']['telefone'];
		$situacao           	= $retorno['result']['situacao'];
		$motivo_situacao_cadastral = $retorno['result']['motivo_situacao_cadastral'];
		$socio		        	= $retorno['result']['quadro_socios'][0];
		$socio2		        	= $retorno['result']['quadro_socios'];				
		
		if ($fantasia == '')
			$fantasia = $nome;
		else	
			$fantasia = $fantasia;
					
		if ($situacao == 'ATIVA') {
			$situacao = 'A';
			$vISituacao = 20;			
		} else if ($situacao == 'BAIXADA') {
			$situacao = 'B';
			$vISituacao = 21;			
		}else if ($situacao == 'INAPTA') {
			$situacao = 'I';
			$vISituacao = 22;	
		}else if ($situacao == 'SUSPENSA') {
			$situacao = 'S';
			$vISituacao = 23;	
		}	
		
		if (($situacao == 'I') || ($situacao == 'B')){
			$vSMSG = 'ATENÇÃO - Cliente consta com situação: '.($situacao == 'I' ? 'INAPTA' : 'BAIXADA');
			$vSBloqueia = 'N';
		} else {
			$vSMSG = 'Cliente atualizado conforme dados da Receita Federal';
			$vSBloqueia = 'N';
		}			
		//'vICLIPORTE'		 => getTabela($porte, 'EMPRESAS - PORTE'),
		$IdUf = getIdUf(trim($uf));
		$vARetorno = [
			'vSMSG' => $vSMSG,
			'vSBloqueia' => $vSBloqueia,
			'vICLICODIGO'		 => '',
			'vICLISITUACAORECEITA'  	 => $vISituacao,
			'vDCLIDATA_INICIO_ATIVIDADES'  => $abertura,	
			'vSCLINOMEFANTASIA'	 		 => $fantasia,		
			'vSCLIRAZAOSOCIAL'	 		 => $nome,		
			'vSCLIFONE'  		 => $telefone,
			'vSCLIEMAIL'  		 => $email,			
			"vITLOCODIGO"        => 41,				
			"vSENDLOGRADOURO"    => $logradouro,
			"vSENDNROLOGRADOURO" => $numero,
			"vSENDCOMPLEMENTO"   => $complemento,
			"vSENDBAIRRO"        => $bairro,
			"vSENDCEP"           => formatar_cep(filterNumber($cep)),
			"vIESTCODIGO"        => $IdUf,
			"vICIDCODIGO"        => getIdCidade($municipio, $IdUf)								
			
		];
		//echo json_encode($vARetorno);
		echo json_encode($vARetorno);
		//pre($vARetorno);
		return;					
		
	}else {
		echo 'erro';
		print_r($retorno);
		return;
	}		
}		

function getIdCidade($pSNomeCidade, $pIEstCodigo=null){ 
	if($pSNomeCidade != ''){
		$pSNomeCidade = strtoupper($pSNomeCidade);
		$sql = "SELECT
					CIDCODIGO
				 FROM
					CIDADES
				 WHERE UPPER(CIDDESCRICAO) LIKE ?
				 AND ESTCODIGO = ?
				 AND CIDSTATUS = 'S' ";				
	    $arrayQuery = array(
		  'query' => $sql,
		  'parametros' => array(
								array("%$pSNomeCidade%", PDO::PARAM_STR),
								array("$pIEstCodigo", PDO::PARAM_INT)
							)
	   );
	   $list = consultaComposta($arrayQuery);
	   if($list['quantidadeRegistros'] > 0)
		   return $list['dados'][0]['CIDCODIGO'];
	   else
		   return 0;
	}
}

function getIdUf($pSNomeEstado){
	if($pSNomeEstado != ''){
		$pSNomeEstado = strtoupper($pSNomeEstado);
		$sql = "SELECT
					ESTCODIGO
				FROM
					ESTADOS
				 Where (UPPER(ESTSIGLA) LIKE ? OR UPPER(ESTDESCRICAO) LIKE ?) ";
	   $arrayQuery = array(
		  'query' => $sql,
		  'parametros' => array(
								array("%$pSNomeEstado%", PDO::PARAM_STR),
								array("%$pSNomeEstado%", PDO::PARAM_STR)
							)
	   );
	   $list = consultaComposta($arrayQuery);
	   if($list['quantidadeRegistros'] > 0)
		   return $list['dados'][0]['ESTCODIGO'];
	   else
		   return 0;
	}
}			