<?php
include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'include/constantes.php';
include_once __DIR__.DIRECTORY_SEPARATOR.'funcoes.php';

if( isset( $_GET['method'] ) ){
	if( $_GET['method'] == 'excluirAtivarRegistros' ) {
		excluirAtivarRegistros( $_GET );
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