<?php session_start();?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>ICR PERU S.A.</title>
	<meta charset="utf-8" />
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/stylepags.css">
	<link rel="stylesheet" type="text/css" href="css/style-menu.css">
	<link href='http://fonts.googleapis.com/css?family=Bowlby+One' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Paprika' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/validar.js"></script>
</head>
<body>
<?php include("../web/includes/analitycs.inc"); ?>
	<header>
		<hgroup>
			<h1>ICR PERU S.A.</h1>
		</hgroup>
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
<?php } ?>
</div>
<?php
if ($_SESSION['access']==true) {
?>
		<div id="men">
			<nav>
				<ul>
					<li ><a id="home" class="home" href="#">Inicio</a></li>
					<li ><a class="cotiza" href="vistacotizacion.php">Cotización</a></li>
					<li ><a class="compra" href="viewcompra.php">Orden de Compra</a></li>
					<li ><a class="contacto" href="contacto.php">Contacto</a></li>
				</ul>
			</nav>
			<script>
				/*$(".home").hover(
					function (){
						/*$("#bac").show(500);
						$
						$("#bac").css("content","url('source/home.png')");
						$("#bac").css("-moz-content","url('source/home.png')");
						$("#bac").css("height","200px");
						$("#bac").css("width","200px");
						$("#bac").css("box-shadow","0px 0px 22px #1D1D1D");
						$("#bac").css("border-radius","15px");
						$("#bac").animate(function(){

						},1000);
  					},function(){
  						$("#bac").hide(500);
  						$("#bac").css("content","none");
  						$("#bac").css("box-shadow","none");
					}
				);
				$(".cotiza").hover(
					function (){
						$("#bac").show(500);
						$("#bac").css("content","url('source/cotizar.png')");
						$("#bac").css("height","200px");
						$("#bac").css("width","200px");
						$("#bac").css("box-shadow","0px 0px 22px #1D1D1D");
						$("#bac").css("border-radius","15px");
  					},function(){
  						$("#bac").hide(500);
  						$("#bac").css("content","none");
  						$("#bac").css("box-shadow","none");
					}
				);
				$(".compra").hover(
					function (){
						$("#bac").show(500);
						$("#bac").css("content","url('source/compra.jpg')");
						$("#bac").css("height","200px");
						$("#bac").css("width","200px");
						$("#bac").css("box-shadow","0px 0px 22px #1D1D1D");
						$("#bac").css("border-radius","15px");
  					},function(){
  						$("#bac").hide(500);
  						$("#bac").css("content","none");
  						$("#bac").css("box-shadow","none");
					}
				);
				$(".contacto").hover(
					function (){
						$("#bac").show(500);
						$("#bac").css("content","url('source/contacto.jpg')");
						$("#bac").css("height","200px");
						$("#bac").css("width","200px");
						$("#bac").css("box-shadow","0px 0px 22px #1D1D1D");
						$("#bac").css("border-radius","15px");
  					},function(){
  						$("#bac").hide(500);
  						$("#bac").css("content","none");
  						$("#bac").css("box-shadow","none");
					}
				);*/
			</script>
		</div>
	</header>
	<section>
	</section>
	<?}?>
	<footer></footer>
</body>
</html>