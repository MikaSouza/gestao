<?php
    include('../include/constantes.php');
    include("../twcore/vendors/mpdf/mpdf.php");

    if(isset($_GET["method"]) && ($_GET["method"] == "imprimir") && ($_GET["pINFSCODIGO"] != "")){
        $vANFSCODIGO = explode (',', $_GET['pINFSCODIGO']);

		//mode, format, default_font_size, default_font, margin_left, margin_right, margin_top, margin_bottom, margin_header, margin_footer
		$mpdf = new mPDF("c", "A4", "", "", 5, 5, 5, 5, 10, 10);
		
		$mpdf->AddPage();
		$mpdf->WriteHTML("<div width='100%' height='1px' ></div>");
	
		$i = 1;
		foreach($vANFSCODIGO as $key => $vINFSCODIGO){
			$vSSql = " SELECT
						nf.NFSDATAEMISSAO,
						nf.NFSNUMERO,
						c.CLINOME,
						emp.EMPNOMEFANTASIA,
						n . *,
						(CASE WHEN n.PROCODIGO = 0 OR n.PROCODIGO IS NULL THEN
							n.NXPCODIGODEV
						ELSE
                        (SELECT p.PROCODIGOAUX FROM PRODUTOS p WHERE p.PROCODIGO = n.PROCODIGO)
						END) as CODIGOBARRA
                    FROM
						NOTASXPRODUTOS n,
						NOTASFISCAIS nf
						LEFT JOIN CLIENTES c ON c.CLICODIGO = nf.CLICODIGO
						LEFT JOIN EMPRESAUSUARIA emp ON emp.EMPCODIGO = nf.EMPCODIGO
                    WHERE
                        n.NXPSTATUS = 'S' AND
						n.NFSCODIGO = ".$vINFSCODIGO." AND
                        nf.NFSCODIGO = ".$vINFSCODIGO."
                    ORDER BY
                        n.NXPDATA_INC";
						
			//echo ($vSSql."<br /><br />");die;
			$vSSql = stripcslashes($vSSql);
			$vConexao = sql_conectar_banco();
			$vAResultDB = sql_executa(vGBancoSite, $vConexao, $vSSql, false);
			
			while($vRRetorno = sql_retorno_lista($vAResultDB)){
				if($i == 5){
					$mpdf->AddPage();
					$i = 1;
				}
				
				$vIXinicio = 8;
				$vIXSequencia = 63;
				$vIXQuantidade = 57;
				$vIXQuantidadeDado = 78;
				$vIXPedido = 45;
				$vIXData = 76;
				$vIXCodigo = 53;
				
				$vIL = strlen($vRRetorno['CODIGOBARRA']);
				
				$vIXCodigo -= $vIL/2;
				
				$vIYFornLabel = 11;
				$vIYFornDado = 18;
				$vIYClienteLabel = 31;
				$vIYClienteDado = 39;
				$vIYSequeLabel = 31;
				$vIYSequeDado = 39;
				$vIYOrdemLabel = 50;
				$vIYOrdemDado = 56;
				$vIYQuantidadeLabel = 50;
				$vIYQuantidadeDado = 50;
				$vIYNotaLabel = 70;
				$vIYNotaDado = 77;
				$vIYPedidoLabel = 70;
				$vIYPedidoDado = 77;
				$vIYDataLabel = 70;
				$vIYDataDado = 77;
				$vIYCodigoLabel = 91;
				$vIYCodigoDado = 131;
				$vIMarginTop = 400;
				$vIMarginLeft = 60;
				$vIMarginTopQua = 200;
				$vIMarginLeftQua = 205;
				
				$vIXRight = 100;
				$vIYBottom = 135;
				
				if($i % 2 == 0){
					$vIXinicio += $vIXRight;
					$vIXSequencia += $vIXRight;
					$vIXQuantidade += $vIXRight;
					$vIXQuantidadeDado += $vIXRight;
					$vIXPedido += $vIXRight;
					$vIXData += $vIXRight;
					$vIXCodigo += $vIXRight;
					$vIMarginLeft = 435;
					$vIMarginLeftQua = 581;
				}
				if(($i % 3 == 0) || ($i % 4 == 0)){
					$vIYFornLabel += $vIYBottom;
					$vIYFornDado += $vIYBottom;
					$vIYClienteLabel += $vIYBottom;
					$vIYClienteDado += $vIYBottom;
					$vIYSequeLabel += $vIYBottom;
					$vIYSequeDado += $vIYBottom;
					$vIYOrdemLabel += $vIYBottom;
					$vIYOrdemDado += $vIYBottom;
					$vIYQuantidadeLabel += $vIYBottom;
					$vIYQuantidadeDado += $vIYBottom;
					$vIYNotaLabel += $vIYBottom;
					$vIYNotaDado += $vIYBottom;
					$vIYPedidoLabel += $vIYBottom;
					$vIYPedidoDado += $vIYBottom;
					$vIYDataLabel += $vIYBottom;
					$vIYDataDado += $vIYBottom;
					$vIYCodigoLabel += $vIYBottom;
					$vIYCodigoDado += $vIYBottom;
					$vIMarginTop = 905;
					$vIMarginTopQua = 710;
				}
				
				$vSNomeFornecedor = $vRRetorno['EMPNOMEFANTASIA'];
				$vSNomeCliente = $vRRetorno['CLINOME'];
				
				if(strlen($vSNomeFornecedor) > 49){
					$vSNomeFornecedor = substr($vSNomeFornecedor, 0, 49);
				}
				
				if(strlen($vSNomeCliente) > 30){
					$vSNomeCliente = substr($vSNomeCliente, 0, 30);
				}	
				
				$mpdf->WriteText($vIXinicio, $vIYFornLabel, "Nome do Fornecedor:");
				$mpdf->WriteText($vIXinicio, $vIYFornDado, $vSNomeFornecedor);//49 lenghts
				$mpdf->WriteText($vIXinicio, $vIYClienteLabel, "Nome do Cliente:");
				$mpdf->WriteText($vIXinicio, $vIYClienteDado, $vSNomeCliente);//30
				$mpdf->WriteText($vIXSequencia, $vIYSequeLabel, "Sequencia:");
				$mpdf->WriteText($vIXSequencia, $vIYSequeDado, "");//21
				$mpdf->WriteText($vIXinicio, $vIYOrdemLabel, "Ordem de Compra:");
				$mpdf->WriteText($vIXinicio, $vIYOrdemDado, $_GET['pSOrdemCompra']);//20
				$mpdf->WriteText($vIXQuantidade, $vIYQuantidadeLabel, "Quantidade:");
				$mpdf->WriteText($vIXQuantidadeDado, $vIYQuantidadeDado, $vRRetorno['NXPQTDE']);//24 
				$mpdf->WriteHTML("<div width='200px' height='45px' style='position: absolute; top: ".$vIMarginTopQua."px; left: ".$vIMarginLeftQua."px;'><img width='200px' height='45px' src='../include/barcode.php?codetype=Code39&size=60&text=".$vRRetorno['NXPQTDE']."'/></div>");//$vRRetorno['NXPQTDE']
				$mpdf->WriteText($vIXinicio, $vIYNotaLabel, "Nota Fiscal:");
				$mpdf->WriteText($vIXinicio, $vIYNotaDado, utf8_encode($vRRetorno['NFSNUMERO']));//17
				$mpdf->WriteText($vIXPedido, $vIYPedidoLabel, "Pedido:");
				$mpdf->WriteText($vIXPedido, $vIYPedidoDado, $_GET['pSPedido']);//14
				$mpdf->WriteText($vIXData, $vIYDataLabel, "Data:");
				$mpdf->WriteText($vIXData, $vIYDataDado, formatar_data($vRRetorno['NFSDATAEMISSAO']));
				
				if($vRRetorno['CODIGOBARRA'] != null){
					$mpdf->WriteText($vIXinicio, $vIYCodigoLabel, "Código Peça:");
					$mpdf->WriteHTML("<div width='300px' height='60px' style='position: absolute; top: ".$vIMarginTop."px; left: ".$vIMarginLeft."px;'><img width='300px' height='60px' src='../include/barcode.php?codetype=Code39&size=60&text=".$vRRetorno['CODIGOBARRA']."'/></div>");//$vRRetorno['CODIGOBARRA']
					$mpdf->WriteText($vIXCodigo, $vIYCodigoDado, $vRRetorno['CODIGOBARRA']);
				}else{
					$mpdf->WriteText($vIXinicio+10, $vIYCodigoLabel+25, "A PEÇA NÃO TEM CÓDIGO AUXILIAR!");
				}
			
			$i++;
		   }
			
			sql_fechar_conexao_banco($vConexao);
		}

		$mpdf->Output("arq.pdf", "I");
	}else{
		header("Location: main.php");
	}
	
?>