<!doctype html>
<html lang="es">
    <head>
		<meta charset="ISO-8859-1">
        <title>formAutor</title>
    </head>
    <body>
		<div class="modal-header">
			<a class="close" data-dismiss="modal" ><img src="images/cerrar.png" alt="&times;" title="" /></a>
			<h3>Datos del autor</h3>
		</div>
		<div class="modal-body">
			<?php
				require("../xpanel/config.php");
				$registros = $conexion->query("SELECT usuarios.*,paises.PAIS FROM usuarios LEFT JOIN paises ON paises.codigo_pais = usuarios.codigo_pais WHERE id_usuario = '".$_GET['id']."'");
				while(!$registros->EOF) {
					$titulo_sitio = stripslashes($registros->fields["NOMBRE_COMPLETO"])." | ".$sitio_nombre;

					$nombre_completo_imagenoriginal = $path_imagenes."fotos/"."fotouser.jpg";
					if($registros->fields["FOTO"] != null) {
						$nombre_completo_imagenoriginal = $path_imagenes."fotos/".$registros->fields["FOTO"];
						//verifico si el archivo existe
						if(file_exists($nombre_completo_imagenoriginal)) {
							//obtengo el nombre y extension del archivo small
							$res = explode(".", $registros->fields["FOTO"]);
							$nombre = $res[0];
							$extension = $res[count($res)-1];
							//obtengo la ubicacion de la imagen small
							$nombre_completo_imagensmall = $path_imagenes."fotos/".$nombre."_small.".$extension;
							if(file_exists($nombre_completo_imagensmall)) {
								$nombre_completo_imagenoriginal = $nombre_completo_imagensmall;
							}
						}
					} #if
					?>
			<h3><?php echo(htmlentities(stripslashes($registros->fields["NOMBRE_COMPLETO"]))); ?></h3>
			<table>
			<tbody>
			<tr>
				<td rowspan="3" width="150"><img src="<?php echo($nombre_completo_imagenoriginal); ?>" border="0" alt=""></td>
				<th>Profesi&oacute;n</th>
				<td><?php echo($registros->fields["PROFESION"]); ?></td>
			</tr>
			<tr>
				<th>Nivel Acad&eacute;mico</th>
				<td><?php echo($registros->fields["NIVEL_ACADEMICO"]); ?></td>
			</tr>
			<tr>
				<th>Ciudad / Pais</th>
				<td><?php echo($registros->fields["CIUDAD"]." (".$registros->fields["PAIS"].")"); ?></td>
			</tr>
			</tbody>
			</table>
					<?php
					$contenido_mostrar .= "<table cellspacing=\"0\" cellpadding=\"0\"><tbody>";
					if($nombre_completo_imagensmall != null) {
						$contenido_mostrar .= "<tr><td rowspan='4' width='150'><img src='".$nombre_completo_imagenoriginal."' border='0' /></td>";
					} else {
						$contenido_mostrar .= "<tr><td rowspan='4'><img src='".$nombre_completo_imagensmall."' border='0' /></td>";
					}
					$contenido_mostrar .= "<th>Profesi&oacute;n</th><td>".$registros->fields["PROFESION"]."</td></tr>";
					$contenido_mostrar .= "<tr><th>Nivel Acad&eacute;mico</th><td>".$registros->fields["NIVEL_ACADEMICO"]."</td></tr>";
					$paises = $temp->GetRow("SELECT * FROM paises WHERE codigo_pais = '".$registros->fields["CODIGO_PAIS"]."'");
					$contenido_mostrar .= "<tr><th>Ciudad / Pais</th><td>".$registros->fields["CIUDAD"]." (".$paises["PAIS"].")"."</td></tr>";
					$contenido_mostrar .= "<tr><td colspan='2'>".htmlentities(stripslashes($registros->fields["COMENTARIOS"]))."</td></tr>";
					$contenido_mostrar .= "<tbody></table>";
					?>
			<hr />
			<h4>Contenidos publicados</h4>
					<?php
					$contenido_mostrar .= "";
					//	obtengo las secciones del usuario
					$registros2 = $temp->query("SELECT * FROM contenidos WHERE (activo = 1) and (id_usuario = '".$registros->fields["ID_USUARIO"]."') GROUP BY categoria ORDER BY categoria");
					if($registros2->RecordCount() > 0) {
						while(!$registros2->EOF) {
							?>
			<h5><?php echo(htmlentities(stripslashes($registros2->fields["CATEGORIA"]))); ?></h5>
			<ul>
							<?php
							$registros3 = $temp2->query("SELECT * FROM contenidos WHERE (activo = 1) and (categoria = '".$registros2->fields["CATEGORIA"]."') and (id_usuario = '".$registros->fields["ID_USUARIO"]."') ORDER BY fecha_alta DESC");
							while(!$registros3->EOF) {
								?>
				<li><a href="./?menu=<?php echo($registros3->fields["TIPO_CONTENIDO"]); ?>&id=<?php echo($registros3->fields["ID_CONTENIDO"]); ?>"><?php echo(htmlentities(stripslashes($registros3->fields["TITULO"]))); ?></a></li>
								<?php
								$registros3->MoveNext();
							}
							$registros3->close();
							?>
			</ul>
							<?php
							$registros2->MoveNext();
						}
						$registros2->close();
					}
					$registros->MoveNext();
				}
				$registros->close();
			?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
		</div>
	</body>
</html>
