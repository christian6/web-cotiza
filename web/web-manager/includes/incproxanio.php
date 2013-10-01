<?php
include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'col') {
	$anio = array('Año');
	$cpro = array('cantidad');

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT extract(year from fecha),COUNT(*)as tot FROM ventas.proyectos 
							GROUP BY extract(year from fecha) ORDER BY extract(year from fecha) ASC;");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$anio[] = (String)$result[0];
			$cpro[] = (Integer)$result[1];
		}
	}
	$cn->close($query);

	echo json_encode( array( $anio,$cpro ) );
}
if ($_POST['tra'] == 'line') {
	//$con = array();
	$cpro[] = array('Años','Cantidad');

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT extract(year from fecha),COUNT(*)as tot FROM ventas.proyectos 
							GROUP BY extract(year from fecha) ORDER BY extract(year from fecha) ASC;");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cpro[] = array((String)$result[0],(Integer)$result[1]);
		}
	}
	$cn->close($query);

	echo json_encode( $cpro );
}
?>