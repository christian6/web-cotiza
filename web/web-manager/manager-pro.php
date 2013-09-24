<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Administrador de Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<style>
		#pbar{
			height: .8em;
			margin-top: .4em;
			margin-bottom: -.8em;
		}
	</style>
	<script>
		function addicon(item) {
			$('#'+item.id+' i').addClass("icon-folder-open").removeClass("icon-folder-close");
		}
		function rmicon (item) {
			$('#'+item.id+' i').removeClass("icon-folder-open").addClass("icon-folder-close");
		}
		function pagesec (pro) {
			location.href='manager-sec.php?pro='+pro;
		}
	</script>
</head>
<body>
<?php include ("includes/menu-manager.inc"); ?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Inicio</a>
				<span class="divider">/</span>
			</li>
			<li class="active">
				Admin. Proyecto
			</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h3 class="t-warning">Administrador de Proyecto</h3>
			<hr>
			<table class="table table-hover">
				<tr>
					<th></th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Cliente</th>
					<th>Estado</th>
					<th>Proceso</th>
				</tr>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT p.proyectoid,p.descripcion,c.nombre,e.esnom FROM ventas.proyectos p INNER JOIN admin.estadoes e ON p.esid LIKE e.esid INNER JOIN admin.clientes c ON p.ruccliente LIKE c.ruccliente WHERE p.esid NOT LIKE '17' AND p.esid NOT LIKE '18'  ORDER BY p.proyectoid ASC");
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<tr class='c-yellow-light'>";
								echo "<td><button id='".$result['proyectoid']."' class='btn btn-small btn-warning' OnMouseOut='rmicon(this)' OnMouseOver='addicon(this);' OnClick=pagesec('".$result['proyectoid']."');><i class='icon-folder-close'></i></button></td>";
								echo "<td>".$result['proyectoid']."</td>";
								echo "<td>".$result['descripcion']."</td>";
								echo "<td>".$result['nombre']."</td>";
								echo "<td>".$result['esnom']."</td>";
								echo "<td> <div id='pbar' class='progress'> <div class='bar' style='width: 40%;'></div></div> </td>";
								echo "</tr>";
							}
						}
						$cn->close($query);
					?>
				</tbody>
			</table>
		</div>
	</section>
	<div id="space"></div>
	<footer>
	</footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>