<?php

$return = "success";

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/")) {
	mkdir($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/", 0777);
	chmod($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/", 0777);
}
if ($_POST['sub'] != '') {
	if (!file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sub']."/")) {
		mkdir($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sub']."/",0777);
	}
	/*if (!file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sub']."/".$_POST['sec']."/")) {
		mkdir($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sub']."/".$_POST['sec']."/", 0777);
	}*/
	$archivador = $_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sub']."/".$_POST['sec'];
}else{
	/*if (!file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sec']."/")) {
		mkdir($_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sec']."/", 0777);
	}*/
	$archivador = $_SERVER['DOCUMENT_ROOT']."/web/project/".$_POST['pro']."/".$_POST['sec'];
}


//$nombre_archivo = $_FILES['archivo']['name'];

//$tipo_archivo = $_FILES['archivo']['type'];

//$tamano_archivo = $_FILES['archivo']['size'];

$tmp_archivo = $_FILES['archivo']['tmp_name'];

$archivador = $archivador . ".pdf";

if (!move_uploaded_file($tmp_archivo, $archivador)) {
	$return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.", 'status' => 'error');
}
if ($return == 'success') {
	chmod($archivador, 0777);
	echo $return;
}else{
	echo "fail";
}

?>