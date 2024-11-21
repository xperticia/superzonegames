<?php
	include("../../config.php");
	$content = "";

	if(isset($_GET['menu'])){
		//	--- Encabezado del Archivo	---	
		$content .= "<?xml version=\"1.0\"?>\n";
		$content .= "<?mso-application progid=\"Excel.Sheet\"?>\n";
		$content .= "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
		$content .= " xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
		$content .= " xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
		$content .= " xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
		$content .= " xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n";
		$content .= " <DocumentProperties xmlns=\"urn:schemas-microsoft-com:office:office\">\n";
		$content .= "  <Title>Plantilla de Excel</Title>\n";
		$content .= "  <Author>Walter E. Irahola</Author>\n";
		$content .= "  <LastAuthor>Walter</LastAuthor>\n";
		$content .= "  <Created>2012-06-10T01:00:33Z</Created>\n";
		$content .= "  <Company>XPERTICIA.com</Company>\n";
		$content .= "  <Version>14.00</Version>\n";
		$content .= " </DocumentProperties>\n";
		$content .= " <OfficeDocumentSettings xmlns=\"urn:schemas-microsoft-com:office:office\">\n";
		$content .= "  <AllowPNG/>\n";
		$content .= " </OfficeDocumentSettings>\n";
		$content .= " <ExcelWorkbook xmlns=\"urn:schemas-microsoft-com:office:excel\">\n";
		$content .= "  <WindowHeight>6105</WindowHeight>\n";
		$content .= "  <WindowWidth>10425</WindowWidth>\n";
		$content .= "  <WindowTopX>600</WindowTopX>\n";
		$content .= "  <WindowTopY>90</WindowTopY>\n";
		$content .= "  <ProtectStructure>False</ProtectStructure>\n";
		$content .= "  <ProtectWindows>False</ProtectWindows>\n";
		$content .= " </ExcelWorkbook>\n";
		$content .= " <Styles>\n";
		$content .= "  <Style ss:ID=\"Default\" ss:Name=\"Normal\">\n";
		$content .= "   <Alignment ss:Vertical=\"Bottom\"/>\n";
		$content .= "   <Borders/>\n";
		$content .= "   <Font ss:FontName=\"Arial\"/>\n";
		$content .= "   <Interior/>\n";
		$content .= "   <NumberFormat/>\n";
		$content .= "   <Protection/>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s65\">\n";
		$content .= "   <Font ss:FontName=\"Arial\" x:Family=\"Swiss\" ss:Size=\"12\"/>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s68\">\n";
		$content .= "   <Font ss:FontName=\"Arial\" x:Family=\"Swiss\" ss:Size=\"14\" ss:Bold=\"1\"/>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s78\">\n";
		$content .= "   <Borders>\n";
		$content .= "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "   </Borders>\n";
		$content .= "   <Font ss:FontName=\"Arial\" x:Family=\"Swiss\" ss:Color=\"#FFFFFF\" ss:Bold=\"1\"/>\n";
		$content .= "   <Interior ss:Color=\"#841921\" ss:Pattern=\"Solid\"/>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s79\">\n";
		$content .= "   <Borders>\n";
		$content .= "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "   </Borders>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s80\">\n";
		$content .= "   <Borders>\n";
		$content .= "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "   </Borders>\n";
		$content .= "   <NumberFormat ss:Format=\"Short Date\"/>\n";
		$content .= "  </Style>\n";
		$content .= "  <Style ss:ID=\"s81\">\n";
		$content .= "   <Borders>\n";
		$content .= "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\"/>\n";
		$content .= "   </Borders>\n";
		$content .= "   <Font ss:FontName=\"Arial\" x:Family=\"Swiss\"/>\n";
		$content .= "   <NumberFormat ss:Format=\"Fixed\"/>\n";
		$content .= "  </Style>\n";
		$content .= " </Styles>\n";
	
		if($_GET['menu'] == "todo") {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");			
		} else {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (evento = '".$_GET['menu']."') and (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");
		}

		//	--- Cuerpo del Archivo
		$content .= " <Worksheet ss:Name=\"Hoja1\">\n";
		$content .= "  <Table x:FullColumns=\"1\" x:FullRows=\"1\" ss:DefaultColumnWidth=\"60\">\n";
		$content .= "   <Row ss:Height=\"18\">\n";
		if($_GET['menu'] == "todo") {
			$content .= "    <Cell ss:StyleID=\"s68\"><Data ss:Type=\"String\">".htmlentities("Eventos de ".$sitio_nombre)."</Data></Cell>\n";
		} else {
			$content .= "    <Cell ss:StyleID=\"s68\"><Data ss:Type=\"String\">".htmlentities($_GET['menu'])."</Data></Cell>\n";
		}
		$content .= "   </Row>\n";
		$content .= "   <Row ss:Height=\"15\">\n";
		$content .= "    <Cell ss:StyleID=\"s65\"><Data ss:Type=\"String\">Inscripciones al ".date("d/m/Y")."</Data></Cell>\n";
		$content .= "   </Row>\n";

		$filas = $registros->RecordCount();
		if($filas > 0) {
			//	--- Nombre de los campos
			$content .= "   <Row ss:Index=\"4\">\n";
			for($i=0; $i<$registros->FieldCount(); $i++) {
				$campo = $registros->FetchField($i);
				$content .= "    <Cell ss:StyleID=\"s78\"><Data ss:Type=\"String\">".$campo->name."</Data></Cell>\n";
			}
			$content .= "   </Row>\n";
			//	--- Valores de los campos
			while(!$registros->EOF) {
				$content .= "   <Row>\n";
				for($i=0; $i<$registros->FieldCount(); $i++) {
					$campo = $registros->FetchField($i);
					$tipo_campo = $registros->MetaType($campo->type);
					switch($tipo_campo) {
						case "C":
							$content .= "    <Cell ss:StyleID=\"s79\"><Data ss:Type=\"String\">".htmlentities(strip_tags(stripslashes($registros->fields[$campo->name])))."</Data></Cell>\n";
							break;
						case "D":
							$content .= "    <Cell ss:StyleID=\"s80\"><Data ss:Type=\"DateTime\">".$registros->fields[$campo->name]."</Data></Cell>\n";
							break;
						case "I":
							$content .= "    <Cell ss:StyleID=\"s79\"><Data ss:Type=\"Number\">".$registros->fields[$campo->name]."</Data></Cell>\n";
							break;
						case "N":
							$content .= "    <Cell ss:StyleID=\"s81\"><Data ss:Type=\"Number\">".$registros->fields[$campo->name]."</Data></Cell>\n";
							break;
//						case "N":
//							$content .= "    <Cell><Data ss:Type=\"String\">".htmlentities(str_replace(".",",",$registros->fields[$campo->name]))."</Data></Cell>\n";
//							break;
						default:						
							$content .= "    <Cell><Data ss:Type=\"String\">".htmlentities(strip_tags(stripslashes($registros->fields[$campo->name])))."</Data></Cell>\n";
							break;
					}
				}
				$content .= "   </Row>\n";
				$registros->MoveNext();
			}
		} else {
			$content .= "   <Row ss:Index=\"4\">\n";
			$content .= "    <Cell><Data ss:Type=\"String\">No hay elementos</Data></Cell>\n";
			$content .= "   </Row>\n";
		}

		$content .= "  </Table>\n";
		$content .= "  <WorksheetOptions xmlns=\"urn:schemas-microsoft-com:office:excel\">\n";
		$content .= "   <PageSetup>\n";
		$content .= "    <Header x:Margin=\"0\"/>\n";
		$content .= "    <Footer x:Margin=\"0\"/>\n";
		$content .= "    <PageMargins x:Bottom=\"0.984251969\" x:Left=\"0.78740157499999996\"\n";
		$content .= "     x:Right=\"0.78740157499999996\" x:Top=\"0.984251969\"/>\n";
		$content .= "   </PageSetup>\n";
		$content .= "   <Selected/>\n";
		$content .= "   <Panes>\n";
		$content .= "    <Pane>\n";
		$content .= "     <Number>3</Number>\n";
		$content .= "     <ActiveRow>1</ActiveRow>\n";
		$content .= "    </Pane>\n";
		$content .= "   </Panes>\n";
		$content .= "   <ProtectObjects>False</ProtectObjects>\n";
		$content .= "   <ProtectScenarios>False</ProtectScenarios>\n";
		$content .= "  </WorksheetOptions>\n";
		$content .= " </Worksheet>\n";

		$content .= "</Workbook>";

		$buscar = "¿¡¬√ƒ≈‡·‚„‰Â“”‘’÷ÿÚÛÙıˆ¯»… ÀËÈÍÎ«ÁÃÕŒœÏÌÓÔŸ⁄€‹˘˙˚¸ˇ—Ò ";
		$remplazar = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn_";
		$nombre_evento = strtr($_GET['menu'],$buscar,$remplazar);
		
		//	Se envia el archivo al navegador
		header ("Content-type: application/x-msexcel");
		if($_GET['menu'] == "todo") {
			header ("Content-Disposition: attachment; filename=\"Inscripciones_al_".date("d-m-Y").".xls\"" ); 
		} else {
			header ("Content-Disposition: attachment; filename=\"Inscripciones_".$nombre_evento."_al_".date("d-m-Y").".xls\"" ); 
		}
		print($content);			
	} else {
		echo("<script type='text/javascript'>");
		echo("window.alert('OpciÛn no valida: ".$_GET['menu'].".');");
		echo("window.history.back();");
		echo("</script>");		
	}
?>