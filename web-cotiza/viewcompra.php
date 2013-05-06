<?php
session_start();
include("datos/postgresHelper.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Vista Orden Compra</title>
	<link rel="stylesheet" type="text/css" href="css/stylepags.css">
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link href='http://fonts.googleapis.com/css?family=Paprika' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Croissant+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style-compra.css">
	<script type="text/javascript" src="js/validar.js"></script>
</head>
<body>
<?php include("../web/includes/analitycs.inc"); ?>
<header>
		<hgroup>
			<div id="cabeza">
				<h1>ICR PERU</h1>
			</div>
		<div id="sess">
<?

if ($_SESSION['access']==true) {
	$usr = $_SESSION['user'];
	$nom = $_SESSION['nom'];
?>
<label for="user">Usuario:</label>
<label for="usuario"><?echo $usr;?></label>
<label for="nom">Nombre: </label>
<label for="nombre"><?echo $nom;?></label>
<button id="btninit" onclick="init();">Inicio</button>
<button id="btnclose" onclick="destroy();">Cerrar Session</button>
<?
}else{
?>
<label for="user">Usuario:</label>
<input type="text" id="txtuser" name="txtuser" tittle="Usuario" placeholder="Username"/>
<label for="passwd">Password: </label>
<input type="Password" id="txtpss" name="txtpss" tittle="Password" placeholder="Password" />
<button id="btnin" onclick="validar();">Iniciar</button>
<a href="">Olvidaste tu Contrase?</a><label id="err"></label>
<?
	}
?>
		</div>
		</hgroup>
		<hgroup>
			<?php include("includes/menu.inc");?>
		</hgroup>
</header>
<?php if ($_SESSION['access']==true) {?>
<section>
<div id="contenedor">
	<h4>Lista de Orden de Compra Pendientes</h4>
	<?php
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT c.nrocompra,m.nomdes,e.empnom||', '||e.empape as nombre,c.fecha,c.fecent,s.esnom FROM logistica.compras c INNER JOIN admin.empleados e ".
							"ON c.empdni = e.empdni INNER JOIN admin.moneda m ON c.monedaid=m.monedaid INNER JOIN admin.estadoes s ON c.esid = s.esid ".
							"WHERE c.rucproveedor LIKE '".$_SESSION['ruc']."' AND c.esid LIKE '12'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<h3>Nro Compra: ".$result['nrocompra']."</h3>";
			echo "<li><p><b>Mandado por:</b> ".$result['nombre']."</p>";
			echo "<p><b>Moneda:</b> ".$result['nomdes']."</p>";
			echo "<p><b>Fecha Entrega:</b> ".$result['fecent']."</p>";
			echo "<p><b>Estado:</b> ".$result['esnom']."</p><a target='_blank' href='reports/pdfs/system/intordencomprapdf.php?nro=".$result['nrocompra']."&ruc=".$_SESSION['ruc']."'><img src='source/compra.png'/></a></li>";
		}
	}
	$cn->close($query);
	?>
</div>
</section>
<div style="height:60px;"></div>
<?php } ?>
<footer>
</footer>
</body>
</html>
