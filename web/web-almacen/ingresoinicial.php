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
	<title>Ingreso de Materiales al Inventario Inicial</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/ingstockmat.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<h4>Agregar Materiales al Inventario Inicial del Periodo Pasado</h4>
			<hr style="margin-top: -.5em; ">
			<div class="row ">
				<div class="span6">
					<div class="alert alert-info">
						<a class="close" href="#" data-dismiss="alert">x</a>
						<strong>Nota</strong>
						<p>
							<p>
								Para porder trabajar normalmente con la aplicacion se requiere ingresar materiales al inventario.
								Esto es valido solo si estas en el nuevo periodo.
							</p>							
							<p>
								<i class="icon-ok"></i> Aqui puedes ingresar los materiales del periodo pasado que contengan un stock mayor a 0 (Stock > 0) al inventario inicial del periodo actual.
							</p>
							<p>
								<i class="icon-th"></i> Aqui puedes agregar todo la lista de materiales del periodo pasado sin tener en cuenta que el stock sea mayor a 0 o igual a 0 (Stock > 0 ó Stock = 0).
							</p>
							<p>
								<i class="icon-list"></i> Aqui puedes agregar todo la lista de materiales existente.
							</p>
						</p>
					</div>
					<div id="als" class="alert alert-success hide">
						<a class="close" href="#" data-dismiss="alert">x</a>
						<strong>Bien Hecho!</strong> Se ha realizado correctamente la transacción.
						<p id="dal"></p>
					</div>
				</div>
			</div>
			<div class="control-group">
				<button type="Button" class="btn" onClick="viewtbl();"><i class="icon-eye-open"></i> Ver Materiales</button>	
				<button type="Button" class="btn" onClick="agregarstock();"><i class="icon-ok"></i> Stock pasado</button>
				<button class="btn btn-success" onClick="fullperiodo();"><i class="icon-th"></i> Periodo Pasado</button>
				<button class="btn btn-danger" onClick="mat('o');"><i class="icon-list"></i> Lista de Materiales</button>
			</div>
			<div id="viewtable">
			</div>
		</div>
	</section>
	<div id="mal" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
                <h4>Agregar Material a Inventario</h4>
			</div>
			<div class="modal-body">
				<select name="cboalmacen" id="cboalmacen">
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
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                <a href="#" class="btn btn-primary">Añadir a Inventario</a>
			</div>
		</div>
	<div id="prm" class="modal hide fade in">
		<div class="modal-header">
			<h5>Parametros</h5>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label for="label" class="label label-info">Almacenes</label>
				<div class="controls">
					<select name="cboal" id="cboal">
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
				<label for="label" class="label label-info">Stock Minimo</label>
				<div class="controls">
					<input type="number" class="span2" id="txtstkm" name="txtstkm">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" onClick="mat('c');">Cancelar</button>
			<button class="btn btn-primary" onClick="matlist();">Guardar Cambios</button>
		</div>
	</div>
	<div id="fullscreen-icr">
		<span id="loading-icr">
			Cargando . . .
			<p>
				Este proceso puede tardar varios minutos.
			</p>
		</span>
	</div>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
	redirect(0);
}
?>