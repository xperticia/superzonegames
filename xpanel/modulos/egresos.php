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

		//	-----	EGRESOS
		if(isset($_GET['menu']) and ($_GET['menu'] == "egresos")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = ""; }
			if(isset($_GET['anio']) and ($_GET['anio'] != "")){
				$url_modulo .= "&anio=".$_GET['anio'];
			}

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						ID_EGRESO,
						egresos.FECHA_ALTA,
						NRO_FACTURA,
						NOMBRE_COMPLETO,
						TIPO_EGRESO,
						(TOTAL * ".convertirTipoMoneda().") TOTAL, 
						PAGADO 
					FROM egresos 
					LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_cliente 
					WHERE 
						(egresos.id_sucursal = '".$_SESSION['usuario_sucursal']."') 
				";
				if(isset($_GET['anio']) and ($_GET['anio'] != "")){
					$query .= " and (year(egresos.fecha_alta) = '".$_GET['anio']."') ";
				}
				if(isset($_GET['texto'])) {
					$query .= " and ( 
						(id_egreso LIKE '%".$_GET['texto']."%') or 
						(nro_factura LIKE '%".$_GET['texto']."%') or 
						(upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(tipo_egreso) LIKE upper('%".$_GET['texto']."%')) 
					) ";
				}
				$query .= "ORDER BY egresos.fecha_alta DESC";

				showTable2($conexion,
					$query,
					array("ID_EGRESO"=>"N&deg;",
						"FECHA_ALTA"=>"Fecha",
						"NRO_FACTURA"=>"N&deg; Factura",
						"NOMBRE_COMPLETO"=>"Cliente",
						"TIPO_EGRESO"=>"Tipo",
						"TOTAL"=>"Total",
						"PAGADO"=>"Pagado"),
					array(0=>"ID_EGRESO"),
					$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,
						array("renombrarTipo"=>"Renombrar Tipo")
					);
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
							<li class="active"><a href="#tab01" data-toggle="tab">Datos del Egreso</a></li>
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
											<label class="control-label">N&deg; de Egreso</label>
											<div class="controls">
												<?php
												$datos = $conexion->GetRow("SHOW TABLE STATUS LIKE 'egresos'");
												?>
												<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$datos["Auto_increment"])); ?>" size="23" maxlength="50" title="N&deg; de Egreso" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" />
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
											<label class="control-label">N&deg; de Factura</label>
											<div class="controls">
												<input type="text" id="nro_factura" name="nro_factura" size="23" maxlength="10" title="N&deg; de Factura" class="input-small" style="text-align: right;" />
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
										<div class="control-group">
											<label class="control-label">Tipo de Egreso</label>
											<div class="controls">								
												<input type="text" id="tipo_egreso" name="tipo_egreso" size="23" maxlength="255" title="Tipo de Egreso" class="input-small" required="required" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%;"></textarea>
									</div>
								</div>
								<hr />
								<div id="divEgresosTotal">
									<div class="control-group">
										<label class="control-label">Total</label>
										<div class="controls">
											<input type="text" id="totalEgreso" name="totalEgreso" size="23" maxlength="255" title="Total" pattern="^[1-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" style="text-align: right;" placeholder="0,00" required />
										</div>
									</div>
								</div>
								<div id="divEgresosProductos" style="display: none;">
									<div class="btn-toolbar">
										<div class="btn-group">
											<button id="btNuevoDetalle" class="btn btn-primary" disabled="disabled" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente=""><i class="icon-plus icon-white"></i> Nuevo</button>
											<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled"><i class="icon-trash icon-white"></i> Eliminar</button>
										</div>
										<div class="btn-group">
											<button id="btnCalcularSubtotal" class="btn btn-primary">Calcular Montos</button>
										</div>
									</div>
									<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
											<tr>
												<th width="20"></th>
												<th>C&oacute;digo</th>
												<th>Detalle</th>
												<th>Precio</th>
												<th>Cantidad</th>
												<th width="150">Monto</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="5" style="text-align: right;font-weight: bold;">Total</td>
												<td style="text-align: right;"><input type="text" id="total" name="total" pattern="[-+]?[0-9]*[,]?[0-9]+" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required /></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<hr />
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
									<div class="span3">
										<div class="control-group">
											<label class="control-label">Pagado</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="1" checked="checked" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="pagado" name="pagado" value="0" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span3">
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
										$categorias = $temp->Query("SELECT CATEGORIA FROM productos WHERE (id_sucursal = '{$_SESSION['usuario_sucursal']}') and (activo = 1) and((categoria is not null) and (categoria <> '')) GROUP BY categoria ORDER BY categoria");
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
				if(strtoupper($_POST['tipo_egreso']) == "COMPRAS") {
					$vTotal = str_replace(".","",$_POST['total']);
					$vTotal = str_replace(",",".",$vTotal);
				} else {
					$vTotal = str_replace(".","",$_POST['totalEgreso']);
					$vTotal = str_replace(",",".",$vTotal);
				}

				if(isset($_POST['id_egreso'])) {
					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					$conexion->Query("UPDATE egresos 
						SET 
							id_sucursal ='".$_POST['sucursal']."', 
							id_cliente = '".$_POST['id_cliente']."', 
							id_usuario = '".$_POST['usuario']."', 
							fecha_alta = '".$_POST['fecha_alta']."', 
							hora_alta = '".$_POST['hora_alta'].":00', 
							nro_factura = '".addslashes($_POST['nro_factura'])."', 
							tipo_egreso = '".$_POST['tipo_egreso']."', 
							tipo_cambio = '".number_format($vTipoCambio, 4, '.', '')."', 
							total = '".number_format($vTotal, 2, '.', '')."', 
							pagado = '".$_POST['pagado']."', 
							descripcion = '".addslashes($_POST['descripcion'])."', 
							id_actualizacion = '".$_POST['id_actualizacion']."' 
						WHERE (id_egreso = '".$_POST['id_egreso']."')");
					$id_egreso = $_POST['id_egreso'];
					
					if(strtoupper($_POST['tipo_egreso']) == "COMPRAS") {
						$conexion->Execute("DELETE FROM egreso_posee_productos WHERE (id_egreso = '".$_POST['id_egreso']."')");

						if(isset($_POST['id_detalle'])) {
							foreach($_POST['id_detalle'] as $key=>$value) {
								//echo($key."=>".$value."<br />");
								$vdetallePrecio = str_replace(".","",$_POST['detallePrecio'][$key]);
								$vdetallePrecio = str_replace(",",".",$vdetallePrecio);
								$vdetalleTotal = str_replace(".","",$_POST['detalleTotal'][$key]);
								$vdetalleTotal = str_replace(",",".",$vdetalleTotal);
								$conexion->Query("INSERT INTO egreso_posee_productos 
									(
										ID_EGRESO,
										ID_PRODUCTO,
										CODIGO,
										DETALLE,
										PRECIO,
										CANTIDAD,TOTAL
									) VALUES(
										'".$id_egreso."',
										'".$_POST['id_detalle'][$key]."',
										'".$_POST['detalleCodigo'][$key]."',
										'".$_POST['detalle'][$key]."',
										'".number_format($vdetallePrecio, 2, '.', '')."',
										'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
										'".number_format($vdetalleTotal, 2, '.', '')."'
									)");

							}
						}
					}

				} else {
					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					$conexion->Query("INSERT INTO egresos 
						(
							ID_EGRESO,
							ID_SUCURSAL,
							ID_CLIENTE,
							ID_USUARIO,
							FECHA_ALTA,
							HORA_ALTA,
							NRO_FACTURA,
							TIPO_EGRESO,
							TIPO_CAMBIO,
							TOTAL,
							PAGADO,
							DESCRIPCION,
							ID_ACTUALIZACION
						) VALUES(
							null,
							'".$_POST['sucursal']."',
							'".$_POST['id_cliente']."',
							'".$_POST['usuario']."',
							'".$_POST['fecha_alta']."',
							'".$_POST['hora_alta'].":00',
							'".addslashes($_POST['nro_factura'])."',
							'".$_POST['tipo_egreso']."',
							'".number_format($vTipoCambio, 4, '.', '')."',
							'".number_format($vTotal, 2, '.', '')."',
							'".$_POST['pagado']."',
							'".addslashes($_POST['descripcion'])."',
							'".$_POST['id_actualizacion']."'
						)");
					$id_egreso = $conexion->Insert_ID();

					if(strtoupper($_POST['tipo_egreso']) == "COMPRAS") {
						if(isset($_POST['id_detalle'])) {
							foreach($_POST['id_detalle'] as $key=>$value) {
								//echo($key."=>".$value."<br />");
								$vdetallePrecio = str_replace(".","",$_POST['detallePrecio'][$key]);
								$vdetallePrecio = str_replace(",",".",$vdetallePrecio);
								$vdetalleTotal = str_replace(".","",$_POST['detalleTotal'][$key]);
								$vdetalleTotal = str_replace(",",".",$vdetalleTotal);
								$conexion->Query("INSERT INTO egreso_posee_productos 
									(
										ID_EGRESO,
										ID_PRODUCTO,
										CODIGO,
										DETALLE,
										PRECIO,
										CANTIDAD,
										TOTAL
									) VALUES(
										'".$id_egreso."',
										'".$_POST['id_detalle'][$key]."',
										'".$_POST['detalleCodigo'][$key]."',
										'".$_POST['detalle'][$key]."',
										'".number_format($vdetallePrecio, 2, '.', '')."',
										'".number_format($_POST['detalleCantidad'][$key], 2, '.', '')."',
										'".number_format($vdetalleTotal, 2, '.', '')."'
									)");

							}
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
				$registros = $conexion->Query("SELECT *,concat(REPEAT('0', (2-LENGTH(hour(HORA_ALTA)))),hour(HORA_ALTA),':',REPEAT('0', (2-LENGTH(minute(HORA_ALTA)))),minute(HORA_ALTA)) as HORA FROM egresos WHERE (id_egreso = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuario = $conexion->GetRow("SELECT *,concat(IFNULL(TELEFONO,''),' / ',IFNULL(TELEFONO_MOVIL,'')) as TELEFONOS FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_CLIENTE"]."')");
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_egreso" value="<?php echo($registros->fields["ID_EGRESO"]); ?>" />
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos del Egreso</a></li>
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
												<label class="control-label">N&deg; de Egreso</label>
												<div class="controls">
													<input type="text" id="proforma" name="proforma" value="<?php echo(sprintf("%04s",$registros->fields["ID_EGRESO"])); ?>" size="23" maxlength="50" title="N&deg; de Venta" class="input-small" style="text-align: right;font-size: 1.5em;" readonly="readonly" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
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
												<label class="control-label">N&deg; de Factura</label>
												<div class="controls">
													<input type="text" id="nro_factura" name="nro_factura" value="<?php echo($registros->fields["NRO_FACTURA"]); ?>" size="23" maxlength="10" title="N&deg; de Factura" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
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
											<div class="control-group">
												<label class="control-label">Tipo de Egreso</label>
												<div class="controls">
													<input type="text" id="tipo_egreso" name="tipo_egreso" value="<?php echo($registros->fields["TIPO_EGRESO"]); ?>" size="23" maxlength="10" title="Tipo de Egreso" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea id="descripcion" name="descripcion" rows="4" cols="60" title="Descripci&oacute;n" style="width: 99%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<hr />
									<div id="divEgresosTotal" <?php if(strtoupper($registros->fields["TIPO_EGRESO"]) == "COMPRAS") { echo("style='display: none;'"); } else { echo("style='display: block;'"); } ?>>
										<div class="control-group">
											<label class="control-label">Total</label>
											<div class="controls">
												<input type="text" id="totalEgreso" name="totalEgreso" value="<?php echo(number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="23" maxlength="255" title="Total" pattern="^[1-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" style="text-align: right;" placeholder="0,00" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
											</div>
										</div>
									</div>
									<div id="divEgresosProductos" <?php if(strtoupper($registros->fields["TIPO_EGRESO"]) == "COMPRAS") { echo("style='display: block;'"); } else { echo("style='display: none;'"); } ?>>
										<div class="btn-toolbar">
											<div class="btn-group">
												<button id="btNuevoDetalle" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalle" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="<?php echo($registros->fields["ID_CLIENTE"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-plus icon-white"></i>Nuevo</button>
												<button id="btEliminarDetalle" class="btn btn-danger" disabled="disabled" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><i class="icon-trash icon-white"></i> Eliminar</button>
											</div>
											<div class="btn-group">
												<button id="btnCalcularSubtotal" class="btn btn-primary" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>Calcular Montos</button>
											</div>
										</div>
										<table class="table table-bordered table-condensed" id="listaDetalles">
										<thead>
										<tr>
											<th width="20"></th>
											<th>C&oacute;digo</th>
											<th>Detalle</th>
											<th>Precio</th>
											<th>Cantidad</th>
											<th width="150">Monto</th>
										</tr>
										</thead>
										<tbody>
										<?php
											$detalles = $temp->Query("SELECT * FROM egreso_posee_productos WHERE (id_egreso = '".$registros->fields["ID_EGRESO"]."') ORDER BY detalle");
											if($detalles) {		
												while(!$detalles->EOF) {
													?>
										<tr class="unChecked">
											<td>
												<input type="checkbox" name="detalle" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												<input type="hidden" name="id_detalle[]" value="<?php echo($detalles->fields["ID_PRODUCTO"]); ?>" />
											</td>
											<td><?php echo(stripslashes($detalles->fields["CODIGO"])); ?><input type="hidden" name="detalleCodigo[]" value="<?php echo(stripslashes($detalles->fields["CODIGO"])); ?>" /></td>
											<td><?php echo(stripslashes($detalles->fields["DETALLE"])); ?><input type="hidden" name="detalle[]" value="<?php echo(stripslashes($detalles->fields["DETALLE"])); ?>" /></td>
											<td style="text-align: right;"><?php echo(number_format($detalles->fields["PRECIO"], 2, ',', '.')); ?><input type="hidden" name="detallePrecio[]" value="<?php echo(number_format($detalles->fields["PRECIO"], 2, ',', '.')); ?>" /></td>
											<td style="text-align: right;"><?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?><input type="hidden" name="detalleCantidad[]" value="<?php echo(stripslashes($detalles->fields["CANTIDAD"])); ?>" /></td>
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
											<td colspan="5" style="text-align: right;font-weight: bold;">Total</td>
											<td style="text-align: right;"><input type="text" id="total" name="total" pattern="[-+]?[0-9]*[,]?[0-9]+" value="<?php echo(number_format($registros->fields["TOTAL"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Total" class="input-small" placeholder="0,00" style="text-align: right;" readonly="readonly" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /></td>
										</tr>
										</tfoot>
										</table>
									</div>
									<hr />
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
										<div class="span3">
											<div class="control-group">
												<label class="control-label">Pagado</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="pagado" name="pagado" value="1" <?php if($registros->fields["PAGADO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="pagado" name="pagado" value="0" <?php if($registros->fields["PAGADO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
										<div class="span3">
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
						$conexion->Execute("DELETE FROM egreso_posee_productos WHERE (id_egreso = ".$valor.")");
						//	elimino el registro
						$conexion->Execute("DELETE FROM egresos WHERE (id_egreso = ".$valor.")");
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Cambiar estado
			if(isset($_GET['accion']) and ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM egresos WHERE (id_egreso = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE egresos SET activo = 1 WHERE (id_egreso = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE egresos SET activo = 0 WHERE (id_egreso = '".$valor."')");
								} else {
									$conexion->Query("UPDATE egresos SET activo = 1 WHERE (id_egreso = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM egresos WHERE (id_egreso = '".$valor."')");
							if($datos) {
								echo("{ \"estado\" : \"".$estados[$datos["ACTIVO"]]."\" }");
							} else {
								echo("{ \"estado\" : \"error\" }");
							}
						}
					}		
				}
			}

			//	Renombrar Tipo de Producto
			if(isset($_GET['accion']) and ($_GET['accion'] == "renombrarTipo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatetipo"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label" for="tipo_egreso">Tipo de Egreso</label>
									<div class="controls">
										<input type="text" id="tipo_egreso" name="tipo_egreso" size="50" maxlength="255" title="Tipo de Egreso" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="nuevotipo_egreso">Nueva Tipo de Producto</label>
									<div class="controls">
										<input type="text" id="nuevotipo_egreso" name="nuevotipo_egreso" size="50" maxlength="255" title="Nuevo Tipo de Egreso" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar Tipo de Egreso" onclick="return confirm('Se va ha cambiar el nombre del Tipo de Egreso seleccionada.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Tipo de Egreso
			if(isset($_GET['accion']) and ($_GET['accion'] == "updatetipo")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE egresos SET tipo_egreso = '".addslashes($_POST["nuevotipo_egreso"])."' WHERE (tipo_egreso = '".$_POST["tipo_egreso"]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

		}
		//	-----	Fin EGRESOS

		include("../footer.php");
		?>
	</body>
</html>