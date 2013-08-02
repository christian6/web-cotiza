<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'apro') {
	$cn = new PostgreSQL();
	$sql = "SELECT DISTINCT d.materialesid,SUM(d.cant) as cant
			FROM ventas.matmetrado d INNER JOIN admin.materiales m
			ON d.materialesid LIKE m.materialesid
			INNER JOIN ventas.proyectos p
			ON d.proyectoid LIKE p.proyectoid ";
	if ($_POST['sub'] == "") {
		$sql .= "WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.sector) LIKE TRIM('".$_POST['sec']."') GROUP BY d.materialesid";
	}elseif ($_POST['sub'] != "") {
		$sql .= "WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_POST['sec']."') GROUP BY d.materialesid";
	}
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			if ($_POST['sub'] == "") {
				$sql = "INSERT INTO operaciones.matmetrado VALUES('".$_POST['pro']."','','".$_POST['sec']."','".$result['materialesid']."',".$result['cant'].",'1');";
			}else if($_POST['sub'] != ""){
				$sql = "INSERT INTO operaciones.matmetrado VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$result['materialesid']."',".$result['cant'].",'1');";
			}
			$q = $c->consulta($sql);
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
	echo "hecho";
}

?>