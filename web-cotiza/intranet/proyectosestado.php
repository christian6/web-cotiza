<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {

	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.proyectos SET esid ='".$_POST['cboest']."' WHERE proyectoid LIKE '".$_POST['cbopro']."'");
	$cn->affected_rows($query);
	$cn->close($query);
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Cambio de Estado Proyectos</title>
		<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="../css/styleint.css">
		<link rel="stylesheet" type="text/css" href="../css/intranet/style-proyecto.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="../modules/styletable.jquery.plugin.js"></script>
		<script type="text/javascript" src="../ajax/intranet/ajxproyectos.js"></script>
		<script type="text/javascript" src="../js/intranet/proyectos.js"></script>
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
			<h3>Cambio de Estado de Proyectos</h3>
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
			<td><label>Estado:</label></td>
			<td><select id="cboest" name="cboest" REQUIRED>
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
	<table id="tbldetalle">
		<thead>
			<tr>
			<th>Codigo</th>
			<th>Nombre Proyecto</th>
			<th>Fecha</th>
			<th>Fecha Entrega</th>
			<th>Cliente</th>
			<th>Dirección</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Pais</th>
			<th>Observación</th>
			<th>Estado</th>
			<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT p.proyectoid,p.descripcion,c.nombre,p.fecha::date,p.fecent,p.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom,p.obser,e.esnom FROM ".
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
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<tr>";
					echo "<td>".$result['proyectoid']."</td>";
					echo "<td>".$result['descripcion']."</td>";
					echo "<td style='text-align:center'>".$result['fecha']."</td>";
					echo "<td style='text-align:center'>".$result['fecent']."</td>";
					echo "<td>".$result['nombre']."</td>";
					echo "<td>".$result['direccion']."</td>";
					echo "<td style='text-align:center'>".$result['distnom']."</td>";
					echo "<td style='text-align:center'>".$result['provnom']."</td>";
					echo "<td style='text-align:center'>".$result['deparnom']."</td>";
					echo "<td style='text-align:center'>".$result['paisnom']."</td>";
					echo "<td style='text-align:center'>".$result['obser']."</td>";
					echo "<td style='text-align:center'>".$result['esnom']."</td>";
					?>
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
<?php
}
?>
<div style="height:70px;"></div>
<footer>
</footer>
</body>
</html>