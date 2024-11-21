<?php
	require("../config.php");
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

		//	-----	USUARIOS
		if(isset($_GET['menu']) && ($_GET['menu'] == "usuarios")) {
			//	Listado
			if(!isset($_GET['accion'])) {
				//	obtengo los tipos de usuarios a ver
				$contador = 0;
				$query_usuarios = "(";
				for($i=0; $i<(count($niveles_acceso)-2); $i++) {
					if ($contador++ > 0) { $query_usuarios .= " or "; }
					$query_usuarios .= "(tipo_usuario = '".$niveles_acceso[$i]."')";
				}
				$query_usuarios .= ")";

				if(isset($_GET['texto'])) {
					$criterio_busqueda = "WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and ".$query_usuarios." and ( (id_usuario LIKE '%".$_GET['texto']."%') or (upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or (upper(email) LIKE upper('%".$_GET['texto']."%')) or (upper(codigo_pais) LIKE upper('%".$_GET['texto']."%')) or (upper(tipo_usuario) LIKE upper('%".$_GET['texto']."%')) ) ";
				} else {
					//$criterio_busqueda = "";
					$criterio_busqueda = " WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and ".$query_usuarios;
				}
				showTable2($conexion,
					"SELECT ID_USUARIO,NOMBRE_COMPLETO,EMAIL,CODIGO_PAIS,TIPO_USUARIO,FECHA_ALTA,ACTIVO FROM usuarios ".$criterio_busqueda."ORDER BY nombre_completo",
					array("ID_USUARIO"=>"Id",
						"NOMBRE_COMPLETO"=>"Nombres y Apellidos",
						"EMAIL"=>"Correo Electr&oacute;nico",
						"CODIGO_PAIS"=>"Pa&iacute;s",
						"TIPO_USUARIO"=>"Tipo",
						"FECHA_ALTA"=>"Fecha de Alta",
						"ACTIVO"=>"Activo"),
					array(0=>"ID_USUARIO"),
					$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,null,
					array("privilegios"=>"Privilegios de Acceso",
						"cambiarclave"=>"Cambiar contrase&ntilde;a",
						"cambiarfoto"=>"Actualizar foto",
						"cambiaremail"=>"Actualizar correo electronico",
						"enviardatos"=>"Enviar datos de acceso"));
			}

			//Nuevo
			if(isset($_GET['accion']) && ($_GET['accion'] == "nuevo")) {
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />	

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="background: url(<?php echo("../../".$path_imagenes."fotos/"."fotouser.jpg"); ?>) no-repeat top right;">
								<div class="control-group">
									<label class="control-label">Tipo y Nro. de Documento</label>
									<div class="controls">
										<select id="tipo_doc" name="tipo_doc" class="input-mini" title="Tipo de Documento" >
											<option value=""></option>
											<?php
												for($i=0; $i<count($tipo_documento); $i++) {
													?>
											<option value="<?php echo($tipo_documento[$i]); ?>"><?php echo($tipo_documento[$i]); ?></option>
													<?php
												}
											?>
										</select>
										<input type="text" id="nro_doc" name="nro_doc" class="input-small" maxlength="8" size="10" title="Nro. de Documento" placeholder="0000000" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nombres y Apellidos</label>
									<div class="controls">
										<input type="text" id="nombre" name="nombre" size="25" maxlength="255" title="Nombres" />
										<input type="text" id="apellidos" name="apellidos" size="25" maxlength="255" title="Apellidos" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Fecha de Nacimiento</label>
									<div class="controls">
										<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="" class="input-medium" title="Fecha de Nacimiento" size="12" maxlength="10" />
									</div>
								</div>
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
														$pais = strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 3, strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"])));
														$registros = $temp->Query("SELECT * FROM paises ORDER BY pais");
														while(!$registros->EOF) {									
																?>
													<option value="<?php echo($registros->fields["CODIGO_PAIS"]); ?>" <?php if($registros->fields["CODIGO_PAIS"] == $pais) { echo("selected='selected'"); } ?>><?php echo($registros->fields["PAIS"]); ?></option>
																<?php
															$registros->MoveNext();
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo Electr&oacute;nico</label>
											<div class="controls">
												<input type="email" id="email" name="email" size="50" maxlength="255" title="Correo Electr&oacute;nico" required />
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
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">NIT</label>
											<div class="controls">
												<input type="text" id="nit" name="nit" size="50" maxlength="20" title="NIT" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Profesi&oacute;n</label>
											<div class="controls">
												<input type="text" id="profesion" name="profesion" size="50" maxlength="255" title="Profesi&oacute;n" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nivel Acad&eacute;mico</label>
											<div class="controls">
												<input type="text" id="nivel_academico" name="nivel_academico" size="50" maxlength="255" title="Nivel Acad&eacute;mico" />
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Tipo de Usuario</label>
											<div class="controls">
												<select id="tipo_usuario" name="tipo_usuario" title="Tipo de Usuario" required>
													<option value=""></option>
													<?php
														if(strtoupper($niveles_acceso[$_SESSION['usuario_nivel']]) == "ADMINISTRADOR"){
															$seleccionar = $_SESSION['usuario_nivel'] + 1;
														} else {
															$seleccionar = $_SESSION['usuario_nivel'];
														}
														//solo me permite otorgar niveles igual o superior al del usuario logeado
														for($i = $_SESSION['usuario_nivel']; $i < count($niveles_acceso); $i++) {							
															?>
													<option value="<?php echo($niveles_acceso[$i]); ?>" <?php if($seleccionar == $i) { ?>selected="selected"<?php } ?>><?php echo($niveles_acceso[$i]); ?></option>
															<?php
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Sucursal</label>
											<div class="controls">
												<select name="sucursal" id="sucursal" title="Sucursal" class="form-control" style="width: auto;" required>
													<option value=""></option>
													<?php
														$registrosSedes = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
														while(!$registrosSedes->EOF) {									
															?>
													<option value="<?php echo($registrosSedes->fields["ID_SUCURSAL"]); ?>" <?php if($registrosSedes->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal']) { echo("selected='selected'"); } ?>><?php echo($registrosSedes->fields["NOMBRE"]); ?></option>
															<?php
															$registrosSedes->MoveNext();
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<input type="text" id="usuario" name="usuario" size="50" maxlength="255" title="Usuario" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Contrase&ntilde;a</label>
											<div class="controls">
												<input type="text" id="clave" name="clave" size="50" maxlength="255" title="Clave" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha de Alta</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
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
									<textarea class="ckeditor" id="comentarios" name="comentarios" rows="40" cols="60" title="Comentarios" style="width:100%"></textarea>
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
			if(isset($_GET['accion']) && ($_GET['accion'] == "insert")) {
				$_POST['nro_doc'] = sprintf("%08s",$_POST['nro_doc']); 
				$nombre_completo = addslashes($_POST['nombre'])." ".addslashes($_POST['apellidos']);
				$_POST['fecha_nacimiento'] = $_POST['fecha_nacimiento'] == '' ? '0000-00-00' : $_POST['fecha_nacimiento'];
				
				if(isset($_POST['id_usuario'])) {
					$actualizacion_set = !empty($_POST['id_actualizacion']) ? "id_actualizacion = '".trim($_POST['id_actualizacion'])."', " : '';

					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$query = "UPDATE usuarios 
						SET 
							cod_usuario = '".$_POST['id_usuario']."', 
							tipo_doc = '".$_POST['tipo_doc']."', 
							nro_doc = '".$_POST['nro_doc']."', 
							nit = '".$_POST['nit']."', 
							nombre = '".addslashes($_POST['nombre'])."', 
							apellidos = '".addslashes($_POST['apellidos'])."', 
							nombre_completo = '".$nombre_completo."', 
							fecha_nacimiento = '".$_POST['fecha_nacimiento']."', 
							sexo = '".$_POST['sexo']."', 
							domicilio = '".addslashes($_POST['domicilio'])."', 
							telefono = '".$_POST['telefono_fijo']."', 
							telefono_movil = '".$_POST['telefono_movil']."', 
							ciudad = '".addslashes($_POST['ciudad'])."', 
							codigo_pais = '".$_POST['codigo_pais']."', 
							email = '".$_POST['email']."', 
							profesion = '".addslashes($_POST['profesion'])."', 
							nivel_academico = '".addslashes($_POST['nivel_academico'])."', 
							tipo_usuario = '".$_POST['tipo_usuario']."', 
							usuario = '".trim($_POST['usuario'])."', 
							clave = '".trim($_POST['clave'])."', 
							clave2 = '".md5(trim($_POST['clave']))."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							activo = '".$_POST['activo']."', 
							comentarios = '".addslashes($_POST['comentarios'])."', 
							creditos = '".$_POST['creditos']."', 
							gratis = '".$_POST['gratis']."', 
							{$actualizacion_set}
							id_sucursal = '".$_POST['sucursal']."'
						WHERE (id_usuario = '".$_POST['id_usuario']."')
					";
					$conexion->Query($query);
					$id_registro = $_POST['id_usuario'];
					$id_usuario = sprintf("US%08s", $id_registro);
				} else {
					$actualizacion_set = !empty($_POST['id_actualizacion']) ? $_POST['id_actualizacion'] : 'null';

					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$query = "INSERT INTO usuarios 
						(
							ID_USUARIO,
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
							PROFESION,
							NIVEL_ACADEMICO,
							TIPO_USUARIO,
							USUARIO,
							CLAVE,
							CLAVE2,
							FECHA_ALTA,
							ACTIVO,
							COMENTARIOS,
							CREDITOS,
							GRATIS,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['sucursal']."',
							'".$_POST['tipo_doc']."',
							'".$_POST['nro_doc']."',
							'".trim($_POST['nit'])."',
							'".addslashes($_POST['nombre'])."',
							'".addslashes($_POST['apellidos'])."',
							'".$nombre_completo."',
							'".$_POST['fecha_nacimiento']."',
							'".$_POST['sexo']."',
							'".addslashes($_POST['domicilio'])."',
							'".trim($_POST['telefono_fijo'])."',
							'".trim($_POST['telefono_movil'])."',
							'".addslashes($_POST['ciudad'])."',
							'".$_POST['codigo_pais']."',
							'".trim($_POST['email'])."',
							'".addslashes($_POST['profesion'])."',
							'".addslashes($_POST['nivel_academico'])."',
							'".$_POST['tipo_usuario']."',
							'".trim($_POST['usuario'])."',
							'".trim($_POST['clave'])."',
							md5('".trim($_POST['clave'])."'),
							'".$_POST['fecha_alta']."',
							'".$_POST['activo']."',
							'".addslashes($_POST['comentarios'])."',
							'".$_POST['creditos']."',
							'".$_POST['gratis']."',	
							{$actualizacion_set}						
						)";
					$conexion->Query($query);
					$id_registro = $conexion->Insert_ID();
					$conexion->Query("UPDATE usuarios SET cod_usuario = '".$id_registro."' WHERE (id_usuario = '".$id_registro."')");
					$id_usuario = sprintf("US%08s", $id_registro);
				}

				//	verifico los archivos subidos
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
						($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
							//copio el archivo
							if(copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."fotos/".$nombre_archivo))) {
								//si no hubo inconvenientes actualiza el nombre del archivo
								$conexion->Query("UPDATE usuarios SET foto = '".$nombre_archivo."' WHERE (id_usuario = '".$id_registro."')");
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
				$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					if($registros->fields["FOTO"] == null) {
						$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/"."fotouser.jpg";
						$nombre_completo_imagensmall = null;
					} else {
						$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
						//verifico si el archivo existe
						if(file_exists($nombre_completo_imagenoriginal)) {
						//obtengo el nombre y extension del archivo small
						$res = explode(".", $registros->fields["FOTO"]);
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
					$registros->fields["NRO_DOC"] = sprintf("%08s",$registros->fields["NRO_DOC"]); 
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
						?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_usuario" value="<?php echo($registros->fields["ID_USUARIO"]); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
							<li class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" border="0" /> M&aacute;s opciones <b class="caret"></b></a>
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
							<div id="tab01" class="tab-pane active" style="background: url(<?php if($nombre_completo_imagensmall == null) { echo($nombre_completo_imagenoriginal);  } else { echo($nombre_completo_imagensmall); } ?>) no-repeat top right;">
								<div class="control-group">
									<label class="control-label">Tipo y Nro. de Documento</label>
									<div class="controls">
										<select name="tipo_doc" class="input-mini" title="Tipo de Documento" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
											<option value=""></option>
											<?php
												for($i=0; $i<count($tipo_documento); $i++) {
													?>
											<option value="<?php echo($tipo_documento[$i]); ?>" <?php if($registros->fields["TIPO_DOC"] == $tipo_documento[$i]) { ?>selected="selected"<?php } ?>><?php echo($tipo_documento[$i]); ?></option>
													<?php
												}
											?>
										</select>
										<input type="text" name="nro_doc" maxlength="8" class="input-small" size="10" title="Nro. de Documento" value="<?php echo($registros->fields["NRO_DOC"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nombres y Apellidos</label>
									<div class="controls">
										<input type="text" name="nombre" size="25" maxlength="255" title="Nombres" value="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE"]))); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										<input type="text" name="apellidos" size="25" maxlength="255" title="Apellidos" value="<?php echo(htmlentities(stripslashes($registros->fields["APELLIDOS"]))); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Fecha de Nacimiento</label>
									<div class="controls">
										<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo($registros->fields["FECHA_NACIMIENTO"]); ?>" class="input-medium" title="Fecha de Nacimiento" placeholder="dd/mm/aaaa" size="12" maxlength="10" placeholder="dd/mm/aaaa" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sexo</label>
									<div class="controls">
										<label class="radio inline">
											<input type="radio" name="sexo" value="M" <?php if($registros->fields["SEXO"] == "M") { ?>checked="checked"<?php } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> Masculino
										</label>
										<label class="radio inline">
											<input type="radio" name="sexo" value="F" <?php if($registros->fields["SEXO"] == "F") { ?>checked="checked"<?php } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>Femenino
										</label>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" name="domicilio" size="50" maxlength="255" title="Direcci&oacute;n" value="<?php echo(htmlentities(stripslashes($registros->fields["DOMICILIO"]))); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tel&eacute;fono</label>
											<div class="controls">
												<input type="text" name="telefono_fijo" size="23" maxlength="50" title="Tel&eacute;fono fijo" value="<?php echo($registros->fields["TELEFONO"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">M&oacute;vil</label>
											<div class="controls">
												<input type="text" name="telefono_movil" size="23" maxlength="50" title="Tel&eacute;fono movil" value="<?php echo($registros->fields["TELEFONO_MOVIL"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Ciudad</label>
											<div class="controls">
												<input type="text" name="ciudad" size="50" maxlength="255" title="Ciudad" value="<?php echo(htmlentities(stripslashes($registros->fields["CIUDAD"]))); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Pa&iacute;s</label>
											<div class="controls">
												<select name="codigo_pais" title="Pa&iacute;s" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
													<option value=""></option>
													<?php
														$pais = strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 3, strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"])));
														$datos = $temp->Query("SELECT * FROM paises ORDER BY pais");
														while(!$datos->EOF) {									
																?>
													<option value="<?php echo($datos->fields["CODIGO_PAIS"]); ?>" <?php if($datos->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($datos->fields["PAIS"]); ?></option>
																<?php
															$datos->MoveNext();
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo Electr&oacute;nico</label>
											<div class="controls">
												<input type="email" name="email" size="50" maxlength="255" title="Correo Electr&oacute;nico" value="<?php echo($registros->fields["EMAIL"]); ?>" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Foto
											<?php 
												if(($registros->fields['FOTO'] != null) and file_exists("../../".$path_imagenes."fotos/".$registros->fields['FOTO'])) { 
													?>
													<a href="<?php echo("../../".$path_imagenes."fotos/".$registros->fields['FOTO']); ?>" class="Foto" rel="colorbox" title="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?>"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
													| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
													<?php 
												} else { 
													if(($registros->fields['FOTO'] != null) and !file_exists("../../".$path_imagenes."fotos/".$registros->fields['FOTO'])) { 
														?>
													<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_USUARIO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Foto de Usuario"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
														<?php 
													} 
												} 
											?>
											</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" name="archivo_foto" class="input-file" title="Foto" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Cr&eacute;ditos</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="creditos" data-limite="0" class="btn" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
													<input type="text" id="creditos" name="creditos" value="<?php echo($registros->fields["CREDITOS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="creditos" class="btn" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Gratis</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="gratis" data-limite="0" class="btn" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
													<input type="text" id="gratis" name="gratis" value="<?php echo($registros->fields["GRATIS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="gratis" class="btn" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>

									</div>

									<div class="span6">
										<div class="control-group">
											<label class="control-label">NIT</label>
											<div class="controls">
												<input type="text" id="nit" name="nit" size="50" maxlength="20" title="NIT" value="<?php echo($registros->fields["NIT"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Profesi&oacute;n</label>
											<div class="controls">
												<input type="text" name="profesion" size="50" maxlength="255" title="Profesi&oacute;n" value="<?php echo($registros->fields["PROFESION"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nivel Acad&eacute;mico</label>
											<div class="controls">
												<input type="text" name="nivel_academico" size="50" maxlength="255" title="Nivel Acad&eacute;mico" value="<?php echo($registros->fields["NIVEL_ACADEMICO"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<hr />
										<div class="control-group">
											<label class="control-label">Tipo de Usuario</label>
											<div class="controls">
												<select name="tipo_usuario" title="Tipo de Usuario" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
													<option value="">Tipo de Usuario</option>
													<?php
														//solo me permite otorgar niveles igual o superior al del usuario logeado
														for($i = $_SESSION['usuario_nivel']; $i < count($niveles_acceso); $i++) {
															?>
													<option value="<?php echo($niveles_acceso[$i]); ?>" <?php if($niveles_acceso[$i] == $registros->fields["TIPO_USUARIO"]) { echo("selected='selected'"); } ?>><?php echo($niveles_acceso[$i]); ?></option>
															<?php
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Sucursal</label>
											<div class="controls">
												<select name="sucursal" id="sucursal" title="Sucursal" class="form-control" style="width: auto;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
													<option value=""></option>
													<?php
														$registrosSedes = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
														while(!$registrosSedes->EOF) {									
															?>
													<option value="<?php echo($registrosSedes->fields["ID_SUCURSAL"]); ?>" <?php if($registrosSedes->fields["ID_SUCURSAL"] == $registros->fields["ID_SUCURSAL"]) { echo("selected='selected'"); } ?>><?php echo($registrosSedes->fields["NOMBRE"]); ?></option>
															<?php
															$registrosSedes->MoveNext();
														}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<input type="text" name="usuario" size="50" maxlength="255" title="Usuario" value="<?php echo($registros->fields["USUARIO"]); ?>" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Contrase&ntilde;a</label>
											<div class="controls">
												<input type="text" name="clave" size="50" maxlength="255" title="Clave" value="<?php echo($registros->fields["CLAVE"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha de Alta</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" placeholder="dd/mm/aaaa" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == "1") { ?>checked="checked"<?php } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[1])?>
												</label>
												<label class="radio inline">
													<input type="radio" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == "0") { ?>checked="checked"<?php } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/> <?php echo($estados[0])?>
												</label>
											</div>
										</div>

									</div>
								</div>
								<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

							</div>

							<div id="tab02" class="tab-pane">
								<div class="control-group">
									<textarea class="ckeditor" id="comentarios" name="comentarios" rows="20" cols="60" title="Comentarios" style="width:100%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></textarea>
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

			//Eliminar imagen/publicidad
			if(isset($_GET['accion']) && ($_GET['accion'] == "deleteimagen")) {
				//	verifico si el archivo existe
				$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$_POST['ida'];
				if(file_exists($nombre_completo_imagenoriginal)) {
					//	obtengo el nombre y extension del archivo
					$res = explode(".", $_POST['ida']);
					$nombre = $res[0];
					$extension = $res[count($res) - 1];
					//	obtengo la ubicacion de la imagen original
					$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
					//	elimino el archivo
					unlink($nombre_completo_imagenoriginal);
					if(file_exists($nombre_completo_imagensmall)) {
						unlink($nombre_completo_imagensmall);
					}
				}
				$conexion->Query("UPDATE usuarios SET ".$_POST['tipo']." = null WHERE (id_usuario = '".$_POST['idc']."')");
				echo("{ \"estado\" : \"ok\" }");
			}

			//	Eliminar
			if(isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	obtengo los datos y verifico si tiene archivo FOTO
						$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
						if($registros) {
							while(!$registros->EOF) {
								if($registros->fields["FOTO"] != null) {
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
									//	verifico si el archivo existe
									if(file_exists($nombre_completo_imagenoriginal)) {
										//obtengo el nombre y extension del archivo small
										$res = explode(".", $registros->fields["FOTO"]);
										$nombre = $res[0];
										$extension = $res[count($res) - 1];
										//	obtengo la ubicacion de la imagen small
										$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
										//	elimino el archivo
										unlink($nombre_completo_imagenoriginal);
										if(file_exists($nombre_completo_imagensmall)) {
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
			if(isset($_GET['accion']) && ($_GET['accion'] == "enviardatos")) {
				?>
				<form class="form-alert">
					<h3>Enviando datos de acceso...</h3>
					<div class="body-alert">
						<?php
						foreach($_GET['registro'] as $valor) {
							$comentarios = "";
							$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
							while(!$registros->EOF) {
								$Htmlfile = "datos_acceso.html";
								$campos = array(
									"fecha" => date("d/m/Y"),
									"sitio_url_xpanel" => $sitio_url."xpanel/",
									"nombre_completo" => $registros->fields["NOMBRE_COMPLETO"],
									"usuario" => $registros->fields["USUARIO"],
									"clave" => $registros->fields["CLAVE"]
								);
								$destinatario = array($registros->fields["EMAIL"]);
								$asunto = "[ ".utf8_decode($sitio_nombre)." ] "."Datos de Acceso";
					
								$resultado = enviarMensaje($Htmlfile,$campos,$destinatario,$asunto);
					
								if($resultado == "OK"){
									$comentarios .= "<p>Se envio los datos de acceso el ".date("d/m/Y H:i:s")." a la direcci&oacute;n de correo electr&oacute;nico <strong>".$registros->fields["EMAIL"]."</strong>.</p>".$registros->fields["COMENTARIOS"];
									$conexion->Query("UPDATE usuarios SET comentarios = '".$comentarios."' WHERE (id_usuario = '".$registros->fields["ID_USUARIO"]."')");
									?>
							<p>Se ha enviado los datos de acceso a la direcci&oacute;n de correo electr&oacute;nico: <strong>&quot;<?php echo($registros->fields["EMAIL"]); ?>&quot;</strong>.</p>
									<?php
								} else	{
									?>
							<p>No se ha podido enviar los datos de acceso a la direcci&oacute;n de correo electr&oacute;nico: <strong>&quot;<?php echo($registros->fields["EMAIL"]); ?>&quot;</strong>: <span style="color: #ff0000;"><?php echo($resultado) ?></span>.</p>
									<?php
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
			if(isset($_GET['accion']) && (($_GET['accion'] == "cambiarclave") || ($_GET['accion'] == "cambiarfoto") || ($_GET['accion'] == "cambiaremail"))) {
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
										<input type="text" size="50" maxlength="255" value="<?php echo($registros["NOMBRE_COMPLETO"]); ?>" readonly="readonly" style="width: 70%;" />
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
										<input type="text" id="clave" name="clave" size="50" maxlength="255" title="Nueva Contrase&ntilde;a" value="" required />
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
										<input type="file" id="archivo_foto" name="archivo_foto" title="Nueva Foto" accept=".jpeg,.jpg,.png,.gif" required />
									</div>
								</div>
									<?php
								}

								if($_GET['accion'] == "cambiaremail") {
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
							<input type="submit" class="btn btn-large btn-primary" value="Guardar cambio" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
			}

			if(isset($_GET['accion']) && ($_GET['accion'] == "updateuser")) {
				echo("<h3>Actualizando datos del usuario...</h3>");

				if($_POST['tipo'] == "cambiarclave") {
					$conexion->Query("UPDATE usuarios SET clave = '".trim($_POST['clave'])."', clave2 = '".md5(trim($_POST['clave']))."' WHERE (id_usuario = '".$_POST['registro'][0]."')");
				}

				if($_POST['tipo'] == "cambiarfoto") {
					$id = $_POST['registro'][0];
					//	elimino cualquier archivo anterior asignado
					$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
					if($registros) {
						while(!$registros->EOF) {
							if($registros->fields["FOTO"] != null) {
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
								//	verifico si el archivo existe
								if(file_exists($nombre_completo_imagenoriginal)) {
									//obtengo el nombre y extension del archivo small
									$res = explode(".", $registros->fields["FOTO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen small
									$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
									//	elimino el archivo
									unlink($nombre_completo_imagenoriginal);
									if(file_exists($nombre_completo_imagensmall)) {
										unlink($nombre_completo_imagensmall);
									}
								}
							}
							$registros->MoveNext();
						}
					}
					$id_usuario = sprintf("US%08s", $id);
					//	verifico los archivos subidos
					if(isset($_FILES["archivo_foto"])) {
						//	obtengo el nombre del archivo y su extension
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
								echo("<script type='text/javascript'>");
								echo("window.alert('".$_FILES['archivo_foto']['name'].": ".$msg_error[10]."');");
								echo("</script>");
							}
						}
					}
				}

				if($_POST['tipo'] == "cambiaremail") {
					$conexion->Query("UPDATE usuarios SET email = '".trim($_POST['email'])."' WHERE (id_usuario = '".$_POST['registro'][0]."')");
					$_SESSION['usuario_email'] = trim($_POST['email']);
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Cambiar estado
			if(isset($_GET['accion']) && ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE usuarios SET activo = 1 WHERE (id_usuario = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE usuarios SET activo = 0 WHERE (id_usuario = '".$valor."')");
								} else {
									$conexion->Query("UPDATE usuarios SET activo = 1 WHERE (id_usuario = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$valor."')");
							if($datos) {
								echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
							} else {
								echo("{ \"estado\" : \"error\" }");
							}
						}
					}		
				}
			}

			//	Privilegios
			if(isset($_GET['accion']) && ($_GET['accion'] == "privilegios")) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updateprivilegios"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_usuario" value="<?php echo($registros->fields["ID_USUARIO"]); ?>" />
					<input type="hidden" name="registro[]" value="<?php echo($registros->fields["ID_USUARIO"]); ?>" />
					<input type="hidden" name="nombre_registro" value="<?php echo(stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?>" />
					<input type="hidden" name="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

					<div class="btn-toolbar pull-right" style="margin-top: -6px;">
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver al listado</button>
					</div>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$registros->fields["ID_USUARIO"]."#tab01"); ?>">Datos del Usuario</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$registros->fields["ID_USUARIO"]."#tab02"); ?>">Comentarios</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Privilegios de Acceso</a></li>
							<li class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> M&aacute;s opciones <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cambiarclave&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Cambiar contrase&ntilde;a</a></li>
									<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=cambiaremail&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Actualizar correo electr&oacute;nico</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviardatos&registro[]=".$registros->fields["ID_USUARIO"]); ?>">Enviar datos de acceso</a></li>
								</ul>
							</li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<h2><strong>Usuario</strong>: <?php echo("&quot;".stripslashes($registros->fields["NOMBRE_COMPLETO"])."&quot; [".$registros->fields["TIPO_USUARIO"]."]"); ?></h2>

								<table id="listado" class="table table-bordered table-condensed" style="margin-top: 10px;">
								<thead>
								<tr>
									<th></th>
									<th colspan="4" style="text-align: center"><strong>Privilegios</strong></th>
								</tr>
								<tr>
									<th style="text-align: center">Contenidos</th>
									<th width="40" style="text-align: center">Ver<br /><input type="checkbox" id="CheckTodoVer" title="Seleccionar todo VER" onclick="SeleccionarColumnaPrivilegiosjQuery(this);" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/></th>
									<th width="40" style="text-align: center">Insertar<br /><input type="checkbox" id="CheckTodoInsertar" title="Seleccionar todo INSERTAR" onclick="SeleccionarColumnaPrivilegiosjQuery(this);" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/></th>
									<th width="40" style="text-align: center">Editar<br /><input type="checkbox" id="CheckTodoEditar" title="Seleccionar todo EDITAR" onclick="SeleccionarColumnaPrivilegiosjQuery(this);" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/></th>
									<th width="40" style="text-align: center">Eliminar<br /><input type="checkbox" id="CheckTodoEliminar" title="Seleccionar todo ELIMINAR" onclick="SeleccionarColumnaPrivilegiosjQuery(this);" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/></th>
								</tr>
								</thead>
								<?php
									//	Listar las Categorias
									$registrosCategorias = $temp->Query("SELECT * FROM menu_modulos WHERE categoria <> '' GROUP BY categoria ORDER BY orden");
									while(!$registrosCategorias->EOF) {
										?>
								<tr>
									<th colspan="5"><strong><?php echo($registrosCategorias->fields['CATEGORIA']);  ?></strong></th>
								</tr>
										<?php
										//	Listar los Menu, segun la Categoria
										$registrosMenus = $temp->Query("SELECT * FROM menu_modulos WHERE (categoria = '".$registrosCategorias->fields['CATEGORIA']."') and ((nombre_menu <> 'Divisor') or (url <> '-')) and (nombre_menu <> 'Paises') ORDER BY orden");
										while(!$registrosMenus->EOF) {
											//	Obtengo los privilegios del usuario actual, para visualizar los menus que posea habilitado
											if($_SESSION['usuario_id'] == 0) {
												$registrosPrivilegiosUsuario["VER"] = 1;
												$registrosPrivilegiosUsuario["INSERTAR"] = 1;
												$registrosPrivilegiosUsuario["MODIFICAR"] = 1;
												$registrosPrivilegiosUsuario["ELIMINAR"] = 1;								
											} else {
												$registrosPrivilegiosUsuario = $temp->GetRow("SELECT * FROM privilegios WHERE (id_menu = '".$registrosMenus->fields["ID_MENU"]."') and (id_usuario = '".$_SESSION['usuario_id']."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
											}
											if($registrosPrivilegiosUsuario and ($registrosPrivilegiosUsuario["VER"] == 1)) {
												?>
								<tr>
									<td><input type="hidden" name="id_menu[]" value="<?php echo($registrosMenus->fields["ID_MENU"])?>" /><?php echo($registrosMenus->fields["NOMBRE_MENU"]); ?></td>
												<?php
											
											//	Obtengo los privilegios del usuario
											$registrosPrivilegios = $temp->Query("SELECT * FROM privilegios WHERE (id_menu = '".$registrosMenus->fields["ID_MENU"]."') and (id_usuario = '".$registros->fields["ID_USUARIO"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
											if($registrosPrivilegios and ($registrosPrivilegios->RecordCount() > 0)) {
												while(!$registrosPrivilegios->EOF) {
													?>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="1" title="ver" <?php if($registrosPrivilegios->fields["VER"] == 1) {  echo("checked"); } ?> <?php if($_GET['accion'] == "ver") {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="2" title="insertar" <?php if($registrosPrivilegios->fields["INSERTAR"] == 1) {  echo("checked"); } ?>  <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["INSERTAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "ACCESO DE USUARIOS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "CONSULTAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PREFERENCIAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PRIVILEGIOS")) {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="3" title="editar" <?php if($registrosPrivilegios->fields["MODIFICAR"] == 1) {  echo("checked"); } ?>  <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["MODIFICAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "ACCESO DE USUARIOS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES")) {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="4" title="eliminar" <?php if($registrosPrivilegios->fields["ELIMINAR"] == 1) {  echo("checked"); } ?>  <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["ELIMINAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PREFERENCIAS")) {  echo("disabled='disabled'"); } ?> /></td>
													<?php
													$registrosPrivilegios->MoveNext();
												}
											} else {
													?>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="1" title="ver" <?php if($_GET['accion'] == "ver") {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="2" title="insertar" <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["INSERTAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "ACCESO DE USUARIOS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "CONSULTAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PREFERENCIAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PRIVILEGIOS")) {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="3" title="editar" <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["MODIFICAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "ACCESO DE USUARIOS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES")) {  echo("disabled='disabled'"); } ?> /></td>
									<td style="text-align: center"><input type="checkbox" name="ver<?php echo("ME".$registrosMenus->fields["ID_MENU"])?>[]" value="4" title="eliminar" <?php if(($_GET['accion'] == "ver") or ($registrosPrivilegiosUsuario["ELIMINAR"] == 0) or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "VISITAS") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "REPORTES") or (strtoupper($registrosMenus->fields["NOMBRE_MENU"]) == "PREFERENCIAS")) {  echo("disabled='disabled'"); } ?> /></td>
													<?php
											}
											?>
								</tr>
											<?php
											}
											$registrosMenus->MoveNext();
										}
										$registrosCategorias->MoveNext();
									}
								?>
								</table>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-lg btn-primary" value="Guardar cambios" onClick="return confirm('Se van ha actualizar los privilegios de acceso del Usuario: <?php echo($registros->fields["NOMBRE_COMPLETO"]); ?>.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-lg btn-default" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
					<?php
					$registros->MoveNext();
				}
			}

			//	Update Privilegios
			if(isset($_GET['accion']) && ($_GET['accion'] == "updateprivilegios")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");

				//	elimino los privilegios que posea
				$conexion->Query("DELETE FROM privilegios WHERE (id_usuario = '".$_POST['id_usuario']."') and (id_sucursal = '".$_POST['sucursal']."')");
				$menu = 'id_menu';
				foreach($_POST[$menu] as $keyMenu => $val) {
					$check = "verME".$_POST[$menu][$keyMenu];
					if(isset($_POST[$check])) {
						$p = array('0', '0', '0', '0');
						foreach($_POST[$check] as $keyCheck => $valCheck) {
							//verificar que privilegio selecciono
							$p[$valCheck - 1] = '1';
						}
						//	agrego los nuevos privilegios
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
								'".$_POST[$menu][$keyMenu]."',
								'".$_POST['id_usuario']."',
								'".$_POST['sucursal']."',
								".implode(",", $p)."
							)");
					}
				}
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

		}
		//	-----	Fin USUARIOS

		include("../footer.php");
		?>
	</body>
</html>