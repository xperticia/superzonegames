<?php
	// No almacenar en el cache del navegador esta página.
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             		// Expira en fecha pasada
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");		// Siempre página modificada
	header("Cache-Control: no-cache, must-revalidate");           		// HTTP/1.1
	header("Pragma: no-cache");                                   		// HTTP/1.0
	if(file_exists("../datos.php")) {
		header ("Location: ../");
		exit;
	}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="ISO-8859-1">
		<title>Instalaci&oacute;n de XPANEL</title>

		<script src="../scripts/jquery/jquery.js"></script>
		<script src="../scripts/jquery/jquery-ui.js"></script>
		<script src="../scripts/bootstrap/js/bootstrap.js"></script>

		<link rel="stylesheet" href="../images/xpanel.css" type="text/css" />
		<!--[if lt IE 9]>
		<script src="../scripts/html5.js"></script>
		<![endif]-->
		<script type="text/javascript" src="install.js"></script>
	</head>

	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="./" title="XPANEL: Sistema de Administraci&oacute;n de Contenidos"><img src="../images/logo_xpanel.png" border="0" alt="XPANEL v3" /></a>

					<div class="nav-collapse">
						<ul class="nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Opciones <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="../../">Pagina Principal</a></li>
									<li class="divider"></li>
									<li><a href="../">Iniciar sesi&oacute;n</a></li>
									<li><a href="<?php echo("../?menu=recuperar"); ?>">Recuperar contrase&ntilde;a</a></li>
									<li><a href="<?php echo("../?menu=acercade"); ?>">Acerca de XPANEL</a></li>
								</ul>
							</li>
							<li class="active"><a href="<?php echo($_SERVER['PHP_SELF']); ?>">Preferencias</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
<?php
if(!isset($_GET['menu'])) {
	//	Guardar
	if(isset($_POST['guardar'])) {
		// El contenido del archivo
		$contenido = "<?php\n\n";

		$contenido .= "//	Datos de la Empresa/Institución	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$sitio_nombre \t\t\t\t= '".trim($_POST['sitio_nombre'])."';\n";
		$contenido .= "\$sitio_url \t\t\t\t\t= '".trim($_POST['sitio_url'])."';\n";
		$contenido .= "\$sitio_eslogan \t\t\t\t= '".trim($_POST['sitio_eslogan'])."';\n";	
		$contenido .= "\$sitio_email \t\t\t\t= '".trim($_POST['sitio_email'])."';\n";
		$contenido .= "\$sitio_email_admin \t\t\t= '".trim($_POST['sitio_email_admin'])."';\n";
		$contenido .= "\$sitio_email_confirmacion \t= '".trim($_POST['sitio_email_confirmacion'])."';\n";
		$contenido .= "\$sitio_plantilla \t\t\t= '".trim($_POST['sitio_plantilla'])."';\n";		
		$contenido .= "\$sitio_estilo \t\t\t\t= '".trim($_POST['sitio_estilo'])."';\n\n";		
		if(!isset($_POST['sitio_logo_mostrar'])) { $_POST['sitio_logo_mostrar'] = ""; }
		$contenido .= "\$sitio_logo_mostrar \t= '".trim($_POST['sitio_logo_mostrar'])."';\n";
		$contenido .= "\$sitio_logo_archivo \t= '".trim($_POST['sitio_logo_archivo'])."';\n\n";

		$contenido .= "//	Datos de conexion a la base de datos	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$tipo_database \t= '".trim($_POST['tipo_database'])."';\n";
		$contenido .= "\$server \t\t= '".trim($_POST['server'])."';\n";
		$contenido .= "\$database \t\t= '".trim($_POST['database'])."';\n";
		$contenido .= "\$user \t\t\t= '".trim($_POST['user'])."';\n";
		$contenido .= "\$password \t\t= '".trim($_POST['password'])."';\n\n";

		$contenido .= "//	Carpetas	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$path_imagenes \t\t= '".trim($_POST['path_imagenes'])."';\n";
		$contenido .= "\$path_archivos \t\t= '".trim($_POST['path_archivos'])."';\n";
		$contenido .= "\$path_modulos \t\t= '".trim($_POST['path_modulos'])."';\n";
		$contenido .= "\$path_scripts \t\t= '".trim($_POST['path_scripts'])."';\n";
		$contenido .= "\$path_plantillas \t= '".trim($_POST['path_plantillas'])."';\n\n";

		$contenido .= "//	Publicidad	\n";
		$contenido .= "//	----------------------------------------\n";

		if(!isset($_POST['publicidad_portada_mostrar'])) { $_POST['publicidad_portada_mostrar'] = ""; }
		if(!isset($_POST['publicidad_h_mostrar'])) { $_POST['publicidad_h_mostrar'] = ""; }
		if(!isset($_POST['publicidad_v_mostrar'])) { $_POST['publicidad_v_mostrar'] = ""; }

		$contenido .= "\$publicidad_portada_mostrar \t= '".trim($_POST['publicidad_portada_mostrar'])."';\n";
		$contenido .= "\$publicidad_portada_archivo \t= '".trim($_POST['publicidad_portada_archivo'])."';\n";
		$contenido .= "\$publicidad_portada_enlace \t\t= '".trim($_POST['publicidad_portada_enlace'])."';\n";
		$contenido .= "\$publicidad_h_mostrar \t\t\t= '".trim($_POST['publicidad_h_mostrar'])."';\n";
		$contenido .= "\$publicidad_h_archivo \t\t\t= '".trim($_POST['publicidad_h_archivo'])."';\n";
		$contenido .= "\$publicidad_h_enlace \t\t\t= '".trim($_POST['publicidad_h_enlace'])."';\n";
		$contenido .= "\$publicidad_v_mostrar \t\t\t= '".trim($_POST['publicidad_v_mostrar'])."';\n";
		$contenido .= "\$publicidad_v_archivo \t\t\t= '".trim($_POST['publicidad_v_archivo'])."';\n";
		$contenido .= "\$publicidad_v_enlace \t\t\t= '".trim($_POST['publicidad_v_enlace'])."';\n\n";

		$contenido .= "\$frase \t\t\t= '".trim($_POST['frase'])."';\n";
		$contenido .= "\$frase_autor \t= '".trim($_POST['frase_autor'])."';\n\n";

		$contenido .= "//	Niveles de Acceso	\n";
		$contenido .= "//	----------------------------------------\n";
		$_POST['niveles_acceso'] = explode("\r\n",$_POST['niveles_acceso']);
		for($i=0; $i<count($_POST['niveles_acceso']); $i++) {
			$contenido .= "\$niveles_acceso[".$i."] = '".$_POST['niveles_acceso'][$i]."';\n";
		}
		$contenido .= "\n";

		$contenido .= "//	Paginador	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$cantidad_filas_x_pagina \t\t= ".trim($_POST['cantidad_filas_x_pagina']).";\n";
		$contenido .= "\$cantidad_enlaces_x_pagina \t\t= ".trim($_POST['cantidad_enlaces_x_pagina']).";\n";
		$contenido .= "\n";

		$contenido .= "//	Datos para los buscadores	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$tags_title \t\t= '".trim($_POST['tags_title'])."';\n";
		$contenido .= "\$tags_keywords \t\t= '".trim($_POST['tags_keywords'])."';\n";
		$contenido .= "\$tags_description \t= '".trim($_POST['tags_description'])."';\n";
		$contenido .= "\n";

		$contenido .= "//	Estados	\n";
		$contenido .= "//	----------------------------------------\n";
		$_POST['estados'] = explode("\r\n",$_POST['estados']);
		for($i=0; $i<count($_POST['estados']); $i++) {
			$contenido .= "\$estados[".$i."] = '".$_POST['estados'][$i]."';\n";
		}
		$contenido .= "\n";

		$contenido .= "//	Tipos de Documentos	\n";
		$contenido .= "//	----------------------------------------\n";
		$_POST['tipo_documento'] = explode("\r\n",$_POST['tipo_documento']);
		for($i=0; $i<count($_POST['tipo_documento']); $i++) {
			$contenido .= "\$tipo_documento[".$i."] = '".$_POST['tipo_documento'][$i]."';\n";
		}
		$contenido .= "\n";

		$contenido .= "//	Google Analytics	\n";
		$contenido .= "//	----------------------------------------\n";
		$contenido .= "\$ga_usuario \t\t= '".trim($_POST['ga_usuario'])."';\n";
		$contenido .= "\$ga_password \t\t= '".trim($_POST['ga_password'])."';\n";
		$contenido .= "\$ga_id \t\t\t\t= '".trim($_POST['ga_id'])."';\n";

		$contenido .= "\n";

		$contenido .= "?>";
		
		// Se abre el archivo (si no existe se crea)
		$archivo = fopen('../datos.php', 'w');
		$error = 0;
		if (!isset($archivo)) {
			$error = 1;
			$msj = "No se ha podido crear/abrir el archivo.";
		}elseif (!fwrite($archivo, $contenido)) {
			$error = 1;
			$msj = "No se ha podido escribir en el archivo.";
		}
		
		@fclose();
		if ($error == 0) {
			$msj = "Datos actualizados...";
		}		
	}

	if(!isset($_GET['accion'])) {		
		@include("../datos.php");		
	?>
			<div class="page-header">
				<h1>Preferencias</h1>
			</div>
			<div class="row">
				<div class="span12">
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" id="signup" class="form-horizontal">
						<?php
							if(isset($msj)) {
								?>
						<div class="alert alert-error">
							<a class="close" data-dismiss="alert" href="#">&times;</a>
							<p style="margin: 0px;"><?php echo($msj); ?></p>
						</div>
								<?php
							}
						?>
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab01" data-toggle="tab">Empresa/Instituci&oacute;n</a></li>
						  <li><a href="#tab02" data-toggle="tab">Conexion a la base de datos</a></li>
						  <li><a href="#tab03" data-toggle="tab">Carpetas de trabajo</a></li>
						  <li><a href="#tab04" data-toggle="tab">Otras variables</a></li>
						  <li><a href="#tab05" data-toggle="tab">Publicidad</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Nombre del sitio</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_nombre" title="Nombre del sitio" data-content="Se debe ingresar el nombre del sitio web (Ej.: XPERTICIA.com), el cual aparecera como titulo principal en cada una de las paginas y en la barra de titulo del navegador." size="50%" value="<?php if(isset($sitio_nombre)) { echo($sitio_nombre); } ?>" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">URL del sitio</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_url" title="URL del sitio" data-content="Se debe ingresar el dominio del sitio web (Ej.: http://www.xperticia.com), el cual sera la direcci&oacute;n que deberan colocar todos los visitantes para ver sus contenidos." size="50%" value="<?php if(isset($sitio_url)) { echo($sitio_url); } ?>" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Eslogan del sitio</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_eslogan" title="Eslogan del sitio" data-content="Se debe ingresar para frase distintiva de la organizaci&oacute;n (Ej.: sistemas inteligentes), sera la cual aparecera junto al nombre del sitio en cada una de las paginas y en los mensajes de aviso que genere el sistema." size="50%" value="<?php if(isset($sitio_eslogan)) { echo($sitio_eslogan); } ?>" />	
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Logo del sitio</label>
									<div class="controls">
										<input type="checkbox" rel="popover" name="sitio_logo_mostrar" title="Habilitar logo del sitio" data-content="" value="s" <?php if(isset($sitio_logo_mostrar) and ($sitio_logo_mostrar == "s")) { echo("checked='checked'"); } ?> /><input type="text" rel="popover" name="sitio_logo_archivo" title="Nombre del archivo con el logo del sitio" size="40%" value="<?php if(isset($sitio_logo_archivo)) { echo($sitio_logo_archivo); } else { echo("logo.jpg"); } ?>" />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Correo electronico oficial</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_email" title="Correo electronico oficial" data-content="Representa la direcci&oacute;n de correo electr&oacute;nico oficial de contacto con el sitio web." size="50%" value="<?php if(isset($sitio_email)) { echo($sitio_email); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Correo electronico del administrador</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_email_admin" title="Correo electronico del administrador" data-content="Representa la direcci&oacute;n de correo electr&oacute;nico oficial de contacto con el sitio web." size="50%" value="<?php if(isset($sitio_email_admin)) { echo($sitio_email_admin); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Correo electronico de confirmaci&oacute;n de lectura</label>
									<div class="controls">
										<input type="text" rel="popover" name="sitio_email_confirmacion" title="Correo electronico de confirmaci&oacute;n de lectura" data-content="Representa la direcci&oacute;n de correo electr&oacute;nico donde se recibiran las confirmaci&oacute;n de lectura de todos los mensajes que se envien desde XPANEL." size="50%" value="<?php if(isset($sitio_email_confirmacion)) { echo($sitio_email_confirmacion); } ?>" />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Plantilla HTML del sitio</label>
									<div class="controls">
										<select rel="popover" name="sitio_plantilla" id="sitio_plantilla" title="Plantilla HTML del sitio" data-content="Permite seleccionar el dise&ntilde;o a aplicar en el sitio web." required>
											<option value=""></option>
											<?php
												//revisa la carpeta plantillas, busca todos los archivos HTML y lee su titulo
												if(!isset($path_plantillas)) {
													$path_plantillas = "templates/";
												}
											  $carpeta = "../../".$path_plantillas;
											  $d = dir($carpeta);
											  while(false !== ($entrada = $d->read())) {
												 //Los diseños para boletines estan en carpetas individuales
												 //verifica si es una carpeta y busca archivos HTML en su interior
												 if(is_dir($carpeta.$entrada)and($entrada != ".")and($entrada != "..")) {
												?>
											<optgroup label="<?php echo($entrada); ?>">
												<?php
													$d2 = dir($carpeta.$entrada);
													while(false !== ($entrada2 = $d2->read())) {
													   //verifico si es archivo y su extension
													   if(is_file($carpeta.$entrada."/".$entrada2)) {
														  $res = explode(".", $entrada2);
														  $extension = $res[count($res) - 1];
														  if(($extension == "html") or ($extension == "htm")) {
															 $file = fopen($carpeta.$entrada."/".$entrada2, "r");
															 $size = filesize($carpeta.$entrada."/".$entrada2);
															 $contenidofile = fread($file, $size);
															 //obtengo el titulo del archivo HTML
															 $titulo = substr($contenidofile, strpos($contenidofile, "<title>") + 7, (strpos($contenidofile, "</title>") - (strpos($contenidofile, "<title>") + 7)));
																?>
											<option value="<?php echo($entrada."/".$entrada2); ?>" <?php if(isset($sitio_plantilla) and ($sitio_plantilla == $entrada."/".$entrada2)) { echo("selected='selected'"); } ?>><?php echo(trim($titulo)); ?></option>
																<?php
														  }
													   }
													}
													$d2->close();
												?>
											</optgroup>
												<?php
												 }
											  }
											  $d->close();
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Estilo CSS del sitio</label>
									<div class="controls">
										<input type="hidden" name="old_sitio_estilo" id="old_sitio_estilo" value="<?php if(isset($sitio_estilo)) { echo($sitio_estilo); } ?>" />
										<select rel="popover" name="sitio_estilo" id="sitio_estilo" title="Estilo CSS del sitio" data-content="Una vez elegido el dise&ntilde;o, se debe seleccionar el estilo a utilizar." required>
											<option value="">&lt;-- seleccionar plantilla</option>
										</select>
									</div>
								</div>
							</div>

							<div id="tab02" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Tipo de base de datos</label>
									<div class="controls">
										<select name="tipo_database" rel="popover" title="Tipo de base de datos" data-content="Se debe seleccionar el tipo de base de datos a utilizar." required>
											<option value=""></option>
											<option value="mysql" <?php if(isset($tipo_database) and ($tipo_database == "mysql")) { echo("selected='selected'"); } ?>>MySql</option>
											<option value="ibase" <?php if(isset($tipo_database) and ($tipo_database == "ibase")) { echo("selected='selected'"); } ?>>Interbase</option>
										</select>									
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Servidor</label>
									<div class="controls">
										<input type="text" rel="popover" name="server" title="Servidor" data-content="Se debe indicar el nombre o direcci&oacute;n del servidor en el que este ubicado la base de datos a utilizar." value="<?php if(isset($server)) { echo($server); } ?>" required />									
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Base de datos</label>
									<div class="controls">
										<input type="text" rel="popover" name="database" title="Base de datos" data-content="Se debe ingresar el nombre de la base de datos a utilizar." value="<?php if(isset($database)) { echo($database); } ?>" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nombre de Usuario</label>
									<div class="controls">
										<input type="text" rel="popover" name="user" title="Nombre de Usuario" data-content="Se debe ingresar el nombre de usuario con acceso a la base de datos." value="<?php if(isset($user)) { echo($user); } ?>" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Contrase&ntilde;a</label>
									<div class="controls">
										<input type="text" rel="popover" name="password" title="Contrase&ntilde;a" data-content="Se debe ingresar la contrase&ntilde;a de acceso a la base de datos." value="<?php if(isset($password)) { echo($password); } ?>" required />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Niveles de Acceso</label>
									<div class="controls">
										<textarea name="niveles_acceso" rel="popover" title="Niveles de Acceso" data-content="Se debe ingresar los tipos de usuarios que tendra en sistema, y se deben indicar segun el orden de importancia, es decir de mayor a menor nivel de prioridad." rows="10" cols="40"><?php if(isset($niveles_acceso)) { for($i=0; $i<count($niveles_acceso); $i++) { echo("\r\n".$niveles_acceso[$i]); } } else { echo("Administrador\nOperador\nCliente\nEstudiante\nVisitante"); }  ?></textarea>
									</div>
								</div>
							</div>

							<div id="tab03" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Imagenes</label>
									<div class="controls">
										<input type="text" rel="popover" name="path_imagenes" title="Imagenes" data-content="Se debe ingresar el nombre de la carpeta donde estaran ubicadas las imagenes (images/)." size="50%" value="<?php if(isset($path_imagenes)) { echo($path_imagenes); } else { echo("images/"); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Archivos</label>
									<div class="controls">
										<input type="text" rel="popover" name="path_archivos" title="Archivos" data-content="Se debe ingresar el nombre de la carpeta donde estaran ubicados todos los archivos de disponibles para descargar (Ej.: archivos/)." size="50%" value="<?php if(isset($path_archivos)) { echo($path_archivos); } else { echo("archivos/"); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Modulos</label>
									<div class="controls">
										<input type="text" rel="popover" name="path_modulos" title="Modulos" data-content="Se debe ingresar el nombre de la carpeta donde se encuentran todos los modulos disponibles para el sistema (Ej.: xpanel/modulos/)." size="50%" value="<?php if(isset($path_modulos)) { echo($path_modulos); } else { echo("xpanel/modulos/"); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Scripts</label>
									<div class="controls">
										<input type="text" rel="popover" name="path_scripts" title="Scripts" data-content="Se debe ingresar el nombre de la carpeta donde se encuentran todos los scripts disponibles para el sistema (Ej.: xpanel/scripts/)." size="50%" value="<?php if(isset($path_scripts)) { echo($path_scripts); } else { echo("xpanel/scripts/"); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Plantillas</label>
									<div class="controls">
										<input type="text" rel="popover" name="path_plantillas" title="Plantillas" data-content="Se debe ingresar el nombre de la carpeta donde se encuentran todas las plantillas de dise&ntilde;o del sitio web, disponibles para seleccionar y configurar su aspecto y distribucion de contenidos (Ej.: templates/)." size="50%" value="<?php if(isset($path_plantillas)) { echo($path_plantillas); } else { echo("templates/"); } ?>" />
									</div>
								</div>
							</div>

							<div id="tab04" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Estados</label>
									<div class="controls">
										<textarea name="estados" rel="popover" title="Estados" data-content="Se debe ingresar como se van a diferencias los estados de ACTIVO y NO ACTIVO en el sistema (Ej.: SI y NO)." rows="2" cols="40"><?php if(isset($estados)) { for($i=0; $i<count($estados); $i++) { echo("\r\n".$estados[$i]); } } else { echo("NO\nSI"); } ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Tipos de Documento</label>
									<div class="controls">
										<textarea name="tipo_documento" rel="popover" title="Tipos de Documento" data-content="Se debe ingresar los diversos tipos de documentos a seleccionar en el sistema, al momento de la registraci&oacute;n de usuarios." rows="10" cols="40"><?php if(isset($tipo_documento)) { for($i=0; $i<count($tipo_documento); $i++) { echo("\r\n".$tipo_documento[$i]); } } else { echo("DNI\nCI\nLC\nLE\nPSP"); } ?></textarea>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Cantidad de filas por pagina</label>
									<div class="controls">
										<input type="text" rel="popover" name="cantidad_filas_x_pagina" title="Cantidad de filas por pagina" data-content="Se debe ingresar la cantidad de registros a visualizar en cada pagina del area de administraci&oacute;n (Ej.: 12)." value="<?php if(isset($cantidad_filas_x_pagina)) { echo($cantidad_filas_x_pagina); } else { echo("12"); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Cantidad de enlaces por pagina</label>
									<div class="controls">
										<input type="text" rel="popover" name="cantidad_enlaces_x_pagina" title="Cantidad de enlaces por pagina" data-content="Se debe ingresar la cantidad de enlaces que se podran visualizar en cada pagina, para desplazarse entre todo el conjunto de registros (Ej.: 2)." value="<?php if(isset($cantidad_enlaces_x_pagina)) { echo($cantidad_enlaces_x_pagina); } else { echo("2"); } ?>" />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Etiquetas de Titulo</label>
									<div class="controls">
										<input type="text" rel="popover" name="tags_title" title="Titulo" data-content="Se debe ingresar, separados por comas, las etiquetas para describir el titulo." value="<?php if(isset($tags_title)) { echo($tags_title); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Palabras clave</label>
									<div class="controls">
										<input type="text" rel="popover" name="tags_keywords" title="Palabras clave" data-content="Se debe ingresar, separados por comas, las etiquetas clave para describir los contenidos." value="<?php if(isset($tags_keywords)) { echo($tags_keywords); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" rel="popover" name="tags_description" title="Descripción" data-content="Se debe ingresar las etiquetas clave de descripción de los contenidos" value="<?php if(isset($tags_description)) { echo($tags_description); } ?>" />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Usuario Google Analytics</label>
									<div class="controls">
										<input type="text" rel="popover" name="ga_usuario" title="Usuario Google Analytics" value="<?php if(isset($ga_usuario)) { echo($ga_usuario); } else { echo(""); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Contraseña Google Analytics</label>
									<div class="controls">
										<input type="text" rel="popover" name="ga_password" title="Contraseña Google Analytics" data-content="" value="<?php if(isset($ga_password)) { echo($ga_password); } else { echo(""); } ?>" />								  
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">ID Google Analytics</label>
									<div class="controls">
										<input type="text" rel="popover" name="ga_id" title="ID Google Analytics" data-content="" value="<?php if(isset($ga_id)) { echo($ga_id); } else { echo(""); } ?>" />								  
									</div>
								</div>
							</div>

							<div id="tab05" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Publicidad Portada</label>
									<div class="controls">
										<input type="checkbox" rel="popover" name="publicidad_portada_mostrar" title="Habilitar Publicidad Portada" data-content="" value="s" <?php if(isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "s")) { echo("checked='checked'"); } ?> />
										<input type="text" rel="popover" name="publicidad_portada_archivo" title="Nombre del archivo de Publicidad en Portada" data-content="Se debera ingresar el nombre de la imagen que se mostrara como publicidad a mostrar solo en la portada del sitio, los formatos pueden ser JPG, PNG o GIF. El tercer campo permite ingresar una direcci&oacute;n URL, en caso que se desee permitir hacer un clic en la imagen y lo derive a alguna parte.<br />Una vez ingresado el nombre de la imagen a mostrar y para que dicha publicidad este habilitada en el sitio, debe hacer clic en el cuadro de opci&oacute;n." size="40%" value="<?php if(isset($publicidad_portada_archivo)) { echo($publicidad_portada_archivo); } else { echo("publicidad_portada.gif"); } ?>" />
										&nbsp;
										<input type="text" rel="popover" name="publicidad_portada_enlace" title="URL de Publicidad en Portada" data-content="Se debera ingresar el nombre de la imagen que se mostrara como publicidad a mostrar solo en la portada del sitio, los formatos pueden ser JPG, PNG o GIF. El tercer campo permite ingresar una direcci&oacute;n URL, en caso que se desee permitir hacer un clic en la imagen y lo derive a alguna parte.<br />Una vez ingresado el nombre de la imagen a mostrar y para que dicha publicidad este habilitada en el sitio, debe hacer clic en el cuadro de opci&oacute;n." size="40%" value="<?php if(isset($publicidad_portada_enlace)) { echo($publicidad_portada_enlace); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Publicidad Horizontal</label>
									<div class="controls">
										<input type="checkbox" rel="popover" name="publicidad_h_mostrar" title="Habilitar Publicidad Horizontal" data-content="" value="s" <?php if(isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "s")) { echo("checked='checked'"); } ?> />
										<input type="text" rel="popover" name="publicidad_h_archivo" title="Nombre del archivo de Publicidad Horizontal" data-content="Idem anterior, con la diferencia que la publicidad aparece en el area de contenidos." size="40%" value="<?php if(isset($publicidad_h_archivo)) { echo($publicidad_h_archivo); } else { echo("publicidad_h.gif"); } ?>" />
										&nbsp;
										<input type="text" rel="popover" name="publicidad_h_enlace" title="URL de Publicidad Horizontal" data-content="Idem anterior, con la diferencia que la publicidad aparece en el area de contenidos." size="40%" value="<?php if(isset($publicidad_h_enlace)) { echo($publicidad_h_enlace); } ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Publicidad Vertical</label>
									<div class="controls">
										<input type="checkbox" rel="popover" name="publicidad_v_mostrar" title="Habilitar Publicidad Vertical" data-content="" value="s" <?php if(isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "s")) { echo("checked='checked'"); } ?> />
										<input type="text" rel="popover" name="publicidad_v_archivo" title="Nombre del archivo de Publicidad Vertical" data-content="Idem anterior, con la diferencia que la publicidad aparece en el area de menus." size="40%" value="<?php if(isset($publicidad_v_archivo)) { echo($publicidad_v_archivo); } else { echo("publicidad_v.gif"); } ?>" />
										&nbsp;
										<input type="text" rel="popover" name="publicidad_v_enlace" title="URL de Publicidad Vertical" data-content="Idem anterior, con la diferencia que la publicidad aparece en el area de menus." size="40%" value="<?php if(isset($publicidad_v_enlace)) { echo($publicidad_v_enlace); } ?>" />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Frase de la semana</label>
									<div class="controls">
										<textarea name="frase" rel="popover" title="Frase de la semana" data-content="Se debera ingresar la frase que se desee visualizar en cada una de las paginas. Esta opci&oacute;n puede ser utilizada como avisos temporales." rows="5" cols="50"><?php if(isset($frase)) { echo($frase); } ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Autor</label>
									<div class="controls">
										<input type="text" rel="popover" name="frase_autor" title="Autor" data-content="Se debe ingresar el nombre del autor de la frase anteriormente ingresada." size="50%" value="<?php if(isset($frase_autor)) { echo($frase_autor); } ?>" />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" name="guardar" value="Guardar datos" />
						</div>
					</form>
				</div>
			</div>
	<?php
	}
}
?>
		</div>
	</body>
</html>