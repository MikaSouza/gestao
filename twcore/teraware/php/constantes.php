<?php
 	/*************************************************
 		Arquivo de constantes usadas pelo sistema
 	***************************************************/
 	session_start();
 	header('Content-Type: text/html; charset=utf-8');
 
 	//Diretório raiz do sistema
  	define("DIR_ROOT", $_SERVER['DOCUMENT_ROOT'].'/app/');
      
    //URL do login 
 	define("URL_LOGIN", 'https://jefersonbraga-atendimento-presencial.teraware.net.br/autenticacao/login.php');
	
	//URL base
	define("URL_BASE", 'https://jefersonbraga-atendimento-presencial.teraware.net.br/');
  
  	//Constantes Diversas
  	define("vSTituloSite", ".: Jeferson Braga Advogados - Plataforma de Atendimento :.");
 	define("vSEmpresa", "Jeferson Braga Advogados");
 	define("vSEmpresaSite", "http://www.jefersonbraga.com.br/");
 	define("vSEmailSite", "contato@jefersonbraga.com.br");
 	define("vSFoneEmpresa", "(51) 3075.0303"); 
    define("cSChaveCEP", "ecb023b62d76c34e879b022accc6b06b");
	
	define("cSPalavraChave", "TWJBA");
 
  	/*
  		DADOS DE ACESSO AO BANCO ERP
  	*/
	define("vGUsername", "teraware06");
	define("vGPassword", "265APS6R");
	define("vGBancoSite", "teraware06");
	define("vGHostSite", "mysql.teraware.net.br");
  	
    //Essa variável é utilizada em todos os arquivos de cadastro.
    //Deve ser adicionado em cada arquivo .cad o valor do status para ela.
    $vSDefaultStatusCad = "";
   
  	include('funcoes.php');
  	include('funcoesBanco.php');
    //include('componentes/listaComponentes.php');
 ?>