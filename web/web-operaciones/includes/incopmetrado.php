<?php
session_start(); 

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
if ($_POST['tra'] == 'sectorok') {
	/*$sql = "SELECT proyectoid,TRIM(subproyectoid) as subproyectoid,TRIM(sector) as sector,
			TRIM(materialesid) as materialesid, SUM(cant) as cant FROM operaciones.matmetrado 
			WHERE proyectoid LIKE '".$_POST['pro']."' AND 
			TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
			AND TRIM(sector) LIKE '".$_POST['sec']."' 
			GROUP BY proyectoid,subproyectoid,sector,materialesid";

	//echo $sql;

	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO operaciones.metproyecto 
							VALUES('".$result['proyectoid']."','".$result['subproyectoid']."','".$result['sector']."',
							'".$result['materialesid']."',".$result['cant'].",'1');");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);*/

	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.sectores SET esid = '60' WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(nroplano) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);

	echo "success";
}
if ($_POST['tra'] == 'save') {
	$cn = new PostgreSQL();
	$sql = "SELECT COUNT(*) FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['cod']."'";
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0 ) {
		$result = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ((int) $result[0] == 0) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO operaciones.tmpmodificaciones VALUES('".$_POST['pro']."','".$_POST['sub']."',
				'".$_POST['sec']."','".$_POST['cod']."',".$_POST['cant'].",'1')");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "success";
	}else{
		echo "exists";
	}
}
if ($_POST['tra'] == 'delmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['cod']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'editmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE operaciones.tmpmodificaciones SET cant = ".$_POST['can']."  WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['mid']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'confirm'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO operaciones.modifysec(proyectoid,subproyectoid,sector,obs,status) VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['obs']."','0');");
	$cn->affected_rows($query);
	$cn->close();
	$cn = new PostgreSQL();
	$cn->auditoria('OPERACIONES_MODIFYSEC','INSERT',$_SESSION['dni-icr'],$_POST['pro'].' '.$_POST['sub'].' '.$_POST['sec'].' Modify',' Se ha modificado el sector '.$_POST['sec']);
	echo "success";
}
?>