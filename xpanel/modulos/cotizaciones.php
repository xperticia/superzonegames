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

		//	-----	COTIZACIONES
		if(isset($_GET['menu']) and ($_GET['menu'] == "cotizaciones")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = ""; }
			if(isset($_GET['anio']) and ($_GET['anio'] != "")){
				$url_modulo .= "&anio=".$_GET['anio'];
			}

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						ID_COTIZACION,
						cotizaciones.FECHA_ALTA,
						NOMBRE_COMPLETO,
						FECHA_VENCIMIENTO,
						(TOTAL * ".convertirTipoMoneda().") TOTAL
					FROM cotizaciones 
					LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_cliente 
					WHERE 
						(cotizaciones.id_sucursal = '".$_SESSION['usuario_sucursal']."') 
				";
				if(isset($_GET['anio']) and ($_GET['anio'] != "")){
					$query .= " and (year(cotizaciones.fecha_alta) = '".$_GET['anio']."') ";
				}        
				if(isset($_GET['texto'])) {
					$query .= " and ( 
						(id_cotizacion LIKE '%".$_GET['texto']."%') or 
						(upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or 
						(id_cliente LIKE '%".$_GET['texto']."%') 
					) ";
				}
				$query .= "ORDER BY cotizaciones.fecha_alta DESC,id_cotizacion DESC";

				showTable2($conexion,
					$query,
					array("ID_COTIZACION"=>"N&deg;",
						"FECHA_ALTA"=>"Fecha",
						"NOMBRE_COMPLETO"=>"Cliente",
						"FECHA_VENCIMIENTO"=>"Vencimiento",
						"TOTAL"=>"Total"),
					array(0=>"ID_COTIZACION"),
					$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,null,
					array("imprimirCotizacion"=>"Imprimir Cotización"));
			}

			// Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				?>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Cotizaci&oacute;n</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<input type="hidden" name="id_cliente" id="id_cliente" />
												<input type="text" name="codigo" id="codigo" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" <?php if(!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled"<?php } ?> autocomplete="off" required />
												<button id="btBuscarCliente" class="btn" title=""><i class="icon-search"></i> </button>
												<input type="text" id="cliente" name="cliente" size="23" maxlength="50" title="Cliente" required style="width: 61%;" <?php if(!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled"<?php } ?> />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Direcci&oacute;n</label>
											<div class="controls">
												<input type="text" id="domicilio" name="domicilio" size="23" maxlength="50" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" />
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Tel&eacute;fonos</label>
													<div class="controls">
														<input type="text" id="telefonos" name="telefonos" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label">Categoria</label>
													<div class="controls">
														<input type="text" id="categoria_usuario" name="categoria_usuario" size="23" maxlength="50" title="Categoria de Usuarios" style="width: 99%;" readonly="readonly" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">N&deg; de Cotizaci&oacute;n</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SHOW TABLE STATUS LIKE 'cotizaciones'");
												?>
												<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$datos["Auto_increment"])); ?>" size="23" maxlength="50" title="N&deg; de Cotizaci&oacute;n" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha</label>
											<div class="controls">
												<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
												<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo(date("H:i")); ?>" size="8" maxlength="8" readonly="readonly" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Vencimiento</label>
											<div class="controls">
												<input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo(sumarDias(15,date("Y-m-d"))); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Tipo de Cambio</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo($_SESSION['usuario_tipocambio']); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="[-+]?[0-9]*[,]?[0-9]+" class="input-small" style="text-align: right;" placeholder="0,00" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="btn-toolbar">
									<div class="btn-group">
										<button id="btNuevoDetalle" class="btn btn-primary" disabled="disabled" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="">Nuevo</button>
										<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled">Eliminar</button>
									</div>
									<div class="btn-group">
										<button id="btnCalcularSubtotal" class="btn btn-primary">Calcular Montos</button>
									</div>
								</div>
								<table class="table table-bordered table-condensed" id="listaDetalles">
								<thead>
								<tr>
									<th width="20"></th>
									<th width="100">C&oacute;digo</th>
									<th>Detalle</th>
									<th>Cantidad</th>
									<th>Precio</th>
									<th>Descuento</th>
									<th width="150">Monto</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="6" style="text-align: right;font-weight: bold;">Total</td>
									<td style="text-align: right;"><input type="text" id="total" name="total" pattern="[-+]?[0-9]*[,]?[0-9]+" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
								</tr>
								</tfoot>
								</table>
								<hr />
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%;"></textarea>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label">Usuario</label>
											<div class="controls">
												<select id="usuario" name="usuario" title="Administrador" required style="width: auto;">
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

									</div>
								</div>

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
				if($_POST['tipo_cambio'] == "") { $_POST['tipo_cambio'] = 0; }
				$vTipoCambio = str_replace(".","",$_POST['tipo_cambio']);
				$vTipoCambio = str_replace(",",".",$vTipoCambio);
				$vTotal = str_replace(".","",$_POST['total']);
				$vTotal = str_replace(",",".",$vTotal);

				if(isset($_POST['id_cotizacion'])) {
					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE cotizaciones 
						SET 
							id_sucursal ='".$_POST['sucursal']."', 
							id_cliente = '".$_POST['id_cliente']."', 
							id_usuario = '".$_POST['usuario']."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							fecha_vencimiento = '".$_POST['fecha_vencimiento']."', 
							hora_alta = '".$_POST['hora_alta'].":00', 
							tipo_cambio = '".number_format($vTipoCambio, 4, '.', '')."', 
							total = '".number_format($vTotal, 2, '.', '')."', 
							descripcion = '".addslashes($_POST['descripcion'])."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_cotizacion = '".$_POST['id_cotizacion']."')");
					$id_cotizacion = $_POST['id_cotizacion'];
					
					$conexion->Execute("DELETE FROM cotizacion_posee_productos WHERE (id_cotizacion = '".$_POST['id_cotizacion']."')");

					if(isset($_POST['id_detalle'])) {
						foreach($_POST['id_detalle'] as $key=>$value) {
							//echo($key."=>".$value."<br />");
							$vdetallePrecioVenta = str_replace(".","",$_POST['detallePrecioVenta'][$key]);
							$vdetallePrecioVenta = str_replace(",",".",$vdetallePrecioVenta);
							$vdetalleDescuento = str_replace(".","",$_POST['detalleDescuento'][$key]);
							$vdetalleDescuento = str_replace(",",".",$vdetalleDescuento);
							$vdetalleTotal = str_replace(".","",$_POST['detalleTotal'][$key]);
							$vdetalleTotal = str_replace(",",".",$vdetalleTotal);
							$conexion->Query("INSERT INTO cotizacion_posee_productos 
								(
									ID_COTIZACION,
									ID_PRODUCTO,
									CANTIDAD,
									PRECIO,
									DESCUENTO,
									TOTAL
								) VALUES(
									'".$id_cotizacion."',
									'".$_POST['id_detalle'][$key]."',
									'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
									'".number_format($vdetallePrecioVenta, 2, '.', '')."',
									'".number_format($vdetalleDescuento, 2, '.', '')."',
									'".number_format($vdetalleTotal, 2, '.', '')."'
								)");

						}
					}

				} else {
					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO cotizaciones 
						(
							ID_COTIZACION,
							ID_SUCURSAL,
							ID_CLIENTE,
							ID_USUARIO,
							FECHA_ALTA,
							HORA_ALTA,
							FECHA_VENCIMIENTO,
							TIPO_CAMBIO,
							TOTAL,
							DESCRIPCION,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['sucursal']."',
							'".$_POST['id_cliente']."',
							'".$_POST['usuario']."',
							'".$_POST['fecha_alta']."',
							'".$_POST['hora_alta'].":00',
							'".$_POST['fecha_vencimiento']."',
							'".number_format($vTipoCambio, 4, '.', '')."',
							'".number_format($vTotal, 2, '.', '')."',
							'".addslashes($_POST['descripcion'])."',
							'".$_POST['id_actualizacion']."'
						)");
					$id_cotizacion = $conexion->Insert_ID();

					if(isset($_POST['id_detalle'])) {
						foreach($_POST['id_detalle'] as $key=>$value) {
							//echo($key."=>".$value."<br />");
							$vdetallePrecioVenta = str_replace(".","",$_POST['detallePrecioVenta'][$key]);
							$vdetallePrecioVenta = str_replace(",",".",$vdetallePrecioVenta);
							$vdetalleDescuento = str_replace(".","",$_POST['detalleDescuento'][$key]);
							$vdetalleDescuento = str_replace(",",".",$vdetalleDescuento);
							$vdetalleTotal = str_replace(".","",$_POST['detalleTotal'][$key]);
							$vdetalleTotal = str_replace(",",".",$vdetalleTotal);
							$conexion->Query("INSERT INTO cotizacion_posee_productos 
								(
									ID_COTIZACION,
									ID_PRODUCTO,
									CANTIDAD,
									PRECIO,
									DESCUENTO,
									TOTAL
								) VALUES(
									'".$id_cotizacion."',
									'".$_POST['id_detalle'][$key]."',
									'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
									'".number_format($vdetallePrecioVenta, 2, '.', '')."',
									'".number_format($vdetalleDescuento, 2, '.', '')."',
									'".number_format($vdetalleTotal, 2, '.', '')."'
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
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA FROM cotizaciones WHERE (id_cotizacion = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_CLIENTE"]."')");
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_cotizacion" value="<?php echo($registros->fields["ID_COTIZACION"]); ?>" />
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos de la Cotizaci&oacute;n</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="control-group">
												<label class="control-label">Cliente</label>
												<div class="controls">
													<input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo($registros->fields["ID_CLIENTE"]); ?>" />
													<input type="text" name="codigo" id="codigo" value="<?php echo(sprintf("%04s",$registros->fields["ID_CLIENTE"])); ?>" class="input-small" placeholder="0000" style="text-align: right;font-size: 1.5em;" autocomplete="off" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<button id="btBuscarCliente" class="btn" title="" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-search"></i> </button>
													<input type="text" id="cliente" name="cliente" value="<?php echo(stripslashes($datosUsuario["NOMBRE_COMPLETO"])); ?>" size="23" maxlength="50" title="Cliente" required style="width: 61%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Direcci&oacute;n</label>
												<div class="controls">
													<input type="text" id="domicilio" name="domicilio" value="<?php echo(stripslashes($datosUsuario["DOMICILIO"])); ?>" size="23" maxlength="50" title="Direcci&oacute;n" style="width: 99%;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Tel&eacute;fonos</label>
														<div class="controls">
															<input type="text" id="telefonos" name="telefonos" value="<?php echo(stripslashes($datosUsuario["TELEFONOS"])); ?>" size="23" maxlength="50" title="Tel&eacute;fonos" style="width: 99%;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Categoria</label>
														<div class="controls">
															<input type="text" id="categoria_usuario" name="categoria_usuario" value="<?php echo(stripslashes($datosUsuario["CATEGORIA_USUARIO"])); ?>" size="23" maxlength="50" title="Categoria de Usuario" style="width: 99%;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">N&deg; de Cotizaci&oacute;n</label>
												<div class="controls">
													<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$registros->fields["ID_COTIZACION"])); ?>" size="23" maxlength="50" title="N&deg; de Cotizaci&oacute;n" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha</label>
												<div class="controls">
													<input type="date" name="fecha_alta" id="fecha_alta" value="<?php echo($registros->fields["FECHA_ALTA"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="text" name="hora_alta" id="hora_alta" class="input-small" style="width: 50px;" title="Hora" value="<?php echo($registros->fields["HORA"]); ?>" size="8" maxlength="8" readonly="readonly" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Vencimiento</label>
												<div class="controls">
													<input type="date" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo($registros->fields["FECHA_VENCIMIENTO"]); ?>" class="input-medium" title="Fecha" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Tipo de Cambio</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="tipo_cambio" name="tipo_cambio" value="<?php echo(number_format($registros->fields["TIPO_CAMBIO"], 4, ',', '.')); ?>" size="23" maxlength="255" title="Tipo de Cambio" pattern="[-+]?[0-9]*[,]?[0-9]+" class="input-small" style="text-align: right;" placeholder="0,00" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="btn-toolbar">
										<div class="btn-group">
											<button id="btNuevoDetalle" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="<?php echo($registros->fields["ID_CLIENTE"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Nuevo</button>
											<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Eliminar</button>
										</div>
										<div class="btn-group">
											<button id="btnCalcularSubtotal" class="btn btn-primary" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Calcular Montos</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th width="20"></th>
												<th width="100">C&oacute;digo</th>
												<th>Detalle</th>
												<th>Cantidad</th>
												<th>Precio</th>
												<th>Descuento</th>
												<th width="150">Monto</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$detalles = $temp->Query("SELECT cotizacion_posee_productos.*,productos.CODIGO,productos.NOMBRE FROM cotizacion_posee_productos LEFT JOIN productos ON productos.id_producto = cotizacion_posee_productos.id_producto WHERE (id_cotizacion = '".$registros->fields["ID_COTIZACION"]."') ORDER BY nombre");
												if($detalles) {		
													while(!$detalles->EOF) {
														?>
											<tr class="unChecked">
												<td>
													<input type="checkbox" name="detalle" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
													<input type="hidden" name="id_detalle[]" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" />
												</td>
												<td><?php echo(stripslashes($detalles->fields["CODIGO"])); ?><input type="hidden" name="detalleCodigo[]" value="<?php echo(stripslashes($detalles->fields["CODIGO"])); ?>" /></td>
												<td><?php echo(stripslashes($detalles->fields["NOMBRE"])); ?><input type="hidden" name="detalle[]" value="<?php echo(stripslashes($detalles->fields["NOMBRE"])); ?>" /></td>
												<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?><input type="hidden" name="detalleCantidad[]" value="<?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?>" /></td>
												<td style="text-align: right;"><?php echo(number_format($detalles->fields["PRECIO"], 2, ',', '.')); ?><input type="hidden" name="detallePrecioVenta[]" value="<?php echo(number_format($detalles->fields["PRECIO"], 2, ',', '.')); ?>" /></td>
												<td style="text-align: right;"><?php echo(number_format($detalles->fields["DESCUENTO"], 2, ',', '.')); ?><input type="hidden" name="detalleDescuento[]" value="<?php echo(number_format($detalles->fields["DESCUENTO"], 2, ',', '.')); ?>" /></td>
												<td style="text-align: right;padding-right: 10px;"><?php echo(number_format($detalles->fields["TOTAL"], 2, ',', '.')); ?><input type="hidden" name="detalleTotal[]" value="<?php echo(number_format($detalles->fields["TOTAL"], 2, ',', '.')); ?>" /></td>
											</tr>
														<?php
														$detalles->MoveNext();
													}
												}
											?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="6" style="text-align: right;font-weight: bold;">Total</td>
												<td style="text-align: right;"><input type="text" id="total" name="total" pattern="[-+]?[0-9]*[,]?[0-9]+" value="<?php echo(number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /></td>
											</tr>
										</tfoot>
									</table>
									<hr />
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Usuario</label>
												<div class="controls">
													<select id="usuario" name="usuario" title="Administrador" required style="width: auto;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
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
											<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>
										</div>
									</div>

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
					$registros->MoveNext();
				}
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//	elimino las venta_posee_productos
						$conexion->Execute("DELETE FROM egreso_posee_productos WHERE (id_cotizacion = ".$valor.")");
						//	elimino el registro
						$conexion->Execute("DELETE FROM cotizaciones WHERE (id_cotizacion = ".$valor.")");
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM cotizaciones WHERE (id_cotizacion = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE cotizaciones SET activo = 1 WHERE (id_cotizacion = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE cotizaciones SET activo = 0 WHERE (id_cotizacion = '".$valor."')");
								} else {
									$conexion->Query("UPDATE cotizaciones SET activo = 1 WHERE (id_cotizacion = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM cotizaciones WHERE (id_cotizacion = '".$valor."')");
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
		//	-----	Fin COTIZACIONES

		include("../footer.php");
		?>
	</body>
</html>