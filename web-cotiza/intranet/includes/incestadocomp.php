<?php
include("../../datos/postgresHelper.php");

if (isset($_REQUEST['nro'])) {
	if ($_REQUEST['t'] == 'a') {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE logistica.compras SET esid = '09' WHERE nrocompra LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "ok";
	}else if($_REQUEST['t'] == 'c'){
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE logistica.compras SET esid = '09' WHERE nrocompra LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}
}

?>