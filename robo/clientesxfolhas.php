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
			
			if ($this->data['dtfolha'] != '')
				$dataset['vSCXFDATA_INC']       = $this->data['dtfolha'];
			else
				$dataset['vSCXFDATA_INC']       = date('Y-m-d H:i:s');
			
			$dataset['vICXFNUMEROFOLHA']    = $this->data['numero'];
			$dataset['vICXFSTATUS']         = $this->data['status'] == 'true' ? 'S' : 'N';
			$dataset['vSCXFDATA']           = $this->data['dtfolha'];
			$dataset['vICLICODIGO']         = $cliente->getClicodigo();
			$dataset['vICXFTIPOLANCAMENTO'] = $this->getTable($this->data['tipolan'], 'CONTAS A RECEBER - CENTRO DE CUSTO');
			$dataset['vICFXSERVICO'] 	    = $this->getTable($this->data['tiposervico'], 'MARPA - SERVICOS');
			$dataset['vICXFPROCEDIMENTO'] 	= $this->getTable($this->data['marpatipoprocedimento_id'], 'CONTAS A RECEBER - PLANO DE CONTAS');
			
			//$dataset['vIPAICODIGO']    		= $this->data['pais']; ??
			
			$dataset['vIEMPCODIGO'] 	   	= 1;	
			$dataset['vICXFPROCESSO'] 	   	= $this->data['codigoprocesso'];	
			$dataset['vICTRCODIGO']         = $this->data['num_contrato'];
			$dataset['vICXFNUMEROAB'] 	    = $this->data['num_ab'];
			$dataset['vICXPFORMAPAGAMENTO'] = $this->getTable($this->data['codigotipodoc'], 'CONTAS A RECEBER - FORMA DE COBRANÇA');
			$dataset['vSCXFOBSERVACAO'] 	= $this->data['obs'];			
			$dataset['vIIDLEGADO']          = $this->data['folha_id'];
			$dataset['vICXFPASTA'] 			= $this->data['pasta'];

			// $dataset['vSCXFUSU_INC']       = 1;

			$CTRCODIGO = insertUpdate(array(
	            'prefixo' => 'CXF',
	            'tabela'  => 'CLIENTESXFOLHAS',
	            'fields'  => $dataset,
	            'debug' => 'S',
	        ));
			

	        print_r($CTRCODIGO);
	        echo "\n";
	}	

	public function getBanco()
	{
		$bancos = array(
			56  => 5,  //"Factoring - Salies Lima"
			55  => 2,  //"Factoring - Banco Virtual"
			88  => 0,  //"Ômega - Banco Virtual"
			3   => 3,  //Não cadastrado
			238 => 8,  //"Marpa Virtual"
			237 => 7,  //"Bradesco - Maringá"
			1   => 1,  //"Bradesco - Benjamin Constant"
			2   => 9,  //"Marpa"
			4   => 10, //"Dolar s/ Registro"
			5   => 4,  //"OITO 1"
			104 => 6,  //"Dolar c/ Registro"
			105 => 6,  //"Caixa Econômica Federal"
		);

		return array_key_exists($this->data['banco'], $bancos) ? $this->data['banco'] : 0;
	}

	public function getEmpresa()
	{
		$empresas = array(
			" " => 2,//Marpa - Matriz
			"1" => 2,//Marpa - Matriz
			"2" => 2,//Oito - Não Usar
			"3" => 5,//Marpa Virtual
			"4" => 1,//Tributario - Não Usar
			"5" => 9,//Marpa - Filial (Nova)
		);

		return (array_key_exists($this->data['codempfil'], $empresas)) ? $empresas[$this->data['codempfil']] : 0;
	}
	
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

	$invoices = $db->db_query("SELECT * FROM marpafolha");
	if (count($invoices) == 0) {
		die;
	}
	foreach ($invoices as $invoice) {
		$insert = new Invoice($invoice);
		$insert->import();
	}
	$pagina++;

