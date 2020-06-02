<?php
	require_once '../transaction/transactionContasaReceber.php';
	require_once 'NFSe.php';

	$notas = consultaComposta(array(
		'query' => 'SELECT
						*
					FROM
						NOTASFISCAIS n
						LEFT JOIN CONTASARECEBER c ON c.CTRCODIGO = n.CTRCODIGO
					WHERE
						NFSSTATUS = \'S\' AND
						CTRSTATUS = \'S\''
	));

	foreach ($notas['dados'] as $nota) {
		$nfse = new NFSe($nota['CTRCODIGO']);
    	$nfse->empresa = new Empresa($nota['EMPCODIGO']);
    	$nfse->check();
	}