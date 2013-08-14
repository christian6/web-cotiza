<?php

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == 'med') {
	$nom = $_REQUEST['nom'];

	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT DISTINCT m.matmed FROM admin.materiales m 
						INNER JOIN almacen.inventario i 
						ON m.materialesid=i.materialesid 
						WHERE m.matnom LIKE '".$nom."' AND i.anio LIKE '".date("Y")."'");
	if ($cn->num_rows($query)>0) {
		echo "<select class='span6' name='cbomed' id='cbomed' onClick='showdet();' onChange='showdet();'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['matmed']."'>".$result['matmed']."</option>";
		}
		echo "</select>";
	}
	$cn->close($query);

}else if($_REQUEST['tra'] == 'data'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT m.materialesid,m.matnom,m.matmed,m.matund FROM
							admin.materiales m INNER JOIN almacen.inventario i 
							ON m.materialesid = i.materialesid
							WHERE  m.matnom LIKE '".$_REQUEST['nom']."' AND m.matmed LIKE '".$_REQUEST['med']."' AND i.anio LIKE '".date("Y")."'
							LIMIT 1 OFFSET 0");
	$cod = "";
	$nom = "";
	$med = "";
	$und = "";
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cod = $result['materialesid'];
			$nom = $result['matnom'];
			$med = $result['matmed'];
			$und = $result['matund'];
		}
	}
	$cn->close($query);
?>
	<dl class="dl-horizontal">
		<dt>Codigo</dt>
		<dd id="cod"><?php echo $cod; ?></dd>
		<dt>Nombre</dt>
		<dd><?php echo $nom; ?></dd>
		<dt>Medida</dt>
		<dd><?php echo $med; ?></dd>
		<dt>Unidad</dt>
		<dd><?php echo $und; ?></dd>
	</dl>
<?php
}else if($_REQUEST['tra'] == 'save'){

	$cod = $_REQUEST['cod'];
	$pro = $_REQUEST['proid'];
	$pla = $_REQUEST['pla'];
	$cant = $_REQUEST['cant'];

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.matmetrado VALUES('$pro','','$pla','$cod',$cant,'1')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}
if ($_POST['tra'] == 'savepro') {
	$pro = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevoproyecto()");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$pro = $result[0];
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.proyectos(proyectoid, descripcion, fecent, ruccliente, direccion, 
            paisid, departamentoid, provinciaid, distritoid, obser, esid)
			VALUES('".$pro."','".$_POST['nom']."','".$_POST['fec']."'::date,'".$_POST['cli']."','".$_POST['dir']."','".$_POST['pais']."',
			'".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."','".$_POST['obs']."','17')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $pro;
}

if ($_POST['tra'] == 'savesec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.sectores VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."',
							'".$_POST['des']."','".$_POST['obs']."','29');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if($_POST['tra'] == 'savesub'){
	$sub = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevosubproyecto();");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$sub = $result[0];
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.subproyectos(subproyectoid,proyectoid,subproyecto,fecent,obser,esid)
							VALUES('".$sub."','".$_POST['pro']."','".$_POST['des']."','".$_POST['fec']."'::date,'".$_POST['obs']."','26');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $sub;
}else if ($_POST['tra'] == 'savead') {
	$adi = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevoadicionales();");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$adi = $result[0];
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.adicionales VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$adi."',
							'".$_POST['des']."','".$_POST['obs']."','56');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $adi;
}
if ($_POST['tra'] == 'valsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM ventas.adicionales WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(nroplano) LIKE TRIM('".$_POST['sec']."')");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			if ($result[0] >= 1) {
				echo "success";
			}else{
				echo "Fail";
			}
		}
	}else{
		echo "Fail";
	}
	$cn->close($query);
}
if($_POST['tra'] == 'savedata'){

	$cod = $_POST['id'];
	$pro = $_POST['pro'];
	$pla = $_POST['sec'];
	$sub = $_POST['sub'];
	$cant = $_POST['cant'];

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.matmetrado VALUES('$pro','$sub','$pla','$cod',$cant,'1')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'listtbl') {
	$cn = new PostgreSQL();
	$qsql = "";
	$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
							FROM ventas.matmetrado d INNER JOIN admin.materiales m
							ON d.materialesid LIKE m.materialesid
							WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."'
							GROUP BY d.materialesid,m.matnom,m.matmed,m.matund	
							");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td id='tc'>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td id='tc'>".$result['matund']."</td>";
			echo "<td id='tc'>".$result['cant']."</td>";
			echo "</tr>";
		}
	}
	$cn->close();
}
if ($_POST['tra'] == "aproved") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM ventas.proyectopersonal WHERE proyectoid LIKE '".$_POST['pro']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		if ($result[0] == 0) {
			echo "nothing";
		}else{
			echo "success";
		}
	}else{
		echo "nothing";
	}
	$cn->close($query);
}
if ($_POST['tra'] == 'prostatus') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.proyectos SET esid = '59' WHERE proyectoid LIKE '".$_POST['pro']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
?>