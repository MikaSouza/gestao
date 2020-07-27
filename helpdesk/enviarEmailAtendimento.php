<?php
function enviarEmailAtendimento($pIOid, $pSCCCliente, $pSCCAtendente, $pSCCHelpdesk) {
	if ($pIOid > 0) {
		$vConexao = sql_conectar_banco(vGBancoSite);

		///////////////////////////
		// DADOS GERAIS DO EMAIL //
		///////////////////////////
		$SqlEmail = 'SELECT
						a.ATEASSUNTO,
						a.ATEMENSAGEM,
						a.ATESEQUENCIAL,
						(SELECT u1.USUNOME FROM USUARIOS u1 WHERE u1.USUCODIGO = a.ATEATENDENTE) as ATENDENTE,
						(SELECT u2.USUEMAIL FROM USUARIOS u2 WHERE u2.USUCODIGO = a.ATEATENDENTE) as ATENDENTE_EMAIL,
						(SELECT c.CLINOMEFANTASIA FROM CLIENTES c WHERE c.CLICODIGO = a.CLICODIGO) as CLIENTE,
						(SELECT p.PXSNOME FROM PRODUTOSXSERVICOS p WHERE p.PXSCODIGO = a.PROCODIGO) as PRODUTO,
						(SELECT t1.TABDESCRICAO FROM TABELAS t1 WHERE t1.TABCODIGO = a.ATETIPOATENDIMENTO) as TIPO_ATENDIMENTO,
						(SELECT t2.TABDESCRICAO FROM TABELAS t2 WHERE t2.TABCODIGO = a.ATEORIGEM) as ORIGEM,
						(SELECT t3.TABDESCRICAO FROM TABELAS t3 WHERE t3.TABCODIGO = a.TABCODIGO) as CATEGORIA,
						e.EMPNOMEFANTASIA as EMPRESA_ATENDENTE,
						( SELECT pp.POPNOME FROM POSICOESPADROES pp WHERE a.ATEPOSICAOATUAL = pp.POPCODIGO ) AS POSICAOATUAL_NOME
					FROM
						ATENDIMENTO a
					LEFT JOIN
						EMPRESAUSUARIA e
					ON
						e.EMPCODIGO = a.EMPCODIGO
					WHERE
						a.ATECODIGO = '.$pIOid;

		$RS_Email = sql_executa(vGBancoSite,$vConexao,$SqlEmail);
		while($reg_Email = sql_retorno_lista($RS_Email)) {
			$vSAssunto = $reg_Email['ATEASSUNTO'];
			$vSMensagem = nl2br($reg_Email['ATEMENSAGEM']);
			$vSAtendente = $reg_Email['ATENDENTE'];
			$vSAtendenteEmail = $reg_Email['ATENDENTE_EMAIL'];
			$vSCliente = $reg_Email['CLIENTE'];
			$vSProduto = $reg_Email['PRODUTO'];
			$vSTipoAtendimento = $reg_Email['TIPO_ATENDIMENTO'];
			$vSOrigem = $reg_Email['ORIGEM'];
			$vSCategoria = $reg_Email['CATEGORIA'];
			$vSPosicao = $reg_Email['POSICAOATUAL_NOME'];
			$vSNumero = adicionarCaracterLeft($reg_Email['ATESEQUENCIAL'], 5);
			$vSEmpresaAtendente = $reg_Email['EMPRESA_ATENDENTE'];
		}

		/////////////////////////////
		// CONTATOS DO ATENDIMENTO //
		/////////////////////////////
		if ($pIOid > 0) {
			$sqlAtendimentoXContatos = "SELECT
											c.CONNOME,
											c.CONFONE,
											c.CONCELULAR,
											c.CONEMAIL
										FROM
											ATENDIMENTOSXCONTATOS a
										LEFT JOIN
											CONTATOS c
										ON
											c.CONCODIGO = a.CONCODIGO
										WHERE
											a.AXCSTATUS = 'S' AND
											a.ATECODIGO = " . $pIOid;

			$resContatos = sql_executa(vGBancoSite, $vConexao, $sqlAtendimentoXContatos);
			$array_contatos = array();
			$html_contatos = "<strong>Contato(s):</strong> <br />";
			
			while($retContatos = sql_retorno_lista($resContatos)) {
				array_push($array_contatos, $retContatos);

				$html_contatos .= $retContatos['CONNOME'];
				
				if($retContatos['CONFONE'] != "")
					$html_contatos .= ", ".$retContatos['CONFONE'];
				if($retContatos['CONCELULAR'] != "")
					$html_contatos .= ", ".$retContatos['CONCELULAR'];
				if($retContatos['CONEMAIL'] != "")
					$html_contatos .= ", ".$retContatos['CONEMAIL'];

				$html_contatos .= ".<br />";
			}
		}
		///////////////////////////
		// ANEXOS DO ATENDIMENTO //
		///////////////////////////
		$SqlA = "SELECT
					*
				FROM
					ANEXOSXATENDIMENTO
				WHERE
					AXASTATUS = 'S' AND
					ATECODIGO = ".$pIOid;

		$resultadoA = sql_executa(vGBancoSite, $vConexao, $SqlA, false);
		$cont = 0;

		$anexos = array();

		while ( $resA = mysql_fetch_array($resultadoA) ) {
			array_push ( $anexos, array(
				'nome' => $resA["AXANOME"],
				'link' => "../anexosAtendimento/". $resA["AXACAMINHO"]
			) ) ;
		}

		/////////////////////////////
		// POSIÇÕES DO ATENDIMENTO //
		/////////////////////////////
		$SqlAxH = "SELECT
						a.AXHDATA_INC,
						(SELECT u1.USUNOME FROM USUARIOS u1 WHERE u1.USUCODIGO = a.AXHUSU_INC) as USUINC,
						(SELECT p.POPNOME FROM POSICOESPADROES p WHERE p.POPCODIGO = a.POPCODIGO) as POSICAO,
						a.AXHDESCRICAO,
						(SELECT u2.USUNOME FROM USUARIOS u2 WHERE u2.USUCODIGO = a.AXHATENDENTENOVO) as ATENDENTE
					FROM
						ATENDIMENTOXHISTORICOS a
					WHERE
						a.AXHSTATUS = 'S' AND
						a.ATECODIGO = ".$pIOid."
					ORDER BY
						a.AXHDATA_INC";

		$resultadoAxH = sql_executa(vGBancoSite, $vConexao, $SqlAxH, FALSE);
		$res_AxH = sql_record_count($resultadoAxH);

		if($res_AxH != 0) {
			$html_posicoes = '<table border="1" style="width:1200px; border-collapse:collapse;">
									<thead>
										<tr>
											<th align="center" style="width:150px"><b>Inclusão</b></th>
											<th align="center" style="width:150px"><b>Atendente</b></th>
											<th align="center" style="width:250px"><b>Posição</b></th>
											<th align="center" style="width:650px"><b>Descrição</b></th>
										</tr>
									</thead>
									<tbody>';

			$i = 0;
			while ($resAxH = mysql_fetch_array($resultadoAxH)) {
				if ($i%2 != 0) 
					$bgcolor = "#FFF";
				else
					$bgcolor = "#E8EAE8";
				$i++;

				$html_posicoes .= "<tr style='background-color:".$bgcolor.";'>
											<td align='left'><span>".otimizarNome($resAxH['USUINC'])."<br />".formatar_data_hora($resAxH['AXHDATA_INC'])."</span></td>
											<td align='left'><span>".otimizarNome($resAxH['ATENDENTE'])."</span></td>
											<td align='left'><span>".$resAxH['POSICAO']."</span></td>
											<td align='left'><span>".nl2br($resAxH['AXHDESCRICAO'])."</span></td>
										</tr>";
			}

			$html_posicoes .= "</tbody></table>";
		}

		////////////////////////////
		// CONFIGURAÇÕES DO EMAIL //
		////////////////////////////
		$Assunto = "Atendimento $vSNumero - $vSPosicao";

		$Mensagem = "<strong>Atendimento $vSNumero</strong><br /><br />";
		$Mensagem .= "<strong>Cliente:</strong> <br />$vSCliente<br />";
		$Mensagem .= $html_contatos;
		$Mensagem .= "<strong>Produto:</strong><br /> $vSProduto <br />";
		$Mensagem .= "<strong>Posição Atual:</strong><br /> $vSPosicao <br />";
		$Mensagem .= "<strong>Atendente:</strong><br /> $vSAtendente <br />";
		$Mensagem .= "<strong>Assunto:</strong><br /> $vSAssunto <br />";
		$Mensagem .= "<strong>Descrição:</strong><br /> $vSMensagem <br />";
		$Mensagem .= "<br />";

		$Mensagem .= $html_posicoes."<br />";
		
		$Mensagem .= "<strong>E-mail Automático!</strong><br />";
		$Mensagem .= '<i>"Por favor não envie solicitações e/ou dúvidas para este e-mail, pois as mesmas não serão respondidas.</i><br />';
		$Mensagem .= '<i>Para realizar a abertura de um chamado e/ou solicitar atendimento, acesse nosso sistema Helpdesk.</i><br />';
		$Mensagem .= '<i>Agradecemos a sua compreensão."</i><br />';
		
		$Mensagem .= "<table style='width:1200px;'>
							<thead>
								<tr><th>&nbsp;</th></tr>
								<tr>
									<th align='left'><strong>Atenciosamente</strong></th>
								</tr>
								<tr>
									<td align='left'>Equipe de Suporte Técnico</td>
								</tr>
								<tr>
									<td align='left'>".$vSEmpresaAtendente."</td>
								</tr>
							</thead>
						</table>";

		$phpmailer_diretorio_base = "../twcore/vendors/phpmailer/";
		include_once( $phpmailer_diretorio_base . "PHPMailerAutoload.php" );

		$mail = new PHPMailer;
		$mail->setLanguage('br', $phpmailer_diretorio_base . 'language/');
		$mail->isSMTP();
		$mail->SMTPDebug = false;
		if ($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPPORTA'] > 0)
			$mail->Port = $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPPORTA'];
		else
			$mail->Port = 587;
		$mail->Host = $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPHOST'];
		$mail->SMTPAuth = ( $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPAUTENTICADO'] == 'S' ) ? true : false;
		$mail->Username = $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPUSUARIO'];
		$mail->Password = Desencriptar($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPSENHA'], "Teraware");
		$mail->SMTPSecure = $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILSMTPCRIPTOGRAFIA'];
		$mail->From = $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILREMETENTEEMAIL'];
		$mail->FromName = utf8_decode($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILREMETENTENOME']);

		if ($pSCCCliente == "S") {
			if( count($array_contatos) > 0 ){
				foreach ($array_contatos as $contato) {
					$mail->addAddress( $contato['CONEMAIL'], utf8_decode($contato['CONNOME']) );
				}
			}
		}

		if (($vSAtendenteEmail != "") && ($pSCCAtendente == "S")){
			$mail->addCC( $vSAtendenteEmail, utf8_decode($vSAtendente) );
		}

		if ($pSCCHelpdesk == "S") {
			$mail->addCC( $_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILREMETENTEEMAIL'], utf8_decode($_SESSION['SA_ACESSOS']['HELPDESK'][$_SESSION["SI_USU_EMPRESA"]]['CHDEMAILREMETENTENOME']) );
		}

		foreach( $anexos as $anexo ){
			$mail->addAttachment( $anexo['link'], $anexo['nome']);
		}

		$mail->isHTML(true);

		$mail->Subject = utf8_decode($Assunto);
		$mail->Body    = utf8_decode($Mensagem);

		if(!$mail->send()) {
		   echo 'Erro no envio do e-mail. Entre em contato com o administrador do sistema.';
		   echo 'Erro: ' . $mail->ErrorInfo;
		   exit;
		} else {
			//echo 'E-mail enviado com sucesso.';
		}
	}
}
?>