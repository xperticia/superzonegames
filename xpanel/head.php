<?php include("preload.php"); ?>
	<header>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">

					<div class="navbar navbar-inverse">
						<div class="navbar-inner">
							<div class="container">
								<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="brand" href="./" title="XPANEL: Sistema de Administraci&oacute;n de Contenidos"><img src="../images/logo_xpanel.png" border="0" alt="XPANEL v3" /></a>

								<div class="nav-collapse collapse">
									<ul class="nav">
										<?php
											//obtengo las categorias
											if(empty($_SESSION['usuario_id'])) {
												$registros = $conexion->Execute("SELECT CATEGORIA,URL FROM menu_modulos WHERE categoria <> '' GROUP BY categoria ORDER BY orden");
											} else {
												$registros = $conexion->Execute("SELECT * FROM privilegios LEFT JOIN menu_modulos ON menu_modulos.id_menu = privilegios.id_menu WHERE (id_usuario = '".$_SESSION['usuario_id']."') GROUP BY categoria ORDER BY orden");			
												if($registros->RecordCount() == 0) {
													$_GET['error_login'] = 11;
												}
											}
											if($registros) {
												$i = 1;
												while(!$registros->EOF) {
													//	obtengo el nombre del archivo del modulo actual 
													$vector = explode("/", $_SERVER['PHP_SELF']);
													$url_modulo = $vector[count($vector)-1];
													$datos_categoria = $conexion->GetRow("SELECT * FROM menu_modulos WHERE (url like '".$url_modulo."?menu=".$_GET['menu']."')");
													?>
										<li <?php if($datos_categoria and ($datos_categoria["CATEGORIA"] == $registros->fields["CATEGORIA"])) { echo("class=\"dropdown active\""); } else { echo("class=\"dropdown\""); } ?>>
											<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo($registros->fields["CATEGORIA"]); ?> <b class="caret"></b></a>
											<ul class="dropdown-menu">
													<?php
													//obtengo los item de cada categoria
													if(empty($_SESSION['usuario_id'])) {
														$registros2 = $temp->Execute("SELECT * FROM menu_modulos WHERE (categoria = '".$registros->fields["CATEGORIA"]."') ORDER BY orden");
													} else {
														$registros2 = $temp->Execute("SELECT * FROM privilegios LEFT JOIN menu_modulos ON (menu_modulos.id_menu = privilegios.id_menu) or ((menu_modulos.nombre_menu = 'Divisor') and (menu_modulos.url = '-')) WHERE (id_usuario = '".$_SESSION['usuario_id']."') and (categoria = '".$registros->fields["CATEGORIA"]."') GROUP BY orden ORDER BY orden");
													}
													while(!$registros2->EOF) {
														$vector = explode("=", $registros2->fields["URL"]);
														$opcion = $vector[count($vector)-1];
														if((strtolower($registros2->fields["NOMBRE_MENU"]) == "divisor") and ($registros2->fields["URL"] == "-")) {
															?>
													<li class="divider"></li>
															<?php
														} else {
															?>
													<li><a href="<?php echo("../../".$path_modulos.$registros2->fields["URL"]); ?>"><?php echo($registros2->fields["NOMBRE_MENU"]); ?></a></li>
															<?php
														}
														$registros2->MoveNext();
													}

													$registros->MoveNext();
													$i++;
													?>
											</ul>
										</li>
													<?php
												}
											}
										?>
									</ul>
								    <form action="./" method="post" id="formTipoMoneda" class="navbar-form pull-left" style="padding-top: 8px;">
								    	<input type="hidden" name="tipo_cambio_vienede" value="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>">
								    	<input type="hidden" name="tipo_cambio_multiplo" value="<?php echo(convertirTipoMoneda()); ?>">
								    	<div class="input-prepend input-append">
 											<span class="add-on"><small>Mostrar en:</small></span>
											<select name="tipo_moneda" title="Seleccionar el tipo de moneda a mostrar" class="input-mini" style="margin-top: 0;" <?php if(isset($_GET['accion']) && (($_GET['accion'] == "nuevo") || ($_GET['accion'] == "editar"))){ echo('disabled'); } ?>>
												<option value="Bs" <?php if (empty($_SESSION['usuario_tipomoneda']) || $_SESSION['usuario_tipomoneda'] == 'Bs') { echo("selected='selected'"); } ?>>Bs</option>
												<option value="$ar" <?php if ($_SESSION['usuario_tipomoneda'] == '$ar') { echo("selected='selected'"); } ?>>$ar</option>
												<option value="$us" <?php if ($_SESSION['usuario_tipomoneda'] == '$us') { echo("selected='selected'"); } ?>>$us</option>
											</select>
 											<span class="add-on"><small>T.C.:</small></span>
      										<input type="text" name="tipo_cambio_actual" id="tipo_cambio_actual" value="<?php echo($_SESSION['usuario_tipocambio']); ?>" class="input-mini" style="text-align: right;" title="Tipo de Cambio" required="required" <?php if(isset($_GET['accion']) && (($_GET['accion'] == "nuevo") || ($_GET['accion'] == "editar"))){ echo('disabled'); } ?> />
											<span class="add-on"><small>Bs</small></span>
      										<div class="btn-group">
	      										<button type="submit" name="guadartipomoneda" class="btn" title="Establecer el Tipo de Moneda y Tipo de Cambio" <?php if(isset($_GET['accion']) && (($_GET['accion'] == "nuevo") || ($_GET['accion'] == "editar"))){ echo('disabled'); } ?>><i class="icon-ok-sign"></i></button>
								            </div>
      									</div>
    								</form>
									<ul class="nav pull-right">
										<?php
											$sucursal = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
										?>
										<li><a href="javascript:void(0);">Sucursal: <span class="label label-info"><?php echo(stripslashes($sucursal["NOMBRE"])); ?></span></a></li>
										<li></li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../images/opciones.gif" title="Opciones" align="absmiddle" /> <?php echo($_SESSION['usuario_login']); ?><b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li>
													<p>
														<small>Usuario:</small> <?php echo($_SESSION['usuario_login']); ?><br />
														<small>Nombre:</small> <?php echo($_SESSION['usuario_nombre']); ?><br />
														<small>Sucursal:</small> <?php echo("&quot;".stripslashes($sucursal["NOMBRE"])."&quot;"); ?>
													</p>
												</li>
												<li class="divider"></li>
												<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo("./?menu=usuarios&accion=cambiarclave&registro[]=".$_SESSION['usuario_id']); ?>';">Cambiar contrase&ntilde;a</a></li>
												<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo("./?menu=usuarios&accion=cambiarfoto&registro[]=".$_SESSION['usuario_id']); ?>';">Actualizar foto</a></li>
												<li><a href="javascript:void(0);" onclick="javascript:window.location.href='<?php echo("./?menu=usuarios&accion=cambiaremail&registro[]=".$_SESSION['usuario_id']); ?>';">Actualizar correo electr&oacute;nico</a></li>
												<li class="divider"></li>
												<li><a href="../" target="_top" title="Cerrar sesi&oacute;n">Cerrar sesi&oacute;n</a></li>
												<li><a href="ayuda.php" title="Ayuda">Ayuda</a></li>
											</ul>
										</li>
									</ul>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</header>

	<div class="container-fluid">
		<header>
			<h1><?php
				switch($_GET['menu']) {
					case "preguntas":
					case "respuestas":
					case "estadisticas":
						echo("Encuestas");
						break;
					case "subelementos":
						echo("Sub-Elementos");
						break;
					default:
						if(isset($datos_acceso['NOMBRE_MENU'])) { echo(ucfirst(strtolower($datos_acceso['NOMBRE_MENU']))); } else { echo(ucfirst(strtolower($_GET['menu']))); } 
						break;					
				}

				if(isset($_GET['accion'])) {
					switch($_GET['accion']) {
						case "nuevo":
						case "ver":
						case "editar":
						case "eliminar":
							if(($_GET['menu'] == "preguntas") or ($_GET['menu'] == "respuestas") or ($_GET['menu'] == "estadisticas")) {
								if($_GET['accion'] == "nuevo") {
									echo("<small>Nueva ".ucfirst(substr_replace($_GET['menu'],"",(strlen($_GET['menu'])-1),strlen($_GET['menu'])))."</small>");
								} else {
									echo("<small>".ucfirst($_GET['accion'])." ".ucfirst(substr_replace($_GET['menu'],"",(strlen($_GET['menu'])-1),strlen($_GET['menu'])))."</small>");
								}
							} else {
								echo("<small>".ucfirst($_GET['accion'])." registro</small>");
							}
							break;
						default:
							echo("<small>".ucfirst($_GET['accion'])."</small>");
							break;
					}
				} else {
					if(isset($_GET['menu']) and (($_GET['menu'] == "portada") or ($_GET['menu'] == "ayuda"))) {
						echo("<small>Sucursal: ".stripslashes($sucursal["NOMBRE"])."</small>");
					} else {
						if(($_GET['menu'] == "preguntas") or ($_GET['menu'] == "respuestas") or ($_GET['menu'] == "estadisticas")) {
							echo("<small>Lista de ".ucfirst($_GET['menu'])."</small>");
						} else {
							echo("<small>Lista de registros</small>");
						}
					}
				}
			?></h1>
		</header>
		<div class="row-fluid">
			<div class="span12" id="divListado">
