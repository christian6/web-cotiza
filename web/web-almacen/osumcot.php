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
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Suministro a Cotizacion</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/sumcot.js"></script>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
<div class="container well">
	<h4 class="">Ordenes de Suministros Aprobadas</h4>
	<hr>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Item</th>
				<th>Nro Suministro</th>
				<th>Almacen</th>
				<th>Empleado</th>
				<th>Fecha</th>
				<th>Fecha Requerido</th>
				<th>Cotizar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("
										SELECT nrosuministro,a.descri,e.empnom||', '||e.empape as empn, s.fecha::date,s.fecreq::date
										FROM almacen.suministro s INNER JOIN admin.almacenes a
										ON s.almacenid=a.almacenid
										INNER JOIN admin.empleados e
										ON s.empdni=e.empdni
										WHERE s.esid LIKE '38'
										");
				if ($cn->num_rows($query) > 0) {
					$i = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td style='text-align: center;'>".$i++."</td>";
						echo "<td style='text-align: center;'>".$result['nrosuministro']."</td>";
						echo "<td style='text-align: center;'>".$result['descri']."</td>";
						echo "<td>".$result['empn']."</td>";
						echo "<td style='text-align: center;'>".$result['fecha']."</td>";
						echo "<td style='text-align: center;'>".$result['fecreq']."</td>";
						?>
						<td style='text-align: center;'><a href="javascript:viewcot('<?php echo $result['nrosuministro'] ?>');"><i class='icon-tags'></i></a></td>
						<?php
						echo "</tr>";
					}
				}else{
					echo "<font color='darkgray'>(sin resultados)</font>";
				}
				$cn->close($query);
			?>
		</tbody>
	</table>
	<div id="modalcot" class="modal hide fade in" style="margin-left: -40%; width: 80%;">
		<div class="modal-header">
              <a class="close" data-dismiss="modal">×</a>
              <h4 class="help-inline">Cotizar Orden de Suministro</h4><label class="help-inline font-bold" id="lblnsum"></label>
              <label id="mend" class="label label-success hide">Bien Hecho!!!</label>
        </div>
        <div class="modal-body">
        	<div class="control-group">
        		<label class="label label-info">Seleccione Proveedor:</label>
        		<div class="controls">
        			<input type="hidden" id="nrocotizacion" name="nrocotizacion" value="" />
        			<select name="cbopro" id="cbopro" class="span4">
        				<?php
        					$cn = new PostgreSQL();
        					$query = $cn->consulta("SELECT rucproveedor,razonsocial FROM admin.proveedor WHERE TRIM(esid) LIKE '15'");
        					if ($cn->num_rows($query) > 0) {
        						while ($result = $cn->ExecuteNomQuery($query)) {
        							echo "<option value='".$result['rucproveedor']."'>".$result['razonsocial']."</option>";
        						}
        					}
        					$cn->close($query);
        				?>
        			</select>
        		</div>
        	</div>
        	<hr>
			<div id="tbl">
			</div>
        </div>
        <div class="modal-footer">
              <a href="#" class="btn pull-left" data-dismiss="modal">Cerrar</a>
              <a href="#" class="btn btn-primary pull-left" onClick="savedetpro();">Guardar Detalle Proveedor</a>
              <a href="#" class="btn btn-warning pull-right" onClick="finalysum();">Terminar Cotización</a>
        </div>
	</div>
	<div id="modalccot" class="modal hide fade in">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">x</a>
			<h4>Generar Solicitud de Cotización</h4>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="label label-info">Nro Orden de Suministro:</label>
				<div class="controls">
					<input type="text" class="span2" name="txtnsum" id="txtnsum" DISABLED />
				</div>
			</div>
			<div class="control-group">
				<label class="label label-info">Empleado:</label>
				<div class="control">
					<input type="text" name="txtdni" id="txtdni" class="input-small" value="<?php echo $_SESSION['dni-icr'];?>" DISABLED/>
					<input type="text" name="txtnom" id="txtnom" class="span3" value="<?php echo $_SESSION['nom-icr'];?>" DISABLED/>
				</div>
			</div>
			<div class="control-group">
				<label class="label label-info">Fecha Requerida</label>
				<div class="controls">
					<input type="text" name="txtfecha" id="txtfecha" class="input-small">
				</div>
			</div>
			<div class="control-group">
				<label class="label label-info">Observacion</label>
				<div class="control">
					<textarea name="txtobser" id="txtobser" class="input-xlarge" rows="3"></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
			<a href="#" class="btn btn-primary" onClick="viewdet();">Guardar cambios</a>
		</div>
	</div>
</div>
</section>
<div id="space"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>