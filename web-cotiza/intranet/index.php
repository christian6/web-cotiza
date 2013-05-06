<?php
session_start();

include("../datos/postgresHelper.php");

$user = $_POST['txtuser'];
$pwd = $_POST['txtpwd'];

if ($_SESSION['accessicr']==true) {
	echo '<meta http-equiv="refresh" content="0;url=menu-int.php">';
}

	if (isset($user) && isset($pwd)) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT admin.spverifylog('$user','$pwd')");
		if ($cn->num_rows($query)>0) {
			$result = $cn->ExecuteNomQuery($query);
			$cn->close($query);
			if (strlen($result[0]) != 0) {
				$_SESSION['accessicr'] = true;
				$_SESSION['dni-icr'] = substr($result[0], 0,8);
				$_SESSION['user-icr'] = substr($result[0], 8);
				$cn = new PostgreSQL();
				$sql = "SELECT e.empnom,e.empape,c.carnom FROM admin.empleados e INNER JOIN admin.cargo c ON c.cargoid = e.cargoid WHERE empdni LIKE TRIM('".substr($result[0], 0,8)."')";
				$query = $cn->consulta($sql);
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						$_SESSION['nom-icr'] = $result['empnom']." ".$result['empape'];
						$_SESSION['car-icr'] = $result['carnom'];
					}
				}
				$cn->close($query);
				echo '<meta http-equiv="refresh" content="0;url=menu-int.php">';
			}else{
			$msg = "Usuario o Contraseña Incorrectos";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset="utf-8" />
	<title>Login - Intranet</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint-login.css">
	<link href="http://fonts.googleapis.com/css?family=Paprika" type="text/css" rel="stylesheet" />
</head>
<body>
	<header>
		<div id="he"><img src="../source/icrlogo.png"></div>
	</header>
	<section>
		<div id="im">
			<img src="../source/red-contraincendio.jpg">
		</div>
			<div id="log">
				<h4>Login Intranet</h4>
				<div id="bo">
				<form name="frm" method="POST" action="">
				<p><label>User Name:</label></p>
				<p><input type="text" id="txtuser" name="txtuser" title="Ingrese su Usuario" placeholder="Usuario" REQUIRED /></p>
				<p><label>Password:</label></p>
				<p><input type="Password" id="txtpwd" name="txtpwd" title="Ingrese su Contraseña" placeholder="Contraseña" REQUIRED/ ></p>
				<p id="btn"><button type="Submit">Iniciar Session</button></p>
				</form>
				<p><a href="">Olvidaste tu contrasenia?</a></p>
				<label style="color:red; font-size:10p;font-family:Arial;"><?echo $msg;?></label>
				</div>
			</div>

	</section>
<footer>
</footer>
</body>
</html>