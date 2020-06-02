<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if(isset($_GET["method"]) && $_GET["method"] == 'fillDepartamentos') {
	$result = comboDepartamentos($_GET['vIUSUCODIGO']);
	?>
	<label>Departamento/Setor</label>
	<select title="Departamento/Setor" id="<?= $_GET['vSNOME']; ?>" class="custom-select" name="<?= $_GET['vSNOME']; ?>" >
		<?php foreach ($result['dados'] as $result) : ?>
				<option value="<?php echo $result['TABCODIGO']; ?>" <?php if ($_GET['vIUSUCODIGO'] == $result['TABCODIGO']) echo "selected='selected'"; ?> ><?php echo $result['TABDESCRICAO']; ?></option>
		<?php endforeach; ?>
	</select>
	<?php

}

function comboDepartamentos($vIUSUCODIGO)
{
	$sql = "SELECT
                t.TABCODIGO, t.TABDESCRICAO
            FROM
                TABELAS t, USUARIOS u
            WHERE
				t.TABCODIGO = u.TABDEPARTAMENTO
			AND u.USUCODIGO = ?	
            AND t.TABSTATUS = 'S'
            AND t.TABTIPO = 'USUARIOS - DEPARTAMENTOS'    
            ORDER BY
                t.TABDESCRICAO ASC";
	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array(array($vIUSUCODIGO, PDO::PARAM_INT))
				);
	$result = consultaComposta($arrayQuery);
	return $result;
}