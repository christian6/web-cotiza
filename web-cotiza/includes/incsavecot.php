<?php
include ("../datos/postgresHelper.php");

$nro = $_REQUEST['nro'];
$dni = $_REQUEST['dni'];
$nom = $_REQUEST['nom'];
$fec = $_REQUEST['fec'];
$est = $_REQUEST['est'];
$obser = $_REQUEST['obser'];
$pro = $_REQUEST['pro'];
$c = "";
if (isset($nro) && isset($dni) && isset($nom) && isset($fec) && isset($est) && isset($obser) && isset($pro)) {
//echo strlen($nro);
$nro = trim($nro);
$array = explode(",", $pro);
$cn = new PostgreSQL();
$query = $cn->consulta("INSERT INTO logistica.cotizacion VALUES('$nro','$nom',now(),to_timestamp('$fec','yyyy-MM-dd'),'Ninguna.','$est')");
$cn->affected_rows($query);
$cn->close($query);
////hasta aqui llegamos
function generarCodigo() {
$key = '';
$pattern = '1234567890$%&@!?#abcdefghijklmnopqrstuvwxyzABCDFGHYJKLMNOPQRSTUVWXYZ';
$max = strlen($pattern)-1;
for($i=0;$i < 8;$i++) $key .= $pattern{mt_rand(0,$max)};
return $key;
}

///
for ($i=0; $i < count($array); $i++) { 
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM logistica.tmpcotiza");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cnn = new PostgreSQL();
			$queryd = $cnn->consulta("INSERT INTO logistica.detcotizacion(nrocotizacion,rucproveedor,materialesid,cantidad) VALUES('$nro','".$array[$i]."','".$result[0]."','".$result[1]."')");
			$cnn->affected_rows($queryd);
			$cnn->close($queryd);
		}
		$c = "grabado";
	}
$cn->close($query);

$cna = new PostgreSQL();
$keys = "SC".generarCodigo();
$querya = $cna->consulta("INSERT INTO logistica.autogenerado(rucproveedor,nrocotizacion,keygen) VALUES('".$array[$i]."','$nro','$keys')");
$cna->affected_rows($querya);
$cna->close($querya);

}

if ($c=="grabado") {
$cn = new PostgreSQL();
$query = $cn->consulta("DELETE FROM logistica.tmpcotiza");
$cn->affected_rows($query);
$cn->close($query);
?>
<center><label for="men">Se Guardo Correctamente...!</label></center>
<META HTTP-EQUIV="REFRESH" CONTENT="3;URL=cotizacion.php"> 
<?
}else{
?>
<center><label for="men">ERROR: No se Puedo Guardar!.</label></center>
<?
}

}
?>
