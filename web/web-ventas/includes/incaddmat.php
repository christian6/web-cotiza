<!doctype html>
<?php include ("../../datos/postgresHelper.php"); ?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Agregar Materiales</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="../../bootstrap/js/bootstrap.js"></script>
	<script src="../../web-almacen/js/autocomplete.js"></script>
	<script src="../js/medida.js"></script>
	<link rel="stylesheet" href="../../css/styleint.css">
</head>
<body>
	<header></header>
	<section>
		<div class="container">
			<div class="well">
				<div class="control-group">
					<div class="controls">
						<div class="btn-group">
							<button class="btn btn-success">
								<i class="icon-search"></i> 
								<span class="hidden-phone">
									<h5>Buscar</h5>
								</span>
							</button>
							<button class="btn btn-success" onClick="savedata();">
								<i class="icon-ok"></i>
								<span class="hidden-phone">
									<h5>Guardar</h5>
								</span>
							</button>
							<button class="btn btn-success" onClick="">
								<i class="icon-leaf"></i>
								<span class="hidden-phone">
									<h5>Nuevo Material</h5>
								</span>
							</button>
							<button class="btn btn-danger" onClick="javascript:window.close();">
								<i class="icon-resize-small"></i>
								<span class="hidden-phone">
									<h5 class="t-d">Salir</h5>
								</span>
							</button>
						</div>
					</div>
				</div>
			<div class="control-group">
				<dl class="dl-horizontal">
					<dt>Codigo de Proyecto</dt>
					<dd id="proid"><?php echo $_REQUEST['proid']; ?></dd>
					<dt>Nro Plano</dt>
					<dd id="plane"><?php echo $_REQUEST['plane'] ?></dd>
				</dl>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label for="label"><b>Descripción</b></label>
				</div>
				<div class="controls">
					<div class="ui-widget">
						<select id="combobox" class="span5" onclick="showmed();" style="display: none;">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT DISTINCT m.matnom FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid AND i.anio LIKE '".date("Y")."' ORDER BY matnom ASC");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
								}
							}
							$cn->close($query);
						?>
						</select>
						<br>
					</div>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label for="label"><b>Medida</b></label>
				</div>
				<div class="controls">
					<div id="med">
						
					</div>
				</div>
			</div>
			<!-- Datos Generales del Material -->
			<div class="control-group">
				<div class="controls">
					<div id="data">
					
					</div>	
				</div>
			</div>
			<!--Agregando cantidad-->
			<div class="control-group">
				<div class="control-label">
					<label for="label"><b>Cantidad</b></label>
				</div>
				<div class="controls">
					<input type="number" class="span2" id="txtcant" min="1" name="txtcant" title="Ingrese Cantidad" >
				</div>
			</div>
			</div>
		</div>
		<div id="fullscreen-icr"></div>
		<div id="msg" class="alert alert-block alert-success fade hide in span8">
			<a class="close" data-dismiss="alert" href="#">×</a>
			<h4 class="alert-heading">¡Bien Hecho!</h4>
			<p>Se ha guardado correctamente los datos.</p>
			<p>
				Esta ventana se cerrara en unos 3 segundos.
			</p>
		</div>
	</section>
	<footer>
	</footer>
</body>
</html>