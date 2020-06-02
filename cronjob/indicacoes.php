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
			/*
			$this->data['indicacao_id']
			$this->data['assunto']
			$this->data['usuario']
			$this->data['data']
			$this->data['hora']
			$this->data['assunto_nome']
			$this->data['divulgacao_outros']
			$this->data['status']
			$this->data['prolongada']
			$this->data['prolongada_daa']
			$this->data['obs']			
			$this->data['agendado']*/

 			$dataset['vSCXPDATA_INC']           = $this->data['data'].' '.$this->data['hora'];
			$dataset['vICLICODIGO']             = $cliente->getClicodigo();
			$dataset['vIEMPCODIGO'] 		    = 1;
			$dataset['vITABFONTEOPORTUNIDADE'] 	= getTable($this->data['divulgacao_id'], 'CRM - FONTE OPORTUNIDADE');
			$dataset['vIPXCCODIGO']             = $this->data['status'];
			$dataset['vSCXPOBSERVACAO'] 		= $this->data['obs'];
			//$dataset['vICXPREPRESENTANTE']      = $this->getConsultor($cliente['autorizante']);
			$dataset['vSCXPASSUNTO'] 		    = $this->data['assunto_nome'];
			$dataset['vICXPPRODUTO'] 		    = getTable($this->data['assunto'], 'CRM - PRODUTO/SERVICO');
			$dataset['vSCXPPROXIMOCONTATO']     = $this->data['prolongada_data'];			
			$dataset['vSCXPINSERIDOPOR']        = 'M';
			$dataset['vSCXPLEGADO']        		= $this->data['indicacao_id'];	

			
			// $dataset['vSCXPUSU_INC']       = 1;

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'CXP',
	            'tabela'  => 'PROSPECCAO',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}

}

$invoices = $db->db_query("SELECT * FROM marpaindicacao
						where indicacao_id > 30000 ");
if (count($invoices) == 0) {
	die;
}
foreach ($invoices as $invoice) {
	$insert = new Invoice($invoice);
	$insert->import();
}
