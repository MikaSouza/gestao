<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillProtocoloPrazos') {	
	$vSTABTIPO 	 = filter_input(INPUT_GET, "vSTABTIPO", FILTER_SANITIZE_STRING);
    $vIPTOPRAZO = filter_input(INPUT_GET, "vIPTOPRAZO", FILTER_SANITIZE_NUMBER_INT);
    $vSMETHOD    = filter_input(INPUT_GET, "vSMETHOD", FILTER_SANITIZE_STRING);
    $vSNAME      = filter_input(INPUT_GET, "vSNAME", FILTER_SANITIZE_STRING);	
	$result = comboTabelasxProtocolos($vSTABTIPO);
	?>
	<label>Prazo</label>
	<select title="Prazo" id="<?= $vSNAME;?>" class="custom-select" name="<?= $vSNAME;?>" <?php if($vSMETHOD == 'consultar') echo 'disabled'; ?>>
		<?php foreach ($result['dados'] as $result) : ?>
				<option value="<?php echo $result['TABCODIGO']; ?>" <?php if ($vIPTOPRAZO == $result['TABCODIGO']) echo "selected='selected'"; ?> ><?php echo $result['TABDESCRICAO']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function comboTabelasxProtocolos($vSTABTIPO)
{
   $sql = "SELECT
                TABCODIGO, TABDESCRICAO
            FROM
                TABELAS
            WHERE
                TABSTATUS = 'S'
            AND
                TABTIPO = ?    
            ORDER BY
                TABCOMPLEMENTO ASC";			
   $arrayQuery = array(
       'query' => $sql,
       'parametros' => array(
           array($vSTABTIPO, PDO::PARAM_STR)
           )
   );
   $list = consultaComposta($arrayQuery);
   return $list;
}