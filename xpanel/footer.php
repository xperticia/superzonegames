				</div>
			</div>
		</div>

		<footer>			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span6">
						<p>&copy; 2007-<?php echo(date("Y"));?> <a href="http://www.xperticia.com" target="_blank"><strong>XPERTICIA.com</strong></a>. Todos los derechos reservados.</p>
					</div>
					<div class="span6" style="text-align: right;float:right;">
						<p>Compatible: 
							<a href="http://windows.microsoft.com/es-ES/internet-explorer/products/ie/home" title="Internet Explorer" target="_blank"><img src="../images/ie.png" alt="Internet Explorer" border="0" /></a>
							<a href="http://www.mozilla.org/es-AR/firefox/fx/" title="Mozilla Firefox" target="_blank"><img src="../images/firefox.png" alt="Mozilla Firefox" border="0" /></a>
							<a href="http://www.google.com/chrome?hl=es" title="Chrome" target="_blank"><img src="../images/chrome.png" alt="Chrome" border="0" /></a>
							<a href="http://www.opera.com/download/" title="Opera" target="_blank"><img src="../images/opera.png" alt="Opera" border="0" /></a>
							- Resoluci&oacute;n: 1024 x 768
						</p>
					</div>
				</div>
			</div>
		</footer>
		<form id="formDescargar" method="post" action="" style="display: none;"></form>
<?php
    $conexion->close();
    $temp->close();
    $temp2->close();
    $temp3->close();
    $temp4->close();
    $privilegios->close();
?>

	<script src="../scripts/jquery/jquery.js"></script>
	<script src="../scripts/jquery/jquery-ui.js"></script>
	<script src="../scripts/bootstrap/js/bootstrap.js"></script>

	<script type="text/javascript" src="../scripts/ckeditor/ckeditor.js"></script>
	<?php
		if (isset($_GET['menu']) and in_array($_GET['menu'], array("cotizaciones", "egresos", "inventarios", "traspasos", "ventas", "servicios", "pedidos"))) {
			?>
		<link rel="stylesheet" type="text/css" href="../scripts/subModal/subModal_xsite.css" />
		<script type="text/javascript" src="../scripts/subModal/common.js"></script>
		<script type="text/javascript" src="../scripts/subModal/subModal.js"></script>
		<script type="text/javascript" src="../scripts/sprintf.js"></script>
			<?php
		}

		if (isset($_GET['menu']) and in_array($_GET['menu'], array("contador", "reportes"))) {
			?>
		<script src="../scripts/highcharts/highcharts.js"></script>
		<script src="../scripts/highcharts/modules/exporting.js"></script>
		<script src="../scripts/highcharts/themes/xsite.js"></script>
		<script type="text/javascript" src="../scripts/jquery.PrintArea.js"></script>
			<?php
		}

		if (isset($_GET['menu']) and in_array($_GET['menu'], array("productos", "reportes")) and ($_GET['menu'] == "productos")) {
			?>
		<script type="text/javascript" src="../scripts/jquery.PrintArea.js"></script>
		<script type="text/javascript" src="../scripts/sprintf.js"></script>
		<script type="text/javascript" src="../scripts/jquery/plugins/meiomask.js"></script>
		<script type="text/javascript" src="../scripts/jquery/plugins/jquery.plugin.js"></script>
		<script type="text/javascript" src="../scripts/jquery/plugins/jquery.dateentry.js"></script>
			<?php
		}
		
		if (isset($_GET['menu']) and ($_GET['menu'] == "config")) {
			switch ($sitio_apimapa) {
				case "leaflet":
					?>
		<link rel="stylesheet" href="../scripts/leaflet/leaflet.css" type="text/css" />
		<link rel="stylesheet" href="../scripts/leaflet/plugins/leaflet-control-geocoder/Control.Geocoder.css" type="text/css" />
		<script type="text/javascript" src="../scripts/leaflet/leaflet.js"></script>
		<script type="text/javascript" src="../scripts/leaflet/plugins/leaflet-control-geocoder/Control.Geocoder.js"></script>
					<?php
					break;
				case "googlemaps":
					?>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo($sitio_keymapa); ?>&callback=initializarMapaUbicacionGoogle" async defer></script>
					<?php
					break;
			}
		}

        if (isset($_GET['menu']) && in_array($_GET['menu'], array("clientes", "proveedores", "sucursales")) && (isset($_GET['accion']) && in_array($_GET['accion'], array("nuevo", "editar")) )) {
            switch ($sitio_apimapa) {
                case "leaflet":
                    ?>
	<link rel="stylesheet" href="../scripts/leaflet/leaflet.css" type="text/css" />
	<link rel="stylesheet" href="../scripts/leaflet/plugins/leaflet-control-geocoder/Control.Geocoder.css" type="text/css" />
	<script type="text/javascript" src="../scripts/leaflet/leaflet.js"></script>
	<script type="text/javascript" src="../scripts/leaflet/plugins/leaflet-control-geocoder/Control.Geocoder.js"></script>
					<?php
                    break;
                case "googlemaps":
                    ?>
	<script	src="https://maps.googleapis.com/maps/api/js?key=<?php echo($sitio_keymapa); ?>&callback=initializarMapaUbicacionGoogle" async defer></script>
					<?php
                    break;
            }
        }
    ?>
	<script type="text/javascript" src="aplicacion.js?v=21122024"></script>
	<script type="module" src="app.js"></script>
