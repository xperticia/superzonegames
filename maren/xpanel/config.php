<?php
//---------------------------------------------------------------------------------------------------------------------
// Autor	: Walter E. Irahola
// E-mail	: wirahola@hotmail.com
// Sitio	: XPERTICIA.com
//---------------------------------------------------------------------------------------------------------------------

$nombre_sistema = "XPANEL 3 lite";
// Nombre de la session (puede dejar este mismo)
$usuarios_sesion  = "maren_xpanel3";
$usuarios_sesion2  = "umaren";

ini_set('date.timezone', 'America/La_Paz');
ini_set("session.cookie_lifetime","14400"); // 4 horas
ini_set("session.gc_maxlifetime","14400");

// Mensajes de error.
$msg_error[0]="No se pudo conectar con Base de datos.";
$msg_error[1]="No se pudo realizar consulta a la Base de datos.";
$msg_error[2]="Usuario o Password no valido.";
$msg_error[3]="Password no valido.";
$msg_error[4]="Usuario no existe.";
$msg_error[5]="No está autorizado para realizar esta acción o entrar en esta página.";
$msg_error[6]="Acceso no autorizado! Registrese.";
$msg_error[7]="Acceso incorrecto!";
$msg_error[8]="El correo electrónico que ingreso ya existe!.";
$msg_error[9]="Usuario deshabilitado para el acceso [NO ACTIVO].";
$msg_error[10]="Tipo de archivo no permitido. Solo se admiten imágenes de tipo: JPG, PNG o GIF.";
$msg_error[11]="El usuario no posee contenidos habilitados.";
$msg_error[12]="Debe configurar la plantilla o el estilo del sitio.";
$msg_error[13]="El Usuario no está habilitado para el acceso a la Sucursal seleccionada.";

//	reCAPTCHA
//---------------------------------------------------------------------------------------------------------------------
$publickey = "6LefpQoAAAAAAEnb5O_wX4njboP0xCLBDB73jfNU";
$privatekey = "6LefpQoAAAAAAHOG5uPZsDzWERQpiERniGwxskBJ";
$resp = null;	# the response from reCAPTCHA
$error = null;	# the error code from reCAPTCHA, if any

//	datos de configuracion del sitio
//---------------------------------------------------------------------------------------------------------------------
include_once("datos.php");

//	phpMailes: clase para enviar mensajes de correo electronico
//---------------------------------------------------------------------------------------------------------------------
//include_once("scripts/phpmailer/class.phpmailer.php");

//	funciones varias para trabajar con datos desde PHP
//---------------------------------------------------------------------------------------------------------------------
include_once("scripts/funciones.php");

//	ADODB: clase para trabajar con bases de datos
//---------------------------------------------------------------------------------------------------------------------
include_once('scripts/adodb5/adodb.inc.php'); 

//	Definicion de variables de acceso a base de datos
//---------------------------------------------------------------------------------------------------------------------

$conexion = ADONewConnection($tipo_database); 
$conexion->SetFetchMode(ADODB_FETCH_ASSOC);
//$conexion->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$conexion->Connect($server,$user,$password,$database);
$conexion->setCharSet('utf8');
$temp = ADONewConnection($tipo_database); 
$temp->SetFetchMode(ADODB_FETCH_ASSOC);
//$temp->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$temp->Connect($server,$user,$password,$database);
$temp->setCharSet('utf8');
$temp2 = ADONewConnection($tipo_database); 
$temp2->SetFetchMode(ADODB_FETCH_ASSOC);
//$temp2->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$temp2->Connect($server,$user,$password,$database);
$temp2->setCharSet('utf8');
$temp3 = ADONewConnection($tipo_database); 
$temp3->SetFetchMode(ADODB_FETCH_ASSOC);
//$temp3->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$temp3->Connect($server,$user,$password,$database);
$temp3->setCharSet('utf8');
$temp4 = ADONewConnection($tipo_database); 
$temp4->SetFetchMode(ADODB_FETCH_ASSOC);
//$temp4->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$temp4->Connect($server,$user,$password,$database);
$temp4->setCharSet('utf8');
$privilegios = ADONewConnection($tipo_database); 
$privilegios->SetFetchMode(ADODB_FETCH_ASSOC);
//$privilegios->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8');
$privilegios->Connect($server,$user,$password,$database);
$privilegios->setCharSet('utf8');

$fondo_filas_par = "#ffffff";
$fondo_filas_impar = "#efefef";
$fondo_filas_sobre = "#ffffcc";

$mes["01"] = "Enero";
$mes["02"] = "Febrero";
$mes["03"] = "Marzo";
$mes["04"] = "Abril";
$mes["05"] = "Mayo";
$mes["06"] = "Junio";
$mes["07"] = "Julio";
$mes["08"] = "Agosto";
$mes["09"] = "Septiembre";
$mes["10"] = "Octubre";
$mes["11"] = "Noviembre";
$mes["12"] = "Diciembre";

$dias["01"] = "Lunes";
$dias["02"] = "Martes";
$dias["03"] = "Miércoles";
$dias["04"] = "Jueves";
$dias["05"] = "Viernes";
$dias["06"] = "Sabado";
$dias["07"] = "Domingo";

$Compartir_ContenidoLateral = "";
$Compartir_ContenidoLateral .= "<div class=\"Compartir_ContenidoLateral\">\n";
$Compartir_ContenidoLateral .= "	<iframe src='//www.facebook.com/plugins/like.php?href={URL}&amp;send=false&amp;layout=box_count&amp;width=75&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=63' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:75px; height:43px;' allowTransparency='true'></iframe>\n";
$Compartir_ContenidoLateral .= "	<a href='https://twitter.com/share' class='twitter-share-button' data-count='vertical' data-via='wirahola' data-lang='es'>Tweet</a><script type='text/javascript' src='//platform.twitter.com/widgets.js'></script>\n";
$Compartir_ContenidoLateral .= "	<script src='https://apis.google.com/js/platform.js' async defer></script>\n";
$Compartir_ContenidoLateral .= "	<g:plusone></g:plusone>\n";
$Compartir_ContenidoLateral .= "	<script src=\"//platform.linkedin.com/in.js\" type=\"text/javascript\"> lang: es_ES </script><script type=\"IN/Share\" data-url=\"{URL}\" data-counter=\"top\"></script>\n";
$Compartir_ContenidoLateral .= "</div>\n";
				
$Compartir_ContenidoHorizontal = "";
$Compartir_ContenidoHorizontal .= "<div class=\"Compartir_ContenidoHorizontal\">\n";
$Compartir_ContenidoHorizontal .= "	<strong style='margin-right: 10px;vertical-align: top;'>Compartir:  </strong>\n";
$Compartir_ContenidoHorizontal .= "	<iframe src='//www.facebook.com/plugins/like.php?href={URL}&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:90px; height:21px;' allowTransparency='true'></iframe>\n";
$Compartir_ContenidoHorizontal .= "	<a href='https://twitter.com/share' class='twitter-share-button' data-count='vertical' data-via='wirahola' data-lang='es'>Tweet</a><script type='text/javascript' src='//platform.twitter.com/widgets.js'></script>\n";
$Compartir_ContenidoHorizontal .= "	<script src='https://apis.google.com/js/platform.js' async defer></script>\n";
$Compartir_ContenidoHorizontal .= "	<g:plusone size='medium'></g:plusone>\n";
$Compartir_ContenidoHorizontal .= "	<script src=\"//platform.linkedin.com/in.js\" type=\"text/javascript\"> lang: es_ES </script><script type=\"IN/Share\" data-url=\"{URL}\" data-counter=\"right\"></script>\n";
$Compartir_ContenidoHorizontal .= "</div>\n";

$mapa_latitud = "-21.533956708413967";
$mapa_longitud = "-64.73425290478514";

?>
