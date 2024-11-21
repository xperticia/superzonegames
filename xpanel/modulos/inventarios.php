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
			#listaProductos th[data-sort]{
				cursor: pointer;
			}
		</style>
	</head>

	<body>
		<?php
		include("../head.php");

		//	-----	INVENTARIOS
		if(isset($_GET['menu']) and ($_GET['menu'] == "inventarios")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = ""; }
			if(isset($_GET['anio']) and ($_GET['anio'] != "")){
				$url_modulo .= "&anio=".$_GET['anio'];
			}

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						ID_INVENTARIO,
						DESCRIPCION,
						U.NOMBRE_COMPLETO as USUARIOREGISTRA,
						UV.NOMBRE_COMPLETO as USUARIOVERIFICA,
						FECHA_REALIZACION 
					FROM inventarios 
					LEFT JOIN usuarios as U ON U.ID_USUARIO = inventarios.ID_USUARIO 
					LEFT JOIN usuarios as UV ON UV.ID_USUARIO = inventarios.ID_USUARIOVERIFICA 
					WHERE (inventarios.id_sucursal = '{$_SESSION['usuario_sucursal']}') 
				";
				if(isset($_GET['anio']) and ($_GET['anio'] != "")){
					$query .= " AND (year(fecha_realizacion) = '{$_GET['anio']}') ";
				}
				if(isset($_GET['texto'])) {
					$query .= " AND ( 
						(id_inventario LIKE '%".$_GET['texto']."%') or 
						(upper(descripcion) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(U.nombre_completo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(UV.nombre_completo) LIKE upper('%".$_GET['texto']."%')) 
					) ";
				}
				$query .= "ORDER BY fecha_realizacion DESC,id_inventario DESC";

				showTable2($conexion,
					$query,
					array("ID_INVENTARIO"=>"N&deg;",
						"DESCRIPCION"=>"Descripci&oacute;n",
						"USUARIOREGISTRA"=>"Usuario que registra",
						"USUARIOVERIFICA"=>"Usuario que verifica",
						"FECHA_REALIZACION"=>"Fecha"),
					array(0=>"ID_INVENTARIO"),
					$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,null);
			}

			// Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos del Inventario</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Descripci&oacute;n</label>
											<div class="controls">
												<input type="text" id="descripcion" name="descripcion" size="23" maxlength="50" title="Descripci&oacute;n" style="width: 99%;" required />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Usuario que registra</label>
													<div class="controls">
														<select id="usuario" name="usuario" title="Administrador" required>
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
																<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
													<label class="control-label">Usuario que verifica</label>
													<div class="controls">
														<select id="usuarioverifica" name="usuarioverifica" title="Administrador" required >
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
																<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $_SESSION['usuario_id']) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">N&deg; de Inventario</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SHOW TABLE STATUS LIKE 'inventarios'");
												?>
												<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$datos["Auto_increment"])); ?>" size="23" maxlength="50" title="N&deg; de Inventario" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_realizacion" id="fecha_realizacion" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_realizacion" id="hora_realizacion" class="input-small" style="width: 50px;" title="Hora" value="<?php echo(date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button id="btNuevoDetalle" class="btn btn-primary" <?php if(!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled"<?php } ?> data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="">Nuevo</button>
										<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled">Eliminar</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed" id="listaDetalles">
									<thead>
										<tr>
											<th width="20"></th>
											<th>C&oacute;digo</th>
											<th>Detalle</th>
											<th>Stock</th>
											<th width="120">Cantidad</th>
											<th width="120">Diferencia</th>
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
								<select id="listaGenero" name="listaGenero" title="G&eacute;nero" style="width: 99%;">
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
							<th data-sort="string">Categor&iacute;a</th>
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
								<input type="text" id="detalleDescuento" name="detalleDescuento" title="Descuento" pattern="[-+]?[0-9]*[,]?[0-9]+" class="input-small" placeholder="0,00" style="text-align: right;" required />
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
				</div>
				<?php
			}

			// Insertar
			if(isset($_GET['accion']) and ($_GET['accion'] == "insert")) {
				if(isset($_POST['id_inventario'])) {
					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE inventarios 
						SET 
							id_sucursal ='".$_POST['sucursal']."', 
							id_usuario = '".$_POST['usuario']."', 
							id_usuarioverifica = '".$_POST['usuarioverifica']."', 
							fecha_realizacion = '".$_POST['fecha_realizacion']."', 
							hora_realizacion = '".$_POST['hora_realizacion'].":00', 
							descripcion = '".addslashes($_POST['descripcion'])."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_inventario = '".$_POST['id_inventario']."')");
					$id_inventario = $_POST['id_inventario'];
							
					$conexion->Execute("DELETE FROM inventario_posee_productos WHERE (id_inventario = '".$_POST['id_inventario']."')");

					if(isset($_POST['id_detalle'])) {
						foreach($_POST['id_detalle'] as $key=>$value) {
							//echo($key."=>".$value."<br />");
							$conexion->Query("INSERT INTO inventario_posee_productos 
								(ID_INVENTARIO,
								ID_PRODUCTO,
								STOCK,
								CANTIDAD,
								DIFERENCIA
							) VALUES(
								'".$id_inventario."',
								'".$_POST['id_detalle'][$key]."',
								'".number_format($_POST['detalleStock'][$key], 2, '.', '')."',
								'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
								'".number_format($_POST['detalleDiferencia'][$key], 2, '.', '')."'
							)");
						}
					}

				} else {
					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO inventarios 
						(
							ID_INVENTARIO,
							ID_SUCURSAL,
							ID_USUARIO,
							ID_USUARIOVERIFICA,
							FECHA_REALIZACION,
							HORA_REALIZACION,
							DESCRIPCION,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['sucursal']."',
							'".$_POST['usuario']."',
							'".$_POST['usuarioverifica']."',
							'".$_POST['fecha_realizacion']."',
							'".$_POST['hora_realizacion'].":00',
							'".addslashes($_POST['descripcion'])."',
							'".$_POST['id_actualizacion']."'
						)");
					$id_inventario = $conexion->Insert_ID();

					if(isset($_POST['id_detalle'])) {
						foreach($_POST['id_detalle'] as $key=>$value) {
							//echo($key."=>".$value."<br />");
							$conexion->Query("INSERT INTO inventario_posee_productos 
								(
									ID_INVENTARIO,
									ID_PRODUCTO,
									STOCK,
									CANTIDAD,
									DIFERENCIA
								) VALUES(
									'".$id_inventario."',
									'".$_POST['id_detalle'][$key]."',
									'".number_format($_POST['detalleStock'][$key], 2, '.', '')."',
									'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
									'".number_format($_POST['detalleDiferencia'][$key], 2, '.', '')."'
								)");
						}
					}
				}

				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			// Editar
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_REALIZACION)))),hour(HORA_REALIZACION),':',REPEAT('0', (2-LENGTH(minute(HORA_REALIZACION)))),minute(HORA_REALIZACION)) as HORA FROM inventarios WHERE (id_inventario = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_inventario" value="<?php echo($registros->fields["ID_INVENTARIO"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos del Inventario</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Sucursal</label>
												<div class="controls">
													<select id="sucursal" name="sucursal" title="Sucursal" required style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<option value=""></option>
														<?php
															$sucursales = $temp->Query("SELECT * FROM sucursales WHERE (activo = 1) ORDER BY nombre");
															if($sucursales and ($sucursales->RecordCount() > 0)){
																while(!$sucursales->EOF) {
																	?>
														<option value="<?php echo($sucursales->fields["ID_SUCURSAL"]); ?>" <?php if($sucursales->fields["ID_SUCURSAL"] == $registros->fields["ID_SUCURSAL"]) { echo("selected='selected'"); } ?>><?php echo(stripslashes($sucursales->fields["NOMBRE"])); ?></option>
																	<?php
																	$sucursales->MoveNext();
																}
															}
														?>									
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Descripci&oacute;n</label>
												<div class="controls">
													<input type="text" id="descripcion" name="descripcion" value="<?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?>" size="23" maxlength="50" title="Descripci&oacute;n" style="width: 99%;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Usuario que registra</label>
														<div class="controls">
															<select id="usuario" name="usuario" title="Administrador" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
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
																	<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIO"]) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
														<label class="control-label">Usuario que verifica</label>
														<div class="controls">
															<select id="usuarioverifica" name="usuarioverifica" title="Administrador" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
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
																	<option value="<?php echo($usuarios->fields["ID_USUARIO"]); ?>" <?php if($usuarios->fields["ID_USUARIO"] == $registros->fields["ID_USUARIOVERIFICA"]) { echo("selected='selected'"); } ?>><?php echo($usuarios->fields["NOMBRE_COMPLETO"]); ?></option>
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
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">N&deg; de Inventario</label>
												<div class="controls">
													<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$registros->fields["ID_INVENTARIO"])); ?>" size="23" maxlength="50" title="N&deg; de Cotizaci&oacute;n" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha</label>
												<div class="controls">
													<input type="date" name="fecha_realizacion" id="fecha_realizacion" value="<?php echo($registros->fields["FECHA_REALIZACION"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="text" name="hora_realizacion" id="hora_realizacion" class="input-small" style="width: 50px;" title="Hora" value="<?php echo($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="btn-toolbar">
										<div class="btn-group">
											<button id="btNuevoDetalle" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Nuevo</button>
											<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Eliminar</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th width="20"></th>
												<th width="80">C&oacute;digo</th>
												<th>Detalle</th>
												<th width="100">Stock</th>
												<th width="100">Cantidad</th>
												<th width="100">Diferencia</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$detalles = $temp->Query("SELECT inventario_posee_productos.*,productos.CODIGO,productos.NOMBRE,productos.CATEGORIA FROM inventario_posee_productos LEFT JOIN productos ON productos.id_producto = inventario_posee_productos.id_producto WHERE (id_inventario = '".$registros->fields["ID_INVENTARIO"]."') ORDER BY nombre");
												if($detalles) {		
													while(!$detalles->EOF) {
														?>
											<tr class="unChecked">
												<td>
													<input type="checkbox" name="detalle" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="hidden" name="id_detalle[]" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" />
												</td>
												<td><?php echo(stripslashes($detalles->fields["CODIGO"])); ?><input type="hidden" name="detalleCodigo[]" value="<?php echo(stripslashes($detalles->fields["CODIGO"])); ?>" /></td>
												<td><?php echo(stripslashes($detalles->fields["NOMBRE"])." [".stripslashes($detalles->fields["CATEGORIA"])."]"); ?><input type="hidden" name="detalle[]" value="<?php echo(stripslashes($detalles->fields["NOMBRE"])." [".stripslashes($detalles->fields["CATEGORIA"])."]"); ?>" /></td>
												<td style="text-align: right;"><?php echo($detalles->fields["STOCK"]); ?><input type="hidden" name="detalleStock[]" value="<?php echo($detalles->fields["STOCK"]); ?>" /></td>
												<td style="text-align: right;"><input type="text" name="detalleCantidad[]" value="<?php echo($detalles->fields["CANTIDAD"]); ?>" class="input-small" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /></td>
												<td style="text-align: right;"><input type="text" name="detalleDiferencia[]" value="<?php echo($detalles->fields["DIFERENCIA"]); ?>" class="input-small" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /></td>
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
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onClick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
								<?php } ?>
							</div>
						</div>
					</form>

					<div id="modalDetalle" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
											$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and  ((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
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
									<select id="listaGenero" name="listaGenero" title="G&eacute;nero" style="width: 99%;">
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
					</div>
					<?php
					$registros->MoveNext();
				}
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	elimino las venta_posee_productos
						$conexion->Execute("DELETE FROM inventario_posee_productos WHERE (id_inventario = ".$valor.")");
						//	elimino el registro
						$conexion->Execute("DELETE FROM inventarios WHERE (id_inventario = ".$valor.")");
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM inventarios WHERE (id_inventario = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE inventarios SET activo = 1 WHERE (id_inventario = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE inventarios SET activo = 0 WHERE (id_inventario = '".$valor."')");
								} else {
									$conexion->Query("UPDATE inventarios SET activo = 1 WHERE (id_inventario = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM inventarios WHERE (id_inventario = '".$valor."')");
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
		//	-----	Fin INVENTARIOS

		include("../footer.php");
		?>
	</body>
</html>