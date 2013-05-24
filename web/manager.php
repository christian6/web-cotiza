<?php
include ("includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect(1);
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, inicial-scale=1.0" />
	<title>Administrador</title>
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style-manager.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="modules/jquery-ui.js"></script>
	<link rel="stylesheet" href="modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.css">
	<script src="bootstrap/js/bootstrap.js"></script>
	<script>
		$(document).ready(function(){

			$( "#dialog" ).dialog({
      			autoOpen: false,
      			show: {
      			  effect: "blind",
      			  duration: 1000
      			},
      			hide: {
      			  effect: "explode",
      			  duration: 1000
      			}
    		});
			/*
			$(".admin").click(function(){
				$("#clog").hide(400);
				$("#cal").hide(400);
				$("#cadmin").show("slow");
  				$("#cadmin").css("background-color","rgba(143,200,0,1)");
			});
			$(".log").click(function(){
				$("#cadmin").hide(400);
				$("#cal").hide(400);
				$("#clog").show("slow");
  				$("#clog").css("background-color","rgba(255,255,136,1)");
			});
			$(".alm").click(function(){
				$("#cadmin").hide(400);
				$("#clog").hide(400);
				$("#cal").show("slow");
  				$("#cal").css("background-color","rgba(254,191,1,1)");
			});*/
			$(".about").click(function(){
				$( "#dialog" ).dialog( "open" );
			});
		});
	</script>
</head>
<body>
<!---<header>
	<hgroup>
		<a href=""><h1>ICR PERU S.A.</h1></a>
	</hgroup>
</header>-->
<section>
	<!--<nav>
		<ul>
			<li class="admin">Administrador</li>
			<li class="log"><a href="../../web-cotiza/intranet/">Logistica</a></li>
			<li class="alm"><a href="web-almacen/home.php">Almacen</a></li>
			<li class="vent"><a href="web-ventas/index.php">Ventas</a></li>
			<li class="about">About</li>
		</ul>
	</nav>-->
	<div class="container">
		<div class="row grid-show">
			<div class="span12">
				<div class="row grid-show">
					<div class='img span3' id='img-1'>
						<div class="mar">
							<a href="manager.php">
								<div id="manager">
									Administrador
								</div>
							</a>
						</div>
					</div>	
					<div class='img span3' id='img-1'>
						<div class="mar">
							<a href="../web-cotiza/intranet/index.php">
								<div id="logistic">
									Logistica
								</div>
							</a>
						</div>
					</div>	
					<div class='img span3' id='img-1'>
						<div class="mar">
							<a href="web-almacen/home.php">
								<div id="almacen">
									Almacen
								</div>
							</a>
						</div>
					</div>	
					<div class='img span3' id='img-1'>
						<div class="mar">
							<a href="web-ventas/">
								<div id="venta">
									Ventas
								</div>
							</a>
						</div>
					</div>	
					<div class='img span3' id='img-1'>
						<div class="mar">
							<a class="about" href="#">
								<div id="about">
									About
								</div>
							</a>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
	
	<div id="dialog" title="About">
		<p>Esta es una interfaz para controlar las actiones de los modulos 
			de Logistica y Almacen.
		</p>
		<p>
			Derechos Reservados
		</p>
	</div>
</section>
</body>
</html>
<?php
}else{
	redirect(0);
}
?>