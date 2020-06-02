<?php
include_once __DIR__.'/../../php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillClientexConsultor') {
	$vICLICODIGO = filter_input(INPUT_GET, "vICLICODIGO", FILTER_SANITIZE_NUMBER_INT);
    $vIUSUCODIGO = filter_input(INPUT_GET, "vIUSUCODIGO", FILTER_SANITIZE_NUMBER_INT);
    $vSMETHOD    = filter_input(INPUT_GET, "vSMETHOD", FILTER_SANITIZE_STRING);
    $vSNAME      = filter_input(INPUT_GET, "vSNAME", FILTER_SANITIZE_STRING);
	
	$result = comboClientexConsultor($vICLICODIGO);
	?>
	<label>Consultor</label>
	<select title="Consultor" id="<?= $vSNAME;?>" class="custom-select obrigatorio" name="<?= $vSNAME;?>" <?php if($vSMETHOD == 'consultar') echo 'disabled'; ?>>
		<?php foreach ($result['dados'] as $result) : ?>
				<option value="<?php echo $result['USUCODIGO']; ?>" <?php if ($vIUSUCODIGO == $result['USUCODIGO']) echo "selected='selected'"; ?> ><?php echo $result['USUNOME']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function comboClientexConsultor($vICLICODIGO)
{
	$sql = "SELECT
				u.USUNOME,
				u.USUCODIGO
			FROM
				USUARIOS u, CLIENTES c
			WHERE
				u.USUCODIGO = c.CLIRESPONSAVEL
			AND
				u.USUSTATUS = 'S'
			AND
				u.USUDATADEMISSAO IS NULL
			AND
				c.CLICODIGO = ?
		ORDER BY
			u.USUNOME ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(array($vICLICODIGO, PDO::PARAM_INT))
				);
	$result = consultaComposta($arrayQuery);
	return $result;
}