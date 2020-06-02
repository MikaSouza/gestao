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
			$dataset['vICXPPRODUTO']		= $this->getTable($this->data['assunto'], 'CRM - PRODUTO/SERVICO');
			$dataset['vICXPLEGADO'] 		= $this->data['prospeccao_id'];
			$dataset['vSCXPASSUNTO'] 		= $this->data['assunto_nome'];
			$dataset['vSCXPDATA_INC']       = $this->data['data'].' '.$this->data['hora'];
			//$dataset['vICXPUSU_INC']       = getConsultor($this->data['usuario']);
			$dataset['vIPXCCODIGO']         = $this->data['status'];
			$dataset['vITABFONTEOPORTUNIDADE'] = getTable($this->data['divulgacao_id'], 'CRM - FONTE OPORTUNIDADE');
			$dataset['vSCXPPROXIMOCONTATO'] = $this->data['prolongada_data'];
			$dataset['vIEMPCODIGO'] 		= 1;
			$dataset['vSCXPOBSERVACAO'] 	= $this->data['obs'];
			$dataset['vSCXPINSERIDOPOR']    = 'C';
			$dataset['vSCXPTIPO']        	= 'P';
					
			$CXPCODIGO = insertUpdate(array(
	            'prefixo' => 'CXP',
	            'tabela'  => 'PROSPECCAO',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CXPCODIGO);
	        echo "\n";
	}		
		
}

$invoices = $db->db_query("SELECT * FROM marpaprospeccao
						where prospeccao_id >= 30000  ");
if (count($invoices) == 0) {
	die;
}
foreach ($invoices as $invoice) {
	$insert = new Invoice($invoice);
	$insert->import();
}
