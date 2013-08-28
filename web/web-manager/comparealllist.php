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
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="../modules/md5-min.js"></script>
	<script src="js/comparar.js"></script>
</head>
<body>
	<?php include("includes/menu-manager.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h4>Comparar Lista Ventas y Operaciones</h4>
			<div class="btn-group">
				<button class="btn btn-success t-d"  onClick="viewapro();"><i class="icon-ok"></i> Aprobar</button>
				<button class="btn btn-info t-d" onClick="editpre();"><i class="icon-edit"></i> Editar Precio</button>
				<button class="btn btn-info t-d" onClick="refreshpre();"><i class="icon-refresh"></i> Ver Precio</button>
				<button class="btn btn-success t-d" onClick="javascript:location.href='sectores.php?proid=<?php echo $_GET['pro']; ?>'"><i class="icon-arrow-left"></i> Volver</button>
				<!--<button class="btn btn-success" onClick="javascript:window.close();"><i class="icon-resize-small"></i> Salir</button>-->
			</div>
			<hr class="hs">
			<table class="table table-bordered table-condensed table-hover">
				<thead>
					<th></th>
					<!--<th>Item</th>-->
					<th>Codigo</th>
					<th>Descripcion</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Precio</th>
					<th>Ventas</th>
					<th>Operaciones</th>
					<!--<th>Editar</th>
					<th>Eliminar</th>-->
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$sql = "SELECT DISTINCT v.materialesid,m.matnom,m.matmed,m.matund,i.precio
							FROM admin.vw_metradobo v INNER JOIN admin.materiales m
							ON v.materialesid LIKE m.materialesid
							INNER JOIN almacen.inventario i
							ON v.materialesid LIKE i.materialesid AND almacenid like '0001' AND i.anio like extract(year from now())::char(4)
							WHERE ";
					if ($_GET['sub'] == "") {
						$sql .= "v.proyectoid LIKE '".$_GET['pro']."'";
					}else if($_GET['sub'] != ""){
						$sql .= "v.proyectoid LIKE '".$_GET['pro']."' AND TRIM(v.subproyectoid) LIKE '".$_GET['sub']."' ";
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
							echo "<td id='tc'><input type='text' class='input-mini' style='height:.8em;' name='snpre' value='".$result['precio']."' DISABLED></td>";
							$c = new PostgreSQL();
							$qv = "SELECT SUM(cant) as cant FROM ventas.matmetrado WHERE ";
							if ($_GET['sub'] == "") {
								$qv .= "proyectoid LIKE '".$_GET['pro']."' AND materialesid LIKE '".$result['materialesid']."'";
							}else if($_GET['sub'] != ""){
								$qv .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND materialesid LIKE '".$result['materialesid']."'";
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
								$qo .= "proyectoid LIKE '".$_GET['pro']."' AND materialesid LIKE '".$result['materialesid']."'";
							}else if($_GET['sub'] != ""){
								$qo .= "proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND materialesid LIKE '".$result['materialesid']."'";
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
							
							echo "</tr>";
						}
					}
					$cn->close($query);
					?>
				</tbody>
			</table>
			<div class="row show-grid">
				<div class="span6">
					<div class="well c-green-light t-info">
						<h4>Info ventas</h4>
						<dl class="dl-horizontal">
							<dt>Total Precio</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on"><i class="icon-tag"></i></span>
									<input type="text" class="span2" id="opv" value="<?php echo $vpr; ?>" DISABLED>
								</div>
							</dd>
							<dt>Precio Modificado</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on"><i class="icon-tags"></i></span>
									<input type="text" class="span2" id="vmpre" DISABLED>
								</div>
							</dd>
						</dl>
					</div>
				</div>
				<div class="span6">
					<div class="well c-green-light t-info">
						<h4>Info Operaciones</h4>
						<dl class="dl-horizontal">
							<dt>Total Precio</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on"><i class="icon-tag"></i></span>
									<input type="text" class="span2" id="opp" value="<?php echo $opr; ?>" DISABLED/>
								</div>
							</dd>
							<dt>Precio Modificado</dt>
							<dd>
								<div class="input-prepend">
									<span class="add-on"><i class="icon-tags"></i></span>
									<input type="text" class="span2" id="ompre" DISABLED>
								</div>
							</dd>
						</dl>
					</div>
				</div>
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
			<p>Debera ingresar la contraseña del administrador o del gerente.</p>
			</p>
			<div class="control-group">
				<input type="hidden" id="txtpro" value="<?php echo $_GET['pro']; ?>">
				<input type="hidden" id="txtsub" value="<?php echo $_GET['sub']; ?>">
				<label class="radio inline"> <input type="radio" name="r" id="rv"> Ventas</label>
				<label class="radio inline"> <input type="radio" name="r" id="ro"> Operaciones</label>
				<label for="label"><b>Usuario:</b></label>
				<div class="controls">
					<select name="cboadmin" id="cboadmin">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT e.empdni,e.empnom,TRIM(l.usere ) as user
													FROM admin.empleados e inner join admin.loginemp l
													ON l.empdni LIKE e.empdni
													WHERE cargoid = 1");
							if ($cn->num_rows($query) > 0) {
								while ($re = $cn->ExecuteNomQuery($query)) {
									echo "<option value='".$re['user']."'>".$re['empnom']."</option>";
								}
							}
							$cn->close($query);
						?>
					</select>
				</div>
				<label for="label"><b>Contraseña:</b></label>
				<div class="controls">
					<input type="password" id="txtpass" name="txtpass" />
				</div>
			</div>
			<div class="progress progress-striped span2 active hide">
				<div class="bar" style="width: 100%"></div>
			</div>
			<label for="label" id="lblaut" class="label label-warning hide">Autenticaón Fallida.</label>
			<label for="label" id="lblsu" class="label label-success hide">Se Aprobo Correctemente.</label>
		</div>
		<div class="modal-footer">
			<button class="btn pull-left" data-dismiss="modal">Cancelar</button>
			<button class="btn btn-inverse pull-right" onClick="aprobarall();">Aceptar</button>
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
