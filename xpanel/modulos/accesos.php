<?php
include("../config.php");
include("../verifica.php");
$nivel_acceso = 5; // Nivel de acceso para esta página.
if ($nivel_acceso < $_SESSION['usuario_nivel']) {
	header("Location: ./?error_login=5");
	exit;
}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo (armarHead()); ?></title>
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

		//	-----	MENU de CONTENIDOS
		if (isset($_GET['menu']) and ($_GET['menu'] == "contenidos")) {
			//	Listado
			if (!isset($_GET['accion'])) {
				$query = "SELECT ID_MENU,NOMBRE_MENU,CATEGORIA,URL,ORDEN FROM menu_modulos ";
				if (isset($_GET['texto'])) {
					$query .= "WHERE ( (upper(nombre_menu) LIKE upper('%" . $_GET['texto'] . "%')) or (upper(categoria) LIKE upper('%" . $_GET['texto'] . "%')) or (upper(url) LIKE upper('%" . $_GET['texto'] . "%')) ) ";
				}
				$query .= "ORDER BY orden";

				showTable2(
					$conexion,
					$query,
					array(
						"ID_MENU" => "Id",
						"NOMBRE_MENU" => "Nombre",
						"CATEGORIA" => "Categoria",
						"URL" => "Enlace",
						"ORDEN" => "#"
					),
					array(0 => "ID_MENU"),
					$cantidad_filas_x_pagina,
					true,
					true,
					$datos_acceso
				);
			}

			//	Nuevo
			if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				?>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Tipo</label>
									<div class="controls">
										<select id="tipo" name="tipo" title="Tipo" class="input-small">
											<option value=""></option>
											<option value="Menu">Menu</option>
											<option value="Contenido">Contenido</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" id="nombre_menu" name="nombre_menu" size="50" maxlength="255" title="Nombre" value="" style="width: 70%;" required autofocus />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" value="" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Enlace</label>
									<div class="controls">
										<input type="text" id="url" name="url" size="50" maxlength="255" title="Enlace" value="" style="width: 70%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Orden</label>
									<div class="controls">
										<div class="input-prepend input-append">
											<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="orden" data-limite="0" class="btn" /><i class="icon-minus"></i></button>
											<input type="text" id="orden" name="orden" title="Orden" value="" maxlength="3" class="input-mini" style="text-align: right;width: 25px;" required />
											<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="orden" class="btn" /><i class="icon-plus"></i></button>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar
			if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				//	Update
				if (isset($_POST['id_menu'])) {
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					//actualizo los datos
					$conexion->Query("UPDATE menu_modulos SET nombre_menu = '" . $_POST['nombre_menu'] . "', categoria = '" . $_POST['categoria'] . "', url = '" . $_POST['url'] . "', orden = '" . $_POST['orden'] . "', tipo = '" . $_POST['tipo'] . "' WHERE (id_menu = '" . $_POST['id_menu'] . "')");
					$id_contenido = $_POST['id_menu'];
				} else {
					//	Insert
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO menu_modulos (ID_MENU,NOMBRE_MENU,CATEGORIA,URL,ORDEN,TIPO) VALUES(null, '" . $_POST['nombre_menu'] . "', '" . $_POST['categoria'] . "', '" . $_POST['url'] . "', '" . $_POST['orden'] . "', '" . $_POST['tipo'] . "')");
					$id_contenido = $conexion->Insert_ID();
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "'");
				echo ("</script>");
			}

			//Editar
			if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM menu_modulos WHERE (id_menu = '" . $id_modo_editar . "')");
				while (!$registros->EOF) {
					?>
					<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active" style="min-height: 100px;">
									<input type="hidden" name="id_menu" value="<?php echo ($registros->fields["ID_MENU"]); ?>" />
									<div class="control-group">
										<label class="control-label">Tipo</label>
										<div class="controls">
											<select id="tipo" name="tipo" title="Tipo" class="input-small" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
												<option value=""></option>
												<option value="Menu" <?php if ($registros->fields["TIPO"] == "Menu") { echo ("selected='selected'"); } ?>>Menu</option>
												<option value="Contenido" <?php if ($registros->fields["TIPO"] == "Contenido") { echo ("selected='selected'"); } ?>>Contenido</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" id="nombre_menu" name="nombre_menu" size="50" maxlength="255" title="Nombre" value="<?php echo ($registros->fields["NOMBRE_MENU"]); ?>" style="width: 70%;" required autofocus <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Categoria</label>
										<div class="controls">
											<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" value="<?php echo ($registros->fields["CATEGORIA"]); ?>" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Enlace</label>
										<div class="controls">
											<input type="text" id="url" name="url" size="50" maxlength="255" title="Enlace" value="<?php echo ($registros->fields["URL"]); ?>" style="width: 70%;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Orden</label>
										<div class="controls">
											<div class="input-prepend input-append">
												<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="orden" data-limite="0" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /><i class="icon-minus"></i></button>
												<input type="text" id="orden" name="orden" title="Orden" value="<?php echo ($registros->fields["ORDEN"]); ?>" maxlength="3" class="input-mini" style="text-align: right;width: 25px;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="orden" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="form-actions">
								<?php if ($_GET['accion'] == "ver") { ?>
									<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
								<?php } else { ?>
									<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
									<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//	Eliminar
			if (isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						//elimino los accesos otorgados
						$conexion->Execute("DELETE FROM privilegios WHERE (id_menu = '" . $valor . "')");
						//elimino el registro
						$conexion->Execute("DELETE FROM menu_modulos WHERE (id_menu = '" . $valor . "')");
					}
					echo ("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado
			if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if (isset($_GET['registro'])) {
					foreach ($_GET['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM menu_modulos WHERE (id_menu = '" . $valor . "')");
						if ($datos) {
							if ($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE menu_modulos SET activo = 1 WHERE (id_menu = '" . $valor . "')");
							} else {
								if ($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE menu_modulos SET activo = 0 WHERE (id_menu = '" . $valor . "')");
								} else {
									$conexion->Query("UPDATE menu_modulos SET activo = 1 WHERE (id_menu = '" . $valor . "')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM menu_modulos WHERE (id_menu = '" . $valor . "')");
							if ($datos) {
								echo ("{ \"estado\" : \"" . $estados[$datos["ACTIVO"]] . "\" }");
							} else {
								echo ("{ \"estado\" : \"error\" }");
							}
						}
					}
				}
			}
		}
		//	-----	Fin MENU de CONTENIDOS

		include("../footer.php");
		?>
	</body>
</html>