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
				<button class="btn btn-success"  onClick="viewapro();"><i class="icon-ok"></i> Aprobar</button>
				<!--<button class="btn btn-success" onClick="javascript:window.close();"><i class="icon-resize-small"></i> Salir</button>-->
			</div>
			<hr class="hs">
			<table class="table table-bordered table-hover">
				<thead>
					<th></th>
					<!--<th>Item</th>-->
					<th>Codigo</th>
					<th>Descripcion</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Ventas</th>
					<th>Operaciones</th>
					<!--<th>Editar</th>
					<th>Eliminar</th>-->
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$sql = "SELECT DISTINCT v.materialesid,m.matnom,m.matmed,m.matund 
							FROM admin.vw_metradobo v INNER JOIN admin.materiales m
							ON v.materialesid LIKE m.materialesid
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
						$com = array('"',"'");
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							//echo "<td id='tc'><input type='checkbox' name='matids' id='".$result['materialesid']."'></td>";
							echo "<td id='tc'>".$i++."</td>";
							echo "<td>".$result['materialesid']."</td>";
							echo "<td>".$result['matnom']."</td>";
							echo "<td>".$result['matmed']."</td>";
							echo "<td id='tc'>".$result['matund']."</td>";
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
								echo "<td id='tc'>".$cv."</td>";
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
								echo "<td id='tc'>".$co."</td>";
							}else{
								echo "<td> - </td>";
							}
							$c->close($qs);

							$c->close($q);
							?>
							<!--<td id='tc'><button class="btn btn-info" onClick="viewedit('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace($com,'',$result['matmed']) ?>');"><i class="icon-edit"></i></button></td>
							<td id='tc'><button class="btn btn-danger" onClick="viewdelete('<?php echo $result['materialesid']; ?>');"><i class="icon-remove"></i></button></td>-->
							<?php
							echo "</tr>";
						}
					}
					$cn->close($query);
					?>
				</tbody>
			</table>
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
