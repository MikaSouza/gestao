$(document).ready(function () {
    if ($("#vSOXTDESCRICAO").length > 0) {
        tinymce.init({
            selector: "textarea#vSOXTDESCRICAO",
            theme: "modern",
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor",
            ],
            toolbar:
                "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
            style_formats: [
                { title: "Bold text", inline: "b" },
                {
                    title: "Red text",
                    inline: "span",
                    styles: { color: "#ff0000" },
                },
                {
                    title: "Red header",
                    block: "h1",
                    styles: { color: "#ff0000" },
                },
                { title: "Example 1", inline: "span", classes: "example1" },
                { title: "Example 2", inline: "span", classes: "example2" },
                { title: "Table styles" },
                { title: "Table row 1", selector: "tr", classes: "tablerow1" },
            ],
        });
    }
});

// function validarForm() {
//     var vAErro = validaCamposForm().split("#");
//     if (tinyMCE.get("vSOXTDESCRICAO").getBody().textContent == "") {
//         var vSAlerta =
//             "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
//         vSAlerta += "<br/>* O campo Descrição deve ser preenchido.";
//         Swal.fire(
//             "Opss..",
//             "<br/>* O campo " + vSAlerta + " deve ser preenchido.",
//             "warning"
//         );
//         return false;
//     }
//     if (vAErro[0] == "S") {
//         Swal.fire("Opss..", vAErro[1], "warning");
//         return false;
//     } else document.forms[0].submit();
// }

function buscarAnexos(oxtcodigo, mencodigo) {
    if (oxtcodigo && mencodigo) {
        $.ajax({
            type: "POST",
            url: "transaction/transactionOrientacaoTecnicaxGED.php",
            data: {
                hdn_metodo_search: "search-anexos",
                oxtcodigo: oxtcodigo,
                mencodigo: mencodigo,
            },
            async: false,
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    $("#tbody_ged").html(gerarLinhaArquivos(response));
                    $("#tfoot_ged").html("");
                    $("#datatable-buttons").removeClass("d-none");
                } else {
                    $("#tbody_ged").html("");
                    $("#tfoot_ged").html(setarLinhaVazia());
                    $("#datatable-buttons").removeClass("d-none");
                }
            },
            complete: function () {},
        });
    }
}

function gerarLinhaArquivos(data) {
    const linhas = [...data]
        .map(
            ({
                GEDCODIGO,
                DATA_INCLUSAO,
                GEDTIPO,
                USUNOME,
                GEDNOMEARQUIVO,
                LINK,
            }) => `
		<tr>
            <td align="center">${DATA_INCLUSAO}</td>
            <td align="left">${USUNOME}</td>
            <td align="left">${GEDTIPO}</td>
            <td align="left">${GEDNOMEARQUIVO}</td>
			<td align="left">${LINK}</td>
			<td align="left">
				<a title="Excluir Anexo" class="btn btn-danger text-white waves-effect" onclick="excluirAnexoOT('${GEDCODIGO}')"><i class="fas fa-trash-alt font-16"></i></a>
			</td>
		</tr>`
        )
        .join("");

    return linhas;
}

function setarLinhaVazia() {
    return `<tr><th colspan="6" class="text-center">Nenhum anexo encontrado.</th></tr>`;
}

function excluirAnexoOT(gedcodigo) {
    let oxtcodigo = $("#vIOXTCODIGO").val();
    let mencodigo = $("#vHMENCODIGO").val();
    if (gedcodigo) {
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
                    dataType: "json",
                    url: "transaction/transactionOrientacaoTecnicaxGED.php",
                    data: {
                        gedcodigo: gedcodigo,
                        method: "excluir-anexo",
                    },
                    success: function (response) {
                        console.log(response.registrosExcluidos);
                        if (response.registrosExcluidos > 0) {
                            swal.fire(
                                "Excluído!",
                                "Registro(s) excluído(s) com sucesso.",
                                "success"
                            );
                            buscarAnexos(oxtcodigo, mencodigo);
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
