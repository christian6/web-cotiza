<!DOCTYPE html>
<?php
session_start();
include ("../../datos/postgresHelper.php");
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Pedidos a Almacen</title>
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint-pedido.css">
	<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="../../bootstrap/js/bootstrap.js"></script>
	<script src="../../bootstrap/js/bootstrap-dropdown.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.dropdown-toggle').dropdown();
		})
	</script>
</head>
<body>
<?php include("../../includes/analitycs.inc"); ?>
<?php include("menu-al.inc"); ?>
<header>
</header>

<section>
	<div id="msg">
		<p>Atención, el número del pedido es:</p>
		<label for="nro">Nro Pedido:</label>
		<label for="n"><?php echo $_REQUEST['num'];?></label>
		<button type="Button" onClick="javascript:location.href='../pedidosal.php'">Nuevo Pedido</button>
		<button type="Button" onClick="javascript:window.open('../../reports/almacen/pdf/rptpedidomat.php?nro=<?php echo $_REQUEST['num'];?>')">Ver Pedido</button>
	</div>
</section>
<footer>
</footer>
</body>
</html>