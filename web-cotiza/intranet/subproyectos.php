<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevosubproyecto()");
	if ($cn->num_rows($query)>0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$cn2 = new PostgreSQL();
				$query2 = $cn2->consulta("INSERT INTO ventas.subproyectos(subproyectoid,proyectoid,subproyecto,fecha,fecent,obser,esid) VALUES(TRIM('".$result[0]."'),'".$_POST['cbopro']."','".$_POST['txtsubpro']."',now(),'".$_POST['txtfecha']."','".$_POST['txtobser']."','".$_POST['cboest']."')");
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
	<title>Sub-Proyectos</title>
		<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="../css/styleint.css">
		<link rel="stylesheet" type="text/css" href="../css/intranet/style-proyecto.css">
		<script type="text/javascript" src="../modules/styletable.jquery.plugin.js"></script>
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
<?php
if($_SESSION['accessicr']==true) { ?>
	<section>
		<?php include("includes/menu.inc");?>
		<hgroup>
			<h3> Sub-Proyectos </h3>
		</hgroup>
		<div id="cont">
		<form name="form" method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="estado('t');"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="estado('f');"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED><img src="../source/floppy32.png"></button>
			<button type="Button" id="btnestado" name="btnestado" title="Estados" onClick="javascript:location.href='proyectosestado.php';"><img src="../source/medicion32.png"></button>
			<button type="Button" id="btnsub" name="btnsub" title="Sub-Proyecto" onClick="javascript:location.href='subproyectos.php'"><img src="../source/subpro32.png"></button>
			<button type="Button" id="btnsec" name="btnsec" title="Sectores de Proyecto" onClick="javascript:location.href='sectores.php'"><img src="../source/sector32.png"></button>
			<button type="Button" id="btnpro" name="btpro" title="Proyectos" onClick="javascript:location.href='proyectos.php';"><img src="../source/mapa32.png"></button>
		</span>
		<br>
		<table id="tbl">
		<tr><td><label>Nombre Proyecto:</label></td>
		<td><select id="cbopro" name="cbopro" REQUIRED DISABLED>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT proyectoid,descripcion FROM ventas.proyectos WHERE esid LIKE '17' ORDER BY descripcion ASC ");
			if ($cn->num_rows($query)>0) {
				while($result = $cn->ExecuteNomQuery($query)){
					echo "<option value='".$result['proyectoid']."'>".$result['descripcion']."</option>";
				}
			}
			$cn->close($query);
			?>
		</select></td>
	</tr>
	<tr>
		<td><label for="sub">SubProyecto:</label></td>
		<td><input type="text" id="txtsubpro" name="txtsubpro" title="Nombre de SubProyecto" placeholder="SubProyecto" REQUIRED /></td>
	</tr>
		<tr>
			<td><label for="fecha">Entrega:</label></td>
			<td><input type="date" id="txtfecha" name="txtfecha" placeholder="dd/mm/yy" title="Fecha de Entrega" REQUIRED /></td>
		</tr>
		<tr>
			<td><label for="fecha">observación:</label></td>
			<td><textarea style="height:100px; max-height: 100px; width: 250px; max-width: 250px;" id="txtobser" name="txtobser" title="Ingrese su Observación" placeholder="Ingrese Su Observación" ></textarea></td>
		</tr>
		<tr>
			<td><label>Estado:</label></td>
			<td><select id="cboest" name="cboest" REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid, esnom FROM admin.estadoes WHERE estid LIKE '14'");
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
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Item</th>
				<th>Codigo</th>
				<th>Nombre Proyecto</th>
				<th>Codigo SubProyecto</th>
				<th>SubProyecto</th>
				<th>Fecha Ingreso</th>
				<th>Fecha Entrega</th>
				<th>Editar</th>
				<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT p.proyectoid,p.descripcion,s.subproyectoid,s.subproyecto,s.fecha::date,s.fecent ".
									"FROM ventas.proyectos p INNER JOIN ventas.subproyectos s ".
									"ON p.proyectoid = s.proyectoid ".
									"WHERE s.esid = '26' AND p.esid = '17' ".
									"GROUP BY p.proyectoid,p.descripcion,s.subproyectoid,s.subproyecto,s.fecha,s.fecent ".
									"ORDER BY p.proyectoid ASC");
			if ($cn->num_rows($query)>0) {
				$i = 1;
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td>".$i++."</td>";
					echo "<td>".$result['proyectoid']."</td>";
					echo "<td>".$result['descripcion']."</td>";
					echo "<td>".$result['subproyectoid']."</td>";
					echo "<td>".$result['subproyecto']."</td>";
					echo "<td style='text-align:center'>".$result['fecha']."</td>";
					echo "<td style='text-align:center'>".$result['fecent']."</td>";
					?>
					<td style='text-align:center'><a href="javascript:updatesubpro('<? echo $result['proyectoid'];?>','<? echo $result['subproyectoid'];?>');"><img src='../source/editar16.png' /></a></td>
					<td style='text-align:center'><a href="javascript:deleteproyecto('<? echo $result['subproyectoid'];?>');"><img src='../source/delete.png' /></a></td>
					<?php
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
<div style="height:70px;"></div>
<footer>
</footer>
</body>
</html>