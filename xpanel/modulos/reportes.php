<?php
require("../config.php");
require("../verifica.php");
$nivel_acceso = 5; // Nivel de acceso para esta pagina.
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
</head>

<body>
	<?php
	include("../head.php");

	//	-----	REPORTES
	if (isset($_GET['menu']) and ($_GET['menu'] == "reportes")) {
		//	Listado
		if (!isset($_GET['accion'])) {
			?>
			<div class="thumbnail">
				<div class="caption">
					
					<div class="row-fluid">
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h3>Información</h3>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porconsultas"); ?>" class="btn btn-primary btn-large btn-block">Consultas</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porcomentarios"); ?>" class="btn btn-primary btn-large btn-block">Comentarios</a>
									</p>
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h3>Contenidos Web</h3>
									<p>
										<a href="javascript:void(0);" disabled="disabled" class="btn btn-large btn-block">Reporte 1</a>
										<a href="javascript:void(0);" disabled="disabled" class="btn btn-large btn-block">Reporte 2</a>
									</p>
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h3>Sistema</h3>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porusuario"); ?>" class="btn btn-primary btn-large btn-block">Actividades de los Usuarios</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br />
			<div class="thumbnail">
				<div class="caption">
					<h3>Datos</h3>
					<div class="row-fluid">
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h5>Reportes de Sucursales</h5>
									<p>
										<a href="javascript:void(0);" disabled="disabled" class="btn btn-large btn-block">Sucursales</a>
									</p>
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h5>Reportes de Clientes</h5>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porclientes"); ?>" class="btn btn-primary btn-large btn-block">Clientes</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=pordeudores"); ?>" class="btn btn-primary btn-large btn-block">Deudores</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=pordeudoresxmes"); ?>" class="btn btn-primary btn-large btn-block">Deudores según los meses</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=pordeudoresxcuota"); ?>" class="btn btn-info btn-large btn-block">Deudores por cuota</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porvencimientos"); ?>" class="btn btn-primary btn-large btn-block">Vencimientos mensuales x Cliente</a>
									</p>
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h5>Reportes de Productos</h5>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porproductos"); ?>" class="btn btn-primary btn-large btn-block">Productos</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porproductosapedir"); ?>" class="btn btn-primary btn-large btn-block">Productos a pedir</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=pormovimientostock"); ?>" class="btn btn-info btn-large btn-block">Movimientos de Stock</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porproductosvencidos"); ?>" class="btn btn-primary btn-large btn-block">Productos con fecha de vencimiento</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porcatalogo"); ?>" class="btn btn-primary btn-large btn-block">Catálogo de productos</a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<br />
					<div class="row-fluid">
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h5>Reportes de Ventas</h5>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porventas"); ?>" class="btn btn-primary btn-large btn-block">Ventas</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porventasmensuales"); ?>" class="btn btn-info btn-large btn-block">Ventas mensuales</a>
									</p>
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="thumbnail">
								<div class="caption">
									<h5>Otros reportes</h5>
									<p>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porservicios"); ?>" class="btn btn-primary btn-large btn-block">Servicios T&eacute;cnicos</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porpedidos"); ?>" class="btn btn-primary btn-large btn-block">Pedidos</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=poregresos"); ?>" class="btn btn-primary btn-large btn-block">Egresos</a>
										<a href="javascript:void(0);" disabled="disabled" class="btn btn-large btn-block">Traspasos</a>
										<a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=porcotizaciones"); ?>" class="btn btn-primary btn-large btn-block">Cotizaciones</a>
										<a href="javascript:void(0);" disabled="disabled" class="btn btn-large btn-block">Inventarios</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		// INFORMACION

		// Consultas
		if (isset($_GET['accion']) and ($_GET['accion'] == "porconsultas")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 1;
			}
			?>
			<h3>Consultas</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar por País" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los paises</option>
								<option value=""></option>
								<?php
								$query = "SELECT paises.* FROM mensajes LEFT JOIN paises ON paises.codigo_pais = mensajes.codigo_pais WHERE (tipo_mensaje = 'Consultas') ";
								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								$query .= "GROUP BY mensajes.codigo_pais ORDER BY pais";

								$datosPaises = $temp->Query($query);
								if ($datosPaises and ($datosPaises->RecordCount() > 0)) {
									while (!$datosPaises->EOF) {
								?>
										<option value="<?php echo ($url . "&p=" . $datosPaises->fields["CODIGO_PAIS"]); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == $datosPaises->fields["CODIGO_PAIS"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosPaises->fields["PAIS"])); ?></option>
								<?php
										$datosPaises->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Nombre</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por E-mail</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Asunto</option>
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "5")) {
																					echo ("selected='selected'");
																				} ?>>por País</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="hidden" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Nombre</th>
									<th>Correo electrónico</th>
									<th>Asunto</th>
									<th>País</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT FECHA_ALTA,NOMBRE,EMAIL,ASUNTO,PAIS,count(";
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "fecha_alta";
											break;
										case 2:
											$query .= "nombre";
											break;
										case 3:
											$query .= "email";
											break;
										case 4:
											$query .= "asunto";
											break;
										case 5:
											$query .= "mensajes.codigo_pais";
											break;
									}
								}
								$query .= ") CANTIDAD FROM mensajes
									LEFT JOIN paises ON paises.codigo_pais = mensajes.codigo_pais
									WHERE (tipo_mensaje = 'Consultas') ";

								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (mensajes.codigo_pais = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY nombre ";
											break;
										case 3:
											$query .= "GROUP BY email ";
											break;
										case 4:
											$query .= "GROUP BY asunto ";
											break;
										case 5:
											$query .= "GROUP BY mensajes.codigo_pais ";
											break;
									}
								}
								$query .= "ORDER BY fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["CANTIDAD"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
											<td><?php echo ($registros->fields["EMAIL"]); ?></td>
											<td><?php echo ($registros->fields["ASUNTO"]); ?></td>
											<td><?php echo ($registros->fields["PAIS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["CANTIDAD"]); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="6" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, 0, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Comentarios
		if (isset($_GET['accion']) and ($_GET['accion'] == "porcomentarios")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 1;
			}
			?>
			<h3>Comentarios</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar por País" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los paises</option>
								<option value=""></option>
								<?php
								$query = "SELECT paises.* FROM mensajes LEFT JOIN paises ON paises.codigo_pais = mensajes.codigo_pais WHERE (tipo_mensaje = 'Comentarios') ";
								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								$query .= "GROUP BY mensajes.codigo_pais ORDER BY pais";

								$datosPaises = $temp->Query($query);
								if ($datosPaises and ($datosPaises->RecordCount() > 0)) {
									while (!$datosPaises->EOF) {
								?>
										<option value="<?php echo ($url . "&p=" . $datosPaises->fields["CODIGO_PAIS"]); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == $datosPaises->fields["CODIGO_PAIS"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosPaises->fields["PAIS"])); ?></option>
								<?php
										$datosPaises->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Nombre</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por E-mail</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Asunto</option>
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "5")) {
																					echo ("selected='selected'");
																				} ?>>por País</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Nombre</th>
									<th>Correo electrónico</th>
									<th>Asunto</th>
									<th>País</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT FECHA_ALTA,NOMBRE,EMAIL,ASUNTO,PAIS,count(";
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "fecha_alta";
											break;
										case 2:
											$query .= "nombre";
											break;
										case 3:
											$query .= "email";
											break;
										case 4:
											$query .= "asunto";
											break;
										case 5:
											$query .= "mensajes.codigo_pais";
											break;
									}
								}
								$query .= ") CANTIDAD FROM mensajes
									LEFT JOIN paises ON paises.codigo_pais = mensajes.codigo_pais
									WHERE (tipo_mensaje = 'Comentarios') ";

								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (mensajes.codigo_pais = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY nombre ";
											break;
										case 3:
											$query .= "GROUP BY email ";
											break;
										case 4:
											$query .= "GROUP BY asunto ";
											break;
										case 5:
											$query .= "GROUP BY mensajes.codigo_pais ";
											break;
									}
								}
								$query .= "ORDER BY fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["CANTIDAD"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
											<td><?php echo ($registros->fields["EMAIL"]); ?></td>
											<td><?php echo ($registros->fields["ASUNTO"]); ?></td>
											<td><?php echo ($registros->fields["PAIS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["CANTIDAD"]); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, 0, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// CONTENIDOS WEB

		// DATOS

		// DATOS -> CLIENTES ---------

		// Clientes
		if (isset($_GET['accion']) and ($_GET['accion'] == "porclientes")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 1;
			}
			?>
			<h3>Clientes</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="cat" id="cat" onchange="AbrirURL(this);" title="Filtrar por Categoría" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorías</option>
								<option value="" disabled></option>
								<?php
								$query = "SELECT CATEGORIA_USUARIO
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and ((categoria_usuario is not null) and (categoria_usuario <> ''))
										GROUP BY categoria_usuario
										ORDER BY categoria_usuario
									";

								$stockminimos = $temp->Query($query);
								if ($stockminimos and ($stockminimos->RecordCount() > 0)) {
									while (!$stockminimos->EOF) {
								?>
										<option value="<?php echo ($url . "&cat=" . $stockminimos->fields["CATEGORIA_USUARIO"]); ?>" <?php if (isset($_GET['cat']) and ($_GET['cat'] == $stockminimos->fields["CATEGORIA_USUARIO"])) {
																																			echo ("selected=\"selected\"");
																																		} ?>>
											<?php echo (stripslashes($stockminimos->fields["CATEGORIA_USUARIO"])); ?>
										</option>
								<?php
										$stockminimos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar por País" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los paises</option>
								<option value="" disabled></option>
								<?php
								$query = "SELECT paises.*
										FROM usuarios
										LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
										GROUP BY usuarios.codigo_pais
										ORDER BY pais
									";

								$stockminimos = $temp->Query($query);
								if ($stockminimos and ($stockminimos->RecordCount() > 0)) {
									while (!$stockminimos->EOF) {
								?>
										<option value="<?php echo ($url . "&p=" . $stockminimos->fields["CODIGO_PAIS"]); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == $stockminimos->fields["CODIGO_PAIS"])) {
																																	echo ("selected=\"selected\"");
																																} ?>>
											<?php echo (stripslashes($stockminimos->fields["PAIS"])); ?>
										</option>
								<?php
										$stockminimos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Ciudad" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Ciudades</option>
								<option value="" disabled></option>
								<?php
								$query = "SELECT CIUDAD
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and ((ciudad is not null) and (ciudad <> ''))
										";
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (codigo_pais = '" . $_GET['p'] . "') ";
								}
								$query .= "GROUP BY ciudad ORDER BY ciudad";

								$stockminimos = $temp->Query($query);
								if ($stockminimos and ($stockminimos->RecordCount() > 0)) {
									while (!$stockminimos->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $stockminimos->fields["CIUDAD"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $stockminimos->fields["CIUDAD"])) {
																															echo ("selected=\"selected\"");
																														} ?>>
											<?php echo (stripslashes($stockminimos->fields["CIUDAD"])); ?>
										</option>
								<?php
										$stockminimos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value="" disabled></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Código</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Nombres y Apellidos</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Categoría</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Ciudad</option>
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "5")) {
																					echo ("selected='selected'");
																				} ?>>por País</option>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<input type="hidden" id="obtenerDatos" value="<?php echo ($url); ?>" onclick="AbrirURL(this);" />
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80" <?php if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "1")) {
														echo ("class='columnaAgrupada'");
													} ?>>Código</th>
									<th <?php if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "2")) {
											echo ("class='columnaAgrupada'");
										} ?>>Nombres y Apellidos</th>
									<th <?php if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "3")) {
											echo ("class='columnaAgrupada'");
										} ?>>Categoría</th>
									<th <?php if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "4")) {
											echo ("class='columnaAgrupada'");
										} ?>>Ciudad</th>
									<th <?php if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "5")) {
											echo ("class='columnaAgrupada'");
										} ?>>País</th>
									<th>Ventas <br />pagadas</th>
									<th>Ventas <br />no pagadas</th>
									<th>Servicios <br />pagados</th>
									<th>Servicios <br />no pagados</th>
									<th>Pedidos <br />registrados</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$suma_ventasPagadas = 0;
								$suma_ventasNopagadas = 0;
								$suma_serviciosPagados = 0;
								$suma_serviciosNopagados = 0;
								$suma_pedidosRegistrados = 0;
								$cantidad_registros = 0;

								$query = "SELECT
									COD_USUARIO,
									NOMBRE_COMPLETO,
									CATEGORIA_USUARIO,
									CIUDAD,
									IFNULL(
										(select pais from paises where (codigo_pais = usuarios.codigo_pais))
										,''
									) PAIS,
									count(";
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "cod_usuario";
											break;
										case 2:
											$query .= "id_usuario";
											break;
										case 3:
											$query .= "categoria_usuario";
											break;
										case 4:
											$query .= "ciudad";
											break;
										case 5:
											$query .= "codigo_pais";
											break;
									}
								}
								$query .= ") CANTIDAD, ";

								// armo la condicion, segun el tipo de agrupamiento que se seleccione
								$agrupamiento = " and (id_cliente = usuarios.id_usuario) ";
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
										case 2:
											$agrupamiento = " and (id_cliente = usuarios.id_usuario) ";
											break;
										case 3:
											$agrupamiento = " and (categoria_usuario = usuarios.categoria_usuario) ";
											break;
										case 4:
											$agrupamiento = " and (ciudad = usuarios.ciudad) ";
											break;
										case 5:
											$agrupamiento = " and (codigo_pais = usuarios.codigo_pais) ";
											break;
									}
								}
								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$agrupamiento .= " and (categoria_usuario = '" . $_GET['cat'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$agrupamiento .= " and (codigo_pais = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$agrupamiento .= " and (ciudad = '" . $_GET['c'] . "') ";
								}

								$query .= "(select count(*) from ventas left join usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE where (tipo_venta = 'ventas') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) and (pagado = 1) " . $agrupamiento . " ) VENTAS_PAGADAS, ";
								$query .= "(select count(*) from ventas left join usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE where (tipo_venta = 'ventas') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) and (pagado = 0) " . $agrupamiento . " ) VENTAS_NOPAGADAS, ";
								$query .= "(select count(*) from ventas left join usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE where (tipo_venta = 'servicios') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) and (pagado = 1) " . $agrupamiento . " ) SERVICIOS_PAGADOS, ";
								$query .= "(select count(*) from ventas left join usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE where (tipo_venta = 'servicios') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) and (pagado = 0) " . $agrupamiento . " ) SERVICIOS_NOPAGADOS, ";
								$query .= "(select count(*) from ventas left join usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE where (tipo_venta = 'pedidos') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) " . $agrupamiento . " ) PEDIDOS_REGISTRADOS ";
								$query .= "FROM usuarios
									WHERE
										(tipo_usuario = 'Cliente')
										and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (activo = 1)
								";

								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (categoria_usuario = '" . $_GET['cat'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (codigo_pais = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$query .= " and (ciudad = '" . $_GET['c'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY cod_usuario ORDER BY cod_usuario";
											break;
										case 2:
											$query .= "GROUP BY id_usuario ORDER BY nombre_completo";
											break;
										case 3:
											$query .= "GROUP BY categoria_usuario ORDER BY categoria_usuario";
											break;
										case 4:
											$query .= "GROUP BY ciudad ORDER BY ciudad";
											break;
										case 5:
											$query .= "GROUP BY codigo_pais ORDER BY codigo_pais";
											break;
									}
								}

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["CANTIDAD"];
										$suma_ventasPagadas += $registros->fields["VENTAS_PAGADAS"];
										$suma_ventasNopagadas += $registros->fields["VENTAS_NOPAGADAS"];
										$suma_serviciosPagados += $registros->fields["SERVICIOS_PAGADOS"];
										$suma_serviciosNopagados += $registros->fields["SERVICIOS_NOPAGADOS"];
										$suma_pedidosRegistrados += $registros->fields["PEDIDOS_REGISTRADOS"];
								?>
										<tr>
											<td style="text-align: center;">
												<?php
												if (isset($_GET['ag']) and ($_GET['ag'] != "") and (($_GET['ag'] == "1") or ($_GET['ag'] == "2"))) {
													echo (sprintf("%06s", $registros->fields["COD_USUARIO"]));
												}
												?>
											</td>
											<td>
												<?php
												if (isset($_GET['ag']) and ($_GET['ag'] != "") and (($_GET['ag'] == "1") or ($_GET['ag'] == "2"))) {
													echo (stripslashes($registros->fields["NOMBRE_COMPLETO"]));
												}
												?>
											</td>
											<td><?php echo ($registros->fields["CATEGORIA_USUARIO"]); ?></td>
											<td><?php echo ($registros->fields["CIUDAD"]); ?></td>
											<td><?php echo ($registros->fields["PAIS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["VENTAS_PAGADAS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["VENTAS_NOPAGADAS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["SERVICIOS_PAGADOS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["SERVICIOS_NOPAGADOS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["PEDIDOS_REGISTRADOS"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["CANTIDAD"]); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_ventasPagadas, 0, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_ventasNopagadas, 0, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_serviciosPagados, 0, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_serviciosNopagados, 0, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_pedidosRegistrados, 0, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, 0, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Deudores
		if (isset($_GET['accion']) and ($_GET['accion'] == "pordeudores")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['v'])) {
				$_GET['v'] = 0;
			}
			?>
			<h3>Deudores</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="cat" id="cat" onchange="AbrirURL(this);" title="Filtrar por Categoría" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorías</option>
								<?php
								$query = "SELECT
											upper(categoria_usuario) CATEGORIA_USUARIO
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and (categoria_usuario is not null) and (categoria_usuario <> '')
										GROUP BY upper(categoria_usuario)
										ORDER BY upper(categoria_usuario)
									";

								$categoriasClientes = $temp->Query($query);
								if ($categoriasClientes and ($categoriasClientes->RecordCount() > 0)) {
									while (!$categoriasClientes->EOF) {
								?>
										<option value="<?php echo ($url . "&cat=" . $categoriasClientes->fields["CATEGORIA_USUARIO"]); ?>" <?php if (isset($_GET['cat']) and ($_GET['cat'] == $categoriasClientes->fields["CATEGORIA_USUARIO"])) {
																																				echo ("selected=\"selected\"");
																																			} ?>><?php echo (stripslashes($categoriasClientes->fields["CATEGORIA_USUARIO"])); ?></option>
								<?php
										$categoriasClientes->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar por País" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los paises</option>
								<?php
								$query = "SELECT paises.*
										FROM usuarios
										LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
									";
								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}
								$query .= "GROUP BY usuarios.codigo_pais ORDER BY pais";

								$datosPaises = $temp->Query($query);
								if ($datosPaises and ($datosPaises->RecordCount() > 0)) {
									while (!$datosPaises->EOF) {
								?>
										<option value="<?php echo ($url . "&p=" . $datosPaises->fields["CODIGO_PAIS"]); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == $datosPaises->fields["CODIGO_PAIS"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosPaises->fields["PAIS"])); ?></option>
								<?php
										$datosPaises->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Ciudad" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Ciudades</option>
								<?php
								$query = "SELECT
											trim(upper(ciudad)) CIUDAD
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and (ciudad is not null) and (ciudad <> '')
									";
								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (codigo_pais = '" . $_GET['p'] . "') ";
								}
								$query .= "GROUP BY trim(upper(ciudad)) ORDER BY trim(upper(ciudad))";

								$datosCiudades = $temp->Query($query);
								if ($datosCiudades and ($datosCiudades->RecordCount() > 0)) {
									while (!$datosCiudades->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $datosCiudades->fields["CIUDAD"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $datosCiudades->fields["CIUDAD"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosCiudades->fields["CIUDAD"])); ?></option>
								<?php
										$datosCiudades->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="v" id="v" onchange="AbrirURL(this);" title="Opciones de ventas" style="width: auto;">
								<option value="" disabled></option>
								<option value="<?php echo ($url . "&v="); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>Ocultar Ventas</option>
								<option value="<?php echo ($url . "&v=1"); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Mostrar ventas</option>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['v'])) {
								$url .= "&v=" . $_GET['v'];
							}
							?>
							<input type="hidden" id="obtenerDatos" value="<?php echo ($url); ?>" onclick="AbrirURL(this);" />
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Código</th>
									<th colspan="2">Nombres y Apellidos</th>
									<th>Categoría</th>
									<th>Ciudad</th>
									<th>País</th>
									<th>Total</th>
									<th>Recibido</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$suma_totalDeudas = 0;
								$suma_recibidoDeudas = 0;
								$suma_saldoDeudas = 0;

								$cantidad_registros = 0;

								$query = "SELECT
									COD_USUARIO,
									NOMBRE_COMPLETO,
									CATEGORIA_USUARIO,
									CIUDAD,
									IFNULL(
										(select pais from paises where (codigo_pais = usuarios.codigo_pais))
										,''
									) PAIS,
									(
										select
											sum(total)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) TOTAL_DEUDA,
									(
										select
											sum(recibido)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) RECIBIDO_DEUDA,
									(
										select
											sum(saldo)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) SALDO_DEUDA,
									ID_USUARIO
								FROM usuarios
								WHERE
									(tipo_usuario = 'Cliente')
									and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
									and (activo = 1)
								";

								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_totalDeudas += $registros->fields["TOTAL_DEUDA"];
										$suma_recibidoDeudas += $registros->fields["RECIBIDO_DEUDA"];
										$suma_saldoDeudas += $registros->fields["SALDO_DEUDA"];

										if ($registros->fields["TOTAL_DEUDA"] > 0) {
								?>
											<tr <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
													echo ("class='info'");
												} ?>>
												<td style="text-align: center;"><?php echo (sprintf("%04s", $registros->fields["COD_USUARIO"])); ?></td>
												<td colspan="2"><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
												<td><?php echo ($registros->fields["CATEGORIA_USUARIO"]); ?></td>
												<td><?php echo ($registros->fields["CIUDAD"]); ?></td>
												<td><?php echo ($registros->fields["PAIS"]); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
											</tr>
											<?php
											if (isset($_GET['v']) and ($_GET['v'] == "1")) {

												// obtengo datos de las VENTAS del CLIENTE
												$queryVentas = "SELECT
														COD_VENTA,
														NRO_CUOTA,
														FECHA_ALTA,
														FECHA_VENCIMIENTO,
														TOTAL,
														RECIBIDO,
														SALDO
													FROM ventas
													WHERE
														(tipo_venta = 'ventas') and
														(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
														(id_cliente = '" . $registros->fields["ID_USUARIO"] . "') and
														(eliminado = 0) and
														(pagado = 0)
												";
												$registrosVentas = $temp->Query($queryVentas);
												if ($registrosVentas and ($registrosVentas->RecordCount() > 0)) {
											?>
													<tr>
														<th></th>
														<th></th>
														<th>N&deg; Venta</th>
														<th>N&deg; Cuota</th>
														<th>Pago</th>
														<th>Vencimiento</th>
														<th>Total</th>
														<th>Recibido</th>
														<th>Saldo</th>
													</tr>
													<?php
													while (!$registrosVentas->EOF) {

													?>
														<tr class="warning">
															<td></td>
															<td></td>
															<td style="text-align: center;"><?php echo (sprintf("%04s", $registrosVentas->fields["COD_VENTA"])); ?></td>
															<td style="text-align: center;"><?php echo ($registrosVentas->fields["NRO_CUOTA"]); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_ALTA"], 3)); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["TOTAL"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["RECIBIDO"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["SALDO"], $cantidad_decimales, ',', '.')); ?></td>
														</tr>
								<?php

														$registrosVentas->MoveNext();
													}
												}
											}
										}
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="6" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;"><?php echo (number_format($suma_totalDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_recibidoDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_saldoDeudas, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Deudores x mes
		if (isset($_GET['accion']) and ($_GET['accion'] == "pordeudoresxmes")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['cc'])) {
				$_GET['cc'] = 3;
			}
			if (!isset($_GET['v'])) {
				$_GET['v'] = 0;
			}
			?>
			<h3>Deudores según los meses</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="cc" onchange="AbrirURL(this);" title="Filtrar por la cantidad de cuotas" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['cc'])) {
																			echo ("selected='selected'");
																		} ?>>Todo</option>
								<option value="<?php echo ($url . "&cc=1"); ?>" <?php if (isset($_GET['cc']) && ($_GET['cc'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Deben 1 mes</option>
								<option value="<?php echo ($url . "&cc=2"); ?>" <?php if (isset($_GET['cc']) && ($_GET['cc'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>Deben 2 meses</option>
								<option value="<?php echo ($url . "&cc=3"); ?>" <?php if (isset($_GET['cc']) && ($_GET['cc'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>Deben +3 meses</option>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cc'])) {
								$url .= "&cc=" . $_GET['cc'];
							}
							?>
							<select name="cat" id="cat" onchange="AbrirURL(this);" title="Filtrar por Categoría" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorías</option>
								<?php
								$query = "SELECT
											upper(categoria_usuario) CATEGORIA_USUARIO
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and (categoria_usuario is not null) and (categoria_usuario <> '')
										GROUP BY upper(categoria_usuario)
										ORDER BY upper(categoria_usuario)
									";

								$categoriasClientes = $temp->Query($query);
								if ($categoriasClientes and ($categoriasClientes->RecordCount() > 0)) {
									while (!$categoriasClientes->EOF) {
								?>
										<option value="<?php echo ($url . "&cat=" . $categoriasClientes->fields["CATEGORIA_USUARIO"]); ?>" <?php if (isset($_GET['cat']) and ($_GET['cat'] == $categoriasClientes->fields["CATEGORIA_USUARIO"])) {
																																				echo ("selected=\"selected\"");
																																			} ?>><?php echo (stripslashes($categoriasClientes->fields["CATEGORIA_USUARIO"])); ?></option>
								<?php
										$categoriasClientes->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar por País" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los paises</option>
								<?php
								$query = "SELECT paises.*
										FROM usuarios
										LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
									";
								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}
								$query .= "GROUP BY usuarios.codigo_pais ORDER BY pais";

								$datosPaises = $temp->Query($query);
								if ($datosPaises and ($datosPaises->RecordCount() > 0)) {
									while (!$datosPaises->EOF) {
								?>
										<option value="<?php echo ($url . "&p=" . $datosPaises->fields["CODIGO_PAIS"]); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == $datosPaises->fields["CODIGO_PAIS"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosPaises->fields["PAIS"])); ?></option>
								<?php
										$datosPaises->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Ciudad" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Ciudades</option>
								<?php
								$query = "SELECT
											trim(upper(ciudad)) CIUDAD
										FROM usuarios
										WHERE
											(tipo_usuario = 'Cliente')
											and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (activo = 1)
											and (ciudad is not null) and (ciudad <> '')
									";
								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= " and (codigo_pais = '" . $_GET['p'] . "') ";
								}
								$query .= "GROUP BY trim(upper(ciudad)) ORDER BY trim(upper(ciudad))";

								$datosCiudades = $temp->Query($query);
								if ($datosCiudades and ($datosCiudades->RecordCount() > 0)) {
									while (!$datosCiudades->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $datosCiudades->fields["CIUDAD"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $datosCiudades->fields["CIUDAD"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($datosCiudades->fields["CIUDAD"])); ?></option>
								<?php
										$datosCiudades->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['cat'])) {
								$url .= "&cat=" . $_GET['cat'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="v" id="v" onchange="AbrirURL(this);" title="Opciones de ventas" style="width: auto;">
								<option value="" disabled></option>
								<option value="<?php echo ($url . "&v="); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>Ocultar Ventas</option>
								<option value="<?php echo ($url . "&v=1"); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Mostrar ventas</option>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['v'])) {
								$url .= "&v=" . $_GET['v'];
							}
							?>
							<input type="hidden" id="obtenerDatos" value="<?php echo ($url); ?>" onclick="AbrirURL(this);" />
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Código</th>
									<th colspan="2">Nombres y Apellidos</th>
									<th>Categoría</th>
									<th>Ciudad</th>
									<th>País</th>
									<th>Cuotas</th>
									<th>Total</th>
									<th>Recibido</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$suma_totalDeudas = 0;
								$suma_recibidoDeudas = 0;
								$suma_saldoDeudas = 0;

								$cantidad_registros = 0;

								$query = "SELECT
									COD_USUARIO,
									NOMBRE_COMPLETO,
									CATEGORIA_USUARIO,
									CIUDAD,
									IFNULL(
										(select pais from paises where (codigo_pais = usuarios.codigo_pais))
										,''
									) PAIS,
									(
										select
											count(id_venta)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) CANTIDAD_CUOTAS,
									(
										select
											sum(total)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) TOTAL_DEUDA,
									(
										select
											sum(recibido)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) RECIBIDO_DEUDA,
									(
										select
											sum(saldo)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
									) SALDO_DEUDA,
									ID_USUARIO
								FROM usuarios
								WHERE
									(tipo_usuario = 'Cliente')
									and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
									and (activo = 1)
								";

								if (isset($_GET['cat']) and ($_GET['cat'] != "")) {
									$query .= " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') ";
								}

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {

										if (($registros->fields["TOTAL_DEUDA"] > 0)) {

											$condicion = false;
											switch ($_GET['cc']) {
												case "1":
													eval('$condicion = (' . $registros->fields["CANTIDAD_CUOTAS"] . ' == 1);');
													break;
												case "2":
													eval('$condicion = (' . $registros->fields["CANTIDAD_CUOTAS"] . ' == 2);');
													break;
												case "3":
													eval('$condicion = (' . $registros->fields["CANTIDAD_CUOTAS"] . ' >= 3);');
													break;
											}

											if (isset($_GET['cc']) && $condicion === true) {

												$suma_totalDeudas += $registros->fields["TOTAL_DEUDA"];
												$suma_recibidoDeudas += $registros->fields["RECIBIDO_DEUDA"];
												$suma_saldoDeudas += $registros->fields["SALDO_DEUDA"];

								?>
												<tr <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
														echo ("class='info'");
													} ?>>
													<td style="text-align: center;"><?php echo (sprintf("%04s", $registros->fields["COD_USUARIO"])); ?></td>
													<td colspan="2"><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
													<td><?php echo ($registros->fields["CATEGORIA_USUARIO"]); ?></td>
													<td><?php echo ($registros->fields["CIUDAD"]); ?></td>
													<td><?php echo ($registros->fields["PAIS"]); ?></td>
													<td style="text-align: center;"><?php echo ($registros->fields["CANTIDAD_CUOTAS"]); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
													<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
												</tr>
												<?php
											}

											if (isset($_GET['v']) and ($_GET['v'] == "1")) {

												// obtengo datos de las VENTAS del CLIENTE
												$queryVentas = "SELECT
														COD_VENTA,
														NRO_CUOTA,
														FECHA_ALTA,
														FECHA_VENCIMIENTO,
														TOTAL,
														RECIBIDO,
														SALDO
													FROM ventas
													WHERE
														(tipo_venta = 'ventas') and
														(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
														(id_cliente = '" . $registros->fields["ID_USUARIO"] . "') and
														(eliminado = 0) and
														(pagado = 0)
												";
												$registrosVentas = $temp->Query($queryVentas);
												if ($registrosVentas and ($registrosVentas->RecordCount() > 0)) {
												?>
													<tr>
														<th></th>
														<th></th>
														<th>N&deg; Venta</th>
														<th>N&deg; Cuota</th>
														<th>Pago</th>
														<th>Vencimiento</th>
														<th>Total</th>
														<th>Recibido</th>
														<th>Saldo</th>
													</tr>
													<?php
													while (!$registrosVentas->EOF) {

													?>
														<tr class="warning">
															<td></td>
															<td></td>
															<td style="text-align: center;"><?php echo (sprintf("%04s", $registrosVentas->fields["COD_VENTA"])); ?></td>
															<td style="text-align: center;"><?php echo ($registrosVentas->fields["NRO_CUOTA"]); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_ALTA"], 3)); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["TOTAL"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["RECIBIDO"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["SALDO"], $cantidad_decimales, ',', '.')); ?></td>
														</tr>
								<?php

														$registrosVentas->MoveNext();
													}
												}
											}
										}
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="7" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;"><?php echo (number_format($suma_totalDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_recibidoDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_saldoDeudas, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Deudores x cuota
		if (isset($_GET['accion']) and ($_GET['accion'] == "pordeudoresxcuota")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			?>
			<h3>Deudores por Cuota</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<form method="get" action="<?php echo ($_SERVER['PHP_SELF']); ?>" style="margin-bottom: 0px;">
								<input type="hidden" name="menu" value="<?php echo ($_GET['menu']); ?>">
								<input type="hidden" name="accion" value="<?php echo ($_GET['accion']); ?>">

								<?php
								$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
								?>
								<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
									<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
									<?php
										$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
										$datosRegistros = $temp->Query($query);

										if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
											while (!$datosRegistros->EOF) {
												?>
												<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
													<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
												</option>
												<?php
												$datosRegistros->MoveNext();
											}
										}
									?>
								</select>

								<select name="cat" style="width: auto;">
									<option value="">Todas las Categorías</option>
									<?php
									$query = "SELECT
												upper(categoria_usuario) CATEGORIA_USUARIO
											FROM usuarios
											WHERE
												(tipo_usuario = 'Cliente')
												and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
												and (activo = 1)
												and (categoria_usuario is not null) and (categoria_usuario <> '')
											GROUP BY upper(categoria_usuario)
											ORDER BY upper(categoria_usuario)
										";

									$categoriasClientes = $temp->Query($query);
									if ($categoriasClientes and ($categoriasClientes->RecordCount() > 0)) {
										while (!$categoriasClientes->EOF) {
									?>
											<option value="<?php echo ($categoriasClientes->fields["CATEGORIA_USUARIO"]); ?>" <?php if (isset($_GET['cat']) and ($_GET['cat'] == $categoriasClientes->fields["CATEGORIA_USUARIO"])) {
																																	echo ("selected=\"selected\"");
																																} ?>>
												<?php echo (stripslashes($categoriasClientes->fields["CATEGORIA_USUARIO"])); ?>
											</option>
									<?php
											$categoriasClientes->MoveNext();
										}
									}
									?>
								</select>
								<div class="input-append">
									<input type="text" name="q" value="<?php if (isset($_GET['cat'])) {
																			echo ($_GET['q']);
																		} ?>" placeholder="buscar..." />
									<button type="submit" class="btn btn-primary">obtener datos</button>
								</div>

								<div class="btn-group" style="margin-top: -10px;">
									<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
										<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
									</ul>
								</div>
								<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>

							</form>
						</div>

						<?php
						$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
						$url .= isset($_GET['cat']) && !empty($_GET['cat']) ? '&cat=' . $_GET['cat'] : '';
						$url .= isset($_GET['q']) && !empty($_GET['q']) ? '&q=' . $_GET['q'] : '';
						?>

						<?php
						/*
							$categoria_set = isset($_GET['cat']) && !empty($_GET['cat']) ? ' and (upper(categoria_usuario) = "'.$_GET['cat'].'") ' : '';
							$search_set = '';
							if(isset($_GET['q']) && !empty($_GET['q'])){
								$codigo = sprintf("%04s",trim($_GET['q']));

								$search_set = ' and (
									(upper(concat(repeat("0",4-length(COD_USUARIO)),COD_USUARIO)) = "'.$codigo.'") or
									(upper(nombre_completo) LIKE upper("%'.$_GET['q'].'%")) or
									(upper(domicilio) LIKE upper("%'.$_GET['q'].'%"))
								) ';
							}
							$order_set = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c']." ".$_GET['o'] : 'nombre_completo';

							echo($categoria_set.'|'.$search_set.'|'.$order_set);

							$query = "CALL obtenerDeudores('".$_SESSION['usuario_sucursal']."','".$categoria_set."','".$search_set."','".$order_set."');";
							$registros = $conexion->Execute($query);
							$columnas = $registros->fieldCount();
							*/
						?>

						<table id="listado" class="table table-striped table-bordered table-condensed" style="display: none;">
							<thead>
								<tr>
									<th>#</th>
									<?php
									for ($colIndex = 0; $colIndex < $columnas; $colIndex++) {
										$fieldInfo = $registros->fetchField($colIndex);
										$type = $registros->metaType($fieldInfo->type);


										if ($colIndex < 4) {

											$estilo = "";
											$o = "asc";
											if (isset($_GET['c']) && ($_GET['c'] == $fieldInfo->name)) {
												$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
												$estilo = $o;
											}

											echo ('<th class="' . $estilo . '"><a href="' . $url . '&c=' . $fieldInfo->name . '&o=' . $o . '">' . $fieldInfo->name . '</a></th>');
										} else {
											echo ('<th>' . $fieldInfo->name . '</th>');
										}
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								while (!$registros->EOF) {
									echo ('<tr>');
									echo ('<td style="text-align: center;">' . $i . '</td>');

									for ($colIndex = 0; $colIndex < $columnas; $colIndex++) {
										$fieldInfo = $registros->fetchField($colIndex);

										if ($colIndex > 4) {
											$bg_set = $registros->fields[$fieldInfo->name] == 'PAGADO' ?
												'background-color: #d4edda;' : ($registros->fields[$fieldInfo->name] == 'DEBE' ?
													'background-color: #f8d7da;' : 'background-color: #fff3cd;');

											$content_set = '';
											switch ($registros->fields[$fieldInfo->name]) {
												case "PAGADO":
													$content_set = '<i class="icon-ok"></i>';
													break;
												case "DEBE":
													$content_set = '<i class="icon-remove"></i>';
													break;
												case "PENDIENTE":
													$content_set = '';
													break;
											}

											echo ('<td style="' . $bg_set . '">' . $content_set . '</td>');
										} else {
											$bg_set = '';
											echo ('<td>' . $registros->fields[$fieldInfo->name] . '</td>');
										}
									}
									echo ('</tr>');

									$registros->MoveNext();
									$i++;
								}
								?>
							</tbody>
						</table>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="40">#</th>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'COD_USUARIO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?> width="80"><a href="<?php echo ($url . '&c=COD_USUARIO&o=' . $o); ?>">Código</a></th>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'NOMBRE_COMPLETO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?>><a href="<?php echo ($url . '&c=NOMBRE_COMPLETO&o=' . $o); ?>">Nombres y Apellidos</a></th>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'DOMICILIO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?>><a href="<?php echo ($url . '&c=DOMICILIO&o=' . $o); ?>">Domicilio</a></th>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'FECHA_INICIO_SERVICIO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?>><a href="<?php echo ($url . '&c=FECHA_INICIO_SERVICIO&o=' . $o); ?>">Fecha Inicio</a></th>
									<th>Cuotas</th>
									<th>1</th>
									<th>2</th>
									<th>3</th>
									<th>4</th>
									<th>5</th>
									<th>6</th>
									<th>7</th>
									<th>8</th>
									<th>9</th>
									<th>10</th>
									<th>11</th>
									<th>12</th>
									<th>13</th>
									<th>14</th>
									<th>15</th>
									<th>16</th>
									<th>17</th>
									<th>18</th>
									<th>19</th>
									<th>20</th>
									<th>21</th>
									<th>22</th>
									<th>23</th>
									<th>24</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$cantidad_registros = 0;

								$categoria_set = isset($_GET['cat']) && !empty($_GET['cat']) ? " and (upper(categoria_usuario) = '" . $_GET['cat'] . "') " : '';
								if (isset($_GET['q']) && !empty($_GET['q'])) {
									$codigo = sprintf("%04s", trim($_GET['q']));

									$search_set = " and (
										(upper(concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO)) = '" . $codigo . "') or
										(upper(nombre_completo) LIKE upper('%" . $_GET['q'] . "%')) or
										(upper(domicilio) LIKE upper('%" . $_GET['q'] . "%'))
									) ";
								}
								$order_set = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] . " " . $_GET['o'] : 'nombre_completo';

								$query = "SELECT
										COD_USUARIO,
										NOMBRE_COMPLETO,
										upper(domicilio) DOMICILIO,
										FECHA_INICIO_SERVICIO,
										count(nro_cuota) CUOTAS,
										MAX(CASE WHEN nro_cuota = 1 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA1,
										MAX(CASE WHEN nro_cuota = 2 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA2,
										MAX(CASE WHEN nro_cuota = 3 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA3,
										MAX(CASE WHEN nro_cuota = 4 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA4,
										MAX(CASE WHEN nro_cuota = 5 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA5,
										MAX(CASE WHEN nro_cuota = 6 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA6,
										MAX(CASE WHEN nro_cuota = 7 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA7,
										MAX(CASE WHEN nro_cuota = 8 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA8,
										MAX(CASE WHEN nro_cuota = 9 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA9,
										MAX(CASE WHEN nro_cuota = 10 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA10,
										MAX(CASE WHEN nro_cuota = 11 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA11,
										MAX(CASE WHEN nro_cuota = 12 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA12,
										MAX(CASE WHEN nro_cuota = 13 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA13,
										MAX(CASE WHEN nro_cuota = 14 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA14,
										MAX(CASE WHEN nro_cuota = 15 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA15,
										MAX(CASE WHEN nro_cuota = 16 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA16,
										MAX(CASE WHEN nro_cuota = 17 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA17,
										MAX(CASE WHEN nro_cuota = 18 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA18,
										MAX(CASE WHEN nro_cuota = 19 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA19,
										MAX(CASE WHEN nro_cuota = 20 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA20,
										MAX(CASE WHEN nro_cuota = 21 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA21,
										MAX(CASE WHEN nro_cuota = 22 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA22,
										MAX(CASE WHEN nro_cuota = 23 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA23,
										MAX(CASE WHEN nro_cuota = 24 THEN IF(pagado=1,'PAGADO',IF(fecha_vencimiento < CURRENT_DATE(),'DEBE','PENDIENTE')) ELSE '' END) AS CUOTA24
									FROM usuarios
									LEFT JOIN ventas ON (ventas.ID_CLIENTE = usuarios.ID_USUARIO)
									WHERE
										(usuarios.ID_SUCURSAL = '" . $_SESSION['usuario_sucursal'] . "')
										and (TIPO_USUARIO = 'Cliente')
										and (TIPO_VENTA = 'ventas')
										and (ELIMINADO = 0)
										and (usuarios.activo = 1)
										{$categoria_set}
										{$search_set}
									GROUP BY usuarios.ID_USUARIO
									ORDER BY {$order_set}
								";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
								?>
										<tr>
											<td style="text-align: center;"><?php echo ($i); ?></td>
											<td style="text-align: center;"><?php echo (sprintf("%04s", $registros->fields["COD_USUARIO"])); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td><?php echo ($registros->fields["DOMICILIO"]); ?></td>
											<td><?php echo (formato_fecha($registros->fields["FECHA_INICIO_SERVICIO"], 3)); ?></td>
											<td style="text-align: center;"><?php echo ($registros->fields["CUOTAS"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA1"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA1"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA1"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA2"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA2"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA2"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA3"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA3"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA3"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA4"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA4"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA4"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA5"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA5"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA5"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA6"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA6"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA6"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA7"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA7"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA7"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA8"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA8"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA8"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA9"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA9"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA9"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA10"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA10"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA10"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA11"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA11"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA11"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA12"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA12"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA12"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA13"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA13"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA13"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA14"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA14"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA14"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA15"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA15"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA15"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA16"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA16"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA16"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA17"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA17"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA17"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA18"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA18"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA18"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA19"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA19"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA19"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA20"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA20"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA20"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA21"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA21"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA21"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA22"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA22"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA22"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA23"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA23"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA23"]); ?></td>
											<?php
											$bg_set = $registros->fields["CUOTA24"] == 'PAGADO' ? 'background-color: #d4edda;' : ($registros->fields["CUOTA24"] == 'DEBE' ? 'background-color: #f8d7da;' : 'background-color: #fff3cd;');
											?>
											<td style="text-align: center;<?php echo ($bg_set); ?>"><?php echo ($registros->fields["CUOTA24"]); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
										$i++;
									}
								}
								?>
							</tbody>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Vencimientos
		if (isset($_GET['accion']) and ($_GET['accion'] == "porvencimientos")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['a'])) {
				$_GET['a'] = date('Y');
			}
			if (!isset($_GET['v'])) {
				$_GET['v'] = 0;
			}
			?>
			<h3>Vencimientos mensuales por Cliente</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</br><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="a" id="a" onchange="AbrirURL(this);" title="Filtrar por Año" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los Años</option>
								<?php
								$query = "SELECT
											year(fecha_vencimiento) ANO
										FROM ventas
										WHERE
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(eliminado = 0) and
											(pagado = 0) and
											(fecha_vencimiento is not null)
										GROUP BY year(fecha_vencimiento)
										ORDER BY year(fecha_vencimiento) DESC
									";

								$anosVentas = $temp->Query($query);
								if ($anosVentas and ($anosVentas->RecordCount() > 0)) {
									while (!$anosVentas->EOF) {
								?>
										<option value="<?php echo ($url . "&a=" . $anosVentas->fields["ANO"]); ?>" <?php if (isset($_GET['a']) and ($_GET['a'] == $anosVentas->fields["ANO"])) {
																														echo ("selected=\"selected\"");
																													} ?>><?php echo (stripslashes($anosVentas->fields["ANO"])); ?></option>
								<?php
										$anosVentas->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['a'])) {
								$url .= "&a=" . $_GET['a'];
							}
							?>
							<select name="m" id="m" onchange="AbrirURL(this);" title="Filtrar por Mes" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los Meses</option>
								<?php
								$query = "SELECT
											month(fecha_vencimiento) MES
										FROM ventas
										WHERE
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(eliminado = 0) and
											(pagado = 0) and
											(fecha_vencimiento is not null)
									";
								if (isset($_GET['a']) and ($_GET['a'] != "")) {
									$query .= " and (year(fecha_vencimiento) = '" . $_GET['a'] . "') ";
								}
								$query .= "
										GROUP BY month(fecha_vencimiento)
										ORDER BY month(fecha_vencimiento)
									";

								$mesesVentas = $temp->Query($query);
								if ($mesesVentas and ($mesesVentas->RecordCount() > 0)) {
									while (!$mesesVentas->EOF) {
								?>
										<option value="<?php echo ($url . "&m=" . $mesesVentas->fields["MES"]); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == $mesesVentas->fields["MES"])) {
																														echo ("selected=\"selected\"");
																													} ?>><?php echo (stripslashes($mes[sprintf("%02s", $mesesVentas->fields["MES"])])); ?></option>
								<?php
										$mesesVentas->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['a'])) {
								$url .= "&a=" . $_GET['a'];
							}
							if (isset($_GET['m'])) {
								$url .= "&m=" . $_GET['m'];
							}
							?>
							<select name="v" id="v" onchange="AbrirURL(this);" title="Opciones de ventas" style="width: auto;">
								<option value="" disabled></option>
								<option value="<?php echo ($url . "&v="); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>Ocultar Ventas</option>
								<option value="<?php echo ($url . "&v=1"); ?>" <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Mostrar ventas</option>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['v'])) {
								$url .= "&v=" . $_GET['v'];
							}
							?>
							<input type="hidden" id="obtenerDatos" value="<?php echo ($url); ?>" onclick="AbrirURL(this);" />
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Código</th>
									<th colspan="2">Nombres y Apellidos</th>
									<th>Categoría</th>
									<th>Ciudad</th>
									<th>País</th>
									<th>Total</th>
									<th>Recibido</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$suma_totalDeudas = 0;
								$suma_recibidoDeudas = 0;
								$suma_saldoDeudas = 0;

								$cantidad_registros = 0;

								$queryVencimiento = "";
								if (isset($_GET['a']) and ($_GET['a'] != "")) {
									$queryVencimiento .= " and (year(fecha_vencimiento) = '" . $_GET['a'] . "') ";
								}
								if (isset($_GET['m']) and ($_GET['m'] != "")) {
									$queryVencimiento .= " and (month(fecha_vencimiento) = '" . $_GET['m'] . "') ";
								}

								$query = "SELECT
									COD_USUARIO,
									NOMBRE_COMPLETO,
									CATEGORIA_USUARIO,
									CIUDAD,
									IFNULL(
										(select pais from paises where (codigo_pais = usuarios.codigo_pais))
										,''
									) PAIS,
									(
										select
											sum(total)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
											" . $queryVencimiento . "
									) TOTAL_DEUDA,
									(
										select
											sum(recibido)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
											" . $queryVencimiento . "
									) RECIBIDO_DEUDA,
									(
										select
											sum(saldo)
										from ventas
										where
											(tipo_venta = 'ventas') and
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
											(id_cliente = usuarios.ID_USUARIO) and
											(eliminado = 0) and
											(pagado = 0)
											" . $queryVencimiento . "
									) SALDO_DEUDA,
									ID_USUARIO
								FROM usuarios
								WHERE
									(tipo_usuario = 'Cliente')
									and (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
									and (activo = 1)
								";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_totalDeudas += $registros->fields["TOTAL_DEUDA"];
										$suma_recibidoDeudas += $registros->fields["RECIBIDO_DEUDA"];
										$suma_saldoDeudas += $registros->fields["SALDO_DEUDA"];

										if ($registros->fields["TOTAL_DEUDA"] > 0) {
								?>
											<tr <?php if (isset($_GET['v']) and ($_GET['v'] == "1")) {
													echo ("class='info'");
												} ?>>
												<td style="text-align: center;"><?php echo (sprintf("%04s", $registros->fields["COD_USUARIO"])); ?></td>
												<td colspan="2"><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
												<td><?php echo ($registros->fields["CATEGORIA_USUARIO"]); ?></td>
												<td><?php echo ($registros->fields["CIUDAD"]); ?></td>
												<td><?php echo ($registros->fields["PAIS"]); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["RECIBIDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
												<td style="text-align: right;"><?php echo (number_format($registros->fields["SALDO_DEUDA"], $cantidad_decimales, ',', '.')); ?></td>
											</tr>
											<?php
											if (isset($_GET['v']) and ($_GET['v'] == "1")) {

												// obtengo datos de las VENTAS del CLIENTE
												$queryVentas = "SELECT
														COD_VENTA,
														NRO_CUOTA,
														FECHA_ALTA,
														FECHA_VENCIMIENTO,
														TOTAL,
														RECIBIDO,
														SALDO
													FROM ventas
													WHERE
														(tipo_venta = 'ventas') and
														(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and
														(id_cliente = '" . $registros->fields["ID_USUARIO"] . "') and
														(eliminado = 0) and
														(pagado = 0)
														" . $queryVencimiento . "
												";
												$registrosVentas = $temp->Query($queryVentas);
												if ($registrosVentas and ($registrosVentas->RecordCount() > 0)) {
											?>
													<tr>
														<th></th>
														<th></th>
														<th>N&deg; Venta</th>
														<th>N&deg; Cuota</th>
														<th>Pago</th>
														<th>Vencimiento</th>
														<th>Total</th>
														<th>Recibido</th>
														<th>Saldo</th>
													</tr>
													<?php
													while (!$registrosVentas->EOF) {

													?>
														<tr class="warning">
															<td></td>
															<td></td>
															<td style="text-align: center;"><?php echo (sprintf("%04s", $registrosVentas->fields["COD_VENTA"])); ?></td>
															<td style="text-align: center;"><?php echo ($registrosVentas->fields["NRO_CUOTA"]); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_ALTA"], 3)); ?></td>
															<td><?php echo (formato_fecha($registrosVentas->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["TOTAL"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["RECIBIDO"], $cantidad_decimales, ',', '.')); ?></td>
															<td style="text-align: right;"><?php echo (number_format($registrosVentas->fields["SALDO"], $cantidad_decimales, ',', '.')); ?></td>
														</tr>
								<?php

														$registrosVentas->MoveNext();
													}
												}
											}
										}
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="6" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;"><?php echo (number_format($suma_totalDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_recibidoDeudas, $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;"><?php echo (number_format($suma_saldoDeudas, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// DATOS -> PRODUCTOS ---------

		// Productos
		if (isset($_GET['accion']) && ($_GET['accion'] == "porproductos")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['tc'])) {
				$_GET['tc'] = "codigo";
			}
			if (!isset($_GET['to'])) {
				$_GET['to'] = "asc";
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = "3";
			}
			?>
			<h3>Productos</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										console.log('tipografico');
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Categoria:</strong> " + $("#c option:selected").text() + " / <strong>Genero:</strong> " + $("#g option:selected").text()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + this.y + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 1) { //	obtengo la segunda columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 5) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 5] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 1) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 5) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 5].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar por Tipo" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) { echo ("selected='selected'"); } ?>>Todos los tipos</option>
								<option value=""></option>
								<?php
								$tiposProductos = $temp->Query("SELECT TIPO_PRODUCTO
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (tipo_producto is not null) and (tipo_producto <> '')
										GROUP BY tipo_producto
										ORDER BY tipo_producto
									");

								if ($tiposProductos and ($tiposProductos->RecordCount() > 0)) {
									while (!$tiposProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&t=" . $tiposProductos->fields["TIPO_PRODUCTO"]); ?>" <?php if (isset($_GET['t']) and ($_GET['t'] == $tiposProductos->fields["TIPO_PRODUCTO"])) {
																																		echo ("selected=\"selected\"");
																																	} ?>><?php echo (stripslashes($tiposProductos->fields["TIPO_PRODUCTO"])); ?></option>
								<?php
										$tiposProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['c'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorias</option>
								<option value=""></option>
								<?php
								$query = "SELECT CATEGORIA
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (categoria is not null) and (categoria <> '')
										";
								if (isset($_GET['t']) and ($_GET['t'] != "")) {
									$query .= " and (tipo_producto = '" . $_GET['t'] . "') ";
								}
								$query .= "GROUP BY categoria
										ORDER BY categoria";
								$categoriasProductos = $temp->Query($query);
								if ($categoriasProductos and ($categoriasProductos->RecordCount() > 0)) {
									while (!$categoriasProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $categoriasProductos->fields["CATEGORIA"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $categoriasProductos->fields["CATEGORIA"])) {
																																		echo ("selected=\"selected\"");
																																	} ?>><?php echo (stripslashes($categoriasProductos->fields["CATEGORIA"])); ?></option>
								<?php
										$categoriasProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['g'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los G&eacute;neros</option>
								<option value=""></option>
								<?php
								$query = "SELECT GENERO FROM productos
										WHERE (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (eliminado = 0) and (activo = 1) and (genero is not null) and (genero <> '') ";
								if (isset($_GET['t']) and ($_GET['t'] != "")) {
									$query .= " and (tipo_producto = '" . $_GET['t'] . "') ";
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$query .= " and (categoria = '" . $_GET['c'] . "') ";
								}
								$query .= "GROUP BY genero
										ORDER BY genero";

								$generosProductos = $temp->Query($query);
								if ($generosProductos and ($generosProductos->RecordCount() > 0)) {
									while (!$generosProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&g=" . $generosProductos->fields["GENERO"]); ?>" <?php if (isset($_GET['g']) and ($_GET['g'] == $generosProductos->fields["GENERO"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($generosProductos->fields["GENERO"])); ?></option>
								<?php
										$generosProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							if (isset($_GET['g'])) {
								$url .= "&g=" . $_GET['g'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Código</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Nombre</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Tipo</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Categoría</option>
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "5")) {
																					echo ("selected='selected'");
																				} ?>>por Genero</option>
							</select>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<?php
									$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
									if (isset($_GET['t'])) {
										$url .= "&t=" . $_GET['t'];
									}
									if (isset($_GET['c'])) {
										$url .= "&c=" . $_GET['c'];
									}
									if (isset($_GET['g'])) {
										$url .= "&g=" . $_GET['g'];
									}
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "codigo")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="80" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=codigo&to=" . $to); ?>">C&oacute;digo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=nombre&to=" . $to); ?>">Nombre</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "tipo_producto")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=tipo_producto&to=" . $to); ?>">Tipo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "categoria")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=categoria&to=" . $to); ?>">Categor&iacute;a</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "genero")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=genero&to=" . $to); ?>">G&eacute;nero</a></th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT *,count(";
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "codigo";
											break;
										case 2:
											$query .= "nombre";
											break;
										case 3:
											$query .= "tipo_producto";
											break;
										case 4:
											$query .= "categoria";
											break;
										case 5:
											$query .= "genero";
											break;
									}
								}
								$query .= ") CANTIDAD
									FROM productos
									WHERE
										(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (eliminado = 0)
										and (activo = 1)
								";

								if (isset($_GET['t']) and ($_GET['t'] != "")) {
									$query .= " and (tipo_producto = '" . $_GET['t'] . "') ";
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$query .= " and (categoria = '" . $_GET['c'] . "') ";
								}
								if (isset($_GET['g']) and ($_GET['g'] != "")) {
									$query .= "and (genero = '" . $_GET['g'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY codigo ";
											break;
										case 2:
											$query .= "GROUP BY nombre ";
											break;
										case 3:
											$query .= "GROUP BY tipo_producto ";
											break;
										case 4:
											$query .= "GROUP BY categoria ";
											break;
										case 5:
											$query .= "GROUP BY genero ";
											break;
									}
								}
								$query .= " ORDER BY " . $_GET['tc'] . " " . $_GET['to'];

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								while (!$registros->EOF) {
									$suma_cantidades += $registros->fields["CANTIDAD"];
									?>
									<tr>
										<td><?php echo ($registros->fields["CODIGO"]); ?></td>
										<td><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
										<td><?php echo ($registros->fields["TIPO_PRODUCTO"]); ?></td>
										<td><?php echo ($registros->fields["CATEGORIA"]); ?></td>
										<td><?php echo ($registros->fields["GENERO"]); ?></td>
										<td style="text-align: right;"><?php echo ($registros->fields["CANTIDAD"]); ?></td>
									</tr>
									<?php
									$registros->MoveNext();
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, 0, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Productos a pedir
		if (isset($_GET['accion']) and ($_GET['accion'] == "porproductosapedir")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['tc'])) {
				$_GET['tc'] = "codigo";
			}
			if (!isset($_GET['to'])) {
				$_GET['to'] = "asc";
			}
			?>
			<h3>Productos a pedir</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Categoria:</strong> " + $("#c option:selected").text() + " / <strong>Genero:</strong> " + $("#g option:selected").text()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + this.y + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 1) { //	obtengo la segunda columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 5) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 5] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 1) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 5) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 5].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="sm" id="sm" onchange="AbrirURL(this);" title="Filtrar por Stock Minimo" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Con Stock m&iacute;nimo</option>
								<option value="<?php echo ($url . "&sm=0"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 0)) {
																					echo ("selected='selected'");
																				} ?>>Sin Stock m&aacute;nimo</option>
								<option value=""></option>
								<?php
								$stockminimos = $temp->Query("SELECT STOCK_MINIMO
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (stock_minimo is not null) and (stock_minimo <> '')
										GROUP BY stock_minimo
										ORDER BY stock_minimo");

								if ($stockminimos and ($stockminimos->RecordCount() > 0)) {
									while (!$stockminimos->EOF) {
								?>
										<option value="<?php echo ($url . "&sm=" . $stockminimos->fields["STOCK_MINIMO"]); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == $stockminimos->fields["STOCK_MINIMO"])) {
																																	echo ("selected=\"selected\"");
																																} ?>><?php echo (stripslashes($stockminimos->fields["STOCK_MINIMO"])); ?></option>
								<?php
										$stockminimos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['sm'])) {
								$url .= "&sm=" . $_GET['sm'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['c'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorias</option>
								<option value=""></option>
								<?php
								$categoriasProductos = $temp->Query("SELECT CATEGORIA
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (categoria is not null) and (categoria <> '')
										GROUP BY categoria
										ORDER BY categoria
									");

								if ($categoriasProductos and ($categoriasProductos->RecordCount() > 0)) {
									while (!$categoriasProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $categoriasProductos->fields["CATEGORIA"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $categoriasProductos->fields["CATEGORIA"])) {
																																		echo ("selected=\"selected\"");
																																	} ?>><?php echo (stripslashes($categoriasProductos->fields["CATEGORIA"])); ?></option>
								<?php
										$categoriasProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['sm'])) {
								$url .= "&sm=" . $_GET['sm'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['g'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los G&eacute;neros</option>
								<option value=""></option>
								<?php
								$generosProductos = $temp->Query("SELECT GENERO
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (genero is not null) and (genero <> '')
										GROUP BY genero
										ORDER BY genero");

								if ($generosProductos and ($generosProductos->RecordCount() > 0)) {
									while (!$generosProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&g=" . $generosProductos->fields["GENERO"]); ?>" <?php if (isset($_GET['g']) and ($_GET['g'] == $generosProductos->fields["GENERO"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($generosProductos->fields["GENERO"])); ?></option>
								<?php
										$generosProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<?php
									$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
									if (isset($_GET['sm'])) {
										$url .= "&sm=" . $_GET['sm'];
									}
									if (isset($_GET['c'])) {
										$url .= "&c=" . $_GET['c'];
									}
									if (isset($_GET['g'])) {
										$url .= "&g=" . $_GET['g'];
									}
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "codigo")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="80" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=codigo&to=" . $to); ?>">C&oacute;digo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=nombre&to=" . $to); ?>">Nombre</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "categoria")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=categoria&to=" . $to); ?>">Categor&iacute;a</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "genero")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=genero&to=" . $to); ?>">G&eacute;nero</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "stock_minimo")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=stock_minimo&to=" . $to); ?>">Stock m&aacute;nimo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "stock")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=stock&to=" . $to); ?>">Stock</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT * FROM productos
									WHERE
										(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (eliminado = 0)
										and (soloapedido = 0)
								";
								if (isset($_GET['sm']) and ($_GET['sm'] != "")) {
									if ($_GET['sm'] == 0) {
										$query .= " and ((stock_minimo is null) or (stock_minimo = 0)) ";
									} else {
										$query .= " and (stock <= '" . $_GET['sm'] . "') ";
									}
								} else {
									$query .= " and (stock <= stock_minimo) ";
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$query .= " and (categoria = '" . $_GET['c'] . "') ";
								}
								if (isset($_GET['g']) and ($_GET['g'] != "")) {
									$query .= "and (genero = '" . $_GET['g'] . "') ";
								}
								$query .= " ORDER BY " . $_GET['tc'] . " " . $_GET['to'];

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								while (!$registros->EOF) {
								?>
									<tr>
										<td><?php echo ($registros->fields["CODIGO"]); ?></td>
										<td><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
										<td><?php echo ($registros->fields["CATEGORIA"]); ?></td>
										<td><?php echo ($registros->fields["GENERO"]); ?></td>
										<td><?php echo ($registros->fields["STOCK_MINIMO"]); ?></td>
										<td><?php echo ($registros->fields["STOCK"]); ?></td>
									</tr>
								<?php
									$registros->MoveNext();
								}
								?>
							</tbody>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Movimientos de Stock
		if (isset($_GET['accion']) && ($_GET['accion'] == "pormovimientostock")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			?>
			<h3>Movimientos de Stock</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option 
												value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" 
												<?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>
											>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="v" id="v" onchange="AbrirURL(this);" title="Filtrar por Vendedor" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['v'])) { echo ("selected='selected'"); } ?>>Todos los Vendedores</option>
								<?php
								$query = "SELECT privilegios.ID_USUARIO,NOMBRE_COMPLETO 
									FROM privilegios
									LEFT JOIN usuarios ON usuarios.ID_USUARIO = privilegios.ID_USUARIO
									WHERE 
										(privilegios.ID_SUCURSAL = '{$_SESSION['usuario_sucursal']}')  
										and (activo = 1)
									GROUP BY privilegios.id_usuario
									ORDER BY nombre_completo
								";

								$datosUsuarios = $temp->Query($query);
								if ($datosUsuarios and ($datosUsuarios->RecordCount() > 0)) {
									while (!$datosUsuarios->EOF) {
										?>
										<option 
											value="<?php echo ($url . "&v=" . $datosUsuarios->fields["ID_USUARIO"]); ?>" 
											<?php if (isset($_GET['v']) and ($_GET['v'] == $datosUsuarios->fields["ID_USUARIO"])) { echo ("selected=\"selected\""); } ?>
										>
											<?php echo (stripslashes($datosUsuarios->fields["NOMBRE_COMPLETO"])); ?>
										</option>
										<?php
										$datosUsuarios->MoveNext();
									}
								}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['v'])) {
								$url .= "&v=" . $_GET['v'];
							}
							?>
							<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar por Tipo de Movimiento" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['t'])) { echo ("selected='selected'"); } ?>>Todos los tipos</option>
								<?php
								$query = "SELECT TIPO_MOVIMIENTOSTOCK 
									FROM movimientos_stock
									WHERE 
										(tipo_movimientostock is not null) and (tipo_movimientostock <> '') 
								";
								if (isset($_GET['fd']) && ($_GET['fd'] != "")) {
									$query .= " AND (DATE(fecha_registro) >= '{$_GET['fd']}') ";
								}
								if (isset($_GET['fh']) && ($_GET['fh'] != "")) {
									$query .= " AND (DATE(fecha_registro) <= '{$_GET['fh']}') ";
								}
								if (isset($_GET['s']) && ($_GET['s'] != "")) {
									$query .= " AND (id_sucursal = '{$_GET['s']}') ";
								} else {
									$query .= " AND (id_sucursal = '{$_SESSION['usuario_sucursal']}') ";
								}
								$query .= "GROUP BY tipo_movimientostock ORDER BY tipo_movimientostock";

								$datosTipos = $temp->Query($query);
								if ($datosTipos and ($datosTipos->RecordCount() > 0)) {
									while (!$datosTipos->EOF) {
										?>
										<option 
											value="<?php echo ($url . "&t=" . $datosTipos->fields["TIPO_MOVIMIENTOSTOCK"]); ?>" 
											<?php if (isset($_GET['t']) and ($_GET['t'] == $datosTipos->fields["TIPO_MOVIMIENTOSTOCK"])) { echo ("selected=\"selected\""); } ?>
										>
											<?php echo (stripslashes($datosTipos->fields["TIPO_MOVIMIENTOSTOCK"])); ?>
										</option>
										<?php
										$datosTipos->MoveNext();
									}
								}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="110">Fecha</th>
									<th>Producto</th>
									<th>Vendedor</th>
									<th>Tipo</th>
									<th>Stock</th>
									<th>Cantidad</th>
									<th>Stock Final</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_stockactual = 0;
								$suma_cantidades = 0;
								$suma_stockfinal = 0;
								$cantidad_registros = 0;

								$query = "SELECT 
										FECHA_REGISTRO,
										DATE_FORMAT(FECHA_REGISTRO, '%H:%i') HORA_REGISTRO,
										concat('[',CODIGO,'] ',productos.NOMBRE) PRODUCTO, 
										NOMBRE_COMPLETO, 
										STOCK_ACTUAL, 
										CANTIDAD, 
										STOCK_FINAL, 
										TIPO_MOVIMIENTOSTOCK 
									FROM movimientos_stock
									LEFT JOIN productos ON productos.id_producto = movimientos_stock.id_producto
									LEFT JOIN usuarios ON usuarios.id_usuario = movimientos_stock.id_usuario
									WHERE 
										(movimientos_stock.id_sucursal = '{$_SESSION['usuario_sucursal']}') 
								";
								if (isset($_GET['fd']) && ($_GET['fd'] != "")) {
									$query .= " AND (DATE(fecha_registro) >= '{$_GET['fd']}') ";
								}
								if (isset($_GET['fh']) && ($_GET['fh'] != "")) {
									$query .= " AND (DATE(fecha_registro) <= '{$_GET['fh']}') ";
								}
								if (isset($_GET['v']) && ($_GET['v'] != "")) {
									$query .= " AND (movimientos_stock.id_usuario = '{$_GET['v']}') ";
								}
								if (isset($_GET['t']) && ($_GET['t'] != "")) {
									$query .= "AND (tipo_movimientostock = '{$_GET['t']}') ";
								}
								$query .= "ORDER BY fecha_registro DESC";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros && ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_stockactual += $registros->fields["STOCK_ACTUAL"];
										$suma_cantidades += $registros->fields["CANTIDAD"];
										$suma_stockfinal += $registros->fields["STOCK_FINAL"];
										?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_REGISTRO"], 3).' '.$registros->fields["HORA_REGISTRO"]); ?></td>
											<td><?php echo (stripslashes($registros->fields["PRODUCTO"])); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td><?php echo ($registros->fields["TIPO_MOVIMIENTOSTOCK"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["STOCK_ACTUAL"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["CANTIDAD"]); ?></td>
											<td style="text-align: right;"><?php echo ($registros->fields["STOCK_FINAL"]); ?></td>
										</tr>
										<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo ($suma_stockactual); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo ($suma_cantidades); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo ($suma_stockfinal); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Productos a pedir
		if (isset($_GET['accion']) and ($_GET['accion'] == "porproductosvencidos")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['tc'])) {
				$_GET['tc'] = "codigo";
			}
			if (!isset($_GET['to'])) {
				$_GET['to'] = "asc";
			}
			?>
			<h3>Productos con fecha de vencimiento</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Categoria:</strong> " + $("#c option:selected").text() + " / <strong>Genero:</strong> " + $("#g option:selected").text()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + this.y + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 1) { //	obtengo la segunda columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 5) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 5] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 1) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 5) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 5].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="sm" id="sm" onchange="AbrirURL(this);" title="Filtrar por Fecha de vencimiento" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los vencidos</option>
								<option value="<?php echo ($url . "&sm=1"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 1)) {
																					echo ("selected='selected'");
																				} ?>>Vencidos hace un año</option>
								<option value="<?php echo ($url . "&sm=2"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 2)) {
																					echo ("selected='selected'");
																				} ?>>Vencidos hace 6 meses</option>
								<option value="<?php echo ($url . "&sm=3"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 3)) {
																					echo ("selected='selected'");
																				} ?>>Vencidos hace 3 meses</option>
								<option value="<?php echo ($url . "&sm=4"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 4)) {
																					echo ("selected='selected'");
																				} ?>>Vencidos hace 1 mes</option>
								<option value="<?php echo ($url . "&sm=5"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 5)) {
																					echo ("selected='selected'");
																				} ?>>Vencidos hace 15 días</option>
								<option value="<?php echo ($url . "&sm=6"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 6)) {
																					echo ("selected='selected'");
																				} ?>>A vencerse en los próximos 15 días</option>
								<option value="<?php echo ($url . "&sm=7"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 7)) {
																					echo ("selected='selected'");
																				} ?>>A vencerse en el próximo mes</option>
								<option value="<?php echo ($url . "&sm=8"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 8)) {
																					echo ("selected='selected'");
																				} ?>>A vencerse en los próximos 3 meses</option>
								<option value="<?php echo ($url . "&sm=9"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 9)) {
																					echo ("selected='selected'");
																				} ?>>A vencerse en los próximos 6 meses</option>
								<option value="<?php echo ($url . "&sm=10"); ?>" <?php if (isset($_GET['sm']) and ($_GET['sm'] == 10)) {
																						echo ("selected='selected'");
																					} ?>>A vencerse en el próximo año</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['sm'])) {
								$url .= "&sm=" . $_GET['sm'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['c'])) {
																			echo ("selected='selected'");
																		} ?>>Todas las Categorias</option>
								<?php
								$categoriasProductos = $temp->Query("SELECT CATEGORIA
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (categoria is not null) and (categoria <> '')
											and (fecha_vencimiento is not null)
										GROUP BY categoria
										ORDER BY categoria
									");

								if ($categoriasProductos and ($categoriasProductos->RecordCount() > 0)) {
									while (!$categoriasProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&c=" . $categoriasProductos->fields["CATEGORIA"]); ?>" <?php if (isset($_GET['c']) and ($_GET['c'] == $categoriasProductos->fields["CATEGORIA"])) {
																																		echo ("selected=\"selected\"");
																																	} ?>><?php echo (stripslashes($categoriasProductos->fields["CATEGORIA"])); ?></option>
								<?php
										$categoriasProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['sm'])) {
								$url .= "&sm=" . $_GET['sm'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['g'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los G&eacute;neros</option>
								<?php
								$generosProductos = $temp->Query("SELECT GENERO
										FROM productos
										WHERE
											(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (eliminado = 0)
											and (activo = 1)
											and (genero is not null) and (genero <> '')
											and (fecha_vencimiento is not null)
										GROUP BY genero
										ORDER BY genero");

								if ($generosProductos and ($generosProductos->RecordCount() > 0)) {
									while (!$generosProductos->EOF) {
								?>
										<option value="<?php echo ($url . "&g=" . $generosProductos->fields["GENERO"]); ?>" <?php if (isset($_GET['g']) and ($_GET['g'] == $generosProductos->fields["GENERO"])) {
																																echo ("selected=\"selected\"");
																															} ?>><?php echo (stripslashes($generosProductos->fields["GENERO"])); ?></option>
								<?php
										$generosProductos->MoveNext();
									}
								}
								?>
							</select>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<?php
									$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
									if (isset($_GET['sm'])) {
										$url .= "&sm=" . $_GET['sm'];
									}
									if (isset($_GET['c'])) {
										$url .= "&c=" . $_GET['c'];
									}
									if (isset($_GET['g'])) {
										$url .= "&g=" . $_GET['g'];
									}
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "codigo")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="80" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=codigo&to=" . $to); ?>">C&oacute;digo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=nombre&to=" . $to); ?>">Nombre</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "categoria")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=categoria&to=" . $to); ?>">Categor&iacute;a</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "genero")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=genero&to=" . $to); ?>">G&eacute;nero</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "stock")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=stock&to=" . $to); ?>">Stock</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "fecha_vencimiento")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=fecha_vencimiento&to=" . $to); ?>">F. Vencimiento</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT * FROM productos
									WHERE
										(id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (eliminado = 0)
										and (soloapedido = 0)
										and (fecha_vencimiento is not null)
								";
								if (isset($_GET['sm']) && !empty($_GET['sm'])) {
									switch ($_GET['sm']) {
										case "1": // Vencidos hace un año
											$query .= " and (fecha_vencimiento >= DATE_ADD(curdate(),INTERVAL -1 YEAR)) and (FECHA_VENCIMIENTO < curdate()) ";
											break;
										case "2": // Vencidos hace 6 meses
											$query .= " and (fecha_vencimiento >= DATE_ADD(curdate(),INTERVAL -6 MONTH)) and (FECHA_VENCIMIENTO < curdate()) ";
											break;
										case "3": // Vencidos hace 3 meses
											$query .= " and (fecha_vencimiento >= DATE_ADD(curdate(),INTERVAL -3 MONTH)) and (FECHA_VENCIMIENTO < curdate()) ";
											break;
										case "4": // Vencidos hace 1 mes
											$query .= " and (fecha_vencimiento >= DATE_ADD(curdate(),INTERVAL -1 MONTH)) and (FECHA_VENCIMIENTO < curdate()) ";
											break;
										case "5": // Vencidos hace 15 días
											$query .= " and (fecha_vencimiento >= DATE_ADD(curdate(),INTERVAL -15 DAY)) and (FECHA_VENCIMIENTO < curdate()) ";
											break;
										case "6": // A vencerse en los próximos 15 días
											$query .= " and (fecha_vencimiento >= curdate()) and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 15 DAY)) ";
											break;
										case "7": // A vencerse en el próximo mes
											$query .= " and (fecha_vencimiento >= curdate()) and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 1 MONTH)) ";
											break;
										case "8": // A vencerse en los próximos 3 meses
											$query .= " and (fecha_vencimiento >= curdate()) and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 6 MONTH)) ";
											break;
										case "9": // A vencerse en los próximos 6 meses
											$query .= " and (fecha_vencimiento >= curdate()) and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 6 MONTH)) ";
											break;
										case "10": // A vencerse en el próximo año
											$query .= " and (fecha_vencimiento >= curdate()) and (FECHA_VENCIMIENTO <= DATE_ADD(curdate(),INTERVAL 1 YEAR)) ";
											break;
									}
								}
								if (isset($_GET['c']) and ($_GET['c'] != "")) {
									$query .= " and (categoria = '" . $_GET['c'] . "') ";
								}
								if (isset($_GET['g']) and ($_GET['g'] != "")) {
									$query .= " and (genero = '" . $_GET['g'] . "') ";
								}
								$query .= " ORDER BY " . $_GET['tc'] . " " . $_GET['to'];

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								while (!$registros->EOF) {
								?>
									<tr>
										<td><?php echo ($registros->fields["CODIGO"]); ?></td>
										<td><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
										<td><?php echo ($registros->fields["CATEGORIA"]); ?></td>
										<td><?php echo ($registros->fields["GENERO"]); ?></td>
										<td><?php echo ($registros->fields["STOCK"]); ?></td>
										<td><?php echo (formato_fecha($registros->fields["FECHA_VENCIMIENTO"], 3)); ?></td>
									</tr>
								<?php
									$registros->MoveNext();
								}
								?>
							</tbody>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Catálogo de Productos
		if (isset($_GET['accion']) && ($_GET['accion'] == "porcatalogo")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['tc'])) {
				$_GET['tc'] = "codigo";
			}
			if (!isset($_GET['to'])) {
				$_GET['to'] = "asc";
			}
			?>
			<h3>Catálogo de Productos</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										console.log('tipografico');
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Categoria:</strong> " + $("#c option:selected").text() + " / <strong>Genero:</strong> " + $("#g option:selected").text()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + this.y + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 1) { //	obtengo la segunda columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 5) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 5] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 5] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return this.y;
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 1) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 5) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 5].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$sucursal_set = "(id_sucursal = '{$_SESSION['usuario_sucursal']}')";
							$tipoProducto_set = isset($_GET['t']) && ($_GET['t'] != "") ? " AND (tipo_producto = '{$_GET['t']}')" : "";
							$categoria_set = isset($_GET['c']) && ($_GET['c'] != "") ? " AND (categoria = '{$_GET['c']}')" : "";
							$genero_set = isset($_GET['g']) && ($_GET['g'] != "") ? " AND (genero = '{$_GET['g']}')" : "";

							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar por Tipo" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['sm'])) { echo ("selected='selected'"); } ?>>Todos los tipos</option>
								<option value=""></option>
								<?php
								$query = "SELECT TIPO_PRODUCTO
									FROM productos
									WHERE
										{$sucursal_set}
										and (eliminado = 0)
										and (activo = 1)
										and (tipo_producto is not null) and (tipo_producto <> '')
									GROUP BY tipo_producto
									ORDER BY tipo_producto
								";
								$datosRegistros = $temp->Query($query);

								if ($datosRegistros and ($datosRegistros->RecordCount() > 0)) {
									while (!$datosRegistros->EOF) {
										?>
										<option value="<?php echo ($url . "&t=" . $datosRegistros->fields["TIPO_PRODUCTO"]); ?>" <?php if (isset($_GET['t']) && ($_GET['t'] == $datosRegistros->fields["TIPO_PRODUCTO"])) { echo ("selected=\"selected\""); } ?>>
											<?php echo (stripslashes($datosRegistros->fields["TIPO_PRODUCTO"])); ?>
										</option>
										<?php
										$datosRegistros->MoveNext();
									}
								}
								?>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							?>
							<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['c'])) { echo ("selected='selected'"); } ?>>Todas las Categorias</option>
								<option value=""></option>
								<?php
								$query = "SELECT CATEGORIA
									FROM productos
									WHERE
										{$sucursal_set}
										and (eliminado = 0)
										and (activo = 1)
										and (categoria is not null) and (categoria <> '')
										{$tipoProducto_set}
									GROUP BY categoria
									ORDER BY categoria
								";

								$datosRegistros = $temp->Query($query);
								if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
									while (!$datosRegistros->EOF) {
										?>
										<option value="<?php echo ($url . "&c=" . $datosRegistros->fields["CATEGORIA"]); ?>" <?php if (isset($_GET['c']) && ($_GET['c'] == $datosRegistros->fields["CATEGORIA"])) { echo ("selected=\"selected\""); } ?>>
											<?php echo (stripslashes($datosRegistros->fields["CATEGORIA"])); ?>
										</option>
										<?php
										$datosRegistros->MoveNext();
									}
								}
								?>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							if (isset($_GET['c'])) {
								$url .= "&c=" . $_GET['c'];
							}
							?>
							<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['g'])) { echo ("selected='selected'"); } ?>>Todos los G&eacute;neros</option>
								<option value=""></option>
								<?php
								$query = "SELECT GENERO 
									FROM productos
									WHERE 
										{$sucursal_set}
										and (eliminado = 0) 
										and (activo = 1) 
										and (genero is not null) and (genero <> '') 
										{$tipoProducto_set}
										{$categoria_set}
									GROUP BY genero
									ORDER BY genero
								";

								$datosRegistros = $temp->Query($query);
								if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
									while (!$datosRegistros->EOF) {
										?>
										<option value="<?php echo ($url . "&g=" . $datosRegistros->fields["GENERO"]); ?>" <?php if (isset($_GET['g']) && ($_GET['g'] == $datosRegistros->fields["GENERO"])) { echo ("selected=\"selected\""); } ?>>
											<?php echo (stripslashes($datosRegistros->fields["GENERO"])); ?>
										</option>
										<?php
										$datosRegistros->MoveNext();
									}
								}
								?>
							</select>

							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls" style="display: none;">Microsoft Excel (*.xls)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xlsx" style="display: none;">Microsoft Excel (*.xlsx)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="40">N°</th>
									<th width="200">Foto</th>
									<?php
									$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
									if (isset($_GET['t'])) {
										$url .= "&t=" . $_GET['t'];
									}
									if (isset($_GET['c'])) {
										$url .= "&c=" . $_GET['c'];
									}
									if (isset($_GET['g'])) {
										$url .= "&g=" . $_GET['g'];
									}
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "codigo")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="100" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=codigo&to=" . $to); ?>">C&oacute;digo</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=nombre&to=" . $to); ?>">Nombre</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "cantidad_pieza_caja")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="150" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=cantidad_pieza_caja&to=" . $to); ?>">Cantidad de Piezas por Caja</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "precio_venta_caja")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="150" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=precio_venta_caja&to=" . $to); ?>">Precio de Venta por Caja</a></th>
									<?php
									$estilo = "";
									$to = "asc";
									if (isset($_GET['tc']) and ($_GET['tc'] == "precio_venta_docena")) {
										if ($_GET['to'] == "asc") {
											$to = "desc";
										} else {
											if ($_GET['to'] == "desc") {
												$to = "asc";
											}
										}
										$estilo = $to;
									}
									?>
									<th width="150" class="<?php echo ($estilo); ?>"><a href="<?php echo ($url . "&tc=precio_venta_docena&to=" . $to); ?>">Precio de Venta por Docena</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT 
										CODIGO,
										NOMBRE,
										IFNULL(foto,'') FOTO,
										CANTIDAD_PIEZA_CAJA,
										(PRECIO_VENTA_CAJA * ".convertirTipoMoneda().") PRECIO_VENTA_CAJA,
										(PRECIO_VENTA_DOCENA * ".convertirTipoMoneda().") PRECIO_VENTA_DOCENA
									FROM productos
									WHERE
										{$sucursal_set}
										and (eliminado = 0)
										and (activo = 1)
										and (stock >= 0)
										{$tipoProducto_set}
										{$categoria_set}
										{$genero_set}
									ORDER BY {$_GET['tc']} {$_GET['to']}
									LIMIT 0,500
								";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								$contador = 1;
								while (!$registros->EOF) {
									?>
									<tr>
										<td style="text-align: center;vertical-align: middle;"><?php echo ($contador); ?></td>
										<td style="text-align: center;vertical-align: middle;">
											<?php  										
											if(!empty($registros->fields["FOTO"])){
												$path_url_foto = $sitio_url.$path_imagenes."productos/".$registros->fields['FOTO'];
												if(verificar_url($path_url_foto)) {
													?>
											<img src="<?php echo ($path_url_foto); ?>" class="img-rounded" width="70" height="70" style="max-height: 70px;" title="<?php echo ("[".$registros->fields["CODIGO"]."] ".stripslashes($registros->fields["NOMBRE"])); ?>" />
													<?php
												}
											}
											?>
										</td>
										<td style="vertical-align: middle;"><?php echo ($registros->fields["CODIGO"]); ?></td>
										<td style="vertical-align: middle;"><?php echo (stripslashes($registros->fields["NOMBRE"])); ?></td>
										<td style="text-align: center;vertical-align: middle;"><?php echo (number_format($registros->fields["CANTIDAD_PIEZA_CAJA"], 0, ',', '.')); ?></td>
										<td style="text-align: right;vertical-align: middle;"><?php echo (number_format($registros->fields["PRECIO_VENTA_CAJA"], 2, ',', '.')." ".(convertirTipoMoneda() == 1 ? 'Bs' : $_SESSION['usuario_tipomoneda'])); ?></td>
										<td style="text-align: right;vertical-align: middle;"><?php echo (number_format($registros->fields["PRECIO_VENTA_DOCENA"], 2, ',', '.')." ".(convertirTipoMoneda() == 1 ? 'Bs' : $_SESSION['usuario_tipomoneda'])); ?></td>
									</tr>
									<?php
									$registros->MoveNext();
									$contador++;
								}
								?>
							</tbody>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// DATOS -> VENTAS ---------

		// Ventas periodicas
		if (isset($_GET['accion']) and ($_GET['accion'] == "porventas")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 1;
			}
			?>
			<h3>Ventas peri&oacute;dicas</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="e" id="e" onchange="AbrirURL(this);" title="Filtrar por Estado" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['e'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los Estados</option>
								<option value=""></option>
								<option value="<?php echo ($url . "&e=Entregado"); ?>" <?php if (isset($_GET['e']) and ($_GET['e'] == "Entregado")) {
																							echo ("selected='selected'");
																						} ?>>Entregado</option>
								<option value="<?php echo ($url . "&e=Pendiente"); ?>" <?php if (isset($_GET['e']) and ($_GET['e'] == "Pendiente")) {
																							echo ("selected='selected'");
																						} ?>>Pendiente</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar segun el Pago" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['p'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los tipos de Pagos</option>
								<option value=""></option>
								<option value="<?php echo ($url . "&p=1"); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Pagado</option>
								<option value="<?php echo ($url . "&p=0"); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>No Pagado</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Vendedor</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Estado</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Pagado</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Cliente</th>
									<th>Estado</th>
									<th>Pagado</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT ventas.FECHA_ALTA,concat('[',REPEAT('0',6-length(COD_USUARIO)),COD_USUARIO,'] ',NOMBRE_COMPLETO) CLIENTE,ESTADO,PAGADO,SUM(TOTAL) as STOTAL FROM ventas
									LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
									WHERE (tipo_venta = 'ventas') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (ventas.eliminado = 0) ";
								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (ventas.fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (ventas.fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['e']) and ($_GET['e'] != "")) {
									$query .= " and (estado = '" . $_GET['e'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= "and (pagado = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY ventas.fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY ventas.id_usuario ";
											break;
										case 3:
											$query .= "GROUP BY ventas.estado ";
											break;
										case 4:
											$query .= "GROUP BY ventas.pagado ";
											break;
									}
								}
								$query .= "ORDER BY ventas.fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["STOTAL"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["CLIENTE"])); ?></td>
											<td><?php echo ($registros->fields["ESTADO"]); ?></td>
											<td style="text-align: center;"><?php if ($registros->fields["PAGADO"] != null) {
																				echo ($estados[$registros->fields["PAGADO"]]);
																			}  ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["STOTAL"], $cantidad_decimales, ',', '.')); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Ventas mensuales
		if (isset($_GET['accion']) and ($_GET['accion'] == "porventasmensuales")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['a'])) {
				$_GET['a'] = date("Y");
			}
			//if(!isset($_GET['p'])) { $_GET['p'] = '1'; }
			?>
			<h3>Ventas mensuales</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
						<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="a" id="a" onchange="AbrirURL(this);" title="Filtrar por Estado" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['a'])) {
																			echo ("selected='selected'");
																		} ?>>Todos años</option>
								<?php
								$query = "SELECT year(fecha_alta) as ANIO
									FROM ventas
									WHERE
										(tipo_venta = 'ventas')
										and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (eliminado = 0)
									GROUP BY year(fecha_alta)
									ORDER BY year(fecha_alta) DESC
								";
								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros) {
									while (!$registros->EOF) {
								?>
										<option value="<?php echo ($url . '&a=' . $registros->fields["ANIO"]); ?>" <?php if (isset($_GET['a']) && ($_GET['a'] == $registros->fields["ANIO"])) {
																														echo ("selected='selected'");
																													} ?>><?php echo ($registros->fields["ANIO"]); ?></option>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['a'])) {
								$url .= "&a=" . $_GET['a'];
							}
							?>
							<select name="b" id="b" onchange="AbrirURL(this);" title="Filtrar por Barrio" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['b'])) {
																			echo ("selected='selected'");
																		} ?>>Todos</option>
								<?php
								$query = "SELECT upper(IFNULL(linea,'')) LINEA
									FROM ventas
									LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente
									WHERE
										(tipo_venta = 'ventas')
										and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (eliminado = 0)
									GROUP BY upper(IFNULL(linea,''))
									ORDER BY upper(IFNULL(linea,''))
								";
								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros) {
									while (!$registros->EOF) {
								?>
										<option value="<?php echo ($url . '&b=' . $registros->fields["LINEA"]); ?>" <?php if (isset($_GET['b']) && ($_GET['b'] == $registros->fields["LINEA"])) {
																														echo ("selected='selected'");
																													} ?>><?php echo ($registros->fields["LINEA"] == '' ? 'SIN SIGNAR' : $registros->fields["LINEA"]); ?></option>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['a'])) {
								$url .= "&a=" . $_GET['a'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar segun el Pago" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['p'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los tipos de Pagos</option>
								<option value="<?php echo ($url . "&p=1"); ?>" <?php if (isset($_GET['p']) && ($_GET['p'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Pagado</option>
								<option value="<?php echo ($url . "&p=0"); ?>" <?php if (isset($_GET['p']) && ($_GET['p'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>No Pagado</option>
							</select>

							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<?php
						$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
						$url .= isset($_GET['a']) && !empty($_GET['a']) ? '&a=' . $_GET['a'] : '';
						$url .= isset($_GET['p']) && !empty($_GET['p']) ? '&p=' . $_GET['p'] : '';
						?>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'COD_USUARIO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?> width="80"><a href="<?php echo ($url . '&c=COD_USUARIO&o=' . $o); ?>">Código</a></th>
									<?php
									$estilo = "";
									$o = "asc";
									if (isset($_GET['c']) && ($_GET['c'] == 'NOMBRE_COMPLETO')) {
										$o = $_GET['o'] == "asc" ? 'desc' : ($_GET['o'] == "desc" ? 'asc' : '');
										$estilo = $o;
									}
									?>
									<th <?php if (!empty($estilo)) {
											echo ('class="' . $estilo . '"');
										} ?>><a href="<?php echo ($url . '&c=NOMBRE_COMPLETO&o=' . $o); ?>">Cliente</a></th>
									<th>Enero</th>
									<th>Febrero</th>
									<th>Marzo</th>
									<th>Abril</th>
									<th>Mayo</th>
									<th>Junio</th>
									<th>Julio</th>
									<th>Agosto</th>
									<th>Septiembre</th>
									<th>Octubre</th>
									<th>Noviembre</th>
									<th>Diciembre</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma = 0;
								$suma_meses = [
									"ENERO" => 0,
									"FEBRERO" => 0,
									"MARZO" => 0,
									"ABRIL" => 0,
									"MAYO" => 0,
									"JUNIO" => 0,
									"JULIO" => 0,
									"AGOSTO" => 0,
									"SEPTIEMBRE" => 0,
									"OCTUBRE" => 0,
									"NOVIEMBRE" => 0,
									"DICIEMBRE" => 0,
								];
								$cantidad_registros = 0;

								$pagado_set = isset($_GET['p']) ? 'and (ventas.PAGADO = ' . $_GET['p'] . ')' : '';
								$barrio_set = isset($_GET['b']) ? "and (upper(IFNULL(linea,'')) = '" . $_GET['b'] . "')" : '';
								$order_set = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] . " " . $_GET['o'] : 'nombre_completo';

								$query = "SELECT
										concat(repeat('0',4-length(COD_USUARIO)),COD_USUARIO) COD_USUARIO,
										NOMBRE_COMPLETO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=1 THEN TOTAL ELSE 0 END) AS ENERO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=2 THEN TOTAL ELSE 0 END) AS FEBRERO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=3 THEN TOTAL ELSE 0 END) AS MARZO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=4 THEN TOTAL ELSE 0 END) AS ABRIL,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=5 THEN TOTAL ELSE 0 END) AS MAYO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=6 THEN TOTAL ELSE 0 END) AS JUNIO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=7 THEN TOTAL ELSE 0 END) AS JULIO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=8 THEN TOTAL ELSE 0 END) AS AGOSTO,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=9 THEN TOTAL ELSE 0 END) AS SEPTIEMBRE,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=10 THEN TOTAL ELSE 0 END) AS OCTUBRE,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=11 THEN TOTAL ELSE 0 END) AS NOVIEMBRE,
										SUM(CASE WHEN extract(month from ventas.FECHA_ALTA)=12 THEN TOTAL ELSE 0 END) AS DICIEMBRE,
										sum(total) TOTAL
									FROM ventas
									LEFT JOIN usuarios ON usuarios.ID_USUARIO = ventas.ID_CLIENTE
									WHERE
										(TIPO_VENTA = 'ventas')
										and (ventas.ID_SUCURSAL = {$_SESSION['usuario_sucursal']})
										and (ventas.ELIMINADO = 0)
										and (usuarios.activo = 1)
										{$pagado_set}
										{$barrio_set}
										and (year(ventas.FECHA_ALTA) = {$_GET['a']})
									GROUP BY ID_CLIENTE
									ORDER BY {$order_set}
								";
								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma += $registros->fields["TOTAL"];

										$suma_meses['ENERO'] += $registros->fields["ENERO"];
										$suma_meses['FEBRERO'] += $registros->fields["FEBRERO"];
										$suma_meses['MARZO'] += $registros->fields["MARZO"];
										$suma_meses['ABRIL'] += $registros->fields["ABRIL"];
										$suma_meses['MAYO'] += $registros->fields["MAYO"];
										$suma_meses['JUNIO'] += $registros->fields["JUNIO"];
										$suma_meses['JULIO'] += $registros->fields["JULIO"];
										$suma_meses['AGOSTO'] += $registros->fields["AGOSTO"];
										$suma_meses['SEPTIEMBRE'] += $registros->fields["SEPTIEMBRE"];
										$suma_meses['OCTUBRE'] += $registros->fields["OCTUBRE"];
										$suma_meses['NOVIEMBRE'] += $registros->fields["NOVIEMBRE"];
										$suma_meses['DICIEMBRE'] += $registros->fields["DICIEMBRE"];
								?>
										<tr>
											<td style="text-align: center;"><?php echo ($registros->fields["COD_USUARIO"]); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["ENERO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["FEBRERO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["MARZO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["ABRIL"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["MAYO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["JUNIO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["JULIO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["AGOSTO"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["SEPTIEMBRE"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["OCTUBRE"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["NOVIEMBRE"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["DICIEMBRE"], $cantidad_decimales, ',', '.')); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["TOTAL"], $cantidad_decimales, ',', '.')); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th><?php echo ($cantidad_registros); ?></th>
									<th style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['ENERO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['FEBRERO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['MARZO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['ABRIL'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['MAYO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['JUNIO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['JULIO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['AGOSTO'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['SEPTIEMBRE'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['OCTUBRE'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['NOVIEMBRE'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_meses['DICIEMBRE'], $cantidad_decimales, ',', '.')); ?></th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// DATOS -> OTROS ---------

		//Servicios Tecnicos periodicos
		if (isset($_GET['accion']) and ($_GET['accion'] == "porservicios")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['t'])) {
				$_GET['t'] = "fecha_alta";
			}
			switch ($_GET['t']) {
				case "fecha_alta":
					$_GET['tid'] = "ID_USUARIO";
					break;
				case "fecha_entrega":
					$_GET['tid'] = "ID_TECNICO";
					break;
			}
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 5;
			}
			?>
			<h3>Servicios T&eacute;cnicos peri&oacute;dicos</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar segun la fecha" style="width: auto;">
								<option value="<?php echo ($url . "&t=fecha_alta"); ?>" <?php echo (!empty($_GET['t']) && ($_GET['t'] == "fecha_alta") ? "selected='selected'" : ''); ?>>Fecha de recepción</option>
								<option value="<?php echo ($url . "&t=fecha_entrega"); ?>" <?php echo (!empty($_GET['t']) && ($_GET['t'] == "fecha_entrega") ? "selected='selected'" : ''); ?>>Fecha de entrega</option>
							</select>
							-
							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							$url .= isset($_GET['t']) ? "&t=" . $_GET['t'] : '';
							?>
							<select name="e" id="e" onchange="AbrirURL(this);" title="Filtrar por Estado" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php echo (empty($_GET['e']) ? "selected='selected'" : ''); ?>>Todos los Estados</option>
								<?php
								// obtengo todos los ESTADOS
								$fecha_desde = !empty($_GET['fd']) ? " and (ventas." . strtoupper($_GET['t']) . " >= '" . $_GET['fd'] . "') " : "";
								$fecha_hasta = !empty($_GET['fh']) ? " and (ventas." . strtoupper($_GET['t']) . " <= '" . $_GET['fh'] . "') " : "";
								$query = "SELECT estado FROM ventas
										WHERE
											(tipo_venta = 'servicios')
											and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
											and (ventas.eliminado = 0)
											{$fecha_desde}
											{$fecha_hasta}
										GROUP BY estado
										ORDER BY estado
								";
								$registrosEstados = $conexion->Query($query);
								if ($registrosEstados && ($registrosEstados->RecordCount() > 0)) {
									while (!$registrosEstados->EOF) {
								?>
										<option value="<?php echo ($url . '&e=' . $registrosEstados->fields["estado"]); ?>" <?php echo (!empty($_GET['e'] && ($_GET['e'] == $registrosEstados->fields["estado"])) ? "selected='selected'" : ''); ?>>
											<?php echo $registrosEstados->fields["estado"]; ?>
										</option>
								<?php
										$registrosEstados->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							$url .= isset($_GET['t']) ? "&t=" . $_GET['t'] : '';
							$url .= isset($_GET['e']) ? "&e=" . $_GET['e'] : '';
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar segun el Pago" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php echo ($_GET['p'] == '' ? "selected='selected'" : ''); ?>>Todos los tipos de pagos</option>
								<option value="<?php echo ($url . "&p=1"); ?>" <?php echo (isset($_GET['p']) && ($_GET['p'] == "1") ? "selected='selected'" : ''); ?>>Pagado</option>
								<option value="<?php echo ($url . "&p=0"); ?>" <?php echo (isset($_GET['p']) && ($_GET['p'] == "0") ? "selected='selected'" : ''); ?>>No Pagado</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							$url .= isset($_GET['t']) ? "&t=" . $_GET['t'] : '';
							$url .= isset($_GET['e']) ? "&e=" . $_GET['e'] : '';
							$url .= isset($_GET['p']) ? "&p=" . $_GET['p'] : '';
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php echo (!empty($_GET['ag']) && ($_GET['ag'] == "5") ? "selected='selected'" : ''); ?>>por ID</option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php echo (!empty($_GET['ag']) && ($_GET['ag'] == "1") ? "selected='selected'" : ''); ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php echo (!empty($_GET['ag']) && ($_GET['ag'] == "2") ? "selected='selected'" : ''); ?>>por Vendedor</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php echo (!empty($_GET['ag']) && ($_GET['ag'] == "3") ? "selected='selected'" : ''); ?>>por Estado</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php echo (!empty($_GET['ag']) && ($_GET['ag'] == "4") ? "selected='selected'" : ''); ?>>por Pagado</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							$url .= isset($_GET['t']) ? "&t=" . $_GET['t'] : '';
							$url .= isset($_GET['e']) ? "&e=" . $_GET['e'] : '';
							$url .= isset($_GET['p']) ? "&p=" . $_GET['p'] : '';
							$url .= isset($_GET['ag']) ? "&ag=" . $_GET['ag'] : '';
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Consola</th>
									<th>Problema</th>
									<th>Vendedor / T&eacute;cnico</th>
									<th>Pagado</th>
									<th>Total</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$fechaDesde_set = !empty($_GET['fd']) ? " and (ventas." . strtoupper($_GET['t']) . " >= '" . $_GET['fd'] . "') " : '';
								$fechaHasta_set = !empty($_GET['fh']) ? " and (ventas." . strtoupper($_GET['t']) . " <= '" . $_GET['fh'] . "') " : '';
								$estado_set = !empty($_GET['e']) ? " and (estado = '" . $_GET['e'] . "') " : '';
								$pagado_set = $_GET['p'] != '' ? " and (pagado = '" . $_GET['p'] . "') " : '';

								$query = "SELECT
										ventas." . strtoupper($_GET['t']) . ",
										CONSOLA,
										DESCRIPCION,
										NOMBRE_COMPLETO,
										PAGADO,
										SUM(TOTAL) as STOTAL,
										ESTADO
									FROM ventas
									LEFT JOIN usuarios ON usuarios.id_usuario = ventas." . $_GET['tid'] . "
									WHERE
										(tipo_venta = 'servicios')
										and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "')
										and (ventas.eliminado = 0)
										{$fechaDesde_set}
										{$fechaHasta_set}
										{$estado_set}
										{$pagado_set}
								";
								if (isset($_GET['ag']) && ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY ventas." . strtoupper($_GET['t']) . " ";
											break;
										case 2:
											$query .= "GROUP BY ventas." . $_GET['tid'] . " ";
											break;
										case 3:
											$query .= "GROUP BY ventas.estado ";
											break;
										case 4:
											$query .= "GROUP BY ventas.pagado ";
											break;
										case 5:
											$query .= "GROUP BY ventas.ID_VENTA ";
											break;
									}
								}
								$query .= "ORDER BY ventas." . strtoupper($_GET['t']) . "";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["STOTAL"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields[strtoupper($_GET['t'])], 3)); ?></td>
											<td><?php echo ($registros->fields["CONSOLA"]); ?></td>
											<td><?php echo ($registros->fields["DESCRIPCION"]); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td style="text-align: center;"><?php if ($registros->fields["PAGADO"] != null) {
																				echo ($estados[$registros->fields["PAGADO"]]);
																			}  ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["STOTAL"], $cantidad_decimales, ',', '.')); ?></td>
											<td><?php echo ($registros->fields["ESTADO"]); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, $cantidad_decimales, ',', '.')); ?></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Pedidos
		if (isset($_GET['accion']) and ($_GET['accion'] == "porpedidos")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 1;
			}
			?>
			<h3>Pedidos peri&oacute;dicas</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />

							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="e" id="e" onchange="AbrirURL(this);" title="Filtrar por Estado" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['e'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los Estados</option>
								<option value=""></option>
								<option value="<?php echo ($url . "&e=Entregado"); ?>" <?php if (isset($_GET['e']) and ($_GET['e'] == "Entregado")) {
																							echo ("selected='selected'");
																						} ?>>Entregado</option>
								<option value="<?php echo ($url . "&e=Pendiente"); ?>" <?php if (isset($_GET['e']) and ($_GET['e'] == "Pendiente")) {
																							echo ("selected='selected'");
																						} ?>>Pendiente</option>
								<option value="<?php echo ($url . "&e=Cancelado"); ?>" <?php if (isset($_GET['e']) and ($_GET['e'] == "Cancelado")) {
																							echo ("selected='selected'");
																						} ?>>Cancelado</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Vendedor</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Estado</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Cliente</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['e'])) {
								$url .= "&e=" . $_GET['e'];
							}
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Vendedor</th>
									<th>Estado</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT ventas.FECHA_ALTA,NOMBRE_COMPLETO,ESTADO,PAGADO,SUM(TOTAL) as STOTAL FROM ventas ";
								if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "4")) {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente ";
								} else {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_usuario ";
								}
								$query .= "WHERE (tipo_venta = 'pedidos') and (ventas.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (ventas.eliminado = 0) ";

								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (ventas.fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (ventas.fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['e']) and ($_GET['e'] != "")) {
									$query .= " and (estado = '" . $_GET['e'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY ventas.fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY ventas.id_usuario ";
											break;
										case 3:
											$query .= "GROUP BY ventas.estado ";
											break;
										case 4:
											$query .= "GROUP BY ventas.id_cliente ";
											break;
										case 5:
											$query .= "GROUP BY ventas.id_tecnico ";
											break;
									}
								}
								$query .= "ORDER BY ventas.fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["STOTAL"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td><?php echo ($registros->fields["ESTADO"]); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["STOTAL"], $cantidad_decimales, ',', '.')); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Egresos
		if (isset($_GET['accion']) and ($_GET['accion'] == "poregresos")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 3;
			}
			?>
			<h3>Egresos peri&oacute;dicas</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="t" id="t" onchange="AbrirURL(this);" title="Filtrar por Tipo" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['e'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los Tipos</option>
								<option value=""></option>
								<?php
								$query = "SELECT TIPO_EGRESO FROM egresos
										WHERE (id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') and (tipo_egreso is not null) and (tipo_egreso <> '') ";
								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								$query .= "GROUP BY tipo_egreso
										ORDER BY tipo_egreso";

								$tiposEgresos = $temp->Query($query);
								if ($tiposEgresos and ($tiposEgresos->RecordCount() > 0)) {
									while (!$tiposEgresos->EOF) {
								?>
										<option value="<?php echo ($url . "&t=" . $tiposEgresos->fields["TIPO_EGRESO"]); ?>" <?php if (isset($_GET['t']) and ($_GET['t'] == $tiposEgresos->fields["TIPO_EGRESO"])) {
																																	echo ("selected=\"selected\"");
																																} ?>><?php echo (stripslashes($tiposEgresos->fields["TIPO_EGRESO"])); ?></option>
								<?php
										$tiposEgresos->MoveNext();
									}
								}
								?>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							?>
							<select name="p" id="p" onchange="AbrirURL(this);" title="Filtrar segun el Pago" style="width: auto;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['p'])) {
																			echo ("selected='selected'");
																		} ?>>Todos los tipos de Pagos</option>
								<option value=""></option>
								<option value="<?php echo ($url . "&p=1"); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>Pagado</option>
								<option value="<?php echo ($url . "&p=0"); ?>" <?php if (isset($_GET['p']) and ($_GET['p'] == "0")) {
																					echo ("selected='selected'");
																				} ?>>No Pagado</option>
							</select>
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Vendedor</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Tipo</option>
								<option value="<?php echo ($url . "&ag=4"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "4")) {
																					echo ("selected='selected'");
																				} ?>>por Pagado</option>
								<option value="<?php echo ($url . "&ag=5"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "5")) {
																					echo ("selected='selected'");
																				} ?>>por Cliente</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['t'])) {
								$url .= "&t=" . $_GET['t'];
							}
							if (isset($_GET['p'])) {
								$url .= "&p=" . $_GET['p'];
							}
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Vendedor</th>
									<th>Tipo</th>
									<th>Pagado</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT egresos.FECHA_ALTA,NOMBRE_COMPLETO,TIPO_EGRESO,PAGADO,SUM(TOTAL) as STOTAL FROM egresos ";
								if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "2")) {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_usuario ";
								} else {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_cliente ";
								}
								$query .= "WHERE (egresos.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') ";

								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (egresos.fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (egresos.fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['t']) and ($_GET['t'] != "")) {
									$query .= " and (tipo_egreso = '" . $_GET['t'] . "') ";
								}
								if (isset($_GET['p']) and ($_GET['p'] != "")) {
									$query .= "and (pagado = '" . $_GET['p'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY egresos.fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY egresos.id_usuario ";
											break;
										case 3:
											$query .= "GROUP BY egresos.tipo_egreso ";
											break;
										case 4:
											$query .= "GROUP BY egresos.pagado ";
											break;
										case 5:
											$query .= "GROUP BY egresos.id_cliente ";
											break;
									}
								}
								$query .= "ORDER BY egresos.fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["STOTAL"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td><?php echo ($registros->fields["TIPO_EGRESO"]); ?></td>
											<td><?php if ($registros->fields["PAGADO"] != null) {
													echo ($estados[$registros->fields["PAGADO"]]);
												}  ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["STOTAL"], $cantidad_decimales, ',', '.')); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// Cotizaciones
		if (isset($_GET['accion']) and ($_GET['accion'] == "porcotizaciones")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			//if(!isset($_GET['fd'])) { $_GET['fd'] = date("d/m/Y"); }
			if (!isset($_GET['fd'])) {
				$_GET['fd'] = sumarDias(-7, date("Y-m-d"));
			}
			if (!isset($_GET['fh'])) {
				$_GET['fh'] = date("Y-m-d");
			}
			if (!isset($_GET['ag'])) {
				$_GET['ag'] = 3;
			}
			?>
			<h3>Cotizaciones peri&oacute;dicas</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras" selected="selected">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Sucursal:</strong> " + $("#s option:selected").text() + " / <strong>Periodo:</strong> " + $("#fd").val() + " al " + $("#fh").val()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + number_format(this.y, 2, ',', '.') + ' Bs' + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + number_format(this.y, 2, ',', '.') + ' Bs';
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "900px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														//console.log("["+i+","+j+"]"+this.innerHTML);
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if (i == 0) { // inicializo la serie
														if (j == 4) {
															if (vValor == "circular") {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																options.series[j - 4] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															} else {
																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 2,
																			y: -10,
																			rotation: -90,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	//console.log("["+i+","+j+"]"+this.innerHTML);
																	options.series[j - 4] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				return number_format(this.y, 2, ',', '.') + ' Bs';
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																//console.log("["+i+","+j+"]"+this.innerHTML);
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
														}
														if ((j == 4) && (vPadre == 'TBODY')) {
															//console.log("["+i+","+j+"]"+this.innerHTML);
															var vCantidad = this.innerHTML;
															var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
															if (vValor == "circular") {
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															} else {
																options.series[j - 4].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});

								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px;height: 600px;margin: 0 auto;width: 98%;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="s" id="s" onchange="AbrirURL(this);" title="Filtrar por Sucursal" style="width: auto;display: none;">
								<option value="<?php echo ($url); ?>" <?php if (!isset($_GET['s'])) { echo ("selected='selected'"); } ?>>Todas</option>
								<?php
									$query = "SELECT ID_SUCURSAL,NOMBRE FROM sucursales ORDER BY nombre";
									$datosRegistros = $temp->Query($query);

									if ($datosRegistros && ($datosRegistros->RecordCount() > 0)) {
										while (!$datosRegistros->EOF) {
											?>
											<option value="<?php echo ($url . "&s=" . $datosRegistros->fields["ID_SUCURSAL"]); ?>" <?php if ((isset($_GET['s']) && ($_GET['s'] == $datosRegistros->fields["ID_SUCURSAL"])) || ($datosRegistros->fields["ID_SUCURSAL"] == $_SESSION['usuario_sucursal'])) { echo ("selected=\"selected\""); } ?>>
												<?php echo (stripslashes($datosRegistros->fields["NOMBRE"])); ?>
											</option>
											<?php
											$datosRegistros->MoveNext();
										}
									}
								?>
							</select>

							<input type="date" name="fd" id="fd" value="<?php echo ($_GET['fd']); ?>" class="input-medium" title="Fecha desde" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							<input type="date" name="fh" id="fh" value="<?php echo ($_GET['fh']); ?>" class="input-medium" title="Fecha hasta" size="12" maxlength="10" placeholder="dd/mm/aaaa" required autocomplete="off" />
							-
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							?>
							<select name="ag" id="ag" onchange="AbrirURL(this);" title="Agrupar datos" style="width: auto;">
								<option value=""></option>
								<option value="<?php echo ($url . "&ag=1"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "1")) {
																					echo ("selected='selected'");
																				} ?>>por Fecha</option>
								<option value="<?php echo ($url . "&ag=2"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "2")) {
																					echo ("selected='selected'");
																				} ?>>por Vendedor</option>
								<option value="<?php echo ($url . "&ag=3"); ?>" <?php if (isset($_GET['ag']) and ($_GET['ag'] == "3")) {
																					echo ("selected='selected'");
																				} ?>>por Cliente</option>
							</select>
							<?php
							$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
							if (isset($_GET['ag'])) {
								$url .= "&ag=" . $_GET['ag'];
							}
							?>
							<button type="button" id="obtenerDatos" value="<?php echo ($url); ?>" class="btn btn-info" onclick="AbrirURL(this);" style="margin-top: -10px;">Obtener datos</button>
							-
							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th width="80">Fecha</th>
									<th>Vendedor</th>
									<th>Vencimiento</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_cantidades = 0;
								$cantidad_registros = 0;

								$query = "SELECT cotizaciones.FECHA_ALTA,NOMBRE_COMPLETO,FECHA_VENCIMIENTO,SUM(TOTAL) as STOTAL FROM cotizaciones ";
								if (isset($_GET['ag']) and ($_GET['ag'] != "") and ($_GET['ag'] == "2")) {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_usuario ";
								} else {
									$query .= "LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_cliente ";
								}
								$query .= "WHERE (cotizaciones.id_sucursal = '" . $_SESSION['usuario_sucursal'] . "') ";

								if (isset($_GET['fd']) and ($_GET['fd'] != "")) {
									$query .= " and (cotizaciones.fecha_alta >= '" . $_GET['fd'] . "') ";
								}
								if (isset($_GET['fh']) and ($_GET['fh'] != "")) {
									$query .= " and (cotizaciones.fecha_alta <= '" . $_GET['fh'] . "') ";
								}
								if (isset($_GET['ag']) and ($_GET['ag'] != "")) {
									switch ($_GET['ag']) {
										case 1:
											$query .= "GROUP BY cotizaciones.fecha_alta ";
											break;
										case 2:
											$query .= "GROUP BY cotizaciones.id_usuario ";
											break;
										case 3:
											$query .= "GROUP BY cotizaciones.id_cliente ";
											break;
									}
								}
								$query .= "ORDER BY cotizaciones.fecha_alta";

								$registros = $conexion->Query($query);
								$cantidad_registros = $registros->RecordCount();

								if ($registros and ($registros->RecordCount() > 0)) {
									while (!$registros->EOF) {
										$suma_cantidades += $registros->fields["STOTAL"];
								?>
										<tr>
											<td><?php echo (formato_fecha($registros->fields["FECHA_ALTA"], 3)); ?></td>
											<td><?php echo (stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?></td>
											<td><?php echo ($registros->fields["FECHA_VENCIMIENTO"]); ?></td>
											<td style="text-align: right;"><?php echo (number_format($registros->fields["STOTAL"], $cantidad_decimales, ',', '.')); ?></td>
										</tr>
								<?php
										$registros->MoveNext();
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3" style="text-align: right;padding-right: 10px;">TOTAL</th>
									<th style="text-align: right;background: #dddddd;"><?php echo (number_format($suma_cantidades, $cantidad_decimales, ',', '.')); ?></th>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" name="cantidad_registros" id="cantidad_registros" value="<?php echo ($cantidad_registros); ?>" />
					</div>
				</div>
			</div>
			<?php
		}

		// SISTEMA

		//	Por Actividad de Usuario
		if (isset($_GET['accion']) and ($_GET['accion'] == "porusuario")) {
			$url = $_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu'] . "&accion=" . $_GET['accion'];
			if (!isset($_GET['a'])) {
				$_GET['a'] = date("Y");
			}
			if (!isset($_GET['m'])) {
				$_GET['m'] = date("m");
			}
			if (!isset($_GET['d'])) {
				$_GET['d'] = date("d");
			}
			?>
			<h3>Actividades de los Usuarios</h3>
			<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
			<div class="btn-toolbar pull-right" style="margin-top: -3px;">
				<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
			</div>
			<div class="tabbable">
				<ul class="nav nav-tabs">
					<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
					<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
					<li><a href="<?php echo ($_SERVER['PHP_SELF'] . "?menu=" . $_GET['menu']); ?>">M&aacute;s reportes</a></li>
				</ul>

				<div class="tab-content">
					<div id="tab01" class="tab-pane">
						<div class="well">
							<strong>Tipo de Grafico: </strong>
							<select name="tipografico" id="tipografico" title="Seleccionar tipo de grafico" style="width: auto;">
								<option value="lineal">Lineal</option>
								<option value="curvas">Curvas</option>
								<option value="circular" selected="selected">Circular</option>
								<option value="columnas">Columnas</option>
								<option value="barras">Barras</option>
							</select>
						</div>

						<script type="text/javascript">
							$(function() {
								var table = document.getElementById('listado');

								$(document).ready(function() {
									$("#tipografico").change(function() {
										$("#vMsjCargando").show();

										var vValor = $(this).val();

										var optionsBasicas = {
											title: {
												text: $("h3").text()
											},
											subtitle: {
												text: "<strong>Total de Accesos:</strong> " + $("#listado tfoot tr th").eq(1).text() + "  / <strong><?php echo (utf8_encode('Año')); ?>:</strong> " + $("#a option:selected").text() + " / <strong>Mes:</strong> " + $("#m option:selected").text()
											},
											xAxis: {
												title: {
													text: $("#listado thead tr th:first").text()
												},
												labels: {
													rotation: 0
												}
											},
											yAxis: {
												title: {
													text: $("#listado thead tr th").eq(1).text()
												}
											}
										};

										switch (vValor) {
											case "lineal":
												var options = {
													chart: {
														type: 'line'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.series.name + '</b><br/>' + this.x + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "600px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "curvas":
												var options = {
													chart: {
														type: 'spline'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "600px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "circular":
												var options = {
													tooltip: {
														formatter: function() {
															return '<b>' + this.point.name + '</b><br/>' + this.series.name + ' : ' + this.y + ' -> ' + number_format(this.percentage, 1, ',', '.') + ' %';
														}
													},
													plotOptions: {
														pie: {
															allowPointSelect: true,
															cursor: 'pointer',
															dataLabels: {
																enabled: true,
																color: '#000000',
																connectorColor: '#000000',
																formatter: function() {
																	return '<b>' + this.point.name + '</b>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																}
															},
															showInLegend: true
														}
													}
												};
												$('#container_graph').css("width", '98%');
												$('#container_graph').css("height", "600px");
												break;
											case "columnas":
												var options = {
													chart: {
														type: 'column'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + this.y;
														}
													}
												};
												optionsBasicas.xAxis.labels.rotation = -90;
												optionsBasicas.xAxis.labels.align = 'right';
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("width", '98%');
													$('#container_graph').css("height", "600px");
												} else {
													$('#container_graph').css("width", (Number($("#cantidad_registros").val()) * 40) + "px");
													$('#container_graph').css("height", "1800px");
												}
												break;
											case "barras":
												var options = {
													chart: {
														type: 'bar'
													},
													tooltip: {
														formatter: function() {
															return '<b>' + this.x + '</b><br/>' + this.series.name + ': ' + this.y;
														}
													}
												};
												$('#container_graph').css("width", '98%');
												if ($("#cantidad_registros").val() < 20) {
													$('#container_graph').css("height", "600px");
												} else {
													$('#container_graph').css("height", (Number($("#cantidad_registros").val()) * 40));
												}
												break;
										}

										$.extend(options, optionsBasicas);

										if (vValor != "circular") {
											// categorias
											options.xAxis.categories = [];
											$("tbody tr", table).each(function(i) {
												var tr = this;
												$("td", tr).each(function(j) {
													if (j == 0) { //	obtengo la primer columna
														options.xAxis.categories.push(this.innerHTML);
													}
												})
											});
										}

										// datos
										var vContador = 0;
										options.series = [];
										$('tr', table).each(function(i) {
											var tr = this;
											$('th, td', tr).each(function(j) {
												var vNivel = "";
												var vPadre = $(this).parent().parent().get(0).tagName;
												if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
													if ((vPadre == 'TBODY') && (j == 0)) {
														if ((this.innerHTML % 10000) == 0) {
															vNivel = "Nivel 1";
														} else {
															if ((this.innerHTML % 1000) == 0) {
																vNivel = "Nivel 2";
															} else {
																if ((this.innerHTML % 100) == 0) {
																	vNivel = "Nivel 3";
																} else {
																	if ((this.innerHTML % 10) == 0) {
																		vNivel = "Nivel 4";
																	} else {
																		vNivel = "Nivel 5";
																	}
																}
															}
														}
													}

													if (i == 0) { // inicializo la serie
														if (vValor == "circular") {
															if (j == 1) {
																options.series[j - 1] = {
																	type: 'pie',
																	name: this.innerHTML,
																	data: [],
																	dataLabels: {
																		formatter: function() {
																			return '<strong>' + this.point.name + '</strong>: ' + number_format(this.percentage, 1, ',', '.') + ' %';
																		}
																	}
																};
															}
														} else {
															if ((j == 1) || (j == 3) || (j == 5) || (j == 7)) {
																if (j == 1) {
																	var pos = j - 1;
																}
																if (j == 3) {
																	var pos = j - 2;
																}
																if (j == 5) {
																	var pos = j - 3;
																}
																if (j == 7) {
																	var pos = j - 4;
																}

																if ((vValor == "lineal") || (vValor == "curvas") || (vValor == "columnas")) {
																	options.series[pos] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				if (this.y > 0) {
																					return this.y;
																				}
																			},
																			x: 2,
																			y: -10,
																			rotation: 0,
																			verticalAlign: 'middle',
																			align: 'left'
																		}
																	};
																} else {
																	options.series[pos] = {
																		name: this.innerHTML,
																		data: [],
																		dataLabels: {
																			formatter: function() {
																				if (this.y > 0) {
																					return this.y;
																				}
																			},
																			x: 10,
																			y: 2
																		}
																	};
																}
															}
														}
													} else { // agrego los valores
														if (vValor == "circular") {
															if ((j == 0) && (vPadre == 'TBODY')) {
																var lista = [];
																lista[0] = this.innerHTML;
																lista[1] = j;
																options.series[0].data.push(lista);
															}
															if ((j == 1) && (vPadre == 'TBODY')) {
																var vCantidad = this.innerHTML;
																var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
																options.series[0].data[i - 2][1] = parseFloat(vCantidad);
															}
														} else {
															if (((j == 1) || (j == 3) || (j == 5) || (j == 7)) && (vPadre == 'TBODY')) {
																if (j == 1) {
																	var pos = j - 1;
																}
																if (j == 3) {
																	var pos = j - 2;
																}
																if (j == 5) {
																	var pos = j - 3;
																}
																if (j == 7) {
																	var pos = j - 4;
																}
																var vCantidad = this.innerHTML;
																var vCantidad = vCantidad.replace('.', '').replace('.', '').replace(',', '.');
																options.series[pos].data.push(parseFloat(vCantidad));
															}
														}
													}

												}
											});
										});

										$('#container_graph').highcharts(options);

										$("#vMsjCargando").hide();
									})

									$("a[data-toggle='tab']").on('shown', function(e) {
										var tabs = decodeURI(e.target).split('#');
										switch (tabs[1]) {
											case "tab01":
												$("#tipografico").trigger('change');
												break;
											case "tab02":
												//	ver datos
												break;
										}
									});


								});

							});
						</script>

						<div id="container_graph" style="min-width: 400px; height: 600px; margin: 0 auto;width: 800px;"></div>
					</div>
					<div id="tab02" class="tab-pane active">
						<div class="well">
							<select name="a" id="a" onchange="AbrirURL(this)" title="Filtrar por Año" style="width: auto;">
								<option value="<?php echo ($url . '&a=&m=&d='); ?>">Todos los años</option>
								<option value="<?php echo ($url . "&a=" . date("Y") . '&m=&d='); ?>" <?php if (isset($_GET['a']) and ($_GET['a'] == date("Y"))) {
																											echo ("selected=\"selected\"");
																										} ?>>
									<?php echo (date("Y")); ?>
								</option>
								<?php
								$registros = $conexion->Query("SELECT year(fecha) ANIO
										FROM contador
										WHERE
											(year(fecha) < year(now()))
										GROUP BY year(fecha)
										ORDER BY year(fecha) DESC
									");
								while (!$registros->EOF) {
								?>
									<option value="<?php echo ($url . "&a=" . $registros->fields["ANIO"] . '&m=&d='); ?>" <?php if (isset($_GET['a']) and ($_GET['a'] == $registros->fields["ANIO"])) {
																																echo ("selected=\"selected\"");
																															} ?>>
										<?php echo ($registros->fields["ANIO"]); ?>
									</option>
								<?php
									$registros->MoveNext();
								}
								?>
							</select>
							-
							<?php
							if (isset($_GET['a']) && !empty($_GET['a'])) {
								$url .= "&a=" . $_GET['a'];
							}
							?>
							<select name="m" id="m" onchange="AbrirURL(this)" title="Filtrar por Mes" style="width: auto;">
								<option value="<?php echo ($url . '&m=&d='); ?>">Todos los meses</option>
								<option value="<?php echo ($url . "&m=1&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "1")) {
																						echo ("selected=\"selected\"");
																					} ?>>Enero</option>
								<option value="<?php echo ($url . "&m=2&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "2")) {
																						echo ("selected=\"selected\"");
																					} ?>>Febrero</option>
								<option value="<?php echo ($url . "&m=3&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "3")) {
																						echo ("selected=\"selected\"");
																					} ?>>Marzo</option>
								<option value="<?php echo ($url . "&m=4&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "4")) {
																						echo ("selected=\"selected\"");
																					} ?>>Abril</option>
								<option value="<?php echo ($url . "&m=5&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "5")) {
																						echo ("selected=\"selected\"");
																					} ?>>Mayo</option>
								<option value="<?php echo ($url . "&m=6&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "6")) {
																						echo ("selected=\"selected\"");
																					} ?>>Junio</option>
								<option value="<?php echo ($url . "&m=7&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "7")) {
																						echo ("selected=\"selected\"");
																					} ?>>Julio</option>
								<option value="<?php echo ($url . "&m=8&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "8")) {
																						echo ("selected=\"selected\"");
																					} ?>>Agosto</option>
								<option value="<?php echo ($url . "&m=9&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "9")) {
																						echo ("selected=\"selected\"");
																					} ?>>Septiembre</option>
								<option value="<?php echo ($url . "&m=10&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "10")) {
																						echo ("selected=\"selected\"");
																					} ?>>Octubre</option>
								<option value="<?php echo ($url . "&m=11&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "11")) {
																						echo ("selected=\"selected\"");
																					} ?>>Noviembre</option>
								<option value="<?php echo ($url . "&m=12&d="); ?>" <?php if (isset($_GET['m']) and ($_GET['m'] == "12")) {
																						echo ("selected=\"selected\"");
																					} ?>>Diciembre</option>
							</select>
							-
							<?php
							if (isset($_GET['m']) && !empty($_GET['m'])) {
								$url .= "&m=" . $_GET['m'];
							}
							?>
							<select name="d" id="d" onchange="AbrirURL(this)" title="Filtrar por Día" style="width: auto;">
								<option value="<?php echo ($url . '&d='); ?>">Todos los días</option>
								<?php
								if (!empty($_GET['a']) && !empty($_GET['m'])) {
									$cantidad_dias = cal_days_in_month(CAL_GREGORIAN, $_GET['m'], $_GET['a']);
									for ($i = 1; $i <= $cantidad_dias; $i++) {
								?>
										<option value="<?php echo ($url . "&d=" . $i); ?>" <?php if (isset($_GET['d']) and ($_GET['d'] == $i)) {
																								echo ("selected=\"selected\"");
																							} ?>>
											<?php echo ($i); ?>
										</option>
								<?php
									}
								}
								?>
							</select>

							<div class="btn-group" style="margin-top: -10px;">
								<button class="btn dropdown-toggle" data-toggle="dropdown" title="Exportar datos">Exportar <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="doc">Microsoft Word (*.doc)</a></li>
									<li><a href="javascript:void(0);" id="btExportarDatos" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
								</ul>
							</div>
							<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
						</div>

						<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<th>Usuarios</th>
									<th>Accesos</th>
									<th>&Uacute;ltimo<br />Acceso</th>
									<th>Contenidos</th>
									<th>Cotizaciones</th>
									<th>Egresos</th>
									<th>Inventarios</th>
									<th>Traspasos</th>
									<th>Clientes</th>
									<th>Ventas</th>
									<th>Servicios</th>
									<th>Pedidos</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$suma_accesos = 0;
								$suma_contenidos = 0;
								$suma_cotizaciones = 0;
								$suma_egresos = 0;
								$suma_inventarios = 0;
								$suma_traspasos = 0;
								$suma_clientes = 0;
								$suma_ventas = 0;
								$suma_servicios = 0;
								$suma_pedidos = 0;

								if (!empty($_GET['a'])) {
									$anio_set = ' and (year(fecha) = ' . $_GET['a'] . ') ';
									$anioContador_set = ' and (year(fecha) = year(C.fecha)) ';
									$anioActualizacion_set = ' and (year(fecha_actualizacion) = year(C.fecha)) ';
								}
								if (!empty($_GET['m'])) {
									$mes_set = ' and (month(fecha) = ' . $_GET['m'] . ') ';
									$mesContador_set = ' and (month(fecha) = month(C.fecha)) ';
									$mesActualizacion_set = ' and (month(fecha_actualizacion) = month(C.fecha)) ';
								}
								if (!empty($_GET['d'])) {
									$dia_set = ' and (day(fecha) = ' . $_GET['d'] . ') ';
									$diaContador_set = ' and (day(fecha) = day(C.fecha)) ';
									$diaActualizacion_set = ' and (day(fecha_actualizacion) = day(C.fecha)) ';
								}

								$query = "SELECT
										C.ID_USUARIO,
										NOMBRE_COMPLETO,
										count(*) ACCESOS,
										IFNULL(
											(
												select max(fecha)
												from contador
												where
													(id_usuario = C.id_usuario)
													{$anioContador_set}
													{$mesContador_set}
													{$diaContador_set}
											),''
										) ULTIMO_ACCESO,
										IFNULL(
											(
												select count(id_actualizacion)
												from contenidos
												where
													(id_actualizacion = C.id_usuario)
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) CONTENIDOS,
										IFNULL(
											(
												select count(id_actualizacion)
												from cotizaciones
												where
													(id_actualizacion = C.id_usuario)
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) COTIZACIONES,
										IFNULL(
											(
												select count(id_actualizacion)
												from egresos
												where
													(id_actualizacion = C.id_usuario)
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) EGRESOS,
										IFNULL(
											(
												select count(id_actualizacion)
												from inventarios
												where
													(id_actualizacion = C.id_usuario)
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) INVENTARIOS,
										IFNULL(
											(
												select count(id_actualizacion)
												from traspasos
												where
													(id_actualizacion = C.id_usuario)
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) TRASPASOS,
										IFNULL(
											(
												select count(id_actualizacion)
												from usuarios
												where
													(id_actualizacion = C.id_usuario)
													and (tipo_usuario = 'Cliente')
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) CLIENTES,
										IFNULL(
											(
												select count(id_actualizacion)
												from ventas
												where
													(id_actualizacion = C.id_usuario)
													and (tipo_venta = 'ventas')
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) VENTAS,
										IFNULL(
											(
												select count(id_actualizacion)
												from ventas
												where
													(id_actualizacion = C.id_usuario)
													and (tipo_venta = 'servicios')
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) SERVICIOS,
										IFNULL(
											(
												select count(id_actualizacion)
												from ventas
												where
													(id_actualizacion = C.id_usuario)
													and (tipo_venta = 'pedidos')
													{$anioActualizacion_set}
													{$mesActualizacion_set}
													{$diaActualizacion_set}
											),0
										) PEDIDOS
									FROM contador C
									LEFT JOIN usuarios ON usuarios.ID_USUARIO = C.ID_USUARIO
									WHERE
										(C.ID_USUARIO is not null)
										{$anio_set}
										{$mes_set}
										{$dia_set}
									GROUP BY C.ID_USUARIO
									ORDER BY nombre_completo
								";

								$registros = $conexion->Execute($query);
								while (!$registros->EOF) {

									$suma_accesos += $registros->fields["ACCESOS"];
									$suma_contenidos += $registros->fields["CONTENIDOS"];
									$suma_cotizaciones += $registros->fields["COTIZACIONES"];
									$suma_egresos += $registros->fields["EGRESOS"];
									$suma_inventarios += $registros->fields["INVENTARIOS"];
									$suma_traspasos += $registros->fields["TRASPASOS"];
									$suma_clientes += $registros->fields["CLIENTES"];
									$suma_ventas += $registros->fields["VENTAS"];
									$suma_servicios += $registros->fields["SERVICIOS"];
									$suma_pedidos += $registros->fields["PEDIDOS"];

								?>
									<tr>
										<td><?php echo ($registros->fields["NOMBRE_COMPLETO"]); ?></td>
										<td style="text-align: right;"><?php echo ($registros->fields["ACCESOS"]); ?></td>
										<td><?php echo (formato_fecha($registros->fields["ULTIMO_ACCESO"], 3)); ?></td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["CONTENIDOS"]); ?>
											<?php
											if (!empty($registros->fields["CONTENIDOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["COTIZACIONES"]); ?>
											<?php
											if (!empty($registros->fields["COTIZACIONES"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["EGRESOS"]); ?>
											<?php
											if (!empty($registros->fields["EGRESOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["INVENTARIOS"]); ?>
											<?php
											if (!empty($registros->fields["INVENTARIOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["TRASPASOS"]); ?>
											<?php
											if (!empty($registros->fields["TRASPASOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["CLIENTES"]); ?>
											<?php
											if (!empty($registros->fields["CLIENTES"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["VENTAS"]); ?>
											<?php
											if (!empty($registros->fields["VENTAS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["SERVICIOS"]); ?>
											<?php
											if (!empty($registros->fields["SERVICIOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
										<td style="text-align: right;">
											<?php echo ($registros->fields["PEDIDOS"]); ?>
											<?php
											if (!empty($registros->fields["PEDIDOS"])) {
											?>
												<button class="btn btn-mini" title="Ver datos" tabindex="-1"><i class="icon-eye-open"></i></button>
											<?php
											}
											?>
										</td>
									</tr>
									<tr style="display: none;">
										<td colspan="12"></td>
									</tr>
								<?php
									$registros->MoveNext();
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th style="text-align: right;padding-right: 10px;">TOTALES</th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_accesos); ?></th>
									<th>&nbsp;</th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_contenidos); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_cotizaciones); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_egresos); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_inventarios); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_traspasos); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_clientes); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_ventas); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_servicios); ?></th>
									<th style="text-align: right;padding-right: 5px;"><?php echo ($suma_pedidos); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<?php
		}

	}
	//	-----	Fin REPORTES

	include("../footer.php");
	?>
</body>

</html>