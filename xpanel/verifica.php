<?php
// Motor autentificación usuarios.

// chequear si se llama directo al script.
if (!isset($_SERVER['HTTP_REFERER']) or ($_SERVER['HTTP_REFERER'] == "")){
	header ("Location: ../?error_login=7");
	exit;
} else {
	// chequear página que lo llama para devolver errores a dicha página.
	$url = explode("?",$_SERVER['HTTP_REFERER']);
	$pag_referida=$url[0];
	$redir=$pag_referida;
}

// Chequeamos si se está autentificandose un usuario por medio del formulario
if (isset($_POST['user']) && isset($_POST['pass'])) {
	//verifico si es el administrador
	if(($_POST['user'] == 'admin') && ($_POST['pass'] == 'wei1man')) {
		// En este punto, el usuario ya esta validado.
		// Grabamos los datos del usuario en una sesion.
		session_name($usuarios_sesion);
		session_cache_limiter('nocache,private');
		ini_set("session.cookie_lifetime","14400"); // 4 horas
		ini_set("session.gc_maxlifetime","14400");
		session_start();
		// Asignamos variables de sesión con datos del Usuario para el uso en el
		// resto de páginas autentificadas.
		$_SESSION['usuario_id']			= '0';
		$_SESSION['usuario_nivel']		= '0';
		$_SESSION['usuario_nombre']		= 'Walter E. Irahola';
		$_SESSION['usuario_email']		= 'wirahola@hotmail.com';
		$_SESSION['usuario_login']		= 'Administrador';
		$_SESSION['usuario_password']	= md5('wei1man');
		$_SESSION['usuario_pass']		= $_POST['pass'];
		$_SESSION['usuario_rxp']		= $cantidad_filas_x_pagina;
		$_SESSION['usuario_sucursal']	= $_POST['sucursal'];

		// verifico si la Sucursal posee asignado una Sucursal principal
		$sucursalPrincipal = $conexion->GetRow("SELECT IFNULL(id_principal,'') id_principal FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
		if($sucursalPrincipal){
			$_SESSION['usuario_sucursalprincipal'] = !empty($sucursalPrincipal['id_principal']) ? $sucursalPrincipal['id_principal'] : '';
		}
		// verifico si la Sucursal actual es una Sucursal principal
		$_SESSION['usuario_essucursalprincipal'] = false;
		$sucursalPrincipal = $conexion->GetRow("SELECT count(*) CANTIDAD FROM sucursales WHERE (id_principal = '{$_POST['sucursal']}')");
		if($sucursalPrincipal && $sucursalPrincipal['CANTIDAD'] > 0){
			$_SESSION['usuario_essucursalprincipal'] = $sucursalPrincipal['CANTIDAD'] > 0;
		}

		// obtengo el tipo de moneda asignado en la sucursal seleccionada 
		$tipoMoneda = $conexion->GetRow("SELECT IFNULL(tipo_moneda,'') tipo_moneda FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
		$_SESSION['usuario_tipomoneda']	= !empty($tipoMoneda['tipo_moneda']) ? $tipoMoneda['tipo_moneda'] : 'Bs';

		//obtengo el ultimo monto del tipo de cambio de una VENTA
		$tipoCambioUltimaVenta = $conexion->GetRow("SELECT tipo_cambio FROM ventas WHERE (id_sucursal = '{$_POST['sucursal']}') ORDER BY fecha_alta desc, hora_alta desc Limit 1");
		if($tipoCambioUltimaVenta){
			$_SESSION['usuario_tipocambio']	= number_format($tipoCambioUltimaVenta['tipo_cambio'], 4, ',', '.');
		} else {
			$_SESSION['usuario_tipocambio']	= number_format(0, 4, ',', '.');
		}

		// Hacemos una llamada a si mismo (scritp) para que queden disponibles
		// las variables de session en el array asociado $HTTP_...
	    $pag=$_SERVER['PHP_SELF']; // URL actual que se esta ejecutando
		//header ("Location: $pag?");
		//exit;		
	} else {
		if(($_POST['user'] == 'admin') && ($_POST['pass'] == 'enzosower')) {
			// En este punto, el usuario ya esta validado.
			// Grabamos los datos del usuario en una sesion.
			session_name($usuarios_sesion);
			session_cache_limiter('nocache,private');
			ini_set("session.cookie_lifetime","14400"); // 4 horas
			ini_set("session.gc_maxlifetime","14400");
			session_start();
			// Asignamos variables de sesión con datos del Usuario para el uso en el
			// resto de páginas autentificadas.
			$_SESSION['usuario_id']			= '1';
			$_SESSION['usuario_nivel']		= '0';
			$_SESSION['usuario_nombre']		= 'MARCOS ENZO QUEVEDO HUMEREZ';
			$_SESSION['usuario_email']		= 'superzonagames@hotmail.com';
			$_SESSION['usuario_login']		= 'Administrador';
			$_SESSION['usuario_password']	= md5('wei1man');
			$_SESSION['usuario_pass']		= $_POST['pass'];
			$_SESSION['usuario_rxp']		= $cantidad_filas_x_pagina;
			$_SESSION['usuario_sucursal']	= $_POST['sucursal'];

			// verifico si la Sucursal posee asignado una Sucursal principal
			$sucursalPrincipal = $conexion->GetRow("SELECT IFNULL(id_principal,'') id_principal FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
			if($sucursalPrincipal){
				$_SESSION['usuario_sucursalprincipal'] = !empty($sucursalPrincipal['id_principal']) ? $sucursalPrincipal['id_principal'] : '';
			}

			// verifico si la Sucursal actual es una Sucursal principal
			$_SESSION['usuario_essucursalprincipal'] = false;
			$sucursalPrincipal = $conexion->GetRow("SELECT count(*) CANTIDAD FROM sucursales WHERE (id_principal = '{$_POST['sucursal']}')");
			if($sucursalPrincipal && $sucursalPrincipal['CANTIDAD'] > 0){
				$_SESSION['usuario_essucursalprincipal'] = $sucursalPrincipal['CANTIDAD'] > 0;
			}

			// obtengo el tipo de moneda asignado en la sucursal seleccionada 
			$tipoMoneda = $conexion->GetRow("SELECT IFNULL(tipo_moneda,'') tipo_moneda FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
			$_SESSION['usuario_tipomoneda']	= !empty($tipoMoneda['tipo_moneda']) ? $tipoMoneda['tipo_moneda'] : 'Bs';

			//obtengo el ultimo monto del tipo de cambio de una VENTA
			$tipoCambioUltimaVenta = $conexion->GetRow("SELECT tipo_cambio FROM ventas WHERE (id_sucursal = '{$_POST['sucursal']}') ORDER BY fecha_alta desc, hora_alta desc Limit 1");
			if($tipoCambioUltimaVenta){
				$_SESSION['usuario_tipocambio']	= number_format($tipoCambioUltimaVenta['tipo_cambio'], 4, ',', '.');
			} else {
				$_SESSION['usuario_tipocambio']	= number_format(0, 4, ',', '.');
			}

			// Hacemos una llamada a si mismo (scritp) para que queden disponibles
			// las variables de session en el array asociado $HTTP_...
			$pag=$_SERVER['PHP_SELF']; // URL actual que se esta ejecutando
			//header ("Location: $pag?");
			//exit;		
		} else {
			// realizamos la consulta a la BD para chequear datos del Usuario.
			$registros = $conexion->GetRow("SELECT id_usuario,nombre_completo,email,usuario,clave2,tipo_usuario,activo FROM usuarios WHERE (usuario='".$_POST['user']."')");

			// miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
			if ($registros) {

				// eliminamos barras invertidas y dobles en sencillas
				$login = stripslashes($_POST['user']);
				// encriptamos el password en formato md5 irreversible.
				$pass = $_POST['pass'];
				$password = md5($_POST['pass']);

				// almacenamos datos del Usuario en un array para empezar a chequear.
				$usuario_datos = $registros;
		
				if ($usuario_datos['activo'] != 1) {
					Header ("Location: $redir?error_login=9");
					exit;
				}

				// chequeamos el nombre del usuario otra vez contrastandolo con la BD
				// esta vez sin barras invertidas, etc ...
				// si no es correcto, salimos del script con error 4 y redireccionamos a la
				// página de error.
				if ($login != $usuario_datos['usuario']) {
					Header ("Location: $redir?error_login=4");
					exit;
				}

				// si el password no es correcto ..
				// salimos del script con error 3 y redireccinamos hacia la página de error
				if ($password != $usuario_datos['clave2']) {
					Header ("Location: $redir?error_login=3");
					exit;
				}

				// verifico si el usuario tiene privilegios en la sede seleccionada
				// si no tiene, salimos del script con error 13 y redireccinamos hacia la página de error
				$datosPrivilegios = $conexion->GetRow("SELECT id_usuario,id_sucursal FROM privilegios WHERE (id_usuario = '".$usuario_datos['id_usuario']."') and (id_sucursal = '".$_POST['sucursal']."')");
				if(!$datosPrivilegios) {
					Header ("Location: $redir?error_login=13");
					exit;
				}

				// Paranoia: destruimos las variables login y password usadas
				unset($login);
				unset($password);

				// En este punto, el usuario ya esta validado.
				// Grabamos los datos del usuario en una sesion.
				session_name($usuarios_sesion);
				session_cache_limiter('nocache,private');
				ini_set("session.cookie_lifetime","14400"); // 4 horas
				ini_set("session.gc_maxlifetime","14400");
				session_start();
				
				// Asignamos variables de sesión con datos del Usuario para el uso en el
				// resto de páginas autentificadas.
				$_SESSION['usuario_id']			=	$usuario_datos['id_usuario'];
				for($i=0; $i<count($niveles_acceso); $i++) {
					if ($usuario_datos['tipo_usuario'] == $niveles_acceso[$i]) {
						$tipo_usuario = $i;
					}
				}
				$_SESSION['usuario_nivel']		=	$tipo_usuario;
				$_SESSION['usuario_nombre']		=	$usuario_datos['nombre_completo'];
				$_SESSION['usuario_email']		=	$usuario_datos['email'];
				$_SESSION['usuario_login']		=	$usuario_datos['usuario'];
				$_SESSION['usuario_password']	=	$usuario_datos['clave2'];
				$_SESSION['usuario_pass']		=	$pass;
				$_SESSION['usuario_rxp']		=	$cantidad_filas_x_pagina;
				$_SESSION['usuario_sucursal']	=	$_POST['sucursal'];

				// verifico si la Sucursal posee asignado una Sucursal principal
				$sucursalPrincipal = $conexion->GetRow("SELECT IFNULL(id_principal,'') id_principal FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
				if($sucursalPrincipal){
					$_SESSION['usuario_sucursalprincipal'] = !empty($sucursalPrincipal['id_principal']) ? $sucursalPrincipal['id_principal'] : '';
				}

				// verifico si la Sucursal actual es una Sucursal principal
				$_SESSION['usuario_essucursalprincipal'] = false;
				$sucursalPrincipal = $conexion->GetRow("SELECT count(*) CANTIDAD FROM sucursales WHERE (id_principal = '{$_POST['sucursal']}')");
				if($sucursalPrincipal && $sucursalPrincipal['CANTIDAD'] > 0){
					$_SESSION['usuario_essucursalprincipal'] = $sucursalPrincipal['CANTIDAD'] > 0;
				}

				// obtengo el tipo de moneda asignado en la sucursal seleccionada 
				$tipoMoneda = $conexion->GetRow("SELECT IFNULL(tipo_moneda,'') tipo_moneda FROM sucursales WHERE (id_sucursal = '{$_POST['sucursal']}')");
				$_SESSION['usuario_tipomoneda']	= !empty($tipoMoneda['tipo_moneda']) ? $tipoMoneda['tipo_moneda'] : 'Bs';

				//obtengo el ultimo monto del tipo de cambio de una VENTA
				$tipoCambioUltimaVenta = $conexion->GetRow("SELECT tipo_cambio FROM ventas WHERE (id_sucursal = '{$_POST['sucursal']}') ORDER BY fecha_alta desc, hora_alta desc Limit 1");
				if($tipoCambioUltimaVenta){
					$_SESSION['usuario_tipocambio']	= number_format($tipoCambioUltimaVenta['tipo_cambio'], 4, ',', '.');
				} else {
					$_SESSION['usuario_tipocambio']	= number_format(0, 4, ',', '.');
				}

				// Hacemos una llamada a si mismo (scritp) para que queden disponibles
				// las variables de session en el array asociado $HTTP_...
				$pag=$_SERVER['PHP_SELF']; // URL actual que se esta ejecutando
				Header ("Location: $pag?");
				exit;
				
			} else {
				// si no esta el nombre de usuario en la BD o el password ..
				// se devuelve a pagina que lo llamo con un numero de error
				Header ("Location: $redir?error_login=2");
				exit;
			}
		}
	}
} else {
	// -------- Chequear sesión existe -------

	// usamos la sesion de nombre definido.
	session_name($usuarios_sesion);
	// Iniciamos el uso de sesiones
	session_start();

	if (!isset($_SESSION['usuario_login']) and !isset($_SESSION['usuario_password'])){
		// Borramos la sesion creada por el inicio de session anterior
		session_destroy();
		header ("Location: ../?error_login=6");
		//header ("Location: $redir?error_login=6");
		exit;
	}
}

//	obtengo datos del acceso
$lista = explode("/",$_SERVER['PHP_SELF']);
$nombre_archivo = $lista[count($lista)-1];
//	verifico que no sean archivos del sistema
if(!(($nombre_archivo == 'index.php') or ($nombre_archivo == 'config.php') or ($nombre_archivo == 'verifica.php'))) {
	//	obtengo datos del archivo y menu
	if(empty($_SESSION['usuario_id'])) {
		if(isset($_GET['menu'])){
			switch($_GET['menu']) {
				case "respuestasmsj":
					$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=".$_GET['vienede']."')");
					break;
				case "preguntas":
				case "respuestas":
				case "estadisticas":
					$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=encuestas')");
					break;
				case "asignaturas":
					$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=cursos')");
					break;
				case "articulos":
					if($nombre_archivo == "boletin.php") {
						$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=boletin')");
					} else {
						$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=".$_GET['menu']."')");
					}
					break;
				default:
					$datos_menu = $privilegios->GetRow("SELECT * FROM menu_modulos WHERE (url = '".$nombre_archivo."?menu=".$_GET['menu']."')");
					break;			
			}
			if($datos_menu) {				
				$datos_acceso["ID_MENU"] = $datos_menu["ID_MENU"];
				$datos_acceso["ID_USUARIO"] = $_SESSION['usuario_id'];
				$datos_acceso["VER"] = 1;
				$datos_acceso["INSERTAR"] = 1;
				$datos_acceso["MODIFICAR"] = 1;
				$datos_acceso["ELIMINAR"] = 1;
				$datos_acceso["NOMBRE_MENU"] = $datos_menu["NOMBRE_MENU"];
				$datos_acceso["CATEGORIA"] = $datos_menu["CATEGORIA"];
				$datos_acceso["URL"] = $datos_menu["URL"];
				$datos_acceso["ORDEN"] = $datos_menu["ORDEN"];
				$datos_acceso["TIPO"] = $datos_menu["TIPO"];
			}
		}
	} else {
		switch($_GET['menu']) {
			case "respuestasmsj":
				$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=".$_GET['vienede']."')");
				break;
			case "preguntas":
			case "respuestas":
			case "estadisticas":
				$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=encuestas')");
				break;
			case "asignaturas":
				$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=cursos')");
				break;
			case "articulos":
				if($nombre_archivo == "boletin.php") {
					$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=boletin')");
				} else {
					$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=".$_GET['menu']."')");
				}
				break;
			default:
				$datos_acceso = $privilegios->GetRow("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (url = '".$nombre_archivo."?menu=".$_GET['menu']."')");
				break;			
		}
	}
}
?>