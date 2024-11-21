<!-- inicio contenido -->
<?php
	$contenido = "";
	$categorias = "";
	$categoriasActual = "";

	$url = urlencode($sitio_url);
	if(isset($_GET['menu'])){ $url .= "?menu=".$_GET['menu']; }
	if(isset($_GET['id'])){ $url .= "&id=".$_GET['id']; }
	if(isset($_GET['action'])){ $url .= "&accion=".$_GET['accion']; }
	if(isset($_GET['c'])){ $url .= "&c=".$_GET['c']; }
	if(isset($_GET['g'])){ $url .= "&g=".$_GET['g']; }

	//	armo los enlaces para compartir
	$categorias .= "	<div class=\"btn-group pull-right\">\n";
	$categorias .= "		<a class=\"btn\" href=\"https://www.facebook.com/sharer/sharer.php?u=".$url."\" title=\"Compartir en Facebook\" target=\"_blank\"><img src=\"images/facebook.png\" width=\"16\" /></a>";
	$categorias .= "		<a class=\"btn\" href=\"https://twitter.com/intent/tweet?text=".$url."\" title=\"Compartir en Twitter\" target=\"_blank\"><img src=\"images/twitter.png\" width=\"16\" /></a>";
	$categorias .= "		<a class=\"btn\" href=\"whatsapp://send?text=".$url."\" title=\"Compartir en WhatsApp\" target=\"_blank\"><img src=\"images/whatsapp.png\" width=\"16\" /></a>";
	$categorias .= "	</div>\n";

	$categorias .= "<div class=\"btn-toolbar\" style=\"margin: 0;\">\n";

	//	obtener datos segun los parametros
	if(isset($_GET['accion']) and ($_GET['accion'] == "listar")) {
		if(isset($_GET['c'])) {
			$categoriasActual = $_GET['c'];
			$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (upper(categoria) = upper('".$_GET['c']."')) ORDER BY fecha_alta DESC,id_contenido DESC");
			$titulo = "Listado de categor&iacute;a: &quot;".ucfirst($_GET['c'])."&quot;";
			$path .= " &raquo; ".$titulo;
			$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&c=".$_GET['c']);
		} else {
			$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') ORDER BY fecha_alta DESC,id_contenido DESC");
			$titulo = "Listado de ".ucfirst($_GET['menu']);
			$path .= " &raquo; ".$titulo;
			$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']);
		}
		if($registros) {
			//	armo los enlaces para compartir
			$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
			$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 
			?>
<h1><?php echo($titulo); ?></h1>
<header>
	<?php echo($Compartir_ContenidoHorizontal); ?>
</header>
<table class="table table-striped table-bordered table-condensed">
<thead>
<tr>
	<th width="40">Fecha</th>
	<th>Titulo</th>
	<th width="50">Leido</th>
</tr>
</thead>
<tbody>
			<?php
			while(!$registros->EOF) {
				?>
<tr>
	<td><?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?></td>
	<td><a href="<?php echo("./?menu=".$_GET['menu']."&id=".$registros->fields["ID_CONTENIDO"]); ?>" title=""><?php echo(stripslashes($registros->fields["TITULO"])); ?></a></td>
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
	} else {								
		//	obtengo el ULTIMO CONTENIDO e inicializo variables
		if(!isset($_GET['id'])) {
			$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') ORDER BY fecha_alta DESC,id_contenido DESC LIMIT 1");
		} else {
			$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (id_contenido = '".$_GET['id']."') ORDER BY fecha_alta DESC,id_contenido DESC LIMIT 1");
		}
		if($registros and $registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if(!isset($_GET['id'])) {
					$_GET['id'] = $registros->fields["ID_CONTENIDO"];
				}
			
				//	armo los enlaces para compartir
				$url = urlencode($sitio_url."?menu=".$_GET['menu']."&id=".$registros->fields["ID_CONTENIDO"]);
				$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
				$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 

				//	Actualizo la cantidad de veces leidas del contenido: CLICK
				$conexion->Execute("UPDATE contenidos SET click = click + 1 WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."')");

				//	verifico si posee COMENTARIOS
				$datosComentarios = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = '".$_GET['menu']."') and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1)");
				//	verifico si posee ARCHIVOS ADJUNTOS
				$datosArchivosadjuntos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");

				//	Contenidos
				$path .= " &raquo; ".stripslashes($registros->fields["TITULO"]);
				?>
<h1><?php echo(stripslashes($registros->fields["TITULO"])); ?></h1>
<header style="display: none;">
	<p>
		<i class="icon-calendar"></i> <strong>Publicado el</strong>: <?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?> / 
		<i class="icon-ok"></i> <strong>Leido</strong>: <?php echo(($registros->fields["CLICK"]+1)); ?> veces / 
		<i class="icon-comment"></i> <a href="#comentarios"><strong>Comentarios</strong>: <?php echo($datosComentarios["CANTIDAD"]); ?></a> / 
		<i class="icon-download-alt"></i> 
		<?php
			if($datosArchivosadjuntos["CANTIDAD"] > 0) {
			?>
		<a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Adjuntos" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" title="Archivos adjuntos"><strong>Archivos Adjuntos</strong>: <?php echo($datosArchivosadjuntos["CANTIDAD"]); ?></a>
			<?php
			} else {
			?>
		<strong>Archivos Adjuntos</strong>: 0
			<?php
			}
		?>
	</p>
	<?php echo($Compartir_ContenidoHorizontal); ?>
</header>
				<?php
				//	verifico si posee IMAGENES el contenido
				$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY id_archivo");
				if($registros2) {
					if($registros2->RecordCount() > 0) {
						$i = 1;
						while(!$registros2->EOF) {
							//	verifico si el archivo existe
							$nombre_completo_imagenoriginal = $path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
							if(file_exists($nombre_completo_imagenoriginal)) {
								//	obtengo el nombre y extension del archivo small
								$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
								$nombre = $res[0];
								$extension = $res[count($res)-1];
								//	obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = $path_imagenes."contenidos/".$nombre."_small.".$extension;
								if(!file_exists($nombre_completo_imagensmall)) {
									$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
								}
								//	muestra la imagen
								$imagen = "";
								//$imagen .= "<ul class=\"thumbnails\">\n";
								//$imagen .= "	<li class=\"span6\">\n";
								if($registros2->fields["COMENTARIO"] == null){
									$imagen .= "		<a href=\"".$nombre_completo_imagenoriginal."\" class=\"thumbnail\" rel=\"colorbox\" title=\"".stripslashes($registros->fields["TITULO"])."\">";
								} else {
									$imagen .= "		<a href=\"".$nombre_completo_imagenoriginal."\" class=\"thumbnail\" rel=\"colorbox\" title=\"".stripslashes($registros2->fields["COMENTARIO"])."\">";
								}
								$imagen .= "			<img src='".$nombre_completo_imagensmall."' border='0' />";
								$imagen .= "		</a>\n";
								$imagen .= "		<div class=\"caption\">".html_entity_decode(stripslashes($registros2->fields["COMENTARIO"]))."</div>\n";
								//$imagen .= "	</li>\n";
								//$imagen .= "</ul>\n";
							} else {
								$imagen = "<span class=\"label label-warning\">".$registros2->fields["NOMBRE_ARCHIVO"].".</span>";
							}
							$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
							//	agrego la imagen al contenido
							$registros->fields["CONTENIDO"] = str_replace($referencia,$imagen,$registros->fields["CONTENIDO"]);
							++$i;

							$registros2->MoveNext();
						}
						$registros2->close();
					}								
				}
				echo(stripslashes($registros->fields["CONTENIDO"]));
				?>
<footer>
	<p>
		<i class="icon-calendar"></i> <strong>Publicado el</strong>: <?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?> / 
		<i class="icon-ok"></i> <strong>Leido</strong>: <?php echo(($registros->fields["CLICK"]+1)); ?> veces / 
		<i class="icon-comment"></i> <a href="#comentarios"><strong>Comentarios</strong>: <?php echo($datosComentarios["CANTIDAD"]); ?></a> / 
		<i class="icon-download-alt"></i> 
		<?php
			if($datosArchivosadjuntos["CANTIDAD"] > 0) {
			?>
		<a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Adjuntos" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" title="Archivos adjuntos"><strong>Archivos Adjuntos</strong>: <?php echo($datosArchivosadjuntos["CANTIDAD"]); ?></a>
			<?php
			} else {
			?>
		<strong>Archivos Adjuntos</strong>: 0
			<?php
			}
		?>
	</p>
	<?php echo($Compartir_ContenidoHorizontal); ?>
</footer>

<a name="comentarios"></a>
<a href="javascript:void(0);" class="btn btn-danger pull-right" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" title="Dejar Comentario" style="color: #ffffff;margin-top: 10px;"><i class="icon-comment icon-white"></i> Dejar comentario</a>
<h2 style="border-bottom: 1px solid #ddd;">Comentarios</h2>
				<?php
				if($datosComentarios["CANTIDAD"] > 0) {
					//	obtengo los comentarios del contenido visualizado
					$registrosComentarios = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = '".$_GET['menu']."') and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1) ORDER BY fecha_alta");
					while($registrosComentarios and !$registrosComentarios->EOF) {
						?>
<blockquote>
	<p><?php echo(stripslashes($registrosComentarios->fields["MENSAJE"])); ?></p>
	<small><?php echo(stripslashes($registrosComentarios->fields["NOMBRE"])); ?> (<?php echo(formato_fecha($registrosComentarios->fields["FECHA_ALTA"],3)); ?>)</small>
</blockquote>
						<?php
						$registrosComentarios->MoveNext();					
					}
					$registrosComentarios->close();
				} else {
					?>
<blockquote>
	<p>No hay comentarios disponibles en este momento, se el primero en hacerlo. <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" title="Dejar Comentario">clic aqu&iacute;</a>.</p>
</blockquote>
					<?php
				}											

				$registros->MoveNext();
			}
			$registros->close();
		} else {
			$path .= "Error";
			?>
<div class="alert alert-block alert-error" style="margin: 20px auto;">
	<h4 class="alert-heading">Atenci&oacute;n</h4>
	<p>No hay registros en el menu de contenido: <strong><?php echo(ucfirst($_GET['menu'])); ?></strong></p>
</div>
			<?php
		}

		//	obtengo todas las ENTRADAS de la categorias actual
		$registros = $conexion->Query("SELECT CATEGORIA FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (id_contenido = '".$_GET['id']."') GROUP BY categoria ORDER BY categoria");
		while($registros and !$registros->EOF) {
			$categoriasActual = $registros->fields["CATEGORIA"];
			$categorias .= "	<div class=\"btn-group\">\n";
			$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Categor&iacute;a: ".stripslashes($registros->fields["CATEGORIA"])." <span class=\"caret\"></span></button>\n";
			$categorias .= "		<ul class=\"dropdown-menu\">\n";
			//	obtengo los contenidos
			$resultados = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (categoria = '".$registros->fields["CATEGORIA"]."') ORDER BY fecha_alta DESC");
			$registros2 = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (categoria = '".$registros->fields["CATEGORIA"]."') ORDER BY fecha_alta DESC,id_contenido DESC LIMIT 0,5");
			while($registros2 and !$registros2->EOF) {
				if($_GET['id'] == $registros2->fields["ID_CONTENIDO"]) {
					$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros2->fields["ID_CONTENIDO"]."\">".stripslashes($registros2->fields["TITULO"])."</a></li>\n";
				} else {
					$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros2->fields["ID_CONTENIDO"]."\">".stripslashes($registros2->fields["TITULO"])."</a></li>\n";
				}
				$registros2->MoveNext();
			}
			$registros2->close();
			if($resultados["CANTIDAD"] >= 5) {
				$categorias .= "			<li class=\"divider\"></li>\n";
				$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&accion=listar&cat=".stripslashes($registros->fields["CATEGORIA"])."\">M&aacute;s entradas anteriores</a></li>\n";
			}
			$categorias .= "		</ul>\n";
			$categorias .= "	</div>\n";
			$registros->MoveNext();
		}
		$registros->close();
	}

	//	obtengo todas las CATEGORIAS del menu de contenidos
	if($categoriasActual == "") {
		$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') GROUP BY categoria ORDER BY categoria LIMIT 0,5");
	} else {
		$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (categoria <> '".$categoriasActual."') GROUP BY categoria ORDER BY categoria LIMIT 0,5");
	}
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Categor&iacute;as <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&accion=listar&cat=".stripslashes($registros->fields["CATEGORIA"])."\">".stripslashes($registros->fields["CATEGORIA"])."</a></li>\n";
			$registros->MoveNext();
		}
		$registros->close();
		if($registros->RecordCount() >= 5) {
			$categorias .= "		<li class=\"divider\"></li>\n";
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&accion=listar&cat=\">M&aacute;s categor&iacute;as</a></li>\n";
		}
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	//	obtengo las ULTIMAS ENTRADAS
	$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') ORDER BY fecha_alta DESC,id_contenido DESC LIMIT 0,4");
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">&Uacute;ltimos contenidos <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros->fields["ID_CONTENIDO"]."\">".stripslashes($registros->fields["TITULO"])."</a></li>\n";
			$registros->MoveNext();
		}
		$registros->close();
		if($registros->RecordCount() >= 5) {
			$categorias .= "		<li class=\"divider\"></li>\n";
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&accion=listar\">M&aacute;s contenidos</a></li>\n";
		}
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	//	obtengo las ENTRADAS MAS LEIDAS
	$registros = $conexion->Query("SELECT * FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$_GET['menu']."') and (click > 0) ORDER BY click DESC LIMIT 0,4");
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Contenidos m&aacute;s le&iacute;das <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros->fields["ID_CONTENIDO"]."\">".stripslashes($registros->fields["TITULO"])."</a></li>\n";
			$registros->MoveNext();
		}
		$registros->close();
		if($registros->RecordCount() >= 5) {
			$categorias .= "		<li class=\"divider\"></li>\n";
			$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&accion=listar\">M&aacute;s contenidos</a></li>\n";
		}
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	$categorias .= "</div>";
?>
<p>&nbsp;</p>
<!-- fin contenido -->