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
	<nav  id="menu" class="nav">  
    <ul>
        <li>
            <a  href="web-manager/index.php" title="">
                <span  class="icon"> <i aria-hidden="false" class="icon-home"></i></span><span>Manager</span>
            </a>
        </li>
        <li>      
            <a href="../web-cotiza/intranet/menu-int.php" title=""><span class="icon"> <i aria-hidden="false" class="icon-services"></i></span><span>Logistica</span></a>   
        </li> 
        <li>
            <a  href="web-almacen/home.php" title=""><span  class="icon"><i  aria-hidden="true" class="icon-portfolio"></i></span><span>Almacen</span></a>
        </li>
        <li>
            <a  href="web-ventas/index.php" title=""><span  class="icon"><i  aria-hidden="true" class="icon-blog"></i></span><span>Ventas</span></a> 
        </li>
        <li>
            <a  href="web-operaciones/index.php" title=""><span  class="icon"><i  aria-hidden="true" class="icon-team"></i></span><span>Operaciones</span></a>    
        </li>
        <li>
            <a  class="about" title=""><span  class="icon"><i  aria-hidden="true" class="icon-contact"></i></span><span>Acerca</span></a>
        </li>
    </ul>
</nav>
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