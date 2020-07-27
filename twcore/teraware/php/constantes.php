<?php
 	/*************************************************
 		Arquivo de constantes usadas pelo sistema
 	***************************************************/
 	session_start();
 	header('Content-Type: text/html; charset=utf-8');
 
 	//Diretório raiz do sistema
  	define("DIR_ROOT", $_SERVER['DOCUMENT_ROOT'].'/app/');
      
      //URL do login
 	define("URL_LOGIN", 'http://app.tweduc.com.br/login.php');
  
  	//Constantes Diversas
  	define("vSTituloSite", ".: Gestão SRV - Sistema de Gestão Empresarial :.");
 	define("vSEmpresa", "Gestão SRV");
 	define("vSEmpresaSite", "https://gestao.srv.br/");
 	define("vSEmailSite", "contato@gestao.srv.br");
 	define("vSFoneEmpresa", "(51) 3541 3355"); 
    define("cSChaveCEP", "ecb023b62d76c34e879b022accc6b06b");
	
	define("cSPalavraChave", "TWFlex");
 
  	/*
  		DADOS DE ACESSO AO BANCO ERP
  	*/
	define("vGUsername", "teraware");
	define("vGPassword", "7g7LWEnB");
	define("vGBancoSite", "teraware");
	define("vGHostSite", "mysql.teraware.net.br");
  	
    //Essa variável é utilizada em todos os arquivos de cadastro.
    //Deve ser adicionado em cada arquivo .cad o valor do status para ela.
    $vSDefaultStatusCad = "";
   
  	include('funcoes.php');
  	include('funcoesBanco.php');
    //include('componentes/listaComponentes.php');
 ?>