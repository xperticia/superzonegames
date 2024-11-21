<?php
	require("../config.php");
	require("../verifica.php");
	$nivel_acceso = 5;// Nivel de acceso para esta página.
	if($nivel_acceso < $_SESSION['usuario_nivel']) { 
	   header("Location: ./?error_login=5");
	   exit;
	}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo(armarHead()); ?></title>
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

		//	-----	TRASPASOS
		if(isset($_GET['menu']) && ($_GET['menu'] == "traspasos")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = ""; }
			if(isset($_GET['anio']) && ($_GET['anio'] != "")){
				$url_modulo .= "&anio=".$_GET['anio'];
			}

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						ID_TRASPASO,
						traspasos.FECHA_ALTA,
						SO.NOMBRE as SUCURSALORIGEN,
						UO.NOMBRE_COMPLETO as USUARIOORIGEN,
						SD.NOMBRE as SUCURSALDESTINO,
						UD.NOMBRE_COMPLETO as USUARIODESTINO
					FROM traspasos 
					LEFT JOIN sucursales as SO ON SO.ID_SUCURSAL = traspasos.ID_SUCURSALORIGEN 
					LEFT JOIN sucursales as SD ON SD.ID_SUCURSAL = traspasos.ID_SUCURSALDESTINO 
					LEFT JOIN usuarios UO ON UO.id_usuario = traspasos.id_usuarioorigen 
					LEFT JOIN usuarios UD ON UD.id_usuario = traspasos.id_usuariodestino 
					WHERE 
						(id_sucursalorigen = '".$_SESSION['usuario_sucursal']."') 
				";
				if(isset($_GET['anio']) and ($_GET['anio'] != "")){
					$query .= " AND (year(traspasos.fecha_alta) = '".$_GET['anio']."') ";
				}
				if(isset($_GET['texto'])) {
					$query .= " AND ( 
						(id_traspaso LIKE '%".$_GET['texto']."%') or 
						(traspasos.fecha_alta LIKE '%".$_GET['texto']."%') or 
						(upper(SO.nombre) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(UO.nombre_completo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(SD.nombre) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(UD.nombre_completo) LIKE upper('%".$_GET['texto']."%')) 
					) ";
				}
				$query .= "ORDER BY traspasos.fecha_alta DESC";

				showTable2(
					$conexion,
					$query,
					array(
						"ID_TRASPASO" => "N&deg;",
						"FECHA_ALTA" => "Fecha",
						"SUCURSALORIGEN" => "Sucursal Origen",
						"USUARIOORIGEN" => "Usuario Origen",
						"SUCURSALDESTINO" => "Sucursal Destino",
						"USUARIODESTINO" => "Usuario Destino"
					),
					array(0 => "ID_TRASPASO"),
					$_SESSION['usuario_rxp'],
					true,
					true,
					$datos_acceso,
					null,
					null
				);
			}

			// Nuevo
			if(isset($_GET['accion']) && ($_GET['accion'] == "nuevo")) {
				$datosNuevoRegistro = $conexion->GetRow("SELECT IF(ID_TRASPASO > 0, (max(ID_TRASPASO) + 1), 1) as ID_TRASPASO FROM traspasos WHERE (id_sucursalorigen = '".$_SESSION['usuario_sucursal']."') ORDER BY id_traspaso DESC");
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />
					<input type="hidden" name="activo" value="1" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos del Traspaso</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">N&deg; de Traspaso</label>
											<div class="controls">
												<input type="text" id="traspaso" name="traspaso" value="<?php echo(sprintf("%04s",$datosNuevoRegistro["ID_TRASPASO"])); ?>" size="23" maxlength="50" title="N&deg; de Traspaso" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo(date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Sucursal Origen</label>
											<div class="controls">
												<input type="hidden" name="sucursalorigen" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
												<?php
													$datosSucursal = $temp->GetRow("SELECT NOMBRE FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
												?>
												<input type="text" name="nombresucursalorigen" value="<?php if($datosSucursal){ echo($datosSucursal['NOMBRE']); } ?>" title="Sucursal Origen" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Usuario Origen</label>
											<div class="controls">
												<input type="hidden" name="usuarioorigen" value="<?php echo($_SESSION['usuario_id']); ?>" />
												<input type="text" name="nombreusuarioorigen" value="<?php echo($_SESSION['usuario_nombre']); ?>" title="Usuario Origen" readonly="readonly" />
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Sucursal Destino</label>
											<div class="controls">
												<select name="sucursaldestino" title="Sucursal Destino" required style="width: auto;" data-evento="change" data-metodo="getData_SucursalDestino">
													<option value="">Sucursal Destino</option>
													<?php
														$sucursales = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
														if($sucursales and ($sucursales->RecordCount() > 0)){
															while(!$sucursales->EOF) {
																?>
																<option value="<?php echo($sucursales->fields["ID_SUCURSAL"]); ?>" <?php if($sucursales->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal']) { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($sucursales->fields["NOMBRE"])); ?></option>
																<?php
																$sucursales->MoveNext();
															}
														}
													?>									
												</select>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Usuario Destino</label>
											<div class="controls">
												<select id="usuariodestino" name="usuariodestino" title="Usuario Destino" required style="width: auto;">
													<option value="">Usuario Destino</option>
													<?php
														for($i=0; $i<(count($niveles_acceso)-3); $i++) {
															$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
															if($usuarios and ($usuarios->RecordCount() > 0)){
																?>
													<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
																<?php
																while(!$usuarios->EOF) {
																	?>
														<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo("disabled='disabled'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
								</div>

								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%;"></textarea>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button type="button" id="btNuevoDetalleTraspaso" class="btn btn-primary" disabled="disabled" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="" data-evento="click" data-metodo="showModal_DetalleTraspaso">Nuevo</button>
										<button type="button" id="btnEliminarDetalle" class="btn btn-danger" disabled="disabled" data-target="#listaDetalles" data-item="input[name='detalle']" data-user="<?php echo(!empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1); ?>">Eliminar</button>
									</div>
									<div class="btn-group" style="display: none;">
										<button id="btnCalcularSubtotal" class="btn btn-primary">Calcular Montos</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed" id="listaDetalles">
									<thead>
										<tr>
											<th width="20"></th>
											<th>Producto de Origen</th>
											<th>Producto de Destino</th>
											<th width="100">Cantidad en Origen</th>
											<th width="100">Cantidad en Destino</th>
											<th width="100">Cantidad a traspasar</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>

				<div id="modalDetalle" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form style="margin: 0;">
						<div class="modal-header">
							<a href="#" class="close" data-dismiss="modal">&times;</a>
							<h3 style="border-bottom: none;">Modal Heading</h3>
						</div>
						<div class="modal-body">
							<h5 class="tituloProductoOrigen">Producto de Origen</h5>
							<div class="row-fluid">
								<div class="span4" style="text-align: left;">
									<strong>Ingrese el texto a buscar: </strong><br />
									<input type="text" name="q" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="codigo, nombre o codigo de barra" style="width: 99%;" autocomplete="off" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle" />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Categoría: </strong><br />
									<select name="listaCategoria" title="Categoría" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle">
										<option value="">Categoría</option>
										<?php
											$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
											while(!$categorias->EOF) {
												?>
												<option value="<?php echo($categorias->fields["CATEGORIA"]); ?>"><?php echo($categorias->fields["CATEGORIA"]); ?></option>
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
									?>
									<select name="listaGenero" title="G&eacute;nero" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle">
										<option value="">Género</option>
										<?php
											foreach($listaGeneros as $key=>$val) {
												?>
												<option value="<?php echo($val); ?>"><?php echo($val); ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="span2" style="text-align: center;">
									<br />
									<button type="button" id="btnModalBuscarOrigen" class="btn btn-primary btn-block" data-target="#modalDetalle" title="Buscar" data-evento="click" data-metodo="getData_ModalBuscarOrigen">buscar</button>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span10">
									<div class="control-group">
										<label class="control-label"><strong>Producto</strong></label>
										<div class="controls">
											<select name="productoorigen" title="Producto de Origen" required style="width: 99%;" data-evento="change" data-metodo="getData_ProductoSeleccionado" data-target="#modalDetalle">
												<option value="">Productos (0)</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label class="control-label"><strong>Cantidad</strong></label>
										<div class="controls">
											<input type="text" name="detalleCantidadOrigen" min="1" title="Cantidad de Origen" class="input-small" placeholder="0" style="text-align: right;" required readonly />
										</div>
									</div>
								</div>
							</div>

							<hr />

							<h5 class="tituloProductoDestino">Producto de Destino</h5>
							<div class="row-fluid">
								<div class="span4" style="text-align: left;">
									<strong>Ingrese el texto a buscar: </strong><br />
									<input type="text" name="qDestino" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="codigo, nombre o codigo de barra" style="width: 99%;" autocomplete="off" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle" />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Categoría: </strong><br />
									<select name="listaCategoriaDestino" title="Categoría" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle">
										<option value="">Categoría</option>
									</select>
								</div>
								<div class="span3" style="text-align: left;">
									<strong>G&eacute;nero: </strong><br />
									<select name="listaGeneroDestino" title="G&eacute;nero" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle">
										<option value="">Género</option>
									</select>
								</div>
								<div class="span2" style="text-align: center;">
									<br />
									<button type="button" id="btnModalBuscarDestino" class="btn btn-primary btn-block" data-target="#modalDetalle" title="Buscar" data-evento="click" data-metodo="getData_ModalBuscarDestino">buscar</button>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span10">
									<div class="control-group">
										<label class="control-label"><strong>Producto</strong></label>
										<div class="controls">
											<select name="productodestino" title="Producto de Destino" required style="width: 99%;" data-evento="change" data-metodo="getData_ProductoSeleccionado" data-target="#modalDetalle">
												<option value="">Productos (0)</option>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label class="control-label"><strong>Cantidad</strong></label>
										<div class="controls">
											<input type="text" name="detalleCantidadDestino" title="Cantidad de Destino" class="input-small" placeholder="0" style="text-align: right;" readonly />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="row-fluid">
								<div class="span9" style="text-align: left;">
									<strong>Cantidad a traspasar: </strong>
									<input type="text" name="detalleCantidad" min="1" max="" title="Cantidad a traspasar" class="input-small moneda" data-decimales="0" placeholder="0" style="text-align: center;" required autocomplete="off" />
								</div>
								<div class="span3" style="text-align: right;">
									<button type="button" class="btn btn-primary" data-target="#modalDetalle" id="btnModalGuardarDetalleTraspaso">Aceptar</button>
									<button type="button" class="btn" id="btnModalCancelar" data-dismiss="modal">Cancelar</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php
			}

			// Insertar
			if(isset($_GET['accion']) && ($_GET['accion'] == "insert")) {

				$_POST['usuarioorigen'] = empty($_POST['usuarioorigen']) ? 1 : $_POST['usuarioorigen'];
				$_POST['id_actualizacion'] = empty($_POST['id_actualizacion']) ? 1 : $_POST['id_actualizacion'];

				if(isset($_POST['id_traspaso'])) {
					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$query = "UPDATE traspasos 
						SET 
							id_sucursalorigen = '{$_POST['sucursalorigen']}', 
							id_usuarioorigen = '{$_POST['usuarioorigen']}',
							id_sucursaldestino = '{$_POST['sucursaldestino']}', 
							id_usuariodestino = '{$_POST['usuariodestino']}', 
							fecha_alta = '{$_POST['fecha_alta']}', 
							hora_alta = '{$_POST['hora_alta']}:00', 
							observaciones = '{$_POST['descripcion']}', 
							id_actualizacion = '{$_POST['id_actualizacion']}', 
							activo = '{$_POST['activo']}' 
						WHERE 
							(id_traspaso = '{$_POST['id_traspaso']}')
					";
					$conexion->Query($query);
					$id_traspaso = $_POST['id_traspaso'];
					
					//$conexion->Execute("DELETE FROM traspaso_posee_productos WHERE (id_traspaso = '{$_POST['id_traspaso']}')");

					if(isset($_POST['id_detalleorigen'])) {
						$fechaHora = date('Y-m-d H:i:s');
						foreach($_POST['id_detalleorigen'] as $key=>$value) {
							// verifico si esta registro el detalle
							$query = "SELECT *  
								FROM traspaso_posee_productos
								WHERE
									(id_traspaso = '{$id_traspaso}') 
									and (id_productoorigen = '{$_POST['id_detalleorigen'][$key]}') 
									and (id_productodestino = '{$_POST['id_detalledestino'][$key]}') 
							";
							$datosVerificados =  $conexion->GetRow($query);

							if(!$datosVerificados){
								$query = "INSERT INTO traspaso_posee_productos 
									SET
										id_traspaso = '{$id_traspaso}',
										id_productoorigen = '{$_POST['id_detalleorigen'][$key]}',
										id_productodestino = '{$_POST['id_detalledestino'][$key]}',
										cantidad_origen = '{$_POST['detalleCantidadOrigen'][$key]}',
										cantidad_destino = '{$_POST['detalleCantidadDestino'][$key]}',
										cantidad = '{$_POST['detalleCantidad'][$key]}'
								";
								$conexion->Query($query);
								$id_itemtraspaso = $conexion->Insert_ID();

								if(!empty($id_itemtraspaso)){
									// registro la disiminucion del movimiento de stock
									$stockFinal = intval($_POST['detalleCantidadOrigen'][$key]) - intval($_POST['detalleCantidad'][$key]);
									$query = "INSERT INTO movimientos_stock 
										SET
											ID_USUARIO = '{$_POST['id_actualizacion']}',
											ID_PRODUCTO = '{$_POST['id_detalleorigen'][$key]}',
											ID_SUCURSAL = '{$_POST['sucursalorigen']}',
											FECHA_REGISTRO = '{$fechaHora}',
											STOCK_ACTUAL = '{$_POST['detalleCantidadOrigen'][$key]}',
											CANTIDAD = '{$_POST['detalleCantidad'][$key]}',
											STOCK_FINAL = '{$stockFinal}',
											TIPO_MOVIMIENTOSTOCK = 'Disminución x Traspaso'
									";
									$conexion->Query($query);
									$id_movimientostockOrigen = $conexion->Insert_ID();

									if(!empty($id_movimientostockOrigen)){
										$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$_POST['id_detalleorigen'][$key]}')";
										$conexion->Query($query);
									}

									// registro el aumento del movimiento de stock
									$stockFinal = intval($_POST['detalleCantidadDestino'][$key]) + intval($_POST['detalleCantidad'][$key]);
									$query = "INSERT INTO movimientos_stock 
										SET
											ID_USUARIO = '{$_POST['id_actualizacion']}',
											ID_PRODUCTO = '{$_POST['id_detalledestino'][$key]}',
											ID_SUCURSAL = '{$_POST['sucursaldestino']}',
											FECHA_REGISTRO = '{$fechaHora}',
											STOCK_ACTUAL = '{$_POST['detalleCantidadDestino'][$key]}',
											CANTIDAD = '{$_POST['detalleCantidad'][$key]}',
											STOCK_FINAL = '{$stockFinal}',
											TIPO_MOVIMIENTOSTOCK = 'Aumento x Traspaso'
									";
									$conexion->Query($query);
									$id_movimientostockDestino = $conexion->Insert_ID();

									if(!empty($id_movimientostockDestino)){
										$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$_POST['id_detalledestino'][$key]}')";
										$conexion->Query($query);
									}

								}

							}

						}
					}

				} else {
					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$query = "INSERT INTO traspasos 
						SET
							id_sucursalorigen = '{$_POST['sucursalorigen']}',
							id_usuarioorigen = '{$_POST['usuarioorigen']}',
							id_sucursaldestino = '{$_POST['sucursaldestino']}',
							id_usuariodestino = '{$_POST['usuariodestino']}',
							fecha_alta = '{$_POST['fecha_alta']}',
							hora_alta = '{$_POST['hora_alta']}:00',
							observaciones = '{$_POST['descripcion']}',
							id_actualizacion = '{$_POST['id_actualizacion']}',
							activo = '{$_POST['activo']}'
					";
					$conexion->Query($query);
					$id_traspaso = $conexion->Insert_ID();
					if(!empty($id_traspaso) && isset($_POST['id_detalleorigen'])){
						$fechaHora = date('Y-m-d H:i:s');
						foreach ($_POST['id_detalleorigen'] as $key => $value) {
							$query = "INSERT INTO traspaso_posee_productos 
								SET
									id_traspaso = '{$id_traspaso}',
									id_productoorigen = '{$_POST['id_detalleorigen'][$key]}',
									id_productodestino = '{$_POST['id_detalledestino'][$key]}',
									cantidad_origen = '{$_POST['detalleCantidadOrigen'][$key]}',
									cantidad_destino = '{$_POST['detalleCantidadDestino'][$key]}',
									cantidad = '{$_POST['detalleCantidad'][$key]}'
							";
							$conexion->Query($query);
							$id_itemtraspaso = $conexion->Insert_ID();

							if(!empty($id_itemtraspaso)){
								// registro la disiminucion del movimiento de stock
								$stockFinal = intval($_POST['detalleCantidadOrigen'][$key]) - intval($_POST['detalleCantidad'][$key]);
								$query = "INSERT INTO movimientos_stock 
									SET
										ID_USUARIO = '{$_POST['usuarioorigen']}',
										ID_PRODUCTO = '{$_POST['id_detalleorigen'][$key]}',
										ID_SUCURSAL = '{$_POST['sucursalorigen']}',
										FECHA_REGISTRO = '{$fechaHora}',
										STOCK_ACTUAL = '{$_POST['detalleCantidadOrigen'][$key]}',
										CANTIDAD = '{$_POST['detalleCantidad'][$key]}',
										STOCK_FINAL = '{$stockFinal}',
										TIPO_MOVIMIENTOSTOCK = 'Disminución x Traspaso'
								";
								$conexion->Query($query);
								$id_movimientostockOrigen = $conexion->Insert_ID();

								if(!empty($id_movimientostockOrigen)){
									$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$_POST['id_detalleorigen'][$key]}')";
									$conexion->Query($query);
								}

								// registro el aumento del movimiento de stock
								$stockFinal = intval($_POST['detalleCantidadDestino'][$key]) + intval($_POST['detalleCantidad'][$key]);
								$query = "INSERT INTO movimientos_stock 
									SET
										ID_USUARIO = '{$_POST['usuariodestino']}',
										ID_PRODUCTO = '{$_POST['id_detalledestino'][$key]}',
										ID_SUCURSAL = '{$_POST['sucursaldestino']}',
										FECHA_REGISTRO = '{$fechaHora}',
										STOCK_ACTUAL = '{$_POST['detalleCantidadDestino'][$key]}',
										CANTIDAD = '{$_POST['detalleCantidad'][$key]}',
										STOCK_FINAL = '{$stockFinal}',
										TIPO_MOVIMIENTOSTOCK = 'Aumento x Traspaso'
								";
								$conexion->Query($query);
								$id_movimientostockDestino = $conexion->Insert_ID();

								if(!empty($id_movimientostockDestino)){
									$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$_POST['id_detalledestino'][$key]}')";
									$conexion->Query($query);
								}

							}
						}
					}
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			// Editar
			if(isset($_GET['accion']) && (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA FROM traspasos WHERE (id_traspaso = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_traspaso" value="<?php echo($registros->fields["ID_TRASPASO"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />
						<input type="hidden" name="activo" value="<?php echo($registros->fields["ACTIVO"]); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos del Traspaso</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">N&deg; de Traspaso</label>
												<div class="controls">
													<input type="text" id="traspaso" name="traspaso" value="<?php echo(sprintf("%04s",$registros->fields["ID_TRASPASO"])); ?>" size="23" maxlength="50" title="N&deg; de Traspaso" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Fecha</label>
												<div class="controls">
													<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
												</div>
											</div>
										</div>
									</div>

									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Sucursal Origen</label>
												<div class="controls">
													<select name="sucursalorigen" title="Sucursal Origen" required style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															$sucursales = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
															if($sucursales and ($sucursales->RecordCount() > 0)){
																while(!$sucursales->EOF) {
																	?>
																	<option value="<?php echo($sucursales->fields["ID_SUCURSAL"]); ?>" <?php if($sucursales->fields["ID_SUCURSAL"] == $registros->fields["ID_SUCURSALORIGEN"]) { echo("selected='selected'"); } ?>><?php echo(stripslashes($sucursales->fields["NOMBRE"])); ?></option>
																	<?php
																	$sucursales->MoveNext();
																}
															}
														?>									
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Usuario Origen</label>
												<div class="controls">
													<select name="usuarioorigen" title="Usuario Origen" required style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															for($i=0; $i<(count($niveles_acceso)-3); $i++) {
																$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
																if($usuarios and ($usuarios->RecordCount() > 0)){
																	?>
														<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
																	<?php
																	while(!$usuarios->EOF) {
																		?>
															<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIOORIGEN"]) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
												<label class="control-label">Sucursal Destino</label>
												<div class="controls">
													<select name="sucursaldestino" title="Sucursal Destino" required style="width: auto;" data-evento="change" data-metodo="getData_SucursalDestino" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															$sucursales = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
															if($sucursales and ($sucursales->RecordCount() > 0)){
																while(!$sucursales->EOF) {
																	?>
																	<option value="<?php echo($sucursales->fields["ID_SUCURSAL"]); ?>" <?php if($sucursales->fields["ID_SUCURSAL"] == $registros->fields["ID_SUCURSALORIGEN"]) { echo("disabled='disabled'"); } ?> <?php if($sucursales->fields["ID_SUCURSAL"] == $registros->fields["ID_SUCURSALDESTINO"]) { echo("selected='selected'"); } ?>><?php echo(stripslashes($sucursales->fields["NOMBRE"])); ?></option>
																	<?php
																	$sucursales->MoveNext();
																}
															}
														?>									
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Usuario Destino</label>
												<div class="controls">
													<select name="usuariodestino" title="Usuario Destino" required style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															for($i=0; $i<(count($niveles_acceso)-3); $i++) {
																$usuarios = $temp->Query("SELECT * FROM usuarios WHERE (tipo_usuario = '".$niveles_acceso[$i]."') ORDER BY nombre_completo");
																if($usuarios and ($usuarios->RecordCount() > 0)){
																	?>
														<optgroup label="<?php echo($niveles_acceso[$i]); ?>">
																	<?php
																	while(!$usuarios->EOF) {
																		?>
															<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>"<?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIOORIGEN"]) { echo("disabled='disabled'"); } ?> <?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIODESTINO"]) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
									</div>

									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["OBSERVACIONES"])); ?></textarea>
										</div>
									</div>
									<hr />
									<div class="btn-toolbar">
										<div class="btn-group">
											<button type="button" id="btNuevoDetalleTraspaso" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="<?php echo($registros->fields["ID_USUARIOORIGEN"]); ?>" data-evento="click" data-metodo="showModal_DetalleTraspaso" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Nuevo</button>
											<button type="button" id="btnEliminarDetalle" class="btn btn-danger" disabled="disabled" data-target="#listaDetalles" data-item="input[name='detalle']" data-user="<?php echo(!empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Eliminar</button>
										</div>
										<div class="btn-group" style="display: none;">
											<button id="btnCalcularSubtotal" class="btn btn-primary" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Calcular Montos</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th width="20"></th>
												<th>Producto de Origen</th>
												<th>Producto de Destino</th>
												<th width="100">Cantidad en Origen</th>
												<th width="100">Cantidad en Destino</th>
												<th width="100">Cantidad a traspasar</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$query = "SELECT 
														traspaso_posee_productos.*,
														concat(
															'[',
															PO.codigo,
															'] ',
															PO.nombre
														) PRODUCTOORIGEN,
														concat(
															'[',
															PD.codigo,
															'] ',
															PD.nombre
														) PRODUCTODESTINO
													FROM traspaso_posee_productos 
													LEFT JOIN productos PO ON PO.id_producto = traspaso_posee_productos.id_productoorigen 
													LEFT JOIN productos PD ON PD.id_producto = traspaso_posee_productos.id_productodestino 
													WHERE 
														(id_traspaso = '{$registros->fields["ID_TRASPASO"]}') 
													ORDER BY PO.nombre
												";
												$detalles = $temp->Query($query);
												if($detalles) {		
													while(!$detalles->EOF) {
														?>
											<tr class="unChecked">
												<td>
													<input type="checkbox" name="detalle" value="<?php echo($detalles->fields['ID_ITEMTRASPASO']); ?>" data-evento="click" data-metodo="checkClickRow" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="hidden" name="id_detalleorigen[]" value="<?php echo($detalles->fields["ID_PRODUCTOORIGEN"]); ?>" />
													<input type="hidden" name="id_detalledestino[]" value="<?php echo($detalles->fields["ID_PRODUCTODESTINO"]); ?>" />
												</td>
												<td><?php echo(stripslashes($detalles->fields["PRODUCTOORIGEN"])); ?></td>
												<td><?php echo(stripslashes($detalles->fields["PRODUCTODESTINO"])); ?></td>
												<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD_ORIGEN"])); ?><input type="hidden" name="detalleCantidadOrigen[]" value="<?php echo(stripslashes($detalles->fields["CANTIDAD_ORIGEN"])); ?>" /></td>
												<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD_DESTINO"])); ?><input type="hidden" name="detalleCantidadDestino[]" value="<?php echo(stripslashes($detalles->fields["CANTIDAD_DESTINO"])); ?>" /></td>
												<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?><input type="hidden" name="detalleCantidad[]" value="<?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?>" /></td>
											</tr>
														<?php
														$detalles->MoveNext();
													}
												}
											?>
										</tbody>
									</table>
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>
								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>

					<div id="modalDetalle" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form style="margin: 0;">
							<div class="modal-header">
								<a href="#" class="close" data-dismiss="modal">&times;</a>
								<h3>Modal Heading</h3>
							</div>
							<div class="modal-body">
								<h5 class="tituloProductoOrigen">Producto de Origen</h5>
								<div class="row-fluid">
									<div class="span4" style="text-align: left;">
										<strong>Ingrese el texto a buscar: </strong><br />
										<input type="text" name="q" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="codigo, nombre o codigo de barra" style="width: 99%;" autocomplete="off" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle" />
									</div>
									<div class="span3" style="text-align: left;">
										<strong>Categoría: </strong><br />
										<select name="listaCategoria" title="Categoría" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle">
											<option value="">Categoría</option>
											<?php
												$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
												while(!$categorias->EOF) {
													?>
													<option value="<?php echo($categorias->fields["CATEGORIA"]); ?>"><?php echo($categorias->fields["CATEGORIA"]); ?></option>
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
										?>
										<select name="listaGenero" title="G&eacute;nero" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarOrigen" data-target="#modalDetalle">
											<option value="">Género</option>
											<?php
												foreach($listaGeneros as $key=>$val) {
													?>
													<option value="<?php echo($val); ?>"><?php echo($val); ?></option>
													<?php
												}
											?>
										</select>
									</div>
									<div class="span2" style="text-align: center;">
										<br />
										<button type="button" id="btnModalBuscarOrigen" class="btn btn-primary btn-block" data-target="#modalDetalle" title="Buscar" data-evento="click" data-metodo="getData_ModalBuscarOrigen">buscar</button>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span10">
										<div class="control-group">
											<label class="control-label"><strong>Producto</strong></label>
											<div class="controls">
												<select name="productoorigen" title="Producto de Origen" required style="width: 99%;" data-evento="change" data-metodo="getData_ProductoSeleccionado" data-target="#modalDetalle">
													<option value="">Productos (0)</option>
												</select>
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label class="control-label"><strong>Cantidad</strong></label>
											<div class="controls">
												<input type="text" name="detalleCantidadOrigen" min="1" title="Cantidad de Origen" class="input-small" placeholder="0" style="text-align: right;" required readonly />
											</div>
										</div>
									</div>
								</div>

								<hr />

								<h5 class="tituloProductoDestino">Producto de Destino</h5>
								<div class="row-fluid">
									<div class="span4" style="text-align: left;">
										<strong>Ingrese el texto a buscar: </strong><br />
										<input type="text" name="qDestino" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="codigo, nombre o codigo de barra" style="width: 99%;" autocomplete="off" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle" />
									</div>
									<div class="span3" style="text-align: left;">
										<strong>Categoría: </strong><br />
										<select name="listaCategoriaDestino" title="Categoría" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle">
											<option value="">Categoría</option>
										</select>
									</div>
									<div class="span3" style="text-align: left;">
										<strong>G&eacute;nero: </strong><br />
										<select name="listaGeneroDestino" title="G&eacute;nero" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarDestino" data-target="#modalDetalle">
											<option value="">Género</option>
										</select>
									</div>
									<div class="span2" style="text-align: center;">
										<br />
										<button type="button" id="btnModalBuscarDestino" class="btn btn-primary btn-block" data-target="#modalDetalle" title="Buscar" data-evento="click" data-metodo="getData_ModalBuscarDestino">buscar</button>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span10">
										<div class="control-group">
											<label class="control-label"><strong>Producto</strong></label>
											<div class="controls">
												<select name="productodestino" title="Producto de Destino" required style="width: 99%;" data-evento="change" data-metodo="getData_ProductoSeleccionado" data-target="#modalDetalle">
													<option value="">Productos (0)</option>
												</select>
											</div>
										</div>
									</div>
									<div class="span2">
										<div class="control-group">
											<label class="control-label"><strong>Cantidad</strong></label>
											<div class="controls">
												<input type="text" name="detalleCantidadDestino" title="Cantidad de Destino" class="input-small" placeholder="0" style="text-align: right;" readonly />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="row-fluid">
									<div class="span9" style="text-align: left;">
										<strong>Cantidad a traspasar: </strong>
										<input type="text" name="detalleCantidad" min="1" max="" title="Cantidad a traspasar" class="input-small moneda" data-decimales="0" placeholder="0" style="text-align: center;" required autocomplete="off" />
									</div>
									<div class="span3" style="text-align: right;">
										<button type="button" class="btn btn-primary" data-target="#modalDetalle" id="btnModalGuardarDetalleTraspaso">Aceptar</button>
										<button type="button" class="btn" id="btnModalCancelar" data-dismiss="modal">Cancelar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<?php
					$registros->MoveNext();
				}
			}

			//	Cambiar estado
			if(isset($_GET['accion']) && ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM traspasos WHERE (id_traspaso = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE traspasos SET activo = 1 WHERE (id_traspaso = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE traspasos SET activo = 0 WHERE (id_traspaso = '".$valor."')");
								} else {
									$conexion->Query("UPDATE traspasos SET activo = 1 WHERE (id_traspaso = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM traspasos WHERE (id_traspaso = '".$valor."')");
							if($datos) {
								echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
							} else {
								echo("{ \"estado\" : \"error\" }");
							}
						}
					}		
				}
			}

		}
		//	-----	Fin TRASPASOS

		include("../footer.php");
		?>
	</body>
</html>