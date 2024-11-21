<!doctype html>
<html lang="es">
    <head>
		<meta charset="ISO-8859-1">
        <title>Actualizar datos en MundoArchivistico</title>
    </head>
    <body>
	
<?php
	require("../config.php");
	
	//	obtengo todas las NOTICIAS con archivos
	$noticias = $conexion->Query("SELECT noticia_posee_archivo.* FROM noticia_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = noticia_posee_archivo.id_archivo GROUP BY id_noticia");
	if(!$noticias) {
		while(!$noticias->EOF) {
			echo("<dt>ID_NOTICIA: ".$noticias->fields["ID_NOTICIA"]."</dt>");
			//	obtengo todos los ARCHIVOS que posea
			$archivos = $conexion->Query("SELECT * FROM noticia_posee_archivo WHERE (id_noticia = '".$noticias->fields["ID_NOTICIA"]."') ORDER BY id_archivo");
			if($archivos) {
				while(!$archivos->EOF) {
					echo("<dd>ID_ARCHIVO: ".$archivos->fields["ID_ARCHIVO"]." - ");
					//	verifico si posee DESTINO noticia_posee_archivo
					if($archivos->fields["DESTINO"] == null) {
						$archivos2 = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
						if($archivos2) {
							while(!$archivos2->EOF) {
								//	verifico si el ARCHIVO posee DESTINO
								if($archivos2->fields["DESTINO"] == null) {
									echo("DESTINO: [contenido]");
									//$conexion->Execute("UPDATE archivos SET destino = 'contenido' WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");								
								}
								$archivos2->MoveNext();
							}						
						}
					} else {
						echo("DESTINO: ".$archivos->fields["DESTINO"]);
						//$conexion->Execute("UPDATE archivos SET destino = '".$archivos->fields["DESTINO"]."' WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
					}

					echo("</dd>");
					$archivos->MoveNext();
				}
			}
			echo("</dt>");
			$noticias->MoveNext();
		}		
	}

	//	obtengo todas las PUBLICACIONES con archivos
	$noticias = $conexion->Query("SELECT publicacion_posee_archivo.* FROM publicacion_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = publicacion_posee_archivo.id_archivo GROUP BY id_publicacion");
	if(!$noticias) {
		while(!$noticias->EOF) {
			echo("<dt>ID_PUBLICACION: ".$noticias->fields["ID_PUBLICACION"]."</dt>");
			//	obtengo todos los ARCHIVOS que posea
			$archivos = $conexion->Query("SELECT * FROM publicacion_posee_archivo WHERE (id_publicacion = '".$noticias->fields["ID_PUBLICACION"]."') ORDER BY id_archivo");
			if($archivos) {
				while(!$archivos->EOF) {
					echo("<dd>ID_ARCHIVO: ".$archivos->fields["ID_ARCHIVO"]." - ");
					//	verifico si posee DESTINO publicacion_posee_archivo
					if($archivos->fields["DESTINO"] != null) {
						echo("DESTINO: ".$archivos->fields["DESTINO"]);
						//$conexion->Execute("UPDATE archivos SET destino = '".$archivos->fields["DESTINO"]."' WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
					}

					echo("</dd>");
					$archivos->MoveNext();
				}
			}
			echo("</dt>");
			$noticias->MoveNext();
		}		
	}

	//	obtengo todas las ASIGNATURAS con archivos
	$noticias = $conexion->Query("SELECT asignatura_posee_archivo.* FROM asignatura_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = asignatura_posee_archivo.id_archivo GROUP BY id_asignatura");
	if(!$noticias) {
		while(!$noticias->EOF) {
			echo("<dt>ID_ASIGNATURA: ".$noticias->fields["ID_ASIGNATURA"]."</dt>");
			//	obtengo todos los ARCHIVOS que posea
			$archivos = $conexion->Query("SELECT * FROM asignatura_posee_archivo WHERE (id_asignatura = '".$noticias->fields["ID_ASIGNATURA"]."') ORDER BY id_archivo");
			if($archivos) {
				while(!$archivos->EOF) {
					echo("<dd>ID_ARCHIVO: ".$archivos->fields["ID_ARCHIVO"]." - ");
					$archivos2 = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
					if($archivos2) {
						while(!$archivos2->EOF) {
							//	verifico si posee DESTINO asignatura_posee_archivo
							if($archivos2->fields["TIPO_ARCHIVO"] == "Imagen") {
								if($archivos2->fields["DESTINO"] == null) {
									echo("DESTINO: [contenido]");
									//$conexion->Execute("UPDATE archivos SET destino = 'contenido' WHERE (id_archivo = '".$archivos2->fields["ID_ARCHIVO"]."')");
								} else {
									echo("DESTINO: ".$archivos2->fields["DESTINO"]);
								}
							}
							$archivos2->MoveNext();
						}						
					}
					echo("</dd>");
					$archivos->MoveNext();
				}
			}
			echo("</dt>");
			$noticias->MoveNext();
		}		
	}

	//	obtengo todas las INSTITUCIONES con archivos
	$noticias = $conexion->Query("SELECT institucion_posee_archivo.* FROM institucion_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = institucion_posee_archivo.id_archivo GROUP BY id_institucion");
	if(!$noticias) {
		while(!$noticias->EOF) {
			echo("<dt>ID_INSTITUCION: ".$noticias->fields["ID_INSTITUCION"]."</dt>");
			//	obtengo todos los ARCHIVOS que posea
			$archivos = $conexion->Query("SELECT * FROM institucion_posee_archivo WHERE (id_institucion = '".$noticias->fields["ID_INSTITUCION"]."') ORDER BY id_archivo");
			if($archivos) {
				while(!$archivos->EOF) {
					echo("<dd>ID_ARCHIVO: ".$archivos->fields["ID_ARCHIVO"]." - ");
					//	verifico si posee DESTINO institucion_posee_archivo
					if($archivos->fields["DESTINO"] == null) {
						$archivos2 = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
						if($archivos2) {
							while(!$archivos2->EOF) {
								if($archivos2->fields["TIPO_ARCHIVO"] == "Imagen") {
									if($archivos2->fields["DESTINO"] == null) {
										echo("DESTINO: [contenido]");
										//$conexion->Execute("UPDATE archivos SET destino = 'contenido' WHERE (id_archivo = '".$archivos2->fields["ID_ARCHIVO"]."')");
									} else {
										echo("DESTINO Imagen: ".$archivos2->fields["DESTINO"]);
									}
								}
								$archivos2->MoveNext();
							}						
						}
					} else {
						echo("DESTINO: ".$archivos->fields["DESTINO"]);
						//$conexion->Execute("UPDATE archivos SET destino = '".$archivos->fields["DESTINO"]."' WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
					}
					echo("</dd>");
					$archivos->MoveNext();
				}
			}
			echo("</dt>");
			$noticias->MoveNext();
		}		
	}

	//	obtengo todas los archivos de ARTICULOS
	$noticias = $conexion->Query("SELECT articulos_poseen.* FROM articulos_poseen LEFT JOIN archivos ON archivos.id_archivo = articulos_poseen.id_archivo GROUP BY id_articulo");
	if(!$noticias) {
		while(!$noticias->EOF) {
			echo("<dt>ID_ARTICULO: ".$noticias->fields["ID_ARTICULO"]."</dt>");
			//	obtengo todos los ARCHIVOS que posea
			$archivos = $conexion->Query("SELECT * FROM articulos_poseen WHERE (id_articulo = '".$noticias->fields["ID_ARTICULO"]."') ORDER BY id_archivo");
			if($archivos) {
				while(!$archivos->EOF) {
					echo("<dd>ID_ARCHIVO: ".$archivos->fields["ID_ARCHIVO"]." - ");
					//	verifico si posee DESTINO articulos_poseen
					if($archivos->fields["DESTINO"] == null) {
						$archivos2 = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
						if($archivos2) {
							while(!$archivos2->EOF) {
								if($archivos2->fields["TIPO_ARCHIVO"] == "Imagen") {
									if($archivos2->fields["DESTINO"] == null) {
										echo("DESTINO: [contenido]");
										//$conexion->Execute("UPDATE archivos SET destino = 'contenido' WHERE (id_archivo = '".$archivos2->fields["ID_ARCHIVO"]."')");
									} else {
										echo("DESTINO2: ".$archivos2->fields["DESTINO"]);
									}
								}
								$archivos2->MoveNext();
							}						
						}
					} else {
						echo("DESTINO: ".$archivos->fields["DESTINO"]);
						//$conexion->Execute("UPDATE archivos SET destino = '".$archivos->fields["DESTINO"]."' WHERE (id_archivo = '".$archivos->fields["ID_ARCHIVO"]."')");
					}
					echo("</dd>");
					$archivos->MoveNext();
				}
			}
			echo("</dt>");
			$noticias->MoveNext();
		}		
	}

?>
    </body>
</html>
