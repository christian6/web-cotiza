<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'delsec') {
	$where = "";
	if ($_POST['op'] == 'se') {
		$where = " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '' AND sector NOT LIKE ''";
	}else if($_POST['op'] == 'su'){
		$where = " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) NOT LIKE ''";
	}else if($_POST['op'] == 'all'){
		$where = " proyectoid LIKE '".$_POST['pro']."'";
	}

	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.metproyecto WHERE ".$where);
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.proyectos SET esid = '17' WHERE proyectoid LIKE '".$_POST['pro']."'");
	$cn->affected_rows($query);
	$cn->close($query);

	echo 'hecho';
}

?>