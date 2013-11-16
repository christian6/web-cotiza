<?php 
session_start();

include("../../datos/postgresHelper.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Reporte Orden de Compra</title>
	<link rel="shortcut icon" href="../../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../../css/styleint2.css">
	<script type="text/javascript" src="../../js/viewkey.js"></script>
</head>
<body>
<header>
<hgroup>
		<img src="../../source/icrlogo.png">
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
<?php if (true) {?>
<section>
	<?include("../includes/menu.inc");?>
	<fieldset id="dgen">
		<legend>Datos Generales</legend>
		<span id="radios">
		<input type="Radio" name="rbtnb" value="n" onchange="valrbtn(this);"><label>Nro Orden Compra</label>
		<input type="Radio" name="rbtnb" value="f" onchange="valrbtn(this);"><label>Entre Fechas</label>
		</span>
		<hr />
		<form name="form1" method="POST" action="">
		<span class="nrocot">
			<label>Nro Orden Compra:</label>
			<input type="text" id="nro" name="nro" maxlength="10" style="width:110px;" title="Ingrese el Nro de Orden de Compra para Buscar" placeholder="Nro Orden Compra" REQUIRED DISABLED/>
		</span>
		<span class="fec2">
			<label>Fecha Inicio:</label>
			<input type="date" id="fecini" maxlength="8" style="width:90px;" placeholder="dia/mes/año" title="Inicio" name="fecini" REQUIRED DISABLED/>
			<label> &nbsp;&nbsp; </label>
			<label>Fecha Fin: </label>
			<input type="date" id="fecfin" maxlength="8" style="width:90px;" placeholder="dia/mes/año" title="Fin" name="fecfin" REQUIRED DISABLED/>
		</span>
		<br />
		<label>Estado :</label>
		<select name="cboest" title="Estado que desea Buscar">
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid = '05'");
			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
				}
			}
			?>
		</select>
		<br />
		<button type="Submit">Buscar</button>
		</form>
	</fieldset>
	<br />
	<? 
		$nro = $_POST['nro'];
		$fini = $_POST['fecini'];
		$ffin = $_POST['fecfin'];
		$cbo = $_POST['cboest'];
		if (isset($nro) || isset($fini) && isset($ffin)){
		?>
	<div id="res">
		<?php
			$cn = new PostgreSQL();
			if(isset($nro)){
				$cad = "SELECT DISTINCT c.nrocompra,c.rucproveedor,p.razonsocial,c.nrocotizacion,m.nomdes,c.fecha::date
						FROM logistica.compras c INNER JOIN admin.proveedor p
						ON c.rucproveedor=p.rucproveedor
						INNER JOIN admin.moneda m
						ON c.monedaid = m.monedaid
						INNER JOIN admin.estadoes e
						ON c.esid=e.esid
						WHERE c.nrocompra LIKE '$nro' AND c.esid LIKE '$cbo' 
						ORDER BY c.nrocompra ASC";
			}else{
				$cad = "SELECT DISTINCT c.nrocompra,c.rucproveedor,p.razonsocial,c.nrocotizacion,m.nomdes,c.fecha::date
						FROM logistica.compras c INNER JOIN admin.proveedor p
						ON c.rucproveedor=p.rucproveedor
						INNER JOIN admin.moneda m
						ON c.monedaid = m.monedaid
						INNER JOIN admin.estadoes e
						ON c.esid=e.esid
						WHERE c.esid LIKE '$cbo' AND c.fecha BETWEEN to_date('$fini','yyyy-MM-dd') AND to_date('$ffin','yyyy-MM-dd')
						ORDER BY c.nrocompra ASC";
			}
			$query = $cn->consulta($cad);
			if ($cn->num_rows($query)>0) {
				echo "<table>";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Nro Cotizacion</th>";
				echo "<th>R U C</th>";
				echo "<th>Razon Social</th>";
				echo "<th>Fecha</th>";
				echo "<th>Ver</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td style='text-align:center'>".$result['nrocompra']." </td>";
					echo "<td>".$result['rucproveedor']." </td>";
					echo "<td>".$result['razonsocial']."</td>";
					echo "<td>".$result['fecha']."</td>";
					echo "<td style='text-align:center'><a href='../../reports/pdfs/system/intordencomprapdf.php?ruc=".$result['rucproveedor']."&nro=".$result['nrocompra']."' target='_blank'><img src='../../source/solti48.png' height='24' width='24' ></a></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}else{
				echo "<h4>No se han Encontrado Resultados</h4>";
			}
			$cn->close($query);
		?>
	</div>
	<?}?>
</section>
<?}?>
<footer>
</footer>
</body>
</html>