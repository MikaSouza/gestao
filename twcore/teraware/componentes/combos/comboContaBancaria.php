<?php // quando alterar este arquivo verificar as dependências
/**
 *  @brief Brief
 *  
 *  @param [in] $pSNome Nome e ID do campo
 *  @param [in] $pSTipo Crédito ou Débito
 *  @param [in] $pSObrigatorio Campo Obrigatorio, passar obrigatorio
 *  @param [in] $pSDisabled Campo desabilitado, passar disabled
 *  @param [in] $pISeletor Valor selecionado do campo
 *  @return Codigo HTML
 *  
 *  @details Details
 */
function gerarContaBancaria($pSNome, $pSTipo, $pSObrigatorio, $pSDisabled, $pISeletor){ ?>
	<span>Conta Bancária <?php echo $pSTipo;?> </span><br />
		<select name="<?php echo $pSNome;?>" id="<?php echo $pSNome;?>" class="<?php echo $pSObrigatorio;?>" <?php echo $pSDisabled;?> >
			<option value="">----------</option>
				<?php $vConexao = sql_conectar_banco(vGBancoSite);
					$SqlCD = "Select b.CBACODIGO, IFNULL(CBANOMEAGENCIA, CONCAT(b.CBAAGENCIA,' - ', b.CBACONTA)) AS CONTADEBITO
						From CONTASBANCARIAS b, CLIENTES c
						where b.CLICODIGO = c.CLICODIGO
						and b.CBASTATUS = 'S' ";
					if (strpos(vIEmpresaUsuariaAdmin, $_SESSION['SI_USU_EMPRESA']) !== false)
						$SqlCD .=" and b.EMPCODIGO = ". $_SESSION['SI_USU_EMPRESA'];
					else	
						$SqlCD .=" and b.EMPCODIGO in (". $_SESSION['SA_EMPRESAS'] .")";	
					$SqlCD .="	Order by CONTADEBITO";
					$RS_CD = sql_executa(vGBancoSite,$vConexao,$SqlCD);
					while($reg_CD = sql_retorno_lista($RS_CD)){ ?>
			<option value="<?php echo $reg_CD['CBACODIGO']; ?>" <?php if ($pISeletor == $reg_CD['CBACODIGO']) echo "selected='selected'"; ?>><?php echo $reg_CD['CONTADEBITO']; ?></option>
					<?php } ?>
		</select><br />
<?php } ?>