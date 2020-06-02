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
			
			//$dataset['vICLICODIGO']       = 57458; //$cliente->getClicodigo();
			
			
			$dataset['vIGEDIDLEGADO'] 	  = $this->data['id'];
			$dataset['vIGEDTIPO'] 	  	  = getTable($this->data['id_tipo'], 'GED - TIPO'); $this->data['id_tipo'];
			$dataset['vSGEDNOMEARQUIVO']  = $this->data['arquivo'];
			$dataset['vSGEDDIRETORIO']  = 'upload/digitalizacoes/clientes/';			
			$dataset['vSGEDDATA_INC'] 	  = $this->data['data'].' '.$this->data['hora'];
		//	$dataset['vIGEDUSU_INC'] 	  = 1; //$this->data['usuario'];
			$dataset['vSGEDSTATUS'] 	  = ($this->data['status'] == 9 ? 'N' : 'S');
			$dataset['vIEMPCODIGO'] 	  = 1;
			$dataset['vIMENCODIGO'] 	  = 1;
			$dataset['vIGEDVINCULO'] 	  = 57458;					

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'GED',
	            'tabela'  => 'GED',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}
	
}

	$invoices = $db->db_query("SELECT * FROM digitalizacao_cliente
							where sigla = 166260");
	if (count($invoices) == 0) {
		die;
	}
	foreach ($invoices as $invoice) {
		$insert = new Invoice($invoice);
		$insert->import();
	}
	$pagina++;

