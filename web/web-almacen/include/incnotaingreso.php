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
								motivo, obser, empdni, recdni, insdni, vbdni)
								VALUES('$nroning','".$_REQUEST['alid']."','".$_REQUEST['ncom']."','".$_REQUEST['ng']."',
								'".$_REQUEST['nc']."','".$_REQUEST['nf']."','".$_REQUEST['mot']."','".$_REQUEST['obser']."',
								'".$_SESSION['dni-icr']."','".$_REQUEST['rec']."','".$_REQUEST['ins']."','".$_REQUEST['vb']."')");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "hecho";
	}elseif ($_REQUEST['tra'] == 'addd') {
		if ($_SESSION['nroning'] != "") {
			$nroning = $_SESSION['nroning'];
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
			$cn = new PostgreSQL();
			$query = $cn->consulta("INSERT INTO almacen.kingresos(tdoc, nrodoc, stkact, cantidad, saldo, precio, flag)
									VALUES('NI','".$nroning."',".$stkact.",".$_REQUEST['cantidad'].",".$sal.",".$_REQUEST['pre'].",'1')");
			$cn->affected_rows($query);
			$cn->close($query);
			/// En el flag el valor 1 es activado 0 desctivados para ser consultados
			//session_unset($_SESSION['nroning']);
		}
	}
}
?>