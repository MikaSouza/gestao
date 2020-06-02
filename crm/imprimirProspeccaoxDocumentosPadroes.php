<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
include_once __DIR__.'/../twcore/teraware/php/constantes.php';
include_once __DIR__.'/../twcore/vendors/mpdf/mpdf.php';

    function removeMonetario($pSValue) {
        $vSValue = $pSValue;
        $vSValue = str_replace("centavo", "", $vSValue);
        $vSValue = str_replace("real", "", $vSValue);
        $vSValue = str_replace("centavos", "", $vSValue);
        $vSValue = str_replace("reais", "", $vSValue);
        return $vSValue;
    }

    if (($_SERVER["REQUEST_METHOD"] == "GET") && ($_GET["vICXPCODIGO"] != "")) {
        $vIDOPCODIGO = $_GET["vIDOPCODIGO"];
        $vConexao = sql_conectar_banco();
			
        ///////////////////////
        // CONTRATOS PADRÕES //
        ///////////////////////

        $vSSql = "SELECT
                        cp.DOPNOME,
                        cp.DOPDESCRICAO
                    FROM
                        DOCUMENTOSPADROES cp
                    WHERE
                        cp.DOPSTATUS = 'S' AND                        
                        cp.DOPCODIGO = ".$vIDOPCODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSCOPNOME = $vSResultLinha['DOPNOME'];
            $vSCOPDESCRICAO = $vSResultLinha['DOPDESCRICAO'];			
        }
		
        ////////////////////
        // CONFIGURA HTML //
        ////////////////////

        $vSHtmlDefault = array();
		
		$vSHtmlDefault["header"] = "<table border='1' style='border-collapse: collapse;' width='100%'>
											<tr>
												<th colspan='1' align='left'>
													<img src='../assets/images/topo.jpg' />
												</th>
											</tr>											
										</table>";
		
		$vIMargemTopo = 45;
		//$vIMargemTopo = 10;
		//$vSHtmlDefault["header"] = '';
			
        ///////////////////////
        // DADOS DO CONTRATO //
        ///////////////////////

		$vSHtmlDefault["body"] = $vSCOPDESCRICAO;
		
		$vSSql = "SELECT
				C.*, P.PXCNOME, T.CLINOME, 
				U.USUNOME AS REPRESENTANTE, U2.USUNOME AS ATENDENTE,
				T2.TABDESCRICAO AS PRODUTO
			FROM PROSPECCAO C	
			LEFT JOIN CLIENTES T ON T.CLICODIGO = C.CLICODIGO
			LEFT JOIN USUARIOS U ON U.USUCODIGO = C.CXPREPRESENTANTE
			LEFT JOIN USUARIOS U2 ON U2.USUCODIGO = C.CXPUSU_INC
			LEFT JOIN TABELAS T2 ON T2.TABCODIGO = C.CXPPRODUTO
			WHERE CXPCODIGO = ".$vICXPCODIGO;

        $vSSql = stripcslashes($vSSql);
        $vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
        while($vSResultLinha = sql_retorno_lista($vAResultDB)) {
            $vSCLIENTE = $vSResultLinha['CLINOME'];
            $vSCPFCNPJ = $vSResultLinha['CPFCNPJ'];
			/*
			$vSCLIIE = $vSResultLinha['CLIIE'];
			$vSCLIIM = $vSResultLinha['CLIIM'];
            $vSCLIENTEENDERECO = $vSResultLinha['CLIENTEENDERECO'];
            $vSCLIFONE = $vSResultLinha['CLIFONE'];
            $vSCLIENTEEMAIL = $vSResultLinha['CLIEMAIL'];
            $vSTIPOCONTRATO = $vSResultLinha['TIPOCONTRATO'];
            $vSCTRNROCONTRATO = adicionarCaracterLeft($vSResultLinha['CTRNROCONTRATO'], 5);
            $vSCTRDATAAINICIO = formatar_data($vSResultLinha['CTRDATAAINICIO']);
            $vSCTRDATATERMINO = formatar_data($vSResultLinha['CTRDATATERMINO']);
            $vSCTRDATACANCELAMENTO = formatar_data($vSResultLinha['CTRDATACANCELAMENTO']);
            $vSINDICEREAJUSTE = $vSResultLinha['INDICEREAJUSTE'];
            $vSVENDEDOR = $vSResultLinha['VENDEDOR'];
            $vSCTRHORASCOBERTURA = $vSResultLinha['CTRHORASCOBERTURA']." (".valorPorExtenso($vSResultLinha['CTRHORASCOBERTURA']).")";
            $vSCTRHORASCOBERTURA = removeMonetario($vSCTRHORASCOBERTURA);
            $vSCTRDESCRICAO = $vSResultLinha['CTRDESCRICAO'];
            $vSRESPONSAVELASSINATURA = $vSResultLinha['RESPONSAVELASSINATURA'];
            $vSREPRESENTANTELEGAL = $vSResultLinha['REPRESENTANTELEGAL'];
            $vSCTRDIAFATURAMENTO = $vSResultLinha['CTRDIAFATURAMENTO']." (".valorPorExtenso($vSResultLinha['CTRDIAFATURAMENTO']).")";
            $vSCTRDIAFATURAMENTO = removeMonetario($vSCTRDIAFATURAMENTO);
            $vSCTRVALORCOBERTURA = formatar_moeda($vSResultLinha['CTRVALORCOBERTURA'])." (".valorPorExtenso($vSResultLinha['CTRVALORCOBERTURA']).")";
			$vSCENTRODECUSTO = $vSResultLinha['CENTRODECUSTO'];*/
        }

        $vSHtmlDefault["body"] = str_replace("[cliente]", ($vSCLIENTE), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[cliente_cpf_cnpj]", ($vSCPFCNPJ), $vSHtmlDefault["body"]);
		/*
		$vSHtmlDefault["body"] = str_replace("[cliente_inscricao_estadual]", ($vSCLIIE), $vSHtmlDefault["body"]);
		$vSHtmlDefault["body"] = str_replace("[cliente_inscricao_municipal]", ($vSCLIIM), $vSHtmlDefault["body"]);		
        $vSHtmlDefault["body"] = str_replace("[cliente_endereco]", ($vSCLIENTEENDERECO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[cliente_telefone]", ($vSCLIFONE), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[cliente_email]", ($vSCLIENTEEMAIL), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[tipo_contrato]", ($vSTIPOCONTRATO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[numero_contrato]", ($vSCTRNROCONTRATO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["header"] = str_replace("{_NUMERO_CONTRATO_}", ($vSCTRNROCONTRATO), $vSHtmlDefault["header"]);
        $vSHtmlDefault["body"] = str_replace("[vig_data_inicio]", ($vSCTRDATAAINICIO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[vig_data_fim]", ($vSCTRDATATERMINO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[vig_data_cancelamento]", ($vSCTRDATACANCELAMENTO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[indice_reajuste]", ($vSINDICEREAJUSTE), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[vendedor]", ($vSVENDEDOR), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[horas_cobertura]", ($vSCTRHORASCOBERTURA), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[descricao_contrato]", ($vSCTRDESCRICAO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[contratada_assinatura]", ($vSRESPONSAVELASSINATURA), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[contratante_assinatura]", ($vSREPRESENTANTELEGAL), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[dia_faturamento]", ($vSCTRDIAFATURAMENTO), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[valor_cobertura]", ($vSCTRVALORCOBERTURA), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[centro_custo]", ($vSCENTRODECUSTO), $vSHtmlDefault["body"]);
        */
       		
        ///////////////////////
        // ALTERAÇÕES GERAIS //
        ///////////////////////

        $vSHtmlDefault["body"] = str_replace("[data_dia_atual_n]", date("d"), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[data_mes_atual_n]", date("m"), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[data_mes_atual_s]", descricaoMes(date("n")), $vSHtmlDefault["body"]);
        $vSHtmlDefault["body"] = str_replace("[data_ano_atual_n]", date("Y"), $vSHtmlDefault["body"]);	
		
        sql_fechar_conexao_banco($vConexao);
        //echo $vSHtmlDefault["header"].$vSHtmlDefault["body"];

        /////////
        // PDF //
        /////////

        // mode, format, default_font_size, default_font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer
        $mpdf = new mPDF("c", "A4", "", "", 10, 10, $vIMargemTopo, 10, 10, 10);
		
        //DEFINE O CABEÇALHO
        $mpdf->SetHTMLHeader($vSHtmlDefault["header"]);

        $mpdf->SetDisplayMode("fullpage");

        $mpdf->list_indent_first_level = 0;
		
        //Carrega css padrão PDF
        $stylesheet = file_get_contents("../css/mpdf.css");
        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->WriteHTML($vSHtmlDefault["body"], 2);
		//echo _MPDF_TEMP_PATH; 
        $mpdf->Output("contrato-".$vSCLIENTE.".pdf", "I");
		

    } else {
        header("Location: main.php");
    }
?>