<?php
    include("../config.php");
    include("../verifica.php");
    $nivel_acceso = 5;// Nivel de acceso para esta página.
    if ($nivel_acceso < $_SESSION['usuario_nivel']) {
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

		//	-----	CONFIGURACION
		if (isset($_GET['menu']) and ($_GET['menu'] == "config")) {
			//	Guardar
			if (isset($_POST['guardar'])) {
				// El contenido del archivo
				$contenido = "<?php\n\n";

				$contenido .= "//	Datos de la Empresa/Institución	\n";
				$contenido .= "//	----------------------------------------\n";
				$contenido .= "\$sitio_nombre \t\t\t\t= '".trim($_POST['sitio_nombre'])."';\n";
				$contenido .= "\$sitio_url \t\t\t\t\t= '".trim($_POST['sitio_url'])."';\n";
				$contenido .= "\$sitio_eslogan \t\t\t\t= '".trim($_POST['sitio_eslogan'])."';\n";
				$contenido .= "\$sitio_direccion \t\t\t= '".trim($_POST['sitio_direccion'])."';\n";
				$contenido .= "\$sitio_telefono \t\t\t= '".trim($_POST['sitio_telefono'])."';\n";
				$contenido .= "\$sitio_email \t\t\t\t= '".trim($_POST['sitio_email'])."';\n";
				$contenido .= "\$sitio_email_admin \t\t\t= '".trim($_POST['sitio_email_admin'])."';\n";
				$contenido .= "\$sitio_email_confirmacion \t= '".trim($_POST['sitio_email_confirmacion'])."';\n";
				$contenido .= "\$sitio_plantilla \t\t\t= '".trim($_POST['sitio_plantilla'])."';\n";
				$contenido .= "\$sitio_estilo \t\t\t\t= '".trim($_POST['sitio_estilo'])."';\n\n";
				if (!isset($_POST['sitio_logo_mostrar'])) {
					$_POST['sitio_logo_mostrar'] = "";
				}
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

				if (!isset($_POST['publicidad_portada_mostrar'])) { $_POST['publicidad_portada_mostrar'] = ""; }
				if (!isset($_POST['publicidad_portada_archivo'])) { $_POST['publicidad_portada_archivo'] = ""; }
				if (!isset($_POST['publicidad_portada_enlace'])) { $_POST['publicidad_portada_enlace'] = ""; }
				
				if (!isset($_POST['publicidad_h_mostrar'])) { $_POST['publicidad_h_mostrar'] = ""; }
				if (!isset($_POST['publicidad_h_archivo'])) { $_POST['publicidad_h_archivo'] = ""; }
				if (!isset($_POST['publicidad_h_enlace'])) { $_POST['publicidad_h_enlace'] = ""; }

				if (!isset($_POST['publicidad_v_mostrar'])) { $_POST['publicidad_v_mostrar'] = ""; }
				if (!isset($_POST['publicidad_v_archivo'])) { $_POST['publicidad_v_archivo'] = ""; }
				if (!isset($_POST['publicidad_v_enlace'])) { $_POST['publicidad_v_enlace'] = ""; }

				$contenido .= "\$publicidad_portada_mostrar \t= '".trim($_POST['publicidad_portada_mostrar'])."';\n";
				//	verifico si subio un archivo
				if (isset($_FILES["publicidad_portada_file"])) {
					//	obtengo el nombre del archivo y su extension
					$res = explode(".", $_FILES['publicidad_portada_file']['name']);
					$tipo_archivo = $_FILES['publicidad_portada_file']['type'];
					$extension = $res[count($res) - 1];
					$nombre_archivo = "publicidad_portada_subido.".$extension;

					//	verifico si se subio un archivo
					if (is_uploaded_file($_FILES['publicidad_portada_file']['tmp_name'])) {
						//	verifico el tipo de archivo
						if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
						($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
							//	copio el archivo
							if (copy($_FILES['publicidad_portada_file']['tmp_name'], stripslashes("../../".$path_imagenes.$nombre_archivo))) {
								echo("archivo copiado<br />");
								//	si no hubo inconvenientes actualiza el nombre del archivo
								$contenido .= "\$publicidad_portada_archivo \t= '".$nombre_archivo."';\n";
							} else {
								$contenido .= "\$publicidad_portada_archivo \t= '".trim($_POST['publicidad_portada_archivo'])."';\n";
							}
							//	elimino el archivo temporal
							unlink($_FILES['publicidad_portada_file']['tmp_name']);
						} else {
							$contenido .= "\$publicidad_portada_archivo \t= '".trim($_POST['publicidad_portada_archivo'])."';\n";
							//	elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
							unlink($_FILES['publicidad_portada_file']['tmp_name']);
							echo("<script type='text/javascript'>");
							echo("window.alert('".$_FILES['publicidad_portada_file']['name'].": ".$msg_error[10]."');");
							echo("</script>");
						}
					} else {
						$contenido .= "\$publicidad_portada_archivo \t= '".trim($_POST['publicidad_portada_archivo'])."';\n";
					}
				} else {
					$contenido .= "\$publicidad_portada_archivo \t= '".trim($_POST['publicidad_portada_archivo'])."';\n";
				}
				$contenido .= "\$publicidad_portada_enlace \t\t= '".trim($_POST['publicidad_portada_enlace'])."';\n";

				$contenido .= "\$publicidad_h_mostrar \t\t\t= '".trim($_POST['publicidad_h_mostrar'])."';\n";
				//	verifico si subio un archivo
				if (isset($_FILES["publicidad_h_file"])) {
					//	obtengo el nombre del archivo y su extension
					$res = explode(".", $_FILES['publicidad_h_file']['name']);
					$tipo_archivo = $_FILES['publicidad_h_file']['type'];
					$extension = $res[count($res) - 1];
					$nombre_archivo = "publicidad_h_subido.".$extension;

					//	verifico si se subio un archivo
					if (is_uploaded_file($_FILES['publicidad_h_file']['tmp_name'])) {
						//	verifico el tipo de archivo
						if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
						($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
							//	copio el archivo
							if (copy($_FILES['publicidad_h_file']['tmp_name'], stripslashes("../../".$path_imagenes.$nombre_archivo))) {
								//	si no hubo inconvenientes actualiza el nombre del archivo
								$contenido .= "\$publicidad_h_archivo \t= '".$nombre_archivo."';\n";
							} else {
								$contenido .= "\$publicidad_h_archivo \t\t\t= '".trim($_POST['publicidad_h_archivo'])."';\n";
							}
							//	elimino el archivo temporal
							unlink($_FILES['publicidad_h_file']['tmp_name']);
						} else {
							$contenido .= "\$publicidad_h_archivo \t\t\t= '".trim($_POST['publicidad_h_archivo'])."';\n";
							//	elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
							unlink($_FILES['publicidad_h_file']['tmp_name']);
							echo("<script type='text/javascript'>");
							echo("window.alert('".$_FILES['publicidad_h_file']['name'].": ".$msg_error[10]."');");
							echo("</script>");
						}
					} else {
						$contenido .= "\$publicidad_h_archivo \t\t\t= '".trim($_POST['publicidad_h_archivo'])."';\n";
					}
				} else {
					$contenido .= "\$publicidad_h_archivo \t\t\t= '".trim($_POST['publicidad_h_archivo'])."';\n";
				}
				$contenido .= "\$publicidad_h_enlace \t\t\t= '".trim($_POST['publicidad_h_enlace'])."';\n";

				$contenido .= "\$publicidad_v_mostrar \t\t\t= '".trim($_POST['publicidad_v_mostrar'])."';\n";
				//	verifico si subio un archivo
				if (isset($_FILES["publicidad_v_file"])) {
					//	obtengo el nombre del archivo y su extension
					$res = explode(".", $_FILES['publicidad_v_file']['name']);
					$tipo_archivo = $_FILES['publicidad_v_file']['type'];
					$extension = $res[count($res) - 1];
					$nombre_archivo = "publicidad_v_subido.".$extension;

					//	verifico si se subio un archivo
					if (is_uploaded_file($_FILES['publicidad_v_file']['tmp_name'])) {
						//	verifico el tipo de archivo
						if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
						($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
							//	copio el archivo
							if (copy($_FILES['publicidad_v_file']['tmp_name'], stripslashes("../../".$path_imagenes.$nombre_archivo))) {
								//	si no hubo inconvenientes actualiza el nombre del archivo
								$contenido .= "\$publicidad_v_archivo \t= '".$nombre_archivo."';\n";
							} else {
								$contenido .= "\$publicidad_v_archivo \t\t\t= '".trim($_POST['publicidad_v_archivo'])."';\n";
							}
							//	elimino el archivo temporal
							unlink($_FILES['publicidad_v_file']['tmp_name']);
						} else {
							$contenido .= "\$publicidad_v_archivo \t\t\t= '".trim($_POST['publicidad_v_archivo'])."';\n";
							//	elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
							unlink($_FILES['publicidad_v_file']['tmp_name']);
							echo("<script type='text/javascript'>");
							echo("window.alert('".$_FILES['publicidad_v_file']['name'].": ".$msg_error[10]."');");
							echo("</script>");
						}
					} else {
						$contenido .= "\$publicidad_v_archivo \t\t\t= '".trim($_POST['publicidad_v_archivo'])."';\n";
					}
				} else {
					$contenido .= "\$publicidad_v_archivo \t\t\t= '".trim($_POST['publicidad_v_archivo'])."';\n";
				}
				$contenido .= "\$publicidad_v_enlace \t\t\t= '".trim($_POST['publicidad_v_enlace'])."';\n\n";

				$contenido .= "\$frase \t\t\t= '".trim($_POST['frase'])."';\n";
				$contenido .= "\$frase_autor \t= '".trim($_POST['frase_autor'])."';\n\n";

				$contenido .= "//	Niveles de Acceso	\n";
				$contenido .= "//	----------------------------------------\n";
				$_POST['niveles_acceso'] = explode("\r\n", trim($_POST['niveles_acceso']));
				for ($i=0; $i<count($_POST['niveles_acceso']); $i++) {
					$contenido .= "\$niveles_acceso[".$i."] = '".trim($_POST['niveles_acceso'][$i])."';\n";
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
				$_POST['estados'] = explode("\r\n", trim($_POST['estados']));
				for ($i=0; $i<count($_POST['estados']); $i++) {
					$contenido .= "\$estados[".$i."] = '".trim($_POST['estados'][$i])."';\n";
				}
				$contenido .= "\n";

				$contenido .= "//	Tipos de Documentos	\n";
				$contenido .= "//	----------------------------------------\n";
				$_POST['tipo_documento'] = explode("\r\n", trim($_POST['tipo_documento']));
				for ($i=0; $i<count($_POST['tipo_documento']); $i++) {
					$contenido .= "\$tipo_documento[".$i."] = '".trim($_POST['tipo_documento'][$i])."';\n";
				}
				$contenido .= "\n";

				$contenido .= "//	Precision	\n";
				$contenido .= "//	----------------------------------------\n";
				$contenido .= "\$cantidad_decimales \t= ".trim($_POST['cantidad_decimales']).";\n";
				$contenido .= "\n";

				$contenido .= "//	Ubicación	\n";
				$contenido .= "//	----------------------------------------\n";
				$contenido .= "\$sitio_apimapa \t= '".trim($_POST['sitio_apimapa'])."';\n";
				$contenido .= "\$sitio_typemapa = '".trim($_POST['sitio_typemapa'])."';\n";
				$contenido .= "\$sitio_keymapa \t= '".trim($_POST['sitio_keymapa'])."';\n";
				$contenido .= "\$latitud \t\t= ".trim($_POST['latitud']).";\n";
				$contenido .= "\$longitud \t\t= ".trim($_POST['longitud']).";\n";

				$contenido .= "\n";
				$contenido .= "//	Google Analytics	\n";
				$contenido .= "//	----------------------------------------\n";
				$contenido .= "\$ga_usuario \t\t= '".trim($_POST['ga_usuario'])."';\n";
				$contenido .= "\$ga_password \t\t= '".trim($_POST['ga_password'])."';\n";
				$contenido .= "\$ga_id \t\t\t\t= '".trim($_POST['ga_id'])."';\n";
				$contenido .= "\n";

				$contenido .= "//	Redes Sociales	\n";
				$contenido .= "//	----------------------------------------\n";
				$contenido .= "\$sitio_url_facebook \t\t\t= '".trim($_POST['sitio_url_facebook'])."';\n";
				$contenido .= "\$sitio_url_facebook_group \t\t= '".trim($_POST['sitio_url_facebook_group'])."';\n";
				$contenido .= "\$sitio_url_twitter \t\t\t\t= '".trim($_POST['sitio_url_twitter'])."';\n";
				$contenido .= "\$sitio_url_linkedin \t\t\t= '".trim($_POST['sitio_url_linkedin'])."';\n";
				$contenido .= "\$sitio_url_google \t\t\t\t= '".trim($_POST['sitio_url_google'])."';\n";
				$contenido .= "\$sitio_url_youtube \t\t\t\t= '".trim($_POST['sitio_url_youtube'])."';\n";
				$contenido .= "\n";

				$contenido .= "//	Colores para las Categorias de Productos	\n";
				$contenido .= "//	--------------------------------------------\n";
				foreach ($_POST['categoria_nombre'] as $key=>$val) {
					$contenido .= "\$colores_categoriasProductos[".$key."] \t= '".trim($_POST['categoria_nombre'][$key])."|".$_POST['categoria_color'][$key]."';\n";
				}
				$contenido .= "\n";

				$contenido .= "//	Colores para los Estados de las Ventas	\n";
				$contenido .= "//	----------------------------------------\n";
				foreach ($_POST['estadoVentas_nombre'] as $key=>$val) {
					$contenido .= "\$colores_estadosVentas[".$key."] \t= '".trim($_POST['estadoVentas_nombre'][$key])."|".$_POST['estadoVentas_color'][$key]."';\n";
				}
				$contenido .= "\n";

				$contenido .= "//	Colores para las Ventas Pagadas o No	\n";
				$contenido .= "//	----------------------------------------\n";
				foreach ($_POST['pagadoVentas_nombre'] as $key=>$val) {
					$contenido .= "\$colores_pagadoVentas[".$key."] \t= '".trim($_POST['pagadoVentas_nombre'][$key])."|".$_POST['pagadoVentas_color'][$key]."';\n";
				}
				$contenido .= "\n";

				$contenido .= "//	Colores para los Estados de los Servicios Técnicos	\n";
				$contenido .= "//	----------------------------------------------------\n";
				foreach ($_POST['estadoServiciosTecnicos_nombre'] as $key=>$val) {
					$contenido .= "\$colores_estadosServiciosTecnicos[".$key."] \t= '".trim($_POST['estadoServiciosTecnicos_nombre'][$key])."|".$_POST['estadoServiciosTecnicos_color'][$key]."';\n";
				}
				$contenido .= "\n";
				
				$contenido .= "//	Colores para los Estados de los Pedidos	\n";
				$contenido .= "//	----------------------------------------\n";
				foreach ($_POST['estadoPedidos_nombre'] as $key=>$val) {
					$contenido .= "\$colores_estadosPedidos[".$key."] \t= '".trim($_POST['estadoPedidos_nombre'][$key])."|".$_POST['estadoPedidos_color'][$key]."';\n";
				}
				$contenido .= "\n";

				$contenido .= "?>";
				
				// Se abre el archivo (si no existe se crea)
				$archivo = fopen('../datos.php', 'w');
				$error = 0;
				if (!isset($archivo)) {
					$error = 1;
					$msj = "No se ha podido crear/abrir el archivo.";
				} elseif (!fwrite($archivo, $contenido)) {
					$error = 1;
					$msj = "No se ha podido escribir en el archivo.";
				}
				
				@fclose($archivo);
				if ($error == 0) {
					$msj = "Datos actualizados...";
				}
			}

			if (!isset($_GET['accion'])) {
				@include("../datos.php"); 
				?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<?php
							if (isset($msj)) {
								?>
						<div class="alert alert-error">
							<a class="close" data-dismiss="alert" href="#">&times;</a>
							<p style="margin: 0px;"><?php echo($msj); ?></p>
						</div>
								<?php
							} ?>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Empresa/Instituci&oacute;n</a></li>
							<li><a href="#tab02" data-toggle="tab">Conexion a la base de datos</a></li>
							<li><a href="#tab03" data-toggle="tab">Otras variables</a></li>
							<li><a href="#tab04" data-toggle="tab">Definici&oacute;n de colores</a></li>
							<li><a href="#tab05" data-toggle="tab">Publicidad</a></li>
							<?php
								//	verifico si es administrador para mostrar opciones de configuracion
								if ($_SESSION['usuario_id'] == 0) {
									?>
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" border="0" /> M&aacute;s opciones</a>
									<ul class="dropdown-menu">
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=config&accion=cargartablas"); ?>">Cargar Tablas</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=config&accion=cargarpaises"); ?>">Cargar Paises</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=config&accion=cargarmenu"); ?>">Cargar Menu</a></li>
									</ul>
								</li>
								<?php
								} 
							?>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Nombre del sitio</label>
									<div class="controls">
										<input type="text" id="sitio_nombre" name="sitio_nombre" title="Nombre del sitio" style="width: 99%" size="50" value="<?php if (isset($sitio_nombre)) { echo($sitio_nombre); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">URL del sitio</label>
									<div class="controls">
										<input type="text" id="sitio_url" name="sitio_url" title="URL del sitio" style="width: 99%" size="50" value="<?php if (isset($sitio_url)) { echo($sitio_url); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Eslogan del sitio</label>
									<div class="controls">
										<input type="text" id="sitio_eslogan" name="sitio_eslogan" title="Eslogan del sitio" style="width: 99%" size="50" value="<?php if (isset($sitio_eslogan)) { echo($sitio_eslogan); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Direcci&oacute;n</label>
									<div class="controls">
										<input type="text" id="sitio_direccion" name="sitio_direccion" title="Direcci&oacute;n del sitio" style="width: 99%" size="50" value="<?php if (isset($sitio_direccion)) { echo($sitio_direccion); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Tel&eacute;fonos</label>
									<div class="controls">
										<input type="text" id="sitio_telefono" name="sitio_telefono" title="Tel&eacute;fonos del sitio" style="width: 50%" size="50" value="<?php if (isset($sitio_telefono)) { echo($sitio_telefono); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Logo del sitio</label>
									<div class="controls">
										<input type="checkbox" id="sitio_logo_mostrar" name="sitio_logo_mostrar" title="Habilitar logo del sitio" value="s" <?php if (isset($sitio_logo_mostrar) and ($sitio_logo_mostrar == "s")) { echo("checked='checked'"); } ?> <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
										<input type="text" id="sitio_logo_archivo" name="sitio_logo_archivo" title="Nombre del archivo con el logo del sitio" size="40%" value="<?php if (isset($sitio_logo_archivo)) { echo($sitio_logo_archivo); } else { echo("logo.jpg"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<hr />
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Correo electr&oacute;nico oficial</label>
											<div class="controls">
												<input type="text" id="sitio_email" name="sitio_email" title="Correo electr&oacute;nico oficial" size="50" style="width: 99%" value="<?php if (isset($sitio_email)) { echo($sitio_email); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo electr&oacute;nico del administrador</label>
											<div class="controls">
												<input type="text" id="sitio_email_admin" name="sitio_email_admin" title="Correo electr&oacute;nico del administrador" size="50" style="width: 99%" value="<?php if (isset($sitio_email_admin)) { echo($sitio_email_admin); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo electr&oacute;nico de confirmaci&oacute;n de lectura</label>
											<div class="controls">
												<input type="text" id="sitio_email_confirmacion" name="sitio_email_confirmacion" title="Correo electr&oacute;nico de confirmaci&oacute;n de lectura" size="50" style="width: 99%" value="<?php if (isset($sitio_email_confirmacion)) { echo($sitio_email_confirmacion); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
									</div>
									<div class="span6">
										<h2>Plantilla</h2>
										<div class="control-group">
											<label class="control-label">Plantilla HTML del sitio</label>
											<div class="controls">
												<select id="sitio_plantilla" name="sitio_plantilla" title="Plantilla HTML del sitio" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required>
													<option value=""></option>
													<?php
														//revisa la carpeta plantillas, busca todos los archivos HTML y lee su titulo
														$carpeta = "../../".$path_plantillas;
														$d = dir($carpeta);
														while (false !== ($entrada = $d->read())) {
															//Los diseños para boletines estan en carpetas individuales
															//verifica si es una carpeta y busca archivos HTML en su interior
															if (is_dir($carpeta.$entrada)and($entrada != ".")and($entrada != "..")) {
																?>
														<optgroup label="<?php echo($entrada); ?>">
																<?php
																$d2 = dir($carpeta.$entrada);
																while (false !== ($entrada2 = $d2->read())) {
																	//verifico si es archivo y su extension
																	if (is_file($carpeta.$entrada."/".$entrada2)) {
																		$res = explode(".", $entrada2);
																		$extension = $res[count($res) - 1];
																		if (($extension == "html") or ($extension == "htm")) {
																			$file = fopen($carpeta.$entrada."/".$entrada2, "r");
																			$size = filesize($carpeta.$entrada."/".$entrada2);
																			$contenidofile = fread($file, $size);
																			//obtengo el titulo del archivo HTML
																			$titulo = substr($contenidofile, strpos($contenidofile, "<title>") + 7, (strpos($contenidofile, "</title>") - (strpos($contenidofile, "<title>") + 7))); ?>
															<option value="<?php echo($entrada."/".$entrada2); ?>" <?php if (isset($sitio_plantilla) and ($sitio_plantilla == $entrada."/".$entrada2)) { echo("selected='selected'");} ?>><?php echo(trim($titulo)); ?></option>
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
												<input type="hidden" id="old_sitio_estilo" name="old_sitio_estilo" value="<?php if (isset($sitio_estilo)) { echo($sitio_estilo); } ?>" />
												<select id="sitio_estilo" name="sitio_estilo" title="Estilo CSS del sitio" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required>
													<option value="">&laquo;-- seleccionar plantilla</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="tab02" class="tab-pane">
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Tipo de base de datos</label>
											<div class="controls">
												<select id="tipo_database" name="tipo_database" title="Tipo de base de datos" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required> 
													<option value=""></option>
													<option value="mysql" <?php if (isset($tipo_database) and ($tipo_database == "mysql")) { echo("selected='selected'"); } ?>>MySql</option>
													<option value="mysqli" <?php if (isset($tipo_database) and ($tipo_database == "mysqli")) { echo("selected='selected'"); } ?>>MySqli</option>
													<option value="ibase" <?php if (isset($tipo_database) and ($tipo_database == "ibase")) { echo("selected='selected'"); } ?>>Interbase</option>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Servidor</label>
											<div class="controls">
												<input type="text" id="server" name="server" title="Servidor" value="<?php if (isset($server)) { echo($server); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Base de datos</label>
											<div class="controls">
												<input type="text" id="database" name="database" title="Base de datos" value="<?php if (isset($database)) { echo($database); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nombre de Usuario</label>
											<div class="controls">
												<input type="text" id="user" name="user" title="Nombre de Usuario" value="<?php if (isset($user)) { echo($user); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Contrase&ntilde;a</label>
											<div class="controls">
												<input type="text" id="password" name="password" title="Contrase&ntilde;a" value="<?php if (isset($password)) { echo($password); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> required />
											</div>
										</div>
									</div>
									<div class="span6">
										<h2>Carpetas de trabajo</h2>
										<div class="control-group">
											<label class="control-label">Imagenes</label>
											<div class="controls">
												<input type="text" id="path_imagenes" name="path_imagenes" title="Imagenes" size="50" value="<?php if (isset($path_imagenes)) { echo($path_imagenes); } else { echo("images/"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Archivos</label>
											<div class="controls">
												<input type="text" id="path_archivos" name="path_archivos" title="Archivos" size="50" value="<?php if (isset($path_archivos)) { echo($path_archivos); } else { echo("archivos/"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Modulos</label>
											<div class="controls">
												<input type="text" id="path_modulos" name="path_modulos" title="Modulos" size="50" value="<?php if (isset($path_modulos)) { echo($path_modulos); } else { echo("xpanel/modulos/"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Scripts</label>
											<div class="controls">
												<input type="text" id="path_scripts" name="path_scripts" title="Scripts" size="50" value="<?php if (isset($path_scripts)) { echo($path_scripts); } else { echo("xpanel/scripts/"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Plantillas</label>
											<div class="controls">
												<input type="text" id="path_plantillas" name="path_plantillas" title="Plantillas" size="50" value="<?php if (isset($path_plantillas)) { echo($path_plantillas); } else { echo("templates/"); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Niveles de Acceso</label>
									<div class="controls">
										<textarea id="niveles_acceso" name="niveles_acceso" title="Niveles de Acceso" rows="10" cols="40" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><?php if (isset($niveles_acceso)) { for ($i=0; $i<count($niveles_acceso); $i++) { echo("\r\n".trim($niveles_acceso[$i])); } } else { echo("Administrador\nOperador\nCliente\nEstudiante\nVisitante"); } ?></textarea>
									</div>
								</div>
							</div>

							<div id="tab03" class="tab-pane">
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Estados</label>
											<div class="controls">
												<textarea id="estados" name="estados" title="Estados" rows="2" cols="40" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><?php if (isset($estados)) { for ($i=0; $i<count($estados); $i++) { echo("\r\n".trim($estados[$i])); } } else { echo("NO\nSI"); } ?></textarea>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipos de Documento</label>
											<div class="controls">
												<textarea id="tipo_documento" name="tipo_documento" title="Tipos de Documento" rows="7" cols="40" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><?php if (isset($tipo_documento)) { for ($i=0; $i<count($tipo_documento); $i++) { echo("\r\n".trim($tipo_documento[$i])); } } else { echo("DNI\nCI\nLC\nLE\nPSP"); } ?></textarea>
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Cantidad de filas por pagina</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="cantidad_filas_x_pagina" data-limite="0" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
													<input type="text" id="cantidad_filas_x_pagina" name="cantidad_filas_x_pagina" title="Cantidad de filas por pagina" value="<?php if (isset($cantidad_filas_x_pagina)) { echo($cantidad_filas_x_pagina); } else { echo("12"); } ?>" maxlength="3" class="input-mini" style="text-align: right;width: 25px;" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="cantidad_filas_x_pagina" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Cantidad de enlaces por pagina</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="cantidad_enlaces_x_pagina" data-limite="0" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
													<input type="text" id="cantidad_enlaces_x_pagina" name="cantidad_enlaces_x_pagina" title="Cantidad de enlaces por pagina" value="<?php if (isset($cantidad_enlaces_x_pagina)) { echo($cantidad_enlaces_x_pagina); } else { echo("2"); } ?>" maxlength="3" class="input-mini" style="text-align: right;width: 25px;" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="cantidad_enlaces_x_pagina" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Cantidad de decimales</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="cantidad_decimales" data-limite="0" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> ><i class="icon-minus"></i></button>
													<input type="text" id="cantidad_decimales" name="cantidad_decimales" title="Cantidad de decimales" value="<?php if (isset($cantidad_decimales)) { echo($cantidad_decimales); } else { echo("0"); } ?>" maxlength="3" class="input-mini" style="text-align: right;width: 25px;" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="cantidad_decimales" class="btn" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> ><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>
										<hr />
										<h2>Mapas</h2>
										<div class="control-group">
											<label class="control-label">Opciones</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<select name="sitio_apimapa" id="sitio_apimapa" title="Nombre de API" style="width: auto;" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>>
														<option value="leaflet" <?php if (isset($sitio_apimapa) and ($sitio_apimapa == "leaflet")) { echo("selected='selected'"); } ?>>leaflet</option>
														<option value="googlemaps" <?php if (isset($sitio_apimapa) and ($sitio_apimapa == "googlemaps")) { echo("selected='selected'"); } ?>>googlemaps</option>
													</select>
													<input type="text" id="sitio_typemapa" name="sitio_typemapa" value="<?php if (isset($sitio_typemapa)) { echo($sitio_typemapa); } ?>" title="Tipo de Mapa" class="input-medium" maxlength="50" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
													<input type="text" id="sitio_keymapa" name="sitio_keymapa" value="<?php if (isset($sitio_keymapa)) { echo($sitio_keymapa); } ?>" title="Key API" class="input-medium" maxlength="50" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Ubicaci&oacute;n</label>
											<div class="controls">
												<div class="input-append">
													<input type="text" name="latitud" id="latitud" value="<?php if (isset($latitud)) { echo($latitud); } else { echo($mapa_latitud); } ?>" class="input-medium" title="Latitud" value="" />
													<input type="text" name="longitud" id="longitud" value="<?php if (isset($longitud)) { echo($longitud); } else { echo($mapa_longitud); } ?>" class="input-medium" title="Longitud" value="" />
													<button type="button" id="editarCoordenadas" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalMapaUbicacion">Definir ubicaci&oacute;n</button>
												</div>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Etiquetas de Titulo</label>
											<div class="controls">
												<input type="text" id="tags_title" name="tags_title" size="50" maxlength="100" title="Titulo" value="<?php if (isset($tags_title)) { echo($tags_title); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Palabras clave</label>
											<div class="controls">
												<input type="text" id="tags_keywords" name="tags_keywords" size="50" maxlength="255" title="Palabras clave" value="<?php if (isset($tags_keywords)) { echo($tags_keywords); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Descripci&oacute;n</label>
											<div class="controls">
												<input type="text" id="tags_description" name="tags_description" size="50" maxlength="255" title="Descripci&oacute;n" value="<?php if (isset($tags_description)) { echo($tags_description); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Usuario Google Analytics</label>
											<div class="controls">
												<input type="text" id="ga_usuario" name="ga_usuario" title="Usuario Google Analytics" value="<?php if (isset($ga_usuario)) { echo($ga_usuario); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Contrase&ntilde;a Google Analytics</label>
											<div class="controls">
												<input type="text" id="ga_password" name="ga_password" title="Contrase&ntilde;a Google Analytics" value="<?php if (isset($ga_password)) { echo($ga_password); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">ID Google Analytics</label>
											<div class="controls">
												<input type="text" id="ga_id" name="ga_id" title="ID Google Analytics" value="<?php if (isset($ga_id)) { echo($ga_id); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">URL Facebook</label>
											<div class="controls">
												<input type="text" id="sitio_url_facebook" name="sitio_url_facebook" title="URL Facebook" value="<?php if (isset($sitio_url_facebook)) { echo($sitio_url_facebook); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">URL Grupo Facebook</label>
											<div class="controls">
												<input type="text" id="sitio_url_facebook_group" name="sitio_url_facebook_group" title="URL Grupo Facebook" value="<?php if (isset($sitio_url_facebook_group)) { echo($sitio_url_facebook_group); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">URL Twitter</label>
											<div class="controls">
												<input type="text" id="sitio_url_twitter" name="sitio_url_twitter" title="URL Twitter" value="<?php if (isset($sitio_url_twitter)) { echo($sitio_url_twitter); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">URL LinkedIn</label>
											<div class="controls">
												<input type="text" id="sitio_url_linkedin" name="sitio_url_linkedin" title="URL LinkedIn" value="<?php if (isset($sitio_url_linkedin)) { echo($sitio_url_linkedin); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">URL Google+</label>
											<div class="controls">
												<input type="text" id="sitio_url_google" name="sitio_url_google" title="URL Google+" value="<?php if (isset($sitio_url_google)) { echo($sitio_url_google); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">URL Youtube</label>
											<div class="controls">
												<input type="text" id="sitio_url_youtube" name="sitio_url_youtube" title="URL Youtube" value="<?php if (isset($sitio_url_youtube)) { echo($sitio_url_youtube); } else { echo(""); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="tab04" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Categorias de Productos</label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra categoria" style="margin-bottom: 10px;" />
										<?php
											$i = 1;
											if (isset($colores_categoriasProductos)) {
												$i = count($colores_categoriasProductos); ?>
										<input type="hidden" id="indicadorCategoria" value="<?php echo($i); ?>" />
												<?php
												for ($i=0; $i<count($colores_categoriasProductos); $i++) {
													$array_datos = explode("|", $colores_categoriasProductos[$i]); ?>
										<div id="adjuntos_categoria<?php echo($i+1); ?>" class="cloneCategoria">
											<label class="control-label"><?php echo("[CATEGORIA".sprintf("%02s", $i+1)."]"); ?></label>
											<div class="controls">
												<input type="text" value="<?php echo($array_datos[0]); ?>" class="input-large" id="categoria_nombre" name="categoria_nombre[]" />
												<input type="color" value="<?php echo($array_datos[1]); ?>" class="input-small" id="categoria_color" name="categoria_color[]" title="Color de fondo" />
												<input type="button" id="btnEliminarClone" data-contenedor="adjuntos_categoria" class="btn btn-danger" value="Eliminar" />
											</div>
										</div>
													<?php
												}
											} else {
												?>
										<input type="hidden" id="indicadorCategoria" value="<?php echo($i); ?>" />
										<div id="adjuntos_categoria<?php echo($i); ?>" class="cloneCategoria">
											<label class="control-label"><?php echo("[CATEGORIA".sprintf("%02s", $i)."]"); ?></label>
											<div class="controls">
												<input type="text" class="input-large" id="categoria_nombre" name="categoria_nombre[]" />
												<input type="color" class="input-small" id="categoria_color" name="categoria_color[]" />
											</div>
										</div>
												<?php
											} 
										?>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Estados de las Ventas</label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otro estado" style="margin-bottom: 10px;" />
										<?php
											$i = 1;
											if (isset($colores_estadosVentas)) {
												$i = count($colores_estadosVentas); 
												?>
										<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
												<?php
												for ($i=0; $i<count($colores_estadosVentas); $i++) {
													$array_datos = explode("|", $colores_estadosVentas[$i]); ?>
										<div id="adjuntos_estado<?php echo($i+1); ?>" class="cloneEstado">
											<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i+1)."]"); ?></label>
											<div class="controls">
												<input type="text" value="<?php echo($array_datos[0]); ?>" class="input-large" id="estadoVentas_nombre" name="estadoVentas_nombre[]" />
												<input type="color" value="<?php echo($array_datos[1]); ?>" class="input-small" id="estadoVentas_color" name="estadoVentas_color[]" />
												<input type="button" id="btnEliminarClone" data-contenedor="adjuntos_estado" class="btn btn-danger" value="Eliminar" />
											</div>
										</div>
													<?php
												}
											} else {
												?>
												<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
												<div id="adjuntos_estado<?php echo($i); ?>" class="cloneEstado">
													<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i)."]"); ?></label>
													<div class="controls">
														<input type="text" class="input-large" id="estadoVentas_nombre" name="estadoVentas_nombre[]" />
														<input type="color" class="input-small" id="estadoVentas_color" name="estadoVentas_color[]" />
													</div>
												</div>
												<?php
											}
										?>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Ventas Pagadas o No</label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otro Tipo de Pago" style="margin-bottom: 10px;" />
										<?php
											$i = 1;
											if (isset($colores_pagadoVentas)) {
												$i = count($colores_pagadoVentas); 
												?>
										<input type="hidden" id="indicadorPagado" value="<?php echo($i); ?>" />
												<?php
												for ($i=0; $i<count($colores_pagadoVentas); $i++) {
													$array_datos = explode("|", $colores_pagadoVentas[$i]); ?>
										<div id="adjuntos_pagado<?php echo($i+1); ?>" class="clonePagado">
											<label class="control-label"><?php echo("[PAGADO".sprintf("%02s", $i+1)."]"); ?></label>
											<div class="controls">
												<input type="text" value="<?php echo($array_datos[0]); ?>" class="input-large" id="pagadoVentas_nombre" name="pagadoVentas_nombre[]" />
												<input type="color" value="<?php echo($array_datos[1]); ?>" class="input-small" id="pagadoVentas_color" name="pagadoVentas_color[]" />
												<input type="button" id="btnEliminarClone" data-contenedor="adjuntos_pagado" class="btn btn-danger" value="Eliminar" />
											</div>
										</div>
													<?php
												}
											} else {
												?>
											<input type="hidden" id="indicadorPagado" value="<?php echo($i); ?>" />
											<div id="adjuntos_pagado<?php echo($i); ?>" class="clonePagado">
												<label class="control-label"><?php echo("[PAGADO".sprintf("%02s", $i)."]"); ?></label>
												<div class="controls">
													<input type="text" class="input-large" id="pagadoVentas_nombre" name="pagadoVentas_nombre[]" />
													<input type="color" class="input-small" id="pagadoVentas_color" name="pagadoVentas_color[]" />
												</div>
											</div>
												<?php
											}
										?>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Estados de los Servicios T&eacute;cnicos</label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otro estado" style="margin-bottom: 10px;" />
										<?php
											$i = 1;
											if (isset($colores_estadosServiciosTecnicos)) {
												$i = count($colores_estadosServiciosTecnicos); ?>
										<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
												<?php
												for ($i=0; $i<count($colores_estadosServiciosTecnicos); $i++) {
													$array_datos = explode("|", $colores_estadosServiciosTecnicos[$i]); ?>
										<div id="adjuntos_estado<?php echo($i+1); ?>" class="cloneEstado">
											<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i+1)."]"); ?></label>
											<div class="controls">
												<input type="text" value="<?php echo($array_datos[0]); ?>" class="input-large" id="estadoServiciosTecnicos_nombre" name="estadoServiciosTecnicos_nombre[]" />
												<input type="color" value="<?php echo($array_datos[1]); ?>" class="input-small" id="estadoServiciosTecnicos_color" name="estadoServiciosTecnicos_color[]" />
												<input type="button" id="btnEliminarClone" data-contenedor="adjuntos_estado" class="btn btn-danger" value="Eliminar" />
											</div>
										</div>
													<?php
												}
											} else {
												?>
										<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
										<div id="adjuntos_estado<?php echo($i); ?>" class="cloneEstado">
											<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i)."]"); ?></label>
											<div class="controls">
												<input type="text" class="input-large" id="estadoServiciosTecnicos_nombre" name="estadoServiciosTecnicos_nombre[]" />
												<input type="color" class="input-small" id="estadoServiciosTecnicos_color" name="estadoServiciosTecnicos_color[]" />
											</div>
										</div>
												<?php
											} 
										?>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Estados de los Pedidos</label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otro estado" style="margin-bottom: 10px;" />
										<?php
											$i = 1;
											if (isset($colores_estadosPedidos)) {
												$i = count($colores_estadosPedidos); ?>
										<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
												<?php
												for ($i=0; $i<count($colores_estadosPedidos); $i++) {
													$array_datos = explode("|", $colores_estadosPedidos[$i]); ?>
										<div id="adjuntos_estado<?php echo($i+1); ?>" class="cloneEstado">
											<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i+1)."]"); ?></label>
											<div class="controls">
												<input type="text" value="<?php echo($array_datos[0]); ?>" class="input-large" id="estadoPedidos_nombre" name="estadoPedidos_nombre[]" />
												<input type="color" value="<?php echo($array_datos[1]); ?>" class="input-small" id="estadoPedidos_color" name="estadoPedidos_color[]" />
												<input type="button" id="btnEliminarClone" data-contenedor="adjuntos_estado" class="btn btn-danger" value="Eliminar" />
											</div>
										</div>
													<?php
												}
											} else {
												?>
										<input type="hidden" id="indicadorEstado" value="<?php echo($i); ?>" />
										<div id="adjuntos_estado<?php echo($i); ?>" class="cloneEstado">
											<label class="control-label"><?php echo("[ESTADO".sprintf("%02s", $i)."]"); ?></label>
											<div class="controls">
												<input type="text" class="input-large" id="estadoPedidos_nombre" name="estadoPedidos_nombre[]" />
												<input type="color" class="input-small" id="estadoPedidos_color" name="estadoPedidos_color[]" />
											</div>
										</div>
											<?php
											} 
										?>
									</div>
								</div>
							</div>

							<div id="tab05" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Publicidad Portada</label>
									<div class="controls">
										<input type="checkbox" id="publicidad_portada_mostrar" name="publicidad_portada_mostrar" title="Habilitar Publicidad Portada" value="s" <?php if (isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "s")) { echo("checked='checked'"); } ?> <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
										<input type="text" id="publicidad_portada_archivo" name="publicidad_portada_archivo" title="Nombre del archivo de Publicidad en Portada" size="40%" value="<?php if (isset($publicidad_portada_archivo)) { echo($publicidad_portada_archivo); } ?>" <?php if ((isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
										<input type="file" id="publicidad_portada_file" name="publicidad_portada_file" title="Subir imagen para la publicidad de portada" accept=".jpeg,.jpg,.png,.gif" <?php if ((isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										&nbsp;
										<input type="text" id="publicidad_portada_enlace" name="publicidad_portada_enlace" title="URL de la Publicidad en Portada" size="40%" value="<?php if (isset($publicidad_portada_enlace)) { echo($publicidad_portada_enlace); } ?>" <?php if ((isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Publicidad Horizontal</label>
									<div class="controls">
										<input type="checkbox" id="publicidad_h_mostrar" name="publicidad_h_mostrar" title="Habilitar Publicidad Horizontal" value="s" <?php if (isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "s")) { echo("checked='checked'"); } ?> <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
										<input type="text" id="publicidad_h_archivo" name="publicidad_h_archivo" title="Nombre del archivo de Publicidad Horizontal" size="40%" value="<?php if (isset($publicidad_h_archivo)) { echo($publicidad_h_archivo); } ?>" <?php if ((isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
										<input type="file" id="publicidad_h_file" name="publicidad_h_file" title="Subir imagen para la publicidad horizontal" accept=".jpeg,.jpg,.png,.gif" <?php if ((isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										&nbsp;
										<input type="text" id="publicidad_h_enlace" name="publicidad_h_enlace" title="URL de la Publicidad Horizontal" size="40%" value="<?php if (isset($publicidad_h_enlace)) { echo($publicidad_h_enlace); } ?>" <?php if ((isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Publicidad Vertical</label>
									<div class="controls">
										<input type="checkbox" id="publicidad_v_mostrar" name="publicidad_v_mostrar" title="Habilitar Publicidad Vertical" value="s" <?php if (isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "s")) { echo("checked='checked'"); } ?> <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
										<input type="text" id="publicidad_v_archivo" name="publicidad_v_archivo" title="Nombre del archivo de Publicidad Vertical" size="40%" value="<?php if (isset($publicidad_v_archivo)) { echo($publicidad_v_archivo); } ?>" <?php if ((isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
										<input type="file" id="publicidad_v_file" name="publicidad_v_file" title="Subir imagen para la publicidad vertical" accept=".jpeg,.jpg,.png,.gif" <?php if ((isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
										&nbsp;
										<input type="text" id="publicidad_v_enlace" name="publicidad_v_enlace" title="URL de la Publicidad Vertical" size="40%" value="<?php if (isset($publicidad_v_enlace)) { echo($publicidad_v_enlace); } ?>" <?php if ((isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "")) or (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0))) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Frase de la semana</label>
									<div class="controls">
										<textarea id="frase" name="frase" title="Frase de la semana" rows="5" cols="50" style="width: 99%" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?>><?php if (isset($frase)) { echo($frase); } ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Autor</label>
									<div class="controls">
										<input type="text" id="frase_autor" name="frase_autor" title="Autor" size="50" style="width: 99%" value="<?php if (isset($frase_autor)) { echo($frase_autor); } ?>" <?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { echo("disabled='disabled'"); } ?> />
									</div>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<?php if (isset($datos_acceso) and ($datos_acceso["VER"] == 1) and ($datos_acceso["INSERTAR"] == 0) and ($datos_acceso["MODIFICAR"] == 0)) { ?>
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo("./"); ?>';" />
							<?php } ?>
							<?php if (isset($datos_acceso) and (($datos_acceso["INSERTAR"] == 1) or ($datos_acceso["MODIFICAR"] == 1))) { ?>
							<input type="submit" class="btn btn-large btn-primary" name="guardar" value="Guardar datos" />
							<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo("./"); ?>';" />
							<?php } ?>
						</div>
					</div>
				</form>
				<?php
				include_once('./mapaUbicacion.php');
			}

			//	-----	Cargar Tablas/Paises/Contenidos

			if (isset($_GET['accion']) and (($_GET['accion'] == "cargartablas") or($_GET['accion'] == "cargarpaises") or ($_GET['accion'] == "cargarmenu"))) {
				if ($_GET['accion'] == "cargartablas") {
					$tipo = "Tablas";
				}
				if ($_GET['accion'] == "cargarpaises") {
					$tipo = "Paises";
				}
				if ($_GET['accion'] == "cargarmenu") {
					$tipo = "Menu de Contenidos";
				} 
				?>
				<form class="form-alert" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cargar"); ?>" method="post">
					<h3>Confirmaci&oacute;n</h3>
					<input type="hidden" name="tipo_carga" value="<?php echo($_GET['accion']); ?>" />
					<div class="body-alert">
						<img src="../images/confirmacion.png" alt="" title="" style="float: left;margin: 10px;" />
						<p>Esta usted seguro que desea cargar <strong><?php echo($tipo); ?></strong>?</p>
					</div>
					<div class="form-actions">
						<input type="submit" class="btn btn-large btn-primary" value="Si" style="width: 100px;" />
						<input type="reset" class="btn btn-large" value="No" style="width: 100px;" onclick="javascript:window.location.href='index.php';" />
					</div>
				</form>
				<?php
			}

			if (isset($_GET['accion']) and ($_GET['accion'] == "cargar")) {
				if ($_POST['tipo_carga'] == "cargartablas") {
					$ArchivoSQL = $sitio_url.$path_scripts."sql/crear_BD_MySQL_XPANEL3_SuperZonaGames.sql";
					$ArchivoFuente = "../../".$path_scripts."sql/crear_BD_MySQL_XPANEL3_SuperZonaGames.sql";
				}
				if ($_POST['tipo_carga'] == "cargarpaises") {
					$ArchivoSQL = $sitio_url.$path_scripts."sql/paises.sql";
					$ArchivoFuente = "../../".$path_scripts."sql/paises.sql";
				}
				if ($_POST['tipo_carga'] == "cargarmenu") {
					$ArchivoSQL = $sitio_url.$path_scripts."sql/menu_modulos.sql";
					$ArchivoFuente = "../../".$path_scripts."sql/menu_modulos.sql";
				}
				if (verificar_url($ArchivoSQL)) {
					$cantidad_registros = 0;
					//	$contenido = file_get_contents($ArchivoSQL);
					//	$contenido = file_get_contents($ArchivoFuente);
					$contenido = obtenerDatosArchivo($ArchivoSQL);
					$linea = explode(";", $contenido);
					for ($i=0; $i<count($linea); $i++) {
						if ((count(trim($linea[$i])) > 0) and (trim($linea[$i]) != "")) {
							$registros = $conexion->Query(trim($linea[$i]));
							$cantidad_registros++;
						}
					}
					$msj = "Se han insertado <strong>".$cantidad_registros."</strong> registros.";
				} else {
					$msj = "El archivo <strong>".$ArchivoFuente."</strong> no existe.";
				} 
				?>
				<form class="form-alert">
					<h3>Informaci&oacute;n</h3>
					<div class="body-alert">
						<img src="../images/informacion.png" alt="" title="" style="float: left;margin: 10px;" />
						<p><?php echo($msj); ?></p>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='index.php';" />
					</div>
				</form>
				<?php
			}
			//	-----	Fin Cargar Tablas/Paises/Contenidos
		}
		//	-----	Fin CONFIGURACION

		//	-----	PAISES
		if (isset($_GET['menu']) and ($_GET['menu'] == "paises")) {
			//	Listado
			if (!isset($_GET['accion'])) {
				if (isset($_GET['texto'])) {
					$query = "SELECT CODIGO_PAIS,PAIS FROM paises WHERE (codigo_pais LIKE '%".$_GET['texto']."%') or (pais LIKE '%".$_GET['texto']."%') ORDER BY pais";
				} else {
					$query = "SELECT CODIGO_PAIS,PAIS FROM paises ORDER BY pais";
				}
				showTable2(
					$conexion,
					$query,
					array("CODIGO_PAIS"=>"C&oacute;digo",
						"PAIS"=>"Nombre"),
					array(0=>"CODIGO_PAIS"),
					$cantidad_filas_x_pagina,
					true,
					true,
					$datos_acceso
				);
			}

			//Nuevo
			if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">C&oacute;digo</label>
									<div class="controls">
										<input type="text" id="codigo_pais" name="codigo_pais" class="input-mini" maxlength="2" size="3" title="C&oacute;digo" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" id="pais" name="pais" size="50" maxlength="255" title="Nombre" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar
			if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
				//verifico si el CODIGO_PAIS existe
				$registros = $temp->Query("SELECT * FROM paises WHERE (codigo_pais = '".strtoupper(trim($_POST['codigo_pais']))."')");
				if ($registros->RecordCount() > 0) {
					while (!$registros->EOF) {
						?>
						<form class="form-alert">
							<h3>Error</h3>
							<div class="body-alert">
								<img src="../images/exclamacion.png" alt="" title="" />
								<p>El codigo de pa&iacute;s <strong><?php echo(strtoupper(trim($_POST['codigo_pais']))); ?></strong> ya existe en la base de datos, y esta registrado con el nombre <strong><?php echo($registros->fields["PAIS"]); ?></strong>.</p>
							</div>
							<div class="form-actions">
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							</div>
						</form>
						<?php
						$registros->MoveNext();
					}
				} else {
					//inserto los datos
					$conexion->Query("INSERT INTO paises (CODIGO_PAIS,PAIS) VALUES('". strtoupper(trim($_POST['codigo_pais']))."','".$_POST['pais']."')");
					$id = $conexion->Insert_ID();

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
					echo("</script>");
				}
			}

			//Editar
			if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM paises WHERE (codigo_pais = '".$id_modo_editar."')");
				while (!$registros->EOF) {
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=update"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active" style="min-height: 100px;">
									<div class="control-group">
										<label class="control-label">C&oacute;digo</label>
										<div class="controls">
											<input type="text" name="codigo_pais" value="<?php echo($registros->fields["CODIGO_PAIS"]); ?>" class="input-mini" maxlength="2" size="3" title="C&oacute;digo" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" name="pais" value="<?php echo($registros->fields["PAIS"]); ?>" size="50" maxlength="255" title="Nombre" style="width: 99%;" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<?php if ($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Update
			if (isset($_GET['accion'])and($_GET['accion'] == "update")) {
				//actualizo los datos
				echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");

				$conexion->Query("UPDATE paises SET codigo_pais = '".strtoupper(trim($_POST['codigo_pais']))."', pais = '".$_POST['pais']."' WHERE (codigo_pais = '".$_POST['codigo_pais']."')");

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."'");
				echo("</script>");
			}

			//	Eliminar
			if (isset($_GET['accion'])and($_GET['accion'] == "eliminar")) {
				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						//elimino el registro
						$conexion->Query("DELETE FROM paises WHERE codigo_pais = '".$valor."'");
					}
					echo("{ \"estado\" : \"ok\" }");
				}
			}
		}
		//	-----	Fin PAISES

		include("../footer.php");
		?>
	</body>
</html>
