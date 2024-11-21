<?php
	require("../xpanel/config.php");
?>
		<form id="formMensajes" class="form-horizontal" action="" method="" style="margin: 0px;">	
			<div class="modal-header">
				<a class="close" data-dismiss="modal"><img src="images/cerrar.png" alt="&times;" title="" /></a>
				<h3>formRegistro</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="tipo_mensaje" name="tipo_mensaje" value="" />
				<input type="hidden" id="tipo_cabeza" name="tipo_cabeza" />
				<input type="hidden" id="id_cabeza" name="id_cabeza" />

				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label">Tipo y Nro. de Documento</label>
							<div class="controls">
								<select id="tipo_doc" name="tipo_doc" class="input-mini" title="Tipo de Documento" required>
									<option value=""></option>
									<?php
										for($i=0; $i<count($tipo_documento); $i++) {
											?>
									<option value="<?php echo($tipo_documento[$i]); ?>"><?php echo($tipo_documento[$i]); ?></option>
											<?php
										}
									?>
								</select>
								<input type="text" id="nro_doc" name="nro_doc" class="input-small" maxlength="8" size="10" title="Nro. de Documento" autofocus="autofocus" required />
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="control-label">C&oacute;digo de Usuario</label>
							<div class="controls">
								<?php
								$datos = $conexion->GetRow("SELECT IF(COD_USUARIO > 0, (max(COD_USUARIO) + 1), 1) as COD_USUARIO FROM usuarios WHERE (tipo_usuario = 'Cliente') and (id_sucursal = '1') ORDER BY cod_usuario DESC");
								?>
								<input type="text" id="cod_usuario" name="cod_usuario" value="<?php echo(sprintf("%06s",$datos["COD_USUARIO"])); ?>" class="input-small" maxlength="8" size="10" title="C&oacute;digo de Usuario" placeholder="0000" style="text-align: right;font-size: 1.8em;font-weight: bold;" readonly="readonly" required />
							</div>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Nombres y Apellidos</label>
					<div class="controls">
						<input type="text" id="nombre" name="nombre" size="25" maxlength="255" title="Nombre" placeholder="Nombres" style="width: 47%;" required />
						<input type="text" id="apellidos" name="apellidos" size="25" maxlength="255" title="Apellidos" placeholder="Apellidos" style="width: 47%;" required />
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label">Fecha de Nacimiento</label>
							<div class="controls">
								<input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="input-small" title="Fecha de Nacimiento" value="<?php echo(date("d/m/Y")); ?>" size="12" maxlength="10" required />
								<script type="text/javascript">
								$(function() {
									$("#fecha_nacimiento").datepicker({ dateFormat: "dd/mm/yy"});
								});
								</script>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="control-label">Sexo</label>
							<div class="controls">
								<label class="radio inline">
									<input type="radio" id="sexo" name="sexo" value="M" checked="checked"> Masculino
								</label>
								<label class="radio inline">
									<input type="radio" id="sexo" name="sexo" value="F"> Femenino
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Direcci&oacute;n</label>
					<div class="controls">
						<input type="text" id="domicilio" name="domicilio" size="50" maxlength="255" title="Direcci&oacute;n" placeholder="Direcci&oacute;n" style="width: 99%;" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Tel&eacute;fonos</label>
					<div class="controls">
						<input type="text" id="telefono_fijo" name="telefono_fijo" size="23" maxlength="50" title="Tel&eacute;fono fijo" placeholder="Tel&eacute;fono fijo" />
						<input type="text" id="telefono_movil" name="telefono_movil" size="23" maxlength="50" title="Tel&eacute;fono m&oacute;vil" placeholder="Tel&eacute;fono m&oacute;vil" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Correo Electr&oacute;nico</label>
					<div class="controls">
						<input type="text" id="email" name="email" size="50" maxlength="255" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" title="Correo Electr&oacute;nico" placeholder="Correo Electr&oacute;nico" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Ciudad</label>
					<div class="controls">
						<input type="text" id="ciudad" name="ciudad" size="50" maxlength="255" title="Ciudad" placeholder="Ciudad" required />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Pa&iacute;s</label>
					<div class="controls">
						<select id="codigo_pais" name="codigo_pais" title="Pa&iacute;s" required>
							<option value=""></option>
							<?php
								$registros = $temp->Query("SELECT * FROM paises ORDER BY pais");
								while(!$registros->EOF) {									
										?>
							<option value="<?php echo($registros->fields["CODIGO_PAIS"]); ?>"><?php echo(htmlentities($registros->fields["PAIS"])); ?></option>
										<?php
									$registros->MoveNext();
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="btGuardarRegistro" class="btn btn-danger">Registrar datos</button>
				<button type="reset" class="btn" id="btCancelarRegistro" data-dismiss="modal">Cancelar</button>
			</div>
		</form>
