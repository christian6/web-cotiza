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
	<title>Proyecto</title>
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
    <script src="js/project.js"></script>
    <style>
    	#cont{
    		display: inline-block;
    		border-radius: 0 50% 0 50%;
    		width: 6.5em;
    		padding: 1.5em;
    		margin: 1em;
    	}
    	#co{ text-align: center; }
		h6{
			color: #FFF;
			margin: -.1em;
		}
		#cont a{ text-decoration: none; }
    </style>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<div class="pull-center">
				<h3>Proyectos de Ventas</h3>
			</div>
				<div class="row show-grid">
					<div class="span1 well">
						<button class="btn btn-inverse" onClick="addnew();">
							<i class="icon-plus icon-white"></i>
							<h6>Nuevo</h6>
							<!--<img src="../resource/add48.png" alt="">-->
						</button>	
					</div>
						<div class="span10 well">
							<h4>Lista de Proyectos</h4>
							<div id="co" class="well c-g pull-center">
							<?php
							  	$cn = new PostgreSQL();
							  	$query = $cn->consulta("SELECT p.proyectoid,p.descripcion,e.esnom FROM ventas.proyectos p
														INNER JOIN admin.estadoes e
														ON p.esid = e.esid
														WHERE p.esid LIKE '17'
														order by p.fecha desc
														");
								if ($cn->num_rows($query) > 0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
									?>
									<div id="cont" class="c-yellow-light pull-center">
										<a href="" class="close pull-left">&times;</a>
										<a href="admin-project.php?id=<?php echo $result['proyectoid']; ?>">
											<i class='icon-eye-open'></i>
											<label><?php echo $result['descripcion']; ?></label>
											<label><?php echo $result['esnom']; ?></label>
										</a>
							  		</div>
							  		<?php
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
						  	</div>
						</div>
				</div>
			
		</div>
	<div id="mpro" class="span11 modal fade in hide" style="margin-left: -39%;">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h5>Agregar Proyecto</h5>
			<div id="awa" class="alert fade in hide span4">
				<a class="close" data-dismiss="alert">&times;</a>
				<strong>¡Oh dios mio!</strong> Mejor que lo compruebes tú mismo, existen campos vacios.
			</div>
			<div id="aer" class="alert alert-danger hide span4">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>¡Oh no!</strong> Parece que tienes un error, por que no llama a soporte.
			</div>
			<div class="alert alert-success hide span4" id="asu">
				<a href="#" data-dismiss="alert" class="close">&times;</a>
				<strong>¡Bien hecho!</strong> Se ha guardado tus datos correctamente.
				<p><strong>Proyecto Nro: <span id="nro"></span></strong></p>
			</div>
		</div>
		<div class="modal-body">
			<form action="" method="POST" name="frmproject" id="frmproject">
				<input type="hidden" id="new" name="new" value="<?php echo $_POST['new']; ?>" />
				<div class="row show-grid">
					<div class="span4">
						<div class="control-group info">
							<label for="controls" class="t-info">Descripcion de Proyecto</label>
							<div class="controls">
								<input type="text" name="des" id="des" class="span4" value="<?php echo $_POST['des']; ?>" placeholder="Ingrese Descripcion de Proyecto">
							</div>
						</div>	
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Fecha de Entrega</label>
							<div class="controls">
								<input type="text" id="fec" name="fec" placeholder="aaaa-mm-dd" class="span2" value="<?php echo $_POST['fec']; ?>" >
							</div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group info">
							<label for="controls" class="t-info">Cliente</label>
							<div class="controls">
								<select name="cli" id="cli" class="span4">
									<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("SELECT ruccliente,nombre FROM admin.clientes WHERE esid LIKE '41' ORDER BY nombre ASC");
									if ($cn->num_rows($query) > 0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											if ($_POST['cli'] == $result['ruccliente']) {
												echo "<option value='".$result['ruccliente']."' SELECTED>".$result['nombre']."</option>";
											}else{
												echo "<option value='".$result['ruccliente']."'>".$result['nombre']."</option>";
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
								<div class="span3">
									<div class="control-group info">
										<label for="controls" class="t-info">Distrito</label>
										<div class="controls">
											<select  id="dis" name="dis" class="span3">
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
							<label for="controls" class="t-info">Dirección</label>
							<div class="controls">
								<input type="text" id="dir" name="dir" placeholder="Ave jr calle" class="span4" value="<?php echo $_POST['dir']; ?>" >
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Estado</label>
							<div class="controls">
								<input type="text" class="span2" value="ACTIVO" DISABLED>
							</div>
						</div>
					</div>
					<div class="span4">
						<div class="control-group info">
							<label for="controls" class="t-info">Observación</label>
							<div class="controls">
								<textarea name="obs" id="obs" rows="4" class="span4"><?php echo $_POST['obs']; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-warning t-d pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
			<button class="btn btn-primary" onClick="saveproject();"><i class="icon-ok icon-white"></i> Guardar Cambios</button>
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