<?php
include("../../datos/postgresHelper.php");

$nro = $_POST['nro'];
$dni = $_POST['dni'];
$nom = $_POST['nom'];
$fec = $_POST['fec'];
$est = $_POST['est'];
$obser = $_POST['obser'];
$ruc = $_POST['ruc'];
$nro = $_POST['nroc'];
$c="";

//$mat = $_REQUEST['mat'];
//if (isset($nro) && isset($dni) && isset($nom) && isset($fec) && isset($est) && isset($obser) && isset($ruc)) {
	$nro = TRIM($nro);
	$ruc = explode(",", $ruc);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO logistica.cotizacion VALUES('$nro','$nom',now(),to_timestamp('$fec','yyyy-MM-dd'),'$obser','$est')");
	$cn->affected_rows($query);
	$cn->close($query);

	function generarCodigo() {
		$key = '';
		$pattern = '1234567890&!?#abcdefghijklmnopqrstuvwxyzABCDFGHYJKLMNOPQRSTUVWXYZ';
		$max = strlen($pattern)-1;
		for($i=0;$i < 8;$i++) $key .= $pattern{mt_rand(0,$max)};
			return $key;
	}
	/*
	for ($i=0; $i < count($ruc) ; $i++) {
		echo $ruc[$i] . "<br >";
	}
	*/
	$jsonary = json_decode($_POST['mat']);
	for ($i=0; $i < count($jsonary); $i++) {
		for ($j=0; $j < count($jsonary[$i]); $j++) {
			//echo $jsonary[$i][$j];
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT SUM(cantidad) AS cantidad FROM logistica.tmpcantpro WHERE materialesid LIKE TRIM('".$jsonary[$i][$j]."')");
			if ($cn->num_rows($query)>0) {
				while($result = $cn->ExecuteNomQuery($query)){
					$cnn = new PostgreSQL();
					$queryd = $cnn->consulta("INSERT INTO logistica.detcotizacion(nrocotizacion,rucproveedor,materialesid,cantidad) VALUES('$nro','".$ruc[$i]."','".$jsonary[$i][$j]."',".$result['cantidad'].")");
					$cnn->affected_rows($queryd);
					$cnn->close($queryd);
				}
			}
			$cn->close($query);
		}
		$c = "grabado";

		$cna = new PostgreSQL();
		$keys = "SC".generarCodigo();
		$querya = $cna->consulta("INSERT INTO logistica.autogenerado(rucproveedor,nrocotizacion,keygen) VALUES('".$ruc[$i]."','$nro','$keys')");
		$cna->affected_rows($querya);
		$cna->close($querya);
	}

	if ($c=="grabado") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("DELETE FROM logistica.tmpcantpro");
		$cn->affected_rows($query);
		$cn->close($query);

		$dir = "../../tmp/";

		$handle = opendir($dir);
		while ($file = readdir($handle))
		{
		if (is_file($dir.$file))
		{
			unlink($dir.$file);
		}
		}

		?>
		<center><label for="men">Se Guardo Correctamente...!</label></center>
		<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=http://190.41.246.91/web-cotiza/intranet/menu-int.php"> 
		<?
	}else{
		?>
		<center><label for="men">ERROR: No se Puedo Guardar!.</label></center>
		<?
	}

//}
?>