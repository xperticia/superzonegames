<?php
	require('../xpanel/config.php');

	if(isset($_GET['t'])) {
		if($_GET['t'] == "eventos") {
			$enlace = "../".$path_archivos."contenidos/".$_GET['ida'];
			header ("Content-Disposition: attachment; filename=".$_GET['ida']."\n\n");
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($enlace));
			readfile($enlace);		
		}		
	} else {
		$registros = $conexion->QUERY("SELECT * FROM archivos WHERE (id_archivo = '".$_GET['ida']."')");
		while(!$registros->EOF) {
			$enlace = "../".$path_archivos."contenidos/".$registros->fields['NOMBRE_ARCHIVO'];
			header ("Content-Disposition: attachment; filename=".$registros->fields['NOMBRE_ARCHIVO']."\n\n");
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($enlace));
			readfile($enlace);		
			$conexion->QUERY("UPDATE archivos SET descargas = descargas + 1 WHERE id_archivo = '".$_GET['ida']."'");
			$registros->MoveNext();
		}
		$registros->close();
	}
?>