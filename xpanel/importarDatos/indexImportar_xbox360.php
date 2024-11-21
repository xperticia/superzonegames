<?php
ini_set('max_execution_time','8000');
require('../config.php'); 

$contenido = "";
$archivo = fopen('importarStock_xbox360.csv', 'r');
$tamano = filesize('importarStock_xbox360.csv');
$contenido = fread($archivo, $tamano);

?>
<table border="1">
<thead>
<tr>
	<th>#</th>
	<th>csv</th>
	<th>ID</th>
	<th>codigo</th>
	<th>nombre</th>
	<th>genero</th>
	<th>idioma</th>
	<th>stock</th>
</tr>
</thead>
<tbody>
<?php
$cantidad_registros = 1;
$linea = explode("\n",$contenido);
for($i=0; $i<count($linea); $i++) {
	if((count(trim($linea[$i])) > 0) and (trim($linea[$i]) != "")) {
		?>
<tr>
	<td><?php echo($cantidad_registros); ?></td>
		<?php
		$campos = explode(";",trim($linea[$i]));
		?>
	<td><?php echo(trim($campos[0]."-".$campos[1]." [".$campos[5]."]")); ?></td>
		<?php
		$registros = $conexion->Query("SELECT * FROM productos WHERE (codigo = '".$campos[0]."-X360')");
		if($registros->RecordCount() > 0) {
			// existe el registro
			while(!$registros->EOF) {
				?>
	<td><?php echo($registros->fields["ID_PRODUCTO"]); ?></td>
	<td><?php echo($registros->fields["CODIGO"]); ?></td>
	<td><?php echo($registros->fields["NOMBRE"]); ?></td>
	<td><?php echo($registros->fields["GENERO"]); ?></td>
	<td><?php echo($registros->fields["IDIOMA"]); ?></td>
				<?php
				// actualizo el NOMBRE
				$conexion->Execute("UPDATE productos SET nombre = '".strtoupper($campos[1])."' WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
				$conexion->Execute("UPDATE sucursal_posee_productos SET stock = '".$campos[5]."' WHERE (id_sucursal = '1') and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
				// obtengo su stock
				$registrosStock = $conexion->Query("SELECT * FROM sucursal_posee_productos WHERE (id_sucursal = '1') and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
				if($registrosStock->RecordCount() > 0) {
					while(!$registrosStock->EOF) {
						?>
	<td><?php echo($registrosStock->fields["STOCK"]); ?></td>
						<?php
						$registrosStock->MoveNext();
					}
				} else {
					// inserto el nuevo STOCK
					$conexion->Execute("INSERT sucursal_posee_productos (ID_SUCURSAL,ID_PRODUCTO,STOCK) VALUES('1','".$registros->fields["ID_PRODUCTO"]."','".$campos[5]."')");
					?>
	<td><?php echo("STOCK: ".$campos[4]); ?></td>
					<?php
				}
				$registros->MoveNext();
			}
		} else {
			// insertar el nuevo registro
			$conexion->Execute("INSERT productos (ID_PRODUCTO,CODIGO,CATEGORIA,NOMBRE,GENERO,IDIOMA,ACTIVO,CLICK) VALUES(null,'".$campos[0]."-X360"."','X360','".strtoupper($campos[1])."','".$campos[3]."','".$campos[4]."','1','0')");
			$id_registro = $conexion->Insert_ID();
			// inserto el nuevo STOCK
			$conexion->Execute("INSERT sucursal_posee_productos (ID_SUCURSAL,ID_PRODUCTO,STOCK) VALUES('1','".$id_registro."','".$campos[5]."')");
			?>
	<td>NUEVO</td>
	<td><?php echo($campos[0]."-X360"); ?></td>
	<td><?php echo($campos[1]); ?></td>
	<td><?php echo($campos[3]); ?></td>
	<td><?php echo($campos[4]); ?></td>
	<td><?php echo($campos[5]); ?></td>
			<?php			
		}
?>
</tr>
<?php
		$cantidad_registros++;
	}
}
?>
</tbody>
</table>
<?php
@fclose();

?>
