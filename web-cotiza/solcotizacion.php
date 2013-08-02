<? include("datos/postgresHelper.php"); ?>

<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset='utf-8' />
	<title>Solicitud de Cotizacion</title>
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/stylepags.css">
	<link rel="stylesheet" href="css/style4.css">
	<script type="text/javascript" src="js/validar.js"></script>
	<script type="text/javascript" src="ajax/ajxsavecli.js"></script>
</head>
<body>
<?php include("../web/includes/analitycs.inc"); ?>
	<header>
		<hgroup>
			<div id="cabeza"><h1>ICR PERU</h1></div>
		<div id="sess">
<?
session_start();

$nro = $_GET['nro'];

if ($_SESSION['access']==true) {
	$usr = $_SESSION['user'];
	$nom = $_SESSION['nom'];
?>
<label for="user">Usuario:</label>
<label for="usuario"><?echo $usr;?></label>
<label for="nom">Nombre: </label>
<label for="nombre"><?echo $nom;?></label>
<br>
<button id="btninit" onclick="init();">Inicio</button>
<button id="btnclose" onclick="destroy();">Cerrar Session</button>
<?
}else{
?>
<label for="user">Usuario:</label>
<input type="text" id="txtuser" name="txtuser" tittle="Usuario" placeholder="Username"/>
<label for="passwd">Password: </label>
<input type="Password" id="txtpss" name="txtpss" tittle="Password" placeholder="Password" />
<button id="btnin" onclick="validar();">Iniciar</button>
<a href="">Olvidaste tu Contrase?</a><label id="err"></label>
<?
	}
?>
		</div>
		</hgroup>
		<h3>Detalle de Solicitud de Cotizacion </h3>
	</header>
	<? if ($_SESSION['access']==true) {
	?>
<section>
	<article>
		<div id="solicitud">
			<h5><p>Jr. Gral. José de San Martin  Mz. E Lote 6 Huachipa - Lurigancho Lima 15, Perú <br / >
				Central Telefonica: (511) 371-0443<br />E-mail: logistica@icrperusa.com<p></h5>
			<img src="source/icrperu.jpg">
			<span id="solcot">
				<h3>Solicitud de Cotizacion</h3>
				<h4>Nro <?echo $nro;?></h4>
			</span>
<?php
$cn = new PostgreSQL();
$query = $cn->consulta("
	SELECT p.rucproveedor,p.razonsocial,p.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom
	FROM admin.proveedor p INNER JOIN admin.pais a
						ON p.paisid=a.paisid
						INNER JOIN admin.departamento d
						ON p.departamentoid=d.departamentoid
						INNER JOIN admin.provincia r
						ON p.provinciaid=r.provinciaid
						INNER JOIN admin.distrito i
						ON p.distritoid=i.distritoid
	WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.rucproveedor LIKE '".$_SESSION['ruc']."'
	");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
?>
			<p><label><b>Ruc: </b></label><label><?echo $result['rucproveedor'];?></label></p>
			<p><label><b>Razon Social: </b></label><label><?echo $result['razonsocial'];?></label></p>
			<p><label><b>Direccion: </b></label><label><?echo $result['direccion'];?></label></p>
			<p><label><b>Distrito: </b></label><label><?echo $result['distnom'];?></label></p>
			<p><label><b>Provincia: </b></label><label><?echo $result['provnom'];?></label>
			<p><label><b>Departamento: </b></label><label><?echo $result['deparnom'];?></label>
			<label><b>Pais: </b></label><label><?echo $result['paisnom'];?></label></p>
<?
	}
$cn->close($query);
}
?>
		<hr />
		<h6>Estimados señores:<br />
		Por la presente nos es grato hacerles llegar nuestra solicitud de cotización por el siguiente material.
		</h6>
		<h6>
			<b>Nota:</b>&nbsp;No es necesario adjuntar los catalogos de los materiales. Si adjunta los catalogos estos deben estar en formato '.pdf'.
		</h6>
		<hr />
		<form name="frm2" action="" method="POST" enctype="multipart/form-data">
		<table>
			<thead>
				<tr>
					<th>Item</th>
					<th>Descripcion</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Catalogo</th>
				</tr>

<?
$tnro =  $_REQUEST['nro'];
$truc = $_REQUEST['ruc'];
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM logistica.spconsultardetcotizacion('$tnro','$truc')");
if ($cn->num_rows($query)>0) {
	$i = 1;
	while($result = $cn->ExecuteNomQuery($query)){
?>
				<tr>
					<td><?echo $i++;?></td>
					
					<td><?echo $result['matnom']?></td>
					<td><?echo $result['matmed']?></td>
					<td><?echo $result['matund']?></td>
					<td class="cant"><?echo $result['cantidad']?></td>
					<td><input type="number" id="<?echo $result['materialesid']?>" name="precio[]" value="<?echo $result['precio'];?>" title="Ingrese el Precio Actual del Material" step="0.01" min="0" max="999999" onblur="updateprice(this);"></td>
					<td class="files"><input type="file" id="<?echo $result['materialesid'];?>" name="<?echo $result['materialesid'];?>" accept="application/pdf" /></td>
				</tr>
<?
	}
$cn->close($query);
}
?>
			</thead>
		</table>
		<div id="pieinfo">
			<br />
		<fieldset>
		<legend>Datos Generales</legend>
		<label for="tiempo">Tiempo de Entrega: </label>
		<input type="number" id="txtdias" name="txtdias" min="0" max="300" title="Tiempo de Entraga de la Materiales en Dias" placeholder="Nro de Dias" REQUIRED /><label>&nbsp en Días.</label>
		<br />
		<label for="validez">Validez de Oferta:</label>
		<input type="date" id="dpvalidez" name="dpvalidez" placeholder="yyyy-MM-dd" title="Fecha de Validez de su Oferta" REQUIRED />
		<br />
		<label for="contac">Nombre: </label>
		<input type="text" id="cont" name="cont" placeholder="Ingrese su Nombre" title="Nombre y Apellidos de Ud." REQUIRED />
		<br />
		<label for="document">Adjuntar Archivo: </label>
		<input type="file" id="scot" name="scot" placeholder="Adjuntar" /> <label>    Aqui puede Adjuntar su Cotizacion usual (Opcional.)</label>
		<br />
		<label for="moneda">Moneda de Cotizacion:</label>
		<select id="cbomo" name="cbomo" onChange="this.options[0].selected = true;" REQUIRED>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT monedaid, nomdes FROM admin.moneda WHERE esid LIKE '10' ORDER BY nomdes ASC");
			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<option value='".$result['monedaid']."'>".$result['nomdes']."</option>";
				}
			}
			?>
		</select>
		<br />
		<label for="obser">Observacion: </label>
		<p>
		<textarea id="obser" name="obser" rows="6" cols="50" title="Ingrese su Observacion" placeholder="Ingrese su Observacion"></textarea></p>
		</div>
		</fieldset>
	</div>
	<span class="btnform">
		<button type="Button" title="Ir a la Lista de Cotizaciones" onClick="javascript:location.href='vistacotizacion.php'"><img src="source/lista48.png"></button>
		<button type="Submit" title="Guardar Cotizacion"><img src="source/floppy48.png"></button>
	</span>
	<input type="hidden" id="nro" name="nro" value="<?echo $tnro;?>" />
	<input type="hidden" id="ruc" name="ruc" value="<?echo $truc;?>" />
	</form>
	</div>
	</article>
</section>
		<div id="buttonera">
			<fieldset>
				<legend>Descarge en: </legend>
			<p><button onclick="javascript:location.href='reports/xls/solcotxls.php?ruc=<?echo $truc;?>&nro=<?echo $tnro?>'"><img src="source/xls48.png"></button></p>
			<button onclick="javascript:location.href='reports/pdfs/solcotpdf.php?ruc=<?echo $truc;?>&nro=<?echo $tnro?>'"><img src="source/pdf48.png"></button>
			</fieldset>
		</div>
<?}?>
<div id="space"></div>
<?php
$txtdias = $_POST['txtdias'];
$dpvalidez = $_POST['dpvalidez'];
$obser = $_POST['obser'];
$nro = $_POST['nro'];
$ruc = $_POST['ruc'];
$cont = $_POST['cont'];
$mon = $_POST['cbomo'];

if(isset($txtdias) && isset($dpvalidez) && isset($obser) && isset($nro) && isset($ruc)){
	$archivo = $_FILES["scot"]['name'];
	$ruta_destino = $_SERVER['DOCUMENT_ROOT']."/web-cotiza/fcotizacion/";
	$nt = $_FILES["scot"]["name"];
	$nt = trim($nt);
	$nt = substr(strrchr($nt,"."),1);
	$nombre_fichero=$nro.$ruc.'.'.$nt;
    $nombre_temporal_que_le_ha_dado_php=$_FILES["scot"]["tmp_name"];
    //lo movemos donde queramos 
    move_uploaded_file($nombre_temporal_que_le_ha_dado_php,$ruta_destino.$nombre_fichero); 
    //es aconsejable ponerle permisos 
    chmod($ruta_destino.$nombre_fichero,0777);



	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO logistica.cotizacioncli VALUES('$nro','$ruc',$txtdias,'$cont',to_date('$dpvalidez','yyyy-MM-dd'),'$mon','$obser','$nombre_fichero')");
	$cn->affected_rows($query);
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT materialesid FROM logistica.detcotizacion WHERE nrocotizacion LIKE '$nro' AND rucproveedor LIKE '$ruc'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
				///subir catalogos de materiales
			$materia = $result['materialesid'];
			$catalogo = $_FILES["$materia"]['name'];
			if(isset($catalogo)){
    			$rdestino = $_SERVER['DOCUMENT_ROOT']."/web-cotiza/catalogos/";
    			$nom = $_FILES["$materia"]["name"];
    			$nom = trim($nom);
    			$nom = substr(strrchr($nom, "."), 1);
				$nomarchivo=$materia.'.'.$nom;
    			$nomtemp=$_FILES["$materia"]["tmp_name"];
    			//lo movemos donde queramos
    			move_uploaded_file($nomtemp,$rdestino.$nomarchivo);
    			//es aconsejable ponerle permisos
    			chmod($rdestino.$nomarchivo,0777);
			}
		}
	}
	?>
	<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=vistacotizacion.php">
	<?
}else{
	echo "No se Ingreso falto alguna variable";
}
?>
<footer></footer>
</body>
</html>
