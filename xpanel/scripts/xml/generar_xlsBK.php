<?php 
	//Autor: Walter E. Irahola
	//E-mail: wirahola@hotmail.com
	//Proyecto: generar reportes en Microsoft Excel

	//	creo la variables para acceder a las base de datos
	include("../../config.php");
	$content = "";
	//	variable utilizada para saber si debe exportar contenido
	$exportar = true;

	//	verifico si existe menu
	if(isset($_GET['menu'])){
		if($_GET['menu'] == "todo") {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");			
		} else {
			$registros = $conexion->Query("SELECT ID_INSCRIPCION,TIPO_DOC,NRO_DOC,NOMBRE_COMPLETO,DOMICILIO,TELEFONO,LOCALIDAD,PROVINCIA,PAIS,CODIGO_POSTAL,EMAIL,PROFESION,OCUPACION,LUGAR_LABORAL,CARGO_LABORAL,DIRECCION_LABORAL,TELEFONO_LABORAL,EMAIL_LABORAL,EVENTO,FECHA_ALTA,TIPO_ASISTENCIA,TEMA_PONENCIA,TIPO_PAGO,MONTO_PAGO,IF(PAGADO=1,'SI','NO') as PAGADO,ESTADO FROM inscripciones LEFT JOIN paises ON paises.codigo_pais = inscripciones.codigo_pais WHERE (evento = '".$_GET['menu']."') and (year(fecha_alta) = ".$_GET['anio'].") ORDER BY fecha_alta DESC");
		}
		//	--- Encabezado del Archivo	---	
		$content .= "<div id='Classeur1_16681' align=left x:publishsource='Excel'>";
		$content .= "<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse'>";
		$content .= "<tr>";
		if($_GET['menu'] == "todo") {
			$content .= "<th class=xl2216681 nowrap colspan=4><h1>Eventos de MundoArchivÌstico.com</h1></th>";
		} else {
			$content .= "<th class=xl2216681 nowrap colspan=4><h1>".$_GET['menu']."</h1></th>";
		}
		$content .= "</tr>";
		$content .= "<tr>";
		$content .= "<th class=xl2216681 nowrap colspan=4><h3>Inscripciones al ".date("d/m/Y")." </h3></th>";
		$content .= "</tr>";
		$content .= "</table>";
		$content .= "</div>";	
		
		if($exportar == true) {
			//	--- Cuerpo del Archivo
			$content .= "<p></p>";
			$filas = $registros->RecordCount();
			if($filas > 0) {
				//	--- Nombre de los campos
				$content .= "<div id='Classeur1_16681' align=center x:publishsource='Excel'>";
				$content .= "<table x:str border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse'>";
				$content .= "<tr style='background: #841921;color: #ffffff;'>";
				for($i=0; $i<$registros->FieldCount(); $i++) {
					$campo = $registros->FetchField($i);
					$content .= "<th class=xl2216681 nowrap>".$campo->name."</th>";
				}
				$content .= "</tr>";
				//	--- Valores de los campos
				while(!$registros->EOF) {
					$content .= "<tr>";
					for($i=0; $i<$registros->FieldCount(); $i++) {
						$campo = $registros->FetchField($i);
						$tipo_campo = $registros->MetaType($campo->type);
						switch($tipo_campo) {
							case "N":
								$content .= "<td class=xl2216681 nowrap>".str_replace(".",",",$registros->fields[$campo->name])."</td>";
								break;
							default:						
								$content .= "<td class=xl2216681 nowrap>".strip_tags(stripslashes($registros->fields[$campo->name]))."</td>";
								break;
						}
					}
					$content .= "</tr>";
					$registros->MoveNext();
				}
				$content .= "</table>";
				$content .= "</div>";
			} else {
				$content .= "<b>No hay elementos</b>";
			}
	
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
			echo("window.alert('No hay registros para exportar.');");
			echo("window.history.back();");
			echo("</script>");		
		}
	} else {
		echo("<script type='text/javascript'>");
		echo("window.alert('OpciÛn no valida: ".$_GET['menu'].".');");
		echo("window.history.back();");
		echo("</script>");		
	}
?>