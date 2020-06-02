$(document).ready(function () {
  if($("#vSAVIDESCRICAO").length > 0){
      tinymce.init({
          selector: "textarea#vSAVIDESCRICAO",
          theme: "modern",
          height:300,
          plugins: [
              "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
              "save table contextmenu directionality emoticons template paste textcolor"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
          style_formats: [
              {title: 'Bold text', inline: 'b'},
              {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
              {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
              {title: 'Example 1', inline: 'span', classes: 'example1'},
              {title: 'Example 2', inline: 'span', classes: 'example2'},
              {title: 'Table styles'},
              {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
          ]
      });
  }
});

function validarForm(){
    var vAErro = validaCamposForm().split("#");
	if(tinyMCE.get('vSAVIDESCRICAO').getBody().textContent == ''){
		var vSAlerta = "Erros ocorreram durante o envio de seu formul\xe1rio.\n\nPor favor, fa\xe7a as seguintes corre\xe7\xf5es:\n";
		vSAlerta += "<br/>* O campo Descrição deve ser preenchido.";
		Swal.fire('Opss..', '<br/>* O campo '+vSAlerta+' deve ser preenchido.', 'warning');
        return false;
	}
    if (vAErro[0] == 'S'){
		Swal.fire('Opss..', vAErro[1], 'warning');
        return false;
    } else
        document.forms[0].submit();
}