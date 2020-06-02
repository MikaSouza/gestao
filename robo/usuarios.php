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

		//	$cliente = new Client($this->data['sigla']);

			$dataset['vSUSULOGIN'] 	   			= $this->data['yusuario'];
			$dataset['vSUSUSENHA'] 	   			= $this->Encriptar( $this->data['ysenha'], 'TWFlex');
			$dataset['vSUSUNOME'] 	   			= $this->data['ynomeusu'];
			$dataset['vSUSUDEPARTAMENTO'] 	   			= $this->data['ycdgrp'];
			$dataset['vSUSUEMAIL'] 	   			= $this->data['yemail'];
			if ($this->data['ydtcad'] != '')
				$dataset['vSUSUDATA_INC']       	= $this->data['ydtcad'];			
			else
				$dataset['vSUSUDATA_INC']       	= date('Y-m-d H:i:s');				
			$dataset['vSUSUSEQUENCIAL'] 	   			= $this->data['ymbusuarios_id'];
			$dataset['vIUSUCODIGOVENDEDOR'] 	   			= $this->data['codigovendedor'];
	
			$dataset['vIEMPCODIGO'] 	        = ($this->data['ytributario'] == 1) ? 1 : 2;

			$dataset['vSUSUSTATUS'] 	        = ($this->data['ycdst'] == 5) ? 'N' : 'S';
			

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'USU',
	            'tabela'  => 'USUARIOS2',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}
	
	public function Randomizar($iv_len) {
		$iv = '';
		while ($iv_len--> 0) {
			$iv .= chr(mt_rand() & 0xff);
		}
		return $iv;
	}

	public function Encriptar($texto, $senha, $iv_len = 16){
		$texto .= "\x13";
		$n = strlen($texto);
		if ($n % 16) $texto .= str_repeat("\0", 16 - ($n % 16));
		$i = 0;
		$Enc_Texto = $this->Randomizar($iv_len);
		$iv = substr($senha ^ $Enc_Texto, 0, 512);
		while ($i < $n) {
			$Bloco = substr($texto, $i, 16) ^ pack('H*', md5($iv));
			$Enc_Texto .= $Bloco;
			$iv = substr($Bloco . $iv, 0, 512) ^ $senha;
			$i += 16;
		}
		return base64_encode($Enc_Texto);
	}	
}
$pagina = 1;
while(true){
	$limit = ($pagina-1)*1000;
	$invoices = $db->db_query("SELECT * FROM ymbusuarios
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
