<?
session_start();
include("../datos/postgresHelper.php");?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset='utf-8' />
	<title>Cotizacion de materiales</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
    <script type="text/javascript" src="../js/autocomplete.js"></script>
	<script src="../ajax/ajxmatmedcbo.js"></script>
	<script type="text/javascript" src="../js/fromsave.js"></script>
</head>
<body>
	<header>
		<?include("includes/menu.inc");?>
		<hgroup>
			<h3>ICR PERU S.A.</h3>
			<h1>Cotizacion de Materiales</h1>
		</hgroup>
		</header>
		<span class="tool">
			<button id="toggle">Ver Buscador</button>
			<input type="Button" name="matnom" id="matnom" value="Ver Medidas" onclick="showUser();" />
			<input type="Button" id="btnlist" name="btnlist" value="Listar" onclick="grilla('lista');"/>
			<input type="Checkbox" id="chkdel" onchange="grilla('all');" />Eliminar Todo
			<input type="Button" name="save" id="save" value="Guardar" onclick="MostrarForm();" />
		</span>
			<div class="ui-widget">
				<table id="cini">
					<tr>
						<td>
				<label for="lblmat">Seleccione Materiales: </label>

				<select id="combobox" onclick="showUser();">
                    <option value="">--Seleccione--</option>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT DISTINCT matnom FROM admin.materiales ORDER BY matnom ASC");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
						}
					}
					$cn->close($query);
				?>
				</select>
				</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td>
						<div id="listado"></div>
					</td>
				</tr>
				</table>
				</div>
			<br />
			<div id="data"></div>
			<div id="grilla"></div>
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
					<input type="text" size="10" id="txtdni" name="txtdni" value="<?echo $_SESSION['dni-icr']?>" />
					<input type="text" id="txtpnom" name="txtpnom" value="<?echo $_SESSION['nom-icr']?>" /></p>
					<p><label for="fecha">Fecha Entrega: </label>
					<input type="date" id="fecent" name="fecent" placeholder="Fecha Entrega" /></p>
					<p><label for="estado">Estado: </label>
					<select id="cboestado">
						<option>--Seleccione--</option>
						<?
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT DISTINCT e.esid,e.estid,e.esnom FROM admin.estado o INNER JOIN admin.estadoes e ON o.estid=e.estid WHERE e.estid like '06' ORDER BY esnom ASC");
						if ($cn->num_rows($query)>0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
							}
						}
						$cn->close($query);
						?>
					</select></p>
					<p><label for="observacion">Observacion: </label>
					<textarea id="txtobser" name="txtobser" rows="3" cols="40" value=""></textarea></p>
					<hr />
					<p>
						<label for="proveedor">Proveedor</label>
						<select id="cboproveedor">
							<option value="">--select--</option>
							<?
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT DISTINCT rucproveedor, razonsocial FROM admin.proveedor ORDER BY razonsocial ASC");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$result['rucproveedor']."'>".$result['razonsocial']."</option>";
								}
							}
							$cn->close($query);
							?>
						</select>
						<button id="btnaddpro" name="btnaddpro" onclick="addproveedor();" ></button>
					</p>
					<p><select multiple="multiple" id="lstpro">
					</select></p>
					<input type="Button" id="btnsave" value="Cancelar" onclick="CerrarForm();"/ >
					<button id="btnsave" name="btnsave" onclick="save();">Save</button>
					</div>
			</div>
		<footer>

		</footer>
</body>
</html>
