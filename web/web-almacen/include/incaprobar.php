<?php

include ("../../datos/postgresHelper.php");

if (isset($_REQUEST['tipo'])) {

	if ($_REQUEST['tipo'] == "a") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '35' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "hecho";
	}elseif ($_REQUEST['tipo'] == "n") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '33' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "hecho";
	}
}

?>