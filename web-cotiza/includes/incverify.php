<?php
session_start();

include("../datos/postgresHelper.php");

$user = $_REQUEST['nom'];
$pss = $_REQUEST['pwd'];

if (isset($user) && isset($pss)) {

$cn = new PostgreSQL();
$query = $cn->consulta("SELECT l.rucproveedor,l.username,p.razonsocial FROM public.loginpro l INNER JOIN admin.proveedor p ON l.rucproveedor=p.rucproveedor WHERE username LIKE trim('$user') AND passwd LIKE trim('$pss') LIMIT 1 offset 0");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
		$_SESSION['ruc'] = $result['rucproveedor'];
		$_SESSION['user'] = $result['username'];
		$_SESSION['nom'] = $result['razonsocial'];
		$_SESSION['access'] = true;
	}
	echo "access";
}else{
	$_SESSION['access'] = false;
	echo "restring";
}
$cn->close($query);
}

?>