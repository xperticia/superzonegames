<?php 
	require("../config.php");
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Formulario de SERVICIO TECNICO</title>
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

				$registros = $conexion->Query("SELECT COD_VENTA,ID_SUCURSAL FROM ventas WHERE (id_venta = '".$_GET['id']."') and (tipo_venta = 'servicios')");
				while(!$registros->EOF) {
					$datosSucursal = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$registros->fields["ID_SUCURSAL"]."')");
					if($datosSucursal){
						if(!empty($datosSucursal['LOGO']) and file_exists("../../".$path_imagenes."sucursales/".$datosSucursal['LOGO'])) { 
							$sucursalLogo = "../../".$path_imagenes."sucursales/".$datosSucursal['LOGO'];
						} else {
							$sucursalLogo = "../../logo.png";
						}
						$sucursalDireccion = !empty($datosSucursal['DOMICILIO']) ? $datosSucursal['DOMICILIO'] : $sitio_direccion;
						$sucursalEmail = !empty($datosSucursal['EMAIL']) ? $datosSucursal['EMAIL'] : $sitio_email;
					}

					?>
				<table border="0" width="100%" style="border: 0;">
				<tbody>
					<tr>
						<td width="150" style="text-align: center;"><img src="<?php echo($sucursalLogo); ?>" width="100" heigth="75" border="0" /></td>
						<td>
							<h2>SERVICIO T&Eacute;CNICO</h2>
						</td>
						<td width="150" style="text-align: center;"><h3 style="margin: 5px auto;">N&deg; <?php echo(sprintf("%04s",$registros->fields["COD_VENTA"])); ?></h3></td>
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
				$registros = $conexion->Query("SELECT ventas.*,COD_USUARIO,NOMBRE_COMPLETO,usuarios.DOMICILIO,usuarios.TELEFONO,TELEFONO_MOVIL 
					FROM ventas 
					LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente 
					WHERE 
						(id_venta = '".$_GET['id']."') 
						and (tipo_venta = 'servicios')
				");
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
						<td><?php echo($datosSucursal["NOMBRE"]); ?></td>
						<th width="100">Fecha</th>
						<td width="100" style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
					</tr>
					<tr>
						<th width="100">Cliente</th>
						<td><?php echo("[".sprintf("%04s",$registros->fields["COD_USUARIO"])."] ".stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
						<?php
							if($registros->fields["NRO_FACTURA"] == null) {
								?>
						<th width="100"></th>
						<th width="100"></th>
								<?php
							} else {
								?>
						<th width="100">N&deg; Factura</th>
						<td width="100" style="text-align: right;font-weight: bold;font-size: 1.2em;"><?php echo(sprintf("%04s",$registros->fields["NRO_FACTURA"])); ?></td>
								<?php
							}
						?>
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
						<th width="100">Estado</th>
						<td><?php echo($registros->fields["ESTADO"]); ?></td>
					</tr>
				</tbody>
				</table>

				<table border="0" width="100%" class="tablaCampos" style="margin-top: 10px;">
				<tbody>
					<tr>
						<th width="100">Consola</th>
						<td><?php echo($registros->fields["CONSOLA"]); ?></td>
						<th width="100">Modelo</th>
						<td><?php echo($registros->fields["MODELO"]); ?></td>
						<th width="100">Nro. de Serie</th>
						<td><?php echo($registros->fields["NRO_SERIE"]); ?></td>
					</tr>
					<tr>
						<th width="100">Problema</th>
						<td colspan="5"><span><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></span></td>
					</tr>
					<tr>
						<th width="100">Observaciones</th>
						<td colspan="2"><span><?php echo(stripslashes($registros->fields["OBSERVACIONES"])); ?></span></td>
						<th width="100">Diagnostico</th>
						<td colspan="2"><span><?php echo(stripslashes($registros->fields["DIAGNOSTICO"])); ?></span></td>
					</tr>
				</tbody>
				</table>

				<table border="0" width="100%" class="tablaCampos" style="margin-top: 10px;">
				<thead>
					<tr>
						<th>Importe</th>
						<th>Iva</th>
						<th>Subtotal</th>
						<th>Descuento</th>
						<th>Total</th>
						<th>Recibido</th>
						<th>Saldo</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["IMPORTE"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["IVA"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["SUBTOTAL"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["DESCUENTO"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["TOTAL"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?></td>
						<td style="text-align: right;width: 14.3%;"><?php echo(number_format($registros->fields["SALDO"], 2, ',', '.')); ?></td>
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
						<td><?php echo(stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?></td>
						<th width="100">Pagado</th>
						<td width="100" style="text-align: center;"><?php echo($estados[$registros->fields["PAGADO"]]); ?></td>
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