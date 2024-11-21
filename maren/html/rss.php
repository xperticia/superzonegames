<?php
	//Autor: Walter E. Irahola
	//E-mail: wirahola@hotmail.com
	//Proyecto: generar rss de noticias 

	//Indicamos al navegador que es un documento en XML
	header('Content-Type: text/xml'); 
	//Versión y juego de carácteres de nuestro documento
	echo('<?xml version="1.0" encoding="iso-8859-1"?>');
	//Aquí la conexión o archivo de conexión a la base de datos
	require("../xpanel/config.php"); 

	// Y generamos nuestro documento
	echo('<rss version="2.0">');
	echo('<channel>');
	echo('	<title>'.$sitio_nombre.'</title>');
	echo('	<link>'.$sitio_url.'</link>');
	echo('	<language>es-ar</language>');
	echo('	<description>Es nuestro objetivo el mantener actualizado a profesionales, simpatizantes, estudiantes y usuarios, de este conocimiento en desarrollo, la Archivología. Es nuestro interés que en este nuevo servicio, los interesados puedan sentirse al día de las novedades, información y cursos ofrecidos.</description>');
	echo('	<generator>Vícctor Hugo Arévalo Jordón</generator>');
	
	//Hacemos la consulta y obtengo las ultimas 4 noticias cargados
	$registros = $conexion->Query("SELECT * FROM noticias WHERE (activo = 1) ORDER BY fecha_alta DESC LIMIT 0,4");
	if($registros){
		while(!$registros->EOF) {
			echo('<item>');
			echo('	<title>'.$registros->fields["TITULO"].'</title>');
			echo('	<link>'.$sitio_url."?menu=noticias&amp;accion=ver&amp;id=".$registros->fields["ID_NOTICIA"].'</link>');
			echo('	<comments>'.$sitio_url."?menu=noticias&amp;accion=ver&amp;id=".$registros->fields["ID_NOTICIA"].'#comentarios</comments>');
			echo('	<pubDate>'.formato_fecha($registros->fields["FECHA_ALTA"],3).'</pubDate>');
			echo('	<category>Noticias</category>');
			echo('	<guid>'.$sitio_url."?menu=noticias&amp;accion=ver&amp;id=".$registros->fields["ID_NOTICIA"].'#comentarios</guid>');
			//verifico si posee imágenes
			$registros2 = $temp->Query("SELECT archivos.* FROM noticia_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = noticia_posee_archivo.id_archivo WHERE (id_noticia = '".$registros->fields["ID_NOTICIA"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY noticia_posee_archivo.id_archivo");
			if($registros2->RecordCount() > 0) {
				$i = 1;
				while(!$registros2->EOF) {
					//verifico si el archivo existe
					$nombre_completo_imagenoriginal = "../".$path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
					if(file_exists($nombre_completo_imagenoriginal)) {
						//obtengo el nombre y extension del archivo small
						$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
						$nombre = $res[0];
						$extension = $res[count($res)-1];
						//obtengo la ubicacion de la imagen small
						$nombre_completo_imagensmall = "../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
						if(file_exists($nombre_completo_imagensmall)) {
							$imagen = "<a href=\"javascript:ShowImagen('".$nombre_completo_imagenoriginal."');\"><img src='".$nombre_completo_imagensmall."' alt='Ver imagen ampliada' border='0' class='bordeimg' /></a>";
						} else {
							$imagen = "<a href=\"javascript:ShowImagen('".$nombre_completo_imagenoriginal."');\"><img src='".$nombre_completo_imagenoriginal."' alt='Ver imagen ampliada' border='0' class='bordeimg' /></a>";
						}
					} else {
						$imagen = "<span style='color: #FF0000;font-weight: bold;'>".$registros2->fields["NOMBRE_ARCHIVO"]."</span>";
					}
					$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
					//agrego la imagen al contenido
					$registros->fields["CONTENIDO"] = str_replace($referencia,$imagen,$registros->fields["CONTENIDO"]);
					++$i;
					$registros2->MoveNext();
				} #while
				$registros2->close();
			} #if
			echo('	<description><![CDATA['.stripslashes($registros->fields["CONTENIDO"]).']]></description>');
			//	echo('	<content:encoded><![CDATA['.$registros->fields["CONTENIDO"].']]></content:encoded>');
			echo('</item>');		
			$registros->MoveNext();
		}
	}
	$registros->close();
	echo('</channel>');
	echo('</rss>');
?>