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
	<title>Proyectos Aprobados</title>
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
			border-radius: 5px;
			display: block;
			margin: 2px;
			padding: 10px;
			
		}
		.cont article{
			/*background-color: #084B8A;*/
			border: 1px solid white;
			border-radius: 3em;
			height: 6em;
			display: inline-table;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
		.cont article a{
			color: #FFF;
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
			<li class="active">Proyectos Aprobados</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<div class="row show-grid">
				<div class="span12">
				<h4>Lista de Proyectos Aprobados</h4>
				<div class="well">
					<form action="" method="POST" class="form-inline">
						<label for="label">Proyecto</label>
						<input type="text" class="span4" name="txtpro" id="txtpro" placeholder="Descripcion de Proyecto" />
						
						<button type="Submit" value="bs" class="btn"><i class="icon-search"></i> Buscar</button>
					</form>
				</div>
				</div>
			</div>
			<div class="row show-grid">
				<div class="span9">
					<div class="well c-g">
						<div class="cont">
							<?php
							$cn = new PostgreSQL();
							$sql = "SELECT proyectoid,descripcion FROM ventas.proyectos WHERE lower(descripcion) LIKE '%".$_POST['txtpro']."%' AND esid LIKE '55'";
							$query = $cn->consulta($sql);
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
								?>
									<article class="c-gd">
										<a id="txts" href="sectorsub.php?pro=<?php echo $result['proyectoid']; ?>">
											<i class="icon-fire icon-white"></i>
											<label for="label"><?php echo $result['proyectoid']; ?></label>
											<label for="label"><?php echo $result['descripcion']; ?></label>	
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
				<div class="span3">
					<div class="well">
						<div class="cont">
							
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