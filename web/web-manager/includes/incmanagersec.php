<?php
session_start();

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'apmsec') {
	// Eliminando sector de metproyecto
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.metproyecto WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'
						 AND TRIM(sector) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	// Insertando el sector de tmpmodificaciones a metproyecto
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO operaciones.metproyecto SELECT * FROM operaciones.tmpmodificaciones
							WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	// update de status de operaciones.modifysec
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT id,fec FROM operaciones.modifysec WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' ORDER BY fec DESC LIMIT 1 OFFSET 0;");
	$res = $cn->ExecuteNomQuery($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE operaciones.modifysec SET status = '2' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' AND id = ".$res[0].";");
	$cn->affected_rows($query);
	$cn->close($query);
	// delete from operaciones.tmpmodificaciones
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);

	// Mandar Mensaje a Buson de entrada
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT empdni FROM ventas.proyectopersonal WHERE proyectoid LIKE '".$_POST['pro']."' LIMIT 1 OFFSET 0");
	if ($cn->num_rows($query) > 0) {
		$ford = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) VALUES ('".$_SESSION['dni-icr']."','".$ford[0]."', 'Modificación de sector Aprobada - ".$_POST['sec']."', 'Se aprobó la modificación del sector ".$_POST['sec']." del proyecto id ".$_POST['pro']." ".$_POST['sub']."', '56');");
	$cn->affected_rows($query);
	$cn->close($query);

	// Poner en auditoria
	$cn = new PostgreSQL();
	$cn->auditoria("OPERACIONES_METPROYECTO","INSERT",$_SESSION['dni-icr'],"APROBAR MODIFICAR SECTOR ".$_POST['sec'],"PROID ".$_POST['pro']." subid ".$_POST['sub']." secid ".$_POST['sec']);

	echo "success";
}
if ($_POST['tra'] == 'msgsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.alertasec(proyectoid,subproyectoid,sector,msg,tm) VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['obs']."','".$_POST['tfr']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'anmsec') {
	// delete from operaciones.tmpmodificaciones
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	// update de status de operaciones.modifysec
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT id FROM operaciones.modifysec WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' ORDER BY fec DESC LIMIT 1 OFFSET 0;");
	$res = $cn->ExecuteNomQuery($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE operaciones.modifysec SET status = '1' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' AND id = ".$res[0].";");
	$cn->affected_rows($query);
	$cn->close($query);
	// Poner en auditoria
	$cn = new PostgreSQL();
	$cn->auditoria("OPERACIONES_MODIFICACIONTMP","INSERT",$_SESSION['dni-icr'],"ANULAR MODIFICAR SECTOR ".$_POST['sec'],"PROID ".$_POST['pro']." subid ".$_POST['sub']." secid ".$_POST['sec']);
	// Mandar Mensaje a Buson de entrada
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT empdni FROM ventas.proyectopersonal WHERE proyectoid LIKE '".$_POST['pro']."' LIMIT 1 OFFSET 0");
	if ($cn->num_rows($query) > 0) {
		$res = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) VALUES ('".$_SESSION['dni-icr']."','".$res[0]."', 'Modificación de sector Anulada - ".$_POST['sec']."', 'Se anulo la modificación del sector ".$_POST['sec']." del proyecto id ".$_POST['pro']." ".$_POST['sub']."', '56');");
	$cn->affected_rows($query);
	$cn->close($query);

	echo "success";
}
if ($_POST['tra'] == 'newadi') {
	// obteniendo adicional
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevosecadicional();");
	$nadi = $cn->ExecuteNomQuery($query);
	$nadi = $nadi[0];
	$cn->close($query);
	// Save to sector
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.sectores VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec'].$nadi."','".$_POST['des']."','".$_POST['obs']."','60');");
	$cn->affected_rows($query);
	$cn->close($query);
	// Save to det Sector
	// insert metoperaciones
	$ar = $_POST['mat'];
	$cad = '';
	for ($i=0; $i < count($ar); $i++) {
		if ($i == (count($ar) - 1)) {
			$cad .= "'".$ar[$i]."'";
		}else{
			$cad .= "'".$ar[$i]."',";
		}
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM operaciones.tmpmodificaciones
							WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid IN (".$cad.");");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO operaciones.metproyecto VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec'].$nadi."',
							'".$result['materialesid']."',".$result['cant'].",'1');");
			$c->close($q);
		}
	}
	$cn->close($query);
	// Save Adicional
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.secadicional VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['sec'].$nadi."','".$_POST['noc']."',now(),'1');");
	$cn->affected_rows($query);
	$cn->close($query);
	// update de status de operaciones.modifysec
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT id,fec FROM operaciones.modifysec WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' ORDER BY fec DESC LIMIT 1 OFFSET 0;");
	$res = $cn->ExecuteNomQuery($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE operaciones.modifysec SET status = '3' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."' AND id = ".$res[0].";");
	$cn->affected_rows($query);
	$cn->close($query);

	// delete from operaciones.tmpmodificaciones
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
							AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$cn->affected_rows($query);
	$cn->close($query);

	// Poner en auditoria
	$cn = new PostgreSQL();
	$cn->auditoria("ventas_sector","INSERT",$_SESSION['dni-icr'],"APROBAR NUEVo ADICIONAL ".$nadi,"PROID ".$_POST['pro']." subid ".$_POST['sub']." secid ".$_POST['sec']." adicional ".$nadi);
	// Mandar Mensaje a Buson de entrada
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT empdni FROM ventas.proyectopersonal WHERE proyectoid LIKE '".$_POST['pro']."' LIMIT 1 OFFSET 0");
	if ($cn->num_rows($query) > 0) {
		$res = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) VALUES ('".$_SESSION['dni-icr']."','".$res[0]."', 'Aprobación de Adicional - ".$nadi."', 'Se aprobo adicional ".$nadi." del proyecto id ".$_POST['pro']." ".$_POST['sub']."', '56');");
	$cn->affected_rows($query);
	$cn->close($query);

	echo "success";
}

?>