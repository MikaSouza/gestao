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

			$dataset['vICLICODIGO']     = $cliente->getClicodigo();
			$dataset['vIEMPCODIGO'] 	= 1;			
			$dataset['vSAUDANTIGO'] 	= $this->data['antigo'];
			$dataset['vSAUDNOVO'] 		= $this->data['novo'];
			$dataset['vSAUDCAMPO'] 		= $this->data['campo']; 
			$dataset['vIAUDMENU'] 		= 1;
			$dataset['vIAUDIDVINCULO'] 	= 1;
			
			if ($this->data['data'] != '')
				$dataset['vSAUDDATA_INC']       = $this->data['data'].' '.$this->data['hora'];
			else
				$dataset['vSAUDDATA_INC']       = date('Y-m-d H:i:s');					
			
			// $dataset['vSCXCUSU_INC']       = 1;

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'AUD',
	            'tabela'  => 'AUDITORIA',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}	
	
}

$invoices = $db->db_query("SELECT * FROM marpaclientehist LIMIT 1000");
if (count($invoices) == 0) {
	die;
}
foreach ($invoices as $invoice) {
	$insert = new Invoice($invoice);
	$insert->import();
}

