<?php
include("../../datos/postgresHelper.php");

$nrocompra = $_REQUEST['nro'];
$cot = $_REQUEST['cot'];
$ruc = $_REQUEST['ruc'];
$matarr = json_decode($_REQUEST['matarr']);


for ($i=0; $i < count($matarr); $i++) { 
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT cantidad FROM logistica.detcotizacion WHERE nrocotizacion LIKE '$cot' AND rucproveedor LIKE '$ruc' AND materialesid LIKE '".$matarr[$i][0]."'");

	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("INSERT INTO logistica.detcompras VALUES('$nrocompra','".$matarr[$i][0]."',".$result['cantidad'].",".$matarr[$i][1].",".$result['cantidad'].",'0')");
			$cn2->affected_rows($query2);
			$cn2->close($query2);
		}
	}
	$cn->close($query);
}
echo "Se Guardo Correctamente!!!";

?>