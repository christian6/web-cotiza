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
	<title>Proyectos</title>
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
		#txts{
			color: #000;
			font-weight: bold; 
			text-decoration: none;
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
			<li class="active">Proyectos</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h4>Proyectos</h4>
			<hr class="hs">
			<div class="row show-grid">
						
						<div class="span11">
							
								<div class="span4">
									<div class="control-group">
										<div class="control-label">
											<label for="label">Descripción de Proyecto:</label>
										</div>
										<div class="controls">
											<input type="text" id="txtdes" name="txtdes" class="span4" onKeyup="listpro();">
										</div>
									</div>
								</div>	
							<div class="span3">
								<div class="control-group">
									<div class="control-label">
										<label for="label">Año</label>
									</div>
									<div class="controls">
										<select name="cboa" id="cboa">
										<?php
											$cn = new PostgreSQL();
											$query = $cn->consulta("SELECT DISTINCT extract(year from fecha)::char(4) as an FROM ventas.proyectos ORDER BY an DESC");
											if ($cn->num_rows($query) > 0) {
												echo "<option value='all'>--Todos --</option>";
												while ($result = $cn->ExecuteNomQuery($query)) {
													echo "<option value='".$result[0]."'>".$result[0]."</option>";
												}
											}
											$cn->close($query);
										?>
										</select>
									</div>
								</div>
						</div>
					</div>
			</div>
			<div id="cont">
				<?php
				$cn = new PostgreSQL();
				$qsql = "SELECT p.proyectoid,p.descripcion,p.fecent,e.esnom FROM ventas.proyectos p
						INNER JOIN admin.estadoes e
						ON p.esid = e.esid
						INNER JOIN ventas.proyectopersonal r
						ON p.proyectoid LIKE r.proyectoid
						WHERE  ";

				if (sestrust('sk') == 1) {
					$qsql .= " p.esid LIKE '59' ORDER BY p.fecha DESC";
				}else if (sestrust('sk') == 0) {
					$qsql .= "r.empdni LIKE '".$_SESSION['dni-icr']."' AND p.esid LIKE '59' ORDER BY p.fecha DESC";
				}

				$query = $cn->consulta($qsql);
				if ($cn->num_rows($query) > 0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
				?>
				<article>
					<a id="txts" href="sectores.php?proid=<?php echo $result['proyectoid']; ?>">
						<i class="icon-map-marker"></i>
						<label for="label"><?php echo $result['proyectoid']; ?></label>
						<p><?php echo $result['descripcion']; ?></p>	
					</a>
				</article>
				<?php
					}
				}
				$cn->close($query);
				?>
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