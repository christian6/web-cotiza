<?php
session_start();

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'ctmp') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."' ");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ($result[0] > 0) {
		echo("success");
	}
}
if ($_POST['tra'] == 'dpro') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT descripcion,direccion FROM ventas.proyectos WHERE proyectoid LIKE '".$_POST['pro']."' ");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		echo $result[0]."|".$result[1]."|success";
	}
	$cn->close($query);
}
if ($_POST['tra'] == 'ltmp') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT d.materialesid,m.matnom,m.matmed,m.matund,sum(d.cantidad) as cantidad FROM almacen.tmpdevolucion d INNER JOIN admin.materiales m ON d.materialesid LIKE m.materialesid WHERE empdni LIKE '".$_SESSION['dni-icr']."' GROUP BY d.materialesid,m.matnom,m.matmed,m.matund");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)){
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td id='tc'>".$result['matund']."</td>";
			echo "<td id='tc'>".$result['cantidad']."</td>";
			echo "<td id='tc'><button class='btn btn-mini btn-warning' onclick=showedi('".$result['materialesid']."');><i class='icon-edit'></i></button></td>";
			echo "<td id='tc'><button class='btn btn-mini btn-danger' onclick=delmattmp('".$result['materialesid']."');><i class='icon-remove'></i></button></td>";
			echo "</tr>";
		}
	}
	$cn->close($query);
	echo "|success";
}
if ($_POST['tra'] == 'stmp') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.tmpdevolucion VALUES('".$_SESSION['dni-icr']."','".$_POST['mat']."',".$_POST['can'].",'".$_POST['est']."','1');");
	$cn->affected_rows($query);
	$cn->close();
	echo "success";
}
if ($_POST['tra'] == 'delmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."' AND materialesid LIKE '".$_POST['mat']."' ");
	$cn->affected_rows($query);
	$cn->close($query);
	delimg($_POST['mat']);
	echo "success";
}
if ($_POST['tra'] == 'delltmp') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."' ");
	$cn->affected_rows($query);
	$cn->close($query);
	delallimg();
	echo "success";
}
if ($_POST['tra'] == 'modify') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."' AND materialesid LIKE '".$_POST['mat']."' ");
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.tmpdevolucion VALUES('".$_SESSION['dni-icr']."','".$_POST['mat']."',".$_POST['can'].",'".$_POST['est']."','1');");
	$cn->affected_rows($query);
	$cn->close();
	echo "success";
}
if ($_POST['tra'] == 'savedev') {
	// obteniendo codigo
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.sp_new_devolucion()");
	if ($cn->num_rows($query) > 0) {
		$ncod = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ($ncod[0] != '') {
		// generar cabecera de devolucion
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.devolucion VALUES('".$ncod[0]."','".$_POST['alm']."','".$_POST['nrg']."','".$_POST['pro']."',now()::date,'".$_POST['obs']."')");
		$cn->affected_rows($query);
		$cn->close($query);
		// consultar detalle de tmp
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT * FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."' ");
		if ($cn->num_rows($query) > 0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO almacen.detdevolucion VALUES('".$ncod[0]."','".$result['materialesid']."',".$result['cantidad'].",'".$result['est']."','1');");
				$c->affected_rows($q);
				$c->close($q);
				// agregar a almacen
				$c = new PostgreSQL();
				$q = $c->consulta("SELECT stock FROM almacen.inventario WHERE almacenid LIKE '".$_POST['alm']."' AND materialesid LIKE '".$result['materialesid']."' AND anio LIKE extract(year from now())::char(4) AND esid LIKE '23' LIMIT 1 OFFSET 0");
				if ($c->num_rows($q) > 0) {
					$stock = $c->ExecuteNomQuery($q);
				}
				$c->close($q);
				$stock = ($stock[0] + $result['cantidad']);
				$c = new PostgreSQL();
				$q = $c->consulta("UPDATE almacen.inventario SET stock = ".$stock." WHERE almacenid LIKE '".$_POST['alm']."' AND materialesid LIKE '".$result['materialesid']."' AND anio LIKE extract(year from now())::char(4) AND esid LIKE '23' ");
				$c->affected_rows($q);
				$c->close($q);
			}
			 
		}
		$cn->close($query);
		//Eliminar Tmp
		$cn = new PostgreSQL();
		$query = $cn->consulta("DELETE FROM almacen.tmpdevolucion WHERE empdni LIKE '".$_SESSION['dni-icr']."'");
		$cn->affected_rows($query);
		$cn->close($query);

		//mover images de devoluciones
		$path = $_SERVER['DOCUMENT_ROOT'].'/web/web-almacen/devolucionesimg/';
		if (!file_exists($path)) {
			mkdir($path);
			chmod($path, 0777);
		}
		$path .= $ncod[0].'/';
		if (!file_exists($path)) {
			mkdir($path);
			chmod($path, 0777);
		}
		$ori = $_SERVER['DOCUMENT_ROOT']."/web/tmpimg/*";
		shell_exec(" mv ".$ori." ".$path);
		echo $ncod[0];
	}else{
		echo "fail";
	}
}

if ($_POST['tra'] == 'upimg') {
	$return = 'success';
	$path = $_SERVER['DOCUMENT_ROOT']."/web/tmpimg/";
	if (!file_exists($path)) {
		mkdir($path);
		chmod($path, 0777);
	}
	$tmp = $_FILES['img']['tmp_name'];
	$name = $_FILES['img']['name'];
	$format = explode(".", $name);
	//echo $format[(count($format) -1)];
	$path = $path .$_POST['mat'].".".$format[(count($format) - 1)];
	if (!move_uploaded_file($tmp, $path)) {
		$return = "He Perdido el Archivo!";
	}
	if ($return == 'success') {
		chmod($path, 0777);
	}
	echo $return;
}

function delimg($mat='')
{
	if ($mat != '') {
		$path = $_SERVER['DOCUMENT_ROOT'].'/web/tmpimg/';
		if (file_exists($path)) {
			$dir = opendir($path);
			while ($file = readdir($dir)) {
				$arch = explode(".", $file);
				if ($arch[0] == $mat) {
					//unlink(filename)
					shell_exec("rm -rf ".$path.$file);
				}
			}
			closedir($dir);
		}
	}
}
function delallimg()
{
	$path = $_SERVER['DOCUMENT_ROOT'].'/web/tmpimg/*';
	shell_exec("rm -rf ".$path);
}

/* lista devolucion */

if ($_POST['tra'] == 'list') {
	//Consulta
	$sql = "SELECT d.devolucionid,p.descripcion,a.descri,d.fecha FROM almacen.devolucion d INNER JOIN ventas.proyectos p ON d.proyectoid LIKE p.proyectoid
			INNER JOIN admin.almacenes a ON d.almacenid LIKE a.almacenid ";
	if ($_POST['cod'] != '') {
		$sql .= " WHERE d.devolucionid LIKE '".$_POST['cod']."' ORDER BY d.devolucionid ASC";
	}else{
		if ($_POST['fi'] != '' && $_POST['ff'] == '') {
			$sql .= " WHERE d.fecha = '".$_POST['fi']."'::date  ORDER BY d.devolucionid ASC";
		}else if($_POST['fi'] != '' && $_POST['ff'] != '') {
			$sql .= " WHERE fecha BETWEEN '".$_POST['fi']."'::date AND '".$_POST['ff']."'::date  ORDER BY d.devolucionid ASC";
		}
	}
	if ($_POST['limit'] != '') {
		$sql .= " ORDER BY d.devolucionid ASC LIMIT 10 OFFSET 0";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td id='tc'>".$i++."</td>";
			echo "<td>".$result['devolucionid']."</td>";
			echo "<td>".$result['descripcion']."</td>";
			echo "<td>".$result['descri']."</td>";
			echo "<td>".$result['fecha']."</td>";
			echo "<td id='tc'><a class='btn btn-mini btn-warning' target='_blank' href=http://190.41.246.91/web/reports/almacen/pdf/rptdevolucion.php?nro=".$result['devolucionid']."><i class='icon-eye-open'></i></a></td>";
			echo "</tr>";
		}
	}
	$cn->close($query);
	echo "|success";
}

?>