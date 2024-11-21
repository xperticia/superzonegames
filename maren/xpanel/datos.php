<?php

//	Datos de la Empresa/Institución	
//	----------------------------------------
$sitio_nombre 				= 'Maren';
$sitio_url 					= 'http://localhost/superzonegames.com/sitioweb/maren/';
$sitio_eslogan 				= 'siempre un paso adelante...';
$sitio_direccion 			= 'Bolivar #183, entre Colon y Suipacha - Tarija (Bolivia)';
$sitio_telefono 			= '(591) 466-53343 / (591) 70221200 / (591) 72981932';
$sitio_email 				= 'info@superzonagames.com';
$sitio_email_admin 			= 'wirahola@hotmail.com';
$sitio_email_confirmacion 	= 'info@superzonagames.com';
$sitio_plantilla 			= 'html5_2019-05/index.html';
$sitio_estilo 				= 'xsite.css';

define('XPANEL_SITIO_URL_HOST', 'http://localhost/superzonegames.com/sitioweb/');
define('XPANEL_SUCURSAL', '4');

$sitio_logo_mostrar 	= '';
$sitio_logo_archivo 	= 'logo.png';

//	Datos de conexion a la base de datos	
//	----------------------------------------
$tipo_database 	= 'mysqli';
$server 		= 'localhost';
$database 		= 'c1340097_xpanel3';
$user 			= 'c1340097';
$password 		= 'tiFOmupo52';

//	Carpetas	
//	----------------------------------------
$path_imagenes 		= 'images/';
$path_archivos 		= 'archivos/';
$path_modulos 		= 'xpanel/modulos/';
$path_scripts 		= 'xpanel/scripts/';
$path_plantillas 	= 'templates/';

//	Publicidad	
//	----------------------------------------
$publicidad_portada_mostrar 	= 's';
$publicidad_portada_archivo 	= 'publicidad_portada_subido.png';
$publicidad_portada_enlace 		= '';
$publicidad_h_mostrar 			= 's';
$publicidad_h_archivo 			= 'publicidad_h_subido.png';
$publicidad_h_enlace 			= '';
$publicidad_v_mostrar 			= 's';
$publicidad_v_archivo 			= 'publicidad_v_subido.jpg';
$publicidad_v_enlace 			= '';

$frase 			= '';
$frase_autor 	= '';

//	Niveles de Acceso	
//	----------------------------------------
$niveles_acceso[0] = 'Administrador';
$niveles_acceso[1] = 'Responsable';
$niveles_acceso[2] = 'Empleado';
$niveles_acceso[3] = 'Proveedor';
$niveles_acceso[4] = 'Cliente';
$niveles_acceso[5] = 'Visitante';

//	Paginador	
//	----------------------------------------
$cantidad_filas_x_pagina 		= 30;
$cantidad_enlaces_x_pagina 		= 2;

//	Datos para los buscadores	
//	----------------------------------------
$tags_title 		= '';
$tags_keywords 		= '';
$tags_description 	= '';

//	Estados	
//	----------------------------------------
$estados[0] = 'NO';
$estados[1] = 'SI';

//	Tipos de Documentos	
//	----------------------------------------
$tipo_documento[0] = 'DNI';
$tipo_documento[1] = 'CI';
$tipo_documento[2] = 'LC';
$tipo_documento[3] = 'LE';
$tipo_documento[4] = 'PSP';

//	Precision	
//	----------------------------------------
$cantidad_decimales 	= 3;

//	Ubicación	
//	----------------------------------------
$sitio_apimapa 	= 'googlemaps';
$sitio_typemapa = '';
$sitio_keymapa 	= 'AIzaSyBhEkplWNuoX9zNY6M3Tq5FvZ76wJInf9A';
$latitud 		= -21.533907016779;
$longitud 		= -64.734352827072;

//	Google Analytics	
//	----------------------------------------
$ga_usuario 		= 'wirahola@gmail.com';
$ga_password 		= 'twz783';
$ga_id 				= '74176325';

//	Redes Sociales	
//	----------------------------------------
$sitio_url_facebook 			= 'http://www.facebook.com/superzonagames';
$sitio_url_facebook_group 		= 'https://www.facebook.com/groups/superzonagames';
$sitio_url_twitter 				= 'https://twitter.com/superzonagames1';
$sitio_url_linkedin 			= '';
$sitio_url_google 				= '';
$sitio_url_youtube 				= 'https://www.youtube.com/channel/UCC2FRUTr7fHBUr1HwDTMV3g';

//	Colores para las Categorias de Productos	
//	--------------------------------------------
$colores_categoriasProductos[0] 	= 'PC-DVD|#ff2f2f';
$colores_categoriasProductos[1] 	= 'PS2|#3e9eff';
$colores_categoriasProductos[2] 	= 'PS3|#c0c0c0';
$colores_categoriasProductos[3] 	= 'PS4|#0000ff';
$colores_categoriasProductos[4] 	= 'WII|#00ffff';
$colores_categoriasProductos[5] 	= 'XBOX360|#4fff4f';
$colores_categoriasProductos[6] 	= 'CONSOLAS|#f8cd21';
$colores_categoriasProductos[7] 	= 'PSVITA|#c0c0c0';
$colores_categoriasProductos[8] 	= 'XBOX ONE|#0c9813';
$colores_categoriasProductos[9] 	= 'NINTENDO 3DS|#f44d6a';
$colores_categoriasProductos[10] 	= 'WII U|#ffff80';

//	Colores para los Estados de las Ventas	
//	----------------------------------------
$colores_estadosVentas[0] 	= 'Pendiente|#ffff00';

//	Colores para las Ventas Pagadas o No	
//	----------------------------------------
$colores_pagadoVentas[0] 	= 'NO|#ff0032';

//	Colores para los Estados de los Servicios Técnicos	
//	----------------------------------------------------
$colores_estadosServiciosTecnicos[0] 	= 'Sin diagnóstico|#ff0000';
$colores_estadosServiciosTecnicos[1] 	= 'Con diagnóstico|#ffff00';
$colores_estadosServiciosTecnicos[2] 	= 'Con diagnóstico confirmado|#2fa8e1';
$colores_estadosServiciosTecnicos[3] 	= 'Con diagnóstico cancelado|#c0c0c0';
$colores_estadosServiciosTecnicos[4] 	= 'Reparado|#00ff00';

//	Colores para los Estados de los Pedidos	
//	----------------------------------------
$colores_estadosPedidos[0] 	= 'Pendiente|#ffff00';
$colores_estadosPedidos[1] 	= 'Cancelado|#ff0000';

?>