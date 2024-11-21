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
		<title><?php echo(armarHead()); ?>
		</title>
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

			//	-----	CLIENTES
			if (isset($_GET['menu']) && ($_GET['menu'] == "clientes")) {
				$datosSucursalActual = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");

				//	Listado
				if (!isset($_GET['accion'])) {
					$query = "SELECT
							COD_USUARIO,
							concat(TIPO_DOC,' ',NRO_DOC) as DOCUMENTO,
							NOMBRE_COMPLETO,
							concat(LATITUD,'|',LONGITUD) COORDENADAS,
							EMAIL,
							CREDITOS,
							GRATIS,
							CATEGORIA_USUARIO,
							FECHA_ALTA,
							FECHA_INICIO_SERVICIO,
							ACTIVO,
							ID_USUARIO
						FROM usuarios
						WHERE
							(tipo_usuario = 'Cliente')
							and (id_sucursal = '".$_SESSION['usuario_sucursal']."')
					";
					if (isset($_GET['texto'])) {
						$codigo = sprintf("%04s",trim($_GET['texto']));

						$query .= " and (
							(upper(concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO)) = '".$codigo."') or
							(concat(TIPO_DOC,' ',NRO_DOC) LIKE '%".$_GET['texto']."%') or
							(upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or
							(upper(email) LIKE upper('%".$_GET['texto']."%')) or
							(upper(categoria_usuario) LIKE upper('%".$_GET['texto']."%'))
						) ";
					}
					$query .= "ORDER BY nombre_completo";

					showTable2(
						$conexion,
						$query,
						array(
							"COD_USUARIO"=>"Código",
							"DOCUMENTO"=>"Documento",
							"NOMBRE_COMPLETO"=>"Nombres y Apellidos",
							"COORDENADAS"=>"<i class=\"icon-map-marker\"></i>",
							"EMAIL"=>"Correo Electr&oacute;nico",
							"CREDITOS"=>"Cr&eacute;ditos",
							"GRATIS"=>"Gratis",
							"CATEGORIA_USUARIO"=>"Categoria",
							"FECHA_ALTA"=>"Fecha de Alta",
							"FECHA_INICIO_SERVICIO"=>"Inicio Servicio",
							"ACTIVO"=>"Activo"
						),
						array(0=>"ID_USUARIO"),
						$_SESSION['usuario_rxp'],
						true,
						true,
						$datos_acceso,
						null,
						array(
							"exportarClientes"=>"Exportar Clientes",
							"importarClientes"=>"Importar Clientes"
						),
						array(
							"nuevaventa"=>"Nueva Venta",
							"generardeudas"=>"Generar deudas",
							"ventasregistradas"=>"Ventas registradas",
							"nuevoservicio"=>"Nuevo Servicio T&eacute;cnico",
							"nuevopedido"=>"Nuevo Pedido",
							"servicios"=>"Servicios T&eacute;cnicos registrados",
							"-"=>"-",
							"cambiarfoto"=>"Actualizar foto",
							"cambiaremail"=>"Actualizar correo electr&oacute;nico"
						)
					);
				}

				//Nuevo
				if (isset($_GET['accion']) && ($_GET['accion'] == "nuevo")) {
					?>
					<form method="post"
						action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="tipo_usuario" value="Cliente" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos del Cliente</a></li>
								<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
								<li><a href="#tab03" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active" style="background: url(<?php echo("../../".$path_imagenes."fotos/"."fotouser.jpg"); ?>) no-repeat top right;">
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label">C&oacute;digo de Usuario</label>
												<div class="controls">
													<?php
													$datos = $conexion->GetRow("SELECT IF(COD_USUARIO > 0, (max(COD_USUARIO) + 1), 1) as COD_USUARIO FROM usuarios WHERE (tipo_usuario = 'Cliente') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY cod_usuario DESC");
													?>
													<input type="text" id="cod_usuario" name="cod_usuario" value="<?php echo(sprintf("%04s", $datos["COD_USUARIO"])); ?>" class="input-small" maxlength="8" size="10" title="C&oacute;digo de Usuario" placeholder="0000" style="text-align: right;font-size: 1.5em;" required readonly="readonly" />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Categoria de Cliente</label>
												<div class="controls">
													<input type="text" id="categoria_usuario" name="categoria_usuario" maxlength="50" size="10" title="Categoria de Usuario" placeholder="Categoria de Usuario" required />
												</div>
											</div>
										</div>
										<div class="span4">
										</div>
									</div>
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Tipo y Nro. de Documento</label>
												<div class="controls">
													<select id="tipo_doc" name="tipo_doc" class="input-mini" title="Tipo de Documento"
														required>
														<option value=""></option>
														<?php
															for ($i=0; $i<count($tipo_documento); $i++) {
																?>
														<option value="<?php echo($tipo_documento[$i]); ?>" <?php if ($tipo_documento[$i] == "CI") { echo("selected='selected'"); } ?>>
															<?php echo($tipo_documento[$i]); ?>
														</option>
																<?php
															} 
														?>
													</select>
													<input type="text" id="nro_doc" name="nro_doc" value="0" class="input-small" maxlength="8" size="10" title="Nro. de Documento" placeholder="0000000" required />
												</div>
											</div>
										</div>
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Nombres y Apellidos</label>
												<div class="controls">
													<input type="text" id="nombre" name="nombre" size="25" maxlength="255" title="Nombres" required />
													<input type="text" id="apellidos" name="apellidos" size="25" maxlength="255" title="Apellidos" required />
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Fecha de Nacimiento</label>
												<div class="controls">
													<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="input-medium" title="Fecha de Nacimiento" value="" max="<?php echo(date("Y-m-d")); ?>" size="12" maxlength="10" />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Sexo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="sexo" name="sexo" value="M" checked="checked"> Masculino
													</label>
													<label class="radio inline">
														<input type="radio" id="sexo" name="sexo" value="F"> Femenino
													</label>
												</div>
											</div>
										</div>
										<div class="span4">
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" size="50" maxlength="255" title="Direcci&oacute;n" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tel&eacute;fono</label>
												<div class="controls">
													<input type="text" id="telefono_fijo" name="telefono_fijo" size="23" maxlength="50" title="Tel&eacute;fono fijo" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">M&oacute;vil</label>
												<div class="controls">
													<input type="text" id="telefono_movil" name="telefono_movil" size="23" maxlength="50" title="Tel&eacute;fono movil" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Ciudad</label>
												<div class="controls">
													<input type="text" id="ciudad" name="ciudad" size="50" maxlength="255" title="Ciudad" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Pa&iacute;s</label>
												<div class="controls">
													<select id="codigo_pais" name="codigo_pais" title="Pa&iacute;s" required>
														<option value=""></option>
														<?php
															$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
															while (!$paises->EOF) {
																?>
														<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>">
															<?php echo($paises->fields["PAIS"]); ?>
														</option>
																<?php
																$paises->MoveNext();
															}
														?>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Correo Electr&oacute;nico</label>
												<div class="controls">
													<input type="text" id="email" name="email" size="50" maxlength="255" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" title="Correo Electr&oacute;nico" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Foto</label>
												<div class="controls">
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="archivo_foto" name="archivo_foto" class="input-file" title="Foto" accept=".jpeg,.jpg,.png,.gif" />
												</div>
											</div>
											<hr />
											<div class="control-group">
												<label class="control-label">Ubicaci&oacute;n</label>
												<div class="controls">
													<div class="input-append">
														<input type="text" name="latitud" id="latitud" value="<?php if ($datosSucursalActual["LATITUD"] != null) { echo($datosSucursalActual["LATITUD"]);} else { echo($latitud); } ?>" class="input-medium" title="Latitud" />
														<input type="text" name="longitud" id="longitud" value="<?php if ($datosSucursalActual["LONGITUD"] != null) { echo($datosSucursalActual["LONGITUD"]); } else { echo($longitud); } ?>" class="input-medium" title="Longitud" />
														<button type="button" id="editarCoordenadas" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalMapaUbicacion">Definir ubicaci&oacute;n</button>
													</div>
												</div>
											</div>

										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Cr&eacute;ditos</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="creditos" data-limite="0" class="btn"><i class="icon-minus"></i></button>
														<input type="text" id="creditos" name="creditos" value="0" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos" />
														<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="creditos" class="btn"><i class="icon-plus"></i></button>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Gratis</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="gratis" data-limite="0" class="btn"><i class="icon-minus"></i></button>
														<input type="text" id="gratis" name="gratis" value="0" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis" />
														<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="gratis" class="btn"><i class="icon-plus"></i></button>
													</div>
												</div>
											</div>
											<hr />
											<div class="control-group">
												<label class="control-label">NIT</label>
												<div class="controls">
													<input type="text" id="nit" name="nit" size="50" maxlength="20" title="NIT" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Línea</label>
												<div class="controls">
													<input type="text" id="linea" name="linea" size="50" maxlength="255" title="Línea" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Modelo</label>
												<div class="controls">
													<input type="text" id="modelo" name="modelo" size="50" maxlength="255" title="Modelo" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Router</label>
												<div class="controls">
													<input type="text" id="router" name="router" size="50" maxlength="255" title="Router" />
												</div>
											</div>
											<hr />
											<div class="control-group">
												<label class="control-label">Fecha de Alta</label>
												<div class="controls">
													<input type="date" name="fecha_alta" id="fecha_alta" class="input-medium" title="Fecha de Alta" value="<?php echo(date("Y-m-d")); ?>" max="<?php echo(date("Y-m-d")); ?>" size="12" maxlength="10" required />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Inicio del Servicio</label>
												<div class="controls">
													<input type="date" name="fecha_inicio_servicio" id="fecha_inicio_servicio" class="input-medium" title="Fecha de Inicio del Servicio" value="<?php echo(date("Y-m-d")); ?>" max="<?php echo(date("Y-m-d")); ?>" size="12" maxlength="10" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="1" checked="checked" /><?php echo($estados[1])?>
													</label>
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="0" /><?php echo($estados[0])?>
													</label>
												</div>
											</div>

										</div>
									</div>

								</div>

								<div id="tab02" class="tab-pane">
									<div class="control-group">
										<textarea class="ckeditor" id="comentarios" name="comentarios" rows="40" cols="60"
											title="Comentarios" style="width:100%"></textarea>
									</div>
								</div>

								<div id="tab03" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Galeria de Im&aacute;genes <a href="javascript:void();" rel="popover" title="Galeria de Im&aacute;genes" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" />

											<input type="hidden" id="indicadorImagenGaleria" value="1" />
											<div id="adjuntos_galeria1" class="cloneImagen">
												<label class="control-label">[IMAGEN01]</label>
												<div class="controls">
													<input type="checkbox" id="redimensionarGaleria" name="redimensionarGaleria[]" title="Redimensionar imagen" />
													<input type="text" id="comentario_imagenGaleria" name="comentario_imagenGaleria[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" />
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="imagen_adjuntaGaleria" name="imagen_adjuntaGaleria[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" />
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="form-actions">
								<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							</div>
						</div>
					</form>
					<?php
				}

				//Insertar
				if (isset($_GET['accion']) && ($_GET['accion'] == "insert")) {
					$_POST['nro_doc'] = sprintf("%07s", $_POST['nro_doc']);
					$nombre_completo = addslashes(trim($_POST['nombre']))." ".addslashes(trim($_POST['apellidos']));
					$_POST['sexo'] = !empty($_POST['sexo']) ? $_POST['sexo'] : 'M';

					if (isset($_POST['id_usuario'])) {

						if(isset($_POST['comentarios'])){
							$comentario_nuevo = 'comentarios = "'.addslashes($_POST['comentarios']).'", ';
						} else {
							$comentario_nuevo = !empty($_POST['comentario_nuevo']) ? 'comentarios = concat(IFNULL(comentarios,""),"<p>","'.date('d-m-Y H:i').' '.$_SESSION['usuario_login'].' - '.$_POST['comentario_nuevo'].'","</p>"), ' : '';
						}

						$fechaNacimiento_set = !empty($_POST['fecha_nacimiento']) && ($_POST['fecha_nacimiento'] != '0000-00-00') ? 'fecha_nacimiento = "'.$_POST['fecha_nacimiento'].'", ' : 'fecha_nacimiento = null, ';
						$fechaInicioServicio_set = !empty($_POST['fecha_inicio_servicio']) && ($_POST['fecha_inicio_servicio'] != '0000-00-00') ? 'fecha_inicio_servicio = "'.$_POST['fecha_inicio_servicio'].'", ' : 'fecha_inicio_servicio = null, ';
						$actualizacion_set = !empty($_POST['id_actualizacion']) ? "id_actualizacion = '".$_POST['id_actualizacion']."', " : '';

						// Update
						echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
						$conexion->Query("UPDATE usuarios
							SET
								cod_usuario = '".$_POST['cod_usuario']."',
								tipo_doc = '".$_POST['tipo_doc']."',
								nro_doc = '".$_POST['nro_doc']."',
								nit = '".$_POST['nit']."',
								nombre = '".addslashes(trim($_POST['nombre']))."',
								apellidos = '".addslashes(trim($_POST['apellidos']))."',
								nombre_completo = '".$nombre_completo."',
								{$fechaNacimiento_set}
								sexo = '".$_POST['sexo']."',
								domicilio = '".addslashes($_POST['domicilio'])."',
								telefono = '".$_POST['telefono_fijo']."',
								telefono_movil = '".$_POST['telefono_movil']."',
								ciudad = '".addslashes($_POST['ciudad'])."',
								codigo_pais = '".$_POST['codigo_pais']."',
								email = '".$_POST['email']."',
								linea = '".addslashes($_POST['linea'])."',
								modelo = '".addslashes($_POST['modelo'])."',
								router = '".addslashes($_POST['router'])."',
								tipo_usuario = '".$_POST['tipo_usuario']."',
								fecha_alta = '".$_POST['fecha_alta']."',
								activo = '".$_POST['activo']."',
								{$comentario_nuevo}
								creditos = '".$_POST['creditos']."',
								gratis = '".$_POST['gratis']."',
								categoria_usuario = '".trim($_POST['categoria_usuario'])."',
								{$fechaInicioServicio_set}
								{$actualizacion_set}
								latitud = '".$_POST['latitud']."',
								longitud = '".$_POST['longitud']."'
							WHERE
								(id_usuario = '".$_POST['id_usuario']."')
						");

						$id_registro = $_POST['id_usuario'];
						$id_archivo_usuario = sprintf("US%08s", $id_registro);
					} else {
						//	Insert
						echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");

						$fechaNacimiento_set = !empty($_POST['fecha_nacimiento']) && ($_POST['fecha_nacimiento'] != '0000-00-00') ? '"'.$_POST['fecha_nacimiento'].'", ' : 'null, ';
						$actualizacion_set = !empty($_POST['id_actualizacion']) ? $_POST['id_actualizacion'] : 'null';

						$passwordUsuario = generarPassword();
						$conexion->Query("INSERT INTO usuarios
							(
								ID_USUARIO,
								COD_USUARIO,
								ID_SUCURSAL,
								TIPO_DOC,
								NRO_DOC,
								NIT,
								NOMBRE,
								APELLIDOS,
								NOMBRE_COMPLETO,
								FECHA_NACIMIENTO,
								SEXO,
								DOMICILIO,
								TELEFONO,
								TELEFONO_MOVIL,
								CIUDAD,
								CODIGO_PAIS,
								EMAIL,
								LINEA,
								MODELO,
								ROUTER,
								TIPO_USUARIO,
								FECHA_ALTA,
								ACTIVO,
								COMENTARIOS,
								CREDITOS,
								GRATIS,
								CATEGORIA_USUARIO,
								LATITUD,
								LONGITUD,
								ID_ACTUALIZACION
							) VALUES(
								null,
								'".$_POST['cod_usuario']."',
								'".$_SESSION['usuario_sucursal']."',
								'".$_POST['tipo_doc']."',
								'".$_POST['nro_doc']."',
								'".trim($_POST['nit'])."',
								'".addslashes($_POST['nombre'])."',
								'".addslashes($_POST['apellidos'])."',
								'".$nombre_completo."',
								{$fechaNacimiento_set}
								'".$_POST['sexo']."',
								'".addslashes($_POST['domicilio'])."',
								'".trim($_POST['telefono_fijo'])."',
								'".trim($_POST['telefono_movil'])."',
								'".addslashes($_POST['ciudad'])."',
								'".$_POST['codigo_pais']."',
								'".trim($_POST['email'])."',
								'".addslashes($_POST['linea'])."',
								'".addslashes($_POST['modelo'])."',
								'".addslashes($_POST['router'])."',
								'".$_POST['tipo_usuario']."',
								'".$_POST['fecha_alta']."',
								'".$_POST['activo']."',
								'".addslashes($_POST['comentarios'])."',
								'".$_POST['creditos']."',
								'".$_POST['gratis']."',
								'".trim($_POST['categoria_usuario'])."',
								'".trim($_POST['latitud'])."',
								'".trim($_POST['longitud'])."',
								{$actualizacion_set}
							)
						");
						$id_registro = $conexion->Insert_ID();
						$id_archivo_usuario = sprintf("US%08s", $id_registro);
					}

					if($id_registro > 0){

						if($_POST['fecha_inicio_servicio'] != ''){
							$conexion->Execute("UPDATE usuarios SET fecha_inicio_servicio = '".$_POST['fecha_inicio_servicio']."' WHERE (id_usuario = '".$id_registro."')");
						} else {
							$conexion->Execute("UPDATE usuarios SET fecha_inicio_servicio = null WHERE (id_usuario = '".$id_registro."')");
						}

						//	verifico los archivos subidos
						if (isset($_FILES["archivo_foto"]) and isset($id_registro)) {
							//obtengo el nombre del archivo y su extension
							$res = explode(".", $_FILES['archivo_foto']['name']);
							$tipo_archivo = $_FILES['archivo_foto']['type'];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_archivo_usuario.".".$extension;

							//verifico si se subio un archivo
							if (is_uploaded_file($_FILES['archivo_foto']['tmp_name'])) {
								//verifico el tipo de archivo
								if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
									($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {

									//copio el archivo
									if (copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."fotos/".$nombre_archivo))) {
										//si no hubo inconvenientes actualiza el nombre del archivo
										$conexion->Query("UPDATE usuarios SET foto = '".$nombre_archivo."' WHERE (id_usuario = '".$id_registro."')");

										//obtengo la ubicacion de la imagen original y small
										$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$nombre_archivo;
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$id_archivo_usuario."_small.".$extension;
										//paso como parametro el nombre la nueva imagen pequeña
										redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 150, 150);
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

						//	verifico las imagenes de la GALERIA subidos
						if (isset($_FILES["imagen_adjuntaGaleria"]) and isset($id_registro)) {
							$total = count($_FILES["imagen_adjuntaGaleria"]["name"]);
							for ($i = 0; $i < $total; $i++) {
								//	obtengo el nombre del archivo y su extension
								$datos = $temp->GetRow("SELECT IF(ID_ARCHIVO > 0, (max(ID_ARCHIVO) + 1), 1) as ID_ARCHIVO FROM archivos ORDER BY id_archivo desc");
								$id_imagen = sprintf("IMG%06s", $datos["ID_ARCHIVO"]);
								$res = explode(".", $_FILES['imagen_adjuntaGaleria']['name'][$i]);
								$tipo_archivo = $_FILES['imagen_adjuntaGaleria']['type'][$i];
								$extension = $res[count($res) - 1];
								$nombre_archivo = $id_imagen.".".$extension;

								//	verifico si subio el archivo
								if (is_uploaded_file($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i])) {
									//	verifico el tipo de archivo
									if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
									($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
										//	copio el archivo
										copy($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i], stripslashes("../../".$path_imagenes."contenidos/".$nombre_archivo));
										//	elimino el archivo temporal
										unlink($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i]);
										//	inserto los datos del archivo
										$conexion->Query("INSERT INTO archivos
											(
												ID_ARCHIVO,
												ID_USUARIO,
												NOMBRE_ARCHIVO,
												TIPO_ARCHIVO,
												DESTINO,
												DESCARGAS,
												COMENTARIO
											) VALUES(
												null,
												'".$id_registro."',
												'".$nombre_archivo."',
												'Imagen',
												'galeria',
												0,
												'".addslashes($_POST['comentario_imagenGaleria'][$i])."'
											)
										");

										//	redimensiono la imagen cargada
										if (isset($_POST['redimensionarGaleria']) and ($_POST['redimensionarGaleria'][$i] == 'on')) {
											//	obtengo la ubicacion de la imagen original
											$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$nombre_archivo;
											$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$id_imagen."_small.".$extension;
											//	paso como parametro el nombre la nueva imagen peque&ntilde;a
											redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
										}
									} else {
										//elimino el archivo temporal
										unlink($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i]);
										echo("<script type='text/javascript'>");
										echo("window.alert('".$_FILES['imagen_adjuntaGaleria']['name'][$i].": ".$msg_error[10]."');");
										echo("</script>");
									}# if
								}# if
							}# for
						}# if

						?>
						<form class="form-alert">
							<h3>Informaci&oacute;n</h3>
							<p>Se ha registrado de forma satisfactoria el nuevo registro o las modificaciones realizadas del Cliente:
								<strong><?php echo("[".sprintf("%04s", $_POST['cod_usuario'])."] ".stripslashes($nombre_completo)); ?></strong>.
							</p>
							<p>A continuaci&oacute;n, usted posee varias opciones por realizar.</p>
							<div class="form-actions">
								<div class="btn-toolbar">
									<div class="btn-group">
										<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=nuevo"); ?>';">Nuevo CLIENTE</button>
									</div>

									<div class="btn-group">
										<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo("ventas.php?menu=ventas&accion=nuevo&registro[]=".$id_registro); ?>';">Nueva VENTA</button>
										<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo("ventas.php?menu=servicios&accion=nuevo&registro[]=".$id_registro); ?>';">Nuevo SERVICIO TECNICO</button>
									</div>

									<div class="btn-group">
										<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
									</div>
								</div>
							</div>
						</form>
						<?php

					} else {
						echo("<script type='text/javascript'>");
						echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
						echo("</script>");
					}

				}

				//Editar
				if (isset($_GET['accion']) && (($_GET['accion'] == "ver") || ($_GET['accion'] == "editar"))) {
					$id_modo_editar = $_GET['registro'][0];
					$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$id_modo_editar."')");
					while (!$registros->EOF) {
						if ($registros->fields["FOTO"] == null) {
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/"."fotouser.jpg";
							$nombre_completo_imagensmall = null;
						} else {
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
							//verifico si el archivo existe
							if (file_exists($nombre_completo_imagenoriginal)) {
								//obtengo el nombre y extension del archivo small
								$res = explode(".", $registros->fields["FOTO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
								if (!file_exists($nombre_completo_imagensmall)) {
									//paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 150, 150);
								}
							} else {
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/"."fotouser.jpg";
								$nombre_completo_imagensmall = null;
							}
						}
						$registros->fields["NRO_DOC"] = sprintf("%07s", $registros->fields["NRO_DOC"]);
						$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
						?>
						<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
							<input type="hidden" name="id_usuario" value="<?php echo($registros->fields["ID_USUARIO"]); ?>" />
							<input type="hidden" name="tipo_usuario" value="<?php echo($registros->fields["TIPO_USUARIO"]); ?>" />
							<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab01" data-toggle="tab">Datos del Cliente</a></li>
									<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
									<li><a href="#tab03" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
									<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=ventasregistradas&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Ventas registradas</a></li>
									<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=serviciosregistrados&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Servicios T&eacute;cnicos registrados</a></li>
									<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" border="0" /> Mas opciones <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cambiarclave&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Cambiar contrase&ntilde;a</a></li>
											<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cambiarfoto&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Actualizar foto</a></li>
											<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cambiaremail&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Actualizar correo electr&oacute;nico</a></li>
											<li class="divider"></li>
											<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviardatos&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Enviar datos de acceso</a></li>
										</ul>
									</li>
								</ul>

								<div class="tab-content">
									<div id="tab01" class="tab-pane active" style="background: url(<?php if ($nombre_completo_imagensmall == null) { echo($nombre_completo_imagenoriginal); } else { echo($nombre_completo_imagensmall); } ?>) no-repeat top right;">
										<div class="row-fluid">
											<div class="span4">
												<div class="control-group">
													<label class="control-label">C&oacute;digo de Usuario</label>
													<div class="controls">
														<input type="text" id="cod_usuario" name="cod_usuario" value="<?php if ($registros->fields["COD_USUARIO"] > 0) { echo(sprintf("%06s", $registros->fields["COD_USUARIO"])); } ?>" class="input-small" maxlength="8" size="10" title="C&oacute;digo de Usuario" placeholder="0000" style="text-align: right;font-size: 1.5em;" required readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span4">
												<div class="control-group">
													<label class="control-label">Categoria de Usuario</label>
													<div class="controls">
														<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php echo($registros->fields["CATEGORIA_USUARIO"]); ?>" maxlength="50" size="10" title="Categoria de Usuario" placeholder="Categoria de Usuario" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
											</div>
											<div class="span4">
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div class="control-group">
													<label class="control-label">Tipo y Nro. de Documento</label>
													<div class="controls">
														<select name="tipo_doc" id="tipo_doc" class="input-mini" title="Tipo de Documento" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
															<option value=""></option>
															<?php
																for ($i=0; $i<count($tipo_documento); $i++) {
																	?>
															<option value="<?php echo($tipo_documento[$i]); ?>" <?php if ($registros->fields["TIPO_DOC"] == $tipo_documento[$i]) { ?>selected="selected"<?php } ?>><?php echo($tipo_documento[$i]); ?></option>
																	<?php
																} 
															?>
														</select>
														<input type="text" name="nro_doc" id="nro_doc" maxlength="8" class="input-small" size="10" title="Nro. de Documento" value="<?php echo($registros->fields["NRO_DOC"]); ?>" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
											</div>
											<div class="span8">
												<div class="control-group">
													<label class="control-label">Nombres y Apellidos</label>
													<div class="controls">
														<input type="text" id="nombre" name="nombre" size="25" maxlength="255" title="Nombres" value="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE"]))); ?>" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
														<input type="text" id="apellidos" name="apellidos" size="25" maxlength="255" title="Apellidos" value="<?php echo(htmlentities(stripslashes($registros->fields["APELLIDOS"]))); ?>" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span4">
												<div class="control-group">
													<label class="control-label">Fecha de Nacimiento</label>
													<div class="controls">
														<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="input-medium" title="Fecha de Nacimiento" placeholder="dd/mm/aaaa" value="<?php echo($registros->fields["FECHA_NACIMIENTO"]); ?>" size="12" maxlength="10" placeholder="dd/mm/aaaa" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
											</div>
											<div class="span4">
												<div class="control-group">
													<label class="control-label">Sexo</label>
													<div class="controls">
														<label class="radio inline">
															<input type="radio" name="sexo" id="sexo" value="M" <?php if ($registros->fields["SEXO"] == "M") { ?>checked="checked"<?php } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> Masculino
														</label>
														<label class="radio inline">
															<input type="radio" name="sexo" id="sexo" value="F" <?php if ($registros->fields["SEXO"] == "F") { ?>checked="checked"<?php } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>Femenino
														</label>
													</div>
												</div>
											</div>
											<div class="span4">
											</div>
										</div>

										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Direcci&oacute;n</label>
													<div class="controls">
														<input type="text" id="domicilio" name="domicilio" size="50" maxlength="255" title="Direcci&oacute;n" value="<?php echo(htmlentities(stripslashes($registros->fields["DOMICILIO"]))); ?>" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Tel&eacute;fono</label>
													<div class="controls">
														<input type="text" id="telefono_fijo" name="telefono_fijo" size="23" maxlength="50" title="Tel&eacute;fono fijo" value="<?php echo($registros->fields["TELEFONO"]); ?>" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">M&oacute;vil</label>
													<div class="controls">
														<input type="text" id="telefono_movil" name="telefono_movil" size="23" maxlength="50" title="Tel&eacute;fono movil" value="<?php echo($registros->fields["TELEFONO_MOVIL"]); ?>" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Ciudad</label>
													<div class="controls">
														<input type="text" name="ciudad" id="ciudad" size="50" maxlength="255" title="Ciudad" value="<?php echo(htmlentities(stripslashes($registros->fields["CIUDAD"]))); ?>" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Pa&iacute;s</label>
													<div class="controls">
														<select name="codigo_pais" id="codigo_pais" title="Pa&iacute;s" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
															<option value=""></option>
															<?php
																$paises = $temp->Query("SELECT * FROM paises ORDER BY pais");
																while (!$paises->EOF) {
																	?>
															<option value="<?php echo($paises->fields["CODIGO_PAIS"]); ?>" <?php if ($paises->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($paises->fields["PAIS"]); ?> </option>
																	<?php
																	$paises->MoveNext();
																} 
															?>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Correo Electr&oacute;nico</label>
													<div class="controls">
														<input type="text" name="email" id="email" size="50" maxlength="255" title="Correo Electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Foto
														<?php
															if ($registros->fields['FOTO'] != null) {
																$path_url_foto = "../../".$path_imagenes."fotos/".$registros->fields['FOTO'];
																if (file_exists($path_url_foto)) {
																	?>
														<a href="<?php echo($path_url_foto); ?>" class="Foto" rel="colorbox" title="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>"><img src="../images/preview.gif" alt="Ver archivo" align="absmiddle" /></a>
														| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
																	<?php
																} else {
																	?>
														<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
																	<?php
																}
															} 
														?>
													</label>
													<div class="controls">
														<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
														<input type="file" name="archivo_foto" class="input-file" title="Foto" accept=".jpeg,.jpg,.png,.gif" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<hr />
												<div class="control-group">
													<label class="control-label">Ubicaci&oacute;n</label>
													<div class="controls">
														<div class="input-append">
															<input type="text" name="latitud" id="latitud" value="<?php if ($registros->fields["LATITUD"] != null) { echo($registros->fields["LATITUD"]); } else { if ($datosSucursalActual["LATITUD"] != null) { echo($datosSucursalActual["LATITUD"]); } else { echo($latitud); } } ?>" class="input-medium" title="Latitud" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
															<input type="text" name="longitud" id="longitud" value="<?php if ($registros->fields["LONGITUD"] != null) { echo($registros->fields["LONGITUD"]); } else { if ($datosSucursalActual["LONGITUD"] != null) { echo($datosSucursalActual["LONGITUD"]); } else { echo($longitud); } } ?>" class="input-medium" title="Longitud" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
															<button type="button" id="editarCoordenadas" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalMapaUbicacion" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Definir ubicaci&oacute;n</button>
															<a href="https://www.google.com/maps/search/?api=1&query=<?php if ($registros->fields["LATITUD"] != null) { echo($registros->fields["LATITUD"]); } else { if ($datosSucursalActual["LATITUD"] != null) { echo($datosSucursalActual["LATITUD"]); } else { echo($latitud); } } ?>,<?php if ($registros->fields["LONGITUD"] != null) { echo($registros->fields["LONGITUD"]); } else { if ($datosSucursalActual["LONGITUD"] != null) { echo($datosSucursalActual["LONGITUD"]); } else { echo($longitud); } } ?>" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" title="Mostrar ubicación en un mapa"><i class="icon-map-marker"></i></a>
														</div>
													</div>
												</div>

											</div>

											<div class="span6">
												<div class="control-group">
													<label class="control-label">Cr&eacute;ditos</label>
													<div class="controls">
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="creditos" data-limite="0" class="btn" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
															<input type="text" id="creditos" name="creditos" value="<?php echo($registros->fields["CREDITOS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
															<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="creditos" class="btn" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
														</div>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Gratis</label>
													<div class="controls">
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-campo="gratis" data-tipo="-" data-limite="0" class="btn" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
															<input type="text" id="gratis" name="gratis" value="<?php echo($registros->fields["GRATIS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
															<button type="button" id="btAumentarCampo" data-campo="gratis" data-tipo="+" class="btn" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
														</div>
													</div>
												</div>
												<hr />
												<div class="control-group">
													<label class="control-label">NIT</label>
													<div class="controls">
														<input type="text" id="nit" name="nit" value="<?php echo($registros->fields["NIT"]); ?>" size="50" maxlength="20" title="NIT" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Línea</label>
													<div class="controls">
														<input type="text" id="linea" name="linea" value="<?php echo($registros->fields["LINEA"]); ?>" size="50" maxlength="255" title="Línea" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Modelo</label>
													<div class="controls">
														<input type="text" id="modelo" name="modelo" value="<?php echo($registros->fields["MODELO"]); ?>" size="50" maxlength="255" title="Modelo" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Router</label>
													<div class="controls">
														<input type="text" id="router" name="router" value="<?php echo($registros->fields["ROUTER"]); ?>" size="50" maxlength="255" title="Router" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													</div>
												</div>
												<hr />
												<div class="control-group">
													<label class="control-label">Fecha de Alta</label>
													<div class="controls">
														<input type="date" name="fecha_alta" id="fecha_alta" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" size="12" maxlength="10" placeholder="dd/mm/aaaa" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Inicio del Servicio</label>
													<div class="controls">
														<input type="date" name="fecha_inicio_servicio" id="fecha_inicio_servicio" class="input-medium" title="Fecha de Inicio del Servicio" placeholder="dd/mm/aaaa" value="<?php echo($registros->fields["FECHA_INICIO_SERVICIO"]); ?>" size="12" maxlength="10" placeholder="dd/mm/aaaa" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Activo</label>
													<div class="controls">
														<label class="radio inline">
															<input type="radio" name="activo" id="activo" value="1" <?php if ($registros->fields["ACTIVO"] == "1") { ?>checked="checked"<?php } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[1])?>
														</label>
														<label class="radio inline">
															<input type="radio" name="activo" id="activo" value="0" <?php if ($registros->fields["ACTIVO"] == "0") { ?>checked="checked"<?php } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[0])?>
														</label>
													</div>
												</div>

											</div>
										</div>

										<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

									</div>

									<div id="tab02" class="tab-pane">
										<?php
											if(!empty($_SESSION['usuario_nivel'])) {
												?>
										<div class="control-group">
											<label class="control-label">Comentario actual</label>
											<div class="controls">
												<blockquote><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></blockquote>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Nuevo comentario a agregar</label>
											<div class="controls">
												<input type="text" name="comentario_nuevo" value="" class="input-small" maxlength="255" size="50" title="Nuevo comentario a agregar" placeholder="Nuevo comentario a agregar" style="width: 99%;" />
											</div>
										</div>
												<?php
											} else {
												?>
										<div class="control-group">
											<textarea class="ckeditor" id="comentarios" name="comentarios" rows="40" cols="60" title="Comentarios" style="width:100%"><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></textarea>
										</div>
												<?php
											}
										?>
									</div>

									<div id="tab03" class="tab-pane">
										<div class="control-group">
											<label class="control-label">Galeria de Im&aacute;genes <a href="javascript:void();" rel="popover" title="Galeria de Im&aacute;genes" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o.">
												<img src="../images/ayuda.gif" /></a>
											</label>
											<div class="controls">
												<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />

												<?php
													//	verifico si posee imagenes en GALERIA
													$i = 1;
													$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_usuario = '".$registros->fields["ID_USUARIO"]."') and (tipo_archivo = 'Imagen') and (destino = 'galeria') ORDER BY id_archivo");
													if ($registros2->RecordCount() > 0) {
														?>
												<table class="table table-striped table-bordered table-condensed" style="margin-top: 10px;">
													<thead>
														<tr>
															<th width="50">Indicador</th>
															<th>Descripci&oacute;n</th>
															<th width="20">Formato</th>
															<th width="50">Tama&ntilde;o</th>
															<th width="20">Editar</th>
															<th width="20">Eliminar</th>
														</tr>
													</thead>
													<tbody>
														<?php
															while (!$registros2->EOF) {
																$referencia = "[IMAGEN".sprintf("%02s", $i)."]"; ?>
														<tr>
															<td><strong><?php echo($referencia); ?></strong>
															</td>
															<?php
																//	verifico si el archivo existe
																$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
																if (file_exists($nombre_completo_imagenoriginal)) {
																	//	obtengo la extension del archivo
																	$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
																	$nombre = $res[0];
																	$extension = $res[count($res) - 1];
																	$formato = $res[count($res) - 1];
																	//	obtengo el tama&ntilde;o del archivo
																	$tamanio_archivo = ceil(filesize($nombre_completo_imagenoriginal) / 1024);
																	//	obtengo la ubicacion de la imagen redimensionada original
																	$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
																	if (file_exists($nombre_completo_imagensmall)) {
																		$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
																	}
																	//	muestro un enlace para ver o descargar la imagen?>
															<td><a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="ImagenesGaleria" rel="colorbox" title="<?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?>"><?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td>
															<td style="text-align: center;"><?php echo($formato); ?> </td>
															<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
															<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
															<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
																	<?php
																} else {
																	//	muestro el nombre del archivo?>
															<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>
															</td>
															<td></td>
															<td></td>
															<td></td>
															<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
																	<?php
																}
															?>
														</tr>
														<?php
																$registros2->MoveNext();
																$i++;
															} 
														?>
													</tbody>
												</table>
														<?php
													}
												?>
												<input type="hidden" id="indicadorImagenGaleria" value="<?php echo($i); ?>" />
												<div id="adjuntos_galeria<?php echo($i); ?>" class="cloneImagen">
													<label class="control-label"><?php echo("[IMAGEN".sprintf("%02s", $i)."]"); ?></label>
													<div class="controls">
														<input type="checkbox" id="redimensionarGaleria" name="redimensionarGaleria[]" title="Redimensionar imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
														<input type="text" id="comentario_imagenGaleria" name="comentario_imagenGaleria[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
														<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
														<input type="file" id="imagen_adjuntaGaleria" name="imagen_adjuntaGaleria[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>

								<div class="form-actions">
									<?php if ($_GET['accion'] == "ver") { ?>
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

				if (isset($_GET['accion']) && (($_GET['accion'] == "nuevo") || ($_GET['accion'] == "editar"))) {
					include_once('./mapaUbicacion.php');
				}

				//Eliminar imagen/publicidad
				if (isset($_GET['accion']) && ($_GET['accion'] == "deleteimagen")) {
					switch ($_POST['tipo']) {
						case "foto":
							//	verifico si el archivo existe
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$_POST['ida'];
							if (file_exists($nombre_completo_imagenoriginal)) {
								//	obtengo el nombre y extension del archivo
								$res = explode(".", $_POST['ida']);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen original
								$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if (file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}

							$conexion->Query("UPDATE usuarios SET ".$_POST['tipo']." = null WHERE (id_usuario = '".$_POST['idc']."')");
							echo("{ \"estado\" : \"ok\" }");
							break;

						default:
							//	obtengo los datos
							$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_usuario = '".$_POST['idc']."') and (id_archivo = '".$_POST['ida']."') ORDER BY id_archivo");
							if ($registros) {
								while (!$registros->EOF) {
									if ($_POST['tipo'] == 'Imagen') {
										//	verifico si el archivo existe
										$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
										if (file_exists($nombre_completo_imagenoriginal)) {
											//	obtengo el nombre y extension del archivo
											$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
											$nombre = $res[0];
											$extension = $res[count($res) - 1];
											//	obtengo la ubicacion de la imagen original
											$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
											//	elimino el archivo
											unlink($nombre_completo_imagenoriginal);
											if (file_exists($nombre_completo_imagensmall)) {
												unlink($nombre_completo_imagensmall);
											}
										}
									}
									if ($_POST['tipo'] == 'Archivo') {
										//	verifico si el archivo existe
										$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
										if (file_exists($nombre_completo_documento)) {
											//	elimino el archivo
											unlink($nombre_completo_documento);
										}
									}
									//	elimino el registro
									$temp->Query("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");
									$registros->MoveNext();
								}
							}
							echo("{ \"estado\" : \"ok\" }");

							break;
					}
				}

				//	Eliminar
				if (isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
					if (isset($_POST['registro'])) {
						foreach ($_POST['registro'] as $valor) {
							//	obtengo los datos y verifico si tiene archivo FOTO
							$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
							if ($registros) {
								while (!$registros->EOF) {
									if ($registros->fields["FOTO"] != null) {
										$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
										//	verifico si el archivo existe
										if (file_exists($nombre_completo_imagenoriginal)) {
											//obtengo el nombre y extension del archivo small
											$res = explode(".", $registros->fields["FOTO"]);
											$nombre = $res[0];
											$extension = $res[count($res) - 1];
											//	obtengo la ubicacion de la imagen small
											$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
											//	elimino el archivo
											unlink($nombre_completo_imagenoriginal);
											if (file_exists($nombre_completo_imagensmall)) {
												unlink($nombre_completo_imagensmall);
											}
										}
									}
									$registros->MoveNext();
								}
							}
							//	elimino los accesos registrados
							$conexion->Execute("DELETE FROM contador WHERE (id_usuario = ".$valor.")");
							//	elimino los accesos otorgados
							$conexion->Execute("DELETE FROM privilegios WHERE (id_usuario = ".$valor.")");
							//	elimino los contenidos
							$conexion->Execute("DELETE FROM contenidos WHERE (id_usuario = ".$valor.")");
							//	elimino las cotizaciones
							$conexion->Execute("DELETE FROM cotizaciones WHERE (id_cliente = ".$valor.")");
							$conexion->Execute("DELETE FROM cotizaciones WHERE (id_usuario = ".$valor.")");
							//	elimino los egresos
							$conexion->Execute("DELETE FROM egresos WHERE (id_cliente = ".$valor.")");
							$conexion->Execute("DELETE FROM egresos WHERE (id_usuario = ".$valor.")");
							//	elimino los inventarios
							$conexion->Execute("DELETE FROM inventarios WHERE (id_usuario = ".$valor.")");
							$conexion->Execute("DELETE FROM inventarios WHERE (id_usuarioverifica = ".$valor.")");
							//	elimino las ventas
							$conexion->Execute("DELETE FROM ventas WHERE (id_cliente = ".$valor.")");
							$conexion->Execute("DELETE FROM ventas WHERE (id_usuario = ".$valor.")");
							//	elimino el registro
							$conexion->Execute("DELETE FROM usuarios WHERE (id_usuario = ".$valor.")");
						}
						echo("{ \"estado\" : \"ok\" }");
					}
				}

				//Enviar datos de acceso
				if (isset($_GET['accion']) && ($_GET['accion'] == "enviardatos")) {
					?>
					<form class="form-alert">
						<h3>Enviando datos de acceso...</h3>
						<div class="body-alert">
							<?php
								foreach ($_GET['registro'] as $valor) {
									$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
									while (!$registros->EOF) {
										//obtengo los datos de la plantilla
										$Htmlfile = $sitio_url."xpanel/templates/"."datos_acceso.html";
										if (verificar_url($Htmlfile)) {
											//$contenido = file_get_contents($Htmlfile);
											$contenido = obtenerDatosArchivo($Htmlfile);
											$titulo = substr($contenido, strpos($contenido, "<title>") + 7, (strpos($contenido, "</title>") - (strpos($contenido, "<title>") + 7)));
											$contenido = str_replace("{FECHA}", formato_fecha(date("d/m/Y"), 5), $contenido);

											$contenido = str_replace("{SITIO_URL}", $sitio_url, $contenido);
											$contenido = str_replace("{SITIO_URL_XPANEL}", $sitio_url."xpanel/", $contenido);
											$contenido = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido);
											$contenido = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido);

											//	obtengo el logo para boletin
											if (verificar_url($sitio_url."xpanel/templates/logo.jpg")) {
												$contenido = str_replace("{LOGO}", "<img src='".$sitio_url."xpanel/templates/logo.jpg"."'>", $contenido);
											} else {
												$contenido = str_replace("{LOGO}", "<h1>".$sitio_nombre."</h1>", $contenido);
											}

											$contenido = str_replace("{NOMBRE_COMPLETO}", $registros->fields["NOMBRE_COMPLETO"], $contenido);
											$contenido = str_replace("{USUARIO}", $registros->fields["USUARIO"], $contenido);
											$contenido = str_replace("{CLAVE}", $registros->fields["CLAVE"], $contenido);
											$contenido = str_replace("Gracias por utilizar nuestro servicio de recuperaci&oacute;n de contrase&ntilde;a.", " ", $contenido);

											$contenido = str_replace("{PUBLICIDAD1}", "", $contenido);
											$contenido = str_replace("{PUBLICIDAD2}", "", $contenido);

											//notifico al administrador la accion registrada
											$mail = new PHPMailer();
											$mail->Priority = 1;
											$mail->ConfirmReadingTo = $sitio_email_confirmacion;
											$mail->From				= $sitio_email;
											$mail->FromName			= $sitio_nombre;
											$mail->Subject			= "[ ".$sitio_nombre." ] ".$titulo;
											$mail->IsHTML(true);
											$mail->Body				= $contenido;
											$mail->AddAddress($registros->fields["EMAIL"]);
											$mail->AddBCC($sitio_email_admin);
											if ($mail->Send()) {
												?>
						<img src="../images/informacion.png" alt="" title="" style="float: left;margin: 5px;" />
						<p>Se ha enviado los datos de acceso a la direcci&oacute;n de correo electr&oacute;nico: <strong>&quot;<?php echo($registros->fields["EMAIL"]); ?>&quot;</strong>.</p>
												<?php
											} else {
												?>
						<img src="../images/exclamacion.png" alt="" title="" style="float: left;margin: 5px;" />
						<p>No se ha podido enviar los datos de acceso a la direcci&oacute;n de correo electr&oacute;nico: <strong>&quot;<?php echo($registros->fields["EMAIL"]); ?>&quot;</strong>.</p>
												<?php
											}
										}
										$registros->MoveNext();
									}
								} 
							?>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</form>
					<?php
				}

				//	Ventana para cambio de datos desde del usuario seleccionado: CLAVE, FOTO y EMAIL
				if (isset($_GET['accion']) && (($_GET['accion'] == "cambiarclave") || ($_GET['accion'] == "cambiarfoto") || ($_GET['accion'] == "cambiaremail"))) {
					$registros = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['registro'][0]."')");
						?>
						<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updateuser"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
							<div class="tabbable">
								<input type="hidden" name="registro[]" value="<?php echo($_GET['registro'][0]); ?>" />
								<input type="hidden" name="tipo" value="<?php echo($_GET['accion']); ?>" />

								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab01">Datos a actualizar</a></li>
								</ul>

								<div class="tab-content">
									<div id="tab01" class="active" style="min-height: 60px;">
										<div class="control-group">
											<label class="control-label">Nombres y Apellidos</label>
											<div class="controls">
												<input type="text" size="50" maxlength="255" value="<?php echo(stripslashes($registros["NOMBRE_COMPLETO"])); ?>" readonly="readonly" style="width: 70%;" />
											</div>
										</div>
										<?php
											if ($_GET['accion'] == "cambiarclave") {
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
												<input type="text" id="clave" name="clave" size="50" maxlength="255" title="Nueva Contrase&ntilde;a" value="" required />
											</div>
										</div>
												<?php
											}

									if ($_GET['accion'] == "cambiarfoto") {
											?>
										<div class="control-group">
											<label class="control-label">Foto actual</label>
												<?php
													if ($registros['FOTO'] != null) {
														$path_url_foto = "../../".$path_imagenes."fotos/".$registros['FOTO'];
														if (file_exists($path_url_foto)) {
															?>
											<a href="<?php echo($path_url_foto); ?>" class="Foto" rel="colorbox" title="<?php echo(htmlentities(stripslashes($registros["NOMBRE_COMPLETO"]))); ?>"><img src="../images/preview.gif" alt="Ver archivo" align="absmiddle" /></a>
											| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php
														} else {
															?>
											<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php
															}
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
												<input type="file" id="archivo_foto" name="archivo_foto" title="Nueva Foto" accept=".jpeg,.jpg,.png,.gif" required />
											</div>
										</div>
										<?php
									}

									if ($_GET['accion'] == "cambiaremail") {
										?>
										<div class="control-group">
											<label class="control-label">Correo electr&oacute;nico actual</label>
											<div class="controls">
												<input type="text" size="50" maxlength="255" value="<?php echo($registros["EMAIL"]); ?>" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nuevo Correo electr&oacute;nico</label>
											<div class="controls">
												<input type="text" id="email" name="email" size="50" maxlength="255" title="Nuevo Correo electr&oacute;nico" value="" required />
											</div>
										</div>
										<?php
									} 
									?>
									</div>
								</div>

								<div class="form-actions">
									<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
									<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								</div>
							</div>
						</form>
					<?php
				}

				if (isset($_GET['accion']) && ($_GET['accion'] == "updateuser")) {
					echo("<h3>Actualizando datos del usuario...</h3>");

					if ($_POST['tipo'] == "cambiarclave") {
						$conexion->Query("UPDATE usuarios SET clave = '".trim($_POST['clave'])."', clave2 = '".md5(trim($_POST['clave']))."' WHERE (id_usuario = '".$_POST['registro'][0]."')");
					}

					if ($_POST['tipo'] == "cambiarfoto") {
						$id_registro = $_POST['registro'][0];
						//	elimino cualquier archivo anterior asignado
						$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
						if ($registros) {
							while (!$registros->EOF) {
								if ($registros->fields["FOTO"] != null) {
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
									//	verifico si el archivo existe
									if (file_exists($nombre_completo_imagenoriginal)) {
										//obtengo el nombre y extension del archivo small
										$res = explode(".", $registros->fields["FOTO"]);
										$nombre = $res[0];
										$extension = $res[count($res) - 1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
										//	elimino el archivo
										unlink($nombre_completo_imagenoriginal);
										if (file_exists($nombre_completo_imagensmall)) {
											unlink($nombre_completo_imagensmall);
										}
									}
								}
								$registros->MoveNext();
							}
						}
						$id_archivo_usuario = sprintf("US%07s", $id_registro);
						//	verifico los archivos subidos
						if (isset($_FILES["archivo_foto"])) {
							//	obtengo el nombre del archivo y su extension
							$res = explode(".", $_FILES['archivo_foto']['name']);
							$tipo_archivo = $_FILES['archivo_foto']['type'];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_archivo_usuario.".".$extension;

							//verifico si se subio un archivo
							if (is_uploaded_file($_FILES['archivo_foto']['tmp_name'])) {
								//verifico el tipo de archivo
								if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
								($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png")) {
									//copio el archivo
									if (copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."fotos/".$nombre_archivo))) {
										//si no hubo inconvenientes actualiza el nombre del archivo
										$conexion->Query("UPDATE usuarios SET foto = '".$nombre_archivo."' WHERE (id_usuario = '".$id_registro."')");
										//obtengo la ubicacion de la imagen original y small
										$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$nombre_archivo;
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$id_archivo_usuario."_small.".$extension;
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
					}

					if ($_POST['tipo'] == "cambiaremail") {
						$conexion->Query("UPDATE usuarios SET email = '".trim($_POST['email'])."' WHERE (id_usuario = '".$_POST['registro'][0]."')");
						$_SESSION['usuario_email'] = trim($_POST['email']);
					}

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
					echo("</script>");
				}

				//	Cambiar estado
				if (isset($_GET['accion']) && ($_GET['accion'] == "cambiarestado")) {
					if (isset($_POST['registro'])) {
						foreach ($_POST['registro'] as $valor) {
							//verifico el estado que posee
							$datos = $temp->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
							if ($datos) {
								if ($datos["ACTIVO"] == 0) {
									$conexion->Query("UPDATE usuarios SET activo = 1 WHERE (id_usuario = '".$valor."')");
								} else {
									if ($datos["ACTIVO"] == 1) {
										$conexion->Query("UPDATE usuarios SET activo = 0 WHERE (id_usuario = '".$valor."')");
									} else {
										$conexion->Query("UPDATE usuarios SET activo = 1 WHERE (id_usuario = '".$valor."')");
									}
								}
								$datos = $temp->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
								if ($datos) {
									echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
								} else {
									echo("{ \"estado\" : \"error\" }");
								}
							}
						}
					}
				}

				//	Nueva Venta para un Cliente
				if (isset($_GET['accion']) && ($_GET['accion'] == "nuevaventa")) {
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Cargando registro...</h5>");

					$url_destino = "ventas.php?menu=ventas&accion=nuevo";
					if (isset($_GET['anio'])) {
						$url_destino .= "&anio=".$_GET['anio'];
					}

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$url_destino."&registro[]=".$_GET['registro'][0]."';");
					echo("</script>");
				}

				//	Nuevo Servicio Tecnico para un Cliente
				if (isset($_GET['accion']) && ($_GET['accion'] == "nuevoservicio")) {
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Cargando registro...</h5>");

					$url_destino = "ventas.php?menu=servicios&accion=nuevo";
					if (isset($_GET['anio'])) {
						$url_destino .= "&anio=".$_GET['anio'];
					}

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$url_destino."&registro[]=".$_GET['registro'][0]."';");
					echo("</script>");
				}

				//	Nuevo Pedido para un Cliente
				if (isset($_GET['accion']) && ($_GET['accion'] == "nuevopedido")) {
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Cargando registro...</h5>");

					$url_destino = "ventas.php?menu=pedidos&accion=nuevo";
					if (isset($_GET['anio'])) {
						$url_destino .= "&anio=".$_GET['anio'];
					}

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$url_destino."&registro[]=".$_GET['registro'][0]."';");
					echo("</script>");
				}

				//	Generar deuda
				if (isset($_GET['accion']) && ($_GET['accion'] == "generardeudas")) {
					if (!isset($_GET['anio'])) {
						$_GET['anio'] = date("Y");
					}
					$datos = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['registro'][0]."')");
					$datosCuotas = $temp->GetRow("SELECT
							IF(max(NRO_CUOTA) > 0, (max(NRO_CUOTA) + 1), 1) NRO_CUOTA,
							concat(
								year(
									IFNULL(
										max(FECHA_VENCIMIENTO),
										IFNULL(
											(
												select
													fecha_inicio_servicio
												from
													usuarios
												where
													(id_usuario = '".$datos["ID_USUARIO"]."')
											),
											curdate()
										)
									)
								),
								'-',
								concat(
									repeat(
										'0',
										2 - length(
											month(
												IFNULL(
													max(FECHA_VENCIMIENTO),
													IFNULL(
														(
															select
																fecha_inicio_servicio
															from
																usuarios
															where
																(id_usuario = '".$datos["ID_USUARIO"]."')
														),
														curdate()
													)
												)
											)
										)
									),
									month(
										IFNULL(
											max(FECHA_VENCIMIENTO),
											IFNULL(
												(
													select
														fecha_inicio_servicio
													from
														usuarios
													where
														(id_usuario = '".$datos["ID_USUARIO"]."')
												),
												curdate()
											)
										)
									)
								),
								'-',
								concat(
									repeat(
										'0',
										2 - length(
											day(
												IFNULL(
													max(FECHA_ALTA),
													IFNULL(
														(
															select
																fecha_inicio_servicio
															from
																usuarios
															where
																(id_usuario = '".$datos["ID_USUARIO"]."')
														),
														curdate()
													)
												)
											)
										)
									),
									day(
										IFNULL(
											max(FECHA_ALTA),
											IFNULL(
												(
													select
														fecha_inicio_servicio
													from
														usuarios
													where
														(id_usuario = '".$datos["ID_USUARIO"]."')
												),
												curdate()
											)
										)
									)
								)
							) FECHA_ALTA
						FROM ventas
						WHERE
							(id_sucursal = '".$_SESSION['usuario_sucursal']."')
							and (ID_CLIENTE = '".$datos["ID_USUARIO"]."')
							and (TIPO_VENTA = 'ventas')
							and (ELIMINADO = 0)
					");
					?>
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos de la deuda</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="btn-toolbar pull-right">
									<?php
									$url = $_SERVER['PHP_SELF']."?menu=clientes";
									if (isset($_GET['anio'])) {
										$url .= "&anio=".$_GET['anio'];
									}
									?>
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
								</div>
								<h1>Cliente: <?php echo("[".sprintf("%04s", $datos["COD_USUARIO"])."] &quot;".$datos["NOMBRE_COMPLETO"]."&quot; "); ?></h1>

								<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insertdeuda"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
									<input type="hidden" name="id_cliente" value="<?php echo($datos["ID_USUARIO"]); ?>" />

									<div class="row-fluid">
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Fecha de Inicio del Servicio</label>
												<div class="controls">
													<input type="date" name="fecha_alta" value="<?php echo($datosCuotas["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Inicio del Servicio" size="12" maxlength="10" required />
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Cantidad de cuotas</label>
												<div class="controls">
													<input type="number" name="cuotas" value="1" min="1" maxlength="255" title="Cantidad de cuotas" class="input-small" style="text-align: center;" placeholder="0" required />
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Nro. de Cuota Inicial</label>
												<div class="controls">
													<input type="number" name="nro_cuota" value="<?php echo($datosCuotas["NRO_CUOTA"]); ?>" min="<?php echo($datosCuotas["NRO_CUOTA"]); ?>" maxlength="255" title="Nro. de Cuota Inicial" class="input-small" style="text-align: center;" placeholder="0" required />
												</div>
											</div>
										</div>
									</div>


									<h2>Seleccionar Producto</h2>
									<div class="well">
										<?php
											$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_USUARIO"];
										?>
										<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar por Tipo" style="width: auto;">
											<option value="<?php echo($url); ?>" <?php if(!isset($_GET['sm'])) { echo("selected='selected'"); } ?>>Todos los tipos</option>
											<option value=""></option>
											<?php
												$tiposProductos = $temp->Query("SELECT TIPO_PRODUCTO FROM productos
													WHERE
														(id_sucursal = '".$_SESSION['usuario_sucursal']."')
														and (eliminado = 0)
														and (activo = 1)
														and (tipo_producto is not null)
														and (tipo_producto <> '')
													GROUP BY tipo_producto
													ORDER BY tipo_producto");

												if($tiposProductos and ($tiposProductos->RecordCount() > 0)){
													while(!$tiposProductos->EOF) {
														?>
											<option value="<?php  echo($url."&t=".$tiposProductos->fields["TIPO_PRODUCTO"]); ?>" <?php if(isset($_GET['t']) and ($_GET['t'] == $tiposProductos->fields["TIPO_PRODUCTO"])) { echo("selected=\"selected\""); } ?>><?php echo(stripslashes($tiposProductos->fields["TIPO_PRODUCTO"])); ?></option>
														<?php
														$tiposProductos->MoveNext();
													}
												}
											?>
										</select>
										-
										<?php
											$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_USUARIO"];
											if(isset($_GET['t'])) { $url .= "&t=".$_GET['t']; }
										?>
										<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
											<option value="<?php echo($url); ?>" <?php if(!isset($_GET['c'])) { echo("selected='selected'"); } ?>>Todas las Categorias</option>
											<option value=""></option>
											<?php
												$query = "SELECT CATEGORIA FROM productos
													WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (eliminado = 0) and (activo = 1) and (categoria is not null) and (categoria <> '') ";
												if(isset($_GET['t']) and ($_GET['t'] != "")){
													$query .= " and (tipo_producto = '".$_GET['t']."') ";
												}
												$query .= "GROUP BY categoria
													ORDER BY categoria";
												$categoriasProductos = $temp->Query($query);
												if($categoriasProductos and ($categoriasProductos->RecordCount() > 0)){
													while(!$categoriasProductos->EOF) {
														?>
											<option value="<?php  echo($url."&c=".$categoriasProductos->fields["CATEGORIA"]); ?>" <?php if(isset($_GET['c']) and ($_GET['c'] == $categoriasProductos->fields["CATEGORIA"])) { echo("selected=\"selected\""); } ?>><?php echo(stripslashes($categoriasProductos->fields["CATEGORIA"])); ?></option>
														<?php
														$categoriasProductos->MoveNext();
													}
												}
											?>
										</select>
										-
										<?php
											$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_USUARIO"];
											if(isset($_GET['t'])) { $url .= "&t=".$_GET['t']; }
											if(isset($_GET['c'])) { $url .= "&c=".$_GET['c']; }
										?>
										<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
											<option value="<?php echo($url); ?>" <?php if(!isset($_GET['g'])) { echo("selected='selected'"); } ?>>Todos los G&eacute;neros</option>
											<option value=""></option>
											<?php
												$query = "SELECT GENERO FROM productos
													WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (eliminado = 0) and (activo = 1) and (genero is not null) and (genero <> '') ";
												if(isset($_GET['t']) and ($_GET['t'] != "")){
													$query .= " and (tipo_producto = '".$_GET['t']."') ";
												}
												if(isset($_GET['c']) and ($_GET['c'] != "")){
													$query .= " and (categoria = '".$_GET['c']."') ";
												}
												$query .= "GROUP BY genero
													ORDER BY genero";

												$generosProductos = $temp->Query($query);
												if($generosProductos and ($generosProductos->RecordCount() > 0)){
													while(!$generosProductos->EOF) {
														?>
											<option value="<?php  echo($url."&g=".$generosProductos->fields["GENERO"]); ?>" <?php if(isset($_GET['g']) and ($_GET['g'] == $generosProductos->fields["GENERO"])) { echo("selected=\"selected\""); } ?>><?php echo(stripslashes($generosProductos->fields["GENERO"])); ?></option>
														<?php
														$generosProductos->MoveNext();
													}
												}
											?>
										</select>
									</div>

									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th width="20"></th>
												<th>C&oacute;digo</th>
												<th>Detalle</th>
												<th>Cantidad</th>
												<th>Precio</th>
												<th>Descuento</th>
												<th width="150">Monto</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$query = "SELECT * FROM productos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (eliminado = 0) and (activo = 1) ";
												if(isset($_GET['t']) and ($_GET['t'] != "")) {
													$query .= " and (tipo_producto = '".$_GET['t']."') ";
												}
												if(isset($_GET['c']) and ($_GET['c'] != "")) {
													$query .= " and (categoria = '".$_GET['c']."') ";
												}
												if(isset($_GET['g']) and ($_GET['g'] != "")) {
													$query .= "and (genero = '".$_GET['g']."') ";
												}
												$query .= "ORDER BY codigo";

												$detalles = $temp->Query($query);
												if($detalles) {
													while(!$detalles->EOF) {
														?>
											<tr class="unChecked">
												<td>
													<input type="radio" name="detalle" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" />
												</td>
												<td><?php echo(stripslashes($detalles->fields["CODIGO"])); ?></td>
												<td><?php echo(stripslashes($detalles->fields["NOMBRE"])); ?></td>
												<td style="text-align: right;"><?php echo(1); ?></td>
												<td style="text-align: right;"><?php echo(number_format($detalles->fields["PRECIO_VENTA"], $cantidad_decimales, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo(number_format(0, 2, ',', '.')); ?></td>
												<td style="text-align: right;padding-right: 10px;"><?php echo(number_format($detalles->fields["PRECIO_VENTA"], $cantidad_decimales, ',', '.')); ?></td>
											</tr>
														<?php
														$detalles->MoveNext();
													}
												}
											?>
										</tbody>
									</table>

									<div class="form-actions">
										<input type="submit" class="btn btn-large btn-primary" value="Generar deudas" onclick="return confirm('Se va a agregar nuevas Ventas, pendientes de pago, automáticamente, a partir de los datos ingresados.\nEsta Ud. seguro?');" />
										<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
									</div>

								</form>

							</div>
						</div>
					</div>
					<?php
				}

				// registra las deudas generadas
				if (isset($_GET['accion']) && ($_GET['accion'] == "insertdeuda")) {
					?>
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Deudas generadas</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="btn-toolbar pull-right">
									<?php
									$url = $_SERVER['PHP_SELF']."?menu=clientes";
									if (isset($_GET['anio'])) {
										$url .= "&anio=".$_GET['anio'];
									}
									?>
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
								</div>
								<h1>Deudas generadas</h1>
								<?php
								if(isset($_POST['detalle'])){
									$datosCliente = $temp->GetRow("SELECT COD_USUARIO,NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$_POST['id_cliente']."')");
									$datosProducto = $temp->GetRow("SELECT CODIGO,NOMBRE,PRECIO_COSTO,PRECIO_VENTA FROM productos WHERE (id_producto = '".$_POST['detalle']."')");

									// genero las fechas
									list($ano, $mes, $dia) = explode("-", $_POST['fecha_alta']);

									for($i=0;$i<$_POST['cuotas'];$i++){
										$nday = $dia;
										$bi = date('L',strtotime($ano.'-01-01')); // verifica si es un año bisiesto
										if(($dia >= 29) and ($dia <= 31) and ($mes == 2)){
											if ($bi == 1) {
												$nday = 29;
											} else {
												$nday = 28;
											}
										} else {
											if($dia == 31){
												$nday = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
											}
										}

										$time = mktime(0, 0, 0, $mes, $nday, $ano);
										$fecha = strftime('%Y-%m-%d' , $time);

										// fecha de vencimiento
										$fecha_vencimiento = sumarDias(30, $fecha);

										// obtengo el nuevo codigo de venta
										$nuevoRegistro = $conexion->GetRow("SELECT IF(COD_VENTA > 0, (max(COD_VENTA) + 1), 1) as COD_VENTA
											FROM ventas
											WHERE
												(id_sucursal = '".$_SESSION['usuario_sucursal']."')
												and (tipo_venta = 'ventas')
											ORDER BY cod_venta DESC
										");
										//$cuota = 'Cuota #'.($i+1);
										$cuota = 'Cuota #'.($_POST['nro_cuota']+$i);
										echo($cuota.'<br />');
										// insert la venta
										$conexion->Query("INSERT INTO ventas
										(
											ID_VENTA,
											ID_SUCURSAL,
											ID_CLIENTE,
											ID_USUARIO,
											COD_VENTA,
											FECHA_ALTA,
											HORA_ALTA,
											TIPO_VENTA,
											TIPO_CAMBIO,
											CREDITOS_VENTA,
											GRATIS_VENTA,
											IMPORTE,
											IVA,
											SUBTOTAL,
											DESCUENTO,
											TOTAL,
											RECIBIDO,
											SALDO,
											PAGADO,
											IMPRESO,
											DESCRIPCION,
											ESTADO,
											ID_ACTUALIZACION,
											NRO_CUOTA,
											FECHA_VENCIMIENTO
										) VALUES(
											null,
											'".$_SESSION['usuario_sucursal']."',
											'".$_POST['id_cliente']."',
											'".$_SESSION['usuario_id']."',
											'".$nuevoRegistro['COD_VENTA']."',
											'".$fecha."',
											'".date('H:i').":00',
											'ventas',
											'0',
											'0',
											'0',
											'".$datosProducto['PRECIO_VENTA']."',
											'0',
											'".$datosProducto['PRECIO_VENTA']."',
											'0',
											'".$datosProducto['PRECIO_VENTA']."',
											'0',
											'".$datosProducto['PRECIO_VENTA']."',
											'0',
											'0',
											'".$cuota."',
											'Entregado',
											'".$_SESSION['usuario_id']."',
											'".($_POST['nro_cuota']+$i)."',
											'".$fecha_vencimiento."'
										)");

										$id_venta = $conexion->Insert_ID();

										// inserto el detalle
										if($id_venta > 0){
											$conexion->Query("INSERT INTO venta_posee_productos
												(
													ID_VENTA,
													ID_PRODUCTO,
													CODIGO,
													DETALLE,
													PRECIO_COSTO,
													PRECIO_VENTA,
													CANTIDAD,
													DESCUENTO,
													TOTAL
												) VALUES(
													'".$id_venta."',
													'".$_POST['detalle']."',
													'".$datosProducto['CODIGO']."',
													'".$datosProducto['NOMBRE']."',
													'".$datosProducto['PRECIO_COSTO']."',
													'".$datosProducto['PRECIO_VENTA']."',
													'1',
													'0',
													'".$datosProducto['PRECIO_VENTA']."'
												)");

											//	disminuyo el STOCK
											//$temp->Query("UPDATE productos
											//	SET
											//		stock = stock - (1)
											//	WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (id_producto = '".$_POST['detalle']."')");
										}


										$mes += 1;
										if($mes > 12){
											$mes = 1;
											$ano++;
										}
									}

									?>
								<p>Se han generado de forma automática <strong><?php echo($_POST['cuotas']); ?></strong> VENTAS, para el Cliente <strong><?php echo("[".sprintf("%04s", $datosCliente['COD_USUARIO'])."] ".$datosCliente['NOMBRE_COMPLETO']); ?></strong>, con el producto <strong><?php echo($datosProducto['NOMBRE']) ?></strong>, cuyo precio de venta es: <strong><?php echo($datosProducto['PRECIO_VENTA']); ?></strong>.</p>

								<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF'].'?menu=clientes&accion=generardeudas&registro[]='.$_POST['id_cliente']); ?>';"><i class="icon-refresh icon-white"></i> Volver a intentar</button>
								<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF'].'?menu=clientes&accion=ventasregistradas&registro[]='.$_POST['id_cliente']); ?>';">Ventas registradas</button>
									<?php

								} else {
									?>
									<div class="alert">
										<strong>Atención!</strong> No ha seleccionado un producto, para obtener su precio de venta, y agregar como monto en la Venta.
									</div>
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF'].'?menu=clientes&accion=generardeudas&registro[]='.$_POST['id_cliente']); ?>';"><i class="icon-refresh icon-white"></i> Volver a intentar</button>
									<?php
								}
								?>

							</div>
						</div>
					</div>
					<?php
				}

				//	Ventas registradas
				if (isset($_GET['accion']) && ($_GET['accion'] == "ventasregistradas")) {
					if (!isset($_GET['anio'])) {
						$_GET['anio'] = date("Y");
					}
					$datos = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['registro'][0]."')");
					?>
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=ver&registro[]=".$datos["ID_USUARIO"]); ?>">Datos del Cliente</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Ventas registradas</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=serviciosregistrados&registro[]=".$datos["ID_USUARIO"]); ?>">Servicios T&eacute;cnicos registrados</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="btn-toolbar pull-right">
									<?php
									$url = $_SERVER['PHP_SELF']."?menu=clientes";
									if (isset($_GET['anio'])) {
										$url .= "&anio=".$_GET['anio'];
									}
									?>
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
								</div>
								<h1>Ventas registradas</h1>
								<h2>Cliente: <?php echo("[".sprintf("%04s", $datos["COD_USUARIO"])."] &quot;".$datos["NOMBRE_COMPLETO"]."&quot; "); ?></h2>

								<table id="listado" class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th width="70"></th>
											<th>N&deg; Venta</th>
											<th>N&deg; Cuota</th>
											<th>Fecha de Pago</th>
											<th>Vencimiento</th>
											<th>N&deg; Factura</th>
											<th>Usuario Vendedor</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
											<th>Pagado</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$query = "SELECT
													COD_VENTA,NRO_CUOTA,ventas.FECHA_ALTA,ventas.FECHA_VENCIMIENTO,NRO_FACTURA,NOMBRE_COMPLETO,TOTAL,RECIBIDO,SALDO,PAGADO,ID_VENTA
												FROM ventas
												LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_usuario
												WHERE
													(tipo_venta = 'ventas')
													and (ventas.id_sucursal = '".$_SESSION['usuario_sucursal']."')
													and (id_cliente = '".$datos["ID_USUARIO"]."')
													and (eliminado = 0)
											";
											if (isset($_GET['texto'])) {
												$query .= " and ((id_venta LIKE '%".$_GET['texto']."%') or (nombre_completo LIKE '%".$_GET['texto']."%')) ";
											}
											$query .= "ORDER BY ventas.fecha_alta DESC,cod_venta DESC";

											$registros = $temp->query($query);
											while(!$registros->EOF){

												$style_colores = "";

												if(isset($colores_pagadoVentas)) {
													for($ic=0; $ic<count($colores_pagadoVentas); $ic++) {
														$array_datos = explode("|",$colores_pagadoVentas[$ic]);
														if(strtoupper($array_datos[0]) == strtoupper($estados[$registros->fields["PAGADO"]])) {
															$style_colores .= "background: ".$array_datos[1].";";
														}
													}
												}

												?>
										<tr>
											<td>
												<div class="btn-group btn-group-sm">
													<?php if(($datos_acceso and ($datos_acceso["VER"] == 1))) { ?><button id="btnVerRegistro" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo('ventas.php?menu=ventas&accion=ver&registro[]='.$registros->fields['ID_VENTA']); ?>';">Ver</button><?php } ?>
													<?php if(($datos_acceso and ($datos_acceso["MODIFICAR"] == 1))) { ?><button id="btnEditarRegistro" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo('ventas.php?menu=ventas&accion=editar&registro[]='.$registros->fields['ID_VENTA']); ?>';">Editar</button><?php } ?>
												</div>
											</td>
											<td style="text-align: center;"><?php echo(sprintf("%04s", $registros->fields["COD_VENTA"])); ?></td>
											<td style="text-align: center;"><?php echo($registros->fields["NRO_CUOTA"]); ?></td>
											<td style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
											<td><?php echo($registros->fields["NRO_FACTURA"]); ?></td>
											<td><?php echo($registros->fields["NOMBRE_COMPLETO"]); ?></td>
											<td style="text-align: right;"><?php echo(number_format($registros->fields["TOTAL"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo(number_format($registros->fields["RECIBIDO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo(number_format($registros->fields["SALDO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: center;<?php echo($style_colores); ?>"><?php echo($estados[$registros->fields["PAGADO"]]); ?></td>
										</tr>
												<?php
												$registros->MoveNext();
											}
										?>
									</tbody>
								</table>

							</div>
						</div>
					</div>
					<?php
				}

				//	Servicios Tecnicos registradas
				if (isset($_GET['accion']) && ($_GET['accion'] == "serviciosregistrados")) {
					if (!isset($_GET['anio'])) {
						$_GET['anio'] = date("Y");
					}
					$datos = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$_GET['registro'][0]."')");
					?>
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=ver&registro[]=".$datos["ID_USUARIO"]); ?>">Datos del Cliente</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=clientes&accion=ventasregistradas&registro[]=".$datos["ID_USUARIO"]); ?>">Ventas registradas</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Servicios T&eacute;cnicos registrados</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="btn-toolbar pull-right">
									<?php
									$url = $_SERVER['PHP_SELF']."?menu=clientes";
									if (isset($_GET['anio'])) {
										$url .= "&anio=".$_GET['anio'];
									}
									?>
									<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
								</div>
								<h1>Servicios T&eacute;cnicos registrados</h1>
								<h2>Cliente: <?php echo("[".sprintf("%06s", $datos["ID_USUARIO"])."] &quot;".$datos["NOMBRE_COMPLETO"]."&quot; "); ?></h2>
								<?php
									$query = "SELECT COD_VENTA,ventas.FECHA_ALTA,NRO_FACTURA,NOMBRE_COMPLETO,TOTAL,RECIBIDO,SALDO,PAGADO,ID_VENTA FROM ventas LEFT JOIN usuarios ON usuarios.ID_USUARIO = ventas.ID_USUARIO WHERE (tipo_venta = 'servicios') and (ventas.id_sucursal = '".$_SESSION['usuario_sucursal']."') and (id_cliente = '".$datos["ID_USUARIO"]."') ";
									if (isset($_GET['texto'])) {
										$query .= " and ((id_venta LIKE '%".$_GET['texto']."%') or (nombre_completo LIKE '%".$_GET['texto']."%')) ";
									}
									$query .= "ORDER BY ventas.FECHA_ALTA DESC";

									showQuery(
										$conexion,
										$query,
										array(
											"COD_VENTA"=>"N&deg;",
											"FECHA_ALTA"=>"Fecha",
											"NRO_FACTURA"=>"N&deg; Factura",
											"NOMBRE_COMPLETO"=>"Usuario Vendedor",
											"TOTAL"=>"Total",
											"RECIBIDO"=>"Recibido",
											"SALDO"=>"Saldo",
											"PAGADO"=>"Pagado"),
										$_SESSION['usuario_rxp']
									);
								?>
							</div>
						</div>
					</div>
					<?php
				}

				//	Exportar Clientes
				if (isset($_GET['accion']) && ($_GET['accion'] == "exportarClientes")) {
					$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
					if (!isset($_GET['tc'])) { $_GET['tc'] = "cod_usuario"; }
					if (!isset($_GET['to'])) { $_GET['to'] = "asc"; }
					$datosSucursal = $temp->Query("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<div class="btn-toolbar pull-right" style="margin-top: -3px;">
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
					</div>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Opciones para exportar los CLIENTES</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="well">
									<input type="hidden" id="s" name="s" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
									<input type="hidden" id="ns" name="ns" value="<?php if ($datosSucursal) { echo($datosSucursal->fields["NOMBRE"]); } ?>" />
									<?php $url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']; ?>
									<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
										<option value="<?php echo($url); ?>" <?php if (!isset($_GET['c'])) { echo("selected='selected'"); } ?>>Todas las Categorias</option>
										<?php
											$registroCategorias = $temp->Query("SELECT CATEGORIA_USUARIO FROM usuarios
												WHERE
													(activo = 1) and
													(categoria_usuario is not null) and
													(categoria_usuario <> '') and
													(tipo_usuario = 'Cliente') and
													(id_sucursal = '".$_SESSION['usuario_sucursal']."')
												GROUP BY categoria_usuario
												ORDER BY categoria_usuario
											");

											if ($registroCategorias and ($registroCategorias->RecordCount() > 0)) {
												while (!$registroCategorias->EOF) {
													?>
													<option value="<?php  echo($url."&c=".$registroCategorias->fields["CATEGORIA_USUARIO"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $registroCategorias->fields["CATEGORIA_USUARIO"])) { echo("selected=\"selected\""); } ?>>
														<?php echo(stripslashes($registroCategorias->fields["CATEGORIA_USUARIO"])); ?>
													</option>
													<?php
													$registroCategorias->MoveNext();
												}
											} 
										?>
									</select>
									-
									<div class="btn-group" style="margin-top: -10px;">
										<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="javascript:void(0);" id="btExportarDatos2" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
											<li><a href="javascript:void(0);" id="btExportarDatos2" data-tipo="xlsx">Microsoft Excel (*.xlsx)</a></li>
										</ul>
									</div>
									<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
								</div>

								<table id="listado" class="table table-striped table-bordered table-condensed">
									<thead>
										<tr>
											<?php
												$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
												$url .= "&s=".$_SESSION['usuario_sucursal'];
												if (isset($_GET['c'])) {
													$url .= "&c=".$_GET['c'];
												}

												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "cod_usuario")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th width="80" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=cod_usuario&to=".$to); ?>">C&oacute;digo</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "tipo_doc")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=tipo_doc&to=".$to); ?>">Tipo de Documento</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "nro_doc")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=nro_doc&to=".$to); ?>">N° de Documento</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=nombre&to=".$to); ?>">Nombre</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "apellidos")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=apellidos&to=".$to); ?>">Apellidos</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "email")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=email&to=".$to); ?>">Correo electrónico</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "telefono")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th width="110" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=telefono&to=".$to); ?>">Teléfono Fijo</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "telefono_movil")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th width="110" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=telefono_movil&to=".$to); ?>">Teléfono Móvil</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "ciudad")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th width="110" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=ciudad&to=".$to); ?>">Ciudad</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "categoria_usuario")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=categoria_usuario&to=".$to); ?>">Categor&iacute;a</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "fecha_inicio_servicio")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=fecha_inicio_servicio&to=".$to); ?>">Inicio del Servicio</a></th>
											<?php
												$estilo = "";
												$to = "asc";
												if (isset($_GET['tc']) and ($_GET['tc'] == "fecha_alta")) {
													if ($_GET['to'] == "asc") {
														$to = "desc";
													} else {
														if ($_GET['to'] == "desc") {
															$to = "asc";
														}
													}
													$estilo = $to;
												}
											?>
											<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=fecha_alta&to=".$to); ?>">F. Alta</a></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$query = "SELECT
												COD_USUARIO,
												TIPO_DOC,
												NRO_DOC,
												NOMBRE,APELLIDOS,
												EMAIL,TELEFONO,
												TELEFONO_MOVIL,
												CIUDAD,
												CATEGORIA_USUARIO,
												FECHA_INICIO_SERVICIO,
												FECHA_ALTA
											FROM usuarios
											WHERE
												(tipo_usuario = 'Cliente') and
												(id_sucursal = '".$_SESSION['usuario_sucursal']."')
											";
											if (isset($_GET['c']) and ($_GET['c'] != "")) {
												$query .= " and (upper(categoria_usuario) = '".$_GET['c']."') ";
											}
											$query .= " ORDER BY ".$_GET['tc']." ".$_GET['to'];
											$registros = $conexion->Query($query);

											if ($registros and ($registros->RecordCount() > 0)) {
												while (!$registros->EOF) {
													?>
													<tr>
														<td><?php echo($registros->fields["COD_USUARIO"]); ?></td>
														<td><?php echo($registros->fields["TIPO_DOC"]); ?></td>
														<td><?php echo($registros->fields["NRO_DOC"]); ?></td>
														<td><?php echo(str_replace("\"", "'", stripslashes($registros->fields["NOMBRE"]))); ?></td>
														<td><?php echo(str_replace("\"", "'", stripslashes($registros->fields["APELLIDOS"]))); ?></td>
														<td><?php echo($registros->fields["EMAIL"]); ?></td>
														<td><?php echo($registros->fields["TELEFONO"]); ?></td>
														<td><?php echo($registros->fields["TELEFONO_MOVIL"]); ?></td>
														<td><?php echo($registros->fields["CIUDAD"]); ?></td>
														<td><?php echo($registros->fields["CATEGORIA_USUARIO"]); ?></td>
														<td><?php echo($registros->fields["FECHA_INICIO_SERVICIO"] == '' ? '' : $registros->fields["FECHA_INICIO_SERVICIO"] ); ?></td>
														<td><?php echo($registros->fields["FECHA_ALTA"] == '' ? '' : $registros->fields["FECHA_ALTA"] ); ?></td>
													</tr>
													<?php
													$registros->MoveNext();
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>

					</div>
					<?php
				}

				//	Importar Clientes
				if (isset($_GET['accion']) && ($_GET['accion'] == "importarClientes")) {
					$sucursales = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY nombre");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insertupdatesclientes"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

						<div class="btn-toolbar pull-right" style="margin-top: -3px;">
							<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
						</div>

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Opciones para importar los CLIENTES</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active" style="min-height: 100px;">
									<div class="control-group">
										<label class="control-label">Sucursal</label>
										<div class="controls">
											<input type="text" value="<?php echo(stripslashes($sucursales["NOMBRE"])); ?>" readonly="readonly" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Archivo MS Excel</label>
										<div class="controls">
											<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
											<input type="file" id="importar_archivo" name="importar_archivo" title="Archivo MS Excel" accept=".xls,.xlsx" required />
											<div class="alert alert-info" role="alert" style="margin-top: 10px;">
												<p>Asegúrese de que el archivo contenga las siguientes columnas en el mismo orden:
													<strong>COD_USUARIO</strong>, <strong>TIPO_DOC</strong>, <strong>NRO_DOC</strong>,
													<strong>NOMBRE</strong>, <strong>APELLIDOS</strong>, <strong>EMAIL</strong>,
													<strong>TELEFONO</strong>, <strong>TELEFONO_MOVIL</strong>, <strong>CIUDAD</strong>, 
													<strong>CATEGORIA_USUARIO</strong>, <strong>FECHA_INICIO_SERVICIO</strong> y 
													<strong>FECHA_ALTA</strong>.</p>
											</div>
										</div>
									</div>

								</div>
							</div>

							<div class="form-actions">
								<input type="submit" class="btn btn-large btn-primary" value="Importa Cliente" onclick="return confirm('Se van ha importar los datos de los CLIENTES del archivo seleccionado.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
							</div>
						</div>
					</form>
					<?php
				}

				//	Insertar archivos con Clientes
				if (isset($_GET['accion']) && ($_GET['accion'] == "insertupdatesclientes")) {
					?>
					<div class="btn-toolbar pull-right" style="margin-top: -3px;">
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
					</div>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos importados en CLIENTES</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<?php
									//echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registros...</h5>");

									//	verifico el archivo subido
									if (isset($_FILES["importar_archivo"])) {
										//	verifico si se subio el archivo
										if (is_uploaded_file($_FILES['importar_archivo']['tmp_name'])) {
											//obtiene el nombre del archivo y su extension
											$res = explode(".", $_FILES['importar_archivo']['name']);
											$tipo_archivo = $_FILES['importar_archivo']['type'];
											$extension = $res[count($res) - 1];
											$nombre = "s".$_POST['sucursal']."-".date("dmY_His");
											$nombre_completo = $nombre.".".$extension;

											$archivo = "../../".$path_archivos."datos/".$nombre_completo;

											copy($_FILES['importar_archivo']['tmp_name'], stripslashes($archivo));

											//	intenta copiar el archivo cargado a la carpeta CONTACTOS
											if (
												!copy($_FILES['importar_archivo']['tmp_name'], stripslashes($archivo)) || 
												($_FILES['importar_archivo']['size'] > 8388608)
											) {
												?>
											<p>No se ha podido cargar el archivo: <strong><?php echo($_FILES['importar_archivo']['name']); ?></strong> por alguna de las siguientes razones:</p>
											<ul>
												<li>El nombre del archivo posee caracteres especiales.</li>
												<li>El tama&ntilde;o del archivo es superior a 8 Mbyte.</li>
											</ul>
												<?php
											} else {

												require_once __DIR__ . '/../vendor/autoload.php';

												// Cargar el archivo
												$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
			
												// Obtener todas las hojas del archivo
												$hojas = $spreadsheet->getAllSheets();
			
												// Iterar a través de las hojas
												foreach ($hojas as $hoja) {
			
													echo("<p>La hoja de cálculo: \"".$hoja->getTitle()."\".</p>");
													echo('<table class="table table-striped table-bordered table-condensed">');
			
													// Obtener la última columna y fila utilizada en la hoja
													$ultimaColumna = $hoja->getHighestColumn();
													//$ultimaFila = $hoja->getHighestRow();
													$ultimaFila = $hoja->getHighestDataRow('A');
			
													// Iterar a través de las celdas
													for ($fila = 1; $fila <= $ultimaFila; $fila++) {
														echo("<tr>");
														for ($columna = 'A'; $columna <= $ultimaColumna; $columna++) {
			
															// Obtener el valor de la celda actual
															$valorCelda = $hoja->getCell($columna . $fila)->getValue();
			
															if($fila == 1){ 
																echo("<th>".$valorCelda."</th>");
															} else {
																echo("<td>".$valorCelda."</td>");
															}
			
															switch($columna) {
																case 'A': $vCodigo = $valorCelda;break;
																case 'B': $vTipoDocumento = $valorCelda;break;
																case 'C': 
																	$vNroDocumento = !empty($valorCelda) ? $valorCelda : 0;
																	break;
																case 'D': $vNombre = $valorCelda;break;
																case 'E': $vApellidos = $valorCelda;break;
																case 'F': $vEmail = $valorCelda;break;
																case 'G': $vTelefonoFijo = $valorCelda;break;
																case 'H': $vTelefonoMovil = $valorCelda;break;
																case 'I': $vCiudad = $valorCelda;break;
																case 'J': $vCategoria = $valorCelda;break;
																case 'K': $vFechaInicioServicio = $valorCelda;break;
																case 'L': $vFechaAlta = $valorCelda;break;
															}
			
														}
														echo("</tr>");
			
														if($fila > 1){
			
															//echo("<p>$vCodigo|$vNombre|$vCategoria|$vGenero|$vPrecio_Costo(".number_format($vPrecio_Costo, 2, '.', '').")|$vPrecio_Venta|$vStock|</p>");
			
															$nombre_completo = trim(addslashes(trim(strtoupper($vNombre)))." ".addslashes(trim(strtoupper($vApellidos))));

															// verifico si existe el USUARIO por su codigo
															$query = "SELECT ID_USUARIO 
																FROM usuarios  
																WHERE 
																	(cod_usuario = '{$vCodigo}') 
																	and (tipo_usuario = 'Cliente') 
																	and (id_sucursal = '{$_POST['sucursal']}')
															";
															$registros = $conexion->Query($query);
			
															if($registros->RecordCount() > 0) {
																// existe el registro
																while(!$registros->EOF) {
																	$fechaInicioServicio_set = !empty($vFechaInicioServicio) ? "fecha_inicio_servicio = '{$vFechaInicioServicio}', " : "fecha_inicio_servicio = '".date("Y-m-d")."', ";
																	$fechaAlta_set = !empty($vFechaAlta) ? "fecha_alta = '{$vFechaAlta}', " : "fecha_alta = '".date("Y-m-d")."', ";
			
																	// actualizo los datos
																	$conexion->Execute("UPDATE usuarios  
																		SET 
																			tipo_doc = '".$vTipoDocumento."',
																			nro_doc = '".$vNroDocumento."',
																			nombre = '".strtoupper(addslashes($vNombre))."',
																			apellidos = '".strtoupper(addslashes($vApellidos))."',
																			nombre_completo = '".$nombre_completo."',
																			telefono = '".strtoupper($vTelefonoFijo)."',
																			telefono_movil = '".strtoupper($vTelefonoMovil)."',
																			ciudad = '".strtoupper($vCiudad)."',
																			email = '".strtolower($vEmail)."',
																			{$fechaInicioServicio_set}
																			{$fechaAlta_set}
																			categoria_usuario = '".strtoupper($vCategoria)."'
																		WHERE 
																			(id_usuario = '".$registros->fields["ID_USUARIO"]."')
																	");
			
																	$registros->MoveNext();
																}
															} else {
																$fechaInicioServicio_set = !empty($vFechaInicioServicio) ? $vFechaInicioServicio : date("Y-m-d");
																$fechaAlta_set = !empty($vFechaAlta) ? $vFechaAlta : date("Y-m-d");

																// insertar el nuevo registro
																$conexion->Execute("INSERT INTO usuarios
																	(
																		ID_USUARIO,
																		COD_USUARIO,
																		ID_SUCURSAL,
																		TIPO_DOC,
																		NRO_DOC,
																		NOMBRE,
																		APELLIDOS,
																		NOMBRE_COMPLETO,
																		TELEFONO,
																		TELEFONO_MOVIL,
																		CIUDAD,
																		CODIGO_PAIS,
																		EMAIL,
																		TIPO_USUARIO,
																		ACTIVO,
																		CATEGORIA_USUARIO,
																		FECHA_INICIO_SERVICIO,
																		FECHA_ALTA
																	) VALUES(
																		null,
																		'".$vCodigo."',
																		'".$_POST['sucursal']."',
																		'".$vTipoDocumento."',
																		'".trim($vNroDocumento)."',
																		'".strtoupper(addslashes(trim($vNombre)))."',
																		'".strtoupper(addslashes(trim($vApellidos)))."',
																		'".$nombre_completo."',
																		'".strtoupper(trim($vTelefonoFijo))."',
																		'".strtoupper(trim($vTelefonoMovil))."',
																		'".strtoupper(trim($vCiudad))."',
																		'BO',
																		'".strtolower(trim($vEmail))."',
																		'Cliente',
																		'1',
																		'".strtoupper(trim($vCategoria))."',
																		'".$fechaInicioServicio_set."',
																		'".$fechaAlta_set."'
																	)
																");
																$id_registro = $conexion->Insert_ID();
															}
			
														}
															
														$vCodigo = "";
														$vTipoDocumento = "";
														$vNroDocumento = "";
														$vNombre = "";
														$vApellidos = "";
														$vEmail = "";
														$vTelefonoFijo = "";
														$vTelefonoMovil = "";
														$vCiudad = "";
														$vCategoria = "";
														$vFechaInicioServicio = "";
														$vFechaAlta = "";
			
													}
			
													echo "</table>";
			
												}
			
											}
										}
									} 
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
			//	-----	Fin CLIENTES

			include("../footer.php");
		?>
	</body>
</html>