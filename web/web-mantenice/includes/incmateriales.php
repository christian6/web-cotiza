<?php
include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == "del") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM admin.materiales WHERE TRIM(materialesid) LIKE TRIM('".$_REQUEST['matid']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

?>