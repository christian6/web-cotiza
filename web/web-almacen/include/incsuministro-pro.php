<?php

session_start();

include("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'addmatsum') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.tmpsuministro VALUES('".$_SESSION['dni-icr']."',
							'".$_POST['mat']."',".$_POST['cant'].");");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'listtmp') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT s.materialesid,m.matnom,m.matmed,m.matund,SUM(s.cantidad) as cantidad FROM almacen.tmpsuministro s
							INNER JOIN admin.materiales m 
							ON s.materialesid LIKE m.materialesid
							WHERE s.empdni LIKE '".$_SESSION['dni-icr']."'
							GROUP BY s.materialesid,m.matnom,m.matmed,m.matund");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr class='c-blue-light'>";
			echo "<td>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td id='tc'>".$result['matund']."</td>";
			echo "<td id='tc'>".$result['cantidad']."</td>";
			echo "<td id='tc'><button class='btn btn-mini btn-info'><i class='icon-edit'></i></button></td>";
			echo "<td id='tc'><button class='btn btn-mini btn-info' onClick='delmattmp(".$result['materialesid'].");'><i class='icon-remove'></i></button></td>";
			echo "</tr>";
		}
	}
	$cn->close($query);
	echo "|success";
}
if ($_POST['tra'] == 'deltmpmat') {
	$cn = new PostgreSQL();
	$query  = $cn->consulta("DELETE FROM almacen.tmpsuministro WHERE materialesid LIKE '".$_POST['mat']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}

?>