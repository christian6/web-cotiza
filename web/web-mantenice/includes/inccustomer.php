<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'save') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.clientes(ruccliente, nombre, abre, direccion, paisid, departamentoid, 
            provinciaid, distritoid, telefono, contacto, esid)
							VALUES('".$_POST['ruc']."','".$_POST['rz']."','".$_POST['abr']."','".$_POST['dir']."',
									'".$_POST['pai']."','".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."',
									'".$_POST['tel']."','".$_POST['con']."','41')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if($_POST['tra'] == 'list'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT c.ruccliente,c.nombre,c.direccion,c.telefono,c.contacto,e.esnom
							FROM admin.clientes c INNER JOIN admin.estadoes e
							ON c.esid LIKE e.esid");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr class='c-yellow-light'>";
			echo "<td id='tc'>".$i++."</td>";
			echo "<td id='tc'>".$result['ruccliente']."</td>";
			echo "<td>".$result['nombre']."</td>";
			echo "<td>".$result['direccion']."</td>";
			echo "<td id='tc'>".$result['telefono']."</td>";
			echo "<td>".$result['contacto']."</td>";
			echo "<td id='tc'>".$result['esnom']."</td>";
			echo "</tr>";
		}
	}
	$cn->close($query);
}

?>