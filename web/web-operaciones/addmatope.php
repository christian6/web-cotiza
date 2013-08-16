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
	<title>Agregar Materiales</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="../web-almacen/js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/med.js"></script>
	<style>
		#fullpdf{
			display: none;
			margin-top: 5em;
			position: absolute;
		}
		#fullscreen-icr button{
			position: absolute;
			top: 3em;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header>
		<input type="hidden" id="pro" value="<?php echo $_GET['pro']; ?>">
		<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>">
		<input type="hidden" id="sec" value="<?php echo $_GET['sec']; ?>">
	</header>
	<section>
		<?php
				$dir = "";
				$file = -1;
				if ($_GET['sub'] != '') {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf";	
						$file = 1;
					}
				}else{
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf";
						$file = 1;
					}
				}
			?>
		<div class="container well">
			<div class="row show-grid">
				<div class="span12">
					<h3>Agregar Materiales</h3>
					<h5>Proyecto: <?php echo $_GET['pro']; ?> Sub Proyecto: <?php echo $_GET['sub']; ?>  Sector: <?php echo $_GET['sec']; ?></h5>
					<div class="btn-group">
						<a href="detsectores.php?proid=<?php echo $_GET['pro']; ?>&subpro=<?php echo $_GET['sub']; ?>&nropla=<?php echo $_GET['sec']; ?>" class="btn btn-success t-d"><i class="icon-arrow-left"></i> volver</a>
						<button class="btn btn-info t-d" onClick="searchbtn();"><i class="icon-search"></i> Buscar Medidas</button>
						<button class="btn btn-info t-d" onClick="list();"><i class="icon-refresh"></i> Actualizar</button>
						<button class="btn" onClick="openfull();"><i class="icon-eye-open"></i> Ver plano</button>
					</div>
					<div class="row show-grid">
						<div class="span12">
							<h5>Datos Generales</h5>
							<div class="row show-grid">
								<div class="span5">
									<div class="control-group">
										<label for="controls" class="label label-info">Descripción : </label>
										<div class="controls">
											<div class="ui-widget">
												<select id="combobox" class="span5" onclick="showmed();" style="display: none;">
												<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT DISTINCT m.matnom FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid AND i.anio LIKE '".date("Y")."' ORDER BY matnom ASC");
													if ($cn->num_rows($query)>0) {
														while ($result = $cn->ExecuteNomQuery($query)) {
															echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
														}
													}
													$cn->close($query);
												?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="control-group">
										<label for="controls" class="label label-info">Medida : </label>
										<div class="controls" id="med">
											
										</div>
									</div>
								</div>
							</div>
							<div class="row show-grid">
								<div class="span5">
									<div class="control-group">
										<label for="controls" class="label label-info">Resumen : </label>
										<div class="controls">
											<div id="data" class="well c-gl"></div>
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="controls" class="label label-info">Cantidad : </label>
										<div class="controls">
											<input type="number" id="cant" class="span2">
										</div>
									</div>
								</div>
								<div class="span2">
									<div class="control-group">
										<label for="controls" class="label label-info">Acciones : </label>
										<div class="controls">
											<button class="btn btn-danger t-d" onClick="savemat();"><i class="icon-plus"></i> agregar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span12">
							<div id="list">
								<div class="well"></div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="medit" class="modal fade in hide" >
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h5>Editar Cantidad</h5>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label for="controls" class="label label-info">Codigo :</label>
					<div class="controls">
						<input type="text" class="span2" id="matid" DISABLED />
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-info">Nombre :</label>
					<div class="controls">
						<input type="text" class="span5" id="matnom" DISABLED />
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-info">Medida :</label>
					<div class="controls">
						<input type="text" class="span5" id="matmed" DISABLED />
					</div>
				</div>
				<div class="control-group">
					<label for="controls" class="label label-info">Cantidad :</label>
					<div class="controls">
						<input type="number" class="span2" id="cantedit" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-success t-d" onClick="edit();"><i class="icon-ok"></i> Guardar Cambios</button>
			</div>
		</div>
		<div id="mdel" class="modal fade in hide">
			<div class="modal-header">
				<a data-dismiss="modal" class="close">x</a>
				<h5 class="t-error">Eliminar Material</h5>
				<p class="t-error">
					Realmente deseas eliminar el material <strong id="matdel"></strong>?.
					<input type="hidden" id="matdel" value="" />
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-danger t-d" onClick="eliminar();"><i class="icon-ok"></i> Eliminar?</button>
			</div>
		</div>
	</section>
	<div id="fullscreen-icr" class="pull-center">
		<button class="btn btn-danger" onClick="closefull();"><i class="icon-remove"></i></button>
		<iframe id="fullpdf" src="<?php echo $dir; ?>" width="100%" height="90%" frameborder="0">
		</iframe>
	</div>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>