<?php
session_start();

if(isset($_SESSION['car-icr'])){
	$car = $_SESSION['car-icr'];
	$car = strtolower($car);
	switch ($car) {
		case 'administrator':
		case 'gerencia':
			header('Location: http://190.41.246.91/web/manager.php');
			break;
		case 'logistica':
			header('Location: http://190.41.246.91/web-cotiza/intranet/menu-int.php');
			break;
		case 'almacen':
			header('Location: http://190.41.246.91/web/web-almacen/home.php');
			break;
		default:
			header('Location: ');
			break;
	}
}
?>