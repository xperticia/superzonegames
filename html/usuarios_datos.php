<?php
		require("../xpanel/config.php");
		$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['id']."')");
		while(!$registros->EOF) {
			$registros->fields["NRO_DOC"] = sprintf("%08s",$registros->fields["NRO_DOC"]); 
			?>
<p>A continuaci&oacute;n, usted puede actualizar sus datos personales:</p>
<form action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
	<div class="tabbable">
		<input type="hidden" name="id_usuario" value="<?php echo($registros->fields["ID_USUARIO"]); ?>" />
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
		</ul>

		<div class="tab-content">
			<div id="tab01" class="tab-pane active">
				<div class="control-group">
					<label class="control-label">Tipo y Nro. de Documento</label>
					<div class="controls">
						<select name="tipo_doc" class="input-mini" title="Tipo de Documento">
							<option value=""></option>
							<?php
								for($i=0; $i<count($tipo_documento); $i++) {
									?>
							<option value="<?php echo($tipo_documento[$i]); ?>" <?php if($registros->fields["TIPO_DOC"] == $tipo_documento[$i]) { ?>selected="selected"<?php } ?>><?php echo($tipo_documento[$i]); ?></option>
									<?php
								}
							?>
						</select>
						<input type="text" name="nro_doc" maxlength="8" class="input-small" size="10" title="Nro. de Documento" value="<?php echo($registros->fields["NRO_DOC"]); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nombres y Apellidos</label>
					<div class="controls">
						<input type="text" name="nombre_completo" size="25" maxlength="255" title="Nombres y Apellidos" value="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>" style="width: 99%;" required/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Dirección</label>
					<div class="controls">
						<input type="text" name="direccion" size="50" maxlength="255" title="Dirección" value="<?php echo(htmlentities(stripslashes($registros->fields["DIRECCION"]))); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Tel&eacute;fono</label>
					<div class="controls">
						<input type="text" name="telefono_fijo" size="23" maxlength="50" title="Tel&eacute;fono fijo" value="<?php echo($registros->fields["TELEFONO_FIJO"]); ?>"/>
						<input type="text" name="telefono_movil" size="23" maxlength="50" title="Tel&eacute;fono movil" value="<?php echo($registros->fields["TELEFONO_MOVIL"]); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Ciudad</label>
					<div class="controls">
						<input type="text" name="ciudad" size="50" maxlength="255" title="Ciudad" value="<?php echo(htmlentities(stripslashes($registros->fields["CIUDAD"]))); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Código Postal</label>
					<div class="controls">
						<input type="text" name="codigo_postal" size="50" maxlength="255" class="input-mini" title="Código Postal" value="<?php echo(htmlentities(stripslashes($registros->fields["CODIGO_POSTAL"]))); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Pa&iacute;s</label>
					<div class="controls">
						<select name="codigo_pais" title="Pa&iacute;s" required>
							<option value=""></option>
							<?php
								$pais = strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 3, strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"])));
								$datos = $temp->Query("SELECT * FROM paises ORDER BY pais");
								while(!$datos->EOF) {									
										?>
							<option value="<?php echo($datos->fields["CODIGO_PAIS"]); ?>" <?php if($datos->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($datos->fields["PAIS"]); ?></option>
										<?php
									$datos->MoveNext();
								}
								$datos->close();
							?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Correo Electr&oacute;nico</label>
					<div class="controls">
						<input type="text" name="email" size="50" maxlength="255" title="Correo Electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" required/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Foto
					<?php
						$nombre_completo_imagenoriginal = $path_imagenes."fotos/".$registros->fields['FOTO'];
						if(($registros->fields['FOTO'] != null) and file_exists("../".$nombre_completo_imagenoriginal)) { 
							?>
							<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="Foto" rel="colorbox" title="Ver foto de <?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>"><img src="xpanel/images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
							| <a href="#" title="Eliminar foto de <?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="xpanel/images/eliminar.gif" alt="Eliminar archivo" align="absmiddle" /></a>
							<?php 
						} else { 
							if(($registros->fields['FOTO'] != null) and !file_exists("../".$nombre_completo_imagenoriginal)) { 
								?>
							<a href="#" title="Eliminar foto de <?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="xpanel/images/eliminar.gif" alt="Eliminar archivo" align="absmiddle" /></a>
								<?php 
							} 
						} 
					?>
					</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
						<input type="file" name="archivo_foto" class="input-file" title="Foto"/>
					</div>
				</div>
				<h2>Datos Laborales</h2>
				<div class="control-group">
					<label class="control-label">Institución</label>
					<div class="controls">
						<input type="text" name="institucion_trabaja" size="50" maxlength="255" title="Institución" value="<?php echo($registros->fields["INSTITUCION_TRABAJA"]); ?>" style="width: 99%;"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Cargo</label>
					<div class="controls">
						<input type="text" name="cargo_trabaja" size="50" maxlength="255" title="Cargo" value="<?php echo($registros->fields["CARGO_TRABAJA"]); ?>" style="width: 99%;"/>
					</div>
				</div>
				<h2>Datos Acádemicos</h2>
				<div class="control-group">
					<label class="control-label">Nivel de Formación</label>
					<div class="controls">
						<select name="nivel_formacion" title="Nivel de Formación">
							<option value=""></option>
							<option value="Directivo/a" <?php if($registros->fields["NIVEL_FORMACION"] == "Directivo/a") { echo("selected='selected'"); } ?>>Directivo/a</option>
							<option value="Empresario/a" <?php if($registros->fields["NIVEL_FORMACION"] == "Empresario/a") { echo("selected='selected'"); } ?>>Empresario/a</option>
							<option value="Profesional" <?php if($registros->fields["NIVEL_FORMACION"] == "Profesional") { echo("selected='selected'"); } ?>>Profesional</option>
							<option value="Empleado" <?php if($registros->fields["NIVEL_FORMACION"] == "Empleado") { echo("selected='selected'"); } ?>>Empleado</option>
							<option value="Autónomo" <?php if($registros->fields["NIVEL_FORMACION"] == "Autónomo") { echo("selected='selected'"); } ?>>Autónomo</option>
							<option value="Estudiante" <?php if($registros->fields["NIVEL_FORMACION"] == "Estudiante") { echo("selected='selected'"); } ?>>Estudiante</option>
							<option value="Desocupado/a" <?php if($registros->fields["NIVEL_FORMACION"] == "Desocupado/a") { echo("selected='selected'"); } ?>>Desocupado/a</option>
							<option value="Otra" <?php if($registros->fields["NIVEL_FORMACION"] == "Otra") { echo("selected='selected'"); } ?>>Otra</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Institución</label>
					<div class="controls">
						<input type="text" id="institucion_estudia" name="institucion_estudia" size="50" maxlength="255" title="Institución" value="<?php echo($registros->fields["INSTITUCION_ESTUDIA"]); ?>" style="width: 99%;" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Curso o Nivel de Estudio</label>
					<div class="controls">
						<input type="text" id="nivel_estudio" name="nivel_estudio" size="50" maxlength="255" title="Curso o Nivel de Estudio" value="<?php echo($registros->fields["NIVEL_ESTUDIO"]); ?>" style="width: 99%;" />
					</div>
				</div>
				<h2>Otros datos</h2>
				<div class="control-group">
					<label class="control-label">Suscripto al Boletin</label>
					<div class="controls">
						<label class="radio inline">
							<input type="radio" name="suscripto_boletin" value="1" <?php if($registros->fields["SUSCRIPTO_BOLETIN"] == "1") { ?>checked="checked"<?php } ?>/> <?php echo($estados[1])?>
						</label>
						<label class="radio inline">
							<input type="radio" name="suscripto_boletin" value="0" <?php if($registros->fields["SUSCRIPTO_BOLETIN"] == "0") { ?>checked="checked"<?php } ?>/> <?php echo($estados[0])?>
						</label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Mostrar sus datos en la sección Archiveros</label>
					<div class="controls">
						<label class="radio inline">
							<input type="radio" name="mostrar_datos" value="1" <?php if($registros->fields["MOSTRAR_DATOS"] == "1") { ?>checked="checked"<?php } ?>/> <?php echo($estados[1])?>
						</label>
						<label class="radio inline">
							<input type="radio" name="mostrar_datos" value="0" <?php if($registros->fields["MOSTRAR_DATOS"] == "0") { ?>checked="checked"<?php } ?>/> <?php echo($estados[0])?>
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se van ha actualizar los datos de <?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>.\nEsta Ud. seguro?');" />
			<input type="reset" class="btn btn-large" value="Cancelar" />
		</div>
	</div>
</form>
			<?php
			$registros->MoveNext();
		}
		$registros->close();
?>
