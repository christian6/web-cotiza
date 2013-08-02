<?php
session_start();
?>
<!DOCTYPE html>
<html lang='es-ES'>
<head>
	<meta charset='utf-8' />
	<title>Bienvenido a la Intranet ICR PERU</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint-menu.css">
	<link href="http://fonts.googleapis.com/css?family=Finger+Paint" type="text/css" rel="stylesheet" />
	<link href="http://fonts.googleapis.com/css?family=Redressed" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" ></script>
	<script>
		$(document).ready(
			function(){
				$.ajax({
					type: "POST",
					url:"../includes/cambio.php",
					success:function(datos){
						alert(datos);
					}
				});
			});
	</script>
</head>
<body>
	<div id="cu">
<div id="sess">
<?php
$nom = $_SESSION['nom-icr'];
$car = $_SESSION['car-icr'];
?>
<p>
<label for="user" style="font-weight: bold;">Cargo:</label>
<?php echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre:</label>
<?php echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?php echo $_SESSION['dni-icr'];?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?php echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<header>
<h1>ICR PERÚ S.A.</h1>
</header>
<?php if ($_SESSION['accessicr']==true) { ?>
<section>
		<nav>
			<li class="lihome"><a href="menu-int.php"><img src="../source/inicio48.png"><span class="home">Inicio</span></a></li>
			<li class="parent"><a href=""><img src="../source/cotiza48.png"><span class="coti">Cotizacion</span></a>
				<ul>
					<li><a href="cotizacion.php"><img src="../source/cot48.png"><span>Cotizacion</span></a></li>
					<li><a href="cotcalc.php"><img src="../source/excel32.png"><span>Cotizacion con Excel</span></a></li>
					<li><a href="viewkey.php"><img src="../source/llave32.png"><span>Ver Keygens</span></a></li>
					<li><a href="compararcot.php"><img src="../source/solti48.png"><span>Comparar Cotizacion</span></a></li>
				</ul>
			</li>
			<li class="limant"><a href=""><img src="../source/mant48.png"><span class="mant">Mantenimiento</span></a>
				<ul>
					<li><a href="proyectos.php"><img src="../source/mapa48.png"><span>Proyectos</span></a></li>
					<li><a href="clientes.php"><img src="../source/cliente48.png"><span>Clientes</span></a></li>
					<li><a href="moneda.php"><img src="../source/moneda64.png"><span>Monedas</span></a></li>
					<li><a href="proveedor.php"><img src="../source/proveedor64.png"><span>Proveedores</span></a></li>
					<li><a href="estados.php"><img src="../source/mago48.png"><span>Estados</span></a></li>
					<li><a href="login-pro.php"><img src="../source/llave-p48.png"><span>Login Proveedor</span></a></li>
					<li><a href="login-per.php"><img src="../source/llave-e48.png"><span>Login Personal</span></a></li>
				</ul>
			</li>
			<li class="lireport"><a href=""><img src="../source/libro48.png"><span class="report">Reportes</span></a>
				<ul>
					<li><a href="report/rptcotizacion.php"><img src="../source/item48.png"><span>Cotización</span></a></li>
					<li><a href="report/rptcompra.php"><img src="../source/item48.png"><span>Orden de Compra</span></a></li>
				</ul>
			</li>
			<li class="liabout"><a href=""><img src="../source/about48.png"><span class="about">About</span></a></li>
		</nav>
</section>
<?php }?>
</div>
<footer>
		<button type="Button" title="Obtener Tipo de cambio" onClick=""><img src="../source/cambio64.png"></button>
</footer>
</body>
</html>