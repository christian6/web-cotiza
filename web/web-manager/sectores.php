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
	<title>Administrador de Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/proyectos.js"></script>
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
		#cont article, #ad{
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
	<?php include ("includes/menu-manager.inc"); ?>
	<?php
		$res = 0;
		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT TRIM(e.empnom) ||' ' ||TRIM(e.empape) as n FROM ventas.proyectopersonal p
								INNER JOIN admin.empleados e
								ON p.empdni LIKE e.empdni
								WHERE p.proyectoid LIKE '".$_REQUEST['proid']."'");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
			$responsable =  TRIM($result[0]);
			$res = 1;
		}
		$cn->close($query);
	?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="proyectoma.php">Proyectos</a>
				<span class="divider">/</span>
			</li>
			<li class="active">Proyecto Admin</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<div class="span5">
				<h3>Administración de Proyectos</h3>
				<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['proid']; ?>">
			</div>
		<div class="row show-grid">
			<div class="span8 well">
				<div class="btn-group">
				<?php
					$sql = "SELECT COUNT(*) FROM operaciones.metproyecto WHERE ";
					if ($_GET['sub'] == "") {
						$sql .= "proyectoid LIKE '".$_GET['proid']."'";
					}else{
						$sql .= "proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."'";
					}
					$cn = new PostgreSQL();
					$query = $cn->consulta($sql);
					if ($cn->num_rows($query) > 0) {
						$result = $cn->ExecuteNomQuery($query);
					}
					$cn->close($query);
					if ($result[0] > 0) {
						echo "<button class='btn' DISABLED><i class='icon-ok'></i> Aprobar</button>";
					}else{
				?>
				
				<button class="btn btn-info t-d" onClick="javascript:location.href='comparealllist.php?pro=<?php echo $_GET['proid']; ?>&sub=<?php echo $_GET['sub']; ?>'">
					<i class="icon-list"></i> Lista de Proyecto</button>
					<?php } ?>
				<!--<button class="btn btn-danger" onClick="viewdel();">Eliminar Lista</button>-->
				<?php if($res != 1){ ?>
				<button class="btn btn-danger t-d" onClick="showuser();"><i class="icon-user"></i> Asignar Responsable</button>
				<?php } ?>
				</div>
				<div class="alert alert-warning">
					<strong>Responsable </strong> <?php echo $responsable; ?>.
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
							$sql .= " proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."'";
						}else{
							$sql .= " proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE ''";
						}
						$query = $cn->consulta($sql);
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
							?>
								<article>
									<a id="txts" href="detsectores.php?nropla=<?php echo $result['nroplano']; ?>&proid=<?php echo $_REQUEST['proid']; ?>">
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
						<div id="cont">
							<?php
							$cn = new PostgreSQL();							
							$query = $cn->consulta("SELECT DISTINCT subproyectoid,subproyecto FROM ventas.subproyectos WHERE  proyectoid LIKE '".$_GET['proid']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?proid=".$_GET['proid']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
								}
							}
							$cn->close($query);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="span8 well">
				<h5>Adicionales del Proyecto</h5>
				<div id="cont">
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['proid']."' 
											AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ");
					if ($cn->num_rows($query) > 0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<div id='ad'>".$result['descrip']."</div>";
						}
					}
					$cn->close($query);
					?>
				</div>
			</div>
		</div>
		</div>
		<div id="dellist" class="modal fade in span3 hide" style="margin-left: -10%;">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Eliminar Lista de Materiales del Proyecto</h4>
			</div>
			<div class="modal-body">
				<p>
					<label id="lblpro" for="label"><?php echo $_GET['proid']; ?></label>
					Que se desa eliminar los sectores del Proyecto
					o los Subproyectos?
				</p>
				<div class="controls">
					<label class="checkbox">
						<input type="checkbox" id="cht" name="cbtn"> Sectores
					</label>
					<label class="checkbox">
						<input type="checkbox" id="chs" name="cbtn"> SubProyectos
					</label>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-danger" onClick="delsectores();">Aceptar</button>
			</div>
		</div>
		<div id="per" class="modal fade in hide c-yellow-light">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h4 class="t-warning">Asignar Responsable de Obra</h4>
				<input type="hidden" id="pro" value="<?php echo $_GET['proid']; ?>">
			</div>
			<div class="modal-body">
				<div class="well c-yellow-light">
					<div class="control-group info">
						<label for="controls" class="control-label"><b>Responsable del Proyecto</b></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">
									<i class="icon-user"></i>
								</span>
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