<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillContasBancarias') {
	$result = comboContasBancarias($_GET['vIEMPCODIGO']);
	?>
	<label>Conta Bancária</label>
	<select title="Conta Bancária" id="vICBACODIGO" class="custom-select obrigatorio" name="vICBACODIGO" >
		<?php foreach ($result['dados'] as $result) : ?>
				<option value="<?php echo $result['CBACODIGO']; ?>" <?php if ($_GET['vICBACODIGO'] == $result['CBACODIGO']) echo "selected='selected'"; ?> ><?php echo $result['CONTA']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function comboContasBancarias($vIEMPCODIGO)
{
	if(verificarVazio($vIEMPCODIGO))
		$where .= 'AND CBA.EMPCODIGO = ? ';
	$sql = "SELECT
			CBA.CBACODIGO,
			CBA.CBANOMEAGENCIA,
			CONCAT(IFNULL(B.BACBANCO, ''), ' - ', IFNULL(CBA.CBAAGENCIA, ''), ' - ', IFNULL(CBA.CBACONTA, '')) AS CONTA
		FROM
			CONTASBANCARIAS CBA
		LEFT JOIN BANCOS B ON B.BACCODIGO = CBA.BACCODIGO
		WHERE CBA.CBASTATUS = 'S'	
		".	$where	."
		ORDER BY
			B.BACBANCO ";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($vIEMPCODIGO))
		$arrayQuery['parametros'][] = array($vIEMPCODIGO, PDO::PARAM_INT);				
	$result = consultaComposta($arrayQuery);
	return $result;
}