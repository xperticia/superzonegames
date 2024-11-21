<?php
	//Autor: Walter E. Irahola
	//E-mail: wirahola@hotmail.com
	//Proyecto: exportar datos desde xpanel

	switch($_POST['tipo']) {
		case "doc":
			header('Content-type: application/vnd.ms-word; charset=iso-8859-1');
			header("Content-Disposition: attachment; filename=".ucfirst($_POST['menu'])."_al_".date("d-m-Y").".doc");
			header("Pragma: no-cache");
			header("Expires: 0");
			print($_POST['contenido']);
			break;		
		case "xls":
			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename=".ucfirst($_POST['menu'])."_al_".date("d-m-Y").".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			print(utf8_decode($_POST['contenido']));
			break;		
	}
?>