function excluirRegistroGrid(pOID, pSTransaction, pSMethod, pIOIDMENU, pSList) {
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
                async: false,
                type: "POST",
                url: "transaction/" + pSTransaction,
                data: {
                    pSCodigos: pOID,
                    method: pSMethod,
                    vIOIDMENU: pIOIDMENU,
                },
                success: function (msg) {
                    swal.fire("Excluído!", msg, "success");

                    if (pSList) {
                        window.location.href = pSList;
                    } else {
                        window.location.reload();
                    }

                    return true;
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
            swal.fire("Cancelado", "O registro não foi excluído :)", "error");
        }
    });
}

function excluirRegistroGridCapa(
    pOID,
    pSTransaction,
    pSMethod,
    pIOIDMENU,
    pSList
) {
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
                async: false,
                type: "POST",
                url: pSTransaction,
                data: {
                    pSCodigos: pOID,
                    method: pSMethod,
                    vIOIDMENU: pIOIDMENU,
                },
                success: function (msg) {
                    swal.fire("Excluído!", msg, "success");

                    if (pSList) {
                        window.location.href = pSList;
                    } else {
                        window.location.reload();
                    }

                    return true;
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
            swal.fire("Cancelado", "O registro não foi excluído :)", "error");
        }
    });
}

function excluirRegistroGridFilha(
    pOID,
    pSTransaction,
    pSMethod,
    pSDivRetorno,
    pID_PAI,
    pSFuncaoRetorno
) {
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
                async: false,
                type: "POST",
                url: "transaction/" + pSTransaction,
                data: {
                    pSCodigos: pOID,
                    method: pSMethod,
                },
                success: function (msg) {
                    swal.fire("Excluído!", msg, "success");

                    gerarGridJSON(
                        pSTransaction,
                        pSDivRetorno,
                        pSFuncaoRetorno,
                        pID_PAI
                    );

                    return true;
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
            swal.fire("Cancelado", "O registro não foi excluído :)", "error");
        }
    });
}
function validarForm() {
    var vAErro = validaCamposForm().split("#");
    if (vAErro[0] == "S") {
        Swal.fire("Opss..", vAErro[1], "warning");
        return false;
    } else document.forms[0].submit();
}

//valida campos formulário automatizado
function validaCamposForm() {
    var vSAlerta =
        "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
    var vSErro = "N";
    var elements = document.getElementsByClassName("obrigatorio");

    for (var i = 0; i < elements.length; i++) {
        if (
            elements[i].tagName == "INPUT" ||
            elements[i].tagName == "SELECT" ||
            elements[i].tagName == "TEXTAREA"
        ) {
            if (elements[i].title != "") {
                if (!elements[i].value || elements[i].value == "0") {
                    var vSErro = "S";
                    vSAlerta +=
                        "<br/>* O campo " +
                        elements[i].title +
                        " deve ser preenchido.";
                }
            }
        }
    }
    return vSErro + "#" + vSAlerta;
}

function mascara(tipo, campo, teclaPress) {
    if (window.event) {
        var tecla = teclaPress.keyCode;
    } else {
        tecla = teclaPress.which;
    }
    var s = new String(campo.value);
    // Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string denovo.
    s = s.replace(/(\.|\(|\)|\/|\-| )+/g, "");
    tam = s.length + 1;
    if (tecla != 9 && tecla != 8) {
        switch (tipo) {
            case "CPF":
                if (tam > 3 && tam < 7)
                    campo.value = s.substr(0, 3) + "." + s.substr(3, tam);
                if (tam >= 7 && tam < 10)
                    campo.value =
                        s.substr(0, 3) +
                        "." +
                        s.substr(3, 3) +
                        "." +
                        s.substr(6, tam - 6);
                if (tam >= 10 && tam < 12)
                    campo.value =
                        s.substr(0, 3) +
                        "." +
                        s.substr(3, 3) +
                        "." +
                        s.substr(6, 3) +
                        "-" +
                        s.substr(9, tam - 9);
                break;

            case "CNPJ":
                if (tam > 2 && tam < 6)
                    campo.value = s.substr(0, 2) + "." + s.substr(2, tam);
                if (tam >= 6 && tam < 9)
                    campo.value =
                        s.substr(0, 2) +
                        "." +
                        s.substr(2, 3) +
                        "." +
                        s.substr(5, tam - 5);
                if (tam >= 9 && tam < 13)
                    campo.value =
                        s.substr(0, 2) +
                        "." +
                        s.substr(2, 3) +
                        "." +
                        s.substr(5, 3) +
                        "/" +
                        s.substr(8, tam - 8);
                if (tam >= 13 && tam < 15)
                    campo.value =
                        s.substr(0, 2) +
                        "." +
                        s.substr(2, 3) +
                        "." +
                        s.substr(5, 3) +
                        "/" +
                        s.substr(8, 4) +
                        "-" +
                        s.substr(12, tam - 12);
                break;
            case "TEL":
                if (tam <= 11) {
                    if (tam > 2 && tam < 4)
                        campo.value =
                            "(" + s.substr(0, 2) + ")" + s.substr(2, tam);
                    if (tam >= 7 && tam < 11)
                        campo.value =
                            "(" +
                            s.substr(0, 2) +
                            ")" +
                            s.substr(2, 4) +
                            "-" +
                            s.substr(6, tam - 6);
                }
                break;
            case "DATA":
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + "/" + s.substr(2, tam);
                if (tam > 4 && tam < 11)
                    campo.value =
                        s.substr(0, 2) +
                        "/" +
                        s.substr(2, 2) +
                        "/" +
                        s.substr(4, tam - 4);
                break;
            case "CEP":
                if (tam > 5 && tam < 7)
                    campo.value = s.substr(0, 5) + "-" + s.substr(5, tam);
                break;
            case "HORA":
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + ":" + s.substr(2, tam);
                break;
            case "SEGUNDOS":
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + ":" + s.substr(2, tam);
                if (tam > 5 && tam < 7)
                    campo.value =
                        s.substr(0, 2) +
                        ":" +
                        s.substr(3, 2) +
                        ":" +
                        s.substr(6, 2);
                break;
            case "MINUTOS":
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + ":" + s.substr(2, tam);
                break;
            case "CEL":
                if (tam <= 12) {
                    if (tam > 2 && tam < 4)
                        campo.value =
                            "(" + s.substr(0, 2) + ")" + s.substr(2, tam);
                    if (tam >= 7 && tam < 12) {
                        campo.value =
                            "(" +
                            s.substr(0, 2) +
                            ")" +
                            s.substr(2, 4) +
                            "-" +
                            s.substr(6, tam - 6);
                        console.info(s.substr(2, 4));
                    }
                }
                break;
        }
    }
}

//--->Função para verificar se o valor digitado é número...<---
//http://www.acasadojava.com.br/tabela-ascii.aspx

function digitos(event) {
    if (window.event) {
        // IE
        key = event.keyCode;
    } else if (event.which) {
        // netscape
        key = event.which;
    }

    if (key != 8 || key != 13 || key < 48 || key > 57)
        return (key > 47 && key < 58) || key == 8 || key == 13;

    return true;
    //return false;
}
/*
$(".classmonetario").attr('maxlength', '15');
$(".classmonetario").keypress(function(event) {
	return digitos(event, this);
});
$(".classmonetario").keydown(function(event) {
	formatarMoeda(this,20,event,2)
});*/

$(".classmonetario").attr("maxlength", "15");
$(".classmonetario").keypress(function (event) {
    return moedaDoisDecimais2(this, ".", ",", event);
});

function formatarMoeda(campo, tammax, teclapres, decimal) {
    var tecla = teclapres.keyCode;
    var vr = Limpar(campo.value, "0123456789");
    var tam = vr.length;
    var dec = decimal;

    if (tam < tammax && tecla != 8) {
        tam = vr.length + 1;
    }

    if (tecla == 8) {
        tam = tam - 1;
    }

    if (
        tecla == 8 ||
        (tecla >= 48 && tecla <= 57) ||
        (tecla >= 96 && tecla <= 105)
    ) {
        if (tam <= dec) {
            campo.value = vr;
        }
        if (tam > dec && tam <= 5) {
            campo.value =
                vr.substr(0, tam - 2) + "," + vr.substr(tam - dec, tam);
        }
        if (tam >= 6 && tam <= 8) {
            campo.value =
                vr.substr(0, tam - 5) +
                "." +
                vr.substr(tam - 5, 3) +
                "," +
                vr.substr(tam - dec, tam);
        }
        if (tam >= 9 && tam <= 11) {
            campo.value =
                vr.substr(0, tam - 8) +
                "." +
                vr.substr(tam - 8, 3) +
                "." +
                vr.substr(tam - 5, 3) +
                "," +
                vr.substr(tam - dec, tam);
        }
        if (tam >= 12 && tam <= 14) {
            campo.value =
                vr.substr(0, tam - 11) +
                "." +
                vr.substr(tam - 11, 3) +
                "." +
                vr.substr(tam - 8, 3) +
                "." +
                vr.substr(tam - 5, 3) +
                "," +
                vr.substr(tam - dec, tam);
        }
        if (tam >= 15 && tam <= 17) {
            campo.value =
                vr.substr(0, tam - 14) +
                "." +
                vr.substr(tam - 14, 3) +
                "." +
                vr.substr(tam - 11, 3) +
                "." +
                vr.substr(tam - 8, 3) +
                "." +
                vr.substr(tam - 5, 3) +
                "," +
                vr.substr(tam - 2, tam);
        }
    }
}
function Limpar(valor, validos) {
    // retira caracteres invalidos da string
    var result = "";
    var aux;
    for (var i = 0; i < valor.length; i++) {
        aux = validos.indexOf(valor.substring(i, i + 1));
        if (aux >= 0) {
            result += aux;
        }
    }
    return result;
}

function gerarGridJSON(pSTransaction, pSDivReturn, pMetodo, pIOID) {
    $.ajax({
        type: "POST",
        url: "transaction/" + pSTransaction,
        data: {
            hdn_metodo_search: pMetodo,
            pIOID: pIOID,
        },
        async: true,
        success: function (vSReturn) {
            // console.log(vSReturn);

            $("#" + pSDivReturn).html(vSReturn);
        },
        complete: function () {
            //disabled.prop('disabled', true);
        },
    });
}

function gerarGridJSONGED(
    pSTransaction,
    pSDivReturn,
    pMetodo,
    pIOID,
    pIOIDMENU
) {
    $.ajax({
        type: "POST",
        url: "transaction/" + pSTransaction,
        data: {
            hdn_metodo_search: pMetodo,
            pIOID: pIOID,
            pIOIDMENU: pIOIDMENU,
        },
        async: true,
        success: function (vSReturn) {
            $("#" + pSDivReturn).html(vSReturn);
        },
        complete: function () {
            //disabled.prop('disabled', true);
        },
    });
}

function exibirFormModal(idFilho, div_nome, titulo) {
    if (idFilho != "") {
        $("#hdn_filho_" + titulo).val(idFilho);
        if (titulo === "ClientesxHistorico") {
            fillClientesxHistorico(idFilho, titulo);
        } else if (titulo == "ContasReceberxParcelasVinculo") {
            fillContasReceberxParcelasVinculo(idFilho, titulo);
        } else if (titulo == "ProspeccaoxAgenda") {
            fillProspeccaoxAgenda(idFilho, titulo);
        } else if (titulo == "UsuariosxBeneficios") {
            fillUsuariosxBeneficios(idFilho, titulo);
        } else if (titulo == "UsuariosxValesTransporte") {
            fillUsuariosxValesTransporte(idFilho, titulo);
        } else if (titulo == "UsuariosxContasBancarias") {
            fillUsuariosxContasBancarias(idFilho, titulo);
        } else if (titulo == "UsuariosxDocumentoPendente") {
            fillUsuariosxDocumentoPendente(idFilho, titulo);
        } else if (titulo == "UsuariosxEscolaridade") {
            fillUsuariosxEscolaridade(idFilho, titulo);
        } else if (titulo == "UsuariosxFeedback") {
            fillUsuariosxFeedback(idFilho, titulo);
        } else if (titulo == "UsuariosxFerias") {
            fillUsuariosxFerias(idFilho, titulo);
        } else if (titulo == "UsuariosxRemuneracao") {
            fillUsuariosxRemuneracao(idFilho, titulo);
        } else if (titulo == "ClientesxContatos") {
            fillContatos(idFilho);
        } else if (titulo == "ClientesxEnderecos") {
            fillEndereco(idFilho);
        }
    } else {
        var idPai = $("#hdn_pai_" + titulo).val();
        limparCamposDialog(div_nome);
        $("#hdn_pai_" + titulo).val(idPai);
    }
    $("#modal" + titulo).modal("show");
}

function validarCamposDiv(div_nome) {
    var vSAlerta =
        "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n\n";
    var mensagens_erro = [];
    $("#" + div_nome)
        .find(".divObrigatorio")
        .each(function () {
            if (
                $(this).val() === "" &&
                typeof $(this).attr("title") !== undefined &&
                typeof $(this).attr("title") !== "undefined"
            ) {
                if (vSAlerta != "") {
                    mensagens_erro.push(vSAlerta);
                    vSAlerta = "";
                }
                mensagens_erro.push(
                    "\n* O campo " +
                        $(this).attr("title") +
                        " deve ser preenchido."
                );
            }
        });
    return mensagens_erro;
}
function limparCamposDialog(div_nome) {
    $("#" + div_nome)
        .find("input, select, textarea")
        .each(function () {
            $(this).val("");
        });
}

//Marcar Todos checkbox conforme Nome
function marcarTodosColuna(form, vSnome) {
    //var form1 = document.getElementById(form);
    var formElements = document.forms[form].elements;

    for (i = 0; i < formElements.length; i++) {
        if (
            formElements[i].type == "checkbox" &&
            formElements[i].checked == 0 &&
            formElements[i].name == vSnome
        ) {
            formElements[i].checked = 1;
        } else if (
            formElements[i].type == "checkbox" &&
            formElements[i].checked == 1 &&
            formElements[i].name == vSnome
        ) {
            formElements[i].checked = 0;
        }
    }
}

$(function () {
    $(".classnumerico").attr("maxlength", "11");
    $(".classnumerico").keypress(function (event) {
        return digitos(event, this);
    });
});

$(function () {
    $(".classnumericobig").attr("maxlength", "20");
    $(".classnumericobig").keypress(function (event) {
        return digitos(event, this);
    });
});

/*
$('#dataTable').dataTable({
	"columnDefs": [
		{
		"orderable": false,
		"targets": 0
		}
	]
});*/

//FORMATA MOEDA 5 CASAS DECIMAIS
function formatarMoeda5Casas(o, f) {
    v_obj = o;
    v_fun = f;
    setTimeout("execmascara()", 1);
}
function execmascara() {
    v_obj.value = v_fun(v_obj.value);
}
function mvalor(v) {
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/(\d)(\d{13})$/, "$1.$2"); //coloca o ponto dos bilhões
    v = v.replace(/(\d)(\d{10})$/, "$1.$2"); //coloca o ponto dos milhões
    v = v.replace(/(\d)(\d{7})$/, "$1.$2"); //coloca o ponto dos milhares
    v = v.replace(/(\d)(\d{5})$/, "$1,$2"); //coloca a virgula antes dos 4 últimos dígitos
    return v;
}

function formatar_valor_monetario_banco(pSValor) {
    vSNewValor = pSValor.replace(".", "");
    vSNewValor = vSNewValor.replace(",", ".");
    return vSNewValor;
}

function number_formatJ(number, decimals, dec_point, thousands_sep) {
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

function formatarHora(campo, tammax, teclapres, decimal) {
    var tecla = teclapres.keyCode;
    var vr = Limpar(campo.value, "0123456789");
    var tam = vr.length;
    var dec = decimal;

    if (tam < tammax && tecla != 8) {
        tam = vr.length + 1;
    }

    if (tecla == 8) {
        tam = tam - 1;
    }

    if (
        tecla == 8 ||
        (tecla >= 48 && tecla <= 57) ||
        (tecla >= 96 && tecla <= 105)
    ) {
        if (tam <= dec) {
            campo.value = vr;
        }
        if (tam > dec && tam <= 5) {
            campo.value =
                vr.substr(0, tam - 2) + ":" + vr.substr(tam - dec, tam);
        }
        if (tam >= 6 && tam <= 8) {
            campo.value =
                vr.substr(0, tam - 5) +
                "" +
                vr.substr(tam - 5, 3) +
                ":" +
                vr.substr(tam - dec, tam);
        }
    }
}

function moedaDoisDecimais2(a, e, r, t) {
    let n = "",
        h = (j = 0),
        u = (tamanho2 = 0),
        l = (ajd2 = ""),
        o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o) return !0;
    if (((n = String.fromCharCode(o)), -1 == "0123456789".indexOf(n)))
        return !1;
    for (
        u = a.value.length, h = 0;
        h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r);
        h++
    );
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) &&
            (l += a.value.charAt(h));
    if (
        ((l += n),
        0 == (u = l.length) && (a.value = ""),
        1 == u && (a.value = "0" + r + "0" + l),
        2 == u && (a.value = "0" + r + l),
        u > 2)
    ) {
        for (ajd2 = "", j = 0, h = u - 3; h >= 0; h--)
            3 == j && ((ajd2 += e), (j = 0)), (ajd2 += l.charAt(h)), j++;
        for (
            a.value = "", tamanho2 = ajd2.length, h = tamanho2 - 1;
            h >= 0;
            h--
        )
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u);
    }
    return !1;
}

function salvarModalTabelaPadrao() {
    var erros = validarCamposDiv("modal_div_tabela_padrao");

    if (erros.length === 0) {
        var data = {
            method: "insertTabelasPadrao",
            vITABCODIGO: $("#vHTABCODIGODIALOG").val(),
            vSTABTIPO: $("#vHTABTIPODIALOG").val(),
            vSTABDESCRICAO: $("#vHTABDESCRICAODIALOG").val(),
        };
        $.ajax({
            async: true,
            type: "POST",
            url: "../cadastro/transaction/transactionTabelas.php",
            data: data,
            success: function (vIOID) {
                swal({
                    title: "",
                    text: "Cadastro realizado com sucesso",
                    type: "success",
                });
                var pAParameters = {
                    vSTitulo: $("#vHTABPARTITULO").val(),
                    vSTabTipo: $("#vHTABTIPODIALOG").val(),
                    vSCampo: $("#vHTABPARNAME").val(),
                    vIValor: vIOID,
                    vSDiv: $("#vHTABPARDIV").val(),
                    vSObrigatorio: "",
                    pSMethod: "",
                    vSCallback: "",
                };

                combo_padrao_tabelas(pAParameters);
                $("#ModalTabelaPadrao").modal("hide");
                return true;
            },
            error: function (msg) {
                limparCamposDialog("modal_div_tabela_padrao");
                sweetAlert("Oops...", "Ocorreu um erro inesperado!", "error");
                alert(msg);
                $("#ModalTabelaPadrao").modal("hide");
                return false;
            },
        });
    } else {
        swal({ title: "Opss..", text: erros.join("\n"), type: "warning" });
    }
}

function combo_padrao_tabelas(pAParameters) {
    jQuery.ajax({
        async: true,
        type: "POST",
        url: "../cadastro/combos/comboGenericoTabela.php",
        data: {
            hdn_metodo_combo: "comboTabela",
            pSCAMPO: pAParameters["vSCampo"],
            pSTITULO: pAParameters["vSTitulo"],
            pSTABTIPO: pAParameters["vSTabTipo"],
            pIOID: pAParameters["vIValor"],
            pSControles: pAParameters["vSControles"],
            pSOBRIGATORIO: pAParameters["vSObrigatorio"],
            pSDiv: pAParameters["vSDiv"],
            pSClasse: pAParameters["vSClasse"],
            pSCallback: pAParameters["vSCallback"],
            pSControles: pAParameters["vSControles"],
            pSMethod: pAParameters["vSMethod"],
            pSDESABILITAR: pAParameters["vSDesabilitar"],
            pStyle: pAParameters["vSStyle"],
        },
        success: function (resposta) {
            $("#" + pAParameters["vSDiv"]).html(resposta);
            return true;
        },
        error: function (msg) {
            sweetAlert(
                "Oops...",
                "Ocorreu um erro inesperado! " + msg,
                "error"
            );
            return false;
        },
    });
}

// COMBO TABELAS GLOBAL
function exibirFormModalIncluirAlterarTipoTabela(pAParameters) {
    var vITABCODIGO = $("#" + pAParameters["vSCampo"]).val();
    if (
        pAParameters["pSMethod"] == "alterar" ||
        pAParameters["pSMethod"] == "desativar"
    ) {
        if (!vITABCODIGO) {
            sweetAlert(
                "Atenção",
                "O campo " +
                    pAParameters["vSTitulo"] +
                    " deve ser selecionado. ",
                "warning"
            );
            return false;
        } else {
            if (pAParameters["pSMethod"] == "alterar") {
                var vIItemSelecionado = $(
                    "#" + pAParameters["vSCampo"] + " option:selected"
                );
                $("#vHTABCODIGODIALOG").val(
                    $("#" + pAParameters["vSCampo"]).val()
                );
                $("#vHTABDESCRICAODIALOG").val(vIItemSelecionado.text());
                $("#vHTABTIPODIALOG").val(pAParameters["vSTabTipo"]);

                $("#vHTABPARTITULO").val(pAParameters["vSTitulo"]);
                $("#vHTABPARNAME").val(pAParameters["vSCampo"]);
                $("#vHTABPARDIV").val(pAParameters["vSDiv"]);

                $("#ModalTabelaPadrao").modal("show");
            } else if (pAParameters["pSMethod"] == "desativar") {
                swal.fire({
                    title: "Deseja excluir/inativar o registro selecionado?",
                    text: "Excluir/Inativar registro!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: "Não, cancelar!",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            async: false,
                            type: "POST",
                            url:
                                "../cadastro/transaction/transactionTabelas.php",
                            data: {
                                pSCodigos: vITABCODIGO,
                                method: "excluirPadrao",
                                vIOIDMENU: 18,
                            },
                            success: function (msg) {
                                swal.fire("Excluído!", msg, "success");
                                combo_padrao_tabelas(pAParameters);
                                return true;
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
            }
        }
    } else {
        var vITABCODIGO = "";
        $("#vHTABCODIGODIALOG").val("");
        $("#vHTABDESCRICAODIALOG").val("");
        $("#vHTABTIPODIALOG").val(pAParameters["vSTabTipo"]);

        $("#vHTABPARTITULO").val(pAParameters["vSTitulo"]);
        $("#vHTABPARNAME").val(pAParameters["vSCampo"]);
        $("#vHTABPARDIV").val(pAParameters["vSDiv"]);

        $("#ModalTabelaPadrao").modal("show");
    }
}
