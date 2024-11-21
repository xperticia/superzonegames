<!-- inicio contenido -->
<?php
	$contenido = "";
	$categorias = "";
	$categoriasActual = "";
	$generoActual = "";

	$categorias .= "<div class=\"btn-toolbar\" style=\"margin: 0;\">\n";
	$categorias .= "	<button class=\"btn\" onclick=\"javascript:window.location='./?menu=productos';\">Buscador</button>\n\n";

	//	obtener datos segun los parametros
	if(isset($_GET['accion']) and ($_GET['accion'] == "listar")) {
		if(isset($_GET['c'])) {
			if($_GET['c'] != "") {
				$categoriasActual = $_GET['c'];
				$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) and (categoria = '".$_GET['c']."') ORDER BY nombre");
				$titulo = "Listado de categor&iacute;a: &quot;".ucfirst($_GET['c'])."&quot;";
				$path .= " &raquo; ".$titulo;
				$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&c=".$_GET['c']);
			} else {
				$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) GROUP BY categoria ORDER BY categoria");
				$titulo = "Listado de categor&iacute;as";
				$path .= " &raquo; ".$titulo;
				$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&c=");
			}
		} else {
			if(isset($_GET['g'])) {
				if($_GET['g'] != "") {
					$generoActual = $_GET['g'];
					$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) and ((genero = '".$_GET['g']."') or (genero LIKE '%".$_GET['g']."%')) ORDER BY genero");
					$titulo = "Listado de g&eacute;nero &quot;".ucfirst($_GET['g'])."&quot;";
					$path .= " &raquo; ".$titulo;
					$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&g=".$_GET['g']);
				} else {
					$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
					$titulo = "Listado de g&eacute;neros";
					$path .= " &raquo; ".$titulo;
					$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&g=");
				}
			} else {
				$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) ORDER BY nombre");
				$titulo = "Listado de ".ucfirst($_GET['menu']);
				$path .= " &raquo; ".$titulo;
				$url = urlencode($sitio_url."?menu=".$_GET['menu']."&accion=".$_GET['accion']);
			}
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
<table class="table table-striped table-bordered table-condensed" id="listaProductos">
<thead>
			<?php
			if(isset($_GET['c']) and ($_GET['c'] == "")) {
				?>
<tr>
	<th width="20">#</th>
	<th>Categor&iacute;a</th>
	<th width="50">Cantidad</th>
</tr>
</thead>
<tbody>
				<?php
				$i=1;
				while(!$registros->EOF) {
					?>
<tr>
	<td style="text-align: right;"><?php echo($i); ?></td>
	<td><a href="<?php echo("./?menu=".$_GET['menu']."&accion=listar&c=".stripslashes($registros->fields["CATEGORIA"])); ?>" title=""><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></a></td>
	<td style="text-align: right;"><?php echo($registros->fields["CANTIDAD"]); ?></td>
</tr>
					<?php
					$registros->MoveNext();
					$i++;
				}
				$registros->close();
			} else {
				if(isset($_GET['g']) and ($_GET['g'] == "")) {
					$listaGeneros = array();
					$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
					while(!$registros->EOF) {
						if(strpos($registros->fields["GENERO"],",") > 0) {
							$arrayGeneros = explode(",",stripslashes($registros->fields["GENERO"]));
							foreach($arrayGeneros as $key=>$val) {
								if(!in_array(trim($val),$listaGeneros)) {
									array_push($listaGeneros,trim($val));
								}
							}
						} else {
							if(!in_array($registros->fields["GENERO"],$listaGeneros)) {
								array_push($listaGeneros,stripslashes($registros->fields["GENERO"]));
							}
						}
						$registros->MoveNext();
					}
					sort($listaGeneros);
					?>
<tr>
	<th width="20">#</th>
	<th>G&eacute;nero</th>
	<th width="50">Cantidad</th>
</tr>
</thead>
<tbody>
					<?php
					$i=1;
					foreach($listaGeneros as $key=>$val) {
						$cantidad = 0;
						$datosGenero = $temp->Query("SELECT count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) and ((genero LIKE '%".$val."%') and (genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
						if($datosGenero) {
							while(!$datosGenero->EOF) {
								$cantidad += $datosGenero->fields["CANTIDAD"];
								$datosGenero->MoveNext();
							}
						}
						?>
<tr>
	<td style="text-align: right;"><?php echo($i); ?></td>
	<td><a href="<?php echo("./?menu=".$_GET['menu']."&g=".$val); ?>" title=""><?php echo($val); ?></a></td>
	<td style="text-align: right;"><?php echo($cantidad); ?></td>
</tr>
						<?php
						$i++;
					}
				} else {
					?>
<tr>
	<th width="20">#</th>
	<th>Productos</th>
</tr>
</thead>
<tbody>
				<?php
					$i=1;
					while(!$registros->EOF) {
					?>
<tr>
	<td style="text-align: right;"><?php echo($i); ?></td>
	<td valign="top">
		<?php
		$nombre_completo_imagenoriginal = $path_imagenes."productos/".$registros->fields['FOTO'];
		if(($registros->fields['FOTO'] != null) and file_exists($nombre_completo_imagenoriginal)) { 
			//	obtengo la extension del archivo
			$res = explode(".", $registros->fields["FOTO"]);
			$nombre = $res[0];
			$extension = $res[count($res) - 1];
			//	obtengo la ubicacion de la imagen redimensionada original
			$nombre_completo_imagensmall = $path_imagenes."productos/".$nombre."_small.".$extension;
			if(!file_exists($nombre_completo_imagensmall)) {
				$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
			}
		} else { 
			$nombre_completo_imagenoriginal = "images/productos/producto.jpg";
			$nombre_completo_imagensmall = "images/productos/producto_small.jpg";
		} 
		?>
		<a href="<?php echo("./?menu=".$_GET['menu']."&id=".$registros->fields["ID_PRODUCTO"]); ?>" class="thumbnail" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE']); ?>" style="float: left;margin: 0 10px 10px 0;">
			<img src="<?php echo($nombre_completo_imagensmall); ?>" border="0" style="height: 100px;" />
		</a>
		<p><strong><a href="<?php echo("./?menu=".$_GET['menu']."&id=".$registros->fields["ID_PRODUCTO"]); ?>" title=""><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></a></strong></p>
		<p>
			<strong>Categoria:</strong> <a href="<?php echo("./?menu=".$_GET['menu']."&c=".stripslashes($registros->fields["CATEGORIA"])); ?>" title=""><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></a>
			<br />
			<strong>G&eacute;nero: </strong>
			<?php
			if(($registros->fields["GENERO"] != null) and ($registros->fields["GENERO"] != "")) {
				$contador = 0;
				$generos = explode(",",stripslashes($registros->fields["GENERO"]));
				foreach($generos as $key=>$val) {
					if ($contador++ > 0) echo(", ");
					echo("<a href=\"?menu=".$_GET['menu']."&accion=listar&g=".trim($val)."\">".trim($val)."</a>");
				}
			}
			?>
			<br />
			<strong>Leido: </strong><?php echo($registros->fields["CLICK"]); ?>
			<br />
			<strong>C&oacute;digo: </strong><?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>
		</p>
	</td>
</tr>
					<?php
						$registros->MoveNext();
						$i++;
					}
					$registros->close();
				}
			}
			?>
</tbody>
</table>
			<?php
		}
	} else {								
		if(isset($_GET['id'])) {
			//	obtengo el CONTENIDO segun su ID
			$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (id_producto = '".$_GET['id']."') ORDER BY id_producto DESC LIMIT 1");
			if($registros) {
				while(!$registros->EOF) {
					if(!isset($_GET['id'])) {
						$_GET['id'] = $registros->fields["ID_PRODUCTO"];
					}
				
					//	armo los enlaces para compartir
					$url = urlencode($sitio_url."?menu=".$_GET['menu']."&id=".$registros->fields["ID_PRODUCTO"]);
					$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
					$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 

					//	Actualizo la cantidad de veces leidas del contenido: CLICK
					$conexion->Execute("UPDATE productos SET click = click + 1 WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");

					//	verifico si posee COMENTARIOS
					$datosComentarios = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = '".$_GET['menu']."') and (id_cabeza = '".$registros->fields["ID_PRODUCTO"]."') and (activo = 1)");
					//	verifico si posee ARCHIVOS ADJUNTOS
					$datosArchivosadjuntos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");

					//	Contenidos
					$path .= " &raquo; ".stripslashes($registros->fields["NOMBRE"]);
					?>
	<h1><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></h1>
					<?php
					//	verifico si posee IMAGENES el contenido
					$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY id_archivo");
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
									$nombre_completo_imagensmall = $path_imagenes."productos/".$nombre."_small.".$extension;
									if(!file_exists($nombre_completo_imagensmall)) {
										$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
									}
									//	muestra la imagen
									$imagen = "";
									//$imagen .= "<ul class=\"thumbnails\">\n";
									//$imagen .= "	<li class=\"span6\">\n";
									if($registros2->fields["COMENTARIO"] == null){
										$imagen .= "		<a href=\"".$nombre_completo_imagenoriginal."\" class=\"thumbnail\" rel=\"colorbox\" title=\"".stripslashes($registros->fields["NOMBRE"])."\">";
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
								$registros->fields["DESCRIPCION"] = str_replace($referencia,$imagen,$registros->fields["DESCRIPCION"]);
								++$i;

								$registros2->MoveNext();
							}
							$registros2->close();
						}								
					}				
					?>
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
			<li><a href="#tab02" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
			<li><a href="#tab03" data-toggle="tab">Comentarios</a></li>
		</ul>

		<div class="tab-content">
			<div id="tab01" class="tab-pane active">
				<div class="row-fluid">
					<div class="span4">
						<h5 style="text-align: center;">C&oacute;digo: <?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?></h5>
						<?php
						$nombre_completo_imagenoriginal = $path_imagenes."productos/".$registros->fields['FOTO'];
						if(($registros->fields['FOTO'] != null) and file_exists($nombre_completo_imagenoriginal)) { 
							//	obtengo la extension del archivo
							$res = explode(".", $registros->fields["FOTO"]);
							$nombre = $res[0];
							$extension = $res[count($res) - 1];
							//	obtengo la ubicacion de la imagen redimensionada original
							$nombre_completo_imagensmall = $path_imagenes."productos/".$nombre."_small.".$extension;
							if(!file_exists($nombre_completo_imagensmall)) {
								$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
							}
						} else { 
							$nombre_completo_imagenoriginal = "images/productos/producto.jpg";
							$nombre_completo_imagensmall = "images/productos/producto_small.jpg";
						} 
						?>
						<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="thumbnail foto" rel="colorbox" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE']); ?>">
							<img src="<?php echo($nombre_completo_imagensmall); ?>" border="0" />
						</a>
					</div>
					<div class="span8">
						<table class="table-condensed">
						<tbody>
							<tr>
								<th width="50" style="text-align: left;">Categoria:</th>
								<td><a href="?menu=<?php echo($_GET['menu']."&c=".$registros->fields["CATEGORIA"]); ?>" title=""><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></a></td>
							</tr>
							<tr>
								<th style="text-align: left;">G&eacute;nero:</th>
								<td>
								<?php 
									//echo(stripslashes($registros->fields["GENERO"]));
									$contador = 0;
									$generos = explode(",",$registros->fields["GENERO"]);
									foreach($generos as $key=>$val) {
										if ($contador++ > 0) echo(", ");
										echo("<a href=\"?menu=".$_GET['menu']."&g=".trim($val)."\">".trim($val)."</a>");
									}
								?>
								</td>
							</tr>
							<tr>
								<th style="text-align: left;">Desarrollador:</th>
								<td><?php echo(stripslashes($registros->fields["MARCA"])); ?></td>
							</tr>
							<tr>
								<th style="text-align: left;">Lanzamiento:</th>
								<td><?php if(($registros->fields["FECHA_LANZAMIENTO"] != null) and ($registros->fields["FECHA_LANZAMIENTO"] != "0000-00-00")) { echo(formato_fecha($registros->fields["FECHA_LANZAMIENTO"], 1)); } else { echo("NO ESPECIFICADO"); } ?></td>
							</tr>
						</tbody>
						</table>
						<hr />
						<?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?>
					</div>
				</div>			
			</div>

			<div id="tab02" class="tab-pane">
				<?php
					//	verifico si posee GALERIA el contenido
					$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'galeria') ORDER BY id_archivo");
					if($registros2 and $registros2->RecordCount() > 0) {
						?>
				<div id="myCarousel" class="carousel slide">
					<ol class="carousel-indicators">
						<?php
							for($i=0; $i<$registros2->RecordCount(); $i++) {
								?>
						<li data-target="#myCarousel" data-slide-to="<?php echo($i); ?>"<?php if($i == 0) { echo(" class=\"active\""); } ?>></li>
								<?php
							}
						?>
					</ol>
					<div class="carousel-inner">
						<?php
						$i = 0;
						while(!$registros2->EOF) {
							$nombre_completo_imagenoriginal = $path_imagenes."contenidos/".$registros2->fields['NOMBRE_ARCHIVO'];
							if(($registros2->fields['NOMBRE_ARCHIVO'] != null) and file_exists($nombre_completo_imagenoriginal)) { 
								//	obtengo la extension del archivo
								$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen redimensionada original
								$nombre_completo_imagensmall = $path_imagenes."contenidos/".$nombre."_small.".$extension;
								if(!file_exists($nombre_completo_imagensmall)) {
									$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
								}
							} 
							?>
						<div class="item<?php if($i == 0) { echo(" active"); }?>">
							<img src="<?php echo($nombre_completo_imagenoriginal); ?>" alt="" border="0" />
							<?php
							if(($registros2->fields["COMENTARIO"] != null) and ($registros2->fields["COMENTARIO"] != "")) {
								?>
							<div class="carousel-caption">
								<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>
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
				<a href="javascript:void(0);" class="btn btn-danger pull-right" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" title="Dejar Comentario" style="color: #ffffff;margin-top: 10px;"><i class="icon-comment icon-white"></i> Dejar comentario</a>
				<?php
				if($datosComentarios["CANTIDAD"] > 0) {
					//	obtengo los comentarios del contenido visualizado
					$registrosComentarios = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'productos') and (id_cabeza = '".$registros->fields["ID_PRODUCTO"]."') and (activo = 1) ORDER BY fecha_alta");
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
				<h5 style="text-align: center;">No hay comentarios disponibles en este momento.</h5>
					<?php
				}
				?>
			</div>

		</div>
	</div>

	<footer>
		<p>
			<i class="icon-backward"></i> <a href="?menu=<?php echo($_GET['menu']."&c=".stripslashes($registros->fields["CATEGORIA"])); ?>" title="">Ver m&aacute;s productos</a> / 
			<i class="icon-ok"></i> <strong>Leido</strong>: <?php echo(($registros->fields["CLICK"]+1)); ?> veces / 
			<i class="icon-comment"></i> <a href="#comentarios"><strong>Comentarios</strong>: <?php echo($datosComentarios["CANTIDAD"]); ?></a> / 
			<i class="icon-download-alt"></i> 
			<?php
				if($datosArchivosadjuntos["CANTIDAD"] > 0) {
				?>
			<a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Adjuntos" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" title="Archivos adjuntos"><strong>Archivos Adjuntos</strong>: <?php echo($datosArchivosadjuntos["CANTIDAD"]); ?></a>
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
	<a href="javascript:void(0);" class="btn btn-danger pull-right" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" title="Dejar Comentario" style="color: #ffffff;margin-top: 10px;"><i class="icon-comment icon-white"></i> Dejar comentario</a>
	<h2 style="border-bottom: 1px solid #ddd;">Comentarios</h2>
					<?php
					if($datosComentarios["CANTIDAD"] > 0) {
						//	obtengo los comentarios del contenido visualizado
						$registrosComentarios = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = '".$_GET['menu']."') and (id_cabeza = '".$registros->fields["ID_PRODUCTO"]."') and (activo = 1) ORDER BY fecha_alta");
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
		<p>No hay comentarios disponibles en este momento, se el primero en hacerlo. <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#ModalMensajes" data-tipo="Comentarios" data-contenido="<?php echo($_GET['menu']); ?>" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" title="Dejar Comentario">clic aqu&iacute;</a>.</p>
	</blockquote>
						<?php
					}											

					$registros->MoveNext();
				}
				$registros->close();
			} else {
				$path .= "Error";
				?>
	<div class="alert alert-error" style="margin: 20px auto;">
		<h3>Atenci&oacute;n</h3>
		<p>No hay registros en el menu de contenido: <strong><?php echo(ucfirst($_GET['menu'])); ?></strong></p>
	</div>
				<?php
			}			
		} else {
			//	muestra la herramienta de busqueda con slide
			?>
<h1>Busca tu juego aqu&iacute;</h1>
<form method="get" action="./" id="fbuscar" style="margin-bottom: 5px;">
	<input type="hidden" name="menu" value="productos" />
	<input type="text" value="<?php if(isset($_GET['q'])) { echo($_GET['q']); } ?>" class="span4" name="q" placeholder="c&oacute;digo o nombre" />
	<input type="hidden" name="ca" value="<?php if(isset($_GET['c'])) { echo($_GET['c']); } ?>" />
	<?php
		if(isset($_GET['c']) and isset($_GET['ca']) and ($_GET['c'] != $_GET['ca'])) {
			$_GET['g'] = "";
		}
	?>
	<select name="c" id="categoria" class="span3">
		<option value="" style="display:none" selected="selected">categorias</option>
		<option value="&Uacute;ltimos productos">&Uacute;ltimos productos</option>
		<option value="Productos m&aacute;s le&iacute;das">Productos m&aacute;s le&iacute;das</option>
		<option disabled="disabled"></option>
		<?php
		//	obtengo todas las CATEGORIAS del menu de contenidos
		$registrosCategorias = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) GROUP BY categoria ORDER BY categoria LIMIT 0,20");
		if($registrosCategorias and $registrosCategorias->RecordCount() > 0) {
			while(!$registrosCategorias->EOF) {
				?>
		<option value="<?php echo(stripslashes($registrosCategorias->fields["CATEGORIA"])); ?>" <?php if(isset($_GET['c']) and ($registrosCategorias->fields["CATEGORIA"] == $_GET['c'])) { echo("selected=\"selected\""); } ?>><?php echo(stripslashes($registrosCategorias->fields["CATEGORIA"])); ?></option>
				<?php
				$registrosCategorias->MoveNext();
			}
		}
		?>
	</select>
	<?php
	//	obtengo todos los GENEROS individualmente del menu de contenidos
	$listaGeneros = array();
	$query = "SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) ";
	if(isset($_GET['c']) != ""){
		if($_GET['c'] == "Productos m&aacute;s le&iacute;das") {
			$query .= " and ((click > 0) and (click is not null) and (click <> '')) ";
		} else {
			if($_GET['c'] != "&Uacute;ltimos productos") {
				$query .= " and (categoria like '%".$_GET['c']."%') "; 
			}
		}		
	}
	$query .= "GROUP BY genero ORDER BY genero";

	$registros = $conexion->Query($query);
	while(!$registros->EOF) {
		if(strpos($registros->fields["GENERO"],",") > 0) {
			$arrayGeneros = explode(",",stripslashes($registros->fields["GENERO"]));
			foreach($arrayGeneros as $key=>$val) {
				if(!in_array(trim($val),$listaGeneros)) {
					array_push($listaGeneros,trim($val));
				}
			}
		} else {
			if(!in_array($registros->fields["GENERO"],$listaGeneros)) {
				array_push($listaGeneros,stripslashes($registros->fields["GENERO"]));
			}
		}
		$registros->MoveNext();
	}
	sort($listaGeneros);
	?>
	<select name="g" id="genero" class="span3">
		<option value="" style="display:none" selected="selected">g&eacute;neros</option>
		<?php
			foreach($listaGeneros as $key=>$val) {
			?>
		<option value="<?php echo($val); ?>"  <?php if(isset($_GET['g']) and ($val == $_GET['g'])) { echo("selected=\"selected\""); } ?>><?php echo($val); ?></option>
			<?php
			}
		?>
	</select>
	<div class="input-append">
		<button type="submit" class="btn"><i class="icon-search"></i> buscar</button>
		<button type="button" class="btn btn-danger" id="btEliminarBusqueda" title="Eliminar busquedas"><i class="icon-remove"></i></button>
	</div>
</form>
		
<div class="tabbable" style="margin-bottom: 10px;">
	<ul id="myTab" class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tab1">Resultados</a></li>
	</ul>
	<div class="tab-content">
		<div id="tab1" class="tab-pane active" style="min-height: 300px;">
			<?php
			//	obtengo los productos
			$query = "";
			if(isset($_GET['q']) and ($_GET['q'] != "")) {
				//verifico si tiene palabras
/*				if(strpos(trim($_GET['q'])," ") > 0) {
					$query .= " and ( ((codigo = '".$_GET['q']."') or (nombre = '".$_GET['q']."')) or ((codigo like '".$_GET['q']."%') or (nombre like '".$_GET['q']."%')) or ((codigo like '%".$_GET['q']."%') or (nombre like '%".$_GET['q']."%'))"; 
					//obtengo cada palabra buscada
					$arrayPalabras = array_unique(explode(" ",trim($_GET['q'])));
					if(count($arrayPalabras) > 1){
						foreach($arrayPalabras as $key=>$val) {
							//busca solo aquellas palabras que tienen mas de 2 letras
							if(strlen($val) > 2){
								$query .= " or ((codigo like '%".$val."%') or (nombre like '%".$val."%'))"; 
							}
						}
					}
					$query .= " )";
				} else {
					$query .= " and ((codigo like '%".$_GET['q']."%') or (nombre like '%".$_GET['q']."%'))"; 
				}
*/
				$query .= " and ((codigo like '%".$_GET['q']."%') or (nombre like '%".$_GET['q']."%'))"; 
			}
			if(isset($_GET['c']) and ($_GET['c'] != "")) {
				if($_GET['c'] == "Productos m&aacute;s le&iacute;das") {
					$query .= " and ((click > 0) and (click is not null) and (click <> ''))"; 					
				} else {
					if($_GET['c'] != "&Uacute;ltimos productos") {
						$query .= " and (categoria = '".$_GET['c']."')";
					}
				}
			}
			if(isset($_GET['g']) and ($_GET['g'] != "")) {
				$query .= " and (genero like '%".$_GET['g']."%')"; 
			}
			
			if(isset($_GET['c']) and ($_GET['c'] == "Productos m&aacute;s le&iacute;das")) {
				$registros = $conexion->Query("SELECT ID_PRODUCTO,CODIGO,NOMBRE,CATEGORIA,FOTO FROM productos WHERE (activo = 1) and (visible = 1) ".$query." ORDER BY click DESC");
			} else {
				$registros = $conexion->Query("SELECT ID_PRODUCTO,CODIGO,NOMBRE,CATEGORIA,FOTO FROM productos WHERE (activo = 1) and (visible = 1) ".$query." ORDER BY id_producto DESC");
			}

			if($registros) {
				if(!($registros->RecordCount() > 0)) {
					?>
			<p style="text-align: center;color: #ff0000;"><strong>No se han encontrado coincidencias.</strong></p>
					<?php
				}
				if(!isset($_GET['cxp'])) {
					$CantidadColumnas = 4;
					$_GET['cxp'] = $CantidadColumnas;					
				} else {
					$CantidadColumnas = $_GET['cxp'];
				}
				if(!isset($_GET['rxp'])) {
					//$cantidad_filas = $_GET['cxp'];
					$cantidad_filas = 8;
					$_GET['rxp'] = $cantidad_filas;
				} else {
					$cantidad_filas = $_GET['rxp'];
				}
				
				if (!isset($_GET['pag'])) $_GET['pag'] = 1; // Por defecto, pagina 1
				$maxpags = 20;

				if($cantidad_filas == -1) {
					$cantidad_filas = $registros->RecordCount();
				}
				if(!isset($_GET['pag'])) { $_GET['pag'] = 1; }
				$reg1 = ($_GET['pag']-1) * $cantidad_filas;
				$enlace = "./?menu=".$_GET['menu'];
				//	verifico si se ordeno por algun campo
				if(isset($_GET['c']) and isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
				//	verifico si se busco algun texto
				if(isset($_GET['q'])) { $enlace .= "&q=".$_GET['q']; }
				//	verifico si se busco alguna categoria
				if(isset($_GET['c'])) { $enlace .= "&c=".$_GET['c']; }
				//	verifico si se busco algun genero
				if(isset($_GET['g'])) { $enlace .= "&g=".$_GET['g']; }
				$enlace .= "&pag=";
				$total = $registros->RecordCount();
				//	coloco los contenidos
				for($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
					if($i >= $reg1) {
						if((($i+1)%$CantidadColumnas) == 1) { 
							?>
			<ul class="thumbnails">
							<?php 
						}
						?>
				<li class="span<?php echo((12/$_GET['cxp'])); ?>" style="margin-bottom: 5px;">
					<div class="thumbnail">
						<?php
							$nombre_completo_imagenoriginal = $path_imagenes."productos/".$registros->fields['FOTO'];
							if(($registros->fields['FOTO'] != null) and file_exists($nombre_completo_imagenoriginal)) { 
								//	obtengo la extension del archivo
								$res = explode(".", $registros->fields["FOTO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen redimensionada original
								$nombre_completo_imagensmall = $path_imagenes."productos/".$nombre."_small.".$extension;
								if(!file_exists($nombre_completo_imagensmall)) {
									$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
								}
							} else { 
								$nombre_completo_imagenoriginal = "images/productos/producto.jpg";
								$nombre_completo_imagensmall = "images/productos/producto_small.jpg";
							}
							if($nombre_completo_imagenoriginal == "images/productos/producto.jpg") {
								?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat right top;background-size: 210% 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>"></a>
								<?php
							} else {
								?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat right top;background-size: 210% 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>"></a>
								<?php
							}
							?>
						<div class="caption">
							<p><a href="javascript:void(0);" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" title="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>"><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></a></p>
							<p><strong>C&oacute;digo:</strong> <?php echo(stripslashes($registros->fields["CODIGO"])); ?></p>
							<p><a href="<?php echo("./?menu=productos&q=&c=".stripslashes($registros->fields["CATEGORIA"])."&g="); ?>" class="btn btn-info btn-mini"><i class="icon-plus-sign"></i> <?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></a></p>
						</div>

					</div>
				</li>
							<?php
					}
					if((($i+1)%$CantidadColumnas) == 0) {
						?>
			</ul>
						<?php 
					}
					$registros->MoveNext(); 
				}
				//	completo con filas en blanco
				if((($total - $reg1) < $cantidad_filas) and ($i > 0)) {
					for($j=($total - $reg1); $j<$cantidad_filas; $j++) {
						if((($j+1)%$CantidadColumnas) == 1) { 
							?>
			<ul class="thumbnails">
							<?php 
						}
						?>
				<li class="span<?php echo((12/$_GET['cxp'])); ?>" style="margin-bottom: 5px;">
					<div class="thumbnail">
					</div>
				</li>
					<?php
						if((($j+1)%$CantidadColumnas) == 0) {
							?>
			</ul>
							<?php 
						}
					}
				}
				?>
				<footer style="margin-top: 0;">
					<p style="text-align: center;">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
					<div class="btn-toolbar" style="text-align: center;">
						<?php
							if(($total > 0) and ($cantidad_filas > 0)){
								$total_paginas = ceil($total/$cantidad_filas);
							} else {
								$total_paginas = 0;
							}
							$primero = 1;
							$anterior = $_GET['pag'] - 1;
							$posterior = $_GET['pag'] + 1;
							$ultimo = $total_paginas;
							$minimo = $maxpags ? max(1, $_GET['pag']-ceil($maxpags/2)): 1;
							$maximo = $maxpags ? min($total_paginas, $_GET['pag']+floor($maxpags/2)): $total_paginas;
							?>
						<div class="btn-group">
							<select class="btn" onchange="AbrirURL(this);" style="width: auto;" title="Columnas por p&aacute;gina">
								<?php
								for($i=1; $i<=4; $i++) {
									?>
								<option value="<?php echo($enlace.$_GET['pag']."&cxp=".$i."&rxp=".$i); ?>"<?php if($CantidadColumnas == $i) { echo(" selected=\"selected\""); } ?>><?php echo($i); ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="btn-group">
							<select class="btn" onchange="AbrirURL(this);" style="width: auto;" title="Registros por p&aacute;gina">
								<?php
								//	obtengo los multiplos de la cantidad de registros por pagina
								//for($i=1; $i<(($total+$_GET['cxp'])/$_GET['cxp']); $i++) {
								//	obtengo el limite de 200 registros por pagina
								for($i=1; $i<=ceil(min(200,$total)/$_GET['cxp']); $i++) {
									?>
								<option value="<?php echo($enlace.$_GET['pag']."&cxp=".$CantidadColumnas."&rxp=".($i*$_GET['cxp'])); ?>"<?php if($cantidad_filas == ($i*$_GET['cxp'])) { echo(" selected=\"selected\""); } ?>><?php echo(($i*$_GET['cxp'])); ?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="btn-group">
							<?php
							if($anterior > 0) {
								?>
							<a href="<?php echo($enlace.$primero."&cxp=".$_GET['cxp']."&rxp=".$_GET['rxp']); ?>" class="btn" title="Ir a la primera p&aacute;gina"><i class="icon-fast-backward"></i> Primera</a>
								<?php
							} else {
								?>
							<a href="javascript:void(0);" class="btn disabled"><i class="icon-fast-backward"></i> Primera</a>
								<?php
							}
							if ($_GET['pag'] > 1) {
								?>
							<a href="<?php echo($enlace.$anterior."&cxp=".$_GET['cxp']."&rxp=".$_GET['rxp']); ?>" class="btn" title="Ir a la anterior p&aacute;gina"><i class="icon-step-backward"></i> Anterior</a>
								<?php
							} else {								
								?>
							<a href="javascript:void(0);" class="btn disabled" title="Ir a la anterior p&aacute;gina"><i class="icon-step-backward"></i> Anterior</a>
								<?php
							}
							$_GET['pag'] = sprintf("%02s",$_GET['pag']); 
							$total_paginas = sprintf("%02s",$total_paginas); 
							?>
							<select class="btn" onchange="AbrirURL(this);" style="width: auto;" title="Ir a la p&aacute;gina numero">
								<?php
								for($i=1; $i<$total_paginas+1; $i++) {
									?>
								<option value="<?php echo($enlace.$i."&cxp=".$_GET['cxp']."&rxp=".$_GET['rxp']); ?>"<?php if($_GET['pag'] == $i) { echo(" selected=\"selected\""); } ?>><?php echo($i); ?></option>
									<?php
								}
								?>
							</select>
							<?php
							if ($_GET['pag'] < $total_paginas) {
								?>
							<a href="<?php echo($enlace.$posterior."&cxp=".$_GET['cxp']."&rxp=".$_GET['rxp']); ?>" class="btn" title="Ir a la siguiente p&aacute;gina">Siguiente <i class="icon-step-forward"></i></a>
								<?php
							} else {
								?>
							<a href="javascript:void(0);" class="btn disabled" title="Ir a la siguiente p&aacute;gina">Siguiente <i class="icon-step-forward"></i></a>
								<?php
							}
							if($posterior <= $total_paginas) {
								?>
							<a href="<?php echo($enlace.$ultimo."&cxp=".$_GET['cxp']."&rxp=".$_GET['rxp']); ?>" class="btn" title="Ir a la &Uacute;ltima p&aacute;gina">&Uacute;ltima <i class="icon-fast-forward"></i></a>
								<?php
							} else {
								?>
							<a href="javascript:void(0);" class="btn disabled" title="Ir a la &Uacute;ltima p&aacute;gina">&Uacute;ltima <i class="icon-fast-forward"></i></a>
								<?php
							}
							?>
						</div>
					</div>
				</footer>

		</div>
	</div>
</div>
				<?php
			}
		}

		//	obtengo todas las ENTRADAS de la categorias actual
/*		$registros = $conexion->Query("SELECT CATEGORIA FROM productos WHERE (activo = 1) and (id_producto = '".$_GET['id']."') GROUP BY categoria ORDER BY categoria");
		while($registros and !$registros->EOF) {
			$categoriasActual = $registros->fields["CATEGORIA"];
			$categorias .= "<div class=\"subSeccion\">Categor&iacute;a: ".$registros->fields["CATEGORIA"]."</div>\n";
			$categorias .= "<div class=\"subSeccion_contenido\">\n";
			$categorias .= "	<select onchange=\"AbrirURL(this);\" style=\"width: 96%;\">\n";
			$categorias .= "		<option value=\"\"></option>\n";
			//	obtengo los contenidos
			$resultados = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (categoria = '".$registros->fields["CATEGORIA"]."') ORDER BY id_producto DESC");
			$registros2 = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (categoria = '".$registros->fields["CATEGORIA"]."') ORDER BY id_producto DESC LIMIT 0,5");
			while($registros2 and !$registros2->EOF) {
				if($_GET['id'] == $registros2->fields["ID_PRODUCTO"]) {
					$categorias .= "		<option value=\"./?menu=".$_GET['menu']."&id=".$registros2->fields["ID_PRODUCTO"]."\" selected=\"selected\">".stripslashes($registros2->fields["NOMBRE"])."</option>";
				} else {
					$categorias .= "		<option value=\"./?menu=".$_GET['menu']."&id=".$registros2->fields["ID_PRODUCTO"]."\">".stripslashes($registros2->fields["NOMBRE"])."</option>\n";
				}
				$registros2->MoveNext();
			}
			$registros2->close();
			if($resultados["CANTIDAD"] >= 5) {
				$categorias .= "		<option value=\"./?menu=".$_GET['menu']."&accion=listar&c=".$registros->fields["CATEGORIA"]."\">M&aacute;s ".$_GET['menu']."</option>\n";
			}
			$categorias .= "	</select>\n";
			$categorias .= "</div>\n";
			$registros->MoveNext();
		}
		$registros->close();*/
	}

	//	obtengo todas las CATEGORIAS del menu de contenidos
	$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) GROUP BY categoria ORDER BY categoria LIMIT 0,10");
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Categor&iacute;as <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			if(isset($_GET['c']) and ($registros->fields["CATEGORIA"] == $_GET['c'])) {
				$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&c=".stripslashes($registros->fields["CATEGORIA"])."\" selected=\"selected\">".stripslashes($registros->fields["CATEGORIA"])."</a></li>\n";
			} else {
				$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&c=".stripslashes($registros->fields["CATEGORIA"])."\">".stripslashes($registros->fields["CATEGORIA"])."</a></li>\n";
			}
			$registros->MoveNext();
		}
		$registros->close();
		if($registros->RecordCount() >= 10) {
			$categorias .= "			<li class=\"divider\"></li>\n";
			$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&accion=listar&c=\">M&aacute;s categor&iacute;as</a></li>\n";
		}
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	//	obtengo todos los GENEROS individualmente del menu de contenidos
	$listaGeneros = array();
	$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
	while(!$registros->EOF) {
		if(strpos($registros->fields["GENERO"],",") > 0) {
			$arrayGeneros = explode(",",stripslashes($registros->fields["GENERO"]));
			foreach($arrayGeneros as $key=>$val) {
				if(!in_array(trim($val),$listaGeneros)) {
					array_push($listaGeneros,trim($val));
				}
			}
		} else {
			if(!in_array($registros->fields["GENERO"],$listaGeneros)) {
				array_push($listaGeneros,stripslashes($registros->fields["GENERO"]));
			}
		}
		$registros->MoveNext();
	}
	sort($listaGeneros);
	$categorias .= "	<div class=\"btn-group\">\n";
	$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">G&eacute;neros <span class=\"caret\"></span></button>\n";
	$categorias .= "		<ul class=\"dropdown-menu\">\n";
	foreach($listaGeneros as $key=>$val) {
		$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&g=".$val."\">".$val."</a></li>\n";
		if($key >= 10) {
			break;
		}
	}
	if(isset($key) and ($key >= 10)) {
		$categorias .= "			<li class=\"divider\"></li>\n";
		$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&accion=listar&g=\">M&aacute;s g&eacute;neros</a></li>\n";
	}
	$categorias .= "		</ul>\n";
	$categorias .= "	</div>\n";

	//	obtengo las ULTIMAS ENTRADAS
	$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) ORDER BY id_producto DESC LIMIT 0,10");
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">&Uacute;ltimos productos <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros->fields["ID_PRODUCTO"]."\">".stripslashes($registros->fields["NOMBRE"])."</a></li>\n";
			$registros->MoveNext();
		}
		$registros->close();
		$categorias .= "			<li class=\"divider\"></li>\n";
		$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&c=&Uacute;ltimos productos\">M&aacute;s ".$_GET['menu']."</a></li>\n";
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	//	obtengo las ENTRADAS MAS LEIDAS
	$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) and (click > 0) ORDER BY click DESC LIMIT 0,10");
	if($registros and $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Productos m&aacute;s le&iacute;das <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			$categorias .= "		<li><a href=\"./?menu=".$_GET['menu']."&id=".$registros->fields["ID_PRODUCTO"]."\">".stripslashes($registros->fields["NOMBRE"])."</a></li>\n";
			$registros->MoveNext();
		}
		$registros->close();
		$categorias .= "			<li class=\"divider\"></li>\n";
		$categorias .= "			<li><a href=\"./?menu=".$_GET['menu']."&c=Productos m&aacute;s le&iacute;das\">M&aacute;s ".$_GET['menu']."</a></li>\n";
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	$categorias .= "</div>";
?>
<p>&nbsp;</p>
<!-- fin contenido -->