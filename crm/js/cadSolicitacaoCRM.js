function exibirCidades(codestado, codcidade, div_retorno, vSNome) {

    jQuery.ajax({
        async: true,
        type: "GET",
        url: "../cadastro/combos/comboCidades.php",
        data: {
            codestado: codestado,
			codcidade: codcidade,
			vSNome: vSNome,
            method: 'fillcidade'
        },
        success: function(resposta){
            document.getElementById(div_retorno).innerHTML = resposta;
            return true;
        },
        error: function(msg) {
            alert("Ocorreu um erro na busca da cidade!"+msg);
            return false;
        }
    });
}

function buscarCEP(vSCEP, vSORIGEM){
	if (vSCEP != ''){
		$.ajax({
			url: '../includes/buscarCEP.php',
			type: 'GET',
			dataType: 'json',
			data: {
				cep: vSCEP
			},
			success: function(result){
				console.log(result);
				if(result.logradouro != ''){
					if (vSORIGEM == 'INPI') {	
					
						$("#vHINPIENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHINPIENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHINPIESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_inpi', 'vHINPICIDCODIGO');						
						$("#vHINPIENDNROLOGRADOURO").focus();						
				
					} else if (vSORIGEM == 'COR') {	

						$("#vHCORENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHCORENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHCORESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_cor', 'vHCORCIDCODIGO');					
						$("#vHCORENDNROLOGRADOURO").focus();
						
					} else if (vSORIGEM == 'COB') {	
					
						$("#vHCOBENDLOGRADOURO").val(result.logradouro).addClass('isActive');
						$("#vHCOBENDBAIRRO").val(result.bairro).addClass('isActive');
						$("#vHCOBESTCODIGO").val(result.estadoCodigo).addClass('isActive');							
						exibirCidades(result.estadoCodigo, result.cidadeCodigo, 'div_cidade_cob', 'vHCOBCIDCODIGO');					
						$("#vHCOBENDNROLOGRADOURO").focus();
						
					}
				}
			},
			error: function(){
				sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
			}
		});
	}	
}