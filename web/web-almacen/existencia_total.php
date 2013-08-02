<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Existencia de Pedido y Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/existencia-total.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h3>Existencia de Pedido y Proyectos Aprobados</h3>
			<div class="row show-grid">
				<div class="span12">
					<form action="" method="POST">
					<div class="control-group">
						<label for="controls">Almacen: </label>
						<div class="controls">
							<select name="cboal" id="cnoal">
								<?php
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
								if ($cn->num_rows($query) > 0) {
									while ($ressult = $cn->ExecuteNomQuery($query)) {
										echo "<option value='".$ressult['almacenid']."'>".$ressult['descri']."</option>";
									}
								}
								$cn->close($query);
								?>
							</select>
							<button type="Submit" class="btn btn-info t-d"><i class="icon-search"></i> Buscar</button>
						</div>
					</div>
					</form>
					<div class="row show-grid">
						<div class="span12">
							<table class="table table-condensed table-hover">
								<caption>
									<div class="btn-group pull-left">
										<button class="btn btn-success t-d" onClick="checkall();"><i class="icon-check"></i> Todo</button>
										<button class="btn btn-success t-d" onClick="descheckall();"><i class="icon-remove-circle"></i> Limpiar</button>
										<button class="btn btn-success t-d"><i class="icon-remove"></i> Limpiar Temporales</button>
										<button class="btn btn-success t-d" onClick="showquets();"><i class="icon-shopping-cart"></i> Suministro</button>
									</div>
								</caption>
								<thead>
									<th></th>
									<th>Item</th>
									<th>Codigo</th>
									<th>Nombre</th>
									<th>Medida</th>
									<th>Cantidad</th>
									<th>Stock</th>
									<th>Saldo</th>
								</thead>
								<tbody>
									<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("SELECT * FROM almacen.sp_consulta_total_pedido_proyecto('".$_POST['cboal']."')");
									if ($cn->num_rows($query) > 0) {
										$i = 1;
										while ($result = $cn->ExecuteNomQuery($query)) {
											echo "<tr class='c-warning'>";
											echo "<td id='tc'><input type='CheckBox' name='matids' id='".$result['materialesid']."' value='".$result['sal']."' /></td>";
											echo "<td id='tc'>".$i++."</td>";
											echo "<td id='tc'>".$result['materialesid']."</td>";
											echo "<td>".$result['matnom']."</td>";
											echo "<td>".$result['matmed']."</td>";
											echo "<td id='tc'>".$result['cant']."</td>";
											echo "<td id='tc'>".$result['stock']."</td>";
											echo "<td id='tc'>".$result['sal']."</td>";
											echo "</tr>";
										}
									}
									$cn->close($query);
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="mquest" class="modal c-blue-light fade in hide">
			<div class="modal-header ">
				<a class="close" data-dismiss="modal">x</a>
				<h4 class="t-info">Question</h4>
				<p class="t-info">
					Desea poner <strong>cantidad</strong> a los materiales?.
					<label class="radio t-info"><input type="radio" name="rbtn" id="rd" value="d"> Cantidad por defecto.</label>
					<label class="radio t-info"><input type="radio" name="rbtn" id="rc" value="c"> Ingresar cantidad.</label>
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-info" onClick="quest();"><i class="icon-ok"></i> Continuar</button>
			</div>
		</div>
		<div id="mdet" class="modal fade in hide" data-backdrop="static">
			<div class="modal-header c-green-light">
				<a class="close" data-dismiss="modal">x</a>
				<h4 class="t-success">Agregando Cantidad</h4>
			</div>
			<div class="modal-body c-green-light">
				<div class="control-group">
					<label for="controls" class="label label-success">Codigo: </label>
					<div class="controls">
						<input type="text" class="span2" id="cod" DISABLED>
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-success">Descripción: </label>
					<div class="controls">
						<input type="text" class="span4" id="des" DISABLED>
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-success">Medida: </label>
					<div class="controls">
						<input type="text" class="span4" id="med" DISABLED>
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-success">Cantidad</label>
					<div class="controls">
						<input type="number" id="cant" class="span2">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success t-d" onClick="dettmp();">Siguiente <i class="icon-chevron-right"></i><i class="icon-chevron-right"></i></button>
			</div>
		</div>
		<div id="msu" class="modal" data-backdrop="static">
			<div class="modal-header c-blue-light">
				<a class="close" data-dismiss>x</a>
				<h4 class="t-info">Orden de Suministro</h4>
			</div>
			<div class="modal-body c-blue-light">
				<div class="control-group">
					<label for="controls" class="label label-info">Almacen : </label>
					<div class="controls">
						<select name="al" id="al" class="span2">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
								}
							}
							$cn->close($query);
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-info">Empleado : </label>
					<div class="controls">
						<input type="text" class="input-small" id="dni" value="<?php echo $_SESSION['dni-icr']; ?>" DISABLED />
						<input type="text" id="ape" class="span4" value="<?php echo $_SESSION['nom-icr']; ?>" DISABLED />
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-info">Fecha Requerida : </label>
					<div class="controls">
						<input type="text" id="fec" class="span2" placeholder="aaaa-mm-dd" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-info t-d" onClick="savesum();"><i class="icon-ok"></i> Guardar Cambios</button>
			</div>
		</div>
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>
