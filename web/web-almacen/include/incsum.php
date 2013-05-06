<?php
session_start();
include ("../../datos/postgresHelper.php");

if ($_REQUEST['tmp'] == "s") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.tmpsuministro VALUES('".$_SESSION['dni-icr']."','".$_REQUEST['matid']."',".$_REQUEST['cant'].")");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if ($_REQUEST['tmp'] == "l") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("
							SELECT t.materialesid,m.matnom,m.matmed,m.matund,t.cantidad
							FROM almacen.tmpsuministro t INNER JOIN admin.materiales m
							ON t.materialesid=m.materialesid
							WHERE t.empdni LIKE '".$_SESSION['dni-icr']."'
							ORDER BY m.matnom ASC
							");
	if ($cn->num_rows($query) > 0) {
		echo "<table class='table table-bordered'>";
		echo "<caption><button class='btn btn-danger inline pull-left' onClick='deldet();'>Eliminar</button></caption>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Chk</th>";
		echo "<th>Item</th>";
		echo "<th>Codigo</th>";
		echo "<th>Descripcion</th>";
		echo "<th>Medida</th>";
		echo "<th>Cantidad</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td><input type='checkbox' name='matids' id='".$result['materialesid']."'></td>";
			echo "<td style='text-align: center; font-size: 12px;'>".$i++."</td>";
			echo "<td style='font-size: 12px;'>".$result['materialesid']."</td>";
			echo "<td style='font-size: 12px;'>".$result['matnom']."</td>";
			echo "<td style='font-size: 12px;'>".$result['matmed']."</td>";
			echo "<td style='text-align: center; font-size: 12px;'>".$result['cantidad']."</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}
	$cn->close($query);
}else if($_REQUEST['tmp'] == "d"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmpsuministro WHERE empdni LIKE '".$_SESSION['dni-icr']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if($_REQUEST['tmp'] == "g"){
	# grabando cabecera de suministro
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.sp_nuevosuministro()");
	$result = $cn->ExecuteNomQuery($query);
	$osid = TRIM($result[0]);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.suministro VALUES('".$osid."','".$_REQUEST['cboal']."','".$_REQUEST['dni']."',now(),'".$_REQUEST['fecr']."'::date,'40')");
	$cn->affected_rows($query);
	$cn->close($query);
	# grabando Detalle de Suministro
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT materialesid,cantidad FROM almacen.tmpsuministro WHERE empdni LIKE '".$_SESSION['dni-icr']."'");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO almacen.detsuministro VALUES('$osid','".$result['materialesid']."',".$result['cantidad'].")");
			$c->affected_rows($q);
			$c->close($q);
		}

		$c2 = new PostgreSQL();
		$q2 = $c2->consulta("DELETE FROM almacen.tmpsuministro WHERE empdni LIKE '".$_SESSION['dni-icr']."'");
		$c2->affected_rows($q2);
		$c2->close($q2);
	}
	$cn->close($query);
	echo "hecho";
}else if($_REQUEST['tmp'] == "dd"){
	$matids = explode(',', $_REQUEST['mids']);
	for ($i=0; $i < COUNT($matids); $i++) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("DELETE FROM almacen.tmpsuministro WHERE materialesid LIKE TRIM('".$matids[$i]."') AND empdni LIKE '".$_SESSION['dni-icr']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}
	echo "hecho";
}

?>