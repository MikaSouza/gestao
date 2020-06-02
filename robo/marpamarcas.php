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
			$dataset['vIMABREPRESENTANTE'] 	        = 0;
			$dataset['vIMABPASTA'] 	   			= $this->data['pasta'];				
			$dataset['vIMABPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vSMABDATAPUBLICACAO'] 	    = $this->data['datapublicacao'];
			if ($this->data['datainclusao'] != '')
				$dataset['vSMABDATA_INC']       	= $this->data['datainclusao'];			
			else
				$dataset['vSMABDATA_INC']       	= date('Y-m-d H:i:s');	
			$dataset['vIMABTIPOPUBLICACAO']       = $this->getTable($this->data['codigotipopublicacao'], 'PROCESSOS - STATUS');
			$dataset['vITABMARCAAPRESENTACAO']       = $this->getTable($this->data['codigoapresentacao'], 'MARCAS - APRESENTACAO');
			$dataset['vITABNATUREZA']       = $this->getTable($this->data['codigonatureza'], 'MARCAS - NATUREZA');

			$dataset['vIMABCLASSE1']       = $this->data['codigoclasse1'];
			$dataset['vIMABCLASSE2']       = $this->data['codigoclasse2'];
			$dataset['vIMABCLASSE3']       = $this->data['codigoclasse3'];
			$dataset['vIMABCLASSE4']       = $this->data['codigoclasse4'];
			$dataset['vIMABCLASSE5']       = $this->data['codigoclasse5'];

			$dataset['vSMABESPECIFICACAOCLASSE'] 		= $this->data['especificacaoclasse'];
			$dataset['vITABMOTIVO'] = $this->getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'MARCAS - TIPO DE PROTECAO');   
			$dataset['vSMABDATAVALIDADE']   	        = $this->data['datavalidade']; 
			$dataset['vSMABNOME'] 	        = $this->data['marca'];
			$dataset['vSMABOBSERVACOES'] 	    = $this->data['obs1'];
			$dataset['vITABTIPOPROCESSO'] = $this->getTable(($this->data['codigotipoprocesso'] == -1) ? 98 : $this->data['codigotipoprocesso'], 'MARCAS - TIPO DE PROCESSO');   
			$dataset['vSMABTITULAR']           = $this->data['titular'];
			$dataset['vICTRCODIGO']   	        = $this->data['contrato'];

			$dataset['vIMABCTRCODIGOINDIVIDUAL']       = $this->data['codigoctrindividual'];
			$dataset['vSMABNUMEROPROTOCOLODEPOSITANTE'] 		= $this->data['numprotocoldep'];
			$dataset['vSMABOBSERVACOESMEMO'] 	    = $this->data['obsmemo'];
			$dataset['vIMABCODIGOAGENTE'] 		= $this->data['codagente'];
			$dataset['vSMABPAIS']   	        = $this->data['paismarca']; 
			$dataset['vSMABPORTADORATUAL'] 		        = $this->data['portador_atual'];


			$dataset['vSMABSIMILARES'] 		        = $this->data['similares'];
			$dataset['vSMABOBSATUAL']   	        = $this->data['obs_atual']; 
			$dataset['vSMABTIPOPRAZO1ATUAL'] 		= $this->data['tipoprazo1_atual'];

			$dataset['vSMABDATAVENC1ATUAL'] 		= $this->data['dtvenc1_atual'];
			$dataset['vSMABAUTORIZ1ATUAL'] 		= $this->data['autoriz1_atual'];
			$dataset['vSMABTIPOPRAZO2ATUAL'] 		= $this->data['tipoprazo2_atual'];
			$dataset['vSMABDATAVENC2ATUAL'] 		= $this->data['dtvenc2_atual'];
			$dataset['vSMABAUTORIZ2ATUAL'] 		= $this->data['autoriz2_atual'];
			$dataset['vIMABCERTIFICADO']   	        = $this->data['certificado']; 
			$dataset['vSMABDATAPROCESSO']   	        = $this->data['dataprocesso']; 
			$dataset['vSMABNOMESEMACENTO'] 	        = $this->data['marcasemacento'];
			$dataset['vITABTIPOPROTECAO']       = $this->getTable($this->data['tipolan'], 'MARCAS - TIPO DE PROTECAO');
			$dataset['vIMABNROCONTRATO']   	        = $this->data['numero_contrato']; 
			$dataset['vSMABSIMILARESTIPO']   	        = $this->data['similarestipo'];
			$dataset['vICODFOLHA']   	        = $this->data['num_folha']; 
			$dataset['vIMABNROTIPOPROTECAO'] 		        = $this->data['num_tipo_protecao'];
			$dataset['vSMABOBSSIMILAR']   	        = $this->data['obssimilar']; 
			$dataset['vSMABOBSESP']   	        = $this->data['obs_esp'];
			$dataset['vIMABVALIDADORREGISTRO']   	        = $this->data['validador_registro']; 
			$dataset['vSMABABREPRAZO']   	        = $this->data['abreprazo']; 
			$dataset['vSMABFECHAPRAZO']   	        = $this->data['fechaprazo']; 
			$dataset['vSMABABREEXTRA']   	        = $this->data['abreextra']; 
			$dataset['vSMABFECHAEXTRA']   	        = $this->data['fechaextra']; 									
				
			$dataset['vSMABVIRTUAL'] 	        = ($this->data['virtual'] == 1) ? "S" : "N";
			$dataset['vSMABPREMIUMCOMTAXA']   	= ($this->data['premium_com_taxa'] == 1) ? "S" : "N";
			$dataset['vSMABPREMIUMCOMTAXAHONORARIOS']  = ($this->data['premium_com_taxa_honorarios'] == 1) ? "S" : "N";	


			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'MAB',
	            'tabela'  => 'MARCASBRASIL',
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
	$invoices = $db->db_query("SELECT * FROM marpaprocesso
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
