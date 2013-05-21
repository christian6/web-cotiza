<?php
session_start();
include ("../datos/postgresHelper.php");?>
<?php
$txt = $_POST['txtgen'];
if (isset($txt)) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT to_char(((MAX(estid)::INTEGER)+1),'00') as cod FROM admin.estado");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$cn->close($query);
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.estado VALUES(TRIM('".$result['cod']."'),'$txt')");
		$cn->affected_rows($query);
		$cn->close($query);
	}
	$cn->close($query);
}
?>
<!DOCTYPE html>
<htmllang="es">
<head>
	<meta charset='utf-8' />
	<title>Estados</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint-estado.css">
	<script type="text/javascript" src="../ajax/ajxcboestado.js"></script>
	<script type="text/javascript" src="../js/estados.js"></script>
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
<?php echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre: </label>
<?php echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?php echo $_SESSION['dni-icr']?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?php echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<?php if ($_SESSION['accessicr']==true) {?>
	<section>
		<?include("includes/menu.inc");?>
		<hgroup>
			<h3>Mantenimientos Estados</h3>
		</hgroup>
		<div id="cuer">
			<fieldset>
				<legend>Datos Generales</legend>
			<label>Estado de :</label>
			<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT DISTINCT estid,estnom FROM admin.estado ORDER BY estnom ASC");
			?>
			<select id="cbogen">
				<?php
				if ($cn->num_rows($query) > 0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['estid']."'>".$result['estnom']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select>
			<button title="Agregar Nuevo Estado" onClick="showadd();"><img src="../source/plus16.png"></button>
			<div id="estgen">
				<button title="Cerrar Nuevo Estado" onClick="hiddenadd();"><img src="../source/cerrar16.png"></button>
				<form name="frm" method="POST" action="">
				<label>Ingrese Estado General:</label><br>
				<input type="text" id="txtgen" name="txtgen" title-"Estado General" placeholder="Estado" REQUIRED />
				<button title="Guardar Estado" type="Submit"><img src="../source/floppy16.png"></button>
			</form>
			</div>
			<br>
			<label>Estado: </label>
			<input type="text" id="txtesp" name="txtesp" title="Ingrese el Nuevo estado" placeholder="Ingrese Descripcion" DISABLED REQUIRED />
			<br />
			<button title="Nuevo Estado Especifico" onClick="enaesp();"><img src="../source/plus16.png"></button>
			<button title="Cancelar" onClick="disesp();"><img src="../source/cancelar216.png"></button>
			<button id="btne" title="Guardar Estado" onClick="insest();" DISABLED ><img src="../source/floppy16.png"></button>
			</fieldset>
			<hr />
			<select id="cbogen2" onChange="estados();">
				<option>-- Seleccionar --</option>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT DISTINCT estid,estnom FROM admin.estado ORDER BY estnom ASC");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<option value=".$result['estid'].">".$result['estnom']."</option>";
						}
					}
				$cn->close($query);
				?>
			</select>
			<br />
			<div id="tbl"></div>
		</div>
	</section>
	<?php }?>
	<footer>
	</footer>
</body>
</html>