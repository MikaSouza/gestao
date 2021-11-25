<?php
/*************************************************
	Data: 30/08/2019 - Pedro Godinho
	Conectar banco de dados produção
***************************************************/
function sql_conectar_banco(){

	//if(!isset($_SESSION['SI_ID_USUARIO']))
	//	die("<script language='javaScript'>window.location.assign('".URL_LOGIN."')</script>");

	$conexao = mysqli_connect(vGHostSite, vGUsername, vGPassword, vGBancoSite) or die(mysqli_error());
	mysqli_set_charset($conexao, "utf8");
	return $conexao;
}

function sql_conectar_banco_login(){
	$conexao = mysqli_connect(vGHostSite, vGUsername, vGPassword) or die(mysqli_error());
	mysqli_set_charset($conexao, "utf8");
	return $conexao;
}

function sql_conectar_banco_logini(){
	$conexao = mysqli_connect(vGHostSite, vGUsername, vGPassword, vGBancoSite) or die(mysqli_error());
	return $conexao;
}

function sql_conectar_bancoi(){

	if(!isset($_SESSION['SI_ID_USUARIO']))
		die("<script language='javaScript'>window.location.assign('".URL_LOGIN."')</script>");

	$conexao = mysqli_connect(vGHostSite, vGUsername, vGPassword, vGBancoSite) or die(mysqli_error());
	mysqli_set_charset($conexao, "utf8");
	return $conexao;
}

function sql_fechar_conexao_banco($pConexao){
	if  (empty($pConexao)) return "";
	mysqli_close($pConexao);
	 /* alteração de versão Mysql
	if	(empty($pConexao)) return "";
	mysql_close($pConexao);*/
}

function sql_executa($pBanco, $pConexao, $pSql) {

	if(empty($pConexao) or empty($pSql))
		return "";
	mysqli_select_db($pConexao, $pBanco);
	$aux = mysqli_query($pConexao, $pSql);
	if(!$aux){
		echo "********* ERRO *********<BR>";
		echo "SQL = ".nl2br($pSql)."<BR><BR>";
		echo mysqli_error ($pConexao);
		echo "<BR>*****<BR>";
		return 0;
	}
	return $aux;
}

function sql_record_count($pSql){
	if (empty($pSql)) return 0;
	return mysqli_num_rows($pSql);		
}

function sql_retorno_lista($pSql){
	if(empty($pSql)) return "";
		  return mysqli_fetch_array($pSql, MYSQLI_ASSOC);
}

function sql_retorno_listai($pSql){
	   if(empty($pSql)) return "";
		  return mysqli_fetch_array($pSql, MYSQLI_ASSOC);
}

$nome_funcoes_mysql = array(
					"NULL",
					"CURDATE()",
					"CURRENT_DATE()",
					"CURRENT_TIME()",
					"CURRENT_TIMESTAMP()",
					"CURTIME()",
					"DATE()",
					"DAY()",
					"NOW()",
					"SYSDATE()",
					"SYSDATE()",
					"USER()",
					"UTC_DATE()",
					"UTC_TIME()",
					"UTC_TIMESTAMP()",
					"VERSION()",
					"DATABASE()",
					"SCHEMA()");

function montaSqlCamposInsert($pCampo,$pValor,$pTabela){

	global $nome_funcoes_mysql;

	$query_fields = '';
	$query_values = '';

	$count_campo = count($pCampo);
	$count_valor = count($pValor);

	if ( $count_campo == $count_valor ) {

		$count_campo--;
		$i = 0;

		for ($i; $i <= $count_campo; $i++) {

			$query_fields .= $pCampo[$i] . ',';
			
			if ( is_array($nome_funcoes_mysql) && in_array(trim(strtoupper($pValor[$i])), $nome_funcoes_mysql) ) { 				
			//if( in_array(trim(strtoupper($pValor[$i])), $nome_funcoes_mysql) ){
				$query_values .= ' ' . strtoupper($pValor[$i]) . ',';
			} else {

				$valor_escapado = trim(str_replace('"', " ", str_replace("'", " ", $pValor[$i])));
				$valor_escapado = '"'.$valor_escapado.'"';

				$query_values .= $valor_escapado . ',';
			}
		}
		$query_fields = rtrim($query_fields, ",");
		$query_values = rtrim($query_values, ",");

		return "INSERT INTO ".$pTabela." (".$query_fields.") VALUES (".$query_values.")";

	} else {
		return "O tamanho do array pCampo diferente do tamanho do array pValor.";
	}
}

function montaSqlCamposUpdate($pCampo,$pValor,$pTabela,$pClausula){

	global $nome_funcoes_mysql;

	$count_campos = ( count($pCampo) == count($pValor) ) ? count( $pValor ) : null;

	if( $count_campos != null ){

		$valor_escapado = "";
		$count_campos--;
		$query_campos = "";
		$i = 0;
		$valor = "";

		for( $i; $i <= $count_campos; $i++ ){

			if( in_array(trim(strtoupper($pValor[$i])), $nome_funcoes_mysql) ){
				$query_campos .= $pCampo[$i].' = '.strtoupper($pValor[$i]).', ';
			} else {

				$valor_escapado = trim(str_replace('"', " ", str_replace("'", " ", $pValor[$i])));
				$valor_escapado = '"'.$valor_escapado.'"';

				$query_campos .= $pCampo[$i].' = ' . $valor_escapado . ', ';

			}
		}
		$query_campos = rtrim($query_campos, ", ");

		return 'Update  '.$pTabela.' set '. $query_campos .' where '.$pClausula;

	}else {
		return "O tamanho do array pCampo diferente do tamanho do array pValor.";
	}

}

function montaSqlCamposDelete($pTabela,$pClausula){
	$sql = "Delete from ".$pTabela." where ".$pClausula;
	return $sql;
}

function verificarParametro($pIValor ,$pSTipo, $login = false){
	$vConexao = !$login ? sql_conectar_banco() : sql_conectar_banco_login();
	$Sql = "Select
				PARCODIGO
			From
				PARAMETROS
			Where
				PARSTATUS = 'S' AND
				PARTIPO = '".$pSTipo."' AND
				EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA'];
	if ($pIValor > 0)
		$Sql .= " and PARDESCRICAO = '".$pIValor."'";
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);
	while($reg_post = sql_retorno_lista($RS_POST)){
		$vITemp = $reg_post['PARCODIGO'];
	}
	return $vITemp;
}

function verificarParametro2($pIValor ,$pSTipo){
	$vConexao = sql_conectar_banco();
	$Sql = "Select
				PARCODIGO
			From
				PARAMETROS
			Where
				PARSTATUS = 'S' AND
				PARTIPO = '".$pSTipo."' ";
	if ($pIValor > 0)
		$Sql .= " and PARDESCRICAO = '".$pIValor."'";
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);
	while($reg_post = sql_retorno_lista($RS_POST)){
		$vITemp = $reg_post['PARCODIGO'];
	}
	return $vITemp;
}

function getParametroValor($pSTipo, $login = false){
	$vConexao = !$login ? sql_conectar_banco() : sql_conectar_banco_login();
	$Sql = "SELECT
				PARDESCRICAO
			FROM
				PARAMETROS
			WHERE
				PARSTATUS = 'S' AND
				PARTIPO = '".$pSTipo."' AND
				EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA'];

	$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql);
	while($reg_post = sql_retorno_lista($RS_POST)){
		$vITemp = $reg_post['PARDESCRICAO'];
	}
	return $vITemp;
}

function stringEscape($pSString) {
	$patterns[0] = '/<\?php/';
	$patterns[1] = '/<\?/';
	$patterns[2] = '/\?>/';
	$replacements[2] = '--';
	$replacements[1] = '--';
	$replacements[0] = '--';
	$pSString = preg_replace($patterns, $replacements, $pSString);

	return $pSString;
}

function proxima_Sequencial($pSSQNTABELA, $pSTipo){
	$SqlMain = 'Select SQNSEQUENCIAL
				 From
					SEQUENCIAIS
				Where
					SQNTABELA = "'.$pSSQNTABELA.'"';
	$vConexao = sql_conectar_banco();
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	while($reg_RS = sql_retorno_lista($RS_MAIN)){
		$vTemp = $reg_RS['SQNSEQUENCIAL'] + 1;
	}
	if ($pSTipo == 'S') {	
		if ($vTemp == 0) {  // incluir
			$SqlMain = 'Insert into SEQUENCIAIS (SQNDATA_INC, SQNUSU_INC, SQNSEQUENCIAL, SQNTABELA) values ( NOW(), '.$_SESSION['SI_USUCODIGO'].', 1, "'.$pSSQNTABELA.'")';
			$vTemp = 1;
		} else {
			$SqlMain = 'Update SEQUENCIAIS set SQNSEQUENCIAL = '.$vTemp.'
						Where 
						SQNTABELA = "'.$pSSQNTABELA.'"';
		}
		$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	}	
	return $vTemp;
}

function validaAcessoGet($pSHttpReferer) {
	echo $pSHttpReferer;
	$pos = strripos(substr(strrchr($pSHttpReferer, "/"), 1, strlen(strrchr($pSHttpReferer, "/"))), "?");
	if ($pos === false) {
		$pSHttpReferer = substr(strrchr($pSHttpReferer, "/"), 1, strlen(strrchr($pSHttpReferer, "/")));
	} else {
		$pSHttpReferer = strstr(substr(strrchr($pSHttpReferer, "/"), 1, strlen(strrchr($pSHttpReferer, "/"))), '?', true);
	}

	echo $pSHttpReferer;
	if(trim($pSHttpReferer) != "") {
		$vITotal = 0;
		$vSSql = "SELECT
						COUNT(MENCODIGO) as TOTAL
					FROM
						MENUS
					WHERE
						MENARQUIVOLIST = '".trim($pSHttpReferer)."' OR
						MENARQUIVOCAD = '".trim($pSHttpReferer)."'";

		$vConexao = sql_conectar_banco();
		$RS_MAIN = sql_executa(vGBancoSite, $vConexao, $vSSql);
		while($reg_RS = sql_retorno_lista($RS_MAIN)) {
			$vITotal = $reg_RS['TOTAL'];
		}
	} else {
		$vITotal = 0;
	}
	return $vITotal;
}


function getPeriodicidadeReal( $pIPERCODIGO ){

	if( $pIPERCODIGO == '' ){
		return array(
			'periodicidade_adicional' => 'Mensal',
			'periodicidade_base' => 0
		);
	}


	$sqlPeriodoReal = "	SELECT
							p.PERPERIODOADICIONAL as periodicidade_adicional,
							p.PERPERIODOBASE as periodicidade_base
						FROM
							PERIODICIDADES p
						WHERE
							p.PERSTATUS = 'S'
						AND
							p.PERCODIGO =" . $pIPERCODIGO;

	$conexao_periodo_real = sql_conectar_banco();
	$resultSet = sql_executa(vGBancoSite, $conexao_periodo_real, $sqlPeriodoReal);

	$registro = sql_retorno_lista($resultSet);

	sql_fechar_conexao_banco($conexao_periodo_real);

	return ($registro == "" || !is_array($registro)) ? false : $registro;

}

function configuracoes_menu_acesso($pIOIDMENU){
	$SqlMain = 'Select MENCODIGO, MENPREFIXO, MENARQUIVOTRAN, MENTITULO, MENGRUPO,
					   MENARQUIVOCAD, MENARQUIVOLIST, MENTABELABANCO, MENTITULOFUNC
				 From
					MENUS
				Where
					MENCODIGO = '.$pIOIDMENU;
	$vConexao = sql_conectar_banco();
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$vAConfig = sql_retorno_lista($RS_MAIN);
	return ( $vAConfig == "" || !is_array($vAConfig) ) ? false : $vAConfig;
}

function buscarIDUsuario($pIUSUSEQUENCIAL) {
	$Sql5 = 'Select USUCODIGO  
				 From USUARIOS 
				 Where USUSEQUENCIAL = '. $pIUSUSEQUENCIAL.' and EMPCODIGO = '.$_SESSION['SI_USU_EMPRESA'];
	$vConexao = sql_conectar_banco();				 
	$RS_MAIN5 = sql_executa(vGBancoSite, $vConexao,$Sql5); 
	$vIUSUCODIGO = 0;
	while($reg_RS5 = sql_retorno_lista($RS_MAIN5)){ 
		$vIUSUCODIGO = $reg_RS5['USUCODIGO'];
	}
	return $vIUSUCODIGO;
}

function fillDescricaoProcesso($pSDescricao, $pIOrigem, $pIKEY){
	$vConexao = !$login ? sql_conectar_banco() : sql_conectar_banco_login();
	$Sql = "SELECT
				MENTABELABANCO, MENPREFIXO
			FROM
				MENUS
			WHERE                    
				MENCODIGO = '{$pIOrigem}'";
	$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql);
	$reg_post = sql_retorno_lista($RS_POST);
	// vinculo KEY
	$Sql = "SELECT
				*
			FROM
				{$reg_post['MENTABELABANCO']}			
			WHERE                    
				{$reg_post['MENPREFIXO']}CODIGO = {$pIKEY}";
	$RS_POST2 = sql_executa(vGBancoSite, $vConexao, $Sql);
	$reg_postkey = sql_retorno_lista($RS_POST2);
	//	LEFT JOIN CLIENTES ON CLIENTES.CLICODIGO = 	{$reg_post['MENTABELABANCO']}.CLICODIGO		
	$Sql = "SHOW COLUMNS FROM				
			{$reg_post['MENTABELABANCO']}";
	$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql);		
	while($reg_RS5 = sql_retorno_lista($RS_POST)){			
		$pSDescricao = str_replace("[{$reg_RS5['Field']}]", $reg_postkey[$reg_RS5["Field"]], $pSDescricao);
	}
	return $pSDescricao;
}

// ****************** PDO  ******************

//Função para remover os prefixos "vS" e "vI"
function removePrefix($a) {
	return substr($a, 2);
}

//Conexão com o banco
function conectarBanco(){
	try{
		
		$user = ($_SESSION['DB_CONN'] == 'MN') ? vGUsernameMN : vGUsername;
		$pass = ($_SESSION['DB_CONN'] == 'MN') ? vGPasswordMN : vGPassword;
		$db_name = ($_SESSION['DB_CONN'] == 'MN') ? vGBancoSiteMN : vGBancoSite;
		$host = ($_SESSION['DB_CONN'] == 'MN') ? vGHostSiteMN : vGHostSite;
		
		$db = new PDO("mysql:host=".$host.";dbname=".$db_name.";charset=UTF8", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
		return $db;
	}catch(Exception $e){
		return $e;
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
		return false;
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


		if ($dados['debug'] == 'S') pre($dados);

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
			$sql = "INSERT INTO ".$dados['tabela']." (".implode(', ', $campos).", ".$dados['prefixo']."DATA_{$tipoTransaction}) VALUES(".implode(', ', $params).", NOW())";
		}
		$sql .= ';';
		

		//Iniciando a conexão
		$db = conectarBanco();

		//Preparando a query
		$query = $db->prepare($sql);
		
		if ($dados['debug'] == 'S') echo $sql;
		 
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
		
		if ($dados['debug'] == 'S') { echo 'ID = '.$vID; return;} 
		sweetAlert('', '', 'S', $dados['url'].'?method=update&oid='.$vID, $dados['msg']);
		
		//Retornando o Id inserido, ou modificado
		return $vID;
	}catch(PDOException $e){
		if ($dados['debug'] == 'S') {echo $e; return;} 
		sweetAlert('', '', 'E', '', $dados['msg']);
		return $e;
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
		$sql = !is_array($dados) ? trim($dados) : trim($dados['query']);

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
	$concluido = false;
	$sql = "UPDATE ".$dados['tabela']." SET ".$dados['prefixo']."STATUS = 'N', ".$dados['prefixo']."USU_ALT = :usuario, ".$dados['prefixo']."DATA_ALT = NOW() WHERE ".$dados['prefixo']."CODIGO = :codigo;";
	//Iniciando a conexão
	$db = conectarBanco();

	//Preparando a query
	$query = $db->prepare($sql);
	
	//Filtrando os valores
	$query->BindValue(':codigo', $dados['codigo'], PDO::PARAM_INT);
	$query->BindValue(':usuario', $_SESSION['SI_USUCODIGO'], PDO::PARAM_INT);

	//Executando
	if($query->Execute()) $concluido = true;
	
	if ($dados['mensagem'] == "S") {
		if($concluido)
			return "Registro(s) excluído(s) com sucesso.";
		else
			return "Erro: Não foi possível excluir o(s) registro(s).";			
	} else
		return $concluido;		
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

function excluirAtivarRegistros( $configs ) {

	$concluido = false;
	$configs['tabela'] = Desencriptar( $configs['tabela'], 'crud');

	$sql = "UPDATE
				".$configs['tabela']."
			SET
				".$configs['prefixo']."STATUS = '".$configs['status']."'
			WHERE ";

			if($configs['empresa'] == 'S')
				$sql .=	" EMPCODIGO IN (".$_SESSION['SA_EMPRESAS'].") AND ";

			$sql .=	$configs['prefixo']."CODIGO IN (".$configs['ids'].")";

	$vConexao = sql_conectar_banco();
	if (sql_executa(vGBancoSite, $vConexao, $sql, false)){
		$concluido = true;
	}
	sql_fechar_conexao_banco($vConexao);

	if ($configs['mensagem'] == "S") {
		if($concluido) {
			return "Registro(s) excluído(s) com sucesso.";
		} else {
			return "Erro: Não foi possível excluir o(s) registro(s).";
		}
	} else {
		return $concluido;
	}
}

?>