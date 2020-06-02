<?php
	include_once __DIR__.'/../twcore/teraware/php/constantes.php';
    $vConexao = sql_conectar_banco_logini(vGBancoSite);


    // login usuario
    // pagina destino

    $sql1 = "Select
                    u.USUSENHA,
                    u.USUNOME,
                    u.USUEMAIL,
                    u.EMPCODIGO,
                    u.USUCODIGO,
                    u.USULOGIN,
					t.TABDESCRICAO AS DEPARTAMENTO
                From
                    USUARIOS u
				LEFT JOIN TABELAS t ON t.TABCODIGO = u.TABDEPARTAMENTO	
                Where
                    u.USUSTATUS = 'S' and                
                    u.USUDATADEMISSAO is null and
                    u.USULOGIN ='".addslashes($_GET["input_login"])."' ";

    $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $sql1);

    while ($reg_RS = sql_retorno_lista($RS_MAIN)) {       
		if ($reg_RS['EMPLOGOMARCA'] != '')
			$vSLogo                     = '../imagens/empresas/'.$reg_RS['EMPLOGOMARCA'];
		else
				$vSLogo                     = '../imagens/empresas/2.png';	       

        $_SESSION['DIR_ROOT']                           = DIR_ROOT;
        $_SESSION['SI_ID_USUARIO']                      = $reg_RS['USUCODIGO'];
        $_SESSION['SS_USUNOME']                    		= $reg_RS['USUNOME'];
        $_SESSION['SS_LOGIN_USUARIO']                   = $reg_RS['USULOGIN']; 
        $_SESSION['SS_EMAIL_USUARIO']                   = $reg_RS['USUEMAIL'];
        $_SESSION['SI_USU_EMPRESA']                     = 2;
        $_SESSION['SS_USU_EMPRESA']                     = 'Marpa Marcas e Patentes  - Matriz';        
        $_SESSION['SI_USUCODIGO']                       = $reg_RS['USUCODIGO'];
        $_SESSION['SS_USUIP']                           = $_SERVER["REMOTE_ADDR"];
        $_SESSION['SS_LOGO']                            = $vSLogo;
        $_SESSION['SS_AMBIENTE']                        = 'DESENV';
        $_SESSION['SS_SECURITY']                        = '1ODLkhuDE2OE';   		
		$_SESSION['SS_USUSETOR']                 		= $reg_RS['DEPARTAMENTO'];
		$_SESSION['SS_USUMASTER']                 		= 'N';

		
		/*
        $sql1 = "Select
                        ACETABELA,
                        ACECONSULTA,
                        ACEINCLUSAO,
                        ACEALTERACAO,
                        ACEEXCLUSAO,
                        ACEEXPORTAR
                    From
                        ACESSOS
                    Where
                        USUCODIGO = ".$reg_RS['USUCODIGO'];

            $RS_MAIN = sql_executa(vGBancoSite, $vConexao, $sql1);

            while($reg_RS2 = sql_retorno_lista($RS_MAIN)) {
                $_SESSION['SA_ACESSOS']['TABELA'][$reg_RS2['ACETABELA']]['CONSULTA'] = $reg_RS2['ACECONSULTA'];
                $_SESSION['SA_ACESSOS']['TABELA'][$reg_RS2['ACETABELA']]['INCLUSAO'] = $reg_RS2['ACEINCLUSAO'];
                $_SESSION['SA_ACESSOS']['TABELA'][$reg_RS2['ACETABELA']]['ALTERACAO'] = $reg_RS2['ACEALTERACAO'];
                $_SESSION['SA_ACESSOS']['TABELA'][$reg_RS2['ACETABELA']]['EXCLUSAO'] = $reg_RS2['ACEEXCLUSAO'];
                $_SESSION['SA_ACESSOS']['TABELA'][$reg_RS2['ACETABELA']]['EXPORTAR'] = $reg_RS2['ACEEXPORTAR'];
            }
                       
            $_SESSION['SA_EMPRESAS'] = 2;
            $_SESSION['SI_QTDEEMPRESAS'] = 1;     */ 
    }
    
    echo "<script>document.location.href='".addslashes($_GET["input_destino"])."';</script>";
	return;
?>