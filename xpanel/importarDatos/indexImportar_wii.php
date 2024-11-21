<?php
ini_set('max_execution_time','8000');
require('../config.php'); 

$contenido = "";
$archivo = fopen('importarStock_wii.csv', 'r');
$tamano = filesize('importarStock_wii.csv');
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
	<td><?php echo(trim($campos[0]."-".$campos[1]." [".$campos[3]."]")); ?></td>
		<?php
		$registros = $conexion->Query("SELECT * FROM productos WHERE (codigo = '".$campos[0]."-WII')");
		if($registros->RecordCount() > 0) {
			// existe el registro
			while(!$registros->EOF) {
				?>
	<td><?php echo($registros->fields["ID_PRODUCTO"]); ?></td>
	<td><?php echo($registros->fields["CODIGO"]); ?></td>
	<td><?php echo($registros->fields["NOMBRE"]); ?></td>
				<?php
				// actualizo el NOMBRE y su STOCK
				$conexion->Execute("UPDATE productos SET nombre = '".strtoupper($campos[1])."' WHERE (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
				$conexion->Execute("UPDATE sucursal_posee_productos SET stock = '".$campos[3]."' WHERE (id_sucursal = '1') and (id_producto = '".$registros->fields["ID_PRODUCTO"]."')");
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
					$conexion->Execute("INSERT sucursal_posee_productos (ID_SUCURSAL,ID_PRODUCTO,STOCK) VALUES('1','".$registros->fields["ID_PRODUCTO"]."','".$campos[3]."')");
					?>
	<td><?php echo("STOCK: ".$campos[3]); ?></td>
					<?php
				}
				$registros->MoveNext();
			}
		} else {
			// insertar el nuevo registro
			$conexion->Execute("INSERT productos (ID_PRODUCTO,CODIGO,CATEGORIA,NOMBRE,ACTIVO,CLICK) VALUES(null,'".$campos[0]."-WII"."','Wii','".$campos[1]."','1','0')");
			$id_registro = $conexion->Insert_ID();
			// inserto el nuevo STOCK
			$conexion->Execute("INSERT sucursal_posee_productos (ID_SUCURSAL,ID_PRODUCTO,STOCK) VALUES('1','".$id_registro."','".$campos[3]."')");
			?>
	<td>NUEVO</td>
	<td><?php echo($campos[0]."-WII"); ?></td>
	<td><?php echo($campos[1]); ?></td>
	<td><?php echo($campos[3]); ?></td>
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