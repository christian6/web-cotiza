<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Upload File</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../../css/styleint.css">
</head>
<body>
	<div id="fullscreen-icr" style="display: block;"></div>
	<div id="loading-icr">
		<h4>Subiendo Archivos</h4>
	</div>
<?php
	$ruta_destino = $_SERVER['DOCUMENT_ROOT']."/web/tmp/";
	$nombre_fichero=$_FILES['txtup']['name']; 
	$ext = explode('.', $nombre_fichero);
	$num = (count($ext) - 1);
	$nombre_temporal_que_le_ha_dado_php = $_FILES["txtup"]["tmp_name"];
	//echo $nombre_temporal_que_le_ha_dado_php;
	//lo movemos donde queramos 
    move_uploaded_file($nombre_temporal_que_le_ha_dado_php,$ruta_destino.$_REQUEST['nro'].".".$ext[$num]);

    $nom = $_REQUEST['nro'].".".$ext[$num];
	//es aconsejable ponerle permisos 
	chmod($ruta_destino.$nom,0777);
	//echo $_REQUEST['nro'];
	header("Location: ../sectores.php?nropla=".$_REQUEST['nro']."");

	// Ahi que leer el archivo que se acaba de subir
	
	error_reporting(E_ALL ^ E_NOTICE);
	require_once '../modules/excel_reader2.php';

	$data = new Spreadsheet_Excel_Reader();
	$data->read($_SERVER['DOCUMENT_ROOT']."/web/tmp/".$nom);

	for ($fila=1; $fila <= $data->rowcount(); $fila++) {
		/* Grabar en la base de datos con los datos del excel*/
		/*$cn = new PostgreSQL();
		$query = $cn->consulta( "SELECT * FROM logistica.spgrabartmpcant('".$valor."','".$data->sheets[0]['cells'][$fila][1]."',".$data->sheets[0]['cells'][$fila][2].") ");
		$cn->affected_rows($query);
		$cn->close($query);*/
	}

	}

?>	
</body>
</html>
