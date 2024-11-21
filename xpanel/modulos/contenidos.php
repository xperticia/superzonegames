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

		//	-----	CONTENIDOS
		if(isset($_GET['menu'])) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
			$url_modulo .= "&anio=".$_GET['anio'];

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT CATEGORIA,TITULO,FECHA_ALTA,CLICK,ACTIVO,ID_CONTENIDO FROM contenidos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (tipo_contenido = '".$_GET['menu']."') and (year(fecha_alta) = '".$_GET['anio']."') ";
				if(isset($_GET['texto'])) {
					$query .= " and ( (upper(categoria) LIKE upper('%".$_GET['texto']."%')) or (upper(titulo) LIKE upper('%".$_GET['texto']."%')) ) ";
				}
				$query .= "ORDER BY fecha_alta DESC";

				showTable2($conexion,
					$query,
					array("CATEGORIA"=>"Categoria",
						"TITULO"=>"T&iacute;tulo",
						"FECHA_ALTA"=>"Fecha Alta",
						"CLICK"=>"Click",
						"ACTIVO"=>"Activo"),
					array(0=>"ID_CONTENIDO"),
					$cantidad_filas_x_pagina,true,true,$datos_acceso);
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) { 
				?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="tipo_contenido" value="<?php echo($_GET['menu']); ?>" />
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="#tab02" data-toggle="tab">Otros datos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" placeholder="Categoria del contenido" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Titulo</label>
									<div class="controls">
										<input type="text" id="titulo" name="titulo" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del contenido" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Autor</label>
									<div class="controls">
										<select id="usuario" name="usuario" title="Autor" required>
											<option value=""></option>
											<?php
												for($i=0; $i<(count($niveles_acceso)-3); $i++) {
													$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
													if($usuarios and ($usuarios->RecordCount() > 0)) {
														?>
											<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
														<?php
														while(!$usuarios->EOF) {
															?>
											<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo("selected='selected'"); } ?>><?php echo(stripslashes($usuarios->fields["NOMBRE_COMPLETO"])); ?></option>
															<?php
															$usuarios->MoveNext();
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
								<div class="control-group">
									<label class="control-label">Contenidos</label>
									<div class="controls">
										<textarea class="ckeditor" id="contenido" name="contenido" rows="10" cols="38" title="Contenido" style="width: 99%"></textarea>
									</div>
								</div>
								<div class="control-group" style="margin-top: 10px;">
									<label class="control-label">Im&aacute;genes del Contenido <a href="javascript:void();" rel="popover" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o. En el lugar donde desee que aparezca cada una de las im&aacute;genes coloque el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" />

										<input type="hidden" id="indicadorImagen" value="1" />
										<div id="adjuntos_contenido1" class="cloneImagen">
											<label class="control-label">[IMAGEN01]</label>
											<div class="controls">
												<input type="checkbox" id="redimensionar" name="redimensionar[]" title="Redimensionar imagen" />
												<input type="text" id="comentario_imagen" name="comentario_imagen[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" />
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="imagen_adjunta" name="imagen_adjunta[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" />

										<input type="hidden" id="indicadorArchivo" value="1" />
										<div id="adjuntos_archivo1" class="cloneArchivo">
											<label class="control-label">[ARCHIVO01]</label>
											<div class="controls">
												<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" />
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="archivo_adjunto" name="archivo_adjunto[]" title="Archivo Adjunto" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="tab02" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Click</label>
									<div class="controls">
										<input type="text" id="click" name="click" value="0" class="input-mini" title="Click" size="10" />
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
										<label class="radio inline">
											<input type="radio" id="activo" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
										</label>
										<label class="radio inline">
											<input type="radio" id="activo" name="activo" value="0" /> <?php echo($estados[0]); ?>
										</label>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Categorias</label>
									<div class="controls">
										<input type="text" name="tags_title" value="" size="50" maxlength="255" title="Categorias" style="width: 99%;" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Etiquetas</label>
									<div class="controls">
										<input type="text" name="tags_keywords" value="" size="50" maxlength="255" title="Etiquetas" style="width: 99%;" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<input type="text" name="tags_description" value="" size="50" maxlength="255" title="Descripci&oacute;n" style="width: 99%;" />
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
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				$_POST['click'] = $_POST['click'] == '' ? '0' : $_POST['click'];
				
				if(isset($_POST['id_contenido'])) {
					//	actualizo los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE contenidos 
						SET 
							id_usuario = '".$_POST['usuario']."', 
							categoria = '".addslashes($_POST['categoria'])."', 
							titulo = '".addslashes($_POST['titulo'])."', 
							contenido = '".addslashes($_POST['contenido'])."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							activo = '".$_POST['activo']."', 
							click = '".$_POST['click']."', 
							tags_title = '".addslashes($_POST['tags_title'])."', 
							tags_keywords = '".addslashes($_POST['tags_keywords'])."', 
							tags_description = '".addslashes($_POST['tags_description'])."', 
							id_sucursal = '".$_POST['id_sucursal']."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_contenido = '".$_POST['id_contenido']."')");
					$id_contenido = $_POST['id_contenido'];
				} else {
					//inserto los datos
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO contenidos 
						(
							ID_CONTENIDO,
							ID_USUARIO,
							TIPO_CONTENIDO,
							CATEGORIA,
							TITULO,
							CONTENIDO,
							FECHA_ALTA,
							ACTIVO,
							CLICK,
							TAGS_TITLE,
							TAGS_KEYWORDS,
							TAGS_DESCRIPTION,
							ID_SUCURSAL,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['usuario']."',
							'".$_POST['tipo_contenido']."',
							'".addslashes($_POST['categoria'])."',
							'".addslashes($_POST['titulo'])."',
							'".addslashes($_POST['contenido'])."',
							'".$_POST['fecha_alta']."', 
							'".$_POST['activo']."', '".$_POST['click']."', 
							'".addslashes($_POST['tags_title'])."', 
							'".addslashes($_POST['tags_keywords'])."', 
							'".addslashes($_POST['tags_description'])."', 
							'".$_POST['id_sucursal']."',
							'".$_POST['id_actualizacion']."'
						)");
					$id_contenido = $conexion->Insert_ID();
				}

                if ($id_contenido > 0) {

                	//verifico las imagenes de contenido subidos
                    if (isset($_FILES["imagen_adjunta"])) {
                        $total = count($_FILES["imagen_adjunta"]["name"]);
                        for ($i = 0; $i < $total; $i++) {
                            //	obtengo el nombre del archivo y su extension
                            $datos = $temp->GetRow("SELECT * FROM archivos ORDER BY id_archivo DESC");
                            if ($datos) {
                                $id = $datos["ID_ARCHIVO"]+1;
                            } else {
                                $id = 1;
                            }
                            $id_imagen = sprintf("IMG%06s", $id);
                            $res = explode(".", $_FILES['imagen_adjunta']['name'][$i]);
                            $tipo_archivo = $_FILES['imagen_adjunta']['type'][$i];
                            $extension = $res[count($res) - 1];
                            $nombre_archivo = $id_imagen.".".$extension;

                            //	verifico si subio el archivo
                            if (is_uploaded_file($_FILES['imagen_adjunta']['tmp_name'][$i])) {
                                //	verifico el tipo de archivo
                                if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
                            ($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
                                    //	copio el archivo
                                    copy($_FILES['imagen_adjunta']['tmp_name'][$i], stripslashes("../../".$path_imagenes."contenidos/".$nombre_archivo));
                                    //	elimino el archivo temporal
                                    unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
                                    //	inserto los datos del archivo
                                    $conexion->Query("INSERT INTO archivos (ID_ARCHIVO,ID_CONTENIDO,NOMBRE_ARCHIVO,TIPO_ARCHIVO,DESTINO,DESCARGAS,COMENTARIO) VALUES(null,'".$id_contenido."','".$nombre_archivo."','Imagen','contenido', 0,'".addslashes($_POST['comentario_imagen'][$i])."')");
                                    $id_archivo = $conexion->Insert_ID();
                                    //	redimensiono la imagen cargada
                                    if (isset($_POST['redimensionar']) and ($_POST['redimensionar'][$i] == 'on')) {
                                        //	obtengo la ubicacion de la imagen original
                                        $nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$nombre_archivo;
                                        $nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$id_imagen."_small.".$extension;
                                        //	paso como parametro el nombre la nueva imagen peque&ntilde;a
                                        redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
                                    }
                                } else {
                                    //elimino el archivo temporal
                                    unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
                                    echo("<script type='text/javascript'>");
                                    echo("window.alert('".$_FILES['imagen_adjunta']['name'][$i].": ".$msg_error[10]."');");
                                    echo("</script>");
                                }# if
                            }# if
                        }# for
                    }# if

                	//verifico los archivos adjuntos subidos
                    if (isset($_FILES["archivo_adjunto"])) {
                        $total = count($_FILES["archivo_adjunto"]["name"]);
                        for ($i = 0; $i < $total; $i++) {
                            //	obtengo el nombre del archivo y su extension
                            $datos = $temp->GetRow("SELECT * FROM archivos ORDER BY id_archivo DESC");
                            if ($datos) {
                                $id = $datos["ID_ARCHIVO"]+1;
                            } else {
                                $id = 1;
                            }
                            $id_doc = sprintf("D0C%06s", $id);
                            $res = explode(".", $_FILES['archivo_adjunto']['name'][$i]);
                            $tipo_archivo = $_FILES['archivo_adjunto']['type'][$i];
                            $extension = $res[count($res) - 1];
                            $nombre_archivo = $id_doc.".".$extension;

                            //	verifico si se subio un archivo
                            if (is_uploaded_file($_FILES['archivo_adjunto']['tmp_name'][$i])) {
                                //	copio el archivo
                                copy($_FILES['archivo_adjunto']['tmp_name'][$i], stripslashes("../../".$path_archivos."contenidos/".$nombre_archivo));
                                //	elimino el archivo temporal
                                unlink($_FILES['archivo_adjunto']['tmp_name'][$i]);
                                //	inserto los datos
                                $conexion->Query("INSERT INTO archivos (ID_ARCHIVO,ID_CONTENIDO,NOMBRE_ARCHIVO,TIPO_ARCHIVO,DESCARGAS,COMENTARIO) VALUES(null,'".$id_contenido."','".$nombre_archivo."','Archivo', 0,'".addslashes($_POST['comentario_archivo'][$i])."')");
                                $id_archivo = $conexion->Insert_ID();
                            }# if
                        }# for
                    }# if
				}
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM contenidos WHERE (id_contenido = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_contenido" value="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" />
						<input type="hidden" name="tipo_contenido" value="<?php echo($_GET['menu']); ?>" />
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
								<li><a href="#tab02" data-toggle="tab">Otros datos</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="control-group">
										<label class="control-label">Categoria</label>				
										<div class="controls">
											<input type="text" id="categoria" name="categoria" value="<?php echo(stripslashes($registros->fields["CATEGORIA"])); ?>" size="50" maxlength="255" title="Categoria" placeholder="Categoria del contenido" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Titulo</label>
										<div class="controls">
											<input type="text" id="titulo" name="titulo" value="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" size="50" maxlength="255" title="Titulo" style="width: 99%;" placeholder="Titulo del contenido" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Autor</label>
										<div class="controls">
											<select id="usuario" name="usuario" title="Autor" placeholder="Autor del contenido" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
												<option value=""></option>
												<?php
													for($i=0; $i<count($niveles_acceso); $i++) {
														$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
														if($usuarios and ($usuarios->RecordCount() > 0)) {
															?>
												<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
															<?php
															while(!$usuarios->EOF) {
																?>
												<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIO"]) { echo("selected='selected'"); } ?>><?php echo(stripslashes($usuarios->fields["NOMBRE_COMPLETO"])); ?></option>
																<?php
																$usuarios->MoveNext();
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
									<div class="control-group">
										<label class="control-label">Contenidos</label>
										<div class="controls">
											<textarea class="ckeditor" id="contenido" name="contenido" rows="30" cols="38" title="Contenido" style="width: 99%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["CONTENIDO"])); ?></textarea>
										</div>
									</div>
									<div class="control-group" style="margin-top: 10px;">
										<label class="control-label">Im&aacute;genes del Contenido <a href="javascript:void();" rel="popover" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o. En el lugar donde desee que aparezca cada una de las im&aacute;genes coloque el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen"  <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>

											<?php
											//	verifico si posee imagenes el contenido
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
											if($registros2->RecordCount() > 0) {
												?>
											<table class="table table-striped table-bordered table-condensed">
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
												while(!$registros2->EOF) {
													$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
													?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
													if(file_exists($nombre_completo_imagenoriginal)) {
														//	obtengo la extension del archivo
														$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														$formato = $res[count($res) - 1];
														//	obtengo el tama&ntilde;o del archivo
														$tamanio_archivo = ceil(filesize($nombre_completo_imagenoriginal) / 1024);
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
														if(file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														//	muestro un enlace para ver o descargar la imagen
														?>
												<td><a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="ImagenesContenido" rel="colorbox" title="<?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?>"><?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td> 
												<td style="text-align: center;"><?php echo($formato); ?> </td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
														<?php
													} else {
													//	muestro el nombre del archivo
													?>
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
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
											<input type="hidden" id="indicadorImagen" value="<?php echo($i); ?>" />
											<div id="adjuntos_contenido<?php echo($i); ?>" class="cloneImagen">
												<label class="control-label"><?php echo("[IMAGEN".sprintf("%02s",$i)."]"); ?></label>
												<div class="controls">
													<input type="checkbox" id="redimensionar" name="redimensionar[]" title="Redimensionar imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="text" id="comentario_imagen" name="comentario_imagen[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="imagen_adjunta" name="imagen_adjunta[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									<hr />

									<div class="control-group">
										<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />

											<?php
											//	verifico si posee archivos adjuntos
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_contenido = '".$registros->fields["ID_CONTENIDO"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");
											if($registros2->RecordCount() > 0) {
												?>
											<table class="table table-striped table-bordered table-condensed">
											<thead>
											<tr>
												<th width="50">Indicador</th>
												<th>Descripci&oacute;n</th>
												<th width="20">Formato</th>
												<th width="50">Tama&ntilde;o</th>
												<th width="20">Descargas</th>
												<th width="20">Editar</th>
												<th width="20">Eliminar</th>
											</tr>
											</thead>
											<tbody>
												<?php
												while(!$registros2->EOF) {
													$referencia = "[ARCHIVO".sprintf("%02s",$i)."]";
													?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
													if(file_exists($nombre_completo_documento)) {
													//	obtengo la extension del archivo
													$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
													$formato = $res[count($res) - 1];
													//	obtengo el tama&ntilde;o del archivo
													$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
													//	muestro un enlace para ver o descargar la imagen
														?>
												<td><a href="#" id="btnVerArchivo" data-archivo="<?php echo($nombre_completo_documento); ?>" title="Ver Archivo"><?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td>
												<td style="text-align: center;"><?php echo($formato); ?></td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: right;"><?php echo($registros2->fields["DESCARGAS"]); ?></td> 
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
														<?php
													} else {
													//muestro el nombre del archivo					   
													?> 
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_CONTENIDO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
													<?php						
													}
													?>
											</tr>
													<?php				
													$registros2->MoveNext();
													$i++;
												}	#while
												?>
											</tbody>
											</table>
												<?php
											}	#if
											?>		
											<input type="hidden" id="indicadorArchivo" value="<?php echo($i); ?>" />
											<div id="adjuntos_archivo<?php echo($i); ?>" class="cloneArchivo">
												<label class="control-label"><?php echo("[ARCHIVO".sprintf("%02s",$i)."]"); ?></label>
												<div class="controls">
													<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="archivo_adjunto" name="archivo_adjunto[]"title="Archivo Adjunto" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>

									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

								</div>

								<div id="tab02" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Click</label>
										<div class="controls">
											<input type="text" id="click" name="click" value="<?php echo($registros->fields["CLICK"]); ?>" class="input-mini" title="Click" size="10" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Fecha de Alta</label>
										<div class="controls">
											<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
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
									<hr />
									<div class="control-group">
										<label class="control-label">Categorias</label>
										<div class="controls">
											<input type="text" name="tags_title" value="<?php echo(stripslashes($registros->fields["TAGS_TITLE"])); ?>" size="50" maxlength="255" title="Categorias" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Etiquetas</label>
										<div class="controls">
											<input type="text" name="tags_keywords" value="<?php echo(stripslashes($registros->fields["TAGS_KEYWORDS"])); ?>" size="50" maxlength="255" title="Etiquetas" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<input type="text" name="tags_description" value="<?php echo(stripslashes($registros->fields["TAGS_DESCRIPTION"])); ?>" size="50" maxlength="255" title="Descripci&oacute;n" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
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
				?>

				<div id="modalEditarArchivo" class="modal hide fade">
					<div class="modal-header">
						<a href="#" class="close" data-dismiss="modal">&times;</a>
						<h3>Modal Heading</h3>
					</div>
					<div class="modal-body">
						<form method="post" action="" class="form-horizontal">
							<input type="hidden" id="idc" name="idc" />
							<input type="hidden" id="ida" name="ida" />
							<input type="hidden" id="tipo" name="tipo" />
							<div class="control-group">
								<label class="control-label" for="archivo_adjunto_nombre">Nombre del Archivo</label>
								<div class="controls">
									<input type="text" id="archivo_adjunto_nombre" name="archivo_adjunto_nombre" value="" title="Archivo Adjunto" readonly="readonly" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="archivo_adjunto_comentario">Descripci&oacute;n</label>
								<div class="controls">
									<input type="text" id="archivo_adjunto_comentario" name="archivo_adjunto_comentario" value="" size="50" maxlength="255" title="Descripci&oacute;n del Archivo" style="width: 95%;" required />
								</div>
							</div>
							<div class="control-group" id="opciones_imagen">
								<label class="control-label" for="archivo_adjunto_redimensionar">Redimensionar imagen</label>
								<div class="controls">
									<input type="checkbox" id="archivo_adjunto_redimensionar" name="archivo_adjunto_redimensionar" title="Redimensionar imagen" />
									<select name="archivo_adjunto_tamanio" class="input-small">
										<option value=""></option>
										<option value="320x240">320x240</option>
										<option value="640x480">640x480</option>
										<option value="800x600">800x600</option>
										<option value="124x768">124x768</option>
										<option value="">-</option>
										<option value="100x100">100x100</option>
										<option value="200x200">200x200</option>
										<option value="300x300">300x300</option>
										<option value="400x400">400x400</option>
										<option value="500x500">500x500</option>
										<option value="600x600">600x600</option>
										<option value="700x700">700x700</option>
										<option value="800x800">800x800</option>
										<option value="900x900">900x900</option>
										<option value="1000x1000">1000x1000</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-primary" id="btnModalGuardar" value="Guardar cambios" />
						<input type="button" class="btn" id="btnModalCancelar" value="Cancelar" />
					</div>
				</div>
				<?php
			}

			//	Actualizar comentario de imagen/archivo
			if(isset($_GET['accion']) and ($_GET['accion'] == "updateimagen")) {
				if($_POST['archivo_adjunto_comentario'] == '') {
					$conexion->Query("UPDATE archivos SET comentario = null WHERE (id_archivo = '".$_POST['ida']."')");
				} else {
					$conexion->Query("UPDATE archivos SET comentario = '".addslashes(utf8_decode($_POST['archivo_adjunto_comentario']))."' WHERE (id_archivo = '".$_POST['ida']."')");
				}

				if($_POST['tipo'] == 'Imagen') {
					//	obtengo los datos de la imagen, y redimensiono la imagen cargada
					$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$_POST['ida']."')");
					if($registros) {				
						while(!$registros->EOF) {
							//	obtengo la ubicacion de la imagen original
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
							//	obtengo el nombre y extension del archivo small
							$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
							$nombre = $res[0];
							$extension = $res[count($res)-1];
							//	obtengo la ubicacion de la imagen small
							$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
							//	creo la redimension
							if(isset($_POST['archivo_adjunto_redimensionar']) and ($_POST['archivo_adjunto_redimensionar'] == 'on')) {
								//	paso como parametro el nombre la nueva imagen peque&ntilde;a
								if($_POST['archivo_adjunto_tamanio'] == '') {
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
								} else {
									$tamanio = explode("x",$_POST['archivo_adjunto_tamanio']);
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, $tamanio[0], $tamanio[1]);
								}
							} else {
								//	elimino la redimension existente
							if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							}
							}
							$registros->MoveNext();
						}
					}
				}
				echo("{ \"estado\" : \"ok\" }");
			}

			//	Eliminar imagen/archivo
			if(isset($_GET['accion']) and ($_GET['accion'] == "deleteimagen")) {
				//	obtengo los datos
				$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_contenido = '".$_POST['idc']."') and (id_archivo = '".$_POST['ida']."') ORDER BY id_archivo");
				if($registros) {
					while(!$registros->EOF) {
						if($_POST['tipo'] == 'Imagen') {
							//	verifico si el archivo existe
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
							if(file_exists($nombre_completo_imagenoriginal)) {
								//	obtengo el nombre y extension del archivo
								$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen original
								$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if(file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}
						}
						if($_POST['tipo'] == 'Archivo') {
							//	verifico si el archivo existe
							$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
							if(file_exists($nombre_completo_documento)) {
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
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico si posee archivos
						$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_contenido = '".$valor."') ORDER BY id_archivo");
						if($registros) {
							while(!$registros->EOF) {
							if($registros->fields["TIPO_ARCHIVO"] == 'Imagen') {
								//verifico si el archivo existe
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
								if(file_exists($nombre_completo_imagenoriginal)) {
									//obtengo el nombre y extension del archivo small
									$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//obtengo la ubicacion de la imagen original
									$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
									//elimino el archivo
									unlink($nombre_completo_imagenoriginal);
									if(file_exists($nombre_completo_imagensmall)) {
										unlink($nombre_completo_imagensmall);
									}
								}
							}
							if($registros->fields["TIPO_ARCHIVO"] == 'Archivo') {
								//verifico si el archivo existe
								$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
								if(file_exists($nombre_completo_documento)) {
									//elimino el archivo
									unlink($nombre_completo_documento);
								}
							}
							//elimino el registro
							$temp->Execute("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

							$registros->MoveNext();
							}
							//elimino el registro
							$conexion->Execute("DELETE FROM contenidos WHERE (id_contenido = '".$valor."')");
						}
					}
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado OK
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM contenidos WHERE (id_contenido = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE contenidos SET activo = 1 WHERE (id_contenido = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE contenidos SET activo = 0 WHERE (id_contenido = '".$valor."')");
								} else {
									$conexion->Query("UPDATE contenidos SET activo = 1 WHERE (id_contenido = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM contenidos WHERE (id_contenido = '".$valor."')");
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
		//	-----	Fin CONTENIDOS

		include("../footer.php");
		?>
	</body>
</html>