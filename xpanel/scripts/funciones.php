<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

/*
	enviarMensaje
		Funcion que arma y envia un mensaje de correo electronico.
		Parametros:
		- $file : archivo HTML que se utilizara como plantilla
		- $campos: representa un array con el nombre del campo a reemplazar y el valor a colocar
			array("campo1"=>"valor1","campo2"=>"valor2")
		- $destinatario: representa el email del destinatario
		- $asunto
*/
function enviarMensaje($file,$campos,$destinatarios,$asunto,$tipo = 0) {
	global $conexion;
	global $sitio_url,$sitio_nombre,$sitio_eslogan,$sitio_direccion,$sitio_telefono,$sitio_email,$sitio_email_admin,$sitio_email_confirmacion,$path_imagenes,$path_archivos,$path_plantillas;

	//	obtengo los datos de la plantilla y verifico si existe
	$Htmlfile = $sitio_url."xpanel/".$path_plantillas.$file;
	if(verificar_url($Htmlfile)) {
		//obtengo los datos del archivo HTML
		$contenido = obtenerDatosArchivo($Htmlfile);		
		$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));

		//	busco el campo y reemplazo el valor
		foreach($campos as $campo=>$valor) {
			$contenido = str_replace("{".strtoupper($campo)."}",$valor,$contenido);
		}
		
		$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
		$contenido = str_replace("{SITIO_NOMBRE}", utf8_decode($sitio_nombre), $contenido);
		$contenido = str_replace("{SITIO_ESLOGAN}", $sitio_eslogan, $contenido);
		$contenido = str_replace("{SITIO_DIRECCION}", $sitio_direccion, $contenido);
		$contenido = str_replace("{SITIO_TELEFONO}", $sitio_telefono, $contenido);
		$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);
		$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
		$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

		//	obtengo el logo para boletin
		if(verificar_url($sitio_url.$path_plantillas."logo.jpg")) {
			$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/".$path_plantillas."logo.jpg"."' />", $contenido);
		} else {
			$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
		}

		if($tipo == 0){
			require('../scripts/phpmailer/src/PHPMailer.php');
			require('../scripts/phpmailer/src/SMTP.php');
			require('../scripts/phpmailer/src/Exception.php');
		} else {
			require('../xpanel/scripts/phpmailer/src/PHPMailer.php');
			require('../xpanel/scripts/phpmailer/src/SMTP.php');
			require('../xpanel/scripts/phpmailer/src/Exception.php');
		}

		//notifico al administrador la accion registrada
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		$mail->IsHTML(true); 

		$mail->Host = "c1340097.ferozo.com"; 
		$mail->Username = "info@superzonegames.com"; 
		$mail->Password = "rmwa@fzlysg1Rrc";
		
		$mail->ConfirmReadingTo = $sitio_email_confirmacion;
		$mail->From = $sitio_email; 
		$mail->FromName = utf8_decode($sitio_nombre);
		foreach ($destinatarios as $key => $value) {
			$mail->AddAddress($value); 
		}
		$mail->AddBCC($sitio_email);
		$mail->AddBCC($sitio_email_admin);

		$mail->Subject = $asunto;
		$mail->Body = $contenido;

		if($mail->Send()) {
			return "OK";
		} else {
			return "Error: ".$mail->ErrorInfo;
		}
	} else {
		return "Error: Plantilla &quot;".$file."&quot; no encontrada";
	}
}

/*
	crearBoletin2
		Funcion que arma y muestra el boletin segun su ID, cuando tiene ARTICULOS.
*/
function crearBoletin2($id) {
	global $conexion;
	global $sitio_url,$sitio_nombre,$sitio_eslogan,$sitio_email,$path_imagenes,$path_archivos;

	//	obtengo los datos del boletin
	$contenido = "";
	$datos = $conexion->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$id."')");
	if($datos){
		//	contenido de la plantilla
		$Htmlfile = $sitio_url."xpanel/templates/".$datos["PLANTILLA"];
		if(verificar_url($Htmlfile)) {
			//	parametros de la plantilla
			if(strpos($datos["PLANTILLA"], "/") > 0) {
				$res = explode("/", $datos["PLANTILLA"]);
				$carpeta = $res[0];
				$Configfile = $sitio_url."xpanel/templates/".$carpeta."/config.txt";
			} else {
				$carpeta = "";
				$Configfile = $sitio_url."xpanel/templates/"."config.txt";
			}
			if(verificar_url($Configfile)) {
				$ContenidoConfig = obtenerDatosArchivo($Configfile);
				//	crear un array por linea
				$array_parametros = explode("\n", $ContenidoConfig);
				foreach($array_parametros as $key => $val) {
					$linea = explode("@", $val);
					//	creo un array con parametro => valor
					if(count($linea) == 2) {
						$parametros[$linea[0]] = $linea[1];
					}
				}
			}

			$contenido = obtenerDatosArchivo($Htmlfile);

			//	obtengo el logo para boletin
			if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
				$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido);
			} else {
				$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
			}

			$contenido = str_replace("images/", $sitio_url."xpanel/templates/".$carpeta."/images/", $contenido);

			//	verifico si hay ARTICULOS
			$secciones = "";
			$cuerpo = ""; //almacena todos los articulos
			$articulo = ""; //almacena un articulo
			$orden = 0;
			$articulos = $conexion->Query("SELECT * FROM articulos WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (activo = 1) ORDER BY id_articulo");
			if($articulos) {
				if($articulos->RecordCount() > 0) {
					while(!$articulos->EOF) {
						if(isset($parametros)) {
							$secciones .= $parametros["SeccionAntes"]."<a style=".$parametros["SeccionEstiloEnlace"]." href='#".$articulos->fields["ID_ARTICULO"]."'>".$articulos->fields["TITULO"]."</a>". $parametros["SeccionDespues"];
						} else {
							$secciones .= "<a href='#".$articulos->fields["ID_ARTICULO"]."'>".$articulos->fields["TITULO"]."</a>";					
						}
						$articulos->fields["CONTENIDO"] = stripslashes($articulos->fields["CONTENIDO"]);				
						++$orden;
						$articulo = "<a name='".$articulos->fields["ID_ARTICULO"]."'></a>";

						//	verifico si posee IMAGENES el ARTICULO
						$archivos = $conexion->Query("SELECT archivos.* FROM articulos_poseen LEFT JOIN archivos ON archivos.id_archivo = articulos_poseen.id_archivo WHERE (id_articulo = '".$articulos->fields["ID_ARTICULO"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY articulos_poseen.id_archivo");
						if($archivos) {
							if($archivos->RecordCount() > 0) {
								$i = 1;
								while(!$archivos->EOF) {
									//	verifico si el archivo existe
									$nombre_completo_imagenoriginal = $sitio_url.$path_imagenes."boletin/".$archivos->fields["NOMBRE_ARCHIVO"];
									if(verificar_url($nombre_completo_imagenoriginal)) {
										//	obtengo el nombre y extension del archivo
										$res = explode(".", $archivos->fields["NOMBRE_ARCHIVO"]);
										$nombre = $res[0];
										$extension = $res[count($res)-1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = $sitio_url.$path_imagenes."boletin/".$nombre."_small.".$extension;
										if(verificar_url($nombre_completo_imagensmall)) {
											$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagensmall."' alt='".stripslashes($archivos->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($archivos->fields["COMENTARIO"])."</em></div>";
										} else {
											$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagenoriginal."' alt='".stripslashes($archivos->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($archivos->fields["COMENTARIO"])."</em></div>";
										}
									} else {
										$imagen = "<span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_imagenes."boletin/".$archivos->fields["NOMBRE_ARCHIVO"]."</span>";
									}
									$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
									//	agrego la imagen al contenido
									$articulos->fields["CONTENIDO"] = str_replace($referencia,$imagen,$articulos->fields["CONTENIDO"]);
									++$i;

									$archivos->MoveNext();
								}
							}
						}
						//	verifico si posee ARCHIVOS ADJUNTOS el ARTICULO
						$archivos_adjuntos = "";
						$archivos = $conexion->Query("SELECT archivos.* FROM articulos_poseen LEFT JOIN archivos ON archivos.id_archivo = articulos_poseen.id_archivo WHERE (id_articulo = '".$articulos->fields["ID_ARTICULO"]."') and (tipo_archivo = 'Archivo') ORDER BY articulos_poseen.id_archivo");
						if($archivos) {
							if($archivos->RecordCount() > 0) {
								$archivos_adjuntos = "<div style='clear: both;'><h2>Archivos adjuntos: </h2><ol>";
								while(!$archivos->EOF) {
									//	verifico si el archivo existe
									$nombre_completo_documento = $sitio_url.$path_archivos."boletin/".$archivos->fields["NOMBRE_ARCHIVO"];
									if(verificar_url($nombre_completo_documento)) {
										//	obtengo la extension del archivo
										$res = explode(".", $archivos->fields["NOMBRE_ARCHIVO"]);
										$formato = $res[count($res) - 1];
										//	obtengo el tamaño del archivo
										//$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
										if(($archivos->fields["COMENTARIO"] == "") or ($archivos->fields["COMENTARIO"] == null)) {
											$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$archivos->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".$archivos->fields["NOMBRE_ARCHIVO"]."</a> | ".$formato."</li>";
										} else {
											$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$archivos->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".stripslashes($archivos->fields["COMENTARIO"])."</a> | ".$formato."</li>";
										}
									} else {
										$archivos_adjuntos .= "<li><span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_archivos."boletin/".$archivos->fields["NOMBRE_ARCHIVO"]."</span></li>";
									}
									$archivos->MoveNext();
								}
								$archivos_adjuntos .= "</ol></div>";
							}
						}
						//	asignar valores
						if(isset($parametros)) {
							$articulo .= $parametros["TituloArticuloAntes"].stripslashes($articulos->fields["TITULO"]).$parametros["TituloArticuloDespues"];
							$articulo .= $parametros["ContenidoAntes"].stripslashes($articulos->fields["CONTENIDO"]).$parametros["ContenidoDespues"];
							$articulo .= $archivos_adjuntos;
							if($parametros["MostrarArriba"] == 1) {
								$articulo .= $parametros["ArribaAntes"]."<a style=".$parametros["ArribaEstiloEnlace"]." href='#top'>".$parametros["ArribaContenido"]."</a>".$parametros["ArribaDespues"];
							}
						} else {
							$articulo .= "<p><strong>".stripslashes($articulos->fields["TITULO"])."</strong></p>";
							$articulo .= "<p>".stripslashes($articulos->fields["CONTENIDO"])."<p>";
							$articulo .= "<p>".$archivos_adjuntos."<p>";
							$articulo .= "<p style='text-align: right;'><a href='#top'>[arriba]</a></p>";
						}
						$orden = sprintf("%02s",$orden);
						$contenido = str_replace("{CONTENIDO".$orden."}",$articulo,$contenido);
						$cuerpo .= $articulo;

						$articulos->MoveNext();
					}
					$contenido = str_replace("{CONTENIDO}",$cuerpo,$contenido);
					$contenido = str_replace("{SECCIONES}",$secciones,$contenido);
				}					
			}

			$articulo = "";
			//	verifico si posee IMAGENES el BOLETIN
			$registros2 = $conexion->Query("SELECT archivos.* FROM boletin_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = boletin_posee_archivo.id_archivo WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (tipo_archivo = 'Imagen') and (archivos.destino = 'contenido') ORDER BY boletin_posee_archivo.id_archivo");
			if($registros2) {
				if($registros2->RecordCount() > 0) {
					$i = 1;
					while(!$registros2->EOF) {
						//	verifico si el archivo existe
						$nombre_completo_imagenoriginal = $sitio_url.$path_imagenes."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
						if(verificar_url($nombre_completo_imagenoriginal)) {
							//	obtengo el nombre y extension del archivo
							$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
							$nombre = $res[0];
							$extension = $res[count($res)-1];
							//	obtengo la ubicacion de la imagen small
							$nombre_completo_imagensmall = $sitio_url.$path_imagenes."boletin/".$nombre."_small.".$extension;
							if(verificar_url($nombre_completo_imagensmall)) {
								$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagensmall."' alt='".stripslashes($registros2->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($registros2->fields["COMENTARIO"])."</em></div>";
							} else {
								$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagenoriginal."' alt='".stripslashes($registros2->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($registros2->fields["COMENTARIO"])."</em></div>";
							}
						} else {
							$imagen = "<span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_imagenes."boletin/".$registros2->fields["NOMBRE_ARCHIVO"]."</span>";
						}
						$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
						//	agrego la imagen al contenido
						$datos["CONTENIDO"] = str_replace($referencia,$imagen,$datos["CONTENIDO"]);
						++$i;

						$registros2->MoveNext();
					}
				}
			}
			//	verifico si posee ARCHIVOS ADJUNTOS el BOLETIN
			$archivos_adjuntos = "";
			$registros2 = $conexion->Query("SELECT archivos.* FROM boletin_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = boletin_posee_archivo.id_archivo WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (tipo_archivo = 'Archivo') ORDER BY boletin_posee_archivo.id_archivo");
			if($registros2) {
				if($registros2->RecordCount() > 0) {
					$archivos_adjuntos = "<div style='clear: both;'><h2>Archivos adjuntos: </h2><ol>";
					while(!$registros2->EOF) {
						//	verifico si el archivo existe
						$nombre_completo_documento = $sitio_url.$path_archivos."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
						if(verificar_url($nombre_completo_documento)) {
							//	obtengo la extension del archivo
							$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
							$formato = $res[count($res) - 1];
							//	obtengo el tamaño del archivo
							//$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
							if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) {
								$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$registros2->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".$registros2->fields["NOMBRE_ARCHIVO"]."</a> | ".$formato."</li>";
							} else {
								$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$registros2->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".stripslashes($registros2->fields["COMENTARIO"])."</a> | ".$formato."</li>";
							}
						} else {
							$archivos_adjuntos .= "<li><span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_archivos."boletin/".$registros2->fields["NOMBRE_ARCHIVO"]."</span></li>";
						}
						$registros2->MoveNext();
					}
					$archivos_adjuntos .= "</ol></div>";
				}
			}
			//	verifico si hay formatos
			if(isset($parametros)) {
				$contenido = str_replace("{TITULO}", $parametros["TituloArticuloAntes"].stripslashes($datos["TITULO"]).$parametros["TituloArticuloDespues"], $contenido);

				$articulo .= $parametros["TituloArticuloAntes"].stripslashes($datos["TITULO"]).$parametros["TituloArticuloDespues"];
				$articulo .= $parametros["ContenidoAntes"].stripslashes($datos["CONTENIDO"]).$parametros["ContenidoDespues"];
				$articulo .= $archivos_adjuntos;
				if($parametros["MostrarArriba"] == 1) {
					$articulo .= $parametros["ArribaAntes"]."<a style=".$parametros["ArribaEstiloEnlace"]." href='#top'>".$parametros["ArribaContenido"]."</a>".$parametros["ArribaDespues"];
				}
			} else {
				$contenido = str_replace("{TITULO}", stripslashes($datos["TITULO"]), $contenido);

				$articulo .= "<h1>".stripslashes($datos["TITULO"])."</h1>";
				$articulo .= "<p>".stripslashes($datos["CONTENIDO"])."</p>";
				$articulo .= "<p>".$archivos_adjuntos."</p>";
			}	#if

			$contenido = str_replace("{CONTENIDO}", $articulo, $contenido);

			//	obtengo las publicidades
			if(($datos["BANNER1"] != null) and verificar_url($sitio_url.$path_imagenes."boletin/".$datos["BANNER1"])) {
				$contenido = str_replace("{PUBLICIDAD1}", "<img src='".$sitio_url.$path_imagenes."boletin/".$datos["BANNER1"]."'>", $contenido);
			} else {
				$contenido = str_replace("{PUBLICIDAD1}", "", $contenido);
			}
			if(($datos["BANNER2"] != null) and verificar_url($sitio_url.$path_imagenes."boletin/".$datos["BANNER2"])) {
				$contenido = str_replace("{PUBLICIDAD2}", "<img src='".$sitio_url.$path_imagenes."boletin/".$datos["BANNER2"]."'>", $contenido);
			} else {
				$contenido = str_replace("{PUBLICIDAD2}", "", $contenido);
			}

			//	actualizo las referencias de los contenidos
			$contenido = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $contenido);
			$contenido = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $contenido);
			$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
			$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
			$contenido = str_replace("{SITIO_ESLOGAN}", $sitio_eslogan, $contenido);
			$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);
			$contenido = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $contenido);

			//	actualizo las referencias del titulo
			$datos["TITULO"] = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_URL}", $sitio_url, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_EMAIL}", $sitio_email, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $datos["TITULO"]);

			//	obtengo el cuerpo de la plantilla HTML
			$contenido = stripslashes(substr($contenido,(strpos($contenido,"<body>")+6),(strpos($contenido,"</body>")-strpos($contenido,"<body>"))-6));
			Return $contenido;
		} else {
			Return "ERROR: No existe la plantilla: ".$Htmlfile.".";
		}
	} else {
		Return "ERROR: No hay resultado para el ID: ".$id.".";
	}
}

/*
	crearBoletin
		Funcion que arma y muestra el boletin segun su ID.
*/
function crearBoletin($id) {
	global $conexion;
	global $sitio_url,$sitio_nombre,$sitio_eslogan,$sitio_email,$path_imagenes,$path_archivos;

	//	obtengo los datos del boletin
	$contenido = "";
	$datos = $conexion->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$id."')");
	if($datos){
		//	contenido de la plantilla
		$Htmlfile = $sitio_url."xpanel/templates/".$datos["PLANTILLA"];
		if(verificar_url($Htmlfile)) {
			//	parametros de la plantilla
			if(strpos($datos["PLANTILLA"], "/") > 0) {
				$res = explode("/", $datos["PLANTILLA"]);
				$carpeta = $res[0];
				$Configfile = $sitio_url."xpanel/templates/".$carpeta."/config.txt";
			} else {
				$carpeta = "";
				$Configfile = $sitio_url."xpanel/templates/"."config.txt";
			}
			if(verificar_url($Configfile)) {
				$ContenidoConfig = obtenerDatosArchivo($Configfile);
				//	crear un array por linea
				$array_parametros = explode("\n", $ContenidoConfig);
				foreach($array_parametros as $key => $val) {
					$linea = explode("@", $val);
					//	creo un array con parametro => valor
					if(count($linea) == 2) {
						$parametros[$linea[0]] = $linea[1];
					}
				}
			}

			$contenido = obtenerDatosArchivo($Htmlfile);

			//	obtengo el logo para boletin
			if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
				$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido);
			} else {
				$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
			}

			$contenido = str_replace("images/", $sitio_url."xpanel/templates/".$carpeta."/images/", $contenido);

			$articulo = "";
			//	verifico si posee IMAGENES
			$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
			if($registros2) {
				if($registros2->RecordCount() > 0) {
					$i = 1;
					while(!$registros2->EOF) {
						//	verifico si el archivo existe
						$nombre_completo_imagenoriginal = $sitio_url.$path_imagenes."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
						if(verificar_url($nombre_completo_imagenoriginal)) {
							//	obtengo el nombre y extension del archivo
							$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
							$nombre = $res[0];
							$extension = $res[count($res)-1];
							//	obtengo la ubicacion de la imagen small
							$nombre_completo_imagensmall = $sitio_url.$path_imagenes."boletin/".$nombre."_small.".$extension;
							if(verificar_url($nombre_completo_imagensmall)) {
								$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagensmall."' alt='".stripslashes($registros2->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($registros2->fields["COMENTARIO"])."</em></div>";
							} else {
								$imagen = "<div style=".$parametros["ContenidoImagenEstilo"]."><img src='".$nombre_completo_imagenoriginal."' alt='".stripslashes($registros2->fields["COMENTARIO"])."' border='0' /><br /><em>".stripslashes($registros2->fields["COMENTARIO"])."</em></div>";
							}
						} else {
							$imagen = "<span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_imagenes."boletin/".$registros2->fields["NOMBRE_ARCHIVO"]."</span>";
						}
						$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
						//	agrego la imagen al contenido
						$datos["CONTENIDO"] = str_replace($referencia,$imagen,$datos["CONTENIDO"]);
						++$i;

						$registros2->MoveNext();
					}
				}
			}
			//	verifico si posee ARCHIVOS ADJUNTOS
			$archivos_adjuntos = "";
			$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");
			if($registros2) {
				if($registros2->RecordCount() > 0) {
					$archivos_adjuntos = "<div style='clear: both;'><h2>Archivos adjuntos: </h2><ol>";
					while(!$registros2->EOF) {
						//	verifico si el archivo existe
						$nombre_completo_documento = $sitio_url.$path_archivos."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
						if(verificar_url($nombre_completo_documento)) {
							//	obtengo la extension del archivo
							$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
							$formato = $res[count($res) - 1];
							//	obtengo el tamaño del archivo
							//$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
							if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) {
								$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$registros2->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".$registros2->fields["NOMBRE_ARCHIVO"]."</a> | ".$formato."</li>";
							} else {
								$archivos_adjuntos .= "<li><a href='".$sitio_url."html/descargar.php?idc={EMAIL}&ida=".$registros2->fields["ID_ARCHIVO"]."' target='_blank' title='Descargar archivo adjunto'>".stripslashes($registros2->fields["COMENTARIO"])."</a> | ".$formato."</li>";
							}
						} else {
							$archivos_adjuntos .= "<li><span style='color: #FF0000;font-weight: bold;'>".$sitio_url.$path_archivos."boletin/".$registros2->fields["NOMBRE_ARCHIVO"]."</span></li>";
						}
						$registros2->MoveNext();
					}
					$archivos_adjuntos .= "</ol></div>";
				}
			}
			//	verifico si hay formatos
			if(isset($parametros)) {
				$contenido = str_replace("{TITULO}", $parametros["TituloArticuloAntes"].stripslashes($datos["TITULO"]).$parametros["TituloArticuloDespues"], $contenido);

				$articulo .= $parametros["ContenidoAntes"].stripslashes($datos["CONTENIDO"]).$parametros["ContenidoDespues"];
				$articulo .= $archivos_adjuntos;
				if($parametros["MostrarArriba"] == 1) {
					$articulo .= $parametros["ArribaAntes"]."<a style=".$parametros["ArribaEstiloEnlace"]." href='#top'>".$parametros["ArribaContenido"]."</a>".$parametros["ArribaDespues"];
				}
			} else {
				$contenido = str_replace("{TITULO}", stripslashes($datos["TITULO"]), $contenido);

				$articulo .= "<p>".stripslashes($datos["CONTENIDO"])."<p>";
				$articulo .= "<p>".$archivos_adjuntos."<p>";
			}	#if

			$contenido = str_replace("{CONTENIDO}", $articulo, $contenido);

			//	obtengo las publicidades
			if(($datos["BANNER1"] != null) and verificar_url($sitio_url.$path_imagenes."boletin/".$datos["BANNER1"])) {
				$contenido = str_replace("{PUBLICIDAD1}", "<img src='".$sitio_url.$path_imagenes."boletin/".$datos["BANNER1"]."'>", $contenido);
			} else {
				$contenido = str_replace("{PUBLICIDAD1}", "", $contenido);
			}
			if(($datos["BANNER2"] != null) and verificar_url($sitio_url.$path_imagenes."boletin/".$datos["BANNER2"])) {
				$contenido = str_replace("{PUBLICIDAD2}", "<img src='".$sitio_url.$path_imagenes."boletin/".$datos["BANNER2"]."'>", $contenido);
			} else {
				$contenido = str_replace("{PUBLICIDAD2}", "", $contenido);
			}

			//	actualizo las referencias de los contenidos
			$contenido = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $contenido);
			$contenido = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $contenido);
			$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
			$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
			$contenido = str_replace("{SITIO_ESLOGAN}", $sitio_eslogan, $contenido);
			$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);
			$contenido = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $contenido);

			//	actualizo las referencias del titulo
			$datos["TITULO"] = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_URL}", $sitio_url, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{SITIO_EMAIL}", $sitio_email, $datos["TITULO"]);
			$datos["TITULO"] = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $datos["TITULO"]);

			//	obtengo el cuerpo de la plantilla HTML
			$contenido = stripslashes(substr($contenido,(strpos($contenido,"<body>")+6),(strpos($contenido,"</body>")-strpos($contenido,"<body>"))-6));
			Return $contenido;
		} else {
			Return "ERROR: No existe la plantilla: ".$Htmlfile.".";
		}
	} else {
		Return "ERROR: No hay resultado para el ID: ".$id.".";
	}
}

/*
	armarHead
		Funcion que arma y muestra el titulo de la pagina a visualizar.
*/
function armarHead() {
	global $nombre_sistema;
	global $datos_acceso;
	$titulo = "";
	if(isset($_GET['accion']) and (($_GET['accion'] == "nuevo") or ($_GET['accion'] == "ver") or ($_GET['accion'] == "editar") or ($_GET['accion'] == "eliminar"))) {
		$titulo = ucfirst(strtolower($_GET['accion']))." registro | ";
	}
	if(isset($datos_acceso)) {
		if(($_GET['menu'] == "preguntas") or ($_GET['menu'] == "respuestas") or ($_GET['menu'] == "estadisticas")) {
			$titulo = $titulo.ucfirst($_GET['menu'])." | Encuestas | ".$nombre_sistema;
		} else {
			if($datos_acceso["NOMBRE_MENU"] == "") {
				$titulo = $titulo.ucfirst(strtolower($_GET['menu']))." | ".$nombre_sistema;
			} else {
				if(($_GET['menu'] == "reportes") and isset($_GET['accion'])) {
					switch($_GET['accion']) {
						case "porproductosapedir":
							$titulo = "Reporte Productos a Pedir | ".$nombre_sistema;
							break;
						case "porventasperiodicas":
							$titulo = "Reporte Ventas Peri&oacute;dicas | ".$nombre_sistema;
							break;
						case "porusuario":
							$titulo = "Reporte Actividades de Usuarios | ".$nombre_sistema;
							break;
						default:
							$titulo = $titulo.$datos_acceso["NOMBRE_MENU"]." | ".$nombre_sistema;
							break;
					}						
				} else {
					$titulo = $titulo.$datos_acceso["NOMBRE_MENU"]." | ".$nombre_sistema;
				}
			}
		}
	} else {
		if(isset($_GET['menu'])) {
			$titulo = $titulo.ucfirst(strtolower($_GET['menu']))." | ".$nombre_sistema;
		} else {
			$titulo = $titulo.$nombre_sistema;
		}
	}
	Return $titulo;
}

/*
	showQuery
		Funcion que muestre los datos de una consulta, permitiendo paginar los datos, ordenar los datos.
	Parametros:
		- $conexion: enlace a la base de datos.
		- $consulta: consulta SQL para obtener los datos.
		- $campos: representa un array con el nombre de los campos ha obtener de la tabla, y sus titulos de columnas a mostrar
			array("campo1"=>"titulo1","campo2"=>"titulo2")
	*	- $claves: representa un array con los campos claves de la tabla. 
			array(0=>"campoclave1",1=>"campoclave2")
			El ordenamiento por defecto se realiza sobre el primer campo clave.
			Es utilizado este parametro para ubicar al registro cuando se desee realizar una accion.
		- $cantidad_filas: indica la cantidad de filas a mostrar por pagina. Si se pasa el valor -1 se muestran todos los datos.
		- $paginador: determina si muestra la barra de navegacion entre paginas.
	*	- $acciones: determina si muestras las acciones NUEVO, EDITAR y ELIMINAR registros.
	*	- $ancho y $alto: determina el ancho de la ventana de acciones
*/

function showQuery($conexion,$query,$campos,$cantidad_filas = 10,$paginador = true) {
	if(isset($_GET['txp'])) {
		$cantidad_filas = $_GET['txp'];	
	}
	global $estados;
	global $fondo_filas_par;
	global $fondo_filas_impar;
	global $fondo_filas_sobre;
	if(isset($_GET['c']) and isset($_GET['o'])) {
		//	busca y elimina el orden anterior
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));	 
		if($sinORDERBY == "") {
			$query .= $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		} else {
			$query = $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		}
	}
	$record = $conexion->Execute($query);
	if (!$record) {   
        print $conexion->ErrorMsg(); 
	} else {
		if (!isset($_GET['pag'])) $_GET['pag'] = 1; // Por defecto, pagina 1
		$maxpags = 20;
		?>
		<table id="listado" class="table table-bordered table-condensed">
		<thead>
		<tr>
		<?php
			//	muestro los titulos de los campos
			foreach($campos as $campo=>$titulo) {
				//	determino el campo y tipo de ordenamiento
				$estilo = "";
				$img = "";
				$o = "asc";
				if(isset($_GET['c']) and ($_GET['c'] == $campo)) {
					if($_GET['o'] == "asc") { $o = "desc";
					} else {
						if($_GET['o'] == "desc") { $o = "asc"; }
					}
					$estilo = $o;
				}
				$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
				if(isset($_GET['accion'])) { $url .= "&accion=".$_GET['accion']; }
				if(isset($_GET['registro'])) { $url .= "&registro[]=".$_GET['registro'][0]; }
				$url .= "&c=".$campo."&o=".$o;
				//	verifico si se busco algun texto
				if(isset($_GET['texto'])) { $url .= "&texto=".$_GET['texto']; }
				//	verifico si se ha seleccionado cuantos registros mostrar por pagina
				if(isset($_GET['txp'])) { $url .= "&txp=".$_GET['txp']; }
				$url .= "&pag=".$_GET['pag'];
				?>
			<th class="<?php echo($estilo); ?>"><a href="<?php echo($url); ?>"><?php echo($titulo); ?></a></th>
				<?php
			}
		?>
		</tr>
	    </thead>
		<tbody>					
		<?php
			if($paginador == false) {
				$cantidad_filas = -1;
			}
			if($cantidad_filas == -1) {
				$cantidad_filas = $record->RecordCount();
			}
			if(!isset($_GET['pag'])) { $_GET['pag'] = 1; }
			$reg1 = ($_GET['pag']-1) * $cantidad_filas;
			$enlace = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(isset($_GET['accion'])) { $enlace .= "&accion=".$_GET['accion']; }
			if(isset($_GET['registro'])) { $enlace .= "&registro[]=".$_GET['registro'][0]; }
			//	verifico si se ordeno por algun campo
			if(isset($_GET['c']) and isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
			//	verifico si se busco algun texto
			if(isset($_GET['texto'])) { $enlace .= "&texto=".$_GET['texto']; }
			//	verifico si se ha seleccionado cuantos registros mostrar por pagina
			if(isset($_GET['txp'])) { $enlace .= "&txp=".$_GET['txp']; }
			$enlace .= "&pag=";
			$total = $record->RecordCount();

			$sumaTotal = 0;
			$sumaRecibido = 0;
			$sumaSaldo = 0;
			$sumaCantidad = 0;

			//	coloco los contenidos
			for ($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
				if($i >= $reg1) {
					//	obtengo el color de fondo de la fila
					if(($i % 2) == 0) { $fondo = $fondo_filas_par; } else { $fondo = $fondo_filas_impar; }
					?>					
		<tr>
					<?php
						$j = 0;
						//	muestro el contenido de los campos
						foreach($campos as $campo=>$titulo) {
							//	obtengo el tipo de dato de la columna
							$type = $record->MetaType($record->FetchField($j));
							if(($type == "D") or ($type == "T")) {
								?>
			<td><?php echo($record->UserDate($record->fields[$campo],"d/m/Y")); ?></td>
								<?php
							} else {								
								?>
			<td <?php if(($type == "I") or ($type == "N") or ($type == "R")) { echo("style='text-align: right;'"); } ?>>
								<?php 
								switch($campo) {
									case "ID_VENTA":
									case "COD_VENTA":
									case "ID_EGRESO":
									case "ID_COTIZACION":
									case "ID_INVENTARIO":
										$record->fields[$campo] = sprintf("%04s",$record->fields[$campo]);
										echo(stripslashes($record->fields[$campo]));
										break;
									case "ACTIVO":
									case "PAGADO":
										echo($estados[$record->fields[$campo]]);
										break;
									case "TOTAL":
										$sumaTotal += $record->fields[$campo];
										echo(number_format($record->fields[$campo], 2, ',', '.'));
										break;
									case "RECIBIDO":
										$sumaRecibido += $record->fields[$campo];
										echo(number_format($record->fields[$campo], 2, ',', '.'));
										break;
									case "SALDO":
										$sumaSaldo += $record->fields[$campo];
										echo(number_format($record->fields[$campo], 2, ',', '.'));
										break;
									case "CANTIDAD":
										$sumaCantidad += $record->fields[$campo];
										echo($record->fields[$campo]);
										break;
									case "PRECIO_COSTO":
									case "PRECIO_VENTA":
										echo(number_format($record->fields[$campo], 2, ',', '.'));
										break;
									default:
										echo($record->fields[$campo]); 
										break;								
								}
								?>
			</td>
								<?php
							}
							$j++;
						}
					?>
		</tr>
					<?php
				}
				$record->MoveNext(); 
			}
			//	completo con filas en blanco
			if(($total - $reg1) < $cantidad_filas) {
				for($i=($total - $reg1); $i<$cantidad_filas; $i++) {
					?>
		<tr>
					<?php
					foreach($campos as $campo=>$titulo) {
						?>
			<td>&nbsp;</td>
						<?php
					}
				?>
		</tr>
				<?php
				}
			}
		?>
		</tbody>					
		<?php
			//fila de totales
			if((isset($_GET['menu']) and ($_GET['menu'] == "clientes")) and (isset($_GET['accion']) and (($_GET['accion'] == "ventasregistradas") or ($_GET['accion'] == "serviciosregistrados")))) {
				?>
		<tfoot>
		<tr>
			<td style="text-align: right;font-weight: bold;" colspan="<?php echo(count($campos)-4); ?>">TOTAL</td>
			<td style="text-align: right;"><?php echo(number_format($sumaTotal, 2, ',', '.')); ?></td>
			<td style="text-align: right;"><?php echo(number_format($sumaRecibido, 2, ',', '.')); ?></td>
			<td style="text-align: right;"><?php echo(number_format($sumaSaldo, 2, ',', '.')); ?></td>
			<td style="text-align: right;">&nbsp;</td>
		</tr>
		</tr>
		</tfoot>
				<?php
			}
			//fila de totales
			if((isset($_GET['menu']) and ($_GET['menu'] == "productos")) and (isset($_GET['accion']) and ($_GET['accion'] == "ventasregistradas"))) {
				?>
		<tfoot>
		<tr>
			<td style="text-align: right;font-weight: bold;" colspan="<?php echo(count($campos)-5); ?>">TOTAL</td>
			<td style="text-align: right;"><?php echo(number_format($sumaTotal, 2, ',', '.')); ?></td>
			<td style="text-align: right;"><?php echo(number_format($sumaRecibido, 2, ',', '.')); ?></td>
			<td style="text-align: right;"><?php echo(number_format($sumaSaldo, 2, ',', '.')); ?></td>
			<td style="text-align: right;">&nbsp;</td>
			<td style="text-align: right;"><?php echo($sumaCantidad); ?></td>
		</tr>
		</tr>
		</tfoot>
				<?php
			}
		?>
		</table>
		<footer>
			<div class="container">
				<div class="row-fluid">
					<div class="span12">
						<div class="btn-toolbar">
							<p class="float-left">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
							<?php if(($paginador == true) and ($total >= $cantidad_filas)) { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } else { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } ?>
						</div>
					</div>		
				</div>
			</div>
		</footer>
		<?php
		$record->Close();  
    }	
}

/*
	showTable: 
		Funcion que muestra los datos de una tabla, permitiendo paginar los datos, ordenarlos, busqueda y elegir acciones sobre los datos.
	Parametros:
		- $conexion: enlace a la base de datos.
		- $tabla: nombre de la tabla donde obtener los datos.
		- $campos: representa un array con el nombre de los campos ha obtener de la tabla, y sus titulos de columnas a mostrar
			array("campo1"=>"titulo1","campo2"=>"titulo2")
		- $claves: representa un array con los campos claves de la tabla. 
			array(0=>"campoclave1",1=>"campoclave2")
			El ordenamiento por defecto se realiza sobre el primer campo clave.
			Es utilizado este parametro para ubicar al registro cuando se desee realizar una accion.
		- $cantidad_filas: indica la cantidad de filas a mostrar por pagina. Si se pasa el valor -1 se muestran todos los datos.
		- $paginador: determina si muestra la barra de navegacion entre paginas.
		- $acciones: determina si muestras las acciones NUEVO, EDITAR y ELIMINAR registros.
		- $ancho y $alto: determina el ancho de la ventana de acciones
		- $datos_acceso: posee los privilegios de acceso sobre la tabla o consulta
*/

function showTable($conexion,$tabla,$campos,$claves,$cantidad_filas = 10,$paginador = true,$acciones = true,$ancho = 780,$alto = 400,$datos_acceso=null,$masopciones = null,$masopcionesporregistro = null) {
	if(isset($_GET['txp'])) {
		$cantidad_filas = $_GET['txp'];	
	}
	global $estados;
	global $fondo_filas_par;
	global $fondo_filas_impar;
	global $fondo_filas_sobre;
	//	armo la consulta
	$query = "SELECT ";
	$i = 0;
	foreach($campos as $campo=>$titulo) {
		$i++;
		$query .= $campo;
		if($i != count($campos)) {
			$query .= ",";
		}
	}
	$query .= " FROM ".$tabla;
	if(isset($_GET['texto'])) {
		$query .= " WHERE ";
		$ca = count($campos);
		foreach($campos as $campo=>$titulo){
			$query .= " (".$campo." like '%".$_GET['texto']."%')";
			$ca--;
			if($ca > 0) {
				$query .= " or ";
			}
		}
	}
	//	ordeno los datos
	if(isset($_GET['c']) and isset($_GET['o'])) {
		//	busca y elimina el orden anterior
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));	 
		$query .= $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
	} else {
		//	por defecto ordeno por el primer campo clave
		$query .= " ORDER BY ".$claves[0];
	}
	$record = $conexion->Execute($query);
	if (!$record) {   
        print $conexion->ErrorMsg(); 
	} else {
		if (!isset($_GET['pag'])) $_GET['pag'] = 1; // Por defecto, pagina 1
		$maxpags = 20;
		?>
	<form id="flista" method="get" action="<?php echo($_SERVER['PHP_SELF']) ?>">
    	<input type="hidden" name="menu" value="<?php echo($_GET['menu']); ?>" />
    	<input type="hidden" name="accion" id="accion" value="" />
		<div style="margin: 5px auto;">
			<div style="float: left;">
			<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["INSERTAR"] == 1))) { ?><input id="btNuevoRegistro" name="nuevo" type="button" value="Nuevo" class="btn primary" onclick="VerificarOpcion2(this);return document.ValorRetorno;" /><?php } ?>
			<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["VER"] == 1))) { ?><input id="btVerRegistro" name="ver" type="button" class="btn primary" value="Ver" onclick="VerificarOpcion2(this);return document.ValorRetorno;" disabled="disabled" /><?php } ?>
			<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["MODIFICAR"] == 1))) { ?><input id="btEditarRegistro" name="editar" type="button" class="btn primary" value="Editar" onclick="VerificarOpcion2(this);return document.ValorRetorno;" disabled="disabled" /><?php } ?>
			<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["ELIMINAR"] == 1))) { ?><input id="btEliminarRegistro" name="eliminar" type="button" class="btn primary" value="Eliminar" onclick="VerificarOpcion2(this);return document.ValorRetorno;" disabled="disabled" /><?php } ?>
				<?php
					if($masopciones) {
						?>
				<select onchange="AbrirURL(this);" <?php if($datos_acceso["INSERTAR"] == 0) { echo("disabled='disabled'");	} ?>>
					<option value="">Mas opciones</option>
						<?php
						foreach($masopciones as $campo=>$titulo) {
							?>
					<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$campo); ?>"><?php echo($titulo); ?></option>
							<?php
						}
						?>
				</select>
						<?php
					}
				?>				
            </div>
			<div style="float: right;" align="right">
				<input type="text" name="texto" placeholder="buscar" value="<?php if(isset($_GET['texto'])) { echo($_GET['texto']); } ?>" />
				<input class="btn primary" type="submit" name="buscar" value="buscar" onclick="VerificarOpcion2(this);return document.ValorRetorno;" />
			</div>
			<div style="clear: both;"></div>
		</div>
		<table id="listado" class="table table-bordered table-condensed">
		<thead>
		<tr>
			<th style="width:15px;vertical-align: top;"><input type="checkbox" id="CheckTodo" name="CheckTodo" onclick="javascript:SeleccionarColumna(this);" /></th>
			<th style="width:32px;">
				<ul class="tabs" data-tabs="tabs" style="margin:0px;padding: 0px;border: none;line-height: 10px;">
					<li class="dropdown" data-dropdown="dropdown" style="margin:0px;padding: 0px;border: none;line-height: 10px;">
						<a href="#" class="dropdown-toggle" style="margin:0px;padding: 0px;border: none;line-height: 10px;"><img src="../images/acciones.gif" /></a>
						<ul class="dropdown-menu" style="margin:0px;padding: 0px;line-height: 10px;top: 20px;">
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["INSERTAR"] == 1))) { ?><li id="mnColumnaNuevoRegistro"><a name="nuevo" href="#" onclick="VerificarOpcion2(this);return document.ValorRetorno;" />Nuevo</a></li><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["VER"] == 1))) { ?><li id="mnColumnaVerRegistro"><a name="ver" href="#" onclick="VerificarOpcion2(this);return document.ValorRetorno;" />Ver</a></li><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["MODIFICAR"] == 1))) { ?><li id="mnColumnaEditarRegistro"><a name="editar" href="#" onclick="VerificarOpcion2(this);return document.ValorRetorno;" />Editar</a></li><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["ELIMINAR"] == 1))) { ?><li id="mnColumnaEliminarRegistro"><a name="eliminar" href="#" onclick="VerificarOpcion2(this);return document.ValorRetorno;" />Eliminar</a></li><?php } ?>
						</ul>
					</li>
				</ul>
			</th>
			<?php
				//	muestro los titulos de los campos
				foreach($campos as $campo=>$titulo) {
					//	determino el campo y tipo de ordenamiento
					$estilo = "";
					$img = "";
					$o = "asc";
					if(isset($_GET['c']) and ($_GET['c'] == $campo)) {
						if($_GET['o'] == "asc") { $o = "desc";
						} else {
							if($_GET['o'] == "desc") { $o = "asc"; }
						}
						$estilo = $o;
					}
					$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
					if(isset($_GET['accion'])) {
						$url .= "&accion=".$_GET['accion'];
					}
					$url .= "&c=".$campo."&o=".$o;
					//	verifico si se busco algun texto
					if(isset($_GET['texto'])) {
						$url .= "&texto=".$_GET['texto'];					
					}
					//	verifico si se ha seleccionado cuantos registros mostrar por pagina
					if(isset($_GET['txp'])) {
						$url .= "&txp=".$_GET['txp'];					
					}
					$url .= "&pag=".$_GET['pag'];
					?>
			<th class="<?php echo($estilo); ?>"><a href="<?php echo($url); ?>"><?php echo($titulo); ?></a></th>
					<?php
				}
			?>
		</tr>
		</thead>
		<tbody>					
				<?php
					if($cantidad_filas == -1) {
						$cantidad_filas = $record->RecordCount();
					}
					if(!isset($_GET['pag'])) { $_GET['pag'] = 1; }
					$reg1 = ($_GET['pag']-1) * $cantidad_filas;
					$enlace = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
					//	verifico si se ordeno por algun campo
					if(isset($_GET['c']) and isset($_GET['o'])) {
						$enlace .= "&c=".$_GET['c']."&o=".$_GET['o'];
					}
					//	verifico si se busco algun texto
					if(isset($_GET['texto'])) {
						$enlace .= "&texto=".$_GET['texto'];
					}
					//	verifico si se ha seleccionado cuantos registros mostrar por pagina
					if(isset($_GET['txp'])) {
						$enlace .= "&txp=".$_GET['txp'];					
					}
					$enlace .= "&pag=";
					$total = $record->RecordCount();
				//	coloco los contenidos
				for ($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
					if($i >= $reg1) {
						$msj = "";
						$j = 0;
						$registro = "";
						if(count($claves) > 1){
							//	armo la lista de campos de clave primaria
							foreach($claves as $key=>$val) {
								$j++;
								$registro .= "registro[]=".$record->fields[$val];
								if($j != count($claves)) {
									$registro .= "&";
								}
							}
							if($datos_acceso and ($datos_acceso["VER"] == 1)) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=ver&'.$registro)."'>Ver</a></li>"; }
							if($datos_acceso and ($datos_acceso["MODIFICAR"] == 1)) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=editar&'.$registro)."'>Editar</a></li>"; }
							if($datos_acceso and ($datos_acceso["ELIMINAR"] == 1)) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=eliminar&'.$registro)."'>Eliminar</a></li>"; }
						} else {
							//	paso la clave primaria
							foreach($claves as $key=>$val) {
								$registro .= $record->fields[$val];
							}
							if($datos_acceso["VER"] == 1) { $msj .= "<li id='mnFilaVerRegistro'><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=ver&registro[]='.$registro)."'>Ver</a></li>"; }
							if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li id='mnFilaEditarRegistro'><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=editar&registro[]='.$registro)."'>Editar</a></li>"; }
							if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li id='mnFilaEliminarRegistro'><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=eliminar&registro[]='.$registro)."'>Eliminar</a></li>"; }

							if($masopcionesporregistro) {
								$msj .= "<li class='divider'></li>";
								foreach($masopcionesporregistro as $campo=>$titulo) {
									if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro)."'>".$titulo."</a></li>"; }
								}
							}
						}
						?>					
		<tr class="unChecked" style="background: <?php echo($fondo_filas_par); ?>;" onmouseover="this.style.background='<?php echo($fondo_filas_sobre); ?>';this.style.cursor='pointer';" onmouseout="this.style.background='<?php echo($fondo_filas_par); ?>';">
				<td><input type="checkbox" name="registro[]" value="<?php echo($registro); ?>" onclick="javascript:SeleccionarFila(this);" /></td>
				<?php if($acciones == true) { ?>
				<td>
					<ul class="tabs" data-tabs="tabs" style="margin:0px;padding: 0px;border: none;line-height: 10px;">
						<li class="dropdown" data-dropdown="dropdown" style="margin:0px;padding: 0px;border: none;line-height: 10px;">
							<a href="#" class="dropdown-toggle" style="margin:0px;padding: 0px;border: none;line-height: 10px;"><img src="../images/acciones.gif" /></a>
							<ul class="dropdown-menu" style="margin:0px;padding: 0px;line-height: 10px;top: 20px;">
								<?php echo($msj); ?>
							</ul>
						</li>
					</ul>
				</td>
				<?php } ?>
						<?php
							$j = 0;
							//	muestro el contenido de los campos
							foreach($campos as $campo=>$titulo) {
								//	obtengo el tipo de dato de la columna
								$fld = $record->FetchField($j);
								$type = $record->MetaType($record->FetchField($j));
								$largo = $fld->max_length;
								$j++;
								if(($type == "D") or ($type == "T")) {
									?>
			<td><?php echo($record->UserDate($record->fields[$campo],"d/m/Y")); ?></td>
									<?php
								} else {								
									?>
			<td <?php if(($type == "I") or ($type == "N") or ($type == "R")) {  if($campo == "ACTIVO") { echo("style='text-align: left;'"); } else { echo("style='text-align: right;'"); } } ?>>
			<?php 
			if($largo == 1) {
				if (($campo == "ACTIVO") and ($datos_acceso["MODIFICAR"] == 1)) { 
					if($record->fields[$campo] == "1") {
						echo("<input type=\"checkbox\" onclick=\"javascript:window.location.href='?menu=".$_GET['menu']."&accion=cambiarestado&registro[]=".$registro."';\" checked=\"checked\" /> ".$estados[$record->fields[$campo]]);
					} else {
						echo("<input type=\"checkbox\" onclick=\"javascript:window.location.href='?menu=".$_GET['menu']."&accion=cambiarestado&registro[]=".$registro."';\" /> ".$estados[$record->fields[$campo]]);
					}
				} else {
					echo($estados[$record->fields[$campo]]); 
				}
			} else { 
				echo(stripslashes($record->fields[$campo])); 
			} 
			?>
			</td>
									<?php
								}
							}
						?>
		</tr>
						<?php
					}
					$record->MoveNext(); 
				}
				//	completo con filas en blanco
				if(($total - $reg1) < $cantidad_filas) {
					for($i=($total - $reg1); $i<$cantidad_filas; $i++) {
						?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
						<?php
						foreach($campos as $campo=>$titulo) {
							?>
			<td>&nbsp;</td>
							<?php
						}
					?>
		</tr>
					<?php
					}
				}
				?>
		</tbody>					
		</table>
	</form>
	<footer>
		<div class="container">
			<div class="row-fluid">
				<div class="span12">
					<div class="btn-toolbar">
						<p class="float-left">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
						<?php if(($paginador == true) and ($total >= $cantidad_filas)) { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } else { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } ?>
					</div>
				</div>		
			</div>
		</div>
	</footer>
		<?php
		$record->Close();  
    }	
}

/*
	showTable2
		Funcion que muestre los datos de una consulta, permitiendo paginar los datos, ordenarlos y elegir acciones sobre los datos.
	Parametros:
		- $conexion: enlace a la base de datos.
		- $consulta: consulta SQL para obtener los datos.
		- $campos: representa un array con el nombre de los campos ha obtener de la tabla, y sus titulos de columnas a mostrar
			array("campo1"=>"titulo1","campo2"=>"titulo2")
		- $claves: representa un array con los campos claves de la tabla. 
			array(0=>"campoclave1",1=>"campoclave2")
			El ordenamiento por defecto se realiza sobre el primer campo clave.
			Es utilizado este parametro para ubicar al registro cuando se desee realizar una accion.
		- $cantidad_filas: indica la cantidad de filas a mostrar por pagina. Si se pasa el valor -1 se muestran todos los datos.
		- $paginador: determina si muestra la barra de navegacion entre paginas.
		- $acciones: determina si muestras las acciones NUEVO, EDITAR y ELIMINAR registros.
		- $ancho y $alto: determina el ancho de la ventana de acciones
		- $datos_acceso: posee los privilegios de acceso sobre la tabla o consulta
		- $id_tablamaestra: indica el ID de la tabla maestra relacionada
*/

function showTable2($conexion,$query,$campos,$claves,$cantidad_filas = 10,$paginador = true,$acciones = true,$datos_acceso=null,$id_tablamaestra = null,$masopciones = null,$masopcionesporregistro = null) {
	if(isset($_GET['txp'])) {
		$cantidad_filas = $_GET['txp'];
		$_SESSION['usuario_rxp'] = $_GET['txp'];
	}
	global $estados;
	global $sitio_url;
	global $cantidad_decimales;
	global $path_imagenes;
	global $colores_categoriasProductos;
	global $colores_estadosVentas;
	global $colores_pagadoVentas;
	global $colores_estadosServiciosTecnicos;
	global $colores_estadosPedidos;

	$queryCategorias = "";
	$queryGeneros = "";

	if(isset($_GET['c']) && isset($_GET['o'])) {
		//	busca y elimina el orden anterior
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));
		if($sinORDERBY == "") {
			$query .= $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		} else {
			$query = $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		}
	}
	if(($_GET['menu'] == "productos")) {
		//	busca y elimina el orden anterior, para poder agrupar
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));
		if($sinORDERBY == "") {
			$queryCategorias .= $sinORDERBY." GROUP BY categoria ORDER BY categoria ASC";
		} else {
			$queryCategorias = $sinORDERBY." GROUP BY categoria ORDER BY categoria ASC";
		}
		//	busca y elimina el orden anterior, para poder agrupar
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));
		if($sinORDERBY == "") {
			$queryGeneros .= $sinORDERBY." GROUP BY genero ORDER BY genero ASC";
		} else {
			$queryGeneros = $sinORDERBY." GROUP BY genero ORDER BY genero ASC";
		}

		$queryFiltro = "";
		if(isset($_GET['fc'])) {
			$queryFiltro .= " (categoria = '".$_GET['fc']."') ";
			//	busco WHERE y agrego el filtro de GENEROS segun la CATEGORIA filtrada
			if(strpos($queryGeneros,"WHERE") === false) {
				$antesWHERE = substr($queryGeneros,0,strpos($queryGeneros,"GROUP BY"));
				$despuesWHERE = substr($queryGeneros,strpos($queryGeneros,"GROUP BY"),strlen($queryGeneros));			
				$queryGeneros = $antesWHERE."WHERE".$queryFiltro.$despuesWHERE;
			} else {
				$antesWHERE = substr($queryGeneros,0,(strpos($queryGeneros,"WHERE")+5));
				$despuesWHERE = substr($queryGeneros,(strpos($queryGeneros,"WHERE")+5),strlen($queryGeneros));			
				$queryGeneros = $antesWHERE.$queryFiltro." and ".$despuesWHERE;
			}
		}
		if(isset($_GET['fg'])) {
			if($queryFiltro != "") { $queryFiltro .= " and "; }
			$queryFiltro .= " (genero LIKE '%".$_GET['fg']."%') ";
		}
		if($queryFiltro != "") {
			//	busco WHERE y agrego el filtro
			if(strpos($query,"WHERE") === false) {
				$antesWHERE = substr($query,0,strpos($query,"ORDER BY"));
				$despuesWHERE = substr($query,strpos($query,"ORDER BY"),strlen($query));			
				$query = $antesWHERE."WHERE".$queryFiltro.$despuesWHERE;
			} else {
				$antesWHERE = substr($query,0,(strpos($query,"WHERE")+5));
				$despuesWHERE = substr($query,(strpos($query,"WHERE")+5),strlen($query));			
				$query = $antesWHERE.$queryFiltro." and ".$despuesWHERE;
			}
		}
	}
	$record = $conexion->Execute($query);
	if (!$record) {   
        print $conexion->ErrorMsg(); 
	} else {
		if (!isset($_GET['pag'])) $_GET['pag'] = 1; // Por defecto, pagina 1
		$maxpags = 20;
		if(isset($id_tablamaestra)) {
			?>
		<input type="hidden" name="id" id="id" value="<?php echo($id_tablamaestra); ?>" />
			<?php			
		}
		?>
		<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
		<div class="btn-toolbar">
			<div class="btn-group">
				<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["INSERTAR"] == 1))) { ?><button id="btNuevoRegistro" class="btn btn-primary" title="Nuevo (Alt+N)">Nuevo</button><?php } ?>
				<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["VER"] == 1))) { ?><button id="btVerRegistro" class="btn btn-primary" title="Ver (Alt+V)" disabled="disabled">Ver</button><?php } ?>
				<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["MODIFICAR"] == 1))) { ?><button id="btEditarRegistro" class="btn btn-primary" title="Editar (Alt+M)" disabled="disabled">Editar</button><?php } ?>
				<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["ELIMINAR"] == 1))) { ?><button id="btEliminarRegistro" class="btn btn-danger" title="Eliminar (Alt+D)" disabled="disabled" data-user="<?php echo(!empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1); ?>">Eliminar</button><?php } ?>
			</div>
			<?php 
			//muestro al opcion de RECUPERAR, si tiene permitido ELIMINAR el USUARIO
			if(($acciones == true) and ($datos_acceso and ($datos_acceso["ELIMINAR"] == 1)) and (in_array($_GET['menu'],['compras','ventas','servicios','pedidos','productos']))) { 
				?>
			<div class="btn-group">
				<button id="btRecuperarRegistro" class="btn" value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=recuperar"); ?>" onclick="AbrirURL(this);"><i class="icon-trash"></i> Recuperar</button>
			</div>
				<?php
			}
			
			if(isset($_GET['anio'])){
				?>
				<div class="btn-group">
					<button class="btn dropdown-toggle" data-toggle="dropdown" title="Filtrar por A&ntilde;o"><?php if($_GET['anio'] == ""){ echo("Todos los a&ntilde;os"); } else { echo($_GET['anio']); } ?> <span class="caret"></span></button>
					<ul class="dropdown-menu">
							<?php
							$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
							if(isset($_GET['tipo'])) { $url .= "&tipo=".$_GET['tipo']; }
							if(isset($_GET['id'])) { $url .= "&id=".$_GET['id']; }
							if(isset($_GET['registro'])) { $url .= "&registro[]=".$_GET['registro'][0]; }
							if(isset($_GET['vienede'])) { $url .= "&vienede=".$_GET['vienede']; }
							?>
						<li><a href="<?php echo($url); ?>">Todos los a&ntilde;os</a></li>
						<li><a href="<?php echo($url."&anio=".date("Y")); ?>"><?php echo(date("Y")); ?></a></li>
							<?php
							switch($_GET['menu']) {
								case "inventarios":
									$anios = $conexion->Query("SELECT year(fecha_realizacion) as ANIO FROM inventarios WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (year(fecha_realizacion) < year(now())) GROUP BY year(fecha_realizacion) ORDER BY year(fecha_realizacion) DESC");
									break;
								case "cotizaciones":
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM cotizaciones WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (year(fecha_alta) < year(now())) GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
								case "traspasos":
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM traspasos WHERE (id_sucursalorigen = '{$_SESSION['usuario_sucursal']}') and (year(fecha_alta) < year(now())) GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
								case "egresos":
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM egresos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (year(fecha_alta) < year(now())) GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
								case "pedidos":
								case "servicios":
								case "ventas":
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM ventas WHERE (tipo_venta = '{$_GET['menu']}') and (ventas.id_sucursal = '{$_SESSION['usuario_sucursal']}') and (eliminado = 0) and ( (year(fecha_alta) < year(now())) or (year(fecha_alta) > year(now())) ) GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
								case "consultas":
								case "comentarios":
								case "novedades":
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM mensajes WHERE (year(fecha_alta) < year(now())) and (tipo_mensaje = '".ucfirst($_GET['menu'])."') GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
								case "accesos":
									$anios = $conexion->Query("SELECT year(fecha) as ANIO FROM contador WHERE (year(fecha) < year(now())) GROUP BY year(fecha) ORDER BY year(fecha) DESC");
									break;
								default:	//	Contenidos Web
									$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM contenidos WHERE (tipo_contenido = '".$_GET['menu']."') and (year(fecha_alta) < year(now())) GROUP BY year(fecha_alta) ORDER BY year(fecha_alta) DESC");
									break;
							}	
							while(!$anios->EOF) {
								?>
						<li><a href="<?php echo($url."&anio=".$anios->fields["ANIO"]); ?>"><?php echo($anios->fields["ANIO"]); ?></a></li>
								<?php
								$anios->MoveNext();
							}
							?>
					</ul>
				</div>
				<?php
			}
			
			if(isset($_GET['dia'])){
				?>
				<?php
			}

			if($_GET['menu'] == "contador") {
				?>
			<div class="btn-group">
				<button class="btn dropdown-toggle" data-toggle="dropdown" title="Periodo de Visitas"><?php echo($_GET['anio']); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php
						$anios = $conexion->Query("SELECT year(fecha) as ANIO FROM contador GROUP BY year(fecha) ORDER BY year(fecha) DESC");
						while(!$anios->EOF) {
							$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&anio=".$anios->fields["ANIO"];
							?>
					<li><a href="<?php echo($url); ?>"><?php echo($anios->fields["ANIO"]); ?></a></li>
							<?php
							$anios->MoveNext();
						}
					?>
				</ul>
			</div>
				<?php
			}

			if($_GET['menu'] == "inscripciones") {
				?>
			<div class="btn-group">
				<button class="btn dropdown-toggle" data-toggle="dropdown" title="Periodo de Inscripciones"><?php echo($_GET['anio']); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php
						$anios = $conexion->Query("SELECT year(fecha_alta) as ANIO FROM inscripciones GROUP BY year(fecha_alta) ORDER BY year(fecha_alta)");
						while(!$anios->EOF) {
							$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&anio=".$anios->fields["ANIO"];
							?>
					<li><a href="<?php echo($url); ?>"><?php echo($anios->fields["ANIO"]); ?></a></li>
							<?php
							$anios->MoveNext();
						}
					?>
				</ul>
			</div>
				<?php
				if(($datos_acceso['MODIFICAR'] == '1') or ($_SESSION['usuario_nivel'] == '0')) { 
					$script_excel = "../scripts/xml/generar_xls.php";
					?>
			<div class="btn-group">
				<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar Inscripciones">Exportar <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><a href="<?php echo($script_excel."?menu=todo&anio=".$_GET['anio']); ?>">Todo</a></li>
					<li class="divider"></li>
						<?php
						$eventos = $conexion->Query("SELECT EVENTO FROM inscripciones WHERE (year(fecha_alta) = ".$_GET['anio'].") GROUP BY evento ORDER BY evento");
						while(!$eventos->EOF) {
							$url = $script_excel."?menu=".$eventos->fields["EVENTO"]."&anio=".$_GET['anio'];
							?>
					<li><a href="<?php echo($url); ?>"><?php echo($eventos->fields["EVENTO"]); ?></a></li>
							<?php
							$eventos->MoveNext();
						}
						?>
				</ul>
			</div>
					<?php
				}
			}

			if($masopciones) {
				?>
			<div class="btn-group">
				<button class="btn dropdown-toggle" data-toggle="dropdown" <?php if($datos_acceso["INSERTAR"] == 0) { echo("disabled='disabled'");	} ?>>M&aacute;s opciones <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php
						foreach($masopciones as $campo=>$titulo) {
							if(isset($_GET['vienede'])) {
								?>
					<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$campo."&vienede=".$_GET['vienede']); ?>"><?php echo($titulo); ?></a></li>
								<?php
							} else {
								if($campo == "aumentarStock") {
									if($datos_acceso["MODIFICAR"] == 1) {
										?>
					<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$campo); ?>"><?php echo($titulo); ?></a></li>
										<?php
									}
								} else {
									?>
					<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$campo); ?>"><?php echo($titulo); ?></a></li>
									<?php
								}
							 }
						}
					?>
				</ul>
			</div>
				<?php
			}
			?>				

			<form method="get" action="<?php echo($_SERVER['PHP_SELF']) ?>" style="margin: 0px;" class="float-right">
				<input type="hidden" name="menu" value="<?php echo($_GET['menu']); ?>" />
				<?php if(isset($id_tablamaestra)) { ?>
				<input type="hidden" name="registro[]" value="<?php echo($id_tablamaestra); ?>" />
				<?php } ?>
				<?php if(isset($_GET['anio'])) { ?>
				<input type="hidden" name="anio" value="<?php echo($_GET['anio']); ?>" />
				<?php } ?>
				<?php if(isset($_GET['fc'])) { ?>
				<input type="hidden" name="fc" value="<?php echo($_GET['fc']); ?>" />
				<?php } ?>
				<?php if(isset($_GET['fd']) && isset($_GET['fh'])) { ?>
				<div class="input-append" style="margin-bottom: 0px;margin-right: 2px;">
					<input type="date" name="fd" value="<?php echo($_GET['fd']); ?>" class="input-small" title="Fecha desde" size="12" maxlength="10" />
					<input type="date" name="fh" value="<?php echo($_GET['fh']); ?>" min="<?php echo($_GET['fd']); ?>" class="input-small" title="Fecha hasta" size="12" maxlength="10" />
				</div>
				<?php } ?>
				<div class="input-append" style="margin-bottom: 0px;">					
					<input type="text" name="texto" placeholder="buscar" value="<?php if(isset($_GET['texto'])) { echo($_GET['texto']); } ?>" />
					<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
					<button type="button" id="btEliminarBusqueda" class="btn btn-primary" <?php if(!isset($_GET['texto'])) { echo("disabled=\"disabled\""); } ?>><i class="icon-remove icon-white"></i></button>  
				</div>
			</form>
		</div>

		<table id="listado" class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th style="width:15px;vertical-align: top;"><input type="checkbox" id="CheckTodo" name="CheckTodo" /></th>
					<th style="width: 42px;">&nbsp;</th>
					<?php
						//	muestro los titulos de los campos
						foreach($campos as $campo=>$titulo) {
							//	determino el campo y tipo de ordenamiento
							$estilo = "";
							$img = "";
							$o = "asc";
							if(isset($_GET['c']) and ($_GET['c'] == $campo)) {
								if($_GET['o'] == "asc") { $o = "desc";
								} else {
									if($_GET['o'] == "desc") { $o = "asc"; }
								}
								$estilo = $o;
							}
							$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
							if(isset($_GET['accion'])) { $url .= "&accion=".$_GET['accion']; }
							if($id_tablamaestra){ $url .= "&registro[]=".$id_tablamaestra; }
							$url .= "&c=".$campo."&o=".$o;
							//	verifico si se busco algun texto
							if(isset($_GET['texto'])) { $url .= "&texto=".$_GET['texto']; }
							if(isset($_GET['fc'])) { $url .= "&fc=".$_GET['fc']; }
							if(isset($_GET['fg'])) { $url .= "&fg=".$_GET['fg']; }
							//	verifico si se ha seleccionado cuantos registros mostrar por pagina
							if(isset($_GET['txp'])) { $url .= "&txp=".$_GET['txp']; }
							$url .= "&pag=".$_GET['pag'];
							?>
					<th class="<?php echo($estilo); ?>">
						<?php
							if(($_GET['menu'] == "productos") and (($campo == "CATEGORIA") or ($campo == "GENERO"))) {
								if($campo == "CATEGORIA") {						
									$url2 = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
									if(isset($_GET['texto'])) { $url2 .= "&texto=".$_GET['texto']; }
									if(isset($_GET['fg'])) { $url2 .= "&fg=".$_GET['fg']; }
									?>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="<?php echo($url); ?>" class="btn btn-mini"><?php echo($titulo); ?></a>
								<?php
								$registros = $conexion->Execute($queryCategorias);
								if($registros) {
									?>
								<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="<?php echo($url2); ?>">Eliminar filtro</a></li>
									<li class="divider"></li>
									<?php
									while(!$registros->EOF) {
										if(isset($_GET['fc']) and (stripslashes($registros->fields["CATEGORIA"]) == $_GET['fc'])) {
											?>
									<li><a href="<?php echo($url2."&fc=".stripslashes($registros->fields["CATEGORIA"])); ?>"><strong><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></strong></a></li>
											<?php									
										} else {
											?>
									<li><a href="<?php echo($url2."&fc=".stripslashes($registros->fields["CATEGORIA"])); ?>"><?php echo(stripslashes($registros->fields["CATEGORIA"])); ?></a></li>
											<?php
										}
										$registros->MoveNext();
									}
									?>
								</ul>					
									<?php
								}
								?>
							</div>
						</div>
									<?php
								}
								if($campo == "GENERO") {						
									$url2 = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
									if(isset($_GET['texto'])) { $url2 .= "&texto=".$_GET['texto']; }
									if(isset($_GET['fc'])) { $url2 .= "&fc=".$_GET['fc']; }
									?>
						<div class="btn-toolbar">
							<div class="btn-group">
								<a href="<?php echo($url); ?>" class="btn btn-mini"><?php echo($titulo); ?></a>
								<?php
								$listaGeneros = array();
								$registros = $conexion->Execute($queryGeneros);
								if($registros) {
									while(!$registros->EOF) {
										if(($registros->fields["GENERO"] != null) and ($registros->fields["GENERO"] != "")) {								
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
										}
										$registros->MoveNext();
									}
									sort($listaGeneros);
									?>
								<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="<?php echo($url2); ?>">Eliminar filtro</a></li>
									<li class="divider"></li>
									<?php
									foreach($listaGeneros as $key=>$val) {
										if(isset($_GET['fg']) and ($val == $_GET['fg'])) {
											?>
									<li><a href="<?php echo($url2."&fg=".$val); ?>"><strong><?php echo($val); ?></strong></a></li>
											<?php
										} else {
											?>
									<li><a href="<?php echo($url2."&fg=".$val); ?>"><?php echo($val); ?></a></li>
											<?php
										}
									}
									?>
								</ul>					
									<?php
								}
								?>
							</div>
						</div>
									<?php
								}
							} else {
								?>
						<a href="<?php echo($url); ?>"><?php echo($titulo); ?></a>
								<?php
							}
						?>
					</th>
							<?php
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					if($cantidad_filas == -1) {
						$cantidad_filas = $record->RecordCount();
					}
					if(!isset($_GET['pag'])) { $_GET['pag'] = 1; }
					$reg1 = ($_GET['pag']-1) * $cantidad_filas;
					$enlace = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
					if(isset($_GET['registro'])) { $enlace .= "&registro[]=".$_GET['registro'][0]; }
					//	verifico si se ordeno por algun campo
					if(isset($_GET['c']) and isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
					//	verifico si se busco algun texto
					if(isset($_GET['texto'])) { $enlace .= "&texto=".$_GET['texto']; }
					if(isset($_GET['fc'])) { $enlace .= "&fc=".$_GET['fc']; }
					if(isset($_GET['fg'])) { $enlace .= "&fg=".$_GET['fg']; }
					//	verifico si se ha seleccionado cuantos registros mostrar por pagina
					if(isset($_GET['txp'])) { $enlace .= "&txp=".$_GET['txp']; }
					$enlace .= "&pag=";
					$total = $record->RecordCount();
					//	coloco los contenidos
					for ($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
						if($i >= $reg1) {
							$msj = "";
							$j = 0;
							$registro = "";
							if(count($claves) > 1){
								//	armo la lista de campos de clave primaria
								foreach($claves as $key=>$val) {
									$j++;
									$registro .= "registro[]=".$record->fields[$val];
									if($j != count($claves)) {
										$registro .= "&";
									}
								}
								if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=ver&'.$registro)."'>Ver</a></li>"; }
								if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=editar&'.$registro)."'>Editar</a></li>"; }
								if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=eliminar&'.$registro)."'>Eliminar</a></li>"; }
							} else {
								//	paso la clave primaria
								foreach($claves as $key=>$val) {
									$registro .= $record->fields[$val];
								}
								if(isset($id_tablamaestra)) {
									if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='#' id='mnFilaVerRegistro'>Ver</a></li>"; }
									if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li><a href='#' id='mnFilaEditarRegistro'>Editar</a></li>"; }
									if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li><a href='#' id='mnFilaEliminarRegistro'>Eliminar</a></li>"; }
								} else {
									if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='#' id='mnFilaVerRegistro'>Ver</a></li>"; }
									if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li ".(isset($record->fields['ID_SUCURSAL']) && $record->fields['ID_SUCURSAL'] != $_SESSION['usuario_sucursal']  && $_GET['menu'] == 'ventas' ? 'class="hidden"' : '')."><a href='#' id='mnFilaEditarRegistro'>Editar</a></li>"; }
									if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li ".(isset($record->fields['ID_SUCURSAL']) && $record->fields['ID_SUCURSAL'] != $_SESSION['usuario_sucursal'] && $_GET['menu'] == 'ventas' ? 'class="hidden"' : '')."><a href='#' id='mnFilaEliminarRegistro'>Eliminar</a></li>"; }
								}

								if($masopcionesporregistro) {
									$msj .= "<li class='divider'></li>";
									foreach($masopcionesporregistro as $campo=>$titulo) {
										switch($campo) {
											case "-":
												$msj .= "<li class='divider'></li>";
												break;
											case "cambiarclave":
											case "cambiarfoto":
											case "cambiaremail":
											case "enviaragrupo":
											case "enviarausuarios":
											case "responder":
												if(($datos_acceso["INSERTAR"] == 1) or ($datos_acceso["MODIFICAR"] == 1)) {
													$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro)."'>".$titulo."</a></li>"; 
												}
												break;
											case "previewBoletin":
												if($datos_acceso["VER"] == 1) {
													$msj .= "<li><a href=\"".$sitio_url."preview.php?idc={EMAIL}&idb=".$registro."\" class=\"iframe\" rel=\"colorbox\" title=\"Vista preliminar\">".$titulo."</a></li>";
												}
												break;
											case "imprimirCotizacion":
												if($datos_acceso["VER"] == 1) {
													$msj .= "<li><a href=\"formCotizacion.php?id=".$registro."\" target=\"_blank\" title=\"".$titulo."\">".$titulo."</a></li>";
												}
												break;
											case "imprimirServicio":
												if($datos_acceso["VER"] == 1) {
													$msj .= "<li><a href=\"formServicio.php?id=".$registro."\" target=\"_blank\" title=\"".$titulo."\">".$titulo."</a></li>";
												}
												break;
											case "imprimirVenta":
												if($datos_acceso["VER"] == 1) {
													$msj .= "<li><a href=\"formVentas.php?id=".$registro."\" target=\"_blank\" title=\"".$titulo."\">".$titulo."</a></li>";
												}
												break;
											default:
												if($datos_acceso["VER"] == 1) {
													if(isset($_GET['registro[]'])) {
														if(isset($_GET['vienede'])) {
															$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro.'&id='.$_GET['registro[]'].'&vienede='.$_GET['vienede'])."'>".$titulo."</a></li>"; 
														} else {
															$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro.'&id='.$_GET['registro[]'])."'>".$titulo."</a></li>"; 
														}
													} else {
														if(isset($_GET['vienede'])) {
															$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro.'&vienede='.$_GET['vienede'])."'>".$titulo."</a></li>"; 
														} else {
															$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro)."'>".$titulo."</a></li>"; 
														}
													}
												}
												break;
										}
									}
								}
							}
							?>
						<tr class="unChecked <?php echo( isset($record->fields['ACTIVO']) && empty($record->fields['ACTIVO']) ? 'desactived' : ''); ?>">
							<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registro); ?>" <?php echo(isset($record->fields['ID_SUCURSAL']) && $record->fields['ID_SUCURSAL'] != $_SESSION['usuario_sucursal'] && $_GET['menu'] == 'ventas' ? 'disabled' : ''); ?> /></td>
							<?php if($acciones == true) { ?>
							<td style="margin: 0;padding: 0px">
								<div class="btn-toolbar">													 
									<div class="btn-group">
										<button title="Opciones de registro" class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" border="0" /> <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<?php echo($msj); ?>
										</ul>
									</div>
								</div>
							</td>
							<?php } ?>
							<?php
								$j = 0;
								//	muestro el contenido de los campos
								foreach($campos as $campo=>$titulo) {
									//	obtengo el tipo de dato de la columna
									$type = $record->MetaType($record->FetchField($j));
									if(($type == "D") or ($type == "T")) {
										?>
							<td>
										<?php  
										if($type == "D") { 
											echo($record->UserDate($record->fields[$campo],"d/m/Y")); 
										} else { 
											if($type == "T") {
												echo($record->fields[$campo]);
											} else {
												$lista = explode(" ",$record->fields[$campo]);
												echo(formato_fecha($lista[0],3));
												if(count($lista) > 1) { 
													echo(" ".$lista[1]); 
												} 
											}
										} 
										?>
							</td>
										<?php
									} else {	
										$style_colores = "";
										if(($type == "I") or ($type == "N") or ($type == "R")) { 
											if($campo == "ACTIVO") {
												$style_colores .= "text-align: left;";
											} else { 
												$style_colores .= "text-align: right;";
											} 
										}
										if(($_GET['menu'] == "clientes") and ($campo == "CATEGORIA_USUARIO")) {
											if(strtoupper($record->fields["CATEGORIA_USUARIO"]) == "NUEVO") {
												$style_colores .= "background: #ff0000;color: #ffff00;";		
											}
								}
										if(($_GET['menu'] == "productos") and ($campo == "CODIGO")) {
											if(isset($colores_categoriasProductos)) {
												for($ic=0; $ic<count($colores_categoriasProductos); $ic++) {
													$array_datos = explode("|",$colores_categoriasProductos[$ic]);
													if(strtoupper($array_datos[0]) == strtoupper($record->fields["CATEGORIA"])) {
														$style_colores .= "background: ".$array_datos[1].";";		
													}
												}										
											}
										}
										if(($_GET['menu'] == "ventas") and ($campo == "COD_VENTA")) {
											if(isset($colores_estadosVentas)) {
												for($ic=0; $ic<count($colores_estadosVentas); $ic++) {
													$array_datos = explode("|",$colores_estadosVentas[$ic]);
													if(strtoupper($array_datos[0]) == strtoupper($record->fields["ESTADO"])) {
														$style_colores .= "background: ".$array_datos[1].";";		
													}
												}										
											}
										}
										if(($_GET['menu'] == "ventas") and ($campo == "PAGADO")) {
											if(isset($colores_pagadoVentas)) {
												for($ic=0; $ic<count($colores_pagadoVentas); $ic++) {
													$array_datos = explode("|",$colores_pagadoVentas[$ic]);
													if(strtoupper($array_datos[0]) == strtoupper($estados[$record->fields["PAGADO"]])) {
														$style_colores .= "background: ".$array_datos[1].";";		
													}
												}										
											}
										}
										if(($_GET['menu'] == "servicios") and ($campo == "DESCRIPCION")) {
											if(isset($colores_estadosServiciosTecnicos)) {
												for($ic=0; $ic<count($colores_estadosServiciosTecnicos); $ic++) {
													$array_datos = explode("|",$colores_estadosServiciosTecnicos[$ic]);
													if(strtoupper($array_datos[0]) == strtoupper($record->fields["ESTADO"])) {
														$style_colores .= "background: ".$array_datos[1].";";		
													}
												}										
											}
										}
										if(($_GET['menu'] == "pedidos") and ($campo == "DESCRIPCION")) {
											if(isset($colores_estadosPedidos)) {
												for($ic=0; $ic<count($colores_estadosPedidos); $ic++) {
													$array_datos = explode("|",$colores_estadosPedidos[$ic]);
													if(strtoupper($array_datos[0]) == strtoupper($record->fields["ESTADO"])) {
														$style_colores .= "background: ".$array_datos[1].";";		
													}
												}										
											}
										}
										?>
							<td <?php if($style_colores != "") { echo("style='".$style_colores."'"); }  ?>>
										<?php 
										if((($campo == "ACTIVO") or ($campo == "USUARIO_ACTIVO")) and ($datos_acceso["MODIFICAR"] == 1)) {						
											if($record->fields[$campo] == "1") {
												echo("<input type=\"checkbox\" name=\"estado\" checked=\"checked\" title=\"".$estados[$record->fields[$campo]]."\" data-evento='change' data-metodo='cambiarEstadoRegistro' /> ");
											} else {
												if($record->fields[$campo] == null) {
													echo("<input type=\"checkbox\" name=\"estado\" title=\"NO\" data-evento='change' data-metodo='cambiarEstadoRegistro' /> ");
												} else {
													echo("<input type=\"checkbox\" name=\"estado\" title=\"".$estados[$record->fields[$campo]]."\" data-evento='change' data-metodo='cambiarEstadoRegistro' /> ");
												}
											}
										} else {
											switch($campo) {
												case "PAGADO":
												case "SOLOAPEDIDO":
													echo($estados[$record->fields[$campo]]);
													break;
												case "ID_VENTA":
												case "COD_VENTA":
												case "ID_EGRESO":
												case "ID_COTIZACION":
												case "ID_INVENTARIO":
													$record->fields[$campo] = sprintf("%04s",$record->fields[$campo]);
													echo(stripslashes($record->fields[$campo]));
													break;
												case "COD_USUARIO":
													$record->fields[$campo] = sprintf("%04s",$record->fields[$campo]);
													echo(stripslashes($record->fields[$campo]));
													break;
												case "TOTAL":
												case "PRECIO_COSTO":
												case "PRECIO_VENTA":
												case "IMPORTE":
												case "IVA":
												case "SUBTOTAL":
												case "DESCUENTO":
												case "RECIBIDO":
												case "SALDO":
													echo(number_format($record->fields[$campo], $cantidad_decimales, ',', '.')." ".(convertirTipoMoneda() == 1 ? 'Bs' : $_SESSION['usuario_tipomoneda']));
													break;
												case "NOMBRE":
													if($_GET['menu'] == "productos") {
														if($datos_acceso["MODIFICAR"] == 1) {
															echo("<a href=\"".htmlentities('?menu='.$_GET['menu'].'&accion=editar&registro[]='.$registro)."\">".stripslashes($record->fields[$campo])."</a>");
														} else {
															if($datos_acceso["VER"] == 1) {
																echo("<a href=\"".htmlentities('?menu='.$_GET['menu'].'&accion=ver&registro[]='.$registro)."\">".stripslashes($record->fields[$campo])."</a>");
															} else {
																echo("<strong>".stripslashes($record->fields[$campo])."</strong>");
															}
														}
													} else {
														echo(stripslashes($record->fields[$campo]));
													}
													break;
												case "FOTO":
													if($record->fields[$campo] != ""){
														if($_GET['menu'] == "productos") {
															$path_url_foto = "../../".$path_imagenes."productos/".$record->fields[$campo];
															if(file_exists($path_url_foto)) {
																echo("<a href=\"".$path_url_foto."\" class=\"foto\" rel=\"colorbox\" title=\""."[".$record->fields["CODIGO"]."] ".htmlentities(stripslashes($record->fields["NOMBRE"]))."\"><i class=\"icon-picture\"></i></a>");
															}												
														}
													}													
													break;
												case "COORDENADAS":
													if($record->fields[$campo] != ""){
														$array_datos = explode("|",$record->fields[$campo]);
														//$valorLatitud = $array_datos[0];
														//$valorLongitud = $array_datos[1];

														$valorLatitud = floatval($array_datos[0]);
														$valorLongitud = floatval($array_datos[1]);

														if($_GET['menu'] == "clientes" && !empty($valorLatitud) && !empty($valorLongitud)) {
															echo("<a href='https://www.google.com/maps/search/?api=1&query=".$valorLatitud.",".$valorLongitud."' target='_blank' rel='noopener noreferrer'
															title='Mostrar ubicación en un mapa'><i
																class='icon-map-marker'></i></a>");
														}
													}													
													break;
												default:
													echo(stripslashes($record->fields[$campo]));
													break;
											}
										}
										?>
							</td>
										<?php
									}
									$j++;
								}
							?>
						</tr>
							<?php
						}
						$record->MoveNext(); 
					}
					//	completo con filas en blanco
					if(($total - $reg1) < $cantidad_filas) {
						for($i=($total - $reg1); $i<$cantidad_filas; $i++) {
							?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
							<?php
							foreach($campos as $campo=>$titulo) {
								?>
					<td>&nbsp;</td>
								<?php
							}
						?>
				</tr>
						<?php
						}
					}
				?>
			</tbody>
		</table>
		
		<footer>
			<div class="container">
				<div class="row-fluid">
					<div class="span12">
						<div class="btn-toolbar">
							<p class="float-left">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
							<?php if(($paginador == true) and ($total >= $cantidad_filas)) { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } else { echo(paginar($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags)); } ?>
						</div>
					</div>		
				</div>
			</div>
		</footer>
		<?php
		$record->Close();  
    }	
}

/*
	showTableImagenes
		Funcion que muestre los datos de una consulta cuyo origen sean imagenes, permitiendo paginar los datos, ordenarlos y elegir acciones sobre los datos.
	Parametros:
		- $conexion: enlace a la base de datos.
		- $consulta: consulta SQL para obtener los datos.
		- $campos: representa un array con el nombre de los campos ha obtener de la tabla, y sus titulos de columnas a mostrar
			array("campo1"=>"titulo1","campo2"=>"titulo2")
		- $claves: representa un array con los campos claves de la tabla. 
			array(0=>"campoclave1",1=>"campoclave2")
			El ordenamiento por defecto se realiza sobre el primer campo clave.
			Es utilizado este parametro para ubicar al registro cuando se desee realizar una accion.
		- $cantidad_filas: indica la cantidad de filas a mostrar por pagina. Si se pasa el valor -1 se muestran todos los datos.
		- $paginador: determina si muestra la barra de navegacion entre paginas.
		- $acciones: determina si muestras las acciones NUEVO, EDITAR y ELIMINAR registros.
		- $ancho y $alto: determina el ancho de la ventana de acciones
		- $datos_acceso: posee los privilegios de acceso sobre la tabla o consulta
		- $id_tablamaestra: indica el ID de la tabla maestra relacionada
*/

function showTableImagenes($conexion,$query,$campos,$claves,$cantidad_filas = 10,$paginador = true,$acciones = true,$CantidadColumnas = 4,$datos_acceso=null,$id_tablamaestra = null,$masopciones = null,$masopcionesporregistro = null) {
	if(isset($_GET['txp'])) {
		$cantidad_filas = $_GET['txp'];
	}
	if(isset($_GET['cxp'])) {
		$CantidadColumnas = $_GET['cxp'];	
	}	
	global $estados;
	global $sitio_url;
	global $fondo_filas_par;
	global $fondo_filas_impar;
	global $fondo_filas_sobre;
	if(isset($_GET['c']) and isset($_GET['o'])) {
		//	busca y elimina el orden anterior
		$sinORDERBY = substr_replace($query,"",strpos($query,"ORDER BY"),strlen($query));
		if($sinORDERBY == "") {
			$query .= $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		} else {
			$query = $sinORDERBY." ORDER BY ".$_GET['c']." ".$_GET['o'];
		}
	}
	$record = $conexion->Execute($query);
	if (!$record) {   
        print $conexion->ErrorMsg(); 
	} else {
		if (!isset($_GET['pag'])) $_GET['pag'] = 1; // Por defecto, pagina 1
		$maxpags = 20;
		if(isset($id_tablamaestra)) {
			?>
		<input type="hidden" name="id" id="id" value="<?php echo($id_tablamaestra); ?>" />
			<?php			
		}
		?>
					<div class="btn-toolbar">
						<div class="btn-group">
							<label class="radio inline" style="float: left;width: 130px;">
								<input type="checkbox" id="CheckTodo" name="CheckTodo" title="Seleccionar todo" style="display: inline-block;" /> Seleccionar todo
							</label>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["INSERTAR"] == 1))) { ?><button id="btNuevoRegistro" class="btn btn-primary">Nuevo</button><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["VER"] == 1))) { ?><button id="btVerRegistro" class="btn btn-primary" disabled="disabled">Ver</button><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["MODIFICAR"] == 1))) { ?><button id="btEditarRegistro" class="btn btn-primary" disabled="disabled">Editar</button><?php } ?>
							<?php if(($acciones == true) and ($datos_acceso and ($datos_acceso["ELIMINAR"] == 1))) { ?><button id="btEliminarRegistro" class="btn btn-primary" disabled="disabled">Eliminar</button><?php } ?>
						</div>
						<div class="btn-group">
							<?php
								if($masopciones) {
									?>
							<button class="btn dropdown-toggle" data-toggle="dropdown" <?php if($datos_acceso["INSERTAR"] == 0) { echo("disabled='disabled'");	} ?>>M&aacute;s opciones <span class="caret"></span></button>
							<ul class="dropdown-menu">
									<?php
									foreach($masopciones as $campo=>$titulo) {
										?>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$campo); ?>"><?php echo($titulo); ?></a></li>
										<?php
									}
									?>
							</ul>
									<?php
								}
							?>				
						</div>
						<form method="get" action="<?php echo($_SERVER['PHP_SELF']) ?>" style="margin: 0px;" class="float-right">
							<input type="hidden" name="menu" value="<?php echo($_GET['menu']); ?>" />
							<div class="input-append" style="margin-bottom: 0px;">
								<input type="text" name="texto" placeholder="buscar por su descripci&oacute;n" value="<?php if(isset($_GET['texto'])) { echo($_GET['texto']); } ?>" />
								<button type="submit" class="btn btn-primary">buscar</button>
								<button type="button" id="btEliminarBusqueda" class="btn btn-primary" <?php if(!isset($_GET['texto'])) { echo("disabled=\"disabled\""); } ?>><i class="icon-remove icon-white"></i></button>  
							</div>
						</form>
					</div>

		<table id="listado" class="table table-condensed">
        <tbody>					
		<?php
			if($cantidad_filas == -1) {
				$cantidad_filas = $record->RecordCount();
			}
			if(!isset($_GET['pag'])) { $_GET['pag'] = 1; }
			$reg1 = ($_GET['pag']-1) * $cantidad_filas;
			$enlace = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			//	verifico si se ordeno por algun campo
			if(isset($_GET['c']) and isset($_GET['o'])) { $enlace .= "&c=".$_GET['c']."&o=".$_GET['o']; }
			//	verifico si se busco algun texto
			if(isset($_GET['texto'])) { $enlace .= "&texto=".$_GET['texto']; }
			//	verifico si se ha seleccionado cuantos registros mostrar por pagina
			if(isset($_GET['txp'])) { $enlace .= "&txp=".$_GET['txp']; }
			$enlace .= "&pag=";
			$total = $record->RecordCount();
			//	coloco los contenidos
			$j = 1;
			for($i=0; $i<min($reg1+$cantidad_filas, $total); $i++) {
				if((($i+1)%$CantidadColumnas) == 1) { ?><tr><?php }
				if($i >= $reg1) {
					$msj = "";
					$j = 0;
					$registro = "";
					if(count($claves) > 1){
						//	armo la lista de campos de clave primaria
						foreach($claves as $key=>$val) {
							$j++;
							$registro .= "registro[]=".$record->fields[$val];
							if($j != count($claves)) {
								$registro .= "&";
							}
						}
						if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=ver&'.$registro)."'>Ver</a></li>"; }
						if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=editar&'.$registro)."'>Editar</a></li>"; }
						if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion=eliminar&'.$registro)."'>Eliminar</a></li>"; }
					} else {
						//	paso la clave primaria
						foreach($claves as $key=>$val) {
							$registro .= $record->fields[$val];
						}
						if($datos_acceso["VER"] == 1) { $msj .= "<li><a href='#' id='mnFilaVerRegistro'>Ver</a></li>"; }
						if($datos_acceso["MODIFICAR"] == 1) { $msj .= "<li><a href='#' id='mnFilaEditarRegistro'>Editar</a></li>"; }
						if(isset($id_tablamaestra)) {
							if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li><a href='#' id='mnFilaEliminarRegistro'>Eliminar</a></li>"; }
						} else {
							if($datos_acceso["ELIMINAR"] == 1) { $msj .= "<li><a href='#' id='mnFilaEliminarRegistro'>Eliminar</a></li>"; }
						}

						if($masopcionesporregistro) {
							$msj .= "<li class='divider'></li>";
							foreach($masopcionesporregistro as $campo=>$titulo) {
								if($datos_acceso["VER"] == 1) {
									if($campo == "previewBoletin") {
										$msj .= "<li><a href=\"".$sitio_url."preview.php?idc={EMAIL}&idb=".$registro."\" class=\"iframe\" rel=\"colorbox\" title=\"Vista preliminar\">".$titulo."</a></li>";
									} else {
										$msj .= "<li><a href='".htmlentities('?menu='.$_GET['menu'].'&accion='.$campo.'&registro[]='.$registro)."'>".$titulo."</a></li>"; 
									}
								}
							}
						}
					}
					?>
				<td class="unChecked" style="background: <?php echo($fondo_filas_par); ?>;" onmouseover="this.style.background='<?php echo($fondo_filas_sobre); ?>';this.style.cursor='pointer';" onmouseout="this.style.background='<?php echo($fondo_filas_par); ?>';">
					<?php
						//	muestro el contenido de los campos
						foreach($campos as $campo=>$titulo) {
							if($campo == "CATEGORIA") {
								$vTitle = stripslashes($record->fields[$campo]);
							}
							if($campo == "DESCRIPCION") {
								$vData = strip_tags(stripslashes($record->fields[$campo]));
								$vData = str_replace("\r\n","",$vData);
							}
							if($campo == "NOMBRE") {
								if($record->fields[$campo] == null){
									$nombre_completo_imagenoriginal = "../../images/fotogaleria/fotogaleria.jpg";
								} else {
									$nombre_completo_imagenoriginal = "../../images/fotogaleria/".$record->fields[$campo];
								}
								if(file_exists($nombre_completo_imagenoriginal)) {
									//	obtengo la extension del archivo
									$res = explode(".", $record->fields[$campo]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen redimensionada original
									$nombre_completo_imagensmall = "../../images/fotogaleria/".$nombre."_small.".$extension;
									/*if(file_exists($nombre_completo_imagensmall)) {
										$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
									}*/
									?>
					<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="fotogaleria" rel="colorbox" title="<?php echo("[".$vTitle."] ".$vData); ?>"><img class="recuadroFotogaleria" src="<?php echo($nombre_completo_imagenoriginal."?t=".date("His")); ?>" /></a>
					<p style="text-align: center;margin: 0 0 5px 0;"><small><?php echo($vData); ?></small></p>
									<?php
								} else {
									?>
					<div class="recuadroFotogaleria"><?php echo(stripslashes($record->fields[$campo])); ?></div>
									<?php
								}							
							}
						}
					?>
					<div class="alert" style="margin: 0px;padding: 0 5px;">
						<?php if($acciones == true) { ?>
						<div class="btn-toolbar">
							<label class="radio inline" style="float: left;padding-left: 0;padding-top: 0;width: 40px;">
								<input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registro); ?>" title="Seleccionar registro" />
							</label>
							<div class="btn-group dropup">
								<button title="Opciones de registro" class="btn dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" border="0" /> Opciones <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<?php echo($msj); ?>
								</ul>
							</div>
						</div>
						<?php } ?>
					</div>
					</td>
					<?php
				}
				if((($i+1)%$CantidadColumnas) == 0) { ?></tr><?php }
				$record->MoveNext(); 
				$j++;
			}
			if($i < ($reg1+$cantidad_filas)) {
				while((($i+1)%$CantidadColumnas) != 0) { echo("<td><div style='margin: 0 auto;width: 200px;height: 200px;border: 1px solid #ddd;padding: 5px;-webkit-border-radius: 12px 12px 0 0;-moz-border-radius: 12px 12px 0 0;border-radius: 12px 12px 0 0;'>&nbsp;</div></td>"); $i++; }
				echo("<td><div style='margin: 0 auto;width: 200px;height: 200px;border: 1px solid #ddd;padding: 5px;-webkit-border-radius: 12px 12px 0 0;-moz-border-radius: 12px 12px 0 0;border-radius: 12px 12px 0 0;'>&nbsp;</div></td></tr>");
				//	completa las filas y columnas faltantes
				/*$i++;
				while($i < ($reg1+$cantidad_filas)) {
					if((($i+1)%$CantidadColumnas) == 1) { ?><tr><?php }
					?>
				<td><div style='margin: 0 auto;width: 200px;height: 200px;border: 1px solid #ddd;padding: 5px;-webkit-border-radius: 12px 12px 0 0;-moz-border-radius: 12px 12px 0 0;border-radius: 12px 12px 0 0;'>&nbsp;</div></td>
					<?php
					if((($i+1)%$CantidadColumnas) == 0) { ?></tr><?php }
					$i++;	
				}*/
			}
		?>
        </tbody>					
		</table>
					<footer>
						<div class="container">
							<div class="row-fluid">
								<div class="span12">
									<div class="btn-toolbar">
										<p class="float-left">Registros <strong><?php echo(($reg1+1)); ?></strong> al <strong><?php echo(($reg1+$cantidad_filas)); ?></strong> de <strong><?php echo($total); ?></strong></p>
										<?php if($paginador == true) { echo(paginarImagenes($_GET['pag'],$total, $cantidad_filas, $enlace, $maxpags,$CantidadColumnas)); } ?>
									</div>
								</div>		
							</div>
						</div>
					</footer>
		<?php
		$record->Close();  
    }	
}

function paginar($actual, $total, $por_pagina, $enlace, $maxpags=0) {
	if(($total > 0) and ($por_pagina > 0)){
		$total_paginas = ceil($total/$por_pagina);
	} else {
		$total_paginas = 0;
	}
	$primero = 1;
	$anterior = $actual - 1;
	$posterior = $actual + 1;
	$ultimo = $total_paginas;
	$minimo = $maxpags ? max(1, $actual-ceil($maxpags/2)): 1;
	$maximo = $maxpags ? min($total_paginas, $actual+floor($maxpags/2)): $total_paginas;
	$texto = "";

	//	permite seleccionar la cantidad de registros por pagina
	$texto .= "<div class=\"btn-group float-right\">";
	$texto .= "		<select class=\"btn\" onchange=\"AbrirURL(this);\" style=\"width: auto;\" title=\"Registros por p&aacute;gina\">";
	for($i=1; $i<5; $i++) {
		$vI = $i*10;
		if($vI == $por_pagina) {
			$texto .= "		<option value=\"$enlace$actual&txp=".$vI."\" selected=\"selected\">".$vI."</option>";
		} else {
			$texto .= "		<option value=\"$enlace$actual&txp=".$vI."\">".$vI."</option>"; 
		}
	}
	if($total > 25) {		
		$i=50;
		for($j=0; $j<($total/50); $j++) {			
			if($por_pagina == $i) {
				$texto .= "		<option value=\"$enlace$actual&txp=$i\" selected>".$i."</option>";
			} else {
				$texto .= "		<option value=\"$enlace$actual&txp=$i\">".$i."</option>";
			}
			$i = $i+50;
		}
	}
	$texto .= "		</select>";
	$texto .= "</div>";

	$texto .= "<div class=\"btn-group float-right\">";

	//	permite desplazarse por los registros anteriores
	if($anterior>0) {
		$texto .= "<a href=\"$enlace$primero\" class=\"btn\" title=\"Ir a la primer p&aacute;gina\"><img src=\"../images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\"></a> ";	
	} else {
		$texto .= "<a class=\"btn disabled\"><img src=\"../images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\"></a>"; 		
	}
	if ($actual>1) {
		$texto .= "<a href=\"$enlace$anterior\" class=\"btn\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"../images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\"></a> "; 
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"../images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\"></a>";
	}
	$actual = sprintf("%02s",$actual); 
	$total_paginas = sprintf("%02s",$total_paginas); 

	//	permite seleccionar la pagina
	$texto .= "		<select class=\"btn\" onchange=\"AbrirURL(this);\" style=\"width: auto;\" title=\"Ir a la p&aacute;gina numero\">";
	for($i=1; $i<$total_paginas+1; $i++) {
		if($actual == $i) {
			$texto .= "		<option value=\"$enlace$i\" selected=\"selected\">".$i."</option>";
		} else {
			$texto .= "		<option value=\"$enlace$i\">".$i."</option>";
		}
	}
	$texto .= "		</select>";

	//	permite desplazarse por los registros posteriores
	if ($actual<$total_paginas) {
		$texto .= " <a href=\"$enlace$posterior\" class=\"btn\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"../images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\"></a>";
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"../images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\"></a>";		
	}
	if($posterior<=$total_paginas) {
		$texto .= "<a href=\"$enlace$ultimo\" class=\"btn\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"../images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\"></a> "; 	
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"../images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\"></a>"; 		
	}
	$texto .= "</div>";

	return $texto;
}

function paginadorSimple($actual, $total, $por_pagina, $enlace, $maxpags=0) {
	if(($total > 0) and ($por_pagina > 0)){
		$total_paginas = ceil($total/$por_pagina);
	} else {
		$total_paginas = 0;
	}
	$primero = 1;
	$anterior = $actual - 1;
	$posterior = $actual + 1;
	$ultimo = $total_paginas;
	$minimo = $maxpags ? max(1, $actual-ceil($maxpags/2)): 1;
	$maximo = $maxpags ? min($total_paginas, $actual+floor($maxpags/2)): $total_paginas;
	$texto = "";

	$texto .= "<div class=\"btn-group float-right\">";

	//	permite desplazarse por los registros anteriores
	if($anterior>0) {
		$texto .= "<a href=\"$enlace$primero\" class=\"btn\" title=\"Ir a la primer p&aacute;gina\"><img src=\"xpanel/images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a> ";	
	} else {
		$texto .= "<a class=\"btn disabled\"><img src=\"xpanel/images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a>"; 		
	}
	if ($actual>1) {
		$texto .= "<a href=\"$enlace$anterior\" class=\"btn\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"xpanel/images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a> "; 
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"xpanel/images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a>";
	}
	$actual = sprintf("%02s",$actual); 
	$total_paginas = sprintf("%02s",$total_paginas); 

	//	permite seleccionar la pagina
	for($i=1; $i<$total_paginas+1; $i++) {
		if($actual == $i) {
			$texto .= "<a href=\"$enlace$i\" class=\"btn disabled\" title=\"P&aacute;gina $i\">".$i."</a> ";
		} else {
			$texto .= "<a href=\"$enlace$i\" class=\"btn\" title=\"P&aacute;gina $i\">".$i."</a> ";
		}
	}

	//	permite desplazarse por los registros posteriores
	if ($actual<$total_paginas) {
		$texto .= " <a href=\"$enlace$posterior\" class=\"btn\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"xpanel/images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a>";
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"xpanel/images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a>";		
	}
	if($posterior<=$total_paginas) {
		$texto .= "<a href=\"$enlace$ultimo\" class=\"btn\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"xpanel/images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a> "; 	
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"xpanel/images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\" style=\"border-radius: none;padding: 0;\" /></a>"; 		
	}
	$texto .= "</div>";

	return $texto;
}

function paginarImagenes($actual, $total, $por_pagina, $enlace, $maxpags=0,$por_columna=0) {
	if(($total > 0) and ($por_pagina > 0)){
		$total_paginas = ceil($total/$por_pagina);
	} else {
		$total_paginas = 0;
	}
	$primero = 1;
	$anterior = $actual - 1;
	$posterior = $actual + 1;
	$ultimo = $total_paginas;
	$minimo = $maxpags ? max(1, $actual-ceil($maxpags/2)): 1;
	$maximo = $maxpags ? min($total_paginas, $actual+floor($maxpags/2)): $total_paginas;
	$texto = "";

	//	permite seleccionar la cantidad de columnas por pagina
	$texto .= "<div class=\"btn-group float-right\">";
	$texto .= "		<select class=\"btn\" onchange=\"AbrirURL(this);\" style=\"width: auto;\" title=\"Columnas por p&aacute;gina\">";
	for($i=1; $i<=8; $i++) {
		if($por_columna == $i) {
			$texto .= "		<option value=\"$enlace$actual&cxp=$i&txp=$por_pagina\" selected=\"selected\">".$i."</option>";		
		} else {
			$texto .= "		<option value=\"$enlace$actual&cxp=$i&txp=$por_pagina\">".$i."</option>";		
		}
	}
	$texto .= "		</select>";
	$texto .= "</div>";

	//	permite seleccionar la cantidad de registros por pagina
	$texto .= "<div class=\"btn-group float-right\">";
	$texto .= "		<select class=\"btn\" onchange=\"AbrirURL(this);\" style=\"width: auto;\" title=\"Registros por p&aacute;gina\">";
	if($por_pagina == 12) { $texto .= "	<option value=\"$enlace$actual&cxp=$por_columna&txp=12\" selected=\"selected\">12</option>";
	} else { $texto .= "	<option value=\"$enlace$actual&cxp=$por_columna&txp=12\">12</option>"; }
	if($por_pagina == 25) { $texto .= "	<option value=\"$enlace$actual&cxp=$por_columna&txp=25\" selected=\"selected\">25</option>";
	} else { $texto .= "	<option value=\"$enlace$actual&cxp=$por_columna&txp=25\">25</option>"; }
	if($total > 25) {		
		$i=50;
		for($j=0; $j<($total/50); $j++) {			
			if($por_pagina == $i) {
				$texto .= "		<option value=\"$enlace$actual&cxp=$por_columna&txp=$i\" selected=\"selected\">".$i."</option>";
			} else {
				$texto .= "		<option value=\"$enlace$actual&cxp=$por_columna&txp=$i\">".$i."</option>";
			}
			$i = $i+50;
		}
	}
	$texto .= "		</select>";
	$texto .= "</div>";

	$texto .= "<div class=\"btn-group float-right\">";

	//	permite desplazarse por las imagenes anteriores
	if($anterior>0) {
		$texto .= "<a href=\"$enlace$primero\" class=\"btn\" title=\"Ir a la primer p&aacute;gina\"><img src=\"../images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\"></a> ";	
	} else {
		$texto .= "<a class=\"btn disabled\"><img src=\"../images/primero_on.gif\" border=\"0\" title=\"Primer p&aacute;gina\"></a>"; 		
	}
	if ($actual>1) {
		$texto .= "<a href=\"$enlace$anterior\" class=\"btn\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"../images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\"></a> "; 
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la anterior p&aacute;gina\"><img src=\"../images/anterior_on.gif\" border=\"0\" title=\"Anterior p&aacute;gina\"></a>";
	}
	$actual = sprintf("%02s",$actual); 
	$total_paginas = sprintf("%02s",$total_paginas); 

	//	permite seleccionar la pagina
	$texto .= "		<select class=\"btn\" onchange=\"AbrirURL(this);\" style=\"width: auto;\" title=\"Ir a la p&aacute;gina numero\">";
	for($i=1; $i<$total_paginas+1; $i++) {
		if($actual == $i) {
			$texto .= "	<option value=\"$enlace$i\" selected=\"selected\">".$i."</option>";
		} else {
			$texto .= "	<option value=\"$enlace$i\">".$i."</option>";
		}
	}
	$texto .= "</select>";

	//	permite desplazarse por las imagenes posteriores
	if ($actual<$total_paginas) {
		$texto .= " <a href=\"$enlace$posterior\" class=\"btn\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"../images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\"></a>";
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la siguiente p&aacute;gina\"><img src=\"../images/siguiente_on.gif\" border=\"0\" title=\"Siguiente p&aacute;gina\"></a>";		
	}
	if($posterior<=$total_paginas) {
		$texto .= "<a href=\"$enlace$ultimo\" class=\"btn\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"../images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\"></a> "; 	
	} else {
		$texto .= "<a class=\"btn disabled\" title=\"Ir a la &uacute;ltima p&aacute;gina\"><img src=\"../images/ultimo_on.gif\" border=\"0\" title=\"&uacute;ltimo p&aacute;gina\"></a>"; 		
	}
	$texto .= "</div>";

	return $texto;
}

//	-------------------------------------------------------------
//	Funcion que permite mostrar una fecha en distintos formatos
function formato_fecha($fecha, $t) {
	$mes["01"] = "Enero";
	$mes["02"] = "Febrero";
	$mes["03"] = "Marzo";
	$mes["04"] = "Abril";
	$mes["05"] = "Mayo";
	$mes["06"] = "Junio";
	$mes["07"] = "Julio";
	$mes["08"] = "Agosto";
	$mes["09"] = "Septiembre";
	$mes["10"] = "Octubre";
	$mes["11"] = "Noviembre";
	$mes["12"] = "Diciembre";

	$diasemana["0"] = "Domingo";
	$diasemana["1"] = "Lunes";
	$diasemana["2"] = "Martes";
	$diasemana["3"] = "Mi&eacute;rcoles";
	$diasemana["4"] = "Jueves";
	$diasemana["5"] = "Viernes";
	$diasemana["6"] = "S&aacute;bado";

	if($fecha != null) {
		if(strpos($fecha,"/") > 0) {
			list($year, $month, $day) = explode("/", $fecha);		
		} else {
			if(strpos($fecha,".") > 0) {
				list($year, $month, $day) = explode(".", $fecha);		
			} else {
				if(strpos($fecha," ") > 0) {
					list($vFecha, $vHora) = explode(" ", $fecha);		
					list($year, $month, $day) = explode("-", $vFecha);		
					list($hour, $minute, $second) = explode(":", $vHora);		
				} else {
					if(strpos($fecha,"-") > 0) {
						list($year, $month, $day) = explode("-", $fecha);		
					}
				}
			}			
		}
	} else {
		//Fecha Nula
		$year = '0000';
		$month = '00';
		$day = '00';
	}
	switch($t) {
		case 1: //Formato: dia semana, día de mes de año
				$fecha_completa = $diasemana[date("w", mktime(0,0,0,$month,$day,$year))].", ".$day." de ".$mes[$month]." de ".$year; 
				break;
		case 2:	//Formato: dd-mm-aaaa
				$fecha_completa = $day."-".$month."-".$year;
				break;
		case 3:	//Formato: dd/mm/aaaa
				$fecha_completa = $day."/".$month."/".$year;
				break;
		case 4:	//Formato: aaaa-mm-aa para guardar en la Base de Datos
				$fecha_completa = $day."-".$month."-".$year;
				break;
		case 5: //Recibo la fecha en formato: dd/mm/aaaa y devuelvo en formato: dia semana, día de mes de año
				$fecha_completa = $diasemana[date("w", mktime(0,0,0,$month,$year,$day))].", ".$year." de ".$mes[$month]." de ".$day; 
				break;
		case 6: //Recibo la fecha en formato: aaaa-mm-dd 00:00:00 y devuelvo en formato: día/mes/año hora:minutos
				$fecha_completa = $day."/".$month."/".$year." ".$hour.":".$minute;
				break;
	}
	Return $fecha_completa;
}
//	-------------------------------------------------------------

/*************************************
 Devuelve una cadena con la fecha que se le manda como par&aacute;metro en formato largo.
 *************************************/
function FechaFormateada($FechaStamp){
	$ano = date('Y',$FechaStamp); //<-- Año
	$mes = date('m',$FechaStamp); //<-- número de mes (01-31)
	$dia = date('d',$FechaStamp); //<-- Día del mes (1-31)
	$dialetra = date('w',$FechaStamp);  //Día de la semana(0-7)
	switch($dialetra){
		case 0: $dialetra="Domingo"; break;
		case 1: $dialetra="Lunes"; break;
		case 2: $dialetra="Martes"; break;
		case 3: $dialetra="Mi&eacute;rcoles"; break;
		case 4: $dialetra="Jueves"; break;
		case 5: $dialetra="Viernes"; break;
		case 6: $dialetra="S&aacute;bado"; break;
	}
	switch($mes) {
		case '01': $mesletra="Enero"; break;
		case '02': $mesletra="Febrero"; break;
		case '03': $mesletra="Marzo"; break;
		case '04': $mesletra="Abril"; break;
		case '05': $mesletra="Mayo"; break;
		case '06': $mesletra="Junio"; break;
		case '07': $mesletra="Julio"; break;
		case '08': $mesletra="Agosto"; break;
		case '09': $mesletra="Septiembre"; break;
		case '10': $mesletra="Octubre"; break;
		case '11': $mesletra="Noviembre"; break;
		case '12': $mesletra="Diciembre"; break;
	}    
	return "$dialetra, $dia de $mesletra de $ano";
}

function dec_romana ($numero,$case) {
	$simbolos=array("I","V","X","L","C","D","M");
	$valores=array ("1","5","10","50","100","500","1000","5000");
	$romano = "";
	if ($numero<=3999) {
		while ($numero>0) {
			$i=0;
			while ($i<7) {
				while ($numero>=$valores[$i] && $numero<$valores[$i+1]) {
					$par=$i%2;  // paridad
					if ($numero>=$valores[$i+1]-$valores[$i-$par]) {
						$romano=$romano.$simbolos[$i-$par].$simbolos[$i+1];
						$numero=$numero-($valores[$i+1]-$valores[$i-$par]);
					} else {
						$romano=$romano.$simbolos[$i];
						$numero=$numero-$valores[$i];
					}
				}
				$i++;
			}
		}
		if ($case=="l") $romano=strtolower ($romano);
	}
	else $romano="overflow";
	return $romano;
}

// Ejemplo de uso -->  
//echo $numero=dec_romana("2349","u");

function sumarDiasBK($numdias, $date) {
	if (isset($date)) {
		$date = time();
	}
	list($hora, $min, $seg, $dia, $mes, $anno) = explode(" ", date( "H i s d m Y"));
	$d = $dia + $numdias;
	$fecha = date("Y-m-d", mktime($hora, $min, $seg, $mes, $d, $anno));
	echo("[".$numdias." ".$date."".$fecha."]<br />");
	return $fecha;
} 

function sumarDias($numdias, $date) {
	if($date == ''){
		$date = date("Y-m-d");
	}
	
	$parametro = "";
	$parametro = $numdias > 0 ? "+ ".($numdias)." days" : ($numdias)." days";

	$mod_date = strtotime($date.$parametro);
	$fecha = date('Y-m-d', $mod_date);

	return $fecha;
} 

//permite crear una imagen mas pequeña de la originalmente subida, para la vista previa
function redimensionar_imagen($imagen,$imagensmall,$ancho,$alto){
	ini_set("memory_limit","64M");
	//establecemos los límites de ancho y alto
	if(isset($ancho)) { $nuevo_ancho = $ancho ; } else { $nuevo_ancho = 200 ; }
	if(isset($alto)) { $nuevo_alto = $alto ; } else { $nuevo_alto = 200 ; }
   
	//Recojo información de la im&aacute;gen
	$info_imagen = getimagesize($imagen);
	$alto = $info_imagen[1];
	$ancho = $info_imagen[0];
	$tipo_imagen = $info_imagen[2];
   
	//Determino las nuevas medidas en función de los límites
	if($ancho > $nuevo_ancho OR $alto > $nuevo_alto) {
		if(($alto - $nuevo_alto) > ($ancho - $nuevo_ancho))	{
			$nuevo_ancho = round($ancho * $nuevo_alto / $alto,0) ;       
		} else {
			$nuevo_alto = round($alto * $nuevo_ancho / $ancho,0);   
		}
	} else { //si la imagen es m&aacute;s pequeña que los límites la dejo igual.
		$nuevo_alto = $alto;
		$nuevo_ancho = $ancho;
	}

	// dependiendo del tipo de imagen tengo que usar diferentes funciones
	switch ($tipo_imagen) {
		case 1: //si es gif ?
			$imagen_nueva = imagecreate($nuevo_ancho, $nuevo_alto);
			$imagen_vieja = imagecreatefromgif($imagen);
			//cambio de tamaño?
			imagecopyresampled($imagen_nueva, $imagen_vieja, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
			if (!imagegif($imagen_nueva, $imagensmall)) return false;
		break;
	   
		case 2: //si es jpeg ?
			$imagen_nueva = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
			$imagen_vieja = imagecreatefromjpeg($imagen);
			//cambio de tamaño?
			imagecopyresampled($imagen_nueva, $imagen_vieja, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
			if (!imagejpeg($imagen_nueva, $imagensmall)) return false;
		break;
	   
		case 3: //si es png ?
			$imagen_nueva = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
			$imagen_vieja = imagecreatefrompng($imagen);
			//cambio de tamaño?
			imagecopyresampled($imagen_nueva, $imagen_vieja, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
			if (!imagepng($imagen_nueva, $imagensmall)) return false;
		break;
	}
	return true; //si todo ha ido bien devuelve true
}

function verificar_url($url) {
	if($url == NULL) return false;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if(curl_errno($ch)){   
		echo 'Curl error: ' . curl_error($ch);
	}            
	curl_close($ch);
	if(($httpcode >= 200) && ($httpcode <= 301)){
		return true;
	} else {
		return false;
	}
}

function obtenerDatosArchivo($url) {
	$ch = curl_init($url);
	$fp = fopen("temp.txt", "w");

	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	curl_exec($ch);
	curl_close($ch);
	//	cierra el archivo y lee su contenido
	fclose($fp);
	$fp = fopen("temp.txt", "rb");
	$contenido = fread($fp, filesize("temp.txt"));
	fclose($fp);
	if(file_exists("temp.txt")) {
		@unlink("temp.txt");
	}
	return $contenido;
}

function obtenerDatosArchivoBK($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$contenido = curl_exec($ch);
	curl_close($ch);
	return $contenido;
}

function generarPassword ($length = 8){
	# start with a blank password
	$password = "";
	# define possible characters
	$possible = "abcdefghijklmnopqrstuvwxyz0123456789"; //you can change this!
	# set up a counter
	$i = 0;
	# add random characters to $password until $length is reached
	while ($i < $length) {
		# pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		# we don't want this character if it's already in the password
		if (!strstr($password, $char)) {
		  $password .= $char;
		  $i++;
		}
	}
	# done!
	return $password;
}

function unescape($strIn, $iconv_to = 'UTF-8') {
    $strOut = '';
    $iPos = 0;
    $len = strlen ($strIn);
    while ($iPos < $len) {
        $charAt = substr ($strIn, $iPos, 1);
        if ($charAt == '\\') {
            $iPos++;
            $charAt = substr ($strIn, $iPos, 1);
            if ($charAt == 'u') {
                // Unicode character
                $iPos++;
                $unicodeHexVal = substr ($strIn, $iPos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $strOut .= code2utf($unicode);
                $iPos += 4;
            }
            else {
                // Escaped ascii character
                $hexVal = substr ($strIn, $iPos, 2);
                if (hexdec($hexVal) > 127) {
                    // Convert to Unicode
                    $strOut .= code2utf(hexdec ($hexVal));
                }
                else {
                    $strOut .= chr (hexdec ($hexVal));
                }
                $iPos += 2;
            }
        }
        else {
            $strOut .= $charAt;
            $iPos++;
        }
    }
    if ($iconv_to != "UTF-8") {
        $strOut = iconv("UTF-8", $iconv_to, $strOut);
    }
    return $strOut;
}

function code2utf($num){
    if($num<128)return chr($num);
    if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
    if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
    if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
    return '';
}

?>
