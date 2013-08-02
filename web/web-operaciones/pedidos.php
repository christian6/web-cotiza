<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Estado de Pedidos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<style>
		td img{ height: 24px; width: 24px; }
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h4>Consulta de Estado de Pedido</h4>
			<div class="row show-grid">
				<div class="span12">
					<form class="form-horizontal" action="" method="POST">
					<div class="control-group">
						<label for="label" class="control-label">Seleccione Estado:</label>
						<div class="controls">
							<select name="cboes" id="cboes">
								<?php
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '16'");
								if ($cn->num_rows($query) > 0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
										if ($_POST['cboes'] == $result['esid']) {
											echo "<option value='".$result['esid']."' SELECTED>".$result['esnom']."</option>";
										}else{
											echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
										}
										
									}
								}else{
									echo "(Sin Result)";
								}
								$cn->close($query);
								?>
							</select>
							<button type="Submit" class="btn btn-info t-white"><i class="icon-search icon-white"></i> Buscar</button>
						</div>
					</div>
					</form>
				</div>
				
			</div>
			<div class="row show-grid">
					<div class="span12">
						<table class="table table-striped table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th>Nro de Pedido</th>
									<th>Fecha</th>
									<th>Fecha de Entrega</th>
									<th>Estado</th>
									<th>Ver Detalle</th>
									<!--<th>Enviar</th>-->
									<th>Vista</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("
										SELECT p.nropedido,p.fecha::date,p.fecent,e.esnom
										FROM almacen.pedido p INNER JOIN admin.estadoes e
										ON p.esid = e.esid
										WHERE p.empdni LIKE '".$_SESSION['dni-icr']."' AND p.esid LIKE '".$_POST['cboes']."'");
									if ($cn->num_rows($query)>0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											echo "<tr>";
											echo "<td id='tc'>".$result['nropedido']."</td>";
											echo "<td id='tc'>".$result['fecha']."</td>";
											echo "<td id='tc'>".$result['fecent']."</td>";
											echo "<td id='tc'>".$result['esnom']."</td>";
											echo "<td style='text-align:center;'><a href='detpedido.php?nro=".$result['nropedido']."' target='_Blank'><img src='../resource/detalle32.png'></a></td>";
											//echo "<td style='text-align:center;'><a href='maillogistica.php?nro=".$result['nropedido']."' target='_self'><img src='../resource/mail32.png'></a></td>";
											echo "<td style='text-align:center;'><a href='../reports/almacen/pdf/rptpedidomat.php?nro=".$result['nropedido']."' target='_blank'><img src='../resource/vistaprevia.png'></a></td>";
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
							<tfoot>
							</tfoot>
						</table>
					</div>
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