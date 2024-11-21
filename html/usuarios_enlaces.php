<?php
		require("../xpanel/config.php");
?>
<p>A continuación, usted podrá consultar, actualizar y eliminar los enlaces registrados, y también podrá seguir registrando nuevos enlaces a publicarse en <strong><?php echo($sitio_nombre); ?></strong>.</p>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab01" data-toggle="tab">Registrados</a></li>
		<li><a href="#tab02" data-toggle="tab">Registrar</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab01" class="tab-pane active">
			<?php
			//	obtengo las inscripciones registradas
			$registros = $conexion->Query("SELECT * FROM enlaces WHERE (id_usuario = '".$_GET['id']."')");
			?>
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th width="50">Fecha</th>
				<th>Titulo</th>
				<th>Categoria</th>
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
				<td><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></td>
				<td><a href="javascript:void(0);" id="btClickEditarEnlace" data-id="divEnlace<?php echo($registros->fields["ID_ENLACE"]); ?>" title="Editar registro"><img src="xpanel/images/editar.gif" border="0" /></a> | <a href="javascript:void(0);" id="btClickEliminarEnlace" data-id="<?php echo($registros->fields["ID_ENLACE"]); ?>" data-titulo="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" title="Eliminar registro"><img src="xpanel/images/eliminar.gif" border="0" /></a></td>
			</tr>
			<tr id="divEnlace<?php echo($registros->fields["ID_ENLACE"]); ?>" style="display: none;">
				<td colspan="4">
					<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup" class="form-horizontal">
						<input type="hidden" name="id_enlace" value="<?php echo($registros->fields["ID_ENLACE"]); ?>" />

						<div class="control-group">
							<label class="control-label">Categoria</label>
							<div class="controls">
								<input type="text" id="categoria" name="categoria" value="<?php echo(stripslashes($registros->fields["CATEGORIA"])); ?>" size="50" maxlength="255" title="Categoria" placeholder="Categoria del enlace" required />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Titulo</label>
							<div class="controls">
								<input type="text" id="titulo" name="titulo" value="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del enlace" required />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Url</label>
							<div class="controls">
								<input type="text" id="url" name="url" value="<?php echo(stripslashes($registros->fields["URL"])); ?>" size="50" pattern="^(http(s)?|ftp)://([a-z0-9_-]+.)+([a-z]{2,}){1}((:|/)(.*))?$" maxlength="255" title="Url" style="width: 99%;" placeholder="http://www.dominio.com" required />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Descripción</label>
							<div class="controls">
								<textarea id="contenido" name="contenido" rows="10" cols="38" title="Descripción" style="width: 99%"><?php echo(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))); ?></textarea>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va ha actualizar los datos del registro actual.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" id="btClickCancelarEditarEnlace" data-id="divEnlace<?php echo($registros->fields["ID_ENLACE"]); ?>" />
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
				<td colspan="4"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no posee enlaces registradas.</strong></p></td>
			</tr>
						<?php
					}
						?>
			</tbody>
			</table>
		</div>

		<div id="tab02" class="tab-pane">
			<p>Registra o modifica tus enlaces registrados en <strong><?php echo($sitio_nombre); ?></strong>:</p>
			<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup" class="form-horizontal">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab01">
							<div class="control-group">
								<label class="control-label">Categoria</label>
								<div class="controls">
									<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" placeholder="Categoria del enlace" required />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Titulo</label>
								<div class="controls">
									<input type="text" id="titulo" name="titulo" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del enlace" required />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Url</label>
								<div class="controls">
									<input type="text" id="url" name="url" size="50" pattern="^(http(s)?|ftp)://([a-z0-9_-]+.)+([a-z]{2,}){1}((:|/)(.*))?$" maxlength="255" title="Url" style="width: 99%;" placeholder="http://www.dominio.com" required />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Descripción</label>
								<div class="controls">
									<textarea id="contenido" name="contenido" rows="10" cols="38" title="Descripción" style="width: 99%"></textarea>
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
