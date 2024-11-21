<?php
	// No almacenar en el cache del navegador esta página.
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             		// Expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// Siempre página modificada
	header("Cache-Control: no-cache, must-revalidate");           		// HTTP/1.1
	header("Pragma: no-cache");                                   		// HTTP/1.0

	require("../xpanel/config.php");
	//	verifico los parametros necesarios
	if(isset($_GET['idc']) and isset($_GET['ida'])) {
		//	verifico el contacto
		if($_GET['idc'] != "{EMAIL}") {
			//	obtengo los datos del contacto
			$registros = $conexion->Query("SELECT * FROM contactos WHERE email = '".$_GET['idc']."'");
			if($registros){
				//	verifico si es un contacto registrado
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
						$contacto = $registros->fields["ID_CONTACTO"];
						$registros->MoveNext();
					}
					$registros->close();
				} else {
					//	inserto el nuevo contacto
					$conexion->Execute("INSERT INTO contactos (ID_CONTACTO,EMAIL,GRUPO,ACTIVO) VALUES(null,'".$_GET['idc']."','Descarga Web','1')");
					$contacto = $conexion->Insert_ID();
				}
				//	verifico la cantidad de descargas que posea
				$registros2 = $conexion->Query("SELECT * FROM contactos_descargan WHERE (id_contacto = '".$contacto."') and (id_archivo = '".$_GET['ida']."')");
				if($registros2){
					if($registros2->RecordCount() >= 3) {
						//	mensaje a mostrar cuando supero la cantidad de descargas permitidas
						?>
<!doctype html>
<html lang="es" style="background-color: #fff;">
    <head>
		<meta charset="ISO-8859-1">
        <title>Descargar archivo</title>
        <link rel="stylesheet" href="../xpanel/images/xpanel.css" />
    </head>
    <body style="background-color: #fff;">
		<div class="alert-message error" style="width: 50%;margin: 0 auto;">
			<h3 style="color: #ffcc00">Error</h3>			
			<p><img src="../xpanel/images/exclamacion.png" border="0" alt="" title="" style="float: left;margin: 10px;"/><strong>&quot;Se supero la cantidad de descargas permitidas.&quot;</strong></p>
			<p>&nbsp;</p>
			<p>Si usted desea descargar un archivo, cuyo enlace se origina en alg&uacute;n mensaje enviado por <strong><?php echo($sitio_nombre); ?></strong> y obtiene este mensaje de error varias veces, por favor notificar este inconveniente a <a href="mailto:<?php echo($sitio_email); ?>" title="<?php echo($sitio_nombre); ?>"><?php echo($sitio_email); ?></a>, a fin de brindar un mejor servicio y evitar futuros inconvenientes.</p>
			<p>&nbsp;</p>
			<div class="actions">
				<input type="button" class="btn primary" value="Cerrar ventana" onclick="javascript:window.close();" />
			</div>
		</div>
    </body>
</html>
						<?php
					} else {
						//obtengo los datos del archivo
						$registros2 = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$_GET['ida']."') and (tipo_archivo = 'Archivo')");
						if($registros2){
							while(!$registros2->EOF) {
								$enlace = $path_boletin.$registros2->fields['NOMBRE_ARCHIVO'];
								header ("Content-Disposition: attachment; filename=".$registros2->fields['NOMBRE_ARCHIVO']."\n\n");
								header ("Content-Type: application/octet-stream");
								//header ("Content-Length: ".filesize($enlace));
								readfile($enlace);		
								$fecha = date("Y/m/d");
								$hora = date("H:i:s");
								$temp->Execute("INSERT INTO contactos_descargan VALUES('".$_GET['ida']."','".$contacto."', '".$fecha."','".$hora."')");		

								$registros2->MoveNext();
							}
							$registros2->close();
						}
						//mensaje de agradecimiento
						?>
<!doctype html>
<html lang="es" style="background-color: #fff;">
    <head>
		<meta charset="ISO-8859-1">
        <title>Descargar archivo</title>
        <link rel="stylesheet" href="../xpanel/images/xpanel.css" />
    </head>
    <body style="background-color: #fff;">
		<div class="alert-message error" style="width: 50%;margin: 0 auto;">
			<h3 style="color: #ffcc00">Informaci&oacute;n</h3>			
			<p><img src="../xpanel/images/informacion.png" border="0" alt="" title="" style="float: left;margin: 10px;"/><strong>&quot;Gracias por utilizar nuestro servicio de descarga segura.&quot;</strong></p>
			<p>&nbsp;</p>
			<?php
				$datos_registro = $conexion->Query("SELECT * FROM contactos_descargan WHERE (id_contacto = '".$contacto."') and (id_archivo = '".$_GET['ida']."')");
			?>
			<p>&quot;Le restan <?php echo(3-$datos_registro->RecordCount()); ?> descargas.&quot;</p>
			<p>&nbsp;</p>
			<div class="actions">
				<input type="button" class="btn primary" value="Cerrar ventana" onclick="javascript:window.close();" />
			</div>
		</div>
	</body>
</html>
						<?php
					}
				}
			}
		} else {
			//mensaje a mostrar cuando IDC es igual a {EMAIL}

			//obtengo los datos del archivo
			$datos_registro = $conexion->GetRow("SELECT * FROM archivos WHERE (id_archivo = '".$_GET['ida']."') and (tipo_archivo = 'Archivo')");
			if($datos_registro){
				?>
<!doctype html>
<html lang="es" style="background-color: #fff;">
    <head>
		<meta charset="ISO-8859-1">
        <title>Descargar archivo</title>
        <link rel="stylesheet" href="../xpanel/images/xpanel.css" />
		<script src="../xpanel/scripts/jquery/jquery-1.7.min.js"></script>
		<script src="../xpanel/scripts/h5f/h5f.js"></script>
    </head>
    <body style="background-color: #fff;">
		<form method="get" action="<?php echo($_SERVER['PHP_SELF']); ?>" id="signup" style="width: 50%;margin: 0 auto;">
			<div class="alert-message error">
				<h3 style="color: #ffcc00">Atención</h3>			
				<p><img src="../xpanel/images/exclamacion.png" border="0" alt="" title="" style="float: left;margin: 10px;"/><strong>&quot;No se posee la direcci&oacute;n de correo electr&oacute;nico de quien desea descargar el archivo.&quot;</strong></p>
				<p>&nbsp;</p>			
				<p>&nbsp;</p>			
			</div>
			<hr />
			<input type="hidden" name="ida" value="<?php echo($_GET['ida']); ?>" required />
			<div class="clearfix">
				<label>Nombre del Archivo</label>
				<div class="input">
					<input name="archivo" type="text" title="Archivo" value="<?php echo($datos_registro['NOMBRE_ARCHIVO']); ?>" size="50" maxlength="255" disabled="disabled" required />
				</div>
			</div>
			<div class="clearfix">
				<label>Correo Electr&oacute;nico</label>
				<div class="input">
					<input name="idc" type="text" title="Correo Electr&oacute;nico" value="" size="50" maxlength="255" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" required />
				</div>
			</div>
			<div class="actions">
				<input type="button" class="btn" value="Cerrar ventana" onclick="javascript:window.close();" />
				<input type="submit" class="btn primary" value="Descargar" />
			</div>
		</form>
    </body>
</html>
				<?php
			} else {
				?>
<!doctype html>
<html lang="es" style="background-color: #fff;">
    <head>
		<meta charset="ISO-8859-1">
        <title>Descargar archivo</title>
        <link rel="stylesheet" href="../xpanel/images/xpanel.css" />
    </head>
    <body style="background-color: #fff;">
		<div class="alert-message error" style="width: 50%;margin: 0 auto;">
			<h3 style="color: #ffcc00">Error</h3>			
			<p><img src="../xpanel/images/exclamacion.png" border="0" alt="" title="" style="float: left;margin: 10px;"/><strong>&quot;No se posee la direcci&oacute;n de correo electr&oacute;nico de quien desea descargar el archivo, y ademas, el parametro de archivo no existe.&quot;</strong></p>
			<p>&nbsp;</p>
			<p>Si usted desea descargar un archivo, cuyo enlace se origina en alg&uacute;n mensaje enviado por <strong><?php echo($sitio_nombre); ?></strong> y obtiene este mensaje de error varias veces, por favor notificar este inconveniente a <a href="mailto:<?php echo($sitio_email); ?>" title="<?php echo($sitio_nombre); ?>"><?php echo($sitio_email); ?></a>, a fin de brindar un mejor servicio y evitar futuros inconvenientes.</p>
			<p>&nbsp;</p>
			<div class="actions">
				<input type="button" class="btn primary" value="Cerrar ventana" onclick="javascript:window.close();" />
			</div>
		</div>
    </body>
</html>
				<?php			
			}
		}		
	} else {
		//mensaje a mostrar cuando no existe IDC e IDB
		?>
<!doctype html>
<html lang="es" style="background-color: #fff;">
    <head>
		<meta charset="ISO-8859-1">
        <title>Descargar archivo</title>
        <link rel="stylesheet" href="../xpanel/images/xpanel.css" />
    </head>
    <body style="background-color: #fff;">
		<div class="alert-message error" style="width: 50%;margin: 0 auto;">
			<h3 style="color: #ffcc00">Atenci&oacute;n</h3>			
			<p><img src="../xpanel/images/exclamacion.png" border="0" alt="" title="" style="float: left;margin: 10px;"/><strong>&quot;No se poseen todos los par&aacute;metros necesarios para poder descargar el archivo.&quot;</strong></p>
			<p>&nbsp;</p>
			<p>Si usted desea descargar un archivo, cuyo enlace se origina en alg&uacute;n mensaje enviado por <strong><?php echo($sitio_nombre); ?></strong> y obtiene este mensaje de error varias veces, por favor notificar este inconveniente a <a href="mailto:<?php echo($sitio_email); ?>" title="<?php echo($sitio_nombre); ?>"><?php echo($sitio_email); ?></a>, a fin de brindar un mejor servicio y evitar futuros inconvenientes.</p>
			<p>&nbsp;</p>
			<div class="actions">
				<input type="button" class="btn primary" value="Cerrar ventana" onclick="javascript:window.close();" />
			</div>
		</div>
    </body>
</html>
		<?php
	}
?>
