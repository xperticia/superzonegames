<?php
	include("../config.php");
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
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate"> 
		<meta http-equiv="Pragma" content="no-cache"> 
		<meta http-equiv="Expires" content="0"> 

		<link rel="stylesheet" href="../images/xpanel.css" type="text/css" />
	</head>

	<body>
		<?php
		include("../head.php");

		//	-----	CONTADOR GOOGLE
		if(isset($_GET['menu']) and ($_GET['menu'] == "contador")) {
			// include the Google Analytics PHP class
			include "../scripts/googleanalytics.class/googleanalytics.class.php";

			try {
				if(file_exists("contador.png")){
					unlink("contador.png");
				}

				$ga = new GoogleAnalytics($ga_usuario,$ga_password);
				$ga->setProfile('ga:'.$ga_id);

				if(!isset($_GET['fdesde']) and !isset($_GET['fhasta'])){
					$_GET['tipo'] = "lineal";
					$_GET['dato'] = "xvisita";
					$ga->setDateRange((date('Y-m-d', time()-(30*24*60*60))),date("Y-m-d"));
					$_GET['fdesde'] = (date('d/m/Y', time()-(30*24*60*60)));
					$_GET['fhasta'] = date("d/m/Y");
				} else {
					$ga->setDateRange(formato_fecha($_GET['fdesde'],4),formato_fecha($_GET['fhasta'],4));
				}
			
				//	Listado
				if(!isset($_GET['accion'])) {
					?>
					<form method="get" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>" id="signup" class="form-horizontal" style="border: 1px solid #DDDDDD;border-radius: 6px 6px 6px 6px;padding: 10px;">
						<input type="hidden" name="menu" value="<?php echo($_GET['menu']); ?>" />
						<div class="control-group">
							<label class="control-label">Tipo de grafico</label>
							<div class="controls">
								<select id="tipo" name="tipo" title="Tipo de grafico" required>
									<option value=""></option>
									<optgroup label="Lineal">
										<option value="lineal" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "lineal")) { echo("selected='selected'"); } ?>>lineal</option>
										<option value="lineal_invertida" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "lineal_invertida")) { echo("selected='selected'"); } ?>>lineal invertida</option>
									</optgroup>
									<optgroup label="Area">
										<option value="area" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "area")) { echo("selected='selected'"); } ?>>area</option>
										<option value="area_invertida" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "area_invertida")) { echo("selected='selected'"); } ?>>area invertida</option>
									</optgroup>
									<option value="barra" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "barra")) { echo("selected='selected'"); } ?>>Barra</option>
									<option value="columna" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "columna")) { echo("selected='selected'"); } ?>>Columna</option>
									<optgroup label="Circular">
										<option value="circularporcentaje" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "circularporcentaje")) { echo("selected='selected'"); } ?>>circular con %</option>
										<option value="circularvalores" <?php if(isset($_GET['tipo']) and ($_GET['tipo'] == "circularvalores")) { echo("selected='selected'"); } ?>>circular con valores</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Tipo de dato</label>
							<div class="controls">
								<select id="dato" name="dato" title="Tipo de datos" required>
									<option value=""></option>
									<optgroup label="Visitante">
										<option value="xvisita" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xvisita")) { echo("selected='selected'"); } ?>>Que visitas diarias tengo?</option>						
										<option value="xvisitorType" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xvisitorType")) { echo("selected='selected'"); } ?>>Que visitante es nuevo o recurrente?</option>
									</optgroup>
									<optgroup label="Sistema">
										<option value="xnavegador" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xnavegador")) { echo("selected='selected'"); } ?>>Que navegador usan los visitantes?</option>
										<option value="xlenguaje" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xlenguaje")) { echo("selected='selected'"); } ?>>De que idioma son los visitantes?</option>
										<option value="xso" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xso")) { echo("selected='selected'"); } ?>>Que sistema operativo poseen los visitantes?</option>
										<option value="xresolucion" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xresolucion")) { echo("selected='selected'"); } ?>>Que resoluci&oacute;n de pantalla poseen los visitantes?</option>
									</optgroup>
									<optgroup label="Ubicaci&oacute;n geogr&aacute;fica">
										<option value="xcontinente" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xcontinente")) { echo("selected='selected'"); } ?>>De que continente son los visitantes?</option>
										<option value="xpais" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpais")) { echo("selected='selected'"); } ?>>De que pa&iacute;s son los visitantes?</option>
										<option value="xregion" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xregion")) { echo("selected='selected'"); } ?>>De que region son los visitantes?</option>
										<option value="xciudad" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xciudad")) { echo("selected='selected'"); } ?>>De que ciudad son los visitantes?</option>
									</optgroup>
									<optgroup label="Seguimiento de p&aacute;gina">
										<option value="xhostname" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xhostname")) { echo("selected='selected'"); } ?>>Nombre de host desde el que se ha realizado la solicitud de seguimiento.</option>
										<option value="xpagePath" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpagePath")) { echo("selected='selected'"); } ?>>P&aacute;gina de tu sitio web que se ha especificado por ruta o por par&aacute;metros de consulta.</option>
										<option value="xpageTitle" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpageTitle")) { echo("selected='selected'"); } ?>>T&iacute;tulo de una p&aacute;gina.</option>
										<option value="xlandingPagePath" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xlandingPagePath")) { echo("selected='selected'"); } ?>>Primera p&aacute;gina de la sesi&oacute;n de un usuario.</option>
										<option value="xexitPagePath" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xexitPagePath")) { echo("selected='selected'"); } ?>>&Uacute;ltima p&aacute;gina de la sesi&oacute;n de un usuario.</option>
										<option value="xpreviousPagePath" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpreviousPagePath")) { echo("selected='selected'"); } ?>>P&aacute;gina de tu sitio web que se ha visitado antes que otra p&aacute;gina del sitio web.</option>
										<option value="xnextPagePath" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xnextPagePath")) { echo("selected='selected'"); } ?>>P&aacute;gina de tu sitio web que se ha visitado despu&eacute;s (after) que otra p&aacute;gina del sitio web.</option>
										<option value="xpageDepth" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpageDepth")) { echo("selected='selected'"); } ?>>N&uacute;mero de p&aacute;ginas visitadas por los usuarios durante una sesi&oacute;n.</option>
									</optgroup>
									<optgroup label="B&uacute;squeda interna">
										<option value="xpalabraclave" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xpalabraclave")) { echo("selected='selected'"); } ?>>Cu&aacute;les son las palabras clave de b&uacute;squeda interna?</option>
										<option value="xsearchKeyword" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchKeyword")) { echo("selected='selected'"); } ?>>T&eacute;rminos de b&uacute;squeda que los usuarios han utilizado.</option>
										<option value="xsearchCategory" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchCategory")) { echo("selected='selected'"); } ?>>Categor&iacute;as utilizadas para la b&uacute;squeda interna.</option>
										<option value="xsearchStartPage" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchStartPage")) { echo("selected='selected'"); } ?>>P&aacute;gina en la que el usuario ha iniciado una b&uacute;squeda interna.</option>
										<option value="xsearchDestinationPage" <?php if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchDestinationPage")) { echo("selected='selected'"); } ?>>P&aacute;gina que el usuario ha visitado despu&eacute;s de realizar una b&uacute;squeda interna.</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Periodo</label>
							<div class="controls">
								<input type="text" name="fdesde" id="fdesde" title="Fecha desde" class="input-small" value="<?php echo($_GET['fdesde']); ?>" size="12" maxlength="10" placeholder="dd/mm/aaaa" required/>
								<input type="text" name="fhasta" id="fhasta" title="Fecha hasta" class="input-small" value="<?php echo($_GET['fhasta']); ?>" size="12" maxlength="10" placeholder="dd/mm/aaaa"  required/>
								<script type="text/javascript">
								$(function() {
									$("#fdesde").datepicker({ dateFormat: "dd/mm/yy"});
									$("#fhasta").datepicker({ dateFormat: "dd/mm/yy"});
								});
								</script>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="obtener datos" />
						</div>
					</form>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li><a href="#tab01" data-toggle="tab">Grafico</a></li>
							<li class="active"><a href="#tab02" data-toggle="tab">Datos</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane">
								<script type="text/javascript">
								$(function () {
									var chart;
									$(document).ready(function() {

										$("a[data-toggle='tab']").on('shown', function (e) {
											var tabs = decodeURI(e.target).split('#');
											//console.log(e.currentTarget.hash + " - " + tabs[1]);
											switch (tabs[1]) {
												case "tab01":
													//	ver grafico
													Highcharts.visualize = function(table, options) {
														// the categories
														if (($("#tipo").val() != "circularporcentaje") && ($("#tipo").val() != "circularvalores")) {
															options.xAxis.categories = [];
															$('tbody th', table).each( function(i) {								
																options.xAxis.categories.push(this.innerHTML);
															});
														}
												
														// the data series
														options.series = [];
														$('tr', table).each( function(i) {
															var tr = this;
															$('th, td', tr).each( function(j) {
																var vPadre = $(this).parent().parent().get(0).tagName;
																if ((vPadre == 'THEAD') || (vPadre == 'TBODY')) {
																	if (i == 0) { // get the name and init the series
																		if (($("#tipo").val() == "circularporcentaje") || ($("#tipo").val() == "circularvalores")) {
																			options.series[j - 1] = {
																				type: 'pie',
																				name: this.innerHTML,
																				data: []
																			};
																		} else {
																			options.series[j - 1] = {
																				name: this.innerHTML,
																				data: []
																			};
																		}
																	} else { // add values
																		if (($("#tipo").val() == "circularporcentaje") || ($("#tipo").val() == "circularvalores")) {
																			var valores = [];
																			if ($(this).siblings().filter("th").length) {																		
																				valores.push($(this).siblings().filter("th").text());
																			} else {
																				valores.push((j-1));																		
																			}
																			valores.push(parseFloat(this.innerHTML));

																			options.series[j - 1].data.push(valores);
																		} else {
																			options.series[j - 1].data.push(parseFloat(this.innerHTML));
																		}
																	}
																}
															});
														});
												
														var chart = new Highcharts.Chart(options);
													}
												
													var table = document.getElementById('listado');

													switch ($("#tipo").val()) {
														case "lineal":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'line'
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' },
																	labels : { rotation: -90,align: 'right' }
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	line: {
																		dataLabels: {
																			enabled: true
																		}
																	}
																}
															};
															break;
														case "lineal_invertida":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'line',
																	inverted: true
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' }
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	line: {
																		dataLabels: {
																			enabled: true
																		}
																	}
																}
															};
															break;
														case "area":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'area'
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' },
																	labels : { rotation: -90,align: 'right' }
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	area: {
																		pointStart: 0
																	}
																}
															};
															break;
														case "area_invertida":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'area',
																	inverted: true
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' }
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	area: {
																		pointStart: 0
																	}
																}
															};
															break;
														case "barra":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'bar'
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' },
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	bar: {
																		dataLabels: {
																			enabled: true
																		}
																	}
																}
															};
															break;
														case "columna":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	type: 'column'
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																xAxis: {
																	title: { text: 'Datos' },
																	labels : { rotation: -90,align: 'right' }
																},
																yAxis: {
																	title: { text: 'Cantidad' }
																},
																tooltip: {
																	formatter: function() {
																		return '<b>'+ this.series.name +'</b><br/>'+
																			this.y +' : '+ this.x;
																	}
																},
																plotOptions: {
																	column: {
																		dataLabels: {
																			enabled: true
																		}
																	}
																}
															};
															break;
														case "circularporcentaje":
															var options = {
																chart: {
																	renderTo: 'container_graph'
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																tooltip: {
																	pointFormat: '{series.name}: <b>{point.percentage}%</b> ({point.y})',
																	percentageDecimals: 1
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
																				return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
																			}
																		},
																		showInLegend: true
																	}
																}
															};
															break;
														case "circularvalores":
															var options = {
																chart: {
																	renderTo: 'container_graph',
																	plotBackgroundColor: null,
																	plotBorderWidth: null,
																	plotShadow: false
																},
																title: {
																	text: $("#dato option:selected").text()
																},
																subtitle: {
																	text: "Del "+ $("#fdesde").val() + " al " + $("#fhasta").val()
																},
																tooltip: {
																	pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage}%)',
																	percentageDecimals: 1
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
																				return '<b>'+ this.point.name +'</b>: '+ this.point.y + ' ('+this.percentage.toFixed(2)+'%)';
																			}
																		},
																		showInLegend: true
																	}
																}
															};
															break;
													}
												
													Highcharts.visualize(table, options);

													break;
												case "tab02":
													//	ver datos
													break;
											}
										});			

									});
									
								});
								</script>

								<div id="container_graph" style="min-width: 400px; height: 600px; margin: 0 auto;width: 90%;"></div>
							</div>
							<div id="tab02" class="tab-pane active">
								<table id="listado" class="table table-striped table-bordered table-condensed">
								<thead>
								<tr>
									<th>datos</th>
									<th width="100">visitas</th>
								</tr>
								</thead>
								<tbody>
								<?php
									$suma_cantidad = 0;

									//	-----------------------------
									if(isset($_GET['dato']) and ($_GET['dato'] == "xvisita")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:date'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:date')
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xvisitorType")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:visitorType'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:visitorType')
										);
									}

									//	-----------------------------
									if(isset($_GET['dato']) and ($_GET['dato'] == "xnavegador")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:browser'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:browser')
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xlenguaje")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:language'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:language')
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xso")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:operatingSystem'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:operatingSystem')
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xresolucion")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:screenResolution'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:screenResolution')
										);
									}

									//	-----------------------------
									if(isset($_GET['dato']) and ($_GET['dato'] == "xhostname")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:hostname'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:hostname'
												)
										);
									}

									if(isset($_GET['dato']) and ($_GET['dato'] == "xpagePath")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:pagePath'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:visitors'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xpageTitle")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:pageTitle'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:pageTitle'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xlandingPagePath")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:landingPagePath'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:landingPagePath'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xexitPagePath")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:exitPagePath'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:exitPagePath'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xpreviousPagePath")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:previousPagePath'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:previousPagePath'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xnextPagePath")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:nextPagePath'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:nextPagePath'
												)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xpageDepth")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:pageDepth'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:pageDepth'
												)
										);
									}

									//	-----------------------------
									if(isset($_GET['dato']) and ($_GET['dato'] == "xpalabraclave")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:keyword'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:visitors'
											)
										);
									}

									if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchKeyword")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:searchKeyword'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:searchKeyword'
											)
										);
									}

									if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchCategory")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:searchCategory'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:searchCategory'
											)
										);
									}

									if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchStartPage")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:searchStartPage'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:searchStartPage'
											)
										);
									}

									if(isset($_GET['dato']) and ($_GET['dato'] == "xsearchDestinationPage")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:searchDestinationPage'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:searchDestinationPage'
											)
										);
									}
									//	-----------------------------
									if(isset($_GET['dato']) and ($_GET['dato'] == "xcontinente")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:continent'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:continent'
											)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xpais")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:country'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:country'
											)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xregion")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:region'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:region'
											)
										);
									}
									if(isset($_GET['dato']) and ($_GET['dato'] == "xciudad")) {
										$report = $ga->getReport(
											array('dimensions'=>urlencode('ga:city'),
												'metrics'=>urlencode('ga:visitors'),
												'sort'=>'ga:city'
											)
										);
									}

									//	-----------------------------
									if($report) {
										foreach($report as $clave=>$valor) {
											if(isset($_GET['dato']) and ($_GET['dato'] == "xvisita")) {
												$vFecha = substr($clave,6,2)."/".substr($clave,4,2)."/".substr($clave,0,4);
												$clave = $vFecha;
											}
											if(isset($_GET['dato']) and ($_GET['dato'] == "xvisitorType")) {
												if($clave == "New Visitor") { $vValor = "Visitante nuevo"; }
												if($clave == "Returning Visitor") { $vValor = "Visitante recurrente"; }
												$clave = $vValor;
											}
											foreach($valor as $clave2=>$valor2) {
												$suma_cantidad += $valor2;
												?>
								<tr>
									<th style="text-align: left;font-weight: normal;"><?php echo($clave); ?></th>
									<td style="text-align: right;"><?php echo($valor2); ?></td>
								</tr>
												<?php
											}
										}
									}
									?>
								</tbody>
								<tfoot>
								<tr>
									<td style="text-align: right;padding-right: 10px;"><em>Totales</em></td>
									<td style="text-align: right;"><?php echo($suma_cantidad); ?></td>
								</tr>
								</tfoot>
								</table>
							</div>
						</div>
					</div>
					<p>&nbsp;</p>
					<?php
				}
			} catch (Exception $e) {
				?>
				<form class="form-alert">
					<h3>Atenci&oacute;n</h3>
					<div class="body-alert">
						<img src="../images/exclamacion.png" alt="" title=""/>
						<p>Se ha obtenido el siguiente mensaje de error:</p>
						<p><strong><?php echo("Error: ".$e->getMessage()); ?></strong></p>
						<p>Vuelva a intentarlo o comuniquese con el adminitrador del sistema.</p>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='index.php'" />
						<input type="button" class="btn btn-large" value="Volver a intentar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
				<?php	
			}
			//	Fin Listado
		}

		//	-----	ACCESO DE USUARIOS
		if(isset($_GET['menu']) and ($_GET['menu'] == "accesos")) {
			$url_modulo = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'];
			if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
			$url_modulo .= "&anio=".$_GET['anio'];

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT ID_CONTADOR,NOMBRE_COMPLETO,sucursales.NOMBRE as SUCURSAL,IP,contador.NOMBRE,IDIOMA,FECHA,HORA FROM contador LEFT JOIN usuarios ON usuarios.id_usuario = contador.id_usuario LEFT JOIN sucursales ON sucursales.id_sucursal = contador.id_sucursal WHERE (contador.id_usuario is not null) and (year(fecha) = '".$_GET['anio']."') ";
				if(isset($_GET['texto'])) {
					$query .= " and ((upper(nombre_completo) LIKE upper('%".$_GET['texto']."%')) or (upper(ip) LIKE upper('%".$_GET['texto']."%')) or (upper(contador.nombre) LIKE upper('%".$_GET['texto']."%')) or (upper(idioma) LIKE upper('%".$_GET['texto']."%')) ) ";
				}
				$query .= "ORDER BY fecha DESC,hora DESC";

				showTable2($conexion,
					$query,
					array("ID_CONTADOR"=>"Id",
						"NOMBRE_COMPLETO"=>"Usuario",
						"SUCURSAL"=>"Sucursal",
						"IP"=>"Ip",
						"NOMBRE"=>"Nombre",
						"IDIOMA"=>"Idioma",
						"FECHA"=>"Fecha",
						"HORA"=>"Hora"),
					array(0=>"ID_CONTADOR"),
					$cantidad_filas_x_pagina,true,true,$datos_acceso);
				?>
				<script type="text/javascript">
					const btNuevoRegistro = document.querySelector('#btNuevoRegistro')
					if(btNuevoRegistro){
						btNuevoRegistro.remove()
					}
					const btEditarRegistro = document.querySelector('#btEditarRegistro')
					if(btEditarRegistro){
						btEditarRegistro.remove()
					}
					const mnFilaEditarRegistro = document.querySelector('#mnFilaEditarRegistro')
					if(mnFilaEditarRegistro){
						mnFilaEditarRegistro.remove()
					}
				</script>
				<?php
			}

			//Nuevo
			if(isset($_GET['accion']) and ($_GET['accion'] == "nuevo")) {
				?>
				<form class="form-alert">
					<h3>Atenci&oacute;n</h3>
					<div class="body-alert">
						<img src="../images/exclamacion.png" alt="" title=""/>
						<p>No se esta permitido agregar registros en este modulo.</p>
					</div>
					<div class="form-actions">
						<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
					</div>
				</form>
				<?php
			}

			//	Ver
			if(isset($_GET['accion']) and (($_GET['accion'] == "ver") or ($_GET['accion'] == "editar"))) {
				$id_modo_ver = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT contador.*,usuarios.NOMBRE_COMPLETO,sucursales.NOMBRE as SUCURSAL FROM contador LEFT JOIN usuarios ON usuarios.id_usuario = contador.id_usuario LEFT JOIN sucursales ON sucursales.id_sucursal = contador.id_sucursal WHERE (id_contador = '".$id_modo_ver."')");
				while(!$registros->EOF) {
					?>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" id="signup" class="form-horizontal">
						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<input type="hidden" name="id_contador" value="<?php echo($registros->fields["ID_CONTADOR"]); ?>" />
									<div class="control-group">
										<label class="control-label">Usuario</label>
										<div class="controls">
											<input type="text" id="usuario" name="usuario" value="<?php echo(stripslashes($registros->fields["NOMBRE_COMPLETO"])); ?>" size="50" maxlength="255" title="Usuario" style="width: 99%" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Sucursal</label>
										<div class="controls">
											<input type="text" id="sucursal" name="sucursal" value="<?php echo(stripslashes($registros->fields["SUCURSAL"])); ?>" size="50" maxlength="255" title="Sucursal" style="width: 99%" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Ip</label>
										<div class="controls">
											<input type="text" id="ip" name="ip" value="<?php echo(stripslashes($registros->fields["IP"])); ?>" size="10" maxlength="15" title="IP" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" id="nombre" name="nombre" value="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>" size="10" maxlength="255" style="width: 99%;" title="Nombre" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Idioma</label>
										<div class="controls">
											<input type="text" id="idioma" name="idioma" value="<?php echo(stripslashes($registros->fields["IDIOMA"])); ?>" size="10" maxlength="50" title="Idioma" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Datos</label>
										<div class="controls">
											<input type="text" id="datos" name="datos" value="<?php echo(stripslashes($registros->fields["DATOS"])); ?>" size="50" maxlength="255" title="Datos" style="width: 99%" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Fecha / Hora</label>
										<div class="controls">
											<input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo($registros->fields["FECHA"]); ?>" class="input-medium" title="Fecha de Alta" placeholder="dd/mm/aaaa" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
											<input type="time" id="hora" name="hora" class="input-small" title="Hora" placeholder="hh:mm:ss" value="<?php echo($registros->fields["HORA"]); ?>" size="12" maxlength="10" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Consulta</label>
										<div class="controls">
											<input type="text" id="consulta" name="consulta" value="<?php echo(stripslashes($registros->fields["QUERY_STRING"])); ?>" size="50" maxlength="255" title="Consulta" style="width: 70%" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Referencia</label>
										<div class="controls">
											<input type="text" id="referencia" name="referencia" value="<?php echo(stripslashes($registros->fields["HTTP_REFERER"])); ?>" size="50" maxlength="255" title="Referencia" style="width: 70%" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
										</div>
									</div>
									<?php
										$actividades = $temp->Query("SELECT *, DATE_FORMAT(fechahora, '%H:%i:%s') as HORA, DATE_FORMAT(fechahora, '%Y-%m-%d') as FECHA FROM actividades_usuario WHERE (id_usuario = '".$registros->fields["ID_USUARIO"]."') and (DATE_FORMAT(fechahora, '%Y-%m-%d') = '".$registros->fields["FECHA"]."') ORDER BY fechahora");
										if($actividades) {
											if($actividades->RecordCount() > 0) {
												?>
									<h1>Actividades</h1>
									<table class="table table-striped table-bordered table-condensed">
									<thead>
									<tr>
										<th width="60">Hora</th>
										<th>Tipo</th>
										<th>Desripci&oacute;n</th>
									</tr>
									</thead>
									<tbody>
											<?php
												while(!$actividades->EOF) {
													?>
									<tr>
										<td><?php echo($actividades->fields["HORA"]) ?></td>
										<td><?php echo($actividades->fields["TIPO_ACTIVIDAD"]) ?></td>
										<td><?php echo($actividades->fields["DESCRIPCION_ACTIVIDAD"]) ?></td>
									</tr>
													<?php
													$actividades->MoveNext();	
												}
											?>
									</tbody>
									</table>
											<?php
											}
										}
									?>
								</div>
							</div>

							<div class="form-actions">
								<?php if($_GET['accion'] == "ver") { ?>
								<input type="button" class="btn btn-large btn-primary" value="Aceptar" onClick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } else { ?>
								<input type="submit" class="btn btn-large btn-primary" value="Guardar cambios" onclick="return confirm('Se va a actualizar el registro.\nEsta Ud. seguro?');" />
								<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>'" />
								<?php } ?>
							</div>
						</div>
					</form>
					<?php
					$registros->MoveNext();
				}
			}

			//	Eliminar
			if(isset($_GET['accion']) and ($_GET['accion'] == "eliminar")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//elimino el registro
						$conexion->Query("DELETE FROM contador WHERE id_contador = '".$valor."'");			
					}		
					echo("{ \"estado\" : \"ok\" }");
				}
			}
		}
		//	-----	Fin ACCESO DE USUARIOS

		include("../footer.php");
		?>
	</body>
</html>