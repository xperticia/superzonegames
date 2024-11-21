<!doctype html>
<html lang="es">
    <head>
		<meta charset="ISO-8859-1">
        <title>formArchivosAdjuntos</title>
    </head>
    <body>
		<div class="modal-header">
			<a class="close" data-dismiss="modal" >&times;</a>
			<h3>Archivos adjuntos</h3>
		</div>
		<div class="modal-body">
			<?php
				require("../xpanel/config.php");
				//	obtengo los ARCHIVOS ADJUNTOS del contenido
				switch($_GET['menu']) {
					case "editorial":
					case "historia":
						$datos = $conexion->GetRow("SELECT * FROM contenidos WHERE (tipo_contenido = '".$_GET['menu']."') and (id_contenido = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT count(*) as CANTIDAD FROM contenido_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = contenido_posee_archivo.id_archivo WHERE (id_contenido = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY contenido_posee_archivo.id_archivo");
						break;
					case "noticias":
						$datos = $conexion->GetRow("SELECT * FROM noticias WHERE (id_noticia = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT archivos.* FROM noticia_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = noticia_posee_archivo.id_archivo WHERE (id_noticia = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY noticia_posee_archivo.id_archivo");
						break;
					case "articulos":
					case "documentos":
					case "publicaciones":
					case "videos":
						$datos = $conexion->GetRow("SELECT * FROM publicaciones WHERE (tipo_publicacion = '".$_GET['menu']."') and (id_publicacion = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT archivos.* FROM publicacion_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = publicacion_posee_archivo.id_archivo WHERE (id_publicacion = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY publicacion_posee_archivo.id_archivo");
						break;
					case "cursos":
						$datos = $conexion->GetRow("SELECT *,cursos.NOMBRE as TITULO FROM cursos WHERE (id_curso = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT archivos.* FROM curso_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = curso_posee_archivo.id_archivo WHERE (id_curso = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY curso_posee_archivo.id_archivo");
						break;
					case "asignaturas":
						$datos = $conexion->GetRow("SELECT * FROM asignaturas WHERE (id_asignatura = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT archivos.* FROM asignatura_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = asignatura_posee_archivo.id_archivo WHERE (id_asignatura = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY asignatura_posee_archivo.id_archivo");
						break;
					case "eventos":
					case "instituciones":
					case "carreras":
					case "archivos":
						$datos = $conexion->GetRow("SELECT * FROM instituciones WHERE (categoria = '".$_GET['menu']."') and (id_institucion = '".$_GET['id']."')");
						$registros = $conexion->Query("SELECT archivos.* FROM institucion_posee_archivo LEFT JOIN archivos ON archivos.id_archivo = institucion_posee_archivo.id_archivo WHERE (id_institucion = '".$_GET['id']."') and (tipo_archivo = 'Archivo') ORDER BY institucion_posee_archivo.id_archivo");
						break;
				}
					?>
			<p>El contenido <strong>&quot;<?php echo(htmlentities(stripslashes($datos["TITULO"]))); ?>&quot;</strong> posee <?php echo($registros->RecordCount()); ?> archivos disponibles:</p>
			<table class="table table-striped table-bordered table-condensed">
			<thead>
			<tr>
				<th>Archivo</th>
				<th width="50">Tipo</th>
				<th width="50">Tama&ntilde;o</th>
				<th width="50">Descargas</th>
			</tr>
			</thead>
			<tbody>
					<?php
					while(!$registros->EOF) {					
						?>
				<tr>
						<?php
						//	verifico si el archivo existe
						if(file_exists("../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"])) {
							//	muestro un enlace para ver o descargar el archivo, e informacion adicional
							$res = explode(".", $registros->fields["NOMBRE_ARCHIVO"]);
							$formato = $res[count($res)-1];
							//	verifico si el icono del archivo existe
							if(!file_exists("../images/archivos/".$formato.".gif")) { $formato = "file"; }
							$tamanio_archivo = ceil(filesize("../".$path_archivos."contenidos/".$registros->fields["NOMBRE_ARCHIVO"])/1024);
							?>
					<td><a href="<?php echo("html/descargarAdjuntos.php?ida=".$registros->fields["ID_ARCHIVO"]); ?>" title="Descargar Archivo" target="_blank"><?php if(($registros->fields["COMENTARIO"] == "") or ($registros->fields["COMENTARIO"] == null)) { echo($registros->fields["NOMBRE_ARCHIVO"]); } else { echo(htmlentities(stripslashes($registros->fields["COMENTARIO"]))); } ?></a></td>
					<td style="text-align: center;"><img src="images/archivos/<?php echo($formato); ?>.gif" align="absmiddle" border="0" alt="<?php echo($formato); ?>" style="margin: 0px; padding: 0px; border: none;float: none;" /> [<?php echo($formato); ?>]</td>
					<td style="text-align: right;"><?php echo($tamanio_archivo); ?> KB</td> 
					<td style="text-align: right;"><?php echo($registros->fields["DESCARGAS"]); ?></td>
							<?php
						} else { 
							//muestro el nombre del archivo
							?>
					<td><?php echo($registros->fields["NOMBRE_ARCHIVO"]); ?></td>
					<td colspan="3"><span style="color: #FF0000;">Archivo no encontrado</span></td>
							<?php
						}
						?>
				</tr>
						<?php
						$registros->MoveNext();
					} #while
					$registros->close();
					?>
			</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
		</div>
	</body>
</html>
