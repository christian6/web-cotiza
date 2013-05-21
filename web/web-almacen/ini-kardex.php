<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
	include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Consulta de Kardex</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<script type="text/javascript" src="js/inikardex.js"></script>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<h5>Kardex</h5>
			<div class="row show-grid">
				<form action="" method="POST" name="frm">
					<div class="span9">
					<div class="row show-grid">
						<div class="span3">
							<div class="control-group">
								<label for="label">Almacen:</label>
								<div class="controls">
									<select name="cboal" id="cboal">
										<?php
											$cn = new PostgreSQL();
											$query = $cn->Consulta("SELECT DISTINCT almacenid,descri FROM admin.almacenes");
											if ($cn->num_rows($query) > 0) {
												while ($result = $cn->ExecuteNomQuery($query)) {
													if ($_POST['cboal'] == $result['almacenid']) {
														echo "<option value='".$result['almacenid']."' SELECTED>".$result['descri']."</option>";
													}else{
														echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
													}
												}
											}
											$cn->close($query);
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								<label for="label">Anio:</label>
								<div class="controls">
									<select class="span2" name="cboa" id="cboa">
									<?php
										$cn = new PostgreSQL();
										$query = $cn->Consulta("SELECT DISTINCT anio FROM almacen.inventario");
										if ($cn->num_rows($query) > 0) {
											while ($result = $cn->ExecuteNomQuery($query)) {
												if ($_POST['cboa'] == $result['anio']) {
													echo "<option value='".$result['anio']."' SELECTED>".$result['anio']."</option>";
												}else{
													echo "<option value='".$result['anio']."'>".$result['anio']."</option>";
												}
											}
										}
										$cn->close($query);
									?>
									</select>
								</div>
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								<label for="label">Mes:</label>
								<div class="controls">
									<select class="span2" name="cbom" id="cbom">
									<?php
										$meses = array(
											1 => 'ENERO',
											2 => 'FEBRERO',
											3 => 'MARZO',
											4 => 'ABRIL',
											5 => 'MAYO',
											6 => 'JUNIO',
											7 => 'JULIO',
											8 => 'AGOSTO',
											9 => 'SEPTIEMBRE',
											10 => 'OCTUBRE',
											11 => 'NOVIEMBRE',
											12 => 'DICIEMBRE'
											);
										foreach ($meses as $key => $value) {
											if ($_POST['cbom'] == $key) {
												echo "<option value='".$key."' SELECTED>".$value."</option>";
											}else{
												echo "<option value='".$key."'>".$value."</option>";	
											}
										}
									?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span3">
							<div class="control-group">
								<label class="radio inline"><input type="radio" id="rbtnc" name="rbtn" value="c" onChange="radios();"> Codigo</label>
								<label class="radio inline"><input type="radio" id="rbtnd" name="rbtn" value="d" onChange="radios();"> Descripcion</label>
							</div>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span8">
							<div class="row show-grid">
								<div class="span3">
									<label for="label">Codigo</label>
									<div class="controls">
										<input type="text" id="txtcod" name="txtcod" class="span2" placeholder="Ingrese Codigo" DISABLED >
									</div>
								</div>
								<div class="span5">
									<label for="label">Codigo</label>
									<div class="controls">
										<input type="text" id="txtdes" name="txtdes" class="span5" placeholder="Ingrese Descripcion" DISABLED >
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="controls">
						<button type="Submit" class="btn btn-success" name="btns" value="btns"><i class="icon-search"></i> Buscar</button>
					</div>
				</div>	
				</form>
				
			</div>
			<hr >
			<span class="hidden-desktop">
				<button class="btn"><i class="icon-eye-open"></i> Pre visualizacion</button>
				<button class="btn"><i class="icon-print"></i> Imprimir</button>
			</span>
				<div class="visible-desktop">
					<table class="table table-bordered table-striped tablel-hover">
						<thead>
							<tr>
								<th>Item</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Medida</th>
								<th>Inicial</th>
								<th>Entrada</th>
								<th>Salida</th>
								<th>Final</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($_POST['btns'] == 'btns'){
								$cn = new PostgreSQL();
								$sql = "select m.materialesid,m.matnom,m.matmed,e.stkact,e.cantent,e.cantsal,e.saldo
										from almacen.resumenk e inner join admin.materiales m
										on e.materialesid like m.materialesid
										where e.almacenid like '".$_POST['cboal']."' and extract(year from e.fecha::date)::varchar like '".$_POST['cboa']."'
										and extract(month from e.fecha::date) = ".$_POST['cbom']."";
								if ($_POST['rbtn'] == "c") {
									$sql .= " and m.materialesid like '".$_POST['txtcod']."'";
								}elseif ($_POST['rbtn'] == "d") {
									$sql .= " and m.matnom like '%".$_POST['txtdes']."%'";
									#and m.matnom like '%Abrazadera%'
								}
								$query = $cn->Consulta($sql);
								if ($cn->num_rows($query) > 0) {
									$i = 1;
									while ($result = $cn->ExecuteNomQuery($query)) {
										echo "<tr>";
										echo "<td>".$i++."</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td>".$result['stkact']."</td>";
										echo "<td>".$result['cantent']."</td>";
										echo "<td>".$result['cantsal']."</td>";
										echo "<td>".$result['saldo']."</td>";
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