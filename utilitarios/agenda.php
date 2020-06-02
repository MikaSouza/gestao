<?php
    require_once __DIR__.'/../../include/constantes.php';
    require_once __DIR__.'/../../transaction/transactionUsuario.php';
	require_once __DIR__.'/../../transaction/transactionAtividade.php';
?>
<!-- exibir -->
<div id="eventContent" title="Event Details" style="display:none;"> 
    <b>Título</b>: <span id="pSAGETITULO"></span><br>
    <b>Cliente</b>: <span id="pSCLIENTE"></span><br>
	<b>Contato</b>: <span id="pSCONTATO"></span><br>
    <b>Vínculo</b>: <span id="pSVINCULO"></span><br>
    <b>Responsável</b>: <span id="pSAGERESPONSAVEL"></span><br>
    <b>Tipo de Atividade</b>: <span id="pSAGETIPO"></span><br>
    <b>Início</b>: <span id="startTime"></span><br>
    <b>Término</b>: <span id="endTime"></span><br>
    <b>Concluído</b>: <span id="pSAGECONCLUIDO"></span><br>
    <b>Descrição</b>: <p id="pSAGEDESCRICAO"></p><br>
    <?php if (verificarAcessoCadastroNoRedirect(379, 'update')): ?>
		<button id="eventLink" class="btnGenericoFilho" title="Editar Atividade"><b>Editar Atividade</b></button>&nbsp;&nbsp;&nbsp;&nbsp;     
    <?php endif; ?>
    <?php if (verificarAcessoCadastroNoRedirect(379, 'excluir')): ?>
		<button id="deleteEvent" class="btnGenericoFilho" title="Excluir Atividade"><b>Excluir Atividade</b></button><br>       
    <?php endif; ?><br><br> 
</div>

<!-- inserir/editar -->
<div id="insertEvent" title="Inserir Atividade" style="display: none;" class="ui-front" data-user_id="<?php echo $_SESSION['SI_USUCODIGO']; ?>">
	<fieldset style="width: 400px;">
		<legend>Dados Cliente:</legend>
			<span>Cliente</span><br />
			<input type="text" name="vHCLIENTE" id="vHCLIENTE" style="width:390px;" title="Cliente" /><br />
			<input type="hidden" name="vICLICODIGO" id="vICLICODIGO">
			
			<div id="divContato"></div>
		</fieldset>
    <span>Título</span><br />
    <input type="text" name="vSAGETITULO" id="vSAGETITULO" style="width:400px;" class="divObrigatorio" title="Título"/><br />    

	<span>Tipo de Atividade</span><br />
	<select name="vIAGETIPO" id="vIAGETIPO" style="width:410px;" class="obrigatorio" title="Tipo de Atividade">
		<?php
			foreach (comboAtividade() as $cbAtividade):
				if ($cbAtividade['ATICODIGO'] == $vROBJETO['AGETIPO']): ?>
					<option selected value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
				<?php else: ?>
					<option value="<?php echo $cbAtividade['ATICODIGO'] ?>"><?php echo $cbAtividade['ATINOME'] ?></option>
				<?php
					endif;
			endforeach;
		?>
	</select><br />

    <span>Descrição</span><br />
    <textarea name="vSAGEDESCRICAO" id="vSAGEDESCRICAO" title="Descrição" class="divObrigatorio" style="width:400px;" cols="40" rows="6"></textarea><br />

    <span>Responsável</span><br />
    <select name="vIAGERESPONSAVEL" id="vIAGERESPONSAVEL" style="width:340px;" class="divObrigatorio" title="Responsável">
        <?php foreach (comboUsuarios() as $usuario): ?>
            <?php if ($usuario['USUCODIGO'] == $_SESSION['SI_USUCODIGO']): ?>
                <option value="<?php echo $usuario['USUCODIGO'] ?>" selected><?php echo $usuario['USUNOME'] ?></option>
            <?php else: ?>
                <option value="<?php echo $usuario['USUCODIGO'] ?>"><?php echo $usuario['USUNOME'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select><br />    

    <span>Concluído</span><br />
    <select name="vSAGECONCLUIDO" id="vSAGECONCLUIDO" style="width:340px;" class="divObrigatorio" title="Concluído">
        <option value="N">Não</option>
        <option value="S">Sim</option>
    </select><br />

    <fieldset style="width: 400px;">
        <legend>Datas</legend>
        <div class="floatleft" style="display: inline-block;">
            <span>Data Início</span><br />
            <input type="text" name="vDAGEDATAINICIO" id="vDAGEDATAINICIO" class="divObrigatorio classdatepicker" title="Data Início"
            style="width:75px;" />
            <br />
        </div>
        <div class="floatleft" style="display: inline-block;">
            <span>Hora Início</span><br />
            <input type="text" name="vSAGEHORAINICIO" id="vSAGEHORAINICIO" class="divObrigatorio" title="Hora Início" onkeypress="return digitos(event, this);" onkeydown="formatarHora(this,20,event,2)" maxlength="5" style="width:75px;" />
            <br />
        </div>        
        <div style="float:left;display: inline-block;">
            <span>Data Final</span><br />
            <input type="text" name="vDAGEDATAFINAL" id="vDAGEDATAFINAL" class="divObrigatorio classdatepicker" title="Data Final" style="width:75px;"/>
            <br />
        </div>
        <div style="float:left;display: inline-block;">
            <span>Hora Final</span><br />
            <input type="text" name="vSAGEHORAFINAL" id="vSAGEHORAFINAL" class="divObrigatorio" title="Hora Final" onkeypress="return digitos(event, this);" onkeydown="formatarHora(this,4,event,2)" maxlength="5" style="width:75px;">
            <br />
        </div>
    </fieldset>
    <input type="hidden" name="vIAGECODIGO" id="vIAGECODIGO">
</div>
