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

	public function exists()
	{
		$response = consultaComposta(array(
	        'query' => "SELECT
	                        GUICODIGO
	                    FROM
	                        GUIAS
	                    WHERE
	                        GUICODIGOLEGADO = ?",
	        'parametros' => array(
	            array($this->data['id'], PDO::PARAM_INT),
	        )
	    ));

    	return ($response['quantidadeRegistros'] > 0);
	}

	public function import()
	{
		if (!$this->exists()):
		$dataset = array();

		$cliente = new Client($this->data['sigla']);

		$dataset['vICLICODIGO']         = $cliente->getClicodigo();
		$dataset['vSGUICAFR']           = $this->data['ca_fr'];
		$dataset['vSGUIDATAVENCIMENTO'] = $this->data['data_vencimento'];
		$dataset['vSGUIDATAPAGAMENTO']  = $this->data['data_pagamento'];
		$dataset['vSGUIVALOR']          = $this->data['valor'];
		$dataset['vSGUINRONOTARECIBO']  = $this->data['nro_nf'];
		$dataset['vSGUIPROCESSO']       = $this->data['processo'];
		$dataset['vSGUIPAGADOR']        = $this->data['pagador'];
		$dataset['vITABPROCEDIMENTO']   = $this->getTable($this->data['marpatipoprocedimento_id'], 'CONTAS A RECEBER - PLANO DE CONTAS');
		$dataset['vIEMPCODIGO']         = 1;
		$dataset['vSGUIDATA_INC']       = date('Y-m-d H:i:s');
		$dataset['vIGUICODIGOLEGADO']   = $this->data['id'];
		// $dataset['vSGUIUSU_INC']       = 1;



		$CTRCODIGO = insertUpdate(array(
            'prefixo' => 'GUI',
            'tabela'  => 'GUIAS',
            'fields'  => $dataset,
            'debug'   => 'S',
        ));

        if (!$CTRCODIGO) {
        	echo "parei com o id: ".$this->data['id'];
        	echo "\n e o cliente eh:". $cliente->getClicodigo();
        	echo "\n a sigla eh:". $this->data['sigla'];
        	die;
        }

		if (is_file('/var/www/html/marpa_intranet/upload/digitalizacoes/guias/'.$this->data['id'].'.pdf')) {
			echo "achei\n";

			if (!is_dir('/var/www/html/temp_guias/'.$CTRCODIGO)) {
				mkdir('/var/www/html/temp_guias/'.$CTRCODIGO, 0755);
				echo "criei\n";
			}

			if (copy('/var/www/html/marpa_intranet/upload/digitalizacoes/guias/'.$this->data['id'].'.pdf', '/var/www/html/temp_guias/'.$CTRCODIGO.'/'.$CTRCODIGO.'.pdf')) {

				echo "copiei\n";
                insertUpdate(array(
                    'prefixo' => 'GED',
                    'tabela'  => 'GED',
                    'debug'   => 'S',
                    'fields'  => array(
                        'vSGEDDATA_INC' => date('Y-m-d H:i:s'),
                        'vIEMPCODIGO' => 2,
                        'vIGEDVINCULO' => $CTRCODIGO,
                        'vIMENCODIGO' => 1890,
                        'vSGEDNOMEARQUIVO' => $CTRCODIGO.'.pdf',
                        'vSGEDDIRETORIO' => '../ged/guias/'.$CTRCODIGO.'/'.$CTRCODIGO.'.pdf'
                    )
                ));
			}
		}

        print_r($CTRCODIGO);
        echo "\n";
    	endif;
	}
	
}
$pagina = 1;
while(true){
	$limit = ($pagina-1)*1000;
	$invoices = $db->db_query("SELECT * FROM marpaguias
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
