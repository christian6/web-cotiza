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
    <link rel="stylesheet" href="../css/styleint.css">
  	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <!--<script src="../modules/jquery1.9.js"></script>
    <script src="../modules/jquery-ui.js"></script>-->
    <script src="../bootstrap/js/bootstrap.js"></script>
    <style>
		.npro{
			background-color: rgba(20,20,20,.8);
			border: solid 1px #000;
			border-radius: 1em;
			/*cursor: pointer;*/
			height: 90%;
			margin-left: 2%;
			margin-bottom: .8em;
			max-height: 6em;
			max-width: 6em;
			text-align: center;
			width: 90%;
		}
		.npro:hover{
			box-shadow: 0 0 .8em #000;
		}
		h6{
			color: #FFF;
			margin: -.1em;
		}
		#sor { margin: 0; padding: 0; width: 100%; }
		.f{
			margin-right: 60%;
		}
		.e{ margin-right: 40%; }
		.dli{ 
			background: #E7EBEC;
			border-radius: 1em;
			border: 1px solid #DDD;
			font-family: Helvetica, Sans-serif, Arial;
			padding: .8em;
			margin: 0 .2em .2em 0;
			text-decoration: none;
		}
		.dli:hover {background-color: #EFDE6B; box-shadow: 0 0 .8em #AAAAAA; font-weight: bold;}
    </style>
    <script>
    	function openwin() {
			window.open('http://190.41.246.91/web-cotiza/intranet/proyectos.php');
		}
    </script>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<div class="control-label">
				<h4>Proyectos</h4>
				<hr style="margin-top: -.1em;">
				<div class="row show-grid">
					<div class="span2">
						<div class="row show-grid">
							<div class="span2">
								<a href="javascript:openwin();">
									<div class="npro">
										<h6>Nuevo</h6>
										<img src="../resource/add48.png" alt="">
									</div>	
								</a>
							</div>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span10 pull-right">
							<h4>Lista de Proyectos <h5>
								Se lista los ultimos 10 proyectos.</h5></h4>
							<div id="sor">
							<?php
							  	$cn = new PostgreSQL();
							  	$query = $cn->consulta("select p.proyectoid,p.descripcion,p.fecent,e.esnom from ventas.proyectos p
														inner join admin.estadoes e
														on p.esid = e.esid
														where p.esid not like '18' or p.esid not like '25'
														order by p.fecha desc
														");
							  	if ($cn->num_rows($query) > 0) {
							  		while ($result = $cn->ExecuteNomQuery($query)) {
							  		?>
							  		<a href="admin-project.php?id=<?php echo $result['proyectoid']; ?>">
							  			<div class='dli'>
							  				<p><?php echo $result['descripcion']; ?><i class='icon-eye-open pull-right'></i>
							  					<span class='f pull-right'><?php echo $result['fecent']; ?></span>
							  				</p>
							  			</div>
							  		</a>
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