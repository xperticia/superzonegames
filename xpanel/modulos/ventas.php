<?php
require("../config.php");
require("../verifica.php");
$nivel_acceso = 5; // Nivel de acceso para esta página.
if ($nivel_acceso < $_SESSION['usuario_nivel']) {
	header("Location: ./?error_login=5");
	exit;
}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo (armarHead()); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="copyright" content="XPERTICIA.com" />
		<meta name="author" content="Walter E. Irahola" />
		<meta name="email" content="info@xperticia.com" />
		<link rel="shortcut icon" href="../favicon.ico" />

		<link rel="stylesheet" href="../images/xpanel.css" type="text/css" />

		<style type="text/css">
			#listaProductos th[data-sort] {
				cursor: pointer;
			}
		</style>
	</head>

	<body>
		<?php
		include("../head.php");		

		//	-----	VENTAS
		if (isset($_GET['menu']) && ($_GET['menu'] == "ventas")) {
			$url_modulo = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'];
			/*
			if (!isset($_GET['anio'])) { $_GET['anio'] = ""; }
			if (isset($_GET['anio']) && ($_GET['anio'] != "")) { $url_modulo .= "&anio=" . $_GET['anio']; }
			*/

			$_GET['fd'] = !empty($_GET['fd']) ? $_GET['fd'] : date('Y-m-d');
			$_GET['fh'] = !empty($_GET['fh']) ? $_GET['fh'] : date('Y-m-d');
			$periodo_set = " AND ( (ventas.fecha_alta >= '{$_GET['fd']}') AND (ventas.fecha_alta <= '{$_GET['fh']}') ) ";
			$url_modulo .= "&fd=" . $_GET['fd'];
			$url_modulo .= "&fh=" . $_GET['fh'];
	
			// Listado
			if (!isset($_GET['accion'])) {

				$query = "SELECT
						COD_VENTA,
						ventas.FECHA_ALTA,
						NRO_FACTURA,
						concat('[',REPEAT('0',4-length(COD_USUARIO)),COD_USUARIO,'] ',
						NOMBRE_COMPLETO) CLIENTE,
						ESTADO,
						(TOTAL * ".convertirTipoMoneda().") TOTAL, 
						(RECIBIDO * ".convertirTipoMoneda().") RECIBIDO, 
						(SALDO * ".convertirTipoMoneda().") SALDO, 
						PAGADO,
						ID_VENTA
					FROM ventas
					LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
					WHERE
						(tipo_venta = '{$_GET['menu']}') 
						and (ventas.id_sucursal = '{$_SESSION['usuario_sucursal']}') 
						and (eliminado = 0)
						{$periodo_set}
				";
				if (isset($_GET['anio']) && ($_GET['anio'] != "")) {
					$query .= " AND (year(ventas.fecha_alta) = '{$_GET['anio']}') ";
				}
				if (isset($_GET['texto'])) {
					$codigo = sprintf("%04s", trim($_GET['texto']));

					$query .= " AND (
							(upper(concat(repeat('0',4-length(COD_VENTA)),COD_VENTA)) = '{$codigo}') or
							(upper(nro_factura) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO)) = '{$codigo}') or
							(upper(nombre_completo) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(estado) LIKE upper('%" . $_GET['texto'] . "%'))
						) ";
				}
				$query .= "ORDER BY ventas.fecha_alta DESC,ventas.hora_alta DESC,cod_venta DESC";

				showTable2(
					$conexion,
					$query,
					array(
						"COD_VENTA" => "N&deg;",
						"FECHA_ALTA" => "Fecha",
						"NRO_FACTURA" => "N&deg; Factura",
						"CLIENTE" => "Cliente",
						"ESTADO" => "Estado",
						"TOTAL" => "Total",
						"RECIBIDO" => "Recibido",
						"SALDO" => "Saldo",
						"PAGADO" => "Pagado"
					),
					array(0 => "ID_VENTA"),
					$_SESSION['usuario_rxp'],
					true,
					true,
					$datos_acceso,
					null,
					null,
					array("imprimirVenta" => "Imprimir Venta")
				);
				
			}

			// Nuevo
			if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				if (isset($_GET['registro'])) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $_GET['registro'][0] . "')");
				}
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="tipo_venta" value="<?php echo ($_GET['menu']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Venta</a></li>
							<li><a href="#tab02" data-toggle="tab">Adjuntar archivos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<input type="hidden" name="id_cliente" id="id_cliente" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["ID_USUARIO"]); } ?>" />
												<input type="text" name="codigo" id="codigo" value="<?php if (isset($datosUsuario)) { echo (sprintf("%04s", $datosUsuario["COD_USUARIO"])); } ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
												<input type="text" id="nombre_completo" name="nombre_completo" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["NOMBRE_COMPLETO"]); } ?>" size="23" maxlength="255" title="Cliente" required style="width: 61%;" readonly="readonly" />
												<button type="button" id="btBuscarClienteVentana" class="btn" title="Buscar Cliente (Alt+B)" data-toggle="modal" data-target="#modalBuscarCliente" data-backdrop="static" data-keyboard="true"><i class="icon-search"></i> buscar cliente</button>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["DOMICILIO"]); } ?>" size="23" maxlength="255" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Tel&eacute;fonos</label>
													<div class="controls">
														<input type="text" id="telefonos" name="telefonos" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["TELEFONOS"]); } ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Categoria</label>
													<div class="controls">
														<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["CATEGORIA_USUARIO"]); } ?>" size="23" maxlength="50" title="Categoria de Usuarios" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span8">
												<div class="control-group">
													<label class="control-label">Cr&eacute;ditos</label>
													<div class="controls">
														<input type="text" id="creditos" name="creditos" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["CREDITOS"]); } ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos actuales" readonly="readonly" />
														&nbsp;
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="creditos_venta" data-limite="<?php if (isset($datosUsuario)) { if ($datosUsuario["CREDITOS"] > 0) { echo ("-"); } echo ($datosUsuario["CREDITOS"]); } else { echo ("-100"); } ?>" data-origen="creditos" data-destino="creditos_final" class="btn" <?php if (!isset($datosUsuario)) { ?>disabled="disabled" <?php } ?>><i class="icon-minus"></i></button>
															<input type="text" id="creditos_venta" name="creditos_venta" value="0" size="50" maxlength="20" class="input-mini" style="text-align: right;width: 25px;" title="Cr&eacute;ditos" />
															<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="creditos_venta" data-limite="100" data-origen="creditos" data-destino="creditos_final" class="btn" <?php if (!isset($datosUsuario)) { ?>disabled="disabled" <?php } ?>><i class="icon-plus"></i></button>
														</div>
														&nbsp;
														<i class="icon-arrow-right"></i>
														&nbsp;
														<input type="text" id="creditos_final" name="creditos_final" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos finales" readonly="readonly" />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Gratis</label>
													<div class="controls">
														<input type="text" id="gratis" name="gratis" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["GRATIS"]); } ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis actuales" readonly="readonly" />
														&nbsp;
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="gratis_venta" data-limite="<?php if (isset($datosUsuario)) { if ($datosUsuario["GRATIS"] > 0) { echo ("-"); } echo ($datosUsuario["GRATIS"]); } else { echo ("-100"); } ?>" data-origen="gratis" data-destino="gratis_final" class="btn" <?php if (!isset($datosUsuario)) { ?>disabled="disabled" <?php } ?>><i class="icon-minus"></i></button>
															<input type="text" id="gratis_venta" name="gratis_venta" value="0" size="50" maxlength="20" class="input-mini" style="text-align: right;width: 25px;" title="Gratis" />
															<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="gratis_venta" data-limite="100" data-origen="gratis" data-destino="gratis_final" class="btn" <?php if (!isset($datosUsuario)) { ?>disabled="disabled" <?php } ?>><i class="icon-plus"></i></button>
														</div>
														&nbsp;
														<i class="icon-arrow-right"></i>
														&nbsp;
														<input type="text" id="gratis_final" name="gratis_final" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis finales" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span4" id="divComentarios" style="border: 1px solid #dddddd;border-radius: 6px;">
												<?php
												if (isset($datosUsuario)) {
													if (($datosUsuario["CREDITOS"] == 0) or ($datosUsuario["GRATIS"] == 0)) {
														echo ($datosUsuario["COMENTARIOS"]);
													}
												}
												?>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">N&deg; de Venta</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SELECT IF(COD_VENTA > 0, (max(COD_VENTA) + 1), 1) as COD_VENTA FROM ventas WHERE (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (tipo_venta = '" . $_GET['menu'] . "') ORDER BY cod_venta DESC");
												?>
												<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $datos["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Venta" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo (date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo (date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">N&deg; de Factura</label>
											<div class="controls">
												<button type="button" id="btnAsignarFactura" class="btn" style="width: 105px;"><i class="icon-check"></i> Asignar</button>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipo de Cambio</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo ($_SESSION['usuario_tipocambio']); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Estado</label>
											<div class="controls">
												<select id="estado" name="estado" title="Estado" style="width: auto;" required>
													<option value="">Estado</option>
													<option value="Entregado" selected>Entregado</option>
													<option value="Pendiente">Pendiente</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button type="button" id="btNuevoDetalle" class="btn btn-primary" <?php if (!isset($datosUsuario)) { ?>disabled="disabled" <?php } ?> data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente=""><i class="icon-plus icon-white"></i> Nuevo detalle</button>
										<button type="button" id="btEliminarDetalle" class="btn btn-danger" disabled="disabled"><i class="icon-trash icon-white"></i> Eliminar detalle</button>
									</div>
									<div class="btn-group">
										<button type="button" id="btnCalcularSubtotal" class="btn btn-primary"><i class="icon-check icon-white"></i> Calcular Montos</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed" id="listaDetalles">
									<thead>
										<tr>
											<th width="20"></th>
											<th width="80">C&oacute;digo</th>
											<th>Detalle</th>
											<th width="150">Cantidad</th>
											<th width="150">Precio</th>
											<th width="150">Descuento</th>
											<th width="150">Monto</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6" style="text-align: right;font-weight: bold;">Importe</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="importe" name="importe" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;font-weight: bold;">
												<div class="btn-toolbar" style="display: inline;">
													<div class="btn-group">
														<button type="button" id="btIva" class="btn" data-porcentaje="0">IVA 0%</button>
														<button type="button" id="btIva" class="btn" data-porcentaje="13">IVA 13%</button>
														<button type="button" id="btIva" class="btn" data-porcentaje="16">IVA 16%</button>
													</div>
												</div>
											</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="iva" name="iva" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;font-weight: bold;">Subtotal</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="subtotal" name="subtotal" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;font-weight: bold;">Descuento</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="descuento" name="descuento" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="4"></td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="total_me" name="total_me" pattern="^(d|-)?(d|.)*,?d*$" value="" size="50" maxlength="255" title="Total M/E" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<span class="add-on"><?php echo((convertirTipoMoneda() == 1 ? 'Bs' : $_SESSION['usuario_tipomoneda'])); ?></span>
												</div>
											</td>
											<td style="text-align: right;font-weight: bold;">Total</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="total" name="total" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="6" style="text-align: right;font-weight: bold;">Recibido</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="recibido" name="recibido" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="6" id="msjSaldo" style="text-align: right;font-weight: bold;">Saldo</td>
											<td style="text-align: right;">
												<div class="input-prepend input-append">
													<input type="text" id="saldo" name="saldo" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required />
													<span class="add-on">Bs</span>
												</div>
											</td>
										</tr>
									</tfoot>
								</table>
								<hr />
								<div class="row-fluid">
									<div class="span5">
										<div class="control-group">
											<label class="control-label">Descripci&oacute;n</label>
											<div class="controls">
												<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%;"></textarea>
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label class="control-label">N&deg; de Cuota</label>
											<div class="controls">
												<input type="number" id="nro_cuota" name="nro_cuota" value="0" min="0" size="23" maxlength="50" title="N&deg; de Cuota" class="input-small" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Vencimiento</label>
											<div class="controls">
												<input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo (date("Y-m-d")); ?>" max="<?php echo (date("Y-m-d")); ?>" class="input-medium" title="Fecha de Vencimiento" size="12" maxlength="10" />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<select id="usuario" name="usuario" title="Administrador" required style="width: auto;">
													<option value=""></option>
													<?php
													for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
														$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
														if ($usuarios and ($usuarios->RecordCount() > 0)) {
													?>
															<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																<?php
																while (!$usuarios->EOF) {
																?>
																	<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																<?php
																	$usuarios->MoveNext();
																}
																?>
															</optgroup>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Pagado</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="1" checked="checked" /> <?php echo ($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="0" /> <?php echo ($estados[0]); ?>
												</label>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Impreso</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="impreso" name="impreso" value="1" /> <?php echo ($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="impreso" name="impreso" value="0" checked="checked" /> <?php echo ($estados[0]); ?>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div id="tab02" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otro archivo" />

										<input type="hidden" id="indicadorArchivo" value="1" />
										<div id="adjuntos_archivo1" class="cloneArchivo">
											<label class="control-label">[ARCHIVO01]</label>
											<div class="controls">
												<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" />
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="archivo_adjunto" name="archivo_adjunto[]" title="Archivo Adjunto" />
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" title="Guardar datos (Alt+G)" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" title="Cancelar (Alt+C)" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>

				<div id="modalDetalle" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form style="margin: 0;">
						<input type="hidden" name="origen" value="" />

						<div class="modal-header">
							<a href="#" class="close" data-dismiss="modal">&times;</a>
							<h3>Modal Heading</h3>
							<div class="row-fluid">
								<div class="span5" style="text-align: left;">
									<strong>Ingrese el texto a buscar: </strong><br />
									<input type="text" id="q" name="q" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="buscar por código, nombre o codigo de barra" style="width: 99%;" autocomplete="off" />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Categoría: </strong><br />
									<select id="listaCategoria" name="listaCategoria" title="Categoría" style="width: 99%;">
										<option value="">Categoría</option>
										<?php
										$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
										while (!$categorias->EOF) {
											?>
											<option value="<?php echo ($categorias->fields["CATEGORIA"]); ?>"><?php echo ($categorias->fields["CATEGORIA"]); ?></option>
											<?php
											$categorias->MoveNext();
										}
										?>
									</select>
								</div>
								<div class="span3" style="text-align: left;">
									<strong>G&eacute;nero: </strong><br />
									<?php
									//	obtengo todos los GENEROS individualmente del menu de contenidos
									$listaGeneros = array();
									$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
									while (!$registros->EOF) {
										if (strpos($registros->fields["GENERO"], ",") > 0) {
											$arrayGeneros = explode(",", stripslashes($registros->fields["GENERO"]));
											foreach ($arrayGeneros as $key => $val) {
												if (!in_array(trim($val), $listaGeneros)) {
													array_push($listaGeneros, trim($val));
												}
											}
										} else {
											if (!in_array($registros->fields["GENERO"], $listaGeneros)) {
												array_push($listaGeneros, stripslashes($registros->fields["GENERO"]));
											}
										}
										$registros->MoveNext();
									}
									sort($listaGeneros);
									?>
									<select id="listaGenero" name="listaGenero" title="G&eacute;nero" style="width: 99%;">
										<option value="">Género</option>
										<?php
										foreach ($listaGeneros as $key => $val) {
											?>
											<option value="<?php echo ($val); ?>"><?php echo ($val); ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="span1" style="text-align: center;">
									<br />
									<button type="button" id="btnModalBuscar" class="btn btn-primary" data-target="#modalDetalle" title="Buscar"><i class="icon-search icon-white"></i> </button>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<table class="table table-bordered table-condensed" id="listaProductos">
								<thead>
									<tr>
										<th></th>
										<th data-sort="int">C&oacute;digo</th>
										<th data-sort="string">Nombre</th>
										<th data-sort="string">Categoria</th>
										<th data-sort="string">G&eacute;nero</th>
										<th data-sort="float" width="70">Precio</th>
										<th data-sort="int" width="50">Stock</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<div class="row-fluid">
								<div class="span3" style="text-align: left;">
									<strong>Cantidad: </strong>
									<input type="text" id="detalleCantidad" name="detalleCantidad" title="Cantidad" pattern="[-+]?[0-9]*" class="input-small" placeholder="0" style="text-align: right;" required />
									<input type="hidden" id="detallePrecio" name="detallePrecio" />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Descuento: </strong>
									<input type="text" id="detalleDescuento" name="detalleDescuento" title="Descuento" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" placeholder="0,00" style="text-align: right;" required />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Total: </strong>
									<input type="text" id="detalleTotal" name="destalleTotal" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" required readonly="readonly" />
								</div>
								<div class="span3" style="text-align: right;">
									<input type="button" class="btn btn-primary" data-target="#modalDetalle" id="btnModalGuardar" value="Aceptar" />
									<input type="reset" class="btn" id="btnModalCancelar" data-dismiss="modal" value="Cancelar" />
								</div>
							</div>
						</div>

					</form>
				</div>

				<div id="modalBuscarCliente" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form style="margin: 0;">
						<input type="hidden" name="origen" value="" />

						<div class="modal-header">
							<a href="#" class="close" data-dismiss="modal">&times;</a>
							<h3>Modal Heading</h3>
							<div class="row-fluid">
								<div class="span10" style="text-align: left;">
									<input type="text" id="queryCliente" name="queryCliente" size="50" maxlength="255" title="buscar registros..." placeholder="buscar por código, documento, nombre o teléfono..." style="width: 99%;" />
								</div>
								<div class="span2" style="text-align: center;">
									<button type="button" id="btnModalBuscarCliente" class="btn btn-primary" data-target="#modalBuscarCliente" title="Buscar">
										<i class="icon-search icon-white"></i> buscar
									</button>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<table class="table table-bordered table-condensed" id="listaClientes">
								<thead>
									<tr>
										<th></th>
										<th data-sort="int">C&oacute;digo</th>
										<th data-sort="string">Documento</th>
										<th data-sort="string">Nombres y Apellidos</th>
										<th data-sort="string">Tel&eacute;fonos</th>
										<th data-sort="int" width="70">Cr&eacute;ditos</th>
										<th data-sort="int" width="70">Gratis</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-primary" data-target="#modalBuscarCliente" id="btnModalGuardarCliente" value="Aceptar" />
							<input type="reset" class="btn" id="btnModalCancelar" data-dismiss="modal" value="Cancelar" />
						</div>
						
					</form>
				</div>
				<?php
			}

			// Insertar
			if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if ($_POST['tipo_cambio'] == "") {
					$_POST['tipo_cambio'] = 0;
				}
				$vTipoCambio = str_replace(".", "", $_POST['tipo_cambio']);
				$vTipoCambio = str_replace(",", ".", $vTipoCambio);

				$_POST['nro_cuota'] = $_POST['nro_cuota'] == '' ? '0' : $_POST['nro_cuota'];

				$_POST['importe'] = $_POST['importe'] == '' ? '0' : $_POST['importe'];
				$vImporte = str_replace(".", "", $_POST['importe']);
				$vImporte = str_replace(",", ".", $vImporte);

				$_POST['iva'] = $_POST['iva'] == '' ? '0' : $_POST['iva'];
				$vIva = str_replace(".", "", $_POST['iva']);
				$vIva = str_replace(",", ".", $vIva);

				$_POST['subtotal'] = $_POST['subtotal'] == '' ? '0' : $_POST['subtotal'];
				$vSubtotal = str_replace(".", "", $_POST['subtotal']);
				$vSubtotal = str_replace(",", ".", $vSubtotal);

				$_POST['descuento'] = $_POST['descuento'] == '' ? '0' : $_POST['descuento'];
				$vDescuento = str_replace(".", "", $_POST['descuento']);
				$vDescuento = str_replace(",", ".", $vDescuento);

				$_POST['total'] = $_POST['total'] == '' ? '0' : $_POST['total'];
				$vTotal = str_replace(".", "", $_POST['total']);
				$vTotal = str_replace(",", ".", $vTotal);

				$_POST['recibido'] = $_POST['recibido'] == '' ? '0' : $_POST['recibido'];
				$vRecibido = str_replace(".", "", $_POST['recibido']);
				$vRecibido = str_replace(",", ".", $vRecibido);

				$_POST['saldo'] = $_POST['saldo'] == '' ? '0' : $_POST['saldo'];
				$vSaldo = str_replace(".", "", $_POST['saldo']);
				$vSaldo = str_replace(",", ".", $vSaldo);

				if (isset($_POST['id_venta'])) {
					$actualizacion_set = !empty($_POST['id_actualizacion']) ? "id_actualizacion = '".$_POST['id_actualizacion']."', " : '';

					//	Update
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE ventas
						SET
							id_sucursal ='" . $_POST['sucursal'] . "',
							id_cliente = '" . $_POST['id_cliente'] . "',
							id_usuario = '" . $_POST['usuario'] . "',
							fecha_alta = '" . $_POST['fecha_alta'] . "',
							hora_alta = '" . $_POST['hora_alta'] . ":00',
							tipo_cambio = '" . number_format($vTipoCambio, 4, '.', '') . "',
							creditos_venta = '" . $_POST['creditos_venta'] . "',
							gratis_venta = '" . $_POST['gratis_venta'] . "',
							importe = '" . number_format($vImporte, 2, '.', '') . "',
							iva = '" . number_format($vIva, 2, '.', '') . "',
							subtotal = '" . number_format($vSubtotal, 2, '.', '') . "',
							descuento = '" . number_format($vDescuento, 2, '.', '') . "',
							total = '" . number_format($vTotal, 2, '.', '') . "',
							recibido = '" . number_format($vRecibido, 2, '.', '') . "',
							saldo = '" . number_format($vSaldo, 2, '.', '') . "',
							pagado = '" . $_POST['pagado'] . "',
							impreso = '" . $_POST['impreso'] . "',
							descripcion = '" . addslashes($_POST['descripcion']) . "',
							estado = '" . $_POST['estado'] . "',
							{$actualizacion_set}
							nro_cuota = '" . $_POST['nro_cuota'] . "'
						WHERE 
							(id_venta = '" . $_POST['id_venta'] . "')
					");

					if (isset($_POST['nro_factura'])) {
						$conexion->Query("UPDATE ventas
								SET
									nro_factura = '" . addslashes($_POST['nro_factura']) . "'
								WHERE (id_venta = '" . $_POST['id_venta'] . "')");
					}
					$id_venta = $_POST['id_venta'];

					//	devuelvo los STOCK a su PRODUCTO antes de eliminarlos
					$registros = $conexion->Query("SELECT * FROM venta_posee_productos WHERE (id_venta = '" . $_POST['id_venta'] . "')");
					while (!$registros->EOF) {
						//	aumento el STOCK
						$temp->Query("UPDATE productos
								SET
									stock = stock + (" . $registros->fields["CANTIDAD"] . ")
								WHERE (id_sucursal = '" . $_POST['sucursal'] . "') and (id_producto = '" . $registros->fields["ID_PRODUCTO"] . "')");
						$registros->MoveNext();
					}
					$conexion->Execute("DELETE FROM venta_posee_productos WHERE (id_venta = '" . $_POST['id_venta'] . "')");

					if (isset($_POST['id_detalle'])) {
						foreach ($_POST['id_detalle'] as $key => $value) {
							//echo($key."=>".$value."<br />");
							$vdetallePrecioCosto = str_replace(".", "", $_POST['detallePrecioCosto'][$key]);
							$vdetallePrecioCosto = str_replace(",", ".", $vdetallePrecioCosto);
							$vdetallePrecioVenta = str_replace(".", "", $_POST['detallePrecioVenta'][$key]);
							$vdetallePrecioVenta = str_replace(",", ".", $vdetallePrecioVenta);
							$vdetalleDescuento = str_replace(".", "", $_POST['detalleDescuento'][$key]);
							$vdetalleDescuento = str_replace(",", ".", $vdetalleDescuento);
							$vdetalleTotal = str_replace(".", "", $_POST['detalleTotal'][$key]);
							$vdetalleTotal = str_replace(",", ".", $vdetalleTotal);
							$conexion->Query("INSERT INTO venta_posee_productos
									(
										ID_VENTA,
										ID_PRODUCTO,
										CODIGO,
										DETALLE,
										PRECIO_COSTO,
										PRECIO_VENTA,
										CANTIDAD,
										DESCUENTO,
										TOTAL
									) VALUES(
										'" . $id_venta . "',
										'" . $_POST['id_detalle'][$key] . "',
										'" . $_POST['detalleCodigo'][$key] . "',
										'" . $_POST['detalle'][$key] . "',
										'" . number_format($vdetallePrecioCosto, 2, '.', '') . "',
										'" . number_format($vdetallePrecioVenta, 2, '.', '') . "',
										'" . number_format($_POST['detalleCantidad'][$key], 2, '.', '') . "',
										'" . number_format($vdetalleDescuento, 2, '.', '') . "',
										'" . number_format($vdetalleTotal, 2, '.', '') . "'
									)");
							//	disminuyo el STOCK
							$temp->Query("UPDATE productos
									SET
										stock = stock - (" . $_POST['detalleCantidad'][$key] . ")
									WHERE (id_sucursal = '" . $_POST['sucursal'] . "') and (id_producto = '" . $_POST['id_detalle'][$key] . "')
							");
						}
					}
				} else {
					//	verifico que no exista el campo COD_VENTA
					$query = "SELECT * FROM ventas WHERE (id_sucursal = '{$_POST['sucursal']}') and (cod_venta = '{$_POST['cod_venta']}') and (tipo_venta = '{$_POST['tipo_venta']}')";
					$registros = $conexion->Query($query);
					if ($registros && ($registros->RecordCount() == 0)) {

						$actualizacion_set = !empty($_POST['id_actualizacion']) ? $_POST['id_actualizacion'] : 'null';

						//	Insert
						echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
						$conexion->Query("INSERT INTO ventas
								(
									ID_VENTA,
									ID_SUCURSAL,
									ID_CLIENTE,
									ID_USUARIO,
									COD_VENTA,
									FECHA_ALTA,
									HORA_ALTA,
									TIPO_VENTA,
									TIPO_CAMBIO,
									CREDITOS_VENTA,
									GRATIS_VENTA,
									IMPORTE,
									IVA,
									SUBTOTAL,
									DESCUENTO,
									TOTAL,
									RECIBIDO,
									SALDO,
									PAGADO,
									IMPRESO,
									DESCRIPCION,
									ESTADO,
									ID_ACTUALIZACION,
									NRO_CUOTA
								) VALUES(
									null,
									'" . $_POST['sucursal'] . "',
									'" . $_POST['id_cliente'] . "',
									'" . $_POST['usuario'] . "',
									'" . $_POST['cod_venta'] . "',
									'" . $_POST['fecha_alta'] . "',
									'" . $_POST['hora_alta'] . ":00',
									'" . $_POST['tipo_venta'] . "',
									'" . number_format($vTipoCambio, 4, '.', '') . "',
									'" . $_POST['creditos_venta'] . "',
									'" . $_POST['gratis_venta'] . "',
									'" . number_format($vImporte, 2, '.', '') . "',
									'" . number_format($vIva, 2, '.', '') . "',
									'" . number_format($vSubtotal, 2, '.', '') . "',
									'" . number_format($vDescuento, 2, '.', '') . "',
									'" . number_format($vTotal, 2, '.', '') . "',
									'" . number_format($vRecibido, 2, '.', '') . "',
									'" . number_format($vSaldo, 2, '.', '') . "',
									'" . $_POST['pagado'] . "','" . $_POST['impreso'] . "',
									'" . addslashes($_POST['descripcion']) . "',
									'" . $_POST['estado'] . "',
									{$actualizacion_set},
									'" . $_POST['nro_cuota'] . "'
								)");
						$id_venta = $conexion->Insert_ID();

						if ($id_venta > 0) {

							if (isset($_POST['nro_factura'])) {
								$conexion->Query("UPDATE ventas
									SET
										nro_factura = '" . addslashes($_POST['nro_factura']) . "'
									WHERE (id_venta = '" . $id_venta . "')");
							}

							//	Actualizo los CREDITOS y GRATIS del CLIENTE
							//echo("<br />CREDITOS: ".$_POST['creditos']." / CREDITOS_VENTA: ".$_POST['creditos_venta']." / CREDITOS_FINAL: ".$_POST['creditos_final']);
							//echo("<br />GRATIS: ".$_POST['gratis']." / GRATIS_VENTA: ".$_POST['gratis_venta']." / GRATIS_FINAL: ".$_POST['gratis_final']);

							$query = "UPDATE usuarios
								SET
									creditos = creditos + ({$_POST['creditos_venta']}),
									gratis = gratis + ({$_POST['gratis_venta']})
								WHERE 
									(id_usuario = '{$_POST['id_cliente']}')
							";
							$conexion->Query($query);

							// verifico los detalles
							if (isset($_POST['id_detalle'])) {
								foreach ($_POST['id_detalle'] as $key => $value) {
									//echo($key."=>".$value."<br />");
									$vdetallePrecioCosto = str_replace(".", "", $_POST['detallePrecioCosto'][$key]);
									$vdetallePrecioCosto = str_replace(",", ".", $vdetallePrecioCosto);
									$vdetallePrecioVenta = str_replace(".", "", $_POST['detallePrecioVenta'][$key]);
									$vdetallePrecioVenta = str_replace(",", ".", $vdetallePrecioVenta);
									$vdetalleDescuento = str_replace(".", "", $_POST['detalleDescuento'][$key]);
									$vdetalleDescuento = str_replace(",", ".", $vdetalleDescuento);
									$vdetalleTotal = str_replace(".", "", $_POST['detalleTotal'][$key]);
									$vdetalleTotal = str_replace(",", ".", $vdetalleTotal);

									$conexion->Query("INSERT INTO venta_posee_productos
											(
												ID_VENTA,
												ID_PRODUCTO,
												CODIGO,
												DETALLE,
												PRECIO_COSTO,
												PRECIO_VENTA,
												CANTIDAD,
												DESCUENTO,
												TOTAL
											) VALUES(
												'" . $id_venta . "',
												'" . $_POST['id_detalle'][$key] . "',
												'" . $_POST['detalleCodigo'][$key] . "',
												'" . $_POST['detalle'][$key] . "',
												'" . number_format($vdetallePrecioCosto, 2, '.', '') . "',
												'" . number_format($vdetallePrecioVenta, 2, '.', '') . "',
												'" . number_format($_POST['detalleCantidad'][$key], 2, '.', '') . "',
												'" . number_format($vdetalleDescuento, 2, '.', '') . "',
												'" . number_format($vdetalleTotal, 2, '.', '') . "'
											)");
									//	disminuyo el STOCK
									$query = "UPDATE productos
										SET
											stock = stock - ({$_POST['detalleCantidad'][$key]})
										WHERE 
											(id_sucursal = '{$_POST['sucursal']}') 
											and (id_producto = '{$_POST['id_detalle'][$key]}')
									";
									$temp->Query($query);
								}
							}

						}

					}
				}

				if ($id_venta > 0) {
					if ($_POST['fecha_vencimiento'] != '') {
						$conexion->Execute("UPDATE ventas SET fecha_vencimiento = '" . $_POST['fecha_vencimiento'] . "' WHERE (id_venta = '" . $id_venta . "')");
					} else {
						$conexion->Execute("UPDATE ventas SET fecha_vencimiento = null WHERE (id_venta = '" . $id_venta . "')");
					}
				}

				//verifico los archivos adjuntos Cuota #nCuota #nsubidos
				if (($id_venta > 0) and isset($_FILES["archivo_adjunto"])) {
					$total = count($_FILES["archivo_adjunto"]["name"]);
					for ($i = 0; $i < $total; $i++) {
						//	obtengo el nombre del archivo y su extension
						$datos = $temp->GetRow("SELECT * FROM archivos ORDER BY id_archivo DESC");
						if ($datos) {
							$id = $datos["ID_ARCHIVO"] + 1;
						} else {
							$id = 1;
						}
						$id_doc = sprintf("D0C%06s", $id);
						$res = explode(".", $_FILES['archivo_adjunto']['name'][$i]);
						$tipo_archivo = $_FILES['archivo_adjunto']['type'][$i];
						$extension = $res[count($res) - 1];
						$nombre_archivo = $id_doc . "." . $extension;

						//	verifico si se subio un archivo
						if (is_uploaded_file($_FILES['archivo_adjunto']['tmp_name'][$i])) {
							//	copio el archivo
							copy($_FILES['archivo_adjunto']['tmp_name'][$i], stripslashes("../../" . $path_archivos . "ventas/" . $nombre_archivo));
							//	elimino el archivo temporal
							unlink($_FILES['archivo_adjunto']['tmp_name'][$i]);
							//	inserto los datos
							$conexion->Query("INSERT INTO archivos
									(
										ID_ARCHIVO,
										ID_VENTA,
										NOMBRE_ARCHIVO,
										TIPO_ARCHIVO,
										DESCARGAS,
										COMENTARIO
									)
									VALUES(
										null,
										'" . $id_venta . "',
										'" . $nombre_archivo . "',
										'Archivo',
										0,
										'" . addslashes($_POST['comentario_archivo'][$i]) . "'
									)");
							$id_archivo = $conexion->Insert_ID();
						} # if
					} # for
				} # if

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			// Editar
			if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA FROM ventas WHERE (id_venta = '" . $id_modo_editar . "')");
				while (!$registros->EOF) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_CLIENTE"] . "')");
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_ACTUALIZACION"] . "')");
					?>
						<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
						<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
							<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($registros->fields["ID_SUCURSAL"]); ?>" />
							<input type="hidden" name="id_venta" id="id_venta" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" />
							<input type="hidden" name="tipo_venta" value="<?php echo ($registros->fields["TIPO_VENTA"]); ?>" />
							<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Venta</a></li>
									<li><a href="#tab02" data-toggle="tab">Adjuntar archivos</a></li>
								</ul>

								<div class="tab-content">
									<div id="tab01" class="tab-pane active">
										<div class="row-fluid">
											<div class="span8">
												<div class="control-group">
													<label class="control-label">Cliente</label>
													<div class="controls">
														<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo ($registros->fields["ID_CLIENTE"]); ?>" />
														<input type="text" name="codigo" id="codigo" value="<?php echo (sprintf("%04s", $datosUsuario["COD_USUARIO"])); ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo (stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?>" size="23" maxlength="50" title="Cliente" required style="width: 61%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<button type="button" id="btBuscarClienteVentana" class="btn" title="" data-toggle="modal" data-target="#modalBuscarCliente" data-backdrop="static" data-keyboard="true" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-search"></i> buscar cliente</button>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Direcci&oacute;n</label>
													<div class="controls">
														<input type="text" id="domicilio" name="domicilio" value="<?php echo (stripslashes($datosUsuario["DOMICILIO"])); ?>" size="23" maxlength="50" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
												<div class="row-fluid">
													<div class="span6">
														<div class="control-group">
															<label class="control-label">Tel&eacute;fonos</label>
															<div class="controls">
																<input type="text" id="telefonos" name="telefonos" value="<?php echo (stripslashes($datosUsuario["TELEFONOS"])); ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															</div>
														</div>
													</div>
													<div class="span6">
														<div class="control-group">
															<label class="control-label">Categoria</label>
															<div class="controls">
																<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php echo (stripslashes($datosUsuario["CATEGORIA_USUARIO"])); ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															</div>
														</div>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Cr&eacute;ditos</label>
													<div class="controls">
														<input type="hidden" id="old_creditos_venta" value="<?php echo ($registros->fields["CREDITOS_VENTA"]); ?>" />
														<input type="text" id="creditos" name="creditos" value="<?php echo ($datosUsuario["CREDITOS"] - ($registros->fields["CREDITOS_VENTA"])); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos actuales" readonly="readonly" />
														&nbsp;
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="creditos_venta" data-limite="<?php echo (($datosUsuario["CREDITOS"] - ($registros->fields["CREDITOS_VENTA"])) * (-1)); ?>" data-origen="creditos" data-destino="creditos_final" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
															<input type="text" id="creditos_venta" name="creditos_venta" value="<?php echo ($registros->fields["CREDITOS_VENTA"]); ?>" size="50" maxlength="20" class="input-mini" style="text-align: right;width: 25px;" title="Cr&eacute;ditos" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="creditos_venta" data-limite="100" data-origen="creditos" data-destino="creditos_final" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
														</div>
														&nbsp;
														<i class="icon-arrow-right"></i>
														&nbsp;
														<input type="text" id="creditos_final" name="creditos_final" value="<?php echo ($datosUsuario["CREDITOS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Cr&eacute;ditos finales" readonly="readonly" />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Gratis</label>
													<div class="controls">
														<input type="hidden" id="old_gratis_venta" value="<?php echo ($registros->fields["GRATIS_VENTA"]); ?>" />
														<input type="text" id="gratis" name="gratis" value="<?php echo ($datosUsuario["GRATIS"] - ($registros->fields["GRATIS_VENTA"])); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis actuales" readonly="readonly" />
														&nbsp;
														<div class="input-prepend input-append">
															<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="gratis_venta" data-limite="<?php echo (($datosUsuario["GRATIS"] - ($registros->fields["GRATIS_VENTA"])) * (-1)); ?>" data-origen="gratis" data-destino="gratis_final" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-minus"></i></button>
															<input type="text" id="gratis_venta" name="gratis_venta" value="<?php echo ($registros->fields["GRATIS_VENTA"]); ?>" size="50" maxlength="20" class="input-mini" style="text-align: right;width: 25px;" title="Gratis" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="gratis_venta" data-limite="100" data-origen="gratis" data-destino="gratis_final" class="btn" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-plus"></i></button>
														</div>
														&nbsp;
														<i class="icon-arrow-right"></i>
														&nbsp;
														<input type="text" id="gratis_final" name="gratis_final" value="<?php echo ($datosUsuario["GRATIS"]); ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Gratis finales" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span4">
												<div class="control-group">
													<label class="control-label">N&deg; de Venta</label>
													<div class="controls">
														<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $registros->fields["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Venta" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Fecha</label>
													<div class="controls">
														<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo ($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo ($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">N&deg; de Factura</label>
													<div class="controls">
														<?php
														if ($registros->fields["NRO_FACTURA"] == null) {
														?>
															<button type="button" id="btnAsignarFactura" class="btn" style="width: 105px;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-check"></i> Asignar</button>
														<?php
														} else {
														?>
															<input type="text" id="nro_factura" name="nro_factura" value="<?php echo ($registros->fields["NRO_FACTURA"]); ?>" size="23" maxlength="10" title="N&deg; de Factura" class="input-small" style="text-align: right;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<?php
														}
														?>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Tipo de Cambio</label>
													<div class="controls">
														<div class="input-prepend input-append">
															<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo (number_format($registros->fields["TIPO_CAMBIO"], 4, ',', '.')); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Estado</label>
													<div class="controls">
														<select id="estado" name="estado" title="Estado" style="width: auto;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
															<option value="" selected>Estado</option>
															<option value="Entregado" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("selected='selected'"); } ?>>Entregado</option>
															<option value="Pendiente" <?php if ($registros->fields["ESTADO"] == "Pendiente") { echo ("selected='selected'"); } ?>>Pendiente</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<hr />
										<div class="btn-toolbar">
											<div class="btn-group">
												<button type="button" id="btNuevoDetalle" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="<?php echo ($registros->fields["ID_CLIENTE"]); ?>" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-plus icon-white"></i> Nuevo detalle</button>
												<button type="button" id="btEliminarDetalle" class="btn btn-danger" disabled="disabled" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-trash icon-white"></i> Eliminar detalle</button>
											</div>
											<div class="btn-group">
												<button type="button" id="btnCalcularSubtotal" class="btn btn-primary" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-check icon-white"></i> Calcular Montos</button>
											</div>
										</div>
										<table class="table table-bordered table-condensed" id="listaDetalles">
											<thead>
												<tr>
													<th width="20"></th>
													<th width="80">C&oacute;digo</th>
													<th>Detalle</th>
													<th width="150">Cantidad</th>
													<th width="150">Precio</th>
													<th width="150">Descuento</th>
													<th width="150">Monto</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$detalles = $temp->Query("SELECT * FROM venta_posee_productos WHERE (id_venta = '" . $registros->fields["ID_VENTA"] . "') ORDER BY detalle");
												if ($detalles) {
													while (!$detalles->EOF) {
														//verifico si el PRODUCTO ha sido eliminado de forma logica
														$datosProducto = $temp2->GetRow("SELECT ELIMINADO FROM productos WHERE (id_producto = '" . $detalles->fields["ID_PRODUCTO"] . "')");
												?>
														<tr class="unChecked" <?php if ($datosProducto and ($datosProducto["ELIMINADO"] == 1)) { echo ("style='color: #ff0000;'"); } ?>>
															<td>
																<input type="checkbox" name="detalle" value="<?php echo ($detalles->fields["ID_PRODUCTO"]); ?>" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
																<input type="hidden" name="id_detalle[]" value="<?php echo ($detalles->fields["ID_PRODUCTO"]); ?>" />
															</td>
															<td>
																<?php echo (stripslashes($detalles->fields["CODIGO"])); ?><input type="hidden" name="detalleCodigo[]" value="<?php echo (stripslashes($detalles->fields["CODIGO"])); ?>" />
																<?php
																if ($datosProducto and ($datosProducto["ELIMINADO"] == 1)) {
																?>
																	<a href="javascript:void(0);" data-toggle="tooltip" title="Registro eliminado de manera l&oacute;gica" class="tooltip-demo"><i class="icon-exclamation-sign"></i></a>
																<?php
																}
																?>
															</td>
															<td><?php echo (stripslashes($detalles->fields["DETALLE"])); ?><input type="hidden" name="detalle[]" value="<?php echo (stripslashes($detalles->fields["DETALLE"])); ?>" /></td>
															<td style="text-align: right;"><?php echo (stripslashes($detalles->fields["CANTIDAD"])); ?><input type="hidden" name="detalleCantidad[]" value="<?php echo (stripslashes($detalles->fields["CANTIDAD"])); ?>" /></td>
															<td style="text-align: right;"><?php echo (number_format($detalles->fields["PRECIO_VENTA"], 2, ',', '.')); ?><input type="hidden" name="detallePrecioCosto[]" value="<?php echo (number_format($detalles->fields["PRECIO_COSTO"], 2, ',', '.')); ?>" /><input type="hidden" name="detallePrecioVenta[]" value="<?php echo (number_format($detalles->fields["PRECIO_VENTA"], 2, ',', '.')); ?>" /></td>
															<td style="text-align: right;"><?php echo (number_format($detalles->fields["DESCUENTO"], 2, ',', '.')); ?><input type="hidden" name="detalleDescuento[]" value="<?php echo (number_format($detalles->fields["DESCUENTO"], 2, ',', '.')); ?>" /></td>
															<td style="text-align: right;padding-right: 10px;"><?php echo (number_format($detalles->fields["TOTAL"], 2, ',', '.')); ?><input type="hidden" name="detalleTotal[]" value="<?php echo (number_format($detalles->fields["TOTAL"], 2, ',', '.')); ?>" /></td>
														</tr>
												<?php
														$detalles->MoveNext();
													}
												}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="6" style="text-align: right;font-weight: bold;">Importe</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="importe" name="importe" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IMPORTE"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="6" style="text-align: right;font-weight: bold;">
														<div class="btn-toolbar" style="display: inline;">
															<div class="btn-group">
																<button type="button" id="btIva" class="btn" data-porcentaje="0" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 0%</button>
																<button type="button" id="btIva" class="btn" data-porcentaje="13" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 13%</button>
																<button type="button" id="btIva" class="btn" data-porcentaje="16" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 16%</button>
															</div>
														</div>
													</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="iva" name="iva" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IVA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="6" style="text-align: right;font-weight: bold;">Subtotal</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="subtotal" name="subtotal" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SUBTOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="6" style="text-align: right;font-weight: bold;">Descuento</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="descuento" name="descuento" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["DESCUENTO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="4"></td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="total_me" name="total_me" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["TOTAL"]*convertirTipoMoneda(), 2, ',', '.')); ?>" size="50" maxlength="255" title="Total M/E" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on"><?php echo((convertirTipoMoneda() == 1 ? 'Bs' : $_SESSION['usuario_tipomoneda'])); ?></span>
														</div>
													</td>
													<td style="text-align: right;font-weight: bold;">Total</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="total" name="total" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="6" style="text-align: right;font-weight: bold;">Recibido</td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="recibido" name="recibido" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" value="<?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="6" id="msjSaldo" style="text-align: right;font-weight: bold;<?php if ($registros->fields["SALDO"] > 0) { echo ("color: #ff0000;"); } else { echo ("color: #000000;"); } ?>"><?php if ($registros->fields["SALDO"] == 0) { echo ("Saldo"); } else { if ($registros->fields["SALDO"] > 0) { echo ("Saldo que adeuda el Cliente"); } else { echo ("Saldo a favor del Cliente"); } } ?></td>
													<td style="text-align: right;">
														<div class="input-prepend input-append">
															<input type="text" id="saldo" name="saldo" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
															<span class="add-on">Bs</span>
														</div>
													</td>
												</tr>
											</tfoot>
										</table>
										<hr />
										<div class="row-fluid">
											<div class="span5">
												<div class="control-group">
													<label class="control-label">Descripci&oacute;n</label>
													<div class="controls">
														<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><?php echo (stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
													</div>
												</div>
											</div>
											<div class="span3">
												<div class="control-group">
													<label class="control-label">N&deg; de Cuota</label>
													<div class="controls">
														<input type="number" id="nro_cuota" name="nro_cuota" value="<?php echo ($registros->fields["NRO_CUOTA"]); ?>" min="0" size="23" maxlength="50" title="N&deg; de Cuota" class="input-small" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Vencimiento</label>
													<div class="controls">
														<input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo ($registros->fields["FECHA_VENCIMIENTO"]); ?>" max="<?php //echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Vencimiento" size="12" maxlength="10" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
											</div>
											<div class="span4">
												<div class="control-group">
													<label class="control-label">Usuario</label>
													<div class="controls">
														<select id="usuario" name="usuario" title="Administrador" required style="width: auto;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
															<option value=""></option>
															<?php
															for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
																$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
																if ($usuarios and ($usuarios->RecordCount() > 0)) {
															?>
																	<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																		<?php
																		while (!$usuarios->EOF) {
																		?>
																			<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIO"]) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																		<?php
																			$usuarios->MoveNext();
																		}
																		?>
																	</optgroup>
															<?php
																}
															}
															?>
														</select>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Pagado</label>
													<div class="controls">
														<label class="radio inline">
															<input type="radio" id="pagado" name="pagado" value="1" <?php if ($registros->fields["PAGADO"] == 1) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[1]); ?>
														</label>
														<label class="radio inline">
															<input type="radio" id="pagado" name="pagado" value="0" <?php if ($registros->fields["PAGADO"] == 0) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[0]); ?>
														</label>
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">Impreso</label>
													<div class="controls">
														<label class="radio inline">
															<input type="radio" id="impreso" name="impreso" value="1" <?php if ($registros->fields["IMPRESO"] == 1) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[1]); ?>
														</label>
														<label class="radio inline">
															<input type="radio" id="impreso" name="impreso" value="0" <?php if ($registros->fields["IMPRESO"] == 0) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[0]); ?>
														</label>
													</div>
												</div>
											</div>
										</div>
										<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo (formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo ($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo ("No Asignado"); } ?></strong>.</p>
									</div>

									<div id="tab02" class="tab-pane">
										<div class="control-group">
											<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
											<div class="controls">
												<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />

												<?php
												//	verifico si posee archivos adjuntos
												$i = 1;
												$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_venta = '" . $registros->fields["ID_VENTA"] . "') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");
												if ($registros2->RecordCount() > 0) {
												?>
													<table class="table table-striped table-bordered table-condensed">
														<thead>
															<tr>
																<th width="50">Indicador</th>
																<th>Descripci&oacute;n</th>
																<th width="20">Formato</th>
																<th width="50">Tama&ntilde;o</th>
																<th width="20">Descargas</th>
																<th width="20">Editar</th>
																<th width="20">Eliminar</th>
															</tr>
														</thead>
														<tbody>
															<?php
															while (!$registros2->EOF) {
																$referencia = "[ARCHIVO" . sprintf("%02s", $i) . "]";
															?>
																<tr>
																	<td><strong><?php echo ($referencia); ?></strong></td>
																	<?php
																	//	verifico si el archivo existe
																	$nombre_completo_documento = "../../" . $path_archivos . "ventas/" . $registros2->fields["NOMBRE_ARCHIVO"];
																	if (file_exists($nombre_completo_documento)) {
																		//	obtengo la extension del archivo
																		$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
																		$formato = $res[count($res) - 1];
																		//	obtengo el tama&ntilde;o del archivo
																		$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
																		//	muestro un enlace para ver o descargar la imagen
																	?>
																		<!--<td><a href="#" id="btnVerArchivo" data-archivo="<?php echo ($nombre_completo_documento); ?>" title="Ver Archivo"><?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo ($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo (stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td>-->
																		<td><a href="<?php echo ($nombre_completo_documento); ?>" target="_blank" title="Ver Archivo"><?php if (($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo ($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo (stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td>
																		<td style="text-align: center;"><?php echo ($formato); ?></td>
																		<td style="text-align: right;"><?php echo ($tamanio_archivo); ?> Kb</td>
																		<td style="text-align: right;"><?php echo ($registros2->fields["DESCARGAS"]); ?></td>
																		<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo ($registros->fields["ID_VENTA"]); ?>" data-archivo="<?php echo ($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo ($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo ($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo (stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
																		<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo ($registros->fields["ID_VENTA"]); ?>" data-archivo="<?php echo ($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo ($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo ($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo (stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
																	<?php
																	} else {
																		//muestro el nombre del archivo
																	?>
																		<td><?php echo ($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo ($registros->fields["ID_VENTA"]); ?>" data-archivo="<?php echo ($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo ($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo ($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo (stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
																	<?php
																	}
																	?>
																</tr>
															<?php
																$registros2->MoveNext();
																$i++;
															}	#while
															?>
														</tbody>
													</table>
												<?php
												}	#if
												?>
												<input type="hidden" id="indicadorArchivo" value="<?php echo ($i); ?>" />
												<div id="adjuntos_archivo<?php echo ($i); ?>" class="cloneArchivo">
													<label class="control-label"><?php echo ("[ARCHIVO" . sprintf("%02s", $i) . "]"); ?></label>
													<div class="controls">
														<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
														<input type="file" id="archivo_adjunto" name="archivo_adjunto[]" title="Archivo Adjunto" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>

								<div class="form-actions">
									<?php if ($_GET['accion'] == "ver") { ?>
										<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
									<?php } else { ?>
										<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
										<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
									<?php } ?>
								</div>
							</div>
						</form>

						<div id="modalDetalle" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form style="margin: 0;">
								<input type="hidden" name="origen" value="" />

								<div class="modal-header">
									<a href="#" class="close" data-dismiss="modal">&times;</a>
									<h3>Modal Heading</h3>
									<div class="row-fluid">
										<div class="span5" style="text-align: left;">
											<strong>Ingrese el texto a buscar: </strong><br />
											<input type="text" id="q" name="q" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="codigo, nombre o codigo de barra" style="width: 99%;" autocomplete="off" />
										</div>
										<div class="span3" style="text-align: left;">
											<strong>Categoría: </strong><br />
											<select id="listaCategoria" name="listaCategoria" title="Categoría" style="width: 99%;">
												<option value="">Categoría</option>
												<?php
												$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
												while (!$categorias->EOF) {
													?>
													<option value="<?php echo ($categorias->fields["CATEGORIA"]); ?>"><?php echo ($categorias->fields["CATEGORIA"]); ?></option>
													<?php
													$categorias->MoveNext();
												}
												?>
											</select>
										</div>
										<div class="span3" style="text-align: left;">
											<strong>G&eacute;nero: </strong><br />
											<?php
											//	obtengo todos los GENEROS individualmente del menu de contenidos
											$listaGeneros = array();
											$registros = $conexion->Query("SELECT GENERO FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((genero is not null) and (genero <> '')) GROUP BY genero ORDER BY genero");
											while (!$registros->EOF) {
												if (strpos($registros->fields["GENERO"], ",") > 0) {
													$arrayGeneros = explode(",", stripslashes($registros->fields["GENERO"]));
													foreach ($arrayGeneros as $key => $val) {
														if (!in_array(trim($val), $listaGeneros)) {
															array_push($listaGeneros, trim($val));
														}
													}
												} else {
													if (!in_array($registros->fields["GENERO"], $listaGeneros)) {
														array_push($listaGeneros, stripslashes($registros->fields["GENERO"]));
													}
												}
												$registros->MoveNext();
											}
											sort($listaGeneros);
											?>
											<select id="listaGenero" name="listaGenero" title="G&eacute;nero" style="width: 99%;">
												<option value="">Género</option>
												<?php
												foreach ($listaGeneros as $key => $val) {
													?>
													<option value="<?php echo ($val); ?>"><?php echo ($val); ?></option>
													<?php
												}
												?>
											</select>
										</div>
										<div class="span1" style="text-align: center;">
											<br />
											<button type="button" id="btnModalBuscar" class="btn btn-primary" data-target="#modalDetalle" title="Buscar"><i class="icon-search icon-white"></i> </button>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<table class="table table-bordered table-condensed" id="listaProductos">
										<thead>
											<tr>
												<th></th>
												<th data-sort="int">C&oacute;digo</th>
												<th data-sort="string">Nombre</th>
												<th data-sort="string">Categoria</th>
												<th data-sort="string">G&eacute;nero</th>
												<th data-sort="float" width="70">Precio</th>
												<th data-sort="int" width="50">Stock</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="modal-footer">
									<div class="row-fluid">
										<div class="span3" style="text-align: left;">
											<strong>Cantidad: </strong>
											<input type="text" id="detalleCantidad" name="detalleCantidad" title="Cantidad" pattern="[-+]?[0-9]*" class="input-small" placeholder="0" style="text-align: right;" required />
											<input type="hidden" id="detallePrecio" name="detallePrecio" />
										</div>
										<div class="span3" style="text-align: left;">
											<strong>Descuento: </strong>
											<input type="text" id="detalleDescuento" name="detalleDescuento" title="Descuento" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" placeholder="0,00" style="text-align: right;" required />
										</div>
										<div class="span3" style="text-align: left;">
											<strong>Total: </strong>
											<input type="text" id="detalleTotal" name="destalleTotal" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" required readonly="readonly" />
										</div>
										<div class="span3" style="text-align: right;">
											<input type="button" class="btn btn-primary" data-target="#modalDetalle" id="btnModalGuardar" value="Aceptar" />
											<input type="button" class="btn" id="btnModalCancelar" data-dismiss="modal" value="Cancelar" />
										</div>
									</div>
								</div>

							</form>
						</div>

						<div id="modalBuscarCliente" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form style="margin: 0;">
								<input type="hidden" name="origen" value="" />

								<div class="modal-header">
									<a href="#" class="close" data-dismiss="modal">&times;</a>
									<h3>Modal Heading</h3>
									<div class="row-fluid">
										<div class="span10" style="text-align: left;">
											<input type="text" id="queryCliente" name="queryCliente" size="50" maxlength="255" title="buscar registros..." placeholder="buscar por código, documento, nombre o teléfono..." style="width: 99%;" />
										</div>
										<div class="span2" style="text-align: center;">
											<button type="button" id="btnModalBuscarCliente" class="btn btn-primary" data-target="#modalBuscarCliente" title="Buscar">
												<i class="icon-search icon-white"></i> buscar
											</button>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<table class="table table-bordered table-condensed" id="listaClientes">
										<thead>
											<tr>
												<th></th>
												<th data-sort="int">C&oacute;digo</th>
												<th data-sort="string">Documento</th>
												<th data-sort="string">Nombres y Apellidos</th>
												<th data-sort="string">Tel&eacute;fonos</th>
												<th data-sort="int" width="70">Cr&eacute;ditos</th>
												<th data-sort="int" width="70">Gratis</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="modal-footer">
									<input type="button" class="btn btn-primary" data-target="#modalBuscarCliente" id="btnModalGuardarCliente" value="Aceptar" />
									<input type="button" class="btn" id="btnModalCancelar" data-dismiss="modal" value="Cancelar" />
								</div>

							</form>
						</div>
					<?php
					$registros->MoveNext();
				}
			}

			//	Eliminar imagen/archivo
			if (isset($_GET['accion']) and ($_GET['accion'] == "deleteimagen")) {
				//	obtengo los datos
				$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_venta = '" . $_POST['idc'] . "') and (id_archivo = '" . $_POST['ida'] . "') ORDER BY id_archivo");
				if ($registros) {
					while (!$registros->EOF) {
						if ($_POST['tipo'] == 'Imagen') {
							//	verifico si el archivo existe
							$nombre_completo_imagenoriginal = "../../" . $path_imagenes . "ventas/" . $registros->fields["NOMBRE_ARCHIVO"];
							if (file_exists($nombre_completo_imagenoriginal)) {
								//	obtengo el nombre y extension del archivo
								$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen original
								$nombre_completo_imagensmall = "../../" . $path_imagenes . "ventas/" . $nombre . "_small." . $extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if (file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}
						}
						if ($_POST['tipo'] == 'Archivo') {
							//	verifico si el archivo existe
							$nombre_completo_documento = "../../" . $path_archivos . "ventas/" . $registros->fields["NOMBRE_ARCHIVO"];
							if (file_exists($nombre_completo_documento)) {
								//	elimino el archivo
								unlink($nombre_completo_documento);
							}
						}
						//	elimino el registro
						$temp->Query("DELETE FROM archivos WHERE (id_archivo = '" . $registros->fields["ID_ARCHIVO"] . "')");
						$registros->MoveNext();
					}
				}
				echo ("{ \"estado\" : \"ok\" }");
			}

			//	Recuperar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "recuperar")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=updateregistros"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">Volver al listado</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<h2>Recuperar registros eliminados</h2>

								<table id="listado" class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th style="width:15px;vertical-align: top;"><input type="checkbox" id="CheckTodo" name="CheckTodo"></th>
											<th style="width: 42px;">&nbsp;</th>
											<th>N&deg;</th>
											<th width="80">Fecha</th>
											<th width="80">N&deg; Factura</th>
											<th>Cliente</th>
											<th>Estado</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
											<th>Pagado</th>
											<th width="120">F. Eliminado</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$query = "SELECT COD_VENTA,ventas.FECHA_ALTA,NRO_FACTURA,NOMBRE_COMPLETO,ESTADO,TOTAL,RECIBIDO,SALDO,PAGADO,FECHA_ELIMINADO,ID_VENTA FROM ventas
											LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
											WHERE (tipo_venta = '" . $_GET['menu'] . "') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 1) ";
										if (isset($_GET['texto'])) {
											$query .= " and (
												(cod_venta LIKE '%" . $_GET['texto'] . "%') or
												(nro_factura LIKE '%" . $_GET['texto'] . "%') or
												(nombre_completo LIKE '%" . $_GET['texto'] . "%') or
												(estado LIKE '%" . $_GET['texto'] . "%')
											) ";
										}
										$query .= "ORDER BY ventas.fecha_eliminado DESC,cod_venta DESC";

										$registros = $conexion->Query($query);
										while (!$registros->EOF) {
										?>
											<tr>
												<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" /></td>
												<td>
													<div class="btn-toolbar">
														<div class="btn-group">
															<button type="button" id="btEliminarRegistroFisico" class="btn btn-mini btn-danger" title="Eliminar registro fiscamente"><i class="icon-remove icon-white"></i></button>
														</div>
													</div>
												</td>
												<td style="text-align: right;"><?php echo (sprintf("%04s", $registros->fields["COD_VENTA"])); ?></td>
												<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
												<td><?php echo ($registros->fields["NRO_FACTURA"]); ?></td>
												<td><?php echo ($registros->fields["NOMBRE_COMPLETO"]); ?></td>
												<td><?php echo ($registros->fields["ESTADO"]); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?></td>
												<td><?php echo ($estados[$registros->fields["PAGADO"]]); ?></td>
												<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ELIMINADO"], 6)); ?></td>
											</tr>
										<?php
											$registros->MoveNext();
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Recuperar registros" onclick="return confirm('Se va ha Recuperar todos los registros seleccionados.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "updateregistros")) {
				echo ("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");

				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						$conexion->Execute("UPDATE ventas SET eliminado = 0, fecha_eliminado = null WHERE (id_venta = '" . $valor . "')");
					}
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			//	Cambiar estado
			if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
						if ($datos) {
							if ($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
							} else {
								if ($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE ventas SET activo = 0 WHERE (id_venta = '" . $valor . "')");
								} else {
									$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
							if ($datos) {
								echo ("{ \"estado\" : \"" . $estados[$datos["ACTIVO"]] . "\" }");
							} else {
								echo ("{ \"estado\" : \"error\" }");
							}
						}
					}
				}
			}
		}
		//	-----	Fin VENTAS

		//	-----	SERVICIOS TECNICOS
		if (isset($_GET['menu']) and ($_GET['menu'] == "servicios")) {
			$url_modulo = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'];
			//if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
			if (!isset($_GET['anio'])) {
				$_GET['anio'] = "";
			}
			if (isset($_GET['anio']) and ($_GET['anio'] != "")) {
				$url_modulo .= "&anio=" . $_GET['anio'];
			}

			//	Listado
			if (!isset($_GET['accion'])) {
				$query = "SELECT 
						COD_VENTA,
						ventas.FECHA_ALTA,
						NOMBRE_COMPLETO,
						CONSOLA,
						ventas.MODELO,
						NRO_SERIE,
						DESCRIPCION,
						ESTADO,
						FECHA_ENTREGA,
						(TOTAL * ".convertirTipoMoneda().") TOTAL, 
						(RECIBIDO * ".convertirTipoMoneda().") RECIBIDO, 
						(SALDO * ".convertirTipoMoneda().") SALDO, 
						PAGADO,
						ID_VENTA
					FROM ventas
					LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
					WHERE
						(tipo_venta = '" . $_GET['menu'] . "')
						and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
						and (ventas.eliminado = 0) 
				";
				if (isset($_GET['anio']) and ($_GET['anio'] != "")) {
					$query .= " and (year(ventas.fecha_alta) = '" . $_GET['anio'] . "') ";
				}
				if (isset($_GET['texto'])) {
					$query .= " and (
							(upper(cod_venta) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(nombre_completo) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(consola) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(ventas.modelo) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(nro_serie) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(descripcion) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(estado) LIKE upper('%" . $_GET['texto'] . "%')) or
							(total LIKE '%" . $_GET['texto'] . "%') or
							(recibido LIKE '%" . $_GET['texto'] . "%') or
							(saldo LIKE '%" . $_GET['texto'] . "%')
						) ";
				}
				$query .= "ORDER BY ventas.fecha_alta DESC,ventas.hora_alta DESC,cod_venta DESC";

				showTable2(
					$conexion,
					$query,
					array(
						"COD_VENTA" => "N&deg;",
						"FECHA_ALTA" => "Fecha",
						"NOMBRE_COMPLETO" => "Cliente",
						"CONSOLA" => "Consola",
						"MODELO" => "Modelo",
						"NRO_SERIE" => "Nro. de Serie",
						"DESCRIPCION" => "Problema",
						"ESTADO" => "Estado",
						"FECHA_ENTREGA" => "F. Entrega",
						"TOTAL" => "Total",
						"RECIBIDO" => "Recibido",
						"SALDO" => "Saldo",
						"PAGADO" => "Pagado"
					),
					array(0 => "ID_VENTA"),
					$_SESSION['usuario_rxp'],
					true,
					true,
					$datos_acceso,
					null,
					array(
						"renombrarConsola" => "Renombrar Consola",
						"renombrarModelo" => "Renombrar Modelo"
					),
					array("imprimirServicio" => "Imprimir Servicio")
				);
			}

			// Nuevo
			if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				if (isset($_GET['registro'])) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $_GET['registro'][0] . "')");
				}
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="tipo_venta" value="<?php echo ($_GET['menu']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos del Servicio T&eacute;cnico</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<input type="hidden" name="id_cliente" id="id_cliente" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["ID_USUARIO"]); } ?>" />
												<input type="text" name="codigo" id="codigo" value="<?php if (isset($datosUsuario)) { echo (sprintf("%04s", $datosUsuario["COD_USUARIO"])); } ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" <?php if (!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled" <?php } ?> autocomplete="off" />
												<button type="button" id="btBuscarCliente" class="btn" title=""><i class="icon-search"></i> </button>
												<input type="text" id="cliente" name="cliente" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["NOMBRE_COMPLETO"]); } ?>" size="23" maxlength="255" title="Cliente" required style="width: 61%;" <?php if (!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled" <?php } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["DOMICILIO"]); } ?>" size="23" maxlength="255" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Tel&eacute;fonos</label>
													<div class="controls">
														<input type="text" id="telefonos" name="telefonos" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["TELEFONOS"]); } ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Categoria</label>
													<div class="controls">
														<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["CATEGORIA_USUARIO"]); } ?>" size="23" maxlength="50" title="Categoria de Usuarios" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">N&deg; de Servicio</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SELECT IF(COD_VENTA > 0, (max(COD_VENTA) + 1), 1) as COD_VENTA FROM ventas WHERE (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (tipo_venta = '" . $_GET['menu'] . "') ORDER BY cod_venta DESC");
												?>
												<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $datos["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Servicio" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo (date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo (date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">N&deg; de Factura</label>
											<div class="controls">
												<button type="button" id="btnAsignarFactura" class="btn" style="width: 105px;"><i class="icon-check"></i> Asignar</button>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipo de Cambio</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo ($_SESSION['usuario_tipocambio']); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Estado</label>
											<div class="controls">
												<select id="estado" name="estado" title="Estado" style="width: auto;" required>
													<option value="">Estado</option>
													<option value="Entregado">Entregado</option>
													<option value="Sin diagn&oacute;stico" selected>Sin diagn&oacute;stico</option>
													<option value="Con diagn&oacute;stico">Con diagn&oacute;stico</option>
													<option value="Con diagn&oacute;stico confirmado">Con diagn&oacute;stico confirmado</option>
													<option value="Con diagn&oacute;stico cancelado">Con diagn&oacute;stico cancelado</option>
													<option value="Reparado">Reparado</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="row-fluid">
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Consola</label>
											<div class="controls">
												<input type="text" id="consola" name="consola" size="23" maxlength="50" title="Consola" style="width: 99%;" />
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Modelo</label>
											<div class="controls">
												<input type="text" id="modelo" name="modelo" size="23" maxlength="50" title="Modelo" style="width: 99%;" />
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Nro. de Serie</label>
											<div class="controls">
												<input type="text" id="nro_serie" name="nro_serie" size="23" maxlength="50" title="Nro. de Serie" style="width: 97%;" />
											</div>
										</div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Problema</label>
									<div class="controls">
										<textarea id="descripcion" name="descripcion" rows="3" cols="60" title="Problema" style="width: 99%;" required></textarea>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Observaciones</label>
											<div class="controls">
												<textarea id="observaciones" name="observaciones" rows="4" cols="60" title="Observaciones" style="width: 99%;"></textarea>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Diagnostico</label>
											<div class="controls">
												<textarea id="diagnostico" name="diagnostico" rows="4" cols="60" title="Diagnostico" style="width: 97%;"></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Fecha de Entrega</label>
											<div class="controls">
												<input type="date" name="fecha_entrega" id="fecha_entrega" value="<?php echo (date("Y-m-d")); ?>" class="input-medium" title="Fecha de Entrega" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" title="Hora" value="<?php echo (date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
												<select class="input-mini" name="hora_entrega" title="Hora de Entrega" style="width: auto;">
													<?php
													for ($horas = 0; $horas < 24; $horas++) {
														for ($j = 0; $j < 4; $j++) {
															$minutos = 15 * $j;
															$horario = sprintf("%02s", $horas) . ":" . sprintf("%02s", $minutos);
													?>
															<option value="<?php echo ($horario); ?>" <?php if ($horario == "08:00") { echo ("selected='selected'"); } ?>><?php echo ($horario); ?></option>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button type="button" id="btnCalcularSubtotal" class="btn btn-primary"><i class="icon-check icon-white"></i> Calcular Montos</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>Importe</th>
											<th>
												<div class="btn-toolbar" style="display: inline;">
													<div class="btn-group">
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="0">IVA 0%</button>
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="13">IVA 13%</button>
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="16">IVA 16%</button>
													</div>
												</div>
											</th>
											<th>Subtotal</th>
											<th>Descuento</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="text-align: right;"><input type="text" id="importe" name="importe" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="iva" name="iva" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="subtotal" name="subtotal" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
											<td style="text-align: right;"><input type="text" id="descuento" name="descuento" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="total" name="total" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
											<td style="text-align: right;"><input type="text" id="recibido" name="recibido" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="saldo" name="saldo" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="7">
												<div id="msjSaldo" style="text-align: right;"></div>
											</td>
										</tr>
									</tfoot>
								</table>
								<hr />
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<select id="usuario" name="usuario" title="Administrador" required style="width: auto;">
													<option value=""></option>
													<?php
													for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
														$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
														if ($usuarios and ($usuarios->RecordCount() > 0)) {
													?>
															<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																<?php
																while (!$usuarios->EOF) {
																?>
																	<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																<?php
																	$usuarios->MoveNext();
																}
																?>
															</optgroup>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Pagado</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="1" /> <?php echo ($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="0" checked="checked" /> <?php echo ($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Impreso</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="impreso" name="impreso" value="1" /> <?php echo ($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="impreso" name="impreso" value="0" checked="checked" /> <?php echo ($estados[0]); ?>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			// Insertar
			if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if ($_POST['tipo_cambio'] == "") {
					$_POST['tipo_cambio'] = 0;
				}
				$vTipoCambio = str_replace(".", "", $_POST['tipo_cambio']);
				$vTipoCambio = str_replace(",", ".", $vTipoCambio);

				$vImporte = str_replace(".", "", $_POST['importe']);
				$vImporte = str_replace(",", ".", $vImporte);
				$vIva = str_replace(".", "", $_POST['iva']);
				$vIva = str_replace(",", ".", $vIva);
				$vSubtotal = str_replace(".", "", $_POST['subtotal']);
				$vSubtotal = str_replace(",", ".", $vSubtotal);
				$vDescuento = str_replace(".", "", $_POST['descuento']);
				$vDescuento = str_replace(",", ".", $vDescuento);
				$vTotal = str_replace(".", "", $_POST['total']);
				$vTotal = str_replace(",", ".", $vTotal);
				$vRecibido = str_replace(".", "", $_POST['recibido']);
				$vRecibido = str_replace(",", ".", $vRecibido);
				$vSaldo = str_replace(".", "", $_POST['saldo']);
				$vSaldo = str_replace(",", ".", $vSaldo);

				if (isset($_POST['id_venta'])) {
					//	Update
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE ventas
							SET
								id_sucursal ='" . $_POST['sucursal'] . "',
								id_cliente = '" . $_POST['id_cliente'] . "',
								id_usuario = '" . $_POST['usuario'] . "',
								cod_venta = '" . $_POST['cod_venta'] . "',
								fecha_alta = '" . $_POST['fecha_alta'] . "',
								hora_alta = '" . $_POST['hora_alta'] . ":00',
								tipo_cambio = '" . number_format($vTipoCambio, 4, '.', '') . "',
								importe = '" . number_format($vImporte, 2, '.', '') . "',
								iva = '" . number_format($vIva, 2, '.', '') . "',
								subtotal = '" . number_format($vSubtotal, 2, '.', '') . "',
								descuento = '" . number_format($vDescuento, 2, '.', '') . "',
								total = '" . number_format($vTotal, 2, '.', '') . "',
								recibido = '" . number_format($vRecibido, 2, '.', '') . "',
								saldo = '" . number_format($vSaldo, 2, '.', '') . "',
								pagado = '" . $_POST['pagado'] . "',
								impreso = '" . $_POST['impreso'] . "',
								descripcion = '" . addslashes($_POST['descripcion']) . "',
								consola = '" . addslashes($_POST['consola']) . "',
								modelo = '" . addslashes($_POST['modelo']) . "',
								nro_serie = '" . addslashes($_POST['nro_serie']) . "',
								observaciones = '" . addslashes($_POST['observaciones']) . "',
								diagnostico = '" . addslashes($_POST['diagnostico']) . "',
								estado = '" . $_POST['estado'] . "',
								id_actualizacion = '" . $_POST['id_actualizacion'] . "'
							WHERE (id_venta = '" . $_POST['id_venta'] . "')");

					if (isset($_POST['nro_factura'])) {
						$conexion->Query("UPDATE ventas
								SET
									nro_factura = '" . addslashes($_POST['nro_factura']) . "'
								WHERE (id_venta = '" . $_POST['id_venta'] . "')");
					}

					if ($_POST['estado'] == "Entregado") {
						$conexion->Query("UPDATE ventas
								SET
									id_tecnico = '" . $_POST['tecnico'] . "',
									fecha_entrega = '" . $_POST['fecha_entrega'] . "',
									hora_entrega = '" . $_POST['hora_entrega'] . ":00'
								WHERE (id_venta = '" . $_POST['id_venta'] . "')");
					} else {
						$conexion->Query("UPDATE ventas
								SET
									id_tecnico = null,
									fecha_entrega = null,
									hora_entrega = null
								WHERE (id_venta = '" . $_POST['id_venta'] . "')");
					}

					$id_venta = $_POST['id_venta'];
				} else {
					//	Insert
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO ventas
							(
								ID_VENTA,
								ID_SUCURSAL,
								ID_CLIENTE,
								ID_USUARIO,
								COD_VENTA,
								FECHA_ALTA,
								HORA_ALTA,
								TIPO_VENTA,
								TIPO_CAMBIO,
								IMPORTE,
								IVA,
								SUBTOTAL,
								DESCUENTO,
								TOTAL,
								RECIBIDO,
								SALDO,
								PAGADO,
								IMPRESO,
								DESCRIPCION,
								CONSOLA,
								MODELO,
								NRO_SERIE,
								OBSERVACIONES,
								DIAGNOSTICO,
								ESTADO,
								ID_ACTUALIZACION
							) VALUES(
								null,
								'" . $_POST['sucursal'] . "',
								'" . $_POST['id_cliente'] . "',
								'" . $_POST['usuario'] . "',
								'" . $_POST['cod_venta'] . "',
								'" . $_POST['fecha_alta'] . "',
								'" . $_POST['hora_alta'] . ":00',
								'" . $_POST['tipo_venta'] . "',
								'" . number_format($vTipoCambio, 4, '.', '') . "',
								'" . number_format($vImporte, 2, '.', '') . "',
								'" . number_format($vIva, 2, '.', '') . "',
								'" . number_format($vSubtotal, 2, '.', '') . "',
								'" . number_format($vDescuento, 2, '.', '') . "',
								'" . number_format($vTotal, 2, '.', '') . "',
								'" . number_format($vRecibido, 2, '.', '') . "',
								'" . number_format($vSaldo, 2, '.', '') . "',
								'" . $_POST['pagado'] . "',
								'" . $_POST['impreso'] . "',
								'" . addslashes($_POST['descripcion']) . "',
								'" . addslashes($_POST['consola']) . "',
								'" . addslashes($_POST['modelo']) . "',
								'" . addslashes($_POST['nro_serie']) . "',
								'" . addslashes($_POST['observaciones']) . "',
								'" . addslashes($_POST['diagnostico']) . "',
								'" . $_POST['estado'] . "',
								'" . $_POST['id_actualizacion'] . "'
							)");
					$id_venta = $conexion->Insert_ID();

					if (isset($_POST['nro_factura'])) {
						$conexion->Query("UPDATE ventas
								SET
									nro_factura = '" . addslashes($_POST['nro_factura']) . "'
								WHERE (id_venta = '" . $id_venta . "')");
					}
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			// Editar
			if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA,concat(REPEAT('0', (2-LENGTH(hour(HORA_ENTREGA)))),hour(HORA_ENTREGA),':',REPEAT('0', (2-LENGTH(minute(HORA_ENTREGA)))),minute(HORA_ENTREGA)) as HORAENTREGA FROM ventas WHERE (id_venta = '" . $id_modo_editar . "')");
				while (!$registros->EOF) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_CLIENTE"] . "')");
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_ACTUALIZACION"] . "')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_venta" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" />
						<input type="hidden" name="tipo_venta" value="<?php echo ($registros->fields["TIPO_VENTA"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos del Servicio T&eacute;cnico</a></li>
								<li><a href="formServicio.php?id=<?php echo ($registros->fields["ID_VENTA"]); ?>" target="_blank"><i class="icon-print"></i> Imprimir</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Cliente</label>
												<div class="controls">
													<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo ($registros->fields["ID_CLIENTE"]); ?>" />
													<input type="text" name="codigo" id="codigo" value="<?php echo (sprintf("%04s", $datosUsuario["COD_USUARIO"])); ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" autocomplete="off" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<button type="button" id="btBuscarCliente" class="btn" title="" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-search"></i> </button>
													<input type="text" id="cliente" name="cliente" value="<?php echo (stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?>" size="23" maxlength="50" title="Cliente" required style="width: 61%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" value="<?php echo (stripslashes($datosUsuario["DOMICILIO"])); ?>" size="23" maxlength="50" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Tel&eacute;fonos</label>
														<div class="controls">
															<input type="text" id="telefonos" name="telefonos" value="<?php echo (stripslashes($datosUsuario["TELEFONOS"])); ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Categoria</label>
														<div class="controls">
															<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php echo (stripslashes($datosUsuario["CATEGORIA_USUARIO"])); ?>" size="23" maxlength="50" title="Categoria de Usuario" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">N&deg; de Servicio</label>
												<div class="controls">
													<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $registros->fields["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Servicio" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha</label>
												<div class="controls">
													<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo ($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo ($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">N&deg; de Factura</label>
												<div class="controls">
													<?php
													if ($registros->fields["NRO_FACTURA"] == null) {
													?>
														<button type="button" id="btnAsignarFactura" class="btn" style="width: 105px;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-check"></i> Asignar</button>
													<?php
													} else {
													?>
														<input type="text" id="nro_factura" name="nro_factura" value="<?php echo ($registros->fields["NRO_FACTURA"]); ?>" size="23" maxlength="10" title="N&deg; de Factura" class="input-small" style="text-align: right;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<?php
													}
													?>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tipo de Cambio</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo (number_format($registros->fields["TIPO_CAMBIO"], 4, ',', '.')); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Estado</label>
												<div class="controls">
													<select id="estado" name="estado" title="Estado" style="width: auto;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
														<option value="" selected>Estado</option>
														<option value="Entregado" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("selected='selected'"); } ?>>Entregado</option>
														<option value="Sin diagn&oacute;stico" <?php if (htmlentities($registros->fields["ESTADO"]) == utf8_decode("Sin diagn&oacute;stico")) { echo ("selected='selected'"); } ?>>Sin diagn&oacute;stico</option>
														<option value="Con diagn&oacute;stico" <?php if (htmlentities($registros->fields["ESTADO"]) == utf8_decode("Con diagn&oacute;stico")) { echo ("selected='selected'"); } ?>>Con diagn&oacute;stico</option>
														<option value="Con diagn&oacute;stico confirmado" <?php if (htmlentities($registros->fields["ESTADO"]) == utf8_decode("Con diagn&oacute;stico confirmado")) { echo ("selected='selected'"); } ?>>Con diagn&oacute;stico confirmado</option>
														<option value="Con diagn&oacute;stico cancelado" <?php if (htmlentities($registros->fields["ESTADO"]) == utf8_decode("Con diagn&oacute;stico cancelado")) { echo ("selected='selected'"); } ?>>Con diagn&oacute;stico cancelado</option>
														<option value="Reparado" <?php if ($registros->fields["ESTADO"] == "Reparado") { echo ("selected='selected'"); } ?>>Reparado</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div id="divEntregado" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("style='display: block;'"); } else { echo ("style='display: none;'"); } ?>>
										<hr />
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">T&eacute;cnico que entrega</label>
													<div class="controls">
														<select id="tecnico" name="tecnico" title="T&eacute;cnico que entrega" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("required"); } ?> style="width: auto;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
															<option value="">T&eacute;cnico que entrega</option>
															<?php
															for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
																$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
																if ($usuarios and ($usuarios->RecordCount() > 0)) {
															?>
																	<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																		<?php
																		while (!$usuarios->EOF) {
																		?>
																			<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_TECNICO"]) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																		<?php
																			$usuarios->MoveNext();
																		}
																		?>
																	</optgroup>
															<?php
																}
															}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Fecha de Entrega</label>
													<div class="controls">
														<input type="text" name="fecha_entrega" id="fecha_entrega" value="<?php if (($registros->fields["FECHA_ENTREGA"] == null) or ($registros->fields["FECHA_ENTREGA"] == "0000-00-00")) { echo (date("Y-m-d")); } else { echo ($registros->fields["FECHA_ENTREGA"]); } ?>" class="input-medium" title="Fecha de Entrega" size="12" maxlength="10" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("required"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<input type="text" name="hora_entrega" id="hora_entrega" class="input-small" style="width: 50px;" title="Hora de Entrega" value="<?php if (($registros->fields["HORA_ENTREGA"] == null) or ($registros->fields["HORA_ENTREGA"] == "00:00:00")) { echo (date("H:i")); } else { echo ($registros->fields["HORAENTREGA"]); } ?>" maxlength="5" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("required"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													</div>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="row-fluid">
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Consola</label>
												<div class="controls">
													<input type="text" id="consola" name="consola" value="<?php echo (stripslashes($registros->fields["CONSOLA"])); ?>" size="23" maxlength="50" title="Consola" style="width: 99%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Modelo</label>
												<div class="controls">
													<input type="text" id="modelo" name="modelo" value="<?php echo (stripslashes($registros->fields["MODELO"])); ?>" size="23" maxlength="50" title="Modelo" style="width: 99%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Nro. de Serie</label>
												<div class="controls">
													<input type="text" id="nro_serie" name="nro_serie" value="<?php echo (stripslashes($registros->fields["NRO_SERIE"])); ?>" size="23" maxlength="50" title="Nro. de Serie" style="width: 97%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Problema</label>
										<div class="controls">
											<textarea id="descripcion" name="descripcion" rows="3" cols="60" title="Problema" style="width: 99%;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><?php echo (stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Observaciones</label>
												<div class="controls">
													<textarea id="observaciones" name="observaciones" rows="4" cols="60" title="Observaciones" style="width: 99%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><?php echo (stripslashes($registros->fields["OBSERVACIONES"])); ?></textarea>
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Diagnostico</label>
												<div class="controls">
													<textarea id="diagnostico" name="diagnostico" rows="4" cols="60" title="Diagnostico" style="width: 97%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><?php echo (stripslashes($registros->fields["DIAGNOSTICO"])); ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="btn-toolbar">
										<div class="btn-group">
											<button type="button" id="btnCalcularSubtotal" class="btn btn-primary" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-check icon-white"></i> Calcular Montos</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th>Importe</th>
												<th>
													<div class="btn-toolbar" style="display: inline;">
														<div class="btn-group">
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="0" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 0%</button>
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="13" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 13%</button>
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="16" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 16%</button>
														</div>
													</div>
												</th>
												<th>Subtotal</th>
												<th>Descuento</th>
												<th>Total</th>
												<th>Recibido</th>
												<th>Saldo</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="text-align: right;"><input type="text" id="importe" name="importe" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IMPORTE"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="iva" name="iva" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IVA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="subtotal" name="subtotal" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SUBTOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="descuento" name="descuento" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["DESCUENTO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="total" name="total" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="recibido" name="recibido" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="saldo" name="saldo" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="7">
													<div id="msjSaldo" style="text-align: right;<?php if ($registros->fields["SALDO"] > 0) { echo ("color: #ff0000;"); } else { echo ("color: #000000;"); } ?>"><?php if ($registros->fields["SALDO"] == 0) { echo ("Saldo"); } else { if ($registros->fields["SALDO"] > 0) { echo ("Saldo que adeuda el Cliente"); } else { echo ("Saldo a favor del Cliente"); } } ?></div>
												</td>
											</tr>
										</tfoot>
									</table>
									<hr />
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Usuario</label>
												<div class="controls">
													<select id="usuario" name="usuario" title="Administrador" required style="width: auto;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
														for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
															$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
															if ($usuarios and ($usuarios->RecordCount() > 0)) {
														?>
																<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																	<?php
																	while (!$usuarios->EOF) {
																	?>
																		<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIO"]) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																	<?php
																		$usuarios->MoveNext();
																	}
																	?>
																</optgroup>
														<?php
															}
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Pagado</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="pagado" name="pagado" value="1" <?php if ($registros->fields["PAGADO"] == 1) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="pagado" name="pagado" value="0" <?php if ($registros->fields["PAGADO"] == 0) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Impreso</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="impreso" name="impreso" value="1" <?php if ($registros->fields["IMPRESO"] == 1) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="impreso" name="impreso" value="0" <?php if ($registros->fields["IMPRESO"] == 0) { echo ("checked='checked'"); } ?> <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /> <?php echo ($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo (formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo ($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo ("No Asignado"); } ?></strong>.</p>
								</div>
							</div>

							<div class="form-actions">
								<?php if ($_GET['accion'] == "ver") { ?>
									<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
								<?php } else { ?>
									<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
									<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//	Renombrar Consola
			if (isset($_GET['accion']) and ($_GET['accion'] == "renombrarConsola")) {
				?>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=updateconsola"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Consola</label>
									<div class="controls">
										<input type="text" id="consola" name="consola" size="50" maxlength="255" title="Consola" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nueva Consola</label>
									<div class="controls">
										<input type="text" id="nuevaconsola" name="nuevaconsola" size="50" maxlength="255" title="Nueva Consola" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar Consola" onclick="return confirm('Se va ha cambiar el nombre de la Consola seleccionada.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Consola
			if (isset($_GET['accion']) and ($_GET['accion'] == "updateconsola")) {
				echo ("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE ventas SET consola = '" . addslashes($_POST["nuevaconsola"]) . "' WHERE (tipo_venta = '" . $_GET['menu'] . "') and (consola = '" . $_POST["consola"] . "') and (id_sucursal = '" . $_POST["sucursal"] . "')");

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			//	Renombrar Modelo
			if (isset($_GET['accion']) and ($_GET['accion'] == "renombrarModelo")) {
				?>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=updatemodelo"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Modelo</label>
									<div class="controls">
										<input type="text" id="modelo" name="modelo" size="50" maxlength="255" title="Modelo" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nuevo Modelo</label>
									<div class="controls">
										<input type="text" id="nuevomodelo" name="nuevomodelo" size="50" maxlength="255" title="Nuevo Modelo" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar Modelo" onclick="return confirm('Se va ha cambiar el nombre del Modelo seleccionado.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Modelo
			if (isset($_GET['accion']) and ($_GET['accion'] == "updatemodelo")) {
				echo ("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE ventas SET modelo = '" . addslashes($_POST["nuevomodelo"]) . "' WHERE (tipo_venta = '" . $_GET['menu'] . "') and (modelo = '" . $_POST["modelo"] . "') and (id_sucursal = '" . $_POST["sucursal"] . "')");

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			//	Cambiar estado
			if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
						if ($datos) {
							if ($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
							} else {
								if ($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE ventas SET activo = 0 WHERE (id_venta = '" . $valor . "')");
								} else {
									$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
							if ($datos) {
								echo ("{ \"estado\" : \"" . $estados[$datos["ACTIVO"]] . "\" }");
							} else {
								echo ("{ \"estado\" : \"error\" }");
							}
						}
					}
				}
			}

			//	Recuperar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "recuperar")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=updateregistros"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<h2>Recuperar registros eliminados</h2>

								<table id="listado" class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th style="width:15px;vertical-align: top;"><input type="checkbox" id="CheckTodo" name="CheckTodo"></th>
											<th style="width: 42px;">&nbsp;</th>
											<th>N&deg;</th>
											<th width="80">Fecha</th>
											<th>Cliente</th>
											<th>Consola</th>
											<th>Modelo</th>
											<th>Problema</th>
											<th>Estado</th>
											<th>F. Entrega</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
											<th>Pagado</th>
											<th width="120">F. Eliminado</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$query = "SELECT 
												COD_VENTA,
												ventas.FECHA_ALTA,
												NOMBRE_COMPLETO,
												CONSOLA,
												ventas.MODELO,
												DESCRIPCION,
												ESTADO,
												FECHA_ENTREGA,
												TOTAL,
												RECIBIDO,
												SALDO,
												PAGADO,
												FECHA_ELIMINADO,
												ID_VENTA 
											FROM ventas 
											LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente 
										";
										if (isset($_GET['texto'])) {
											$query .= "WHERE (tipo_venta = '" . $_GET['menu'] . "') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 1) and ((cod_venta LIKE '%" . $_GET['texto'] . "%') or (nro_factura LIKE '%" . $_GET['texto'] . "%') or (nombre_completo LIKE '%" . $_GET['texto'] . "%') or (estado LIKE '%" . $_GET['texto'] . "%')) ";
										} else {
											$query .= "WHERE (tipo_venta = '" . $_GET['menu'] . "') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 1) ";
										}
										$query .= "ORDER BY ventas.fecha_eliminado DESC,cod_venta DESC";

										$registros = $conexion->Query($query);
										if($registros){
											while (!$registros->EOF) {
												?>
												<tr>
													<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" /></td>
													<td>
														<div class="btn-toolbar">
															<div class="btn-group">
																<button type="button" id="btEliminarRegistroFisico" class="btn btn-mini btn-danger" title="Eliminar registro fiscamente"><i class="icon-remove"></i></button>
															</div>
														</div>
													</td>
													<td><?php echo ($registros->fields["COD_VENTA"]); ?></td>
													<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
													<td><?php echo ($registros->fields["NOMBRE_COMPLETO"]); ?></td>
													<td><?php echo ($registros->fields["CONSOLA"]); ?></td>
													<td><?php echo ($registros->fields["MODELO"]); ?></td>
													<td><?php echo ($registros->fields["DESCRIPCION"]); ?></td>
													<td><?php echo ($registros->fields["ESTADO"]); ?></td>
													<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ENTREGA"], 3)); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?></td>
													<td><?php echo ($estados[$registros->fields["PAGADO"]]); ?></td>
													<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ELIMINADO"], 6)); ?></td>
												</tr>
												<?php
												$registros->MoveNext();
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Recuperar registros" onclick="return confirm('Se va ha Recuperar todos los registros seleccionados.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "updateregistros")) {
				echo ("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");

				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						$conexion->Execute("UPDATE ventas SET eliminado = 0, fecha_eliminado = null WHERE (id_venta = '" . $valor . "') and (id_sucursal = '" . $_POST["sucursal"] . "')");
					}
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}
		}
		//	-----	Fin SERVICIOS TECNICOS

		//	-----	PEDIDOS
		if (isset($_GET['menu']) and ($_GET['menu'] == "pedidos")) {
			$url_modulo = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'];
			//if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
			if (!isset($_GET['anio'])) {
				$_GET['anio'] = "";
			}
			if (isset($_GET['anio']) and ($_GET['anio'] != "")) {
				$url_modulo .= "&anio=" . $_GET['anio'];
			}

			//	Listado
			if (!isset($_GET['accion'])) {
				$query = "SELECT 
						COD_VENTA,
						ventas.FECHA_ALTA,
						NOMBRE_COMPLETO,
						DESCRIPCION,
						ESTADO,
						(TOTAL * ".convertirTipoMoneda().") TOTAL, 
						(RECIBIDO * ".convertirTipoMoneda().") RECIBIDO, 
						(SALDO * ".convertirTipoMoneda().") SALDO, 
						ID_VENTA 
					FROM ventas
					LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
					WHERE 
						(tipo_venta = '" . $_GET['menu'] . "') 
						AND (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') 
						AND (eliminado = 0) 
				";
				if (isset($_GET['anio']) and ($_GET['anio'] != "")) {
					$query .= " AND (year(ventas.fecha_alta) = '" . $_GET['anio'] . "') ";
				}
				if (isset($_GET['texto'])) {
					$query .= " AND (
							(upper(cod_venta) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(nombre_completo) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(descripcion) LIKE upper('%" . $_GET['texto'] . "%')) or
							(upper(estado) LIKE upper('%" . $_GET['texto'] . "%'))
						) ";
				}
				$query .= "ORDER BY ventas.fecha_alta DESC,cod_venta DESC";

				showTable2(
					$conexion,
					$query,
					array(
						"COD_VENTA" => "N&deg;",
						"FECHA_ALTA" => "Fecha",
						"NOMBRE_COMPLETO" => "Cliente",
						"DESCRIPCION" => "Observaciones",
						"ESTADO" => "Estado",
						"TOTAL" => "Total",
						"RECIBIDO" => "Recibido",
						"SALDO" => "Saldo"
					),
					array(0 => "ID_VENTA"),
					$_SESSION['usuario_rxp'],
					true,
					true,
					$datos_acceso
				);
			}

			// Nuevo
			if (isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				if (isset($_GET['registro'])) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $_GET['registro'][0] . "')");
				}
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="tipo_venta" value="<?php echo ($_GET['menu']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<input type="hidden" name="id_cliente" id="id_cliente" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["ID_USUARIO"]); } ?>" />
												<input type="text" name="codigo" id="codigo" value="<?php if (isset($datosUsuario)) { echo (sprintf("%04s", $datosUsuario["ID_USUARIO"])); } ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" <?php if (!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled" <?php } ?> autocomplete="off" />
												<button type="button" id="btBuscarCliente" class="btn" title=""><i class="icon-search"></i> </button>
												<input type="text" id="cliente" name="cliente" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["NOMBRE_COMPLETO"]); } ?>" size="23" maxlength="255" title="Cliente" required style="width: 61%;" <?php if (!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled" <?php } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["DOMICILIO"]); } ?>" size="23" maxlength="255" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Tel&eacute;fonos</label>
													<div class="controls">
														<input type="text" id="telefonos" name="telefonos" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["TELEFONOS"]); } ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Categoria</label>
													<div class="controls">
														<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php if (isset($datosUsuario)) { echo ($datosUsuario["CATEGORIA_USUARIO"]); } ?>" size="23" maxlength="50" title="Categoria de Usuario" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
										</div>

									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">N&deg; de Pedido</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SELECT IF(COD_VENTA > 0, (max(COD_VENTA) + 1), 1) as COD_VENTA FROM ventas WHERE (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (tipo_venta = '" . $_GET['menu'] . "') ORDER BY cod_venta DESC");
												?>
												<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $datos["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Pedido" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo (date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo (date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipo de Cambio</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo ($_SESSION['usuario_tipocambio']); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Estado</label>
											<div class="controls">
												<select id="estado" name="estado" title="Estado" style="width: auto;" required>
													<option value="">Estado</option>
													<option value="Entregado">Entregado</option>
													<option value="Pendiente" selected>Pendiente</option>
													<option value="Cancelado">Cancelado</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
										<textarea id="descripcion" name="descripcion" rows="3" cols="60" title="Observaciones" style="width: 99%;" required></textarea>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button type="button" id="btnCalcularSubtotal" class="btn btn-primary"><i class="icon-check icon-white"></i> Calcular Montos</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>Importe</th>
											<th>
												<div class="btn-toolbar" style="display: inline;">
													<div class="btn-group">
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="0">IVA 0%</button>
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="13">IVA 13%</button>
														<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="16">IVA 16%</button>
													</div>
												</div>
											</th>
											<th>Subtotal</th>
											<th>Descuento</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="text-align: right;"><input type="text" id="importe" name="importe" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="iva" name="iva" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="subtotal" name="subtotal" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
											<td style="text-align: right;"><input type="text" id="descuento" name="descuento" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="total" name="total" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
											<td style="text-align: right;"><input type="text" id="recibido" name="recibido" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required /></td>
											<td style="text-align: right;"><input type="text" id="saldo" name="saldo" value="0,00" pattern="^(d|-)?(d|.)*,?d*$" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="7">
												<div id="msjSaldo" style="text-align: right;"></div>
											</td>
										</tr>
									</tfoot>
								</table>
								<hr />
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<select id="usuario" name="usuario" title="Administrador" required style="width: auto;">
													<option value=""></option>
													<?php
													for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
														$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
														if ($usuarios and ($usuarios->RecordCount() > 0)) {
													?>
															<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																<?php
																while (!$usuarios->EOF) {
																?>
																	<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																<?php
																	$usuarios->MoveNext();
																}
																?>
															</optgroup>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">&nbsp;</label>
											<div class="controls">
												&nbsp;
											</div>
										</div>

									</div>
								</div>

							</div>

						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			// Insertar
			if (isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if ($_POST['tipo_cambio'] == "") {
					$_POST['tipo_cambio'] = 0;
				}
				$vTipoCambio = str_replace(".", "", $_POST['tipo_cambio']);
				$vTipoCambio = str_replace(",", ".", $vTipoCambio);

				$vImporte = str_replace(".", "", $_POST['importe']);
				$vImporte = str_replace(",", ".", $vImporte);
				$vIva = str_replace(".", "", $_POST['iva']);
				$vIva = str_replace(",", ".", $vIva);
				$vSubtotal = str_replace(".", "", $_POST['subtotal']);
				$vSubtotal = str_replace(",", ".", $vSubtotal);
				$vDescuento = str_replace(".", "", $_POST['descuento']);
				$vDescuento = str_replace(",", ".", $vDescuento);
				$vTotal = str_replace(".", "", $_POST['total']);
				$vTotal = str_replace(",", ".", $vTotal);
				$vRecibido = str_replace(".", "", $_POST['recibido']);
				$vRecibido = str_replace(",", ".", $vRecibido);
				$vSaldo = str_replace(".", "", $_POST['saldo']);
				$vSaldo = str_replace(",", ".", $vSaldo);

				if (isset($_POST['id_venta'])) {
					//	Update
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE ventas
							SET
								id_sucursal ='" . $_POST['sucursal'] . "',
								id_cliente = '" . $_POST['id_cliente'] . "',
								id_usuario = '" . $_POST['usuario'] . "',
								cod_venta = '" . $_POST['cod_venta'] . "',
								fecha_alta = '" . $_POST['fecha_alta'] . "',
								hora_alta = '" . $_POST['hora_alta'] . ":00',
								tipo_cambio = '" . number_format($vTipoCambio, 4, '.', '') . "',
								importe = '" . number_format($vImporte, 2, '.', '') . "',
								iva = '" . number_format($vIva, 2, '.', '') . "',
								subtotal = '" . number_format($vSubtotal, 2, '.', '') . "',
								descuento = '" . number_format($vDescuento, 2, '.', '') . "',
								total = '" . number_format($vTotal, 2, '.', '') . "',
								recibido = '" . number_format($vRecibido, 2, '.', '') . "',
								saldo = '" . number_format($vSaldo, 2, '.', '') . "',
								descripcion = '" . addslashes($_POST['descripcion']) . "',
								estado = '" . $_POST['estado'] . "',
								id_actualizacion = '" . $_POST['id_actualizacion'] . "'
							WHERE (id_venta = '" . $_POST['id_venta'] . "')
					");
					$id_venta = $_POST['id_venta'];
				} else {
					//	Insert
					echo ("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO ventas
							(
								ID_VENTA,
								ID_SUCURSAL,
								ID_CLIENTE,
								ID_USUARIO,
								COD_VENTA,
								FECHA_ALTA,
								HORA_ALTA,
								TIPO_VENTA,
								TIPO_CAMBIO,
								IMPORTE,
								IVA,
								SUBTOTAL,
								DESCUENTO,
								TOTAL,
								RECIBIDO,
								SALDO,
								DESCRIPCION,
								ESTADO,
								ID_ACTUALIZACION
							) VALUES(
								null,
								'" . $_POST['sucursal'] . "',
								'" . $_POST['id_cliente'] . "',
								'" . $_POST['usuario'] . "',
								'" . $_POST['cod_venta'] . "',
								'" . $_POST['fecha_alta'] . "',
								'" . $_POST['hora_alta'] . ":00',
								'" . $_POST['tipo_venta'] . "',
								'" . number_format($vTipoCambio, 4, '.', '') . "',
								'" . number_format($vImporte, 2, '.', '') . "',
								'" . number_format($vIva, 2, '.', '') . "',
								'" . number_format($vSubtotal, 2, '.', '') . "',
								'" . number_format($vDescuento, 2, '.', '') . "',
								'" . number_format($vTotal, 2, '.', '') . "',
								'" . number_format($vRecibido, 2, '.', '') . "',
								'" . number_format($vSaldo, 2, '.', '') . "',
								'" . addslashes($_POST['descripcion']) . "',
								'" . $_POST['estado'] . "',
								'" . $_POST['id_actualizacion'] . "'
							)");
					$id_venta = $conexion->Insert_ID();
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}

			// Editar
			if (isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA FROM ventas WHERE (id_venta = '" . $id_modo_editar . "')");
				while (!$registros->EOF) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_CLIENTE"] . "')");
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '" . $registros->fields["ID_ACTUALIZACION"] . "')");
				?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form method="post" action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_venta" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" />
						<input type="hidden" name="tipo_venta" value="<?php echo ($registros->fields["TIPO_VENTA"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo ($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Cliente</label>
												<div class="controls">
													<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo ($registros->fields["ID_CLIENTE"]); ?>" />
													<input type="text" name="codigo" id="codigo" value="<?php echo (sprintf("%04s", $registros->fields["ID_CLIENTE"])); ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" autocomplete="off" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<button type="button" id="btBuscarCliente" class="btn" title="" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-search"></i> </button>
													<input type="text" id="cliente" name="cliente" value="<?php echo (stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?>" size="23" maxlength="50" title="Cliente" required style="width: 61%;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" value="<?php echo (stripslashes($datosUsuario["DOMICILIO"])); ?>" size="23" maxlength="50" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Tel&eacute;fonos</label>
														<div class="controls">
															<input type="text" id="telefonos" name="telefonos" value="<?php echo (stripslashes($datosUsuario["TELEFONOS"])); ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Categoria</label>
														<div class="controls">
															<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php echo (stripslashes($datosUsuario["DOMICILIO"])); ?>" size="23" maxlength="50" title="Categoria de Usuario" style="width: 99%;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">N&deg; de Pedido</label>
												<div class="controls">
													<input type="text" id="cod_venta" name="cod_venta" value="<?php echo (sprintf("%04s", $registros->fields["COD_VENTA"])); ?>" size="23" maxlength="50" title="N&deg; de Pedido" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha</label>
												<div class="controls">
													<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo ($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
													<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo ($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tipo de Cambio</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo (number_format($registros->fields["TIPO_CAMBIO"], 4, ',', '.')); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="^(d|-)?(d|.)*,?d*$" class="input-small" style="text-align: right;" placeholder="0,00" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Estado</label>
												<div class="controls">
													<select id="estado" name="estado" title="Estado" style="width: auto;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
														<option value="" selected>Estado</option>
														<option value="Entregado" <?php if ($registros->fields["ESTADO"] == "Entregado") { echo ("selected='selected'"); } ?>>Entregado</option>
														<option value="Pendiente" <?php if ($registros->fields["ESTADO"] == "Pendiente") { echo ("selected='selected'"); } ?>>Pendiente</option>
														<option value="Cancelado" <?php if ($registros->fields["ESTADO"] == "Cancelado") { echo ("selected='selected'"); } ?>>Cancelado</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="control-group">
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea id="descripcion" name="descripcion" rows="3" cols="60" title="Observaciones" style="width: 99%;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><?php echo (stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<hr />
									<div class="btn-toolbar">
										<div class="btn-group">
											<button type="button" id="btnCalcularSubtotal" class="btn btn-primary" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>><i class="icon-check icon-white"></i> Calcular Montos</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th>Importe</th>
												<th>
													<div class="btn-toolbar" style="display: inline;">
														<div class="btn-group">
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="0" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 0%</button>
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="13" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 13%</button>
															<button type="button" id="btIva" class="btn btn-mini" data-porcentaje="16" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>IVA 16%</button>
														</div>
													</div>
												</th>
												<th>Subtotal</th>
												<th>Descuento</th>
												<th>Total</th>
												<th>Recibido</th>
												<th>Saldo</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="text-align: right;"><input type="text" id="importe" name="importe" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IMPORTE"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Importe" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="iva" name="iva" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["IVA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="IVA" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="subtotal" name="subtotal" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SUBTOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Subtotal" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="descuento" name="descuento" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["DESCUENTO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Descuento" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="total" name="total" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="recibido" name="recibido" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Recibido" class="input-small" placeholder="0,00" style="text-align: right;" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" id="saldo" name="saldo" pattern="^(d|-)?(d|.)*,?d*$" value="<?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Saldo" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?> /></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="7">
													<div id="msjSaldo" style="text-align: right;<?php if ($registros->fields["SALDO"] > 0) { echo ("color: #ff0000;"); } else { echo ("color: #000000;"); } ?>"><?php if ($registros->fields["SALDO"] == 0) { echo ("Saldo"); } else { if ($registros->fields["SALDO"] > 0) { echo ("Saldo que adeuda el Cliente"); } else { echo ("Saldo a favor del Cliente"); } } ?></div>
												</td>
											</tr>
										</tfoot>
									</table>
									<hr />
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Usuario</label>
												<div class="controls">
													<select id="usuario" name="usuario" title="Administrador" required style="width: auto;" <?php if ($_GET['accion'] == "ver") { echo ("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
														for ($i = 0; $i < (count($niveles_acceso) - 3); $i++) {
															$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '" . $niveles_acceso[$i] . "') ORDER BY nombre_completo");
															if ($usuarios and ($usuarios->RecordCount() > 0)) {
														?>
																<optgroup label="<?php echo ($niveles_acceso[$i]); ?>">
																	<?php
																	while (!$usuarios->EOF) {
																	?>
																		<option value="<?php echo ($usuarios->fields["ID_USUARIO"]); ?>" <?php if ($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIO"]) { echo ("selected='selected'"); } ?>><?php echo ($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
																	<?php
																		$usuarios->MoveNext();
																	}
																	?>
																</optgroup>
														<?php
															}
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span6">
											<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo (formato_fecha($registros->fields["FECHA_ACTUALIZACION"], 6)); ?></strong> por <strong><?php if ($datosUsuarioActualizacion) { echo ($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo ("No Asignado"); } ?></strong>.</p>
										</div>
									</div>

								</div>

							</div>

							<div class="form-actions">
								<?php if ($_GET['accion'] == "ver") { ?>
									<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
								<?php } else { ?>
									<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
									<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
				<?php
					$registros->MoveNext();
				}
			}

			//	Cambiar estado
			if (isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
						if ($datos) {
							if ($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
							} else {
								if ($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE ventas SET activo = 0 WHERE (id_venta = '" . $valor . "')");
								} else {
									$conexion->Query("UPDATE ventas SET activo = 1 WHERE (id_venta = '" . $valor . "')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM ventas WHERE (id_venta = '" . $valor . "')");
							if ($datos) {
								echo ("{ \"estado\" : \"" . $estados[$datos["ACTIVO"]] . "\" }");
							} else {
								echo ("{ \"estado\" : \"error\" }");
							}
						}
					}
				}
			}

			//	Recuperar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "recuperar")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=updateregistros"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo ($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<h2>Recuperar registros eliminados</h2>

								<table id="listado" class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th style="width:15px;vertical-align: top;"><input type="checkbox" id="CheckTodo" name="CheckTodo"></th>
											<th style="width: 42px;">&nbsp;</th>
											<th>N&deg;</th>
											<th width="80">Fecha</th>
											<th>Cliente</th>
											<th>Observaciones</th>
											<th>Estado</th>
											<th>Total</th>
											<th>Recibido</th>
											<th>Saldo</th>
											<th width="120">F. Eliminado</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$query = "SELECT COD_VENTA,ventas.FECHA_ALTA,NOMBRE_COMPLETO,DESCRIPCION,ESTADO,TOTAL,RECIBIDO,SALDO,FECHA_ELIMINADO,ID_VENTA FROM ventas LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente ";
										if (isset($_GET['texto'])) {
											$query .= "WHERE (tipo_venta = '" . $_GET['menu'] . "') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 1) and ((cod_venta LIKE '%" . $_GET['texto'] . "%') or (nro_factura LIKE '%" . $_GET['texto'] . "%') or (nombre_completo LIKE '%" . $_GET['texto'] . "%') or (estado LIKE '%" . $_GET['texto'] . "%')) ";
										} else {
											$query .= "WHERE (tipo_venta = '" . $_GET['menu'] . "') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 1) ";
										}
										$query .= "ORDER BY ventas.fecha_eliminado DESC,cod_venta DESC";

										$registros = $conexion->Query($query);
										if($registros){
											while (!$registros->EOF) {
												?>
												<tr>
													<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo ($registros->fields["ID_VENTA"]); ?>" /></td>
													<td>
														<div class="btn-toolbar">
															<div class="btn-group">
																<button type="button" id="btEliminarRegistroFisico" class="btn btn-mini btn-danger" title="Eliminar registro fiscamente"><i class="icon-remove"></i></button>
															</div>
														</div>
													</td>
													<td><?php echo ($registros->fields["COD_VENTA"]); ?></td>
													<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
													<td><?php echo ($registros->fields["NOMBRE_COMPLETO"]); ?></td>
													<td><?php echo ($registros->fields["DESCRIPCION"]); ?></td>
													<td><?php echo ($registros->fields["ESTADO"]); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL"], 2, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO"], 2, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO"], 2, ',', '.')); ?></td>
													<td style="text-align: center;"><?php echo (formato_fecha($registros->fields["FECHA_ELIMINADO"], 6)); ?></td>
												</tr>
												<?php
												$registros->MoveNext();
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Recuperar registros" onclick="return confirm('Se va ha Recuperar todos los registros seleccionados.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar registros
			if (isset($_GET['accion']) and ($_GET['accion'] == "updateregistros")) {
				echo ("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");

				if (isset($_POST['registro'])) {
					foreach ($_POST['registro'] as $valor) {
						$conexion->Execute("UPDATE ventas SET eliminado = 0, fecha_eliminado = null WHERE (id_venta = '" . $valor . "') and (id_sucursal = '" . $_POST["sucursal"] . "')");
					}
				}

				echo ("<script type='text/javascript'>");
				echo ("window.location.href='" . $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "';");
				echo ("</script>");
			}
		}
		//	-----	Fin PEDIDOS

		include("../footer.php");
		?>
	</body>
</html>