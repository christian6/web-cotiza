<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevoproyecto()");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("INSERT INTO ventas.proyectos(proyectoid, descripcion, fecent, ruccliente, direccion, paisid, departamentoid, provinciaid, distritoid, obser, esid) VALUES(TRIM('".$result[0]."'),'".$_POST['txtpr']."','".$_POST['txtfec']."','".$_POST['cbocli']."','".$_POST['txtdir']."','".$_POST['cbopais']."','".$_POST['cbodepartamento']."','".$_POST['cboprovincia']."','".$_POST['cbodistrito']."','".$_POST['txtobser']."','".$_POST['cboest']."')");
			$cn2->affected_rows($query2);
			$cn2->close($query2);
		}
	}
	$cn->close($query);
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Proyectos</title>
		<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="../css/styleint.css">
		<link rel="stylesheet" type="text/css" href="../css/intranet/style-proyecto.css">
		<script type="text/javascript" src="../modules/styletable.jquery.plugin.js"></script>
		<script type="text/javascript" src="../ajax/intranet/ajxproveedor.js"></script>
		<script type="text/javascript" src="../ajax/intranet/ajxproyectos.js"></script>
		<script type="text/javascript" src="../js/intranet/proyectos.js"></script>
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
	<section>
		<?php include("includes/menu.inc");?>
		<hgroup>
			<h3> Proyectos</h3>
		</hgroup>
		<div id="cont">
		<form method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="status('t');" title="Nuevo Proyecto"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="status('f');" title="Cancelar Nuevo Proyecto"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED title="Guardar Nuevo Proyecto"><img src="../source/floppy32.png"></button>
			<button type="Button" id="btnestado" name="btnestado" title="Estados" onClick="javascript:location.href='proyectosestado.php';"><img src="../source/medicion32.png"></button>
			<button type="Button" id="btnsub" name="btnsub" title="Sub-Proyecto" onClick="javascript:location.href='subproyectos.php'"><img src="../source/subpro32.png"></button>
			<button type="Button" id="btnsec" name="btnsec" title="Sectores de Proyecto" onClick="javascript:location.href='sectores.php'"><img src="../source/sector32.png"></button>
		</span>
		<br>
		<table id="tbl">
		<tr>
		<td><label>Nombre Proyecto:</label></td>
		<td><input type="text" id="txtpr" name="txtpr" title="Nombre Proyecto" style="width: 25em;" placeholder="Nombre de Proyectos" REQUIRED DISABLED/></td>
		<td><label>Pais:</label></td>
		<td><select id="cbopais" name="cbopais" class="span2" OnChange="cbos('de');" REQUIRED DISABLED>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT paisid,paisnom FROM admin.pais ORDER BY paisnom ASC");
			if ($cn->num_rows($query)>0) {
				while($result = $cn->ExecuteNomQuery($query)){
					echo "<option value='".$result['paisid']."'>".$result['paisnom']."</option>";
				}
			}
			$cn->close($query);
			?>
		</select></td>
		</tr>
		<tr><td><label>Fecha Entrega:</label></td>
		<td><input type="date" id="txtfec" name="txtfec" class="span2" title="Fecha de Entrega" placeholder="Fecha de Entrega" REQUIRED DISABLED/></td>
		<td><label>Departamento:</label></td>
		<td><div id="dcbodepartamento" DISABLED REQUIRED></div></td>
		</tr>
		<tr><td><label>Cliente:</label></td>
		<td><select id="cbocli" name="cbocli" class="span2" REQUIRED DISABLED>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT ruccliente,nombre FROM admin.clientes ORDER BY nombre ASC");
			if ($cn->num_rows($query)>0) {
				while($result = $cn->ExecuteNomQuery($query)){
					echo "<option value='".$result['ruccliente']."'>".$result['nombre']."</option>";
				}
			}
			$cn->close($query);
			?>
		</select></td>
		<td><label>Provincia:</label></td>
		<td><div id="dcboprovincia" DISABLED REQUIRED></div></td>
		</tr>
		<tr>
		<td><label>Dirección:</label></td>
		<td><input type="text" id="txtdir" name="txtdir" title="Direccion del Proyecto" style="width: 25em;" placeholder="Dirección del Proyecto" REQUIRED DISABLED/></td>
		<td><label>Distrito:</label></td>
		<td><div id="dcbodistrito" DISABLED REQUIRED></div></td>
		</tr>
		<tr>
			<td><label>Observación:</label></td>
			<td><textarea id="txtobser" name="txtobser" REQUIRED DISABLED></textarea></td>
			<td><label>Estado:</label></td>
			<td><select id="cboest" name="cboest" class="span2" DISABLED REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid, esnom FROM admin.estadoes WHERE estid LIKE '10'");
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
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Item</th>
				<th>Codigo</th>
				<th>Nombre Proyecto</th>
				<th>Fecha</th>
				<th>Fecha Entrega</th>
				<th>Cliente</th>
				<th>Dirección</th>
				<th>Estado</th>
				<th>Editar</th>
				<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("
				SELECT p.proyectoid,p.descripcion,c.nombre,p.fecha::date,p.fecent,p.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom,p.obser,e.esnom FROM ".
				"ventas.proyectos p INNER JOIN admin.pais a ".
				"ON p.paisid=a.paisid ".
				"INNER JOIN admin.departamento d ".
				"ON p.departamentoid=d.departamentoid ".
				"INNER JOIN admin.provincia r ".
				"ON p.provinciaid=r.provinciaid ".
				"INNER JOIN admin.distrito i ".
				"ON p.distritoid=i.distritoid ".
				"INNER JOIN admin.estadoes e ".
				"ON p.esid=e.esid ".
				"INNER JOIN admin.clientes c ".
				"ON p.ruccliente=c.ruccliente ".
				"WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.esid LIKE '17' ORDER BY p.proyectoid ASC");
			if ($cn->num_rows($query)>0) {
				$i = 1;
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td style='text-align:center'>".$i++."</td>";
					echo "<td>".$result['proyectoid']."</td>";
					echo "<td>".$result['descripcion']."</td>";
					echo "<td style='text-align:center'>".$result['fecha']."</td>";
					echo "<td style='text-align:center'>".$result['fecent']."</td>";
					echo "<td>".$result['nombre']."</td>";
					echo "<td>".$result['direccion']."</td>"; 
					echo "<td style='text-align:center'>".$result['esnom']."</td>";
					?>
					<td style='text-align:center'><a href="javascript:updateproyecto('<? echo $result['proyectoid'];?>');"><img src='../source/editar16.png' /></a></td>
					<td style='text-align:center'><a href="javascript:deleteproyecto('<? echo $result['proyectoid'];?>');"><img src='../source/delete.png' /></a></td>
					<?php 
					echo "</tr>";
				}
			}
			?>
		</tbody>
	</table>
	</div>
	</section>
<div style="height:70px;"></div>
<footer>
</footer>
</body>
</html>