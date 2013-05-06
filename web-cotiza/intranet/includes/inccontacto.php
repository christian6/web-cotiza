<?php
include ("../../datos/postgresHelper.php");
$t = $_REQUEST['t'];
if($t == "c"){
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM logistica.spnewcompra()");
if ($cn->num_rows($query)>0) {
	while($result = $cn->ExecuteNomQuery($query)){
		echo $result[0];
	}
}
$cn->close($query);
$cn = new PostgreSQL();
$nro = $_REQUEST['nro'];
$ruc = $_REQUEST['ruc'];
$query = $cn->consulta("SELECT contacto FROM logistica.cotizacioncli WHERE nrocotizacion LIKE '$nro' AND rucproveedor LIKE '$ruc' ORDER BY contacto DESC LIMIT 1 OFFSET 0");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
		echo $result['contacto'];
	}
}
$cn->close($query);
}
?>