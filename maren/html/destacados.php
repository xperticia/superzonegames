<?php
	require("../xpanel/config.php");
?>
				<div class="row-fluid">
					<div class="span9">
						{PUBLICIDAD_H}
						<?php
							$contenido = "";
							$categorias = "";
							$categoriasActual = "";

							if(isset($_GET['id'])) {
								$registros = $conexion->GetRow("SELECT usuarios.*,PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) and (id_usuario = '".$_GET['id']."') ORDER BY nombre_completo");
								$path = "Destacado: ".$registros["NOMBRE_COMPLETO"];
								$url = urlencode($sitio_url."?menu=".$_GET['menu']."&id=".$_GET['id']);
							} else {
								if(isset($_GET['cat'])) {
									if(isset($_GET['ciudad'])) {
										$registros = $conexion->Query("SELECT usuarios.*,PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) and (usuarios.codigo_pais = '".$_GET['cat']."') and (ciudad = '".$_GET['ciudad']."') ORDER BY nombre_completo");
									} else {
										$registros = $conexion->Query("SELECT usuarios.*,PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) and (usuarios.codigo_pais = '".$_GET['cat']."') ORDER BY nombre_completo");
									}
								} else {
									if(isset($_GET['ciudad'])) {
										$registros = $conexion->Query("SELECT usuarios.*,PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) and (ciudad = '".$_GET['ciudad']."') ORDER BY nombre_completo");
									} else {
										$registros = $conexion->Query("SELECT usuarios.*,PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) ORDER BY nombre_completo");
									}
								}
								$path = "Listado de ".ucfirst($_GET['menu']);
								$url = urlencode($sitio_url."?menu=".$_GET['menu']);
							}

							if($registros) {
								//	armo los enlaces para compartir
								$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
								$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 
								?>
						<h1><?php echo($path); ?></h1>
						<?php echo($Compartir_ContenidoHorizontal); ?>
								<?php
								if(!isset($_GET['id'])) {
									?>
						<p>En esta secci&oacute;n, se lista todas aquellas personas que han contribuido al desarrollo y fortalecimiento de la profesi&oacute;n archiv&iacute;stica, tanto en el contexto local, nacional e internacional; y permite, adem&aacute;s, acceder a visualizar su Curriculum Vitae u Hoja de Vida de cada uno de ellos.</p>
									<?php
								}
								?>
						<div class="well" style="padding: 5px;">
							Pa&iacute;ses: 							
							<select name="cat" title="Pa&iacute;ses" onchange="AbrirURL(this);" style="margin-bottom: 0;">
								<option value="<?php echo($sitio_url."?menu=".$_GET['menu']); ?>">Todos los pa&iacute;ses</option>
								<?php
								$paises = $conexion->Query("SELECT usuarios.CODIGO_PAIS,PAIS, count(*) as CANTIDAD FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) GROUP BY pais ORDER BY pais");
								while(!$paises->EOF) {
									?>
								<option value="<?php echo($sitio_url."?menu=".$_GET['menu']."&cat=".$paises->fields["CODIGO_PAIS"]); ?>" <?php if((isset($_GET['cat']) and ($_GET['cat'] == $paises->fields["CODIGO_PAIS"]))) { echo("selected='selected'"); } ?>><?php if($paises->fields["PAIS"] == "") { echo("Sin Asignar"); } else { echo(stripslashes($paises->fields["PAIS"])); } echo(" [".$paises->fields["CANTIDAD"]."]"); ?></option>
									<?php
									$paises->MoveNext();
								}
								$paises->close();

								?>
							</select>
							-
							Ciudades: 
								<?php
								$urlCiudades = $sitio_url."?menu=".$_GET['menu'];
								if(isset($_GET['cat'])) {
									$urlCiudades .= "&cat=".$_GET['cat'];
								}
								?>
							<select name="ciudad" title="Ciudades" onchange="AbrirURL(this);" style="margin-bottom: 0;">
								<option value="<?php echo($urlCiudades); ?>">Todas las ciudades</option>
								<?php
								if(isset($_GET['cat'])) {
									$ciudades = $conexion->Query("SELECT CIUDAD, count(*) as CANTIDAD FROM usuarios WHERE (destacado = 1) and (codigo_pais = '".$_GET['cat']."') GROUP BY ciudad ORDER BY ciudad");
								} else {
									$ciudades = $conexion->Query("SELECT CIUDAD, count(*) as CANTIDAD FROM usuarios WHERE (destacado = 1) GROUP BY ciudad ORDER BY ciudad");
								}
								while(!$ciudades->EOF) {
									?>
								<option value="<?php echo($urlCiudades."&ciudad=".$ciudades->fields["CIUDAD"]); ?>" <?php if((isset($_GET['ciudad']) and ($_GET['ciudad'] == $ciudades->fields["CIUDAD"]))) { echo("selected='selected'"); } ?>><?php if((isset($_GET['ciudad']) and ($_GET['ciudad'] == $ciudades->fields["CIUDAD"]))) { echo("selected='selected'"); } ?>><?php if($ciudades->fields["CIUDAD"] == "") { echo("Sin Asignar"); } else { echo(stripslashes($ciudades->fields["CIUDAD"])); } echo(" [".$ciudades->fields["CANTIDAD"]."]"); ?></option>
									<?php
									$ciudades->MoveNext();
								}
								$ciudades->close();

								?>
							</select>
						</div>

								<?php
								if(isset($_GET['id'])) {
									if($registros["FOTO"] == null) {
										$nombre_completo_imagenoriginal = $path_imagenes."fotos/"."fotouser.jpg";
										$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
									} else {
										$nombre_completo_imagenoriginal = $path_imagenes."fotos/".$registros["FOTO"];
										//verifico si el archivo existe
										if(file_exists("../".$nombre_completo_imagenoriginal)) {
										   //obtengo el nombre y extension del archivo small
										   $res = explode(".", $registros["FOTO"]);
										   $nombre = $res[0];
										   $extension = $res[count($res) - 1];
										   //obtengo la ubicacion de la imagen small
										   $nombre_completo_imagensmall = $path_imagenes."fotos/".$nombre."_small.".$extension;
											if(!file_exists("../".$nombre_completo_imagensmall)) {
												$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
											}
										} else {
											$nombre_completo_imagenoriginal = $path_imagenes."fotos/"."fotouser.jpg";
											$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
										}
									}
									?>
									<?php
										if(strpos($nombre_completo_imagenoriginal,"fotouser.jpg") === false) {
											?>
								<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="thumbnail archiveros" rel="colorbox" title="<?php echo(stripslashes($registros["NOMBRE_COMPLETO"]." (".$registros["CIUDAD"]." - ".$registros["PAIS"].")")); ?>">
									<img src="<?php echo($nombre_completo_imagenoriginal); ?>" height="100" border="0" />
								</a>
											<?php
										} else {
											?>
								<a href="javascript:void();" class="thumbnail" title="<?php echo(stripslashes($registros["NOMBRE_COMPLETO"])); ?>">
									<img src="<?php echo($nombre_completo_imagenoriginal); ?>" height="100" border="0" />
								</a>
											<?php
										}
									?>
									<?php
									echo(stripslashes($registros["CURRICULUM"]));

									//	obtengo todos los PAISES registrados
									$registros = $conexion->Query("SELECT usuarios.CODIGO_PAIS,PAIS, count(*) as CANTIDAD FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE (destacado = 1) GROUP BY pais ORDER BY pais LIMIT 0,5");
									if($registros and $registros->RecordCount() > 0) {
										$categorias .= "<span class='label label-important'>Pa&iacute;ses</span>\n";
										$categorias .= "<div class='well'>\n";
										$categorias .= "	<ul>\n";
										while(!$registros->EOF) {
											if($registros->fields["PAIS"] == "") {
												$categorias .= "		<li><a href=\""."./?menu=".$_GET['menu']."&cat=".$registros->fields["CODIGO_PAIS"]."\" title=''>Sin Asignar</a> [".$registros->fields["CANTIDAD"]."]</li>";
											} else {
												$categorias .= "		<li><a href=\""."./?menu=".$_GET['menu']."&cat=".$registros->fields["CODIGO_PAIS"]."\" title=''>".stripslashes($registros->fields["PAIS"])."</a> [".$registros->fields["CANTIDAD"]."]</li>";
											}
											$registros->MoveNext();
										}
										$registros->close();
										$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."\" title=''>Más pa&iacute;ses</a></li>";
										$categorias .= "	</ul>\n";
										$categorias .= "</div>\n";
									}

								} else {
								?>
						<ul class="thumbnails">
								<?php
									$i = 0;
									while(!$registros->EOF) {
										if($registros->fields["FOTO"] == null) {
											$nombre_completo_imagenoriginal = $path_imagenes."fotos/"."fotouser.jpg";
											$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
										} else {
											$nombre_completo_imagenoriginal = $path_imagenes."fotos/".$registros->fields["FOTO"];
											//verifico si el archivo existe
											if(file_exists("../".$nombre_completo_imagenoriginal)) {
											   //obtengo el nombre y extension del archivo small
											   $res = explode(".", $registros->fields["FOTO"]);
											   $nombre = $res[0];
											   $extension = $res[count($res) - 1];
											   //obtengo la ubicacion de la imagen small
											   $nombre_completo_imagensmall = $path_imagenes."fotos/".$nombre."_small.".$extension;
												if(!file_exists("../".$nombre_completo_imagensmall)) {
													$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
												}
											} else {
												$nombre_completo_imagenoriginal = $path_imagenes."fotos/"."fotouser.jpg";
												$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
											}
										}
										if(($i % 4) == 0) {
											?>
						</ul>
						<ul class="thumbnails">
											<?php
										}
										?>
							<li class="span3">
								<div class="thumbnail" style="min-height: 200px;">
									<a class="thumbnail" href="<?php echo("./?menu=".$_GET['menu']."&id=".$registros->fields["ID_USUARIO"]); ?>" title="<?php echo(stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?>">
										<img src="<?php echo($nombre_completo_imagenoriginal); ?>" height="100" border="0" />
									</a>
									<div class="caption" style="text-align: center;">
										<h5><?php echo(stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></h5>
										<p style="text-align: center;"><?php echo($registros->fields["CIUDAD"]." - ".$registros->fields["PAIS"]); ?></p>
									</div>
								</div>
							</li>
										<?php
										$registros->MoveNext();
										$i++;
									}
									$registros->close();
								?>
						</ul>
								<?php
								}
							}

							echo($contenido);
						?>
						<div id="path"><?php echo($path); ?></div>
						<p>&nbsp;</p>
					</div>

					<div class="span3">
						<form id="fbuscar" method="get" class="well form-search" action="./">
							<input type="hidden" name="menu" value="buscar" />
							<input type="text" class="input-small search-query" name="q" placeholder="buscar" required />
							<button type="submit" class="btn btn-small"><i class="icon-search"></i></button>
						</form>
						<div style="text-align: center;margin: 0px;padding-top: 10px;"> 
							<p><strong>siguenos en</strong></p>
							<ul class="pager">
								<?php 
									if(isset($sitio_url_facebook) and ($sitio_url_facebook != "")) {
										?>
									<li><a href="<?php echo($sitio_url_facebook); ?>" target="_blank" alt="siguenos en facebook" title="siguenos en facebook"><img src="images/facebook.png" width="32" alt="" border="0" /></a></li>
										<?php
									}
								?>
								<?php
									if(isset($sitio_url_twitter) and ($sitio_url_twitter != "")) {
										?>
									<li><a href="<?php echo($sitio_url_twitter); ?>" target="_blank" alt="siguenos en twitter" title="siguenos en twitter"><img src="images/twitter.png" width="32" alt="" border="0" /></a></li>
										<?php
									}
								?>
								<?php
									if(isset($sitio_url_linkedin) and ($sitio_url_linkedin != "")) {
										?>
									<li><a href="<?php echo($sitio_url_linkedin); ?>" target="_blank" alt="siguenos en linkedin" title="siguenos en linkedin"><img src="images/linkedin.png" width="32" alt="" border="0" /></a></li>
										<?php
									}
								?>
							</ul>							
						</div>
						<div class="well" style="text-align: center;margin: 20px 0;">
							<a data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" href="javascript:void(0);" data-tipo="Registro" data-contenido="<?php echo($_GET['menu']); ?>" data-id="" title="Formulario de Registro" class="btn btn-danger" style="color: #ffffff;width: 90%;">Registrarse</a>
							<a href="./?menu=iniciarsesion" title="Iniciar sesión" class="btn btn-danger" style="color: #ffffff;width: 90%;margin-top: 10px;">Iniciar sesi&oacute;n</a>
						</div>

						<?php echo($categorias.$Compartir_ContenidoLateral); ?>
						<div style="text-align: center;margin: 20px -5px;"> 
						{PUBLICIDAD_V}
						</div>

						<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FMundo-Archivistico%2F277141889016906&amp;width=220&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:220px; height:258px;" allowTransparency="true"></iframe>
						<hr />

						<div id="google_translate_element"></div>
						<script>
						function googleTranslateElementInit() {
						  new google.translate.TranslateElement({
							pageLanguage: 'es'
						  }, 'google_translate_element');
						}
						</script>
						<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>            
						<hr />
					</div>
				</div>