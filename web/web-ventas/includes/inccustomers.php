<?php

include ('../../datos/postgresHelper.php');

if ($_POST['tra'] == 'savedCus') {
	$ltra = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT COUNT(*) FROM admin.clientes WHERE ruccliente LIKE '".$_POST['ruc']."' ");
		if ($cn->num_rows($query) > 0) {
			$exists = $cn->ExecuteNomQuery($query);
		}
		$cn->close($query);
		if ($exists > 0) {
			$cn = new PostgreSQL();
			$query = $cn->consulta("UPDATE admin.clientes SET nombre='".$_POST['nom']."',abre='".$_POST['abre']."',direccion='".$_POST['dir']."',paisid='".$_POST['pais']."',departamentoid='".$_POST['dep']."',provinciaid='".$_POST['pro']."',distritoid='".$_POST['dis']."',telefono='".$_POST['tel']."',contacto='".$_POST['con']."' WHERE ruccliente LIKE '".$_POST['ruc']."'");
			$cn->affected_rows($query);
			$cn->close($query);
		}else{
			$cn = new PostgreSQL();
			$query = $cn->consulta("INSERT INTO admin.clientes VALUES('".$_POST['ruc']."','".$_POST['nom']."','".$_POST['abre']."','".$_POST['dir']."','".$_POST['pais']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."','".$_POST['tel']."','".$_POST['con']."','41')");
			$cn->affected_rows($query);
			$cn->close($query);
		}
		$ltra['status'] = 'success';
	} catch (Exception $e) {
		$ltra['status'] = 'fail';
	}
	echo json_encode($ltra);
}
if ($_POST['tra'] == 'delC') {
	$ltra = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE admin.clientes SET esid='42' WHERE ruccliente LIKE '".$_POST['ruc']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		$ltra['status'] = 'success';
	} catch (Exception $e) {
		$ltra['status'] = 'fail';
	}
	echo json_encode($ltra);
}

?>