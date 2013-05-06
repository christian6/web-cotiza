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
	<title>Administrador</title>
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style-manager.css">
	<script src="modules/jquery1.9.js"></script>
	<script src="modules/jquery-ui.js"></script>
	<link rel="stylesheet" href="modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
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
			});
			$(".about").click(function(){
				$( "#dialog" ).dialog( "open" );
			});
		});
	</script>
</head>
<body>
<header>
	<hgroup>
		<a href=""><h1>ICR PERU S.A.</h1></a>
	</hgroup>
	<hgroup>
		<div class="nav pull-right">
			<button class="btn btn-danger" data-toggle="collapse" data-target="#session">
			  <i class="icon icon-cog"></i>
			</button>
			<div id="session" class="collapse">
				<ul>
					<li><b>Nombre:</b> <?php echo $_SESSION['nom-icr'];?></li>
					<li><b>Cargo: </b><?php echo $_SESSION['car-icr'];?></li>
					<li><b><?php echo $_SESSION['user-icr'];?></b></li>
					<hr>
					<li><a href="includes/session-destroy.php"><b>Cerrar Session</b></a></li>
				</ul>
			</div>
		</div>
	</hgroup>
</header>
<section>
	<nav>
		<ul>
			<li class="admin">Administrador</li>
			<li class="log">Logistica</li>
			<li class="alm">Almacen</li>
			<li class="about">About</li>
		</ul>
	</nav>
	<div id="cadmin">
	</div>
	<div id="clog">
		<ul>
			<li><a href="http://190.41.246.91/web-cotiza/intranet/">Logistica</a></li>
			<li><a href="#">Cotización</a></li>
			<li><a href="#">Ver Keys</a></li>
			<li><a href="#">Comparar</a></li>
		</ul>
	</div>
	<div id="cal">
		<ul>
			<li><a href="web-almacen/home.php">Almacen</a></li>
			<li><a href="">Pedido al Almacen</a> </li>
			<li><a href="">Orden de Suministro</a> </li>
			<li><a href="">Salida de Materiales</a> </li>
			<li><a href="">Ingrese de Materiales</a> </li>
		</ul>
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
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(0);
}
?>