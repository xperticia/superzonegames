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

		//	-----	FOTOGALERIA
		if(isset($_GET['menu']) and ($_GET['menu'] == "fotogaleria")) {
			//	Listado
			if(!isset($_GET['accion']))	{
				$CantidadColumnas = 4;
				$query = "SELECT CATEGORIA,DESCRIPCION,NOMBRE,ID_FOTO FROM fotogaleria WHERE (categoria <> 'Portada') ";
				if(isset($_GET['texto'])) {
					$query .= " and ( (upper(descripcion) LIKE upper('%".$_GET['texto']."%')) or (upper(categoria) LIKE upper('%".$_GET['texto']."%')) ) ";
				}
				$query .= "ORDER BY fecha_alta DESC, id_foto DESC";

				showTableImagenes($conexion,
					$query,
					array("CATEGORIA"=>"Categoria",
						"DESCRIPCION"=>"Descripcion",
						"NOMBRE"=>"Nombre"),
					array(0=>"ID_FOTO"),
					$cantidad_filas_x_pagina,true,true,$CantidadColumnas,$datos_acceso,null,
					array("renombrar"=>"Cambiar nombre a Categoria"));
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				$datos = $conexion->GetRow("SELECT IF(id_foto > 0, (max(id_foto) + 1), 1) as ID_FOTO FROM fotogaleria ORDER BY id_foto DESC");
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_foto" value="<?php echo($datos["ID_FOTO"]); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Imagen</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Categoria</label>
											<div class="controls">
												<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" style="width: 99%;" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Imagen</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" name="nombre_archivo" title="Imagen" accept=".jpeg,.jpg,.png,.gif" required />
												<p class="help-block"><strong>Nota:</strong> las im&aacute;genes puede ser de formato JPG, PNG o GIF y no deben superar los 8 Mbyte de tama&ntilde;o.</p>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Redimensionar imagen</label>
											<div class="controls">
												<select class="input-small" name="redimensionar" id="redimensionar" title="Redimensionar imagen">
													<option value=""></option>
													<option value="100x100">100x100</option>
													<option value="200x200">200x200</option>
													<option value="300x300">300x300</option>
													<option value="400x400">400x400</option>
													<option value="500x500">500x500</option>
													<option value="600x600">600x600</option>
													<option value="640x480">640x480</option>
													<option value="800x600">800x600</option>
													<option value="1024x768">1024x768</option>
												</select>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Click</label>
											<div class="controls">
												<input type="text" name="click" value="0" title="Click" class="input-small" size="10" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha de Alta</label>
											<div class="controls">
												<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline"><input type="radio" name="activo" value="1" checked="checked" />&nbsp;<span><?php echo($estados[1]); ?></span></label>
												<label class="radio inline"><input type="radio" name="activo" value="0" />&nbsp;<span><?php echo($estados[0]); ?></span></label>
											</div>
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea class="ckeditor" id="descripcion" name="descripcion" rows="10" cols="38" title="Descripci&oacute;n" style="width: 99%;"></textarea>
									</div>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							<input type="reset" class="btn btn-large" value="Agregar im&aacute;genes en grupo" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=nuevogrupo"); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Insertando registro...</h5>");
				//	verifico si se selecciono un archivo
				if (is_uploaded_file($_FILES['nombre_archivo']['tmp_name'])) {
					//	obtengo el nombre del archivo y su extension
					$id_foto = sprintf("I%07s",$_POST['id_foto']);
					$res = explode(".", $_FILES['nombre_archivo']['name']);
					$tipo_archivo = $_FILES['nombre_archivo']['type'];
					$extension = $res[count($res)-1];
					$nombre_completo = $id_foto.".".$extension;

					//	verifico el tipo de archivo: GIF, JPG y PNG
					if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or 
					($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
						//	intento copiar el archivo cargado a la carpeta 
						if (!copy($_FILES['nombre_archivo']['tmp_name'], stripslashes("../../".$path_imagenes."fotogaleria/".$nombre_completo)) or ($_FILES['nombre_archivo']['size'] > 8388608)) {
							echo("<script type='text/javascript' language='javascript'>");
							echo("window.alert('No se ha podido cargar el archivo: \"".$_FILES['nombre_archivo']['name']."\" por alguna de las siguientes razones:\\n- El nombre del archivo posee caracteres especiales.\\n- El tama&ntilde;o del archivo es superior a 8 Mbyte.');");
							echo("window.location.href=\"".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."\";");
							echo("</script>");					
						} else {	
							//	si no hubo inconvenientes registra los datos del archivo de estado
							$conexion->Query("INSERT INTO fotogaleria 
								(
									ID_FOTO,
									CATEGORIA,
									NOMBRE,
									DESCRIPCION,
									FECHA_ALTA,
									ACTIVO,
									CLICK,
									ID_ACTUALIZACION
								) VALUES(
									null,
									'".addslashes($_POST['categoria'])."',
									'".$nombre_completo."',
									'".addslashes($_POST['descripcion'])."',
									'".$_POST['fecha_alta']."',
									'".$_POST['activo']."', 
									'".$_POST['click']."',
									'".$_POST['id_actualizacion']."'
								)");

							if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
								$dimensiones = explode("x",$_POST['redimensionar']);
								if(count($dimensiones) > 1) {
									//	obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$nombre_completo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$id_foto."_small.".$extension;
									//	paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
								}
							}
						}					
						//	elimino el archivo temporal
						unlink($_FILES['nombre_archivo']['tmp_name']);
					} else {
						//	elimino el archivo temporal y muestro mensaje de error en tipo de archivo
						unlink($_FILES['nombre_archivo']['tmp_name']);
						echo("<script type='text/javascript' language='javascript'>");
						echo("window.alert('".$_FILES['imagen_adjunta']['name'].": ".$msg_error[10]."');");
						echo("window.history.back();");
						echo("</script>");						
					}
				} else {
					echo("<script type='text/javascript' language='javascript'>");
					echo("window.alert('".$_FILES['nombre_archivo']['tmp_name'].": archivo no cargado."."');");
					echo("window.history.back();");
					echo("</script>");						
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Nuevo grupo de fotos
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevogrupo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insertgrupo"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />	

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" placeholder="categoria de las im&aacute;genes" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Redimensionar im&aacute;genes</label>
									<div class="controls">
										<select class="input-small" name="redimensionar" id="redimensionar" title="Redimensionar im&aacute;genes">
											<option value=""></option>
											<option value="100x100">100x100</option>
											<option value="200x200">200x200</option>
											<option value="300x300">300x300</option>
											<option value="400x400">400x400</option>
											<option value="500x500">500x500</option>
											<option value="600x600">600x600</option>
											<option value="640x480">640x480</option>
											<option value="800x600">800x600</option>
											<option value="1024x768">1024x768</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Opciones</label>
									<div class="controls">
										<label class="radio inline"><input type="checkbox" id="obtenerDescripcion" /> <span>Obtener Descripci&oacute;n desde el nombre de los archivos.</span></label>
									</div>
								</div>
								<p style="text-align: center;"><small>Puede subir hasta un total de 100 im&aacute;genes por vez.</small></p>
								<div id="dropbox">
									<span class="message">Arrastre y suelte aqui las im&aacute;genes a cargar.</span>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar grupo" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							<input type="reset" class="btn btn-large" value="Agregar im&aacute;genes individuales" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=nuevo"); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar grupo de fotos
			if(isset($_GET['accion']) and ($_GET['accion'] == "insertgrupo")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando grupo de registros...</h5>");
				//verifico las fotos subidos
				if(isset($_POST["id_foto"])) {
					$total = count($_POST["id_foto"]);
					for($i = 0; $i < $total; $i++) {
						$conexion->Query("UPDATE fotogaleria 
							SET 
								categoria = '".addslashes($_POST['categoria'])."', 
								descripcion = '".addslashes($_POST['imageText'][$i])."', 
								id_actualizacion = '".$_POST['id_atualizacion']."' 
							WHERE id_foto = '".$_POST['id_foto'][$i]."'");
					}
					if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
						$dimensiones = explode("x",$_POST['redimensionar']);
						if(count($dimensiones) > 1) {
							$total = count($_POST["id_foto"]);
							for($i = 0; $i < $total; $i++) {
								$datos = $conexion->GetRow("SELECT NOMBRE FROM fotogaleria WHERE id_foto = '".$_POST['id_foto'][$i]."'");
								if($datos) {
									//	verifico si el archivo existe
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$datos["NOMBRE"];
									if(file_exists($nombre_completo_imagenoriginal)) {
										//	obtengo el nombre y extension del archivo small
										$res = explode(".", $datos["NOMBRE"]);
										$nombre = $res[0];
										$extension = $res[count($res) - 1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
										//	paso como parametro el nombre la nueva imagen pequeña
										redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
									}
								}
							}								
						}
					}

				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (id_foto = '".$id_modo_editar."')");
				while(!$registros->EOF)	{
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=update"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_foto" value="<?php echo($registros->fields["ID_FOTO"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Imagen</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Categoria</label>
												<div class="controls">
													<input type="text" id="categoria" name="categoria" value="<?php echo(stripslashes($registros->fields["CATEGORIA"])); ?>" size="50" maxlength="255" title="Categoria" style="width: 99%;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Imagen
												<input type="hidden" name="old_nombre_archivo" value="<?php echo($registros->fields['NOMBRE']); ?>" />                
												<?php
													$nombre_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$registros->fields['NOMBRE'];
													if(($registros->fields['NOMBRE'] != null) and file_exists($nombre_imagenoriginal)) { 
														//	obtengo la extension del archivo
														$res = explode(".", $registros->fields["NOMBRE"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
														if(file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														?>
												<a href="<?php echo($nombre_imagenoriginal); ?>" class="Foto" rel="colorbox" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE']); ?>"><img src="../images/preview.gif" title="Vista preliminar" align="absmiddle" /></a>
												<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_FOTO"]); ?>" data-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-tipo="nombre" data-nombre-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-texto=""><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
														<?php 
													} else { 
														if(($registros->fields['NOMBRE'] != null) and !file_exists($nombre_imagenoriginal)) { 
															?>
												<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_FOTO"]); ?>" data-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-tipo="nombre" data-nombre-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-texto=""><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php 
														} 
													} 
												?>
												</label>
												<div class="controls">
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" name="nombre_archivo" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if(($registros->fields['NOMBRE'] == null)) { echo("required"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<p class="help-block"><strong style="text-decoration:underline;">Nota:</strong> las im&aacute;genes puede ser de formato JPG, PNG o GIF y no deben superar los 8 Mbyte de tama&ntilde;o.</p>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Redimensionar imagen</label>
												<div class="controls">
													<select class="input-small" name="redimensionar" id="redimensionar" title="Redimensionar imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<option value="100x100">100x100</option>
														<option value="200x200">200x200</option>
														<option value="300x300">300x300</option>
														<option value="400x400">400x400</option>
														<option value="500x500">500x500</option>
														<option value="600x600">600x600</option>
														<option value="640x480">640x480</option>
														<option value="800x600">800x600</option>
														<option value="1024x768">1024x768</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Click</label>
												<div class="controls">
													<input type="text" name="click" value="<?php echo($registros->fields["CLICK"]); ?>" title="Click" class="input-small" size="10" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha de Alta</label>
												<div class="controls">
													<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline"><input type="radio" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[1]); ?></span></label>
													<label class="radio inline"><input type="radio" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[0]); ?></span></label>
												</div>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea class="ckeditor" id="descripcion" name="descripcion" rows="10" cols="38" title="Descripci&oacute;n" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Update
			if(isset($_GET['accion']) and ($_GET['accion'] == "update")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				//verifico si se selecciono un archivo
				if (is_uploaded_file($_FILES['nombre_archivo']['tmp_name'])) {
					//obtengo el nombre del archivo y su extension
					$id_foto = sprintf("I%07s",$_POST['id_foto']);
					$res = explode(".", $_FILES['nombre_archivo']['name']);
					$tipo_archivo = $_FILES['nombre_archivo']['type'];
					$extension = $res[count($res)-1];
					$nombre_completo = $id_foto.".".$extension;

					//verifico el tipo de archivo: GIF, JPG y PNG
					if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or 
					($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
						//	elimino las imagenes anteriores
						if(isset($_POST['old_nombre_archivo']) and ($_POST['old_nombre_archivo'] != "")){
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['old_nombre_archivo'];
							if(file_exists($nombre_completo_imagenoriginal)) {
							//	obtengo el nombre y extension del archivo
							$res2 = explode(".", $_POST['old_nombre_archivo']);
							$nombre2 = $res2[0];
							$extension2 = $res2[count($res2) - 1];
							//obtengo la ubicacion de la imagen original
							$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre2."_small.".$extension2;
							//elimino el archivo
							unlink($nombre_completo_imagenoriginal);
							if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							}
							}
						}
						//intento copiar el archivo cargado a la carpeta 
						if (!copy($_FILES['nombre_archivo']['tmp_name'], stripslashes("../../".$path_imagenes."fotogaleria/".$nombre_completo)) or ($_FILES['nombre_archivo']['size'] > 8388608)) {
							echo("<script type='text/javascript' language='javascript'>");
							echo("window.alert('No se ha podido cargar el archivo: \"".$_FILES['nombre_archivo']['name']."\" por alguna de las siguientes razones:\\n- El nombre del archivo posee caracteres especiales.\\n- El tama&ntilde;o del archivo es superior a 8 Mbyte.');");
							echo("window.location.href=\"".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."\";");
							echo("</script>");					
						} else {	
							//si no hubo inconvenientes registra los datos del archivo de estado
							$conexion->Query("UPDATE fotogaleria 
								SET 
									categoria = '".addslashes($_POST['categoria'])."', 
									nombre = '".$nombre_completo."', 
									descripcion = '".addslashes($_POST['descripcion'])."', 
									fecha_alta = '".$_POST['fecha_alta']."', 
									activo = '".$_POST['activo']."', 
									click = '".$_POST['click']."', 
									id_actualizacion = '".$_POST['id_actualizacion']."' 
								WHERE id_foto = '".$_POST['id_foto']."'");

							//verifico si se desea redimensionar la imagen
							if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
								$dimensiones = explode("x",$_POST['redimensionar']);
								if(count($dimensiones) > 1) {
									//obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$nombre_completo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$id_foto."_small.".$extension;
									//paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
								}
							}
						}					
						//elimino el archivo temporal
						unlink($_FILES['nombre_archivo']['tmp_name']);
					} else {
						//elimino el archivo temporal y muestro mensaje de error en tipo de archivo
						unlink($_FILES['nombre_archivo']['tmp_name']);
						echo("<script type='text/javascript' language='javascript'>");
						echo("window.alert('".$msg_error[10]."');");
						echo("window.history.back();");
						echo("</script>");						
					}
				} else {
					//actualizo los datos
					$conexion->Query("UPDATE fotogaleria 
						SET 
							categoria = '".addslashes($_POST['categoria'])."', 
							descripcion = '".addslashes($_POST['descripcion'])."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							activo = '".$_POST['activo']."', 
							click = '".$_POST['click']."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE id_foto = '".$_POST['id_foto']."'");

					//verifico si se desea redimensionar la imagen
					if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
						$dimensiones = explode("x",$_POST['redimensionar']);
						if(count($dimensiones) > 1) {
							//	obtengo el nombre y extension del archivo
							$res = explode(".", $_POST['old_nombre_archivo']);
							$nombre = $res[0];
							$extension = $res[count($res) - 1];
							//obtengo la ubicacion de la imagen original
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['old_nombre_archivo'];
							$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
							//paso como parametro el nombre la nueva imagen pequeña
							redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
						}
					}
					
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Eliminar imagen/publicidad
			if(isset($_GET['accion']) and ($_GET['accion'] == "deleteimagen")) {
				//	verifico si el archivo existe
				$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['ida'];
				if(file_exists($nombre_completo_imagenoriginal)) {
				//	obtengo el nombre y extension del archivo
				$res = explode(".", $_POST['ida']);
				$nombre = $res[0];
				$extension = $res[count($res) - 1];
				//	obtengo la ubicacion de la imagen small
				$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
				//	elimino el archivo
				unlink($nombre_completo_imagenoriginal);
				if(file_exists($nombre_completo_imagensmall)) {
					unlink($nombre_completo_imagensmall);
				}
				}
				$conexion->Query("UPDATE fotogaleria SET ".$_POST['tipo']." = null WHERE (id_foto = '".$_POST['idc']."')");
				echo("{ \"estado\" : \"ok\" }");
			}

			//	Renombrar
			if(isset($_GET['accion']) and ($_GET['accion'] == "renombrar")) {
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatecategoria"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nueva Categoria</label>
									<div class="controls">
										<input type="text" id="nuevacategoria" name="nuevacategoria" size="50" maxlength="255" title="Nueva Categoria" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar categoria" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Actualizar Categoria
			if(isset($_GET['accion']) and ($_GET['accion'] == "updatecategoria")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE fotogaleria SET categoria = '".addslashes($_POST["nuevacategoria"])."' WHERE (categoria = '".$_POST["categoria"]."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}
			
			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Eliminando registro...</h5>");
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	obtengo los datos del registro
						$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
						if($registros) {
							while(!$registros->EOF) {
								//	verifico si el archivo existe
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$registros->fields["NOMBRE"];
								if(file_exists($nombre_completo_imagenoriginal)) {
									//	obtengo el nombre y extension del archivo small
									$res = explode(".", $registros->fields["NOMBRE"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen small
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
									//elimino el archivo
									unlink($nombre_completo_imagenoriginal);
									if(file_exists($nombre_completo_imagensmall)) {
										unlink($nombre_completo_imagensmall);
									}
								}
								$registros->MoveNext();
							}
							//elimino el registro
							$conexion->Query("DELETE FROM fotogaleria WHERE (id_foto = '".$valor."')");			
						}
						echo("{ \"estado\" : \"ok\" }");
					}		
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_GET['registro'])) {
					foreach($_GET['registro'] as $valor) {
						//	verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE fotogaleria SET activo = 1 WHERE (id_foto = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE fotogaleria SET activo = 0 WHERE (id_foto = '".$valor."')");
								} else {
									$conexion->Query("UPDATE fotogaleria SET activo = 1 WHERE (id_foto = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
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
		//	-----	Fin FOTOGALERIA

		//	-----	IMAGENES DE PORTADA
		if(isset($_GET['menu']) and ($_GET['menu'] == "imgportada")) {
			//	Listado
			if(!isset($_GET['accion']))	{
				$CantidadColumnas = 4;
				$query = "SELECT CATEGORIA,DESCRIPCION,NOMBRE,ID_FOTO FROM fotogaleria WHERE (categoria = 'Portada') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ";
				if(isset($_GET['texto'])) {
					$query .= " and ( (upper(descripcion) LIKE upper('%".$_GET['texto']."%')) or (upper(categoria) LIKE upper('%".$_GET['texto']."%')) ) ";
				}
				$query .= "ORDER BY fecha_alta DESC, id_foto DESC";

				showTableImagenes($conexion,
					$query,
					array("CATEGORIA"=>"Categoria",
						"DESCRIPCION"=>"Descripcion",
						"NOMBRE"=>"Nombre"),
					array(0=>"ID_FOTO"),
					$cantidad_filas_x_pagina,true,true,$CantidadColumnas,$datos_acceso);
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				$datos = $conexion->GetRow("SELECT IF(id_foto > 0, (max(id_foto) + 1), 1) as ID_FOTO FROM fotogaleria ORDER BY id_foto DESC");
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_foto" value="<?php echo($datos["ID_FOTO"]); ?>" />
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="categoria" value="Portada" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Imagen de Portada</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Imagen</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" name="nombre_archivo" title="Imagen" accept=".jpeg,.jpg,.png,.gif" required />
												<p class="help-block"><strong>Nota:</strong> las im&aacute;genes de portada puede ser de formato JPG, PNG o GIF y no deben superar los 8 Mbyte de tama&ntilde;o, con una dimensi&oacute;n de 1250 x 250 pixeles.</p>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Fecha de Alta</label>
											<div class="controls">
												<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline"><input type="radio" name="activo" value="1" checked="checked" />&nbsp;<span><?php echo($estados[1]); ?></span></label>
												<label class="radio inline"><input type="radio" name="activo" value="0" />&nbsp;<span><?php echo($estados[0]); ?></span></label>
											</div>
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea class="ckeditor" id="descripcion" name="descripcion" rows="10" cols="38" title="Descripci&oacute;n" style="width: 99%;"></textarea>
									</div>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							<input type="reset" class="btn btn-large" value="Agregar im&aacute;genes en grupo" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=nuevogrupo"); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Insertando registro...</h5>");
				//	verifico si se selecciono un archivo
				if (is_uploaded_file($_FILES['nombre_archivo']['tmp_name'])) {
					//	obtengo el nombre del archivo y su extension
					$id_foto = sprintf("I%07s",$_POST['id_foto']);
					$res = explode(".", $_FILES['nombre_archivo']['name']);
					$tipo_archivo = $_FILES['nombre_archivo']['type'];
					$extension = $res[count($res)-1];
					$nombre_completo = $id_foto.".".$extension;

					//	verifico el tipo de archivo: GIF, JPG y PNG
					if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or 
					($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
						//	intento copiar el archivo cargado a la carpeta 
						if (!copy($_FILES['nombre_archivo']['tmp_name'], stripslashes("../../".$path_imagenes."fotogaleria/".$nombre_completo)) or ($_FILES['nombre_archivo']['size'] > 8388608)) {
							echo("<script type='text/javascript' language='javascript'>");
							echo("window.alert('No se ha podido cargar el archivo: \"".$_FILES['nombre_archivo']['name']."\" por alguna de las siguientes razones:\\n- El nombre del archivo posee caracteres especiales.\\n- El tama&ntilde;o del archivo es superior a 8 Mbyte.');");
							echo("window.location.href=\"".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."\";");
							echo("</script>");					
						} else {	
							//	si no hubo inconvenientes registra los datos del archivo de estado
							$conexion->Query("INSERT INTO fotogaleria 
								(
									ID_FOTO,
									CATEGORIA,
									NOMBRE,
									DESCRIPCION,
									FECHA_ALTA,
									ACTIVO,
									CLICK,
									ID_SUCURSAL,
									ID_ACTUALIZACION
								) VALUES(
									null,
									'".addslashes($_POST['categoria'])."',
									'".$nombre_completo."',
									'".addslashes($_POST['descripcion'])."',
									'".$_POST['fecha_alta']."',
									'".$_POST['activo']."',
									0,
									'".$_POST['id_sucursal']."',
									'".$_POST['id_actualizacion']."'
								)");

							if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
								$dimensiones = explode("x",$_POST['redimensionar']);
								if(count($dimensiones) > 1) {
									//	obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$nombre_completo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$id_foto."_small.".$extension;
									//	paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
								}
							}
						}					
						//	elimino el archivo temporal
						unlink($_FILES['nombre_archivo']['tmp_name']);
					} else {
						//	elimino el archivo temporal y muestro mensaje de error en tipo de archivo
						unlink($_FILES['nombre_archivo']['tmp_name']);
						echo("<script type='text/javascript' language='javascript'>");
						echo("window.alert('".$_FILES['imagen_adjunta']['name'].": ".$msg_error[10]."');");
						echo("window.history.back();");
						echo("</script>");						
					}
				} else {
					echo("<script type='text/javascript' language='javascript'>");
					echo("window.alert('".$_FILES['nombre_archivo']['tmp_name'].": archivo no cargado."."');");
					echo("window.history.back();");
					echo("</script>");						
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Nuevo grupo de fotos
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevogrupo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insertgrupo"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="categoria" value="Portada" />
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Opciones</label>
									<div class="controls">
										<label class="radio inline"><input type="checkbox" id="obtenerDescripcion" /> <span>Obtener Descripci&oacute;n desde el nombre de los archivos.</span></label>
									</div>
								</div>
								<p style="text-align: center;"><small>Puede subir hasta un total de 100 im&aacute;genes por vez.</small></p>
								<div id="dropbox">
									<span class="message">Arrastre y suelte aqui las im&aacute;genes a cargar.</span>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar grupo" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							<input type="reset" class="btn btn-large" value="Agregar im&aacute;genes individuales" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=nuevo"); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Insertar grupo de fotos
			if(isset($_GET['accion']) and ($_GET['accion'] == "insertgrupo")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando grupo de registros...</h5>");
				//verifico las fotos subidos
				if(isset($_POST["id_foto"])) {
					$total = count($_POST["id_foto"]);
					for($i = 0; $i < $total; $i++) {
						$conexion->Query("UPDATE fotogaleria 
							SET 
								id_sucursal = '".$_POST['id_sucursal']."', 
								categoria = '".addslashes($_POST['categoria'])."', 
								descripcion = '".addslashes($_POST['imageText'][$i])."', 
								id_actualizacion = '".$_POST['id_actualizacion']."'
							WHERE (id_foto = '".$_POST['id_foto'][$i]."')");

					}
					if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
						$dimensiones = explode("x",$_POST['redimensionar']);
						if(count($dimensiones) > 1) {
							$total = count($_POST["id_foto"]);
							for($i = 0; $i < $total; $i++) {
								$datos = $conexion->GetRow("SELECT NOMBRE FROM fotogaleria WHERE (id_foto = '".$_POST['id_foto'][$i]."')");
								if($datos) {
									//	verifico si el archivo existe
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$datos["NOMBRE"];
									if(file_exists($nombre_completo_imagenoriginal)) {
										//	obtengo el nombre y extension del archivo small
										$res = explode(".", $datos["NOMBRE"]);
										$nombre = $res[0];
										$extension = $res[count($res) - 1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
										//	paso como parametro el nombre la nueva imagen pequeña
										redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
									}
								}
							}								
						}
					}

				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (id_foto = '".$id_modo_editar."')");
				while(!$registros->EOF)	{
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=update"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_foto" value="<?php echo($registros->fields["ID_FOTO"]); ?>" />
						<input type="hidden" name="categoria" value="<?php echo(stripslashes($registros->fields["CATEGORIA"])); ?>" />
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Imagen</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Imagen
												<input type="hidden" name="old_nombre_archivo" value="<?php echo($registros->fields['NOMBRE']); ?>" />                
												<?php
													$nombre_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$registros->fields['NOMBRE'];
													if(($registros->fields['NOMBRE'] != null) and file_exists($nombre_imagenoriginal)) { 
														//	obtengo la extension del archivo
														$res = explode(".", $registros->fields["NOMBRE"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
														if(file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														?>
												<a href="<?php echo($nombre_imagenoriginal); ?>" class="Foto" rel="colorbox" title="<?php echo("[".stripslashes($registros->fields["CATEGORIA"])."] ".$registros->fields['NOMBRE']); ?>"><img src="../images/preview.gif" title="Vista preliminar" align="absmiddle" /></a>
												<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_FOTO"]); ?>" data-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-tipo="nombre" data-nombre-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-texto=""><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
														<?php 
													} else { 
														if(($registros->fields['NOMBRE'] != null) and !file_exists($nombre_imagenoriginal)) { 
															?>
												<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_FOTO"]); ?>" data-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-tipo="nombre" data-nombre-archivo="<?php echo($registros->fields["NOMBRE"]); ?>" data-texto=""><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php 
														} 
													} 
												?>
												</label>
												<div class="controls">
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" name="nombre_archivo" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if(($registros->fields['NOMBRE'] == null)) { echo("required"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<p class="help-block"><strong>Nota:</strong> las im&aacute;genes de portada puede ser de formato JPG, PNG o GIF y no deben superar los 8 Mbyte de tama&ntilde;o, con una dimensi&oacute;n de 1250 x 250 pixeles.</p>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Fecha de Alta</label>
												<div class="controls">
													<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline"><input type="radio" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[1]); ?></span></label>
													<label class="radio inline"><input type="radio" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[0]); ?></span></label>
												</div>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea class="ckeditor" id="descripcion" name="descripcion" rows="10" cols="38" title="Descripci&oacute;n" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//Update
			if(isset($_GET['accion']) and ($_GET['accion'] == "update")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				//verifico si se selecciono un archivo
				if (is_uploaded_file($_FILES['nombre_archivo']['tmp_name'])) {
					//obtengo el nombre del archivo y su extension
					$id_foto = sprintf("I%07s",$_POST['id_foto']);
					$res = explode(".", $_FILES['nombre_archivo']['name']);
					$tipo_archivo = $_FILES['nombre_archivo']['type'];
					$extension = $res[count($res)-1];
					$nombre_completo = $id_foto.".".$extension;

					//verifico el tipo de archivo: GIF, JPG y PNG
					if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or 
					($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
						//	elimino las imagenes anteriores
						if(isset($_POST['old_nombre_archivo']) and ($_POST['old_nombre_archivo'] != "")){
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['old_nombre_archivo'];
							if(file_exists($nombre_completo_imagenoriginal)) {
							//	obtengo el nombre y extension del archivo
							$res2 = explode(".", $_POST['old_nombre_archivo']);
							$nombre2 = $res2[0];
							$extension2 = $res2[count($res2) - 1];
							//obtengo la ubicacion de la imagen original
							$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre2."_small.".$extension2;
							//elimino el archivo
							unlink($nombre_completo_imagenoriginal);
							if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							}
							}
						}
						//intento copiar el archivo cargado a la carpeta 
						if (!copy($_FILES['nombre_archivo']['tmp_name'], stripslashes("../../".$path_imagenes."fotogaleria/".$nombre_completo)) or ($_FILES['nombre_archivo']['size'] > 8388608)) {
							echo("<script type='text/javascript' language='javascript'>");
							echo("window.alert('No se ha podido cargar el archivo: \"".$_FILES['nombre_archivo']['name']."\" por alguna de las siguientes razones:\\n- El nombre del archivo posee caracteres especiales.\\n- El tama&ntilde;o del archivo es superior a 8 Mbyte.');");
							echo("window.location.href=\"".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."\";");
							echo("</script>");					
						} else {	
							//si no hubo inconvenientes registra los datos del archivo de estado
							$conexion->Query("UPDATE fotogaleria 
								SET 
									id_sucursal = '".$_POST['id_sucursal']."', 
									categoria = '".addslashes($_POST['categoria'])."', 
									nombre = '".$nombre_completo."', 
									descripcion = '".addslashes($_POST['descripcion'])."', 
									fecha_alta = '".$_POST['fecha_alta']."', 
									activo = '".$_POST['activo']."', 
									click = '".$_POST['click']."', 
									id_actualizacion = '".$_POST['id_actualizacion']."' 
								WHERE id_foto = '".$_POST['id_foto']."'");

							//verifico si se desea redimensionar la imagen
							if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
								$dimensiones = explode("x",$_POST['redimensionar']);
								if(count($dimensiones) > 1) {
									//obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$nombre_completo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$id_foto."_small.".$extension;
									//paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
								}
							}
						}					
						//elimino el archivo temporal
						unlink($_FILES['nombre_archivo']['tmp_name']);
					} else {
						//elimino el archivo temporal y muestro mensaje de error en tipo de archivo
						unlink($_FILES['nombre_archivo']['tmp_name']);
						echo("<script type='text/javascript' language='javascript'>");
						echo("window.alert('".$msg_error[10]."');");
						echo("window.history.back();");
						echo("</script>");						
					}
				} else {
					//actualizo los datos
					$conexion->Query("UPDATE fotogaleria 
						SET 
							categoria = '".addslashes($_POST['categoria'])."', 
							descripcion = '".addslashes($_POST['descripcion'])."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							activo = '".$_POST['activo']."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_foto = '".$_POST['id_foto']."')");

					//verifico si se desea redimensionar la imagen
					if(isset($_POST['redimensionar']) and ($_POST['redimensionar']) != "") {
						$dimensiones = explode("x",$_POST['redimensionar']);
						if(count($dimensiones) > 1) {
							//	obtengo el nombre y extension del archivo
							$res = explode(".", $_POST['old_nombre_archivo']);
							$nombre = $res[0];
							$extension = $res[count($res) - 1];
							//obtengo la ubicacion de la imagen original
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['old_nombre_archivo'];
							$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
							//paso como parametro el nombre la nueva imagen pequeña
							redimensionar_imagen($nombre_completo_imagenoriginal,$nombre_completo_imagensmall,$dimensiones[0],$dimensiones[1]);
						}
					}
					
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Eliminar imagen/publicidad
			if(isset($_GET['accion']) and ($_GET['accion'] == "deleteimagen")) {
				//	verifico si el archivo existe
				$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$_POST['ida'];
				if(file_exists($nombre_completo_imagenoriginal)) {
				//	obtengo el nombre y extension del archivo
				$res = explode(".", $_POST['ida']);
				$nombre = $res[0];
				$extension = $res[count($res) - 1];
				//	obtengo la ubicacion de la imagen small
				$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
				//	elimino el archivo
				unlink($nombre_completo_imagenoriginal);
				if(file_exists($nombre_completo_imagensmall)) {
					unlink($nombre_completo_imagensmall);
				}
				}
				$conexion->Query("UPDATE fotogaleria SET ".$_POST['tipo']." = null WHERE (id_foto = '".$_POST['idc']."')");
				echo("{ \"estado\" : \"ok\" }");
			}

			//	Renombrar
			if(isset($_GET['accion']) and ($_GET['accion'] == "renombrar")) {
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatecategoria"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nueva Categoria</label>
									<div class="controls">
										<input type="text" id="nuevacategoria" name="nuevacategoria" size="50" maxlength="255" title="Nueva Categoria" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar categoria" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//Actualizar Categoria
			if(isset($_GET['accion']) and ($_GET['accion'] == "updatecategoria")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE fotogaleria SET categoria = '".addslashes($_POST["nuevacategoria"])."' WHERE (categoria = '".$_POST["categoria"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}
			
			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Eliminando registro...</h5>");
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	obtengo los datos del registro
						$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
						if($registros) {
							while(!$registros->EOF) {
								//	verifico si el archivo existe
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$registros->fields["NOMBRE"];
								if(file_exists($nombre_completo_imagenoriginal)) {
									//	obtengo el nombre y extension del archivo small
									$res = explode(".", $registros->fields["NOMBRE"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen small
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
									//elimino el archivo
									unlink($nombre_completo_imagenoriginal);
									if(file_exists($nombre_completo_imagensmall)) {
										unlink($nombre_completo_imagensmall);
									}
								}
								$registros->MoveNext();
							}
							//elimino el registro
							$conexion->Query("DELETE FROM fotogaleria WHERE (id_foto = '".$valor."')");			
						}
						echo("{ \"estado\" : \"ok\" }");
					}		
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_GET['registro'])) {
					foreach($_GET['registro'] as $valor) {
						//	verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE fotogaleria SET activo = 1 WHERE (id_foto = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE fotogaleria SET activo = 0 WHERE (id_foto = '".$valor."')");
								} else {
									$conexion->Query("UPDATE fotogaleria SET activo = 1 WHERE (id_foto = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
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
		//	-----	Fin IMAGENES DE PORTADA

		include("../footer.php");
		?>
	</body>
</html>