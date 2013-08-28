<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Lista de Venta</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script type="text/javascript" src="../web-almacen/js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
	<?php #include("includes/menu-manager.inc"); ?>
	<!--<header></header>-->
	<section>
		<div class="container well">
			<h4>Lista de Operaciones</h4>
			<dl class="dl-horizontal">
				<?php
				$sql = "";
				if($_GET['sub'] == ""){
					$sql = "SELECT p.descripcion,t.sector 
							FROM ventas.proyectos p INNER JOIN ventas.sectores t
							ON p.proyectoid LIKE t.proyectoid
							WHERE p.proyectoid LIKE '".$_GET['pro']."' AND TRIM(t.nroplano) LIKE '".$_GET['sec']."'";
				}else if($_GET['sub'] != ""){
					$sql = "SELECT p.descripcion,s.subproyecto,t.sector 
							FROM ventas.proyectos p INNER JOIN ventas.sectores t
							ON p.proyectoid LIKE t.proyectoid
							INNER JOIN ventas.subproyectos s
							ON p.proyectoid LIKE s.proyectoid
							WHERE p.proyectoid LIKE '".$_GET['pro']."' AND s.subproyectoid LIKE TRIM('".$_GET['sub']."') AND TRIM(t.nroplano) LIKE '".$_GET['sec']."'";
				}
				# echo $sql;
				$cn = new PostgreSQL();
				$query = $cn->consulta($sql);
				if($cn->num_rows($query) > 0){
					$result = $cn->ExecuteNomQuery($query);
				}
				?>
				<dt>Proyecto</dt>
				<dd><?php echo $result['descripcion']; ?></dd>
				<?php if ($_GET['sub'] != "") {
					echo "<dt>Sub Proyecto</dt>";
					echo "<dd>".$result['subproyecto']."</dd>";
				} ?>
				<dt>Sector</dt>
				<dd><?php echo $result['sector']; ?></dd>
			</dl>
			<hr class="hs">
			<table class="table table-bordered table-hover">
				<thead>
					<th>Item</th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Cantidad</th>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						$qsql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
								FROM operaciones.matmetrado d INNER JOIN admin.materiales m
								ON d.materialesid LIKE m.materialesid
								INNER JOIN ventas.proyectos p
								ON d.proyectoid LIKE p.proyectoid ";

						if ($subpro == "") {
							$qsql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
						}elseif ($subpro != "") {
							$qsql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM('d.subproyectoid') LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
						}
						$query = $cn->consulta($qsql);
						if ($cn->num_rows($query) > 0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<tr>";
								echo "<td>".$i++."</td>";
								echo "<td>".$result['materialesid']."</td>";
								echo "<td>".$result['matnom']."</td>";
								echo "<td>".$result['matmed']."</td>";
								echo "<td>".$result['matund']."</td>";
								echo "<td>".$result['cant']."</td>";
								echo "</tr>";
							}
						}
						$cn->close($query);
					?>
				</tbody>
			</table>
			<div class="">
					<div class="well c-yellow-light">
						<h4 class="t-warning">Observaciones de Operaciones</h4>
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT sector,obser FROM ventas.alertaspro WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."'");
							if ($cn->num_rows($query) > 0) {
								echo "<div class='alert alet-block alert-info'>";
								echo "<ul>";
								while($result = $cn->ExecuteNomQuery($query)){
									echo "<li>";
									echo "<strong>".$result['sector']."</strong>";
									echo "<p>".$result['obser']."</p>";
									echo "</li>";
								}
								echo "</ul>";
								echo "</div>";
							}
							$cn->close($query);
						?>
					</div>
				</div>
		</div>
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>
