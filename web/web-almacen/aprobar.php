<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
	include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="uft-8" />
	<title>Aprobar Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedidoes.css">
	<script type="text/javascript" src="js/aprobar.js"></script>
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
<header>
</header>
<section>
<?php include("include/menu-al.inc"); ?>
	<hgroup>
		<h4>Pedido al Almacen Nro: <label for="lblnro" id="lblnro"><?php echo $_GET['nro'];?></label></h4>
	</hgroup>
	<article>
		<?php
		// recuperando el numero de pedido
		$nrop = $_GET['nro'];
		if (isset($nrop)) {
			# realizando una conexion hacia la base de datos
			$cn = new PostgreSQL();
			# ecribiendo el query
			$query = $cn->consulta("SELECT p.proyectoid,p.subproyectoid,p.sector,p.proyectoid,r.descripcion as pronom,p.empdni,e.empnom,p.fecha::date,p.fecent,p.obser,p.almacenid,a.descri,s.esnom ".
									"FROM almacen.pedido p INNER JOIN ventas.proyectos r ".
									"ON p.proyectoid=r.proyectoid ".
									"INNER JOIN admin.empleados e ".
									"ON p.empdni=e.empdni ".
									"INNER JOIN admin.almacenes a ".
									"ON p.almacenid=a.almacenid ".
									"INNER JOIN admin.estadoes s ".
									"ON p.esid=s.esid ".
									"WHERE p.nropedido LIKE '".$nrop."' AND p.esid LIKE '32'");

			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
				?>
				<div class="row show-grid">
					<div class="span6 well">
						<div class="row show-grid">
							<div class="span3">
								<label for="lblcod"><b>Codigo Proyecto:</b> <?php echo $result['proyectoid'];?></label>
								<label for="lblnom"><b>Nombre de Proyecto:</b> <?php echo $result['pronom'];?></label>
							<?php
							$cn2 = new PostgreSQL();
							$query2 = $cn2->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$result['proyectoid']."' AND subproyectoid LIKE '".$result['subproyectoid']."'");
							if ($cn2->num_rows($query2)>0) {
								while ($result2 = $cn2->ExecuteNomQuery($query2)) {
									echo "<label for='lblsub'><b>Subproyecto:</b> ".$result2['subproyecto']."</label>";
								}
							}
							$cn2->close($query2);

							$cn2 = new PostgreSQL();
							$query2 = $cn2->consulta("SELECT descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$result['proyectoid']."' AND sector LIKE '".$result['sector']."'");
							if ($cn2->num_rows($query2)>0) {
								while ($result2 = $cn2->ExecuteNomQuery($query2)) {
									echo "<label for='lblsub'><b>Sector:</b> ".$result2['descripcion']."</label>";
								}
							}
							$cn2->close($query2);

							?>
								<label for="lblemp"><b>Empleado:</b> <?php echo $result['empnom'];?></label>
								<label for="lblfec"><b>Fecha:</b> <?php echo $result['fecha'];?></label>
								<label for="lblent"><b>Fec. Entrega:</b> <?php echo $result['fecent'];?></label>
							</div>
							<div class="span3">
								<label for="lblobs"><b>Observacion:</b></label>
								<?php echo $result['obser'];?>
								<label for="lblal"><b>Almacen:</b> <?php echo $result['descri'];?></label>
								<label for="lbles"><b>Estado:</b> <?php echo $result['esnom'];?></label>
						</div>
							</div>
							<button type="Button" class="btn btn-primary" onclick="aprobar('<?php echo $nrop;?>');" title="Aprobar Pedido"><i class="icon-ok icon-white"></i> Aprobar</button>
							<button type="Button" class="btn btn-warning" onclick="anular();" title="Anular Pedido"><i class="icon-remove icon-white"></i> Anular</button>
						</div>
					</div>
				<?php
				}
			}
			$cn->close($query);

			# Detalle de Pedido
			?>
			<div class="cont">
			<table class="table table-striped table-bordered">
				<caption><h5 style="text-align: left;">Detalle de Pedido</h5></caption>
				<thead>
					<tr>
						<th>Item</th>
						<th>Codigo</th>
						<th>Descripción</th>
						<th>Medida</th>
						<th>Unidad</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM almacen.spconsultardetpedidomat('".$nrop."')");
  					if ($cn->num_rows($query)>0) {
    					$i = 1;
    					while($fila = $cn->ExecuteNomQuery($query)){
    						echo "<tr>";
    						echo "<td style='text-align: center;'>".$i++."</td>";
    						echo "<td>".$fila['materialesid']."</td>";
    						echo "<td>".$fila['matnom']."</td>";
    						echo "<td>".$fila['matmed']."</td>";
    						echo "<td style='text-align: center;'>".$fila['matund']."</td>";
    						echo "<td style='text-align: center;'>".$fila['cantidad']."</td>";
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
			<?php
		}
		?>
	</article>
</section>
<div style="height: 70px;"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>