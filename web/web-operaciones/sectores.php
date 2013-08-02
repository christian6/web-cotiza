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
	<?php include ("includes/menu-operaciones.inc"); ?>
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
	<section>
		<div class="container well">
		<div class="row show-grid">
			<div class="span8 well">
				<div class="row show-grid">
					<div class="span5">
						<h4>Administración de Proyectos</h4>
						<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['proid']; ?>">
						<hr class="hs">
					</div>
				
					<div class="span4">
						<dl class="dl-horizontal">
							<dt>Responsable</dt>
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
							$sql .= "proyectoid LIKE '".$_REQUEST['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ";
						}else{
							$sql .= "proyectoid LIKE '".$_REQUEST['proid']."' AND TRIM(subproyectoid) LIKE '' ";
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
						<hr class="hs">
						<div id="cont">
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['proid']."'");
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