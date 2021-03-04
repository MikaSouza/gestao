<?php
/*
    if($vAConfig['NovoRegistro'][0] == 'N'){?>
<?php echo "";?>
<?php
    }else{?>
<button type="button" class="btn btn-primary px-4 btn-rounded float-right mt-0 mb-3"
	onclick="exibirFormModal('','modal_div_<?=$tituloModal?>','<?=$tituloModal?>')">+ Novo Registro</button>
<?php
    }*/
?>


<?php if ($vAConfig['BTN_NOVO_REGISTRO'] == '') {
    ?>
<button type="button" class="btn btn-primary px-4 btn-rounded float-right mt-0 mb-3"
	onclick="exibirFormModal('','modal_div_<?=$tituloModal?>','<?=$tituloModal?>')">+ Novo Registro</button>
<?php
} ?>
<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
	style="border-collapse: collapse; border-spacing: 0; width: 100%;">
	<thead>
		<tr>
			<?php
            //print_r($vAConfig);
            for ($i = 0; $i < count($vAConfig['vATitulos']); $i++) {
                if ($vAConfig['vATitulos'][$i] != '') {
                    $vSGridTitulos .= "	<th>".$vAConfig['vATitulos'][$i]."</th>";
                }
            }
            echo $vSGridTitulos;
                ?>
			<?php if (empty($vAConfig['BTN_EDITAR']) || empty($vAConfig['BTN_EXCLUIR'])): ?>
			<th>Ações</th>
			<?php endif; ?>
		</tr>
	</thead>
	<?php
    /*$vSName = "list".$nomeList;*/

    $colsp = count($vAConfig['vATitulos']);
    $arrayGrid = $result;

    if ($arrayGrid['quantidadeRegistros'] == 0) {
        echo "<td align='center' colspan='".$colsp."'>Sem dados disponíveis na tabela.</td>";
    } else {
        foreach ($arrayGrid['dados'] as $arrayGrid) : ?>

	<tr>
		<?php $vIQtdeR = 0;
        for ($i = 0; $i < count($vAConfig['vACampos']); $i++) {
            if ($vAConfig['vACampos'][$i] != '') {
                $vIQtdeR++;
                if ($vAConfig['vATipos'][$i] == 'int') {
                    $vSAlinhar = 'right';
                    $vSValor = $arrayGrid[$vAConfig['vACampos'][$i]];
                } elseif ($vAConfig['vATipos'][$i] == 'int_sum') {
                    $vSAlinhar = 'right';
                    $vSValor = $arrayGrid[$vAConfig['vACampos'][$i]];
                    $vITotalIntSum[$i] += $arrayGrid[$vAConfig['vACampos'][$i]];
                } elseif ($vAConfig['vATipos'][$i] == 'datetime') {
                    $vSAlinhar = 'center';
                    $vSValor = formatar_data_hora($arrayGrid[$vAConfig['vACampos'][$i]]);
                } elseif ($vAConfig['vATipos'][$i] == 'monetario') {
                    $vSAlinhar = 'right';
                    $vSValor = formatar_moeda($arrayGrid[$vAConfig['vACampos'][$i]], false);
                    $vCTotalMonSum[$i] += $arrayGrid[$vAConfig['vACampos'][$i]];
                } elseif ($vAConfig['vATipos'][$i] == 'float') {
                    $vSAlinhar = 'right';
                    $vSValor = formatar_moeda($arrayGrid[$vAConfig['vACampos'][$i]], false);
                } elseif ($vAConfig['vATipos'][$i] == 'date') {
                    $vSAlinhar = 'center';
                    $vSValor = formatar_data($arrayGrid[$vAConfig['vACampos'][$i]]);
                } elseif ($vAConfig['vATipos'][$i] == 'varchar') {
                    $vSAlinhar = 'left';
                    $vSValor = $arrayGrid[$vAConfig['vACampos'][$i]];
                    $tituloModalCustos = $arrayGrid[$vAConfig['vACampos'][$i]];
                } elseif ($vAConfig['vATipos'][$i] == 'text') {
                    $vSAlinhar = 'left';
                    $vSValor = nl2br($arrayGrid[$vAConfig['vACampos'][$i]]);
                } elseif ($vAConfig['vATipos'][$i] == 'sequencial') {
                    $vSAlinhar = 'right';
                    $vSValor = adicionarCaracterLeft($arrayGrid[$vAConfig['vACampos'][$i]], 5);
                } elseif ($vAConfig['vATipos'][$i] == 'simNao') {
                    $vSAlinhar = 'center';
                    $vSValor = ativoSimNao($arrayGrid[$vAConfig['vACampos'][$i]]);
                } elseif ($vAConfig['vATipos'][$i] == 'chave') {
                    $vIOIDFILHO = $arrayGrid[$vAConfig['vACampos'][$i]];
                } elseif ($vAConfig['vATipos'][$i] == 'origem') {
                    $vSOrigem = $arrayGrid[$vAConfig['vACampos'][$i]];
                } else {
                    $vSAlinhar = 'center';
                    $vSValor = $arrayGrid[$vAConfig['vACampos'][$i]];
                }
                if ($vAConfig['vATipos'][$i] != 'chave' and $vAConfig['vATipos'][$i] != 'origem') {
                    $vSGrid = "<td align=\"$vSAlinhar\">". $vSValor ."</td>";
                }
            }
            if ($vSGrid != '') {
                echo $vSGrid;
            }
            $vSGrid = ''; ?>

		<?php
        } ?>
		<td>
			<?php 	if ($vAConfig['BTN_EDITAR'] == '') {
            ?>
			<a onclick="exibirFormModal(<?=$vIOIDFILHO?>,'','<?=$tituloModal?>')" class="mr-2 mdi"
				style="cursor: pointer;" title="Editar Registro" alt="Editar Registro"><i
					class="fas fa-edit text-info font-16"></i></a>
			<?php
        }
        if ($vAConfig['BTN_INCLUIR_CUSTOS']) {
            ?>
			<a onclick="exibirModalCadCustos(<?=$vIOIDFILHO?>,'<?=$tituloModalCustos?>')" class="mr-2 mdi"
				style="cursor: pointer;" title="Incluir Custos" alt="Incluir Custos"><i
					class="fas fa-dollar-sign text-info font-16"></i></a>
			<?php
        }
        if ($vSOrigem == 'P') {
            ?>
			<a href="#"
				onclick="excluirRegistroGridFilhaMedidaReformaInsumos('<?= $vIOIDFILHO; ?>', '<?= $vAConfig['TRANSACTION']; ?>', 'excluirFilhoP', '<?= $vAConfig['DIV_RETORNO']; ?>', '<?= $vAConfig['ID_PAI']; ?>', '<?= $vAConfig['FUNCAO_RETORNO']; ?>')"
				title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
			<?php
        } elseif ($vSOrigem == 'O') {
            ?>
			<a href="#"
				onclick="excluirRegistroGridFilhaMedidaReformaInsumos('<?= $vIOIDFILHO; ?>', '<?= $vAConfig['TRANSACTION']; ?>', 'excluirFilhoO', '<?= $vAConfig['DIV_RETORNO']; ?>', '<?= $vAConfig['ID_PAI']; ?>', '<?= $vAConfig['FUNCAO_RETORNO']; ?>')"
				title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
			<?php
        }
        if ($vAConfig['BTN_EXCLUIR'] == '') {
            ?>
			<a href="#"
				onclick="excluirRegistroGridFilha('<?= $vIOIDFILHO; ?>', '<?= $vAConfig['TRANSACTION']; ?>', 'excluirFilho', '<?= $vAConfig['DIV_RETORNO']; ?>', '<?= $vAConfig['ID_PAI']; ?>', '<?= $vAConfig['FUNCAO_RETORNO']; ?>')"
				title="Excluir Registro" alt="Excluir Registro"><i class="fas fa-trash-alt text-danger font-16"></i></a>
			<?php
        } ?>
		</td>
	</tr>

	<?php endforeach;
    }
    ?>
</table>