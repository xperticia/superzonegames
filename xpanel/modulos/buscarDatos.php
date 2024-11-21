<?php
// El objetivo de este demo no es realizar una busqueda con php, sino mostrar lo simple
// que es programar una rutina de autocompletado con jQuery UI, por esta razon no vamos
// a realizar nada importante en este archivo.

//header('Content-Type: text/html; charset=iso-8859-1');
header('Content-Type: text/html; charset=utf-8');
require("../config.php");

// recuperamos el criterio de la busqueda
if(isset($_GET["term"])) {
	$criterio = strtolower($_GET["term"]);
}
//if (!$criterio) return;
?>
[<?php
$contador = 0;

/* PRODUCTOS */
if(isset($_GET['t']) and ($_GET['t'] == "verificarCodigo")) {
	$query = "SELECT COUNT(codigo) as CANTIDAD,NOMBRE, CATEGORIA FROM productos WHERE (id_sucursal = '".$_GET['s']."') and (codigo = '".trim($_GET['n'])."-".trim($_GET['l'])."') GROUP BY codigo";
	$registros = $temp->Query($query);
	if($registros) {
		if($registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";

				print "{ \"cantidad\" : \"".$registros->fields["CANTIDAD"]."\", \"nombre\" : \"".stripslashes($registros->fields["NOMBRE"])."\", \"categoria\" : \"".stripslashes($registros->fields["CATEGORIA"])."\" }";	
				$registros->MoveNext();
			}
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "verificarNroSerie")) {
	$query = "SELECT productos.ID_PRODUCTO,CODIGO,productos.NOMBRE,sucursales.NOMBRE as SUCURSAL FROM productos LEFT JOIN sucursales ON sucursales.id_sucursal = productos.id_sucursal ";
	if(isset($_GET['a']) and ($_GET['a'] != "")) {
		$query .= "WHERE (nro_serie = '".$_GET['c']."') and (nro_serie <> '".$_GET['a']."') and (nro_serie > 0) ";
	} else {
		$query .= "WHERE (nro_serie = '".$_GET['c']."') and (nro_serie > 0) ";
	}
	if(isset($_GET['s']) and ($_GET['s'] != "")) {
		$query .= "and (productos.id_sucursal = '".$_GET['s']."') ";
	}

	$registros = $temp->Query($query);
	if($registros) {
		if($registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";

				print "{ \"id\" : \"".$registros->fields["ID_PRODUCTO"]."\", \"codigo\" : \"".$registros->fields["CODIGO"]."\", \"nombre\" : \"".stripslashes($registros->fields["NOMBRE"])."\", \"sucursal\" : \"".$registros->fields["SUCURSAL"]."\" }";	
				$registros->MoveNext();
			}
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "tipo_producto")) {
	$query = "SELECT upper(tipo_producto) as TIPO_PRODUCTO FROM productos WHERE ((tipo_producto LIKE '%$criterio%') and (tipo_producto is not null) and (tipo_producto <> '')) ";	
	if(isset($_GET['ids']) and ($_GET['ids'] != "")){
		$query .= " and (id_sucursal = '".$_GET['ids']."') ";
	}
	$query .= "GROUP BY tipo_producto ORDER BY tipo_producto LIMIT 0,10";	
	$registros = $temp->Query($query);
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["TIPO_PRODUCTO"]."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "obtenerCodigo")) {
	$registros = $temp->GetRow("SELECT CODIGO,CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED) as NUMERO, SUBSTRING(CODIGO,(LOCATE('-',CODIGO)+1),LENGTH(CODIGO)) as LETRA, IF(CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED) > 0, (max(CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED)) + 1), 1) as NUEVONUMERO, concat(IF(CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED) > 0, (max(CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED)) + 1), 1),SUBSTRING(CODIGO,LOCATE('-',CODIGO),LENGTH(CODIGO))) as NUEVOCODIGO FROM productos WHERE (id_sucursal = '".$_GET['s']."') and ((categoria = '".$_GET['c']."') and (categoria is not null) and (categoria <> '')) GROUP BY CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED) ORDER BY CAST(SUBSTRING(CODIGO,1,LOCATE('-',CODIGO)) as SIGNED) DESC LIMIT 1");
	if($registros) {
		print "{ \"codigo\" : \"".$registros["NUEVOCODIGO"]."\", \"letra\" : \"".$registros["LETRA"]."\", \"numero\" : \"".sprintf("%04s",$registros["NUEVONUMERO"])."\" }";
	} else {
		print "{ \"codigo\" : \"\", \"letra\" : \"".substr($_GET['c'],0,4)."\", \"numero\" : \"".sprintf("%04s",1)."\" }";
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "genero")) {
	$query = "SELECT GENERO FROM productos WHERE ((genero LIKE '%$criterio%') and (genero is not null) and (genero <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")) {
		$query .= "and (id_sucursal = '".$_GET['ids']."') ";
	}
	if(isset($_GET['c']) and ($_GET['c'] != "")) {
		$query .= "and (categoria = '".$_GET['c']."') ";
	}
	$query .= "GROUP BY genero ORDER BY genero LIMIT 0,10";
	$registros = $temp->Query($query);

	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($registros->fields["GENERO"])."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "listaGeneros")) {
	$listaGeneros = array();

	$query = "SELECT GENERO FROM productos WHERE (activo = 1) and ((genero is not null) and (genero <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")) {
		$query .= "and (id_sucursal = '".$_GET['ids']."') ";
	}
	if(isset($_GET['c']) and ($_GET['c'] != "")) {
		$query .= "and (categoria = '".$_GET['c']."') ";
	}
	$query .= "GROUP BY genero ORDER BY genero";
	$registros = $temp->Query($query);

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
		print "{ \"label\" : \"".$val."\" }";
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "marca")) {
	$query = "SELECT MARCA FROM productos WHERE ((marca LIKE '%$criterio%') and (marca is not null) and (marca <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")) {
		$query .= "and (id_sucursal = '".$_GET['ids']."') ";
	}
	$query .= "GROUP BY marca ORDER BY marca LIMIT 0,10";
	$registros = $temp->Query($query);
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["MARCA"]."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "idioma")) {
	$query = "SELECT IDIOMA FROM productos WHERE ((idioma LIKE '%$criterio%') and (idioma is not null) and (idioma <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")) {
		$query .= "and (id_sucursal = '".$_GET['ids']."') ";
	}
	$query .= "GROUP BY idioma ORDER BY idioma LIMIT 0,10";
	$registros = $temp->Query($query);
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["IDIOMA"]."\" }";
			$registros->MoveNext();
		}
	}
}

/* SERVICIOS TECNICOS */
if(isset($_GET['t']) and ($_GET['t'] == "consola")) {
	$query = "SELECT CONSOLA FROM ventas WHERE (tipo_venta = 'servicios') and ((consola LIKE '%$criterio%') and (consola is not null) and (consola <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")){
		$query .= " and (id_sucursal = '".$_GET['ids']."') ";
	}
	$query .= "GROUP BY consola ORDER BY consola LIMIT 0,10";
	$registros = $temp->Query($query);
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["CONSOLA"]."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "modelo")) {
	$query = "SELECT MODELO FROM ventas WHERE (tipo_venta = 'servicios') and ((modelo LIKE '%$criterio%') and (modelo is not null) and (modelo <> '')) ";
	if(isset($_GET['ids']) and ($_GET['ids'] != "")){
		$query .= " and (id_sucursal = '".$_GET['ids']."') ";
	}
	if(isset($_GET['c']) and ($_GET['c'] != "")) {
		$query .= " and (id_sucursal = '".$_GET['c']."') ";
	}
	$query .= "GROUP BY modelo ORDER BY modelo LIMIT 0,10";
	$registros = $temp->Query($query);
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["MODELO"]."\" }";
			$registros->MoveNext();
		}
	}
}


if(isset($_GET['t']) and ($_GET['t'] == "obtenerNroFactura")) {
	$registros = $temp->GetRow("SELECT IF(NRO_FACTURA > 0, (max(NRO_FACTURA) + 1), 1) as NRO_FACTURA FROM ventas WHERE (nro_factura is not null) ORDER BY nro_factura DESC");
	if($registros) {
		print "{ \"factura\" : \"".sprintf("%04s",$registros["NRO_FACTURA"])."\" }";
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "tipo_egreso")) {
	$tipos = array(
		"Sueldo",
		"Alquiler",
		"Compras",
		"Adelanto",
		"Servicios"
	);
	//	obtengo todos los tipos de respuestas tipo_egreso
	$registros = $temp->Query("SELECT TIPO_EGRESO FROM egresos WHERE ((tipo_egreso LIKE '%$criterio%') and (tipo_egreso is not null) and (tipo_egreso <> '')) GROUP BY tipo_egreso ORDER BY tipo_egreso");
	if($registros) {
		while(!$registros->EOF) {
			if(!in_array($registros->fields["TIPO_EGRESO"],$tipos)) {
				array_push($tipos,$registros->fields["TIPO_EGRESO"]);
			}
			$registros->MoveNext();
		}		
	}
	//	ordeno los datos
	sort($tipos);
	$i = 0;
	foreach($tipos as $key=>$val) {
		if($criterio == "") {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"$val\" }";		
			$i++;
		} else {
			if (strpos(strtolower($val), $criterio) !== false) {
				if ($contador++ > 0) print ", ";
				print "{ \"label\" : \"$val\" }";		
				$i++;
			}
		}
		if($i >= 10) {
			break;
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "obtenerStock")) {
	$registros = $temp->Query("SELECT * FROM sucursal_posee_productos WHERE (id_producto = '".$_GET['p']."') and (id_sucursal = '".$_GET['s']."')");
	if($registros) {
		if($registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";
				print "{ \"stock\" : \"".$registros->fields["STOCK"]."\" }";
				$registros->MoveNext();
			}
		} else {
			print "{ \"stock\" : \"0\" }";
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "verificarProducto")) {
	$registros = $temp->Query("SELECT 
			productos.ID_PRODUCTO,
			CODIGO,
			CATEGORIA,
			NOMBRE,
			GENERO,
			IF(STOCK >= 0,STOCK,0) as STOCK 
			FROM productos 
			WHERE (id_sucursal = '".$_GET['s']."')  and (activo = 1) and (eliminado = 0) and (codigo = '".$_GET['c']."')");
			
	if($registros) {		
		while(!$registros->EOF) {
			if ($contador++ > 0) print ", ";
			print "{ \"id\" : \"".$registros->fields["ID_PRODUCTO"]."\", \"nombre\" : \"".str_replace("\"","'",stripslashes($registros->fields["NOMBRE"]))."\", \"codigo\" : \"".stripslashes($registros->fields["CODIGO"])."\", \"categoria\" : \"".stripslashes($registros->fields["CATEGORIA"])."\", \"genero\" : \"".stripslashes($registros->fields["GENERO"])."\", \"stock\" : \"".$registros->fields["STOCK"]."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "productos")) {
	if(isset($_GET["term"])) {
		$query = "SELECT 
				productos.ID_PRODUCTO,
				CODIGO,
				CATEGORIA,
				NOMBRE,
				GENERO,
				IF(STOCK >= 0,STOCK,0) as STOCK 
			FROM productos 
			WHERE (upper(nombre) LIKE upper('%$criterio%')) and (id_sucursal = '".$_GET['s']."') and (activo = 1) and (eliminado = 0) GROUP BY nombre ORDER BY nombre LIMIT 0,10";
			
		$registros = $temp->Query($query);
		if($registros) {		
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";
				print "{ \"label\" : \"".str_replace("\"","'",stripslashes($registros->fields["NOMBRE"]))."\", \"value\" : \"".$registros->fields["ID_PRODUCTO"]."\", \"codigo\" : \"".stripslashes($registros->fields["CODIGO"])."\", \"categoria\" : \"".stripslashes($registros->fields["CATEGORIA"])."\", \"genero\" : \"".stripslashes($registros->fields["GENERO"])."\", \"stock\" : \"".$registros->fields["STOCK"]."\" }";
				$registros->MoveNext();
			}
		}
	} else {
		$query = "SELECT 
				productos.ID_PRODUCTO,
				CODIGO,
				CATEGORIA,
				NOMBRE,
				GENERO,
				PRECIO_COSTO,
				PRECIO_VENTA,
				IF(STOCK >= 0,STOCK,0) as STOCK,
				FOTO 
			FROM productos 
			WHERE 
				(id_sucursal = '{$_GET['s']}') 
				and (activo = 1) 
				and (eliminado = 0) 
		";
		if(isset($_GET['id']) and ($_GET['id'] != "")) {
			$query .= " and (productos.ID_PRODUCTO = '".$_GET['id']."') ";
		}
		if(isset($_GET['q']) and ($_GET['q'] != "")) {
			$query .= " and ( 
				(codigo LIKE upper('%".sprintf("%04s",utf8_decode($_GET['q']))."%')) or 
				(upper(nombre) LIKE upper('%".utf8_decode($_GET['q'])."%')) or 
				(upper(codigo_barra) LIKE upper('%".utf8_decode($_GET['q'])."%')) or 
				(upper(nro_serie) LIKE upper('%".utf8_decode($_GET['q'])."%')) 
			)";
		}
		if(isset($_GET['c']) and ($_GET['c'] != "")) {
			$query .= " and (categoria = '".utf8_decode($_GET['c'])."') ";
		}
		if(isset($_GET['g']) and ($_GET['g'] != "")) {
			$query .= " and (genero LIKE '%".utf8_decode($_GET['g'])."%') ";
		}
		if(isset($_GET['a']) and ($_GET['a'] == "exportarStock")) {
			$query .= " ORDER BY codigo";
		} else {
			$query .= " ORDER BY nombre";
		}

		$registros = $temp->Query($query);
		if($registros->RecordCount() > 1000) {
			$registros = $temp->Query($query." LIMIT 0,500");
		}
		if($registros) {		
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";
				print "{ \"id\" : \"".$registros->fields["ID_PRODUCTO"]."\", \"codigo\" : \"".stripslashes($registros->fields["CODIGO"])."\", \"nombre\" : \"".str_replace("\"","'",stripslashes($registros->fields["NOMBRE"]))."\", \"categoria\" : \"".stripslashes($registros->fields["CATEGORIA"])."\", \"genero\" : \"".stripslashes($registros->fields["GENERO"])."\", \"precio_costo\" : \"".number_format($registros->fields["PRECIO_COSTO"], 2, ',', '.')."\", \"precio_venta\" : \"".number_format($registros->fields["PRECIO_VENTA"], 2, ',', '.')."\", \"stock\" : \"".$registros->fields["STOCK"]."\", \"foto\" : \"".$registros->fields["FOTO"]."\" }";
				$registros->MoveNext();
			}
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "verificarCliente")) {
	$query = "SELECT ID_USUARIO,COD_USUARIO,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,TELEFONO_MOVIL,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS,CREDITOS,GRATIS,COMENTARIOS FROM usuarios WHERE (cod_usuario = '".$_GET['c']."') and (id_usuario > 0) ";
	if(isset($_GET['a']) and ($_GET['a'] != "")) {
		$query .= " and (id_usuario <> '".$_GET['a']."') ";
	}
	if(isset($_GET['s']) and ($_GET['s'] != "")) {
		$query .= " and (id_sucursal = ".$_GET['s'].")";
	}
	$registros = $temp->Query($query);
	if($registros) {
		if($registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";

				print "{ \"id\" : \"".$registros->fields["ID_USUARIO"]."\", \"codigo\" : \"".$registros->fields["COD_USUARIO"]."\", \"nombre\" : \"".stripslashes($registros->fields["NOMBRE_COMPLETO"])."\", \"domicilio\" : \"".stripslashes($registros->fields["DOMICILIO"])."\", \"telefonos\" : \"".stripslashes($registros->fields["TELEFONOS"])."\", \"creditos\" : \"".$registros->fields["CREDITOS"]."\", \"gratis\" : \"".$registros->fields["GRATIS"]."\", \"comentarios\" : \"".preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", stripslashes($registros->fields["COMENTARIOS"]))."\" }";	
				$registros->MoveNext();
			}
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "verificarUsuario")) {

	$usuario_set = !empty($_GET['u']) ? ' and (upper(usuario) = upper("'.$_GET['u'].'")) ' : '';
	$id_set = !empty($_GET['id']) ? ' and (id_usuario <> "'.$_GET['id'].'") ' : '';

	$query = "SELECT 
			ID_USUARIO,
			NOMBRE_COMPLETO, 
			TIPO_USUARIO,
			IFNULL( (select NOMBRE from sucursales where (id_sucursal = usuarios.id_sucursal)),'') SUCURSAL 
		FROM usuarios 
		WHERE 
			((usuario is not null) and (usuario <> '')) 
			{$usuario_set}
			{$id_set}
	";
	$registros = $temp->Query($query);
	if($registros) {
		if($registros->RecordCount() > 0) {
			while(!$registros->EOF) {
				if ($contador++ > 0) print ", ";

				print "{ \"id\" : \"".$registros->fields["ID_USUARIO"]."\", \"nombre\" : \"".stripslashes($registros->fields["NOMBRE_COMPLETO"])."\", \"usuario\" : \"".$registros->fields["USUARIO"]."\", \"tipo\" : \"".$registros->fields["TIPO_USUARIO"]."\", \"sucursal\" : \"".$registros->fields["SUCURSAL"]."\" }";	
				$registros->MoveNext();
			}
		}
	}
}


if(isset($_GET['t']) and ($_GET['t'] == "cliente")) {
	if(isset($_GET['term'])) {
		$query = "SELECT ID_USUARIO,COD_USUARIO,concat(TIPO_DOC,' ',NRO_DOC) as DOCUMENTO,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,TELEFONO_MOVIL,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS,CREDITOS,GRATIS,CATEGORIA_USUARIO,COMENTARIOS FROM usuarios WHERE (tipo_usuario = 'Cliente') and (activo = 1) and ( (upper(nombre_completo) LIKE upper('%$criterio%')) and (nombre_completo is not null) and (nombre_completo <> '') ) ";
		if(isset($_GET['s']) and ($_GET['s'] != "")) {
			$query .= " and (id_sucursal = ".$_GET['s'].")";
		}
		$query .= "GROUP BY nombre_completo ORDER BY nombre_completo LIMIT 0,100";
		$registros = $temp->Query($query);
	} else {
		$query = "SELECT 
				ID_USUARIO,
				COD_USUARIO,
				concat(TIPO_DOC,' ',NRO_DOC) as DOCUMENTO,
				NOMBRE_COMPLETO,
				DOMICILIO,
				TELEFONO,
				TELEFONO_MOVIL,
				concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS,
				CREDITOS,
				GRATIS,
				CATEGORIA_USUARIO,
				COMENTARIOS 
			FROM usuarios 
			WHERE 
				(tipo_usuario = 'Cliente') 
				and (activo = 1) 
		";
		if(isset($_GET['s']) and ($_GET['s'] != "")) {
			$query .= " and (id_sucursal = ".$_GET['s'].")";
		}
		if(isset($_GET['cc']) and ($_GET['cc'] != "")) {
			$codigo = sprintf("%04s",trim($_GET['cc']));
			$query .= " and (upper(concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO)) = '".$codigo."') ";
			//$query .= " and (cod_usuario LIKE '%".utf8_decode($_GET['cc'])."%')";
			//$query .= " and (cod_usuario LIKE '%".sprintf("%06s",utf8_decode($_GET['cc']))."%')";
		}
		if(isset($_GET['d']) and ($_GET['d'] != "")) {
			$query .= " and (upper(concat(tipo_doc,' ',nro_doc)) LIKE upper('%".utf8_decode($_GET['d'])."%')) ";
		}
		if(isset($_GET['n']) and ($_GET['n'] != "")) {
			$query .= " and (upper(nombre_completo) LIKE upper('%".utf8_decode($_GET['n'])."%')) ";
		}
		if(isset($_GET['tc']) and ($_GET['tc'] != "")) {
			$query .= " and (upper(concat(telefono,' / ',telefono_movil)) LIKE upper('%".utf8_decode($_GET['tc'])."%')) ";
		}
        if (isset($_GET['q']) and ($_GET['q'] != "")) {
			$codigo = sprintf("%04s",trim($_GET['q']));
			$query .= "
				and (
					(upper(concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO)) = '".$codigo."') OR
					(upper(concat(TIPO_DOC,' ',NRO_DOC)) LIKE upper('%".utf8_decode($_GET['q'])."%')) OR
					(upper(NOMBRE_COMPLETO) LIKE upper('%".utf8_decode($_GET['q'])."%')) OR
					(upper(concat(TELEFONO,' / ',TELEFONO_MOVIL)) LIKE upper('%".utf8_decode($_GET['q'])."%'))
				)
			";
        }
		$query .= " 
			GROUP BY nombre_completo 
			ORDER BY nombre_completo
		";
		
		$registros = $temp->Query($query);
		if($registros->RecordCount() > 1000) {
			$registros = $temp->Query($query." LIMIT 0,500");
		}
	}
	if($registros) {		
		while(!$registros->EOF) {
			if ($contador++ > 0) print ", ";

			print "{ \"label\" : \"".str_replace("\"","'",stripslashes($registros->fields["NOMBRE_COMPLETO"]))."\", \"value\" : \"".$registros->fields["ID_USUARIO"]."\", \"codigo\" : \"".sprintf("%04s",$registros->fields["COD_USUARIO"])."\", \"documento\" : \"".$registros->fields["DOCUMENTO"]."\", \"domicilio\" : \"".str_replace("\"","'",stripslashes($registros->fields["DOMICILIO"]))."\", \"telefonos\" : \"".stripslashes($registros->fields["TELEFONOS"])."\", \"creditos\" : \"".$registros->fields["CREDITOS"]."\", \"gratis\" : \"".$registros->fields["GRATIS"]."\", \"categoria_usuario\" : \"".$registros->fields["CATEGORIA_USUARIO"]."\", \"comentarios\" : \"".str_replace("\"","'",preg_replace("/\r\n+|\r+|\n+|\t+/i", " ", stripslashes($registros->fields["COMENTARIOS"])))."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "categoria")) {
	switch($_GET['m']) {
		case "contenidos":
			$registros = $temp->Query("SELECT CATEGORIA FROM menu_modulos WHERE ((categoria LIKE '%$criterio%') and (categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria LIMIT 0,10");
			break;
		case "productos":
		case "ventas":
		case "traspasos":
			if(isset($_GET['ids'])) {
				if($_GET['ids'] != "") {
					$registros = $temp->Query("SELECT CATEGORIA FROM productos WHERE ((categoria is not null) and (categoria <> '')) and (id_sucursal = '".$_GET['ids']."') GROUP BY categoria ORDER BY categoria");
				} else {
					$registros = $temp->Query("SELECT CATEGORIA FROM productos WHERE ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
				}
			} else {
				$registros = $temp->Query("SELECT CATEGORIA FROM productos WHERE ((categoria LIKE '%$criterio%') and (categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria LIMIT 0,10");
			}
			break;
		case "fotogaleria":
			$registros = $temp->Query("SELECT CATEGORIA FROM fotogaleria WHERE ((categoria LIKE '%$criterio%') and (categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria LIMIT 0,10");
			break;
		default:
			$registros = $temp->Query("SELECT CATEGORIA FROM contenidos WHERE ((categoria LIKE '%$criterio%') and (categoria is not null) and (categoria <> '')) and (tipo_contenido = '".$_GET['m']."') GROUP BY categoria ORDER BY categoria LIMIT 0,10");
			break;
	}
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($registros->fields["CATEGORIA"])."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "subcategoria")) {
	$registros = $temp->Query("SELECT SUBCATEGORIA FROM encuestas_preguntas WHERE ((subcategoria LIKE '%$criterio%') and (subcategoria is not null) and (subcategoria <> '')) and (id_tipoencuesta = '".$_GET['e']."') GROUP BY subcategoria ORDER BY subcategoria LIMIT 0,10");
	if($registros) {		
		while(!$registros->EOF) {

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".$registros->fields["SUBCATEGORIA"]."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "tipo_respuesta")) {
	$tipos = array(
		"Texto",
		"Memo",
		"Archivo",
		"Fecha",
		"País",
		"Malo|Regular|Bueno|Excelente",
		"Si|No"
	);
	//	obtengo todos los tipos de respuestas registradas
	$registros = $temp->Query("SELECT TIPO_RESPUESTA FROM encuestas_preguntas WHERE ((tipo_respuesta LIKE '%$criterio%') and (tipo_respuesta is not null) and (tipo_respuesta <> '')) and (id_tipoencuesta = '".$_GET['e']."') GROUP BY tipo_respuesta ORDER BY tipo_respuesta");
	if($registros) {
		while(!$registros->EOF) {
			if(!in_array($registros->fields["TIPO_RESPUESTA"],$tipos)) {
				array_push($tipos,$registros->fields["TIPO_RESPUESTA"]);
			}
			$registros->MoveNext();
		}		
	}
	//	ordeno los datos
	sort($tipos);
	$i = 0;
	foreach($tipos as $key=>$val) {
		if($criterio == "") {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"$val\" }";		
			$i++;
		} else {
			if (strpos(strtolower($val), $criterio) !== false) {
				if ($contador++ > 0) print ", ";
				print "{ \"label\" : \"$val\" }";		
				$i++;
			}
		}
		if($i >= 10) {
			break;
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "categoria_usuario")) {
	$registros = $temp->Query("SELECT upper(categoria_usuario) as CATEGORIA_USUARIO FROM usuarios 
		WHERE ( (upper(categoria_usuario) LIKE upper('%$criterio%')) and (categoria_usuario is not null) and (categoria_usuario <> '') ) 
		GROUP BY upper(categoria_usuario) 
		ORDER BY upper(categoria_usuario) LIMIT 0,10");
	if($registros) {		
		while(!$registros->EOF) {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($registros->fields["CATEGORIA_USUARIO"])."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "profesion")) {
	switch($_GET['m']) {
		case "usuarios":
		case "clientes":
			$registros = $temp->Query("SELECT PROFESION FROM usuarios WHERE ((profesion LIKE '%$criterio%') and (profesion is not null) and (profesion <> '')) GROUP BY profesion ORDER BY profesion LIMIT 0,10");
			break;
		default:
			$registros = $temp->Query("SELECT PROFESION FROM contactos WHERE ((profesion LIKE '%$criterio%') and (profesion is not null) and (profesion <> '')) GROUP BY profesion ORDER BY profesion LIMIT 0,10");
			break;	
	}
	if($registros) {		
		while(!$registros->EOF) {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($registros->fields["PROFESION"])."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "nivel_academico")) {
	switch($_GET['m']) {
		case "usuarios":
		case "clientes":
			$registros = $temp->Query("SELECT NIVEL_ACADEMICO FROM usuarios WHERE ((nivel_academico LIKE '%$criterio%') and (nivel_academico is not null) and (nivel_academico <> '')) GROUP BY nivel_academico ORDER BY nivel_academico LIMIT 0,10");
			break;
		default:
			$registros = $temp->Query("SELECT NIVEL_ACADEMICO FROM contactos WHERE ((nivel_academico LIKE '%$criterio%') and (nivel_academico is not null) and (nivel_academico <> '')) GROUP BY nivel_academico ORDER BY nivel_academico LIMIT 0,10");
			break;	
	}
	if($registros) {		
		while(!$registros->EOF) {
			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"".stripslashes($registros->fields["NIVEL_ACADEMICO"])."\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "ciudad")) {
	switch($_GET['m']) {
		case "usuarios":
		case "clientes":
			$registros = $temp->Query("SELECT upper(ciudad) CIUDAD FROM usuarios WHERE ((ciudad LIKE '%$criterio%') and (ciudad is not null) and (ciudad <> '')) GROUP BY upper(ciudad) ORDER BY upper(ciudad) LIMIT 0,10");
			break;
		default:
			$registros = $temp->Query("SELECT upper(ciudad) CIUDAD FROM contactos WHERE ((ciudad LIKE '%$criterio%') and (ciudad is not null) and (categoria <> '')) GROUP BY upper(ciudad) ORDER BY upper(ciudad) LIMIT 0,10");
			break;	
	}
	if($registros) {		
		while(!$registros->EOF) {
			$descripcion = $registros->fields["CIUDAD"];

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"$descripcion\" }";
			$registros->MoveNext();
		}
	}
}

if(isset($_GET['t']) and ($_GET['t'] == "grupo")) {
	$registros = $temp->Query("SELECT GRUPO FROM contactos WHERE ((grupo LIKE '%$criterio%') and (grupo is not null) and (grupo <> '')) GROUP BY grupo ORDER BY grupo LIMIT 0,10");
	if($registros) {		
		while(!$registros->EOF) {
			$descripcion = $registros->fields["GRUPO"];

			if ($contador++ > 0) print ", ";
			print "{ \"label\" : \"$descripcion\" }";
			$registros->MoveNext();
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
}

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