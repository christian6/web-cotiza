<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Ingreso de Materiales al Inventario</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/ingxcompra.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<h4>Ingreso de Materiales con Orden de Compra</h4>
			<!--<div class="row">-->
				<form action="" name="frm" method="POST">
					<div class="row show-grid">
						<div class="span9">
							<div class="control-group">
								<label class="radio inline"><input type="radio" id="rbtnc" name="rbtn" value="c" onChange="radios();" /> Nro de Compra</label>
								<label class="radio inline"><input type="radio" id="rbtnf" name="rbtn" value="f" onChange="radios();" /> Entre Fechas</label>
							</div>
							<div class="row show-grid">
								<div class="span3">
									<div class="control-group">
										<label class="label label-info">Nro Compra </label>
										<div class="controls">
											<input type="text" name="txtnco" id="txtnco" class="span2" REQUIRED DISABLED/>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="label label-info">Fecha Inicio </label>
										<div class="controls">
											<input type="text" name="txtfini" id="txtfini" class="span2" REQUIRED DISABLED/>
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="label label-info">Fecha Fin </label>
										<div class="controls">
											<input type="text" name="txtffin" id="txtffin" class="span2" DISABLED/>
										</div>
									</div>
								</div>
							</div>
							<button type="Submit" class="btn btn-success" name="btns" value="btns"><i class="icon-search icon-white"></i> Buscar</button>
						</div>
					</div>
				</form>
			<!--</div>-->
			<hr>
			<div class="container">
				<?php
				if ($_POST['btns'] == 'btns') {
				?>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Item</th>
							<th>Nro Compra</th>
							<th>Proveedor</th>
							<th>Registrado</th>
							<th>Fecha</th>
							<th>Estado</th>
							<th>Ver</th>
							<th>Recibir</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$cn = new PostgreSQL();
						$qsql = "SELECT c.nrocompra,c.rucproveedor,p.razonsocial,c.fecha::date,c.fecent,e.esnom 
								FROM logistica.compras c INNER JOIN admin.proveedor p
								ON c.rucproveedor = p.rucproveedor
								INNER JOIN admin.estadoes e
								ON c.esid = e.esid
								 ";
						$lq = strlen($qsql);
						if ($_POST['rbtn'] == "c") {
							$qsql .= "WHERE nrocompra LIKE '".$_POST['txtnco']."' AND c.esid NOT LIKE '13'  ORDER BY c.fecha ASC";
						}elseif ($_POST['rbtn'] == "f") {
							if ($_POST['txtfini']!="" && $_POST['txtffin']=="") {
								$qsql .= " WHERE fecha::date = '".$_POST['txtfini']."'::date  AND c.esid NOT LIKE '13' ORDER BY c.fecha ASC";
							}elseif ($_POST['txtfini']!="" && $_POST['txtffin']!="") {
								$qsql .= " WHERE fecha::date BETWEEN '".$_POST['txtfini']."'::date AND '".$_POST['txtffin']."'::date  AND c.esid NOT LIKE '13' ORDER BY c.fecha ASC";
							}
						}
						if (strlen($qsql) > $lq) {
						$query = $cn->consulta($qsql);
						if ($cn->num_rows($query) > 0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<tr>";
								echo "<td style='text-align: center;'>".$i++."</td>";
								echo "<td style='text-align: center;'>".$result['nrocompra']."</td>";
								echo "<td>".$result['razonsocial']."</td>";
								echo "<td style='text-align: center;'>".$result['fecha']."</td>";
								echo "<td style='text-align: center;'>".$result['fecent']."</td>";
								echo "<td>".$result['esnom']."</td>";
								?>
								<td style='text-align: center;'><a href="javascript:view('<?php echo $result['nrocompra']; ?>','<?php echo $result['rucproveedor']; ?>');"><i class='icon-eye-open'></i></a></td>
								<td style='text-align: center;'><a href="javascript:recibir('<?php echo $result['nrocompra']; ?>');")><i class='icon-th-large'></i></a></td>
								<?php
								echo "<tr>";
							}
						}else{
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
	redirect(1);
}
?>