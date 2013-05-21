<?php
session_start();
include("../datos/postgresHelper.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset='utf-8' />
	<title>Cotizador con Excel</title>
	<link rel="stylesheet" href="../css/styleint.css">
	<script type="text/javascript" src="../js/cotcalc.js"></script>
	<script type="text/javascript" src="../ajax/ajxupcalc.js"></script>
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
		<?php include("includes/menu.inc"); ?>
		<aside>
			<select id="cbotpro" multiple>
				<?
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT proyectoid,descripcion FROM ventas.proyectos ORDER BY descripcion ASC");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value=".$result['proyectoid'].">".$result['descripcion']."</option>";
					}
				$cn->close($query);
				}
				?>
			</select>
			<button id="btnadd" onclick="addproyecto();"> > </button>
			<button id="btndel" onclick="rmproyecto();"> < </button>
			<select id="cboprosel" multiple >
			</select>
			<button id="btnok" onclick="calc();">OK</button>
		</aside>
	<article>
		<div id="upcalc">
		</div>
		<?php
function array_recibe($url_array) { 
     $tmp = stripslashes($url_array); 
     $tmp = urldecode($tmp); 
     $tmp = unserialize($tmp); 

    return $tmp; 
} 


$array=$_REQUEST['array']; 
  // el método de envio usado. (en el ejemplo un link genera un GET. En el formulario se usa POST podria ser GET tambien ...) 

$archivo = $_FILES["calc"]['name'];
if(isset($archivo)){

	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM logistica.tmpcantpro");
	$cn->affected_rows($query);
	$cn->close($query);

$ruta_destino = $_SERVER['DOCUMENT_ROOT']."/web-cotiza/tmp/";
$tot = count($_FILES["calc"]["name"]);
if ($archivo != "") {
	$count = 0;
    foreach ($_FILES["calc"]["error"] as $key => $error) { 
        if ($error == UPLOAD_ERR_OK) { //se ha subido bien 
        //Cojemos los nombres del fichero 
        $nombre_fichero=$_FILES["calc"]["name"][$key]; 
        $nombre_temporal_que_le_ha_dado_php=$_FILES["calc"]["tmp_name"][$key]; 
        //lo movemos donde queramos 
        move_uploaded_file($nombre_temporal_que_le_ha_dado_php,$ruta_destino.$nombre_fichero); 
        //es aconsejable ponerle permisos 
        chmod($ruta_destino.$nombre_fichero,0777); 
        $status = "Se subio los archivos Correctamente";
        $count++;
        }//fin del if 
        else{ 
            $status = $_FILES["imagen"]["name"][$key]." se subió con errores!!!"; 
        } 
    }

} else {
    $status = "Error al subir archivo.";
}

error_reporting(E_ALL ^ E_NOTICE);
require_once '../modules/excel_reader2.php';

$array=array_recibe($array); 

foreach ($array as $indice => $valor){ 
echo $indice." = ".$valor."<br>";
$data = new Spreadsheet_Excel_Reader();
$data->read($_SERVER['DOCUMENT_ROOT']."/web-cotiza/tmp/".$_FILES['calc']['name'][$indice]);

for ($fila=1; $fila <= $data->rowcount(); $fila++) {
	$cn = new PostgreSQL();
	$query = $cn->consulta( "SELECT * FROM logistica.spgrabartmpcant('".$valor."','".$data->sheets[0]['cells'][$fila][1]."',".$data->sheets[0]['cells'][$fila][2].") ");
	$cn->affected_rows($query);
	$cn->close($query);
}

}

}

echo "<div>";
echo "<center>";
if ($count > 0) {
$array = serialize($array);
$array = urlencode($array);
echo "<META HTTP-EQUIV='REFRESH' CONTENT=1;URL='saldomat.php?array=$array'>";
}
echo $status;
echo "</center>";
echo "</div>";
?> 
	</article>
<?}?>
</section>
	<footer>
	</footer>
</body>
</html>