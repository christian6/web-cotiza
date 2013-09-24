<?php
session_start();

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tipo'] == "a") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '35' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		//select dni
		$c = new PostgreSQL();
		$q = $c->consulta("SELECT empdni FROM almacen.pedido WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$re = $c->ExecuteNomQuery($q);
		$c->close($q);
		//enviar mensaje
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) 
			VALUES('".$_SESSION['dni-icr']."','".$re['empdni']."','Pedido Aprobado Nro ".$_REQUEST['nro']."','Se aprobo el pedido nro ".$_REQUEST['nro']."\r\n\r\nSystem.','55')");
		$cn->affected_rows($query);
		$cn->close($query);
		//auditoria
		$c = new PostgreSQL();
		$tb = "almacen_pedido";
		$t = "UPDATE";
		$u = $_SESSION['dni-icr'];
		$q = $_REQUEST['nro']." data update 35";
		$b = "Se aprobo el pedido nro ".$_REQUEST['nro'];
		$c->auditoria($tb,$t,$u,$q,$b);
		echo "hecho";
}elseif ($_REQUEST['tipo'] == "n") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE almacen.pedido SET esid = '33' WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		//select dni
		$c = new PostgreSQL();
		$q = $c->consulta("SELECT proyectoid,TRIM(subproyectoid) as subproyectoid,TRIM(sector) as sector,empdni FROM almacen.pedido WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		$re = $c->ExecuteNomQuery($q);
		$c->close($q);
		//enviar mensaje
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) 
			VALUES('".$_SESSION['dni-icr']."','".$re['empdni']."','Pedido Anulado Nro ".$_REQUEST['nro']."','Se anulo el pedido nro ".$_REQUEST['nro']."\r\n\r\nSystem.','55')");
		$cn->affected_rows($query);
		$cn->close($query);
		//
		$c2 = new PostgreSQL();
		$q2 = $c2->consulta("SELECT DISTINCT materialesid FROM almacen.detpedidomat WHERE nropedido LIKE '".$_REQUEST['nro']."'");
		if ($c2->num_rows($q2) > 0) {
			while ($fila = $c2->ExecuteNomQuery($q2)) {
				$cn = new PostgreSQL();
				$query = $cn->consulta("UPDATE operaciones.metproyecto set flag = '1' WHERE proyectoid LIKE '".$re['proyectoid']."'
							AND TRIM(subproyectoid) LIKE '".$re['subproyectoid']."' AND TRIM(sector) LIKE '".$re['sector']."'
							AND materialesid LIKE '".$fila['materialesid']."'");
				$cn->affected_rows($query);
				$cn->close($query);
				//echo "UPDATE operaciones.metproyecto set flag = '1' WHERE proyectoid LIKE TRIM('".$re['proyectoid']."')
				//			AND TRIM(subproyectoid) LIKE TRIM('".$re['subproyectoid']."') AND TRIM(sector) LIKE TRIM('".$re['sector']."')
				//			AND materialesid LIKE TRIM('".$fila['materialesid']."')";
			}
		}
		$c2->close($q2);
		// update tabla niples
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE operaciones.niples set flag = '0' where nropedido LIKE '".$_REQUEST['nro']."';");
		$cn->affected_rows($query);
		$cn->close($query);

		//auditoria
		$c = new PostgreSQL();
		$tb = "almacen_pedido";
		$t = "UPDATE";
		$u = $_SESSION['dni-icr'];
		$q = $_REQUEST['nro']." data update 33";
		$b = "Se anulo el pedido nro ".$_REQUEST['nro'];
		$c->auditoria($tb,$t,$u,$q,$b);

		echo "hecho";
}

if ($_POST['tra'] == 'saveobs') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.alertapedido(nropedido,msg,status) VALUES('".$_POST['npe']."','".$_POST['obs']."','1');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
?>