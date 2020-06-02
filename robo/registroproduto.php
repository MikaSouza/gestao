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

			$dataset['vICLICODIGO']             = $cliente->getClicodigo();
			$dataset['vIRGPCONSULTOR'] 	        = 0;
			$dataset['vIRGPPASTA'] 	   			= $this->data['pasta'];			
			$dataset['vIRGPPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vSRGPDATAPROCESSO'] 	= $this->data['data'];
			$dataset['vIRGPMOTIVOCANCELAMENTO'] = $this->getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'PROCESSOS - CANCELAMENTO');   
			$dataset['vSRGPOBSERVACAO'] 	    = $this->data['obs'];
			if ($this->data['dtincl'] != '')
				$dataset['vSRGPDATA_INC']       	= $this->data['dtincl'];			
			else
				$dataset['vSRGPDATA_INC']       	= date('Y-m-d H:i:s');
			$dataset['vIRGPCODIGOPRODUTO'] 	   			= $this->getTable($this->data['codprod'], 'REGISTROXPRODUTO - ORGAO');	
			$dataset['vSRGPPRODUTO'] 	        = $this->data['produto'];
			$dataset['vSRGPMARCA'] 		= $this->data['marca'];
			
			
			$dataset['vICTRCODIGO']   	        = $this->data['numero_contrato']; 

			
		
			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'RGP',
	            'tabela'  => 'REGISTROPRODUTO',
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
	$invoices = $db->db_query("SELECT * FROM marpaprodutos
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
