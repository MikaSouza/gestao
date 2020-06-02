<?php
/**
 *  @brief Brief
 *
 *  @param [in] $pIEMPCODIGO código da empresa do ambiente
 *  @return Array com configurações do ambiente
 *
 *  @details Details
 */
function buscarAmbienteNFe($pIEMPCODIGO) {
	$vConexao = sql_conectar_banco();
	$SqlMain = 'Select SNFAMBIENTE, SNFMODELO, SNFSERIE
				 From
					SEQUENCIAISNF
				Where
					EMPCODIGO = '.$pIEMPCODIGO.'
				AND	SNFSTATUS = "S"
				AND	SNFTIPO = 2068';
	$RS_MAIN = sql_executa(vGBancoSite, $vConexao,$SqlMain);
	while($reg_RS = sql_retorno_lista($RS_MAIN)){
		if ($reg_RS['SNFAMBIENTE'] == 'H') {
			$ambiente_nfe = array(
				"id_ambiente" => 2,
				"pasta_ambiente" => "homologacao",
				"modelo" => $reg_RS['SNFMODELO'],
				"serie" => $reg_RS['SNFSERIE']
			);
		} else if ($reg_RS['SNFAMBIENTE'] == 'P') {
			$ambiente_nfe = array(
				"id_ambiente" => 1,
				"pasta_ambiente" => "producao",
				"modelo" => $reg_RS['SNFMODELO'],
				"serie" => $reg_RS['SNFSERIE']
			);
		}
	}
	if(!isset($ambiente_nfe['id_ambiente'])) {
		$ambiente_nfe = array(
			"id_ambiente" => 0,
			"pasta_ambiente" => '',
			"modelo" => '',
			"serie" => ''
		);
	}
	return $ambiente_nfe;
}

function buscarDadosConfiguracaoNFSe($pIEMPCODIGO){

	$vConexao = sql_conectar_banco();
	$vSSql = "SELECT
				e.EMPRAZAOSOCIAL,
				e.EMPNOMEFANTASIA,
				es.ESTSIGLA,
				e.ESTCODIGO,
				e.EMPNFSEREGIMEESPECIALTRIBUTACAO,
				e.EMPCEP,
				e.EMPBAIRRO,
				CONCAT(
					(SELECT
						t.TLOSIGLA
					FROM
						TIPOLOGRADOURO t
					WHERE
						t.TLOCODIGO = e.TLOCODIGO
					),
					' ',
					e.EMPLOGRADOURO
				) as LOGRADOURO,
				e.EMPNROLOGRADOURO,
				e.EMPCOMPLEMENTO,
				e.EMPFONE,
				e.EMPISENTAIE,
				e.EMPIE,
				e.EMPISENTAIM,
				e.EMPIM,
				CONCAT(
					(CASE WHEN e.EMPTIPOEMPRESA = 'J' THEN
						e.EMPCNPJ
					ELSE
						e.EMPCPF
					END)
				) as CNPJCPF,
				c.CIDCODIGO,
				c.CIDDESCRICAO,
				f.CCOCERTIFICADOSENHA,
				f.CCOEMAIL,
				f.CCOEMAILHOST,
				f.CCOEMAILUSUARIO,
				f.CCOEMAILSENHA,
				f.CCOEMAILPORTA,
				f.CCOEMAILASSUNTO,
				f.CCOEMAILNOME,
				f.CCOEMAILIMAPHOST,
				f.CCOEMAILIMAPPORTA,
				f.CCOEMAILCOPIA,
				f.CCOEMAILCOPIANOME,
				CONCAT(
					e.EMPCODIGO,
					e.EMPLOGOMARCA
				) as LOGOMARCA,
				n.CNACNAE
			FROM
				EMPRESAUSUARIA e
				LEFT JOIN ESTADOS es ON es.ESTCODIGO = e.ESTCODIGO
				LEFT JOIN CIDADES c ON c.CIDCODIGO = e.CIDCODIGO
				LEFT JOIN CONFIGURACOESCOMERCIAL f ON f.EMPCODIGO = e.EMPCODIGO
				LEFT JOIN CNAE n ON n.CNACODIGO = e.CNACODIGO
			WHERE
				e.EMPSTATUS = 'S' AND
				e.EMPCODIGO = ".$pIEMPCODIGO;

	$vSSql = stripcslashes($vSSql);
	$vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
	while($vSResultLinha = sql_retorno_lista($vAResultDB)) {

		$aAmbiente = buscarAmbienteNFe($pIEMPCODIGO);

		if ($vSResultLinha['EMPISENTAIE'] == 'S')
			$vSEMPIE = 'ISENTO';
		else
			$vSEMPIE = filterNumber($vSResultLinha['EMPIE']);
		if ($vSResultLinha['EMPISENTAIM'] == 'S')
			$vSEMPIM = 'ISENTO';
		else
			$vSEMPIM = filterNumber($vSResultLinha['EMPIM']);

		$aConfig = array(
				"ambiente" => $aAmbiente['id_ambiente'],
				"empresa" => $vSResultLinha['EMPRAZAOSOCIAL'],
				"empresa_nome_fantasia" => $vSResultLinha['EMPNOMEFANTASIA'],
				"cnpj" => filterNumber($vSResultLinha['CNPJCPF']), // somente numeros
				"insc_estadual" => $vSEMPIE, // somente numeros
				"insc_municipal" => filterNumber($vSEMPIM), // somente numeros
				"cnae" => filterNumber($vSResultLinha['CNACNAE']), // somente numeros
				"UF" => $vSResultLinha['ESTSIGLA'],
				"idUF" => $vSResultLinha['ESTCODIGO'],
				"cidade" => $vSResultLinha['CIDDESCRICAO'],
				"idCidade" => $vSResultLinha['CIDCODIGO'],
				"logradouro" => $vSResultLinha['LOGRADOURO'],
				"nro_logradouro" => $vSResultLinha['EMPNROLOGRADOURO'],
				"complemento_logradouro" => $vSResultLinha['EMPCOMPLEMENTO'],
				"cep" => filterNumber($vSResultLinha['EMPCEP']), // somente numeros
				"bairro" => $vSResultLinha['EMPBAIRRO'],
				"fone" => filterNumber($vSResultLinha['EMPFONE']), // somente numeros
				"regime_tributario" => $vSResultLinha['EMPNFSEREGIMEESPECIALTRIBUTACAO'],
				"certName" => $pIEMPCODIGO.'.pfx',
				"keyPass" => $vSResultLinha['CCOCERTIFICADOSENHA'],
				"passPhrase" => "",
				"arquivosDir" => vSCaminhoAbsoluto.'/nfe/'.$pIEMPCODIGO,
				"arquivoURLxml" => "nfe_ws2.xml",
				"baseurl" => "https://erp.teraware.com.br/nfephp",
				"danfeLogo" =>  vSCaminhoAbsoluto.'/imagens/empresas/'.$vSResultLinha['LOGOMARCA'],
				"danfeLogoPos" => "L",
				"danfeFormato" => "P",
				"danfePapel" => "A4",
				"danfeCanhoto" => 1,
				"danfeFonte" => "Times",
				"danfePrinter" => "hpteste",
				"schemes" => "PL_006s",
				"proxyIP" => "",
				"mailAuth" => "1",
				"mailFROM" => $vSResultLinha['CCOEMAIL'],
				"mailHOST" => $vSResultLinha['CCOEMAILHOST'],
				"mailUSER" => $vSResultLinha['CCOEMAILUSUARIO'],
				"mailPASS" => $vSResultLinha['CCOEMAILSENHA'],
				"mailFROMAssunto" => $vSResultLinha['CCOEMAILASSUNTO'],
				"mailPROTOCOL" => "",
				"mailPORT" => $vSResultLinha['CCOEMAILPORTA'],
				"mailFROMmail" => $vSResultLinha['CCOEMAIL'],
				"mailFROMname" => $vSResultLinha['CCOEMAILNOME'],
				"mailREPLYTOmail" => $vSResultLinha['CCOEMAILCOPIA'],
				"mailREPLYTOname" => $vSResultLinha['CCOEMAILCOPIANOME'],
				"mailIMAPhost" => $vSResultLinha['CCOEMAILIMAPHOST'],
				"mailIMAPport" => $vSResultLinha['CCOEMAILIMAPPORTA'],
				"mailIMAPsecurity" => "starttls",
				"mailIMAPnocerts" => "novalidate-cert",
				"mailIMAPbox" => "INBOX",
				"id_ambiente" => $aAmbiente['id_ambiente'],
				"pasta_ambiente" => $aAmbiente['pasta_ambiente'],
				"modelo" => $aAmbiente['modelo'],
				"serie" => $aAmbiente['serie'],
				"mailLayoutFile" => '',
				"recebidasDir" => '',
				"temporariasDir" => '',
				"canceladasDir" => ''
			);
	}
	return $aConfig;
}

function gerarNFSeXMLEnvioLote($pLNSECODIGO) {
	$vSErro = '';
	try {
		$vConexao = sql_conectar_banco();
		$Sql = 'SELECT
				nf.NSECODIGO,
				nf.NSENUMERO,
				nf.EMPCODIGO,
				nf.NSEALIQUOTA,
				nf.NSEVALORTOTAL,
				nf.NSEVALORISS,
				nf.NSEVALORISSRETIDO,
				nf.NSEVALORLIQUIDO,
				nf.NSEBASECALCULO,
				nf.NSESTATUSRECEITA,
				SER.SERITEM,
				SER.SERCODIGO,
				cl.CLIISENTAIE,
				cl.CLIIM,
				cl.CLITIPOCLIENTE,
				CONCAT(
					(CASE WHEN cl.CLITIPOCLIENTE = "J" THEN
						cl.CLICNPJ
					ELSE
						cl.CLICPF
					END)
				) as CNPJCPF,
				cl.CLICNPJ,
				cl.CLICPF,
				cl.CLIRAZAOSOCIAL,
				cl.CLIFONE,
				cl.CLIEMAILENVIONFE,
				CONCAT(
					(SELECT
						t.TLOSIGLA
					FROM
						TIPOLOGRADOURO t
					WHERE
						t.TLOCODIGO = end.TLOCODIGO
					),
					" ",
					end.ENDLOGRADOURO
				) as LOGRADOURO,
				end.ENDNROLOGRADOURO,
				end.ENDCOMPLEMENTO,
				end.ENDBAIRRO,
				end.ENDCEP,
				est.ESTSIGLA,
				est.ESTCODIGO,
				cid.CIDCODIGO,
				cid.CIDDESCRICAO,
				NOS.NOSCODIGOFISCAL,
				nf.NSENUMERORPS,
				nf.NSEISSRETIDO,
				cnae.CNACNAE,
				nf.NSECODIGOTRIBUTACAO,
				nf.NSEALIQUOTAISSRETIDO,
				nf.NSEMUNICIPIOSERVICO
				FROM
					NOTASFISCAISSERVICO nf
				LEFT JOIN
					CLIENTES cl
				ON
					nf.CLICODIGO = cl.CLICODIGO
				LEFT JOIN
					SERVICOS SER
				ON
					SER.SERCODIGO = nf.SERCODIGO
				LEFT JOIN
					NATUREZAOPERACAOSERVICO NOS
				ON
					NOS.NOSCODIGO = nf.NOSCODIGO
				LEFT JOIN
					ENDERECOS end
				ON
					end.ENDCODIGO = nf.ENDCODIGO
				LEFT JOIN
					ESTADOS est
				ON
					est.ESTCODIGO = end.ESTCODIGO
				LEFT JOIN
					CIDADES cid
				ON
					cid.CIDCODIGO = end.CIDCODIGO
				LEFT JOIN
					CNAE cnae
				ON
					cnae.CNACODIGO = nf.CNACODIGO
				WHERE nf.NSECODIGO in ('.$pLNSECODIGO.')';
		$Sql = stripcslashes($Sql);

		$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql, false);
		while($result = sql_retorno_lista($RS_POST)){
			$pINSECODIGO = $result["NSECODIGO"];
			$vSNumeroNF = $pSNumeroNF; //$result["NFSNUMERO"]; //500;
			$vIEMPCODIGO = $result["EMPCODIGO"];
			$vINroLote = $result["NSENUMERORPS"];
			if ($aConfig == '')
				$aConfig = buscarDadosConfiguracaoNFSe($vIEMPCODIGO);
			$aConfig['pasta_ambiente'] = 'producao';
			if ($result['NSESTATUSRECEITA'] == 'B') { // ja foi enviado verificar retorno erro ou sucesso
				// arquivo erro
				$pSCaminhoSafeWebErro = "/".$vIEMPCODIGO."/NFSe/SAIDA/NFS_".$vINroLote.".err";
				$vSCaminhoRetornoErro = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/NFS_".$vINroLote.".xml";

				// arquivo pdf
				$pSCaminhoSafeWebPDF = "/".$vIEMPCODIGO."/NFSe/RPS/NFS_".$vINroLote.".pdf";
				$vSCaminhoRetornoPDF = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/enviadas/pdf/NFS_".$vINroLote.".pdf";

				// arquivo xml
				$pSCaminhoSafeWebXML = "/".$vIEMPCODIGO."/NFSe/BACKUP/NFS_".$vINroLote.".xml";
				$vSCaminhoRetornoXML = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/enviadas/xml/NFS_".$vINroLote.".xml";

				// deletar arquivo se existir
				if (file_exists($vSCaminhoRetornoErro))
					unlink($vSCaminhoRetornoErro);

				// primeiro verifica se caso de sucesso pdf
				ftpArquivo($vSCaminhoRetornoPDF, $pSCaminhoSafeWebPDF, 'download', 'B');
				sleep(1);
				if (file_exists($vSCaminhoRetornoPDF)) {
					$vSRetorno = 'S'; $vSSucesso = 'S';
					$vSErro = ftpArquivo($vSCaminhoRetornoXML, $pSCaminhoSafeWebXML, 'download');
					if ($vSErro == '') {
						$vSErro = gravarNFSeRetornoReceita($pINSECODIGO, $vSCaminhoRetornoXML);
					}

				} else { // caso erro
					$vSErro = ftpArquivo($vSCaminhoRetornoErro, $pSCaminhoSafeWebErro, 'download');
					if (file_exists($vSCaminhoRetornoErro)) {
						$vSRetorno = 'S';
						sleep(1);
						$vSErro = verificarRetornoSafeWeb($vSCaminhoRetornoErro);

						if ($vSErro != '') {
							$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSESTATUSRECEITA = 'R', NSEERRORECEITA = '".$vSErro."'
								where NSECODIGO = ".$pINSECODIGO;
							$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);
						}
					}
				}

			} else {

				// Atributos NFe 2014-07-23T14:51:00-03:00
				$vDDataEmissao = date("Y-m-d").'T'.date("H:i:s");

				//$vDDataEmissao = '2018-04-30'.'T'.date("H:i:s");
				list($vDData, $vTHora) = explode(' ', $vDDataEmissao);
				list($vIAno, $vIMes, $vIDia) = explode('-', substr($vDData, 0,10));
				$vDDataEmissaoNF = $vDData.'T'.$vTHora;
				$vIAAMM = substr($vIAno, 2,2). adicionarCaracterLeft($vIMes, 2);

				$SqlItens = 'SELECT
								I.NXSDESCRICAO, P.PRONOME, I.NXSVALOR
							FROM NOTASFISCAISXITENSSERVICO I
							LEFT JOIN PRODUTOS P ON I.PROCODIGO = P.PROCODIGO
							WHERE I.NSECODIGO = '.$pINSECODIGO.'
							AND I.NXSSTATUS = "S"';
				$RS_POSTItens = sql_executa(vGBancoSite, $vConexao, $SqlItens, false);
				// servicos
				$vSServico = '';
				while($resultItens = sql_retorno_lista($RS_POSTItens)){
					if ($_SESSION['SI_USU_EMPRESA'] == 1)
						$vSServico .= $resultItens['NXSDESCRICAO'].' - '.formatar_moeda($resultItens['NXSVALOR']).'\n';
					else
						$vSServico .= $resultItens['PRONOME'].' '.$resultItens['NXSDESCRICAO'].' ';
				}		

				$vSNFSe = "<GerarNfseEnvio xmlns=\"http://www.abrasf.org.br/nfse.xsd\">";
				$vSNFSe .= "<LoteRps Id=\"$vINroLote\" versao=\"1.00\">";
				$vSNFSe .= "<NumeroLote>".$vINroLote."</NumeroLote>";
				$vSNFSe .= "<Cnpj>".$aConfig['cnpj']."</Cnpj>";
				$vSNFSe .= "<InscricaoMunicipal>".$aConfig['insc_municipal']."</InscricaoMunicipal>";
				$vSNFSe .= "<QuantidadeRps>1</QuantidadeRps>";
				$vSNFSe .= "<ListaRps>";
				$vSNFSe .= "<Rps xmlns=\"http://www.abrasf.org.br/nfse.xsd\">";
				$vSNFSe .= "<InfRps Id=\"R".$vINroLote."\">";
				$vSNFSe .= "<IdentificacaoRps>";
				$vSNFSe .= "<Numero>".$vINroLote."</Numero>";
				$vSNFSe .= "<Serie>".$aConfig['serie']."</Serie>";
				$vSNFSe .= "<Tipo>1</Tipo>";
				$vSNFSe .= "</IdentificacaoRps>";
				$vSNFSe .= "<DataEmissao>".$vDDataEmissao."</DataEmissao>";
				$vSNFSe .= "<NaturezaOperacao>".$result['NOSCODIGOFISCAL']."</NaturezaOperacao>";
				$vSNFSe .= "<RegimeEspecialTributacao>".$aConfig['regime_tributario']."</RegimeEspecialTributacao>";
				$vSNFSe .= "<OptanteSimplesNacional>1</OptanteSimplesNacional>";
				$vSNFSe .= "<IncentivadorCultural>2</IncentivadorCultural>";
				$vSNFSe .= "<Status>1</Status>";
				$vSNFSe .= "<Servico>";
				$vSNFSe .= "<Valores>";
				$vSNFSe .= "<ValorServicos>".$result['NSEVALORTOTAL']."</ValorServicos>";
				$vSNFSe .= "<IssRetido>".$result['NSEISSRETIDO']."</IssRetido>";
				if ($result['NSEISSRETIDO'] == 1)
					$vSNFSe .= "<ValorIss>".$result['NSEVALORISSRETIDO']."</ValorIss>";					
				else
					$vSNFSe .= "<ValorIss>".$result['NSEVALORISS']."</ValorIss>";

				$vSNFSe .= "<BaseCalculo>".$result['NSEBASECALCULO']."</BaseCalculo>";
				if ($result['NSEISSRETIDO'] == 1) {
					$aliquotaISSQN = ($result['NSEALIQUOTAISSRETIDO'] / 100);
					$vSNFSe .= "<Aliquota>".$aliquotaISSQN."</Aliquota>";
				}else
					$vSNFSe .= "<Aliquota>".$result['NSEALIQUOTA']."</Aliquota>";
				$vSNFSe .= "<ValorLiquidoNfse>".$result['NSEVALORLIQUIDO']."</ValorLiquidoNfse>";
				$vSNFSe .= "</Valores>";
				$vSNFSe .= "<ItemListaServico>".$result['SERITEM']."</ItemListaServico>";
				$vSNFSe .= "<CodigoCnae>".filternumber($result['CNACNAE'])."</CodigoCnae>";
				$vSNFSe .= "<CodigoTributacaoMunicipio>".$result['NSECODIGOTRIBUTACAO']."</CodigoTributacaoMunicipio>";
				$vSNFSe .= "<Discriminacao>".$vSServico."</Discriminacao>";
				if ($result['NSEMUNICIPIOSERVICO'] != 4314902)
					$vSNFSe .= "<CodigoMunicipio>".$result['NSEMUNICIPIOSERVICO']."</CodigoMunicipio>";
				else
					$vSNFSe .= "<CodigoMunicipio>".$aConfig['idCidade']."</CodigoMunicipio>";
				$vSNFSe .= "</Servico>";
				$vSNFSe .= "<Prestador>";
				$vSNFSe .= "<Cnpj>".$aConfig['cnpj']."</Cnpj>";
				$vSNFSe .= "<InscricaoMunicipal>".$aConfig['insc_municipal']."</InscricaoMunicipal>";
				$vSNFSe .= "</Prestador>";
				$vSNFSe .= "<Tomador>";
				$vSNFSe .= "<IdentificacaoTomador>";
				$vSNFSe .= "<CpfCnpj>";
				if ($result['CLITIPOCLIENTE'] == 'J')
					$vSNFSe .= "					<Cnpj>".filterNumber($result['CNPJCPF'])."</Cnpj>";
				else
					$vSNFSe .= "					<Cpf>".filterNumber($result['CNPJCPF'])."</Cpf>";
				$vSNFSe .= "</CpfCnpj>";
				if (($result['CLIISENTAIM'] != 'S') && ($result['CLIIM'] != ''))
					$vSNFSe .= "<InscricaoMunicipal>".filterNumber($result["CLIIM"])."</InscricaoMunicipal>";
				$vSNFSe .= "</IdentificacaoTomador>";
				$vSCLIRAZAOSOCIAL = str_replace('&', '&amp;', $result["CLIRAZAOSOCIAL"]);
				$vSNFSe .= "<RazaoSocial>".substr($vSCLIRAZAOSOCIAL,0,113)."</RazaoSocial>";
				$vSNFSe .= "<Endereco>";
				$vSNFSe .= "<Endereco>".$result["LOGRADOURO"]."</Endereco>";
				$vSNFSe .= "<Numero>".$result["ENDNROLOGRADOURO"]."</Numero>";
				if ($result["ENDCOMPLEMENTO"] != '')
					$vSNFSe .= "<Complemento>".$result["ENDCOMPLEMENTO"]."</Complemento>";
				$vSNFSe .= "<Bairro>".$result["ENDBAIRRO"]."</Bairro>";
				$vSNFSe .= "<CodigoMunicipio>".$result["CIDCODIGO"]."</CodigoMunicipio>";
				$vSNFSe .= "<Uf>".$result["ESTSIGLA"]."</Uf>";
				$vSNFSe .= "<Cep>".filterNumber($result["ENDCEP"])."</Cep>";
				$vSNFSe .= "</Endereco>";
				if ((filterNumber($result["CLIFONE"]) != '') || ($result["CLIEMAILENVIONFE"] != '')) {
					$vSNFSe .= "			<Contato>";
					if (filterNumber($result["CLIFONE"]) != '')
						$vSNFSe .= "				<Telefone>".filterNumber($result["CLIFONE"])."</Telefone>";

					if ($result["CLIEMAILENVIONFE"] != '') {
						$vSCLIEMAILNFSE = $result['CLIEMAILENVIONFE'];
						if ($vSCLIEMAILNFSE != '') {
							$vSCLIEMAILNFSE = explode(';', $vSCLIEMAILNFSE);
							$vSCLIEMAILNFSE = $vSCLIEMAILNFSE[0];
							$vSNFSe .= "<Email>".$vSCLIEMAILNFSE."</Email>";
						}
					}
					$vSNFSe .= "			</Contato>";
				}
				$vSNFSe .= "</Tomador>";
				$vSNFSe .= "	</InfRps>";
				$vSNFSe .= "<Signature />";
				$vSNFSe .= "</Rps>";
				$vSNFSe .= "</ListaRps>";
				$vSNFSe .= "</LoteRps>";
				$vSNFSe .= "<Signature />";
				$vSNFSe .= "</GerarNfseEnvio>";

				$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/temp/NFSe_".$vINroLote.".xml";

				$fp = fopen($vSNomeArquivo, "w");
				$escreve = fwrite($fp, $vSNFSe);
				fclose($fp);

				//mandar para outro servidor SafeWeb
				$pSCaminhoDestino = "/".$vIEMPCODIGO."/NFSe/ENTRADA/NFS_".$vINroLote.".xml";

				$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'upload');

				$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSESTATUSRECEITA = 'B', NSEERRORECEITA = ''
								where NSECODIGO = ".$pINSECODIGO;
				$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);
			}
		}
		echo 'Procedimento(s) executado(s) com sucesso!';
		/*
		$vSStatus =	utf8_decode('Aguardando Aprovação');
		$SqlUpdate = "UPDATE COBRANCA set COBSTATUSRECEITA = '".$vSStatus."', COBERRORECEITA = ''
					where COBCODIGO = ".$tuplaNota['COBCODIGO'];
		$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate); */

		/*$pSCaminhoDestino = "/NFSe/ENTRADA/NFS_1_.xml";
		$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'upload');
		$pSCaminhoDestino = "/NFSe/SAIDA/NFS_1_.err";
		$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/NFS_1.xml";
		$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'download');
		$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/NFS_1.xml";
		$vSErro = verificarArquivoRetorno($vSNomeArquivo); */
	} catch (Exception $e) {
	  $vSErro = "Erro ao gravar arquivo XML NFSe: ".  $e->getMessage(). "\n";
	}
	return $vSErro;
}

function gerarNFSeXMLEnvio($pINSECODIGO) {
	$vSErro = '';
	try {
		$vConexao = sql_conectar_banco();
		$Sql = 'SELECT
				nf.NSECODIGO,
				nf.NSENUMERO,
				nf.EMPCODIGO,
				nf.NSEALIQUOTA,
				nf.NSEVALORTOTAL,
				nf.NSEVALORISSRETIDO,
				nf.NSEVALORLIQUIDO,
				nf.NSEBASECALCULO,
				SER.SERITEM,
				cl.CLIISENTAIM,
				cl.CLIIM,
				cl.CLITIPOCLIENTE,
				CONCAT(
					(CASE WHEN cl.CLITIPOCLIENTE = "J" THEN
						cl.CLICNPJ
					ELSE
						cl.CLICPF
					END)
				) as CNPJCPF,
				cl.CLICNPJ,
				cl.CLICPF,
				cl.CLIRAZAOSOCIAL,
				cl.CLIFONE,
				CONCAT(
					(SELECT
						t.TLOSIGLA
					FROM
						TIPOLOGRADOURO t
					WHERE
						t.TLOCODIGO = end.TLOCODIGO
					),
					" ",
					end.ENDLOGRADOURO
				) as LOGRADOURO,
				end.ENDNROLOGRADOURO,
				end.ENDCOMPLEMENTO,
				end.ENDBAIRRO,
				end.ENDCEP,
				est.ESTSIGLA,
				est.ESTCODIGO,
				cid.CIDCODIGO,
				cid.CIDDESCRICAO,
				NOS.NOSCODIGOFISCAL,
				nf.NSENUMERORPS
				FROM
					NOTASFISCAISSERVICO nf
				LEFT JOIN
					CLIENTES cl
				ON
					nf.CLICODIGO = cl.CLICODIGO
				LEFT JOIN
					SERVICOS SER
				ON
					SER.SERCODIGO = nf.SERCODIGO
				LEFT JOIN
					NATUREZAOPERACAOSERVICO NOS
				ON
					NOS.NOSCODIGO = nf.NOSCODIGO
				LEFT JOIN
					ENDERECOS end
				ON
					end.ENDCODIGO = nf.ENDCODIGO
				LEFT JOIN
					ESTADOS est
				ON
					est.ESTCODIGO = end.ESTCODIGO
				LEFT JOIN
					CIDADES cid
				ON
					cid.CIDCODIGO = end.CIDCODIGO
				WHERE nf.NSECODIGO = '.$pINSECODIGO;
		$Sql = stripcslashes($Sql);
		$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql, false);
		while($result = sql_retorno_lista($RS_POST)){
			$vSNumeroNF = $pSNumeroNF; //$result["NFSNUMERO"]; //500;
			$vIEMPCODIGO = $result["EMPCODIGO"];
			$aConfig = buscarDadosConfiguracaoNFSe($vIEMPCODIGO);
			// Atributos NFe 2014-07-23T14:51:00-03:00
			$vDDataEmissao = date("Y-m-d").'T'.date("H:i:s");
			list($vDData, $vTHora) = explode(' ', $vDDataEmissao);
			list($vIAno, $vIMes, $vIDia) = explode('-', substr($vDData, 0,10));
			$vDDataEmissaoNF = $vDData.'T'.$vTHora;
			$vIAAMM = substr($vIAno, 2,2). adicionarCaracterLeft($vIMes, 2);

			$SqlItens = 'SELECT
							I.NXSDESCRICAO, P.PRONOME
						FROM NOTASFISCAISXITENSSERVICO I
						LEFT JOIN PRODUTOS P ON I.PROCODIGO = P.PROCODIGO
						WHERE I.NSECODIGO = '.$pINSECODIGO.'
						AND I.NXSSTATUS = "S"';
			$RS_POSTItens = sql_executa(vGBancoSite, $vConexao, $SqlItens, false);
			// servicos
			while($resultItens = sql_retorno_lista($RS_POSTItens)){
				$vSServico .= $resultItens['PRONOME'].' '.$resultItens['NXSDESCRICAO'].' ';
			}
			//$vINroLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
			$vINroLote = $result["NSENUMERORPS"];
			$vSNFSe .= "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>";
			$vSNFSe .= "<EnviarLoteRpsEnvio xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\">";
			$vSNFSe .= "<LoteRps Id=\"L".$vINroLote."\">";
			$vSNFSe .= "<NumeroLote>".$vINroLote."</NumeroLote>";
			$vSNFSe .= "<Cnpj>".$aConfig['cnpj']."</Cnpj>";
			$vSNFSe .= "<InscricaoMunicipal>".$aConfig['insc_municipal']."</InscricaoMunicipal>";
			$vSNFSe .= "<QuantidadeRps>1</QuantidadeRps>";
			$vSNFSe .= "<ListaRps>";
			$vSNFSe .= "<Rps>";
			$vSNFSe .= "<InfRps Id=\"R".$vINroLote."\">";
			$vSNFSe .= "<IdentificacaoRps>";
			$vSNFSe .= "<Numero>".$vINroLote."</Numero>";
			$vSNFSe .= "<Serie>".$aConfig['serie']."</Serie>";
			$vSNFSe .= "<Tipo>1</Tipo>";
			$vSNFSe .= "</IdentificacaoRps>";
			$vSNFSe .= "<DataEmissao>".$vDDataEmissao."</DataEmissao>";
			$vSNFSe .= "<NaturezaOperacao>".$result['NOSCODIGOFISCAL']."</NaturezaOperacao>";
			$vSNFSe .= "<RegimeEspecialTributacao>".$aConfig['regime_tributario']."</RegimeEspecialTributacao>";
			$vSNFSe .= "<OptanteSimplesNacional>2</OptanteSimplesNacional>";
			$vSNFSe .= "<IncentivadorCultural>2</IncentivadorCultural>";
			$vSNFSe .= "<Status>1</Status>";
			$vSNFSe .= "<Servico>";
			$vSNFSe .= "<Valores>";
			$vSNFSe .= "<ValorServicos>".$result['NSEVALORTOTAL']."</ValorServicos>";
			$vSNFSe .= "<IssRetido>".$result['NSEISSRETIDO']."</IssRetido>";
			$vSNFSe .= "<ValorIss>".$result['NSEVALORISSRETIDO']."</ValorIss>";
			$vSNFSe .= "<BaseCalculo>".$result['NSEBASECALCULO']."</BaseCalculo>";
			$vSNFSe .= "<Aliquota>".$result['NSEALIQUOTA']."</Aliquota>";
			$vSNFSe .= "<ValorLiquidoNfse>".$result['NSEVALORLIQUIDO']."</ValorLiquidoNfse>";
			$vSNFSe .= "</Valores>";
			$vSNFSe .= "<ItemListaServico>".filterNumber($result['SERITEM'])."</ItemListaServico>";
			$vSNFSe .= "<CodigoCnae>".$aConfig['cnae']."</CodigoCnae>";
			$vSNFSe .= "<Discriminacao>".$vSServico."</Discriminacao>";
			$vSNFSe .= "<CodigoMunicipio>".$aConfig['idCidade']."</CodigoMunicipio>";
			$vSNFSe .= "</Servico>";
			$vSNFSe .= "<Prestador>";
			$vSNFSe .= "<Cnpj>".$aConfig['cnpj']."</Cnpj>";
			$vSNFSe .= "<InscricaoMunicipal>".$aConfig['insc_municipal']."</InscricaoMunicipal>";
			$vSNFSe .= "</Prestador>";
			$vSNFSe .= "<Tomador>";
			$vSNFSe .= "<IdentificacaoTomador>";
			$vSNFSe .= "<CpfCnpj>";
				if ($result['CLITIPOCLIENTE'] == 'J')
					$vSNFSe .= "					<Cnpj>".filterNumber($result['CNPJCPF'])."</Cnpj>";
				else
					$vSNFSe .= "					<Cpf>".filterNumber($result['CNPJCPF'])."</Cpf>";
			$vSNFSe .= "<CpfCnpj>";
			if (($result['CLIISENTAIM'] != 'S') && ($result['CLIIM'] != ''))
				$vSNFSe .= "<InscricaoMunicipal>".$result["CLIIM"]."</InscricaoMunicipal>";
			//$vSNFSe .= "<Cpf>".filterNumber($result['CNPJCPF'])."</Cpf>";
			$vSNFSe .= "</CpfCnpj>";
			$vSNFSe .= "</IdentificacaoTomador>";
			
			$vSCLIRAZAOSOCIAL = str_replace('&', '&amp;', $result["CLIRAZAOSOCIAL"]);
			$vSNFSe .= "<RazaoSocial>".substr($vSCLIRAZAOSOCIAL,0,113)."</RazaoSocial>";
			$vSNFSe .= "<Endereco>";
			$vSNFSe .= "<Endereco>".$result["LOGRADOURO"]."</Endereco>";
			$vSNFSe .= "<Numero>".$result["ENDNROLOGRADOURO"]."</Numero>";
			$vSNFSe .= "<Complemento>".$result["ENDCOMPLEMENTO"]."</Complemento>";
			$vSNFSe .= "<Bairro>".$result["ENDBAIRRO"]."</Bairro>";
			$vSNFSe .= "<CodigoMunicipio>".$result["CIDCODIGO"]."</CodigoMunicipio>";
			$vSNFSe .= "<Uf>".$result["ESTSIGLA"]."</Uf>";
			$vSNFSe .= "<Cep>".filterNumber($result["ENDCEP"])."</Cep>";
			$vSNFSe .= "</Endereco>";
			$vSNFSe .= "<Contato>";
			$vSNFSe .= "<Telefone>".filterNumber($result["CLIFONE"])."</Telefone>";
			$vSNFSe .= "<Email>".$result["CLIEMAILENVIONFE"]."</Email>";
			$vSNFSe .= "</Contato>";
			$vSNFSe .= "</Tomador>";
			$vSNFSe .= "</InfRps>";
			$vSNFSe .= "</Rps>";
			$vSNFSe .= "</ListaRps>";
			$vSNFSe .= "</LoteRps>";
			$vSNFSe .= "</EnviarLoteRpsEnvio>";
			$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/NFSe_".$vSNumeroNF.".xml";
			$fp = fopen($vSNomeArquivo, "w");
			$escreve = fwrite($fp, $vSNFSe);
			fclose($fp);
		}
		/*$pSCaminhoDestino = "/NFSe/ENTRADA/NFS_1_.xml";
		$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'upload');
		$pSCaminhoDestino = "/NFSe/SAIDA/NFS_1_.err";
		$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/NFS_1.xml";
		$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'download');  */
		$vSNomeArquivo = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/NFS_1.xml";
		$vSErro = verificarArquivoRetorno($vSNomeArquivo);
	} catch (Exception $e) {
	  $vSErro = "Erro ao gravar arquivo XML NFSe: ".  $e->getMessage(). "\n";
	}
	return $vSErro;
}

/**
 *  @brief Brief
 *
 *  @param [in] $pINFSCODIGO Código da Cobrança
 *  @param [in] $pSNFSNRORECEITA Número da NFSe da Prefeitura
 *  @return mensagem de erro se acontecer
 *
 *  @details Details
 */
function cancelarNFSe($pINFSCODIGO, $pSNFSNRORECEITA, $pIEMPCODIGO, $pINSENUMERORPS) {
	$vSErro = '';
	$aConfig = buscarDadosConfiguracaoNFSe($pIEMPCODIGO);
	// Gerar xml cancelamento
	list($vIAno, $vSNro) = explode('/', $pSNFSNRORECEITA);
	$vSCOBNRORECEITA = $vIAno. adicionarCaracterLeft($vSNro, 11, 0);
	$vSXML = "<?xml version=\"1.0\"?>";
	$vSXML .= "<CancelarNfseEnvio xmlns=\"http://www.abrasf.org.br/nfse.xsd\">";
	$vSXML .= "	<Pedido>";
	$vSXML .= "		<InfPedidoCancelamento Id=\"Cnfs\">";
	$vSXML .= "			<IdentificacaoNfse>";
	$vSXML .= "				<Numero>$vSCOBNRORECEITA</Numero>";
	$vSXML .= "				<Cnpj>".$aConfig['cnpj']."</Cnpj>";
	$vSXML .= "				<InscricaoMunicipal>".$aConfig['insc_municipal']."</InscricaoMunicipal>";
	$vSXML .= "				<CodigoMunicipio>4314902</CodigoMunicipio>";
	$vSXML .= "			</IdentificacaoNfse>";
	$vSXML .= "			<CodigoCancelamento>2</CodigoCancelamento>";
	$vSXML .= "		</InfPedidoCancelamento>";
	$vSXML .= "	</Pedido>";
	$vSXML .= "	<Signature/>";
	$vSXML .= "</CancelarNfseEnvio>";

	$vSNomeArquivo = "../nfse/".$pIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/temp/CNFS_".$pINSENUMERORPS.".xml";
	$fp = fopen($vSNomeArquivo, "w");
	$escreve = fwrite($fp, $vSXML);
	fclose($fp);

	//mandar para outro servidor SafeWeb
	$pSCaminhoDestino = "/".$pIEMPCODIGO."/NFSe/ENTRADA/CNFS_".$pINSENUMERORPS.".xml";
	$vSErro = ftpArquivo($vSNomeArquivo, $pSCaminhoDestino, 'upload');
	sleep(10);

	// verificar retorno
	$vSCaminhoRetornoXML = "../nfse/".$pIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/retorno/CNFS_".$pINSENUMERORPS.".xml";
	$pSCaminhoSafeWebXML = "/".$pIEMPCODIGO."/NFSe/BACKUP/CNFS_".$pINSENUMERORPS.".xml";

	$vSErro = ftpArquivo($vSCaminhoRetornoXML, $pSCaminhoSafeWebXML, 'download');
	$vSErro = verificarRetornoCancelamentoSafeWeb($vSCaminhoRetornoXML);
	$vConexao = sql_conectar_banco();
	if ($vSErro != '') {
		$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSESTATUSRECEITA = 'D', NSEERRORECEITA = '".$vSErro."'
					where NSECODIGO = ".$pINFSCODIGO;
		$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);
	} else {
		$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSESTATUSRECEITA = 'C', NSEERRORECEITA = '".$vSErro."', NSEEMAILENVIADO = 'N'
					where NSECODIGO = ".$pINFSCODIGO;
		$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);
		$vSDestinoXML = "../nfse/".$pIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/enviadas/xml/CNFS_".$pINSENUMERORPS.".xml";
		copy($vSCaminhoRetornoXML, $vSDestinoXML);
	}
	return $vSErro;
}

function verificarRetornoCancelamentoSafeWeb($arquivoXML) {
	$vSErro = '';
	try {
		// ler XML
		if(file_exists($arquivoXML)) {

			$xml = simplexml_load_file($arquivoXML);
			// imprime os atributos do objeto criado

			if (empty($xml->ListaMensagemRetorno->MensagemRetorno->Mensagem)){ //nao teve erro

				if (empty($xml->RetCancelamento->NfseCancelamento->Confirmacao->Pedido->InfPedidoCancelamento->IdentificacaoNfse->Numero))
					$vSErro = "<h4>Arquivo sem dados de autorização!</h4>";
				//else
					//$vSErro = $xml->RetCancelamento->NfseCancelamento->Confirmacao->Pedido->InfPedidoCancelamento->IdentificacaoNfse->Numero;
				return $vSErro;
			} else
				$vSErro = $xml->ListaMensagemRetorno->MensagemRetorno->Mensagem;
		}
	} catch (Exception $e) {
		$vSErro = "Erro ao gravar dados no banco de dados: ".  $e->getMessage(). "\n";
	}
	return $vSErro;
}

function gerarNFSeXMLCancelamento($chNFe, $cOrgaoUF, $tpAmb, $cnpj, $nProt, $xJust) {
	$tpEvento = '110111';
	$nSeqEvento = '1';
	$descEvento = 'Cancelamento';
	$dhEvento = date('Y-m-d').'T'.date('H:i:s').'-03:00';
	if (strlen(trim($nSeqEvento))==1){
		$zenSeqEvento = str_pad($nSeqEvento, 2, "0", STR_PAD_LEFT);
	} else {
		$zenSeqEvento = trim($nSeqEvento);
	}
	$id = "ID".$tpEvento.$chNFe.$zenSeqEvento;
	//monta mensagem
	$vSXML = "<CancelarNfseEnvio xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\">";
	$vSXML .= "<Pedido>";
	$vSXML .= "<InfPedidoCancelamento>";
	$vSXML .= "<IdentificacaoNfse>";
	$vSXML .= "<Numero>$chNFe</Numero>";
	$vSXML .= "<Cnpj>$cnpj</Cnpj>";
	$vSXML .= "<InscricaoMunicipal>0</InscricaoMunicipal>";
	$vSXML .= "<CodigoMunicipio>4303103</CodigoMunicipio>";
	$vSXML .= "</IdentificacaoNfse>";
	$vSXML .= "<CodigoCancelamento>E506</CodigoCancelamento>";
	$vSXML .= "</InfPedidoCancelamento>";
	$vSXML .= "</Pedido>";
	$vSXML .= "<Signature />";
	$vSXML .= "</CancelarNfseEnvio>";

	$vSNomeArquivo = "../nfse/40/homologacao/canceladas/1.xml";
	$fp = fopen($vSNomeArquivo, "w");
	$escreve = fwrite($fp, $vSXML);

	fclose($fp);
}

function verificarArquivoRetorno($pSArquivo) {
	$pSArquivo = "../nfse/1/homologacao/retorno/NFS_1.xml";
	$dom = new DOMDocument('1.0', 'utf-8');
	$dom->preservWhiteSpace = false; //elimina espaços em branco
	$dom->formatOutput = false;
	$dom->loadXML($pSArquivo,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
	//$dom->loadXML($pSArquivo,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

	echo("<pre>");
		print_r($dom);
		echo("</pre>");
	/*
	$fp = fopen($pSArquivo, "r");
	if ($fp == false) die('O arquivo não existe.');
	while (!feof($fp)) {
		$linha = fgets($fp, 4096);
		echo $linha."<br>";
	} */
	//$vetorArq = file("../BanrisulICMS/".$nomeArquivo);
	/*
	for($i=0; $i<sizeof($fp); $i++){
			$linha=explode(' ',strstr($vetorArq[$i],"DPMTR"));
			//print_r($linha);
			$tam = sizeof($linha);
			$codCobranca=$linha[7];
				//echo $codCobranca."\n";
			if(!empty($codCobranca)){
				@$codigos .= ",".$linha[7] ; //usado na consulta pra pegar os codigos dos clientes
				for($x=13; $x<$tam ; $x++){//começa em 12 por o 11 é a data; pega o valor
					if(!empty($linha[$x])){
						$valor=$linha[$x];
						$x=$tam;//sai do laço
					}
				}
				$escreve = fwrite($fp, "".$codCobranca.'|');
				$escreve = fwrite($fp, str_replace("\n",'',$valor)."\n");
				$auxCod = (int) $codCobranca;
				$vetorValor[$auxCod]= ($valor);
			}

	} */
	//echo
	//fclose($fp);


	//$xml = simplexml_load_file($pSArquivo);
	//echo $xml->Message;
}

/**
 *  @brief Brief
 *
 *  @param [in] $pINFSCODIGO Código da Cobrança a ser atualizada
 *  @param [in] $arquivoXML Arquivo retorno de sucesso da SafeWeb pasta no servidor
 *  @return Retorna sucesso ou erro
 *
 *  @details Details
 */
function gravarNFSeRetornoReceita($pINSECODIGO, $arquivoXML) {
	$vSErro = '';
	try {
		// ler XML
		if(file_exists($arquivoXML)) {

			$xml = simplexml_load_file($arquivoXML);

			// imprime os atributos do objeto criado
			//if (empty($xml->ListaNfse->CompNfse->Nfse->InfNfse->Numero)){
			if (empty($xml->Nfse->InfNfse->Numero)){
				$vSErro = "<h4>Aqui Arquivo sem dados de autorização!</h4>";
				return $vSErro;
			}

			/*
			$vSNroNFSe = $xml->ListaNfse->CompNfse->Nfse->InfNfse->Numero;
			$vSCodigoVerificacao = $xml->ListaNfse->CompNfse->Nfse->InfNfse->CodigoVerificacao;
			$vSDataEmissao = $xml->ListaNfse->CompNfse->Nfse->InfNfse->DataEmissao;
			*/
			$vSNroNFSe = $xml->Nfse->InfNfse->Numero;
			$vSCodigoVerificacao = $xml->Nfse->InfNfse->CodigoVerificacao;
			$vSDataEmissao = $xml->Nfse->InfNfse->DataEmissao;
			list($vDDataEmissao, $vDHoraEmissao) = explode('T', $vSDataEmissao);

			$vSDataEmissaoNova = $vDDataEmissao.' '.$vDHoraEmissao;
			$vIAno = substr($vSNroNFSe, 0, 4);
			$vINro = substr($vSNroNFSe, 4, 11);
			$vINro = (int) $vINro;
			$vSNroNFSe = $vIAno.'/'.$vINro;
			$vConexao = sql_conectar_banco();
			$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSESTATUSRECEITA = 'A',
														 NSEERRORECEITA = null,
														 NSENUMERO = '".$vSNroNFSe."',
														 NSEDATAEMISSAO = '".$vSDataEmissaoNova."',
														 NSENRRECIBOENVIO = '".$vSCodigoVerificacao."'
							where NSECODIGO = ".$pINSECODIGO;
			$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);

			$SqlUpdate = "Update CONTASARECEBER set CTRNRODOCUMENTO = '".$vSNroNFSe."', CTRDATAEMISSAODOCUMENTO = '".$vSDataEmissaoNova."'
						Where NSECODIGO = ".$pINSECODIGO;
			$RS_MAINUpdate = sql_executa(vGBancoSite, $vConexao,$SqlUpdate);

		}
	} catch (Exception $e) {
		$vSErro = "Erro ao gravar dados no banco de dados: ".  $e->getMessage(). "\n";
	}
	return $vSErro;
}

/**
 *  @brief Brief
 *
 *  @param [in] $pSCaminho Caminho onde encontra-se o arquivo de retorno do erro no servidor
 *  @return retorna uma mensagem se tiver erro com a descrição conforme SafeWeb
 *
 *  @details Details
 */
function verificarRetornoSafeWeb($pSCaminho) {
	$vetorArq = file($pSCaminho);
	$vSMsgRetorno = '';
	for($i=0; $i<sizeof($vetorArq); $i++){
		$linha = explode('\n', $vetorArq[$i]);
		$tam = sizeof($linha);
		$vLinha = '';
		for($j=0; $j<$tam; $j++){
			$vLinha .= $linha[$j];
		}
		if(preg_match("<Mensagem>", $vLinha)) {
			list($vSMsg1, $vSMsg2) = explode('<Mensagem>', $vLinha);
			if ($vSMsgRetorno == '')
				$vSMsgRetorno = $vSMsg2;

		}
	}
	return $vSMsgRetorno;
}

/**
 *  @brief Funcao para enviar e-mail da NFSe conforme configurações definidas na tabela CONFIGURACOESCOMERCIAL
 *
 *  @param [in] $pSNFe Objeto da NFe criado no arquivo ToolsNFePHP.class.php
 *  @param [in] $pSArquivoXML Caminho do XML criado
 *  @param [in] $pDDataRecibo Parameter_Description
 *  @param [in] $pSMotivo Parameter_Description
 *  @param [in] $pSNumeroNF Parameter_Description
 *  @return Variável de erro, se retornar vazio não tem erro no envio do e-mail
 *
 *  @details Details
 */
function enviarEmailNFSe($pLNSECODIGO, $pSTipo = 'E') {
	// buscar dados da NFe
	$vConexao = sql_conectar_banco();
	$Sql = 'SELECT
			nf.NSECODIGO,
			nf.NSENUMERO,
			nf.EMPCODIGO,
			nf.NSEVALORLIQUIDO,
			cl.CLIRAZAOSOCIAL,
			nf.NSENUMERORPS,
			EMP.EMPRAZAOSOCIAL,
			cl.CLIEMAILENVIONFE
			FROM
				NOTASFISCAISSERVICO nf
			LEFT JOIN
				CLIENTES cl
			ON
				nf.CLICODIGO = cl.CLICODIGO
			LEFT JOIN
				EMPRESAUSUARIA EMP
			ON
				EMP.EMPCODIGO = nf.EMPCODIGO
			WHERE nf.NSECODIGO in ('.$pLNSECODIGO.')';
	$Sql = stripcslashes($Sql);
	$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql, false);
	$vSErro = '';
	while($result = sql_retorno_lista($RS_POST)){
		$vSNSENUMERORPS = $result["NSENUMERORPS"];
		$vSNSENUMERONFSE = $result["NSENUMERO"];
		$vSEMPRAZAOSOCIAL = $result["EMPRAZAOSOCIAL"];
		$vSCLIRAZAOSOCIAL = $result["CLIRAZAOSOCIAL"];
		$vIEMPCODIGO = $result["EMPCODIGO"];
		$vINSECODIGO = $result["NSECODIGO"];

		$vSNFSVALORTOTALNF = formatar_moeda($result["NSEVALORLIQUIDO"], false);

		$phpmailer_diretorio_base = "../twcore/vendors/phpmailer/";
		include_once( $phpmailer_diretorio_base . "PHPMailerAutoload.php" );

		$mail = new PHPMailer;
		$mail->setLanguage('br', $phpmailer_diretorio_base . 'language/');
		$mail->isSMTP();
		// buscar configuracao da Nfe
		if ($aConfig == '')	$aConfig = buscarDadosConfiguracaoNFSe($vIEMPCODIGO);

		//print_r($aConfig);
		$mail->Host = $aConfig['mailHOST'];
		$mail->SMTPAuth = true;
		$mail->Port = 587;
		$mail->Username = $aConfig['mailUSER'];
		$mail->Password = $aConfig['mailPASS'];
		$mail->SMTPSecure = 'ssl';
		$mail->From = $aConfig['mailFROMmail'];
		$mail->FromName = $aConfig['mailFROMname'];
		$vSCLIEMAILNFSE = '';
		if ($result["CLIEMAILENVIONFE"] != '') {
			$vSCLIEMAILNFSE = $result['CLIEMAILENVIONFE'];
			if ($vSCLIEMAILNFSE != '') {
				$vLCLIEMAILNFSE = explode(';', $vSCLIEMAILNFSE);
				for($i=0; $i<count($vLCLIEMAILNFSE); $i++){
					if ($i==0)
						$mail->addAddress($vLCLIEMAILNFSE[$i], utf8_decode($vSCLIRAZAOSOCIAL) );
					else
						$mail->addCC($vLCLIEMAILNFSE[$i], utf8_decode($vSCLIRAZAOSOCIAL));
				}
			}
		}
		//$mail->addAddress( 'contato@teraware.com.br', 'Contato' );
		// Copia
		if ($aConfig['mailREPLYTOmail'] != '')
			$mail->addCC( $aConfig['mailREPLYTOmail'], $aConfig['mailREPLYTOname'] );

		// anexos Danfe e xml
		$vSCaminhoPDF = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/enviadas/pdf/NFS_".$vSNSENUMERORPS.".pdf";
		$vSCaminhoXML = "../nfse/".$vIEMPCODIGO."/". $aConfig['pasta_ambiente'] ."/enviadas/xml/NFS_".$vSNSENUMERORPS.".xml";
		
		// gerar boleto senão existir
		/*
		if ($vICOBFORMAPAGAMENTO == 116 && $pSBoleto == 'S'){ // boleto			
			$vSCaminhoBoleto = "../arquivos/boletos/".$vIEMPCODIGO."/Boleto_".$vSNSENUMERORPS.".pdf";
			$_GET['vIConta'] = ;
			$_GET["vLOidList"] =;
			$_GET["envEmail"] = 'S';
			include('../boletos/341/boletonovo.php');						
		}
		*/
		
		/*  Marpa
		$vSCaminhoBoleto = "../arquivos/boletos/".$vIEMPCODIGO."/Boleto_516_1.pdf";
		$mail->addAttachment($vSCaminhoBoleto, $vSNSENUMERORPS.' - Boleto 1');
		$vSCaminhoBoleto2 = "../arquivos/boletos/".$vIEMPCODIGO."/Boleto_516_2.pdf";
		$mail->addAttachment($vSCaminhoBoleto2, $vSNSENUMERORPS.' - Boleto 2');
		$vSCaminhoBoleto3 = "../arquivos/boletos/".$vIEMPCODIGO."/Boleto_516_3.pdf";
		$mail->addAttachment($vSCaminhoBoleto3, $vSNSENUMERORPS.' - Boleto 3');

		$mail->addAttachment($vSCaminhoPDF, $vSNSENUMERORPS.' - NFS-e (PDF)', 'base64', 'application/pdf' );
		$mail->addAttachment($vSCaminhoXML, $vSNSENUMERORPS.' - XML', 'base64', 'xml');
		//if (file_exists($vSCaminhoBoleto))
			//$mail->addAttachment($vSCaminhoBoleto, $vSNSENUMERORPS.' - Boleto');
			
		*/
		
		$vSCaminhoBoleto = "../arquivos/boletos/".$vIEMPCODIGO."/Boleto_".$vSNSENUMERORPS.".pdf";

		$mail->addAttachment($vSCaminhoPDF, $vSNSENUMERORPS.' - NFS-e (PDF)', 'base64', 'application/pdf' );
		$mail->addAttachment($vSCaminhoXML, $vSNSENUMERORPS.' - XML', 'base64', 'xml');
		if (file_exists($vSCaminhoBoleto))
			$mail->addAttachment($vSCaminhoBoleto, $vSNSENUMERORPS.' - Boleto');

		$mail->isHTML(true);

		if ($pSTipo == 'E') {
			$Assunto = "NFS-e Nota Fiscal Serviço Eletrônica: $vSNSENUMERONFSE - $vSEMPRAZAOSOCIAL";

			$Mensagem =  "<p><b>Prezada(o) $vSCLIRAZAOSOCIAL,</b></p>";
			$Mensagem .= "<p>Você está recebendo a Nota Fiscal de Serviço Eletrônica número $vSNSENUMERONFSE de";
			$Mensagem .= " $vSEMPRAZAOSOCIAL, no valor de R$ $vSNFSVALORTOTALNF.";
			$Mensagem .= "<p><i>Podemos conceituar a Nota Fiscal de Serviço Eletrônica como um documento de existência apenas";
			$Mensagem .= " digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins";
			$Mensagem .= " fiscais, ocorrida entre as partes.";
			$Mensagem .= " Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria";
			$Mensagem .= " e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da";
			$Mensagem .= " ocorrência do Fato Gerador.</i></p>";
			$Mensagem .= "<p><i>Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo";
			$Mensagem .= " da NFS-e, anexo neste e-mail, ou utilizando o RPS, que representa graficamente a Nota";
			$Mensagem .= " Fiscal de Serviço Eletrônica. A validade e autenticidade deste documento eletrônico pode ser";
			$Mensagem .= " verificada no site do projeto (http://notalegal.portoalegre.rs.gov.br/), através do código de verificação";
			$Mensagem .= " contida no RPS.</i></p>";
			$Mensagem .= "<p><i>Para poder utilizar os dados descritos do RPS na escrituração da NFS-e, tanto o";
			$Mensagem .= " contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da";
			$Mensagem .= " NFS-e. Esta validade está vinculada à efetiva existência da NFS-e nos arquivos da SEFAZ,";
			$Mensagem .= " e comprovada através da emissão da Autorização de Uso.</i></p>";
			$Mensagem .= "<p><b>O RPS não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas";
			$Mensagem .= " como instrumento auxiliar para consulta da NFS-e no Ambiente Nacional.</b></p>";
			$Mensagem .= "<p>Para mais detalhes sobre o projeto, consulte: ";
			$Mensagem .= "<a href='http://notalegal.portoalegre.rs.gov.br/'>http://notalegal.portoalegre.rs.gov.br/</a></p>";
			$Mensagem .= "<br /><p>Atenciosamente,<p>$vSEMPRAZAOSOCIAL</p>";

		} else if ($pSTipo == 'C') {
			$Assunto = "CANCELAMENTO de NFS-e Nota Fiscal Serviço Eletrônica: $vSNSENUMERONFSE - $vSEMPRAZAOSOCIAL";

			$Mensagem =  "<p><b>Prezada(o) $vSCLIRAZAOSOCIAL,</b></p>";
			$Mensagem .= "<p>Você está recebendo a notificação de Cancelamento";
			$Mensagem .= " da Nota Fiscal Serviço Eletrônica número $vSNSENUMERORPS, de $vSEMPRAZAOSOCIAL</p>";
			$Mensagem .= "<p><i>Podemos conceituar a Nota Fiscal Serviço Eletrônica como um documento de existência";
			$Mensagem .= " apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar,";
			$Mensagem .= " para fins fiscais, ocorrida entre as partes.";
			$Mensagem .= " Sua validade jurídica é garantida pela assinatura digital do remetente";
			$Mensagem .= " (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico,";
			$Mensagem .= " antes da ocorrência do Fato Gerador.</i></p>";
			$Mensagem .= "<p><i>Os registros fiscais e contábeis devem ser feitos, a partir do próprio";
			$Mensagem .= " arquivo da NFS-e e do Cancelamento, anexo neste e-mail. A validade e autenticidade";
			$Mensagem .= " deste documento eletrônico pode ser verificada no site do projeto";
			$Mensagem .= " (http://notalegal.portoalegre.rs.gov.br/), através da chave de acesso contida no RPS.</i></p>";
			$Mensagem .= "<p><i></i></p>";
			$Mensagem .= "<p><b></b></p>";
			$Mensagem .= "<p>Para mais detalhes sobre o projeto, consulte: ";
			$Mensagem .= "<a href='http://notalegal.portoalegre.rs.gov.br/'>http://notalegal.portoalegre.rs.gov.br/</a></p>";
			$Mensagem .= "<br /><p>Atenciosamente,<p>$vSEMPRAZAOSOCIAL</p>";
		}
		$mail->Subject = utf8_decode($Assunto);
		$mail->Body    = utf8_decode($Mensagem);
		if ($vSCLIEMAILNFSE	!= '') {
			if(!$mail->send()) {
			   $vSErro = 'S';
			   echo 'Erro no envio do e-mail. Entre em contato com o administrador do sistema.';
			   echo 'Erro: ' . $mail->ErrorInfo;
			   exit;
			} else {
				$SqlUpdate = "UPDATE NOTASFISCAISSERVICO set NSEEMAILENVIADO = 'S'
								where NSECODIGO = ".$vINSECODIGO;
				$RS_Update = sql_executa(vGBancoSite, $vConexao, $SqlUpdate);
				//echo 'E-mail enviado com sucesso.';
			}
		}
	}
	if ($vSErro == '')
		echo 'E-mail enviado com sucesso.';
}

function downloadArquivosNFSe($pLNFSCODIGO) {
	$vSErro = '';	
	try {
		$vConexao = sql_conectar_banco();
		$Sql = 'SELECT
				nf.NSECODIGO,
				nf.NSENUMERORPS,
				nf.EMPCODIGO		
				FROM
					NOTASFISCAISSERVICO nf				
				WHERE nf.NSECODIGO in ('.$pLNFSCODIGO.')';
		$Sql = stripcslashes($Sql);
		$RS_POST = sql_executa(vGBancoSite, $vConexao, $Sql, false);
		$total_arquivos = sql_record_count($RS_POST);	
		$vILote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,5);
		$vSNomeArquivoZip = 'NFSe_'.$vILote.'.zip';
		$diretorio_destino = '../nfe/zip/'.$vSNomeArquivoZip;
		$zip = new ZipArchive();
		$opened = $zip->open( $diretorio_destino , ZipArchive::CREATE );

		if( $opened === true ){

			while($result = sql_retorno_lista($RS_POST)){		
		
				$vSNumeroNF = $result["NSENUMERORPS"]; //500;
				$vIEMPCODIGO = $result["EMPCODIGO"];
				$pAConfig = buscarDadosConfiguracaoNFSe($vIEMPCODIGO);
			
				$vSCaminhoRetornoPDF = "../nfse/".$vIEMPCODIGO."/". $pAConfig['pasta_ambiente'] ."/enviadas/pdf/NFS_".$vSNumeroNF.".pdf";
				$vSCaminhoRetornoXML = "../nfse/".$vIEMPCODIGO."/". $pAConfig['pasta_ambiente'] ."/enviadas/xml/NFS_".$vSNumeroNF.".xml";
				// Carta Correcao
				$vSCaminhoRetornoXMLCC = "../nfse/".$vIEMPCODIGO."/". $pAConfig['pasta_ambiente'] ."/enviadas/xml/CC_".$vSNumeroNF.".xml";
				// Cancelada
				$vSCaminhoRetornoXMLCNF = "../nfse/".$vIEMPCODIGO."/". $pAConfig['pasta_ambiente'] ."/enviadas/xml/CNFS_".$vSNumeroNF.".xml";
				//echo $vSCaminhoRetornoPDF;
				if( file_exists($vSCaminhoRetornoPDF) ){
					$vSNomeArquivo = 'NFS_'.$vSNumeroNF.'.pdf';
					$zip->addFile($vSCaminhoRetornoPDF, $vSNomeArquivo );
				}
				
				if( file_exists($vSCaminhoRetornoXML) ){
					$vSNomeArquivo = 'NFS_'.$vSNumeroNF.'.xml';
					$zip->addFile($vSCaminhoRetornoXML, $vSNomeArquivo );
				}
				
				if( file_exists($vSCaminhoRetornoXMLCNF) ){
					$vSNomeArquivo = 'CNFS_'.$vSNumeroNF.'.xml';
					$zip->addFile($vSCaminhoRetornoXMLCNF, $vSNomeArquivo );
				}
			}
			$zip->close();
			$opened = null;
		}	
		return $vSNomeArquivoZip;
	} catch (Exception $e) {
	  $vSErro = "Erro ao gravar arquivo(s) download(s) da(s) NFSe(s): ".  $e->getMessage(). "\n";
	}
	return $vSErro;
}

?>