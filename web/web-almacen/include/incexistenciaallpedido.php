<?php

include ("../../datos/postgresHelper.php");

if (isset($_REQUEST['alid'])) {
	# Obteniendo Nro de la Orden de Suministro
	$nros = "";
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.sp_nuevosuministro()");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$nros = $result[0];
	}
	$cn->close($query);

	# Guardando la cabecera de la Order de Suministro
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.suministro(nrosuministro,almacenid,empdni,fecreq,esid) VALUES('$nros','".$_REQUEST['alid']."','".$_REQUEST['empid']."',to_date('".$_REQUEST['fec']."','dd-mm-yyyy'),'40')");
	$cn->affected_rows($query);
	$cn->close($query);

	#Guardando el Detalle de la Orden de Suministro
	$arrmat = explode(",", $_REQUEST['matid']);

	for ($i=0; $i < count($arrmat); $i++) {

		$cn = new PostgreSQL();
		if ($_REQUEST['es']== '35') {
			$query = $cn->consulta("
				SELECT SUM(d.cantidad) as cantidad FROM almacen.pedido p INNER JOIN almacen.detpedidomat d
				ON p.nropedido LIKE d.nropedido
				INNER JOIN almacen.inventario i
				ON d.materialesid LIKE i.materialesid
				INNER JOIN admin.materiales m
				ON i.materialesid LIKE m.materialesid AND d.materialesid=m.materialesid
				INNER JOIN admin.almacenes a
				ON a.almacenid = p.almacenid
				WHERE p.almacenid LIKE '".$_REQUEST['alid']."' AND p.esid LIKE '".$_REQUEST['es']."' AND d.materialesid LIKE TRIM('$arrmat[$i]')
				");
		}else if($_REQUEST['es'] == '37'){
		$query = $cn->consulta("
				SELECT SUM(d.cantidad) as cantidad FROM almacen.pedido p INNER JOIN almacen.detpedidomat d
				ON p.nropedido LIKE d.nropedido
				INNER JOIN almacen.inventario i
				ON d.materialesid LIKE i.materialesid
				INNER JOIN admin.materiales m
				ON i.materialesid LIKE m.materialesid AND d.materialesid=m.materialesid
				INNER JOIN admin.almacenes a
				ON a.almacenid = p.almacenid
				WHERE p.almacenid LIKE '".$_REQUEST['alid']."' AND p.esid LIKE '".$_REQUEST['es']."' AND d.auto LIKE '1' AND d.materialesid LIKE TRIM('$arrmat[$i]')
				");
		}
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("INSERT INTO almacen.detsuministro VALUES('$nros','$arrmat[$i]',$result[0])");
			$cn2->affected_rows($query2);
			$cn2->close($query2);
		}
		$cn->close($query);
		
	}
	echo $nros;
}

?>