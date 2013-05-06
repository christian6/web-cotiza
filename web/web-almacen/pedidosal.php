<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html>
<head>
	<meta charset="utf-8" />
	<title>Pedidos a Almacen</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<script src="ajax/ajxcbomat.js"></script>
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script type="text/javascript" src="js/autocomplete.js"></script>
	<script>
		$(function() {
        	$( "#txtfecha" ).datepicker({ minDate: "0" , maxDate: "+1M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
        	$('.dropdown-toggle').dropdown();
   		 });
	</script>
	<style>
		#ldata{ font-weight: bold;}
		.row{ height: 70px; }
	</style>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<!--<div id="sess">
	<?php
	$nom = $_SESSION['nom-icr'];
	$car = $_SESSION['car-icr'];
	$dni = $_SESSION['dni-icr'];
	$user = $_SESSION['user-icr'];
	?>
	<p>
	<label for="user" style="font-weight: bold;">Cargo:</label>
	<?echo $car;?>&nbsp;
	<label for="nom" style="font-weight: bold;">Nombre: </label>
	<?echo $nom;?>
	</p>
	<p>
	<label style="font-weight: bold;">Dni:</label>
	&nbsp;<?echo $dni; ?>&nbsp;
	<label style="font-weight: bold;">User:</label>
	<?echo $user;?>
	<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
	</p>
</div>-->
<section>
	<!--<span class="rbtn">
		<form action="" method="POST">
			<input type="radio" id="rbtnmat" name="rbtn" onChange="javascript:submit();" value="mat" > Materiales
			<input type="radio" id="rbtneh" name="rbtn" onChange="javascript:submit();" value="eh" > Equipos y Herramientas
		</form>
	</span>-->
	<span class="tool well">
			<button id="toggle" class="btn btn-info"><i class="icon icon-search"></i> Ver Buscador</button>
			<button type="Button" class="btn" name="matnom" id="matnom" onclick="showmed();"><i class="icon-list-alt"></i> Ver Medidas</button>
			<button type="Button" class="btn" onClick="grilla('lista');"><i class="icon-th-list"></i> Listar</button>
			<button type="Button" class="btn" onClick="mostrar(true);"> <i class="icon-ok"></i> Pedido</button>
	</span>
	<div class="well">
	<div class="ui-widget">
				<label for="mat">Seleccione Materiales: </label>
				<select id="combobox" class="span5" onclick="showmed();" style="display: none;">
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT DISTINCT m.matnom FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid ORDER BY matnom ASC");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
						}
					}
					$cn->close($query);
				?>
				</select>
				</div>
				<br />
				<div id="medida"></div>
	</div>
			<div id="data"></div>
			<div id="detgrilla"></div>
			<div id="fullscreen">&nbsp;</div>
			<div id="Form" >
				<h5>Guardar Requerimiento de Materiales</h5>
				<h6>Pedido al Almacen</h6>
				<div id="contentfro">
					<table>
						<tr>
						<td><label for="pro">Proyecto:</label></td>
						<td><select id="cbopro" name="cbopro" onClick="sub();">
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT proyectoid,descripcion FROM ventas.proyectos WHERE esid = '17'");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['proyectoid']."'>".$result['descripcion']."</option>";
								}
							}
							?>
						</select>
					</td></tr>
					<tr id="sub">
					</tr>
					<tr id="sec">
					</tr>
					<tr>
						<td><label for="emp">Realizado:</label></td>
						<td><input type="text" id="txtdni" name="txtdni" title="Nro de DNI" value="<?echo $_SESSION['dni-icr']?>" DISABLED />
						<input type="text" id="txtnemp" name="txtnemp" title="Nombre" value="<?echo $_SESSION['nom-icr']?>" DISABLED /></td>
					</tr>
					<tr>
						<td><label for="fecha">Entrega:</label></td>
						<td><input type="text" id="txtfecha" name="txtfecha" placeholder="aaaa-mm-dd" title="Fecha de Entrega" /></td>
					</tr>
					<tr>
						<td><label for="al">Almacen:</label></td>
						<td><select id="cboal" name="cboal">
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid = '21'");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
								}
							}
							?>
						</select>
					</td></tr>
					<tr>
						<td rowspan="2"><label for="obser">Observación:</label></td>
					</tr>
					<tr><td><textarea id="txtobser" name="txtobser" title="Observaciones" placeholder="Ingrese su Observación"></textarea></td></tr>
					<tr>
						<td><button type="Button" class="btn" onClick="mostrar(false);"><i class="icon-ban-circle"></i></button></td>
						<td><button type="Button" class="btn btn-primary" onClick="savepedido();"><i class="icon-ok icon-white"></i></button></td>
					</tr>
				</table>
				</div>
			</div>
</section>
<div class="row"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>