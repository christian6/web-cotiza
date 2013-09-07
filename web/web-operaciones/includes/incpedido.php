<?php

session_start();

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'sp') {
	// generando numero de pedido
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.spnuevopedido()");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$ncod = TRIM($result[0]);
	}
	$cn->close($query);
	// guardando datos de la cabcera de pedido
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.pedido(nropedido,proyectoid,subproyectoid,sector,empdni,fecent,obser,almacenid,esid) 
							VALUES('$ncod','".$_POST['pro']."',TRIM('".$_POST['sub']."'),'".$_POST['sec']."','".$_POST['dni']."',
							'".$_POST['fec']."'::Date,'".$_POST['obs']."','".$_POST['alm']."','32')");
	$cn->affected_rows($query);
	$cn->close($query);
	// guardando detalle de proyecto
	//echo $_POST['mat'];
	$ids = $_POST['mat'];
	$cn = new PostgreSQL();
	$sql = "SELECT materialesid,cant FROM operaciones.metproyecto WHERE ";
	if ($_POST['sub'] != "") {
		$sql .= "proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND materialesid LIKE ";
	}else{
		$sql .= "proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '' AND materialesid LIKE ";
	}
	for ($i=0; $i < count($ids); $i++) { 
		if( $i == (count($ids) - 1) ){
			$sql .= "'".$ids[$i]."'";
		}else{
			$sql .= "'".$ids[$i]."' OR  materialesid LIKE ";
		}
	}
	//echo $sql;
	//SELECT materialesid,cant FROM operaciones.metproyecto 
	//WHERE proyectoid LIKE '0000000002' AND TRIM(subproyectoid) LIKE '' AND materialesid LIKE
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO almacen.detpedidomat VALUES('$ncod','".$result['materialesid']."',".$result['cant'].",'1','0');");
			$c->affected_rows($q);
			$c->close($q);
			$c = new PostgreSQL();
			$q = $cn->consulta("UPDATE operaciones.metproyecto SET flag = '0' 
				WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
				AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$result['materialesid']."'");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
	echo "hecho";
}
if ($_POST['tra'] == 'tmpnip') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO operaciones.tmpniples(dni,proyecotid,subproyectoid,sector,adicionalid,materialid,metrado,tipo) VALUES('".$_SESSION['dni-icr']."',
							'".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['adi']."','".$_POST['matid']."',".$_POST['met'].",'".$_POST['tip']."');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'listnip') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT id,dni,materialid,metrado,tipo,
		(select sum(metrado)as tot from operaciones.tmpniples WHERE materialid LIKE materialid ) as tot FROM operaciones.tmpniples 
		WHERE dni LIKE '".$_SESSION['dni-icr']."' AND materialid LIKE '".$_POST['mat']."'
		GROUP BY id,dni,materialid,metrado,tipo;");
	if ($cn->num_rows($query) > 0 ) {
		$tot = 0;
		echo "<table class='table t-info'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td>".$result['id']."</td>";
			echo "<td>".$result['materialid']."</td><td>".$_POST['med']."''</td><td>&times;</td><td>".$result['metrado']."</td><td>".$result['tipo']."</td>";
			echo "<td><button class='btn btn-mini' OnClick=delniple(".$result['id'].",'".$result['materialid']."','".$_POST['med']."');><i class='icon-trash'></i></button></td>";
			echo "</tr>";
			$tot = $result['tot'];
		}
		echo "</table>";
	}
	$cn->close($query);
	echo "|success|".$tot;
}
if ($_POST['tra'] == 'delniple') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpniples WHERE dni LIKE '".$_SESSION['dni-icr']."' AND id = ".$_POST['id']."");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
?>