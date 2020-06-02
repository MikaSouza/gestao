<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *  
 *  @param [in] $pSNome Nome e ID do campo
 *  @param [in] $pSTipo CONTAS A PAGAR - FORMA DE PAGAMENTO ou CONTAS A RECEBER - FORMA DE PAGAMENTO
 *  @param [in] $pSObrigatorio Campo Obrigatorio, passar obrigatorio
 *  @param [in] $pSDisabled Campo desabilitado, passar disabled
 *  @param [in] $pISeletor Valor selecionado do campo
 *  @return Codigo HTML
 *  
 *  @details Details
 */
function gerarFormaPagamento($pSNome, $pSTipo, $pSObrigatorio, $pSDisabled, $pISeletor){ ?>
	<span>Forma de Pagamento </span><br />
	<select name="<?php echo $pSNome;?>" id="<?php echo $pSNome;?>" class="<?php echo $pSObrigatorio;?>" <?php echo $pSDisabled;?> >
		<option value="">----------</option>
				<?php
					$SqlCC = 'SELECT DISTINCT
						T.TABCODIGO,
						T.TABDESCRICAO
					FROM
						TABELAS T
					LEFT JOIN
						TABELASXEMPRESAUSUARIA T2
					ON
						T.TABCODIGO = T2.TABCODIGO
					WHERE
						T.TABTIPO = "'. $pSTipo .'" ';
					if (strpos(vIEmpresaUsuariaAdmin, $_SESSION['SI_USU_EMPRESA']) !== false)
						$SqlCC .=' and T2.EMPCODIGO = '. $_SESSION['SI_USU_EMPRESA'];
					else	
						$SqlCC .=' and T2.EMPCODIGO in ('. $_SESSION['SA_EMPRESAS'] .')';
					$SqlCC .=' AND
						T2.TXEACESSO = "S"
					AND
						T.TABSTATUS = "S"
					ORDER BY
						T.TABDESCRICAO';
					$vConexao = sql_conectar_banco(vGBancoSite);
					$RS_CC = sql_executa(vGBancoSite,$vConexao,$SqlCC);
					while($reg_CC = sql_retorno_lista($RS_CC)){ ?>
			<option value="<?php echo $reg_CC['TABCODIGO']; ?>" <?php if ($pISeletor == $reg_CC['TABCODIGO']) echo "selected='selected'"; ?>><?php echo $reg_CC['TABDESCRICAO']; ?></option>
					<?php }?>
		</select><br />
<?php } ?>