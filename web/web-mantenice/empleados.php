<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Empleados</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/employee.js"></script>
</head>
<body>
	<?php include("../web-manager/includes/menu-manager.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h4>Empleados</h4>
			
			<div class="btn-group">
				<button class="btn btn-success t-d" onClick="nuevo();"><i class="icon-plus"></i> Nuevo</button>
				<button class="btn" onClick="listemployee();"><i class="icon-list"></i> Catalogo</button>
			</div>
			<ul id="tab" class="nav nav-tabs">
			  <li class="active"><a href="#home" data-toggle="tab" >Datos Generales</a></li>
			  <li><a href="#profile" onClick="listemployee();" data-toggle="tab" >Catalogo</a></li>
			</ul>
			 
			<div class="tab-content">
				<div class="tab-pane active" data-toggle="tab" id="home">
					
					<form action="" method="POST" name="frmemployee" id="frmemployee">
						<div class="well c-green-light">
							<div class="row show-grid">
								<div class="span10">
									<div id="malert" class="alert alert-block fade in hide span4">
										<a class="close" data-dismiss="alert">&times;</a>
										<strong>¡Oh dios mio!</strong> Mejor que lo compruebes tú mismo, existen campos vacios.
									</div>
									<div id="aex" class="alert alert-danger hide alert-block span4">
										<a href="#" class="close" data-dismiss="alert">&times;</a>
										<strong>¡Oh no!</strong> Parece que el empleado ya existe.
									</div>
									<div class="alert alert-block alert-success hide span4" id="asu">
										<a href="#" data-dismiss="alert" class="close">&times;</a>
										<strong>¡Bien hecho!</strong> Se ha guardado tud datos correctamente.
									</div>
								</div>
							</div>
							<div class="row show-grid">
							
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">DNI</label>
										<div class="controls">
											<input type="text" class="span2 t-d" name="dni" id="dni" value="<?php echo $_POST['dni']; ?>" placeholder="Ingrese DNI" onKeypress="return alonenum(event);" maxlength="8" title="Ingrese DNI" REQUIRED />
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="control-group info">
										<label for="controls" class="t-info" onKeypress="return alonechar(event);">Nombre</label>
										<div class="controls">
											<input type="text" name="nom" id="nom" class="span4" value="<?php echo $_POST['nom']; ?>" REQUIRED />
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="control-group info">
										<label for="controls" class="t-info" onKeypress="return alonechar(event);">Apellidos</label>
										<div class="controls">
											<input type="text" name="ape" id="ape" class="span4" value="<?php echo $_POST['ape']; ?>">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Fecha Nacimiento</label>
										<div class="controls">
											<input type="text" name="fecnac" id="fecnac" class="span2" placeholder="aaaa-mm-dd" value="<?php echo $_POST['fecnac']; ?>">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Pais</label>
										<div class="controls">
											<select name="pais" id="pais" class="span2" onChange="javascript:submit();">
												<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT paisid,paisnom FROM admin.pais");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														if ($_POST['pais'] == $result['paisid']) {
															echo "<option value='".$result['paisid']."' SELECTED>".$result['paisnom']."</option>";
														}else{
															echo "<option value='".$result['paisid']."'>".$result['paisnom']."</option>";
														}
													}
												}
												$cn->close($query);
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Departamento</label>
										<div class="controls">
											<select  id="dep" name="dep" class="span2" onChange="javascript:submit();">
												<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT departamentoid,deparnom FROM admin.departamento WHERE paisid LIKE '".$_POST['pais']."'");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														if ($_POST['dep'] == $result['departamentoid']) {
															echo "<option value='".$result['departamentoid']."' SELECTED>".$result['deparnom']."</option>";
														}else{
															echo "<option value='".$result['departamentoid']."'>".$result['deparnom']."</option>";
														}
													}
												}
												$cn->close($query);
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Provincia</label>
										<div class="controls">
											<select  id="pro" name="pro" class="span2" onChange="javascript:submit();">
												<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT provinciaid,provnom FROM admin.provincia WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."'");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														if ($_POST['pro'] == $result['provinciaid']) {
															echo "<option value='".$result['provinciaid']."' SELECTED>".$result['provnom']."</option>";
														}else{
															echo "<option value='".$result['provinciaid']."'>".$result['provnom']."</option>";
														}
													}
												}
												$cn->close($query);
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Distrito</label>
										<div class="controls">
											<select  id="dis" name="dis" class="span2">
												<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT distritoid,distnom FROM admin.distrito WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' AND provinciaid LIKE '".$_POST['pro']."'");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														if ($_POST['dis'] == $result['distritoid']) {
															echo "<option value='".$result['distritoid']."' SELECTED>".$result['distnom']."</option>";
														}else{
															echo "<option value='".$result['distritoid']."'>".$result['distnom']."</option>";
														}
													}
												}
												$cn->close($query);
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="control-group info">
										<label for="controls" class="t-info">Direccion</label>
										<div class="controls">
											<input type="text" name="dir" id="dir" value="<?php echo $_POST['dir']; ?>" class="span4">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info" onKeypress="return alonenum(event);">Telefono</label>
										<div class="controls">
											<input type="text" id="tel" name="tel" value="<?php echo $_POST['tel']; ?>" class="span2">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Cargo</label>
										<div class="controls">
											<select  id="car" name="car" class="span2">
												<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT cargoid,carnom FROM admin.cargo");
													if ($cn->num_rows($query) > 0) {
														while ($result = $cn->ExecuteNomQuery($query)) {
															if ($_POST['car'] == $result['cargoid']) {
																echo "<option value='".$result['cargoid']."' SELECTED>".$result['carnom']."</option>";
															}else{
																echo "<option value='".$result['cargoid']."'>".$result['carnom']."</option>";
															}
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group info">
										<label for="controls" class="t-info">Estado</label>
										<div class="controls input-warning">
											<input type="text" id="est" class="span2 c-orange" value="Activo" title="Estado" DISABLED>
										</div>
									</div>
								</div>
								<div class="span10">
									<div class="row show-grid">
										<div class="span3">
											<button type="Button" onClick="reset();" class="btn btn-warning t-d" id="btnr" value="reset" title="Limpiar"><i class="icon-trash"></i> Limpiar</button>
											<button type="Submit" onClick="savedata();" class="btn btn-primary" id="btns" value="save"><i class="icon-ok icon-white"></i> Guardar Cambios</button>
										</div>
										<div class="span4">
											<div id="prog" class="progress progress-striped active hide span4">
												<div class="bar" style="width: 100%;"></div>
											</div>
										</div>
									</div>
								</div>
							
							</div>
						</div>
					</form>
				</div>
				<div class="tab-pane" data-toggle="tab" id="profile">
			  		<div class="well c-blue-light">
						<table class="table table-bordered table-condensed table-hover">
							<thead>
								<th>Item</th>
								<th>DNI</th>
								<th>Nombres y Apellidos</th>
								<th>Fecha Nacimiento</th>
								<th>Telefono</th>
								<th>Cargo</th>
								<th>Estado</th>
							</thead>
							<tbody id="list">
								
							</tbody>
						</table>
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