<?php

include("../../datos/postgresHelper.php");

if (isset($_REQUEST['ruc'])){

	if ($_REQUEST['tra']=="d") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("DELETE FROM admin.clientes  WHERE TRIM(ruccliente) LIKE TRIM('".$_REQUEST['ruc']."')");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "complete";
	}

}

?>