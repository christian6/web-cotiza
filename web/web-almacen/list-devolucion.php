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
	<title>Lista Devoluciones</title>
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
	<script src="js/listdev.js"></script>
	<style>
		fieldset{
			border-radius: 1em;
			border-style: dashed;
			padding: 1.5em;
		}
		fieldset legend{
			font-weight: bold;
		}
	</style>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
	<section>
		<div class="container well">
			<h3 class="t-info">Devoluciones</h3>
			<fieldset>
				<legend> Busqueda por</legend>
				<div class="row show-grid">
					<div class="span10">
						<div class="control-group alert alert-info span2 pull-left">
							<label class="radio inline"> <input type="radio" name='rbtn' onChange="changeradio();" value="c"> Codigo</label>
							<label class="radio inline"> <input type="radio" name='rbtn' onChange="changeradio();" value="f"> Fecha</label>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label class="control-label">Codigo</label>
							<div class="controls">
								<input type="text" class="span2" maxlength="8" placeholder="DAA-0001" id="cod" DISABLED>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label class="control-label">Fecha Inicio</label>
							<div class="controls">
								<input type="text" class="span2" id="fi" maxlength="10" placeholder="aaaa-mm-dd" DISABLED>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label class="control-label">Fecha Fin</label>
							<div class="controls">
								<input type="text" class="span2" id="ff" maxlength="10" placeholder="aaaa-mm-dd" DISABLED>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label class="control-label">&nbsp;</label>
							<div class="controls">
								<button class="btn btn-info t-d" onClick="listdev();"><i class="icon-search"></i> Buscar</button>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<table class="table table-condensed-table-hover table-bordered">
				<thead>
					<tr>
						<th></th>
						<th>Codigo</th>
						<th>Proyecto</th>
						<th>Almacen</th>
						<th>Fecha</th>
						<th>Ver</th>
					</tr>
				</thead>
				<tbody id="tdet">
					
				</tbody>
			</table>
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