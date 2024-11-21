<?php
	/*
		Autor: Walter E. Irahola
		E-mail: wirahola@hotmail.com
		Proyecto: exportar datos desde xpanel
	*/

	require_once('../config.php');
	require_once __DIR__ . '/../vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\Writer\Xls;

	if(isset($_POST['menu'])){

		// Crear una instancia de la hoja de cálculo
		$spreadsheet = new Spreadsheet();

		// Definir propiedades
		$spreadsheet->getProperties()
			->setCreator("xperticia.com")
			->setLastModifiedBy("Walter E. Irahola")
			->setTitle("Reporte de ".ucfirst($_POST['menu']))
			->setCategory("Reportes de xpanel");

		$spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);

		$sheet = $spreadsheet->getActiveSheet();
		
		switch ($_POST['menu']) {
			case 'productos':

				// Definir propiedades
				$spreadsheet->getProperties()
					->setTitle("Stock de Productos")
					->setSubject("Productos al ".date("d-m-Y"))
					->setDescription("Datos exportados desde xpanel con PHPExcel.")
					->setKeywords("office 2007 openxml php xperticia")
					->setCategory("exportarStock");

				switch ($_POST['accion']) {
					case 'exportarStock':
						$titulo = ucfirst($_POST['ns']);
						if(isset($_POST['c']) && !empty($_POST['c'])){
							$titulo .= '_'.ucfirst($_POST['c']);
						}
						$titulo = strlen($titulo) > 31 ? substr($titulo, 0, 31) : $titulo;
						$sheet->setTitle($titulo);

						$sheet->setCellValue('A1', 'CODIGO')
							->setCellValue('B1', 'NOMBRE')
							->setCellValue('C1', 'TIPO_PRODUCTO')
							->setCellValue('D1', 'CATEGORIA')
							->setCellValue('E1', 'GENERO')
							->setCellValue('F1', 'PRECIO_COSTO')
							->setCellValue('G1', 'PRECIO_VENTA')
							->setCellValue('H1', 'STOCK');

						$query = "SELECT 
								productos.ID_PRODUCTO,
								CODIGO,
								TIPO_PRODUCTO,
								CATEGORIA,
								NOMBRE,
								GENERO,
								PRECIO_COSTO,
								PRECIO_VENTA,
								IF(STOCK >= 0,STOCK,0) as STOCK 
							FROM productos 
							WHERE 
								(soloapedido = 0) 
								and (id_sucursal = '".$_POST['s']."')
						";
						if(isset($_POST['tp']) && !empty($_POST['tp'])) { 
							if($_POST['tp'] == '*'){
								$query .= " AND (IFNULL(tipo_producto,'') = '') "; 
							} else {
								$query .= " AND (IFNULL(tipo_producto,'') = '".$_POST['tp']."') "; 
							}
						}
						if(isset($_POST['c']) && !empty($_POST['c'])) { $query .= " AND (categoria = '".$_POST['c']."') "; }
						if(isset($_POST['g']) && !empty($_POST['g'])) { $query .= " AND (genero = '".$_POST['g']."') "; }
						if((isset($_POST['tc']) && !empty($_POST['tc'])) && (isset($_POST['to']) && !empty($_POST['to']))) {
							$query .= " ORDER BY ".$_POST['tc']." ".$_POST['to'];
						} else {
							$query .= " ORDER BY codigo ASC";
						}
						$registros = $conexion->Query($query);

						if($registros && ($registros->RecordCount() > 0)){
							$i = 2;
							while(!$registros->EOF) {
								$sheet->setCellValue('A'.$i, $registros->fields["CODIGO"])
									->setCellValue('B'.$i, str_replace("\"","'",stripslashes(utf8_encode($registros->fields["NOMBRE"]))))
									->setCellValue('C'.$i, $registros->fields["TIPO_PRODUCTO"])
									->setCellValue('D'.$i, $registros->fields["CATEGORIA"])
									->setCellValue('E'.$i, $registros->fields["GENERO"])
									->setCellValue('F'.$i, number_format($registros->fields["PRECIO_COSTO"], 2, ',', '.'))
									->setCellValue('G'.$i, number_format($registros->fields["PRECIO_VENTA"], 2, ',', '.'))
									->setCellValue('H'.$i, $registros->fields["STOCK"]);
								$registros->MoveNext();	
								$i++;
							}
						}

						$sheet->getColumnDimension('B')->setAutoSize(true);
						$sheet->getColumnDimension('C')->setAutoSize(true);
						$sheet->getColumnDimension('D')->setAutoSize(true);
						$sheet->getColumnDimension('E')->setAutoSize(true);
						
						break;
					
					default:
						break;
				}

				break;
			case 'clientes':

				// Definir propiedades
				$spreadsheet->getProperties()
					->setTitle("Lista de Clientes")
					->setSubject("Clientes al ".date("d-m-Y"))
					->setDescription("Datos exportados desde xpanel con PHPExcel.")
					->setKeywords("office 2007 openxml php xperticia")
					->setCategory("exportarClientes");

				switch ($_POST['accion']) {
					case 'exportarClientes':
						$titulo = ucfirst($_POST['ns']);
						if(isset($_POST['c']) && !empty($_POST['c'])){
							$titulo .= '_'.ucfirst($_POST['c']);
						}
						$titulo = strlen($titulo) > 31 ? substr($titulo, 0, 31) : $titulo;
						$sheet->setTitle($titulo);
						
						$sheet->setCellValue('A1', 'COD_USUARIO')
							->setCellValue('B1', 'TIPO_DOC')
							->setCellValue('C1', 'NRO_DOC')
							->setCellValue('D1', 'NOMBRE')
							->setCellValue('E1', 'APELLIDOS')
							->setCellValue('F1', 'EMAIL')
							->setCellValue('G1', 'TELEFONO')
							->setCellValue('H1', 'TELEFONO_MOVIL')
							->setCellValue('I1', 'CIUDAD')
							->setCellValue('J1', 'CATEGORIA_USUARIO')
							->setCellValue('K1', 'FECHA_INICIO_SERVICIO')
							->setCellValue('L1', 'FECHA_ALTA');

						$query = "SELECT 
								COD_USUARIO,
								TIPO_DOC,
								NRO_DOC,
								NOMBRE,
								APELLIDOS,
								EMAIL,
								TELEFONO,
								TELEFONO_MOVIL,
								CIUDAD,
								CATEGORIA_USUARIO,
								FECHA_INICIO_SERVICIO,
								FECHA_ALTA 
							FROM usuarios 
							WHERE 
								(tipo_usuario = 'Cliente') 
								and (id_sucursal = '".$_POST['s']."')
						";
						if(isset($_POST['c']) && !empty($_POST['c'])) { $query .= " AND (upper(categoria_usuario) = '".$_POST['c']."') "; }
						if((isset($_POST['tc']) && !empty($_POST['tc'])) && (isset($_POST['to']) && !empty($_POST['to']))) {
							$query .= " ORDER BY ".$_POST['tc']." ".$_POST['to'];
						} else {
							$query .= " ORDER BY cod_usuario ASC";
						}
						$registros = $conexion->Query($query);

						if($registros && ($registros->RecordCount() > 0)){
							$i = 2;
							while(!$registros->EOF) {
								$sheet->setCellValue('A'.$i, $registros->fields["COD_USUARIO"])
									->setCellValue('B'.$i, $registros->fields["TIPO_DOC"])
									->setCellValue('C'.$i, $registros->fields["NRO_DOC"])
									->setCellValue('D'.$i, str_replace("\"","'",stripslashes(($registros->fields["NOMBRE"]))))
									->setCellValue('E'.$i, str_replace("\"","'",stripslashes(($registros->fields["APELLIDOS"]))))
									->setCellValue('F'.$i, $registros->fields["EMAIL"])
									->setCellValue('G'.$i, $registros->fields["TELEFONO"])
									->setCellValue('H'.$i, $registros->fields["TELEFONO_MOVIL"])
									->setCellValue('I'.$i, $registros->fields["CIUDAD"])
									->setCellValue('J'.$i, $registros->fields["CATEGORIA_USUARIO"])
									->setCellValue('K'.$i, $registros->fields["FECHA_INICIO_SERVICIO"])
									->setCellValue('L'.$i, $registros->fields["FECHA_ALTA"]);
								$registros->MoveNext();	
								$i++;
							}
						}
						/*
						$sheet->getColumnDimension('A')->setAutoSize(true);
						$sheet->getColumnDimension('B')->setWidth(60);
						$sheet->getColumnDimension('C')->setAutoSize(true);
						$sheet->getColumnDimension('D')->setWidth(20);
						*/

						break;
					
					default:
						break;
				}

				break;
			
			
			default:
				break;
		}
		
		// Crear un objeto Writer
		switch ($_POST['tipo']) {
			case 'xls':
				$writer = new Xls($spreadsheet);
				break;
			case 'xlsx':
				$writer = new Xlsx($spreadsheet);
				break;
			
			default:
				break;
		}
		
		// Definir las cabeceras para descargar el archivo
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.ucfirst($_POST['menu'])."_al_".date("d-m-Y").'.'.$_POST['tipo'].'"');
		header('Cache-Control: max-age=0');
		
		// Guardar el archivo en el flujo de salida
		$writer->save('php://output');

	} else {
		echo json_encode(array("message" => 'Acceso denegado.'));
	}

?>