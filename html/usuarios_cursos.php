<?php
		require("../xpanel/config.php");
?>
<p>A continuación, usted podrá consultar las inscripciones a cursos realizadas e inscribirse a los nuevos cursos disponibles en <strong><?php echo($sitio_nombre); ?></strong>.</p>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab01" data-toggle="tab">Inscripciones</a></li>
		<li><a href="#tab02" data-toggle="tab">Disponibles</a></li>
		<li><a href="#tab03" data-toggle="tab">Inscribirse</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab01" class="tab-pane active">
			<?php
			//	obtengo las inscripciones registradas
			$registros = $conexion->Query("SELECT FECHA,NOMBRE,METODOLOGIA,DURACION FROM usuario_seinscribe_curso LEFT JOIN cursos ON cursos.id_curso = usuario_seinscribe_curso.id_curso WHERE (id_usuario = '".$_GET['id']."')");
			?>
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th width="50">Fecha</th>
				<th>Nombre</th>
				<th>Metodolog&iacute;a</th>
				<th>Duraci&oacute;n</th>
			</tr>
			</thead>
			<tbody>
						<?php
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							?>
			<tr>
				<td><?php echo(formato_fecha($registros->fields["FECHA"], 3)); ?></td>
				<td><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></td>
				<td><?php echo(stripslashes($registros->fields["METODOLOGIA"])); ?></td>
				<td><?php echo(stripslashes($registros->fields["DURACION"])); ?></td>
			</tr>
							<?php
							$registros->MoveNext();
						}
						$registros->close();
					} else {
						?>
			<tr>
				<td colspan="4"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no posee inscripciones registradas.</strong></p></td>
			</tr>
						<?php
					}
						?>
			</tbody>
			</table>
		</div>

		<div id="tab02" class="tab-pane">
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th>Nombre</th>
				<th>Metodolog&iacute;a</th>
				<th>Duraci&oacute;n</th>
				<th width="80">Inscribirse</th>
			</tr>
			</thead>
			<tbody>
			<?php
			//	obtengo todos los estados de cursos
			$categoriaCursos = $conexion->Query("SELECT ESTADO_CURSO FROM cursos GROUP BY estado_curso ORDER BY estado_curso");
			while(!$categoriaCursos->EOF) {
				?>
			<tr>
				<td colspan="4" style="background: #B94A48;color: #ffffff;font-weight: bold;"><?php echo(stripslashes($categoriaCursos->fields["ESTADO_CURSO"])); ?></td>
			</tr>
				<?php
				//	obtengo todos los cursos disponibles
				$registros = $conexion->Query("SELECT * FROM cursos WHERE (estado_curso = '".$categoriaCursos->fields["ESTADO_CURSO"]."') ORDER BY nombre");
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
						//	verifico si esta inscripto al curso
						$publicacionComprada = $conexion->Query("SELECT * FROM usuario_seinscribe_curso WHERE (id_usuario = '".$_GET['id']."') and (id_curso = '".$registros->fields["ID_CURSO"]."')");							
						?>
			<tr>
				<td><?php if($publicacionComprada->RecordCount() > 0) { echo("<strong>"); };echo(stripslashes($registros->fields["NOMBRE"]));if($publicacionComprada->RecordCount() > 0) { echo("</strong>"); }; ?></td>
				<td><?php if($publicacionComprada->RecordCount() > 0) { echo("<strong>"); };echo(stripslashes($registros->fields["METODOLOGIA"]));if($publicacionComprada->RecordCount() > 0) { echo("</strong>"); }; ?></td>
				<td><?php if($publicacionComprada->RecordCount() > 0) { echo("<strong>"); };echo(stripslashes($registros->fields["DURACION"]));if($publicacionComprada->RecordCount() > 0) { echo("</strong>"); }; ?></td>
				<td><i class="icon-shopping-cart"></i> <a href="javascript:void(0);" id="btClickInscribirse" data-id="<?php echo($registros->fields["ID_CURSO"]); ?>" title="Inscribirse al curso">inscribirse</a></td>
			</tr>
						<?php
						$registros->MoveNext();
					}
					$registros->close();
				} else {
					?>
			<tr>
				<td colspan="4"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no hay cursos disponibles.</strong></p></td>
			</tr>
					<?php
				}

				$categoriaCursos->MoveNext();
			}
			$categoriaCursos->close();
			?>
			</tbody>
			</table>
		</div>

		<div id="tab03" class="tab-pane">
			<p>Para poder participar de alg&uacute;n curso que promociona <strong><?php echo($sitio_nombre); ?></strong>, se debe seguir los siguientes pasos:</p>
			<ol>
				<li>
					<h3>Registrar tus datos personales.</h3>
					<p>Usted ya es un usuario registrado <strong>FELICITACIONES!</strong></p>
					<p>Le recomendamos que verifique que est&eacute;n actualizados sus datos personales, preferentemente: nombre y apellido completo, tipo y n&uacute;mero de documento de identidad, direcci&oacute;n de correo electr&oacute;nico y domicilio de residencia. Este &uacute;ltimo dato, debe estar en forma clara, de acuerdo a la modalidad local, para el envi&oacute; del certificado correspondiente.</p>
					<p>Seleccione el curso en el cual desea inscribirse:</p>
					<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup" class="form-horizontal" style="margin-bottom: 0;">
						<select id="curso" name="curso" title="Cursos a inscribirse" style="width: 99%" required>
							<option value=""></option>
							<?php
							//muestro la lista de cursos, agrupados por Estado
							$categoriaCursos = $conexion->Query("SELECT ESTADO_CURSO FROM cursos GROUP BY estado_curso ORDER BY estado_curso");
							while(!$categoriaCursos->EOF) {
								?>
							<optgroup label="<?php echo(stripslashes($categoriaCursos->fields["ESTADO_CURSO"])); ?>">
								<?php
								$cursos = $conexion->Query("SELECT * FROM cursos WHERE (estado_curso = '".$categoriaCursos->fields["ESTADO_CURSO"]."') ORDER BY nombre");
								while(!$cursos->EOF) {
									if(isset($_GET['id']) and ($_GET['id'] == $cursos->fields['ID_CURSO'])) {
										?>
								<option value="<?php echo($cursos->fields['ID_CURSO']); ?>" selected="selected"><?php echo(stripslashes($cursos->fields['NOMBRE'])); ?></option>
										<?php
									} else {
										?>
								<option value="<?php echo($cursos->fields['ID_CURSO']); ?>"><?php echo(stripslashes($cursos->fields['NOMBRE'])); ?></option>
										<?php
									}
									$cursos->MoveNext();
								}
								$cursos->close();
								?>
							</optgroup>
								<?php
								$categoriaCursos->MoveNext();
							}
							$categoriaCursos->close();
							?>
						</select>
						<div class="form-actions" style="margin: 0;padding-bottom: 5px;padding-left: 10px;padding-top: 5px;">
							<input type="submit" class="btn btn-primary" value="registrar inscripción">
							<input type="reset" class="btn" value="cancelar">
						</div>
				  </form>
				</li>
				<li>
					<h3>Realizar dep&oacute;sito o transferencia bancaria.</h3>
					<p>Los dep&oacute;sitos se efectuaran a nombre de <strong>V&iacute;ctor Hugo Ar&eacute;valo Jord&aacute;n</strong>, por el monto correspondiente, a la siguiente cuenta:</p>
					<blockquote>
						NUEVO BANCO DE SANTA FE S.A.<br />
						Sucursal: 503. Caja de Ahorro.<br />
						Numero de Cuenta: 503005080200 <br />
						Numero de CBU: 3300503225030050802003<br />
					</blockquote>
					<p>Una vez realizado el dep&oacute;sito, se debe enviar por correo electr&oacute;nico el n&uacute;mero del tal&oacute;n del dep&oacute;sito, a la direcci&oacute;n <a href="mailto:info@mundoarchivistico.com">info@mundoarchivistico.com</a>. </p>
				</li>
				<li>
					<h3>Recibir confirmaci&oacute;n de inscripci&oacute;n y cronograma de actividades.</h3>
					<p>Una vez que los datos personales y el dep&oacute;sito hayan sido verificados, usted recibir&aacute;, en la direcci&oacute;n de correo electr&oacute;nico suministrado en su registraci&oacute;n, la confirmaci&oacute;n de inscripci&oacute;n y un cronograma de actividades del curso.</p>
				</li>
			</ol>
			<p>&nbsp;</p>
			<p><u>Aclaraciones:</u> <strong><?php echo($sitio_nombre); ?></strong> no se hace responsable por un mal proceder en los procedimientos de inscripci&oacute;n o dep&oacute;sito por parte del usuario.</p>
		</div>
	</div>
</div>
<?php
?>
