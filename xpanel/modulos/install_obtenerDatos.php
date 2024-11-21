<?php
header( 'Content-type: text/html; charset=iso-8859-1' );

// recuperamos el criterio de la busqueda
if(isset($_GET['term'])) {
	$criterio = strtolower($_GET["term"]);
}
//if (!$criterio) return;
?>
[<?php
$contador = 0;
if(isset($_GET['t']) and ($_GET['t'] == "estilos")) {
	if(strpos($_GET['valor'],"/") > 0) {
		$res = explode("/",$_GET['valor']);
		$carpeta2 = $res[0]."/";
	} else {
		$carpeta2 = "/";
	}
	if(!isset($path_plantillas)) {
		$path_plantillas = "templates/";
	}
	//revisa la carpeta plantillas, busca todos los archivos HTML y lee su titulo
	$carpeta = "../../".$path_plantillas.$carpeta2;
	$d = dir($carpeta);
	while(false !== ($entrada = $d->read())) {
		//Los diseños para boletines estan en carpetas individuales
		//verifica si es una carpeta y busca archivos HTML en su interior
		if(is_dir($carpeta . $entrada)and($entrada != ".") and ($entrada != "..")) {
			$d2 = dir($carpeta . $entrada);
			while(false !== ($entrada2 = $d2->read())) {
				//verifico si es archivo y su extension
				if(is_file($carpeta . $entrada . "/" . $entrada2)) {
					$res = explode(".", $entrada2);
					$extension = $res[count($res) - 1];
					if($extension == "css") {
						$titulo = $entrada2;

						if ($contador++ > 0) print ", ";
						print "{ \"label\" : \"".trim($titulo)."\", \"value\" : \"".$entrada . "/" . $entrada2."\" }";
					}
				}
				//verifico si es una carpeta y busco archivos
				if(is_dir($carpeta . $entrada. "/" . $entrada2) and ($entrada2 != ".") and ($entrada2 != "..")) {
					$d3 = dir($carpeta . $entrada. "/" . $entrada2);
					while(false !== ($entrada3 = $d3->read())) {
						$res = explode(".", $entrada3);
						$extension = $res[count($res) - 1];
						if(($extension == "css")) {
							$titulo = $entrada3;

							if ($contador++ > 0) print ", ";
							print "{ \"label\" : \"".trim($titulo)."\", \"value\" : \"".$entrada . "/" . $entrada2. "/" .$entrada3."\" }";
						}
					}
					$d3->close();
				}
			}
			$d2->close();
		}
		//verifico si es archivo y su extension
		if(is_file($carpeta . $entrada)) {
			$res = explode(".", $entrada);
			$extension = $res[count($res) - 1];
			if(($extension == "css")) {
				$titulo = $entrada;

				if ($contador++ > 0) print ", ";
				print "{ \"label\" : \"".trim($titulo)."\", \"value\" : \"".$entrada."\" }";
			}
		}
	}
	$d->close();
}
?>]
