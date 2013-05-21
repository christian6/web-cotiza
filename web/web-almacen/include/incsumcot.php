<?php
session_start();

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == "det") {

	$cn = new PostgreSQL();
	$sum = "";
	$cot = "";
	$query = $cn->consulta("SELECT nrosuministro,nrocotizacion FROM almacen.sumcot WHERE nrosuministro LIKE '".$_REQUEST['nsum']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$sum = $result['nrosuministro'];
		$cot = $result['nrocotizacion'];
	}
	$cn->close($query);

	if ($sum != $_REQUEST['nsum']) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT * FROM logistica.spnuevacotizacion()");
		$result = $cn->ExecuteNomQuery($query);
		$cn->close($query);
		$nrocot = TRIM($result[0]);

		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO logistica.cotizacion VALUES('$nrocot','".$_SESSION['dni-icr']."', now(), '".$_REQUEST['fec']."'::date, TRIM('".$_REQUEST['obser']."'),'14')");
		$cn->affected_rows($query);
		$cn->close($query);

		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.sumcot VALUES(TRIM('".$_REQUEST['nsum']."'),TRIM('$nrocot'))");
		$cn->affected_rows($query);
		$cn->close($query);
	}else{
		$nrocot = $cot;
	}

	echo $nrocot;
}else if($_REQUEST['tra'] == "tbl"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("
							SELECT s.nrosuministro,s.materialesid,m.matnom,m.matmed,m.matund,s.cantidad
							FROM almacen.detsuministro s INNER JOIN admin.materiales m
							ON s.materialesid = m.materialesid
							WHERE s.nrosuministro LIKE '".$_REQUEST['nsum']."'
							GROUP BY s.nrosuministro,s.materialesid,m.matnom,m.matmed,m.matund,s.cantidad
							ORDER BY m.matnom ASC
						");
	if ($cn->num_rows($query) > 0) {
		echo "<table class='table table-bordered table-hover'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>Check</th>";
		echo "<th>Item</th>";
		echo "<th>Codigo</th>";
		echo "<th>Descripción</th>";
		echo "<th>Medida</th>";
		echo "<th>Unidad</th>";
		echo "<th>Cantidad</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		echo "</tbody>";
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td style='text-align: center;'><input type='checkbox' name='matids' id='".$result['materialesid']."' value='".$result['cantidad']."'></td>";
			echo "<td style='text-align: center;'>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td style='text-align: center;'>".$result['matund']."</td>";
			echo "<td style='text-align: center;'>".$result['cantidad']."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	$cn->close($query);
}elseif ($_REQUEST['tra'] == "savedet") {
	$nrocot = TRIM($_REQUEST['nrocot']);
	//echo $nrocot;

	$armatid = explode(",", $_REQUEST['matid']);
	$arcant = explode(",", $_REQUEST['cants']);

	for ($i=0; $i < count($armatid) ; $i++) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO logistica.detcotizacion VALUES('$nrocot','".$_REQUEST['cbopro']."','".$armatid[$i]."',".$arcant[$i].",0)");
		$cn->close($query);
	}

	function generarCodigo() {
		$key = '';
		$pattern = '1234567890&!?#abcdefghijklmnopqrstuvwxyzABCDFGHYJKLMNOPQRSTUVWXYZ';
		$max = strlen($pattern)-1;
		for($i=0;$i < 8;$i++) $key .= $pattern{mt_rand(0,$max)};
			return $key;
	}

	$cna = new PostgreSQL();
	$keys = "SC".generarCodigo();
	$querya = $cna->consulta("INSERT INTO logistica.autogenerado(rucproveedor,nrocotizacion,keygen) VALUES('".$_REQUEST['cbopro']."','$nrocot','$keys')");
	$cna->affected_rows($querya);
	$cna->close($querya);

	echo "hecho";

}elseif ($_REQUEST['tra'] == "statussum") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE almacen.suministro SET esid = '48' WHERE nrosuministro LIKE '".$_REQUEST['nsum']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

?>