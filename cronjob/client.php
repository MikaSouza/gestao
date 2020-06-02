<?php
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//////////////////NÂO REALIZAR A IMPORTAÇÃO DIRETAMENTE NESTE ARQUIVO!//////////////////
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
require_once 'import.php';
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

class Client
{
	private $client = array();
	private $db;
    public $CLICODIGO;

	public function __construct($sigla)
	{
        $this->db = new Import();
        $this->CLICODIGO = 0;
        $exists = $this->exists($sigla);
        if ($exists == 0) {
            $this->client = $this->get($sigla);
            echo "Importando cliente\n";
            $this->import('');
		} else {
            $this->client = $this->get($sigla);
            echo "Atualizando cliente\n";
            $this->import($exists);
		}
	}

    public function getClicodigo()
    {
        return $this->CLICODIGO;
    }

	private function exists($sigla)
	{
		$response = consultaComposta(array(
	        'query' => "SELECT
	                        CLICODIGO
	                    FROM
	                        CLIENTES
	                    WHERE
	                        IDSIGLA = ?",
	        'parametros' => array(
	            array(trim($sigla), PDO::PARAM_INT),
	        )
	    ));

    	if ($response['quantidadeRegistros'] > 0) {
            $this->CLICODIGO = $response['dados'][0]['CLICODIGO'];
            echo "Cliente existente!\n";
            return $response['dados'][0]['CLICODIGO'];
    	}

        return 0;
	}

	private function import($vICLICODIGO)
	{
        if (!empty($this->client)):
		$cliente = array_map('utf8_encode', $this->client);
        $cliente = array_map('trim', $cliente);

        if ($cliente['datacad'] == '') {
            $cliente['datacad'] = date('Y-m-d');
        }
        if (strtoupper($cliente['fj']) == 'J') {
            $cliente['fj'] = 'J';
            $vSCNPJCPF = preg_replace( '/[^0-9]/', '', $cliente['cgc']);
            if ($vSCNPJCPF != '')
                $vSCNPJCPF = substr($vSCNPJCPF,0,2).".".substr($vSCNPJCPF,2,3).".".substr($vSCNPJCPF,5,3)."/".substr($vSCNPJCPF,8,4)."-".substr($vSCNPJCPF,12,2);
            else $vSCNPJCPF = '';
        } else {
			$cliente['fj'] = 'F';
            $vSCNPJCPF = preg_replace( '/[^0-9]/', '', $cliente['cgc']);
            if ($vSCNPJCPF != '')
                $vSCNPJCPF = substr($vSCNPJCPF,0,3).".".substr($vSCNPJCPF,3,3).".".substr($vSCNPJCPF,6,3)."-".substr($vSCNPJCPF,9,4);
            else $vSCNPJCPF = '';
        }
		
		//prospeccao = 24687
        //indicacao = 24687
        //cliente marpa = 24688
        //autorizacao = 24688
		
        $cliente_id = insertUpdate(array(
            'prefixo' => 'CLI',
            'tabela'  => 'CLIENTES',
            'debug' => 'S',
            'fields'  => array(
                 'vICLICODIGO'                => $vICLICODIGO,
                'vIIDSIGLA'                   => $cliente['sigla'],
                'vSCLINOME'                   => $cliente['empresa'],
                'vICLITIPOCADASTRO'           => getTable($cliente['tipo_cliente'], 'PARCEIROS - TIPO'), // buscar de tabelas
                'vSCLIPOSICAO'                => getTable($cliente['status_cliente'], 'PARCEIROS - POSICAO'),
                'vSCLICONTATO'                => $cliente['contato'],
                'vSCLIFONE'                   => '('.$cliente['prefixo'].') '.$cliente['telefone'],                
                'vSCLIEMAIL'                  => $cliente['email'],
                'vSCLITIPOCLIENTE'            => strtoupper($cliente['fj']),
                'vSCLICNPJ'                   => strtoupper($cliente['fj'] == 'J') ? $vSCNPJCPF : '', //formatar cnpj
                'vSCLICPF'                    => strtoupper($cliente['fj'] == 'F') ? $vSCNPJCPF : '',
                'vSCLIIE'                     => $cliente['inscest'],
                'vSCLIIM'                     => $cliente['inscmun'],
                'vICLIRESPONSAVEL'            => getConsultor($cliente['codigovendedor']),
                'vICLIMOTIVOCANCELAMENTO'     => getTableComplemento($cliente['motivocanc'], 'PROCESSOS - CANCELAMENTO'), // buscar de tabelas
                'vSCLIDATA_INC'               => $cliente['datacad'].' 00:00:00',
                'vSCLIANUIDADEBRASIL'         => $cliente['anuidade'] == 'SIM' ? 'S' : 'N',
                'vSCLIANUIDADEEXTERIOR'       => $cliente['anuidade_ext'] == 'SIM' ? 'S' : 'N',
                'vSCLICELULAR'                => '('.$cliente['prefixo_celular'].') '.$cliente['celular'],
                'vSCLISITE'                   => $cliente['site'],
                'vSCLIOBSESPCORRESPONDENCIA'  => $cliente['instrucoes_envio'],
                'vSCLIOBSESPFATURA'           => $cliente['instrucoes_fatura'],
                'vSCLIOBSESPCONTATO'          => $cliente['instrucoes_contato'],
                'vSCLIME'                     => ($cliente['me'] == 0) ? 'S' : 'N',
                'vSCLIOPTANTESIMPLESNACIONAL' =>  $cliente['simples'] == 0 ? 'S' : 'N',
                'vSCLIDATADISTRATO'            => $cliente['datadistrato'],
                'vIEMPCODIGO'                 => 1,
                'vSCLIISENTAIE'               => empty($cliente['inscest']) ? 'S' : 'N',                
                'vICLISEQUENCIAL'             => $cliente['sigla'],

            )
        ));
		
        //pendencias
		/*
        if ($cliente['endereco'] != '') {
            insertUpdate(array(
                'tabela'  => 'ENDERECOS',
                'prefixo' => 'END',
                'fields'  => array(
                    'vSENDSTATUS'        => 'S',
                    // 'vIENDUSU_INC'       => 1,
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vITABCODIGO'        => 426, //Comercial                    
                    'vSENDLOGRADOURO'    => $cliente['endereco'],
                    'vIESTCODIGO'        => findEstado($cliente['estado']),
                    'vIPAICODIGO'        => 30,
                    'vICIDCODIGO'        => findCidade($cliente['cidade']),
                    'vSENDBAIRRO'        => $cliente['bairro'],
                    'vSENDCEP'           => $cliente['cep'],
                    'vSENDDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));
        }

        if ($cliente['enderaux'] != '') {
            insertUpdate(array(
                'tabela'  => 'ENDERECOS',
                'prefixo' => 'END',
                'fields'  => array(
                    'vSENDSTATUS'        => 'S',
                    // 'vIENDUSU_INC'       => 1,
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vITABCODIGO'        => 428, //Entrega = Correspondencia                    
                    'vSENDLOGRADOURO'    => $cliente['enderaux'],
                    'vIESTCODIGO'        => findEstado($cliente['estadoaux']),
                    'vIPAICODIGO'        => 30,
                    'vICIDCODIGO'        => findCidade($cliente['cidadeaux']),
                    'vSENDBAIRRO'        => $cliente['bairroaux'],
                    'vSENDCEP'           => $cliente['cepaux'],
                    'vSENDDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));
        }

        if ($cliente['endercob'] != '') {
            insertUpdate(array(
                'tabela'  => 'ENDERECOS',
                'prefixo' => 'END',
                'fields'  => array(
                    'vSENDSTATUS'        => 'S',
                    // 'vIENDUSU_INC'       => 1,
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vITABCODIGO'        => 427, //Cobrança                    
                    'vSENDLOGRADOURO'    => $cliente['endercob'],
                    'vIESTCODIGO'        => findEstado($cliente['estadocob']),
                    'vIPAICODIGO'        => 30,
                    'vICIDCODIGO'        => findCidade($cliente['cidadecob']),
                    'vSENDBAIRRO'        => $cliente['bairrocob'],
                    'vSENDCEP'           => $cliente['cepcob'],
                    'vSENDDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));
        }       

		//contatos
		if ($cliente['contato'] != '') {// INPI		
			insertUpdate(array(
                'tabela'  => 'CONTATOS',
                'prefixo' => 'CON',
                'fields'  => array(
                    'vSCONSTATUS'        => 'S',                    
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vICONTIPO'          => 26933, //Cobrança                    
                    'vSCONNOME'   		 => $cliente['contato'],
                    'vSCONEMAIL'         => $cliente['email'],
                    'vSCONFONE'          => '('.$cliente['prefixo'].') '.$cliente['telefone'],
                    'vSCONOBSERVACOES'   => $cliente['instrucoes_contato'],
                    'vSCONDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));		
		}
		
		if ($cliente['contatoaux'] != '') {// Correspondencia		
			insertUpdate(array(
                'tabela'  => 'CONTATOS',
                'prefixo' => 'CON',
                'fields'  => array(
                    'vSCONSTATUS'        => 'S',                    
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vICONTIPO'          => 26936, //Correspondencia                    
                    'vSCONNOME'   		 => $cliente['contatoaux'],
                    'vSCONEMAIL'         => $cliente['emailaux'],
                    'vSCONFONE'          => '('.$cliente['prefixoaux'].') '.$cliente['telefaux'],
                    'vSCONOBSERVACOES'   => $cliente['instrucoes_envio'],
                    'vSCONDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));		
		}
		
		if ($cliente['contatocob'] != '') {// Cobranca		
			insertUpdate(array(
                'tabela'  => 'CONTATOS',
                'prefixo' => 'CON',
                'fields'  => array(
                    'vSCONSTATUS'        => 'S',                    
                    'vICLICODIGO'        => $cliente_id,
                    'vIEMPCODIGO'        => 1,
                    'vICONTIPO'          => 26934, //Cobranca                    
                    'vSCONNOME'   		 => $cliente['contatocob'],
                    'vSCONEMAIL'         => $cliente['emailcob'],
                    'vSCONFONE'          => '('.$cliente['prefixocob'].') '.$cliente['telefcob'],
                    'vSCONOBSERVACOES'   => $cliente['instrucoes_fatura'],
                    'vSCONDATA_INC'      => $cliente['datacad'].' 00:00:00',
                )
            ));		
		}*/
		        


        // print_r($cliente_id);
        $this->CLICODIGO = $cliente_id;
        // return $cliente_id;
        endif;
	}

	private function get($sigla)
	{
		$sql = "SELECT
		            *
		        FROM
		            marpacliente
		        WHERE
		            sigla = '{$sigla}'";
		$query = $this->db->db_query($sql);
        // print_r($query);
        if (count($query) > 0) {
            //echo "Inserindo cliente \n";
			return $query[0];
		}
	}

}