<?php
include ("../includes/session-trust.php");

header('Access-Control-Allow-Origin: *');
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
	<title>Mantenimiento de Empleados</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/styleint.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<script src="js/employee.js"></script>
	<?php
	if (isset($_POST['pais'])) {
	?>
	<script>
		$(function () {
			$('#tab a[href="#upkeep"]').tab('show');
		}); 
	</script>
	<?php
	}
	if (isset($_POST['dis'])) {
		?>
		<script>
		$(function () {
			triggerChange('dis',<?php echo $_POST['dis']; ?>);
		}); 
		</script>
		<?php
	}
	?>
</head>
<body>
	<?php include("includes/menu-manager.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h3>Mantenimiento de Empleados</h3>
			<hr>
			<div class="row show-grid">
				<div class="span2">
					<ul class="nav nav-tabs nav-pills nav-stacked" id="tab">
						<li><a href="#list" data-toggle="tab">Empleados</a></li>
						<li><a href="#upkeep" data-toggle="tab">Mantenimineto</a></li>
						<li><a href="#uplogin" data-toggle="tab">Login</a></li>
					</ul>
				</div>
				<div class="span10">
					<div class="well">
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade in" id="list">
								<h4><em>Lista Empleados</em></h4>
								<div class="span10">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>Item</th>
												<th>DNI</th>
												<th>Nombre</th>
												<th>Apellido</th>
												<th>Dirección</th>
												<th>Cargo</th>
												<th>Editar</th>
												<th>Eliminar</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT DISTINCT e.empdni,e.empnom,e.empape,e.empdir,e.empfnc::date,e.paisid,e.departamentoid,e.provinciaid,e.distritoid,e.emptel,e.cargoid,c.carnom FROM admin.empleados e INNER JOIN admin.cargo c ON e.cargoid = c.cargoid WHERE esid LIKE '19' ORDER BY c.carnom");
												if ($cn->num_rows($query) > 0) {
													$i = 1;
													while ($result = $cn->ExecuteNomQuery($query)) {
														?>
														<tr>
														<td><?php echo $i++; ?></td>
														<td><?php echo $result['empdni']; ?></td>
														<td><?php echo $result['empnom']; ?></td>
														<td><?php echo $result['empape']; ?></td>
														<td><?php echo $result['empdir']; ?></td>
														<td><?php echo $result['carnom']; ?></td>
														<td id="tc"><button class='btn btn-small btn-warning' onClick="edithingEmployee(<?php echo "'".$result['empdni']."','".$result['empnom']."','".$result['empape']."','".$result['empfnc']."','".$result['paisid']."','".$result['departamentoid']."','".$result['provinciaid']."','".$result['distritoid']."','".$result['empdir']."','".$result['emptel']."','".$result['cargoid']."'";?>);">
															<i class='icon-edit'></i>
														</td>
														<td id="tc">
															<button class='btn btn-small btn-danger' onClick="deleteEmployee(<?php echo "'".$result['empdni']."'";?>);">
															<i class='icon-remove-circle'></i>
														</td>
														</tr>
														<?php
													}
												}
												$cn->close();
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade in" id="upkeep">
								<h4><em>Mantenimiento</em></h4>
								<form action="" name="form1" method="POST">
									<input type="hidden" name="dep" value="">
									<input type="hidden" name="pro" value="">
									<input type="hidden" name="dis" value="">
									<div class="row show-grid">
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">DNI Empleado</label>
												<div class="controls">
													<input type="text" class="span2" maxlength="8" name="dni" value="<?php echo $_POST['dni']; ?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group info">
												<label class="control-label">Nombres</label>
												<div class="controls">
													<input type="text" class="span3" name="nom" value="<?php echo $_POST['nom']; ?>">
												</div>
											</div>
										</div>
										<div class="span3">
											<div class="control-group info">
												<label class="control-label">Apellidos</label>
												<div class="controls">
													<input type="text" class="span3" name="ape" value="<?php echo $_POST['ape']; ?>">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Fecha Nacimiento</label>
												<div class="controls">
													<input type="text" class="span2" maxlength=="10" name="fec" value="<?php echo $_POST['fec']; ?>">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Pais</label>
												<div class="controls">
													<select onChange="changeCombo();" name="pais" id="pais" class="span2">
														<?php
															$cn = new PostgreSQL();
															$query = $cn->consulta("SELECT DISTINCT * FROM admin.pais ORDER BY paisnom ASC");
															if ($cn->num_rows($query) > 0) {
																while ($result = $cn->ExecuteNomQuery($query)) {
																	if ( $_POST['pais'] == $result['paisid'] ) {
																		echo "<option value='".$result['paisid']."' SELECTED>".$result['paisnom']."</option>";	
																	}else{
																		echo "<option value='".$result['paisid']."'>".$result['paisnom']."</option>";
																	}
																}
															}else{
																echo "<option>Nothing</option>";
															}
															$cn->close($query);
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Departamento</label>
												<div class="controls">
													<select onChange="changeCombo();" name="dep" id="dep" class="span2">
														<?php
															if (isset($_POST['pais'])) {
																$cn = new PostgreSQL();
																$query = $cn->consulta("SELECT DISTINCT * FROM admin.departamento WHERE paisid LIKE '".$_POST['pais']."' ORDER BY deparnom ASC");
																if ($cn->num_rows($query) > 0) {
																	while ($result = $cn->ExecuteNomQuery($query)) {
																		if ($_POST['dep'] == $result['departamentoid']) {
																			echo "<option value='".$result['departamentoid']."' SELECTED>".$result['deparnom']."</option>";
																		}else{
																			echo "<option value='".$result['departamentoid']."'>".$result['deparnom']."</option>";
																		}
																	}
																}else{
																	echo "<option>Nothing</option>";
																}
																$cn->close($query);
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Provincia</label>
												<div class="controls">
													<select onChange="changeCombo();" name="pro" id="pro" class="span2">
														<?php
															if (isset($_POST['dep'])) {
																$cn = new PostgreSQL();
																$query = $cn->consulta("SELECT DISTINCT * FROM admin.provincia WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' ORDER BY provnom");
																if ($cn->num_rows($query) > 0) {
																	while ($result = $cn->ExecuteNomQuery($query)) {
																		if ($_POST['pro'] == $result['provinciaid']) {
																			echo "<option value='".$result['provinciaid']."' SELECTED>".$result['provnom']."</option>";
																		}else{
																			echo "<option value='".$result['provinciaid']."'>".$result['provnom']."</option>";
																		}
																		
																	}
																}else{
																	echo "<option>Nothing</option>";
																}
																$cn->close($query);
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Distrito</label>
												<div class="controls">
													<select name="dis" id="dis" class="span2">
														<?php
															if (isset($_POST['pro'])) {
																$cn = new PostgreSQL();
																$query = $cn->consulta("SELECT DISTINCT * FROM admin.distrito WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' AND provinciaid LIKE '".$_POST['pro']."' ORDER BY distnom ASC");
																if ($cn->num_rows($query) > 0) {
																	while ($result = $cn->ExecuteNomQuery($query)) {
																		if ($_POST['dis'] == $result['distritoid']) {
																			echo "<option value='".$result['distritoid']."' SELECTED>".$result['distnom']."</option>";
																		}else{
																			echo "<option value='".$result['distritoid']."'>".$result['distnom']."</option>";
																		}
																	}
																}else{
																	echo "<option>Nothing</option>";
																}
																$cn->close($query);
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group info">
												<label class="control-label">Dirección</label>
												<div class="controls">
													<input type="text" class="span4" name="dir" value="<?php echo $_POST['dir']; ?>">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Telefono</label>
												<div class="controls">
													<input type="text" class="span2" maxlength="9" name="tel" value="<?php echo $_POST['tel']; ?>">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group info">
												<label class="control-label">Cargo</label>
												<div class="controls">
													<select name="car" id="car" class="span2">
														<?php
															$cn = new PostgreSQL();
															$query = $cn->consulta("SELECT DISTINCT * FROM admin.cargo");
															if ($cn->num_rows($query) > 0) {
																while ($result = $cn->ExecuteNomQuery($query)) {
																	if ($_POST['car']== $result['cargoid']) {
																		echo "<option value='".$result['cargoid']."' SELECTED>".$result['carnom']."</option>";
																	}else{
																		echo "<option value='".$result['cargoid']."'>".$result['carnom']."</option>";
																	}
																}
															}else{
																echo "<option>Nothing</option>";
															}
															$cn->close($query);
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="span10">
											<button type="reset" class="btn btn-warning"><i class="icon-trash"></i> Limpiar</button>
											<button type="button" onClick="savedEmployee();" class="btn btn-primary"><i class="icon-ok icon-white"></i> Guardar Cambios</button>
										</div>
									</div>
								</form>
							</div>
							<div class="tab-pane fade in" id="uplogin">
								<div class="control-group info">
									<label class="control-label">Seleccione</label>
									<div class="controls">
										<select name="emp" id="emp" class="span4" onClick="consulthing();">
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT empdni, empnom,empape FROM admin.empleados WHERE esid LIKE '19'");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														echo "<option value='".$result['empdni']."'>".$result['empnom'].", ".$result['empape']."</option>";
													}
												}
												$cn->close($query);
											?>
										</select>
									</div>
								</div>
								<div class="form-horizontal hide">
									<div class="control-group info">
										<label class="control-label">DNI</label>
										<div class="controls">
											<input type="text" class="span2" id="dnin" DISABLED>
										</div>
									</div>
									<div class="control-group info">
										<label class="control-label">User Name</label>
										<div class="controls">
											<input type="text" class="span2" id="user">
										</div>
									</div>
									<div class="control-group info">
										<label class="control-label">Password</label>
										<div class="controls">
											<input type="password" class="span2" id="pwdn">
										</div>
									</div>
									<div class="control-group info">
										<label class="control-label">Confirmar</label>
										<div class="controls">
											<input type="password" class="span2" id="pwdc">
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<button class="btn btn-warning t-black" onClick="savedlogin();"><i class="icon-ok"></i> Guardar Cambios</button>
										</div>
									</div>
								</div>
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
