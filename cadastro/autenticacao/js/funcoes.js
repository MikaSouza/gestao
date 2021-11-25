function validarForm(){
    var vAErro = validaCamposForm().split("#");
    if (vAErro[0] == 'S'){
		Swal.fire('Opss..', vAErro[1], 'warning');
		return false;
    } else
       return true;
}

//valida campos formulário automatizado
function validaCamposForm() {
    var vSAlerta = "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
    var vSErro = 'N';
    var elements = document.getElementsByClassName('obrigatorio');

    for(var i = 0; i < elements.length; i++) {
        if ((elements[i].tagName == "INPUT") || (elements[i].tagName == "SELECT") || (elements[i].tagName == "TEXTAREA")) {
            if(elements[i].title != "") {
                if(!elements[i].value || elements[i].value == "0") {
                    var vSErro = 'S';
                    vSAlerta += "<br/>* O campo "+elements[i].title+" deve ser preenchido.";
                }
            }
         }
    }
    return vSErro+"#"+vSAlerta;
}

function enviarEmail(){
    var vAErro = validaCamposForm().split("#");
    if (vAErro[0] == 'S'){
		Swal.fire('Opss..', vAErro[1], 'warning');
		return false;
    }else{

        var data = {
            method: "enviarEmail",
            Login: $("#vSLoginRecuperar").val()
        };

        console.log(data);

        $.ajax({
            async: true,
            type: "POST",
            url: "transaction/transactionRecuperarLogin.php",
            data: data,
            success: function(msg){

                var json = JSON.parse(msg);
                console.log(json);
                swal({title : "", text :"Foi enviado uma mensagem para o e-mail cadastrado com a nova senha de acesso!", type : "success"});                

            },
            error: function(msg) {
            }
        });
    }
}