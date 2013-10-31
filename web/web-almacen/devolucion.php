<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Crear Devolución</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script type="text/javascript" src="js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<script src="js/devolucion.js"></script>
	<style>
		fieldset{
			border-radius: 1em;
		}
		fieldset legend{
			font-weight: bold;
		}
		.ui-autocomplete{
			max-height: 16em;
			overflow-y: auto;
			overflow-x: hidden;
		}
	</style>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
	<div class="container well">
		<h3 class="t-info">Devolución de Materiales</h3>
		<div class="row show-grid">
			<div class="span12">
				<div class="btn-group">
					<button class="btn btn-info t-d" onClick="viewobj(false);"><i class="icon-file"></i> Nuevo</button>
					<button class="btn btn-success t-d" onClick="saveDev();"><i class="icon-plus"></i> Guardar e Ingresar a almacen</button>
				</div>
			</div>
			<div class="span12">
				<div class="row show-grid">
					<div class="span6">
						<fieldset>
							<legend>Datos Devolución</legend>
							
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Almacén</label>
										<div class="controls">
											<select name="al" id="al" class="span3">
												<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT * FROM admin.almacenes");
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
								<div class="span2">
									<div class="control-group">
										<label class="control-label">Nro Guia Remisión</label>
										<div class="controls">
											<input type="text" id="nrg" class="span2">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label class="control-label">Fecha</label>
										<div class="controls">
											<input type="text" class="span2" id="fec" value="<?php echo(date('Y-m-d')); ?>">
										</div>
									</div>
								</div>
								<div class="span3">
									<div class="control-group">
										<label class="control-label">Observación</label>
										<div class="controls">
											<textarea class="span3" name="obs" id="obs" onFocus="heighttext(this.id,true)" onBlur="heighttext(this.id,false);" style="height:1.5em;"></textarea>
										</div>
									</div>
								</div>
						</fieldset>
					</div>
					<div class="span6">
						<fieldset>
							<legend>Datos Proyecto</legend>
							<div class="span5">
								<div class="control-group">
									<label class="control-label">Proyecto</label>
									<div class="controls">
										<select name="pro" id="pro" onClick="datapro();" class="span5">
										<?php
											$cn = new PostgreSQL();
											$query = $cn->consulta("SELECT proyectoid,descripcion FROM ventas.proyectos WHERE esid LIKE '55'");
											if ($cn->num_rows($query) > 0) {
												while ($result = $cn->ExecuteNomQuery($query)) {
													echo "<option value='".$result['proyectoid']."'>".$result['descripcion']."</option>";
												}
											}
											$cn->close($query);
										?>
										</select>
									</div>
								</div>
							</div>
							<div class="span5">
								<label class="inline">Proyecto <span id="npro" class="help-inline inline"></span></label>
							</div>
							<div class="span5">
								<label class="inline">Dirección <span id="dpro" class="help-inline inline"></span></label>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row show-grid">
			<div class="span12">
				<table class="table table-condensed-table-hover">
					<caption style="text-align: left;">
						<div class="control-group">
							<fieldset class="c-red-light">
								<legend>Agregar Material
									<div class="btn-group">
										<button id="btnr" class="btn btn-small btn-danger" onClick="resizeadd();"><i class="icon-plus"></i></button>
										<button class="btn btn-info btn-small t-d" onClick="delalltmp();"><i class="icon-trash"></i> Eliminar Tmp</button>
									</div>
								</legend>
								<div id="add" class="row show-grid">
									<div class="span6">
										<div class="control-group error">
											<label class="control-label">Descripción</label>
											<div class="controls">
												<div class="ui-widget">
												<select name="mat" id="combobox" class="span5 hide">
												<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT DISTINCT matnom FROM admin.materiales ORDER BY matnom ASC");
													if ($cn->num_rows($query) > 0) {
														while ($result = $cn->ExecuteNomQuery($query)) {
															echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
														}
													}
													$cn->close($query);
												?>
												</select>
											</div>
											</div>
										</div>
									</div>
									<div class="span5">
										<div class="control-group error">
											<label class="control-label">Medida</label>
											<div class="controls">
												<select name="med" id="med" class="span5" onClick="showdet();"></select>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="well c-red t-white" id="cdet">
											
										</div>
									</div>
									<div class="span2">
										<div class="control-group error">
											<label class="control-label">Estado</label>
											<div class="controls">
												<textarea name="est" id="est" onFocus="heighttext(this.id,true)" onBlur="heighttext(this.id,false);" style="height:1.5em;" class="span2"></textarea>
											</div>
										</div>
									</div>
									<div class="span1">
										<div class="control-group error">
											<label class="control-label">Cantidad</label>
											<div class="controls">
												<input type="number" max="999" min="1" class="span1" id="cant">
											</div>
										</div>
									</div>
									<div class="span1">
										<div class="control-group error">
											<label class="control-label">Imagen</label>
											<div class="controls">
												<input type="file" id="mimg" class="span1 hide" accept="image/*">
												<a class="alert alert-warning" href="javascript:$('#mimg').click();">Foto</a>
											</div>
										</div>
									</div>
									<div class="span1">
										<div class="control-group">
											<label class="control-label">&nbsp;</label>
											<div class="controls">
												<button class="btn btn-danger" onClick="savetmp();"><i class="icon-plus"></i></button>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</caption>
					<thead>
						<tr>
							<th></th>
							<th>Codigo</th>
							<th>Descripción</th>
							<th>Medida</th>
							<th>Unidad</th>
							<th>Cantidad</th>
							<th>Edit</th>
							<th>Eliminar</th>
						</tr>
					</thead>
					<tbody id="dett">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="modaled" class="modal fade in hide c-yellow-light">
		<div class="modal-header">
			<a data-dismiss="modal" class="close">&times;</a>
			<h4 class="t-warning">Editar Material <span id="medit"></span></h4>
		</div>
		<div class="modal-body">
			<div class="row show-grid">
				<div class="span2">
					<div class="control-group warning">
						<label class="control-label">Cantidad</label>
						<div class="controls">
							<input type="number" min="1" max="9999" class="span2" id="cedit">
						</div>
					</div>
				</div>
				<div class="span2">
					<div class="control-group warning">
						<label class="control-label">Imagen</label>
						<div class="controls">
							<input type="file" class="hide" id="imgmodify">
							<span class="alert alert-info">
								<a href="javascript:$('#imgmodify').click();" class="pull-center">Click Aqui.</a>
							</span>
						</div>
					</div>
				</div>
				<div class="span5">
					<div class="control-group warning">
						<label class="control-label">Estado</label>
						<div class="controls">
							<textarea name="eest" id="eest" class="span5" rows="4"></textarea>
						</div>
					</div>
				</div>
				<div class="span5">
					<button class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
					<button class="btn btn-warning t-d pull-right" onClick="editmat();"><i class="icon-edit"></i> Editar</button>
				</div>
			</div>
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
	redirect();
}
?>