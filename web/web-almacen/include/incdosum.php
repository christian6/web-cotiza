<!DOCTYPE html>
<?php
session_start();

include ("../../datos/postgresHelper.php");

if (isset($_POST['btnanular'])) {
	$cn = new PostgreSQL();
	echo $_POST['nro'];
	$query = $cn->consulta("UPDATE almacen.suministro SET esid = '39' WHERE nrosuministro LIKE '".$_POST['nro']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "<script> self.window.close();	</script>";
}elseif (isset($_POST['btnaprobar'])) {
	$cn = new PostgreSQL();
	echo $_POST['nro'];
	$query = $cn->consulta("UPDATE almacen.suministro SET esid = '38' WHERE nrosuministro LIKE '".$_POST['nro']."'");
	$cn->affected_rows($query);
	$cn->close($query);

	echo "<script> 
	self.window.close();
	</script>";
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Detalle de Orden de Suministro</title>
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint-aprobar-suministro.css">
	<script type="text/javascript" src="../js/aprobar-suministro.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script>
		$(function() {
			$(".btnn").draggable();
			$(".btnn").css("cursor","move")
		});
	</script>
</head>
<body>
<div class="header">
<h3>Detalle de Orden de Suministro</h3>
<hr>
</div>
<section>
	<div id="cabos">
		<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT s.nrosuministro,a.descri,e.empnom,s.fecha::date,s.fecreq,d.esnom
									FROM almacen.suministro s
									INNER JOIN admin.almacenes a
									ON s.almacenid=a.almacenid
									INNER JOIN admin.empleados e
									ON s.empdni=e.empdni
									INNER JOIN admin.estadoes d
									ON s.esid=d.esid
									WHERE s.esid LIKE '40' AND s.nrosuministro LIKE '".$_REQUEST['nros']."'");

			if ($cn->num_rows($query)>0) {
				$result = $cn->ExecuteNomQuery($query);
				?>
				<p><label for="nro">Nro de Orden de Suministro:</label>&nbsp;
				<label><?php echo $_REQUEST['nros'];?></label></p>
				<p><label for="alid">Almacen:</label>&nbsp;
				<label><?php echo $result['descri'];?></label></p>
				<p><label for="lblemp">Empleado:</label>&nbsp;
				<label><?php echo $result['empnom'];?></label></p>
				<p><label for="lblfec">Fecha:</label>&nbsp;
				<label><?php echo $result['fecha'];?></label>&nbsp;&nbsp;&nbsp;
				<label for="lblfreq">Fecha Requerido:</label>&nbsp;
				<label><?php echo $result['fecreq'];?></label>&nbsp;&nbsp;&nbsp;
				<label for="lbles">Estado:</label>&nbsp;
				<label><?php echo $result['esnom'];?></label></p>
				<?php
			}
			$cn->close($query);
		?>
	</div>
	<span class="btnn">
			<form name="frma" method="POST" action="">
				<input type="hidden" value="<?php echo $_REQUEST['nros'];?>" id="nro" name="nro">
				<button type="Submit" id="btnanular" name="btnanular" title="Anular Orden de Suministro"> <img src="../../resource/cerrar32.png"> </button>
			</form>
			<form name="frmp" method="POST" action="">
				<input type="hidden" value="<?php echo $_REQUEST['nros'];?>" id="nro" name="nro">
				<button type="Submit" id="btnaprobar" name="btnaprobar" title="Aprobar Orden de Suminstro"> <img src="../../resource/aprobar32.png"> </button>
			</form>
			<button id="btnsalir" name="btnsalir" title="Salir" onClick="javascript:self.window.close();" > <img src="../../resource/salir32.png"> </button>
	</span>
	<table id="tbldet">
		<thead>
			<th>Item</th>
			<th>Codigo</th>
			<th>Descripcion</th>
			<th>Medida</th>
			<th>Unidad</th>
			<th>Cantidad</th>
		</thead>
		<tbody>
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("
					SELECT d.materialesid,m.matnom,m.matmed,m.matund,d.cantidad FROM almacen.inventario i INNER JOIN almacen.detsuministro d
					ON d.materialesid=i.materialesid
					INNER JOIN admin.materiales m
					ON i.materialesid=m.materialesid
					WHERE d.nrosuministro LIKE '".$_REQUEST['nros']."'
					");
				if ($cn->num_rows($query) > 0 ){
					$i = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td style='text-align:center;'>".$i++."</td>";
						echo "<td>".$result['materialesid']."</td>";
						echo "<td>".$result['matnom']."</td>";
						echo "<td>".$result['matmed']."</td>";
						echo "<td>".$result['matund']."</td>";
						echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
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
</section>
<footer>
</footer>
</body>
</html>