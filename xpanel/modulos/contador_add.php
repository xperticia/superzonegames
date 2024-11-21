<?php 
	// Autor: Walter E. Irahola
	// E-mail: wirahola@hotmail.com

	ini_set('date.timezone', 'America/La_Paz');

	$ip = getenv("REMOTE_ADDR");
	$nombre = @gethostbyaddr($ip);
	$idioma = getenv("HTTP_ACCEPT_LANGUAGE");
	$datos = getenv("HTTP_USER_AGENT");
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$query_string = getenv("QUERY_STRING");
	$http_referer = getenv("HTTP_REFERER");

	if(isset($_SESSION['usuario_id']) and ($_SESSION['usuario_id'] != 0)) {
		$conexion->Query("INSERT INTO contador (ID_CONTADOR,IP,NOMBRE,IDIOMA,DATOS,FECHA,HORA,QUERY_STRING,HTTP_REFERER,ID_USUARIO,ID_SUCURSAL) values(null,'".$ip."','".$nombre."','".$idioma."','".$datos."','".$fecha."','".$hora."','".$query_string."','".$http_referer."','".$_SESSION['usuario_id']."','".$_SESSION['usuario_sucursal']."')");
	} else {
		if(!isset($_SESSION['usuario_id'])) {
			//echo("INSERT INTO contador (ID_CONTADOR,IP,NOMBRE,IDIOMA,DATOS,FECHA,HORA,QUERY_STRING,HTTP_REFERER,ID_USUARIO) values(null,'".$ip."','".$nombre."','".$idioma."','".$datos."','".$fecha."','".$hora."','".$query_string."','".$http_referer."',null)");
			$conexion->Query("INSERT INTO contador (ID_CONTADOR,IP,NOMBRE,IDIOMA,DATOS,FECHA,HORA,QUERY_STRING,HTTP_REFERER,ID_USUARIO,ID_SUCURSAL) values(null,'".$ip."','".$nombre."','".$idioma."','".$datos."','".$fecha."','".$hora."','".$query_string."','".$http_referer."',null,1)");
		}
	}
?>