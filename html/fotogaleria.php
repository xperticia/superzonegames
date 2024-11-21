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

							if(isset($_GET['cat'])) {
								if($_GET['cat'] == "") {
									$registros = $conexion->Query("SELECT *,count(*) as CANTIDAD FROM fotogaleria WHERE (activo = 1) GROUP BY categoria ORDER BY categoria");
									$path = "Listado de categor&iacute;a";
									$url = urlencode($sitio_url."?menu=".$_GET['menu']."&cat=");
								} else {
									$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (activo = 1) and (categoria = '".$_GET['cat']."') ORDER BY fecha_alta DESC,id_foto DESC");
									$path = "Listado de categor&iacute;a: &quot;".ucfirst($_GET['cat'])."&quot;";
									$url = urlencode($sitio_url."?menu=".$_GET['menu']."&cat=".$_GET['cat']);
								}
							} else {
								$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (activo = 1) ORDER BY fecha_alta DESC,id_foto DESC LIMIT 0,8");
								$path = "Listado de Fotos";
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
						if(!isset($_GET['cat'])) {
							?>
						<p>Esta secci&oacute;n esta pensada para mostrar (mediante im&aacute;genes) eventos, actividades o experiencias del quehacer archiv&iacute;stico.</p>
						<p>Las im&aacute;genes est&aacute;n organizadas en categor&iacute;as, las cuales se pueden seleccionar en la lista del lado derecho.</p>
						<p>A continuaci&oacute;n se muestran las &uacute;ltimas im&aacute;genes cargadas.</p>			
							<?php
						}
						?>

						<ul class="thumbnails">
								<?php
									$i = 0;
									while(!$registros->EOF) {

										$nombre_completo_imagenoriginal = $path_imagenes."fotogaleria/".$registros->fields['NOMBRE_ARCHIVO'];
										if(($registros->fields['NOMBRE_ARCHIVO'] != null) and file_exists("../".$nombre_completo_imagenoriginal)) { 
											//	obtengo la extension del archivo
											$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
											$nombre = $res[0];
											$extension = $res[count($res) - 1];
											//	obtengo la ubicacion de la imagen redimensionada original
											$nombre_completo_imagensmall = $path_imagenes."fotogaleria/".$nombre."_small.".$extension;
											if(!file_exists("../".$nombre_completo_imagensmall)) {
												$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
											}
										} else { 
											if(($registros->fields['NOMBRE_ARCHIVO'] != null) and !file_exists("../".$nombre_completo_imagenoriginal)) { 
												$nombre_completo_imagenoriginal = null;
												$nombre_completo_imagensmall = null;
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
									<?php
										if($nombre_completo_imagenoriginal == null) {
											?>
									<a href="javascript:void();" class="thumbnail" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE_ARCHIVO']); ?>">
										<img src="<?php echo($nombre_completo_imagenoriginal); ?>" border="0" />
									</a>
											<?php
											
										} else {
											if(isset($_GET['cat']) and ($_GET['cat'] == "")) {
												?>
									<a href="<?php echo("./?menu=".$_GET['menu']."&accion=listar&cat=".$registros->fields["CATEGORIA"]); ?>" class="thumbnail" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE_ARCHIVO']); ?>">
										<img src="<?php echo($nombre_completo_imagensmall); ?>" border="0" />
									</a>
										<?php
										//	obtengo el resto de las imagenes de la categoria
										$fotosCategoria = $conexion->Query("SELECT * FROM fotogaleria WHERE (activo = 1) and (categoria = '".stripslashes($registros->fields["CATEGORIA"])."') and (nombre_archivo <> '".$registros->fields['NOMBRE_ARCHIVO']."') ORDER BY fecha_alta DESC,id_foto DESC");
										if($fotosCategoria) {
											while(!$fotosCategoria->EOF) {
												$nombre_completo_imagenoriginal = $path_imagenes."fotogaleria/".$fotosCategoria->fields['NOMBRE_ARCHIVO'];
												if(($fotosCategoria->fields['NOMBRE_ARCHIVO'] != null) and file_exists("../".$nombre_completo_imagenoriginal)) { 
													?>
									<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="thumbnail foto" rel="colorbox" title="<?php echo("[".stripslashes($fotosCategoria->fields["CATEGORIA"])."] ".$fotosCategoria->fields['NOMBRE_ARCHIVO']); ?>" style="display: none;"></a>
													<?php
												} 
												$fotosCategoria->MoveNext();	
											}
											$fotosCategoria->close();
										}
										?>
									<div class="caption" style="text-align: center;">
										<?php 
											echo("<a href=\""."./?menu=".$_GET['menu']."&accion=listar&cat=".$registros->fields["CATEGORIA"]."\" title=\"\"><strong>".stripslashes($registros->fields["CATEGORIA"])."</strong></a><br />"); 
											if($registros->fields["CANTIDAD"] > 1) {
												echo($registros->fields["CANTIDAD"]." im&aacute;genes");
											} else {
												echo($registros->fields["CANTIDAD"]." imagen");
											}
										?>
									</div>
												<?php												
											} else {
												?>
									<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="thumbnail foto" rel="colorbox" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE_ARCHIVO']); ?>">
										<img src="<?php echo($nombre_completo_imagensmall); ?>" border="0" />
									</a>
									<div class="caption" style="text-align: center;">
										<?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?>
									</div>
												<?php
											}
										}
									?>
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

							//	obtengo todas las CATEGORIAS del menu de contenidos
							if($categoriasActual == "") {
								$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM fotogaleria WHERE (activo = 1) GROUP BY categoria ORDER BY categoria LIMIT 0,5");
							} else {
								$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM fotogaleria WHERE (activo = 1) and (categoria <> '".$categoriasActual."') GROUP BY categoria ORDER BY categoria LIMIT 0,5");
							}
							if($registros and $registros->RecordCount() > 0) {
								$categorias .= "<span class='label label-important'>Categor&iacute;as</span>\n";
								$categorias .= "<div class='well'>\n";
								$categorias .= "	<ul>\n";
								while(!$registros->EOF) {
									$categorias .= "		<li><a href='"."./?menu=".$_GET['menu']."&accion=listar&cat=".$registros->fields["CATEGORIA"]."' title=''>".stripslashes($registros->fields["CATEGORIA"])."</a> [".$registros->fields["CANTIDAD"]."]</li>";
									$registros->MoveNext();
								}
								$registros->close();
								if($registros->RecordCount() >= 5) {
									$categorias .= "		<li><a href='"."./?menu=".$_GET['menu']."&accion=listar&cat=' title=''>Más categor&iacute;as</a></li>";
								}
								$categorias .= "	</ul>\n";
								$categorias .= "</div>\n";
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