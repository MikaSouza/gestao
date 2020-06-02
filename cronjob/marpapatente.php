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

			$cliente = new Client($this->data['siglacliente']);

			$dataset['vICLICODIGO']             = $cliente->getClicodigo();
			$dataset['vIPATCONSULTOR'] 	        = 0;
			$dataset['vIPATPASTA'] 	   			= $this->data['pasta'];				
			$dataset['vIPATPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vSPATNUMEROPROTOCOLODEPOSITANTE'] 		= $this->data['numprotocoldep'];
			$dataset['vPATDATAPUBLICACAO'] 	    = $this->data['datapublicacao'];
			$dataset['vIPATTIPOPUBLICACAO']       = $this->getTable($this->data['codigotipopublicacao'], 'PROCESSOS - STATUS');
			$dataset['vIPATCLASSIFICACAO']       = $this->getTable($this->data['codtipoclass'], 'PATENTES - CLASSIFICACAO');
			$dataset['vIPATMOTIVOCANCELAMENTO'] = $this->getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'MARCAS - TIPO DE PROTECAO');   
			$dataset['vSPATDEPOSITANTE'] 		= $this->data['depositante'];		
			$dataset['vSPATTITULO'] 	        = $this->data['titulo'];
			$dataset['vSPATOBSERVACOES'] 	    = $this->data['obs'];
			$dataset['vSPATOBSERVACOESMEMO'] 	    = $this->data['obsmemo'];
			$dataset['vIPATTIPOPROCESSO'] = $this->getTable(($this->data['codigotipoprocesso'] == -1) ? 98 : $this->data['codigotipoprocesso'], 'MARCAS - TIPO DE PROCESSO');   
			$dataset['vSPATTITULAR']           = $this->data['titular'];
			$dataset['vSPATESPECIFICACAO'] 		        = $this->data['especificacao'];
			$dataset['vSPATSIMILARES'] 		        = $this->data['similares'];
			$dataset['vICTRCODIGO']   	        = $this->data['contrato']; 
			$dataset['vSPATPAIS']   	        = $this->data['deppais']; 
			$dataset['vIPATCODIGOAGENTE']   	        = $this->data['codagente']; 
			$dataset['vIPATCERTIFICADO']   	        = $this->data['certificado']; 	
			if ($this->data['datainclusao'] != '')
				$dataset['vSPATDATA_INC']       	= $this->data['datainclusao'];			
			else
				$dataset['vSPATDATA_INC']       	= date('Y-m-d H:i:s');	
			$dataset['vSPATDATAPROCESSO']   	        = $this->data['dataprocesso']; 
			$dataset['vSPATABREPRAZO']   	        = $this->data['abreprazo']; 
			$dataset['vSPATFECHAPRAZO']   	        = $this->data['fechaprazo']; 
			$dataset['vSPATABREEXTRA']   	        = $this->data['abreextra']; 
			$dataset['vSPATFECHAEXTRA']   	        = $this->data['fechaextra']; 
			$dataset['vIPATTIPOPROTECAO']       = $this->getTable($this->data['tipolan'], 'MARCAS - TIPO DE PROTECAO');
			$dataset['vIPATNROCONTRATO']   	        = $this->data['numero_contrato']; 
			$dataset['vSPATDATAACOMPANHAMENTO']   	        = $this->data['dataacomp']; 
			$dataset['vIPATFOLHA']   	        = $this->data['num_folha']; 
			$dataset['vSPATOBSSIMILAR']   	        = $this->data['obssimilar']; 
			$dataset['vSPATOBSERVACOESESPECIAIS']   	        = $this->data['obs_esp']; 	
			$dataset['vSPATVIRTUAL'] 	        = ($this->data['virtual'] == 1) ? "S" : "N";
			$dataset['vIPATCONTROLEPCT']   	    = ($this->data['controle_pct'] == 1) ? "S" : "N";
			$dataset['vSPATPREMIUMCOMTAXA']   	= ($this->data['premium_com_taxa'] == 1) ? "S" : "N";
			$dataset['vSPATPREMIUMCOMTAXAHONORARIOS']  = ($this->data['premium_com_taxa_honorarios'] == 1) ? "S" : "N";	


			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'PAT',
	            'tabela'  => 'PATENTES',
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
	$invoices = $db->db_query("SELECT * FROM marpapatente
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
