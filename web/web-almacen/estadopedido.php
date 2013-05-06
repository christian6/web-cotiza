<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(0);
	}

include ("../datos/postgresHelper.php");
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Pedidos</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedidoes.css">
	<link href='http://fonts.googleapis.com/css?family=Elsie' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
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
<section>
	<div class="container well">
		<h3>Estado de Pedidos al Almacen de: <?php echo $nom;?></h3>
		<form class="form-inline" name="frmst" method="POST" action="">
			<label for="lblcbo">Seleccione Estado:</label>
			<select id="cboestado" name="cboestado" REQUIRED>
				<option>--Seleccione--</option>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE esid LIKE '32' OR esid LIKE '33' OR esid LIKE '35' OR esid LIKE '36'");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ($_POST['cboestado'] == $result['esid']) {
								echo "<option value='".$result['esid']."' SELECTED >".$result['esnom']."</option>";
							}else{
								echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
							}
						}
					}
					$cn->close($query);
				?>
			</select>
			<label>Realizado por:</label>
			<select name="cboemp">
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT empdni,empnom FROM admin.empleados WHERE esid LIKE '19'");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ($_POST['cboemp'] == $result['empdni']) {
								echo "<option value='".$result['empdni']."' SELECTED >".$result['empnom']."</option>";
							}else{
								echo "<option value='".$result['empdni']."'>".$result['empnom']."</option>";
							}
						}
					}
					$cn->close($query);
				?>
			</select>
			<button class="btn" type="Submit"><i class="icon-search"></i> BUscar</button>
		</form>
		<hr>
		<?php if ($_POST['cboestado'] != "") {
		?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Nro de Pedido</th>
					<th>Fecha</th>
					<th>Fecha de Entrega</th>
					<th>Estado</th>
					<th>Ver Detalle</th>
					<th>Enviar</th>
					<th>Vista</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("
						SELECT p.nropedido,p.fecha::date,p.fecent,e.esnom
						FROM almacen.pedido p INNER JOIN admin.estadoes e
						ON p.esid = e.esid
						WHERE p.empdni LIKE '".$_POST['cboemp']."' AND p.esid LIKE '".$_POST['cboestado']."'
						");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							echo "<td>".$result['nropedido']."</td>";
							echo "<td>".$result['fecha']."</td>";
							echo "<td>".$result['fecent']."</td>";
							echo "<td>".$result['esnom']."</td>";
							echo "<td style='text-align:center;'><a href='deestadopedido.php?nro=".$result['nropedido']."' target='_Blank'><img src='../resource/detalle32.png'></a></td>";
							echo "<td style='text-align:center;'><a href='maillogistica.php?nro=".$result['nropedido']."' target='_self'><img src='../resource/mail32.png'></a></td>";
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
			<tfoot>
			</tfoot>
		</table>
			<?php }?>
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