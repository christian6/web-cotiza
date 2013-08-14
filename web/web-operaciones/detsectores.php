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
	<style>
		#plano{
			background-color: #2E3134;
			border: .3em dashed gray;
			border-radius: .3em;
			color: #7f858a;
			font-size: 1em;
			font-weight: bold;
			padding: .5em;
			text-align: center;
			text-transform: uppercase;
		}
		#fullpdf{
			display: none;
			margin-top: 5em;
			position: absolute;
			/*top: 1em;*/
		}
		#fullscreen-icr button{
			position: absolute;
			top: 3em;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<?php
		$proid = $_GET['proid'];
		$plane = $_GET['nropla'];
		$subpro = $_GET['subpro'];
		
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
			<h2>Sector de Proyecto</h2>
			<div class="row">
						<dl class="dl-horizontal" >
						<dt>Proyecto </dt>
						<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT p.descripcion FROM ventas.proyectos p 
												WHERE p.proyectoid LIKE '".$_GET['proid']."' ");
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<dd>".$result[0]."</dd>";
							}
						}else{
							echo "<dd> &nbsp;</dd>";
						}
						$cn->close($query);
						?>
						<dt>Subproyecto </dt>
						<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT subproyecto FROM ventas.subproyectos
												WHERE proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['subpro']."' ");
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<dd>".$result[0]."</dd>";
							}
						}else{
							echo "<dd> &nbsp;</dd>";
						}
						$cn->close($query);
						?>
						<dt>Sector </dt>
						<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT sector FROM ventas.sectores
												WHERE proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['subpro']."' AND TRIM(nroplano) LIKE TRIM('".$_GET['nropla']."') ");
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<dd>".$result[0]."</dd>";
							}
						}else{
							echo "<dd> &nbsp;</dd>";
						}
						$cn->close($query);
						?>
					</dl>
					</div>
			<?php
				$dir = "";
				$file = -1;
				if ($_GET['subpro'] != '') {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['proid']."/".$_GET['subpro']."/".$_GET['nropla'].".pdf")) {
						$dir = "/web/project/".$_GET['proid']."/".$_GET['subpro']."/".$_GET['nropla'].".pdf";	
						$file = 1;
					}
				}else{
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['proid']."/".$_GET['nropla'].".pdf")) {
						$dir = "/web/project/".$_GET['proid']."/".$_GET['nropla'].".pdf";
						$file = 1;
					}
				}
			?>
			<?php if ($file == 1){ ?>
			<div class="row show-grid">
				<div class="span12">
					<div id="plano">
						<div class="btn-group pull-left">
							<button class="btn" onClick="openfull();"><i class="icon-eye-open"></i></button>
							<button class="btn" onClick="resizesmall();"><i class="icon-resize-small"></i></button>
							<button class="btn" onClick="resizefull();"><i class="icon-resize-full"></i></button>
						</div>
						<iframe id="vpdf" src="<?php echo $dir; ?>" width="100%" height="400" frameborder="1"></iframe>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="row show-grid">
				<div class="span12">
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
										<!--<button class="btn btn-info t-d" onClick="viewlist();"><i class="icon-list"></i> Ver Lista de Venta</button>
										<button class="btn" onClick="openfile();"><i class="icon-remove"></i> Rechazar</button>-->
										<div class="alert alert-success">
											<p>
												<i class="icon-info-sign"></i> <strong>Lista de Ventas.</strong>
											</p>
											<button class="btn btn-success t-d" onClick="aproved();"><i class="icon-ok"></i> Aprobar</button>
											<button class="btn"></button>
											<button class="btn btn-success t-d" onClick="addmat();"><i class="icon-list"></i> Hacer mi lista</button>
										</div>
										
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
				              				}else{
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
					//echo $qsql;
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