<?php
require("../xpanel/config.php");
$categorias = "";
$url = urlencode($sitio_url);
if (isset($_GET['menu'])) {
	$url .= "?menu=" . $_GET['menu'];
} else {
	$url .= "?menu=productos";
}
if (isset($_GET['id'])) {
	$url .= "&id=" . $_GET['id'];
}
if (isset($_GET['action'])) {
	$url .= "&accion=" . $_GET['accion'];
}
if (isset($_GET['c'])) {
	$url .= "&c=" . $_GET['c'];
}
if (isset($_GET['g'])) {
	$url .= "&g=" . $_GET['g'];
}

//	armo los enlaces para compartir
$categorias .= "	<div class=\"btn-group\">\n";
$categorias .= "		<a class=\"btn\" href=\"https://www.facebook.com/sharer/sharer.php?u=" . $url . "\" title=\"Compartir en Facebook\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/facebook.png\" width=\"16\" /></a>";
$categorias .= "		<a class=\"btn\" href=\"https://twitter.com/intent/tweet?text=" . $url . "\" title=\"Compartir en Twitter\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/twitter.png\" width=\"16\" /></a>";
$categorias .= "		<a class=\"btn\" href=\"whatsapp://send?text=" . $url . "\" title=\"Compartir en WhatsApp\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/whatsapp.png\" width=\"16\" /></a>";
$categorias .= "	</div>\n";

?>
<form id="formProducto" class="form-horizontal" action="" method="" style="margin: 0px;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal"><img src="images/cerrar.png" alt="&times;" title="" /></a>
		<h3>formProducto</h3>
	</div>
	<div class="modal-body">
		<input type="hidden" id="tipo_mensaje" name="tipo_mensaje" value="" />
		<input type="hidden" id="tipo_cabeza" name="tipo_cabeza" />
		<input type="hidden" id="id_cabeza" name="id_cabeza" />

		<?php
		//	obtengo el CONTENIDO segun su ID
		$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (id_producto = '" . $_GET['id'] . "') ORDER BY id_producto DESC LIMIT 1");
		if ($registros) {
			while (!$registros->EOF) {

				//	armo los enlaces para compartir
				$url = urlencode($sitio_url . "?menu=productos&id=" . $registros->fields["ID_PRODUCTO"]);
				$Compartir_ContenidoLateral = str_replace("{URL}", $url, $Compartir_ContenidoLateral);
				$Compartir_ContenidoHorizontal = str_replace("{URL}", $url, $Compartir_ContenidoHorizontal);

				//	Actualizo la cantidad de veces leidas del contenido: CLICK
				$conexion->Execute("UPDATE productos SET click = click + 1 WHERE (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "')");

				//	verifico si posee COMENTARIOS
				$datosComentarios = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'productos') and (id_cabeza = '" . $registros->fields["ID_PRODUCTO"] . "') and (activo = 1)");
				//	verifico si posee ARCHIVOS ADJUNTOS
				$datosArchivosadjuntos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");

				//	verifico si posee IMAGENES el contenido
				$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY id_archivo");
				if ($registros2) {
					if ($registros2->RecordCount() > 0) {
						$i = 1;
						while (!$registros2->EOF) {
							//	verifico si el archivo existe
							$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes . "contenidos/" . $registros2->fields["NOMBRE_ARCHIVO"];
							if (verificar_url($nombre_completo_imagenoriginal)) {
								//	obtengo el nombre y extension del archivo small
								$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes . "productos/" . $nombre . "_small." . $extension;
								if (!verificar_url($nombre_completo_imagensmall)) {
									$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
								}
								//	muestra la imagen
								$imagen = "";
								//$imagen .= "<ul class=\"thumbnails\">\n";
								//$imagen .= "	<li class=\"span6\">\n";
								if ($registros2->fields["COMENTARIO"] == null) {
									$imagen .= "		<a href=\"" . $nombre_completo_imagenoriginal . "\" class=\"thumbnail\" rel=\"colorbox\" title=\"" . stripslashes($registros->fields["NOMBRE"]) . "\">";
								} else {
									$imagen .= "		<a href=\"" . $nombre_completo_imagenoriginal . "\" class=\"thumbnail\" rel=\"colorbox\" title=\"" . stripslashes($registros2->fields["COMENTARIO"]) . "\">";
								}
								$imagen .= "			<img src='" . $nombre_completo_imagensmall . "' border='0' />";
								$imagen .= "		</a>\n";
								$imagen .= "		<div class=\"caption\">" . html_entity_decode(stripslashes($registros2->fields["COMENTARIO"])) . "</div>\n";
								//$imagen .= "	</li>\n";
								//$imagen .= "</ul>\n";
							} else {
								$imagen = "<span class=\"label label-warning\">" . $registros2->fields["NOMBRE_ARCHIVO"] . ".</span>";
							}
							$referencia = "[IMAGEN" . sprintf("%02s", $i) . "]";
							//	agrego la imagen al contenido
							$registros->fields["DESCRIPCION"] = str_replace($referencia, $imagen, $registros->fields["DESCRIPCION"]);
							++$i;

							$registros2->MoveNext();
						}
						$registros2->close();
					}
				}
				?>
				<p class="stock pull-right">
					<?php
					// verifico si el PRODUCTO solo es a PEDIDO
					if ($registros->fields["SOLOAPEDIDO"] == 1) {
						echo ("SOLO A PEDIDO");
					} else {
						// verifico si posee STOCK el PRODUCTO
						$datosStock = $temp->Query("SELECT STOCK FROM productos WHERE (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "') and (stock > 0)");
						if ($datosStock && ($datosStock->RecordCount() > 0)) {
							echo ("EN STOCK");
						} else {
							//echo ("SIN STOCK");
							echo ("DISPONIBLE");
						}
					}
					?>
				</p>
				<div class="tabbable">
					<ul id="myTabProducto" class="nav nav-tabs">
						<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						<li><a href="#tab02" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
						<li><a href="#tab03" data-toggle="tab">Comentarios</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<div class="row-fluid">
								<div class="span8">
									<?php
									$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes . "productos/" . $registros->fields['FOTO'];
									if (($registros->fields['FOTO'] != null) && verificar_url($nombre_completo_imagenoriginal)) {
										//	obtengo la extension del archivo
										$res = explode(".", $registros->fields["FOTO"]);
										$nombre = $res[0];
										$extension = $res[count($res) - 1];
										//	obtengo la ubicacion de la imagen redimensionada original
										$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes . "productos/" . $nombre . "_small." . $extension;
										if (!verificar_url($nombre_completo_imagensmall)) {
											$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
										}
									} else {
										$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST."images/productos/producto.jpg";
										$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST."images/productos/producto_small.jpg";
									}
									?>
									<a href="<?php echo ($nombre_completo_imagenoriginal); ?>" class="thumbnail foto" rel="colorbox" title="<?php echo ("[" . stripslashes($registros->fields["CATEGORIA"]) . "] " . $registros->fields['NOMBRE']); ?>">
										<img src="<?php echo ($nombre_completo_imagenoriginal); ?>" border="0" />
									</a>
								</div>
								<div class="span4">
									<table class="table-condensed">
										<tbody>
											<tr>
												<th width="50" style="text-align: left;">C&oacute;digo</th>
												<td><?php echo (sprintf("%06s", $registros->fields["CODIGO"])); ?></td>
											</tr>
											<tr>
												<th width="50" style="text-align: left;">Categoria:</th>
												<td><a href="<?php echo ("?menu=productos&c=" . $registros->fields["CATEGORIA"]); ?>" title=""><?php echo (stripslashes($registros->fields["CATEGORIA"])); ?></a></td>
											</tr>
											<tr>
												<th style="text-align: left;">G&eacute;nero:</th>
												<td>
													<?php
													//echo(stripslashes($registros->fields["GENERO"]));
													$contador = 0;
													$generos = explode(",", $registros->fields["GENERO"]);
													foreach ($generos as $key => $val) {
														if ($contador++ > 0) echo (", ");
														echo ("<a href=\"?menu=productos&g=" . htmlentities(trim($val)) . "\">" . htmlentities(trim($val)) . "</a>");
													}
													?>
												</td>
											</tr>
											<tr>
												<th style="text-align: left;">Desarrollador:</th>
												<td><?php echo (stripslashes($registros->fields["MARCA"])); ?></td>
											</tr>
											<tr>
												<th style="text-align: left;">Lanzamiento:</th>
												<td><?php if (($registros->fields["FECHA_LANZAMIENTO"] != null) && ($registros->fields["FECHA_LANZAMIENTO"] != "0000-00-00")) {
														echo (formato_fecha($registros->fields["FECHA_LANZAMIENTO"], 3));
													} else {
														echo ("NO ESPECIFICADO");
													} ?></td>
											</tr>
										</tbody>
									</table>
									<hr />
									<?php echo (stripslashes($registros->fields["DESCRIPCION"])); ?>

								</div>
							</div>
						</div>

						<div id="tab02" class="tab-pane">
							<?php
							//	verifico si posee GALERIA el contenido
							$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "') and (tipo_archivo = 'Imagen') and (archivos.destino = 'galeria') ORDER BY id_archivo");
							if ($registros2 && $registros2->RecordCount() > 0) {
								?>
								<div id="myCarousel" class="carousel slide">
									<ol class="carousel-indicators">
										<?php
										for ($i = 0; $i < $registros2->RecordCount(); $i++) {
											?>
											<li data-target="#myCarousel" data-slide-to="<?php echo ($i); ?>" <?php if ($i == 0) {
																													echo (" class=\"active\"");
																												} ?>></li>
										<?php
										}
										?>
									</ol>
									<div class="carousel-inner">
										<?php
										$i = 0;
										while (!$registros2->EOF) {
											$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes . "contenidos/" . $registros2->fields['NOMBRE_ARCHIVO'];
											if (($registros2->fields['NOMBRE_ARCHIVO'] != null) && verificar_url($nombre_completo_imagenoriginal)) {
												//	obtengo la extension del archivo
												$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
												$nombre = $res[0];
												$extension = $res[count($res) - 1];
												//	obtengo la ubicacion de la imagen redimensionada original
												$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes . "contenidos/" . $nombre . "_small." . $extension;
												if (!verificar_url($nombre_completo_imagensmall)) {
													$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
												}
											}
											?>
											<div class="item<?php if ($i == 0) {
																echo (" active");
															} ?>">
												<img src="<?php echo ($nombre_completo_imagenoriginal); ?>" alt="" border="0" />
												<?php
												if (($registros2->fields["COMENTARIO"] != null) && ($registros2->fields["COMENTARIO"] != "")) {
													?>
													<div class="carousel-caption">
														<?php echo (stripslashes($registros2->fields["COMENTARIO"])); ?>
													</div>
												<?php
												}
												?>
											</div>
											<?php
											$registros2->MoveNext();
											$i++;
										}
										?>
									</div>
									<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
									<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
								</div>
							<?php
							} else {
								?>
								<h5 style="text-align: center;">No hay im&aacute;genes disponibles en este momento.</h5>
							<?php
							}
							?>
						</div>

						<div id="tab03" class="tab-pane">
							<a href="javascript:void(0);" class="btn btn-danger pull-right" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="producto" data-id="<?php echo ($registros->fields["ID_PRODUCTO"]); ?>" title="Dejar Comentario" style="color: #ffffff;margin-top: 10px;"><i class="icon-comment icon-white"></i> Dejar comentario</a>
							<?php
							if ($datosComentarios["CANTIDAD"] > 0) {
								//	obtengo los comentarios del contenido visualizado
								$registrosComentarios = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'productos') and (id_cabeza = '" . $registros->fields["ID_PRODUCTO"] . "') and (activo = 1) ORDER BY fecha_alta");
								while ($registrosComentarios && !$registrosComentarios->EOF) {
									?>
									<blockquote>
										<p><?php echo (stripslashes($registrosComentarios->fields["MENSAJE"])); ?></p>
										<small><?php echo (stripslashes($registrosComentarios->fields["NOMBRE"])); ?> (<?php echo (formato_fecha($registrosComentarios->fields["FECHA_ALTA"], 3)); ?>)</small>
									</blockquote>
									<?php
									$registrosComentarios->MoveNext();
								}
								$registrosComentarios->close();
							} else {
								?>
								<h5 style="text-align: center;">No hay comentarios disponibles en este momento.</h5>
							<?php
							}
							?>
						</div>

					</div>
				</div>
				<footer style="margin-top: 5px;">
					<i class="icon-ok"></i> <strong>Leido</strong>: <?php echo (($registros->fields["CLICK"] + 1)); ?> veces /
					<i class="icon-comment"></i> <strong>Comentarios</strong>: <?php echo ($datosComentarios["CANTIDAD"]); ?> /
					<i class="icon-download-alt"></i>
					<?php
					if ($datosArchivosadjuntos["CANTIDAD"] > 0) {
						?>
						<a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Adjuntos" data-contenido="<?php echo ($_GET['menu']); ?>" data-id="<?php echo ($registros->fields["ID_PRODUCTO"]); ?>" title="Archivos adjuntos"><strong>Archivos Adjuntos</strong>: <?php echo ($datosArchivosadjuntos["CANTIDAD"]); ?></a>
					<?php
					} else {
						?>
						<strong>Archivos Adjuntos</strong>: 0
					<?php
					}
					?>
				</footer>
				<?php
				$registros->MoveNext();
			}
			$registros->close();
		}
		?>
	</div>
	<div class="modal-footer">
		<div class="row-fluid">
			<div class="span8" style="text-align: left;">
				<?php echo ($categorias); ?>
			</div>
			<div class="span4">
				<button type="button" class="btn btn-danger" id="btCancelarRegistro" data-dismiss="modal">Cerrar ventana</button>
			</div>
		</div>
	</div>
</form>