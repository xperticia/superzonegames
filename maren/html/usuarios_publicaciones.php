<?php
		require("../xpanel/config.php");
?>
<p>A continuación, usted podrá consultar sus publicaciones adquiridas y comprar las nuevas publicaciones disponibles en <strong><?php echo($sitio_nombre); ?></strong>.</p>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab01" data-toggle="tab">Adquiridas</a></li>
		<li><a href="#tab02" data-toggle="tab">Disponibles</a></li>
		<li><a href="#tab03" data-toggle="tab">Comprar</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab01" class="tab-pane active">
			<?php
			//	obtengo las publicaciones compradas
			$registros = $conexion->Query("SELECT FECHA, TITULO, AUTOR FROM usuario_compra_publicacion LEFT JOIN publicaciones ON publicaciones.id_publicacion = usuario_compra_publicacion.id_publicacion WHERE (id_usuario = '".$_GET['id']."')");
			?>
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th width="50">Fecha</th>
				<th>Titulo</th>
				<th>Autor</th>
			</tr>
			</thead>
			<tbody>
						<?php
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							?>
			<tr>
				<td><?php echo(formato_fecha($registros->fields["FECHA"], 3)); ?></td>
				<td><?php echo(stripslashes($registros->fields["TITULO"])); ?></td>
				<td><?php echo(stripslashes($registros->fields["AUTOR"])); ?></td>
			</tr>
							<?php
							$registros->MoveNext();
						}
						$registros->close();
					} else {
						?>
			<tr>
				<td colspan="3"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no posee publicaciones adquiridas.</strong></p></td>
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
				<th>Titulo</th>
				<th>Autor</th>
				<th width="70">Comprar</th>
			</tr>
			</thead>
			<tbody>
			<?php
			//	obtengo todos los estados de cursos
			$categoriaPublicaciones = $conexion->Query("SELECT CATEGORIA FROM publicaciones WHERE (tipo_publicacion = 'Publicaciones') and (activo = 1) GROUP BY categoria ORDER BY categoria");
			while(!$categoriaPublicaciones->EOF) {
				?>
			<tr>
				<td colspan="4" style="background: #B94A48;color: #ffffff;font-weight: bold;"><?php echo(stripslashes($categoriaPublicaciones->fields["CATEGORIA"])); ?></td>
			</tr>
				<?php
				//	obtengo todas las publicaciones disponibles
				$registros = $conexion->Query("SELECT * FROM publicaciones WHERE (tipo_publicacion = 'publicaciones') and (activo = 1) and (categoria = '".$categoriaPublicaciones->fields["CATEGORIA"]."') ORDER BY titulo");
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
						//	verifico si fue comprada la publicacion
						$publicacionComprada = $conexion->Query("SELECT * FROM usuario_compra_publicacion WHERE (id_usuario = '".$_GET['id']."') and (id_publicacion = '".$registros->fields["ID_PUBLICACION"]."')");							
						?>
				<tr>
					<td><?php if($publicacionComprada->RecordCount() > 0) { echo("<strong>"); };echo(stripslashes($registros->fields["TITULO"]));if($publicacionComprada->RecordCount() > 0) { echo("</strong>"); }; ?></td>
					<td><?php if($publicacionComprada->RecordCount() > 0) { echo("<strong>"); };echo(stripslashes($registros->fields["AUTOR"]));if($publicacionComprada->RecordCount() > 0) { echo("</strong>"); }; ?></td>
					<td><i class="icon-shopping-cart"></i> <a href="javascript:void(0);" id="btClickComprar" data-id="<?php echo($registros->fields["ID_PUBLICACION"]); ?>" title="Comprar publicación">comprar</a></td>
				</tr>
						<?php
						$registros->MoveNext();
					}
					$registros->close();
				} else {
					?>
				<tr>
					<td colspan="3"><p style="margin: 0 auto;text-align: center;"><strong>Actualmente no hay publicaciones disponibles.</strong></p></td>
				</tr>
					<?php
				}

				$categoriaPublicaciones->MoveNext();
			}
			$categoriaPublicaciones->close();
			?>
			</tbody>
			</table>
		</div>

		<div id="tab03" class="tab-pane">
			<p>Para poder adquirir alguna de las publicaciones que promociona <strong><?php echo($sitio_nombre); ?></strong>, se debe seguir los siguientes pasos:</p>
			<ol>
				<li>
					<h3>Registrar tus datos personales.</h3>
					<p>Usted ya es un usuario registrado <strong>FELICITACIONES!</strong></p>
					<p>Le recomendamos que verifique que est&eacute;n actualizados sus datos personales, preferentemente: nombre y apellido completo, tipo y n&uacute;mero de documento de identidad, direcci&oacute;n de correo electr&oacute;nico y domicilio de residencia. Este &uacute;ltimo dato, debe estar en forma clara, de acuerdo a la modalidad local, para el envi&oacute; del ejemplar solicitado.</p>
					<p>Seleccione la publicaci&oacute;n que desea adquirir.</p>
					<form method="post" action="<?php echo("usuarios.php?menu=".$_GET['menu']."&uma=".$_GET['uma']); ?>" id="signup" class="form-horizontal" style="margin-bottom: 0;">
						<select id="publicacion" name="publicacion" title="Publicaci&oacute;n a comprar" style="width: 99%" required>
							<option value=""></option>
							<?php
							//	muestro la lista de publicaciones, agrupados por categoria
							$categoriaPublicaciones = $conexion->Query("SELECT CATEGORIA FROM publicaciones WHERE (tipo_publicacion = 'Publicaciones') and (activo = 1) GROUP BY categoria ORDER BY categoria");
							while(!$categoriaPublicaciones->EOF) {
								?>
							<optgroup label="<?php echo(stripslashes($categoriaPublicaciones->fields["CATEGORIA"])); ?>">
								<?php
								$publicaciones = $conexion->Query("SELECT * FROM publicaciones WHERE (categoria = '".$categoriaPublicaciones->fields["CATEGORIA"]."') ORDER BY titulo");
								while(!$publicaciones->EOF) {
									if(isset($_GET['id']) and ($_GET['id'] == $publicaciones->fields['ID_PUBLICACION'])) {
										?>
								<option value="<?php echo($publicaciones->fields['ID_PUBLICACION']); ?>" selected="selected"><?php echo(stripslashes($publicaciones->fields['TITULO'])); ?></option>
										<?php
									} else {
										?>
								<option value="<?php echo($publicaciones->fields['ID_PUBLICACION']); ?>"><?php echo(stripslashes($publicaciones->fields['TITULO'])); ?></option>
										<?php
									}
									$publicaciones->MoveNext();
								}
								$publicaciones->close();
								?>
							</optgroup>
								<?php
								$categoriaPublicaciones->MoveNext();
							}
							$categoriaPublicaciones->close();
							?>
						</select>
						<div class="form-actions" style="margin: 0;padding-bottom: 5px;padding-left: 10px;padding-top: 5px;">
							<input type="submit" class="btn btn-primary" value="registrar compra">
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
					<h3>Recibir confirmaci&oacute;n de cobro y notificaci&oacute;n de envio.</h3>
					<p>Una vez que los datos personales y el dep&oacute;sito hayan sido verificados, usted recibir&aacute;, en la direcci&oacute;n de correo electr&oacute;nico suministrado en su registraci&oacute;n, la confirmaci&oacute;n de cobro y la notificaci&oacute;n de envio del ejemplar solicitado, a la direcci&oacute;n o domicilio suministrado en su registraci&oacute;n.</p>
				</li>
			</ol>
			<p>&nbsp;</p>
			<p><u>Aclaraciones:</u> <strong><?php echo($sitio_nombre); ?></strong> no se hace responsable por un mal proceder en los procedimientos de dep&oacute;sito por parte del usuario.</p>
		</div>
	</div>
</div>
<?php
?>
