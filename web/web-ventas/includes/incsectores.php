<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'editsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.sectores SET sector = '".$_POST['des']."', descripcion = '".$_POST['obs']."' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(nroplano) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}else if ($_POST['tra'] == 'delsecv') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.sectores WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(nroplano) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'delsub') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.sectores WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.subproyectos WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}else if($_POST['tra'] == 'editsub'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.subproyectos SET subproyecto = '".$_POST['des']."', fecent = '".$_POST['fec']."'::date, obser = '".$_POST['obs']."' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
?>