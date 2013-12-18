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

	if ($_POST['sub'] == "") {
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/', 0777);
		}
		if (!move_uploaded_file($tmpcomp, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar')) {
				$return = 'Error al Cargar Archivo complementario';
			}
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/', 0777);
		}
		if (!move_uploaded_file($tmpadm, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar')) {
			$return = 'Error al Cargar Archivo Administracion';
		}
		
		if ($return == 'success') {
			shell_exec('tar -x '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			shell_exec('tar -x '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/complementario.rar');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/administracion.rar');
			shell_exec('chmod -R 754 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/*');
			shell_exec('chmod -R 754 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/*');
		}
	}else if($_POST['sub'] != ''){
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/', 0777);
		}
		if (!move_uploaded_file($tmpcomp, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar')) {
			$return = 'Error al Cargar el Archivo complementario Subpro';
		}
		
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/');
			chmod($_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/', 0777);
		}
		if (!move_uploaded_file($tmpadm, $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar')) {
			$return = 'Error al Cargar el Archivo Administracion Subpro';
		}
		if ($return == 'success') {
			shell_exec('tar -x '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/adm/');
			shell_exec('tar -x '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar -C '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/comp/');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/complementario.rar');
			//shell_exec('rm -rf '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/administracion.rar');
			shell_exec('chmod -R 754 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/comp/*');
			shell_exec('chmod -R 754 '.$_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/'.$_POST['sub'].'/adm/*');
		}
	}
	echo $return;
}
if ($_POST['tra'] == 'msgplu') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.alertapro(proyectoid,subproyectoid,msg,tm) VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['msg']."','".$_POST['tfr']."');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'editpro') {
	$lpro = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT proyectoid,descripcion,fecent,ruccliente,direccion,paisid,departamentoid,provinciaid,distritoid,obser,feccom FROM ventas.proyectos WHERE proyectoid LIKE '".$_POST['pro']."'");
		if ($cn->num_rows($query) > 0) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array('proyectoid'=>$result['proyectoid'],'descripcion'=>$result['descripcion'],'fecent'=>$result['fecent'],'ruccliente'=>$result['ruccliente'],'direccion'=>$result['direccion'],'paisid'=>$result['paisid'],'departamentoid'=>$result['departamentoid'],'provinciaid'=>$result['provinciaid'],'distritoid'=>$result['distritoid'],'obser'=>$result['obser'],'feccom'=>$result['feccom']);
			}
		}
		$cn->close($query);
		$lpro['status'] = 'success';
		$lpro['list'] = $res;
	} catch (Exception $e) {
		$lpro['status'] = 'fail';
	}
	echo json_encode( $lpro );
}

if ($_GET['tra'] == 'matnom') {
	$lmat = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT matnom FROM admin.materiales WHERE lower(matnom) LIKE lower('%".$_GET['nom']."%') ORDER BY matnom ASC");
		if ($cn->num_rows($query) > 0){
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array('matnom'=>$result['matnom']);
			}
		}
		$cn->close($query);
		$lmat['status'] = 'success';
		$lmat['list'] = $res;
	} catch (Exception $e) {
		$lmat['status'] = 'fail';
	}
	echo json_encode($lmat);
}

?>