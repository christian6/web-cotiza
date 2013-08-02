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
<!doctype html>
<html lang="es_ES">
<head>
	<meta charset="UTF-8">
	<title>Ingreso Inventario de Materiales</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/iniinventariomat.js"></script>
	<style>
		.radio, #lblm { margin-top: -1em; }
	</style>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<h4>Ingreso de Materiales al Inventario Inicial <?php echo date('Y'); ?></h4>
			<hr>
			<button class="btn" onClick="location.href='http://190.41.246.91/web/web-mantenice/materiales.php';"><i class="icon-plus"></i> Nuevo Material</button>
			<div class="container">
				<table class="table table-bordered table-hover">
					<caption>
						<form method="POST" name="frm1" action="">
							<div class="control-group">
								<label class="radio inline"><input type="radio" name="rbtn" id="rbtnc" value="c" onChange="radios();" /> Codigo</label>
								<div class="controls help-inline">
									<input type="text" class="inline span2" name="txtcodigo" id="txtcodigo" title="Ingrese Codigo" placeholder="Codigo" REQUIRED DISABLED />
								</div>
								<label class="radio inline"><input type="radio" name="rbtn" id="rbtnd" value="d" onChange="radios();"> Descripción</label>
								<div class="controls help-inline">
									<input type="text" class="inline span5" name="txtnombre" id="txtnombre" title="Ingrese Nombre" placeholder="Descripcion de Material" REQUIRED DISABLED />
								</div>
								<div class="help-inline">
									<button type="Submit" name="btnb" value="btnb" class="btn btn-primary"><i class="icon-search icon-white"></i> Buscar</button>
								</div>
							</div>
						</form>
					</caption>
					<thead>
						<th>Item</th>
						<th>Codigo</th>
						<th>Descripción</th>
						<th>Medida</th>
						<th>Unidad</th>
						<th>Ingresar</th>
					</thead>
					<tbody>
						<?php
							if ($_POST['btnb'] == 'btnb') {
								$cn = new PostgreSQL();
								$qsql = "SELECT DISTINCT materialesid,matnom,matmed,matund FROM admin.materiales ";
								if ($_POST['rbtn'] == "c") {
									$qsql .= "WHERE materialesid LIKE '".$_POST['txtcodigo']."%'";
								}else if($_POST['rbtn'] == "d"){
									$qsql .= "WHERE lower(matnom) LIKE lower('%".$_POST['txtnombre']."%')";
								}
								# echo "Aqui ".$qsql;
								$query = $cn->consulta($qsql);
								if ($cn->num_rows($query) > 0) {
									$t = 1;
									while ($result = $cn->ExecuteNomQuery($query)) {
										echo "<tr>";
										echo "<td style='text-align: center;'>".$t++."</td>";
										echo "<td style='text-align: center;'>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align: center;'>".$result['matund']."</td>";
										?>
										<td style='text-align: center;'><a href="javascript:openadd('<?php echo $result['materialesid'];?>','<?php echo $result['matnom'];?>','<?php echo $cad = str_replace('"','',$result['matmed']);?>','<?php echo $result['matund']; ?>');"><i class='icon-plus'></i></td>
										<?php
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
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div id="madd" class="modal hide fade in">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h4>Agregar Material a Inventario Inicial</h4>
                <div id="ale" class="alert alert-block alert-error hide face in">
                	<a class="close" data-dismiss="alert" href="#">x</a>
                	<h4 class="alert-heading">¡Oh, no! Tienes un error!</h4>
                	<p>
                		El material que esta intentando ingresar ya existe en el inventario,
                		realize una nueva busqueda.
                		No se ha podido ingresar el material.
                	</p>
                </div>
                <div id="als" class="alert alert-block alert-success hide face in">
                	<a class="close" data-dismiss="alert" href="#">x</a>
                	<h4 class="alert-heading">¡Bien hecho!</h4>
                	<p>
                		El material se ingresado satisfactoriamente al inventario.
                	</p>
                </div>
            </div>
            <div class="modal-body">
				<div class="container-fluid">
					<div class="control-group">
						<label id="lblm" class="label label-info">Codigo</label>
						<div class="controls">
							<input type="text" class="span3" name="txtid" id="txtid"  DISABLED/>
						</div>
					</div>
					<div class="control-group">
						<label class="label label-info">Nombre</label>
						<div class="controls">
							<input type="text" class="span5" id="txtnom" DISABLED/>
						</div>
					</div>
					<div class="control-group">
						<label class="label label-info">Medida</label>
						<div class="controls">
							<input type="text" class="span5" id="txtmed" DISABLED/>
						</div>
					</div>
					<div class="control-group">
						<label class="label label-info">Unidad</label>
						<div class="controls">
							<input type="text" class="input-small" id="txtund" DISABLED/>
						</div>
					</div>
					<div class="control-group">
						<label class="label label-info">Almacen</label>
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
				</div>
            </div>
            <div class="modal-footer">
            	<label id="lblpro" class="label label-warning hide fade in pull-left"> Comprobando la existencia de material... </label>
                <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
                <a href="javascript:addinvent();" class="btn btn-primary">Añadir a Inventario</a>
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