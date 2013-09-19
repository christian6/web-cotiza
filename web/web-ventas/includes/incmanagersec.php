<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'apmsec') {
	// Eliminando sector de metproyecto
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.metproyecto WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'
						 AND TRIM(sector) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	// Insertando el sector de tmpmodificaciones a metproyecto
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO operaciones.metproyecto SELECT * FROM operaciones.tmpmodificaciones
							WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	// update de status de operaciones.modifysec
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE operaciones.modifysec SET status = '2' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	// delete from operaciones.tmpmodificaciones
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}

?>