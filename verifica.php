<?php
// Motor autentificación usuarios.

// Cargar datos conexion y otras variables.
require ("xpanel/config.php");
// chequear si se llama directo al script.
if (!isset($_SERVER['HTTP_REFERER']) or ($_SERVER['HTTP_REFERER'] == "")){
	header ("Location: ./?menu=iniciarsesion&error_login=7");
	exit;
} else {
	// chequear página que lo llama para devolver errores a dicha página.
	$url = explode("?",$_SERVER['HTTP_REFERER']);
	$pag_referida=$url[0];
	$redir=$pag_referida;
}

// Chequeamos si se está autentificandose un usuario por medio del formulario
if (isset($_POST['user']) and isset($_POST['pass'])) {
	// realizamos la consulta a la BD para chequear datos del Usuario.
	$registros = $conexion->GetRow("SELECT id_usuario,nombre_completo,email,usuario,clave2,tipo_usuario,usuario_activo FROM usuarios WHERE (usuario='".$_POST['user']."')");

	// miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
	if ($registros) {

		// eliminamos barras invertidas y dobles en sencillas
		$login = stripslashes($_POST['user']);
		// encriptamos el password en formato md5 irreversible.
		$pass = $_POST['pass'];
		$password = md5($_POST['pass']);

		// almacenamos datos del Usuario en un array para empezar a chequear.
		$usuario_datos = $registros;
 
		//verifico si el Usuario esta habilitado
		if ($usuario_datos['usuario_activo'] != 1) {
			Header ("Location: ./?menu=iniciarsesion&error_login=9");
			exit;
		}

		// chequeamos el nombre del usuario otra vez contrastandolo con la BD
		// esta vez sin barras invertidas, etc ...
		// si no es correcto, salimos del script con error 4 y redireccionamos a la
		// página de error.
		if ($login != $usuario_datos['usuario']) {
			Header ("Location: ./?menu=iniciarsesion&error_login=4");
			exit;
		}

		// si el password no es correcto ..
		// salimos del script con error 3 y redireccinamos hacia la página de error
		if ($password != $usuario_datos['clave2']) {
			Header ("Location: ./?menu=iniciarsesion&error_login=3");
			exit;
		}

		// Paranoia: destruimos las variables login y password usadas
		unset($login);
		unset($password);

		// En este punto, el usuario ya esta validado.
		// Grabamos los datos del usuario en una sesion.
		session_name($usuarios_sesion2);
		session_start();
		session_cache_limiter('nocache,private');
		
		// Asignamos variables de sesión con datos del Usuario para el uso en el
		// resto de páginas autentificadas.
		$_SESSION['usuario_id'] = $usuario_datos['id_usuario'];
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

		//registro en el contador su acceso
		$ip = $_SERVER["REMOTE_ADDR"];
		$nombre = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);
		$idioma = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		$datos = $_SERVER["HTTP_USER_AGENT"];
		$fecha = date("Y/m/d");
		$hora = date("H:i:s");
		$query_string = $_SERVER["QUERY_STRING"];
		$http_referer = $_SERVER["HTTP_REFERER"];

		if($_SESSION['usuario_id'] != '0') {
			$conexion->Query("INSERT INTO contador (ID_CONTADOR,ID_USUARIO,IP,NOMBRE,IDIOMA,DATOS,FECHA,HORA,QUERY_STRING,HTTP_REFERER) values(null,'".$_SESSION['usuario_id']."','".$ip."','".$nombre."','".$idioma."','".$datos."','".$fecha."','".$hora."','".$query_string."','".$http_referer."')");		
		}

		// Hacemos una llamada a si mismo (scritp) para que queden disponibles
		// las variables de session en el array asociado $HTTP_...
		$pag = $_SERVER['PHP_SELF']; // URL actual que se esta ejecutando
		//$pag = "./?menu=usuarios"; // URL actual que se esta ejecutando
		Header ("Location: $pag?".session_name()."=".session_id());
		exit;
		
	} else {
		  // si no esta el nombre de usuario en la BD o el password ..
		  // se devuelve a pagina que lo llamo con un numero de error
		  Header ("Location: ./?menu=iniciarsesion&error_login=2");
		  exit;
	}
} else {
	// -------- Chequear sesión existe -------

	// usamos la sesion de nombre definido.
	session_name($usuarios_sesion2);
	// Iniciamos el uso de sesiones
	session_start();

	if (!isset($_SESSION['usuario_login']) and !isset($_SESSION['usuario_password'])){
		// Borramos la sesion creada por el inicio de session anterior
		session_destroy();
		header ("Location: ./?menu=iniciarsesion&error_login=6");
		exit;
	}
}
?>