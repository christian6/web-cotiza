<!DOCTYPE html>
<?php
session_start();

include ("../../datos/postgresHelper.php");

if (isset($_REQUEST['matcod'])) {

	# Obteniendo Nro de la Orden de Suministro
	$nros = "";
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.sp_nuevosuministro()");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$nros = $result[0];
	}
	$cn->close($query);
	# Guardando la cabecera de la Order de Suministro
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.suministro(nrosuministro,almacenid,empdni,fecreq,esid) VALUES('$nros','".$_REQUEST['alid']."','".$_REQUEST['empid']."',to_date('".$_REQUEST['fec']."','dd-mm-yyyy'),'40')");
	$cn->affected_rows($query);
	$cn->close($query);

	$mat = explode(",", $_REQUEST['matcod']);

	for ($i=0; $i < count($mat); $i++) {
		$id = $mat[$i];
		$cant = $_REQUEST[$id];
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.detsuministro VALUES('$nros','$id',$cant)");
		$cn->affected_rows($query);
		$cn->close($query);
	}

	echo "<script> self.window.close();</script>";
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Detalle de Orden de Suministro</title>
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint-existencia-all.css">
	<script type="text/javascript" src="../js/existencia-all.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
</head>
<body>
<div>
	<h3>Detalle de Orden de Suministro</h3>
	<hr>
</div>
<section>
	<h4></h4>
	<?php
		$cad = "";
		$mat = explode(",", $_REQUEST['matid']);
		for ($i=0; $i < count($mat); $i++) {
			if ($i == 0) {
				$cad = "'".$mat[0]."' OR i.materialesid LIKE '";
			}
			if ($i != 0 && $i != (count($mat)-1)) {
				$cad .= $mat[$i]."' OR i.materialesid LIKE '";
			}
			if ($i == (count($mat)-1) ) {
				$cad .= $mat[$i]."'";
			}
		}
		$com = "SELECT i.materialesid,m.matnom,m.matmed,m.matund FROM almacen.inventario i INNER JOIN admin.materiales m 
					ON i.materialesid=m.materialesid
					WHERE i.materialesid LIKE ";
	?>
	<form name="frm" method="GET" action="">

	<input type="hidden" id="matcod" name="matcod" value="<?php echo $_REQUEST['matid'];?>">
	<input type="hidden" id="alid" name="alid" value="<?php echo $_REQUEST['alid'];?>">
	<input type="hidden" id="empid" name="empid" value="<?php echo $_REQUEST['empid'];?>">
	<input type="hidden" id="fec" name="fec" value="<?php echo $_REQUEST['fec'];?>">

	<table id="tbldet">
		<caption><button type="Button" onClick="javascript:self.window.close();"> <img src="../../resource/izquierda32.png"></button type="Submit">&nbsp;&nbsp;&nbsp;<button> <img src="../../resource/floppy32.png"> </button></caption>
		<thead>
			<th>Item</th>
			<th>Codigo</th>
			<th>Descripcion</th>
			<th>Medida</th>
			<th>Unidad</th>
			<th>Cantidad</th>
		</thead>
		<tbody>
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta($com.$cad);
				if ($cn->num_rows($query)>0 ){
					$i = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td>".$i++."</td>";
						echo "<td>".$result['materialesid']."</td>";
						echo "<td>".$result['matnom']."</td>";
						echo "<td>".$result['matmed']."</td>";
						echo "<td>".$result['matund']."</td>";
						echo "<td><input type='number' id='".$result['materialesid']."' name='".$result['materialesid']."' title='Ingrese la Cantidad' placeholder='0' >";
						echo "</tr>";
					}
				}
				$cn->close($query);
			?>
		</tbody>
	</table>
	</form>
</section>
<div id="space"></div>
<footer>
</footer>
</body>
</html>