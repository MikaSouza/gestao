<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define("vGUsername", "marpa2");
define("vGPassword", "mF01lkwvXbpd7w88");
define("vGBancoSite", "marpaconsultoria");
define("vGHostSite", "192.168.1.252");

//Função para remover os prefixos "vS" e "vI"
function removePrefix($a) {
	return substr($a, 2);
}

//Conexão com o banco
function conectarBanco(){
	try{
		$db = new PDO("mysql:host=".vGHostSite.";dbname=".vGBancoSite.";charset=UTF8", vGUsername, vGPassword,array(
    PDO::ATTR_PERSISTENT => true
));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
		return $db;
	}catch(Exception $e){
		print_r($e->getMessage());
	}
}

//Teste de onexão com o banco
function testarConexao(){
	try{
		$db = new PDO("mysql:host=".vGHostSite.";dbname=".vGBancoSite.";charset=UTF8", vGUsername, vGPassword);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
		return true;
	}catch(Exception $e){
		print_r($e->getMessage());
	}
}

//Dunção para inserir ou alterar dados
function insertUpdate($dados){
	try{
		//Removendo os dados do crop, se precisar
		if(isset($dados['fields']['aspectRatioCrop'])) unset($dados['fields']['aspectRatioCrop']);		

		//Definindo o tipo de caractere pelo prefixo
		foreach ($dados['fields'] as $key => $value) {
			switch(substr($key, 0, 2)){
				case 'vI':
					$tipoFiltro = PDO::PARAM_INT;					
					if($value != '')
						$value = (int) $value;
					else
						$value = null;
					break;
				case 'vS':
					$tipoFiltro = PDO::PARAM_STR;
					if($value != '')
						$value = (String) $value;
					else
						$value = null;					
					break;
				case 'vD':
					$tipoFiltro = PDO::PARAM_STR;					
					if($value != '')
						$value = (String) formatar_data_banco($value);
					else
						$value = null;
					break;
				case 'vM':
					$tipoFiltro = PDO::PARAM_STR;
					if($value != '')
						$value = (String) formatar_valor_monetario_banco($value);
					else
						$value = null;
					break;
				default:
					unset($dados['fields'][$key]);	
					$tipoFiltro = '';	
			}
			if ($tipoFiltro != '')
				$dados['fields'][$key] = array(
					'valor'  => $value,
					'filtro' => $tipoFiltro,
				);
		}


		// if ($dados['debug'] == 'S') pre($dados);

		//Removendo o prefixo
		$fields = array_combine(array_map('removePrefix', array_keys($dados['fields'])), $dados['fields']);
		$tipoTransaction = 'INC';

		
		//Definindo os parametros
		foreach ($fields as $campo => $opcoes){
			if($campo != $dados['prefixo'].'CODIGO'){
				$campos[] = $campo;
				$params[] = ':'.strtolower($campo);
			}else{
				if($fields[$dados['prefixo'].'CODIGO']['valor'] != ''){
					$tipoTransaction = 'ALT';
				}
			}
		}

		//Inserindo data e usuário de inclusão/alteração
		$campos[] = $dados['prefixo'].'USU_'.$tipoTransaction;
		$params[] = ':'.strtolower($dados['prefixo']).'usu_'.strtolower($tipoTransaction);
		$fields[$dados['prefixo'].'USU_'.$tipoTransaction] = array(
			'valor'  => (int) $_SESSION['SI_USUCODIGO'],
			'filtro' => PDO::PARAM_INT
		);

		//Verificando se foi informado o status, caso não, será definido com 'S'
		if(!in_array($dados['prefixo'].'STATUS', $campos)){
			$campos[] = $dados['prefixo'].'STATUS';
			$params[] = ':'.strtolower($dados['prefixo'].'STATUS');
			$fields[$dados['prefixo'].'STATUS'] = array(
				'valor'  => 'S',
				'filtro' => PDO::PARAM_STR
			);
		}

		//Montando a query
		if($tipoTransaction == 'ALT'){
			$sql = "UPDATE ".$dados['tabela']." SET ";
			if(count($campos) == count($params)){
				foreach ($campos as $i => $campo) {
					$sql .= "$campo = {$params[$i]}, ";
				}
			}else{
				return false;
			}
			$sql .= $dados['prefixo']."DATA_{$tipoTransaction} = NOW() ";
			$sql .= "WHERE ".$dados['prefixo']."CODIGO = :".strtolower($dados['prefixo'].'CODIGO');
		}else{
			if(isset($fields[$dados['prefixo'].'CODIGO'])){
				unset($fields[$dados['prefixo'].'CODIGO']);
			}
			// if (array_key_exists($dados['prefixo']."DATA_{$tipoTransaction}", $params)) {
				$sql = "INSERT INTO ".$dados['tabela']." (".implode(', ', $campos).") VALUES(".implode(', ', $params).")";
			// } else {
				// $sql = "INSERT INTO ".$dados['tabela']." (".implode(', ', $campos).", ".$dados['prefixo']."DATA_{$tipoTransaction}) VALUES(".implode(', ', $params).", NOW())";
			// }
		}
		$sql .= ';';
		

		//Iniciando a conexão
		$db = conectarBanco();

		//Preparando a query
		$query = $db->prepare($sql);
		
		// if ($dados['debug'] == 'S'){
		// echo $sql;
		// die;	
		// } 
		 
		//Filtrando os valores
		foreach ($fields as $field => $opcoes) {
			$query->BindValue(':'.strtolower($field), $opcoes['valor'], $opcoes['filtro']);
		}

		//Executando
		$query->Execute();
		
		//Retornando o Id inserido, ou modificado
		if($tipoTransaction == 'INC')
			$vID = $db->lastInsertId();
		else
			$vID = $fields[$dados['prefixo'].'CODIGO']['valor'];
		
		// if ($dados['debug'] == 'S') { echo 'ID = '.$vID; return;} 
		// sweetAlert('', '', 'S', $dados['url'].'?method=update&oid='.$vID, $dados['msg']);
		
		//Retornando o Id inserido, ou modificado
		return $vID;
	}catch(PDOException $e){
		// if ($dados['debug'] == 'S') {echo $e; return;} 
		// sweetAlert('', '', 'E', '', $dados['msg']);
		print_r($e->getMessage());
	}
}

//Função para trazer todos os fields de uma única tabela, quando informado o codigo
function fillUnico($dados){
	try{
		//Monatando a query
		$sql = "SELECT * FROM ".$dados['tabela']." WHERE ".$dados['prefixo']."CODIGO = :codigo;";

		//Iniciando a conexão
		$db = conectarBanco();

		//Preparando a query
		$query = $db->prepare($sql);
		
		//Filtrando os valores
		$query->BindValue(':codigo', $dados['codigo'], PDO::PARAM_INT);

		//Executando
		if($query->Execute()){
			//Retornando os dados
			$row = $query->fetch(PDO::FETCH_ASSOC);
			return $row;
		}

		return false;
	}catch(Exception $e){
		return $e;
	}
}


//Função para consultas compostas
function consultaComposta($dados){
	try{
		$sql = trim($dados['query']);

		//Iniciando a conexão
		$db = conectarBanco();

		//Preparando a query
		$query = $db->prepare($sql);
		
		//Filtrando os valores
		if(isset($dados['parametros']) && !empty($dados['parametros']))
			foreach($dados['parametros'] as $i => $parametro){
				$query->BindValue($i+1, $parametro[0], $parametro[1]);
			}

		//Executando
		if($query->Execute()){
			//Retornando os dados
			$dados = ($query->rowCount() > 0) ? $query->fetchall(PDO::FETCH_ASSOC) : array();
			return array(
						'quantidadeRegistros' => $query->rowCount(),
						'dados'               => $dados
					);
		}

	}catch(Exception $e){
		return $e;
	}
}

//Função para "DELETAR" registros
function deletarRegistro($dados){
	$sql = "UPDATE ".$dados['tabela']." SET ".$dados['prefixo']."STATUS = 'N', ".$dados['prefixo']."USU_ALT = :usuario, ".$dados['prefixo']."DATA_ALT = NOW() WHERE ".$dados['prefixo']."CODIGO = :codigo;";
	//Iniciando a conexão
	$db = conectarBanco();

	//Preparando a query
	$query = $db->prepare($sql);
	
	//Filtrando os valores
	$query->BindValue(':codigo', $dados['codigo'], PDO::PARAM_INT);
	$query->BindValue(':usuario', $_SESSION['SI_USUCODIGO'], PDO::PARAM_INT);

	//Executando
	if($query->Execute()){
		return true;
	}

	return false;
}

//Função para ordenar e limitar as consultas das grids
function limitDataTables($ordemColunas, $dados){
	$sql = $ordemColunas[$dados['order'][0]['column']].' '.strtoupper($dados['order'][0]['dir']).' ';
	if($dados['length'] > 0)
		$sql .= "LIMIT ".$dados['start'].', '.$dados['length'];
	return $sql;
}

//Função para contar os registros ativos
function countRegistros($tabela, $prefixo){
	try{
		$sql = "SELECT count({$prefixo}CODIGO) AS qtd FROM {$tabela} WHERE {$prefixo}STATUS = 'S'";

		//Iniciando a conexão
		$db = conectarBanco();

		//Preparando a query
		$query = $db->prepare($sql);

		//Executando
		if($query->Execute()){
			$result = $query->fetch(PDO::FETCH_ASSOC);
			return $result['qtd'];
		}

	}catch(Exception $e){
		return $e;
	}
}

//Função para criar tabelas no banco de dados
function createTables($sql){
	try{
		//Iniciando a conexão
		$db = conectarBanco();

		//Executando a query
		return $db->exec($sql);

	}catch(Exception $e){
		return $e;
	}
}
function filterNumber($pSString) {
	return  preg_replace( '/[^0-9]/', '', $pSString );
}
/*************************************************
	Data: 22/10/2009 - Pedro Godinho
	Formatar CNPJ
	$pCNPJ: parâmetro CNPJ
***************************************************/
function formatar_cnpj($pCNPJ){
	$pCNPJ = filterNumber($pCNPJ);
	$pCNPJ = str_pad($pCNPJ, 14, '0', STR_PAD_LEFT);
	return substr($pCNPJ,0,2).".".substr($pCNPJ,2,3).".".substr($pCNPJ,5,3)."/".substr($pCNPJ,8,4)."-".substr($pCNPJ,12,2);
}

/*************************************************
	Data: 22/10/2009 - Pedro Godinho
	Formatar CPF
	$pCPF: parâmetro CPF
***************************************************/
function formatar_cpf($pCPF){
	$pCPF = filterNumber($pCPF);
	$pCPF = str_pad($pCPF, 11, '0', STR_PAD_LEFT);
	return substr($pCPF,0,3).".".substr($pCPF,3,3).".".substr($pCPF,6,3)."-".substr($pCPF,9,4);
}

function getTable($codigolegado, $tipo)
{
	$response = consultaComposta(array(
		'query' => "SELECT
						TABCODIGO
					FROM
						TABELAS
					WHERE
						TABTIPO = ? AND
						TABDESCRICAOAUX = ?",
		'parametros' => array(
			array($tipo, PDO::PARAM_STR),
			array($codigolegado, PDO::PARAM_INT),
		)
	));

	return ($response['quantidadeRegistros'] > 0) ? $response['dados'][0]['TABCODIGO'] : 0;
}

function getTableComplemento($codigolegado, $tipo)
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

function getConsultor($pIUSUCODIGOVENDEDOR)
{
	
	$response = consultaComposta(array(
		'query' => "SELECT
						USUCODIGO
					FROM
						USUARIOS
					WHERE
						USUCODIGOVENDEDOR = ?",
		'parametros' => array(
			array($pIUSUCODIGOVENDEDOR , PDO::PARAM_INT), 
		)
	));
	
	if ($response['quantidadeRegistros'] > 0) {
		echo '----'.$pIUSUCODIGOVENDEDOR.'----';
		return $response['dados'][0]['USUCODIGO'];
	}

	return 0;
	
/*
(56, 'Alexandra Skibinski Lopes'),
(57, 'Aline Ribeiro dos Santos'),
(87, 'Ana Paula Correa'),
(58, 'Ana Paula Machado Andreazza'),
(4, 'Andre Luiz Lima Teer'),
(41, 'Andreia da Silva Lourenço'),
(42, 'Andrielle Dias Pinheiro'),
(29, 'Aparecida Beatriz Manuela Lima'),
(33, 'Benício Alexandre Aragão'),
(59, 'Brenda  Iparraguirre Martins'),
(60, 'Bruna Machado Bittencourt Rodenbush'),
(13, 'Caleb Fernando Anderson da Cunha'),
(6, 'Caleb Fernando Anderson da Cunha'),
(17, 'Carlos Eduardo Davi Paulo Mendes'),
(24, 'Carolina Fátima Priscila Sales'),
(23, 'Carolina Fátima Priscila Sales'),
(43, 'Caroline Portel Fiorenza Rodrigues'),
(44, 'Caroline Vieira de Souza'),
(25, 'Catarina Cláudia Yasmin da Cruz'),
(16, 'Cauã Murilo Carvalho'),
(22, 'Cecília Emilly Vitória Oliveira'),
(46, 'Claudemir da Silveira Cuti'),
(62, 'Cristina  da Silva Fagundes'),
(35, 'danielle'),
(36, 'Danielle Berwig Möller'),
(94, 'Dolly Outeiral '),
(93, 'Dra Dolly'),
(11, 'Fernanda Stella Baptista'),
(47, 'Franciele Rodrigues da Silva'),
(91, 'Franciele Zamboni Pires'),
(52, 'Francine Machado Pinto'),
(92, 'Francisco Junior'),
(74, 'Gabriel Dal Corso Oliveira'),
(30, 'Greice Silva Soares'),
(20, 'Heloise Jéssica Souza'),
(19, 'Isabelle Jaqueline Cecília Silveira'),
(88, 'Jandira Anes'),
(67, 'Jessica Fazenda Buffon'),
(83, 'Joao Fernando Kliemann'),
(7, 'Jorge Martin Diego Peixoto'),
(69, 'Julia Cristiane Fortes Brum'),
(90, 'Laura Pinto Ferreira'),
(89, 'Letícia Porciuncula'),
(70, 'Liliane Mazzoni Rodrigues'),
(9, 'Lívia Sandra Laís Gomes'),
(21, 'Lorena Isadora Campos'),
(71, 'Lucia Gabriela dos Santos Both'),
(14, 'Luiz Kaique Carlos Caldeira'),
(85, 'Maria Aparecida de Mattos Moura'),
(48, 'Marina Dias Mendes Totta'),
(12, 'Mário Alexandre Vicente Almeida'),
(75, 'Mauren Codevilla da Porciuncula'),
(49, 'Michela M Machado'),
(28, 'Nicole Beatriz da Cunha'),
(84, 'Recursos Humanos'),
(32, 'Renato Manuel Galvão'),
(50, 'Roberta Silva Cardoso'),
(95, 'Rosemari Silva Soares'),
(38, 'Samuel Elias da Silva'),
(15, 'Sebastiana Sueli Monteiro'),
(51, 'Sergio Sunol dos Santos'),
(26, 'Sônia da Mata'),
(27, 'Sônia da Mata'),
(8, 'Stella Louise Ana Teixeira'),
(53, 'Suzana da Veiga Focking'),
(73, 'Taina Cardoso Vieira'),
(18, 'Thiago Murilo Iago Assis'),
(86, 'Vanessa Beatriz Cabral'),
(54, 'Vanessa Montalvão Dias'),
(55, 'Vinicius dos Santos Brito'),
(98, 'William Silva Soares'),
(10, 'Yasmin Fátima da Costa'),
(31, 'Yasmin Sarah Souza');

	$consultores = array(
		1    => 96,  // "VALDOMIRO G. S.                         "
		383  => 0,  // "CRM RS CLIENTES                         "
		221  => 0,  // "COMERCIAL N/INTERESSE                   "
		33   => 80, // "Edilson Brazil                          "
		20   => 97,  // "MICHAEL SILVA SOARES                    "
		375  => 0,  // "CRM RS PROSP                            "
		361  => 0,  // "CRM PR                                  "
		396  => 0,  // "CRM N/INTERESSE                         "
		47   => 79, // "Martinha Borghetti                      "
		426  => 61, // "Caroline Pires                          "
		87   => 82, // "Izilda                                  "
		1007 => 66,  // "Janaina Ferreira                        "
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
		1004 => 39,  // "Adriana                                 "
		1019 => 0,  // "Nelson de Oliveira                      "
		153  => 78, // "Simone Souza                            "
		1020 => 65, // "Guilherme Popko                         "
		1040 => 0,  // "Ismael Decol                            "
		1038 => 40, // "Amanda Estefani da Silva Bocarite -     "
		1042 => 0,  // "Lisiane Vargas                          "
		1041 => 72, // "Caroline Lima Troleis                   "
	);

	return array_key_exists($this->client['codigovendedor'], $consultores) ? $consultores[$this->client['codigovendedor']] : 0;
	 */
}

function findCidade($cidade)
{
	$response = consultaComposta(array(
		'query' => "SELECT
						CIDCODIGO
					FROM
						CIDADES
					WHERE
						CIDDESCRICAO LIKE ?",
		'parametros' => array(
			array('%'.trim($cidade).'%', PDO::PARAM_STR),
		)
	));

	if ($response['quantidadeRegistros'] > 0) {
		return $response['dados'][0]['CIDCODIGO'];
	}

	return 0;
}

function findEstado($estado)
{
	$response = consultaComposta(array(
		'query' => "SELECT
						ESTCODIGO
					FROM
						ESTADOS
					WHERE
						ESTSIGLA = ?",
		'parametros' => array(
			array(trim($estado), PDO::PARAM_STR),
		)
	));

	if ($response['quantidadeRegistros'] > 0) {
		return $response['dados'][0]['ESTCODIGO'];
	}

	return 0;
}