<?php
	include_once '../twcore/teraware/php/constantes.php';
	require_once '../cadastro/transaction/transactionCidades.php';

	$cep      = filterNumber($_GET['cep']);
	$chaveAPI = cSChaveCEP;
	$retorno = file_get_contents("https://webservice.uni5.net/web_cep.php?auth=[$chaveAPI]&formato=json&cep={$cep}");
	$retorno = json_decode($retorno, true);
	$tipoLogradouro = $retorno['tipo_logradouro'];
	$logradouro = $retorno['logradouro'];
	$bairro     = $retorno['bairro'];
	$cidade     = $retorno['cidade'];
	$uf         = $retorno['uf'];
	$ufid       = getIdUf($uf);
	echo json_encode(array(
						'logradouro'   => trim($logradouro),
						'tipologradouro' => $tipoLogradouro,
						'bairro'       => trim($bairro),
						'cidadeCodigo' => getIdCidade($cidade, $ufid),
						'estadoCodigo' => $ufid,
						'cidade'       => $cidade,
						'uf'           => $uf
					));