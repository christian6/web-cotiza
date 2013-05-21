<?php
include ("../../datos/postgresHelper.php");

$id = $_REQUEST['id'];
$nom = $_REQUEST['nom'];
$tp = $_REQUEST['tip'];
$txt = $_REQUEST['text'];
$eid = $_REQUEST['eid'];

if (isset($txt)) {
	$cn = new PostgreSQL();
	$ncod = "";
	$query = $cn->consulta("SELECT to_char(((MAX(esid)::INTEGER)+1),'00') as cod FROM admin.estadoes");
	if ($cn->num_rows($query)>0) {
		while($result = $cn->ExecuteNomQuery($query)){
			$ncod = $result['cod'];
		}
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.estadoes VALUES(TRIM('$ncod'),TRIM('$eid'),'$txt')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

if (isset($id) && isset($tp)) {
	$cad = "";
	if (strlen($id)<2) {
		$id = "0".$id;
	}
	if ($tp == "m") {
		$cad = "UPDATE admin.estadoes SET esnom='$nom' WHERE esid = '$id'";
	}else if($tp == "d"){
		$cad = "DELETE FROM admin.estadoes WHERE esid='$id'";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($cad);
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

?>