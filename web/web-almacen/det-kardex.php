<?php
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es_ES">
<head>
	<meta charset="UTF-8">
	<title>kardex Detallado</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
<section>
<div class="container well">
	<div>
		<h3>Kardex por Producto Detallado</h3>
	</div>
	<?php
$cn = new PostgreSQL();
$query = $cn->consulta("select m.materialesid,m.matnom,m.matmed,i.anio from
						almacen.inventario i inner join admin.materiales m
						on i.materialesid like m.materialesid
						where i.anio like '2013'
						limit 50");
if ($cn->num_rows($query) > 0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
	?>
	<div class="control-group">
		<div class="">
			<label for="label" class="inline"><?php echo $result['materialesid']; ?></label>
			<label for="label" class="inline"><?php echo $result['matnom']; ?></label>
			<label for="label" class="inline"><?php echo $result['matmed']; ?></label>
			<table>
				<?php
					$c = new PostgreSQL();
					$q = $c->consulta("select e.tdoc,e.nrodoc,e.fecha::date,e.stkact,e.cantent,e.cantsal,e.saldo
									from almacen.resumenk e
									where extract(year from e.fecha)::char(4) like '".$result['anio']."' 
									and materialesid like '".$result['materialesid']."'
									");
					if ($c->num_rows($q) > 0) {
						while ($r = $c->ExecuteNomQuery($q)) {
						?>
								<tr>
									<td><?php echo $r['tdoc']; ?></td>
									<td><?php echo $r['nrodoc']; ?></td>
									<td><?php echo $r['fecha']; ?></td>
									<td><?php echo $r['stkact']; ?></td>
									<td><?php echo $r['cantent']; ?></td>
									<td><?php echo $r['cantsal']; ?></td>
									<td><?php echo $r['saldo']; ?></td>
								</tr>
						<?php
						}
					}
					$c->close($q);
				?>
			</table>
		</div>
	</div>
	<?php
	}
}
$cn->close($query);
?>	
</div>
</section>
<div id="space"></div>
<footer></footer>
</body>
</html>