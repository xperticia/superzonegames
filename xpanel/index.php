<?php
	// No almacenar en el cache del navegador esta página.
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             		// Expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// Siempre página modificada
	header("Cache-Control: no-cache, must-revalidate");           		// HTTP/1.1
	header("Pragma: no-cache");                                   		// HTTP/1.0
	//	verifico si existe las variables de configuracion
	if(!file_exists("datos.php")) {
		header ("Location: modulos/install.php");
		exit;
	}
	ini_set('log_errors', 1);
	ini_set('error_log', './php_scripts_error.log');
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//ini_set ('error_reporting', E_ALL);
	include("config.php");
	// le damos un mobre a la sesion (por si quisieramos identificarla)
	session_name($usuarios_sesion);
	// iniciamos sesiones
	ini_set("session.cookie_lifetime","14400"); // 4 horas
	ini_set("session.gc_maxlifetime","14400");
	session_start();
	// destruimos la session de usuarios.
	session_destroy();
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php if(isset($nombre_sistema)) { echo($nombre_sistema); } else { echo("XPANEL v3"); } ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<meta name="copyright" content="XPERTICIA.com" />
		<meta name="author" content="Walter E. Irahola" />
		<meta name="email" content="info@xperticia.com" />
		<link rel="shortcut icon" href="favicon.ico" />

		<link rel="stylesheet" href="images/xpanel.css" type="text/css" />
		<!--[if lt IE 9]>
		<script src="scripts/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body>
		<header>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">

						<div class="navbar navbar-inverse">
							<div class="navbar-inner">
								<div class="container">
									<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</a>
									<a class="brand" href="./" title="XPANEL: Sistema de Administraci&oacute;n de Contenidos"><img src="images/logo_xpanel.png" border="0" alt="XPANEL v3" /></a>

									<div class="nav-collapse collapse">
										<ul class="nav">
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Opciones <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<li><a href="../">Pagina Principal</a></li>
													<li class="divider"></li>
													<li><a href="./">Iniciar sesi&oacute;n</a></li>
													<li><a href="<?php echo("./?menu=recuperar"); ?>">Recuperar contrase&ntilde;a</a></li>
													<li class="divider"></li>
													<li><a href="<?php echo("./?menu=acercade"); ?>">Acerca de XPANEL</a></li>
												</ul>
											</li>
										</ul>
										<div class="pull-right">
											<a href="http://www.xperticia.com" target="_blank" title="Producto desarrollado por XPERTICIA.com"><img src="../images/xperticia_blanco.png" border="0" alt=""></a>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</header>

		<div class="container-fluid">
			<?php		
				//	intento iniciar sesion
				if(!isset($_GET['menu'])) {
				?>
			<header>
				<h1>Iniciar sesi&oacute;n</h1>
			</header>
			<div class="row-fluid">
				<div class="span4"></div>
				<div class="span4">
					<form action="modulos/index.php" method="post" id="signup" class="well form-login">
						<?php
						//	muestro los mensajes de error
						if(isset($_GET['error_login'])) {
							?>
						<div class="alert alert-error">
							<a class="close" data-dismiss="alert" href="#">&times;</a>
							<p style="margin: 0px;"><strong>Error: </strong><?php echo($msg_error[$_GET['error_login']]); ?></p>
						</div>
							<?php
						}
						?>
						<div class="control-group">
							<label class="control-label">Sucursal</label>
							<div class="controls">
								<select id="sucursal" name="sucursal" title="Sucursal" class="input-block-level" required autofocus="true">
									<option value="">Sucursal</option>
									<?php
										$sucursales = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
										if($sucursales and ($sucursales->RecordCount() > 0)){
											while(!$sucursales->EOF) {
												?>
									<option value="<?php echo($sucursales->fields["ID_SUCURSAL"]); ?>" <?php if($sucursales->fields["ID_SUCURSAL"] == 1) { echo("selected='selected'"); } ?>><?php echo(stripslashes($sucursales->fields["NOMBRE"])); ?></option>
												<?php
												$sucursales->MoveNext();
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="control-group" style="margin-top: 20px;">
							<label class="control-label" for="user">Usuario</label>
							<div class="controls">
								<input type="text" id="user" name="user" size="30" maxlength="255" placeholder="usuario@dominio" class="input-block-level" required />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="pass">Contrase&ntilde;a</label>
							<div class="controls">
								<input type="password" id="pass" name="pass" size="30" maxlength="255" class="input-block-level" required />
							</div>
						</div>

						<div class="form-actions" style="margin-bottom: 0px;">
							<input type="submit" class="btn btn-large btn-block btn-primary" value="&raquo; Ingresar" />
						</div>
					</form>
				</div>
				<div class="span4"></div>
			</div>
				<?php
				}
				//	intento recuperar los datos de acceso
				if(isset($_GET['menu']) and ($_GET['menu'] == "recuperar")) {
				?>
			<header>
				<h1>Recuperar contrase&ntilde;a</h1>
			</header>
			<div class="row-fluid">
				<div class="span12">
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=enviar"); ?>" id="signup" class="well form-login">
						<?php
						//	muestro los mensajes de error
						if(isset($_GET['error_login'])) {
							?>
						<div class="alert alert-error">
							<a class="close" data-dismiss="alert" href="#">&times;</a>
							<p style="margin: 0px;"><strong>Error: </strong><?php echo($msg_error[$_GET['error_login']]); ?></p>
						</div>
							<?php
						}
						?>
						<div class="control-group" style="margin-top: 20px;">
							<label class="control-label" for="user">Usuario</label>
							<div class="controls">
								<input type="text" id="user" name="user" size="30" maxlength="255" class="input-block-level" placeholder="usuario@dominio" required />
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="pass">Correo Electr&oacute;nico</label>
							<div class="controls">
								<input type="text" id="email" name="email" size="30" maxlength="255" class="input-block-level" placeholder="usuario@dominio" required />
							</div>
						</div>

						<div class="form-actions" style="margin-bottom: 0px;">
							<input type="submit" class="btn btn-large btn-primary" value="&raquo; Recuperar contrase&ntilde;a" />
						</div>
					</form>
				</div>
			</div>
				<?php
				}
				//	verifico si los datos de recuperacion
				if(isset($_GET['menu']) and ($_GET['menu'] == "enviar")) {
				?>
			<header>
				<h1>Recuperando contrase&ntilde;a...</h1>
			</header>
			<div class="row-fluid">
				<div class="span12">
					<?php
					//obtengo los datos del usuario
					$registros = $conexion->Query("SELECT * FROM usuarios WHERE (usuario = '".trim($_POST["user"])."') and (email = '".trim($_POST["email"])."') and (activo = 1)");

					//verifica si el usuario existe y esta activo
					if ($registros->RecordCount() > 0){
						//obtengo los datos de la plantilla 
						$Htmlfile = "templates/datos_acceso.html";
						$file = fopen($Htmlfile,"r");
						$size = filesize($Htmlfile);
						$contenido = fread($file,$size);
						fclose($file);
						while (!$registros->EOF) {			
							$contenido = str_replace("{NOMBRE_COMPLETO}",$registros->fields["NOMBRE_COMPLETO"],$contenido);
							$contenido = str_replace("{USUARIO}",$registros->fields["USUARIO"],$contenido);
							$contenido = str_replace("{CLAVE}",$registros->fields["CLAVE"],$contenido);
							$contenido = str_replace("{SITIO_NOMBRE}",$sitio_nombre,$contenido);
							$contenido = str_replace("{SITIO_URL}",$sitio_url,$contenido);
							$contenido = str_replace("{SITIO_URL_XPANEL}",$sitio_url."xpanel/",$contenido);

							$contenido = str_replace("{PUBLICIDAD1}","",$contenido);
							$contenido = str_replace("{PUBLICIDAD2}","",$contenido);
							$registros->MoveNext();
						}
						$mail = new PHPMailer();	
						$mail->Priority = 1;
						$mail->ConfirmReadingTo = $sitio_email_confirmacion;
						$mail->From				= $sitio_email;
						$mail->FromName			= $sitio_nombre;
						$mail->Subject			= "Datos de Acceso";
						$mail->IsHTML(true);
						$mail->Body				= $contenido;
						$mail->AddAddress($_POST["email"]);
						$mail->AddBCC($sitio_email_admin); 
						?>
					<p>Los datos ingresados son validos.</p>
						<?php
						if($mail->Send()) {
							?>
					<p>La contrase&ntilde;a del usuario <strong>&quot;<?php echo($_POST['user']); ?>&quot;</strong> se ha enviado al correo electr&oacute;nico: <strong>&quot;<?php echo($_POST['email']); ?>&quot;</strong>.</p>
							<?php
						} else {
							?>
					<p>Se obtuvo el siguiente mensaje <span style="color: #FF0000"><strong>Error:</strong> <?php echo($mail->ErrorInfo); ?></span>, al intenar enviar los datos de acceso, a la direcci&oacute;n de correo electr&oacute;nico: <strong><?php echo($_POST["email"]); ?></strong>.</p>
							<?php
						}
						?>
					<p>Gracias por utilizar nuestro servicio de recuperaci&oacute;n de contrase&ntilde;a.</p>
						<?php
					} else {
						?>
					<p>Los datos ingresados no son validos, debido a alguna de las siguientes razones:</p>
					<ul>
						<li>El nombre de usuario no es valido.</li>
						<li>El correo electr&oacute;nico no coincide con el registrado.</li>
						<li>No se encuentra habilitado para el acceso a nuestro sistema.</li>
					</ul>
					<p>Ante dudas o consultas contactarse a <a href="mailto:info@xperticia.com?subject=[ xperticia.com ] &gt; Error al Recuperar Contrase&ntilde;a">info@xperticia.com</a>.</p>
					<p><a href="<?php echo($_SERVER['PHP_SELF']."?menu=recuperar"); ?>" title="recuperar contrase&ntilde;a">Clic aqui</a> para volver a intentarlo.</p>
					<?php
					}
					?>
				</div>
			</div>
				<?php
				}
				//	muestro ayuda sobre el sistema
				if(isset($_GET['menu']) and ($_GET['menu'] == "acercade")) {
				?>
			<header>
				<h1>Acerca de XPANEL</h1>
			</header>
			<div class="row-fluid">
				<div class="span12">
					<h3>Que es XPANEL?</h3>
					<p>Es un sistema en l&iacute;nea,  desarrollado por <a href="http://www.xperticia.com/">XPERTICIA.com</a>, que  permite administrar un sitio web y sus contenidos.</p>
					<p>Este sistema trabaja de  forma modulizada, es decir, que los contenidos a administrar son m&oacute;dulos  independientes y f&aacute;cilmente configurables, los cuales se pueden adquirir de  forma separada o por paquetes. Adem&aacute;s, se desarrollan m&oacute;dulos a medida, seg&uacute;n  sus requerimientos o necesidades.</p>
					<p>Puede ser utilizado como soporte  para aquellas personas que no posean conocimientos en programaci&oacute;n o dise&ntilde;o de  p&aacute;ginas o no desean depender de forma permanente de un dise&ntilde;ador para las  actualizaciones. Tambi&eacute;n esta orientado para aquellas peque&ntilde;as o medianas  empresas que desean la promoci&oacute;n de sus productos o servicios a trav&eacute;s de  internet.</p>
					<p><strong>XPANEL</strong> env&iacute;a a los  administradores del sistema, mensajes de notificaci&oacute;n sobre los eventos  ocurridos en su sitio, de esta forma esta al tanto en cualquier momento de que  es lo realizan los visitantes o usuarios en su sitio. Permitiendo obtener  reportes diarios, semanal, mensual y anual.</p>
					<p>Actualmente se posee dos  versiones en el mercado:</p>
					<ul>
						  <li><strong><em>XPANEL Individual</em></strong>: incluye el sitio  web y su &aacute;rea de administraci&oacute;n. Es ideal para sitios personales, peque&ntilde;os o  medianas empresas u organizaciones p&uacute;blicas o privadas.</li>
						  <li><strong><em>XPANEL Corporativo</em></strong>: incluye el sitio  web, el cual permite la administraci&oacute;n de sub-sitios relacionados, y su &aacute;rea de  administraci&oacute;n. Es ideal para aquellas empresas u organizaciones que poseen  sedes o sucursales, ubicadas en zonas geogr&aacute;ficas alejadas, que tienen  informaci&oacute;n y actividades relacionadas, pero que requieren una administraci&oacute;n  de forma centralizada.</li>
					</ul>
					<p>Si desea obtener mas informaci&oacute;n, visitenos en <a href="http://www.xperticia.com" target="_blank">http://www.xperticia.com</a> o contactese a <a href="mailto:info@xperticia.com">info@xperticia.com</a>. </p></td>
				</div>
			</div>
				<?php
				}
				?>		
		</div>

		<footer>
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span6">
						<p>&copy; 2007-<?php echo(date("Y"));?> <a href="http://www.xperticia.com" target="_blank"><strong>XPERTICIA.com</strong></a>. Todos los derechos reservados.</p>
					</div>
					<div class="span6" style="text-align: right;float:right;">
						<p>Compatible: 
							<a href="http://windows.microsoft.com/es-ES/internet-explorer/products/ie/home" title="Internet Explorer" target="_blank"><img src="images/ie.png" alt="Internet Explorer" border="0" /></a>
							<a href="http://www.mozilla.org/es-AR/firefox/fx/" title="Mozilla Firefox" target="_blank"><img src="images/firefox.png" alt="Mozilla Firefox" border="0" /></a>
							<a href="http://www.google.com/chrome?hl=es" title="Chrome" target="_blank"><img src="images/chrome.png" alt="Chrome" border="0" /></a>
							<a href="http://www.opera.com/download/" title="Opera" target="_blank"><img src="images/opera.png" alt="Opera" border="0" /></a>
							- Resoluci&oacute;n: 1024 x 768
						</p>
					</div>
				</div>		
			</div>		
		</footer>

		<script src="scripts/jquery/jquery.js"></script>
		<script src="scripts/jquery/jquery-ui.js"></script>
		<script src="scripts/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript">
			$(function() {
				if($("#signup").length) {
					$.getScript("scripts/h5f/h5f.js",function(){
						H5F.setup(document.getElementById("signup"));
					});
				};
				$(window).resize(function () {
					$("body > .container-fluid:first").css('min-height',($(window).height()-135)+"px");
				})
				$(window).trigger('resize');

				if($("#sucursal").length) {
					$("#sucursal").change(function() {
						if ($(this).val() != "") {
							$("#user").focus();
							$("#user").select();
						}
					})
				}

				if($("#user").length) {	
					$("#user").bind('keypress',function(e) {
						if(e.which == 13){
							$("#pass").focus();
							$("#pass").select();
							return false;
						}
					})
				}

				if($("#pass").length) {	
					$("#pass").bind('keypress',function(e) {
						if(e.which == 13){
							$('[type="submit"]').focus();
							return false;
						}
					})
				}

			});
		</script>

	</body>
</html>