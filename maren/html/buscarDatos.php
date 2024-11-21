<?php
header( 'Content-type: text/html; charset=iso-8859-1' );
require("../xpanel/config.php"); 
// recuperamos el criterio de la busqueda
if(isset($_GET["term"])) {
	$criterio = strtolower($_GET["term"]);
}
?>
[<?php
$contador = 0;

if(isset($_GET['t']) and ($_GET['t'] == "genero")) {
	if(isset($_GET['c']) and ($_GET['c'] != "")) {
		if(($_GET['c'] == "Últimos productos") or ($_GET['c'] == "Productos más leídas")) {
			$registros = $temp->Query("SELECT GENERO FROM productos WHERE (activo = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
		} else {
			$registros = $temp->Query("SELECT GENERO FROM productos WHERE (activo = 1) and (categoria = '".$_GET['c']."') and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
		}
	} else {
		$registros = $temp->Query("SELECT GENERO FROM productos WHERE (activo = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
	}
	if($registros) {
		$listaGeneros = array();
		while(!$registros->EOF) {
			if(strpos($registros->fields["GENERO"],",") > 0) {
				$arrayGeneros = explode(",",stripslashes($registros->fields["GENERO"]));
				foreach($arrayGeneros as $key=>$val) {
					if(!in_array(trim($val),$listaGeneros)) {
						array_push($listaGeneros,trim($val));
					}
				}
			} else {
				if(!in_array($registros->fields["GENERO"],$listaGeneros)) {
					array_push($listaGeneros,stripslashes($registros->fields["GENERO"]));
				}
			}
			$registros->MoveNext();
		}
		sort($listaGeneros);
		foreach($listaGeneros as $key=>$val) {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($val)."\" }";
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "click")) {
	if(isset($_GET['menu']) and isset($_GET['id'])) {
		switch(strtolower($_GET['menu'])) {
			case "enlaces":
				$conexion->Execute("UPDATE enlaces SET click = click + 1 WHERE (id_enlace = '".$_GET['id']."')");		
				break;		
			case "producto":
				$conexion->Execute("UPDATE productos SET click = IFNULL(click+1,1) WHERE (id_producto = '".$_GET['id']."')");		
				break;		
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "pais")) {
	$registros = $temp->Query("SELECT * FROM paises WHERE (pais LIKE '%$criterio%') ORDER BY pais");
	while(!$registros->EOF) {
		$descripcion = $registros->fields["PAIS"];
		$valor = $registros->fields["CODIGO_PAIS"];

		if ($contador++ > 0) print ", ";
		print "{ \"label\" : \"$descripcion\", \"value\" : { \"descripcion\" : \"$descripcion\", \"precio\" : \"$valor\" } }";
		$registros->MoveNext();
	}
	$registros->close();
}
?>]