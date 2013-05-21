<?php
session_start();

include ("../datos/postgresHelper.php");

if (isset($_REQUEST['usr']) && isset($_REQUEST['pwd'])) {
	# abriendo la conexion a la base de datos
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM admin.spvalidlogin('".$_REQUEST['usr']."','".$_REQUEST['pwd']."')");
	if ($cn->num_rows($query)>0) {
		# Recuperando Datos de la consulta
		$result = $cn->ExecuteNomQuery($query);
		$dni = trim($result[0]);
		if (strlen($dni) == 8) {
			$cn->close($query);

			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("
				SELECT (e.empnom || ' '||e.empape) as nombre,c.carnom FROM admin.empleados e
				INNER JOIN admin.cargo c
				ON e.cargoid = c.cargoid
				WHERE e.empdni LIKE '".$dni."' AND e.esid LIKE '19'
				");
			if ($cn2->num_rows($query2)>0) {
				$ses = $cn2->ExecuteNomQuery($query2);
				# Creando las sessiones para la navegacion
				$_SESSION['accessicr'] = true;
				$_SESSION['dni-icr'] = $dni;
				$_SESSION['nom-icr'] = $ses['nombre'];
				$_SESSION['car-icr'] = $ses['carnom'];
				$_SESSION['user-icr'] = $_REQUEST['usr'];
			}

			$cn2->close($query2);

			echo $_SESSION['car-icr'];
		}else if($result[0] == "nada"){
			$cn->close($query);
			echo "false";
		}
	}else{
		echo "false";
	}
}else{
	echo "false";
}

?>
