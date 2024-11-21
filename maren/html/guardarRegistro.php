<!doctype html>
<html lang="es">
    <head>
		<meta charset="ISO-8859-1">
        <title>formInscripcion</title>
    </head>
    <body>
	<?php 
		require("../xpanel/config.php");
		//	verifico si ya esta registrado
		$registros = $conexion->Query("SELECT EMAIL,COMENTARIOS,FECHA_ALTA FROM usuarios WHERE (email = '".trim($_POST['email'])."')");
		if($registros) {
			if($registros->RecordCount() > 0) {
				while(!$registros->EOF) {
					?>
			<div class="alert">
				<h3>Atenci&oacute;n</h3>
				<p>Usted ya se ha registrado en <strong><?php echo($sitio_nombre); ?></strong> con la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($registros->fields["EMAIL"]); ?></strong>, con las siguientes observaciones:</p><br />
				<blockquote><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></blockquote>
			</div>
			<p>Lo invitamos a iniciar sesi&oacute;n como usuario registrado, con el nombre de usuario y contrase&ntilde;a que se ha remitido a su direcci&oacute;n de correo electr&oacute;nico, el pasado <strong><?php echo(formato_fecha($registros->fields["FECHA_ALTA"],3)); ?></strong>, a fin de no duplicar su registro en <strong><?php echo($sitio_nombre); ?></strong>.</p>
			<p>Si posee alguna duda o consulta al respecto, por favor, contactarse con la direcci&oacute;n de correo electr&oacute;nico <a href="mailto:<?php echo($sitio_email); ?>"><?php echo($sitio_email); ?></a>, a fin de evitar futuros inconvenientes.</p>
					<?php
					$registros->MoveNext();
				}
				$registros->close();
			} else {				
				//	registro la suscripcion
				$_POST['nro_doc'] = sprintf("%07s",$_POST['nro_doc']); 
				$_POST['nombre'] = strtoupper(trim($_POST['nombre']));
				$_POST['apellidos'] = strtoupper(trim($_POST['apellidos']));
				$nombre_completo = addslashes($_POST['nombre'])." ".addslashes($_POST['apellidos']);
				$_POST['ciudad'] = strtoupper(trim($_POST['ciudad']));

				$comentarios = "<p>Registro realizado el ".date("d/m/Y H:i:s")."</p>";
				$passwordUsuario = generarPassword();

				$conexion->Query("INSERT INTO usuarios 
					(
						ID_USUARIO,
						COD_USUARIO,
						ID_SUCURSAL,
						CATEGORIA_USUARIO,
						TIPO_DOC,
						NRO_DOC,
						NOMBRE,
						APELLIDOS,
						NOMBRE_COMPLETO,
						FECHA_NACIMIENTO,
						SEXO,
						DOMICILIO,
						TELEFONO,
						TELEFONO_MOVIL,
						CIUDAD,
						CODIGO_PAIS,
						EMAIL,
						TIPO_USUARIO,
						USUARIO,
						CLAVE,
						CLAVE2,
						FECHA_ALTA,
						ACTIVO,
						COMENTARIOS,
						CREDITOS,
						GRATIS
					) VALUES(
						null,
						1,
						'NUEVO',
						'".$_POST['cod_usuario']."',
						'".$_POST['tipo_doc']."',
						'".$_POST['nro_doc']."',
						'".addslashes(utf8_decode($_POST['nombre']))."',
						'".addslashes(utf8_decode($_POST['apellidos']))."',
						'".$nombre_completo."',
						'".formato_fecha($_POST['fecha_nacimiento'], 4)."',
						'".$_POST['sexo']."',
						'".addslashes(utf8_decode($_POST['domicilio']))."',
						'".$_POST['telefono_fijo']."',
						'".$_POST['telefono_movil']."',
						'".addslashes(utf8_decode($_POST['ciudad']))."',
						'".$_POST['codigo_pais']."',
						'".trim($_POST['email'])."',
						'Cliente',
						'".trim($_POST['tipo_doc']).trim($_POST['nro_doc'])."',
						'".$passwordUsuario."', 
						md5('".$passwordUsuario."'),
						'".date("Y-m-d")."',
						'0',
						'".addslashes($comentarios)."',
						'0',
						'0'
					)");		
				$id_registro = $conexion->Insert_ID();
				$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_registro.html";

				//obtengo los datos de la plantilla 
				if(verificar_url($Htmlfile)) {
					$contenido = obtenerDatosArchivo($Htmlfile);
					$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
					$contenido = str_replace("{FECHA}",date("d/m/Y"),$contenido);

					$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);
					$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
					$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);

					$contenido = str_replace("{PUBLICIDAD1}", "", $contenido);
					$contenido = str_replace("{PUBLICIDAD2}", "", $contenido);

					//	obtengo el logo para boletin
					if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
						$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido);
					} else {
						$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
					}

					$contenido = str_replace("{NOMBRE}",utf8_decode($nombre_completo),$contenido);
					$contenido = str_replace("{EMAIL}",trim($_POST['email']),$contenido);
					$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$_POST['codigo_pais']."')");
					$contenido = str_replace("{PAIS}",$pais["PAIS"],$contenido);

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
					$mail->Body				= $contenido;
					if($mail->Send()) {
						?>
					<div class="alert">
						<h3>&iexcl;Gracias por tu registro en <?php echo($sitio_nombre); ?></h3>
						<p>Usted recibir&aacute; un mensaje de confirmaci&oacute;n en su direcci&oacute;n de correo electr&oacute;nico, de parte de <strong><?php echo($sitio_email); ?></strong>.</p>
						<p>Este proceso es necesario para asegurar su privacidad y de que usted se ha registrado voluntariamente a <strong><?php echo($sitio_nombre); ?></strong>.</p>
					</div>
					<p>Si utiliza Hotmail, Yahoo, Gmail u otro detector de SPAM, no olvide agregar a su lista blanca de contactos: <strong><?php echo($sitio_email); ?></strong>.</p>
						<?php
						//	envio el mail solicitando confirmacion
						$Htmlfile = $sitio_url."xpanel/templates/"."confirmacion_registro.html";

						$contenido = obtenerDatosArchivo($Htmlfile);
						$titulo = utf8_decode($nombre_completo).", ¡confirma tu registro!";
						$contenido = str_replace("{FECHA}",date("d/m/Y"),$contenido);

						$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);
						$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
						$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);

						$contenido = str_replace("{PUBLICIDAD1}", "", $contenido);
						$contenido = str_replace("{PUBLICIDAD2}", "", $contenido);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido);
						} else {
							$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
						}

						$contenido = str_replace("{NOMBRE}",utf8_decode($nombre_completo),$contenido);
						$contenido = str_replace("{EMAIL}",trim($_POST['email']),$contenido);
						$contenido = str_replace("{ID}",$id_registro,$contenido);

						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->ClearAddresses();
						$mail->AddAddress(trim($_POST['email']));
						$mail->AddBCC($sitio_email);
						$mail->AddBCC($sitio_email_admin);
						$mail->Body				= $contenido;
						if($mail->Send()) {
							?>
						<div class="alert">
							<h3>Atenci&oacute;n</h3>
							<p>Se ha enviado la solicitud de confirmaci&oacute;n de su registro a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo(trim($_POST['email'])); ?></strong>.</p>
						</div>
							<?php
						} else {
							?>
						<div class="alert">
							<h3>Atenci&oacute;n</h3>
							<p>Se ha obtenido el siguiente mensaje <span style="color: #FFFF00"><strong>Error:</strong> <?php echo(htmlentities($mail->ErrorInfo)); ?></span>, al intentar enviar la solicitud de confirmaci&oacute;n de su registro.</p>
							<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href="mailto:<?php echo($sitio_email); ?>"><?php echo($sitio_email); ?></a>, a fin de evitar futuros inconvenientes.</p>
						</div>
							<?php
						}

					} else {
						?>
					<div class="alert">
						<h3>Atenci&oacute;n</h3>
						<p>Se ha obtenido el siguiente mensaje <span style="color: #FFFF00"><strong>Error:</strong> <?php echo(htmlentities($mail->ErrorInfo)); ?></span>, al intentar enviar el aviso de registro.</p>
						<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href="mailto:<?php echo($sitio_email); ?>"><?php echo($sitio_email); ?></a>, a fin de evitar futuros inconvenientes.</p>
					</div>
						<?php
					}
				}
			}
		}
		?>
		<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>
		<p style="text-align: right;"><strong><?php echo($sitio_nombre); ?></strong><br /><em><?php echo($sitio_eslogan); ?></em></p>
	</body>
</html>