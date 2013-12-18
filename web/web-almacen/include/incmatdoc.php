<?php

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tipo']=="a") {
	if ($_REQUEST['doc']=="g") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.guiaremision SET esid = '47' WHERE nroguia LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		unableFlag($_REQUEST['nro']);
		echo "hecho";
	}else if($_REQUEST['doc']=="n"){
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.notasalida SET esid = '02' WHERE nronsalida LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		unableFlag($_REQUEST['nro']);
		echo "hecho";
	}

}

function unableFlag($nro)
{
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE almacen.entradasalida SET flag = '0' WHERE TRIM(nrodoc) LIKE '".$nro."' AND tdoc LIKE 'GUIA' ");
	$cn->affected_rows($query);
	$cn->close($query);
}

?>