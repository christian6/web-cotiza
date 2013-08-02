<?php
session_start();

include("../../datos/postgresHelper.php");

if ($_POST['tra'] == "edit") {
	
	$table = "";
	if ($_POST['rad'] == 'v') {
		$table = "ventas.matmetrado";
	}else if ($_POST['rad'] == 'o') {
		$table = "operaciones.matmetrado";
	}
	
	$sql = "INSERT INTO $table VALUES(";
	$del = "DELETE FROM  $table WHERE ";
	if ($_POST['sub'] != "") {
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['mid']."'";
		$sql .= "'".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['mid']."',".$_POST['cant'].",'1');";
	}else if($_POST['sub'] == ""){
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['mid']."'";
		$sql .= "'".$_POST['pro']."','','".$_POST['sec']."','".$_POST['mid']."',".$_POST['cant'].",'1');";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($del);
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	$cn->affected_rows($query);
	$cn->close($query);

	echo "hecho";
}else if($_POST['tra'] == 'del'){
	$table = "";
	if ($_POST['rad'] == 'v') {
		$table = "ventas.matmetrado";
	}else if ($_POST['rad'] == 'o') {
		$table = "operaciones.matmetrado";
	}
	$del = "DELETE FROM  $table WHERE ";
	if ($_POST['sub'] != "") {
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['mid']."'";
	}else if($_POST['sub'] == ""){
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$_POST['mid']."'";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($del);
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if ($_POST['tra'] == 'venta') {
	$table = "";
	if ($_POST['rad'] == 'v') {
		$table = "ventas.matmetrado";
	}else if ($_POST['rad'] == 'o') {
		$table = "operaciones.matmetrado";
	}
	$sql = "SELECT proyectoid,TRIM(subproyectoid) as subproyectoid,
			TRIM(sector) as sector,TRIM(materialesid) as materialesid, 
			SUM(cant) as cant FROM ".$table." WHERE";
	if ($_POST['sub'] != "") {
		$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
				AND TRIM(sector) LIKE '".$_POST['sec']."' GROUP BY proyectoid,subproyectoid,sector,materialesid";
	}else if($_POST['sub'] == ""){
		$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE '".$_POST['sec']."' GROUP BY proyectoid,subproyectoid,sector,materialesid";
	}

	//echo $sql;

	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO operaciones.metproyecto 
							VALUES('".$result['proyectoid']."','".$result['subproyectoid']."','".$result['sector']."',
							'".$result['materialesid']."',".$result['cant'].",'1');");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);

	echo "hecho";
}else if ($_POST['tra'] == 'admin') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM admin.sp_valid_admin('".$_POST['usu']."','".$_POST['pwd']."');");
	$val = '';
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		//echo $_POST['pwd'];
		//echo $result[0];
		if ($result[0] == "success"){
			$val = True;
		}else{
			$val = False;
		}
	}
	$cn->close($query);
	if ($val == True) {
		$table = "";
		if ($_POST['rad'] == 'v') {
			$table = "ventas.matmetrado";
		}else if ($_POST['rad'] == 'o') {
			$table = "operaciones.matmetrado";
		}
		$sql = "SELECT proyectoid,TRIM(subproyectoid) as subproyectoid,
				TRIM(sector) as sector,TRIM(materialesid) as materialesid, 
				SUM(cant) as cant FROM ".$table." WHERE";
		if ($_POST['sub'] != "") {
			$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
					 GROUP BY proyectoid,subproyectoid,sector,materialesid";
		}else if($_POST['sub'] == ""){
			$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '' GROUP BY proyectoid,subproyectoid,sector,materialesid";
		}
		$cn = new PostgreSQL();
		$query = $cn->consulta($sql);
		if ($cn->num_rows($query) > 0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO operaciones.metproyecto 
								VALUES('".$result['proyectoid']."','".$result['subproyectoid']."','".$result['sector']."',
								'".$result['materialesid']."',".$result['cant'].",'1');");
				$c->affected_rows($q);
				$c->close($q);
			}
		}
		//
		// Cambiando el estado del proyecto de pendiente a aprobado
		//
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE ventas.proyectos SET esid = '55' WHERE proyectoid LIKE '".$_POST['pro']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		//
		$cn->close($query);
		$cn = new PostgreSQL();
		$au = $cn->auditoria('admin_metproyecto','INSERT',$_SESSION['dni-icr'],'Aprobar Lista proyecto ID_PRO: '.$_POST['pro'],"Fue aprobado con el usuario: ".$_POST['usu']." Autenticado Satisfactoriamente.");
		echo "success";
	}else{
		echo "fallida";
	}
}else if($_POST['tra'] == 'delsec'){
	$del = "DELETE FROM operaciones.metproyecto WHERE ";
	if ($_POST['sub'] != "") {
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."'";
	}else if($_POST['sub'] == ""){
		$del .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE '".$_POST['sec']."'";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($del);
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

?>