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
			$dataset['vIEMPCODIGO'] 		= 1;
			$dataset['vSCHGDATA'] 	   		= $this->data['dtincl'];
			$dataset['vSCHGHISTORICO'] 		= $this->data['obs'];
			$dataset['vICHGTIPO'] 	   		= getTable($this->data['tipoobs'], 'HISTORICO GERAL - TIPO');
			$dataset['vICHGPOSICAO'] 	    = getTable($this->data['tipoobs_status'], 'HISTORICO GERAL - STATUS');
			
			if ($this->data['dtincl'] != '')
				$dataset['vSCHGDATA_INC']       = $this->data['dtincl'].' '.$this->data['horaobs'];
			else
				$dataset['vSCHGDATA_INC']       = date('Y-m-d H:i:s');					
			
			// $dataset['vSCXCUSU_INC']       = 1;

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'CHG',
	            'tabela'  => 'CLIENTESXHISTORICOGERAL',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}	
	
}

$invoices = $db->db_query("SELECT * FROM marpaclienteobs LIMIT 1");
if (count($invoices) == 0) {
	die;
}
foreach ($invoices as $invoice) {
	$insert = new Invoice($invoice);
	$insert->import();
}

