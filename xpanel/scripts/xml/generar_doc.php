<?php
	include("../../config.php");
	$content = "";

	if(isset($_GET['menu'])){
		//	--- Encabezado del Archivo	---	
		$content = "";
		$content .= "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
		$content .= "<?mso-application progid=\"Word.Document\"?>";
		$content .= "<w:wordDocument xmlns:aml=\"http://schemas.microsoft.com/aml/2001/core\" xmlns:wpc=\"http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas\" xmlns:dt=\"uuid:C2F41010-65B3-11d1-A29F-00AA00C14882\" xmlns:mc=\"http://schemas.openxmlformats.org/markup-compatibility/2006\" xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w10=\"urn:schemas-microsoft-com:office:word\" xmlns:w=\"http://schemas.microsoft.com/office/word/2003/wordml\" xmlns:wx=\"http://schemas.microsoft.com/office/word/2003/auxHint\" xmlns:wne=\"http://schemas.microsoft.com/office/word/2006/wordml\" xmlns:wsp=\"http://schemas.microsoft.com/office/word/2003/wordml/sp2\" xmlns:sl=\"http://schemas.microsoft.com/schemaLibrary/2003/core\" w:macrosPresent=\"no\" w:embeddedObjPresent=\"no\" w:ocxPresent=\"no\" xml:space=\"preserve\"><w:ignoreSubtree w:val=\"http://schemas.microsoft.com/office/word/2003/wordml/sp2\"/><o:DocumentProperties><o:Author>Walter</o:Author><o:LastAuthor>Walter</o:LastAuthor><o:Revision>6</o:Revision><o:TotalTime>7</o:TotalTime><o:Created>2012-06-11T12:00:00Z</o:Created><o:LastSaved>2012-06-11T13:20:00Z</o:LastSaved><o:Pages>1</o:Pages><o:Words>18</o:Words><o:Characters>99</o:Characters><o:Company>xperticia.com</o:Company><o:Lines>1</o:Lines><o:Paragraphs>1</o:Paragraphs><o:CharactersWithSpaces>116</o:CharactersWithSpaces><o:Version>14</o:Version></o:DocumentProperties><w:fonts><w:defaultFonts w:ascii=\"Calibri\" w:fareast=\"Calibri\" w:h-ansi=\"Calibri\" w:cs=\"Times New Roman\"/><w:font w:name=\"Times New Roman\"><w:panose-1 w:val=\"02020603050405020304\"/><w:charset w:val=\"00\"/><w:family w:val=\"Roman\"/><w:pitch w:val=\"variable\"/><w:sig w:usb-0=\"20002A87\" w:usb-1=\"80000000\" w:usb-2=\"00000008\" w:usb-3=\"00000000\" w:csb-0=\"000001FF\" w:csb-1=\"00000000\"/></w:font><w:font w:name=\"Cambria Math\"><w:panose-1 w:val=\"02040503050406030204\"/><w:charset w:val=\"01\"/><w:family w:val=\"Roman\"/><w:notTrueType/><w:pitch w:val=\"variable\"/></w:font><w:font w:name=\"Calibri\"><w:panose-1 w:val=\"020F0502020204030204\"/><w:charset w:val=\"00\"/><w:family w:val=\"Swiss\"/><w:pitch w:val=\"variable\"/><w:sig w:usb-0=\"E10002FF\" w:usb-1=\"4000ACFF\" w:usb-2=\"00000009\" w:usb-3=\"00000000\" w:csb-0=\"0000019F\" w:csb-1=\"00000000\"/></w:font></w:fonts><w:styles><w:versionOfBuiltInStylenames w:val=\"7\"/><w:latentStyles w:defLockedState=\"off\" w:latentStyleCount=\"267\"><w:lsdException w:name=\"Normal\"/><w:lsdException w:name=\"heading 1\"/><w:lsdException w:name=\"heading 2\"/><w:lsdException w:name=\"heading 3\"/><w:lsdException w:name=\"heading 4\"/><w:lsdException w:name=\"heading 5\"/><w:lsdException w:name=\"heading 6\"/><w:lsdException w:name=\"heading 7\"/><w:lsdException w:name=\"heading 8\"/><w:lsdException w:name=\"heading 9\"/><w:lsdException w:name=\"caption\"/><w:lsdException w:name=\"Title\"/><w:lsdException w:name=\"Subtitle\"/><w:lsdException w:name=\"Strong\"/><w:lsdException w:name=\"Emphasis\"/><w:lsdException w:name=\"No Spacing\"/><w:lsdException w:name=\"List Paragraph\"/><w:lsdException w:name=\"Quote\"/><w:lsdException w:name=\"Intense Quote\"/><w:lsdException w:name=\"Subtle Emphasis\"/><w:lsdException w:name=\"Intense Emphasis\"/><w:lsdException w:name=\"Subtle Reference\"/><w:lsdException w:name=\"Intense Reference\"/><w:lsdException w:name=\"Book Title\"/><w:lsdException w:name=\"TOC Heading\"/></w:latentStyles><w:style w:type=\"paragraph\" w:default=\"on\" w:styleId=\"Normal\"><w:name w:val=\"Normal\"/><w:rsid w:val=\"00015934\"/><w:pPr><w:spacing w:after=\"200\" w:line=\"276\" w:line-rule=\"auto\"/></w:pPr><w:rPr><wx:font wx:val=\"Calibri\"/><w:sz w:val=\"22\"/><w:sz-cs w:val=\"22\"/><w:lang w:val=\"ES-BO\" w:fareast=\"EN-US\" w:bidi=\"AR-SA\"/></w:rPr></w:style><w:style w:type=\"character\" w:default=\"on\" w:styleId=\"Fuentedeprrafopredeter\"><w:name w:val=\"Default Paragraph Font\"/><wx:uiName wx:val=\"Fuente de párrafo predeter.\"/></w:style><w:style w:type=\"table\" w:default=\"on\" w:styleId=\"Tablanormal\"><w:name w:val=\"Normal Table\"/><wx:uiName wx:val=\"Tabla normal\"/><w:rPr><wx:font wx:val=\"Calibri\"/><w:lang w:val=\"ES-BO\" w:fareast=\"ES-BO\" w:bidi=\"AR-SA\"/></w:rPr><w:tblPr><w:tblInd w:w=\"0\" w:type=\"dxa\"/><w:tblCellMar><w:top w:w=\"0\" w:type=\"dxa\"/><w:left w:w=\"108\" w:type=\"dxa\"/><w:bottom w:w=\"0\" w:type=\"dxa\"/><w:right w:w=\"108\" w:type=\"dxa\"/></w:tblCellMar></w:tblPr></w:style><w:style w:type=\"list\" w:default=\"on\" w:styleId=\"Sinlista\"><w:name w:val=\"No List\"/><wx:uiName wx:val=\"Sin lista\"/></w:style><w:style w:type=\"table\" w:styleId=\"Tablaconcuadrcula\"><w:name w:val=\"Table Grid\"/><wx:uiName wx:val=\"Tabla con cuadrícula\"/><w:basedOn w:val=\"Tablanormal\"/><w:rsid w:val=\"006E5A23\"/><w:rPr><wx:font wx:val=\"Calibri\"/></w:rPr><w:tblPr><w:tblInd w:w=\"0\" w:type=\"dxa\"/><w:tblBorders><w:top w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:left w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:bottom w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:right w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:insideH w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:insideV w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/></w:tblBorders><w:tblCellMar><w:top w:w=\"0\" w:type=\"dxa\"/><w:left w:w=\"108\" w:type=\"dxa\"/><w:bottom w:w=\"0\" w:type=\"dxa\"/><w:right w:w=\"108\" w:type=\"dxa\"/></w:tblCellMar></w:tblPr></w:style></w:styles><w:shapeDefaults><o:shapedefaults v:ext=\"edit\" spidmax=\"1026\"/><o:shapelayout v:ext=\"edit\"><o:idmap v:ext=\"edit\" data=\"1\"/></o:shapelayout></w:shapeDefaults><w:docPr><w:view w:val=\"print\"/><w:zoom w:percent=\"100\"/><w:doNotEmbedSystemFonts/><w:proofState w:spelling=\"clean\" w:grammar=\"clean\"/><w:defaultTabStop w:val=\"708\"/><w:hyphenationZone w:val=\"425\"/><w:punctuationKerning/><w:characterSpacingControl w:val=\"DontCompress\"/><w:optimizeForBrowser/><w:allowPNG/><w:validateAgainstSchema/><w:saveInvalidXML w:val=\"off\"/><w:ignoreMixedContent w:val=\"off\"/><w:alwaysShowPlaceholderText w:val=\"off\"/><w:compat><w:breakWrappedTables/><w:snapToGridInCell/><w:wrapTextWithPunct/><w:useAsianBreakRules/><w:dontGrowAutofit/></w:compat><wsp:rsids><wsp:rsidRoot wsp:val=\"006E5A23\"/><wsp:rsid wsp:val=\"00014FF7\"/><wsp:rsid wsp:val=\"00015934\"/><wsp:rsid wsp:val=\"000F68A2\"/><wsp:rsid wsp:val=\"00306760\"/><wsp:rsid wsp:val=\"003C1A90\"/><wsp:rsid wsp:val=\"00670868\"/><wsp:rsid wsp:val=\"006E5A23\"/><wsp:rsid wsp:val=\"009A480F\"/><wsp:rsid wsp:val=\"00AA2F71\"/><wsp:rsid wsp:val=\"00D1260B\"/><wsp:rsid wsp:val=\"00FB1F14\"/><wsp:rsid wsp:val=\"00FE04DD\"/></wsp:rsids></w:docPr>";
		$content .= "	<w:body>";
		$content .= "		<wx:sect>";

		//	--- Cuerpo del Archivo
		if($_GET['menu'] == "todo") {
			//	".htmlentities("Eventos de ".$sitio_nombre)."
			$content .= "			<w:p wsp:rsidR=\"00D1260B\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\"><w:pPr><w:rPr><w:b/><w:sz w:val=\"28\"/><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r wsp:rsidRPr=\"006E5A23\"><w:rPr><w:b/><w:sz w:val=\"28\"/><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode("Eventos de ".$sitio_nombre)."</w:t></w:r></w:p>";
		} else {
			//	".htmlentities($_GET['menu'])."
			$content .= "			<w:p wsp:rsidR=\"00D1260B\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\"><w:pPr><w:rPr><w:b/><w:sz w:val=\"28\"/><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r wsp:rsidRPr=\"006E5A23\"><w:rPr><w:b/><w:sz w:val=\"28\"/><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode($_GET['menu'])."</w:t></w:r></w:p>";
		}
		//	Inscripciones al ".date("d/m/Y")."
		$content .= "			<w:p wsp:rsidR=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\"><w:pPr><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>Inscripciones al ".date("d/m/Y")."</w:t></w:r></w:p>";
	
		if($_GET['menu'] == "todo") {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");			
		} else {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (evento = '".$_GET['menu']."') and (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");
		}
		$filas = $registros->RecordCount();
		if($filas > 0) {
			$content .= "			<w:tbl>";
			$content .= "				<w:tblPr><w:tblW w:w=\"0\" w:type=\"auto\"/><w:tblBorders><w:top w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:left w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:bottom w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:right w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:insideH w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/><w:insideV w:val=\"single\" w:sz=\"4\" wx:bdrwidth=\"10\" w:space=\"0\" w:color=\"auto\"/></w:tblBorders><w:tblLook w:val=\"04A0\"/></w:tblPr>";
			$content .= "				<w:tblGrid><w:gridCol w:w=\"2766\"/><w:gridCol w:w=\"2096\"/><w:gridCol w:w=\"2096\"/><w:gridCol w:w=\"2096\"/></w:tblGrid>";
			//	--- Nombre de los campos
			$content .= "				<w:tr wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidTr=\"006E5A23\">";
			$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2766\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\" wsp:rsidP=\"006E5A23\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:rPr><w:b/><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r wsp:rsidRPr=\"006E5A23\"><w:rPr><w:b/><w:lang w:val=\"ES\"/></w:rPr><w:t>#</w:t></w:r></w:p></w:tc>";
			for($i=0; $i<$registros->FieldCount(); $i++) {
				$campo = $registros->FetchField($i);
				//	".$campo->name."
				$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2096\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\" wsp:rsidP=\"006E5A23\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:rPr><w:b/><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r wsp:rsidRPr=\"006E5A23\"><w:rPr><w:b/><w:lang w:val=\"ES\"/></w:rPr><w:t>".$campo->name."</w:t></w:r></w:p></w:tc>";
			}
			$content .= "				</w:tr>";

			//	--- Valores de los campos
			$f = 1;
			while(!$registros->EOF) {
				if($f == 1) {
					$content .= "				<w:tr wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidTr=\"006E5A23\">";
				} else {
					$content .= "				<w:tr wsp:rsidR=\"009A480F\" wsp:rsidRPr=\"006E5A23\" wsp:rsidTr=\"006E5A23\">";
				}
				$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2766\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"009A480F\" wsp:rsidP=\"006E5A23\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>".$f."</w:t></w:r></w:p></w:tc>";
				for($i=0; $i<$registros->FieldCount(); $i++) {
					$campo = $registros->FetchField($i);
					$tipo_campo = $registros->MetaType($campo->type);
					switch($tipo_campo) {
						case "C":
							//	".utf8_encode($registros->fields[$campo->name])."
							$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2766\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"009A480F\" wsp:rsidP=\"006E5A23\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode($registros->fields[$campo->name])."</w:t></w:r></w:p></w:tc>";
							break;
						case "D":
							//	".utf8_encode(formato_fecha($registros->fields[$campo->name],3))."							
							$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2096\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"00FB1F14\" wsp:rsidP=\"00FE04DD\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:jc w:val=\"right\"/><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode(formato_fecha($registros->fields[$campo->name],3))."</w:t></w:r></w:p></w:tc>";
							break;
						case "I":
						case "N":
							//	".utf8_encode($registros->fields[$campo->name])."
							$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2096\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"00FB1F14\" wsp:rsidP=\"00FE04DD\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:jc w:val=\"right\"/><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode($registros->fields[$campo->name])."</w:t></w:r></w:p></w:tc>";
							break;
						default:
							//	".utf8_encode($registros->fields[$campo->name])."
							$content .= "					<w:tc><w:tcPr><w:tcW w:w=\"2766\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"auto\"/></w:tcPr><w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"009A480F\" wsp:rsidP=\"006E5A23\"><w:pPr><w:spacing w:after=\"0\" w:line=\"240\" w:line-rule=\"auto\"/><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr><w:r><w:rPr><w:lang w:val=\"ES\"/></w:rPr><w:t>".utf8_encode($registros->fields[$campo->name])."</w:t></w:r></w:p></w:tc>";
							break;
					}
				}
				$content .= "				</w:tr>";
				$f++;
				$registros->MoveNext();
			}
			$content .= "			</w:tbl>";
		} else {
			//	No hay elementos
		}
		$content .= "			<w:p wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidRDefault=\"006E5A23\"><w:pPr><w:rPr><w:lang w:val=\"ES\"/></w:rPr></w:pPr></w:p>";
		$content .= "			<w:sectPr wsp:rsidR=\"006E5A23\" wsp:rsidRPr=\"006E5A23\" wsp:rsidSect=\"00015934\"><w:pgSz w:w=\"12240\" w:h=\"15840\"/><w:pgMar w:top=\"1417\" w:right=\"1701\" w:bottom=\"1417\" w:left=\"1701\" w:header=\"708\" w:footer=\"708\" w:gutter=\"0\"/><w:cols w:space=\"708\"/><w:docGrid w:line-pitch=\"360\"/></w:sectPr>";
		$content .= "		</wx:sect>";
		$content .= "	</w:body>";
		$content .= "</w:wordDocument>";

		$buscar = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ";
		$remplazar = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn_";
		$nombre_evento = strtr($_GET['menu'],$buscar,$remplazar);
		
		//	Se envia el archivo al navegador
		header ("Content-type: application/msword");
		if($_GET['menu'] == "todo") {
			header ("Content-Disposition: attachment; filename=\"Inscripciones_al_".date("d-m-Y").".doc\"" ); 
		} else {
			header ("Content-Disposition: attachment; filename=\"Inscripciones_".$nombre_evento."_al_".date("d-m-Y").".doc\"" ); 
		}
		print($content);			
	} else {
		echo("<script type='text/javascript'>");
		echo("window.alert('Opción no valida: ".$_GET['menu'].".');");
		echo("window.history.back();");
		echo("</script>");		
	}
?>