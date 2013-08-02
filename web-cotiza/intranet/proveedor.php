<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.proveedor VALUES('".$_POST['txtruc']."','".$_POST['txtrz']."','".$_POST['txtdir']."','".$_POST['cbopais']."','".$_POST['cbodepartamento']."','".$_POST['cboprovincia']."','".$_POST['cbodistrito']."','".$_POST['txttel']."','".$_POST['cbotipo']."','".$_POST['cboorigen']."','".$_POST['cboest']."')");
	$cn->affected_rows($query);
	$cn->close($query);
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Mantenimiento de Proveedores</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/intranet/style-proveedor.css">
	<script type="text/javascript" src="../ajax/intranet/ajxproveedor.js"></script>
	<script type="text/javascript" src="../js/intranet/proveedor.js"></script>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
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
<?php
if($_SESSION['accessicr']==true) { ?>
<section>
	<?php include("includes/menu.inc");?>
		<hgroup>
			<h3>Mantenimientos Proveedores</h3>
		</hgroup>
		<div id="cont">
		<form method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="status('t');"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="status('f');"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED ><img src="../source/floppy32.png"></button>
		</span>
		<br>
		<br>
		<table id="tbl">
		<tr>
		<td><label>Nro de RUC:</label></td>
		<td><input type="text" id="txtruc" name="txtruc" title="Nro de RUC" placeholder="Nro de RUC" REQUIRED DISABLED/></td>
		<td><label>Pais:</label></td>
		<td><select id="cbopais" name="cbopais" OnChange="cbos('de');" REQUIRED>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT paisid,paisnom FROM admin.pais ORDER BY paisnom ASC");
			if ($cn->num_rows($query)>0) {
				while($result = $cn->ExecuteNomQuery($query)){
					echo "<option value='".$result['paisid']."'>".$result['paisnom']."</option>";
				}
			}
			?>
		</select></td>
		<td><label>Tipo:</label></td>
			<td><select id="cbotipo" name="cbotipo" DISABLED REQUIRED>
				<option value="JURIDICA">JURIDICA</option>
				<option value="NATURAL">NACIONAL</option>
			</select></td>
		</tr>
		<tr>
		<td><label>Razon Social:</label></td>
		<td><input type="text" id="txtrz" name="txtrz" title="Razón Social" style="width: 20em;" placeholder="Razon Social" REQUIRED DISABLED/></td>
		<td><label>Departamento:</label></td>
		<td><div id="dcbodepartamento" DISABLED REQUIRED></div></td>
		<td><label>Origen</label></td>
			<td>
				<select id="cboorigen" name="cboorigen" DISABLED REQUIRED>
					<option value="NACIONAL">NACIONAL</option>
					<option value="INTERNACIONAL">INTERNACIONAL</option>
				</select>
			</td>
		</tr>
		<tr><td><label>Dirección:</label></td>
		<td><input type="text" id="txtdir" name="txtdir" title="Dirección" style="width: 20em;" placeholder="Dirección" REQUIRED DISABLED/></td>
		<td><label>Provincia:</label></td>
		<td><div id="dcboprovincia" DISABLED REQUIRED></div></td>
		<td><label>Estado:</label></td>
			<td><select id="cboest" name="cboest" DISABLED REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid, esnom FROM admin.estadoes WHERE estid LIKE '09'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></td>
		</tr>
		<tr>
		<td><label>Telefono:</label></td>
		<td><input type="tel" id="txttel" name="txttel" title="Numero Telefono" placeholder="Número de Telefono"  DISABLED REQUIRED /></td>
		<td><label>Distrito:</label></td>
		<td><div id="dcbodistrito" DISABLED REQUIRED></div></td>
		</tr>
		</table>
	</form>
	<table class="table table-bordered">
		<thead>
			<tr>
			<th>Item</th>
			<th>RUC</th>
			<th>Razon Social</th>
			<!--<th>Dirección</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Pais</th>-->
			<th>Telefono</th>
			<!--<th>Tipo</th>-->
			<th>Origen</th>
			<th>Estado</th>
			<th>Editar</th>
			<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT p.rucproveedor,p.razonsocial,p.direccion,p.telefono,p.origen,e.esnom FROM ".
				"admin.proveedor p INNER JOIN admin.estadoes e ".
				"ON p.esid=e.esid ".
				"WHERE p.esid LIKE '15' ORDER BY p.razonsocial ASC");
			if ($cn->num_rows($query)>0) {
				$c = 1;
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td style='text-align:center'>".$c++."</td>";
					echo "<td>".$result['rucproveedor']."</td>";
					echo "<td>".$result['razonsocial']."</td>";
					/*echo "<td>".$result['direccion']."</td>";
					echo "<td style='text-align:center'>".$result['distnom']."</td>";
					echo "<td style='text-align:center'>".$result['provnom']."</td>";
					echo "<td style='text-align:center'>".$result['deparnom']."</td>";
					echo "<td style='text-align:center'>".$result['paisnom']."</td>";*/
					echo "<td style='text-align:center'>".$result['telefono']."</td>";
					/*echo "<td style='text-align:center'>".$result['tipo']."</td>";*/
					echo "<td style='text-align:center'>".$result['origen']."</td>";
					echo "<td style='text-align:center'>".$result['esnom']."</td>";
					echo "<td style='text-align: center;'><a href='javascript:editarpro(".$result['rucproveedor'].");'><i class='icon-edit'></i></a></td>";
					echo "<td style='text-align:center'><a href='javascript:deletepro(".$result['rucproveedor'].");'><img src='../source/delete.png' /></a></td>";
					echo "</tr>";
				}
			}
			?>
		</tbody>
	</table>
	</div>
</section>
<?php
}
?>
<div style="height: 70px;">
</div>
<footer>
</footer>
</body>
</html>