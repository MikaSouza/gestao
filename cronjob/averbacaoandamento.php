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

			//$cliente = new Client($this->data['sigla']);

			$dataset['vIAXAPASTA'] 	   			= $this->data['pasta'];
			$dataset['vSAXADATA'] 	= $this->data['data'];
			$dataset['vIAXASEQUENCIAL'] 		        = $this->data['seq'];
			$dataset['vIAXARPI'] 		        = $this->data['rpi'];
			$dataset['vIAXADESPACHO'] 		        = $this->data['despacho'];
			$dataset['vIAXAHISTORICOSTATUS'] = $this->getTable(($this->data['codstatusandamento'] == -1) ? 99 : $this->data['codstatusandamento'], 'PROCESSOS - STATUS');   
			$dataset['vSAXAOBSERVACAO'] 	    = $this->data['obs'];
			
			if ($this->data['dtincl'] != '')
				$dataset['vSAXADATA_INC']       	= $this->data['dtincl'];			
			else
				$dataset['vSAXADATA_INC']       	= date('Y-m-d H:i:s');	


			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'AXA',
	            'tabela'  => 'AVERBACAOXANDAMENTOS',
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
	$invoices = $db->db_query("SELECT * FROM marpaaverbacaoand
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
