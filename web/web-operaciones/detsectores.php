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
	<title>Sectores del Proyectos</title>
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
	<script src="js/sectores.js"></script>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<?php
		$proid = $_REQUEST['proid'];
		$plane = $_REQUEST['nropla'];
		$subpro = $_REQUEST['subpro'];
		
		$sql = "SELECT COUNT(*) FROM operaciones.matmetrado ";

		if ($subpro != "") {
			$sql .= " WHERE proyectoid LIKE '$proid' AND TRIM(sector) LIKE TRIM('$plane')";
		}elseif ($subpro == "") {
			$sql .= " WHERE proyectoid LIKE '$proid' AND TRIM(sector) LIKE TRIM('$plane')";
		}

		$c = new PostgreSQL();
		$q = $c->consulta($sql);
		if ($c->num_rows($q) > 0) {
			$r = $c->ExecuteNomQuery($q);
		}
		$c->close($q);
	?>
	<header></header>
	<section>
		<div class="container well">
			<h4>Sector de Proyecto</h4>
			<hr class="hs">
			<div class="row show-grid">
				<div class="span12">
					<h5 id="plane"><?php echo $_REQUEST['nropla']; ?></h5>
					<h5 id="proid"><?php echo $_REQUEST['proid']; ?></h5>
					<ul id="tab" class="nav nav-tabs">
			            <li class="active"><a href="#mat" data-toggle="tab">Materiales</a></li>
			            <li class=""><a href="#eyh" data-toggle="tab">Equipos y Herramientas</a></li>
			            <li class="">
			              <a href="#mo" data-toggle="tab">Mano de Obra</a>
			            </li>
			        </ul>
			        <div id="myTabContent" class="tab-content">
			            <div class="tab-pane fade active in" id="mat">
			              <div class="row">
			              	
			              	<div class="span11 well">
			              		<div class="controls">
			              		<div class="button-group">
			              			<?php
			              			if ($r[0] >= 1) {
			              				?>
			              				<button class="btn" onClick="openaddm();"><i class="icon-plus"></i> Agregar material</button>
										<button class="btn" onClick="openfile();"><i class="icon-plus"></i> Agregar Archivo</button>
									<?php
			              			}else{
			              			?>
										<button class="btn" onClick="viewlist();"><i class="icon-ok"></i> Ver Lista de Venta</button>
										<!--<button class="btn" onClick="openfile();"><i class="icon-remove"></i> Rechazar</button>-->
				              		<?php } ?>
			              		</div>
			              	</div>
			              		<table class="table table-hover table-bordered">
			              			<thead>
			              				<tr>
			              					<th>Item</th>
			              					<th>Codigo</th>
			              					<th>Descripcion</th>
			              					<th>Medida</th>
			              					<th>Undidad</th>
			              					<th>Cantidad</th>
			              					<th>Editar</th>
			              					<th>Elimnar</th>
			              				</tr>
			              			</thead>
			              			<tbody>
			              				<?php											
											$cn = new PostgreSQL();
											$qsql = "";

											if ($r[0] >= 1) {
												$qsql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
														FROM operaciones.matmetrado d INNER JOIN admin.materiales m
														ON d.materialesid LIKE m.materialesid
														INNER JOIN ventas.proyectos p
														ON d.proyectoid LIKE p.proyectoid ";
							  					if ($subpro == "") {
							  						$qsql .= "WHERE d.proyectoid LIKE '".$proid."' AND TRIM(d.sector) LIKE TRIM('".$plane."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
							  					}elseif ($subpro != "") {
				              						$qsql .= "WHERE d.proyectoid LIKE '".$proid."' GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
				              					}
											
											
			              					$query = $cn->consulta($qsql);
			              					if ($cn->num_rows($query) > 0) {
			              						$i = 1;
			              						while ($result = $cn->ExecuteNomQuery($query)) {
			              							echo "<tr>";
			              							echo "<td id='tc'>".$i++."</td>";
			              							echo "<td>".$result['materialesid']."</td>";
			              							echo "<td>".$result['matnom']."</td>";
			              							echo "<td>".$result['matmed']."</td>";
			              							echo "<td id='tc'>".$result['matund']."</td>";
			              							echo "<td id='tc'>".$result['cant']."</td>";
			              							echo "<td id='tc'><a href='javascript:conedit(".$result['materialesid'].");'><i class='icon-pencil'></i></a></td>";
			              							echo "<td id='tc'><a href='javascript:delmat(".$result['materialesid'].");'><i class='icon-remove'></i></a></td>";
			              							echo "</tr>";
			              						}
			              					}
			              					}
			              					$cn->close();
			              				?>
			              			</tbody>
			              		</table>
			              	</div>
			              </div>
			            </div>
			            <div class="tab-pane fade" id="eyh">
			              <div class="row">
			              	<div class="span11" style="background-color: rgba(0,0,0,.5); border-radius: .5em;">
			              		<span>
			              			<h4 style="text-align: center;">No Disponible</h4>
			              		</span>
			              	</div>
			              </div>
			            </div>
						<div class="tab-pane fade" id="mo">
			             	<div class="row">
			              	<div class="span11" style="background-color: rgba(0,0,0,.5); border-radius: .5em;">
			              		<span>
			              			<h4 style="text-align: center;">No Disponible</h4>
			              		</span>
			              	</div>
			              </div>
			            </div>
					</div>
				</div>
			</div>
		</div>

		<div id="adda" class="modal fade in hide">
			<form method="POST" enctype="multipart/form-data" action="includes/incfile.php">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h4>Agragar Archivo</h4>
			</div>
			<div class="modal-body">
				<div class="well">
					<div class="control-group">
						<div class="control-label">
							<label for="label"><b>Leer Archivo</b></label>
						</div>
						<div class="controls">
							<input type="file" id="txtup" name="txtup" REQUIRED />
							<input type="hidden" name="nro" id="nro" value="<?php echo $_REQUEST['nropla']; ?>" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="Button" class="btn" onClick="closefile();">Cancelar</button>
				<button type="Submit" id="btns" name="btns" class="btn btn-primary">Leer Archivo</button>
			</div>
			</form>
		</div>
		<div id="vm" class="modal fade in hide span10" style="margin-left: -35%;">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Lista de Materiales de Ventas</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="txtproid" value="<?php echo $proid; ?>">
				<input type="hidden" id="txtplane" value="<?php echo $plane; ?>">
				<input type="hidden" id="txtsubpro" value="<?php echo $subpro; ?>">
				<?php
					$cn = new PostgreSQL();
					$qsql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
							FROM ventas.matmetrado d INNER JOIN admin.materiales m
							ON d.materialesid LIKE m.materialesid
							INNER JOIN ventas.proyectos p
							ON d.proyectoid LIKE p.proyectoid ";
							if ($subpro == "") {
								$qsql .= "WHERE d.proyectoid LIKE '".$proid."' AND TRIM(d.sector) LIKE TRIM('".$plane."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
							}elseif ($subpro != "") {
								$qsql .= "WHERE d.proyectoid LIKE '".$proid."' AND TRIM(d.subproyectoid) LIKE TRIM('$subpro') AND TRIM(d.sector) LIKE TRIM('".$plane."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
							}
					$query = $cn->consulta($qsql);
					if ($cn->num_rows($query) > 0) {
						?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>Item</th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>Medida</th>
								<th>Undidad</th>
								<th>Cantidad</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$j = 1;
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							echo "<td id='tc'>".$j++."</td>";
							echo "<td>".$result['materialesid']."</td>";
							echo "<td>".$result['matnom']."</td>";
							echo "<td>".$result['matmed']."</td>";
							echo "<td id='tc'>".$result['matund']."</td>";
							echo "<td id='tc'>".$result['cant']."</td>";
						}
					}
					$cn->close($query);
				?>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-primary" onClick="aproved();"><i class="icon-ok icon-white"></i> Aprobar</button>
				<button class="btn btn-primary" onClick="addmat();"><i class="icon-list icon-white"></i> Hacer Mi Lista</button>
			</div>
		</div>
		<div id="edit" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Editar Cantidad de Material</h4>
			</div>
			<div class="modal-body well">
				<div class="control-group">
					<div class="control-label">
						<label for="label"><b>Codigo</b></label>
					</div>
					<div class="controls">
						<input type="text" id="txtmatid" class="span2">
						<input type="hidden" id="txtpro" value="<?php echo $proid; ?>">
						<input type="hidden" id="txtsub" value="<?php echo $subpro; ?>">
						<input type="hidden" id="txtsec" value="<?php echo $plane; ?>">
					</div>
					<div class="control-label">
						<label for="label"><b>Nombre</b></label>
					</div>
					<div class="controls">
						<input type="text" class="span4" id="txtnom">
					</div>
					<div class="control-label">
						<label for="label"><b>Medida</b></label>
					</div>
					<div class="controls">
						<input type="text" class="span4" id="txtmed">
					</div>
					<div class="control-label">
						<label for="label"><b>Cantidad</b></label>
					</div>
					<div class="controls">
						<input type="number" class="span2" id="txtcant">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Cancelar</button>
				<button class="btn btn-primary" onClick="editope();">Guardar Cambios</button>
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