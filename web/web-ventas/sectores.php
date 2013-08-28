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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/autocomplete.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/sectores.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<style>
		#upload{
			background-color: #313437;
			background-image:-webkit-gradient(linear, 0 0, 0 100%, from(#373a3d), to(#313437));
			background-image:-webkit-linear-gradient(#373a3d, #313437);
			background-image:-moz-linear-gradient(#373a3d, #313437);
			background-image:-o-linear-gradient(#373a3d, #313437);
			background-image:linear-gradient(#373a3d, #313437);
			box-shadow: 0 0 1em rgba(0,0,0,.3);
			font-family: 'PS Sans Narrow', sans-serif;
			padding: 1em;
		}
		#drop , #plano{
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
		#drop a{
			background-color: #007a96;
			border-radius: .5em;
			color : #FFF;
			cursor: pointer;
			display: inline-block;
			line-height: 1;
			padding: .8em;
		}
		#drop a:hover{
			background-color: #0986a3;
		}
		#drop input{
			display: none;
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
		.ui-autocomplete{
			max-height: 16em;
			overflow-y: auto;
			overflow-x: hidden;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="proyecto.php">Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="admin-project.php?id=<?php echo $_GET['proid']; ?>">Proyecto Admin</a>
				<span class="divider">/</span>
			</li>
			<li class="active">Sectores</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h2>Sector de Proyecto</h2>
			<div class="row show-grid">
				<div class="span5">
				<div class="row show-grid">
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
										WHERE proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ");
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
										WHERE proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(nroplano) LIKE TRIM('".$_GET['nropla']."') ");
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
				</div>
				<?php
				$dir = "";
				$file = -1;
				if ($_GET['sub'] != '') {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['proid']."/".$_GET['sub']."/".$_GET['nropla'].".pdf")) {
						$dir = "/web/project/".$_GET['proid']."/".$_GET['sub']."/".$_GET['nropla'].".pdf";	
						$file = 1;
					}
				}else{
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['proid']."/".$_GET['nropla'].".pdf")) {
						$dir = "/web/project/".$_GET['proid']."/".$_GET['nropla'].".pdf";
						$file = 1;
					}
				}
				#echo $file;
				if ($file != 1) {
				?>
				<div class="span6">
					<div >
						<h4>Subir Plano</h4>
						<form id="upload" method="post" action="upload.php" enctype="multipart/form-data">
							<div id="drop">
								Click Aqui
								<a id="bro" href="javascript:open();">Browse</a>
								<input type="file" name="upl" id="upl" onChange="uploadAjax();" accept="application/pdf" runat="server" />
							</div>
						</form>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php if ($file == 1){ ?>
			<div class="row show-grid">
				<div class="span12">
					<div id="plano">
						<div class="btn-group pull-left">
							<button class="btn" onClick="openfull();"><i class="icon-eye-open"></i></button>
							<button class="btn" onClick="resizesmall();"><i class="icon-resize-small"></i></button>
							<button class="btn" onClick="resizefull();"><i class="icon-resize-full"></i></button>
						</div>
						<!--<a class="media" href="">PDF File</a>-->
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
			              <div class="row show-grid">
			              	
			              	<div class="span11 well">
			              		<div class="control-group">
			              		<div class="btn-group">
			              			 <button class="btn btn-success t-d" onClick="showaddmat();" <?php if($_GET['status'] == '55' || $_GET['status'] == '59'){ echo "DISABLED";} ?>><i class="icon-plus"></i> Agregar material</button>
			              			 <button class="btn btn-success t-d" onClick="openfile();" <?php if($_GET['status'] == '55' || $_GET['status'] == '59'){ echo "DISABLED";} ?>><i class="icon-file"></i> Agregar Archivo</button>
			              		</div>

			              		</div>
			              		<div id="maddmat" class="row show-grid span10 well c-yellow-light hide">
			              			<a href="javascript:closeaddmat();" class="close">&times;</a>
									<div class="span6">
										<div class="control-group info">
											<label for="controls" class="t-info">Nombre o Descripción</label>
											<div class="controls">
												<div class="ui-widget">
												<select name="cbomat" id="combobox" class="span4 hide">
													<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT DISTINCT TRIM(matnom) as matnom FROM admin.materiales ORDER BY matnom ASC;");
													if ($cn->num_rows($query) > 0) {
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
									<div class="span3">
										<div class="control-group">
											<div class="controls">
												<label class="t-info">Solicitar nuevo material</label>
												<button class="btn btn-success" onClick="showsol();"><i class="icon-plus"></i></button>
											</div>
										</div>
									</div>
									<div class="span6">
											<div class="control-group info">
												<label for="controls" class="t-info">Medida</label>
												<div id="matmed" class="controls">
													
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group info">
												<label for="controls" class="t-info">Resumen</label>
												<div class="controls well c-red t-white">
													<div class="row">
														<div class="row">
															<div class="row">
																<div id="data"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group info">
												<label for="controls" class="t-info">Cantidad</label>
												<div class="controls">
													<input type="number" class="span2" min="0" id="cant">
												</div>
											</div>
										</div>
									<div class="span4">
										<div class="controls">
											<button class="btn btn-info t-d" onClick="savemat();"><i class="icon-plus"></i> Agregar</button>
										</div>
									</div>
								</div>
			              		<table class="table table-hover table-bordered table-condensed">
			              			<thead>
			              				<tr>
			              					<th>Item</th>
			              					<th>Codigo</th>
			              					<th>Descripcion</th>
			              					<th>Medida</th>
			              					<th>Undidad</th>
			              					<th>Cantidad</th>
			              					<th>Modificar</th>
			              					<th>Eliminar</th>
			              				</tr>
			              			</thead>
			              			<tbody id="tbld">
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
			              							echo "<tr class='c-yellow-light'>";
			              							echo "<td id='tc'>".$i++."</td>";
			              							echo "<td>".$result['materialesid']."</td>";
			              							echo "<td>".$result['matnom']."</td>";
			              							echo "<td>".$result['matmed']."</td>";
			              							echo "<td id='tc'>".$result['matund']."</td>";
			              							echo "<td id='tc'>".$result['cant']."</td>";
			              							if ($_GET['status'] == '59' || $_GET['status'] == '55'){}else{?>
			              								<td id="tc"><a href="javascript:showedit('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace('"', '', $result['matmed']); ?>',<?php echo $result['cant']; ?>);"><i class="icon-edit"></i></a></td>
														<td id="tc"><a href="javascript:delmat('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace('"', '', $result['matmed']); ?>');"><i class="icon-trash"></i></a></td>	
			              							<?php
			              								}
			              							echo "</tr>";
			              						}
			              					}
			              					$cn->close();
			              				?>
			              			</tbody>
			              		</table>
			              	</div>
			              	<div class="span12">
					<div class="well c-yellow-light">
						<h4 class="t-warning">Observaciones de Operaciones</h4>
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT sector,obser FROM ventas.alertaspro WHERE proyectoid LIKE '".$_GET['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['nropla']."'");
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
		<div id="mmat" class="modal fade in c-blue-light t-info hide">
			<div class="modal-header">
				<a href="" class="close" data-dismiss="modal">&times;</a>
				<h4>Agregar Nuevo Material</h4>
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Material ID</label>
							<div class="controls">
								<input type="text" id="matid" class="span2" maxlength="15">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group">
							<label class="control-label">Descripcion</label>
							<div class="controls">
								<input type="text" id="nom" class="span5">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group">
							<label class="control-label">Medida</label>
							<div class="controls">
								<input type="text" id="med" class="span5">
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Unidad</label>
							<div class="controls">
								<select name="und" id="und" class="span2 t-info">
									<?php
									$cn = new PostgreSQL();
									$query =  $cn->consulta("SELECT DISTINCT uninom FROM admin.unidad");
									if ($cn->num_rows($query) > 0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											echo "<option value='".$result['uninom']."'>".$result['uninom']."</option>";
										}
									}
									$cn->close($query);
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Marca</label>
							<div class="controls">
								<input type="text" class="span2" id="mar">
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Modelo</label>
							<div class="controls">
								<input type="text" class="span2" id="mod">
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Acabado</label>
							<div class="controls">
								<input type="text" class="span2" id="aca">
							</div>
						</div>
					</div>
					<div class="span5">
						<button class="btn btn-warning t-d" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
						<button class="btn btn-info t-d pull-right" onClick="savenmat();"><i class="icon-ok"></i> Guardar</button>
					</div>
				</div>
			</div>
		</div>
		<div id="modmat" class="modal fade in c-blue-light t-info hide">
			<div class="modal-header">
				<a href="" class="close" data-dismiss="modal">&times;</a>
				<h3>Modificar Material</h3>
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group info">
						<label for="controls" class="control-label">Materiales ID</label>
						<div class="controls">
							<input type="text" class="span2" id="mid" DISABLED />
						</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
						<label for="controls" class="control-label">Nombre Material</label>
						<div class="controls">
							<input type="text" class="span5 t-info" id="mnom" DISABLED />
						</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
						<label for="controls" class="control-label">Medida Material</label>
						<div class="controls">
							<input type="text" class="span5" id="mmed" DISABLED />
						</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
						<label for="controls" class="control-label">Cantidad</label>
						<div class="controls">
							<input type="number" class="span2" id="mcant" min="0" />
						</div>
						</div>
					</div>
					<div class="span5">
						<div class="controls">
							<button class="btn btn-warning" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
							<button class="btn btn-info pull-right" onClick="editmat();"><i class="icon-ok"></i> Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" id="pro" value="<?php echo $_GET['proid']; ?>" />
		<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>" />
		<input type="hidden" id="sec" value="<?php echo $_GET['nropla']; ?>">
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
