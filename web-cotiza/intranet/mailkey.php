<?php 
session_start();

include("../datos/postgresHelper.php");
//require("../modules/phpmailer/class.phpmailer.php");
//require("../modules/phpmailer/class.smtp.php");

?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset="utf-8" />
	<title>Enviar Correo a Proveedor</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint-mail.css">
</head>
<body>
	<header>
	<hgroup>
		<img src="../source/icrlogo.png">
			<div id="cab">
				<h1>Especialistas en Sistemas Contra Incendios</h1>
			</div>
	</hgroup>
</header>
<div id="sess">
<?php
$nom = $_SESSION['nom-icr'];
$car = $_SESSION['car-icr'];
?>
<p>
<label for="user" style="font-weight: bold;">Cargo:</label>
<?echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre: </label>
<?echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?echo $_SESSION['dni-icr']?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<?php if ($_SESSION['accessicr']==true) {?>
<section>
	<?include("includes/menu.inc");?>
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
	$email = $des;
	$asunto = $asu;
	$cabeceras = 	'From: logistica@icrperusa.com' . "\r\n" .
            		'Reply-To: logistica@icrperusa.com' . "\r\n" .
            		"Content-type: text/html\r\n".
            		'X-Mailer: PHP/' . phpversion();

    ini_set ("SMTP", "smtp.gmail.com"); 
    ini_set("sendmail_from","logistica@icrperusa.com");
	//date_default_timezone_set('America/Lima');

	if(mail($email,$asunto,$codigohtml,$cabeceras)){
		echo "<br /><br />";
    	echo "<label class='msg'>Enviado Correctamente!!!</label>";
	}else{
		echo "<br /><br />";
    	echo "<label class='msg'>No se ha podido enviar su Correo.</label>";
	}

}else{

$msg = "Saludos Sr(s). <b>$rz</b>, le hacemos llegar nuestra <b>Solicitud de Cotización</b>, el número de cotización y el key ".
		"para el ingreso a nuestra Web Site para relaizar lo solicitado.<br><br>".
		"Nro de Cotizacion: <b>".$nro."</b><br />".
		"Key Auto Generado: <b>".$key."<b><br><br>".
		"Ir a nuestra Web Site: <a href='http://190.41.246.91/web-cotiza/'>IR a PAGINA</a>".
		"<br /><br /><br />".
		"--<br />".
		"Dpto. de Logistica<br />".
		"<b>ICR PERU S.A.</b><br />".
		"Central: 51 1 371-0443<br />".
		"logistica@icrperusa.com<br />".
		"www.icrperusa.com";
?>
<form name="frm" method="POST" action="">
	<table>
		<thead>
			<tr>
			<th><button type="Submit" id="save" name="save">Enviar</button></th>
			<th>Enviar correo a <?echo $rz;?></th>
			</tr>
		</thead>
		<tr>
			<td>From:</td>
			<td>logitica@icrperusa.com</td>
		</tr>
		<tr>
			<td>From Name:</td>
			<td>Dpto. Logistica</td>
		</tr>
		<tr>
			<td>Destinatario:</td>
			<td><input type="email" id="txtmail" name="txtmail" title="Ingrese Email del destinatario" placeholder="ejemplo@dominio.com" REQUIRED /></td>
		</tr>
		<tr>
			<td>Asunto:</td>
			<td><input type="text" id="txtasunto" name="txtasunto" title="Ingrese el Asunto a Tratar" placeholder="Asunto" REQUIRED /></td>
		</tr>
		<tr>
			<td>Menssage:</td>
			<td rowspan='2'><textarea id="txtmen" name="txtmen" REQUIRED><?echo $msg;?></textarea></td>

		</tr>
		<tr>
			<td></td>
			<td></td>
		</tr>

		<tbody>
		</tbody>
	</table>
</form>
</section>
<?php
}
}?>
<footer>
</footer>
</body>
</html>