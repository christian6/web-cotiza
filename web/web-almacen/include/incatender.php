<?php
include ("../../datos/postgresHelper.php");

if ($_REQUEST['tipo'] == "a") {

	if ($_REQUEST['status']=="i") {
		# Cambiar el estado del Pedido al Almacen
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '37' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}else if($_REQUEST['status'] == "c"){
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '36' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}

	// cambiar el estado de los flag del detalle de pedido
	$ar = explode(",", $_REQUEST['matid']);
	for ($i=0; $i < count($ar); $i++) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.detpedidomat SET auto = '0' WHERE materialesid LIKE '".$ar[$i]."' AND nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
	}
	/* Actulizando el Stock en la base de datos  */
	$cant=0;
	$pen=0;
	$fin=0;
	$stock=0;
	$alid="";
	for ($i=0; $i < count($ar); $i++){
		// Recuperando la cantidad del material que se pide en la pedido de almacen
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT cantidad FROM almacen.detpedidomat WHERE nropedido LIKE '".$_REQUEST['nro']."' AND materialesid LIKE '".$ar[$i]."' AND auto LIKE '0'");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$cant = $result['cantidad'];
		}
		$cn->close($query);
		// Recuperando el id del almacen al que se ha solicitado el pedido
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT almacenid FROM almacen.pedido WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$alid = $result['almacenid'];
		}
		$cn->close($query);
		// Recuperando el stock actual del material
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT stock FROM almacen.inventario WHERE materialesid LIKE '".$ar[$i]."' AND almacenid LIKE '$alid' AND esid LIKE '23'");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$stock = $result['stock'];
		}
		$cn->close($query);
		// Recuperando la cantidad de pendiente del material
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT stockpen FROM almacen.inventario WHERE materialesid LIKE '".$ar[$i]."' AND almacenid LIKE '$alid' AND esid LIKE '23'");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$pen = $result['stockpen'];
		}
		$cn->close($query);
		if ($cant != 0 && $alid != "") {
			// Recuperando la cantidad de pendiente del material
			$fin = ($stock - $cant);
			$pen = ($pen - $cant);
			//echo 'material '.$ar[$i].' stock '.$stock.' Cantidad '.$cant.' stock ac'.$fin.' stock pen'.$pen;
			//echo "<br>";
			$cn = new PostgreSQL();
			if ($fin <= 0) {
				$query = $cn->consulta("UPDATE almacen.inventario SET stock = 0, stockpen = $pen WHERE materialesid LIKE '".$ar[$i]."' AND almacenid LIKE '$alid' AND esid LIKE '23'");
			}else{
				$query = $cn->consulta("UPDATE almacen.inventario SET stock = $fin, stockpen = $pen WHERE materialesid LIKE '".$ar[$i]."' AND almacenid LIKE '$alid' AND esid LIKE '23'");
			}
			$cn->affected_rows($query);
			$cn->close($query);
		}
	}
	/*
		Recuperando el numero de materiales que no tienen stock actual
	*/
	$nss = 0;
	$cn2 = new PostgreSQL();
	$query2 = $cn2->consulta("SELECT COUNT(flag) FROM almacen.detpedidomat WHERE nropedido LIKE '".$_REQUEST['nro']."' AND flag = '1'");
	if ($cn2->num_rows($query2)>0) {
		$res = $cn2->ExecuteNomQuery($query2);
		$nss = $res[0];
	}
	$cn2->close($query2);
	/*
	 Preguntamos si ya antes se ha actualizado el flag de sin stock
	 si el $nss es menor entonce sera la primera vez que estamos aqui
	 y actualizamos el flag a 1
	*/
	if ($nss == 0) {
	 	fnflag();
	}
	echo "hecho";
}

function fnflag()
{
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT materialesid FROM almacen.detpedidomat WHERE nropedido LIKE '".$_REQUEST['nro']."' AND auto LIKE '1'");
	if ($cn->num_rows($query)>0){
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("UPDATE almacen.detpedidomat SET flag = '1' WHERE materialesid LIKE '".$result['materialesid']."' AND nropedido LIKE '".$_REQUEST['nro']."'");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
}

?>