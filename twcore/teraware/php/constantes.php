<?php
 	/*************************************************
 		Arquivo de constantes usadas pelo sistema
 	***************************************************/
 	session_start();
 	header('Content-Type: text/html; charset=utf-8');

 	//Diretório raiz do sistema
  	define("DIR_ROOT", $_SERVER['DOCUMENT_ROOT'].'/app/');

    //URL do login
 	define("URL_LOGIN", 'https://gestao-srv.twflex.com.br/autenticacao/login.php');

	//URL base
	define("URL_BASE", 'https://gestao-srv.twflex.com.br/');

  	//Constantes Diversas
  	define("vSTituloSite", ".: Gestão SRV - Sistema de Gestão Empresarial :.");
 	define("vSEmpresa", "Gestão SRV");
 	define("vSEmpresaSite", "https://gestao.srv.br/");
 	define("vSEmailSite", "contato@gestao.srv.br");
 	define("vSFoneEmpresa", "(51) 3541 3355");
    define("cSChaveCEP", "ecb023b62d76c34e879b022accc6b06b");

	define("cSPalavraChave", "TWFlex");

	define('cSUrlSiteEmpresa', '');
	define('cSSegmentoAtendimento', 'Gestão SRV');
	define('cSTelefone1', '(51) 3541-3355');
	define('cSTelefone2', ' (51) 9 8443-2097');
	define('cSTelefone3', '');
	define('cSEncriptacao', '');
	define('cSSiteEmpresa', 'https://gestao-srv.twflex.com.br/');
	define('cSNomeEmpresa', 'Gestão SRV');
	define('cSEmailRecebimento', 'gestao@gestao.srv.br');

	define('cSLogoMarca', 'logo_menu.png');

  	/*
  		DADOS DE ACESSO AO BANCO ERP
  	*/
	define("vGUsername", "twflex14");
	define("vGPassword", "7g7LWEnB");
	define("vGBancoSite", "twflex14");
	define("vGHostSite", "mysql08-farm2.uni5.net");

    //Essa variável é utilizada em todos os arquivos de cadastro.
    //Deve ser adicionado em cada arquivo .cad o valor do status para ela.
    $vSDefaultStatusCad = "";

  	include('funcoes.php');
  	include('funcoesBanco.php');
    //include('componentes/listaComponentes.php');
 ?>