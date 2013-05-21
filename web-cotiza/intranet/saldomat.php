<?php
session_start();

include("../datos/postgresHelper.php");?>
<?
$array = unserialize(urldecode(stripcslashes($_REQUEST['array'])));
?>
<!DOCTYPE html>
<html lang='es'>
<head>
    <title>Saldo de Materiales</title>
    <link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleint.css">
    <link rel="stylesheet" href="../css/style5.css">
    <script src="../modules/mootools-core-1.3.2-full-compat-yc.js"></script>
    <script type="text/javascript" src="../js/saldos.js"></script>
    <script type="text/javascript" src="../js/fromsave.js"></script>
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
<section>
	<?include("includes/menu.inc");?>
	<div>
		<table>
			<caption>
				<input type="Checkbox" value="full" id="tdo" name="tdo" onChange="fullcheck(this);" />Seleccionar Todo&nbsp;&nbsp;&nbsp;
				<button id="btnclear" onclick="clearchk();">Limpiar</button>&nbsp;&nbsp;&nbsp;
				<button id="btncan" onclick="cancelcotexc();">Cancelar Cotización</button>
			</caption>
			<thead>
				<tr>
					<th>Item</th>
					<th>Check</th>
					<th>Codigo</th>
					<th>Descripcion</th>
					<th>Medida</th>
					<th>Unidad</th>
					<?
					for($i = 0; $i < count($array); $i++){
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT descripcion FROM ventas.proyectos WHERE proyectoid LIKE '".$array[$i]."'");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
						?>
						<th><?echo $result['descripcion'];?></th>
						<?
						}
					}
					$cn->close($query);
					}
					?>
					<th>Total Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<?
				$c = 1;
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT t.materialesid,m.matnom,m.matmed,m.matund FROM logistica.tmpcantpro t INNER JOIN admin.materiales m ON t.materialesid=m.materialesid ORDER BY t.materialesid ASC");
				if ($cn->num_rows($query)>0) {
					$x = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						?>
					<tr>
						<td><?php echo $x++; ?></td>
						<td id="cc"><input type="checkbox" name="chkmat" id="chkmat" value="<?echo $result['materialesid'];?>" /></td>
						<td><?echo $result['materialesid'];?></td>
						<td><?echo $result['matnom'];?></td>
						<td><?echo $result['matmed'];?></td>
						<td id="cc"><?echo $result['matund'];?></td>
						<?
						///cantidad parcial por proyecto
						for ($i=0; $i < count($array); $i++) {
						$cn2 = new PostgreSQL();
						$query2 = $cn2->consulta("SELECT cantidad FROM logistica.tmpcantpro WHERE proyectoid LIKE '".$array[$i]."' AND materialesid LIKE '".$result['materialesid']."'");
						if ($cn2->num_rows($query2)>0) {
							while ($result2 = $cn2->ExecuteNomQuery($query2)) {
								?>
								<td id="cc"><?echo $result2['cantidad'];?></td>
								<?
							}
						}else{
							?>
							<td id="cc">0</td>
							<?
						}
						$cn2->close($query2);
						}
						///cantidad Total
						$cn3 = new PostgreSQL();
						$query3 = $cn3->consulta("SELECT SUM(cantidad) AS totca FROM logistica.tmpcantpro WHERE materialesid LIKE '".$result['materialesid']."'");
						if ($cn3->num_rows($query3)>0) {
							while ($result3 = $cn3->ExecuteNomQuery($query3)) {
								?>
								<td id="cc"><?echo $result3['totca'];?></td>
								<?
							}
							$cn3->close($query3);
						}else{
							?>
							<td id="cc">0</td>
							<?
						}
						echo "</tr>";
				}
				$cn->close($query);
			}
				?>
			</tbody>
		</table>
	</div>
	<br />
	<div class="detalle">
	<hr />
	<select id="lstpro">
		<?
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT rucproveedor,razonsocial FROM admin.proveedor ORDER BY razonsocial ASC");
		if ($cn->num_rows($query)>0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<option value='".$result['rucproveedor']."'>".$result['razonsocial']."</option>";
			}
			$cn->close($query);
		}
		?>
	</select>
	<button onclick="chekear();"> <img src="../source/plus16.png"> </button>
	<button onclick="delpro();"> <img src="../source/quit16.png"></button>
	<button onclick="MostrarForm();"> <img src="../source/floppy16.png"> </button>
	<br />
	<details open="open">
		<summary>Materiales por Proveedor</summary>
			<div id="prov">

			</div>
	</details>
</div>
</section>
<div id="fullscreen">&nbsp;</div>
			<div id="Form" >
				<h5>Guardar Cotizacion</h5>
				<h6>Asignacion de Proveedor</h6>
				<div id="contentfro">
					<p><label for="nroCotizacion">Nro Cotizacion: </label>
					<?
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM logistica.spnuevacotizacion()");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
					?>
					<input type="text" id="txtnro" name="txtnro" value="<?echo $result[0];?>" DISABLED /></p>
					<?
						}
					}
					$cn->close($query);
					?>
					<p><label for="personal">Personal: </label>
					<input type="text" size="10" id="txtdni" name="txtdni" value="<?php echo $_SESSION['dni-icr'];?>" />
					<input type="text" id="txtpnom" name="txtpnom" value="<?php echo $_SESSION['nom-icr'];?>"/></p>
					<p><label for="fecha">Fecha Entrega: </label>
					<input type="date" id="fecent" name="fecent" placeholder="Fecha Entrega" /></p>
					<p><label for="estado">Estado: </label>
					<select id="cboestado">
						<option>--Seleccione--</option>
						<?
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT DISTINCT esid,esnom FROM admin.estadoes WHERE estid like '06' ORDER BY esnom ASC");
						if ($cn->num_rows($query)>0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
							}
						}
						$cn->close($query);
						?>
					</select></p>
					<p><label for="observacion">Observacion: </label>
					<textarea id="txtobser" name="txtobser" rows="3" cols="50"></textarea></p>
					</p>
					<button id="btncancelar" name="btncancelar" onclick="CerrarForm();"><img src="../source/cancelar16.png"> Cancelar</button>
					<button id="btnsave" name="btnsave" onclick="save();"><img src="../source/floppy16.png"> Guardar</button>
					</div>
			</div>
			<div id="space"></div>
<footer>
</footer>
</body>
</html> 