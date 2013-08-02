<?php
include ("../datos/postgresHelper.php");

$matid = $_REQUEST['matid'];
$price = $_REQUEST['price'];
$ruc = $_REQUEST['ruc'];
$nro = $_REQUEST['nro'];
if(isset($matid) && isset($price) && isset($ruc) && isset($nro)){
$cn = new PostgreSQL();
$query = $cn->consulta("UPDATE logistica.detcotizacion SET precio = $price WHERE nrocotizacion='$nro' AND rucproveedor='$ruc' AND materialesid='$matid' ");
$cn->affected_rows($query);
$cn->close($query);
}
?>
