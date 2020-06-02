<?php
require_once 'import.php';
require_once 'client.php';
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

			$dataset['vICLICODIGO']         = $cliente->getClicodigo();
			$dataset['vIEMPCODIGO'] 		= $this->getEmpresa();
			$dataset['vITABCENTROCUSTO'] 		   = $this->getTable($this->data['tipolan'], 'CONTAS A RECEBER - CENTRO DE CUSTO');
			$dataset['vICXCNUMEROCA']         = $this->data['numero'];
			$dataset['vSCXCDATA'] 	   = $this->data['data'];
			$dataset['vSCXCDATAENVIO'] 		   = $this->data['dtenvio'];
			$dataset['vICXCCLASSE'] 		   = $this->data['classe'];
			$dataset['vSCXCDESCRICAO'] 	   = $this->data['obs'];
			$dataset['vSCXCVALOR'] 	   = $this->data['valorsv'];
			$dataset['vSCXCOUTRASTAXAS'] 		   = $this->data['outrastaxas'];
			$dataset['vSCXCFIZJUR'] 		   = $this->data['fisjur'];
			$dataset['vSCXCMATRIZ'] 		   = $this->data['matriz'];
			$dataset['vSCXCJURIDICO'] 		   = $this->data['juridico'];
			$dataset['vSCXCTECNICO'] 		   = $this->data['tecnico'];
			$dataset['vICXCCONSULTOR'] 		   = $this->getConsultor();
			$dataset['vSCXCVALORCOMISSAO']  = $this->data['valcomis'];
			if ($this->data['dtincl'] != '')
				$dataset['vSCXCDATA_INC']       = $this->data['dtincl'];
			else
				$dataset['vSCXCDATA_INC']       = date('Y-m-d H:i:s');
			$dataset['vICXCDIAVENCIMENTO'] = $this->data['diavenc'];
			$dataset['vICTRCODIGO']         = $this->data['numparc'];
			$dataset['vSCXCDATATERMINO']   = $this->data['dttermctr'];
			$dataset['vSCXCMOEDA'] 	   = $this->getTable($this->data['tipomoeda'], 'CONTAS A RECEBER - MOEDA');
			$dataset['vSCXCDATARECEBIMENTO'] = $this->data['dtreceb'];
			
						
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
	
}
$pagina = 1;
while(true){
	$limit = ($pagina-1)*1000;
	$invoices = $db->db_query("SELECT sigla, siglarel FROM marpaclienterel
							LIMIT 1000
							OFFSET {$limit}");
	if (count($invoices) == 0) {
		die;
	}
	foreach ($invoices as $invoice) {
		$insert = new Invoice($invoice);
		$insert->import();
	}
	$pagina++;
}
