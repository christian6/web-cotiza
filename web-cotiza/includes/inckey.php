<?php

include ("../datos/postgresHelper.php");

$nro = $_REQUEST['nro'];
$ruc = $_REQUEST['ruc'];
$key = $_REQUEST['key'];

if (isset($nro) && isset($ruc) && isset($key)) {
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT COUNT(*) FROM logistica.autogenerado WHERE rucproveedor LIKE TRIM('$ruc') AND nrocotizacion LIKE TRIM ('$nro') AND keygen LIKE TRIM('$key')");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
		if ($result[0]==1) {
			echo "passed";
		}else{
			echo "ERROR: KEY INVALIDO!";		
		}
		
	}

}
}
?>