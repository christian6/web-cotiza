<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}

include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Suministro de Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<script src="js/suministro-pro.js"></script>
</head>
<body>
	<?php include("include/menu-al.inc") ?>
	<header></header>
	<section>
		<div class="container well">
			<h3 class="t-info">Suministro de Proyectos y Pedidos</h3>
			<div class="span8" style="display: inline-table;">
				<div style="" class="alert alert-error span2">
					Stock Actual insuficiente.
				</div>
				<div style="" class="alert alert-success span2">
					Stock Actual normal.
				</div>
			</div>
			<br>
			<br><br>
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT proyectoid, descripcion FROM ventas.proyectos WHERE esid LIKE '55';");
				if ($cn->num_rows($query) > 0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<h4 class='t-warning'>".$result['descripcion']."</h4>";
						?>
						<table class="table table-condensed table-hover">
							<tbody>
								<?php
									$c = new PostgreSQL();
									$q = $c->consulta("SELECT DISTINCT p.materialesid,m.matnom,m.matmed,m.matund,sum(p.cant) as cant FROM operaciones.metproyecto p 
														INNER JOIN admin.materiales m ON p.materialesid LIKE m.materialesid 
														WHERE proyectoid LIKE '".$result['proyectoid']."' AND flag LIKE '1'
														GROUP BY p.materialesid,m.matnom,m.matmed,m.matund");
									$i = 1;
									if ($cn->num_rows($q) > 0) {
										while ($res = $c->ExecuteNomQuery($q)) {
											$cs = new PostgreSQL();
											$qs = $cs->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$res['materialesid']."')");
											if ($cs->num_rows($qs) > 0) {
												$r = $cs->ExecuteNomQuery($qs);
											}
											$cs->close($q);
											if ($r[0] < $res['cant']) {
												echo "<tr class='c-red-light'>";
											}else{
												echo "<tr class='c-green-light'>";
											}
											$cant = $res['cant'];
											$stk = $r[0];
											//echo $cant.' - '.$stk;
											//echo "<br>";
											if ($stk < $cant) { $sum = ($cant - $stk); }else{ $sum = ($stk - $cant); }
											echo "<td>".$i++."</td>";
											echo "<td class='span2'>".$res['materialesid']."</td>";
											echo "<td class='span6'>".$res['matnom']."</td>";
											echo "<td class='span4'>".$res['matmed']."</td>";
											echo "<td>".$res['matund']."</td>";
											echo "<td >".$res['cant']."</td>";
											if($r[0]!=''){echo "<td>".$r[0]."</td>";}else{echo "<td>-</td>";}
											echo "<td><button class='btn btn-mini btn-warning' onClick=addmatsuminsitro('".$res['materialesid']."',".$sum.");><i class='icon-plus'></i></button></td>";
											echo "</tr>";
										}
									}
									$cn->close();
									?>
								</tbody>
						</table>
									<?php
					}
				}
				$cn->close($query);
				//obteniendo los nros de pedido
				echo "<h4 class='t-warning'>Detalles de Pedidos</h4>";
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT nropedido FROM almacen.pedido WHERE esid IN ('35','37'); ");
				if ($cn->num_rows($query) > 0) {
					$cpe = '';
					while ($result = $cn->ExecuteNomQuery($query)) {
						$cpe .= "'".$result['nropedido']."',";
					}
				}
				$cn->close($query);
				$cpe = substr($cpe, 0,(strlen($cpe)-1));
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cantidad) as cantidad
									FROM almacen.detpedidomat d INNER JOIN admin.materiales m
									ON d.materialesid=m.materialesid
									INNER JOIN almacen.pedido p
									ON p.nropedido LIKE d.nropedido
									WHERE d.nropedido IN (".$cpe.")
									GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.auto;");
				if ($cn->num_rows($query) > 0) {
					$i = 1;
					echo "<table class='table table-condensed table-hover'>";
					echo "<tbody>";
					while ($result = $cn->ExecuteNomQuery($query)) {
						$cs = new PostgreSQL();
						$qs = $cs->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$res['materialesid']."')");
						if ($cs->num_rows($qs) > 0) {
							$r = $cs->ExecuteNomQuery($qs);
						}
						if ($r[0] < $result['cantidad']) {
							echo "<tr class='c-red-light'>";
						}else{
							echo "<tr class='c-green-light'>";
						}
						$cant = $result['cantidad'];
						$stk = $r[0];
						if ($stk < $cant) { $sum = ($cant - $stk); }else{ $sum = ($stk - $cant); }
						echo "<td>".$i++."</td>";
						echo "<td class='span2'>".$result['materialesid']."</td>";
						echo "<td class='span6'>".$result['matnom']."</td>";
						echo "<td class='span4'>".$result['matmed']."</td>";
						echo "<td>".$result['matund']."</td>";
						echo "<td >".$result['cantidad']."</td>";
						if($r[0]!=''){echo "<td>".$r[0]."</td>";}else{echo "<td>-</td>";}
						echo "<td><button class='btn btn-mini btn-warning' onClick=addmatsuminsitro('".$result['materialesid']."',".$sum.");><i class='icon-plus'></i></button></td>";
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table";
				}
				$cn->close($query);
			?>
			<br>
			<h4 class="t-success">Detalle de Suministro</h4>
			<table class="table table-condensed table-hover">
				<caption>
					<button class="btn btn-small btn-success pull-left t-d" OnClick="showsum();"><i class="icon-ok"></i> Orden de Suministro</button>
				</caption>
				<thead>
					<th></th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Editar</th>
					<th>Eliminar</th>
				</thead>
				<tbody id="tdet">
				</tbody>
			</table>
		</div>
		<div id="mos" class="modal fade in hide t-warning c-yellow-light">
			<div class="modal-header">
				<a data-dismiss="modal" class="close">&times;</a>
				<h4>Generar Orden de Suministro</h4>
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="control-label">Almacen</label>
							<div class="controls">
								<select name="cboal" id="cboal" class="span2">
									<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("SELECT * FROM admin.almacenes");
									if ($cn->num_rows($query) > 0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
										}
									}
									$cn->close($query);
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="control-label">Fecha Requerida</label>
							<div class="controls">
								<input type="text" class="span2" id="fec">
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="control-label">DNI</label>
							<div class="controls">
								<input type="text" class="span2" id="dni" value="<?php echo $_SESSION['dni-icr']; ?>" DISABLED />
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group info">
							<label for="controls" class="control-label">Nombres</label>
							<div class="controls">
								<input type="text" id="nom" class="span3" value="<?php echo $_SESSION['nom-icr']; ?>" DISABLED />
							</div>
						</div>
					</div>
					<div class="span5">
						<button class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
						<button class="btn btn-warning t-d pull-right" OnClick="gensuministro();"><i class="icon-ok"></i> Guardar Cambios</button>
					</div>
				</div>
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