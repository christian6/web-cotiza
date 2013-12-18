<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect(0);
	}

include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Aprobar Pedidos</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedidoes.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		$(function () {
			$(" .dropdown-toggle").dropdown();
		});
	</script>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<!--<hgroup>
		<img src="../resource/icrlogo.png">
			<div id="cab">
				<h1>Especialistas en Sistemas Contra Incendios</h1>
			</div>
	</hgroup>
</header>
<div id="sess">
	<?php/*
	$nom = $_SESSION['nom-icr'];
	$car = $_SESSION['car-icr'];
	$dni = $_SESSION['dni-icr'];
	$user = $_SESSION['user-icr'];*/
	?>
	<p>
	<label for="user" style="font-weight: bold;">Cargo:</label>
	<?echo $car;?>&nbsp;
	<label for="nom" style="font-weight: bold;">Nombre: </label>
	<?echo $nom;?>
	</p>
	<p>
	<label style="font-weight: bold;">Dni:</label>
	&nbsp;<?echo $dni; ?>&nbsp;
	<label style="font-weight: bold;">User:</label>
	<?echo $user;?>
	<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
	</p>
</div>-->
<section>
	<hgroup>
		<h4>Listado de Pedido Pendientes por Aprobar</h4>
	</hgroup>
	<div class="cont">
		
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Item</th>
					<th>Nro de Pedido</th>
					<th>Codigo</th>
					<th>Proyecto</th>
					<th>Fecha</th>
					<th>Fecha de Entrega</th>
					<th>Estado</th>
					<th>Aprobar</th>
					<th>Vista</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("
						SELECT p.nropedido,p.proyectoid,r.descripcion as pronom,p.fecha::date,p.fecent,e.esnom
						FROM almacen.pedido p INNER JOIN admin.estadoes e
						ON p.esid = e.esid
						INNER JOIN ventas.proyectos r
						ON p.proyectoid = r.proyectoid
						WHERE p.esid LIKE '32'
						ORDER BY p.nropedido ASC
						");
					if ($cn->num_rows($query)>0) {
						$i = 1;
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							echo "<td style='text-align:center;'>".$i++."</td>";
							echo "<td>".$result['nropedido']."</td>";
							echo "<td>".$result['proyectoid']."</td>";
							echo "<td>".$result['pronom']."</td>";
							echo "<td>".$result['fecha']."</td>";
							echo "<td>".$result['fecent']."</td>";
							echo "<td>".$result['esnom']."</td>";
							echo "<td style='text-align:center;'><a href='aprobar.php?nro=".$result['nropedido']."'><img src='../resource/caja32.png'></a></td>";
							echo "<td style='text-align:center;'><a href='../reports/almacen/pdf/rptpedidomat.php?nro=".$result['nropedido']."' target='_blank'><img src='../resource/vistaprevia.png'></a></td>";
							echo "</tr>";
						}
					}else{
						echo "<div class='alert alert-warning'>
								<a class='close' data-dismiss='alert'>x</a>
								<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
								<h4>No se encontraron resultados</h4>
								</div>";
					}
					$cn->close($query);
				?>
			</tbody>
		</table>
		</div>
</section>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>