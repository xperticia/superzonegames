<?php
//	XPANEL 3.0
//	Fecha actualizacion:	09/08/2023 23:00 p.m.

//ini_set('log_errors', 1);
//ini_set('error_log', './archivos/php_scripts_error.log');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!file_exists("xpanel/datos.php")) {
	header("Location: xpanel/modulos/install.php");
	exit;
}
require_once("xpanel/config.php");
//	armo la direccion completa de la plantilla html
if (($sitio_plantilla == "") or $sitio_estilo == "") {
	header("Location: xpanel/?error_login=12");
	exit;
}
$PlantillaHTML = $sitio_url . $path_plantillas . $sitio_plantilla;

//	obtengo la plantilla HTML
if (verificar_url($PlantillaHTML)) {
	//	armo la direccion completa del estilo CSS
	if (strpos($sitio_plantilla, "/") > 0) {
		$res = explode("/", $sitio_plantilla);
		$carpeta = $res[0] . "/";
		$EstiloHTML = $sitio_url . $path_plantillas . $carpeta . $sitio_estilo;
	} else {
		$carpeta = "/";
		$EstiloHTML = $sitio_url . $path_plantillas . $sitio_estilo;
	}
	$ContenidoHTML = obtenerDatosArchivoBK($PlantillaHTML);
	//	actualizo la direccion del estilo css
	$ContenidoHTML = str_replace($sitio_estilo, $EstiloHTML, $ContenidoHTML);
	//	actualizo los datos del sitio
	$ContenidoHTML = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_URL}", $sitio_url, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_EMAIL}", $sitio_email, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_ESLOGAN}", $sitio_eslogan, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_DIRECCION}", $sitio_direccion, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_TELEFONO}", $sitio_telefono, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_LOGO}", "<img src='" . $sitio_url . $sitio_logo_archivo . "' />", $ContenidoHTML);
	$ContenidoHTML = str_replace("{FECHA}", date("d/m/Y"), $ContenidoHTML);
	$ContenidoHTML = str_replace("{FECHAHORA}", date("H:i") . " del " . formato_fecha(date("Y/m/d"), 1), $ContenidoHTML);
	$ContenidoHTML = str_replace("{ESTILOCSS}", $EstiloHTML, $ContenidoHTML);

	$path = "";
	$categorias = "";
	$Secciones_Head_Horizontal = "";
	$Secciones_Head_Horizontal .= "<div class=\"nav-tabs-responsive\">";
	$Secciones_Head_Horizontal .= "<ul class=\"nav nav-tabs nav-justified\">";

	$Secciones_Head = "";
	if (isset($_GET['menu'])) {
		$Secciones_Head .= "<li><a href=\"./\">Inicio</a></li>";
		//$Secciones_Head_Horizontal .= "<li><a href=\"./\">Inicio</a></li>";
	} else {
		$Secciones_Head .= "<li class=\"active\"><a href=\"./\">Inicio</a></li>";
		//$Secciones_Head_Horizontal .= "<li class=\"active\"><a href=\"./\">Inicio</a></li>";
		$path .= " &raquo; Bienvenidos";
		include("xpanel/modulos/contador_add.php");
	}

	$secciones_Body = "";
	$secciones_Body .= "<div class=\"accordion\" id=\"accordion2\">";
	//	Obtengo todas las SECCIONES del sitio
	$query = "SELECT 
			*,
			SUBSTRING( URL,(INSTR(URL,'=')+1)) as MENU 
		FROM menu_modulos 
		WHERE 
			(categoria = 'Contenidos Web') 
			and (upper(nombre_menu) <> 'DIVISOR') 
		ORDER BY orden
	";
	$registros = $conexion->Query($query);
	if ($registros) {
		while (!$registros->EOF) {
			if (isset($_GET['menu']) and ($_GET['menu'] == $registros->fields["MENU"])) {
				$path = " &raquo; <a href='?menu=" . $_GET['menu'] . "'>" . $registros->fields["NOMBRE_MENU"] . "</a> <span>{PATH}</span>";
			}
			//	obtengo las SECCION de Contenido Web con contenido
			/*
				$tipo_contenido = str_replace("contenidos.php?menu=","",$registros->fields["URL"]);
				$registros2 = $conexion->Query("SELECT count(*) as CANTIDAD FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$tipo_contenido."') GROUP BY id_contenido");
				if($registros2) {
					if($registros2->RecordCount() > 0) {
						//	verifico en que SECCION estoy
						if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
							$Secciones_Head .= "<li class=\"active\"><a href=\"./?menu=".$registros->fields["MENU"]."\">".$registros->fields["NOMBRE_MENU"]."</a></li>";
						} else {
							$Secciones_Head .= "<li><a href=\"./?menu=".$registros->fields["MENU"]."\">".$registros->fields["NOMBRE_MENU"]."</a></li>";
						}
					}
				}
				*/

			//	obtengo un enlace a todos la CATEGORIAS del Contenido Web
			$tipo_contenido = str_replace("contenidos.php?menu=", "", $registros->fields["URL"]);
			$registros2 = $conexion->Query("SELECT CATEGORIA FROM contenidos WHERE (activo = 1) and (tipo_contenido = '" . $tipo_contenido . "') and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria ORDER BY categoria");
			if ($registros2) {
				if ($registros2->RecordCount() > 0) {
					//	verifico en que SECCION estoy
					if (isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
						$Secciones_Head .= "<li class=\"dropdown active\">";
					} else {
						$Secciones_Head .= "<li class=\"dropdown\">";
					}
					$Secciones_Head .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"><i class=\"icon-chevron-right\"></i>" . stripslashes($registros->fields["NOMBRE_MENU"]) . "</a>\n";

					$Secciones_Head .= "<ul class=\"dropdown-menu\" style=\"width: 100%;\">\n";
					if (!isset($_GET['c'])) {
						$Secciones_Head .= "<li class=\"active\"><a href=\"./?menu=novedades\">Ver todas las Novedades</a></li>\n";
					} else {
						$Secciones_Head .= "<li><a href=\"./?menu=novedades\">Ver todas las Novedades</a></li>\n";
					}
					$Secciones_Head .= "<li class=\"divider\"></li>\n";

					while (!$registros2->EOF) {
						if (isset($_GET['c']) and ($registros2->fields["CATEGORIA"] == $_GET['c'])) {
							$Secciones_Head .= "<li class=\"active\"><a href=\"./?menu=novedades&c=" . stripslashes($registros2->fields["CATEGORIA"]) . "\"><i class=\"icon-chevron-right\"></i>" . stripslashes($registros2->fields["CATEGORIA"]) . "</a></li>\n";
						} else {
							$Secciones_Head .= "<li><a href=\"./?menu=novedades&c=" . stripslashes($registros2->fields["CATEGORIA"]) . "\"><i class=\"icon-chevron-right\"></i>" . stripslashes($registros2->fields["CATEGORIA"]) . "</a></li>\n";
						}

						$registros2->MoveNext();
					}
					$registros2->close();

					$Secciones_Head .= "</ul>\n";
					$Secciones_Head .= "</li>";
				} else {
					//	verifico si hay FOTOGALERIA
					$tipo_contenido = "fotogaleria";
					if (strrpos($registros->fields["URL"], $tipo_contenido) > 0) {
						//	obtengo todas las CATEGORIAS
						$registros2 = $conexion->Query("SELECT CATEGORIA FROM fotogaleria WHERE (activo = 1) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria ORDER BY fecha_alta,categoria");
						if ($registros2) {
							if ($registros2->RecordCount() > 0) {
								//	verifico en que SECCION estoy
								if (isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
									$Secciones_Head .= "<li class=\"dropdown active\">";
								} else {
									$Secciones_Head .= "<li class=\"dropdown\">";
								}
								$Secciones_Head .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">" . stripslashes($registros->fields["NOMBRE_MENU"]) . "<b class=\"caret\"></b></a>\n";

								if (isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
									$secciones_Body .= "<div class=\"accordion-group\">";
									$secciones_Body .= "	<div class=\"accordion-heading\">";
									$secciones_Body .= "		<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion2\" href=\"#" . str_replace(" ", "", stripslashes($registros->fields["NOMBRE_MENU"])) . "\">" . stripslashes($registros->fields["NOMBRE_MENU"]) . "</a>";
									$secciones_Body .= "	</div>";
									$secciones_Body .= "	<div id=\"" . str_replace(" ", "", stripslashes($registros->fields["NOMBRE_MENU"])) . "\" class=\"accordion-body collapse in\">";
									$secciones_Body .= "		<div class=\"accordion-inner\">";
									$secciones_Body .= "			<ul class=\"nav nav-tabs nav-stacked\">";
								}

								$Secciones_Head .= "<ul class=\"dropdown-menu\">\n";
								while (!$registros2->EOF) {
									$Secciones_Head .= "<li><a href='./?menu=" . $tipo_contenido . "&cat=" . $registros2->fields["CATEGORIA"] . "'>" . stripslashes($registros2->fields["CATEGORIA"]) . "</a></li>\n";

									if (isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
										if (isset($_GET['cat']) and ($_GET['cat'] == $registros2->fields["CATEGORIA"])) {
											$secciones_Body .= "		<li class=\"active\"><a href='./?menu=" . $tipo_contenido . "&cat=" . $registros2->fields["CATEGORIA"] . "'>" . stripslashes($registros2->fields["CATEGORIA"]) . "</a></li>\n";
										} else {
											$secciones_Body .= "		<li><a href='./?menu=" . $tipo_contenido . "&cat=" . $registros2->fields["CATEGORIA"] . "'>" . stripslashes($registros2->fields["CATEGORIA"]) . "</a></li>\n";
										}
									}

									$registros2->MoveNext();
								}
								$registros2->close();

								if (isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
									$secciones_Body .= "			</ul>";
									$secciones_Body .= "		</div>";
									$secciones_Body .= "	</div>";
									$secciones_Body .= "</div>";
								}

								$Secciones_Head .= "</ul>\n";
								$Secciones_Head .= "</li>";
							}
						}
					}
				}
			}

			//	obtengo todas la CATEGORIAS y los 10 ultimos de Contenido Web
			/*
				$tipo_contenido = str_replace("contenidos.php?menu=","",$registros->fields["URL"]);
				$registros2 = $conexion->Query("SELECT CATEGORIA FROM contenidos WHERE (activo = 1) and (tipo_contenido = '".$tipo_contenido."') GROUP BY categoria ORDER BY categoria");
				if($registros2) {
					if($registros2->RecordCount() > 0) {
						//	verifico en que SECCION estoy
						if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
							$Secciones_Head .= "<li class=\"dropdown active\">";
						} else {
							$Secciones_Head .= "<li class=\"dropdown\">";
						}
						$Secciones_Head .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"><i class=\"icon-chevron-right\"></i>".stripslashes($registros->fields["NOMBRE_MENU"])."</a>\n";

						$Secciones_Head .= "<ul class=\"dropdown-menu\" style=\"width: 100%;\">\n";
						while(!$registros2->EOF) {
							$Secciones_Head .= "<li class=\"nav-header\">".stripslashes($registros2->fields["CATEGORIA"])."</li>\n";

							if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
								$secciones_Body .= "<div class=\"accordion-group\">";
								$secciones_Body .= "	<div class=\"accordion-heading\">";
								$secciones_Body .= "		<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion2\" href=\"#".str_replace(" ","",stripslashes($registros2->fields["CATEGORIA"]))."\">".stripslashes($registros2->fields["CATEGORIA"])."</a>";
								$secciones_Body .= "	</div>";
							}

							//	obtengo todos los contenidos de la CATEGORIA
							$registros3 = $temp->Query("SELECT * FROM contenidos WHERE (tipo_contenido = '".$tipo_contenido."') and (activo = 1) and (categoria = '".$registros2->fields["CATEGORIA"]."') ORDER BY fecha_alta,id_contenido LIMIT 10");
							if($registros3) {

								if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
									$secciones_Body .= "	<div id=\"".str_replace(" ","",stripslashes($registros2->fields["CATEGORIA"]))."\" class=\"accordion-body collapse in\">";
									$secciones_Body .= "		<div class=\"accordion-inner\">";
									$secciones_Body .= "			<ul class=\"nav nav-tabs nav-stacked\">";
								}

								while(!$registros3->EOF) {
									$Secciones_Head .= "<li><a href='".str_replace("contenidos.php","./",$registros->fields["URL"])."&id=".$registros3->fields["ID_CONTENIDO"]."'>".stripslashes($registros3->fields["TITULO"])."</a></li>\n";

									if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
										if(isset($_GET['id']) and ($_GET['id'] == $registros3->fields["ID_CONTENIDO"])) {
											$secciones_Body .= "		<li class=\"active\"><a href='".str_replace("contenidos.php","./",$registros->fields["URL"])."&id=".$registros3->fields["ID_CONTENIDO"]."'>".stripslashes($registros3->fields["TITULO"])."</a></li>\n";
										} else {
											$secciones_Body .= "		<li><a href='".str_replace("contenidos.php","./",$registros->fields["URL"])."&id=".$registros3->fields["ID_CONTENIDO"]."'>".stripslashes($registros3->fields["TITULO"])."</a></li>\n";
										}
									}

									$registros3->MoveNext();
								}
								$registros3->close();

								if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
									$secciones_Body .= "			</ul>";
									$secciones_Body .= "		</div>";
									$secciones_Body .= "	</div>";
								}
							}
							if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
								$secciones_Body .= "</div>";
							}

							$registros2->MoveNext();
						}
						$registros2->close();

						$Secciones_Head .= "</ul>\n";
						$Secciones_Head .= "</li>";
					} else {
						//	verifico si hay FOTOGALERIA
						$tipo_contenido = "fotogaleria";
						if(strrpos($registros->fields["URL"],$tipo_contenido) > 0) {
							//	obtengo todas las CATEGORIAS
							$registros2 = $conexion->Query("SELECT CATEGORIA FROM fotogaleria WHERE (activo = 1) GROUP BY categoria ORDER BY fecha_alta,categoria");
							if($registros2) {
								if($registros2->RecordCount() > 0) {
									//	verifico en que SECCION estoy
									if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
										$Secciones_Head .= "<li class=\"dropdown active\">";
									} else {
										$Secciones_Head .= "<li class=\"dropdown\">";
									}
									$Secciones_Head .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">".stripslashes($registros->fields["NOMBRE_MENU"])."<b class=\"caret\"></b></a>\n";

									if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
										$secciones_Body .= "<div class=\"accordion-group\">";
										$secciones_Body .= "	<div class=\"accordion-heading\">";
										$secciones_Body .= "		<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion2\" href=\"#".str_replace(" ","",stripslashes($registros->fields["NOMBRE_MENU"]))."\">".stripslashes($registros->fields["NOMBRE_MENU"])."</a>";
										$secciones_Body .= "	</div>";
										$secciones_Body .= "	<div id=\"".str_replace(" ","",stripslashes($registros->fields["NOMBRE_MENU"]))."\" class=\"accordion-body collapse in\">";
										$secciones_Body .= "		<div class=\"accordion-inner\">";
										$secciones_Body .= "			<ul class=\"nav nav-tabs nav-stacked\">";
									}

									$Secciones_Head .= "<ul class=\"dropdown-menu\">\n";
									while(!$registros2->EOF) {
										$Secciones_Head .= "<li><a href='./?menu=".$tipo_contenido."&cat=".$registros2->fields["CATEGORIA"]."'>".stripslashes($registros2->fields["CATEGORIA"])."</a></li>\n";

										if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
											if(isset($_GET['cat']) and ($_GET['cat'] == $registros2->fields["CATEGORIA"])) {
												$secciones_Body .= "		<li class=\"active\"><a href='./?menu=".$tipo_contenido."&cat=".$registros2->fields["CATEGORIA"]."'>".stripslashes($registros2->fields["CATEGORIA"])."</a></li>\n";
											} else {
												$secciones_Body .= "		<li><a href='./?menu=".$tipo_contenido."&cat=".$registros2->fields["CATEGORIA"]."'>".stripslashes($registros2->fields["CATEGORIA"])."</a></li>\n";
											}
										}

										$registros2->MoveNext();
									}
									$registros2->close();

									if(isset($_GET['menu']) and ($_GET['menu'] == $tipo_contenido)) {
										$secciones_Body .= "			</ul>";
										$secciones_Body .= "		</div>";
										$secciones_Body .= "	</div>";
										$secciones_Body .= "</div>";
									}

									$Secciones_Head .= "</ul>\n";
									$Secciones_Head .= "</li>";
								}
							}
						}
					}
				}
				*/

			$registros->MoveNext();
		}
		$registros->close();

		// armo el menu de PRODUCTOS
		if (isset($_GET['menu']) and ($_GET['menu'] == "productos")) {
			$Secciones_Head .= "<li class=\"dropdown active\"><a href=\"./?menu=productos\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-chevron-right\"></i>Productos</a>";
		} else {
			$Secciones_Head .= "<li class=\"dropdown\"><a href=\"./?menu=productos\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-chevron-right\"></i>Productos</a>";
		}
		//	obtengo todas las CATEGORIAS de Productos
		$Secciones_Head .= "<ul class=\"dropdown-menu\" style=\"width: 100%;\">\n";
		if (!isset($_GET['c'])) {
			$Secciones_Head .= "<li class=\"active\"><a href=\"./?menu=productos\">Ver todos los Productos</a></li>\n";
		} else {
			$Secciones_Head .= "<li><a href=\"./?menu=productos\">Ver todos los productos</a></li>\n";
		}
		$Secciones_Head .= "<li class=\"divider\"></li>\n";
		$registrosCategorias = $temp->execute("SELECT CATEGORIA,count(*) as CANTIDAD FROM productos WHERE (activo = 1) and (visible = 1) and ((categoria is not null) and (categoria <> '')) and (id_sucursal = '".XPANEL_SUCURSAL."') GROUP BY categoria ORDER BY categoria LIMIT 0,20");
		while (!$registrosCategorias->EOF) {
			if (isset($_GET['c']) and ($registrosCategorias->fields["CATEGORIA"] == $_GET['c'])) {
				$Secciones_Head .= "	<li class=\"active\"><a href=\"./?menu=productos&c=" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "\"><i class=\"icon-chevron-right\"></i>" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "</a></li>\n";
			} else {
				$Secciones_Head .= "	<li><a href=\"./?menu=productos&c=" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "\"><i class=\"icon-chevron-right\"></i>" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "</a></li>\n";
			}
			if (isset($_GET['c']) and ($_GET['c'] == stripslashes($registrosCategorias->fields["CATEGORIA"]))) {
				$Secciones_Head_Horizontal .= "	<li><a href=\"./?menu=productos&c=" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "\" class='active'>" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "</a></li>\n";
			} else {
				$Secciones_Head_Horizontal .= "	<li><a href=\"./?menu=productos&c=" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "\">" . stripslashes($registrosCategorias->fields["CATEGORIA"]) . "</a></li>\n";
			}
			$registrosCategorias->MoveNext();
		}
		$registrosCategorias->close();
		$Secciones_Head .= "</ul></li>\n";

		$Secciones_Head .= "<li><a data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Consultas\" title=\"Dejar consulta\">Cont&aacute;ctanos</a></li>\n";
		//$Secciones_Head_Horizontal .= "<li><a data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Consultas\" title=\"Dejar consulta\">Cont&aacute;ctanos</a></li>\n";
		//$Secciones_Head_Horizontal .= "<li><a data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Registro\" title=\"Formulario de Registro\">Reg&iacute;strate</a></li>\n";
		//$Secciones_Head .= "<li><a data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Suscripcion\" title=\"Suscribirse\">Suscripci&oacute;n</a></li>\n";
	}
	$secciones_Body .= "</div>";

	$Secciones_Head_Horizontal .= "</ul>";
	$Secciones_Head_Horizontal .= "</div>";
	$ContenidoHTML = str_replace("{SECCIONES_HEADER}", $Secciones_Head_Horizontal, $ContenidoHTML);

	$ContenidoHTML = str_replace("{SECCIONES1}", $Secciones_Head, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SECCIONES2}", $secciones_Body, $ContenidoHTML);
	if ($path != "") {
		$ContenidoHTML = str_replace("<span>{PATH}</span>", $path, $ContenidoHTML);
	} else {
		if ($_GET['menu'] == "iniciarsesion") {
			$path = " &raquo; <a href='?menu=" . $_GET['menu'] . "'>Iniciar sesi&oacute;n</a>";
			$ContenidoHTML = str_replace("<span>{PATH}</span>", $path, $ContenidoHTML);
		}
		if ($_GET['menu'] == "recuperarclave") {
			$path = " &raquo; <a href='?menu=" . $_GET['menu'] . "'>Recuperar contrase&ntilde;a</a>";
			$ContenidoHTML = str_replace("<span>{PATH}</span>", $path, $ContenidoHTML);
		}
		if ($_GET['menu'] == "usuarios") {
			$path = " &raquo; <a href='?menu=" . $_GET['menu'] . "'>Usuarios</a> <span>{PATH}</span>";
			$ContenidoHTML = str_replace("<span>{PATH}</span>", $path, $ContenidoHTML);
		}
	}

	//	Cargo los CONTENIDOS segun la seccion seleccionada
	if (isset($_GET['menu'])) {
		//	verifico el tipo de contenido del parametro de menu
		switch ($_GET['menu']) {
			case "laempresa":
			case "novedades":
			case "news":
				$titulo_sitio = ucfirst($_GET['menu']) . " | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/contenidos.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "boletin":
				$titulo_sitio = ucfirst($_GET['menu']) . " | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/boletin.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "productos":
				$titulo_sitio = ucfirst($_GET['menu']) . " | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/productos.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "fotogaleria":
				$titulo_sitio = ucfirst($_GET['menu']) . " | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/fotogaleria.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "iniciarsesion":
				$titulo_sitio = $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/iniciarsesion.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "recuperarclave":
				$titulo_sitio = $sitio_nombre;
				$contenido_mostrar = "";

				if (isset($_POST['user']) and isset($_POST['email'])) {
					//	busco el usuario segun el ID e EMAIL
					$registros = $conexion->Query("SELECT * FROM usuarios WHERE ((email = '" . $_POST['email'] . "') and (email is not null) and (email <> '')) and ((usuario = '" . $_POST['user'] . "') and (usuario is not null) and (usuario <> '')) and (usuario_activo = 1)");
					//	verifico si hay resuldados
					if ($registros) {
						if ($registros->RecordCount() > 0) {
							while (!$registros->EOF) {
								//	envio el mail de bienvenida
								$Htmlfile = $sitio_url . "xpanel/templates/" . "recuperar_clave.html";

								$contenido_mail = obtenerDatosArchivo($Htmlfile);
								$titulo = $registros->fields["NOMBRE_COMPLETO"] . ", su contrase&ntilde;a de acceso!";
								$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

								$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
								$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
								$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

								$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
								$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

								//	obtengo el logo para boletin
								if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
									$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
								} else {
									$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
								}

								$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
								$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
								$contenido_mail = str_replace("{ID}", $registros->fields["ID_USUARIO"], $contenido_mail);
								$contenido_mail = str_replace("{USUARIO}", $registros->fields["USUARIO"], $contenido_mail);
								$contenido_mail = str_replace("{CLAVE}", $registros->fields["CLAVE"], $contenido_mail);

								$mail = new PHPMailer();
								$mail->ConfirmReadingTo = $sitio_email_confirmacion;
								$mail->From				= $sitio_email;
								$mail->FromName			= $sitio_nombre;
								$mail->Subject			= $titulo;
								$mail->IsHTML(true);
								$mail->ClearAddresses();
								$mail->AddAddress($registros->fields["EMAIL"]);
								$mail->AddBCC($sitio_email);
								$mail->AddBCC($sitio_email_admin);
								$mail->Body				= $contenido_mail;
								if ($mail->Send()) {
									$contenido_mostrar .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por utilizar nuestro servicio de recuperaci&oacute;n de contrase&ntilde;a.</p>";
									$contenido_mostrar .= "<p>En unos instantes recibira un mensaje, en la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>, con la contraseña de acceso del usuario: <strong>" . $registros->fields["USUARIO"] . "</strong>.</p>";
									$contenido_mostrar .= "<p>&nbsp;</p>";
									$contenido_mostrar .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
								} else {
									$contenido_mostrar .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje, a la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>, con la contraseña de acceso del usuario: <strong>" . $registros->fields["USUARIO"] . "</strong>.</p></div>";
									$contenido_mostrar .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido_mostrar .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								}

								$registros->MoveNext();
							}
							$registros->close();
						} else {
							$contenido_mostrar .= "<div class=\"alert alert-error\"><h3>Datos no validos</h3>";
							$contenido_mostrar .= "<p>Las causas pueden deberse a alguna de las siguientes razones:</p>";
							$contenido_mostrar .= "<ul>";
							$contenido_mostrar .= "	<li>El nombre de usuario no es valido.</li>";
							$contenido_mostrar .= "	<li>La direcci&oacute;n de correo electr&oacute;nico no coincide con el registrado.</li>";
							$contenido_mostrar .= "	<li>No se encuentra habilitado para el acceso al area de usuarios registrados de <strong>" . $sitio_nombre . "</strong>.</li>";
							$contenido_mostrar .= "</ul>";
							$contenido_mostrar .= "</div>";
							$contenido_mostrar .= "<p>Volver a intentar recuperar la contrase&ntilde;a: [ <a href=\"./?menu=recuperarclave\">clic aqui</a> ]</p>";
							$contenido_mostrar .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido_mostrar .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					}
				} else {
					ob_start();
					include_once("html/recuperarclave.php");
					$contenido_mostrar .= ob_get_contents();
					ob_end_clean();
				}

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "usuarios":
				$titulo_sitio = ucfirst($_GET['menu']) . " | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/usuarios.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "buscar":
				$titulo_sitio = "buscar | " . $sitio_nombre;
				$contenido_mostrar = "";

				ob_start();
				include_once("html/buscar.php");
				$contenido_mostrar .= ob_get_contents();
				ob_end_clean();

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
				break;
			case "alta":
				$path = "";
				if (isset($_GET['curso'])) {
					$titulo_sitio = "Confirmaci&oacute;n de inscripci&oacute;n | " . $sitio_nombre;
					$contenido = "<h1>Confirmaci&oacute;n de inscripci&oacute;n</h1>";
					$path = "Confirmaci&oacute;n de inscripci&oacute;n";

					if (isset($_GET['id'])) {
						//	busco el usuario segun el ID y CURSO
						$registros = $conexion->Query("SELECT usuarios.* FROM usuario_seinscribe_curso LEFT JOIN usuarios ON usuarios.id_usuario = usuario_seinscribe_curso.id_usuario WHERE (usuario_seinscribe_curso.id_usuario = '" . $_GET['id'] . "') and (id_curso = '" . $_GET['curso'] . "')");
						//	verifico si hay resuldados
						if ($registros) {
							if ($registros->RecordCount() > 0) {
								//	busco el curso segun CURSO
								$datosCurso = $conexion->GetRow("SELECT * FROM cursos WHERE (id_curso = '" . $_GET['curso'] . "')");
								while (!$registros->EOF) {
									//	verifico si ya confirmo su registro
									if ($registros->fields["USUARIO_ACTIVO"] == 1) {
										$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>La direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> ya ha sido confirmada con anterioridad.</p></div>";
										$contenido .= "<p>Por ese motivo, lo invitamos a iniciar sesi&oacute;n como usuario registrado, con el nombre de usuario y contrase&ntilde;a que se ha remitido a su direcci&oacute;n de correo electr&oacute;nico el pasado <strong>" . formato_fecha($registros->fields["FECHA_ALTA"], 3) . "</strong>, a fin de que pueda realizar la inscripci&oacute;n en el curso que desea.</p>";
										$contenido .= "<p>Si posee algun inconveniente, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
										$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
									} else {
										//	actualizo la inscripcion
										$comentarios = "<p>Confirmaci&oacute;n de inscripci&oacute;n realizada el " . date("d/m/Y H:i:s") . " al Curso &quot;" . $datosCurso["NOMBRE"] . "&quot;</p>" . stripslashes($registros->fields["COMENTARIOS"]);
										$temp->Query("UPDATE usuarios SET usuario_activo = 1, comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "') and (usuario_activo = 0)");

										//	envio el mail de bienvenida
										$Htmlfile = $sitio_url . "xpanel/templates/" . "mensaje_bienvenida.html";

										$contenido_mail = obtenerDatosArchivo($Htmlfile);
										$titulo = $registros->fields["NOMBRE_COMPLETO"] . ", bienvenido a " . $sitio_nombre . "!";
										$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

										$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

										$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
										$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

										//	obtengo el logo para boletin
										if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
											$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
										} else {
											$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
										}

										$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
										$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
										$contenido_mail = str_replace("{ID}", $registros->fields["ID_USUARIO"], $contenido_mail);
										$contenido_mail = str_replace("{USUARIO}", $registros->fields["USUARIO"], $contenido_mail);
										$contenido_mail = str_replace("{CLAVE}", $registros->fields["CLAVE"], $contenido_mail);

										$mail = new PHPMailer();
										$mail->ConfirmReadingTo = $sitio_email_confirmacion;
										$mail->From				= $sitio_email;
										$mail->FromName			= $sitio_nombre;
										$mail->Subject			= $titulo;
										$mail->IsHTML(true);
										$mail->ClearAddresses();
										$mail->AddAddress($registros->fields["EMAIL"]);
										$mail->AddBCC($sitio_email);
										$mail->AddBCC($sitio_email_admin);
										$mail->Body				= $contenido_mail;
										if ($mail->Send()) {
											$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> y tu inscripci&oacute;n al Curso: &quot;" . $datosCurso["NOMBRE"] . "&quot;</p>";
											$contenido .= "<p>En unos instantes recibira un mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado en <strong>" . $sitio_nombre . "</strong>.</p>";
											$contenido .= "<p>&nbsp;</p>";
											$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
										} else {
											$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> y tu inscripci&oacute;n al Curso: &quot;" . $datosCurso["NOMBRE"] . "&quot;</p>";
											$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado, a la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										}
									}
									$registros->MoveNext();
								}
								$registros->close();
							} else {
								$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID o CURSO suministrado no existe.</p></div>";
								$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
								$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
							}
						}
					} else {
						$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
						$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
						$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
					}
				} else {
					if (isset($_GET['publicacion'])) {
						$titulo_sitio = "Confirmaci&oacute;n de compra | " . $sitio_nombre;
						$contenido = "<h1>Confirmaci&oacute;n de compra</h1>";
						$path = "Confirmaci&oacute;n de compra";

						if (isset($_GET['id'])) {
							//	busco el usuario segun el ID y PUBLICACION
							$registros = $conexion->Query("SELECT usuarios.* FROM usuario_compra_publicacion LEFT JOIN usuarios ON usuarios.id_usuario = usuario_compra_publicacion.id_usuario WHERE (usuario_compra_publicacion.id_usuario = '" . $_GET['id'] . "') and (id_publicacion = '" . $_GET['publicacion'] . "')");
							//	verifico si hay resuldados
							if ($registros) {
								if ($registros->RecordCount() > 0) {
									//	busco la publicacion segun PUBLICACION
									$datosPublicacion = $conexion->GetRow("SELECT * FROM publicaciones WHERE (id_publicacion = '" . $_GET['publicacion'] . "')");
									while (!$registros->EOF) {
										//	verifico si ya confirmo su registro
										if ($registros->fields["USUARIO_ACTIVO"] == 1) {
											$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>La direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> ya ha sido confirmada con anterioridad.</p></div>";
											$contenido .= "<p>Por ese motivo, lo invitamos a iniciar sesi&oacute;n como usuario registrado, con el nombre de usuario y contrase&ntilde;a que se ha remitido a su direcci&oacute;n de correo electr&oacute;nico el pasado <strong>" . formato_fecha($registros->fields["FECHA_ALTA"], 3) . "</strong>, a fin de que pueda realizar la compra del ejemplar que desea.</p>";
											$contenido .= "<p>Si posee algun inconveniente, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										} else {
											//	actualizo la compra
											$comentarios = "<p>Confirmaci&oacute;n de compra realizada el " . date("d/m/Y H:i:s") . " de la Publicaci&oacute;n: &quot;" . $datosPublicacion["TITULO"] . "&quot;</p>" . stripslashes($registros->fields["COMENTARIOS"]);
											$temp->Query("UPDATE usuarios SET usuario_activo = 1, comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "') and (usuario_activo = 0)");

											//	envio el mail de bienvenida
											$Htmlfile = $sitio_url . "xpanel/templates/" . "mensaje_bienvenida.html";

											$contenido_mail = obtenerDatosArchivo($Htmlfile);
											$titulo = $registros->fields["NOMBRE_COMPLETO"] . ", bienvenido a " . $sitio_nombre . "!";
											$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

											$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

											$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
											$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

											//	obtengo el logo para boletin
											if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
												$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
											} else {
												$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
											}

											$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
											$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
											$contenido_mail = str_replace("{ID}", $registros->fields["ID_USUARIO"], $contenido_mail);
											$contenido_mail = str_replace("{USUARIO}", $registros->fields["USUARIO"], $contenido_mail);
											$contenido_mail = str_replace("{CLAVE}", $registros->fields["CLAVE"], $contenido_mail);

											$mail = new PHPMailer();
											$mail->ConfirmReadingTo = $sitio_email_confirmacion;
											$mail->From				= $sitio_email;
											$mail->FromName			= $sitio_nombre;
											$mail->Subject			= $titulo;
											$mail->IsHTML(true);
											$mail->ClearAddresses();
											$mail->AddAddress($registros->fields["EMAIL"]);
											$mail->AddBCC($sitio_email);
											$mail->AddBCC($sitio_email_admin);
											$mail->Body				= $contenido_mail;
											if ($mail->Send()) {
												$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> y tu compra de la Publicaci&oacute;n: &quot;" . $datosPublicacion["TITULO"] . "&quot;</p>";
												$contenido .= "<p>En unos instantes recibira un mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado en <strong>" . $sitio_nombre . "</strong>.</p>";
												$contenido .= "<p>&nbsp;</p>";
												$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
											} else {
												$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> y tu compra de la Publicaci&oacute;n: &quot;" . $datosPublicacion["TITULO"] . "&quot;</p>";
												$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado, a la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p></div>";
												$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
												$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
											}
										}
										$registros->MoveNext();
									}
									$registros->close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID o PUBLICACION suministrada no existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					} else {
						$titulo_sitio = "Confirmaci&oacute;n de registro | " . $sitio_nombre;
						$contenido = "<h1>Confirmaci&oacute;n de registro</h1>";
						$path = "Confirmaci&oacute;n de registro";

						if (isset($_GET['id'])) {
							//	busco el usuario segun el ID
							$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '" . $_GET['id'] . "')");
							//	verifico si hay resuldados
							if ($registros) {
								if ($registros->RecordCount() > 0) {
									while (!$registros->EOF) {
										//	verifico si ya confirmo su registro
										if ($registros->fields["USUARIO_ACTIVO"] == 1) {
											$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>La direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> ya ha sido confirmada con anterioridad.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										} else {
											//	actualizo el registro
											$comentarios = "<p>Confirmaci&oacute;n de registro realizada el: " . date("d/m/Y H:i:s") . "</p>" . stripslashes($registros->fields["COMENTARIOS"]);
											$temp->Query("UPDATE usuarios SET usuario_activo = 1, comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "') and (usuario_activo = 0)");

											//	envio el mail de bienvenida
											$Htmlfile = $sitio_url . "xpanel/templates/" . "mensaje_bienvenida.html";

											$contenido_mail = obtenerDatosArchivo($Htmlfile);
											$titulo = $registros->fields["NOMBRE_COMPLETO"] . ", bienvenido a " . $sitio_nombre . "!";
											$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

											$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
											$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

											$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
											$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

											//	obtengo el logo para boletin
											if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
												$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
											} else {
												$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
											}

											$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
											$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
											$contenido_mail = str_replace("{ID}", $registros->fields["ID_USUARIO"], $contenido_mail);
											$contenido_mail = str_replace("{USUARIO}", $registros->fields["USUARIO"], $contenido_mail);
											$contenido_mail = str_replace("{CLAVE}", $registros->fields["CLAVE"], $contenido_mail);

											$mail = new PHPMailer();
											$mail->ConfirmReadingTo = $sitio_email_confirmacion;
											$mail->From				= $sitio_email;
											$mail->FromName			= $sitio_nombre;
											$mail->Subject			= $titulo;
											$mail->IsHTML(true);
											$mail->ClearAddresses();
											$mail->AddAddress($registros->fields["EMAIL"]);
											$mail->AddBCC($sitio_email);
											$mail->AddBCC($sitio_email_admin);
											$mail->Body				= $contenido_mail;
											if ($mail->Send()) {
												$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p>";
												$contenido .= "<p>En unos instantes recibira un mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado en <strong>" . $sitio_nombre . "</strong>.</p>";
												$contenido .= "<p>&nbsp;</p>";
												$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
											} else {
												$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por confirmar tu direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p>";
												$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de bienvenida con los datos de acceso para iniciar sesi&oacute;n como usuario registrado, a la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p></div>";
												$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
												$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
											}
										}
										$registros->MoveNext();
									}
									$registros->close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					}
				}

				$contenido .= "<div id=\"path\">" . $path . "</div>";

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido, $ContenidoHTML);
				break;
			case "baja":
				if (isset($_GET['curso'])) {
					$titulo_sitio = "Eliminar inscripci&oacute;n | " . $sitio_nombre;
					$contenido = "<h1>Eliminar inscripci&oacute;n</h1>";
					$path = "Eliminar inscripci&oacute;n";

					if (isset($_GET['id'])) {
						//	busco el usuario segun el ID y CURSO
						$registros = $conexion->Query("SELECT usuarios.* FROM usuario_seinscribe_curso LEFT JOIN usuarios ON usuarios.id_usuario = usuario_seinscribe_curso.id_usuario WHERE (usuario_seinscribe_curso.id_usuario = '" . $_GET['id'] . "') and (id_curso = '" . $_GET['curso'] . "')");
						//	verifico si hay resuldados
						if ($registros) {
							if ($registros->RecordCount() > 0) {
								//	busco el curso segun CURSO
								$datosCurso = $conexion->GetRow("SELECT * FROM cursos WHERE (id_curso = '" . $_GET['curso'] . "')");
								while (!$registros->EOF) {
									//	actualizo el registro
									$comentarios = "<p>Eliminaci&oacute;n de inscripci&oacute;n realizada el " . date("d/m/Y H:i:s") . " al Curso &quot;" . $datosCurso["NOMBRE"] . "&quot;</p>" . stripslashes($registros->fields["COMENTARIOS"]);
									$conexion->Execute("DELETE FROM usuario_seinscribe_curso WHERE (id_usuario = '" . $_GET['id'] . "') and (id_curso = '" . $_GET['curso'] . "')");
									$temp->Query("UPDATE usuarios SET comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "')");

									//	envio el mail de bienvenida
									$Htmlfile = $sitio_url . "xpanel/templates/" . "notificacion_eliminacioninscripcion.html";

									$contenido_mail = obtenerDatosArchivo($Htmlfile);
									$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
									$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

									$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
									$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
									$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

									$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
									$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

									//	obtengo el logo para boletin
									if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
										$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
									} else {
										$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
									}

									$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
									$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
									$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '" . $registros->fields["CODIGO_PAIS"] . "')");
									$contenido = str_replace("{PAIS}", $pais["PAIS"], $contenido);
									$contenido_mail = str_replace("{CURSO}", $datosCurso["NOMBRE"], $contenido_mail);

									$mail = new PHPMailer();
									$mail->ConfirmReadingTo = $sitio_email_confirmacion;
									$mail->From				= $sitio_email;
									$mail->FromName			= $sitio_nombre;
									$mail->Subject			= $titulo;
									$mail->IsHTML(true);
									$mail->ClearAddresses();
									$mail->AddAddress($registros->fields["EMAIL"]);
									$mail->AddBCC($sitio_email);
									$mail->AddBCC($sitio_email_admin);
									$mail->Body				= $contenido_mail;
									if ($mail->Send()) {
										$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										$contenido .= "<div class=\"alert alert-info\"><h3>Atenci&oacute;n</h3><p>Se ha eliminado la inscripci&oacute;n realizada con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> al Curso &quot;" . $datosCurso["NOMBRE"] . "&quot;.</p></div>";
										$contenido .= "<p>&nbsp;</p>";
										$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
									} else {
										$contenido .= "<p>Se ha eliminado la inscripci&oacute;n realizada con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> al Curso &quot;" . $datosCurso["NOMBRE"] . "&quot;.</p>";
										$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de aviso sobre la eliminaci&oacute;n de la inscripci&oacute;n.</p></div>";
										$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:$sitio_email\">$sitio_email</a>, a fin de evitar futuros inconvenientes.</p>";
										$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
									}

									$registros->MoveNext();
								}
								$registros->close();
							} else {
								$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID o CURSO suministrado no existe.</p></div>";
								$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
								$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
							}
						}
					} else {
						$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
						$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
						$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
					}
				} else {
					if (isset($_GET['publicacion'])) {
						$titulo_sitio = "Eliminar compra | " . $sitio_nombre;
						$contenido = "<h1>Eliminar compra</h1>";
						$path = "Eliminar compra";

						if (isset($_GET['id'])) {
							//	busco el usuario segun el ID y PUBLICACION
							$registros = $conexion->Query("SELECT usuarios.* FROM usuario_compra_publicacion LEFT JOIN usuarios ON usuarios.id_usuario = usuario_compra_publicacion.id_usuario WHERE (usuario_compra_publicacion.id_usuario = '" . $_GET['id'] . "') and (id_publicacion = '" . $_GET['publicacion'] . "')");
							//	verifico si hay resuldados
							if ($registros) {
								if ($registros->RecordCount() > 0) {
									//	busco la publicacion segun PUBLICACION
									$datosPublicacion = $conexion->GetRow("SELECT * FROM publicaciones WHERE (id_publicacion = '" . $_GET['publicacion'] . "')");
									while (!$registros->EOF) {
										//	actualizo el registro
										$comentarios = "<p>Eliminaci&oacute;n de compra realizada el " . date("d/m/Y H:i:s") . " de la Publicaci&oacute;n: &quot;" . $datosPublicacion["TITULO"] . "&quot;</p>" . stripslashes($registros->fields["COMENTARIOS"]);
										$conexion->Execute("DELETE FROM usuario_compra_publicacion WHERE (id_usuario = '" . $_GET['id'] . "') and (id_publicacion = '" . $_GET['publicacion'] . "')");
										$temp->Query("UPDATE usuarios SET comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "')");

										//	envio el mail de bienvenida
										$Htmlfile = $sitio_url . "xpanel/templates/" . "notificacion_eliminacioncompra.html";

										$contenido_mail = obtenerDatosArchivo($Htmlfile);
										$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
										$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

										$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

										$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
										$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

										//	obtengo el logo para boletin
										if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
											$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
										} else {
											$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
										}

										$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
										$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
										$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '" . $registros->fields["CODIGO_PAIS"] . "')");
										$contenido = str_replace("{PAIS}", $pais["PAIS"], $contenido);
										$contenido_mail = str_replace("{PUBLICACION}", $datosPublicacion["TITULO"], $contenido_mail);

										$mail = new PHPMailer();
										$mail->ConfirmReadingTo = $sitio_email_confirmacion;
										$mail->From				= $sitio_email;
										$mail->FromName			= $sitio_nombre;
										$mail->Subject			= $titulo;
										$mail->IsHTML(true);
										$mail->ClearAddresses();
										$mail->AddAddress($registros->fields["EMAIL"]);
										$mail->AddBCC($sitio_email);
										$mail->AddBCC($sitio_email_admin);
										$mail->Body				= $contenido_mail;
										if ($mail->Send()) {
											$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
											$contenido .= "<div class=\"alert alert-info\"><h3>Atenci&oacute;n</h3><p>Se ha eliminado la compra realizada con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> de la Publicaci&oacute;n &quot;" . $datosPublicacion["TITULO"] . "&quot;.</p></div>";
											$contenido .= "<p>&nbsp;</p>";
											$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
										} else {
											$contenido .= "<p>Se ha eliminado la compra realizada con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> de la Publicaci&oacute;n &quot;" . $datosPublicacion["TITULO"] . "&quot;.</p>";
											$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de aviso sobre la eliminaci&oacute;n de la compra.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										}

										$registros->MoveNext();
									}
									$registros->close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID o PUBLICACION suministrado no existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					} else {
						$titulo_sitio = "Eliminar registro | " . $sitio_nombre;
						$contenido = "<h1>Eliminar registro</h1>";
						$path = "Eliminar registro";

						if (isset($_GET['id'])) {
							//	busco el usuario segun el ID
							$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '" . $_GET['id'] . "')");
							//	verifico si hay resuldados
							if ($registros) {
								if ($registros->RecordCount() > 0) {
									while (!$registros->EOF) {
										//	acutalizo el registro
										$comentarios = "<p>Eliminaci&oacute;n de registro realizada el: " . date("d/m/Y H:i:s") . "</p>" . stripslashes($registros->fields["COMENTARIOS"]);
										$temp->Query("UPDATE usuarios SET usuario_activo = 0, comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . trim($_GET['id']) . "')");

										//	envio el mail de bienvenida
										$Htmlfile = $sitio_url . "xpanel/templates/" . "notificacion_eliminacionregistro.html";

										$contenido_mail = obtenerDatosArchivo($Htmlfile);
										$titulo = substr($contenido_mail, strpos($contenido_mail, "<title>") + 7, (strpos($contenido_mail, "</title>") - (strpos($contenido_mail, "<title>") + 7)));
										$contenido_mail = str_replace("{FECHA}", date("d/m/Y"), $contenido_mail);

										$contenido_mail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_URL}", $sitio_url, $contenido_mail);
										$contenido_mail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenido_mail);

										$contenido_mail = str_replace("{PUBLICIDAD1}", "", $contenido_mail);
										$contenido_mail = str_replace("{PUBLICIDAD2}", "", $contenido_mail);

										//	obtengo el logo para boletin
										if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
											$contenido_mail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenido_mail);
										} else {
											$contenido_mail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenido_mail);
										}

										$contenido_mail = str_replace("{NOMBRE}", $registros->fields["NOMBRE_COMPLETO"], $contenido_mail);
										$contenido_mail = str_replace("{EMAIL}", $registros->fields["EMAIL"], $contenido_mail);
										$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '" . $registros->fields["CODIGO_PAIS"] . "')");
										$contenido = str_replace("{PAIS}", $pais["PAIS"], $contenido);

										$mail = new PHPMailer();
										$mail->ConfirmReadingTo = $sitio_email_confirmacion;
										$mail->From				= $sitio_email;
										$mail->FromName			= $sitio_nombre;
										$mail->Subject			= $titulo;
										$mail->IsHTML(true);
										$mail->ClearAddresses();
										$mail->AddAddress($registros->fields["EMAIL"]);
										$mail->AddBCC($sitio_email);
										$mail->AddBCC($sitio_email_admin);
										$mail->Body				= $contenido_mail;
										if ($mail->Send()) {
											$contenido .= "<p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong>, gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
											$contenido .= "<div class=\"alert alert-info\"><h3>Atenci&oacute;n</h3><p>Se ha eliminado el registro realizado con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p></div>";
											$contenido .= "<p style=\"color: #808080;\">Para darte de ALTA autom&aacute;ticamente de nuestra lista, en caso de haber eliminado el registro por error, basta con que hagas clic sobre el siguiente link: <a href=\"" . $sitio_url . "?menu=alta&id=" . $registros->fields["ID_USUARIO"] . "\">" . $sitio_url . "?menu=alta&id=" . $registros->fields["ID_USUARIO"] . "</a>.</p>";
											$contenido .= "<p>&nbsp;</p>";
											$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
										} else {
											$contenido .= "<p>Se ha eliminado el registro realizado con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong>.</p>";
											$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>Se ha obtenido el siguiente mensaje <strong>Error: " . htmlentities($mail->ErrorInfo) . "</strong>, al intentar enviar el mensaje de aviso sobre la eliminaci&oacute;n de registro.</p></div>";
											$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
											$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
										}

										$registros->MoveNext();
									}
									$registros->close();
								} else {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no existe.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								}
							}
						} else {
							$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p>El ID suministrado no es correcto</p></div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					}
				}

				$contenido .= "<div id=\"path\">" . $path . "</div>";

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido, $ContenidoHTML);
				break;
			case "descargar":
				$titulo_sitio = "Descargar regalo | " . $sitio_nombre;
				$contenido = "<h1>Descargar regalo</h1>";

				if (isset($_GET['id'])) {
					//	busco al usuarios segun el ID
					$registros = $conexion->Query("SELECT * FROM usuarios WHERE (id_usuario = '" . $_GET['id'] . "')");
					//	verifico si hay resuldados
					if ($registros) {
						if ($registros->RecordCount() > 0) {
							while (!$registros->EOF) {
								//	verifico si ya descarglo el regalo
								if ($registros->fields["DESCARGO_REGALO"] == 1) {
									$contenido .= "<div class=\"alert alert-error\"><h3>Atenci&oacute;n</h3><p><strong>" . $registros->fields["NOMBRE_COMPLETO"] . "</strong> ya has descargado el regalo titulado <strong>\"Modelo de Plan de negocios online\"</strong>.</p></div>";
									$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
								} else {

									//	actualizo la descarga
									$comentarios = "<p>Descargalo regalo <strong>\"Modelo de Plan de negocios online\"</strong> el: " . date("d/m/Y H:i:s") . "</p>" . stripslashes($registros->fields["COMENTARIOS"]);
									$temp->Query("UPDATE usuarios SET descargo_regalo = 1, comentarios = '" . addslashes($comentarios) . "' WHERE (id_usuario = '" . $_GET['id'] . "') and (descargo_regalo = 0)");

									//$contenido .= "<script src=\"descargarRegalo.php\" type=\"text/javascript\"></script>";
									include("descargarRegalo.php");

									$contenido .= "<p>Gracias por descargar el regalo titulado <strong>\"Modelo de Plan de negocios online\"</strong> de " . $sitio_nombre . ".</p>";
									$contenido .= "<p>&nbsp;</p>";
									$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
									$contenido .= "<p>&nbsp;</p>";
								}
								$registros->MoveNext();
							}
							$registros->close();
						} else {
							$contenido .= "<div class=\"alert alert-error\">";
							$contenido .= "	<h3>Atenci&oacute;n</h3>";
							$contenido .= "	<p>El ID suministrado no existe.</p>";
							$contenido .= "</div>";
							$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
						}
					}
				} else {
					$contenido .= "<div class=\"alert alert-error\">";
					$contenido .= "	<h3>Atenci&oacute;n</h3>";
					$contenido .= "	<p>El ID suministrado no es correcto</p>";
					$contenido .= "</div>";
					$contenido .= "<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
					$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
				}

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido, $ContenidoHTML);
				break;
			case "suscripcion":
				$titulo_sitio = "Registrando suscripci&oacute;n | " . $sitio_nombre;
				$contenido = "<h1>Registrando suscripci&oacute;n</h1>";

				if (isset($_POST['btEnviarSuscripcion'])) {
					//	verifico si ya esta suscripto
					$registros = $conexion->Query("SELECT * FROM usuarios WHERE email = '" . $_POST['email'] . "'");
					if ($registros->RecordCount() > 0) {
						while (!$registros->EOF) {
							$contenido .= "<div class=\"alert alert-error\">";
							$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";
							$contenido .= "	<p>Usted ya se ha suscripto a nuestro boletin con la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $registros->fields["EMAIL"] . "</strong> con las siguientes observaciones:</p><br />";
							$contenido .= "	<blockquote>" . stripslashes($registros->fields["COMENTARIOS"]) . "</blockquote>";
							$contenido .= "</div>";
							$contenido .= "<p>Si posee alguna duda o consulta al respecto, por favor, contactarse con la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
							$registros->MoveNext();
						}
						$registros->close();
					} else {

						//	registro la suscripcion
						$comentarios = "<p>Suscripci&oacute;n realizada el: " . date("d/m/Y H:i:s") . "</p>";
						$conexion->Query("INSERT INTO usuarios (ID_USUARIO,TIPO_DOC,NRO_DOC,NOMBRE,APELLIDOS,NOMBRE_COMPLETO,CODIGO_PAIS,EMAIL,TIPO_USUARIO,USUARIO,CLAVE,FECHA_ALTA,ACTIVO,COMENTARIOS) VALUES(null,'DNI','0','" . addslashes($_POST['nombre']) . "','','" . addslashes($_POST['nombre']) . "','" . $_POST['codigo_pais'] . "','" . $_POST['email'] . "','Suscriptor','" . $_POST['email'] . "','" . generarPassword() . "','" . date("Y-m-d") . "','0','" . addslashes($comentarios) . "')");
						$id_registro = $conexion->Insert_ID();
						$Htmlfile = $sitio_url . "xpanel/templates/" . "notificacion_suscripcion.html";

						//obtengo los datos de la plantilla
						if (verificar_url($Htmlfile)) {
							//$contenidoMail = file_get_contents($Htmlfile);
							$contenidoMail = obtenerDatosArchivo($Htmlfile);
							$titulo = substr($contenidoMail, strpos($contenidoMail, "<title>") + 7, (strpos($contenidoMail, "</title>") - (strpos($contenidoMail, "<title>") + 7)));
							$contenidoMail = str_replace("{FECHA}", date("d/m/Y"), $contenidoMail);

							$contenidoMail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenidoMail);
							$contenidoMail = str_replace("{SITIO_URL}", $sitio_url, $contenidoMail);
							$contenidoMail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenidoMail);

							$contenidoMail = str_replace("{PUBLICIDAD1}", "", $contenidoMail);
							$contenidoMail = str_replace("{PUBLICIDAD2}", "", $contenidoMail);

							//	obtengo el logo para boletin
							if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
								$contenidoMail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenidoMail);
							} else {
								$contenidoMail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenidoMail);
							}

							$contenidoMail = str_replace("{NOMBRE}", $_POST['nombre'], $contenidoMail);
							$contenidoMail = str_replace("{EMAIL}", $_POST['email'], $contenidoMail);
							$pais = $temp->GetRow("SELECT * FROM paises WHERE (codigo_pais = '" . $_POST['codigo_pais'] . "')");
							$contenidoMail = str_replace("{PAIS}", $pais["PAIS"], $contenidoMail);
							$url = "./?menu=" . $_POST['tipo_cabeza'] . "&id=" . $_POST['id_cabeza'];
							$contenido = str_replace("{URL}", $url, $contenido);

							//notifico al administrador
							$mail = new PHPMailer();
							$mail->ConfirmReadingTo = $sitio_email_confirmacion;
							$mail->From				= $sitio_email;
							$mail->FromName			= $sitio_nombre;
							$mail->Subject			= $titulo;
							$mail->IsHTML(true);
							$mail->ClearAddresses();
							$mail->AddBCC($sitio_email);
							$mail->AddBCC($sitio_email_admin);
							$mail->Body				= $contenidoMail;
							if ($mail->Send()) {
								$contenido .= "<div class=\"alert alert-success\">";
								$contenido .= "	<h4 class=\"alert-heading\">&iexcl;Gracias por suscribirte a " . $sitio_nombre . "!</h4>";
								$contenido .= "	<p>Usted recibir&aacute; un mensaje de confirmaci&oacute;n de su direcci&oacute;n de correo electr&oacute;nico, de parte de <strong>" . $sitio_email . "</strong>.</p>";
								$contenido .= "	<p>Este proceso es necesario para asegurar su privacidad, de que usted se suscribi&oacute; voluntariamente a mi boletin de Informaci&oacute;n y Negocios.</p>";
								$contenido .= "</div>";
								$contenido .= "<p>Si utiliza Hotmail, Yahoo, Gmail u otro detector de SPAM, No olvide agregar a su lista blanca de contactos <strong>" . $sitio_email . "</strong>.</p>";

								//	envio el mail solicitando confirmacion
								$Htmlfile = $sitio_url . "xpanel/templates/" . "confirmacion_suscripcion.html";

								//$contenidoMail = file_get_contents($Htmlfile);
								$contenidoMail = obtenerDatosArchivo($Htmlfile);
								$titulo = $_POST['nombre'] . ", !confirma tu registraci&oacute;n a " . $sitio_nombre . "!";
								$contenidoMail = str_replace("{FECHA}", date("d/m/Y"), $contenidoMail);

								$contenidoMail = str_replace("{SITIO_EMAIL}", $sitio_email, $contenidoMail);
								$contenidoMail = str_replace("{SITIO_URL}", $sitio_url, $contenidoMail);
								$contenidoMail = str_replace("{SITIO_NOMBRE}", $sitio_nombre, $contenidoMail);

								$contenidoMail = str_replace("{PUBLICIDAD1}", "", $contenidoMail);
								$contenidoMail = str_replace("{PUBLICIDAD2}", "", $contenidoMail);

								//	obtengo el logo para boletin
								if (verificar_url($sitio_url . "xpanel/templates/logo.jpg")) {
									$contenidoMail = str_replace("{LOGO}", "<img src='" . $sitio_url . "xpanel/templates/logo.jpg" . "'>", $contenidoMail);
								} else {
									$contenidoMail = str_replace("{LOGO}", "<h1>" . $sitio_nombre . "</h1>", $contenidoMail);
								}

								$contenidoMail = str_replace("{NOMBRE}", $_POST['nombre'], $contenidoMail);
								$contenidoMail = str_replace("{EMAIL}", $_POST['email'], $contenidoMail);
								$contenidoMail = str_replace("{ID}", $id_registro, $contenidoMail);

								$mail->ConfirmReadingTo = $sitio_email_confirmacion;
								$mail->From				= $sitio_email;
								$mail->FromName			= $sitio_nombre;
								$mail->Subject			= $titulo;
								$mail->IsHTML(true);
								$mail->ClearAddresses();
								$mail->AddAddress($_POST['email']);
								$mail->AddBCC($sitio_email);
								$mail->AddBCC($sitio_email_admin);
								$mail->Body				= $contenidoMail;
								if ($mail->Send()) {
									$contenido .= "<div class=\"alert alert-error\">";
									$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";
									$contenido .= "	<p>Se ha enviado la solicitud de confirmaci&oacute;n de suscripci&oacute;n a la direcci&oacute;n de correo electr&oacute;nico: <strong>" . $_POST['email'] . "</strong>.</p>";
									$contenido .= "</div>";
								} else {
									$contenido .= "<div class=\"alert alert-error\">";
									$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";
									$contenido .= "	<p>Se ha obtenido el siguiente mensaje <span style=\"color: #FF0000\"><strong>Error:</strong> " . htmlentities($mail->ErrorInfo) . "</span>, al intentar enviar la solicitud de confirmaci&oacute;n de suscripci&oacute;n.</p>";
									$contenido .= "	<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
									$contenido .= "</div>";
								}
							} else {
								$contenido .= "<div class=\"alert alert-error\">";
								$contenido .= "	<h4 class=\"alert-heading\">Atenci&oacute;n</h4>";
								$contenido .= "	<p>Se ha obtenido el siguiente mensaje <span style=\"color: #FF0000\"><strong>Error:</strong> " . htmlentities($mail->ErrorInfo) . "</span>, al intentar enviar el aviso de suscripci&oacute;n.</p>";
								$contenido .= "	<p>Por favor, notificar del siguiente mensaje a la direcci&oacute;n de correo electr&oacute;nico <a href=\"mailto:" . $sitio_email . "\">" . $sitio_email . "</a>, a fin de evitar futuros inconvenientes.</p>";
								$contenido .= "</div>";
							}
						}
					}	//	fin de verificar si ya esta suscripto
					$contenido .= "<p>Gracias por visitar nuestro sitio web y utilizar nuestros servicios.</p>";
					$contenido .= "<p style=\"text-align: right;\"><strong>" . $sitio_nombre . "</strong><br /><em>" . $sitio_eslogan . "</em></p>";
				}

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido, $ContenidoHTML);
				break;
			case "fotogaleria2":
				if (isset($_GET['cat'])) {
					$titulo_sitio = $_GET['cat'] . " | " . $sitio_nombre;
					$contenido = "<h1>" . $_GET['cat'] . "</h1>";

					//	busco la imagenes de la CATEGORIA
					$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (categoria = '" . $_GET['cat'] . "') ORDER BY id_foto");
					//	verifico si hay resuldados
					if ($registros) {
						if ($registros->RecordCount() > 0) {
							$contenido .= "<ul class=\"thumbnails\">\n";
							while (!$registros->EOF) {
								//	verifico si el archivo existe
								$nombre_completo_imagenoriginal = $path_imagenes . "fotogaleria/" . $registros->fields["NOMBRE_ARCHIVO"];
								if (file_exists($nombre_completo_imagenoriginal)) {
									//	obtengo el nombre y extension del archivo small
									$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen small
									$nombre_completo_imagensmall = $path_imagenes . "fotogaleria/" . $nombre . "_small." . $extension;
									if (file_exists($nombre_completo_imagensmall)) {
										$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
									}
								} else {
									$nombre_completo_imagenoriginal = $path_imagenes . "fotogaleria/foto.gif";
								}
								$contenido .= "<li class=\"span3\">";
								if ($registros->fields["DESCRIPCION"] == null) {
									$contenido .= "<a href=\"" . $nombre_completo_imagenoriginal . "\" class=\"thumbnail\" rel=\"colorbox\" title=\"" . stripslashes($registros->fields["CATEGORIA"]) . "\">";
								} else {
									$contenido .= "<a href=\"" . $nombre_completo_imagenoriginal . "\" class=\"thumbnail\" rel=\"colorbox\" title=\"" . stripslashes($registros->fields["DESCRIPCION"]) . "\">";
								}
								$contenido .= "	<img src=\"" . $nombre_completo_imagenoriginal . "\" border=\"0\" alt=\"\" />";
								$contenido .= "</a>";
								$contenido .= "<div class=\"caption\">" . stripslashes($registros->fields["DESCRIPCION"]) . "</div>";
								$contenido .= "</li>\n";
								$registros->MoveNext();
								$nombre_completo_imagenoriginal = "";
							}
							$registros->close();
							$contenido .= "</ul>";
						}
					}
				} else {
					//	Muestro la lista de categorias de imagenes
					$contenido = "<h1>Categorias de im&aacute;genes</h1>";
				}

				$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido, $ContenidoHTML);
				break;
			default:
				$titulo_sitio = "error | " . $sitio_nombre;
				$contenido = "";
				$ContenidoHTML = str_replace("{CONTENIDOS}", "<div class=\"alert alert-error\" style=\"margin: 20px auto;\"><h3>Atenci&oacute;n</h3><p><strong>" . ucfirst($_GET['menu']) . "</strong> no es un menu de contenido</p></div>", $ContenidoHTML);
				$ContenidoHTML = str_replace("{CATEGORIAS}", "", $ContenidoHTML);
				break;
		}
		$ContenidoHTML = str_replace("{CATEGORIAS}", $categorias, $ContenidoHTML);
		$ContenidoHTML = str_replace("{COMPARTIR_CONTENIDO_LATERAL}", trim($Compartir_ContenidoLateral), $ContenidoHTML);
		$ContenidoHTML = str_replace("{PATH}", trim($path), $ContenidoHTML);
	} else {
		//	cargo la portada del sitio
		$titulo_sitio = $sitio_nombre . ": " . $sitio_eslogan;
		$contenido_mostrar = "";

		//	Video de portada
		//$contenido .= "<div class=\"well\"><div id=\"container_video\">Cargando reproductor ...</div></div>\n";

		ob_start();
		include_once("html/portada.php");
		$contenido_mostrar .= ob_get_contents();
		ob_end_clean();

		//$ContenidoHTML = str_replace("{PUBLICIDAD_V}","",$ContenidoHTML);
		//$ContenidoHTML = str_replace("{IFRAME_FACEBOOK}","",$ContenidoHTML);
		$ContenidoHTML = str_replace("{COMPARTIR_CONTENIDO_LATERAL}", $Compartir_ContenidoLateral, $ContenidoHTML);
		$ContenidoHTML = str_replace("{PATH}", trim($path), $ContenidoHTML);
		$ContenidoHTML = str_replace("{PUBLICIDAD_H}", "{PUBLICIDAD_PORTADA}", $ContenidoHTML);
		$ContenidoHTML = str_replace("{CONTENIDOS}", $contenido_mostrar, $ContenidoHTML);
		$ContenidoHTML = str_replace("{CATEGORIAS}", $categorias, $ContenidoHTML);

		$url = urlencode($sitio_url);
		$Compartir_Contenido = "";
		$Compartir_Contenido .= "<div style='margin: 10px -5px;text-align: center;'>";
		$Compartir_Contenido .= "	<iframe src='//www.facebook.com/plugins/like.php?href=" . $url . "&amp;send=false&amp;layout=box_count&amp;width=75&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=63' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:75px; height:63px;' allowTransparency='true'></iframe>";
		$Compartir_Contenido .= "	<a href='https://twitter.com/share' class='twitter-share-button' data-count='vertical' data-via='wirahola' data-lang='es'>Tweet</a><script type='text/javascript' src='//platform.twitter.com/widgets.js'></script>";
		$Compartir_Contenido .= "	<script type='text/javascript' src='https://apis.google.com/js/plusone.js'> {lang: 'es'} </script>";
		$Compartir_Contenido .= "	&nbsp;<g:plusone size='tall'></g:plusone>";
		$Compartir_Contenido .= "</div>";
		$ContenidoHTML = str_replace("{COMPARTIR}", $Compartir_Contenido, $ContenidoHTML);
	}

	//$ContenidoHTML = str_replace("{PATH}",$path,$ContenidoHTML);

	//	actualizo el TITULO de la PLANTILLA
	$titulo_plantilla = substr($ContenidoHTML, strpos($ContenidoHTML, "<title>") + 7, (strpos($ContenidoHTML, "</title>") - (strpos($ContenidoHTML, "<title>") + 7)));
	$ContenidoHTML = str_replace($titulo_plantilla, $titulo_sitio, $ContenidoHTML);

	//	actualizo la PUBLICIDAD
	$url = "";
	if (isset($publicidad_portada_mostrar) and ($publicidad_portada_mostrar == "s") and (verificar_url($sitio_url . $path_imagenes . $publicidad_portada_archivo))) {
		$url = "<img src='" . $sitio_url . $path_imagenes . $publicidad_portada_archivo . "' style='width: 100%;padding: 0;' />";
		if ($publicidad_portada_enlace != "") {
			$url = "<a href='" . $publicidad_portada_enlace . "' border='0'>" . $url . "</a>";
		}
	}
	$ContenidoHTML = str_replace("{PUBLICIDAD_PORTADA}", $url, $ContenidoHTML);

	$url = "";
	if (isset($publicidad_h_mostrar) and ($publicidad_h_mostrar == "s") and (verificar_url($sitio_url . $path_imagenes . $publicidad_h_archivo))) {
		$url = "<img src='" . $sitio_url . $path_imagenes . $publicidad_h_archivo . "' style=\"padding: 0;\" />";
		if ($publicidad_h_enlace != "") {
			$url = "<a href='" . $publicidad_h_enlace . "' border='0'>" . $url . "</a>";
		}
	} else {
		$url .= "<div class='row' style=\"background-color: #FFFFCC;height: 200px;width: 100%;border: 1px solid #eee;margin-left: 0px;margin-bottom: 20px;padding: 0px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;\">\n";
		$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
		$url .= "		<script type=\"text/javascript\">\n";
		$url .= "		google_ad_client = \"pub-4033818343503592\";\n";
		$url .= "		google_ad_slot = \"0809458744\";\n";
		$url .= "		google_ad_width = 200;\n";
		$url .= "		google_ad_height = 200;\n";
		$url .= "		</script>\n";
		$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
		$url .= "	</div>\n";
		$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
		$url .= "		<script type=\"text/javascript\">\n";
		$url .= "		google_ad_client = \"pub-4033818343503592\";\n";
		$url .= "		google_ad_slot = \"3474887345\";\n";
		$url .= "		google_ad_width = 200;\n";
		$url .= "		google_ad_height = 200;\n";
		$url .= "		</script>\n";
		$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
		$url .= "	</div>\n";
		$url .= "	<div class='span3' style='margin: 0 5px;'>\n";
		$url .= "		<script type=\"text/javascript\">\n";
		$url .= "		google_ad_client = \"ca-pub-4033818343503592\";\n";
		$url .= "		google_ad_slot = \"1135461466\";\n";
		$url .= "		google_ad_width = 200;\n";
		$url .= "		google_ad_height = 200;\n";
		$url .= "		</script>\n";
		$url .= "		<script type=\"text/javascript\" src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\"></script>\n";
		$url .= "	</div>\n";
		$url .= "</div>\n";
	}
	$ContenidoHTML = str_replace("{PUBLICIDAD_H}", $url, $ContenidoHTML);

	$url = "";
	if (isset($publicidad_v_mostrar) and ($publicidad_v_mostrar == "s") and (verificar_url($sitio_url . $path_imagenes . $publicidad_v_archivo))) {
		$url = "<img src='" . $sitio_url . $path_imagenes . $publicidad_v_archivo . "' />";
		if ($publicidad_v_enlace != "") {
			$url = "<a href='" . $publicidad_v_enlace . "' border='0'>" . $url . "</a>";
		}
	}
	$ContenidoHTML = str_replace("{PUBLICIDAD_V}", $url, $ContenidoHTML);

	//	obtengo la FRASE DE LA SEMANA
	$msj = "";
	if ((isset($frase) and ($frase != "")) and (isset($frase_autor) and ($frase_autor != ""))) {
		$msj .= "<div class=\"alert\">";
		$msj .= "<h1>Frase de la semana</h1>";
		$msj .= "<p class=\"align-center\"><em>&quot;" . $frase . "&quot;</em></p>";
		$msj .= "<p class=\"align-right\"><strong>- " . $frase_autor . "</strong></p>";
		$msj .= "</div>";
	}
	$ContenidoHTML = str_replace("{FRASE}", $msj, $ContenidoHTML);

	//	obtengo la DIRECCION del sitio
	$msj = "";
	if (isset($sitio_direccion) and ($sitio_direccion != "")) {
		$msj .= "<address>" . $sitio_direccion . "</address>";
	}
	$ContenidoHTML = str_replace("{DIRECCION}", $msj, $ContenidoHTML);

	//	actualizo los ENLACES PRINCIPALES
	$url = " data-toggle=\"modal\" data-backdrop=\"static\" data-target=\"#ModalMensajes\" href=\"javascript:void(0);\" data-tipo=\"Consultas\" title=\"Dejar consulta\"";
	$ContenidoHTML = str_replace("{CONTACTOS}", $url, $ContenidoHTML);
	$url = "href='html/rss.php' title='Fuentes RSS'";
	$ContenidoHTML = str_replace("{RSS}", $url, $ContenidoHTML);
	$ContenidoHTML = str_replace("{SITIO_EMAIL}", $sitio_email, $ContenidoHTML);

	//	actualizo el CONTADOR
	$contador_visitas_header = "";
	$registrosContador = $conexion->GetRow("SELECT count(*) as CANTIDAD FROM contador WHERE (id_usuario is null) ORDER BY id_contador DESC LIMIT 1");
	if ($registrosContador) {
		$contadorActual = 1637218;
		$contador_visitas = "\n";
		$contador_visitas .= "<div class=\"subSeccion\">Contador de Visitas</div>\n";
		$contador_visitas .= "<ul class=\"pager\">\n";
		$contador_visitas .= "	<li><p class='contador'>" . sprintf("%05s", ($registrosContador["CANTIDAD"] + $contadorActual)) . "</p></li>\n";
		$contador_visitas .= "</ul>\n";
		$ContenidoHTML = str_replace("{CONTADOR_VISITAS}", $contador_visitas, $ContenidoHTML);

		$contador_visitas_header .= "<h6>Contador de Visitas</h6>\n";
		$contador_visitas_header .= "<p class='contador'>" . sprintf("%05s", ($registrosContador["CANTIDAD"] + $contadorActual)) . "</p>\n";
		$ContenidoHTML = str_replace("{CONTADOR_VISITAS_FOODER}", $contador_visitas_header, $ContenidoHTML);
	}
	//	actualizo el CONTADOR y la BANDERA DE VISITA
	/*
		if(verificar_url('http://api.wipmania.com/')) {
			$valor_retornado = explode("<br>", file_get_contents('http://api.wipmania.com/'));
			$pais = $valor_retornado[1];
			if(file_exists("images/paises/".$pais.".gif")) {
				$datosPais = $conexion->GetRow("SELECT * FROM paises WHERE (codigo_pais = '".$pais."')");
				if($datosPais) {
					$ContenidoHTML = str_replace("{BANDERA}","<img src=\"images/paises/".$datosPais["CODIGO_PAIS"].".gif\" alt=\"".$datosPais["PAIS"]."\" title=\"".$datosPais["PAIS"]."\" />",$ContenidoHTML);
				}
			} else {
				$ContenidoHTML = str_replace("{BANDERA}",$pais,$ContenidoHTML);
			}
		}
		*/

	//	actualizo las REDES SOCIALES
	$redes_sociales = "\n";
	$redes_sociales .= "<div class=\"subSeccion\">Siguenos en</div>\n";
	$redes_sociales .= "<ul class=\"pager\">\n";
	if (isset($sitio_url_facebook) and ($sitio_url_facebook != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_facebook . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook.png\" width=\"32\" alt=\"facebook\" border=\"0\" /></a></li>\n";
	}
	if (isset($sitio_url_facebook_group) and ($sitio_url_facebook_group != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_facebook_group . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook-group.png\" width=\"32\" alt=\"facebook-group\" border=\"0\" /></a></li>\n";
	}
	if (isset($sitio_url_twitter) and ($sitio_url_twitter != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_twitter . "\" target=\"_blank\" alt=\"siguenos en twitter\" title=\"siguenos en twitter\"><img src=\"images/twitter.png\" width=\"32\" alt=\"twitter\" border=\"0\" /></a></li>\n";
	}
	if (isset($sitio_url_linkedin) and ($sitio_url_linkedin != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_linkedin . "\" target=\"_blank\" alt=\"siguenos en linkedin\" title=\"siguenos en linkedin\"><img src=\"images/linkedin.png\" width=\"32\" alt=\"linkedin\" border=\"0\" /></a></li>\n";
	}
	if (isset($sitio_url_google) and ($sitio_url_google != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_google . "\" target=\"_blank\" alt=\"siguenos en google+\" title=\"siguenos en google+\"><img src=\"images/google.png\" width=\"32\" alt=\"google\" border=\"0\" /></a></li>\n";
	}
	if (isset($sitio_url_youtube) and ($sitio_url_youtube != "")) {
		$redes_sociales .= "	<li><a href=\"" . $sitio_url_youtube . "\" target=\"_blank\" alt=\"siguenos en youtube\" title=\"siguenos en youtube\"><img src=\"images/youtube.png\" width=\"32\" alt=\"youtube\" border=\"0\" /></a></li>\n";
	}
	$redes_sociales .= "</ul>\n";

	$ContenidoHTML = str_replace("{REDES_SOCIALES}", $redes_sociales, $ContenidoHTML);

	//	actualizo las REDES SOCIALES 2019 del heaer y footer
	$redes_sociales_footer = "";
	$redes_sociales_header = "\n";
	$redes_sociales_header .= "<ul class=\"nav pull-right social-icons\">\n";
	if (isset($sitio_url_facebook) && ($sitio_url_facebook != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_facebook . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook.png\" width=\"16\" alt=\"facebook\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "	<a href=\"" . $sitio_url_facebook . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook.png\" width=\"16\" alt=\"facebook\" border=\"0\" /></a> ";
	}
	if (isset($sitio_url_facebook_group) && ($sitio_url_facebook_group != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_facebook_group . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook-group.png\" width=\"16\" alt=\"facebook-group\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "<a href=\"" . $sitio_url_facebook_group . "\" target=\"_blank\" alt=\"siguenos en facebook\" title=\"siguenos en facebook\"><img src=\"images/facebook-group.png\" width=\"16\" alt=\"facebook-group\" border=\"0\" /></a> ";
	}
	if (isset($sitio_url_twitter) && ($sitio_url_twitter != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_twitter . "\" target=\"_blank\" alt=\"siguenos en twitter\" title=\"siguenos en twitter\"><img src=\"images/twitter.png\" width=\"16\" alt=\"twitter\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "<a href=\"" . $sitio_url_twitter . "\" target=\"_blank\" alt=\"siguenos en twitter\" title=\"siguenos en twitter\"><img src=\"images/twitter.png\" width=\"16\" alt=\"twitter\" border=\"0\" /></a> ";
	}
	if (isset($sitio_url_linkedin) && ($sitio_url_linkedin != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_linkedin . "\" target=\"_blank\" alt=\"siguenos en linkedin\" title=\"siguenos en linkedin\"><img src=\"images/linkedin.png\" width=\"16\" alt=\"linkedin\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "<a href=\"" . $sitio_url_linkedin . "\" target=\"_blank\" alt=\"siguenos en linkedin\" title=\"siguenos en linkedin\"><img src=\"images/linkedin.png\" width=\"16\" alt=\"linkedin\" border=\"0\" /></a> ";
	}
	if (isset($sitio_url_google) && ($sitio_url_google != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_google . "\" target=\"_blank\" alt=\"siguenos en google+\" title=\"siguenos en google+\"><img src=\"images/google.png\" width=\"16\" alt=\"google\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "<a href=\"" . $sitio_url_google . "\" target=\"_blank\" alt=\"siguenos en google+\" title=\"siguenos en google+\"><img src=\"images/google.png\" width=\"16\" alt=\"google\" border=\"0\" /></a> ";
	}
	if (isset($sitio_url_youtube) && ($sitio_url_youtube != "")) {
		$redes_sociales_header .= "	<li><a href=\"" . $sitio_url_youtube . "\" target=\"_blank\" alt=\"siguenos en youtube\" title=\"siguenos en youtube\"><img src=\"images/youtube.png\" width=\"16\" alt=\"youtube\" border=\"0\" /></a></li>\n";
		$redes_sociales_footer .= "<a href=\"" . $sitio_url_youtube . "\" target=\"_blank\" alt=\"siguenos en youtube\" title=\"siguenos en youtube\"><img src=\"images/youtube.png\" width=\"16\" alt=\"youtube\" border=\"0\" /></a> ";
	}
	$redes_sociales_header .= "	<li><a href=\"https://maren.superzonegames.com/\" target=\"_blank\" alt=\"Visita MAREN\" title=\"Visita MAREN\">MAREN</a></li>\n";
	$redes_sociales_header .= "</ul>\n";

	$ContenidoHTML = str_replace("{REDES_SOCIALES_HEADER}", $redes_sociales_header, $ContenidoHTML);
	$ContenidoHTML = str_replace("{REDES_SOCIALES_FOOTER}", $redes_sociales_footer, $ContenidoHTML);

	//	verifico la habilitacion del boton del FORMULARIO DE INSCRIPCION
	$inscripcion_enlinea = "";
	if (isset($formulario_inscripcion_mostrar) and ($formulario_inscripcion_mostrar == "s") and (verificar_url($sitio_url . $path_imagenes . $formulario_inscripcion_boton))) {
		$inscripcion_enlinea .= "<a href=\"finscripcion.php\" target=\"_blank\" title=\"Inscripci&oacute;n en linea\"><img src=\"" . $sitio_url . $path_imagenes . $formulario_inscripcion_boton . "\" border=\"0\" alt=\"Inscripci&oacute;n en linea\" style=\"margin: 5px auto;\" /></a>";
	}
	$ContenidoHTML = str_replace("{INSCRIPCIONENLINEA}", $inscripcion_enlinea, $ContenidoHTML);

	//	actualizo el boton de la FICHA DE RELEVAMIENTO
	$bt_relevemiento = "";
	$bt_relevemiento .= "	<a href=\"./relevamiento\" target=\"_blank\" title=\"Ficha de Relevamiento\"><img src=\"images/boton_ficharelevamiento.jpg\" border=\"0\" alt=\"Ficha de Relevamiento\" style=\"margin: 5px auto;\" /></a>";
	$ContenidoHTML = str_replace("{BT_RELEVAMIENTO}", $bt_relevemiento, $ContenidoHTML);

	//	actualizo el boton del CENSO DE ARCHIVOS
	$bt_censoarchivos = "";
	$bt_censoarchivos .= "	<a href=\"./censo\" target=\"_blank\" title=\"Censo de Archivos\"><img src=\"images/boton_censo.jpg\" border=\"0\" alt=\"Censo de Archivos\" style=\"margin: 5px auto;\" /></a>";
	$ContenidoHTML = str_replace("{BT_CENSO_ARCHIVOS}", $bt_censoarchivos, $ContenidoHTML);

	//	actualizo el IFRAME de FACEBOOK
	$iframe_facebook = "";
	$iframe_facebook .= "<iframe src=\"//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FMundo-Archivistico%2F277141889016906&amp;width=220&amp;height=300&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=true&amp;header=true\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:220px; height:300px;\" allowTransparency=\"true\"></iframe>";
	$ContenidoHTML = str_replace("{IFRAME_FACEBOOK}", $iframe_facebook, $ContenidoHTML);

	//	actualizo el IFRAME del TRADUCTO DE GOOGLE
	$iframe_traductor = "\n";
	$iframe_traductor .= "<div id=\"google_translate_element\"></div>\n";
	$iframe_traductor .= "<script>\n";
	$iframe_traductor .= "function googleTranslateElementInit() {\n";
	$iframe_traductor .= "	new google.translate.TranslateElement({\n";
	$iframe_traductor .= "		pageLanguage: 'es'\n";
	$iframe_traductor .= "	}, 'google_translate_element');\n";
	$iframe_traductor .= "}\n";
	$iframe_traductor .= "</script>\n";
	$iframe_traductor .= "<script src=\"http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit\"></script>\n";
	$ContenidoHTML = str_replace("{IFRAME_TRADUCTOR}", $iframe_traductor, $ContenidoHTML);

	//	obtengo las imagenes del Carousel en el header
	$carousel_header = "<div id=\"myCarousel\" class=\"carousel slide\">";
	$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (activo = 1) and (upper(categoria) = 'PORTADA') and (id_sucursal = '".XPANEL_SUCURSAL."') ORDER BY id_foto DESC");
	//$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (activo = 1) ORDER BY id_foto DESC");
	if ($registros and $registros->RecordCount() > 0) {
		$carousel_header .= "<ol class=\"carousel-indicators\">\n";
		for ($i = 0; $i < $registros->RecordCount(); $i++) {
			if ($i == 0) {
				$carousel_header .= "	<li data-target=\"#myCarousel\" data-slide-to=\"" . $i . "\" class=\"active\"></li>\n";
			} else {
				$carousel_header .= "	<li data-target=\"#myCarousel\" data-slide-to=\"" . $i . "\"></li>\n";
			}
		}
		$carousel_header .= "</ol>\n";

		$i = 0;
		$carousel_header .= "<div class=\"carousel-inner\">\n";
		while (!$registros->EOF) {
			if ($i == 0) {
				$carousel_header .= "	<div class=\"item active\">\n";
			} else {
				$carousel_header .= "	<div class=\"item\">\n";
			}

			//	verifico si el archivo existe
			$nombre_completo_imagenoriginal = $path_imagenes . "fotogaleria/" . $registros->fields["NOMBRE"];
			if (file_exists($nombre_completo_imagenoriginal)) {
				$carousel_header .= "		<img src=\"" . $path_imagenes . "fotogaleria/" . $registros->fields["NOMBRE"] . "\" alt=\"\" />\n";
			} else {
				$carousel_header .= "		<img src=\"" . $path_imagenes . "publicidad_portada_1250x250.png" . "\" alt=\"\" />\n";
			}
			if (($registros->fields["DESCRIPCION"] != "") and ($registros->fields["DESCRIPCION"] != null)) {
				$carousel_header .= "		<div class=\"carousel-caption\">\n";
				$carousel_header .= "			" . stripslashes($registros->fields["DESCRIPCION"]) . "\n";
				$carousel_header .= "		</div>\n";
			}
			$carousel_header .= "	</div>\n";

			$registros->MoveNext();
			$i++;
		}
		$carousel_header .= "</div>\n";
		$registros->close();
	} else {
		$carousel_header .= "<ol class=\"carousel-indicators\">\n";
		$carousel_header .= "	<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>\n";
		$carousel_header .= "</ol>\n";
		$carousel_header .= "<div class=\"carousel-inner\">\n";
		$carousel_header .= "	<div class=\"item active\">\n";
		$carousel_header .= "		<img src=\"" . $path_imagenes . "publicidad_portada_1250x250.png\" alt=\"\" />\n";
		$carousel_header .= "		<div class=\"carousel-caption\">\n";
		$carousel_header .= "			<h4>Atenci&oacute;n</h4>\n";
		$carousel_header .= "			<p>No hay im&aacute;genes de portada registradas.</p>\n";
		$carousel_header .= "		</div>\n";
		$carousel_header .= "	</div>\n";
		$carousel_header .= "</div>\n";
	}
	$carousel_header .= "	</div>\n";
	$ContenidoHTML = str_replace("{CAROUSEL_HEADER}", $carousel_header, $ContenidoHTML);

	echo ($ContenidoHTML);
}
$conexion->close();
$temp->close();
$temp2->close();
$temp3->close();
$temp4->close();
$privilegios->close();
