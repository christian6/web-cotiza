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
	<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />-->
    	<!--<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>-->
    <script type="text/javascript" src="../web-almacen/js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/sectores.js"></script>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
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
			              			 <button class="btn" onClick="openaddm();"><i class="icon-plus"></i> Agregar material</button>
			              			 <button class="btn" onClick="openfile();"><i class="icon-plus"></i> Agregar Archivo</button>
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
			              				</tr>
			              			</thead>
			              			<tbody>
			              				<?php
			              					$proid = $_REQUEST['proid'];
			              					$plane = $_REQUEST['nropla'];
			              					$subpro = $_REQUEST['sub'];
			              					$cn = new PostgreSQL();
			              					$qsql = "";
			              					$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
			              											FROM ventas.matmetrado d INNER JOIN admin.materiales m
			              											ON d.materialesid LIKE m.materialesid
			              											WHERE d.proyectoid LIKE '".$_GET['proid']."' AND TRIM(d.subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['nropla']."'
			              											GROUP BY d.materialesid,m.matnom,m.matmed,m.matund	
			              											");
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
