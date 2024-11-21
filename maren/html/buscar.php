<?php
	$contenido = "";
	$categorias = "";
	$categoriasActual = "";

	//	obtener datos segun los parametros
	$path = ucfirst($_GET['menu']).": &quot;".$_GET['q']."&quot;";
	$url = urlencode($sitio_url."?menu=".$_GET['menu']."&q=".$_GET['q']);

	//	armo los enlaces para compartir
	$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
	$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 
	?>
<h1><?php echo($path); ?></h1>
<header>
	<?php echo($Compartir_ContenidoHorizontal); ?>
</header>
<?php
	//	verifico si hay resuldados en LA EMPRESA
	$registros = $conexion->Query("SELECT * FROM contenidos WHERE ((titulo LIKE '%".$_GET['q']."%') or (contenido LIKE '%".$_GET['q']."%')) and (tipo_contenido = 'laempresa') ORDER BY fecha_alta DESC");
	if($registros) {
		if($registros->RecordCount() > 0) {
			$contenido .= "<h2>La Empresa [".$registros->RecordCount()."]</h2>";
			while(!$registros->EOF) {
				//	elimino todas las marcas de IMAGENES
				$datos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
				if($datos["CANTIDAD"] > 0) {
					for($i=1; $i<=$datos["CANTIDAD"]; $i++) {
						$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
						$registros->fields["CONTENIDO"] = str_replace($referencia,"",$registros->fields["CONTENIDO"]);										
					}
				}
				//	Busco las apariciones de la frase; si tiene php5 usar STRIPOS()
				if(version_compare(phpversion(),"5") >= 0) {
					$posicion_inicial = stripos(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$_GET['q']);
				} else {
					$posicion_inicial = strpos(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$_GET['q']);
				}
				$posicion_inicial -= 50;
				if($posicion_inicial < 0) { $posicion_inicial = 0; }
				$frase_buscar = "...".substr(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$posicion_inicial,200)."...";
				//	destaco el texto en la frase a mostrar
				$frase_buscar = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$frase_buscar);
				//	destaco el texto en el titulo a mostrar
				$registros->fields["TITULO"] = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",stripslashes($registros->fields["TITULO"]));
				$registros->fields["TITULO"] = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$registros->fields["TITULO"]);
				$registros->fields["TITULO"] = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$registros->fields["TITULO"]);
				$registros->fields["TITULO"] = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$registros->fields["TITULO"]);

				$registros3 = $conexion->GetRow("SELECT count(*) as Cantidad  FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'historia') and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1)");

				$contenido .= "<blockquote>\n";
				$contenido .= "	<h3><a href='./?menu=laempresa&id=".$registros->fields["ID_CONTENIDO"]."'>".$registros->fields["TITULO"]."</a></h3>\n";
				$contenido .= "	<p style='font-size: 12px;line-height: 18px;margin-bottom: 10px;'>".$frase_buscar."</p>\n";
				$contenido .= "	<small style='border-top: 1px solid #ddd;'>Publicado el ".formato_fecha($registros->fields["FECHA_ALTA"],3)." - En <a href='./?menu=laempresa&id=".$registros->fields["ID_CONTENIDO"]."'>Historia</a> - Leido ".$registros->fields["CLICK"]." veces - Comentado ".$registros3["Cantidad"]." veces</small>\n";
				$contenido .= "</blockquote>\n";
	
				$registros->MoveNext();
			}
			$registros->close();
		}
	}

	//	verifico si hay resuldados en NOVEDADES
	$registros = $conexion->Query("SELECT * FROM contenidos WHERE ((titulo LIKE '%".$_GET['q']."%') or (contenido LIKE '%".$_GET['q']."%')) and (tipo_contenido = 'novedades') ORDER BY fecha_alta DESC");
	if($registros) {
		if($registros->RecordCount() > 0) {
			$contenido .= "<h2>Novedades [".$registros->RecordCount()."]</h2>";
			while(!$registros->EOF) {
				//	elimino todas las marcas de IMAGENES
				$datos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
				if($datos["CANTIDAD"] > 0) {
					for($i=1; $i<=$datos["CANTIDAD"]; $i++) {
						$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
						$registros->fields["CONTENIDO"] = str_replace($referencia,"",$registros->fields["CONTENIDO"]);										
					}
				}
				//	Busco las apariciones de la frase; si tiene php5 usar STRIPOS()
				if(version_compare(phpversion(),"5") >= 0) {
					$posicion_inicial = stripos(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$_GET['q']);
				} else {
					$posicion_inicial = strpos(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$_GET['q']);
				}
				$posicion_inicial -= 50;
				if($posicion_inicial < 0) { $posicion_inicial = 0; }
				$frase_buscar = "...".substr(html_entity_decode(strip_tags(stripslashes($registros->fields["CONTENIDO"]))),$posicion_inicial,200)."...";
				//	destaco el texto en la frase a mostrar
				$frase_buscar = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$frase_buscar);
				//	destaco el texto en el titulo a mostrar
				$registros->fields["TITULO"] = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",stripslashes($registros->fields["TITULO"]));
				$registros->fields["TITULO"] = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$registros->fields["TITULO"]);
				$registros->fields["TITULO"] = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$registros->fields["TITULO"]);
				$registros->fields["TITULO"] = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$registros->fields["TITULO"]);

				$registros3 = $conexion->GetRow("SELECT count(*) as Cantidad  FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'editorial') and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1)");

				$contenido .= "<blockquote>\n";
				$contenido .= "	<h3><a href='./?menu=novedades&id=".$registros->fields["ID_CONTENIDO"]."'>".$registros->fields["TITULO"]."</a></h3>\n";
				$contenido .= "	<p style='font-size: 12px;line-height: 18px;margin-bottom: 10px;'>".$frase_buscar."</p>\n";
				$contenido .= "	<small style='border-top: 1px solid #ddd;'>Publicado el ".formato_fecha($registros->fields["FECHA_ALTA"],3)." - En <a href='./?menu=novedades&id=".$registros->fields["ID_CONTENIDO"]."'>Editorial</a> - Leido ".$registros->fields["CLICK"]." veces - Comentado ".$registros3["Cantidad"]." veces</small>\n";
				$contenido .= "</blockquote>\n";

				$registros->MoveNext();
			}
			$registros->close();
		}
	}

	//	verifico si hay resuldados en FOTOGALERÍA
	$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (descripcion LIKE '%".$_GET['q']."%') or (categoria LIKE '%".$_GET['q']."%') ORDER BY id_foto DESC");
	if($registros) {
		if($registros->RecordCount() > 0) {
			$contenido .= "<h2>Fotogalería [".$registros->RecordCount()."]</h2>";
			while(!$registros->EOF) {
				//	Busco las apariciones de la frase; si tiene php5 usar STRIPOS()
				if(version_compare(phpversion(),"5") >= 0) {
					$posicion_inicial = stripos(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$_GET['q']);
				} else {
					$posicion_inicial = strpos(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$_GET['q']);
				}
				$posicion_inicial -= 50;
				if($posicion_inicial < 0) { $posicion_inicial = 0; }
				$frase_buscar = "...".substr(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$posicion_inicial,200)."...";
				//	destaco el texto en la frase a mostrar
				$frase_buscar = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$frase_buscar);

				$contenido .= "<blockquote>\n";
				$contenido .= "	<h4><a href='./?menu=fotogaleria&cat=".$registros->fields["CATEGORIA"]."'>".$registros->fields["CATEGORIA"]."</a></h4>\n";
				$contenido .= "	<p style='font-size: 12px;line-height: 18px;margin-bottom: 10px;'><a href='./?menu=fotogaleria&cat=".$registros->fields["CATEGORIA"]."'>".$frase_buscar."</a></p>\n";
				$contenido .= "	<small style='border-top: 1px solid #ddd;'>Publicado el ".formato_fecha($registros->fields["FECHA_ALTA"],3)." - En <a href='./?menu=fotogaleria&id=".$registros->fields["ID_FOTO"]."'>Fotogaleria</a> - Leido ".$registros->fields["CLICK"]." veces</small>\n";
				$contenido .= "</blockquote>\n";

				//$posicion_inicial = strpos(strip_tags(stripslashes($registros->fields["DESCRIPCION"])),$_GET['q']);
				//$frase = substr(strip_tags(stripslashes($registros->fields["DESCRIPCION"])),$posicion_inicial-50,100);

				//$contenido .= "<li><a href=\"./?menu=fotogaleria&cat=".$registros->fields["CATEGORIA"]."\">...".str_replace($_GET['q'], "<strong>".$_GET['q']."</strong>", $frase)."...</a></li>";

				$registros->MoveNext();
			}
			$registros->close();
		}
	}

	//	verifico si hay resuldados en PRODUCTOS
	$registros = $conexion->Query("SELECT * FROM productos WHERE (nombre LIKE '%".$_GET['q']."%') or (genero LIKE '%".$_GET['q']."%') or (marca LIKE '%".$_GET['q']."%') or (descripcion LIKE '%".$_GET['q']."%') ORDER BY fecha_lanzamiento DESC,id_producto DESC");
	if($registros) {
		if($registros->RecordCount() > 0) {
			$contenido .= "<h2>Productos [".$registros->RecordCount()."]</h2>";
			while(!$registros->EOF) {
				//	obtengo la imagen del PRODUCTO
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
				//	elimino todas las marcas de IMAGENES
				$datos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
				if($datos["CANTIDAD"] > 0) {
					for($i=1; $i<=$datos["CANTIDAD"]; $i++) {
						$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
						$registros->fields["DESCRIPCION"] = str_replace($referencia,"",$registros->fields["DESCRIPCION"]);										
					}
				}
				//	Busco las apariciones de la frase; si tiene php5 usar STRIPOS()
				if(version_compare(phpversion(),"5") >= 0) {
					$posicion_inicial = stripos(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$_GET['q']);
				} else {
					$posicion_inicial = strpos(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$_GET['q']);
				}
				$posicion_inicial -= 50;
				if($posicion_inicial < 0) { $posicion_inicial = 0; }
				$frase_buscar = "...".substr(html_entity_decode(strip_tags(stripslashes($registros->fields["DESCRIPCION"]))),$posicion_inicial,200)."...";
				//	destaco el texto en la frase a mostrar
				$frase_buscar = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$frase_buscar);
				$frase_buscar = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$frase_buscar);
				//	destaco el texto en el titulo a mostrar
				$registros->fields["NOMBRE"] = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",stripslashes($registros->fields["NOMBRE"]));
				$registros->fields["NOMBRE"] = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$registros->fields["NOMBRE"]);
				$registros->fields["NOMBRE"] = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$registros->fields["NOMBRE"]);
				$registros->fields["NOMBRE"] = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$registros->fields["NOMBRE"]);

				$registros3 = $conexion->GetRow("SELECT count(*) as Cantidad  FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = 'productos') and (id_cabeza = '".$registros->fields["ID_PRODUCTO"]."') and (activo = 1)");

				$contenido .= "<blockquote>\n";
				$contenido .= "<a href=\"./?menu=productos&id=".$registros->fields["ID_PRODUCTO"]."\" title=\"[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE']."\" style=\"float: left;margin: 0 10px 10px 0;\"><img src=\"".$nombre_completo_imagensmall."\" border=\"0\" style=\"height: 80px;\" /></a>\n";
				$contenido .= "	<h4><a href='./?menu=productos&id=".$registros->fields["ID_PRODUCTO"]."'>".$registros->fields["NOMBRE"]."</a></h4>\n";
				$contenido .= "	<p style='font-size: 12px;line-height: 18px;margin-bottom: 10px;'>".$frase_buscar."</p>\n";
				$contenido .= "	<small style='border-top: 1px solid #ddd;'>En <a href='./?menu=productos&id=".$registros->fields["ID_PRODUCTO"]."'>Productos</a> - Leido ".$registros->fields["CLICK"]." veces - Comentado ".$registros3["Cantidad"]." veces</small>\n";
				$contenido .= "</blockquote>\n";

				$registros->MoveNext();
			}
			$registros->close();
		}
	}

	echo($contenido);
?>
<p>&nbsp;</p>
