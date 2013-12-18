<?php

include ('../datos/postgresHelper.php');

if ($_POST['tra'] == 'cbod') {
	$ldep = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT departamentoid, deparnom FROM admin.departamento WHERE paisid LIKE '".$_POST['pais']."' ORDER BY deparnom ASC ");
		if ( $cn->num_rows($query) > 0 ) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array( 'val' => $result['departamentoid'], 'name' => $result['deparnom'] );
			}
		}
		$cn->close($query);
		$ldep['status'] = 'success';
		$ldep['list'] = $res;
	} catch (Exception $e) {
		$ldep['status'] = 'fail';
	}
	echo json_encode($ldep);
}
if ($_POST['tra'] == 'cbop') {
	$lpro = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT provinciaid, provnom FROM admin.provincia WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' ORDER BY provnom ASC ");
		if ( $cn->num_rows($query) > 0 ) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array( 'val' => $result['provinciaid'], 'name' => $result['provnom'] );
			}
		}
		$cn->close($query);
		$lpro['status'] = 'success';
		$lpro['list'] = $res;
	} catch (Exception $e) {
		$lpro['status'] = 'fail';
	}
	echo json_encode($lpro);
}
if ($_POST['tra'] == 'cbodi') {
	$ldis = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT distritoid, distnom FROM admin.distrito WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' AND provinciaid LIKE '".$_POST['pro']."' ORDER BY distnom ASC ");
		if ( $cn->num_rows($query) > 0 ) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array( 'val' => $result['distritoid'], 'name' => $result['distnom'] );
			}
		}
		$cn->close($query);
		$ldis['status'] = 'success';
		$ldis['list'] = $res;
	} catch (Exception $e) {
		$ldis['status'] = 'fail';
	}
	echo json_encode($ldis);
}
?>