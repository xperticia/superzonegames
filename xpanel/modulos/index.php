<?php
	if(!isset($_GET['menu'])) { $_GET['menu'] = "portada"; }
	include("../config.php");
	include("../verifica.php");
	$nivel_acceso=4; // Nivel de acceso para esta página.
	if ($nivel_acceso < $_SESSION['usuario_nivel']){
		header ("Location: ./?error_login=5");
		exit;
	}
	include("contador_add.php");
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
		<?php include("../head.php"); ?>
		<?php
			// almaceno el Tipo de Cambio
			if(isset($_POST['guadartipomoneda'])){
				$_SESSION['usuario_tipomoneda'] = $_POST['tipo_moneda'];
				$_SESSION['usuario_tipocambio'] = $_POST['tipo_cambio_actual'];

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_POST['tipo_cambio_vienede']."';");
				echo("</script>");
			}

			//	-----	Usuarios
			if(isset($_GET['menu']) && ($_GET['menu'] == "usuarios")) {

				//	Ventana para cambio de datos del usuario logueado: CLAVE, FOTO y EMAIL
				if(isset($_GET['accion']) and (($_GET['accion'] == "cambiarclave") or ($_GET['accion'] == "cambiarfoto") or ($_GET['accion'] == "cambiaremail"))) {
					$registros = $conexion->GetRow("SELECT * FROM usuarios WHERE id_usuario = '".$_SESSION['usuario_id']."'");
					if($registros) {											
					?>
		<form class="form-alert" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updateuser"); ?>" method="post" enctype="multipart/form-data" id="signup" style="width: 92%;">
			<?php
				if($_GET['accion'] == "cambiarclave") { echo("<h3>Cambiar contrase&ntilde;a</h3>"); }
				if($_GET['accion'] == "cambiarfoto") { echo("<h3>Actualizar foto</h3>"); }
				if($_GET['accion'] == "cambiaremail") { echo("<h3>Actualizar correo electronico</h3>"); }
			?>
			<div class="tabbable">
				<input type="hidden" name="registro[]" value="<?php echo($_GET['registro'][0]);  ?>" />
				<input type="hidden" name="tipo" value="<?php echo($_GET['accion']);  ?>" />

				<div class="body-alert">
					<div class="control-group">
						<label class="control-label">Apellido y Nombre</label>
						<div class="controls">
							<input type="text" size="50" maxlength="255" value="<?php echo($registros["NOMBRE_COMPLETO"]); ?>" disabled="disabled" />
						</div>
					</div>
					<?php
					if($_GET['accion'] == "cambiarclave") {
						?>
					<div class="control-group">
						<label class="control-label">Contrase&ntilde;a actual</label>
						<div class="controls">
							<input type="text" size="50" maxlength="255" value="<?php echo($registros["CLAVE"]); ?>" disabled="disabled" />				
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nueva Contrase&ntilde;a</label>
						<div class="controls">
							<input type="text" name="clave" size="50" maxlength="255" title="Nueva Contrase&ntilde;a" value="" required />
						</div>
					</div>
					<?php
					}

					if($_GET['accion'] == "cambiarfoto") {
						?>
					<div class="control-group">
						<label class="control-label">Foto actual</label>
						<?php 
							if(($registros['FOTO'] != null) and file_exists("../../".$path_imagenes."fotos/".$registros['FOTO'])) { 
								?>
								<a href="<?php echo("../../".$path_imagenes."fotos/".$registros['FOTO']); ?>" class="Foto" rel="colorbox" title="<?php echo(stripslashes($registros["NOMBRE_COMPLETO"])); ?>"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
								| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros['FOTO']); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
								<?php 
							} 
						?>
						<div class="controls">
							<input type="text" size="50" maxlength="255" value="<?php echo($registros["FOTO"]); ?>" readonly="readonly" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nueva Foto</label>
						<div class="controls">
							<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
							<input type="file" name="archivo_foto" title="Nueva Foto" required />
						</div>
					</div>
						<?php
					}

					if($_GET['accion'] == "cambiaremail") {
						?>
					<div class="control-group">
						<label class="control-label">Correo electr&oacute;nico actual</label>
						<div class="controls">
							<input type="text" size="50" maxlength="255" value="<?php echo($registros["EMAIL"]); ?>" disabled="disabled" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Nuevo Correo electr&oacute;nico</label>
						<div class="controls">
							<input type="text" name="email" size="50" maxlength="255" title="Nuevo Correo electr&oacute;nico" value="" required />
						</div>
					</div>
						<?php
					}
					?>
				</div>
						
				<div class="form-actions">
					<input type="submit" class="btn btn-large btn-primary" value="Aceptar" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
					<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']); ?>'" />
				</div>
			</div>
		</form>
					<?php
					} else {
						?>
		<form class="form-alert">
			<h3>Atenci&oacute;n</h3>
			<div class="body-alert">
				<img src="../images/exclamacion.png" alt="" title=""/>
				<p>No se ha podido encontrar los datos del usuario: <strong><?php echo($_SESSION['usuario_id']." : ".$_SESSION['usuario_nombre']); ?></strong>.</p>
			</div>
			<div class="form-actions">
				<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='index.php';" />
			</div>
		</form>
						<?php
					}
				}

				if(isset($_GET['accion']) && ($_GET['accion'] == "updateuser")) {
					echo("<h3>Actualizando datos del usuario activo...</h3>");

					if($_POST['tipo'] == "cambiarclave") {
						$conexion->Query("UPDATE usuarios SET clave = '".trim($_POST['clave'])."', clave2 = '".md5(trim($_POST['clave']))."' WHERE id_usuario = '".$_POST['registro'][0]."'");
					}

					if($_POST['tipo'] == "cambiarfoto") {
						$id = $_POST['registro'][0];
						$id_usuario = sprintf("US%08s", $id);
						//verifico los archivos subidos
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
									($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png")) {
									//copio el archivo
									if(copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."fotos/".$nombre_archivo))) {
										//si no hubo inconvenientes actualiza el nombre del archivo
										$conexion->Query("UPDATE usuarios SET foto = '".$nombre_archivo."' WHERE id_usuario = '".$id."'");
										//obtengo la ubicacion de la imagen original y small
										$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$nombre_archivo;
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$id_usuario."_small.".$extension;
										//paso como parametro el nombre la nueva imagen pequeña
										redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 100, 100);
									}
									//elimino el archivo temporal
									unlink($_FILES['archivo_foto']['tmp_name']);
								} else {
									//elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
									unlink($_FILES['archivo_foto']['tmp_name']);
									echo("<script type='text/javascript' language='javascript'>");
									echo("window.alert('".$_FILES['archivo_foto']['name'].": ".$msg_error[10]."');");
									echo("</script>");
								}# if
							}# if
						}# if
					}

					if($_POST['tipo'] == "cambiaremail") {
						$conexion->Query("UPDATE usuarios SET email = '".trim($_POST['email'])."' WHERE id_usuario = '".$_POST['registro'][0]."'");
						$_SESSION['usuario_email'] = trim($_POST['email']);
					}

					echo("<script type='text/javascript' language='javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."';");
					echo("</script>");
				}
			} else {
				?>
		<div class="row-fluid">
			<div class="span4">
				<div class="well" style="padding: 8px 0pt;">
					<ul class="nav nav-list">
					<?php
						//	verifico si es administrador para mostrar opciones de configuracion
						if($_SESSION['usuario_id'] == 0) {
							?>
						<li class="nav-header">Administrador</li>
						<li><a href="configuracion.php?menu=config"><i class="icon-cog"></i> Preferencias</a></li>
						<?php
							//verifico si la base de datos tiene tablas creadas
							$registros = $conexion->Query("SHOW TABLES");
							if($registros and ($registros->RecordCount() == 0)) {
								?>
						<li><a href="configuracion.php?menu=config&accion=cargartablas"><i class="icon-folder-open"></i> Crear Tablas</a></li> 
								<?php
							} else {
								?>
						<li><a href="usuarios.php?menu=usuarios"><i class="icon-user"></i> Usuarios</a></li>
						<li><a href="accesos.php?menu=contenidos"><i class="icon-book"></i> Contenidos</a></li>
								<?php
							}
							//	verifico si se posee usuarios
							$registros = $conexion->Query("SELECT * FROM usuarios");
							if($registros and ($registros->RecordCount() > 0)) {
								?>
						<li><a href="accesos.php?menu=accesos"><i class="icon-pencil"></i> Privilegios</a></li>
								<?php
							}
							//	verifico si se posee paises
							$registros = $conexion->Query("SELECT * FROM paises");
							if($registros and ($registros->RecordCount() == 0)) {
								?>
						<li><a href="configuracion.php?menu=config&accion=cargarpaises"><i class="icon-folder-open"></i> Cargar Paises</a></li> 
								<?php
							}
							//	verifico si se posee contenidos
							$registros = $conexion->Query("SELECT * FROM menu_modulos");
							if($registros and ($registros->RecordCount() == 0)) {
								?>
						<li><a href="configuracion.php?menu=config&accion=cargarmenu"><i class="icon-folder-open"></i> Cargar Menu</a></li>
								<?php
							}
						?>
							<?php
						}

						if($_SESSION['usuario_id'] != 0) {
							?>
						<li class="nav-header">Datos de acceso</li>
						<li>
							<p style="margin-left: 0;"><small>Usuario:</small> <strong><?php echo($_SESSION['usuario_login']); ?></strong></p>
							<p style="margin-left: 0;"><small>Nombre:</small> <strong><?php echo($_SESSION['usuario_nombre']); ?></strong></p>
						<?php 
							$registros = $conexion->GetRow("SELECT * FROM usuarios WHERE id_usuario = ".$_SESSION['usuario_id']);
							if(!$registros or ($registros["FOTO"] == null)) {
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/"."fotouser.jpg";
								$nombre_completo_imagensmall = null;
							} else {
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros["FOTO"];
								//verifico si el archivo existe
								if(file_exists($nombre_completo_imagenoriginal)) {
									//obtengo el nombre y extension del archivo small
									$res = explode(".", $registros["FOTO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//obtengo la ubicacion de la imagen small
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
									//paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 150, 150);
								} else {
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/"."fotouser.jpg";
									$nombre_completo_imagensmall = null;
								}
							}
						?>
							<div style="text-align: center;"><img src="<?php if($nombre_completo_imagensmall == null) { echo($nombre_completo_imagenoriginal);  } else { echo($nombre_completo_imagensmall); } ?>" alt="" style="border: 1px solid #ddd;padding: 10px;-webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;-webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);-moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.2);" /></div>
						</li>
						<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo('./?menu=usuarios&accion=cambiarclave&registro[]='.$_SESSION['usuario_id']); ?>';"><i class="icon-edit"></i> Cambiar contrase&ntilde;a</a></li>
						<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo('./?menu=usuarios&accion=cambiarfoto&registro[]='.$_SESSION['usuario_id']); ?>';"><i class="icon-picture"></i> Actualizar foto</a></li>
						<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo('./?menu=usuarios&accion=cambiaremail&registro[]='.$_SESSION['usuario_id']); ?>';"><i class="icon-pencil"></i> Actualizar correo electr&oacute;nico</a></li>
							<?php
						}
					?>
						<li class="nav-header">Preferencias</li>
						<li>
							<p style="margin: 0;"><small>Nombre:</small> <strong><?php echo($sitio_nombre); ?></strong></p>
							<p style="margin: 0;"><small>URL:</small> <strong><?php echo($sitio_url); ?></strong></p>			
							<p style="margin: 0;"><small>Servidor:</small> <strong><?php echo($server); ?></strong></p>
							<p style="margin: 0;"><small>Base de datos:</small> <strong><?php echo($database); ?></strong></p>
							<p style="margin: 0;"><small>Tipo de Base de datos:</small> <strong><?php echo($tipo_database); ?></strong></p>
						</li>
					</ul>
				</div>
				<div class="well">
					<h3>Combinaciones de teclas</h3>
					<ul>
						<li><strong>ALT + N</strong>: nuevo registro</li>
						<li><strong>ALT + G</strong>: guardar registro</li>
						<li><strong>ALT + C</strong>: cancelar</li>
						<li><strong>ALT + B</strong>: buscar</li>
						<li><i class="icon-arrow-up"></i> o <i class="icon-arrow-down"></i>: moverse entre campos</li>
						<li><strong>INTRO </strong> o <i class="icon-arrow-right"></i>: seleccionar dato u opción</li>
					</ul>
				</div>
			</div>
			<div class="span4">
				<div class="well" style="max-width: 400px; margin: 0 auto 10px;">
					<?php
					if($_SESSION['usuario_id'] == '0') {
						$datos_acceso["VER"] = 1;
						$datos_acceso["INSERTAR"] = 1;
						$datos_acceso["MODIFICAR"] = 1;
						$datos_acceso["ELIMINAR"] = 1;
						?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='clientes.php?menu=clientes';">Clientes</button>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='productos.php?menu=productos';">Productos</button>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=ventas';">Ventas</button>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=servicios';">Servicios T&eacute;cnicos</button>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=pedidos';">Pedidos</button>
						<?php
					} else {
						$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = 'clientes.php?menu=clientes')");
						if($datos_acceso["VER"] == 1) {
							?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='clientes.php?menu=clientes';">Clientes</button>
							<?php
						}
						$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = 'productos.php?menu=productos')");
						if($datos_acceso["VER"] == 1) {
							?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='productos.php?menu=productos';">Productos</button>
							<?php
						}
						$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = 'ventas.php?menu=ventas')");
						if($datos_acceso["VER"] == 1) {
							?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=ventas';">Ventas</button>
							<?php
						}
						$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = 'ventas.php?menu=servicios')");
						if($datos_acceso["VER"] == 1) {
							?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=servicios';">Servicios T&eacute;cnicos</button>
							<?php
						}
						$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = 'ventas.php?menu=pedidos')");
						if($datos_acceso["VER"] == 1) {
							?>
					<button type="button" class="btn btn-primary btn-large btn-block" onclick="javascript:window.location.href='ventas.php?menu=pedidos';">Pedidos</button>
							<?php
						}
					}
					?>
					<hr />
					<ul>
						<?php
							$query = "SELECT
								count(*) CANTIDAD 									  
							FROM usuarios U
							WHERE 
								(tipo_usuario = 'Cliente') and 
								(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
								(activo = 1) and (
									(
									select 
										count(id_venta) 
									from ventas 
									where 
										(tipo_venta = 'ventas') and 
										(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
										(id_cliente = U.ID_USUARIO) and 
										(eliminado = 0) and 
										(pagado = 0)
									) = 1
								)
							";
							$registros = $conexion->GetRow($query);
						?>
						<li>Deben 1 mes: <span class="badge badge-warning"><?php echo($registros['CANTIDAD']); ?></span></li>
						<?php
							$query = "SELECT
								count(*) CANTIDAD 									  
							FROM usuarios U
							WHERE 
								(tipo_usuario = 'Cliente') and 
								(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
								(activo = 1) and (
									(
									select 
										count(id_venta) 
									from ventas 
									where 
										(tipo_venta = 'ventas') and 
										(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
										(id_cliente = U.ID_USUARIO) and 
										(eliminado = 0) and 
										(pagado = 0)
									) = 2
								)
							";
							$registros = $conexion->GetRow($query);
						?>
						<li>Deben 2 meses <span class="badge badge-warning"><?php echo($registros['CANTIDAD']); ?></span></li>
						<?php
							$query = "SELECT
								count(*) CANTIDAD 									  
							FROM usuarios U
							WHERE 
								(tipo_usuario = 'Cliente') and 
								(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
								(activo = 1) and (
									(
									select 
										count(id_venta) 
									from ventas 
									where 
										(tipo_venta = 'ventas') and 
										(id_sucursal = '".$_SESSION['usuario_sucursal']."') and 
										(id_cliente = U.ID_USUARIO) and 
										(eliminado = 0) and 
										(pagado = 0)
									) >= 3
								)
							";
							$registros = $conexion->GetRow($query);
						?>
						<li>Deben +3 meses <span class="badge badge-warning"><?php echo($registros['CANTIDAD']); ?></span></li>
					</ul>
					<p>Para ver quienes son los Clientes, acceder a <strong>REPORTES >> Deudores según los meses</strong>: <a href="./reportes.php?menu=reportes&accion=pordeudoresxmes">Click aquí</a></p>
				</div>

				<div class="well">
					<h3>Vencimientos de Productos</h3>
					<?php
						$query = "SELECT 
							IFNULL(
								(
									SELECT count(ID_PRODUCTO) 
									FROM productos 
									WHERE 
										(ID_SUCURSAL = sucursales.ID_SUCURSAL) 
										and (FECHA_VENCIMIENTO >= DATE_ADD(curdate(),INTERVAL -15 DAY)) 
										and (FECHA_VENCIMIENTO < curdate())
								),0
							) YA_VENCIDOS,
							IFNULL(
								(
									SELECT count(ID_PRODUCTO) 
									FROM productos 
									WHERE 
										(ID_SUCURSAL = sucursales.ID_SUCURSAL) 
										and (FECHA_VENCIMIENTO >= curdate()) 
										and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 15 DAY))
								),0
							) PROXIMO_VENCERSE
						FROM sucursales
						WHERE 
							(ID_SUCURSAL = '".$_SESSION['usuario_sucursal']."')
							and (activo = 1) 
						";
						$registros = $conexion->GetRow($query);
					?>
					<ul>
						<li>Vencidos hace 15 días <span class="badge badge-warning"><?php echo($registros['YA_VENCIDOS']); ?></span></li>
						<li>A vencerse en los próximos 15 días <span class="badge badge-warning"><?php echo($registros['PROXIMO_VENCERSE']); ?></span></li>
					</ul>
					<p>Para ver los Productos vencidos, acceder a <strong>REPORTES >> Productos con fecha de vencimiento</strong>: <a href="./reportes.php?menu=reportes&accion=porproductosvencidos">Click aquí</a></p>
				</div>

			</div>
			<div class="span4">
				<h3>Actividades del d&iacute;a</h3>
				<p>Hoy <strong><?php echo(formato_fecha(date("Y-m-d"),1)); ?></strong> se han registrado</p>

				<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th></th>
						<th width="30">Hoy</th>
						<th width="30">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
							//	obtener datos de VISITAS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM contador WHERE (fecha = '".date("Y-m-d")."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM contador WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Accesos</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de CONSULTAS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM mensajes WHERE (tipo_mensaje = 'Consultas') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM mensajes WHERE (tipo_mensaje = 'Consultas') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Consultas</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de COMENTARIOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM mensajes WHERE (tipo_mensaje = 'Comentarios') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM mensajes WHERE (tipo_mensaje = 'Comentarios') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Comentarios</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<?php
						//	obtener datos de CONTENIDOS WEB
						$registros = $conexion->Query("SELECT TIPO_CONTENIDO FROM contenidos GROUP BY tipo_contenido ORDER BY tipo_contenido");
						while(!$registros->EOF) {
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM contenidos WHERE (tipo_contenido = '".$registros->fields["TIPO_CONTENIDO"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM contenidos WHERE (tipo_contenido = '".$registros->fields["TIPO_CONTENIDO"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
							?>
					<tr>
						<td><?php echo(ucfirst($registros->fields["TIPO_CONTENIDO"])); ?></td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
							<?php
							$registros->MoveNext();	
						}
					?>
					<tr>
						<?php
							//	obtener datos de SUCURSALES
							//$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM sucursales WHERE (fecha = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM sucursales");
						?>
						<td>Sucursales</td>
						<th></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM usuarios WHERE (tipo_usuario = 'Cliente') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM usuarios WHERE (tipo_usuario = 'Cliente') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Clientes</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de PRODUCTOS
							//$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM productos WHERE (fecha = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Productos</td>
						<th></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de VENTAS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'ventas') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'ventas') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Ventas</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de SERVICIOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'servicios') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'servicios') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Servicios Técnicos</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de PEDIDOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'pedidos') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM ventas WHERE (tipo_venta = 'pedidos') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Pedidos</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de EGRESOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM egresos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM egresos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Egresos</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de TRASPASOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM traspasos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM traspasos WHERE  (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Traspasos</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de COTIZACIONES
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM cotizaciones WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM cotizaciones WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Cotizaciones</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<tr>
						<?php
							//	obtener datos de INVENTARIOS
							$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM inventarios WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_realizacion = '".date("Y-m-d")."')");
							$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM inventarios WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
						?>
						<td>Inventarios</td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
					<?php
						for($i=0; $i<(count($niveles_acceso)-1); $i++) {
							if(trim($niveles_acceso[$i]) != 'Cliente'){
								$datos = $conexion->GetRow("SELECT count(*) as Cantidad FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (fecha_alta = '".date("Y-m-d")."')");
								$datos2 = $conexion->GetRow("SELECT count(*) as Cantidad FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");

								if (isset($datos2["Cantidad"]) and ($datos2["Cantidad"] > 0)) {
									?>
					<tr>
						<td><?php echo(ucfirst($niveles_acceso[$i])); ?></td>
						<th><?php if(isset($datos["Cantidad"])) { echo($datos["Cantidad"]); } ?></th>
						<th><?php if(isset($datos2["Cantidad"])) { echo($datos2["Cantidad"]); } ?></th>
					</tr>
									<?php

								}

							}
						}
					?>
				</tbody>
				</table>

			</div>
		</div>
				<?php
			}

		?>
		<?php include("../footer.php"); ?>
	</body>
</html>
