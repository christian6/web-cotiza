<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }
include ("../datos/postgresHelper.php");
$ok=0;
if ( isset($_POST['save']) ) {
	$cn = new PostgreSQL;
	$query = $cn->consulta("INSERT INTO admin.solmat(solnom,solmed,solmar,solmod,solobs,empdni) VALUES('".$_POST['nom']."','".$_POST['med']."','".$_POST['mar']."','".$_POST['mod']."','".$_POST['obser']."','".$_SESSION['dni-icr']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	$ok = 1;
}

?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Solitud de Material Nuevo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<div class="row show-grid">
				<div class="span12">
					<h4>Solicitud de Materiales Nuevos</h4>
					<?php if ($ok == 0) {
						echo "<div class='alert hide'>";
					}else{ ?>
					<div class="alert alert-success fade in span4">
					<?php } ?>
						<a class="close" data-dismiss="alert">×</a>
						<h4 class="alert-heading">¡Bien Hecho!</h4>
						<p>Se ha guardado correctamente tu solicitud para la creación de un nuevo material.</p>
					</div>
					<div class="row show-grid">
						<div class="span12">
							<form method="POST" action="">
							<div class="control-group">
								<label for="">Nombre del Material</label>
								<div class="controls">
									<input type="text" name="nom" class="span6" REQUIRED />
								</div>
							</div>
							<div class="control-group">
								<label for="">Medida</label>
								<div class="controls">
									<input type="text" name="med" class="span6">
								</div>
							</div>
							<!--<div class="control-gruop">
								<label for="">Unidad</label>
								<div class="controls">
									<select name="cbound" id="cbound">
										<?php
										/*$cn = new PostgreSQL();
										$query = $cn->consulta("SELECT unidadid,uninom FROM admin.unidad");
										if ($cn->num_rows($query) > 0) {
											while ($result = $cn->ExecuteNomQuery($query)) {
												echo "<option value='".$result['unidadid']."'>".$result['uninom']."</option>";
											}
										}
										$cn->close($query);*/
										?>
									</select>
								</div>
							</div>-->
							<div class="control-group">
								<label for="">Marca Sugerida</label>
								<div class="controls">
									<input type="text" name="mar">
								</div>
							</div>
							<div class="control-group">
								<label for="">Modelo sugerido</label>
								<div class="controls">
									<input type="text" name="mod">
								</div>
							</div>
							<div class="control-group">
								<label for="">Observacion</label>
								<div class="controls">
									<textarea class="span6" name="obser" cols="20" rows="4"></textarea>
								</div>
							</div>
							<div class="controls">
								<button class="btn btn-warning t-d pull-left" onclick="javascript:window.refresh;">Limpiar</button>
								<button class="btn btn-primary pull-right" name="save" type="submit">Guardar Cambios</button>
							</div>
						</form>
						</div>
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