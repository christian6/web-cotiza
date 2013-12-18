<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'save') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM admin.empleados WHERE empdni LIKE '".$_POST['dni']."'");
	if ($cn->num_rows($query) > 0) {
		$res  = $cn->ExecuteNomQuery($query);
	}
	if ($res[0] > 0) {
		$c = new PostgreSQL();
		$q = $c->consulta("UPDATE admin.empleados SET empnom='".$_POST['nom']."',empape='".$_POST['ape']."',empfnc='".$_POST['fec']."'::date,paisid='".$_POST['pais']."',departamentoid='".$_POST['dep']."',provinciaid='".$_POST['pro']."',distritoid='".$_POST['dis']."',empdir='".$_POST['dir']."',emptel='".$_POST['tel']."',cargoid=".$_POST['car'].", esid='19' WHERE empdni LIKE '".$_POST['dni']."'");
		$c->affected_rows($q);
		$c->close($q);
	}else{
		$c = new PostgreSQL();
		$q = $c->consulta("INSERT INTO admin.empleados VALUES('".$_POST['dni']."','".$_POST['nom']."','".$_POST['ape']."',now(),'".$_POST['fec']."'::date,'".$_POST['pais']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."','".$_POST['dir']."','".$_POST['tel']."',".$_POST['car'].",'19');");
		$c->affected_rows($q);
		$c->close($q);
	}
	$cn->close();
	echo "success";
}
if ($_POST['tra'] == 'delete') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE admin.empleados SET esid = '20' WHERE empdni LIKE '".$_POST['dni']."' ");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'clo') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*),usere FROM admin.loginemp WHERE empdni LIKE '".$_POST['emp']."' GROUP BY usere");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ($result[0] > 0) {
		echo "success|exists|".$_POST['emp']."|".$result[1];
	}else{
		echo "success|noexists|".$_POST['emp'];
	}
}
if ($_POST['tra'] == 'slog') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM admin.loginemp WHERE empdni LIKE '".$_POST['dni']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ($result[0] > 0) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE admin.loginemp SET pwde = MD5('".$_POST['pwd']."') WHERE empdni LIKE '".$_POST['dni']."' ");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "success";
	}else{
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.loginemp VALUES('".$_POST['dni']."','".$_POST['user']."',MD5('".$_POST['pwd']."'); ");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "success";
	}
}
?>