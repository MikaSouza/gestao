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
			
			if ($this->data['dtincl'] != '')
				$dataset['vSCXCDATA_INC']   = $this->data['dtincl'];
			else
				$dataset['vSCXCDATA_INC']       = date('Y-m-d H:i:s');
			$dataset['vICLICODIGO']         = $cliente->getClicodigo();
			$dataset['vIEMPCODIGO'] 		= $this->getEmpresa();
			$dataset['vITABCENTROCUSTO'] 	= getTable($this->data['tipolan'], 'CONTAS A RECEBER - CENTRO DE CUSTO');
			
			
			$dataset['vICXCNUMEROCA']       = $this->data['numero'];
			$dataset['vSCXCDATA'] 	   		= $this->data['data'];
			$dataset['vSCXCDATAENVIO'] 		= $this->data['dtenvio'];
			$dataset['vICXCCLASSE'] 		= $this->data['classe'];
			$dataset['vSCXCDESCRICAO'] 	    = $this->data['obs'];
			$dataset['vSCXCVALOR'] 	   		= $this->data['valorsv'];
			$dataset['vSCXCOUTRASTAXAS'] 	= $this->data['outrastaxas'];
			$dataset['vSCXCFIZJUR'] 		= $this->data['fisjur'];
			$dataset['vSCXCMATRIZ'] 		= $this->data['matriz'];
			$dataset['vSCXCJURIDICO'] 		= $this->data['juridico'];
			$dataset['vSCXCTECNICO'] 		= $this->data['tecnico'];
			$dataset['vICXCCONSULTOR'] 		= $this->getConsultor();
			$dataset['vSCXCVALORCOMISSAO']  = $this->data['valcomis'];
			
			$dataset['vICXCDIAVENCIMENTO'] = $this->data['diavenc'];
			$dataset['vICTRCODIGO']         = $this->data['numparc'];
			$dataset['vSCXCDATATERMINO']   = $this->data['dttermctr'];
			$dataset['vSCXCMOEDA'] 	   = getTable($this->data['tipomoeda'], 'CONTAS A RECEBER - MOEDA');
			$dataset['vSCXCDATARECEBIMENTO'] = $this->data['dtreceb'];
			
			
			//$dataset['vICXCPROCESSO'] 	   	= $this->data['numero'];			
			//$dataset['vSCXCDATAASSINATURA'] = $this->data['dtab'];
			//$dataset['vSCXCTOTALCOMISSOES'] = $this->data['percomiss'];
			//$dataset['vSCXCTOTALTAXAS'] 	   = $this->data['outrastaxas'];
			//$dataset['vICXCSERVICO'] 		   = $this->data['obs'];

			
			// $dataset['vSCXCUSU_INC']       = 1;

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'CXC',
	            'tabela'  => 'CLIENTESXCA',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
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
	
}

	$invoices = $db->db_query("SELECT * FROM marpaca");
	if (count($invoices) == 0) {
		die;
	}
	foreach ($invoices as $invoice) {
		$insert = new Invoice($invoice);
		$insert->import();
	}
