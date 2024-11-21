<?php
		require("../xpanel/config.php");
?>
<p>A continuación, usted podrá consultar, actualizar y eliminar las carreras registradas, y también podrá seguir registrando nuevas carreras a publicarse en <strong><?php echo($sitio_nombre); ?></strong>.</p>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab01" data-toggle="tab">Registrados</a></li>
		<li><a href="#tab02" data-toggle="tab">Registrar</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab01" class="tab-pane active">
			<?php
			//	obtengo las inscripciones registradas
			$registros = $conexion->Query("SELECT * FROM instituciones WHERE (id_usuario = '".$_GET['id']."') and (categoria = '".$_GET['menu']."')");
			?>
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th width="50">Fecha</th>
				<th>Titulo</th>
				<th width="40">Opciones</th>
			</tr>
			</thead>
			<tbody>
						<?php
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							?>
			<tr>
				<td><?php echo(formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
				<td><?php echo(stripslashes($registros->fields["TITULO"])); ?></td>
				<td><a href="javascript:void(0);" id="btClickEditarEnlace" data-id="divEnlace<?php echo($registros->fields["ID_INSTITUCION"]); ?>" title="Editar registro"><img src="xpanel/images/editar.gif" border="0" /></a> | <a href="javascript:void(0);" id="btClickEliminarEnlace" data-id="<?php echo($registros->fields["ID_INSTITUCION"]); ?>" data-titulo="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" title="Eliminar registro"><img src="xpanel/images/eliminar.gif" border="0" /></a></td>
			</tr>
			<tr id="divEnlace<?php echo($registros->fields["ID_INSTITUCION"]); ?>" style="display: none;">
				<td colspan="3">
					<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup">
							<input type="hidden" name="id_institucion" value="<?php echo($registros->fields["ID_INSTITUCION"]); ?>" />
							<input type="hidden" name="categoria" value="<?php echo($registros->fields["CATEGORIA"]); ?>" />

							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_01" data-toggle="tab">Datos Generales</a></li>
								<li><a href="#tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_02" data-toggle="tab">Objetivos</a></li>
								<li><a href="#tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_03" data-toggle="tab">Otros datos</a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_01">
									<div class="control-group">
										<label class="control-label">Titulo</label>
										<div class="controls">
											<input type="text" id="titulo" name="titulo" value="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del contenido" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Descripción</label>
										<div class="controls">
											<textarea class="ckeditor" id="descripcion_<?php echo($registros->fields["ID_INSTITUCION"]); ?>" name="descripcion_<?php echo($registros->fields["ID_INSTITUCION"]); ?>" rows="15" cols="38" title="Contenido" style="width: 99%"><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
								</div>

								<div id="tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_02" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Objetivos</label>
										<div class="controls">
											<textarea class="ckeditor" id="objetivos_<?php echo($registros->fields["ID_INSTITUCION"]); ?>" name="objetivos_<?php echo($registros->fields["ID_INSTITUCION"]); ?>" rows="17" cols="38" title="Fragmento" style="width: 99%"><?php echo(stripslashes($registros->fields["OBJETIVOS"])); ?></textarea>
										</div>
									</div>
								</div>

								<div id="tab3_<?php echo($registros->fields["ID_INSTITUCION"]); ?>_03" class="tab-pane">
									<p>Su número de teléfono y dirección de correo electrónico serán la forma de contacto oficial, pero puede ingresar otras formas alternativas de contacto:</p>
									<div class="control-group">
										<label class="control-label">Otras formas de contactos</label>
										<div class="controls">
											<textarea id="contactos" name="contactos_<?php echo($registros->fields["ID_INSTITUCION"]); ?>" rows="10" cols="38" title="Fragmento" style="width: 99%"><?php echo(stripslashes($registros->fields["CONTACTOS"])); ?></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va ha actualizar los datos del registro actual.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" id="btClickCancelarEditarEnlace" data-id="divEnlace<?php echo($registros->fields["ID_INSTITUCION"]); ?>" />
							</div>
					</form>
				</td>
			</tr>
							<?php
							$registros->MoveNext();
						}
						$registros->close();
					} else {
						?>
			<tr>
				<td colspan="4"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no posee <?php echo($_GET['menu']); ?> registradas.</strong></p></td>
			</tr>
						<?php
					}
						?>
			</tbody>
			</table>
		</div>

		<div id="tab02" class="tab-pane">
			<p>A continuación, puede registrar los siguientes datos, pero si lo desea puede enviar un mensaje a la dirección de correo electrónico: <strong><?php echo($sitio_email); ?></strong> con toda la información que se solicita, incluyendo si lo desea una imagen institucional, y nuestros operadores con gusto lo registraran por usted.</p>
			<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup">
				<div class="tabbable">
					<input type="hidden" name="categoria" value="<?php echo($_GET['menu']); ?>" />

					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab201" data-toggle="tab">Datos Generales</a></li>
						<li><a href="#tab202" data-toggle="tab">Objetivos</a></li>
						<li><a href="#tab203" data-toggle="tab">Otros datos</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab201">
							<div class="control-group">
								<label class="control-label">Titulo</label>
								<div class="controls">
									<input type="text" id="titulo" name="titulo" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del contenido" required />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Descripción</label>
								<div class="controls">
									<textarea class="ckeditor" id="descripcion" name="descripcion" rows="10" cols="38" title="Contenido" style="width: 99%" required></textarea>
								</div>
							</div>
						</div>

						<div id="tab202" class="tab-pane">
							<div class="control-group">
								<label class="control-label">Objetivos</label>
								<div class="controls">
									<textarea class="ckeditor" id="objetivos" name="objetivos" rows="10" cols="38" title="Fragmento" style="width: 99%"></textarea>
								</div>
							</div>
						</div>

						<div id="tab203" class="tab-pane">
							<p>Su número de teléfono y dirección de correo electrónico serán la forma de contacto oficial, pero puede ingresar otras formas alternativas de contacto:</p>
							<div class="control-group">
								<label class="control-label">Otras formas de contactos</label>
								<div class="controls">
									<textarea id="contactos" name="contactos" rows="10" cols="38" title="Fragmento" style="width: 99%"></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="form-actions">
						<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
						<input type="reset" class="btn btn-large" value="Cancelar" />
					</div>
				</div>
			</form>

		</div>
	</div>
</div>
<?php
?>
