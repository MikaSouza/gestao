function enviarAcessos() {
    let usuarios = [];
    $("input[type=checkbox][name='vEnviarAcesso[]']:checked").each(function (
        index,
        el
    ) {
        usuarios.push($(this).val());
    });

    if (usuarios.length > 0) {
        swal.fire({
            title: "Enviar E-mail Acesso",
            text:
                "Você Deseja Enviar o E-mail com o acesso para os usuários selecionados?",
            type: "info",
            showCancelButton: true,
            confirmButtonText: "Sim, enviar!",
            cancelButtonText: "Não, enviar!",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:
                        "transaction/transactionUsuario.php?metodo=enviarAcesso",
                    type: "POST",
                    async: false,
                    dataType: "json",
                    data: {
                        vAUSUCODIGO: usuarios,
                    },
                    success: function (response) {
                        console.log({ response });
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
