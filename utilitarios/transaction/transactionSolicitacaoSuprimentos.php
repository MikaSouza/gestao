<?php
if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {
    $vIOid = insertUpdateSolicitacaoSuprimentos($_POST, 'N');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_SolicitacaoSuprimentos($_GET['oid'], $vAConfiguracaoTela);
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
}

if(isset($_POST["method"]) && $_POST["method"] == 'excluirPadrao') {
	include_once '../../twcore/teraware/php/constantes.php';
	$pAConfiguracaoTela = configuracoes_menu_acesso($_POST["vIOIDMENU"]);
	$config_excluir = array(
		"tabela" => Encriptar($pAConfiguracaoTela['MENTABELABANCO'], 'crud'),
		"prefixo" => $pAConfiguracaoTela['MENPREFIXO'],
		"status" => "N",
		"ids" => $_POST['pSCodigos'],
		"mensagem" => "S",
		"empresa" => "N"
	);
	echo excluirAtivarRegistros($config_excluir);
}

function listSolicitacaoSuprimentos($_POSTDADOS){
	$where = '';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME']))
		$where .= 'AND p.PRONOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vISOESOLICITANTE']))
		$where .= 'AND s.SOESOLICITANTE = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND s.SOEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND s.SOEDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaInicio']))
		$where .= 'AND s.SOEDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaFim']))
		$where .= 'AND s.SOEDATA_INC <= ? ';
	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSSOEPOSICAO'])){
		$where .= 'AND s.SOEPOSICAO = ? ';
	}else 
		$where .= "AND s.SOESTATUS = 'S' ";
	/*, '<br/>',
						IFNULL(p2.PRONOME, ''), !IFNULL(p2.PRONOME, ' - '), IFNULL(s.SOEQUANTIDADE2, ''), !IFNULL(p2.PRONOME, '<br/>'),
						IFNULL(p3.PRONOME, ''), !IFNULL(p3.PRONOME, ' - '), IFNULL(s.SOEQUANTIDADE3, ''), !IFNULL(p3.PRONOME, '<br/>'),
						IFNULL(p4.PRONOME, ''), !IFNULL(p4.PRONOME, ' - '), IFNULL(s.SOEQUANTIDADE4, ''), !IFNULL(p4.PRONOME, '<br/>'),
						IFNULL(p5.PRONOME, ''), !IFNULL(p5.PRONOME, ' - '), IFNULL(s.SOEQUANTIDADE5, '')
	*/
	$sql = "SELECT
					s.SOECODIGO,
					s.SOEPOSICAO,
					s.SOEDATA_INC,
					CONCAT(
						p.PRONOME, ' - ', s.SOEQUANTIDADE1) AS PRODUTO,
					u.USUNOME AS SOLICITANTE,
					t.TABDESCRICAO AS DEPARTAMENTO
				FROM
					SOLICITACAOESTOQUE s
				LEFT JOIN PRODUTOS p ON p.PROCODIGO = s.PROCODIGO1
				LEFT JOIN PRODUTOS p2 ON p2.PROCODIGO = s.PROCODIGO2
				LEFT JOIN PRODUTOS p3 ON p3.PROCODIGO = s.PROCODIGO3
				LEFT JOIN PRODUTOS p4 ON p4.PROCODIGO = s.PROCODIGO4
				LEFT JOIN PRODUTOS p5 ON p5.PROCODIGO = s.PROCODIGO5
				LEFT JOIN
					USUARIOS u ON u.USUCODIGO = s.SOESOLICITANTE
				LEFT JOIN
					TABELAS t ON u.TABDEPARTAMENTO = t.TABCODIGO
				WHERE
					1 = 1 ". $where."
				ORDER BY s.SOEDATA_INC desc";				
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vSPRONOME'])){
		$vSPRONOME = $_POSTDADOS['FILTROS']['vSPRONOME'];
		$arrayQuery['parametros'][] = array("%$vSPRONOME%", PDO::PARAM_STR);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vISOESOLICITANTE']))		
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vISOESOLICITANTE'], PDO::PARAM_INT);	
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}	
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataEntregaInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataEntregaFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataEntregaFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}	
	if(verificarVazio($_POSTDADOS['FILTROS']['vSSOEPOSICAO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSSOEPOSICAO'], PDO::PARAM_STR);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateSolicitacaoSuprimentos($_POSTDADOS, $pSMsg = 'N'){
	$dadosBanco = array(
		'tabela'  => 'SOLICITACAOESTOQUE',
		'prefixo' => 'SOE',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	enviarEmailEstoque($id, $_POSTDADOS['vSSOEPOSICAO']);
	sweetAlert('', '', 'S', 'cadSolicitacaoSuprimentos.php?method=update&oid='.$id, 'S');
	return $id;
}

function fill_SolicitacaoSuprimentos($pOid){
	$SqlMain = "SELECT
                    s.SOECODIGO,
					s.SOEDATA_INC,
					s.SOESOLICITANTE,
					s.SOEQUANTIDADE1,
					s.SOEQUANTIDADE2,
					s.SOEQUANTIDADE3,
					s.SOEQUANTIDADE4,
					s.SOEQUANTIDADE5,
					s.PROCODIGO1,
					s.PROCODIGO2,
					s.PROCODIGO3,
					s.PROCODIGO4,
					s.PROCODIGO5,	
					s.SOEPOSICAO,
					p.PRONOME AS PRODUTO,
					p2.PRONOME AS PRODUTO2,
					p3.PRONOME AS PRODUTO3,
					p4.PRONOME AS PRODUTO4,
					p5.PRONOME AS PRODUTO5,
					t.TABDESCRICAO AS PROUNIDADE1,
					t2.TABDESCRICAO AS PROUNIDADE2,
					t3.TABDESCRICAO AS PROUNIDADE3,
					t4.TABDESCRICAO AS PROUNIDADE4,
					t5.TABDESCRICAO AS PROUNIDADE5
                FROM SOLICITACAOESTOQUE s
				LEFT JOIN PRODUTOS p ON	s.PROCODIGO1 = p.PROCODIGO
				LEFT JOIN PRODUTOS p2 ON s.PROCODIGO2 = p2.PROCODIGO
				LEFT JOIN PRODUTOS p3 ON s.PROCODIGO3 = p3.PROCODIGO
				LEFT JOIN PRODUTOS p4 ON s.PROCODIGO4 = p4.PROCODIGO
				LEFT JOIN PRODUTOS p5 ON s.PROCODIGO5 = p5.PROCODIGO
				LEFT JOIN TABELAS t ON t.TABCODIGO = p.PROUNIDADE
				LEFT JOIN TABELAS t2 ON t2.TABCODIGO = p2.PROUNIDADE
				LEFT JOIN TABELAS t3 ON t3.TABCODIGO = p3.PROUNIDADE
				LEFT JOIN TABELAS t4 ON t4.TABCODIGO = p4.PROUNIDADE
				LEFT JOIN TABELAS t5 ON t5.TABCODIGO = p5.PROUNIDADE
                    WHERE SOECODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	return $registro !== null ? $registro : "N";
}

function enviarEmailEstoque($vISOECODIGO, $vSPosicao)
{
	if (($vSPosicao == '') || ($vSPosicao == 'AGUARDANDO'))  $vSPosicao = 'AGUARDANDO ENTREGA';
	$SqlMain = "SELECT
						s.SOECODIGO,
						s.SOEDATA_INC,
						s.SOEQUANTIDADE1,
						s.SOEQUANTIDADE2,
						s.SOEQUANTIDADE3,
						s.SOEQUANTIDADE4,
						s.SOEQUANTIDADE5,
						s.PROCODIGO1,
						s.PROCODIGO2,
						s.PROCODIGO3,
						s.PROCODIGO4,
						s.PROCODIGO5,						
		       			p.PRONOME AS PRODUTO,
						p2.PRONOME AS PRODUTO2,
						p3.PRONOME AS PRODUTO3,
						p4.PRONOME AS PRODUTO4,
						p5.PRONOME AS PRODUTO5,
						u.USUEMAIL,
						u.USUNOME AS SOLICITANTE,
						t2.TABDESCRICAO AS DEPARTAMENTO
					FROM
						SOLICITACAOESTOQUE s
					LEFT JOIN PRODUTOS p ON	 s.PROCODIGO1 = p.PROCODIGO
					LEFT JOIN PRODUTOS p2 ON s.PROCODIGO2 = p2.PROCODIGO
					LEFT JOIN PRODUTOS p3 ON s.PROCODIGO3 = p3.PROCODIGO
					LEFT JOIN PRODUTOS p4 ON s.PROCODIGO4 = p4.PROCODIGO
					LEFT JOIN PRODUTOS p5 ON s.PROCODIGO5 = p5.PROCODIGO
					LEFT JOIN
						USUARIOS u
					ON 
						u.USUCODIGO = s.SOESOLICITANTE	
					LEFT JOIN 
						TABELAS t2 ON t2.TABCODIGO = u.TABDEPARTAMENTO	
					WHERE
						s.SOECODIGO = ".$vISOECODIGO;
					
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$recipients = array();
	while ($regemails = sql_retorno_lista($resultSet)){
		$vISOECODIGO = str_pad($regemails['SOECODIGO'], 5, '0', STR_PAD_LEFT);
		$data_atual  = formatar_data_hora($regemails['SOEDATA_INC']);
		$nomeProduto = $regemails['PRODUTO'];
		$quantidade = $regemails['SOEQUANTIDADE1'];
		$nomeProduto2 = $regemails['PRODUTO2'];
		$quantidade2 = $regemails['SOEQUANTIDADE2'];
		$nomeProduto3 = $regemails['PRODUTO3'];
		$quantidade3 = $regemails['SOEQUANTIDADE3'];
		$nomeProduto4 = $regemails['PRODUTO4'];
		$quantidade4 = $regemails['SOEQUANTIDADE4'];
		$nomeProduto5 = $regemails['PRODUTO5'];
		$quantidade5 = $regemails['SOEQUANTIDADE5'];
		$nomeUsuario = $regemails['SOLICITANTE'];
		$usuarioDepartamento = $regemails['DEPARTAMENTO'];		
		$recipients[] = $regemails['USUEMAIL'];
	}		
	
	sql_fechar_conexao_banco($vConexao);	
	$recipients[] = 'suprimentos@marpa.com.br';
	$recipients[] = 'sistema@marpa.com.br';

	$Assunto    = 	"Solicitação de Estoque - ".$vSPosicao;
	$Mensagem   = 	"<b>Módulo Estoque/Suprimentos - E-mail Automático</b><br />";
	$Mensagem  .= 	"<i>\"Por favor não responda a este e-mail, pois esta mensagem é uma aviso automático do Módulo Estoque/Suprimentos.\"<br /></i><br /><br />";
	$Mensagem  .= 	"<i>\"<b>A entrega de material será SEMPRE as terças e quintas até 11:30.<b>\"<br /></i><br /><br />";
	
	$Mensagem  .= 	"<table style='border-collapse:collapse;' border='1' width='100%'>
						<thead>
							<tr style='background-color:#E8EAE8'>
								<th>Solicitação nº</th>
								<th>Data</th>
								<th>Setor</th>
								<th>Solicitante</th>								
							</tr>
						</thead>
						<tbody>
							<tr style='background-color:#E8EAE8'>
								<td align='right'>".$vISOECODIGO."</td>
								<td align='right'>".$data_atual."</td>
								<td align='left'>".$usuarioDepartamento."</td>
								<td align='left'>".$nomeUsuario."</td>
							</tr>
							<tr style='background-color:#E8EAE8'>							
								<th colspan='2'>Produto Solicitado </th>
								<th>Quantidade</th>
								<th>Posição</th>
							</tr>
							<tr style='background-color:#E8EAE8'>
								<td align='left' colspan='2'>".$nomeProduto."</td>
								<td align='right'>".$quantidade."</td>
								<td align='left'>".$vSPosicao."</td>
							</tr>";
							if ($nomeProduto2 != ''){
								$Mensagem  .= 	"<tr style='background-color:#E8EAE8'>
								<td align='left' colspan='2'>".$nomeProduto2."</td>
								<td align='right'>".$quantidade2."</td>
								<td align='left'>".$vSPosicao."</td>
							</tr>";								
							}	
							if ($nomeProduto3 != ''){
								$Mensagem  .= 	"<tr style='background-color:#E8EAE8'>
								<td align='left' colspan='2'>".$nomeProduto3."</td>
								<td align='right'>".$quantidade3."</td>
								<td align='left'>".$vSPosicao."</td>
							</tr>";								
							}
							if ($nomeProduto4 != ''){
								$Mensagem  .= 	"<tr style='background-color:#E8EAE8'>
								<td align='left' colspan='2'>".$nomeProduto4."</td>
								<td align='right'>".$quantidade4."</td>
								<td align='left'>".$vSPosicao."</td>
							</tr>";								
							}
							if ($nomeProduto5 != ''){
								$Mensagem  .= 	"<tr style='background-color:#E8EAE8'>
								<td align='left' colspan='2'>".$nomeProduto5."</td>
								<td align='right'>".$quantidade5."</td>
								<td align='left'>".$vSPosicao."</td>
							</tr>";								
							}
	$Mensagem  .= 	"	</tbody>
					</table><br /><br />
					";				
	
	enviarEmail($recipients, $Assunto, $Mensagem, 'N');	
	return;
}		