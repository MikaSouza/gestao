$(document).ready(function () {
  if($("#vSPXADESCRICAO").length > 0){
      tinymce.init({
          selector: "textarea#vSPXADESCRICAO",
          theme: "modern",
          height:100,
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

jQuery(document).ready(function($) {
	$("#add_atividade").on('click', function(event) {
		event.preventDefault();
		$tr_clonada = $("#table_atividades tbody tr").first().clone();
		$tr_clonada.find('input, select').each(function(index, el) {
			$(this).val('');
		});
		$tr_clonada.find('[name="vHAGESITUACAO[]"]').val('A');
		$tr_clonada.removeClass('modelo-atividade');
		$("#table_atividades").append($tr_clonada);
	});
});

jQuery(document).ready(function($) {
	$("#add_atividade2").on('click', function(event) {
		event.preventDefault();
		$tr_clonada = $("#table_atividades2").first().clone();
		$tr_clonada.find('input, select').each(function(index, el) {
			$(this).val('');
		});
		$tr_clonada.find('[name="vHAGESITUACAO[]"]').val('A');
		$tr_clonada.removeClass('modelo-atividade');
		$("#table_atividades2").append($tr_clonada);
	});
});