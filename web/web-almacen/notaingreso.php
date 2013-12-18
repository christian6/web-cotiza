<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");

?>
<!doctype html>
<html lang="es-ES">
<head>
	<meta charset="UTF-8">
	<title>Notas de Ingreso</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" href="css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/notaingreso.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<h4>Notas de Ingreso</h4>
			<hr style="margin-top:-.5em;">
			<div class="row show-grid">
			<form name="frm" method="POST" action="">
				<div class="span9">
					<div class="controls">
						<label class="radio inline"><input type="radio" name="rbtn" id="rbtnc" value="c" onChange="radios();"> Codigo</label>
						<label class="radio inline"><input type="radio" name="rbtn" id="rbtnf" value="f" onChange="radios();"> Entre Fechas</label>
					</div>
					<br>
					<div class="row show-grid">
						<div class="span3">
							<div class="control-group">
								<label for="label" class="label label-info">Codigo</label>
								<div class="controls">
									<input type="text" id="txtnro" maxlength="10" name="txtnro" class="span2" placeholder="Nro de Nota Ingreso" title="Ingrese el Nro de la Nota de Ingreso" REQUIRED DISABLED />
								</div>	
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								<label for="label" class="label label-info">Fecha Inicio</label>
								<div class="controls">
									<input type="text" class="span2" id='fecini' name="fecini" placeholder="aaaa-mm-dd" title="Ingrese una fecha" REQUIRED DISABLED />
								</div>
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								<label for="label" class="label label-info">Fecha Fin</label>
								<div class="controls">
									<input type="text" class="span2" id='fecfin' name="fecfin" placeholder="aaaa-mm-dd" title="Ingrese una fecha" DISABLED />
								</div>
							</div>
						</div>
					</div>
					<div class="controls">
						<button type="Submit" name="btns" value="btns" class="btn btn-success"><i class="icon-search icon-white"></i> Buscar</button>
					</div>
				</div>
			</form>
			</div>
			<hr style="margin-top: -.5em;">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
					<th>Item</th>
					<th>Nro Documento</th>
					<th>Nro Compra</th>
					<th>Fecha Ingreso</th>
					<th>Almacen</th>
					<th>Ver</th>
					<th>Reporte</th>
					<th>Anular</th>
				</tr>
				</thead>
				<tbody>
					<?php
					if($_POST['btns'] == 'btns'){
						$cn = new PostgreSQL();
						$qsql = "SELECT n.nroningreso,n.nrocompra,n.fecha::date,n.almacenid,a.descri,(SELECT COUNT(*) FROM almacen.rptinspeccion WHERE nroningreso LIKE n.nroningreso) as report
								FROM almacen.notaingreso n INNER JOIN admin.almacenes a
								ON n.almacenid LIKE a.almacenid";
						if ($_POST['rbtn'] == "c") {
							$qsql .= " WHERE n.nroningreso LIKE '".$_POST['txtnro']."' AND n.esid LIKE '51'";
						}elseif ($_POST['rbtn'] == "f") {
							if ($_POST['fecini'] != "" && $_POST['fecfin'] == "") {
								$qsql .= " WHERE n.fecha::date = '".$_POST['fecini']."'::date AND n.esid LIKE '51'";
							}elseif ($_POST['fecini'] != "" && $_POST['fecfin'] != "") {
								$qsql .= " WHERE n.fecha::date BETWEEN '".$_POST['fecini']."'::date AND '".$_POST['fecfin']."'::date AND n.esid LIKE '51'";
							}
						}
						$query = $cn->consulta($qsql);
						if ($cn->num_rows($query) > 0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								?>
									<tr>
										<td style="text-align: center"><?php echo $i++; ?></td>
										<td><?php echo $result['nroningreso']; ?></td>
										<td><?php echo $result['nrocompra']; ?></td>
										<td style="text-align: center"><?php echo $result['fecha']; ?></td>
										<td><?php echo $result['descri']; ?></td>
										<td style="text-align: center"><a href="javascript:view('<?php echo $result['nroningreso']; ?>');"><i class="icon-eye-open"></i></a></td>
										<?php 
											if ($result['report'] > 0) {
												?>
												<td style="text-align: center"><a href="javascript:viewins('<?php echo $result['nroningreso']; ?>');"><i class="icon-list"></i></a></td>
												<?php
											}else{
												?>
												<td></td>
												<?php
											}
										 ?>
										 <td style="text-align: center"><a href="javascript:"><i class="icon-remove"></i></a></td>
									</tr>
								<?php
							}
						}else{
							echo "<div class='alert alert-warning'>
										<a class='close' data-dismiss='alert'>x</a>
										<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
										<h4>No se encontraron resultados</h4>
										</div>";
						}
						$cn->close($query);
					}
					?>
				</tbody>
			</table>
		</div>
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
	redirect(0);
}
?>