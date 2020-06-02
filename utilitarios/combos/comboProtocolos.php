<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillDadosPasta') {
	
	echo $_GET["vIPTOPASTA"];
	// buscar cliente e buscar marca
	return;
	$result = comboProtocolos($_GET['codestado']);
	?>
	<label>Cidade</label>
	<select title="Cidade" id="<?= $_GET['vSNome']; ?>" class="custom-select <?php echo ($_GET['vSObrigatorio'] == 'S' ? 'obrigatorio' : ''); ?>" title="Cidade" name="<?= $_GET['vSNome']; ?>" >
		<?php foreach ($result['dados'] as $result) : ?>
				<option value="<?php echo $result['CIDCODIGO']; ?>" <?php if ($_GET['codcidade'] == $result['CIDCODIGO']) echo "selected='selected'"; ?> ><?php echo $result['CIDDESCRICAO']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function comboProtocolos($estcodigo)
{
	$sql = "SELECT
			CIDCODIGO,
			CIDDESCRICAO
		FROM
			CIDADES
		WHERE
			ESTCODIGO = ?
		ORDER BY
			CIDDESCRICAO ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(array($estcodigo, PDO::PARAM_INT))
				);
	$result = consultaComposta($arrayQuery);
	return $result;
}