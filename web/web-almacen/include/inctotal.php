<?php

session_start();

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'datos') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT matnom,matmed FROM admin.materiales 
							WHERE materialesid LIKE '".$_POST['id']."' LIMIT 1 OFFSET 0");
	if ($cn->num_rows($query) > 0) {
		while ($result =  $cn->ExecuteNomQuery($query)) {
			echo $result['matnom']."|".$result['matmed'];
		}
	}else{
		echo "nada";
	}
	$cn->close($query);

}else if($_POST['tra'] == 'stmp'){
	$cn = new PostgreSQL();
	$cn->consulta("INSERT INTO almacen.tmpsuministro VALUES('".$_SESSION['dni-icr']."','".$_POST['cod']."','".$_POST['ca']."');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

?>