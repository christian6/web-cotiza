<?php
include("../../datos/postgresHelper.php");
$t = $_REQUEST['t'];
if ($t=="u") {
	$cod = $_REQUEST['cod'];
	$nom = $_REQUEST['nom'];
	$sim = $_REQUEST['sim'];
	$opt = $_REQUEST['est'];
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE admin.moneda SET nomdes='$nom',simbolo='$sim',esid='$opt' WHERE monedaid='$cod'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "completado";
}elseif ($t=="d") {
	$cod = $_REQUEST['cod'];
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE  FROM admin.moneda WHERE monedaid='$cod'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "completado";
}
?>