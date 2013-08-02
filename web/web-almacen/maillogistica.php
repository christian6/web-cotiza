<!DOCTYPE html>
<?php
session_start();
?>
<html lang='es'>
<head>
	<meta charset='utf-8'>
	<title>Enviar Correo a Logistica</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<style type="text/css">
		#msg{
			background-color: #a4b357;
			border-radius: .5em;
			font-weight: bold;
			margin: 0;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
			width: 14em;
		}

		table label{
			color: #FFF;
		}
		form
		{
			background-color: #a4b357;
			border-radius: .5em;
			box-shadow: 0px 0px 1.5em rgba(255,2255,255,1);
			margin-left: auto;
			margin-right: auto;
			padding: 2em;
			width: 30em;
		}
		input
		{
			width: 22em;
		}
		textarea
		{
			height: 8em;
			max-height: 8em;
			max-width: 24em;
			width: 22em;
		}
	</style>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<?php
if (isset($_POST['btnsend'])) {
	$asu = $_POST['txtasu'];
	$men = $_POST['txtmen'];
	$des = "cvaldezchavez@gmail.com";

 	$codigohtml = $men;
	$email = $des;
	$asunto = $asu;
	$cabeceras = 'From: logistica@icrperusa.com' . "\r\n" .
            'Reply-To: logistica@icrperusa.com' . "\r\n" .
            "Content-type: text/html\r\n".
            'X-Mailer: PHP/' . phpversion();

    ini_set ("SMTP", "smtp.gmail.com");
    ini_set("sendmail_from","logistica@icrperusa.com");
	date_default_timezone_set('America/Lima');
	echo "<div id='msg'>";
	if(mail($email,$asunto,$codigohtml,$cabeceras)){
		echo "<br />";
    	echo "<label>Enviado Correctamente!!</label>";
    	echo "<a href='estadopedido.php'><img src='../resource/regresar32.png' /></a>";
	}else{
		echo "<br />";
    	echo "<label>No se ha podido enviar su Correo.</label>";
    	echo "<a href='estadopedido.php'><img src='../resource/regresar32.png' /></a>";
	}
	echo "</div>";
}else{

?>
<section>
	<div class="container well">
		<div class="row-fluid">
			<h3>Enviar correo a Almacen</h3>
			<hr>
		</div>
		<form class="form" name="frmmail" method="POST" action="">
		<table>
			<thead>
			<tr>
				<th><button class="btn btn-primary" name="btnsend" title="enviar"><i class="icon-envelope icon-white"></i>  Enviar</button></th>
				<th><label class="pull-left" for="nro"><b>Nro Pedido <?php echo $_GET['nro'];?></b></label></th>
			</tr>
			<tr>
				<th><label for=''></label></th>
				<th></th>
			</tr>
			<tr>
				<th><label for="lblasu">Asunto:</label></th>
				<th><input type="text" id="txtasu" name="txtasu" title="Ingrese Asunto a tratar" placeholder="Asunto" REQUIRED /></th>
			</tr>
			<tr>
				<th><label for="lblnom">Nombre:</label></th>
				<th><input type="text" id="txtnom" name="txtnom" value="<?php echo $_SESSION['nom'];?>" REQUIRED /></th>
			</tr>
			<tr>
				<th><label for="lblmen">Mensaje:</label></th>
				<th rowspan="2"><textarea id="txtmen" name="txtmen" placeholder="Ingrese su mensaje" title="Mensaje"></textarea></th>
			</tr>
			<tr>
				<th></th>
				<th></th>
			</tr>
			</thead>
		</table>
		</form>
	</div>
	</section>
<?php
}
?>
<footer>
</footer>
</body>
</html>