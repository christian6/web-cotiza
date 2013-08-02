<?php
include ("../../datos/postgresHelper.php");
$t = $_REQUEST['t'];
if ($t == "n") {
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM logistica.spnewcompra()");
if ($cn->num_rows($query)>0) {
	while($result = $cn->ExecuteNomQuery($query)){
		echo $result[0];
	}
}
$cn->close($query);
}
?>