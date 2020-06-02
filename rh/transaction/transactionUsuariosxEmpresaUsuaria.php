<?php
include_once __DIR__.'/../include/constantes.php';
include_once __DIR__.'/../twcore/teraware/php/crud.php';


if(isset($_GET["method"]) && $_GET["method"] == 'searchAcesso'){
	list($pIOid, $pSOrdem) = explode (",", $_GET["parametros"]);
	search_UsuariosxEmpresaUsuaria($pIOid, $pSOrdem);
}

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchUXE')
	search_UsuariosxEmpresaUsuaria($_POST);

if(isset($_POST["method"]) && $_POST["method"] == 'insert_update_lote_UsuariosxEmpresaUsuaria')
{
	$_POST['vIOidListaEmpresa'] = explode(',',$_POST['vIOidListaEmpresa']);
	$_POST['vIOidListaAcesso'] 	= explode(',',$_POST['vIOidListaAcesso']);
	$_POST['vIListCheck'] 		= explode(',',$_POST['vIListCheck']);


	$total = count($_POST['vIOidListaEmpresa']);
	$i = 0;

	foreach($_POST['vIOidListaEmpresa'] as $key => $EMPCODIGO)
	{
			if($_POST['vIOidListaAcesso'][$key] != 'false')
				$ret .= insert_update_UsuariosxEmpresaUsuaria($_POST['vIOidListaAcesso'][$key], $EMPCODIGO, $_POST['vIListCheck'][$key], $_POST['vICLICODIGO'], 'N');

			if($_POST['vIOidListaAcesso'][$key] == 'false')
				insert_update_UsuariosxEmpresaUsuaria(false, $EMPCODIGO, $_POST['vIListCheck'][$key], $_POST['vICLICODIGO'], 'N');	
			$i++;
	}

	$retorno = array();

	if($total == $i)
	{
		$retorno['msg'] 	= "Acesso atualizado! ";
		$retorno['status'] 	= "success";
	}
	else
	{
		$retorno['msg']		= "Erro ao atualizar permissão";
		$retorno['status'] 	= "error";
	}

	echo json_encode($retorno);
	return;
}

function search_UsuariosxEmpresaUsuaria($_POSTDADOS){
	include('../grids/gridUsuariosxEmpresaUsuaria.php');
	$Sql = "Select
				EMPCODIGO,
				EMPNOMEFANTASIA
			From
				EMPRESAUSUARIA
			Where
				EMPSTATUS = 'S'";
	$Sql .= " order by EMPNOMEFANTASIA";
	$Sql = stripcslashes($Sql);
	$vConexao = sql_conectar_banco();
	$RS_POST = sql_executa(vGBancoSite, $vConexao,$Sql,FALSE);
	montarGridUsuariosxEmpresaUsuaria($RS_POST, $_POSTDADOS['vIUSUCODIGO']);
	sql_fechar_conexao_banco($vConexao);
}

function insert_update_UsuariosxEmpresaUsuaria($pOid, $pIEMPCODIGO, $pSUXEACESSO, $pIUSUCODIGO, $pSMsg){
	$aSCampos  = array();
	$aSValores = array();
	$vConexao = sql_conectar_banco(vGBancoSite);

	array_push($aSValores,"'".$_SESSION['SI_USUCODIGO']."'");
	array_push($aSValores," NOW() ");
	if(verificarVazio($pOid)){
		array_push($aSCampos,"UXEUSU_ALT");
		array_push($aSCampos,"UXEDATA_ALT");
	} else {
		array_push($aSCampos,"UXEUSU_INC");
		array_push($aSCampos,"UXEDATA_INC");
	}
	if(verificarVazio($pSUXEACESSO)){
		array_push($aSValores,"'".$pSUXEACESSO."'");
		array_push($aSCampos,"UXEACESSO");
	}
	if(verificarVazio($pIUSUCODIGO)){
		array_push($aSValores,"'".$pIUSUCODIGO."'");
		array_push($aSCampos,"USUCODIGO");
	}
	if(verificarVazio($pIEMPCODIGO)){
		array_push($aSValores,"'".$pIEMPCODIGO."'");
		array_push($aSCampos,"EMPCODIGO");
	}
	if(verificarVazio($pOid)){
		$vSClasula = " UXECODIGO = ". $pOid;
		$vStringRegistro = montaSqlCamposUpdate($aSCampos,$aSValores,"USUARIOSXEMPRESAUSUARIA",$vSClasula);
	} else
		$vStringRegistro = montaSqlCamposInsert($aSCampos,$aSValores,"USUARIOSXEMPRESAUSUARIA");

	if(!sql_executa(vGBancoSite, $vConexao,$vStringRegistro,FALSE))
		$vSMsg = 'Erro ao executar as atualizações!';
	else
		$vSMsg = 'Cadastro efetuado com sucesso!';

	if ($pSMsg == 'S')
		echo '<script>alert("'.$vSMsg.'");</script>';

	if(!verificarVazio($pOid)) {
		$pOid = mysqli_insert_id($vConexao);
	}
	//sql_fechar_conexao_banco($vConexao);

	return $pOid;
}
?>