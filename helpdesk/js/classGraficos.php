<?php
	include_once( "../include/constantes.php" );
	
	class DashboardFinanceiro {

		private $conexao;

		public function __construct() {
			$this->conexao = sql_conectar_banco();
		}
		
		public function getServidorSexo($config) {
			
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';

			$sql = "SELECT
                        s.SERSEXO,
						COUNT(s.SERCODIGO) as TOTAL,
						CASE s.SERSEXO
						WHEN 'M' THEN 'Masculino'
						ELSE 'Feminino' END AS TIPO
					FROM
						SERVIDORES s					
					WHERE
						s.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						s.SERSTATUS = 'S' ";
				
			$sql .= " GROUP BY s.SERSEXO ORDER BY TOTAL ";

			$retorno = array();
			$query = sql_executa(vGBancoSite, $this->conexao, $sql);

            while($lista = sql_retorno_lista($query)) {
                if(trim($lista['SERSEXO']) == "")
                    $lista['TIPO'] = "Não definido";

                if($config['RETORNO'] == 'chart') {
                    $retorno[] = array($lista['TIPO'], (int)$lista['TOTAL']);
                } elseif($config['RETORNO'] == 'array') {
                    $retorno[] = array(
                        'CENTROCUSTO' => $lista['TIPO'],
                        'TOTAL' => $lista['TOTAL'],
                    );
                }
            }

            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}		

		public function getCategoriaFuncional($config) {
            if(!isset($config['RETORNO'])) 
                $config['RETORNO'] = 'array';

			$sql = "SELECT
                        s.SERCATEGORIA_FUNCIONAL,
						COUNT(s.SERCODIGO) as TOTAL,
						CASE s.SERCATEGORIA_FUNCIONAL
						WHEN 'Q' THEN 'Quadro Geral'
						ELSE 'Magistério' END AS TIPO
					FROM
						SERVIDORES s					
					WHERE
						s.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						s.SERSTATUS = 'S' ";
				
			$sql .= " GROUP BY s.SERCATEGORIA_FUNCIONAL ORDER BY TOTAL DESC";
		
			$retorno = array();
			$query = sql_executa(vGBancoSite, $this->conexao, $sql);

            while($lista = sql_retorno_lista($query)) {
                if(trim($lista['SERCATEGORIA_FUNCIONAL']) == "")
                    $lista['TIPO'] = "Não definido";

                if($config['RETORNO'] == 'chart') {
                    $retorno[] = array($lista['TIPO'], (int)$lista['TOTAL']);
                } elseif($config['RETORNO'] == 'array') {
                    $retorno[] = array(
                        'CENTROCUSTO' => $lista['TIPO'],
                        'TOTAL' => $lista['TOTAL'],
                    );
                }
            }

            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getServidorIdade2($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';
			$retorno = array();
			$sqlPago = "SELECT                        
						SUM(cp.CTPVALORAPAGAR) as TOTAL
					FROM
						CONTASAPAGAR cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTPSTATUS = 'S' AND
						YEAR(cp.CTPDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTPDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTPDATAPAGAMENTO is not null ";						
			$query = sql_executa(vGBancoSite, $this->conexao, $sqlPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Realizado', (int)$lista['TOTAL']);
            }
			$sqlNaoPago = "SELECT                        
						SUM(cp.CTPVALORAPAGAR) as TOTAL
					FROM
						CONTASAPAGAR cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTPSTATUS = 'S' AND
						YEAR(cp.CTPDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTPDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTPDATAPAGAMENTO is null AND
						cp.CTPDATAVENCIMENTO < CURDATE() ";						
			//$query = sql_executa(vGBancoSite, $this->conexao, $sqlNaoPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Atrasado', (int)$lista['TOTAL']);
            }
			$sqlNaoPago = "SELECT                        
						SUM(cp.CTPVALORAPAGAR) as TOTAL
					FROM
						CONTASAPAGAR cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTPSTATUS = 'S' AND
						YEAR(cp.CTPDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTPDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTPDATAPAGAMENTO is null  ";						
			//$query = sql_executa(vGBancoSite, $this->conexao, $sqlNaoPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Aberto', (int)$lista['TOTAL']);
            }
			
            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getServidorIdade($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';

			$sql = "SELECT                        
						COUNT(s.SERCODIGO) as TOTAL,
						TIMESTAMPDIFF(YEAR, SERDATA_NASCIMENTO, CURDATE()) as IDADE						
					FROM
						SERVIDORES s					
					WHERE
						s.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						s.SERSTATUS = 'S' ";
				
			$sql .= " GROUP BY IDADE ORDER BY TOTAL DESC";

			$retorno = array();
			$query = sql_executa(vGBancoSite, $this->conexao, $sql);

            while($lista = sql_retorno_lista($query)) {
                if(trim($lista['IDADE']) == "")
                    $lista['IDADE'] = "Não definido";

                if($config['RETORNO'] == 'chart') {
                    $retorno[] = array($lista['IDADE'], (int)$lista['TOTAL']);
                } elseif($config['RETORNO'] == 'array') {
                    $retorno[] = array(
                        'CENTROCUSTO' => $lista['IDADE'],
                        'TOTAL' => $lista['TOTAL'],
                    );
                }
            }

            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getContasReceberResumo($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';
			$retorno = array();
			$sqlPago = "SELECT                        
						SUM(cp.CTRVALORARECEBER) as TOTAL
					FROM
						CONTASARECEBER cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTRSTATUS = 'S' AND
						YEAR(cp.CTRDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTRDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTRDATAPAGAMENTO is not null ";						
			//$query = sql_executa(vGBancoSite, $this->conexao, $sqlPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Realizado', (int)$lista['TOTAL']);
            }
			$sqlNaoPago = "SELECT                        
						SUM(cp.CTRVALORARECEBER) as TOTAL
					FROM
						CONTASARECEBER cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTRSTATUS = 'S' AND
						YEAR(cp.CTRDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTRDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTRDATAPAGAMENTO is null AND
						cp.CTRDATAVENCIMENTO < CURDATE()  ";						
			//$query = sql_executa(vGBancoSite, $this->conexao, $sqlNaoPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Atrasado', (int)$lista['TOTAL']);
            }
			$sqlNaoPago = "SELECT                        
						SUM(cp.CTRVALORARECEBER) as TOTAL
					FROM
						CONTASARECEBER cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTRSTATUS = 'S' AND
						YEAR(cp.CTRDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTRDATAVENCIMENTO) = ". date('m')." AND 
						cp.CTRDATAPAGAMENTO is null   ";						
			//$query = sql_executa(vGBancoSite, $this->conexao, $sqlNaoPago);
            while($lista = sql_retorno_lista($query)) {
				$retorno[] = array('Aberto', (int)$lista['TOTAL']);
            }
            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getPlanoContasContasReceber($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';

			$sql = "SELECT
                        cp.TABPLANOCONTAS,
						SUM(cp.CTRVALORARECEBER) as TOTAL,
						t.TABDESCRICAO
					FROM
						CONTASARECEBER cp
					LEFT JOIN TABELAS t ON t.TABCODIGO = cp.TABPLANOCONTAS	
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTRSTATUS = 'S' AND
						YEAR(cp.CTRDATAVENCIMENTO) = ". date('Y')." AND
						MONTH(cp.CTRDATAVENCIMENTO) = ". date('m')." ";
				
			$sql .= " GROUP BY cp.TABPLANOCONTAS ORDER BY TOTAL DESC";

			$retorno = array();
			//$query = sql_executa(vGBancoSite, $this->conexao, $sql);

            while($lista = sql_retorno_lista($query)) {
                if(trim($lista['TABDESCRICAO']) == "")
                    $lista['TABDESCRICAO'] = "Não definido";

                if($config['RETORNO'] == 'chart') {
                    $retorno[] = array($lista['TABDESCRICAO'], (int)$lista['TOTAL']);
                } elseif($config['RETORNO'] == 'array') {
                    $retorno[] = array(
                        'CENTROCUSTO' => $lista['TABDESCRICAO'],
                        'TOTAL' => $lista['TOTAL']
                    );
                }
            }

            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getFluxoCaixa($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';						

			$sql = "SELECT                        
						TIMESTAMPDIFF(YEAR, SERDATA_NASCIMENTO, CURDATE()) as IDADE,	
						COUNT(s.SERCODIGO) as TOTAL
					FROM
						SERVIDORES s					
					WHERE
						s.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						s.SERSTATUS = 'S' AND
						s.SERSEXO = 'M' ";
				
			$sql .= " GROUP BY IDADE ";
			
			$retorno = array();
			$query = sql_executa(vGBancoSite, $this->conexao, $sql);
						
			$sql2 = "SELECT                        
						TIMESTAMPDIFF(YEAR, SERDATA_NASCIMENTO, CURDATE()) as IDADE,	
						COUNT(s.SERCODIGO) as TOTAL
					FROM
						SERVIDORES s					
					WHERE
						s.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						s.SERSTATUS = 'S' AND
						s.SERSEXO = 'F'";
				
			$sql2 .= " GROUP BY IDADE ";

			$query2 = sql_executa(vGBancoSite, $this->conexao, $sql2);            
			
			while($lista2 = sql_retorno_lista($query2)) {

				$vIKey = $lista2['IDADE'];
				$vADebito[$vIKey]['DEBITO']['VALOR'] = $lista2['TOTAL'];
               
            }
			
			while($lista = sql_retorno_lista($query)) {

				$vIKey = $lista['IDADE'];
				$vADebito[$vIKey]['RECEITA']['VALOR'] = $lista['TOTAL'];
               
            }
			
			foreach ($vADebito as $key => $vSLinha) {
			
				if ($vADebito[$key]['RECEITA']['VALOR'] == '') 
					$vCValorReceita = 0;
				else
					$vCValorReceita = $vADebito[$key]['RECEITA']['VALOR'];
				if ($vADebito[$key]['DEBITO']['VALOR'] == '')
					$vCValorDebito = 0;
				else
					$vCValorDebito = $vADebito[$key]['DEBITO']['VALOR'];	
			
				if($config['RETORNO'] == 'chart') {
					$retorno[] = array($key, (int)$vCValorReceita, (int)$vCValorDebito);
				} 

			}	
            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}
		
		public function getServidorTimeLine($config) {
			
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';						
			
			$sql = "SELECT T.TCTLOCAL, T.TCTDATA_INGRESSO, T.TCTDATA_SAIDA, T.SERCODIGO
					FROM TEMPOSCONTRIBUICAO T
					WHERE 
						T.EMPCODIGO = ".$config['EMPCODIGO']."
						AND T.SERCODIGO = ".$config['SERCODIGO']."
						AND T.TCTLOCAL!='' 
						AND T.TCTLOCAL IS NOT NULL 
						AND T.TCTDATA_INGRESSO IS NOT NULL
						AND T.TCTDATA_SAIDA IS NOT NULL
						AND T.TCTDATA_INGRESSO !='0000-00-00'
						AND T.TCTDATA_SAIDA !='0000-00-00'
					GROUP BY T.TCTLOCAL
					ORDER BY T.TCTDATA_INGRESSO 
					";
					
					$retorno = array();
					$query = sql_executa(vGBancoSite, $this->conexao, $sql);

            while($lista = sql_retorno_lista($query)) {                
                if($config['RETORNO'] == 'chart') { 
					$lista['TCTDATA_SAIDA'] = verificarVazio($lista['TCTDATA_SAIDA']) ? $lista['TCTDATA_SAIDA'] : date('Y-m-d');
                    $retorno[] = array($lista['TCTLOCAL'], $lista['TCTDATA_INGRESSO'], $lista['TCTDATA_SAIDA']);
                }
            }
			
            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}				
		
		public function getFluxoCaixa2($config) {
            if(!isset($config['RETORNO']))
                $config['RETORNO'] = 'array';

			$sql = "SELECT
                        YEAR(cp.CTRDATAVENCIMENTO) AS ANO, MONTH(cp.CTRDATAVENCIMENTO) AS MES,
						SUM(cp.CTRVALORARECEBER) AS TOTALARECEBER, SUM(cp.CTRVALORRECEBIDO) AS TOTALPAGO						
					FROM
						CONTASARECEBER cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTRSTATUS = 'S' AND
						YEAR(cp.CTRDATAVENCIMENTO) = ". date('Y');
				
			$sql .= " GROUP BY ANO, MES ";
			
			$retorno = array();
			//$query = sql_executa(vGBancoSite, $this->conexao, $sql);
						
			$sql2 = "SELECT
                        YEAR(cp.CTPDATAVENCIMENTO) AS ANO, MONTH(cp.CTPDATAVENCIMENTO) AS MES,
						SUM(cp.CTPVALORAPAGAR) AS TOTALARECEBER, SUM(cp.CTPVALORPAGO) AS TOTALPAGO						
					FROM
						CONTASAPAGAR cp					
					WHERE
						cp.EMPCODIGO IN (".$config['EMPCODIGO'].") AND
						cp.CTPSTATUS = 'S' AND
						YEAR(cp.CTPDATAVENCIMENTO) = ". date('Y');
				
			$sql2 .= " GROUP BY ANO, MES ";

			//$query2 = sql_executa(vGBancoSite, $this->conexao, $sql2);

            while($lista = sql_retorno_lista($query)) {

				$vIKey = $lista['MES'];
				$vADebito[$vIKey]['RECEITAPREV']['VALOR'] = $lista['TOTALARECEBER'];
                $vADebito[$vIKey]['RECEITAREA']['VALOR'] = $lista['TOTALPAGO'];
            }
			
			while($lista2 = sql_retorno_lista($query2)) {

				$vIKey = $lista2['MES'];
				$vADebito[$vIKey]['DEBITOPREV']['VALOR'] = $lista2['TOTALARECEBER'];
                $vADebito[$vIKey]['DEBITOREA']['VALOR'] = $lista2['TOTALPAGO'];
            }
			
			foreach ($vADebito as $key => $vSLinha) {
			
				if ($vADebito[$key]['RECEITAPREV']['VALOR'] == '')
					$vCValorReceitaPrev = 0;
				else
					$vCValorReceitaPrev = $vADebito[$key]['RECEITAPREV']['VALOR'];
				if ($vADebito[$key]['RECEITAREA']['VALOR'] == '')
					$vCValorReceitaRea = 0;
				else
					$vCValorReceitaRea = $vADebito[$key]['RECEITAREA']['VALOR'];
					
				if ($vADebito[$key]['DEBITOPREV']['VALOR'] == '')
					$vCValorDebitoPrev = 0;
				else
					$vCValorDebitoPrev = $vADebito[$key]['DEBITOPREV']['VALOR'];
				if ($vADebito[$key]['DEBITOREA']['VALOR'] == '')
					$vCValorDebitoRea = 0;
				else
					$vCValorDebitoRea = $vADebito[$key]['DEBITOREA']['VALOR'];		
			
				if($config['RETORNO'] == 'chart') {
					$retorno[] = array(substr(descricaoMes($key),0,3), (int)$vCValorReceitaPrev, (int)$vCValorReceitaRea, (int)$vCValorDebitoPrev, (int)$vCValorDebitoRea);
				} elseif($config['RETORNO'] == 'array') {
					$retorno[] = array(
						'CENTROCUSTO' => $lista['TABDESCRICAO'],
						'TOTAL' => $lista['TOTAL']
					);
				}

			}	
            if($config['RETORNO'] == 'chart')
                return json_encode($retorno);
            elseif($config['RETORNO'] == 'array')
                return $retorno;
		}

		public function fechar(){
			sql_fechar_conexao_banco($this->conexao);
		}

	}
?>