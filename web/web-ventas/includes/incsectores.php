<?php

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'editsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.sectores SET sector = '".$_POST['des']."', descripcion = '".$_POST['obs']."' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(nroplano) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}else if ($_POST['tra'] == 'delsecv') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.sectores WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(nroplano) LIKE '".$_POST['sec']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'delsub') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.sectores WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.subproyectos WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}else if($_POST['tra'] == 'editsub'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.subproyectos SET subproyecto = '".$_POST['des']."', fecent = '".$_POST['fec']."'::date, obser = '".$_POST['obs']."' WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'upload') {
	if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/')) {
		mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/');
		chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/', 0777);
	}
	$return = 'success';
	$tmpcomp = $_FILES['fcom']['tmp_name'];
	$tmpadm = $_FILES['fadm']['tmp_name'];

	if ($_POST['sub'] != '') {
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/', 0777);
		}
		if (!move_uploaded_file($tmpcomp, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar')) {
			$return = 'Error al Cargar el Archivo';
		}
		
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/', 0777);
		}
		if (!move_uploaded_file($tmpadm, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar')) {
			$return = 'Error al Cargar el Archivo';
		}
		if ($return == 'success') {
			shell_exec('tar -xf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			shell_exec('tar -xf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar');
			shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar');
			shell_exec('chmod -R 0007 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/*');
			shell_exec('chmod -R 0007 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/*');
		}
	}else{
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/', 0777);
		}
		if (!move_uploaded_file($tmpcomp, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar')) {
				$return = 'Error al Cargar Archivo';
			}
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/', 0777);
		}
		if (!move_uploaded_file($tmpadm, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar')) {
			$return = 'Error al Cargar Archivo';
		}
		
		if ($return == 'success') {
			shell_exec('tar -xf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			shell_exec('tar -xf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar');
			shell_exec('chmod -R 0007 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/*');
			shell_exec('chmod -R 0007 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/*');
		}
	}
	echo $return;
}
?>