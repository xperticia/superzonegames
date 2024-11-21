<?php 
	//Autor: Walter E. Irahola
	//E-mail: wirahola@hotmail.com
	//Proyecto: Script para previsualizar los boletines desde XPANEL como de los CONTACTOS

	require("xpanel/config.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="ISO-8859-1">
	<title>Boletines <?php echo($sitio_nombre); ?></title>
	<!--[if lt IE 9]>
	<script src="xpanel/scripts/html5.js"></script>
	<![endif]-->

	<script src="xpanel/scripts/jquery/jquery-1.7.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#hidepage").remove();
		});
	</script>
</head>

<body>
	<div id="hidepage">
		<div id="fade" style="display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;background: #000;z-index:1001;opacity:.75;-moz-opacity: 0.75;filter: alpha(opacity=75);"></div>
		<div id="light" style="position:absolute;width:250px;height:60px;left:50%;padding: 10px;margin-left:-100px;top:50%;margin-top:-100px;text-align: center;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;background: #ffffcc;border: 5px solid #900;-webkit-border-radius: 6px 6px;-moz-border-radius: 6px 6px;border-radius: 6px 6px;-webkit-box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);-moz-box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);box-shadow: 0 3px 7px rgba(255, 255, 255, 0.6);color: #900;z-index:1002;"><img src="images/preloader.gif" align="bottom" border="0" alt="" /><br />Cargando pagina...<br />Por favor espere.<br /></div>
	</div>
<?php 
	//	verifico si vienen con email y numero de boletin
	if(isset($_GET['idc']) and isset($_GET['idb'])) {

		//	actualizo la cantidad de click que tenga el boletin
		if($_GET['idc'] != "{EMAIL}") {
			$conexion->Query("UPDATE boletin SET click = click + 1 WHERE (id_boletin = '".$_GET['idb']."')");
		}
		//	registro el acceso del contacto
		$datos = $conexion->Query("SELECT ID_CONTACTO FROM contactos WHERE (email = '".trim($_GET['idc'])."')");
		if($datos->RecordCount() > 0) {
			while(!$datos->EOF) {			
				$conexion->Execute("INSERT contactos_acceden (ID_CONTACTO,ID_BOLETIN,FECHA,HORA) VALUES('".$datos->fields["ID_CONTACTO"]."','".$_GET['idb']."','".date("Y/m/d")."','".date("H:i:s")."');");
				$datos->MoveNext();
			}
			$datos->Close();
		}
		
		//	obtengo el boletin
		echo(crearBoletin2($_GET['idb']));
	} else {
		echo("<p>Parametros incorrectos</p>");
	}
?>
</body>
</html>
