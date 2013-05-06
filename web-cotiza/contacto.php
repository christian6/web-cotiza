<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Contactenos</title>
	<link rel="stylesheet" type="text/css" href="css/stylepags.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-mail.css">
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link href='http://fonts.googleapis.com/css?family=Paprika' rel='stylesheet' type='text/css'>
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
	<?php 
$ruc = $_GET['ruc'];
$nro = $_GET['nro'];
$rz = $_GET['rz'];
$key = $_GET['key'];


$s = $_POST['save'];

if (isset($s)) {
	$asu = $_POST['txtasunto'];
	$men = $_POST['txtmen'];
	$des = $_POST['txtmail'];

 	$codigohtml = $men;
	$email = "cvaldezchavez@gmail.com";
	$asunto = $asu;
	$cabeceras = 'From: '.$des . "\r\n" .
            	'Reply-To: '.$des. "\r\n" .
            	"Content-type: text/html\r\n".
            	'X-Mailer: PHP/' . phpversion();

    ini_set ("SMTP", "smtp.gmail.com"); 
    ini_set("sendmail_from","$des");
	date_default_timezone_set('America/Lima');

	if(mail($email,$asunto,$codigohtml,$cabeceras)){
		echo "<br /><br />";
    	echo "<label class='msg'>Enviado Correctamente!!</label>";
	}else{
		echo "<br /><br />";
    	echo "<label class='msg'>No se ha podido enviar su Correo.</label>";
	}

}else{
?>
	<form name="frm" method="POST" action="">
	<table>
		<thead>
			<tr>
			<th><button type="Submit" id="save" name="save">Enviar</button></th>
			<th>Enviar mail a ICR PERU S.A.</th>
			</tr>
		</thead>
		<tr>
			<td>De:</td>
			<td><input type="email" id="txtmail" name="txtmail" title="Ingrese Email Quien Envia" placeholder="ejemplo@dominio.com" REQUIRED /></td>
		</tr>
		<tr>
			<td>Nombre de:</td>
			<td><?echo $_SESSION['nom'];?></td>
		</tr>
		<tr>
			<td>Destinatario:</td>
			<td>info@icrperusa.com</td>
		</tr>
		<tr>
			<td>Asunto:</td>
			<td><input type="text" id="txtasunto" name="txtasunto" title="Ingrese el Asunto a Tratar" placeholder="Asunto" REQUIRED /></td>
		</tr>
		<tr>
			<td>Mensaje:</td>
			<td rowspan='2'><textarea id="txtmen" name="txtmen" placeholder="Ingrese su mensaje aquí." REQUIRED></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
		</tr>

		<tbody>
		</tbody>
	</table>
</form>
<?}?>
</section>
<?php } ?>
<footer>
</footer>
</body>
</html>