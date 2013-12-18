<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reporte de Inspección</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		function valid (id,val) {
			if (id.value != val) {
				id.value = val;
			};
		}
	</script>
</head>
<body>
<?php
if ($_POST['btns']== "btns") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.rptinspeccion(nroningreso, transporte, feclleg, fecingal, desmat, tpemb, obser, empdni, esid)
							VALUES('".$_POST['txtnroing']."','".$_POST['rbtntra']."','".$_POST['txtfeclle']."'::date,'".$_POST['txtfecial']."',
							'".$_POST['txtdesg']."','".$_POST['rbtntipo']."','".$_POST['txtobser']."','".$_SESSION['dni-icr']."','53')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "<div class='alert alert-success alert-small'>
			<strong>Bien Hecho!</strong>
			<p>
			 Se ha guardado Correctamente el reporte de Inspección
			</p>
			<button class='btn btn-success' onClick='self.window.close();' ><i class='icon-off'></i> Salir</button>
		</div>";
}
?>
	<section>
		<div class="container well">
			<h5>Reporte de Inspección</h5>
			<hr style="margin-top: -.5em;">
			<?php
			$nro = '';
			$lle = '';
			$ing = '';
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT n.nroningreso,c.fecent,n.fecha::date
									FROM almacen.notaingreso n INNER JOIN logistica.compras c
									ON TRIM(n.nrocompra) LIKE c.nrocompra
									WHERE n.nroningreso LIKE '".$_GET['nro']."' LIMIT 1 OFFSET 0");
			if ($cn->num_rows($query) > 0) {
				$result = $cn->ExecuteNomQuery($query);
				$nro = $result[0];
				$lle = $result[1];
				$ing = $result[2];
			}
			$cn->close($query);
			?>
			<div class="row show-grid">
				<div class="span6 well well-small">
					<div class="row show-grid">
					<form action="" name="frm" method="POST">
						<div class="span6">
							<div class="control-group">
								<label for="label" class="label label-info">Nro de Nota de Ingreso</label>
								<div class="controls">
									<input type="text" class="uneditable-input" id="txtnroing" name='txtnroing' onBlur="valid(this,'<?php echo $nro; ?>');" value="<?php echo $_GET['nro']; ?>" title="Nro de Nota de Ingreso" REQUIRED/>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Transporte</label>
								<div class="controls">
									<label class="radio inline"><input type="radio" name="rbtntra" id="rbtnair" value='Aereo' REQUIRED> Aereo</label>
									<label class="radio inline"><input type="radio" name="rbtntra" id="rbtnmar" value="Maritimo" REQUIRED> Maritimo</label>
									<label class="radio inline"><input type="radio" name="rbtntra" id="rbtnterra" value="Terrestre" REQUIRED> Terrestre</label>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Fecha de Llegada</label>
								<div class="controls">
									<input type="text" class="span2 uneditable-input" name="txtfeclle" id="txtfeclle" onBlur="valid(this,'<?php echo $lle; ?>');" value="<?php echo $lle; ?>" title="Fecha de Llegada Programada" REQUIRED>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Fecha de Ingreso al Almacen</label>
								<div class="controls">
									<input type="text" class="span2" name="txtfecial" id="txtfecial" title="Fecha de Ingreso al Almacen" onBlur="valid(this,'<?php echo $ing; ?>');" value="<?php echo $ing; ?>" REQUIRED>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Descripción General de la Orden</label>
								<div class="controls">
									<textarea name="txtdesg" id="txtdesg" cols="30" rows="5" class="span6" title="Ingrese Descripción General de la Orden" REQUIRED></textarea>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Tipo de Embarque</label>
								<div class="controls">
									<label class="radio inline"><input type="radio" name="rbtntipo" id="rbtna" value="Adecuado" REQUIRED> Adecuado</label>
									<label class="radio inline"><input type="radio" name="rbtntipo" id="rbtni" value="Inadecuado" REQUIRED> Inadecuado</label>
								</div>
							</div>
							<div class="control-group">
								<label for="label" class="label label-info">Observaciones</label>
								<div class="controls">
									<textarea name="txtobser" id="txtobser" cols="30" rows="5" class="span6"></textarea>
								</div>
							</div>
							<div class="controls">
								<button type="Submit" name="btns" value="btns" class="btn btn-primary"><i class="icon-ok icon-white"></i> Guardar Cambios</button>
								<button type="Button" class="btn btn-danger" onClick="self.window.close();"><i class="icon-remove icon-white"></i> Cancelar y Salir</button>
							</div>
						</div>
					</from>
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
	redirect(1);
}
?>