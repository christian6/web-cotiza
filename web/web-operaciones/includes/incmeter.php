<?php

session_start();

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == 'med') {
	$nom = $_REQUEST['nom'];

	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT DISTINCT m.matmed FROM admin.materiales m 
						INNER JOIN almacen.inventario i 
						ON m.materialesid=i.materialesid 
						WHERE TRIM(LOWER(m.matnom)) LIKE TRIM(LOWER('".$nom."')) AND i.anio LIKE '".date("Y")."' ORDER BY m.matmed ASC");
	if ($cn->num_rows($query)>0) {
		echo "<select class='span4' name='cbomed' id='cbomed' onClick='showdet();' onChange='showdet();'>";
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
							WHERE  TRIM(LOWER(m.matnom)) LIKE TRIM(LOWER('".$_REQUEST['nom']."')) AND 
							TRIM(LOWER(m.matmed)) LIKE TRIM(LOWER('".$_REQUEST['med']."')) AND i.anio LIKE '".date("Y")."'
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
	<dl class="dl-horizontal" style="margin-left: -10%;">
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
	$sub = $_REQUEST['sub'];
	$sql = "";
	if ($sub == "") {
		$sql .= "INSERT INTO operaciones.matmetrado VALUES('$pro','','$pla','$cod',$cant,'1')";
	}else if($sub != ""){
		$sql .= "INSERT INTO operaciones.matmetrado VALUES('$pro','$sub','$pla','$cod',$cant,'1')";
	}

	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";

}elseif ($_REQUEST['tra'] == 'sper') {
	/* Proviene de otra pagina */
	$pro = $_POST['proid'];
	$dni = $_POST['dni'];

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.proyectopersonal VALUES('$pro','$dni')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}

if ($_POST['tra'] == 'conedit') {
	$sql = "SELECT o.materialesid,m.matnom,m.matmed,SUM(o.cant) as cant 
			FROM operaciones.matmetrado o INNER JOIN admin.materiales m
			ON o.materialesid LIKE m.materialesid
			WHERE ";
	if ($sub != "") {
		$sql .= "o.proyectoid LIKE '".$_POST['pro']."' AND TRIM(o.subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(o.sector) LIKE TRIM('".$_POST['sec']."') AND o.materialesid LIKE '".$_POST['mid']."' GROUP BY o.materialesid,m.matnom,m.matmed";
	}else if ($sub == "") {
		$sql .= "o.proyectoid LIKE '".$_POST['pro']."' AND TRIM(o.sector) LIKE TRIM('".$_POST['sec']."') AND o.materialesid LIKE '".$_POST['mid']."' GROUP BY o.materialesid,m.matnom,m.matmed";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		echo $result['materialesid']."|".$result['matnom']."|".$result['matmed']."|".$result['cant'];
	}else{
		echo "none";
	}
	$cn->close($query);
}else if ($_POST['tra'] == 'upcant') {
	$sqld = "DELETE FROM operaciones.matmetrado WHERE ";
	$sqlu = "INSERT INTO operaciones.matmetrado ";
	if ($_POST['sub'] == "") {
		$sqld .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['matid']."'";
		$sqlu .= "VALUES('".$_POST['pro']."','','".$_POST['sec']."','".$_POST['matid']."',".$_POST['cant'].",'1')";
	}else if ($_POST['sub'] != "") {
		$sqld .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['matid']."'";
		$sqlu .= "VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['matid']."',".$_POST['cant'].",'1')";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($sqld);
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta($sqlu);
	$cn->affected_rows($query);
	$cn->close($query);

	echo "hecho";
}elseif ($_POST['tra'] == 'delmat') {
	$sql = "DELETE FROM operaciones.matmetrado WHERE ";
	if ($_POST['sub'] == "") {
		$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['matid']."'";
	}else if ($_POST['sub'] != "") {
		$sql .= " proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['matid']."'";
	}
	$cn = new PostgreSQL();
	$query = $cn->consulta($sql);
	$cn->affected_rows($query);
	$cn->close($query);

	echo "hecho";
}else if($_POST['tra'] == 'list'){
?>
<table class="table table-hover table-condensed table-bordered">
	<thead>
		<th>Item</th>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Medida</th>
		<th>Unidad</th>
		<th>Cantidad</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</thead>
	<tbody>
		<?php
		$cn = new PostgreSQL();
		$sql = "SELECT o.materialesid,m.matnom,m.matmed,m.matund,SUM(o.cant) as cant 
			FROM operaciones.matmetrado o INNER JOIN admin.materiales m
			ON o.materialesid LIKE m.materialesid
			WHERE o.proyectoid LIKE '".$_POST['pro']."' AND TRIM(o.subproyectoid) LIKE TRIM('".$_POST['sub']."') AND 
			TRIM(o.sector) LIKE TRIM('".$_POST['sec']."') 
			GROUP BY o.materialesid,m.matnom,m.matmed,m.matund ORDER BY m.matnom ASC";
		$query = $cn->consulta($sql);
		if ($cn->num_rows($query) > 0) {
			$i = 1;
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<tr>";
				echo "<td id='tc'>".$i++."</td>";
				echo "<td id='tc'>".$result['materialesid']."</td>";
				echo "<td>".$result['matnom']."</td>";
				echo "<td>".$result['matmed']."</td>";
				echo "<td id='tc'>".$result['matund']."</td>";
				echo "<td id='tc'>".$result['cant']."</td>";
				echo "<td id='tc'><Button class='btn btn-info' onClick='showedit(".$result['materialesid'].");'><i class='icon-edit'></i></td>";
				echo "<td id='tc'><Button class='btn btn-danger' onClick='showdel(".$result['materialesid'].");'><i class='icon-minus'></i></td>";
				echo "</tr>";
			}
		}
		$cn->close($query);
		?>
	</tbody>
</table>
<?php
}
if ($_POST['tra'] == 'saveobs') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.alertaspro VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_SESSION['dni-icr']."',
							'".$_POST['top']."','".$_POST['obs']."','1')");
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.mensaje(empdni, fordni, question, body, esid)
						VALUES('".$_SESSION['dni-icr']."','".$_POST['top']."','OBSERVACION ".$_POST['pro']." ".$_POST['sub']." ".$_POST['sec']."','".$_POST['obs']."','56');");
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$cn->auditoria('VENTAS_ALERTASPRO','INSERT',$_SESSION['dni-icr'],'SECTOR DESAPROBADO > VENTAS PROYECTO '.$_POST['pro'].' SECTOR'.$_POST['sec'],$_POST['pro'].' '.$_POST['sub'].' '.$_POST['sec'].$_POST['obs']);

	echo "success";
}

?>