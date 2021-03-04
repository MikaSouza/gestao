<?php //if ($_SESSION['SA_ACESSOS']['TABELA'][$vAConfiguracaoTela['MENCODIGO']]['INCLUSAO'] == "S") {
    if ($vAConfiguracaoTela['BTN_NOVO_REGISTRO'] == '') {
        ?>
<a href="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>?method=insert" id="btnIncluir">
	<button class="btn btn-primary px-4 btn-rounded float-left mt-0 mb-3">+ Novo Registro</button>
</a>
<?php
    } //}?>
<?php if ($vAConfiguracaoTela['BTN_FILTROS'] == 'S') {
        ?>
<button type="button" class="btn btn-primary waves-effect waves-light float-right" data-toggle="modal"
	data-animation="bounce" data-target=".bs-example-modal-center">+ Filtros</button>
<?php
    } ?>
<div class="table-responsive dash-social">
	<form id="frmGrid" name="frmGrid">
		<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
			style="border-collapse: collapse; border-spacing: 0; width: 100%;">
			<thead>
				<tr>
					<th width="25px">AÇÕES</th>
					<!--<th align="center" width="15px" onclick="marcarTodosColuna('frmGrid', 'ckPadrao[]');"><input type='checkbox' title='Marcar Todos' name='ckPadraoTitulo' onclick="marcarTodosColuna('frmGrid', 'ckPadrao[]');"  id='ckPadraoTitulo' /></th>-->
					<?php
        //print_r($vAConfig);
        for ($i = 0; $i < count($vAConfig['vATitulos']); $i++) {
            if ($vAConfig['vATitulos'][$i] != '') {
                $vSGridTitulos .= "	<th>".$vAConfig['vATitulos'][$i]."</th>";
            }
        }
            echo $vSGridTitulos;
        if ($vAConfiguracaoTela['BTN_NOVO_REGISTRO'] == '') {
            ?>

					<?php
        } ?>
				</tr>
			</thead>
			<tbody>
				<?php
    if ($vAConfiguracaoTela['MENTITULOFUNC'] <> 'N') {
        $vSName = "list".$vAConfiguracaoTela['MENTITULOFUNC'];
        $arrayGrid = $vSName($vAConfiguracaoTela);
    } else {
        $arrayGrid = $result;
    }


        foreach ($arrayGrid['dados'] as $arrayGrid) : ?>

				<tr>
					<?php if ($vAConfiguracaoTela['BTN_NOVO_REGISTRO'] == ''): ?>
					<td align="center">
						<a href="<?= $vAConfiguracaoTela['MENARQUIVOCAD']; ?>?oid=<?= $arrayGrid[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO']; ?>&method=update"
							class="mr-2" title="Editar Registro" alt="Editar Registro"><i
								class="fas fa-edit text-info font-16"></i></a>
						<a href="#"
							onclick="excluirRegistroGrid('<?= $arrayGrid[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO']?>', '<?= $vAConfiguracaoTela['MENARQUIVOTRAN']; ?>', 'excluirPadrao', '<?= $vAConfiguracaoTela['MENCODIGO']; ?>')"
							title="Excluir Registro" alt="Excluir Registro"><i
								class="fas fa-trash-alt text-danger font-16"></i></a>
					</td>
					<?php endif; ?>
					<!--<td align="center"><input type='checkbox' title='ckPadrao' name='ckPadrao[]' value ='' id='ckPadrao[]' /></td>-->
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
                    } elseif ($vAConfig['vATipos'][$i] == 'text') {
                        $vSAlinhar = 'left';
                        $vSValor = nl2br($arrayGrid[$vAConfig['vACampos'][$i]]);
                    } elseif ($vAConfig['vATipos'][$i] == 'sequencial') {
                        $vSAlinhar = 'right';
                        $vSValor = adicionarCaracterLeft($arrayGrid[$vAConfig['vACampos'][$i]], 6);
                    } elseif ($vAConfig['vATipos'][$i] == 'simNao') {
                        $vSAlinhar = 'center';
                        $vSValor = ativoSimNao($arrayGrid[$vAConfig['vACampos'][$i]]);
                    } elseif ($vAConfig['vATipos'][$i] == 'chave') {
                        $vIOID = $arrayGrid[$vAConfig['vACampos'][$i]];
                    } else {
                        $vSAlinhar = 'center';
                        $vSValor = $arrayGrid[$vAConfig['vACampos'][$i]];
                    }
                    if ($i == 0) {
                        $vSGrid = "<td align=\"$vSAlinhar\">";
                        if ($vAConfiguracaoTela['LINKFILIAL'] > 0) {
                            $vSGrid .= "<a href=\"". $vAConfiguracaoTela['MENARQUIVOCAD'] ."?oid=".$arrayGrid[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO']."&method=update&filial=".$vAConfiguracaoTela['LINKFILIAL']."\" class=\"mr-2\" title=\"Editar Registro\" alt=\"Editar Registro\">";
                        } else {
                            $vSGrid .= "<a href=\"". $vAConfiguracaoTela['MENARQUIVOCAD'] ."?oid=". $arrayGrid[$vAConfiguracaoTela['MENPREFIXO'].'CODIGO'] ."&method=update\" class=\"mr-2\" title=\"Editar Registro\" alt=\"Editar Registro\">";
                        }
                        $vSGrid .= $vSValor ."</a></td>";
                    } else {
                        $vSGrid = "<td align=\"$vSAlinhar\">". $vSValor ."</td>";
                    }
                }
                if ($vSGrid != '') {
                    echo $vSGrid;
                }

                $vSGrid = "";
                $vSValor = '';
            } ?>
				</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	</form>
</div>
<!--
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mt-0" id="exampleModalLabel">Filtros</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
			<form class="form-parsley">
				<div class="row">
					<div class="col-md-6">
						<label>Data Início</label>
						<input class="form-control" name="" title="Data Início" id=""  type="date" maxlength="10">
                    </div>
					<div class="col-md-6">
						<label>Data Fim</label>
						<input class="form-control" name="" title="Data Fim" id=""  type="date" maxlength="10">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 mt-3">
						<button type="button" class="btn btn-primary btn-xs  waves-effect waves-light fa-pull-right">Aplicar</button>
					</div>
				</div>
			</form>

			</div>
		</div>
	</div>
</div>-->