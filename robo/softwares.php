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
			$dataset['vIPGCCONSULTOR'] 	        = 0;
			$dataset['vSPGCVIRTUAL'] 	        = ($this->data['virtual'] == 1) ? "S" : "N";
			$dataset['vIPGCPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vIPGCTIPOPROTECAO']       = $this->getTable($this->data['tipolan'], 'MARCAS - TIPO DE PROTECAO');
			$dataset['vICTRCODIGO']   	        = $this->data['numero_contrato']; 
			$dataset['vIPGCFOLHA'] 		        = $this->data['folha'];
			$dataset['vSPGCTITULO'] 	        = $this->data['titmarca'];
			$dataset['vIPGCMOTIVOCANCELAMENTO'] = $this->getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'MARCAS - TIPO DE PROTECAO');   
			$dataset['vSPGCDEPOSITANTE'] 		= $this->data['depositante'];
			$dataset['vSPGCOBSERVACAO'] 	    = $this->data['obs'];
			$dataset['vSPGCDATAPROCESSO'] 	    = $this->data['data'];
			$dataset['vIPGCPASTA'] 	   			= $this->data['pasta'];
			$dataset['vIPGCPASTAOLD'] 	   	    = $this->data['old_pasta'];			
			
			$dataset['vSPGCDATA_INC']       	= date('Y-m-d H:i:s');

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'PGC',
	            'tabela'  => 'PROGRAMACOMPUTADOR',
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
	$invoices = $db->db_query("SELECT * FROM marpasoftwares
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
