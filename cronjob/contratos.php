<?php
require_once 'import.php';
require_once 'client.php';
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
$db = new Import();

class Invoice
{
	private $data;

	public function __construct($invoice)
	{
		$invoice = array_map('utf8_encode', $invoice);
		$invoice = array_map('trim', $invoice);
		$this->data = $invoice;
	}

	public function import()
	{
		$dataset = array();

			$cliente = new Client($this->data['sigla']);
			
			if ($this->data['dtlanc'] != '')
				$dataset['vSCTRDATA_INC']       = $this->data['dtlanc'];
			else
				$dataset['vSCTRDATA_INC']       = date('Y-m-d H:i:s');
			
			$dataset['vIEMPCODIGO'] 		= $this->getEmpresa();		
			$dataset['vICTRTIPOCONTRATO']   = $this->getTable($this->data['tipolan'], 'CONTAS A RECEBER - CENTRO DE CUSTO');
			$dataset['vICTRNROCONTRATO']    = $this->data['numero'];
			$dataset['vSCTRDATACANCELAMENTO'] = $this->data['dtcancel'];
			$dataset['vSCTRDATATERMINO']    = $this->data['dttermctr'];
			$dataset['vICTRDIAFATURAMENTO'] = $this->data['diavenc'];		
			$dataset['vICTRPRAZO']			= $this->data['numparc'];
			$dataset['vICLICODIGO']         = $cliente->getClicodigo();			
			$dataset['vSCTRVALORPARCELA']   = $this->data['valparc'];
			$dataset['vSCTRCOMISSAO'] 	    = $this->data['comissao'];
			$dataset['vSCTRVALORCOMISSAO']  = $this->data['valcomis'];
			$dataset['vSCTRPERCJUROS'] 	    = $this->data['percjuros'];
			$dataset['vSCTRPERCMULTA']      = $this->data['percmulta'];
			$dataset['vSCTRDESCRICAO'] 		= $this->data['obs'];			
			$dataset['vSCTRDATAAINICIO']    = $this->data['dtcontato'];
			$dataset['vICTRMOEDA'] 		    = $this->getTable($this->data['tipomoeda'], 'CONTAS A RECEBER - MOEDA');
			$dataset['vSCTRDATAENVIO']      = $this->data['dtenvio'];	
			$dataset['vICTRVENDEDOR']       = $this->getConsultor(); 
			$dataset['vICTRPOSICAO'] 	    = $this->getTable($this->data['codstatus'], 'CONTRATOS - POSICAO');
						
						
		//	$dataset['vICTRUSU_INC']        = 1; 

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'CTR',
	            'tabela'  => 'CONTRATOS',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}

	public function getConsultor()
	{
		$consultores = array(
			1    => 0,  // "VALDOMIRO G. S.                         "
			383  => 0,  // "CRM RS CLIENTES                         "
			221  => 0,  // "COMERCIAL N/INTERESSE                   "
			33   => 80, // "Edilson Brazil                          "
			20   => 0,  // "MICHAEL SILVA SOARES                    "
			375  => 0,  // "CRM RS PROSP                            "
			361  => 0,  // "CRM PR                                  "
			396  => 0,  // "CRM N/INTERESSE                         "
			47   => 79, // "Martinha Borghetti                      "
			426  => 61, // "Caroline Pires                          "
			87   => 82, // "Izilda                                  "
			1007 => 0,  // "Janaina Ferreira                        "
			448  => 0,  // "CRM INATIVOS                            "
			1021 => 0,  // "Diretoria Tributário                    "
			1025 => 0,  // "Gustavo Barbosa                         "
			1028 => 64, // "Fernanda Vargas                         "
			1029 => 0,  // "Leandro Rezende                         "
			1032 => 0,  // "André Vanacor                           "
			1036 => 45, // "Chiara Maria Lage                       "
			1037 => 0,  // "Elieser Lima Oliveira                   "
			67   => 81, // "Juslane                                 "
			1015 => 63, // "Diana Pimentel                          "
			1035 => 68, // "Jessica Peres                           "
			1004 => 0,  // "Adriana                                 "
			1019 => 0,  // "Nelson de Oliveira                      "
			153  => 78, // "Simone Souza                            "
			1020 => 65, // "Guilherme Popko                         "
			1040 => 0,  // "Ismael Decol                            "
			1038 => 40, // "Amanda Estefani da Silva Bocarite -     "
			1042 => 0,  // "Lisiane Vargas                          "
			1041 => 72, // "Caroline Lima Troleis                   "
		);

		return array_key_exists($this->data['codigovendedor'], $consultores) ? $consultores[$this->data['codigovendedor']] : 0;
	}

	public function getUsuario()
	{
		switch (strtoupper($this->data['usuario'])) {
			case 'ANDRE TEER':
				return 4;
				break;
			case 'CAROLINE':
				return 43;
				break;
			default:
				return 1;
				break;
		}
	}

	public function getBanco()
	{
		$bancos = array(
			56  => 5,  //"Factoring - Salies Lima"
			55  => 2,  //"Factoring - Banco Virtual"
			88  => 0,  //"Ômega - Banco Virtual"
			3   => 3,  //Não cadastrado
			238 => 8,  //"Marpa Virtual"
			237 => 7,  //"Bradesco - Maringá"
			1   => 1,  //"Bradesco - Benjamin Constant"
			2   => 9,  //"Marpa"
			4   => 10, //"Dolar s/ Registro"
			5   => 4,  //"OITO 1"
			104 => 6,  //"Dolar c/ Registro"
			105 => 6,  //"Caixa Econômica Federal"
		);

		return array_key_exists($this->data['banco'], $bancos) ? $this->data['banco'] : 0;
	}

	public function getEmpresa()
	{
		$empresas = array(
			" " => 2,//Marpa - Matriz
			"1" => 2,//Marpa - Matriz
			"2" => 2,//Oito - Não Usar
			"3" => 5,//Marpa Virtual
			"4" => 1,//Tributario - Não Usar
			"5" => 9,//Marpa - Filial (Nova)
		);

		return (array_key_exists($this->data['codempfil'], $empresas)) ? $empresas[$this->data['codempfil']] : 0;
	}

	// /var/www/html/import/guias.php

	public function getTable($codigolegado, $tipo)
	{
		$response = consultaComposta(array(
	        'query' => "SELECT
	                        TABCODIGO
	                    FROM
	                        TABELAS
	                    WHERE
	                        TABTIPO = ? AND
	                        TABCOMPLEMENTO = ?",
	        'parametros' => array(
	            array($tipo, PDO::PARAM_STR),
	            array($codigolegado, PDO::PARAM_INT),
	        )
	    ));

    	return ($response['quantidadeRegistros'] > 0) ? $response['dados'][0]['TABCODIGO'] : 0;
	}
}
/*
$pagina = 1;
while(true){
	$limit = ($pagina-1)*1000;*/
	$invoices = $db->db_query("SELECT * FROM marpagold");
	if (count($invoices) == 0) {
		die;
	}
	foreach ($invoices as $invoice) {
		$insert = new Invoice($invoice);
		$insert->import();
	}
	$pagina++;
//}
