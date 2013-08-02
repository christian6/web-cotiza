<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {

	$cn2 = new PostgreSQL();
	$query2 = $cn2->consulta("
						INSERT INTO admin.clientes(ruccliente, nombre, abre, direccion, paisid, departamentoid, provinciaid, distritoid, telefono, contacto, esid)
        				VALUES('".$_POST['txtruc']."','".$_POST['txtrz']."','".$_POST['txtabre']."','".$_POST['txtdir']."','".$_POST['cbopais']."','".$_POST['cbodepartamento']."','".$_POST['cboprovincia']."','".$_POST['cbodistrito']."','".$_POST['txttel']."','".$_POST['txtcont']."','".$_POST['cboest']."')");
	$cn2->affected_rows($query2);
	$cn2->close($query2);
}
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Clientes</title>
		<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="../css/styleint.css">
		<link rel="stylesheet" type="text/css" href="../css/intranet/style-proyecto.css">
		<script type="text/javascript" src="../ajax/intranet/ajxproveedor.js"></script>
		<script type="text/javascript" src="../ajax/intranet/ajxproyectos.js"></script>
		<script type="text/javascript" src="../js/intranet/clientes.js"></script>
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
			<h3> Mantenimiento de Clientes</h3>
		</hgroup>
		<div id="cont">
		<form method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="status('t');" title="Nuevo Cliente"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="status('f');" title="Cancelar Nuevo Cliente"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED title="Guardar Nuevo Cliente"><img src="../source/floppy32.png"></button>
		</span>
		<br>
		<table id="tbl">
		<tr>
		<td><label class="pre">Número de RUC:</label></td>
		<td><input type="text" id="txtruc" name="txtruc" class="span2" title="RUC Cliente" maxlength="11" placeholder="Número de RUC" REQUIRED DISABLED/></td>
		<td><label class="pre">Pais:</label></td>
		<td><select class="span2" id="cbopais" name="cbopais" OnChange="cbos('de');" REQUIRED DISABLED>
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
		<td><label class="pre">Telefono:</label></td>
		<td><input type="text" id="txttel" name="txttel" class="span2" placeholder="Telefono" title="Ingrese el # de Telefono" REQUIRED DISABLED />
		</td>
		</tr>
		<tr><td><label class="pre">Razon Social:</label></td>
		<td><input type="text" id="txtrz" name="txtrz" title="Nombre del Cliente" style="width: 25em;" placeholder="Razon Social o Nombre" REQUIRED DISABLED/></td>
		<td><label class="pre">Departamento:</label></td>
		<td><div id="dcbodepartamento" DISABLED REQUIRED></div></td>
		<td> <label for="lblcont" class="pre">Contacto:</label> </td>
		<td><input type="text" id="txtcont" name="txtcont" placeholder="Contacto" title="Ingrese el nombre del Contacto" /></td>
		</tr>
		<tr><td><label class="pre">Abreviatura:</label></td>
		<td><input type="text" id="txtabre" name="txtabre" class="input input-small" placeholder="Iniciales" title="Ingrese Abreviatura" REQUIRED DISABLED /></td>
		<td><label class="pre">Provincia:</label></td>
		<td><div id="dcboprovincia" DISABLED REQUIRED></div></td>
		<td><label class="pre">Estado:</label></td>
			<td><select class="span2" id="cboest" name="cboest" DISABLED REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid, esnom FROM admin.estadoes WHERE estid LIKE '18'");
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
		<td><label class="pre">Dirección:</label></td>
		<td><input type="text" id="txtdir" name="txtdir" title="Direccion del Cliente" style="width: 25em;" placeholder="Dirección del Cliente" REQUIRED DISABLED/></td>
		<td><label class="pre">Distrito:</label></td>
		<td><div id="dcbodistrito" DISABLED REQUIRED></div></td>
		</tr>
		</table>
	</form>
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>Item</th>
				<th>Codigo</th>
				<th>Razón Social</th>
				<th>Abreviatura</th>
				<th>Dirección</th>
				<th>Telefono</th>
				<th>Contacto</th>
				<th>Estado</th>
				<th>Ver</th>
				<th>Modificar</th>
				<th>Eliminar</th>
		</tr>
		</thead>
		<tbody>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("
				SELECT c.ruccliente,c.nombre,c.abre,c.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom,c.telefono,c.contacto,e.esnom
						FROM admin.clientes c INNER JOIN admin.pais a
						ON c.paisid=a.paisid
						INNER JOIN admin.departamento d
						ON c.departamentoid=d.departamentoid
						INNER JOIN admin.provincia r
						ON c.provinciaid=r.provinciaid
						INNER JOIN admin.distrito i
						ON c.distritoid=i.distritoid
						INNER JOIN admin.estadoes e
						ON c.esid=e.esid
						WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND c.esid LIKE '41'
						ORDER BY c.nombre ASC
						");
			if ($cn->num_rows($query)>0) {
				$i = 0;
				while ($result = $cn->ExecuteNomQuery($query)) {
					$i++;
					echo "<tr>";
					echo "<td style='text-align:center'>$i</td>";
					echo "<td>".$result['ruccliente']."</td>";
					echo "<td>".$result['nombre']."</td>";
					echo "<td style='text-align:center'>".$result['abre']."</td>";
					echo "<td>".$result['direccion']."</td>";
					echo "<td style='text-align:center'>".$result['telefono']."</td>";
					echo "<td>".$result['contacto']."</td>";
					echo "<td style='text-align:center'>".$result['esnom']."</td>";
					?>
					<td style='text-align:center'><a href="javascript:vercli('<?php echo $result['ruccliente'];?>');"> <i class="icon-search"></i> </a></td>
					<td style='text-align:center'><a href="javascript:updatecliente('<?php echo $result['ruccliente'];?>');"><img src='../source/editar16.png' /></a></td>
					<td style='text-align:center'><a href="javascript:deletecliente('<?php echo $result['ruccliente'];?>');"><img src='../source/delete.png' /></a></td>
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
	<div id="msg-e" class="alert alert-error">
		<a class="close" data-dismiss="alert">x</a>
		<b>¡Oh, no!</b> No se a podido Completar la Transacción
	</div>
	<div id="msg-s" class="alert alert-success">
		<a class="close" data-dismiss="alert">x</a>
		<b>¡Bien hecho!</b> Se Completo la Transacción Correctamente
	</div>
</footer>
</body>
</html>