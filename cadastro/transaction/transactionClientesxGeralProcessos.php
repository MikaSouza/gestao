<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'ClientesxProcessos')
	listClientesxProcessos($_POST['pIOID'], 'ClientesxProcessos');

function listClientesxProcessos($vIOIDPAI, $tituloModal){
	
	// direito autoral
	$sql = "SELECT COUNT(d.DIAPASTA) AS TOTAL
		FROM DIREITOAUTORAL d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.DIASTATUS = 'S' 
		AND c.CLICODIGO = ?  
		GROUP BY d.DIAPASTA ";
	
	/* software	
	$sql .= "SELECT COUNT(d.PGCPASTA) AS TOTAL
		FROM PROGRAMACOMPUTADOR d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PGCSTATUS = 'S' 
		AND c.CLICODIGO = ?	
		GROUP BY d.PGCPASTA ";	
	
	// Código barras	
	$sql .= "SELECT COUNT(d.CODPASTA) AS TOTAL
		FROM CODIGOBARRAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.CODSTATUS = 'S'
		AND c.CLICODIGO = ?  
		GROUP BY d.CODPASTA ";
		
	// Marcas no Brasil
	$sql .= "SELECT COUNT(d.MARPASTA) AS TOTAL
		FROM MARCAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.MARSTATUS = 'S'
		AND d.MARPAIS = 'BR' 
		AND c.CLICODIGO = ? 
		GROUP BY d.MARPASTA ";

	// Marcas no Exterior
	$sql .= "SELECT COUNT(d.MARPASTA) AS TOTAL
		FROM MARCAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.MARSTATUS = 'S'
		AND d.MARPAIS <> 'BR' 
		AND c.CLICODIGO = ? 
		GROUP BY d.MARPASTA ";
	
	// Patentes no Brasil
	$sql .= "SELECT COUNT(d.PATPASTA) AS TOTAL
		FROM PATENTES d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PATSTATUS = 'S'
		AND d.PATPAIS = 'BR' 
		AND c.CLICODIGO = ?
		GROUP BY d.PATPASTA ";

	// Patentes no Exterior
	$sql .= "SELECT COUNT(d.PATPASTA) AS TOTAL
		FROM PATENTES d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PATSTATUS = 'S'
		AND d.PATPAIS <> 'BR' 
		AND c.CLICODIGO = ?
		GROUP BY d.PATPASTA ";
	
	// Registro Produto
	$sql .= "SELECT COUNT(d.RGPPASTA) AS TOTAL
		FROM REGISTROPRODUTO d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.RGPSTATUS = 'S'
		AND c.CLICODIGO = ?
		GROUP BY d.RGPPASTA "; */
		
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(
						array($vIOIDPAI, PDO::PARAM_INT)
					)
				);
	$result = consultaComposta($arrayQuery);
	$vAConfig['TRANSACTION'] = "transactionClientesxProcessos.php";
	$vAConfig['DIV_RETORNO'] = "div_processos";
	$vAConfig['FUNCAO_RETORNO'] = "ClientesxProcessos";
	$vAConfig['ID_PAI'] = $vIOIDPAI;
	$vAConfig['BTN_EDITAR'] = 'N';
	$vAConfig['vATitulos'] 	= array('', 'Segmentos');
	$vAConfig['vACampos'] 	= array('CXRCODIGO', 'SEGNOME');
	$vAConfig['vATipos'] 	= array('chave', 'varchar');
	include_once '../../twcore/teraware/componentes/gridPadraoFilha.php';

	return ;

}

function search_ClientesxGeralProcessos($_POSTDADOS){
	include_once('../grids/gridPadrao.php');

	// direito autoral
	$Sql = "SELECT COUNT(d.DIAPASTA) AS TOTAL
		FROM DIREITOAUTORAL d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.DIASTATUS = 'S'";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.DIAPASTA";

	// software
	$Sql = "SELECT COUNT(d.PGCPASTA) AS TOTAL
		FROM PROGRAMACOMPUTADOR d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PGCSTATUS = 'S'";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.PGCPASTA";


	// Código barras
	$Sql = "SELECT COUNT(d.CODPASTA) AS TOTAL
		FROM CODIGOBARRAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.CODSTATUS = 'S'";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.CODPASTA";


	// Marcas no Brasil
	$Sql = "SELECT COUNT(d.MARPASTA) AS TOTAL
		FROM MARCAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.MARSTATUS = 'S'
		AND d.MARPAIS = 'BR' ";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.MARPASTA";


	// Marcas no Exterior
	$Sql = "SELECT COUNT(d.MARPASTA) AS TOTAL
		FROM MARCAS d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.MARSTATUS = 'S'
		AND d.MARPAIS <> 'BR' ";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.MARPASTA";

	// Patentes no Brasil
	$Sql = "SELECT COUNT(d.PATPASTA) AS TOTAL
		FROM PATENTES d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PATSTATUS = 'S'
		AND d.PATPAIS = 'BR' ";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.PATPASTA";

	// Patentes no Exterior
	$Sql = "SELECT COUNT(d.PATPASTA) AS TOTAL
		FROM PATENTES d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.PATSTATUS = 'S'
		AND d.PATPAIS <> 'BR' ";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.PATPASTA";

	// Registro Produto
	$Sql = "SELECT COUNT(d.RGPPASTA) AS TOTAL
		FROM REGISTROPRODUTO d
		LEFT JOIN CLIENTES c ON c.CLICODIGO = d.CLICODIGO 
		WHERE d.RGPSTATUS = 'S' ";
	if(verificarVazio($_POSTDADOS['vICLICODIGO']))
		$Sql .=" and c.CLICODIGO = ".$_POSTDADOS['vICLICODIGO'];	
	$Sql .=" GROUP BY d.RGPPASTA";


	echo $Sql;
	$Sql = stripcslashes($Sql);
	$vConexao = sql_conectar_banco();
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);

	$vAConfig['vIIDACESSO']    = 1856;
	$vAConfig['bDesabilitar']  = true;

	$vAConfig['vSTitulo'] = 'ClientesxGeralProcessos';
	$vAConfig['vSPrefixo'] = 'CHG';
	$vAConfig['vATitulos'] = array('Direito Autoral', 'Descrição', 'Data Inclusão', 'Data Alteração', 'Ativo');
	$vAConfig['vACampos'] = array('TOTAL', 'CHGHISTORICO', 'CHGDATA_INC', 'CHGDATA_ALT', 'CHGSTATUS');
	$vAConfig['vATipos'] = array('inteiro', 'varchar', 'datetime', 'datetime', 'simNao');
	if ($_POSTDADOS['hdn_metodo_search'] == 'searchCGP')
		montarGridPadrao($RS_POST, $vAConfig);
	elseif ($_POSTDADOS['hdn_metodo_search'] == 'exportarGridCHG')
		exportarTXTGridPadrao($RS_POST, $vAConfig);
	sql_fechar_conexao_banco($vConexao);
}

?>