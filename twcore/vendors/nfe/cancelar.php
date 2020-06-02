<?php
require_once '../transaction/transactionContasaReceber.php';
require_once __DIR__.'/Cancellation.php';

$contaReceber = fill_ContasaReceber($_GET['ctrcodigo']);

$cancellation = new Cancellation($contaReceber['CTRCODIGO']);
$cancellation->empresa = new Empresa($contaReceber['EMPCODIGO']);

$cancellation->build();
$cancellation->sign();
$cancellation->send();