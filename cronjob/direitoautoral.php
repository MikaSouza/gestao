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
			$dataset['vIDIACONSULTOR'] 	        = 0;
			$dataset['vIDIAPASTA'] 	   			= $this->data['pasta'];	
			$dataset['vIDIAPROCESSO']           = $this->data['codigoprocesso'];
			$dataset['vSDIADATAPROCESSO'] 	    = $this->data['data'];
			$dataset['vIDIALIVRO']           = $this->data['livro'];
			$dataset['vIDIAFOLHA'] 		        = $this->data['folha'];
			$dataset['vIDIATIPODIREITOAUTORAL']       = $this->getTable($this->data['tipo'], 'DIREITOAUTORAL - TIPO');
			$dataset['vSDIATITULO'] 	        = $this->data['titulo'];
			$dataset['vSDIADEPOSITANTE'] 		= $this->data['depositante'];
			$dataset['vIDIAMOTIVOCANCELAMENTO'] = $this->getTable(($this->data['codigomotcancel'] == -1) ? 99 : $this->data['codigomotcancel'], 'MARCAS - TIPO DE PROTECAO');   	
			$dataset['vSDIAOBSERVACAO'] 	    = $this->data['obs'];
			
			if ($this->data['dtincl'] != '')
				$dataset['vSDIADATA_INC']       	= $this->data['dtincl'];			
			else
				$dataset['vSDIADATA_INC']       	= date('Y-m-d H:i:s');			
			$dataset['vSDIAVIRTUAL'] 	        = ($this->data['virtual'] == 1) ? "S" : "N";			
			$dataset['vIDIATIPOPROTECAO']       = $this->getTable($this->data['tipolan'], 'DIREITOAUTORAL - TIPO PROTECAO');
			$dataset['vICTRCODIGO']   	        = $this->data['numero_contrato']; 

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'DIA',
	            'tabela'  => 'DIREITOAUTORAL',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}

	public function getConsultor()
	{
		$consultores = array(
			1    => 0,  // "VALDOMIRO G. S.                         "
			383  => 0,  // "CRM RS CLIENTES                         "
			221  => 0,  // "COMERCIAL N/INTERESSE                   "
			33   => 80, // "Edilson Brazil                          "
			20   => 0,  // "MICHAEL SILVA SOARES                    "
			375  => 0,  // "CRM RS PROSP                            "
			361  => 0,  // "CRM PR                                  "
			396  => 0,  // "CRM N/INTERESSE                         "
			47   => 79, // "Martinha Borghetti                      "
			426  => 61, // "Caroline Pires                          "
			87   => 82, // "Izilda                                  "
			1007 => 0,  // "Janaina Ferreira                        "
			448  => 0,  // "CRM INATIVOS                            "
			1021 => 0,  // "Diretoria Tributário                    "
			1025 => 0,  // "Gustavo Barbosa                         "
			1028 => 64, // "Fernanda Vargas                         "
			1029 => 0,  // "Leandro Rezende                         "
			1032 => 0,  // "André Vanacor                           "
			1036 => 45, // "Chiara Maria Lage                       "
			1037 => 0,  // "Elieser Lima Oliveira                   "
			67   => 81, // "Juslane                                 "
			1015 => 63, // "Diana Pimentel                          "
			1035 => 68, // "Jessica Peres                           "
			1004 => 0,  // "Adriana                                 "
			1019 => 0,  // "Nelson de Oliveira                      "
			153  => 78, // "Simone Souza                            "
			1020 => 65, // "Guilherme Popko                         "
			1040 => 0,  // "Ismael Decol                            "
			1038 => 40, // "Amanda Estefani da Silva Bocarite -     "
			1042 => 0,  // "Lisiane Vargas                          "
			1041 => 72, // "Caroline Lima Troleis                   "
		);

		return array_key_exists($this->data['codigovendedor'], $consultores) ? $consultores[$this->data['codigovendedor']] : 0;
	}

	public function getUsuario()
	{
		switch (strtoupper($this->data['usuario'])) {
			case 'ANDRE TEER':
				return 4;
				break;
			case 'CAROLINE':
				return 43;
				break;
			default:
				return 1;
				break;
		}
	}


	// /var/www/html/import/guias.php

	public function getTable($codigolegado, $tipo)
	{
		$response = consultaComposta(array(
	        'query' => "SELECT
	                        TABCODIGO
	                    FROM
	                        TABELAS
	                    WHERE
	                        TABTIPO = ? AND
	                        TABCOMPLEMENTO = ?",
	        'parametros' => array(
	            array($tipo, PDO::PARAM_STR),
	            array($codigolegado, PDO::PARAM_INT),
	        )
	    ));

    	return ($response['quantidadeRegistros'] > 0) ? $response['dados'][0]['TABCODIGO'] : 0;
	}
}
$pagina = 1;
while(true){
	$limit = ($pagina-1)*1000;
	$invoices = $db->db_query("SELECT * FROM marpadiraut
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
