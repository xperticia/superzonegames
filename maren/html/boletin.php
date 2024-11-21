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

							//	obtener datos segun los parametros
							$registros = $conexion->Query("SELECT * FROM boletin WHERE (activo = 1) ORDER BY fecha_alta DESC,id_boletin DESC");
							$path = "Listado de boletines";
							$url = urlencode($sitio_url."?menu=".$_GET['menu']);
							if($registros) {									
								//	armo los enlaces para compartir
								$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
								$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 
								?>
						<h1><?php echo($path); ?></h1>
						<?php echo($Compartir_ContenidoHorizontal); ?>
						<p>En esta secci&oacute;n se lista todas las ediciones de <strong>MundoNoticias</strong>, bolet&iacute;n electr&oacute;nico de distribuci&oacute;n semanal, con noticias, eventos e informaci&oacute;n variada del quehacer archiv&iacute;stico.</p>
						<div class="alert" style="margin: 20px auto;width: 300px;">
							<p>Deseas recibir las noticias de <strong><?php echo($sitio_nombre); ?></strong> en tu bandeja de entrada?, podes suscribirte a nuestro bolet&iacute;n electr&oacute;nico <strong>MundoNoticias</strong>, sin costo alguno: [ <a data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" href="javascript:void(0);" data-tipo="Registro" data-contenido="<?php echo($_GET['menu']); ?>" data-id="" title="Formulario de Registro">registrarse</a> ]</p>
						</div>
						<table class="table table-striped table-bordered table-condensed">
						<thead>
						<tr>
							<th width="40">Fecha</th>
							<th>Titulo</th>
							<th width="40">Leido</th>
						</tr>
						</thead>
						<tbody>
								<?php
								while(!$registros->EOF) {
									?>
						<tr>
							<td><?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?></td>
							<td><a href="<?php echo("preview.php?idc={EMAIL}&idb=".$registros->fields["ID_BOLETIN"]); ?>" class="iframe" rel="colorbox" title=""><?php echo(stripslashes($registros->fields["TITULO"])); ?></a></td>
							<td style="text-align: right;"><?php echo($registros->fields["CLICK"]); ?></td>
						</tr>
									<?php
									$registros->MoveNext();
								}
								$registros->close();
								?>
						</tbody>
						</table>
								<?php
							}

							//	obtengo todas la CATEGORIAS del menu de contenidos
							if($categoriasActual == "") {
								$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM contenidos WHERE (tipo_contenido = '".$_GET['menu']."') and (activo = 1) GROUP BY categoria ORDER BY categoria LIMIT 0,5");
							} else {
								$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM contenidos WHERE (tipo_contenido = '".$_GET['menu']."') and (activo = 1) and (categoria <> '".$categoriasActual."') GROUP BY categoria ORDER BY categoria LIMIT 0,5");
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