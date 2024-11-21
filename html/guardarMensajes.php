<!doctype html>
<html lang="es">
    <head>
		<meta charset="ISO-8859-1">
        <title>formComentarios</title>
    </head>
    <body>
	<?php 
		require("../xpanel/config.php");
		//	verifico el tipo de mensaje
		if($_POST['tipo_mensaje'] == "Comentarios") {
			$conexion->Query("INSERT INTO mensajes (ID_MENSAJE,TIPO_MENSAJE,NOMBRE,EMAIL,ASUNTO,MENSAJE,TIPO_CABEZA,ID_CABEZA,FECHA_ALTA,ACTIVO,CODIGO_PAIS,ID_SUCURSAL) VALUES(null,'".$_POST['tipo_mensaje']."','".addslashes(utf8_decode($_POST['nombre']))."','".trim($_POST['email'])."','".addslashes(utf8_decode($_POST['asunto']))."','".addslashes(nl2br(utf8_decode($_POST['mensaje'])))."', '".$_POST['tipo_cabeza']."', '".$_POST['id_cabeza']."', '".date("Y-m-d")."','1','".$_POST['codigo_pais']."',1)");
			//	obtengo el mail del autor y titulo del contenido
			$email_autor = "";
			$titulo_contenido = "";
			switch($_POST['tipo_cabeza']) {
				case "laempresa":
				case "novedades":
					$registros = $conexion->Query("SELECT TITULO FROM contenidos WHERE (id_contenido = '".$_POST['id_cabeza']."')");
					if($registros) {
						while(!$registros->EOF) {
							$titulo_contenido = addslashes($registros->fields["TITULO"]);
							$registros->MoveNext();
						}
						$registros->close();
					}
					$email_autor = "";
					break;
				case "productos":
					$registros = $conexion->Query("SELECT NOMBRE FROM productos WHERE (id_producto = '".$_POST['id_cabeza']."')");
					if($registros) {
						while(!$registros->EOF) {
							$titulo_contenido = addslashes($registros->fields["NOMBRE"]);
							$registros->MoveNext();
						}
						$registros->close();
					}
					break;
				default: 
					$registros = $conexion->Query("SELECT TITULO,EMAIL FROM contenidos LEFT JOIN usuarios ON usuarios.id_usuario = contenidos.id_usuario WHERE (id_contenido = '".$_POST['id_cabeza']."')");
					if($registros) {
						while(!$registros->EOF) {
							if(($registros->fields["EMAIL"] != "") or ($registros->fields["EMAIL"] != null)) {
								$email_autor = $registros->fields["EMAIL"];
							}
							$titulo_contenido = addslashes($registros->fields["TITULO"]);
							$registros->MoveNext();
						}
						$registros->close();
					}
					break;
			}

			$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$_POST['codigo_pais']."')");

			$Htmlfile = "notificacion_comentario.html";
			$campos = array(
				"fecha" => date("d/m/Y"),
				"tipo_mensaje" => utf8_decode($_POST['tipo_mensaje']),
				"nombre" => utf8_decode($_POST['nombre']),
				"email" => trim($_POST['email']),
				"pais" => $pais["PAIS"],
				"asunto" => stripslashes(utf8_decode($_POST['asunto'])),
				"mensaje" => $utf8_decode($_POST['mensaje']),
				"tipo_cabeza" => ucfirst($_POST['tipo_cabeza']),
				"titulo" => $titulo_contenido
			);
			$destinatario = array(trim($_POST['email']),$email_autor);
			$asunto = "[ ".utf8_decode($sitio_nombre)." ] "."Aviso de Comentario";
		} else {
			if($_POST['tipo_mensaje'] == "Consultas") {
				$conexion->Query("INSERT INTO mensajes (ID_MENSAJE,TIPO_MENSAJE,NOMBRE,EMAIL,ASUNTO,MENSAJE,FECHA_ALTA,ACTIVO,CODIGO_PAIS,ID_SUCURSAL) VALUES(null,'".$_POST['tipo_mensaje']."','".addslashes(utf8_decode($_POST['nombre']))."','".trim($_POST['email'])."','".addslashes(utf8_decode($_POST['asunto']))."','".addslashes(nl2br(utf8_decode($_POST['mensaje'])))."',  '".date("Y-m-d")."','1','".$_POST['codigo_pais']."',1)");

				$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$_POST['codigo_pais']."')");

				$Htmlfile = "notificacion_consulta.html";
				$campos = array(
					"fecha" => date("d/m/Y"),
					"tipo_mensaje" => utf8_decode($_POST['tipo_mensaje']),
					"nombre" => utf8_decode($_POST['nombre']),
					"email" => trim($_POST['email']),
					"pais" => $pais["PAIS"],
					"asunto" => stripslashes(utf8_decode($_POST['asunto'])),
					"mensaje" => utf8_decode($_POST['mensaje'])
				);
				$destinatario = array(trim($_POST['email']));
				$asunto = "[ ".utf8_decode($sitio_nombre)." ] "."Aviso de Consulta";
			}
		}

		$resultado = enviarMensaje($Htmlfile,$campos,$destinatario,$asunto,1);
		
		if($resultado == "OK"){
			?>
		<div class="alert alert-success" role="alert">
			<h4>&iexcl;Gracias por colaborar en <?php echo($sitio_nombre); ?>!</h4>
			<p>Se ha enviado de forma satisfactoria el aviso de registro del comentario y/o consulta, registrado por <strong><?php echo($_POST['nombre']); ?></strong>, con la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST['email']); ?></strong>.</p>
		</div>
			<?php
		} else {
			?>
		<div class="alert alert-danger" role="alert">
			<h4>Atenci&oacute;n</h4>
			<p>Se ha obtenido el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($resultado); ?></span>, al intentar enviar el aviso de registro del comentario y/o consulta.</p>
			<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href="mailto:<?php echo($sitio_email); ?>"><?php echo($sitio_email); ?></a>, a fin de evitar futuros inconvenientes.</p>
		</div>
			<?php
		}
		?>
		<p>Todos sus comentarios, consultas y/o sugerencias ser&aacute;n le&iacute;das y respondidas a la mayor brevedad posible, a trav&eacute;s de un mensaje de correo electr&oacute;nico de parte de <strong><?php echo($sitio_email); ?></strong>.</p>
		<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>
		<p style="text-align: right;"><strong><?php echo(($sitio_nombre)); ?></strong><br /><em><?php echo(($sitio_eslogan)); ?></em></p>
    </body>
</html>
