<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Lista de Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedidoes.css">
	<link href='http://fonts.googleapis.com/css?family=Elsie' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		$(function() {
        	$('.dropdown-toggle').dropdown();
   		 });
	</script>
</head>
<body>
<header>
</header>
<section>
	<?php include("include/menu-al.inc"); ?>
	<div class="container well" style="margin-top: -1em; padding:.1em 0 0 1em;">
		<h3>Atender Pedido al Almacen:</h3>
		<form name="frmal" method="POST" class="form-inline" action="">
			<label for="lblal"><b>Seleccione un Almacen:</b></label>
			<select id="cboal" name="cboal" class="span2" OnClick="this.form.submit()">
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						if ($_POST['cboal'] == $result['almacenid']){
							echo "<option value='".$result['almacenid']."' SELECTED>".$result['descri']."</option>";
						}else{
							echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
						}
					}
				}
				$cn->close($query);
			?>
			</select>
		</form>
		<div id="det">
		<?php if (isset($_POST['cboal'])) { ?>
		<div class="cont">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Nro de Pedido</th>
					<th>Codigo</th>
					<th>Proyecto</th>
					<th>Fecha</th>
					<th>Fecha de Entrega</th>
					<th>Estado</th>
					<th>Atender</th>
					<th>Vista</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("
						SELECT p.nropedido,p.proyectoid,r.descripcion as pronom,p.fecha::date,p.fecent,e.esnom,p.esid
						FROM almacen.pedido p INNER JOIN admin.estadoes e
						ON p.esid = e.esid
						INNER JOIN ventas.proyectos r
						ON p.proyectoid = r.proyectoid
						WHERE p.esid LIKE '35' OR p.esid LIKE '37' AND p.almacenid LIKE '".$_POST['cboal']."'
						ORDER BY p.nropedido ASC
						");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							echo "<td>".$result['nropedido']."</td>";
							echo "<td>".$result['proyectoid']."</td>";
							echo "<td>".$result['pronom']."</td>";
							echo "<td>".$result['fecha']."</td>";
							echo "<td>".$result['fecent']."</td>";
							echo "<td>".$result['esnom']."</td>";
							echo "<td style='text-align:center;'><a href='atender.php?nro=".$result['nropedido']."&es=".$result['esid']."'><img src='../resource/listaapro32.png'></a></td>";
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
		</div>
		<?php } ?>
	</div>
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