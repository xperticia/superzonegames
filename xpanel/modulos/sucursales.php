<?php
	include("../config.php");
	require("../verifica.php");
	$nivel_acceso = 5;// Nivel de acceso para esta pagina.
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

		//	-----	SUCURSALES
		if(isset($_GET['menu']) && ($_GET['menu'] == "sucursales")) {
			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						ID_SUCURSAL,
						NOMBRE,
						DOMICILIO,
						TELEFONO,
						EMAIL,
						IFNULL(
							(select count(DISTINCT ID_USUARIO) from privilegios WHERE (ID_SUCURSAL = sucursales.id_sucursal))
							,0
						) CANTIDAD_USUARIOS,
						IFNULL(tipo_moneda,'') TIPO_MONEDA,
						ACTIVO 
					FROM sucursales 
				";
				if(isset($_GET['texto'])) {
					$query .= "WHERE ( 
						(upper(nombre) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(domicilio) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(telefono) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(email) LIKE upper('%".$_GET['texto']."%')) 
					) 
					";
				}
				$query .= "ORDER BY nombre";
				showTable2($conexion,
					$query,
					array("ID_SUCURSAL"=>"Id",
						"NOMBRE"=>"Nombre",
						"DOMICILIO"=>"Direcci&oacute;n",
						"TELEFONO"=>"Tel&eacute;fono",
						"EMAIL"=>"Correo electr&oacute;nico",
						"CANTIDAD_USUARIOS"=>"Usuarios Habilitados",
						"TIPO_MONEDA"=>"Moneda",
						"ACTIVO"=>"Activo"),
					array(0=>"ID_SUCURSAL"),
					$cantidad_filas_x_pagina,true,true,$datos_acceso);
			}

			//Nuevo
			if(isset($_GET['accion']) && ($_GET['accion'] == "nuevo")) { 
				?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Nombre</label>
											<div class="controls">
												<input type="text" id="nombre" name="nombre" size="50" maxlength="255" title="Nombre" style="width: 99%;" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" size="50" maxlength="255" title="Direcci&oacute;n" style="width: 99%;" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tel&eacute;fono</label>
											<div class="controls">
												<input type="text" id="telefono" name="telefono" size="23" maxlength="50" title="Tel&eacute;fono" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo Electr&oacute;nico</label>
											<div class="controls">
												<input type="text" id="email" name="email" size="50" maxlength="255" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" title="Correo Electr&oacute;nico" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Logo</label>
											<div class="controls">
												<input type="file" id="archivo_foto" name="archivo_foto" class="input-file" title="Logo" accept=".jpeg,.jpg,.png,.gif" />
												<span class="help-block">La im&aacute;gen pueden ser de formato JPG, PNG o GIF, y no deben superar los <?php echo(ini_get('post_max_size')) ?> de tama&ntilde;o. Se recomienda que tenga la dimensión de 336 x 220 pixeles, en formato PNG.</span>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Ubicaci&oacute;n</label>
											<div class="controls">
												<div class="input-append">
													<input type="text" name="latitud" id="latitud" value="<?php if(isset($latitud)) { echo($latitud); } else { echo($mapa_latitud); } ?>" class="input-medium" title="Latitud" />
													<input type="text" name="longitud" id="longitud" value="<?php if(isset($longitud)) { echo($longitud); } else { echo($mapa_longitud); } ?>" class="input-medium" title="Longitud" />
													<button type="button" id="editarCoordenadas" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalMapaUbicacion">Definir ubicaci&oacute;n</button>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipo de Moneda a mostrar</label>
											<div class="controls">
												<select name="tipo_moneda" title="Tipo de moneda a mostrar">
													<option value="Bs">Bs - Bolivianos</option>
													<option value="$ar">$ar - Pesos Argentinos</option>
													<option value="$us">$us - Dólares</option>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Sucursal Principal</label>
											<div class="controls">
												<select name="sucursal_principal" title="Sucursal Principal" style="width: auto;">
													<option value="">Sucursal Principal</option>
													<?php
														$registrosSedes = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
														while(!$registrosSedes->EOF) {									
															?>
															<option value="<?php echo($registrosSedes->fields["ID_SUCURSAL"]); ?>"><?php echo($registrosSedes->fields["NOMBRE"]); ?></option>
															<?php
															$registrosSedes->MoveNext();
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="0" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span6">
										<?php
										if($_SESSION['usuario_login'] == 'Administrador'){
											?>
										<div class="control-group">
											<label class="control-label">Categoria de Productos</label>
											<div class="controls">
												<?php
													$tiposProductos = $temp->Query("SELECT TIPO_PRODUCTO FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) and (tipo_producto is not null) and (tipo_producto <> '') GROUP BY tipo_producto ORDER BY tipo_producto");
													if($tiposProductos && ($tiposProductos->RecordCount() > 0)){
														while(!$tiposProductos->EOF) {
															?>
												<h3>Tipo: <strong><?php echo(stripslashes($tiposProductos->fields["TIPO_PRODUCTO"])); ?></strong></h3>
															<?php
															$categoriaProductos = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) and (tipo_producto = '".$tiposProductos->fields["TIPO_PRODUCTO"]."') and (categoria is not null) and (categoria <> '') GROUP BY categoria ORDER BY categoria");
															if($categoriaProductos && ($categoriaProductos->RecordCount() > 0)){
																while(!$categoriaProductos->EOF) {
																	?>
														<label class="checkbox inline">
															<input type="checkbox" name="categoria_productos[]" value="<?php echo($categoriaProductos->fields["CATEGORIA"]); ?>" /><?php echo(stripslashes($categoriaProductos->fields["CATEGORIA"])); ?>
														</label>
																	<?php
																	$categoriaProductos->MoveNext();
																}
															}
															$tiposProductos->MoveNext();
														}
													}
												?>									
											</div>
										</div>
											<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar
			if(isset($_GET['accion']) && ($_GET['accion'] == "insert")) {
				$categoriaProductos = "";
				if(isset($_POST['categoria_productos']) && count($_POST['categoria_productos']) > 0) {
					foreach($_POST['categoria_productos'] as $key=>$val) {
						$categoriaProductos .= $val."|";
					}
				}

				if(isset($_POST['id_sucursal'])) {
					$actualizacion_set = !empty($_POST['id_actualizacion']) ? "id_actualizacion = '".trim($_POST['id_actualizacion'])."', " : '';
					$sucursalPrincipal_set = !empty($_POST['sucursal_principal']) ? "id_principal = '".trim($_POST['sucursal_principal'])."', " : "id_principal = null, ";

					//	actualizo los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE sucursales 
						SET 
							{$actualizacion_set}
							{$sucursalPrincipal_set}
							nombre = '".addslashes(ucfirst($_POST['nombre']))."', 
							domicilio = '".addslashes($_POST['domicilio'])."', 
							telefono = '".addslashes(trim($_POST['telefono']))."', 
							email = '".trim($_POST['email'])."', 
							activo = '".$_POST['activo']."', 
							latitud = '".trim($_POST['latitud'])."', 
							longitud = '".trim($_POST['longitud'])."', 
							tipo_moneda = '".trim($_POST['tipo_moneda'])."'
						WHERE (id_sucursal = '".$_POST['id_sucursal']."')
					");
					$id_contenido = $_POST['id_sucursal'];
					$id_imagenAdjunta = sprintf("SU%08s", $id_contenido);
				} else {
					$actualizacion_set = !empty($_POST['id_actualizacion']) ? $_POST['id_actualizacion'] : 'null';
					$sucursalPrincipal_set = !empty($_POST['sucursal_principal']) ? $_POST['sucursal_principal'] : 'null';

					//inserto los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO sucursales 
						(
							ID_SUCURSAL,
							NOMBRE,
							DOMICILIO,
							TELEFONO,
							EMAIL,
							ACTIVO, 
							LATITUD, 
							LONGITUD,
							TIPO_MONEDA,
							ID_ACTUALIZACION,
							ID_PRINCIPAL
						) VALUES(
							null,
							'".addslashes(ucfirst($_POST['nombre']))."',
							'".addslashes($_POST['domicilio'])."',
							'".addslashes(trim($_POST['telefono']))."', 
							'".trim($_POST['email'])."', 
							'".$_POST['activo']."', 
							'".trim($_POST['latitud'])."', 
							'".trim($_POST['longitud'])."',
							'".trim($_POST['tipo_moneda'])."',
							{$actualizacion_set},
							{$sucursalPrincipal_set}
						)
					");
					$id_contenido = $conexion->Insert_ID();
					$id_imagenAdjunta = sprintf("SU%08s", $id_contenido);

					// obtengo todos los PRIVILEGIOS de los USUARIOS de tipo: "Administrador" en la SUCURSAL ACTUAL
					$datosPrivilegios = $conexion->Execute("SELECT privilegios.* FROM usuarios 
						LEFT JOIN privilegios ON privilegios.ID_USUARIO = usuarios.ID_USUARIO 
						WHERE 
							(tipo_usuario = 'Administrador') 
							and (privilegios.ID_SUCURSAL = '".$_SESSION['usuario_sucursal']."')
					");

					while(!$datosPrivilegios->EOF) {
						// inserto todos los PRIVILEGIOS en la nueva SUCURSAL
						$conexion->Query("INSERT INTO privilegios 
							(
								ID_MENU,
								ID_USUARIO,
								ID_SUCURSAL,
								VER,
								INSERTAR,
								MODIFICAR,
								ELIMINAR
							) VALUES(
								'".$datosPrivilegios->fields["ID_MENU"]."',
								'".$datosPrivilegios->fields["ID_USUARIO"]."',
								'".$id_contenido."',
								'".$datosPrivilegios->fields["VER"]."', 
								'".$datosPrivilegios->fields["INSERTAR"]."', 
								'".$datosPrivilegios->fields["MODIFICAR"]."', 
								'".$datosPrivilegios->fields["ELIMINAR"]."'
							)");

						$datosPrivilegios->MoveNext();
					}			

				}

				if($_SESSION['usuario_login'] == 'Administrador'){
					$conexion->Query("UPDATE sucursales SET categoria_productos = '".$categoriaProductos."' WHERE (id_sucursal = '".$id_contenido."')");
				}

				//	verifico los archivos subidos
				if(isset($_FILES["archivo_foto"])) {
					//obtengo el nombre del archivo y su extension
					$res = explode(".", $_FILES['archivo_foto']['name']);
					$tipo_archivo = $_FILES['archivo_foto']['type'];
					$extension = $res[count($res) - 1];
					$nombre_archivo = $id_imagenAdjunta.".".$extension;

					//verifico si se subio un archivo
					if(is_uploaded_file($_FILES['archivo_foto']['tmp_name'])) {
						//verifico el tipo de archivo
						if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
						($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
							//copio el archivo
							if(copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."sucursales/".$nombre_archivo))) {
								//si no hubo inconvenientes actualiza el nombre del archivo
								$conexion->Query("UPDATE sucursales SET logo = '".$nombre_archivo."' WHERE (id_sucursal = '".$id_contenido."')");
								
								//obtengo la ubicacion de la imagen original y small
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."sucursales/".$nombre_archivo;
								$nombre_completo_imagensmall = "../../".$path_imagenes."sucursales/".$id_imagenAdjunta."_small.".$extension;
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

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Editar
			if(isset($_GET['accion']) && (($_GET['accion'] == "ver") || ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM sucursales WHERE (id_sucursal = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Nombre</label>
												<div class="controls">
													<input type="text" id="nombre" name="nombre" value="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>" size="50" maxlength="255" title="Nombre" style="width: 99%;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>				
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" value="<?php echo(stripslashes($registros->fields["DOMICILIO"])); ?>" size="50" maxlength="255" title="Direcci&oacute;n" style="width: 99%;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tel&eacute;fono</label>				
												<div class="controls">
													<input type="text" id="telefono" name="telefono" value="<?php echo(stripslashes($registros->fields["TELEFONO"])); ?>" size="50" maxlength="255" title="Tel&eacute;fono" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Correo Electr&oacute;nico</label>
												<div class="controls">
													<input type="text" name="email" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" title="Correo Electr&oacute;nico" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Logo
												<?php 
													if(($registros->fields['LOGO'] != null) && file_exists("../../".$path_imagenes."sucursales/".$registros->fields['LOGO'])) { 
														?>
														<a href="<?php echo("../../".$path_imagenes."sucursales/".$registros->fields['LOGO']); ?>" class="Foto" rel="colorbox" title="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE"]))); ?>"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
														| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" data-archivo="<?php echo($registros->fields["LOGO"]); ?>" data-tipo="logo" data-nombre-archivo="<?php echo($registros->fields['LOGO']); ?>" data-texto="Logo de la Sucursal"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
														<?php 
													} else { 
														if(($registros->fields['LOGO'] != null) && !file_exists("../../".$path_imagenes."sucursales/".$registros->fields['LOGO'])) { 
															?>
														<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" data-archivo="<?php echo($registros->fields["LOGO"]); ?>" data-tipo="logo" data-nombre-archivo="<?php echo($registros->fields['LOGO']); ?>" data-texto="Logo de la Sucursal"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php 
														} 
													} 
												?>
												</label>
												<div class="controls">
													<input type="file" name="archivo_foto" class="input-file" title="Logo" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<span class="help-block">La im&aacute;gen pueden ser de formato JPG, PNG o GIF, y no deben superar los <?php echo(ini_get('post_max_size')) ?> de tama&ntilde;o. Se recomienda que tenga la dimensión de 336 x 220 pixeles, en formato PNG.</span>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Ubicaci&oacute;n</label>
												<div class="controls">
													<div class="input-append">
														<input type="text" name="latitud" id="latitud" value="<?php if($registros->fields["LATITUD"] != null) { echo($registros->fields["LATITUD"]); } else { echo($latitud); } ?>" class="input-medium" title="Latitud" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<input type="text" name="longitud" id="longitud" value="<?php if($registros->fields["LONGITUD"] != null) { echo($registros->fields["LONGITUD"]); } else { echo($longitud); } ?>" class="input-medium" title="Longitud" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<button type="button" id="editarCoordenadas" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalMapaUbicacion" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Definir ubicaci&oacute;n</button>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tipo de Moneda a mostrar</label>
												<div class="controls">
													<select name="tipo_moneda" title="Tipo de moneda a mostrar">
														<option value="Bs" <?php if (empty($registros->fields["TIPO_MONEDA"]) || $registros->fields["TIPO_MONEDA"] == 'Bs') { echo("selected='selected'"); } ?>>Bs - Bolivianos</option>
														<option value="$ar" <?php if ($registros->fields["TIPO_MONEDA"] == '$ar') { echo("selected='selected'"); } ?>>$ar - Pesos Argentinos</option>
														<option value="$us" <?php if ($registros->fields["TIPO_MONEDA"] == '$us') { echo("selected='selected'"); } ?>>$us - Dólares</option>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Sucursal Principal</label>
												<div class="controls">
													<select name="sucursal_principal" title="Sucursal Principal" style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value="">Sucursal Principal</option>
														<?php
															$registrosSedes = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) and (id_sucursal <> '{$registros->fields["ID_SUCURSAL"]}') ORDER BY nombre");
															while(!$registrosSedes->EOF) {									
																?>
																<option value="<?php echo($registrosSedes->fields["ID_SUCURSAL"]); ?>" <?php if ($registrosSedes->fields["ID_SUCURSAL"] == $registros->fields["ID_PRINCIPAL"]) { echo("selected='selected'"); } ?>><?php echo($registrosSedes->fields["NOMBRE"]); ?></option>
																<?php
																$registrosSedes->MoveNext();
															}
														?>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[0]); ?>
													</label>
												</div>
											</div>
											<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

										</div>
										<div class="span6">
											<?php
											if($_SESSION['usuario_login'] == 'Administrador'){
												?>
											<div class="control-group">
												<label class="control-label">Categoria de Productos</label>
												<div class="controls">
													<?php
														$tiposProductos = $temp->Query("SELECT TIPO_PRODUCTO FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) and (tipo_producto is not null) and (tipo_producto <> '') GROUP BY tipo_producto ORDER BY tipo_producto");
														if($tiposProductos && ($tiposProductos->RecordCount() > 0)){
															while(!$tiposProductos->EOF) {
																?>
													<h3>Tipo: <strong><?php echo(stripslashes($tiposProductos->fields["TIPO_PRODUCTO"])); ?></strong></h3>
																<?php
																$categoriaProductos = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) and (tipo_producto = '".$tiposProductos->fields["TIPO_PRODUCTO"]."') and (categoria is not null) and (categoria <> '') GROUP BY categoria ORDER BY categoria");
																if($categoriaProductos && ($categoriaProductos->RecordCount() > 0)){
																	while(!$categoriaProductos->EOF) {
																		?>
															<label class="checkbox inline">
																<input type="checkbox" name="categoria_productos[]" value="<?php echo($categoriaProductos->fields["CATEGORIA"]); ?>" <?php if(in_array($categoriaProductos->fields["CATEGORIA"],explode("|",$registros->fields["CATEGORIA_PRODUCTOS"]))) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/><?php echo(stripslashes($categoriaProductos->fields["CATEGORIA"])); ?>
															</label>
																		<?php
																		$categoriaProductos->MoveNext();
																	}
																}
																$tiposProductos->MoveNext();
															}
														}
													?>									
												</div>
											</div>
												<?php
											}
											?>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			if(isset($_GET['accion']) && (($_GET['accion'] == "nuevo") || ($_GET['accion'] == "editar"))) {
				include_once('./mapaUbicacion.php');
			}

			//Eliminar imagen/publicidad
			if(isset($_GET['accion']) && ($_GET['accion'] == "deleteimagen")) {
				//	verifico si el archivo existe
				$nombre_completo_imagenoriginal = "../../".$path_imagenes."sucursales/".$_POST['ida'];
				if(file_exists($nombre_completo_imagenoriginal)) {
					//	obtengo el nombre y extension del archivo
					$res = explode(".", $_POST['ida']);
					$nombre = $res[0];
					$extension = $res[count($res) - 1];
					//	obtengo la ubicacion de la imagen original
					$nombre_completo_imagensmall = "../../".$path_imagenes."sucursales/".$nombre."_small.".$extension;
					//	elimino el archivo
					unlink($nombre_completo_imagenoriginal);
					if(file_exists($nombre_completo_imagensmall)) {
						unlink($nombre_completo_imagensmall);
					}
				}
				$conexion->Query("UPDATE sucursales SET ".$_POST['tipo']." = null WHERE (id_sucursal = '".$_POST['idc']."')");
				echo("{ \"estado\" : \"ok\" }");
			}

			//	Eliminar
			if(isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	elimino las COTIZACIONES
						$registros = $conexion->Query("SELECT * FROM cotizaciones WHERE (id_sucursal = ".$valor.")");
						while(!$registros->EOF) {
							$conexion->Execute("DELETE FROM cotizacion_posee_productos WHERE (id_cotizacion = ".$registros->fields["ID_COTIZACION"].")");
							$registros->MoveNext();
						}
						$conexion->Execute("DELETE FROM cotizaciones WHERE (id_sucursal = ".$valor.")");

						//	elimino los EGRESOS
						$registros = $conexion->Query("SELECT * FROM egresos WHERE (id_sucursal = ".$valor.")");
						while(!$registros->EOF) {
							$conexion->Execute("DELETE FROM egreso_posee_productos WHERE (id_egreso = ".$registros->fields["ID_EGRESO"].")");
							$registros->MoveNext();
						}
						$conexion->Execute("DELETE FROM egresos WHERE (id_sucursal = ".$valor.")");

						//	elimino los INVENTARIOS
						$registros = $conexion->Query("SELECT * FROM inventarios WHERE (id_sucursal = ".$valor.")");
						while(!$registros->EOF) {
							$conexion->Execute("DELETE FROM inventario_posee_productos WHERE (id_inventario = ".$registros->fields["ID_INVENTARIO"].")");
							$registros->MoveNext();
						}
						$conexion->Execute("DELETE FROM inventarios WHERE (id_sucursal = ".$valor.")");

						//	elimino las VENTAS
						$registros = $conexion->Query("SELECT * FROM ventas WHERE (id_sucursal = ".$valor.")");
						while(!$registros->EOF) {
							$conexion->Execute("DELETE FROM venta_posee_productos WHERE (id_venta = ".$registros->fields["ID_VENTA"].")");
							$registros->MoveNext();
						}
						$conexion->Execute("DELETE FROM ventas WHERE (id_sucursal = ".$valor.")");

						//	elimino las SUCURSAL_POSEE_PRODUCTOS
						$conexion->Execute("DELETE FROM sucursal_posee_productos WHERE (id_sucursal = ".$valor.")");
						//elimino el registro
						$conexion->Execute("DELETE FROM sucursales WHERE (id_sucursal = '".$valor."')");
					}
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado 
			if(isset($_GET['accion']) && ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE sucursales SET activo = 1 WHERE (id_sucursal = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE sucursales SET activo = 0 WHERE (id_sucursal = '".$valor."')");
								} else {
									$conexion->Query("UPDATE sucursales SET activo = 1 WHERE (id_sucursal = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$valor."')");
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
		//	-----	Fin SUCURSALES

		include("../footer.php");
		?>
	</body>
</html>