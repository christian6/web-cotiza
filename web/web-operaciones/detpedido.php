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
	<title>Detallde Pedido</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
</head>
<body>
	<section>
	<div class="container well">
		<div class="row show-grid">
			<div class="span12">
				<h3>Detalle de Pedido Nro <?php echo $_GET['nro']; ?></h3>
				<hr>
				<div class="row show-grid">
					<div class="span12">
						<table class="table table-bordered table-condensed table-hover">
							<caption>
								<label for="label" class="label pull-left">
								<label for="icon" class="inline pull-left t-d"><i class="c-green-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i> Atendido</label>
								<label class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<label for="icon" class="inline pull-left t-d"><i class="c-red-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i> Pendiente</label>
								</label>
							</caption>
							<thead>
								<th>Item</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Medida</th>
								<th>Unidad</th>
								<th>Cantidad</th>
							</thead>
							<tbody>
								<?php
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT * FROM almacen.spconsultardetpedidomat('".$_GET['nro']."')");
								if ($cn->num_rows($query)>0) {
									$i = 0;
									while ($result = $cn->ExecuteNomQuery($query)) {
										$i++;
										if ($result['auto'] == '1') {
											echo "<tr class='c-red-light'>";
										}else if($result['auto'] == '0'){
											echo "<tr class='c-green-light'>";
										}
										echo "<td style='text-align:center;'>$i</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align:center;'>".$result['matund']."</td>";
										echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
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
						</table>
					</div>
				</div>
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