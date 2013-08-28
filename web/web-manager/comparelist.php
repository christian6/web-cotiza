<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Comparar Lista de operaciones y ventas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script type="text/javascript" src="../web-almacen/js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/comparar.js"></script>
</head>
<body>
	<section>
		<div class="container well">
			<h3 class="t-warning">Comparar Lista Ventas y Operaciones</h3>
			<div class="btn-group">
				<?php
					$sql = "SELECT COUNT(*) FROM operaciones.metproyecto WHERE ";
					$cn = new PostgreSQL();
					if ($_GET['sub'] != "") {
						$sql .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."'";
					}else{
						$sql .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(sector) LIKE '".$_GET['sec']."'";
					}
					$query = $cn->consulta($sql);
					if ($cn->num_rows($query) > 0) {
						$result = $cn->ExecuteNomQuery($query);
					}
					$cn->close($query);
					if ($result[0] > 0) {
						$nedit = 1;
						echo "<button class='btn btn-success t-d' DISABLED><i class='icon-ok'></i> Aprobar</button>";
					}else{
				?>
				<button class="btn btn-success t-d" onClick="viewapro();"><i class="icon-ok"></i> Aprobar</button>
				<?php } ?>
				<button class="btn btn-danger t-d" onClick="delsector();"><i class="icon-remove"></i> Eliminar</button>
				<button class="btn btn-info t-d" onClick="editpre();"><i class="icon-edit"></i> Editar Precio</button>
				<button class="btn btn-info t-d" onClick="refreshpre();"><i class="icon-refresh"></i> Ver Precio</button>
				<button class="btn btn-inverse" onClick="javascript:window.close();"><i class="icon-resize-small icon-white"></i> Salir</button>
			</div>
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					
					<th>Item</th>
					<th>Codigo</th>
					<th>Descripcion</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Precio</th>
					<th>Ventas</th>
					<th>Operaciones</th>
					<th>Editar</th>
					<th>Eliminar</th>
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$sql = "SELECT DISTINCT v.materialesid,m.matnom,m.matmed,m.matund,i.precio
							FROM admin.vw_metradobo v INNER JOIN admin.materiales m
							ON v.materialesid LIKE m.materialesid
							INNER JOIN almacen.inventario i
							ON v.materialesid LIKE i.materialesid AND i.almacenid LIKE '0001' AND i.anio LIKE extract(year from now())::char(4)
							WHERE ";
					if ($_GET['sub'] == "") {
						$sql .= "v.proyectoid LIKE '".$_GET['pro']."' AND TRIM(v.sector) LIKE '".$_GET['sec']."'";
					}else if($_GET['sub'] != ""){
						$sql .= "v.proyectoid LIKE '".$_GET['pro']."' AND TRIM(v.subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(v.sector) LIKE '".$_GET['sec']."'";
					}
					$query = $cn->consulta($sql);
					if ($cn->num_rows($query) > 0) {
						$i = 1;
						$cv=0;
						$co=0;
						$vpr = 0;
						$opr = 0;
						$com = array('"',"'");
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr class='c-blue-light'>";
							//echo "<td id='tc'><input type='checkbox' name='matids' id='".$result['materialesid']."'></td>";
							echo "<td id='tc'>".$i++."</td>";
							echo "<td>".$result['materialesid']."</td>";
							echo "<td>".$result['matnom']."</td>";
							echo "<td>".$result['matmed']."</td>";
							echo "<td id='tc'>".$result['matund']."</td>";
							echo "<td id='tc'><input type='text' style='height: .8em;' class='input-mini' id='snpre' name='snpre' value='".$result['precio']."' DISABLED></td>";
							$c = new PostgreSQL();
							$qv = "SELECT SUM(cant) as cant FROM ventas.matmetrado WHERE ";
							if ($_GET['sub'] == "") {
								$qv .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(sector) LIKE '".$_GET['sec']."' AND materialesid LIKE '".$result['materialesid']."'";
							}else if($_GET['sub'] != ""){
								$qv .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."' AND materialesid LIKE '".$result['materialesid']."'";
							}
							$qs = $c->consulta($qv);
							if ($c->num_rows($qs) > 0) {
								$r = $c->ExecuteNomQuery($qs);
								$cv = $r[0];
								echo "<td id='tc'>".$cv."<input type='hidden' name='cvent' value='".$cv."'></td>";
								$vpr += ( $result['precio'] * $cv );
							}else{
								echo "<td> - </td>";
							}
							$c->close($qs);
							#
							$c = new PostgreSQL();
							$qo = "SELECT SUM(cant) as cant FROM operaciones.matmetrado WHERE ";
							if ($_GET['sub'] == "") {
								$qo .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(sector) LIKE '".$_GET['sec']."' AND materialesid LIKE '".$result['materialesid']."'";
							}else if($_GET['sub'] != ""){
								$qo .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."' AND materialesid LIKE '".$result['materialesid']."'";
							}
							$qs = $c->consulta($qo);
							if ($c->num_rows($qs) > 0) {
								$r = $c->ExecuteNomQuery($qs);
								$co = $r[0];
								echo "<td id='tc'>".$co."<input type='hidden' name='coper' value='".$co."'> </td>";
								$opr += ( $result['precio'] * $co );
							}else{
								echo "<td> - </td>";
							}
							$c->close($qs);

							$c->close($q);

							if ($nedit == 1) {
								echo "<td id='tc'> - </td>";
								echo "<td id='tc'> - </td>";
							}else{
							?>
							<td id='tc'><button class="btn btn-mini btn-info" onClick="viewedit('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace($com,'',$result['matmed']) ?>');"><i class="icon-edit"></i></button></td>
							<td id='tc'><button class="btn btn-mini btn-danger" onClick="viewdelete('<?php echo $result['materialesid']; ?>');"><i class="icon-remove"></i></button></td>
							<?php
							}
							echo "</tr>";
						}
					}
					$cn->close($query);
					?>
				</tbody>
			</table>
			<div class="row show-grid">
				<div class="span6">
					<div class="well c-yellow-light t-warning">
						<h4>Info Ventas</h4>
						<dl class="dl-horizontal">
							<dt>Precio Total</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on">
										$
									</span>
									<input type="text" id="vopre" class="span2" value="<?php echo $vpr; ?>" DISABLED />
								</div>
							</dd>
							<dt>Precio Modificado</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on">
										<i class="icon-tag"></i>
									</span>
									<input type="text" class="span2" id="vmpre" DISABLED>
								</div>
							</dd>
						</dl>
					</div>
				</div>
				<div class="span6">
					<div class="well c-yellow-light t-warning">
						<h4>Info Operaciones</h4>
						<dl class="dl-horizontal">
							<dt>Precio Total</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on">
										$
									</span>
									<input type="text" id="oopre" class="span2" value="<?php echo $opr; ?>" DISABLED />
								</div>
							</dd>
							<dt>Precio Modificado</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on">
										<i class="icon-tag"></i>
									</span>
									<input type="text" class="span2" id="ompre" DISABLED>
								</div>
							</dd>
						</dl>
					</div>
				</div>
				<div class="span12">
					<div class="well c-yellow-light">
						<h4 class="t-warning">Observaciones de Operaciones</h4>
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT sector,obser FROM ventas.alertaspro WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."'");
							if ($cn->num_rows($query) > 0) {
								echo "<div class='alert alet-block alert-info'>";
								echo "<ul>";
								while($result = $cn->ExecuteNomQuery($query)){
									echo "<li>";
									echo "<strong>".$result['sector']."</strong>";
									echo "<p>".$result['obser']."</p>";
									echo "</li>";
								}
								echo "</ul>";
								echo "</div>";
							}
							$cn->close($query);
						?>
					</div>
				</div>
			</div>
			
		</div>
		<div id="medit" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Editar Cantidad</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="radio inline"><input type="radio" id="rbtnve" name="rbtn"> Ventas</label>
					<label class="radio inline"><input type="radio" id="rbtnop" name="rbtn"> Operaciones</label>
					<input type="hidden" id="txtpro" value="<?php echo $_GET['pro']; ?>">
					<input type="hidden" id="txtsub" value="<?php echo $_GET['sub']; ?>">
					<input type="hidden" id="txtsec" value="<?php echo $_GET['sec']; ?>">
				</div>
				<div class="control-group">
					<label class="control-label" for="label">Codigo de Material</label>
					<div class="controls">
						<input type="text" class="span2" id="txtid">
					</div>
				</div>
				<div class="control-group">
					<label for="label" class="control-label">Descripcion</label>
					<div class="controls">
						<input type="text" class="span5" id="txtnom">
					</div>
				</div>
				<div class="control-group">
					<label for="label" class="control-label">Medida</label>
					<div class="controls">
						<input type="text" class="span5" id="txtmed">
					</div>
				</div>
				<div class="control-group">
					<label for="label" class="control-label">Cantidad</label>
					<div class="controls">
						<input type="number" class="span2" id="txtcant">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-primary" onClick="edit();">Guardar Cambios</button>
			</div>
		</div>
		<div id="mdel" class="modal fade in alert-danger hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<div class="control-group">
					<strong>Eliminar Material!</strong>
					<p>
						Realmente desea eliminar el material?
					</p>
					<div class="controls">
						<label class="radio inline"><input type="radio" id="rbtnvd" name="rbtnd"> Ventas</label>
						<label class="radio inline"><input type="radio" id="rbtnod" name="rbtnd"> Operaciones</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-danger" onClick="del();">Eliminar</button>
			</div>
		</div>
	<div id="alist" class="modal fade in hide">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">x</a>
			<h4>Aprobar Lista de Proyecto</h4>
		</div>
		<div class="modal-body">
			<p>
			<strong>Desea aprobar la lista?</strong>
			<!--<p>Realmente Desea aprobar la lista de ventas</p>-->
			<p>Debera ingresar la contraseña del administrodor o del gerente.</p>
			</p>
			<div class="control-group">
				<label class="radio inline"> <input type="radio" name="r" id="rv"> Ventas</label>
				<label class="radio inline"> <input type="radio" name="r" id="ro"> Operaciones</label>
				<hr class="hs">
				<label for="label"><b>Contraseña:</b></label>
				<div class="controls">
					<input type="password" id="txtpass" name="txtpass" />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn pull-left" data-dismiss="modal">Cancelar</button>
			<button class="btn btn-inverse pull-right" onClick="aprobar();">Aceptar</button>
		</div>
	</div>
	<div id="fullscreen-icr">
		<div id="loading-icr">
			Se Aprobo es sector <?php echo $_GET['sec']; ?>
		</div>
	</div>
	
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>
