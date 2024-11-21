<?php
		require("../xpanel/config.php");
		$registros = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['id']."')");
		?>
<form action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']."&accion=updateuser"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
	<div class="tabbable">
		<input type="hidden" name="id_usuario" value="<?php echo($registros["ID_USUARIO"]); ?>" />

		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab01">Datos a actualizar</a></li>
		</ul>

		<div class="tab-content">
			<div id="tab01" class="active" style="min-height: 60px;">
				<div class="control-group">
					<label class="control-label">Nombres y Apellidos</label>
					<div class="controls">
						<input type="text" size="50" maxlength="255" value="<?php echo($registros["NOMBRE_COMPLETO"]); ?>" readonly="readonly" style="width: 99%;" />
					</div>
				</div>
				<div class="control-group" style="display: none;">
					<label class="control-label">Contrase&ntilde;a actual</label>
					<div class="controls">
						<input type="text" size="50" maxlength="255" value="<?php echo($registros["CLAVE"]); ?>" disabled="disabled" />	
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nueva Contrase&ntilde;a</label>
					<div class="controls">
						<input type="password" id="clave" name="clave" size="50" maxlength="255" title="Nueva Contrase&ntilde;a" value="" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Repetir Nueva Contrase&ntilde;a</label>
					<div class="controls">
						<input type="password" id="clave2" name="clave2" size="50" maxlength="255" title="Repetir Nueva Contrase&ntilde;a" value="" required />
					</div>
				</div>
			</div>
		</div>
				
		<div class="form-actions">
			<input type="submit" class="btn btn-large btn-primary" value="Guardar cambio" onclick="return confirm('Se va ha actualizar la contraseña de acceso de <?php echo(htmlentities(stripslashes($registros["NOMBRE_COMPLETO"]))); ?>.\nEsta Ud. seguro?');" />
			<input type="reset" class="btn btn-large" value="Cancelar" />
		</div>
	</div>
</form>
		<?php	
	
?>
