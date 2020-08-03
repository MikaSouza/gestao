<?php
    include('../include/constantes.php');
    include("../mpdf/mpdf.php");

    if (($_SERVER["REQUEST_METHOD"] == "GET") && ($_GET["oid"] != "")) {
        $vIATECODIGO = $_GET["oid"];

        ////////////////////
        // CONFIGURA HTML //
        ////////////////////

        $vSHtmlDefault = array();

        $vSHtmlDefault["header"] = "<table width='100%'>
                                        <tr>
                                            <td align='left'>
                                                <img src='{_CABLOGOMARCA_}' {_CABDIMENSAO_} />
                                            </td>
                                            <td align='right' style='font-size:14px;'>
                                                {_CABLINHA1_}<br />
                                                {_CABLINHA2_}<br />
                                                {_CABLINHA3_}<br />
												{_SITE_}
                                            </td>
                                        </tr>
                                    </table>
									<br />
									<table width='100%'>
                                        <tr>
                                            <th align='center' style='font-size:18px;'>
                                                Atendimento {_SEQUENCIAL_}
                                            </th>
                                        </tr>
                                    </table>";

        $vSHtmlDefault["body"] = "<table border='1' style='border-collapse: collapse;' width='100%'>
										<tr style='background-color:#bbb;'><td colspan='4' align='left'><center><b>IDENTIFICAÇÃO</b></center></td></tr>
                                        <tr>
                                            <td colspan='1' width='15%'><b>Cliente: </b></td>
                                            <td colspan='1' width='35%'>{_CLIENTE_}</td>
                                            <td colspan='1' width='15%'><b>Contato: </b></td>
                                            <td colspan='1' width='35%'>{_CONTATO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' width='20%'><b>Endere&ccedil;o: </b></td>
                                            <td colspan='3' width='80%'>{_ENDERECO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' width='15%'><b>Produto: </b></td>
                                            <td colspan='1' width='35%'>{_PRODUTO_}</td>
                                            <td colspan='1' width='15%'><b>N&uacute;mero S&eacute;rie: </b></td>
                                            <td colspan='1' width='35%'>{_NUMEROSERIE_}</td>
                                        </tr>
                                    </table>
                                    <br />
                                    <table border='1' style='border-collapse: collapse;' width='100%'>
										<tr style='background-color:#bbb;'><td colspan='4' align='left'><center><b>DADOS GERAIS</b></center></td></tr>
                                        <tr>
                                            <td colspan='1' width='15%'><b>Tipo Atendimento: </b></td>
                                            <td colspan='1' width='35%'>{_TIPOATENDIMENTO_}</td>
                                            <td colspan='1' width='15%'><b>Origem: </b></td>
                                            <td colspan='1' width='35%'>{_ORIGEM_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' width='15%'><b>Categoria: </b></td>
                                            <td colspan='1' width='35%'>{_CATEGORIA_}</td>
                                            <td colspan='1' width='15%'><b>Integra&ccedil;&atilde;o: </b></td>
                                            <td colspan='1' width='35%'>{_INTEGRACAO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1'><b>Assunto: </b></td>
                                            <td colspan='3'>{_ASSUNTO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1'><b>Descri&ccedil;&atilde;o: </b></td>
                                            <td colspan='3'>{_DESCRICAO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1'><b>Posição Atual: </b></td>
                                            <td colspan='3'>{_ATENDENTE_}</td>
                                        </tr>
										<tr>
                                            <td colspan='1'><b>Atendente: </b></td>
                                            <td colspan='3'>{_ATENDENTE_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' width='20%'><b>In&iacute;cio Atendimento: </b></td>
                                            <td colspan='1' width='30%'>{_INICIOATENDIMENTO_}</td>
                                            <td colspan='1' width='20%'><b>Previs&atilde;o de Conclus&atilde;o: </b></td>
                                            <td colspan='1' width='30%'>{_PREVISAOCONCLUSAO_}</td>
                                        </tr>
                                    </table>
                                    {_DADOSADICIONAIS_}
                                    {_POSICOES_}";

        $vConexao = sql_conectar_banco();

        /////////////
        // EMPRESA //
        /////////////
		$vSSql = "SELECT
						CONCAT(
							e.EMPCODIGO,
							e.EMPLOGOMARCA
						) as LOGOMARCA,
						e.EMPNOMEFANTASIA as LINHA1,
						CONCAT(
							(CASE WHEN e.EMPTIPOEMPRESA = 'J' THEN
								e.EMPCNPJ
							ELSE
								e.EMPCPF
							END)
						) as LINHA2,
						e.EMPFONE as LINHA3,
						e.EMPSITE as SITE
					FROM
						EMPRESAUSUARIA e
					WHERE
						e.EMPSTATUS = 'S' AND
						e.EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA'];

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSLINHA1			= $vSResultLinha['LINHA1'];
            $vSLINHA2			= $vSResultLinha['LINHA2'];
            $vSLINHA3			= $vSResultLinha['LINHA3'];
			$vSSITE				= $vSResultLinha['SITE'];
            $vSEMPLOGOMARCA		= "../imagens/empresas/".$vSResultLinha['LOGOMARCA'];
        }

        if($vSEMPLOGOMARCA != "") {
            $vADimensoes = getimagesize($vSEMPLOGOMARCA);
            if($vADimensoes[0] > $vADimensoes[1]) {
                $alturanova = ($vADimensoes[1] * 280) / $vADimensoes[0];
                if($alturanova >  100)
                    $vSDimensao = "style='height: 100px'";
                else
                    $vSDimensao = "style='width: 280px'";
            } else
                $vSDimensao = "style='height: 100px'";
        }

        $vSHtmlDefault["header"] = str_replace("{_CABLOGOMARCA_}", $vSEMPLOGOMARCA, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_CABDIMENSAO_}", $vSDimensao, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_CABLINHA1_}", $vSLINHA1, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_CABLINHA2_}", $vSLINHA2, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_CABLINHA3_}", $vSLINHA3, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_SITE_}", $vSSITE, $vSHtmlDefault["header"]);

		$sqlGetSequencial = "SELECT
								a.ATESEQUENCIAL
							FROM
								ATENDIMENTO a
							WHERE
								a.ATECODIGO = " . $vIATECODIGO;

        $sqlGetSequencialRS = sql_executa(vGBancoSite, $vConexao, $sqlGetSequencial, false);
		$sequencial = '';

        while($row = sql_retorno_lista($sqlGetSequencialRS)) {
			$sequencial = adicionarCaracterLeft($row['ATESEQUENCIAL'], 5);
		}
		
		$vSHtmlDefault["header"] = str_replace("{_SEQUENCIAL_}", $sequencial, $vSHtmlDefault["header"]);
        
		/////////////////
        // ATENDIMENTO //
        /////////////////

        $vSSql = "SELECT
                        a.ATESEQUENCIAL,
                        a.ATECODIGOAUX,
                        (SELECT c1.CLINOMEFANTASIA FROM CLIENTES c1 WHERE c1.CLICODIGO = a.CLICODIGO) as CLIENTE,
                        (SELECT
							GROUP_CONCAT(c2.CONNOME SEPARATOR ', ')
						FROM
							CONTATOS c2
						WHERE
							c2.CONCODIGO IN (SELECT
												axc.CONCODIGO
											FROM
												ATENDIMENTOSXCONTATOS axc
											WHERE
												axc.AXCSTATUS = 'S' AND
												axc.ATECODIGO = a.ATECODIGO)
						) as CONTATO,
                        (SELECT
                            CONCAT(
                                IFNULL((SELECT t1.TLOSIGLA FROM TIPOLOGRADOURO t1 WHERE t1.TLOCODIGO = e1.TLOCODIGO),''),
                                ' ',
                                IFNULL(e1.ENDLOGRADOURO,''),
                                ', ',
                                IFNULL(e1.ENDNROLOGRADOURO,''),
                                ', ',
                                IFNULL(e1.ENDCOMPLEMENTO,''),
                                ', ',
                                IFNULL(e1.ENDBAIRRO,''),
                                ', ',
                                IFNULL((SELECT c3.CIDDESCRICAO FROM CIDADES c3 WHERE c3.CIDCODIGO = e1.CIDCODIGO),''),
                                ' - ',
                                IFNULL((SELECT e2.ESTSIGLA FROM ESTADOS e2 WHERE e2.ESTCODIGO = e1.ESTCODIGO),'')
                            )
                        FROM
                            ENDERECOS e1
                        WHERE
                            e1.ENDCODIGO = a.ENDCODIGO
                        ) as ENDERECO,
                        (CASE WHEN a.PROCODIGO = 0 THEN
                            a.ATEPRODUTOOUTRO
                        ELSE
                            (SELECT p.PRONOME FROM PRODUTOS p WHERE p.PROCODIGO = a.PROCODIGO)
                        END) as PRODUTO,
                        a.ATENUMEROSERIE,
                        (SELECT t2.TABDESCRICAO FROM TABELAS t2 WHERE t2.TABCODIGO = a.ATETIPOATENDIMENTO) as TIPOATENDIMENTO,
                        (SELECT t3.TABDESCRICAO FROM TABELAS t3 WHERE t3.TABCODIGO = a.ATEORIGEM) as ORIGEM,
                        (SELECT t4.TABDESCRICAO FROM TABELAS t4 WHERE t4.TABCODIGO = a.TABCODIGO) as CATEGORIA,
                        (CASE WHEN a.ATEINTEGRACAO = 'S' THEN
                            'SIM'
                        ELSE
                            'N&Atilde;O'
                        END) as INTEGRACAO,
                        a.ATEASSUNTO,
                        a.ATEMENSAGEM,
                        (SELECT u.USUNOME FROM USUARIOS u WHERE u.USUCODIGO = a.ATEATENDENTE) as ATENDENTE,
                        a.ATEINICIOATENDIMENTO,
                        a.ATEPREVISAOCONCLUSAOFIM
                    FROM
                        ATENDIMENTO a
                    WHERE
                        a.ATECODIGO = ".$vIATECODIGO;

		//corrige max lenght group concat
		$Sql = "SET SESSION group_concat_max_len = 1000000";
		sql_executa(vGBancoSite, $vConexao, $Sql, false);

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSATESEQUENCIAL = adicionarCaracterLeft($vSResultLinha['ATESEQUENCIAL'], 5);
            $vSATECODIGOAUX = $vSResultLinha['ATECODIGOAUX'];
            $vSCLIENTE = $vSResultLinha['CLIENTE'];
            $vSCONTATO = $vSResultLinha['CONTATO'];
            $vSENDERECO = $vSResultLinha['ENDERECO'];
            $vSPRODUTO = $vSResultLinha['PRODUTO'];
            $vSATENUMEROSERIE = $vSResultLinha['ATENUMEROSERIE'];
            $vSTIPOATENDIMENTO = $vSResultLinha['TIPOATENDIMENTO'];
            $vSORIGEM = $vSResultLinha['ORIGEM'];
            $vSCATEGORIA = $vSResultLinha['CATEGORIA'];
            $vSINTEGRACAO = $vSResultLinha['INTEGRACAO'];
            $vSATEASSUNTO = $vSResultLinha['ATEASSUNTO'];
            $vSATEMENSAGEM = $vSResultLinha['ATEMENSAGEM'];
            $vSATENDENTE = $vSResultLinha['ATENDENTE'];
            $vSATEINICIOATENDIMENTO = formatar_data($vSResultLinha['ATEINICIOATENDIMENTO']);
            $vSATEPREVISAOCONCLUSAO = formatar_data($vSResultLinha['ATEPREVISAOCONCLUSAO']);
        }

        $vSHtmlDefault["header"] = str_replace("{_ATESEQUENCIAL_}", $vSATESEQUENCIAL, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_ATECODIGOAUX_}", $vSATECODIGOAUX, $vSHtmlDefault["header"]);
        $vSHtmlDefault["header"] = str_replace("{_DATAINICIO_}", $vSATEINICIOATENDIMENTO, $vSHtmlDefault["header"]);
        $vSHtmlDefault["body"] = str_replace("{_CLIENTE_}", $vSCLIENTE, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_CONTATO_}", $vSCONTATO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_ENDERECO_}", $vSENDERECO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_PRODUTO_}", $vSPRODUTO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_NUMEROSERIE_}", $vSATENUMEROSERIE, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_TIPOATENDIMENTO_}", $vSTIPOATENDIMENTO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_ORIGEM_}", $vSORIGEM, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_CATEGORIA_}", $vSCATEGORIA, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_INTEGRACAO_}", $vSINTEGRACAO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_ASSUNTO_}", $vSATEASSUNTO, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_DESCRICAO_}", nl2br($vSATEMENSAGEM), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_ATENDENTE_}", $vSATENDENTE, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_INICIOATENDIMENTO_}", $vSCLINOMEFANTASIA, $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("{_PREVISAOCONCLUSAO_}", $vSATEPREVISAOCONCLUSAO, $vSHtmlDefault["body"]);

        //DADOS ADICIONAIS
        $vSHtmlDefault["body"] = str_replace("{_DADOSADICIONAIS_}", getDadosAdicionais($vIATECODIGO), $vSHtmlDefault["body"]);

        //POSIÇÕES
        $vSHtmlDefault["body"] = str_replace("{_POSICOES_}", getPosicoes($vIATECODIGO), $vSHtmlDefault["body"]);

        $vSSql = "SELECT
                        CONCAT(
                            (SELECT
                                t.TLOSIGLA
                            FROM
                                TIPOLOGRADOURO t
                            WHERE
                                t.TLOCODIGO = e.TLOCODIGO
                            ),
                            ' ',
                            e.EMPLOGRADOURO,
                            ', ',
                            e.EMPNROLOGRADOURO,
                            ', ',
                            e.EMPCOMPLEMENTO
                        ) as LINHA1,
                        CONCAT(
                            e.EMPBAIRRO,
                            ', ',
                            (SELECT
                                c.CIDDESCRICAO
                            FROM
                                CIDADES c
                            WHERE
                                c.CIDCODIGO = e.CIDCODIGO
                            ),
                            ' - ',
                            (SELECT
                                es.ESTSIGLA
                            FROM
                                ESTADOS es
                            WHERE
                                es.ESTCODIGO = e.ESTCODIGO
                            )
                        ) as LINHA2
                    FROM
                        EMPRESAUSUARIA e
                    WHERE
                        e.EMPSTATUS = 'S' AND
                        e.EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA'];

        $vSSql = stripcslashes($vSSql);
		$vConexao = sql_conectar_banco();
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSLINHA1 = $vSResultLinha['LINHA1'];
            $vSLINHA2 = $vSResultLinha['LINHA2'];
        }

		$vSRodape = "<table width='100%'>
                            <tr>
                                <td align='left' width='25%'></td>
                                <td align='center' width='50%' rowspan='3'>
                                    ".ucwords(strtolower($vSLINHA1))." ".$vSLINHA2."
                                </td>
                                <td align='right' width='25%'></td>
                            </tr>
                            <tr>
                                <td align='left' width='25%'>
                                    {DATE j/m/Y - H:i}
                                </td>
                                <td align='right' width='25%'>
                                    {PAGENO}/{nb}
                                </td>
                            </tr>
                        </table>";

        //return $vSRodape;
		$vSHtmlDefault["body"] .= montarSubAtendimentos($vIATECODIGO);

        sql_fechar_conexao_banco($vConexao);

        /////////
        // PDF //
        /////////

        // mode, format, default_font_size, default_font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer
        $mpdf = new mPDF("c", "A4", "", "helvetica", 10, 10, 48, 15, 8, 8);

        //DEFINE O CABEÇALHO
        $mpdf->SetHTMLHeader($vSHtmlDefault["header"]);
		$mpdf->SetHTMLFooter($vSRodape);
        $mpdf->SetDisplayMode("fullpage");

        $mpdf->list_indent_first_level = 0;


		//$vSRodape

        //Carrega css padrão PDF
        $stylesheet = file_get_contents("../css/mpdf.css");
        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->WriteHTML($vSHtmlDefault["body"], 2);

		$mpdf->Output("atendimento-".$vSATESEQUENCIAL.".pdf", "I");
		//echo $vSHtmlDefault["header"].$vSHtmlDefault["body"];

    } else {
        header("Location: main.php");
    }
	
	function getDadosAdicionais($pIATECODIGO) {
		$vConexao = sql_conectar_banco();

        $vSSql = "SELECT
                        ae.AXENUMERO,
                        ae.AXEDESCRICAO,
                        ae.AXEDETALHES
                    FROM
                        ATENDIMENTOXESPECIFICACAO ae
                    WHERE
                        ae.AXESTATUS = 'S' AND
                        ae.ATECODIGO = ".$pIATECODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
        $vSHtmlDadosAdicionais = "";
        $vICount = 0;

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSAXENUMERO = $vSResultLinha['AXENUMERO'];
            $vSAXEDESCRICAO = $vSResultLinha['AXEDESCRICAO'];
            $vSAXEDETALHES = $vSResultLinha['AXEDETALHES'];
            $vICount = $vICount + 1;

            if ($vICount == 1) {
                $vSHtmlDadosAdicionais .= "<br />
                                            <table border='1' style='border-collapse: collapse;' width='100%'>
												<tr style='background-color:#bbb;'><td colspan='3' align='left'><center><b>DADOS ADICIONAIS</b></center></td></tr>
                                                <tr>
                                                    <td align='center' colspan='1' width='33%'><b>N&uacute;mero</b></td>
                                                    <td align='center' colspan='1' width='33%'><b>Sentido Giro</b></td>
                                                    <td align='center' colspan='1' width='34%'><b>Posi&ccedil;&atilde;o Cx. Control</b></td>
                                                </tr>
                                                <tr>
                                                    <td align='center' colspan='1' width='33%'>".$vSAXENUMERO."</td>
                                                    <td align='center' colspan='1' width='33%'>".$vSAXEDESCRICAO."</td>
                                                    <td align='center' colspan='1' width='34%'>".$vSAXEDETALHES."</td>
                                                </tr>";
            } elseif ($vICount > 1) {
                $vSHtmlDadosAdicionais .= "<tr>
                                                <td align='center' colspan='1' width='33%'>".$vSAXENUMERO."</td>
                                                <td align='center' colspan='1' width='33%'>".$vSAXEDESCRICAO."</td>
                                                <td align='center' colspan='1' width='34%'>".$vSAXEDETALHES."</td>
                                            </tr>";
            }
        }

        if ($vSHtmlDadosAdicionais != "")
            $vSHtmlDadosAdicionais .= "</table>";

		return $vSHtmlDadosAdicionais;
	}
	
	function getPosicoes($pIATECODIGO) {
		$vConexao = sql_conectar_banco();

        $vSSql = "SELECT
                        ah.AXHDATA_INC,
                        (SELECT u.USUNOME FROM USUARIOS u WHERE u.USUCODIGO = ah.AXHUSU_INC) as USUARIO,
                        ah.AXHDESCRICAO
                    FROM
                        ATENDIMENTOXHISTORICOS ah
                    WHERE
                        ah.AXHSTATUS = 'S' AND
                        ah.ATECODIGO = ".$pIATECODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
        $vSHtmlPosicoes = "";
        $vICount = 0;

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSDATAHORA = formatar_data_hora($vSResultLinha['AXHDATA_INC']);
            $vSUSUARIO = $vSResultLinha['USUARIO'];
            $vSAXHDESCRICAO = $vSResultLinha['AXHDESCRICAO'];
            $vICount = $vICount + 1;

            if ($vICount == 1) {
                $vSHtmlPosicoes .= "<br />
                                    <table border='1' style='border-collapse: collapse;' width='100%'>
										<tr style='background-color:#bbb;'><td colspan='3' align='left'><center><b>POSIÇÕES</b></center></td></tr>
                                        <tr>
                                            <td align='center' colspan='1' width='20%'><b>Data / Hora</b></td>
                                            <td align='center' colspan='1' width='25%'><b>Usu&aacute;rio</b></td>
                                            <td align='center' colspan='1' width='40%'><b>Descri&ccedil;&atilde;o</b></td>
                                        </tr>
                                        <tr>
                                            <td align='center' colspan='1' width='20%'>".$vSDATAHORA."</td>
                                            <td align='center' colspan='1' width='25%'>".$vSUSUARIO."</td>
                                            <td align='center' colspan='1' width='40%'>".$vSAXHDESCRICAO."</td>
                                        </tr>";
            } elseif ($vICount > 1) {
                $vSHtmlPosicoes .= "<tr>
                                        <td align='center' colspan='1' width='20%'>".$vSDATAHORA."</td>
                                        <td align='center' colspan='1' width='25%'>".$vSUSUARIO."</td>
                                        <td align='center' colspan='1' width='40%'>".$vSAXHDESCRICAO."</td>
                                    </tr>";
            }
        }

        if ($vSHtmlPosicoes != "")
            $vSHtmlPosicoes .= "</table>";

		return $vSHtmlPosicoes;
	}
	
	function montarSubAtendimentos( $vIATECODIGO ){
		$vSSql = "SELECT
						a.AXSCODIGO 
					FROM 
						ATENDIMENTOSXSUBATENDIMENTOS a 
					WHERE 
						a.AXSSTATUS = 'S' AND
						a.ATECODIGO  = " .$vIATECODIGO;
						
		$vConexao = sql_conectar_banco();
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
		$vSSubAtendimentoTexto = "<br />";

		$vASubAtendimentos = array();

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
			array_push($vASubAtendimentos, $vSResultLinha['AXSCODIGO']);
		}
		//sql_fechar_conexao_banco($vConexao);

		foreach( $vASubAtendimentos as $id){
			$vSSubAtendimentoTexto .= getSubAtendimento($id) . "<br /><br />";
		}
		return $vSSubAtendimentoTexto;
	}
	
	function getSubAtendimento($vICODIGO) {
		$vConexao = sql_conectar_banco();
		$vIAXSCODIGO = $vICODIGO;
		$vSSubAtendimento = array();

        $vSSubAtendimento["body"] = "<table border='1' style='border-collapse: collapse;' width='100%'>
                                        <tr style='background-color:#bbb;'><td colspan='4' align='left'><center><b>SUB ATENDIMENTO {_SEQUENCIAL_}</b></center></td></tr>
                                        <tr>
                                            <td colspan='1' width='20%'><b>Assunto: </b></td>
                                            <td colspan='3' width='80%'>{_ASSUNTO_}</td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' width='20%' height='30'><b>Descri&ccedil;&atilde;o: </b></td>
                                            <td colspan='3' width='80%'>{_DESCRICAO_}</td>
                                        </tr>
                                        <tr>
                                            <td width='10%'><b>Atendente: </b></td>
                                            <td width='40%'>{_ATENDENTE_}</td>
                                            <td width='10%'><b>Posi&ccedil;&atilde;o: </b></td>
                                            <td width='40%'>{_POSICAO_}</td>
                                        </tr>
                                        <tr>
                                            <td width='10%'><b>Previs&atilde;o Conclus&atilde;o: </b></td>
                                            <td width='40%'>{_PREVISAOCONCLUSAO_}</td>
                                            <td width='10%'><b>Conclus&atilde;o: </b></td>
                                            <td width='40%'>{_CONCLUSAO_}</td>
                                        </tr>
                                        <tr>
                                            <td width='10%'><b>Previsto / Realizado: </b></td>
                                            <td width='40%'>{_PREVISTOREALIZADO_}</td>
                                            <td width='10%'><b>Excedente: </b></td>
                                            <td width='40%'>{_EXCEDENTE_}</td>
                                        </tr>
                                    </table>
                                    {_ATIVIDADES_}";

        $vSSql = "SELECT
                    a.ATECODIGO,
                    CONCAT(a.ATESEQUENCIAL, '-', sa.AXSSEQUENCIAL) SEQUENCIAL,
                    u.USUNOME as ATENDENTE,
                    sa.AXSASSUNTO,
                    sa.AXSDESCRICAO,
                    sa.AXSPREVISAOCONCLUSAO,
                    sa.AXSPREVISAOCONCLUSAOHORA,
                    SUBSTRING((SELECT
                                    SEC_TO_TIME(
                                        SUM(
                                            (TIME_TO_SEC(CONCAT(sht.SHTHORAFIM, ':00')) - TIME_TO_SEC(CONCAT(sht.SHTHORAINI, ':00')))
                                        )
                                    )
                                FROM
                                    SUBATENDIMENTOXHORASTRABALHADAS sht
                                WHERE
                                    sht.SHTSTATUS = 'S' AND
                                    sht.SXHCODIGO IN (SELECT
                                                            sxh.SXHCODIGO
                                                        FROM
                                                            SUBATENDIMENTOXHISTORICOS sxh
                                                        WHERE
                                                            sxh.SXHSTATUS = 'S' AND
                                                            sxh.AXSCODIGO = sa.AXSCODIGO)
                                ), 1, 5
                    ) as TEMPO_REALIZADO,
                    SUBSTRING(
                        SEC_TO_TIME(
                            (TIME_TO_SEC(
                                CONCAT(
                                    SUBSTRING((SELECT
                                                    SEC_TO_TIME(
                                                        SUM(
                                                            (TIME_TO_SEC(CONCAT(sht.SHTHORAFIM, ':00')) - TIME_TO_SEC(CONCAT(sht.SHTHORAINI, ':00')))
                                                        )
                                                    )
                                                FROM
                                                    SUBATENDIMENTOXHORASTRABALHADAS sht
                                                WHERE
                                                    sht.SHTSTATUS = 'S' AND
                                                    sht.SXHCODIGO IN (SELECT
                                                                            sxh.SXHCODIGO
                                                                        FROM
                                                                            SUBATENDIMENTOXHISTORICOS sxh
                                                                        WHERE
                                                                            sxh.SXHSTATUS = 'S' AND
                                                                            sxh.AXSCODIGO = sa.AXSCODIGO)
                                                ), 1, 5
                                    ), ':00'
                                )
                            )
                            -
                            TIME_TO_SEC(
                                CONCAT(
                                    sa.AXSPREVISAOCONCLUSAOHORA, ':00'
                                )
                            ))
                        ), 1, 5
                    ) as EXCEDENTE,
                    sa.AXSDATACONCLUSAO,
                    sa.AXSPOSICAO
                FROM
                    ATENDIMENTO a
                LEFT JOIN
                    ATENDIMENTOSXSUBATENDIMENTOS sa
                ON
                    sa.ATECODIGO = a.ATECODIGO
                LEFT JOIN
                    USUARIOS u
                ON
                    u.USUCODIGO = sa.AXSATENDENTE
                WHERE
                    a.EMPCODIGO = ".$_SESSION["SI_USU_EMPRESA"]." AND
                    a.ATESTATUS = 'S' AND
					sa.AXSSTATUS = 'S' AND
                    sa.AXSCODIGO = ".$vIAXSCODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vIATECODIGO = $vSResultLinha['ATECODIGO'];
            $vSSEQUENCIAL = adicionarCaracterLeft($vSResultLinha['SEQUENCIAL'], 7);
            $vSATENDENTE = $vSResultLinha['ATENDENTE'];
            $vSAXSASSUNTO = $vSResultLinha['AXSASSUNTO'];
            $vSAXSDESCRICAO = $vSResultLinha['AXSDESCRICAO'];
            $vSAXSPREVISAOCONCLUSAO = formatar_data($vSResultLinha['AXSPREVISAOCONCLUSAO']);
            $vSAXSPREVISAOCONCLUSAOHORA = $vSResultLinha['AXSPREVISAOCONCLUSAOHORA'];
            $vSTEMPO_REALIZADO = $vSResultLinha['TEMPO_REALIZADO'];
            $vSEXCEDENTE = $vSResultLinha['EXCEDENTE'];
            $vSAXSDATACONCLUSAO = formatar_data($vSResultLinha['AXSDATACONCLUSAO']);
            $vSAXSPOSICAO = $vSResultLinha['AXSPOSICAO'];
        }

		$vSSubAtendimento["body"] = str_replace("{_SEQUENCIAL_}", $vSSEQUENCIAL, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_ASSUNTO_}", $vSAXSASSUNTO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_DESCRICAO_}", $vSAXSDESCRICAO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_ATENDENTE_}", $vSATENDENTE, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_POSICAO_}", $vSAXSPOSICAO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_PREVISAOCONCLUSAO_}", $vSAXSPREVISAOCONCLUSAO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_CONCLUSAO_}", $vSAXSDATACONCLUSAO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_PREVISTOREALIZADO_}", $vSAXSPREVISAOCONCLUSAOHORA." / ".$vSTEMPO_REALIZADO, $vSSubAtendimento["body"]);
        $vSSubAtendimento["body"] = str_replace("{_EXCEDENTE_}", $vSEXCEDENTE, $vSSubAtendimento["body"]);

        /////////////////////////
        // Pacote de Trabalho  //
        /////////////////////////

		//echo $vSSql;
        $vSSql = "SELECT
                        sah.SXHCODIGO,
                        sah.SXHDESCRICAO,
                        sah.SXHPOSICAO,
                        sah.SXHNAOCONFORMIDADE
                    FROM
                        SUBATENDIMENTOXHISTORICOS sah
                    WHERE
                        sah.SXHSTATUS  = 'S' AND
                        sah.EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA']." AND
                        sah.AXSCODIGO = ".$vIAXSCODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
        $vSHtmlAtividades = "";
        $vICount = 0;

        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vISXHCODIGO = $vSResultLinha['SXHCODIGO'];
            $vSSXHDESCRICAO = $vSResultLinha['SXHDESCRICAO'];
            $vSSXHPOSICAO = $vSResultLinha['SXHPOSICAO'];
            $vSSXHNAOCONFORMIDADE = $vSResultLinha['SXHNAOCONFORMIDADE'];
            $vICount = $vICount + 1;


                $vSHtmlAtividades .= "<table border='1' style='border-collapse: collapse;' width='100%'>
										<tr style='background-color:#d6d6d6;'><td align='left' colspan='3'><center><b>Pacote de Trabalho</b></center></td></tr>
										<tr>
											<td align='center' width='50%'><b>Descrição</b></td>
											<td align='center' width='15%'><b>Posição</b></td>
											<td align='center' width='35%'><b>Não Conformidade</b></td>
										</tr>
										<tr>
											<td align='justify'>".nl2br($vSSXHDESCRICAO)."</td>
											<td align='center'>".$vSSXHPOSICAO."</td>
											<td align='justify'>".$vSSXHNAOCONFORMIDADE."</td>
										</tr>";

            ///////////////////////
            // HORAS TRABALHADAS //
            ///////////////////////
            $vSSqlH = "SELECT
                            sht.SHTDATA,
                            sht.SHTHORAINI,
                            sht.SHTHORAFIM,
                            SUBSTRING(SEC_TO_TIME((TIME_TO_SEC(CONCAT(sht.SHTHORAFIM, ':00')) - TIME_TO_SEC(CONCAT(sht.SHTHORAINI, ':00')))), 1, 5) as TEMPO_REALIZADO,
                            (CASE WHEN sht.SHTRETRABALHO = 'S' THEN
                                'Sim'
                            ELSE
                                'N&atilde;o'
                            END) as RETRABALHO
                        FROM
                            SUBATENDIMENTOXHORASTRABALHADAS sht
                        WHERE
                            sht.SHTSTATUS  = 'S' AND
                            sht.EMPCODIGO = ".$_SESSION['SI_USU_EMPRESA']." AND
                            sht.SXHCODIGO = ".$vISXHCODIGO;

            $vSSqlH = stripcslashes($vSSqlH);
            $vAResultDBH = sql_executa(vGBancoSite, $vConexao, $vSSqlH, false);
            $vSHtmlHoras = "";
            $vICountH = 0;

            while($vSResultLinhaH = sql_retorno_lista($vAResultDBH)) {
                $vSSHTDATA = formatar_data_hora($vSResultLinhaH['SHTDATA']);
                $vSSHTHORAINI = $vSResultLinhaH['SHTHORAINI'];
                $vSSHTHORAFIM = $vSResultLinhaH['SHTHORAFIM'];
                $vSTEMPO_REALIZADO = $vSResultLinhaH['TEMPO_REALIZADO'];
                $vSRETRABALHO = $vSResultLinhaH['RETRABALHO'];
                $vICountH = $vICountH + 1;

                if ($vICountH == 1) {
                    $vSHtmlHoras .= "<tr><td colspan='3'><center><b>HORAS TRABALHADAS</b></center></td></tr>
                                    <tr>
                                        <td colspan='3'>
                                            <table border='0' style='border-collapse: collapse;' width='100%'>
                                                <tr>
                                                    <td align='center' width='20%'><b>Data</b></td>
                                                    <td align='center' width='20%'><b>In&iacute;cio</b></td>
                                                    <td align='center' width='20%'><b>T&eacute;rmino</b></td>
                                                    <td align='center' width='20%'><b>Tempo Realizado</b></td>
                                                    <td align='center' width='20%'><b>Retrabalho</b></td>
                                                </tr>";
                }

				$vSHtmlHoras .= "<tr>
									<td align='center'>".$vSSHTDATA."</td>
									<td align='center'>".$vSSHTHORAINI."</td>
									<td align='center'>".$vSSHTHORAFIM."</td>
									<td align='center'>".$vSTEMPO_REALIZADO."</td>
									<td align='center'>".$vSRETRABALHO."</td>
								</tr>";
            }

            if ($vSHtmlHoras != "") {
                $vSHtmlHoras .= "</table></td></tr>";
                $vSHtmlAtividades = $vSHtmlAtividades.$vSHtmlHoras;
            }
			$vSHtmlAtividades .= "</table>";
        }

        $vSSubAtendimento["body"] = str_replace("{_ATIVIDADES_}", ($vSHtmlAtividades), $vSSubAtendimento["body"]);

		return $vSSubAtendimento["body"];
	}
?>