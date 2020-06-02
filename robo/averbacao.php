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
			$dataset['vIAVECONSULTOR'] 	        = 0;
			$dataset['vIAVEPASTA'] 	   			= $this->data['pasta'];
			$dataset['vIAVEPASTAOLD'] 	   	    = $this->data['old_pasta'];	
			$dataset['vIAVEPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vSAVEDATAPROCESSO'] 	= $this->data['data'];
			$dataset['vSAVETITULO'] 	        = $this->data['titulo'];
			$dataset['vIAVEMOTIVOCANCELAMENTO'] = getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'MARCAS - TIPO DE PROTECAO');   
			$dataset['vSAVEDEPOSITANTE'] 		= $this->data['depositante'];
			$dataset['vSAVEOBSERVACAO'] 	    = $this->data['obs'];
			$dataset['vIAVETIPOINPI']           = getTable($this->data['tipoinpi'], 'AVERBACAO - TIPO');
			$dataset['vICTRCODIGO']   	        = $this->data['numero_contrato']; 
			$dataset['vIAVEFOLHA'] 		        = $this->data['folha'];
			$dataset['vSAVEVIRTUAL'] 	        = ($this->data['virtual'] == 1) ? "S" : "N";

			
			$dataset['vSAVEDATA_INC']       	= date('Y-m-d H:i:s');

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'AVE',
	            'tabela'  => 'AVERBACAOINPI',
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
	$invoices = $db->db_query("SELECT * FROM marpaaverbacao
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
