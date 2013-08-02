<!DOCTYPE html>
<html lang="es">
<head>
<meta charset='utf-8' />
<title> ICR PERU S.A.</title>
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/style2.css">
    <script type="text/javascript" src="js/validar.js"></script>
    <link rel="stylesheet" type="text/css" href="css/stylepags.css">
</head>
<body>
<?php include("../web/includes/analitycs.inc"); ?>
<div id="cuer">
<header>
	<hgroup>
		<div id="sess">
<?
session_start();

if ($_SESSION['access']==true) {
	$usr = $_SESSION['user'];
	$nom = $_SESSION['nom'];
?>
<label for="user">Usuario:</label>
<label for="usuario"><?echo $usr;?></label>
<label for="nom">Nombre: </label>
<label for="nombre"><?echo $nom;?></label>
<button id="btnse" onclick="vista();">Entrar</button>
<button id="btnclose" onclick="destroy();">Cerrar Session</button>
<?php
}else{
?>
<label for="user">Usuario:</label>
<input type="text" id="txtuser" name="txtuser" title="Usuario" placeholder="Username"/>
<label for="passwd">Password: </label>
<input type="Password" id="txtpss" name="txtpss" title="Password" placeholder="Password" />
<button id="btnin" onclick="validar();">Iniciar</button>
<a href="">Olvidaste tu Contrase?</a><label id="err"></label>
<?
	}
?>
		</div>
	</hgroup>
</header>
<section>
	<div id="line1"></div>
	<h4>ICR PERU S.A. <span>Especialistas en Instalaciones Contra Incendio</span></h4>
	<div id="line2">ICR<img src="source/detector.jpg"></div>
	<div id="line3">PERU<img src="source/contraincendios.jpg"></div>
	<div id="line4">S.A.<img src="source/gabinete.jpg"></div>
	<div id="image"><img src="source/cursos_contrato.jpg"></div>
	<div id="line5"><img src="source/Bombdie2.jpg"></div>
	<div id="line6"></div>
</section>
<footer>
</footer>
</div>
</body>
</html>