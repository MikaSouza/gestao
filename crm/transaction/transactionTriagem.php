<?php
include_once __DIR__.'/../../twcore/teraware/php/constantes.php';

if (isset($_POST['hdn_metodo_search']) && $_POST['hdn_metodo_search'] == 'searchTriagem')
    listTriagem($_POST);

function listTriagem($_POSTDADOS){
	
	$where = '';	
	if(verificarVazio($_POSTDADOS['vSCLICNPJ']))
		$where .= "AND CLICNPJ = ? ";	
	if(verificarVazio($_POSTDADOS['vSCLICPF']))
		$where .= "AND CLICPF = ? ";
	if(verificarVazio($_POSTDADOS['vSCLINOME']))
		$where .= "AND CLINOME LIKE ? ";
	if(verificarVazio($_POSTDADOS['vSCLICONTATO']))
		$where .= "AND CLICONTATO LIKE ? ";
	if(verificarVazio($_POSTDADOS['vSCLIEMAIL']))
		$where .= "AND CLIEMAIL LIKE ? ";
	if(verificarVazio($_POSTDADOS['vSCLIFONE']))
		$where .= "AND CLIFONE LIKE ? ";
	
	$sql = "SELECT 
					C.CLISEQUENCIAL,
					CONCAT(
					(CASE WHEN C.CLITIPOCLIENTE = 'J' THEN
					C.CLICNPJ
					ELSE
					C.CLICPF
					END)
					) as CNPJCPF,
					C.CLINOME,
					C.CLIDATA_INC,
					C.CLITIPOCLIENTE,
					t.TABDESCRICAO AS TIPOCADASTRO,
					COALESCE(U.USUNOME, 'SEM COMERCIAL DEFINIDO') AS COMERCIAL,
					t2.TABDESCRICAO AS STATUS,
					C.CLICODIGO
			FROM CLIENTES C			
			LEFT JOIN TABELAS t ON t.TABCODIGO = C.CLITIPOCADASTRO	
			LEFT JOIN TABELAS t2 ON t2.TABCODIGO = C.CLIPOSICAO	
			LEFT JOIN USUARIOS U ON C.CLIRESPONSAVEL = U.USUCODIGO
			WHERE 
				1 = 1
			".	$where	."
			ORDER BY 1 
			Limit 10";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);		

	if(verificarVazio($_POSTDADOS['vSCLICNPJ']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['vSCLICNPJ'], PDO::PARAM_STR);
	if(verificarVazio($_POSTDADOS['vSCLICPF']))
		$arrayQuery['parametros'][] = array($_POSTDADOS['vSCLICPF'], PDO::PARAM_STR);
	if(verificarVazio($_POSTDADOS['vSCLINOME']))
		$arrayQuery['parametros'][] = array("%".$_POSTDADOS['vSCLINOME']."%", PDO::PARAM_STR);	
	if(verificarVazio($_POSTDADOS['vSCLICONTATO']))
		$arrayQuery['parametros'][] = array("%".$_POSTDADOS['vSCLICONTATO']."%", PDO::PARAM_STR);
	if(verificarVazio($_POSTDADOS['vSCLIEMAIL']))
		$arrayQuery['parametros'][] = array("%".$_POSTDADOS['vSCLIEMAIL']."%", PDO::PARAM_STR);
	if(verificarVazio($_POSTDADOS['vSCLIFONE']))
		$arrayQuery['parametros'][] = array("%".$_POSTDADOS['vSCLIFONE']."%", PDO::PARAM_STR);
	
	$result = consultaComposta($arrayQuery);	
	$vITotalRetorno = $result['quantidadeRegistros'];
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="height: 430px; overflow: auto; width: 100%">
		<thead>
			<tr>
				<th>Sigla</th>
				<th>Cliente/Empresa</th>
				<th>CPF/CNPJ</th>
				<th>Representante</th>
				<th>Tipo de Cadastro</th>
				<th>Data Inclusão</th>	
				<th>Status</th>
				<th></th>				
			</tr>
		</thead>
	<?php foreach ($result['dados'] as $result) : ?>

		<tr>	
			<td align="right"><a href="../cadastro/cadClientes.php?oid=<?= $result['CLICODIGO'];?>&method=update" class="mr-2" title="Consultar Cliente" alt="Consultar Cliente" target="_blank"><?= $result['CLISEQUENCIAL'];?></a></td>
			<td align="left"><?= $result['CLINOME'];?></td> 
			<td align="left"><?= $result['CNPJCPF'];?></td>
			<td align="left"><?= $result['COMERCIAL'];?></td>
			<td align="left"><?= $result['TIPOCADASTRO'];?></td>
			<td align="center"><?= formatar_data_hora($result['CLIDATA_INC']);?></td>
			<td align="left"><?= $result['STATUS'];?></td> 
			<td align="left"><a href="cadSolicitacaoCRM.php?method=insert&oidcliente=<?= $result['CLICODIGO'];?>"><button type="button" class="btn btn-secondary waves-effect">Abrir Solicitação</button></a></td>
		</tr>

	<?php endforeach; ?>
	</table>	
	<a href="cadProspeccao.php?method=insert">
	<button type="button" class="btn btn-secondary waves-effect">Abrir Nova Prospecção</button>
	</a>
<?php
}