<?php
include ("../../datos/postgresHelper.php");
extract($_GET);
if ($tra == "bar") {
#$anio = '2012';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT distinct p.proyectoid,p.descripcion,extract(month from p.fecha)::char(2) as mes
							from ventas.proyectos p 
							where extract(year from p.fecha)::char(4) like '".$anio."' ");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$arrayX[$result['descripcion']][] = $result['mes'];
		}
	}
	$cn->close($query);
	echo json_encode($arrayX);
}elseif ($tra == "anio") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT extract(year FROM fecha),(SELECT count(*) FROM ventas.proyectos
							WHERE extract(year FROM p.fecha) = extract(year FROM fecha)) FROM ventas.proyectos p
							GROUP BY fecha;");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		$array = array();
		while ($result = $cn->ExecuteNomQuery($query)) {
			array_push($array, array('name' => $result[0],'data' => array($result[1]) ));
		}
	}
	$cn->close($query);
	echo json_encode($array);
}
?>