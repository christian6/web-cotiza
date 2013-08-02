<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'save') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.empleados(empdni, empnom, empape, empfnc, paisid, departamentoid, provinciaid, distritoid, empdir, emptel, cargoid, esid) 
							VALUES('".$_POST['dni']."','".$_POST['nom']."','".$_POST['ape']."','".$_POST['fec']."'::timestamp,
							'".$_POST['pai']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."','".$_POST['dir']."',
							'".$_POST['tel']."',".$_POST['car'].",'19')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if($_POST['tra'] == 'list'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT e.empdni,e.empnom||', '||e.empape as nom,e.empfnc::date,e.emptel,c.carnom,s.esnom FROM 
							admin.empleados e INNER JOIN admin.cargo c
							ON e.cargoid = c.cargoid
							INNER JOIN admin.estadoes s
							ON s.esid LIKE e.esid
							");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr class='c-yellow-light'>";
			echo "<td id='tc'>".$i++."</td>";
			echo "<td id='tc'>".$result['empdni']."</td>";
			echo "<td>".$result['nom']."</td>";
			echo "<td id='tc'>".$result['empfnc']."</td>";
			echo "<td id='tc'>".$result['emptel']."</td>";
			echo "<td id='tc'>".$result['carnom']."</td>";
			echo "<td id='tc'>".$result['esnom']."</td>";
			echo "</tr>";
		}
	}
	$cn->close($query);
}

?>