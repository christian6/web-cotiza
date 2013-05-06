<?php

include ("../../datos/postgresHelper.php");

if (isset($_REQUEST['tipo'])) {

switch ($_REQUEST['tipo']) {
	case 'g':
		$nrop = $_REQUEST['nro'];
		$destino = $_REQUEST['des'];
		$ruc = $_REQUEST['ruc'];
		$rz = $_REQUEST['rz'];
		$fec = $_REQUEST['fec'];
		$tra = $_REQUEST['trans'];
		$mov = $_REQUEST['mov'];
		$con = $_REQUEST['con'];
		# Guardar Cabezera de Guia de Remision
		$nguia = "";
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT almacen.fn_nuevaguia() ");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$nguia = $result[0];
		}
		$cn->close($query);

		$cn = new PostgreSQL();
		# nroguia, nropedido, puntollega, razonsocial, ruc, fectra, fecha, traruc, condni, nroplaca
		$query = $cn->consulta("INSERT INTO almacen.guiaremision(nroguia,nropedido,puntollega,razonsocial,ruc,fectra,traruc,condni,nroplaca)
				VALUES(TRIM('$nguia'),TRIM('$nrop'),TRIM('$destino'),TRIM('$rz'),TRIM('$ruc'),to_date('$fec','dd-mm-yyyy'),TRIM('$tra'),TRIM('$con'),TRIM('$mov'))");
		$cn->affected_rows($query);
		$cn->close($query);
		# Guardar detalle de guia de remision
		///
		$flag = 0;
		$c = new PostgreSQL();
		$q = $c->consulta("SELECT COUNT(flag) FROM almacen.detpedidomat WHERE nropedido LIKE '$nrop' AND flag = '1'");
		if ($c->num_rows($q)>0) {
			$r = $c->ExecuteNomQuery($q);
			$flag = $r[0];
		}
		$c->close($q);
		///
		$cn = new PostgreSQL();
		if($flag <= 0){
			$query = $cn->consulta("SELECT materialesid,sum(cantidad) as cantidad FROM almacen.detpedidomat WHERE auto = '0' AND flag LIKE '0' AND nropedido LIKE '$nrop' group by materialesid order by materialesid ASC ");
		}else if($flag >= 1){
			$query = $cn->consulta("SELECT materialesid,sum(cantidad) as cantidad FROM almacen.detpedidomat WHERE auto = '0' AND flag LIKE '1' AND nropedido LIKE '$nrop' group by materialesid order by materialesid ASC ");
		}
		if ($cn->num_rows($query)>0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$cn2 = new PostgreSQL();
				$query2 = $cn->consulta("INSERT INTO almacen.detguiamat VALUES('$nguia','".$result['materialesid']."',".$result['cantidad'].")");
				$cn2->affected_rows($query2);
				$cn2->close($query2);
			}
		}
		$cn->close($query);

		echo $nguia;
		break;
	case 'n':
		# Recuperando el numero de la nota de salida
		$nnota = "";
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT almacen.fn_nuevanota() ");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$nnota = $result[0];
		}
		$cn->close($query);
		# Recuperar Datos para la cabecera
		$nrop = TRIM($_REQUEST['nro']);
		$fec = TRIM($_REQUEST['fec']);
		$des = TRIM($_REQUEST['des']);
		$nnota = TRIM($nnota);
		# Guardando la cabecera de la nota
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.notasalida(nronsalida,nropedido,fecsal,destino) VALUES(TRIM('$nnota'),TRIM('$nrop'),to_date('$fec','dd-mm-yyyy'),TRIM('$des'))");
		$cn->affected_rows($query);
		$cn->close($query);
		# Guardar Detalle de nota de salida
		$flag = 0;
		$c = new PostgreSQL();
		$q = $c->consulta("SELECT COUNT(flag) FROM almacen.detpedidomat WHERE nropedido LIKE TRIM('$nrop') AND flag = '1'");
		if ($c->num_rows($q)>0) {
			$r = $c->ExecuteNomQuery($q);
			$flag = $r[0];
		}
		$c->close($q);
		$cn = new PostgreSQL();
		if($flag <= 0){
			$query = $cn->consulta("SELECT materialesid,sum(cantidad) as cantidad FROM almacen.detpedidomat WHERE auto = '0' AND flag LIKE '0' AND nropedido LIKE TRIM('$nrop') group by materialesid order by materialesid ASC ");
		}else if($flag >= 1){
			$query = $cn->consulta("SELECT materialesid,sum(cantidad) as cantidad FROM almacen.detpedidomat WHERE auto = '0' AND flag LIKE '1' AND nropedido LIKE TRIM('$nrop') group by materialesid order by materialesid ASC ");
		}
		if ($cn->num_rows($query)>0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$cn2 = new PostgreSQL();
				$query2 = $cn->consulta("INSERT INTO almacen.detnsalidamat VALUES(TRIM('$nnota'),'".$result['materialesid']."',".$result['cantidad'].")");
				$cn2->affected_rows($query2);
				$cn2->close($query2);
			}
		}
		$cn->close($query);
		echo $nnota;
		break;
}

}

if (isset($_REQUEST['tra'])) {
	# movilidad
	echo "<tr>";
	echo "<td><label for='mov'>Movilidad: </label></td>";
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT nropla, marca FROM admin.transporte WHERE traruc = '".$_REQUEST['truc']."'");
	if ($cn->num_rows($query)>0) {
		echo "<td>";
		echo "<select id='cbomov' name='cbomov'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['nropla']."'>".$result['nropla']." - ".$result['marca']."</option>";
		}
		echo "</select>";
		echo "</td>";
	}
	$cn->close($query);
	echo "</tr>";
	# conductor
	echo "<tr>";
	echo "<td><label for='con'>Conductor: </label></td>";
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT condni,conlic, connom FROM admin.conductor WHERE traruc = '".$_REQUEST['truc']."'");
	if ($cn->num_rows($query)>0) {
		echo "<td>";
		echo "<select id='cbocon' name='cbocon'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['condni']."'>".$result['connom']."</option>";
		}
		echo "</select>";
		echo "</td>";
	}
	$cn->close($query);
	echo "</tr>";
}


?>
