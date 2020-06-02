<?php
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);*/

include_once '../twcore/teraware/php/constantes.php';
require_once '../cadastro/transaction/transactionTabelas.php';
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
				c.FAVCODIGO,				
				c.FAVCNPJ,
				c.FAVNOMEFANTASIA,
				c.FAVRAZAOSOCIAL,
				c.FAVDATA_INICIO_ATIVIDADES,
				c.FAVFONE,
				c.FAVEMAIL,
				c.FAVSITUACAORECEITA
			FROM
				FAVORECIDOS c
			WHERE c.FAVSTATUS = 'S' AND
				c.FAVCNPJ = '".formatar_cnpj($cnpj)."'";	
	//echo $Sql;				
	$vConexao = sql_conectar_banco(vGBancoSite);
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,false);
	$vICLICODIGO = 0;
	while($reg_post = sql_retorno_lista($RS_POST)){
		$vICLICODIGO  = $reg_post['CLICODIGO'];				
		$vSMSG = 'Já existe um cadastro com este CNPJ.';
		$vSBloqueia = 'S';	
		$vARetorno = [
			'vSMSG' => $vSMSG,
			'vSBloqueia' => $vSBloqueia				
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
		$abertura    	 		= $retorno['result']['abertura'];
		$nome     		 		= $retorno['result']['nome'];
		$fantasia        		= $retorno['result']['fantasia'];		
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
		$vARetorno = [
			'vSMSG' => $vSMSG,
			'vSBloqueia' => $vSBloqueia,
			'vIFAVCODIGO'		 => '',
			'vIFAVSITUACAORECEITA'  	 => $vISituacao,
			'vDFAVDATA_INICIO_ATIVIDADES'  => formatar_data_banco($abertura),	
			'vSFAVRAZAOSOCIAL'	 		 => $nome,						
			'vSFAVNOMEFANTASIA'  		 => $fantasia,
			'vSFAVFONE'  		 => $telefone,		
			'vSFAVEMAIL'  		 => $email,						
			"vSENDLOGRADOURO"    => $logradouro,
			"vSENDNROLOGRADOURO" => $numero,
			"vSENDCOMPLEMENTO"   => $complemento,
			"vSENDBAIRRO"        => $bairro,
			"vSENDCEP"           => formatar_cep(filterNumber($cep)),
			"vIESTCODIGO"        => getIdUf(trim($uf)),
			"vICIDCODIGO"        => getIdCidade($municipio)								
			
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
				 WHERE CIDDESCRICAO LIKE ?
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