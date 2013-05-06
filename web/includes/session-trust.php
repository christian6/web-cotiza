<?php
session_start();

function sesaccess()
{
	$res = 'not';
	if ( isset($_SESSION['accessicr']) ){
		$access = $_SESSION['accessicr'];
		if ($access == true) {
			$res = 'ok';
		}elseif ($access == false || $access == '') {
			$res = 'not';
		}
	}

	return $res;
}

function sestrust($llave='')
{
	$res = 0;
	$car = $_SESSION['car-icr'];
	$car = strtolower($car);
	switch ($llave) {
		case '':
			header('Location: http://190.41.246.91/web/includes/notpermit.php');
			exit;
			break;
		case 'sk':
			if ($car == "administrator" || $car == "gerencia") {
				$res = 1;
			}else{
				$res = 0;
			}
			break;
		case 'k':
				$res = 1;
			break;
		default :
			exit;
			break;
	}

	return $res;
}

function redirect($pro=0)
{
	if ($pro == 0) {
		Header('Location: http://190.41.246.91/web/not-access.php');
	}elseif($pro == 1){
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
				header('Location: http://190.41.246.91/web/');
				break;
		}
	}
}

function login()
{
	header('Location: http://190.41.246.91/web/');
}

?>