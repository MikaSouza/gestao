$(function () {
    $(".select2-multiple").select2();
    var form;
    $("#fileUpload").change(function (event) {
        form = new FormData();
        form.append("fileUpload", event.target.files[0]); // para apenas 1 arquivo
        //var name = event.target.files[0].content.name; // para capturar o nome do arquivo com sua extenção
        form.append("vIGEDVINCULO", $("#vICLICODIGO").val());
        form.append("vSGEDDIRETORIO", "clientes");
        form.append("vIMENCODIGO", 6);
        form.append("method", "incluirGED");
    });

    $("#gerarSenhaBtn").click(function (event) {
        event.preventDefault();
        $.ajax({
            url:
                "transaction/transactionContatos.php?hdn_metodo_fill=gerarSenhaContatos",
            success: function (result) {
                $("#vHMCONSENHA").val(result);
            },
            error: function (result) {
                alert("Ocorreu um erro ao gerar a senha!");
                console.log(result);
            },
        });
    });

    $("#vHMCONCELULAR, #vHMCONWHATS, #vHMCONFONE").on("paste", function (e) {
        $(this).mask("(99) 9999-9999?9");
    });

    $("#btnEnviar").click(function () {
        var erros = validarCamposDiv("modal_div_ClientesxGED");
        form.append("vIGEDTIPO", $("#vHGED").val());
        if (erros.length === 0) {
            var hdn_pai_codigo = $("#vICLICODIGO").val();
            $.ajax({
                url: "../utilitarios/transaction/transactionGED.php",
                data: form,
                processData: false,
                contentType: false,
                type: "POST",
                success: function (data) {
                    swal({
                        title: "",
                        text: "Cadastro realizado com sucesso",
                        type: "success",
                    });
                    gerarGridJSONGED(
                        "../../utilitarios/transaction/transactionGED.php",
                        "div_ged",
                        "GED",
                        hdn_pai_codigo,
                        "6"
                    );
                    return true;
                },
            });
        } else {
            swal({
                title: "Opss..",
                text: erros.join("\n"),
                type: "warning",
            });
        }
    });

    $("#formEnderecos").submit(function (e) {
        e.preventDefault();
        var serializeDados = $("#formEnderecos").serialize();
        var data = {
            method: "incluirEndereco",
            hdn_oid_endereco: $("#hdn_endereco").val(),
            vICLICODIGO: $("#vICLICODIGO").val(),
            vITABCODIGO: $("#vITABCODIGO").val(),
            vITLOCODIGO: $("#vITLOCODIGO").val(),
            vSENDLOGRADOURO: $("#vSENDLOGRADOURO").val(),
            vSENDNROLOGRADOURO: $("#vHENDNROLOGRADOURO").val(),
            vSENDCOMPLEMENTO: $("#vHENDCOMPLEMENTO").val(),
            vSENDBAIRRO: $("#vHENDBAIRRO").val(),
            vSENDCEP: $("#vHENDCEP").val(),
            vIPAICODIGO: $("#vHPAICODIGO").val(),
            vIESTCODIGO: $("#vHESTCODIGO").val(),
            vICIDCODIGO: $("#vHCIDCODIGO").val(),
        };
        $.ajax({
            url: "transaction/transactionClientes.php",
            type: "POST",
            async: true,
            dataType: "json",
            data: data,
            success: function (response) {
                swal(":)", "Dados atulizados com sucesso!", "success");

                $("#modalEnderecos").modal("hide");
                gerarGridJSONContasPagar();
            },
            error: function () {
                swal("", "Ocorreu uma falha na atualização dos dados", "error");
            },
        });
    });

    $("#formContatos").submit(function (e) {
        e.preventDefault();
        var serializeDados = $("#formContatos").serialize();
        var data = {
            method: "incluirContato",
            vICONCODIGO: $("#vHCONCODIGO").val(),
            vICLICODIGO: $("#vICLICODIGO").val(),
            vSCONNOME: $("#vSCONNOME").val(),
            vICONESTADOCIVIL: $("#vICONESTADOCIVIL").val(),
            vSCONEMAIL: $("#vSCONEMAIL").val(),
            vSCONCPF: $("#vSCONCPF").val(),
            vSCONRG: $("#vSCONRG").val(),
            vSCONFONE: $("#vSCONFONE").val(),
            vSCONRAMAL: $("#vSCONRAMAL").val(),
            vSCONCELULAR: $("#vSCONCELULAR").val(),
            vSCONCARGO: $("#vSCONCARGO").val(),
            vSCONSETOR: $("#vSCONSETOR").val(),
            vSCONPRINCIPAL: $("#vSCONPRINCIPAL").val(),
        };
        $.ajax({
            url: "transaction/transactionContatos.php",
            type: "POST",
            async: true,
            dataType: "json",
            data: data,
            success: function (response) {
                swal(":)", "Dados atulizados com sucesso!", "success");

                $("#modalContatos").modal("hide");
                gerarGridJSONContasPagar();
            },
            error: function () {
                swal("", "Ocorreu uma falha na atualização dos dados", "error");
            },
        });
    });
});

function exibirCidades(codestado, codcidade, div_retorno, vSNome) {
    jQuery.ajax({
        async: true,
        type: "GET",
        url: "combos/comboCidades.php",
        data: {
            codestado: codestado,
            codcidade: codcidade,
            vSNome: vSNome,
            method: "fillcidade",
        },
        success: function (resposta) {
            document.getElementById(div_retorno).innerHTML = resposta;
            return true;
        },
        error: function (msg) {
            alert("Ocorreu um erro na busca da cidade!" + msg);
            return false;
        },
    });
}

function mostrarJxF(pSValue) {
    var element = document.getElementById("J");
    if (pSValue == "J") {
        $(".divJuridica").show();
        $(".divFisica").hide();
        document.getElementById("vSCLICNPJ").classList.add("obrigatorio");
        document.getElementById("vSCLICPF").classList.remove("obrigatorio");
        document.getElementById("vHCLINOME").classList.remove("obrigatorio");
        document
            .getElementById("vSCLIRAZAOSOCIAL")
            .classList.add("obrigatorio");
        document
            .getElementById("vSCLINOMEFANTASIA")
            .classList.add("obrigatorio");
        document
            .getElementById("vICLISITUACAORECEITA")
            .classList.add("obrigatorio");
    } else {
        $(".divFisica").show();
        $(".divJuridica").hide();
        document.getElementById("vSCLICPF").classList.add("obrigatorio");
        document.getElementById("vSCLICNPJ").classList.remove("obrigatorio");
        document.getElementById("vHCLINOME").classList.add("obrigatorio");
        document
            .getElementById("vSCLIRAZAOSOCIAL")
            .classList.remove("obrigatorio");
        document
            .getElementById("vSCLINOMEFANTASIA")
            .classList.remove("obrigatorio");
        document
            .getElementById("vICLISITUACAORECEITA")
            .classList.remove("obrigatorio");
    }
}

function mostrarIE(pSValue) {
    var element = document.getElementById("vSCLIIE");
    if (pSValue == "N") {
        $(".divIE").show();
        element.classList.add("obrigatorio");
    } else {
        $(".divIE").hide();
        element.classList.remove("obrigatorio");
    }
}

$(function () {
    $("#vSCLIISENTAIE").on("change", function () {
        mostrarIE($(this).val());
    });

    $("#vSCLICNPJ").mask("99.999.999/9999-99");
    $("#vHENDCEP").mask("99999-999");
    $("#vHMENDCEP").mask("99999-999");
    $("#vHCONFONE").mask("(99) 9999-9999");

    mostrarJxF($("#vSCLITIPOCLIENTE").val());
});

function buscarCEP(vSCEP) {
    if (vSCEP != "") {
        $.ajax({
            url: "../includes/buscarCEP.php",
            type: "GET",
            dataType: "json",
            data: {
                cep: vSCEP,
            },
            success: function (result) {
                console.log(result);
                if (result.logradouro != "") {
                    $("#vHENDLOGRADOURO")
                        .val(result.logradouro)
                        .addClass("isActive");
                    $("#vHENDBAIRRO").val(result.bairro).addClass("isActive");
                    $("#vHESTCODIGO")
                        .val(result.estadoCodigo)
                        .addClass("isActive");
                    exibirCidades(
                        result.estadoCodigo,
                        result.cidadeCodigo,
                        "div_cidade",
                        "vHCIDCODIGO"
                    );
                    $("#vHENDNROLOGRADOURO").focus();
                }
            },
            error: function () {
                sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
            },
        });
    }
}

function buscarDadosReceita() {
    if ($("#vSCLICNPJ").val() == "") {
        sweetAlert(
            "Oops...",
            "Informe o CNPJ para buscar os dados na Receita Federal!",
            "error"
        );
    } else {
        $.getJSON(
            "../includes/buscarCNPJ.php?vSCNPJ=" + $("#vSCLICNPJ").val(),
            function (json) {
                console.log(json);
                for (var i in json) {
                    vSMSG = json.vSMSG;
                    vSBloqueia = json.vSBloqueia;
                    $("#vICLICODIGO").val(json.vICLICODIGO);
                    $("#vSCLIRAZAOSOCIAL").val(json.vSCLIRAZAOSOCIAL);
                    $("#vSCLINOMEFANTASIA").val(json.vSCLINOMEFANTASIA);
                    $("#vICLISITUACAORECEITA").val(json.vICLISITUACAORECEITA);
                    $("#vDCLIDATA_INICIO_ATIVIDADES").val(
                        json.vDCLIDATA_INICIO_ATIVIDADES
                    );

                    $("#vICLIREGIMETRIBUTARIO").val(json.vICLIREGIMETRIBUTARIO);
                    $("#vICLINATUREZAJURIDICA").val(json.vICLINATUREZAJURIDICA);
                    $("#vICNACODIGO").val(json.vICNACODIGO);

                    $("#vHCONFONE").val(json.vSCLIFONE);
                    $("#vHCONEMAIL").val(json.vSCLIEMAIL);
                    //endereco
                    $("#vHENDLOGRADOURO").val(json.vSENDLOGRADOURO);
                    $("#vHENDNROLOGRADOURO").val(json.vSENDNROLOGRADOURO);
                    $("#vHENDCOMPLEMENTO").val(json.vSENDCOMPLEMENTO);
                    $("#vHENDBAIRRO").val(json.vSENDBAIRRO);
                    $("#vHENDCEP").val(json.vSENDCEP);
                    $("#vHESTCODIGO").val(json.vIESTCODIGO);
                    exibirCidades(
                        json.vIESTCODIGO,
                        json.vICIDCODIGO,
                        "div_cidade",
                        "vHCIDCODIGO"
                    );
                    swal({
                        title: ":)",
                        text: vSMSG,
                        type: "info",
                    });
                }
            }
        );
    }
}

function fillClientesxHistorico(pIFXSCODIGO, titulo) {
    var vSUrl =
        "transaction/transactionClientesxHistorico.php?hdn_metodo_fill=fill_ClientesxHistorico&FXSCODIGO=" +
        pIFXSCODIGO +
        "&formatoRetorno=json";
    $.getJSON(vSUrl, function (json) {
        for (var i in json) {
            $("#hdn_pai_" + titulo).val(json.FXSIDFILIAIS);
            $("#vDCHGDATA").val(json.CHGDATA);
            $("#vICHGTIPO").val(json.CHGTIPO);
            $("#vICHGPOSICAO").val(json.CHGPOSICAO);
            $("#vSCHGHISTORICO").val(json.CHGHISTORICO);
        }
    });
}

function salvarContato() {
    const vICLICODIGO = $("#vICLICODIGO").val();
    const DIV_NOME = "modalClientesxContatos";

    var erros = validarCamposDiv(DIV_NOME);
    if (erros.length === 0) {
        var data = {
            method: "incluir-contato",
            vICLICODIGO: vICLICODIGO,
            vICONCODIGO: $("vHMCONCODIGO").val(),
            vSCONNOME: $("#vHMCONNOME").val(),
            vSCONEMAIL: $("#vHMCONEMAIL").val(),
            vSCONCELULAR: $("#vHMCONFONE").val(),
            vSCONFONE: $("#vHMCONCELULAR").val(),
            vSCONCARGO: $("#vHMCONCARGO").val(),
            vSCONSETOR: $("#vHMCONSETOR").val(),
            vSCONSENHA: $("#vHMCONSENHA").val(),
        };
        $.ajax({
            async: true,
            type: "POST",
            url: "transaction/transactionContatos.php",
            dataType: "json",
            data: data,
            success: function (response) {
                if (response.success) {
                    swal({
                        title: "",
                        text: "Cadastro realizado com sucesso",
                        type: "success",
                    });
                    $("#tbody_contatos").prepend(
                        gerarLinhaContato(response.registro)
                    );
                } else {
                    sweetAlert(
                        "Oops...",
                        "Ocorreu um erro ao inserir o registro!",
                        "error"
                    );
                }
                $("#modalClientesxContatos").modal("hide");
            },
            error: function (msg) {
                limparCamposDialog(DIV_NOME);
                sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
                $("#modalClientesxContatos").modal("hide");
                return false;
            },
        });
    } else {
        swal({
            title: "Opss..",
            text: erros.join("\n"),
            type: "warning",
        });
    }
}

function fillEndereco(pIENDCODIGO) {
    var vSUrl =
        "transaction/transactionEnderecos.php?hdn_metodo_fill=fill_Enderecos&vIENDCODIGO=" +
        pIENDCODIGO +
        "&formatoRetorno=json";
    $.getJSON(vSUrl, function (json) {
        for (var i in json) {
            $("#vIENDCODIGO").val(json.ENDCODIGO);
            $("#vITABCODIGO").val(json.TABCODIGO);
            $("#vSENDLOGRADOURO").val(json.ENDLOGRADOURO);
            $("#vSENDNROLOGRADOURO").val(json.ENDNROLOGRADOURO);
            $("#vSENDCOMPLEMENTO").val(json.ENDCOMPLEMENTO);
            $("#vSENDBAIRRO").val(json.ENDBAIRRO);
            $("#vSENDCEP").val(json.ENDCEP);
            $("#vIESTCODIGO").val(json.ESTCODIGO);
            // exibirEstados(json.PAICODIGO, json.ESTCODIGO, json.CIDCODIGO);
            exibirCidades(
                json.ESTCODIGO,
                json.CIDCODIGO,
                "div_cidade_modal",
                "vICIDCODIGO"
            );
            $("#vSENDPADRAO").val(json.ENDPADRAO);
        }
    });
}

function salvarModalClientesxEnderecos() {
    const DIV_NOME = "modalClientesxEnderecos";
    const TRANSACTION = "transactionEnderecos.php";
    const DIV_RETURN = "div_enderecos";
    const METODO = "ClientesxEnderecos";
    const vICLICODIGO = $("#vICLICODIGO").val();

    var erros = validarCamposDiv(DIV_NOME);
    if (erros.length === 0) {
        var data = {
            method: "incluirEndereco",
            vICLICODIGO: vICLICODIGO,
            vIENDCODIGO: $("#vIENDCODIGO").val(),
            vSENDCEP: $("#vSENDCEP").val(),
            vITABCODIGO: $("#vITABCODIGO").val(),
            vSENDLOGRADOURO: $("#vSENDLOGRADOURO").val(),
            vSENDNROLOGRADOURO: $("#vSENDNROLOGRADOURO").val(),
            vSENDCOMPLEMENTO: $("#vSENDCOMPLEMENTO").val(),
            vSENDBAIRRO: $("#vSENDBAIRRO").val(),
            vIESTCODIGO: $("#vIESTCODIGO").val(),
            vICIDCODIGO: $("#vICIDCODIGO").val(),
            vSENDPADRAO: $("#vSENDPADRAO").val(),
        };
        $.ajax({
            async: true,
            type: "POST",
            url: "transaction/" + TRANSACTION,
            data: data,
            success: function (msg) {
                swal({
                    title: "",
                    text: "Cadastro realizado com sucesso",
                    type: "success",
                });
                gerarGridJSON(TRANSACTION, DIV_RETURN, METODO, vICLICODIGO);
                $("#modal" + METODO).modal("hide");
                limparCamposDialog(DIV_NOME);
            },
            error: function (msg) {
                limparCamposDialog(DIV_NOME);
                sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
                alert(msg);
                $("#modal" + METODO).modal("hide");
                return false;
            },
        });
    } else {
        swal({ title: "Opss..", text: erros.join("\n"), type: "warning" });
    }
}

function gerarLinhaContato(data) {
    const linhas = [...data]
        .map(
            ({
                CONCODIGO,
                CONNOME,
                CONEMAIL,
                CONSENHA,
                CONFONE,
                CONCELULAR,
                CONCARGO,
                CONSETOR,
                DATA_INCLUSAO,
            }) => `
		<tr id="contato-${CONCODIGO}">
            <td align="center">${exibirCheckboxEnviaremail(
                CONCODIGO,
                CONSENHA
            )}</td>
			<td align="left">${CONNOME}</td>
			<td align="left">${CONEMAIL}</td>
			<td align="left">${CONFONE}</td>
			<td align="left">${CONCELULAR}</td>
			<td align="left">${CONCARGO}</td>
			<td align="left">${CONSETOR}</td>
            <td align="center">${DATA_INCLUSAO}</td>
            <td style="text-align:center;width:220px">${exibirBotaoListaContato(
                CONCODIGO
            )}</td>
		</tr>`
        )
        .join("");

    return linhas;
}

function exibirCheckboxEnviaremail(concodigo, consenha) {
    let checkbox = "";
    if (consenha) {
        checkbox = `<input type="checkbox" name="vEnviarAcesso[]" value="${concodigo}">`;
    }
    return checkbox;
}

function exibirBotaoEnviarAcesso() {
    return `<tr>
                <td colspan="9" style="text-left">
                    <button type="button" title="Enviar Acesso" style="width:150px" onclick="enviarAcessos();"
                    class="btn btn-primary waves-effect waves-light">Enviar Acesso</button>
                </td>
            </tr>`;
}

function exibirBotaoListaContato(concodigo) {
    let botao = "";
    botao = ` <button type="button" class="mb-1 btn btn-secondary waves-effect" onclick="editarContato(${concodigo})"><i class="fas fa-edit font-16"></i></button>
            <button type="button" class="mb-1 ml-2 btn btn-danger waves-effect" style="color:white" onclick="excluirContato(${concodigo})"><i class="fas fa-trash-alt font-16"></i></button>`;

    return botao;
}

function exibirLinhaSemContato() {
    return `
		<tr>
			<td colspan="9" style="text-align:center;">Nenhum contato encontrado.</td>
		</tr>
	`;
}

function listarContatos(clicodigo) {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        url: "transaction/transactionContatos.php",
        data: {
            method: "listar-contatos",
            clicodigo: clicodigo,
        },
        success: function (response) {
            if (response.success) {
                $("#tbody_contatos").html(gerarLinhaContato(response.contatos));
                $("#tfoot_contatos").html(exibirBotaoEnviarAcesso());
            } else {
                $("#tbody_contatos").html(exibirLinhaSemContato());
                swal.fire("Oops...", response.message, "error");
            }
        },
        error: function (response) {
            swal.fire("Oops...", "Ocorreu um erro inesperado!", "error");
        },
    });
}

function enviarAcessos() {
    let codigos = [];
    $("input[type=checkbox][name='vEnviarAcesso[]']:checked").each(function (
        index,
        el
    ) {
        codigos.push($(this).val());
    });

    if (codigos.length > 0) {
        swal.fire({
            title: "Enviar E-mail Acesso",
            text:
                "Você Deseja Enviar o E-mail com o acesso para os contatos selecionados?",
            type: "info",
            showCancelButton: true,
            confirmButtonText: "Sim, enviar!",
            cancelButtonText: "Não, enviar!",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                Swal.showLoading();
                $.ajax({
                    url: "transaction/transactionContatos.php",
                    type: "POST",
                    async: false,
                    dataType: "json",
                    data: {
                        method: "enviarAcesso",
                        concodigos: codigos,
                    },
                    success: function (response) {
                        console.clear();
                        console.log(response);
                        if (response.success) {
                            swal("", response.msg, "success");
                        } else {
                            swal("", response.msg, "error");
                        }
                        $(
                            "input[type=checkbox][name='vEnviarAcesso[]']:checked"
                        ).prop("checked", false);
                    },
                    error: function (response) {
                        console.error({ response });
                        swal(
                            "",
                            "Ocorreu uma falha no envio dos E-mails",
                            "error"
                        );
                    },
                    // always: function () {
                    //     Swal.hideLoading();
                    //     // alert( "complete" );
                    // },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swal.fire(
                    "Cancelado",
                    "O registro não foi enviado :)",
                    "error"
                );
            }
        });
    } else {
        swal.fire("", "Marque ao menos um registro :)", "error");
    }
}

/**
 * Função que exibe o Formulário de Cadastro/Edição de Contato
 * @param int concodigo
 */
function exibirFormContato(concodigo) {
    if (concodigo) {
        editarContato(concodigo);
    } else {
        $("#vHMCONCODIGO").val("");
        $("#vHMCONNOME").val("");
        $("#vHMCONEMAIL").val("");
        $("#vHMCONFONE").val("");
        $("#vHMCONCELULAR").val("");
        $("#vHMCONCARGO").val("");
        $("#vHMCONSETOR").val("");
        $("#vHMCONSENHA").val("");
        $("#modalClientesxContatos").modal("show");
    }
}
/**
 * Função que busca os dados de um Registro de Contato
 * @param int concodigo
 */
function editarContato(concodigo) {
    $.ajax({
        async: true,
        type: "POST",
        dataType: "json",
        url: "transaction/transactionContatos.php",
        data: {
            method: "show-contato",
            concodigo: concodigo,
        },
        success: function (response) {
            let {
                CONCODIGO,
                CONNOME,
                CONEMAIL,
                CONFONE,
                CONCELULAR,
                CONCARGO,
                CONSETOR,
                CONSENHA,
            } = response;
            if (CONCODIGO && CONNOME && CONEMAIL) {
                $("#vHMCONCODIGO").val(CONCODIGO);
                $("#vHMCONNOME").val(CONNOME);
                $("#vHMCONEMAIL").val(CONEMAIL);
                $("#vHMCONFONE").val(CONFONE);
                $("#vHMCONCELULAR").val(CONCELULAR);
                $("#vHMCONCARGO").val(CONCARGO);
                $("#vHMCONSETOR").val(CONSETOR);
                $("#vHMCONSENHA").val(CONSENHA);
                $("#modalClientesxContatos").modal("show");
            }
        },
        error: function (response) {
            sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
            $("#modalClientesxContatos").modal("hide");
        },
    });
}

/**
 * Função que Inativa um registro de Contato
 * @param int concodigo
 */
function excluirContato(concodigo) {
    if (concodigo) {
        swal.fire({
            title: "Deseja excluir/inativar o(s) registro(s) selecionados?",
            text: "Excluir/Inativar registro(s)!",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, excluir!",
            cancelButtonText: "Não, cancelar!",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "transaction/transactionContatos.php",
                    data: {
                        method: "excluir-contato",
                        concodigo: concodigo,
                    },
                    async: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.registrosExcluidos > 0) {
                            swal.fire(
                                "Excluído!",
                                "Registro(s) excluído(s) com sucesso.",
                                "success"
                            );
                            $("#contato-" + concodigo).remove();

                            if ($("#tbody_contatos tr").length == 0) {
                                $("#tbody_contatos").html(
                                    exibirLinhaSemContato()
                                );
                            }
                        }
                    },
                    error: function (msg) {
                        swal.fire(
                            "Oops...",
                            "Ocorreu um erro inesperado! " + msg,
                            "error"
                        );
                        return false;
                    },
                });
            } else if (
                // Read more about handling dismissals
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swal.fire(
                    "Cancelado",
                    "O registro não foi excluído :)",
                    "error"
                );
            }
        });
    } else {
        swal.fire(
            "Ops..",
            "Selecione ao menos um registro para ser excluído :)",
            "error"
        );
    }
}
