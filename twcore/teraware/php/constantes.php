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
  	define("vSTituloSite", ".: Marpa - Marcas e Patentes - Sistema de Gestão Empresarial :.");
 	define("vSEmpresa", "Marpa - Marcas e Patentes");
 	define("vSEmpresaSite", "https://marpa.com.br");
 	define("vSEmailSite", "contato@marpa.com.br");
 	define("vSFoneEmpresa", "(51) 3061-2550");
    define("cSChaveCEP", "ecb023b62d76c34e879b022accc6b06b");
	
	define("cSPalavraChave", "TWFlex");
 
  	/*
  		DADOS DE ACESSO AO BANCO ERP
  	*/
	define("vGUsername", "marpa2");
	define("vGPassword", "mF01lkwvXbpd7w88");
	define("vGBancoSite", "marpaconsultoria");
	define("vGHostSite", "192.168.1.252");
  	
    //Essa variável é utilizada em todos os arquivos de cadastro.
    //Deve ser adicionado em cada arquivo .cad o valor do status para ela.
    $vSDefaultStatusCad = "";
   
  	include('funcoes.php');
  	include('funcoesBanco.php');
    //include('componentes/listaComponentes.php');
 ?>