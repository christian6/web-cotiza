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
	<title>Administrador de Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />-->
    <!--<script src="../modules/jquery1.9.js"></script>
    <script src="../modules/jquery-ui.js"></script>-->
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/project.js"></script>
	<style>
		#txts{
			color: #000;
			font-weight: bold; 
			text-decoration: none;
		}
		#cont{
			background-color: gray;
			border-radius: .8em;
			padding: 18px;
			text-align: center;
		}
		#cont span{
			background-color: #CCC;
			border-radius: 5px;
			display: block;
			margin: 2px;
			padding: 10px;
			
		}
		#cont article{
			background-color: #CCC;
			border: 1px solid white;
			border-radius: 5px;
			display: inline-block;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<?php
		$res = 0;
		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT TRIM(e.empnom) ||' ' ||TRIM(e.empape) as n FROM ventas.proyectopersonal p
								INNER JOIN admin.empleados e
								ON p.empdni LIKE e.empdni
								WHERE p.proyectoid LIKE '".$_REQUEST['id']."'");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
			$responsable =  TRIM($result[0]);
			$res = 1;
		}
		$cn->close($query);
	?>
	<header></header>
	<section>
		<div class="container well">
		<div class="row show-grid">
			<div class="span8 well">
				<div class="row show-grid">
					<div class="span5">
						<h4>Administración de Proyectos</h4>
						<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['id']; ?>">
						<div class="controls">
							<button title="Nuevo Sector" class="btn btn-danger">
								<i class="icon-th-large"></i> 
								<span class="visible-desktop"><h6>Nuevo Sector</h6></span>
							</button>
							<button title="Nuevo Subproyecto" class="btn btn-danger">
								<i class="icon-th"></i>
								<span class="visible-desktop"><h6>Nuevo Sub-Proyecto</h6></span>
							</button>
							<?php
							if ($res == 1) {
								echo "<button class='btn btn-danger' DISABLED>";	
							}else{
							?>
							<button title="Agregar Responsable de Proyecto" class="btn btn-danger" onClick="showpersonal();">
							<?php 	
							}
							?>
								<i class="icon-user"></i>
								<span class="visible-desktop"><h6>Responsable</h6></span>
							</button>
						</div>
						<hr class="hs">
					</div>
				
					<div class="span4">
						<dl class="dl-horizontal">
							<dt>Encargado</dt>
							<dd>
								<?php echo $responsable; ?>
							</dd>
						</dl>
					</div>
				</div>
				<div class="row show-grid">
					<div class="span8">
						<h5>Sectores</h5>
						<hr class="hs">
						<div id="cont">
						<?php
						$cn = new PostgreSQL();
						$sql = "SELECT nroplano,sector,descripcion FROM ventas.sectores WHERE ";
						if ($_GET['sub'] != "") {
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ";
						}else{
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '' ";
						}
						$query = $cn->consulta($sql);
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
							?>
								<article>
									<a id="txts" href="sectores.php?nropla=<?php echo $result['nroplano']; ?>&proid=<?php echo $_GET['id']; ?>&sub=<?php echo $_GET['sub']; ?>">
										<i class="icon-flag"></i>
										<label for="label"><?php echo $result['nroplano']; ?></label>
										<label for="label"><?php echo $result['sector']; ?></label>	
									</a>
								</article>
							<?php
							}
						}
						$cn->close($query);
						?>
						</div>
					</div>
				</div>
			</div>
			<div class="span3 well">
				<div class="row show-grid">
					<div class="span3">
						<h5>Sub Proyectos</h5>
						<hr class="hs">
						<div id="cont">
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['id']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?id=".$_GET['id']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
								}
							}
							$cn->close($query);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div id="per" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h4>Asignar Responsable de Obra</h4>
			</div>
			<div class="modal-body">
				<div class="well">
					<div class="control-group">
						<div class="control-label">
							<label for="label"><b>Responsable del Proyecto</b></label>
						</div>
						<div class="controls">
							<select name="cboper" id="cboper">
								<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("SELECT empdni,empnom,empape FROM admin.empleados WHERE cargoid = 7 ");
									if ($cn->num_rows($query) > 0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											echo "<option value='".$result['empdni']."'>".$result['empnom'].", ".$result['empape']."</option>";
										}
									}else{
										echo "<option>--No se Encontraron--</option>";
									}
									$cn->close($query);
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-primary" onClick="saveper();">Guardar Cambios</button>
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