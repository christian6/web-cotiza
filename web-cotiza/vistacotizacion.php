<?include("datos/postgresHelper.php");?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<title> Cotizacion </title>
	<link rel="shortcut icon" href="ico/icrperu.ico" type="image/x-icon">
	<link href='http://fonts.googleapis.com/css?family=Paprika' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/stylepags.css">
	<link rel="stylesheet" href="css/style3.css">
	<script type="text/javascript" src="js/autogen.js"></script>
	<script type="text/javascript" src="js/validar.js"></script>
	<script src="ajax/ajxkeygen.js"></script>
</head>
<body>
<?php include("../web/includes/analitycs.inc"); ?>
	<header>
		<hgroup>
			<div id="cabeza">
				<h1>ICR PERU</h1>
			</div>
		<div id="sess">
<?
session_start();

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
		<hgroup>
			<?php include("includes/menu.inc");?>
		</hgroup>
	</header>
<?if($_SESSION['access']==true){ ?>
	<section>
		<div id="amarra"></div>
		<div id="datospro">
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
			<p><label><b>Ruc: </b></label><label><?echo $result['rucproveedor'];?></label>
			<label><b>Razon Social: </b></label><label><?echo $result['razonsocial'];?></label></p>
			<p><label><b>Direccion: </b></label><label><?echo $result['direccion'];?></label>
			<label><b>Distrito: </b></label><label><?echo $result['distnom'];?></label></p>
			<p><label><b>Provincia: </b></label><label><?echo $result['provnom'];?></label>
			<p><label><b>Departamento: </b></label><label><?echo $result['deparnom'];?></label>
			<label><b>Pais: </b></label><label><?echo $result['paisnom'];?></label></p>
<?
	}
}
?>
<hr>
			<button id="refresh" onClick="javascript:location.href=''">Actualizar</button>
		</div>
	</section>
	<article>
		<div id="tdetalle"><h3>Lista de Cotizaciones</h3></div>
		<div id="detalle">
			<?php
$cn = new PostgreSQL();
$query = $cn->consulta("
				SELECT DISTINCT c.nrocotizacion,c.fecha::date,c.fecreq::date
				FROM logistica.cotizacion c, logistica.detcotizacion d
				WHERE d.rucproveedor LIKE '".$_SESSION['ruc']."' AND c.nrocotizacion LIKE d.nrocotizacion AND TRIM(c.estado) LIKE '14'
				ORDER BY c.nrocotizacion
				");
if ($cn->num_rows($query)>0) {
while ($result = $cn->ExecuteNomQuery($query)) {
			?>
			<h4><a href="javascript:MostrarForm('<?echo $result['nrocotizacion'];?>');">Solicitud de Cotizacion Nro: <?echo $result['nrocotizacion'];?></a></h4>
			<p><b>Fecha Solicitada: </b><label for="fec"><?echo $result['fecha'];?></label>
			<b>Fecha Requerida: </b><label for="fecreq"><?echo $result['fecreq'];?></label>
			<b>Ver Detalle: </b><a href="javascript:MostrarForm('<?echo $result['nrocotizacion'];?>');"><img src="source/doc16.png"></a></p>
			<?php
			}
		}
$cn->close($query);
			?>
		</div>
	</article>
<?}else{?>
	<aside> No hay Detalles para Mostrar.!</aside>
<? } ?>
	<div id="fullscreen">&nbsp;</div>
	<div id="Form" >
		<div id="cagen">
			<h4>Ingrese AutoGenerado</h4>
			<table>
			<thead>
				<tr>
					<th>
						<label for="cot">Nro Cotizacion: </label>
					</th>
					<th>
						<input type="text" id="nrocot" name="nrocot" DISABLED />
					</th>
				</tr>
				<tr>
					<th>
						<label for="prov">RUC Proveedor: </label>
					</th>
					<th>
						<input type="text" id="rucpro" name="rucpro" value="<?echo $_SESSION['ruc']?>" DISABLED/>
					</th>
				</tr>
				<tr>
					<th><label for="agen">AutoGenerado: </label></th>
					<th><input type="text" id="tagen" name="tagen" placeholder="Auto Generado"/></th>
				</tr>
				<tr>
					<th><button id="close" onclick="CerrarForm();">Salir</button></th>
					<th><button style="background-color:#73880a; color: #FFF;" id="ok" onclick="keygen();">Aceptar</button></th>
				</tr>
			</thead>
			</table>
			<label id="msg"></label>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>
