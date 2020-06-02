<?php
	session_start();
	$_SESSION['SI_EMPCODIGO'] = $_GET['id'];
	header('location:http://vipcustos.twflex.com.br/cadastro/');
?>