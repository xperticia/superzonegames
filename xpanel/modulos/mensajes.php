<?php
	include("../config.php");
	require("../verifica.php");
	$nivel_acceso = 5;// Nivel de acceso para esta página.
	if($nivel_acceso < $_SESSION['usuario_nivel']) { 
	   header("Location: ./?error_login=5");
	   exit;
	}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo(armarHead()); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<meta name="copyright" content="XPERTICIA.com" />
		<meta name="author" content="Walter E. Irahola" />
		<meta name="email" content="info@xperticia.com" />
		<link rel="shortcut icon" href="../favicon.ico" />

		<link rel="stylesheet" href="../images/xpanel.css" type="text/css" />
	</head>

	<body>
		<?php
		include("../head.php");

		//	-----	Consultas / Comentarios	/ Novedades
		if(isset($_GET['menu']) and ($_GET['menu'] == "consultas") or ($_GET['menu'] == "comentarios") or ($_GET['menu'] == "novedades")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
			$url_modulo .= "&anio=".$_GET['anio'];

			//	Listado
			if(!isset($_GET['accion'])) {
				// CONSULTAS o COMENTARIOS
				if(($_GET['menu'] == "consultas") or ($_GET['menu'] == "comentarios")) {
					$query = "SELECT ID_MENSAJE,NOMBRE,EMAIL,ASUNTO,PAIS,FECHA_ALTA,ACTIVO FROM mensajes LEFT JOIN paises ON paises.codigo_pais = mensajes.codigo_pais WHERE (tipo_mensaje = '".ucfirst($_GET['menu'])."') and (year(fecha_alta) = '".$_GET['anio']."') ";
					if(isset($_GET['texto'])) {
						$query .= " and ( (upper(nombre) LIKE upper('%".$_GET['texto']."%')) or (upper(email) LIKE upper('%".$_GET['texto']."%')) or (upper(asunto) LIKE upper('%".$_GET['texto']."%')) ) ";
					}
					$query .= "ORDER BY fecha_alta DESC";
			
					showTable2($conexion,
						$query,
						array("ID_MENSAJE"=>"#",
							"NOMBRE"=>"Nombre",
							"EMAIL"=>"Correo electr&oacute;nico",
							"ASUNTO"=>"Asunto",
							"PAIS"=>"Pa&iacute;s",
							"FECHA_ALTA"=>"Fecha Alta",
							"ACTIVO"=>"Activo"),
						array(0=>"ID_MENSAJE"),
						$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,null,
						array("responder"=>"Responder", 
							"respuestasmsj"=>"Respuestas"));
				} else {
					// NOVEDADES
					$query = "SELECT ID_MENSAJE,NOMBRE,EMAIL,ASUNTO,FECHA_ALTA,ACTIVO FROM mensajes WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (tipo_mensaje = '".ucfirst($_GET['menu'])."') and (year(fecha_alta) = '".$_GET['anio']."') ";
					if(isset($_GET['texto'])) {
						$query .= " and ( (nombre LIKE '%".$_GET['texto']."%') or (email LIKE '%".$_GET['texto']."%') or (asunto LIKE '%".$_GET['texto']."%') ) ";
					}
					$query .= "ORDER BY fecha_alta DESC";

					showTable2($conexion,
						$query,
						array("ID_MENSAJE"=>"#",
							"NOMBRE"=>"Nombre",
							"EMAIL"=>"Correo electr&oacute;nico",
							"ASUNTO"=>"Asunto",
							"FECHA_ALTA"=>"Fecha Alta",
							"ACTIVO"=>"Activo"),
						array(0=>"ID_MENSAJE"),
						$_SESSION['usuario_rxp'],true,true,$datos_acceso
					);
				}

				if($_GET['menu'] != "novedades") {		
					?>
				<script type="text/javascript">
					const btNuevoRegistro = document.querySelector('#btNuevoRegistro')
					if(btNuevoRegistro){
						btNuevoRegistro.remove()
					}
				</script>
					<?php
				}
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				if($_GET['menu'] == "novedades") {
					?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="tipo_mensaje" value="<?php echo(ucfirst($_GET['menu'])); ?>" />
					<input type="hidden" name="activo" value="1" />
					<input type="hidden" name="codigo_pais" value="BO" />
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo(date("Y-m-d"));  ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required />
											</div>
										</div>

									</div>
									<div class="span9">
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<select name="nombre" title="Usuario" style="width: 100%;" required>
													<option value=""></option>
													<?php
														for($i=0; $i<count($niveles_acceso); $i++) {
															$registros = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
															if($registros and ($registros->RecordCount() > 0)) {
																?>
													<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
																<?php
																while(!$registros->EOF) {
																	?>
														<option value="<?php echo($registros->fields["NOMBRE_COMPLETO"]."|".$registros->fields["EMAIL"]); ?>"><?php echo($registros->fields["NOMBRE_COMPLETO"]." - ".$registros->fields["EMAIL"]); ?></option>
																	<?php
																	$registros->MoveNext();
																}
																?>
													</optgroup>
																<?php
															}
														}
													?>
												</select>
											</div>
										</div>

									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Asunto</label>
									<div class="controls">
										<input type="text" id="asunto" name="asunto" title="Asunto" value="" size="50" maxlength="255" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Mensaje</label>
									<div class="controls">
										<textarea class="ckeditor" id="mensaje" name="mensaje" title="Mensaje" rows="30" cols="60" style="width: 99%;" required></textarea>			
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onClick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
						<?php
					} else {
						?>
				<form class="form-alert">
					<h3>Atenci&oacute;n</h3>
					<div class="body-alert">
						<img src="../images/exclamacion.png" alt="" title="" />
						<p>No se esta permitido agregar registros en este modulo.</p>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
					<?php
				}
			}

			//Insertar
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if($_GET['menu'] == "novedades") {

					if(isset($_POST['id_mensaje'])) {
						echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");			
						$nombre_email = explode("|",$_POST['nombre']);
						$conexion->Query("UPDATE mensajes 
							SET nombre = '".$nombre_email[0]."', 
								email = '".$nombre_email[1]."', 
								asunto = '".addslashes($_POST['asunto'])."', 
								mensaje = '".addslashes($_POST['mensaje'])."', 
								fecha_alta = '".$_POST['fecha_alta']."', 
								codigo_pais = '".$_POST['codigo_pais']."', 
								id_sucursal = '".$_POST['id_sucursal']."', 
								id_actualizacion = '".$_POST['id_actualizacion']."' 
							WHERE (id_mensaje = '".$_POST['id_mensaje']."')");
						$id_registro = $_POST['id_mensaje'];			
					} else {
						echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
						$nombre_email = explode("|",$_POST['nombre']);
						$conexion->Query("INSERT INTO mensajes 
							(
								ID_MENSAJE,
								TIPO_MENSAJE,
								NOMBRE,
								EMAIL,
								ASUNTO,
								MENSAJE,
								FECHA_ALTA,
								ACTIVO,
								CODIGO_PAIS,
								ID_SUCURSAL,
								ID_ACTUALIZACION
							) 
							VALUES(
								null,
								'".$_POST['tipo_mensaje']."',
								'".$nombre_email[0]."',
								'".$nombre_email[1]."',
								'".addslashes($_POST['asunto'])."',
								'".addslashes($_POST['mensaje'])."',
								'".$_POST['fecha_alta']."',
								'".$_POST['activo']."',
								'".$_POST['codigo_pais']."',
								'".$_POST['id_sucursal']."',
								'".$_POST['id_actualizacion']."'
							)");
						$id_registro = $conexion->Insert_ID();
					}		

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
					echo("</script>");

				} else {
					if(isset($_POST['id_cabeza'])) {
						//	inserto los datos de la respuesta
						echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
						$conexion->Query("INSERT INTO mensajes 
							(
								ID_MENSAJE,
								TIPO_MENSAJE,
								NOMBRE,
								EMAIL,
								ASUNTO,
								MENSAJE,
								FECHA_ALTA,
								ACTIVO,
								TIPO_CABEZA,
								ID_CABEZA,
								ID_ACTUALIZACION
							) VALUES(
								null,
								'".$_POST['tipo_mensaje']."',
								'".$_POST['nombre']."',
								'".$_POST['email']."',
								'".addslashes($_POST['asunto'])."',
								'".addslashes(nl2br($_POST['mensaje']))."',
								'".$_POST['fecha_alta']."',
								'".$_POST['activo']."',
								'".$_POST['tipo_cabeza']."',
								'".$_POST['id_cabeza']."',
								'".$_POST['id_actualizacion']."'
							)");
						$id_contenido = $conexion->Insert_ID();

						//obtengo los datos de la plantilla
						$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_respuesta.html";
						if(verificar_url($Htmlfile)) {
							//$contenido = file_get_contents($Htmlfile);
							$contenido = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
							$contenido = str_replace("{FECHA}",formato_fecha($_POST['fecha_alta'],5),$contenido);
							$contenido = str_replace("{NOMBRE}",$_POST['nombre'],$contenido);
							$contenido = str_replace("{EMAIL}",$_POST['email'],$contenido);
							$contenido = str_replace("{ASUNTO}",htmlentities(stripslashes($_POST['asunto'])),$contenido);
							$contenido = str_replace("{MENSAJE}",nl2br($_POST['mensaje']),$contenido);
							$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
							$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

							$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
							$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
							$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

							//	obtengo el logo para boletin
							if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
								$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."' />", $contenido);
							} else {
								$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
							}

							//notifico al administrador la accion registrada
							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->Body				= $contenido;
							$mail->AddAddress($_POST['email']);
							$mail->AddAddress($_POST['email_respuesta']);
							$mail->AddBCC($sitio_email_admin);
							if($mail->Send()) {
								?>
							<form class="form-alert">
								<h3>Atenci&oacute;n</h3>
								<div class="body-alert">
									<img src="../images/informacion.png" alt="" title="" />
									<p>Se ha registrado de forma satisfactoria la respuesta realizada por <strong><?php echo($_POST['nombre']." (".$_POST['email'].")"); ?></strong>, y se ha enviado la respuesta a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST['email_respuesta']); ?></strong>.</p>
								</div>
								<div class="form-actions">
									<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								</div>
							</form>
								<?php
							} else	{
								?>
							<form class="form-alert">
								<h3>Error</h3>
								<div class="body-alert">
									<img src="../images/exclamacion.png" alt="" title="" />
									<p>Se ha obtenido el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar enviar la respuesta realizada por <strong><?php echo($_POST['nombre']." (".$_POST['email'].")"); ?></strong>, a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST['email_respuesta']); ?></strong>.</p>
								</div>
								<div class="form-actions">
									<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								</div>
							</form>
								<?php
							}				
						}
					}

					if(isset($_POST['id_mensaje'])) {
						//actualizo los datos
						$conexion->Query("UPDATE mensajes 
							SET 
								nombre = '".$_POST['nombre']."', 
								email = '".$_POST['email']."', 
								asunto = '".addslashes($_POST['asunto'])."', 
								mensaje = '".addslashes(nl2br($_POST['mensaje']))."', 
								activo = '".$_POST['activo']."', 
								codigo_pais = '".$_POST['codigo_pais']."', 
								id_actualizacion = '".$_POST['id_actualizacion']."' 
							WHERE (id_mensaje = '".$_POST['id_mensaje']."')");

						echo("<script type='text/javascript'>");
						echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."'");
						echo("</script>");
					}
				}
			}

			//Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_mensaje" value="<?php echo($registros->fields["ID_MENSAJE"]); ?>" />
						<?php
							if($_GET['menu'] == "novedades") {
								?>
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
								<?php
							}
						?>
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />
						
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
								<?php 
									if($_GET['menu'] != "novedades") {
										?>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=respuestasmsj&registro[]=".$registros->fields["ID_MENSAJE"]); ?>">Respuestas</a></li>
										<?php
									}
								?>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<?php
										if($_GET['menu'] == "novedades") {
											?>
									<div class="control-group">
										<label class="control-label">Usuario</label>
										<div class="controls">
											<select name="nombre" title="Usuario" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
												<option value=""></option>
												<?php
													for($i=0; $i<count($niveles_acceso); $i++) {
														$registros2 = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
														if($registros2 and ($registros2->RecordCount() > 0)) {
														?>
												<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
															<?php
															while(!$registros2->EOF) {								
																?>
													<option value="<?php echo($registros2->fields["NOMBRE_COMPLETO"]."|".$registros2->fields["EMAIL"]); ?>" <?php if($registros2->fields["NOMBRE_COMPLETO"]."|".$registros2->fields["EMAIL"] == $registros->fields["NOMBRE"]."|".$registros->fields["EMAIL"]) { echo("selected='selected'");	} ?>><?php echo($registros2->fields["NOMBRE_COMPLETO"]." - ".$registros2->fields["EMAIL"]); ?></option>
																<?php
																$registros2->MoveNext();
															}
															?>
												</optgroup>
															<?php
														}
													}
												?>
											</select>
										</div>
									</div>
											<?php
										} else {
											?>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" id="nombre" name="nombre" title="Nombre" value="<?php echo($registros->fields["NOMBRE"]); ?>" size="50" maxlength="255" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo Electr&oacute;nico</label>
										<div class="controls">
											<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
											<?php
										}
									?>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<div class="controls">
											<select name="codigo_pais" title="Pa&iacute;s" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
												<option value=""></option>
												<?php
													$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
													while(!$paises->EOF) {									
															?>
												<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>" <?php if($paises->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($paises->fields["PAIS"]); ?></option>
															<?php
														$paises->MoveNext();
													}
												?>
											</select>				
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" id="asunto" name="asunto" title="Asunto" value="<?php echo(htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<?php
										if($_GET['menu'] == "novedades") {
											?>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<textarea class="ckeditor" id="mensaje" name="mensaje" title="Mensaje" rows="30" cols="60" style="width: 99%;" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["MENSAJE"])); ?></textarea>
										</div>
									</div>
											<?php
										} else {
											?>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<textarea name="mensaje" id="mensaje" title="Mensaje" rows="10" cols="40" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes(str_replace("<br />","",$registros->fields["MENSAJE"]))); ?></textarea>
										</div>
									</div>
										<?php
											$registros2 = $conexion->GetRow("SELECT * FROM contenidos WHERE (tipo_contenido = '".$registros->fields["TIPO_CABEZA"]."') and  (id_contenido = '".$registros->fields["ID_CABEZA"]."')");
											if($registros2){
												?>
									<hr />
									<div class="control-group">
										<label class="control-label">Tipo de Contenido</label>
										<div class="controls">
											<input type="text" title="Tipo de Contenido" value="<?php echo(stripslashes($registros->fields["TIPO_CABEZA"])); ?>" size="50" maxlength="255" style="width: 70%;" readonly="readonly" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Categoria</label>
										<div class="controls">
											<input type="text" title="Categoria" value="<?php echo(stripslashes($registros2["CATEGORIA"])); ?>" size="50" maxlength="255" style="width: 70%;" readonly="readonly" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Titulo</label>
										<div class="controls">
											<input type="text" title="Titulo" value="<?php echo(stripslashes($registros2["TITULO"])); ?>" size="50" maxlength="255" style="width: 70%;" readonly="readonly" />
										</div>
									</div>
												<?php
											}
										}
									?>
									<div class="control-group">
										<label class="control-label">Activo</label>
										<div class="controls">
											<label class="radio inline">
												<input type="radio" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[1]); ?>
											</label>
											<label class="radio inline">
												<input type="radio" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[0]); ?>
											</label>
										</div>
									</div>
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

								</div>

								<?php 
									if($_GET['menu'] != "novedades") {
										?>
								<div id="tab02" class="tab-pane">
									<?php
										$registros2 = $temp->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = 'Respuesta') and (tipo_cabeza = '".$_GET['menu']."') and (id_cabeza = '".$registros->fields["ID_MENSAJE"]."') ORDER BY fecha_alta DESC,id_mensaje DESC");
										if(($registros2->RecordCount() > 0)) {
											while(!$registros2->EOF) {
												?>
									<div class="control-group">
										<blockquote style="width: 90%;"><p><?php echo(stripslashes($registros2->fields["MENSAJE"])); ?></p></blockquote>
										<p class='align-right'>
											<strong><?php echo($registros2->fields["NOMBRE"]); ?></strong> (<?php echo($registros2->fields["EMAIL"]); ?>) <br>
											<em><?php echo(formato_fecha($registros2->fields["FECHA_ALTA"],1)); ?></em> 
											<input type="button" class="btn" value="Re-Enviar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF'].'?menu='.$_GET['menu'].'&accion=enviar&registro[]='.$registros2->fields["ID_MENSAJE"]); ?>'" />
										</p>
									</div>
												<?php if($registros2->RecordCount() > 1) { echo("<hr size='1' />"); } ?>
												<?php
												$registros2->MoveNext();
											}
										}
									?>
								</div>
										<?php
									}
								?>
							</div>

							<div class="form-actions">
								<?php if($_GET['menu'] == "novedades") { ?>
								<input type="button" class="btn btn-large" value="Enviar novedad" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF'].'?menu='.$_GET['menu'].'&accion=enviar&registro[]='.$registros->fields["ID_MENSAJE"]); ?>';" />
								<?php } ?>
								<?php if(($_GET['menu'] == "consultas") or ($_GET['menu'] == "comentarios")) { ?>
								<input type="button" class="btn btn-large" value="Responder" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=responder&registro[]=".$registros->fields["ID_MENSAJE"]); ?>';" />
								<?php } ?>

								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	elimino todas las respuestas
						$conexion->Query("DELETE FROM mensajes WHERE id_cabeza = '".$valor."'");			
						//	elimino el registro
						$conexion->Query("DELETE FROM mensajes WHERE id_mensaje = '".$valor."'");			
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//Responder
			if(isset($_GET['accion']) and ($_GET['accion'] == "responder")) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la respuesta</a></li>
								<li><a href="#tab02" data-toggle="tab"><?php echo(ucfirst($_GET['menu'])); ?></a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<input type="hidden" name="tipo_mensaje" value="Respuesta" />
									<input type="hidden" name="tipo_cabeza" value="<?php echo($_GET['menu']); ?>" />
									<input type="hidden" name="id_cabeza" value="<?php echo($registros->fields["ID_MENSAJE"]); ?>" />
									<input type="hidden" name="email_respuesta" value="<?php echo($registros->fields["EMAIL"]); ?>" />
									<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required />
										</div>
									</div>
									<?php
										//	obtengo los datos del usuario actual
										$registrosOriginal = $conexion->Query("SELECT NOMBRE_COMPLETO,EMAIL,CODIGO_PAIS FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");
										while(!$registrosOriginal->EOF) {
											?>
									<div class="control-group">
										<label class="control-label">Nombre <?php echo($_SESSION['usuario_id']); ?></label>
										<div class="controls">
											<input type="text" id="nombre" name="nombre" title="Nombre" value="<?php echo($registrosOriginal->fields["NOMBRE_COMPLETO"]); ?>" size="50" maxlength="255" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo Electr&oacute;nico</label>
										<div class="controls">
											<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" value="<?php echo($registrosOriginal->fields["EMAIL"]); ?>" size="50" maxlength="255" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<div class="controls">
											<select name="codigo_pais" title="Pa&iacute;s" required >
												<option value=""></option>
												<?php
													$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
													while(!$paises->EOF) {									
															?>
												<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>" <?php if($paises->fields["CODIGO_PAIS"] == $registrosOriginal->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($paises->fields["PAIS"]); ?></option>
															<?php
														$paises->MoveNext();
													}
												?>
											</select>				
										</div>
									</div>
											<?php
											$registrosOriginal->MoveNext();
										}
									?>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" id="asunto" name="asunto" title="Asunto" value="<?php echo("Re-".htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Respuesta</label>
										<div class="controls">
											<textarea id="mensaje" name="mensaje" title="Mensaje" rows="10" cols="40" style="width: 99%;" required></textarea>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Activo</label>
										<div class="controls">
											<label class="radio inline">
												<input type="radio" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
											</label>
											<label class="radio inline">
												<input type="radio" name="activo" value="0" /> <?php echo($estados[0]); ?>
											</label>
										</div>
									</div>
								</div>
								<div id="tab02" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" title="Nombre" value="<?php echo($registros->fields["NOMBRE"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo electr&oacute;nico</label>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<?php 
											$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$registros->fields["CODIGO_PAIS"]."')");
										?>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($pais["PAIS"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" title="Asunto" value="<?php echo(htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<blockquote style="width: 90%;"><p><?php echo(stripslashes($registros->fields["MENSAJE"])); ?></p></blockquote>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<input type="submit" class="btn btn-large btn-primary" value="Enviar respuesta" onClick="return confirm('Se va a agregar un nuevo registro y \nenviar la respuesta al correo electr&oacute;nico: <?php echo($registros->fields["EMAIL"]); ?>.\nEsta Ud. seguro?')" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Enviar respuesta y/o novedad
			if (isset($_GET['accion']) and ($_GET['accion']=="enviar")) {
				?>
				<form class="form-alert">
					<h3>Enviando <?php echo($_GET['menu']); ?>...</h3>
					<div class="body-alert">
						<?php
						$id_modo_editar= $_GET['registro'][0];
						$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = ".$id_modo_editar.")");
						while(!$registros->EOF) {
							//	verifico si es una respuesta
							if(($registros->fields["ID_CABEZA"] != null) and ($registros->fields["ID_CABEZA"] != 0)) {
								$origen = $conexion->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = ".$registros->fields["ID_CABEZA"].")");					

								//	obtengo los datos de la plantilla
								$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_respuesta.html";
								if(verificar_url($Htmlfile)) {
									//$contenido = file_get_contents($Htmlfile);
									$contenido = obtenerDatosArchivo($Htmlfile);
									$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
									$contenido = str_replace("{FECHA}",formato_fecha($registros->fields["FECHA_ALTA"],3),$contenido);
									$contenido = str_replace("{NOMBRE}",$registros->fields["NOMBRE"],$contenido);
									$contenido = str_replace("{EMAIL}",$registros->fields["EMAIL"],$contenido);
									$contenido = str_replace("{ASUNTO}",htmlentities(stripslashes($registros->fields["ASUNTO"])),$contenido);
									$contenido = str_replace("{MENSAJE}",stripslashes($registros->fields["MENSAJE"]),$contenido);
									$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
									$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

									$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
									$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
									$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

									//	obtengo el logo para boletin
									if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
										$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."' />", $contenido);
									} else {
										$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
									}

									//notifico al administrador la accion registrada
									$mail = new PHPMailer();
									$mail->ConfirmReadingTo = $sitio_email_confirmacion;
									$mail->From				= $sitio_email;
									$mail->FromName			= $sitio_nombre;
									$mail->Subject			= $titulo;
									$mail->IsHTML(true);
									$mail->Body				= $contenido;
									$mail->AddAddress($registros->fields["EMAIL"]);
									$mail->AddAddress($origen["EMAIL"]); 
									$mail->AddBCC($sitio_email_admin);
									if($mail->Send()) {
										?>
						<img src="../images/informacion.png" alt="" title="" />
						<p>Se ha enviado de forma satisfactoria la respuesta realizada por <strong><?php echo($registros->fields["NOMBRE"]." (".$registros->fields["EMAIL"].")"); ?></strong>, y se ha enviado a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($origen["NOMBRE"]." (".$origen["EMAIL"].")"); ?></strong>.</p>
										<?php
									} else	{
										?>
						<img src="../images/exclamacion.png" alt="" title="" />
						<p>Se obtuvo el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar enviar la respuesta realizada por <strong><?php echo($registros->fields["NOMBRE"]." (".$registros->fields["EMAIL"].")"); ?></strong>, a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($origen["NOMBRE"]." (".$origen["EMAIL"].")"); ?></strong>.</p>
										<?php
									}				
								}

							} else {
								//	es una novedad
								//	obtengo los datos de la plantilla 
								$Htmlfile = $sitio_url."xpanel/templates/"."novedad.html";
								if(verificar_url($Htmlfile)) {
									$contenido = obtenerDatosArchivo($Htmlfile);
									$contenido = str_replace("{FECHA}",formato_fecha($registros->fields["FECHA_ALTA"],3),$contenido);
									$contenido = str_replace("{TITULO}",htmlentities(stripslashes($registros->fields["ASUNTO"])),$contenido);
									$contenido = str_replace("{CONTENIDO}",stripslashes($registros->fields["MENSAJE"]),$contenido);
									$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
									$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

									$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
									$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
									$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

									//	obtengo el logo para boletin
									if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
										$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."' />", $contenido);
									} else {
										$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
									}

									//	envio la novedad al usuario
									$mail = new PHPMailer();	
									$mail->ConfirmReadingTo = $sitio_email_confirmacion;
									$mail->From				= $sitio_email;
									$mail->FromName			= $sitio_nombre;
									$mail->Subject			= stripslashes($registros->fields["ASUNTO"]);
									$mail->IsHTML(true);
									$mail->ClearAddresses();
									$mail->AddAddress($registros->fields["EMAIL"]); 
									$mail->AddBCC($sitio_email_admin);
									$mail->Body				= $contenido;
									if($mail->Send()) {
										?>
						<img src="../images/informacion.png" alt="" title="" />
						<p>Se ha registrado de forma satisfactoria la novedad realizada por <strong><?php echo($_SESSION['usuario_nombre']); ?></strong>, y se ha enviado a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($registros->fields["EMAIL"]); ?></strong>.</p>
										<?php					
									} else {
										?>
						<img src="../images/exclamacion.png" alt="" title="" />
						<p>Se obtuvo el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar enviar la novedad, a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($registros->fields["EMAIL"]); ?></strong>.</p>
										<?php					
									}
								}				
							}			
							$registros->MoveNext();
						}
						?>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
				<?php
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE mensajes SET activo = 1 WHERE (id_mensaje = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE mensajes SET activo = 0 WHERE (id_mensaje = '".$valor."')");
								} else {
									$conexion->Query("UPDATE mensajes SET activo = 1 WHERE (id_mensaje = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = '".$valor."')");
							if($datos) {
								echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
							} else {
								echo("{ \"estado\" : \"error\" }");
							}
						}
					}		
				}
			}

			//	Respuestas
			if(isset($_GET['accion']) and ($_GET['accion'] == "respuestasmsj")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Cargando registro...</h5>");

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."&vienede=".$_GET['menu']."';");
				echo("</script>");
			}

		}
		//	-----	Fin Consultas / Comentarios / Novedades

		//	-----	Respuestas
		if(isset($_GET['menu']) and ($_GET['menu'] == "respuestasmsj")) {

			//	Listado
			if(!isset($_GET['accion'])) {
				$datos = $conexion->GetRow("SELECT ID_MENSAJE,NOMBRE,EMAIL FROM mensajes WHERE (id_mensaje = '".$_GET['registro'][0]."')");
				?>
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['vienede']."&accion=ver&registro[]=".$datos["ID_MENSAJE"]."#tab01"); ?>">Datos Generales</a></li>
						<li class="active"><a href="#tab01" data-toggle="tab">Respuestas</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<input type="button" class="btn btn-primary pull-right" value="Volver al listado" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['vienede']); ?>';" />
							<h2>Contacto: <?php echo($datos["NOMBRE"]." (".$datos["EMAIL"].")"); ?></h2>
							<?php
								if(isset($_GET['texto'])) {
									$query = "SELECT NOMBRE,EMAIL,FECHA_ALTA,ACTIVO,ID_MENSAJE FROM mensajes WHERE (tipo_mensaje = 'Respuesta') and (tipo_cabeza = '".$_GET['vienede']."') and (id_cabeza = '".$datos["ID_MENSAJE"]."') and ((nombre LIKE '%".$_GET['texto']."%') or (email LIKE '%".$_GET['texto']."%')) ORDER BY fecha_alta DESC,id_mensaje DESC";
								} else {
									$query = "SELECT NOMBRE,EMAIL,FECHA_ALTA,ACTIVO,ID_MENSAJE FROM mensajes WHERE (tipo_mensaje = 'Respuesta') and (tipo_cabeza = '".$_GET['vienede']."') and (id_cabeza = '".$datos["ID_MENSAJE"]."') ORDER BY fecha_alta DESC,id_mensaje DESC";
								}
								showTable2($conexion,
									$query,
									array("NOMBRE"=>"Nombre",
										"EMAIL"=>"Correo electr&oacute;nico",
										"FECHA_ALTA"=>"Fecha",
										"ACTIVO"=>"Activo"),
									array(0=>"ID_MENSAJE"),10,true,true,$datos_acceso,$datos['ID_MENSAJE'],null,
									array("reenviar"=>"Re-enviar"));

							?>
						</div>
					</div>
				</div>
				<?php
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				$id_modo_editar = $_GET['id'];
				$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert&vienede=".$_GET['vienede']); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la respuesta</a></li>
								<li><a href="#tab02" data-toggle="tab"><?php echo(ucfirst($_GET['vienede'])); ?></a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<input type="hidden" name="tipo_mensaje" value="Respuesta" />
									<input type="hidden" name="tipo_cabeza" value="<?php echo($_GET['vienede']); ?>" />
									<input type="hidden" name="id_cabeza" value="<?php echo($registros->fields["ID_MENSAJE"]); ?>" />
									<input type="hidden" name="email_respuesta" value="<?php echo($registros->fields["EMAIL"]); ?>" />
									<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required />
										</div>
									</div>
									<?php
										//	obtengo los datos del usuario actual
										$registrosOriginal = $conexion->Query("SELECT NOMBRE_COMPLETO,EMAIL,CODIGO_PAIS FROM usuarios WHERE (id_usuario = '".$_SESSION['usuario_id']."')");
										while(!$registrosOriginal->EOF) {
											?>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" id="nombre" name="nombre" title="Nombre" value="<?php echo($registrosOriginal->fields["NOMBRE_COMPLETO"]); ?>" size="50" maxlength="255" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo Electr&oacute;nico</label>
										<div class="controls">
											<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" value="<?php echo($registrosOriginal->fields["EMAIL"]); ?>" size="50" maxlength="255" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<div class="controls">
											<select name="codigo_pais" title="Pa&iacute;s" required >
												<option value=""></option>
												<?php
													$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
													while(!$paises->EOF) {									
															?>
												<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>" <?php if($paises->fields["CODIGO_PAIS"] == $registrosOriginal->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($paises->fields["PAIS"]); ?></option>
															<?php
														$paises->MoveNext();
													}
												?>
											</select>				
										</div>
									</div>
											<?php
											$registrosOriginal->MoveNext();
										}
									?>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" id="asunto" name="asunto" title="Asunto" value="<?php echo("Re-".htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" required />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Respuesta</label>
										<div class="controls">
											<textarea id="mensaje" name="mensaje" title="Mensaje" rows="10" cols="40" style="width: 99%;" required></textarea>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Activo</label>
										<div class="controls">
											<label class="radio inline">
												<input type="radio" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
											</label>
											<label class="radio inline">
												<input type="radio" name="activo" value="0" /> <?php echo($estados[0]); ?>
											</label>
										</div>
									</div>
								</div>

								<div id="tab02" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" title="Nombre" value="<?php echo($registros->fields["NOMBRE"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo electr&oacute;nico</label>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<?php 
											$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$registros->fields["CODIGO_PAIS"]."')");
										?>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($pais["PAIS"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" title="Asunto" value="<?php echo(htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<blockquote style="width: 90%;"><p><?php echo(stripslashes($registros->fields["MENSAJE"])); ?></p></blockquote>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<input type="submit" class="btn btn-large btn-primary" value="Enviar respuesta" onClick="return confirm('Se va a agregar un nuevo registro y \nenviar la respuesta al correo electr&oacute;nico: <?php echo($registros->fields["EMAIL"]); ?>.\nEsta Ud. seguro?')" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_GET['id']."&vienede=".$_GET['vienede']); ?>';" />
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Insertar
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if(isset($_POST['id_mensaje'])) {
					//actualizo los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");			
					$conexion->Query("UPDATE mensajes 
						SET 
							fecha_alta = '".$_POST['fecha_alta']."', 
							nombre = '".$_POST['nombre']."', 
							email = '".$_POST['email']."', 
							asunto = '".addslashes($_POST['asunto'])."', 
							mensaje = '".addslashes(nl2br($_POST['mensaje']))."', 
							activo = '".$_POST['activo']."', 
							codigo_pais = '".$_POST['codigo_pais']."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_mensaje = '".$_POST['id_mensaje']."')");

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_POST['id_cabeza']."&vienede=".$_GET['vienede']."';");
					echo("</script>");
				} else {
					//inserto los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					
					//	inserto los datos de la respuesta
					$conexion->Query("INSERT INTO mensajes 
						(
							ID_MENSAJE,
							TIPO_MENSAJE,
							NOMBRE,
							EMAIL,
							ASUNTO,
							MENSAJE,
							FECHA_ALTA,
							ACTIVO,
							TIPO_CABEZA,
							ID_CABEZA,
							CODIGO_PAIS,
							ID_ACTUALIZACION
						) 
						VALUES(
							null,
							'".$_POST['tipo_mensaje']."',
							'".$_POST['nombre']."',
							'".$_POST['email']."',
							'".addslashes($_POST['asunto'])."',
							'".addslashes($_POST['mensaje'])."',
							'".$_POST['fecha_alta']."', 
							'".$_POST['activo']."', 
							'".$_POST['tipo_cabeza']."', 
							'".$_POST['id_cabeza']."',
							'".$_POST['codigo_pais']."',
							'".$_POST['id_actualizacion']."'
						)");
					$id_contenido = $conexion->Insert_ID();

					//obtengo los datos de la plantilla
					$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_respuesta.html";
					if(verificar_url($Htmlfile)) {
						$contenido = obtenerDatosArchivo($Htmlfile);
						$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
						$contenido = str_replace("{FECHA}",formato_fecha($_POST['fecha_alta'],5),$contenido);
						$contenido = str_replace("{NOMBRE}",$_POST['nombre'],$contenido);
						$contenido = str_replace("{EMAIL}",$_POST['email'],$contenido);
						$contenido = str_replace("{ASUNTO}",htmlentities(stripslashes($_POST['asunto'])),$contenido);
						$contenido = str_replace("{MENSAJE}",nl2br($_POST['mensaje']),$contenido);
						$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
						$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

						$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
						$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
						$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

						//	obtengo el logo para boletin
						if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
							$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."' />", $contenido);
						} else {
							$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
						}

						//notifico al administrador la accion registrada
						$mail = new PHPMailer();
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= $titulo;
						$mail->IsHTML(true);
						$mail->Body				= $contenido;
						$mail->AddAddress($_POST['email']);
						$mail->AddAddress($_POST['email_respuesta']);
						$mail->AddBCC($sitio_email_admin);
						if($mail->Send()) {
							?>
						<form class="form-alert">
							<h3>Atenci&oacute;n</h3>
							<div class="body-alert">
								<img src="../images/informacion.png" alt="" title="" />
								<p>Se ha registrado de forma satisfactoria la respuesta realizada por <strong><?php echo($_POST['nombre']." (".$_POST['email'].")"); ?></strong>, y se ha enviado la respuesta a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST['email_respuesta']); ?></strong>.</p>
							</div>
							<div class="form-actions">
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_POST['id_cabeza']."&vienede=".$_GET['vienede']); ?>';" />
							</div>
						</form>
							<?php
						} else	{
							?>
						<form class="form-alert">
							<h3>Error</h3>
							<div class="body-alert">
								<img src="../images/exclamacion.png" alt="" title="" />
								<p>Se ha obtenido el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar enviar la respuesta, a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST['email_respuesta']); ?></strong>.</p>
							</div>
							<div class="form-actions">
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_POST['id_cabeza']."&vienede=".$_GET['vienede']); ?>'" />
							</div>
						</form>
							<?php
						}				
					}
				}
			}

			//Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert&vienede=".$_GET['vienede']); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la respuesta</a></li>
								<li><a href="#tab02" data-toggle="tab"><?php echo(ucfirst($_GET['vienede'])); ?></a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<input type="hidden" name="id_mensaje" value="<?php echo($registros->fields["ID_MENSAJE"]); ?>" />
									<input type="hidden" name="tipo_mensaje" value="Respuesta" />
									<input type="hidden" name="tipo_cabeza" value="<?php echo($registros->fields["TIPO_CABEZA"]); ?>" />
									<input type="hidden" name="id_cabeza" value="<?php echo($registros->fields["ID_CABEZA"]); ?>" />
									<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" id="nombre" name="nombre" title="Nombre" value="<?php echo($registros->fields["NOMBRE"]); ?>" size="50" maxlength="255" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo Electr&oacute;nico</label>
										<div class="controls">
											<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<div class="controls">
											<select name="codigo_pais" title="Pa&iacute;s" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
												<option value=""></option>
												<?php
													$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
													while(!$paises->EOF) {									
															?>
												<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>" <?php if($paises->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($paises->fields["PAIS"]); ?></option>
															<?php
														$paises->MoveNext();
													}
												?>
											</select>				
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" id="asunto" name="asunto" title="Asunto" value="<?php echo(htmlentities(stripslashes($registros->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" required  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<textarea name="mensaje" id="mensaje" title="Mensaje" rows="10" cols="40" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(str_replace("\t","",strip_tags(stripslashes(str_replace("<br />","",$registros->fields["MENSAJE"]))))); ?></textarea>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Activo</label>
										<div class="controls">
											<label class="radio inline">
												<input type="radio" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[1]); ?>
											</label>
											<label class="radio inline">
												<input type="radio" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[0]); ?>
											</label>
										</div>
									</div>
								</div>

								<div id="tab02" class="tab-pane">
									<?php
										//	obtengo los datos de la consulta/comentario original
										$registrosOriginal = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = '".$_GET['id']."')");
										while(!$registrosOriginal->EOF) {
											?>
									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="date" value="<?php echo($registrosOriginal->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" title="Nombre" value="<?php echo($registrosOriginal->fields["NOMBRE"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Correo electr&oacute;nico</label>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($registrosOriginal->fields["EMAIL"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Pa&iacute;s</label>
										<?php 
											$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$registrosOriginal->fields["CODIGO_PAIS"]."')");
										?>
										<div class="controls">
											<input type="text" title="Correo electr&oacute;nico" value="<?php echo($pais["PAIS"]); ?>" size="50" maxlength="255" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Asunto</label>
										<div class="controls">
											<input type="text" title="Asunto" value="<?php echo(htmlentities(stripslashes($registrosOriginal->fields["ASUNTO"]))); ?>" size="50" maxlength="255" style="width: 99%;" readonly="readonly"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Mensaje</label>
										<div class="controls">
											<blockquote style="width: 90%;"><p><?php echo(stripslashes($registrosOriginal->fields["MENSAJE"])); ?></p></blockquote>
										</div>
									</div>
											<?php
											$registrosOriginal->MoveNext();
										}
									?>
								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_GET['id']."&vienede=".$_GET['vienede']); ?>';" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_GET['id']."&vienede=".$_GET['vienede']); ?>';" />
								<?php } ?>
								<input type="button" class="btn btn-large" value="Enviar respuesta" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reenviar&registro[]=".$registros->fields["ID_MENSAJE"]."&vienede=".$_GET['vienede']); ?>';" />
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Re-enviar respuesta
			if (isset($_GET['accion']) and ($_GET['accion']=="reenviar")) {
				?>
				<form class="form-alert">
					<h3>Enviando respuesta...</h3>
					<div class="body-alert">
						<?php
						$id_modo_editar= $_GET['registro'][0];
						$registros = $conexion->Query("SELECT * FROM mensajes WHERE (id_mensaje = ".$id_modo_editar.")");
						while(!$registros->EOF) {
							//	verifico si es una respuesta
							if(($registros->fields["ID_CABEZA"] != null) and ($registros->fields["ID_CABEZA"] != 0)) {
								$id = $registros->fields["ID_CABEZA"];
								$origen = $conexion->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = ".$registros->fields["ID_CABEZA"].")");					

								//	obtengo los datos de la plantilla
								$Htmlfile = $sitio_url."xpanel/templates/"."notificacion_respuesta.html";
								if(verificar_url($Htmlfile)) {
									//$contenido = file_get_contents($Htmlfile);
									$contenido = obtenerDatosArchivo($Htmlfile);
									$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
									$contenido = str_replace("{FECHA}",formato_fecha($registros->fields["FECHA_ALTA"],3),$contenido);
									$contenido = str_replace("{NOMBRE}",$registros->fields["NOMBRE"],$contenido);
									$contenido = str_replace("{EMAIL}",$registros->fields["EMAIL"],$contenido);
									$contenido = str_replace("{ASUNTO}",htmlentities(stripslashes($registros->fields["ASUNTO"])),$contenido);
									$contenido = str_replace("{MENSAJE}",stripslashes($registros->fields["MENSAJE"]),$contenido);
									$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
									$contenido = str_replace("{PUBLICIDAD2}","",$contenido);

									$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
									$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
									$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

									//	obtengo el logo para boletin
									if(verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
										$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."' />", $contenido);
									} else {
										$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
									}

									//notifico al administrador la accion registrada
									$mail = new PHPMailer();
									$mail->ConfirmReadingTo = $sitio_email_confirmacion;
									$mail->From				= $sitio_email;
									$mail->FromName			= $sitio_nombre;
									$mail->Subject			= $titulo;
									$mail->IsHTML(true);
									$mail->Body				= $contenido;
									$mail->AddAddress($registros->fields["EMAIL"]);
									$mail->AddAddress($origen["EMAIL"]); 
									$mail->AddBCC($sitio_email_admin);
									if($mail->Send()) {
										?>
						<img src="../images/informacion.png" alt="" title="" />
						<p>Se ha enviado de forma satisfactoria la respuesta realizada por <strong><?php echo($registros->fields["EMAIL"]); ?></strong>, y se ha enviado a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($origen["EMAIL"]); ?></strong>.</p>
										<?php
									} else	{
										?>
						<img src="../images/exclamacion.png" alt="" title=""/>
						<p>Se obtuvo el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar re-enviar la respuesta generado por <strong><?php echo($registros->fields["NOMBRE"]." (".$registros->fields["EMAIL"].")"); ?></strong> a <strong><?php echo($origen["NOMBRE"]." (".$origen["EMAIL"].")"); ?></strong>.</p>
										<?php
									}				
								}
							}			
							$registros->MoveNext();
						}
						?>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$id."&vienede=".$_GET['vienede']); ?>';" />
					</div>
				</form>
				<?php
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	elimino el registro
						$conexion->Query("DELETE FROM mensajes WHERE (id_mensaje = '".$valor."')");			
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE mensajes SET activo = 1 WHERE (id_mensaje = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE mensajes SET activo = 0 WHERE (id_mensaje = '".$valor."')");
								} else {
									$conexion->Query("UPDATE mensajes SET activo = 1 WHERE (id_mensaje = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM mensajes WHERE (id_mensaje = '".$valor."')");
							if($datos) {
								echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
							} else {
								echo("{ \"estado\" : \"error\" }");
							}
						}
					}		
				}
			}

		}
		//	-----	Fin Respuestas

		include("../footer.php");
		?>
	</body>
</html>