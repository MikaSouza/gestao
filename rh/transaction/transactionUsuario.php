<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../../twcore/vendors/phpmailer/email.php';


if( $_GET['hdn_metodo_fill'] == 'fill_Usuario' )
	fill_Usuario($_GET['vIUSUCODIGO'], $_GET['formatoRetorno']);

if (($_POST["methodPOST"] == "insert")||($_POST["methodPOST"] == "update")) {

    $vIOid = insertUpdateUsuario($_POST, 'N');

	//incluir endereços
	include_once 'transactionUsuariosxEnderecos.php';
	$_POST['vIUSUCODIGO'] = $vIOid;
	insertUpdateUsuariosxEnderecos($_POST, 'N');

	//incluir acessos
	include_once 'transactionUsuariosxAcessos.php';
	$_POST['vIUSUCODIGO'] = $vIOid;
	insertUpdateLoteUsuariosxAcessos($_POST, 'N');
	sweetAlert('', '', 'S', 'cadUsuario.php?method=update&oid='.$vIOid, 'S');
    return;
} else if (($_GET["method"] == "consultar")||($_GET["method"] == "update")) {
    $vROBJETO = fill_Usuario($_GET['oid'], 'array');
    $vIOid = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'];
    $vSDefaultStatusCad = $vROBJETO[$vAConfiguracaoTela['MENPREFIXO'].'STATUS'];
	//incluir endereços
	include_once 'transactionUsuariosxEnderecos.php';
	$vRENDERECO = fill_UsuariosxEnderecos($vIOid);
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

if($_GET['metodo'] == 'enviarAcesso'){

	$arrCodigos = filter_var_array($_POST['vAUSUCODIGO'], FILTER_SANITIZE_NUMBER_INT);
	$enviados = [];

	foreach ($arrCodigos as $usucodigo) {
		$usuario = fill_Usuario($usucodigo);
		$dadosEmail = array(
			'titulo'        => 'Acesso Sistema Gestão',
			'descricao'     => 'Informamos que você foi cadastrado no Sistema da Gestão SRV. <br/>
								Para acessar o sistema siga os seguintes passos:',
			'destinatarios' => array(
				$usuario['USUEMAIL']
			),
			'fields' => array(
				'link'    => 'https://gestao-srv.twflex.com.br/autenticacao/login.php',
				'Email/Login'    => $usuario['USUEMAIL'],
				'Senha'          => Desencriptar($usuario['USUSENHA'],cSPalavraChave)
			)
		);
		$enviados[$usuario['USUCODIGO']] = emailField($dadosEmail);
	}
	$fails = array_filter($enviados, function($enviado) {
        return $enviado != 1;
	});

    if (count($fails) > 0) {
        $response = [
            'success' => false,
            'msg'     => 'Não foi possível enviar E-mail.'
        ];

        if (count($fails) != count($enviados)) {
            $response['msg'] .= '. Os demais foram enviados com sucesso!';
		}

		echo json_encode($response);
		die();
    } else {
		echo json_encode([
			'success' => true,
			'msg' => 'Todos os E-mails foram enviados com sucesso!',
		]);
	}
}

function listUsuario($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND u.USUSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND u.USUSTATUS = 'N' ";
	}else
		$where .= "AND u.USUSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND u.USUDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND u.USUDATA_INC <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoInicio']))
		$where .= 'AND u.USUDATAADMISSAO >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoFim']))
		$where .= 'AND u.USUDATAADMISSAO <= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoInicio']))
		$where .= 'AND u.USUDATADEMISSAO >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoFim']))
		$where .= 'AND u.USUDATADEMISSAO <= ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND u.USUSEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME']))
		$where .= 'AND u.USUNOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ']))
		$where .= 'AND u.USUCNPJ = ? ';

	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUSITUACAO']) && ($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] != "T")) {
		if($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] == "A")
			$where .=" and u.USUDATAADMISSAO is not null and USUDATADEMISSAO is null ";
		else if($_POSTDADOS['FILTROS']['vSUSUSITUACAO'] == "D")
			$where .=" and u.USUDATADEMISSAO is not null ";
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$where .=" and u.TABDEPARTAMENTO = ".$_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'];
	if(verificarVazio($_POSTDADOS['FILTROS']['vIEMPCODIGO']))
		$where .=" and u.EMPCODIGO = ".$_POSTDADOS['FILTROS']['vIEMPCODIGO'];

	$sql = "SELECT
				u.USUCODIGO,
				u.USUNOME,
				u.USUEMAIL,
				DATE_FORMAT(u.USUDATA_NASCIMENTO,'%d/%m/%Y') AS DATA_NASCIMENTO,
				DATE_FORMAT(u.USUDATAADMISSAO,'%d/%m/%Y') AS DATA_ADMISSAO,
				CASE
					WHEN u.USUSTATUS = 'S' THEN 'Sim'
					ELSE 'Não'
				END AS STATUS,
				t.TABDESCRICAO AS DEPARTAMENTO,
				t1.TABDESCRICAO AS TIPOVINCULO,
				t2.TABDESCRICAO AS CARGO,
				e.EMPNOMEFANTASIA AS EMPRESA
			FROM USUARIOS u
			LEFT JOIN TABELAS t1 ON t1.TABCODIGO = u.USUVINCULO
			LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO
			LEFT JOIN TABELAS t2 ON t2.TABCODIGO = u.TABCARGO
			LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = u.EMPCODIGO
				WHERE 1 = 1
				".	$where	."
				ORDER BY 1 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataAdmissaoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataAdmissaoFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataAdmissaoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataDemissaoInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataDemissaoFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataDemissaoFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSUSUNOME'])){
		$pesquisa = $_POSTDADOS['FILTROS']['vSUSUNOME'];
		$arrayQuery['parametros'][] = array("%$pesquisa%", PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLICNPJ'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vSCLICNPJ'], PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vITABDEPARTAMENTO'], PDO::PARAM_INT);
	if(verificarVazio($_POSTDADOS['FILTROS']['vIEMPCODIGO']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vIEMPCODIGO'], PDO::PARAM_INT);
	$result = consultaComposta($arrayQuery);

	return $result;

}

function insertUpdateUsuario($_POSTDADOS, $pSMsg = 'N'){

	$cod_usuario = filter_var($_POSTDADOS['vIUSUCODIGO'], FILTER_SANITIZE_NUMBER_INT);

	if($_FILES['vHUSUFOTO']['error'] == 0){
		$nomeArquivo = removerAcentoEspacoCaracter($_FILES['vHUSUFOTO']['name']);
		$nomeArquivo = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,10).'_'.$nomeArquivo;
		uploadArquivo($_FILES['vHUSUFOTO'], '../ged/usuarios_fotos', $nomeArquivo);
		$_POSTDADOS['vSUSUFOTO'] = $nomeArquivo;
	}
	if(verificarVazio($_POSTDADOS['vSUSUSENHA']))
		$_POSTDADOS['vSUSUSENHA']  = Encriptar($_POSTDADOS['vSUSUSENHA'], cSPalavraChave);
	$dadosBanco = array(
		'tabela'  => 'USUARIOS',
		'prefixo' => 'USU',
		'fields'  => $_POSTDADOS,
		'msg'     => $pSMsg,
		'url'     => '',
		'debug'   => 'N'
	);
	$id = insertUpdate($dadosBanco);
	if (!verificarVazio($cod_usuario)) {

		$aferias = array();
		$vDUSUDATAADMISSAO               = formatar_data_banco($_POSTDADOS['vDUSUDATAADMISSAO']);
		$aferias['vIUSUCODIGO']          = $id;
		$aferias['vIEMPCODIGO']          = $_POSTDADOS['vIEMPCODIGO'];
		$aferias['vDUXFDATAAQUISITIVO1'] = date('d/m/Y', strtotime($vDUSUDATAADMISSAO . " +1 year"));
		$aferias['vDUXFDATAAQUISITIVO2'] = date('d/m/Y', strtotime($vDUSUDATAADMISSAO . " +1 year 1 month"));
		$aferias['vDUXFDATALIMITEGOZO']  = date('d/m/Y', strtotime($vDUSUDATAADMISSAO . " +2 years"));
		include_once('transactionUsuariosxFerias.php');

		insertUpdateUsuariosxFerias($aferias, 'N');
		if (verificarVazio($_POSTDADOS["vMUSUSALARIOINICIAL"])) {
			$aremuneracao = array();
			$aremuneracao['vIUSUCODIGO']                = $id;
			$aremuneracao['vIEMPCODIGO']                = $_POSTDADOS['vIEMPCODIGO'];
			$aremuneracao['vMUXRSALARIOATUAL']          = $_POSTDADOS["vMUSUSALARIOINICIAL"];
			$aremuneracao['vDUXRDATAALTERACAOSALARIAL'] = $_POSTDADOS['vDUSUDATAADMISSAO'];
			$aremuneracao['vSUXRMOTIVOALTERACAOSALARIAL'] = 'Salário Inicial';
			include_once('transactionUsuariosxRemuneracao.php');
			insertUpdateUsuariosxRemuneracao($aremuneracao, 'N');
		}
	}
	return $id;
}

function fill_Usuario($pOid, $formatoRetorno = 'array'){
	$SqlMain = "SELECT
                    *
                FROM USUARIOS
                    WHERE USUCODIGO  =".$pOid;
	$vConexao = sql_conectar_banco(vGBancoSite);
	$resultSet = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	$registro = sql_retorno_lista($resultSet);
	if( $formatoRetorno == 'array')
		return $registro !== null ? $registro : "N";
	else if( $formatoRetorno == 'json' )
		echo json_encode($registro);
	return $registro !== null ? $registro : "N";
}