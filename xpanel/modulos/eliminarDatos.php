<?php
// El objetivo de este script es que permite eliminar registros y obtener mensajes 
// de error en los casos que no se pueda borrar

header('Content-Type: text/html; charset=utf-8');
require("../config.php");

?>
[<?php
$contador = 0;

//	Eliminar COMPRAS logico
if(isset($_GET['menu']) && ($_GET['menu'] == "compras") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos de la COMPRA
			$registros = $conexion->Query("SELECT COD_VENTA,PAGADO,IMPRESO FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- La COMPRA: \\\"".sprintf("%04s",$registros->fields["COD_VENTA"])."\\\" ha sido PAGADA.\" }";
						} else {
							//	verifico si la VENTA fue IMPRESA
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- La COMPRA: \\\"".sprintf("%04s",$registros->fields["COD_VENTA"])."\\\" ha sido IMPRESA.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino el registro logicamente
				$conexion->Execute("UPDATE ventas SET eliminado = 1, fecha_eliminado = now() WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar COMPRAS Fisico
if(isset($_GET['menu']) && ($_GET['menu'] == "compras") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarfisico")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos de la COMPRA
			$registros = $conexion->Query("SELECT COD_VENTA,PAGADO,IMPRESO FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- La COMPRA: \\\"".sprintf("%04s",$registros->fields["COD_VENTA"])."\\\" ha sido PAGADA.\" }";
						} else {
							//	verifico si la VENTA fue IMPRESA
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- La COMPRA: \\\"".sprintf("%04s",$registros->fields["COD_VENTA"])."\\\" ha sido IMPRESA.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	obtengo los PRODUCTOS de la COMPRA
				$registros = $conexion->Query("SELECT ventas.ID_SUCURSAL,venta_posee_productos.ID_PRODUCTO,CANTIDAD,venta_posee_productos.ID_VENTA 
					FROM venta_posee_productos 
					LEFT JOIN ventas ON ventas.id_venta = venta_posee_productos.id_venta 
					WHERE (venta_posee_productos.id_venta = ".$valor.")
				");
				if($registros) {
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							//	disminuyo la cantidad comprada al STOCK del PRODUCTO
							$conexion->Execute("UPDATE productos 
								SET 
									stock = (stock - {$registros->fields["CANTIDAD"]}) 
								WHERE 
									(id_sucursal = '{$registros->fields["ID_SUCURSAL"]}') 
									and (id_producto = '{$registros->fields["ID_PRODUCTO"]}')
							");

							//	elimino el PRODUCTO de la COMPRA
							$conexion->Execute("DELETE FROM venta_posee_productos WHERE (id_venta = '{$registros->fields["ID_VENTA"]}') and (id_producto = '{$registros->fields["ID_PRODUCTO"]}')");

							$registros->MoveNext();
						}
					}
				}
				//	elimino el registro
				$conexion->Execute("DELETE FROM ventas WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar PRODUCTOS en los TRASPASOS
if(isset($_GET['menu']) && ($_GET['menu'] == "traspasos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarDetalle")) {
	if(!empty($_GET['master']) && !empty($_GET['id'])) {

		// actualizo el STOCK de los PRODUCTOS del TRASPASO
		$datosRegistro = $conexion->GetRow("SELECT * FROM traspasos WHERE (id_traspaso = ".$_GET['master'].")");
		$datosDetalles = $conexion->GetRow("SELECT * FROM traspaso_posee_productos WHERE (id_traspaso = '{$_GET['master']}') and (id_itemtraspaso = '{$_GET['id']}')");

		if($datosDetalles){

			$fechaHora = date('Y-m-d H:i:s');

			// producto de origen
			$datosProducto = $conexion->GetRow("SELECT nombre,stock FROM productos WHERE (id_producto = '{$datosDetalles['ID_PRODUCTOORIGEN']}') and (id_sucursal = '{$datosRegistro['ID_SUCURSALORIGEN']}')");
			if($datosProducto){

				// registro el aumento en el movimiento de stock, por el retorno de stock debido a la eliminación del traspaso
				$stockFinal = intval($datosProducto['stock']) + intval($datosDetalles['CANTIDAD']);
				$query = "INSERT INTO movimientos_stock 
					SET
						ID_USUARIO = '{$_GET['idu']}',
						ID_PRODUCTO = '{$datosDetalles['ID_PRODUCTOORIGEN']}',
						ID_SUCURSAL = '{$datosRegistro['ID_SUCURSALORIGEN']}',
						FECHA_REGISTRO = '{$fechaHora}',
						STOCK_ACTUAL = '{$datosProducto['stock']}',
						CANTIDAD = '{$datosDetalles['CANTIDAD']}',
						STOCK_FINAL = '{$stockFinal}',
						TIPO_MOVIMIENTOSTOCK = 'Aumento x Eliminación de ítem en Traspaso'
				";
				$conexion->Query($query);
				$id_movimientostockOrigen = $conexion->Insert_ID();

				if(!empty($id_movimientostockOrigen)){
					$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$datosDetalles['ID_PRODUCTOORIGEN']}')";
					$conexion->Query($query);
				}

			}

			// producto de destino
			$datosProducto = $conexion->GetRow("SELECT nombre,stock FROM productos WHERE (id_producto = '{$datosDetalles['ID_PRODUCTODESTINO']}') and (id_sucursal = '{$datosRegistro['ID_SUCURSALDESTINO']}')");
			if($datosProducto){

				// registro la disminucion en el movimiento de stock, por el retorno de stock debido a la eliminación del traspaso
				$stockFinal = intval($datosProducto['stock']) - intval($datosDetalles['CANTIDAD']);
				$query = "INSERT INTO movimientos_stock 
					SET
						ID_USUARIO = '{$_GET['idu']}',
						ID_PRODUCTO = '{$datosDetalles['ID_PRODUCTODESTINO']}',
						ID_SUCURSAL = '{$datosRegistro['ID_SUCURSALDESTINO']}',
						FECHA_REGISTRO = '{$fechaHora}',
						STOCK_ACTUAL = '{$datosProducto['stock']}',
						CANTIDAD = '{$datosDetalles['CANTIDAD']}',
						STOCK_FINAL = '{$stockFinal}',
						TIPO_MOVIMIENTOSTOCK = 'Disminución x Eliminación de ítem en Traspaso'
				";
				$conexion->Query($query);
				$id_movimientostockOrigen = $conexion->Insert_ID();

				if(!empty($id_movimientostockOrigen)){
					$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$datosDetalles['ID_PRODUCTODESTINO']}')";
					$conexion->Query($query);
				}

			}

		}

		// elimino el registro
		$conexion->Execute("DELETE FROM traspaso_posee_productos WHERE (id_traspaso = '{$_GET['master']}') and (id_itemtraspaso = '{$_GET['id']}')");
		// reset AUTO_INCREMENT
		$datosID = $conexion->GetRow("SELECT IF(max(id_itemtraspaso) > 0, (max(id_itemtraspaso) + 1), 1) ID FROM traspaso_posee_productos");
		if($datosID){
			$conexion->Execute("ALTER TABLE traspaso_posee_productos AUTO_INCREMENT = ".$datosID['ID']);
		}

		print "{ \"estado\" : \"OK\" }";
	} else {
		print "{ \"estado\" : \"ERROR\" }";
	}
}

//	Eliminar TRASPASOS
if(isset($_GET['menu']) && ($_GET['menu'] == "traspasos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			// actualizo el STOCK de los PRODUCTOS del TRASPASO
			$datosRegistro = $conexion->GetRow("SELECT * FROM traspasos WHERE (id_traspaso = ".$valor.")");
			$datosDetalles = $conexion->Query("SELECT * FROM traspaso_posee_productos WHERE (id_traspaso = ".$valor.")");

			$fechaHora = date('Y-m-d H:i:s');
			foreach ($datosDetalles as $key => $item) {
				// producto de origen
				$datosProducto = $conexion->GetRow("SELECT nombre,stock FROM productos WHERE (id_producto = '{$item['ID_PRODUCTOORIGEN']}') and (id_sucursal = '{$datosRegistro['ID_SUCURSALORIGEN']}')");
				if($datosProducto){

					// registro el aumento en el movimiento de stock, por el retorno de stock debido a la eliminación del traspaso
					$stockFinal = intval($datosProducto['stock']) + intval($item['CANTIDAD']);
					$query = "INSERT INTO movimientos_stock 
						SET
							ID_USUARIO = '{$_POST['idu']}',
							ID_PRODUCTO = '{$item['ID_PRODUCTOORIGEN']}',
							ID_SUCURSAL = '{$datosRegistro['ID_SUCURSALORIGEN']}',
							FECHA_REGISTRO = '{$fechaHora}',
							STOCK_ACTUAL = '{$datosProducto['stock']}',
							CANTIDAD = '{$item['CANTIDAD']}',
							STOCK_FINAL = '{$stockFinal}',
							TIPO_MOVIMIENTOSTOCK = 'Aumento x Eliminación de Traspaso'
					";
					$conexion->Query($query);
					$id_movimientostockOrigen = $conexion->Insert_ID();

					if(!empty($id_movimientostockOrigen)){
						$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$item['ID_PRODUCTOORIGEN']}')";
						$conexion->Query($query);
					}

				}

				// producto de destino
				$datosProducto = $conexion->GetRow("SELECT nombre,stock FROM productos WHERE (id_producto = '{$item['ID_PRODUCTODESTINO']}') and (id_sucursal = '{$datosRegistro['ID_SUCURSALDESTINO']}')");
				if($datosProducto){

					// registro la disminucion en el movimiento de stock, por el retorno de stock debido a la eliminación del traspaso
					$stockFinal = intval($datosProducto['stock']) - intval($item['CANTIDAD']);
					$query = "INSERT INTO movimientos_stock 
						SET
							ID_USUARIO = '{$_POST['idu']}',
							ID_PRODUCTO = '{$item['ID_PRODUCTODESTINO']}',
							ID_SUCURSAL = '{$datosRegistro['ID_SUCURSALDESTINO']}',
							FECHA_REGISTRO = '{$fechaHora}',
							STOCK_ACTUAL = '{$datosProducto['stock']}',
							CANTIDAD = '{$item['CANTIDAD']}',
							STOCK_FINAL = '{$stockFinal}',
							TIPO_MOVIMIENTOSTOCK = 'Disminución x Eliminación de Traspaso'
					";
					$conexion->Query($query);
					$id_movimientostockOrigen = $conexion->Insert_ID();

					if(!empty($id_movimientostockOrigen)){
						$query = "UPDATE productos SET stock = '{$stockFinal}' WHERE (id_producto = '{$item['ID_PRODUCTODESTINO']}')";
						$conexion->Query($query);
					}

				}
				
			}

			//	elimino los PRODUCTOS del TRASPASO
			$conexion->Execute("DELETE FROM traspaso_posee_productos WHERE (id_traspaso = ".$valor.")");
			// reset AUTO_INCREMENT
			$datosID = $conexion->GetRow("SELECT IF(max(id_itemtraspaso) > 0, (max(id_itemtraspaso) + 1), 1) ID FROM traspaso_posee_productos");
			if($datosID){
				$conexion->Execute("ALTER TABLE traspaso_posee_productos AUTO_INCREMENT = ".$datosID['ID']);
			}
			
			//	elimino el registro
			$conexion->Execute("DELETE FROM traspasos WHERE (id_traspaso = ".$valor.")");
			// reset AUTO_INCREMENT
			$datosID = $conexion->GetRow("SELECT IF(max(id_traspaso) > 0, (max(id_traspaso) + 1), 1) ID FROM traspasos");
			if($datosID){
				$conexion->Execute("ALTER TABLE traspasos AUTO_INCREMENT = ".$datosID['ID']);
			}

			print "{ \"estado\" : \"OK\" }";
		}		
	}
}

//	Eliminar PRODUCTOS en las VENTAS
if(isset($_GET['menu']) && ($_GET['menu'] == "ventas") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarDetalle")) {
	if(isset($_GET['idv']) and isset($_GET['idp']) and isset($_GET['ids'])) {
		//	devuelvo el STOCK a su PRODUCTO antes de eliminarlos
		$registros = $conexion->Query("SELECT * FROM venta_posee_productos WHERE (id_venta = '".$_GET['idv']."') and (id_producto = '".$_GET['idp']."')");
		while(!$registros->EOF) {
			//	aumento el STOCK
			$temp->Query("UPDATE productos SET stock = stock + (".$registros->fields["CANTIDAD"].") WHERE (id_sucursal = '".$_GET['ids']."') and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
			$registros->MoveNext();
		}
		$conexion->Execute("DELETE FROM venta_posee_productos WHERE (id_venta = '".$_GET['idv']."') and (id_producto = '".$_GET['idp']."')");	
		print "{ \"estado\" : \"OK\" }";
	} else {
		print "{ \"estado\" : \"ERROR\" }";
	}
}

//	Eliminar INVENTARIOS
if(isset($_GET['menu']) && ($_GET['menu'] == "inventarios") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino los PRODUCTOSs del INVENTARIO
			$conexion->Execute("DELETE FROM inventario_posee_productos WHERE (id_inventario = ".$valor.")");
			//	elimino el registro
			$conexion->Execute("DELETE FROM inventarios WHERE (id_inventario = ".$valor.")");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar COTIZACIONES
if(isset($_GET['menu']) && ($_GET['menu'] == "cotizaciones") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino los PRODUCTOSs de la COTIZACION
			$conexion->Execute("DELETE FROM cotizacion_posee_productos WHERE (id_cotizacion = ".$valor.")");
			//	elimino el registro
			$conexion->Execute("DELETE FROM cotizaciones WHERE (id_cotizacion = ".$valor.")");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar EGRESOS
if(isset($_GET['menu']) && ($_GET['menu'] == "egresos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos del EGRESO
			$registros = $conexion->Query("SELECT * FROM egresos WHERE (id_egreso = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- El EGRESO: \\\"".sprintf("%04s",$registros->fields["ID_EGRESO"])."\\\" ha sido PAGADA.\" }";
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	obtengo los PRODUCTOS del EGRESO
				$registros = $conexion->Query("SELECT egresos.ID_SUCURSAL,egreso_posee_productos.ID_PRODUCTO,CANTIDAD,egreso_posee_productos.ID_EGRESO FROM egreso_posee_productos LEFT JOIN egresos ON egresos.id_egreso = egreso_posee_productos.id_egreso WHERE (egreso_posee_productos.id_egreso = ".$valor.")");
				if($registros) {
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							//	disminuyo la cantidad al STOCK del PRODUCTO
							$conexion->Execute("UPDATE productos SET stock = (stock-".$registros->fields["CANTIDAD"].") WHERE (id_sucursal = ".$registros->fields["ID_SUCURSAL"].") and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");

							//	elimino el PRODUCTO del EGRESO
							$conexion->Execute("DELETE FROM egreso_posee_productos WHERE (id_egreso = ".$registros->fields["ID_EGRESO"].") and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");

							$registros->MoveNext();
						}
					}
				}
				//	elimino el registro
				$conexion->Execute("DELETE FROM egresos WHERE (id_egreso = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar PEDIDOS logico
if(isset($_GET['menu']) && ($_GET['menu'] == "pedidos") and isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			if($vMsj == "") {
				//	elimino el registro logicamente
				$conexion->Execute("UPDATE ventas SET eliminado = 1, fecha_eliminado = now() WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar PEDIDOS fisico
if(isset($_GET['menu']) && ($_GET['menu'] == "pedidos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarfisico")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			if($vMsj == "") {
				//	elimino el registro
				$conexion->Execute("DELETE FROM ventas WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar SERVICIOS logico
if(isset($_GET['menu']) && ($_GET['menu'] == "servicios") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos del SERVICIO
			$registros = $conexion->Query("SELECT * FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si el SERVICIO fue PAGADO
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- El SERVICIO: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido PAGADO.\" }";
						} else {
							//	verifico si el SERVICIO fue IMPRESO
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- El SERVICIO: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido IMPRESO.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino el registro logicamente
				$conexion->Execute("UPDATE ventas SET eliminado = 1, fecha_eliminado = now() WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar SERVICIOS fisico
if(isset($_GET['menu']) && ($_GET['menu'] == "servicios") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarfisico")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos de la VENTA
			$registros = $conexion->Query("SELECT * FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- El SERVICIO: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido PAGADA.\" }";
						} else {
							//	verifico si la VENTA fue IMPRESA
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- El SERVICIO: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido IMPRESA.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino el registro
				$conexion->Execute("DELETE FROM ventas WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar VENTAS logico
if(isset($_GET['menu']) && ($_GET['menu'] == "ventas") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos de la VENTA
			$registros = $conexion->Query("SELECT * FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- La VENTA: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido PAGADA.\" }";
						} else {
							//	verifico si la VENTA fue IMPRESA
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- La VENTA: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido IMPRESA.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino el registro logicamente
				$conexion->Execute("UPDATE ventas SET eliminado = 1, fecha_eliminado = now() WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar VENTAS Fisico
if(isset($_GET['menu']) && ($_GET['menu'] == "ventas") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarfisico")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico datos de la VENTA
			$registros = $conexion->Query("SELECT * FROM ventas WHERE (id_venta = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registros->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }

						//	verifico si la VENTA fue PAGADA
						if($registros->fields["PAGADO"] == "1") {
							$vMsj .= "{ \"error\" : \"- La VENTA: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido PAGADA.\" }";
						} else {
							//	verifico si la VENTA fue IMPRESA
							if($registros->fields["IMPRESO"] == "1") {
								$vMsj .= "{ \"error\" : \"- La VENTA: \\\"".sprintf("%04s",$registros->fields["ID_VENTA"])."\\\" ha sido IMPRESA.\" }";
							}
						}
						$registros->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	obtengo los PRODUCTOS de la VENTA
				$registros = $conexion->Query("SELECT ventas.ID_SUCURSAL,venta_posee_productos.ID_PRODUCTO,CANTIDAD,venta_posee_productos.ID_VENTA FROM venta_posee_productos LEFT JOIN ventas ON ventas.id_venta = venta_posee_productos.id_venta WHERE (venta_posee_productos.id_venta = ".$valor.")");
				if($registros) {
					if($registros->RecordCount() > 0) {
						while(!$registros->EOF) {
							//	aumento la cantidad vendida al STOCK del PRODUCTO
							$conexion->Execute("UPDATE productos SET stock = (stock+".$registros->fields["CANTIDAD"].") WHERE (id_sucursal = ".$registros->fields["ID_SUCURSAL"].") and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");

							//	elimino el PRODUCTO de la VENTA
							$conexion->Execute("DELETE FROM venta_posee_productos WHERE (id_venta = ".$registros->fields["ID_VENTA"].") and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");

							$registros->MoveNext();
						}
					}
				}
				//	elimino el registro
				$conexion->Execute("DELETE FROM ventas WHERE (id_venta = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}

		}		
	}
}

//	Eliminar PRODUCTOS logico
if(isset($_GET['menu']) && ($_GET['menu'] == "productos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			if($vMsj == "") {
				//	elimino el registro logicamente
				$conexion->Execute("UPDATE productos SET eliminado = 1, fecha_eliminado = now() WHERE (id_producto = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}
		}		
	}
}

//	Eliminar PRODUCTOS fisico
if(isset($_GET['menu']) && ($_GET['menu'] == "productos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminarfisico")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico si esta asignado a COTIZACIONES
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM cotizacion_posee_productos LEFT JOIN productos ON productos.id_producto = cotizacion_posee_productos.id_producto WHERE (cotizacion_posee_productos.id_producto = '".$valor."') GROUP BY cotizacion_posee_productos.id_cotizacion");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." COTIZACIONES.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}

			//	verifico si esta asignado a EGRESOS
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM egreso_posee_productos LEFT JOIN productos ON productos.id_producto = egreso_posee_productos.id_producto WHERE (egreso_posee_productos.id_producto = '".$valor."') GROUP BY egreso_posee_productos.id_egreso");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." EGRESOS.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}

			//	verifico si esta asignado a INVENTARIOS
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM inventario_posee_productos LEFT JOIN productos ON productos.id_producto = inventario_posee_productos.id_producto WHERE (inventario_posee_productos.id_producto = '".$valor."') GROUP BY inventario_posee_productos.id_inventario");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." INVENTARIOS.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}

			//	verifico si esta asignado a MOVIMIENTOS_STOCK
			/*
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM movimientos_stock LEFT JOIN productos ON productos.id_producto = movimientos_stock.id_producto WHERE (movimientos_stock.id_producto = '".$valor."') GROUP BY movimientos_stock.id_movimientostock");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." MOVIMIENTOS DE STOCK.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}
			*/

			//	verifico si esta asignado a STOCK de PRODUCTOS
			/*
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM sucursal_posee_productos LEFT JOIN productos ON productos.id_producto = sucursal_posee_productos.id_producto WHERE (sucursal_posee_productos.id_producto = '".$valor."') GROUP BY sucursal_posee_productos.id_sucursal");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" posee STOCK asignado en ".$registrosDeudas->fields["CANTIDAD"]." SUCURSALES.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}
			*/

			//	verifico si esta asignado a TRASPASO_POSEE_PRODUCTOS
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM traspaso_posee_productos LEFT JOIN productos ON productos.id_producto = traspaso_posee_productos.id_producto WHERE (traspaso_posee_productos.id_producto = '".$valor."') GROUP BY traspaso_posee_productos.id_traspaso");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." TRASPASOS.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}

			//	verifico si esta asignado a VENTAS
			$registrosDeudas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM venta_posee_productos LEFT JOIN productos ON productos.id_producto = venta_posee_productos.id_producto WHERE (venta_posee_productos.id_producto = '".$valor."') GROUP BY venta_posee_productos.id_venta");
			if($registrosDeudas) {
				if($registrosDeudas->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosDeudas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El PRODUCTO: \\\"".stripslashes($registrosDeudas->fields["NOMBRE"])."\\\" esta asignado en ".$registrosDeudas->fields["CANTIDAD"]." VENTAS.\" }";
						$registrosDeudas->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	verifico si posee FOTO
				$registros = $conexion->Execute("SELECT * FROM productos WHERE (id_producto = ".$valor.")");
				if($registros) {
					while(!$registros->EOF) {
						if($registros->fields["FOTO"] != null) {
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."productos/".$registros->fields["FOTO"];
							//	verifico si el archivo existe
							if(file_exists($nombre_completo_imagenoriginal)) {
								//obtengo el nombre y extension del archivo small
								$res = explode(".", $registros->fields["FOTO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = "../../".$path_imagenes."productos/".$nombre."_small.".$extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if(file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}
						}
						$registros->MoveNext();
					}
				}
				//	verifico si posee ARCHIVOS
				$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_producto = '".$valor."') ORDER BY id_archivo");
				if($registros) {
					while(!$registros->EOF) {
					   if($registros->fields["TIPO_ARCHIVO"] == 'Imagen') {
						  //	verifico si el archivo existe
						  $nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_imagenoriginal)) {
							 //	obtengo el nombre y extension del archivo small
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
					   if($registros->fields["TIPO_ARCHIVO"] == 'Archivo') {
						  //	verifico si el archivo existe
						  $nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_documento)) {
							 //	elimino el archivo
							 unlink($nombre_completo_documento);
						  }
					   }
					   //	elimino el registro
					   $temp->Execute("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

					   $registros->MoveNext();
					}
				}
				//	elimino las sucursal_posee_productos
				$conexion->Execute("DELETE FROM sucursal_posee_productos WHERE (id_producto = ".$valor.")");
				//	elimino los movimientos de stock
				$conexion->Execute("DELETE FROM movimientos_stock WHERE (id_producto = ".$valor.")");
				//	elimino el registro
				$conexion->Execute("DELETE FROM productos WHERE (id_producto = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}
		}		
	}
}

//	Eliminar SUCURSALES
if(isset($_GET['menu']) && ($_GET['menu'] == "sucursales") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico si posee COTIZACIONES
			$registrosCotizaciones = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM cotizaciones LEFT JOIN sucursales ON sucursales.id_sucursal = cotizaciones.id_sucursal WHERE (cotizaciones.id_sucursal = '".$valor."') GROUP BY cotizaciones.id_sucursal");
			if($registrosCotizaciones) {
				if($registrosCotizaciones->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosCotizaciones->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosCotizaciones->fields["NOMBRE"])."\\\" posee ".$registrosCotizaciones->fields["CANTIDAD"]." COTIZACIONES registradas.\" }";
						$registrosCotizaciones->MoveNext();
					}
				}
			}

			//	verifico si posee EGRESOS
			$registrosEgresos = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM egresos LEFT JOIN sucursales ON sucursales.id_sucursal = egresos.id_sucursal WHERE (egresos.id_sucursal = '".$valor."') GROUP BY egresos.id_sucursal");
			if($registrosEgresos) {
				if($registrosEgresos->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosEgresos->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosEgresos->fields["NOMBRE"])."\\\" posee ".$registrosEgresos->fields["CANTIDAD"]." EGRESOS registrados.\" }";
						$registrosEgresos->MoveNext();
					}
				}
			}

			//	verifico si posee INVENTARIOS
			$registrosInventarios = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM inventarios LEFT JOIN sucursales ON sucursales.id_sucursal = egresos.id_sucursal WHERE (egresos.id_sucursal = '".$valor."') GROUP BY egresos.id_sucursal");
			if($registrosInventarios) {
				if($registrosInventarios->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosInventarios->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosInventarios->fields["NOMBRE"])."\\\" posee ".$registrosInventarios->fields["CANTIDAD"]." INVENTARIOS registrados.\" }";
						$registrosInventarios->MoveNext();
					}
				}
			}

			//	verifico si posee VENTAS
			$registrosVentas = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM ventas LEFT JOIN sucursales ON sucursales.id_sucursal = ventas.id_sucursal WHERE (ventas.id_sucursal = '".$valor."') GROUP BY ventas.id_sucursal");
			if($registrosVentas) {
				if($registrosVentas->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosVentas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosVentas->fields["NOMBRE"])."\\\" posee ".$registrosVentas->fields["CANTIDAD"]." VENTAS registradas.\" }";
						$registrosVentas->MoveNext();
					}
				}
			}

			//	verifico si posee STOCK de PRODUCTOS
			/*
			$registrosStock = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM sucursal_posee_productos LEFT JOIN sucursales ON sucursales.id_sucursal = sucursal_posee_productos.id_sucursal WHERE (sucursal_posee_productos.id_sucursal = '".$valor."') GROUP BY sucursal_posee_productos.id_sucursal");
			if($registrosStock) {
				if($registrosStock->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosStock->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosStock->fields["NOMBRE"])."\\\" posee ".$registrosStock->fields["CANTIDAD"]." STOCK de PRODUCTOS registrados.\" }";
						$registrosStock->MoveNext();
					}
				}
			}
			*/

			// verifico si fue asignado como ID_PRINCIPAL
			$registrosSucursales = $conexion->Query("SELECT NOMBRE,count(*) as CANTIDAD FROM sucursales WHERE (id_sucursal <> '{$valor}') and (id_principal = '{$valor}') GROUP BY id_principal");
			if($registrosSucursales) {
				if($registrosSucursales->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosSucursales->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- La SUCURSAL: \\\"".stripslashes($registrosSucursales->fields["NOMBRE"])."\\\" ha sido asignado ".$registrosSucursales->fields["CANTIDAD"]." ".($registrosSucursales->fields["CANTIDAD"] > 1 ? "veces" : "vez")." como SUCURSAL PRINCIPAL.\" }";
						$registrosSucursales->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino los accesos registrados
				$conexion->Execute("DELETE FROM contador WHERE (id_sucursal = ".$valor.")");
				//	elimino los accesos otorgados
				$conexion->Execute("DELETE FROM privilegios WHERE (id_sucursal = ".$valor.")");
				//	elimino el registro
				$conexion->Execute("DELETE FROM sucursales WHERE (id_sucursal = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}
		}		
	}
}

//	Eliminar CLIENTES / PROVEEDORES
if(isset($_GET['menu']) && (in_array($_GET['menu'], ['clientes','proveedores'])) && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			$datosRegistro = $conexion->GetRow("SELECT NOMBRE_COMPLETO, IFNULL(foto,'') FOTO FROM usuarios WHERE (id_usuario = '{$valor}')");

			if ($contador++ > 0) { print ", "; }

			//	verifico si posee COTIZACIONES como CLIENTE
			$registrosCotizaciones = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM cotizaciones LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_cliente WHERE (cotizaciones.id_cliente = '".$valor."') GROUP BY cotizaciones.id_cliente");
			if($registrosCotizaciones) {
				if($registrosCotizaciones->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosCotizaciones->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosCotizaciones->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosCotizaciones->fields["CANTIDAD"]." COTIZACIONES registradas como CLIENTE.\" }";
						$registrosCotizaciones->MoveNext();
					}
				}
			}

			//	verifico si posee EGRESOS como CLIENTE
			$registrosEgresos = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM egresos LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_cliente WHERE (egresos.id_cliente = '".$valor."') GROUP BY egresos.id_cliente");
			if($registrosEgresos) {
				if($registrosEgresos->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosEgresos->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosEgresos->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosEgresos->fields["CANTIDAD"]." EGRESOS registradas como CLIENTE.\" }";
						$registrosEgresos->MoveNext();
					}
				}
			}

			//	verifico si posee VENTAS como CLIENTE
			$registrosVentas = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM ventas LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente WHERE (ventas.id_cliente = '".$valor."') GROUP BY ventas.id_cliente");
			if($registrosVentas) {
				if($registrosVentas->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosVentas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosVentas->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosVentas->fields["CANTIDAD"]." VENTAS registradas como CLIENTE.\" }";
						$registrosVentas->MoveNext();
					}
				}
			}

			//	verifico si posee COMPRAS como PROVEEDOR
			$datosResultado = $conexion->Query("SELECT count(*) as CANTIDAD FROM ventas WHERE (tipo_venta = 'compras') and (ventas.id_cliente = '".$valor."') GROUP BY id_cliente");
			if($datosResultado) {
				if($datosResultado->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$datosResultado->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$datosRegistro["NOMBRE_COMPLETO"]."\\\" posee ".$datosResultado->fields["CANTIDAD"]." COMPRAS registradas como PROVEEDOR.\" }";
						$datosResultado->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino los accesos registrados
				$conexion->Execute("DELETE FROM contador WHERE (id_usuario = ".$valor.")");
				//	elimino los accesos otorgados
				$conexion->Execute("DELETE FROM privilegios WHERE (id_usuario = ".$valor.")");
				//	obtengo los datos y verifico si tiene FOTO
				$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
				if($registros) {
					while(!$registros->EOF) {
						if($registros->fields["FOTO"] != null) {
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
							//	verifico si el archivo existe
							if(file_exists($nombre_completo_imagenoriginal)) {
								//obtengo el nombre y extension del archivo small
								$res = explode(".", $registros->fields["FOTO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if(file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}
						}
						$registros->MoveNext();
					}
				}
				//	elimino el registro
				$conexion->Execute("DELETE FROM usuarios WHERE (id_usuario = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}
		}		
	}
}

//	Eliminar USUARIOS
if(isset($_GET['menu']) && ($_GET['menu'] == "usuarios") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		$vMsj = "";
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico si posee CONTENIDOS
			$registrosContenidos = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM contenidos LEFT JOIN usuarios ON usuarios.id_usuario = contenidos.id_usuario WHERE (contenidos.id_usuario = '".$valor."') GROUP BY contenidos.id_usuario");
			if($registrosContenidos) {
				if($registrosContenidos->RecordCount() > 0) {
					$contador2 = 0;
					while(!$registrosContenidos->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosContenidos->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosContenidos->fields["CANTIDAD"]." CONTENIDOS registrados.\" }";
						$registrosContenidos->MoveNext();
					}
				}
			}

			//	verifico si posee COTIZACIONES como CLIENTE
			$registrosCotizaciones = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM cotizaciones LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_cliente WHERE (cotizaciones.id_cliente = '".$valor."') GROUP BY cotizaciones.id_cliente");
			if($registrosCotizaciones) {
				if($registrosCotizaciones->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosCotizaciones->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosCotizaciones->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosCotizaciones->fields["CANTIDAD"]." COTIZACIONES registradas como CLIENTE.\" }";
						$registrosCotizaciones->MoveNext();
					}
				}
			}

			//	verifico si posee COTIZACIONES como USUARIO
			$registrosCotizaciones = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM cotizaciones LEFT JOIN usuarios ON usuarios.id_usuario = cotizaciones.id_usuario WHERE (cotizaciones.id_usuario = '".$valor."') GROUP BY cotizaciones.id_usuario");
			if($registrosCotizaciones) {
				if($registrosCotizaciones->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosCotizaciones->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosCotizaciones->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosCotizaciones->fields["CANTIDAD"]." COTIZACIONES registradas como USUARIO.\" }";
						$registrosCotizaciones->MoveNext();
					}
				}
			}

			//	verifico si posee EGRESOS como CLIENTE
			$registrosEgresos = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM egresos LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_cliente WHERE (egresos.id_cliente = '".$valor."') GROUP BY egresos.id_cliente");
			if($registrosEgresos) {
				if($registrosEgresos->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosEgresos->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosEgresos->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosEgresos->fields["CANTIDAD"]." EGRESOS registradas como CLIENTE.\" }";
						$registrosEgresos->MoveNext();
					}
				}
			}

			//	verifico si posee EGRESOS como USUARIO
			$registrosEgresos = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM egresos LEFT JOIN usuarios ON usuarios.id_usuario = egresos.id_usuario WHERE (egresos.id_usuario = '".$valor."') GROUP BY egresos.id_usuario");
			if($registrosEgresos) {
				if($registrosEgresos->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosEgresos->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosEgresos->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosEgresos->fields["CANTIDAD"]." EGRESOS registradas como USUARIO.\" }";
						$registrosEgresos->MoveNext();
					}
				}
			}

			//	verifico si posee INVENTARIOS como USUARIOVERIFICA
			$registrosInventarios = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM inventarios LEFT JOIN usuarios ON usuarios.id_usuario = inventarios.id_usuarioverifica WHERE (inventarios.id_usuarioverifica = '".$valor."') GROUP BY inventarios.id_usuarioverifica");
			if($registrosInventarios) {
				if($registrosInventarios->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosInventarios->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosInventarios->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosInventarios->fields["CANTIDAD"]." INVENTARIOS registrados como USUARIO que verifica.\" }";
						$registrosInventarios->MoveNext();
					}
				}
			}

			//	verifico si posee INVENTARIOS como USUARIO
			$registrosInventarios = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM inventarios LEFT JOIN usuarios ON usuarios.id_usuario = inventarios.id_usuario WHERE (inventarios.id_usuario = '".$valor."') GROUP BY inventarios.id_usuario");
			if($registrosInventarios) {
				if($registrosInventarios->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosInventarios->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosInventarios->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosInventarios->fields["CANTIDAD"]." INVENTARIOS registrados como USUARIO.\" }";
						$registrosInventarios->MoveNext();
					}
				}
			}

			//	verifico si posee VENTAS como CLIENTE
			$registrosVentas = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM ventas LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_cliente WHERE (ventas.id_cliente = '".$valor."') GROUP BY ventas.id_cliente");
			if($registrosVentas) {
				if($registrosVentas->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosVentas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosVentas->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosVentas->fields["CANTIDAD"]." VENTAS registradas como CLIENTE.\" }";
						$registrosVentas->MoveNext();
					}
				}
			}

			//	verifico si posee VENTAS como USUARIO
			$registrosVentas = $conexion->Query("SELECT NOMBRE_COMPLETO,count(*) as CANTIDAD FROM ventas LEFT JOIN usuarios ON usuarios.id_usuario = ventas.id_usuario WHERE (ventas.id_usuario = '".$valor."') GROUP BY ventas.id_usuario");
			if($registrosVentas) {
				if($registrosVentas->RecordCount() > 0) {
					if ($vMsj != "") { $vMsj .= ", "; }
					$contador2 = 0;
					while(!$registrosVentas->EOF) {
						if ($contador2++ > 0) { $vMsj .= ", "; }
						$vMsj .= "{ \"error\" : \"- El USUARIO: \\\"".$registrosVentas->fields["NOMBRE_COMPLETO"]."\\\" posee ".$registrosVentas->fields["CANTIDAD"]." VENTAS registradas como USUARIO.\" }";
						$registrosVentas->MoveNext();
					}
				}
			}

			if($vMsj == "") {
				//	elimino los accesos registrados
				$conexion->Execute("DELETE FROM contador WHERE (id_usuario = ".$valor.")");
				//	elimino los accesos otorgados
				$conexion->Execute("DELETE FROM privilegios WHERE (id_usuario = ".$valor.")");
				//	obtengo los datos y verifico si tiene FOTO
				$registros = $conexion->Execute("SELECT * FROM usuarios WHERE (id_usuario = ".$valor.")");
				if($registros) {
					while(!$registros->EOF) {
						if($registros->fields["FOTO"] != null) {
							$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotos/".$registros->fields["FOTO"];
							//	verifico si el archivo existe
							if(file_exists($nombre_completo_imagenoriginal)) {
								//obtengo el nombre y extension del archivo small
								$res = explode(".", $registros->fields["FOTO"]);
								$nombre = $res[0];
								$extension = $res[count($res) - 1];
								//	obtengo la ubicacion de la imagen small
								$nombre_completo_imagensmall = "../../".$path_imagenes."fotos/".$nombre."_small.".$extension;
								//	elimino el archivo
								unlink($nombre_completo_imagenoriginal);
								if(file_exists($nombre_completo_imagensmall)) {
									unlink($nombre_completo_imagensmall);
								}
							}
						}
						$registros->MoveNext();
					}
				}
				//	elimino el registro
				$conexion->Execute("DELETE FROM usuarios WHERE (id_usuario = ".$valor.")");
				print "{ \"estado\" : \"OK\" }";				
			} else {
				print "{ \"estado\" : \"ERROR\", \"errores\" : [".$vMsj."] }";
			}
		}		
	}
}

//	Eliminar FOTOGALERIA
if(isset($_GET['menu']) && (($_GET['menu'] == "fotogaleria") || ($_GET['menu'] == "imgportada")) && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	obtengo los datos del registro
			$registros = $conexion->Query("SELECT * FROM fotogaleria WHERE (id_foto = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
						//	verifico si el archivo existe
						$nombre_completo_imagenoriginal = "../../".$path_imagenes."fotogaleria/".$registros->fields["NOMBRE"];
						if(file_exists($nombre_completo_imagenoriginal) and ($registros->fields["NOMBRE"] != null)) {
							//	obtengo el nombre y extension del archivo small
							$res = explode(".", $registros->fields["NOMBRE"]);
							$nombre = $res[0];
							$extension = $res[count($res) - 1];
							//	obtengo la ubicacion de la imagen original
							$nombre_completo_imagensmall = "../../".$path_imagenes."fotogaleria/".$nombre."_small.".$extension;
							//	elimino el archivo
							unlink($nombre_completo_imagenoriginal);
							if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							}
						}
						$registros->MoveNext();
					}
				}
			}

			//	elimino el registro
			$conexion->Execute("DELETE FROM fotogaleria WHERE (id_foto = '".$valor."')");			
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar BOLETIN
if(isset($_GET['menu']) && ($_GET['menu'] == "boletin") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico si posee ARCHIVOS
			$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_boletin = '".$valor."') ORDER BY id_archivo");
			if($registros) {
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
					   if($registros->fields["TIPO_ARCHIVO"] == 'Imagen') {
						  //	verifico si el archivo existe
						  $nombre_completo_imagenoriginal = "../../".$path_imagenes."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_imagenoriginal)) {
							 //	obtengo el nombre y extension del archivo small
							 $res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
							 $nombre = $res[0];
							 $extension = $res[count($res) - 1];
							 //	obtengo la ubicacion de la imagen original
							 $nombre_completo_imagensmall = "../../".$path_imagenes."boletin/".$nombre."_small.".$extension;
							 //	elimino el archivo
							 unlink($nombre_completo_imagenoriginal);
							 if(file_exists($nombre_completo_imagensmall)) {
								unlink($nombre_completo_imagensmall);
							 }
						  }
					   }
					   if($registros->fields["TIPO_ARCHIVO"] == 'Archivo') {
						  //	verifico si el archivo existe
						  $nombre_completo_documento = "../../".$path_archivos."boletin/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_documento)) {
							 //	elimino el archivo
							 unlink($nombre_completo_documento);
						  }
					   }
					   //	elimino el registro
					   $temp->Execute("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

					   $registros->MoveNext();
					}
				}
			}
			//	verifico si posee PUBLICIDAD asignada
			$registros = $conexion->Query("SELECT BANNER1,BANNER2 FROM boletin WHERE (id_boletin = '".$valor."')");
			if($registros) {
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
						$archivo = "../../".$path_imagenes."boletin/".$registros->fields["BANNER1"];
						if(($registros->fields["BANNER1"] != null) and file_exists($archivo)) {
						   unlink($archivo);
						}
						$archivo = "../../".$path_imagenes."boletin/".$registros->fields["BANNER2"];
						if(($registros->fields["BANNER2"] != null)and file_exists($archivo)) {
						   unlink($archivo);
						}
						$registros->MoveNext();
					}
				}
			}

			//	elimino los accesos de los contactos
			$conexion->Execute("DELETE FROM contactos_acceden WHERE (id_boletin = '".$valor."')");
			//	elimino los envios realizados a los contactos
			$conexion->Execute("DELETE FROM contactos_reciben WHERE (id_boletin = '".$valor."')");
			//	elimino el registro
			$conexion->Execute("DELETE FROM boletin WHERE (id_boletin = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar CONTACTOS
if(isset($_GET['menu']) && ($_GET['menu'] == "contactos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino los datos que posea registrado
			$conexion->Execute("DELETE FROM contactos_acceden WHERE (id_contacto = '".$valor."')");
			$conexion->Execute("DELETE FROM contactos_descargan WHERE (id_contacto = '".$valor."')");
			$conexion->Execute("DELETE FROM contactos_pertenece WHERE (id_contacto = '".$valor."')");
			$conexion->Execute("DELETE FROM contactos_reciben WHERE (id_contacto = '".$valor."')");
			//	eliminar el registro
			$conexion->Execute("DELETE FROM contactos WHERE (id_contacto = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar GRUPOS
if(isset($_GET['menu']) && ($_GET['menu'] == "grupos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino los CONTACTOS del GRUPO
			$conexion->Execute("DELETE FROM contactos_pertenecen WHERE (id_grupo = '".$valor."')");
			//	elimino el registro
			$conexion->Execute("DELETE FROM grupos WHERE (id_grupo = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar CONTENIDOS
if(isset($_GET['menu']) && (($_GET['menu'] == "laempresa") || ($_GET['menu'] == "novedades") || ($_GET['menu'] == "accesorios")) && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	verifico si posee ARCHIVOS
			$registros = $conexion->Query("SELECT * FROM archivos WHERE (id_contenido = '".$valor."') ORDER BY id_archivo");
			if($registros) {
				if($registros->RecordCount() > 0) {
					while(!$registros->EOF) {
					   if($registros->fields["TIPO_ARCHIVO"] == 'Imagen') {
						  //	verifico si el archivo existe
						  $nombre_completo_imagenoriginal = "../../".$path_imagenes."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_imagenoriginal)) {
							 //	obtengo el nombre y extension del archivo small
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
					   if($registros->fields["TIPO_ARCHIVO"] == 'Archivo') {
						  //	verifico si el archivo existe
						  $nombre_completo_documento = "../../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"];
						  if(file_exists($nombre_completo_documento)) {
							 //	elimino el archivo
							 unlink($nombre_completo_documento);
						  }
					   }
					   //	elimino el registro
					   $temp->Execute("DELETE FROM archivos WHERE (id_archivo = '".$registros->fields["ID_ARCHIVO"]."')");

					   $registros->MoveNext();
					}
				}
			}

			//	elimino el registro
			$conexion->Execute("DELETE FROM contenidos WHERE (id_contenido = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar MENSAJES: consultas, comentarios y novedades
if(isset($_GET['menu']) && (($_GET['menu'] == "consultas") || ($_GET['menu'] == "comentarios")) && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino todas las respuestas
			$conexion->Query("DELETE FROM mensajes WHERE id_cabeza = '".$valor."'");			
			//	elimino el registro
			$conexion->Query("DELETE FROM mensajes WHERE id_mensaje = '".$valor."'");			
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar CONTADOR
if(isset($_GET['menu']) && ($_GET['menu'] == "accesos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino el registro
			$conexion->Execute("DELETE FROM contador WHERE (id_contador = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar PRIVILEGIOS
if(isset($_GET['menu']) && ($_GET['menu'] == "privilegios") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino el registro
			$conexion->Execute("DELETE FROM privilegios WHERE (id_usuario = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar CONTENIDOS_MENU
if(isset($_GET['menu']) && ($_GET['menu'] == "contenidos") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//elimino los accesos otorgados
			$conexion->Execute("DELETE FROM privilegios WHERE (id_menu = '".$valor."')");
			//elimino el registro
			$conexion->Execute("DELETE FROM menu_modulos WHERE (id_menu = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

//	Eliminar PAISES
if(isset($_GET['menu']) && ($_GET['menu'] == "paises") && isset($_GET['accion']) && ($_GET['accion'] == "eliminar")) {
	if(isset($_POST['registro'])) {
		foreach($_POST['registro'] as $valor) {
			if ($contador++ > 0) { print ", "; }

			//	elimino el registro
			$conexion->Execute("DELETE FROM paises WHERE (codigo_pais = '".$valor."')");
			print "{ \"estado\" : \"OK\" }";				
		}		
	}
}

$conexion->Close();
$temp->Close();
$temp2->Close();
$temp3->Close();
$temp4->Close();
$privilegios->Close();
?>]