<?php 
include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == 'tbl') {
?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Item</th>
			<th>Codigo</th>
			<th>Descripción</th>
			<th>Medida</th>
			<th>Unidad</th>
			<th>Stock</th>
		</tr>
	</thead>
	<tbody>
	<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT i.materialesid,m.matnom,m.matmed,m.matund,i.stock FROM
									admin.materiales m INNER JOIN almacen.inventario i
									ON m.materialesid LIKE i.materialesid
									WHERE i.stock > 0 AND anio LIKE '".(intVal(date('Y')) - 1)."'");
				if ($cn->num_rows($query) > 0) {
					$i = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td>".$i++."</td>";
						echo "<td>".$result['materialesid']."</td>";
						echo "<td>".$result['matnom']."</td>";
						echo "<td>".$result['matmed']."</td>";
						echo "<td>".$result['matund']."</td>";
						echo "<td>".$result['stock']."</td>";
						echo "</tr>";
					}
				}
			$cn->close($query);	
	?>
	</tbody>
</table>
<?php
}elseif ($_REQUEST['tra'] == 'addstock') {
	$i = 0;
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT i.materialesid,i.almacenid,i.precio,i.stockmin,i.rucproveedor,i.stock FROM
							admin.materiales m INNER JOIN almacen.inventario i
							ON m.materialesid LIKE i.materialesid
							WHERE i.stock > 0 AND anio LIKE '".(intVal(date('Y')) - 1)."'");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c =  new PostgreSQL();
			$q = $c->consulta("SELECT COUNT(*) FROM almacen.inventario WHERE materialesid LIKE '".$result['materialesid']."' AND anio LIKE '".date('Y')."'");
			$r = $c->ExecuteNomQuery($q);
			$c->close($q);
			if ($r[0] > 0) {
				$i++;
			}else{
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO almacen.inventario(materialesid, almacenid, precio, stockmin, stock, stockpen, stockdev, 
            						anio, fecing, nrocompra, rucproveedor, esid)
    							VALUES ('".$result['materialesid']."', '".$result['almacenid']."', ".$result['precio'].",0, ".$result['stockmin'].",
    							".$result['stock'].", 0, 0, '".date('Y')."',now()::date, '', '".$result['rucproveedor']."', '23')");
				$c->affected_rows($q);
				$c->close($q);
				$ck = new PostgreSQL();
				$qk = $ck->consulta("INSERT INTO almacen.entradasalida(tdoc,nrodoc,almacenid,materialesid,stkact,cantent,cantsal,saldo,precio,flag)
    								VALUES ('II','0000000000000000','".$result['almacenid']."','".$result['materialesid']."',".$result['stock'].", 0, 0, ".$result['stock'].", ".$result['precio'].",'1');");
				$ck->affected_rows($qk);
				$ck->close($ck);
			}
		}
	}
	$cn->close($query);	
	echo "hecho".$i;
}elseif ($_REQUEST['tra'] == "addlist") {
	$i = 0;
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT i.materialesid,i.almacenid,i.precio,i.stockmin,i.rucproveedor,i.stock FROM
							admin.materiales m INNER JOIN almacen.inventario i
							ON m.materialesid LIKE i.materialesid
							WHERE anio LIKE '".(intVal(date('Y')) - 1)."'");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c =  new PostgreSQL();
			$q = $c->consulta("SELECT COUNT(*) FROM almacen.inventario WHERE materialesid LIKE '".$result['materialesid']."' AND anio LIKE '".date('Y')."'");
			$r = $c->ExecuteNomQuery($q);
			$c->close($q);
			if ($r[0] > 0) {
				$i++;
			}else{
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO almacen.inventario(materialesid, almacenid, precio, stockmin, stock, stockpen, stockdev, 
            						anio, fecing, nrocompra, rucproveedor, esid)
    							VALUES ('".$result['materialesid']."', '".$result['almacenid']."', ".$result['precio'].",0, ".$result['stockmin'].",
    							".$result['stock'].", 0, 0, '".date('Y')."',now()::date, '', '".$result['rucproveedor']."', '23')");
				$c->affected_rows($q);
				$c->close($q);
				$ck = new PostgreSQL();
				$qk = $ck->consulta("INSERT INTO almacen.entradasalida(tdoc,nrodoc,almacenid,materialesid,stkact,cantent,cantsal,saldo,precio,flag)
    								VALUES ('II','0000000000000000','".$result['almacenid']."','".$result['materialesid']."',".$result['stock'].", 0, 0, ".$result['stock'].", ".$result['precio'].",'1');");
				
				$ck->affected_rows($qk);
				$ck->close($ck);
			}
		}
	}
	$cn->close($query);	
	echo "hecho".$i;
}elseif ($_REQUEST['tra'] == "addmat") {
	$i = 0;
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT materialesid FROM admin.materiales");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c =  new PostgreSQL();
			$q = $c->consulta("SELECT COUNT(*) FROM almacen.inventario WHERE materialesid LIKE '".$result['materialesid']."' AND anio LIKE '".date('Y')."'");
			$r = $c->ExecuteNomQuery($q);
			$c->close($q);
			if ($r[0] > 0) {
				$i++;
			}else{
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO almacen.inventario(materialesid, almacenid, precio, stockmin, stock, stockpen, stockdev, 
            						anio, fecing, nrocompra, rucproveedor, esid)
    							VALUES ('".$result['materialesid']."', '".$_REQUEST['alid']."', 0,0 , ".$_REQUEST['stk'].",
    							0, 0, 0, '".date('Y')."',now()::date, '', '10704928501', '23')");
				$c->affected_rows($q);
				$c->close($q);
				$ck = new PostgreSQL();
				$qk = $ck->consulta("INSERT INTO almacen.entradasalida(tdoc,nrodoc,almacenid,materialesid,stkact,cantent,cantsal,saldo,precio,flag)
    								VALUES ('II','0000000000000000','".$_REQUEST['alid']."','".$result['materialesid']."',0, 0, 0, 0, 0,'1');");
				
				$ck->affected_rows($qk);
				$ck->close($ck);
			}
		}
	}
	$cn->close($query);	
	echo "hecho".$i;
}

?>