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
	</head>

	<body>
		<?php
		include("../head.php");

		//	-----	PRODUCTOS
		if(isset($_GET['menu']) && ($_GET['menu'] == "productos")) {

			//	Listado
			if(!isset($_GET['accion'])) {
				$query = "SELECT 
						CODIGO,
						NOMBRE,
						FOTO,
						TIPO_PRODUCTO,
						CATEGORIA,
						GENERO,
						(PRECIO_VENTA * ".convertirTipoMoneda().") PRECIO_VENTA,
						IF(STOCK >= 0,STOCK,0) as STOCK,
						SOLOAPEDIDO,
						ACTIVO,
						ID_PRODUCTO 
					FROM productos 
					WHERE 
						(id_sucursal = '{$_SESSION['usuario_sucursal']}') 
						and (eliminado = 0) 
				";
				if(isset($_GET['texto'])) {
					$query .= " AND ( 
						(upper(codigo) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(nro_serie) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(codigo_barra) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(nombre) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(tipo_producto) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(categoria) LIKE upper('%".$_GET['texto']."%')) or 
						(upper(genero) LIKE upper('%".$_GET['texto']."%')) 
					) ";
				}
				$query .= "ORDER BY codigo";

				showTable2(
					$conexion,
					$query,
					array(
						"CODIGO"=>"C&oacute;digo",
						"NOMBRE"=>"Nombre",
						"FOTO"=>"<i class=\"icon-picture\"></i>",
						"TIPO_PRODUCTO"=>"Tipo",
						"CATEGORIA"=>"Categor&iacute;a",
						"GENERO"=>"G&eacute;nero",
						"PRECIO_VENTA"=>"Precio Venta",
						"STOCK"=>"Stock",
						"SOLOAPEDIDO"=>"A Pedido",
						"ACTIVO"=>"Activo"
					),
					array(0=>"ID_PRODUCTO"),
					$_SESSION['usuario_rxp'],true,true,$datos_acceso,null,
					array(
						"aumentarStock"=>"Aumentar Stock",
						"exportarStock"=>"Exportar Stock",
						"importarStock"=>"Importar Stock",
						"renombrarTipo"=>"Renombrar Tipo",
						"renombrarCategoria"=>"Renombrar Categoria",
						"renombrarGenero"=>"Renombrar Genero",
						"desactivarCategoria"=>"Activar/Desactivar Categoria"
					),
					array("ventasregistradas"=>"Ventas registradas")
				);
			}

			// Nuevo
			if(isset($_GET['accion']) && ($_GET['accion'] == "nuevo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
							<li><a href="#tab02" data-toggle="tab">Descripci&oacute;n</a></li>
							<li><a href="#tab03" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
							<li><a href="#tab04" data-toggle="tab">Comentarios</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active">
								<div class="row-fluid">
									<div class="span8">
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label" for="tipo_producto">Tipo de Producto</label>
													<div class="controls">
														<input type="text" id="tipo_producto" name="tipo_producto" size="50" maxlength="255" title="Tipo de Producto" placeholder="Tipo de Producto" required />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label" for="codigo_barra">C&oacute;digo de Barra</label>
													<div class="controls">
														<input type="text" id="codigo_barra" name="codigo_barra" size="50" maxlength="50" title="C&oacute;digo de Barra" placeholder="C&oacute;digo de Barra" style="width: 96%;" />
													</div>
												</div>
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<div class="control-group">
													<label class="control-label" for="categoria">Categor&iacute;a</label>
													<div class="controls">
														<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categor&iacute;a" placeholder="Categoria de Producto" required />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="control-group">
													<label class="control-label" for="codigo">C&oacute;digo</label>
													<div class="controls">
														<input type="hidden" id="codigo" name="codigo" class="input-small" maxlength="18" size="10" title="C&oacute;digo" />
														<input type="text" id="numero" name="numero" class="input-small" maxlength="13" size="13" title="Numero" style="text-align: right;" required />
														<input type="text" id="letra" name="letra" class="input-mini" maxlength="4" size="10" title="Iniciales" style="width: 35px;" required />
													</div>
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="nombre">Nombre</label>
											<div class="controls">
												<input type="text" id="nombre" name="nombre" size="50" maxlength="255" title="Nombre" style="width: 99%;" required />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="nro_serie">Nro. de Serie</label>
											<div class="controls">
												<div class="input-append">
													<input type="text" id="item_serie" name="item_serie" maxlength="50" title="Item de Serie" placeholder="ingrese el nro. de serie y presione ENTER" style="width: 86%;" />
													<button type="button" id="btnAgregaSerie" class="btn btn-secondary btn-sm" title="Agregar Número de Serie"><i class="icon-plus"></i></button>
												</div>
												<select name="nro_serie[]" id="nro_serie" title="Nro. de Serie" multiple="true" size="5" style="width: 99%;">
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label" for="genero">G&eacute;nero</label>
											<div class="controls">
												<input type="text" id="genero" name="genero" size="50" maxlength="255" title="G&eacute;nero" required />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="genero">Idioma</label>
											<div class="controls">
												<input type="text" id="idioma" name="idioma" size="50" maxlength="255" title="Idioma" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="archivo_foto">Imagen</label>
											<div class="controls">
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="archivo_foto" name="archivo_foto" class="input-file" title="Imagen" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label" for="marca">Desarrollador/Marca</label>
											<div class="controls">
												<input type="text" id="marca" name="marca" size="50" maxlength="255" title="Desarrollador/Marca" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha de Lanzamiento</label>
											<div class="controls">
												<input type="date" name="fecha_lanzamiento" id="fecha_lanzamiento" value="" max="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Lanzamiento" size="12" maxlength="10" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Fecha de Vencimiento</label>
											<div class="controls">
												<input type="date" name="fecha_vencimiento" value="" max="<?php echo(date("Y-m-d")); ?>" class="input-medium" title="Fecha de Vencimiento" size="12" maxlength="10" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_costo">Precio de Costo</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_costo" name="precio_costo" size="50" maxlength="255" title="Precio de Costo" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_venta">Precio de Venta Unitario</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_venta" name="precio_venta" size="50" maxlength="255" title="Precio de Venta" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="cantidad_pieza_caja">Cantidad de Piezas por Caja</label>
											<div class="controls">
												<input type="text" id="cantidad_pieza_caja" name="cantidad_pieza_caja" value="" size="50" maxlength="255" title="Cantidad de Piezas por Caja" pattern="[-+]?[0-9]*" class="input-small" placeholder="0" style="text-align: right;" />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_venta_caja">Precio de Venta por Caja</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_venta_caja" name="precio_venta_caja" size="50" maxlength="255" title="Precio de Venta por Caja" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_total_venta_caja">Precio Total de Venta por Caja</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_total_venta_caja" name="precio_total_venta_caja" size="50" maxlength="255" title="Precio Total de Venta por Caja" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_costo_unitario">Precio de Costo x Unidad</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_costo_unitario" name="precio_costo_unitario" size="50" maxlength="255" title="Precio de Costo x UNIDAD" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_venta_docena">Precio de Venta por Docena</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_venta_docena" name="precio_venta_docena" size="50" maxlength="255" title="Precio de Venta por Docena" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="precio_total_venta_docena">Precio Total de Venta por Docena</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<input type="text" id="precio_total_venta_docena" name="precio_total_venta_docena" size="50" maxlength="255" title="Precio Total de Venta por Docena" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" />
													<span class="add-on">Bs</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Stock</label>
											<div class="controls">
												<input type="text" id="stock" name="stock" value="0" size="50" maxlength="255" title="Stock" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="stock_minimo">Stock Minimo</label>
											<div class="controls">
												<input type="text" id="stock_minimo" name="stock_minimo" value="3" size="50" maxlength="255" title="Stock Minimo" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="stock_maximo">Stock Maximo</label>
											<div class="controls">
												<input type="text" id="stock_maximo" name="stock_maximo" value="10" size="50" maxlength="255" title="Stock Maximo" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="0" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Visible en el Sitio Web</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="visible" name="visible" value="1" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="visible" name="visible" value="0" checked="checked" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Solo a Pedido</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="soloapedido" name="soloapedido" value="1" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="soloapedido" name="soloapedido" value="0" checked="checked" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div id="tab02" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Descripci&oacute;n</label>
									<div class="controls">
										<textarea class="ckeditor" id="contenido" name="contenido" rows="10" cols="38" title="Contenido" style="width: 99%"></textarea>
									</div>
								</div>
								<div class="control-group" style="margin-top: 10px;">
									<label class="control-label">Im&aacute;genes de la Descripci&oacute;n <a href="javascript:void();" rel="popover" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o. En el lugar donde desee que aparezca cada una de las im&aacute;genes coloque el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" />

										<input type="hidden" id="indicadorImagen" value="1" />
										<div id="adjuntos_contenido1" class="cloneImagen">
											<label class="control-label">[IMAGEN01]</label>
											<div class="controls">
												<input type="checkbox" id="redimensionar" name="redimensionar[]" title="Redimensionar imagen" />
												<input type="text" id="comentario_imagen" name="comentario_imagen[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" />
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="imagen_adjunta" name="imagen_adjunta[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
									</div>
								</div>
								<hr />
								<div class="control-group">
									<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" />

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

							<div id="tab03" class="tab-pane">
								<div class="control-group">
									<label class="control-label">Galeria de Im&aacute;genes <a href="javascript:void();" rel="popover" title="Galeria de Im&aacute;genes" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
									<div class="controls">
										<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" />

										<input type="hidden" id="indicadorImagenGaleria" value="1" />
										<div id="adjuntos_galeria1" class="cloneImagen">
											<label class="control-label">[IMAGEN01]</label>
											<div class="controls">
												<input type="checkbox" id="redimensionarGaleria" name="redimensionarGaleria[]" title="Redimensionar imagen" />
												<input type="text" id="comentario_imagenGaleria" name="comentario_imagenGaleria[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" />
												<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
												<input type="file" id="imagen_adjuntaGaleria" name="imagen_adjuntaGaleria[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" />
											</div>
										</div>
									</div>
								</div>
							</div>

							<div id="tab04" class="tab-pane">
								<div class="control-group">
									<textarea class="ckeditor" id="comentarios" name="comentarios" rows="40" cols="60" title="Comentarios" style="width:100%"></textarea>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Guardar datos" onclick="return confirm('Se va a agregar un nuevo registro.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			// Insertar
			if(isset($_GET['accion']) && ($_GET['accion'] == "insert")) {
				$valores_nroserie = "";
				if(isset($_POST['nro_serie'])){
					foreach($_POST['nro_serie'] as $NS){
						$valores_nroserie .= trim($NS)."|"; 
					}
				}

				$_POST['stock_minimo'] = $_POST['stock_minimo'] == '' ? '0' : $_POST['stock_minimo'];
				$_POST['stock_maximo'] = $_POST['stock_maximo'] == '' ? '0' : $_POST['stock_maximo'];

				$_POST['stock'] = empty($_POST['stock']) ? '0' : $_POST['stock'];
				$vStock = str_replace(".","",$_POST['stock']);
				$vStock = str_replace(",",".",$vStock);

				$_POST['precio_costo'] = $_POST['precio_costo'] == '' ? '0' : $_POST['precio_costo'];
				$vPrecioCosto = str_replace(".","",$_POST['precio_costo']);
				$vPrecioCosto = str_replace(",",".",$vPrecioCosto);

				$_POST['precio_venta'] = $_POST['precio_venta'] == '' ? '0' : $_POST['precio_venta'];
				$vPrecioVenta = str_replace(".","",$_POST['precio_venta']);
				$vPrecioVenta = str_replace(",",".",$vPrecioVenta);

				$_POST['cantidad_pieza_caja'] = $_POST['cantidad_pieza_caja'] == '' ? '0' : $_POST['cantidad_pieza_caja'];

				$_POST['precio_costo_unitario'] = $_POST['precio_costo_unitario'] == '' ? '0' : $_POST['precio_costo_unitario'];
				$vPrecioCostoUnitario = str_replace(".","",$_POST['precio_costo_unitario']);
				$vPrecioCostoUnitario = str_replace(",",".",$vPrecioCostoUnitario);

				$_POST['precio_venta_caja'] = $_POST['precio_venta_caja'] == '' ? '0' : $_POST['precio_venta_caja'];
				$vPrecioVentaCaja = str_replace(".","",$_POST['precio_venta_caja']);
				$vPrecioVentaCaja = str_replace(",",".",$vPrecioVentaCaja);

				$_POST['precio_total_venta_caja'] = $_POST['precio_total_venta_caja'] == '' ? '0' : $_POST['precio_total_venta_caja'];
				$vPrecioTotalVentaCaja = str_replace(".","",$_POST['precio_total_venta_caja']);
				$vPrecioTotalVentaCaja = str_replace(",",".",$vPrecioTotalVentaCaja);

				$_POST['precio_venta_docena'] = $_POST['precio_venta_docena'] == '' ? '0' : $_POST['precio_venta_docena'];
				$vPrecioVentaDocena = str_replace(".","",$_POST['precio_venta_docena']);
				$vPrecioVentaDocena = str_replace(",",".",$vPrecioVentaDocena);

				$_POST['precio_total_venta_docena'] = $_POST['precio_total_venta_docena'] == '' ? '0' : $_POST['precio_total_venta_docena'];
				$vPrecioTotalVentaDocena = str_replace(".","",$_POST['precio_total_venta_docena']);
				$vPrecioTotalVentaDocena = str_replace(",",".",$vPrecioTotalVentaDocena);

				if(isset($_POST['id_producto'])) {
					//	Update
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Actualizando registro...</h5>");
					//	verifico si el codigo asignado al PRODUCTO editado no haya sido asignado
					$codigo = trim($_POST['codigo']);
					$query = "SELECT COUNT(codigo) as CANTIDAD,NOMBRE, CATEGORIA 
						FROM productos 
						WHERE 
							(id_sucursal = '{$_POST['sucursal']}') 
							and (id_producto <> '{$_POST['id_producto']}') 
							and (codigo = '{$codigo}') 
						GROUP BY codigo
					";
					$datosConsultados = $temp->Query($query);

					if($datosConsultados and ($datosConsultados->RecordCount() > 0)){
						echo("<script type='text/javascript'>");
						echo("alert('El Producto editado con el Código: \"".trim($_POST['codigo'])."\", ya ha sido asignado \\nal Producto: \"".$datosConsultados->fields["NOMBRE"]."\" en la Categoría: \"".$datosConsultados->fields["CATEGORIA"]."\".');");
						echo("</script>");
					} else {
						$actualizacion_set = !empty($_POST['id_actualizacion']) ? "id_actualizacion = '".trim($_POST['id_actualizacion'])."', " : '';

						$fechaLanzamiento_set = !empty($_POST['fecha_lanzamiento']) && ($_POST['fecha_lanzamiento'] != '0000-00-00') ? 'fecha_lanzamiento = "'.$_POST['fecha_lanzamiento'].'", ' : 'fecha_lanzamiento = null, ';
						$fechaVencimiento_set = !empty($_POST['fecha_vencimiento']) && ($_POST['fecha_vencimiento'] != '0000-00-00') ? 'fecha_vencimiento = "'.$_POST['fecha_vencimiento'].'", ' : 'fecha_vencimiento = null, ';

						$query = "UPDATE productos 
							SET 
								{$actualizacion_set}
								tipo_producto = '".addslashes(trim($_POST['tipo_producto']))."', 
								nro_serie = '".$valores_nroserie."', 
								codigo_barra = '".addslashes(trim($_POST['codigo_barra']))."', 
								codigo = '".trim($_POST['codigo'])."', 
								categoria = '".addslashes(trim($_POST['categoria']))."', 
								nombre = '".addslashes(trim($_POST['nombre']))."', 
								genero = '".addslashes(trim($_POST['genero']))."', 
								marca = '".addslashes(trim($_POST['marca']))."', 
								idioma = '".addslashes(trim($_POST['idioma']))."', 
								{$fechaLanzamiento_set}
								descripcion = '".addslashes($_POST['contenido'])."', 
								precio_costo = '".number_format($vPrecioCosto, 2, '.', '')."', 
								precio_venta = '".number_format($vPrecioVenta, 2, '.', '')."', 
								cantidad_pieza_caja = '".$_POST['cantidad_pieza_caja']."', 
								precio_costo_unitario = '".number_format($vPrecioCostoUnitario, 2, '.', '')."', 
								precio_venta_caja = '".number_format($vPrecioVentaCaja, 2, '.', '')."', 
								precio_total_venta_caja = '".number_format($vPrecioTotalVentaCaja, 2, '.', '')."', 
								precio_venta_docena = '".number_format($vPrecioVentaDocena, 2, '.', '')."', 
								precio_total_venta_docena = '".number_format($vPrecioTotalVentaDocena, 2, '.', '')."', 
								stock_minimo = '".$_POST['stock_minimo']."', 
								stock_maximo = '".$_POST['stock_maximo']."', 
								activo = '".$_POST['activo']."', 
								comentarios = '".addslashes($_POST['comentarios'])."', 
								soloapedido = '".$_POST['soloapedido']."', 
								visible = '".$_POST['visible']."', 
								id_sucursal = '".$_POST['sucursal']."', 
								{$fechaVencimiento_set}
								stock = '".number_format($vStock, 2, '.', '')."'
							WHERE 
								(id_producto = '".$_POST['id_producto']."')
						";
						$conexion->Query($query);
						$id_registro = $_POST['id_producto'];
						$id_producto = sprintf("P%08s", $id_registro);

						$usuario_set = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null';

						// verifico si hizo cambios en el STOCK
						$_POST['stock_backup'] = $_POST['stock_backup'] == '' ? '0' : $_POST['stock_backup'];
						$vStockBackup = str_replace(".","",$_POST['stock_backup']);
						$vStockBackup = str_replace(",",".",$vStockBackup);						
						if($vStock != $vStockBackup){
							$tipo = '';
							$cantidad = 0;

							if($vStock > $vStockBackup){
								$tipo = 'Aumento';
								$cantidad = $vStock - $vStockBackup;
							} else {
								if($vStock < $vStockBackup){
									$tipo = 'Disminución';
									$cantidad = $vStockBackup - $vStock;
								}
							}

							// registro el movimiento de stock
							$query = "INSERT INTO movimientos_stock (
								ID_MOVIMIENTOSTOCK,
								ID_USUARIO,
								ID_PRODUCTO,
								ID_SUCURSAL,
								FECHA_REGISTRO,
								STOCK_ACTUAL,
								CANTIDAD,
								STOCK_FINAL,
								TIPO_MOVIMIENTOSTOCK
							) VALUES(
								null,
								{$usuario_set},
								'" . $id_registro . "',
								'" . $_POST['sucursal'] . "',
								'" . date('Y-m-d H:i:s') . "',
								'" . $vStockBackup . "',
								'" . $cantidad . "',
								'" . $vStock . "',
								'".$tipo."'
							)";
							$conexion->Query($query);

						}
		
					}
				} else {
					//	Insert
					echo("<h5><img src='../images/preloader.gif' border='0' alt='' /> Insertando registro...</h5>");
					//	verifico si el codigo asignado al nuevo PRODUCTO no haya sido asignado
					$codigo = trim($_POST['numero'])."-".trim($_POST['letra']);
					$query = "SELECT COUNT(codigo) as CANTIDAD,NOMBRE, CATEGORIA 
						FROM productos 
						WHERE 
							(id_sucursal = '{$_POST['sucursal']}') 
							and (codigo = '{$codigo}') 
						GROUP BY codigo
					";
					$datosConsultados = $temp->Query($query);

					if($datosConsultados && ($datosConsultados->RecordCount() > 0)){
						echo("<script type='text/javascript'>");
						echo("alert('El nuevo Producto con el Código: \"".trim($_POST['numero'])."-".trim($_POST['letra'])."\", ya ha sido asignado al Producto: \"".$datosConsultados->fields["NOMBRE"]."\" en la Categoría: \"".$datosConsultados->fields["CATEGORIA"]."\".');");
						echo("</script>");
					} else {
						$actualizacion_set = !empty($_POST['id_actualizacion']) ? $_POST['id_actualizacion'] : 'null';

						$fechaLanzamiento_set = !empty($_POST['fecha_lanzamiento']) && ($_POST['fecha_lanzamiento'] != '0000-00-00') ? '"'.$_POST['fecha_lanzamiento'].'", ' : 'null, ';
						$fechaVencimiento_set = !empty($_POST['fecha_vencimiento']) && ($_POST['fecha_vencimiento'] != '0000-00-00') ? '"'.$_POST['fecha_vencimiento'].'", ' : 'null, ';

						$query = "INSERT INTO productos 
							(
								ID_PRODUCTO,
								TIPO_PRODUCTO,
								NRO_SERIE,
								CODIGO_BARRA,
								CODIGO,
								CATEGORIA,
								NOMBRE,
								GENERO,
								MARCA,
								IDIOMA,
								FECHA_LANZAMIENTO,
								DESCRIPCION,
								PRECIO_COSTO,
								PRECIO_VENTA,
								CANTIDAD_PIEZA_CAJA,
								PRECIO_COSTO_UNITARIO,
								PRECIO_VENTA_CAJA,
								PRECIO_TOTAL_VENTA_CAJA,
								PRECIO_VENTA_DOCENA,
								PRECIO_TOTAL_VENTA_DOCENA,
								STOCK_MINIMO,
								STOCK_MAXIMO,
								ACTIVO,
								COMENTARIOS,
								SOLOAPEDIDO,
								VISIBLE,
								ID_SUCURSAL,
								STOCK,
								FECHA_VENCIMIENTO,
								ID_ACTUALIZACION
							) VALUES(
								null,
								'".addslashes(trim($_POST['tipo_producto']))."',
								'".$valores_nroserie."',
								'".addslashes(trim($_POST['codigo_barra']))."',
								'".trim($_POST['numero'])."-".trim($_POST['letra'])."',
								'".addslashes(trim($_POST['categoria']))."',
								'".addslashes(trim($_POST['nombre']))."',
								'".addslashes(trim($_POST['genero']))."',
								'".addslashes(trim($_POST['marca']))."',
								'".addslashes(trim($_POST['idioma']))."',
								{$fechaLanzamiento_set}
								'".addslashes($_POST['contenido'])."',
								'".number_format($vPrecioCosto, 2, '.', '')."',
								'".number_format($vPrecioVenta, 2, '.', '')."',
								'".$_POST['cantidad_pieza_caja']."',
								'".number_format($vPrecioCostoUnitario, 2, '.', '')."',
								'".number_format($vPrecioVentaCaja, 2, '.', '')."',
								'".number_format($vPrecioTotalVentaCaja, 2, '.', '')."',
								'".number_format($vPrecioVentaDocena, 2, '.', '')."',
								'".number_format($vPrecioTotalVentaDocena, 2, '.', '')."',
								'".$_POST['stock_minimo']."',
								'".$_POST['stock_maximo']."',
								'".$_POST['activo']."',
								'".addslashes($_POST['comentarios'])."',
								'".$_POST['soloapedido']."',
								'".$_POST['visible']."',
								'".$_POST['sucursal']."',
								'".number_format($vStock, 2, '.', '')."',
								{$fechaVencimiento_set}
								{$actualizacion_set}
							)";
						$conexion->Query($query);
						$id_registro = $conexion->Insert_ID();
						$id_producto = sprintf("P%08s", $id_registro);

						$usuario_set = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null';

						// registro el movimiento de stock
						$query = "INSERT INTO movimientos_stock (
								ID_MOVIMIENTOSTOCK,
								ID_USUARIO,
								ID_PRODUCTO,
								ID_SUCURSAL,
								FECHA_REGISTRO,
								STOCK_ACTUAL,
								CANTIDAD,
								STOCK_FINAL,
								TIPO_MOVIMIENTOSTOCK
							) VALUES(
								null,
								{$usuario_set},
								'" . $id_registro . "',
								'" . $_POST['sucursal'] . "',
								'" . date('Y-m-d H:i:s') . "',
								'0',
								'" . $vStock . "',
								'" . $vStock . "',
								'Inicial'
							)
						";
						echo($query);
						$conexion->Query($query);

					}
				}

				if($id_registro > 0){

					//	verifico la FOTO subidos
					if(isset($_FILES["archivo_foto"]) and isset($id_producto)) {
						//obtengo el nombre del archivo y su extension
						$res = explode(".", $_FILES['archivo_foto']['name']);
						$tipo_archivo = $_FILES['archivo_foto']['type'];
						$extension = $res[count($res) - 1];
						$nombre_archivo = $id_producto.".".$extension;

						//verifico si se subio un archivo
						if(is_uploaded_file($_FILES['archivo_foto']['tmp_name'])) {
							//verifico el tipo de archivo
							if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg")or
							($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
								//copio el archivo
								if(copy($_FILES['archivo_foto']['tmp_name'], stripslashes("../../".$path_imagenes."productos/".$nombre_archivo))) {
									//si no hubo inconvenientes actualiza el nombre del archivo
									$conexion->Query("UPDATE productos SET foto = '".$nombre_archivo."' WHERE (id_producto = '".$id_registro."')");

									//obtengo la ubicacion de la imagen original y small
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."productos/".$nombre_archivo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."productos/".$id_producto."_small.".$extension;
									//paso como parametro el nombre la nueva imagen pequeña
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 145, 210);
								}
								//elimino el archivo temporal
								unlink($_FILES['archivo_foto']['tmp_name']);
							} else {
								//elimino el archivo temporal, y muestro mensaje de error en tipo de archivo
								unlink($_FILES['archivo_foto']['tmp_name']);
								echo("<script type='text/javascript'>");
								echo("window.alert('".$_FILES['archivo_foto']['name'].": ".$msg_error[10]."');");
								echo("</script>");
							}
						}
					}

					//verifico las imagenes de CONTENIDO subidos
					if(isset($_FILES["imagen_adjunta"]) and isset($id_producto)) {
						$total = count($_FILES["imagen_adjunta"]["name"]);
						for($i = 0; $i < $total; $i++) {
							//	obtengo el nombre del archivo y su extension
							$datos = $temp->GetRow("SELECT IF(ID_ARCHIVO > 0, (max(ID_ARCHIVO) + 1), 1) as ID_ARCHIVO FROM archivos ORDER BY id_archivo desc");
							$id_imagen = sprintf("IMG%06s", $datos["ID_ARCHIVO"]);
							$res = explode(".", $_FILES['imagen_adjunta']['name'][$i]);
							$tipo_archivo = $_FILES['imagen_adjunta']['type'][$i];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_imagen.".".$extension;

							//	verifico si subio el archivo
							if(is_uploaded_file($_FILES['imagen_adjunta']['tmp_name'][$i])) {
							//	verifico el tipo de archivo
							if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
								($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
								//	copio el archivo
								copy($_FILES['imagen_adjunta']['tmp_name'][$i], stripslashes("../../".$path_imagenes."contenidos/".$nombre_archivo));
								//	elimino el archivo temporal
								unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
								//	inserto los datos del archivo
								$conexion->Query("INSERT INTO archivos 
									(
										ID_ARCHIVO,
										ID_PRODUCTO,
										NOMBRE_ARCHIVO,
										TIPO_ARCHIVO,
										DESTINO,
										DESCARGAS,
										COMENTARIO
									) VALUES(
										null,
										'".$id_registro."',
										'".$nombre_archivo."',
										'Imagen',
										'contenido', 
										0,
										'".addslashes($_POST['comentario_imagen'][$i])."'
									)");
								$id_archivo = $conexion->Insert_ID();
								//	redimensiono la imagen cargada
								if(isset($_POST['redimensionar']) and ($_POST['redimensionar'][$i] == 'on')) {
									//	obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$nombre_archivo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$id_imagen."_small.".$extension;
									//	paso como parametro el nombre la nueva imagen peque&ntilde;a
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
								}
							} else {
								//elimino el archivo temporal
								unlink($_FILES['imagen_adjunta']['tmp_name'][$i]);
								echo("<script type='text/javascript'>");
								echo("window.alert('".$_FILES['imagen_adjunta']['name'][$i].": ".$msg_error[10]."');");
								echo("</script>");
							}# if
							}# if
						}# for
					}# if

					//	verifico las imagenes de la GALERIA subidos
					if(isset($_FILES["imagen_adjuntaGaleria"]) and isset($id_producto)) {
						$total = count($_FILES["imagen_adjuntaGaleria"]["name"]);
						for($i = 0; $i < $total; $i++) {
							//	obtengo el nombre del archivo y su extension
							$datos = $temp->GetRow("SELECT IF(ID_ARCHIVO > 0, (max(ID_ARCHIVO) + 1), 1) as ID_ARCHIVO FROM archivos ORDER BY id_archivo desc");
							$id_imagen = sprintf("IMG%06s", $datos["ID_ARCHIVO"]);
							$res = explode(".", $_FILES['imagen_adjuntaGaleria']['name'][$i]);
							$tipo_archivo = $_FILES['imagen_adjuntaGaleria']['type'][$i];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_imagen.".".$extension;

							//	verifico si subio el archivo
							if(is_uploaded_file($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i])) {
							//	verifico el tipo de archivo
							if(($tipo_archivo == "image/pjpeg") or ($tipo_archivo == "image/jpeg") or
								($tipo_archivo == "image/gif") or ($tipo_archivo == "image/x-png") or ($tipo_archivo == "image/png")) {
								//	copio el archivo
								copy($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i], stripslashes("../../".$path_imagenes."contenidos/".$nombre_archivo));
								//	elimino el archivo temporal
								unlink($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i]);
								//	inserto los datos del archivo
								$conexion->Query("INSERT INTO archivos 
									(
										ID_ARCHIVO,
										ID_PRODUCTO,
										NOMBRE_ARCHIVO,
										TIPO_ARCHIVO,
										DESTINO,
										DESCARGAS,
										COMENTARIO
									) VALUES(
										null,
										'".$id_registro."',
										'".$nombre_archivo."',
										'Imagen',
										'galeria', 
										0,
										'".addslashes($_POST['comentario_imagenGaleria'][$i])."'
									)");

								//	redimensiono la imagen cargada
								if(isset($_POST['redimensionarGaleria']) and ($_POST['redimensionarGaleria'][$i] == 'on')) {
									//	obtengo la ubicacion de la imagen original
									$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$nombre_archivo;
									$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$id_imagen."_small.".$extension;
									//	paso como parametro el nombre la nueva imagen peque&ntilde;a
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
								}
							} else {
								//elimino el archivo temporal
								unlink($_FILES['imagen_adjuntaGaleria']['tmp_name'][$i]);
								echo("<script type='text/javascript'>");
								echo("window.alert('".$_FILES['imagen_adjuntaGaleria']['name'][$i].": ".$msg_error[10]."');");
								echo("</script>");
							}# if
							}# if
						}# for
					}# if

					//	verifico los ARCHIVOS ADJUNTOS subidos
					if(isset($_FILES["archivo_adjunto"]) and isset($id_producto)) {
						$total = count($_FILES["archivo_adjunto"]["name"]);
						for($i = 0; $i < $total; $i++) {
							//	obtengo el nombre del archivo y su extension
							$datos = $temp->GetRow("SELECT IF(ID_ARCHIVO > 0, (max(ID_ARCHIVO) + 1), 1) as ID_ARCHIVO FROM archivos ORDER BY id_archivo desc");
							$id_doc = sprintf("D0C%06s", $datos["ID_ARCHIVO"]);
							$res = explode(".", $_FILES['archivo_adjunto']['name'][$i]);
							$tipo_archivo = $_FILES['archivo_adjunto']['type'][$i];
							$extension = $res[count($res) - 1];
							$nombre_archivo = $id_doc.".".$extension;

							//	verifico si se subio un archivo
							if(is_uploaded_file($_FILES['archivo_adjunto']['tmp_name'][$i])) {
								//	copio el archivo
								copy($_FILES['archivo_adjunto']['tmp_name'][$i], stripslashes("../../".$path_archivos."contenidos/".$nombre_archivo));
								//	elimino el archivo temporal
								unlink($_FILES['archivo_adjunto']['tmp_name'][$i]);
								//	inserto los datos
								$conexion->Query("INSERT INTO archivos 
									(
										ID_ARCHIVO,
										ID_PRODUCTO,
										NOMBRE_ARCHIVO,
										TIPO_ARCHIVO,
										DESCARGAS,
										COMENTARIO
									) VALUES(
										null,
										'".$id_registro."',
										'".$nombre_archivo."',
										'Archivo', 
										0,
										'".addslashes($_POST['comentario_archivo'][$i])."'
									)");
								$id_archivo = $conexion->Insert_ID();
							}# if
						}# for
					}# if

				}

				echo("<script type='text/javascript'>");
				//echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			// Editar
			if(isset($_GET['accion']) && (($_GET['accion'] == "ver") || ($_GET['accion'] == "editar"))) {
				$id_modo_editar = $_GET['registro'][0];
				$registros = $conexion->Query("SELECT * FROM productos WHERE (id_producto = '".$id_modo_editar."')");
				while(!$registros->EOF) {
					$datosUsuarioActualizacion = $conexion->GetRow("SELECT NOMBRE_COMPLETO FROM usuarios WHERE (id_usuario = '".$registros->fields["ID_ACTUALIZACION"]."')");
					?>
					<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
					<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insert"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
						<input type="hidden" name="id_producto" id="id_producto" value="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" />
						<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($registros->fields["ID_SUCURSAL"]); ?>" />
						<input type="hidden" name="id_actualizacion" value="<?php echo($_SESSION['usuario_id']); ?>" />

						<div class="tabbable">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
								<li><a href="#tab02" data-toggle="tab">Descripci&oacute;n</a></li>
								<li><a href="#tab03" data-toggle="tab">Galeria de Im&aacute;genes</a></li>
								<li><a href="#tab04" data-toggle="tab">Comentarios</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ventasregistradas&registro[]=".$registros->fields["ID_PRODUCTO"]); ?>">Ventas registradas</a></li>
								<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=vermovimientostock&registro[]=".$registros->fields["ID_PRODUCTO"]); ?>">Movimientos de Stock</a></li>
							</ul>

							<div class="tab-content">
								<div id="tab01" class="tab-pane active">
									<div class="row-fluid">
										<div class="span8">
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label" for="tipo_producto">Tipo de Producto</label>
														<div class="controls">
															<input type="text" id="tipo_producto" name="tipo_producto" value="<?php echo(stripslashes($registros->fields["TIPO_PRODUCTO"])); ?>" size="50" maxlength="255" title="Tipo de Producto" placeholder="Tipo de Producto" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="control-group">
														<label class="control-label" for="codigo_barra">C&oacute;digo de Barra</label>
														<div class="controls">
															<input type="text" id="codigo_barra" name="codigo_barra" value="<?php echo(stripslashes($registros->fields["CODIGO_BARRA"])); ?>" size="50" maxlength="50" title="C&oacute;digo de Barra" placeholder="C&oacute;digo de Barra" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
											</div>
											<div class="row-fluid">
												<div class="span6">
													<div class="control-group">
														<label class="control-label">Categor&iacute;a</label>
														<div class="controls">
															<input type="text" id="categoria" name="categoria" value="<?php echo(stripslashes($registros->fields["CATEGORIA"])); ?>" size="50" maxlength="255" title="Categor&iacute;a" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
												<div class="span6">
													<div class="control-group">
														<label class="control-label">C&oacute;digo</label>
														<div class="controls">
															<input type="text" id="codigo" name="codigo" value="<?php echo(stripslashes($registros->fields["CODIGO"])); ?>" class="input-small" maxlength="16" size="16" title="C&oacute;digo" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														</div>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Nombre</label>
												<div class="controls">
													<input type="text" id="nombre" name="nombre" value="<?php echo(stripslashes($registros->fields["NOMBRE"])); ?>" size="50" maxlength="255" title="Nombre" style="width: 99%;" placeholder="Nombre del Producto" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="nro_serie">Nro. de Serie</label>
												<div class="controls">
													<div class="input-append">
														<input type="text" id="item_serie" name="item_serie" maxlength="50" title="Item de Serie" placeholder="ingrese el nro. de serie y presione ENTER" style="width: 86%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<button type="button" id="btnAgregaSerie" class="btn btn-secondary btn-sm" title="Agregar Número de Serie"><i class="icon-plus"></i></button>
													</div>
													<select name="nro_serie[]" id="nro_serie" title="Nro. de Serie" multiple="true" size="5" style="width: 99%;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>>
														<?php
														$lista = explode("|",$registros->fields["NRO_SERIE"]);
														foreach($lista as $item){
															if($item != ""){
																?>
														<option value="<?php echo($item); ?>"><?php echo($item); ?></option>
																<?php
															}
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<div class="control-group">
												<label class="control-label">G&eacute;nero</label>
												<div class="controls">
													<input type="text" id="genero" name="genero" value="<?php echo(stripslashes($registros->fields["GENERO"])); ?>" size="50" maxlength="255" title="G&eacute;nero" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Idioma</label>
												<div class="controls">
													<input type="text" id="idioma" name="idioma" value="<?php echo(stripslashes($registros->fields["IDIOMA"])); ?>" size="50" maxlength="255" title="Idioma" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Imagen 
												<?php 
													if($registros->fields['FOTO'] != null) { 
														$path_url_foto = "../../".$path_imagenes."productos/".$registros->fields['FOTO'];
														if(file_exists($path_url_foto)) {
															?>
													<a href="<?php echo($path_url_foto); ?>" class="Foto" rel="colorbox" title="<?php echo(htmlentities(stripslashes($registros->fields["NOMBRE"]))); ?>"><img src="../images/preview.gif" alt="Ver archivo"  align="absmiddle" /></a> 
													| <a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Imagen del Producto"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php 								
														} else {
															?>
													<a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros->fields["FOTO"]); ?>" data-tipo="foto" data-nombre-archivo="<?php echo($registros->fields['FOTO']); ?>" data-texto="Imagen del Producto"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a>
															<?php 
														}
													} 
												?>
												</label>
												<div class="controls">
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="archivo_foto" name="archivo_foto" class="input-file" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label">Desarrollador/Marca</label>
												<div class="controls">
													<input type="text" id="marca" name="marca" value="<?php echo(stripslashes($registros->fields["MARCA"])); ?>" size="50" maxlength="255" title="Desarrollador/Marca" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha de Lanzamiento</label>
												<div class="controls">
													<input type="date" name="fecha_lanzamiento" id="fecha_lanzamiento" value="<?php echo($registros->fields["FECHA_LANZAMIENTO"]); ?>" class="input-medium" title="Fecha de Lanzamiento" placeholder="dd/mm/aaaa" size="12" maxlength="10" placeholder="dd/mm/aaaa" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fecha de Vencimiento</label>
												<div class="controls">
													<input type="date" name="fecha_vencimiento" value="<?php echo($registros->fields["FECHA_VENCIMIENTO"]); ?>" class="input-medium" title="Fecha de Vencimiento" placeholder="dd/mm/aaaa" size="12" maxlength="10" placeholder="dd/mm/aaaa" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_costo">Precio de Costo</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_costo" name="precio_costo" value="<?php echo(number_format($registros->fields["PRECIO_COSTO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio de Costo" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;<?php echo( !empty($_SESSION['usuario_nivel']) ? 'display: none;' : '' ); ?>" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_venta">Precio de Venta Unitario</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_venta" name="precio_venta" value="<?php echo(number_format($registros->fields["PRECIO_VENTA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio de Venta" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
										</div>
									</div>
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="cantidad_pieza_caja">Cantidad de Piezas por Caja</label>
												<div class="controls">
													<input type="text" id="cantidad_pieza_caja" name="cantidad_pieza_caja" value="<?php if($registros->fields["CANTIDAD_PIEZA_CAJA"] == null) { echo(""); } else { echo(number_format($registros->fields["CANTIDAD_PIEZA_CAJA"], 0, ',', '.')); }  ?>" size="50" maxlength="255" title="Stock Minimo" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_venta_caja">Precio de Venta por Caja</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_venta_caja" name="precio_venta_caja" value="<?php echo(number_format($registros->fields["PRECIO_VENTA_CAJA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio de Venta por Caja" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_total_venta_caja">Precio Total de Venta por Caja</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_total_venta_caja" name="precio_total_venta_caja" value="<?php echo(number_format($registros->fields["PRECIO_TOTAL_VENTA_CAJA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio Total de Venta por Caja" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_costo_unitario">Precio de Costo x Unidad</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_costo_unitario" name="precio_costo_unitario" value="<?php echo(number_format($registros->fields["PRECIO_COSTO_UNITARIO"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio de Costo x UNIDAD" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;<?php echo( !empty($_SESSION['usuario_nivel']) ? 'display: none;' : '' ); ?>" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_venta_docena">Precio de Venta por Docena</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_venta_docena" name="precio_venta_docena" value="<?php echo(number_format($registros->fields["PRECIO_VENTA_DOCENA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio de Venta por Docena" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="precio_total_venta_docena">Precio Total de Venta por Docena</label>
												<div class="controls">
													<div class="input-prepend input-append">
														<input type="text" id="precio_total_venta_docena" name="precio_total_venta_docena" value="<?php echo(number_format($registros->fields["PRECIO_TOTAL_VENTA_DOCENA"], 2, ',', '.')); ?>" size="50" maxlength="255" title="Precio Total de Venta por Docena" pattern="^[0-9]\d{0,2}(\.\d{3})*(,\d+)?$" class="input-small" placeholder="0.00" style="text-align: right;" required <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
														<span class="add-on">Bs</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label" for="stock">Stock</label>
												<div class="controls">
													<input type="hidden" name="stock_backup" value="<?php echo($registros->fields["STOCK"]); ?>" />
													<input type="text" id="stock" name="stock" value="<?php echo($registros->fields["STOCK"]); ?>" size="50" maxlength="255" title="Stock" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Stock Minimo</label>
												<div class="controls">
													<input type="text" id="stock_minimo" name="stock_minimo" value="<?php if($registros->fields["STOCK_MINIMO"] == null) { echo("3"); } else { echo(stripslashes($registros->fields["STOCK_MINIMO"])); }  ?>" size="50" maxlength="255" title="Stock Minimo" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Stock Maximo</label>
												<div class="controls">
													<input type="text" id="stock_maximo" name="stock_maximo" value="<?php if($registros->fields["STOCK_MINIMO"] == null) { echo("10"); } else { echo(stripslashes($registros->fields["STOCK_MAXIMO"])); } ?>" size="50" maxlength="255" title="Stock Maximo" pattern="[-+]?[0-9]*" class="input-small" style="text-align: right;" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="row-fluid">
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Activo</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="1" <?php if($registros->fields["ACTIVO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="activo" name="activo" value="0" <?php if($registros->fields["ACTIVO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Visible en el Sitio Web</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="visible" name="visible" value="1" <?php if($registros->fields["VISIBLE"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="visible" name="visible" value="0" <?php if($registros->fields["VISIBLE"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Solo a Pedido</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" id="soloapedido" name="soloapedido" value="1" <?php if($registros->fields["SOLOAPEDIDO"] == 1) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[1]); ?>
													</label>
													<label class="radio inline">
														<input type="radio" id="soloapedido" name="soloapedido" value="0" <?php if($registros->fields["SOLOAPEDIDO"] == 0) { echo("checked='checked'"); } ?> <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> /> <?php echo($estados[0]); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
									<p class="help-inline">&Uacute;ltima modificaci&oacute;n realizada el <strong><?php echo(formato_fecha($registros->fields["FECHA_ACTUALIZACION"],6)); ?></strong> por <strong><?php if($datosUsuarioActualizacion) { echo($datosUsuarioActualizacion["NOMBRE_COMPLETO"]); } else { echo("No Asignado"); } ?></strong>.</p>
								</div>

								<div id="tab02" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Descripci&oacute;n</label>
										<div class="controls">
											<textarea class="ckeditor" id="contenido" name="contenido" rows="10" cols="38" title="Contenido" style="width: 99%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["DESCRIPCION"])); ?></textarea>
										</div>
									</div>
									<div class="control-group" style="margin-top: 10px;">
										<label class="control-label">Im&aacute;genes de la Descripci&oacute;n <a href="javascript:void();" rel="popover" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o. En el lugar donde desee que aparezca cada una de las im&aacute;genes coloque el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />

											<?php
											//	verifico si posee imagenes el contenido
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Imagen') and (destino = 'contenido') ORDER BY id_archivo");
											if($registros2->RecordCount() > 0) {
												?>
											<table class="table table-striped table-bordered table-condensed">
											<thead>
											<tr>
												<th width="50">Indicador</th>
												<th>Descripci&oacute;n</th>
												<th width="20">Formato</th>
												<th width="50">Tama&ntilde;o</th>
												<th width="20">Editar</th>
												<th width="20">Eliminar</th>
											</tr>
											</thead>
											<tbody>
												<?php
												while(!$registros2->EOF) {
													$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
													?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
													if(file_exists($nombre_completo_imagenoriginal)) {
														//	obtengo la extension del archivo
														$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														$formato = $res[count($res) - 1];
														//	obtengo el tama&ntilde;o del archivo
														$tamanio_archivo = ceil(filesize($nombre_completo_imagenoriginal) / 1024);
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
														if(file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														//	muestro un enlace para ver o descargar la imagen
														?>
												<td><a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="ImagenesContenido" rel="colorbox" title="<?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?>"><?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td> 
												<td style="text-align: center;"><?php echo($formato); ?> </td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
														<?php
													} else {
													//	muestro el nombre del archivo
													?>
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
													<?php
													}
													?>
											</tr>
													<?php
													$registros2->MoveNext();
													$i++;
												}
												?>
											</tbody>
											</table>
												<?php				
											}
											?>
											<input type="hidden" id="indicadorImagen" value="<?php echo($i); ?>" />
											<div id="adjuntos_contenido<?php echo($i); ?>" class="cloneImagen">
												<label class="control-label"><?php echo("[IMAGEN".sprintf("%02s",$i)."]"); ?></label>
												<div class="controls">
													<input type="checkbox" id="redimensionar" name="redimensionar[]" title="Redimensionar imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="text" id="comentario_imagen" name="comentario_imagen[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="imagen_adjunta" name="imagen_adjunta[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
									<hr />
									<div class="control-group">
										<label class="control-label">Archivos adjuntos <a href="javascript:void();" rel="popover" data-content="Los archivos puede ser de cualquier tipo: doc, xls, ppt, etc., y no deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra archivo" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />

											<?php
											//	verifico si posee archivos adjuntos
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Archivo') ORDER BY id_archivo");
											if($registros2->RecordCount() > 0) {
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
												while(!$registros2->EOF) {
													$referencia = "[ARCHIVO".sprintf("%02s",$i)."]";
													?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
													if(file_exists($nombre_completo_documento)) {
													//	obtengo la extension del archivo
													$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
													$formato = $res[count($res) - 1];
													//	obtengo el tama&ntilde;o del archivo
													$tamanio_archivo = ceil(filesize($nombre_completo_documento) / 1024);
													//	muestro un enlace para ver o descargar la imagen
														?>
												<td><a href="#" id="btnVerArchivo" data-archivo="<?php echo($nombre_completo_documento); ?>" title="Ver Archivo"><?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td>
												<td style="text-align: center;"><?php echo($formato); ?></td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: right;"><?php echo($registros2->fields["DESCARGAS"]); ?></td> 
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
														<?php
													} else {
													//muestro el nombre del archivo					   
													?> 
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar archivo" align="absmiddle" /></a></td>
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
											<input type="hidden" id="indicadorArchivo" value="<?php echo($i); ?>" />
											<div id="adjuntos_archivo<?php echo($i); ?>" class="cloneArchivo">
												<label class="control-label"><?php echo("[ARCHIVO".sprintf("%02s",$i)."]"); ?></label>
												<div class="controls">
													<input type="text" id="comentario_archivo" name="comentario_archivo[]" title="Descripci&oacute;n del Archivo" placeholder="Descripci&oacute;n del Archivo adjunto" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="archivo_adjunto" name="archivo_adjunto[]"title="Archivo Adjunto" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>

								</div>

								<div id="tab03" class="tab-pane">
									<div class="control-group">
										<label class="control-label">Galeria de Im&aacute;genes <a href="javascript:void();" rel="popover" title="Galeria de Im&aacute;genes" data-content="Las im&aacute;genes pueden ser de formato JPG, PNG o GIF. No deben superar los 8 Mbyte de tama&ntilde;o."><img src="../images/ayuda.gif" /></a></label>
										<div class="controls">
											<input type="button" id="btnAgregarCampos" class="btn" value="Agregar otra imagen" <?php if ($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?> />

											<?php
											//	verifico si posee imagenes en GALERIA
											$i = 1;
											$registros2 = $temp->Query("SELECT * FROM archivos WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."') and (tipo_archivo = 'Imagen') and (destino = 'galeria') ORDER BY id_archivo");
											if($registros2->RecordCount() > 0) {
												?>
											<table class="table table-striped table-bordered table-condensed" style="margin-top: 10px;">
											<thead>
											<tr>
												<th width="50">Indicador</th>
												<th>Descripci&oacute;n</th>
												<th width="20">Formato</th>
												<th width="50">Tama&ntilde;o</th>
												<th width="20">Editar</th>
												<th width="20">Eliminar</th>
											</tr>
											</thead>
											<tbody>
												<?php
												while(!$registros2->EOF) {
													$referencia = "[IMAGEN".sprintf("%02s",$i)."]";
													?>
											<tr>
												<td><strong><?php echo($referencia); ?></strong></td>				
													<?php
													//	verifico si el archivo existe
													$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros2->fields["NOMBRE_ARCHIVO"];
													if(file_exists($nombre_completo_imagenoriginal)) {
														//	obtengo la extension del archivo
														$res = explode(".", $registros2->fields["NOMBRE_ARCHIVO"]);
														$nombre = $res[0];
														$extension = $res[count($res) - 1];
														$formato = $res[count($res) - 1];
														//	obtengo el tama&ntilde;o del archivo
														$tamanio_archivo = ceil(filesize($nombre_completo_imagenoriginal) / 1024);
														//	obtengo la ubicacion de la imagen redimensionada original
														$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
														if(file_exists($nombre_completo_imagensmall)) {
															$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
														}
														//	muestro un enlace para ver o descargar la imagen
														?>
												<td><a href="<?php echo($nombre_completo_imagenoriginal); ?>" class="ImagenesGaleria" rel="colorbox" title="<?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?>"><?php if(($registros2->fields["COMENTARIO"] == "") or ($registros2->fields["COMENTARIO"] == null)) { echo($registros2->fields["NOMBRE_ARCHIVO"]); } else { echo(stripslashes($registros2->fields["COMENTARIO"])); } ?></a></td> 
												<td style="text-align: center;"><?php echo($formato); ?> </td>
												<td style="text-align: right;"><?php echo($tamanio_archivo); ?> Kb</td>
												<td style="text-align: center;"><a href="#" id="btnEditarComentarioArchivo" data-toggle="modal" data-target="#modalEditarArchivo" data-backdrop="static" data-keyboard="true" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/comentario.gif" title="Editar comentario" align="absmiddle" /></a></td>
												<td style="text-align: center;"><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
														<?php
													} else {
													//	muestro el nombre del archivo
													?>
												<td><?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?></td>
												<td></td>
												<td></td>
												<td></td>
												<td><a href="#" id="btnEliminarArchivo" data-contenido="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" data-archivo="<?php echo($registros2->fields["ID_ARCHIVO"]); ?>" data-tipo="<?php echo($registros2->fields["TIPO_ARCHIVO"]); ?>" data-nombre-archivo="<?php echo($registros2->fields["NOMBRE_ARCHIVO"]); ?>" data-texto="<?php echo(stripslashes($registros2->fields["COMENTARIO"])); ?>"><img src="../images/eliminar.gif" title="Eliminar imagen" align="absmiddle" /></a></td>
													<?php
													}
													?>
											</tr>
													<?php
													$registros2->MoveNext();
													$i++;
												}
												?>
											</tbody>
											</table>
												<?php				
											}
											?>
											<input type="hidden" id="indicadorImagenGaleria" value="<?php echo($i); ?>" />
											<div id="adjuntos_galeria<?php echo($i); ?>" class="cloneImagen">
												<label class="control-label"><?php echo("[IMAGEN".sprintf("%02s",$i)."]"); ?></label>
												<div class="controls">
													<input type="checkbox" id="redimensionarGaleria" name="redimensionarGaleria[]" title="Redimensionar imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="text" id="comentario_imagenGaleria" name="comentario_imagenGaleria[]" title="Descripci&oacute;n de la Imagen" placeholder="Descripci&oacute;n de la Imagen" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
													<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
													<input type="file" id="imagen_adjuntaGaleria" name="imagen_adjuntaGaleria[]" title="Imagen" accept=".jpeg,.jpg,.png,.gif" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>/>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="tab04" class="tab-pane">
									<div class="control-group">
										<textarea class="ckeditor" id="comentarios" name="comentarios" rows="20" cols="60" title="Comentarios" style="width:100%" <?php if($_GET['accion'] == "ver") { echo("disabled='disabled'"); } ?>><?php echo(stripslashes($registros->fields["COMENTARIOS"])); ?></textarea>
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
					<?php
					$registros->MoveNext();
				}
				?>

				<div id="modalEditarArchivo" class="modal hide fade">
					<div class="modal-header">
						<a href="#" class="close" data-dismiss="modal">&times;</a>
						<h3>Modal Heading</h3>
					</div>
					<div class="modal-body">
						<form method="post" action="" class="form-horizontal">
							<input type="hidden" id="idc" name="idc" />
							<input type="hidden" id="ida" name="ida" />
							<input type="hidden" id="tipo" name="tipo" />
							<div class="control-group">
								<label class="control-label" for="archivo_adjunto_nombre">Nombre del Archivo</label>
								<div class="controls">
									<input type="text" id="archivo_adjunto_nombre" name="archivo_adjunto_nombre" value="" title="Archivo Adjunto" readonly="readonly" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="archivo_adjunto_comentario">Descripci&oacute;n</label>
								<div class="controls">
									<input type="text" id="archivo_adjunto_comentario" name="archivo_adjunto_comentario" value="" size="50" maxlength="255" title="Descripci&oacute;n del Archivo" style="width: 95%;" required />
								</div>
							</div>
							<div class="control-group" id="opciones_imagen">
								<label class="control-label" for="archivo_adjunto_redimensionar">Redimensionar imagen</label>
								<div class="controls">
									<input type="checkbox" id="archivo_adjunto_redimensionar" name="archivo_adjunto_redimensionar" title="Redimensionar imagen" />
									<select name="archivo_adjunto_tamanio" class="input-small">
										<option value=""></option>
										<option value="320x240">320x240</option>
										<option value="640x480">640x480</option>
										<option value="800x600">800x600</option>
										<option value="124x768">124x768</option>
										<option value="">-</option>
										<option value="100x100">100x100</option>
										<option value="200x200">200x200</option>
										<option value="300x300">300x300</option>
										<option value="400x400">400x400</option>
										<option value="500x500">500x500</option>
										<option value="600x600">600x600</option>
										<option value="700x700">700x700</option>
										<option value="800x800">800x800</option>
										<option value="900x900">900x900</option>
										<option value="1000x1000">1000x1000</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-primary" id="btnModalGuardar" value="Guardar cambios" />
						<input type="button" class="btn" id="btnModalCancelar" value="Cancelar" />
					</div>
				</div>
				<?php
			}

			//	Actualizar comentario de imagen/archivo
			if(isset($_GET['accion']) && ($_GET['accion'] == "updateimagen")) {
				if($_POST['archivo_adjunto_comentario'] == "") {
					$conexion->Query("UPDATE archivos SET comentario = null WHERE (id_archivo = '".$_POST['ida']."')");
				} else {
					$conexion->Query("UPDATE archivos SET comentario = '".addslashes(utf8_decode($_POST['archivo_adjunto_comentario']))."' WHERE (id_archivo = '".$_POST['ida']."')");
				}

				if($_POST['tipo'] == 'Imagen') {
					//	obtengo los datos de la imagen, y redimensiono la imagen cargada
					$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_archivo = '".$_POST['ida']."')");
					if($registros) {				
						while(!$registros->EOF) {
							//	obtengo la ubicacion de la imagen original
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
							//	obtengo el nombre y extension del archivo small
							$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
							$nombre = $res[0];
							$extension = $res[count($res)-1];
							//	obtengo la ubicacion de la imagen small
							$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
							//	creo la redimension
							if(isset($_POST['archivo_adjunto_redimensionar']) and ($_POST['archivo_adjunto_redimensionar'] == 'on')) {
								//	paso como parametro el nombre la nueva imagen peque&ntilde;a
								if($_POST['archivo_adjunto_tamanio'] == '') {
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, 200, 200);
								} else {
									$tamanio = explode("x",$_POST['archivo_adjunto_tamanio']);
									redimensionar_imagen($nombre_completo_imagenoriginal, $nombre_completo_imagensmall, $tamanio[0], $tamanio[1]);
								}
							} else {
								//	elimino la redimension existente
							if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							}
							}
							$registros->MoveNext();
						}
					}
				}
				echo("{ \"estado\" : \"ok\" }");
			}

			// Eliminar imagen/archivo/publicidad
			if(isset($_GET['accion']) && ($_GET['accion'] == "deleteimagen")) {
				if(($_POST['tipo'] == "Imagen") or ($_POST['tipo'] == "Archivo")) {
					//	obtengo los datos
					$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '".$_POST['idc']."') and (id_archivo = '".$_POST['ida']."') ORDER BY id_archivo");
					if($registros) {
						while(!$registros->EOF) {
							if($_POST['tipo'] == 'Imagen') {
								//	verifico si el archivo existe
								$nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
								if(file_exists($nombre_completo_imagenoriginal)) {
									//	obtengo el nombre y extension del archivo
									$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
									$nombre = $res[0];
									$extension = $res[count($res) - 1];
									//	obtengo la ubicacion de la imagen original
									$nombre_completo_imagensmall = "../../".$path_imagenes."contenidos/".$nombre."_small.".$extension;
									//	elimino el archivo
									unlink($nombre_completo_imagenoriginal);
									if(file_exists($nombre_completo_imagensmall)) {
										unlink($nombre_completo_imagensmall);
									}
								}
							}
							if($_POST['tipo'] == 'Archivo') {
								//	verifico si el archivo existe
								$nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
								if(file_exists($nombre_completo_documento)) {
									//	elimino el archivo
									unlink($nombre_completo_documento);
								}
							}
							//elimino el registro
							$temp->Query("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

							$registros->MoveNext();
						}
						echo("{ \"estado\" : \"ok\" }");
					}
				} else {
					//	verifico si el archivo existe
					$nombre_completo_archivo = "../../".$path_imagenes."productos/".$_POST['ida'];
					if(file_exists($nombre_completo_archivo)) {
						unlink($nombre_completo_archivo);
					}
					$conexion->Query("UPDATE productos SET ".$_POST['tipo']." = null WHERE (id_producto = '".$_POST['idc']."')");
					echo("{ \"estado\" : \"ok\" }");
				}
			}

			//	Ventas registradas
			if(isset($_GET['accion']) && ($_GET['accion'] == "ventasregistradas")) {
				if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
				$datos = $conexion->GetRow("SELECT * FROM productos WHERE (id_producto = '".$_GET['registro'][0]."')");
				$url_back = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'].(!empty($_GET['anio']) ? "&anio=".$_GET['anio'] : '');
				?>
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=productos&accion=ver&registro[]=".$datos["ID_PRODUCTO"]); ?>">Datos del Producto</a></li>
						<li class="active"><a href="#tab01" data-toggle="tab">Ventas registradas</a></li>
						<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=vermovimientostock&registro[]=".$datos["ID_PRODUCTO"]); ?>">Movimientos de Stock</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<div class="btn-toolbar pull-right">
								<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url_back); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
							</div>
							<h1>Ventas registradas</h1>
							<h2>Producto: <?php echo("[".$datos["CODIGO"]."] &quot;".$datos["NOMBRE"]."&quot; "); ?></h2>
							<?php
								$query = "SELECT COD_VENTA,ventas.FECHA_ALTA,NRO_FACTURA,NOMBRE_COMPLETO,ventas.TOTAL,RECIBIDO,SALDO,PAGADO,CANTIDAD,ventas.ID_VENTA 
									FROM ventas 
									LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente 
									LEFT JOIN venta_posee_productos ON venta_posee_productos.id_venta = ventas.id_venta 
								";
								if(isset($_GET['texto'])) {
									$query .= "WHERE (tipo_venta = 'ventas') and (id_producto = '".$datos["ID_PRODUCTO"]."') and ((id_venta LIKE '%".$_GET['texto']."%') or (nombre_completo LIKE '%".$_GET['texto']."%')) ";
								} else {
									$query .= "WHERE (tipo_venta = 'ventas') and (id_producto = '".$datos["ID_PRODUCTO"]."') ";
								}
								$query .= "ORDER BY ventas.fecha_alta DESC,cod_venta DESC";

							showQuery(
								$conexion,
								$query,
								array(
									"COD_VENTA" => "N&deg;",
									"FECHA_ALTA" => "Fecha",
									"NRO_FACTURA" => "N&deg; Factura",
									"NOMBRE_COMPLETO" => "Cliente",
									"TOTAL" => "Total",
									"RECIBIDO" => "Recibido",
									"SALDO" => "Saldo",
									"PAGADO" => "Pagado",
									"CANTIDAD" => "Cantidad"
								),
								$_SESSION['usuario_rxp']
							);
							?>
						</div>
					</div>
				</div>
				<?php
			}

			//	Motimientos de Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "vermovimientostock")) {
				if(!isset($_GET['anio'])) { $_GET['anio'] = date("Y"); }
				$datos = $conexion->GetRow("SELECT * FROM productos WHERE (id_producto = '".$_GET['registro'][0]."')");
				$url_back = $_SERVER['PHP_SELF']."?menu=".$_GET['menu'].(!empty($_GET['anio']) ? "&anio=".$_GET['anio'] : '');
				?>
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ver&registro[]=".$datos["ID_PRODUCTO"]); ?>">Datos del Producto</a></li>
						<li><a href="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=ventasregistradas&registro[]=".$datos["ID_PRODUCTO"]); ?>">Ventas registradas</a></li>
						<li class="active"><a href="#tab01" data-toggle="tab">Motivmientos de Stock</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<div class="btn-toolbar pull-right">
								<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($url_back); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
							</div>
							<h1>Movimientos de Stock</h1>
							<h2>Producto: <?php echo("[".$datos["CODIGO"]."] &quot;".$datos["NOMBRE"]."&quot; "); ?></h2>
							<?php
								$query = "SELECT
										r.FECHA,
										r.DETALLE,
										r.VENDEDOR,
										r.TIPO,
										r.STOCK_ACTUAL,
										r.CANTIDAD,
										r.STOCK_FINAL
									FROM (
										(
											SELECT 
												fecha_registro FECHA,
												'Movimiento registrado' DETALLE, 
												IFNULL(
													(
														select nombre_completo from usuarios where (id_usuario = movimientos_stock.id_usuario)
													),''
												) VENDEDOR,
												tipo_movimientostock TIPO, 
												STOCK_ACTUAL, 
												CANTIDAD, 
												STOCK_FINAL
											FROM movimientos_stock
											WHERE 
												(movimientos_stock.id_sucursal = '{$_SESSION['usuario_sucursal']}') 
												and (movimientos_stock.id_producto = '{$datos["ID_PRODUCTO"]}')
										)
										UNION ALL
										(
											SELECT
												now() FECHA,
												'Datos del Producto' DETALLE,
												IFNULL(
													(
														select nombre_completo from usuarios where (id_usuario = '{$_SESSION['usuario_id']}')
													),''
												) VENDEDOR,
												'Actual' TIPO,
												stock STOCK_ACTUAL,
												0 CANTIDAD,
												stock STOCK_FINAL
											FROM productos
											WHERE
												(id_producto = '{$datos["ID_PRODUCTO"]}')
										)
										UNION ALL
										(
											SELECT
												fecha_alta FECHA,
												concat(
													'Venta registrada #',
													cod_venta,
													IF(
														eliminado = 1,
														' [ELIMINADO]',
														''
													)
												) DETALLE,
												IFNULL(
													(
														select nombre_completo from usuarios where (id_usuario = ventas.id_usuario)
													),''
												) VENDEDOR,
												'Venta' TIPO,
												0 STOCK_ACTUAL,
												CANTIDAD,
												0 STOCK_FINAL
											FROM ventas 
											LEFT JOIN venta_posee_productos ON venta_posee_productos.id_venta = ventas.id_venta
											WHERE
												(tipo_venta = 'ventas')
												and (id_sucursal = '{$_SESSION['usuario_sucursal']}') 
												and (id_producto = '{$datos["ID_PRODUCTO"]}')
										)
									) r
									ORDER BY r.FECHA desc
								";

								showQuery(
									$conexion,
									$query,
									array(
										"FECHA" => "Fecha",
										"DETALLE" => "Detalle",
										"VENDEDOR" => "Vendedor",
										"TIPO" => "Tipo",
										"STOCK_ACTUAL" => "Stock",
										"CANTIDAD" => "Cantidad",
										"STOCK_FINAL" => "Stock Finak"
									),
									$_SESSION['usuario_rxp']
								);
							?>
						</div>
					</div>
				</div>
				<?php
			}

			//	Aumentar Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "aumentarStock")) {
				if(isset($_GET['registro'])) {
					$datosProducto = $conexion->GetRow("SELECT * FROM productos WHERE (id_producto = '".$_GET['registro'][0]."') and (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
				}
				$sucursales = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY nombre");
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatestock"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

					<div class="btn-toolbar pull-right" style="margin-top: -3px;">
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
					</div>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<input type="text" value="<?php echo(stripslashes($sucursales["NOMBRE"])); ?>" readonly="readonly" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Producto</label>
									<div class="controls">
										<input type="hidden" name="id_producto" value="<?php if(isset($datosProducto)) { echo($datosProducto["ID_PRODUCTO"]); } ?>" />
										<input type="text" name="codigo" value="<?php if(isset($datosProducto)) { echo($datosProducto["CODIGO"]); } ?>" placeholder="000000-AAA" <?php if(!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled"<?php } ?> autocomplete="off" readonly="readonly" />
										<input type="text" name="producto" value="<?php if(isset($datosProducto)) { echo($datosProducto["NOMBRE"]); } ?>" size="23" maxlength="255" title="Nombre del Producto" placeholder="Nombre del Producto" required style="width: 75%;" <?php if(!isset($_SESSION['usuario_sucursal'])) { ?>disabled="disabled"<?php } ?> autocomplete="off" readonly="readonly" />
										<button type="button" id="btnBuscarProducto" class="btn btn-primary" data-toggle="modal" data-target="#modalBuscarProducto" data-backdrop="static" data-keyboard="true" data-proforma="" data-cliente="" data-evento="click" data-metodo="showModal_BuscarProducto"><i class="icon-search icon-white"></i> buscar</button>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
											<label class="control-label" for="campo_categoria">Categor&iacute;a</label>
											<div class="controls">
												<input type="text" name="campo_categoria" size="50" maxlength="255" title="Categor&iacute;a" placeholder="Categor&iacute;a" readonly="readonly" />
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="control-group">
											<label class="control-label" for="campo_genero">Género</label>
											<div class="controls">
												<input type="text" name="campo_genero" size="50" maxlength="255" title="Género" placeholder="Género" readonly="readonly" />
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="stock">Stock Actual</label>
											<div class="controls">
												<input type="text" id="stock" name="stock" value="<?php if(isset($datosProducto)) { echo($datosProducto["STOCK"]); } ?>" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Stock actual" readonly="readonly" />
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Cantidad a aumentar</label>
											<div class="controls">
												<div class="input-prepend input-append">
													<button type="button" id="btAumentarCampo" data-tipo="-" data-campo="stock_aumentar" data-limite="<?php if(isset($datosProducto)) { if($datosProducto["STOCK"] > 0) { echo("-"); } echo($datosUsuario["STOCK"]); } else { echo("-100"); } ?>" data-origen="stock" data-destino="stock_final" class="btn" <?php if(!isset($datosProducto)) { ?>disabled="disabled"<?php } ?> ><i class="icon-minus"></i></button>
													<input type="number" id="stock_aumentar" name="stock_aumentar" value="0" min="0" size="50" maxlength="20" class="input-mini" style="text-align: center;width: 50px;" title="Cantidad a aumentar" />
													<button type="button" id="btAumentarCampo" data-tipo="+" data-campo="stock_aumentar" data-limite="100" data-origen="stock" data-destino="stock_final" class="btn" <?php if(!isset($datosProducto)) { ?>disabled="disabled"<?php } ?> ><i class="icon-plus"></i></button>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label" for="genero">Stock Final</label>
											<div class="controls">
												<input type="text" id="stock_final" name="stock_final" size="50" maxlength="3" class="input-mini" style="text-align: right;" title="Stock final" readonly="readonly" required="required" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Aumentar Stock" onclick="return confirm('Se va ha cambiar el stock del Producto seleccionado.\nEsta Ud. seguro?');" disabled />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>

				<div id="modalBuscarProducto" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form style="margin: 0;">
						<input type="hidden" name="origen" value="" />

						<div class="modal-header">
							<a href="#" class="close" data-dismiss="modal">&times;</a>
							<h3 style="border-bottom: none;">Buscar producto</h3>
							<div class="row-fluid">
								<div class="span4" style="text-align: left;">
									<strong>Ingrese el texto a buscar: </strong><br />
									<input type="text" name="q" size="50" maxlength="255" title="Ingrese el texto a buscar:" placeholder="código, nombre o codigo de barra" style="width: 99%;" autocomplete="off" data-evento="change" data-metodo="getData_ModalBuscarProducto" data-target="#modalBuscarProducto" />
								</div>
								<div class="span3" style="text-align: left;">
									<strong>Categoría: </strong><br />
									<select name="listaCategoria" title="Categoría" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarProductoGeneros,getData_ModalBuscarProducto" data-target="#modalBuscarProducto">
										<?php
										$query = "SELECT CATEGORIA 
											FROM productos 
											WHERE 
												(id_sucursal = '{$_SESSION['usuario_sucursal']}') 
												and (activo = 1) 
												and ((categoria is not null) and (categoria <> '')) 
											GROUP BY categoria 
											ORDER BY categoria
										";
										$categorias = $temp->Query($query);
										?>
										<option value="">Categoría (<?php echo($categorias->RecordCount()); ?>)</option>
										<?php
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
									<select name="listaGenero" title="G&eacute;nero" style="width: 99%;" data-evento="change" data-metodo="getData_ModalBuscarProducto" data-target="#modalBuscarProducto">
										<option value="">Género (<?php echo(count($listaGeneros)); ?>)</option>
										<?php
											foreach ($listaGeneros as $key => $val) {
												?>
												<option value="<?php echo ($val); ?>"><?php echo ($val); ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="span2" style="text-align: center;">
									<br />
									<button type="button" id="btnModalBuscarProducto" class="btn btn-primary btn-block" data-target="#modalBuscarProducto" title="Buscar" data-evento="click" data-metodo="getData_ModalBuscarProducto">buscar</button>
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
								<div class="span9" style="text-align: left;">
								</div>
								<div class="span3" style="text-align: right;">
									<button type="button" class="btn btn-primary" data-target="#modalBuscarProducto" id="btnModalGuardarBuscarProducto">Aceptar</button>
									<button type="button" class="btn" id="btnModalCancelar" data-dismiss="modal">Cancelar</button>
								</div>
							</div>
						</div>

					</form>
				</div>

				<?php
			}

			//	Actualizar Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "updatestock")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");

				if(($_POST['stock_final'] != "") && ($_POST['stock_final'] > $_POST['stock'])){
					echo($_POST['sucursal'].'|'.$_POST['id_producto'].'|'.$_POST['stock'].'|'.$_POST['stock_aumentar'].'|'.$_POST['stock_final']);

					$StockSucursales = $conexion->Query("SELECT * FROM productos WHERE (id_producto = '".$_POST['id_producto']."') and (id_sucursal = '".$_POST['sucursal']."')");
					if($StockSucursales && ($StockSucursales->RecordCount() > 0) && !empty($_SESSION['usuario_id'])) {
						// registro el aumento de stock
						$query = "UPDATE productos 
							SET 
								stock = '{$_POST['stock_final']}' 
							WHERE 
								(id_producto = '{$_POST['id_producto']}') 
								and (id_sucursal = '{$_POST['sucursal']}')
						";
						$conexion->Query($query);
						// registro el movimiento de stock
						$query = "INSERT INTO movimientos_stock (
								ID_MOVIMIENTOSTOCK,
								ID_USUARIO,
								ID_PRODUCTO,
								ID_SUCURSAL,
								FECHA_REGISTRO,
								STOCK_ACTUAL,
								CANTIDAD,
								STOCK_FINAL,
								TIPO_MOVIMIENTOSTOCK
							) VALUES(
								null,
								'" . $_SESSION['usuario_id'] . "',
								'" . $_POST['id_producto'] . "',
								'" . $_POST['sucursal'] . "',
								'" . date('Y-m-d H:i:s') . "',
								'" . $_POST['stock'] . "',
								'" . $_POST['stock_aumentar'] . "',
								'" . $_POST['stock_final'] . "',
								'Aumento'
							)
						";
						$conexion->Query($query);
					}

					echo("<script type='text/javascript'>");
					echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
					echo("</script>");

				} else {
					echo("");
					?>
					<form class="form-alert">
						<h3>Atenci&oacute;n</h3>
						<div class="body-alert">
							<p>No se ha registrado ningún movimiento porque el <strong>STOCK FINAL</strong> [<?php echo($_POST['stock_final']); ?>] esta vacío o es igual al <strong>STOCK</strong> actual [<?php echo($_POST['stock']); ?>] del producto seleccionado: "<?php echo($_POST['producto']); ?>".</p>
						</div>
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
					</form>
					<?php
				}				
			}

			//	Exportar Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "exportarStock")) {
				$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
				if(!isset($_GET['tc'])) { $_GET['tc'] = "codigo"; }
				if(!isset($_GET['to'])) { $_GET['to'] = "asc"; }
				$datosSucursal = $temp->Query("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."')");
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<div class="btn-toolbar pull-right" style="margin-top: -3px;">
					<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
				</div>

				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab01" data-toggle="tab">Opciones para exportar los Stock de PRODUCTOS</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
							<div class="well">
								<input type="hidden" id="s" name="s" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />					
								<input type="hidden" id="ns" name="ns" value="<?php if($datosSucursal){ echo($datosSucursal->fields["NOMBRE"]); } ?>" />
								<?php 
									$tipo_set = isset($_GET['tp']) && !empty($_GET['tp']) 
										? ($_GET['tp'] == '*' ? " AND (IFNULL(tipo_producto,'') = '')" : " AND (IFNULL(tipo_producto,'') = '{$_GET['tp']}')")
										: '';
									$categoria_set = isset($_GET['c']) && !empty($_GET['c']) ? " AND (categoria = '{$_GET['c']}')" : '';
									$genero_set = isset($_GET['g']) && !empty($_GET['g']) ? " AND (genero = '{$_GET['g']}')" : '';

									$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
									?>
								<select name="tp" id="tp" onchange="AbrirURL(this);" title="Filtrar por Tipo" style="width: auto;">
									<option value="<?php echo($url); ?>" <?php if(!isset($_GET['tp'])) { echo("selected='selected'"); } ?>>Todos los Tipos</option>
									<?php
										$tiposProductos = $temp->Query("SELECT 
												IF(
													IFNULL(tipo_producto,'') = '',
													'*',
													IFNULL(tipo_producto,'')
												) TIPO_PRODUCTO 
											FROM productos 
											WHERE 
												(activo = 1) 
												and (id_sucursal = '{$_SESSION['usuario_sucursal']}') 
											GROUP BY IFNULL(tipo_producto,'') 
											ORDER BY IFNULL(tipo_producto,'')
										");

										if($tiposProductos && ($tiposProductos->RecordCount() > 0)){
											while(!$tiposProductos->EOF) {
												?>
											<option value="<?php echo($url."&tp=".$tiposProductos->fields["TIPO_PRODUCTO"]); ?>" 
												<?php if(isset($_GET['tp']) && ($_GET['tp'] == $tiposProductos->fields["TIPO_PRODUCTO"])) { echo("selected=\"selected\""); } ?>
											>
												<?php echo(stripslashes($tiposProductos->fields["TIPO_PRODUCTO"])); ?>
											</option>
												<?php
												$tiposProductos->MoveNext();
											}
										}
									?>
								</select>
								<?php 
									$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
									if(isset($_GET['tp'])) { $url .= "&tp=".$_GET['tp']; }
									?>
								<select name="c" id="c" onchange="AbrirURL(this);" title="Filtrar por Categoria" style="width: auto;">
									<option value="<?php echo($url); ?>" <?php if(!isset($_GET['c'])) { echo("selected='selected'"); } ?>>Todas las Categorias</option>
									<?php
										$categoriasProductos = $temp->Query("SELECT CATEGORIA 
											FROM productos 
											WHERE 
												(activo = 1) 
												and (categoria is not null) and (categoria <> '') 
												and (id_sucursal = '{$_SESSION['usuario_sucursal']}') 
												{$tipo_set}
											GROUP BY categoria 
											ORDER BY categoria
										");

										if($categoriasProductos && ($categoriasProductos->RecordCount() > 0)){
											while(!$categoriasProductos->EOF) {
												?>
											<option value="<?php echo($url."&c=".$categoriasProductos->fields["CATEGORIA"]); ?>" 
												<?php if(isset($_GET['c']) && ($_GET['c'] == $categoriasProductos->fields["CATEGORIA"])) { echo("selected=\"selected\""); } ?>
											>
												<?php echo(stripslashes($categoriasProductos->fields["CATEGORIA"])); ?>
											</option>
												<?php
												$categoriasProductos->MoveNext();
											}
										}
									?>
								</select>
								-
								<?php 
									$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
									if(isset($_GET['tp'])) { $url .= "&tp=".$_GET['tp']; }
									if(isset($_GET['c'])) { $url .= "&c=".$_GET['c']; }
									?>
								<select name="g" id="g" onchange="AbrirURL(this);" title="Filtrar por Genero" style="width: auto;">
									<option value="<?php echo($url); ?>" <?php if(!isset($_GET['g'])) { echo("selected='selected'"); } ?>>Todos los G&eacute;neros</option>
									<?php
										$query = "SELECT GENERO 
											FROM productos 
											WHERE 
												(activo = 1) 
												and (genero is not null) and (genero <> '') 
												and (id_sucursal = '{$_SESSION['usuario_sucursal']}') 
												{$tipo_set}
												{$categoria_set}
											GROUP BY genero 
											ORDER BY genero
										";
										$generosProductos = $temp->Query($query);

										if($generosProductos && ($generosProductos->RecordCount() > 0)){
											while(!$generosProductos->EOF) {
												?>
											<option value="<?php echo($url."&g=".$generosProductos->fields["GENERO"]); ?>" 
												<?php if(isset($_GET['g']) && ($_GET['g'] == $generosProductos->fields["GENERO"])) { echo("selected=\"selected\""); } ?>
											>
												<?php echo(stripslashes($generosProductos->fields["GENERO"])); ?>
											</option>
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
										<li><a href="javascript:void(0);" id="btExportarDatos2" data-tipo="xls">Microsoft Excel (*.xls)</a></li>
										<li><a href="javascript:void(0);" id="btExportarDatos2" data-tipo="xlsx">Microsoft Excel (*.xlsx)</a></li>
									</ul>
								</div>
								<button type="button" class="btn" id="printButton" data-contenido="#tab02" title="Imprimir" style="margin-top: -10px;"><i class="icon-print"></i></button>
							</div>

							<table id="listado" class="table table-striped table-bordered table-condensed">
							<thead>
								<tr>
									<?php 
										$url = $_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=".$_GET['accion'];
										$url .= "&s=".$_SESSION['usuario_sucursal'];
										if(isset($_GET['c'])) { $url .= "&c=".$_GET['c']; }
										if(isset($_GET['g'])) { $url .= "&g=".$_GET['g']; }

										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "codigo")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th width="80" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=codigo&to=".$to); ?>">C&oacute;digo</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "nombre")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=nombre&to=".$to); ?>">Nombre</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "tipo_producto")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=tipo_producto&to=".$to); ?>">Tipo</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "categoria")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=categoria&to=".$to); ?>">Categor&iacute;a</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "genero")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=genero&to=".$to); ?>">G&eacute;nero</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "precio_costo")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th width="110" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=precio_costo&to=".$to); ?>">Precio de Costo</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "precio_venta")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th width="110" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=precio_venta&to=".$to); ?>">Precio de Venta</a></th>
									<?php
										$estilo = "";
										$to = "asc";
										if(isset($_GET['tc']) and ($_GET['tc'] == "stock")) {
											if($_GET['to'] == "asc") { $to = "desc";
											} else {
												if($_GET['to'] == "desc") { $to = "asc"; }
											}
											$estilo = $to;
										}
									?>
									<th width="50" class="<?php echo($estilo); ?>"><a href="<?php echo($url."&tc=stock&to=".$to); ?>">Stock</a></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$query = "SELECT 
										productos.ID_PRODUCTO,
										CODIGO,
										NOMBRE,
										TIPO_PRODUCTO,
										CATEGORIA,
										GENERO,
										PRECIO_COSTO,
										PRECIO_VENTA,
										IF(STOCK >= 0,STOCK,0) as STOCK 
									FROM productos 
									WHERE 
										(soloapedido = 0) 
										and (id_sucursal = '{$_SESSION['usuario_sucursal']}') 
										{$tipo_set}
										{$categoria_set}
										{$genero_set}
									ORDER BY {$_GET['tc']} {$_GET['to']}
								";
								$registros = $conexion->Query($query);

								if($registros && ($registros->RecordCount() > 0)){
									while(!$registros->EOF) {
										?>
										<tr>
											<td><?php echo($registros->fields["CODIGO"]); ?></td>
											<td><?php echo(str_replace("\"","'",stripslashes($registros->fields["NOMBRE"]))); ?></td>
											<td><?php echo($registros->fields["TIPO_PRODUCTO"]); ?></td>
											<td><?php echo($registros->fields["CATEGORIA"]); ?></td>
											<td><?php echo($registros->fields["GENERO"]); ?></td>
											<td><?php echo(number_format($registros->fields["PRECIO_COSTO"], 2, ',', '.')); ?></td>
											<td><?php echo(number_format($registros->fields["PRECIO_VENTA"], 2, ',', '.')); ?></td>
											<td><?php echo($registros->fields["STOCK"]); ?></td>
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

				</div>
				<?php
			}

			//	Importar Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "importarStock")) {
				$sucursales = $temp->GetRow("SELECT * FROM sucursales WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') ORDER BY nombre");
					?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=insertupdatestock"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					
					<div class="btn-toolbar pull-right" style="margin-top: -3px;">
						<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
					</div>

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Opciones para importar los Stock de PRODUCTOS</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<input type="text" value="<?php echo(stripslashes($sucursales["NOMBRE"])); ?>" readonly="readonly" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Archivo MS Excel</label>
									<div class="controls">
										<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
										<input type="file" id="importar_archivo" name="importar_archivo" title="Archivo MS Excel" accept=".xls,.xlsx" required />
										<div class="alert alert-info" role="alert" style="margin-top: 10px;">
											<p>Asegúrese de que el archivo contenga las siguientes columnas en el mismo orden: 
													<strong>CODIGO</strong>, <strong>NOMBRE</strong>, <strong>TIPO_PRODUCTO</strong>, 
													<strong>CATEGORIA</strong>, <strong>GENERO</strong>, <strong>PRECIO_COSTO</strong>, 
													<strong>PRECIO_VENTA</strong> y <strong>STOCK</strong>.</p>
											<p>Además, si no es necesario modificar los campos de <strong>PRECIO_COSTO</strong> o <strong>PRECIO_VENTA</strong>, simplemente ingrese un valor de 0 (cero).</p>
										</div>
									</div>
								</div>
								
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Importa Stock" onclick="return confirm('Se van ha importar los datos de los PRODUCTOS y STOCK del archivo seleccionado.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
					<?php
			}

			//	Insertar archivos con Stock
			if(isset($_GET['accion']) && ($_GET['accion'] == "insertupdatestock")) {
				?>
				<div class="btn-toolbar pull-right" style="margin-top: -3px;">
					<button type="button" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';"><i class="icon-chevron-left icon-white"></i> Volver al listado</button>
				</div>

				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab01" data-toggle="tab">Datos importados en PRODUCTOS</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab01" class="tab-pane active">
						<?php
						//echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registros...</h5>");

						//	verifico el archivo subido
						if(isset($_FILES["importar_archivo"])) {
							//	verifico si se subio el archivo
							if(is_uploaded_file($_FILES['importar_archivo']['tmp_name'])) {
								//obtiene el nombre del archivo y su extension
								$res = explode(".", $_FILES['importar_archivo']['name']);
								$tipo_archivo = $_FILES['importar_archivo']['type'];
								$extension = $res[count($res) - 1];
								$nombre = "s".$_POST['sucursal']."-".date("dmY_His");
								$nombre_completo = $nombre.".".$extension;

								$archivo = "../../".$path_archivos."datos/".$nombre_completo;

								copy($_FILES['importar_archivo']['tmp_name'], stripslashes($archivo));

								//	intenta copiar el archivo cargado a la carpeta DATOS
								if(
									!copy($_FILES['importar_archivo']['tmp_name'], stripslashes($archivo)) or 
									($_FILES['importar_archivo']['size'] > 8388608)
								) {
									?>
									<p>No se ha podido cargar el archivo: <strong><?php echo($_FILES['importar_archivo']['name']); ?></strong> por alguna de las siguientes razones:</p>
									<ul>
										<li>El nombre del archivo posee caracteres especiales.</li>
										<li>El tama&ntilde;o del archivo es superior a 8 Mbyte.</li>
									</ul>
									<?php
								} else {									

									require_once __DIR__ . '/../vendor/autoload.php';

									// Cargar el archivo
									$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);

									// Obtener todas las hojas del archivo
									$hojas = $spreadsheet->getAllSheets();

									// Iterar a través de las hojas
									foreach ($hojas as $hoja) {

										echo("<p>La hoja de cálculo: \"".$hoja->getTitle()."\".</p>");
										echo('<table class="table table-striped table-bordered table-condensed">');

										// Obtener la última columna y fila utilizada en la hoja
										$ultimaColumna = $hoja->getHighestColumn();
										$ultimaFila = $hoja->getHighestRow();

										// Iterar a través de las celdas
										for ($fila = 1; $fila <= $ultimaFila; $fila++) {
											echo("<tr>");
											for ($columna = 'A'; $columna <= $ultimaColumna; $columna++) {

												// Obtener el valor de la celda actual
												$valorCelda = $hoja->getCell($columna . $fila)->getValue();

												if($fila == 1){ 
													echo("<th>".$valorCelda."</th>");
												} else {
													if($columna == 'B' && empty($valorCelda)){
														echo("<td class='text-danger'>REGISTRO NO IMPORTADO</td>");
													} else {
														echo("<td>".$valorCelda."</td>");
													}
												}

												switch($columna) {
													case 'A': $vCodigo = $valorCelda;break;
													case 'B': $vNombre = $valorCelda;break;
													case 'C': $vTipo = $valorCelda;break;
													case 'D': $vCategoria = $valorCelda;break;
													case 'E': $vGenero = $valorCelda;break;
													case 'F': 
														//$vPrecio_Costo = $valorCelda;
														$vPrecio_Costo = floatval(str_replace('.', '', $valorCelda));
														break;
													case 'G': 
														//$vPrecio_Venta = $valorCelda;
														$vPrecio_Venta = floatval(str_replace('.', '', $valorCelda));
														break;
													case 'H': $vStock = $valorCelda;break;
												}

											}
											echo("</tr>");

											if($fila > 1){

												//echo("<p>$vCodigo|$vNombre|$vCategoria|$vGenero|$vPrecio_Costo(".number_format($vPrecio_Costo, 2, '.', '').")|$vPrecio_Venta|$vStock|</p>");												

												// verifico si existe el PRODUCTO por su codigo
												$query = "SELECT ID_PRODUCTO,STOCK
													FROM productos 
													WHERE 
														(upper(codigo) = upper('{$vCodigo}')) 
														and (id_sucursal = '{$_SESSION['usuario_sucursal']}')
												";
												$registros = $conexion->Query($query);

												if($registros->RecordCount() > 0) {
													$actualizacion_set = !empty($_SESSION['usuario_id']) ? "id_actualizacion = '".$_SESSION['usuario_id']."', " : '';

													// existe el registro
													while(!$registros->EOF) {
														$precioCosto_set = $vPrecio_Costo > 0 ? "precio_costo = '".number_format($vPrecio_Costo, 2, '.', '')."', " : '';
														$precioVenta_set = $vPrecio_Venta > 0 ? "precio_venta = '".number_format($vPrecio_Venta, 2, '.', '')."', " : '';

														// actualizo el NOMBRE, CATEGORIA, GENERO y STOCK
														$query = "UPDATE productos 
															SET 
																{$actualizacion_set}
																{$precioCosto_set}
																{$precioVenta_set}
																nombre = '".strtoupper(addslashes($vNombre))."', 
																tipo_producto = '".strtoupper($vTipo)."', 
																categoria = '".strtoupper($vCategoria)."', 
																genero = '".strtoupper($vGenero)."', 
																stock = '".$vStock."' 
															WHERE 
																(id_producto = '".$registros->fields["ID_PRODUCTO"]."')
														";
														$conexion->Execute($query);

														$usuario_set = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null';

														// verifico si hizo cambios en el STOCK
														$vStockBackup = str_replace(".","",$registros->fields["STOCK"]);
														$vStockBackup = str_replace(",",".",$vStockBackup);						
														if($vStock != $vStockBackup){
															$tipo = '';
															$cantidad = 0;

															if($vStock > $vStockBackup){
																$tipo = 'Aumento al Importar';
																$cantidad = $vStock - $vStockBackup;
															} else {
																if($vStock < $vStockBackup){
																	$tipo = 'Disminución al Importar';
																	$cantidad = $vStockBackup - $vStock;
																}
															}

															if(!empty($tipo)){
																// registro el movimiento de stock
																$query = "INSERT INTO movimientos_stock (
																	ID_MOVIMIENTOSTOCK,
																	ID_USUARIO,
																	ID_PRODUCTO,
																	ID_SUCURSAL,
																	FECHA_REGISTRO,
																	STOCK_ACTUAL,
																	CANTIDAD,
																	STOCK_FINAL,
																	TIPO_MOVIMIENTOSTOCK
																) VALUES(
																	null,
																	{$usuario_set},
																	'" . $registros->fields["ID_PRODUCTO"] . "',
																	'" . $_SESSION['usuario_sucursal'] . "',
																	'" . date('Y-m-d H:i:s') . "',
																	'" . $vStockBackup . "',
																	'" . $cantidad . "',
																	'" . $vStock . "',
																	'".$tipo."'
																)";
																$conexion->Query($query);
															}

														}

														$registros->MoveNext();
													}
												} else {

													if(!empty($vNombre)){
														$actualizacion_set = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null';

														// insertar el nuevo registro
														$query = "INSERT INTO productos 
															(
																ID_PRODUCTO,
																CODIGO,
																TIPO_PRODUCTO,
																CATEGORIA,
																NOMBRE,
																GENERO,
																PRECIO_COSTO,
																PRECIO_VENTA,
																ACTIVO,
																CLICK,
																SOLOAPEDIDO,
																ID_SUCURSAL,
																STOCK,
																ID_ACTUALIZACION
															) VALUES(
																null,
																'".$vCodigo."',
																'".strtoupper($vTipo)."',
																'".strtoupper($vCategoria)."',
																'".strtoupper(addslashes($vNombre))."',
																'".strtoupper($vGenero)."',
																'".number_format($vPrecio_Costo, 2, '.', '')."',
																'".number_format($vPrecio_Venta, 2, '.', '')."',
																'1',
																'0',
																'0',
																'".$_POST['sucursal']."',
																'".$vStock."',
																{$actualizacion_set}
															)
														";
														$conexion->Execute($query);
														$id_registro = $conexion->Insert_ID();

														$usuario_set = !empty($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null';

														// registro el movimiento de stock
														$query = "INSERT INTO movimientos_stock (
																ID_MOVIMIENTOSTOCK,
																ID_USUARIO,
																ID_PRODUCTO,
																ID_SUCURSAL,
																FECHA_REGISTRO,
																STOCK_ACTUAL,
																CANTIDAD,
																STOCK_FINAL,
																TIPO_MOVIMIENTOSTOCK
															) VALUES(
																null,
																{$usuario_set},
																'" . $id_registro . "',
																'" . $_POST['sucursal'] . "',
																'" . date('Y-m-d H:i:s') . "',
																'0',
																'" . $vStock . "',
																'" . $vStock . "',
																'Inicial'
															)
														";
														$conexion->Query($query);
													}

												}

											}
												
											$vCodigo = "";
											$vNombre = "";
											$vTipo = "";
											$vCategoria = "";
											$vGenero = "";
											$vPrecio_Costo = "";
											$vPrecio_Venta = "";
											$vStock = "";

										}

										echo "</table>";

									}

								}
							}
						}
						?>
						</div>
					</div>
				</div>
				<?php
			}

			//	Renombrar Tipo de Producto
			if(isset($_GET['accion']) && ($_GET['accion'] == "renombrarTipo")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatetipo"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label" for="tipo_producto">Tipo de Producto</label>
									<div class="controls">
										<input type="text" id="tipo_producto" name="tipo_producto" size="50" maxlength="255" title="Tipo de Producto" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="nuevotipo_producto">Nueva Tipo de Producto</label>
									<div class="controls">
										<input type="text" id="nuevotipo_producto" name="nuevotipo_producto" size="50" maxlength="255" title="Nuevo Tipo de Producto" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar Tipo de Producto" onclick="return confirm('Se va ha cambiar el nombre del Tipo de Producto seleccionada.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Tipo de Producto
			if(isset($_GET['accion']) && ($_GET['accion'] == "updatetipo")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE productos SET tipo_producto = '".addslashes($_POST["nuevotipo_producto"])."' WHERE (tipo_producto = '".$_POST["tipo_producto"]."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Renombrar Categoria
			if(isset($_GET['accion']) && ($_GET['accion'] == "renombrarCategoria")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatecategoria"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />
					
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nueva Categoria</label>
									<div class="controls">
										<input type="text" id="nuevacategoria" name="nuevacategoria" size="50" maxlength="255" title="Nueva Categoria" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar categoria" onclick="return confirm('Se va ha cambiar el nombre de la categoria seleccionada.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Categoria
			if(isset($_GET['accion']) && ($_GET['accion'] == "updatecategoria")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE productos SET categoria = '".addslashes($_POST["nuevacategoria"])."' WHERE (categoria = '".$_POST["categoria"]."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Renombrar Genero
			if(isset($_GET['accion']) && ($_GET['accion'] == "renombrarGenero")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form method="post" action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updategenero"); ?>" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<div class="control-group">
									<label class="control-label">G&eacute;nero</label>
									<div class="controls">
										<input type="text" id="genero" name="genero" size="50" maxlength="255" title="G&eacute;nero" style="width: 99%;" required />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Nuevo G&eacute;nero</label>
									<div class="controls">
										<input type="text" id="nuevogenero" name="nuevogenero" size="50" maxlength="255" title="Nuevo G&eacute;nero" style="width: 99%;" required />
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Renombrar genero" onclick="return confirm('Se va ha cambiar el nombre del genero seleccionado.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			// Actualizar Genero
			if(isset($_GET['accion']) && ($_GET['accion'] == "updategenero")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE productos SET genero = '".addslashes($_POST["nuevogenero"])."' WHERE (genero = '".$_POST["genero"]."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Desactivar por Categoria
			if(isset($_GET['accion']) && ($_GET['accion'] == "desactivarCategoria")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updatedesactivarcategoria"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
					<input type="hidden" name="sucursal" id="sucursal" value="<?php echo($_SESSION['usuario_sucursal']); ?>" />

					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab01" data-toggle="tab">Datos Generales</a></li>
						</ul>

						<div class="tab-content">
							<div id="tab01" class="tab-pane active" style="min-height: 100px;">
								<h2>Activar/Desactivar Categoria</h2>
								<div class="control-group">
									<label class="control-label">Categoria</label>
									<div class="controls">
										<input type="text" id="categoria" name="categoria" size="50" maxlength="255" title="Categoria" style="width: 99%;" required />
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Activo</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="1" checked="checked" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="activo" name="activo" value="0" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="control-group">
											<label class="control-label">Visible en el Sitio Web</label>
											<div class="controls">
												<label class="radio inline">
													<input type="radio" id="visible" name="visible" value="1" checked="checked" /> <?php echo($estados[1]); ?>
												</label>
												<label class="radio inline">
													<input type="radio" id="visible" name="visible" value="0" /> <?php echo($estados[0]); ?>
												</label>
											</div>
										</div>
									</div>
									<div class="span4">
									</div>
								</div>
							</div>
						</div>

						<div class="form-actions">
							<input type="submit" class="btn btn-large btn-primary" value="Desactivar categoria" onclick="return confirm('Se va ha Activar/Desactivar todos los Productos de la categoria seleccionada.\nEsta Ud. seguro?');" />
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar Categoria
			if(isset($_GET['accion']) && ($_GET['accion'] == "updatedesactivarcategoria")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");
				$conexion->Execute("UPDATE productos SET activo = '".$_POST['activo']."', visible = '".$_POST['visible']."' WHERE (categoria = '".$_POST["categoria"]."')");
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Recuperar registros
			if(isset($_GET['accion']) && ($_GET['accion'] == "recuperar")) {
				?>
				<p id="vMsjCargando"><img src="../images/preloader.gif" /> <span>obteniendo datos ...</span></p>
				<form action="<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']."&accion=updateregistros"); ?>" method="post" enctype="multipart/form-data" id="signup" class="form-horizontal">
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
									<th>C&oacute;digo</th>
									<th>Nombre</th>
									<th>Tipo</th>
									<th>Categor&iacute;a</th>
									<th>G&eacute;nero</th>
									<th>Precio Venta</th>
									<th>Stock</th>
									<th>A Pedido</th>
									<th width="120">F. Eliminado</th>
								</tr>
								</thead>
								<tbody>
								<?php
									$query = "SELECT CODIGO,NOMBRE,TIPO_PRODUCTO,CATEGORIA,GENERO,PRECIO_VENTA,IF(STOCK >= 0,STOCK,0) as STOCK,SOLOAPEDIDO,ACTIVO,FECHA_ELIMINADO,productos.ID_PRODUCTO FROM productos 
										WHERE (id_sucursal = '".$_SESSION['usuario_sucursal']."') and (eliminado = 1) ";
									if(isset($_GET['texto'])) {
										$query .= " and ( 
											(codigo LIKE '%".$_GET['texto']."%') or 
											(nro_serie LIKE '%".$_GET['texto']."%') or 
											(nombre LIKE '%".$_GET['texto']."%') or 
											(tipo_producto LIKE '%".$_GET['texto']."%') or 
											(categoria LIKE '%".$_GET['texto']."%') or 
											(genero LIKE '%".$_GET['texto']."%') 
										) ";
									}
									$query .= "ORDER BY fecha_eliminado DESC,codigo DESC";

									$registros = $conexion->Query($query);
									while(!$registros->EOF) {
										?>
								<tr>
									<td><input type="checkbox" id="registro[]" name="registro[]" value="<?php echo($registros->fields["ID_PRODUCTO"]); ?>" /></td>
									<td>
										<div class="btn-toolbar">
											<div class="btn-group">
												<button id="btEliminarRegistroFisico" class="btn btn-mini btn-danger" title="Eliminar registro fiscamente"><i class="icon-remove"></i></button>			
											</div>
										</div>
									</td>
									<td><?php echo($registros->fields["CODIGO"]); ?></td>
									<td><?php echo($registros->fields["NOMBRE"]); ?></td>
									<td><?php echo($registros->fields["TIPO_PRODUCTO"]); ?></td>
									<td><?php echo($registros->fields["CATEGORIA"]); ?></td>
									<td><?php echo($registros->fields["GENERO"]); ?></td>
									<td style="text-align: right;"><?php echo(number_format($registros->fields["PRECIO_VENTA"], 2, ',', '.')); ?></td>
									<td style="text-align: right;"><?php echo($registros->fields["STOCK"]); ?></td>
									<td><?php echo($estados[$registros->fields["SOLOAPEDIDO"]]); ?></td>
									<td style="text-align: center;"><?php echo(formato_fecha($registros->fields["FECHA_ELIMINADO"],6)); ?></td>
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
							<input type="reset" class="btn btn-large" value="Cancelar" onclick="javascript:window.location.href='<?php echo($_SERVER['PHP_SELF']."?menu=".$_GET['menu']); ?>';" />
						</div>
					</div>
				</form>
				<?php
			}

			//	Actualizar registros
			if(isset($_GET['accion']) && ($_GET['accion'] == "updateregistros")) {
				echo("<h5><img src='../images/preloader.gif' border='0' alt=''> Actualizando registro...</h5>");

				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						$conexion->Execute("UPDATE productos SET eliminado = 0, fecha_eliminado = null WHERE (id_producto = '".$valor."')");
					}
				}
				
				echo("<script type='text/javascript'>");
				echo("window.location.href='".$_SERVER['PHP_SELF']."?menu=".$_GET['menu']."';");
				echo("</script>");
			}

			//	Cambiar estado
			if(isset($_GET['accion']) && ($_GET['accion'] == "cambiarestado")) {
				if(isset($_POST['registro'])) {
					foreach($_POST['registro'] as $valor) {
						//verifico el estado que posee
						$datos = $temp->GetRow("SELECT * FROM productos WHERE (id_producto = '".$valor."')");
						if($datos) { 
							if($datos["ACTIVO"] == 0) {
								$conexion->Query("UPDATE productos SET activo = 1 WHERE (id_producto = '".$valor."')");
							} else {
								if($datos["ACTIVO"] == 1) {
									$conexion->Query("UPDATE productos SET activo = 0 WHERE (id_producto = '".$valor."')");
								} else {
									$conexion->Query("UPDATE productos SET activo = 1 WHERE (id_producto = '".$valor."')");
								}
							}
							$datos = $temp->GetRow("SELECT * FROM productos WHERE (id_producto = '".$valor."')");
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
		//	-----	Fin PRODUCTOS

		include("../footer.php");
		?>
	</body>
</html>