<?php
session_start();

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == 'med') {
	$nom = $_REQUEST['nom'];

	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT DISTINCT m.matmed FROM admin.materiales m 
						INNER JOIN almacen.inventario i 
						ON m.materialesid=i.materialesid 
						WHERE TRIM(lower(m.matnom)) LIKE TRIM(lower('".$nom."')) AND i.anio LIKE '".date("Y")."'");
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
	$query = $cn->consulta("SELECT m.materialesid,m.matnom,m.matmed FROM
							admin.materiales m INNER JOIN almacen.inventario i 
							ON m.materialesid = i.materialesid
							WHERE  TRIM(LOWER(m.matnom)) LIKE TRIM(LOWER('".$_REQUEST['nom']."')) AND TRIM(LOWER(m.matmed)) LIKE TRIM(LOWER('".$_REQUEST['med']."')) AND i.anio LIKE '".date("Y")."'
							LIMIT 1 OFFSET 0");
	$cod = "";
	$nom = "";
	$med = "";
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cod = $result['materialesid'];
			$nom = $result['matnom'];
			$med = $result['matmed'];
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

	$npro = '';
	$rz = '';
	$c = new PostgreSQL();
	$q = $c->consulta("SELECT p.descripcion,c.nombre from ventas.proyectos p INNER JOIN admin.clientes c
						ON p.ruccliente LIKE c.ruccliente
						WHERE p.proyectoid LIKE '".$_POST['proid']."';");
	if ($c->num_rows($q) > 0) {
		$res = $c->ExecuteNomQuery($q);
		$npro = $res['descripcion'];
		$rz = $res['nombre'];
	}
	$c->close($q);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) 
							VALUES('".$_SESSION['dni-icr']."','$dni','Asignación de Proyecto $npro',
								'<b>".$_SESSION['nom-icr']."</b>
								<p>
								Por medio de la presente, hago de su conocimiento la asignación oficial del proyecto
								denominado <b>".$npro."</b> con codigo <b>".$pro."</b>, presentado por la empresa <b>".$rz."</b>.
								Tambien queda habilitado el proyecto en esta plataforma para su evaluación y desarrollo.
								</p>','56');");
	$cn->affected_rows($query);
	$cn->close($query);

	$audi = new PostgreSQL();
	$audi->auditoria('ventas_proyectopersonal','INSERT',$_SESSION['dni-icr'],"Asigna $pro -> $dni",'Se asigno el proyecto ID : $pro $npro al usuario DNI '.$_SESSION['dni-icr'].' '.$_SESSION['nom-icr']);

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
}
?>