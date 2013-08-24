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
	<title>Sectores y Subproyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<style>
		.cont{
			text-align: center;
		}
		.cont span{
			background-color: #CCC;
			border-radius: .8em;
			display: block;
			padding: .6em;
		}
		.cont #car article{
			/*background-color: #084B8A;*/
			border: 3px dashed #2d2d2d;
			border-radius: 50% 75% 50%;
			display: block;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
		.cont article a{
			color: #333;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="aprobados.php">Proyecto Aprobados</a>
				<span class="divider">/</span>
			</li>
			<li class="active">admin-project</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h4>Sectores y Subproyectos</h4>
			<a href="aprobados.php" class="btn btn-success t-d"><i class="icon-arrow-left"></i> Volver</a>
			<hr>
			<div class="row show-grid">
				<div class="span8 well c-gd">
					<div class="cont">
						<center>
						<?php
							$cn = new PostgreSQL();
							$sql = "SELECT nroplano,sector,descripcion FROM ventas.sectores WHERE ";
							if ($_GET['sub'] != "") {
								$sql .= " proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."'";
							}else{
								$sql .= " proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE ''";
							}
							$query = $cn->consulta($sql);
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
								?>
								<div id="car" class="span2">
									<article class="c-orange">
										<a id="txts" href="pedidosector.php?sec=<?php echo $result['nroplano']; ?>&pro=<?php echo $_GET['pro']; ?>&sub=<?php echo TRIM($_GET['sub']); ?>">
											<i class="icon-map-marker"></i>
											<label for="label"><?php echo $result['nroplano']; ?></label>
											<label for="label"><?php echo $result['sector']; ?></label>	
										</a>
									</article>
									<div class="progress progress-success progress-striped active c-yellow" style="height: .5em;">
										<?php
											$c =  new PostgreSQL();
											$s = "SELECT COUNT(DISTINCT materialesid),(select count(flag) from  operaciones.metproyecto where flag like '0' AND 
												proyectoid LIKE '".$_GET['pro']."' AND 
												TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$result['nroplano']."')
												from operaciones.metproyecto WHERE proyectoid LIKE '".$_GET['pro']."' AND 
												TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$result['nroplano']."'";
											$q = $c->consulta($s);
											if ($c->num_rows($q) > 0) {
												$re = $c->ExecuteNomQuery($q);
												$por = (($re[1] * 100)/ $re[0]);
											}
											$c->close($q);
										?>
										<div class="bar" style="width: <?php echo $por;?>%;"></div>
									</div>
								</div>
								<?php
								}
							}
							$cn->close($query);
						?>
					</center>
					</div>	
				</div>
				<div class="span3 well c-g">
					<h5 class="t-orange">Sub Proyectos</h5>
						<div class="cont">
							<?php
							$cn = new PostgreSQL();							
							$query = $cn->consulta("SELECT DISTINCT subproyectoid,subproyecto FROM ventas.subproyectos WHERE  proyectoid LIKE '".$_GET['pro']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?pro=".$_GET['pro']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
								}
							}
							$cn->close($query);
							?>
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