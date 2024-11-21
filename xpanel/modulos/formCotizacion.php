<?php 
	require("../config.php");
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Formulario de COTIZACIÓN</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="copyright" content="XPERTICIA.com" />
		<meta name="author" content="Walter E. Irahola" />
		<meta name="email" content="info@xperticia.com" />
		<link rel="shortcut icon" href="../favicon.ico" />
		<meta name="Keywords" content="">
		<meta name="Description" content=""> 

		<!--[if lt IE 9]>
		<script src="../scripts/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="../images/xpanel_form.css" type="text/css" />

		<script src="../scripts/jquery/jquery.js"></script>
		<script src="../scripts/jquery.PrintArea.js" type="text/javascript"></script>
		<script type="text/javascript" src="aplicacion.js"></script>
	</head>

	<body>
		<div class="botones-opciones">
			<button type="button" class="btn btn-primary" id="printButton" data-contenido=".imprimible"><img src="../images/imprimir.gif" border="0" alt="imprimir" /> Imprimir</button>
			<button type="button" class="btn btn-primary" id="closeButton"><img src="../images/cerrar.gif" border="0" alt="imprimir" /> Cerrar</button>
		</div>

		<div class="imprimible">
			<header>
				<?php
				$sucursalLogo = "";
				$sucursalDireccion = "";
				$sucursalTelefono = "";
				$sucursalEmail = "";

				$registros = $conexion->Query("SELECT ID_COTIZACION,ID_SUCURSAL FROM cotizaciones WHERE (id_cotizacion = '".$_GET['id']."')");
				while(!$registros->EOF) {
					$datosSucursal = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$registros->fields["ID_SUCURSAL"]."')");
					if($datosSucursal){
						if(($datosSucursal['LOGO'] != null) and ($datosSucursal['LOGO'] != "") and file_exists("../../".$path_imagenes."sucursales/".$datosSucursal['LOGO'])) { 
							$sucursalLogo = "../../".$path_imagenes."sucursales/".$datosSucursal['LOGO'];
						} else {
							$sucursalLogo = "../../logo.png";
						}
						if(($datosSucursal['DOMICILIO'] != null) and ($datosSucursal['DOMICILIO'] != "")){
							$sucursalDireccion = $datosSucursal['DOMICILIO'];
						} else {
							$sucursalDireccion = $sitio_direccion;
						}
						if(($datosSucursal['EMAIL'] != null) and ($datosSucursal['EMAIL'] != "")){
							$sucursalEmail = $datosSucursal['EMAIL'];
						} else {
							$sucursalEmail = $sitio_email;
						}
					}

					?>
				<table border="0" width="100%" style="border: 0;">
				<tbody>
					<tr>
						<td width="150" style="text-align: center;"><img src="<?php echo($sucursalLogo); ?>" width="100" heigth="75" border="0" /></td>
						<td>
							<h2>COTIZACI&Oacute;N</h2>
						</td>
						<td width="150" style="text-align: center;"><h3 style="margin: 5px auto;">N&deg; <?php echo(sprintf("%04s",$registros->fields["ID_COTIZACION"])); ?></h3></td>
					</tr>
				</tbody>
				</table>
					<?php
					$registros->MoveNext();
				}
				?>		
			</header>
			<section>
				<?php
				$registros = $conexion->Query("SELECT cotizaciones.*,COD_USUARIO,NOMBRE_COMPLETO,usuarios.DOMICILIO,usuarios.TELEFONO,TELEFONO_MOVIL FROM cotizaciones LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_cliente WHERE (id_cotizacion = '".$_GET['id']."')");
				while(!$registros->EOF) {
					if($registros->fields["TELEFONO"] != "") {
						if($registros->fields["TELEFONO_MOVIL"] != "") {
							$vTelefonos = $registros->fields["TELEFONO"]." / ".$registros->fields["TELEFONO_MOVIL"];
						} else {
							$vTelefonos = $registros->fields["TELEFONO"];
						}
					} else {
						if($registros->fields["TELEFONO_MOVIL"] != "") {
							$vTelefonos = $registros->fields["TELEFONO_MOVIL"];
						} else {
							$vTelefonos = "";
						}
					}

					?>
				<table border="0" width="100%" class="tablaCampos">
				<tbody>
					<tr>
						<th width="100">Sucursal</th>
						<?php
						$datosSucursal = $conexion->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$registros->fields["ID_SUCURSAL"]."')");
						?>
						<td style="vertical-align: middle;"><?php echo($datosSucursal["NOMBRE"]); ?></td>
						<th width="100">Fecha</th>
						<td width="100" style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
					</tr>
					<tr>
						<th width="100">Cliente</th>
						<td><?php echo("[".sprintf("%04s",$registros->fields["COD_USUARIO"])."] ".stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
						<th width="100">Vencimiento</th>
						<td width="100" style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
					</tr>
					<tr>
						<th width="100">Direcci&oacute;n</th>
						<td><span><?php if($registros->fields["DOMICILIO"] != null) {  echo(stripslashes($registros->fields["DOMICILIO"])); } else { echo("&nbsp;"); } ?></span></td>
						<th width="100">Tipo de Cambio</th>
						<td style="text-align: right;"><?php echo(number_format($registros->fields["TIPO_CAMBIO"], 2, ',', '.')); ?></td>
					</tr>
					<tr>
						<th width="100">Tel&eacute;fono</th>
						<td><?php echo(stripslashes($vTelefonos)); ?></td>
						<th width="100"></th>
						<td width="100"></td>
					</tr>
				</tbody>
				</table>

				<table border="0" width="100%" class="tablaCampos" style="margin-top: 10px;">
				<thead>
					<tr>
						<th width="10"></th>
						<th width="50">Código</th>
						<th>Detalle</th>
						<th width="150">Foto</th>
						<th>Cantidad</th>
						<th>Precio</th>
						<th>Descuento</th>
						<th>Monto</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$detalles = $temp->Query("SELECT cotizacion_posee_productos.*,productos.CODIGO,productos.NOMBRE,FOTO FROM cotizacion_posee_productos LEFT JOIN productos ON productos.id_producto = cotizacion_posee_productos.id_producto WHERE (id_cotizacion = '".$registros->fields["ID_COTIZACION"]."') ORDER BY nombre");
						if($detalles) {
							$i = 1;
							while(!$detalles->EOF) {
								?>
					<tr>
						<td><?php echo($i); ?></td>
						<td><?php echo(stripslashes($detalles->fields["CODIGO"])); ?></td>
						<td><span><?php echo(stripslashes($detalles->fields["NOMBRE"])); ?></span></td>
						<?php 
							$path_url_foto = "";
							if($detalles->fields['FOTO'] != null) { 
								$path_url_foto = "../../".$path_imagenes."productos/".$detalles->fields['FOTO'];
								if(!file_exists($path_url_foto)) {
									$path_url_foto = "";
								}
							} 
						?>
						<td><span><?php if($path_url_foto != "" ) { ?><img src="<?php echo($path_url_foto); ?>" width="150" /><?php } else { echo($detalles->fields["FOTO"]); } ?></span></td>
						<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?></td>
						<td style="text-align: right;"><?php echo(number_format($detalles->fields["PRECIO"], 2, ',', '.')); ?></td>
						<td style="text-align: right;"><?php echo(number_format($detalles->fields["DESCUENTO"], 2, ',', '.')); ?></td>
						<td style="text-align: right;padding-right: 10px;"><?php echo(number_format($detalles->fields["TOTAL"], 2, ',', '.')); ?></td>
					</tr>
								<?php
								$i++;
								$detalles->MoveNext();
							}
						}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" style="text-align: right;font-weight: bold;">Total</td>
						<td style="text-align: right;padding-right: 10px;"><?php echo(number_format($registros->fields["TOTAL"], 2, ',', '.')); ?></td>
					</tr>
				</tfoot>
				</table>

				<table border="0" width="100%" class="tablaCampos" style="margin-top: 10px;">
				<tbody>
					<tr>
						<th width="100">Descripción</th>
						<td colspan="3" style="vertical-align: top;"><span><?php if($registros->fields["DESCRIPCION"] != null) {  echo(stripslashes($registros->fields["DESCRIPCION"])); } else { echo("&nbsp;"); } ?></span></span></td>
					</tr>
				</tbody>
				</table>

				<table border="0" width="100%" class="tablaCampos" style="margin-top: 10px;">
				<tbody>
					<tr>
						<th width="100">Usuario</th>
						<?php
						$datosUsuario = $conexion->GetRow("SELECT * FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_USUARIO"]."')");
						?>
						<td colspan="3"><?php echo(stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?></td>
					</tr>
				</tbody>
				</table>

				<table border="0" width="100%" style="margin-top: 50px;border: 0;">
				<tbody>
				<tr>
					<td width="10%"></td>
					<td width="35%" style="text-align: center;border-top: 1px dotted #333;">CLIENTE ENTREGUE CONFORME</td>
					<td width="10%"></td>
					<td width="35%" style="text-align: center;border-top: 1px dotted #333;">USUARIO RECIBI CONFORME</td>
					<td width="10%"></td>
				</tr>
				</tbody>
				</table>
					<?php
					$registros->MoveNext();
				}
				?>
			</section>
			<footer>
				<table border="0" width="100%" style="border: 0;">
					<tr>
						<td width="50%">&copy; <?php echo(date("Y"));?> <strong><?php echo($sitio_nombre); ?></a></strong>. Todos los derechos reservados.</td>
						<td width="50%">Dir.: <?php echo($sucursalDireccion); ?> - E-mail: <a href="mailto:<?php echo($sucursalEmail); ?>"><?php echo($sucursalEmail); ?></a></td>
					</tr>
				</table>
			</footer>
		</div>	
	</body>
</html>