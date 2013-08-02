<?php
include("../../datos/postgresHelper.php");

if (isset($_REQUEST['nro']) && isset($_REQUEST['ruc'])) {
	if ($_REQUEST['t'] == 'a') {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE logistica.autogenerado SET esid = '45' WHERE nrocotizacion LIKE '".$_REQUEST['nro']."' AND rucproveedor LIKE '".$_REQUEST['ruc']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "ok";
	}else if($_REQUEST['t'] == 'c'){
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE logistica.autogenerado SET esid = '03' WHERE nrocotizacion LIKE '".$_REQUEST['nro']."' AND rucproveedor LIKE '".$_REQUEST['ruc']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}
}

?>