<?php
	//	XPANEL 3.0
	//	Fecha actualización:	02/12/2012 09:33 a.m.

	ini_set ('error_reporting', E_ALL);
	if(!file_exists("xpanel/datos.php")) {
		header ("Location: xpanel/modulos/install.php");
		exit;
	}
	require("verifica.php");
	//	armo la direccion completa de la plantilla html
	if(($sitio_plantilla == "") or $sitio_estilo == "") {
		header ("Location: xpanel/?error_login=12");
		exit;
	}
	//	armo la direccion completa la pagina de usuarios
	if(strpos($sitio_plantilla,"/") > 0) {
		$res = explode("/",$sitio_plantilla);
		$carpeta = $res[0]."/";
		$PlantillaHTML = $sitio_url.$path_plantillas.$carpeta."index_usuarios.html";
	} else {
		$carpeta = "/";
		$PlantillaHTML = $sitio_url.$path_plantillas."index_usuarios.html";
	}

	//	obtengo la plantilla HTML
	if(verificar_url($PlantillaHTML)) {

		//	armo la direccion completa del estilo CSS
		if(strpos($sitio_plantilla,"/") > 0) {
			$res = explode("/",$sitio_plantilla);
			$carpeta = $res[0]."/";
			$EstiloHTML = $sitio_url.$path_plantillas.$carpeta.$sitio_estilo;
		} else {
			$carpeta = "/";
			$EstiloHTML = $sitio_url.$path_plantillas.$sitio_estilo;
		}

		$ContenidoHTML = obtenerDatosArchivo($PlantillaHTML);
		//	actualizo la direccion del estilo css
		$ContenidoHTML = str_replace($sitio_estilo,$EstiloHTML,$ContenidoHTML);
		//	actualizo los datos del sitio
		$ContenidoHTML = str_replace("{SITIO_NOMBRE}",$sitio_nombre,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SITIO_URL}",$sitio_url,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SITIO_EMAIL}",$sitio_email,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SITIO_ESLOGAN}",$sitio_eslogan,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SITIO_LOGO}","<img src='".$sitio_url.$sitio_logo_archivo."' />",$ContenidoHTML);
		$ContenidoHTML = str_replace("{FECHA}",date("d/m/Y"),$ContenidoHTML);
		$ContenidoHTML = str_replace("{ESTILOCSS}",$EstiloHTML,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SESION}",session_name()."=".session_id(),$ContenidoHTML);

		$path = "";
		$Secciones_Head = "";
		$secciones_Body = "";
		if(isset($_GET['menu'])) {
			$path .= " &raquo; <a href=\"usuarios.php?".session_name()."=".session_id()."\" title=\"\">".$_SESSION['usuario_nombre']."</a>";
		} else {
			$path .= " &raquo; ".$_SESSION['usuario_nombre'];
		}
//		$path = " &raquo; <a href='?menu=".$_GET['menu']."'>".$registros->fields["NOMBRE_MENU"]."</a> <span>{PATH}</span>";
		
		$ContenidoHTML = str_replace("{SECCIONES1}",$Secciones_Head,$ContenidoHTML);
		$ContenidoHTML = str_replace("{SECCIONES2}",$secciones_Body,$ContenidoHTML);
/*		if($path != "") {
			$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
		} else {
			if($_GET['menu'] == "normas") {
				$path = " &raquo; <a href='?menu=".$_GET['menu']."'>Normas Archivísticas</a> <span>{PATH}</span>";
				$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
			}
			if($_GET['menu'] == "archiveros") {
				$path = " &raquo; <a href='?menu=".$_GET['menu']."'>Archiveros</a> <span>{PATH}</span>";
				$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
			}
			if($_GET['menu'] == "destacados") {
				$path = " &raquo; <a href='?menu=".$_GET['menu']."'>Destacados</a> <span>{PATH}</span>";
				$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
			}
			if($_GET['menu'] == "iniciarsesion") {
				$path = " &raquo; <a href='?menu=".$_GET['menu']."'>Iniciar sesión</a>";
				$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
			}
			if($_GET['menu'] == "usuarios") {
				$path = " &raquo; <a href='?menu=".$_GET['menu']."'>Usuarios</a> <span>{PATH}</span>";
				$ContenidoHTML = str_replace("<span>{PATH}</span>",$path,$ContenidoHTML);
			}
		}*/

		//	Cargo los CONTENIDOS segun la seccion seleccionada
		if(isset($_GET['menu'])) {		
			$url = "?".session_name()."=".session_id();
			if(isset($_GET['menu'])) { $url .= "&menu=".$_GET['menu']; }
			if(isset($_GET['accion'])) { $url .= "&accion=".$_GET['accion']; }

			$contenido_mostrar = "";
			$contenido_mostrar .= "<div class=\"row-fluid\">";
			$contenido_mostrar .= "	<div class=\"span9\">";
			$contenido_mostrar .= "		{PUBLICIDAD_H}";

			//	verifico el tipo de contenido del parametro de menu
			switch($_GET['menu']) {
				case "datos":
					$titulo_sitio = "Datos Personales | ".$_SESSION['usuario_nombre']." | ".$sitio_nombre;
					$path .= " &raquo; Datos Personales";

					$contenido_mostrar .= "<h1>Datos Personales</h1>";

					if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
						$conexion->Query("UPDATE usuarios SET tipo_doc = '".$_POST['tipo_doc']."',nro_doc = '".trim($_POST['nro_doc'])."',nombre_completo = '".addslashes($_POST['nombre_completo'])."', direccion = '".addslashes($_POST['direccion'])."', telefono_fijo = '".$_POST['telefono_fijo']."', telefono_movil = '".$_POST['telefono_movil']."', ciudad = '".addslashes($_POST['ciudad'])."', codigo_pais = '".$_POST['codigo_pais']."', codigo_postal = '".$_POST['codigo_postal']."', email = '".$_POST['email']."', institucion_trabaja = '".addslashes($_POST['institucion_trabaja'])."', cargo_trabaja = '".addslashes($_POST['cargo_trabaja'])."', nivel_formacion = '".addslashes($_POST['nivel_formacion'])."', institucion_estudia = '".addslashes($_POST['institucion_estudia'])."', nivel_estudio = '".addslashes($_POST['nivel_estudio'])."', suscripto_boletin = '".$_POST['suscripto_boletin']."', mostrar_datos = '".$_POST['mostrar_datos']."' WHERE (id_usuario = '".$_POST['id_usuario']."')");
						$id = $_POST['id_usuario'];
						$id_usuario = sprintf("US%08s", $id);

						//	verifico los archivos subidos
						if(isset($_FILES["archivo_foto"])) {
							//obtengo el nombre del archivo y su extension
							$res = explode(".", $_FILES['archivo_foto']['name']);
							$tipo_archivo = $_FILES['archivo_foto']['type'];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_usuario.".".$extension;

							//verifico si se subio un archivo
							if(is_uploaded_file($_FILES['archivo_foto']['tmp_name'])) {
								//verifico el tipo de archivo
								if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
								   ($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
									//copio el archivo
									if(copy($_FILES['archivo_foto']['tmp_name'], stripslashes($path_imagenes."fotos/".$nombre_archivo))) {
										//si no hubo inconvenientes actualiza el nombre del archivo
										$conexion->Query("UPDATE usuarios SET foto = '".$nombre_archivo."' WHERE (id_usuario = '".$id."')");
										//obtengo la ubicacion de la imagen original y small
										$nombre_completo_imagenoriginal = $path_imagenes."fotos/".$nombre_archivo;
										$nombre_completo_imagensmall = $path_imagenes."fotos/".$id_usuario."_small.".$extension;
										//paso como parametro el nombre la nueva imagen pequeña
										redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 100, 100);
									}
									//elimino el archivo temporal
									unlink($_FILES['archivo_foto']['tmp_name']);
								} else {
									//elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
									unlink($_FILES['archivo_foto']['tmp_name']);
									echo("<script type='text/javascript'>");
									echo("window.alert('".$_FILES['archivo_foto']['name'].": ".$msg_error[10]."');");
									echo("</script>");
								}
							}
						}

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_actualizaciondatospersonales.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL,USUARIO FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",$datosUsuario["NOMBRE_COMPLETO"],$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$contenido_mail = str_replace("{USUARIO}",$datosUsuario["USUARIO"],$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se han registrado de forma satisfactoria las actualizaciones realizadas en sus datos personales, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria las actualizaciones realizadas en sus datos personales, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}

					}
					$ArchivoHTML = $sitio_url."html/usuarios_datos.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;
					
					break;
				case "cambiarclave":
					$titulo_sitio = "Cambiar Contrase&ntilde;a | ".$_SESSION['usuario_nombre']." | ".$sitio_nombre;
					$path .= " &raquo; Cambiar Contrase&ntilde;a";

					$contenido_mostrar .= "		<h1>Cambiar Contrase&ntilde;a</h1>";

					if(isset($_GET['accion']) and ($_GET['accion'] == "updateuser")) {
						if($_POST['clave'] == $_POST['clave2']) {
							$conexion->Query("UPDATE usuarios SET clave = '".trim($_POST['clave'])."', clave2 = '".md5(trim($_POST['clave']))."' WHERE (id_usuario = '".$_POST['id_usuario']."')");

							//	enviar notificacion
							$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_cambiocontrasenia.html";

							$contenido_mail = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
							$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

							$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

							$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
							$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

							//	obtengo el logo para boletin
							if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
								$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
							} else {
								$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
							}

							$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL,USUARIO FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
							$contenido_mail = str_replace("{NOMBRE}",$datosUsuario["NOMBRE_COMPLETO"],$contenido_mail);
							$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
							$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
							$contenido_mail = str_replace("{USUARIO}",$datosUsuario["USUARIO"],$contenido_mail);

							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->ClearAddresses();
							$mail->AddAddress($datosUsuario["EMAIL"]);
							$mail->AddBCC($sitio_email);
							$mail->AddBCC($sitio_email_admin);
							$mail->Body				= $contenido_mail;
							if($mail->Send()) {
								$contenido_mostrar .= "<div class=\"alert alert-success\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se han registrado de forma satisfactoria el cambio de contraseña, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							} else {
								$contenido_mostrar .= "<div class=\"alert alert-error\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el cambio de contraseña, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							}

						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Debe ingresar nuevamente dos veces la contraseña, dado que no coinciden.</p>";
							$contenido_mostrar .= "</div>";
						}
					}

					$ArchivoHTML = $sitio_url."html/usuarios_cambiarclave.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;
					
					break;
				case "publicaciones":
					$titulo_sitio = ucfirst($_GET['menu'])." | ".$sitio_nombre;
					$path .= " &raquo; ".ucfirst($_GET['menu']);

					$contenido_mostrar .= "		<h1>".ucfirst($_GET['menu'])."</h1>";

					if(isset($_POST['publicacion'])) {
						//	registro la compra
						$conexion->Query("INSERT INTO usuario_compra_publicacion (ID_USUARIO,ID_PUBLICACION,FECHA) VALUES('".$_SESSION['usuario_id']."','".$_POST['publicacion']."','".date("Y-m-d")."')");
						
						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_compra.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",$datosUsuario["NOMBRE_COMPLETO"],$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$datosPublicacion = $conexion->GetRow("SELECT TITULO FROM publicaciones WHERE (id_publicacion = '".$_POST['publicacion']."')");
						$contenido_mail = str_replace("{PUBLICACION}",$datosPublicacion["TITULO"],$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la compra de la publicación: <strong>".$datosPublicacion["TITULO"]."</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la compra de la publicación: <strong>".$datosPublicacion["TITULO"]."</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					}

					$ArchivoHTML = $sitio_url."html/usuarios_publicaciones.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;

					break;
				case "cursos":
					$titulo_sitio = ucfirst($_GET['menu'])." | ".$sitio_nombre;
					$path .= " &raquo; ".ucfirst($_GET['menu']);

					$contenido_mostrar .= "		<h1>".ucfirst($_GET['menu'])."</h1>";

					if(isset($_POST['curso'])) {
						//	registro la inscripción
						$conexion->Query("INSERT INTO usuario_seinscribe_curso (ID_USUARIO,ID_CURSO,FECHA) VALUES('".$_SESSION['usuario_id']."','".$_POST['curso']."','".date("Y-m-d")."')");
						
						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_inscripcion.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",$datosUsuario["NOMBRE_COMPLETO"],$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$datosCurso = $conexion->GetRow("SELECT NOMBRE FROM cursos WHERE (id_curso = '".$_POST['curso']."')");
						$contenido_mail = str_replace("{CURSO}",$datosCurso["NOMBRE"],$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria su inscripción al curso: <strong>".$datosCurso["NOMBRE"]."</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria su inscripción al curso: <strong>".$datosCurso["NOMBRE"]."</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					}

					$ArchivoHTML = $sitio_url."html/usuarios_cursos.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;

					break;
				case "enlaces":
					$titulo_sitio = ucfirst($_GET['menu'])." | ".$sitio_nombre;
					$path .= " &raquo; ".ucfirst($_GET['menu']);

					$contenido_mostrar .= "		<h1>".ucfirst($_GET['menu'])."</h1>";

					//	verifico si es una actualizacion
					if(isset($_POST['id_enlace'])) {
						//	actualizo en enlace
						$conexion->Query("UPDATE enlaces SET categoria = '".addslashes($_POST['categoria'])."', titulo = '".addslashes($_POST['titulo'])."', url = '".addslashes($_POST['url'])."', descripcion = '".addslashes($_POST['contenido'])."' WHERE (id_enlace = '".$_POST['id_enlace']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_actualizacionenlace.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$datosEnlace = $conexion->GetRow("SELECT TITULO,URL FROM enlaces WHERE (id_enlace = '".$_POST['id_enlace']."')");
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosEnlace["TITULO"]),$contenido_mail);
						$contenido_mail = str_replace("{URL}",stripslashes($datosEnlace["URL"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos del enlace: <strong>".$datosEnlace["TITULO"]."</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos del enlace: <strong>".$datosEnlace["TITULO"]."</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					} else {
						//	verifico si es una insercion
						if(isset($_POST['categoria']) and isset($_POST['titulo']) and isset($_POST['url'])) {
							//	registro la inscripción
							$conexion->Query("INSERT INTO enlaces (ID_ENLACE,ID_USUARIO,CATEGORIA,TITULO,URL,DESCRIPCION,ACTIVO,FECHA_ALTA) VALUES(null,'".$_SESSION['usuario_id']."','".addslashes($_POST['categoria'])."','".addslashes($_POST['titulo'])."','".addslashes($_POST['url'])."','".addslashes($_POST['contenido'])."','1','".date("Y-m-d")."')");
							$id_enlace = $conexion->Insert_ID();
							
							//	enviar notificacion
							$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_registroenlace.html";

							$contenido_mail = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
							$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

							$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

							$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
							$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

							//	obtengo el logo para boletin
							if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
								$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
							} else {
								$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
							}

							$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
							$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
							$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
							$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

							$datosEnlace = $conexion->GetRow("SELECT * FROM enlaces WHERE (id_enlace = '".$id_enlace."')");
							$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosEnlace["CATEGORIA"]),$contenido_mail);
							$contenido_mail = str_replace("{TITULO}",stripslashes($datosEnlace["TITULO"]),$contenido_mail);
							$contenido_mail = str_replace("{URL}",stripslashes($datosEnlace["URL"]),$contenido_mail);
							$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosEnlace["DESCRIPCION"]),$contenido_mail);

							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->ClearAddresses();
							$mail->AddAddress($datosUsuario["EMAIL"]);
							$mail->AddBCC($sitio_email);
							$mail->AddBCC($sitio_email_admin);
							$mail->Body				= $contenido_mail;
							if($mail->Send()) {
								$contenido_mostrar .= "<div class=\"alert alert-success\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el enlace: <strong>".stripslashes($datosEnlace["TITULO"])."</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							} else {
								$contenido_mostrar .= "<div class=\"alert alert-error\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el enlace: <strong>".stripslashes($datosEnlace["TITULO"])."</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							}
						}
					}
					//	verificando si se desea eliminar un registro
					if(isset($_GET['accion']) and ($_GET['accion'] == "delete")) {
						//	obtengo datos del registro a eliminar
						$datosEnlace = $conexion->GetRow("SELECT * FROM enlaces WHERE (id_enlace = '".$_GET['id']."')");
						//	elimino el registro
						$conexion->Query("DELETE FROM enlaces WHERE (id_enlace = '".$_GET['id']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_eliminacionenlace.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

						$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosEnlace["CATEGORIA"]),$contenido_mail);
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosEnlace["TITULO"]),$contenido_mail);
						$contenido_mail = str_replace("{URL}",stripslashes($datosEnlace["URL"]),$contenido_mail);
						$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosEnlace["DESCRIPCION"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria el enlace: <strong>".stripslashes($datosEnlace["TITULO"])."</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria el enlace: <strong>".stripslashes($datosEnlace["TITULO"])."</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					}

					$ArchivoHTML = $sitio_url."html/usuarios_enlaces.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;

					break;
				case "archivos":
					$titulo_sitio = ucfirst($_GET['menu'])." | ".$sitio_nombre;
					$path .= " &raquo; ".ucfirst($_GET['menu']);

					$contenido_mostrar .= "		<h1>".ucfirst($_GET['menu'])."</h1>";

					//	verifico si es una actualizacion
					if(isset($_POST['id_institucion'])) {
						//	actualizo el archivo
						$conexion->Query("UPDATE instituciones SET titulo = '".addslashes($_POST['titulo'])."', descripcion = '".addslashes($_POST['descripcion_'.$_POST['id_institucion']])."', autoridades = '".addslashes($_POST['autoridades_'.$_POST['id_institucion']])."', historia = '".addslashes($_POST['historia_'.$_POST['id_institucion']])."', objetivos = '".addslashes($_POST['objetivos_'.$_POST['id_institucion']])."', contactos = '".addslashes($_POST['contactos_'.$_POST['id_institucion']])."' WHERE (id_institucion = '".$_POST['id_institucion']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_actualizacionarchivo.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$datosArchivo = $conexion->GetRow("SELECT TITULO FROM instituciones WHERE (id_institucion = '".$_POST['id_institucion']."')");
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosArchivo["TITULO"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos del Archivo: <strong>&quot;".$datosArchivo["TITULO"]."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos del Archivo: <strong>&quot;".$datosArchivo["TITULO"]."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					} else {
						//	verifico si es una insercion
						if(isset($_POST['titulo']) and isset($_POST['descripcion'])) {
							//	registro la inscripción
							$conexion->Query("INSERT INTO instituciones (ID_INSTITUCION,ID_USUARIO,CATEGORIA,TITULO,DESCRIPCION,AUTORIDADES,HISTORIA,OBJETIVOS,CONTACTOS,ACTIVO,FECHA_ALTA) VALUES(null,'".$_SESSION['usuario_id']."','".addslashes($_POST['categoria'])."','".addslashes($_POST['titulo'])."','".addslashes($_POST['descripcion'])."','".addslashes($_POST['autoridades'])."','".addslashes($_POST['historia'])."','".addslashes($_POST['objetivos'])."','".addslashes($_POST['contactos'])."','1','".date("Y-m-d")."')");
							$id_institucion = $conexion->Insert_ID();
							
							//	enviar notificacion
							$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_registroarchivo.html";

							$contenido_mail = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
							$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

							$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

							$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
							$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

							//	obtengo el logo para boletin
							if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
								$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
							} else {
								$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
							}

							$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
							$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
							$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
							$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

							$datosArchivo = $conexion->GetRow("SELECT * FROM instituciones WHERE (id_institucion = '".$id_institucion."')");
							$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosArchivo["CATEGORIA"]),$contenido_mail);
							$contenido_mail = str_replace("{TITULO}",stripslashes($datosArchivo["TITULO"]),$contenido_mail);
							$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosArchivo["DESCRIPCION"]),$contenido_mail);
							$contenido_mail = str_replace("{AUTORIDADES}",stripslashes($datosArchivo["AUTORIDADES"]),$contenido_mail);
							$contenido_mail = str_replace("{HISTORIA}",stripslashes($datosArchivo["HISTORIA"]),$contenido_mail);
							$contenido_mail = str_replace("{OBJETIVOS}",stripslashes($datosArchivo["OBJETIVOS"]),$contenido_mail);
							$contenido_mail = str_replace("{CONTACTOS}",stripslashes($datosArchivo["CONTACTOS"]),$contenido_mail);

							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->ClearAddresses();
							$mail->AddAddress($datosUsuario["EMAIL"]);
							$mail->AddBCC($sitio_email);
							$mail->AddBCC($sitio_email_admin);
							$mail->Body				= $contenido_mail;
							if($mail->Send()) {
								$contenido_mostrar .= "<div class=\"alert alert-success\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							} else {
								$contenido_mostrar .= "<div class=\"alert alert-error\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							}
						}
					}
					//	verificando si se desea eliminar un registro
					if(isset($_GET['accion']) and ($_GET['accion'] == "delete")) {
						//	obtengo datos del registro a eliminar
						$datosArchivo = $conexion->GetRow("SELECT * FROM instituciones WHERE (id_institucion = '".$_GET['id']."')");
						//	elimino el registro
						$conexion->Query("DELETE FROM instituciones WHERE (id_institucion = '".$_GET['id']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_eliminacionarchivo.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

						$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosArchivo["CATEGORIA"]),$contenido_mail);
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosArchivo["TITULO"]),$contenido_mail);
						$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosArchivo["DESCRIPCION"]),$contenido_mail);
						$contenido_mail = str_replace("{AUTORIDADES}",stripslashes($datosArchivo["AUTORIDADES"]),$contenido_mail);
						$contenido_mail = str_replace("{HISTORIA}",stripslashes($datosArchivo["HISTORIA"]),$contenido_mail);
						$contenido_mail = str_replace("{OBJETIVOS}",stripslashes($datosArchivo["OBJETIVOS"]),$contenido_mail);
						$contenido_mail = str_replace("{CONTACTOS}",stripslashes($datosArchivo["CONTACTOS"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					}

					$ArchivoHTML = $sitio_url."html/usuarios_archivos.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;

					break;
				case "carreras":
					$titulo_sitio = ucfirst($_GET['menu'])." | ".$sitio_nombre;
					$path .= " &raquo; ".ucfirst($_GET['menu']);

					$contenido_mostrar .= "		<h1>".ucfirst($_GET['menu'])."</h1>";

					//	verifico si es una actualizacion
					if(isset($_POST['id_institucion'])) {
						//	actualizo el archivo
						$conexion->Query("UPDATE instituciones SET titulo = '".addslashes($_POST['titulo'])."', descripcion = '".addslashes($_POST['descripcion_'.$_POST['id_institucion']])."', objetivos = '".addslashes($_POST['objetivos_'.$_POST['id_institucion']])."', contactos = '".addslashes($_POST['contactos_'.$_POST['id_institucion']])."' WHERE (id_institucion = '".$_POST['id_institucion']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_actualizacioncarrera.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);
						$datosCarrera = $conexion->GetRow("SELECT TITULO FROM instituciones WHERE (id_institucion = '".$_POST['id_institucion']."')");
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosCarrera["TITULO"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos de la carrera: <strong>&quot;".$datosCarrera["TITULO"]."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria la actualización de los datos de la carrera: <strong>&quot;".$datosCarrera["TITULO"]."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					} else {
						//	verifico si es una insercion
						if(isset($_POST['titulo']) and isset($_POST['descripcion'])) {
							//	registro la inscripción
							$conexion->Query("INSERT INTO instituciones (ID_INSTITUCION,ID_USUARIO,CATEGORIA,TITULO,DESCRIPCION,OBJETIVOS,CONTACTOS,ACTIVO,FECHA_ALTA) VALUES(null,'".$_SESSION['usuario_id']."','".addslashes($_POST['categoria'])."','".addslashes($_POST['titulo'])."','".addslashes($_POST['descripcion'])."','".addslashes($_POST['objetivos'])."','".addslashes($_POST['contactos'])."','1','".date("Y-m-d")."')");
							$id_institucion = $conexion->Insert_ID();
							
							//	enviar notificacion
							$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_registrocarrera.html";

							$contenido_mail = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
							$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

							$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
							$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

							$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
							$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

							//	obtengo el logo para boletin
							if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
								$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
							} else {
								$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
							}

							$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
							$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
							$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
							$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

							$datosCarrera = $conexion->GetRow("SELECT * FROM instituciones WHERE (id_institucion = '".$id_institucion."')");
							$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosCarrera["CATEGORIA"]),$contenido_mail);
							$contenido_mail = str_replace("{TITULO}",stripslashes($datosCarrera["TITULO"]),$contenido_mail);
							$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosCarrera["DESCRIPCION"]),$contenido_mail);
							$contenido_mail = str_replace("{OBJETIVOS}",stripslashes($datosCarrera["OBJETIVOS"]),$contenido_mail);
							$contenido_mail = str_replace("{CONTACTOS}",stripslashes($datosCarrera["CONTACTOS"]),$contenido_mail);

							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->ClearAddresses();
							$mail->AddAddress($datosUsuario["EMAIL"]);
							$mail->AddBCC($sitio_email);
							$mail->AddBCC($sitio_email_admin);
							$mail->Body				= $contenido_mail;
							if($mail->Send()) {
								$contenido_mostrar .= "<div class=\"alert alert-success\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosCarrera["TITULO"])."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							} else {
								$contenido_mostrar .= "<div class=\"alert alert-error\">";
								$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
								$contenido_mostrar .= "	<p>Se ha registrado de forma satisfactoria el archivo: <strong>&quot;".stripslashes($datosCarrera["TITULO"])."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
								$contenido_mostrar .= "</div>";
							}
						}
					}
					//	verificando si se desea eliminar un registro
					if(isset($_GET['accion']) and ($_GET['accion'] == "delete")) {
						//	obtengo datos del registro a eliminar
						$datosArchivo = $conexion->GetRow("SELECT * FROM instituciones WHERE (id_institucion = '".$_GET['id']."')");
						//	elimino el registro
						$conexion->Query("DELETE FROM instituciones WHERE (id_institucion = '".$_GET['id']."')");

						//	enviar notificacion
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_eliminacioncarrera.html";

						$contenido_mail = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
						$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

						$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
						$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

						$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
						$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
						} else {
							$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
						}

						$datosUsuario = $conexion->GetRow("SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");					
						$contenido_mail = str_replace("{NOMBRE}",stripslashes($datosUsuario["NOMBRE_COMPLETO"]),$contenido_mail);
						$contenido_mail = str_replace("{EMAIL}",$datosUsuario["EMAIL"],$contenido_mail);
						$contenido_mail = str_replace("{ID}",$datosUsuario["ID_USUARIO"],$contenido_mail);

						$contenido_mail = str_replace("{CATEGORIA}",stripslashes($datosArchivo["CATEGORIA"]),$contenido_mail);
						$contenido_mail = str_replace("{TITULO}",stripslashes($datosArchivo["TITULO"]),$contenido_mail);
						$contenido_mail = str_replace("{DESCRIPCION}",stripslashes($datosArchivo["DESCRIPCION"]),$contenido_mail);
						$contenido_mail = str_replace("{OBJETIVOS}",stripslashes($datosArchivo["OBJETIVOS"]),$contenido_mail);
						$contenido_mail = str_replace("{CONTACTOS}",stripslashes($datosArchivo["CONTACTOS"]),$contenido_mail);

						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress($datosUsuario["EMAIL"]);
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido_mail;
						if($mail->Send()) {
							$contenido_mostrar .= "<div class=\"alert alert-success\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria la carrera: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, y se ha enviado un mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\">";
							$contenido_mostrar .= "	<a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a>";
							$contenido_mostrar .= "	<p>Se ha eliminado de forma satisfactoria la carrera: <strong>&quot;".stripslashes($datosArchivo["TITULO"])."&quot;</strong>, pero se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el mensaje de aviso a la dirección de correo electrónico: <strong>".$datosUsuario["EMAIL"]."</strong>.</p>";
							$contenido_mostrar .= "</div>";
						}
					}
					
					$ArchivoHTML = $sitio_url."html/usuarios_carreras.php".$url."&id=".$_SESSION['usuario_id'];
					$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
					$contenido_mostrar .= $ContenidoArchivoHTML;

					break;
				case "buscar":
					$titulo_sitio = "buscar | ".$sitio_nombre;
					$registros = $conexion->Query("SELECT contenidos.*,usuarios.NOMBRE_COMPLETO FROM contenidos LEFT JOIN usuarios ON usuarios.id_usuario = contenidos.id_usuario WHERE (contenidos.activo = 1) and ((titulo like '%".$_GET['q']."%') or (contenido like '%".$_GET['q']."%') or (contenido like '%".htmlentities($_GET['q'])."%') or (contenido like '%".htmlspecialchars($_GET['q'])."%')) ORDER BY contenidos.fecha_alta DESC,contenidos.id_contenido DESC");
					$contenido_mostrar = "";
					$contenido_mostrar .= "<h1>Buscador</h1>";
					if($registros) {
						if($registros->RecordCount() > 0) {
							$contenido_mostrar .= "<p>Se han encontrado <strong>".$registros->RecordCount()."</strong> coincidencias para \"<strong>".$_GET['q']."</strong>\": </p>";
							while($registros and !$registros->EOF) {
								//	elimino todas las marcas de IMAGENES
								$datos = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
								if($datos["CANTIDAD"] > 0) {
									for($i=1; $i<$datos["CANTIDAD"]; $i++) {
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
								$registros->fields["TITULO"] = str_replace($_GET['q'],"<span style='color: #ff0000;font-weight: bold;'>".$_GET['q']."</span>",$registros->fields["TITULO"]);
								$registros->fields["TITULO"] = str_replace(strtolower($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtolower($_GET['q'])."</span>",$registros->fields["TITULO"]);
								$registros->fields["TITULO"] = str_replace(strtoupper($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".strtoupper($_GET['q'])."</span>",$registros->fields["TITULO"]);
								$registros->fields["TITULO"] = str_replace(ucfirst($_GET['q']),"<span style='color: #ff0000;font-weight: bold;'>".ucfirst($_GET['q'])."</span>",$registros->fields["TITULO"]);

								$registros2 = $conexion->GetRow("SELECT * FROM menu_modulos WHERE url = 'contenidos.php?menu=".$registros->fields["TIPO_CONTENIDO"]."'");
								$registros3 = $conexion->GetRow("SELECT count(*) as Cantidad  FROM mensajes WHERE (tipo_mensaje = 'comentarios') and (tipo_cabeza = '".$registros->fields["TIPO_CONTENIDO"]."') and (id_cabeza = '".$registros->fields["ID_CONTENIDO"]."') and (activo = 1)");

								$contenido_mostrar .= "<blockquote>\n";
								$contenido_mostrar .= "	<h4><a href='./?menu=".$registros->fields["TIPO_CONTENIDO"]."&id=".$registros->fields["ID_CONTENIDO"]."'>".$registros->fields["TITULO"]."</a></h4>\n";
								$contenido_mostrar .= "	<p style='font-size: 12px;line-height: 18px;margin-bottom: 10px;'>".$frase_buscar."</p>\n";
								if($registros->fields["TIPO_CONTENIDO"] == "blog") {
									$contenido_mostrar .= "	<small style='border-top: 1px solid #ddd;'>Publicado el ".formato_fecha($registros->fields["FECHA_ALTA"],3)." - Por <a href='./?menu=autor&id=".$registros->fields["ID_USUARIO"]."'>".$registros->fields["NOMBRE_COMPLETO"]."</a> - En <a href='./?menu=".$registros->fields["TIPO_CONTENIDO"]."&id=".$registros->fields["ID_CONTENIDO"]."'>".$registros2["NOMBRE_MENU"]."</a> - Leido ".$registros->fields["CLICK"]." veces - Comentado ".$registros3["Cantidad"]." veces</small>\n";
								} else {
									$contenido_mostrar .= "	<small style='border-top: 1px solid #ddd;'>Publicado el ".formato_fecha($registros->fields["FECHA_ALTA"],3)." - En <a href='./?menu=".$registros->fields["TIPO_CONTENIDO"]."&id=".$registros->fields["ID_CONTENIDO"]."'>".$registros2["NOMBRE_MENU"]."</a> - Leido ".$registros->fields["CLICK"]." veces - Comentado ".$registros3["Cantidad"]." veces</small>\n";
								}
								$contenido_mostrar .= "</blockquote>\n";
								$registros->MoveNext();	
							}
							$registros->Close();
						} else {
							$contenido_mostrar .= "<p>No se han encontrado coincidencias para \"<strong>".$_GET['q']."</strong>\": </p>";
						}
					} else {
						$contenido_mostrar .= "<p>No se han encontrado coincidencias para \"<strong>".$_GET['q']."</strong>\": </p>";
					}
					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido_mostrar,$ContenidoHTML);			
					break;
				case "alta":
					if(isset($_GET['curso'])) {
						$titulo_sitio = "Confirmaci&oacute;n de inscripci&oacute;n | ".$sitio_nombre;
						$contenido = "<h1>Confirmaci&oacute;n de inscripci&oacute;n</h1>";

						if(isset($_GET['id'])) {
							//	busco al usuarios segun el ID y CURSO
							$registros = $conexion->Query("SELECT usuarios.* FROM usuario_seinscribe_curso LEFT JOIN usuarios ON usuarios.id_usuario = usuario_seinscribe_curso.id_usuario WHERE (usuario_seinscribe_curso.id_usuario = '".$_GET['id']."') and (id_curso = '".$_GET['curso']."')");
							//	verifico si hay resuldados
							if($registros) {
								if($registros->RecordCount() > 0) {
									//	busco al curso segun el CURSO
									$datosCurso = $conexion->GetRow("SELECT * FROM cursos WHERE (id_curso = '".$_GET['curso']."')");
									while(!$registros->EOF) {
										//	verifico si ya confirmo su inscripcion
										if($registros->fields["ACTIVO"] == 1) {
											$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>La dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong> ya ha sido confirmado con anterioridad.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
										} else {
											//	actualizo la inscripcion
											$comentarios = "<p>Confirmaci&oacute;n de inscripci&oacute;n realizada el ".date("d/m/Y H:i:s")." al Curso &nbsp;".$datosCurso["NOMBRE"]."&nbsp;</p>".stripslashes($registros->fields["COMENTARIOS"]);
											$temp->Query("UPDATE usuarios SET activo = 1, comentarios = '".addslashes($comentarios)."' WHERE (id_usuario = '".$_GET['id']."') and (activo = 0)");

											$contenido .= "<p><strong>".$registros->fields["NOMBRE_COMPLETO"]."</strong>, gracias por confirmar tu inscripci&oacute;n al Curso: &quot;".$datosCurso["NOMBRE"]."&quot; con tu dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong>.</p>";
											$contenido .= "<p>Gracias por inscribirte a ".$sitio_nombre.".</p>";
											$contenido .= "<p>&nbsp;</p>";
											$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
										}
										$registros->MoveNext();
									}
									$registros->Close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID o CURSO suministrado no es existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
						}						
					} else {						
						$titulo_sitio = "Confirmaci&oacute;n de suscripci&oacute;n | ".$sitio_nombre;
						$contenido = "<h1>Confirmaci&oacute;n de suscripci&oacute;n</h1>";

						if(isset($_GET['id'])) {
							//	busco al usuarios segun el ID
							$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['id']."')");
							//	verifico si hay resuldados
							if($registros) {
								if($registros->RecordCount() > 0) {
									while(!$registros->EOF) {
										//	verifico si ya confirmo su suscripcion
										if($registros->fields["ACTIVO"] == 1) {
											$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>La dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong> ya ha sido confirmado con anterioridad.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
										} else {
											//	actualizo la suscripcion
											$comentarios = "<p>Confirmaci&oacute;n de suscripci&oacute;n realizada el: ".date("d/m/Y H:i:s")."</p>".stripslashes($registros->fields["COMENTARIOS"]);
											$temp->Query("UPDATE usuarios SET activo = 1, comentarios = '".addslashes($comentarios)."' WHERE (id_usuario = '".$_GET['id']."') and (activo = 0)");

											//	envio el mail para la descarga
											$Htmlfile = $sitio_url."xpanel/templates/"."enlace_descargarregalo.html";

											//$contenido_mail = file_get_contents($Htmlfile);
											$contenido_mail = obtenerDatosArchivo($Htmlfile);
											$titulo = $registros->fields["NOMBRE_COMPLETO"].", tu regalo por bienvenida a ".$sitio_nombre."!";
											$contenido_mail = str_replace("{FECHA}",date("d/m/Y"),$contenido_mail);

											$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

											$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
											$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

											//	obtengo el logo para boletin
											if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
												$contenido_mail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido_mail);
											} else {
												$contenido_mail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido_mail);
											}

											$contenido_mail = str_replace("{NOMBRE}",$registros->fields["NOMBRE_COMPLETO"],$contenido_mail);
											$contenido_mail = str_replace("{EMAIL}",$registros->fields["EMAIL"],$contenido_mail);
											$contenido_mail = str_replace("{ID}",$registros->fields["ID_USUARIO"],$contenido_mail);

											$mail = new PHPMailer();
											$mail->ConfirmReadingTo = $sitio_email_confirmacion;
											$mail->From				= $sitio_email;
											$mail->FromName			= $sitio_nombre;
											$mail->Subject			= $titulo;
											$mail->IsHTML(true);
											$mail->ClearAddresses();
											$mail->AddAddress($registros->fields["EMAIL"]);
											$mail->AddBCC($sitio_email);
											$mail->AddBCC($sitio_email_admin);
											$mail->Body				= $contenido_mail;
											if($mail->Send()) {
												$contenido .= "<p><strong>".$registros->fields["NOMBRE_COMPLETO"]."</strong>, gracias por confirmar tu dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong>, en unos instantes recibiras un mensaje con el enlace para descargar tu regalo.</p>";
												$contenido .= "<div class=\"alert alert-info\"><h3>Atención</h3><p>Se ha enviado el enlace de descarga a la dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong>.</p></div>";
												$contenido .= "<p>Gracias por suscribirte a ".$sitio_nombre.".</p>";
												$contenido .= "<p>&nbsp;</p>";
												$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
											} else {
												$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: ".htmlentities($mail->ErrorInfo)."</strong>, al intentar enviar el enlace de descarga a la dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong>.</p></div>";
												$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:$sitio_email\">$sitio_email</a>, a fin de evitar futuros inconvenientes.</p>";
												$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
											}
										}
										$registros->MoveNext();
									}
									$registros->Close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID suministrado no es existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
						}
					}

					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
					break;
				case "baja":
					if(isset($_GET['curso'])) {
						$titulo_sitio = "Eliminar inscripci&oacute;n | ".$sitio_nombre;
						$contenido = "<h1>Eliminar inscripci&oacute;n</h1>";

						if(isset($_GET['id'])) {
							//	busco al usuarios segun el ID y CURSO
							$registros = $conexion->Query("SELECT usuarios.* FROM usuario_seinscribe_curso LEFT JOIN usuarios ON usuarios.id_usuario = usuario_seinscribe_curso.id_usuario WHERE (usuario_seinscribe_curso.id_usuario = '".$_GET['id']."') and (id_curso = '".$_GET['curso']."')");
							//	verifico si hay resuldados
							if($registros) {
								if($registros->RecordCount() > 0) {
									//	busco al curso segun el CURSO
									$datosCurso = $conexion->GetRow("SELECT * FROM cursos WHERE (id_curso = '".$_GET['curso']."')");
									while(!$registros->EOF) {
										//	actualizo la inscripcion
										$comentarios = "<p>Eliminación de inscripción realizada el ".date("d/m/Y H:i:s")." al Curso &nbsp;".$datosCurso["NOMBRE"]."&nbsp;</p>".stripslashes($registros->fields["COMENTARIOS"]);
										$conexion->Execute("DELETE FROM usuario_seinscribe_curso WHERE (id_usuario = '".$_GET['id']."') and (id_curso = '".$_GET['curso']."')");
										$temp->Query("UPDATE usuarios SET activo = 0, comentarios = '".addslashes($comentarios)."' WHERE (id_usuario = '".$_GET['id']."')");

										$contenido .= "<p><strong>".$registros->fields["NOMBRE_COMPLETO"]."</strong>, gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										$contenido .= "<div class=\"alert alert-info\"><h3>Atención</h3><p>Se ha eliminado la inscripción realizada con la dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong> al Curso &nbsp;".$datosCurso["NOMBRE"]."&nbsp;.</p></div>";
										$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										$contenido .= "<p>&nbsp;</p>";
										$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
										$contenido .= "<p>&nbsp;</p>";

										$registros->MoveNext();
									}
									$registros->Close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID o CURSO suministrado no es existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";										
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";									
						}
					} else {						
						$titulo_sitio = "Eliminar suscripci&oacute;n | ".$sitio_nombre;
						$contenido = "<h1>Eliminar suscripci&oacute;n</h1>";
						if(isset($_GET['id'])) {
							//	busco al usuarios segun el ID
							$registros = $conexion->Query("SELECT * FROM usuarios WHERE (email = '".$_GET['id']."')");
							//	verifico si hay resuldados
							if($registros) {
								if($registros->RecordCount() > 0) {
									while(!$registros->EOF) {
										//	acutalizo la suscripcion
										$comentarios = "<p>Eliminación de suscripción realizada el: ".date("d/m/Y H:i:s")."</p>".stripslashes($registros->fields["COMENTARIOS"]);
										$temp->Query("UPDATE usuarios SET activo = 0, comentarios = '".addslashes($comentarios)."' WHERE (email = '".trim($_GET['id'])."')");

										$contenido .= "<p><strong>".$registros->fields["NOMBRE_COMPLETO"]."</strong>, gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										$contenido .= "<div class=\"alert alert-info\"><h3>Atención</h3><p>Se ha eliminado la suscripción realizada con la dirección de correo electrónico: <strong>".$registros->fields["EMAIL"]."</strong>.</p></div>";
										$contenido .= "<p style=\"color: #808080;\">Para darte de ALTA automáticamente de nuestra lista, en caso de haber eliminado la suscripciòn por error, basta con que hagas clic sobre el siguiente link: <a href=\"".$sitio_url."?menu=alta&id=".$registros->fields["ID_USUARIO"]."\">".$sitio_url."?menu=alta&id=".$registros->fields["ID_USUARIO"]."</a>.</p>";
										$contenido .= "<p>Gracias por suscribirte a ".$sitio_nombre.".</p>";
										$contenido .= "<p>&nbsp;</p>";
										$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
										$contenido .= "<p>&nbsp;</p>";

										$registros->MoveNext();
									}
									$registros->Close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>La dirección de correo electrónico (".$_GET['id'].") suministrado no es existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";										
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p>La dirección de correo electrónico suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";									
						}
					}

					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
					break;
				case "descargar":
					$titulo_sitio = "Descargar regalo | ".$sitio_nombre;
					$contenido = "<h1>Descargar regalo</h1>";

					if(isset($_GET['id'])) {
						//	busco al usuarios segun el ID
						$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['id']."')");
						//	verifico si hay resuldados
						if($registros) {
							if($registros->RecordCount() > 0) {
								while(!$registros->EOF) {
									//	verifico si ya descarglo el regalo
									if($registros->fields["DESCARGO_REGALO"] == 1) {
										$contenido .= "<div class=\"alert alert-error\"><h3>Atención</h3><p><strong>".$registros->fields["NOMBRE_COMPLETO"]."</strong> ya has descargado el regalo titulado <strong>\"Modelo de Plan de negocios online\"</strong>.</p></div>";
										$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
										$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
									} else {

										//	actualizo la descarga
										$comentarios = "<p>Descargalo regalo <strong>\"Modelo de Plan de negocios online\"</strong> el: ".date("d/m/Y H:i:s")."</p>".stripslashes($registros->fields["COMENTARIOS"]);
										$temp->Query("UPDATE usuarios SET descargo_regalo = 1, comentarios = '".addslashes($comentarios)."' WHERE (id_usuario = '".$_GET['id']."') and (descargo_regalo = 0)");

										//$contenido .= "<script src=\"descargarRegalo.php\" type=\"text/javascript\"></script>";
										include("descargarRegalo.php");
										
										$contenido .= "<p>Gracias por descargar el regalo titulado <strong>\"Modelo de Plan de negocios online\"</strong> de ".$sitio_nombre.".</p>";
										$contenido .= "<p>&nbsp;</p>";
										$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
										$contenido .= "<p>&nbsp;</p>";
									}
									$registros->MoveNext();
								}
								$registros->Close();
							} else {
								$contenido .= "<div class=\"alert alert-error\">";
								$contenido .= "	<h3>Atención</h3>";
								$contenido .= "	<p>El ID suministrado no es existe.</p>";
								$contenido .= "</div>";
								$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
								$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
							}
						}
					} else {
						$contenido .= "<div class=\"alert alert-error\">";
						$contenido .= "	<h3>Atención</h3>";
						$contenido .= "	<p>El ID suministrado no es correcto</p>";
						$contenido .= "</div>";
						$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
						$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";								
					}

					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
					break;
				case "suscripcion":
					$titulo_sitio = "Registrando suscripci&oacute;n | ".$sitio_nombre;
					$contenido = "<h1>Registrando suscripci&oacute;n</h1>";

					if(isset($_POST['btEnviarSuscripcion'])) {
						//	verifico si ya esta suscripto
						$registros = $conexion->Query("SELECT * FROM usuarios WHERE email = '".$_POST['email']."'");
						if($registros->RecordCount() > 0) {
							while(!$registros->EOF) {
								$contenido .= "<div class=\"alert alert-error\">";
								$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";										
								$contenido .= "	<p>Usted ya se ha suscripto a nuestro boletin con la direcci&oacute;n de correo electr&oacute;nico: <strong>".$registros->fields["EMAIL"]."</strong> con las siguientes observaciones:</p><br />";
								$contenido .= "	<blockquote>".stripslashes($registros->fields["COMENTARIOS"])."</blockquote>";
								$contenido .= "</div>";
								$contenido .= "<p>Si posee alguna duda o consulta al respecto, por favor, contactarse con la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
								$registros->MoveNext();
							}
							$registros->Close();
						} else {
							
							//	registro la suscripcion
							$comentarios = "<p>Suscripci&oacute;n realizada el: ".date("d/m/Y H:i:s")."</p>";
							$conexion->Query("INSERT INTO usuarios (ID_USUARIO,TIPO_DOC,NRO_DOC,NOMBRE,APELLIDOS,NOMBRE_COMPLETO,CODIGO_PAIS,EMAIL,TIPO_USUARIO,USUARIO,CLAVE,FECHA_ALTA,ACTIVO,COMENTARIOS) VALUES(null,'DNI','0','".addslashes($_POST['nombre'])."','','".addslashes($_POST['nombre'])."','".$_POST['codigo_pais']."','".$_POST['email']."','Suscriptor','".$_POST['email']."','".generarPassword()."','".date("Y-m-d")."','0','".addslashes($comentarios)."')");
							$id_registro = $conexion->Insert_ID();
							$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_suscripcion.html";

							//obtengo los datos de la plantilla 
							if(verificar_url($Htmlfile)) {
								//$contenidoMail = file_get_contents($Htmlfile);
								$contenidoMail = obtenerDatosArchivo($Htmlfile);
								$titulo = substr($contenidoMail, strpos($contenidoMail, "<title>") + 7, (strpos($contenidoMail, "</title>") - (strpos($contenidoMail, "<title>") + 7)));
								$contenidoMail = str_replace("{FECHA}",date("d/m/Y"),$contenidoMail);

								$contenidoMail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenidoMail);
								$contenidoMail = str_replace("{SITIO_URL}", $sitio_url, $contenidoMail);
								$contenidoMail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenidoMail);

								$contenidoMail = str_replace("{PUBLICIDAD1}", "", $contenidoMail);
								$contenidoMail = str_replace("{PUBLICIDAD2}", "", $contenidoMail);

								//	obtengo el logo para boletin
								if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
									$contenidoMail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenidoMail);
								} else {
									$contenidoMail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenidoMail);
								}

								$contenidoMail = str_replace("{NOMBRE}",$_POST['nombre'],$contenidoMail);
								$contenidoMail = str_replace("{EMAIL}",$_POST['email'],$contenidoMail);
								$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$_POST['codigo_pais']."')");
								$contenidoMail = str_replace("{PAIS}",$pais["PAIS"],$contenidoMail);
								$url = "./?menu=".$_POST['tipo_cabeza']."&id=".$_POST['id_cabeza'];
								$contenido = str_replace("{URL}",$url,$contenido);

								//notifico al administrador
								$mail = new PHPMailer();	
								$mail->ConfirmReadingTo = $sitio_email_confirmacion;
								$mail->From				= $sitio_email;
								$mail->FromName			= $sitio_nombre;
								$mail->Subject			= $titulo;
								$mail->IsHTML(true);
								$mail->ClearAddresses();
								$mail->AddBCC($sitio_email);
								$mail->AddBCC($sitio_email_admin);
								$mail->Body				= $contenidoMail;
								if(!$mail->Send()) {
									$contenido .= "<div class=\"alert alert-success\">";
									$contenido .= "	<h4 class=\"alert-heading\">&iexcl;Gracias por suscribirte a ".$sitio_nombre."!</h4>";
									$contenido .= "	<p>Usted recibir&aacute; un mensaje de confirmaci&oacute;n de su direcci&oacute;n de correo electr&oacute;nico, de parte de <strong>".$sitio_email."</strong>.</p>";
									$contenido .= "	<p>Este proceso es necesario para asegurar su privacidad, de que usted se suscribi&oacute; voluntariamente a mi boletin de Informaci&oacute;n y Negocios.</p>";
									$contenido .= "</div>";
									$contenido .= "<p>Si utiliza Hotmail, Yahoo, Gmail u otro detector de SPAM, No olvide agregar a su lista blanca de contactos <strong>".$sitio_email."</strong>.</p>";

									//	envio el mail solicitando confirmacion
									$Htmlfile = $sitio_url."xpanel/templates/"."confirmacion_suscripcion.html";

									//$contenidoMail = file_get_contents($Htmlfile);
									$contenidoMail = obtenerDatosArchivo($Htmlfile);
									$titulo = $_POST['nombre'].", ¡confirma tu registración a ".$sitio_nombre."!";
									$contenidoMail = str_replace("{FECHA}",date("d/m/Y"),$contenidoMail);

									$contenidoMail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenidoMail);
									$contenidoMail = str_replace("{SITIO_URL}", $sitio_url, $contenidoMail);
									$contenidoMail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenidoMail);

									$contenidoMail = str_replace("{PUBLICIDAD1}", "", $contenidoMail);
									$contenidoMail = str_replace("{PUBLICIDAD2}", "", $contenidoMail);

									//	obtengo el logo para boletin
									if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
										$contenidoMail = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenidoMail);
									} else {
										$contenidoMail = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenidoMail);
									}

									$contenidoMail = str_replace("{NOMBRE}",$_POST['nombre'],$contenidoMail);
									$contenidoMail = str_replace("{EMAIL}",$_POST['email'],$contenidoMail);
									$contenidoMail = str_replace("{ID}",$id_registro,$contenidoMail);

									$mail->ConfirmReadingTo = $sitio_email_confirmacion;
									$mail->From				= $sitio_email;
									$mail->FromName			= $sitio_nombre;
									$mail->Subject			= $titulo;
									$mail->IsHTML(true);
									$mail->ClearAddresses();
									$mail->AddAddress($_POST['email']);
									$mail->AddBCC($sitio_email);
									$mail->AddBCC($sitio_email_admin);
									$mail->Body				= $contenidoMail;
									if($mail->Send()) {
										$contenido .= "<div class=\"alert alert-error\">";
										$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";										
										$contenido .= "	<p>Se ha enviado la solicitud de confirmaci&oacute;n de suscripci&oacute;n a la direcci&oacute;n de correo electr&oacute;nico: <strong>".$_POST['email']."</strong>.</p>";
										$contenido .= "</div>";
									} else {
										$contenido .= "<div class=\"alert alert-error\">";
										$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";										
										$contenido .= "	<p>Se ha obtenido el siguiente mensaje <span style=\"color: #FF0000\"><strong>Error:</strong> ".htmlentities($mail->ErrorInfo)."</span>, al intentar enviar la solicitud de confirmaci&oacute;n de suscripci&oacute;n.</p>";
										$contenido .= "	<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
										$contenido .= "</div>";
									}

								} else {
									$contenido .= "<div class=\"alert alert-error\">";
									$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";										
									$contenido .= "	<p>Se ha obtenido el siguiente mensaje <span style=\"color: #FF0000\"><strong>Error:</strong> ".htmlentities($mail->ErrorInfo)."</span>, al intentar enviar el aviso de suscripci&oacute;n.</p>";
									$contenido .= "	<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:".$sitio_email."\">".$sitio_email."</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "</div>";
								}
							}
						}	//	fin de verificar si ya esta suscripto								
						$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						$contenido .= "<p style=\"text-align: right;\"><strong>".$sitio_nombre."</strong><br /><em>".$sitio_eslogan."</em></p>";
					}

					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
					break;
				case "fotogaleria2":
					if(isset($_GET['cat'])) {
						$titulo_sitio = $_GET['cat']." | ".$sitio_nombre;
						$contenido = "<h1>".$_GET['cat']."</h1>";

						//	busco la imagenes de la CATEGORIA
						$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (categoria = '".$_GET['cat']."') ORDER BY id_foto");
						//	verifico si hay resuldados
						if($registros) {
							if($registros->RecordCount() > 0) {
								$contenido .= "<ul class=\"thumbnails\">\n";
								while(!$registros->EOF) {
									//	verifico si el archivo existe
									$nombre_completo_imagenoriginal = $path_imagenes."fotogaleria/".$registros->fields["NOMBRE_ARCHIVO"];
									if(file_exists($nombre_completo_imagenoriginal)) {
										//	obtengo el nombre y extension del archivo small
										$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
										$nombre = $res[0];
										$extension = $res[count($res)-1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = $path_imagenes."fotogaleria/".$nombre."_small.".$extension;
										if(file_exists($nombre_completo_imagensmall)) {
											$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
										}
									} else {
										$nombre_completo_imagenoriginal = $path_imagenes."fotogaleria/foto.gif";
									}											
									$contenido .= "<li class=\"span3\">";
									if($registros->fields["DESCRIPCION"] == null){
										$contenido .= "<a href=\"".$nombre_completo_imagenoriginal."\" class=\"thumbnail\" rel=\"colorbox\" title=\"".stripslashes($registros->fields["CATEGORIA"])."\">";
									} else {
										$contenido .= "<a href=\"".$nombre_completo_imagenoriginal."\" class=\"thumbnail\" rel=\"colorbox\" title=\"".stripslashes($registros->fields["DESCRIPCION"])."\">";
									}
									$contenido .= "	<img src=\"".$nombre_completo_imagenoriginal."\" border=\"0\" alt=\"\" />";
									$contenido .= "</a>";
									$contenido .= "<div class=\"caption\">".stripslashes($registros->fields["DESCRIPCION"])."</div>";											
									$contenido .= "</li>\n";
									$registros->MoveNext();
									$nombre_completo_imagenoriginal = "";
								}
								$registros->Close();
								$contenido .= "</ul>";
							}
						}
					} else {
						//	Muestro la lista de categorias de imagenes
						$contenido = "<h1>Categorias de imágenes</h1>";
					}

					$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
					break;
				default:
					$titulo_sitio = "error | ".$sitio_nombre;
					$path .= " &raquo; error";

					$contenido_mostrar .= "		<div class=\"alert alert-error\" style=\"margin: 20px auto;\"><h3>Atención</h3><p><strong>".ucfirst($_GET['menu'])."</strong> no es un menu de contenido</p></div>";
					break;
			}
			$contenido_mostrar .= "	</div>";
			$contenido_mostrar .= "	<div class=\"span3\">";
			$contenido_mostrar .= "		<div style=\"text-align: center;margin: 0px;padding-top: 10px;\">";
			$contenido_mostrar .= "			<p><strong>siguenos en</strong></p>";
			$contenido_mostrar .= "			<ul class=\"pager\">";
					if(isset($sitio_url_facebook) and ($sitio_url_facebook != "")) {
			$contenido_mostrar .= "				<li><a href=\"".$sitio_url_facebook."\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook.png\" width=\"32\" alt=\"\" border=\"0\" /></a></li>";
					}
					if(isset($sitio_url_twitter) and ($sitio_url_twitter != "")) {
			$contenido_mostrar .= "				<li><a href=\"".$sitio_url_twitter."\" target=\"_blank\" alt=\"siguenos en twitter\" title=\"siguenos en twitter\"><img src=\"images/twitter.png\" width=\"32\" alt=\"\" border=\"0\" /></a></li>";
					}
					if(isset($sitio_url_linkedin) and ($sitio_url_linkedin != "")) {
			$contenido_mostrar .= "				<li><a href=\"".$sitio_url_linkedin."\" target=\"_blank\" alt=\"siguenos en linkedin\" title=\"siguenos en linkedin\"><img src=\"images/linkedin.png\" width=\"32\" alt=\"\" border=\"0\" /></a></li>";
					}
			$contenido_mostrar .= "			</ul>";
			$contenido_mostrar .= "		</div>";
			$contenido_mostrar .= "		<div style=\"text-align: center;margin: 20px -5px;\">";
			$contenido_mostrar .= "		{PUBLICIDAD_V}";
			$contenido_mostrar .= "		</div>";
			$contenido_mostrar .= "		<div id=\"google_translate_element\"></div>";
			$contenido_mostrar .= "		<script>";
			$contenido_mostrar .= "		function googleTranslateElementInit() {";
			$contenido_mostrar .= "		  new google.translate.TranslateElement({";
			$contenido_mostrar .= "			pageLanguage: 'es'";
			$contenido_mostrar .= "		  }, 'google_translate_element');";
			$contenido_mostrar .= "		}";
			$contenido_mostrar .= "		</script>";
			$contenido_mostrar .= "		<script src=\"http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit\"></script>";
			$contenido_mostrar .= "	</div>";
			$contenido_mostrar .= "</div>";

			$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido_mostrar,$ContenidoHTML);
		} else {
			$titulo_sitio = $_SESSION['usuario_nombre']." | ".$sitio_nombre;
			$contenido = "";
			
			//	Video de portada
			//$contenido .= "<div class=\"well\"><div id=\"container_video\">Cargando reproductor ...</div></div>\n";

			$contenido = "";
			$ArchivoHTML = $sitio_url."html/portada.php";
			$ContenidoArchivoHTML = obtenerDatosArchivo($ArchivoHTML);
			$contenido .= $ContenidoArchivoHTML;

			$ContenidoHTML = str_replace("{PUBLICIDAD_H}","{PUBLICIDAD_PORTADA}",$ContenidoHTML);			
			$ContenidoHTML = str_replace("{CONTENIDOS}",$contenido,$ContenidoHTML);			
			$ContenidoHTML = str_replace("{CATEGORIAS}","",$ContenidoHTML);

			$url = urlencode($sitio_url);
			$Compartir_Contenido = "";
			$Compartir_Contenido .= "<div style='margin: 10px -5px;text-align: center;'>";
			$Compartir_Contenido .= "	<iframe src='//www.facebook.com/plugins/like.php?href=".$url."&amp;send=false&amp;layout=box_count&amp;width=75&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=63' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:75px; height:63px;' allowTransparency='true'></iframe>";
			$Compartir_Contenido .= "	<a href='https://twitter.com/share' class='twitter-share-button' data-count='vertical' data-via='wirahola' data-lang='es'>Tweet</a><script type='text/javascript' src='//platform.twitter.com/widgets.js'></script>";
			$Compartir_Contenido .= "	<script type='text/javascript' src='https://apis.google.com/js/plusone.js'> {lang: 'es'} </script>";
			$Compartir_Contenido .= "	&nbsp;<g:plusone size='tall'></g:plusone>";
			$Compartir_Contenido .= "</div>";
			$ContenidoHTML = str_replace("{COMPARTIR}",$Compartir_Contenido,$ContenidoHTML);
		}

		$ContenidoHTML = str_replace("{PATH}",$path,$ContenidoHTML);

		//	obtengo el titulo de la plantilla
		$titulo_plantilla = substr($ContenidoHTML, strpos($ContenidoHTML, "<title>") + 7, (strpos($ContenidoHTML, "</title>") - (strpos($ContenidoHTML, "<title>") + 7)));
		$ContenidoHTML = str_replace($titulo_plantilla,$titulo_sitio,$ContenidoHTML);

		//	actualizo la publicidad
		$url = "";
		if(isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "s") and (verificar_url($sitio_url.$path_imagenes.$publicidad_portada_archivo))) {
			$url = "<img src='".$sitio_url.$path_imagenes.$publicidad_portada_archivo."' style='width: 100%;' />";
			if($publicidad_portada_enlace != "") { $url = "<a href='".$publicidad_portada_enlace."' border='0'>".$url."</a>"; }
		}
		$ContenidoHTML = str_replace("{PUBLICIDAD_PORTADA}",$url,$ContenidoHTML);

		$url = "";
		if(isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "s") and (verificar_url($sitio_url.$path_imagenes.$publicidad_h_archivo))) {
			$url = "<img src='".$sitio_url.$path_imagenes.$publicidad_h_archivo."' />";
			if($publicidad_h_enlace != "") { $url = "<a href='".$publicidad_h_enlace."' border='0'>".$url."</a>"; }
		} else {
			$url .= "<div class='row' style=\"background-color: #FFFFCC;height: 200px;width: 100%;border: 1px solid #eee;margin-left: 0px;margin-bottom: 20px;padding: 0px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;\">\n";
			$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
			$url .= "		<script type=\"text/javascript\">\n";
			$url .= "		google_ad_client = \"pub-4033818343503592\";\n";
			$url .= "		google_ad_slot = \"0809458744\";\n";
			$url .= "		google_ad_width = 200;\n";
			$url .= "		google_ad_height = 200;\n";
			$url .= "		</script>\n";
			$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
			$url .= "	</div>\n";
			$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
			$url .= "		<script type=\"text/javascript\">\n";
			$url .= "		google_ad_client = \"pub-4033818343503592\";\n";
			$url .= "		google_ad_slot = \"3474887345\";\n";
			$url .= "		google_ad_width = 200;\n";
			$url .= "		google_ad_height = 200;\n";
			$url .= "		</script>\n";
			$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
			$url .= "	</div>\n";
			$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
			$url .= "		<script type=\"text/javascript\">\n";
			$url .= "		google_ad_client = \"ca-pub-4033818343503592\";\n";
			$url .= "		google_ad_slot = \"1135461466\";\n";
			$url .= "		google_ad_width = 200;\n";
			$url .= "		google_ad_height = 200;\n";
			$url .= "		</script>\n";
			$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
			$url .= "	</div>\n";
			$url .= "</div>\n";
		}
		$ContenidoHTML = str_replace("{PUBLICIDAD_H}",$url,$ContenidoHTML);

		$url = "";
		if(isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "s") and (verificar_url($sitio_url.$path_imagenes.$publicidad_v_archivo))) {
			$url = "<img src='".$sitio_url.$path_imagenes.$publicidad_v_archivo."' />";
			if($publicidad_v_enlace != "") { $url = "<a href='".$publicidad_v_enlace."' border='0'>".$url."</a>"; }
		}
		$ContenidoHTML = str_replace("{PUBLICIDAD_V}",$url,$ContenidoHTML);

		//	obtengo la frase de la semana
		$msj = "";
		if((isset($frase) and ($frase != "")) and (isset($frase_autor) and ($frase_autor != ""))) {
        	$msj .= "<div class=\"alert\">";
			$msj .= "<h1>Frase de la semana</h1>";
			$msj .= "<p class=\"align-center\"><em>&quot;".$frase."&quot;</em></p>";
			$msj .= "<p class=\"align-right\"><strong>- ".$frase_autor."</strong></p>";
            $msj .= "</div>";
		}
		$ContenidoHTML = str_replace("{FRASE}",$msj,$ContenidoHTML);

		//	obtengo la direccion del sitio
		$msj = "";
		if(isset($sitio_direccion) and ($sitio_direccion != "")) {
        	$msj .= "<address>".$sitio_direccion."</address>";
		}
		$ContenidoHTML = str_replace("{DIRECCION}",$msj,$ContenidoHTML);

		//	actualizo los enlaces principales
		$url = " data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Consultas\" title=\"Dejar consulta\"";
		$ContenidoHTML = str_replace("{CONTACTOS}",$url,$ContenidoHTML);
		$url = "href='html/rss.php' title='Fuentes RSS'";
		$ContenidoHTML = str_replace("{RSS}",$url,$ContenidoHTML);			
		$ContenidoHTML = str_replace("{SITIO_EMAIL}",$sitio_email,$ContenidoHTML);			

		echo($ContenidoHTML);
	}
	$conexion->Close();
	$temp->Close();
	$temp2->Close();
	$temp3->Close();
	$temp4->Close();
	$privilegios->Close();
?>
