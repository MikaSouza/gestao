<!-- Sweet-Alert  -->
<script src="../assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
<script src="../assets/pages/jquery.sweet-alert.init.js"></script>

<!-- App js -->
<script src="../assets/js/app.js"></script>

<?php if ($vSName != 'indexCliente') : ?>
	<!-- Start of  Zendesk Widget script -->
	<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=af96b062-0118-4830-8057-059fc7ceca25"> </script>
	<!-- End of  Zendesk Widget script -->
<?php endif; ?>

<!-- Funcoes js -->
<script src="../assets/js/funcoes.js"></script>
<!-- MaskedInput -->
<script type="text/javascript" src="../assets/plugins/jquery.maskedinput/jquery.maskedinput.min.js"></script>

<?php if ($_GET['method'] == 'consultar' || $vSDefaultStatusCad == "N") { ?>
	<script type="text/javascript" DEFER="DEFER">
		$(function() {
			//Desabilita todos os campos
			$("input").prop("disabled", "true");
			$("input:hidden").attr("disabled", false);
			$("select").prop("disabled", "true");
			$("textarea").prop("disabled", "true");
			<?php if ($vSDefaultStatusCad == "N" && $_GET['method'] != 'consultar') { ?>
				$("#vS<?php echo $vAConfiguracaoTela['MENPREFIXO']; ?>STATUS").attr('disabled', false);
			<?php } ?>
		});
	</script>

<?php } ?>