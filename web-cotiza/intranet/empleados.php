<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.empleados VALUES('".$_POST['txtdni']."','".$_POST['txtnom']."','".$_POST['txtape']."',now(),'".$_POST['txtfnc']."','".$_POST['cbopais']."','".$_POST['cbodepartamento']."','".$_POST['cboprovincia']."','".$_POST['cbodistrito']."','".$_POST['txtdir']."','".$_POST['txttel']."',".$_POST['cbocar'].",'".$_POST['cboest']."')");
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
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="../modules/styletable.jquery.plugin.js"></script>
	<script type="text/javascript" src="../ajax/intranet/ajxempleados.js"></script>
	<script type="text/javascript" src="../js/intranet/empleados.js"></script>
	<script>  
    $(document).ready(function(){  
       $('#tbldetalle').styleTable({  
    		th_bgcolor: '#CDDFB5',  
    		th_border_color: '#4C5F3B',  
    		tr_odd_bgcolor: '#F2FFE1',  
    		tr_even_bgcolor: '#ffffff',  
    		tr_border_color: '#6E8F50',  
    		tr_hover_bgcolor: '#B4CF9B'  
		});
    });
</script>
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
			<h3>Mantenimientos Moneda</h3>
		</hgroup>
		<div id="cont">
		<form method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="status('t');"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="status('f');"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED ><img src="../source/floppy32.png"></button>
		</span>
		<br>
		<table id="tbl">
		<tr>
		<td><label>Nro de DNI:</label></td>
		<td><input type="text" id="txtdni" name="txtdni" title="Nro de DNI" placeholder="Nro de DNI" REQUIRED DISABLED/></td></tr>
		<tr>
		<td><label>Nombre:</label></td>
		<td><input type="text" id="txtnom" name="txtnom" title="Nombre Empleado" placeholder="Nombre Empleado" REQUIRED DISABLED/></td></tr>
		<tr>
		<td><label>Apellidos:</label></td>
		<td><input type="text" id="txtape" name="txtape" title="Apellido Empleado" placeholder="Apellido Empleado" REQUIRED DISABLED/></td></tr>
		<tr>
		<td><label>Fecha Nacimiento:</label></td>
		<td><input type="date" id="txtfnc" name="txtfnc" title="Fecha de Nacimiento" placeholder="dd/mm/aa" REQUIRED DISABLED/></td></tr>
		<tr><td><label>Dirección:</label></td>
		<td><input type="text" id="txtdir" name="txtdir" title="Dirección" placeholder="Dirección" REQUIRED DISABLED/></td></tr>
		<tr><td><label>Pais:</label></td>
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
		</select></td></tr>
		<tr>
		<td><label>Departamento:</label></td>
		<td><div id="dcbodepartamento" DISABLED REQUIRED></div></td></tr>
		<tr>
		<td><label>Provincia:</label></td>
		<td><div id="dcboprovincia" DISABLED REQUIRED></div></td></tr>
		<tr>
		<td><label>Distrito:</label></td>
		<td><div id="dcbodistrito" DISABLED REQUIRED></div></td></tr>
		<tr>
		<td><label>Telefono:</label></td>
		<td><input type="tel" id="txttel" name="txttel" title="Numero Telefono" placeholder="Número de Telefono"  DISABLED REQUIRED /></td></tr>
		<tr>
			<td><label>Cargo:</label></td>
			<td>
				<select id="cbocar" name="cbocar" DISABLED REQUIRED>
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT * FROM admin.cargo");
						if ($cn->num_rows($query)>0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<option value='".$result['cargoid']."'>".$result['carnom']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label>Estado:</label></td>
			<td><select id="cboest" name="cboest" DISABLED REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid, esnom FROM admin.estadoes WHERE estid LIKE '11'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></td>
		</tr>
		</table>
	</form>
	<table id="tbldetalle">
		<thead>
			<tr>
			<th>DNI</th>
			<th>Nombre</th>
			<th>Apellidos</th>
			<th>Fecha</th>
			<th>Fecha Nacimiento</th>
			<th>Dirección</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Pais</th>
			<th>Telefono</th>
			<th>Cargo</th>
			<th>Estado</th>
			<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT e.empdni,e.empnom,e.empfec::date,e.empfnc::date,e.empdir,a.paisnom,d.deparnom,r.provnom,i.distnom,e.emptel,c.carnom,s.esnom FROM ".
				"admin.empleados e INNER JOIN admin.pais a ".
				"ON e.paisid=a.paisid ".
				"INNER JOIN admin.departamento d ".
				"ON e.departamentoid=d.departamentoid ".
				"INNER JOIN admin.provincia r ".
				"ON e.provinciaid=r.provinciaid ".
				"INNER JOIN admin.distrito i ".
				"ON e.distritoid=i.distritoid ".
				"INNER JOIN admin.cargo c ".
				"ON e.cargoid=c.cargoid ".
				"INNER JOIN admin.estadoes s ".
				"ON e.esid=s.esid ".
				"WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND e.esid LIKE '19' ORDER BY e.empnom ASC");
			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td>".$result['empdni']."</td>";
					echo "<td>".$result['empnom']."</td>";
					echo "<td>".$result['empape']."</td>";
					echo "<td style='text-align:center'>".$result['empfec']."</td>";
					echo "<td style='text-align:center'>".$result['empfnc']."</td>";
					echo "<td>".$result['empdir']."</td>";
					echo "<td style='text-align:center'>".$result['distnom']."</td>";
					echo "<td style='text-align:center'>".$result['provnom']."</td>";
					echo "<td style='text-align:center'>".$result['deparnom']."</td>";
					echo "<td style='text-align:center'>".$result['paisnom']."</td>";
					echo "<td style='text-align:center'>".$result['telefono']."</td>";
					echo "<td style='text-align:center'>".$result['carnom']."</td>";
					echo "<td style='text-align:center'>".$result['esnom']."</td>";
					echo "<td style='text-align:center'><a href='javascript:deleteemp(".$result['empdni'].");'><img src='../source/delete.png' /></a></td>";
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