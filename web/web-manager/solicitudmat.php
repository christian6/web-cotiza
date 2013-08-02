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
	<title>Vista Solicitud de Materiales</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/solmat.js"></script>
	<style>
		.cont{
			text-align: center;
		}
		.cont button{
			background-color: #084B8A;
			border: 3px double #2d2d2d;
			border-radius: 1em;
			display: inline-block;
			height: 120px;
			margin: 5px;
			padding: 10px;
			vertical-align: middle;
			width: 110px;
			transition: .8s;
			-webkit-transition: .8s;
			-moz-transition: .8s;
			-o-transition: .8s;
		}
		.cont button:hover{
			border-radius: 100%;
			box-shadow: 0 0 1em #222;
			transition: .8s;
			-webkit-transition: .8s;
			-moz-transition: .8s;
			-o-transition: .8s;
		}
		
	</style>
</head>
<body>
	<?php include ("includes/menu-manager.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<div class="row show-grid">
				<div class="span12">
					<h4>Solicitud de Materiales Nuevos</h4>
					<div class="row show-grid">
						<div class="span12">
							<div class="well cont c-g">
							<?php 
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT s.mtid,s.solnom,e.empnom FROM admin.solmat s
													INNER JOIN admin.empleados e
													ON s.empdni LIKE e.empdni
													WHERE s.flag LIKE '0'");
								if ($cn->num_rows($query) > 0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
										?>
										<button class='t-white' onClick="viewdet('<?php echo $result['mtid']; ?>');">
										<?php
										echo "<strong>".$result['solnom']."</strong>";
										//echo "<p>".$result['solnom']."</p>";
										echo "<p>".$result['empnom']."</p>";
										echo "</button>";
									}
								}
								$cn->close($query);
							 ?>
							 </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="mpri" class="modal fade in hide" data-backdrop="static">
			<div class="modal-header">
				<a class="close" data-dsimiss="modal">x</a>
				<h4>Solicitud Nuevo Material</h4>
				<input type="hidden" id="sol">
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="label label-info">Descripción</label>
					<div class="controls">
						<input type="text" id="nom" class="span5">
					</div>
				</div>
				<div class="control-group">
					<label class="label label-info">Medida</label>
					<div class="controls">
						<input type="text" class="span5" id="med">
					</div>
				</div>
				<div class="contol-group">
					<label class="label label-info">Marca Sugerida</label>
					<div class="controls">
						<input type="text" id="mar" class="span2">
					</div>
				</div>
				<div class="contol-group">
					<label class="label label-info">Modelo Sugerida</label>
					<div class="controls">
						<input type="text" id="mod" class="span2">
					</div>
				</div>
				<div class="control-group">
					<label class="label label-info">Observación</label>
					<div class="controls">
						<textarea name="" id="obser" class="span4" rows="4"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-info t-d" onClick="nextcod();"> Continuar <i class="icon-chevron-right"></i></button>
			</div>
		</div>
		<div class="modal fade in hide" id="mseg" data-backdrop="static">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Solicitud Nuevo Material</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="label label-info">Codigo de Material</label>
					<div class="controls">
						<input type="text" class="span2" id="cod" maxlength="15" REQUIRED>
					</div>
				</div>
				<div class="control-gruop">
					<label class="label label-info">Unidad</label>
					<div class="controls">
						<select class="span2" id="cbound" >
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT unidadid,uninom FROM admin.unidad");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['uninom']."'>".$result['uninom']."</option>";
								}
							}
							$cn->close($query);
							?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="label label-info">Resumen</label>
					<div class="controls">
						<dl class="dl-horizontal">
							<dt>Descripción</dt>
							<dd id="dnom"></dd>
							<dt>Medida</dt>
							<dd id="dmed"></dd>
							<dt>Marca</dt>
							<dd id="dmar"></dd>
							<dt>Modelo</dt>
							<dd id="dmod"></dd>
						</dl>
					</div>
				</div>
				<div class="alert alert-error hide">
					<a class="close" data-dismiss="alert">×</a>
					<strong>¡Oh, no!</strong>
					<p>No se ha podido Realizar la transacción. Parece el Codigo del material ya existe.</p>
				</div>
				<div class="alert alert-success hide">
					<a class="close" data-dismiss="alert">×</a>
					<strong>¡Bien hecho!</strong>
					<p>Se ha guardado el material correctamente y se añadio al inventario.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" onClick="backpri();"><i class="icon-chevron-left"></i> Atras</button>
				<button class="btn btn-info t-d" id="btng" onClick="savemat();"><i class="icon-ok"></i> Guardar y agregar al inventario</button>
				<button class="btn btn-info t-d hide" id="btnc" onClick="javscript:location.href='';"><i class="icon-off"></i> Salir</button>
			</div>
		</div>
		<div class="modal fade in span3 hide" id="min" data-backdrop="static" style="margin-left: -10%;">
			<div class="modal-header">
				<h5>Agregar a Inventario</h5>
			
				<div class="control-group">
					<label class="label label-info">Seleccione Almacen</label>
					<div class="controls">
						<select class="span2" id="cboal">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes");
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
					<label class="label label-info">Stock Minimo</label>
					<div class="controls">
						<input type="number" class="span2" id="stkm" value="10" REQUIRED>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success t-d" onClick="addinve();"><i class="icon-ok"></i> Continuar</button>
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