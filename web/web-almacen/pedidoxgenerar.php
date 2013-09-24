<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");

function valpedido($nrop='')
{
	$res = 0;
	$g = 0;
	$n = 0;
	if ($nrop != "") {

		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT COUNT(nropedido) FROM almacen.guiaremision WHERE nropedido LIKE TRIM('$nrop')");
		if ($cn->num_rows($query)>0) {
			$r = $cn->ExecuteNomQuery($query);
			$g = $r[0];
		}
		$cn->close($query);
		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT COUNT(nropedido) FROM almacen.notasalida WHERE nropedido LIKE TRIM('$nrop')");
		if ($cn->num_rows($query)>0) {
			$r = $cn->ExecuteNomQuery($query);
			$n = $r[0];
		}
		$cn->close($query);
		$res = $g + $n;
	}
	return $res;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Pedidos por Generar Documentos de Salida</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		$(function() {
			$( ".dropdown-toggle" ).dropdown();
		});
	</script>
</head>
<body data-spy="scroll" data-offset="50" data-twttr-rendered="true">
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
	<div class="container well">
		<h4>Pedidos Atendidos que faltan generar documento de salida</h4>
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
		<hr>
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<th>Item</th>
				<th>Nro Pedido</th>
				<th>Proyecto</th>
				<th>Fecha Pedido</th>
				<th>Fecha Reque.</th>
				<th>Estado</th>
				<th>Generar Doc</th>
				<th>Ver</th>
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
						WHERE p.esid LIKE '36' OR p.esid LIKE '37' AND p.almacenid LIKE '".$_POST['cboal']."'
						ORDER BY p.nropedido ASC
						");
					if ($cn->num_rows($query)>0) {
						$i = 1;
						while ($result = $cn->ExecuteNomQuery($query)) {
							if (valpedido($result['nropedido']) <= 0) {
								echo "<tr>";
								echo "<td style='text-align:center;'>".$i++."</td>";
								echo "<td>".$result['nropedido']."</td>";
								echo "<td>".$result['pronom']."</td>";
								echo "<td>".$result['fecha']."</td>";
								echo "<td>".$result['fecent']."</td>";
								echo "<td style='text-align:center;'>".$result['esnom']."</td>";
								echo "<td style='text-align:center;'><a href='generardoc.php?nro=".$result['nropedido']."'><i class='icon-edit'></i></a></td>";
								echo "<td style='text-align:center;'><a href='../reports/almacen/pdf/rptpedidomat.php?nro=".$result['nropedido']."' target='_blank'><i class='icon-eye-open'><i></a></td>";
								echo "</tr>";
							}
						}
						if ($i == 1) {
							echo "<div class='alert alert-warning'>
								<a class='close' data-dismiss='alert'>x</a>
								<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
								<h4>No se encontraron resultados</h4>
								</div>";
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