<?php
	require_once '../transaction/transactionContasaReceber.php';
	require_once 'NFSe.php';

	$response = [
		'success' => false,
		'msg'     => 'Não foi informada a conta',
	];

	if (isset($_GET['ctrcodigo']) && is_numeric($_GET['ctrcodigo'])) {
		$contaReceber = fill_ContasaReceber($_GET['ctrcodigo']);
		$nota = new NFSe($contaReceber['CTRCODIGO']);

	    $nota->quantidade_rps = 1;
	    $nota->discriminacao  = $contaReceber['CTRDESCRICAO'];
	    $nota->valor 		  = $contaReceber['CTRVALORARECEBER'];

	    $nota->empresa = new Empresa($contaReceber['EMPCODIGO']);
	    $nota->tomador = new Tomador($contaReceber['CLICODIGO']);

	    $nota->calculate();

	    if ($nota->build()) {
	    	if ($nota->sign()) {
	    		if ($nota->send()) {
	    			$response['msg']     = 'Nota Transmitida com sucesso';
	    			$response['success'] = true;
	    		} else {
	    			$response['msg'] = 'Ocorreu um erro na transmissão da nota à prefeitura';
	    		}
	    	} else {
	    		$response['msg'] = 'Ocorreu um erro ao assinar digitalmente a nota';
	    	}
	    } else {
	    	$response['msg'] = 'Ocorreu um erro na construção do arquivo XML da nota';
	    }
	}

	if ($response['success']) {
		$nota->check();
	}

	echo $response['msg'];