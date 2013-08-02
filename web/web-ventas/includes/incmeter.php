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
	$query = $cn->consulta("SELECT m.materialesid,m.matnom,m.matmed FROM
							admin.materiales m INNER JOIN almacen.inventario i 
							ON m.materialesid = i.materialesid
							WHERE  m.matnom LIKE '".$_REQUEST['nom']."' AND m.matmed LIKE '".$_REQUEST['med']."' AND i.anio LIKE '".date("Y")."'
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

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.matmetrado VALUES('$pro','','$pla','$cod',$cant,'1')");
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
?>