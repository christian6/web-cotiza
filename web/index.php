<?php
include ("includes/valsession.php");
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
	<meta chartset="utf-8" />
	<title>Login</title>
    <link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style-login.css">
    <script src="modules/jquery1.9.js"></script>
    <script type="text/javascript" src="modules/md5-min.js"></script>
    <script type="text/javascript" src="js/home.js"></script>
</head>
<body>
    <?php include("includes/analitycs.inc"); ?>
<header>
    <div id="cab">
        <h1>Bienvenidos a ICR Instalaciones</h1>
    </div>
</header>
<div id="cir"></div>
<section>
	<div id="login-box" class="login-popup">
          <form id="frm" name="frm" method="post" class="signin" action="?login=true" >
                <fieldset class="textbox">
            	<label class="username">
                <span>Username</span>
                <input id="username" name="username" value="" type="text" autocomplete="on" placeholder="Username">
                </label>
                <label class="password">
                <span>Password</span>
                <input id="password" name="password" value="" type="password" placeholder="Password">
                </label>
                <input type="hidden" id="log" name="log" value="" />
                <button class="button" type="Button">Ingresar</button>
                <p>
                    <label for="lblres" class="result"></label>
                </p>
                <p>
                <a class="forgot" href="#">Se a olvidado su password?</a>
                </p>
                </fieldset>
          </form>
</div>
</section>
</body>
</html>
