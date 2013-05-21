<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Detalle de Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
<section>
<div class="container well">
<?php
if($_REQUEST['nro'] != null) {

include ("../datos/postgresHelper.php");

echo "<h4 style='text-align:center;'>Detalle de Pedido: Nro ".$_REQUEST['nro']."</h4>";

echo "<br />";

echo "<table class='table table-bordered table-hover'>";
echo "<thead>";
echo "<th>Item</th>";
echo "<th>Codigo</th>";
echo "<th>Descripción</th>";
echo "<th>Medida</th>";
echo "<th>Unidad</th>";
echo "<th>Cantidad</th>";
echo "</thead>";
echo "<tbody>";
$cn = new PostgreSQl();
$query = $cn->consulta("SELECT * FROM almacen.spconsultardetpedidomat('".$_REQUEST['nro']."')");
if ($cn->num_rows($query)>0) {
	$i = 0;
	while ($result = $cn->ExecuteNomQuery($query)) {
		$i++;
		echo "<tr>";
		echo "<td style='text-align:center;'>$i</td>";
		echo "<td>".$result['materialesid']."</td>";
		echo "<td>".$result['matnom']."</td>";
		echo "<td>".$result['matmed']."</td>";
		echo "<td style='text-align:center;'>".$result['matund']."</td>";
		echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
	}
}else{
	echo "<div class='alert alert-warning'>
		<a class='close' data-dismiss='alert'>x</a>
		<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
		<h4>No se encontraron resultados</h4>
		</div>";
}
	$cn->close($query);
}
echo "</tbody>";
echo "</table>";
?>
</div>
</section>
<footer>
</footer>
</body>
</html>

