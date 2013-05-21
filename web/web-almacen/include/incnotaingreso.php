<?php
session_start();

include ("../../datos/postgresHelper.php");

if (isset($_REQUEST['tra'])) {
	if ($_REQUEST['tra'] == 'addc') {
		$nroning = "";
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT * FROM almacen.sp_nuevonotaingreso()");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
			$nroning = $result[0];
		}
		$_SESSION['nroning'] = $nroning;
		$cn->close($query);
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.notaingreso (nroningreso, almacenid, nrocompra, nroguia, nrocotizacion, nrofactura, 
								motivo, obser, empdni, recdni, insdni, vbdni, esid)
								VALUES('$nroning','".$_REQUEST['alid']."','".$_REQUEST['ncom']."','".$_REQUEST['ng']."',
								'".$_REQUEST['nc']."','".$_REQUEST['nf']."','".$_REQUEST['mot']."','".$_REQUEST['obser']."',
								'".$_SESSION['dni-icr']."','".$_REQUEST['rec']."','".$_REQUEST['ins']."','".$_REQUEST['vb']."','51')");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "hecho";
	}elseif ($_REQUEST['tra'] == 'addd') {
		if ($_SESSION['nroning'] != "") {

			$nroning = $_SESSION['nroning'];
			// Consultando si el material existe en el inventario del periodo presente
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT COUNT(*) FROM almacen.inventario WHERE materialesid LIKE '".$_REQUEST['matid']."' AND anio LIKE '".date("Y")."'");
			if ($cn->num_rows($query) > 0) {
				$result = $cn->ExecuteNomQuery($query);
				if (intval($result[0]) <= 0) {
					$c = new PostgreSQL();
					$q = $c->consulta("INSERT INTO almacen.inventario(materialesid, almacenid, precio, stockmin, stock, stockpen, stockdev, 
										anio, fecing, nrocompra, rucproveedor, esid)
										VALUES('".$_REQUEST['matid']."','".$_REQUEST['alid']."',".$_REQUEST['pre'].",10,0,0,0,'".date("Y")."',now()::date,'".$_REQUEST['ncom']."','".$_REQUEST['rucp']."','23')");
					$c->affected_rows($query);
					$c->close($q);
				}
			}
			$cn->close($query);

			// Guardando Detalle de Nota de Ingreso
			$cn = new PostgreSQL();
			$query = $cn->consulta("INSERT INTO almacen.detnotaingreso VALUES('$nroning','".$_REQUEST['matid']."',".$_REQUEST['cantidad'].",'0')");
			$cn->affected_rows($query);
			$cn->close($query);

			///Consultando datos para kingresos
			$stkact = 0;
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT stock FROM almacen.inventario WHERE  materialesid LIKE '".$_REQUEST['matid']."' AND almacenid LIKE '".$_REQUEST['alid']."' AND anio LIKE '".date('Y')."' LIMIT 1 OFFSET 0");
			if ($cn->num_rows($query) > 0) {
				$result = $cn->ExecuteNomQuery($query);
				$stkact = $result[0];
			}
			$cn->close($query);

			$sal = (intval($stkact) + intval($_REQUEST['cantidad']));
			/// Guardando kardex historico ingresos
			///// En el flag el valor 1 es activado 0 desctivados para ser consultados
			$cn = new PostgreSQL();
			$query = $cn->consulta("INSERT INTO almacen.entradasalida(tdoc,nrodoc,almacenid,materialesid,stkact,cantent,cantsal,saldo,precio,flag)
    								VALUES ('NI','".$nroning."','".$_REQUEST['alid']."','".$_REQUEST['matid']."',".$stkact.", 0,".$_REQUEST['cantidad'].", ".$sal.", ".$_REQUEST['pre'].",'1');");
    					
			$cn->affected_rows($query);
			$cn->close($query);
			//Actualizando el la cantidad y flag del detalle de la orden de compras 1 cantidad incompleto 2 cantidad completo  'cv' -> cantidad verdadera
			if ($_REQUEST['es'] == "i") {
				#$nca = (intval($_REQUEST['cv']));
				$cn = new PostgreSQL();
				$query = $cn->consulta("UPDATE logistica.detcompras SET cantidad = ".$_REQUEST['cv'].", flag = '1' WHERE nrocompra LIKE '".$_REQUEST['ncom']."' AND materialesid LIKE '".$_REQUEST['matid']."' ");	
				$cn->affected_rows($query);
				$cn->close($query);
			}elseif($_REQUEST['es'] == "c"){
				$cn = new PostgreSQL();
				$query = $cn->consulta("UPDATE logistica.detcompras SET flag = '2' WHERE nrocompra LIKE '".$_REQUEST['ncom']."' AND materialesid LIKE '".$_REQUEST['matid']."' ");	
				$cn->affected_rows($query);
				$cn->close($query);
			}
			//Actualizar Stock de Inventario
			$cn = new PostgreSQL();
			$query = $cn->consulta("UPDATE almacen.inventario SET precio=".$_REQUEST['pre'].",stock=$sal,fecing = now()::date, nrocompra='".$_REQUEST['ncom']."',rucproveedor='".$_REQUEST['rucp']."' WHERE anio LIKE '".date("Y")."' AND materialesid LIKE '".$_REQUEST['matid']."'");
			$cn->affected_rows($query);
			$cn->close($query);
		}
	}elseif ($_REQUEST['tra'] == 'condi') {
		if ($_SESSION['nroning'] != "") {
			$n = -1;
			$c = -1;
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT COUNT(*) FROM logistica.detcompras WHERE nrocompra LIKE '".$_REQUEST['ncom']."'");	
			if ($cn->num_rows($query) > 0) {
				$result = $cn->ExecuteNomQuery($query);
				$n = intval($result[0]);
			}
			$cn->close($query);
			///
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT COUNT(*) FROM logistica.detcompras WHERE nrocompra LIKE '".$_REQUEST['ncom']."' And flag LIKE '2'");
			if ($cn->num_rows($query) > 0) {
				$result = $cn->ExecuteNomQuery($query);
				$c = intval($result[0]);
			}
			$cn->close($query);

			if ($n >= 0 && $c >= 0) {
				if ($c < $n) {
					// Actualizando el estado id de cabecera orden de compras
					$cn = new PostgreSQL();
					$query = $cn->consulta("UPDATE logistica.compras SET esid = '50' WHERE nrocompra LIKE '".$_REQUEST['ncom']."' ");
					$cn->affected_rows($query);
					$cn->close($query);
				}elseif ($n == $c) {
					// Actualizando el estado id de cabecera orden de compras
					$cn = new PostgreSQL();
					$query = $cn->consulta("UPDATE logistica.compras SET esid = '13' WHERE nrocompra LIKE '".$_REQUEST['ncom']."' ");
					$cn->affected_rows($query);
					$cn->close($query);
				}
				echo $_SESSION['nroning'];
				$_SESSION['nroning'] = '';
			}else{
				echo "error";
			}	
		}
	}
}
?>