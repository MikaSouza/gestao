<?php

function listCobrancas($_POSTDADOS){
	$where = '';
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL']))
		$where .= 'AND C.CLISEQUENCIAL = ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME']))
		$where .= 'AND c.CLINOME LIKE ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vSStatusFiltro'])){
		if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'S')
			$where .= "AND cp.CTRSTATUS = 'S' ";
		else if($_POSTDADOS['FILTROS']['vSStatusFiltro'] == 'N')
			$where .= "AND cp.CTRSTATUS = 'N' ";
	}else
		$where .= "AND cp.CTRSTATUS = 'S' ";

	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio']))
		$where .= 'AND cp.CTRDATA_INC >= ? ';
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim']))
		$where .= 'AND cp.CTRDATA_INC <= ? ';
	
	$sql = "SELECT
				cp.*,
				c.CLINOME,
				e.EMPNOMEFANTASIA,
				u.USUNOME AS CONSULTOR,
				t1.TABDESCRICAO AS CENTROCUSTO,
				t3.TABDESCRICAO AS PLANO
			FROM
				CONTASARECEBER cp 
			LEFT JOIN CLIENTES c ON c.CLICODIGO = cp.CLICODIGO	
			LEFT JOIN EMPRESAUSUARIA e ON e.EMPCODIGO = cp.EMPCODIGO	
			LEFT JOIN USUARIOS u ON	cp.CTRCONSULTOR = u.USUCODIGO
			LEFT JOIN TABELAS t1 ON	t1.TABCODIGO = cp.TABCENTROCUSTO
			LEFT JOIN TABELAS t3 ON	t3.TABCODIGO = cp.TABPLANOCONTAS
			WHERE
				cp.CTRSTATUS = 'S'
				".	$where	."
			LIMIT 20 ";

	$arrayQuery = array(
					'query' => $sql,
					'parametros' => array()
				);
	if(verificarVazio($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'])){
		$arrayQuery['parametros'][] = array($_POSTDADOS['FILTROS']['vICLISEQUENCIAL'], PDO::PARAM_INT);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vSCLINOME'])){
		$vSCLINOME = $_POSTDADOS['FILTROS']['vSCLINOME'];
		$arrayQuery['parametros'][] = array("%$vSCLINOME%", PDO::PARAM_STR);
	}			
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataInicio'])){
		$varIni = $_POSTDADOS['FILTROS']['vDDataInicio']." 00:00:00";
		$arrayQuery['parametros'][] = array($varIni, PDO::PARAM_STR);
	}
	if(verificarVazio($_POSTDADOS['FILTROS']['vDDataFim'])){
		$varFim = $_POSTDADOS['FILTROS']['vDDataFim']." 23:59:59";
		$arrayQuery['parametros'][] = array($varFim, PDO::PARAM_STR);
	}
				
	$result = consultaComposta($arrayQuery);

	return $result;

}
