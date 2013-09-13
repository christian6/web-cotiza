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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<style>
		#cont{
			text-align: center;
			width: 100%;
		}
		#csec{
			background-color: #2d2d2d;
			border-radius: 1em;
			color: #FFF;
			display: inline-table;
			height: 5em;
			margin: 0 1em 1em 0;
			padding: 1em;
			width: 10em;
		}
		#csub{
			background-color: #2d2d2d;
			border-radius: 1em;
			color: #FFF;
			display: inline-table;
			height: 3em;
			line-height: 3em;
			width: 100%;
		}
	</style>
</head>
<body>
<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h3 class="t-d">Administrador de Sectores</h3>
			<div class="row show-grid">
				<div class="span8 well c-yellow">
					<div id="cont">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM ventas.sectores WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ORDER BY nroplano ASC");
							if ($cn->num_rows($query) > 0 ) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									?>
									<div id="csec">
										<a class='t-white' href="manager-detsec.php?pro=<?php echo $_GET['pro']; ?>&sub=<?php echo $_GET['sub']; ?>&sec=<?php echo $result['nroplano']; ?>">
										<i class="icon-list-alt icon-white"></i>
										<p>
											<?php echo $result['nroplano']; ?>	
										</p>
										<p><?php echo $result['sector']; ?></p>
										</a>
									</div>
									<?php
								}
							}
							$cn->close($query);
						?>
					</div>
				</div>
				<div class="span3 well c-yellow">
					<div id="cont">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM ventas.subproyectos WHERE proyectoid LIKE '".$_GET['pro']."' ORDER BY subproyecto ASC");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									?>
									<div id="csub">
										<a class='t-white' href="?pro=<?php echo $_GET['pro']; ?>&sub=<?php echo $result['subproyectoid']; ?>">
											<p><?php echo $result['subproyecto']; ?></p>
										</a>
									</div>
									<?php
								}
							}
							$cn->close($query);
						?>
					</div>
				</div>
				<sdiv class="span8 well c-yellow">
					<div id="cont">
						
					</div>
				</sdiv>
				<div class="span12">
					<div class="well c-blue-light t-info">
						<h4>Observaciones de Operaciones</h4>
						<div class="row show-grid">
							<div class="span12">
								<div class="span6 well">
										<div class="alert alert-success span4 ">
											<strong>Ventas</strong>
										</div>
										<div class="alert alert-warning span4 pull-right">
											<strong class="pull-right">Operaciones</strong>
										</div>
										<div class="alert alert-success span4 ">
											<strong>Ventas</strong>
										</div>
										<div class="alert alert-warning span4 pull-right">
											<strong class="pull-right">Operaciones</strong>
										</div>
								</div>
								<div class="span5">
									<div class="well">
										<h5>Escribe una observacion para este proyecto</h5>
										<div class="control-group">
											<div class="controls">
												<textarea name="proobs" id="proobs" style="width:96%;" maxlength="320" rows="5"></textarea>
											</div>
										</div>
										<div class="controls">
											<button class="btn btn-success t-d"><i class="icon-comment"></i> Publicar</button>
											<small>Solo se admiten 320 caracteres.</small>
										</div>
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
	<footer>
	</footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>