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
	$categorias .= "		<a class=\"btn\" href=\"https://www.facebook.com/sharer/sharer.php?u=".$url."\" title=\"Compartir en Facebook\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/facebook.png\" width=\"16\" /></a>";
	$categorias .= "		<a class=\"btn\" href=\"https://twitter.com/intent/tweet?text=".$url."\" title=\"Compartir en Twitter\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/twitter.png\" width=\"16\" /></a>";
	$categorias .= "		<a class=\"btn\" href=\"whatsapp://send?text=".$url."\" title=\"Compartir en WhatsApp\" target=\"_blank\"><img src=\"".XPANEL_SITIO_URL_HOST."images/whatsapp.png\" width=\"16\" /></a>";
	$categorias .= "	</div>\n";
	
	$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
	$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 

	?>
<h1>Busca tu juego aqu&iacute;</h1>
<form method="get" action="./?menu=productos" class="form-inline" style="margin-bottom: 5px;">
	<input type="hidden" name="menu" value="productos" />
	<input type="hidden" name="ca" value="<?php if(isset($_GET['c'])) { echo($_GET['c']); } ?>" />

	<div class="row-fluid">
		<div class="span6">
			<div class="input-append" style="width: 99%;">
				<input type="text" name="q" value="<?php if(isset($_GET['q'])) { echo($_GET['q']); } ?>" placeholder="c&oacute;digo o nombre" class="span10" />
				<button type="submit" class="btn"><i class="icon-search"></i> buscar</button>
				<button type="button" class="btn btn-danger" id="btEliminarBusqueda" title="Eliminar busquedas"><i class="icon-remove icon-white"></i></button>
			</div>
			<span class="help-block">Ej. escriba <strong>call</strong> para buscar <strong>Call of Duty</strong></span>
		</div>
		<div class="span3">
			<select name="c" id="categoria">
				<option value="" style="display:none">categorías</option>
				<option value="&Uacute;ltimos productos"<?php if(isset($_GET['c']) && ($_GET['c'] == "&Uacute;ltimos productos")) { echo(" selected=\"selected\""); } ?>>&Uacute;ltimos productos</option>
				<option value="Productos m&aacute;s le&iacute;das"<?php if(isset($_GET['c']) && ($_GET['c'] == "Productos m&aacute;s le&iacute;das")) { echo(" selected=\"selected\""); } ?>>Productos m&aacute;s le&iacute;das</option>
				<option disabled="disabled"></option>
				<?php
				//	obtengo todas las CATEGORIAS del menu de contenidos
				$registrosCategorias = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria ORDER BY categoria LIMIT 0,20");
				if($registrosCategorias && $registrosCategorias->RecordCount() > 0) {
					while(!$registrosCategorias->EOF) {
						?>
				<option value="<?php echo(stripslashes($registrosCategorias->fields["CATEGORIA"])); ?>"<?php if(isset($_GET['c']) && ($registrosCategorias->fields["CATEGORIA"] == $_GET['c'])) { echo(" selected=\"selected\""); } ?>><?php echo(stripslashes($registrosCategorias->fields["CATEGORIA"])); ?></option>
						<?php
						$registrosCategorias->MoveNext();
					}
				}
				?>
			</select>
			<?php
			//	obtengo todos los GENEROS individualmente del menu de contenidos
			$listaGeneros = array();
			$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY genero ORDER BY genero");
			if($registros && $registros->RecordCount() > 0) {
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
			}
			sort($listaGeneros);
			?>
		</div>
		<div class="span3">
			<select name="g" id="genero">
				<option value="" style="display:none" selected="selected">g&eacute;neros</option>
				<?php
					foreach($listaGeneros as $key=>$val) {
					?>
				<option value="<?php echo($val); ?>"><?php echo($val); ?></option>
					<?php
					}
				?>
			</select>
		</div>
	</div>
</form>

<div class="tabbable">
	<ul id="myTab" class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tab1">&Uacute;ltimos productos</a></li>
		<li><a data-toggle="tab" href="#tab2">Novedades</a></li>
		<li><a data-toggle="tab" href="#tab3">Productos m&aacute;s le&iacute;das</a></li>
		<li><a href="./?menu=productos"><i class="icon-chevron-right"></i> M&aacute;s productos</a></li>
	</ul>
	<div class="tab-content">
		<div id="tab1" class="tab-pane active">
			<?php
				//	obtengo las ULTIMAS ENTRADAS x Categoria
				//$registros = $conexion->Query("SELECT P.ID_PRODUCTO,P.CODIGO,P.NOMBRE,P.CATEGORIA,FOTO FROM productos as P INNER JOIN (SELECT categoria, MAX( id_producto ) AS maximo FROM productos WHERE (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria) as U ON (U.categoria = P.categoria) and (U.maximo = P.id_producto) WHERE (P.activo = 1) and (P.visible = 1) and (P.id_sucursal = '".XPANEL_SUCURSAL."') ORDER BY P.id_producto desc");
				//	obtengo las ULTIMAS 8 ENTRADAS
				$registros = $conexion->Query("SELECT ID_PRODUCTO,CODIGO,NOMBRE,CATEGORIA,FOTO FROM productos WHERE (activo = 1) and (visible = 1) and (id_sucursal = '".XPANEL_SUCURSAL."') ORDER BY id_producto desc LIMIT 0,9");
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
						if(($registros->RecordCount() % 2) == 0){
							$cantidad_filas = $registros->RecordCount();
						} else {
							$cantidad_filas = $registros->RecordCount() + 1;
						}
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
					$enlace = "./?menu=productos";
					//	verifico si se ordeno por algun campo
					if(isset($_GET['c']) && isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
					//	verifico si se busco algun texto
					if(isset($_GET['q'])) { $enlace .= "&q=".$_GET['q']; }
					//	verifico si se busco alguna categoria
					if(isset($_GET['c'])) { $enlace .= "&c=".$_GET['c']; } else { $enlace .= "&c=&Uacute;ltimos productos"; }
					//	verifico si se busco algun genero
					if(isset($_GET['g'])) { $enlace .= "&g=".$_GET['g']; }
					$enlace .= "&pag=";
					$total = $registros->RecordCount();
					//	coloco los contenidos
					for($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
						if($i >= $reg1) {
							if((($i+1) % $CantidadColumnas) == 1) { 
								?>
				<ul class="thumbnails">
								<?php 
							}
							?>
					<li class="span<?php echo((12/$_GET['cxp'])); ?>" style="margin-bottom: 5px;">
						<div class="thumbnail">
							<?php
								$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes."productos/".$registros->fields['FOTO'];
								if(($registros->fields['FOTO'] != null) && verificar_url($nombre_completo_imagenoriginal)) { 
									//	obtengo la extension del archivo
									$res = explode(".", $registros->fields["FOTO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen redimensionada original
									$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes."productos/".$nombre."_small.".$extension;
									if(!verificar_url($nombre_completo_imagensmall)) {
										$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
									}
								} else { 
									$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST."images/productos/producto.jpg";
									$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST."images/productos/producto_small.jpg";
								}
								if($nombre_completo_imagenoriginal == XPANEL_SITIO_URL_HOST."images/productos/producto.jpg") {
									?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat left top;background-size: 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>"></a>
									<?php
								} else {
									?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat left top;background-size: 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>"></a>
									<?php
								}
								?>
							<div class="caption">
								<p><a href="javascript:void(0);" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-origen="#tab01" title="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>"><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></a></p>
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
					if((($total - $reg1) < $cantidad_filas) && ($i > 0)) {
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
					<footer style="margin-top: 0;display: none;">
						<p style="text-align: center;">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
						<div class="btn-toolbar" style="text-align: center;">
							<?php
								if(($total > 0) && ($cantidad_filas > 0)){
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
					<?php
				}
			?>
		</div>
		<div id="tab2" class="tab-pane">
			<?php
				//	obtengo las ULTIMAS ENTRADAS de las NOVEDADES
				$registros = $conexion->Query("SELECT * FROM contenidos WHERE ((tipo_contenido = 'novedades') or (tipo_contenido = 'news')) and (activo = 1) and (id_sucursal = '".XPANEL_SUCURSAL."') ORDER BY fecha_alta DESC LIMIT 0,10");
				if($registros) {
					if(!($registros->RecordCount() > 0)) {
						?>
				<p style="text-align: center;color: #ff0000;"><strong>No se han encontrado registros.</strong></p>
						<?php
					} else {
						?>
			<div id="myCarouselPortada" class="carousel slide">
				<ol class="carousel-indicators">
					<?php
						for($i=0; $i<$registros->RecordCount(); $i++) {
							?>
					<li data-target="#myCarouselPortada" data-slide-to="<?php echo($i); ?>"<?php if($i == 0) { echo(" class=\"active\""); } ?>></li>
							<?php
						}
					?>
				</ol>
				<div class="carousel-inner">
					<?php
						$i = 0;
						while(!$registros->EOF) {
							//	verifico si posee COMENTARIOS
							$datosComentarios = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM mensajes WHERE (tipo_mensaje = 'comentarios') and ((tipo_cabeza = 'novedades') or (tipo_cabeza = 'news')) and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1)");
							//	verifico si posee ARCHIVOS ADJUNTOS
							$datosArchivosadjuntos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");

							?>
					<div class="item<?php if($i == 0) { echo(" active"); }?>">
							<?php
							//	elimino todas las marcas de IMAGENES en el CONTENIDO
							$datos = $conexion->GetRow("SELECT count(*) as CANTIDAD,ID_ARCHIVO FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
							if($datos["CANTIDAD"] > 0) {
								for($i=1; $i<=$datos["CANTIDAD"]; $i++) {
									//	obtengo los datos de la primera imagen subida
									if($i == 1) {									
										//	verifico si el archivo existe
										$datosArchivo = $conexion->GetRow("SELECT * FROM archivos WHERE (id_archivo = '".$datos["ID_ARCHIVO"]."') and (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");

										$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes."contenidos/".$datosArchivo["NOMBRE_ARCHIVO"];
										if(verificar_url($nombre_completo_imagenoriginal)) {
											//	obtengo el nombre y extension del archivo small
											$res = explode(".", $datosArchivo["NOMBRE_ARCHIVO"]);
											$nombre = $res[0];
											$extension = $res[count($res)-1];
											//	obtengo la ubicacion de la imagen small
											$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes."contenidos/".$nombre."_small.".$extension;
											if(!verificar_url($nombre_completo_imagensmall)) {
												$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
											}
										} else {
											$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST."images/productos/producto.jpg";
											$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST."images/productos/producto_small.jpg";
										}
									}
									$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
									$registros->fields["CONTENIDO"] = str_replace($referencia,"",$registros->fields["CONTENIDO"]);										
								}
							} else {
								$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST."images/productos/producto.jpg";
								$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST."images/productos/producto_small.jpg";								
							}
							?>
						<a href="./?menu=novedades&id=<?php echo($registros->fields["ID_CONTENIDO"]); ?>" title="<?php echo(stripslashes($registros->fields['TITULO'])); ?>">
							<img src="<?php echo($nombre_completo_imagensmall); ?>" border="0" />
						</a>
						<div class="carousel-caption">
							<h3 style="margin-bottom: 5px;"><a href="./?menu=novedades&id=<?php echo($registros->fields["ID_CONTENIDO"]); ?>" style="font-size: 16px;"><?php echo(stripslashes($registros->fields["TITULO"])); ?></a></h3>
							<p style="border-top: 1px solid #dddddd;line-height: 14px;margin-bottom: 10px;padding-top: 5px;">
								<i class="icon-calendar"></i> <strong>Publicado el</strong>: <?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?> / 
								<i class="icon-ok"></i> <strong>Leido</strong>: <?php echo(($registros->fields["CLICK"]+1)); ?> veces / 
								<i class="icon-comment"></i> <a href="./?menu=novedades&id=<?php echo($registros->fields["ID_CONTENIDO"]); ?>#comentarios"><strong>Comentarios</strong>: <?php echo($datosComentarios["CANTIDAD"]); ?></a>
							</p>
							<?php
								$posicion_inicial = strpos(stripslashes($registros->fields["CONTENIDO"]),"<p");
								$posicion_final = strpos(stripslashes($registros->fields["CONTENIDO"]),"</p>");
								$parrafo = trim(substr(stripslashes($registros->fields["CONTENIDO"]),$posicion_inicial,$posicion_final));
							?>
							<?php echo($parrafo); ?>
							<a href="./?menu=novedades&id=<?php echo($registros->fields["ID_CONTENIDO"]); ?>" class="btn btn-mini pull-right"><i class="icon-chevron-right"></i> leer m&aacute;s</a>
						</div>
					</div>
							<?php
							$registros->MoveNext();
							$i++;
						}
						$registros->close();
					?>
				</div>
				<a class="left carousel-control" href="#myCarouselPortada" data-slide="prev">&lsaquo;</a>
				<a class="right carousel-control" href="#myCarouselPortada" data-slide="next">&rsaquo;</a>
			</div>
						<?php
						$registros->close();
					}
				}
			?>
		</div>
		<div id="tab3" class="tab-pane">
			<?php
				//	obtengo las ENTRADAS MAS LEIDAS
				$registros = $conexion->Query("SELECT * FROM productos WHERE (activo = 1) and (visible = 1) and ((click > 0) and (click is not null) and (click <> '')) and (id_sucursal = '".XPANEL_SUCURSAL."') ORDER BY click DESC");
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
						$cantidad_filas = $_GET['cxp'];
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
					$enlace = "./?menu=productos";
					//	verifico si se ordeno por algun campo
					if(isset($_GET['c']) && isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
					//	verifico si se busco algun texto
					if(isset($_GET['q'])) { $enlace .= "&q=".$_GET['q']; }
					//	verifico si se busco alguna categoria
					if(isset($_GET['c'])) { $enlace .= "&c=".$_GET['c']; } else { $enlace .= "&c=Productos m&aacute;s le&iacute;das"; }
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
								$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST.$path_imagenes."productos/".$registros->fields['FOTO'];
								if(($registros->fields['FOTO'] != null) && verificar_url($nombre_completo_imagenoriginal)) { 
									//	obtengo la extension del archivo
									$res = explode(".", $registros->fields["FOTO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen redimensionada original
									$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST.$path_imagenes."productos/".$nombre."_small.".$extension;
									if(!verificar_url($nombre_completo_imagensmall)) {
										$nombre_completo_imagensmall = $nombre_completo_imagenoriginal;
									}
								} else { 
									$nombre_completo_imagenoriginal = XPANEL_SITIO_URL_HOST."images/productos/producto.jpg";
									$nombre_completo_imagensmall = XPANEL_SITIO_URL_HOST."images/productos/producto_small.jpg";
								}
								if($nombre_completo_imagenoriginal == XPANEL_SITIO_URL_HOST."images/productos/producto.jpg") {
								?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat left top;background-size: 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>">
						</a>
								<?php
								} else {
								?>
						<a href="javascript:void(0);" style="background: url('<?php echo($nombre_completo_imagenoriginal); ?>') no-repeat left top;background-size: 100%;" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-codigo="<?php echo(sprintf("%06s",$registros->fields["CODIGO"])); ?>" class="thumbnail" title="<?php echo(stripslashes($registros->fields['NOMBRE'])); ?>">
						</a>
								<?php
								}
								?>
							<div class="caption">
								<p><a href="javascript:void(0);" data-toggle="modal" data-target="#ModalMensajes" data-tipo="Producto" data-id="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-origen="#tab03" title="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>"><?php echo(stripslashes($registros->fields["NOMBRE"])); ?></a></p>
								<p><strong>C&oacute;digo:</strong> <?php echo(stripslashes($registros->fields["CODIGO"])); ?></p>
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
					if((($total - $reg1) < $cantidad_filas) && ($i > 0)) {
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
								if(($total > 0) && ($cantidad_filas > 0)){
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
					<?php
				}
			?>
		</div>
	</div>
</div>
	<?php

	$categorias .= "<div class=\"btn-toolbar\" style=\"margin: 0;\">\n";

	//	obtengo todas las CATEGORIAS del menu de contenidos
	$registros = $conexion->Query("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) and ((categoria is not null) and (categoria <> '')) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria ORDER BY categoria LIMIT 0,10");
	if($registros && $registros->RecordCount() > 0) {
		$categorias .= "	<div class=\"btn-group\">\n";
		$categorias .= "		<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\">Categor&iacute;as <span class=\"caret\"></span></button>\n";
		$categorias .= "		<ul class=\"dropdown-menu\">\n";
		while(!$registros->EOF) {
			if(isset($_GET['c']) && ($registros->fields["CATEGORIA"] == $_GET['c'])) {
				$categorias .= "		<li><a href=\"./?menu=productos&c=".stripslashes($registros->fields["CATEGORIA"])."\" selected=\"selected\">".stripslashes($registros->fields["CATEGORIA"])."</a></li>\n";
			} else {
				$categorias .= "		<li><a href=\"./?menu=productos&c=".stripslashes($registros->fields["CATEGORIA"])."\">".stripslashes($registros->fields["CATEGORIA"])."</a></li>\n";
			}
			$registros->MoveNext();
		}
		$registros->close();
		if($registros->RecordCount() >= 10) {
			$categorias .= "			<li class=\"divider\"></li>\n";
			$categorias .= "			<li><a href=\"./?menu=productos&accion=listar&c=\">M&aacute;s categor&iacute;as</a></li>\n";
		}
		$categorias .= "		</ul>\n";
		$categorias .= "	</div>\n";
	}

	//	obtengo todos los GENEROS individualmente del menu de contenidos
	$listaGeneros = array();
	$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (visible = 1) and ((genero is not null) and (genero <> '')) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY genero ORDER BY genero");
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
		$categorias .= "			<li><a href=\"./?menu=productos&g=".$val."\">".$val."</a></li>\n";
		if($key >= 10) {
			break;
		}
	}
	if(isset($key) && ($key >= 10)) {
		$categorias .= "			<li class=\"divider\"></li>\n";
		$categorias .= "			<li><a href=\"./?menu=productos&accion=listar&g=\">M&aacute;s g&eacute;neros</a></li>\n";
	}
	$categorias .= "		</ul>\n";
	$categorias .= "	</div>\n";

	$categorias .= "</div>";
?>