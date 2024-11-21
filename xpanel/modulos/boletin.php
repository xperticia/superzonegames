<?php
    include("../config.php");
    include("../verifica.php");
    $nivel_acceso = 5;// Nivel de acceso para esta pagina.
    if ($nivel_acceso < $_SESSION['usuario_nivel']) {
        header("Location: ./?error_login=5");
        exit;
    }
?>
<?php ini_set('max_execution_time', '5000'); ?>
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

        //	------	BOLETIN
        if (isset($_GET['menu']) and ($_GET['menu'] == "boletin")) {
            //	Listado
            if (!isset($_GET['accion'])) {
                $query = "SELECT NRO_EDICION,TITULO,FECHA,FECHA_ENVIO,CLICK,ACTIVO,count(contactos_reciben.ID_CONTACTO) as CANT,boletin.ID_BOLETIN FROM boletin 
					LEFT JOIN contactos_reciben ON contactos_reciben.id_boletin = boletin.id_boletin 
					WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') ";
                if (isset($_GET['texto'])) {
                    $query .= " and ( 
						(boletin.id_boletin = '".$_GET['texto']."') or 
						(upper(nro_edicion) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(titulo) LIKE upper('%".$_GET['texto']."%')) 
					) ";
                }
                $query .= "GROUP BY boletin.id_boletin ORDER BY fecha DESC";

                showTable2(
                    $conexion,
                    $query,
                    array("NRO_EDICION"=>"Edici&oacute;n",
                        "TITULO"=>"Titulo",
                        "FECHA"=>"Fecha Alta",
                        "FECHA_ENVIO"=>"&Uacute;ltimo Envio",
                        "CLICK"=>"Click",
                        "ACTIVO"=>"Activo",
                        "CANT"=>"Envios"),
                    array(0=>"ID_BOLETIN"),
                    $cantidad_filas_x_pagina,
                    true,
                    true,
                    $datos_acceso,
                    null,
                    null,
                    array("destinatarios"=>"Enviado a ...",
                        "enviaragrupo"=>"Enviar a Grupos",
                        "enviarausuarios"=>"Enviar a Usuarios",
                        "previewBoletin"=>"Vista Preliminar")
                );
            }//	Fin Listado

            //Nuevo
            if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
                ?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF'].'?menu='.$_GET['menu'].'&accion=insert'); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
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
											<label class="control-label">Plantilla</label>
											<div class="controls">
												<select id="plantilla" name="plantilla" title="Plantilla" required>
													<option value=""></option>
													<?php
													//revisa la carpeta plantillas, busca todos los archivos HTML y lee su titulo
													$carpeta = "../templates/";
													$d = dir($carpeta);
													while (false !== ($entrada = $d->read())) {
														//Los diseños para boletines estan en carpetas individuales
														//verifica si es una carpeta y busca archivos HTML en su interior
														if (is_dir($carpeta.$entrada) and ($entrada != ".") and ($entrada != "..")) {
															$d2 = dir($carpeta.$entrada);
															while (false !== ($entrada2 = $d2->read())) {
																//verifico si es archivo y su extension
																if (is_file($carpeta.$entrada."/".$entrada2)) {
																	$res = explode(".", $entrada2);
																	$extension = $res[count($res) - 1];
																	if (($extension == "html")or($extension == "htm")) {
																		$file = fopen($carpeta.$entrada."/".$entrada2, "r");
																		$size = filesize($carpeta.$entrada."/".$entrada2);
																		$contenidofile = fread($file, $size);
																		//obtengo el titulo del archivo HTML
																		$titulo = substr($contenidofile, strpos($contenidofile, "<title>") + 7, (strpos($contenidofile, "</title>") - (strpos($contenidofile, "<title>") + 7))); ?>
																		<option value="<?php echo($entrada."/".$entrada2); ?>"><?php echo(trim($titulo)); ?></option>
																		<?php
																	}
																}
															}
															$d2->close();
														}
													}
													$d->close(); 
													?>
												</select>
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
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Publicidad 1</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="publicidad1" name="publicidad1" title="Publicidad 1" style="width: 70%" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Publicidad 2</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="publicidad2" name="publicidad2" title="Publicidad 2" style="width: 70%" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Click</label>
											<div class="controls">
												<input type="text" id="click" name="click" value="0" class="input-mini" title="Click" size="10" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">N&deg; de Edici&oacute;n</label>
									<div class="controls">
										<input type="text" id="nro_edicion" name="nro_edicion" class="input-small" size="10" maxlength="255" title="N&ordm; de Edici&oacute;n" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Titulo</label>
									<div class="controls">
										<input type="text" id="titulo" name="titulo" size="50" maxlength="255" title="Titulo" style="width: 99%" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Contenidos</label>
									<div class="controls">
										<input type="hidden" name="tipo_contenido" value="Texto|Formato|Imagenes" />
										<textarea class="ckeditor" id="contenido" name="contenido" rows="30" cols="38" title="Contenido" style="width: 99%"></textarea>
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
												<input type="checkbox" id="redimensionar" name="redimensionar[]" value="on" title="Redimensionar imagen" />
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
            if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				$_POST['click'] = $_POST['click'] == '' ? '0' : $_POST['click'];

                if (isset($_POST['id_boletin'])) {
                    //	actualizo los datos
                    echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
                    $conexion->Query("UPDATE boletin 
						SET 
							nro_edicion = '".addslashes($_POST['nro_edicion'])."', 
							titulo = '".addslashes($_POST['titulo'])."', 
							contenido = '".addslashes($_POST['contenido'])."', 
							plantilla = '".$_POST['plantilla']."', 
							fecha = '".$_POST['fecha_alta']."', 
							activo = '".$_POST['activo']."', 
							click = '".$_POST['click']."', 
							id_sucursal = '".$_POST['id_sucursal']."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_boletin = '".$_POST['id_boletin']."')");
                    $id_boletin = $_POST['id_boletin'];
                } else {
                    //	inserto los datos
                    echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
                    $conexion->Query("INSERT INTO boletin 
						(
							ID_BOLETIN,
							NRO_EDICION,
							TITULO,
							CONTENIDO,
							PLANTILLA,
							FECHA,
							ACTIVO,
							CLICK,
							ID_SUCURSAL,
							ID_ACTUALIZACION
						) VALUES(
							null, 
							'".addslashes($_POST['nro_edicion'])."', 
							'".addslashes($_POST['titulo'])."',
							'".addslashes($_POST['contenido'])."', 
							'".$_POST['plantilla']."', 
							'".$_POST['fecha_alta']."', 
							'".$_POST['activo']."', 
							'".$_POST['click']."', 
							'".$_POST['id_sucursal']."', 
							'".$_POST['id_actualizacion']."'
						)");
                    $id_boletin = $conexion->Insert_ID();
                }

                if ($id_boletin > 0) {

                    //	verifico si selecciono un archivo en publicidad1
                    if (isset($_FILES["publicidad1"])) {
                        $id_imagen = sprintf("PUBLICIDAD%04s01", $id_boletin);
                        $res = explode(".", $_FILES['publicidad1']['name']);
                        $tipo_archivo = $_FILES['publicidad1']['type'];
                        $extension = $res[count($res) - 1];
                        $nombre_archivo = $id_imagen.".".$extension;
                        //	verifico si subio el archivo
                        if (is_uploaded_file($_FILES['publicidad1']['tmp_name'])) {
                            //	verifico el tipo de archivo
                            if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
                                ($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {

                                //	elimino las imagenes anteriores
                                if (isset($_POST['old_publicidad1']) and ($_POST['old_publicidad1'] != "")) {
                                    $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$_POST['old_publicidad1'];
                                    if (file_exists($nombre_completo_imagenoriginal)) {
                                        //	elimino el archivo
                                        unlink($nombre_completo_imagenoriginal);
                                    }
                                }
                                
                                //	copio el archivo
                                copy($_FILES['publicidad1']['tmp_name'], stripslashes("../../".$path_imagenes."boletin/".$nombre_archivo));
                                //	elimino el archivo temporal
                                unlink($_FILES['publicidad1']['tmp_name']);
                                //	actualizo los datos del archivo en el registro
                                $conexion->Query("UPDATE boletin SET banner1 = '".$nombre_archivo."' WHERE (id_boletin = '".$id_boletin."')");
                            } else {
                                //	elimino el archivo temporal
                                unlink($_FILES['publicidad1']['tmp_name']);
                                echo("<script type='text/javascript'>");
                                echo("window.alert('".$_FILES['publicidad1']['name']." [".$tipo_archivo."] : ".$msg_error[10]."');");
                                echo("</script>");
                            }# if
                        }# if
                    }# if

                    //	verifico si selecciono un archivo en publicidad2
                    if (isset($_FILES["publicidad2"])) {
                        $id_imagen = sprintf("PUBLICIDAD%04s02", $id_boletin);
                        $res = explode(".", $_FILES['publicidad2']['name']);
                        $tipo_archivo = $_FILES['publicidad2']['type'];
                        $extension = $res[count($res) - 1];
                        $nombre_archivo = $id_imagen.".".$extension;
                        //	verifico si subio el archivo
                        if (is_uploaded_file($_FILES['publicidad2']['tmp_name'])) {
                            //	verifico el tipo de archivo
                            if (($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
                                ($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {

                                //	elimino las imagenes anteriores
                                if (isset($_POST['old_publicidad2']) and ($_POST['old_publicidad2'] != "")) {
                                    $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$_POST['old_publicidad2'];
                                    if (file_exists($nombre_completo_imagenoriginal)) {
                                        //elimino el archivo
                                        unlink($nombre_completo_imagenoriginal);
                                    }
                                }

                                //	copio el archivo
                                copy($_FILES['publicidad2']['tmp_name'], stripslashes("../../".$path_imagenes."boletin/".$nombre_archivo));
                                //	elimino el archivo temporal
                                unlink($_FILES['publicidad2']['tmp_name']);
                                //	actualizo los datos del archivo en el registro
                                $conexion->Query("UPDATE boletin SET banner2 = '".$nombre_archivo."' WHERE (id_boletin = '".$id_boletin."')");
                            } else {
                                //	elimino el archivo temporal
                                unlink($_FILES['publicidad2']['tmp_name']);
                                echo("<script type='text/javascript'>");
                                echo("window.alert('".$_FILES['publicidad2']['name']." [".$tipo_archivo."] : ".$msg_error[10]."');");
                                echo("</script>");
                            }# if
                        }# if
                    }# if

                    //	verifico las imagenes subidas
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
                                    copy($_FILES['imagen_adjunta']['tmp_name'][$i], stripslashes("../../".$path_imagenes."boletin/".$nombre_archivo));
                                    //	elimino el archivo temporal
                                    unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
                                    //	inserto los datos del archivo
                                    $conexion->Query("INSERT INTO archivos 
										(
											ID_ARCHIVO,
											ID_BOLETIN,
											NOMBRE_ARCHIVO,
											TIPO_ARCHIVO,
											DESTINO,
											DESCARGAS,
											COMENTARIO
										) VALUES(
											null,
											'".$id_boletin."',
											'".$nombre_archivo."',
											'Imagen',
											'contenido', 
											0,
											'".addslashes($_POST['comentario_imagen'][$i])."'
										)");

                                    $id_archivo = $conexion->Insert_ID();
                                    //	redimensiono la imagen cargada
                                    if (isset($_POST['redimensionar']) and ($_POST['redimensionar'][$i] == 'on')) {
                                        //	obtengo la ubicacion de la imagen original
                                        $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$nombre_archivo;
                                        $nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$id_imagen."_small.".$extension;
                                        //	paso como parametro el nombre la nueva imagen pequeña
                                        redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
                                    }
                                } else {
                                    //	elimino el archivo temporal
                                    unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
                                    echo("<script type='text/javascript'>");
                                    echo("window.alert('".$_FILES['imagen_adjunta']['name'][$i]." [".$tipo_archivo."] : ".$msg_error[10]."');");
                                    echo("</script>");
                                }# if
                            }# if
                        }# for
                    }# if

                    //	verifico los archivos adjuntos subidos
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
                                copy($_FILES['archivo_adjunto']['tmp_name'][$i], stripslashes("../../".$path_archivos."boletin/".$nombre_archivo));
                                //	elimino el archivo temporal
                                unlink($_FILES['archivo_adjunto']['tmp_name'][$i]);
                                //	inserto los datos
                                $conexion->Query("INSERT INTO archivos 
									(
										ID_ARCHIVO,
										ID_BOLETIN,
										NOMBRE_ARCHIVO,
										TIPO_ARCHIVO,
										DESCARGAS,
										COMENTARIO
									) VALUES(
										null,
										'".$id_boletin."',
										'".$nombre_archivo."',
										'Archivo', 
										0,
										'".addslashes($_POST['comentario_archivo'][$i])."'
									)");

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
            if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("SELECT * FROM boletin WHERE (id_boletin = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    $datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')"); 
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<input type="hidden" name="id_boletin" value="<?php echo($registros->fields["ID_BOLETIN"]); ?>" />

							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" /> Mas opciones</a>
									<ul class="dropdown-menu">
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=destinatarios&registro[]=".$registros->fields["ID_BOLETIN"]); ?>">Enviado a ...</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviaragrupo&registro[]=".$registros->fields["ID_BOLETIN"]); ?>">Enviar a Grupos</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviaragrupo&registro[]=".$registros->fields["ID_BOLETIN"]); ?>">Enviar a Contactos</a></li>
										<li class="divider"></li>
										<li><a href="<?php echo($sitio_url."preview.php?idc={EMAIL}&idb=".$registros->fields["ID_BOLETIN"]); ?>" class="iframe" rel="colorbox" title="Vista preliminar">Vista preliminar</a></li>
									</ul>
								</li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Plantilla</label>
												<div class="controls">
													<select id="plantilla" name="plantilla" title="Plantilla" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
														//revisa la carpeta plantillas, busca todos los archivos HTML y lee su titulo
														$carpeta = "../templates/";
														$d = dir($carpeta);
														while (false !== ($entrada = $d->read())) {
															//Los diseños para boletines estan en carpetas individuales
															//verifica si es una carpeta y busca archivos HTML en su interior
															if (is_dir($carpeta.$entrada) and ($entrada != ".") and ($entrada != "..")) {
																$d2 = dir($carpeta.$entrada);
																while (false !== ($entrada2 = $d2->read())) {
																	//verifico si es archivo y su extension
																	if (is_file($carpeta.$entrada."/".$entrada2)) {
																		$res = explode(".", $entrada2);
																		$extension = $res[count($res) - 1];
																		if (($extension == "html")or($extension == "htm")) {
																			$file = fopen($carpeta.$entrada."/".$entrada2, "r");
																			$size = filesize($carpeta.$entrada."/".$entrada2);
																			$contenidofile = fread($file, $size);
																			//obtengo el titulo del archivo HTML
																			$titulo = substr($contenidofile, strpos($contenidofile, "<title>") + 7, (strpos($contenidofile, "</title>") - (strpos($contenidofile, "<title>") + 7))); ?>
																			<option value="<?php echo($entrada."/".$entrada2); ?>" <?php if ($entrada."/".$entrada2 == $registros->fields['PLANTILLA']) { echo("selected='selected'"); } ?>><?php echo(trim($titulo)); ?></option>
																			<?php
																		}
																	}
																}
																$d2->close();
															}
														}
														$d->close(); 
														?>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha de Alta</label>
												<div class="controls">
													<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="1" <?php if ($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[1]); ?></span>
													</label>
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="0" <?php if ($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[0]); ?></span>
													</label>
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Publicidad 1 
													<?php
														$nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields['BANNER1'];
														if (($registros->fields['BANNER1'] != null) and file_exists($nombre_completo_imagenoriginal)) {
															?>
														<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="Publicidad1" rel="colorbox" title="Publicidad 1"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
														| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros->fields["BANNER1"]); ?>" data-tipo="banner1" data-nombre-archivo="<?php echo($nombre_completo_imagenoriginal); ?>" data-texto="Publicidad 1"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php
														} else {
															if (($registros->fields['BANNER1'] != null) and !file_exists($nombre_completo_imagenoriginal)) {
																?>
														<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros->fields["BANNER1"]); ?>" data-tipo="banner1" data-nombre-archivo="<?php echo($nombre_completo_imagenoriginal); ?>" data-texto="Publicidad 1"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
																<?php
															}
														} 
													?>
												</label>
												<div class="controls">
													<input type="hidden" name="old_publicidad1" value="<?php echo($registros->fields['BANNER1']); ?>" />
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="publicidad1" name="publicidad1" title="Publicidad 1" accept=".jpeg,.jpg,.png,.gif" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Publicidad 2
													<?php
														$nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields['BANNER2'];
														if (($registros->fields['BANNER2'] != null) and file_exists($nombre_completo_imagenoriginal)) {
															?>
														<a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="Publicidad2" rel="colorbox" title="Publicidad 2"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
														| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros->fields["BANNER2"]); ?>" data-tipo="banner2" data-nombre-archivo="<?php echo($nombre_completo_imagenoriginal); ?>" data-texto="Publicidad 2"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php
														} else {
															if (($registros->fields['BANNER2'] != null) and !file_exists($nombre_completo_imagenoriginal)) {
																?>
														<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros->fields["BANNER2"]); ?>" data-tipo="banner2" data-nombre-archivo="<?php echo($nombre_completo_imagenoriginal); ?>" data-texto="Publicidad 2"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
																<?php
															}
														} 
													?>
												</label>
												<div class="controls">
													<input type="hidden" name="old_publicidad2" value="<?php echo($registros->fields['BANNER2']); ?>" />
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="publicidad2" name="publicidad2" title="Publicidad 2" accept=".jpeg,.jpg,.png,.gif" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Click</label>
												<div class="controls">
													<input type="text" id="click" name="click" value="<?php echo($registros->fields["CLICK"]); ?>" class="input-mini" title="Click" size="10" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="control-group">
										<label class="control-label">N&deg; de Edici&oacute;n</label>
										<div class="controls">
											<input type="text" id="nro_edicion" name="nro_edicion" value="<?php echo(stripslashes($registros->fields["NRO_EDICION"])); ?>" class="input-small" size="10" maxlength="255" title="N&ordm; de Edici&oacute;n" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Titulo</label>
										<div class="controls">
											<input type="text" id="titulo" name="titulo" value="<?php echo(stripslashes($registros->fields["TITULO"])); ?>" size="50" maxlength="255" title="Titulo" style="width: 99%" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Contenidos</label>
										<div class="controls">
											<textarea class="ckeditor" id="contenido" name="contenido" rows="30" cols="38" title="Contenido" style="width: 99%" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["CONTENIDO"])); ?></textarea>
										</div>
									</div>
									<div class="control-group" style="margin-top: 10px;">
										<label class="control-label">Im&aacute;genes del Contenido <a href="javascript:void();" rel="popover" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o. En el lugar donde desee que aparezca cada una de las im&aacute;genes coloque el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>

											<?php
											//	verifico si posee imagenes el contenido
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_boletin = '".$registros->fields["ID_BOLETIN"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
											if ($registros2->RecordCount() > 0) {
												?>
											<table class="table table-bordered table-condensed zebra-striped">
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
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
													if (file_exists($nombre_completo_imagenoriginal)) {
														//	obtengo la extension del archivo
														$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														$formato = $res[count($res) - 1];
														//	obtengo el tamaño del archivo
														$tamanio_archivo = ceil(filesize($nombre_completo_imagenoriginal) / 1024);
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$nombre."_small.".$extension;
														if (file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														//	muestro un enlace para ver o descargar la imagen ?>
												<td><a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="ImagenesContenido" rel="colorbox" title="<?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) {
															echo($registros2->fields["NOMBRE_ARCHIVO"]);
														} else {
															echo(stripslashes($registros2->fields["COMENTARIO"]));
														} ?>"><?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) {
															echo($registros2->fields["NOMBRE_ARCHIVO"]);
														} else {
															echo(stripslashes($registros2->fields["COMENTARIO"]));
														} ?></a></td> 
												<td style="text-align: center;"><?php echo($formato); ?> </td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
														<?php
													} else {
														//	muestro el nombre del archivo ?> 
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
													<?php
													} ?>
											</tr>
													<?php
													$registros2->MoveNext();
													$i++;
												} ?>
											</tbody>
											</table>
												<?php
												} 
											?>
											<input type="hidden" id="indicadorImagen" value="<?php echo($i); ?>" />
											<div id="adjuntos_contenido<?php echo($i); ?>" class="cloneImagen">
												<label class="control-label"><?php echo("[IMAGEN".sprintf("%02s", $i)."]"); ?></label>
												<div class="controls">
													<input type="checkbox" id="redimensionar" name="redimensionar[]" value="on" title="Redimensionar imagen" />
													<input type="text" id="comentario_imagen" name="comentario_imagen[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="imagen_adjunta" name="imagen_adjunta[]" title="Imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="control-group">
										<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>

											<?php
											//	verifico si posee archivos adjuntos
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_boletin = '".$registros->fields["ID_BOLETIN"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");
											if ($registros2->RecordCount() > 0) {
												?>
											<table class="table table-bordered table-condensed zebra-striped">
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
												while (!$registros2->EOF) {
													$referencia = "[ARCHIVO".sprintf("%02s", $i)."]"; ?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_documento = "../../".$path_archivos."boletin/".$registros2->fields["NOMBRE_ARCHIVO"];
													if (file_exists($nombre_completo_documento)) {
														//	obtengo la extension del archivo
														$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
														$formato = $res[count($res) - 1];
														//	obtengo el tamaño del archivo
														$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
														//	muestro un enlace para ver o descargar la imagen ?>
												<td><a href="#" id="btnVerArchivo" data-archivo="<?php echo($nombre_completo_documento); ?>" title="Ver Archivo"><?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) {
															echo($registros2->fields["NOMBRE_ARCHIVO"]);
														} else {
															echo(stripslashes($registros2->fields["COMENTARIO"]));
														} ?></a></td>
												<td style="text-align: center;"><?php echo($formato); ?></td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: right;"><?php echo($registros2->fields["DESCARGAS"]); ?></td> 
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
														<?php
													} else {
														//	muestro el nombre del archivo ?> 
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_BOLETIN"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
													<?php
													} ?>
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
												<label class="control-label"><?php echo("[ARCHIVO".sprintf("%02s", $i)."]"); ?></label>
												<div class="controls">
													<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="archivo_adjunto" name="archivo_adjunto[]" title="Archivo Adjunto" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

								</div>

							</div>

							<div class="form-actions">
								<?php if ($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
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

            //Actualizar comentario de imagen/archivo
            if (isset($_GET['accion']) and ($_GET['accion'] == "updateimagen")) {
                if ($_POST['archivo_adjunto_comentario'] == '') {
                    $conexion->Query("UPDATE archivos SET comentario = null WHERE (id_archivo = '".$_POST['ida']."')");
                } else {
                    $conexion->Query("UPDATE archivos SET comentario = '".addslashes($_POST['archivo_adjunto_comentario'])."' WHERE (id_archivo = '".$_POST['ida']."')");
                }

                if ($_POST['tipo'] == 'Imagen') {
                    //	obtengo los datos de la imagen, y redimensiono la imagen cargada
                    $registros = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$_POST['ida']."')");
                    if ($registros) {
                        while (!$registros->EOF) {
                            //	obtengo la ubicacion de la imagen original
                            $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
                            //	obtengo el nombre y extension del archivo small
                            $res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
                            $nombre = $res[0];
                            $extension = $res[count($res)-1];
                            //	obtengo la ubicacion de la imagen small
                            $nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$nombre."_small.".$extension;
                            //	creo la redimension
                            if (isset($_POST['archivo_adjunto_redimensionar']) and ($_POST['archivo_adjunto_redimensionar'] == 'on')) {
                                //	paso como parametro el nombre la nueva imagen peque&ntilde;a
                                if ($_POST['archivo_adjunto_tamanio'] == '') {
                                    redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
                                } else {
                                    $tamanio = explode("x", $_POST['archivo_adjunto_tamanio']);
                                    redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, $tamanio[0], $tamanio[1]);
                                }
                            } else {
                                //	elimino la redimension existente
                                if (file_exists($nombre_completo_imagensmall)) {
                                    unlink($nombre_completo_imagensmall);
                                }
                            }
                            $registros->MoveNext();
                        }
                    }
                }
                echo("{ \"estado\" : \"ok\" }");
            }

            //Eliminar imagen/archivo/publicidad
            if (isset($_GET['accion']) and ($_GET['accion'] == "deleteimagen")) {
                if (($_POST['tipo'] == "Imagen") or ($_POST['tipo'] == "Archivo")) {
                    //	obtengo los datos
                    $registros = $conexion->Query("SELECT * FROM archivos WHERE (id_boletin = '".$_POST['idc']."') and (id_archivo = '".$_POST['ida']."') ORDER BY id_archivo");
                    if ($registros) {
                        while (!$registros->EOF) {
                            if ($_POST['tipo'] == 'Imagen') {
                                //	verifico si el archivo existe
                                $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
                                if (file_exists($nombre_completo_imagenoriginal)) {
                                    //	obtengo el nombre y extension del archivo
                                    $res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
                                    $nombre = $res[0];
                                    $extension = $res[count($res) - 1];
                                    //	obtengo la ubicacion de la imagen original
                                    $nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$nombre."_small.".$extension;
                                    //	elimino el archivo
                                    unlink($nombre_completo_imagenoriginal);
                                    if (file_exists($nombre_completo_imagensmall)) {
                                        unlink($nombre_completo_imagensmall);
                                    }
                                }
                            }
                            if ($_POST['tipo'] == 'Archivo') {
                                //	verifico si el archivo existe
                                $nombre_completo_documento = "../../".$path_archivos."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
                                if (file_exists($nombre_completo_documento)) {
                                    //	elimino el archivo
                                    unlink($nombre_completo_documento);
                                }
                            }
                            //elimino el registro
                            $temp->Query("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

                            $registros->MoveNext();
                        }
                        echo("{ \"estado\" : \"ok\" }");
                    }
                } else {
                    //	verifico si el archivo existe
                    $nombre_completo_archivo = "../../".$path_imagenes."boletin/".$_POST['ida'];
                    if (file_exists($nombre_completo_archivo)) {
                        unlink($nombre_completo_archivo);
                    }
                    $conexion->Query("UPDATE boletin SET ".$_POST['tipo']." = null WHERE (id_boletin = '".$_POST['idc']."')");
                    echo("{ \"estado\" : \"ok\" }");
                }
            }

            //Eliminar
            if (isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //	verifico si posee ARCHIVOS el BOLETIN
                        $registros = $conexion->Query("SELECT * FROM archivos WHERE (id_boletin = '".$valor."') ORDER BY id_archivo");
                        if ($registros) {
                            while (!$registros->EOF) {
                                //	elimino las veces que fue descargado
                                $temp->Query("DELETE FROM contactos_descargan WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");
                                //	verifico el tipo de archivo que es, para eliminarlos
                                if ($registros->fields["TIPO_ARCHIVO"] == 'Imagen') {
                                    $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
                                    if (file_exists($nombre_completo_imagenoriginal)) {
                                        $res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
                                        $nombre = $res[0];
                                        $extension = $res[count($res) - 1];
                                        $nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$nombre."_small.".$extension;
                                        unlink($nombre_completo_imagenoriginal);
                                        if (file_exists($nombre_completo_imagensmall)) {
                                            unlink($nombre_completo_imagensmall);
                                        }
                                    }
                                }
                                if ($registros->fields["TIPO_ARCHIVO"] == 'Archivo') {
                                    $nombre_completo_archivo = "../../".$path_archivos."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
                                    if (file_exists($nombre_completo_archivo)) {
                                        unlink($nombre_completo_archivo);
                                    }
                                }
                                //	elimino el registro
                                $temp->Query("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

                                $registros->MoveNext();
                            }
                        }
                        //	verifico si posee PUBLICIDAD asignada
                        $registros = $conexion->Query("SELECT BANNER1,BANNER2 FROM boletin WHERE (id_boletin = '".$valor."')");
                        if ($registros) {
                            while (!$registros->EOF) {
                                $archivo = "../../".$path_imagenes."boletin/".$registros->fields["BANNER1"];
                                if (($registros->fields["BANNER1"] != null) and file_exists($archivo)) {
                                    unlink($archivo);
                                }
                                $archivo = "../../".$path_imagenes."boletin/".$registros->fields["BANNER2"];
                                if (($registros->fields["BANNER2"] != null)and file_exists($archivo)) {
                                    unlink($archivo);
                                }
                                $registros->MoveNext();
                            }
                        }
                        //	elimino los accesos de los contactos
                        $conexion->Query("DELETE FROM contactos_acceden WHERE (id_boletin = '".$valor."')");
                        //	elimino los envios realizados a los contactos
                        $conexion->Query("DELETE FROM contactos_reciben WHERE (id_boletin = '".$valor."')");
                        //	elimino el registro
                        $conexion->Query("DELETE FROM boletin WHERE (id_boletin = '".$valor."')");
                    }
                    echo("{ \"estado\" : \"ok\" }");
                }
            }

            //	Cambiar estado
            if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //verifico el estado que posee
                        $datos = $temp->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$valor."')");
                        if ($datos) {
                            if ($datos["ACTIVO"] == 0) {
                                $conexion->Query("UPDATE boletin SET activo = 1 WHERE (id_boletin = '".$valor."')");
                            } else {
                                if ($datos["ACTIVO"] == 1) {
                                    $conexion->Query("UPDATE boletin SET activo = 0 WHERE (id_boletin = '".$valor."')");
                                } else {
                                    $conexion->Query("UPDATE boletin SET activo = 1 WHERE (id_boletin = '".$valor."')");
                                }
                            }
                            $datos = $temp->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$valor."')");
                            if ($datos) {
                                echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
                            } else {
                                echo("{ \"estado\" : \"error\" }");
                            }
                        }
                    }
                }
            }

            //	Permite ver quienes fueron los destinatarios del Boletin
            if (isset($_GET['accion']) and ($_GET['accion'] == "destinatarios")) {
                $datos = $conexion->GetRow("SELECT boletin.ID_BOLETIN,boletin.TITULO,count(contactos_reciben.ID_CONTACTO) as CANT FROM boletin LEFT JOIN contactos_reciben ON contactos_reciben.id_boletin = boletin.ID_BOLETIN WHERE (boletin.ID_BOLETIN = '".$_GET['registro'][0]."')"); 
				?>
				<form action="" method="post" class="form-horizontal">
					<div class="tabbable">
						<h3><?php echo(stripslashes($datos["TITULO"])); ?></h3>
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$datos["ID_BOLETIN"]); ?>">Ver datos del Boletin</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Enviado a ...</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviaragrupo&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviar a Grupos</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviarausuarios&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviar a Contactos</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo($sitio_url."preview.php?idc={EMAIL}&idb=".$datos["ID_BOLETIN"]); ?>" class="iframe" rel="colorbox" title="Vista preliminar">Vista preliminar</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="well" style="width: 99%;">
									<label class="control-label" style="width: 200px;">Enviado a <?php echo($datos["CANT"]); ?> contactos</label>
									<select name="agrupadosxfecha" onchange="AbrirURL(this);" title="Fechas de Envio">
										<option value="">Fecha (Cantidad)</option>
										<?php
										$registros = $conexion->Query("SELECT date(fechahora) as FE, time(fechahora) as HS, count(*) as Cant FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') GROUP BY date(fechahora) ORDER BY fechahora DESC");
										while (!$registros->EOF) {
											?>
										<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_BOLETIN"]."&op=".$registros->fields["FE"]); ?>" <?php if ((isset($_GET['op']) and ($_GET['op'] == $registros->fields["FE"]))) { echo("selected='selected'"); } ?>><?php echo(formato_fecha($registros->fields["FE"], 3)." (".$registros->fields["Cant"].")"); ?></option>
											<?php
											$registros->MoveNext();
											} 
										?>
									</select>
									<select name="agrupados" onchange="AbrirURL(this);" title="Fechas de Envio">
										<option value="">Fecha&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grupo (Cantidad)</option>
										<?php
										$registros = $conexion->Query("SELECT date(fechahora) as FE, time(fechahora) as HS, count(*) as Cant, contactos.GRUPO FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') GROUP BY date(fechahora),grupo ORDER BY fechahora DESC,grupo");
										while (!$registros->EOF) {
											?>
										<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_BOLETIN"]."&op=".$registros->fields["FE"]."&op2=".$registros->fields["GRUPO"]); ?>" <?php if ((isset($_GET['op']) and ($_GET['op'] == $registros->fields["FE"])) and (isset($_GET['op2']) and ($_GET['op2'] == $registros->fields["GRUPO"]))) { echo("selected='selected'"); } ?>><?php echo(formato_fecha($registros->fields["FE"], 3)." ".$registros->fields["GRUPO"]." (".$registros->fields["Cant"].")"); ?></option>
											<?php
											$registros->MoveNext();
										} 
										?>
									</select>
									<input type="button" class="btn" value="Ver Todos" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$datos["ID_BOLETIN"]."&op=todos"); ?>';" />
								</div>

								<table id="listado" class="table table-bordered table-condensed">
								<thead>
								<tr>
									<th>#</th>
									<th>Apellido y Nombre</th>
									<th>Correo Electr&oacute;nico</th>
									<th>Grupo</th>
									<th>Fecha / Hora</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($_GET['op'])) {
									if (isset($_GET['op']) and ($_GET['op'] == "todos")) {
										$registros = $conexion->Query("SELECT * FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') ORDER BY fechahora DESC");
									} else {
										if (isset($_GET['op2'])) {
											$registros = $conexion->Query("SELECT * FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (date(fechahora) = '".$_GET['op']."') and (grupo = '".$_GET['op2']."') ORDER BY fechahora DESC");
										} else {
											$registros = $conexion->Query("SELECT * FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto WHERE (id_boletin = '".$datos["ID_BOLETIN"]."') and (date(fechahora) = '".$_GET['op']."') ORDER BY fechahora DESC");
										}
									}
									if ($registros->RecordCount() > 0) {
										$i = $registros->RecordCount();
										while (!$registros->EOF) {
											?>
								<tr class="unChecked">
									<td><?php echo($i); ?></td>
									<td><?php echo($registros->fields["NOMBRE_COMPLETO"]); ?></td>
									<td><?php echo($registros->fields["EMAIL"]); ?></td>
									<td><?php echo($registros->fields["GRUPO"]); ?></td>
									<td><?php echo($registros->fields["FECHAHORA"]); ?></td>
								</tr>
											<?php
											$registros->MoveNext();
											$i--;
										}
									} else {
										?>
								<tr>
									<td colspan="5" style="text-align: center;"><h5>no hay registros</h5></td>
								</tr>
									<?php
									}
								} else {
									?>
								<tr>
									<td colspan="5" style="text-align: center;"><h5>seleccionar fechas de envio</h5></td>
								</tr>
									<?php
								} ?>
								</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">	
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //	Permite seleccionar GRUPOS de contactos para enviar el Boletin
            if (isset($_GET['accion']) and ($_GET['accion'] == "enviaragrupo")) {
                $datos = $conexion->GetRow("SELECT ID_BOLETIN,TITULO FROM boletin WHERE (id_boletin = '".$_GET['registro'][0]."')"); 
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=sendgrupos"); ?>" method="post" class="form-horizontal">
					<div class="tabbable">
						<input type="hidden" name="id_boletin" value="<?php echo($datos["ID_BOLETIN"]); ?>" />
						
						<h3><?php echo(stripslashes($datos["TITULO"])); ?></h3>
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$datos["ID_BOLETIN"]); ?>">Ver datos del Boletin</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=destinatarios&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviado a ...</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Enviar a Grupos</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviarausuarios&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviar a Contactos</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo($sitio_url."preview.php?idc={EMAIL}&idb=".$datos["ID_BOLETIN"]); ?>" class="iframe" rel="colorbox" title="Vista preliminar">Vista preliminar</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<table id="listado" class="table table-bordered table-condensed">
								<thead>
								<tr>
									<th style="width:15px;"><input type="checkbox" id="CheckTodo" name="CheckTodo" /></th>
									<th>Nombre</th>
									<th style="width:40px;">Cantidad</th>
								</tr>
								</thead>
								<tbody>
									<?php
									$registros = $temp->Query("SELECT GRUPO,count(*) as Cant FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') GROUP BY grupo ORDER BY grupo");
									if ($registros->RecordCount() > 0) {
										?>
									<tr>
										<td colspan="3"><strong>Grupos de Contactos</strong></td>
									</tr>
									<?php
										while (!$registros->EOF) {
											?>
									<tr class="unChecked">
										<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registros->fields["GRUPO"]); ?>" /></td>
										<td><?php echo($registros->fields["GRUPO"]); ?></td>
										<td style="text-align: center;"><?php echo($registros->fields["Cant"]); ?></td>
									</tr>
											<?php
											$registros->MoveNext();
										}	#while
									}	#if

									$registros = $temp->Query("SELECT grupos.*,count(*) as Cant FROM grupos LEFT JOIN contactos_pertenecen ON contactos_pertenecen.id_grupo = grupos.id_grupo GROUP BY grupos.id_grupo ORDER BY nombre");
									if ($registros->RecordCount() > 0) {
										?>
									<tr>
										<td colspan="3"><strong>Grupos de Promoci&oacute;n</strong></td>
									</tr>
									<?php
										while (!$registros->EOF) {
											?>
									<tr class="unChecked">
										<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registros->fields["ID_GRUPO"]); ?>" /></td>
										<td><?php echo($registros->fields["NOMBRE"]); ?></td>
										<td style="text-align: center;"><?php echo($registros->fields["Cant"]); ?></td>
									</tr>
											<?php
											$registros->MoveNext();
										}	#while
									}	#if
									?>
								</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Enviar Boletin" onclick="return confirm('Se va ha enviar el boletin a los Grupos seleccionados.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //	Permite seleccionar USUARIOS para enviar el Boletin
            if (isset($_GET['accion']) and ($_GET['accion'] == "enviarausuarios")) {
                $datos = $conexion->GetRow("SELECT ID_BOLETIN,TITULO FROM boletin WHERE (id_boletin = '".$_GET['registro'][0]."')"); 
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=sendusuarios"); ?>" method="post" class="form-horizontal">
					<div class="tabbable">
						<input type="hidden" name="id_boletin" value="<?php echo($datos["ID_BOLETIN"]); ?>" />

						<h3><?php echo(stripslashes($datos["TITULO"])); ?></h3>
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$datos["ID_BOLETIN"]); ?>">Ver datos del Boletin</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=destinatarios&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviado a ...</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=enviaragrupo&registro[]=".$datos["ID_BOLETIN"]); ?>">Enviar a Grupos</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Enviar a Contactos</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo($sitio_url."preview.php?idc={EMAIL}&idb=".$datos["ID_BOLETIN"]); ?>" class="iframe" rel="colorbox" title="Vista preliminar">Vista preliminar</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="well" style="width: 99%;">
									<input type="button" class="btn" value="Ver todos" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."&op=todos"); ?>';" />
									<select name="agrupados" onchange="AbrirURL(this);" title="Grupos de Contactos">
										<option value="">-- Grupos de Contactos</option>
										<?php
											$registros = $conexion->Query("SELECT *,count(*) as Cant FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) GROUP BY grupo ORDER BY grupo");
											while (!$registros->EOF) {
												?>
										<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."&op=".$registros->fields["GRUPO"]); ?>" <?php if (isset($_GET['op']) and ($_GET['op'] == $registros->fields["GRUPO"])) { echo("selected=selected"); } ?>><?php echo($registros->fields["GRUPO"]." (".$registros->fields["Cant"].")"); ?></option>
												<?php
												$registros->MoveNext();
											} #while 
										?>
									</select>
									<input type="button" class="btn" value="Ver sin agrupar" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_GET['registro'][0]."&accion=".$_GET['accion']."&op=sinagrupar"); ?>';" />
								</div>
								<table id="listado" class="table table-bordered table-condensed">
								<thead>
								<tr>
									<th style="width:15px;"><input type="checkbox" id="CheckTodo" name="CheckTodo" /></th>
									<th>Apellido y Nombre</a></th>
									<th>Correo Electr&oacute;nico</th>
									<th>Grupo</th>
								</tr>
								</thead>
								<tbody>
									<?php
										if (isset($_GET['op'])) {
											if (isset($_GET['op']) and ($_GET['op'] == "todos")) {
												$registros = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (activo = 1) ORDER BY nombre_completo, email");
											} else {
												if (isset($_GET['op']) and ($_GET['op'] == "sinagrupar")) {
													$registros = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and ((grupo = '') or (grupo is null)) and (activo = 1) ORDER BY nombre_completo, email");
												} else {
													$registros = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (grupo = '".$_GET['op']."') and (activo = 1) ORDER BY nombre_completo, email");
												}	#if
											}	#if

											if ($registros->RecordCount() > 0) {
												while (!$registros->EOF) {
													?>
									<tr class="unChecked">
										<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registros->fields["ID_CONTACTO"]); ?>" /></td>
										<td><?php echo($registros->fields["NOMBRE_COMPLETO"]); ?></td>
										<td><?php echo($registros->fields["EMAIL"]); ?></td>
										<td><?php echo($registros->fields["GRUPO"]); ?></td>
									</tr>
													<?php
													$registros->MoveNext();
												}	#while
											} else {
												?>
									<tr>
										<td colspan="4" style="text-align: center;"><h5>no hay registros</h5></td>
									</tr>
												<?php
											}	#if
										} else {
											?>
									<tr>
										<td colspan="4" style="text-align: center;"><h5>seleccionar grupos de contactos</h5></td>
									</tr>
											<?php
										}	#if 
									?>
								</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Enviar Boletin" onclick="return confirm('Se va ha enviar el boletin a los registros seleccionados.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //	Enviar boletin
            if (isset($_GET['accion']) and (($_GET['accion'] == "sendgrupos") or ($_GET['accion'] == "sendusuarios"))) {

                //	obtengo los datos del boletin
                $contenido = "";
                $datos = $conexion->GetRow("SELECT * FROM boletin WHERE (id_boletin = '".$_POST['id_boletin']."')");
                if ($datos) {
                    //	actualizo las referencias del titulo
                    $datos["TITULO"] = str_replace("{EDICION}", stripslashes($datos["NRO_EDICION"]), $datos["TITULO"]);
                    $datos["TITULO"] = str_replace("{FECHA}", formato_fecha(date("Y/m/d"), 1), $datos["TITULO"]);
                    $datos["TITULO"] = str_replace("{SITIO_URL}", $sitio_url, $datos["TITULO"]);
                    $datos["TITULO"] = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $datos["TITULO"]);
                    $datos["TITULO"] = str_replace("{SITIO_EMAIL}", $sitio_email, $datos["TITULO"]);
                    $datos["TITULO"] = str_replace("{ID_BOLETIN}", $datos["ID_BOLETIN"], $datos["TITULO"]);

                    //	obtengo el boletin
                    $contenido = crearBoletin2($datos["ID_BOLETIN"]);
                } else {
                    echo("<p>No hay resultado para el parametro indicado.</p>");
                }

                $mail = new PHPMailer();
                $mail->ConfirmReadingTo = $sitio_email_confirmacion;
                $mail->From				= $sitio_email;
                $mail->FromName			= $sitio_nombre;
                $mail->Subject			= stripslashes($datos["TITULO"]);
                $mail->IsHTML(true);
                $mail->Body				= $contenido;

                $envios = 0;

                if ($_GET['accion'] == "sendgrupos") {
                    ?>
				<form method="post" action="" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Enviando a grupos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Boletin</label>
									<div class="controls">
										<input type="text" value="<?php echo(stripslashes($datos["TITULO"])); ?>" style="width: 70%;" readonly="readonly" />
									</div>
								</div>
								<?php
                                    foreach ($_POST['registro'] as $valor) {
                                        //	verifico si es grupo de contactos o de promocion
                                        if ($valor > 0) {
                                            $datos = $conexion->GetRow("SELECT * FROM grupos WHERE (id_grupo = '".$valor."')"); ?>
								<div class="control-group">
									<label class="control-label">Grupo</label>
									<div class="controls">
										<input type="text" value="<?php echo($datos["NOMBRE"]); ?>" style="width: 70%;" readonly="readonly" />				
									</div>
								</div>
											<?php
                                            //	es un grupo de promocion
                                            $registros = $conexion->Query("SELECT ID_CONTACTO,EMAIL,NOMBRE_COMPLETO FROM contactos_pertenecen LEFT JOIN contactos ON (contactos.id_contacto = contactos_pertenecen.id_contacto) and (contactos.activo = 1) WHERE (id_grupo = '".$valor."')");
                                            if ($registros) {
                                                ?>
								<div class="control-group">
									<label class="control-label">Destinatarios</label>
									<div class="controls">
										<ol>
												<?php
                                                $notificacion = "<p>Boletin enviado al GRUPO: <strong>".$datos["NOMBRE"]."</strong>, con los siguientes destinatarios:</p>";
                                                $notificacion .= "<ol>\n";
                                                while (!$registros->EOF) {
                                                    $notificacion .= "\t<li>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ";
                                                    echo("<li><em>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ".$registros->fields["EMAIL"]." ")."</em>";

                                                    //	actualizo las referencias del ASUNTO
                                                    $asunto 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), stripslashes($datos["TITULO"]));
                                                    $asunto 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $asunto);
                                                    $asunto 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $asunto);
                                                    $asunto 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $asunto);
                                                    $mail->Subject			= $asunto;

                                                    //	actualizo las referencias del MENSAJE
                                                    $mensaje 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $contenido);
                                                    $mensaje 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $mensaje);
                                                    $mensaje 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $mensaje);
                                                    $mensaje 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $mensaje);

                                                    $mail->MsgHTML($mensaje);
                                                    $mail->ClearAddresses();

                                                    //	verifico si tiene mas de un correo
                                                    if (strpos($registros->fields["EMAIL"], ";")) {
                                                        $destinatario = explode(";", trim($registros->fields["EMAIL"]));
                                                        $notificacion .= "<ol>\n";
                                                        foreach ($destinatario as $valor) {
                                                            $mail->AddAddress($valor);
                                                            echo("\t<li>".$valor."</li>\n");
                                                            $notificacion .= "<li>".$valor."</li>\n";
                                                        }
                                                        $notificacion .= "</ol>\n";
                                                    } else {
                                                        $mail->AddAddress(trim($registros->fields["EMAIL"]));
                                                        $notificacion .= trim($registros->fields["EMAIL"])." ";
                                                        echo(trim($registros3->fields["EMAIL"])." ");
                                                    }
                                                    
                                                    if ($mail->Send()) {
                                                        $notificacion .= "[OK]";
                                                        echo("[OK]");
                                                        $envios++;
                                                        $temp->Query("INSERT INTO contactos_reciben (ID_CONTACTO,ID_BOLETIN,FECHAHORA) values('".$registros->fields["ID_CONTACTO"]."','".$_POST['id_boletin']."','".date("Y/m/d H:i:s")."')");
                                                    } else {
                                                        $notificacion .= "<span style='color: red;'>[ERROR]: ".$mail->ErrorInfo."</span>";
                                                        echo("<span class=\"label label-important\">[ERROR]: ".$mail->ErrorInfo."</span>");
                                                    }
                                                    echo("</li>");

                                                    $notificacion .= "</li>\n";
                                                    $registros->MoveNext();
                                                }
                                                $notificacion .= "</ol>\n";

                                                //	actualizo la fecha de envio
                                                if ($envios > 0) {
                                                    //	envio notificacion al administrador
                                                    //$mail->From     = $sitio_email;
                                                    //$mail->FromName = $sitio_nombre;
                                                    $mail->Subject  = "[ Aviso ] > ".stripslashes($datos["TITULO"]);
                                                    $mail->MsgHTML($contenido."<hr />".$notificacion);
                                                    $mail->ClearAddresses();
                                                    $mail->AddAddress($sitio_email_admin);
                                                    if ($mail->Send()) {
                                                        //	actualizo la fecha del ultimo envio
                                                        $conexion->Execute("UPDATE boletin SET fecha_envio = '".date("Y-m-d H:i:s")."' WHERE (id_boletin = '".$datos["ID_BOLETIN"]."')");
                                                    }
                                                } ?>
										</ol>
									</div>
								</div>
											<?php
                                            }
                                        } else {
                                            ?>
								<div class="control-group">
									<label class="control-label">Grupo</label>
									<div class="controls">
										<input type="text" value="<?php echo($valor); ?>" style="width: 70%;" readonly="readonly" />				
									</div>
								</div>
											<?php
                                            //	es un grupo de contactos
                                            $registros = $conexion->Query("SELECT ID_CONTACTO,EMAIL,NOMBRE_COMPLETO FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (grupo = '".$valor."') and (activo = 1)");
                                            if ($registros) {
                                                ?>
								<div class="control-group">
									<label class="control-label">Destinatarios</label>
									<div class="controls">
										<ol>
												<?php
                                                $notificacion = "<p>Boletin enviado al GRUPO: <strong>".$valor."</strong>, con los siguientes destinatarios:</p>";
                                                $notificacion .= "<ol>\n";
                                                while (!$registros->EOF) {
                                                    $notificacion .= "\t<li>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ";
                                                    echo("<li><em>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ".$registros->fields["EMAIL"]." ")."</em>";

                                                    //	actualizo las referencias del ASUNTO
                                                    $asunto 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), stripslashes($datos["TITULO"]));
                                                    $asunto 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $asunto);
                                                    $asunto 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $asunto);
                                                    $asunto 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $asunto);
                                                    $mail->Subject			= $asunto;

                                                    //	actualizo las referencias del MENSAJE
                                                    $mensaje 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $contenido);
                                                    $mensaje 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $mensaje);
                                                    $mensaje 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $mensaje);
                                                    $mensaje 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $mensaje);

                                                    $mail->MsgHTML($mensaje);
                                                    $mail->ClearAddresses();

                                                    //	verifico si tiene mas de un correo
                                                    if (strpos($registros->fields["EMAIL"], ";")) {
                                                        $destinatario = explode(";", trim($registros->fields["EMAIL"]));
                                                        $notificacion .= "<ol>\n";
                                                        foreach ($destinatario as $valor) {
                                                            $mail->AddAddress($valor);
                                                            echo("\t<li>".$valor."</li>\n");
                                                            $notificacion .= "<li>".$valor."</li>\n";
                                                        }
                                                        $notificacion .= "</ol>\n";
                                                    } else {
                                                        $mail->AddAddress(trim($registros->fields["EMAIL"]));
                                                        $notificacion .= trim($registros->fields["EMAIL"])." ";
                                                        echo(trim($registros->fields["EMAIL"])." ");
                                                    }

                                                    if ($mail->Send()) {
                                                        $notificacion .= "[OK]";
                                                        echo("[OK]");
                                                        $envios++;
                                                        $temp->Query("INSERT INTO contactos_reciben (ID_CONTACTO,ID_BOLETIN,FECHAHORA) values('".$registros->fields["ID_CONTACTO"]."','".$_POST['id_boletin']."','".date("Y/m/d H:i:s")."')");
                                                    } else {
                                                        $notificacion .= "<span style='color: red;'>[ERROR]: ".$mail->ErrorInfo."</span>";
                                                        echo("<span class=\"label label-important\">[ERROR]: ".$mail->ErrorInfo."</span>");
                                                    }
                                                    echo("</li>");
                                                
                                                    $notificacion .= "</li>\n";
                                                    $registros->MoveNext();
                                                }
                                                $notificacion .= "</ol>\n";
                                                if ($envios > 0) {
                                                    //	envio notificacion al administrador
                                                    //$mail->From     = $sitio_email;
                                                    //$mail->FromName = $sitio_nombre;
                                                    $mail->Subject  = "[ Aviso ] > ".stripslashes($datos["TITULO"]);
                                                    $mail->MsgHTML($contenido."<hr />".$notificacion);
                                                    $mail->ClearAddresses();
                                                    $mail->AddAddress($sitio_email_admin);
                                                    if ($mail->Send()) {
                                                        //	actualizo la fecha del ultimo envio
                                                        $conexion->Execute("UPDATE boletin SET fecha_envio = '".date("Y-m-d H:i:s")."' WHERE (id_boletin = '".$datos["ID_BOLETIN"]."')");
                                                    }
                                                } ?>
										</ol>
									</div>
								</div>
												<?php
                                            }
                                        }
                                    } ?>
							</div>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
					<?php
                }

                if ($_GET['accion'] == "sendusuarios") {
                    ?>
				<form method="post" action="" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Enviando a usuarios</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Boletin</label>
									<div class="controls">
										<input type="text" value="<?php echo(stripslashes($datos["TITULO"])); ?>" style="width: 70%;" readonly="readonly" />				
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Destinatarios</label>
									<div class="controls">
										<ol>
										<?php
                                            foreach ($_POST['registro'] as $valor) {
                                                $registros = $conexion->Query("SELECT ID_CONTACTO,EMAIL,NOMBRE_COMPLETO FROM contactos WHERE (id_contacto = '".$valor."')");
                                                if ($registros) {
                                                    $notificacion = "<p>Boletin enviado a los siguientes destinatarios:</p>";
                                                    $notificacion .= "<ol>\n";
                                                    while (!$registros->EOF) {
                                                        $notificacion .= "\t<li>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ";
                                                        echo("<li><em>".stripslashes($registros->fields["NOMBRE_COMPLETO"])." - ".$registros->fields["EMAIL"]." ")."</em>";

                                                        //	actualizo las referencias del ASUNTO
                                                        $asunto 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), stripslashes($datos["TITULO"]));
                                                        $asunto 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $asunto);
                                                        $asunto 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $asunto);
                                                        $asunto 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $asunto);
                                                        $mail->Subject			= $asunto;

                                                        //	actualizo las referencias del MENSAJE
                                                        $mensaje 		= str_replace("{NOMBRE_COMPLETO}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $contenido);
                                                        $mensaje 		= str_replace("{NOMBRE}", stripslashes($registros->fields["NOMBRE_COMPLETO"]), $mensaje);
                                                        $mensaje 		= str_replace("{EMAIL}", trim($registros->fields["EMAIL"]), $mensaje);
                                                        $mensaje 		= str_replace("{ID}", $registros->fields["ID_CONTACTO"], $mensaje);

                                                        $mail->MsgHTML($mensaje);
                                                        $mail->ClearAddresses();

                                                        //	verifico si tiene mas de un correo
                                                        if (strpos($registros->fields["EMAIL"], ";")) {
                                                            $destinatario = explode(";", trim($registros->fields["EMAIL"]));
                                                            $notificacion .= "<ol>\n";
                                                            foreach ($destinatario as $valor) {
                                                                $mail->AddAddress($valor);
                                                                echo("\t<li>".$valor."</li>\n");
                                                                $notificacion .= "<li>".$valor."</li>\n";
                                                            }
                                                            $notificacion .= "</ol>\n";
                                                        } else {
                                                            $mail->AddAddress(trim($registros->fields["EMAIL"]));
                                                            $notificacion .= trim($registros->fields["EMAIL"])." ";
                                                            echo(trim($registros->fields["EMAIL"])." ");
                                                        }

                                                        if ($mail->Send()) {
                                                            $notificacion .= "[OK]";
                                                            echo("[OK]");
                                                            $envios++;
                                                            $temp->Query("INSERT INTO contactos_reciben (ID_CONTACTO,ID_BOLETIN,FECHAHORA) values('".$registros->fields["ID_CONTACTO"]."','".$_POST['id_boletin']."','".date("Y/m/d H:i:s")."')");
                                                        } else {
                                                            $notificacion .= "<span style='color: red;'>[ERROR]: ".$mail->ErrorInfo."</span>";
                                                            echo("<span class=\"label label-important\">[ERROR]: ".$mail->ErrorInfo."</span>");
                                                        }
                                                        echo("</li>");
                                                        $notificacion .= "</li>\n";
                                                        $registros->MoveNext();
                                                    }
                                                    $notificacion .= "</ol>\n";

                                                    //	actualizo la fecha y hora de envio
                                                    if ($envios > 0) {
                                                        //	envio notificacion al administrador
                                                        //$mail->From     = $sitio_email;
                                                        //$mail->FromName = $sitio_nombre;
                                                        $mail->Subject  = "[ Aviso ] > ".stripslashes($datos["TITULO"]);
                                                        $mail->MsgHTML($contenido."<hr />".$notificacion);
                                                        $mail->ClearAddresses();
                                                        $mail->AddAddress($sitio_email_admin);
                                                        if ($mail->Send()) {
                                                            //	actualizo la fecha del ultimo envio
                                                            $conexion->Execute("UPDATE boletin SET fecha_envio = '".date("Y-m-d H:i:s")."' WHERE (id_boletin = '".$datos["ID_BOLETIN"]."')");
                                                        }
                                                    }
                                                }
                                            } ?>
										</ol>
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
					<?php
                }
            }	# fin enviar boletin

            //	Articulos
            if (isset($_GET['accion']) and ($_GET['accion'] == "articulos")) {
                echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Cargando registro...</h5>");

                echo("<script type='text/javascript'>");
                echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."';");
                echo("</script>");
            }
        }
        //	-----	Fin BOLETIN

        //	-----	CONTACTOS
        if (isset($_GET['menu']) and ($_GET['menu'] == "contactos")) {
            //Listado
            if (!isset($_GET['accion'])) {
                $query = "SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO,ACTIVO FROM contactos 
					WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') ";
                if (isset($_GET['texto'])) {
                    $query .= " and ( 
						(id_contacto LIKE '%".$_GET['texto']."%') or 
						(upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(email) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(grupo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(ciudad) LIKE upper('%".$_GET['texto']."%')) 
					) ";
                }
                $query .= "ORDER BY email";

                showTable2(
                    $conexion,
                    $query,
                    array("ID_CONTACTO"=>"Id",
                        "NOMBRE_COMPLETO"=>"Apellido y Nombre",
                        "EMAIL"=>"Correo electr&oacute;nico",
                        "GRUPO"=>"Grupo",
                        "ACTIVO"=>"Activo"),
                    array(0=>"ID_CONTACTO"),
                    $cantidad_filas_x_pagina,
                    true,
                    true,
                    $datos_acceso,
                    null,
                    array("actualizar"=>"Actualizar",
                        "importar"=>"Importar",
                        "exportar"=>"Exportar",
                        "reagrupar"=>"Re-agrupar",
                        "renombrar"=>"Renombrar"),
                    array("infocontactodescargas"=>"Descargas realizadas",
                        "infocontactoenvios"=>"Envios recibidos",
                        "infocontactoaccesos"=>"Accesos realizados")
                );
            }

            //Nuevo
            if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span6">
										<h2>Datos Personales</h2>
										<div class="control-group">
											<label class="control-label">Tipo y Nro. de Documento</label>
											<div class="controls">
												<select id="tipo_doc" name="tipo_doc" class="input-mini" title="Tipo de Documento">
													<option value=""></option>
													<?php
														for ($i = 0; $i < count($tipo_documento); $i++) {
															?>
													<option value="<?php echo($tipo_documento[$i]); ?>"><?php echo($tipo_documento[$i]); ?></option>
															<?php
														} ?>
												</select>
												<input type="text" id="nro_doc" name="nro_doc" class="input-small" maxlength="10" size="10" title="Nro. de Documento" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nombres y Apellidos</label>
											<div class="controls">
												<input type="text" id="nombre_completo" name="nombre_completo" maxlength="255" size="50" title="Nombres" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Sexo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="sexo" name="sexo" value="M" checked="checked" />&nbsp;<span>Masculino</span>
												</label>
												<label class="radio inline">
													<input type="radio" id="sexo" name="sexo" value="F" />&nbsp;<span>Femenino</span>
												</label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Domicilio</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" title="Domicilio" size="50" maxlength="100" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tel&eacute;fonos</label>
											<div class="controls">
												<input type="text" id="telefono_particular" name="telefono_particular" title="Tel&eacute;fono Fijo" size="23" maxlength="50" />
												<input type="text" id="telefono_movil" name="telefono_movil" title="Tel&eacute;fono Movil" size="23" maxlength="50" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Ciudad</label>
											<div class="controls">
												<input type="text" id="ciudad" name="ciudad" title="Ciudad" size="50" maxlength="255" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Pa&iacute;s</label>
											<div class="controls">
												<select id="codigo_pais" name="codigo_pais" title="Pa&iacute;s">
													<option value=""></option>
													<?php
														$pais = strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 3, strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"])));
														$registros2 = $temp->Query("SELECT * FROM paises ORDER BY pais");
														while (!$registros2->EOF) {
															?>
													<option value="<?php echo($registros2->fields["CODIGO_PAIS"]); ?>" <?php if ($registros2->fields["CODIGO_PAIS"] == $pais) { echo("selected='selected'"); } ?>><?php echo($registros2->fields["PAIS"]); ?></option>
																<?php
															$registros2->MoveNext();
														} 
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Correo Electr&oacute;nico</label>
											<div class="controls">
												<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" size="50" maxlength="255" required />
											</div>
										</div>
									</div>

									<div class="span6">
										<h2>Datos de Organizaci&oacute;n</h2>
										<div class="control-group">
											<label class="control-label">Grupo</label>
											<div class="controls">
												<input type="text" id="grupo" name="grupo" maxlength="255" size="50" title="Grupo" />
											</div>
										</div>
										<?php
											$registros2 = $temp->Query("SELECT * FROM grupos ORDER BY nombre");
											if ($registros2 and ($registros2->RecordCount() > 0)) {
												?>
										<div class="control-group">
											<label class="control-label">Grupos de Promoci&oacute;n</label>
											<div class="controls">
												<?php
													while (!$registros2->EOF) {
														?>
												<label class="checkbox">
													<input type="checkbox" id="grupopromocion[]" name="grupopromocion[]" value="<?php echo($registros2->fields["ID_GRUPO"]); ?>" title="<?php echo($registros2->fields["NOMBRE"]); ?>"/><?php echo($registros2->fields["NOMBRE"]); ?>
												</label>
														<?php
														$registros2->MoveNext();
													}	#while
												?>
											</div>
										</div>
												<?php
											}	#if
										?>
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="1" checked="checked" />&nbsp;<span><?php echo($estados[1]); ?></span>
												</label>
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="0" />&nbsp;<span><?php echo($estados[0]); ?></span>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span6">
										<h2>Datos Profesionales</h2>
										<div class="control-group">
											<label class="control-label">Profesi&oacute;n</label>
											<div class="controls">
												<input type="text" id="profesion" name="profesion" maxlength="255" size="50" title="Profesi&oacute;n" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Nivel Acad&eacute;mico</label>
											<div class="controls">
												<select id="nivel_academico" name="nivel_academico" title="Nivel Acad&eacute;mico">
													<option value=""></option>
													<option value="Bachiller">Bachiller</option>
													<option value="T&eacute;cnico Medio">T&eacute;cnico Medio</option>
													<option value="T&eacute;cnico Superior">T&eacute;cnico Superior</option>
													<option value="Universitario">Universitario</option>
													<option value="Diplomado">Diplomado</option>
													<option value="Licenciatura">Licenciatura</option>
													<option value="Maestr&iacute;a">Maestr&iacute;a</option>
													<option value="Doctorado">Doctorado</option>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Area de Estudio</label>
											<div class="controls">
												<select id="area_estudio" name="area_estudio" title="Area de Estudio">
													<option value=""></option>
													<option value="Ciencias Agr&iacute;colas">Ciencias Agr&iacute;colas</option>
													<option value="Ciencias Empresariales">Ciencias Empresariales</option>
													<option value="Ciencias Exactas">Ciencias Exactas</option>
													<option value="Ciencias de la Salud">Ciencias de la Salud</option>
													<option value="Ciencias Jur&iacute;dicas">Ciencias Jur&iacute;dicas</option>
													<option value="Ciencias Sociales">Ciencias Sociales</option>
													<option value="Otras">Otras</option>
												</select>
											</div>
										</div>
									</div>
									<div class="span6">
										<h2>Datos Laborales</h2>
										<div class="control-group">
											<label class="control-label">Instituci&oacute;n</label>
											<div class="controls">
												<input type="text" id="institucion_trabaja" name="institucion_trabaja" title="Instituci&oacute;n" size="50" maxlength="255" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Cargo</label>
											<div class="controls">
												<input type="text" id="cargo_trabaja" name="cargo_trabaja" title="Cargo" size="50" maxlength="255" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio_trabaja" name="domicilio_trabaja" title="Direcci&oacute;n" size="50" maxlength="255" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tel&eacute;fono</label>
											<div class="controls">
												<input type="text" id="telefono_trabaja" name="telefono_trabaja" title="Tel&eacute;fono" size="20" maxlength="50" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="tab02" class="tab-pane">
								<textarea class="ckeditor" id="descripcion" name="descripcion" rows="20" cols="60" title="Comentarios" style="width:100%"></textarea>
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

            if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
                $contenido = "";
                if (isset($_POST['areas_interes'])) {
                    $cantidad = count($_POST['areas_interes']);
                    foreach ($_POST['areas_interes'] as $key => $val) {
                        $contenido .= $val;
                        if (($key + 1) < $cantidad) {
                            $contenido .= "|";
                        }
                    }
                }
                if (isset($_POST['id_contacto'])) {
                    //	Update
                    $conexion->Query("UPDATE contactos 
						SET 
							TIPO_DOC = '".$_POST['tipo_doc']."', 
							NRO_DOC = '".$_POST['nro_doc']."', 
							NOMBRE_COMPLETO = '".addslashes($_POST['nombre_completo'])."', 
							SEXO = '".$_POST['sexo']."', 
							DOMICILIO = '".addslashes($_POST['domicilio'])."', 
							TELEFONO_PARTICULAR = '".$_POST['telefono_particular']."', 
							TELEFONO_MOVIL = '".$_POST['telefono_movil']."', 
							CIUDAD = '".addslashes($_POST['ciudad'])."', 
							EMAIL = '".trim($_POST['email'])."', 
							PROFESION = '".addslashes($_POST['profesion'])."', 
							NIVEL_ACADEMICO = '".$_POST['nivel_academico']."', 
							AREA_ESTUDIO = '".$_POST['area_estudio']."', 
							INSTITUCION_TRABAJA = '".addslashes($_POST['institucion_trabaja'])."', 
							CARGO_TRABAJA = '".addslashes($_POST['cargo_trabaja'])."', 
							DOMICILIO_TRABAJA = '".addslashes($_POST['domicilio_trabaja'])."', 
							TELEFONO_TRABAJA = '".$_POST['telefono_trabaja']."', 
							COMENTARIOS = '".addslashes($_POST['descripcion'])."', 
							AREAS_INTERES = '".$contenido."', 
							GRUPO = '".addslashes($_POST['grupo'])."', 
							ACTIVO = '".$_POST['activo']."', 
							ID_SUCURSAL = '".$_POST['id_sucursal']."', 
							ID_ACTUALIZACION = '".$_POST['id_actualizacion']."' 
						WHERE (id_contacto = '".$_POST['id_contacto']."')");
                    $id = $_POST['id_contacto'];
                    //elimino los grupos asignados anteriormente
                    $conexion->Query("DELETE FROM contactos_pertenecen WHERE (id_contacto = '".$_POST['id_contacto']."')");
                } else {
                    //	Insert
                    $conexion->Query("INSERT INTO contactos 
						(
							ID_CONTACTO, 
							TIPO_DOC, 
							NRO_DOC, 
							NOMBRE_COMPLETO, 
							SEXO, 
							DOMICILIO, 
							TELEFONO_PARTICULAR, 
							TELEFONO_MOVIL, 
							CIUDAD, 
							EMAIL, 
							PROFESION, 
							NIVEL_ACADEMICO, 
							AREA_ESTUDIO, 
							INSTITUCION_TRABAJA, 
							CARGO_TRABAJA, 
							DOMICILIO_TRABAJA, 
							TELEFONO_TRABAJA, 
							COMENTARIOS, 
							AREAS_INTERES, 
							GRUPO, 
							ID_ORIGEN, 
							ACTIVO, 
							ID_SUCURSAL,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['tipo_doc']."',
							'".$_POST['nro_doc']."',
							'".addslashes($_POST['nombre_completo'])."',
							'".$_POST['sexo']."',
							'".addslashes($_POST['domicilio'])."',
							'".$_POST['telefono_particular']."',
							'".$_POST['telefono_movil']."',
							'".addslashes($_POST['ciudad'])."',
							'".trim($_POST['email'])."',
							'".addslashes($_POST['profesion'])."',
							'".$_POST['nivel_academico']."',
							'".$_POST['area_estudio']."',
							'".addslashes($_POST['institucion_trabaja'])."',
							'".addslashes($_POST['cargo_trabaja'])."',
							'".addslashes($_POST['domicilio_trabaja'])."',
							'".$_POST['telefono_trabaja']."',
							'".addslashes($_POST['descripcion'])."',
							'".$contenido."',
							'".$_POST['grupo']."',
							null,
							'".$_POST['activo']."',
							'".$_POST['id_sucursal']."',
							'".$_POST['id_actualizacion']."'
						)");
                    $id = $conexion->Insert_ID();
                }
                //	verifico si selecciono PAIS
                if ($_POST['codigo_pais'] != null) {
                    $conexion->Query("UPDATE contactos SET codigo_pais = '".$_POST['codigo_pais']."' WHERE id_contacto = '".$id."'");
                }
                //	agrego el registro al grupo de promoci&oacute;n seleccionado
                if (isset($_POST['grupopromocion'])) {
                    foreach ($_POST['grupopromocion'] as $key => $val) {
                        $conexion->Query("INSERT contactos_pertenecen (ID_CONTACTO,ID_GRUPO) VALUES('".$id."','".$val."');");
                    }
                }

                echo("<script type='text/javascript'>");
                echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."'");
                echo("</script>");
            }

            //Editar
            if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("select * FROM contactos WHERE (id_contacto = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    $datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')"); 
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal"><input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />
						<input type="hidden" name="id_contacto" value="<?php echo($registros->fields["ID_CONTACTO"]); ?>" />
						<input type="hidden" name="id_sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
								<li><a href="#tab02" data-toggle="tab">Comentarios</a></li>
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/acciones.gif" /> Mas informaci&oacute;n</a>
									<ul class="dropdown-menu">
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactodescargas&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Descargas realizadas</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoenvios&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Envios recibidos</a></li>
										<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoaccesos&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Accesos realizados</a></li>
									</ul>
								</li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span6">
											<h2>Datos Personales</h2>
											<div class="control-group">
												<label class="control-label">Tipo y Nro. de Documento</label>
												<div class="controls">
													<select id="tipo_doc" name="tipo_doc" class="input-mini" title="Tipo de Documento" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															for ($i = 0; $i < count($tipo_documento); $i++) {
																?>
														<option value="<?php echo($tipo_documento[$i]); ?>" <?php if ($tipo_documento[$i] == $registros->fields["TIPO_DOC"]) {
																	echo("selected='selected'");
																} ?>><?php echo($tipo_documento[$i]); ?></option>
																<?php
															} ?>
													</select>
													<input type="text" id="nro_doc" name="nro_doc" class="input-small" value="<?php echo($registros->fields["NRO_DOC"]); ?>" maxlength="10" size="10" title="Nro. de Documento" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Nombres y Apellidos</label>
												<div class="controls">
													<input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo(stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?>" maxlength="255" size="50" title="Nombres" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Sexo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="sexo" name="sexo" value="M" <?php if ($registros->fields["SEXO"] == 'M') { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span>Masculino</span>
													</label>
													<label class="radio inline">
														<input type="radio" id="sexo" name="sexo" value="F" <?php if ($registros->fields["SEXO"] == 'F') { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span>Femenino</span>
													</label>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Domicilio</label>
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" title="Domicilio" value="<?php echo(stripslashes($registros->fields["DOMICILIO"])); ?>" size="50" maxlength="100" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tel&eacute;fonos</label>
												<div class="controls">
													<input type="text" id="telefono_particular" name="telefono_particular" title="Tel&eacute;fono Fijo" value="<?php echo($registros->fields["TELEFONO_PARTICULAR"]); ?>" size="23" maxlength="50" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="text" id="telefono_movil" name="telefono_movil" title="Tel&eacute;fono Movil" value="<?php echo($registros->fields["TELEFONO_MOVIL"]); ?>" size="23" maxlength="50" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Ciudad</label>
												<div class="controls">
													<input type="text" id="ciudad" name="ciudad" title="Ciudad" value="<?php echo(stripslashes($registros->fields["CIUDAD"])); ?>" size="50" maxlength="255" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Pa&iacute;s</label>
												<div class="controls">
													<select id="codigo_pais" name="codigo_pais" title="Pa&iacute;s" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															$pais = strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 3, strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"])));
															$registros2 = $temp->Query("SELECT * FROM paises ORDER BY pais");
															while (!$registros2->EOF) {
																?>
														<option value="<?php echo($registros2->fields["CODIGO_PAIS"]); ?>" <?php if ($registros2->fields["CODIGO_PAIS"] == $registros->fields["CODIGO_PAIS"]) { echo("selected='selected'"); } ?>><?php echo($registros2->fields["PAIS"]); ?></option>
																<?php
																$registros2->MoveNext();
															} 
														?>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Correo Electr&oacute;nico</label>
												<div class="controls">
													<input type="text" id="email" name="email" title="Correo Electr&oacute;nico" pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$" value="<?php echo($registros->fields["EMAIL"]); ?>" size="50" maxlength="255" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>

										<div class="span6">
											<h2>Datos de Organizaci&oacute;n</h2>
											<div class="control-group">
												<label class="control-label">Grupo</label>
												<div class="controls">
													<input type="text" id="grupo" name="grupo" value="<?php echo(stripslashes($registros->fields["GRUPO"])); ?>" maxlength="255" size="50" title="Grupo" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<?php
												$registros2 = $temp->Query("SELECT * FROM grupos ORDER BY nombre");
												if ($registros2 and ($registros2->RecordCount() > 0)) {
													?>
											<div class="control-group">
												<label class="control-label">Grupos de Promoci&oacute;n</label>
												<div class="controls">
														<?php
														while (!$registros2->EOF) {
															//	verifico si esta en asignado a un grupo
															$registros3 = $temp2->Query("SELECT * FROM contactos_pertenecen WHERE (id_grupo = '".$registros2->fields["ID_GRUPO"]."') and (id_contacto = '".$registros->fields["ID_CONTACTO"]."')"); ?>
													<label class="checkbox">
														<input type="checkbox" id="grupopromocion[]" name="grupopromocion[]" value="<?php echo($registros2->fields["ID_GRUPO"]); ?>" title="<?php echo($registros2->fields["NOMBRE"]); ?>" <?php if ($registros3->RecordCount() > 0) { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/><?php echo($registros2->fields["NOMBRE"]); ?><br />
													</label>
															<?php
															$registros2->MoveNext();
														}	#while
														?>
												</div>
											</div>
													<?php
												}	#if
											?>
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="1" <?php if ($registros->fields["ACTIVO"] == '1') { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[1]); ?></span>
													</label>
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="0" <?php if ($registros->fields["ACTIVO"] == '0') { echo("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>&nbsp;<span><?php echo($estados[0]); ?></span>
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="row-fluid">
										<div class="span6">
											<h2>Datos Profesionales</h2>
											<div class="control-group">
												<label class="control-label">Profesi&oacute;n</label>
												<div class="controls">
													<input type="text" id="profesion" name="profesion" value="<?php echo(stripslashes($registros->fields["PROFESION"])); ?>" maxlength="255" size="50" title="Profesi&oacute;n" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Nivel Acad&eacute;mico</label>
												<div class="controls">
													<select id="nivel_academico" name="nivel_academico" title="Nivel Acad&eacute;mico" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<option value="Bachiller" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Bachiller") { echo("selected='selected'"); } ?>>Bachiller</option>
														<option value="T&eacute;cnico Medio" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "T&eacute;cnico Medio") { echo("selected='selected'"); } ?>>T&eacute;cnico Medio</option>
														<option value="T&eacute;cnico Superior" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "T&eacute;cnico Superior") { echo("selected='selected'"); } ?>>T&eacute;cnico Superior</option>
														<option value="Universitario" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Universitario") { echo("selected='selected'"); } ?>>Universitario</option>
														<option value="Diplomado" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Diplomado") { echo("selected='selected'"); } ?>>Diplomado</option>
														<option value="Licenciatura" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Licenciatura") { echo("selected='selected'"); } ?>>Licenciatura</option>
														<option value="Maestr&iacute;a" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Maestr&iacute;a") { echo("selected='selected'"); } ?>>Maestr&iacute;a</option>
														<option value="Doctorado" <?php if ($registros->fields["NIVEL_ACADEMICO"] == "Doctorado") { echo("selected='selected'"); } ?>>Doctorado</option>
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Area de Estudio</label>
												<div class="controls">
													<select id="area_estudio" name="area_estudio" title="Area de Estudio" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<option value="Ciencias Agr&iacute;colas" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias Agr&eacute;colas") { echo("selected='selected'"); } ?>>Ciencias Agr&iacute;colas</option>
														<option value="Ciencias Empresariales" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias Empresariales") { echo("selected='selected'"); } ?>>Ciencias Empresariales</option>
														<option value="Ciencias Exactas" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias Exactas") { echo("selected='selected'"); } ?>>Ciencias Exactas</option>
														<option value="Ciencias de la Salud" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias de la Salud") { echo("selected='selected'"); } ?>>Ciencias de la Salud</option>
														<option value="Ciencias Jur&iacute;dicas" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias Jur&eacute;dicas") { echo("selected='selected'"); } ?>>Ciencias Jur&iacute;dicas</option>
														<option value="Ciencias Sociales" <?php if ($registros->fields["AREA_ESTUDIO"] == "Ciencias Sociales") { echo("selected='selected'"); } ?>>Ciencias Sociales</option>
														<option value="Otras" <?php if ($registros->fields["AREA_ESTUDIO"] == "Otras") { echo("selected='selected'"); } ?>>Otras</option>
													</select>
												</div>
											</div>
										</div>
										<div class="span6">
											<h2>Datos Laborales</h2>
											<div class="control-group">
												<label class="control-label">Instituci&oacute;n</label>
												<div class="controls">
													<input type="text" id="institucion_trabaja" name="institucion_trabaja" title="Instituci&oacute;n" value="<?php echo(stripslashes($registros->fields["INSTITUCION_TRABAJA"])); ?>" size="50" maxlength="255" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Cargo</label>
												<div class="controls">
													<input type="text" id="cargo_trabaja" name="cargo_trabaja" title="Cargo" value="<?php echo(stripslashes($registros->fields["CARGO_TRABAJA"])); ?>" size="50" maxlength="255" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>
												<div class="controls">
													<input type="text" id="domicilio_trabaja" name="domicilio_trabaja" title="Direcci&oacute;n" value="<?php echo(stripslashes($registros->fields["DOMICILIO_TRABAJA"])); ?>" size="50" maxlength="255" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tel&eacute;fono</label>
												<div class="controls">
													<input type="text" id="telefono_trabaja" name="telefono_trabaja" title="Tel&eacute;fono" value="<?php echo($registros->fields["TELEFONO_TRABAJA"]); ?>" size="20" maxlength="50" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>

									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>

								</div>

								<div id="tab02" class="tab-pane">
									<textarea class="ckeditor" id="descripcion" name="descripcion" rows="20" cols="60" title="Comentarios" style="width:100%" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></textarea>
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

            //Renombrar
            if (isset($_GET['accion']) and ($_GET['accion'] == "renombrar")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatenombre"); ?>" method="post" id="signup" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab01" data-toggle="tab">Lista de Grupos</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<table id="listado" class="table table-bordered table-condensed">
							<thead>
							<tr>
								<th>Grupo</th>
								<th>Nuevo nombre</th>
								<th>Contactos</th>
							</tr>
							</thead>
							<tbody>
							<?php
								$grupos = $conexion->Query("SELECT GRUPO,count(ID_CONTACTO) as Cant FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') GROUP BY grupo ORDER BY grupo");
								if ($grupos) {
									while (!$grupos->EOF) {
										?>
							<tr class="unChecked">
								<td><?php echo($grupos->fields["GRUPO"]); ?></td>
								<td>
									<input type="hidden" name="old_nombre[]" value="<?php echo($grupos->fields["GRUPO"]); ?>" />
									<input type="text" name="nombre[]" value="<?php echo($grupos->fields["GRUPO"]); ?>" />
								</td>
								<td style="text-align: right;"><?php echo($grupos->fields["Cant"]); ?></td>
							</tr>
										<?php
										$grupos->MoveNext();
									}
								} else {
									?>
							<tr>
								<td colspan="3" style="text-align: center;"><h5>no hay registros</h5></td>
							</tr>
									<?php
								} 
							?>
							</tbody>
							</table>
						</div>
					</div>

					<div class="form-actions">
						<input type="submit" class="btn btn-large btn-primary" value="Renombrar datos" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
						<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
				<?php
            }

            //	Actualizar el nombre de los GRUPOS
            if (isset($_GET['accion']) and ($_GET['accion'] == "updatenombre")) {
                echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registros...</h5>");
                if (isset($_POST['old_nombre'])) {
                    foreach ($_POST['old_nombre'] as $key=>$val) {
                        if ($val != $_POST['nombre'][$key]) {
                            echo($key." : ".$val." - ".$_POST['nombre'][$key]."<br />");
                            $conexion->Query("UPDATE contactos SET grupo = '".$_POST['nombre'][$key]."' WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (grupo = '".$val."')");
                        }
                    }
                }
                echo("<script type='text/javascript'>");
                echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."'");
                echo("</script>");
            }

            //Eliminar
            if (isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //elimino los datos que posea registrado
                        $conexion->Execute("DELETE FROM contactos_acceden WHERE (id_contacto = '".$valor."')");
                        $conexion->Execute("DELETE FROM contactos_descargan WHERE (id_contacto = '".$valor."')");
                        $conexion->Execute("DELETE FROM contactos_pertenecen WHERE (id_contacto = '".$valor."')");
                        $conexion->Execute("DELETE FROM contactos_reciben WHERE (id_contacto = '".$valor."')");
                        //eliminar el registro
                        $conexion->Execute("DELETE FROM contactos WHERE (id_contacto = '".$valor."')");
                    }
                    echo("{ \"estado\" : \"ok\" }");
                }
            }

            //	Cambiar estado
            if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //	verifico el estado que posee
                        $datos = $temp->GetRow("SELECT * FROM contactos WHERE (id_contacto = '".$valor."')");
                        if ($datos) {
                            if ($datos["ACTIVO"] == 0) {
                                $conexion->Query("UPDATE contactos SET activo = 1 WHERE (id_contacto = '".$valor."')");
                            } else {
                                if ($datos["ACTIVO"] == 1) {
                                    $conexion->Query("UPDATE contactos SET activo = 0 WHERE (id_contacto = '".$valor."')");
                                } else {
                                    $conexion->Query("UPDATE contactos SET activo = 1 WHERE (id_contacto = '".$valor."')");
                                }
                            }
                            $datos = $temp->GetRow("SELECT * FROM contactos WHERE (id_contacto = '".$valor."')");
                            if ($datos) {
                                echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
                            } else {
                                echo("{ \"estado\" : \"error\" }");
                            }
                        }
                    }
                }
            }

            //	Informacion de descargas realizadas por el Contacto
            if (isset($_GET['accion']) and ($_GET['accion'] == "infocontactodescargas")) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("select * FROM contactos WHERE (id_contacto = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    ?>
					<form method="post" action="">	
						<div class="tabbable">
							<h3><?php echo("[".$registros->fields["ID_CONTACTO"]."] ".$registros->fields["EMAIL"]); ?></h3>
							<ul class="nav nav-tabs">
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Ver datos del Contacto</a></li>
								<li class="active"><a href="#tab01" data-toggle="tab">Descargas realizadas por el Contacto</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoenvios&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Envios recibidos</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoaccesos&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Accesos realizados</a></li>
							</ul>
							
							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<?php
										showQuery(
											$conexion,
											"SELECT boletin.TITULO, archivos.NOMBRE_ARCHIVO, archivos.COMENTARIO,contactos_descargan.FECHA,contactos_descargan.HORA FROM contactos_descargan LEFT JOIN archivos ON archivos.id_archivo = contactos_descargan.id_archivo LEFT JOIN boletin ON boletin.id_boletin = archivos.id_boletin WHERE (id_contacto = '".$registros->fields["ID_CONTACTO"]."') ORDER BY contactos_descargan.FECHA DESC, contactos_descargan.HORA DESC",
											array(
												"TITULO"=>"Boletin",
												"NOMBRE_ARCHIVO"=>"Archivo",
												"COMENTARIO"=>"Descripci&oacute;n",
												"FECHA"=>"Fecha",
												"HORA"=>"Hora"
											),
											$cantidad_filas_x_pagina,
											true
										); 
									?>
								</div>

							</div>
						</div>
					</form>
					<?php
                    $registros->MoveNext();
                }
            }

            //	Informacion de los envios recibidos por el Contacto
            if (isset($_GET['accion']) and ($_GET['accion'] == "infocontactoenvios")) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("select * FROM contactos WHERE (id_contacto = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    ?>
					<form method="post" action="">	
						<div class="tabbable">
							<h3><?php echo("[".$registros->fields["ID_CONTACTO"]."] ".$registros->fields["EMAIL"]); ?></h3>
							<ul class="nav nav-tabs">
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Ver datos del Contacto</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactodescargas&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Descargas realizados</a></li>
								<li class="active"><a href="#tab01" data-toggle="tab">Envios recibidos</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoaccesos&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Accesos realizados</a></li>
							</ul>
							
							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<?php
										showQuery(
											$conexion,
											"SELECT date(fechahora) as FECHA, time(fechahora) as HORA,boletin.TITULO FROM contactos_reciben LEFT JOIN boletin ON boletin.id_boletin = contactos_reciben.id_boletin WHERE (id_contacto = '".$registros->fields["ID_CONTACTO"]."') ORDER BY fechahora DESC",
											array(
												"TITULO"=>"Boletin",
												"FECHA"=>"Fecha",
												"HORA"=>"Hora"
											),
											$cantidad_filas_x_pagina,
											true
										); 
									?>
								</div>

							</div>
						</div>
					</form>
					<?php
                    $registros->MoveNext();
                }
            }

            //	Informacion de los accesos realizados por el Contacto
            if (isset($_GET['accion']) and ($_GET['accion'] == "infocontactoaccesos")) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("select * FROM contactos WHERE (id_contacto = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    ?>
					<form method="post" action="">	
						<div class="tabbable">
							<h3><?php echo("[".$registros->fields["ID_CONTACTO"]."] ".$registros->fields["EMAIL"]); ?></h3>
							<ul class="nav nav-tabs">
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Ver datos del Contacto</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactodescargas&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Descargas realizados</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=infocontactoenvios&registro[]=".$registros->fields["ID_CONTACTO"]); ?>">Envios recibidos</a></li>
								<li class="active"><a href="#tab01" data-toggle="tab">Accesos realizados por el Contacto</a></li>
							</ul>
							
							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<?php
										showQuery(
											$conexion,
											"SELECT boletin.TITULO, contactos_acceden.FECHA,contactos_acceden.HORA FROM contactos_acceden  LEFT JOIN boletin ON boletin.id_boletin = contactos_acceden.id_boletin WHERE (id_contacto = '".$registros->fields["ID_CONTACTO"]."') ORDER BY contactos_acceden.FECHA DESC, contactos_acceden.HORA DESC",
											array(
												"TITULO"=>"Boletin",
												"FECHA"=>"Fecha",
												"HORA"=>"Hora"
											),
											$cantidad_filas_x_pagina,
											true
										); 
									?>
								</div>
							</div>
						</div>
					</form>
					<?php
                    $registros->MoveNext();
                }
            }

            //Opciones para Actualizar contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "actualizar")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=update"); ?>" method="post" enctype="multipart/form-data" class="form-horizontal" style="width: 50%;margin: 0 auto;">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Origenes de datos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<p>Seleccionar el origen de datos para actualizar los Contactos:</p>
								<div class="control-group">
									<label class="control-label">&nbsp;</label>
									<div class="controls">
										<label class="checkbox"><input type="checkbox" name="origenes[]" value="consultas" checked="checked" />Consutlas</label>
										<label class="checkbox"><input type="checkbox" name="origenes[]" value="comentarios" checked="checked" />Comentarios</label>
										<label class="checkbox"><input type="checkbox" name="origenes[]" value="usuarios" checked="checked" />Usuarios</label>
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Actualizar" onclick="return confirm('Se van ha actualizar los datos de los origenes seleccionados.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Actualizar contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "update")) {
                ?>
				<form class="form-alert">
					<h3>Resultados</h3>
					<div class="body-alert">
						<table id="listado" class="table table-condensed">
						<thead>
						<tr>
							<th>Origen de datos</th>
							<th width="60">Insertados</th>
							<th width="60">Actualizados</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$ti = 0;
					$ta = 0;
					foreach ($_POST['origenes'] as $key=>$val) {
						switch ($val) {
							case "consultas":
								?>
						<tr>
							<td><?php echo(ucfirst($val)); ?></td>
									<?php
									$ci = 0;
									$ca = 0;
									$nro_grupo = 0;
									$cantidad_grupo = 0;
									$grupo = ucfirst($val);

									$registros = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = '".ucfirst($val)."') ORDER BY fecha_alta");
									if ($registros) {
										while (!$registros->EOF) {
											$nro_grupo = sprintf("%02s", $nro_grupo);

											//	verifico si el CONTACTO ya fue agregado
											$registros2 = $temp->Query("SELECT * FROM contactos WHERE (email = '".$registros->fields["EMAIL"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");
											if ($registros2->RecordCount() > 0) {
												while (!$registros2->EOF) {
													//	armo la sentencia UPDATE
													$update = "UPDATE contactos SET nombre = '".$registros->fields["NOMBRE"]."', nombre_completo = '".$registros->fields["NOMBRE"]."', email = '".$registros->fields["EMAIL"]."', grupo = '".$grupo.$nro_grupo."', id_sucursal = '".$_SESSION['usuario_sucursal']."' WHERE (email = '".$registros->fields["EMAIL"]."')";
													$temp2->Query($update);
													++$ca;
													$registros2->MoveNext();
												}	# while
											} else {
												//	armo la sentencia INSERT
												$insert = "INSERT contactos (ID_CONTACTO,NOMBRE,NOMBRE_COMPLETO,EMAIL,ID_ORIGEN,GRUPO,ID_SUCURSAL) VALUES(null,'".$registros->fields["NOMBRE"]."','".$registros->fields["NOMBRE"]."','".$registros->fields["EMAIL"]."','".$registros->fields["ID_MENSAJE"]."','".$grupo.$nro_grupo."','".$_SESSION['usuario_sucursal']."');";
												$temp2->Query($insert);
												++$ci;
											}	#if

											++$cantidad_grupo;
											if ($cantidad_grupo > 139) {
												$cantidad_grupo = 0;
												++$nro_grupo;
											}

											$registros->MoveNext();
										}	#while
									}
									$ti += $ci;
									$ta += $ca;
									?>
							<td style="text-align: right;"><?php echo($ci); ?></td>
							<td style="text-align: right;"><?php echo($ca); ?></td>
						</tr>
								<?php
								break;
							case "comentarios":
								?>
						<tr>
							<td><?php echo(ucfirst($val)); ?></td>
									<?php
									$ci = 0;
									$ca = 0;
									$nro_grupo = 0;
									$cantidad_grupo = 0;
									$grupo = ucfirst($val);

									$registros = $conexion->Query("SELECT * FROM mensajes WHERE (tipo_mensaje = '".ucfirst($val)."') ORDER BY fecha_alta");
									if ($registros) {
										while (!$registros->EOF) {
											$nro_grupo = sprintf("%02s", $nro_grupo);

											//	verifico si el CONTACTO ya fue agregado
											$registros2 = $temp->Query("SELECT * FROM contactos WHERE (email = '".$registros->fields["EMAIL"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");
											if ($registros2->RecordCount() > 0) {
												while (!$registros2->EOF) {
													//	armo la sentencia UPDATE
													$update = "UPDATE contactos SET nombre = '".$registros->fields["NOMBRE"]."', nombre_completo = '".$registros->fields["NOMBRE"]."', email = '".$registros->fields["EMAIL"]."', grupo = '".$grupo.$nro_grupo."', (id_sucursal = '".$_SESSION['usuario_sucursal']."') WHERE (email = '".$registros->fields["EMAIL"]."')";
													$temp2->Query($update);
													++$ca;
													$registros2->MoveNext();
												}	# while
											} else {
												//	armo la sentencia INSERT
												$insert = "INSERT contactos (ID_CONTACTO,NOMBRE,NOMBRE_COMPLETO,EMAIL,ID_ORIGEN,GRUPO,ID_SUCURSAL) VALUES(null,'".$registros->fields["NOMBRE"]."','".$registros->fields["NOMBRE"]."','".$registros->fields["EMAIL"]."','".$registros->fields["ID_MENSAJE"]."','".$grupo.$nro_grupo."','".$_SESSION['usuario_sucursal']."');";
												$temp2->Query($insert);
												++$ci;
											}	#if

											++$cantidad_grupo;
											if ($cantidad_grupo > 139) {
												$cantidad_grupo = 0;
												++$nro_grupo;
											}

											$registros->MoveNext();
										}	#while
									}
									$ti += $ci;
									$ta += $ca;
									?>
							<td style="text-align: right;"><?php echo($ci); ?></td>
							<td style="text-align: right;"><?php echo($ca); ?></td>
						</tr>
								<?php
								break;
							case "usuarios":
								?>
						<tr>
							<td><?php echo(ucfirst($val)); ?></td>
									<?php
									$ci = 0;
									$ca = 0;
									$nro_grupo = 0;
									$cantidad_grupo = 0;
									$grupo = ucfirst($val);

									$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (email is not null)");
									if ($registros) {
										while (!$registros->EOF) {
											$nro_grupo = sprintf("%02s", $nro_grupo);

											//	verifico si el USUARIO ya fue agregado
											$registros2 = $temp->Query("SELECT * FROM contactos WHERE (email = '".$registros->fields["EMAIL"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");
											if ($registros2->RecordCount() > 0) {
												while (!$registros2->EOF) {
													//	si existe actualizo sus datos: armo la sentencia UPDATE
													if ($registros->fields["CODIGO_PAIS"] == null) {
														$update = "UPDATE contactos SET tipo_doc = '".$registros->fields["TIPO_DOC"]."', nro_doc = '".$registros->fields["NRO_DOC"]."', nombre = '".addslashes($registros->fields["NOMBRE"])."', apellidos = '".addslashes($registros->fields["APELLIDOS"])."', nombre_completo = '".addslashes($registros->fields["NOMBRE_COMPLETO"])."', sexo = '".$registros->fields["SEXO"]."', domicilio = '".addslashes($registros->fields["DOMICILIO"])."', telefono_particular = '".$registros->fields["TELEFONO"]."', telefono_movil = '".$registros->fields["TELEFONO_MOVIL"]."', ciudad = '".addslashes($registros->fields["CIUDAD"])."', email = '".$registros->fields["EMAIL"]."', profesion = '".addslashes($registros->fields["PROFESION"])."', nivel_academico = '".$registros->fields["NIVEL_ACADEMICO"]."', grupo = '".$grupo.$nro_grupo."', activo = '".$registros->fields["ACTIVO"]."', id_sucursal = '".$_SESSION['usuario_sucursal']."' WHERE (email = '".$registros->fields["EMAIL"]."')";
													} else {
														$update = "UPDATE contactos SET tipo_doc = '".$registros->fields["TIPO_DOC"]."', nro_doc = '".$registros->fields["NRO_DOC"]."', nombre = '".addslashes($registros->fields["NOMBRE"])."', apellidos = '".addslashes($registros->fields["APELLIDOS"])."', nombre_completo = '".addslashes($registros->fields["NOMBRE_COMPLETO"])."', sexo = '".$registros->fields["SEXO"]."', domicilio = '".addslashes($registros->fields["DOMICILIO"])."', telefono_particular = '".$registros->fields["TELEFONO"]."', telefono_movil = '".$registros->fields["TELEFONO_MOVIL"]."', ciudad = '".addslashes($registros->fields["CIUDAD"])."', codigo_pais = '".$registros->fields["CODIGO_PAIS"]."', email = '".$registros->fields["EMAIL"]."', profesion = '".addslashes($registros->fields["PROFESION"])."', nivel_academico = '".$registros->fields["NIVEL_ACADEMICO"]."', grupo = '".$grupo.$nro_grupo."', activo = '".$registros->fields["ACTIVO"]."', id_sucursal = '".$_SESSION['usuario_sucursal']."' WHERE (email = '".$registros->fields["EMAIL"]."')";
													}	#if
													$temp2->Query($update);
													++$ca;
													$registros2->MoveNext();
												}	#while
											} else {
												//	armo la sentencia INSERT
												if ($registros->fields["CODIGO_PAIS"] == null) {
													$insert = "INSERT contactos (ID_CONTACTO,TIPO_DOC,NRO_DOC,NOMBRE,APELLIDOS,NOMBRE_COMPLETO,SEXO,DOMICILIO,TELEFONO_PARTICULAR,TELEFONO_MOVIL,CIUDAD,EMAIL,PROFESION,NIVEL_ACADEMICO,GRUPO,ID_ORIGEN,ACTIVO,ID_SUCURSAL) VALUES(null,'".$registros->fields["TIPO_DOC"]."','".$registros->fields["NRO_DOC"]."','".addslashes($registros->fields["NOMBRE"])."','".addslashes($registros->fields["APELLIDOS"])."','".addslashes($registros->fields["NOMBRE_COMPLETO"])."','".$registros->fields["SEXO"]."','".addslashes($registros->fields["DOMICILIO"])."','".$registros->fields["TELEFONO"]."','".$registros->fields["TELEFONO_MOVIL"]."','".addslashes($registros->fields["CIUDAD"])."','".$registros->fields["EMAIL"]."','".addslashes($registros->fields["PROFESION"])."','".$registros->fields["NIVEL_ACADEMICO"]."','".$grupo.$nro_grupo."','".$registros->fields["ID_USUARIO"]."','".$registros->fields["ACTIVO"]."','".$_SESSION['usuario_sucursal']."');";
												} else {
													$insert = "INSERT contactos (ID_CONTACTO,TIPO_DOC,NRO_DOC,NOMBRE,APELLIDOS,NOMBRE_COMPLETO,SEXO,DOMICILIO,TELEFONO_PARTICULAR,TELEFONO_MOVIL,CIUDAD,CODIGO_PAIS,EMAIL,PROFESION,NIVEL_ACADEMICO,GRUPO,ID_ORIGEN,ACTIVO,ID_SUCURSAL) VALUES(null,'".$registros->fields["TIPO_DOC"]."','".$registros->fields["NRO_DOC"]."','".addslashes($registros->fields["NOMBRE"])."','".addslashes($registros->fields["APELLIDOS"])."','".addslashes($registros->fields["NOMBRE_COMPLETO"])."','".$registros->fields["SEXO"]."','".addslashes($registros->fields["DOMICILIO"])."','".$registros->fields["TELEFONO"]."','".$registros->fields["TELEFONO_MOVIL"]."','".addslashes($registros->fields["CIUDAD"])."','".$registros->fields["CODIGO_PAIS"]."','".$registros->fields["EMAIL"]."','".addslashes($registros->fields["PROFESION"])."','".$registros->fields["NIVEL_ACADEMICO"]."','".$grupo.$nro_grupo."','".$registros->fields["ID_USUARIO"]."','".$registros->fields["ACTIVO"]."','".$_SESSION['usuario_sucursal']."');";
												}
												$temp2->Query($insert);
												++$ci;
											}	#if

											++$cantidad_grupo;
											if ($cantidad_grupo > 139) {
												$cantidad_grupo = 0;
												++$nro_grupo;
											}

											$registros->MoveNext();
										}	#while
									}
									$ti += $ci;
									$ta += $ca;
									?>
							<td style="text-align: right;"><?php echo($ci); ?></td>
							<td style="text-align: right;"><?php echo($ca); ?></td>
						</tr>
								<?php
								break;
							}
					} 
					?>
						<tr>
							<th style="text-align: right;">TOTAL</th>
							<th style="text-align: right;"><?php echo($ti); ?></th>
							<th style="text-align: right;"><?php echo($ta); ?></th>
						</tr>
						</tbody>
						</table>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
				<?php
            }

            //Importar contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "importar")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=upload"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 330px;">
								<div class="control-group">
									<label class="control-label">Grupo</label>
									<div class="controls">
										<input type="text" id="grupo" name="grupo" maxlength="255" size="50" title="Grupo" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Archivo</label>
									<div class="controls">
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
										<input type="file" name="importar_archivo" title="Archivo" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Separador de campos</label>
									<div class="controls">
										<select id="delimitador" name="delimitador" title="Separador de Campos" required>
											<option value=""></option>
											<option value="," selected="selected">, (coma)</option>
											<option value=";">; (punto y coma)</option>
										</select>
									</div>
								</div>

								<h2>Campos</h2>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Datos Personales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="tipo_doc" /><span>Tipo de Documento</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nro_doc" /><span>Nro. de Documento</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nombre" /><span>Nombre</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="apellidos" /><span>Apellidos</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nombre_completo" /><span>Nombres y Apellidos</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="email" checked="checked" /><span>Correo Electr&oacute;nico  <span class="label label-important">[obligatorio]</span></span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="sexo" /><span>Sexo [M/F]</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="domicilio" /><span>Domicilio</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_particular" /><span>Tel&eacute;fono Particular</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_movil" /><span>Tel&eacute;fono Movil</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="ciudad" /><span>Ciudad</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="codigo_pais" /><span>Pa&iacute;s [ar, bo, cl, ...]</span></label>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Datos Profesionales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="profesion" /><span>Profesi&oacute;n</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nivel_academico" /><span>Nivel Acad&eacute;mico</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="area_estudio" /><span>&Aacute;rea de Estudio</span></label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Datos Laborales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="institucion_trabaja" /><span>Intituci&oacute;n</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="cargo_trabaja" /><span>Cargo</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="domicilio_trabaja" /><span>Domicilio</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_trabaja" /><span>Tel&eacute;fono</span></label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Otros datos</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="areas_interes" /><span>&Aacute;reas de Interes</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="grupo" /><span>Grupo</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="comentarios" /><span>Comentarios</span></label>
											</div>
										</div>
									</div>
								</div>

								<h2>Recomendaciones</h2>
								<div> 
									<p><strong>Tipo de Archivo:</strong> debe ser de tipo &quot;Archivo de texto (valores separados por comas)&quot; o &quot;CSV (delimitado por comas)&quot;, y  su extensi&oacute;n puede ser TXT o CSV.</p>
									<p>En cualquiera de los casos de origen de los datos, no debe contener l&iacute;neas en blanco ni al principio ni al final del archivo.</p>
									<p><strong>Separador de Campos:</strong> representa el delimitador o separador de cada campo a importar.</p>
									<ul>
										<li>Si se exporta desde Microsoft Outlook el separador de campos es la coma (,) y se debe eliminar la primer l&iacute;nea del archivo (los nombre de campos), antes de importar.</li>
										<li>Si se exporta desde Microsoft Excel el separador es el punto y coma (;)</li>
									</ul>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Importar" onclick="return confirm('Se van ha importar los datos del archivo seleccionado.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Guardo y Proceso el archivo importado
            if (isset($_GET['accion']) and ($_GET['accion'] == "upload")) {
                ?>
				<form method="post" action="" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Importando datos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<?php
								if (isset($_POST['campo']) and isset($_FILES['importar_archivo'])) {
									?>
								<div class="control-group">
									<label class="control-label">Grupo</label>
									<div class="controls">
										<input type="text" value="<?php echo($_POST['grupo']); ?>" />				
									</div>
								</div>
									<?php
									//	verifico los campos seleccionados
									$campos_seleccionados = "";
									if (isset($_POST['campo'])) {
										$cantidad = count($_POST['campo']);
										foreach ($_POST['campo'] as $key => $val) {
											$campos_seleccionados .= $val;
											if (($key + 1) < $cantidad) {
												$campos_seleccionados .= ",";
											}
										}
									} ?>
								<div class="control-group">
									<label class="control-label">Campos seleccionados</label>
									<div class="controls">
										<input type="text" value="<?php echo($campos_seleccionados); ?>" />
									</div>
								</div>
									<?php
									//	verifico si se selecciono algun archivo
									if (is_uploaded_file($_FILES['importar_archivo']['tmp_name'])) {
										//obtiene el nombre del archivo y su extension
										$res = explode(".", $_FILES['importar_archivo']['name']);
										$tipo_archivo = $_FILES['importar_archivo']['type'];
										$extension = $res[count($res) - 1];
										$nombre = date("dmY_His");
										$nombre_completo = $nombre.".".$extension; ?>
								<div class="control-group">
									<label class="control-label">Archivo
										<a href="<?php echo("../../".$path_archivos."contactos/".$nombre_completo); ?>" target="_blank"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a>
									</label>
									<div class="controls">
										<input type="text" value="<?php echo($_FILES['importar_archivo']['name']); ?>" />
									</div>
								</div>
										<?php

										copy($_FILES['importar_archivo']['tmp_name'], stripslashes("../../".$path_archivos."contactos/".$nombre_completo));

										//	intenta copiar el archivo cargado a la carpeta CONTACTOS
										if (!copy($_FILES['importar_archivo']['tmp_name'], stripslashes("../../".$path_archivos."contactos/".$nombre_completo)) or ($_FILES['importar_archivo']['size'] > 2097152)) {
											?>
								<p>No se ha podido cargar el archivo: <strong><?php echo($_FILES['importar_archivo']['name']); ?></strong> por alguna de las siguientes razones:</p>
								<ul>
									<li>El nombre del archivo posee caracteres especiales.</li>
									<li>El tama&ntilde;o del archivo es superior a 8 Mbyte.</li>
								</ul>
											<?php
										} else {
											//	verifico si hay separador de campos seleccionado
											if (isset($_POST['delimitador']) and ($_POST['delimitador'] != null)) {
												$fieldseparator = $_POST['delimitador'];
											} else {
												$fieldseparator = ",";
											} ?>
								<div class="control-group">
									<label class="control-label">Separador de campos</label>
									<div class="controls">
										<input type="text" value="<?php echo($fieldseparator); ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Datos</label>
									<div class="controls">
										<ol>
											<?php
											$lineseparator = "\n";
											$csvfile = "../../".$path_archivos."contactos/".$nombre_completo;
											$file = fopen($csvfile, "r");
											if (!$file) {
												?>
								<p><strong><?php echo($csvfile); ?></strong>: Error al abrir el archivo de datos.</p>
												<?php
												exit;
											}
											$size = filesize($csvfile);
											if (!$size) {
												?>
								<p><strong><?php echo($csvfile); ?></strong>: Archivo vacio.</p>
												<?php
												exit;
											}
											$csvcontent = fread($file, $size);
											fclose($file);

											$ci = 0;
											$lines = 0;
											$queries = "";
											$linearray = array();
											//	variables para armar grupos de 140 contactos cada uno
											$ci_grupo = 0;
											$nro_grupo = 0;
											foreach (explode($lineseparator, $csvcontent) as $line) {
												$nro_grupo = sprintf("%03s", $nro_grupo);

												$lines++;
												$line = trim($line, " \t");
												$line = str_replace("\r", "", $line);
												$line = str_replace("'", "\'", $line);
												$linearray = explode($fieldseparator, $line);
												$linemysql = implode("','", $linearray);
												if ($linemysql == "") {
													?>
								<p><strong><?php echo($csvfile); ?></strong>: Error al abrir el archivo, datos o campos incorrectos.</p>
													<?php
													exit;
												}
												//	obtengo el email
												foreach ($linearray as $key => $val) {
													if (strpos($val, "@")) {
														$email = $val;
													}
												}
												$query = "";
												//	verifico si la cuenta de correo electr&oacute;nico existe
												$registros = $temp->Query("SELECT * FROM contactos WHERE (email = '".$email."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
												if ($registros->RecordCount() == 0) {
													//sino existe el email, inserto los datos
													if (isset($_POST['grupo']) and ($_POST['grupo'] != null)) {
														$query = "INSERT INTO contactos (ID_CONTACTO,$campos_seleccionados,GRUPO,ACTIVO,ID_SUCURSAL) values(null,'$linemysql','".$_POST['grupo'].$nro_grupo."','1','".$_SESSION['usuario_sucursal']."');";
													} else {
														$query = "INSERT INTO contactos (ID_CONTACTO,$campos_seleccionados,ACTIVO,ID_SUCURSAL) values(null,'$linemysql','1','".$_SESSION['usuario_sucursal']."');";
													}
												} else {
													echo("<li style='margin-left: 20px;'><strong style='color: #ff0000;'>".$email."</strong> ya existe.</li>");
												}
												if ($query != "") {
													$conexion->Query($query);
													++$ci;
													++$ci_grupo;
													if ($ci_grupo > 139) {
														$ci_grupo = 0;
														++$nro_grupo;
													} ?>
										<li style='margin-left: 20px;'><?php echo($linemysql); ?></li>
													<?php
												}
											}	#foreach ?>
										</ol>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Resultado</label>
									<div class="controls">
										<p>Insertado <strong><?php echo($ci); ?></strong> registros.</p>
									</div>
								</div>
										<?php
										}
									}	#if
								} else {
									?>
								<p>Debe seleccionar los campos contenidos en el archivo a importar.</p>
									<?php
								}	# if ?>
							</div>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Exportar contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "exportar")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=download"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Tipo de Archivo</label>
									<div class="controls">
										<select id="tipo_archivo" name="tipo_archivo" title="Tipo de Archivo" style="width: 50%;" required>
											<option value=""></option>
											<option value="txt">Archivo de Texto, delimitado por comas (*.TXT)</option>
											<option value="cvs">Archivo delimitado por puntos y comas (*.CVS)</option>
											<option value="xls">Microsoft Excel (*.XLS)</option>
											<option value="pdf">Adobe Acrobat (*.PDF)</option>
											<option value="sql">SQL (*.SQL)</option>
										</select>
									</div>
								</div>

								<h2>Campos</h2>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Datos Personales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="tipo_doc" /><span>Tipo de Documento</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nro_doc" /><span>Nro. de Documento</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nombre" /><span>Nombre</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="apellidos" /><span>Apellidos</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nombre_completo" /><span>Nombres y Apellidos</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="email" checked="checked" /><span>Correo Electr&oacute;nico  <span class="label label-important">[obligatorio]</span></span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="sexo" /><span>Sexo [M/F]</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="domicilio" /><span>Domicilio</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_particular" /><span>Tel&eacute;fono Particular</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_movil" /><span>Tel&eacute;fono Movil</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="ciudad" /><span>Ciudad</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="codigo_pais" /><span>Pa&iacute;s [ar, bo, cl, ...]</span></label>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Datos Profesionales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="profesion" /><span>Profesi&oacute;n</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="nivel_academico" /><span>Nivel Acad&eacute;mico</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="area_estudio" /><span>&Aacute;rea de Estudio</span></label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Datos Laborales</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="institucion_trabaja" /><span>Intituci&oacute;n</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="cargo_trabaja" /><span>Cargo</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="domicilio_trabaja" /><span>Domicilio</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="telefono_trabaja" /><span>Tel&eacute;fono</span></label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Otros datos</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="campo[]" value="areas_interes" /><span>&Aacute;reas de Interes</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="grupo" /><span>Grupo</span></label>
												<label class="checkbox"><input type="checkbox" name="campo[]" value="comentarios" /><span>Comentarios</span></label>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Exportar" onclick="return confirm('Se van ha exportar los campos seleccionados de Contactos.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Guardo y Proceso el archivo importado
            if (isset($_GET['accion']) and ($_GET['accion'] == "download")) {
                ?>
				<form method="post" action="" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Exportando datos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Tipo de Archivo</label>
									<div class="controls">
										<input type="text" value="<?php echo($_POST['tipo_archivo']); ?>" />				
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Descargar</label>
									<div class="controls">
										<input type="button" value="clic aqui" class="btn success" />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Re-agrupar contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "reagrupar")) {
                ?>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reagrupargrupo"); ?>" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Grupo a re-agrupar</label>
									<div class="controls">
										<select name="grupo" title="Grupo a re-agrupar" required>
											<option value=""></option>
											<?php
												$registros2 = $temp->Query("SELECT *, count(*) as Cant FROM contactos WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') GROUP BY grupo ORDER BY grupo");
												while (!$registros2->EOF) {
													?>
											<option value="<?php echo($registros2->fields["GRUPO"]); ?>"><?php echo($registros2->fields["GRUPO"]." [".$registros2->fields["Cant"]."]"); ?></option>
													<?php
													$registros2->MoveNext();
												} 
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nuevo nombre al Grupo</label>
									<div class="controls">
										<input type="text" id="grupo" name="nombre" title="Nuevo nombre al Grupo" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Cantidad por sub-grupo</label>
									<div class="controls">
										<input type="text" name="cantidad" class="input-mini" title="Cantidad por grupo" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Re-agrupar" onclick="return confirm('Se van ha re-agrupar los registro del grupo seleccionado.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Re-agrupar contactos, segun el grupo seleccionado
            if (isset($_GET['accion']) and ($_GET['accion'] == "reagrupargrupo")) {
                ?>
				<form method="post" action="" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Re-agrupando registros</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Grupo a re-agrupar</label>
									<div class="controls">
										<input type="text" value="<?php echo($_POST['grupo']); ?>" readonly="readonly" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nuevo nombre al Grupo</label>
									<div class="controls">
										<input type="text" value="<?php echo($_POST['nombre']); ?>" readonly="readonly" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Cantidad por sub-grupo</label>
									<div class="controls">
										<input type="text" value="<?php echo($_POST['cantidad']); ?>" readonly="readonly" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Grupos generados</label>
									<div class="controls">
										<ol>
											<?php
												$registros = $conexion->Query("SELECT * FROM contactos WHERE (grupo = '".$_POST['grupo']."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");
												if ($registros) {
													$contador_registro = 0;
													$contador_subgrupo = 0;
													while (!$registros->EOF) {
														$nro_subgrupo = sprintf("%03s", $contador_subgrupo);

														$temp->Query("UPDATE contactos SET grupo = '".$_POST['nombre'].$nro_subgrupo."' WHERE (id_contacto = '".$registros->fields["ID_CONTACTO"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."') ");

														++$contador_registro;
														if ($contador_registro >= $_POST['cantidad']) {
															?>
											<li><?php echo($_POST['nombre'].$nro_subgrupo); ?></li>
															<?php
															$contador_registro = 0;
															++$contador_subgrupo;
														}
														$registros->MoveNext();
													}
												} 
											?>
											<li><?php echo($_POST['nombre'].$nro_subgrupo); ?></li>
										</ol>
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="button" class="btn btn-large" value="Re-agrupar otra vez" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reagrupar"); ?>'" />
							<input type="button" class="btn btn-large btn-primary" value="Aceptar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }
        }
        //	-----	Fin CONTACTOS

        //	-----	GRUPOS
        if (isset($_GET['menu']) and ($_GET['menu'] == "grupos")) {
            //Listado
            if (!isset($_GET['accion'])) {
                $query = "SELECT grupos.ID_GRUPO,grupos.NOMBRE,count(ID_CONTACTO) AS Cant FROM grupos LEFT JOIN contactos_pertenecen ON contactos_pertenecen.id_grupo = grupos.id_grupo ";
                if (isset($_GET['texto'])) {
                    $query .= " WHERE ( (grupos.ID_GRUPO LIKE '%".$_GET['texto']."%') or (upper(nombre) LIKE upper('%".$_GET['texto']."%')) ) ";
                }
                $query .= "GROUP BY id_grupo ORDER BY grupos.NOMBRE";

                showTable2(
                    $conexion,
                    $query,
                    array("ID_GRUPO"=>"Id",
                        "NOMBRE"=>"Nombre",
                        "Cant"=>"Contactos"),
                    array(0=>"ID_GRUPO"),
                    $cantidad_filas_x_pagina,
                    true,
                    true,
                    $datos_acceso
                );
            }

            //Nuevo
            if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
                ?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="control-group">
									<label class="control-label">Nombre del Grupo</label>
									<div class="controls">
										<input type="text" name="nombre" maxlength="255" size="50" title="Nombre del Grupo" required />
									</div>
								</div>
								<div class="well" style="width: 99%;">
									<input type="button" class="btn" value="Ver todos" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&op=todos"); ?>';" />
									<select name="agrupados" onchange="AbrirURL(this);" title="Grupos de Contactos">
										<option value="">-- Grupos de Contactos</option>
										<?php
											$registros = $conexion->Query("SELECT *,count(*) as Cant FROM contactos WHERE (activo = 1) GROUP BY grupo ORDER BY grupo");
											while (!$registros->EOF) {
												?>
										<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&op=".$registros->fields["GRUPO"]); ?>" <?php if (isset($_GET['op']) and ($_GET['op'] == $registros->fields["GRUPO"])) { echo("selected=selected"); } ?>><?php echo($registros->fields["GRUPO"]." (".$registros->fields["Cant"].")"); ?></option>
												<?php
												$registros->MoveNext();
											} #while 
										?>
									</select>
									<input type="button" class="btn" value="Ver sin agrupar" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&op=sinagrupar"); ?>';" />
								</div>
								<table id="listado" class="table table-bordered table-condensed">
								<thead>
								<tr>
									<th style="width:15px;"><input type="checkbox" id="CheckTodo" name="CheckTodo" /></th>
									<th>Apellido y Nombre</th>
									<th>Correo Electr&oacute;nico</th>
									<th>Grupo</th>
								</tr>
								</thead>
								<?php
									if (isset($_GET['op'])) {
										if (isset($_GET['op']) and ($_GET['op'] == "todos")) {
											$registros = $conexion->Query("SELECT * FROM contactos WHERE (activo = 1) ORDER BY nombre_completo, email");
										} else {
											if (isset($_GET['op']) and ($_GET['op'] == "sinagrupar")) {
												$registros = $conexion->Query("SELECT * FROM contactos WHERE ((grupo = '') or (grupo is null)) and (activo = 1) ORDER BY nombre_completo, email");
											} else {
												$registros = $conexion->Query("SELECT * FROM contactos WHERE (grupo = '".$_GET['op']."') and (activo = 1) ORDER BY nombre_completo, email");
											}	#if
										}	#if

										if ($registros->RecordCount() > 0) {
											while (!$registros->EOF) {
												?>
								<tr class="unChecked">
									<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registros->fields["ID_CONTACTO"]); ?>" /></td>
									<td><?php echo($registros->fields["NOMBRE_COMPLETO"]); ?></td>
									<td><?php echo($registros->fields["EMAIL"]); ?></td>
									<td><?php echo($registros->fields["GRUPO"]); ?></td>
								</tr>
												<?php
												$registros->MoveNext();
											}	#while
										} else {
											?>
								<tr>
									<td colspan="4" style="text-align: center;"><h5>no hay registros</h5></td>
								</tr>
											<?php
										}	#if
									} else {
										?>
								<tr>
									<td colspan="4" style="text-align: center;"><h5>seleccionar grupos de contactos</h5></td>
								</tr>
										<?php
									}	#if 
								?>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va ha agregar un Grupo de Contactos con los registros seleccionados.\nEsta Ud. seguro?')" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
						</div>
					</div>
				</form>
				<?php
            }

            //Insertar
            if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
                if (isset($_POST['id_grupo'])) {
                    //	Update
                    $conexion->Execute("UPDATE grupos SET nombre = '".$_POST['nombre']."' WHERE (id_grupo = '".$_POST['id_grupo']."')");
                    //elimino los contactos anteriores del grupo
                    //$conexion->Query("DELETE FROM contactos_pertenece WHERE id_grupo = '".$_POST['id_grupo']."'");
                    $id = $_POST['id_grupo'];
                } else {
                    //	Insert
                    $conexion->Execute("INSERT grupos (ID_GRUPO,NOMBRE) VALUES(null,'".$_POST['nombre']."');");
                    $id = $conexion->Insert_ID();
                }
                //	Inserto los nuevo contactos del grupo
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //	verifico si ya esta asignado
                        $registros = $conexion->Query("SELECT * FROM contactos_pertenecen WHERE (id_grupo = '".$id."') and (id_contacto = '".$valor."')");
                        if ($registros and ($registros->RecordCount() > 0)) {
                            echo("- Contacto: ".$valor." ya asignado.<br />");
                        } else {
                            $conexion->Execute("INSERT contactos_pertenecen (ID_CONTACTO,ID_GRUPO) VALUES('".$valor."','".$id."');");
                        }
                    }
                }

                echo("<script type='text/javascript'>");
                echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."'");
                echo("</script>");
            }

            //Eliminar asignacion de registro
            if (isset($_GET['accion']) and ($_GET['accion'] == "deleteasignacion")) {
                $conexion->Query("DELETE FROM contactos_pertenecen WHERE (id_grupo = '".$_POST['idg']."') and (id_contacto = '".$_POST['idc']."')");
                echo("{ \"estado\" : \"ok\" }");
            }
            
            //Editar
            if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
                $id_modo_editar = $_GET['registro'][0];
                $registros = $conexion->Query("SELECT * FROM grupos WHERE (id_grupo = '".$id_modo_editar."')");
                while (!$registros->EOF) {
                    ?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" id="signup" class="form-horizontal">
						<div class="tabbable">
							<input type="hidden" name="id_grupo" value="<?php echo($registros->fields["ID_GRUPO"]); ?>" />
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="control-group">
										<label class="control-label">Nombre del Grupo</label>
										<div class="controls">
											<input type="text" name="nombre" value="<?php echo($registros->fields["NOMBRE"]); ?>" maxlength="255" size="50" title="Nombre del Grupo" required <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="well" style="width: 99%;">
										<input type="button" class="btn" value="Ver todos" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."&op=todos"); ?>';" />
										<select name="agrupados" onchange="AbrirURL(this);" title="Grupos de Contactos">
											<option value="">-- Grupos de Contactos</option>
											<?php
												$registros2 = $conexion->Query("SELECT *,count(*) as Cant FROM contactos WHERE (activo = 1) GROUP BY grupo ORDER BY grupo");
												while (!$registros2->EOF) {
													?>
											<option value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion']."&registro[]=".$_GET['registro'][0]."&op=".$registros2->fields["GRUPO"]); ?>" <?php if (isset($_GET['op']) and ($_GET['op'] == $registros2->fields["GRUPO"])) { echo("selected=selected"); } ?>><?php echo($registros2->fields["GRUPO"]." (".$registros2->fields["Cant"].")"); ?></option>
													<?php
													$registros2->MoveNext();
												} #while 
											?>
										</select>
										<input type="button" class="btn" value="Ver sin agrupar" onclick="window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&registro[]=".$_GET['registro'][0]."&accion=".$_GET['accion']."&op=sinagrupar"); ?>';" />
									</div>
									<table id="listado" class="table table-bordered table-condensed">
									<thead>
									<tr>
										<th style="width:15px;"><input type="checkbox" id="CheckTodo" name="CheckTodo" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/></th>
										<th style="width:15px;"></th>
										<th>Apellido y Nombre</th>
										<th>Correo Electr&oacute;nico</th>
										<th>Grupo</th>
									</tr>
									</thead>
									<tbody>
									<?php
										if (isset($_GET['op'])) {
											if (isset($_GET['op']) and ($_GET['op'] == "todos")) {
												$registros2 = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE (activo = 1) ORDER BY nombre_completo, email");
											} else {
												if (isset($_GET['op']) and ($_GET['op'] == "sinagrupar")) {
													$registros2 = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE ((grupo = '') or (grupo is null)) and (activo = 1) ORDER BY nombre_completo, email");
												} else {
													$registros2 = $conexion->Query("SELECT ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos WHERE (grupo = '".$_GET['op']."') and (activo = 1) ORDER BY nombre_completo, email");
												}	#if
											}	#if
										} else {
											$registros2 = $conexion->Query("SELECT contactos.ID_CONTACTO,NOMBRE_COMPLETO,EMAIL,GRUPO FROM contactos_pertenecen LEFT JOIN contactos ON contactos.id_contacto = contactos_pertenecen.id_contacto WHERE (contactos_pertenecen.id_grupo = '".$registros->fields["ID_GRUPO"]."') ORDER BY nombre_completo, email");
										}	#if

										if ($registros2->RecordCount() > 0) {
											while (!$registros2->EOF) {
												//	verifico si esta en el grupo
												$registros3 = $temp2->Query("SELECT * FROM contactos_pertenecen WHERE (id_grupo = '".$registros->fields["ID_GRUPO"]."') and (id_contacto = '".$registros2->fields["ID_CONTACTO"]."')"); ?>
												<tr <?php if ($registros3->RecordCount() > 0) {
													echo("class='Checked'");
												} else {
													echo("class='unChecked'");
												} ?>>
													<td><input type="checkbox" name="registro[]" value="<?php echo($registros2->fields["ID_CONTACTO"]); ?>" <?php if ($registros3->RecordCount() > 0) {
													echo("checked='checked'");
												} ?> <?php if ($_GET['accion'] == "ver") {
													echo("disabled='disabled'");
												} ?>/></td>
													<td>
													<?php if ($_GET['accion'] != "ver") { ?>
													<?php if ($registros3->RecordCount() > 0) { ?><a href="javascript:void(0);" id="btEliminarAsignacion" data-grupo="<?php echo($registros->fields["ID_GRUPO"]); ?>" data-contacto="<?php echo($registros2->fields["ID_CONTACTO"]); ?>"><img src="../images/eliminar.gif" title="Eliminar asignaci&oacute;n" align="absmiddle" /></a><?php } ?>
													<?php } ?>
													</td>
													<td><?php echo($registros2->fields["NOMBRE_COMPLETO"]); ?></td>
													<td><?php echo($registros2->fields["EMAIL"]); ?></td>
													<td><?php echo($registros2->fields["GRUPO"]); ?></td>
												</tr>
																<?php
																$registros2->MoveNext();
											}	#while
										} else {
											?>
												<tr>
													<td colspan="4" style="text-align: center;"><h5>no hay registros</h5></td>
												</tr>
											<?php
										}	#if 
									?>
									</tbody>
									</table>
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

            //Eliminar
            if (isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
                if (isset($_POST['registro'])) {
                    foreach ($_POST['registro'] as $valor) {
                        //elimino los contactos del grupo
                        $conexion->Query("DELETE FROM contactos_pertenecen WHERE (id_grupo = '".$valor."')");
                        $conexion->Query("DELETE FROM grupos WHERE (id_grupo = '".$valor."')");
                    }
                    echo("{ \"estado\" : \"ok\" }");
                }
            }
        }
        //	-----	Fin GRUPOS

        //	-----	REPORTES
        if (isset($_GET['menu']) and ($_GET['menu'] == "reportes")) {
            if (!isset($_GET['accion'])) {
                $_GET['accion'] = "reportesdescargas";
            }

            //	Reportes de descargas realizdas por los Contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "reportesdescargas")) {
                ?>
				<form method="post" action="">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Descargas realizadas</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesenvios"); ?>">Envios realizados</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesaccesos"); ?>">Accesos realizados</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
							<?php
								showQuery(
									$conexion,
									"SELECT archivos.NOMBRE_ARCHIVO,archivos.COMENTARIO,contactos.EMAIL,FECHA,HORA FROM contactos_descargan LEFT JOIN archivos ON archivos.id_archivo = contactos_descargan.id_archivo LEFT JOIN contactos ON contactos.id_contacto = contactos_descargan.id_contacto WHERE (contactos.id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY FECHA DESC",
									array(
										"NOMBRE_ARCHIVO"=>"Archivo",
										"COMENTARIO"=>"Descripci&oacute;n",
										"EMAIL"=>"Contacto",
										"FECHA"=>"Fecha",
										"HORA"=>"Hora"
									),
									$cantidad_filas_x_pagina,
									true
								); 
							?>
							</div>
						</div>
					</div>
				</form>
				<?php
            }

            //	Reporte de Envios realizados a los Contactos
            if (isset($_GET['accion']) and ($_GET['accion'] == "reportesenvios")) {
                ?>
				<form method="post" action="">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesdescargas"); ?>">Descargas realizadas</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Envios realizados</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesaccesos"); ?>">Accesos realizados</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
							<?php
								showQuery(
									$conexion,
									"SELECT boletin.TITULO,date(fechahora) as FE, time(fechahora) as HS, contactos.GRUPO, count(*) as Cant FROM contactos_reciben LEFT JOIN contactos ON contactos.id_contacto = contactos_reciben.id_contacto LEFT JOIN boletin ON boletin.id_boletin = contactos_reciben.id_boletin WHERE (boletin.id_sucursal = '".$_SESSION['usuario_sucursal']."') GROUP BY date(fechahora),grupo ORDER BY fechahora DESC",
									array(
										"TITULO"=>"Boletin",
										"FE"=>"Fecha",
										"HS"=>"Hora",
										"GRUPO"=>"Grupo",
										"Cant"=>"Contactos"
									),
									$cantidad_filas_x_pagina,
									true
								); 
							?>
							</div>
						</div>
					</div>
				</form>
				<?php
            }

            if (isset($_GET['accion']) and ($_GET['accion'] == "reportesaccesos")) {
                ?>
				<form method="post" action="">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesdescargas"); ?>">Descargas realizadas</a></li>
							<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=reportesenvios"); ?>">Envios realizados</a></li>
							<li class="active"><a href="#tab01" data-toggle="tab">Accesos realizados</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
							<?php
								showQuery(
									$conexion,
									"SELECT boletin.TITULO,contactos.EMAIL,contactos_acceden.FECHA,contactos_acceden.HORA FROM contactos_acceden LEFT JOIN boletin ON boletin.id_boletin = contactos_acceden.id_boletin LEFT JOIN contactos ON contactos.id_contacto = contactos_acceden.id_contacto WHERE (boletin.id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY fecha DESC",
									array(
										"TITULO"=>"Boletin",
										"EMAIL"=>"Contacto",
										"FECHA"=>"Fecha",
										"HORA"=>"Hora"),
									$cantidad_filas_x_pagina,
									true
								); 
							?>
							</div>

						</div>
					</div>
				</form>
				<?php
            }
        }
        //	-----	Fin REPORTES

        include("../footer.php");
        ?>
	</body>
</html>