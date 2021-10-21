const assets = [
'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css',
'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js',
'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js',
'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/pt-br.js',
];

var dataSource = {
	method:'list'
}

let consultor = document.getElementById('vICONSULTOR');
let vinculo   = document.getElementById('vIMENCODIGO');

$(document).ready(function() {
	//getHTMLSctructure();

	requireFiles(assets, renderCalendar);

	$("#gerarGridJSON").click(function() {
		dataSource['consultor'] = consultor.value;
		dataSource['vinculo'] = vinculo.value;
		$("#mycal").fullCalendar('refetchEvents');
	});
});

function editarEvento(event)
{
	$("#vSAGETITULO").val(event.title);
	$("#vHCLIENTE").val(event.clientName);
	$("#vICLICODIGO").val(event.clientId);
	if (event.clientId == '') {				
		$("#checkbox2").prop("checked", true);
		mostrarDivCliente();
	}	
	$("#vSAGEDESCRICAO").val(event.description);
	$("#vIAGERESPONSAVEL").val(event.assignee_id);
	$("#vIAGETIPO").val(event.type_id);
	$("#vSAGECONCLUIDO").val(event.concluido);
	$("#vSAGEDATAINICIO").val(moment(event.start).format("DD/MM/YYYY"));
	$("#vSAGEHORAINICIO").val(moment(event.start).format("HH:mm"));
	$("#vSAGEDATAFINAL").val(moment(event.end).format("DD/MM/YYYY"));
	$("#vSAGEHORAFINAL").val(moment(event.end).format("HH:mm"));
	$("#vSAGEENVIAREMAIL").val(event.enviar_email);
	$("#vSAGEENVIARSMS").val(event.enviar_sms);
	$("#vIAGECODIGO").val(event.id);
	$("#eventContent").modal("hide");
	$("#insertEvent").dialog('open');
	
}

function deletarEvento(event_id)
{
	if (event_id) {
		swal({
			title: "",
			text: "Deseja excluir a Atividade selecionado?",
			type: "warning",
			confirmButtonText: "Excluir",
			cancelButtonText: "Cancelar",
			showCancelButton: true,
			showLoaderOnConfirm: true,
			closeOnConfirm: false
		},function(){
			$.ajax({
				url: 'transaction/transactionAgenda.php',
				type: 'GET',
				async: true,
				dataType: 'json',
				data: {
					method: 'delete',
					vIAGECODIGO: event_id
				},
				success: function(response) {
					swal('', 'Atividade excluido com sucesso', 'success');
				},
				error: function(response) {
					swal('Oops', 'Ocorreu um erro inesperado', 'error');
					console.log(response);
				},
				complete: function() {
					$("#mycal").fullCalendar('refetchEvents');
				}
			});

		});
	}
}

function removeClasseErroInputs(div_nome)
{
	$('#'+div_nome).find('input, select, textarea').each(function(){
		$(this).removeClass("ui-state-error");
	});
}
function limparCamposDialog(div_nome)
{
	$("#vSAGEDATAINICIO").val('');
	$("#vSAGEDESCRICAO").val('');
	$("#vSAGEHORAINICIO").val('');
	$("#vSAGEHORAFINAL").val('');
	$("#vSAGEENVIAREMAIL").val('N');
	$("#vSAGECONCLUIDO").val('N');
}

function getHTMLSctructure()
{
	$.ajax({
		url: 'agenda.php',
		type: 'GET',
		dataType: 'html',
		async: true,
		success: function(response){
			$("body").append(response);

			combo_padrao_tabelas({
				'vSTitulo'      : 'Tipo de Atividade',
				'vSTabTipo'     : 'AGENDA - TIPO',
				'vSCampo'       : 'vIAGETIPO',
				'vIValor'       : '',
				'vSDESABILITAR' : 'N',
				'vSDiv'         : 'divTipo',
				'vSClasse'      : 'divObrigatorio',
				'vSObrigatorio' : 'N',
				'vSStyle'       : 'width:337px;',
				'vSMethod'      : 'list'
			});

			$("#insertEvent").dialog({
				autoOpen: false,
				height: 'auto',
				width: 'auto',
				modal: true,
				buttons: {
					"Salvar": function() {
						var erros = validarCamposDiv("insertEvent");
						
						console.log('aqui:'+erros); 
						if(erros.length === 0){
							removeClasseErroInputs("insertEvent");
							let url  = "transaction/transactionAgenda.php";
							let data = {method: "insertAgenda"};

							$("#insertEvent").find('input, select, textarea').each(function(index, el) {
								data[$(this).attr("name")] = $(this).val();								
							});

							$.ajax({
								url: url,
								method: 'POST',
								async: true,
								data: data,
								complete: function(){
									$("#mycal").fullCalendar('refetchEvents');
								}
							});

							$(this).dialog("close");
						} else {
							swal("Opss..", erros.join("<br/>"), "warning");
						}
					},
					"Cancelar": function() {
						$(this).dialog("close");
					}
				},
				close: function() {
					limparCamposDialog("insertEvent");
					$("#vIAGERESPONSAVEL").val($("#insertEvent").data('user_id'));
					$("#vIAGETIPO").selectedIndex = 0;
					$("#vSAGEENVIAREMAIL").val('N');
					$("#vSAGEENVIARSMS").val('N');
					$("#vSAGECONCLUIDO").val('N');
					$("#mycal").fullCalendar('refetchEvents');
				},
				open: function() {
					$("#vHCLIENTE").autocomplete({
						source: "combos/comboClientes.php",
						appendTo: '#insertEvent',
						minLength: 2,
						autoFocus: true,
						select: function(event, ui) {
							$('#vICLICODIGO').val(ui.item.id);
						}
					});

					$(".classdatepicker").attr('maxlength', '10');
					$(".classdatepicker").keyup(function(event) {
						mascara('DATA', this, event);
					});
					$(".classdatepicker").datepicker({
						changeMonth: true,
						changeYear: true,
						dateFormat: "dd/mm/yy",
						showOn: "button",
						buttonImage: "../imagens/calendar.gif",
						buttonImageOnly: true,
						onSelect: function(dateText) {
							if ($(this).attr('id') == 'vSAGEDATAINICIO') {
								$("#vSAGEDATAFINAL").val(dateText);
							}
						}
					});

					$("#vSAGEDATAINICIO").on('blur', function(){
						$("#vSAGEDATAFINAL").val($(this).val());
					});

					$("#vSAGEHORAINICIO").on('blur', function(){
						if ($(this).val()) {
							let hora_inicio = $(this).val().split(':');
							let minutos = (parseInt(hora_inicio[0])*60)+parseInt(hora_inicio[1])+30;
							let horas = parseInt(minutos/60);
							let rest_minutos = minutos-(horas*60);

							horas = horas.toString();
							rest_minutos = rest_minutos.toString();

							while (horas.length < 2) horas = '0'+horas;
							while (rest_minutos.length < 2) rest_minutos = '0'+rest_minutos;

							if (parseInt(horas) >= 24) {
								horas = parseInt(horas)-24;
								horas = horas.toString();
								while (horas.length < 2) horas = horas+'0';
								$("#vSAGEDATAFINAL").val(moment($("#vSAGEDATAFINAL").val(), 'DD/MM/YYYY').add(1, 'day').format('DD/MM/YYYY'));
							}

							$("#vSAGEHORAFINAL").val(horas+':'+rest_minutos);
						}
					});
				}
			});
		},
		error: function(){
			swal('Opss..', 'Não foi possível carregar o Widget de agenda', 'error');
		}
	});
}

function renderCalendar()
{
	$("#mycal").fullCalendar({
		customButtons: {
			addButtom: {
				text: 'Inserir Atividade',
				click: function() {
					window.location.assign("cadAgenda.php?method=insert");
					
				}
			},
			atualizar: {
				text: 'Atualizar Atividade',
				click: function() {
					$("#mycal").fullCalendar('refetchEvents');
				}
			},
		},
		header: {
			left: 'prev,next today addButtom',
			center: 'title',
			right: 'atualizar month,basicWeek,basicDay'
		},
		locale: 'pt-br',
		timeFormat: 'HH:mm',
		nextDayThreshold: '00:00:00',
		events: function(start, end, timezone, callback) {
			dataSource['start']   = start.format();
			dataSource['end']     = end.format();
			dataSource['current'] = $('#mycal').fullCalendar('getDate').format('YYYY-MM-DD');

			$.ajax({
				url: 'transaction/transactionAgenda.php',
				method: 'GET',
				async: true,
				cache: false,
				dataType: 'json',
				data: dataSource,
				success: function(events) {
					callback(events);
				}
			});
		},
		eventAfterAllRender: function() {
			if ($("#cal_loader").length > 0) {
				$("#cal_loader").remove();
			}
			if ($(".equal_height").length > 0) {
				$(".equal_height").each(function(index, el) {
					$(el).height($($(el).data('equal')).height());
				});
			}
		},
		eventRender: function (event, element) {
			element.prop('title', event.title);
			element.attr('href', 'javascript:void(0);');
			element.css('background-color', event.color);

			element.click(function() {
				if (event.allDay) {
					$("#startTime").html(moment(event.start).format("DD/MM/YYYY"));
					$("#endTime").html(moment(event.start).format("DD/MM/YYYY"));
				} else {
					$("#startTime").html(moment(event.start).format("DD/MM/YYYY HH:mm"));
					$("#endTime").html(moment(event.end).format("DD/MM/YYYY HH:mm"));
				}
				$("#pSAGEDESCRICAO").html(event.description);
				$("#pSAGETITULO").html(event.title);
				if (parseInt(event.clientId)) {
					$("#pSCLIENTE").html('<a target="_blank" href="cadCliente.php?oid='+event.clientId+'&method=consultar">'+event.clientName+'</a>');
				} else {
					$("#pSCLIENTE").html("Não definido!");
				}

				if (parseInt(event.link_id)) {
					$("#pSVINCULO").html('<a target="_blank" href="'+event.menuName+'?oid='+event.link_id+'&method=consultar">'+event.menuTitulo+'</a>');
				} else {
					$("#pSVINCULO").html("Não definido!");
				}

				$("#pSAGERESPONSAVEL").html(event.assignee ? event.assignee : 'Não definido!');
				$("#pSAGETIPO").html(event.type ? event.type : 'Não definido!');
				$("#pSAGECONCLUIDO").html(event.concluido == 'S' ? 'Sim' : 'Não');
				$("#vIAGECODIGO").html(event.id);

				if ($("#eventLink").length) {
					$("#eventLink").on('click', function(e){
						e.preventDefault();						
						$("#eventContent").modal("hide");
						editarEvento(event);
					});
				}

				if ($("#deleteEvent").length) {
					$("#deleteEvent").on('click', function(e){
						e.preventDefault();
						$("#eventContent").modal("hide");
						deletarEvento(event.id);
					});
				}

				if (event.id) {
					$("#eventLink").show();
					$("#deleteEvent").show();
				} else {
					$("#eventLink").hide();
					$("#deleteEvent").hide();
				}

				$("#pSAGEENVIAREMAIL").html(destinatarios(event.enviar_email));
				$("#pSAGEENVIARSMS").html(destinatarios(event.enviar_sms));

				abrirModalAgenda(event.id);
			});
		},
	});
}

function destinatarios(dest)
{
	switch (dest) {
		case 'A':
			return 'Para o responsável e também para o cliente';
		case 'R':
			return 'Paro o responsável';
		case 'C':
			return 'Para o cliente';
		case 'N':
		default:
			return 'Não';
	}
}


require = function (file, callback) {
	callback = callback ||
	function () {};
	var filenode;
	var jsfile_extension = /(.js)$/i;
	var cssfile_extension = /(.css)$/i;

	if (jsfile_extension.test(file)) {
		filenode = document.createElement('script');
		filenode.src = file;
        // IE
        filenode.onreadystatechange = function () {
        	if (filenode.readyState === 'loaded' || filenode.readyState === 'complete') {
        		filenode.onreadystatechange = null;
        		callback();
        	}
        };
        // others
        filenode.onload = function () {
        	callback();
        };
        document.head.appendChild(filenode);
    } else if (cssfile_extension.test(file)) {
    	filenode = document.createElement('link');
    	filenode.rel = 'stylesheet';
    	filenode.type = 'text/css';
    	filenode.href = file;
    	document.head.appendChild(filenode);
    	callback();
    } else {
    	console.log("Unknown file type to load.")
    }
};

requireFiles = function () {
	var index = 0;
	return function (files, callback) {
		index += 1;
		require(files[index - 1], callBackCounter);

		function callBackCounter() {
			if (index === files.length) {
				index = 0;
				callback();
			} else {
				requireFiles(files, callback);
			}
		};
	};
}();

function abrirModalAgenda(vIAGECODIGO){	
	$("#vIAGECODIGO").val(vIAGECODIGO);
	$("#vSAGEENVIAREMAIL").val('N');
	$("#vSAGECONCLUIDO").val('N');
	if (vIAGECODIGO > 0) fillAgenda(vIAGECODIGO);
		
	$( "#modalAgenda").modal("show");	
}

$(document).ready(function() {
    $('#formAgendaDetalhe').submit(function(e) {
        e.preventDefault();
        var serializeDados = $('#formAgendaDetalhe').serialize();
		var erros = validarCamposDiv('modalAgenda');
		if(erros.length === 0){
			$.ajax({
				url: 'transaction/transactionAgenda.php',
				type: 'POST',
				async: true,
				dataType: 'json',
				data: serializeDados,				
				success: function(response) {
					swal(':)', 'Dados atulizados com sucesso!', 'success');
					
					$( "#modalAgenda").modal("hide");	
					$("#mycal").fullCalendar('refetchEvents');
				},
				error: function() {
					swal('', 'Ocorreu uma falha na atualização dos dados', 'error');
				}
			});
		} else {
			swal({title : "Opss..", text : erros.join("\n"), type : "warning"});
		}					
	});
});

function fillAgenda(pIAGECODIGO){
	var vSUrl = 'transaction/transactionAgenda.php?hdn_metodo_fill=fill_AgendaCalendario&AGECODIGO='+pIAGECODIGO+'&formatoRetorno=json';
	$.getJSON(vSUrl, function(json) {
		for (var i in json) {
            $("#vIAGECODIGO").val(json.AGECODIGO);
            $("#vSAGEDATAINICIO").val(json.AGEDATAINICIO);
			$("#vSAGEHORAINICIO").val(json.AGEHORAINICIO);
			$("#vSAGEHORAFINAL").val(json.AGEHORAFINAL);
			$("#vIAGERESPONSAVEL").val(json.AGERESPONSAVEL);
			$("#vIAGETIPO").val(json.AGETIPO);
			$("#vSAGEDESCRICAO").val(json.AGEDESCRICAO);
			$("#vICLICODIGO").val(json.CLIENTE);
			$("#vSAGECONCLUIDO").val(json.AGECONCLUIDO);
			$("#vSAGEENVIAREMAIL").val(json.AGEENVIAREMAIL);
			$("#vSAGECOPIARSUPERVISOR").val(json.AGECOPIARSUPERVISOR);
        }
	});
}

$(document).ready(function () {
    $('#vICLICODIGO').select2({
      minimumInputLength: 2,
      multiple: false,
      ajax: {
        url: '../cadastro/combos/comboClientes2.php',
		dataType: 'json',
		delay: 250,
        data: function (params) {
          var query = {
            term: params['term'],
            action: 'your_action'
          }
          return query;
        },
        processResults: function (response) {
		 return {
			results: response
		 };
	   }
      }
    });
});

function mostrarDivCliente(){
	if ($('#checkbox2').is(':checked')) {
		$(".divCliente").hide();	
		document.getElementById("vICLICODIGO").classList.remove("obrigatorio");
	}else{		
		$(".divCliente").show();	
		document.getElementById("vICLICODIGO").classList.add("obrigatorio");
	}	
}

function mostrarDivContato(){
	if ($('#checkbox3').is(':checked')) {
		$(".divContato").hide();	
		document.getElementById("vHCONTATO").classList.remove("obrigatorio");
	}else{		
		$(".divContato").show();	
		document.getElementById("vHCONTATO").classList.add("obrigatorio");
	}	
}