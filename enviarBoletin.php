<?php 
	//Autor: Walter E. Irahola
	//E-mail: wirahola@hotmail.com
	//Proyecto: Script para el envio de los boletines, por contacto o por grupos

	ini_set('max_execution_time','5000');
	ini_set ('error_reporting', E_ALL);
	require("xpanel/config.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="ISO-8859-1">
	<title>Boletines <?php echo($sitio_nombre); ?></title>
    <!--[if lt IE 9]>
      <script src="xpanel/scripts/html5.js"></script>
    <![endif]-->

	<script src="xpanel/scripts/jquery/jquery-1.7.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#hidepage").remove();
		});
	</script>
</head>

<body>
	<div id="hidepage">
		<div id="fade" style="display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;background: #000;z-index:1001;opacity:.75;-moz-opacity: 0.75;filter: alpha(opacity=75);"></div>
		<div id="light" style="position:absolute;width:250px;height:60px;left:50%;padding: 10px;margin-left:-100px;top:50%;margin-top:-100px;text-align: center;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;background: #ffffcc;border: 5px solid #900;-webkit-border-radius: 6px 6px;-moz-border-radius: 6px 6px;border-radius: 6px 6px;-webkit-box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);-moz-box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);color: #900;z-index:1002;"><img src="images/preloader.gif" align="bottom" border="0" alt="" /><br />Cargando pagina...<br />Por favor espere.<br /></div>
	</div>

<?php 
if(!isset($_GET['tipo'])) { $_GET['tipo'] = "grupo"; }

//verifico si vienen con ID de Boletin y TIPO de envio
if(isset($_GET['id']) and isset($_GET['tipo'])) {

	//	obtengo los datos del boletin
	$contenido = "";
	$datos = $conexion->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$_GET['id']."')");
	if($datos){
		//	actualizo las referencias del titulo
		$datos["TITULO"] = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $datos["TITULO"]);
		$datos["TITULO"] = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $datos["TITULO"]);
		$datos["TITULO"] = str_replace("{SITIO_URL}", $sitio_url, $datos["TITULO"]);
		$datos["TITULO"] = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $datos["TITULO"]);
		$datos["TITULO"] = str_replace("{SITIO_EMAIL}", $sitio_email, $datos["TITULO"]);
		$datos["TITULO"] = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $datos["TITULO"]);

		//	obtengo el boletin
		$contenido = crearBoletin2($datos["ID_BOLETIN"]);

		//	configuro las opciones para el envio del mail
		$mail = new PHPMailer();	
		$mail->ConfirmReadingTo = $sitio_email_confirmacion;
		$mail->From				= $sitio_email;
		$mail->FromName			= $sitio_nombre;
		$mail->Subject			= stripslashes($datos["TITULO"]);
		$mail->IsHTML(true);

		//	--------------------------------------------------------------------------------------
		//	Envio por grupo: envia al siguiente grupo que le toca recibir
		if(isset($_GET['tipo']) and ($_GET['tipo'] == "grupo")) {
			echo("\t<ol>\n");
			//	obtengo todos los grupos existentes
			$registros = $conexion->Query("SELECT GRUPO,count(*) as Cant FROM contactos WHERE (grupo <> '') and (grupo is not null) and (activo = 1) GROUP BY grupo ORDER BY grupo DESC");
			while(!$registros->EOF) {
				echo("\t\t<li>".$registros->fields["GRUPO"]." : ".$registros->fields["Cant"]);
				//	verifico si se envio al grupo actual
				$registros2 = $conexion->Query("SELECT GRUPO, FECHAHORA, count(*) as Cant FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (grupo = '".$registros->fields["GRUPO"]."') GROUP BY grupo ORDER BY fechahora");
				if(($registros2->RecordCount() > 0) and ($registros2->fields["Cant"] >= $registros->fields["Cant"])) { 
					echo(" [ Enviado el ".$registros2->fields["FECHAHORA"]." a ".$registros2->fields["Cant"]." contactos ]"); 
				} else {
					echo(" <strong>[ Enviando ahora : ".date("d/m/Y H:i:s")."]</strong>");

					$notificacion = "<p>Boletin enviado al GRUPO: <strong>".$registros->fields["GRUPO"]."</strong>, con los siguientes destinatarios:</p>";
					$notificacion .= "<ol>\n";
					//	es un grupo de contactos
					$registros3 = $conexion->Query("SELECT ID_CONTACTO,EMAIL,NOMBRE_COMPLETO FROM contactos WHERE (grupo = '".$registros->fields["GRUPO"]."') and (activo = 1)");
					while(!$registros3->EOF) {
						$notificacion .= "\t<li>".stripslashes($registros3->fields["NOMBRE_COMPLETO"])." - ";

						//	actualizo las referencias del ASUNTO
						$asunto 		= str_replace("{NOMBRE_COMPLETO}",stripslashes($registros3->fields["NOMBRE_COMPLETO"]),stripslashes($datos["TITULO"]));
						$asunto 		= str_replace("{NOMBRE}",stripslashes($registros3->fields["NOMBRE_COMPLETO"]),$asunto);
						$asunto 		= str_replace("{EMAIL}",trim($registros3->fields["EMAIL"]),$asunto);
						$asunto 		= str_replace("{ID}",$registros3->fields["ID_CONTACTO"],$asunto);
						$mail->Subject			= $asunto;

						//	actualizo las referencias del MENSAJE
						$mensaje 		= str_replace("{NOMBRE_COMPLETO}",stripslashes($registros3->fields["NOMBRE_COMPLETO"]),$contenido);
						$mensaje 		= str_replace("{NOMBRE}",stripslashes($registros3->fields["NOMBRE_COMPLETO"]),$mensaje);
						$mensaje 		= str_replace("{EMAIL}",trim($registros3->fields["EMAIL"]),$mensaje);
						$mensaje 		= str_replace("{ID}",$registros3->fields["ID_CONTACTO"],$mensaje);

						$mail->MsgHTML($mensaje);
						$mail->ClearAddresses();

						//	verifico si tiene mas de un correo
						if(strpos($registros3->fields["EMAIL"],";")) {
							$destinatario = explode(";",trim($registros3->fields["EMAIL"]));
							$notificacion .= "<ol>\n";
							foreach ($destinatario as $valor) {
								$mail->AddAddress($valor); 
								echo("\t<li>".$valor."</li>\n");
								$notificacion .= "<li>".$valor."</li>\n";
							}
							$notificacion .= "</ol>\n";
						} else {
							$mail->AddAddress(trim($registros3->fields["EMAIL"])); 								
							$notificacion .= trim($registros3->fields["EMAIL"])." ";
							echo(trim($registros3->fields["EMAIL"])." ");
						}

						if($mail->Send()) {	
							$notificacion .= "[OK]";
							//	registro el envio en la base de datos
							$conexion->Query("INSERT INTO contactos_reciben (ID_CONTACTO,ID_BOLETIN,FECHAHORA) values('".$registros3->fields["ID_CONTACTO"]."','".$datos["ID_BOLETIN"]."','".date("Y-m-d H:i:s")."')");
						} else { 
							$notificacion .= "<span style='color: red;'>[ERROR]: ".$mail->ErrorInfo."</span>"; 
						}
						$notificacion .= "</li>\n";

						$registros3->MoveNext();
					}
					$registros3->Close();
					$notificacion .= "</ol>\n";

					//	envio notificacion al administrador
					//$mail->From     = $sitio_email;
					//$mail->FromName = $sitio_nombre;
					$mail->Subject  = "[ Aviso ] » ".stripslashes($datos["TITULO"]);
					$mail->MsgHTML($contenido."<hr />".$notificacion);
					$mail->ClearAddresses();
					$mail->AddAddress($sitio_email_admin); 
					if($mail->Send()) {	
						//	actualizo la fecha del ultimo envio
						$conexion->Execute("UPDATE boletin SET fecha_envio = '".date("Y-m-d H:i:s")."' WHERE (id_boletin = '".$datos["ID_BOLETIN"]."')");	
					} 
					break;
				}
				echo("</li>");
				$registros->MoveNext();
			}
			$registros->Close();
			echo("\t</ol>\n");
		}

		//	--------------------------------------------------------------------------------------
		//	Envio por contacto: envia a todos los contactos de la base de datos,cada 140
		if(isset($_GET['tipo']) and ($_GET['tipo'] == "email")) {
			echo("\t<ol>\n");
			$notificacion = "<p>Boletin enviado a los siguientes destinatarios:</p>";
			$notificacion .= "<ol>";

			$registros = $conexion->Query("SELECT ID_CONTACTO,EMAIL,NOMBRE_COMPLETO FROM contactos WHERE (grupo <> '') and ((email <> '') and (email is not null)) and (activo = 1) GROUP BY email ORDER BY email");
			$i = 1;
			while(!$registros->EOF) {
				echo("\t\t<li>".$registros->fields["EMAIL"]);
				//	verifico si recibio el boletin
				$registros2 = $temp->Query("SELECT * FROM contactos_reciben WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (id_contacto = '".$registros->fields["ID_CONTACTO"]."')");
				if($registros2->RecordCount() > 0) {
					echo(" [ Enviado el ".$registros2->fields["FECHAHORA"]." ]"); 
				} else {
					echo(" <strong>[ Enviando ahora : ".date("d/m/Y H:i:s")."]</strong>");

					$notificacion .= "<li>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ";

					//	actualizo las referencias del ASUNTO
					$asunto 		= str_replace("{NOMBRE_COMPLETO}",stripslashes($registros->fields["NOMBRE_COMPLETO"]),stripslashes($datos["TITULO"]));
					$asunto 		= str_replace("{NOMBRE}",stripslashes($registros->fields["NOMBRE_COMPLETO"]),$asunto);
					$asunto 		= str_replace("{EMAIL}",trim($registros->fields["EMAIL"]),$asunto);
					$asunto 		= str_replace("{ID}",$registros->fields["ID_CONTACTO"],$asunto);
					$mail->Subject			= $asunto;

					//	actualizo las referencias del MENSAJE
					$mensaje 		= str_replace("{NOMBRE_COMPLETO}",stripslashes($registros->fields["NOMBRE_COMPLETO"]),$contenido);
					$mensaje 		= str_replace("{NOMBRE}",stripslashes($registros->fields["NOMBRE_COMPLETO"]),$mensaje);
					$mensaje 		= str_replace("{EMAIL}",trim($registros->fields["EMAIL"]),$mensaje);
					$mensaje 		= str_replace("{ID}",$registros->fields["ID_CONTACTO"],$mensaje);

					$mail->MsgHTML($mensaje);
					$mail->ClearAddresses();

					//	verifico si tiene mas de un correo
					if(strpos($registros->fields["EMAIL"],";")) {
						$destinatario = explode(";",trim($registros->fields["EMAIL"]));
						$notificacion .= "<ol>\n";
						foreach ($destinatario as $valor) {
							$mail->AddAddress($valor); 
							echo("\t<li>".$valor."</li>\n");
							$notificacion .= "<li>".$valor."</li>\n";
						}
						$notificacion .= "</ol>\n";
					} else {
						$mail->AddAddress(trim($registros->fields["EMAIL"])); 								
						$notificacion .= trim($registros->fields["EMAIL"])." ";
						echo(trim($registros->fields["EMAIL"])." ");
					}

					if($mail->Send()) {	
						$notificacion .= "[OK]";
						//	registro el envio en la base de datos
						$conexion->Execute("INSERT INTO contactos_reciben (ID_CONTACTO,ID_BOLETIN,FECHAHORA) values('".$registros->fields["ID_CONTACTO"]."','".$datos["ID_BOLETIN"]."','".date("Y/m/d H:i:s")."')");
					} else { 
						$notificacion .= "<span style='color: red;'>[ERROR]: ".$mail->ErrorInfo."</span>";	
					} 
					$notificacion .= "</li>";
					
					++$i;
					if($i == 141) {
						$notificacion .= "</ol>";

						//	envio notificacion al administrador
						//$mail->From     = $sitio_email;
						//$mail->FromName = $sitio_nombre;
						$mail->Subject  = "[ Aviso ] » ".stripslashes($datos["TITULO"]);
						$mail->MsgHTML($contenido."<hr />".$notificacion);
						$mail->ClearAddresses();
						$mail->AddAddress($sitio_email_admin); 
						if($mail->Send()) {
							//	actualizo la fecha del ultimo envio
							$conexion->Query("UPDATE boletin SET fecha_envio = '".date("Y-m-d")."' WHERE (id_boletin = '".$datos["ID_BOLETIN"]."')");	
						}
						break;
					}
				}
				echo("</li>\n");
				$registros->MoveNext();
			}
			$registros->Close();
			echo("\t</ol>\n");
		}
	} else {
		echo("<p>No hay resultado para el parametro indicado.</p>");
	}
} else {
	echo("<p>Parametros incorrectos.</p>");
}
?>
</body>
</html>