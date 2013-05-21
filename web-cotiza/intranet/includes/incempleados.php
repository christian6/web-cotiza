<?php
include("../../datos/postgresHelper.php");


if ($_REQUEST['t']=="b") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM admin.empleados WHERE TRIM(empdni) LIKE TRIM('".$_REQUEST['cod']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "completado";
}

?>