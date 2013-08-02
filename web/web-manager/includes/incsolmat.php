<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'det') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT s.mtid,s.solnom,s.solmed,s.solmar,s.solmod,s.solobs FROM admin.solmat s
							WHERE s.flag LIKE '0' AND s.mtid LIKE '".$_POST['sid']."'");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo $result['solnom'].','.$result['solmed'].','.$result['solmar'].','.$result['solmod'].','.$result['solobs'];
		}
	}else{
		echo "nada";
	}
	$cn->close($query);
}else if($_POST['tra'] == 'new'){
	// Verificando si el codigo del material ya existe
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM admin.materiales WHERE materialesid LIKE '".$_POST['cod']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		if ($result[0] > 0) {
			echo "exists";
		}else{
			echo "nothing";
		}
	}else{
		echo "nothing count";
	}
	$cn->close($query);
	echo "nothing";
}else if($_POST['tra'] == 'save'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.materiales VALUES('".$_POST['cod']."','".$_POST['nom']."','".$_POST['med']."',
		'".$_POST['und']."',0,'".$_POST['mar']."','".$_POST['mod']."','Natural',0);");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}else if($_POST['tra'] == 'inve'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.inventario VALUES('".$_POST['cod']."','".$_POST['alm']."',0,'".$_POST['stk']."',
		0,0,0,'".date('Y')."',now()::DATE,'0000000000','10704928501','23');");
	$cn->affected_rows($query);
	$cn->close($query);
	//update sol
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE admin.solmat set flag = '1' WHERE mtid LIKE '".$_POST['sol']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}

?>