<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}

?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html lang="es-ES">
<head>
	<meta charset="utf-8" />
	<title>Atender Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-atender.css">
	<script type="text/javascript" src="js/atender.js"></script>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
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
	<hgroup>
		<h4>Atender Pedido Nro : <?php echo $_REQUEST['nro'];?></h4>
	</hgroup>
	<article>
		<?php
		$status = $_REQUEST['es'];
		// recuperando el numero de pedido
		$nrop = $_REQUEST['nro'];
			# realizando una conexion hacia la base de datos
			$cn = new PostgreSQL();
			# ecribiendo el query
			$query = $cn->consulta("SELECT p.proyectoid,p.subproyectoid,p.sector,p.proyectoid,v.descripcion as pronom,p.empdni,e.empnom,
									p.fecha::date,p.fecent,p.obser,l.almacenid,l.descri,s.esnom
									FROM almacen.pedido p INNER JOIN ventas.proyectos v
									ON p.proyectoid = v.proyectoid
									INNER JOIN admin.empleados e
									ON p.empdni = e.empdni
									INNER JOIN admin.almacenes l
									ON p.almacenid = l.almacenid
									INNER JOIN admin.estadoes s
									ON p.esid = s.esid
									WHERE  p.esid LIKE '".$status."' AND p.nropedido LIKE '".$nrop."'
									");

			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
				?>
				<div class="row show-grid">
					<div class="span6">
						<div class="row show-grid">
							<div class="span3">
								<label for="lblcod"><b>Codigo Proyecto: </b><?php echo $result['proyectoid'];?></label>
								<label for="lblnom"><b>Nombre Proyecto:</b> <?php echo $result['pronom'];?></label>
								<?php
									$cn2 = new PostgreSQL();
									$query2 = $cn2->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$result['proyectoid']."' AND subproyectoid LIKE '".$result['subproyectoid']."'");
									if ($cn2->num_rows($query2)>0) {
										while ($result2 = $cn2->ExecuteNomQuery($query2)) {
											echo "<label for='lblsub'><b>Subproyecto: </b>".$result2['subproyecto']."</label>";
										}
									}
									$cn2->close($query2);

									$cn2 = new PostgreSQL();
									$query2 = $cn2->consulta("SELECT descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$result['proyectoid']."' AND sector LIKE '".$result['sector']."'");
									if ($cn2->num_rows($query2)>0) {
										while ($result2 = $cn2->ExecuteNomQuery($query2)) {
											echo "<label for='lblsub'><b>Sector:</b>".$result2['descripcion']."</label>";
										}
									}
									$cn2->close($query2);

								?>
								<label for="lblemp"><b>Empleado:</b> <?php echo $result['empnom'];?></label>
								<label for="lblfec"><b>Fecha:</b> <?php echo $result['fecha'];?></label>
								<label for="lblent"><b>Fec. Entrega: </b><?php echo $result['fecent'];?></label>
							</div>
							<div class="span3">
								<label for="lblobs"><b>Observacion:</b></label>
								<?php echo $result['obser'];?>
								<label for="lblal"><b>Almacen:</b> <?php echo $result['descri'];?></label>
								<label for="lbles"><b>Estado:</b> <?php echo $result['esnom'];?></label>
							</div>
						</div>
						<button type="Button" class="btn btn-primary" onclick="atender('<?php echo $nrop;?>');"><i class="icon-shopping-cart icon-white"></i> Despachar</button>
						<button type="Button" class="btn btn-warning"onClick="location.href='verpedido.php'"><i class="icon-chevron-left"></i>Atras</button>
					</div>
				</div>
				<img class='img1' src="../resource/cajas.gif">
				<hr>
				<?php
				}
			}
			$cn->close($query);
			# Detalle de Pedido Revisar la existencia  de los materiales
			?>
			<table class="table table-bordered table-condensed">
				<caption><h6 style="text-align: left;">Detalle de Pedido</h6></caption>
				<thead>
					<th>Chk</th>
					<th>Item</th>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>Medida</th>
					<th>Undidad</th>
					<th>Cantidad</th>
					<th>Stock Actual</th>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT * FROM almacen.sp_consultar_existencia('".$nrop."')");
						if ($cn->num_rows($query)>0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {

								if($status == '37'){
									if ($result['auto'] == '1') {
										if ($result['existencia'] < $result['cantidad']) {
											echo "<tr class='nothing'>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' DISABLED /></td>";
										}else{
											echo "<tr>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
										}
										echo "<td style='text-align:center;'>".$i++."</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align:center;'>".$result['matund']."</td>";
										echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
										echo "<td style='text-align:center;'>".$result['existencia']."</td>";
										echo "</tr>";
									}
								}elseif ($status == '35') {
									if ($result['auto'] == '1') {
										if ($result['existencia'] < $result['cantidad']) {
											echo "<tr class='nothing'>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' DISABLED /></td>";
										}else{
											echo "<tr>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
										}
										echo "<td style='text-align:center;'>".$i++."</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align:center;'>".$result['matund']."</td>";
										echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
										echo "<td style='text-align:center;'>".$result['existencia']."</td>";
										echo "</tr>";
									}
								}else{
									echo "<div class='alert alert-warning'>
										<a class='close' data-dismiss='alert'>x</a>
										<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
										<h4>No se encontraron resultados</h4>
										</div>";
								}

							} # end While
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
	</article>
</section>
<div style="height: 70px;"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(1);
}
?>